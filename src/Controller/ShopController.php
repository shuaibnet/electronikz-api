<?php

namespace App\Controller;

use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController
{
    /**
     * @Route("/{page}", name="product_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 10);
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function (Product $item) {
                    return $this->generateUrl('product_by_slug', ['slug' => $item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/product/{id}", name="product_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("item", class="App:Product")
     */
    public function item($item)
    {
        // It's the same as doing find($id) on repository
        return $this->json($item);
    }

    /**
     * @Route("/product/{slug}", name="product_by_slug", methods={"GET"})
     * The below annotation is not required when $item is typehinted with Product
     * and route parameter name matches any field on the Product entity
     * @ParamConverter("item", class="App:Product", options={"mapping": {"slug": "slug"}})
     */
    public function itemBySlug(Product $item)
    {
        // Same as doing findOneBy(['slug' => contents of {slug}])
        return $this->json($item);
    }

    /**
     * @Route("/add", name="product_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $product = $serializer->deserialize($request->getContent(), Product::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->json($product);
    }

    /**
     * @Route("/product/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Product $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
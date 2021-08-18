<?php

namespace App\Controller;

use http\Env\Request;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController
{
    private  const POSTS = [
        ['id'=> 1,
        'slug' => 'Fjallraven',
            'name'=> 'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops',
            'price'=> 109.95,
         ],
        ['id'=> 2,
            'slug' => 'tshirt',
            'name'=> 'Mens Casual Premium Slim Fit T-Shirts',
            'price'=> 19.95,
        ],
        ['id'=> 3,
            'slug' => 'jacket',
            'name'=> 'Mens Cotton Jacket',
            'price'=> 109.95,
        ],
    ];

    /**
     * @Route("/{page}", name="item_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page=1, Request $request)
    {
        $limit = $request->get('limit', 10);

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function (Shop $item) {
                    return $this->generateUrl('item_by_slug', ['slug' => $item->getSlug()]);
                }, self::POSTS)
            ]
        );
    }


    /**
     * @Route("/{id}", name="item_by_id", requirements={"id"="\d+"})
     */
    public function post($id)
    {
        return $this->json(
           self::POSTS [array_search($id, array_column(self::POSTS, 'id'))]
        );

    }

    /**
     * @Route("/{slug}", name="item_by_slug")
     */
public function postBySlug($slug)
{
    return $this->json(
       self::POSTS[ array_search($slug, array_column(self::POSTS, 'slug'))]
    );
}

/**
 * @Route ("/add", name="shop_add", methods={"POST"})
 */
public function add(Request $request)
{
    /** @var Serializer $serializer */
    $serializer = $this->get('serializer');

    $shop = $serializer->desialize($request->getContent(), Shop::class, 'json');
    $em = $this->getDoctrine()->getManager();
    $em->persist($shop);
    $em->flush();
}
}
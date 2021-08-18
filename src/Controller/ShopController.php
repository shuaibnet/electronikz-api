<?php

namespace App\Controller;

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
     * @Route("/", name="item_list")
     */
    public function list()
    {
        return new JsonResponse(self::POSTS);

    }
    /**
     * @Route("/{id}", name="item_by_id", requirements={"id"="\d+"})
     */
    public function post($id)
    {
        return new JsonResponse(
           self::POSTS [array_search($id, array_column(self::POSTS, 'id'))]
        );

    }
    /**
     * @Route("/{slug}", name="item_by_slug")
     */
public function postBySlug($slug)
{
    return new JsonResponse(
       self::POSTS[ array_search($slug, array_column(self::POSTS, 'slug'))]
    );
}
}
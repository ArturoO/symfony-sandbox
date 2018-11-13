<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop/{page}", name="shop", requirements={"page"="\d+"})
     */
    public function index($page = 1)
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'page' => $page,
        ]);
    }
}

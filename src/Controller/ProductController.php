<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{name}", name="product", requirements={"name"="[\w-]+"})
     */
    public function index($name)
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'name' => $name,
        ]);
    }
}

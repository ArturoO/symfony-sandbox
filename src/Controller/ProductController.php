<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;

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

    /**
     * @Route("/product/add/{name}/{price}", name="add_product", requirements={"name"="[\w-]+","price"="[\d]+"})
     */
    public function addProduct($name, $price)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription('New product, please add description');
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->render('product/add.html.twig', [
            'controller_name' => 'ProductController',
            'id' => $product->getId(),
            'name' => $name,
            'price' => $price,
        ]);
    }
}

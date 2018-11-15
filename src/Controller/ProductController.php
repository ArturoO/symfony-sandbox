<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product", requirements={"id"="[\d]+"})
     */
    public function index($id)
    {

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        dump($product);
        die;

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
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

    /**
     * @Route("/product/import/", name="import_product")
     */
    public function importProduct()
    {
        die;
        $entityManager = $this->getDoctrine()->getManager();

        $products = [];

        $products[] = new Product("Banana", 10, "Sweet fruit rich in potassium.");
        $products[] = new Product("Bottled Water", 8, "Mineral water good for everyone.");
        $products[] = new Product("Chockolade", 20, "Loved by children.");
        $products[] = new Product("Camera", 100, "Allows to record videos.");
        $products[] = new Product("Banana", 2, "Sweet fruit rich in potassium.");

        // $product = new Product();
        // $product->setName($name);
        // $product->setPrice($price);
        // $product->setDescription('New product, please add description');

        foreach($products as $product)
            $entityManager->persist($product);
        
        $entityManager->flush();

        return $this->render('product/import.html.twig', [
            'controller_name' => 'ProductController',
            'product_count' => count($products),
            // 'id' => $product->getId(),
            // 'name' => $name,
            // 'price' => $price,
        ]);
    }



}

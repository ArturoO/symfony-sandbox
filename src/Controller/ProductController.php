<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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

        // dump($product);
        // die;

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
        ]);
    }


    /**
     * @Route("/product/new/", name="product_new")
     */
    public function new(Request $request)
    {
        $product = new Product('', 0, '');

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create a product'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_created');
        }
        

        return $this->render('product/new.html.twig', array(
            'title' => 'Create new product',
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/product/created/", name="product_created")
     */
    public function created(Request $request)
    {
        return $this->render('product/created.html.twig', array(
            'title' => 'Product has been created',            
        ));
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

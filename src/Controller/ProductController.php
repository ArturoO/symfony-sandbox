<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Product;
use App\Entity\Category;


class ProductController extends AbstractController
{

    /**
     * @Route("/products/", name="products")
     */
    public function products()
    {
        $products = $this->getDoctrine()
			->getRepository(Product::class)
			->findAll();
		
		return $this->render('product/list.html.twig', [
			'title' => 'Products list',
            'products' => $products,
        ]);
    }


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
     * @Route("/product/add/", name="product_add")
     */
    public function add(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();
        $categoriesChoices = [];
        if($categories)
        {
            foreach($categories as $category)
                $categoriesChoices[$category->getName()] = $category;
        }
        
        $product = new Product('', 0, '');
		
        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('category', ChoiceType::class, [
                'choices' => $categoriesChoices
            ])
            ->add('save', SubmitType::class, array('label' => 'Create a product'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
			
			$this->addFlash(
				'notice',
				'Product has been created!'
			);

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }
        

        return $this->render('product/add.html.twig', array(
            'title' => 'Create new product',
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/product/edit/{id}", name="product_edit", requirements={"id"="[\d]+"})
     */
    public function edit(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Update product'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();

			$this->addFlash(
				'notice',
				'Product has been updated!'
			);
        }
        

        return $this->render('product/edit.html.twig', array(
            'title' => 'Update product',
            'id' => $product->getId(),
            'form' => $form->createView(),
        ));

    }
    
    /**
     * @Route("/product/delete/{id}", name="product_delete", requirements={"id"="[\d]+"})
     */
    public function delete(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $em->remove($product);
        $em->flush();
        
        $this->addFlash(
            'notice',
            'Product has been deleted!'
        );
        
        return $this->redirectToRoute('products');
    }

}

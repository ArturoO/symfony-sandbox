<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function categories(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $category = new Category('');

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)        
            ->add('save', SubmitType::class, array('label' => 'Create'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            
            $this->addFlash(
                'notice',
                'Category has been created!'
            );
        }
        
        $categories = $em->getRepository(Category::class)->findAll();
        
        return $this->render('category/list.html.twig', [
            'title' => 'Manage Categories',
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }
}

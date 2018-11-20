<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function categories()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        
        return $this->render('category/list.html.twig', [
            'title' => 'Manage Categories',
            'categories' => $categories,
        ]);
    }
}

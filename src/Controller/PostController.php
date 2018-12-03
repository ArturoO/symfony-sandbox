<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;

class PostController extends AbstractController
{
    
    /**
     * @Route("/posts/", name="posts")
     */
    public function list(Request $request)
    {
        $response = new Response();
        
        $em = $this->getDoctrine()->getManager();
        $postRepository = $em->getRepository(Post::class);
        $posts = $postRepository->findAll();
        
        
        $outputHtml = 'List of all posts:<br>';
        
        if($posts)
        {
            foreach($posts as $post)
            {
                $outputHtml.='ID: ' . $post->getId() . '; Title: ' . $post->getTitle() . '<br>';
            }
        }

        $response->setContent($outputHtml);        
        return $response;
    }
    
    
    /**
     * @Route("/post/add/", name="post_add")
     */
    public function index(Request $request)
    {
        $response = new Response();

        $title = $request->request->get('title', '');
        $content = $request->request->get('content', '');
        
        if(!strlen($title))
        {
            $response->setContent('Title can\'t be empty!');
            return $response;
        }
        
        $em = $this->getDoctrine()->getManager();
        $post = new Post($title, $content);
        
        $em->persist($post);
        $em->flush();
        
        $response->setContent('Post created, id: ' . $post->getId());
        return $response;
    }
}

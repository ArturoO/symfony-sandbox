<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;
use App\Entity\User;

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
                $outputHtml.='ID: ' . $post->getId() . '; Title: ' . $post->getTitle() . '; User: ' . $post->getUser()->getFirstName() . ' ' . $post->getUser()->getSecondName() . '<br>';
            }
        }

        $response->setContent($outputHtml);        
        return $response;
    }
    
    
    /**
     * @Route("/post/add/", name="post_add")
     */
    public function add(Request $request)
    {
        $response = new Response();

        $title = $request->request->get('title', '');
        $content = $request->request->get('content', '');
        $userId = $request->request->get('user_id', 0);
        
        if(!strlen($title))
        {
            $response->setContent('Title can\'t be empty!');
            return $response;
        }
        
        if(!$userId)
        {
            $response->setContent('Post must be assigned to a user!');
            return $response;
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository(User::class)->find($userId);
        
        if(is_null($user))
        {
            $response->setContent('User doesn\'t exist.');
            return $response;
        }
                
        $post = new Post($title, $content);
        $post->setUser($user);
        
        $em->persist($post);
        $em->flush();
        
        $response->setContent('Post created, id: ' . $post->getId());
        return $response;
    }
}

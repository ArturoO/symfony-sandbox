<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/settings", name="user_settings")
     */
    public function userSettings()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
//         $user = $this->getUser();
//         dump($user);
//         die;
        
        return $this->render('user/settings.html.twig', [
            'title' => 'Settings',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user/settings", name="user_settings")
     */
    public function userSettings(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $user = $this->getUser();
        
        $form = $this->createFormBuilder($user)
            ->add('id', IntegerType::class, ['disabled' => true])
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class)
            ->add('email', TextType::class, ['disabled' => true])
            ->add('gender', ChoiceType::class, [ 'choices' => ['Male' => 'male', 'Female' => 'female']])
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Update'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $this->addFlash(
                'notice',
                'User details updated'
            );
            
            //this resolves problems with refreshing the page
            return $this->redirectToRoute('user_settings');
        }
            
        
        return $this->render('user/settings.html.twig', [
            'title' => 'Settings',
            'form' => $form->createView(),
        ]);
    }
}

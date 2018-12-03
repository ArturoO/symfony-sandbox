<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/user/settings", name="user_settings")
     */
    public function userSettings(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $user = $this->getUser();
        
        $form = $this->createFormBuilder($user)
            ->add('id', IntegerType::class, ['disabled' => true])
            ->add('email', TextType::class, ['disabled' => true])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Password'
                ),
                'second_options' => array(
                    'label' => 'Repeat Password'
                ),
                'required' => false,
            ])
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class)
            ->add('gender', ChoiceType::class, [ 'choices' => ['Male' => 'male', 'Female' => 'female']])
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Update'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $plainPassword =  $user->getPlainPassword();
            if(strlen(trim($plainPassword)))
            {
                $password = $passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }
            
            
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

<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterController extends AbstractController
{

    /**
     *
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $usersCount = $entityManager->getRepository(User::class)->countAll();
        
        $user = new User();

        // 1) Build Form 
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)            
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Password'
                ),
                'second_options' => array(
                    'label' => 'Repeat Password'
                )
            ])
            ->add('save', SubmitType::class, array('label' => 'Register'))
            ->getForm();
    
        // 2) Handle request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            //first registered user will become admin
            if(!$usersCount)
                $user->setRoles(['ROLE_ADMIN']);

            // 4) save the User!
            //$entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
           
            return $this->redirectToRoute('register_success');
        }

        return $this->render('register/index.html.twig', [
            'title' => 'Register',
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/register/success", name="register_success")
     */
    function success()
    {
        return $this->render('register/success.html.twig', [
            'title' => 'User created',
            'content' => 'Register process successful',
        ]);
    }
}

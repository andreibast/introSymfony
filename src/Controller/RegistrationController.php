<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        //userpasswordencoder is called auto-wiring and it uses dependency injection.
        // It knows you are asking for dependency injection, so it will inject it to your function.


        //here is just the HTML form elements
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password']
            ])
            ->add('Register', SubmitType::class,[
                'attr'=> [
                    'class' => 'btn btn-success float-right'
                ]
            ])
            ->getForm()
            ;

        //this is to handle the form with the database
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();

            $user= new \App\Entity\User();
            $user ->setUsername($data['username']);
            $user ->setPassword(
                $passwordEncoder->encodePassword($user, $data['password'])
            );

            dump($user);
            $em =$this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this ->redirect($this->generateUrl('app_login'));
        }


        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

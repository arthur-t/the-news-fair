<?php

namespace App\Controller;

use App\AppBundle\Form\LoginForm;
use App\AppBundle\Form\RegisterForm;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
        ]);

        return $this->render('security/login.html.twig', array(
            'form'=> $form->createView(),
            'error'=> $error,
        ));
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $errorsString=null;


        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);


            try{


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('login');

            }
            catch (\Exception $e) {

                $errorsString="The username or email you entered is already taken";
            }


        }



        return $this->render(
            'security/register.html.twig',array(
                'form' => $form->createView(),
                'error'=> $errorsString,
            )
        );
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(){
        $this->getUser();
    }

    /**
     * @Route("/success", name="success")
     */
    public function success(){
        $user = $this->getUser();
        return new Response('Hello '.$user->getFullName());
        //return $this->render('security/success.html.twig');
    }

}

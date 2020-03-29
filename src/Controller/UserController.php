<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $message = "Page d'accueil en construction";
        return $this->render('user/index.html.twig', compact("message"));
    }
    /**
     * @Route("/inscription", name="inscription")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $manager = $this -> getDoctrine() -> getManager();
        $user = new User;
        $form = $this -> createForm(UserType::class, $user);
        $session = $request ->getSession();

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){

          $user -> setRoles(['ROLE_USER']);
          $user -> setDateEnregistrement(new \DateTime('now'));

          $password = $user -> getPassword();
          $password_hash = $encoder -> encodePassword($user, $password);
          $user -> setPassword($password_hash);

          $manager -> persist($user);
          $manager -> flush();
          $session -> set('user', $user); 
          

          // gestion de l'accord inscrit vs inscrite
          $e = ($user -> getSexe() == 'm') ? '' : 'e';
          $this -> addFlash('success', 'Félicitations' . ' ' . $user->getPrenom() . ', vous êtes bien inscrit' . $e. '.');
          return $this -> redirectToRoute('login');
        }

        return $this->render('user/register.html.twig',[
          'userForm' => $form -> createView(),
          'form_title' => 'Inscription'
        ]);
    }
    /**
     * @Route("/connexion", name="login")
     */
    public function login(AuthenticationUtils $auth, Request $request)
    {
      $session = $request -> getSession();
      
        
        $lastEmail = $auth -> getLastUsername();
        
        
        $error = $auth -> getLastAuthenticationError();
        if($error){
          $this -> addFlash('errors', 'Problème d\'identifiant');
        }
        return $this->render('user/login.html.twig',[
          'lastEmail' => $lastEmail
        ]);
      

    }

    /**
     * @Route("/connexion_check", name="login_check")
     */
    public function loginCheck(){}
      
    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout(){}
}

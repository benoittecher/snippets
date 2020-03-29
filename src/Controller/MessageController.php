<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\ConfirmDeletionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;


class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
      if ($this->getUser() === null){
        return $this->render('login');
      }
      $user = $this->getUser();
      
    
      $mRepo = $this -> getDoctrine() -> getRepository(Message::class);
      //1 : Récupérer des données
      $messages = $mRepo -> findBy(['user' => $user]);

      //2 : Afficher la vue
      return $this -> render('message/index.html.twig', compact('messages'));
      
    
    }

    /**
     * @Route("/message/{id}", name="message_show")
     */
    public function messageShow(Message $message){
        return $this -> render('message/show.html.twig',compact('message'));
      }

    /**
     * @Route("message_add", name="message_add")
     */
    public function messageAdd(Request $request)
    {
      $manager = $this -> getDoctrine() -> getManager();
      
      
      
      if($this->getUser() != null){
        // Création du formulaire
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
          $m = $form->getData();
          $user = $this->getUser();
          $m->setUser($user);

          $manager->persist($m);
          $manager->flush();

          $this->addFlash('success', 'Le message a été enregistré');
          return $this->redirectToRoute('message');
        }

        return $this->render('message/form.html.twig', [
          'form' => $form->createView(),
          'form_title' => 'Ajouter un message',
          ]);
      }
      return $this->redirectToRoute('login');
      
      
    }
    
    /**
     * @Route("/message_update/{id}", name="message_update")
     */
    public function messageUpdate(Message $message, Request $request)
    {
      $manager = $this -> getDoctrine() -> getManager();
      $mRepo = $this -> getDoctrine() -> getRepository(Message::class);
      $messages = $mRepo -> findAll();

      // Instanciation du formulaire
      $form = $this->createForm(MessageType::class, $message);
      $form->handleRequest($request);
      $m = "";
      

      // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $m = $form->getData();
            $manager->persist($m);
            $manager->flush();
            

            $this->addFlash('success', 'Le message a été modifié');
            return $this->render('message/index.html.twig', compact('messages'));
        }

      return $this->render('message/form.html.twig', [
        'message' => $m,
        'form' => $form->createView(),
        'form_title' => 'Modifier un message',
      ]);
    }


    /**
     * @Route("/message_delete/{id}", name="message_delete")
     */
    public function messageDelete(Message $message, Request $request)
    {
      $manager = $this -> getDoctrine() -> getManager();

      // Instanciation du formulaire
      $form = $this->createForm(ConfirmDeletionType::class);
      $form->handleRequest($request);

      // Traitement du formulaire
      if ($form->isSubmitted() && $form->isValid()) {
          $manager->remove($message);
          $manager->flush();
      

          $this->addFlash('success', 'Le message a été supprimé');
          return $this->redirectToRoute('message');
      }
      
      return $this->render('message/delete.html.twig', [
          'form' => $form->createView(),
          'title' => 'Supprimer un message',
          'form_label' => 'Je confirme la suppression du message ',
          'cancel_route' => 'message',
      ]);
    }
}

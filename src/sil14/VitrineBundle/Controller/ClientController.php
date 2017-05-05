<?php

namespace sil14\VitrineBundle\Controller;

use sil14\VitrineBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Client controller.
 *
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('VitrineBundle:Client')->findAll();

        return $this->render('VitrineBundle:Client:index.html.twig', array(
            'clients' => $clients,
        ));
    }

    /**
     * Creates a new client entity.
     *
     */
    public function newAction(Request $request)
    {

        $client = new Client();
        $form = $this->createForm('sil14\VitrineBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            //verif user pas éxistant
            if($this->getCustomerByMail($client->getMail())){
                $this->addFlash('danger', "Un compte existe déjà avec cette adresse email");
                return $this->redirectToRoute('client_new');
            } else {
                
                //NE FONCTIONNE PAS - à modif
                //$encoder=$this->container->get('security.password_encoder'); 
                // On récupère l'encodeur défini dans security.yml
                //$encoded=$encoder->encodePassword($client,$client->getPassword());
                // On encode le mot de passe issu du formulaire 
                //$client->setPassword($encoded);
                
                
                $em->persist($client);
                $em->flush($client);

                //$session = new Session();
                //$session->start();
                $session = $request->getSession();
                $session->set('id_user', $client->getId());
                
                $this->addFlash('success', "Compte créé, bienvenue !");
                return $this->forward('VitrineBundle:Article:index');
            }
        }

        return $this->render('VitrineBundle:Client:new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a client entity.
     *
     */
    public function showAction(Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);

        return $this->render('VitrineBundle:Client:show.html.twig', array(
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     */
    public function editAction(Request $request, Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm('sil14\VitrineBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_edit', array('id' => $client->getId()));
        }

        return $this->render('VitrineBundle:Client:edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a client entity.
     *
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush($client);
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('id' => $client->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function loginAction(Request $request){
      $client = new Client();
      
      $form = $this->createFormBuilder($client)
         ->add('mail', 'text')
         ->add('password', 'password')
         ->add('submit', 'submit')
         ->getForm();

      $form->handleRequest($request);

     if ($form->isSubmitted() && $form->isValid()) {
         $connexion_client = $form->getData();
         $em = $this->getDoctrine()->getManager();
         $client = $this->getCustomerByMail($connexion_client->getMail());
         if($client){
            if($client->getPassword() == $connexion_client->getPassword()){
              $this->createSession($client);
              $this->addFlash('success', "Connexion réussie");
              $session = $request->getSession();
              $session->set('id_user', $client->getId());  
              return $this->forward('VitrineBundle:Article:index');
            }
         }
         $this->addFlash('danger', "Identifiants incorrects, veuillez réssayer");
     }
      
      return $this->render('VitrineBundle:pages:connexion.html.twig', array('customer' => $client, 'form' => $form->createView()));
    }

    
    //fonctions utiles
    private function createSession($client) {
      $session = $this->getRequest()->getSession();
      $session->set('id_user', $client->getId());
    }
    private function getCustomerByMail($mail){
      $em = $this->getDoctrine()->getManager();
      $client = $em->getRepository('VitrineBundle:Client')->findOneByMail($mail);
      return $client;
    }
    public function logoutAction(Request $request){
        $session = $request->getSession();
        $session->remove('id_user');
        $this->addFlash('success', "Deconnexion réussie");
        return $this->forward('VitrineBundle:Article:index');
    }
}

<?php

namespace sil14\VitrineBundle\Controller;

use sil14\VitrineBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Commande controller.
 *
 */
class CommandeController extends Controller
{
    /**
     * Lists all commande entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository('VitrineBundle:Commande')->findAll();

        return $this->render('commande/index.html.twig', array(
            'commandes' => $commandes,
        ));
    }

    /**
     * Creates a new commande entity.
     *
     */
    public function newAction(Request $request)
    {
        $commande = new Commande();
        $form = $this->createForm('sil14\VitrineBundle\Form\CommandeType', $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush($commande);

            return $this->redirectToRoute('commande_show', array('id' => $commande->getId()));
        }

        return $this->render('commande/new.html.twig', array(
            'commande' => $commande,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a commande entity.
     *
     */
    public function showAction(Commande $commande)
    {
        $deleteForm = $this->createDeleteForm($commande);

        return $this->render('commande/show.html.twig', array(
            'commande' => $commande,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing commande entity.
     *
     */
    public function editAction(Request $request, Commande $commande)
    {
        $deleteForm = $this->createDeleteForm($commande);
        $editForm = $this->createForm('sil14\VitrineBundle\Form\CommandeType', $commande);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_edit', array('id' => $commande->getId()));
        }

        return $this->render('commande/edit.html.twig', array(
            'commande' => $commande,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a commande entity.
     *
     */
    public function deleteAction(Request $request, Commande $commande)
    {
        $form = $this->createDeleteForm($commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande);
            $em->flush($commande);
        }

        return $this->redirectToRoute('commande_index');
    }

    /**
     * Creates a form to delete a commande entity.
     *
     * @param Commande $commande The commande entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commande $commande)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commande_delete', array('id' => $commande->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /*
     * affichage des commandes selon l'id de l'user
     */
    public function listeCommandesAction(){
        //si l'user est connecté, on affiche la liste de ses commandes
        $session = $this->getRequest()->getSession();
        if(!is_null($session->get('id_user'))){
             $idClient=$session->get('id_user');
             
             $em = $this->getDoctrine()->getManager();
             $liste_commandes = $em->getRepository('VitrineBundle:Commande')->findByClient($idClient);
                
                if(!empty($liste_commandes)){
                    
                    $renderArrayCommandes= array();
                    $compteur=0;
                    
                    foreach($liste_commandes as $commande){
                        $renderArrayCommandes[$compteur]['infos']['id_commande']=$commande->getId();
                        
                        //au cas où il n'y ait pas de lignes
                        $renderArrayCommandes[$compteur]['infos']['vide']=FALSE;
                        
                        //date et état
                        $renderArrayCommandes[$compteur]['infos']['date']=$commande->getDate()->format('d/m/Y');
                        $renderArrayCommandes[$compteur]['infos']['etat']=$commande->getValide();
                        
                        //on récupère les lignes
                        $lignes_commande = $em->getRepository('VitrineBundle:ligneCommande')->findByCommande($commande->getId());
                        
                        if(!empty($lignes_commande)){
                            $compteurLigne=0;
                            
                            foreach($lignes_commande as $ligne){
                               //on récupère l'intitulé de l'article
                               $articleLigne=$ligne->getArticle();

                               $renderArrayCommandes[$compteur]['lignes'][$compteurLigne]=array(
                                   'article' => $articleLigne->getLibelle(),
                                   'prix'    => $ligne->getPrix(),
                                   'quantite'=> $ligne->getQuantite(),
                                   );

                               $compteurLigne++;
                            }
                        }else{
                            $renderArrayCommandes[$compteur]['infos']['vide']=TRUE;
                        }
                        
                    $compteur++;
                }
             return $this->render('VitrineBundle:Commande:listeCommandesClient.html.twig',
                array(
                    'commandes' => $renderArrayCommandes,
                    ));
        }
    }
    //autres cas
          $this->addFlash('danger', "Vous n'êtes pas connecté.");
          return $this->forward('VitrineBundle:Article:index');
 }
}

<?php

namespace sil14\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use sil14\VitrineBundle\Form\RechercheFiltreArticles;

/**
 * Description of ArticleController
 *
 * @author Kévin COISSARD
 */
class ArticleController extends Controller{
    
    //action de base
    public function indexAction()
    {
        $articles=$this->getDoctrine()
                ->getManager()
                ->getRepository('VitrineBundle:Article')
                ->findAll();
        
        //formulaires d'ajout au panier
        $formAjoutPanier = [];
        $tab = array();

        
        foreach($articles as $article){
            $form = $this->createFormBuilder($tab, array(
                'action' => $this->generateUrl('ajoutArticlePanier')
            ))
                ->add('id_article', 'hidden')
                ->add('quantity', 'integer', array("label" => "Quantité", "data" => 1))
                ->add('submit', 'submit')
                ->getForm();
            $formAjoutPanier[$article->getId()] = $form->createView();
         }
        
        //formulaire de recherche
        $formulaireRecherche = $this->createForm(new RechercheFiltreArticles());
        $request = $this->getRequest();
        
        if($request->getMethod() == 'POST'){
            
            $formulaireRecherche->bind($request);

            //On vérifie que les valeurs entrées sont correctes
            if($formulaireRecherche->isValid()){
                $em = $this->getDoctrine()->getManager();

                //On récupère les données entrées dans le formulaire par l'utilisateur
                $data = $this->getRequest()->request->get('formulaire_filtrage_articles');

                //On va récupérer la méthode dans le repository afin de trouver tous les articles filtrés par les paramètres du formulaire
                $liste_articles = $em->getRepository('VitrineBundle:Article')->findByCategorie($data);
                
                if(!empty($liste_articles)){
                //Puis on redirige vers la page de visualisation de cette liste d'annonces
                return $this->render('VitrineBundle:Article:index.html.twig', array(
                    'articles' => $liste_articles,
                    'formulaireRecherche' => $formulaireRecherche->createView(),
                    'formulaires' => $formAjoutPanier,
                        ));
                }
            }
        }

        
        if(!$articles)
        { 
            //Encadré rouge 
            $this->addFlash(
                'danger',//notice
                'Il n\'y a pas de produits.'
            );
        }
        else{
            return $this->render('VitrineBundle:Article:index.html.twig',
                array(
                    'articles' => $articles,
                    'formulaireRecherche' => $formulaireRecherche->createView(),
                    'formulaires' => $formAjoutPanier,
                ));
        }
    }
    
    //Coincoin des promotions
    public function promotionsAction()
    {
        $em=$this->getDoctrine()
                ->getManager();
        $query=$em->createQuery( 'SELECT a FROM VitrineBundle:Article a WHERE a.promotion=1' );
        $promotions=$query->getResult();
        
        //formulaires d'ajout au panier
        $formAjoutPanier = [];
        $tab = array();
        foreach($promotions as $promo){
            $form = $this->createFormBuilder($tab, array(
                'action' => $this->generateUrl('ajoutArticlePanier')
            ))
                ->add('id_article', 'hidden')
                ->add('quantity', 'integer', array("label" => "Quantité", "data" => 1))
                ->add('submit', 'submit')
                ->getForm();
            $formAjoutPanier[$promo->getId()] = $form->createView();
         }
        
       return $this->render('VitrineBundle:Article:promotions.html.twig',
                array(
                    'promotions' => $promotions,
                    'formulaires' => $formAjoutPanier,
                ));
        
    }
}

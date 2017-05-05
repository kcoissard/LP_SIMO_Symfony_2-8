<?php

namespace sil14\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use sil14\VitrineBundle\Entity\Panier;
use sil14\VitrineBundle\Entity\Commande;
use sil14\VitrineBundle\Entity\LigneCommande;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Description of ArticleController
 *
 * @author Kévin COISSARD
 */
class PanierController extends Controller{
    
    //action de base
    public function indexAction()
    {          
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier', new Panier());
        $articles = array();
        
        if(!empty($panier->getContenu())){
            
          $data = array();
          $formBuilder = $this->createFormBuilder($data, array(
              'action' => $this->generateUrl('panier')
          ));
          
          $prix_total = 0;
          foreach($panier->getContenu() as $id_article => $qte){
            $article = $this->findArticle($id_article);
            
            if($article){
              $articles[] = array(
                'article' => $article,
                'qte' => $qte,
              );
              
              $formBuilder->add(
                      'quantity',
                      'number',
                      array(
                          "label" => "Quantité",
                          "data" => $panier->getContenu()[$id_article],
                          ));
            }
            $prix_total+=$article->getPrix()*$qte;
          }
          
          $formBuilder->add('submit', 'submit');
          
          $form = $formBuilder->getForm();
        }else{
          return $this->render('VitrineBundle:Panier:index.html.twig', array('articles' => $articles, 'form' => null));
        }
        
        return $this->render('VitrineBundle:Panier:index.html.twig',
                array(
                    'articles' => $articles,
                    'form' => $form->createView(),
                    'prixtotal' => $prix_total,
                    ));
    }
    
    
    
    
    public function contenuPanierAction()
    {
      $session = $this->getRequest()->getSession();
      $panier = $session->get('panier', new Panier());
      $articles = array();
      
      if(!empty($panier->getContenu())){
          
        $prix_total = 0;
        $data = array();
        $formBuilder = $this->createFormBuilder($data, array(
            'action' => $this->generateUrl('panier')
        ));
        
        foreach($panier->getContenu() as $article_id => $qte){
            
          $article = $this->findArticle($article_id);
          
          if($article){
            $articles[] = array(
              'article' => $article,
              'qte' => $qte,
            );
            $formBuilder->add('quantity', 'number',
                    array(
                        "label" => "Quantité",
                        "data" => $panier->getContenu()[$article_id],
                        ));
          }
          $prix_total+=$article->getPrix()*$qte;
        }
        
        $formBuilder->add('submit', 'submit');
        
        $form = $formBuilder->getForm();
      }else{
        return $this->render('VitrineBundle:Panier:contenuPanier.html.twig',
                array(
                    'articles' => $articles,
                    'form' => null,
                    ));
      }

      return $this->render('VitrineBundle:Panier:contenuPanier.html.twig',
              array(
                  'articles' => $articles,
                  'form' => $form->createView(),
                  'prix_total' => $prix_total,
                  ));
    }
    
    
    
    
    public function ajoutArticlePanierAction(){
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('form');
        
        //pour connaitre l'actuelle route
        $request = $this->container->get('request');
        $routeName = $request->get('_route');
        
        $article_id = (int)$params['id_article'];
        $quantity = $params['quantity'];
        $article = $this->findArticle($article_id);
        $panier = $session->get('panier', new Panier());
        
        if(!empty($panier->getContenu())){
            $panier_article_quantity = array_key_exists($article_id, $panier->getContenu()) ? $panier->getContenu()[$article_id] : 0;
        }else{
            $panier_article_quantity=0;
        }
        
        if($article && ($article->getStock() >= $quantity + (int) $panier_article_quantity)){
          $panier->ajoutArticle($article_id, $quantity);
          $session->set('panier', $panier);
          $this->addFlash('success', "Produit ajouté !");
          if($routeName=="panier"){
            return $this->redirect($this->generateUrl('panier'));
          }
          return $this->redirect($this->generateUrl('catalogue'));
        } else {
          if(!$article){
            $this->addFlash('danger', "Article inconnu");
          } else {
            $this->addFlash('danger', "Il n'y a pas assez de stocks");
          }
          if($routeName=="panier"){
            return $this->redirect($this->generateUrl('panier'));
          }
          return $this->redirect($this->generateUrl('catalogue'));
        }
        
    }
    
    
    
    
    public function suppressionArticlePanierAction($id_article){
        $session = $this->getRequest()->getSession();
        $article = $this->findArticle($id_article);
        
        //pour connaitre l'actuelle route
        $request = $this->container->get('request');
        $routeName = $request->get('_route');
          
        if($article){
          $panier = $session->get('panier', new Panier());
          $panier->supprimeArticle($id_article);
          $session->set('panier', $panier);
          $this->addFlash('success', "Article supprimé !");
          

          if($routeName=="panier"){
              return $this->redirect($this->generateUrl('panier'));
          }
          return $this->redirect($this->generateUrl('catalogue'));
        } else {
          $this->addFlash('danger', "Erreur lors de la suppression");
          if($routeName=="panier"){
            return $this->redirect($this->generateUrl('panier'));
          }
          return $this->redirect($this->generateUrl('catalogue'));
        }
        
    }
    
    public function viderPanierAction(){
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier', new Panier());
        $panier->viderPanier();
        $session->set('panier', $panier);
        $this->addFlash('success', "Panier vidé avec succès");
        return $this->redirect($this->generateUrl('catalogue'));
    }
    
    
    public function confirmationPanierAction(){
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier', new Panier());
        
        $articles = array();
        
        if(!empty($panier->getContenu())){
            
            //créer une commande
            $idClient=$session->get('id_user');
            $repositoryClient = $this->getDoctrine()->getRepository('VitrineBundle:Client');
            $client = $repositoryClient->find($idClient);

            
            $today = new \DateTime('now');
            $commande = new Commande();
            $commande->setClient($client);
            $commande->setDate($today);
            $commande->setValide(false);
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush($commande);
            
          //créer les ligneCommande à chaque article du panier
          foreach($panier->getContenu() as $id_article => $qte){
            $article = $this->findArticle($id_article);
                if($article && !is_null($qte)){
                    
                    $ligneCommande = new ligneCommande();
                    $ligneCommande->setArticle($article);
                    $ligneCommande->setCommande($commande);
                    $ligneCommande->setPrix($article->getPrix());
                    $ligneCommande->setQuantite(intval($qte));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($ligneCommande);
                }                    
                $em->flush($ligneCommande);
            }
        
        
            $this->addFlash('success', "Commande validée");
            $panier->viderPanier();
            return $this->render('VitrineBundle:Commande:listeCommandesClient.html.twig',
                    array(
                        'articles' => $articles,
                        ));
        }
    
        $this->addFlash('danger', "Panier vide, veuillez choisir un article avant de valider une commande.");
        return $this->forward('VitrineBundle:Article:index');
    }
    
    
    
    //FACTORISATION
    private function findArticle($id_article){
      $article_manager = $this->getDoctrine()
              ->getManager()
              ->getRepository('VitrineBundle:Article');
      
      if($id_article){
        $article = $article_manager->find($id_article);
      }
      
      if (!$article) {
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier', new Panier());
        $panier->deleteArticle($id_article);
        $session->set('panier', $panier);
        return null;
      } else {
        return $article;
      }
    }
    
    
    
}

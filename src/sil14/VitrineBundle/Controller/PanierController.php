<?php

namespace sil14\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use sil14\VitrineBundle\Entity\Panier;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of ArticleController
 *
 * @author Kévin COISSARD
 */
class PanierController extends Controller{
    
    //action de base
    public function indexAction()
    {
        $article_manager = $this->getDoctrine()
                ->getManager()
                ->getRepository('VitrineBundle:Article');
        
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
                    'prix_total' => $prix_total,
                    ));
    }
    
    
    
    
    public function contenuPanierAction()
    {
      $article_manager = $this->getDoctrine()
              ->getManager()
              ->getRepository('VitrineBundle:Product');
      
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
          $prix_total+=$article->getPrice()*$qte;
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
          return $this->redirect($this->generateUrl('panier'));
        } else {
          if(!$article){
            $this->addFlash('danger', "Article inconnu");
          } else {
            $this->addFlash('danger', "Il n'y a pas assez de stocks");
          }
          return $this->redirect($this->generateUrl('catalogue'));
        }
        
    }
    
    
    
    
    public function suppressionArticlePanierAction($id_article){
        $session = $this->getRequest()->getSession();
        $article = $this->findArticle($id_article);
        if($article){
          $panier = $session->get('panier', new Panier());
          $panier->supprimeArticle($id_article);
          $session->set('panier', $panier);
          $this->addFlash('success', "Article supprimé !");
          return $this->redirect($this->generateUrl('panier'));
        } else {
          $this->addFlash('danger', "Erreur lors de la suppression");
          return $this->redirect($this->generateUrl('panier'));
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
        $panier->deleteProduct($id_article);
        $session->set('panier', $panier);
        return null;
      } else {
        return $article;
      }
    }
    
}

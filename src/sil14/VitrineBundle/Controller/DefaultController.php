<?php

namespace sil14\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use sil14\VitrineBundle\Entity\Article;
use sil14\VitrineBundle\Entity\Categorie;

use Symfony\Component\HttpFoundation\Response; 

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VitrineBundle:Default:index.html.twig',
                array('name'=>$name));
    }
    
    public function mentionsAction()
    {
        return $this->render('VitrineBundle:pages:mentions.html.twig');
    }
    
    public function catalogueAction()
    {
        $articles=$this->getDoctrine()->getManager()->getRepository('VitrineBundle:Article') ->findAll();
        
        if(!$articles)
        { 
            throw$this->createNotFoundException('Produits non trouvés'); 
        }
        return $this->redirectToRoute(catalogue, array(
            'articles' => $articles
        ));
    }
    /* REDIRECT DUN CONTROLER A LOTR A COMPLETER 
    public function listePromoAction()
    {
        $em=$this->getDoctrine()->getManager();
        $query=$em->createQuery( 'SELECT a FROM sil14VitrineBundle:Article a WHERE a.promotio,=1' );
        $promotions=$query->getResult();
        
        if(!$promotions)
        { 
            throw$this->createNotFoundException('Produits non trouvés'); 
        }
        
        return $this->render('VitrineBundle:pages:catalogue.html.twig', array(
            'promotions' => $promotions
        ));
    }
    
    /*
    public function detailArticleAction($id)
    {
        //
    }
    */
    
    public function panierAction()
    {
        return $this->render('VitrineBundle:pages:panier.html.twig');
    }
    
    public function creerAction(){
        
        //test si les catégories éxistent
        /*
        $categories=$this->getDoctrine()->getManager()->getRepository('VitrineBundle:Categorie')->findAll();
        dump($categories);
        
        //Catégorie coin coin
        $categorie = new Categorie();
        $categorie->setLibelle('Le coincoin des célébrités');
        $categorie->setDescription('Retrouvez les plus célèbres CoinCoins.');
        
        $categorie2 = new Categorie();
        $categorie2->setLibelle('Le coincoin des Hippies');
        $categorie2->setDescription('Ne jugez pas ces Coincoins dévoués à la nature.');
        
        $categorie3 = new Categorie();
        $categorie3->setLibelle('The Koingdom');
        $categorie3->setDescription('Les plus royaux des Coincoins');
        */
        
        // COIN COIN
        /*$article = new Article();
        $article->setLibelle('Coeinstein');
        $article->setPrix(11,99);
        $article->setDescription('E = MCoin2!');
        $article->setStock(10);
        $article->setPromotion(TRUE);
        $categorie=$this->getDoctrine()->getManager() ->getRepository('VitrineBundle:Categorie') ->find(1);
        $article->getCategorie($categorie);
        
        $article2 = new Article();
        $article2->setLibelle('Sherlockoin');
        $article2->setPrix(15,99);
        $article2->setDescription('Un Coincoin de votre collection a disparu dans la baignoire ? Faites appel au célèbre détective Sherlockoin.');
        $article2->setStock(6);
        $article2->setPromotion(FALSE);
        $article2->getCategorie($categorie);
        
        $article3 = new Article();
        $article3->setLibelle('Rose');
        $article3->setPrix(13,99);
        $article3->setDescription('A force de sniffer trop de fleurs ...');
        $article3->setStock(2);
        $article3->setPromotion(FALSE);
        $categorie2=$this->getDoctrine()->getManager() ->getRepository('VitrineBundle:Categorie') ->find(2);
        $article3->getCategorie($categorie2);
        
        $article4 = new Article();
        $article4->setLibelle('Checoinval');
        $article4->setPrix(9,99);
        $article4->setDescription('CoinHuuuCoiiiiiin');
        $article4->setStock(17);
        $article4->setPromotion(TRUE);
        $article4->setCategorie($categorie2);
        
        $article5 = new Article();
        $article5->setLibelle('Koing');
        $article5->setPrix(11,99);
        $article5->setDescription('Parceque le roi de la baignoire c\'est vous !');
        $article5->setStock(10);
        $article5->setPromotion(FALSE);
        $categorie3=$this->getDoctrine()->getManager() ->getRepository('VitrineBundle:Categorie') ->find(3);
        $article5->getCategorie($categorie3);
        
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->persist($article2);
        $em->persist($article3);
        $em->persist($article4);
        $em->persist($article5);
        //$em->persist($categorie);
        */
        
        $em->flush();
        
        //return new Response('Catégorie créées');
        return new Response('Produits créés');
    }
}

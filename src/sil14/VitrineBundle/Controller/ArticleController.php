<?php

namespace sil14\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        $categories=$this->getDoctrine()
                ->getManager()
                ->getRepository('VitrineBundle:Categorie')
                ->findAll();

        //on récupère les promotions s'il y en a
        //$promotions=$this->promotions();
        
        if(!$articles)
        { 
            //throw$this->createNotFoundException('Produits non trouvés'); 

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
                    'categories' => $categories,
                    //'promotions' => $promotions,
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
        
        /*
        if(!$promotions)
        { 
            return '';
        }else{
            return $promotions;
        }
         * */
       return $this->render('VitrineBundle:Article:promotions.html.twig',
                array(
                    'promotions' => $promotions,
                ));
        
    }
}

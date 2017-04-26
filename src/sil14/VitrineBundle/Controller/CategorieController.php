<?php
 
namespace sil14\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use sil14\VitrineBundle\Entity\Article;
use sil14\VitrineBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response; 

/**
 * Description of CategorieController
 *
 * @author Kévin COISSARD
 */
class CategorieController {
    
    //action de base - tri par id de categorie
    public function indexAction($id_categorie_article)
    {
        $articles=triParCategorie($id_categorie_article);
        if(empty($articles)){
            return $this->redirect($this->generateUrl('catalogue'));
        }
        else {
            return $this->render('VitrineBundle:Article:index.html.twig', array('articles' => $articles,));
        }
      }
      
    public function triParCategorie($id_categorie_article){
        
        $articles = array();
        
        $categorie = $this->getDoctrine()
              ->getManager()
              ->getRepository('VitrineBundle:Categorie')
              ->find($id_categorie_article);

        if(!$categorie){
          //Encadré rouge 
          $this->addFlash(
              'danger',//notice
              'La catégorie demandée n\'existe pas !'
          );
        }else{
            $articles = $categorie->getArticles();
        }
        return $articles;
    }
}

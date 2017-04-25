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
        $categorie = $this->getDoctrine()
                ->getManager()
                ->getRepository('VitrineBundle:Categorie')
                ->find($id_categorie_article);
         
        if(!$categorie){
            //throw $this->createNotFoundException('La catégorie demandée n'existe pas !');

            //Encadré rouge 
            $this->addFlash(
                'danger',//notice
                'La catégorie demandée n\'existe pas !'
            );
            // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()
          
            return $this->redirect($this->generateUrl('catalogue'));
          
        } else {
          $articles = $categorie->getArticles();
          
          //Formulaires à gérer ici
          
          return $this->render('VitrineBundle:Article:index.html.twig',
                               array('articles' => $articles,
                                     'categorie' => $categorie,
                                   )
                                 );
        }
      }
}

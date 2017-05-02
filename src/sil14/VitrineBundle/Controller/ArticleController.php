<?php

namespace sil14\VitrineBundle\Controller;

use sil14\VitrineBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use sil14\VitrineBundle\Form\RechercheFiltreArticles;

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{
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
    
    /**
     * Lists all article entities.
     *
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('VitrineBundle:Article')->findAll();

        return $this->render('article/index.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * Creates a new article entity.
     *
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('sil14\VitrineBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush($article);

            return $this->redirectToRoute('article_show', array('id' => $article->getId()));
        }

        return $this->render('article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);

        return $this->render('article/show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     */
    public function editAction(Request $request, Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('sil14\VitrineBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_edit', array('id' => $article->getId()));
        }

        return $this->render('article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a article entity.
     *
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush($article);
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

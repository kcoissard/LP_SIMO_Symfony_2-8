<?php

namespace sil14\VitrineBundle\Controller;

use sil14\VitrineBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Categorie controller.
 *
 */
class CategorieController extends Controller
{
    
     //action de base - tri par id de categorie
    public function indexAction($id_categorie_article)
    {
        $articles=$this->triParCategorie($id_categorie_article);
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
    
    /**
     * Lists all categorie entities.
     *
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('VitrineBundle:Categorie')->findAll();

        return $this->render('VitrineBundle:Categorie:index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new categorie entity.
     *
     */
    public function newAction(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm('sil14\VitrineBundle\Form\CategorieType', $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush($categorie);

            return $this->redirectToRoute('categorie_show', array('id' => $categorie->getId()));
        }

        return $this->render('VitrineBundle:Categorie:new.html.twig', array(
            'categorie' => $categorie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a categorie entity.
     *
     */
    public function showAction(Categorie $categorie)
    {
        $deleteForm = $this->createDeleteForm($categorie);

        return $this->render('VitrineBundle:Categorie:show.html.twig', array(
            'categorie' => $categorie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     */
    public function editAction(Request $request, Categorie $categorie)
    {
        $deleteForm = $this->createDeleteForm($categorie);
        $editForm = $this->createForm('sil14\VitrineBundle\Form\CategorieType', $categorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_edit', array('id' => $categorie->getId()));
        }

        return $this->render('VitrineBundle:Categorie:edit.html.twig', array(
            'categorie' => $categorie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a categorie entity.
     *
     */
    public function deleteAction(Request $request, Categorie $categorie)
    {
        $form = $this->createDeleteForm($categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush($categorie);
        }

        return $this->redirectToRoute('categorie_index');
    }

    /**
     * Creates a form to delete a categorie entity.
     *
     * @param Categorie $categorie The categorie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categorie $categorie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categorie_delete', array('id' => $categorie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sil14\VitrineBundle\Entity;

/**
 * Description of Panier
 *
 * @author Kévin COISSARD
 */
class Panier {
    
    private $contenu = [];    
    //Tableau - contenu[i] = quantite d'article d’id=i dans le panier) 
 
    public function __construct() {     
        // initialise le contenu     
         $contenu = [];
    }
 
    public function getContenu() {     
        // getter
        /*
        if(empty($this->$contenu)){
            $contenuBis = array([1] => 2);
             return $contenuBis;
        }else{
            return $this->$contenu;
        }*/
         return $this->contenu;
    }
 
    public function ajoutArticle($articleId, $qte = 1) {    
        // ajoute l'article $articleId au contenu, en quantité $qte
        //  (vérifier si l'article n'y est pas déjà)
        if(array_key_exists($articleId,$this->getContenu())){
            $this->contenu[$articleId]+=$qte;
        } else {
            $this->contenu[$articleId]=$qte;
        }
    } 
 
    public function supprimeArticle($articleId) {     
        // supprimer l'article $articleId du contenu  
        if(array_key_exists((int) $articleId, $this->getContenu())){
            unset($this->contenu[$articleId]);
        }
    } 
    
    public function setContenu($contenu) {
     $this->contenu = $contenu;
    }
    
    public function viderPanier() {     // vide le contenu    
       $this->setContenu([]);
    }
}

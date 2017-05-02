<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sil14\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of SessionController
 *
 * @author Kévin COISSARD
 */
class SessionController extends Controller{
    
    //action de base
    public function indexAction(){
        //if user connecté --> on affiche le bouton déconnexion
        //return $this->render('VitrineBundle:Menu:menuDeconnexion.html.twig');

        //else -> on affiche le bouton connexion + inscription
        return $this->render('VitrineBundle:Menu:menuConnexion.html.twig');
    }
    
    //action de base
    public function connexionAction(){
        
    }
    
    //action de base
    public function deconnexionAction(){
        
    }
}

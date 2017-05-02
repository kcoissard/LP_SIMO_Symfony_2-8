<?php

namespace sil14\VitrineBundle\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//use sil14\VitrineBundle\Entity\Article;




/**
 * Description of CommandeController
 *
 * @author Kévin COISSARD
 */
class CommandeController extends Controller{
    //put your code here
    
    public function indexAction() {
        echo 'toto indexACTION commande';
    }
    
    public function creerAction(){
        //var_dump($array_articles);
    }
}

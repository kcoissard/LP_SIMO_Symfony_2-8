<?php

namespace sil14\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        //user loggé ?
        
        return $this->render('VitrineBundle:Default:index.html.twig',
                array('name'=>$name));
    }
    
    public function mentionsAction()
    {
        return $this->render('VitrineBundle:pages:mentions.html.twig');
    }
    
    public function backofficeProvisoirAction(){
        return $this->render('VitrineBundle:Default:backofficeProvisoir.html.twig');
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sil14\VitrineBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AdvertController
 *
 * @author Kévin COISSARD
 */
class AdvertController {
    //put your code here
    public function indexAction()
    {
        return new Response("Hello World !");
    }
}

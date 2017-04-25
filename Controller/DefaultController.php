<?php

namespace RValin\InfoBipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/toto")
     */
    public function indexAction()
    {
        return $this->render('RValinInfoBipBundle:Default:index.html.twig');
    }
}

<?php

namespace Administracion\ModeloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ModeloBundle:Default:index.html.twig', array('name' => $name));
    }
}

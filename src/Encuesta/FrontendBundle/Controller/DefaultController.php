<?php

namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $votados = $em->getRepository('ModeloBundle:Evento')->getEventosMasVotados();
        return $this->render('FrontendBundle:Default:index.html.twig', array('votados'=> $votados));
    }
}

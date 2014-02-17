<?php

namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $votados = $em->getRepository('ModeloBundle:Evento')->getEventosMasVotados(8, $this->getRequest()->getLocale());
        $categorias = $em->getRepository('ModeloBundle:Categoria')->getCategoriasPadres();
        return $this->render('FrontendBundle:Default:index.html.twig', array('votados'=> $votados, 'categorias'=>$categorias));
    }
}

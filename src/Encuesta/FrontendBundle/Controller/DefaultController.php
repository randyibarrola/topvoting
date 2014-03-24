<?php

namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
//        $em = $this->getDoctrine()->getManager();
//        $votados = $em->getRepository('ModeloBundle:Evento')->getEventosMasVotados(8, $this->getRequest()->getLocale());
//        $categorias = $em->getRepository('ModeloBundle:Categoria')->getCategoriasPadres();
//        return $this->render('FrontendBundle:Default:index.html.twig', array('votados'=> $votados, 'categorias'=>$categorias));
        return $this->render('FrontendBundle:Default:index.html.twig', array());
    }

    public function cargarCajasAction()
    {
        $peticion = $this->getRequest();

        $pagina = $peticion->request->get('pagina', 1);
        $contenido = $peticion->request->get('contenido', 'home');

        $cajas = array();
        $em = $this->getDoctrine()->getManager();

        switch($contenido) {
            case 'categoria':
                $cajas = $em->getRepository('ModeloBundle:Categoria')->findAll();
                break;
            case 'mas_voting':
                $cajas = $em->getRepository('ModeloBundle:Evento')->getEventosMasVotadosIndex($peticion->getLocale());
                break;
            case 'home':
                $cajas = $em->getRepository('ModeloBundle:Evento')->getEventosActivosOrdenFecha($peticion->getLocale());
                break;
            case 'ultimo':
                $cajas = $em->getRepository('ModeloBundle:Evento')->getEventosActivosOrdenFecha($peticion->getLocale());
                break;
        }

        $paginador = $this->get('knp_paginator')->paginate($cajas, $pagina, 8);

        return $this->render('FrontendBundle:Default:votacionesHome.html.twig', array(
            'contenido' => $contenido,
            'pagina' => $pagina,
            'paginador' => $paginador
        ));
    }

    public function autoCompletarAction()
    {
        $peticion = $this->getRequest();
        $translator = $this->get('translator');
        $texto = $peticion->request->get('texto', false);
        if (!$texto || strlen(trim($texto)) < 3)
            return new Response($translator->trans('No existen coincidencias'));

        $eventos = $this->getDoctrine()->getManager()->getRepository('ModeloBundle:Evento')->getEventosAutocompletar($texto, $peticion->getLocale());

        return $this->render('FrontendBundle:Default:autoCompletar.html.twig', array(
            'eventos' => $eventos
        ));
    }
}

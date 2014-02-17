<?php

namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('ModeloBundle:Usuario')->findAll();
        $categorias = $em->getRepository('ModeloBundle:Categoria')->findAll();
        $eventos = $em->getRepository('ModeloBundle:Evento')->findAll();
        $candidatos = $em->getRepository('ModeloBundle:Candidato')->findAll();

        $stats = array(
            'usuarios' => count($usuarios),
            'categorias' => count($categorias),
            'eventos' => count($eventos),
            'candidatos' => count($candidatos)
        );

        return $this->render('DashboardBundle:Default:index.html.twig', array(
            'stats' => $stats
        ));
    }

    public function sideBarMenuAction()
    {
        return $this->render('DashboardBundle:Default:sideBarMenu.html.twig', array(
            'route_match' => $this->getRouteName()
        ));
    }

    private function getRouteName()
    {
        $request = Request::createFromGlobals();
        $router = $this->get('router');
        $router_match = $router->match($request->getPathInfo());

        return isset($router_match['_route']) ? $router_match['_route'] : '';
    }
}

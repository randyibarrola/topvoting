<?php

namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DashboardBundle:Default:index.html.twig');
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

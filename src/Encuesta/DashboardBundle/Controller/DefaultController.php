<?php

namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DashboardBundle:Default:index.html.twig');
    }

    public function sideBarMenuAction()
    {
        return $this->render('DashboardBundle:Default:sideBarMenu.html.twig');
    }
}

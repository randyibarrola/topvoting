<?php
namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;


class CategoriaController extends Controller
{
    public function listAction()
    {
        return $this->render('DashboardBundle:Categoria:list.html.twig');
    }

    public function newAction()
    {
        return $this->render('DashboardBundle:Categoria:form.html.twig');
    }
}
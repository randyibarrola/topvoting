<?php
namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR, $session->get(SecurityContext::AUTHENTICATION_ERROR));

        return $this->render('DashboardBundle:User:login.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error ));
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ModeloBundle:Usuario')->findAll();

        return $this->render('DashboardBundle:User:list.html.twig', array(
            'list' => $list
        ));
    }

    public function changeStateAction()
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $peticion = $this->getRequest();

        $response = $this->get('dashboard.ajaxresponse');
        try {
            $obj = $em->getRepository('ModeloBundle:Usuario')->find($peticion->get('id'));

            if(!$obj) {
                $response->setHttpCode(500);
                $response->setMessage($translator->trans('Está intentando modificar un usuario que no existe'));
            }
            else {
                $obj->setActivo(!$obj->getActivo());
                $em->persist($obj);
                $em->flush();

                $response->setMessage($translator->trans('Se ha modificado el estado del usuario correctamente'));
            }
        }
        catch(\Exception $e) {
            $response->setHttpCode(500);
            $response->setMessage($translator->trans('Lo sentimos, hubo un error'));
        }

        $sResponse = new Response(json_encode($response->response()));
        $sResponse->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $sResponse;
    }
}
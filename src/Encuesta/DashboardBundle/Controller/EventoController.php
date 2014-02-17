<?php
namespace Encuesta\DashboardBundle\Controller;

use Encuesta\ModeloBundle\Entity\Evento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;


class EventoController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ModeloBundle:Evento')->findAll();

        return $this->render('DashboardBundle:Evento:list.html.twig', array(
            'list' => $list,
            'idiomas' => $this->container->getParameter('idiomas'),
            'service_entity' => $this->get('dashboard.entity')
        ));
    }

    public function deleteAction()
    {
        $response = $this->get('dashboard.ajaxresponse');
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');

        try {
            $obj = $em->getRepository('ModeloBundle:Evento')->find($this->getRequest()->get('id'));

            if(!$obj) {
                $response->setHttpCode(500);
                $response->setMessage($translator->trans('El evento que intenta eliminar no existe'));
            }
            else {
                $em->remove($obj);
                $em->flush();

                $response->setMessage($translator->trans('El evento se ha eliminado satisfactoriamente'));
                $response->setDataHolder(array('route' => $this->get('router')->generate('dashboard_evento')));
            }
        }
        catch(\Exception $e) {
            $response->setHttpCode(500);
            $response->setMessage($translator->trans('Lo sentimos, ha ocurrido un error'));
        }

        return new Response(json_encode($response->response()));
    }

    public function viewAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $obj = $em->getRepository('ModeloBundle:Evento')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el evento que está intentando ver');

        return $this->render('DashboardBundle:Evento:view.html.twig', array(
            'idiomas' => $this->container->getParameter('idiomas'),
            'obj' => $obj,
            'i18n' => $em->getRepository('Gedmo\Translatable\Entity\Translation')->findTranslations($obj),
            'service_entity' => $this->get('dashboard.entity')
        ));
    }

    public function changeStateAction()
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $peticion = $this->getRequest();

        $response = $this->get('dashboard.ajaxresponse');
        try {
            $obj = $em->getRepository('ModeloBundle:Evento')->find($peticion->get('id'));

            if(!$obj) {
                $response->setHttpCode(500);
                $response->setMessage($translator->trans('Está intentando modificar un evento que no existe'));
            }
            else {
                $obj->setActivo(!$obj->getActivo());
                $em->persist($obj);
                $em->flush();

                $response->setMessage($translator->trans('Se ha modificado el estado del evento correctamente'));
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
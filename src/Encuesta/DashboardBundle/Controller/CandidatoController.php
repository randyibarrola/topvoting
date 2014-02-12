<?php
namespace Encuesta\DashboardBundle\Controller;

use Encuesta\ModeloBundle\Entity\Candidato;
use Encuesta\ModeloBundle\Form\CandidatoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;


class CandidatoController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ModeloBundle:Candidato')->findAll();

        return $this->render('DashboardBundle:Candidato:list.html.twig', array(
            'list' => $list,
            'idiomas' => $this->container->getParameter('idiomas'),
            'service_entity' => $this->get('dashboard.entity')
        ));
    }

    public function newAction()
    {
        $request = $this->getRequest();
        $idiomas = $this->container->getParameter('idiomas');

        $obj = new Candidato();
        $form = $this->createForm(new CandidatoType(), $obj);

        if($request->getMethod() == 'POST') {
            return $this->save($form);
        }

        return $this->render('DashboardBundle:Candidato:form.html.twig', array(
            'idiomas' => $idiomas,
            'obj' => $obj,
            'form' => $form->createView(),
            'i18n' => array()
        ));
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $idiomas = $this->container->getParameter('idiomas');

        $obj = $em->getRepository('ModeloBundle:Candidato')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el candidato que está intentando editar');

        $form = $this->createForm(new CandidatoType(), $obj);

        if($request->getMethod() == 'POST') {
            $imagen_original = $form->getData()->getImagen();
            $eliminar_imagen = $request->request->get('imagen_delete', 0);
            $response = $this->save($form);

            $obj = $form->getData();
            if($obj->getImagen() === null && $eliminar_imagen == 0) {
                $obj->setImagen($imagen_original);

                $em->persist($obj);
                $em->flush();
            }
            else if($imagen_original != null) {
                unlink($this->container->getParameter('upload_dir').'candidato/'.$obj->getId().'/'.$imagen_original);
                if(is_dir($this->container->getParameter('upload_dir').'candidato/'.$obj->getId()))
                    @rmdir($this->container->getParameter('upload_dir').'candidato/'.$obj->getId());
            }

            return $response;
        }

        $i18n = $em->getRepository('Gedmo\Translatable\Entity\Translation')->findTranslations($obj);

        return $this->render('DashboardBundle:Candidato:form.html.twig', array(
            'idiomas' => $idiomas,
            'obj' => $obj,
            'form' => $form->createView(),
            'i18n' => $i18n
        ));
    }

    private function save(FormInterface $form)
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $response = $this->get('dashboard.ajaxresponse');
        try {
            $form->bind($request);
            if($form->isValid()) {
                $data = $form->getData();

                if($data->getId() == null) {
                    $em->persist($data);
                    $em->flush();
                }

                $dir = $this->container->getParameter('upload_dir').'candidato/'.$data->getId().'/';
                $imagen = $this->get('dashboard.file')->uploadFile($data->getImagen(), $dir);
                $data->setImagen($imagen);

                $em->persist($data);
                $em->flush();

                $i18n = array(
                    'titulo' => $request->request->get('translate_titulo', array()),
                    'descripcion' => $request->request->get('translate_descripcion', array()),
                );

                if($this->get('dashboard.entity')->persistEntityTranslations($data, $i18n)) {
                    $em->flush();
                }


                $response->setMessage($translator->trans('El candidato se ha guardado satisfactoriamente'));
            }
            else {
                $response = $this->get('dashboard.ajaxformresponse');
                $response->setForm($form);
                $response->setHttpCode(500);
                $response->setMessage($translator->trans('Existen datos incorrectos en el formulario'));
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

    public function deleteAction()
    {
        $response = $this->get('dashboard.ajaxresponse');
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');

        try {
            $obj = $em->getRepository('ModeloBundle:Candidato')->find($this->getRequest()->get('id'));

            if(!$obj) {
                $response->setHttpCode(500);
                $response->setMessage($translator->trans('El candidato que intenta eliminar no existe'));
            }
            else {
                $em->remove($obj);
                $em->flush();

                $response->setMessage($translator->trans('El candidato se ha eliminado satisfactoriamente'));
                $response->setDataHolder(array('route' => $this->get('router')->generate('dashboard_candidato')));
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

        $obj = $em->getRepository('ModeloBundle:Candidato')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el candidato que está intentando ver');

        return $this->render('DashboardBundle:Candidato:view.html.twig', array(
            'idiomas' => $this->container->getParameter('idiomas'),
            'obj' => $obj,
            'i18n' => $em->getRepository('Gedmo\Translatable\Entity\Translation')->findTranslations($obj),
            'service_entity' => $this->get('dashboard.entity')
        ));
    }
}
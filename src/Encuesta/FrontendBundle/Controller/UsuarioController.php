<?php
namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Encuesta\ModeloBundle\Entity\Usuario;
use Encuesta\ModeloBundle\Form\UsuarioType;


class UsuarioController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest()->get;
        $session = $request->getSession();

        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR, $session->get(SecurityContext::AUTHENTICATION_ERROR));

        return $this->render('FrontendBundle:Usuario:login.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error ));
    }
    
    public function registroAction()
    {
        $peticion = $this->getRequest();
        $usuario = new Usuario();
        $form = $this->createForm(new UsuarioType(), $usuario);
        
        if($peticion->getMethod() == "POST") {
            $form->bind($peticion);
            if($form->isValid()){
                
                $em = $this->getDoctrine()->getManager();
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($usuario);
                $salt = md5(time());
                $usuario->setSalt($salt);            
                $password = $encoder->encodePassword($peticion->get('password'),$salt );
                $usuario->setPassword($password);
                $usuario->setActivo(false);
                $usuario->setCodigoActivacion(md5(time()));

                $rol = $em->getRepository('ModeloBundle:Rol')->findOneBy(array('nombre'=>'ROLE_USER')); 
                $usuario->addUsuarioRole($rol);

                $em->persist($usuario);
                $em->flush();

                $mailer = $this->container->get('mailer');  
                $url = $peticion->getUriForPath($this->generateUrl('frontend_activacion', array('codigo'=>$usuario->getCodigoActivacion())));               
                //Mail para el cliente
                $msgCliente = $this->container->get('topvoting.mailer')->getMsgCreacionCuenta($usuario, $url);            
                $mailer->send($msgCliente);

                return $this->render('FrontendBundle:Usuario:registro.html.twig', array(
                    'procesado' => true
                ));
            }
            
        }
        return $this->render('FrontendBundle:Usuario:registro.html.twig', array(
            'form' => $form->createView()
        ));        
    }
    
    public function activacionAction()
    {
       $codigo = $this->getRequest()->get('codigo');
       if($codigo){
           $em = $this->getDoctrine()->getManager();
           $usuario = $em->getRepository('ModeloBundle:Usuario')->findOneBy(array('codigo_activacion'=> $codigo, 'activo'=> 0));
           if($usuario){
               $usuario->setActivo(1);
               $em->persist($usuario);
               $em->flush();  
               
                return $this->render('FrontendBundle:Usuario:activacion.html.twig', array(
                    'resultado' => 'ok'
                )); 
           }
       }
       
       return $this->render('FrontendBundle:Usuario:activacion.html.twig', array(
           'resultado' => 'error'
        ));        

    }
}

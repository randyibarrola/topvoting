<?php
namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Encuesta\ModeloBundle\Entity\Usuario;


class UsuarioController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR, $session->get(SecurityContext::AUTHENTICATION_ERROR));

        return $this->render('FrontendBundle:Usuario:login.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error ));
    }
    
    public function registroAction()
    {
        $peticion = $this->getRequest();
        if($peticion->getMethod() == "POST") {
            
            $em = $this->getDoctrine()->getManager();
            $usuario = new Usuario();
            $usuario->setEmail($peticion->get('email'));
            $usuario->setApellidos('Apellidos Fijos');
            $usuario->setUsername($peticion->get('username'));
            
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

            //Mail para el cliente
            $msgCliente = $this->container->get('topvoting.mailer')->getMsgCreacionCuenta($usuario);            
            $mailer->send($msgCliente);
            
            return $this->redirect($this->generateUrl('frontend_registro'));
            
        }
        return $this->render('FrontendBundle:Usuario:registro.html.twig');        
    }
}

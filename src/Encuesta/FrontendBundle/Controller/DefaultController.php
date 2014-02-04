<?php

namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Encuesta\ModeloBundle\Entity\Usuario;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getDoctrine()->getManager()->getRepository('ModeloBundle:Usuario')->find(1);
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($usuario);
        $salt = $usuario->getSalt() ? $usuario->getSalt() : md5(time());
        $usuario->setSalt($salt); 
        if( strlen('adminpass') > 0){
            $password = $encoder->encodePassword('adminpass',$salt );
            $usuario->setPassword($password);
        }
        $em->persist($usuario);
        
        return $this->render('FrontendBundle:Default:index.html.twig', array('name' => $name));
    }
}

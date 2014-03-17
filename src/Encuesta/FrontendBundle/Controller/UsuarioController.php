<?php
namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Encuesta\ModeloBundle\Entity\Usuario;
use Encuesta\ModeloBundle\Form\UsuarioType;
use Encuesta\ModeloBundle\Form\RegistroUsuarioType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends Controller
{
    public function loginAction()
    {
        if($this->getUser())
            return $this->redirect ($this->generateUrl ('frontend_homepage'));
        
        $request = $this->getRequest();
        $session = $request->getSession();
        
        $usuario = new Usuario();
        $form = $this->createForm(new RegistroUsuarioType(), $usuario);        

        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR, $session->get(SecurityContext::AUTHENTICATION_ERROR));

        return $this->render('FrontendBundle:Usuario:login.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
            'form' => $form->createView()
            ));
    }
    
    public function registroAction()
    {
        $peticion = $this->getRequest();
        $usuario = new Usuario();
        $form = $this->createForm(new UsuarioType(), $usuario);
        
        if($peticion->getMethod() == "POST") {
            $form->bind($peticion);
            if($form->isValid()){
                
                $usuario = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($usuario);
                $salt = md5(time());
                $usuario->setSalt($salt);            
                $password = $encoder->encodePassword($usuario->getPassword(),$salt );
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
               $usuario->setCodigoActivacion(null);
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
    
    public function perfilAction()
    {
        $usuario = $this->getUser();
        if($usuario) {
            $form = $this->createForm(new UsuarioType(true), $usuario);
            $peticion = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            
            if($peticion->getMethod() == 'POST'){                 
                $form->bind($peticion);
                if($form->isValid()){
                    $usuario = $form->getData();                     
                    $extension = $form['imagen']->getData()->guessExtension();
                    if( in_array( $extension, array('png', 'jpg', 'jpeg', 'gif', 'bmp') ) ) {
                        if(!is_dir($usuario->getUploadDir()))      
                                   mkdir($usuario->getUploadDir(), 0777);  
                        
                        $nombre = md5(time());
                        $form['imagen']->getData()->move($usuario->getUploadDir(), $nombre.'.'.$extension);
                        $usuario->setImagen($nombre.'.'.$extension);
                    }                    
                    $em->persist($usuario);
                    $em->flush(); 
                    
                    $this->get('session')->getFlashBag()->add('notificacion', 'Perfil Actualizado');
                }    
                
            } 
                
            return $this->render('FrontendBundle:Usuario:perfil.html.twig', array(
                'form' => $form->createView(),
                'usuario' => $usuario
            ));                 
            
            
        }
        
        throw new AccessDeniedException();
        
    }
    
    public function loginFacebookAction()
    {
        $em = $this->getDoctrine()->getManager();
        $peticion = $this->getRequest();
        $nombre = $peticion->get('nombre');
        $apellido = $peticion->get('apellido');
        $username = $peticion->get('username');
        $email = $peticion->get('email');
        $usuario = $em->getRepository('ModeloBundle:Usuario')->findOneBy(array('username'=> $username));
        
        if(!$this->getUser()) {
            
            if(! $usuario) {            

                $usuario = new Usuario();
                $usuario->setUsername($username);
                $usuario->setNombre($nombre);
                $usuario->setApellidos($apellido);
                $usuario->setEmail($email);
                $usuario->setPassword(md5(time()));
                $usuario->setSalt(md5(time()));
                $usuario->setActivo(1);
                $usuario->setRedSocial(1);
                $em->persist($usuario);
                $em->flush();
            }

            //logueando manualmente 

            $logintoken = new UsernamePasswordToken($usuario, $usuario->getPassword(), 'frontend', array('ROLE_USER'));
            $this->get('security.context')->setToken($logintoken);

            $event = new InteractiveLoginEvent($peticion, $logintoken);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);   


            //redireccionando a homepage

            return new Response( json_encode(array('resultado' => 'recargar'  ) ));  
            
        }
        
        return new Response( json_encode(array('resultado' => 'ok'  ) ));  
        
    }
}

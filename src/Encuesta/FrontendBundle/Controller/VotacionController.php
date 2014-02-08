<?php
namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Encuesta\ModeloBundle\Entity\Usuario;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Encuesta\ModeloBundle\Form\EventoType;
use Encuesta\ModeloBundle\Entity\Evento;

class VotacionController extends Controller
{
    public function votacionesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $peticion = $this->getRequest();
        $evento = new Evento();
        $form = $this->createForm(new EventoType(), $evento);

        
        if($peticion->getMethod() == "POST") {
            $form->bind($peticion);
            if($form->isValid()){
                $evento = $form->getData();   
                $extension = $form['imagen']->getData()->guessExtension();
                if( in_array( $extension, array('png', 'jpg', 'jpeg', 'gif', 'bmp') ) ) {
                    if(!is_dir($evento->getUploadDir()))      
                               mkdir($evento->getUploadDir(), 0777);                       
                    
                    $nombre = md5(time());
                    $form['imagen']->getData()->move($evento->getUploadDir(), $nombre.'.'.$extension);
                    $evento->setImagen($nombre.$extension);
                }         
                $evento->setCreador($this->getUser());
                $em->persist($evento);
                $em->flush();    
                
                return $this->redirect($this->generateUrl('frontend_agregar_candidato'));
                
            }
        }
        
        $eventos = $em->getRepository('ModeloBundle:Evento')->getEventosActivos();
        return $this->render('FrontendBundle:Votacion:votaciones.html.twig', array(
            'form' => $form->createView(),
            'eventos' => $eventos
        ));         
        
    }
    
    public function agregarCandidatoAction()
    {
        return $this->render('FrontendBundle:Votacion:agregarCandidato.html.twig', array(
           
           
        ));        
    }
}


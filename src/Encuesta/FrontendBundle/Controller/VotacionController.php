<?php
namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Encuesta\ModeloBundle\Entity\Usuario;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Encuesta\ModeloBundle\Form\EventoType;
use Encuesta\ModeloBundle\Entity\Evento;
use Encuesta\ModeloBundle\Entity\Candidato;
use Encuesta\ModeloBundle\Entity\Voto;
use Encuesta\ModeloBundle\Entity\EventoCandidato;
use Encuesta\ModeloBundle\Form\CandidatoType;
use Symfony\Component\HttpFoundation\Response;

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
                if($form['imagen']->getData()){
                $extension = $form['imagen']->getData()->guessExtension();
                    if( in_array( $extension, array('png', 'jpg', 'jpeg', 'gif', 'bmp') ) ) {
                        if(!is_dir($evento->getUploadDir()))      
                                   mkdir($evento->getUploadDir(), 0777);                       

                        $nombre = md5(time());
                        $form['imagen']->getData()->move($evento->getUploadDir(), $nombre.'.'.$extension);
                        $evento->setImagen($nombre.$extension);
                    }   
                }
                $evento->setCreador($this->getUser());
                $evento->setActivo(false);
                $em->persist($evento);
                $em->flush();    
                
                return $this->redirect($this->generateUrl('frontend_agregar_candidato', array('id'=>$evento->getId())));
                
            }
        }
        
        $todosEventos = $em->getRepository('ModeloBundle:Evento')->getEventosActivos();
        $misEventos = $em->getRepository('ModeloBundle:Evento')->getEventosActivos($this->getUser());
        return $this->render('FrontendBundle:Votacion:votaciones.html.twig', array(
            'form' => $form->createView(),
            'eventos' => $todosEventos,
            'misEventos' => $misEventos
        ));         
        
    }
    
    public function agregarCandidatoAction()
    {
        $em = $this->getDoctrine()->getManager();    
        $id = $this->getRequest()->get('id');
        $evento = $id ? $em->getRepository('ModeloBundle:Evento')->find($id)  : null;
        $peticion = $this->getRequest();
        
        if($evento) {
            
            $candidatos = $evento->getCandidatos();
            $candidato = new Candidato();
            $form = $this->createForm(new CandidatoType(), $candidato);
            
            if($peticion->getMethod() == "POST") {
                $form->bind($peticion);
                if($form->isValid()) {
                    $candidato = $form->getData();   
                    if($form['imagen']->getData()){
                        $extension = $form['imagen']->getData()->guessExtension();
                        if( in_array( $extension, array('png', 'jpg', 'jpeg', 'gif', 'bmp') ) ) {
                            if(!is_dir($candidato->getUploadDir()))      
                                       mkdir($candidato->getUploadDir(), 0777);                       

                            $nombre = md5(time());
                            $form['imagen']->getData()->move($candidato->getUploadDir(), $nombre.'.'.$extension);
                            $candidato->setImagen($nombre.$extension);
                        }
                    } 
                    $em->persist($candidato);
                    
                    $eventoCandidato = new EventoCandidato();
                    $eventoCandidato->setEvento($evento);
                    $eventoCandidato->setCandidato($candidato);
                    $em->persist($eventoCandidato);
                    
                    $evento->addEventoCandidato($eventoCandidato);
                    $candidato->addCandidatoEvento($eventoCandidato);                    
                    
                    $puntuaciones = $this->container->getParameter('puntuacion_candidato');
                    $puntuacion = $evento->getPuntuacionNuevoCandidato($puntuaciones);
                    
                    $voto = new Voto();
                    $voto->setEvento($evento);
                    $voto->setCandidato($candidato);
                    $voto->setUsuario($this->getUser());
                    $voto->setPuntos($puntuacion);
                    $em->persist($voto);
                    
                    $em->flush(); 
                    
                    $form = $this->createForm(new CandidatoType(), new Candidato());
                }
            } 
            
            return $this->render('FrontendBundle:Votacion:agregarCandidato.html.twig', array(
                'evento' => $evento,                
                'form' => $form->createView()
            ));
        }
        
        throw $this->createNotFoundException();
                
    }
    
    public function modificarEstadoVotacionAction()
    {
        $em = $this->getDoctrine()->getManager();    
        $estado = $this->getRequest()->get('estado');
        $id = $this->getRequest()->get('id');
        $evento = $id ? $em->getRepository('ModeloBundle:Evento')->find($id) : null;
        if($evento) {
            $evento->setActivo($estado);
            $em->persist($evento);
            $em->flush();
            
            return new Response( json_encode(array(
                'resultado' => 'ok', 
                'estado' => $evento->getActivo() ? 0 : 1,
                'texto' => $evento->getActivo() ? 'Publico' : 'No Publico'                
                )
            ));  
        }
        
        return new Response( json_encode(array('resultado' => 'error') ));  
    }
}


<?php
namespace Encuesta\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Encuesta\ModeloBundle\Entity\Usuario;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Encuesta\ModeloBundle\Form\EventoType;
use Encuesta\ModeloBundle\Entity\Evento;
use Encuesta\ModeloBundle\Entity\Candidato;
use Encuesta\ModeloBundle\Form\CandidatoType;

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
        if($evento) {
            
            $candidatos = $evento->getCandidatos();
            $candidato = new Candidato();
            $form = $this->createForm(new CandidatoType(), $candidato);
            return $this->render('FrontendBundle:Votacion:agregarCandidato.html.twig', array(
                'evento' => $evento,
                'form' => $form->createView()
            ));
        }
        
        throw $this->createNotFoundException();
                
    }
}


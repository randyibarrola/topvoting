<?php

namespace Encuesta\FrontendBundle\Listener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Encuesta\ModeloBundle\Entity\Evento;
use Encuesta\ModeloBundle\Entity\Categoria;
use Encuesta\ModeloBundle\Entity\Candidato;

class CargarEntidadIdioma
{
    private $container;    
    
    public function __construct(ContainerInterface $container)
    {
      $this->container = $container;          
    }    
    
    public function postLoad(LifecycleEventArgs $args)
    {
       $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        // perhaps you only want to act on some "X" entity
        $locale = $this->container->get('request')->getLocale();
        if ( $locale != 'es' && (  $entity instanceof Evento || $entity instanceof Candidato || $entity instanceof Categoria )) {
            $entity->setTranslatableLocale($locale);
            $entityManager->refresh($entity);
        } 
    }
}
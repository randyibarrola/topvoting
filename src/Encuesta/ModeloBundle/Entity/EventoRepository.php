<?php

namespace Encuesta\ModeloBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EventoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventoRepository extends EntityRepository
{
    /*
     * Retorna todos los eventos activos, si se le indica usuario, retorna solamente los creados por ese usuario
     */
    public function getEventosActivos($usuario)
    {
        $em = $this->getEntityManager();
        //$sql = 'SELECT e FROM ModeloBundle:Evento e WHERE e.fecha_fin >= :fecha and e.activo = 1'; 
        $sql = 'SELECT e FROM ModeloBundle:Evento e WHERE e.activo = 1'; 
        if($usuario){
            $sql .= ' and e.creador = :usuario';
        }
        $consulta = $em->createQuery($sql);
        if($usuario){
           $consulta->setParameter('usuario',$usuario); 
        }
	//$consulta->setParameter('fecha', new \DateTime('today'));
        return $consulta->getResult(); 
    }
    
    
}

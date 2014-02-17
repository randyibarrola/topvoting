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
    public function getEventosActivos($usuario = null)
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
    
    /*
     * Retorna los eventos publicados con mas votaciones, la cantidad se especifica como parametro, por defecto, 8
     */
    public function getEventosMasVotados($cantidad = 8, $idioma = 'es')
    {
        $em = $this->getEntityManager();
        //$sql = 'SELECT e FROM ModeloBundle:Evento e WHERE e.fecha_fin >= :fecha and e.activo = 1'; 
        $sql = 'SELECT e FROM ModeloBundle:Evento e WHERE e.activo = 1 and e.idioma like :idioma ORDER BY e.numero_votaciones DESC';         
        $consulta = $em->createQuery($sql);
        $consulta->setParameter('idioma',$idioma); 
        $consulta->setMaxResults($cantidad);       

        return $consulta->getResult(); 
    }  
    
    
    /*
     * Determina si un usuario ya voto por un evento determinado, retorna true en caso de existir, falso si no existe
     */
    public function ExisteVotoUsuario($evento, $usuario)
    {
        $em = $this->getEntityManager();
        $voto = $em->getRepository('ModeloBundle:Voto')->findOneBy(array('evento'=>$evento, 'usuario'=>$usuario));
        return $voto ? true : false;
    }      
    
    
}

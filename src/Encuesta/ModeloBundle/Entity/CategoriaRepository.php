<?php
namespace Encuesta\ModeloBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoriaRepository extends EntityRepository
{
    /*
     * Retorna todas las categorias Padres
     */
    public function getCategoriasPadres()
    {
        $em = $this->getEntityManager();
        //$sql = 'SELECT e FROM ModeloBundle:Evento e WHERE e.fecha_fin >= :fecha and e.activo = 1'; 
        $sql = 'SELECT c FROM ModeloBundle:Categoria c WHERE c.padre is null';  
        $consulta = $em->createQuery($sql);   

        return $consulta->getResult(); 
    }      

}
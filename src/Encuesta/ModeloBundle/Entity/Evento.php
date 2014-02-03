<?php

namespace Encuesta\ModeloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Evento
 *
 * @ORM\Table(name="evento")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\EventoRepository")
 */
class Evento 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
  /**
   * @var Usuario $creador
   *
   * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="eventos")
   * @ORM\JoinColumn(nullable=false, onDelete="cascade", nullable=true)
   * @Assert\Type(type="Encuesta\ModeloBundle\Entity\Usuario")
   */
    protected $creador;    

    /**
     * @var string
     * @Gedmo\Translatable     
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    private $titulo;

    /**
     * @var text $descripcion
     * @Gedmo\Translatable   
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;
    
    /**
     * @var integer $numero_votaciones
     *
     * @ORM\Column(name="numero_votaciones", type="integer", nullable=true)
     */
    private $numero_votaciones = 0;    

    /**
     * @var datetime $fecha_fin
     *
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */
    private $fecha_fin;
    
    /**
     * @var boolean $activo
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo = true;	
   

	/**
     * @var string $imagen
     *
     * @ORM\Column(name="imagen", type="string", length=255) 
     */
    private $imagen;
    
  /**
   * @var datetime $created_at
   *
   * @Gedmo\Timestampable(on="create")
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $created_at;

  /**
   * @var datetime $updated_at
   *
   * @Gedmo\Timestampable(on="update")
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $updated_at;  


   
}
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
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true) 
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


   

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Evento
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Evento
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set numero_votaciones
     *
     * @param integer $numeroVotaciones
     * @return Evento
     */
    public function setNumeroVotaciones($numeroVotaciones)
    {
        $this->numero_votaciones = $numeroVotaciones;
    
        return $this;
    }

    /**
     * Get numero_votaciones
     *
     * @return integer 
     */
    public function getNumeroVotaciones()
    {
        return $this->numero_votaciones;
    }

    /**
     * Set fecha_fin
     *
     * @param \DateTime $fechaFin
     * @return Evento
     */
    public function setFechaFin($fechaFin)
    {
        $this->fecha_fin = $fechaFin;
    
        return $this;
    }

    /**
     * Get fecha_fin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Evento
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     * @return Evento
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    
        return $this;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Evento
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Evento
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set creador
     *
     * @param \Encuesta\ModeloBundle\Entity\Usuario $creador
     * @return Evento
     */
    public function setCreador(\Encuesta\ModeloBundle\Entity\Usuario $creador = null)
    {
        $this->creador = $creador;
    
        return $this;
    }

    /**
     * Get creador
     *
     * @return \Encuesta\ModeloBundle\Entity\Usuario 
     */
    public function getCreador()
    {
        return $this->creador;
    }
    
    public function getUploadDir()
    {
        // the absolute directory path where uploaded
        // image profile should be saved
        return __DIR__.'/../../../../web/uploads/evento/'.$this->id;
    }  
    
    public function getImagenEvento()
    {
        return $this->imagen ? '/uploads/evento/'.$this->id.'/'.$this->imagen : null;
    }    
}
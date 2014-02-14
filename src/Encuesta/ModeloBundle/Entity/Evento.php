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
     * @var string   
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;   
    
    /**
     * @var string   
     * @ORM\Column(name="idioma", type="string", length=10, nullable=true)
     */
    private $idioma;     

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
    private $activo = false;	
   
    
    /**
     * @var boolean $activo
     *
     * @ORM\Column(name="destacado", type="boolean")
     */
    private $destacado = false;	    

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
   * @var ArrayCollection $evento_candidatos
   *
   * @ORM\OneToMany(targetEntity="EventoCandidato", mappedBy="evento")
   */
    protected $evento_candidatos; 
   

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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->evento_candidatos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add evento_candidatos
     *
     * @param \Encuesta\ModeloBundle\Entity\EventoCandidato $eventoCandidatos
     * @return Evento
     */
    public function addEventoCandidato(\Encuesta\ModeloBundle\Entity\EventoCandidato $eventoCandidatos)
    {
        $this->evento_candidatos[] = $eventoCandidatos;
    
        return $this;
    }

    /**
     * Remove evento_candidatos
     *
     * @param \Encuesta\ModeloBundle\Entity\EventoCandidato $eventoCandidatos
     */
    public function removeEventoCandidato(\Encuesta\ModeloBundle\Entity\EventoCandidato $eventoCandidatos)
    {
        $this->evento_candidatos->removeElement($eventoCandidatos);
    }

    /**
     * Get evento_candidatos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEventoCandidatos()
    {
        return $this->evento_candidatos;
    }
    
    public function getCandidatos()
    {
        $candidatos = array();
        foreach($this->evento_candidatos as $candidato)
            $candidatos[] = $candidato->getCandidato();

        return $candidatos;
    }

    private $filenameForRemove;
    private $imageDir;
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getImagen();
        $this->imageDir = __DIR__.'/../../../../web/uploads/evento/'.$this->getId();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove != null) {
            unlink($this->imageDir.'/'.$this->filenameForRemove);
            if(is_dir($this->imageDir))
                @rmdir($this->imageDir);
        }
    }
    
    /*
     * $puntuacion es un array de puntuaciones que esta en el config.yml
     */
    public function getPuntuacionNuevoCandidato($puntuacion)
    {
        $cantidad  = count($this->getEventoCandidatos());
        if(array_key_exists($cantidad, $puntuacion))
            return $puntuacion[$cantidad];
        return 1;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Evento
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set idioma
     *
     * @param string $idioma
     * @return Evento
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
    
        return $this;
    }

    /**
     * Get idioma
     *
     * @return string 
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set destacado
     *
     * @param boolean $destacado
     * @return Evento
     */
    public function setDestacado($destacado)
    {
        $this->destacado = $destacado;
    
        return $this;
    }

    /**
     * Get destacado
     *
     * @return boolean 
     */
    public function getDestacado()
    {
        return $this->destacado;
    }
}
<?php

namespace Encuesta\ModeloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;
use Encuesta\ModeloBundle\Entity\Categoria;

/**
 * Candidato
 *
 * @ORM\Table(name="candidato")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Candidato {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $titulo
     * @Gedmo\Translatable   
     * @ORM\Column(name="titulo", type="string", length=200, unique=true )
     * @Assert\NotBlank()
     */
    protected $titulo;
	
    /**
     * @var text $descripcion
     * @Gedmo\Translatable   
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;	
    
    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="candidatos")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade", nullable=true)
     **/
    private $categoria;  
	
    /**
    * @var ArrayCollection $candidato_eventos
    *
    * @ORM\OneToMany(targetEntity="EventoCandidato", mappedBy="candidato")
    */
     protected $candidato_eventos;     
    
    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     * @Assert\Image(maxSize="500k")
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
     * @return Candidato
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
     * @return Candidato
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
     * Set imagen
     *
     * @param string $imagen
     * @return Candidato
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
     * @return Candidato
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
     * @return Candidato
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
     * Set categoria
     *
     * @param \Encuesta\ModeloBundle\Entity\Categoria $categoria
     * @return Candidato
     */
    public function setCategoria(\Encuesta\ModeloBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Encuesta\ModeloBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    private $filenameForRemove;
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getImagen();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove != null) {
            unlink(__DIR__.'/../../../../web/uploads/images/candidato/'.$this->filenameForRemove);
        }
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->candidato_eventos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add candidato_eventos
     *
     * @param \Encuesta\ModeloBundle\Entity\EventoCandidato $candidatoEventos
     * @return Candidato
     */
    public function addCandidatoEvento(\Encuesta\ModeloBundle\Entity\EventoCandidato $candidatoEventos)
    {
        $this->candidato_eventos[] = $candidatoEventos;
    
        return $this;
    }

    /**
     * Remove candidato_eventos
     *
     * @param \Encuesta\ModeloBundle\Entity\EventoCandidato $candidatoEventos
     */
    public function removeCandidatoEvento(\Encuesta\ModeloBundle\Entity\EventoCandidato $candidatoEventos)
    {
        $this->candidato_eventos->removeElement($candidatoEventos);
    }

    /**
     * Get candidato_eventos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCandidatoEventos()
    {
        return $this->candidato_eventos;
    }
    
    public function getEventos()
    {
        $eventos = array();
        foreach($this->candidato_eventos as $evento)
            $eventos[] = $evento;
        
        return $eventos;
    }    
}
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
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\CandidatoRepository")
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
     * @var string $idioma  
     * @ORM\Column(name="idioma", type="string", length=10, nullable=true )
     */
    protected $idioma;    
	
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
    private $imageDir;
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getImagen();
        $this->imageDir = __DIR__.'/../../../../web/uploads/candidato/'.$this->getId();
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
            $eventos[] = $evento->getEvento();
        
        return $eventos;
    }
    
    public function getUploadDir()
    {
        // the absolute directory path where uploaded
        // image profile should be saved
        return __DIR__.'/../../../../web/uploads/candidato/'.$this->id;
    }  
    
    public function getImagenEvento()
    {
        return $this->imagen ? '/uploads/candidato/'.$this->id.'/'.$this->imagen : null;
    }      

    /**
     * Set idioma
     *
     * @param string $idioma
     * @return Candidato
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
}
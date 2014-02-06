<?php

namespace Encuesta\ModeloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Categoria
 *
 * @ORM\Table(name="categoria")
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\CategoriaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Categoria {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $nombre
     * @Gedmo\Translatable   
     * @ORM\Column(name="nombre", type="string", length=200, unique=true )
     * @Assert\NotBlank()
     */
    protected $nombre;
	
    /**
     * @ORM\OneToMany(targetEntity="Categoria", mappedBy="padre", cascade={"remove"})
     **/
    private $subcategorias;
    
    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="subcategorias", cascade = {"persist"})
     * @ORM\JoinColumn(name="padre_id", referencedColumnName="id", onDelete="set null", nullable=true)
     **/
    private $padre;  
	
    /**
     * @var text $descripcion
     * @Gedmo\Translatable   
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;	
	
	/**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", nullable=true)
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
     * Constructor
     */
    public function __construct()
    {
        $this->subcategorias = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set nombre
     *
     * @param string $nombre
     * @return Categoria
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Categoria
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
     * @return Categoria
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
     * @return Categoria
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
     * @return Categoria
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
     * Add subcategorias
     *
     * @param \Encuesta\ModeloBundle\Entity\Categoria $subcategorias
     * @return Categoria
     */
    public function addSubcategoria(\Encuesta\ModeloBundle\Entity\Categoria $subcategorias)
    {
        $this->subcategorias[] = $subcategorias;
    
        return $this;
    }

    /**
     * Remove subcategorias
     *
     * @param \Encuesta\ModeloBundle\Entity\Categoria $subcategorias
     */
    public function removeSubcategoria(\Encuesta\ModeloBundle\Entity\Categoria $subcategorias)
    {
        $this->subcategorias->removeElement($subcategorias);
    }

    /**
     * Get subcategorias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubcategorias()
    {
        return $this->subcategorias;
    }

    /**
     * Set padre
     *
     * @param \Encuesta\ModeloBundle\Entity\Categoria $padre
     * @return Categoria
     */
    public function setPadre(\Encuesta\ModeloBundle\Entity\Categoria $padre = null)
    {
        $this->padre = $padre;
    
        return $this;
    }

    /**
     * Get padre
     *
     * @return \Encuesta\ModeloBundle\Entity\Categoria 
     */
    public function getPadre()
    {
        return $this->padre;
    }

    public function __toString()
    {
        return $this->getNombre();
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
            unlink(__DIR__.'/../../../../web/uploads/images/categoria/'.$this->filenameForRemove);
        }
    }
}
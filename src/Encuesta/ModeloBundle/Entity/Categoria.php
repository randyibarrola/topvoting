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
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\CategoriaRepository")
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
     */
    protected $nombre;
	
    /**
     * @ORM\OneToMany(targetEntity="Categoria", mappedBy="padre")
     **/
    private $subcategorias;	
	
    /**
     * @ORM\OneToMany(targetEntity="Categoria", mappedBy="categoria")
     **/
    private $candidatos;		
    
    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="subcategorias")
     * @ORM\JoinColumn(name="padre_id", referencedColumnName="id")
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
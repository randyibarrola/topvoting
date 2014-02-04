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
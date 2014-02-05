<?php

namespace Encuesta\ModeloBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("username")
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\UsuarioRepository")
 */
class Usuario implements UserInterface, \Serializable
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
   * @var ArrayCollection $eventos
   *
   * @ORM\OneToMany(targetEntity="Evento", mappedBy="creador")
   */
    protected $eventos; 	
    
  /**
   * @var Sucursal $sucursal
   *
   * @ORM\ManyToOne(targetEntity="Sucursal", inversedBy="usuarios")
   * @ORM\JoinColumn(nullable=false, onDelete="cascade", nullable=true)
   * @Assert\Type(type="Encuesta\ModeloBundle\Entity\Sucursal")
   */
    protected $sucursal;    

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     */
    private $apellidos;
    
    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=50, nullable=true)
     */
    private $telefono;    

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo = true;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="categoria", type="integer", nullable=true)
     */
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
  



    
    public function serialize()
    {
           return serialize($this->id);
    }

    public function unserialize($data)
    {
           $this->id = unserialize($data);
    }    

    public function __sleep()
    {
        return array($this->id); // add your own fields
    }


   
}
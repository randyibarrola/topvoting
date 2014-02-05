<?php

namespace Encuesta\ModeloBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use \Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Encuesta\ModeloBundle\Entity\Rol
 * @ORM\Entity
 * @ORM\Table(name="rol")
 */
class Rol implements RoleInterface, \Serializable
{
  /**
   * @var integer $id
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="nombre", type="string", length=120)
   */
  private $nombre;

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  public function getRole()
  {
    return $this->nombre;
  }

 

  /**
   * @see \Serializable::serialize()
   */
  public function serialize()
  {
    /*
     * ! Don't serialize $users field !
     */
    return \serialize(array(
        $this->id,
        $this->nombre
      ));
  }

  /**
   * @see \Serializable::unserialize()
   */
  public function unserialize($serialized)
  {
    list(
      $this->id,
      $this->nombre
      ) = \unserialize($serialized);
  }


    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Rol
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
    
    public function __toString()
    {
        return $this->nombre;
    }
}
<?php

namespace Encuesta\ModeloBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
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
 * @ORM\HasLifecycleCallbacks
 */
class Usuario implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;    

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
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true) 
     */
    private $imagen;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_activacion", type="string", length=255, unique=true, nullable=true)
     */
    private $codigo_activacion;      
    
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
   * se utilizó user_roles para no hacer conflicto al aplicar ->toArray en getRoles()
   * @ORM\ManyToMany(targetEntity="Rol")
   * @ORM\JoinTable(name="usuario_rol",
   *     joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="cascade")},
   *     inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id")}
   * )
   */
  protected $usuario_roles;  

    
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


    public function eraseCredentials() {
        
    }



   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario_roles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Usuario
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    
        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Usuario
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
     * Set categoria
     *
     * @param integer $categoria
     * @return Usuario
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return integer 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     * @return Usuario
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
     * @return Usuario
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
     * @return Usuario
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
     * Add eventos
     *
     * @param \Encuesta\ModeloBundle\Entity\Evento $eventos
     * @return Usuario
     */
    public function addEvento(\Encuesta\ModeloBundle\Entity\Evento $eventos)
    {
        $this->eventos[] = $eventos;
    
        return $this;
    }

    /**
     * Remove eventos
     *
     * @param \Encuesta\ModeloBundle\Entity\Evento $eventos
     */
    public function removeEvento(\Encuesta\ModeloBundle\Entity\Evento $eventos)
    {
        $this->eventos->removeElement($eventos);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEventos()
    {
        return $this->eventos;
    }

    /**
     * Add usuario_roles
     *
     * @param \Encuesta\ModeloBundle\Entity\Rol $usuarioRoles
     * @return Usuario
     */
    public function addUsuarioRole(\Encuesta\ModeloBundle\Entity\Rol $usuarioRoles)
    {
        $this->usuario_roles[] = $usuarioRoles;
    
        return $this;
    }

    /**
     * Remove usuario_roles
     *
     * @param \Encuesta\ModeloBundle\Entity\Rol $usuarioRoles
     */
    public function removeUsuarioRole(\Encuesta\ModeloBundle\Entity\Rol $usuarioRoles)
    {
        $this->usuario_roles->removeElement($usuarioRoles);
    }

    /**
     * Get usuario_roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarioRoles()
    {
        return $this->usuario_roles;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRoles() {
        return $this->usuario_roles->toArray();
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->username;
    }
    
    public function getUsuarioRolesString()
    {
        $nombres = array();
        foreach($this->usuario_roles as $rol)
            $nombres[] = $rol->getNombre();
        return implode(',', $nombres) ;
    }    



    /**
     * Set codigo_activacion
     *
     * @param string $codigoActivacion
     * @return Usuario
     */
    public function setCodigoActivacion($codigoActivacion)
    {
        $this->codigo_activacion = $codigoActivacion;
    
        return $this;
    }

    /**
     * Get codigo_activacion
     *
     * @return string 
     */
    public function getCodigoActivacion()
    {
        return $this->codigo_activacion;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->activo;
    }
    
    public function getUploadDir()
    {
        // the absolute directory path where uploaded
        // image profile should be saved
        return __DIR__.'/../../../../web/uploads/perfil/'.$this->id;
    }  
    
    public function getImagenPerfil()
    {
        return $this->imagen ? '/uploads/perfil/'.$this->id.'/'.$this->imagen : null;
    }
    
    public function __toString()
    {
        return $this->nombre. ' '.$this->apellidos;
    }
	public function getNombreCompleto()
    {
        return $this->nombre.' '.$this->apellidos;
    }

    private $filenameForRemove;
    private $imageDir;
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getImagen();
        $this->imageDir = __DIR__.'/../../../../web/uploads/perfil/'.$this->getId();
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
}
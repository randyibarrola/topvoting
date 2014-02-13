<?php

namespace Encuesta\ModeloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Voto
 *
 * @ORM\Table(name="voto")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\VotoRepository")
 */
class Voto 
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
   * @var Usuario $usuario
   *
   * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="votos")
   * @ORM\JoinColumn(nullable=false, onDelete="cascade")
   * @Assert\Type(type="Encuesta\ModeloBundle\Entity\Usuario")
   */
    protected $usuario;    
    
  /**
   * @var Evento $evento
   *
   * @ORM\ManyToOne(targetEntity="Evento", inversedBy="votos")
   * @ORM\JoinColumn(nullable=false, onDelete="cascade")
   * @Assert\Type(type="Encuesta\ModeloBundle\Entity\Evento")
   */
    protected $evento;   
    
  /**
   * @var Candidato $candidato
   *
   * @ORM\ManyToOne(targetEntity="Candidato", inversedBy="votos")
   * @ORM\JoinColumn(nullable=false, onDelete="cascade")
   * @Assert\Type(type="Encuesta\ModeloBundle\Entity\Candidato")
   */
    protected $candidato;     
    
    /**
     * @var integer $puntos
     *
     * @ORM\Column(name="puntos", type="integer", nullable=true)
     */
    private $puntos = 0;    

   
    
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
     * Set puntos
     *
     * @param integer $puntos
     * @return Voto
     */
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;
    
        return $this;
    }

    /**
     * Get puntos
     *
     * @return integer 
     */
    public function getPuntos()
    {
        return $this->puntos;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Voto
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
     * @return Voto
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
     * Set usuario
     *
     * @param \Encuesta\ModeloBundle\Entity\Usuario $usuario
     * @return Voto
     */
    public function setUsuario(\Encuesta\ModeloBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Encuesta\ModeloBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set evento
     *
     * @param \Encuesta\ModeloBundle\Entity\Evento $evento
     * @return Voto
     */
    public function setEvento(\Encuesta\ModeloBundle\Entity\Evento $evento)
    {
        $this->evento = $evento;
    
        return $this;
    }

    /**
     * Get evento
     *
     * @return \Encuesta\ModeloBundle\Entity\Evento 
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * Set candidato
     *
     * @param \Encuesta\ModeloBundle\Entity\Candidato $candidato
     * @return Voto
     */
    public function setCandidato(\Encuesta\ModeloBundle\Entity\Candidato $candidato)
    {
        $this->candidato = $candidato;
    
        return $this;
    }

    /**
     * Get candidato
     *
     * @return \Encuesta\ModeloBundle\Entity\Candidato 
     */
    public function getCandidato()
    {
        return $this->candidato;
    }
}
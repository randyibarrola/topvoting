<?php

namespace Encuesta\ModeloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Evento
 *
 * @ORM\Table(name="evento_candidato")
 * @ORM\Entity
 */
class EventoCandidato 
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
   * @var Evento $evento
   *
   * @ORM\ManyToOne(targetEntity="Evento", inversedBy="evento_candidatos")
   * @ORM\JoinColumn(nullable=false, onDelete="cascade")
   */
    protected $evento;  
    
  /**
   * @var Candidato $candidato
   *
   * @ORM\ManyToOne(targetEntity="Candidato", inversedBy="candidato_eventos")
   * @ORM\JoinColumn(nullable=false, onDelete="cascade")
   */
    protected $candidato;    
    


    
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return EventoCandidato
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
     * @return EventoCandidato
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
     * Set evento
     *
     * @param \Encuesta\ModeloBundle\Entity\Evento $evento
     * @return EventoCandidato
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
     * @return EventoCandidato
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
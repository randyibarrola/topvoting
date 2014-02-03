<?php

namespace PanelControl\ModeloBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use PanelControl\ModeloBundle\Entity\Producto;
use PanelControl\EmpresaBundle\Util\Util;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="PanelControl\ModeloBundle\Entity\PlanningRepository")
 * @ORM\Table(name="planning", uniqueConstraints={@ORM\UniqueConstraint(name="nombreUnico", columns={"producto_id", "nombre"})})
 * @Gedmo\SoftDeleteable(fieldName="deleted_at")
 */
class Planning
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @var ArrayCollection
   *
   * @ORM\OneToMany(targetEntity="PlanningArtista", mappedBy="planning")
   */
  private $artistas;

  /**
   * @var string
   *
   * @ORM\Column(type="string", length=50)
   * @Assert\NotBlank()
   */
  protected $nombre;

  /**
   * @var string
   *
   * @ORM\Column(type="string", length=200)
   * @Assert\NotBlank()
   */
  protected $slug;

  /**
   * @var string
   *
   * @ORM\Column(name="descripcion", type="text", nullable=true)
   * @Gedmo\Translatable
   */
  private $descripcion;

  /**
   * @Gedmo\Locale
   */
  private $locale;

  /**
   * @var Producto $producto
   *
   * @ORM\ManyToOne(targetEntity="Producto", inversedBy="plannings")
   * @ORM\JoinColumn(nullable=false, onDelete="cascade")
   */
  private $producto;

  /**
   * @var Imagen $imagen
   *
   * @ORM\ManyToOne(targetEntity="Imagen", inversedBy="plannings", cascade = {"persist", "remove"})
   */
  private $imagen;

  /**
   * @var ArrayCollection $links
   *
   * @ORM\OneToMany(targetEntity = "Link", mappedBy = "planning", cascade = {"persist", "remove"})
   */
  protected $links;

  /**
   * @var ArrayCollection $imagenes
   *
   * @ORM\ManyToMany(targetEntity="Imagen", inversedBy="plannings")
   * @ORM\JoinTable(name="planning_imagenes", joinColumns={@ORM\JoinColumn(name="planning_id", referencedColumnName="id")}, inverseJoinColumns={@ORM\JoinColumn(name="imagen_id", referencedColumnName="id")})
   */
  private $imagenes;

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
   * @var datetime $deleted_at
   *
   * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
   */
  private $deleted_at;

  /**
   * @var ArrayCollection $canal_ventas
   *
   * @ORM\OneToMany(targetEntity="CanalVenta", mappedBy="planning")
   */
  private $canal_ventas;

  /**
   * @var ArrayCollection $excepciones
   *
   * @ORM\OneToMany(targetEntity="Excepcion", mappedBy="planning")
   */
  protected $excepciones;

  /**
   * @var ArrayCollection $tarifa_planning
   *
   * @ORM\OneToMany(targetEntity="TarifaPlanning", mappedBy="planning")
   */
  protected $tarifa_planning;

  /**
   * @var $orden_reglas_configuracion
   *
   * @ORM\Column(type="array", nullable=true)
   */
  protected $orden_reglas_configuracion;

  /**
   * @var Etiqueta $etiqueta
   *
   * @ORM\ManyToOne(targetEntity="Etiqueta")
   */
  protected $etiqueta;

  /**
   *
   * @ORM\ManyToOne(targetEntity="Galeria", inversedBy="plannings")
   * @ORM\JoinColumn(name="galeria_id", referencedColumnName="id")
   */
  protected $galeria;

  /**
   * @var ArrayCollection $generos
   *
   * @ORM\OneToMany(targetEntity="PlanningGenero", mappedBy="planning")
   */
  private $generos;

  /**
   * @var ArrayCollection $formatos
   *
   * @ORM\OneToMany(targetEntity="PlanningFormato", mappedBy="planning")
   */
  private $formatos;

  /**
   * @var ArrayCollection $tarifas
   *
   * @ORM\OneToMany(targetEntity="Tarifa", mappedBy="planning")
   */
  private $tarifas;

  /**
   * @ORM\ManyToOne(targetEntity = "Moneda", inversedBy = "plannings")
   * @ORM\JoinColumn(onDelete="set null")
   */
  protected $moneda;

  /**
   * @ORM\OneToOne(targetEntity="ObraDetalle", mappedBy="planning")
   */
  protected $obra_detalle;

  /**
   * @var type ArrayCollection
   *
   * @ORM\OneToMany(targetEntity="ObraOpinion", mappedBy="planning")
   */
  private $opiniones;

  /**
   * @var ArrayCollection
   *
   * @ORM\OneToMany(targetEntity="TarifaDetalle", mappedBy="planning")
   */
  private $tarifa_detalle;

  public function __toString()
  {
    return $this->getNombre();
  }
  
  public function getTarifasActuales()
  {
    $tarifas = array();
    foreach ($this->tarifas as $tarifa)
    {
      if ($tarifa->getDeletedAt() == null && $tarifa->getActivo())
        $tarifas[] = $tarifa;
    }
    return $tarifas;
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
   */
  public function setNombre($nombre)
  {
    $this->nombre = $nombre;
    $util = new Util();
    $this->slug = $util->getSlug($nombre,'_');
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
   * @param text $descripcion
   */
  public function setDescripcion($descripcion)
  {
    $this->descripcion = $descripcion;
  }

  /**
   * Get descripcion
   *
   * @return text
   */
  public function getDescripcion()
  {
    return $this->descripcion;
  }

  /**
   * Set created_at
   *
   * @param datetime $createdAt
   */
  public function setCreatedAt($createdAt)
  {
    $this->created_at = $createdAt;
  }

  /**
   * Get created_at
   *
   * @return datetime
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }

  /**
   * Set updated_at
   *
   * @param datetime $updatedAt
   */
  public function setUpdatedAt($updatedAt)
  {
    $this->updated_at = $updatedAt;
  }

  /**
   * Get updated_at
   *
   * @return datetime
   */
  public function getUpdatedAt()
  {
    return $this->updated_at;
  }

  /**
   * Set deleted_at
   *
   * @param datetime $deletedAt
   */
  public function setDeletedAt($deletedAt)
  {
    $this->deleted_at = $deletedAt;
  }

  /**
   * Get deleted_at
   *
   * @return datetime
   */
  public function getDeletedAt()
  {
    return $this->deleted_at;
  }

  /**
   * Set orden_reglas_configuracion
   *
   * @param array $ordenReglasConfiguracion
   */
  public function setOrdenReglasConfiguracion($ordenReglasConfiguracion)
  {
    $this->orden_reglas_configuracion = $ordenReglasConfiguracion;
  }

  /**
   * Get orden_reglas_configuracion
   *
   * @return array
   */
  public function getOrdenReglasConfiguracion()
  {
    return $this->orden_reglas_configuracion;
  }

  /**
   * Add artistas
   *
   * @param PanelControl\ModeloBundle\Entity\PlanningArtista $artistas
   */
  public function addPlanningArtista(\PanelControl\ModeloBundle\Entity\PlanningArtista $artistas)
  {
    $this->artistas[] = $artistas;
  }

  /**
   * Get artistas
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getArtistas()
  {
    return $this->artistas;
  }

  /**
   * Set producto
   *
   * @param PanelControl\ModeloBundle\Entity\Producto $producto
   */
  public function setProducto(\PanelControl\ModeloBundle\Entity\Producto $producto)
  {
    $this->producto = $producto;
  }

  /**
   * Get producto
   *
   * @return PanelControl\ModeloBundle\Entity\Producto
   */
  public function getProducto()
  {
    return $this->producto;
  }

  /**
   * Set imagen
   *
   * @param PanelControl\ModeloBundle\Entity\Imagen $imagen
   */
  public function setImagen(\PanelControl\ModeloBundle\Entity\Imagen $imagen)
  {
    $this->imagen = $imagen;
  }

  /**
   * Get imagen
   *
   * @return PanelControl\ModeloBundle\Entity\Imagen
   */
  public function getImagen()
  {
    return $this->imagen;
  }

  /**
   * Add links
   *
   * @param PanelControl\ModeloBundle\Entity\Link $links
   */
  public function addLink(\PanelControl\ModeloBundle\Entity\Link $links)
  {
    $this->links[] = $links;
  }

  /**
   * Get links
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getLinks()
  {
    return $this->links;
  }

  /**
   * Add imagenes
   *
   * @param PanelControl\ModeloBundle\Entity\Imagen $imagenes
   */
  public function addImagen(\PanelControl\ModeloBundle\Entity\Imagen $imagenes)
  {
    $this->imagenes[] = $imagenes;
  }

  /**
   * Get imagenes
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getImagenes()
  {
    return $this->imagenes;
  }

  /**
   * Add canal_ventas
   *
   * @param PanelControl\ModeloBundle\Entity\CanalVenta $canalVentas
   */
  public function addCanalVenta(\PanelControl\ModeloBundle\Entity\CanalVenta $canalVentas)
  {
    $this->canal_ventas[] = $canalVentas;
  }

  /**
   * Get canal_ventas
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getCanalVentas()
  {
    return $this->canal_ventas;
  }

  /**
   * Add excepciones
   *
   * @param PanelControl\ModeloBundle\Entity\Excepcion $excepciones
   */
  public function addExcepcion(\PanelControl\ModeloBundle\Entity\Excepcion $excepciones)
  {
    $this->excepciones[] = $excepciones;
  }

  /**
   * Get excepciones
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getExcepciones()
  {
    return $this->excepciones;
  }

  /**
   * Add tarifa_planning
   *
   * @param PanelControl\ModeloBundle\Entity\TarifaPlanning $tarifaPlanning
   */
  public function addTarifaPlanning(\PanelControl\ModeloBundle\Entity\TarifaPlanning $tarifaPlanning)
  {
    $this->tarifa_planning[] = $tarifaPlanning;
  }

  /**
   * Get tarifa_planning
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getTarifaPlanning()
  {
    return $this->tarifa_planning;
  }

  /**
   * Set etiqueta
   *
   * @param PanelControl\ModeloBundle\Entity\Etiqueta $etiqueta
   */
  public function setEtiqueta(\PanelControl\ModeloBundle\Entity\Etiqueta $etiqueta)
  {
    $this->etiqueta = $etiqueta;
  }

  /**
   * Get etiqueta
   *
   * @return PanelControl\ModeloBundle\Entity\Etiqueta
   */
  public function getEtiqueta()
  {
    return $this->etiqueta;
  }

  /**
   * Set galeria
   *
   * @param PanelControl\ModeloBundle\Entity\Galeria $galeria
   */
  public function setGaleria(\PanelControl\ModeloBundle\Entity\Galeria $galeria)
  {
    $this->galeria = $galeria;
  }

  /**
   * Get galeria
   *
   * @return PanelControl\ModeloBundle\Entity\Galeria
   */
  public function getGaleria()
  {
    return $this->galeria;
  }

  /**
   * Add generos
   *
   * @param PanelControl\ModeloBundle\Entity\PlanningGenero $generos
   */
  public function addPlanningGenero(\PanelControl\ModeloBundle\Entity\PlanningGenero $generos)
  {
    $this->generos[] = $generos;
  }

  /**
   * Get generos
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getGeneros()
  {
    return $this->generos;
  }

  /**
   * Add formatos
   *
   * @param PanelControl\ModeloBundle\Entity\PlanningFormato $formatos
   */
  public function addPlanningFormato(\PanelControl\ModeloBundle\Entity\PlanningFormato $formatos)
  {
    $this->formatos[] = $formatos;
  }

  /**
   * Get formatos
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getFormatos()
  {
    return $this->formatos;
  }

  /**
   * Add tarifas
   *
   * @param PanelControl\ModeloBundle\Entity\Tarifa $tarifas
   */
  public function addTarifa(\PanelControl\ModeloBundle\Entity\Tarifa $tarifas)
  {
    $this->tarifas[] = $tarifas;
  }

  /**
   * Get tarifas
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getTarifas()
  {
    return $this->tarifas;
  }

  /**
   * Set moneda
   *
   * @param PanelControl\ModeloBundle\Entity\Moneda $moneda
   */
  public function setMoneda(\PanelControl\ModeloBundle\Entity\Moneda $moneda)
  {
    $this->moneda = $moneda;
  }

  /**
   * Get moneda
   *
   * @return PanelControl\ModeloBundle\Entity\Moneda
   */
  public function getMoneda()
  {
    return $this->moneda;
  }

  /**
   * Set obra_detalle
   *
   * @param PanelControl\ModeloBundle\Entity\ObraDetalle $obraDetalle
   */
  public function setObraDetalle(\PanelControl\ModeloBundle\Entity\ObraDetalle $obraDetalle)
  {
    $this->obra_detalle = $obraDetalle;
  }

  /**
   * Get obra_detalle
   *
   * @return PanelControl\ModeloBundle\Entity\ObraDetalle
   */
  public function getObraDetalle()
  {
    return $this->obra_detalle;
  }

    /**
     * Add opiniones
     *
     * @param PanelControl\ModeloBundle\Entity\ObraOpinion $opiniones
     */
    public function addObraOpinion(\PanelControl\ModeloBundle\Entity\ObraOpinion $opiniones)
    {
        $this->opiniones[] = $opiniones;
    }

    /**
     * Get opiniones
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getOpiniones()
    {
        return $this->opiniones;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function __construct()
    {
      $this->artistas = new ArrayCollection();
      $this->links = new ArrayCollection();
      $this->imagenes = new ArrayCollection();
      $this->canal_ventas = new ArrayCollection();
      $this->excepciones = new ArrayCollection();
      $this->tarifa_planning = new ArrayCollection();
      $this->generos = new ArrayCollection();
      $this->formatos = new ArrayCollection();
      $this->tarifas = new ArrayCollection();
      $this->opiniones = new ArrayCollection();
      $this->tarifa_detalle = new ArrayCollection();
    }

    /**
     * Add tarifa_detalle
     *
     * @param PanelControl\ModeloBundle\Entity\TarifaDetalle $tarifaDetalle
     */
    public function addTarifaDetalle(\PanelControl\ModeloBundle\Entity\TarifaDetalle $tarifaDetalle)
    {
        $this->tarifa_detalle[] = $tarifaDetalle;
    }

    /**
     * Get tarifa_detalle
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTarifaDetalle()
    {
        return $this->tarifa_detalle;
    }

    public function getNombreVisual($caracteres = 0)
    {
        if (strlen($this->nombre) > $caracteres)
            return substr($this->nombre, 0, $caracteres) . ' (...)';
        return $this->nombre;
    }
}
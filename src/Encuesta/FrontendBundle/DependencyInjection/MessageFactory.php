<?php

namespace Encuesta\FrontendBundle\DependencyInjection;

use Encuesta\ModeloBundle\Entity\Usuario;
use Encuesta\ModeloBundle\Entity\Votacion;
use Encuesta\ModeloBundle\Entity\Evento;
use Swift_Message;


class MessageFactory
{
  /**
   * @var Symfony\Bundle\TwigBundle\TwigEngine
   */
  private $templateEngine;

  /**
   * @var string
   */
  private $rootDir;


  /**
   * @var Symfony\Component\Filesystem\Filesystem
   */
  private $fileSystem;

  /**
   * @var array
   */
  private $from;


  /**
   *
   * @param type $container
   * @param type $rootDir
   * @param type $from
   */
  public function __construct($container, $rootDir, $from)
  {
    $this->templateEngine = $container->get('templating');
    $this->fileSystem = $container->get('filesystem');
    $this->rootDir = $rootDir;
    $this->from = $from;
  }
  
  public function getMsgCreacionCuenta(Usuario $usuario)
  {    
    
    $msg = Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($this->from)
      ->setTo($usuario->getEmail(), $usuario->getNombre());

    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('FrontendBundle:Messages:creacionCuenta.html.twig', array(      
      'usuario' => $usuario
      ));

    $msg->addPart($htmlPart, 'text/html');
    
    return $msg;
  }


  /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @return \Swift_Message
   */
  public function getMsgReservaCreadaParaCliente(Reserva $reserva)
  {
    $msg = Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($this->from)
      ->setTo($reserva->getCliente()->getEmail(), $reserva->getIidentidad()->getNombreCompleto());

    //El cuerpo en texto plano
    $msg
      ->addPart($this->templateEngine->render('FrontendBundle:Messages:avisoReservaCreadaParaCliente.txt.twig', array(
          'usuario' => $usuario,
          'identidad' => $identidad
        )), 'text/plain')
    ;
    
    


    $checkPath = $this->rootDir . '/../web/images/aceptarGrande.png';
    $check = $msg->embed(\Swift_Image::fromPath($checkPath));

    $texto = $reserva->recuperarTextoQR();

    $qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    foreach ($reserva->getProducto()->getTelefonos() as $telef)
    {
      if ($telef->getClasificacion() == 3)
      {
        $telefonoRecepcion = "({$telef->getCodigo()}) {$telef->getNumero()}";
      }
    }

    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('ModeloBundle:Messages:avisoReservaCreadaParaCliente.html.twig', array(
      'telefonoRecepcion' => isset($telefonoRecepcion) ? $telefonoRecepcion : '',
      'emailReserva' => $emailReserva,
      'montoPrepago' => 0,
      'reserva' => $reserva,
      'images' => array(
        'qr' => $qr,
        'check' => $check
      ),
      'identidad' => $identidad
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @return \Swift_Message
   */
  public function getMsgReservaAprobadaParaCliente(Reserva $reserva)
  {
    $msg = Swift_Message::newInstance('Aviso: Reserva aprobada')
      ->setFrom($this->from)
      ->setTo($reserva->getCliente()->getEmail(), $reserva->getIdentidad()->getNombreCompleto())
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:avisoReservaCreadaParaCliente.txt.twig', array(
        'reserva' => $reserva,
        'identidad' => $identidad
      )), 'text/plain')
    ;

    $checkPath = $this->rootDir . '/../web/images/aceptarGrande.png';
    $params = array(
      'reserva' => $reserva,
      'identidad' => $identidad,
      'check' => $msg->embed(\Swift_Image::fromPath($checkPath))
    );

    $texto = $reserva->recuperarTextoQR();

    $qrImage = $this->qrImageGenerator->getImage($texto);
    $params['qrimage'] = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));

    $msg->addPart($this->templateEngine->render('ModeloBundle:Messages:avisoReservaCreadaParaCliente.html.twig', $params), 'text/html');

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param string $urlModificarReserva
   * @return \Swift_Message
   */
  public function getMsgReservaCreadaParaHotel(Reserva $reserva, $urlModificarReserva)
  {
    $msg = \Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($this->from)
    ;
    $producto = $reserva->getProducto();
    $empresa = $producto->getEmpresa()->getEmpresaPadre() ? $producto->getEmpresa()->getEmpresaPadre() : $producto->getEmpresa();

    foreach ($producto->getEmails() as $email)
    {
      $msg->addTo($email->getEmail());
    }

    $msg
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:avisoReservaCreadaParaHotel.txt.twig', array(
          'reserva' => $reserva,
          'url' => $urlModificarReserva,
          'empresaDistribuidora' => $empresa
        )), 'text/plain')
    ;

    $logotipo = $producto->getLogo() && file_exists($this->rootDir . '/../web/' . $producto->getLogo()) ? $this->rootDir . '/../web/' . $producto->getLogo() : $this->rootDir . '/../web/images/logos/logoWiki.png';
    $msg
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:avisoReservaCreadaParaHotel.html.twig', array(
          'reserva' => $reserva,
          'logotipo' => $msg->embed(\Swift_Image::fromPath($logotipo)),
          'empresaDistribuidora' => $empresa,
          'url' => $urlModificarReserva
        )), 'text/html')
    ;

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @return \Swift_Message
   */
  public function getMsgReservaAprobadaParaHotel(Reserva $reserva, $urlModificarReserva)
  {
    $msg = \Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($this->from)
    ;
    $producto = $reserva->getProducto();
    $empresa = $producto->getEmpresa()->getEmpresaPadre() ? $producto->getEmpresa()->getEmpresaPadre() : $producto->getEmpresa();

    foreach ($producto->getEmails() as $email)
    {
      $msg->addTo($email->getEmail());
    }

    $msg
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:avisoReservaAprobadaParaHotel.txt.twig', array(
          'reserva' => $reserva,
          'url' => $urlModificarReserva,
          'empresaDistribuidora' => $empresa
        )), 'text/plain')
    ;

    $logotipo = $producto->getLogo() && file_exists($this->rootDir . '/../web/' . $producto->getLogo()) ? $this->rootDir . '/../web/' . $producto->getLogo() : $this->rootDir . '/../web/images/logos/logoWiki.png';
    $msg
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:avisoReservaAprobadaParaHotel.html.twig', array(
          'reserva' => $reserva,
          'logotipo' => $msg->embed(\Swift_Image::fromPath($logotipo)),
          'empresaDistribuidora' => $empresa,
          'url' => $urlModificarReserva
        )), 'text/html')
    ;

    return $msg;
  }

  public function getMsgReservaAprobacionAutomaticaParaCliente(Reserva $reserva)
  {
    $msg = \Swift_Message::newInstance()
      ->setFrom($this->from)
      ->setSubject('Aviso: Reserva creada');

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Usuario $usuario
   * @param string $asunto
   * @param \PanelControl\ModeloBundle\Entity\Producto $producto
   * @param string $mensaje
   * @return \Swift_Message
   */
  public function getMessageQuejaSugerencia($usuario, $asunto, $producto, $mensaje)
  {
    $msg = Swift_Message::newInstance()
      ->setFrom($this->from)
      ->setSubject('Nueva sugerencia: ' . $asunto)
      ->setTo('soporte@wikibooking.org', 'Soporte Wikibooking')
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:sugerenciaEmail.txt.twig', array(
        'usuario' => $usuario->getEmail(),
        'fecha' => date('Y-m-d h:i a'),
        'producto' => $producto->getNombre(),
        'empresa' => $producto->getEmpresa()->getNombreFiscal(),
        'mensaje' => $mensaje
        ), 'text/plain'));

    return $msg;
  }

  /**
   * @param string $emailCliente
   * @return \Swift_Message
   */
  public function getMessageQuejaSugerenciaParaCliente($emailCliente)
  {
    $msg = Swift_Message::newInstance()
      ->setSubject('Gracias por su sugerencia')
      ->setFrom($this->from)
      ->setTo($emailCliente)
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:sugerenciaEmailCliente.txt.twig', array()), 'text/plain');

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Cliente $cliente
   * @param mixed $para
   * @return \Swift_Message
   */
  public function getMessageContactoAyudaDesdeTpvParaPropietario(\PanelControl\ModeloBundle\Entity\Cliente $cliente, $para)
  {
    $msg = \Swift_Message::newInstance()
      ->setSubject('Solicitud de ayuda')
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:contactoClienteTpvParaPropietario.txt.twig', array('registro' => $cliente)), 'text/plain')
      ->setFrom($this->from)
      ->setTo($para)
    ;

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Cliente $cliente
   * @param \PanelControl\ModeloBundle\Entity\Producto $producto
   * @return \Swift_Message
   */
  public function getMessageMapaProductoParaCliente(Cliente $cliente, Producto $producto)
  {
    $msg = \Swift_Message::newInstance()
      ->setSubject('Localización de nuestro producto')
      ->setFrom($this->from)
      ->setTo($cliente->getEmail())
    ;

    if ($producto->getLatitud() && $producto->getLongitud())
    {
      $img = $this->recuperarImagenMapaGooglePorCoordenadas($producto->getLatitud(), $producto->getLongitud());

      if ($img !== false)
      {
        $msg->attach(\Swift_Attachment::newInstance($img, 'mapa.png', 'image/png'));
      }
    }

    return $msg;
  }

  /**
   * @param string $email
   * @return \Swift_Message
   */
  public function getMessageVerificarNuevaCuenta($email, $password, $url)
  {
    $msg = Swift_Message::newInstance('Verificación de cuenta - Wikibooking.org')
      ->setFrom('booking.online@wikibooking.org')
      ->setTo($email)
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:verificarNuevaCuenta.txt.twig', array('usuario' => $email,
        'password' => $password,
        'url' => $url), 'text/plain'));

    return $msg;
  }

  /**
   * @param PanelControl\ModeloBundle\Entity\TerminalVenta $terminalVenta
   * @param string $destinatario
   * @return Swift_Message
   */
  public function getMsgTerminalConfiguradaParaCliente(\PanelControl\ModeloBundle\Entity\TerminalVenta $terminalVenta, $destinatario)
  {
    $msg = Swift_Message::newInstance('Aviso: Terminal de venta configurada')
      ->setFrom($this->from)
      ->setTo($destinatario)
      ->setBody($this->templateEngine->render('ModeloBundle:Messages:avisoTerminalConfigurada.txt.twig', array('terminal' => $terminalVenta)))
    ;

    return $msg;
  }

  /**
   * @param $usuarioSession,$usuario,$password,$producto
   * @return \Swift_Message
   */
  public function getMessageCreacionUsuarioBasico($usuarioSession, $email, $password, $producto)
  {
    $msg = Swift_Message::newInstance()
      ->setSubject('Creación de usuario con permisos básicos')
      ->setFrom($this->from)
      ->setTo($usuarioSession->getEmail())
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:creacionUsuarioBasico.txt.twig', array('usuarioSession' => $usuarioSession, 'email' => $email, 'password' => $password, 'producto' => $producto)), 'text/plain');

    return $msg;
  }

  /**
   * @param $usuarioSession,$usuario,$password,$empresa
   * @return \Swift_Message
   */
  public function getMessageUsuarioResponsableEmpresa_Creador($usuarioSession, $email, $password, $empresa)
  {
    $msg = Swift_Message::newInstance()
      ->setSubject('Creación de usuario responsable de empresa')
      ->setFrom($this->from)
      ->setTo($usuarioSession->getEmail())
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:creacionUsuarioResponsableEmpresaParaCreador.txt.twig', array('usuarioSession' => $usuarioSession, 'email' => $email, 'password' => $password, 'empresa' => $empresa)), 'text/plain');

    return $msg;
  }

  /**
   * @param $usuario,$password,$empresa
   * @return \Swift_Message
   */
  public function getMessageUsuarioResponsableEmpresa_Responsable($email, $password, $empresa)
  {
    $msg = Swift_Message::newInstance()
      ->setSubject('Responsable de empresa')
      ->setFrom($this->from)
      ->setTo($email)
      ->addPart($this->templateEngine->render('ModeloBundle:Messages:creacionUsuarioResponsableEmpresaParaResponsable.txt.twig', array('email' => $email, 'password' => $password, 'empresa' => $empresa)), 'text/plain');

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param int $vista  La vista utilizada para el mensaje (1 = web, 2 = portatiles)
   * @return \Swift_Message
   */
  public function getMsgReservaTeatroCreadaParaCliente(Reserva $reserva, $vista = 1)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    $texto = $reserva->recuperarTextoQR();

    $qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      if ($email->getClasificacion() === 1)
        if (!in_array($email->getEmail(), $emails))
          $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    foreach ($reserva->getProducto()->getTelefonos() as $telef)
    {
      if ($telef->getClasificacion() == 3)
      {
        $telefonoRecepcion = "({$telef->getCodigo()}) {$telef->getNumero()}";
      }
    }

    //Buscamos la zona y la sala
    $zona = $sala = null;
    foreach ($reserva->getTarifaDetalleReservas() as $tdr)
    {
      foreach ($tdr->getTarifaDetalle()->getTarifaArticulos() as $ta)
      {
        $articulo = $ta->getArticulo();
        if (strtolower($articulo->getTipoArticulo()->getNombre()) == 'zona')
        {
          ## Suponemos que siempre se reservará todo en la misma zona/sala
          $zona = $articulo;
          $sala = $zona->getArticuloPadre();
          break 2;
        }
      }
    }

    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);

    $coordenadas = $this->getLatitudLongitudFromReserva($reserva);
    $mapImage = $coordenadas ? $this->recuperarImagenMapaGooglePorCoordenadas($coordenadas['latitud'], $coordenadas['longitud'], 220, 220) : null;
    $mapa = $mapImage ? $msg->embed(\Swift_Image::newInstance($mapImage, 'mapa.png', 'image/png')) : null;

    //El PDF
    /* Raibel, quito el PDF hasta que se resuelva el tema del png y ademas hasta que mejoremos el maquetado del pdf
      $pdf = $this->getPDFReservaCreada($reserva, $vista == 2);
      $msg->attach(\Swift_Attachment::newInstance($pdf, sprintf('%s.pdf', $reserva->getCodigo()), 'application/pdf'));
     */

    //El cuerpo en html
    $plantillaHtml = $vista == 1 ? 'ModeloBundle:Messages:avisoReservaTeatroCreadaParaCliente.html.twig' : 'ModeloBundle:Messages:avisoReservaTeatroPortatilCreadaParaCliente.html.twig';
    $urlCancelacion = $reserva->getTerminal() && $reserva->getTerminal()->getUrlEnlaces() ? $reserva->getTerminal()->getUrlEnlaces() : ($reserva->getProducto()->getWeb() ? $reserva->getProducto()->getWeb() : 'http://www.deteatros.com/');
    $htmlPart = $this->templateEngine->render($plantillaHtml, array(
      'urlCancelacion' => sprintf('%s%scancelar/reserva/canal/%s/codigo/%s', $urlCancelacion, substr($urlCancelacion, -1) != '/' ? '/' : '', $reserva->getCanalVenta() ? $reserva->getCanalVenta()->getWsCodigo() : $reserva->getTerminal()->getCodigo(), $reserva->getCodigo()),
      'telefonoRecepcion' => isset($telefonoRecepcion) ? $telefonoRecepcion : '',
      'emailReserva' => $emailReserva,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'zona' => $zona,
      'sala' => $sala,
      'images' => array(
        'qr' => $qr,
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png')),
        'mapa' => $mapa
      ),
      'pdte_aprobacion' => $reserva->getEstado() == Reserva::ESTADO_PDTE_APROBACION,
      'identidad' => $identidad,
      'tarifa' => $reserva->getNombreTarifa(),
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta' => $tipo_tarjeta  
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @return \Swift_Message
   */
  public function getMsgReservaTeatroAprobadaParaCliente(Reserva $reserva)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva aprobada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    //El PDF
    /* Raibel, quito el PDF hasta que se resuelva el tema del png y ademas hasta que mejoremos el maquetado del pdf
      $pdf = $this->getPDFReservaCreada($reserva, $vista == 2);
      $msg->attach(\Swift_Attachment::newInstance($pdf, sprintf('%s.pdf', $reserva->getCodigo()), 'application/pdf'));
     */

    $msg->setBody($this->templateEngine->render('ModeloBundle:Messages:avisoReservaTeatroAprobadaParaCliente.txt.twig', array(
        'reserva' => $reserva
      )));

    return $msg;
  }

  public function getMsgReservaTeatroParaProducto(Reserva $reserva)
  {
    $detalle = $reserva->getPlanning()->getObraDetalle();
    $emailProducto = $detalle ? ( $detalle->getMantenerEmailProducto() ? $detalle->getEmailReserva() : null) : null;

    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($from ? $from : $this->from );

    $producto = $reserva->getProducto();

    if ($emailProducto)
    {
       $emails = $this->getEmailsParaEnvio($emailProducto);
       foreach($emails as $e)
        $msg->addTo($e);
    }
    else
    {
      foreach ($producto->getEmails() as $email)
      {
        if (strlen($email->getEmail()) > 0 && $email->getClasificacion() == 1){        
            $msg->addTo($email->getEmail());
        }
      }
    }

    $texto = $reserva->recuperarTextoQR();
    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);
    $qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));

    //Buscamos la zona y la sala
    $zona = $sala = null;
    foreach ($reserva->getTarifaDetalleReservas() as $tdr)
    {
      foreach ($tdr->getTarifaDetalle()->getTarifaArticulos() as $ta)
      {
        $articulo = $ta->getArticulo();
        if (strtolower($articulo->getTipoArticulo()->getNombre()) == 'zona')
        {
          ## Suponemos que siempre se reservará todo en la misma zona/sala
          $zona = $articulo;
          $sala = $zona->getArticuloPadre();
          break 2;
        }
      }
    }

    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('ModeloBundle:Messages:avisoReservaTeatroCreadaParaProducto.html.twig', array(
      'montoPrepago' => 0,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'zona' => $zona,
      'sala' => $sala,
      'images' => array(
        'qr' => $qr,
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png'))
      ),
      'tarifa' => $reserva->getNombreTarifa(),
      'sitehostname' => $this->sitehostname,
      'pdte_aprobacion' => $reserva->getEstado() == Reserva::ESTADO_PDTE_APROBACION,
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta'=>$tipo_tarjeta ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }

  /**
   * getMsgReservaTeatroCanceladaParaCliente
   *
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @return \Swift_Message
   */
  public function getMsgReservaTeatroCanceladaParaCliente(Reserva $reserva)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva cancelada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    $texto = $reserva->recuperarTextoQR();

    $qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      if ($email->getClasificacion() === 1)
        if (!in_array($email->getEmail(), $emails))
          $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    foreach ($reserva->getProducto()->getTelefonos() as $telef)
    {
      if ($telef->getClasificacion() == 3)
      {
        $telefonoRecepcion = "({$telef->getCodigo()}){$telef->getNumero()}";
      }
    }

    //Buscamos la zona y la sala
    $zona = $sala = null;
    foreach ($reserva->getTarifaDetalleReservas() as $tdr)
    {
      foreach ($tdr->getTarifaDetalle()->getTarifaArticulos() as $ta)
      {
        $articulo = $ta->getArticulo();
        if (strtolower($articulo->getTipoArticulo()->getNombre()) == 'zona')
        {
          ## Suponemos que siempre se reservará todo en la misma zona/sala
          $zona = $articulo;
          $sala = $zona->getArticuloPadre();
          break 2;
        }
      }
    }

    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);

    $coordenadas = $this->getLatitudLongitudFromReserva($reserva);
    $mapImage = $coordenadas ? $this->recuperarImagenMapaGooglePorCoordenadas($coordenadas['latitud'], $coordenadas['longitud'], 220, 220) : null;
    $mapa = $mapImage ? $msg->embed(\Swift_Image::newInstance($mapImage, 'mapa.png', 'image/png')) : null;

    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('ModeloBundle:Messages:avisoReservaTeatroCanceladaParaCliente.html.twig', array(
      'telefonoRecepcion' => isset($telefonoRecepcion) ? $telefonoRecepcion : '',
      'emailReserva' => $emailReserva,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'zona' => $zona,
      'sala' => $sala,
      'images' => array(
        'qr' => $qr,
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png')),
        'mapa' => $mapa
      ),
      'identidad' => $identidad,
      'tarifa' => $reserva->getNombreTarifa(),
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta'=>$tipo_tarjeta  
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }

  /**
   * getMsgReservaTeatroCanceladaParaCliente
   *
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @return \Swift_Message
   */
  public function getMsgReservaTeatroCanceladaRequestParaCliente(Reserva $reserva, $motivoCancelacion = null)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva cancelada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      foreach ($reserva->getProducto()->getEmails() as $email)
      {
        if ($email->getClasificacion() === 1)
          if (!in_array($email->getEmail(), $emails))
            $emails[] = $email->getEmail();
      }
    }
    $emailReserva = implode(', ', $emails);

    //El cuerpo en html
    $msg->setBody($this->templateEngine->render('ModeloBundle:Messages:avisoReservaTeatroCanceladaParaCliente.txt.twig', array(
        'email_reserva' => $emailReserva,
        'reserva' => $reserva,
        'motivo' => $motivoCancelacion
      )));

    return $msg;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param int $vista  La vista utilizada para el mensaje (1 = web, 2 = portatiles)
   * @return \Swift_Message
   */
  public function getMsgReservaExcursionCreadaParaCliente(Reserva $reserva, $vista = 1)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($from ? $from : $this->from)
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    /*$texto = $reserva->recuperarTextoQR();

    $qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));
    */
    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      if ($email->getClasificacion() === 1)
        if (!in_array($email->getEmail(), $emails))
          $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    foreach ($reserva->getProducto()->getTelefonos() as $telef)
    {
      if ($telef->getClasificacion() == 3)
      {
        $telefonoRecepcion = "({$telef->getCodigo()}) {$telef->getNumero()}";
      }
    }

    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);

    $coordenadas = $this->getLatitudLongitudFromReserva($reserva);
    $mapImage = $coordenadas ? $this->recuperarImagenMapaGooglePorCoordenadas($coordenadas['latitud'], $coordenadas['longitud'], 220, 220) : null;
    $mapa = $mapImage ? $msg->embed(\Swift_Image::newInstance($mapImage, 'mapa.png', 'image/png')) : null;

    //El PDF
    /* Raibel, quito el PDF hasta que se resuelva el tema del png y ademas hasta que mejoremos el maquetado del pdf
      $pdf = $this->getPDFReservaCreada($reserva, $vista == 2);
      $msg->attach(\Swift_Attachment::newInstance($pdf, sprintf('%s.pdf', $reserva->getCodigo()), 'application/pdf'));
     */

    //El cuerpo en html
    $plantillaHtml = $vista == 1 ? 'ModeloBundle:Messages:avisoReservaExcursionCreadaParaCliente.html.twig' : 'ModeloBundle:Messages:avisoReservaExcursionPortatilCreadaParaCliente.html.twig';
    $urlCancelacion = $reserva->getTerminal() && $reserva->getTerminal()->getUrlEnlaces() ? $reserva->getTerminal()->getUrlEnlaces() : ($reserva->getProducto()->getWeb() ? $reserva->getProducto()->getWeb() : 'http://www.canaryadventure.com/');
    $urlCondiciones = $reserva->getTerminal() && $reserva->getTerminal()->getUrlEnlaces() ? $reserva->getTerminal()->getUrlEnlaces() : ($reserva->getProducto()->getWeb() ? $reserva->getProducto()->getWeb() : 'http://www.canaryadventure.com/');
    $htmlPart = $this->templateEngine->render($plantillaHtml, array(
      'urlCancelacion' => sprintf('%s%scancelar/reserva/canal/%s/codigo/%s', $urlCancelacion, substr($urlCancelacion, -1) != '/' ? '/' : '', $reserva->getCanalVenta() ? $reserva->getCanalVenta()->getWsCodigo() : $reserva->getTerminal()->getCodigo(), $reserva->getCodigo()),
      'urlCondiciones' => sprintf('%s%scondiciones/generales/reserva', $urlCondiciones, substr($urlCancelacion, -1) != '/' ? '/' : ''),
      'telefonoRecepcion' => isset($telefonoRecepcion) ? $telefonoRecepcion : '',
      'emailReserva' => $emailReserva,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'images' => array(
        //'qr' => $qr,
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png')),
        'mapa' => $mapa
      ),
      'pdte_aprobacion' => $reserva->getEstado() == Reserva::ESTADO_PDTE_APROBACION,
      'identidad' => $identidad,
      'tarifa' => $reserva->getNombreTarifa(),
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta' => $tipo_tarjeta
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }

  public function getMsgReservaExcursionAprobadaParaCliente(Reserva $reserva)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva aprobada')
      ->setFrom($from ? $from : $this->from)
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    //El PDF
    /* Raibel, quito el PDF hasta que se resuelva el tema del png y ademas hasta que mejoremos el maquetado del pdf
      $pdf = $this->getPDFReservaCreada($reserva, $vista == 2);
      $msg->attach(\Swift_Attachment::newInstance($pdf, sprintf('%s.pdf', $reserva->getCodigo()), 'application/pdf'));
     */

    $msg->setBody($this->templateEngine->render('ModeloBundle:Messages:avisoReservaExcursionAprobadaParaCliente.txt.twig', array(
        'reserva' => $reserva
      )));

    return $msg;
  }

  public function getMsgReservaExcursionParaProducto(Reserva $reserva)
  {
    $detalle = $reserva->getPlanning()->getObraDetalle();
    $emailProducto = $detalle ? ( $detalle->getMantenerEmailProducto() ? $detalle->getEmailReserva() : null) : null;

    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($from ? $from : $this->from );

    $producto = $reserva->getProducto();

    if ($emailProducto)
    {
       $emails = $this->getEmailsParaEnvio($emailProducto);
       foreach($emails as $e)
        $msg->addTo($e);
    }
    else
    {
      foreach ($producto->getEmails() as $email)
      {
        if (strlen($email->getEmail()) > 0 && $email->getClasificacion() == 1){      
            $msg->addTo($email->getEmail());
        }
      }
    }

    //$texto = $reserva->recuperarTextoQR();
    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);
    /*$qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));
    */
    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('ModeloBundle:Messages:avisoReservaExcursionCreadaParaProducto.html.twig', array(
      'montoPrepago' => 0,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'images' => array(
        //'qr' => $qr,
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png'))
      ),
      'pdte_aprobacion' => $reserva->getEstado() == Reserva::ESTADO_PDTE_APROBACION,
      'tarifa' => $reserva->getNombreTarifa(),
      'sitehostname' => $this->sitehostname,
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta' => $tipo_tarjeta 
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }

  /**
   * getMsgReservaExcursionCanceladaParaCliente
   *
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param int $vista  La vista utilizada para el mensaje (1 = web, 2 = portatiles)
   * @return \Swift_Message
   */
  public function getMsgReservaExcursionCanceladaParaCliente(Reserva $reserva)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva cancelada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    /*$texto = $reserva->recuperarTextoQR();

    $qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));*/

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      if ($email->getClasificacion() === 1)
        if (!in_array($email->getEmail(), $emails))
          $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    foreach ($reserva->getProducto()->getTelefonos() as $telef)
    {
      if ($telef->getClasificacion() == 3)
      {
        $telefonoRecepcion = "({$telef->getCodigo()}){$telef->getNumero()}";
      }
    }

    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);

    $coordenadas = $this->getLatitudLongitudFromReserva($reserva);
    $mapImage = $coordenadas ? $this->recuperarImagenMapaGooglePorCoordenadas($coordenadas['latitud'], $coordenadas['longitud'], 220, 220) : null;
    $mapa = $mapImage ? $msg->embed(\Swift_Image::newInstance($mapImage, 'mapa.png', 'image/png')) : null;

    $urlCondiciones = $reserva->getTerminal() && $reserva->getTerminal()->getUrlEnlaces() ? $reserva->getTerminal()->getUrlEnlaces() : ($reserva->getProducto()->getWeb() ? $reserva->getProducto()->getWeb() : 'http://www.canaryadventure.com/');
    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('ModeloBundle:Messages:avisoReservaExcursionCanceladaParaCliente.html.twig', array(
      'telefonoRecepcion' => isset($telefonoRecepcion) ? $telefonoRecepcion : '',
      'emailReserva' => $emailReserva,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'images' => array(
        //'qr' => $qr,
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png')),
        'mapa' => $mapa
      ),
      'identidad' => $identidad,
      'tarifa' => $reserva->getNombreTarifa(),
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta' => $tipo_tarjeta,
      'urlCondiciones' => sprintf('%s%scondiciones/generales/reserva', $urlCondiciones, substr($urlCondiciones, -1) != '/' ? '/' : ''),
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }

  /**
   * getMsgReservaExcursionCanceladaRequestParaCliente
   *
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param int $vista  La vista utilizada para el mensaje (1 = web, 2 = portatiles)
   * @return \Swift_Message
   */
  public function getMsgReservaExcursionCanceladaRequestParaCliente(Reserva $reserva, $motivoCancelacion = null)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva cancelada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      if ($email->getClasificacion() === 1)
        if (!in_array($email->getEmail(), $emails))
          $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    $msg->setBody($this->templateEngine->render('ModeloBundle:Messages:avisoReservaExcursionCanceladaParaCliente.txt.twig', array(
        'email_reserva' => $emailReserva,
        'reserva' => $reserva,
        'motivo' => $motivoCancelacion
      )));

    return $msg;
  }
  
   /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param int $vista  La vista utilizada para el mensaje (1 = web, 2 = portatiles)
   * @return \Swift_Message
   */
  public function getMsgReservaAlojamientoCreadaParaCliente(Reserva $reserva, $vista = 1)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($from ? $from : $this->from)
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    //$texto = $reserva->recuperarTextoQR();
    
    //Comentareando uso de QR
    /*$qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));*/

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      if ($email->getClasificacion() === 1)
        if (!in_array($email->getEmail(), $emails))
          $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    foreach ($reserva->getProducto()->getTelefonos() as $telef)
    {
      if ($telef->getClasificacion() == 3)
      {
        $telefonoRecepcion = "({$telef->getCodigo()}) {$telef->getNumero()}";
      }
    }

    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);

    $coordenadas = $this->getLatitudLongitudFromReserva($reserva);
    $mapImage = $coordenadas ? $this->recuperarImagenMapaGooglePorCoordenadas($coordenadas['latitud'], $coordenadas['longitud'], 220, 220) : null;
    $mapa = $mapImage ? $msg->embed(\Swift_Image::newInstance($mapImage, 'mapa.png', 'image/png')) : null;

    //El PDF
    /* Raibel, quito el PDF hasta que se resuelva el tema del png y ademas hasta que mejoremos el maquetado del pdf
      $pdf = $this->getPDFReservaCreada($reserva, $vista == 2);
      $msg->attach(\Swift_Attachment::newInstance($pdf, sprintf('%s.pdf', $reserva->getCodigo()), 'application/pdf'));
     */

    //El cuerpo en html    
    $plantillaHtml = $vista == 1 ? 'ModeloBundle:Messages:avisoReservaAlojamientoCreadaParaCliente.html.twig' : 'ModeloBundle:Messages:avisoReservaAlojamientoPortatilCreadaParaCliente.html.twig';
    $urlCancelacion = $reserva->getTerminal() && $reserva->getTerminal()->getUrlEnlaces() ? $reserva->getTerminal()->getUrlEnlaces() : ($reserva->getProducto()->getWeb() ? $reserva->getProducto()->getWeb() : 'http://www.canaryadventure.com/');
    $urlCondiciones = $reserva->getTerminal() && $reserva->getTerminal()->getUrlEnlaces() ? $reserva->getTerminal()->getUrlEnlaces() : ($reserva->getProducto()->getWeb() ? $reserva->getProducto()->getWeb() : 'http://www.canaryadventure.com/');
    $htmlPart = $this->templateEngine->render($plantillaHtml, array(
      'urlCancelacion' => sprintf('%s%scancelar/reserva/canal/%s/codigo/%s', $urlCancelacion, substr($urlCancelacion, -1) != '/' ? '/' : '', $reserva->getCanalVenta() ? $reserva->getCanalVenta()->getWsCodigo() : $reserva->getTerminal()->getCodigo(), $reserva->getCodigo()),
      'urlCondiciones' => sprintf('%s%scondiciones/generales/reserva', $urlCondiciones, substr($urlCancelacion, -1) != '/' ? '/' : ''),
      'telefonoRecepcion' => isset($telefonoRecepcion) ? $telefonoRecepcion : '',
      'emailReserva' => $emailReserva,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'images' => array(  
        'mapa' => $mapa,
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png')),
        ),
      'pdte_aprobacion' => $reserva->getEstado() == Reserva::ESTADO_PDTE_APROBACION,
      'identidad' => $identidad,
      'tarifa' => $reserva->getNombreTarifa(),
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta' => $tipo_tarjeta,
      'moneda' => $reserva->getProducto()->getEmpresa()->getMoneda()->getSimbolo()  
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }
  
  public function getMsgReservaAlojamientoParaProducto(Reserva $reserva)
  {
    $detalle = $reserva->getPlanning()->getObraDetalle();
    $emailProducto = $detalle ? ( $detalle->getMantenerEmailProducto() ? $detalle->getEmailReserva() : null) : null;

    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva creada')
      ->setFrom($from ? $from : $this->from );

    $producto = $reserva->getProducto();

    if ($emailProducto)
    {
       $emails = $this->getEmailsParaEnvio($emailProducto);
       foreach($emails as $e)
        $msg->addTo($e);
    }
    else
    {
      foreach ($producto->getEmails() as $email)
      {
        if (strlen($email->getEmail()) > 0 && $email->getClasificacion() == 1){         
            $msg->addTo($email->getEmail());
        }
      }
    }

    //$texto = $reserva->recuperarTextoQR();
    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);
    //$qrImage = $this->qrImageGenerator->getImage($texto);
    //$qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));

    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('ModeloBundle:Messages:avisoReservaAlojamientoCreadaParaProducto.html.twig', array(
      'montoPrepago' => 0,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'images' => array(     
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png'))
      ),
      'pdte_aprobacion' => $reserva->getEstado() == Reserva::ESTADO_PDTE_APROBACION,
      'tarifa' => $reserva->getNombreTarifa(),
      'sitehostname' => $this->sitehostname,
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta' => $tipo_tarjeta,
      'moneda' => $reserva->getProducto()->getEmpresa()->getMoneda()->getSimbolo()    
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }  
  
  public function getMsgReservaAlojamientoAprobadaParaCliente(Reserva $reserva)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva aprobada')
      ->setFrom($from ? $from : $this->from)
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    //El PDF
    /* Raibel, quito el PDF hasta que se resuelva el tema del png y ademas hasta que mejoremos el maquetado del pdf
      $pdf = $this->getPDFReservaCreada($reserva, $vista == 2);
      $msg->attach(\Swift_Attachment::newInstance($pdf, sprintf('%s.pdf', $reserva->getCodigo()), 'application/pdf'));
     */

    $msg->setBody($this->templateEngine->render('ModeloBundle:Messages:avisoReservaAlojamientoAprobadaParaCliente.txt.twig', array(
        'reserva' => $reserva
      )));

    return $msg;
  }
  
   /**
   * getMsgReservaAlojamientoCanceladaParaCliente
   *
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param int $vista  La vista utilizada para el mensaje (1 = web, 2 = portatiles)
   * @return \Swift_Message
   */
  public function getMsgReservaAlojamientoCanceladaParaCliente(Reserva $reserva)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva cancelada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    //$texto = $reserva->recuperarTextoQR();

   /* $qrImage = $this->qrImageGenerator->getImage($texto);
    $qr = $msg->embed(\Swift_Image::newInstance($qrImage, 'qrcode.png', 'image/png'));*/

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      if ($email->getClasificacion() === 1)
        if (!in_array($email->getEmail(), $emails))
          $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    foreach ($reserva->getProducto()->getTelefonos() as $telef)
    {
      if ($telef->getClasificacion() == 3)
      {
        $telefonoRecepcion = "({$telef->getCodigo()}){$telef->getNumero()}";
      }
    }

    $dato_formato_visual = $reserva->getDatosFormatoVisual($this->creditCardCrypter);
    $tipo_tarjeta = $reserva->getTipoTarjetaCredito($this->creditCardCrypter);

    $coordenadas = $this->getLatitudLongitudFromReserva($reserva);
    $mapImage = $coordenadas ? $this->recuperarImagenMapaGooglePorCoordenadas($coordenadas['latitud'], $coordenadas['longitud'], 220, 220) : null;
    $mapa = $mapImage ? $msg->embed(\Swift_Image::newInstance($mapImage, 'mapa.png', 'image/png')) : null;

    $urlCondiciones = $reserva->getTerminal() && $reserva->getTerminal()->getUrlEnlaces() ? $reserva->getTerminal()->getUrlEnlaces() : ($reserva->getProducto()->getWeb() ? $reserva->getProducto()->getWeb() : 'http://www.canaryadventure.com/');
    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('ModeloBundle:Messages:avisoReservaAlojamientoCanceladaParaCliente.html.twig', array(
      'telefonoRecepcion' => isset($telefonoRecepcion) ? $telefonoRecepcion : '',
      'emailReserva' => $emailReserva,
      'reserva' => $reserva,
      'detalle' => $reserva->getPlanning()->getObraDetalle(),
      'images' => array(        
        'check' => $msg->embed(\Swift_Image::fromPath($this->rootDir . '/../web/images/email-teatro/icono-ticket.png')),
        'mapa' => $mapa
      ),
      'identidad' => $identidad,
      'tarifa' => $reserva->getNombreTarifa(),
      'dato_formato_visual' => $dato_formato_visual,
      'tipo_tarjeta' => $tipo_tarjeta,
      'urlCondiciones' => sprintf('%s%scondiciones/generales/reserva', $urlCondiciones, substr($urlCondiciones, -1) != '/' ? '/' : ''),
      'moneda' => $reserva->getProducto()->getEmpresa()->getMoneda()->getSimbolo()  
      ));

    $msg->addPart($htmlPart, 'text/html');

    return $msg;
  }  
  
   /**
   * getMsgReservaAlojamientoCanceladaRequestParaCliente
   *
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param int $vista  La vista utilizada para el mensaje (1 = web, 2 = portatiles)
   * @return \Swift_Message
   */
  public function getMsgReservaAlojamientoCanceladaRequestParaCliente(Reserva $reserva, $motivoCancelacion = null)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva cancelada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      if ($email->getClasificacion() === 1)
        if (!in_array($email->getEmail(), $emails))
          $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    $msg->setBody($this->templateEngine->render('ModeloBundle:Messages:avisoReservaAlojamientoCanceladaParaCliente.txt.twig', array(
        'email_reserva' => $emailReserva,
        'reserva' => $reserva,
        'motivo' => $motivoCancelacion
      )));

    return $msg;
  }  

  /**
   * getMsgReservaTeatroCanceladaParaCliente
   *
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @param int $vista  La vista utilizada para el mensaje (1 = web, 2 = portatiles)
   * @return \Swift_Message
   */
  public function getMsgReservaCanceladaEmpresaParaCliente(Reserva $reserva, $mensaje)
  {
    $identidad = $reserva->getIdentidad();
    $from = $this->getFrom($reserva->getProducto());
    $msg = Swift_Message::newInstance('Aviso: Reserva cancelada')
      ->setFrom($from ? $from : $this->from )
      ->setTo($reserva->getCliente()->getEmail(), $identidad->getNombreCompleto());

    $msg->addPart($mensaje, 'text/html');

    return $msg;
  }

  /**
   * @param float $latitud
   * @param float $longitud
   * @return string|false La imagen o false si hay error
   */
  private function recuperarImagenMapaGooglePorCoordenadas($latitud, $longitud, $width = 400, $height = 400)
  {
    $this->gMapCreator->setImageWidth($width);
    $this->gMapCreator->setImageHeight($height);

    return $this->gMapCreator->getImage($latitud, $longitud);
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Producto $producto
   * @return string
   * @todo
   */
  private function getPathToLogo(Producto $producto)
  {
    return $this->rootDir . '/../web/images/logos/logoWiki.png';
  }

  /**
   * Produce el PDF para adjuntar en el mail de reserva creada
   */
  private function getPDFReservaCreada(Reserva $reserva)
  {
    $texto = $reserva->recuperarTextoQR();
    $qrImage = $this->qrImageGenerator->getImage($texto);

    $mapImage = $reserva->getProducto()->getLatitud() && $reserva->getProducto()->getLongitud() ? $this->recuperarImagenMapaGooglePorCoordenadas($reserva->getProducto()->getLatitud(), $reserva->getProducto()->getLongitud(), 220, 220) : null;

    $emails = array();
    foreach ($reserva->getProducto()->getEmails() as $email)
    {
      $emails[] = $email->getEmail();
    }
    $emailReserva = implode(', ', $emails);

    foreach ($reserva->getProducto()->getTelefonos() as $telef)
    {
      if ($telef->getClasificacion() == 3)
      {
        $telefonoRecepcion = "({$telef->getCodigo()}) {$telef->getNumero()}";
      }
    }

    //Buscamos la zona y la sala
    $zona = $sala = null;
    foreach ($reserva->getTarifaDetalleReservas() as $tdr)
    {
      foreach ($tdr->getTarifaDetalle()->getTarifaArticulos() as $ta)
      {
        $articulo = $ta->getArticulo();
        if (strtolower($articulo->getTipoArticulo()->getNombre()) == 'zona')
        {
          ## Suponemos que siempre se reservará todo en la misma zona/sala
          $zona = $articulo;
          $sala = $zona->getArticuloPadre();
          break 2;
        }
      }
    }

    //El cuerpo en html
    $htmlPart = $this->templateEngine->render('ModeloBundle:Messages:pdfReservaTeatroCreadaParaCliente.html.twig', array(
      'telefonoRecepcion' => isset($telefonoRecepcion) ? $telefonoRecepcion : '',
      'emailReserva' => $emailReserva,
      'reserva' => $reserva,
      'detalle' => $reserva->getCanalVenta()->getPlanning()->getObraDetalle(),
      'zona' => $zona,
      'sala' => $sala,
      //'tarjetaCredito' => $reserva->getTarjetaCreditoNumero() ? substr($reserva->getTarjetaCreditoNumero(), -4) : null,
      'tarjetaCredito' => null,
      'images' => array(
        'qr' => base64_encode($qrImage),
        'mapa' => $mapImage ? base64_encode($mapImage) : null
      ),
      'identidad' => $reserva->getIdentidad()
      ));

    $pdf = new HojaCarta();
    $pdf->AddPage();
    $pdf->writeHTML($htmlPart);
    $cPdf = $pdf->render();

    return $cPdf;
  }

  /**
   * @param string $asunto
   * @param string $to
   * @param array $params
   * @return Swift_Message
   */
  public function getMsgDesactivacionObras($asunto, $to, $params)
  {
    $msg = \Swift_Message::newInstance()
      ->setFrom($this->from)
      ->setSubject($asunto)
      ->setTo($to)
      ->setBody($this->templating->render('RegistroBundle:Messages:desactivacionObras.txt.twig', array('params' => $params), 'text/plain'));
    return $msg;
  }

  private function getFrom(Producto $producto)
  {
    foreach ($producto->getEmails() as $email)
    {
      if ($email->getClasificacion() == 1)
      {
        $emailProducto = explode(';', $email->getEmail());
        return array(trim($emailProducto[0]) => $producto->getNombre());
      }
    }

    return null;
  }
  
  private function getEmailsParaEnvio($email)
  {    
    $emails = explode(';', $email);
    $validos = array();
    foreach($emails as $e){
        $validos[] = trim($e);
    }
    
    return $validos;
  }

  /**
   * @param \PanelControl\ModeloBundle\Entity\Reserva $reserva
   * @return array|null
   */
  private function getLatitudLongitudFromReserva(Reserva $reserva)
  {
    $r = array();

    if ($reserva->getPlanning()->getObraDetalle() && ($reserva->getPlanning()->getObraDetalle()->getLatitud() && $reserva->getPlanning()->getObraDetalle()->getLatitud() != 0 && $reserva->getPlanning()->getObraDetalle()->getLongitud() && $reserva->getPlanning()->getObraDetalle()->getLongitud() != 0))
    {
      $r['latitud'] = $reserva->getPlanning()->getObraDetalle()->getLatitud();
      $r['longitud'] = $reserva->getPlanning()->getObraDetalle()->getLongitud();
    }
    elseif ($reserva->getProducto()->getLatitud() && $reserva->getProducto()->getLongitud())
    {
      $r['latitud'] = $reserva->getProducto()->getLatitud();
      $r['longitud'] = $reserva->getProducto()->getLongitud();
    }
    else
    {
      $r = null;
    }

    return $r;
  }

}
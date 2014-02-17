<?php
namespace Encuesta\DashboardBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\Container;

class LocaleExtension extends \Twig_Extension
{
    private $container;

    public function __construct(Container $c)
    {
        $this->container = $c;
    }

    public function getName()
    {
        return 'locale';
    }

    public function getFunctions()
    {
        return array(
            'locale_date_format' => new \Twig_Function_Method($this, 'localeDateFormat')
        );
    }

    public function localeDateFormat($locale, $env = false)
    {
        $formats = $this->container->getParameter('date_format', array());

        $format = isset($formats[$locale]) ? $formats[$locale] : array();
        if($env !== false) {
            $format = isset($format[$env]) ? $format[$env] : null;;
        }

        return $format;
    }
}
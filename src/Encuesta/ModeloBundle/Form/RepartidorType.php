<?php
namespace Administracion\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RepartidorType extends AbstractType
{
    protected $sucursales;
    protected $sucursalSeleccionada;         

    public function __construct (array $sucursales, $sucursalSeleccionada = null)
    {
        $this->sucursales = $sucursales;
        $this->sucursalSeleccionada = $sucursalSeleccionada;
    }    
    
      public function buildForm(FormBuilderInterface $builder, array $options)
    {  
          $builder
               ->add('sucursal', 'choice', array(
                    'label' => 'Sucursal',
                    'choices' => $this->sucursales,
                    'empty_value' => false,
                    'attr' => array(
                        'class' => 'validate[required] txt_gris12'),
                    'mapped' => false,
                    'required' => true,
                    'constraints' => array(
                        new NotBlank()                   
                    ),
                    'data'=> $this->sucursalSeleccionada
                ))     
                  
                ->add('nombre', 'text', array(
                    'label' => 'Nombre',
                    'attr' => array(
                        'class' => 'validate[required] txt_gris12',
                        'size'=>40,
                        'placeholder' => 'Nombre'
                    ),
                    'required' => true,
                    'constraints' => array(
                        new NotBlank()                   
                    ),
                ))
                  
                ->add('apellido', 'text', array(
                    'label' => 'Apellido',
                    'attr' => array(
                        'class' => 'validate[required] txt_gris12',
                        'placeholder' => 'Apellido',
                        'size'=>40
                    ),
                    'required' => true,
                    'constraints' => array(
                        new NotBlank()                   
                    ),
                ))
                  
                ->add('telefono', 'text', array(
                    'label' => 'Tel./Cel',
                    'attr' => array(
                        'class' => 'txt_gris12',
                        'placeholder' => 'Teléfono o Celular',
                        'size'=>20
                    )                    
                ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Administracion\ModeloBundle\Entity\Repartidor'            
        ));       
    }

    public function getName()
    {
        return 'administracion_modelobundle_repartidortype';
    }
}

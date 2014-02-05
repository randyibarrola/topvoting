<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SucursalType extends AbstractType
{
    
     public function buildForm(FormBuilderInterface $builder, array $options)
    {  
          $builder
            ->add('nombre', 'text', array(
                'label' => 'Nombre',
                'attr' => array(
                    'class' => 'validate[required] txt_gris12',
                    'placeholder' => 'Nombre',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
              ))
                               
            ->add('domicilio', 'text', array(
                'label' => 'Domicilio',
                'attr' => array(
                    'class' => 'validate[required] txt_gris12',
                    'placeholder' => 'Domicilio',
                    'size'=>80
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
              ))
                  
            ->add('telefono', 'text', array(
                'label' => 'Tel.',
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
            'data_class' => 'Encuesta\ModeloBundle\Entity\Sucursal'
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_sucursaltype';
    }
}
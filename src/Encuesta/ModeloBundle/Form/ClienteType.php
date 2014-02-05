<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Date;

class ClienteType extends AbstractType
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
            
                 
            ->add('email', 'text', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'txt_gris12 validate[email]',
                    'placeholder' => 'Email',
                    'size'=>40
                ),
                'constraints' => array(
                    new Email()                   
                   ),
              ))
                 
            ->add('cumple', 'date', array(
                'label' => 'Cumpleaños',  
                'years' => range(1930, 2000),
                'attr' => array(
                    'class' => 'txt_gris12',
                    'placeholder' => 'Cumpleaños',
                    'style'=>'width:250px'
                ),
                'constraints' => array(
                    new Date()                   
                   ),
              ))
                 
            ->add('comentarios', 'textarea', array(
                'label' => 'Comentarios',
                'attr' => array(
                    'class' => 'txt_gris12',
                    'placeholder' => 'Comentarios',
                     'cols' => 100,
                     'rows' => 3
                ),
                'constraints' => array(
                    new NotBlank()                   
                   ),
              ));
    }
    
     public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Cliente'
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_clientetype';
    }
    
}
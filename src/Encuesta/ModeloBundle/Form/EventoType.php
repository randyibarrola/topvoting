<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class EventoType extends AbstractType
{     
 
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
         $builder
            ->add('creador', 'hidden', array(
                'label' => 'Creador',
                'attr' => array(
                    'class' => '',
                    'size'=>40
                ),
                'required' => false, 
                
                
              ))                 
            ->add('titulo', 'text', array(
                'label' => 'Título',
                'attr' => array(
                    'class' => '',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
              ))
            ->add('imagen', 'file', array(
                'label' => 'Imagen',
                'attr' => array(
                    'class' => '',
                    'size'=>40
                ),
                'required' => false
                
              ))                 
            ->add('fecha_fin', 'datetime', array(
                'label' => 'Fecha Fin',
                'attr' => array(
                    'class' => '',
                    'size'=>40
                ),
                'required' => false,
                
              ))                 
            ->add('numero_votaciones', 'integer', array(
                'label' => 'Número Votaciones',
                'attr' => array(
                    'class' => '',
                    'size'=>40
                ),
                'required' => false                
              ))                 
            ->add('descripcion', 'textarea', array(
                'label' => 'Descripción',
                'attr' => array(
                    'class' => 'validate[required]',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
              )) ;                
	}
	
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Evento'            
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_eventotype';
    }
}
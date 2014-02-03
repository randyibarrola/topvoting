<?php
namespace Administracion\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TelefonoType extends AbstractType
{  
    
     public function buildForm(FormBuilderInterface $builder, array $options)
    { 
		$builder
			->add('codigo', 'text', array(
                'attr' => array(
                    'class' => 'txt_gris12',
					'placeholder' => 'Cod.',
                    'size'=>5
                ),
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))
			
			->add('tipo', 'choice', array(
                'label' => 'Tipo de teléfono',
                'choices' => array('1'=>'Teléfono', '2'=>'Celular'),
                'empty_value' => false,
                'attr' => array('class' => 'validate[required] txt_gris12'),
                'mapped' => false,
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                )                
            ))

            ->add('numero', 'text', array(
                'label' => 'Tel./Cel',
                'attr' => array(
                    'class' => 'validate[required] txt_gris12',
                    'size'=>20
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
              ));		  
	
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Administracion\ModeloBundle\Entity\Telefono'            
        ));       
    }

    public function getName()
    {
        return 'administracion_modelobundle_telefonotype';
    }
	
}
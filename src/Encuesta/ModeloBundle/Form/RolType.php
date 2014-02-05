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

class RolType extends AbstractType
{
    
     public function buildForm(FormBuilderInterface $builder, array $options)
    {   
         $builder
            ->add('nombre', 'text', array(
                'label' => 'Rol',
                'attr' => array(
                    'class' => 'validate[required] txt_gris12',
                    'size'=>40
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
            'data_class' => 'Encuesta\ModeloBundle\Entity\Rol'
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_roltype';
    }
}
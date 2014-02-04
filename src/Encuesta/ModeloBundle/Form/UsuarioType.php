<?php
namespace Administracion\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsuarioType extends AbstractType
{


    public function __construct ()
    {
       
    }    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        
        $builder                    
            ->add('username', 'text', array(
                'label' => 'Nombre de usuario',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Nombre de usuario',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
                )                
            )   
            ->add('password', 'password', array(
                'label' => 'Contraseña',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Contraseña',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))    
            ->add('nombre', 'text', array(
                'label' => 'Nombre',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Nombre',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))   
            ->add('apellidos', 'text', array(
                'label' => 'Apellidos',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Apellidos',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))  
            ->add('apellidos', 'text', array(
                'label' => 'Apellidos',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Apellidos',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))                
            ->add('telefono', 'text', array(
                'label' => 'Teléfono',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Teléfono',
                    'size'=>40
                ),
                'required' => false
                
            ))
            ->add('email', 'text', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'validate[required, custom[email]]',
                    'placeholder' => 'Email',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))                  
            ->add('categoria', 'text', array(
                'label' => 'Categoría',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Categoría',
                    'size'=>40
                ),
                'required' => false
                
            ));                  
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Administracion\ModeloBundle\Entity\Usuario'            
        ));       
    }

    public function getName()
    {
        return 'administracion_modelobundle_usuariotype';
    }
}
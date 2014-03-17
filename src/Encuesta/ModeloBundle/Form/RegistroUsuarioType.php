<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistroUsuarioType extends AbstractType
{
    var $datosPerfil;

    public function __construct ($datos_perfil = false)
    {
        $this->datosPerfil  = $datos_perfil;
    }    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        
        $builder            
            ->add('username', 'text', array(
                'label' => 'Nombre de usuario',
                'attr' => array(
                    'class' => 'validate[required] required campo_text',
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
                    'class' => 'validate[required] required campo_text',
                    'placeholder' => 'Contraseña',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))  
           
            ->add('email', 'email', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'validate[required, custom[email]] email campo_text',
                    'placeholder' => 'Email',
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
            'data_class' => 'Encuesta\ModeloBundle\Entity\Usuario'            
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_registrousuariotype';
    }
}
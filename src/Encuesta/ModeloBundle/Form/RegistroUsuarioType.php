<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Country;

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
                    'class' => 'validate[required] campo_text',
                    /*'placeholder' => 'Nombre de usuario',*/
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
                    'class' => 'validate[required] campo_text',
                    /*'placeholder' => 'Contraseña',*/
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            )) 
            ->add('codigo_postal', 'text', array(
                'label' => 'Código postal',
                'attr' => array(
                    'class' => 'validate[required] campo_text cod_postal',
                    /*'placeholder' => 'Código postal',*/
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
                )                
            )   
            ->add('pais', 'country', array(
                'required' => false,
                'data'=> 'ES',
                'constraints' => array(
                    new Country(),                    
                ),
            ))  
            ->add('acepta_termino', 'checkbox', array(
                'required' => true,
                'attr' => array(
                    'class' => 'validate[required]',
                    /*'placeholder' => 'Acepta terminos'*/
                    
                ),
                'mapped' => false
            ))                
           
            ->add('email', 'email', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'validate[required, custom[email]] email campo_text',
                    /*'placeholder' => 'Email',*/
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
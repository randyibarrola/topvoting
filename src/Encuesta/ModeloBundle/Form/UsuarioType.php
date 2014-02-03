<?php
namespace Administracion\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsuarioType extends AbstractType
{
    protected $sucursales;
    protected $usuario;

    public function __construct (array $sucursales, $usuario)
    {
        $this->sucursales = $sucursales;
        $this->usuario = $usuario;
    }    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $roles = $this->usuario->getRolesObj();
        $builder
            ->add('perfil', 'choice', array(
                'label' => 'Perfil',
                'choices' => array('1'=>'Administrador', '2'=>'Gerente', '3'=>'General'),
                'empty_value' => false,
                'attr' => array('class' => 'validate[required] txt_gris12'),
                'mapped' => false,
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                ) ,
                'data' => count($roles) > 0 ? $roles[0]->getId() : null
            ))    
            ->add('sucursal', 'choice', array(
                'label' => 'Sucursal',
                'choices' => $this->sucursales,
                'empty_value' => false,
                'attr' => array('class' => 'validate[required] txt_gris12'),
                'mapped' => false,
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                ),
                'data' => $this->usuario->getSucursal() ? $this->usuario->getSucursal()->getId() : null
            ))                 
            ->add('username', 'text', array(
                'label' => 'Usuario',
                'attr' => array(
                    'class' => 'validate[required] txt_gris12',
                    'placeholder' => 'Usuario',
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
                    'class' => 'validate[required] txt_gris12',
                    'placeholder' => 'Contraseña',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))                  
            ->add('telefono', 'text', array(
                'label' => 'Tel./ Cel',
                'attr' => array(
                    'class' => 'validate[required] txt_gris12',
                    'placeholder' => 'Teléfono o Celular',
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
            'data_class' => 'Administracion\ModeloBundle\Entity\Usuario'            
        ));       
    }

    public function getName()
    {
        return 'administracion_modelobundle_usuariotype';
    }
}
<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductoType extends AbstractType
{
    protected $categoria;
    protected $subcategoria;
    protected $categoriaSeleccionada;
    protected $subcategoriaSeleccionada;    

    public function __construct (array $categoria, array $subcategoria, $categoriaSeleccionada = null, $subcategoriaSeleccionada = null)
    {
        $this->categoria = $categoria;
	$this->subcategoria = $subcategoria;
        $this->categoriaSeleccionada = $categoriaSeleccionada;
        $this->subcategoriaSeleccionada = $subcategoriaSeleccionada;        
    }    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $builder
	  ->add('categoria', 'choice', array(
                'label' => 'Categoría',
                'choices' => $this->categoria,
                'preferred_choices' => $this->categoriaSeleccionada ?  array(0=>$this->categoriaSeleccionada) : array(),
                'empty_value' => false,
                'attr' => array('class' => 'validate[required] txt_gris12'),
                'mapped' => false,
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                ),   
                'data'=> $this->categoriaSeleccionada              
            )) 
			
	  ->add('subcategoria', 'choice', array(
                'label' => 'Subcategoría',
                'choices' => $this->subcategoria,
                'empty_value' => false,
                'attr' => array('class' => 'txt_gris12 validate[required]'),
                'mapped' => false,
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                ),
                'data'=> $this->subcategoriaSeleccionada
            )) 
			
	  ->add('nombre', 'text', array(
                'label' => 'Nombre del producto',
                'attr' => array(
                'class' => 'txt_gris12 validate[required]',
                'size'=>40
                ),
                'constraints' => array(
                    new NotBlank()                   
                   ),
              ))
			  
	  ->add('descripcion', 'text', array(
                'label' => 'Descripción',
                'attr' => array(
                'class' => 'txt_gris12 validate[required]',
                'size'=>50
                ),
                'constraints' => array(
                    new NotBlank()                   
                   ),
              ))
			  
	  ->add('precio_pizza_grande', 'number', array(
                'label' => 'Precio Grande',
                'attr' => array(
                'class' => 'txt_gris12 validate[required,custom[number]]',
                'size'=>40
                )
                
              ))
			  
	  ->add('precio_pizza_chica', 'number', array(
                'label' => 'Precio Chica',
                'attr' => array(
                'class' => 'txt_gris12 validate[required,custom[number]]',
                'size'=>40
                )
              ))	


	  ->add('precio', 'number', array(
                'attr' => array(
                'class' => 'txt_gris12 validate[required,custom[number]]',
                'size'=>50
                ),
		'required' => true
                
              ));			  
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Producto'
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_productotype';
    }
}
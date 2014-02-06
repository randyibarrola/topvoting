<?php

namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubcategoriaType extends AbstractType {

    protected $categoria;

    public function __construct(array $categoria) {
        $this->categoria = $categoria;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('nombre', 'text', array(
        'label' => 'Nombre',
        'attr' => array(
        'class' => 'validate[required] txt_gris12',
        'size' => 40
        ),
        'required' => true,
        'constraints' => array(
        new NotBlank()
        ),
        ))
        ->add('sucursal', 'choice', array(
        'label' => 'Sucursal',
        'choices' => $this->categoria,
        'empty_value' => false,
        'attr' => array('class' => 'validate[required] txt_gris12'),
        'mapped' => false,
        'required' => true,
        'constraints' => array(
        new NotBlank()
        )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Subcategoria'
        ));
    }

    public function getName() {
        return 'encuesta_modelobundle_subcategoriatype';
    }

}
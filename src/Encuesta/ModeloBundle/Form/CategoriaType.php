<?php

namespace Administracion\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoriaType extends AbstractType {

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
                ->add('tipo_pizza', 'choice', array(
                    'label' => 'Tamaño de pizza',
                    'choices' => array('1' => 'Grande', '2' => 'Chica'),
                    'empty_value' => false,
                    'attr' => array('class' => 'txt_gris12'),
                    'mapped' => false,
                    'constraints' => array(
                        new NotBlank()
                    )
                ))
                ->add('tipo_pizza', 'choice', array(
                    'label' => 'Porción de pizza',
                    'choices' => array('1' => 'Entera', '2' => 'Mitad/Mitad', '3' => 'Mitad/2 Cuartos', '4' => '4 Cuartos'),
                    'empty_value' => false,
                    'attr' => array('class' => 'txt_gris12'),
                    'mapped' => false,
                    'constraints' => array(
                        new NotBlank()
                    )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Administracion\ModeloBundle\Entity\Categoria'
        ));
    }

    public function getName() {
        return 'administracion_modelobundle_categoriatype';
    }

}


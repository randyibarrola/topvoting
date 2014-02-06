<?php

namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nombre', 'text', array(
                'label' => false,
                'required' => true
            ))
            ->add('descripcion', 'textarea', array(
                'label' => false,
                'required' => true
            ))
            ->add('padre', 'entity', array(
                'label' => false,
                'required' => false,
                'class' => 'ModeloBundle:Categoria',
                'property' => 'nombre',
            ))
            ->add('imagen', 'file', array(
                'label' => false,
                'required' => false,
                'data_class' => null
            ));
       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Categoria'
        ));
    }

    public function getName() {
        return 'encuesta_modelobundle_categoriatype';
    }

}


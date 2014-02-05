<?php

namespace Administracion\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoriaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nombre', 'text', array(
                    'label' => 'Nombre',
                    'attr' => array(
                        'class' => 'validate[required]',
                        'size' => 40
                    ),
                    'required' => true,
                    'constraints' => array(
                        new NotBlank()
                    ),
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


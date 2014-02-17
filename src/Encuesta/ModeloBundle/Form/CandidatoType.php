<?php

namespace Encuesta\ModeloBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CandidatoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('titulo', 'text', array(
                'label' => false,
                'required' => true
            ))
            ->add('descripcion', 'textarea', array(
                'label' => false,
                'required' => false
            ))
            ->add('categoria', 'entity', array(
                'label' => false,
                'required' => false,
                'class' => 'ModeloBundle:Categoria',
                'property' => 'nombre',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'group_by' => 'c.padre'
            ))
            ->add('imagen', 'file', array(
                'label' => false,
                'required' => false,
                'data_class' => null
            ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Candidato'
        ));
    }

    public function getName() {
        return 'encuesta_modelobundle_candidatotype';
    }

}


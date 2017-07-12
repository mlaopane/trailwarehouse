<?php

namespace TrailWarehouse\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use TrailWarehouse\AppBundle\Form\ProductType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FamilyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name')
          ->add('brand', EntityType::class, [
            'class' => 'TrailWarehouseAppBundle:Brand',
            'label' => 'Marque',
            'choice_label' => 'name',
            'multiple' => true,
          ])
          ->add('category', EntityType::class, [
            'class' => 'TrailWarehouseAppBundle:Category',
            'label' => 'Catégorie',
            'choice_label' => 'name',
            'multiple' => true,
          ])
          ->add('description', TextType::class)
          ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TrailWarehouse\AppBundle\Entity\Family'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trailwarehouse_appbundle_family';
    }


}

<?php

namespace TrailWarehouse\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use TrailWarehouse\AppBundle\Form\FamilyType;
use TrailWarehouse\AppBundle\Form\ColorType;
use TrailWarehouse\AppBundle\Form\SizeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('family', FamilyType::class)
          ->add('price', TextType::class)
          ->add('stock', TextType::class)
          ->add('color', ColorType::class, ['required' => false])
          ->add('size', SizeType::class, ['required' => false])
          ->add('image', ImageType::class, ['required' => false])
          ->add('ref', TextType::class, ['required' => false])
          ->add('save', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TrailWarehouse\AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trailwarehouse_appbundle_product';
    }


}

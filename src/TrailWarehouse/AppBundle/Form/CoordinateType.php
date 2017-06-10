<?php

namespace TrailWarehouse\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoordinateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $class = ['class' => 'form-control col-6 mb-3'];
      $builder
        ->add('address', TextType::class, ['label' => 'Adresse', 'attr' => $class])
        ->add('zipcode', TextType::class, ['label' => 'Code postal', 'attr' => $class])
        ->add('city', TextType::class, ['label' => 'Ville', 'attr' => $class])
        ->add('type', TextType::class, ['label' => 'Type', 'attr' => $class])
      ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TrailWarehouse\AppBundle\Entity\Coordinate'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trailwarehouse_appbundle_coordinate';
    }


}

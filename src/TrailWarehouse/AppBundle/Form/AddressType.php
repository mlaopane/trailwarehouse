<?php

namespace TrailWarehouse\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $class = ['class' => 'form-control col-6 mb-3'];
      $builder
        ->add('title', TextType::class, ['label' => 'Titre*', 'attr' => $class])
        ->add('street', TextType::class, ['label' => 'Addresse*', 'attr' => $class])
        ->add('zipcode', TextType::class, ['label' => 'Code postal*', 'attr' => $class])
        ->add('city', TextType::class, ['label' => 'Ville*', 'attr' => $class])
        ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
      ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TrailWarehouse\AppBundle\Entity\Address'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trailwarehouse_appbundle_address';
    }


}

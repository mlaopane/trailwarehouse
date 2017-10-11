<?php

namespace TrailWarehouse\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use TrailWarehouse\AppBundle\Entity\Address;

class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $user = $options['user'];
      $addresses = $options['addresses'];
      $attr = ['class' => 'mb-3'];
      $builder
        ->add('address', ChoiceType::class, [
          'label'        => 'Adresse',
          'attr'         => $attr,
          'expanded'     => true,
          'choices'      => $addresses,
          'choice_label' => function($address, $key, $index) {
            return ucfirst($address->getTitle());
          },
          'choice_value' => function($address) {
            if (!empty($address)) {
              return $address->getId();
            }
          },
        ])
        ->add('send', SubmitType::class, [
          'label' => 'Valider',
          'attr'  => ['class' => 'btn btn-confirm w-100'],
        ])
      ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'TrailWarehouse\AppBundle\Entity\Order'
        ));
        $resolver->setRequired('user');
        $resolver->setRequired('addresses');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trailwarehouse_appbundle_order';
    }


}

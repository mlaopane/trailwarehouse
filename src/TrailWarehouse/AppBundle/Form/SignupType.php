<?php

namespace TrailWarehouse\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use TrailWarehouse\AppBundle\Entity\User;

class SignupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('lastname', TextType::class, [
              'label' => "Nom de famille*",
              'attr' => [
                  'class' => 'form-control',
                  'placeholder' => 'Nom de famille',
              ]
          ])
          ->add('firstname', TextType::class, [
              'label' => 'Prénom*',
              'attr' => [
                  'class' => 'form-control',
                  'placeholder' => 'Prénom',
              ]
          ])
          ->add('email', EmailType::class, [
              'label' => 'E-mail*',
              'attr' => [
                  'class' => 'form-control',
                  'placeholder' => 'xyz@example.com',
              ],
          ])
          ->add('plainPassword', RepeatedType::class, [
            'type'            => PasswordType::class,
            'first_options'   => [
                'label' => 'Mot de passe*',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Minimum 6 caractères',
                ]
            ],
            'second_options'  => [
                'label' => 'Confirmation du mot de passe*',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '',
                ]
            ],
            'invalid_message' => 'Les mots de passe ne correspondent pas',
          ])
          ->add('termsAccepted', CheckboxType::class, [
            'mapped'      => false,
            'constraints' => new IsTrue(),
          ])
          ->add('send', SubmitType::class, [
              'label' => "Valider l'inscription",
              'attr' => [
                'class' => 'btn btn-confirm w-100'
              ],
          ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trailwarehouse_appbundle_user';
    }


}

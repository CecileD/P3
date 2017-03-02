<?php

namespace MDL\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array(
                'label' => 'Nom',
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom',
            ))
            ->add('country', CountryType::class, array(
                'label' => 'Pays',
                'preferred_choices' => array('FR'),
            ))
            ->add('dateOfBirth', BirthdayType::class, array(
                'label' => 'Date de naissance',

                'attr' => ['class' => 'date_of_birth'],

                'format' => 'dd-MM-yyyy',

                'placeholder' => array(
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ))
            )
            ->add('reducedPricing', CheckboxType::class, array(
                'label'    => 'Tarif Réduit (applicable sur présentation d\'un justificatif lors de la validation des billets aux personnes éligibles : étudiant, employé du musée, d’un service du Ministère de la Culture, militaire).',
                'required' => false,
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MDL\CoreBundle\Entity\Visitor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mdl_corebundle_visitor';
    }


}

<?php

namespace MDL\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',     EmailType::class)

            //Ajout du champ de sélection de la date (calendrier généré en jquery)
            ->add('date',      DateType::class, array(
                'widget' => 'single_text',

                // Permet de changer le input en champs texte et non date (nécessaire pour le plugin)
                'html5' => false,

                // On ajoute un id pour la sélection jquery
            ))
            // Ajout du champ de sélection de la validité du ticket (journée ou demi-journée)
            ->add('ticketDuration', ChoiceType::class, array(

                //Ajout des choix de sélection possibles
                'choices'  => array(
                    'Demi-journée' => 'Demi-journée',
                    'Journée' => 'Journée',
                ),
            ))
            ->add('visitors', CollectionType::class, array(
                'entry_type'   => VisitorType::class,
                'label' => 'Visiteurs',
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => true
            ))
            ->add('save', SubmitType::class,  array(
                'attr' => array('class' => 'btn btn-default form-group'),
                'label' =>'Réserver'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MDL\CoreBundle\Entity\Registration'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mdl_corebundle_registration';
    }


}

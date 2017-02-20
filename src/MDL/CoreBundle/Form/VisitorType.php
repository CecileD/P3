<?php

namespace MDL\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('country', TextType::class, array(
                'label' => 'Pays',
            ))
            ->add('dateOfBirth', DateType::class, array(
                'label' => 'Date de naissance',

                'widget' => 'single_text',

                // Permet de changer le input en champs texte et non date (nécessaire pour le plugin)
                'html5' => false,

                // On ajoute un id pour la sélection jquery
                'attr' => ['class' => 'date_of_birth form-control col-sm-10'],
        ));
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

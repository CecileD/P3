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
                'placeholder'=> 'France',
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
        /*

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,    // 1er argument : L'évènement qui nous intéresse : ici, PRE_SET_DATA
            function(FormEvent $event) { // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
                // On récupère notre objet Advert sous-jacent
                $visitor = $event->getData();


                // Cette condition est importante, on en reparle plus loin
                if (null === $visitor) {
                    return; // On sort de la fonction sans rien faire lorsque $advert vaut null
                }


                $birthdayVisitor = $visitor ->getDateOfBirth();
                $currentDate = new \DateTime();
                $ageVisitor = $currentDate->diff($birthdayVisitor)->y;

                $repository = $this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository('OCPlatformBundle:Advert')
                ;

                if($ageVisitor >=0 && $ageVisitor <4)
                {
                    $Price = new Pricing();
                    $visitor.setPrice($Price.find());
                }
                if (!$advert->getPublished() || null === $advert->getId()) {
                    // Alors on ajoute le champ published
                    $event->getForm()->add('published', CheckboxType::class, array('required' => false));
                } else {
                    // Sinon, on le supprime
                    $event->getForm()->remove('published');
                }
            }

        );
        */
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

<?php
/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 08/02/2017
 * Time: 15:56
 */

namespace MDL\CoreBundle\Controller;

use MDL\CoreBundle\Entity\Registration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class MainController extends Controller
{
    public function homeAction()
    {
        $content = $this->get('templating')->render('MDLCoreBundle:Home:index.html.twig');
        return new Response($content);
    }

    public function registrationAction()
    {
        //Création d'une nouvelle réservation
        $registration = new Registration();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $registration);

        $formBuilder
            ->add('email',     EmailType::class)

            //Ajout du champ de sélection de la date (calendrier généré en jquery)
            ->add('date',      DateType::class, array(
                'widget' => 'single_text',

                // Permet de changer le input en champs texte et non date (nécessaire pour le plugin)
                'html5' => false,

                // On ajoute un id pour la sélection jquery
                'attr' => ['id' => 'registration_date', 'style' => 'display:none;'],
            ))
            // Ajout du champ de sélection de la validité du ticket (journée ou demi-journée)
            ->add('ticketDuration', ChoiceType::class, array(

                //Ajout des choix de sélection possibles
                'choices'  => array(
                    'Demi-journée' => 'Demi-journée',
                    'Journée' => 'Journée',
                ),

                //On cache le champ
                'attr' => ['style' => 'display:none;'],
                ))
        ;

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        return $this->render('MDLCoreBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
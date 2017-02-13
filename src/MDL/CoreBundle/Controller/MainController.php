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
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('date',      DateType::class, array(
                'widget' => 'single_text',

                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // add a class that can be selected in JavaScript
                'attr' => ['id' => 'registration_date', 'style' => 'display:none;'],
            ))
        ;

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        return $this->render('MDLCoreBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
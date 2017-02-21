<?php
/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 08/02/2017
 * Time: 15:56
 */

namespace MDL\CoreBundle\Controller;

use MDL\CoreBundle\Entity\Registration;
use MDL\CoreBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class MainController extends Controller
{
    public function homeAction()
    {
        $content = $this->get('templating')->render('MDLCoreBundle:Home:index.html.twig');
        return new Response($content);
    }

    public function registrationAction(Request $request)
    {
        //Création d'une nouvelle réservation
        $registration = new Registration();
        //On créé le formulaire déjà défini dans le fichier RegistrationType
        $form = $this->get('form.factory')->create(RegistrationType::class, $registration);

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('MDLCoreBundle:Pricing')
        ;

        $listPrices = $repository->findAll();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $registration->setTotalPrice(0);
            $registration->setRegistrationCode('salut2');
            $registration->setNbTicket(12);
            foreach($registration->getVisitors() as $visitor)
            {
                $visitor->setPrice($repository->find(1));
                $visitor->setRegistration($registration);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($registration);
            $em->flush();


            return $this->redirectToRoute('mdl_core_home', array('id' => $registration->getId()));
        }

        return $this->render('MDLCoreBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
            'listPrices' => $listPrices,
        ));

    }
}
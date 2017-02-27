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
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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



        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            //Récupération du service de réservation
            $registerSRV= $this->container->get('mdl_core.registration');

            //Lancement de la réservation sur l'objet registration
            $registerSRV->register($registration);

            return $this->redirectToRoute('mdl_core_payment', array('id' => $registration->getId()));
        }

        return $this->render('MDLCoreBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function paymentAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer une seule annonce, on utilise la méthode find($id)
        $registration = $em->getRepository('MDLCoreBundle:Registration')->find($id);

        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id n'existe pas, d'où ce if :
        if (null === $registration) {
            throw new NotFoundHttpException("Réservation introuvable");
        }

        // Récupération de la liste des candidatures de l'annonce
        $listVisitors = $em
            ->getRepository('MDLCoreBundle:Visitor')
            ->findBy(array('registration' => $registration))
        ;

        $tableLines = [];

        foreach($listVisitors as $key=>$visitor )
        {
            $pricing = $visitor->getPrice();
            $tableLines[$key]=array('nom'=>$visitor->getLastname(), 'prenom'=>$visitor->getFirstname(), 'tarif'=>$pricing->getType(), 'prix'=>$pricing->getPrice());
        }

        $content = $this->get('templating')->render('MDLCoreBundle:Registration:payment.html.twig',array(
            'tableLines'=>$tableLines,
            'registration'=>$registration,
        ));
        return new Response($content);
    }
}
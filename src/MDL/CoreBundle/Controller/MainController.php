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
        if($this->get('session')->has('réservation'))
        {
            $session = $this->get('session');
            $registration = $session->get('réservation');
        }else
        {
            //Création d'une nouvelle réservation
            $registration = new Registration();
        }

        //On créé le formulaire déjà défini dans le fichier RegistrationType
        $form = $this->get('form.factory')->create(RegistrationType::class, $registration);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            //Récupération du service de réservation
            $registerSRV= $this->container->get('mdl_core.registration');

            //On vérifie si la limite de billet pour cette journée n'a pas été atteinte
            if($registerSRV->limitReached($registration) == true)
            {
                //Si elle a été atteinte on recharge la page en affichant un message d'erreur
                $request->getSession()->getFlashBag()->add('error', 'Il n\'y a plus de tickets disponibles pour la journée sélectionnée');
                return $this->render('MDLCoreBundle:Registration:registration.html.twig', array(
                    'form' => $form->createView(),
                ));
            }else
            {
                //Sinon : lancement de la réservation sur l'objet registration
                $registerSRV->register($registration);
                $session = $this->get('session');
                $session->set('réservation', $registration);
                return $this->redirectToRoute('mdl_core_payment', array('id' => $registration->getId()));
            }

        }

        return $this->render('MDLCoreBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function paymentAction(Request $request)
    {
        // Pour récupérer une seule annonce, on utilise la méthode find($id)
        $session = $this->get('session');
        $registration = $session->get('réservation');

        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id n'existe pas, d'où ce if :
        if (null === $registration || $registration->getPaid()=== 1) {
            throw new NotFoundHttpException("Réservation introuvable ou déjà réglée");
        }

        $tableLines = $this->container->get('mdl_core.maketable')->makeTable();

        if ($request->isMethod('POST'))
        {
            $paymentSRV= $this->container->get('mdl_core.stripepayment');
            $paymentSRV->registrationPayment($registration->getTotalPrice()*100,$registration->getRegistrationCode(),$_POST['stripeToken'],$registration,$tableLines);
            return $this->redirectToRoute('mdl_core_confirmation', array('id' => $registration->getId()));
        }

        $content = $this->get('templating')->render('MDLCoreBundle:Registration:payment.html.twig',array(
            'tableLines'=>$tableLines,
            'registration'=>$registration,
        ));
        return new Response($content);
    }

    public function confirmationAction()
    {
        //Changer requête
        $session = $this->get('session');
        $registration = $session->get('réservation');

        $tableLines = $this->container->get('mdl_core.maketable')->makeTable();

        if (null === $registration) {
            throw new NotFoundHttpException("Erreur commande inexistante ou finalisée");
        }

        if(!$session->has('erreur'))
        {
            $this->get('session')->clear();
        }

        $content = $this->get('templating')->render('MDLCoreBundle:Registration:confirmation.html.twig',array(
            'tableLines'=>$tableLines,
            'registration'=>$registration,
        ));
        return new Response($content);
    }
}
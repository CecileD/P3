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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MainController extends Controller
{
    public function homeAction()
    {
        //On génère la page à l'aide du template index
        $content = $this->get('templating')->render('MDLCoreBundle:Home:index.html.twig');
        return new Response($content);
    }

    public function registrationAction(Request $request)
    {
        //Si l'entité réservation est définie en session
        if($this->get('session')->has('réservation'))
        {
            //On récupère l'objet réservation
            $session = $this->get('session');
            $registration = $session->get('réservation');
        }else
        {
            //Sinon création d'une nouvelle réservation
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

        //On génère la page à l'aide du template registration
        return $this->render('MDLCoreBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function paymentAction(Request $request)
    {
        // On récupère l'entité réservation stockée en session
        $session = $this->get('session');
        $registration = $session->get('réservation');

        //Si la réservation n'existe pas ou a déjà été réglée on affiche une erreur
        if (null === $registration || $registration->getPaid()=== 1) {
            throw new NotFoundHttpException("Réservation introuvable ou déjà réglée");
        }

        //On créé le tableau récapitulatif de commande grâce au service makeTable
        $tableLines = $this->container->get('mdl_core.maketable')->makeTable();

        if ($request->isMethod('POST'))
        {
            //Si le formulaire Stripe est POST on lance le paiement de la réservation
            $paymentSRV= $this->container->get('mdl_core.stripepayment');
            $paymentSRV->registrationPayment($registration->getTotalPrice()*100,$registration->getRegistrationCode(),$_POST['stripeToken'],$registration,$tableLines);
            $em = $this->getDoctrine()->getManager();
            //$id = $em->getRepository('MDLCoreBundle:Registration')->findOneBy(array('registrationCode'=>$registration->getRegistrationCode()))->getId();
            return $this->redirectToRoute('mdl_core_confirmation', array('id' => $registration->getRegistrationCode()));
        }

        //On génère la page à l'aide du template payment
        $content = $this->get('templating')->render('MDLCoreBundle:Registration:payment.html.twig',array(
            'tableLines'=>$tableLines,
            'registration'=>$registration,
        ));
        return new Response($content);
    }

    public function confirmationAction($id)
    {
        //On récupère cette fois la réservation en base pour pouvoir recharger la page de confirmation même après la suppression de la session
        $em = $this->getDoctrine()->getManager();
        $registration = $em->getRepository('MDLCoreBundle:Registration')->findOneBy(array('registrationCode'=>$id));

        //On créé le tableau récapitulatif de commande grâce au service makeTable
        $tableLines = $this->container->get('mdl_core.maketable')->makeTable($registration);

        //En cas d'erreur d'URL on affiche une erreur 404
        if (null === $registration) {
            throw new NotFoundHttpException("Erreur commande inexistante ou finalisée");
        }

        //Si le paiement ne s'est pas terminer sur une erreur, on vide la session
        if(!$this->get('session')->has('erreur'))
        {
            $this->get('session')->clear();
        }

        //On génère la page à l'aide du template confirmation
        $content = $this->get('templating')->render('MDLCoreBundle:Registration:confirmation.html.twig',array(
            'tableLines'=>$tableLines,
            'registration'=>$registration,
        ));
        return new Response($content);
    }

    public function checkDateAction(Request $req)
    {
        if($req->isXmlHttpRequest()) {
            $date = $req->get('date');
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('MDLCoreBundle:Registration');

            //On récupère le nombre total de tickets réservés présents dans la base pour cette date
            $dbTicketNb = $repository->getVisitorNumberPerDate($date);
            if($dbTicketNb>=1000)
            {
                $reponse = "<div class=\"col-sm-12 alert alert-danger messageCheck\" style='margin-top : 10px;'> Il n'y a plus de ticket pour cette journée </div>";
                return new JsonResponse(array('reponse'=> json_encode($reponse), 'limit'=>true));
            }else if((1000-$dbTicketNb)<=100){
                $reponse = "<div class=\"col-sm-12 alert alert-warning messageCheck\" style='margin-top : 10px;'>Il reste ".(1000-$dbTicketNb)." billets pour cette journée</div>";
                return new JsonResponse(array('reponse'=>json_encode ($reponse), 'limit'=>false));
            }else{
                $reponse = "<div class=\"col-sm-12 alert alert-success messageCheck\" style='margin-top : 10px;'>Il reste ".(1000-$dbTicketNb)." billets pour cette journée</div>";
                return new JsonResponse(array('reponse'=>json_encode ($reponse), 'limit'=>false));
            }
        }
        return new Response("Erreur : Erreur requête Ajax");
    }

}
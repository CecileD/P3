<?php

namespace MDL\CoreBundle\Stripe;

use Doctrine\ORM\EntityManagerInterface;
use MDL\CoreBundle\Entity\Registration;
use MDL\CoreBundle\Mailer\MDLConfirmationMailer;
use Symfony\Component\HttpFoundation\Session\Session;

class MDLStripePayment
{
    private $session;
    private $em;
    private $confirmationMailer;

    public function __construct(Session $session, EntityManagerInterface $em, MDLConfirmationMailer $confirmationMailer)
    {
        $this->session = $session;
        $this->em =$em;
        $this->confirmationMailer = $confirmationMailer;
    }

    public function registrationPayment($amount, $registrationNb, $stripeToken,Registration $registration, Array $tableLines)
    {
        \Stripe\Stripe::setApiKey("sk_test_rbVoaIfJuVbE7JnmKS7Gwzvx");

        // On récupère les informations rentrées par l'utilisateur
        $token = $stripeToken;

        // On lance le paiement
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $amount, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - Réservation n°".$registrationNb
            ));

            $registration->setPaid(1);

            //On envoi le mail de confirmation grâce au service mailer
            $this->confirmationMailer->mailingConfirmation($registration, $tableLines);

            //On entre les informations en base
            $this->em->merge($registration);
            $this->em->flush();

            //On affiche le succès dans les flash bags
            $this->session->getFlashBag()->add("success","Paiement effectué avec succès ");

        } catch(\Stripe\Error\Card $e) {
            $registration->setPaid(0);
            //On affiche une erreur dans les flash bags
            $this->session->getFlashBag()->add("error","Une erreur s'est produite lors du paiement");
            $this->session->set('erreur',true);
            // La carte n'est pas acceptée
        }
    }
}
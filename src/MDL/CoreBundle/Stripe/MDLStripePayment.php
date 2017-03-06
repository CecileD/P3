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
        \Stripe\Stripe::setApiKey("sk_test");

        // Get the credit card details submitted by the form
        $token = $stripeToken;

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $amount, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - Réservation n°".$registrationNb
            ));
            $registration->setPaid(1);
            $this->confirmationMailer->mailingConfirmation($registration, $tableLines);
            $this->em->persist($registration);
            $this->em->flush();
            $this->session->getFlashBag()->add("success","Paiement effectué avec succès ");

        } catch(\Stripe\Error\Card $e) {
            $registration->setPaid(0);
            $this->em->persist($registration);
            $this->em->flush();
            $this->session->getFlashBag()->add("error","Une erreur s'est produite lors du paiement");
            // The card has been declined
        }
    }
}
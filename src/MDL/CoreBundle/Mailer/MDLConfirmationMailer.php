<?php

namespace MDL\CoreBundle\Mailer;

use MDL\CoreBundle\Entity\Registration;

/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 06/03/2017
 * Time: 15:51
 */
class MDLConfirmationMailer
{
    private $mailer;

    public function __construct($mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function mailingConfirmation(Registration $registration, Array $tableLines)
    {
        //Mail de confirmation
        $message = \Swift_Message::newInstance()
            ->setSubject('Validation de la commande de billets du musée du Louvre n° '. $registration->getRegistrationCode())
            ->setFrom(array('b.aubin95@gmail.com'=>"MDL Service réservation"))
            ->setTo($registration->getEmail())
            ->setCharset('utf-8')
            ->setContentType('text/html');

        $image = $message->embed(\Swift_Image::fromPath('../web/img/logo.jpg'));

        $message->setBody($this->templating->render('MDLCoreBundle:SwiftLayout:ConfirmationMail.html.twig', array('registration'=>$registration, 'tableLines'=> $tableLines, 'image'=>$image)));

        $this->mailer->send($message);
    }

}
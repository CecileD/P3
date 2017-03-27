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
        //Définition de la structure du mail de confirmation
        $message = \Swift_Message::newInstance()
            ->setSubject('Validation de la commande de billets du musée du Louvre n° '. $registration->getRegistrationCode())
            ->setFrom(array('b.aubin95@gmail.com'=>"MDL Service réservation"))
            ->setTo($registration->getEmail())
            ->setCharset('utf-8')
            ->setContentType('text/html');

        //On incorpore le logo du musée au message
        $image = $message->embed(\Swift_Image::fromPath('../web/img/logo.jpg'));

        //Définiion du contenu du corps du message
        $message->setBody($this->templating->render('MDLCoreBundle:SwiftLayout:ConfirmationMail.html.twig', array('registration'=>$registration, 'tableLines'=> $tableLines, 'image'=>$image)));

        //Envoi du mail
        $this->mailer->send($message);
    }

}
<?php

namespace MDL\CoreBundle\Registration;

use Doctrine\ORM\EntityManagerInterface;
use MDL\CoreBundle\Entity\Registration;

class MDLRegistration
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function register(Registration $registration)
    {
        $repository = $this->em->getRepository('MDLCoreBundle:Pricing');

        //Tableau récupérant tous les prix
        $listPrices = $repository->findAll();

        //Création du code de réservation unique

        //Récupération de la date du jour
        $currentDate = new \DateTime();

        //Détermination du code à utiliser selon le type de réservation
        if($registration->getTicketDuration() == 'Demi-journée')
        {
            $dayCode = 'DJ';
        }else
        {
            $dayCode = 'JC';
        }

        //Génération d'un identifiant unique pour la réservation
        $idRegistration = md5(uniqid(rand(), true));

        //Concaténation de la date du jour du type de réservation et de l'id dans la BDD pour formé le code de réservation
        $registrationCode = ''. date_format($currentDate,'Y') .''.date_format($currentDate,'m').''.date_format($currentDate,'d').''.$dayCode.''.$idRegistration.'';
        $registration->setRegistrationCode($registrationCode);

        //Création des compteurs pour le nombre de ticket et le prix total de la réservation
        $ticketNb =0;
        $totalPrice =0;

        foreach($registration->getVisitors() as $visitor)
        {
            $birthdayVisitor = $visitor ->getDateOfBirth();
            $ageVisitor = $currentDate->diff($birthdayVisitor)->y;

            //Si l'utilisateur coche le tarif réduit et que le visiteur n'est pas éligible au tarif enfant ou moins de 4 ans
            if($visitor->getReducedPricing() == true && $ageVisitor >=12)
            {
                //On applique le tarif réduit
                $visitor->setPrice($listPrices[4]);
            }else
                //Sinon si le visiteur n'est pas éligible au tarif réduit on lui applique le tarif correspondant à son âge
            {
                switch ($ageVisitor)
                {
                    case ($ageVisitor >=0 && $ageVisitor <4) :
                        $visitor->setPrice($listPrices[0]);
                        break;
                    case ($ageVisitor >=4 && $ageVisitor <12) :
                        $visitor->setPrice($listPrices[1]);
                        break;
                    case ($ageVisitor >=12 && $ageVisitor <60) :
                        $visitor->setPrice($listPrices[2]);
                        break;
                    case ($ageVisitor >=60) :
                        $visitor->setPrice($listPrices[3]);
                        break;
                }
            }
            //On affecte la réservation correspondant au visiteur
            $visitor->setRegistration($registration);


            //on compte le nombre de ticket
            $ticketNb++;

            //On calcule le prix total a payé pour cette réservation
            $pricing = $visitor->getPrice();
            $totalPrice += $pricing->getPrice();
        }

        //On affecte les valeurs  de prix total et du nombre de billets, obtenus dans la boucle, à l'objet réservation correspondant
        $registration->setNbTicket($ticketNb);
        $registration->setTotalPrice($totalPrice);

        //On ajoute les données dans la bdd
        $this->em->persist($registration);
        $this->em->flush();

    }


}
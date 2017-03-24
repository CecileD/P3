<?php
/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 20/03/2017
 * Time: 18:01
 */

namespace tests\MDL\CoreBundle\Registration;

use Doctrine\Common\Collections\ArrayCollection;
use MDL\CoreBundle\Entity\Registration;
use MDL\CoreBundle\Entity\Visitor;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MDLRegistrationTest extends WebTestCase
{
    private $registration;
    private $registerSRV;
    private $visitor1;
    private $visitor2;

    public function setUp ()
    {
        //On crée un objet client pour récupérer le container
        $client = static::createClient();

        //On crée et on hydrate un premier visiteur de test
        $this->visitor1 = new Visitor();
        $this->visitor1->setCountry('France');
        $this->visitor1->setDateOfBirth(new \DateTime('1993-07-06'));
        $this->visitor1->setFirstname('Bob');
        $this->visitor1->setLastname('Dylan');
        $this->visitor1->setReducedPricing(1);

        //On crée et on hydrate un second visiteur de test
        $this->visitor2 = new Visitor();
        $this->visitor2->setCountry('France');
        $this->visitor2->setDateOfBirth(new \DateTime('1960-05-01'));
        $this->visitor2->setFirstname('Bob');
        $this->visitor2->setLastname('Dylan');
        $this->visitor2->setReducedPricing(0);


        //On crée une réservation test et on l'hydrate
        $this->registration = new Registration();
        //On choisit le 22/03/2017 comme date de réservation pour tester la fonction limitReached
        $this->registration->setDate(new \DateTime('2017-03-22'));
        $this->registration->setTicketDuration('Demi-journée');
        $this->registration->addVisitor($this->visitor1);
        $this->registration->addVisitor($this->visitor2);


        //On récupère le service que l'on étudie
        $this->registerSRV= $client->getContainer()->get('mdl_core.registration');

        //On appelle le service de réservation sur notre réservation test
        $this->registerSRV->register($this->registration);
    }

    public function testNbTicketDefinition()
    {
        //Crée une erreur à l'exécution de phpunit le nombre de ticket devant être de 2 dans cet exemple
        $this->assertEquals(3,$this->registration->getNbTicket());
    }

    public function testTotalPriceDefinition ()
    {
        //Ce test est passé car le total des billets est ici de 26€ (un tarif plein + un tarif réduit)
        $this->assertEquals(26,$this->registration->getTotalPrice());
    }

    public function testVisitor1PricingDefinition ()
    {
        //On vérifie que le tarif appliqué au premier visiteur est bien le tarif réduit
        $this->assertEquals('Tarif Réduit',$this->visitor1->getPrice()->getType());
    }

    public function testVisitor2PricingDefinition ()
    {
        //On vérifie que le tarif appliqué au second visiteur est le tarif plein
        $this->assertEquals('Plein Tarif',$this->visitor2->getPrice()->getType());
    }

    public function testRegistrationCodeDefinition()
    {
        //On vérifie que le code de réservation commence bien par la date du jour et le code du type de réservation
        $chaineTeste = substr($this->registration->getRegistrationCode(),0,10);
        $currentDate = new \DateTime();
        $test = ''. date_format($currentDate,'Y') .''.date_format($currentDate,'m').''.date_format($currentDate,'d').'DJ';
        $this->assertEquals($test,$chaineTeste);
    }

    public function testLimitReached()
    {
        //La journée du 22/03/2017 étant complète la fonction limitReached doit renvoyer true
        $this->assertEquals(true,$this->registerSRV->limitReached($this->registration));
    }
}
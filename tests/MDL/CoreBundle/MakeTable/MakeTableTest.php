<?php

namespace tests\MDL\CoreBundle\MakeTable;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use MDL\CoreBundle\Entity\Registration;
use MDL\CoreBundle\Entity\Visitor;


class MDLMakeTableTest extends WebTestCase
{
    public static $shared_session = array();
    private $registerSRV;
    private $registration;
    private $makeTableSRV;
    private $visitor1;
    private $visitor2;

    public function setUp()
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
        $this->visitor2->setFirstname('Robert');
        $this->visitor2->setLastname('Zimmerman');
        $this->visitor2->setReducedPricing(0);

        //On crée une réservation test et on l'hydrate
        $this->registration = new Registration();
        $this->registration->setDate(new \DateTime('2017-03-22'));
        $this->registration->setTicketDuration('Demi-journée');
        $this->registration->addVisitor($this->visitor1);
        $this->registration->addVisitor($this->visitor2);

        //On récupère le service que l'on étudie
        $this->makeTableSRV= $client->getContainer()->get('mdl_core.maketable');

        //On récupère le service de réservation pour pouvoir définir correctement l'objet de réservation à tester
        $this->registerSRV= $client->getContainer()->get('mdl_core.registration');

        //On appelle le service de réservation sur notre réservation test
        $this->registerSRV->register($this->registration);

    }

    public function testMakeTable()
    {
        //$_SESSION['réservation']= array($this->registration);
        $tableTested = $this->makeTableSRV->makeTable($this->registration);
        //die(var_dump($tableTested));
        for($i=0; $i<=1;$i++)
        {
            //On teste pourchaque visiteur que les clés (nom, prenom, tarif et prix) ont bien été définies
            $this->assertArrayHasKey('nom',$tableTested[$i]);
            $this->assertArrayHasKey('prenom',$tableTested[$i]);
            $this->assertArrayHasKey('tarif',$tableTested[$i]);
            $this->assertArrayHasKey('prix',$tableTested[$i]);

            if($i==0)
            {
                $visitorTested = $this->visitor1;
            }else{
                $visitorTested = $this->visitor2;
                //var_dump($visitorTested);
            }
            $pricing = $visitorTested->getPrice();

            //Puis on vérifie pour chaque visiteur que le tableau contient les bonnes valeurs pour chaque clé
            $this->assertContains($visitorTested->getFirstname(), $tableTested[$i]['prenom']);
            $this->assertContains($visitorTested->getLastname(), $tableTested[$i]['nom']);
            $this->assertContains($pricing->getType(), $tableTested[$i]['tarif']);
            $this->assertEquals($pricing->getPrice(), $tableTested[$i]['prix']);
        }
    }
}
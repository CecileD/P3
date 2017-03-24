<?php

namespace tests\MDL\CoreBundle\RegistrationRepository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RegistrationRepositoryTest extends KernelTestCase
{
    private $em;
    private $repository;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->repository = $this->em
            ->getRepository('MDLCoreBundle:Registration');
    }

    public function testVisitorNumberPerDate()
    {
        $date = new \DateTime('2017-03-22');

        $dbTicketNb = $this->repository->getVisitorNumberPerDate($date);

        $this->assertEquals(1000, $dbTicketNb);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
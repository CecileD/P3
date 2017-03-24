<?php

namespace MDL\CoreBundle\MakeTable;

use MDL\CoreBundle\Entity\Registration;
use Symfony\Component\HttpFoundation\Session\Session;

class MDLMakeTable
{
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function makeTable(Registration $registration=NULL)
    {
        if(!isset($registration))
        {
            $registration = $this->session->get('réservation');
        }

        $listVisitors = $registration->getVisitors();

        $tableLines = [];

        foreach($listVisitors as $key=>$visitor )
        {
            $pricing = $visitor->getPrice();
            $tableLines[$key]=array('nom'=>$visitor->getLastname(), 'prenom'=>$visitor->getFirstname(), 'tarif'=>$pricing->getType(), 'prix'=>$pricing->getPrice());
        }

        return $tableLines;
    }
}
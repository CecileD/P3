<?php

namespace MDL\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MDL\CoreBundle\Entity\Pricing;

class LoadPricing implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Nom des différents tickets de la billetterie
        $types = array('Tarif moins de 4 ans', 'Tarif enfant', 'Plein Tarif', 'Tarif Senior', 'Tarif Réduit');
        //Prix des différents tickets de la billetterie
        $prices = array(0, 8, 16, 12, 10);

        foreach ($types as $index => $type) {
            // On implémente une nouvelle tarification
            $pricing = new Pricing();
            // On l'hydrate avec les deux tableaux précédent
            $pricing->setType($type);
            $pricing->setPrice($prices[$index]);
            // On le persiste
            $manager->persist($pricing);
        }

        // On enregistre en base de données les nouvelles tarifications ainsi créées
        $manager->flush();
    }
}

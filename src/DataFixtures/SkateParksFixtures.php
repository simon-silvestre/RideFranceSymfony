<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\SkateParks;

class SkateParksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i > 6; $i++){
            $skatepark = new SkateParks();
            $skatepark->setRegion("Île-de-France")
                      ->setVille("Paris")
                      ->setContenu("<p>Contenu de l'article</p>")
                      ->setImage("http://placehold.it/350x150")
                      ->setAdresse("40 Rue Emile Lepeu, 75011 Paris")
                      ->setCreationDate(new \DateTime())
                      ->setUser(0);
            $manager->persist($skatepark);
        }

        $manager->flush();
    }
}

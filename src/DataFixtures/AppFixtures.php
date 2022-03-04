<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Clients;
use App\Entity\Service;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         
        $faker = Factory::create('en_US'); 
 
        // create fake costumers 
        for ($i = 0; $i < 20; $i++) {
            $client = new Clients();
            $client ->setNom($faker->firstName());
            $client->setAdress($faker->address());
            $client->setIce($faker->phoneNumber());
            $client->setTelephone($faker->phoneNumber());
            $client->setPays($faker->country());
            $client->setVille($faker->state());
            $client->setMail($faker->email());
            $manager->persist($client);
        }
        
                // create fake service  

        for ($j = 0; $j < 5; $j++) {
            $service = new Service();
            $service ->setNom($faker->firstName());  
            $manager->persist($service);
        }
        $manager->flush();


    }
}

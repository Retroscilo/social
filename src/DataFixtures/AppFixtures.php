<?php

namespace App\Faker\Provider;

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\User;
use App\Entity\Post;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $users = Array();
        for ($i = 0; $i < 4; $i++) {
            $users[$i] = new User();
            $users[$i]->setLastName($faker->lastName);
            $users[$i]->setFirstName($faker->firstName);
            $users[$i]->setEmail($faker->email);
            $users[$i]->setCity($faker->city);
            $users[$i]->setBirthDate($faker->dateTimeBetween('-2 years', 'now', null));
            $users[$i]->setAddress($faker->streetAddress);
            $users[$i]->setInternshipDescription($faker->paragraph);
            $users[$i]->setPassword($faker->password);

            $manager->persist($users[$i]);
        }
        $manager->flush();

        foreach($users as $user) {
            $posts = Array();
            for ($i = 0; $i < 5; $i++) {
                $posts[$i] = new Post;
                $posts[$i]->setUser($user);
                $posts[$i]->setName($faker->sentence);
                $posts[$i]->setContent($faker->paragraph);
                $posts[$i]->setCreatedAt($faker->dateTimeBetween('-2 years', 'now', null));

                $manager->persist($posts[$i]);
            }
            $manager->flush();
        }
    }
}

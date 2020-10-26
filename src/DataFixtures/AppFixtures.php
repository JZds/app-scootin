<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Scooter;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $longitude = (float)mt_rand(1, 50) . '.' . mt_rand(000, 999);
            $latitude = (float)mt_rand(1, 50) . '.' . mt_rand(000, 999);
            $manager->persist(
                (new Scooter())
                    ->setStatus(Scooter::STATUS_AVAILABLE)
                    ->setLongitude($longitude)
                    ->setLatitude($latitude)
                    ->setLocation(new Point($longitude, $latitude))
                    ->setUpdatedAt(new \DateTimeImmutable())
            );
            $manager->persist((new Client()));
        }
        $manager->flush();
    }
}

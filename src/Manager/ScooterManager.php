<?php

namespace App\Manager;

use App\Entity\Client;
use App\Entity\Scooter;
use App\Entity\ScooterReservation;
use App\Exception\ScooterAvailableException;
use App\Exception\ScooterUnavailableException;
use App\Repository\ScooterRepositoryInterface;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\ORM\EntityManagerInterface;

class ScooterManager
{
    private $entityManager;
    private $scooterRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ScooterRepositoryInterface $scooterRepository
    ) {
        $this->entityManager = $entityManager;
        $this->scooterRepository = $scooterRepository;
    }

    /**
     * @param Scooter $scooter
     * @param Client $client
     * @throws ScooterUnavailableException
     */
    public function reserveScooter(Scooter $scooter, Client $client)
    {
        if ($scooter->getStatus() !== Scooter::STATUS_AVAILABLE) {
            throw new ScooterUnavailableException();
        }
        $scooter->setStatus(Scooter::STATUS_OCCUPIED);

        /** @var ScooterReservation $scooterReservation */
        $scooterReservation = $client
            ->getScooterReservations()
            ->filter(function (ScooterReservation $scooterReservation) use ($scooter) {
                return
                    $scooterReservation->getScooter()->getUuid() === $scooter->getUuid()
                    && !$scooterReservation->isActive()
                ;
            })
            ->first()
        ;

        if ($scooterReservation instanceof ScooterReservation) {
            $scooterReservation->setActive(true);
        } else {
            $this->entityManager->persist(new ScooterReservation($client, $scooter, true));
        }
    }

    /**
     * @param ScooterReservation $scooterReservation
     * @throws ScooterAvailableException
     */
    public function revokeScooterReservation(ScooterReservation $scooterReservation)
    {
        if (!$scooterReservation->isActive()) {
            throw new ScooterAvailableException();
        }
        $scooterReservation->getScooter()->setStatus(Scooter::STATUS_AVAILABLE);
        $scooterReservation->setActive(false);
    }

    public function updateScooterLocation(Scooter $scooter, $longitude, $latitude)
    {
        $scooter
            ->setLongitude($longitude)
            ->setLatitude($latitude)
            ->setLocation(new Point($longitude, $latitude))
            ->setUpdatedAt(new \DateTimeImmutable())
        ;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $uuid;

    /**
     * @var ScooterReservation[]
     * @ORM\OneToMany(targetEntity="ScooterReservation", mappedBy="client")
     */
    private $scooterReservations;

    public function __construct()
    {
        $this->scooterReservations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return ScooterReservation[]|Collection
     */
    public function getScooterReservations(): Collection
    {
        return $this->scooterReservations;
    }

    /**
     * @param ScooterReservation[]|Collection $scooterReservations
     * @return $this
     */
    public function setScooterReservations(Collection $scooterReservations): self
    {
        $this->scooterReservations = $scooterReservations;
        return $this;
    }
}

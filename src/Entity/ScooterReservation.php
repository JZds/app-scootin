<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="scooter_reservations",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(name="client_scooter_uniq", columns={"client_uuid", "scooter_uuid"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Repository\ScooterReservationRepository")
 */
class ScooterReservation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $uuid;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="scooter_reservations")
     * @ORM\JoinColumn(name="client_uuid", referencedColumnName="uuid", nullable=false)
     */
    private $client;

    /**
     * @var Scooter
     * @ORM\ManyToOne(targetEntity="Scooter", inversedBy="scooter_reservations")
     * @ORM\JoinColumn(name="scooter_uuid", referencedColumnName="uuid", nullable=false)
     */
    private $scooter;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function __construct(Client $client, Scooter $scooter, bool $active)
    {
        $this->client = $client;
        $this->scooter = $scooter;
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Scooter
     */
    public function getScooter(): Scooter
    {
        return $this->scooter;
    }

    /**
     * @param Scooter $scooter
     * @return $this
     */
    public function setScooter(Scooter $scooter): self
    {
        $this->scooter = $scooter;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }
}

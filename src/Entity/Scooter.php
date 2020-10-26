<?php

namespace App\Entity;

use CrEOF\Spatial\PHP\Types\Geometry\Point;
use DateTimeInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="scooters",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uuid_status_uniq", columns={"uuid", "status"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Repository\ScooterRepository")
 */
class Scooter
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';

    const STATUSES = [self::STATUS_AVAILABLE, self::STATUS_OCCUPIED];

    /**
     * @ORM\Id
     * @ORM\Column(type="string", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $uuid;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @var string
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @var Point
     * @ORM\Column(type="point", nullable=false)
     */
    private $location;

    /**
     * @var DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var ScooterReservation[]
     * @ORM\OneToMany(targetEntity="ScooterReservation", mappedBy="scooter")
     */
    private $scooterReservations;

    public function __construct()
    {
        $this->updatedAt = new DateTimeImmutable();
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     * @return $this
     */
    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     * @return $this
     */
    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
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

    /**
     * @return Point
     */
    public function getLocation(): Point
    {
        return $this->location;
    }

    /**
     * @param Point $location
     * @return $this
     */
    public function setLocation(Point $location): self
    {
        $this->location = $location;
        return $this;
    }
}

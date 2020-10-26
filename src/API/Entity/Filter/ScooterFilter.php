<?php

namespace App\API\Entity\Filter;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 */
class ScooterFilter extends Filter
{
    /**
     * @var array
     * @Type("array")
     */
    private $points = [];

    /**
     * @var string|null
     * @Type("string")
     */
    private $status;

    /**
     * @return array
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * @param array $points
     * @return $this
     */
    public function setPoints(array $points): self
    {
        $this->points = $points;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return $this
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }
}

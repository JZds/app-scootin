<?php

namespace App\API\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 */
class ScooterReservation
{
    /**
     * @var string
     * @Type("string")
     */
    private $clientUuid;

    /**
     * @return string
     */
    public function getClientUuid(): ?string
    {
        return $this->clientUuid;
    }

    /**
     * @param string $clientUuid
     * @return $this
     */
    public function setClientUuid(string $clientUuid): self
    {
        $this->clientUuid = $clientUuid;
        return $this;
    }
}

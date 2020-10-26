<?php

namespace App\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

class Token
{
    /**
     * @var string
     * @Type("string")
     */
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }
}

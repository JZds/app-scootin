<?php

namespace App\API\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 */
class ErrorResponse
{
    /**
     * @var string
     * @Type("string")
     */
    private $error;

    public function __construct($error = null)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }
}

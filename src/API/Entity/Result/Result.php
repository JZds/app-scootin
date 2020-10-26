<?php

namespace App\API\Entity\Result;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 */
class Result
{
    /**
     * @var array
     * @Type("array")
     */
    private $items;

    /**
     * @var int
     * @Type("int")
     */
    private $total;

    public function __construct(array $items, int $total)
    {
        $this->items = $items;
        $this->total = $total;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return $this
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }
}

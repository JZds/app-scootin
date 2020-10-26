<?php

namespace App\API\Entity\Filter;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 */
class Filter
{
    const LIMIT = 100;

    /**
     * @var int|null
     * @Type("int")
     */
    private $limit;

    /**
     * @var int|null
     * @Type("int")
     */
    private $offset;

    /**
     * @var string|null
     * @Type("string")
     */
    private $sortBy;

    /**
     * @var string|null
     * @Type("string")
     */
    private $orderBy;

    public function __construct()
    {
        $this->orderBy = [];
        $this->limit = self::LIMIT;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return $this
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return $this
     */
    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    /**
     * @param string|null $sortBy
     * @return $this
     */
    public function setSortBy(?string $sortBy): self
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * @param string|null $orderBy
     * @return $this
     */
    public function setOrderBy(?string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }
}

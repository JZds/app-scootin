<?php

namespace App\Repository;

use App\API\Entity\Filter\ScooterFilter;

interface ScooterRepositoryInterface
{
    public function findByScootersFilter(ScooterFilter $scooterFilter): array;

    public function findCountByScootersFilter(ScooterFilter $scooterFilter): int;
}

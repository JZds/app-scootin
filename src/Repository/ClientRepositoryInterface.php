<?php

namespace App\Repository;

use App\Entity\Client;

interface ClientRepositoryInterface
{
    public function findClientByUuid(string $uuid): ?Client;
}

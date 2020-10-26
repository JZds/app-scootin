<?php

namespace App\Transformer;

use App\API\Entity\Scooter as ApiScooter;
use App\Entity\Scooter;

class ScooterTransformer extends AbstractTransformer
{
    /**
     * @param Scooter $internalEntity
     * @return ApiScooter
     */
    public function transformFromEntity($internalEntity)
    {
        return (new ApiScooter())
            ->setUuid($internalEntity->getUuid())
            ->setStatus($internalEntity->getStatus())
            ->setLongitude($internalEntity->getLongitude())
            ->setLatitude($internalEntity->getLatitude())
            ->setUpdatedAt($internalEntity->getUpdatedAt())
        ;
    }
}

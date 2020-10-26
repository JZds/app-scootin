<?php

namespace App\Transformer;

use App\API\Entity\Result\Result;

class AbstractTransformer implements TransformerInterface
{
    public function transformFromEntity($internalEntity)
    {
        throw new \LogicException('Missing from entity transformer method');
    }

    public function transformFromEntityItems(array $internalEntities, int $total): Result
    {
        $transformedItems = [];
        foreach ($internalEntities as $entity) {
            $transformedItems[] = $this->transformFromEntity($entity);
        }
        return new Result($transformedItems, $total);
    }
}

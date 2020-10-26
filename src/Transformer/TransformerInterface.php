<?php

namespace App\Transformer;

interface TransformerInterface
{
    public function transformFromEntity($internalEntity);
}

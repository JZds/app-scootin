<?php

namespace App\API\Constraint;

use App\API\Validator\ScooterFilterValidator;
use Symfony\Component\Validator\Constraint;

class ScooterFilterConstraint extends Constraint
{
    public function validatedBy()
    {
        return ScooterFilterValidator::class;
    }
}

<?php

namespace App\API\Constraint;

use App\API\Validator\ScooterValidator;
use Symfony\Component\Validator\Constraint;

class ScooterConstraint extends Constraint
{
    public function validatedBy()
    {
        return ScooterValidator::class;
    }
}

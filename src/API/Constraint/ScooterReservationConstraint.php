<?php

namespace App\API\Constraint;

use App\API\Validator\ScooterReservationValidator;
use Symfony\Component\Validator\Constraint;

class ScooterReservationConstraint extends Constraint
{
    public function validatedBy()
    {
        return ScooterReservationValidator::class;
    }
}

<?php

namespace App\API\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ScooterReservationValidator extends ConstraintValidator
{
    public function validate($scooterFilter, Constraint $constraint)
    {
        if (!isset($scooterFilter['client_uuid'])) {
            $this->context->buildViolation('Value client_uuid must be provided')->addViolation();
            return;
        }
        if (!is_string($scooterFilter['client_uuid'])) {
            $this->context->buildViolation('Value client_uuid must be string')->addViolation();
            return;
        }
    }
}

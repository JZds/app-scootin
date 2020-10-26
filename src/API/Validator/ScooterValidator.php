<?php

namespace App\API\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ScooterValidator extends ConstraintValidator
{
    public function validate($scooterFilter, Constraint $constraint)
    {
        if (!isset($scooterFilter['longitude'])) {
            $this->context->buildViolation('Value longitude missing')->addViolation();
            return;
        }
        if (!isset($scooterFilter['latitude'])) {
            $this->context->buildViolation('Value latitude missing')->addViolation();
            return;
        }
        $this->validateCoordinate($scooterFilter['longitude'], 'longitude');
        $this->validateCoordinate($scooterFilter['latitude'], 'latitude');
    }

    private function validateCoordinate($coordinate, $name)
    {
        if (!preg_match('#^[-]?\d*\.?\d+$#', $coordinate)) {
            $this->context->buildViolation('Value '. $name . ' must be valid coordinate')->addViolation();
            return;
        }
    }
}

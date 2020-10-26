<?php

namespace App\API\Validator;

use App\Entity\Scooter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ScooterFilterValidator extends ConstraintValidator
{
    public function validate($scooterFilter, Constraint $constraint)
    {
        if (isset($scooterFilter['status'])) {
            if (!is_string($scooterFilter['status'])) {
                $this->context->buildViolation('Value status must be string')->addViolation();
                return;
            }
            if (!preg_match('#^(' . join('|', Scooter::STATUSES) . ')$#', $scooterFilter['status'])) {
                $this->context
                    ->buildViolation('Only available statuses: ' . join(', ', Scooter::STATUSES))
                    ->addViolation()
                ;
                return;
            }
        }

        if (isset($scooterFilter['points'])) {
            if (!is_array($scooterFilter['points'])) {
                $this->context->buildViolation('Value points must be an array')->addViolation();
                return;
            }
            if (count($scooterFilter['points']) !== 2) {
                $this->context->buildViolation('Only 2 points must be provided')->addViolation();
                return;
            }
            foreach ($scooterFilter['points'] as $point) {
                if (!is_string($point)) {
                    $this->context->buildViolation('Value point must be string')->addViolation();
                    return;
                }
                if (!preg_match('#^(-?\d+\.?\d+?,-?\d+\.?\d+?)$#', $point)) {
                    $this->context->buildViolation('Invalid point parameter')->addViolation();
                    return;
                }
            }
        }
    }
}

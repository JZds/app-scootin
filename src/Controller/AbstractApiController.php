<?php

namespace App\Controller;

use App\API\Constraint\ScooterConstraint;
use App\API\Constraint\ScooterFilterConstraint;
use App\API\Constraint\ScooterReservationConstraint;
use App\API\Entity\Filter\ScooterFilter;
use App\API\Entity\Scooter;
use App\API\Entity\ScooterReservation;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController extends AbstractController
{
    private $serializer;
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param string $class
     * @return object
     */
    protected function deserializeRequest(Request $request, string $class)
    {
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException('Invalid parameters');
        }
        return $this->serializer->deserialize(
            json_encode($this->validate(json_decode($request->getContent(), true), $class)),
            $class,
            'json'
        );
    }

    /**
     * @param Request $request
     * @param string $class
     * @return mixed
     */
    protected function deserializeQueryRequest(Request $request, string $class)
    {
        return $this->serializer
            ->deserialize(json_encode($this->validate($request->query->all(), $class), true), $class, 'json')
        ;
    }

    protected function validate(array $parameters, string $class): array
    {
        $errors = $this->validator->validate($parameters, $this->getConstraintByClass($class));
        if ($errors->count() > 0) {
            throw new BadRequestHttpException($errors[0]->getMessage());
        }
        return $parameters;
    }

    private function getConstraintByClass(string $class): Constraint
    {
        $constraints = [
            ScooterFilter::class => new ScooterFilterConstraint(),
            Scooter::class => new ScooterConstraint(),
            ScooterReservation::class => new ScooterReservationConstraint(),
        ];

        return $constraints[$class];
    }
}

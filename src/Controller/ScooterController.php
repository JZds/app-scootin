<?php

namespace App\Controller;

use App\API\Entity\Filter\ScooterFilter;
use App\API\Entity\Scooter as ApiScooter;
use App\Entity\Scooter;
use App\Manager\ScooterManager;
use App\Repository\ScooterRepositoryInterface;
use App\Transformer\ScooterTransformer;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ScooterController extends AbstractApiController
{
    private $entityManager;
    private $scooterRepository;
    private $scooterTransformer;
    private $scooterManager;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ScooterRepositoryInterface $scooterRepository,
        ScooterTransformer $scooterTransformer,
        ValidatorInterface $validator,
        ScooterManager $scooterManager
    ) {
        parent::__construct($serializer, $validator);
        $this->entityManager = $entityManager;
        $this->scooterRepository = $scooterRepository;
        $this->scooterTransformer = $scooterTransformer;
        $this->scooterManager = $scooterManager;
    }

    /**
     * @Route("/api/v1/scooters", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getScooters(Request $request): Response
    {
        $scooterFilter = $this->deserializeQueryRequest($request, ScooterFilter::class);
        return $this->json(
            $this->scooterTransformer->transformFromEntityItems(
                $this->scooterRepository->findByScootersFilter($scooterFilter),
                $this->scooterRepository->findCountByScootersFilter($scooterFilter)
            )
        );
    }

    /**
     * @Route("/api/v1/scooters/{uuid}", methods={"PUT"})
     * @param string $uuid
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateScooter($uuid, Request $request)
    {
        /** @var ApiScooter $apiScooter */
        $apiScooter = $this->deserializeRequest($request, ApiScooter::class);

        /** @var Scooter $scooter */
        $scooter = $this->scooterRepository->findOneBy(['uuid' => $uuid]);
        if ($scooter === null) {
            throw new NotFoundHttpException('Resource not found');
        }
        $this->scooterManager->updateScooterLocation($scooter, $apiScooter->getLongitude(), $apiScooter->getLatitude());
        $this->entityManager->flush();

        return $this->json($this->scooterTransformer->transformFromEntity($scooter));
    }
}

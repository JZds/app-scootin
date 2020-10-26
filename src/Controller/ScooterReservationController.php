<?php

namespace App\Controller;

use App\API\Entity\ScooterReservation as ApiScooterReservation;
use App\Entity\Client;
use App\Entity\Scooter;
use App\Entity\ScooterReservation;
use App\Exception\ScooterAvailableException;
use App\Exception\ScooterUnavailableException;
use App\Repository\ClientRepositoryInterface;
use App\Repository\ScooterRepositoryInterface;
use App\Manager\ScooterManager;
use App\Repository\ScooterReservationRepository;
use App\Transformer\ScooterTransformer;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ScooterReservationController extends AbstractApiController
{
    private $entityManager;
    private $scooterTransformer;
    private $scooterRepository;
    private $clientRepository;
    private $scooterReservationRepository;
    private $scooterManager;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        ScooterTransformer $scooterTransformer,
        ScooterRepositoryInterface $scooterRepository,
        ClientRepositoryInterface $clientRepository,
        ScooterReservationRepository $scooterReservationRepository,
        ScooterManager $scooterManager
    ) {
        parent::__construct($serializer, $validator);
        $this->entityManager = $entityManager;
        $this->scooterTransformer = $scooterTransformer;
        $this->scooterRepository = $scooterRepository;
        $this->clientRepository = $clientRepository;
        $this->scooterReservationRepository = $scooterReservationRepository;
        $this->scooterManager = $scooterManager;
    }

    /**
     * @Route("/api/v1/scooters/{uuid}/reservations", methods={"POST"})
     * @param string $uuid
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function reserveScooter(string $uuid, Request $request): JsonResponse
    {
        /**
         * @var Scooter $scooter
         * @var Client $client
         */
        list($scooter, $client) = $this->getAndValidateReservationParameters($uuid, $request);

        try {
            $this->scooterManager->reserveScooter($scooter, $client);
        } catch (ScooterUnavailableException $scooterUnavailableException) {
            throw new ConflictHttpException('Scooter unavailable');
        }

        return $this->updateAndGetScooter($scooter);
    }

    /**
     * @Route("/api/v1/scooters/{uuid}/reservations/revoke", methods={"PUT"})
     * @param string $uuid
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function revokeScooterReservation(string $uuid, Request $request): JsonResponse
    {
        /**
         * @var Scooter $scooter
         * @var Client $client
         */
        list($scooter, $client) = $this->getAndValidateReservationParameters($uuid, $request);

        /** @var ScooterReservation $scooterReservation */
        $scooterReservation = $this->scooterReservationRepository->findOneBy(
            ['client' => $client->getUuid(), 'scooter' => $scooter->getUuid()]
        );
        if ($scooterReservation === null) {
            throw new NotFoundHttpException('Reservation not found');
        }

        try {
            $this->scooterManager->revokeScooterReservation($scooterReservation);
        } catch (ScooterAvailableException $scooterUnavailableException) {
            throw new ConflictHttpException('Scooter is available');
        }

        return $this->updateAndGetScooter($scooter);
    }

    private function getAndValidateReservationParameters(string $uuid, Request $request): array
    {
        /** @var ApiScooterReservation $scooterReservation */
        $scooterReservation = $this->deserializeRequest($request, ApiScooterReservation::class);

        /** @var Scooter $scooter */
        $scooter = $this->scooterRepository->findOneBy(['uuid' => $uuid]);
        $client = $this->clientRepository->findOneBy(['uuid' => $scooterReservation->getClientUuid()]);
        if ($scooter === null) {
            throw new NotFoundHttpException('Scooter not found');
        }

        if ($client === null) {
            throw new NotFoundHttpException('Client not found');
        }
        return [$scooter, $client];
    }

    private function updateAndGetScooter(Scooter $scooter): JsonResponse
    {
        $this->entityManager->flush();
        return $this->json($this->scooterTransformer->transformFromEntity($scooter));
    }
}

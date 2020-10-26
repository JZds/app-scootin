<?php

namespace App\Command;

use App\Entity\Client;
use App\Entity\Scooter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BotCommand extends Command
{
    private $entityManager;
    private $httpClient;
    private $endpointUrl;

    public function __construct(
        $name = 'bot:execute',
        string $endpointUrl,
        EntityManagerInterface $entityManager,
        HttpClientInterface $httpClient
    ) {
        parent::__construct($name);
        $this->endpointUrl = $endpointUrl;
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
   {
       /** @var Client $client */
       $client = $this->entityManager->createQueryBuilder()
           ->select('c')
           ->from('App\Entity\Client', 'c')
           ->leftJoin('App\Entity\ScooterReservation', 'sr', 'WITH', 'sr.client = c.uuid')
           ->where('sr.uuid IS NULL')
           ->orWhere('sr.active = false')
           ->setMaxResults(1)
           ->getQuery()
           ->getOneOrNullResult()
       ;
       $output->writeln('Client: ' . $client->getUuid(). PHP_EOL . 'Searching for scooter...');

       $headers = ['Authorization' => 'Bearer ' . $client->getUuid(), 'content-type' => 'application/json'];
       $scooter = $this->httpClient->request(
           'GET',
           $this->endpointUrl . '/api/v1/scooters',
           ['headers' => $headers, 'query' => ['status' => Scooter::STATUS_AVAILABLE, 'limit' => 1]]
       )->toArray()['items'][0];

       $output->writeln('Got scooter: ' . $scooter['uuid'] . PHP_EOL . 'Reserving...');
       $this->httpClient->request(
           'POST',
           $this->endpointUrl . '/api/v1/scooters/' . $scooter['uuid'] . '/reservations',
           ['headers' => $headers, 'body' => json_encode(['client_uuid' => $client->getUuid()])]
       );

       $longitude = $scooter['longitude'];
       $latitude = $scooter['latitude'];
       $scooterUuid = $scooter['uuid'];

       $output->writeln(
           'Updating scooter location: client('
           . $client->getUuid() . ') scooter('
           . $scooter['uuid'] . ') '
           . 'longitude(' . $longitude
           . ') latitude' . $latitude
       );
       for ($i = 0; $i < 5; $i++) {
           $latitude = $latitude + '0.125';
           $this->httpClient->request(
               'PUT',
               $this->endpointUrl . '/api/v1/scooters/' . $scooterUuid,
               ['headers' => $headers, 'body' => json_encode(['longitude' => $longitude, 'latitude' => $latitude])]
           );
           $output->writeln('Scooter location update: longitude(' . $longitude . ') latitude(' . $latitude . ')');
           sleep(3);
       }
       $output->writeln('Canceling scooter reservation: longitude(' . $longitude . ') latitude(' . $latitude . ')');
       $this->httpClient->request(
           'PUT',
           $this->endpointUrl . '/api/v1/scooters/' . $scooter['uuid'] . '/reservations/revoke',
           ['headers' => $headers, 'body' => json_encode(['client_uuid' => $client->getUuid()])]
       );
       $output->writeln('Resting');
       sleep(5);
       $output->writeln('Finished');

       return Command::SUCCESS;
   }
}

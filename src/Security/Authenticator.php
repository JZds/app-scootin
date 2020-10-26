<?php

namespace App\Security;

use App\Entity\Token;
use App\Repository\ClientRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Authenticator implements AuthenticatorInterface
{
    private $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param Token $token
     */
    public function authenticate(Token $token)
    {
        $client = $this->clientRepository->findClientByUuid($token->getUuid());
        if ($client === null) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }
}

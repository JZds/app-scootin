<?php

namespace App\EventListener;

use App\Entity\Token;
use App\Security\AuthenticatorInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiRequestEventListener
{
    private $authenticator;

    public function __construct(AuthenticatorInterface $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        if (mb_strpos($event->getRequest()->getRequestUri(), '/api') === 0) {
            $token = $event->getRequest()->headers->get('Authorization');
            if ($token === null || mb_substr($token, 0, 7) !== 'Bearer ') {
                throw new UnauthorizedHttpException('Basic realm="Scootin"', 'Unauthorized');
            }

            $this->authenticator->authenticate(new Token(mb_substr($token, 7)));
        }
    }

    public static function getSubscribedEvents()
    {
        return [RequestEvent::class => 'onKernelRequest'];
    }
}

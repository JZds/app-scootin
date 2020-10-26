<?php

namespace App\EventListener;

use App\API\Entity\ErrorResponse;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpExceptionEventListener
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ExceptionEvent $event
     * @throws \Throwable
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HttpException) {
            $event->setResponse(
                new JsonResponse(
                    $this->serializer->serialize(new ErrorResponse($exception->getMessage()), 'json'),
                    $exception->getStatusCode(),
                    ['Content-Type', 'application/json'],
                    true
                )
            );
        } else {
            $event->setResponse(
                new JsonResponse(
                    $this->serializer->serialize(new ErrorResponse('Internal server error'), 'json'),
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['Content-Type', 'application/json'],
                    true
                )
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return [RequestEvent::class => 'onKernelException'];
    }
}

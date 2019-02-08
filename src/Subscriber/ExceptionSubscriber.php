<?php declare(strict_types=1);

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\AppException;

/**
 * Class ExceptionSubscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => [['onThrowable']]];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onThrowable(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();
        $errors = [];
        if ($exception instanceof AppException) {
            /**
             * @var AppException $exception
             */
            $errors = $exception->getErrors();
        }

        $data = [
            'status' => 'error',
            'data' => [
                'errors'  => $errors,
                'message' => $exception->getMessage(),
            ],
            'code' => $exception->getCode() !== 0
                ? $exception->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR,
        ];

        if (getenv('APP_ENV') === 'dev') {
            $data['trace'] = $exception->getTrace();
        }

        $response = new JsonResponse($data, Response::HTTP_OK);

        $event->setResponse($response);
        $event->allowCustomResponseCode();
        $event->stopPropagation();
    }
}

<?php
declare(strict_types = 1); 
namespace App\EventSubscriber;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class ExceptionsSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
            $response = new JsonResponse();

            $exception = $event->getThrowable();

            switch ($exception) {
                case $exception instanceof NotFoundHttpException:
                    $response->setStatusCode(Response::HTTP_NOT_FOUND);
                    $response->setData([
                        'code' => Response::HTTP_NOT_FOUND,
                        'message' => 'Resource not found'
                    ]);
                    break;
                case $exception instanceof AccessDeniedException:
                    $response->setStatusCode(Response::HTTP_FORBIDDEN);
                    $response->setData([
                        'code' => Response::HTTP_FORBIDDEN,
                        'message' => 'Forbiden'
                    ]);
                    break;
                case $exception instanceof InvalidArgumentException:
                    $response->setStatusCode($exception->getCode());
                    $response->setData($exception->getMessage());
                    break;

                default:
                    $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                    $response->setData([
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => 'Server error'
                    ]);
                break;
            }

            $event->setResponse($response);
    }

    //Symfony events
    public static function getSubscribedEvents(): array
    {
        return [
            //KernelEvents::EXCEPTION => ['onKernelException', 10]
        ];    
    }    
}


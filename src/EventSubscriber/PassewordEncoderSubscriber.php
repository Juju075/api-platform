<?php

namespace App\EventSubscriber;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Client;

use http\Client\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class PassewordEncoderSubscriber implements EventSubscriberInterface
{
private $encoder;

    //besoin service encoder

    /**
     * Encoder service
     *
     * @param UserPasswordHasherInterface $encoder
     */
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Encode the password used to authenticate the client.
     *
     * @param ViewEvent $event
     * @return void
     */
    public function encodePassword(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult(); //on capte le resultat du controller
        $method = $event->getRequest()->getMethod();

        if (
            $controllerResult instanceof User && in_array
                (
                    $method,
                    [
                        Request::METHOD_POST,Request::METHOD_PUT,Request::METHOD_PATCH
                    ]
                )
        )
        {
           //$hash = $this->encoder->encodePassword($controllerResult, $controllerResult->getPassword()); //Returns the password used to authenticate the user.
           $hash = $this->encoder->hashPassword($controllerResult, $controllerResult->getPassword()); //Returns the password used to authenticate the user.
           $controllerResult->setPassword($hash);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE],
        ];
        
    }
}

<?php

namespace App\EventSubscriber;
use App\Entity\Client;

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
        //limite uniquement a des requete post
        $controllerResult = $event->getControllerResult; //on capte le resultat du controller  Notice: Undefined property:
       
        $method = $event->getRequest()->getMethod(); //method 

        //Important vas cibler uniquement un POST sur l'entité Client.    
        if ($controllerResult instanceof Client && $method === "POST") {
           //$hash = $this->encoder->encodePassword($controllerResult, $controllerResult->getPassword()); //Returns the password used to authenticate the user.
           $hash = $this->encoder->hashPassword($controllerResult, $controllerResult->getPassword()); //Returns the password used to authenticate the user.
           $controllerResult->setPassword($hash);
        }
    }

    //Apiplatform events    
    public static function getSubscribedEvents()
    {
        return [
            //KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE],
        ];
        
    }
}

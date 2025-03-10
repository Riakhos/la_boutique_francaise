<?php

namespace App\EventSubscriber;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginSubscriber implements EventSubscriberInterface
{
	private $em;
	private $security;
	
	public function __construct(Security $security, EntityManagerInterface $em)
	{
		$this->em = $em;
		$this->security = $security;
	}
	
	public function onLogin()
	{
		/** @var \App\Entity\User $user */
		$user =  $this->security->getUser();
		$user->setLastLoginAt(new DateTime());
		$this->em->flush();
	}
	
	public static function getSubscribedEvents(): array
    {
        // return the subscribed events, their methods and priorities
        return [
            LoginSuccessEvent::class => 'onLogin'
        ];
    }
}
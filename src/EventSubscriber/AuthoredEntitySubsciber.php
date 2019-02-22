<?php

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthoredEntitySubsciber implements EventSubscriberInterface
{
	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	/**
	 * AuthoredEntitySubsciber constructor.
	 *
	 * @param TokenStorageInterface $tokenStorage
	 */
	public function __construct( TokenStorageInterface $tokenStorage ) {
		$this->tokenStorage = $tokenStorage;
	}


	public static function getSubscribedEvents() {
		return [
			KernelEvents::VIEW=> ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
		];
	}

	public function getAuthenticatedUser(GetResponseForControllerResultEvent $event){
		$entity = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();
		/**
		 * @var UserInterface $author
		 */
		$author = $this->tokenStorage->getToken()->getUser();

		if ((!$entity instanceof Post && !$entity instanceof Comment) || Request::METHOD_POST !== $method) {
			return;
		}

		$entity->setAuthor($author);
	}

}
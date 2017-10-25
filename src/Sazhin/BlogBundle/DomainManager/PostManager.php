<?php


namespace Sazhin\BlogBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Entity\User;
use Sazhin\BlogBundle\Event\PostCreatedEvent;
use Sazhin\BlogBundle\PostEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PostManager
{

    private $manager;
    private $dispatcher;

    public function __construct(EntityManager $manager, EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
    }

    public function createPost(Post $post, User $user)
    {

        $post->setUser($user);

        $this->manager->persist($post);

        $this->manager->flush();

        $this->dispatch($post, new PostCreatedEvent(), PostEvents::POST_CREATED);

    }

    public function updatePost(Post $post, User $user)
    {

    }

    private function dispatch(Post $post, Event $event, string $eventName)
    {

        if ($event instanceof Event) {

            $eventDispatcher = $this->dispatcher;

            $event->setPost($post);

            $eventDispatcher->dispatch($eventName, $event);

        }

    }
}
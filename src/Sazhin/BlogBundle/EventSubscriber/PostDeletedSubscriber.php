<?php

namespace Sazhin\BlogBundle\EventSubscriber;


use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Event\PostCreatedEvent;
use Psr\Log\LoggerInterface;
use Sazhin\BlogBundle\Event\PostDeletedEvent;
use Sazhin\BlogBundle\Event\PostUpdatedEvent;


class PostDeletedSubscriber
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    public function postDeleted(PostDeletedEvent $post)
    {

        /** @var Post $post */
        $post = $post->getPost();

        $title = $post->getTitle();

        $userName = $post->getUser()->getUsername();

        $link = $_SERVER['HTTP_HOST'] . '/' . $post->getId();

        $loggerString = 'Post DELETE detected! ' . $title . ' ' . $userName . ' ' . $link;

        $this->logger->warning($loggerString);

    }

}
<?php

namespace Sazhin\BlogBundle\EventSubscriber;


use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Event\PostCreatedEvent;
use Psr\Log\LoggerInterface;
use Sazhin\BlogBundle\Event\PostUpdatedEvent;


class PostUpdatedSubscriber
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    public function postUpdated(PostUpdatedEvent $post)
    {

        /** @var Post $post */
        $post = $post->getPost();

        $title = $post->getTitle();

        $userName = $post->getUser()->getUsername();

        $link = $_SERVER['HTTP_HOST'] . '/' . $post->getId();

        $loggerString = 'Post UPDATE detected! ' . $title . ' ' . $userName . ' ' . $link;

        $this->logger->info($loggerString);

    }

}
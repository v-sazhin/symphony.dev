<?php

namespace Sazhin\BlogBundle\EventSubscriber;


use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Event\PostCreatedEvent;
use Psr\Log\LoggerInterface;

/**
 * Class PostCreatedSubscriber
 * @package Sazhin\BlogBundle\EventSubscriber
 * @author v-sazhin
 */
class PostCreatedSubscriber
{
    private $logger;

    /**
     * PostCreatedSubscriber constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PostCreatedEvent $post
     */
    public function newPostCreated(PostCreatedEvent $post)
    {

        /** @var Post $post */
        $post = $post->getPost();

        $title = $post->getTitle();

        $userName = $post->getUser()->getUsername();

        $link = $_SERVER['HTTP_HOST'] . '/' . $post->getId();

        $loggerString = 'New post detected! ' . $title . ' ' . $userName . ' ' . $link;

        $this->logger->info($loggerString);

    }

}
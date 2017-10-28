<?php

namespace Sazhin\BlogBundle\EventSubscriber;


use Sazhin\BlogBundle\Entity\Comment;
use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Entity\User;
use Sazhin\BlogBundle\Event\CommentCreatedEvent;
use Sazhin\BlogBundle\Event\PostCreatedEvent;
use Psr\Log\LoggerInterface;

/**
 * Class PostCreatedSubscriber
 * @package Sazhin\BlogBundle\EventSubscriber
 * @author v-sazhin
 */
class CommentCreatedSubscriber
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
     * @param CommentCreatedEvent $comment
     *
     * @internal param PostCreatedEvent $post
     */
    public function newCommentCreated(CommentCreatedEvent $comment)
    {

        /** @var Comment $comment */
        $comment = $comment->getComment();

        /** @var User $commentator */
        $commentator = $comment->getUser();

        $loggerString = 'Добавлен новый комментарий от пользователя '. $commentator->getUsername() .
            ' :'.$comment->getComment();

        $this->logger->info($loggerString);

    }

}
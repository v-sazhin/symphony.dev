<?php


namespace Sazhin\BlogBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use Sazhin\BlogBundle\CommentEvents;
use Sazhin\BlogBundle\Entity\Comment;
use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Entity\User;
use Sazhin\BlogBundle\Event\CommentCreatedEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;


class CommentManager
{

    private $manager;
    private $dispatcher;

    public function __construct(EntityManager $manager, EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
    }

    public function createComment(Post $post, Request $request, Comment $comment, User $user)
    {

        $comment->setUser($user);

        $comment->setPost($post);

        if ($request->get('parent')) {

            $parent = $this->manager->getRepository('SazhinBlogBundle:Comment')
                ->find($request->get('parent'));

            if (!$parent) {

                return false;

            }

            $comment->setParent($parent);

        }

        $this->manager->persist($comment);

        $this->manager->flush();

        $this->dispatch($comment, new CommentCreatedEvent(), CommentEvents::COMMENT_CREATED);

        return true;

    }

    private function dispatch(Comment $comment, Event $event, string $eventName)
    {

        if ($event instanceof Event) {

            $eventDispatcher = $this->dispatcher;

            $event->setComment($comment);

            $eventDispatcher->dispatch($eventName, $event);

        }

    }
}
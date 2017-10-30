<?php


namespace Sazhin\BlogBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use LogicException;
use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Entity\User;
use Sazhin\BlogBundle\Event\PostCreatedEvent;
use Sazhin\BlogBundle\Event\PostDeletedEvent;
use Sazhin\BlogBundle\Event\PostUpdatedEvent;
use Sazhin\BlogBundle\PostEvents;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Exception\ExceptionInterface;

class PostManager implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    private $manager;
    private $dispatcher;
    private $workflow;

    public function __construct(EntityManager $manager, EventDispatcherInterface $dispatcher, ContainerInterface $container)
    {
        $this->setContainer($container);
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->workflow = $this->container->get('workflow.blog_publishing');
    }

    public function createPost(Post $post, User $user)
    {

        try {

            $this->workflow->apply($post, 'to_review');

        } catch (LogicException $e) {

            $this->container->get('session')->getFlashBag()->add('danger', $e->getMessage());

        }

        $post->setUser($user);

        $this->manager->persist($post);

        $this->manager->flush();

        $this->dispatch($post, new PostCreatedEvent(), PostEvents::POST_CREATED);

        return true;

    }

    public function editPost(Post $post)
    {

        $this->manager->persist($post);

        $this->manager->flush();

        $this->dispatch($post, new PostUpdatedEvent(), PostEvents::POST_UPDATED);

        return true;

    }

    public function deletePost(Post $post)
    {

        $this->manager->remove($post);

        $this->manager->flush();

        $this->dispatch($post,new PostDeletedEvent(), PostEvents::POST_DELETED);

        return true;

    }

    public function applyTransition(Post $post, Request $request)
    {
        $transition = $request->request->get('transition');
        try {
            $this->workflow
                ->apply($post, $transition);
            $this->manager->flush();
        } catch (ExceptionInterface $e) {
            $this->container->get('session')->getFlashBag()->add('danger', $e->getMessage());
            //return false;
        }
        return true;
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
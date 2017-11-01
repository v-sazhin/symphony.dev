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
use Sazhin\BlogBundle\Service\FileUploader;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Workflow\Exception\ExceptionInterface;
use Symfony\Component\Workflow\Workflow;

class PostManager
{

    private $manager;
    private $dispatcher;
    private $workflow;
    private $session;
    private $fileUploader;

    public function __construct(
        EntityManager $manager,
        EventDispatcherInterface $dispatcher,
        Session $session,
        Workflow $workflow,
        FileUploader $fileUploader)
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->workflow = $workflow;
        $this->session = $session;
        $this->fileUploader = $fileUploader;
    }

    public function createPost(Post $post, User $user)
    {

        try {

            $this->workflow->apply($post, 'to_review');

        } catch (LogicException $e) {

            $this->session->getFlashBag()->add('danger', $e->getMessage());

        }
        /*$fileName = $this->fileUploader->upload($post->getImage());

        $post->setImage($fileName)*/;

        $post->setUser($user);

        $this->manager->persist($post);

        $this->manager->flush();

        $this->dispatch($post, new PostCreatedEvent(), PostEvents::POST_CREATED);


        $this->session->getFlashBag()->add('success', 'flash.post.created');

        return true;

    }

    public function editPost(Post $post)
    {
       // dump($post);die();
        $this->manager->persist($post);

        $this->manager->flush();

        $this->dispatch($post, new PostUpdatedEvent(), PostEvents::POST_UPDATED);

        $this->session->getFlashBag()->add('success', 'flash.post.updated');

        return true;

    }

    public function deletePost(Post $post)
    {

        $this->manager->remove($post);

        $this->manager->flush();

        $this->dispatch($post, new PostDeletedEvent(), PostEvents::POST_DELETED);

        $this->session->getFlashBag()->add('danger', 'flash.post.deleted');

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
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        }

        $this->session->getFlashBag()->add('success', 'flash.post.workflow.changed.'. $transition);

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
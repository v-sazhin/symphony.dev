<?php

namespace Sazhin\BlogBundle\Form\Handler;

use Sazhin\BlogBundle\DomainManager\CommentManager;
use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateCommentFormHandler
{

    private $manager;

    public function __construct(CommentManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(FormInterface $form, Request $request, User $user, Post $post)
    {

        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->manager->createComment($post, $request, $form->getData(), $user)){

                return true;
            }
            return false;
        }

        return false;
    }
}
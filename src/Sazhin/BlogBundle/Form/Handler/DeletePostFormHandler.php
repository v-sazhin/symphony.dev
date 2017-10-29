<?php

namespace Sazhin\BlogBundle\Form\Handler;


use Sazhin\BlogBundle\DomainManager\PostManager;
use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class DeletePostFormHandler
{

    private $manager;

    public function __construct(PostManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(FormInterface $form, Request $request, Post $post)
    {

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dump($post);die;
            $this->manager->deletePost($post);

            return true;
        }

        return false;
    }
}
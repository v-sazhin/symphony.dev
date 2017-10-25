<?php

namespace Sazhin\BlogBundle\Form\Handler;


use Sazhin\BlogBundle\DomainManager\PostManager;
use Sazhin\BlogBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CreatePostFormHandler
{

    private $manager;

    public function __construct(PostManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(FormInterface $form, Request $request, User $user)
    {
        //dump($user);die;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->createPost($form->getData(), $user);

            return true;
        }

        return false;
    }
}
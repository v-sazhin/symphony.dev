<?php

namespace Sazhin\BlogBundle\Form\Handler;


use Sazhin\BlogBundle\DomainManager\PostManager;
use Sazhin\BlogBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class EditPostFormHandler
{

    private $manager;

    public function __construct(PostManager $manager)
    {

        $this->manager = $manager;

    }

    public function handle(FormInterface $form, Request $request)
    {

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->editPost($form->getData());

            return true;
        }

        return false;
    }
}
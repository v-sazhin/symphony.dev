<?php

namespace Sazhin\BlogBundle\Controller;

use Sazhin\BlogBundle\Entity\Category;
use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Event\PostCreatedEvent;
use Sazhin\BlogBundle\Event\PostUpdatedEvent;
use Sazhin\BlogBundle\PostEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM SazhinBlogBundle:Post a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('post/index.html.twig', array(
            'posts' => $pagination,
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Post $post)
    {
        return $this->render('post/show.html.twig', array(
            'post' => $post,
        ));
    }


}

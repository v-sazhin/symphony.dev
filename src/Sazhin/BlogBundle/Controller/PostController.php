<?php

namespace Sazhin\BlogBundle\Controller;

use Sazhin\BlogBundle\Entity\Comment;
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

        $query = $this->getDoctrine()->getRepository('SazhinBlogBundle:Post')
            ->getPublishedPosts();

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10, ['defaultSortFieldName' => 'id', 'defaultSortDirection' => 'desc']
        );

        return $this->render('post/index.html.twig', array(
            'posts' => $pagination,
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @param $slug
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getRepository('SazhinBlogBundle:Post');

        $post = $em->findOneBy(['slug' => $slug]);

        if (!$post) {

            throw $this->createNotFoundException('Запрошенная страница не существует');

        }

        $commentRepo = $this->getDoctrine()->getRepository('SazhinBlogBundle:Comment');

        $comment = new Comment();

        $commentForm = $this->createForm('Sazhin\BlogBundle\Form\CommentType', $comment);

        $commentForm->handleRequest($request);

        $commentFormHandler = $this->get('sazhin.post.create_comment_form_handler');

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($commentFormHandler->handle(
                $commentForm,
                $request,
                $this->getUser(),
                $post)) {
                return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
            }
        }

        $comments = $commentRepo->getCommentsForPost($post->getId());

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'comments' => $comments,
            'commentForm' => $commentForm->createView()
        ));
    }

}

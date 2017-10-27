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
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM SazhinBlogBundle:Post a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10, ['defaultSortFieldName' => 'a.createdAt', 'defaultSortDirection' => 'desc']
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
        //dump($request->request->all());die;
        $em = $this->getDoctrine()->getRepository('SazhinBlogBundle:Post');
        $post = $em->findOneBy(['slug' => $slug]);
        if (!$post) {
            throw $this->createNotFoundException('Запрошенная страница не существует');
        }
        $commentRepo = $this->getDoctrine()->getRepository('SazhinBlogBundle:Comment');

        $comment = new Comment();

        $commentForm = $this->createForm('Sazhin\BlogBundle\Form\CommentType', $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $comment->setPost($post);
            $comment->setUser($this->getUser());

            if ($request->get('parent')){
                $parent = $commentRepo->find($request->get('parent'));
                if (!$parent){
                    $this->createNotFoundException('Комментарий не определен');
                }
                $comment->setParent($parent);
            }

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_show', ['slug'=>$post->getSlug()]);
        }

        $comments= $commentRepo->getCommentsForPost($post->getId());

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'comments'=>$comments,
            'commentForm' => $commentForm->createView()
        ));
    }


}

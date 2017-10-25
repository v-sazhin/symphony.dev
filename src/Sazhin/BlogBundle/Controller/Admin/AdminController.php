<?php

namespace Sazhin\BlogBundle\Controller\Admin;

use Sazhin\BlogBundle\Entity\Post;
use Sazhin\BlogBundle\Event\PostUpdatedEvent;
use Sazhin\BlogBundle\PostEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController
 * @package Sazhin\BlogBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
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
            50, ['defaultSortFieldName' => 'a.createdAt', 'defaultSortDirection' => 'desc']
        );

        return $this->render('/admin/index.html.twig', array(
            'posts' => $pagination,
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm('Sazhin\BlogBundle\Form\PostType', $post);

        $formHandler = $this->get('sazhin.post.create_post_form_handler');

        if ($formHandler->handle($form, $request, $this->getUser())) {

            return $this->redirectToRoute('admin_index');

        }

        return $this->render('/admin/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));

    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @param Request $request
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Post $post)
    {

        $deleteForm = $this->createDeleteForm($post);

        $editForm = $this->createForm('Sazhin\BlogBundle\Form\PostType', $post);

        $formHandler = $this->get('sazhin.post.update_post_form_handler');

        if ($formHandler->handle($editForm, $request)) {

            return $this->redirectToRoute('admin_edit', ['id' => $post->getId()]);

        }

        return $this->render(':admin:edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @param Request $request
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('admin_index');
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

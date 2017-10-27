<?php

namespace Sazhin\BlogBundle\Controller;

use Sazhin\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    public function newAction($post_id,Request $request)
    {
        /*$comment = new Comment();
        $form = $this->createForm('Sazhin\BlogBundle\Form\CommentType', $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $post = $this->getDoctrine()->getRepository('SazhinBlogBundle:Post')
                ->find((int)$post_id);
            if(!$post){
                $this->createNotFoundException('Запись не определена...');
            }
            $comment->setPost($post);
            $comment->setUser($this->getUser());

            if ($request->get('parent')){
                $parrent = $this->getDoctrine()->getRepository('SazhinBlogBundle:Comment')
                    ->find($request->get('parent'));
                if (!$parrent){
                    $this->createNotFoundException('Комментарий не определен');
                }
                $comment->setParent($parrent);

                $em->persist($comment);
                $em->flush();
            }

            return $this->redirectToRoute('post_show', $post->getId());
        }

        return $this->render('/admin/category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));*/
    }
}

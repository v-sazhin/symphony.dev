<?php

namespace Sazhin\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @param $slug string
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug, Request $request)
    {
        $paginator = $this->get('knp_paginator');

        $category = $this->getDoctrine()->getRepository('SazhinBlogBundle:Category')
            ->findOneBy(['slug' => $slug]);

        $query = $category->getPosts();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10/*, ['defaultSortFieldName' => 'a.createdAt', 'defaultSortDirection' => 'desc']*/
        );

        return $this->render(':post/category:index.html.twig', array(
            'posts' => $pagination,
            'category' => $category
        ));
    }
}

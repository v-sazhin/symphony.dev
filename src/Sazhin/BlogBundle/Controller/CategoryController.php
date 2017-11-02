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

        $category = $this->getDoctrine()->getRepository('SazhinBlogBundle:Category')
            ->findOneBy(['slug' => $slug]);

        $posts = $category->getPosts()->toArray();
        //dump($posts);die();

        $paginator = $this->get('sazhin_blog.service.pagination');
        $pagination = $paginator->paginate($posts, $request->query->getInt('page', 1));

        return $this->render(':post/category:index.html.twig', array(
            'posts' => $pagination,
            'category' => $category
        ));
    }
}

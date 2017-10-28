<?php

namespace Sazhin\BlogBundle\Event;

use Sazhin\BlogBundle\Entity\Post;
use Symfony\Component\EventDispatcher\Event;

class PostCreatedEvent extends Event
{

    private $post;

    function setPost(Post $post)
    {
        $this->post = $post;
    }

    public function getPost()
    {
        return $this->post;
    }

}
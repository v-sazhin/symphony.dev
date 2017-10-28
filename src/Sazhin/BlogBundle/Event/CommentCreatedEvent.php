<?php

namespace Sazhin\BlogBundle\Event;

use Sazhin\BlogBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\Event;

class CommentCreatedEvent extends Event
{

    private $comment;

    function setComment(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

}
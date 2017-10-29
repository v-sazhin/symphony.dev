<?php

namespace Sazhin\BlogBundle;


class PostEvents
{
    const POST_CREATED = 'sazhin.post.post_created_event';
    const POST_DELETED = 'sazhin.post.post_deleted_event';
    const POST_UPDATED = 'sazhin.post.post_updated_event';

    const COMMENT_CREATED = 'sazhin.post.comment_created_event';

    public static function defined(string $constName)
    {
        return defined('static::' . $constName);
    }
}
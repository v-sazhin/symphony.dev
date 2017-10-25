<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 23.10.2017
 * Time: 11:21
 */

namespace Sazhin\BlogBundle;


class PostEvents
{
    const POST_CREATED = 'sazhin.post.post_created_event';
    const POST_DELETER = 'sazhin.post.post_deleted_event';
    const POST_UPDATED = 'sazhin.post.post_updated_event';

    public static function defined(string $constName)
    {
        return defined('static::'.$constName);
    }
}
<?php

namespace srag\CommentsUI\Utils;

use srag\CommentsUI\Comment\Repository;
use srag\CommentsUI\Comment\RepositoryInterface;
use srag\CommentsUI\UI\UI;
use srag\CommentsUI\UI\UIInterface;

/**
 * Trait CommentsUITrait
 *
 * @package srag\CommentsUI\Utils
 */
trait CommentsUITrait
{

    /**
     * @return RepositoryInterface
     */
    protected static function comments() : RepositoryInterface
    {
        return Repository::getInstance();
    }


    /**
     * @return UIInterface
     */
    protected static function commentsUI() : UIInterface
    {
        return new UI();
    }
}

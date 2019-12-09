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
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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

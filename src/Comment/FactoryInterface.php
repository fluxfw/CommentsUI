<?php

namespace srag\CommentsUI\Comment;

use stdClass;

/**
 * Interface FactoryInterface
 *
 * @package srag\CommentsUI\Comment
 */
interface FactoryInterface
{

    /**
     * @param stdClass $data
     *
     * @return Comment
     */
    public function fromDB(stdClass $data) : Comment;


    /**
     * @return Comment
     */
    public function newInstance() : Comment;
}

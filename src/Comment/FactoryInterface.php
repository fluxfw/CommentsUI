<?php

namespace srag\CommentsUI\Comment;

use stdClass;

/**
 * Interface FactoryInterface
 *
 * @package srag\CommentsUI\Comment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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

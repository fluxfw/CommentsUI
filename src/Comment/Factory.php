<?php

namespace srag\CommentsUI\Comment;

use ilDateTime;
use srag\DIC\DICTrait;
use stdClass;

/**
 * Class Factory
 *
 * @package srag\CommentsUI\Comment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory implements FactoryInterface
{

    use DICTrait;
    /**
     * @var FactoryInterface[]
     */
    protected static $instances = [];


    /**
     * @param string $comment_class
     *
     * @return FactoryInterface
     */
    public static function getInstance(string $comment_class) : FactoryInterface
    {
        if (!isset(self::$instances[$comment_class])) {
            self::$instances[$comment_class] = new self($comment_class);
        }

        return self::$instances[$comment_class];
    }


    /**
     * @var string|Comment
     */
    protected $comment_class;


    /**
     * Factory constructor
     *
     * @param string $comment_class
     */
    private function __construct(string $comment_class)
    {
        $this->comment_class = $comment_class;
    }


    /**
     * @inheritdoc
     */
    public function fromDB(stdClass $data) : Comment
    {
        $comment = $this->newInstance();

        $comment->setId($data->id);
        $comment->setComment($data->comment);
        $comment->setReportObjId($data->report_obj_id);
        $comment->setReportUserId($data->report_user_id);
        $comment->setCreatedTimestamp((new ilDateTime($data->created_timestamp, IL_CAL_DATETIME))->getUnixTime());
        $comment->setCreatedUserId($data->created_user_id);
        $comment->setUpdatedTimestamp((new ilDateTime($data->updated_timestamp, IL_CAL_DATETIME))->getUnixTime());
        $comment->setUpdatedUserId($data->updated_user_id);
        $comment->setIsShared($data->is_shared);
        $comment->setDeleted($data->deleted);

        return $comment;
    }


    /**
     * @inheritdoc
     */
    public function newInstance() : Comment
    {
        $comment = new $this->comment_class();

        return $comment;
    }
}

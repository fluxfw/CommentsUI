<?php

namespace srag\CommentsUI\Comment;

use ActiveRecord;
use arConnector;
use srag\CommentsUI\Utils\CommentsUITrait;
use srag\DIC\DICTrait;
use stdClass;
use Throwable;

/**
 * Class AbstractComment
 *
 * @package srag\CommentsUI\Comment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractComment extends ActiveRecord implements Comment
{

    use DICTrait;
    use CommentsUITrait;


    /**
     * @inheritdoc
     */
    protected static function comments() : RepositoryInterface
    {
        return Repository::getInstance(static::class);
    }


    /**
     * @return string
     */
    public function getConnectorContainerName()
    {
        return static::TABLE_NAME;
    }


    /**
     * @return string
     *
     * @deprecated
     */
    public static function returnDbTableName()
    {
        return static::TABLE_NAME;
    }


    /**
     *
     */
    public static function updateDB_()/*: void*/
    {
        try {
            self::updateDB();
        } catch (Throwable $ex) {
            // Fix Call to a member function getName() on null (Because not use ILIAS primary key)
        }

        if (self::dic()->database()->sequenceExists(static::TABLE_NAME)) {
            self::dic()->database()->dropSequence(static::TABLE_NAME);
        }

        self::dic()->database()->createAutoIncrement(static::TABLE_NAME, "id");
    }


    /**
     *
     */
    public static function dropDB_()/*: void*/
    {
        self::dic()->database()->dropTable(static::TABLE_NAME, false);

        self::dic()->database()->dropAutoIncrementTable(static::TABLE_NAME);
    }


    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     * @con_is_primary  true
     */
    protected $id = 0;
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $comment = "";
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     */
    protected $report_obj_id;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     */
    protected $report_user_id;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   timestamp
     * @con_is_notnull  true
     */
    protected $created_timestamp;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     */
    protected $created_user_id;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   timestamp
     * @con_is_notnull  true
     */
    protected $updated_timestamp;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     */
    protected $updated_user_id;
    /**
     * @var bool
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      1
     * @con_is_notnull  true
     */
    protected $is_shared = false;
    /**
     * @var bool
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      1
     * @con_is_notnull  true
     */
    protected $deleted = false;


    /**
     * AbstractComment constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        //parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritdoc
     */
    public function getId() : int
    {
        return $this->id;
    }


    /**
     * @inheritdoc
     */
    public function setId(int $id)/*: void*/
    {
        $this->id = $id;
    }


    /**
     * @inheritdoc
     */
    public function getComment() : string
    {
        return $this->comment;
    }


    /**
     * @inheritdoc
     */
    public function setComment(string $comment)/*: void*/
    {
        $this->comment = $comment;
    }


    /**
     * @inheritdoc
     */
    public function getReportObjId() : int
    {
        return $this->report_obj_id;
    }


    /**
     * @inheritdoc
     */
    public function setReportObjId(int $report_obj_id)/*: void*/
    {
        $this->report_obj_id = $report_obj_id;
    }


    /**
     * @inheritdoc
     */
    public function getReportUserId() : int
    {
        return $this->report_user_id;
    }


    /**
     * @inheritdoc
     */
    public function setReportUserId(int $report_user_id)/*: void*/
    {
        $this->report_user_id = $report_user_id;
    }


    /**
     * @inheritdoc
     */
    public function getCreatedTimestamp() : int
    {
        return $this->created_timestamp;
    }


    /**
     * @inheritdoc
     */
    public function setCreatedTimestamp(int $created_timestamp)/*: void*/
    {
        $this->created_timestamp = $created_timestamp;
    }


    /**
     * @inheritdoc
     */
    public function getCreatedUserId() : int
    {
        return $this->created_user_id;
    }


    /**
     * @inheritdoc
     */
    public function setCreatedUserId(int $created_user_id)/*: void*/
    {
        $this->created_user_id = $created_user_id;
    }


    /**
     * @inheritdoc
     */
    public function getUpdatedTimestamp() : int
    {
        return $this->updated_timestamp;
    }


    /**
     * @inheritdoc
     */
    public function setUpdatedTimestamp(int $updated_timestamp)/*: void*/
    {
        $this->updated_timestamp = $updated_timestamp;
    }


    /**
     * @inheritdoc
     */
    public function getUpdatedUserId() : int
    {
        return $this->updated_user_id;
    }


    /**
     * @inheritdoc
     */
    public function setUpdatedUserId(int $updated_user_id)/*: void*/
    {
        $this->updated_user_id = $updated_user_id;
    }


    /**
     * @inheritdoc
     */
    public function isShared() : bool
    {
        return $this->is_shared;
    }


    /**
     * @inheritdoc
     */
    public function setIsShared(bool $is_shared)/*: void*/
    {
        $this->is_shared = $is_shared;
    }


    /**
     * @inheritdoc
     */
    public function isDeleted() : bool
    {
        return $this->deleted;
    }


    /**
     * @inheritdoc
     */
    public function setDeleted(bool $deleted)/*: void*/
    {
        $this->deleted = $deleted;
    }


    /**
     * @inheritdoc
     */
    public function jsonSerialize() : stdClass
    {
        return self::comments()->toJson($this);
    }
}

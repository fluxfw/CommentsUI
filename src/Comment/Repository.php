<?php

namespace srag\CommentsUI\Comment;

use ilDateTime;
use ilDBConstants;
use ilObjUser;
use srag\DIC\DICTrait;
use stdClass;

/**
 * Class Repository
 *
 * @package srag\CommentsUI\Comment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements RepositoryInterface
{

    use DICTrait;
    /**
     * @var RepositoryInterface[]
     */
    protected static $instances = [];


    /**
     * @param string $comment_class
     *
     * @return RepositoryInterface
     */
    public static function getInstance(string $comment_class) : RepositoryInterface
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
     * @var bool
     */
    protected $output_object_titles = false;


    /**
     * Repository constructor
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
    public function canBeDeleted(Comment $comment) : bool
    {
        if (empty($comment->getId())) {
            return false;
        }

        if ($comment->isShared() || $comment->isDeleted()) {
            return false;
        }

        if ($comment->getCreatedUserId() !== intval(self::dic()->user()->getId())) {
            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function canBeShared(Comment $comment) : bool
    {
        if (empty($comment->getId())) {
            return false;
        }

        if ($comment->isShared() || $comment->isDeleted()) {
            return false;
        }

        if ($comment->getCreatedUserId() !== intval(self::dic()->user()->getId())) {
            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function canBeStored(Comment $comment) : bool
    {
        if (empty($comment->getId())) {
            return true;
        }

        if ($comment->isShared() || $comment->isDeleted()) {
            return false;
        }

        if ($comment->getCreatedUserId() !== intval(self::dic()->user()->getId())) {
            return false;
        }

        $time = time();

        return (($time - $comment->getCreatedTimestamp()) <= (self::EDIT_LIMIT_MINUTES * 60));
    }


    /**
     * @inheritdoc
     */
    public function deleteComment(Comment $comment)/*: void*/
    {
        if (!$this->canBeDeleted($comment)) {
            return;
        }

        $comment->setDeleted(true);

        $this->storeComment($comment, false);
    }


    /**
     * @inheritdoc
     */
    public function factory() : FactoryInterface
    {
        return Factory::getInstance($this->comment_class);
    }


    /**
     * @inheritdoc
     */
    public function getCommentById(int $id)/*: ?Comment*/
    {
        /**
         * @var Comment|null $comment
         */
        $comment = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
                ->quoteIdentifier($this->comment_class::TABLE_NAME) . ' WHERE id=%s', [ilDBConstants::T_INTEGER], [$id]), [
            $this->factory(),
            "fromDB"
        ]);

        return $comment;
    }


    /**
     * @inheritdoc
     */
    public function getCommentsForReport(int $report_obj_id, int $report_user_id) : array
    {
        /**
         * @var Comment[] $comments
         */
        $comments = array_values(self::dic()->database()->fetchAllCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
                ->quoteIdentifier($this->comment_class::TABLE_NAME)
            . ' WHERE deleted=%s AND report_obj_id=%s AND report_user_id=%s ORDER BY updated_timestamp DESC', [
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER
        ], [false, $report_obj_id, $report_user_id]), [
            $this->factory(),
            "fromDB"
        ]));

        return $comments;
    }


    /**
     * @inheritdoc
     */
    public function getCommentsForCurrentUser(/*?int*/ $report_obj_id = null) : array
    {
        $where = [
            "deleted=%s",
            "report_user_id=%s",
            "is_shared=%s"
        ];
        $types = [
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER
        ];
        $values = [
            false,
            self::dic()->user()->getId(),
            true
        ];

        if (!empty($report_obj_id)) {
            $where[] = "report_obj_id=%s";
            $types[] = ilDBConstants::T_INTEGER;
            $values[] = $report_obj_id;
        }

        /**
         * @var Comment[] $comments
         */
        $comments = array_values(self::dic()->database()->fetchAllCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
                ->quoteIdentifier($this->comment_class::TABLE_NAME) . ' WHERE ' . implode(' AND ', $where)
            . ' ORDER BY updated_timestamp DESC', $types, $values), [
            $this->factory(),
            "fromDB"
        ]));

        return $comments;
    }


    /**
     * @inheritdoc
     */
    public function shareComment(Comment $comment)/*: void*/
    {
        if (!$this->canBeShared($comment)) {
            return;
        }

        $comment->setIsShared(true);

        $this->storeComment($comment, false);
    }


    /**
     * @inheritdoc
     */
    public function storeComment(Comment $comment, bool $check_can_be_store = true)/*: void*/
    {
        if ($check_can_be_store && !$this->canBeStored($comment)) {
            return;
        }

        $time = time();

        if (empty($comment->getId())) {
            $comment->setCreatedTimestamp($time);
            $comment->setCreatedUserId(self::dic()->user()->getId());
        }

        $comment->setUpdatedTimestamp($time);
        $comment->setUpdatedUserId(self::dic()->user()->getId());

        self::dic()->database()->store($this->comment_class::TABLE_NAME, [
            "comment"           => [ilDBConstants::T_TEXT, $comment->getComment()],
            "report_obj_id"     => [ilDBConstants::T_INTEGER, $comment->getReportObjId()],
            "report_user_id"    => [ilDBConstants::T_INTEGER, $comment->getReportUserId()],
            "created_timestamp" => [ilDBConstants::T_TEXT, (new ilDateTime($comment->getCreatedTimestamp(), IL_CAL_UNIX))->get(IL_CAL_DATETIME)],
            "created_user_id"   => [ilDBConstants::T_INTEGER, $comment->getCreatedUserId()],
            "updated_timestamp" => [ilDBConstants::T_TEXT, (new ilDateTime($comment->getUpdatedTimestamp(), IL_CAL_UNIX))->get(IL_CAL_DATETIME)],
            "updated_user_id"   => [ilDBConstants::T_INTEGER, $comment->getUpdatedUserId()],
            "is_shared"         => [ilDBConstants::T_INTEGER, $comment->isShared()],
            "deleted"           => [ilDBConstants::T_INTEGER, $comment->isDeleted()]
        ], "id", $comment->getId());
    }


    /**
     * @inheritdoc
     */
    public function toJson(Comment $comment) : stdClass
    {
        $content = $comment->getComment();

        if ($this->output_object_titles) {
            $content = self::dic()->objDataCache()->lookupTitle($comment->getReportObjId()) . "\n" . $content;
        }

        return (object) [
            "content"                 => $content,
            "created"                 => date("Y-m-d H:i:s", $comment->getCreatedTimestamp()),
            "created_by_current_user" => $this->canBeStored($comment),
            "deletable"               => $this->canBeDeleted($comment),
            "fullname"                => self::dic()->objDataCache()->lookupTitle($comment->getCreatedUserId()),
            "id"                      => $comment->getId(),
            "modified"                => date("Y-m-d H:i:s", $comment->getUpdatedTimestamp()),
            "profile_picture_url"     => (new ilObjUser($comment->getCreatedUserId()))->getPersonalPicturePath("big"),
            "shareable"               => $this->canBeShared($comment)
        ];
    }


    /**
     * @inheritdoc
     */
    public function withOutputObjectTitles(bool $output_object_titles = false) : RepositoryInterface
    {
        $this->output_object_titles = $output_object_titles;

        return $this;
    }
}

<?php

namespace srag\CommentsUI\Comment;

use ilDateTime;
use ilDBConstants;
use ilObjUser;
use LogicException;
use srag\CommentsUI\Comment\Comment as CommentInterface;
use srag\DIC\DICTrait;
use srag\DIC\Plugin\PluginInterface;
use srag\DIC\Util\LibraryLanguageInstaller;
use stdClass;
use Throwable;

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
     * @var RepositoryInterface|null
     */
    protected static $instance = null;


    /**
     * @return RepositoryInterface
     */
    public static function getInstance() : RepositoryInterface
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @var string
     */
    protected $table_name_prefix = "";
    /**
     * @var PluginInterface
     */
    protected $plugin;
    /**
     * @var bool
     */
    protected $output_object_titles = false;


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @inheritdoc
     */
    public function canBeDeleted(CommentInterface $comment) : bool
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
    public function canBeShared(CommentInterface $comment) : bool
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
    public function canBeStored(CommentInterface $comment) : bool
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
    public function deleteComment(CommentInterface $comment)/*: void*/
    {
        if (!$this->canBeDeleted($comment)) {
            return;
        }

        $comment->setDeleted(true);

        $this->storeComment($comment, false);
    }


    /**
     * @inheritDoc
     */
    public function dropTables()/*: void*/
    {
        self::dic()->database()->dropTable(AbstractComment::getTableName(), false);

        self::dic()->database()->dropAutoIncrementTable(AbstractComment::getTableName());
    }


    /**
     * @inheritdoc
     */
    public function factory() : FactoryInterface
    {
        return Factory::getInstance();
    }


    /**
     * @inheritdoc
     */
    public function getCommentById(int $id)/*: ?CommentInterface*/
    {
        /**
         * @var CommentInterface|null $comment
         */
        $comment = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
                ->quoteIdentifier(AbstractComment::getTableName()) . ' WHERE id=%s', [ilDBConstants::T_INTEGER], [$id]), [
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
         * @var CommentInterface[] $comments
         */
        $comments = array_values(self::dic()->database()->fetchAllCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
                ->quoteIdentifier(AbstractComment::getTableName())
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
         * @var CommentInterface[] $comments
         */
        $comments = array_values(self::dic()->database()->fetchAllCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
                ->quoteIdentifier(AbstractComment::getTableName()) . ' WHERE ' . implode(' AND ', $where)
            . ' ORDER BY updated_timestamp DESC', $types, $values), [
            $this->factory(),
            "fromDB"
        ]));

        return $comments;
    }


    /**
     * @inheritDoc
     */
    public function getPlugin() : PluginInterface
    {
        if (empty($this->plugin)) {
            throw new LogicException("plugin is empty - please call withPlugin earlier!");
        }

        return $this->plugin;
    }


    /**
     * @inheritDoc
     */
    public function getTableNamePrefix() : string
    {
        if (empty($this->table_name_prefix)) {
            throw new LogicException("table name prefix is empty - please call withTableNamePrefix earlier!");
        }

        return $this->table_name_prefix;
    }


    /**
     * @inheritDoc
     */
    public function installLanguages()/*:void*/
    {
        LibraryLanguageInstaller::getInstance()->withPlugin($this->getPlugin())->withLibraryLanguageDirectory(__DIR__
            . "/../../lang")->updateLanguages();
    }


    /**
     * @inheritDoc
     */
    public function installTables()/*:void*/
    {
        try {
            AbstractComment::updateDB();
        } catch (Throwable $ex) {
            // Fix Call to a member function getName() on null (Because not use ILIAS sequence)
        }

        if (self::dic()->database()->sequenceExists(AbstractComment::getTableName())) {
            self::dic()->database()->dropSequence(AbstractComment::getTableName());
        }

        self::dic()->database()->createAutoIncrement(AbstractComment::getTableName(), "id");
    }


    /**
     * @inheritdoc
     */
    public function shareComment(CommentInterface $comment)/*: void*/
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
    public function storeComment(CommentInterface $comment, bool $check_can_be_store = true)/*: void*/
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

        self::dic()->database()->store(AbstractComment::getTableName(), [
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
    public function toJson(CommentInterface $comment) : stdClass
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


    /**
     * @inheritDoc
     */
    public function withPlugin(PluginInterface $plugin) : RepositoryInterface
    {
        $this->plugin = $plugin;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function withTableNamePrefix(string $table_name_prefix) : RepositoryInterface
    {
        $this->table_name_prefix = $table_name_prefix;

        return $this;
    }
}

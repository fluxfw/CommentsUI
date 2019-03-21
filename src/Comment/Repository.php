<?php

namespace srag\CommentsUI\Comment;

use srag\DIC\DICTrait;

/**
 * Class Repository
 *
 * @package srag\CommentsUI\Comment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

	use DICTrait;
	/**
	 * @var self[]
	 */
	protected static $instances = [];


	/**
	 * @param string $comment_class
	 *
	 * @return self
	 */
	public static function getInstance(string $comment_class): self {
		if (!isset(self::$instances[$comment_class])) {
			self::$instances[$comment_class] = new self($comment_class);
		}

		return self::$instances[$comment_class];
	}


	/**
	 * @var string|AbstractComment
	 */
	protected $comment_class;


	/**
	 * Repository constructor
	 *
	 * @param string $comment_class
	 */
	private function __construct(string $comment_class) {
		$this->comment_class = $comment_class;
	}


	/**
	 * @param AbstractComment $comment
	 */
	public function deleteComment(AbstractComment $comment)/*: void*/ {
		$comment->delete();
	}


	/**
	 * @return Factory
	 */
	public function factory(): Factory {
		return Factory::getInstance($this->comment_class);
	}


	/**
	 * @param int $id
	 *
	 * @return AbstractComment|null
	 */
	public function getCommentById(int $id)/*: ?Comment*/ {
		/**
		 * @var AbstractComment|null $comment
		 */

		$comment = $this->comment_class::where([ "id" => $id ])->first();

		return $comment;
	}


	/**
	 * @return AbstractComment[]
	 */
	public function getComments(): array {
		/**
		 * @var AbstractComment[] $comments
		 */

		$comments = $this->comment_class::orderBy("updated_timestamp", "desc")->get();

		return $comments;
	}


	/**
	 * @param int $report_obj_id
	 * @param int $report_user_id
	 *
	 * @return AbstractComment[]
	 */
	public function getCommentsForReport(int $report_obj_id, int $report_user_id): array {
		/**
		 * @var AbstractComment[] $comments
		 */

		$comments = array_values($this->comment_class::where([
			"report_obj_id" => $report_obj_id,
			"report_user_id" => $report_user_id
		])->orderBy("updated_timestamp", "desc")->get());

		return $comments;
	}


	/**
	 * @param int|null $report_obj_id
	 *
	 * @return AbstractComment[]
	 */
	public function getCommentsForCurrentUser(/*?int*/
		$report_obj_id = null): array {
		/**
		 * @var AbstractComment[] $comments
		 */

		$where = [
			"report_user_id" => self::dic()->user()->getId(),
			"is_shared" => true
		];

		if (!empty($report_obj_id)) {
			$where["report_obj_id"] = $report_obj_id;
		}

		$comments = array_values($this->comment_class::where($where)->orderBy("updated_timestamp", "desc")->get());

		return $comments;
	}


	/**
	 * @param AbstractComment $comment
	 */
	public function storeInstance(AbstractComment $comment)/*: void*/ {
		$time = time();

		if (empty($comment->getId())) {
			$comment->setCreatedTimestamp($time);
			$comment->setCreatedUserId(self::dic()->user()->getId());
		}

		$comment->setUpdatedTimestamp($time);
		$comment->setUpdatedUserId(self::dic()->user()->getId());

		$comment->store();
	}
}

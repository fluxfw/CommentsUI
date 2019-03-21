<?php

namespace srag\CommentsUI\Ctrl;

use srag\CommentsUI\Utils\CommentsUITrait;
use srag\DIC\DICTrait;

/**
 * Class AbstractCtrl
 *
 * @package srag\CommentsUI\Ctrl
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCtrl {

	use DICTrait;
	use CommentsUITrait;
	const CMD_CREATE_COMMENT = "createComment";
	const CMD_UPDATE_COMMENT = "updateComment";
	const CMD_DELETE_COMMENT = "deleteComment";
	const GET_PARAM_COMMENT_ID = "comment_id";
	const GET_PARAM_REPORT_OBJ_ID = "report_obj_id";
	const GET_PARAM_REPORT_USER_ID = "report_user_id";
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const COMMENTS_CLASS_NAME = "";


	/**
	 * AbstractCtrl constructor
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		$cmd = self::dic()->ctrl()->getCmd();

		switch ($cmd) {
			case self::CMD_CREATE_COMMENT:
			case self::CMD_UPDATE_COMMENT:
			case self::CMD_DELETE_COMMENT:
				$this->{$cmd}();
				break;

			default:
				break;
		}
	}


	/**
	 *
	 */
	public function createComment()/*: void*/ {
		$report_obj_id = intval(filter_input(INPUT_GET, self::GET_PARAM_REPORT_OBJ_ID));
		$report_user_id = intval(filter_input(INPUT_GET, self::GET_PARAM_REPORT_USER_ID));

		$comment = self::comments(static::COMMENTS_CLASS_NAME)->factory()->newInstance();

		$comment->setReportObjId($report_obj_id);

		$comment->setReportUserId($report_user_id);

		$comment->setComment(filter_input(INPUT_POST, "content"));

		self::comments(static::COMMENTS_CLASS_NAME)->storeInstance($comment);

		self::output()->outputJSON($comment);
	}


	/**
	 *
	 */
	public function updateComment()/*: void*/ {
		$comment_id = intval(filter_input(INPUT_GET, self::GET_PARAM_COMMENT_ID));

		$comment = self::comments(static::COMMENTS_CLASS_NAME)->getCommentById($comment_id);

		$comment->setComment(filter_input(INPUT_POST, "content"));

		self::comments(static::COMMENTS_CLASS_NAME)->storeInstance($comment);
	}


	/**
	 *
	 */
	public function deleteComment()/*: void*/ {
		$comment_id = intval(filter_input(INPUT_GET, self::GET_PARAM_COMMENT_ID));

		$comment = self::comments(static::COMMENTS_CLASS_NAME)->getCommentById($comment_id);

		self::comments(static::COMMENTS_CLASS_NAME)->deleteComment($comment);
	}


	/**
	 * @return string
	 */
	public function getAsyncBaseUrl(): string {
		self::dic()->ctrl()->setParameter($this, self::GET_PARAM_REPORT_OBJ_ID, $report_obj_id);
		self::dic()->ctrl()->setParameter($this, self::GET_PARAM_REPORT_USER_ID, $report_user_id);

		return self::dic()->ctrl()->getLinkTarget($this, "", "", true, false);
	}


	/**
	 * @return array
	 */
	public abstract function getComments(): array;
}

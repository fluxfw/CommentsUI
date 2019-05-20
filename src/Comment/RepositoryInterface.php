<?php

namespace srag\CommentsUI\Comment;

use stdClass;

/**
 * Interface RepositoryInterface
 *
 * @package srag\CommentsUI\Comment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface {

	/**
	 * @var int
	 */
	const EDIT_LIMIT_MINUTES = 5;


	/**
	 * @param Comment $comment
	 *
	 * @return bool
	 */
	public function canBeDeleted(Comment $comment): bool;


	/**
	 * @param Comment $comment
	 *
	 * @return bool
	 */
	public function canBeShared(Comment $comment): bool;


	/**
	 * @param Comment $comment
	 *
	 * @return bool
	 */
	public function canBeStored(Comment $comment): bool;


	/**
	 * @param Comment $comment
	 */
	public function deleteComment(Comment $comment)/*: void*/ ;


	/**
	 * @return FactoryInterface
	 */
	public function factory(): FactoryInterface;


	/**
	 * @param int $id
	 *
	 * @return Comment|null
	 */
	public function getCommentById(int $id)/*: ?Comment*/ ;


	/**
	 * @param int $report_obj_id
	 * @param int $report_user_id
	 *
	 * @return Comment[]
	 */
	public function getCommentsForReport(int $report_obj_id, int $report_user_id): array;


	/**
	 * @param int|null $report_obj_id
	 *
	 * @return Comment[]
	 */
	public function getCommentsForCurrentUser(/*?int*/ $report_obj_id = null): array;


	/**
	 * @param Comment $comment
	 */
	public function shareComment(Comment $comment)/*: void*/ ;


	/**
	 * @param Comment $comment
	 * @param bool    $check_can_be_store
	 */
	public function storeComment(Comment $comment, bool $check_can_be_store = true)/*: void*/ ;


	/**
	 * @param Comment $comment
	 *
	 * @return stdClass
	 */
	public function toJson(Comment $comment): stdClass;


	/**
	 * @param bool $output_object_titles
	 *
	 * @return self
	 */
	public function withOutputObjectTitles(bool $output_object_titles = false): self;
}

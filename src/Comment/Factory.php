<?php

namespace srag\CommentsUI\Comment;

use srag\DIC\DICTrait;

/**
 * Class Factory
 *
 * @package srag\CommentsUI\Comment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory {

	use DICTrait;
	/**
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Factory constructor
	 */
	private function __construct() {

	}


	/**
	 * @return Comment
	 */
	public function newInstance(): Comment {
		$comment = new Comment();

		return $comment;
	}
}

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
	 * Factory constructor
	 *
	 * @param string $comment_class
	 */
	private function __construct(string $comment_class) {
		$this->comment_class = $comment_class;
	}


	/**
	 * @return AbstractComment
	 */
	public function newInstance(): AbstractComment {
		$comment = new $this->comment_class();

		return $comment;
	}
}

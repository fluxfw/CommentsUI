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
final class Factory implements FactoryInterface {

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
	public static function getInstance(string $comment_class): FactoryInterface {
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
	private function __construct(string $comment_class) {
		$this->comment_class = $comment_class;
	}


	/**
	 * @inheritdoc
	 */
	public function newInstance(): Comment {
		$comment = new $this->comment_class();

		return $comment;
	}
}

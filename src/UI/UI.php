<?php

namespace srag\CommentsUI\UI;

use srag\CommentsUI\Comment\Comment;
use srag\DIC\DICTrait;

/**
 * Class UI
 *
 * @package srag\CommentsUI\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class UI {

	use DICTrait;
	/**
	 * @var Comment[]
	 */
	protected $comments = [];


	/**
	 * UI constructor
	 */
	public function __construct() {

	}


	/**
	 * @param Comment[] $comments
	 *
	 * @return self
	 */
	public function withComments(array $comments): self {
		$this->comments = $comments;

		return $this;
	}


	/**
	 * @return string
	 */
	public function render(): string {
		return "TODO";
	}
}

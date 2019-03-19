<?php

namespace srag\CommentsUI;

use srag\CommentsUI\Repository as CommentsRepository;
use srag\CommentsUI\UI as CommentsUI;

/**
 * Trait CommentsUITrait
 *
 * @package srag\CommentsUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait CommentsUITrait {

	/**
	 * @return CommentsRepository
	 */
	protected static function comments(): CommentsRepository {
		return CommentsRepository::getInstance();
	}


	/**
	 * @return CommentsUI
	 */
	protected static function commentsUI(): CommentsUI {
		return new CommentsUI();
	}
}

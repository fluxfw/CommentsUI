<?php

namespace srag\CommentsUI;

use srag\DIC\DICTrait;

/**
 * Class UI
 *
 * @package srag\CommentsUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class UI {

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
	 * UI constructor
	 */
	public function __construct() {

	}
}

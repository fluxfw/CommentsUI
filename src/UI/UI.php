<?php

namespace srag\CommentsUI\UI;

use ilTemplate;
use srag\CommentsUI\Ctrl\AbstractCtrl;
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
	 * @var bool
	 */
	protected static $init = false;
	/**
	 * @var string
	 */
	protected $id = "";
	/**
	 * @var AbstractCtrl
	 */
	protected $ctrl_class;


	/**
	 * UI constructor
	 */
	public function __construct() {

	}


	/**
	 * @param string $id
	 *
	 * @return self
	 */
	public function withId(string $id): self {
		$this->id = $id;

		return $this;
	}


	/**
	 * @param AbstractCtrl $ctrl_class
	 *
	 * @return self
	 */
	public function withCtrlClass(AbstractCtrl $ctrl_class): self {
		$this->ctrl_class = $ctrl_class;

		return $this;
	}


	/**
	 *
	 */
	private function initJs()/*: void*/ {
		if (self::$init === false) {
			self::$init = true;

			$dir = __DIR__;
			$dir = "./" . substr($dir, strpos($dir, "/Customizing/") + 1);

			self::dic()->mainTemplate()->addJavaScript($dir . "/../../node_modules/jquery-comments/js/jquery-comments.js");
			self::dic()->mainTemplate()->addCss($dir . "/../../node_modules/jquery-comments/css/jquery-comments.css");

			self::dic()->mainTemplate()->addJavaScript($dir . "/../../js/commentsui.min.js");
			self::dic()->mainTemplate()->addCss($dir . "/../../css/commentsui.css");
		}
	}


	/**
	 * @return string
	 */
	public function render(): string {
		$this->initJs();

		$tpl = new ilTemplate(__DIR__ . "/../../templates/commentsui.html", false, false);

		$tpl->setVariable("ID", $this->id);

		$tpl->setVariable("READONLY", json_encode($this->ctrl_class->getIsReadOnly()));

		$tpl->setVariable("ASYNC_BASE_URL", json_encode($this->ctrl_class->getAsyncBaseUrl()));

		return self::output()->getHTML($tpl);
	}
}

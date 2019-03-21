<?php

namespace srag\CommentsUI\UI;

use ilTemplate;
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
	 * @var bool
	 */
	protected static $init = false;
	/**
	 * @var string
	 */
	protected $id = "";
	/**
	 * @var Comment[]
	 */
	protected $comments = [];
	/**
	 * @var bool
	 */
	protected $readonly = false;
	/**
	 * @var string
	 */
	protected $async_base_url = "";


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
	 * @param Comment[] $comments
	 *
	 * @return self
	 */
	public function withComments(array $comments): self {
		$this->comments = $comments;

		return $this;
	}


	/**
	 * @param bool $readonly
	 *
	 * @return self
	 */
	public function withReadonly(bool $readonly): self {
		$this->readonly = $readonly;

		return $this;
	}


	/**
	 * @param string $async_class
	 * @param int    $report_obj_id
	 * @param int    $report_user_id
	 *
	 * @return self
	 */
	public function withAsyncClass(string $async_class, int $report_obj_id, int $report_user_id): self {
		self::dic()->ctrl()->setParameterByClass($async_class, Ctrl::GET_PARAM_REPORT_OBJ_ID, $report_obj_id);
		self::dic()->ctrl()->setParameterByClass($async_class, Ctrl::GET_PARAM_REPORT_USER_ID, $report_user_id);

		$this->async_base_url = self::dic()->ctrl()->getLinkTargetByClass($async_class);

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

		$tpl->setVariable("COMMENTS", json_encode($this->comments));

		$tpl->setVariable("READONLY", json_encode($this->readonly));

		$tpl->setVariable("ASYNC_BASE_URL", json_encode($this->async_base_url));

		return self::output()->getHTML($tpl);
	}
}

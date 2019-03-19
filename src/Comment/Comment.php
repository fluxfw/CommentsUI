<?php

namespace srag\CommentsUI\Comment;

use ActiveRecord;
use arConnector;
use ilDateTime;
use JsonSerializable;
use srag\DIC\DICTrait;
use stdClass;

/**
 * Class Comment
 *
 * @package srag\CommentsUI\Comment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class Comment extends ActiveRecord implements JsonSerializable {

	use DICTrait;
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const TABLE_NAME = "";


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return static::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return static::TABLE_NAME;
	}


	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 * @con_is_primary  true
	 * @con_sequence    true
	 */
	protected $id;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $comment = "";
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $report_obj_id;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $report_user_id;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   timestamp
	 * @con_is_notnull  true
	 */
	protected $created_timestamp;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $created_user_id;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   timestamp
	 * @con_is_notnull  true
	 */
	protected $updated_timestamp;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $updated_user_id;
	/**
	 * @var bool
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      1
	 * @con_is_notnull  true
	 */
	protected $is_shared = false;


	/**
	 * Comment constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct(/*int*/
		$primary_key_value = 0, /*?*/
		arConnector $connector = null) {
		parent::__construct($primary_key_value, $connector);
	}


	/**
	 * @param string $field_name
	 *
	 * @return mixed|null
	 */
	public function sleep(/*string*/
		$field_name) {
		$field_value = $this->{$field_name};

		switch ($field_name) {
			case "is_shared":
				return ($field_value ? 1 : 0);

			case "created_timestamp":
			case "updated_timestamp":
				return (new ilDateTime($field_value, IL_CAL_UNIX))->get(IL_CAL_DATETIME);

			default:
				return null;
		}
	}


	/**
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 */
	public function wakeUp(/*string*/
		$field_name, $field_value) {
		switch ($field_name) {
			case "id":
			case "report_obj_id":
			case "report_user_id":
			case "created_user_id":
			case "updated_user_id":
				return intval($field_value);
				break;

			case "is_shared":
				return boolval($field_value);

			case "created_timestamp":
			case "updated_timestamp":
				return (new ilDateTime($field_value, IL_CAL_DATETIME))->getUnixTime();

			default:
				return null;
		}
	}


	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}


	/**
	 * @param int $id
	 */
	public function setId(int $id)/*: void*/ {
		$this->id = $id;
	}


	/**
	 * @return string
	 */
	public function getComment(): string {
		return $this->comment;
	}


	/**
	 * @param string $comment
	 */
	public function setComment(string $comment)/*: void*/ {
		$this->comment = $comment;
	}


	/**
	 * @return int
	 */
	public function getReportObjId(): int {
		return $this->report_obj_id;
	}


	/**
	 * @param int $report_obj_id
	 */
	public function setReportObjId(int $report_obj_id)/*: void*/ {
		$this->report_obj_id = $report_obj_id;
	}


	/**
	 * @return int
	 */
	public function getReportUserId(): int {
		return $this->report_user_id;
	}


	/**
	 * @param int $report_user_id
	 */
	public function setReportUserId(int $report_user_id)/*: void*/ {
		$this->report_user_id = $report_user_id;
	}


	/**
	 * @return int
	 */
	public function getCreatedTimestamp(): int {
		return $this->created_timestamp;
	}


	/**
	 * @param int $created_timestamp
	 */
	public function setCreatedTimestamp(int $created_timestamp)/*: void*/ {
		$this->created_timestamp = $created_timestamp;
	}


	/**
	 * @return int
	 */
	public function getCreatedUserId(): int {
		return $this->created_user_id;
	}


	/**
	 * @param int $created_user_id
	 */
	public function setCreatedUserId(int $created_user_id)/*: void*/ {
		$this->created_user_id = $created_user_id;
	}


	/**
	 * @return int
	 */
	public function getUpdatedTimestamp(): int {
		return $this->updated_timestamp;
	}


	/**
	 * @param int $updated_timestamp
	 */
	public function setUpdatedTimestamp(int $updated_timestamp)/*: void*/ {
		$this->updated_timestamp = $updated_timestamp;
	}


	/**
	 * @return int
	 */
	public function getUpdatedUserId(): int {
		return $this->updated_user_id;
	}


	/**
	 * @param int $updated_user_id
	 */
	public function setUpdatedUserId(int $updated_user_id)/*: void*/ {
		$this->updated_user_id = $updated_user_id;
	}


	/**
	 * @return bool
	 */
	public function isShared(): bool {
		return $this->is_shared;
	}


	/**
	 * @param bool $is_shared
	 */
	public function setIsShared(bool $is_shared)/*: void*/ {
		$this->is_shared = $is_shared;
	}


	/**
	 * @return stdClass
	 */
	public function jsonSerialize(): stdClass {
		return (object)[
			"id" => $this->id,
			"created" => $this->created_timestamp,
			"content" => $this->comment,
			"fullname" => self::dic()->objDataCache()->lookupTitle($this->created_user_id)
		];
	}
}

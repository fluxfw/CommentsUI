/**
 * @param {string} container_id
 * @param {bool} readonly
 * @param {string} async_base_url
 *
 * @constructor
 */
il.CommentsUI = function (container_id = "", readonly = false, async_base_url = "") {
	this.element = $("#" + container_id);

	this.readonly = readonly;

	this.async_base_url = async_base_url;

	this.init();
};

/**
 * @type {il.CommentsUI[]}
 *
 * @private
 */
il.CommentsUI.INSTANCES = [];

/**
 * @param {string} container_id
 * @param {bool} readonly
 * @param {string} async_base_url
 */
il.CommentsUI.newInstance = function (container_id = "", readonly = false, async_base_url = "") {
	this.INSTANCES.push(new this(container_id, readonly, async_base_url));
};

/**
 * @type {Object}
 */
il.CommentsUI.prototype = {
	constructor: il.CommentsUI,

	/**
	 * @type {string}
	 */
	async_base_url: "",

	/**
	 * @type {jQuery|null}
	 */
	element: null,

	/**
	 * @type {boolean}
	 */
	readonly: false,

	/**
	 * @param {Object} comment
	 * @param {function} onSuccess
	 * @param {function} onError
	 */
	createComment: function (comment, onSuccess, onError) {
		$.ajax({
			type: "post",
			url: this.async_base_url + "&cmd=createComment",
			data: comment,
			success: onSuccess,
			error: onError
		});
	},

	/**
	 * @param {Object} comment
	 * @param {function} onSuccess
	 * @param {function} onError
	 */
	deleteComment: function (comment, onSuccess, onError) {
		$.ajax({
			type: "delete",
			url: this.async_base_url + "&cmd=deleteComment&comment_id=" + comment.id,
			success: onSuccess,
			error: onError
		});
	},

	/**
	 * @param {function} onSuccess
	 * @param {function} onError
	 */
	getComments: function (onSuccess, onError) {
		$.ajax({
			type: "get",
			url: this.async_base_url + "&cmd=getComments",
			success: onSuccess,
			error: onError
		});
	},

	/**
	 *
	 */
	init: function () {
		if (this.readonly) {
			this.element.addClass("readonly");
		}

		this.element.comments({
			enableDeleting: !this.readonly,
			enableEditing: !this.readonly,

			forceResponsive: false,
			enableAttachments: false,
			enableDeletingCommentWithReplies: false,
			enableHashtags: false,
			enableNavigation: false,
			enablePinging: false,
			enableReplying: false,
			enableUpvoting: false,
			postCommentOnEnter: false,
			readOnly: this.readonly,

			getComments: this.getComments.bind(this),
			postComment: this.createComment.bind(this),
			putComment: this.updateComment.bind(this),
			deleteComment: this.deleteComment.bind(this)
		});
	},

	/**
	 * @param {Object} comment
	 * @param {function} onSuccess
	 * @param {function} onError
	 */
	updateComment: function (comment, onSuccess, onError) {
		$.ajax({
			type: "put",
			url: this.async_base_url + "&cmd=updateComment&comment_id=" + comment.id,
			data: comment,
			success: onSuccess,
			error: onError
		});
	}
};

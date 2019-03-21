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
			success: function (comment) {
				onSuccess(comment);

				// TODO
				//this.getCommentsUpdate();
			},
			error: onError
		});
	},

	/**
	 * @param {Object} comment
	 * @param {jQuery} commentElement
	 */
	deleteComment: function (comment, commentElement) {
		$.ajax({
			type: "post",
			url: this.async_base_url + "&cmd=deleteComment&comment_id=" + comment.id,
			success: function () {
				commentElement.remove();
			},
			error: function () {
			}
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
			success: function (comments) {
				onSuccess(comments);

				this.getCommentsUpdate(comments);
			}.bind(this),
			error: onError
		});
	},

	/**
	 * @param {Array} comments
	 */
	getCommentsUpdate: function (comments) {
		if (!this.readonly) {
			comments.forEach(function (comment) {
				var commentElement = $(".comment[data-id=" + comment.id + "]", this.element);
				var actions = $(" .actions", commentElement);

				if (comment.deletable) {
					// Delete
					var deleteButton = $('<button/>', {
						class: "action delete",
						text: "Delete",
					});
					deleteButton.on("click", this.deleteComment.bind(this, comment, commentElement));
					actions.append(deleteButton);
				}

				// Share
				if (!comment.is_shared) {
					var shareButton = $('<button/>', {
						class: "action share",
						text: "Share",
					});
					shareButton.on("click", this.shareComment.bind(this, comment, shareButton));
					actions.append(shareButton);
				}
			}, this);
		}
	},

	/**
	 *
	 */
	init: function () {
		if (this.readonly) {
			this.element.addClass("readonly");
		}

		this.element.comments({
			enableEditing: !this.readonly,

			forceResponsive: false,
			enableAttachments: false,
			enableDeleting: false,
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
			putComment: this.updateComment.bind(this)
		});
	},

	/**
	 * @param {Object} comment
	 * @param {jQuery} shareButton
	 */
	shareComment: function (comment, shareButton) {
		$.ajax({
			type: "post",
			url: this.async_base_url + "&cmd=shareComment&comment_id=" + comment.id,
			success: function () {
				shareButton.remove();
			},
			error: function () {
			}
		});
	},

	/**
	 * @param {Object} comment
	 * @param {function} onSuccess
	 * @param {function} onError
	 */
	updateComment: function (comment, onSuccess, onError) {
		$.ajax({
			type: "post",
			url: this.async_base_url + "&cmd=updateComment&comment_id=" + comment.id,
			data: comment,
			success: function (comment) {
				onSuccess(comment);

				// TODO
				//this.getCommentsUpdate();
			},
			error: onError
		});
	}
};

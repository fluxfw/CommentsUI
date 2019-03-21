/**
 * @param {Array} comments
 * @param {string} container_id
 *
 * @constructor
 */
il.CommentsUI = function (comments = [], container_id = "") {
	this.element = $(container_id);

	this.comments = comments;

	this.init();
};

/**
 * @type {il.CommentsUI[]}
 *
 * @private
 */
il.CommentsUI.INSTANCES = [];

/**
 * @param {Array} comments
 * @param {string} container_id
 */
il.CommentsUI.newInstance = function (comments = [], container_id = "") {
	this.INSTANCES.push(new this(comments, container_id));
};

/**
 * @type {Object}
 */
il.CommentsUI.prototype = {
	constructor: il.CommentsUI,

	/**
	 * @type {Array}
	 */
	comments: [],

	/**
	 * @type {jQuery|null}
	 */
	element: null,

	/**
	 * @param {Object} commentJSON
	 * @param {function} success
	 * @param {function} error
	 */
	deleteComment: function (commentJSON, success, error) {

	},

	/**
	 * @param {function} success
	 * @param {function} error
	 */
	getComments: function (success, error) {
		success(this.comments);
	},

	/**
	 *
	 */
	init: function () {
		this.element.comments({
			enableDeleting: true,
			enableEditing: true,

			forceResponsive: false,
			enableAttachments: false,
			enableDeletingCommentWithReplies: false,
			enableHashtags: false,
			enableNavigation: false,
			enablePinging: false,
			enableReplying: false,
			enableUpvoting: false,
			postCommentOnEnter: false,
			readOnly: false,

			getComments: this.getComments.bind(this),

			postComment: this.postComment.bind(this),

			putComment: this.putComment.bind(this),

			deleteComment: this.deleteComment.bind(this)
		});
	},

	/**
	 * @param {Object} commentJSON
	 * @param {function} success
	 * @param {function} error
	 */
	postComment: function (commentJSON, success, error) {

	},

	/**
	 * @param {Object} commentJSON
	 * @param {function} success
	 * @param {function} error
	 */
	putComment: function (commentJSON, success, error) {

	}
};

$(document).ready(function () {
	$("#comments-container").comments({
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

		getComments: function (success, error) {
			var commentsArray = [{
				id: 1,
				created: "2015-10-01",
				content: "Lorem ipsum dolort sit amet",
				fullname: "Simon Powell",
				upvote_count: 2,
				user_has_upvoted: false
			}];
			success(commentsArray);
		},

		postComment: function (commentJSON, success, error) {
			$.ajax({
				type: "post",
				url: "/api/comments/",
				data: commentJSON,
				success: function (comment) {
					success(comment)
				},
				error: error
			});
		},

		putComment: function (commentJSON, success, error) {
			$.ajax({
				type: "put",
				url: "/api/comments/" + commentJSON.id,
				data: commentJSON,
				success: function (comment) {
					success(comment)
				},
				error: error
			});
		},

		deleteComment: function (commentJSON, success, error) {
			$.ajax({
				type: "delete",
				url: "/api/comments/" + commentJSON.id,
				success: success,
				error: error
			});
		}
	});
});

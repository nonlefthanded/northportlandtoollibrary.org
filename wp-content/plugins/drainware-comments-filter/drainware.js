jQuery(document).ready(function () {
	jQuery('.drainware-status').each(function () {
		var thisId = jQuery(this).attr('commentid');
		jQuery(this).prependTo('#comment-' + thisId + ' .column-comment div:first-child');
	});
	jQuery('.drainware-user-comment-count').each(function () {
		var thisId = jQuery(this).attr('commentid');
		jQuery(this).insertAfter('#comment-' + thisId + ' .author strong:first').show();
	});
});

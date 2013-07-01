$(function() {
	$('.gallery_images').iscrubber();

	$(".galleries").sortable({
		cursor: 'move',
		handle: '.title',
		stop: function(event, ui) {
			var order = '';

			$('.galleries.ui-sortable > div').each(function() {
				order = order + ',' + $(this).attr('id');
			});

			order = order.substring(1);

			$.ajax({
				type: 'POST',
				global: false,
				url: SITE_URL + 'admin/galleries/reorder',
				data: { order: order },

				error: function(data) {
					pyro.add_notification($('<div class="alert error">Unable to reorder galleries</div>'));
				}

			});
		}
	});

	$(".galleries").disableSelection();
});
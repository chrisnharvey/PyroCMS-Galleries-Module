$(function() {
	bind_upload('admin/galleries/upload/' + $('#id').val());

	$('#dropbox').sortable({
		start: function(e, ui) {
			$(this).addClass('noclick');
		},

		stop: function(e, ui) {
			$(this).removeClass('noclick');

			var order = '';

			$('.ui-sortable div.preview').each(function() {
				order = order + ',' + $(this).attr('id').substring(6);
			});

			order = order.substring(1);

			$.ajax({
				type: 'POST',
				global: false,
				url: SITE_URL + 'admin/galleries/images/reorder',
				data: { order: order },

				error: function(data) {
					pyro.add_notification($('<div class="alert error">Unable to reorder images</div>'));
				}

			});
		}
	});

	$('#dropbox:not(.noclick) .edit_image').live('click', function(e) {
		e.preventDefault();

		$.colorbox({
			href: SITE_URL + 'admin/galleries/images/edit/' + $(this).data('id'),
			width: 500
		});
	});

	$('.ajax').live('submit', function(e) {
		e.preventDefault();

		var data = $(this).serializeArray();
		var id = $(this).data('id');

		$.ajax({
			type: 'POST',
			global: false,
			url: SITE_URL + 'admin/galleries/images/edit/' + id,
			data: data,

			success: function(data) {
				$('#image-' + id + ' .imageTitle').text(data.name);

				$.colorbox.close();
			},

			error: function(data) {
				pyro.add_notification($('<div class="alert error">Unable to edit image</div>'));
			}

		});
	});
});
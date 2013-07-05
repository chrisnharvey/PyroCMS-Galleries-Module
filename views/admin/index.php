<div class="one_full">
	<section class="title">
		<h4>Galleries</h4>
	</section>

	<section class="item">
		<div class="content">
			<?php if ( ! empty($entries)): ?>
				

			<div class="galleries">
				<?php foreach ($entries as $entry): ?>

				<div id="<?php echo $entry['id']; ?>">

					<section class="title">
						<h4><?php echo $entry['name']; ?></h4>
					</section>

					<section class="item">

						<div class="gallery_data">
							<ul class="gallery_images">
								<?php foreach ($entry['images'] as $image): ?>

									<li>
										<a href="<?php echo site_url("admin/galleries/make_cover/{$entry['id']}/{$image['id']}"); ?>">
											<img src="<?php echo site_url("files/thumb/{$image['file']}/184/200/fit"); ?>">
										</a>
										<div class="make-cover">Click to make cover image</div>
									</li>

								<?php endforeach; ?>
							</ul>

							<span>
								<div class="buttons">
									<?php if ($entry['status']['key'] == 'live'): ?>
										<a class="btn green confirm" title="Are you sure you want to set this image to draft? It will no longer appear on the site!" href="<?php echo site_url('admin/galleries/draft/'.$entry['id']); ?>">
											<span>Live</span>
										</a>
									<?php else: ?>
										<a class="btn orange confirm" title="Are you sure you want to set this image to live? It will be visible on the site!" href="<?php echo site_url('admin/galleries/live/'.$entry['id']); ?>">
											<span>Draft</span>
										</a>
									<?php endif; ?>
									<a class="btn blue" href="<?php echo site_url('admin/galleries/edit/'.$entry['id']); ?>">
										<span>Edit</span>
									</a>
									<a class="btn red confirm" href="<?php echo site_url('admin/galleries/delete/'.$entry['id']); ?>">
										<span>Delete</span>
									</a>
								</div>
							</span>
						</div>

					</section>
					
				</div>

				<?php endforeach; ?>
			</div>

			<?php else : ?>
				<div class="no_data">There are currently no galleries</div>
			<?php endif ?>
		</div>
	</section>
</div>

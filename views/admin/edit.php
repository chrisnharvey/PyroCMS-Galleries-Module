<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud" id="tabs"'); ?>
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
	<section class="title">
        <h4>Editing "<?=$name?>" Gallery</h4>
    </section>

    <section class="item">
		<div class="content">

			<div class="tabs">

			    <ul class="tab-menu">
					<li><a href="#general"><span>General</span></a></li>
					<li><a href="#images"><span>Images</span></a></li>
			    </ul>

			    <div id="general" class="form_inputs">
	                <fieldset>
	                    <ul>
	                    	<?php foreach ($fields as $field): ?>
		                    	<li>
	                                <label for="title">
	                                    <?php echo $field['input_title']; ?> <?php echo $field['required']; ?>
	                                    <small><?php echo $field['instructions']; ?></small>
	                                </label>
	                                <div class="input">
	                                    <?php echo $field['input']; ?>
	                                </div>
	                            </li>
	                        <?php endforeach; ?>
	                    </ul>
	                </fieldset>
	            </div>

	            <div id="images" class="form_inputs">
	                <fieldset>
	                    <div id="dropbox">
	                    	<?php echo count($images) > 0 ? '' : '<span class="message">Drop images here or click to upload</span>'; ?>

	                        <?php foreach($images as $image): ?>
	                            <div class="preview" id="image-<?php echo $image['id']; ?>">
	                                <span class="imageHolder">
	                                    <a href="<?php echo site_url("admin/galleries/images/delete/{$image['id']}"); ?>" class="delete">x</a>
	                                    <a class="edit_image" href="#" data-id="<?php echo $image['id']; ?>"><img src="<?php echo site_url("files/thumb/{$image['file']}/480/360"); ?>" /></a>
	                                </span>
	                                <span class="imageTitle">

	                                	<?php echo $image['name']; ?>

	                                </span>
	                            </div>
	                        <?php endforeach; ?>
	                    </div>
	                </fieldset>
	            </div>

			</div>

			<div class="buttons align-right padding-top">
	            <button class="btn blue" value="save" name="btnAction" type="submit">
					<span>Save</span>
				</button>
	        </div>

		</div>
	</section>
<?php form_close(); ?>
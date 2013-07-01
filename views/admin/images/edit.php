<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud ajax" data-id="'.$id.'"'); ?>
	<ul>
		<?php foreach ($fields as $field): ?>
			<li>
				<label for="<?php echo $field['input_slug']; ?>">
					<?php echo $field['input_title']; ?>
				</label>
				<?php echo $field['input']; ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="buttons align-right padding-top">
        <button class="btn blue" value="save" name="btnAction" type="submit">
			<span>Save</span>
		</button>
    </div>
<?php form_close(); ?>
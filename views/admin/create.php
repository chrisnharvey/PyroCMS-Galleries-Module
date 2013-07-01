<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud" id="tabs"'); ?>


	<section class="title">
        <h4>Test</h4>
    </section>

    <section class="item">
		<div class="content">

			<div class="tabs">

			    <ul class="tab-menu">
					<li><a href="#general"><span>General</span></a></li>
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

			</div>

			<div class="buttons align-right padding-top">
	            <button class="btn blue" value="save" name="btnAction" type="submit">
					<span>Save &amp; Add Images</span>
				</button>
	        </div>

		</div>
	</section>
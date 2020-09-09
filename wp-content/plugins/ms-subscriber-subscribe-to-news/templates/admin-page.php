<?php

namespace MS_WEB\MS_SUBSCRIBER;

if (current_user_can('manage_options')) {
  ?>
  <div class="container">
    <div class="col-sm-12">
  	<h4><?php _e('Settings', 'ms-plugins'); ?> MS-Subscriber</h4>
    </div>
    <div class="row">
  	<div class="col-sm-12" style="box-shadow: 2px 11px 20px #00000052;">
  	  <ul class="nav nav-tabs" id="myTab" role="tablist">
  		<li class="nav-item">
  		  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
  			 aria-controls="home" aria-selected="true"><?php _e('Primary', 'ms-plugins'); ?></a>
  		</li>
  	  </ul>
  	  <div class="tab-content" id="myTabContent">
  		<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

  		  <!-- 1 section  Run newsletter -->

  		  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

  			<div class="panel panel-primary">
  			  <div class="panel-heading minimized" role="tab" id="headingTwo" data-toggle="collapse"
  				   targetId="collapseTwo">
  				<h5 class="panel-title">
  				  <a class="collapsed" data-parent="#accordion" aria-expanded="false"
  					 aria-controls="collapseTwo">
						 <?php _e('Run newsletter', 'ms-plugins'); ?>
  				  </a>
  				</h5>
  			  </div>
  			  <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
  				   aria-labelledby="headingTwo">

  				<div class="col-sm-12 text-center" style="padding: 20px">
					<?php
					if (!$param['active_mce']) {
					  ?>
					  <div class="msweb-subscribe-warrning">
						<p><?php _e('Recommended for installing the TinyMCE Advanced plugin. It allows you to edit content, while setting a lot of settings, including font size in paragraphs, text color, background color, etc.', 'ms-plugins'); ?></p>
						<div class="text-right">
						  <button class="btn btn-success btn-xs"
								  onclick="msweb.plugins.msSubscribe.onInstallMCEClick();"><?php _e('Install TinyMCE', 'ms-plugins'); ?></button>
						</div>
					  </div>
					<?php } ?>
					<?php echo wp_editor('', $param['editor_id'], $param['editor_params']); ?>
  				</div>
  				<div class="msweb-plugins-subscriber-menu-item"><div class="row">
  					<div class="col-sm-12">
						<?php
						echo Main::getTemplatesList();
						?>
  					</div>
  				  </div>
  				</div>
  				<div class="row">
  				  <div class="col-sm-6 text-center">
  					<button class="btn btn-success"
							onclick="msweb.plugins.msSubscribe.saveTemplate()"><?php _e('Save as template', 'ms-plugins'); ?></button><br>
							<?php
							// поставил проверку, поэтому этого больше не надо
							// _e('You must turn off and turn on again a plugin, to use this functionality', 'ms-plugins');
							?>
  				  </div>
  				  <div class="col-sm-6 text-center">
  					<button class="btn btn-success"
  							onclick="msweb.plugins.msSubscribe.sendNews()"><?php _e('Send', 'ms-plugins'); ?></button>
  				  </div>
  				</div>
  			  </div>
  			</div>
  		  </div>
  		  <!-- END 1 section  Run newsletter -->


  		  <div class="panel panel-primary">
  			<div class="panel-heading minimized" role="tab" id="headingOne" data-toggle="collapse"
  				 targetId="collapseOne">
  			  <h5 class="panel-title">
  				<a data-parent="#accordion" aria-expanded="true" aria-controls="collapseOne">
					<?php _e('The way to send letters', 'ms-plugins'); ?>
  				</a>
  			  </h5>
  			</div>
  			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
  				 aria-labelledby="headingOne">
  			  <div class="msweb-plugins-subscriber-menu-item">
  				<input type="checkbox" id="sendertypesmtp" <?php
				  if ($param['use_smtp']) {
					echo 'checked';
				  }
				  ?>>
  				<label for="sendertypesmtp"><?php _e('Use', 'ms-plugins'); ?> SMTP</label>
  				<span class="dashicons dashicons-editor-help" data-toggle="tooltip"
  					  title="<?php _e('Send letters using your mailbox (tested on mail servers', 'ms-plugins'); ?> yandex.ru, mail.ru и gmail.com, smtp.beget.com)"></span>
  				<div class="subscriber-smtp-form <?php
				  if ($param['use_smtp']) {
					echo 'visible';
				  }
				  ?>">
  				  <label>Email (<?php _e('mail for the domain is also suitable', 'ms-plugins'); ?>)</label>
  				  <div ms-input name="smtp-email" class="form-control"
  					   placeholder="<?php
						 if (!empty($param['smtp']['smtp_email'])) {
						   echo $param['smtp']['smtp_email'];
						 } else {
						   _e('Enter Email', 'ms-plugins');
						 }
						 ?>"><?php
						   if (!empty($param['smtp']['smtp_email'])) {
							 echo $param['smtp']['smtp_email'];
						   } else {
							 _e('Enter Email', 'ms-plugins');
						   }
						   ?></div>
  				  <label><?php _e('Select the server where the mailbox is located', 'ms-plugins'); ?>
  					:</label><br>
  				  <select class="form-control smtp-server-select"
  						  onchange="msweb.plugins.msSubscribe.onchangeServer(this.value)">
  					<option value=""><?php _e('Choose server', 'ms-plugins'); ?></option>
  					<option disabled></option>
  					<option
  					  value="yandex"<?php
						if ($param['smtp']['smtp_server'] == 'yandex') {
						  echo 'selected=" selected"';
						}
						?>>
  					  Yandex.ru
  					</option>
  					<option
  					  value="mail"<?php
						if ($param['smtp']['smtp_server'] == 'mail') {
						  echo 'selected=" selected"';
						}
						?>>
  					  Mail.ru
  					</option>
  					<option
  					  value="google"<?php
						if ($param['smtp']['smtp_server'] == 'google') {
						  echo 'selected=" selected"';
						}
						?>>
  					  Google
  					</option>
  					<option
  					  value="other"<?php
						if ($param['smtp']['smtp_server'] == 'other') {
						  echo 'selected=" selected"';
						}
						?>>
						  <?php _e('Other', 'ms-plugins'); ?>
  					</option>
  				  </select>
  				  <div class="col-sm-12 msweb_ms_subscriber-smtp-server-other-options">
  					<label><?php _e('Specify the server', 'ms-plugins'); ?></label>
  					<div ms-input name="smtp-server-other" class="form-control"
  						 placeholder="<?php
						   if (!empty($param['smtp']['smtp_other_server'])) {
							 echo $param['smtp']['smtp_other_server'];
						   } else {
							 _e('For example, smtp.yandex.ru', 'ms-plugins');
						   }
						   ?>"><?php
							 if (!empty($param['smtp']['smtp_other_server'])) {
							   echo $param['smtp']['smtp_other_server'];
							 } else {
							   _e('For example, smtp.yandex.ru', 'ms-plugins');
							 }
							 ?></div>
  					<label><?php _e('Specify the port', 'ms-plugins'); ?></label>
  					<div ms-input name="smtp-server-other-port" class="form-control"
  						 placeholder="<?php
						   if (!empty($param['smtp']['smtp_other_server_port'])) {
							 echo $param['smtp']['smtp_other_server_port'];
						   } else {
							 _e('For example, 465', 'ms-plugins');
						   }
						   ?>"><?php
							 if (!empty($param['smtp']['smtp_other_server_port'])) {
							   echo $param['smtp']['smtp_other_server_port'];
							 } else {
							   _e('For example, 465', 'ms-plugins');
							 }
							 ?></div>
  				  </div>
  				  <label>Пароль</label>
  				  <div ms-input name="smtp-password" class="form-control"
  					   placeholder="<?php
						 if (!empty($param['smtp']['smtp_password'])) {
						   echo $param['smtp']['smtp_password'];
						 } else {
						   _e('Password', 'ms-plugins');
						 }
						 ?>"><?php
						   if (!empty($param['smtp']['smtp_password'])) {
							 echo $param['smtp']['smtp_password'];
						   } else {
							 _e('Password', 'ms-plugins');
						   }
						   ?></div>


  				</div>
  				<p>
  				  <button class="btn btn-success"
  						  onclick="msweb.plugins.msSubscribe.saveEmail();"><?php _e('Save SMTP Settings', 'ms-plugins'); ?></button>
  				  <span class="save-email-loader-container"></span></p>
  				<div class="alert alert-success hidden" data-on-save-email-mess>
  				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  					<span aria-hidden="true">&times;</span>
  				  </button>
					<?php _e('An email was sent to the site administrator\'s mailbox.', 'ms-plugins'); ?>
  				  <b
  					data-subscriber-smtp-email></b> <?php _e('for check. If the letter was not delivered, check the correctness of the specified data or try to specify another mail.', 'ms-plugins'); ?>
  				</div>
  				<div class="alert alert-danger hidden" data-on-save-email-error>
  				</div>
  			  </div>
  			</div>
  		  </div>

  		  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

  			<div class="panel panel-primary">
  			  <div class="panel-heading minimized" role="tab" id="headingTwo" data-toggle="collapse"
  				   targetId="collapseThree">
  				<h5 class="panel-title">
  				  <a class="collapsed" data-parent="#accordion" aria-expanded="false"
  					 aria-controls="collapseThree">
						 <?php _e('Widget output', 'ms-plugins'); ?>
  				  </a>
  				</h5>
  			  </div>
  			  <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
  				   aria-labelledby="headingThree">
  				<div class="row ms-subscriber-admin-row" style="display:none;">
  				  <label><input type="checkbox" <?php if (Main::getOption('simpleform')) echo ' checked'; ?>> <?php _e('Use simple form, calling by shortcode'); ?></label>
  				</div>
  				<div class="row ms-subscriber-admin-row">
					<?php if (!$param['active']) { ?>
					  <div class="col-sm-12">
						<div
						  class="alert alert-warning"><?php _e('This functionality available in premium version. It cost', 'ms-plugins'); ?> <?php echo Main::getOption('price'); ?> <?php _e('RUB', 'ms-plugins'); ?>
						  <a href="<?php echo Main::getWebHref(array('action' => 'buy', 'plugin' => 'ms_subscriber', 'domain' => $_SERVER['SERVER_NAME'], 'callback_url' => get_site_url() . '?ms_subscriber_activate_callback=1')); ?>"><?php _e('Get upgrade', 'ms-plugins'); ?></a>
						</div>
					  </div>
					<?php } ?>
  				  <div class="col-sm-6">
					  <?php __('Choose widget color', 'ms-plugins'); ?>
  					<label><?php _e('Minimized background', 'ms-plugins'); ?></label><br>
  					<input name="color_min_background" type="text" value="<?php echo $param['widget_opts']['color_min_background']; ?>"/>
  					<label><?php _e('Minimized text color', 'ms-plugins'); ?></label><br>
  					<input name="color_min_text" type="text" value="<?php echo $param['widget_opts']['color_min_text']; ?>"/>
  					<label><?php _e('Maximized background', 'ms-plugins'); ?></label><br>
  					<input name="color_max_background" type="text" value="<?php echo $param['widget_opts']['color_max_background']; ?>"/>
  					<label><?php _e('Maximized text color', 'ms-plugins'); ?></label><br>
  					<input name="color_max_text" type="text" value="<?php echo $param['widget_opts']['color_max_text']; ?>"/>

  					<label><?php _e('Maximized button background', 'ms-plugins'); ?></label><br>
  					<input name="color_max_background_button" type="text" value="<?php echo $param['widget_opts']['color_max_background_button']; ?>"/>
  					<label><?php _e('Maximized button text color', 'ms-plugins'); ?></label><br>
  					<input name="color_max_button_text" type="text" value="<?php echo $param['widget_opts']['color_max_button_text']; ?>"/>
  				  </div>

  				  <div class="col-sm-6">
  					<div class="col-sm-12" ms-subscriber-widget-for-setting></div>
  					<div class="col-sm-12">
						<?php // todo доп опции менять текст  ?>
  					</div>
  				  </div>

  				</div>
  				<div class="row ms-subscriber-admin-row">
					<?php if (!$param['active']) { ?>
					  <div class="col-sm-12">
						<div
						  class="alert alert-warning"><?php _e('This functionality available in premium version. It cost', 'ms-plugins'); ?> <?php echo Main::getOption('price'); ?> <?php _e('RUB', 'ms-plugins'); ?>
						  <a href="<?php echo Main::getWebHref(array('action' => 'buy', 'plugin' => 'ms_subscriber', 'domain' => $_SERVER['SERVER_NAME'], 'callback_url' => get_site_url() . '?ms_subscriber_activate_callback=1')); ?>"><?php _e('Get upgrade', 'ms-plugins'); ?></a>
						</div>
					  </div>
					<?php } ?>
  				  <div class="col-sm-12">
					  <?php _e('By default widget is shown on all pages', 'ms-plugins'); ?><br>
  					<label><input type="checkbox"
  								  id="ms-subscriber-use-only-on-page" <?php
									if ($param['use_on_same']['use_on_same']) {
									  echo 'checked';
									}
									?>> <?php _e('Show widget on the same page', 'ms-plugins'); ?>
  					</label>
  					<div
  					  class="msweb-plugins-subscriber-use-only-on-page<?php
						if ($param['use_on_same']['use_on_same']) {
						  echo ' visible';
						}
						?>">
  					  <label><?php _e('Enter page id', 'ms-plugins'); ?></label><br>
						<?php _e('You can specify multiple id\'s, separated by commas. For example: 12, 151, 214', 'ms-plugins'); ?>
  					  <input msweb-plugins-subscriber-output-page-id class="form-control"
  							 placeholder="For example, 123" <?php
							   if (!empty($param['use_on_same']['same_page_id'])) {
								 echo 'value="' . $param['use_on_same']['same_page_id'] . '"';
							   }
							   ?>><br>
  					  <b><?php _e('OR', 'ms-plugins'); ?></b><br>
  					  <label><?php _e('Start typing the page title', 'ms-plugins'); ?></label><br>
  					  <input msweb-plugins-subscriber-output-page-title class="form-control"
  							 placeholder=""><br>
  					  <div class="msweb-plugins-subscriber-output-find-page-message"></div>
  					</div>
  				  </div>

  				</div>

  				<div class="col-sm-12 text-center" style="padding: 20px">
  				  <button class="btn btn-success" onclick="msweb.plugins.msSubscribe.saveWidgetOutput()"><?php _e('Save output', 'ms-plugins'); ?></button>
  				</div>

  			  </div>
  			</div>
  		  </div>

  		  <!-- subscribers list -->
  		  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

  			<div class="panel panel-primary">
  			  <div class="panel-heading minimized" role="tab" id="headingFour" data-toggle="collapse"
  				   targetId="collapseFour">
  				<h5 class="panel-title">
  				  <a class="collapsed" data-parent="#accordion" aria-expanded="false"
  					 aria-controls="collapseFour">
						 <?php _e('Subscribers', 'ms-plugins'); ?>
  				  </a>
  				</h5>
  			  </div>
  			  <div id="collapseFour" class="panel-collapse collapse" role="tabpanel"
  				   aria-labelledby="headingFour">
  				<div class="row ms-subscriber-admin-row">
					<?php echo Main::getSubscribersList(); ?>
  				</div>
  			  </div>
  			</div>
  		  </div>
  		  <!-- end subscribers list -->

  		</div>
  	  </div>
  	</div>

    </div>
  </div>

<?php } ?>
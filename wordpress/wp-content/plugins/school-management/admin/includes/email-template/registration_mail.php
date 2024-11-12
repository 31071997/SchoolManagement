<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>

<div class="panel-body">
<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
	<div class="form-group">
		<label for="first_name" class="col-sm-3 control-label">Email Subject <span class="require-field">*</span></label>
		<div class="col-md-8">
			<input class="form-control validate[required]" name="registration_title" id="registration_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo get_option('registration_title'); ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="first_name" class="col-sm-3 control-label">Emails Sent to Student When A Student Register  <span class="require-field">*</span></label>
		<div class="col-md-8">
			<textarea name="registratoin_mailtemplate_content" class="form-control validate[required]"><?php echo get_option('registration_mailtemplate');?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-md-8">
			<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>
			<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>
			<label><strong>{{user_name}} - </strong><?php esc_attr_e('User name of student','school-mgt');?></label><br>
			<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class name of student','school-mgt');?></label><br>
			<label><strong>{{email}} - </strong><?php esc_attr_e('Email of student','school-mgt');?></label><br>
			<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label>				
		</div>
	</div>
	<div class="col-sm-offset-3 col-sm-8">        	
        <input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_registration_template" class="btn btn-success"/>
    </div>
</form>
</div>
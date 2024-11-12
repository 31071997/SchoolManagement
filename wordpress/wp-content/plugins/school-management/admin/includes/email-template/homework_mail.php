<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>

<div class="panel-body">
<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
<div class="form-group">
	<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label">Email Subject <span class="require-field">*</span></label>
	<div class="col-md-8">
		<input id="student_activation_title" class="form-control validate[required]" name="homework_title" id="homework_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo get_option('homework_title'); ?>">
	</div>
</div>
<div class="form-group">
	<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label">Emails Sent to Parents When A Give Homework <span class="require-field">*</span></label>
	<div class="col-md-8">
		<textarea id="fee_payment_mailcontent" name="fee_payment_mailcontent" class="form-control validate[required]"><?php echo get_option('fee_payment_mailcontent');?></textarea>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-md-8">
		<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				
		<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name','school-mgt');?></label><br>
		<label><strong>{{parent_name}} - </strong><?php esc_attr_e('The parent name','school-mgt');?></label><br>
		<label><strong>{{roll_number}} - </strong><?php esc_attr_e('Student roll number','school-mgt');?></label><br>
		<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class name of student','school-mgt');?></label><br>
		<label><strong>{{fee_type}} - </strong><?php esc_attr_e('Fees Type','school-mgt');?></label><br>
		<label><strong>{{fee_amount}} - </strong><?php esc_attr_e('Fee Amount','school-mgt');?></label><br>
		<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>
		<label><strong>{{start_year}} - </strong><?php esc_attr_e('Start Year','school-mgt');?></label><br>
		<label><strong>{{end_year}} - </strong><?php esc_attr_e('End Year','school-mgt');?></label><br>			
	</div>
</div>
<div class="col-sm-offset-3 col-sm-8">        	
    <input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_homework_mailtemplate" class="btn btn-success"/>
</div>
</form>
</div>
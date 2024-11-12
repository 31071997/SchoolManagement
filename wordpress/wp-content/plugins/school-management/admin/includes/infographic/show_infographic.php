<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/infographic.js'; ?>" ></script>

<div class="panel-body">
<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
	
		<div class="form-group">
			<label class="col-sm-3 control-label" for="smgt_enable_total_student"><?php esc_attr_e('Enable To View Total Students','school-mgt');?></label>
			<div class="col-sm-8">
				<div class="checkbox">
					<label>
	              		<input type="checkbox" name="smgt_enable_total_student"  value="1" <?php echo checked(get_option('smgt_enable_total_student'),1);?>/><?php esc_attr_e('Enable','school-mgt');?>
	              </label>
              </div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="smgt_enable_total_teacher"><?php esc_attr_e('Enable To View Total Teacher','school-mgt');?></label>
			<div class="col-sm-8">
				<div class="checkbox">
					<label>
	              		<input type="checkbox" name="smgt_enable_total_teacher"  value="1" <?php echo checked(get_option('smgt_enable_total_teacher'),1);?>/><?php esc_attr_e('Enable','school-mgt');?>
	              </label>
              </div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="smgt_enable_total_parent"><?php esc_attr_e('Enable To View Total Parent','school-mgt');?></label>
			<div class="col-sm-8">
				<div class="checkbox">
					<label>
	              		<input type="checkbox" name="smgt_enable_total_parent"  value="1" <?php echo checked(get_option('smgt_enable_total_parent'),1);?>/><?php esc_attr_e('Enable','school-mgt');?>
	              </label>
              </div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="smgt_enable_total_attendance"><?php esc_attr_e('Enable To View Total Attendance','school-mgt');?></label>
			<div class="col-sm-8">
				<div class="checkbox">
					<label>
	              		<input type="checkbox" name="smgt_enable_total_attendance"  value="1" <?php echo checked(get_option('smgt_enable_total_attendance'),1);?>/><?php esc_attr_e('Enable','school-mgt');?>
	              </label>
              </div>
			</div>
		</div>
		<div class="col-sm-offset-3 col-sm-8">
        	<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_infographic" class="btn btn-success"/>
        </div>
</form>
</div>
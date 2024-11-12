
 <div class="panel-body">
    <form name="upload_form" action="" method="post" class="form-horizontal" id="upload_form" enctype="multipart/form-data">
    <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
	<input type="hidden" name="action" value="<?php echo $action;?>">
	<input type="hidden" name="role" value="<?php echo $role;?>"  />
	 
	<div class="form-group row mb-3">
		<label class="col-sm-2 control-label col-form-label text-md-end" for="city_name"><?php esc_attr_e('Select CSV file','school-mgt');?><span class="require-field">*</span></label>
		<div class="col-sm-8">
			<input id="csv_file" type="file" class="validate[required] csvfile_width d-inline" name="csv_file">
		</div>
	</div>
	<div class="offset-sm-2 col-sm-8">
      	<input type="submit" value="<?php esc_attr_e('Upload CSV File','school-mgt');?>" name="upload_teacher_csv_file" class="btn btn-success"/>
    </div>
	<?php wp_nonce_field( 'upload_csv_nonce' ); ?>
</form>
</div>
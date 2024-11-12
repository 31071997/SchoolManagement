 <div class="panel-body">
    <form name="upload_form" action="" method="post" class="form-horizontal" id="upload_form" enctype="multipart/form-data">
    <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
	<input type="hidden" name="action" value="<?php echo $action;?>">
	<input type="hidden" name="role" value="<?php echo $role;?>"  />
	<div class="form-group row mb-3">
		<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
		<div class="col-sm-8">
	        <select name="class_name" class="form-control validate[required]" id="class_list">
              	<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
                <?php
				foreach(mj_smgt_get_allclass() as $classdata)
				{  
				?>
					<option value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="form-group row mb-3">
		<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
		<div class="col-sm-8">		
            <select name="class_section" class="form-control" id="class_section">
                <option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>               
            </select>
		</div>
	</div>	
		
	<div class="form-group row mb-3">
		<label class="col-sm-2 control-label col-form-label text-md-end" for="city_name"><?php esc_attr_e('Select CSV file','school-mgt');?><span class="require-field">*</span></label>
		<div class="col-sm-8">
			<input id="csv_file" type="file" class="validate[required] csvfile_width d-inline" name="csv_file">
		</div>
	</div>
	<div class="offset-sm-2 col-sm-8">
      	<input type="submit" value="<?php esc_attr_e('Upload CSV File','school-mgt');?>" name="upload_csv_file" class="btn btn-success"/>
    </div>
	<?php wp_nonce_field( 'upload_teacher_admin_nonce' ); ?>
</form>
</div>
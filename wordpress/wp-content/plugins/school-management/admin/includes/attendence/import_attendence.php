<div class="panel-body"><!-- panel-body --> 
    <form name="upload_form" action="" method="post" class="form-horizontal" id="upload_form" enctype="multipart/form-data">
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="form-body user_form">
			<div class="row">
				<div class="col-md-5">	
					<div class="form-group input">
						<div class="col-md-12 form-control res_rtl_height_50px">	
							<label class="custom-control-label custom-top-label ml-2 margin_left_30px" for="city_name"><?php esc_attr_e('Select CSV file','school-mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-12">
								<input id="csv_file" type="file" class="col-md-12 validate[required] csvfile_width" name="csv_file">			 
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<input type="submit" value="<?php esc_attr_e('Upload CSV File','school-mgt');?>" name="upload_attendance_csv_file" class="col-sm-6 save_btn"/>
				</div>
			</div>
		</div>
	</form>
</div><!-- panel-body --> 
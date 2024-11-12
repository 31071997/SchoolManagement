<div class="panel-body"><!-- panel-body --> 
    <form name="upload_form" action="" method="post" class="form-horizontal" id="upload_form" enctype="multipart/form-data">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">		
		<div class="col-sm-12">        	
        	<input type="submit" value="<?php esc_attr_e('Export Student Attendance','school-mgt');?>" name="export_attendance_in_csv" class="col-sm-6 save_att_btn"/>
        </div>
	</form>
</div><!-- panel-body --> 
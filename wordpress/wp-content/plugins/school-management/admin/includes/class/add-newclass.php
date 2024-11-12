<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/class.js'; ?>" ></script>
<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$classdata= mj_smgt_get_class_by_id($_REQUEST['class_id']);
	} 
?>
       
<div class="panel-body"><!-------- penal body -------->
	<form name="class_form" action="" method="post" class="form-horizontal" id="class_form"><!------- form Start --------->
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Class Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">	
				<div class="col-md-6">
				<div class="form-group input">
					<div class="col-md-12 form-control">
						<input id="class_name" class="form-control validate[required]" maxlength="50" type="text" value="<?php if($edit){ echo $classdata->class_name;}?>" name="class_name">
						<label for="userinput1" class=""><?php esc_html_e('Class Name','school-mgt');?><span class="required">*</span></label>
					</div>
				</div>
			</div>
				<div class="col-md-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="class_num_name" class="form-control validate[required,min[0],maxSize[4]] text-input" oninput="this.value = Math.abs(this.value)"  type="number" value="<?php if($edit){ echo $classdata->class_num_name;}?>" name="class_num_name" >
							<label for="userinput1" class=""><?php esc_html_e('Class Numeric Value','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<?php wp_nonce_field( 'save_class_admin_nonce' ); ?>	
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="class_capacity" oninput="this.value = Math.abs(this.value)" class="form-control validate[required,min[0],maxSize[4]]" type="number" value="<?php if($edit){ echo $classdata->class_capacity;}?>" name="class_capacity">
							<label for="userinput1" class=""><?php esc_html_e('Student Capacity','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-body user_form">
			<div class="row">	
				<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Class','school-mgt'); }else{ esc_attr_e('Add Class','school-mgt');}?>" name="save_class" class="save_btn" />
				</div> 
			</div>        
		</div>               
	</form> <!------- form end --------->
</div><!-------- penal body -------->
<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$user_access=mj_smgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		mj_smgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
?>
<script type="text/javascript">
    jQuery(document).ready(function($)
    {
        "use strict";	
        $('#select_data').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
    });
</script>
<?php 
// This is Class at admin side!!!!!!!!! 
if(isset($_REQUEST['migration']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_migration_admin_nonce' ) )
	{
		$current_class = mj_smgt_onlyNumberSp_validation($_REQUEST['current_class']);
		$next_class = mj_smgt_onlyNumberSp_validation($_REQUEST['next_class']);
		if($current_class != $next_class)
		{
		$exam_id = mj_smgt_onlyNumberSp_validation($_REQUEST['exam_id']);
		$passing_marks = mj_smgt_onlyNumberSp_validation($_REQUEST['passing_marks']);
		$student_fail = mj_smgt_fail_student_list($current_class,$next_class,$exam_id,$passing_marks);
		$update = mj_smgt_migration($current_class,$next_class,$exam_id,$student_fail);
		  wp_redirect ( home_url().'?dashboard=user&page=migration&message=1');
		}
		else
		{ 
			wp_redirect ( home_url().'?dashboard=user&page=migration&message=2');
		}
	}
}
?>
<div class="page-inner min-h-1631-px"><!--------- Page Inner ------->
	<div  id="" class="marks_list main_list_margin_5px">
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Migration Completed Successfully.','school-mgt');
				break;	
			case '2';
				$message_string = esc_attr__('Current Class and Next Class can not be same.','school-mgt');
				break;
		}
		
		if($message)
		{ ?>
			<div id="message" style="margin-top:10px;" class="alert_msg alert alert-success alert-dismissible " role="alert">
                <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
                </button>
                <p><?php echo $message_string;?></p>
            </div>
			<?php 
		} ?>
	
		<div class="panel-white"><!--------- penal White ------->
			<div class="panel-body margin_top_20px padding_top_25px_res">  <!--------- penal body ------->
				<?php
				$tablename="marks";
				?>	 
				<div class="panel-body"> <!--------- penal body ------->
					<form method="post" id="select_data">  
						<div class="header">	
							<h3 class="first_hed"><?php esc_html_e('Migration Information','school-mgt');?></h3>
						</div>
						<div class="form-body user_form">
							<div class="row">
								<div class="col-md-6 input">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Current Class','school-mgt');?><span class="require-field">*</span></label>
									<select name="current_class"  id="current_class" class="line_height_30px form-control validate[required] text-input">
										<option value=" "><?php esc_attr_e('Select Current Class','school-mgt');?></option>
										<?php
												foreach(mj_smgt_get_allclass() as $classdata)
												{  
												?>
												<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
										<?php }?>
									</select>                   
								</div>
								<div class="col-md-6 input error_msg_left_margin">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Next Class Name','school-mgt');?><span class="require-field">*</span></label>
									<select name="next_class"  id="next_class" class="line_height_30px form-control validate[required] text-input">
										<option value=" "><?php esc_attr_e('Select Class','school-mgt');?></option>
										    <?php
											global $wpdb;
											$table_name = $wpdb->prefix .'smgt_class';
											$classdata_new =$wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
                                            foreach($classdata_new as $classdata)
                                            {  
												?>
												<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
										        <?php 
                                            }?>
									</select>                 
								</div>
								<div class="col-md-6 input">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
									<?php
									$tablename="exam"; 
									$retrieve_class = mj_smgt_get_all_data($tablename);
								
									?>
									<select name="exam_id" class="line_height_30px form-control validate[required] text-input">
										<option value=" "><?php esc_attr_e('Select Exam','school-mgt');?></option>
										<?php
											foreach($retrieve_class as $retrieved_data)
											{
												
											?>
										<option value="<?php echo $retrieved_data->exam_id;?>"><?php echo $retrieved_data->exam_name;?></option>
											<?php	
											}
											?>
									</select>                
								</div>
								<?php wp_nonce_field( 'save_migration_admin_nonce' ); ?>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input type="number" name="passing_marks" value=""  class="form-control validate[required,min[0],maxSize[5]]">
											<label class="" for="isbn"><?php esc_attr_e('Passing Marks','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-body user_form">
							<div class="row">
								<div class="form-group col-md-6 button_possition_padding ">
									<input type="submit" value="<?php esc_attr_e('Go','school-mgt');?>" name="migration"  class="btn btn-info save_btn"/>
								</div>
							</div>
						</div>
					</form>
				</div><!--------- penal body ------->
				<div class="clearfix"> </div>
			</div><!--------- penal body ------->
		</div><!--------- penal White ------->
	</div>    
</div><!--------- page Inner ------->
<script type="text/javascript">
	jQuery(document).ready(function($){
		"use strict";	
		$('#setting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	});
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
//--------------- ACCESS WISE ROLE -----------//
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
if(isset($_POST['save_setting']))
{
	$optionval=mj_smgt_option();
	foreach($optionval as $key=>$val)
	{
		if(isset($_POST[$key]))
		{
			$result=update_option( $key, $_POST[$key]);
		}
	}
	//	UPDATE GENERAL SETTINGS OPTION
	if(isset($_REQUEST['smgt_paymaster_pack']))
	{
		update_option( 'smgt_paymaster_pack', 'yes' );
	}
	else
	{
		update_option( 'smgt_paymaster_pack', 'no' );
	}
	//	UPDATE GENERAL SETTINGS OPTION
	if(isset($_REQUEST['smgt_mail_notification']))
	{
		update_option( 'smgt_mail_notification',1 );
	}
	else
	{
		update_option( 'smgt_mail_notification',0);
	}
	if(isset($_REQUEST['parent_send_message']))
		update_option( 'parent_send_message', 1 );
	else
		update_option( 'parent_send_message', 0 );
	
	if(isset($_REQUEST['student_send_message']))
		update_option( 'student_send_message', 1 );
	else
		update_option( 'student_send_message', 0 );
	if(isset($_REQUEST['student_approval']))
		update_option( 'student_approval', 1 );
	else
		update_option( 'student_approval', 0 );
	if(isset($_REQUEST['smgt_enable_sandbox']))
			update_option( 'smgt_enable_sandbox', 'yes' );
		else 
			update_option( 'smgt_enable_sandbox', 'no' );
	if(isset($_REQUEST['smgt_enable_virtual_classroom']))
	{
		update_option( 'smgt_enable_virtual_classroom', 'yes' );
	}
	else 
	{
		update_option( 'smgt_enable_virtual_classroom', 'no' );
	}
	if(isset($_REQUEST['smgt_enable_virtual_classroom_reminder']))
	{
		update_option( 'smgt_enable_virtual_classroom_reminder', 'yes' );
	}
	else 
	{
		update_option( 'smgt_enable_virtual_classroom_reminder', 'no' );
	}
	
	if(isset($_REQUEST['smgt_enable_sms_virtual_classroom_reminder']))
	{
		update_option( 'smgt_enable_sms_virtual_classroom_reminder', 'yes' );
	}
	else 
	{
		update_option( 'smgt_enable_sms_virtual_classroom_reminder', 'no' );
	}

	if(isset($_REQUEST['smgt_teacher_manage_allsubjects_marks']))
	{
			update_option( 'smgt_teacher_manage_allsubjects_marks', 'yes' );
	}
	else
	{ 
		update_option( 'smgt_teacher_manage_allsubjects_marks', 'no' );	
	}
	//Principal Singnature
	if(isset($_REQUEST['smgt_principal_signature']))
	{
		update_option( 'smgt_principal_signature', $_REQUEST['smgt_principal_signature'] );
	}
	
	wp_redirect( admin_url() . 'admin.php?page=smgt_gnrl_settings&message=1');
}
?>
<!-- Nav tabs -->
<div class="panel-body panel-white">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		   case '1':
			$message_string = esc_attr__('Settings Updated Successfully.','school-mgt');
			break;
	}
	
	if($message)
	{ ?>
		<div class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<?php echo $message_string;?>
		</div>
  		<?php 
   	}	
 	?>
 	<div class="panel-body">
		<!-- <h2>
        	<?php echo esc_html( esc_attr__( 'General Settings', 'school-mgt')); ?>
        </h2> -->
		<div class="panel-body">
        	<form name="student_form" action="" method="post" class="form-horizontal" id="setting_form">
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e(' General Settings','school-mgt');?></h3>
				</div>
				<div class="form-body user_form">
					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_school_name" class="form-control validate[required,custom[popup_category_validation]]" type="text" maxlength="100" value="<?php echo get_option( 'smgt_school_name' );?>"  name="smgt_school_name">
									<label class="" for="smgt_school_name"><?php esc_attr_e('School Name','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_staring_year" class="form-control validate[minSize[4],maxSize[4],min[0]]" min="1" step="1" type="number" value="<?php echo get_option( 'smgt_staring_year' );?>"  name="smgt_staring_year">
									<label class="" for="smgt_staring_year"><?php esc_attr_e('Starting Year','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_school_address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text" value="<?php echo get_option( 'smgt_school_address' );?>"  name="smgt_school_address">
									<label class="" for="smgt_school_address"><?php esc_attr_e('School Address','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_contact_number" class="form-control  validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text" value="<?php echo get_option( 'smgt_contact_number' );?>"  name="smgt_contact_number">
									<label class="" for="smgt_contact_number"><?php esc_attr_e('Official Phone Number','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						
						<div class="col-md-6 input">
							<label class="ml-1 custom-top-label top" for="smgt_contry"><?php esc_attr_e('Country','school-mgt');?></label>
							<?php 
							$url = content_url().'/plugins/school-management/countrylist.xml';
							if(mj_smgt_get_remote_file($url))
							{
								$xml =simplexml_load_string(mj_smgt_get_remote_file($url));
							}
							else 
							{
								die("Error: Cannot create object");
							}
						
							?>
							<select name="smgt_contry" class="line_height_30px form-control validate[required] max_width_100" id="smgt_contry">
								<option value=""><?php esc_attr_e('Select Country','school-mgt');?></option>
								<?php
									foreach($xml as $country)
									{  
									?>
									<option value="<?php echo $country->name;?>" <?php selected(get_option( 'smgt_contry' ), $country->name);  ?>><?php echo $country->name;?></option>
								<?php }?>
							</select> 
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text" value="<?php echo get_option( 'smgt_email' );?>"  name="smgt_email">
									<label class="" for="smgt_email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_notification_fcm_key" class="form-control text-input" type="text" value="<?php echo get_option( 'smgt_notification_fcm_key' );?>"  name="smgt_notification_fcm_key">
									<label class="" for="smgt_notification_fcm_key"><?php esc_attr_e('Notification FCM Key','school-mgt');?></label>
								</div>
							</div>
						</div>

						<div class="col-md-6 input">
							<label class="ml-1 custom-top-label top" for="smgt_datepicker_format"><?php esc_attr_e('Date Format','school-mgt');?>
							</label>
							<?php $date_format_array = mj_smgt_datepicker_dateformat();
							if(get_option( 'smgt_datepicker_format' ))
							{
								$selected_format = get_option( 'smgt_datepicker_format' );
							}
							else
								$selected_format = 'Y-m-d';
							?>
							<select id="smgt_datepicker_format" class="line_height_30px form-control max_width_100" name="smgt_datepicker_format">
								<?php 
								foreach($date_format_array as $key=>$value)
								{
									echo '<option value="'.$value.'" '.selected($selected_format,$value).'>'.$value.'</option>';
								}
								?>
							</select>
						</div>

						
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">
									<label class="custom-control-label custom-top-label ml-2" for="smgt_email"><?php esc_attr_e('System Logo','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-12 display_flex">
										<input type="text" id="smgt_user_avatar_url" name="smgt_system_logo" class="image_path_dots form-control validate[required]" value="<?php  echo get_option( 'smgt_school_logo' ); ?>" readonly />
										<input id="upload_user_avatar_button" type="button" class="button upload_image_btn"  style="float: right;" value="<?php esc_attr_e( 'Upload image', 'school-mgt' ); ?>" />
									
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div id="upload_user_avatar_preview">
										<img class="image_preview_css" src="<?php  echo get_option( 'smgt_system_logo' ); ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">
									<label class="custom-control-label custom-top-label ml-2" for="smgt_cover_image"><?php esc_attr_e('Profile Cover Image','school-mgt');?></label>
									<div class="col-sm-12 display_flex">
										<input type="text" id="smgt_school_background_image" name="smgt_school_background_image" class="image_path_dots form-control" value="<?php  echo get_option( 'smgt_school_logo' ); ?>" readonly />	
										<input id="upload_image_button" type="button" class="button upload_user_cover_button upload_image_btn" style="float: right;" value="<?php esc_attr_e( 'Upload Cover Image', 'school-mgt' ); ?>" />
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 mt-3">
									<div id="upload_school_cover_preview min-h-100-px mt-5-px">
										<img class="other_data_logo" src="<?php  echo get_option( 'smgt_school_logo' ); ?>" />
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-body user_form">
					<div class="row">
						<div class="header">	
							<h3 class="first_hed"><?php esc_attr_e('Payment Setting','school-mgt');?></h3>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="">
											<label class="custom-top-label" for="smgt_enable_sandbox"><?php esc_attr_e('Enable Sandbox','school-mgt');?></label>
											<input type="checkbox" class="margin_right_checkbox_css" name="smgt_enable_sandbox"  value="1" <?php echo checked(get_option('smgt_enable_sandbox'),'yes');?>/>
											<lable><?php esc_attr_e('Enable','school-mgt');?></label>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input margin_0px_rtl">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_paypal_email" class="form-control validate[custom[email]] text-input" maxlength="100" type="text" value="<?php echo get_option( 'smgt_paypal_email' );?>"  name="smgt_paypal_email">
									<label class="" for="smgt_paypal_email"><?php esc_attr_e('Paypal Email Id','school-mgt');?></label>
								</div>
							</div>
						</div>

						<div class="col-md-6 input">
							<div class="row">
								<div class="col-md-11">
									<label class="ml-1 custom-top-label top" for="smgt_currency_code"><?php esc_attr_e('Select Currency','school-mgt');?></label>
					
									<select name="smgt_currency_code" class=" form-control text-input max_width_100">
										<option value=""> <?php esc_attr_e('Select Currency','school-mgt');?></option>
										<option value="AUD" <?php echo selected(get_option( 'smgt_currency_code' ),'AUD');?>>
										<?php esc_attr_e('Australian Dollar','school-mgt');?></option>
										<option value="BRL" <?php echo selected(get_option( 'smgt_currency_code' ),'BRL');?>>
										<?php esc_attr_e('Brazilian Real','school-mgt');?> </option>
										<option value="CAD" <?php echo selected(get_option( 'smgt_currency_code' ),'CAD');?>>
										<?php esc_attr_e('Canadian Dollar','school-mgt');?></option>
										
										<option value="CZK" <?php echo selected(get_option( 'smgt_currency_code' ),'CZK');?>>
										<?php esc_attr_e('Czech Koruna','school-mgt');?></option>
										
										<option value="KHR" <?php echo selected(get_option( 'smgt_currency_code' ),'KHR');?>>
										<?php esc_attr_e('Cambodia Riel','school-mgt');?></option>
										
										<option value="DKK" <?php echo selected(get_option( 'smgt_currency_code' ),'DKK');?>>
										<?php esc_attr_e('Danish Krone','school-mgt');?></option>
										<option value="EUR" <?php echo selected(get_option( 'smgt_currency_code' ),'EUR');?>>
										<?php esc_attr_e('Euro','school-mgt');?></option>
										
										<option value="GHC" <?php echo selected(get_option( 'smgt_currency_code' ),'GHC');?>>
										<?php esc_attr_e('Cedis','school-mgt');?></option>
										
										<option value="HKD" <?php echo selected(get_option( 'smgt_currency_code' ),'HKD');?>>
										<?php esc_attr_e('Hong Kong Dollar','school-mgt');?></option>
										<option value="HUF" <?php echo selected(get_option( 'smgt_currency_code' ),'HUF');?>>
										<?php esc_attr_e('Hungarian Forint','school-mgt');?> </option>
										<option value="INR" <?php echo selected(get_option( 'smgt_currency_code' ),'INR');?>>
										<?php esc_attr_e('Indian Rupee','school-mgt');?></option>

										<option value="IDR" <?php echo selected(get_option( 'smgt_currency_code' ),'IDR');?>>
										<?php esc_attr_e('Indonesian Rupiah','school-mgt');?></option>
										
										<option value="PKR" <?php echo selected(get_option( 'smgt_currency_code' ),'PKR');?>>
										<?php esc_attr_e('Pakistan Rupee','school-mgt');?></option>
										
										<option value="ILS" <?php echo selected(get_option( 'smgt_currency_code' ),'ILS');?>>
										<?php esc_attr_e('Israeli New Sheqel','school-mgt');?></option>
										<option value="JPY" <?php echo selected(get_option( 'smgt_currency_code' ),'JPY');?>>
										<?php esc_attr_e('Japanese Yen','school-mgt');?></option>
										<option value="MYR" <?php echo selected(get_option( 'smgt_currency_code' ),'MYR');?>>
										<?php esc_attr_e('Malaysian Ringgit','school-mgt');?></option>
										<option value="MXN" <?php echo selected(get_option( 'smgt_currency_code' ),'MXN');?>>
										<?php esc_attr_e('Mexican Peso','school-mgt');?></option>
										<option value="NOK" <?php echo selected(get_option( 'smgt_currency_code' ),'NOK');?>>
										<?php esc_attr_e('Norwegian Krone','school-mgt');?></option>
										<option value="NZD" <?php echo selected(get_option( 'smgt_currency_code' ),'NZD');?>>
										<?php esc_attr_e('New Zealand Dollar','school-mgt');?></option>
										<option value="PHP" <?php echo selected(get_option( 'smgt_currency_code' ),'PHP');?>>
										<?php esc_attr_e('Philippine Peso','school-mgt');?></option>
										<option value="PLN" <?php echo selected(get_option( 'smgt_currency_code' ),'PLN');?>>
										<?php esc_attr_e('Polish Zloty','school-mgt');?></option>
										<option value="GBP" <?php echo selected(get_option( 'smgt_currency_code' ),'GBP');?>>
										<?php esc_attr_e('Pound Sterling','school-mgt');?></option>
										<option value="SGD" <?php echo selected(get_option( 'smgt_currency_code' ),'SGD');?>>
										<?php esc_attr_e('Singapore Dollar','school-mgt');?></option>
										<option value="SEK" <?php echo selected(get_option( 'smgt_currency_code' ),'SEK');?>>
										<?php esc_attr_e('Swedish Krona','school-mgt');?></option>
										<option value="CHF" <?php echo selected(get_option( 'smgt_currency_code' ),'CHF');?>>
										<?php esc_attr_e('Swiss Franc','school-mgt');?></option>
										<option value="TWD" <?php echo selected(get_option( 'smgt_currency_code' ),'TWD');?>>
										<?php esc_attr_e('Taiwan New Dollar','school-mgt');?></option>
										<option value="THB" <?php echo selected(get_option( 'smgt_currency_code' ),'THB');?>>
										<?php esc_attr_e('Thai Baht','school-mgt');?></option>
										<option value="TRY" <?php echo selected(get_option( 'smgt_currency_code' ),'TRY');?>>
										<?php esc_attr_e('Turkish Lira','school-mgt');?></option>
										<option value="USD" <?php echo selected(get_option( 'smgt_currency_code' ),'USD');?>>
										<?php esc_attr_e('U.S. Dollar','school-mgt');?></option>
										<option value="ZAR" <?php echo selected(get_option( 'smgt_currency_code' ),'ZAR');?>>
										<?php esc_attr_e('South African Rand','school-mgt');?></option>
										<option value="NGN" <?php echo selected(get_option( 'smgt_currency_code' ),'NGN');?>>
										<?php esc_attr_e('Nigerian Naira','school-mgt');?></option>
										<option value="BDT" <?php echo selected(get_option( 'smgt_currency_code' ),'BDT');?>>
										<?php esc_attr_e('Bangladeshi Taka','school-mgt');?></option>
									</select>
								</div>
								<div class="col-md-1">
									<span class="fr_currency_font_23 font-23-px"><?php echo mj_smgt_get_currency_symbol();?></span>
								</div>
							</div>
							<span class="description"><?php esc_attr_e('Selected currency might not supported by paypal. Please check with paypal.', 'school-mgt' ); ?></span>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control margin_15px_rtl">
									<div class="row padding_radio">
										<div class="">
											<label for="smgt_mail_notification" class="custom-top-label"><?php esc_attr_e('Mail Notification','school-mgt');?></label>
											<input type="checkbox" class="margin_right_checkbox_css" value="1" <?php echo checked(get_option('smgt_mail_notification'),1);?> name="smgt_mail_notification">
											<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
										</div>												
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-body user_form">
					<div class="row">
						<div class="header">	
							<h3 class="first_hed"><?php esc_attr_e('Virtual Classroom Setting(Zoom)','school-mgt');?></h3>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_virtual_classroom_client_id" class="form-control text-input" type="text" value="<?php echo get_option( 'smgt_virtual_classroom_client_id' );?>"  name="smgt_virtual_classroom_client_id">
									<label class="" for="smgt_virtual_classroom_client_id"><?php esc_attr_e('Client Id','school-mgt');?></label>
								</div>
								<span class="description"><?php esc_attr_e('That will be provided by zoom.', 'school-mgt' ); ?></span>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_virtual_classroom_client_secret_id" class="form-control text-input" type="text" value="<?php echo get_option( 'smgt_virtual_classroom_client_secret_id' );?>"  name="smgt_virtual_classroom_client_secret_id">
									<label class="" for="smgt_virtual_classroom_client_secret_id"><?php esc_attr_e('Client Secret Id','school-mgt');?></label>
								</div>
								<span class="description"><?php esc_attr_e('That will be provided by zoom.', 'school-mgt' ); ?></span>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="">
											<label class="custom-top-label" for="smgt_enable_virtual_classroom"><?php esc_attr_e('Virtual Classroom','school-mgt');?></label>
											<input type="checkbox" class="margin_right_checkbox_css" name="smgt_enable_virtual_classroom"  value="1" <?php echo checked(get_option('smgt_enable_virtual_classroom'),'yes');?>/>
											<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
										</div>												
									</div>
								</div>
							</div>
						</div>


						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="" class="form-control text-input" type="text" value="<?php echo site_url().'?page=callback';?>"  name="" disabled>
									<label class="" for="smgt_virtual_classroom_client_id"><?php esc_attr_e('Redirect URL','school-mgt');?></label>
								</div>
								<span class="description"><?php esc_attr_e('Please copy this Redirect URL and add in your zoom account Redirect URL.', 'school-mgt' ); ?></span>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="">
											<label class="custom-top-label" for="smgt_enable_virtual_classroom_reminder"><?php esc_attr_e('Mail Notification Virtual ClassRoom Reminder','school-mgt');?></label>
											<input id="virtual_classroom_reminder" class="margin_right_checkbox_css" type="checkbox" name="smgt_enable_virtual_classroom_reminder"  value="1" <?php echo checked(get_option('smgt_enable_virtual_classroom_reminder'),'yes');?>/>
											<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
										</div>												
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control ">
									<div class="row padding_radio">
										<div class="">
											<label class="custom-top-label" for="smgt_enable_sms_virtual_classroom_reminder"><?php esc_attr_e('SMS Notification Virtual Class Room Reminder','school-mgt');?></label>
											<input id="virtual_classroom_reminder" class="margin_right_checkbox_css" type="checkbox" name="smgt_enable_sms_virtual_classroom_reminder"  value="1" <?php echo checked(get_option('smgt_enable_sms_virtual_classroom_reminder'),'yes');?>/>
											<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
										</div>												
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-6 form-control width_rtl_100">
									<input id="smgt_virtual_classroom_reminder_before_time" class="form-control" min="0" type="number" onKeyPress="if(this.value.length==2) return false;"  placeholder="<?php esc_html_e('01','school-mgt');?>"value="<?php echo get_option( 'smgt_virtual_classroom_reminder_before_time' );?>"  name="smgt_virtual_classroom_reminder_before_time">
									<label class="" for="smgt_virtual_classroom_reminder_before_time"><?php esc_html_e('Reminder Before Time','school-mgt');?> <?php esc_html_e('(','school-mgt');?><?php esc_html_e('Minute','school-mgt');?><?php esc_html_e(')','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-body user_form">
					<div class="row">
						<div class="header">	
							<h3 class="first_hed"><?php esc_attr_e('Message Setting','school-mgt');?></h3>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="">
											<label for="parent_send_message" class="custom-top-label"><?php esc_attr_e('Parent can send message to class students','school-mgt');?></label>
											<input type="checkbox" class="margin_right_checkbox_css" value="1" <?php echo checked(get_option('parent_send_message'),1);?> name="parent_send_message">
											<lable><?php esc_attr_e('Enable','school-mgt') ?></label>
										</div>												
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="">
											<label for="student_send_message" class="custom-top-label"><?php esc_attr_e(' Student can send message to each other','school-mgt');?></label>
											<input type="checkbox" class="margin_right_checkbox_css" value="1" <?php echo checked(get_option('student_send_message'),1);?> name="student_send_message">
											<lable><?php esc_attr_e('Enable','school-mgt') ?></label>
										</div>												
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-body user_form">
					<div class="row">
						<div class="header">	
							<h3 class="first_hed"><?php esc_attr_e('Student Approval setting','school-mgt');?></h3>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="">
											<label for="student_approval" class="custom-top-label"><?php esc_attr_e('Student Approval','school-mgt');?></label>
											<input type="checkbox" class="margin_right_checkbox_css" value="1" <?php echo checked(get_option('student_approval'),1);?> name="student_approval">
											<lable><?php esc_attr_e('Enable','school-mgt') ?></label>
										</div>												
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-body user_form">
					<div class="row">
						<div class="header">	
							<h3 class="first_hed"></h3>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">
									<label class="custom-control-label custom-top-label ml-2" for="smgt_email"><?php esc_attr_e('Principal Signature','school-mgt');?></label>
									<div class="col-sm-12 display_flex">
										<input type="text" id="smgt_principal_signature" name="smgt_principal_signature" class="image_path_dots form-control"  value="<?php  echo get_option( 'smgt_principal_signature' ); ?>" readonly />
										
										<input id="upload_principal_signature" type="button" class="button upload_image_btn" style="float: right;" value="<?php esc_attr_e( 'Upload image', 'school-mgt' ); ?>" />
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div id="upload_user_aprincipal_signature">
										<img class="image_preview_css" src="<?php  echo get_option('smgt_principal_signature'); ?>" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if($user_access['add'] == 1 OR $user_access['edit'] == 1 )
				{
					?>
					<div class="form-body user_form mt-3">
						<div class="row">
							<div class="col-sm-6">          	
								<input type="submit" value="<?php esc_attr_e('Save', 'school-mgt' ); ?>" name="save_setting" class="btn btn-success save_btn"/>
							</div>    
						</div>
					</div>     
					<?php
				}
				?>
        	</form>
		</div>
	</div>
</div>

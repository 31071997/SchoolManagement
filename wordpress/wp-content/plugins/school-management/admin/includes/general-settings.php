<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";
	 $('#setting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
});
</script>
<?php
$role=mj_smgt_get_user_role(get_current_user_id());
$role_array = explode(',', $role);
if(in_array("administrator", $role_array))
{
	$user_access_add=1;
	$user_access_edit=1;
	$user_access_delete=1;
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('general_settings');
	$user_access_add=$user_access['add'];
	$user_access_edit=$user_access['edit'];
	$user_access_delete=$user_access['delete'];
	$user_access_view=$user_access['view'];
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
	if(isset($_REQUEST['smgt_system_color_code']))
	{
		update_option( 'smgt_system_color_code', $_REQUEST['smgt_system_color_code']);
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
	if(isset($_REQUEST['smgt_enable_recurring_invoices']))
	{
		update_option( 'smgt_enable_recurring_invoices', 'yes' );
	}
	else
	{
		update_option( 'smgt_enable_recurring_invoices', 'no' );
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

	if(isset($_REQUEST['smgt_enable_video_popup_show']))
	{
		update_option( 'smgt_enable_video_popup_show', 'yes' );
	}
	else 
	{
		update_option( 'smgt_enable_video_popup_show', 'no' );
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

	if(isset($_REQUEST['smgt_heder_enable']))
	{
			update_option( 'smgt_heder_enable', 'yes' );
	}
	else
	{
			update_option( 'smgt_heder_enable', 'no' );
	}

	if(isset($_REQUEST['smgt_admission_fees']))
	{
		update_option( 'smgt_admission_fees', 'yes' );
	}
	else
	{
		update_option( 'smgt_admission_fees', 'no' );
	}

	if(isset($_REQUEST['smgt_registration_fees']))
	{
		update_option( 'smgt_registration_fees', 'yes' );
	}
	else
	{
		update_option( 'smgt_registration_fees', 'no' );
	}


	//Principal Singnature
	if(isset($_REQUEST['smgt_principal_signature']))
	{
		update_option( 'smgt_principal_signature', $_REQUEST['smgt_principal_signature'] );
	}
	//------------ Wizard Setup Option -----------//
	$wizard = smgt_setup_wizard_steps_updates('step1_general_setting');


	//-------- Card option update for Student ---------//
	$dashboard_result = get_option("smgt_dashboard_card_for_student");
	$dashboard_card_access = array();

	$dashboard_card_access =[
								"smgt_payment_status_chart" => isset($_REQUEST['payment_status_chart_enable_student'])?esc_attr($_REQUEST['payment_status_chart_enable_student']):"no",
								"smgt_user_chart" => isset($_REQUEST['user_chart_enable_student'])?esc_attr($_REQUEST['user_chart_enable_student']):"no",
								"smgt_invoice_chart" => isset($_REQUEST['invoice_enable'])?esc_attr($_REQUEST['invoice_enable']):"no",
							];

	$dashboard_result = update_option( 'smgt_dashboard_card_for_student',$dashboard_card_access);

	//-------- Card option update for staffmemeber ---------//
	$dashboard_result_1 = get_option("smgt_dashboard_card_for_support_staff");
	$dashboard_card_access_for_staff = array();

	$dashboard_card_access_for_staff =[
								"smgt_student_status_chart" => isset($_REQUEST['student_status_staff'])?esc_attr($_REQUEST['student_status_staff']):"no",
								"smgt_attendance_chart" => isset($_REQUEST['attendance_staff'])?esc_attr($_REQUEST['attendance_staff']):"no",
								"smgt_payment_status_chart" => isset($_REQUEST['payment_status_staff'])?esc_attr($_REQUEST['payment_status_staff']):"no",
								"smgt_payment_report" => isset($_REQUEST['payment_report_staff'])?esc_attr($_REQUEST['payment_report_staff']):"no",
								"smgt_invoice_chart" => isset($_REQUEST['invoice_enable_staff'])?esc_attr($_REQUEST['invoice_enable_staff']):"no",
								"smgt_user_chart" => isset($_REQUEST['users_chart_staff'])?esc_attr($_REQUEST['users_chart_staff']):"no",
							];

	$dashboard_result_1= update_option( 'smgt_dashboard_card_for_support_staff',$dashboard_card_access_for_staff);

	//-------- Card option update for teacher ---------//
	$dashboard_result_2 = get_option("smgt_dashboard_card_for_teacher");
	$dashboard_card_access_teacher = array();

	$dashboard_card_access_teacher =[
								"smgt_student_status_chart" => isset($_REQUEST['student_status_enable_teacher'])?esc_attr($_REQUEST['student_status_enable_teacher']):"no",
								"smgt_attendance_chart" => isset($_REQUEST['attendance_chart_enable_teacher'])?esc_attr($_REQUEST['attendance_chart_enable_teacher']):"no",
								"smgt_user_chart" => isset($_REQUEST['user_chart_enable_teacher'])?esc_attr($_REQUEST['user_chart_enable_teacher']):"no",
							];

	$dashboard_result_2=update_option( 'smgt_dashboard_card_for_teacher',$dashboard_card_access_teacher);

	//-------- Card option update for parent ---------//
	$dashboard_result_3 = get_option("smgt_dashboard_card_for_parent");
	$dashboard_card_access_parent = array();

	$dashboard_card_access_parent =[
								"smgt_user_chart" => isset($_REQUEST['user_chart_parent'])?esc_attr($_REQUEST['user_chart_parent']):"no",
								"smgt_invoice_chart" => isset($_REQUEST['invoice_enable_parent'])?esc_attr($_REQUEST['invoice_enable_parent']):"no",
								"smgt_payment_status_chart" => isset($_REQUEST['payment_status_parent'])?esc_attr($_REQUEST['payment_status_parent']):"no",
							];

	$dashboard_result_3=update_option( 'smgt_dashboard_card_for_parent',$dashboard_card_access_parent);

	wp_redirect( admin_url() . 'admin.php?page=smgt_gnrl_settings&message=1');
}

?>
<div class="popup-bg">
	<div class="overlay-content max_height_overflow">
		<div class="modal-content">
			<div class="result"></div>
			<div class="view-parent"></div>
			<div class="category_list">
			</div>
		</div>
	</div>    
</div>
<?php
if(get_option('smgt_enable_video_popup_show') == 'yes')
{
?>
<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/vCxdYKKX9es?si=DUUdlwfucUoScL-N";?>" title="General Settings">
	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
</a>
<?php
}
?>

<div class="page-inner"><!-- page-inner-->
	<!-- <div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div> -->
	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
		<div class="row"><!-- Row -->
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
				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
					<p><?php echo $message_string;?></p>
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
				<?php
			} ?>
			<div class="col-md-12 padding_0"><!-- col-md-12 -->
				<div class="panel-body">
        			<form name="student_form" action="" method="post" class="form-horizontal" id="setting_form">
						<div class="header">
							<h3 class="first_hed"><?php esc_html_e(' General Settings','school-mgt');?></h3>
						</div>
						<div class="form-body user_form">
							<div class="row">
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="smgt_school_name" class="form-control validate[required,custom[popup_category_validation]]" type="text" maxlength="100" value="<?php echo get_option( 'smgt_school_name' );?>"  name="smgt_school_name">
											<label class="" for="smgt_school_name"><?php esc_attr_e('School Name','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="smgt_staring_year" class="form-control validate[minSize[4],maxSize[4],min[0]]" min="1" step="1" type="number" value="<?php echo get_option( 'smgt_staring_year' );?>"  name="smgt_staring_year">
											<label class="" for="smgt_staring_year"><?php esc_attr_e('Starting Year','school-mgt');?></label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="smgt_school_address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text" value="<?php echo get_option( 'smgt_school_address' );?>"  name="smgt_school_address">
											<label class="" for="smgt_school_address"><?php esc_attr_e('School Address','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>

								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="smgt_contact_number" class="form-control  validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text" value="<?php echo get_option( 'smgt_contact_number' );?>"  name="smgt_contact_number">
											<label class="label_margin_left_7px" for="smgt_contact_number"><?php esc_attr_e('Official Phone Number','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>

								<div class="col-md-6 input">
									<label class="ml-1 custom-top-label top" for="smgt_contry"><?php esc_attr_e('Country','school-mgt');?></label>
									<?php
									$url = plugins_url( 'countrylist.xml', __FILE__ );
									?>
									<select name="smgt_contry" class="form-control validate[required] max_width_100" id="smgt_contry">
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
										<div class="col-md-12 form-control">
											<input id="smgt_email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text" value="<?php echo get_option( 'smgt_email' );?>"  name="smgt_email">
											<label class="" for="smgt_email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group input">
										<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">
											<label class="custom-control-label label_margin_left_15px custom-top-label ml-2 label_position_rtl label_right_position" for="smgt_email"><?php esc_attr_e('System Logo','school-mgt');?> (<?php  esc_attr_e('Size Must Be 150 x 150 px','school-mgt'); ?>)<span class="require-field">*</span></label>
											<div class="col-sm-12 display_flex">
												<input type="text" id="smgt_system_logo_url" name="smgt_system_logo" class="image_path_dots form-control validate[required]" value="<?php  echo get_option( 'smgt_system_logo' ); ?>" readonly />
												<input id="upload_system_logo_button" type="button" class="button upload_image_btn"  style="float: right;" value="<?php esc_attr_e( 'Upload image', 'school-mgt' ); ?>" />
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0 margin_top_15px">
											<div id="upload_system_logo_preview" class="gnrl_setting_image_background">
												<img class="image_preview_css" src="<?php  echo get_option( 'smgt_system_logo' ); ?>" />
											</div>
										</div>

									</div>
									<p><?php esc_attr_e('Note: logo Size must be 200 X 54 PX And Color Should Be White.','school-mgt');?></p>
								</div>

								<div class="col-md-6">
									<div class="form-group input">
										<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">
											<label class="label_margin_left_7px custom-control-label label_position_rtl custom-top-label ml-2" for="smgt_cover_image"><?php esc_attr_e('Other Logo(Invoice, Mail)','school-mgt');?></label>
											<div class="col-sm-12 display_flex">
												<input type="text" id="smgt_school_background_image" name="smgt_school_logo" class="image_path_dots form-control" value="<?php  echo get_option( 'smgt_school_logo' ); ?>" readonly />
												<input id="upload_image_button" type="button" class="button upload_user_cover_button upload_image_btn" style="float: right;" value="<?php esc_attr_e( 'Upload Cover Image', 'school-mgt' ); ?>" />
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-0 mt-3">
											<div id="upload_school_cover_preview min-h-100-px mt-5-px">
												<img class="other_data_logo" src="<?php  echo get_option( 'smgt_school_logo' ); ?>" />
											</div>

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
									<select id="smgt_datepicker_format" class="form-control max_width_100" name="smgt_datepicker_format">
										<?php
										foreach($date_format_array as $key=>$value)
										{
											echo '<option value="'.$value.'" '.selected($selected_format,$value).'>'.$value.'</option>';
										}
										?>
									</select>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
									<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
										<div class="form-group input">
											<div class="col-md-12 form-control color_picker_div_height">
											<label class="ml-1 custom-top-label top label_position_rtl" for="smgt_datepicker_format"><?php esc_attr_e('System Color','school-mgt');?></label>
												<input id="smgt_notification_fcm_key" class="form-control text-input color_picker_input" type="color" value="<?php echo get_option( 'smgt_system_color_code' );?>"  name="smgt_system_color_code">
												&nbsp;<label class="color_picker_label" for="smgt_notification_fcm_key"><?php esc_attr_e('System Color Code : ','school-mgt');?><?php echo get_option( 'smgt_system_color_code' );?></label>
											</div>
										</div>
									</div>
								</div>

								
							</div>
						</div>


						<div class="form-body user_form">
							<div class="row">
								<div class="header">
									<h3 class="first_hed"><?php esc_attr_e('Recurring Invoices Settings','school-mgt');?></h3>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for="smgt_enable_recurring_invoices"><?php esc_attr_e('Enable Recurring Invoices','school-mgt');?></label>
													<input type="checkbox" class="margin_right_checkbox_css" name="smgt_enable_recurring_invoices"  value="yes" <?php echo checked(get_option('smgt_enable_recurring_invoices'),'yes');?>/>
													<lable><?php esc_attr_e('Enable','school-mgt');?></label>
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
									<h3 class="first_hed"><?php esc_attr_e('Payment Setting','school-mgt');?></h3>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for="smgt_enable_sandbox"><?php esc_attr_e('Enable Sandbox','school-mgt');?></label>
													<input type="checkbox" class="margin_right_checkbox_css" name="smgt_enable_sandbox"  value="1" <?php echo checked(get_option('smgt_enable_sandbox'),'yes');?>/>
													<lable><?php esc_attr_e('Enable','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="smgt_paypal_email" class="form-control validate[custom[email]] text-input" maxlength="100" type="text" value="<?php echo get_option( 'smgt_paypal_email' );?>"  name="smgt_paypal_email">
											<label class="" for="smgt_paypal_email"><?php esc_attr_e('Paypal Email Id','school-mgt');?></label>
										</div>
									</div>
								</div>

								<div class="col-md-6 input">
									<div class="row">
										<div class="col-md-11">
											<label class="ml-1 custom-top-label top" for="smgt_currency_code"><?php esc_attr_e('Select Currency','school-mgt');?></label>
											<select name="smgt_currency_code" class="form-control text-input max_width_100">
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

												<option value="GMD" <?php echo selected(get_option( 'smgt_currency_code' ),'GMD');?>>
												<?php esc_attr_e('Gambian dalasi','school-mgt');?></option>

												<option value="HKD" <?php echo selected(get_option( 'smgt_currency_code' ),'HKD');?>>
												<?php esc_attr_e('Hong Kong Dollar','school-mgt');?></option>
												<option value="HUF" <?php echo selected(get_option( 'smgt_currency_code' ),'HUF');?>>
												<?php esc_attr_e('Hungarian Forint','school-mgt');?> </option>
												<option value="INR" <?php echo selected(get_option( 'smgt_currency_code' ),'INR');?>>
												<?php esc_attr_e('Indian Rupee','school-mgt');?></option>

												<option value="IDR" <?php echo selected(get_option( 'smgt_currency_code' ),'IDR');?>>
												<?php esc_attr_e('Indonesian Rupiah','school-mgt');?></option>


												<option value="KWD" <?php echo selected(get_option( 'smgt_currency_code' ),'KWD');?>>
												<?php esc_attr_e('Kuwaiti dinar','school-mgt');?></option>

												<option value="PKR" <?php echo selected(get_option( 'smgt_currency_code' ),'PKR');?>>
												<?php esc_attr_e('Pakistan Rupee','school-mgt');?></option>

												<option value="ILS" <?php echo selected(get_option( 'smgt_currency_code' ),'ILS');?>>
												<?php esc_attr_e('Israeli New Sheqel','school-mgt');?></option>
												<option value="JPY" <?php echo selected(get_option( 'smgt_currency_code' ),'JPY');?>>
												<?php esc_attr_e('Japanese Yen','school-mgt');?></option>


												<option value="MAD" <?php echo selected(get_option( 'smgt_currency_code' ),'MAD');?>>
												<?php esc_attr_e('Moroccan dirham','school-mgt');?></option>

												<option value="MYR" <?php echo selected(get_option( 'smgt_currency_code' ),'MYR');?>>
												<?php esc_attr_e('Malaysian Ringgit','school-mgt');?></option>

												<option value="MXN" <?php echo selected(get_option( 'smgt_currency_code' ),'MXN');?>>
												<?php esc_attr_e('Mexican Peso','school-mgt');?></option>

												<option value="MZN" <?php echo selected(get_option( 'smgt_currency_code' ),'MZN');?>>
												<?php esc_attr_e('Mozambican metical','school-mgt');?></option>

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

												<option value="SLL" <?php echo selected(get_option( 'smgt_currency_code' ),'SLL');?>>
												<?php esc_attr_e('Sierra Leonean leone','school-mgt');?></option>

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
												<option value="SAR" <?php echo selected(get_option( 'smgt_currency_code' ),'SAR');?>>
												<?php esc_attr_e('Saudi riyal','school-mgt');?></option>
											</select>
										</div>
										<div class="col-md-1">
											<span class="font-23-px"><?php echo mj_smgt_get_currency_symbol();?></span>
										</div>
									</div>
									<span class="description"><?php esc_attr_e('Selected currency might not supported by paypal. Please check with paypal.', 'school-mgt' ); ?></span>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<textarea name="smgt_invoice_notice" class="form-control texarea_padding_0"><?php echo get_option('smgt_invoice_notice');?></textarea>
											<!-- <input id="smgt_invoice_notice" class="form-control text-input" maxlength="100" type="text" value="<?php echo get_option( 'smgt_invoice_notice' );?>"  name="smgt_invoce_notice"> -->
											<label for="smgt_invoice_notice" class="custom-top-label top active"><?php esc_attr_e('Invoice Notice', 'school-mgt'); ?></label>
										</div>
									</div>
								</div>
								<?php
								if(is_plugin_active('paymaster/paymaster.php'))
								{ ?>

									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px mb-3">
										<div class="form-group">
											<div class="col-md-12 form-control">
												<div class="row padding_radio">
													<div class="">
														<label for="smgt_paymaster_pack" class="label_margin_left_0px custom-top-label"><?php esc_attr_e('Use Paymaster Payment Gateways','school-mgt');?></label>
														<input type="checkbox"  class="margin_right_checkbox_css"value="yes" <?php echo checked(get_option('smgt_paymaster_pack'),'yes');?> name="smgt_paymaster_pack">
														<lable><?php esc_attr_e('Enable','school-mgt');?></label>
													</div>
												</div>
											</div>
										</div>
									</div>

									<!-- <div class="form-group row mb-3">
										<label for="smgt_paymaster_pack" class="col-sm-2 control-label col-form-label text-md-end"><?php esc_attr_e('Use Paymaster Payment Gateways','school-mgt');?></label>
										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox"  class="margin_right_checkbox_css"value="yes" <?php echo checked(get_option('smgt_paymaster_pack'),'yes');?> name="smgt_paymaster_pack"><?php esc_attr_e('Enable','school-mgt') ?>
												</label>
											</div>
										</div>
									</div> -->
									<?php
								} ?>

							</div>
						</div>

						<div class="form-body user_form">
							<div class="row">
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
									<div class="form-group">
										<div class="col-md-12 form-control input_height_48px">
											<div class="row padding_radio">
												<div class="input-group">
													<label class="custom-top-label margin_left_0" for=""><?php esc_html_e("Admission Fees","school-mgt");?></label>
													<div class="checkbox checkbox_lebal_padding_8px">
														<label class="control-label form-label">
															<input type="checkbox" class="smgt_admission_fees" name="smgt_admission_fees" value="1" <?php echo checked(get_option('smgt_admission_fees'),'yes');?>/>
															<label><?php esc_html_e('Yes','school-mgt');?></label>
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 smgt_admission_amount">
									<div class="form-group input">
										<?php
											global $wpdb;
											$fees_table_name = $wpdb->prefix . 'smgt_fees';
											$fees_data = $wpdb->get_results( "SELECT * FROM $fees_table_name" );
										?>

										<label class="ml-1 custom-top-label top" for="smgt_admission_amount"><?php esc_attr_e('Admission Fees Amount', 'school-mgt'); ?><span class="require-field">*</span></label>

										<select id="smgt_admission_amount" class="form-control validate[required] text-input max_width_100" name="smgt_admission_amount">

										<option value=""><?php esc_html_e( 'Select Fees', 'school-mgt' ); ?></option>
											<?php

											$options = get_option('smgt_admission_amount');
												foreach( $fees_data as $fees )
												{
												?>
												<option value="<?php echo esc_attr($fees->fees_id); ?>"  <?php if($options==$fees->fees_id) echo 'selected="selected"'; ?>
												name='smgt_admission_amount' >

													<?php echo  get_the_title( $fees->fees_title_id ) . '(' . mj_smgt_get_currency_symbol() . ' ' . $fees->fees_amount . ')'; ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<!-- <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 smgt_admission_amount">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="smgt_admission_amount" class="line_height_35px form-control text-input" type="number" minlength="1" maxlength="100" value="<?php echo get_option( 'smgt_admission_amount' );?>"  name="smgt_admission_amount">
											<label class="" for="smgt_admission_amount"><?php esc_html_e('Admission Fees Amount','school-mgt');?></label>
										</div>
									</div>
								</div> -->
							</div>
						</div>
						<div class="form-body user_form">
							<div class="row">
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
									<div class="form-group">
										<div class="col-md-12 form-control input_height_48px">
											<div class="row padding_radio">
												<div class="input-group">
													<label class="custom-top-label margin_left_0" for=""><?php esc_html_e("Registration Fees","school-mgt");?></label>
													<div class="checkbox checkbox_lebal_padding_8px">
														<label class="control-label form-label">
															<input type="checkbox" class="smgt_registration_fees" name="smgt_registration_fees" value="1" <?php echo checked(get_option('smgt_registration_fees'),'yes');?>/>
															<label><?php esc_html_e('Yes','school-mgt');?></label>
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 smgt_registration_amount">
									<div class="form-group input">
									<?php
										global $wpdb;
										$wpdb_tablename = $wpdb->prefix . 'smgt_fees' ;
										$fees_data_type = $wpdb->get_results( "SELECT * FROM $wpdb_tablename ");
									?>
											<label class="ml-1 custom-top-label top" for="smgt_registration_amount"><?php esc_html_e('Registration Fees Amount','school-mgt');?><span class="require-field">*</span></label>

											<select name="smgt_registration_amount" id="smgt_registration_amount" class='form-control validate[required] text-input max_width_100'>
											<option value=""><?php esc_html_e( 'Select Fees', 'school-mgt' ); ?></option>
											<?php
											$options = get_option('smgt_registration_amount');
												foreach( $fees_data as $fees )
												{
												?>
												<option value="<?php echo esc_attr($fees->fees_id); ?>"  <?php if($options==$fees->fees_id) echo 'selected="selected"'; ?>
												name='smgt_registration_amount' >

													<?php echo  get_the_title( $fees->fees_title_id ) . '(' . mj_smgt_get_currency_symbol() . ' ' . $fees->fees_amount . ')'; ?>
												</option>

											<?php } ?>
                                        </select>

										</div>
									</div>
								</div>


								<!-- <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 smgt_admission_amount">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="smgt_registration_amount" class="line_height_35px form-control text-input" type="number" minlength="1" maxlength="100" value="<?php echo get_option( 'smgt_registration_amount' );?>"  name="smgt_registration_amount">
											<label class="" for="smgt_registration_amount"><?php esc_html_e('Registration Fees Amount','school-mgt');?></label>
										</div>
									</div>
								</div> -->
							</div>
						</div>
						<div class="form-body user_form">
							<div class="header">
								<h3 class="first_hed"><?php esc_attr_e('Virtual Classroom Setting(Zoom)','school-mgt');?></h3>
							</div>
							<div class="row">
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 rtl_margin_top_15px">
									<div class="form-group">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for="smgt_enable_virtual_classroom"><?php esc_attr_e('Virtual Classroom','school-mgt');?></label>
													<input type="checkbox" id="virual_class_checkbox" class="margin_right_checkbox_css" name="smgt_enable_virtual_classroom"  value="1" <?php echo checked(get_option('smgt_enable_virtual_classroom'),'yes');?>/>
													<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
							if (get_option('smgt_enable_virtual_classroom') == 'yes')
							{
								?>
								<style>
									#virtual_class_div{
										display: block;
									}
								</style>
								<?php
							}
							else
							{
								?>
								<style>
									#virtual_class_div{
										display: none;
									}
								</style>
								<?php
							}
							?>
							<div id="virtual_class_div" class="">
								<div class="row">
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="smgt_virtual_classroom_client_id" class="form-control text-input" type="text" value="<?php echo get_option( 'smgt_virtual_classroom_client_id' );?>"  name="smgt_virtual_classroom_client_id">
												<label class="" for="smgt_virtual_classroom_client_id"><?php esc_attr_e('Client Id','school-mgt');?></label>
											</div>
											<span class="description"><?php esc_attr_e('That will be provided by zoom.', 'school-mgt' ); ?></span>
										</div>
									</div>

									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="smgt_virtual_classroom_client_secret_id" class="form-control text-input" type="text" value="<?php echo get_option( 'smgt_virtual_classroom_client_secret_id' );?>"  name="smgt_virtual_classroom_client_secret_id">
												<label class="" for="smgt_virtual_classroom_client_secret_id"><?php esc_attr_e('Client Secret Id','school-mgt');?></label>
											</div>
											<span class="description"><?php esc_attr_e('That will be provided by zoom.', 'school-mgt' ); ?></span>
										</div>
									</div>
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
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

									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
										<div class="form-group mb-3">
											<div class="col-md-12 form-control">
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
											<div class="col-md-12 form-control">
												<input id="" class="form-control text-input" type="text" value="<?php echo site_url().'/?page=callback';?>"  name="" disabled>
												<label class="" for="smgt_virtual_classroom_client_id"><?php esc_attr_e('Redirect URL','school-mgt');?></label>
											</div>
											<span class="description"><?php esc_attr_e('Please copy this Redirect URL and add in your zoom account Redirect URL.', 'school-mgt' ); ?></span>
										</div>
									</div>
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="smgt_virtual_classroom_reminder_before_time" class="form-control" min="0" type="number" onKeyPress="if(this.value.length==2) return false;"  placeholder="<?php esc_html_e('01 Minute','school-mgt');?>"value="<?php echo get_option( 'smgt_virtual_classroom_reminder_before_time' );?>"  name="smgt_virtual_classroom_reminder_before_time">
												<label class="" for="smgt_virtual_classroom_reminder_before_time"><?php esc_html_e('Reminder Before Time','school-mgt');?></label>
											</div>
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
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
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

								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
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
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
								<h3 class="first_hed"><?php esc_html_e('Student Approval setting','school-mgt');?></h3>
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label for="student_approval" class="label_margin_left_0px custom-top-label"><?php esc_attr_e('Student Approval','school-mgt');?></label>
													<input type="checkbox" class="margin_right_checkbox_css" value="1" <?php echo checked(get_option('student_approval'),1);?> name="student_approval"> <?php esc_attr_e('Enable','school-mgt') ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 margin_top_15px_rs rtl_margin_top_15px">

									<h3 class="first_hed"><?php esc_html_e('Video Setting','school-mgt');?></h3>

									<div class="form-group mb-3">

										<div class="col-md-12 form-control">

											<div class="row padding_radio">

												<div class="">

													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("How to Videos Display?","school-mgt");?></label>

													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="smgt_enable_video_popup_show"  value="yes" <?php echo checked(get_option('smgt_enable_video_popup_show'),'yes');?>/>

													<label class="res_margin_top_5px"><?php esc_html_e('Enable','school-mgt');?></label>

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
									<h3 class="first_hed"><?php esc_attr_e('Other setting','school-mgt');?></h3>
								</div>
								<div class="col-md-6">
									<div class="form-group input rtl_margin_0px">
										<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">
											<label class="custom-control-label custom-top-label ml-2 label_position_rtl" for="smgt_email"><?php esc_attr_e('Principal Signature','school-mgt');?></label>
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
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control" style="min-height: 47px !important;">
											<div class="row padding_radio">
												<div class="">
													<label for="smgt_mail_notification" class="label_margin_left_0px custom-top-label"><?php esc_attr_e('Mail Notification','school-mgt');?></label>
													<input type="checkbox" class="margin_right_checkbox_css" value="1" <?php echo checked(get_option('smgt_mail_notification'),1);?> name="smgt_mail_notification">
													<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-body user_form"> <!-- user_form Strat-->
							<div class="row"><!--Row Div Strat-->
								<!-- <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 ">
									<div class="header">
										<h3 class="first_hed"><?php esc_html_e('Footer setting','school-mgt');?></h3>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 P-0 rtl_padding_0px ">
										<div class="form-group input rtl_margin_0px">
											<div class="col-md-12 form-control">
												<input id="smgt_footer_description" class="form-control text-input" type="text" minlength="6" maxlength="100" value="<?php echo get_option( 'smgt_footer_description' );?>"  name="smgt_footer_description">
												<label class="" for="smgt_footer_description"><?php esc_html_e('Footer Description','school-mgt');?></label>
											</div>
										</div>
									</div>
								</div> -->
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
									<div class="header">
										<h3 class="first_hed"><?php esc_html_e('Datatable Header Settings','school-mgt');?></h3>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<div class="form-group">
											<div class="col-md-12 form-control input_height_48px">
												<div class="row padding_radio">
													<div class="input-group">
														<label class="custom-top-label margin_left_0" for=""><?php esc_html_e("Header","school-mgt");?></label>
														<div class="checkbox checkbox_lebal_padding_8px">
															<label class="control-label form-label">
																<input type="checkbox" name="smgt_heder_enable" value="1" <?php echo checked(get_option('smgt_heder_enable'),'yes');?>/>
																<label><?php esc_html_e('Enable','school-mgt');?></label>
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
									<div class="header">
										<h3 class="first_hed"><?php esc_html_e('Push Notification setting','school-mgt');?></h3>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 rtl_padding_0px">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="smgt_notification_fcm_key" class="form-control text-input" type="text" value="<?php echo get_option( 'smgt_notification_fcm_key' );?>"  name="smgt_notification_fcm_key">
												<label class="" for="smgt_notification_fcm_key"><?php esc_attr_e('Notification FCM Key','school-mgt');?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						
						<div class="header">
							<h3 class="first_hed"><?php esc_html_e('Dashboard Card setting For Student','school-mgt');?></h3>
						</div>
						<div class="form-body user_form"> <!-- user_form Strat-->
							<div class="row"><!--Row Div Strat-->
								<?php
								$dashboard_card = get_option("smgt_dashboard_card_for_student");
								?>
								<!-- <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Card","school-mgt");?></label>

													<div class="row">
														<div class="col-md-3 col-sm-6 col-6 p-0">
															<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" <?php echo checked($dashboard_card['smgt_teacher'],"yes");?> value="yes" name="teacher_card">
															<label class="res_margin_top_5px"><?php esc_html_e('Teacher','school-mgt');?></label>
														</div>

														<div class="col-md-3 col-sm-6 col-6 p-0">
														<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="staff_card"  value="yes" <?php echo checked($dashboard_card['smgt_staff'],"yes");?>/>
														<label class="res_margin_top_5px"><?php esc_html_e('Support Staff','school-mgt');?></label>
														</div>

														<div class="col-md-3 col-sm-6 col-6 p-0">
														<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="notice_card"  value="yes" <?php echo checked($dashboard_card['smgt_notices'],"yes");?>/>
														<label class="res_margin_top_5px"><?php esc_html_e('Notice','school-mgt');?></label>
														</div>

														<div class="col-md-3 col-sm-6 col-6 p-0">
														<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="message_card"  value="yes" <?php echo checked($dashboard_card['swmgt_messages'],"yes");?>/>
														<label class="res_margin_top_5px"><?php esc_html_e('Message','school-mgt');?></label>
														</div>

													</div>
												</div>
											</div>
										</div>
									</div>
								</div> -->
								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Users Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="user_chart_enable_student"  value="yes" <?php echo checked($dashboard_card['smgt_user_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Payment Status Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="payment_status_chart_enable_student"  value="yes" <?php echo checked($dashboard_card['smgt_payment_status_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Fees Payment Card","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="invoice_enable"  value="yes" <?php echo checked($dashboard_card['smgt_invoice_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="header">
							<h3 class="first_hed"><?php esc_html_e('Dashboard Card setting For Support-Staff','school-mgt');?></h3>
						</div>
						<div class="form-body user_form"> <!-- user_form Strat-->
							<div class="row"><!--Row Div Strat-->
								<?php $dashboard_card_for_staff = get_option("smgt_dashboard_card_for_support_staff"); ?>

								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Users Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="users_chart_staff"  value="yes" <?php echo checked($dashboard_card_for_staff['smgt_user_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Student Status Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="student_status_staff"  value="yes" <?php echo checked($dashboard_card_for_staff['smgt_student_status_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Attendance Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="attendance_staff"  value="yes" <?php echo checked($dashboard_card_for_staff['smgt_attendance_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Payment Status Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="payment_status_staff"  value="yes" <?php echo checked($dashboard_card_for_staff['smgt_payment_status_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>


								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Payment Report","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="payment_report_staff"  value="yes" <?php echo checked($dashboard_card_for_staff['smgt_payment_report'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Fees Payment Card","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="invoice_enable_staff"  value="yes" <?php echo checked($dashboard_card_for_staff['smgt_invoice_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<div class="header">
							<h3 class="first_hed"><?php esc_html_e('Dashboard Card setting For Teacher','school-mgt');?></h3>
						</div>
						<div class="form-body user_form"> <!-- user_form Strat-->
							<div class="row"><!--Row Div Strat-->
								<?php $dashboard_card_for_teacher = get_option("smgt_dashboard_card_for_teacher"); ?>



								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("User Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="user_chart_enable_teacher"  value="yes" <?php echo checked($dashboard_card_for_teacher['smgt_user_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Student Status Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="student_status_enable_teacher"  value="yes" <?php echo checked($dashboard_card_for_teacher['smgt_student_status_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								

								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Attendance Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="attendance_chart_enable_teacher"  value="yes" <?php echo checked($dashboard_card_for_teacher['smgt_attendance_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="header">
							<h3 class="first_hed"><?php esc_html_e('Dashboard Card setting For Parent','school-mgt');?></h3>
						</div>
						<div class="form-body user_form"> <!-- user_form Strat-->
							<div class="row"><!--Row Div Strat-->
								<?php $dashboard_card_for_parent = get_option("smgt_dashboard_card_for_parent"); ?>

								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("User Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="user_chart_parent"  value="yes" <?php echo checked($dashboard_card_for_parent['smgt_user_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Payement Status Chart","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="payment_status_parent"  value="yes" <?php echo checked($dashboard_card_for_parent['smgt_payment_status_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3  rtl_margin_top_15px">
									<div class="form-group mb-3">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="label_margin_left_0px custom-top-label" for=""><?php esc_html_e("Fees Payment Card","school-mgt");?></label>
													<input type="checkbox" class="res_margin_top_5px margin_right_checkbox_css" name="invoice_enable_parent"  value="yes" <?php echo checked($dashboard_card_for_parent['smgt_invoice_chart'],"yes");?>/>
													<label class="res_margin_top_5px"><?php esc_html_e('Show','school-mgt');?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="form-body user_form">
							<div class="row">
								<div class="col-sm-6">
									<input id="" class="form-control text-input" type="hidden" value="<?php echo get_option( 'general_setting_option_update' );?>"  name="general_setting_option_update">
									<input type="submit" value="<?php esc_attr_e('Save', 'school-mgt' ); ?>" name="save_setting" class="btn btn-success save_btn"/>
								</div>
							</div>
						</div>

					</form>
				</div><!-- panel-body-->
			</div><!-- col-md-12 -->
		</div><!-- Row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner-->
 <?php

?>
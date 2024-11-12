
<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		"use strict";
		jQuery('#user_account_info').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		jQuery('#user_other_info').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		jQuery(document).ready(function($)
		{
		jQuery("body").on("change", ".profile_file", function ()
			{
				"use strict";
				var file = this.files[0];
				var ext = $(this).val().split('.').pop().toLowerCase();
				//Extension Check
				if($.inArray(ext, ['jpeg', 'jpg', 'png', 'bmp','']) == -1)
				{
					var alert_1=language_translate2.account_alert_1;
					var alert_2=language_translate2.account_alert_2;
					alert(""+alert_1+" ."+ext+" "+alert_2+"");
					$(".profile_file").val("");
					return false;
				}
			});
			jQuery("body").on("click", ".save_upload_profile_btn", function ()
			{
				"use strict";
				var value = $(".profile_file").val();
				if(!value)
				{
					alert("<?php echo esc_html__('Please Select Atlest One Image.','school-mgt') ?>")
					return false;
				}
			});
		});
	});
</script>
<?php
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
		}
	}
	$school_obj = new School_Management ( get_current_user_id () );
	$user = wp_get_current_user ();
	$user_info=get_userdata($user->ID);
	$user_data =get_userdata( $user->ID);
	require_once ABSPATH . 'wp-includes/class-phpass.php';
	$wp_hasher = new PasswordHash( 8, true );
	if(isset($_POST['save_change']))
	{

		$nonce = $_POST['_wpnonce'];
		if (  wp_verify_nonce( $nonce, 'password_save_change_nonce' ) )
		{
			if(!empty($_POST['current_pass']) && !empty($_POST['new_pass']) && !empty($_POST['conform_pass']))
			{
			$referrer = $_SERVER['HTTP_REFERER'];
			$success=0;
			if($wp_hasher->CheckPassword($_REQUEST['current_pass'],$user_data->user_pass))
			{

				if(isset($_REQUEST['new_pass'])==$_REQUEST['conform_pass'])
				{
						wp_set_password( $_REQUEST['new_pass'], $user->ID);
						$success=1;
				}
				else
				{
					wp_redirect($referrer.'&sucess=2');
				}
			}
			else
			{

				wp_redirect($referrer.'&sucess=3');
			}
			if($success==1)
			{
				wp_cache_delete($user->ID,'users');
				wp_cache_delete($user_data->user_login,'userlogins');
				wp_logout();
				if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
					$referrer = $_SERVER['HTTP_REFERER'];

					wp_redirect($referrer.'&sucess=1');
				endif;
				ob_start();
			}
			else
			{
				wp_set_auth_cookie($user->ID, true);
			}
			}
		}
	}
	if(isset($_POST['save_change_new']))
	{
		$nonce = $_POST['_wpnonce'];
		if (  wp_verify_nonce( $nonce, 'password_save_change_nonce_new' ) )
		{

			if(!empty($_POST['current_pass']) && !empty($_POST['new_pass']) && !empty($_POST['conform_pass']))
			{
				$referrer = $_SERVER['HTTP_REFERER'];
				$success=0;
				if($wp_hasher->CheckPassword($_POST['current_pass'],$user_data->user_pass))
				{
					if($_POST['new_pass'] == $_POST['conform_pass'])
					{
							wp_set_password( $_POST['new_pass'], $user->ID);
							$success=1;
					}
					else
					{
						wp_redirect($referrer.'&sucess=2');
					}
				}
				else
				{

					wp_redirect($referrer.'&sucess=3');
				}

				if($success==1)
				{
					wp_cache_delete($user->ID,'users');
					wp_cache_delete($user_data->user_login,'userlogins');
					wp_logout();

					if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
						$referrer = $_SERVER['HTTP_REFERER'];

						wp_redirect($referrer.'&sucess=1');
					endif;
					ob_start();
				}
				else
				{
					wp_set_auth_cookie($user->ID, true);
				}
			}
		}
	}
	if(isset($_REQUEST['sucess']))
	{
		$message =$_REQUEST['sucess'];
		if($message == 1)
		{
			?>
			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span></button>
				<p><?php esc_html_e("Password Changed Successfully.",'school-mgt');?></p>
			</div>
			<?php
		}
		if($message == 2)
		{
			?>
			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span></button>
				<p><?php esc_html_e("Confirm password does not match.",'school-mgt');?></p>
			</div>
			<?php
		}
		if($message == 3)
		{
			?>
			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span></button>
				<p><?php esc_html_e("Enter correct current password.",'school-mgt');?></p>
			</div>
			<?php
		}
		if($message == 4)
		{
			?>
			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span></button>
				<p><?php esc_html_e("Record Updated Successfully.",'school-mgt');?></p>
			</div>
			<?php
		}
		if($message == 5)
		{
			?>
			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span></button>
				<p><?php esc_html_e("Enter New password.",'school-mgt');?></p>
			</div>
			<?php

		}
		if($message == 6)
		{
			?>
			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span></button>
				<p><?php esc_html_e("Profile Updated Successfully.",'school-mgt');?></p>
			</div>
			<?php
		}
	}
?>
<?php
if($school_obj->role == "student")
{
	?>
	<style>
	img.qr_code_account
	{
	height: 150px;
	border-radius: 10px !important;
	width: 150px;
	margin-left: 15px;
	}
	img.account_profile_qr
	{
	height: 150px;
	width: 150px;
	}
	@media (min-width: 320px) and (max-width: 500px) {
		.view_page_main #body_area {
			margin-top: 60% !important;
		}
		.responsive_account .view_user_name_label, .responsive_account .color_white_rs {
        color: #333333 !important;
    }
	}
	.user_profile_header_left div.row.user_student_account
	{
	width: 65% !important;
	}
	section.student_qr_margin
	{
		margin-top: 8% !important;
	}
</style>
	<?php
	if(is_rtl())
	{
		?>
		<style>
			img.qr_code_account
			{
				height: 150px;
				width: 150px;
				border-radius: 10px !important;
				margin-right: 15px;
				margin-left: 0px;
			}
			.user_profile_header_left div.row.user_student_account
			{
				width: 60% !important;
			}
		</style>
		<?php
	}
}
?>

<div class="panel-body panel-white frontend_list_margin_30px_res"><!------------ PENAL BODY ------------>
	<div class="view_page_main">
		<!-- POP up code -->
		<div class="popup-bg">
			<div class="overlay-content">
				<div class="modal-content">
					<div class="profile_picture"></div>
				</div>
			</div>
		</div>
		<!-- End POP-UP Code -->

		<?php $user_data = get_userdata(get_current_user_id());
			$user_role = $user_data->roles[0];
			if($user_role == 'student')
			{
				$class_add = 'responsive_account';
			}
			else
			{
				$class_add = '';
			}
		?>

		<!-- Detail Page Header Start -->
		<section id="user_information" class="<?php echo $class_add; ?>">
			<div class="view_page_header_bg">
				<div class="row">
					<div class="col-xl-10 col-md-9 col-sm-10">
						<div class="user_profile_header_left float_left_width_100">
							<?php
							$userimage=mj_smgt_get_user_image($user->ID);
							$class_id=get_user_meta(get_current_user_id(), 'class_name',true);
							$section_name=get_user_meta(get_current_user_id(), 'class_section',true);
							?>
							<script type="text/javascript">
								$(document).ready(function()
								{
									var qr_code_urlnew =JSON.stringify({"user_id": '<?php echo get_current_user_id();?>',"class_id":'<?php echo $class_id;?>',"section_id":'<?php echo $section_name;?>',"qr_type":"schoolqr"});
									var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + qr_code_urlnew + '&amp;size=50x50';
									$('.id_card_barcode').attr('src', url);
								});
							</script>
							<img id="profile_change" class="cursor_pointer user_view_profile_image account_profile_qr" src="<?php if(!empty($userimage)) {echo $userimage; }else{ if($school_obj->role=='student'){ echo get_option( 'smgt_student_thumb_new' );}elseif($school_obj->role=='supportstaff'){ echo get_option( 'smgt_supportstaff_thumb_new' ); }elseif($school_obj->role=='teacher'){ echo get_option( 'smgt_teacher_thumb_new' ); }else{ echo get_option( 'smgt_parent_thumb_new' ); } }?>">
							<?php
							if($school_obj->role == "student")
							{
							?>
							<img class="id_card_barcode user_view_profile_image qr_code_account" id='' src=''>
							<?php
							}
							?>

							<div class="row profile_user_name user_student_account">
								<div class="float_left view_top1">
									<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
										<label class="view_user_name_label"><?php echo esc_html($user->display_name);?></label>
									</div>
									<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
										<div class="view_user_phone float_left_width_100">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<lable class="color_white_rs"><?php echo $user->mobile_number;?></label>
										</div>
									</div>
								</div>
							</div>
							<div id="rs_fd_account_address_width" class="row fd_account_module user_student_account">
								<div class="col-xl-12 col-md-12 col-sm-12">
									<div id="res_mt_8_per" class="view_top2">
										<div class="row view_user_doctor_label">
											<div class="col-md-12 address_student_div">
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $user->address; ?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-3 col-sm-2 add_btn_possition_res">
						<div class="group_thumbs">
							<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Group.png"?>">
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Detail Page Header End -->

		<section id="body_area" class="student_qr_margin">
			<div class="header">
				<h3 class="first_hed"><?php esc_html_e('Account Information','school-mgt');?></h3>
			</div>
			<form class="form-horizontal" action="#" id="user_account_info" method="post">
				<div class="form-body user_form"> <!-- user_form Strat-->
					<div class="row"><!--Row Div Strat-->
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="email" class="amgt_email_id_validation form-control validate[required,custom[email]] text-input" maxlength="50" type="text"  name="email" value="<?php echo esc_attr($user_info->user_email);?>" disabled>
									<label class="" for="desc"><?php esc_html_e('Email','school-mgt');?></label>
								</div>
								<div class="email_validation_div">
									<div class="formError" style="opacity: 0.87; position: absolute; top: 33px; left: 482.5px; margin-top: 0px; display: block;"><div class="formErrorArrow formErrorArrowBottom"><div class="line1"><!-- --></div><div class="line2"><!-- --></div><div class="line3"><!-- --></div><div class="line4"><!-- --></div><div class="line5"><!-- --></div><div class="line6"><!-- --></div><div class="line7"><!-- --></div><div class="line8"><!-- --></div><div class="line9"><!-- --></div><div class="line10"><!-- --></div></div><div class="formErrorContent"><?php esc_html_e('Email id Already Exist.','school-mgt');?><br></div></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input type="password" class="form-control"  id="inputPassword" name="current_pass">
									<label class="" for="desc"><?php esc_html_e('Current Password','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input type="password" class="validate[required] form-control" minlength="8" maxlength="12" id="inputPassword" name="new_pass">
									<label class="" for="desc"><?php esc_html_e('New Password','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input type="password" class="validate[required] form-control" minlength="8" maxlength="12" id="inputPassword" name="conform_pass">
									<label class="" for="desc"><?php esc_html_e('Confirm Password','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php wp_nonce_field( 'password_save_change_nonce_new' ); ?>
				<?php
				if($user_access['edit'] == 1)
				{
					?>
					<div class="form-body user_form"> <!-- user_form Strat-->
						<div class="row"><!--Row Div Strat-->
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<button type="submit" class="btn save_btn" name="save_change_new"><?php esc_html_e('Save','school-mgt');?></button>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</form>
			<?php $user_info=get_userdata(get_current_user_id()); ?>
			<div class="header">
				<h3 class="first_hed"><?php esc_html_e('Other Information','school-mgt');?></h3>
			</div>
			<?php
			$edit=1;
			?>
			<form class="form-horizontal" id="user_other_info" action="#" method="post">
				<input type="hidden" value="<?php print esc_attr($first_name) ?>" name="first_name" >
				<input type="hidden" value="<?php print esc_attr($last_name) ?>" name="last_name" >
				<div class="form-body user_form"> <!-- user_form Strat-->
					<div class="row"><!--Row Div Strat-->
						<div class="col-md-6 col-lg-6 col-sm-12 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($user_info->first_name);} ?>" name="first_name">
									<label class="" for="date"><?php esc_html_e('First Name','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<?php wp_nonce_field( 'profile_save_change_nonce' ); ?>
						<div class="col-md-6 col-lg-6 col-sm-12 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter] " type="text" maxlength="50"  value="<?php if($edit){ echo esc_attr($user_info->middle_name);} ?>" name="middle_name">
									<label class="" for="date"><?php esc_html_e('Middle Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-6 col-sm-12 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($user_info->last_name);} ?>" name="last_name">
									<label class="" for="date"><?php esc_html_e('Last Name','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo esc_attr(mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )));?>"  class="form-control" name="phonecode">
											<label for="phonecode" class="pl-2 popup_countery_code_css"><?php esc_html_e('Country Code','school-mgt');?><span class="required red">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="mobile_number" class="form-control margin_top_10_res text-input validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="mobile_number" value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
											<label class="" for="mobile"><?php esc_html_e('Mobile Number','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="address" class="form-control validate[custom[address_description_validation]]" type="text" maxlength="120" name="address" value="<?php if($edit){ echo esc_attr($user_info->address);} ?>">
									<label class="" for="middle_name"><?php esc_html_e('Address','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="city_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" value="<?php if($edit){ echo esc_attr($user_info->city);} ?>">
									<label class="" for="middle_name"><?php esc_html_e('City','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input class="form-control validate[custom[city_state_country_validation]]" type="text" maxlength="50" name="state_name" value="<?php if($edit){ echo esc_attr($user_info->state);} ?>">
									<label class="" for="middle_name"><?php esc_html_e('State','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input class="form-control validate[custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zipcode" value="<?php if($edit){ echo esc_attr($user_info->zip_code);} ?>">
									<label class="" for="middle_name"><?php esc_html_e('Zip Code','school-mgt');?></label>
								</div>
							</div>
						</div>
						<?php
						if($school_obj->role=='student')
						{
							?>
							<!-- <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="roll_id" class="form-control validate[required,custom[username_validation]]" maxlength="50" type="text" <?php if($edit){ ?>value="<?php  echo $user_info->roll_id;}elseif(isset($_POST['roll_id'])) echo $_POST['roll_id'];?>" name="roll_id">
										<label class="" for="roll_id"><?php esc_attr_e('Roll Number','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div> -->
							<?php
						}
						elseif($school_obj->role=='supportstaff' || $school_obj->role=='teacher')
						{
							?>
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">
								<label class="ml-1 custom-top-label top" for="working_hour"><?php esc_attr_e('Working Hour','school-mgt');?></label>
								<?php if($edit){ $workrval=$user_info->working_hour; }elseif(isset($_POST['working_hour'])){$workrval=$_POST['working_hour'];}else{$workrval='';}?>
								<select name="working_hour" class="line_height_30px form-control max_width_100" id="working_hour">
									<option value=""><?php esc_attr_e('Select Job Time','school-mgt');?></option>
									<option value="full_time" <?php selected( $workrval, 'full_time'); ?>><?php esc_attr_e('Full Time','school-mgt');?></option>
									<option value="half_day" <?php selected( $workrval, 'half_day'); ?>><?php esc_attr_e('Part time','school-mgt');?></option>
								</select>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="email" class="form-control validate[custom[address_description_validation]]" maxlength="50" type="text"  name="possition"
										value="<?php if($edit){ echo $user_info->possition;}elseif(isset($_POST['possition'])) echo $_POST['possition'];?>">
										<label class="" for="possition "><?php esc_attr_e('Position','school-mgt');?></label>
									</div>
								</div>
							</div>
							<?php
						}

						?>
					</div>
				</div>
				<div class="form-body user_form"> <!-- user_form Strat-->
					<?php
					if($user_access['edit'] == 1)
					{
					?>
					<div class="row"><!--Row Div Strat-->
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<button type="submit" class="btn save_btn" name="profile_save_change"><?php esc_html_e('Save','school-mgt');?></button>
						</div>
					</div>
					<?php } ?>
				</div>
			</form>
		</section>
	</div>
</div>
	<?php
	if(($school_obj->role)=='teacher')
	{
		$teacher_id=$user->ID;
	}
	?>

	<?php
	if(isset($_POST['profile_save_change']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'profile_save_change_nonce' ) )
		{
			$usermetadata=array(
							'address'=>mj_smgt_address_description_validation($_POST['address']),
							'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
							'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
							'mobile_number'	=>	mj_smgt_phone_number_validation($_POST['mobile_number']),
							'middle_name'	=>	mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']),
							'first_name'	=>	mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']),
							'last_name'	=>	mj_smgt_onlyLetter_specialcharacter_validation($_POST['last_name']),
							'zip_code' => mj_smgt_onlyLetterNumber_validation($_POST['zipcode']),
						);

			$firstname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);
			$lastname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['last_name']);
			$userdata = array(
				'display_name'=>$firstname." ".$lastname
			);
			$userdata['ID']=$user->ID;

			$result=mj_smgt_update_user_profile($userdata,$usermetadata);

			wp_safe_redirect(home_url()."?dashboard=user&page=account&sucess=4" );

		}
	}
	if(isset($_POST['profile_save_change_new']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'profile_save_change_nonce_new' ) )
		{
			$usermetadata=array(
							'address'=>mj_smgt_address_description_validation($_POST['address']),
							'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
							'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
							'phone'=>mj_smgt_phone_number_validation($_POST['phone']));

			$userdata = array('user_email'=>mj_smgt_email_validation($_POST['email']));

			$userdata['ID']=$user->ID;

			$result=mj_smgt_update_user_profile($userdata,$usermetadata);

			wp_safe_redirect(home_url()."?dashboard=user&page=account&sucess=4" );

		}
	}
	//SAVE PROFILE PICTURE
	if(isset($_POST['save_profile_pic']))
	{
		$referrer = $_SERVER['HTTP_REFERER'];
		if($_FILES['profile']['size'] > 0)
		{
			$user_image=mj_smgt_load_documets($_FILES['profile'],'profile','pimg');
			$photo_image_url=content_url().'/uploads/school_assets/'.$user_image;
		}

		$returnans=update_user_meta($user->ID,'smgt_user_avatar',$photo_image_url);
		if($returnans)
		{
			wp_redirect($referrer.'&sucess=6');
		}
	}
	?>
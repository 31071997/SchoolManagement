<?php

$role=mj_smgt_get_user_role(get_current_user_id());

if($role == 'administrator')

{

	$user_access_add=1;

	$user_access_edit=1;

	$user_access_delete=1;

	$user_access_view=1;

}

else

{

	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('sms_setting');

	$user_access_add=$user_access['add'];

	$user_access_edit=$user_access['edit'];

	$user_access_delete=$user_access['delete'];

	$user_access_view=$user_access['view'];

} 

$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'registration_mail';

$changed = 0;

if(isset($_REQUEST['save_registration_template']))

{

	update_option('registration_mailtemplate',mj_smgt_strip_tags_and_stripslashes($_REQUEST['registratoin_mailtemplate_content']));

	update_option('registration_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['registration_title']));	

	$search=array('{{student_name}}','{{school_name}}');

	$replace = array('ashvin','A1 School');

	$message_content = str_replace($search, $replace,get_option('registration_mailtemplate'));

	$changed = 1;

}

if(isset($_REQUEST['save_activation_mailtemplate']))

{

	update_option('student_activation_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['activation_mailcontent']));

	update_option('student_activation_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['student_activation_title']));	

	$search=array('{{student_name}}','{{school_name}}');

	$replace = array('ashvin','A1 School');

	$message_content = str_replace($search, $replace,get_option('student_activation_mailcontent'));	

	$changed = 1;

}



//---- -------// 

if(isset($_REQUEST['save_feepayment_mailtemplate']))

{

	update_option('fee_payment_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['fee_payment_mailcontent']));

	update_option('fee_payment_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['fee_payment_title']));	

	$changed = 1;

}
if(isset($_REQUEST['save_feepayment_mailtemplate_for_parent'])){

	update_option('fee_payment_mailcontent_for_parent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['fee_payment_mailcontent_for_parent']));

	update_option('fee_payment_title_for_parent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['fee_payment_title_for_parent']));	

	$changed = 1;
}
if(isset($_REQUEST['save_homework_mailtemplate']))

{

	update_option('homework_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['homework_mailcontent']));

	update_option('homework_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['homework_title']));	

	$search=array('{{parent_name}}','{{student_name}}','{{title}}','{{submision_date}}','{{school_name}}');

	$replace = array('ashvin','Bhaskar','Title','2017/9/25','A1 School');

	$message_content = str_replace($search, $replace,get_option('student_activation_mailcontent'));	

	$changed = 1;

}

if(isset($_REQUEST['save_messege_recived_mailtemplate']))

{

	update_option('message_received_mailsubject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['message_received_mailsubject']));

	update_option('message_received_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['message_received_mailcontent']));	

	$changed = 1;	

}

if(isset($_REQUEST['save_adduser_mailtemplate']))

{

	update_option('add_user_mail_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_user_mail_subject']));

	update_option('add_user_mail_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_user_mail_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_holiday_mailtemplate']))

{

	update_option('holiday_mailsubject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['holiday_mailsubject']));

	update_option('holiday_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['holiday_mailcontent']));

	$changed = 1;	

}

if(isset($_REQUEST['save_bus_alocation_mailtemplate']))

{

	update_option('school_bus_alocation_mail_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['school_bus_alocation_mail_content']));

	update_option('school_bus_alocation_mail_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['school_bus_alocation_mail_subject']));

	$changed = 1;

}

if(isset($_REQUEST['save_student_assign_teacher_mailtemplate']))

{

	update_option('student_assign_teacher_mail_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['student_assign_teacher_mail_subject']));

	update_option('student_assign_teacher_mail_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['student_assign_teacher_mail_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_payment_recived_mailtemplate']))

{

	update_option('payment_recived_mailsubject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['payment_recived_mailsubject']));

	update_option('payment_recived_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['payment_recived_mailcontent']));	

	$changed = 1;

}

if(isset($_REQUEST['save_admission_template']))

{

	update_option('admissiion_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['admissiion_title']));

	update_option('admission_mailtemplate_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['admission_mailtemplate_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_admission_template_for_parent']))

{

	update_option('admissiion_approve_subject_for_parent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['admissiion_approve_subject_for_parent']));

	update_option('admission_mailtemplate_content_for_parent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['admission_mailtemplate_content_for_parent']));

	$changed = 1;

}

if(isset($_REQUEST['save_approve_admission_mailtemplate']))
{

	update_option('add_approve_admisson_mail_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_approve_admisson_mail_subject']));

	update_option('add_approve_admission_mail_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_approve_admission_mail_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_homework_mailtemplate_parent']))

{

	update_option('parent_homework_mail_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['parent_homework_mail_subject']));

	update_option('parent_homework_mail_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['parent_homework_mail_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_student_absent_mailtemplate']))

{

	update_option('absent_mail_notification_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['absent_mail_notification_subject']));

	update_option('absent_mail_notification_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['absent_mail_notification_content']));

	$changed = 1;

}


if(isset($_REQUEST['save_exam_receipt_generate']))

{

	update_option('exam_receipt_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['exam_receipt_subject']));

	update_option('exam_receipt_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['exam_receipt_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_bed_template']))

{

	update_option('bed_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['bed_subject']));

	update_option('bed_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['bed_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_student_assign_to_teacher_mailtemplate']))

{

	update_option('student_assign_to_teacher_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['student_assign_to_teacher_subject']));

	update_option('student_assign_to_teacher_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['student_assign_to_teacher_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_notice_mailtemplate']))

{

	update_option('notice_mailsubject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['notice_mailsubject']));

	update_option('notice_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['notice_mailcontent']));

	$changed = 1;

}
if(isset($_REQUEST['save_event_mailtemplate']))

{

	update_option('event_mailsubject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['event_mailsubject']));

	update_option('event_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['event_mailcontent']));

	$changed = 1;

}
if(isset($_REQUEST['virtual_class_invite_teacher_form_template']))

{

	update_option('virtual_class_invite_teacher_mail_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['virtual_class_invite_teacher_mail_subject']));

	update_option('virtual_class_invite_teacher_mail_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['virtual_class_invite_teacher_mail_content']));

	$changed = 1;

}

if(isset($_REQUEST['virtual_class_teacher_reminder_template']))

{

	update_option('virtual_class_teacher_reminder_mail_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['virtual_class_teacher_reminder_mail_subject']));

	update_option('virtual_class_teacher_reminder_mail_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['virtual_class_teacher_reminder_mail_content']));

	$changed = 1;

}

if(isset($_REQUEST['virtual_class_student_reminder_template']))

{

	update_option('virtual_class_student_reminder_mail_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['virtual_class_student_reminder_mail_subject']));

	update_option('virtual_class_student_reminder_mail_content',mj_smgt_strip_tags_and_stripslashes($_REQUEST['virtual_class_student_reminder_mail_content']));

	$changed = 1;

}

if(isset($_REQUEST['save_feepayment_reminder_mailtemplate']))

{

	update_option('fee_payment_reminder_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['fee_payment_reminder_title']));

	update_option('fee_payment_reminder_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['fee_payment_reminder_mailcontent']));

	$changed = 1;

}

if(isset($_REQUEST['save_feepayment_reminder_mailtemplate_for_student']))

{

	update_option('fee_payment_reminder_title_for_student',mj_smgt_strip_tags_and_stripslashes($_REQUEST['fee_payment_reminder_title_for_student']));

	update_option('fee_payment_reminder_mailcontent_for_student',mj_smgt_strip_tags_and_stripslashes($_REQUEST['fee_payment_reminder_mailcontent_for_student']));

	$changed = 1;

}


if(isset($_REQUEST['save_assign_subject_mailtemplate']))

{

	update_option('assign_subject_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['assign_subject_title']));

	update_option('assign_subject_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['assign_subject_mailcontent']));

	$changed = 1;

}

if(isset($_REQUEST['save_issue_book_mailtemplate']))

{

	update_option('issue_book_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['issue_book_title']));

	update_option('issue_book_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['issue_book_mailcontent']));

	$changed = 1;

}

if(isset($_REQUEST['add_leave_template']))

{

	update_option('addleave_email_template',mj_smgt_strip_tags_and_stripslashes($_REQUEST['addleave_email_template']));

	update_option('add_leave_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_leave_subject']));	

	update_option('add_leave_emails',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_leave_emails']));	

	$changed = 1;

}
if(isset($_REQUEST['add_leave_template_for_student']))

{

	update_option('addleave_email_template_student',mj_smgt_strip_tags_and_stripslashes($_REQUEST['addleave_email_template_student']));	

	update_option('add_leave_subject_for_student',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_leave_subject_for_student']));	

	$changed = 1;

}
if(isset($_REQUEST['add_leave_template_for_parent']))

{

	update_option('addleave_email_template_parent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['addleave_email_template_parent']));	

	update_option('add_leave_subject_for_parent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_leave_subject_for_parent']));	

	$changed = 1;

}

if(isset($_REQUEST['add_leave_template_for_admin']))

{

	update_option('addleave_email_template_of_admin',mj_smgt_strip_tags_and_stripslashes($_REQUEST['addleave_email_template_of_admin']));

	update_option('add_leave_subject_of_admin',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_leave_subject_of_admin']));	

	$changed = 1;

}

if(isset($_REQUEST['leave_approve_template']))

{

	update_option('leave_approve_email_template',mj_smgt_strip_tags_and_stripslashes($_REQUEST['leave_approve_email_template']));	

	update_option('leave_approve_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['leave_approve_subject']));	

	update_option('leave_approveemails',mj_smgt_strip_tags_and_stripslashes($_REQUEST['leave_approveemails']));

	$changed = 1;	

}

if(isset($_REQUEST['leave_reject_template']))

{

	update_option('leave_reject_email_template',mj_smgt_strip_tags_and_stripslashes($_REQUEST['leave_reject_email_template']));	

	update_option('leave_reject_subject',mj_smgt_strip_tags_and_stripslashes($_REQUEST['leave_reject_subject']));	

	$changed = 1;	

}

if(isset($_REQUEST['save_exam_mail_template']))

{

	update_option('add_exam_mail_title',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_exam_mail_title']));	

	update_option('add_exam_mailcontent',mj_smgt_strip_tags_and_stripslashes($_REQUEST['add_exam_mailcontent']));	

	$changed = 1;	

}

if($changed)

{

	wp_redirect( admin_url().'admin.php?page=smgt_email_template&message=1');

}

?>

<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" >

</script>

<div class="page-inner"><!-- page-inner -->

	<!-- <div class="page-title"> 

		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>

	</div> -->

	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->

		<div class="row"><!-- row -->

			<?php

			$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

			switch($message)

			{

				case '1':

					$message_string = esc_attr__('Email Template Updated successfully.','school-mgt');

					break;

			}

			if($message)

			{ ?>

				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">

					<p><?php echo $message_string;?></p>

					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>

				</div>

				<?php

			} 

			$i = 1;

			?>

			<div class="col-md-12 padding_0"><!-- col-md-12 -->

				<div class="smgt_main_listpage"><!-- smgt_main_listpage -->

					<div class="panel-body"><!-- panel-body -->

						<!-- <div class="panel-group" id="accordion"> -->

						<div class="main_email_template"><!--main_email_template -->

							<?php $i++; ?>

 							<div id="accordionExample" class="smgt_accordion panel-group accordion accordion-flush padding_top_15px_res" id="accordionFlushExample" aria-multiselectable="false" role="tablist"><!--START accordion -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Request For Admission Mail Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse " aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample" > 

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="admissiion_title" id="admissiion_title" placeholder="Enter Admission subject" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('admissiion_title')); ?>">

																	<label for="first_name" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea name="admission_mailtemplate_content" class="form-control min_height_200 validate[required] h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('admission_mailtemplate_content'));?></textarea>

																<label for="first_name" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('Student name','school-mgt');?></label><br>

														<label><strong>{{user_name}} - </strong><?php esc_attr_e('User name of student','school-mgt');?></label><br>

														<label><strong>{{email}} - </strong><?php esc_attr_e('Email of student','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label>	

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_admission_template" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div> 

								<?php 

								$i++; ?>

								

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

										<?php esc_attr_e('Approve Admission Mail Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="add_approve_admisson_mail_subject" id="add_approve_admisson_mail_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_approve_admisson_mail_subject')); ?>">

																	<label for="add_approve_admisson_mail_subject" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="add_approve_admission_mail_content" name="add_approve_admission_mail_content" class="form-control min_height_200 validate[required] h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_approve_admission_mail_content'));?></textarea>

																<label for="add_approve_admission_mail_content" class="textarea_label"><?php esc_attr_e('Emails Sent to user When', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{user_name}} - </strong><?php esc_attr_e('The student full name','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>

														<label><strong>{{login_link}} - </strong><?php esc_attr_e('Login Link','school-mgt');?></label><br>	

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class Name','school-mgt');?></label><br>		

														<label><strong>{{roll_no}} - </strong><?php esc_attr_e('Roll No','school-mgt');?></label><br>					

														<label><strong>{{username}} - </strong><?php esc_attr_e('Username','school-mgt');?></label><br>					

														<label><strong>{{password}} - </strong><?php esc_attr_e('Password','school-mgt');?></label><br>					

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

														?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">    

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_approve_admission_mailtemplate" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Approve Admission Mail Template For Parent', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse " aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample" > 

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="admissiion_approve_subject_for_parent" id="admissiion_approve_subject_for_parent" placeholder="Enter Admission subject" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('admissiion_approve_subject_for_parent')); ?>">

																	<label for="first_name" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea name="admission_mailtemplate_content_for_parent" class="form-control min_height_200 validate[required] h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('admission_mailtemplate_content_for_parent'));?></textarea>

																<label for="first_name" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>

														<label><strong>{{parent_name}} - </strong><?php esc_attr_e('Parent Name','school-mgt');?></label><br>

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('Student name','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>

														<label><strong>{{login_link}} - </strong><?php esc_attr_e('Login Link','school-mgt');?></label><br>	

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class Name','school-mgt');?></label><br>		

														<label><strong>{{roll_no}} - </strong><?php esc_attr_e('Roll No','school-mgt');?></label><br>					

														<label><strong>{{email}} - </strong><?php esc_attr_e('Email','school-mgt');?></label><br>					

														<label><strong>{{password}} - </strong><?php esc_attr_e('Password','school-mgt');?></label><br>

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_admission_template_for_parent" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div> 

								<?php 

								$i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Registration Mail Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

											<div class="row">

												<div class="col-md-6">

													<div class="form-group input">

														<div class="col-md-12">

															<div class="col-md-12 form-control input_height_75px">

																<input type="text" class="form-control validate[required]" name="registration_title" id="registration_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('registration_title')); ?>">

																<label for="first_name" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>	

														</div>

													</div>

												</div>

												<div class="col-md-6">

													<div class="form-group input">

														<div class="col-md-12 form-control texarea_padding_15">

															<textarea name="registratoin_mailtemplate_content" class="form-control min_height_200 validate[required] h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('registration_mailtemplate'));?></textarea>

															<label for="first_name" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

														</div>

													</div>

												</div>

											</div>

											<div class="form-group input">

                        						<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>

														<label><strong>{{user_name}} - </strong><?php esc_attr_e('User name of student','school-mgt');?></label><br>

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class name of student','school-mgt');?></label><br>

														<label><strong>{{email}} - </strong><?php esc_attr_e('Email of student','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label>		

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_registration_template" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>
								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Add User', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="add_user_mail_subject" id="add_user_mail_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_user_mail_subject')); ?>">

																	<label for="add_user_mail_subject" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="add_user_mail_content" name="add_user_mail_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_user_mail_content'));?></textarea>

																<label for="add_user_mail_content" class="textarea_label"><?php esc_attr_e('Emails Sent to user When', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>	

														<label><strong>{{user_name}} - </strong><?php esc_attr_e('The student full name','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('Parent Name','school-mgt');?></label><br>

														<label><strong>{{role}} - </strong><?php esc_attr_e('Student roll number','school-mgt');?></label><br>					

														<label><strong>{{login_link}} - </strong><?php esc_attr_e('Student roll number','school-mgt');?></label><br>					

														<label><strong>{{username}} - </strong><?php esc_attr_e('Student roll number','school-mgt');?></label><br>					

														<label><strong>{{password}} - </strong><?php esc_attr_e('Student roll number','school-mgt');?></label><br>		

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">    

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_adduser_mailtemplate" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Virtual ClassRoom Teacher Invite Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="virtual_class_invite_teacher_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="virtual_class_invite_teacher_mail_subject" class="form-control validate[required]" name="virtual_class_invite_teacher_mail_subject" id="virtual_class_invite_teacher_mail_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('virtual_class_invite_teacher_mail_subject')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject ','school-mgt');?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="bed_content" name="virtual_class_invite_teacher_mail_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('virtual_class_invite_teacher_mail_content'));?></textarea>

																<label for="virtual_class_invite_teacher_mail_content" class="textarea_label"><?php esc_attr_e('Message','school-mgt');?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                        							<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class Name','school-mgt');?></label><br>

														<label><strong>{{time}} - </strong><?php esc_attr_e('Time','school-mgt');?></label><br>

														<label><strong>{{virtual_class_id}} - </strong><?php esc_attr_e('Virtual Class ID','school-mgt');?></label><br>

														<label><strong>{{password}} - </strong><?php esc_attr_e('Password','school-mgt');?></label><br>

														<label><strong>{{join_zoom_virtual_class}} - </strong><?php esc_attr_e('Join Zoom Virtual Class','school-mgt');?></label><br>

														<label><strong>{{start_zoom_virtual_class}} - </strong><?php esc_attr_e('Start Zoom Virtual Class','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">       	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="virtual_class_invite_teacher_form_template" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<!-- Virtual ClassRoom Teacher Reminder Template -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Virtual ClassRoom Teacher Reminder Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="virtual_class_teacher_reminder_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="virtual_class_invite_teacher_mail_subject" class="form-control validate[required]" name="virtual_class_teacher_reminder_mail_subject" id="virtual_class_invite_teacher_mail_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('virtual_class_teacher_reminder_mail_subject')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject ','school-mgt');?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="bed_content" name="virtual_class_teacher_reminder_mail_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('virtual_class_teacher_reminder_mail_content'));?></textarea>

																<label for="virtual_class_invite_teacher_mail_content" class="textarea_label"><?php esc_attr_e('Message','school-mgt');?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                       	 							<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>		

														<label><strong>{{teacher_name}} - </strong><?php esc_attr_e('Teacher Name','school-mgt');?></label><br>	

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class Name','school-mgt');?></label><br>

														<label><strong>{{subject_name}} - </strong><?php esc_attr_e('Subject Name','school-mgt');?></label><br>

														<label><strong>{{date}} - </strong><?php esc_attr_e('Date','school-mgt');?></label><br>

														<label><strong>{{time}} - </strong><?php esc_attr_e('Time','school-mgt');?></label><br>

														<label><strong>{{virtual_class_id}} - </strong><?php esc_attr_e('Virtual Class ID','school-mgt');?></label><br>

														<label><strong>{{password}} - </strong><?php esc_attr_e('Password','school-mgt');?></label><br>

														<label><strong>{{start_zoom_virtual_class}} - </strong><?php esc_attr_e('Start Zoom Virtual Class','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">   	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="virtual_class_teacher_reminder_template" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<!-- Virtual ClassRoom Student Reminder Template -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Virtual ClassRoom Student Reminder Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="virtual_class_student_reminder_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="virtual_class_invite_teacher_mail_subject" class="form-control validate[required]" name="virtual_class_student_reminder_mail_subject" id="virtual_class_invite_teacher_mail_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('virtual_class_student_reminder_mail_subject')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject ','school-mgt');?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="bed_content" name="virtual_class_student_reminder_mail_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('virtual_class_student_reminder_mail_content'));?></textarea>

																<label for="virtual_class_invite_teacher_mail_content" class="textarea_label"><?php esc_attr_e('Message','school-mgt');?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                        							<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>		

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('Student Name','school-mgt');?></label><br>

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class Name','school-mgt');?></label><br>

														<label><strong>{{subject_name}} - </strong><?php esc_attr_e('Subject Name','school-mgt');?></label><br>

														<label><strong>{{teacher_name}} - </strong><?php esc_attr_e('Teacher Name','school-mgt');?></label><br>

														<label><strong>{{date}} - </strong><?php esc_attr_e('Date','school-mgt');?></label><br>

														<label><strong>{{time}} - </strong><?php esc_attr_e('Time','school-mgt');?></label><br>

														<label><strong>{{virtual_class_id}} - </strong><?php esc_attr_e('Virtual Class ID','school-mgt');?></label><br>

														<label><strong>{{password}} - </strong><?php esc_attr_e('Password','school-mgt');?></label><br>

														<label><strong>{{join_zoom_virtual_class}} - </strong><?php esc_attr_e('Join Zoom Virtual Class','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="virtual_class_student_reminder_template" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Assign Subject Mail Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="assign_subject_title" class="form-control validate[required]" name="assign_subject_title" id="fee_payment_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('assign_subject_title')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="assign_subject_mailcontent" name="assign_subject_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('assign_subject_mailcontent'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>					

														<label><strong>{{teacher_name}} - </strong><?php esc_attr_e('Teacher Name','school-mgt');?></label><br>				

														<label><strong>{{subject_name}} - </strong><?php esc_attr_e('Subject Name','school-mgt');?></label><br>				

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">         	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_assign_subject_mailtemplate" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Student Activation Mail Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="student_activation_title" class="form-control validate[required]" name="student_activation_title" id="student_activation_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('student_activation_title')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="activation_mailcontent" name="activation_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('student_activation_mailcontent'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>							

														<label><strong>{{user_name}} - </strong><?php esc_attr_e('User name of student','school-mgt');?></label><br>

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class name of student','school-mgt');?></label><br>

														<label><strong>{{email}} - </strong><?php esc_attr_e('Email of student','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>			

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_activation_mailtemplate" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>


								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Student Assign to Teacher mail template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

											<div class="row">

												<div class="col-md-6">

													<div class="form-group input">

														<div class="col-md-12">

															<div class="col-md-12 form-control input_height_75px">

																<input type="text" class="form-control validate[required]" name="student_assign_teacher_mail_subject" id="student_assign_teacher_mail_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('student_assign_teacher_mail_subject')); ?>" />

																<label for="student_assign_teacher_mail_subject" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

															</div>	

														</div>

													</div>

												</div>

												<div class="col-md-6">

													<div class="form-group input">

														<div class="col-md-12 form-control texarea_padding_15">

															<textarea id="student_assign_teacher_mail_content" name="student_assign_teacher_mail_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('student_assign_teacher_mail_content'));?></textarea>

															<label for="student_assign_teacher_mail_content" class="textarea_label"><?php esc_attr_e('Message', 'school-mgt'); ?><span class="require-field">*</span></label>

														</div>

													</div>

												</div>

											</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School Name','school-mgt');?></label><br>										

														<label><strong>{{teacher_name}} - </strong><?php esc_attr_e('Teacher Name','school-mgt');?></label><br>								

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_student_assign_teacher_mailtemplate" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Student Assigned to Teacher Student mail template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="student_assign_to_teacher_subject" id="student_assign_to_teacher_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('student_assign_to_teacher_subject')); ?>" />

																	<label for="student_assign_to_teacher_subject" class=""><?php esc_attr_e('Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="student_assign_to_teacher_content" name="student_assign_to_teacher_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('student_assign_to_teacher_content'));?></textarea>

																<label for="student_assign_to_teacher_content" class="textarea_label"><?php esc_attr_e('Emails Sent to user When Student Assigned to Teacher', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                        							<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>						

														<label><strong>{{teacher_name}} - </strong><?php esc_attr_e('Teacher Name','school-mgt');?></label><br>	

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('Enter school name','school-mgt');?></label><br>	

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('Enter student name','school-mgt');?></label><br>	

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Enter Class name','school-mgt');?></label><br>	

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">       	

													<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_student_assign_to_teacher_mailtemplate" class="btn btn-success save_btn"/>

												</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>

								<!-- ADD EXAM MAIL TEMPLATE START-->
								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Add Exam Mail Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="add_exam_mail_title" class="form-control validate[required]" name="add_exam_mail_title" id="add_exam_mail_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_exam_mail_title')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="add_exam_mailcontent" name="add_exam_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_exam_mailcontent'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>					

														<label><strong>{{user_name}} - </strong><?php esc_attr_e('Student/Parent Name','school-mgt');?></label><br>				

														<label><strong>{{exam_name}} - </strong><?php esc_attr_e('Exam Name','school-mgt');?></label><br>				

														<label><strong>{{exam_start_end_date}} - </strong><?php esc_attr_e('Exam Start to End Date','school-mgt');?></label><br>

														<label><strong>{{exam_comment}} - </strong><?php esc_attr_e('Exam Comment','school-mgt');?></label><br>
														
														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>
													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">         	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_exam_mail_template" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>
								<!-- ADD EXAM MAIL TEMPLATE END-->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Student Exam Hall Receipt', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="student_activation_title" class="form-control validate[required]" name="exam_receipt_subject" id="exam_receipt_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('exam_receipt_subject')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject ','school-mgt');?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="exam_receipt_content" name="exam_receipt_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('exam_receipt_content'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Message','school-mgt');?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>	

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_exam_receipt_generate" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>"><?php esc_attr_e('HomeWork Mail Template For Student', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="homework_title" id="homework_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('homework_title')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject ','school-mgt');?><span class="require-field">*</span></label>

																</div>

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="homework_mailcontent" name="homework_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('homework_mailcontent'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Emails Sent Students When Give Homework','school-mgt');?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the Homework email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>							

														<label><strong>{{title}} - </strong><?php esc_attr_e('Student homework title','school-mgt');?></label><br>

														<label><strong>{{subject}} - </strong><?php esc_attr_e('Subject Name','school-mgt');?></label><br>

														<label><strong>{{homework_date}} - </strong><?php esc_attr_e('Homework Date','school-mgt');?></label><br>

														<label><strong>{{submition_date}} - </strong><?php esc_attr_e('Submission Date','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>			

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_homework_mailtemplate" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('HomeWork Mail Template For Parent', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="student_activation_title" class="form-control validate[required]" name="parent_homework_mail_subject" id="parent_homework_mail_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('parent_homework_mail_subject')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject ','school-mgt');?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="parent_homework_mail_content" name="parent_homework_mail_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('parent_homework_mail_content'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Emails Sent to Parents When A Give Homework','school-mgt');?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                        							<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name','school-mgt');?></label><br>

														<label><strong>{{parent_name}} - </strong><?php esc_attr_e('Parent Name','school-mgt');?></label><br>

														<label><strong>{{title}} - </strong><?php esc_attr_e('Student homework title','school-mgt');?></label><br>

														<label><strong>{{homework_date}} - </strong><?php esc_attr_e('Homework Date','school-mgt');?></label><br>

														<label><strong>{{submition_date}} - </strong><?php esc_attr_e('Submission Date','school-mgt');?></label><br>

														<label><strong>{{submition_date}} - </strong><?php esc_attr_e('Submission Date','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>	

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">    	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_homework_mailtemplate_parent" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Attendance Absent Notification', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="absent_mail_notification_subject" id="absent_mail_notification_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('absent_mail_notification_subject')); ?>" />

																	<label for="absent_mail_notification_subject" class=""><?php esc_attr_e('Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="absent_mail_notification_content" name="absent_mail_notification_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('absent_mail_notification_content'));?></textarea>

																<label for="absent_mail_notification_content" class="textarea_label"><?php esc_attr_e('Emails Sent to user if student absent', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                     						   		<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>						

														<label><strong>{{child_name}} - </strong><?php esc_attr_e('Enter name of child','school-mgt');?></label><br>	

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_student_absent_mailtemplate" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>



								<!-- Add Leave Email Template - start -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Add Leave Email Template For Admin', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="student_activation_title" class="form-control validate[required]" name="add_leave_subject_of_admin" id="add_leave_subject_of_admin" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_leave_subject_of_admin')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="activation_mailcontent" name="addleave_email_template_of_admin" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('addleave_email_template_of_admin'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Email sent when student add leave', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>
												
														<label><strong>{{leave_type}} - </strong><?php esc_attr_e('Leave Type','school-mgt');?></label><br>

														<label><strong>{{leave_duration}} - </strong><?php esc_attr_e('Duration of the leave','school-mgt');?></label><br>

														<label><strong>{{reason}} - </strong><?php esc_attr_e('Reson of the leave','school-mgt');?></label><br>

														<label><strong>{{date}} - </strong><?php esc_attr_e('Date of leave','school-mgt');?></label><br>			

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label>

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="add_leave_template_for_admin" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>

								<!-- Add Leave Email Template For Student - start -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Add Leave Email Template For Student', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="student_activation_title" class="form-control validate[required]" name="add_leave_subject_for_student" id="add_leave_subject_for_student" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_leave_subject_for_student')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="activation_mailcontent" name="addleave_email_template_student" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('addleave_email_template_student'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Email sent when student add leave', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>

														<label><strong>{{leave_type}} - </strong><?php esc_attr_e('Leave Type','school-mgt');?></label><br>

														<label><strong>{{leave_duration}} - </strong><?php esc_attr_e('Duration of the leave','school-mgt');?></label><br>

														<label><strong>{{reason}} - </strong><?php esc_attr_e('Reson of the leave','school-mgt');?></label><br>

														<label><strong>{{date}} - </strong><?php esc_attr_e('Date of leave','school-mgt');?></label><br>			

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label>

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="add_leave_template_for_student" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>
								<!-- Add Leave Email Template For Student - End -->

								<!-- Add Leave Email Template For Parent - start -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Add Leave Email Template For Parent', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="student_activation_title" class="form-control validate[required]" name="add_leave_subject_for_parent" id="add_leave_subject_for_parent" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('add_leave_subject_for_parent')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="activation_mailcontent" name="addleave_email_template_parent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('addleave_email_template_parent'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Email sent when student add leave', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{parent_name}} - </strong><?php esc_attr_e('Parent Name','school-mgt');?></label><br>

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>

														<label><strong>{{leave_type}} - </strong><?php esc_attr_e('Leave Type','school-mgt');?></label><br>

														<label><strong>{{leave_duration}} - </strong><?php esc_attr_e('Duration of the leave','school-mgt');?></label><br>

														<label><strong>{{reason}} - </strong><?php esc_attr_e('Reson of the leave','school-mgt');?></label><br>

														<label><strong>{{date}} - </strong><?php esc_attr_e('Date of leave','school-mgt');?></label><br>			

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label>

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="add_leave_template_for_parent" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>
								<!-- Add Leave Email Template For Student - End -->

								<!-- Leave Approve Email Template - start -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Leave Approve Email Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="leave_approve_subject" id="leave_approve_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('leave_approve_subject')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="leave_approve_email_template" name="leave_approve_email_template" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('leave_approve_email_template'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Email Sent to Student When Admin Add Approve Leave', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>									

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>							

														<label><strong>{{date}} - </strong><?php esc_attr_e('Date of leave','school-mgt');?></label><br>

														<label><strong>{{comment}} - </strong><?php esc_attr_e('Comment','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label>	

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="leave_approve_template" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>

								<!-- Leave Approve Email Template - End -->

								<!-- Leave Reject Email Template - start -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Leave Reject Email Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="leave_reject_subject" id="leave_reject_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('leave_reject_subject')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="leave_reject_email_template" name="leave_reject_email_template" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('leave_reject_email_template'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Email Sent to Student When Admin can Reject Leave', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>									

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name or login name (whatever is available)','school-mgt');?></label><br>							

														<label><strong>{{date}} - </strong><?php esc_attr_e('Date of leave','school-mgt');?></label><br>

														<label><strong>{{comment}} - </strong><?php esc_attr_e('Comment','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label>	

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="leave_reject_template" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>

								<!-- Leave Reject Email Template - End -->

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Fee Payment Mail Template For Student', 'school-mgt'); ?>

										</button>
									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="student_activation_title" class="form-control validate[required]" name="fee_payment_title" id="fee_payment_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('fee_payment_title')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="fee_payment_mailcontent" name="fee_payment_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('fee_payment_mailcontent'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>		

														<label><strong>{{parent_name}} - </strong><?php esc_attr_e('Parent Name','school-mgt');?></label><br>				

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>	

													</div>

												</div>

												<?php 

												if($user_access_add == 1 OR $user_access_edit == 1 )

												{

													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_feepayment_mailtemplate" class="btn btn-success save_btn"/>

													</div>

													<?php 

												} ?>

											</form>

										</div>

									</div>

								</div>

								<?php 

								$i++; ?>

								<div class="mt-1 accordion-item">

								<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

									<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

										<?php esc_attr_e('Fee Payment Mail Template For Parent', 'school-mgt'); ?>

									</button>
								</h4>

								<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

									<div class="m-auto panel-body margin_20px">

										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

											<div class="row">

												<div class="col-md-6">

													<div class="form-group input">

														<div class="col-md-12">

															<div class="col-md-12 form-control input_height_75px">

																<input type="text" id="student_activation_title" class="form-control validate[required]" name="fee_payment_title_for_parent" id="fee_payment_title_for_parent" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('fee_payment_title_for_parent')); ?>">

																<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>	

														</div>

													</div>

												</div>

												<div class="col-md-6">

													<div class="form-group input">

														<div class="col-md-12 form-control texarea_padding_15">

															<textarea id="fee_payment_mailcontent_for_parent" name="fee_payment_mailcontent_for_parent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('fee_payment_mailcontent_for_parent'));?></textarea>

															<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

														</div>

													</div>

												</div>

											</div>

											<div class="form-group input">

												<div class="col-md-12">

													<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>		

													<label><strong>{{parent_name}} - </strong><?php esc_attr_e('Parent Name','school-mgt');?></label><br>				

													<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>	

												</div>

											</div>

											<?php 

											if($user_access_add == 1 OR $user_access_edit == 1 )

											{

												?>

												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

													<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_feepayment_mailtemplate_for_parent" class="btn btn-success save_btn"/>

												</div>

												<?php 

											} ?>

										</form>

									</div>

								</div>

								</div>

								<?php 

								$i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Payment Received against Invoice', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="payment_recived_mailsubject" id="payment_recived_mailsubject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('payment_recived_mailsubject')); ?>" />

																	<label for="payment_recived_mailsubject" class=""><?php esc_attr_e('Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="payment_recived_mailcontent" name="payment_recived_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('payment_recived_mailcontent'));?></textarea>

																<label for="payment_recived_mailcontent" class="textarea_label"><?php esc_attr_e('Message', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                        							<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>						

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('Enter school name','school-mgt');?></label><br>

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('Enter student name','school-mgt');?></label><br>

														<label><strong>{{invoice_no}} - </strong><?php esc_attr_e('Enter Invoice No','school-mgt');?></label><br>

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">        	

													<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_payment_recived_mailtemplate" class="btn btn-success save_btn"/>

												</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

												<?php esc_attr_e('Fee Payment Reminder Mail Template For Parent', 'school-mgt'); ?>

											</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="fee_payment_reminder_title" class="form-control validate[required]" name="fee_payment_reminder_title" id="fee_payment_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('fee_payment_reminder_title')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="fee_payment_reminder_mailcontent" name="fee_payment_reminder_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('fee_payment_reminder_mailcontent'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>					

														<label><strong>{{parent_name}} - </strong><?php esc_attr_e('Parent Name','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('Student name','school-mgt');?></label><br>				

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>					

														<label><strong>{{total_amount}} - </strong><?php esc_attr_e('Total Amount','school-mgt');?></label><br>					

														<label><strong>{{due_amount}} - </strong><?php esc_attr_e('Due Amount','school-mgt');?></label><br>					

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class Name','school-mgt');?></label><br>				

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">     	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_feepayment_reminder_mailtemplate" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

									</div>

									<!-- </div> -->

									<?php 

									$i++; ?>

									<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

												<?php esc_attr_e('Fee Payment Reminder Mail Template For Student', 'school-mgt'); ?>

											</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="fee_payment_reminder_title_for_student" class="form-control validate[required]" name="fee_payment_reminder_title_for_student" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('fee_payment_reminder_title_for_student')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="fee_payment_reminder_mailcontent_for_student" name="fee_payment_reminder_mailcontent_for_student" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('fee_payment_reminder_mailcontent_for_student'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>					

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('Student name','school-mgt');?></label><br>				

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>					

														<label><strong>{{total_amount}} - </strong><?php esc_attr_e('Total Amount','school-mgt');?></label><br>					

														<label><strong>{{due_amount}} - </strong><?php esc_attr_e('Due Amount','school-mgt');?></label><br>					

														<label><strong>{{class_name}} - </strong><?php esc_attr_e('Class Name','school-mgt');?></label><br>				

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">     	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_feepayment_reminder_mailtemplate_for_student" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

									<!-- </div> -->

								<?php 

								$i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Issue Book Mail Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="issue_book_title" class="form-control validate[required]" name="issue_book_title" id="fee_payment_title" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('issue_book_title')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="issue_book_mailcontent" name="issue_book_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('issue_book_mailcontent'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Subject', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

														<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>					

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('Student name','school-mgt');?></label><br>				

														<label><strong>{{book_name}} - </strong><?php esc_attr_e('Book Title','school-mgt');?></label><br>				

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>				

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )
												{
													?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">         	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_issue_book_mailtemplate" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>
								<?php 

								$i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Hostel Bed Assigned Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" id="student_activation_title" class="form-control validate[required]" name="bed_subject" id="bed_subject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('bed_subject')); ?>">

																	<label for="learner_complete_quiz_notification_title" class=""><?php esc_attr_e('Email Subject ','school-mgt');?><span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="bed_content" name="bed_content" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('bed_content'));?></textarea>

																<label for="learner_complete_quiz_notification_mailcontent" class="textarea_label"><?php esc_attr_e('Message','school-mgt');?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>				

														<label><strong>{{student_name}} - </strong><?php esc_attr_e('The student full name','school-mgt');?></label><br>

														<label><strong>{{hostel_name}} - </strong><?php esc_attr_e('Hostel name','school-mgt');?></label><br>

														<label><strong>{{room_id}} - </strong><?php esc_attr_e('Room number','school-mgt');?></label><br>

														<label><strong>{{bed_id}} - </strong><?php esc_attr_e('Bed number','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School name','school-mgt');?></label><br>	

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">          	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_bed_template" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Notice', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="notice_mailsubject" id="notice_mailsubject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('notice_mailsubject')); ?>" />

																	<label for="notice_mailsubject" class=""><?php esc_attr_e('Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

													

																<textarea id="notice_mailcontent" name="notice_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('notice_mailcontent'));?></textarea>

																<label for="notice_mailcontent" class="textarea_label"><?php esc_attr_e('Message', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                        						<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>						

														<label><strong>{{notice_title}} - </strong><?php esc_attr_e('Enter notice title','school-mgt');?></label><br>

														<label><strong>{{notice_date}} 	- </strong><?php esc_attr_e('Enter notice date','school-mgt');?></label><br>

														<label><strong>{{notice_for}}	 - </strong><?php esc_attr_e('Enter role name for notice','school-mgt');?></label><br>

														<label><strong>{{notice_comment}} - </strong><?php esc_attr_e('Enter notice comment','school-mgt');?></label><br>

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">  	

													<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_notice_mailtemplate" class="btn btn-success save_btn"/>

												</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Event Mail Template', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="event_mailsubject" id="event_mailsubject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('event_mailsubject')); ?>" />

																	<label for="event_mailsubject" class=""><?php esc_attr_e('Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

													

																<textarea id="event_mailcontent" name="event_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('event_mailcontent'));?></textarea>

																<label for="event_mailcontent" class="textarea_label"><?php esc_attr_e('Event Mail Content', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

                        						<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>						

														<label><strong>{{event_title}} - </strong><?php esc_attr_e('Enter Event title','school-mgt');?></label><br>

														<label><strong>{{event_date}} 	- </strong><?php esc_attr_e('Enter Event date','school-mgt');?></label><br>

														<label><strong>{{event_time}}	 - </strong><?php esc_attr_e('Enter Event time','school-mgt');?></label><br>

														<label><strong>{{description}} - </strong><?php esc_attr_e('Enter Description','school-mgt');?></label><br>

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">  	

													<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_event_mailtemplate" class="btn btn-success save_btn"/>

												</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Message Received', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="message_received_mailsubject" id="message_received_mailsubject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo get_option('message_received_mailsubject'); ?>" />

																	<label for="message_received_mailsubject" class=""><?php esc_attr_e('Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="message_received_mailcontent" name="message_received_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('message_received_mailcontent'));?></textarea>

																<label for="message_received_mailcontent" class="textarea_label"><?php esc_attr_e('Message', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>						

														<label><strong>{{from_mail}} - </strong><?php esc_attr_e('Message sender name','school-mgt');?></label><br>

														<label><strong>{{school_name}} - </strong><?php esc_attr_e('School Name','school-mgt');?></label><br>										

														<label><strong>{{receiver_name}} - </strong><?php esc_attr_e('Message Receive Name','school-mgt');?></label><br>										

														<label><strong>{{message_content}} - </strong><?php esc_attr_e('Message Content','school-mgt');?></label><br>										

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">       	

													<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_messege_recived_mailtemplate" class="btn btn-success save_btn"/>

												</div>

												<?php } ?>

											</form>

										</div>

									</div>

									</div>

									<?php 

									$i++; ?>


								<div class="mt-1 accordion-item">

									<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapstwelve">

											<?php esc_attr_e('', 'school-mgt'); ?>

										</a>

										<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

											<?php esc_attr_e('Holiday', 'school-mgt'); ?>

										</button>

									</h4>

									<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse email_temp_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel-body margin_20px">

											<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">

												<div class="row">

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12">

																<div class="col-md-12 form-control input_height_75px">

																	<input type="text" class="form-control validate[required]" name="holiday_mailsubject" id="holiday_mailsubject" placeholder="<?php esc_attr_e('Enter Email Subject','school-mgt');?>" value="<?php echo mj_smgt_strip_tags_and_stripslashes(get_option('holiday_mailsubject')); ?>" />

																	<label for="holiday_mailsubject" class=""><?php esc_attr_e('Subject', 'school-mgt'); ?> <span class="require-field">*</span></label>

																</div>	

															</div>

														</div>

													</div>

													<div class="col-md-6">

														<div class="form-group input">

															<div class="col-md-12 form-control texarea_padding_15">

																<textarea id="holiday_mailcontent" name="holiday_mailcontent" class="form-control validate[required] min_height_200 h-200-px texarea_padding_0"><?php echo mj_smgt_strip_tags_and_stripslashes(get_option('holiday_mailcontent'));?></textarea>

																<label for="holiday_mailcontent" class="textarea_label"><?php esc_attr_e('Message', 'school-mgt'); ?><span class="require-field">*</span></label>

															</div>

														</div>

													</div>

												</div>

												<div class="form-group input">

													<div class="col-md-12">

														<label><?php esc_attr_e('You can use following variables in the email template:','school-mgt');?></label><br>						

														<label><strong>{{holiday_title}} - </strong><?php esc_attr_e('Enter holiday title','school-mgt');?></label><br>

														<label><strong>{{holiday_date}} 	- </strong><?php esc_attr_e('Enter holiday date','school-mgt');?></label><br>						

													</div>

												</div>

												<?php if($user_access_add == 1 OR $user_access_edit == 1 )

												{

												?>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">       	

														<input type="submit" value="<?php  esc_attr_e('Save','school-mgt')?>" name="save_holiday_mailtemplate" class="btn btn-success save_btn"/>

													</div>

												<?php } ?>

											</form>

										</div>

									</div>

								</div>

								<?php $i++; ?>

							</div><!--END accordion -->

						</div><!--main_email_template -->

					</div><!-- panel-body -->

				</div><!-- smgt_main_listpage -->

			</div><!-- col-md-12 -->

		</div><!-- row -->

	</div><!-- main_list_margin_15px -->

</div><!-- page-inner -->
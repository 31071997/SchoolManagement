<?php 	
$result=get_option('smgt_access_right_supportstaff');
if(isset($_POST['save_access_right']))
{
	$role_access_right = array();
	$result=get_option('smgt_access_right_supportstaff');
	$role_access_right['supportstaff'] = [
	                                 //New Module Added //
									"admission"=>
									["menu_icone"=>plugins_url('school-management/assets/images/icons/admission.png'),
											   "menu_title"=>'Admission',
											   "page_link"=>'admission',
											   "own_data" =>isset($_REQUEST['admission_own_data'])?$_REQUEST['admission_own_data']:0,
											   "add" =>isset($_REQUEST['admission_add'])?$_REQUEST['admission_add']:0,
												"edit"=>isset($_REQUEST['admission_edit'])?$_REQUEST['admission_edit']:0,
												"view"=>isset($_REQUEST['admission_view'])?$_REQUEST['admission_view']:0,
												"delete"=>isset($_REQUEST['admission_delete'])?$_REQUEST['admission_delete']:0
									 ],
									  "student"=>
									  ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/student-icon.png'),
											  "menu_title"=>'Student',
											  "page_link"=>'student',
											 "own_data" => isset($_REQUEST['student_own_data'])?$_REQUEST['student_own_data']:0,
											 "add" => isset($_REQUEST['student_add'])?$_REQUEST['student_add']:0,
											 "edit"=>isset($_REQUEST['student_edit'])?$_REQUEST['student_edit']:0,
											 "view"=>isset($_REQUEST['student_view'])?$_REQUEST['student_view']:0,
											 "delete"=>isset($_REQUEST['student_delete'])?$_REQUEST['student_delete']:0
								      ],
									  "teacher"=>
									  ["menu_icone"=>plugins_url('school-management/assets/images/icons/teacher.png'),
											   "menu_title"=>'Teacher',
											   "page_link"=>'teacher',
											   "own_data" =>isset($_REQUEST['teacher_own_data'])?$_REQUEST['teacher_own_data']:0,
											   "add" =>isset($_REQUEST['teacher_add'])?$_REQUEST['teacher_add']:0,
												"edit"=>isset($_REQUEST['teacher_edit'])?$_REQUEST['teacher_edit']:0,
												"view"=>isset($_REQUEST['teacher_view'])?$_REQUEST['teacher_view']:0,
												"delete"=>isset($_REQUEST['teacher_delete'])?$_REQUEST['teacher_delete']:0
									 ],
									"supportstaff"=>["menu_icone"=>plugins_url( 'school-management/assets/images/icons/support-staff.png'),
											   "menu_title"=>'Supportstaff',
											   "page_link"=>'supportstaff',
											   "own_data" =>isset($_REQUEST['supportstaff_own_data'])?$_REQUEST['supportstaff_own_data']:0,
											   "add" =>isset($_REQUEST['supportstaff_add'])?$_REQUEST['supportstaff_add']:0,
												"edit"=>isset($_REQUEST['supportstaff_edit'])?$_REQUEST['supportstaff_edit']:0,
												"view"=>isset($_REQUEST['supportstaff_view'])?$_REQUEST['supportstaff_view']:0,
												"delete"=>isset($_REQUEST['supportstaff_delete'])?$_REQUEST['supportstaff_delete']:0
												],
												
									"parent"=>
									 ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/parents.png'),
											"menu_title"=>'Parent',
											"page_link"=>'parent',
											 "own_data" => isset($_REQUEST['parent_own_data'])?$_REQUEST['parent_own_data']:0,
											 "add" => isset($_REQUEST['parent_add'])?$_REQUEST['parent_add']:0,
											"edit"=>isset($_REQUEST['parent_edit'])?$_REQUEST['parent_edit']:0,
											"view"=>isset($_REQUEST['parent_view'])?$_REQUEST['parent_view']:0,
											"delete"=>isset($_REQUEST['parent_delete'])?$_REQUEST['parent_delete']:0
								      ],
									  "subject"=>
									  ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/subject.png'),
												"menu_title"=>'Subject',
												"page_link"=>'subject',
												"own_data" => isset($_REQUEST['subject_own_data'])?$_REQUEST['subject_own_data']:0,
												 "add" => isset($_REQUEST['subject_add'])?$_REQUEST['subject_add']:0,
												 "edit"=>isset($_REQUEST['subject_edit'])?$_REQUEST['subject_edit']:0,
												"view"=>isset($_REQUEST['subject_view'])?$_REQUEST['subject_view']:0,
												"delete"=>isset($_REQUEST['subject_delete'])?$_REQUEST['subject_delete']:0
									  ],
									  "class"=>
									  ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/class.png'),
										  "menu_title"=>'Class',
										  "page_link"=>'class',
										  "own_data" => isset($_REQUEST['class_own_data'])?$_REQUEST['class_own_data']:0,
										   "add" => isset($_REQUEST['class_add'])?$_REQUEST['class_add']:0,
										   "edit"=>isset($_REQUEST['class_edit'])?$_REQUEST['class_edit']:0,
										  "view"=>isset($_REQUEST['class_view'])?$_REQUEST['class_view']:0,
										  "delete"=>isset($_REQUEST['class_delete'])?$_REQUEST['class_delete']:0
									  ],
									  "virtual_classroom"=>
									  ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/virtual_classroom.png'),							       
												 "menu_title"=>'virtual_classroom',
												 "page_link"=>'virtual_classroom',
												 "own_data" => isset($_REQUEST['virtual_classroom_own_data'])?$_REQUEST['virtual_classroom_own_data']:0,
												 "add" => isset($_REQUEST['virtual_classroom_add'])?$_REQUEST['virtual_classroom_add']:0,
												"edit"=>isset($_REQUEST['virtual_classroom_edit'])?$_REQUEST['virtual_classroom_edit']:0,
												"view"=>isset($_REQUEST['virtual_classroom_view'])?$_REQUEST['virtual_classroom_view']:0,
												"delete"=>isset($_REQUEST['virtual_classroom_delete'])?$_REQUEST['virtual_classroom_delete']:0
									  ],
									  "schedule"=>
									  ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/class-route.png'),
												 "menu_title"=>'Class Routine',
												 "page_link"=>'schedule',
												 "own_data" => isset($_REQUEST['schedule_own_data'])?$_REQUEST['schedule_own_data']:0,
												 "add" => isset($_REQUEST['schedule_add'])?$_REQUEST['schedule_add']:0,
												"edit"=>isset($_REQUEST['schedule_edit'])?$_REQUEST['schedule_edit']:0,
												"view"=>isset($_REQUEST['schedule_view'])?$_REQUEST['schedule_view']:0,
												"delete"=>isset($_REQUEST['schedule_delete'])?$_REQUEST['schedule_delete']:0
									  ],
									  "attendance"=>
									  ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/attandance.png'),
												   "menu_title"=>'Attendance',
												   "page_link"=>'attendance',
												 "own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
												 "add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
												"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
												"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:0,
												"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
									  ],
									  "notification"=>
									  ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/attandance.png'),
												   "menu_title"=>'Notification',
												   "page_link"=>'notification',
												 "own_data" => isset($_REQUEST['notification_own_data'])?$_REQUEST['notification_own_data']:0,
												 "add" => isset($_REQUEST['notification_add'])?$_REQUEST['notification_add']:0,
												"edit"=>isset($_REQUEST['notification_edit'])?$_REQUEST['notification_edit']:0,
												"view"=>isset($_REQUEST['notification_view'])?$_REQUEST['notification_view']:0,
												"delete"=>isset($_REQUEST['notification_delete'])?$_REQUEST['notification_delete']:0
									  ],
									 "exam"=>
									 ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/exam.png'),
												 "menu_title"=>'Exam',
												 "page_link"=>'exam',
												 "own_data" => isset($_REQUEST['exam_own_data'])?$_REQUEST['exam_own_data']:0,
												 "add" => isset($_REQUEST['exam_add'])?$_REQUEST['exam_add']:0,
												"edit"=>isset($_REQUEST['exam_edit'])?$_REQUEST['exam_edit']:0,
												"view"=>isset($_REQUEST['exam_view'])?$_REQUEST['exam_view']:0,
												"delete"=>isset($_REQUEST['exam_delete'])?$_REQUEST['exam_delete']:0
									   ],
									  "exam_hall"=>
									  ["menu_icone"=>plugins_url( 'school-management/assets/images/icons/exam_hall.png'),
											   "menu_title"=>'Exam Hall',
											   "page_link"=>'exam_hall',
											   "own_data" =>isset($_REQUEST['exam_hall_own_data'])?$_REQUEST['exam_hall_own_data']:0,
											   "add" =>isset($_REQUEST['exam_hall_add'])?$_REQUEST['exam_hall_add']:0,
												"edit"=>isset($_REQUEST['exam_hall_edit'])?$_REQUEST['exam_hall_edit']:0,
												"view"=>isset($_REQUEST['exam_hall_view'])?$_REQUEST['exam_hall_view']:0,
												"delete"=>isset($_REQUEST['exam_hall_delete'])?$_REQUEST['exam_hall_delete']:0
									],
									"grade"=>
									["menu_icone"=>plugins_url( 'school-management/assets/images/icons/grade.png'),
											   "menu_title"=>'Grade',
											   "page_link"=>'grade',
											   "own_data" =>isset($_REQUEST['grade_own_data'])?$_REQUEST['grade_own_data']:0,
											   "add" =>isset($_REQUEST['grade_add'])?$_REQUEST['grade_add']:0,
												"edit"=>isset($_REQUEST['grade_edit'])?$_REQUEST['grade_edit']:0,
												"view"=>isset($_REQUEST['grade_view'])?$_REQUEST['grade_view']:0,
												"delete"=>isset($_REQUEST['grade_delete'])?$_REQUEST['grade_delete']:0
									  ],
									"manage_marks"=>
									['menu_icone'=>plugins_url( 'school-management/assets/images/icons/mark-manage.png'),
												  "menu_title"=>'Mark Manage',
												  "page_link"=>'manage_marks',
												 "own_data" => isset($_REQUEST['manage_marks_own_data'])?$_REQUEST['manage_marks_own_data']:0,
												 "add" => isset($_REQUEST['manage_marks_add'])?$_REQUEST['manage_marks_add']:0,
												"edit"=>isset($_REQUEST['manage_marks_edit'])?$_REQUEST['manage_marks_edit']:0,
												"view"=>isset($_REQUEST['manage_marks_view'])?$_REQUEST['manage_marks_view']:0,
												"delete"=>isset($_REQUEST['manage_marks_delete'])?$_REQUEST['manage_marks_delete']:0
									  ],
									 "homework"=>
									 ['menu_icone'=>plugins_url( 'school-management/assets/images/icons/homework.png'),
												 "menu_title"=>'Home Work',
												 "page_link"=>'homework',
												 "own_data" => isset($_REQUEST['homework_own_data'])?$_REQUEST['homework_own_data']:0,
												 "add" => isset($_REQUEST['homework_add'])?$_REQUEST['homework_add']:0,
												"edit"=>isset($_REQUEST['homework_edit'])?$_REQUEST['homework_edit']:0,
												"view"=>isset($_REQUEST['homework_view'])?$_REQUEST['homework_view']:0,
												"delete"=>isset($_REQUEST['homework_delete'])?$_REQUEST['homework_delete']:0
									  ],
									"hostel"=>
									['menu_icone'=>plugins_url( 'school-management/assets/images/icons/hostel.png'),
												 "menu_title"=>'Hostel',
												 "page_link"=>'hostel',
												 "own_data" => isset($_REQUEST['hostel_own_data'])?$_REQUEST['hostel_own_data']:0,
												 "add" => isset($_REQUEST['hostel_add'])?$_REQUEST['hostel_add']:0,
												"edit"=>isset($_REQUEST['hostel_edit'])?$_REQUEST['hostel_edit']:0,
												"view"=>isset($_REQUEST['hostel_view'])?$_REQUEST['hostel_view']:0,
												"delete"=>isset($_REQUEST['hostel_delete'])?$_REQUEST['hostel_delete']:0
									  ],
									  "document"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/hostel.png'),
												 "menu_title"=>'Document',
												 "page_link"=>'document',
												 "own_data" => isset($_REQUEST['document_own_data'])?$_REQUEST['document_own_data']:0,
												 "add" => isset($_REQUEST['document_add'])?$_REQUEST['document_add']:0,
												"edit"=>isset($_REQUEST['document_edit'])?$_REQUEST['document_edit']:0,
												"view"=>isset($_REQUEST['document_view'])?$_REQUEST['document_view']:0,
												"delete"=>isset($_REQUEST['document_delete'])?$_REQUEST['document_delete']:0
									  ],
									  "leave"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/notification_new.png'),
												'app_icone'=>plugins_url( 'school-management/assets/images/icons/notification_new.png'),
												"menu_title"=>'Leave',
												"page_link"=>'leave',
												"own_data" => isset($_REQUEST['leave_own_data'])?$_REQUEST['leave_own_data']:0,
												"add" => isset($_REQUEST['leave_add'])?$_REQUEST['leave_add']:0,
												"edit"=>isset($_REQUEST['leave_edit'])?$_REQUEST['leave_edit']:0,
												"view"=>isset($_REQUEST['leave_view'])?$_REQUEST['leave_view']:0,
												"delete"=>isset($_REQUEST['leave_delete'])?$_REQUEST['leave_delete']:0
										],
									   "transport"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/transport.png'),
											   "menu_title"=>'Transport',
											   "page_link"=>'transport',
												 "own_data" => isset($_REQUEST['transport_own_data'])?$_REQUEST['transport_own_data']:0,
												 "add" => isset($_REQUEST['transport_add'])?$_REQUEST['transport_add']:0,
												"edit"=>isset($_REQUEST['transport_edit'])?$_REQUEST['transport_edit']:0,
												"view"=>isset($_REQUEST['transport_view'])?$_REQUEST['transport_view']:0,
												"delete"=>isset($_REQUEST['transport_delete'])?$_REQUEST['transport_delete']:0
									  ],
									  
									  "notice"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/notice.png'),
												  "menu_title"=>'Notice Board',
												  "page_link"=>'notice',
												 "own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:0,
												 "add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:0,
												"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
												"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:0,
												"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
									  ],
									  // message //
									  "message"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:0,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:0,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:0
									  ],
									   // Migration //
									   "migration"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/message.png'),
												"menu_title"=>'Migration',
												"page_link"=>'migration',
												"own_data" => isset($_REQUEST['migration_own_data'])?$_REQUEST['migration_own_data']:0,
												"add" => isset($_REQUEST['migration_add'])?$_REQUEST['migration_add']:0,
												"edit"=>isset($_REQUEST['migration_edit'])?$_REQUEST['migration_edit']:0,
												"view"=>isset($_REQUEST['migration_view'])?$_REQUEST['migration_view']:0,
												"delete"=>isset($_REQUEST['migration_delete'])?$_REQUEST['migration_delete']:0
										],
										// tax //
										"tax"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/fee.png'),
												"menu_title"=>'Tax',
												"page_link"=>'tax',
												"own_data" => isset($_REQUEST['tax_own_data'])?$_REQUEST['tax_own_data']:0,
												"add" => isset($_REQUEST['tax_add'])?$_REQUEST['tax_add']:0,
												"edit"=>isset($_REQUEST['tax_edit'])?$_REQUEST['tax_edit']:0,
												"view"=>isset($_REQUEST['tax_view'])?$_REQUEST['tax_view']:0,
												"delete"=>isset($_REQUEST['tax_delete'])?$_REQUEST['tax_delete']:0
										],
								     // feepayment //
									  "feepayment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/fee.png'),
												 "menu_title"=>'Fee Payment',
												 "page_link"=>'feepayment',
												 "own_data" => isset($_REQUEST['feepayment_own_data'])?$_REQUEST['feepayment_own_data']:0,
												 "add" => isset($_REQUEST['feepayment_add'])?$_REQUEST['feepayment_add']:0,
												"edit"=>isset($_REQUEST['feepayment_edit'])?$_REQUEST['feepayment_edit']:0,
												"view"=>isset($_REQUEST['feepayment_view'])?$_REQUEST['feepayment_view']:0,
												"delete"=>isset($_REQUEST['feepayment_delete'])?$_REQUEST['feepayment_delete']:0
									  ],
									   // payment //
									  "payment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/payment.png'),
												 "menu_title"=>'Payment',
												 "page_link"=>'payment',
												 "own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:0,
												 "add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:0,
												"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:0,
												"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:0,
												"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:0
									  ],
									  
									    // holiday //
									  "holiday"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/holiday.png'),
												 "menu_title"=>'Holiday',
												 "page_link"=>'holiday',
												 "own_data" => isset($_REQUEST['holiday_own_data'])?$_REQUEST['holiday_own_data']:0,
												 "add" => isset($_REQUEST['holiday_add'])?$_REQUEST['holiday_add']:0,
												"edit"=>isset($_REQUEST['holiday_edit'])?$_REQUEST['holiday_edit']:0,
												"view"=>isset($_REQUEST['holiday_view'])?$_REQUEST['holiday_view']:0,
												"delete"=>isset($_REQUEST['holiday_delete'])?$_REQUEST['holiday_delete']:0
									  ],
									   // library //
									   "library"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/library.png'),
											   "menu_title"=>'Library',
											   "page_link"=>'library',
												 "own_data" => isset($_REQUEST['library_own_data'])?$_REQUEST['library_own_data']:0,
												 "add" => isset($_REQUEST['library_add'])?$_REQUEST['library_add']:0,
												"edit"=>isset($_REQUEST['library_edit'])?$_REQUEST['library_edit']:0,
												"view"=>isset($_REQUEST['library_view'])?$_REQUEST['library_view']:0,
												"delete"=>isset($_REQUEST['library_delete'])?$_REQUEST['library_delete']:0
									        ],
                                             // custom_field //
										"custom_field"=>["menu_icone"=>plugins_url( 'school-management/assets/images/icons/custom.png'),
											   "menu_title"=>'Custom Field',
											   "page_link"=>'custom_field',
											   "own_data" =>isset($_REQUEST['custom_field_own_data'])?$_REQUEST['custom_field_own_data']:0,
											   "add" =>isset($_REQUEST['custom_field_add'])?$_REQUEST['custom_field_add']:0,
												"edit"=>isset($_REQUEST['custom_field_edit'])?$_REQUEST['custom_field_edit']:0,
												"view"=>isset($_REQUEST['custom_field_view'])?$_REQUEST['custom_field_view']:0,
												"delete"=>isset($_REQUEST['custom_field_delete'])?$_REQUEST['custom_field_delete']:0
												],
											  // report new //
										"report"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/report.png'),
												 "menu_title"=>'Report',
												 "page_link"=>'report',
												 "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
												 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
												"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
												"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:0,
												"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
									          ],
										// sms_setting //
										"sms_setting"=>["menu_icone"=>plugins_url( 'school-management/assets/images/icons/sms_setting.png'),
											   "menu_title"=>'SMS Setting',
											   "page_link"=>'sms_setting',
											   "own_data" =>isset($_REQUEST['sms_setting_own_data'])?$_REQUEST['sms_setting_own_data']:0,
											   "add" =>isset($_REQUEST['sms_setting_add'])?$_REQUEST['sms_setting_add']:0,
												"edit"=>isset($_REQUEST['sms_setting_edit'])?$_REQUEST['sms_setting_edit']:0,
												"view"=>isset($_REQUEST['sms_setting_view'])?$_REQUEST['sms_setting_view']:0,
												"delete"=>isset($_REQUEST['sms_setting_delete'])?$_REQUEST['sms_setting_delete']:0
										],
												// email_template //
										"email_template"=>
										["menu_icone"=>plugins_url( 'school-management/assets/images/icons/email_template.png'),
											   "menu_title"=>'Email Template',
											   "page_link"=>'email_template',
											   "own_data" =>isset($_REQUEST['email_template_own_data'])?$_REQUEST['email_template_own_data']:0,
											   "add" =>isset($_REQUEST['email_template_add'])?$_REQUEST['email_template_add']:0,
												"edit"=>isset($_REQUEST['email_template_edit'])?$_REQUEST['email_template_edit']:0,
												"view"=>isset($_REQUEST['email_template_view'])?$_REQUEST['email_template_view']:0,
												"delete"=>isset($_REQUEST['email_template_delete'])?$_REQUEST['email_template_delete']:0
									],
									// general_settings new  //
									"general_settings"=>["menu_icone"=>plugins_url( 'school-management/assets/images/icons/general_settings.png'),
											   "menu_title"=>'General Settings',
											   "page_link"=>'general_settings',
											   "own_data" =>isset($_REQUEST['general_settings_own_data'])?$_REQUEST['general_settings_own_data']:0,
											   "add" =>isset($_REQUEST['general_settings_add'])?$_REQUEST['general_settings_add']:0,
												"edit"=>isset($_REQUEST['general_settings_edit'])?$_REQUEST['general_settings_edit']:0,
												"view"=>isset($_REQUEST['general_settings_view'])?$_REQUEST['general_settings_view']:0,
												"delete"=>isset($_REQUEST['general_settings_delete'])?$_REQUEST['general_settings_delete']:0
									],
										// account //
											"account"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/account.png'),
														"menu_title"=>'Account',
														"page_link"=>'account',
														"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
														"add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
														"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:0,
														"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:0,
														"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
										],
										"event"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/report.png'),							       
														"menu_title"=>'Event',
														"page_link"=>'event',
														"own_data" => isset($_REQUEST['event_own_data'])?$_REQUEST['event_own_data']:0,
														"add" => isset($_REQUEST['event_add'])?$_REQUEST['event_add']:0,
														"edit"=>isset($_REQUEST['event_edit'])?$_REQUEST['event_edit']:0,
														"view"=>isset($_REQUEST['event_view'])?$_REQUEST['event_view']:0,
														"delete"=>isset($_REQUEST['event_delete'])?$_REQUEST['event_delete']:0
											]
									    ];

	$result=update_option( 'smgt_access_right_supportstaff',$role_access_right);
	wp_redirect ( admin_url() . 'admin.php?page=smgt_access_right&tab=Support_staff&message=1');
}
$access_right=get_option('smgt_access_right_supportstaff');
?>	
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Support Staff Access Right Updated Successfully.','school-mgt');
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
?>
<div class=""><!--- PANEL WHITE DIV START -->
		<div class="header">	
			<h3 class="first_hed"><?php echo esc_html( esc_attr__( 'Support Staff Access Right', 'school-mgt')); ?></h3>
		</div>			
		<div class="panel-body" id="rs_access_pl_15px"> <!--- PANEL BODY DIV START -->
			<form name="student_form" action="" method="post" class="form-horizontal" id="access_right_form">	
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15"><?php esc_attr_e('Menu','school-mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 "><?php esc_attr_e('OwnData','school-mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 "><?php esc_attr_e('View','school-mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15"><?php esc_attr_e('Add','school-mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15"><?php esc_attr_e('Edit','school-mgt');?></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15"><?php esc_attr_e('Delete ','school-mgt');?></div>
				</div>
				<div class="access_right_menucroll row border_bottom_0">
				
				   <!-- Admission module code  -->
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Admission','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['admission']['own_data'],1);?> value="1" name="admission_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['admission']['view'],1);?> value="1" name="admission_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['admission']['add'],1);?> value="1" name="admission_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['admission']['edit'],1);?> value="1" name="admission_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['admission']['delete'],1);?> value="1" name="admission_delete"  >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Admission module code end -->

					<!-- Class module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Class','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['class']['own_data'],1);?> value="1" name="class_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['class']['view'],1);?> value="1" name="class_view">
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['class']['add'],1);?> value="1" name="class_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['class']['edit'],1);?> value="1" name="class_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['class']['delete'],1);?> value="1" name="class_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Class module code end -->


					<!-- Class Routine module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Class Routine','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['schedule']['own_data'],1);?> value="1" name="schedule_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['schedule']['view'],1);?> value="1" name="schedule_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['schedule']['add'],1);?> value="1" name="schedule_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['schedule']['edit'],1);?> value="1" name="schedule_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['schedule']['delete'],1);?> value="1" name="schedule_delete">	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Class Routine module code end -->
					
					<!-- Virtual Classroom module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Virtual Classroom','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['virtual_classroom']['own_data'],1);?> value="1" name="virtual_classroom_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['virtual_classroom']['view'],1);?> value="1" name="virtual_classroom_view">
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['virtual_classroom']['add'],1);?> value="1" name="virtual_classroom_add">	              
								</label>
							</div>
						</div> 
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['virtual_classroom']['edit'],1);?> value="1" name="virtual_classroom_edit">
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['virtual_classroom']['delete'],1);?> value="1" name="virtual_classroom_delete">
								</label>
							</div>
						</div>								
					</div>							
					<!-- Virtual Classroom module code end -->

					<!-- Subject module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Subject','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['subject']['own_data'],1);?> value="1" name="subject_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['subject']['view'],1);?> value="1" name="subject_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['subject']['add'],1);?> value="1" name="subject_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['subject']['edit'],1);?> value="1" name="subject_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['subject']['delete'],1);?> value="1" name="subject_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Subject module code end -->

					<!-- Student module code  -->							
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Student','school-mgt');?>
							</span>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['student']['own_data'],1);?> value="1" name="student_own_data">	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['student']['view'],1);?> value="1" name="student_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['student']['add'],1);?> value="1" name="student_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['student']['edit'],1);?> value="1" name="student_edit" >	              
								</label>
							</div>
						</div>								
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['student']['delete'],1);?> value="1" name="student_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Student module code  -->
					
					<!-- Teacher module code  -->
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Teacher','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['teacher']['own_data'],1);?> value="1" name="teacher_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['teacher']['view'],1);?> value="1" name="teacher_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['teacher']['add'],1);?> value="1" name="teacher_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['teacher']['edit'],1);?> value="1" name="teacher_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['teacher']['delete'],1);?> value="1" name="teacher_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Teacher module code end -->

					<!-- Support staff module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label Supportstaff_menu_label">
								<?php esc_attr_e('Supportstaff','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['supportstaff']['own_data'],1);?> value="1" name="supportstaff_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['supportstaff']['view'],1);?> value="1" name="supportstaff_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['supportstaff']['add'],1);?> value="1" name="supportstaff_add" disabled >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['supportstaff']['edit'],1);?> value="1" name="supportstaff_edit" disabled >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['supportstaff']['delete'],1);?> value="1" name="supportstaff_delete" disabled >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Support staff module code end -->

					<!-- Parent module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Parent','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['parent']['own_data'],1);?> value="1" name="parent_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['parent']['view'],1);?> value="1" name="parent_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['parent']['add'],1);?> value="1" name="parent_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['parent']['edit'],1);?> value="1" name="parent_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['parent']['delete'],1);?> value="1" name="parent_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Parent module code end -->
					
					<!-- Exam module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Exam','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam']['own_data'],1);?> value="1" name="exam_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam']['view'],1);?> value="1" name="exam_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam']['add'],1);?> value="1" name="exam_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam']['edit'],1);?> value="1" name="exam_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam']['delete'],1);?> value="1" name="exam_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Exam module code end -->

					<!-- Exam Hall module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Exam Hall','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam_hall']['own_data'],1);?> value="1" name="exam_hall_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam_hall']['view'],1);?> value="1" name="exam_hall_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam_hall']['add'],1);?> value="1" name="exam_hall_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam_hall']['edit'],1);?> value="1" name="exam_hall_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['exam_hall']['delete'],1);?> value="1" name="exam_hall_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Exam Hall module code end -->

										<!-- Manage Marks module code  -->					
										<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Manage Marks','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['manage_marks']['own_data'],1);?> value="1" name="manage_marks_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['manage_marks']['view'],1);?> value="1" name="manage_marks_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['manage_marks']['add'],1);?> value="1" name="manage_marks_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['manage_marks']['edit'],1);?> value="1" name="manage_marks_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['manage_marks']['delete'],1);?> value="1" name="manage_marks_delete" disabled>	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Manage Marks module code end -->

					<!-- Grade module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Grade','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['grade']['own_data'],1);?> value="1" name="grade_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['grade']['view'],1);?> value="1" name="grade_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['grade']['add'],1);?> value="1" name="grade_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['grade']['edit'],1);?> value="1" name="grade_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['grade']['delete'],1);?> value="1" name="grade_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Grade Hall module code end -->

					<!-- Migration module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Migration','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['migration']['own_data'],1);?> value="1" name="migration_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['migration']['view'],1);?> value="1" name="migration_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['migration']['add'],1);?> value="1" name="migration_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['migration']['edit'],1);?> value="1" name="migration_edit" disabled>	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['migration']['delete'],1);?> value="1" name="migration_delete" disabled>	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Migration module code end -->

					<!-- Home Work module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Home Work','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['homework']['own_data'],1);?> value="1" name="homework_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['homework']['view'],1);?> value="1" name="homework_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['homework']['add'],1);?> value="1" name="homework_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['homework']['edit'],1);?> value="1" name="homework_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['homework']['delete'],1);?> value="1" name="homework_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Home Work module code end -->

					<!-- Attendance module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Attendance','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['attendance']['own_data'],1);?> value="1" name="attendance_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['attendance']['view'],1);?> value="1" name="attendance_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['attendance']['add'],1);?> value="1" name="attendance_add" >
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['attendance']['edit'],1);?> value="1" name="attendance_edit">
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['attendance']['delete'],1);?> value="1" name="attendance_delete" disabled>	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Attendance module code end -->

					<!-- document module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Document','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['document']['own_data'],1);?> value="1" name="document_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['document']['view'],1);?> value="1" name="document_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['document']['add'],1);?> value="1" name="document_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['document']['edit'],1);?> value="1" name="document_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['document']['delete'],1);?> value="1" name="document_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- document module code end -->

					<!-- Leave module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Leave','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['leave']['own_data'],1);?> value="1" name="leave_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['leave']['view'],1);?> value="1" name="leave_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['leave']['add'],1);?> value="1" name="leave_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['leave']['edit'],1);?> value="1" name="leave_edit"  >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['leave']['delete'],1);?> value="1" name="leave_delete"  >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Leave module code end -->

					<!-- Fee Payment module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Tax','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['tax']['own_data'],1);?> value="1" name="tax_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['tax']['view'],1);?> value="1" name="tax_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['tax']['add'],1);?> value="1" name="tax_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['tax']['edit'],1);?> value="1" name="tax_edit">	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['tax']['delete'],1);?> value="1" name="tax_delete">	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Fee Payment module code end -->

					<!-- Fee Payment module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Fees Payment','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['feepayment']['own_data'],1);?> value="1" name="feepayment_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['feepayment']['view'],1);?> value="1" name="feepayment_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['feepayment']['add'],1);?> value="1" name="feepayment_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['feepayment']['edit'],1);?> value="1" name="feepayment_edit">	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['feepayment']['delete'],1);?> value="1" name="feepayment_delete">	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Fee Payment module code end -->

					<!-- Payment module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Payment','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['payment']['own_data'],1);?> value="1" name="payment_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['payment']['view'],1);?> value="1" name="payment_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['payment']['add'],1);?> value="1" name="payment_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['payment']['edit'],1);?> value="1" name="payment_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['payment']['delete'],1);?> value="1" name="payment_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Payment module code end -->

					<!-- Library module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Library','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['library']['own_data'],1);?> value="1" name="library_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['library']['view'],1);?> value="1" name="library_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['library']['add'],1);?> value="1" name="library_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['library']['edit'],1);?> value="1" name="library_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['library']['delete'],1);?> value="1" name="library_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Library module code end -->

					<!-- Hostel module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Hostel','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['hostel']['own_data'],1);?> value="1" name="hostel_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['hostel']['view'],1);?> value="1" name="hostel_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['hostel']['add'],1);?> value="1" name="hostel_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['hostel']['edit'],1);?> value="1" name="hostel_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['hostel']['delete'],1);?> value="1" name="hostel_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Hostel module code end -->

					<!-- Transport module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Transport','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['transport']['own_data'],1);?> value="1" name="transport_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['transport']['view'],1);?> value="1" name="transport_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['transport']['add'],1);?> value="1" name="transport_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['transport']['edit'],1);?> value="1" name="transport_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['transport']['delete'],1);?> value="1" name="transport_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Transport module code end -->

					<!-- Report module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Report','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['report']['own_data'],1);?> value="1" name="report_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['report']['view'],1);?> value="1" name="report_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['report']['add'],1);?> value="1" name="report_add" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['report']['edit'],1);?> value="1" name="report_edit" disabled>	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['report']['delete'],1);?> value="1" name="report_delete" disabled>	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Report module code end -->

					<!-- Notice Board module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Notice','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notice']['own_data'],1);?> value="1" name="notice_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notice']['view'],1);?> value="1" name="notice_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notice']['add'],1);?> value="1" name="notice_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notice']['edit'],1);?> value="1" name="notice_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notice']['delete'],1);?> value="1" name="notice_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Notice Board module code end -->

					<!-- Event module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Event','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['event']['own_data'],1);?> value="1" name="event_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['event']['view'],1);?> value="1" name="event_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['event']['add'],1);?> value="1" name="event_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['event']['edit'],1);?> value="1" name="event_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['event']['delete'],1);?> value="1" name="event_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Event module code end -->

					<!-- Message module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Message','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['message']['own_data'],1);?> value="1" name="message_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['message']['view'],1);?> value="1" name="message_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['message']['add'],1);?> value="1" name="message_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['message']['edit'],1);?> value="1" name="message_edit" disabled>	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['message']['delete'],1);?> value="1" name="message_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Message module code end -->
					
					<!-- Notification module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Notification','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notification']['own_data'],1);?> value="1" name="notification_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notification']['view'],1);?> value="1" name="notification_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notification']['add'],1);?> value="1" name="notification_add" >
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notification']['edit'],1);?> value="1" name="notification_edit" disabled>
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['notification']['delete'],1);?> value="1" name="notification_delete">
								</label>
							</div>
						</div>								
					</div>							
					<!-- Notification module code end -->

					<!-- Holiday module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Holiday','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['holiday']['own_data'],1);?> value="1" name="holiday_own_data">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['holiday']['view'],1);?> value="1" name="holiday_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['holiday']['add'],1);?> value="1" name="holiday_add" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['holiday']['edit'],1);?> value="1" name="holiday_edit" >	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['holiday']['delete'],1);?> value="1" name="holiday_delete" >	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Holiday module code end -->

					<!-- Custom module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Custom Field','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['custom_field']['own_data'],1);?> value="1" name="custom_field_own_data" >	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['custom_field']['view'],1);?> value="1" name="custom_field_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['custom_field']['add'],1);?> value="1" name="custom_field_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['custom_field']['edit'],1);?> value="1" name="custom_field_edit">	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['custom_field']['delete'],1);?> value="1" name="custom_field_delete">	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Report module code end -->	
					
					<!-- SMS Setting module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('SMS Setting','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['sms_setting']['own_data'],1);?> value="1" name="sms_setting_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['sms_setting']['view'],1);?> value="1" name="sms_setting_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['sms_setting']['add'],1);?> value="1" name="sms_setting_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['sms_setting']['edit'],1);?> value="1" name="sms_setting_edit">	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['sms_setting']['delete'],1);?> value="1" name="sms_setting_delete" disabled>	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Report module code end -->

					<!-- Email Template module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Email Template','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['email_template']['own_data'],1);?> value="1" name="email_template_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['email_template']['view'],1);?> value="1" name="email_template_view">
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['email_template']['add'],1);?> value="1" name="email_template_add">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['email_template']['edit'],1);?> value="1" name="email_template_edit">	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['email_template']['delete'],1);?> value="1" name="email_template_delete" disabled>	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Email Template module code end -->
					
					<!-- General Settings module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('General Settings','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['general_settings']['own_data'],1);?> value="1" name="general_settings_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['general_settings']['view'],1);?> value="1" name="general_settings_view">
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['general_settings']['add'],1);?> value="1" name="general_settings_add" disabled>	              
								</label>
							</div>
						</div> 
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['general_settings']['edit'],1);?> value="1" name="general_settings_edit" disabled>
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['general_settings']['delete'],1);?> value="1" name="general_settings_delete" disabled>
								</label>
							</div>
						</div>								
					</div>							
					<!-- Virtual Classroom module code end -->

					<!-- Account module code  -->					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<span class="menu-label">
								<?php esc_attr_e('Account','school-mgt');?>
							</span>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['account']['own_data'],1);?> value="1" name="account_own_data" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['account']['view'],1);?> value="1" name="account_view">	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_10">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['account']['add'],1);?> value="1" name="account_add" disabled>	              
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['account']['edit'],1);?> value="1" name="account_edit">	              
								</label>
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu_left_15">
							<div class="checkbox">
								<label>
									<input type="checkbox" <?php echo checked($access_right['supportstaff']['account']['delete'],1);?> value="1" name="account_delete" disabled>	              
								</label>
							</div>
						</div>								
					</div>							
					<!-- Account module code end -->
				</div>						
				<div class="col-sm-6 row_bottom rtl_acc_save_btn">							
					<input type="submit" value="<?php esc_attr_e('Save', 'school-mgt' ); ?>" name="save_access_right" class="btn btn-success save_btn"/>
				</div>					
			</form>
		</div><!---END PANEL BODY DIV -->
</div> <!--- END PANEL WHITE DIV -->   
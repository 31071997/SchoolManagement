<?php 
 // This is adminside main First page of school management plugin 
add_action( 'admin_menu', 'mj_smgt_school_menu' );
function mj_smgt_school_menu()
{
	if (function_exists('mj_smgt_school_menu'))  
	{
			$user = new WP_User(get_current_user_id());
			$user_role=$user->roles[0];
			if($user_role == 'administrator' )
			{
				
				add_menu_page( 'School Management', esc_attr__('School Management','school-mgt'), 'manage_options', 'smgt_school', 'mj_smgt_school_dashboard',plugins_url( 'school-management/assets/images/school-management-system-1.png' ), 7); 
				
				if($_SESSION['cmgt_verify'] == '')
				{ 
					add_submenu_page('smgt_school','Licence Settings',esc_attr__( 'Licence Settings', 'school-mgt' ),'manage_options','smgt_setup','mj_smgt_school_dashboard');
				}
				add_submenu_page('smgt_school', esc_attr__( 'Dashboard', 'school-mgt' ), esc_attr__( 'Dashboard', 'school-mgt' ), 'administrator', 'smgt_school', 'mj_smgt_school_dashboard');

				add_submenu_page('smgt_school',  esc_attr__( 'Admission', 'school-mgt' ), esc_attr__( 'Admission', 'school-mgt' ), 'administrator', 'smgt_admission', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Student', 'school-mgt' ), esc_attr__( 'Student', 'school-mgt' ), 'administrator', 'smgt_student', 'mj_smgt_school_dashboard');
				
				add_submenu_page('smgt_school', esc_attr__( 'Teacher', 'school-mgt' ), esc_attr__( 'Teacher', 'school-mgt' ), 'administrator', 'smgt_teacher', 'mj_smgt_school_dashboard');	
				add_submenu_page('smgt_school', esc_attr__( 'Support Staff', 'school-mgt' ), esc_attr__( 'Support Staff', 'school-mgt' ), 'administrator', 'smgt_supportstaff', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school',  esc_attr__( 'Parent', 'school-mgt' ), esc_attr__( 'Parent', 'school-mgt' ), 'administrator', 'smgt_parent', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school',  esc_attr__( 'Subject', 'school-mgt' ), esc_attr__( 'Subject', 'school-mgt' ), 'administrator', 'smgt_Subject', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school',  esc_attr__( 'Class', 'school-mgt' ), esc_attr__( 'Class', 'school-mgt' ), 'administrator', 'smgt_class', 'mj_smgt_school_dashboard');
				if (get_option('smgt_enable_virtual_classroom') == 'yes')
				{
					add_submenu_page('smgt_school',  esc_attr__( 'Virtual Classroom', 'school-mgt' ), esc_attr__( 'Virtual Classroom', 'school-mgt' ), 'administrator', 'smgt_virtual_classroom', 'mj_smgt_school_dashboard');
				}
				add_submenu_page('smgt_school', esc_attr__( 'Class Routine', 'school-mgt' ), esc_attr__( 'Class Routine', 'school-mgt' ), 'administrator', 'smgt_route', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( ' Attendance', 'school-mgt' ), esc_attr__( ' Attendance', 'school-mgt' ), 'administrator', 'smgt_attendence', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school',  esc_attr__('Exam', 'school-mgt' ), esc_attr__('Exam', 'school-mgt' ), 'administrator', 'smgt_exam', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Exam Hall', 'school-mgt' ), esc_attr__( 'Exam Hall', 'school-mgt' ), 'administrator', 'smgt_hall', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Grade', 'school-mgt' ), esc_attr__( 'Grade', 'school-mgt' ), 'administrator', 'smgt_grade', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Manage Marks', 'school-mgt' ), esc_attr__( 'Manage Marks', 'school-mgt' ), 'administrator', 'smgt_result', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Homework', 'school-mgt' ), esc_attr__( 'Homework', 'school-mgt' ), 'administrator', 'smgt_student_homewrok', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Hostel', 'school-mgt' ), esc_attr__( 'Hostel', 'school-mgt' ), 'administrator', 'smgt_hostel', 'mj_smgt_school_dashboard');
				
				add_submenu_page('smgt_school', esc_attr__( 'Leave', 'school-mgt' ), esc_attr__( 'Leave', 'school-mgt' ), 'administrator', 'smgt_leave', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__('Documents', 'school-mgt' ), esc_attr__('Documents', 'school-mgt' ), 'administrator', 'smgt_document', 'mj_smgt_school_dashboard');

				add_submenu_page('smgt_school', esc_attr__( 'Transport', 'school-mgt' ), esc_attr__( 'Transport', 'school-mgt' ), 'administrator', 'smgt_transport', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Notice', 'school-mgt' ), esc_attr__( 'Notice', 'school-mgt' ), 'administrator', 'smgt_notice', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Event', 'school-mgt' ), esc_attr__( 'Event', 'school-mgt' ), 'administrator', 'smgt_event', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school',  esc_attr__( 'Message', 'school-mgt' ), esc_attr__( 'Message', 'school-mgt' ), 'administrator', 'smgt_message', 'mj_smgt_school_dashboard');	
				add_submenu_page('smgt_school',  esc_attr__( 'Notification', 'school-mgt' ), esc_attr__( 'Notification', 'school-mgt' ), 'administrator', 'smgt_notification', 'mj_smgt_school_dashboard');
				
				add_submenu_page('smgt_school', esc_attr__( 'Tax', 'school-mgt' ), esc_attr__( 'Tax', 'school-mgt' ), 'administrator', 'smgt_tax', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Advance Reports', 'school-mgt' ), esc_attr__( 'Advance Reports', 'school-mgt' ), 'administrator', 'smgt_advance_report', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Fees Payment', 'school-mgt' ), esc_attr__( 'Fees Payment', 'school-mgt' ), 'administrator', 'smgt_fees_payment', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school',  esc_attr__( 'Payment', 'school-mgt' ), esc_attr__( 'Payment', 'school-mgt' ), 'administrator', 'smgt_payment', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Holiday', 'school-mgt' ), esc_attr__( 'Holiday', 'school-mgt' ), 'administrator', 'smgt_holiday', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Library', 'school-mgt' ), esc_attr__( 'Library', 'school-mgt' ), 'administrator', 'smgt_library', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Custom Fields', 'school-mgt' ), esc_attr__( 'Custom Fields', 'school-mgt' ), 'administrator', 'custom_field', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Report', 'school-mgt' ), esc_attr__( 'Report', 'school-mgt' ), 'administrator', 'smgt_report', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'Migration', 'school-mgt' ), esc_attr__( 'Migration', 'school-mgt' ), 'administrator', 'smgt_Migration', 'mj_smgt_school_dashboard');
				add_submenu_page('smgt_school', esc_attr__( 'SMS Setting', 'school-mgt' ), esc_attr__( 'SMS Setting', 'school-mgt' ), 'administrator', 'smgt_sms-setting', 'mj_smgt_school_dashboard');
				
				add_submenu_page('smgt_school',esc_attr__('Email Template','school-mgt'),esc_attr__('Email Template','school-mgt'),'administrator','smgt_email_template','mj_smgt_school_dashboard');

				add_submenu_page('smgt_school',esc_attr__('Access Right','school-mgt'),esc_attr__('Access Right','school-mgt'),'administrator','smgt_access_right','mj_smgt_school_dashboard');
				add_submenu_page('smgt_school',  esc_attr__( 'How To Videos', 'school-mgt' ), esc_attr__( 'How To Videos', 'school-mgt' ), 'administrator', 'smgt_system_videos', 'mj_smgt_school_dashboard');	
				add_submenu_page('smgt_school',  esc_attr__( 'General Settings', 'school-mgt' ), esc_attr__( 'General Settings', 'school-mgt' ), 'administrator', 'smgt_gnrl_settings', 'mj_smgt_school_dashboard');
			}
			elseif($user_role == 'management' )
			{
				$admission=mj_smgt_get_userrole_wise_access_right_array_by_page('admission');
				$supportstaff=mj_smgt_get_userrole_wise_access_right_array_by_page('supportstaff');
				$exam_hall=mj_smgt_get_userrole_wise_access_right_array_by_page('exam_hall');
				$grade=mj_smgt_get_userrole_wise_access_right_array_by_page('grade');
				$notification=mj_smgt_get_userrole_wise_access_right_array_by_page('notification');
				$custom_field=mj_smgt_get_userrole_wise_access_right_array_by_page('custom_field');
				$migration=mj_smgt_get_userrole_wise_access_right_array_by_page('migration');
				$sms_setting=mj_smgt_get_userrole_wise_access_right_array_by_page('sms_setting');
				$email_template=mj_smgt_get_userrole_wise_access_right_array_by_page('email_template');
				$access_right=mj_smgt_get_userrole_wise_access_right_array_by_page('access_right');
				$general_settings=mj_smgt_get_userrole_wise_access_right_array_by_page('general_settings');
				$teacher=mj_smgt_get_userrole_wise_access_right_array_by_page('teacher');
				$student=mj_smgt_get_userrole_wise_access_right_array_by_page('student');
				$parent=mj_smgt_get_userrole_wise_access_right_array_by_page('parent');
				$subject=mj_smgt_get_userrole_wise_access_right_array_by_page('subject');
				$class=mj_smgt_get_userrole_wise_access_right_array_by_page('class');
				$virtual_classroom=mj_smgt_get_userrole_wise_access_right_array_by_page('virtual_classroom');
				$schedule=mj_smgt_get_userrole_wise_access_right_array_by_page('schedule');
				$attendance=mj_smgt_get_userrole_wise_access_right_array_by_page('attendance');
				$exam=mj_smgt_get_userrole_wise_access_right_array_by_page('exam');
				$hostel=mj_smgt_get_userrole_wise_access_right_array_by_page('hostel');

				$leave=mj_smgt_get_userrole_wise_access_right_array_by_page('leave');
				$documents=mj_smgt_get_userrole_wise_access_right_array_by_page('document');
				$homework=mj_smgt_get_userrole_wise_access_right_array_by_page('homework');
				$manage_marks=mj_smgt_get_userrole_wise_access_right_array_by_page('manage_marks');
				$feepayment=mj_smgt_get_userrole_wise_access_right_array_by_page('feepayment');
				$tax=mj_smgt_get_userrole_wise_access_right_array_by_page('feepayment');
				$payment=mj_smgt_get_userrole_wise_access_right_array_by_page('payment');
				$transport=mj_smgt_get_userrole_wise_access_right_array_by_page('transport');
				$notice=mj_smgt_get_userrole_wise_access_right_array_by_page('notice');
				$event=mj_smgt_get_userrole_wise_access_right_array_by_page('event');
				$message=mj_smgt_get_userrole_wise_access_right_array_by_page('message');
				$holiday=mj_smgt_get_userrole_wise_access_right_array_by_page('holiday');
				$library=mj_smgt_get_userrole_wise_access_right_array_by_page('library');
				//$account=mj_smgt_get_userrole_wise_access_right_array_by_page('account');
				$report=mj_smgt_get_userrole_wise_access_right_array_by_page('report');
				
				add_menu_page( 'School Management', esc_attr__('School Management','school-mgt'), 'management', 'smgt_school', 'mj_smgt_school_dashboard',plugins_url( 'school-management/assets/images/school-management-system-1.png' ), 7); 
				if($_SESSION['cmgt_verify'] == '')
				{ 
					add_submenu_page('smgt_school','Licence Settings',esc_attr__( 'Licence Settings', 'school-mgt' ),'management','smgt_setup','mj_smgt_school_dashboard');
				}
				add_submenu_page('smgt_school', esc_attr__( 'Dashboard', 'school-mgt' ), esc_attr__( 'Dashboard', 'school-mgt' ), 'management', 'smgt_dashboard', 'mj_smgt_school_dashboard');
				if($admission == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Admission', 'school-mgt' ), esc_attr__( 'Admission', 'school-mgt' ), 'management', 'smgt_admission', 'mj_smgt_school_dashboard');
				}
				if($student == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Student', 'school-mgt' ), esc_attr__( 'Student', 'school-mgt' ), 'management', 'smgt_student', 'mj_smgt_school_dashboard');
				}
				if($teacher == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Teacher', 'school-mgt' ), esc_attr__( 'Teacher', 'school-mgt' ), 'management', 'smgt_teacher', 'mj_smgt_school_dashboard');	
				}
				if($supportstaff == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Support Staff', 'school-mgt' ), esc_attr__( 'Support Staff', 'school-mgt' ), 'management', 'smgt_supportstaff', 'mj_smgt_school_dashboard');
				}
				if($parent == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Parent', 'school-mgt' ), esc_attr__( 'Parent', 'school-mgt' ), 'management', 'smgt_parent', 'mj_smgt_school_dashboard');
				}
				if($subject == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Subject', 'school-mgt' ), esc_attr__( 'Subject', 'school-mgt' ), 'management', 'smgt_Subject', 'mj_smgt_school_dashboard');
				}
				if($class == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Class', 'school-mgt' ), esc_attr__( 'Class', 'school-mgt' ), 'management', 'smgt_class', 'mj_smgt_school_dashboard');
				}
				if($virtual_classroom == 1)
				{
					if (get_option('smgt_enable_virtual_classroom') == 'yes')
					{
						add_submenu_page('smgt_school',  esc_attr__( 'Virtual Classroom', 'school-mgt' ), esc_attr__( 'Virtual Classroom', 'school-mgt' ), 'management', 'smgt_virtual_classroom', 'mj_smgt_school_dashboard');
					}
				}
				if($schedule == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Class Routine', 'school-mgt' ), esc_attr__( 'Class Routine', 'school-mgt' ), 'management', 'smgt_route', 'mj_smgt_school_dashboard');
				}
				if($attendance == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( ' Attendance', 'school-mgt' ), esc_attr__( ' Attendance', 'school-mgt' ), 'management', 'smgt_attendence', 'mj_smgt_school_dashboard');
				}
				if($exam == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__('Exam', 'school-mgt' ), esc_attr__('Exam', 'school-mgt' ), 'management', 'smgt_exam', 'mj_smgt_school_dashboard');
				}
				if($exam_hall == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Exam Hall', 'school-mgt' ), esc_attr__( 'Exam Hall', 'school-mgt' ), 'management', 'smgt_hall', 'mj_smgt_school_dashboard');
				}
				
				if($grade == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Grade', 'school-mgt' ), esc_attr__( 'Grade', 'school-mgt' ), 'management', 'smgt_grade', 'mj_smgt_school_dashboard');
				}
				if($manage_marks == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Manage Marks', 'school-mgt' ), esc_attr__( 'Manage Marks', 'school-mgt' ), 'management', 'smgt_result', 'mj_smgt_school_dashboard');
				}
				if($homework == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Homework', 'school-mgt' ), esc_attr__( 'Homework', 'school-mgt' ), 'management', 'smgt_student_homewrok', 'mj_smgt_school_dashboard');
				}
				if($hostel == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Hostel', 'school-mgt' ), esc_attr__( 'Hostel', 'school-mgt' ), 'management', 'smgt_hostel', 'mj_smgt_school_dashboard');
				}

				if($leave == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Leave', 'school-mgt' ), esc_attr__( 'Leave', 'school-mgt' ), 'management', 'smgt_leave', 'mj_smgt_school_dashboard');
				}
				if($documents == 1)
				{
					add_submenu_page('smgt_school',  esc_attr__( 'Documents', 'school-mgt' ), esc_attr__( 'Documents', 'school-mgt' ), 'management', 'smgt_document', 'mj_smgt_school_dashboard');
				}

				if($transport == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Transport', 'school-mgt' ), esc_attr__( 'Transport', 'school-mgt' ), 'management', 'smgt_transport', 'mj_smgt_school_dashboard');
				}
				if($notice == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Notice', 'school-mgt' ), esc_attr__( 'Notice', 'school-mgt' ), 'management', 'smgt_notice', 'mj_smgt_school_dashboard');
				}
				if($event == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Event', 'school-mgt' ), esc_attr__( 'Event', 'school-mgt' ), 'management', 'smgt_event', 'mj_smgt_school_dashboard');
				}
				if($message == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Message', 'school-mgt' ), esc_attr__( 'Message', 'school-mgt' ), 'management', 'smgt_message', 'mj_smgt_school_dashboard');
				}			
				if($notification == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Notification', 'school-mgt' ), esc_attr__( 'Notification', 'school-mgt' ), 'management', 'smgt_notification', 'mj_smgt_school_dashboard');
				}
				if($tax == 1)
				{
				   add_submenu_page('smgt_school', esc_attr__( 'Tax', 'school-mgt' ), esc_attr__( 'Tax', 'school-mgt' ), 'management', 'smgt_tax', 'mj_smgt_school_dashboard');
				}
				if($feepayment == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Fees Payment', 'school-mgt' ), esc_attr__( 'Fees Payment', 'school-mgt' ), 'management', 'smgt_fees_payment', 'mj_smgt_school_dashboard');
				}
				if($payment == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'Payment', 'school-mgt' ), esc_attr__( 'Payment', 'school-mgt' ), 'management', 'smgt_payment', 'mj_smgt_school_dashboard');
				}
				if($holiday == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Holiday', 'school-mgt' ), esc_attr__( 'Holiday', 'school-mgt' ), 'management', 'smgt_holiday', 'mj_smgt_school_dashboard');
				}
				if($library == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Library', 'school-mgt' ), esc_attr__( 'Library', 'school-mgt' ), 'management', 'smgt_library', 'mj_smgt_school_dashboard');
				}
				if($custom_field == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Custom Fields', 'school-mgt' ), esc_attr__( 'Custom Fields', 'school-mgt' ), 'management', 'custom_field', 'mj_smgt_school_dashboard');
				}
				if($report == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Report', 'school-mgt' ), esc_attr__( 'Report', 'school-mgt' ), 'management', 'smgt_report', 'mj_smgt_school_dashboard');
				}
				if($migration == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'Migration', 'school-mgt' ), esc_attr__( 'Migration', 'school-mgt' ), 'management', 'smgt_Migration', 'mj_smgt_school_dashboard');
				}
				if($sms_setting == 1)
				{
				add_submenu_page('smgt_school', esc_attr__( 'SMS Setting', 'school-mgt' ), esc_attr__( 'SMS Setting', 'school-mgt' ), 'management', 'smgt_sms-setting', 'mj_smgt_school_dashboard');
				}
				if($email_template == 1)
				{
				
				add_submenu_page('smgt_school',esc_attr__('Email Template','school-mgt'),esc_attr__('Email Template','school-mgt'),'management','smgt_email_template','mj_smgt_school_dashboard');
				}
				if($access_right == 1)
				{
				add_submenu_page('smgt_school',esc_attr__('Access Right','school-mgt'),esc_attr__('Access Right','school-mgt'),'management','smgt_access_right','mj_smgt_school_dashboard');
				}
				
				if($general_settings == 1)
				{
				add_submenu_page('smgt_school',  esc_attr__( 'General Settings', 'school-mgt' ), esc_attr__( 'General Settings', 'school-mgt' ), 'management', 'smgt_gnrl_settings', 'mj_smgt_school_dashboard');
				}
			}
		}	
	else
	{ 		 		
		die;
	}
}

function mj_smgt_school_dashboard()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/dasboard.php';
	
}
?>
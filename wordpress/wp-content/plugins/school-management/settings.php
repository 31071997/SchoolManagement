<?php
	
// This is Setting page of school management plugin
$file_path = plugin_dir_path(__FILE__) . 'assets/css/dynamic-style.php';

require_once SMS_PLUGIN_DIR . '/includes/class-attendence-manage.php';

require_once SMS_PLUGIN_DIR . '/smgt-function.php';

require_once SMS_PLUGIN_DIR . '/includes/class-marks-manage.php';

require_once SMS_PLUGIN_DIR . '/school-management-class.php';

require_once SMS_PLUGIN_DIR . '/includes/class-routine.php';

require_once SMS_PLUGIN_DIR . '/includes/class-dashboard.php';

require_once SMS_PLUGIN_DIR . '/includes/class-payment.php';

require_once SMS_PLUGIN_DIR . '/includes/class-fees.php';

require_once SMS_PLUGIN_DIR . '/includes/class-homework.php';

require_once SMS_PLUGIN_DIR . '/includes/class-feespayment.php';

require_once SMS_PLUGIN_DIR . '/lib/paypal/paypal_class.php';

require_once SMS_PLUGIN_DIR . '/includes/class-library.php';

require_once SMS_PLUGIN_DIR . '/includes/class-teacher.php';

require_once SMS_PLUGIN_DIR . '/includes/class-exam.php';

require_once SMS_PLUGIN_DIR . '/includes/class-admissioin.php';

require_once SMS_PLUGIN_DIR . '/includes/class-hostel.php';

require_once SMS_PLUGIN_DIR . '/includes/class-subject.php';

require_once SMS_PLUGIN_DIR . '/includes/custome_field.php';

require_once SMS_PLUGIN_DIR . '/includes/class_virtual_classroom.php';

require_once SMS_PLUGIN_DIR . '/includes/class-event.php';

require_once SMS_PLUGIN_DIR . '/includes/leave.php';

require_once SMS_PLUGIN_DIR . '/includes/document.php';

require_once SMS_PLUGIN_DIR . '/includes/class-tax.php';

// require_once SMS_PLUGIN_DIR . '/assets/css/dynamic_css.php';

function mj_smgt_role_exists($role)

{



	if (!empty($role)) {

		return $GLOBALS['wp_roles']->is_role($role);
	}

	return false;
}

function mj_smgt_add_role_caps()

{

	// gets the author role

	if (mj_smgt_role_exists('teacher')) {

		// The 'editor' role exists!

		$role = get_role('teacher');

		$role->add_cap('read');

		$role->add_cap('level_0');
	}

	if (mj_smgt_role_exists('student')) {

		// The 'editor' role exists!

		$role = get_role('student');

		$role->add_cap('read');

		$role->add_cap('level_0');
	}

	if (mj_smgt_role_exists('parent')) {

		// The 'editor' role exists!

		$role = get_role('parent');

		$role->add_cap('read');

		$role->add_cap('level_0');
	}

	if (!mj_smgt_role_exists('supportstaff')) {

		// The 'editor' role exists!

		add_role('supportstaff', esc_attr__('Support Staff', 'school-mgt'), array('read' => true, 'level_0' => true));
	}

	if (!mj_smgt_role_exists('student_temp')) {

		// The 'editor' role exists!

		add_role('student_temp', esc_attr__('student_temp', 'school-mgt'), array('read' => true, 'level_0' => true));
	}

	if (!mj_smgt_role_exists('management')) {

		add_role('management', __('Management', 'school-mgt'), array('read' => true, 'level_1' => true));
	}
}

add_action('admin_init', 'mj_smgt_add_role_caps');



add_action('admin_bar_menu', 'mj_smgt_school_dashboard_link', 999);



function mj_smgt_school_dashboard_link($wp_admin_bar)
{

	$args = array(

		'id'    => 'school-dashboard',

		'title' => esc_attr__('School Dashboard', 'school-mgt'),

		'href'  => home_url() . '?dashboard=user',

		'meta'  => array('class' => 'smgt-school-dashboard')

	);

	$wp_admin_bar->add_node($args);
}



add_action('admin_head', 'mj_smgt_admin_css');
function mj_smgt_admin_css()

{  ?>

	<link rel="stylesheet" href="<?php echo SMS_PLUGIN_URL . '/assets/css/settings/setting_admin.css'; ?>">

<?php

}



add_action('init', 'mj_smgt_amgt_session_manager');
function mj_smgt_amgt_session_manager()

{

	if (!session_id()) {

		session_start();

		if (!isset($_SESSION['cmgt_verify'])) {

			$_SESSION['cmgt_verify'] = '';
		}
	}



	session_write_close();
}





function mj_smgt_logout()
{

	if (isset($_SESSION['cmgt_verify'])) {
		unset($_SESSION['cmgt_verify']);
	}
}

add_action('wp_logout', 'mj_smgt_logout');

add_action('init', 'mj_smgt_setup'); 
function mj_smgt_setup()
{

	$is_cmgt_pluginpage = mj_smgt_is_cmgtpage();

	$is_verify = false;

	if (!isset($_SESSION['cmgt_verify']))

		$_SESSION['cmgt_verify'] = '';

	$server_name = $_SERVER['SERVER_NAME'];

	$is_localserver = mj_smgt_chekserver($server_name);



	if ($is_localserver) {

		return true;
	}

	if ($is_cmgt_pluginpage) {

		if ($_SESSION['cmgt_verify'] == '') {



			if (get_option('licence_key') && get_option('cmgt_setup_email')) {

				$domain_name = $_SERVER['SERVER_NAME'];

				$licence_key = get_option('licence_key');

				$email = get_option('cmgt_setup_email');

				$result = mj_smgt_check_productkey($domain_name, $licence_key, $email);

				$is_server_running = mj_smgt_check_ourserver();

				if ($is_server_running)

					$_SESSION['cmgt_verify'] = $result;

				else

					$_SESSION['cmgt_verify'] = '0';

				$is_verify = mj_smgt_check_verify_or_not($result);
			}
		}
	}

	$is_verify = mj_smgt_check_verify_or_not($_SESSION['cmgt_verify']);

	if ($is_cmgt_pluginpage)

		if ($is_verify) {

			$_SESSION['cmgt_verify'] = '';

			if ($_REQUEST['page'] != 'smgt_setup')

				wp_redirect(admin_url() . 'admin.php?page=smgt_setup');
		}
}
if (is_admin()) {
	require_once SMS_PLUGIN_DIR . '/admin/admin.php';

	function mj_smgt_school_install()
	{

		add_role('teacher', esc_attr__('Teacher', 'school-mgt'), array('read' => true, 'level_0' => true));

		add_role('student', esc_attr__('Student', 'school-mgt'), array('read' => true, 'level_0' => true));

		add_role('parent', esc_attr__('Parent', 'school-mgt'), array('read' => true, 'level_0' => true));

		add_role('supportstaff', esc_attr__('Support Staff', 'school-mgt'), array('read' => true, 'level_0' => true));

		add_role('management', __('Management', 'school-mgt'), array('read' => true, 'level_1' => true));

		mj_smgt_install_tables();
		mj_smgt_register_post();
		mj_smgt_attendance_migratation_for_new_table();
	}
	register_activation_hook(SMS_PLUGIN_BASENAME, 'mj_smgt_school_install');

	function mj_smgt_option()

	{

		$role_access_right_student = array();

		$role_access_right_student['student'] = [

			"teacher" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"app_icone" => plugins_url('school-management/assets/images/icons/app_icon/teacher.png'),

				"menu_title" => 'Teacher',

				"page_link" => 'teacher',

				"own_data" => isset($_REQUEST['teacher_own_data']) ? $_REQUEST['teacher_own_data'] : 1,

				"add" => isset($_REQUEST['teacher_add']) ? $_REQUEST['teacher_add'] : 0,

				"edit" => isset($_REQUEST['teacher_edit']) ? $_REQUEST['teacher_edit'] : 0,

				"view" => isset($_REQUEST['teacher_view']) ? $_REQUEST['teacher_view'] : 1,

				"delete" => isset($_REQUEST['teacher_delete']) ? $_REQUEST['teacher_delete'] : 0

			],



			"student" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/student-icon.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/student.png'),

				"menu_title" => 'Student',

				"page_link" => 'student',

				"own_data" => isset($_REQUEST['student_own_data']) ? $_REQUEST['student_own_data'] : 1,

				"add" => isset($_REQUEST['student_add']) ? $_REQUEST['student_add'] : 0,

				"edit" => isset($_REQUEST['student_edit']) ? $_REQUEST['student_edit'] : 0,

				"view" => isset($_REQUEST['student_view']) ? $_REQUEST['student_view'] : 1,

				"delete" => isset($_REQUEST['student_delete']) ? $_REQUEST['student_delete'] : 0

			],



			"parent" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/parents.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/parents.png'),

				"menu_title" => 'Parent',

				"page_link" => 'parent',

				"own_data" => isset($_REQUEST['parent_own_data']) ? $_REQUEST['parent_own_data'] : 1,

				"add" => isset($_REQUEST['parent_add']) ? $_REQUEST['parent_add'] : 0,

				"edit" => isset($_REQUEST['parent_edit']) ? $_REQUEST['parent_edit'] : 0,

				"view" => isset($_REQUEST['parent_view']) ? $_REQUEST['parent_view'] : 1,

				"delete" => isset($_REQUEST['parent_delete']) ? $_REQUEST['parent_delete'] : 0

			],



			"supportstaff" => [

				"menu_icone" => plugins_url('school-management/assets/images/icons/support-staff.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/support-staff.png'),

				"menu_title" => 'Supportstaff',

				"page_link" => 'supportstaff',

				"own_data" => isset($_REQUEST['supportstaff_own_data']) ? $_REQUEST['supportstaff_own_data'] : 0,

				"add" => isset($_REQUEST['supportstaff_add']) ? $_REQUEST['supportstaff_add'] : 0,

				"edit" => isset($_REQUEST['supportstaff_edit']) ? $_REQUEST['supportstaff_edit'] : 0,

				"view" => isset($_REQUEST['supportstaff_view']) ? $_REQUEST['supportstaff_view'] : 1,

				"delete" => isset($_REQUEST['supportstaff_delete']) ? $_REQUEST['supportstaff_delete'] : 0

			],

			"subject" => [

				'menu_icone' => plugins_url('school-management/assets/images/icons/subject.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/subject.png'),

				"menu_title" => 'Subject',

				"page_link" => 'subject',

				"own_data" => isset($_REQUEST['subject_own_data']) ? $_REQUEST['subject_own_data'] : 1,

				"add" => isset($_REQUEST['subject_add']) ? $_REQUEST['subject_add'] : 0,

				"edit" => isset($_REQUEST['subject_edit']) ? $_REQUEST['subject_edit'] : 0,

				"view" => isset($_REQUEST['subject_view']) ? $_REQUEST['subject_view'] : 1,

				"delete" => isset($_REQUEST['subject_delete']) ? $_REQUEST['subject_delete'] : 0

			],



			"schedule" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/class-route.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/class-route.png'),

				"menu_title" => 'Class Routine',

				"page_link" => 'schedule',

				"own_data" => isset($_REQUEST['schedule_own_data']) ? $_REQUEST['schedule_own_data'] : 1,

				"add" => isset($_REQUEST['schedule_add']) ? $_REQUEST['schedule_add'] : 0,

				"edit" => isset($_REQUEST['schedule_edit']) ? $_REQUEST['schedule_edit'] : 0,

				"view" => isset($_REQUEST['schedule_view']) ? $_REQUEST['schedule_view'] : 1,

				"delete" => isset($_REQUEST['schedule_delete']) ? $_REQUEST['schedule_delete'] : 0

			],

			"virtual_classroom" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/virtual_classroom.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/virtual_class.png'),

				"menu_title" => 'virtual_classroom',

				"page_link" => 'virtual_classroom',

				"own_data" => isset($_REQUEST['virtual_classroom_own_data']) ? $_REQUEST['virtual_classroom_own_data'] : 1,

				"add" => isset($_REQUEST['virtual_classroom_add']) ? $_REQUEST['virtual_classroom_add'] : 0,

				"edit" => isset($_REQUEST['virtual_classroom_edit']) ? $_REQUEST['virtual_classroom_edit'] : 0,

				"view" => isset($_REQUEST['virtual_classroom_view']) ? $_REQUEST['virtual_classroom_view'] : 1,

				"delete" => isset($_REQUEST['virtual_classroom_delete']) ? $_REQUEST['virtual_classroom_delete'] : 0

			],



			"attendance" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/attandance.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/attandance.png'),

				"menu_title" => 'Attendance',

				"page_link" => 'attendance',

				"own_data" => isset($_REQUEST['attendance_own_data']) ? $_REQUEST['attendance_own_data'] : 1,

				"add" => isset($_REQUEST['attendance_add']) ? $_REQUEST['attendance_add'] : 0,

				"edit" => isset($_REQUEST['attendance_edit']) ? $_REQUEST['attendance_edit'] : 0,

				"view" => isset($_REQUEST['attendance_view']) ? $_REQUEST['attendance_view'] : 1,

				"delete" => isset($_REQUEST['attendance_delete']) ? $_REQUEST['attendance_delete'] : 0

			],

			"notification" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				"menu_title" => 'Notification',

				"page_link" => 'notification',

				"own_data" => isset($_REQUEST['notification_own_data']) ? $_REQUEST['notification_own_data'] : 1,

				"add" => isset($_REQUEST['notification_add']) ? $_REQUEST['notification_add'] : 0,

				"edit" => isset($_REQUEST['notification_edit']) ? $_REQUEST['notification_edit'] : 0,

				"view" => isset($_REQUEST['notification_view']) ? $_REQUEST['notification_view'] : 1,

				"delete" => isset($_REQUEST['notification_delete']) ? $_REQUEST['notification_delete'] : 0

			],

			"exam" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/exam.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/exam.png'),

				"menu_title" => 'Exam',

				"page_link" => 'exam',

				"own_data" => isset($_REQUEST['exam_own_data']) ? $_REQUEST['exam_own_data'] : 1,

				"add" => isset($_REQUEST['exam_add']) ? $_REQUEST['exam_add'] : 0,

				"edit" => isset($_REQUEST['exam_edit']) ? $_REQUEST['exam_edit'] : 0,

				"view" => isset($_REQUEST['exam_view']) ? $_REQUEST['exam_view'] : 1,

				"delete" => isset($_REQUEST['exam_delete']) ? $_REQUEST['exam_delete'] : 0

			],



			"grade" =>

			[
				"menu_icone" => plugins_url('school-management/assets/images/icons/grade.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/grade.png'),

				"menu_title" => 'Grade',

				"page_link" => 'grade',

				"own_data" => isset($_REQUEST['grade_own_data']) ? $_REQUEST['grade_own_data'] : 0,

				"add" => isset($_REQUEST['grade_add']) ? $_REQUEST['grade_add'] : 0,

				"edit" => isset($_REQUEST['grade_edit']) ? $_REQUEST['grade_edit'] : 0,

				"view" => isset($_REQUEST['grade_view']) ? $_REQUEST['grade_view'] : 1,

				"delete" => isset($_REQUEST['grade_delete']) ? $_REQUEST['grade_delete'] : 0

			],



			"hostel" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/hostel.png'),

				"menu_title" => 'Hostel',

				"page_link" => 'hostel',

				"own_data" => isset($_REQUEST['hostel_own_data']) ? $_REQUEST['hostel_own_data'] : 1,

				"add" => isset($_REQUEST['hostel_add']) ? $_REQUEST['hostel_add'] : 0,

				"edit" => isset($_REQUEST['hostel_edit']) ? $_REQUEST['hostel_edit'] : 0,

				"view" => isset($_REQUEST['hostel_view']) ? $_REQUEST['hostel_view'] : 1,

				"delete" => isset($_REQUEST['hostel_delete']) ? $_REQUEST['hostel_delete'] : 0

			],

			"document" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/hostel.png'),

				"menu_title" => 'Document',

				"page_link" => 'document',

				"own_data" => isset($_REQUEST['document_own_data']) ? $_REQUEST['document_own_data'] : 1,

				"add" => isset($_REQUEST['document_add']) ? $_REQUEST['document_add'] : 0,

				"edit" => isset($_REQUEST['document_edit']) ? $_REQUEST['document_edit'] : 0,

				"view" => isset($_REQUEST['document_view']) ? $_REQUEST['document_view'] : 1,

				"delete" => isset($_REQUEST['document_delete']) ? $_REQUEST['document_delete'] : 0

			],

			"leave" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				"menu_title" => 'Leave',

				"page_link" => 'leave',

				"own_data" => isset($_REQUEST['leave_own_data']) ? $_REQUEST['leave_own_data'] : 1,

				"add" => isset($_REQUEST['leave_add']) ? $_REQUEST['leave_add'] : 1,

				"edit" => isset($_REQUEST['leave_edit']) ? $_REQUEST['leave_edit'] : 0,

				"view" => isset($_REQUEST['leave_view']) ? $_REQUEST['leave_view'] : 1,

				"delete" => isset($_REQUEST['leave_delete']) ? $_REQUEST['leave_delete'] : 0

			],

			"homework" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/homework.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/homework.png'),

				"menu_title" => 'Home Work',

				"page_link" => 'homework',

				"own_data" => isset($_REQUEST['homework_own_data']) ? $_REQUEST['homework_own_data'] : 1,

				"add" => isset($_REQUEST['homework_add']) ? $_REQUEST['homework_add'] : 0,

				"edit" => isset($_REQUEST['homework_edit']) ? $_REQUEST['homework_edit'] : 0,

				"view" => isset($_REQUEST['homework_view']) ? $_REQUEST['homework_view'] : 1,

				"delete" => isset($_REQUEST['homework_delete']) ? $_REQUEST['homework_delete'] : 0

			],

			"manage_marks" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/mark-manage.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/mark-manage.png'),

				"menu_title" => 'Mark Manage',

				"page_link" => 'manage_marks',

				"own_data" => isset($_REQUEST['manage_marks_own_data']) ? $_REQUEST['manage_marks_own_data'] : 0,

				"add" => isset($_REQUEST['manage_marks_add']) ? $_REQUEST['manage_marks_add'] : 0,

				"edit" => isset($_REQUEST['manage_marks_edit']) ? $_REQUEST['manage_marks_edit'] : 0,

				"view" => isset($_REQUEST['manage_marks_view']) ? $_REQUEST['manage_marks_view'] : 0,

				"delete" => isset($_REQUEST['manage_marks_delete']) ? $_REQUEST['manage_marks_delete'] : 0

			],



			"feepayment" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/fee.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/fee_payment.png'),

				"menu_title" => 'Fees Payment',

				"page_link" => 'feepayment',

				"own_data" => isset($_REQUEST['feepayment_own_data']) ? $_REQUEST['feepayment_own_data'] : 1,

				"add" => isset($_REQUEST['feepayment_add']) ? $_REQUEST['feepayment_add'] : 0,

				"edit" => isset($_REQUEST['feepayment_edit']) ? $_REQUEST['feepayment_edit'] : 0,

				"view" => isset($_REQUEST['feepayment_view']) ? $_REQUEST['feepayment_view'] : 1,

				"delete" => isset($_REQUEST['feepayment_delete']) ? $_REQUEST['feepayment_delete'] : 0

			],



			"payment" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/payment.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/payment.png'),

				"menu_title" => 'Payment',

				"page_link" => 'payment',

				"own_data" => isset($_REQUEST['payment_own_data']) ? $_REQUEST['payment_own_data'] : 1,

				"add" => isset($_REQUEST['payment_add']) ? $_REQUEST['payment_add'] : 0,

				"edit" => isset($_REQUEST['payment_edit']) ? $_REQUEST['payment_edit'] : 0,

				"view" => isset($_REQUEST['payment_view']) ? $_REQUEST['payment_view'] : 1,

				"delete" => isset($_REQUEST['payment_delete']) ? $_REQUEST['payment_delete'] : 0

			],

			"transport" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/transport.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/transport.png'),

				"menu_title" => 'Transport',

				"page_link" => 'transport',

				"own_data" => isset($_REQUEST['transport_own_data']) ? $_REQUEST['transport_own_data'] : 0,

				"add" => isset($_REQUEST['transport_add']) ? $_REQUEST['transport_add'] : 0,

				"edit" => isset($_REQUEST['transport_edit']) ? $_REQUEST['transport_edit'] : 0,

				"view" => isset($_REQUEST['transport_view']) ? $_REQUEST['transport_view'] : 1,

				"delete" => isset($_REQUEST['transport_delete']) ? $_REQUEST['transport_delete'] : 0

			],

			"notice" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notice.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/notice.png'),

				"menu_title" => 'Notice Board',

				"page_link" => 'notice',

				"own_data" => isset($_REQUEST['notice_own_data']) ? $_REQUEST['notice_own_data'] : 1,

				"add" => isset($_REQUEST['notice_add']) ? $_REQUEST['notice_add'] : 0,

				"edit" => isset($_REQUEST['notice_edit']) ? $_REQUEST['notice_edit'] : 0,

				"view" => isset($_REQUEST['notice_view']) ? $_REQUEST['notice_view'] : 1,

				"delete" => isset($_REQUEST['notice_delete']) ? $_REQUEST['notice_delete'] : 0

			],

			"message" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/message.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/message.png'),

				"menu_title" => 'Message',

				"page_link" => 'message',

				"own_data" => isset($_REQUEST['message_own_data']) ? $_REQUEST['message_own_data'] : 1,

				"add" => isset($_REQUEST['message_add']) ? $_REQUEST['message_add'] : 1,

				"edit" => isset($_REQUEST['message_edit']) ? $_REQUEST['message_edit'] : 0,

				"view" => isset($_REQUEST['message_view']) ? $_REQUEST['message_view'] : 1,

				"delete" => isset($_REQUEST['message_delete']) ? $_REQUEST['message_delete'] : 1

			],

			"holiday" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/holiday.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/holiday.png'),

				"menu_title" => 'Holiday',

				"page_link" => 'holiday',

				"own_data" => isset($_REQUEST['holiday_own_data']) ? $_REQUEST['holiday_own_data'] : 0,

				"add" => isset($_REQUEST['holiday_add']) ? $_REQUEST['holiday_add'] : 0,

				"edit" => isset($_REQUEST['holiday_edit']) ? $_REQUEST['holiday_edit'] : 0,

				"view" => isset($_REQUEST['holiday_view']) ? $_REQUEST['holiday_view'] : 1,

				"delete" => isset($_REQUEST['holiday_delete']) ? $_REQUEST['holiday_delete'] : 0

			],



			"library" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/library.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/library.png'),

				"menu_title" => 'Library',

				"page_link" => 'library',

				"own_data" => isset($_REQUEST['library_own_data']) ? $_REQUEST['library_own_data'] : 1,

				"add" => isset($_REQUEST['library_add']) ? $_REQUEST['library_add'] : 0,

				"edit" => isset($_REQUEST['library_edit']) ? $_REQUEST['library_edit'] : 0,

				"view" => isset($_REQUEST['library_view']) ? $_REQUEST['library_view'] : 1,

				"delete" => isset($_REQUEST['library_delete']) ? $_REQUEST['library_delete'] : 0

			],



			"account" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/account.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/account.png'),

				"menu_title" => 'Account',

				"page_link" => 'account',

				"own_data" => isset($_REQUEST['account_own_data']) ? $_REQUEST['account_own_data'] : 1,

				"add" => isset($_REQUEST['account_add']) ? $_REQUEST['account_add'] : 0,

				"edit" => isset($_REQUEST['account_edit']) ? $_REQUEST['account_edit'] : 1,

				"view" => isset($_REQUEST['account_view']) ? $_REQUEST['account_view'] : 1,

				"delete" => isset($_REQUEST['account_delete']) ? $_REQUEST['account_delete'] : 0

			],



			"report" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/app_icon/report.png'),

				"menu_title" => 'Report',

				"page_link" => 'report',

				"own_data" => isset($_REQUEST['report_own_data']) ? $_REQUEST['report_own_data'] : 0,

				"add" => isset($_REQUEST['report_add']) ? $_REQUEST['report_add'] : 0,

				"edit" => isset($_REQUEST['report_edit']) ? $_REQUEST['report_edit'] : 0,

				"view" => isset($_REQUEST['report_view']) ? $_REQUEST['report_view'] : 0,

				"delete" => isset($_REQUEST['report_delete']) ? $_REQUEST['report_delete'] : 0

			],

			"event" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

				"menu_title" => 'Event',

				"page_link" => 'event',

				"own_data" => isset($_REQUEST['event_own_data']) ? $_REQUEST['event_own_data'] : 0,

				"add" => isset($_REQUEST['event_add']) ? $_REQUEST['event_add'] : 0,

				"edit" => isset($_REQUEST['event_edit']) ? $_REQUEST['event_edit'] : 0,

				"view" => isset($_REQUEST['event_view']) ? $_REQUEST['event_view'] : 1,

				"delete" => isset($_REQUEST['event_delete']) ? $_REQUEST['event_delete'] : 0

			]

		];

		$role_access_right_teacher = array();

		$role_access_right_teacher['teacher'] = [

			"admission" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/admission.png'),

				"menu_title" => 'Admission',

				"page_link" => 'admission',

				"own_data" => isset($_REQUEST['admission_own_data']) ? $_REQUEST['admission_own_data'] : 0,

				"add" => isset($_REQUEST['admission_add']) ? $_REQUEST['admission_add'] : 1,

				"edit" => isset($_REQUEST['admission_edit']) ? $_REQUEST['admission_edit'] : 1,

				"view" => isset($_REQUEST['admission_view']) ? $_REQUEST['admission_view'] : 1,

				"delete" => isset($_REQUEST['admission_delete']) ? $_REQUEST['admission_delete'] : 0

			],

			"teacher" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Teacher',

				"page_link" => 'teacher',

				"own_data" => isset($_REQUEST['teacher_own_data']) ? $_REQUEST['teacher_own_data'] : 1,

				"add" => isset($_REQUEST['teacher_add']) ? $_REQUEST['teacher_add'] : 0,

				"edit" => isset($_REQUEST['teacher_edit']) ? $_REQUEST['teacher_edit'] : 0,

				"view" => isset($_REQUEST['teacher_view']) ? $_REQUEST['teacher_view'] : 1,

				"delete" => isset($_REQUEST['teacher_delete']) ? $_REQUEST['teacher_delete'] : 0

			],



			"student" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/student-icon.png'),

				"menu_title" => 'Student',

				"page_link" => 'student',

				"own_data" => isset($_REQUEST['student_own_data']) ? $_REQUEST['student_own_data'] : 1,

				"add" => isset($_REQUEST['student_add']) ? $_REQUEST['student_add'] : 1,

				"edit" => isset($_REQUEST['student_edit']) ? $_REQUEST['student_edit'] : 0,

				"view" => isset($_REQUEST['student_view']) ? $_REQUEST['student_view'] : 1,

				"delete" => isset($_REQUEST['student_delete']) ? $_REQUEST['student_delete'] : 0

			],



			"parent" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/parents.png'),

				"menu_title" => 'Parent',

				"page_link" => 'parent',

				"own_data" => isset($_REQUEST['parent_own_data']) ? $_REQUEST['parent_own_data'] : 1,

				"add" => isset($_REQUEST['parent_add']) ? $_REQUEST['parent_add'] : 1,

				"edit" => isset($_REQUEST['parent_edit']) ? $_REQUEST['parent_edit'] : 0,

				"view" => isset($_REQUEST['parent_view']) ? $_REQUEST['parent_view'] : 1,

				"delete" => isset($_REQUEST['parent_delete']) ? $_REQUEST['parent_delete'] : 0

			],



			"subject" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/subject.png'),

				"menu_title" => 'Subject',

				"page_link" => 'subject',

				"own_data" => isset($_REQUEST['subject_own_data']) ? $_REQUEST['subject_own_data'] : 1,

				"add" => isset($_REQUEST['subject_add']) ? $_REQUEST['subject_add'] : 1,

				"edit" => isset($_REQUEST['subject_edit']) ? $_REQUEST['subject_edit'] : 1,

				"view" => isset($_REQUEST['subject_view']) ? $_REQUEST['subject_view'] : 1,

				"delete" => isset($_REQUEST['subject_delete']) ? $_REQUEST['subject_delete'] : 0

			],

			"class" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/class.png'),

				"menu_title" => 'Class',

				"page_link" => 'class',

				"own_data" => isset($_REQUEST['class_own_data']) ? $_REQUEST['class_own_data'] : 1,

				"add" => isset($_REQUEST['class_add']) ? $_REQUEST['class_add'] : 0,

				"edit" => isset($_REQUEST['class_edit']) ? $_REQUEST['class_edit'] : 0,

				"view" => isset($_REQUEST['class_view']) ? $_REQUEST['class_view'] : 1,

				"delete" => isset($_REQUEST['class_delete']) ? $_REQUEST['class_delete'] : 0

			],



			"virtual_classroom" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/virtual_classroom.png'),

				"menu_title" => 'virtual_classroom',

				"page_link" => 'virtual_classroom',

				"own_data" => isset($_REQUEST['virtual_classroom_own_data']) ? $_REQUEST['virtual_classroom_own_data'] : 0,

				"add" => isset($_REQUEST['virtual_classroom_add']) ? $_REQUEST['virtual_classroom_add'] : 1,

				"edit" => isset($_REQUEST['virtual_classroom_edit']) ? $_REQUEST['virtual_classroom_edit'] : 1,

				"view" => isset($_REQUEST['virtual_classroom_view']) ? $_REQUEST['virtual_classroom_view'] : 1,

				"delete" => isset($_REQUEST['virtual_classroom_delete']) ? $_REQUEST['virtual_classroom_delete'] : 0

			],



			"schedule" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/class-route.png'),

				"menu_title" => 'Class Routine',

				"page_link" => 'schedule',

				"own_data" => isset($_REQUEST['schedule_own_data']) ? $_REQUEST['schedule_own_data'] : 1,

				"add" => isset($_REQUEST['schedule_add']) ? $_REQUEST['schedule_add'] : 1,

				"edit" => isset($_REQUEST['schedule_edit']) ? $_REQUEST['schedule_edit'] : 0,

				"view" => isset($_REQUEST['schedule_view']) ? $_REQUEST['schedule_view'] : 1,

				"delete" => isset($_REQUEST['schedule_delete']) ? $_REQUEST['schedule_delete'] : 0

			],

			"attendance" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/attandance.png'),

				"menu_title" => 'Attendance',

				"page_link" => 'attendance',

				"own_data" => isset($_REQUEST['attendance_own_data']) ? $_REQUEST['attendance_own_data'] : 1,

				"add" => isset($_REQUEST['attendance_add']) ? $_REQUEST['attendance_add'] : 1,

				"edit" => isset($_REQUEST['attendance_edit']) ? $_REQUEST['attendance_edit'] : 1,

				"view" => isset($_REQUEST['attendance_view']) ? $_REQUEST['attendance_view'] : 1,

				"delete" => isset($_REQUEST['attendance_delete']) ? $_REQUEST['attendance_delete'] : 0

			],

			"notification" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/attandance.png'),

				"menu_title" => 'Notification',

				"page_link" => 'notification',

				"own_data" => isset($_REQUEST['notification_own_data']) ? $_REQUEST['notification_own_data'] : 1,

				"add" => isset($_REQUEST['notification_add']) ? $_REQUEST['notification_add'] : 1,

				"edit" => isset($_REQUEST['notification_edit']) ? $_REQUEST['notification_edit'] : 1,

				"view" => isset($_REQUEST['notification_view']) ? $_REQUEST['notification_view'] : 1,

				"delete" => isset($_REQUEST['notification_delete']) ? $_REQUEST['notification_delete'] : 1

			],

			"exam" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/exam.png'),

				"menu_title" => 'Exam',

				"page_link" => 'exam',

				"own_data" => isset($_REQUEST['exam_own_data']) ? $_REQUEST['exam_own_data'] : 1,

				"add" => isset($_REQUEST['exam_add']) ? $_REQUEST['exam_add'] : 1,

				"edit" => isset($_REQUEST['exam_edit']) ? $_REQUEST['exam_edit'] : 1,

				"view" => isset($_REQUEST['exam_view']) ? $_REQUEST['exam_view'] : 1,

				"delete" => isset($_REQUEST['exam_delete']) ? $_REQUEST['exam_delete'] : 0

			],



			"exam_hall" =>

			[
				"menu_icone" => plugins_url('school-management/assets/images/icons/exam_hall.png'),

				"menu_title" => 'Exam Hall',

				"page_link" => 'exam_hall',

				"own_data" => isset($_REQUEST['exam_hall_own_data']) ? $_REQUEST['exam_hall_own_data'] : 1,

				"add" => isset($_REQUEST['exam_hall_add']) ? $_REQUEST['exam_hall_add'] : 1,

				"edit" => isset($_REQUEST['exam_hall_edit']) ? $_REQUEST['exam_hall_edit'] : 1,

				"view" => isset($_REQUEST['exam_hall_view']) ? $_REQUEST['exam_hall_view'] : 1,

				"delete" => isset($_REQUEST['exam_hall_delete']) ? $_REQUEST['exam_hall_delete'] : 0

			],





			"hostel" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

				"menu_title" => 'Hostel',

				"page_link" => 'hostel',

				"own_data" => isset($_REQUEST['hostel_own_data']) ? $_REQUEST['hostel_own_data'] : 0,

				"add" => isset($_REQUEST['hostel_add']) ? $_REQUEST['hostel_add'] : 0,

				"edit" => isset($_REQUEST['hostel_edit']) ? $_REQUEST['hostel_edit'] : 0,

				"view" => isset($_REQUEST['hostel_view']) ? $_REQUEST['hostel_view'] : 1,

				"delete" => isset($_REQUEST['hostel_delete']) ? $_REQUEST['hostel_delete'] : 0

			],

			"homework" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/homework.png'),

				"menu_title" => 'Home Work',

				"page_link" => 'homework',

				"own_data" => isset($_REQUEST['homework_own_data']) ? $_REQUEST['homework_own_data'] : 1,

				"add" => isset($_REQUEST['homework_add']) ? $_REQUEST['homework_add'] : 1,

				"edit" => isset($_REQUEST['homework_edit']) ? $_REQUEST['homework_edit'] : 1,

				"view" => isset($_REQUEST['homework_view']) ? $_REQUEST['homework_view'] : 1,

				"delete" => isset($_REQUEST['homework_delete']) ? $_REQUEST['homework_delete'] : 0

			],

			"manage_marks" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/mark-manage.png'),

				"menu_title" => 'Mark Manage',

				"page_link" => 'manage_marks',

				"own_data" => isset($_REQUEST['manage_marks_own_data']) ? $_REQUEST['manage_marks_own_data'] : 1,

				"add" => isset($_REQUEST['manage_marks_add']) ? $_REQUEST['manage_marks_add'] : 1,

				"edit" => isset($_REQUEST['manage_marks_edit']) ? $_REQUEST['manage_marks_edit'] : 1,

				"view" => isset($_REQUEST['manage_marks_view']) ? $_REQUEST['manage_marks_view'] : 1,

				"delete" => isset($_REQUEST['manage_marks_delete']) ? $_REQUEST['manage_marks_delete'] : 0

			],



			"feepayment" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/fee.png'),

				"menu_title" => 'Fee Payment',

				"page_link" => 'feepayment',

				"own_data" => isset($_REQUEST['feepayment_own_data']) ? $_REQUEST['feepayment_own_data'] : 1,

				"add" => isset($_REQUEST['feepayment_add']) ? $_REQUEST['feepayment_add'] : 1,

				"edit" => isset($_REQUEST['feepayment_edit']) ? $_REQUEST['feepayment_edit'] : 1,

				"view" => isset($_REQUEST['feepayment_view']) ? $_REQUEST['feepayment_view'] : 1,

				"delete" => isset($_REQUEST['feepayment_delete']) ? $_REQUEST['feepayment_delete'] : 0

			],



			"payment" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/payment.png'),

				"menu_title" => 'Payment',

				"page_link" => 'payment',

				"own_data" => isset($_REQUEST['payment_own_data']) ? $_REQUEST['payment_own_data'] : 0,

				"add" => isset($_REQUEST['payment_add']) ? $_REQUEST['payment_add'] : 0,

				"edit" => isset($_REQUEST['payment_edit']) ? $_REQUEST['payment_edit'] : 0,

				"view" => isset($_REQUEST['payment_view']) ? $_REQUEST['payment_view'] : 0,

				"delete" => isset($_REQUEST['payment_delete']) ? $_REQUEST['payment_delete'] : 0

			],

			"transport" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/transport.png'),

				"menu_title" => 'Transport',

				"page_link" => 'transport',

				"own_data" => isset($_REQUEST['transport_own_data']) ? $_REQUEST['transport_own_data'] : 0,

				"add" => isset($_REQUEST['transport_add']) ? $_REQUEST['transport_add'] : 0,

				"edit" => isset($_REQUEST['transport_edit']) ? $_REQUEST['transport_edit'] : 0,

				"view" => isset($_REQUEST['transport_view']) ? $_REQUEST['transport_view'] : 1,

				"delete" => isset($_REQUEST['transport_delete']) ? $_REQUEST['transport_delete'] : 0

			],

			"document" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

				"menu_title" => 'Document',

				"page_link" => 'document',

				"own_data" => isset($_REQUEST['document_own_data']) ? $_REQUEST['document_own_data'] : 1,

				"add" => isset($_REQUEST['document_add']) ? $_REQUEST['document_add'] : 1,

				"edit" => isset($_REQUEST['document_edit']) ? $_REQUEST['document_edit'] : 1,

				"view" => isset($_REQUEST['document_view']) ? $_REQUEST['document_view'] : 1,

				"delete" => isset($_REQUEST['document_delete']) ? $_REQUEST['document_delete'] : 1

			],

			"leave" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				"menu_title" => 'Leave',

				"page_link" => 'leave',

				"own_data" => isset($_REQUEST['leave_own_data']) ? $_REQUEST['leave_own_data'] : 0,

				"add" => isset($_REQUEST['leave_add']) ? $_REQUEST['leave_add'] : 1,

				"edit" => isset($_REQUEST['leave_edit']) ? $_REQUEST['leave_edit'] : 1,

				"view" => isset($_REQUEST['leave_view']) ? $_REQUEST['leave_view'] : 1,

				"delete" => isset($_REQUEST['leave_delete']) ? $_REQUEST['leave_delete'] : 1

			],

			"notice" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notice.png'),

				"menu_title" => 'Notice Board',

				"page_link" => 'notice',

				"own_data" => isset($_REQUEST['notice_own_data']) ? $_REQUEST['notice_own_data'] : 1,

				"add" => isset($_REQUEST['notice_add']) ? $_REQUEST['notice_add'] : 1,

				"edit" => isset($_REQUEST['notice_edit']) ? $_REQUEST['notice_edit'] : 1,

				"view" => isset($_REQUEST['notice_view']) ? $_REQUEST['notice_view'] : 1,

				"delete" => isset($_REQUEST['notice_delete']) ? $_REQUEST['notice_delete'] : 0

			],

			"message" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/message.png'),

				"menu_title" => 'Message',

				"page_link" => 'message',

				"own_data" => isset($_REQUEST['message_own_data']) ? $_REQUEST['message_own_data'] : 1,

				"add" => isset($_REQUEST['message_add']) ? $_REQUEST['message_add'] : 1,

				"edit" => isset($_REQUEST['message_edit']) ? $_REQUEST['message_edit'] : 0,

				"view" => isset($_REQUEST['message_view']) ? $_REQUEST['message_view'] : 1,

				"delete" => isset($_REQUEST['message_delete']) ? $_REQUEST['message_delete'] : 0

			],

			// Migration //

			"migration" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/message.png'),

				"menu_title" => 'Migration',

				"page_link" => 'migration',

				"own_data" => isset($_REQUEST['migration_own_data']) ? $_REQUEST['migration_own_data'] : 0,

				"add" => isset($_REQUEST['migration_add']) ? $_REQUEST['migration_add'] : 1,

				"edit" => isset($_REQUEST['migration_edit']) ? $_REQUEST['migration_edit'] : 0,

				"view" => isset($_REQUEST['migration_view']) ? $_REQUEST['migration_view'] : 1,

				"delete" => isset($_REQUEST['migration_delete']) ? $_REQUEST['migration_delete'] : 0

			],

			"holiday" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/holiday.png'),

				"menu_title" => 'Holiday',

				"page_link" => 'holiday',

				"own_data" => isset($_REQUEST['holiday_own_data']) ? $_REQUEST['holiday_own_data'] : 0,

				"add" => isset($_REQUEST['holiday_add']) ? $_REQUEST['holiday_add'] : 1,

				"edit" => isset($_REQUEST['holiday_edit']) ? $_REQUEST['holiday_edit'] : 1,

				"view" => isset($_REQUEST['holiday_view']) ? $_REQUEST['holiday_view'] : 1,

				"delete" => isset($_REQUEST['holiday_delete']) ? $_REQUEST['holiday_delete'] : 0

			],



			"library" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/library.png'),

				"menu_title" => 'Library',

				"page_link" => 'library',

				"own_data" => isset($_REQUEST['library_own_data']) ? $_REQUEST['library_own_data'] : 1,

				"add" => isset($_REQUEST['library_add']) ? $_REQUEST['library_add'] : 1,

				"edit" => isset($_REQUEST['library_edit']) ? $_REQUEST['library_edit'] : 1,

				"view" => isset($_REQUEST['library_view']) ? $_REQUEST['library_view'] : 1,

				"delete" => isset($_REQUEST['library_delete']) ? $_REQUEST['library_delete'] : 0

			],



			"account" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/account.png'),

				"menu_title" => 'Account',

				"page_link" => 'account',

				"own_data" => isset($_REQUEST['account_own_data']) ? $_REQUEST['account_own_data'] : 1,

				"add" => isset($_REQUEST['account_add']) ? $_REQUEST['account_add'] : 0,

				"edit" => isset($_REQUEST['account_edit']) ? $_REQUEST['account_edit'] : 1,

				"view" => isset($_REQUEST['account_view']) ? $_REQUEST['account_view'] : 1,

				"delete" => isset($_REQUEST['account_delete']) ? $_REQUEST['account_delete'] : 0

			],



			"report" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

				"menu_title" => 'Report',

				"page_link" => 'report',

				"own_data" => isset($_REQUEST['report_own_data']) ? $_REQUEST['report_own_data'] : 0,

				"add" => isset($_REQUEST['report_add']) ? $_REQUEST['report_add'] : 0,

				"edit" => isset($_REQUEST['report_edit']) ? $_REQUEST['report_edit'] : 0,

				"view" => isset($_REQUEST['report_view']) ? $_REQUEST['report_view'] : 1,

				"delete" => isset($_REQUEST['report_delete']) ? $_REQUEST['report_delete'] : 0

			],

			"event" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

				"menu_title" => 'Event',

				"page_link" => 'event',

				"own_data" => isset($_REQUEST['event_own_data']) ? $_REQUEST['event_own_data'] : 0,

				"add" => isset($_REQUEST['event_add']) ? $_REQUEST['event_add'] : 1,

				"edit" => isset($_REQUEST['event_edit']) ? $_REQUEST['event_edit'] : 1,

				"view" => isset($_REQUEST['event_view']) ? $_REQUEST['event_view'] : 1,

				"delete" => isset($_REQUEST['event_delete']) ? $_REQUEST['event_delete'] : 1

			]

		];

		$role_access_right_parent = array();

		$role_access_right_parent['parent'] = [



			//New Module Added //

			//    "admission"=>["menu_icone"=>plugins_url( 'school-management/assets/images/icons/admission.png'),

			// 		   "menu_title"=>'Admission',

			// 		   "page_link"=>'admission',

			// 		   "own_data" =>isset($_REQUEST['admission_own_data'])?$_REQUEST['admission_own_data']:1,

			// 		   "add" =>isset($_REQUEST['admission_add'])?$_REQUEST['admission_add']:0,

			// 			"edit"=>isset($_REQUEST['admission_edit'])?$_REQUEST['admission_edit']:0,

			// 			"view"=>isset($_REQUEST['admission_view'])?$_REQUEST['admission_view']:1,

			// 			"delete"=>isset($_REQUEST['admission_delete'])?$_REQUEST['admission_delete']:0

			// 			],

			"teacher" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Teacher',

				"page_link" => 'teacher',

				"own_data" => isset($_REQUEST['teacher_own_data']) ? $_REQUEST['teacher_own_data'] : 1,

				"add" => isset($_REQUEST['teacher_add']) ? $_REQUEST['teacher_add'] : 0,

				"edit" => isset($_REQUEST['teacher_edit']) ? $_REQUEST['teacher_edit'] : 0,

				"view" => isset($_REQUEST['teacher_view']) ? $_REQUEST['teacher_view'] : 1,

				"delete" => isset($_REQUEST['teacher_delete']) ? $_REQUEST['teacher_delete'] : 0

			],



			"student" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/student-icon.png'),

				"menu_title" => 'Student',

				"page_link" => 'student',

				"own_data" => isset($_REQUEST['student_own_data']) ? $_REQUEST['student_own_data'] : 1,

				"add" => isset($_REQUEST['student_add']) ? $_REQUEST['student_add'] : 0,

				"edit" => isset($_REQUEST['student_edit']) ? $_REQUEST['student_edit'] : 0,

				"view" => isset($_REQUEST['student_view']) ? $_REQUEST['student_view'] : 1,

				"delete" => isset($_REQUEST['student_delete']) ? $_REQUEST['student_delete'] : 0

			],



			"parent" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/parents.png'),

				"menu_title" => 'Parent',

				"page_link" => 'parent',

				"own_data" => isset($_REQUEST['parent_own_data']) ? $_REQUEST['parent_own_data'] : 1,

				"add" => isset($_REQUEST['parent_add']) ? $_REQUEST['parent_add'] : 0,

				"edit" => isset($_REQUEST['parent_edit']) ? $_REQUEST['parent_edit'] : 0,

				"view" => isset($_REQUEST['parent_view']) ? $_REQUEST['parent_view'] : 1,

				"delete" => isset($_REQUEST['parent_delete']) ? $_REQUEST['parent_delete'] : 0

			],



			"subject" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/subject.png'),

				"menu_title" => 'Subject',

				"page_link" => 'subject',

				"own_data" => isset($_REQUEST['subject_own_data']) ? $_REQUEST['subject_own_data'] : 1,

				"add" => isset($_REQUEST['subject_add']) ? $_REQUEST['subject_add'] : 0,

				"edit" => isset($_REQUEST['subject_edit']) ? $_REQUEST['subject_edit'] : 0,

				"view" => isset($_REQUEST['subject_view']) ? $_REQUEST['subject_view'] : 1,

				"delete" => isset($_REQUEST['subject_delete']) ? $_REQUEST['subject_delete'] : 0

			],



			"schedule" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/class-route.png'),

				"menu_title" => 'Class Routine',

				"page_link" => 'schedule',

				"own_data" => isset($_REQUEST['schedule_own_data']) ? $_REQUEST['schedule_own_data'] : 1,

				"add" => isset($_REQUEST['schedule_add']) ? $_REQUEST['schedule_add'] : 0,

				"edit" => isset($_REQUEST['schedule_edit']) ? $_REQUEST['schedule_edit'] : 0,

				"view" => isset($_REQUEST['schedule_view']) ? $_REQUEST['schedule_view'] : 1,

				"delete" => isset($_REQUEST['schedule_delete']) ? $_REQUEST['schedule_delete'] : 0

			],



			"virtual_classroom" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/virtual_classroom.png'),

				"menu_title" => 'virtual_classroom',

				"page_link" => 'virtual_classroom',

				"own_data" => isset($_REQUEST['virtual_classroom_own_data']) ? $_REQUEST['virtual_classroom_own_data'] : 1,

				"add" => isset($_REQUEST['virtual_classroom_add']) ? $_REQUEST['virtual_classroom_add'] : 0,

				"edit" => isset($_REQUEST['virtual_classroom_edit']) ? $_REQUEST['virtual_classroom_edit'] : 0,

				"view" => isset($_REQUEST['virtual_classroom_view']) ? $_REQUEST['virtual_classroom_view'] : 1,

				"delete" => isset($_REQUEST['virtual_classroom_delete']) ? $_REQUEST['virtual_classroom_delete'] : 0

			],



			"attendance" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/attandance.png'),

				"menu_title" => 'Attendance',

				"page_link" => 'attendance',

				"own_data" => isset($_REQUEST['attendance_own_data']) ? $_REQUEST['attendance_own_data'] : 1,

				"add" => isset($_REQUEST['attendance_add']) ? $_REQUEST['attendance_add'] : 0,

				"edit" => isset($_REQUEST['attendance_edit']) ? $_REQUEST['attendance_edit'] : 0,

				"view" => isset($_REQUEST['attendance_view']) ? $_REQUEST['attendance_view'] : 1,

				"delete" => isset($_REQUEST['attendance_delete']) ? $_REQUEST['attendance_delete'] : 0

			],



			"exam" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/exam.png'),

				"menu_title" => 'Exam',

				"page_link" => 'exam',

				"own_data" => isset($_REQUEST['exam_own_data']) ? $_REQUEST['exam_own_data'] : 1,

				"add" => isset($_REQUEST['exam_add']) ? $_REQUEST['exam_add'] : 0,

				"edit" => isset($_REQUEST['exam_edit']) ? $_REQUEST['exam_edit'] : 0,

				"view" => isset($_REQUEST['exam_view']) ? $_REQUEST['exam_view'] : 1,

				"delete" => isset($_REQUEST['exam_delete']) ? $_REQUEST['exam_delete'] : 0

			],



			"hostel" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

				"menu_title" => 'Hostel',

				"page_link" => 'hostel',

				"own_data" => isset($_REQUEST['hostel_own_data']) ? $_REQUEST['hostel_own_data'] : 1,

				"add" => isset($_REQUEST['hostel_add']) ? $_REQUEST['hostel_add'] : 0,

				"edit" => isset($_REQUEST['hostel_edit']) ? $_REQUEST['hostel_edit'] : 0,

				"view" => isset($_REQUEST['hostel_view']) ? $_REQUEST['hostel_view'] : 1,

				"delete" => isset($_REQUEST['hostel_delete']) ? $_REQUEST['hostel_delete'] : 0

			],

			"notification" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/attandance.png'),

				"menu_title" => 'Notification',

				"page_link" => 'notification',

				"own_data" => isset($_REQUEST['notification_own_data']) ? $_REQUEST['notification_own_data'] : 1,

				"add" => isset($_REQUEST['notification_add']) ? $_REQUEST['notification_add'] : 0,

				"edit" => isset($_REQUEST['notification_edit']) ? $_REQUEST['notification_edit'] : 0,

				"view" => isset($_REQUEST['notification_view']) ? $_REQUEST['notification_view'] : 1,

				"delete" => isset($_REQUEST['notification_delete']) ? $_REQUEST['notification_delete'] : 0

			],

			"homework" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/homework.png'),

				"menu_title" => 'Home Work',

				"page_link" => 'homework',

				"own_data" => isset($_REQUEST['homework_own_data']) ? $_REQUEST['homework_own_data'] : 1,

				"add" => isset($_REQUEST['homework_add']) ? $_REQUEST['homework_add'] : 0,

				"edit" => isset($_REQUEST['homework_edit']) ? $_REQUEST['homework_edit'] : 0,

				"view" => isset($_REQUEST['homework_view']) ? $_REQUEST['homework_view'] : 1,

				"delete" => isset($_REQUEST['homework_delete']) ? $_REQUEST['homework_delete'] : 0

			],

			"manage_marks" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/mark-manage.png'),

				"menu_title" => 'Mark Manage',

				"page_link" => 'manage_marks',

				"own_data" => isset($_REQUEST['manage_marks_own_data']) ? $_REQUEST['manage_marks_own_data'] : 0,

				"add" => isset($_REQUEST['manage_marks_add']) ? $_REQUEST['manage_marks_add'] : 0,

				"edit" => isset($_REQUEST['manage_marks_edit']) ? $_REQUEST['manage_marks_edit'] : 0,

				"view" => isset($_REQUEST['manage_marks_view']) ? $_REQUEST['manage_marks_view'] : 0,

				"delete" => isset($_REQUEST['manage_marks_delete']) ? $_REQUEST['manage_marks_delete'] : 0

			],



			"feepayment" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/fee.png'),

				"menu_title" => 'Fee Payment',

				"page_link" => 'feepayment',

				"own_data" => isset($_REQUEST['feepayment_own_data']) ? $_REQUEST['feepayment_own_data'] : 1,

				"add" => isset($_REQUEST['feepayment_add']) ? $_REQUEST['feepayment_add'] : 0,

				"edit" => isset($_REQUEST['feepayment_edit']) ? $_REQUEST['feepayment_edit'] : 0,

				"view" => isset($_REQUEST['feepayment_view']) ? $_REQUEST['feepayment_view'] : 1,

				"delete" => isset($_REQUEST['feepayment_delete']) ? $_REQUEST['feepayment_delete'] : 0

			],



			"document" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

				"menu_title" => 'Document',

				"page_link" => 'document',

				"own_data" => isset($_REQUEST['document_own_data']) ? $_REQUEST['document_own_data'] : 1,

				"add" => isset($_REQUEST['document_add']) ? $_REQUEST['document_add'] : 0,

				"edit" => isset($_REQUEST['document_edit']) ? $_REQUEST['document_edit'] : 0,

				"view" => isset($_REQUEST['document_view']) ? $_REQUEST['document_view'] : 1,

				"delete" => isset($_REQUEST['document_delete']) ? $_REQUEST['document_delete'] : 0

			],



			"leave" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				"menu_title" => 'Leave',

				"page_link" => 'leave',

				"own_data" => isset($_REQUEST['leave_own_data']) ? $_REQUEST['leave_own_data'] : 1,

				"add" => isset($_REQUEST['leave_add']) ? $_REQUEST['leave_add'] : 0,

				"edit" => isset($_REQUEST['leave_edit']) ? $_REQUEST['leave_edit'] : 0,

				"view" => isset($_REQUEST['leave_view']) ? $_REQUEST['leave_view'] : 1,

				"delete" => isset($_REQUEST['leave_delete']) ? $_REQUEST['leave_delete'] : 0

			],



			"payment" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/payment.png'),

				"menu_title" => 'Payment',

				"page_link" => 'payment',

				"own_data" => isset($_REQUEST['payment_own_data']) ? $_REQUEST['payment_own_data'] : 1,

				"add" => isset($_REQUEST['payment_add']) ? $_REQUEST['payment_add'] : 0,

				"edit" => isset($_REQUEST['payment_edit']) ? $_REQUEST['payment_edit'] : 0,

				"view" => isset($_REQUEST['payment_view']) ? $_REQUEST['payment_view'] : 1,

				"delete" => isset($_REQUEST['payment_delete']) ? $_REQUEST['payment_delete'] : 0

			],

			"transport" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/transport.png'),

				"menu_title" => 'Transport',

				"page_link" => 'transport',

				"own_data" => isset($_REQUEST['transport_own_data']) ? $_REQUEST['transport_own_data'] : 0,

				"add" => isset($_REQUEST['transport_add']) ? $_REQUEST['transport_add'] : 0,

				"edit" => isset($_REQUEST['transport_edit']) ? $_REQUEST['transport_edit'] : 0,

				"view" => isset($_REQUEST['transport_view']) ? $_REQUEST['transport_view'] : 1,

				"delete" => isset($_REQUEST['transport_delete']) ? $_REQUEST['transport_delete'] : 0

			],

			"notice" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notice.png'),

				"menu_title" => 'Notice Board',

				"page_link" => 'notice',

				"own_data" => isset($_REQUEST['notice_own_data']) ? $_REQUEST['notice_own_data'] : 1,

				"add" => isset($_REQUEST['notice_add']) ? $_REQUEST['notice_add'] : 0,

				"edit" => isset($_REQUEST['notice_edit']) ? $_REQUEST['notice_edit'] : 0,

				"view" => isset($_REQUEST['notice_view']) ? $_REQUEST['notice_view'] : 1,

				"delete" => isset($_REQUEST['notice_delete']) ? $_REQUEST['notice_delete'] : 0

			],

			"message" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/message.png'),

				"menu_title" => 'Message',

				"page_link" => 'message',

				"own_data" => isset($_REQUEST['message_own_data']) ? $_REQUEST['message_own_data'] : 1,

				"add" => isset($_REQUEST['message_add']) ? $_REQUEST['message_add'] : 1,

				"edit" => isset($_REQUEST['message_edit']) ? $_REQUEST['message_edit'] : 0,

				"view" => isset($_REQUEST['message_view']) ? $_REQUEST['message_view'] : 1,

				"delete" => isset($_REQUEST['message_delete']) ? $_REQUEST['message_delete'] : 1

			],

			"holiday" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/holiday.png'),

				"menu_title" => 'Holiday',

				"page_link" => 'holiday',

				"own_data" => isset($_REQUEST['holiday_own_data']) ? $_REQUEST['holiday_own_data'] : 0,

				"add" => isset($_REQUEST['holiday_add']) ? $_REQUEST['holiday_add'] : 0,

				"edit" => isset($_REQUEST['holiday_edit']) ? $_REQUEST['holiday_edit'] : 0,

				"view" => isset($_REQUEST['holiday_view']) ? $_REQUEST['holiday_view'] : 1,

				"delete" => isset($_REQUEST['holiday_delete']) ? $_REQUEST['holiday_delete'] : 0

			],



			"library" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/library.png'),

				"menu_title" => 'Library',

				"page_link" => 'library',

				"own_data" => isset($_REQUEST['library_own_data']) ? $_REQUEST['library_own_data'] : 1,

				"add" => isset($_REQUEST['library_add']) ? $_REQUEST['library_add'] : 0,

				"edit" => isset($_REQUEST['library_edit']) ? $_REQUEST['library_edit'] : 0,

				"view" => isset($_REQUEST['library_view']) ? $_REQUEST['library_view'] : 1,

				"delete" => isset($_REQUEST['library_delete']) ? $_REQUEST['library_delete'] : 0

			],



			"account" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/account.png'),

				"menu_title" => 'Account',

				"page_link" => 'account',

				"own_data" => isset($_REQUEST['account_own_data']) ? $_REQUEST['account_own_data'] : 1,

				"add" => isset($_REQUEST['account_add']) ? $_REQUEST['account_add'] : 0,

				"edit" => isset($_REQUEST['account_edit']) ? $_REQUEST['account_edit'] : 1,

				"view" => isset($_REQUEST['account_view']) ? $_REQUEST['account_view'] : 1,

				"delete" => isset($_REQUEST['account_delete']) ? $_REQUEST['account_delete'] : 0

			],



			"report" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

				"menu_title" => 'Report',

				"page_link" => 'report',

				"own_data" => isset($_REQUEST['report_own_data']) ? $_REQUEST['report_own_data'] : 0,

				"add" => isset($_REQUEST['report_add']) ? $_REQUEST['report_add'] : 0,

				"edit" => isset($_REQUEST['report_edit']) ? $_REQUEST['report_edit'] : 0,

				"view" => isset($_REQUEST['report_view']) ? $_REQUEST['report_view'] : 0,

				"delete" => isset($_REQUEST['report_delete']) ? $_REQUEST['report_delete'] : 0

			],

			"event" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

				"menu_title" => 'Event',

				"page_link" => 'event',

				"own_data" => isset($_REQUEST['event_own_data']) ? $_REQUEST['event_own_data'] : 0,

				"add" => isset($_REQUEST['event_add']) ? $_REQUEST['event_add'] : 0,

				"edit" => isset($_REQUEST['event_edit']) ? $_REQUEST['event_edit'] : 0,

				"view" => isset($_REQUEST['event_view']) ? $_REQUEST['event_view'] : 1,

				"delete" => isset($_REQUEST['event_delete']) ? $_REQUEST['event_delete'] : 0

			]

		];



		$role_access_right_support_staff = array();

		$role_access_right_support_staff['supportstaff'] =

			[

				//NEw Module Added //

				"admission" => [
					"menu_icone" => plugins_url('school-management/assets/images/icons/admission.png'),

					"menu_title" => 'Admission',

					"page_link" => 'admission',

					"own_data" => isset($_REQUEST['admission_own_data']) ? $_REQUEST['admission_own_data'] : 0,

					"add" => isset($_REQUEST['admission_add']) ? $_REQUEST['admission_add'] : 1,

					"edit" => isset($_REQUEST['admission_edit']) ? $_REQUEST['admission_edit'] : 1,

					"view" => isset($_REQUEST['admission_view']) ? $_REQUEST['admission_view'] : 1,

					"delete" => isset($_REQUEST['admission_delete']) ? $_REQUEST['admission_delete'] : 0

				],



				"student" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/student-icon.png'),

					"menu_title" => 'Student',

					"page_link" => 'student',

					"own_data" => isset($_REQUEST['student_own_data']) ? $_REQUEST['student_own_data'] : 0,

					"add" => isset($_REQUEST['student_add']) ? $_REQUEST['student_add'] : 1,

					"edit" => isset($_REQUEST['student_edit']) ? $_REQUEST['student_edit'] : 1,

					"view" => isset($_REQUEST['student_view']) ? $_REQUEST['student_view'] : 1,

					"delete" => isset($_REQUEST['student_delete']) ? $_REQUEST['student_delete'] : 1

				],

				"teacher" => [
					"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

					"menu_title" => 'Teacher',

					"page_link" => 'teacher',

					"own_data" => isset($_REQUEST['teacher_own_data']) ? $_REQUEST['teacher_own_data'] : 0,

					"add" => isset($_REQUEST['teacher_add']) ? $_REQUEST['teacher_add'] : 1,

					"edit" => isset($_REQUEST['teacher_edit']) ? $_REQUEST['teacher_edit'] : 1,

					"view" => isset($_REQUEST['teacher_view']) ? $_REQUEST['teacher_view'] : 1,

					"delete" => isset($_REQUEST['teacher_delete']) ? $_REQUEST['teacher_delete'] : 1

				],



				"supportstaff" => [
					"menu_icone" => plugins_url('school-management/assets/images/icons/support-staff.png'),

					"menu_title" => 'Supportstaff',

					"page_link" => 'supportstaff',

					"own_data" => isset($_REQUEST['supportstaff_own_data']) ? $_REQUEST['supportstaff_own_data'] : 1,

					"add" => isset($_REQUEST['supportstaff_add']) ? $_REQUEST['supportstaff_add'] : 0,

					"edit" => isset($_REQUEST['supportstaff_edit']) ? $_REQUEST['supportstaff_edit'] : 0,

					"view" => isset($_REQUEST['supportstaff_view']) ? $_REQUEST['supportstaff_view'] : 1,

					"delete" => isset($_REQUEST['supportstaff_delete']) ? $_REQUEST['supportstaff_delete'] : 0

				],



				"parent" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/parents.png'),

					"menu_title" => 'Parent',

					"page_link" => 'parent',

					"own_data" => isset($_REQUEST['parent_own_data']) ? $_REQUEST['parent_own_data'] : 0,

					"add" => isset($_REQUEST['parent_add']) ? $_REQUEST['parent_add'] : 1,

					"edit" => isset($_REQUEST['parent_edit']) ? $_REQUEST['parent_edit'] : 1,

					"view" => isset($_REQUEST['parent_view']) ? $_REQUEST['parent_view'] : 1,

					"delete" => isset($_REQUEST['parent_delete']) ? $_REQUEST['parent_delete'] : 1

				],



				"subject" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/subject.png'),

					"menu_title" => 'Subject',

					"page_link" => 'subject',

					"own_data" => isset($_REQUEST['subject_own_data']) ? $_REQUEST['subject_own_data'] : 0,

					"add" => isset($_REQUEST['subject_add']) ? $_REQUEST['subject_add'] : 1,

					"edit" => isset($_REQUEST['subject_edit']) ? $_REQUEST['subject_edit'] : 1,

					"view" => isset($_REQUEST['subject_view']) ? $_REQUEST['subject_view'] : 1,

					"delete" => isset($_REQUEST['subject_delete']) ? $_REQUEST['subject_delete'] : 1

				],



				"class" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/class.png'),

					"menu_title" => 'Class',

					"page_link" => 'class',

					"own_data" => isset($_REQUEST['class_own_data']) ? $_REQUEST['class_own_data'] : 0,

					"add" => isset($_REQUEST['class_add']) ? $_REQUEST['class_add'] : 1,

					"edit" => isset($_REQUEST['class_edit']) ? $_REQUEST['class_edit'] : 1,

					"view" => isset($_REQUEST['class_view']) ? $_REQUEST['class_view'] : 1,

					"delete" => isset($_REQUEST['class_delete']) ? $_REQUEST['class_delete'] : 1

				],



				"schedule" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/class-route.png'),

					"menu_title" => 'Class Routine',

					"page_link" => 'schedule',

					"own_data" => isset($_REQUEST['schedule_own_data']) ? $_REQUEST['schedule_own_data'] : 0,

					"add" => isset($_REQUEST['schedule_add']) ? $_REQUEST['schedule_add'] : 1,

					"edit" => isset($_REQUEST['schedule_edit']) ? $_REQUEST['schedule_edit'] : 1,

					"view" => isset($_REQUEST['schedule_view']) ? $_REQUEST['schedule_view'] : 1,

					"delete" => isset($_REQUEST['schedule_delete']) ? $_REQUEST['schedule_delete'] : 1

				],



				"virtual_classroom" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/virtual_classroom.png'),

					"menu_title" => 'virtual_classroom',

					"page_link" => 'virtual_classroom',

					"own_data" => isset($_REQUEST['virtual_classroom_own_data']) ? $_REQUEST['virtual_classroom_own_data'] : 0,

					"add" => isset($_REQUEST['virtual_classroom_add']) ? $_REQUEST['virtual_classroom_add'] : 1,

					"edit" => isset($_REQUEST['virtual_classroom_edit']) ? $_REQUEST['virtual_classroom_edit'] : 1,

					"view" => isset($_REQUEST['virtual_classroom_view']) ? $_REQUEST['virtual_classroom_view'] : 1,

					"delete" => isset($_REQUEST['virtual_classroom_delete']) ? $_REQUEST['virtual_classroom_delete'] : 1

				],



				"attendance" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/attandance.png'),

					"menu_title" => 'Attendance',

					"page_link" => 'attendance',

					"own_data" => isset($_REQUEST['attendance_own_data']) ? $_REQUEST['attendance_own_data'] : 0,

					"add" => isset($_REQUEST['attendance_add']) ? $_REQUEST['attendance_add'] : 1,

					"edit" => isset($_REQUEST['attendance_edit']) ? $_REQUEST['attendance_edit'] : 1,

					"view" => isset($_REQUEST['attendance_view']) ? $_REQUEST['attendance_view'] : 1,

					"delete" => isset($_REQUEST['attendance_delete']) ? $_REQUEST['attendance_delete'] : 1

				],



				"exam" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/exam.png'),

					"menu_title" => 'Exam',

					"page_link" => 'exam',

					"own_data" => isset($_REQUEST['exam_own_data']) ? $_REQUEST['exam_own_data'] : 0,

					"add" => isset($_REQUEST['exam_add']) ? $_REQUEST['exam_add'] : 1,

					"edit" => isset($_REQUEST['exam_edit']) ? $_REQUEST['exam_edit'] : 1,

					"view" => isset($_REQUEST['exam_view']) ? $_REQUEST['exam_view'] : 1,

					"delete" => isset($_REQUEST['exam_delete']) ? $_REQUEST['exam_delete'] : 1

				],

				"notification" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/attandance.png'),

					"menu_title" => 'Notification',

					"page_link" => 'notification',

					"own_data" => isset($_REQUEST['notification_own_data']) ? $_REQUEST['notification_own_data'] : 1,

					"add" => isset($_REQUEST['notification_add']) ? $_REQUEST['notification_add'] : 1,

					"edit" => isset($_REQUEST['notification_edit']) ? $_REQUEST['notification_edit'] : 1,

					"view" => isset($_REQUEST['notification_view']) ? $_REQUEST['notification_view'] : 1,

					"delete" => isset($_REQUEST['notification_delete']) ? $_REQUEST['notification_delete'] : 1

				],

				"exam_hall" => [
					"menu_icone" => plugins_url('school-management/assets/images/icons/exam_hall.png'),

					"menu_title" => 'Exam Hall',

					"page_link" => 'exam_hall',

					"own_data" => isset($_REQUEST['exam_hall_own_data']) ? $_REQUEST['exam_hall_own_data'] : 0,

					"add" => isset($_REQUEST['exam_hall_add']) ? $_REQUEST['exam_hall_add'] : 1,

					"edit" => isset($_REQUEST['exam_hall_edit']) ? $_REQUEST['exam_hall_edit'] : 1,

					"view" => isset($_REQUEST['exam_hall_view']) ? $_REQUEST['exam_hall_view'] : 1,

					"delete" => isset($_REQUEST['exam_hall_delete']) ? $_REQUEST['exam_hall_delete'] : 1

				],

				"grade" => [
					"menu_icone" => plugins_url('school-management/assets/images/icons/grade.png'),

					"menu_title" => 'Grade',

					"page_link" => 'grade',

					"own_data" => isset($_REQUEST['grade_own_data']) ? $_REQUEST['grade_own_data'] : 0,

					"add" => isset($_REQUEST['grade_add']) ? $_REQUEST['grade_add'] : 1,

					"edit" => isset($_REQUEST['grade_edit']) ? $_REQUEST['grade_edit'] : 1,

					"view" => isset($_REQUEST['grade_view']) ? $_REQUEST['grade_view'] : 1,

					"delete" => isset($_REQUEST['grade_delete']) ? $_REQUEST['grade_delete'] : 1

				],

				"manage_marks" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/mark-manage.png'),

					"menu_title" => 'Mark Manage',

					"page_link" => 'manage_marks',

					"own_data" => isset($_REQUEST['manage_marks_own_data']) ? $_REQUEST['manage_marks_own_data'] : 0,

					"add" => isset($_REQUEST['manage_marks_add']) ? $_REQUEST['manage_marks_add'] : 1,

					"edit" => isset($_REQUEST['manage_marks_edit']) ? $_REQUEST['manage_marks_edit'] : 1,

					"view" => isset($_REQUEST['manage_marks_view']) ? $_REQUEST['manage_marks_view'] : 1,

					"delete" => isset($_REQUEST['manage_marks_delete']) ? $_REQUEST['manage_marks_delete'] : 0

				],



				"homework" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/homework.png'),

					"menu_title" => 'Home Work',

					"page_link" => 'homework',

					"own_data" => isset($_REQUEST['homework_own_data']) ? $_REQUEST['homework_own_data'] : 0,

					"add" => isset($_REQUEST['homework_add']) ? $_REQUEST['homework_add'] : 1,

					"edit" => isset($_REQUEST['homework_edit']) ? $_REQUEST['homework_edit'] : 1,

					"view" => isset($_REQUEST['homework_view']) ? $_REQUEST['homework_view'] : 1,

					"delete" => isset($_REQUEST['homework_delete']) ? $_REQUEST['homework_delete'] : 1

				],



				"hostel" =>

				[
					'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

					"menu_title" => 'Hostel',

					"page_link" => 'hostel',

					"own_data" => isset($_REQUEST['hostel_own_data']) ? $_REQUEST['hostel_own_data'] : 0,

					"add" => isset($_REQUEST['hostel_add']) ? $_REQUEST['hostel_add'] : 1,

					"edit" => isset($_REQUEST['hostel_edit']) ? $_REQUEST['hostel_edit'] : 1,

					"view" => isset($_REQUEST['hostel_view']) ? $_REQUEST['hostel_view'] : 1,

					"delete" => isset($_REQUEST['hostel_delete']) ? $_REQUEST['hostel_delete'] : 1

				],



				"document" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

					"menu_title" => 'Document',

					"page_link" => 'document',

					"own_data" => isset($_REQUEST['document_own_data']) ? $_REQUEST['document_own_data'] : 1,

					"add" => isset($_REQUEST['document_add']) ? $_REQUEST['document_add'] : 1,

					"edit" => isset($_REQUEST['document_edit']) ? $_REQUEST['document_edit'] : 1,

					"view" => isset($_REQUEST['document_view']) ? $_REQUEST['document_view'] : 1,

					"delete" => isset($_REQUEST['document_delete']) ? $_REQUEST['document_delete'] : 1

				],



				"leave" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

					'app_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

					"menu_title" => 'Leave',

					"page_link" => 'leave',

					"own_data" => isset($_REQUEST['leave_own_data']) ? $_REQUEST['leave_own_data'] : 0,

					"add" => isset($_REQUEST['leave_add']) ? $_REQUEST['leave_add'] : 1,

					"edit" => isset($_REQUEST['leave_edit']) ? $_REQUEST['leave_edit'] : 1,

					"view" => isset($_REQUEST['leave_view']) ? $_REQUEST['leave_view'] : 1,

					"delete" => isset($_REQUEST['leave_delete']) ? $_REQUEST['leave_delete'] : 1

				],



				"transport" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/transport.png'),

					"menu_title" => 'Transport',

					"page_link" => 'transport',

					"own_data" => isset($_REQUEST['transport_own_data']) ? $_REQUEST['transport_own_data'] : 0,

					"add" => isset($_REQUEST['transport_add']) ? $_REQUEST['transport_add'] : 1,

					"edit" => isset($_REQUEST['transport_edit']) ? $_REQUEST['transport_edit'] : 1,

					"view" => isset($_REQUEST['transport_view']) ? $_REQUEST['transport_view'] : 1,

					"delete" => isset($_REQUEST['transport_delete']) ? $_REQUEST['transport_delete'] : 1

				],



				"notice" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/notice.png'),

					"menu_title" => 'Notice Board',

					"page_link" => 'notice',

					"own_data" => isset($_REQUEST['notice_own_data']) ? $_REQUEST['notice_own_data'] : 0,

					"add" => isset($_REQUEST['notice_add']) ? $_REQUEST['notice_add'] : 1,

					"edit" => isset($_REQUEST['notice_edit']) ? $_REQUEST['notice_edit'] : 1,

					"view" => isset($_REQUEST['notice_view']) ? $_REQUEST['notice_view'] : 1,

					"delete" => isset($_REQUEST['notice_delete']) ? $_REQUEST['notice_delete'] : 1

				],

				"message" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/message.png'),

					"menu_title" => 'Message',

					"page_link" => 'message',

					"own_data" => isset($_REQUEST['message_own_data']) ? $_REQUEST['message_own_data'] : 0,

					"add" => isset($_REQUEST['message_add']) ? $_REQUEST['message_add'] : 1,

					"edit" => isset($_REQUEST['message_edit']) ? $_REQUEST['message_edit'] : 0,

					"view" => isset($_REQUEST['message_view']) ? $_REQUEST['message_view'] : 1,

					"delete" => isset($_REQUEST['message_delete']) ? $_REQUEST['message_delete'] : 1

				],

				// Migration //

				"migration" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/message.png'),

					"menu_title" => 'Migration',

					"page_link" => 'migration',

					"own_data" => isset($_REQUEST['migration_own_data']) ? $_REQUEST['migration_own_data'] : 0,

					"add" => isset($_REQUEST['migration_add']) ? $_REQUEST['migration_add'] : 1,

					"edit" => isset($_REQUEST['migration_edit']) ? $_REQUEST['migration_edit'] : 0,

					"view" => isset($_REQUEST['migration_view']) ? $_REQUEST['migration_view'] : 1,

					"delete" => isset($_REQUEST['migration_delete']) ? $_REQUEST['migration_delete'] : 0

				],

				// tax //
				"tax"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/fee.png'),
					"menu_title"=>'Tax',
					"page_link"=>'tax',
					"own_data" => isset($_REQUEST['tax_own_data'])?$_REQUEST['tax_own_data']:0,
					"add" => isset($_REQUEST['tax_add'])?$_REQUEST['tax_add']:0,
					"edit"=>isset($_REQUEST['tax_edit'])?$_REQUEST['tax_edit']:0,
					"view"=>isset($_REQUEST['tax_view'])?$_REQUEST['tax_view']:1,
					"delete"=>isset($_REQUEST['tax_delete'])?$_REQUEST['tax_delete']:0
				],

				"feepayment" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/fee.png'),

					"menu_title" => 'Fee Payment',

					"page_link" => 'feepayment',

					"own_data" => isset($_REQUEST['feepayment_own_data']) ? $_REQUEST['feepayment_own_data'] : 0,

					"add" => isset($_REQUEST['feepayment_add']) ? $_REQUEST['feepayment_add'] : 1,

					"edit" => isset($_REQUEST['feepayment_edit']) ? $_REQUEST['feepayment_edit'] : 1,

					"view" => isset($_REQUEST['feepayment_view']) ? $_REQUEST['feepayment_view'] : 1,

					"delete" => isset($_REQUEST['feepayment_delete']) ? $_REQUEST['feepayment_delete'] : 1

				],



				"payment" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/payment.png'),

					"menu_title" => 'Payment',

					"page_link" => 'payment',

					"own_data" => isset($_REQUEST['payment_own_data']) ? $_REQUEST['payment_own_data'] : 0,

					"add" => isset($_REQUEST['payment_add']) ? $_REQUEST['payment_add'] : 1,

					"edit" => isset($_REQUEST['payment_edit']) ? $_REQUEST['payment_edit'] : 1,

					"view" => isset($_REQUEST['payment_view']) ? $_REQUEST['payment_view'] : 1,

					"delete" => isset($_REQUEST['payment_delete']) ? $_REQUEST['payment_delete'] : 1

				],



				"holiday" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/holiday.png'),

					"menu_title" => 'Holiday',

					"page_link" => 'holiday',

					"own_data" => isset($_REQUEST['holiday_own_data']) ? $_REQUEST['holiday_own_data'] : 0,

					"add" => isset($_REQUEST['holiday_add']) ? $_REQUEST['holiday_add'] : 1,

					"edit" => isset($_REQUEST['holiday_edit']) ? $_REQUEST['holiday_edit'] : 1,

					"view" => isset($_REQUEST['holiday_view']) ? $_REQUEST['holiday_view'] : 1,

					"delete" => isset($_REQUEST['holiday_delete']) ? $_REQUEST['holiday_delete'] : 1

				],



				"library" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/library.png'),

					"menu_title" => 'Library',

					"page_link" => 'library',

					"own_data" => isset($_REQUEST['library_own_data']) ? $_REQUEST['library_own_data'] : 0,

					"add" => isset($_REQUEST['library_add']) ? $_REQUEST['library_add'] : 1,

					"edit" => isset($_REQUEST['library_edit']) ? $_REQUEST['library_edit'] : 1,

					"view" => isset($_REQUEST['library_view']) ? $_REQUEST['library_view'] : 1,

					"delete" => isset($_REQUEST['library_delete']) ? $_REQUEST['library_delete'] : 1

				],

				"custom_field" => [
					"menu_icone" => plugins_url('school-management/assets/images/icons/custom.png'),

					"menu_title" => 'Custom Field',

					"page_link" => 'custom_field',

					"own_data" => isset($_REQUEST['custom_field_own_data']) ? $_REQUEST['custom_field_own_data'] : 0,

					"add" => isset($_REQUEST['custom_field_add']) ? $_REQUEST['custom_field_add'] : 1,

					"edit" => isset($_REQUEST['custom_field_edit']) ? $_REQUEST['custom_field_edit'] : 1,

					"view" => isset($_REQUEST['custom_field_view']) ? $_REQUEST['custom_field_view'] : 1,

					"delete" => isset($_REQUEST['custom_field_delete']) ? $_REQUEST['custom_field_delete'] : 1

				],

				"report" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

					"menu_title" => 'Report',

					"page_link" => 'report',

					"own_data" => isset($_REQUEST['report_own_data']) ? $_REQUEST['report_own_data'] : 0,

					"add" => isset($_REQUEST['report_add']) ? $_REQUEST['report_add'] : 0,

					"edit" => isset($_REQUEST['report_edit']) ? $_REQUEST['report_edit'] : 0,

					"view" => isset($_REQUEST['report_view']) ? $_REQUEST['report_view'] : 1,

					"delete" => isset($_REQUEST['report_delete']) ? $_REQUEST['report_delete'] : 0

				],



				// sms_setting //

				"sms_setting" => [
					"menu_icone" => plugins_url('school-management/assets/images/icons/sms_setting.png'),

					"menu_title" => 'SMS Setting',

					"page_link" => 'sms_setting',

					"own_data" => isset($_REQUEST['sms_setting_own_data']) ? $_REQUEST['sms_setting_own_data'] : 0,

					"add" => isset($_REQUEST['sms_setting_add']) ? $_REQUEST['sms_setting_add'] : 1,

					"edit" => isset($_REQUEST['sms_setting_edit']) ? $_REQUEST['sms_setting_edit'] : 1,

					"view" => isset($_REQUEST['sms_setting_view']) ? $_REQUEST['sms_setting_view'] : 1,

					"delete" => isset($_REQUEST['sms_setting_delete']) ? $_REQUEST['sms_setting_delete'] : 0

				],

				// email_template //

				"email_template" =>

				[
					"menu_icone" => plugins_url('school-management/assets/images/icons/email_template.png'),

					"menu_title" => 'Email Template',

					"page_link" => 'email_template',

					"own_data" => isset($_REQUEST['email_template_own_data']) ? $_REQUEST['email_template_own_data'] : 0,

					"add" => isset($_REQUEST['email_template_add']) ? $_REQUEST['email_template_add'] : 1,

					"edit" => isset($_REQUEST['email_template_edit']) ? $_REQUEST['email_template_edit'] : 1,

					"view" => isset($_REQUEST['email_template_view']) ? $_REQUEST['email_template_view'] : 1,

					"delete" => isset($_REQUEST['email_template_delete']) ? $_REQUEST['email_template_delete'] : 0

				],

				"general_settings" => [
					"menu_icone" => plugins_url('school-management/assets/images/icons/general_settings.png'),

					"menu_title" => 'General Settings',

					"page_link" => 'general_settings',

					"own_data" => isset($_REQUEST['general_settings_own_data']) ? $_REQUEST['general_settings_own_data'] : 0,

					"add" => isset($_REQUEST['general_settings_add']) ? $_REQUEST['general_settings_add'] : 0,

					"edit" => isset($_REQUEST['general_settings_edit']) ? $_REQUEST['general_settings_edit'] : 0,

					"view" => isset($_REQUEST['general_settings_view']) ? $_REQUEST['general_settings_view'] : 0,

					"delete" => isset($_REQUEST['general_settings_delete']) ? $_REQUEST['general_settings_delete'] : 0

				],

				"account" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/account.png'),

					"menu_title" => 'Account',

					"page_link" => 'account',

					"own_data" => isset($_REQUEST['account_own_data']) ? $_REQUEST['account_own_data'] : 1,

					"add" => isset($_REQUEST['account_add']) ? $_REQUEST['account_add'] : 0,

					"edit" => isset($_REQUEST['account_edit']) ? $_REQUEST['account_edit'] : 1,

					"view" => isset($_REQUEST['account_view']) ? $_REQUEST['account_view'] : 1,

					"delete" => isset($_REQUEST['account_delete']) ? $_REQUEST['account_delete'] : 0

				],

				"event" => [
					'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

					"menu_title" => 'Event',

					"page_link" => 'event',

					"own_data" => isset($_REQUEST['event_own_data']) ? $_REQUEST['event_own_data'] : 0,

					"add" => isset($_REQUEST['event_add']) ? $_REQUEST['event_add'] : 1,

					"edit" => isset($_REQUEST['event_edit']) ? $_REQUEST['event_edit'] : 1,

					"view" => isset($_REQUEST['event_view']) ? $_REQUEST['event_view'] : 1,

					"delete" => isset($_REQUEST['event_delete']) ? $_REQUEST['event_delete'] : 1

				]

			];



		$role_access_right_management = array();

		$role_access_right_management['management'] = [

			// NEw Menu Addded //

			"admission" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Admission',

				"page_link" => 'admission',

				"own_data" => isset($_REQUEST['admission_own_data']) ? $_REQUEST['admission_own_data'] : 0,

				"add" => isset($_REQUEST['admission_add']) ? $_REQUEST['admission_add'] : 1,

				"edit" => isset($_REQUEST['admission_edit']) ? $_REQUEST['admission_edit'] : 1,

				"view" => isset($_REQUEST['admission_view']) ? $_REQUEST['admission_view'] : 1,

				"delete" => isset($_REQUEST['admission_delete']) ? $_REQUEST['admission_delete'] : 1

			],



			"supportstaff" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Supportstaff',

				"page_link" => 'supportstaff',

				"own_data" => isset($_REQUEST['supportstaff_own_data']) ? $_REQUEST['supportstaff_own_data'] : 0,

				"add" => isset($_REQUEST['supportstaff_add']) ? $_REQUEST['supportstaff_add'] : 1,

				"edit" => isset($_REQUEST['supportstaff_edit']) ? $_REQUEST['supportstaff_edit'] : 1,

				"view" => isset($_REQUEST['supportstaff_view']) ? $_REQUEST['supportstaff_view'] : 1,

				"delete" => isset($_REQUEST['supportstaff_delete']) ? $_REQUEST['supportstaff_delete'] : 1

			],



			"exam_hall" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Exam Hall',

				"page_link" => 'exam_hall',

				"own_data" => isset($_REQUEST['exam_hall_own_data']) ? $_REQUEST['exam_hall_own_data'] : 0,

				"add" => isset($_REQUEST['exam_hall_add']) ? $_REQUEST['exam_hall_add'] : 1,

				"edit" => isset($_REQUEST['exam_hall_edit']) ? $_REQUEST['exam_hall_edit'] : 1,

				"view" => isset($_REQUEST['exam_hall_view']) ? $_REQUEST['exam_hall_view'] : 1,

				"delete" => isset($_REQUEST['exam_hall_delete']) ? $_REQUEST['exam_hall_delete'] : 1

			],



			"grade" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Grade',

				"page_link" => 'grade',

				"own_data" => isset($_REQUEST['grade_own_data']) ? $_REQUEST['grade_own_data'] : 0,

				"add" => isset($_REQUEST['grade_add']) ? $_REQUEST['grade_add'] : 1,

				"edit" => isset($_REQUEST['grade_edit']) ? $_REQUEST['grade_edit'] : 1,

				"view" => isset($_REQUEST['grade_view']) ? $_REQUEST['grade_view'] : 1,

				"delete" => isset($_REQUEST['grade_delete']) ? $_REQUEST['grade_delete'] : 1

			],



			"notification" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Notification',

				"page_link" => 'notification',

				"own_data" => isset($_REQUEST['notification_own_data']) ? $_REQUEST['notification_own_data'] : 0,

				"add" => isset($_REQUEST['notification_add']) ? $_REQUEST['notification_add'] : 1,

				"edit" => isset($_REQUEST['notification_edit']) ? $_REQUEST['notification_edit'] : 1,

				"view" => isset($_REQUEST['notification_view']) ? $_REQUEST['notification_view'] : 1,

				"delete" => isset($_REQUEST['notification_delete']) ? $_REQUEST['notification_delete'] : 1

			],



			"custom_field" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Custom Field',

				"page_link" => 'custom_field',

				"own_data" => isset($_REQUEST['custom_field_own_data']) ? $_REQUEST['custom_field_own_data'] : 0,

				"add" => isset($_REQUEST['custom_field_add']) ? $_REQUEST['custom_field_add'] : 1,

				"edit" => isset($_REQUEST['custom_field_edit']) ? $_REQUEST['custom_field_edit'] : 1,

				"view" => isset($_REQUEST['custom_field_view']) ? $_REQUEST['custom_field_view'] : 1,

				"delete" => isset($_REQUEST['custom_field_delete']) ? $_REQUEST['custom_field_delete'] : 1

			],



			"migration" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Migration',

				"page_link" => 'migration',

				"own_data" => isset($_REQUEST['migration_own_data']) ? $_REQUEST['migration_own_data'] : 0,

				"add" => isset($_REQUEST['migration_add']) ? $_REQUEST['migration_add'] : 0,

				"edit" => isset($_REQUEST['migration_edit']) ? $_REQUEST['migration_edit'] : 0,

				"view" => isset($_REQUEST['migration_view']) ? $_REQUEST['migration_view'] : 1,

				"delete" => isset($_REQUEST['migration_delete']) ? $_REQUEST['migration_delete'] : 0

			],



			"sms_setting" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'SMS Setting',

				"page_link" => 'sms_setting',

				"own_data" => isset($_REQUEST['sms_setting_own_data']) ? $_REQUEST['sms_setting_own_data'] : 0,

				"add" => isset($_REQUEST['sms_setting_add']) ? $_REQUEST['sms_setting_add'] : 0,

				"edit" => isset($_REQUEST['sms_setting_edit']) ? $_REQUEST['sms_setting_edit'] : 1,

				"view" => isset($_REQUEST['sms_setting_view']) ? $_REQUEST['sms_setting_view'] : 1,

				"delete" => isset($_REQUEST['sms_setting_delete']) ? $_REQUEST['sms_setting_delete'] : 0

			],



			"email_template" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Email Template',

				"page_link" => 'email_template',

				"own_data" => isset($_REQUEST['email_template_own_data']) ? $_REQUEST['email_template_own_data'] : 0,

				"add" => isset($_REQUEST['email_template_add']) ? $_REQUEST['email_template_add'] : 1,

				"edit" => isset($_REQUEST['email_template_edit']) ? $_REQUEST['email_template_edit'] : 1,

				"view" => isset($_REQUEST['email_template_view']) ? $_REQUEST['email_template_view'] : 1,

				"delete" => isset($_REQUEST['email_template_delete']) ? $_REQUEST['email_template_delete'] : 1

			],



			"access_right" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Access Right',

				"page_link" => 'access_right',

				"own_data" => isset($_REQUEST['access_right_own_data']) ? $_REQUEST['access_right_own_data'] : 0,

				"add" => isset($_REQUEST['access_right_add']) ? $_REQUEST['access_right_add'] : 0,

				"edit" => isset($_REQUEST['access_right_edit']) ? $_REQUEST['access_right_edit'] : 0,

				"view" => isset($_REQUEST['access_right_view']) ? $_REQUEST['access_right_view'] : 0,

				"delete" => isset($_REQUEST['access_right_delete']) ? $_REQUEST['access_right_delete'] : 0

			],





			//End new module //





			"teacher" => [
				"menu_icone" => plugins_url('school-management/assets/images/icons/teacher.png'),

				"menu_title" => 'Teacher',

				"page_link" => 'teacher',

				"own_data" => isset($_REQUEST['teacher_own_data']) ? $_REQUEST['teacher_own_data'] : 0,

				"add" => isset($_REQUEST['teacher_add']) ? $_REQUEST['teacher_add'] : 1,

				"edit" => isset($_REQUEST['teacher_edit']) ? $_REQUEST['teacher_edit'] : 1,

				"view" => isset($_REQUEST['teacher_view']) ? $_REQUEST['teacher_view'] : 1,

				"delete" => isset($_REQUEST['teacher_delete']) ? $_REQUEST['teacher_delete'] : 1

			],



			"student" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/student-icon.png'),

				"menu_title" => 'Student',

				"page_link" => 'student',

				"own_data" => isset($_REQUEST['student_own_data']) ? $_REQUEST['student_own_data'] : 0,

				"add" => isset($_REQUEST['student_add']) ? $_REQUEST['student_add'] : 1,

				"edit" => isset($_REQUEST['student_edit']) ? $_REQUEST['student_edit'] : 1,

				"view" => isset($_REQUEST['student_view']) ? $_REQUEST['student_view'] : 1,

				"delete" => isset($_REQUEST['student_delete']) ? $_REQUEST['student_delete'] : 1

			],



			"parent" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/parents.png'),

				"menu_title" => 'Parent',

				"page_link" => 'parent',

				"own_data" => isset($_REQUEST['parent_own_data']) ? $_REQUEST['parent_own_data'] : 0,

				"add" => isset($_REQUEST['parent_add']) ? $_REQUEST['parent_add'] : 1,

				"edit" => isset($_REQUEST['parent_edit']) ? $_REQUEST['parent_edit'] : 1,

				"view" => isset($_REQUEST['parent_view']) ? $_REQUEST['parent_view'] : 1,

				"delete" => isset($_REQUEST['parent_delete']) ? $_REQUEST['parent_delete'] : 1

			],



			"subject" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/subject.png'),

				"menu_title" => 'Subject',

				"page_link" => 'subject',

				"own_data" => isset($_REQUEST['subject_own_data']) ? $_REQUEST['subject_own_data'] : 0,

				"add" => isset($_REQUEST['subject_add']) ? $_REQUEST['subject_add'] : 1,

				"edit" => isset($_REQUEST['subject_edit']) ? $_REQUEST['subject_edit'] : 1,

				"view" => isset($_REQUEST['subject_view']) ? $_REQUEST['subject_view'] : 1,

				"delete" => isset($_REQUEST['subject_delete']) ? $_REQUEST['subject_delete'] : 1

			],



			"class" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/class.png'),

				"menu_title" => 'Class',

				"page_link" => 'class',

				"own_data" => isset($_REQUEST['class_own_data']) ? $_REQUEST['class_own_data'] : 0,

				"add" => isset($_REQUEST['class_add']) ? $_REQUEST['class_add'] : 1,

				"edit" => isset($_REQUEST['class_edit']) ? $_REQUEST['class_edit'] : 1,

				"view" => isset($_REQUEST['class_view']) ? $_REQUEST['class_view'] : 1,

				"delete" => isset($_REQUEST['class_delete']) ? $_REQUEST['class_delete'] : 1

			],



			"virtual_classroom" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/virtual_classroom.png'),

				"menu_title" => 'virtual_classroom',

				"page_link" => 'virtual_classroom',

				"own_data" => isset($_REQUEST['virtual_classroom_own_data']) ? $_REQUEST['virtual_classroom_own_data'] : 0,

				"add" => isset($_REQUEST['virtual_classroom_add']) ? $_REQUEST['virtual_classroom_add'] : 1,

				"edit" => isset($_REQUEST['virtual_classroom_edit']) ? $_REQUEST['virtual_classroom_edit'] : 1,

				"view" => isset($_REQUEST['virtual_classroom_view']) ? $_REQUEST['virtual_classroom_view'] : 1,

				"delete" => isset($_REQUEST['virtual_classroom_delete']) ? $_REQUEST['virtual_classroom_delete'] : 1

			],



			"schedule" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/class-route.png'),

				"menu_title" => 'Class Routine',

				"page_link" => 'schedule',

				"own_data" => isset($_REQUEST['schedule_own_data']) ? $_REQUEST['schedule_own_data'] : 0,

				"add" => isset($_REQUEST['schedule_add']) ? $_REQUEST['schedule_add'] : 1,

				"edit" => isset($_REQUEST['schedule_edit']) ? $_REQUEST['schedule_edit'] : 1,

				"view" => isset($_REQUEST['schedule_view']) ? $_REQUEST['schedule_view'] : 1,

				"delete" => isset($_REQUEST['schedule_delete']) ? $_REQUEST['schedule_delete'] : 1

			],

			"attendance" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/attandance.png'),

				"menu_title" => 'Attendance',

				"page_link" => 'attendance',

				"own_data" => isset($_REQUEST['attendance_own_data']) ? $_REQUEST['attendance_own_data'] : 0,

				"add" => isset($_REQUEST['attendance_add']) ? $_REQUEST['attendance_add'] : 1,

				"edit" => isset($_REQUEST['attendance_edit']) ? $_REQUEST['attendance_edit'] : 1,

				"view" => isset($_REQUEST['attendance_view']) ? $_REQUEST['attendance_view'] : 1,

				"delete" => isset($_REQUEST['attendance_delete']) ? $_REQUEST['attendance_delete'] : 1

			],



			"exam" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/exam.png'),

				"menu_title" => 'Exam',

				"page_link" => 'exam',

				"own_data" => isset($_REQUEST['exam_own_data']) ? $_REQUEST['exam_own_data'] : 0,

				"add" => isset($_REQUEST['exam_add']) ? $_REQUEST['exam_add'] : 1,

				"edit" => isset($_REQUEST['exam_edit']) ? $_REQUEST['exam_edit'] : 1,

				"view" => isset($_REQUEST['exam_view']) ? $_REQUEST['exam_view'] : 1,

				"delete" => isset($_REQUEST['exam_delete']) ? $_REQUEST['exam_delete'] : 1

			],





			"hostel" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

				"menu_title" => 'Hostel',

				"page_link" => 'hostel',

				"own_data" => isset($_REQUEST['hostel_own_data']) ? $_REQUEST['hostel_own_data'] : 0,

				"add" => isset($_REQUEST['hostel_add']) ? $_REQUEST['hostel_add'] : 1,

				"edit" => isset($_REQUEST['hostel_edit']) ? $_REQUEST['hostel_edit'] : 1,

				"view" => isset($_REQUEST['hostel_view']) ? $_REQUEST['hostel_view'] : 1,

				"delete" => isset($_REQUEST['hostel_delete']) ? $_REQUEST['hostel_delete'] : 1

			],

			"homework" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/homework.png'),

				"menu_title" => 'Home Work',

				"page_link" => 'homework',

				"own_data" => isset($_REQUEST['homework_own_data']) ? $_REQUEST['homework_own_data'] : 0,

				"add" => isset($_REQUEST['homework_add']) ? $_REQUEST['homework_add'] : 1,

				"edit" => isset($_REQUEST['homework_edit']) ? $_REQUEST['homework_edit'] : 1,

				"view" => isset($_REQUEST['homework_view']) ? $_REQUEST['homework_view'] : 1,

				"delete" => isset($_REQUEST['homework_delete']) ? $_REQUEST['homework_delete'] : 1

			],

			"manage_marks" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/mark-manage.png'),

				"menu_title" => 'Mark Manage',

				"page_link" => 'manage_marks',

				"own_data" => isset($_REQUEST['manage_marks_own_data']) ? $_REQUEST['manage_marks_own_data'] : 0,

				"add" => isset($_REQUEST['manage_marks_add']) ? $_REQUEST['manage_marks_add'] : 1,

				"edit" => isset($_REQUEST['manage_marks_edit']) ? $_REQUEST['manage_marks_edit'] : 1,

				"view" => isset($_REQUEST['manage_marks_view']) ? $_REQUEST['manage_marks_view'] : 1,

				"delete" => isset($_REQUEST['manage_marks_delete']) ? $_REQUEST['manage_marks_delete'] : 1

			],



			"feepayment" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/fee.png'),

				"menu_title" => 'Fee Payment',

				"page_link" => 'feepayment',

				"own_data" => isset($_REQUEST['feepayment_own_data']) ? $_REQUEST['feepayment_own_data'] : 0,

				"add" => isset($_REQUEST['feepayment_add']) ? $_REQUEST['feepayment_add'] : 1,

				"edit" => isset($_REQUEST['feepayment_edit']) ? $_REQUEST['feepayment_edit'] : 1,

				"view" => isset($_REQUEST['feepayment_view']) ? $_REQUEST['feepayment_view'] : 1,

				"delete" => isset($_REQUEST['feepayment_delete']) ? $_REQUEST['feepayment_delete'] : 1

			],



			"payment" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/payment.png'),

				"menu_title" => 'Payment',

				"page_link" => 'payment',

				"own_data" => isset($_REQUEST['payment_own_data']) ? $_REQUEST['payment_own_data'] : 0,

				"add" => isset($_REQUEST['payment_add']) ? $_REQUEST['payment_add'] : 1,

				"edit" => isset($_REQUEST['payment_edit']) ? $_REQUEST['payment_edit'] : 1,

				"view" => isset($_REQUEST['payment_view']) ? $_REQUEST['payment_view'] : 1,

				"delete" => isset($_REQUEST['payment_delete']) ? $_REQUEST['payment_delete'] : 1

			],

			"transport" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/transport.png'),

				"menu_title" => 'Transport',

				"page_link" => 'transport',

				"own_data" => isset($_REQUEST['transport_own_data']) ? $_REQUEST['transport_own_data'] : 0,

				"add" => isset($_REQUEST['transport_add']) ? $_REQUEST['transport_add'] : 1,

				"edit" => isset($_REQUEST['transport_edit']) ? $_REQUEST['transport_edit'] : 1,

				"view" => isset($_REQUEST['transport_view']) ? $_REQUEST['transport_view'] : 1,

				"delete" => isset($_REQUEST['transport_delete']) ? $_REQUEST['transport_delete'] : 1

			],

			"document" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/hostel.png'),

				"menu_title" => 'Document',

				"page_link" => 'document',

				"own_data" => isset($_REQUEST['document_own_data']) ? $_REQUEST['document_own_data'] : 0,

				"add" => isset($_REQUEST['document_add']) ? $_REQUEST['document_add'] : 1,

				"edit" => isset($_REQUEST['document_edit']) ? $_REQUEST['document_edit'] : 1,

				"view" => isset($_REQUEST['document_view']) ? $_REQUEST['document_view'] : 1,

				"delete" => isset($_REQUEST['	']) ? $_REQUEST['document_delete'] : 1

			],

			"leave" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				'app_icone' => plugins_url('school-management/assets/images/icons/notification_new.png'),

				"menu_title" => 'Leave',

				"page_link" => 'leave',

				"own_data" => isset($_REQUEST['leave_own_data']) ? $_REQUEST['leave_own_data'] : 1,

				"add" => isset($_REQUEST['leave_add']) ? $_REQUEST['leave_add'] : 1,

				"edit" => isset($_REQUEST['leave_edit']) ? $_REQUEST['leave_edit'] : 1,

				"view" => isset($_REQUEST['leave_view']) ? $_REQUEST['leave_view'] : 1,

				"delete" => isset($_REQUEST['leave_delete']) ? $_REQUEST['leave_delete'] : 1

			],

			"notice" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/notice.png'),

				"menu_title" => 'Notice Board',

				"page_link" => 'notice',

				"own_data" => isset($_REQUEST['notice_own_data']) ? $_REQUEST['notice_own_data'] : 0,

				"add" => isset($_REQUEST['notice_add']) ? $_REQUEST['notice_add'] : 1,

				"edit" => isset($_REQUEST['notice_edit']) ? $_REQUEST['notice_edit'] : 1,

				"view" => isset($_REQUEST['notice_view']) ? $_REQUEST['notice_view'] : 1,

				"delete" => isset($_REQUEST['notice_delete']) ? $_REQUEST['notice_delete'] : 1

			],

			"message" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/message.png'),

				"menu_title" => 'Message',

				"page_link" => 'message',

				"own_data" => isset($_REQUEST['message_own_data']) ? $_REQUEST['message_own_data'] : 0,

				"add" => isset($_REQUEST['message_add']) ? $_REQUEST['message_add'] : 1,

				"edit" => isset($_REQUEST['message_edit']) ? $_REQUEST['message_edit'] : 1,

				"view" => isset($_REQUEST['message_view']) ? $_REQUEST['message_view'] : 1,

				"delete" => isset($_REQUEST['message_delete']) ? $_REQUEST['message_delete'] : 1

			],

			"holiday" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/holiday.png'),

				"menu_title" => 'Holiday',

				"page_link" => 'holiday',

				"own_data" => isset($_REQUEST['holiday_own_data']) ? $_REQUEST['holiday_own_data'] : 0,

				"add" => isset($_REQUEST['holiday_add']) ? $_REQUEST['holiday_add'] : 1,

				"edit" => isset($_REQUEST['holiday_edit']) ? $_REQUEST['holiday_edit'] : 1,

				"view" => isset($_REQUEST['holiday_view']) ? $_REQUEST['holiday_view'] : 1,

				"delete" => isset($_REQUEST['holiday_delete']) ? $_REQUEST['holiday_delete'] : 1

			],



			"library" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/library.png'),

				"menu_title" => 'Library',

				"page_link" => 'library',

				"own_data" => isset($_REQUEST['library_own_data']) ? $_REQUEST['library_own_data'] : 0,

				"add" => isset($_REQUEST['library_add']) ? $_REQUEST['library_add'] : 1,

				"edit" => isset($_REQUEST['library_edit']) ? $_REQUEST['library_edit'] : 1,

				"view" => isset($_REQUEST['library_view']) ? $_REQUEST['library_view'] : 1,

				"delete" => isset($_REQUEST['library_delete']) ? $_REQUEST['library_delete'] : 1

			],



			"account" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/account.png'),

				"menu_title" => 'Account',

				"page_link" => 'account',

				"own_data" => isset($_REQUEST['account_own_data']) ? $_REQUEST['account_own_data'] : 0,

				"add" => isset($_REQUEST['account_add']) ? $_REQUEST['account_add'] : 0,

				"edit" => isset($_REQUEST['account_edit']) ? $_REQUEST['account_edit'] : 1,

				"view" => isset($_REQUEST['account_view']) ? $_REQUEST['account_view'] : 1,

				"delete" => isset($_REQUEST['account_delete']) ? $_REQUEST['account_delete'] : 0

			],



			"report" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

				"menu_title" => 'Report',

				"page_link" => 'report',

				"own_data" => isset($_REQUEST['report_own_data']) ? $_REQUEST['report_own_data'] : 0,

				"add" => isset($_REQUEST['report_add']) ? $_REQUEST['report_add'] : 0,

				"edit" => isset($_REQUEST['report_edit']) ? $_REQUEST['report_edit'] : 0,

				"view" => isset($_REQUEST['report_view']) ? $_REQUEST['report_view'] : 1,

				"delete" => isset($_REQUEST['report_delete']) ? $_REQUEST['report_delete'] : 0

			],

			"event" => [
				'menu_icone' => plugins_url('school-management/assets/images/icons/report.png'),

				"menu_title" => 'Event',

				"page_link" => 'event',

				"own_data" => isset($_REQUEST['event_own_data']) ? $_REQUEST['event_own_data'] : 0,

				"add" => isset($_REQUEST['event_add']) ? $_REQUEST['event_add'] : 1,

				"edit" => isset($_REQUEST['event_edit']) ? $_REQUEST['event_edit'] : 1,

				"view" => isset($_REQUEST['event_view']) ? $_REQUEST['event_view'] : 1,

				"delete" => isset($_REQUEST['event_delete']) ? $_REQUEST['event_delete'] : 1

			]



		];



		$dashboard_card_access_for_student = array();
		$dashboard_card_access_for_student = [
			"smgt_payment_status_chart" => isset($_REQUEST['payment_status_chart_enable_student']) ? esc_attr($_REQUEST['payment_status_chart_enable_student']) : "yes",
			"smgt_user_chart" => isset($_REQUEST['user_chart_enable_student']) ? esc_attr($_REQUEST['user_chart_enable_student']) : "yes",
			"smgt_invoice_chart" => isset($_REQUEST['invoice_enable']) ? esc_attr($_REQUEST['invoice_enable']) : "yes",
		];
		$dashboard_card_access_for_support_staff = array();
		$dashboard_card_access_for_support_staff = [

			"smgt_student_status_chart" => isset($_REQUEST['student_status_staff']) ? esc_attr($_REQUEST['student_status_staff']) : "yes",
			"smgt_attendance_chart" => isset($_REQUEST['attendance_staff']) ? esc_attr($_REQUEST['attendance_staff']) : "yes",
			"smgt_payment_status_chart" => isset($_REQUEST['payment_status_staff']) ? esc_attr($_REQUEST['payment_status_staff']) : "yes",
			"smgt_payment_report" => isset($_REQUEST['payment_report_staff']) ? esc_attr($_REQUEST['payment_report_staff']) : "yes",
			"smgt_invoice_chart" => isset($_REQUEST['invoice_enable_staff']) ? esc_attr($_REQUEST['invoice_enable_staff']) : "yes",
			"smgt_user_chart" => isset($_REQUEST['users_chart_staff']) ? esc_attr($_REQUEST['users_chart_staff']) : "yes",

		];



		$dashboard_card_access_for_teacher = array();
		$dashboard_card_access_for_teacher = [
			"smgt_student_status_chart" => isset($_REQUEST['student_status_enable_teacher']) ? esc_attr($_REQUEST['student_status_enable_teacher']) : "yes",
			"smgt_attendance_chart" => isset($_REQUEST['attendance_chart_enable_teacher']) ? esc_attr($_REQUEST['attendance_chart_enable_teacher']) : "yes",
			"smgt_user_chart" => isset($_REQUEST['user_chart_enable_teacher']) ? esc_attr($_REQUEST['user_chart_enable_teacher']) : "yes",
		];
		$dashboard_card_access_for_parent = array();
		$dashboard_card_access_for_parent = [
			"smgt_user_chart" => isset($_REQUEST['user_chart_parent']) ? esc_attr($_REQUEST['user_chart_parent']) : "yes",
			"smgt_invoice_chart" => isset($_REQUEST['invoice_enable_parent']) ? esc_attr($_REQUEST['invoice_enable_parent']) : "yes",
			"smgt_payment_status_chart" => isset($_REQUEST['payment_status_parent']) ? esc_attr($_REQUEST['payment_status_parent']) : "yes",

		];

		/* Setup Wizard Options */

		$smgt_setup_wizard_step = array(

			"step1_general_setting" => "no",

			"step2_class" => "no",

			"step3_teacher" => "no",

			"step4_subject" => "no",

			"step5_class_time_table" => "no",

			"step6_student" => "no"
		);

		/* Setup Wizard Step */

		$wizard_option = get_option('smgt_setup_wizard_step');

		if (empty($wizard_option)) {

			$wizard_option = add_option('smgt_setup_wizard_step', $smgt_setup_wizard_step);
		}

		/* Setup Wizard Status */

		$smgt_setup_wizard_status = 'no';



		$options = array(

			"smgt_school_name" => esc_attr__('School Title Here', 'school-mgt'),

			"smgt_staring_year" => "",

			"smgt_school_address" => "",

			"smgt_contact_number" => "",

			"smgt_contry" => "United States",

			"smgt_email" => "",

			"smgt_datepicker_format" => 'yy/mm/dd',

			"smgt_school_logo" => plugins_url('school-management/assets/images/finel-logo6.png'),

			"smgt_system_logo" => plugins_url('school-management/assets/images/school_new_logo.png'),

			"smgt_school_background_image" => plugins_url('school-management/assets/images/school_life.jpg'),

			"smgt_student_thumb" => plugins_url('school-management/assets/images/thumb_icon/Student.png'),

			"smgt_no_data_img" => plugins_url('school-management/assets/images/thumb_icon/Plus-icon.png'),

			"smgt_parent_thumb" => plugins_url('school-management/assets/images/thumb_icon/Parents.png'),

			"smgt_teacher_thumb" => plugins_url('school-management/assets/images/thumb_icon/Teacher.png'),

			"smgt_supportstaff_thumb" => plugins_url('school-management/assets/images/thumb_icon/support-staff.png'),

			"smgt_driver_thumb" => plugins_url('school-management/assets/images/thumb_icon/Transport.png'),

			"smgt_principal_signature" => plugins_url('school-management/assets/images/Signature Stamp.png'),



			"smgt_student_thumb_new" => plugins_url('school-management/assets/images/thumb_icon/Student.png'),

			"smgt_parent_thumb_new" => plugins_url('school-management/assets/images/thumb_icon/Parents.png'),

			"smgt_teacher_thumb_new" => plugins_url('school-management/assets/images/thumb_icon/Teacher.png'),

			"smgt_supportstaff_thumb_new" => plugins_url('school-management/assets/images/thumb_icon/support-staff.png'),

			"smgt_driver_thumb_new" => plugins_url('school-management/assets/images/thumb_icon/Transport.png'),



			"smgt_footer_description" => "Copyright ©2022 Mojoomla. All rights reserved.",



			"smgt_access_right_student" => $role_access_right_student,

			"smgt_access_right_teacher" => $role_access_right_teacher,

			"smgt_access_right_parent" => $role_access_right_parent,

			"smgt_access_right_supportstaff" => $role_access_right_support_staff,

			"smgt_access_right_management" => $role_access_right_management,



			"smgt_dashboard_card_for_student" => $dashboard_card_access_for_student,

			"smgt_dashboard_card_for_teacher" => $dashboard_card_access_for_teacher,

			"smgt_dashboard_card_for_support_staff" => $dashboard_card_access_for_support_staff,

			"smgt_dashboard_card_for_parent" => $dashboard_card_access_for_parent,

			"smgt_setup_wizard_status" => $smgt_setup_wizard_status,

			"smgt_sms_service" => "",

			//PAY MASTER OPTION//

			"smgt_paymaster_pack" => "no",

			"smgt_mail_notification" => 1,

			"smgt_notification_fcm_key" => "",

			"smgt_sms_service_enable" => 0,

			"student_approval" => 1,

			"smgt_sms_template" => "Hello [SMS_USER_NAME] ",

			"smgt_clickatell_sms_service" => array(),

			"smgt_twillo_sms_service" => array(),

			"parent_send_message" => 1,

			"smgt_enable_total_student" => 1,

			"smgt_enable_total_teacher" => 1,

			"smgt_enable_total_parent" => 1,

			"smgt_enable_homework_mail" => 0,

			"smgt_enable_total_attendance" => 1,

			"smgt_enable_sandbox" => 'yes',

			"smgt_virtual_classroom_client_id" => '',

			"smgt_virtual_classroom_client_secret_id" => '',

			"smgt_virtual_classroom_access_token" => '',

			"smgt_enable_virtual_classroom" => 'no',

			"smgt_paypal_email" => '',

			"razorpay__key" => '',

			"razorpay_secret_mid" => '',

			"smgt_currency_code" => 'USD',

			"smgt_teacher_manage_allsubjects_marks" => 'yes',

			"smgt_enable_video_popup_show" => "yes",

			"registration_title" => 'Student Registration',

			"student_activation_title" => 'Student Approved',

			"fee_payment_title" => 'Fees Alert',

			"fee_payment_title_for_parent" => 'Fees Alert',

			"smgt_teacher_show_access" => "own_class",

			"admissiion_title" => 'Request For Admission',

			"exam_receipt_subject" => 'Exam Receipt Generate',

			"bed_subject" => 'Hostel Bed Assigned',

			"add_approve_admisson_mail_subject" => 'Admission Approved',

			"student_assign_teacher_mail_subject" => "New Student has been assigned to you.",

			"smgt_enable_virtual_classroom_reminder" => "yes",

			"smgt_enable_sms_virtual_classroom_reminder" => "yes",

			"smgt_virtual_classroom_reminder_before_time" => "30",

			"smgt_heder_enable" => "yes",



			"smgt_admission_fees" => "no",

			"smgt_enable_recurring_invoices" => "no",

			"smgt_admission_amount" => "",

			"smgt_system_color_code" => "#5840bb",


			"smgt_registration_fees" => "no",

			"smgt_registration_amount" => "",


			"smgt_invoice_notice" => "If You Paid Your Payment than Invoice are automatically Generated.",


			"smgt_attendence_migration_status" => "no",



			'add_leave_emails' => '',

			'leave_approveemails' => '',



			'add_leave_subject' => 'Request For Leave',

			'add_leave_subject_of_admin' => 'Request For Leave',

			'add_leave_subject_for_student' => 'Request For Leave',

			'add_leave_subject_for_parent' => 'Request For Leave',

			'leave_approve_subject' => 'Your leave has been Approved Successfully',

			'leave_reject_subject' => 'Your leave has been Rejected',

			'add_exam_mail_title' => 'New exam has been assigned to you.',

			"student_assign_teacher_mail_content" => "Dear {{teacher_name}},



         New Student {{student_name}} has been assigned to you.



Regards From {{school_name}}.",



			"generate_invoice_mail_subject" => "Generate Invoice",

			"generate_invoice_mail_content" => "Dear {{student_name}},



        Your have a new invoice.  You can check the invoice attached here.



Regards From {{school_name}}.",

			//------------ ADD USER ---------------//

			"add_user_mail_subject" => 'Your have been assigned role of {{role}} in {{school_name}}.',

			"add_user_mail_content" => "Dear {{user_name}},



         You are Added by admin in {{school_name}} . Your have been assigned role of {{role}} in {{school_name}}.  You can sign in using this link. {{login_link}}



UserName : {{username}}

Password : {{Password}}



Regards From {{school_name}}.",



			//------- Registration Successfully ----------//

			"registration_mailtemplate" => "Hello {{student_name}} ,



Your registration has been successful with {{school_name}}.



Class Name : {{class_name}}

Email ID : {{email_id}}

Password : {{password}}



Regards From {{school_name}}.",



			//------- Request for  Admission ----------//

			"admission_mailtemplate_content" => "Hello {{student_name}} ,



Your admission request has been successful with {{school_name}}. You will be able to access your account after school admin approves it and we will send username and password shortly.



Student Name : {{user_name}}

Email : {{email}}



Regards From {{school_name}}.",



			//------- Exam Receipt GENERATE----------//

			"exam_receipt_content" => "Hello {{student_name}} ,



		your exam hall receipt has been generated.



Regards From {{school_name}}.",





			//------- Hostel Bed Assigned ----------//

			"bed_content" => "Hello {{student_name}} ,



		You have been assigned new hostel bed in {{school_name}}.



Hostel Name : {{hostel_name}}

Room Number : {{room_id}}

Bed Number : {{bed_id}}

Bed Charge : {{bed_charge}}



Regards From {{school_name}}.",



			//------- Approved Admission ----------//

			"add_approve_admission_mail_content" => "Hello {{user_name}} ,



		Your admission has been successful approved with {{school_name}}.



		You can signin using this link. {{login_link}}

		Class Name : {{class_name}}

		Roll No : {{roll_no}}

		Email : {{email}}

		Password : {{Password}}



		Regards From {{school_name}}.",



			//----------- Student Activation --------------//



			"student_activation_mailcontent" => "Hello {{student_name}},

                 Your account with {{school_name}} is approved. You can access student account using your login details. Your other details are given bellow.



User Name : {{user_name}}

Class Name : {{class_name}}

Email : {{email}}



Regards From {{school_name}}.",



			//--------- student Appoved Leave   --------------//



			'addleave_email_template' =>



			'Hello,
  Date : {{date}}

  Leave Type : {{leave_type}}

  Leave Duration : {{leave_duration}}

  Reason : {{reason}}

Thank you

{{employee_name}}



',







			'leave_approve_email_template' =>

			'Hello,
Leave of {{user_name}} is approved successfully.

Date     :  {{date}}

Comment  : {{comment}}

Regards From {{system_name}}.

Thank you

{{system_name}}

',

			// -------------------- LEAVE REQUEST MAIL TEMPLATE FOR STUDENT ---------------------//

			'addleave_email_template_student' =>

			'Hello {{student_name}},

Your Leave are Added Successfully.

Date : {{date}},

Leave Type : {{leave_type}},

Leave Duration : {{leave_duration}},

Reason : {{reason}},

Thank you
{{school_name}}.
',

			// -------------------- LEAVE REQUEST MAIL TEMPLATE FOR PARENT ---------------------//

			'addleave_email_template_parent' =>

			'Hello {{parent_name}},

Your child {{student_name}}, has been added leave of {{date}}.

Leave Type : {{leave_type}},

Leave Duration : {{leave_duration}},

Reason : {{reason}},

Thank you
{{school_name}}.
',


			// -------------------- LEAVE REQUEST MAIL TEMPLATE FOR ADMIN ---------------------//

			'addleave_email_template_of_admin' =>

			'Dear Admin,

{{student_name}} are Add Leave of {{date}}.

 Leave Type : {{leave_type}},

 Leave Duration : {{leave_duration}},

 Reason : {{reason}},

 Thank you
 {{school_name}}.
',






			//--------------- LEAVE REJECT MAIL TEMPLATE ----------------//

			'leave_reject_email_template' =>

			'Hello {{student_name}} ,

Leave of {{student_name}} is Rejected.

Date     :  {{date}}

Comment  : {{comment}}

Regards From {{school_name}}

Thank you',

			//--------------- ADD EXAM MAIL TEMPLATE ----------------//

			'add_exam_mailcontent' =>

			'Dear {{user_name}},

A new exam {{exam_name}} has been assigned to you.
Exam Details:
Exam Name : {{exam_name}}
Exam Start To End Date : {{exam_start_end_date}}
Exam Comment : {{exam_comment}}

Regards From
{{school_name}}',


			//--------------- FEES PAYMENT FOR STUDENT --------------//

			"fee_payment_mailcontent" => "Dear {{student_name}},

        You have a new invoice.  You can check the invoice attached here.
	Date : {{date}}

	Amount : {{amount}}

  Regards From {{school_name}}

Thank you",

			//--------------- FEES PAYMENT FOR PARENT --------------//

			"fee_payment_mailcontent_for_parent" => "Dear {{parent_name}},

	You have a new invoice for your child {{child_name}}. You can check the invoice attached here.
	Date : {{date}}

	Amount : {{amount}}

	Regards From {{school_name}}

Thank you",
			//------------------ MESSAGE RECEIVED ---------------//

			'message_received_mailcontent' => 'Dear {{receiver_name}},



        You have received new message {{message_content}}.



Regards From {{school_name}}.',

			'message_received_mailsubject' => 'You have received new message from {{from_mail}} at {{school_name}}',

			//------------------ CHILD ABSENT -------------------//

			'absent_mail_notification_subject' => 'Your Child {{child_name}} is absent today',

			'absent_mail_notification_content' => "Your Child {{child_name}} is absent today.



Regards From {{school_name}}.",

			//----------------- ASSIGNED TEACHER ------------------//

			'student_assign_to_teacher_subject' => 'You have been Assigned {{teacher_name}} at {{school_name}}',

			'student_assign_to_teacher_content' => 'Dear {{student_name}},



         You are assigned to  {{teacher_name}}. {{teacher_name}} belongs to {{class_name}}.



Regards From {{school_name}}.',



			'payment_recived_mailsubject' => 'Payment Received against Invoice',

			'payment_recived_mailcontent' => 'Dear {{student_name}},



        Your have successfully paid your invoice {{invoice_no}}. You can check the invoice attached here.



Regards From {{school_name}}.',

			'notice_mailsubject'	=>	'New Notice For You',

			'notice_mailcontent'	=>	'New Notice For You.



Notice Title : {{notice_title}}



Notice Date  : {{notice_date}}



Notice Comment :  {{notice_comment}}



Regards From {{school_name}}

',

			/* ----------------- Event Mail Template ------------------- */

			'event_mailsubject'	=>	'Exciting New Event at {{school_name}}.',

			'event_mailcontent'	=>	'Dear {{user_name}},

             We are delighted to announce an exciting new event at {{school_name}} that promises to be a memorable experience for all attendees!

Event Details:
Event Name: {{event_title}}
Date: {{event_date}}
Time: {{event_time}}
Description: {{description}}

Regards From {{school_name}}.',

			/*   -------Parent mail notification template------- */

			'parent_homework_mail_subject' => 'New Homework Assigned',

			'parent_homework_mail_content'	=>	'Dear {{parent_name}},

New homework has been assign to your child.

Student name : {{student_name}}
Homework Title : {{title}}
Subject : {{subject}}
Homework Date : {{homework_date}}
Submission Date : {{submition_date}}

Regards From {{school_name}}

',

			/*   -------student mail notification template------- */



			'homework_title' => 'Dear {{student_name}},

New homework has been assign to you.

Homework Title : {{title}}
Subject : {{subject}}
Homework Date : {{homework_date}}
Submission Date : {{submition_date}}

Regards From {{school_name}}

',

			//-------------- HOLIDAY MAILTEMPLATE -----------//

			'holiday_mailsubject' => 'Holiday Announcement',

			'holiday_mailcontent' => 'Holiday Announcement



Holiday Title : {{holiday_title}}



Holiday Date : {{holiday_date}}



Regards From {{school_name}}

',



			//----------------------- VIRTUAL CLASSROOM TEACHER INVITE MAIL ------//

			'virtual_class_invite_teacher_mail_subject' => 'Inviting you to a scheduled Zoom meeting',

			'virtual_class_invite_teacher_mail_content' => 'Inviting you to a scheduled Zoom meeting



	Class Name : {{class_name}}



	Time : {{time}}



	Virtual Class ID : {{virtual_class_id}}



	Password : {{password}}



	Join Zoom Virtual Class : {{join_zoom_virtual_class}}



	Start Zoom Virtual Class : {{start_zoom_virtual_class}}



	Regards From {{school_name}}

',

			//----------------------- VIRTUAL CLASSROOM TEACHER REMINDER MAIL ------//

			'virtual_class_teacher_reminder_mail_subject' => 'Your virtual class just start',

			'virtual_class_teacher_reminder_mail_content' => 'Dear {{teacher_name}}



	Your virtual class just start



	Class Name : {{class_name}}



	subject Name : {{subject_name}}



	Date : {{date}}



	Time : {{time}}



	Virtual Class ID : {{virtual_class_id}}



	Password : {{password}}



	{{start_zoom_virtual_class}}



	Regards From {{school_name}}

',

			//----------------------- VIRTUAL CLASSROOM STUDENT REMINDER MAIL ------//

			'virtual_class_student_reminder_mail_subject' => 'Your virtual class just start',

			'virtual_class_student_reminder_mail_content' => 'Dear {{student_name}}



	Your virtual class just start



	Class Name : {{class_name}}



	Subject Name : {{subject_name}}



	Teacher Name : {{teacher_name}}



	Date : {{date}}



	Time : {{time}}



	Virtual Class ID : {{virtual_class_id}}



	Password : {{password}}



	{{join_zoom_virtual_class}}



	Regards From {{school_name}}

',

			//----------------- Fee Payment Reminder Mail ---------------------//

			'fee_payment_reminder_title' => 'Fees Payment Reminder',

			'fee_payment_reminder_mailcontent' => '

Dear {{parent_name}},



We just wanted to send you a reminder that the tuition fee has not been paid against your son/daughter {{student_name}} of class {{class_name}} .the total amount is {{total_amount}} and the due amount is {{due_amount}}.



Regards From

{{school_name}}',

			//----------------- Fee Payment Reminder Mail For Student---------------------//

			'fee_payment_reminder_title_for_student' => 'Fees Payment Reminder',
			'fee_payment_reminder_mailcontent_for_student' => '
Dear {{student_name}},

We just wanted to send you a reminder that the tuition fee has not been paid against you. the total amount is {{total_amount}} and the due amount is {{due_amount}}.

Regards From
{{school_name}}',

			//----------------- Assign Subject Mail ---------------------//

			'assign_subject_title' => 'New subject has been assigned to you.',

			'assign_subject_mailcontent' => '

Dear {{teacher_name}},



New subject {{subject_name}} has been assigned to you.



Regards From

{{school_name}}',



			//----------------- Issue Book  Mail ---------------------//



			'issue_book_title' => 'New book has been issue to you.',

			'issue_book_mailcontent' => '

Dear {{student_name}},

New book {{book_name}} has been issue to you.

Issue Date : {{issue_date}}

Return Date : {{return_date}}

Regards From
{{school_name}}'



		);





		return $options;
	}

	add_action('admin_init', 'mj_smgt_general_setting');

	function mj_smgt_general_setting()

	{

		$options = mj_smgt_option();

		foreach ($options as $key => $val) {

			add_option($key, $val);
		}
	}

	function mj_smgt_call_script_page()

	{

		$page_array = array(
			'smgt_school', 'smgt_admission', 'smgt_setup', 'smgt_student', 'smgt_student_homewrok', 'smgt_teacher', 'smgt_parent', 'smgt_Subject', 'smgt_class', 'smgt_route', 'smgt_attendence', 'smgt_exam',

			'smgt_grade', 'smgt_result', 'smgt_leave', 'smgt_document', 'smgt_transport', 'smgt_notice', 'smgt_event', 'smgt_message', 'smgt_hall', 'smgt_fees', 'smgt_fees_payment', 'smgt_payment', 'smgt_holiday', 'smgt_report', 'smgt_advance_report',

			'smgt_Migration', 'smgt_sms-setting', 'smgt_system_videos','smgt_gnrl_settings', 'smgt_supportstaff', 'smgt_library', 'custom_field', 'smgt_access_right', 'smgt_hostel', 'smgt_view-attendance', 'smgt_email_template', 'smgt_show_infographic', 'smgt_notification', 'smgt_homework', 'smgt_virtual_classroom', 'smgt_dashboard','smgt_tax'
		);

		return  $page_array;
	}

	function mj_smgt_change_adminbar_css($hook)

	{

		$current_page = $_REQUEST['page'];

		$page_array = mj_smgt_call_script_page();

		if (in_array($current_page, $page_array)) {

			wp_enqueue_style('smgt-calender-css11', 'https://appsforoffice.microsoft.com/fabric/fabric-core/4.0.0/fabric.min.css');



			wp_enqueue_style('smgt-calender-css', plugins_url('/assets/css/fullcalendar.min.css', __FILE__));

			wp_enqueue_style('smgt-datatable-min-css', plugins_url('/assets/css/dataTables.min.css', __FILE__), array('bootstrap-main'), '1.10.25', 'all');

			wp_enqueue_style('smgt-datatable-jq-css', plugins_url('/assets/css/jquery.dataTables.min.css', __FILE__));

			wp_enqueue_style('smgt-admin-style-css', plugins_url('/admin/css/admin-style.css', __FILE__));

			wp_enqueue_style('smgt-dynamic_css-css', plugins_url('/assets/css/dynamic_css.php', __FILE__));

			wp_enqueue_style('smgt-style-css', plugins_url('/assets/css/style.css', __FILE__));

			wp_enqueue_style('smgt-newversion', plugins_url('/assets/css/newversion.css', __FILE__));

			if (is_rtl()) {

				// Load RTL CSS.

				wp_enqueue_style('smgt-rtl-css', plugins_url('/assets/css/new_design_rtl.css', __FILE__));
			}

			wp_enqueue_style('smgt-dashboard-css', plugins_url('/assets/css/dashboard.css', __FILE__));

			wp_enqueue_style('smgt-popup-css', plugins_url('/assets/css/popup.css', __FILE__));

			wp_enqueue_style('smgt-datable-responsive-css', plugins_url('/assets/css/dataTables.responsive.css', __FILE__));

			wp_enqueue_style('smgt-multiselect-css', plugins_url('/assets/css/Bootstrap/bootstrap-multiselect.css', __FILE__));

			wp_enqueue_style('timepicker-min-css', plugins_url('/assets/css/Bootstrap/bootstrap-timepicker.min.css', __FILE__));

			wp_enqueue_script('smgt-calender', plugins_url('/assets/js/fullcalendar.min.js', __FILE__), array('jquery'), '4.1.1', true);

			// poppins font family css

			wp_enqueue_style('poppins-fontfamily-css', plugins_url('/assets/css/popping_font.css', __FILE__));

			// End  poppins font family css

			// new design css //

			wp_enqueue_style('smgt-new-design', plugins_url('/assets/css/smgt_new_design.css', __FILE__));

			wp_enqueue_style('smgt-responsive-new-design', plugins_url('/assets/css/smgt_responsive_new_design.css', __FILE__));

			wp_enqueue_style('smgt-roboto-fontfamily', plugins_url('/assets/css/roboto-font.css', __FILE__));

			// End new design css //

			wp_enqueue_script('smgt-defaultscript_ui', plugins_url('/assets/js/Jquery/jquery-ui.js', __FILE__), array('jquery'), '4.1.1', true);

			wp_enqueue_script('smgt-timeago-js', plugins_url('/assets/js/Jquery/jquery.timeago.js', __FILE__));

			wp_enqueue_script('smgt-timeago-js', plugins_url('/assets/js/Jquery/jquery-3.6.0.min.js', __FILE__), array('jquery'), '3.6.0', true);

			wp_enqueue_style('smgt-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans', false);

			wp_enqueue_script('smgt-datatable-editor', plugins_url('/assets/js/dataTables.editor.min.js', __FILE__));

			wp_enqueue_script('smgt-calender_moment', plugins_url('/assets/js/moment.min.js', __FILE__), array('jquery'), '4.1.1', true);





			/*--------Full calendar multilanguage---------*/

			$lancode = get_locale();

			$code = substr($lancode, 0, 2);

			wp_enqueue_script('smgt-calender-es', plugins_url('/assets/js/calendar-lang/' . $code . '.js', __FILE__), array('jquery'), '4.1.1', true);





			if (isset($_REQUEST['tab'])) {

				if ($_REQUEST['tab'] != 'view_all_message' && $_REQUEST['tab'] != 'view_all_message_reply') {

					wp_enqueue_script('smgt-datatable-css', plugins_url('/assets/js/datatables.min.js', __FILE__), array('jquery'), true);

					wp_enqueue_script('smgt-datatable-jq', plugins_url('/assets/js/Jquery/jquery.dataTables.min.js', __FILE__), array('jquery'), true);
				}
			} else {
				?>
				<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->
				<?php
				wp_enqueue_script('smgt-datatable', plugins_url('/assets/js/Jquery/jquery.dataTables.min.js', __FILE__), array('jquery'), true);
			}

			wp_enqueue_script('smgt-datatable-button', plugins_url('/assets/js/dataTables.buttons.min.js', __FILE__), array('jquery'), '1.5.6', true);

			wp_enqueue_script('vfs_fonts', plugins_url('/assets/js/vfs_fonts.js', __FILE__), array('jquery'), '0.1.53', true);

			wp_enqueue_script('pdfmake-min', plugins_url('/assets/js/pdfmake_min.js', __FILE__), array('jquery'), '0.1.53', true);

			wp_enqueue_script('smgt-buttons-html5', plugins_url('/assets/js/buttons.colVis.min.js', __FILE__), array('jquery'), '1.6.5', true);



			wp_enqueue_script('smgt-buttons-colVis-min', plugins_url('/assets/js/buttons.colVis.min.js', __FILE__), array('jquery'), '1.7.0', true);



			wp_enqueue_script('smgt-customjs', plugins_url('/assets/js/smgt_custom.js', __FILE__), array('jquery'), '4.1.1', true);

			wp_enqueue_script('smgt-icheckjs', plugins_url('/assets/js/icheck.min.js', __FILE__), array('jquery'), '4.1.1', true);

			wp_enqueue_script('smgt-popper-js', plugins_url('/assets/js/popper.min.js', __FILE__));

			wp_enqueue_script('smgt-multiselect', plugins_url('/assets/js/Bootstrap/bootstrap-multiselect.js', __FILE__), array('jquery'), '4.1.1', true);

			//Print and PDF

			wp_enqueue_script('smgt-dataTables-buttons-min', plugins_url('/assets/js/smgt-dataTables-buttons-min.js', __FILE__), array('jquery'), '4.1.1', true);

			wp_enqueue_script('smgt-buttons-print-min', plugins_url('/assets/js/smgt-buttons-print-min.js', __FILE__), array('jquery'), '4.1.1', true);



			wp_enqueue_script('smgt-popup', plugins_url('/assets/js/popup.js', __FILE__));

			wp_localize_script('smgt-popup', 'smgt', array('ajax' => admin_url('admin-ajax.php')));

			wp_enqueue_script('jquery');

			wp_enqueue_media();

			wp_enqueue_script('thickbox');

			wp_enqueue_style('thickbox');

			wp_enqueue_script('smgt-image-upload', plugins_url('/assets/js/image-upload.js', __FILE__), array('jquery'), '4.1.1', true);

			//image upload file alert msg languages translation

			wp_localize_script(
				'smgt-image-upload',
				'language_translate1',
				array(

					'allow_file_alert' => esc_attr__('Only jpg,jpeg,png File allowed', 'school-mgt'),

				)

			);

			wp_localize_script(
				'smgt-popup',
				'language_translate2',
				array(

					'edit_record_alert' => esc_attr__('Are you sure want to edit this record?', 'school-mgt'),

					'category_alert' => esc_attr__('You must fill out the field', 'school-mgt'),

					'class_limit_alert' => esc_attr__('Class Limit Is Full.', 'school-mgt'),

					'enter_room_alert' => esc_attr__('Please Enter Room Category Name.', 'school-mgt'),

					'enter_value_alert' => esc_attr__('Please Enter Value.', 'school-mgt'),

					'delete_record_alert' => esc_attr__('Are you sure you want to delete this record?', 'school-mgt'),

					'select_hall_alert' => esc_attr__('Please Select Exam Hall', 'school-mgt'),

					'one_record_alert' => esc_attr__('Please Select Atleast One Student', 'school-mgt'),

					'select_member_alert' => esc_attr__('Please select Student', 'school-mgt'),

					'one_record_select_alert' => esc_attr__('Please select atleast one record', 'school-mgt'),

					'one_class_select_alert' => esc_attr__('Please select atleast one class', 'school-mgt'),

					'one_select_Validation_alert' => esc_attr__('Please select atleast one Validation', 'school-mgt'),

					'lower_starting_year_alert' => esc_attr__('You can not select year lower then starting year', 'school-mgt'),

					'do_delete_record' => esc_attr__('Do you really want to delete this ?', 'school-mgt'),

					'select_one_book_alert' => esc_attr__('Please select atleast one book', 'school-mgt'),

					'select_different_student_alert' => esc_attr__('Please Select Different Student', 'school-mgt'),

					'select_user_label' => esc_attr__('Select Users', 'school-mgt'),

					'select_all_label' => esc_attr__('Select all', 'school-mgt'),

					'same_email_alert' => esc_attr__('you have used the same email', 'school-mgt'),



					'image_forame_alert' => esc_attr__("Only '.jpeg','.jpg', '.png', '.bmp' formats are allowed.", "school-mgt"),



					'more_then_exam_date_time' => esc_attr__("Fail! More than one subject exam date & time same.", "school-mgt"),



					'single_entry_alert' => esc_attr__("There is only single entry,You can not remove it.", "school-mgt"),



					'one_teacher_alert' => esc_attr__("Please select atleast one teacher", "school-mgt"),



					'one_assign_room__alert' => esc_attr__("Please select Student", "school-mgt"),



					'one_message_alert' => esc_attr__("Please select atleast one message", "school-mgt"),



					'large_file_Size_alert' => esc_attr__("Too large file Size. Only file smaller than 10MB can be uploaded.", "school-mgt"),



					'pdf_alert' => esc_attr__("Only pdf formate are allowed.", "school-mgt"),



					'starting_year_alert' => esc_attr__("You Can Not Select Ending Year Lower Than Starting Year", "school-mgt"),



					'one_user_replys_alert' => esc_attr__("Please select atleast one users to replys", "school-mgt"),



					'csv_alert' => esc_attr__("Problems with user: we are going to skip", "school-mgt"),

					'select_user' => esc_attr__("Select Users", "school-mgt"),

					'select_all' => esc_attr__("Select all", "school-mgt"),

					'mail_reminder' => esc_attr__("Are you sure you want to send a mail reminder?", "school-mgt"),

					'account_alert_1' => esc_attr__("Only jpeg,jpg,png and bmp formate are allowed.", "school-mgt"),

					'account_alert_2' => esc_attr__("formate are not allowed.", "school-mgt"),

					'exam_hallCapacity_1' => esc_attr__("Exam Hall Capacity", "school-mgt"),

					'exam_hallCapacity_2' => esc_attr__("Out Of", "school-mgt"),

					'exam_hallCapacity_3' => esc_attr__("Students.", "school-mgt")





				)

			);

			wp_enqueue_style('smgt-bootstrap-css', plugins_url('/assets/css/Bootstrap/bootstrap5.min.css', __FILE__));

			wp_enqueue_style('smgt-font-awesome-css', plugins_url('/assets/css/font-awesome.min.css', __FILE__));

			//-- font-awesome-6.2 css --//

			//wp_enqueue_style( 'smgt-font-awesome-css-6.2', plugins_url( '/assets/css/font-awesome6-all.min.css', __FILE__) );

			//---End - font-awesome css

			wp_enqueue_style('smgt-white-css', plugins_url('/assets/css/white.css', __FILE__));

			wp_enqueue_style('smgt-schoolmgt-min-css', plugins_url('/assets/css/schoolmgt.min.css', __FILE__));

			wp_enqueue_style('jq-ui-css-m', plugins_url('/assets/css/jquery-ui.css', __FILE__));

			wp_enqueue_style('smgt-bootstrap-min-css', plugins_url('/assets/css/Bootstrap/bootstrap.min.css', __FILE__));

			//metrial design csss

			wp_enqueue_style('smgt-bootstrap-inputs', plugins_url('/assets/css/material/bootstrap-inputs.css', __FILE__));

			//End metrial design csss

			if (is_rtl()) {

				wp_enqueue_style('smgt-bootstrap-rtl-css', plugins_url('/assets/css/Bootstrap/bootstrap-rtl.min.css', __FILE__));

				wp_enqueue_style('smgt-custome-rtl-css', plugins_url('/assets/css/custome_rtl.css', __FILE__));

				wp_enqueue_script('smgt-validationEngine-en-js', plugins_url('/assets/js/Jquery/jquery.validationEngine-ar.js', __FILE__));

				//wp_enqueue_script('smgt-validationEngine-en-js', plugins_url( '/assets/js/Jquery/jquery.validationEngine-en.js', __FILE__ ) );

			}



			wp_enqueue_style('smgt-responsive-css', plugins_url('/assets/css/school-responsive.css', __FILE__));

			wp_enqueue_style('smgt-buttons-dataTables-min-css', plugins_url('/assets/css/buttons.dataTables.min.css', __FILE__));



			wp_enqueue_script('smgt-bootstrap-js', plugins_url('/assets/js/Bootstrap/bootstrap5.min.js', __FILE__));

			//metrial design js

			wp_enqueue_script('smgt-material-min-js', plugins_url('/assets/js/material/material.min.js', __FILE__));

			//End metrial design js

			wp_enqueue_script('smgt-school-js', plugins_url('/assets/js/schooljs.js', __FILE__));

			wp_enqueue_script('smgt-waypoints-js', plugins_url('/assets/js/Jquery/jquery.waypoints.min.js', __FILE__));

			wp_enqueue_script('smgt-counterup-js', plugins_url('/assets/js/Jquery/jquery.counterup.min.js', __FILE__));

			wp_enqueue_script('jquery-ui-datepicker');

			//Vlidation style And Script

			//validation lib



			wp_enqueue_style('wcwm-validate-css', plugins_url('/lib/validationEngine/css/validationEngine.jquery.css', __FILE__));

			//------- time picker js -------//

			wp_enqueue_script('smgt-mdtimepicker-min-js', plugins_url('/assets/js/mdtimepicker.min.js', __FILE__));

			wp_enqueue_style('smgt-mdtimepicker-min-css', plugins_url('/assets/css/mdtimepicker.min.css', __FILE__));

			wp_enqueue_script('smgt-mdtimepicker-js', plugins_url('/assets/js/mdtimepicker.js', __FILE__));

			wp_enqueue_style('smgt-mdtimepicker-css', plugins_url('/assets/css/mdtimepicker.css', __FILE__));

			//------- time picker js -------//

			wp_register_script('jquery-validationEngine-' . $code . '', plugins_url('/lib/validationEngine/js/languages/jquery.validationEngine-' . $code . '.js', __FILE__), array('jquery'));

			wp_enqueue_script('jquery-validationEngine-' . $code . '');

			wp_register_script('jquery-validationEngine', plugins_url('/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array('jquery'));

			wp_enqueue_script('jquery-validationEngine');

			//------MULTIPLE SELECT ITEM JS -------------

			wp_enqueue_style('smgt-select2-css', plugins_url('/lib/select2-3.5.3/select2.css', __FILE__));

			wp_enqueue_script('smgt-select2', plugins_url('/lib/select2-3.5.3/select2.min.js', __FILE__), array('jquery'), '4.1.1', true);

			//------END MULTIPLE SELECT ITEM JS------//

			if (isset($_REQUEST['page']) && ($_REQUEST['page'] == 'report' || $_REQUEST['page'] == 'school')) {

				wp_enqueue_script('smgt-customjs', plugins_url('/assets/js/Chart.min.js', __FILE__), array('jquery'), '4.1.1', true);
			}

			wp_enqueue_script('smgt-custom_jobj', plugins_url('/assets/js/smgt_custom_confilict_obj.js', __FILE__), array('jquery'), '4.1.1', false);
		}
	}

	if (isset($_REQUEST['page'])) {

		add_action('admin_enqueue_scripts', 'mj_smgt_change_adminbar_css');
	}
}



function mj_smgt_upload_image()
{

	global $pagenow;

	if (isset($_REQUEST['page'])) {

		if ($_REQUEST['page'] == 'smgt_school') {

			// Now we'll replace the 'Insert into Post Button' inside Thickbox

			add_filter('gettext', 'mj_smgt_replace_thickbox_text', 1, 3);
		}
	}
}

add_action('admin_init', 'mj_smgt_upload_image');



function mj_smgt_replace_thickbox_text($translated_text, $text, $domain)
{

	if ('Insert into Post' == $text) {

		$referer = strpos(wp_get_referer(), 'wptuts-settings');

		if ($referer != '') {

			return esc_attr__('Upload Image', 'school-mgt');
		}
	}

	return $translated_text;
}

function mj_smgt_domain_load()
{

	load_plugin_textdomain('school-mgt', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'mj_smgt_domain_load');

function mj_smgt_install_login_page()

{



	if (!get_option('smgt_login_page')) {

		$curr_page = array(

			'post_title' => esc_attr__('School Management Login Page', 'school-mgt'),

			'post_content' => '[smgt_login]',

			'post_status' => 'publish',

			'post_type' => 'page',

			'comment_status' => 'closed',

			'ping_status' => 'closed',

			'post_category' => array(1),

			'post_parent' => 0
		);



		$curr_created = wp_insert_post($curr_page);

		update_option('smgt_login_page', $curr_created);
	}
}

function mj_smgt_install_student_registration_page()

{

	if (!get_option('mj_smgt_install_student_registration_page')) {

		$curr_page = array(

			'post_title' => esc_attr__('Student Registration', 'school-mgt'),

			'post_content' => '[smgt_student_registration]',

			'post_status' => 'publish',

			'post_type' => 'page',

			'comment_status' => 'closed',

			'ping_status' => 'closed',

			'post_category' => array(1),

			'post_parent' => 0
		);



		$curr_created = wp_insert_post($curr_page);

		update_option('mj_smgt_install_student_registration_page', $curr_created);
	}
}



function mj_smgt_user_dashboard()

{

	if (isset($_REQUEST['dashboard'])) {

		require_once SMS_PLUGIN_DIR . '/fronted_template.php';

		exit;
	}

	if (isset($_REQUEST['smgt_login'])) {

		add_action('authenticate', 'mj_smgt_pu_blank_login');
	}
}



function mj_smgt_remove_all_theme_styles()

{

	global $wp_styles;

	$wp_styles->queue = array();
}

if (isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'user') {

	add_action('wp_print_styles', 'mj_smgt_remove_all_theme_styles', 100);
}



function mj_smgt_load_script1()

{

	if (isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'user') {

		wp_register_script('smgt-popup-front', plugins_url('assets/js/popup.js', __FILE__), array('jquery'));

		wp_enqueue_script('smgt-popup-front');

		wp_localize_script('smgt-popup-front', 'smgt', array('ajax' => admin_url('admin-ajax.php')));

		wp_localize_script(
			'smgt-popup-front',
			'language_translate2',
			array(

				'edit_record_alert' => esc_attr__('Are you sure want to edit this record?', 'school-mgt'),

				'category_alert' => esc_attr__('You must fill out the field!', 'school-mgt'),

				'class_limit_alert' => esc_attr__('Class Limit Is Full.', 'school-mgt'),

				'enter_room_alert' => esc_attr__('Please Enter Room Category Name.', 'school-mgt'),

				'enter_value_alert' => esc_attr__('Please Enter Value.', 'school-mgt'),

				'delete_record_alert' => esc_attr__('Are you sure you want to delete this record?', 'school-mgt'),

				'select_hall_alert' => esc_attr__('Please Select Exam Hall', 'school-mgt'),

				'one_record_alert' => esc_attr__('Please Checked Atleast One Student', 'school-mgt'),

				'select_member_alert' => esc_attr__('Please select Student', 'school-mgt'),

				//New Updated alert message//

				'one_record_select_alert' => esc_attr__('Please select atleast one record', 'school-mgt'),

				'one_class_select_alert' => esc_attr__('Please select atleast one class', 'school-mgt'),

				'one_select_Validation_alert' => esc_attr__('Please select atleast one Validation', 'school-mgt'),

				'lower_starting_year_alert' => esc_attr__('You can not select year lower then starting year', 'school-mgt'),

				'do_delete_record' => esc_attr__('Do you really want to delete this ?', 'school-mgt'),

				'select_one_book_alert' => esc_attr__('Please select atleast one book', 'school-mgt'),

				'select_different_student_alert' => esc_attr__('Please Select Different Student', 'school-mgt'),

				'select_user_label' => esc_attr__('Select Users', 'school-mgt'),

				'select_all_label' => esc_attr__('Select all', 'school-mgt'),

				'same_email_alert' => esc_attr__('you have used the same email', 'school-mgt'),



				'image_forame_alert' => esc_attr__("Only '.jpeg','.jpg', '.png', '.bmp' formats are allowed.", "school-mgt"),



				'more_then_exam_date_time' => esc_attr__("Fail! More than one subject exam date & time same.", "school-mgt"),



				'single_entry_alert' => esc_attr__("There is only single entry,You can not remove it.", "school-mgt"),



				'one_teacher_alert' => esc_attr__("Please select atleast one teacher", "school-mgt"),



				'one_assign_room__alert' => esc_attr__("Please select Student", "school-mgt"),



				'one_message_alert' => esc_attr__("Please select atleast one message", "school-mgt"),



				'large_file_Size_alert' => esc_attr__("Too large file Size. Only file smaller than 10MB can be uploaded.", "school-mgt"),



				'pdf_alert' => esc_attr__("Only pdf formate are allowed.", "school-mgt"),



				'starting_year_alert' => esc_attr__("You can not select year lower then starting year", "school-mgt"),



				'one_user_replys_alert' => esc_attr__("Please select atleast one users to replys", "school-mgt"),

				'csv_alert' => esc_attr__("Problems with user: we are going to skip", "school-mgt"),

				'mail_reminder' => esc_attr__("Are you sure you want to send a mail reminder?", "school-mgt"),

				'account_alert_1' => esc_attr__("Only jpeg,jpg,png and bmp formate are allowed.", "school-mgt"),

				'account_alert_2' => esc_attr__("formate are not allowed.", "school-mgt"),

				'exam_hallCapacity_1' => esc_attr__("Exam Hall Capacity", "school-mgt"),

				'exam_hallCapacity_2' => esc_attr__("Out Of", "school-mgt"),

				'exam_hallCapacity_3' => esc_attr__("Students.", "school-mgt")

			)

		);

		wp_enqueue_script('jquery');
	}
}

function mj_smgt_registration_form($class_name, $first_name, $middle_name, $last_name, $gender, $birth_date, $address, $city_name, $state_name, $zip_code, $mobile_number, $alternet_mobile_number, $phone, $email, $username, $password, $smgt_user_avatar)

{

	wp_enqueue_style('smgt-bootstrap-inputs', plugins_url('/assets/css/material/bootstrap-inputs.css', __FILE__));

	wp_enqueue_script('smgt-material-min-js', plugins_url('/assets/js/material/material.min.js', __FILE__));

	//-------------- MATERIAL DESIGN ---------------//

	wp_enqueue_style('timepicker-min-css', plugins_url('/assets/css/Bootstrap/bootstrap-timepicker.min.css', __FILE__));

	wp_enqueue_script('smgt-defaultscript', plugins_url('/assets/js/Jquery/jquery-3.6.0.min.js', __FILE__), array('jquery'), '4.1.1', true);

	wp_enqueue_script('smgt-bootstrap-js', plugins_url('/assets/js/Bootstrap/bootstrap5.min.js', __FILE__));

	$lancode = get_locale();

	$code = substr($lancode, 0, 2);

	wp_enqueue_style('wcwm-validate-css', plugins_url('/lib/validationEngine/css/validationEngine.jquery.css', __FILE__));

	wp_register_script('jquery-3.6.0', plugins_url('/lib/validationEngine/js/jquery-3.6.0.min.js', __FILE__), array('jquery'));

	wp_enqueue_script('jquery-3.6.0');

	wp_register_script('jquery-validationEngine-' . $code . '', plugins_url('/lib/validationEngine/js/languages/jquery.validationEngine-' . $code . '.js', __FILE__), array('jquery'));

	wp_enqueue_script('jquery');

	wp_enqueue_media();

	wp_enqueue_script('thickbox');

	wp_enqueue_style('thickbox');

	wp_enqueue_script('jquery-validationEngine-' . $code . '');

	wp_register_script('jquery-validationEngine', plugins_url('/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array('jquery'));

	wp_enqueue_script('jquery-validationEngine');
	wp_enqueue_style('smgt-dynamic_css-css', plugins_url('/assets/css/dynamic_css.php', __FILE__));
	wp_enqueue_style('smgt-style-css', plugins_url('/assets/css/style.css', __FILE__));

	wp_enqueue_style('smgt-bootstrap-css', plugins_url('/assets/css/Bootstrap/bootstrap5.min.css', __FILE__));

	wp_enqueue_style('smgt-bootstrap-min-css', plugins_url('/assets/css/Bootstrap/bootstrap.min.css', __FILE__));

	wp_enqueue_style('smgt-responsive-css', plugins_url('/assets/css/school-responsive.css', __FILE__));

	if (is_rtl()) {

		wp_register_script('jquery-validationEngine-en', plugins_url('/lib/validationEngine/js/languages/jquery.validationEngine-' . $code . '.js', __FILE__), array('jquery'));

		wp_enqueue_script('smgt-validationEngine-en-js', plugins_url('/assets/js/Jquery/jquery.validationEngine-ar.js', __FILE__));

		wp_enqueue_style('css-custome_rtl-css', plugins_url('/assets/css/custome_rtl.css', __FILE__));
	}

	wp_enqueue_script('jquery-ui-datepicker');

	wp_register_script('font-awsome-js', plugins_url('/assets/js/fontawesome.min.js', __FILE__));

	wp_enqueue_script('smgt-custom_jobj', plugins_url('/assets/js/smgt_custom_confilict_obj.js', __FILE__), array('jquery'), '4.1.1', false);



	wp_enqueue_style('css-custome_rtl-css', plugins_url('/assets/css/custome_rtl.css', __FILE__));

	wp_enqueue_style('register-css', plugins_url('/assets/css/settings/register.css', __FILE__));

	wp_enqueue_style('jq-ui-css-m', plugins_url('/assets/css/jquery-ui.css', __FILE__));

	wp_enqueue_script('smgt-defaultscript_ui', plugins_url('/assets/js/Jquery/jquery-ui.js', __FILE__), array('jquery'), '4.1.1', true);

?>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/assets/js/Jquery/jquery-3.6.0.min.js'; ?>"></script>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/assets/js/Jquery/jquery-ui.js'; ?>"></script>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/lib/validationEngine/js/languages/jquery.validationEngine-' . $code . '.js'; ?>"></script>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
<?php

?>
	<script>
		jQuery(document).ready(function($) {

			"use strict";

			$('#registration_form').validationEngine({
				promptPosition: "bottomLeft",
				maxErrorsPerField: 1
			});

			jQuery('#birth_date').datepicker({
				maxDate: 0,
				dateFormat: "<?php echo get_option('smgt_datepicker_format'); ?>",
				changeMonth: true,
				changeYear: true,
				yearRange: '-65:+25',
				onChangeMonthYear: function(year, month, inst) {
					jQuery(this).val(month + "/" + year);
				}
			});
			
			//custom field datepicker

			$('.after_or_equal').datepicker({

				dateFormat: "yy-mm-dd",

				minDate: 0,

				changeMonth: true,

				changeYear: true,
				beforeShow: function(textbox, instance)

				{

					instance.dpDiv.css({

						marginTop: (-textbox.offsetHeight) + 'px'

					});

				}

			});

			$('.date_equals').datepicker({

				dateFormat: "yy-mm-dd",

				minDate: 0,

				maxDate: 0,

				changeMonth: true,

				changeYear: true,

				beforeShow: function(textbox, instance)

				{

					instance.dpDiv.css({

						marginTop: (-textbox.offsetHeight) + 'px'

					});

				}

			});

			$('.before_or_equal').datepicker({

				dateFormat: "yy-mm-dd",

				maxDate: 0,
				changeMonth: true,

				changeYear: true,
				beforeShow: function(textbox, instance)

				{

					instance.dpDiv.css({

						marginTop: (-textbox.offsetHeight) + 'px'

					});

				}

			});



			$('.space_validation').on('keypress', function(e)

				{

					if (e.which === 32)

						return false;

				});



			//Custom Field File Validation//

			function Smgt_custom_filed_fileCheck(obj)

			{

				"use strict";

				var fileExtension = $(obj).attr('file_types');

				var fileExtensionArr = fileExtension.split(',');

				var file_size = $(obj).attr('file_size');



				var sizeInkb = obj.files[0].size / 1024;



				if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionArr) == -1)

				{

					alert("Only " + fileExtension + "formats are allowed.");

					$(obj).val('');

				} else if (sizeInkb > file_size)

				{

					alert("Only " + file_size + " kb size is allowed");

					$(obj).val('');

				}

			}

		});

		function fileCheck(obj)

		{

			var fileExtension = ['jpeg', 'jpg', 'png', 'bmp', ''];

			if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)

			{

				alert("Only " + fileExtension + "formats are allowed.");

				$(obj).val('');

			}

		}
	</script>

	<?php

	if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'success_1') {

	?>



		<div class="col-lg-12 col-md-12 admission_successfully_message">

			<?php

			esc_attr_e('Registration complete.Your account active after admin can approve.', 'school-mgt');

			?>

		</div>

	<?php

	}

	$edit = 0;

	$theme_name = get_current_theme();



	if ($theme_name == 'Twenty Twenty') {

	?>

		<style>
			.singular .entry-header {

				padding: 0px !important;

			}

			.post-inner {

				padding-top: 4rem !important;

			}

			.entry-content>*:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide) {

				max-width: 60% !important;

			}
		</style>

	<?php

	}

	?>

	<style>
		.entry-content {

			font-family: Poppins !important;

		}

		div.student_registraion_form {

			font-style: normal !important;

			font-family: Poppins !important;

		}

		.save_btn {

			height: 46px;

			background-color: <?php echo get_option('smgt_system_color_code'); ?> !important;

			background: <?php echo get_option('smgt_system_color_code'); ?>;

			color: #fff !important;

			width: 100% !important;

			font-weight: 500 !important;

			font-size: 16px !important;

			line-height: 24px;

			text-align: center;

			color: #FFFFFF;

			text-transform: uppercase;

			border: 0px solid black !important;

		}

		.line_height_29px_registration_from {

			line-height: 29px !important;

		}

		.line_height_27px_registration_from {

			line-height: 25px !important;

		}

		.form-control:focus {

			box-shadow: 0 0 0 0rem rgb(13 110 253 / 25%) !important;

		}

		.res_margin_bottom_20px {
			margin-bottom: 20px;
		}
	</style>
	<?php
	if (is_rtl()) {
	?>
		<style>
			div.row select {
    			background: #F2F5FA url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E) no-repeat right 95% top 55% !important;
			}
			#registration_form div label {
				direction: rtl;
				left: 70% !important;
			}

			#registration_form div label.label_mobile_number {
				direction: rtl;
				left: 35% !important;
			}
			#registration_form div label.label_email {
				direction: rtl;
				left: 60% !important;
			}
			div.row div select {
				background: #F2F5F9 url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E) no-repeat left 5px top 55% !important;
			}
			#togglePassword {
				position: absolute;
				top: 30%;
				left: 5% !important;
				right: unset !important;
				font-size: 17px;
				cursor: pointer;
			}
		</style>
	<?php
	}
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<div class="student_registraion_form">

		<form id="registration_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">

			<div class="form-body user_form"> <!------  Form Body -------->

				<div class="row">

					<div class="col-md-6 input error_msg_left_margin responsive_bottom_5">

						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Name', 'school-mgt'); ?><span class="required">*</span></label>

						<select name="class_name" class="line_height_27px_registration_from form-control validate[required] width_100" id="class_name">

							<option value=""><?php echo esc_attr_e('Select Class', 'school-mgt'); ?></option>

							<?php

							$classval = $class_name;

							foreach (mj_smgt_get_allclass() as $classdata) {

							?>

								<option value="<?php echo $classdata['class_id']; ?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name']; ?></option>

							<?php

							}

							?>

						</select>

					</div>

					<?php

					if (get_option("smgt_registration_fees") == "yes") {

						$fees_id = get_option('smgt_registration_amount');

						$obj_fees = new Smgt_fees();

						$amount = $obj_fees->mj_smgt_get_single_feetype_data_amount($fees_id);

						if ($amount) {

							$fees = $amount;
						} else {

							$fees = 0;
						}

					?>

						<div class="col-md-6 error_msg_left_margin">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input class="line_height_29px_registration_from form-control text-input" type="text" readonly value="<?php echo mj_smgt_get_currency_symbol() . ' ' . $fees; ?>">

									<label for="userinput1" class="active"><?php esc_html_e('Registration Fees', 'school-mgt'); ?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<input id="registration_fees" class="form-control" type="hidden" name="registration_fees" value="<?php echo get_option('smgt_registration_amount'); ?>">

					<?php

					}

					?>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="first_name" class="line_height_29px_registration_from form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if ($edit) {
																																																						echo $user_info->first_name;
																																																					} elseif (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" name="first_name">

								<label for="userinput1" class="active"><?php esc_html_e('First Name', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="middle_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text" value="<?php if ($edit) {
																																																	echo $user_info->middle_name;
																																																} elseif (isset($_POST['middle_name'])) echo $_POST['middle_name']; ?>" name="middle_name">

								<label for="userinput1" class="active"><?php esc_html_e('Middle Name', 'school-mgt'); ?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="last_name" class="line_height_29px_registration_from form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if ($edit) {
																																																						echo $user_info->last_name;
																																																					} elseif (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" name="last_name">

								<label for="userinput1" class="active"><?php esc_html_e('Last Name', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-6 res_margin_bottom_20px">

						<div class="form-group">

							<div class="col-md-12 form-control">

								<div class="row padding_radio mb-0">

									<div class="input-group mb-0">

										<label class="custom-top-label margin_left_0 gender_label_rtl"><?php esc_html_e('Gender', 'school-mgt'); ?><span class="required">*</span></label>

										<div class="d-inline-block mb-1">

											<?php $genderval = "male";
											if ($edit) {
												$genderval = $user_info->gender;
											} elseif (isset($_POST['gender'])) {
												$genderval = $_POST['gender'];
											} ?>

											<input type="radio" value="male" class="tog validate[required]" name="gender" <?php checked('male', $genderval);  ?> />

											<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male', 'school-mgt'); ?></label>

											&nbsp;&nbsp;

											<input type="radio" value="female" class="tog validate[required]" name="gender" <?php checked('female', $genderval);  ?> />

											<label class="custom-control-label" for="female"><?php esc_html_e('Female', 'school-mgt'); ?></label>

											&nbsp;&nbsp;

											<input type="radio" value="other" class="tog validate[required]" name="gender" <?php checked('other', $genderval);  ?> />

											<label class="custom-control-label" for="female"><?php esc_html_e('Other', 'school-mgt'); ?></label>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="col-md-6 ">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="birth_date" class="line_height_29px_registration_from validate[required]" type="text" name="birth_date" value="<?php if ($edit) {
																																								echo mj_smgt_getdate_in_input_box($user_info->birth_date);
																																							} elseif (isset($_POST['birth_date'])) {
																																								echo mj_smgt_getdate_in_input_box($_POST['birth_date']);
																																							} else {
																																								echo mj_smgt_getdate_in_input_box(date("Y-m-d"));
																																							} ?>" readonly>

								<label for="userinput1" class="active"><?php esc_html_e('Date of Birth', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="address" class="line_height_29px_registration_from form-control validate[required,custom[address_description_validation]]" maxlength="120" type="text" name="address" value="<?php if ($edit) {
																																																							echo $user_info->address;
																																																						} elseif (isset($_POST['address'])) echo $_POST['address']; ?>">

								<label for="userinput1" class="active"><?php esc_html_e('Address', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="city_name" class="line_height_29px_registration_from form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text" name="city_name" value="<?php if ($edit) {
																																																								echo $user_info->city;
																																																							} elseif (isset($_POST['city_name'])) echo $_POST['city_name']; ?>">

								<label for="userinput1" class="active"><?php esc_html_e('City', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="state_name" class="line_height_29px_registration_from form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text" name="state_name" value="<?php if ($edit) {
																																																						echo $user_info->state;
																																																					} elseif (isset($_POST['state_name'])) echo $_POST['state_name']; ?>">

								<label for="userinput1" class="active"><?php esc_html_e('State', 'school-mgt'); ?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="zip_code" class="form-control line_height_29px_registration_from validate[required,custom[zipcode]]" maxlength="15" type="text" name="zip_code" value="<?php if ($edit) {
																																																		echo $user_info->zip_code;
																																																	} elseif (isset($_POST['zip_code'])) echo $_POST['zip_code']; ?>">

								<label for="userinput1" class="active"><?php esc_html_e('Zip Code', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-2">

						<div class="form-group input margin_bottom_0">

							<div class="col-md-12 form-control">

								<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option('smgt_contry')); ?>" class="line_height_29px_registration_from country_code_res" name="phonecode">

								<label for="phonecode" class="pl-2 label_mobile_number"><?php esc_html_e('Code', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-4 mobile_error_massage_left_margin">

						<div class="form-group input margin_bottom_0">

							<div class="col-md-12 form-control">

								<input id="mobile_number" class="line_height_29px_registration_from form-control text-input validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text" name="mobile_number" maxlength="10" value="<?php if ($edit) {
																																																														echo $user_info->mobile_number;
																																																													} elseif (isset($_POST['mobile_number'])) echo $_POST['mobile_number']; ?>">

								<label for="userinput6" class="label_mobile_number"><?php esc_html_e('Mobile Number', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-2">

						<div class="form-group input margin_bottom_0">

							<div class="col-md-12 form-control">

								<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option('smgt_contry')); ?>" class="line_height_29px_registration_from" name="alter_mobile_number">

								<label for="phonecode" class="pl-2 label_mobile_number"><?php esc_html_e('Code', 'school-mgt'); ?></label>

							</div>

						</div>

					</div>

					<div class="col-md-4 mobile_error_massage_left_margin">

						<div class="form-group input margin_bottom_0">

							<div class="col-md-12 form-control">

								<input id="alternet_mobile_number" class="line_height_29px_registration_from form-control text-input" type="text" name="alternet_mobile_number" maxlength="10" value="<?php if ($edit) {
																																																			echo $user_info->alternet_mobile_number;
																																																		} elseif (isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number']; ?>">

								<label for="userinput6" class="label_mobile_number"><?php esc_html_e('Alternate Mobile Number', 'school-mgt'); ?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="email" class="line_height_29px_registration_from form-control validate[required,custom[email]] text-input " maxlength="100" type="text" name="email" value="<?php if ($edit) {
																																																			echo $user_info->user_email;
																																																		} elseif (isset($_POST['email'])) echo $_POST['email']; ?>">

								<label for="userinput1" class="label_email active"><?php esc_html_e('Email', 'school-mgt'); ?><span class="required">*</span></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="password" class="line_height_29px_registration_from form-control <?php if (!$edit) {
																												echo 'validate[required,minSize[8],maxSize[12]]';
																											} else {
																												echo 'validate[minSize[8],maxSize[12]]';
																											} ?>" type="password" name="password" value="">

								<label for="userinput1" class="active"><?php esc_html_e('Password', 'school-mgt'); ?><?php if (!$edit) { ?><span class="required">*</span><?php } ?></label>
								<i class="fa fa-eye-slash" id="togglePassword"></i>

							</div>

						</div>

					</div>
					<script>
						// PASSWORD SHOWING TOGGLE BUTTON

						const togglePassword = document.querySelector("#togglePassword");
						const password = document.querySelector("#password");

						togglePassword.addEventListener("click", function() {
							// toggle the type attribute
							const type = password.getAttribute("type") === "password" ? "text" : "password";
							password.setAttribute("type", type);

							// toggle the Font Awesome icon class
							this.classList.toggle("fa-eye");
							this.classList.toggle("fa-eye-slash");
						});
					</script>
					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px mb-0" style="padding:0px;padding-left:10px;">

								<div class="col-sm-12 display_flex mb-0">

									<input type="file" style="border:0px;margin-bottom:0px;" class="form-control" onchange="fileCheck(this);" name="smgt_user_avatar">

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>



			<?php

			//Get Module Wise Custom Field Data

			$custom_field_obj = new Smgt_custome_field;



			$module = 'student';



			$compact_custom_field = $custom_field_obj->mj_smgt_getCustomFieldByModule($module);



			if (!empty($compact_custom_field)) {

			?>

				<!-- <div class="header">

					<h3><?php esc_html_e('Custom Fields', 'school-mgt'); ?></h3>

				</div>-->

				<div class="form-body user_form">

					<div class="row">

						<?php

						foreach ($compact_custom_field as $custom_field) {

							if ($edit) {

								$custom_field_id = $custom_field->id;



								$module_record_id = $_REQUEST['student_id'];



								$custom_field_value = $custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module, $module_record_id, $custom_field_id);
							}



							// Custom Field Validation //

							$exa = explode('|', $custom_field->field_validation);

							$min = "";

							$max = "";

							$required = "";

							$red = "";

							$limit_value_min = "";

							$limit_value_max = "";

							$numeric = "";

							$alpha = "";

							$space_validation = "";

							$alpha_space = "";

							$alpha_num = "";

							$email = "";

							$url = "";

							$minDate = "";

							$maxDate = "";

							$file_types = "";

							$file_size = "";

							$datepicker_class = "";

							foreach ($exa as $key => $value) {

								if (strpos($value, 'min') !== false) {

									$min = $value;

									$limit_value_min = substr($min, 4);
								} elseif (strpos($value, 'max') !== false) {

									$max = $value;

									$limit_value_max = substr($max, 4);
								} elseif (strpos($value, 'required') !== false) {

									$required = "required";

									$red = "*";
								} elseif (strpos($value, 'numeric') !== false) {

									$numeric = "number";
								} elseif ($value == 'alpha') {

									$alpha = "onlyLetterSp";

									$space_validation = "space_validation";
								} elseif ($value == 'alpha_space') {

									$alpha_space = "onlyLetterSp";
								} elseif (strpos($value, 'alpha_num') !== false) {

									$alpha_num = "onlyLetterNumber";
								} elseif (strpos($value, 'email') !== false) {

									$email = "email";
								} elseif (strpos($value, 'url') !== false) {

									$url = "url";
								} elseif (strpos($value, 'after_or_equal:today') !== false) {

									$minDate = 1;

									$datepicker_class = 'after_or_equal';
								} elseif (strpos($value, 'date_equals:today') !== false) {

									$minDate = $maxDate = 1;

									$datepicker_class = 'date_equals';
								} elseif (strpos($value, 'before_or_equal:today') !== false) {

									$maxDate = 1;

									$datepicker_class = 'before_or_equal';
								} elseif (strpos($value, 'file_types') !== false) {

									$types = $value;



									$file_types = substr($types, 11);
								} elseif (strpos($value, 'file_upload_size') !== false) {

									$size = $value;

									$file_size = substr($size, 17);
								}
							}

							$option = $custom_field_obj->mj_smgt_getDropDownValue($custom_field->id);

							$data = 'custom.' . $custom_field->id;

							$datas = 'custom.' . $custom_field->id;



							if ($custom_field->field_type == 'text') {

						?>

								<div class="col-md-6">

									<div class="form-group input">

										<div class="col-md-12 form-control">

											<input class="form-control hideattar<?php echo $custom_field->form_name; ?> validate[<?php if (!empty($required)) {
																																		echo $required; ?>,<?php } ?><?php if (!empty($limit_value_min)) { ?> minSize[<?php echo $limit_value_min; ?>],<?php }
																																																																		if (!empty($limit_value_max)) { ?> maxSize[<?php echo $limit_value_max; ?>],<?php }
																																																																																							if ($numeric != '' || $alpha != '' || $alpha_space != '' || $alpha_num != '' || $email != '' || $url != '') { ?> custom[<?php echo $numeric;
																																																																																																																							echo $alpha;
																																																																																																																							echo $alpha_space;
																																																																																																																							echo $alpha_num;
																																																																																																																							echo $email;
																																																																																																																							echo $url; ?>]<?php } ?>] <?php echo $space_validation; ?>" type="text" name="custom[<?php echo $custom_field->id; ?>]" id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>" <?php if ($edit) { ?> value="<?php echo $custom_field_value; ?>" <?php } ?>>

											<label for="<?php echo $custom_field->id; ?>" class=""><?php esc_html_e($custom_field->field_label, 'school-mgt'); ?><span class="required red"><?php echo $red; ?></span></label>

										</div>

									</div>

								</div>

							<?php

							} elseif ($custom_field->field_type == 'textarea') {

							?>

								<div class="col-md-6 note_text_notice has-feedback">

									<div class="form-group input">

										<div class="col-md-12 note_border">

											<div class="form-field">

												<textarea rows="3" class="textarea_height_47px form-control hideattar<?php echo $custom_field->form_name; ?> validate[<?php if (!empty($required)) {
																																											echo $required; ?>,<?php } ?><?php if (!empty($limit_value_min)) { ?> minSize[<?php echo $limit_value_min; ?>],<?php }
																																																																											if (!empty($limit_value_max)) { ?> maxSize[<?php echo $limit_value_max; ?>],<?php }
																																																																																																if ($numeric != '' || $alpha != '' || $alpha_space != '' || $alpha_num != '' || $email != '' || $url != '') { ?> custom[<?php echo $numeric;
																																																																																																																																echo $alpha;
																																																																																																																																echo $alpha_space;
																																																																																																																																echo $alpha_num;
																																																																																																																																echo $email;
																																																																																																																																echo $url; ?>]<?php } ?>] <?php echo $space_validation; ?>" name="custom[<?php echo $custom_field->id; ?>]" id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>"><?php if ($edit) {
																																																																																																																																																																																																							echo $custom_field_value;
																																																																																																																																																																																																						} ?></textarea>

												<span class="txt-title-label"></span>

												<label class="text-area address"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>

											</div>

										</div>

									</div>

								</div>

							<?php

							} elseif ($custom_field->field_type == 'date') {

							?>

								<div class="col-md-6">

									<div class="form-group input">

										<div class="col-md-12 form-control has-feedback">

											<input type="text" class="form-control  custom_datepicker <?php echo $datepicker_class; ?> hideattar<?php echo $custom_field->form_name; ?> <?php if (!empty($required)) { ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>]" <?php if ($edit) { ?> value="<?php echo $custom_field_value; ?>" <?php } ?>id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>">

											<label for="bdate" class=""><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>

										</div>

									</div>

								</div>

							<?php

							} elseif ($custom_field->field_type == 'dropdown') {

							?>

								<div class="col-md-6 col-sm-6 input">

									<label class="ml-1 custom-top-label top" for="<?php echo $custom_field->id; ?>"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>

									<select class="form-control hideattar<?php echo $custom_field->form_name; ?>

										<?php if (!empty($required)) { ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>]" id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>">

										<option value=""> <?php esc_attr_e('Select', 'school-mgt'); ?></option>

										<?php

										if (!empty($option)) {

											foreach ($option as $options) {

										?>

												<option value="<?php echo $options->option_label; ?>" <?php if ($edit) {
																											echo selected($custom_field_value, $options->option_label);
																										} ?>> <?php echo $options->option_label; ?></option>

										<?php

											}
										}

										?>

									</select>

								</div>



							<?php

							} elseif ($custom_field->field_type == 'checkbox') {

							?>

								<div class="col-md-6 mb-3 smgt_main_custome_field">

									<div class="form-group">

										<div class="col-md-12 form-control">

											<div class="row padding_radio">

												<div class="">

													<label class="custom-top-label margin_left_0"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>

													<?php

													if (!empty($option)) {

														foreach ($option as $options) {

															if ($edit) {

																$custom_field_value_array = explode(',', $custom_field_value);
															}

													?>

															<div class="mr-1 d-inline-block custom-control custom-checkbox">

																<input type="checkbox" value="<?php echo $options->option_label; ?>" <?php if ($edit) {
																																			echo checked(in_array($options->option_label, $custom_field_value_array));
																																		} ?> class="hideattar<?php echo $custom_field->form_name; ?><?php if (!empty($required)) { ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>][]">&nbsp;&nbsp;<span class="span_left_custom" style="margin-bottom: -5px;">

																	<label class="custom-control-label" for="colorCheck1"><?php echo $options->option_label; ?></label>

															</div>

													<?php

														}
													}

													?>



												</div>

											</div>

										</div>

									</div>

								</div>

							<?php

							} elseif ($custom_field->field_type == 'radio') {

							?>



								<div class="col-md-6 mb-3 rtl_margin_top_15px">

									<div class="form-group">

										<div class="col-md-12 form-control">

											<div class="row padding_radio">

												<div class="input-group">

													<label class="custom-top-label margin_left_0"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>



													<?php

													if (!empty($option)) {

														foreach ($option as $options) {

													?>

															<div class="d-inline-block">

																<label class="radio-inline">

																	<input type="radio" value="<?php echo $options->option_label; ?>" <?php if ($edit) {
																																			echo checked($options->option_label, $custom_field_value);
																																		} ?> name="custom[<?php echo $custom_field->id; ?>]" class="custom-control-input hideattar<?php echo $custom_field->form_name; ?> <?php if (!empty($required)) { ?> validate[<?php echo $required; ?>] <?php } ?>  " id="<?php echo $options->option_label; ?>">

																	<?php echo $options->option_label; ?>

																</label>&nbsp;&nbsp;

															</div>

													<?php

														}
													}

													?>

												</div>

											</div>

										</div>

									</div>

								</div>

							<?php

							} elseif ($custom_field->field_type == 'file') {

							?>

								<div class="col-md-6">

									<div class="form-group input">

										<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">

											<label for="photo" class="custom-control-label custom-top-label ml-2"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>



											<div class="col-sm-12 display_flex">

												<input type="hidden" name="hidden_custom_file[<?php echo $custom_field->id; ?>]" value="<?php if ($edit) {
																																			echo $custom_field_value;
																																		} ?>">

												<input type="file" onchange="mj_smgt_custom_filed_fileCheck(this);" Class="hideattar<?php echo $custom_field->form_name;
																																	if ($edit) {
																																		if (!empty($required)) {
																																			if ($custom_field_value == '') { ?> validate[<?php echo $required; ?>] <?php }
																																																																		}
																																																																	} else {
																																																																		if (!empty($required)) { ?> validate[<?php echo $required; ?>] <?php }
																																																																																					} ?>" name="custom_file[<?php echo $custom_field->id; ?>]" <?php if ($edit) { ?> value="<?php echo $custom_field_value; ?>" <?php } ?> id="<?php echo $custom_field->id; ?>" file_types="<?php echo $file_types; ?>" file_size="<?php echo $file_size; ?>">

											</div>

										</div>

									</div>

								</div>

						<?php

							}
						}

						?>

					</div>

				</div>

			<?php

			}

			?>

			<?php wp_nonce_field('save_student_frontend_shortcode_nonce'); ?>

			<div class="form-body user_form">

				<div class="row">

					<div class="col-sm-6">

						<input type="submit" value="<?php esc_attr_e('Registration', 'school-mgt'); ?>" name="save_student_front" class="btn btn-success btn_style save_btn" />

					</div>

				</div>

			</div>

		</form>

	</div>

	<script>
		//Custom Field File Validation//

		function mj_smgt_custom_filed_fileCheck(obj)

		{

			"use strict";

			var fileExtension = $(obj).attr('file_types');

			var fileExtensionArr = fileExtension.split(',');

			var file_size = $(obj).attr('file_size');



			var sizeInkb = obj.files[0].size / 1024;



			if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionArr) == -1)

			{

				alert("Only " + fileExtension + " formats are allowed.");

				$(obj).val('');

			} else if (sizeInkb > file_size)

			{

				alert("Only " + file_size + " kb size is allowed.");

				$(obj).val('');

			}

		}



		jQuery(document).ready(function($)

			{

				$('.custom_datepicker').datepicker({

					endDate: '+0d',

					autoclose: true,

					orientation: "bottom" // <-- and add this

				});

			});
	</script>

	<?php

}

function mj_smgt_complete_registration($class_name, $first_name, $middle_name, $last_name, $gender, $birth_date, $address, $city_name, $state_name, $zip_code, $mobile_number, $alternet_mobile_number, $phone, $email, $username, $password, $smgt_user_avatar, $wp_nonce)
{

	global $reg_errors;

	$custom_field_obj = new Smgt_custome_field;



	if (wp_verify_nonce($wp_nonce, 'save_student_frontend_shortcode_nonce')) {

		if (1 > count($reg_errors->get_error_messages())) {



			$userdata = array(

				'user_login'    =>   $email,

				'user_email'    =>   $email,

				'user_pass'     =>   $password,

				'user_url'      =>   NULL,

				'first_name'    =>   $first_name,

				'last_name'     =>   $last_name,

				'nickname'      =>   NULL

			);

			$user_id = wp_insert_user($userdata);



			if (get_option("smgt_registration_fees") == "yes") {

				$registration_fees_id = get_option("smgt_registration_amount");
			} else {

				$registration_fees_id = "";
			}



			if (get_option("smgt_registration_fees") == "yes") {

				$obj_fees = new Smgt_fees();

				$registration_amount = $obj_fees->mj_smgt_get_single_feetype_data_amount($registration_fees_id);

				$generated = mj_smgt_generate_admission_fees_invoice($registration_amount, $user_id, $registration_fees_id, $class_name, 0, 'Registration Fees');
			}

			// Custom Field File Insert //

			$custom_field_file_array = array();

			if (!empty($_FILES['custom_file']['name'])) {

				$count_array = count($_FILES['custom_file']['name']);



				for ($a = 0; $a < $count_array; $a++) {

					foreach ($_FILES['custom_file'] as $image_key => $image_val) {

						foreach ($image_val as $image_key1 => $image_val2) {

							if ($_FILES['custom_file']['name'][$image_key1] != '') {

								$custom_file_array[$image_key1] = array(

									'name' => $_FILES['custom_file']['name'][$image_key1],

									'type' => $_FILES['custom_file']['type'][$image_key1],

									'tmp_name' => $_FILES['custom_file']['tmp_name'][$image_key1],

									'error' => $_FILES['custom_file']['error'][$image_key1],

									'size' => $_FILES['custom_file']['size'][$image_key1]

								);
							}
						}
					}
				}

				if (!empty($custom_file_array)) {

					foreach ($custom_file_array as $key => $value) {

						global $wpdb;

						$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';



						$get_file_name = $custom_file_array[$key]['name'];



						$custom_field_file_value = mj_smgt_load_documets_new($value, $value, $get_file_name);



						//Add File in Custom Field Meta//

						$custom_meta_data['module'] = 'student';

						$custom_meta_data['module_record_id'] = $user_id;

						$custom_meta_data['custom_fields_id'] = $key;

						$custom_meta_data['field_value'] = $custom_field_file_value;

						$custom_meta_data['created_at'] = date("Y-m-d H:i:s");

						$custom_meta_data['updated_at'] = date("Y-m-d H:i:s");



						$insert_custom_meta_data = $wpdb->insert($wpnc_custom_field_metas, $custom_meta_data);
					}
				}
			}

			$add_custom_field = $custom_field_obj->mj_smgt_add_custom_field_metas('student', $_POST['custom'], $user_id);

			$user = new WP_User($user_id);

			$user->set_role('student');

			$smgt_avatar = '';

			if ($_FILES['smgt_user_avatar']['size'] > 0) {

				$smgt_avatar_image = mj_smgt_user_avatar_image_upload('smgt_user_avatar');

				$smgt_avatar = content_url() . '/uploads/school_assets/' . $smgt_avatar_image;
			} else {

				$smgt_avatar = '';
			}

			$usermetadata = array(

				'roll_id' => '',

				'middle_name' => $middle_name,

				'gender' => $gender,

				'birth_date' => $birth_date,

				'address' => $address,

				'city' => $city_name,

				'state' => $state_name,

				'zip_code' => $zip_code,

				'class_name' => $class_name,

				'phone' => $phone,

				'mobile_number' => $mobile_number,

				'alternet_mobile_number' => $alternet_mobile_number,

				'smgt_user_avatar' => $smgt_avatar
			);

			//var_dump($usermetadata);

			foreach ($usermetadata as $key => $val) {

				$result = update_user_meta($user_id, $key, $val);
			}



			if (get_option('student_approval') == '1') {

				$hash = md5(rand(0, 1000));

				$result123 = update_user_meta($user_id, 'hash', $hash);
			}

			$class_name = get_user_meta($user_id, 'class_name', true);

			$user_info = get_userdata($user_id);

			$to = $user_info->user_email;

			$subject = get_option('registration_title');

			$search = array('{{student_name}}', '{{email_id}}', '{{class_name}}', '{{password}}', '{{school_name}}');

			$replace = array($user_info->display_name, $to, mj_smgt_get_class_name($class_name), $password, get_option('smgt_school_name'));

			$message = str_replace($search, $replace, get_option('registration_mailtemplate'));
			$school=get_option('smgt_school_name');

			$headers="";
			$headers .= 'From: '.$school.' <noreplay@gmail.com>' . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers.= 'Content-type: text/html; charset=UTF-8' . "\r\n";

			// MAIL CONTEMNT WITH TEMPLATE DESIGN
			$email_template = mj_smgt_get_mail_content_with_template_design($message);
			if ($result) {

				if (get_option('student_approval') == '1') {

					if (get_option('smgt_mail_notification') == '1') {
						

						wp_mail($to, $subject, $email_template,$headers);
					}



					$page_id = get_option('mj_smgt_install_student_registration_page');

					$referrer_ipn = array(

						'page_id' => $page_id,

						'action' => 'success_1'

					);

					$referrer_ipn = add_query_arg($referrer_ipn, home_url().'/student-registration/');

					wp_redirect($referrer_ipn);

					exit;
				} else {

					if (get_option('smgt_mail_notification') == '1') {

						wp_mail($to, $subject, $email_template,$headers);
					}



					//----------- STUDENT ASSIGNED TEACHER MAIL ------------//

					$TeacherIDs = mj_smgt_check_class_exits_in_teacher_class($class_name);

					$TeacherEmail = array();

					$string['{{school_name}}']  = get_option('smgt_school_name');

					$string['{{student_name}}'] =  $user_info->display_name;

					$subject = get_option('student_assign_teacher_mail_subject');

					$MessageContent = get_option('student_assign_teacher_mail_content');

					foreach ($TeacherIDs as $teacher) {

						$TeacherData = get_userdata($teacher);

						//$TeacherData->user_email;

						$string['{{teacher_name}}'] = mj_smgt_get_display_name($TeacherData->ID);

						$message = mj_smgt_string_replacement($string, $MessageContent);

						mj_smgt_send_mail($TeacherData->user_email, $subject, $message);
					}





					$page_id = get_option('mj_smgt_install_student_registration_page');

					$referrer_ipn = array(

						'page_id' => $page_id,

						'action' => 'success_2'

					);

					$referrer_ipn = add_query_arg($referrer_ipn, home_url());

					wp_redirect($referrer_ipn);

					exit;
				}



				return $user_id;
			}
		}
	} else {

		die('Security check');
	}
}

function mj_smgt_registration_validation($class_name, $first_name, $middle_name, $last_name, $gender, $birth_date, $address, $city_name, $state_name, $zip_code, $mobile_number, $alternet_mobile_number, $phone, $email, $username, $password, $smgt_user_avatar)

{

	global $reg_errors;

	$reg_errors = new WP_Error;



	if (empty($class_name)  || empty($first_name) || empty($last_name) || empty($birth_date) || empty($address) || empty($city_name) || empty($zip_code) || empty($mobile_number) || empty($email) || empty($username) || empty($password)) {

		$reg_errors->add('field', 'Required form field is missing');
	}

	if (4 > strlen($username)) {

		$reg_errors->add('username_length', 'Username too short. At least 4 characters is required');
	}

	if (username_exists($username))

		$reg_errors->add('user_name', 'Sorry, that username already exists!');



	if (!is_email($email)) {

		$reg_errors->add('email_invalid', 'Email is not valid');
	}

	if (email_exists($email)) {

		$reg_errors->add('email', 'Email Already in use');
	}

	if (is_wp_error($reg_errors)) {

		foreach ($reg_errors->get_error_messages() as $error) {

			echo '<div class="student_reg_error">';

			echo '<strong> ' . esc_attr__("ERROR", "school-mgt") . '</strong> : ';

			echo '<span class="error"> ' . esc_attr__("$error", "school-mgt") . ' </span><br/>';

			echo '</div>';
		}
	}
}

function smgt_student_registration_function()
{

	global $class_name, $first_name, $middle_name, $last_name, $gender, $birth_date, $address, $city_name, $state_name, $zip_code, $mobile_number, $alternet_mobile_number, $phone, $email, $username, $password, $smgt_user_avatar;

	$class_name = isset($_POST['class_name']) ? $_POST['class_name'] : '';



	if (isset($_POST['save_student_front'])) {

		mj_smgt_registration_validation(

			$_POST['class_name'],

			$_POST['first_name'],

			$_POST['middle_name'],

			$_POST['last_name'],

			$_POST['gender'],

			$_POST['birth_date'],

			$_POST['address'],

			$_POST['city_name'],

			$_POST['state_name'],

			$_POST['zip_code'],

			$_POST['mobile_number'],

			$_POST['alternet_mobile_number'],

			$_POST['phone'],

			$_POST['email'],

			$_POST['email'],

			$_POST['password'],

			isset($_FILE['smgt_user_avatar'])

		);





		// sanitize user form input

		global $class_name, $first_name, $middle_name, $last_name, $gender, $birth_date, $address, $city_name, $state_name, $zip_code, $mobile_number, $alternet_mobile_number, $phone, $email, $username, $password, $smgt_user_avatar;

		if (isset($_POST['class_name'])) {
			$class_name = $_POST['class_name'];
		} else {
			echo $class_name = "";
		}

		$first_name =    mj_smgt_strip_tags_and_stripslashes($_POST['first_name']);

		$middle_name =   mj_smgt_strip_tags_and_stripslashes($_POST['middle_name']);

		$last_name =  mj_smgt_strip_tags_and_stripslashes($_POST['last_name']);

		$gender =   mj_smgt_strip_tags_and_stripslashes($_POST['gender']);

		$birth_date =   mj_smgt_strip_tags_and_stripslashes($_POST['birth_date']);

		$address =   mj_smgt_strip_tags_and_stripslashes($_POST['address']);

		$city_name =    mj_smgt_strip_tags_and_stripslashes($_POST['city_name']);

		$state_name =   mj_smgt_strip_tags_and_stripslashes($_POST['state_name']);

		$zip_code =   mj_smgt_strip_tags_and_stripslashes($_POST['zip_code']);

		$mobile_number =   mj_smgt_strip_tags_and_stripslashes($_POST['mobile_number']);

		$alternet_mobile_number =  mj_smgt_strip_tags_and_stripslashes($_POST['alternet_mobile_number']);

		$phone =   mj_smgt_strip_tags_and_stripslashes($_POST['phone']);

		$username   =     $_POST['email'];

		$password   =    strip_tags($_POST['password']);

		$email      =    $_POST['email'];

		$wp_nonce     =   $_POST['_wpnonce'];





		// call @function complete_registration to create the user

		// only when no WP_error is found

		mj_smgt_complete_registration(

			$class_name,
			$first_name,
			$middle_name,
			$last_name,
			$gender,
			$birth_date,
			$address,
			$city_name,
			$state_name,
			$zip_code,
			$mobile_number,
			$alternet_mobile_number,
			$phone,
			$email,
			$username,
			$password,
			$smgt_user_avatar,
			$wp_nonce

		);
	}

	mj_smgt_registration_form(

		$class_name,
		$first_name,
		$middle_name,
		$last_name,
		$gender,
		$birth_date,
		$address,
		$city_name,
		$state_name,
		$zip_code,
		$mobile_number,
		$alternet_mobile_number,
		$phone,
		$email,
		$username,
		$password,
		$smgt_user_avatar

	);
}

function mj_smgt_activat_mail_link()

{

	if (isset($_REQUEST['haskey']) && isset($_REQUEST['id'])) {



		global $wpdb;

		$table_users = $wpdb->prefix . 'users';

		$user = get_userdatabylogin($_REQUEST['id']);

		$user_id =  $user->ID; // prints the id of the user

		if (get_user_meta($user_id, 'hash', true)) {



			if (get_user_meta($user_id, 'hash', true) == $_REQUEST['haskey']) {

				delete_user_meta($user_id, 'hash');

				$curr_args = array(

					'page_id' => get_option('smgt_login_page'),

					'smgt_activate' => 1

				);

				//print_r($curr_args);

				$referrer_faild = add_query_arg($curr_args, get_permalink(get_option('smgt_login_page')));

				wp_redirect($referrer_faild);

				exit;
			} else {

				$curr_args = array(

					'page_id' => get_option('smgt_login_page'),

					'smgt_activate' => 2

				);

				//print_r($curr_args);

				$referrer_faild = add_query_arg($curr_args, get_permalink(get_option('smgt_login_page')));

				wp_redirect($referrer_faild);

				exit;
			}
		}

		wp_redirect(home_url('/'));

		exit;
	}
}

//add user authenticate filter

add_filter('wp_authenticate_user', function ($user) {

	$havemeta = get_user_meta($user->ID, 'hash', true);

	if ($havemeta) {

		$WP_Error = new WP_Error();

		$referrer = $_SERVER['HTTP_REFERER'];

		$curr_args = array(

			'page_id' => get_option('smgt_login_page'),

			'smgt_activate' => 'smgt_activate'

		);

		$referrer_faild = add_query_arg($curr_args, get_permalink(get_option('smgt_login_page')));

		wp_redirect($referrer_faild);

		exit();
	}

	return $user;
}, 10, 2);



add_action('wp_enqueue_scripts', 'mj_smgt_load_script1');

add_action('init', 'mj_smgt_install_login_page');

add_action('init', 'mj_smgt_install_student_registration_page');

add_action('init', 'mj_smgt_install_student_admission_page');

add_action('wp_head', 'mj_smgt_user_dashboard');

add_shortcode('smgt_login', 'mj_smgt_login_link');



add_action('wp_login', 'mj_smgt_student_login', 10, 2);



add_action('init', 'mj_smgt_output_ob_start');

// Register a new shortcode: [cr_custom_registration]

add_shortcode('smgt_student_registration', 'mj_smgt_custom_registration_shortcode');

add_shortcode('smgt_student_admission', 'mj_smgt_custom_admission_shortcode');

// The callback function that will replace [book]

function mj_smgt_custom_registration_shortcode()
{

	ob_start();

	smgt_student_registration_function();

	return ob_get_clean();
}

function mj_smgt_custom_admission_shortcode()
{

	ob_start();

	smgt_student_admisiion_function();

	return ob_get_clean();
}

function mj_smgt_output_ob_start()

{

	ob_start();
}



add_action('init', 'mj_smgt_generate_pdf');

function mj_smgt_generate_pdf()

{

	if (isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf' && isset($_REQUEST['student'])) {

		ob_start();

		$obj_mark = new Marks_Manage();

		$uid = $_REQUEST['student'];



		$user = get_userdata($uid);



		$user_meta = get_user_meta($uid);

		$class_id = $user_meta['class_name'][0];

		$section_id = $user_meta['class_section'][0];


		$subject = $obj_mark->mj_smgt_student_subject_list($class_id, $section_id);

		$total_subject = count($subject);

		$exam_id = $_REQUEST['exam_id'];

		$total = 0;

		$grade_point = 0;

		$umetadata = mj_smgt_get_user_image($uid);

		error_reporting(1);

		if(is_rtl())
		{
		?>
		<div class="container" style="margin-bottom:8px;">

				<div style="border: 2px solid;">

					<div style="padding:20px;">

						<div style="float:right;width:100%; ">

							<div style="float:right;width:25%;">

								<div class="asasa" style="float:right;border-radius:50px;">

									<div style="width: 150px;background-image: url('<?php echo get_option('smgt_school_logo') ?>');height: 150px;border-radius: 50%;background-repeat:no-repeat;background-size:cover;"></div>

								</div>

							</div>

							<div style="float:right; width:55%;font-size:24px;padding-top:50px;">

								<b style="color:#307994;align-item:center;"><?php echo get_option('smgt_school_name'); ?></b>

							</div>

							<div style="float:right;width:15%;padding-top:55px;">

								<?php

								$term_id = $obj_mark->mj_smgt_get_exam_term($exam_id);

								?>

								<b> <?php echo get_the_title($term_id); ?> <?php esc_attr_e('Term Exam Result', 'school-mgt'); ?></b>

							</div>

						</div>

					</div>

				</div>

			</div>
			<div style="border: 2px solid;background-color:#f5c6cc;margin-bottom:8px;">

				<div style="float:right;width:100%;">

					<div class="123" style="padding:10px;">

						<div style="float:right;width:33%;"><?php esc_attr_e('Student Name', 'school-mgt'); ?>: <b><?php echo get_user_meta($uid, 'first_name', true); ?>&nbsp;<?php echo get_user_meta($uid, 'last_name', true); ?></div>

						<div style="float:right;width:33%;"><?php esc_attr_e("Father's Name", "school-mgt"); ?>: <b><?php

							$parent_id = get_user_meta($uid, 'parent_id', true);

							if (!empty($parent_id)) {

								foreach ($parent_id as $id) {

									$parentinfo = get_userdata($id);
								}

								echo  $parentinfo->display_name;
							} else {

								echo "N/A";
							}

							?> </b></div>

						<div style="float:right;width:33%;"><?php esc_attr_e("Roll No", "school-mgt"); ?>:

							<b><?php echo get_user_meta($uid, 'roll_id', true); ?> </b>
						</div>

					</div>

				</div>

				<div style="float:right;width:100%;">

					<div class="123" style="padding:10px;">

						<div style="float:right;width:33%;"><?php esc_attr_e('Class', 'school-mgt'); ?>: <b><?php $class_id = get_user_meta($uid, 'class_name', true);

																											echo $classname = mj_smgt_get_class_name($class_id); ?></b></div>

						<div style="float:right;width:33%;"><?php esc_attr_e('Section', 'school-mgt'); ?>:

							<b><?php

								$section_name = get_user_meta($uid, 'class_section', true);

								if ($section_name != "") {

									echo mj_smgt_get_section_name($section_name);
								} else {

									esc_attr_e('No Section', 'school-mgt');;
								}

								?></b>
						</div>

						<div style="float:right;width:33%;"><?php esc_attr_e('Exam Name', 'school-mgt'); ?>:

							<b><?php echo mj_smgt_get_exam_name_id($exam_id); ?>

							</b>
						</div>

					</div>

				</div>

			</div>
			<table style="float:right;width:100%;border:1px solid #000;margin-bottom:8px;" cellpadding="10" cellspacing="0">

				<thead>

					<tr style="border-bottom: 1px solid #000;background-color:#b8daff;">

						<th style="border-bottom: 1px solid #000;text-align:right;border-right: 1px solid #000;"><?php esc_attr_e('Subject', 'school-mgt') ?></th>

						<th style="border-bottom: 1px solid #000;text-align:right;border-right: 1px solid #000;"><?php esc_attr_e('Max Marks', 'school-mgt') ?></th>

						<th style="border-bottom: 1px solid #000;text-align:right;border-right: 1px solid #000;"><?php esc_attr_e('Pass Marks', 'school-mgt') ?></th>

						<th style="border-bottom: 1px solid #000;text-align:right;border-right: 1px solid #000;"><?php esc_attr_e('Obtain Mark', 'school-mgt') ?></th>

						<th style="border-bottom: 1px solid #000;text-align:right;border-right: 1px solid #000;"><?php esc_attr_e('Grade', 'school-mgt') ?></th>

					</tr>

				</thead>

				<tbody>

					<?php

					$i = 1;

					$total_pass_mark = 0;

					$total_max_mark = 0;

					foreach ($subject as $sub) {

						$total_pass_mark += $obj_mark->mj_smgt_get_pass_marks($exam_id);

					?>

						<tr style="border-bottom: 1px solid #000;">

							<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $sub->sub_name; ?></td>

							<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_max_marks($exam_id); ?> </td>

							<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_pass_marks($exam_id); ?></td>

							<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_marks($exam_id, $class_id, $sub->subid, $uid); ?></td>

							<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_grade($exam_id, $class_id, $sub->subid, $uid); ?></td>

						</tr>

					<?php

						$i++;

						$total +=  $obj_mark->mj_smgt_get_marks($exam_id, $class_id, $sub->subid, $uid);

						$total_max_mark += $obj_mark->mj_smgt_get_max_marks($exam_id);
					}

					?>

				</tbody>

				<tfoot>

					<tr style="border-bottom: 1px solid #000;background-color:#b8daff;">

						<th><?php esc_attr_e('TOTAL MARKS', 'school-mgt') ?></th>

						<th><?php



							if (!empty($total_max_mark)) {

								echo $total_max_mark;
							} else {

								echo "-";
							}

							?></th>

						<th><?php

							if (!empty($total_pass_mark)) {

								echo $total_pass_mark;
							} else {

								echo "-";
							}

							?></th>

						<th><?php

							if (!empty($total)) {

								echo $total;
							} else {

								echo "-";
							}

							?></th>

						<th></th>

					</tr>

				</tfoot>

			</table>
			<div style="border: 2px solid #8b8b8b;background-color:#eacf80;width:100%;float: right;margin-bottom:8px;">

				<div class="row" style="">

					<div style="float:right;width: 60%;margin: 10px;">

						<b class="" style="text-align: right"><?php esc_attr_e('Percentage', 'school-mgt'); ?> : </b>

						<?php



						$percentage = $total / $total_max_mark * 100;



						if (!empty($percentage)) {

							echo number_format($percentage, 2);
						} else {

							echo "-";
						}

						?>

					</div>

					<div style="float:right;width: 20%;margin: 0px;">

						<b style="text-align: right;"><?php esc_attr_e('Result', 'school-mgt'); ?> : </b>

						<?php

						$result = array();

						$result1 = array();

						foreach ($subject as $sub) {



							if ($obj_mark->mj_smgt_get_marks($exam_id, $class_id, $sub->subid, $uid) >= $obj_mark->mj_smgt_get_pass_marks($exam_id)) {

								$result[] = "pass";
							} else {

								$result1[] = "fail";
							}
						}



						if (isset($result) && in_array("pass", $result) && isset($result1) && in_array("fail", $result1)) {

							echo  esc_attr_e('Fail', 'school-mgt');
						} elseif (isset($result) && in_array("pass", $result)) {

							echo  esc_attr_e('Pass', 'school-mgt');
						} elseif (isset($result1) && in_array("fail", $result1)) {

							echo  esc_attr_e('Fail', 'school-mgt');
						} else {

							echo "-";
						}

						?>

					</div>

				</div>

				<hr>

				<div class="aaa" style="direction: rtl;margin-right: 20px;">

					<br>

					<div style="float:right;margin-right:0px;margin-right: auto;">

						<div>

							<img src="<?php echo get_option('smgt_principal_signature') ?>" style="width:100px; margin-right:15px;" />

						</div>

						<div style="border: 1px solid  !important;width: 150px;margin-top: 5px;"></div>

						<div style="margin-right:10px;margin-bottom:10px;">

							<?php esc_attr_e('Principal Signature', 'school-mgt'); ?>

						</div>

					</div>

				</div>

			</div>
		<?php
		}
		else
		{
			?>
			<div class="container" style="margin-bottom:8px;">

				<div style="border: 2px solid;">

					<div style="padding:20px;">

						<div style="float:left;width:100%; ">

							<div style="float:left;width:25%;">

								<div class="asasa" style="float:letf;border-radius:50px;">

									<div style="width: 150px;background-image: url('<?php echo get_option('smgt_school_logo') ?>');height: 150px;border-radius: 50%;background-repeat:no-repeat;background-size:cover;"></div>

								</div>

							</div>

							<div style="float:left; width:55%;font-size:24px;padding-top:50px;">

								<b style="color:#307994;align-item:center;"><?php echo get_option('smgt_school_name'); ?></b>

							</div>

							<div style="float:left;width:15%;padding-top:55px;">

								<?php

								$term_id = $obj_mark->mj_smgt_get_exam_term($exam_id);

								?>

								<b> <?php echo get_the_title($term_id); ?> <?php esc_attr_e('Term Exam Result', 'school-mgt'); ?></b>

							</div>

						</div>

					</div>

				</div>

			</div>

		<div style="border: 2px solid;background-color:#f5c6cc;margin-bottom:8px;">

			<div style="float:left;width:100%;">

				<div class="123" style="padding:10px;">

					<div style="float:left;width:33%;"><?php esc_attr_e('Student Name', 'school-mgt'); ?>: <b><?php echo get_user_meta($uid, 'first_name', true); ?>&nbsp;<?php echo get_user_meta($uid, 'last_name', true); ?></div>

					<div style="float:left;width:33%;"><?php esc_attr_e("Father's Name", "school-mgt"); ?>: <b><?php

						$parent_id = get_user_meta($uid, 'parent_id', true);



						if (!empty($parent_id)) {

							foreach ($parent_id as $id) {

								$parentinfo = get_userdata($id);
							}

							echo  $parentinfo->display_name;
						} else {

							echo "N/A";
						}

						?> </b></div>

					<div style="float:left;width:33%;"><?php esc_attr_e("Roll No", "school-mgt"); ?>:

						<b><?php echo get_user_meta($uid, 'roll_id', true); ?> </b>
					</div>

				</div>

			</div>



			<div style="float:left;width:100%;">

				<div class="123" style="padding:10px;">

					<div style="float:left;width:33%;"><?php esc_attr_e('Class', 'school-mgt'); ?>: <b><?php $class_id = get_user_meta($uid, 'class_name', true);

																										echo $classname = mj_smgt_get_class_name($class_id); ?></b></div>

					<div style="float:left;width:33%;"><?php esc_attr_e('Section', 'school-mgt'); ?>:

						<b><?php

							$section_name = get_user_meta($uid, 'class_section', true);

							if ($section_name != "") {

								echo mj_smgt_get_section_name($section_name);
							} else {

								esc_attr_e('No Section', 'school-mgt');;
							}

							?></b>
					</div>

					<div style="float:left;width:33%;"><?php esc_attr_e('Exam Name', 'school-mgt'); ?>:

						<b><?php echo mj_smgt_get_exam_name_id($exam_id); ?>

						</b>
					</div>

				</div>

			</div>



		</div>

		<table style="float:left;width:100%;border:1px solid #000;margin-bottom:8px;" cellpadding="10" cellspacing="0">

			<thead>

				<tr style="border-bottom: 1px solid #000;background-color:#b8daff;">

					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Subject', 'school-mgt') ?></th>

					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Max Marks', 'school-mgt') ?></th>

					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Pass Marks', 'school-mgt') ?></th>

					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Obtain Mark', 'school-mgt') ?></th>

					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Grade', 'school-mgt') ?></th>

				</tr>

			</thead>

			<tbody>

				<?php

				$i = 1;

				$total_pass_mark = 0;

				$total_max_mark = 0;

				foreach ($subject as $sub) {

					$total_pass_mark += $obj_mark->mj_smgt_get_pass_marks($exam_id);

				?>

					<tr style="border-bottom: 1px solid #000;">

						<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $sub->sub_name; ?></td>

						<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_max_marks($exam_id); ?> </td>

						<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_pass_marks($exam_id); ?></td>

						<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_marks($exam_id, $class_id, $sub->subid, $uid); ?></td>

						<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_grade($exam_id, $class_id, $sub->subid, $uid); ?></td>

					</tr>

				<?php

					$i++;

					$total +=  $obj_mark->mj_smgt_get_marks($exam_id, $class_id, $sub->subid, $uid);

					$total_max_mark += $obj_mark->mj_smgt_get_max_marks($exam_id);
				}

				?>

			</tbody>

			<tfoot>

				<tr style="border-bottom: 1px solid #000;background-color:#b8daff;">

					<th><?php esc_attr_e('TOTAL MARKS', 'school-mgt') ?></th>

					<th><?php



						if (!empty($total_max_mark)) {

							echo $total_max_mark;
						} else {

							echo "-";
						}

						?></th>

					<th><?php

						if (!empty($total_pass_mark)) {

							echo $total_pass_mark;
						} else {

							echo "-";
						}

						?></th>

					<th><?php

						if (!empty($total)) {

							echo $total;
						} else {

							echo "-";
						}

						?></th>

					<th></th>

				</tr>

			</tfoot>

		</table>



		<div style="border: 2px solid #8b8b8b;background-color:#eacf80;width:100%;float: left;margin-bottom:8px;">

			<div class="row" style="">

				<div style="float:left;width: 60%;margin: 10px;">

					<b class="" style="text-align: left"><?php esc_attr_e('Percentage', 'school-mgt'); ?> : </b>

					<?php



					$percentage = $total / $total_max_mark * 100;



					if (!empty($percentage)) {

						echo number_format($percentage, 2);
					} else {

						echo "-";
					}

					?>

				</div>

				<div style="float:right;width: 20%;margin: 0px;">

					<b style="text-align: right;"><?php esc_attr_e('Result', 'school-mgt'); ?> : </b>

					<?php

					$result = array();

					$result1 = array();

					foreach ($subject as $sub) {



						if ($obj_mark->mj_smgt_get_marks($exam_id, $class_id, $sub->subid, $uid) >= $obj_mark->mj_smgt_get_pass_marks($exam_id)) {

							$result[] = "pass";
						} else {

							$result1[] = "fail";
						}
					}



					if (isset($result) && in_array("pass", $result) && isset($result1) && in_array("fail", $result1)) {

						echo  esc_attr_e('Fail', 'school-mgt');
					} elseif (isset($result) && in_array("pass", $result)) {

						echo  esc_attr_e('Pass', 'school-mgt');
					} elseif (isset($result1) && in_array("fail", $result1)) {

						echo  esc_attr_e('Fail', 'school-mgt');
					} else {

						echo "-";
					}

					?>

				</div>

			</div>

			<hr>

			<div class="aaa" style="direction: rtl;margin-right: 20px;">

				<br>

				<div style="float:right;margin-right:0px;margin-left: auto;">

					<div>

						<img src="<?php echo get_option('smgt_principal_signature') ?>" style="width:100px; margin-right:15px;" />

					</div>

					<div style="border: 1px solid  !important;width: 150px;margin-top: 5px;"></div>

					<div style="margin-right:10px;margin-bottom:10px;">

						<?php esc_attr_e('Principal Signature', 'school-mgt'); ?>

					</div>

				</div>

			</div>

		</div>
			<?php
		}
	?>

		

		<?php



		$out_put = ob_get_contents();



		echo '<link rel="stylesheet" href="' . plugins_url('/assets/css/bootstrap_min.css', __FILE__) . '"></link>';

		echo '<script  rel="javascript" src="' . plugins_url('/assets/js/bootstrap_min.js', __FILE__) . '"></script>';

		ob_clean();

		header('Content-type: application/pdf');

		header('Content-Disposition: inline; filename="result"');

		header('Content-Transfer-Encoding: binary');

		header('Accept-Ranges: bytes');

		require_once SMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';

		$stylesheet1 = file_get_contents(SMS_PLUGIN_DIR . '/assets/css/style.css'); // Get css content



		$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [250, 236]]);

		$mpdf->SetTitle('Result');

		$mpdf->SetDisplayMode('fullwidth');

		$mpdf->autoScriptToLang = true;

		$mpdf->autoLangToFont = true;



		if (is_rtl()) {

			$mpdf->autoScriptToLang = true;

			$mpdf->autoLangToFont = true;

			$mpdf->SetDirectionality('rtl');
		}

		$mpdf->WriteHTML($stylesheet1, 1); // Writing style to pdf

		$mpdf->WriteHTML($out_put);

		$mpdf->Output();

		unset($out_put);

		unset($mpdf);

		exit;
	}

	if (isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf' && isset($_REQUEST['invoice_type'])) {

		// error_reporting(0);

		mj_smgt_student_invoice_pdf($_REQUEST['invoice_id']);

		$out_put = ob_get_contents();

		ob_clean();

		header('Content-type: application/pdf');

		header('Content-Disposition: inline; filename="result"');

		header('Content-Transfer-Encoding: binary');

		header('Accept-Ranges: bytes');



		require_once SMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';

		$mpdf = new Mpdf\Mpdf;

		$mpdf->SetTitle('Payment');

		$mpdf->autoScriptToLang = true;

		$mpdf->autoLangToFont = true;



		if (is_rtl()) {

			$mpdf->autoScriptToLang = true;

			$mpdf->autoLangToFont = true;

			$mpdf->SetDirectionality('rtl');
		}



		$mpdf->WriteHTML($out_put);

		$mpdf->Output();

		unset($out_put);

		unset($mpdf);

		exit;
	}

	if (isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf' && isset($_REQUEST['fee_paymenthistory'])) {

		// error_reporting(0);
		?>
		<style>
			.currency_symbol {
				font-family: Arial, sans-serif;
			}
		</style>
	<?php

		mj_smgt_student_paymenthistory_pdf($_REQUEST['payment_id']);

		$out_put = ob_get_contents();

		ob_clean();

		header('Content-type: application/pdf');

		header('Content-Disposition: inline; filename="feepaymenthistory"');

		header('Content-Transfer-Encoding: binary');

		header('Accept-Ranges: bytes');

		require_once SMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';

		$mpdf = new Mpdf\Mpdf;

		$mpdf->SetTitle('Fees Payment');

		$mpdf->autoScriptToLang = true;

		$mpdf->autoLangToFont = true;



		if (is_rtl()) {

			$mpdf->autoScriptToLang = true;

			$mpdf->autoLangToFont = true;

			$mpdf->SetDirectionality('rtl');
		}



		$mpdf->WriteHTML($out_put);

		$mpdf->Output();

		unset($out_put);

		unset($mpdf);

		exit;
	}

	if (isset($_REQUEST['student_exam_receipt_pdf']) && $_REQUEST['student_exam_receipt_pdf'] == 'student_exam_receipt_pdf') {

		// error_reporting(0);

		mj_smgt_student_exam_receipt_pdf($_REQUEST['student_id'], $_REQUEST['exam_id']);

		$out_put = ob_get_contents();

		ob_clean();

		header('Content-type: application/pdf');

		header('Content-Disposition: inline; filename="examreceipt"');

		header('Content-Transfer-Encoding: binary');

		header('Accept-Ranges: bytes');

		require_once SMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';

		$mpdf = new Mpdf\Mpdf;

		$mpdf->SetTitle('Hall Ticket');

		$mpdf->autoScriptToLang = true;

		$mpdf->autoLangToFont = true;

		// if (is_rtl()) {

		// 	$mpdf->autoScriptToLang = true;

		// 	$mpdf->autoLangToFont = true;

		// 	$mpdf->SetDirectionality('rtl');
		// }



		$mpdf->WriteHTML($out_put);

		$mpdf->Output();

		unset($out_put);

		unset($mpdf);

		exit;
	}
}



/**

 * Authenticate a user, confirming the username and password are valid.

 *

 * @since 2.8.0

 *

 * @param WP_User|WP_Error|null $user     WP_User or WP_Error object from a previous callback. Default null.

 * @param string                $username Username for authentication.

 * @param string                $password Password for authentication.

 * @return WP_User|WP_Error WP_User on success, WP_Error on failure.

 */

//add_filter( 'authenticate', 'wp_authenticate_username_password_new', 20, 3 );



function mj_smgt_wp_authenticate_username_password_new($user, $username, $password)

{



	if ($user instanceof WP_User) {

		return $user;
	}



	if (empty($username) || empty($password)) {

		if (is_wp_error($user)) {

			return $user;
		}



		$error = new WP_Error();



		if (empty($username)) {

			$error->add('empty_username', esc_attr__('<strong>ERROR</strong>: The username field is empty.'));
		}



		if (empty($password)) {

			$error->add('empty_password', esc_attr__('<strong>ERROR</strong>: The password field is empty.'));
		}



		return $error;
	}



	$user = get_user_by('login', $username);



	/**

	 * Filters whether the given user can be authenticated with the provided $password.

	 *

	 * @since 2.5.0

	 *

	 * @param WP_User|WP_Error $user     WP_User or WP_Error object if a previous

	 *                                   callback failed authentication.

	 * @param string           $password Password to check against the user.

	 */

	$user = apply_filters('wp_authenticate_user', $user, $password);

	if (is_wp_error($user)) {

		return $user;
	}



	return $user;
}



add_filter('auth_cookie_expiration', 'mj_smgt_keep_me_logged_in_60_minutes');

function mj_smgt_keep_me_logged_in_60_minutes($expirein)
{

	return 7200; // 1 hours

}



//Auto Fill Feature is Enabled  wp login page//

add_action('login_form', function ($args) {



	$login = ob_get_contents();



	ob_clean();

	$login = str_replace('id="user_pass"', 'id="user_pass" autocomplete="off"', $login);

	$login = str_replace('id="user_login"', 'id="user_login" autocomplete="off"', $login);

	echo $login;
}, 9999);



// Wordpress User Information Dislclosure//

//Remove for page and ad edit post issue//



////X-Frame-Options Header Not Set//

function mj_smgt_block_frames()
{

	header('X-FRAME-OPTIONS: SAMEORIGIN');
}

add_action('send_headers', 'mj_smgt_block_frames', 10);

// add_action( 'send_headers', 'send_frame_options_header', 10, 0 );





if (!empty($_SERVER['HTTPS'])) {

	function mj_smgt_add_hsts_header($headers)
	{

		$headers['strict-transport-security'] = 'max-age=31536000; includeSubDomains';

		return $headers;
	}

	add_filter('wp_headers', 'mj_smgt_add_hsts_header');
}

//------------- STUDENT ADMISSION PAGE --------------//

function mj_smgt_install_student_admission_page()

{

	if (!get_option('smgt_student_admission_page')) {

		$curr_page = array(

			'post_title' => esc_attr__('Student Admission', 'school-mgt'),

			'post_content' => '[smgt_student_admission]',

			'post_status' => 'publish',

			'post_type' => 'page',

			'comment_status' => 'closed',

			'ping_status' => 'closed',

			'post_category' => array(1),

			'post_parent' => 0
		);



		$curr_created = wp_insert_post($curr_page);

		update_option('smgt_student_admission_page', $curr_created);
	}
}



function smgt_student_admisiion_function()

{

	global $admission_no, $admission_date, $first_name, $middle_name, $last_name, $birth_date, $gender, $address, $state_name, $city_name, $zip_code, $phone_code, $mobile_number, $alternet_mobile_number, $email, $username, $password, $preschool_name, $smgt_user_avatar, $sibling_information, $p_status, $fathersalutation, $father_first_name, $father_middle_name, $father_last_name, $fathe_gender, $father_birth_date, $father_address, $father_city_name, $father_state_name, $father_zip_code, $father_email, $father_mobile, $father_school, $father_medium, $father_education, $fathe_income, $father_occuption, $father_doc, $mothersalutation, $mother_first_name, $mother_middle_name, $mother_last_name, $mother_gender, $mother_birth_date, $mother_address, $mother_city_name, $mother_state_name, $mother_zip_code, $mother_email, $mother_mobile, $mother_school, $mother_medium, $mother_education, $mother_income, $mother_occuption, $mother_doc, $admission_fees;



	if (isset($_POST['save_student_front_admission'])) {



		mj_smgt_admission_validation(

			$_POST['email'],

			$_POST['email'],

			$_POST['father_email'],

			$_POST['mother_email']

		);

		// sanitize user form input

		global $admission_no, $admission_date, $first_name, $middle_name, $last_name, $birth_date, $gender, $address, $state_name, $city_name, $zip_code, $phone_code, $mobile_number, $alternet_mobile_number, $email, $username, $password, $preschool_name, $smgt_user_avatar, $sibling_information, $p_status, $fathersalutation, $father_first_name, $father_middle_name, $father_last_name, $fathe_gender, $father_birth_date, $father_address, $father_city_name, $father_state_name, $father_zip_code, $father_email, $father_mobile, $father_school, $father_medium, $father_education, $fathe_income, $father_occuption, $father_doc, $mothersalutation, $mother_first_name, $mother_middle_name, $mother_last_name, $mother_gender, $mother_birth_date, $mother_address, $mother_city_name, $mother_state_name, $mother_zip_code, $mother_email, $mother_mobile, $mother_school, $mother_medium, $mother_education, $mother_income, $mother_occuption, $mother_doc, $admission_fees;

		$sibling_value = array();



		if (isset($_FILES['father_doc']) && !empty($_FILES['father_doc']) && $_FILES['father_doc']['size'] != 0) {

			if ($_FILES['father_doc']['size'] > 0)

				$upload_docs = mj_smgt_load_documets_new($_FILES['father_doc'], $_FILES['father_doc'], $_POST['father_document_name']);
		} else {

			$upload_docs = '';
		}



		$father_document_data = array();

		if (!empty($upload_docs)) {

			$father_document_data[] = array('title' => $_POST['father_document_name'], 'value' => $upload_docs);
		} else {

			$father_document_data[] = '';
		}



		if (isset($_FILES['mother_doc']) && !empty($_FILES['mother_doc']) && $_FILES['mother_doc']['size'] != 0) {

			if ($_FILES['mother_doc']['size'] > 0)

				$upload_docs1 = mj_smgt_load_documets_new($_FILES['mother_doc'], $_FILES['mother_doc'], 'mother_doc');
		} else {

			$upload_docs1 = '';
		}

		$mother_document_data = array();

		if (!empty($upload_docs1)) {

			$mother_document_data[] = array('title' => $_POST['mother_document_name'], 'value' => $upload_docs1);
		} else {

			$mother_document_data[] = '';
		}

		if (isset($_POST['smgt_user_avatar']) && $_POST['smgt_user_avatar'] != "") {

			$photo	=	$_POST['smgt_user_avatar'];
		} else {

			$photo	=	"";
		}

		if ($_POST['password'] != "") {

			$user_pass = mj_smgt_password_validation($_POST['password']);
		} else {

			$user_pass = wp_generate_password();
		}

		// if(!empty($_POST['siblingsname']))

		// {

		// 	foreach($_POST['siblingsname'] as $key=>$value)

		// 	{

		// 		$sibling_value[]=array("siblinggender" => $_POST['siblinggender'][$key],"siblingsname" => mj_smgt_onlyLetter_specialcharacter_validation($value), "siblingage" =>$_POST['siblingage'][$key],"sibling_standard" => $_POST['sibling_standard'][$key], "siblingsid" => $_POST['siblingsid'][$key]);

		// 	}

		// }

		$sibling_value = array();

		if (!empty($data['siblingsclass'])) {

			foreach ($data['siblingsclass'] as $key => $value) {

				$sibling_value[] = array("siblingsclass" => $value, "siblingssection" => $data['siblingssection'][$key], "siblingsstudent" => $data['siblingsstudent'][$key]);
			}
		}

		$admission_no =	mj_smgt_address_description_validation($_POST['admission_no']);

		$admission_date = $_POST['admission_date'];

		$first_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);

		$middle_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']);

		$last_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['last_name']);

		$birth_date = $_POST['birth_date'];

		$gender = mj_smgt_onlyLetterSp_validation($_POST['gender']);

		$address = mj_smgt_address_description_validation($_POST['address']);

		$state_name = mj_smgt_city_state_country_validation($_POST['state_name']);

		$city_name = mj_smgt_city_state_country_validation($_POST['city_name']);

		$zip_code = mj_smgt_onlyLetterNumber_validation($_POST['zip_code']);

		$phone_code = $_POST['phone_code'];

		$mobile_number = mj_smgt_phone_number_validation($_POST['mobile_number']);

		//$alternet_mobile_number=mj_smgt_phone_number_validation($_POST['alternet_mobile_number']);

		$email = mj_smgt_username_validation($_POST['email']);

		$username = mj_smgt_username_validation($_POST['email']);

		$password = $user_pass;

		$preschool_name = $_POST['preschool_name'];

		$smgt_user_avatar = $photo;

		$sibling_information = $sibling_value;

		$p_status = $_POST['pstatus'];

		$fathersalutation = mj_smgt_onlyLetter_specialcharacter_validation($_POST['fathersalutation']);

		$father_first_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['father_first_name']);

		$father_middle_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['father_middle_name']);

		$father_last_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['father_last_name']);

		$fathe_gender = $_POST['fathe_gender'];

		$father_birth_date = $_POST['father_birth_date'];

		$father_address = mj_smgt_address_description_validation($_POST['father_address']);

		$father_city_name = mj_smgt_city_state_country_validation($_POST['father_city_name']);

		$father_state_name = mj_smgt_city_state_country_validation($_POST['father_state_name']);

		$father_zip_code = mj_smgt_onlyLetterNumber_validation($_POST['father_zip_code']);

		$father_email = mj_smgt_email_validation($_POST['father_email']);

		$father_mobile =	mj_smgt_phone_number_validation($_POST['father_mobile']);

		$father_school = mj_smgt_onlyLetter_specialcharacter_validation($_POST['father_school']);

		$father_medium = $_POST['father_medium'];

		$father_education = $_POST['father_education'];

		$fathe_income = $_POST['fathe_income'];

		$father_occuption = $_POST['father_occuption'];

		$father_doc = json_encode($father_document_data);

		$mothersalutation =	mj_smgt_onlyLetter_specialcharacter_validation($_POST['mothersalutation']);

		$mother_first_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['mother_first_name']);

		$mother_middle_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['mother_middle_name']);

		$mother_last_name = mj_smgt_onlyLetter_specialcharacter_validation($_POST['mother_last_name']);

		$mother_gender = $_POST['mother_gender'];

		$mother_birth_date = $_POST['mother_birth_date'];

		$mother_address = mj_smgt_address_description_validation($_POST['mother_address']);

		$mother_city_name = mj_smgt_city_state_country_validation($_POST['mother_city_name']);

		$mother_state_name = mj_smgt_city_state_country_validation($_POST['mother_state_name']);

		$mother_zip_code = mj_smgt_onlyLetterNumber_validation($_POST['mother_zip_code']);

		$mother_email = mj_smgt_email_validation($_POST['mother_email']);

		$mother_mobile = mj_smgt_phone_number_validation($_POST['mother_mobile']);

		$mother_school = mj_smgt_onlyLetter_specialcharacter_validation($_POST['mother_school']);

		$mother_medium = $_POST['mother_medium'];

		$mother_education = $_POST['mother_education'];

		$mother_income = $_POST['mother_income'];

		$mother_occuption = $_POST['mother_occuption'];

		$mother_doc = json_encode($mother_document_data);

		$wp_nonce     =   $_POST['_wpnonce'];

		$admission_fees     =   $_POST['admission_fees'];





		// call @function smgt_complete_admission to create the user

		// only when no WP_error is found

		mj_smgt_complete_admission($admission_no, $admission_date, $first_name, $middle_name, $last_name, $birth_date, $gender, $address, $state_name, $city_name, $zip_code, $phone_code, $mobile_number, $alternet_mobile_number, $email, $username, $password, $preschool_name, $smgt_user_avatar, $sibling_information, $p_status, $fathersalutation, $father_first_name, $father_middle_name, $father_last_name, $fathe_gender, $father_birth_date, $father_address, $father_city_name, $father_state_name, $father_zip_code, $father_email, $father_mobile, $father_school, $father_medium, $father_education, $fathe_income, $father_occuption, $father_doc, $mothersalutation, $mother_first_name, $mother_middle_name, $mother_last_name, $mother_gender, $mother_birth_date, $mother_address, $mother_city_name, $mother_state_name, $mother_zip_code, $mother_email, $mother_mobile, $mother_school, $mother_medium, $mother_education, $mother_income, $mother_occuption, $mother_doc, $wp_nonce, $admission_fees);
	}

	mj_smgt_admission_form($admission_no, $admission_date, $first_name, $middle_name, $last_name, $birth_date, $gender, $address, $state_name, $city_name, $zip_code, $phone_code, $mobile_number, $alternet_mobile_number, $email, $username, $password, $preschool_name, $smgt_user_avatar, $sibling_information, $p_status, $fathersalutation, $father_first_name, $father_middle_name, $father_last_name, $fathe_gender, $father_birth_date, $father_address, $father_city_name, $father_state_name, $father_zip_code, $father_email, $father_mobile, $father_school, $father_medium, $father_education, $fathe_income, $father_occuption, $father_doc, $mothersalutation, $mother_first_name, $mother_middle_name, $mother_last_name, $mother_gender, $mother_birth_date, $mother_address, $mother_city_name, $mother_state_name, $mother_zip_code, $mother_email, $mother_mobile, $mother_school, $mother_medium, $mother_education, $mother_income, $mother_occuption, $mother_doc, $admission_fees);
}



function mj_smgt_admission_form($admission_no, $admission_date, $first_name, $middle_name, $last_name, $birth_date, $gender, $address, $state_name, $city_name, $zip_code, $phone_code, $mobile_number, $alternet_mobile_number, $email, $username, $password, $preschool_name, $smgt_user_avatar, $sibling_information, $p_status, $fathersalutation, $father_first_name, $father_middle_name, $father_last_name, $fathe_gender, $father_birth_date, $father_address, $father_city_name, $father_state_name, $father_zip_code, $father_email, $father_mobile, $father_school, $father_medium, $father_education, $fathe_income, $father_occuption, $father_doc, $mothersalutation, $mother_first_name, $mother_middle_name, $mother_last_name, $mother_gender, $mother_birth_date, $mother_address, $mother_city_name, $mother_state_name, $mother_zip_code, $mother_email, $mother_mobile, $mother_school, $mother_medium, $mother_education, $mother_income, $mother_occuption, $mother_doc, $admission_fees)

{

	//-------------- MATERIAL DESIGN ---------------//

	wp_enqueue_style('smgt-bootstrap-inputs', plugins_url('/assets/css/material/bootstrap-inputs.css', __FILE__));

	wp_enqueue_script('smgt-material-min-js', plugins_url('/assets/js/material/material.min.js', __FILE__));

	//-------------- MATERIAL DESIGN ---------------//

	wp_enqueue_style('timepicker-min-css', plugins_url('/assets/css/Bootstrap/bootstrap-timepicker.min.css', __FILE__));

	wp_enqueue_script('smgt-defaultscript', plugins_url('/assets/js/Jquery/jquery-3.6.0.min.js', __FILE__), array('jquery'), '4.1.1', true);

	wp_enqueue_script('smgt-bootstrap-js', plugins_url('/assets/js/Bootstrap/bootstrap5.min.js', __FILE__));

	$lancode = get_locale();

	$code = substr($lancode, 0, 2);

	wp_enqueue_style('wcwm-validate-css', plugins_url('/lib/validationEngine/css/validationEngine.jquery.css', __FILE__));

	wp_register_script('jquery-3.6.0', plugins_url('/lib/validationEngine/js/jquery-3.6.0.min.js', __FILE__), array('jquery'));

	wp_enqueue_script('jquery-3.6.0');

	wp_register_script('jquery-validationEngine-' . $code . '', plugins_url('/lib/validationEngine/js/languages/jquery.validationEngine-' . $code . '.js', __FILE__), array('jquery'));

	wp_enqueue_script('jquery');

	wp_enqueue_media();

	wp_enqueue_script('thickbox');

	wp_enqueue_style('thickbox');

	wp_enqueue_script('jquery-validationEngine-' . $code . '');

	wp_register_script('jquery-validationEngine', plugins_url('/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array('jquery'));

	wp_enqueue_script('jquery-validationEngine');
	wp_enqueue_style('smgt-dynamic_css-css', plugins_url('/assets/css/dynamic_css.php', __FILE__));
	wp_enqueue_style('smgt-style-css', plugins_url('/assets/css/style.css', __FILE__));

	wp_enqueue_style('smgt-bootstrap-css', plugins_url('/assets/css/Bootstrap/bootstrap5.min.css', __FILE__));

	wp_enqueue_style('smgt-bootstrap-min-css', plugins_url('/assets/css/Bootstrap/bootstrap.min.css', __FILE__));

	wp_enqueue_style('smgt-responsive-css', plugins_url('/assets/css/school-responsive.css', __FILE__));

	if (is_rtl()) {

		wp_register_script('jquery-validationEngine-en', plugins_url('/lib/validationEngine/js/languages/jquery.validationEngine-' . $code . '.js', __FILE__), array('jquery'));

		wp_enqueue_script('smgt-validationEngine-en-js', plugins_url('/assets/js/Jquery/jquery.validationEngine-ar.js', __FILE__));

		wp_enqueue_style('css-custome_rtl-css', plugins_url('/assets/css/custome_rtl.css', __FILE__));
	}

	wp_enqueue_script('jquery-ui-datepicker');

	wp_register_script('font-awsome-js', plugins_url('/assets/js/fontawesome.min.js', __FILE__));

	wp_enqueue_script('smgt-custom_jobj', plugins_url('/assets/js/smgt_custom_confilict_obj.js', __FILE__), array('jquery'), '4.1.1', false);

	wp_enqueue_style('css-custome_rtl-css', plugins_url('/assets/css/custome_rtl.css', __FILE__));

	wp_enqueue_style('admission-css', plugins_url('/assets/css/settings/admission.css', __FILE__));

	wp_enqueue_style('jq-ui-css-m', plugins_url('/assets/css/jquery-ui.css', __FILE__));

	wp_enqueue_script('smgt-defaultscript_ui', plugins_url('/assets/js/Jquery/jquery-ui.js', __FILE__), array('jquery'), '4.1.1', true);

	?>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/assets/js/Jquery/jquery-3.6.0.min.js'; ?>"></script>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/assets/js/settings/admission.js'; ?>"></script>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/assets/js/Jquery/jquery-ui.js'; ?>"></script>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/lib/validationEngine/js/languages/jquery.validationEngine-' . $code . '.js'; ?>"></script>

	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>



	<script type="text/javascript">
		//add multiple Sibling //



		jQuery("body").on("change", ".input-file[type=file]", function()

			{

				"use strict";

				var file = this.files[0];

				var ext = $(this).val().split('.').pop().toLowerCase();

				//Extension Check

				if ($.inArray(ext, [, 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'gif', 'png', 'jpg', 'jpeg', '']) == -1)

				{

					alert('<?php esc_attr_e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. ', 'school-mgt'); ?>' + ext + '<?php esc_attr_e(' formate are not allowed.', 'school-mgt'); ?>');

					$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');

					return false;

				}

				//File Size Check

				if (file.size > 20480000)

				{

					alert(language_translate2.large_file_Size_alert);

					$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');

					return false;

				}

			});

		// function mj_smgt_deleteParentElement(n)

		// {

		// 	alert('Do you really want to delete this ?');

		// 	n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);

		// }
	</script>



	<?php

	$role = 'student_temp';

	$theme_name = get_template();

	if ($theme_name == 'Divi') {

	?>

		<style>
			.theme_page_addmission_form_padding {

				padding: 0px 10px 10px 10px;

			}
		</style>

	<?php

	}

	if ($theme_name == 'twentytwenty') {

	?>

		<style>
			.singular .entry-header {

				padding: 0 !important;

			}

			.post-inner {

				padding-top: 4rem !important;

			}

			.entry-content>*:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide) {

				width: 65% !important;

			}

			.entry-content>*:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide) {

				max-width: 65%;

			}
		</style>

	<?php

	}

	if ($theme_name == 'twentytwentyone') {

	?>

		<style>
			.site .button:not(:hover):not(:active):not(.has-background),
			button:not(:hover):not(:active):not(.has-background),
			input[type=reset]:not(:hover):not(:active):not(.has-background),
			.wp-block-search .wp-block-search__button:not(:hover):not(:active):not(.has-background),
			.wp-block-button .wp-block-button__link:not(:hover):not(:active):not(.has-background),
			.wp-block-file a.wp-block-file__button:not(:hover):not(:active):not(.has-background) {

				background-color: #fff !important;

			}

			.site .button:not(:hover):not(:active):not(.has-text-color),
			button:not(:hover):not(:active):not(.has-text-color),
			input[type=reset]:not(:hover):not(:active):not(.has-text-color),
			.wp-block-search .wp-block-search__button:not(:hover):not(:active):not(.has-text-color),
			.wp-block-button .wp-block-button__link:not(:hover):not(:active):not(.has-text-color),
			.wp-block-file a.wp-block-file__button:not(:hover):not(:active):not(.has-text-color) {

				color: #212529 !important;

			}

			.twentytwentyone {

				max-width: 65% !important;

			}

			.is-light-theme #admission_form input {

				border: 0px solid #ccc !important;

			}

			.is-light-theme #admission_form input[type="checkbox"] {

				border: 1px solid #ccc !important;

			}

			.is-light-theme #admission_form input[type="radio"] {

				border: 1px solid #ccc !important;

			}

			.save_btn {

				height: 46px;

				background-color: <?php echo get_option('smgt_system_color_code'); ?> !important;

				background: <?php echo get_option('smgt_system_color_code'); ?>;

				color: #fff !important;

				width: 100% !important;

				font-weight: 500 !important;

				font-size: 16px !important;

			}
		</style>

	<?php

	}

	if ($theme_name == 'twentytwentytwo') {

	?>

		<style>
			.twentytwentytwo {

				max-width: 65% !important;

			}
		</style>

	<?php

	}

	wp_enqueue_script('smgt-popup', plugins_url('/assets/js/popup.js', __FILE__));

	wp_localize_script('smgt-popup', 'smgt', array('ajax' => admin_url('admin-ajax.php')));

	?>



	<style>
		.sibling_div_none {

			display: none !important;

		}

		.sibling_div_block {

			display: block !important;

		}

		.save_btn {

			height: 46px;

			background-color: <?php echo get_option('smgt_system_color_code'); ?> !important;

			background: <?php echo get_option('smgt_system_color_code'); ?>;

			color: #fff !important;

			width: 100% !important;

			font-weight: 500 !important;

			font-size: 16px !important;

			line-height: 24px;

			text-align: center;

			color: #FFFFFF;

			text-transform: uppercase;

			border: 0px solid black !important;

		}

		.form-control:focus {

			box-shadow: 0 0 0 0rem rgb(13 110 253 / 25%) !important;

		}

		.form-control:disabled,
		.form-control[readonly] {

			background-color: #fff !important;

		}

		.input input[type=email]+label,
		.input input[type=number]+label,
		.input input[type=password]+label,
		.input input[type=text]+label,
		.input textarea+label {

			left: 30px !important;

			/* top: 20px !important; */

		}

		.line_height_29px_registration_from {

			line-height: 29px !important;

		}

		.line_height_27px_registration_from {

			line-height: 25px !important;

		}

		.accordion-button:focus {

			box-shadow: 0 0 0 0rem rgb(13 110 253 / 25%) !important;

		}

		.class_border_div {

			border: 1px solid #E1E3E5 !important;

			border-left: 5px solid <?php echo get_option('smgt_system_color_code'); ?> !important;

			margin-bottom: 15px !important;

		}

		.class_route_list:not(.collapsed) {

			background-color: #5840bb26 !important;

			color: #5840bb !important;

		}

		.input {

			margin-bottom: 0;

		}

		.margin_top_15px {

			margin-top: 15px !important;

		}

		.admintion_page_checkbox_span.front {

			font-size: 20px !important;

		}

		#sibling_div {

			padding: 20px !important;

		}

		.margin_top_10px {

			margin-top: 10px !important;

		}
	</style>
	<?php
	if (is_rtl()) {
	?>
		<style>
			div.row select {
    			background: #F2F5FA url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E) no-repeat right 95% top 55% !important;
			}
			.accordion-button::after
			{
				margin-right: auto !important;
				margin-left: 0 !important;
			}
			#admission_form div label {
				direction: rtl;
				left: 80% !important;
			}
			#admission_form div label.custom-top-label {
				left: unset !important;
			}
			#admission_form div label.phonecode {
				direction: rtl;
				left: 55% !important;
			}
			#admission_form div label.mobile_number_rtl {
				direction: rtl;
				left: 68% !important;
			}
			@media (max-width: 768px) {
				#admission_form div label {
					direction: rtl;
					left: 50% !important;
				}

				#admission_form div label.gender_label_rtl {
					direction: rtl;
					left: 75% !important;
				}
			}
		</style>
	<?php

	}
	?>

	<script>
		jQuery(document).ready(function($){
			"use strict";
			jQuery('#admission_date').datepicker({
				minDate : 0,
				dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
				changeMonth: true,
				changeYear: true,
				yearRange:'-65:+25',
				beforeShow: function (textbox, instance)
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'
					});
				},
				onChangeMonthYear: function(year, month, inst) {
					jQuery(this).val(month + "/" + year);
				}
			});
			jQuery('.birth_date').datepicker({
                    maxDate : 0,
					dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
                    changeMonth: true,
                    changeYear: true,
                    yearRange:'-65:+25',
                    beforeShow: function (textbox, instance)
                    {
                        instance.dpDiv.css({
                            marginTop: (-textbox.offsetHeight) + 'px'
                        });
                    },
                    onChangeMonthYear: function(year, month, inst) {
                        jQuery(this).val(month + "/" + year);
                    }
                });
		});
	</script>


	<div class="<?php echo $theme_name; ?>">



		<form id="admission_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">



			<input type="hidden" name="action" value="<?php echo $action; ?>">



			<input type="hidden" name="role" value="<?php echo $role; ?>" />



			<!--- Hidden User and password --------->



			<input id="username" type="hidden" name="username">



			<input id="password" type="hidden" name="password">



			<div class="">



				<div class="">



					<div class="accordion admission_label" id="myAccordion">



						<div class="accordion-item class_border_div">



							<h2 class="accordion-header accordion_header_custom_css" id="headingOne">



								<button type="button" class="accordion-button class_route_list collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne" style="font-weight:800;"><?php esc_attr_e('Student Information', 'school-mgt'); ?></button>



							</h2>



							<div id="collapseOne" class="accordion-collapse collapse theme_page_addmission_form_padding show" data-bs-parent="#myAccordion">



								<div class="card-body_1">



									<div class="form-body user_form padding_20px_child_theme margin_top_15px"> <!------  Form Body -------->



										<div class="row">



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="admission_no" class="line_height_29px_registration_from form-control validate[required] text-input" type="text" value="<?php echo mj_smgt_generate_admission_number(); ?>" name="admission_no" readonly>



														<label for="userinput1" class=""><?php esc_html_e('Admission Number', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="admission_date" class="line_height_29px_registration_from form-control validate[required]" type="text" name="admission_date" value="<?php echo mj_smgt_getdate_in_input_box(date("Y-m-d")); ?>" readonly>



														<label for="userinput1" class=""><?php esc_html_e('Admission Date', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>

											<?php

											if (get_option("smgt_admission_fees") == "yes") {

												$fees_id = get_option('smgt_admission_amount');

												$obj_fees = new Smgt_fees();

												$amount = $obj_fees->mj_smgt_get_single_feetype_data_amount($fees_id);

												if ($amount) {

													$fees = $amount;
												} else {

													$fees = 0;
												}

											?>

												<div class="col-md-12 error_msg_left_margin mb-3">

													<div class="form-group input">

														<div class="col-md-12 form-control">

															<input id="admission_fees" class="form-control" type="text" readonly value="<?php echo mj_smgt_get_currency_symbol() . ' ' . $fees; ?>">

															<label for="userinput1" class=""><?php esc_html_e('Admission Fees', 'school-mgt'); ?><span class="required">*</span></label>

														</div>

													</div>

												</div>

												<input id="" class="form-control" type="hidden" name="admission_fees" value="<?php echo $fees_id; ?>">

											<?php

											}

											?>

											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="first_name" class="line_height_29px_registration_from form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="first_name">



														<label for="userinput1" class=""><?php esc_html_e('First Name', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="middle_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text" name="middle_name">



														<label for="userinput1" class=""><?php esc_html_e('Middle Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="last_name" class="line_height_29px_registration_from form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="last_name">



														<label for="userinput1" class=""><?php esc_html_e('Last Name', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="birth_date" class="line_height_29px_registration_from form-control validate[required] birth_date" type="text" value="<?php echo mj_smgt_getdate_in_input_box(date("Y-m-d")); ?>" name="birth_date" readonly>



														<label for="userinput1" class=""><?php esc_html_e('Date Of Birth', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group">



													<div class="col-md-12 form-control">



														<div class="row padding_radio">



															<div class="input-group">



																<label class="custom-top-label margin_left_0 gender_label_rtl" style="top:-7px !important"><?php esc_html_e('Gender', 'school-mgt'); ?><span class="required">*</span></label>



																<div class="d-inline-block line_height_29px_registration_from">



																	<input type="radio" value="male" class="tog validate[required]" name="gender" <?php checked('male', $genderval);  ?> checked />



																	<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male', 'school-mgt'); ?></label>



																	&nbsp;&nbsp;<input type="radio" value="female" class="tog validate[required]" name="gender" <?php checked('female', $genderval);  ?> />



																	<label class="custom-control-label" for="female"><?php esc_html_e('Female', 'school-mgt'); ?></label>



																	&nbsp;&nbsp;<input type="radio" value="other" class="tog validate[required]" name="gender" <?php checked('other', $genderval);  ?> />



																	<label class="custom-control-label" for="other"><?php esc_html_e('Other', 'school-mgt'); ?></label>



																</div>



															</div>



														</div>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="address" class="line_height_29px_registration_from form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text" name="address">



														<label for="userinput1" class=""><?php esc_html_e('Address', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="state_name" class="line_height_29px_registration_from form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text" name="state_name">



														<label for="userinput1" class=""><?php esc_html_e('State', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="city_name" class="line_height_29px_registration_from form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text" name="city_name">



														<label for="userinput1" class=""><?php esc_html_e('City', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="zip_code" class="line_height_29px_registration_from form-control validate[required,custom[zipcode]]" maxlength="15" type="text" name="zip_code">



														<label for="userinput1" class=""><?php esc_html_e('Zip Code', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>

											<div class="col-md-4 mb-3">



												<div class="form-group input margin_bottom_0">



													<div class="col-md-12 form-control">



														<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option('smgt_contry')); ?>" class="line_height_29px_registration_from form-control" name="phonecode">



														<label for="phonecode" class="pl-2 phonecode"><?php esc_html_e('Code', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-8 mb-3 mobile_error_massage_left_margin">



												<div class="form-group input margin_bottom_0">



													<div class="col-md-12 form-control">



														<input id="phone" class="line_height_29px_registration_from form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text" name="mobile_number">



														<label for="userinput6" class="mobile_number_rtl"><?php esc_html_e('Mobile Number', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="email" class="line_height_29px_registration_from form-control validate[required,custom[email]] text-input email" maxlength="100" type="text" name="email">



														<label for="userinput1" class=""><?php esc_html_e('Email', 'school-mgt'); ?><span class="required">*</span></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="preschool_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="preschool_name">



														<label for="userinput1" class=""><?php esc_html_e('Previous School', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



										</div>



									</div>



								</div>



							</div>



						</div>

						<div class="accordion-item class_border_div">



							<h2 class="accordion-header" id="headingTwo">



								<button type="button" class="accordion-button class_route_list collapsed" style="font-weight:800;" data-bs-toggle="collapse" data-bs-target="#collapseTwo"><?php esc_attr_e('Siblings Information', 'school-mgt'); ?></button>



							</h2>



							<div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#myAccordion">

								<div class="card-body_1">

									<div class="panel-body padding_20px_child_theme">



										<div class="form-group">



											<div class="col-md-12 col-sm-12 col-xs-12" style="display: inline-flex;" id="relationid">



												<input type="checkbox" id="chkIsTeamLead" style="margin-top:4px;" />&nbsp;&nbsp;<h4 class="admintion_page_checkbox_span front"><?php esc_html_e('In case of any sibling ? click here', 'school-mgt'); ?></span>



											</div>



										</div>

										<script>
											function add_more_siblings() {
												var click_val = $(".click_value").val();
												var curr_data = {
													action: 'mj_smgt_load_siblings_dropdown',
													click_val: click_val,
													dataType: 'json'
												};
												$.post(smgt.ajax, curr_data, function(response) {
													var value = parseInt(click_val) + 1;
													$(".click_value").val(value);
													$("#sibling_div").append(response);
												});
											}

											function deleteParentElement(n) {

												var alert = confirm("<?php esc_attr_e('Are you sure you want to delete this record?', 'school-mgt'); ?>");
												if (alert == true) {
													n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
													return false;
												}
											}
										</script>
										<div id="sibling_div" class="sibling_div_none">

											<div class="form-body user_form">

												<div class="row">

													<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 input smgt_form_select mb-3">

														<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class', 'school-mgt'); ?><span class="require-field">*</span></label>

														<select name="siblingsclass[]" class="form-control validate[required]  class_in_student max_width_100" style="height:45px;" id="sibling_class_change">

															<option value=""><?php esc_attr_e('Select Class', 'school-mgt'); ?></option>

															<?php

															foreach (mj_smgt_get_allclass() as $classdata) {

															?>

																<option value="<?php echo $classdata['class_id']; ?>"><?php echo $classdata['class_name']; ?></option>

															<?php

															}

															?>

														</select>

													</div>

													<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input smgt_form_select mb-3">

														<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class Section', 'school-mgt'); ?></label>

														<select name="siblingssection[]" class="form-control max_width_100" style="height:45px;" id="sibling_class_section">

															<option value=""><?php esc_attr_e('Select Class Section', 'school-mgt'); ?></option>

														</select>

													</div>

													<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input class_section_hide mb-3">

														<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student', 'school-mgt'); ?><span class="require-field">*</span></label>

														<select name="siblingsstudent[]" id="sibling_student_list" style="height:45px;" class="form-control max_width_100 validate[required1]">

															<option value=""><?php esc_attr_e('Select Student', 'school-mgt'); ?></option>

														</select>

													</div>
													<input type="hidden" class="click_value" name="" value="1">
													<div class="col-md-1 col-sm-3 col-xs-12 width_20px_res">
														<img src="<?php echo SMS_PLUGIN_URL . "/assets/images/dashboard_icon/Add_new_plus_btn.png" ?>" onclick="add_more_siblings()" alt="" class="rtl_margin_top_15px add_cirtificate" id="add_more_sibling">
													</div>

												</div>

											</div>

										</div>



									</div>



								</div>



							</div>



						</div>











						<div class="accordion-item class_border_div">



							<h2 class="accordion-header" id="headingThree">



								<button type="button" class="accordion-button class_route_list collapsed" style="font-weight:800;" data-bs-toggle="collapse" data-bs-target="#collapseThree"><?php esc_attr_e('Family Information', 'school-mgt'); ?></a></button>



							</h2>







							<div id="collapseThree" class="accordion-collapse collapse margin_top_10px" data-bs-parent="#myAccordion">



								<div class="card-body_1 admission_parent_information_div">



									<div class="form-body user_form padding_20px_child_theme">



										<div class="row">



											<div class="col-md-12">



												<div class="form-group">



													<div class="col-md-12 form-control">



														<div class="row padding_radio">



															<div class="input-group ">



																<label class="custom-top-label margin_left_0"><?php esc_html_e('Parental Status', 'school-mgt'); ?></label>



																<div class="d-inline-block family_information">



																	<?php $pstatus = "Both"; ?>



																	<input type="radio" name="pstatus" class="tog" value="Father" id="sinfather" <?php checked('Father', $pstatus);  ?>>



																	<label class="custom-control-label margin_right_20px" for="Father"><?php esc_html_e('Father', 'school-mgt'); ?></label>



																	&nbsp;&nbsp; <input type="radio" name="pstatus" class="tog " id="sinmother" value="Mother" <?php checked('Mother', $pstatus);  ?>>



																	<label class="custom-control-label" for="Mother"><?php esc_html_e('Mother', 'school-mgt'); ?></label>



																	&nbsp;&nbsp;<input type="radio" name="pstatus" class="tog" id="boths" value="Both" <?php checked('Both', $pstatus);  ?>>



																	<label class="custom-control-label" for="Both"><?php esc_html_e('Both', 'school-mgt'); ?></label>



																</div>



															</div>



														</div>



													</div>



												</div>



											</div>



										</div>



									</div>



									<div class="panel-body">



										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 father_div ">



											<div class="header" id="fatid" style="margin-left:10px;">



												<h3 class="first_hed"><?php esc_html_e('Father Information', 'school-mgt'); ?></h3>



											</div>



											<div id="fatid1" class="col-md-12 mb-3">



												<div class="form-group input">



													<select class="line_height_29px_registration_from form-control validate[required]" name="fathersalutation" id="fathersalutation">



														<option value="Mr"><?php esc_attr_e('Mr', 'school-mgt'); ?></option>



													</select>



												</div>



											</div>



											<div id="fatid2" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_first_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_first_name">



														<label for="userinput1" class=""><?php esc_html_e('First Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid3" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_middle_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_middle_name">



														<label for="userinput1" class=""><?php esc_html_e('Middle Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid4" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_last_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_last_name">



														<label for="userinput1" class=""><?php esc_html_e('Last Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid13" class="col-md-12 mb-3">



												<div class="form-group radio_button_bottom_margin_rs margin_top_15px_child_theme">



													<div class="col-md-12 form-control">



														<div class="row padding_radio line_height_29px_registration_from">



															<div class="input-group">



																<label class="custom-top-label margin_left_0 gender_label_rtl" style="left: 0px;top:-11px !important"><?php esc_html_e('Gender', 'school-mgt'); ?></label>



																<div class="d-inline-block">



																	<?php $father_gender = "male"; ?>



																	<input type="radio" value="male" class="tog" name="fathe_gender" <?php checked('male', $father_gender);  ?> />



																	<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male', 'school-mgt'); ?></label>&nbsp;&nbsp;



																	<input type="radio" value="female" class="tog" name="fathe_gender" <?php checked('female', $father_gender);  ?> />



																	<label class="custom-control-label" for="female"><?php esc_html_e('Female', 'school-mgt'); ?></label>&nbsp;&nbsp;



																	<input type="radio" value="other" class="tog" name="fathe_gender" <?php checked('other', $father_gender);  ?> />



																	<label class="custom-control-label" for="other"><?php esc_html_e('Other', 'school-mgt'); ?></label>&nbsp;&nbsp;



																</div>



															</div>



														</div>



													</div>



												</div>



											</div>



											<div id="fatid14" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_birth_date" class="line_height_29px_registration_from form-control birth_date" type="text" name="father_birth_date" readonly>



														<label for="userinput1"><?php esc_html_e('Date of Birth', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid15" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_address" class="line_height_29px_registration_from form-control validate[custom[address_description_validation]]" maxlength="150" type="text" name="father_address">



														<label for="userinput1"><?php esc_html_e('Address', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid16" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_state_name" class="line_height_29px_registration_from form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text" name="father_state_name">



														<label for="userinput1"><?php esc_html_e('State', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid17" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_city_name" class="line_height_29px_registration_from form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text" name="father_city_name">



														<label for="userinput1"><?php esc_html_e('City', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid18" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_zip_code" class="line_height_29px_registration_from form-control  validate[custom[zipcode]]" maxlength="15" type="text" name="father_zip_code">



														<label for="userinput1"><?php esc_html_e('Zip Code', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid5" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_email" class="line_height_29px_registration_from form-control validate[custom[email]] text-input father_email" maxlength="100" type="text" name="father_email">



														<label for="userinput1"><?php esc_html_e('Email', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid6" class="col-md-12 mb-3">



												<div class="row">



													<div class="col-md-4">



														<div class="form-group input margin_bottom_0">



															<div class="col-md-12 form-control">



																<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option('smgt_contry')); ?>" class="line_height_29px_registration_from form-control" name="phone_code">



																<label for="phonecode" class="pl-2 phonecode"><?php esc_html_e('Country Code', 'school-mgt'); ?></label>



															</div>



														</div>



													</div>



													<div class="col-md-8">



														<div class="form-group input margin_bottom_0">



															<div class="col-md-12 form-control">



																<input id="father_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]] line_height_29px_registration_from" type="text" name="father_mobile">



																<label for="userinput6" class="mobile_number_rtl"><?php esc_html_e('Mobile Number', 'school-mgt'); ?></label>



															</div>



														</div>



													</div>



												</div>



											</div>



											<div id="fatid7" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input line_height_29px_registration_from" maxlength="50" type="text" name="father_school">



														<label for="userinput1"><?php esc_html_e('School Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid8" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_medium" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_medium">



														<label for="userinput1"><?php esc_html_e('Medium of Instruction', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid9" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_education" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_education">



														<label for="userinput1"><?php esc_html_e('Educational Qualification', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid10" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="fathe_income" class="line_height_29px_registration_from form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" maxlength="50" type="text" name="fathe_income">



														<label for="userinput1"><?php esc_html_e('Annual Income', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="fatid9" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="father_occuption" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_occuption">



														<label for="userinput1"><?php esc_html_e('Occupation', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div class="col-md-12 mb-3" id="fatid12">



												<div class="form-group input margin_top_15px_child_theme">



													<div class="col-md-12 form-control">



														<label for="" class="custom-control-label custom-top-label ml-2 margin_left_30px" style="left: 20px;top:9px !important"><?php _e('Proof of Qualification', 'school-mgt'); ?></label>



														<div class="col-sm-12">



															<input type="file" name="father_doc" class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file father_doc">



														</div>



													</div>



												</div>



											</div>



										</div>



										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mother_div">



											<div class="header" id="motid" style="margin-left:10px;">



												<h3 class="first_hed"><?php esc_html_e('Mother Information', 'school-mgt'); ?></h3>



											</div>



											<div id="motid1" class="col-md-12 mb-3">



												<div class="form-group input">



													<select class="form-control validate[required]" name="mothersalutation" id="mothersalutation">



														<option value="Ms"><?php esc_attr_e('Ms', 'school-mgt'); ?></option>



														<option value="Mrs"><?php esc_attr_e('Mrs', 'school-mgt'); ?></option>



														<option value="Miss"><?php esc_attr_e('Miss', 'school-mgt'); ?></option>



													</select>



												</div>



											</div>



											<div id="motid2" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_first_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_first_name">



														<label for="userinput1" class=""><?php esc_html_e('First Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid3" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_middle_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_middle_name">



														<label for="userinput1" class=""><?php esc_html_e('Middle Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid4" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_last_name" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_last_name">



														<label for="userinput1" class=""><?php esc_html_e('Last Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid13" class="col-md-12 mb-3">



												<?php $mother_gender = "female"; ?>



												<div class="form-group radio_button_bottom_margin_rs margin_top_15px_child_theme">



													<div class="col-md-12 form-control">



														<div class="row padding_radio line_height_29px_registration_from">



															<div class="input-group">



																<label class="custom-top-label margin_left_0 gender_label_rtl" style="left: 0px;top:-11px !important"><?php esc_html_e('Gender', 'school-mgt'); ?></label>



																<div class="d-inline-block">



																	<input type="radio" value="male" class="tog" name="mother_gender" <?php checked('male', $mother_gender);  ?> />



																	<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male', 'school-mgt'); ?></label>&nbsp;&nbsp;



																	<input type="radio" value="female" class="tog" name="mother_gender" <?php checked('female', $mother_gender);  ?> />



																	<label class="custom-control-label" for="female"><?php esc_html_e('Female', 'school-mgt'); ?></label>&nbsp;&nbsp;



																	<input type="radio" value="other" class="tog" name="mother_gender" <?php checked('other', $mother_gender);  ?> />



																	<label class="custom-control-label" for="other"><?php esc_html_e('Other', 'school-mgt'); ?></label>&nbsp;&nbsp;



																</div>



															</div>



														</div>



													</div>



												</div>



											</div>



											<div id="motid14" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_birth_date" class="line_height_29px_registration_from form-control birth_date" type="text" name="mother_birth_date" readonly>



														<label for="userinput1"><?php esc_html_e('Date of Birth', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid15" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_address" class="line_height_29px_registration_from form-control validate[custom[address_description_validation]]" maxlength="150" type="text" name="mother_address">



														<label for="userinput1"><?php esc_html_e('Address', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid16" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_state_name" class="line_height_29px_registration_from form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text" name="mother_state_name">



														<label for="userinput1"><?php esc_html_e('State', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid17" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_city_name" class="line_height_29px_registration_from form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text" name="mother_city_name">



														<label for="userinput1"><?php esc_html_e('City', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid18" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_zip_code" class="line_height_29px_registration_from form-control  validate[custom[zipcode]]" maxlength="15" type="text" name="mother_zip_code">



														<label for="userinput1"><?php esc_html_e('Zip Code', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid5" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_email" class="line_height_29px_registration_from form-control  validate[custom[email]]  text-input mother_email" maxlength="100" type="text" name="mother_email">



														<label for="userinput1"><?php esc_html_e('Email', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid6" class="col-md-12 mb-3">



												<div class="row">



													<div class="col-md-4">



														<div class="form-group input margin_bottom_0">



															<div class="col-md-12 form-control">



																<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option('smgt_contry')); ?>" class="line_height_29px_registration_from form-control" name="phone_code">



																<label for="phonecode" class="pl-2 phonecode"><?php esc_html_e('Country Code', 'school-mgt'); ?></label>



															</div>



														</div>



													</div>



													<div class="col-md-8">



														<div class="form-group input margin_bottom_0">



															<div class="col-md-12 form-control">



																<input id="mother_mobile" class="line_height_29px_registration_from form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text" name="mother_mobile">



																<label for="userinput6" class="mobile_number_rtl"><?php esc_html_e('Mobile Number', 'school-mgt'); ?></label>



															</div>



														</div>



													</div>



												</div>



											</div>



											<div id="motid7" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_school" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_school">



														<label for="userinput1"><?php esc_html_e('School Name', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<?php wp_nonce_field('save_admission_form'); ?>



											<div id="motid8" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_medium" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_medium">



														<label for="userinput1"><?php esc_html_e('Medium of Instruction', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid9" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_education" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_education">



														<label for="userinput1"><?php esc_html_e('Educational Qualification', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid10" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_income" class="line_height_29px_registration_from form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" maxlength="50" type="text" name="mother_income">



														<label for="userinput1"><?php esc_html_e('Annual Income', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid9" class="col-md-12 mb-3">



												<div class="form-group input">



													<div class="col-md-12 form-control">



														<input id="mother_occuption" class="line_height_29px_registration_from form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_occuption">



														<label for="userinput1"><?php esc_html_e('Occupation', 'school-mgt'); ?></label>



													</div>



												</div>



											</div>



											<div id="motid12" class="col-md-12 mb-3">



												<div class="form-group input margin_top_15px_child_theme">



													<div class="col-md-12 form-control">



														<label for="" class="custom-control-label custom-top-label ml-2 margin_left_30px" style="left: 20px;top:9px !important"><?php _e('Proof of Qualification', 'school-mgt'); ?></label>



														<div class="col-sm-12">



															<input type="file" name="mother_doc" class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file father_doc">



														</div>



													</div>



												</div>



											</div>



										</div>



									</div>



								</div>



							</div>



						</div>



					</div>



				</div>



			</div>

	</div>

	<div class="col-sm-6 admission_button" style="width:100%;">



		<input type="submit" value="<?php esc_attr_e('New Admission', 'school-mgt'); ?>" name="save_student_front_admission" class="btn btn-success btn_style save_btn" />



	</div>



	</form>



	</div>

	<script>
		var value = 0;

		// function mj_smgt_add_sibling()

		// {

		// 	value++;

		// 	$("#sibling_div").append('<div class="form-body user_form"><div class="row"><div class="col-md-3 col-sm-3 col-xs-12 res_margin_bottom_20px" style="margin-left:8px;width:25%;padding-left:0;padding-right:0;"><div class="form-group"><div class="col-md-12 form-control"><div class="row padding_radio"><div class="input-group line_height_29px_registration_from"><label class="custom-top-label margin_left_0"><?php esc_html_e('Relation', 'school-mgt'); ?></label><div class="d-inline-block"><input type="radio" name="siblinggender['+value+']" value="Brother" id="txtNumHours2" checked><label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Brother', 'school-mgt'); ?></label>&nbsp;&nbsp;<input type="radio" name="siblinggender['+value+']" value="Sister" id="txtNumHours2"><label class="custom-control-label" for="female"><?php esc_html_e('Sister', 'school-mgt'); ?></label></div></div></div></div></div></div><div class="col-md-2 col-sm-3 col-xs-12" style="width:15%;padding-left:0;padding-right:0;"><div class="form-group input"><div class="col-md-12 form-control"><input id="txtNumHours" class="line_height_29px_registration_from form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  name="siblingsname[]"><label for="userinput1" class=""><?php esc_html_e('Full Name', 'school-mgt'); ?><span class="required">*</span></label></div></div></div><div class="col-md-1 col-sm-3 col-xs-12" style="width:13%;padding-left:0;padding-right:0;"><div class="form-group input"><div class="col-md-12 form-control input_height_47px"><input id="txtNumHours1" class="line_height_29px_registration_from form-control age_padding_left_right_0 validate[custom[onlyNumberSp],maxSize[3],max[100]] text-input" type="number" maxlength="3" name="siblingage[]" ><label for="userinput1" class=""><?php esc_html_e('Age', 'school-mgt'); ?></label></div></div></div><div class="col-md-2 col-sm-3 col-xs-12 input" style="width:15%;padding-left:0;padding-right:0;margin-top:10px;"><label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Standard', 'school-mgt'); ?><span class="required">*</span></label><select class="line_height_29px_registration_from form-control standard_category validate[required] line_height_30px" name="sibling_standard[]" id="txtNumHours3"><option value=""><?php esc_html_e('Select Standard', 'school-mgt'); ?></option><?php $activity_category = mj_smgt_get_all_category('standard_category');
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																											if (!empty($activity_category)) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																												foreach ($activity_category as $retrive_data) { ?><option value="<?php echo $retrive_data->ID; ?>"><?php echo esc_attr($retrive_data->post_title); ?> </option><?php }
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																										} ?></select></div><div class="col-md-2 col-sm-3 col-xs-12" style="width:20%;padding-left:0;padding-right:0;"><div class="form-group input"><div class="col-md-12 form-control input_height_47px"><input id="txtNumHours4" class="line_height_29px_registration_from form-control validate[custom[onlyNumberSp],maxSize[6]] text-input" type="number"  name="siblingsid[]" > <label for="userinput1" class=""><?php esc_html_e('Enter SID Number', 'school-mgt'); ?></label></div></div></div><div class="col-md-1 col-sm-1 col-xs-12" style="width:10%;margin-top:10px;margin-right:5px;">	<img src="<?php echo SMS_PLUGIN_URL . "/assets/images/listpage_icon/Delete.png" ?>" onclick="mj_smgt_deleteParentElement(this)" alt="" class="rtl_margin_top_15px remove_cirtificate float_right input_btn_height_width"></div></div></div>');



		// }
	</script>

	<?php

}

function mj_smgt_complete_admission($admission_no, $admission_date, $first_name, $middle_name, $last_name, $birth_date, $gender, $address, $state_name, $city_name, $zip_code, $phone_code, $mobile_number, $alternet_mobile_number, $email, $username, $password, $preschool_name, $smgt_user_avatar, $sibling_information, $p_status, $fathersalutation, $father_first_name, $father_middle_name, $father_last_name, $fathe_gender, $father_birth_date, $father_address, $father_city_name, $father_state_name, $father_zip_code, $father_email, $father_mobile, $father_school, $father_medium, $father_education, $fathe_income, $father_occuption, $father_doc, $mothersalutation, $mother_first_name, $mother_middle_name, $mother_last_name, $mother_gender, $mother_birth_date, $mother_address, $mother_city_name, $mother_state_name, $mother_zip_code, $mother_email, $mother_mobile, $mother_school, $mother_medium, $mother_education, $mother_income, $mother_occuption, $mother_doc, $wp_nonce, $admission_fees)

{



	global $reg_errors;



	if (wp_verify_nonce($wp_nonce, 'save_admission_form')) {



		if (1 > count($reg_errors->get_error_messages())) {



			$userdata 	= 	array(

				'user_login'	=>	$email,

				'user_nicename'	=>	NULL,

				'user_email'	=>	$email,

				'user_url'		=>	NULL,

				'display_name'	=>	$first_name . " " . $last_name,

			);

			if ($password != "") {

				$userdata['user_pass'] = mj_smgt_password_validation($password);
			} else {

				$userdata['user_pass'] = wp_generate_password();
			}

			$role = "student_temp";

			$status = "Not Approved";

			// ADD USER META //

			if (get_option("smgt_admission_fees") == "yes") {

				$admission_fees_id = $admission_fees;

				$obj_fees = new Smgt_fees();

				$admission_fees_amount = $obj_fees->mj_smgt_get_single_feetype_data_amount($admission_fees_id);
			}



			$usermetadata	=	array(

				'admission_no'	=>	$admission_no,

				'admission_date'	=> $admission_date,

				'admission_fees'	=> $admission_fees_amount,

				'role'	=> $role,

				'status'	=> $status,

				'roll_id'	=> "",

				'middle_name'	=> $middle_name,

				'gender'	=> $gender,

				'birth_date' => $birth_date,

				'address'	=>	$address,

				'city'		=>	$city_name,

				'state'		=>	$state_name,

				'zip_code'	=>	$zip_code,

				'preschool_name'	=> $preschool_name,

				'phone_code'		=> $phone_code,

				'mobile_number'		=> $mobile_number,

				'alternet_mobile_number'	=> $alternet_mobile_number,

				'sibling_information'	=> json_encode($sibling_information),

				'parent_status'	=> $p_status,

				'fathersalutation'	=>	$fathersalutation,

				'father_first_name'	=>	$father_first_name,

				'father_middle_name'	=>	$father_middle_name,

				'father_last_name'	=>	$father_last_name,

				'fathe_gender'	=> $fathe_gender,

				'father_birth_date'	=> $father_birth_date,

				'father_address' => $father_address,

				'father_city_name' => $father_city_name,

				'father_state_name' => $father_state_name,

				'father_zip_code' => $father_zip_code,

				'father_email'	=>	$father_email,

				'father_mobile'	=>	$father_mobile,

				'father_school'	=>	$father_school,

				'father_medium'	=> $father_medium,

				'father_education'	=> $father_education,

				'fathe_income'	=> $fathe_income,

				'father_occuption'	=> $father_occuption,

				'father_doc'	=> json_encode($father_doc),

				'mothersalutation'	=>	$mothersalutation,

				'mother_first_name'	=>	$mother_first_name,

				'mother_middle_name'	=>	$mother_middle_name,

				'mother_last_name'	=>	$mother_last_name,

				'mother_gender'	=> $mother_gender,

				'mother_birth_date'	=> $mother_birth_date,

				'mother_address' => $mother_address,

				'mother_city_name' => $mother_city_name,

				'mother_state_name' => $mother_state_name,

				'mother_zip_code' => $mother_zip_code,

				'mother_email'	=>	$mother_email,

				'mother_mobile'	=>	$mother_mobile,

				'mother_school'	=>	$mother_school,

				'mother_medium'	=> $mother_medium,

				'mother_education'	=> $mother_education,

				'mother_income'	=> $mother_income,

				'mother_occuption'	=> $mother_occuption,

				'mother_doc'	=> json_encode($mother_doc),

				'smgt_user_avatar'	=> $smgt_user_avatar,

				'created_by'	=> 1

			);



			$returnval;

			$user_id = wp_insert_user($userdata);

			$user = new WP_User($user_id);

			$user->set_role($role);

			foreach ($usermetadata as $key => $val) {

				$returnans = add_user_meta($user_id, $key, $val, true);
			}

			if (get_option("smgt_admission_fees") == "yes") {

				$generated = mj_smgt_generate_admission_fees_invoice($admission_fees_amount, $user_id, $admission_fees_id, 0, 0, 'Admission Fees');
			}

			$returnval = update_user_meta($user_id, 'first_name', $first_name);

			$returnval = update_user_meta($user_id, 'last_name', $last_name);

			$hash = md5(rand(0, 1000));

			$returnval = update_user_meta($user_id, 'hash', $hash);

			if ($user_id) {

				//---------- ADMISSION REQUEST MAIL ---------//

				$string = array();

				$string['{{student_name}}']   = mj_smgt_get_display_name($user_id);

				$string['{{user_name}}']   =  $first_name . ' ' . $last_name;

				$string['{{email}}']   =  $userdata['user_email'];

				$string['{{school_name}}'] =  get_option('smgt_school_name');

				$MsgContent                =  get_option('admission_mailtemplate_content');

				$MsgSubject				   =  get_option('admissiion_title');

				$message = mj_smgt_string_replacement($string, $MsgContent);

				$MsgSubject = mj_smgt_string_replacement($string, $MsgSubject);



				$email = $email;

				mj_smgt_send_mail($email, $MsgSubject, $message);

	?>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="color:green;">

					<?php

					esc_attr_e('Request For Admission Successfully. You will be able to access your account after the school admin approves it.', 'school-mgt');

					?>

				</div>

		<?php

			}

			return $returnval;
		}
	} else {

		die('Security check');
	}
}

function mj_smgt_admission_validation($email, $username, $father_email, $mother_email)

{

	global $reg_errors;

	$reg_errors = new WP_Error;

	if (4 > strlen($username)) {

		$reg_errors->add('username_length', 'Username too short. At least 4 characters is required');
	}

	if (username_exists($username))

		$reg_errors->add('user_name', 'Sorry, that username already exists!');


	if (!is_email($email)) {

		$reg_errors->add('email_invalid', 'Email is not valid');
	}

	if (email_exists($email)) {

		$reg_errors->add('email', 'Email Already in use');
	}



	if (is_wp_error($reg_errors)) {



		foreach ($reg_errors->get_error_messages() as $error) {

			echo '<div class="student_reg_error">';

			echo '<strong>' . esc_attr__("ERROR", "school-mgt") . '</strong> : ';

			echo '<span class="error"> ' . esc_attr__("$error", "school-mgt") . ' </span><br/>';

			echo '</div>';
		}
	}
}
function remove_menus()
{
	$author = wp_get_current_user();
	if (isset($author->roles[0])) {
		$current_role = $author->roles[0];
	} else {
		$current_role = 'management';
	}
	if ($current_role == 'management') {
		add_action('admin_bar_menu', 'shapeSpace_remove_toolbar_nodes', 999);
		add_action('admin_menu', 'remove_menus1', 999);
		add_action('admin_menu', 'wpdocs_remove_menus', 999);
		add_action('admin_menu', 'disable_elementor_menu', 999);
		$management = get_role('management');
		$management->add_cap('upload_files');
		?>
		<style>
			#menu-media {
				display: none !important;
			}
		</style>
<?php
	}
}
function wpdocs_remove_menus()
{
	remove_menu_page('index.php');                  //Dashboard
	remove_menu_page('jetpack');                    //Jetpack*
	remove_menu_page('edit.php');                   //Posts
	remove_menu_page('upload.php');                 //Media
	remove_menu_page('edit.php?post_type=page');    //Pages
	remove_menu_page('edit-comments.php');          //Comments
	remove_menu_page('themes.php');                 //Appearance
	remove_menu_page('plugins.php');                //Plugins
	remove_menu_page('users.php');                  //Users
	remove_menu_page('tools.php');                  //Tools
	remove_menu_page('options-general.php');        //Settings
}
function disable_elementor_menu()
{
	remove_menu_page('elementor');
	remove_menu_page('edit.php?post_type=elementor_library');
}
add_action('admin_menu', 'remove_menus');
function remove_menus1()
{
	if (!current_user_can('administrator')) {
		remove_menu_page('jetpack');
	}
}
function shapeSpace_remove_toolbar_nodes($wp_admin_bar)
{
	$wp_admin_bar->remove_node('wp-logo');
	$wp_admin_bar->remove_node('site-name');
}
/*

For Demo //

add_action( 'load-profile.php', function()

{

    if( ! current_user_can( 'manage_options' ) )

        exit( wp_safe_redirect( admin_url().'/admin.php?page=smgt_school' ) );

} ); */


add_filter('document_title_parts', 'my_custom_title');

function my_custom_title($title)

{

	$page_name = '';

	if (!empty($_REQUEST['page'])) {

		$page_name = $_REQUEST['page'];
	}



	if (isset($_REQUEST['page'])) {

		if ($_REQUEST['page'] == $page_name) {

			$title['title'] = __($page_name, "school-mgt");
		}
	} else {

		if (is_singular('post')) {

			$title['title'] = get_option('smgt_school_name', 'school-mgt') . ' ' . $title['title'];
		}
	}

	return $title;
}
function mj_smgt_student_login($user_login, $user)
{

	$role = $user->roles;

	$role_name = $role[0];

	school_append_user_log($user_login, $role_name);
}

function mj_smgt_generate_recurring_invoice()
{
	set_time_limit(0);
	global $wpdb;
	$obj_feespayment = new mj_smgt_feespayment();
	$table_smgt_fees = $wpdb->prefix . 'smgt_fees';
	$table_smgt_fees_payment_recurring 	= $wpdb->prefix . 'smgt_fees_payment_recurring';
	$date = date('Y-m-d');
	$all_recurring_fees_data = $obj_feespayment->mj_smgt_get_all_recurring_fees_active($date);
	
	if (!empty($all_recurring_fees_data)) {
		foreach ($all_recurring_fees_data as $recurring_fees_data) {

			$student_id_array = explode(',', $recurring_fees_data->student_id);
			$fees_id_array =	explode(',', $recurring_fees_data->fees_id);
			$recurring_type =	$recurring_fees_data->recurring_type;

			if ($recurring_type == 'monthly') {
				$recurring_enddate = date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d"))));
			}
			else if ($recurring_type == 'weekly') {
				$recurring_enddate = date('Y-m-d', strtotime("+1 week", strtotime(date("Y-m-d"))));
			} 
			else if ($recurring_type == 'quarterly') {
				$recurring_enddate = date('Y-m-d', strtotime("+3 months", strtotime(date("Y-m-d"))));
			} else if ($recurring_type == 'half_yearly') {
				$recurring_enddate = date('Y-m-d', strtotime("+6 months", strtotime(date("Y-m-d"))));
			} else {
				$recurring_enddate = date("Y-m-d");
			}

			$fees_amount = array();
			foreach ($fees_id_array as $id) {
				$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees where fees_id = $id");
				$fees_amount[] = $result->fees_amount;
			}
			$total_fees_amount = array_sum($fees_amount);

			foreach ($student_id_array as $student_id) {
				global $wpdb;
				$table_smgt_fees_payment 	= $wpdb->prefix . 'smgt_fees_payment';
				$feedata['class_id']    	=	$recurring_fees_data->class_id;
				$feedata['section_id']		=	$recurring_fees_data->section_id;
				$feedata['fees_id']		    =	$recurring_fees_data->fees_id;
				$feedata['student_id']		    =	$student_id;
				$feedata['fees_amount']	=	$total_fees_amount;
				if(isset($recurring_fees_data->tax))
				{
					$feedata['tax']= $recurring_fees_data->tax;
					$feedata['tax_amount'] = MJ_smgt_get_tax_amount($total_fees_amount, explode(",", $recurring_fees_data->tax));
				}
				else
				{
					$feedata['tax']=null;
					$feedata['tax_amount'] = 0;
				}
				$feedata['total_amount']	=	$feedata['fees_amount'] + $feedata['tax_amount'];
				$feedata['description']		=	$recurring_fees_data->description;
				$feedata['start_year']		=	date("Y-m-d");
				$feedata['end_year']		=	$recurring_enddate;
				$feedata['paid_by_date']	=	date("Y-m-d");
				$feedata['created_date']	=	date("Y-m-d H:i:s");
				$feedata['created_by']		=	get_current_user_id();

				//Generate Recurring Invoice //
				$result	=	$wpdb->insert($table_smgt_fees_payment, $feedata);
				$fees_pay_id 	= 	$wpdb->insert_id;
				//END Generate Recurring Invoice //

				//Send Mail Notiifcation to student //
				$student_info 	= 	get_userdata($student_id);
				$Cont = get_option('fee_payment_mailcontent');
				$email = $student_info->user_email;
				$SearchArr['{{student_name}}']	=	$student_info->display_name;
				$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');
				$SearchArr['{{date}}']     = mj_smgt_getdate_in_input_box(date('Y-m-d'));
				$SearchArr['{{amount}}']     = mj_smgt_get_currency_symbol() . '' . number_format($total_fees_amount, 2, '.', '');
				$MessageContent = mj_smgt_string_replacement($SearchArr, get_option('fee_payment_mailcontent'));
				if (get_option('smgt_mail_notification') == '1') {
					mj_smgt_send_mail_paid_invoice_pdf($email, get_option('fee_payment_title'), $MessageContent, $fees_pay_id);
				}
				//END Send Mail Notiifcation to student //
				//Send Mail To Parant code start //
				$parent 	= 	get_user_meta($student_id, 'parent_id', true);
				if (!empty($parent)) {
					foreach ($parent as $parent_id) {
						$parent_info = get_userdata($parent_id);
						$Cont = get_option('fee_payment_title_for_parent');
						$email = $parent_info->user_email;
						$SearchArr['{{parent_name}}']	=	$parent_info->display_name;
						$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');
						$SearchArr['{{date}}']     = mj_smgt_getdate_in_input_box(date('Y-m-d'));
						$SearchArr['{{amount}}']     = mj_smgt_get_currency_symbol() . '' . number_format($total_fees_amount, 2, '.', '');
						$SearchArr['{{child_name}}']	=	$student_info->display_name;
						$MessageContent = mj_smgt_string_replacement($SearchArr, get_option('fee_payment_mailcontent_for_parent'));
						if (get_option('smgt_mail_notification') == '1') {
							mj_smgt_send_mail_paid_invoice_pdf($email, get_option('fee_payment_title'), $MessageContent, $fees_pay_id);
						}
					}
				}
				//END Send Mail To Parant code start //
			}
			//Update Recuring END DATE//
			$recurring_fees_id['recurring_id']	=	$recurring_fees_data->recurring_id;
			$recurring_feedata['recurring_enddate']	=	$recurring_enddate;
			$result = $wpdb->update($table_smgt_fees_payment_recurring, $recurring_feedata, $recurring_fees_id);
			//END Update Recuring END DATE//
		}
	}
}
add_action('recurring_invoice_event', 'mj_smgt_generate_recurring_invoice');
wp_schedule_single_event(time() + 1800, 'recurring_invoice_event');
?>
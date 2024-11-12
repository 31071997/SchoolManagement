<?php

// This is Dashboard at admin side!!!!!!!!!

$obj_attend = new Attendence_Manage();

$obj_event = new event_Manage();

$all_notice = "";

$args['post_type'] = 'notice';

$args['posts_per_page'] = -1;

$args['post_status'] = 'public';

$q = new WP_Query();

$all_notice = $q->query($args);

$notive_array = array();

if (!empty($all_notice)) {

	foreach ($all_notice as $notice) {

		$notice_start_date = get_post_meta($notice->ID, 'start_date', true);

		$notice_end_date = get_post_meta($notice->ID, 'end_date', true);

		$notice_comment = $notice->post_content;

		if(!empty($notice->post_content))

		{

			$notice_comment = $notice->post_content;

		}

		else

		{

			$notice_comment = "N/A";

		}

		$start_date =  $notice->start_date;

		$end_date =  $notice->end_date;

		$notice_for = ucfirst(get_post_meta($notice->ID, 'notice_for',true));

		$i = 1;

		if(get_post_meta( $notice->ID, 'smgt_class_id',true) != "" && get_post_meta( $notice->ID, 'smgt_class_id',true) =="all")

		{

			$class_name = esc_html__('All','school-mgt');

		}

		elseif(get_post_meta( $notice->ID, 'smgt_class_id',true) != "")

		{

			$class_name = mj_smgt_get_class_name(get_post_meta($notice->ID, 'smgt_class_id',true));

		}

		else

		{

			$class_name = '';

		}

		$to =  esc_html__( "To", "school-mgt" );

		$start_to_end_date = mj_smgt_getdate_in_input_box($start_date).' '.$to.' '.mj_smgt_getdate_in_input_box($end_date);

		$notice_title = $notice->post_title;



		$notive_array[] = array(

			'event_title' => esc_html__( 'Notice Details', 'school-mgt' ),

			'notice_title' => $notice_title,

			'title' => $notice->post_title,

			'description' => 'notice',

			'notice_comment' => $notice_comment,

			'notice_for' => esc_html__($notice_for,'school-mgt'),

			'start' => mysql2date('Y-m-d', $notice_start_date),

			'class_name' => $class_name,

			'end' => date('Y-m-d', strtotime($notice_end_date . ' +' . $i . ' days')),

			'color' => '#ffd000',

			'start_to_end_date' => $start_to_end_date,

		);

	}

}

$holiday_list = mj_smgt_get_all_data('holiday');



if (!empty($holiday_list)) {



	foreach ($holiday_list as $holiday)

	{

		if($holiday->status == 0)

		{

			$notice_start_date = $holiday->date;

			$notice_end_date = $holiday->end_date;

			$i = 1;

			$holiday_title = $holiday->holiday_title;

			$holiday_comment = $holiday->description;

			if(!empty($holiday->description))

			{

				$holiday_comment = $holiday->description;

			}

			else

			{

				$holiday_comment ="N/A";

			}

			$to =  esc_html__( "To", "school-mgt" );

			$start_to_end_date = mj_smgt_getdate_in_input_box($notice_start_date).' '.$to.' '.mj_smgt_getdate_in_input_box($notice_end_date);

			$notive_array[] = array(

				'event_title' => esc_html__( 'Holiday Details', 'school-mgt' ),

				'title' => $holiday->holiday_title,

				'description' => 'holiday',

				'start' => mysql2date('Y-m-d', $notice_start_date),

				'end' => date('Y-m-d', strtotime($notice_end_date . ' +' . $i . ' days')),

				'color' => '#3c8dbc',

				'holiday_title' => $holiday_title,

				'holiday_comment' => $holiday_comment,

				'start_to_end_date' => $start_to_end_date,

				'status' => esc_html__( 'Approve', 'school-mgt' ),

			);

		}

	}

}



//----------- EVENT FOR CELENDAR -------------//

$event_list = mj_smgt_get_all_data('event');

// var_dump($event_list);

// die;

if(!empty($event_list))

{



	foreach ($event_list as $event)

	{

		$event_start_date = $event->start_date;

		$event_end_date = $event->end_date;

		$i = 1;



		$notive_array[] = array(

			'event_title' => esc_html__( 'Event Details', 'school-mgt' ),

			'title' => $event->event_title,

			'description' => 'event',

			'start' => mysql2date('Y-m-d', $event_start_date),

			'end' => date('Y-m-d', strtotime($event_end_date . ' +' . $i . ' days')),

			'color' => '#36A8EB',

			'event_heading' => $event->event_title,

			'event_comment' => $event->description,

			'event_start_time' => MJ_smgt_timeremovecolonbefoream_pm($event->start_time),

			'event_end_time' => MJ_smgt_timeremovecolonbefoream_pm($event->end_time),

			'event_start_date' => $event->start_date,

			'event_end_date' => $event->end_date,

		);

	}

}



$exam_list = mj_smgt_get_all_data('exam');

if (!empty($exam_list)) {

	foreach ($exam_list as $exam) {



		$exam_start_date = mj_smgt_getdate_in_input_box($exam->exam_start_date);

		$exam_end_date = mj_smgt_getdate_in_input_box($exam->exam_end_date);

		$i = 1;

		$exam_title = $exam->exam_name;

		$exam_term =  get_the_title($exam->exam_term);



		if(!empty($exam->section_id))

		{

			$section_name = mj_smgt_get_section_name($exam->section_id);

		}

		else

		{

			$section_name = "N/A";

		}
		$class_name = smgt_get_class_section_name_wise($exam->class_id,$exam->section_id);
		if(!empty($exam->exam_comment))

		{

			$comment = $exam->exam_comment;

		}

		else

		{

			$comment = "N/A";

		}
		$to =  esc_html__( "To", "school-mgt" );

		$start_to_end_date = mj_smgt_getdate_in_input_box($exam_start_date).' '.$to.' '.mj_smgt_getdate_in_input_box($exam_end_date);
		$total_mark = $exam->total_mark;

		$passing_mark = $exam->passing_mark;

		$notive_array[] = array(

			'exam_title' => $exam_title,

			'exam_term' => $exam_term,

			'class_name' => $class_name,

			'total_mark' => $total_mark,

			'passing_mark' => $passing_mark,

			'comment' => $comment,

			'start_date' => $start_to_end_date,

			'event_title' => esc_html__( 'Exam Details', 'school-mgt' ),

			'title' => $exam->exam_name,

			'description' => 'exam',

			'start' => mysql2date('Y-m-d', $exam_start_date),

			'end' => date('Y-m-d', strtotime($exam_end_date . ' +' . $i . ' days')),

			'color' => '#5840bb',

		);

	}

}

?>

<style>

	.ui-dialog-titlebar-close

	{

		font-size: 13px !important;

		border: 1px solid transparent !important;

		border-radius: 0 !important;

		outline: 0!important;

		background-color: #fff !important;

		background-image: url("<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>");

		background-repeat: no-repeat;

		float: right;

		color: #fff !important;

		width: 10% !important;

		height: 30px !important;

	}

	.ui-widget-header {

		border: 0px solid #aaaaaa !important;

		background: unset !important;

		font-size: 22px !important;

		color: #333333 !important;

		font-weight: 500 !important;

		font-style: normal!important;

		font-family: Poppins!important;

	}

	.ui-dialog {

		background: #ffffff none repeat scroll 0 0;

		border-radius: 4px;

		box-shadow: 0 0 5px rgb(0 0 0 / 90%);

		cursor: default;

	}

	@media (max-width: 768px)

	{

		.ui-dialog.ui-corner-all.ui-widget.ui-widget-content.ui-front.ui-draggable.ui-resizable

		{

			width: 332px !important;

			left: -131px !important;

		}

	}
	

</style>
<?php
if(is_rtl())
{
	?>
	<style>
		.ui-dialog .ui-dialog-title
		{
			float: right !important;
		}
		.ui-dialog .ui-dialog-titlebar-close
		{
			right: unset !important;
		}
	</style>
	<?php
}
?>
<!--------------- NOTICE CALENDER POPUP ---------------->

<div id="event_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

	<div class="penal-body">

		<div class="row">

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Title','school-mgt');?></label><br>

				<label for="" class="label_value" id="notice_title"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Start Date To End Date','school-mgt');?></label><br>

				<label for="" class="label_value" id="start_to_end_date"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Notice For','school-mgt');?></label><br>

				<label for="" class="label_value" id="notice_for"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Class Name','school-mgt');?></label><br>

				<label for="" class="label_value" id="class_name_111"></label>

			</div>

			<div class="col-md-12 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Comment','school-mgt');?></label><br>

				<label for="" class="label_value " id="discription"> </label>

			</div>

		</div>

	</div>

</div>

<!--------------- HOLIDAY CALENDER POPUP ---------------->

<div id="holiday_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

	<div class="penal-body">

		<div class="row">

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Title','school-mgt');?></label><br>

				<label for="" class="label_value" id="holiday_title"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Start Date To End Date','school-mgt');?></label><br>

				<label for="" class="label_value" id="start_to_end_date"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Status','school-mgt');?></label><br>

				<label for="" class="label_value" id="status" style="color:green !important;"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Description','school-mgt');?></label><br>

				<label for="" class="label_value" id="holiday_comment"></label>

			</div>

		</div>

	</div>

</div>



<!--------------- EXAM CALENDER POPUP ---------------->

<div id="exam_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

	<div class="penal-body">

		<div class="row">

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Title','school-mgt');?></label><br>

				<label for="" class="label_value" id="exam_title"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Term','school-mgt');?></label><br>

				<label for="" class="label_value" id="exam_term"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Class','school-mgt');?></label><br>

				<label for="" class="label_value" id="class_name_123"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Start To End Date','school-mgt');?></label><br>

				<label for="" class="label_value" id="start_date"></label>

			</div>


			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Total Marks','school-mgt');?></label><br>

				<label for="" class="label_value" id="total_mark"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Passing Marks','school-mgt');?></label><br>

				<label for="" class="label_value" id="passing_mark"></label>

			</div>

			<div class="col-md-12 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Comment','school-mgt');?></label><br>

				<label for="" class="label_value" id="comment"></label>

			</div>

		</div>

	</div>

</div>



<!--------------- EVENT CALENDER POPUP ---------------->

<div id="event_list_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

	<div class="penal-body">

		<div class="row">

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Title','school-mgt');?></label><br>

				<label for="" class="label_value" id="event_heading"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Start Date','school-mgt');?></label><br>

				<label for="" class="label_value" id="event_start_date_calender"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('End Date','school-mgt');?></label><br>

				<label for="" class="label_value" id="event_end_date_calender"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Start Time','school-mgt');?></label><br>

				<label for="" class="label_value" id="event_start_time_calender"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('End Time','school-mgt');?></label><br>

				<label for="" class="label_value" id="event_end_time_calender"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_attr_e('Description','school-mgt');?></label><br>

				<label for="" class="label_value" id="event_comment_calender"></label>

			</div>

		</div>

	</div>

</div>

<!DOCTYPE html>

	<html lang="en"><!-- HTML START -->

		<head>

		</head>

		<script>

			var calendar_laungage ="<?php echo mj_smgt_calander_laungage();?>";

			// $ = jQuery.noConflict();

			document.addEventListener('DOMContentLoaded', function() {

				var calendarEl = document.getElementById('calendar');

				var calendar = new FullCalendar.Calendar(calendarEl,{

					initialView: 'dayGridMonth',

					dayMaxEventRows: 1,

					locale: calendar_laungage,

					headerToolbar: {

						left: 'prev,today,next ',

						center: 'title',

						right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'

					},

					events: <?php echo json_encode($notive_array); ?>,

					eventClick:  function(event, jsEvent, view)

					{
						//----------FOR ZOOM ----------//

						if(event.event._def.extendedProps.description=='notice')

						{

							$("#event_booked_popup #notice_title").html(event.event._def.extendedProps.notice_title);

							$("#event_booked_popup #start_to_end_date").html(event.event._def.extendedProps.start_to_end_date);

							$("#event_booked_popup #discription").html(event.event._def.extendedProps.notice_comment);

							$("#event_booked_popup #notice_for").html(event.event._def.extendedProps.notice_for);

							$("#event_booked_popup #class_name_111").html(event.event._def.extendedProps.class_name);

							$( "#event_booked_popup" ).removeClass( "display_none" );

							$("#event_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:300 });

						}

						if(event.event._def.extendedProps.description=='holiday')

						{

							$("#holiday_booked_popup #holiday_title").html(event.event._def.extendedProps.holiday_title);

							$("#holiday_booked_popup #start_to_end_date").html(event.event._def.extendedProps.start_to_end_date);

							$("#holiday_booked_popup #status").html(event.event._def.extendedProps.status);

							$("#holiday_booked_popup #holiday_comment").html(event.event._def.extendedProps.holiday_comment);

							$( "#holiday_booked_popup" ).removeClass( "display_none" );

							$("#holiday_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:250 });

						}

						if(event.event._def.extendedProps.description=='exam')

						{

							$("#exam_booked_popup #exam_title").html(event.event._def.extendedProps.exam_title);

							$("#exam_booked_popup #start_date").html(event.event._def.extendedProps.start_date);

							$("#exam_booked_popup #end_date").html(event.event._def.extendedProps.end_date);

							$("#exam_booked_popup #section_name_123").html(event.event._def.extendedProps.section_name);

							$("#exam_booked_popup #class_name_123").html(event.event._def.extendedProps.class_name);

							$("#exam_booked_popup #passing_mark").html(event.event._def.extendedProps.passing_mark);

							$("#exam_booked_popup #total_mark ").html(event.event._def.extendedProps.total_mark);

							$("#exam_booked_popup #exam_term ").html(event.event._def.extendedProps.exam_term);

							$("#exam_booked_popup #comment ").html(event.event._def.extendedProps.comment);

							$( "#exam_booked_popup" ).removeClass( "display_none" );

							$("#exam_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:350 });

						}

						if(event.event._def.extendedProps.description=='event')

						{

							$("#event_list_booked_popup #event_heading").html(event.event._def.extendedProps.event_heading);

							$("#event_list_booked_popup #event_start_date_calender").html(event.event._def.extendedProps.event_start_date);

							$("#event_list_booked_popup #event_end_date_calender").html(event.event._def.extendedProps.event_end_date);

							$("#event_list_booked_popup #event_comment_calender").html(event.event._def.extendedProps.event_comment);

							$("#event_list_booked_popup #event_start_time_calender ").html(event.event._def.extendedProps.event_start_time);

							$("#event_list_booked_popup #event_end_time_calender ").html(event.event._def.extendedProps.event_end_time);

							$( "#event_list_booked_popup" ).removeClass( "display_none" );

							$("#event_list_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:350 });

						}

					},

				});

				calendar.render();

			});

		</script>

		<!-- body part start  -->

		<body>

			<!--task-event POP up code -->

			

			<?php

			if ( is_rtl() )

			{

				$rtl_left_icon_class = "fa-chevron-left";

			}

			else

			{

				$rtl_left_icon_class = "fa-chevron-right";

			}



			$role=mj_smgt_get_user_role(get_current_user_id());

			if($role == "management")

			{

				$admission_page = 'admission';

				$admission_access = mj_smgt_get_userrole_wise_filter_access_right_array($admission_page);



				$student_page='student';

				$student_access = mj_smgt_get_userrole_wise_filter_access_right_array($student_page);



				$teacher_page='teacher';

				$teacher_access = mj_smgt_get_userrole_wise_filter_access_right_array($teacher_page);



				$supportstaff_page='supportstaff';

				$supportstaff_access = mj_smgt_get_userrole_wise_filter_access_right_array($supportstaff_page);



				$parent_page='parent';

				$parent_access = mj_smgt_get_userrole_wise_filter_access_right_array($parent_page);



				$class_page='class';

				$class_access = mj_smgt_get_userrole_wise_filter_access_right_array($class_page);



				$schedule_page='schedule';

				$schedule_access = mj_smgt_get_userrole_wise_filter_access_right_array($schedule_page);



				$virtual_classroom_page='virtual_classroom';

				$virtual_classroom_access = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page);



				$subject_page='subject';

				$subject_access = mj_smgt_get_userrole_wise_filter_access_right_array($subject_page);



				$exam_page='exam';

				$exam_access = mj_smgt_get_userrole_wise_filter_access_right_array($exam_page);



				$exam_hall_page='exam_hall';

				$exam_hall_access = mj_smgt_get_userrole_wise_filter_access_right_array($exam_hall_page);



				$manage_marks_page='manage_marks';

				$manage_marks_access = mj_smgt_get_userrole_wise_filter_access_right_array($manage_marks_page);



				$grade_page='grade';

				$grade_access = mj_smgt_get_userrole_wise_filter_access_right_array($grade_page);



				$homework_page='homework';

				$homework_access = mj_smgt_get_userrole_wise_filter_access_right_array($homework_page);



				$attendance_page='attendance';

				$attendance_access = mj_smgt_get_userrole_wise_filter_access_right_array($attendance_page);



				$document_page='document';

				$document_access = mj_smgt_get_userrole_wise_filter_access_right_array($document_page);


				$tax_page='tax';

				$tax_access = mj_smgt_get_userrole_wise_filter_access_right_array($tax_page);


				$feepayment_page='feepayment';

				$feepayment_access = mj_smgt_get_userrole_wise_filter_access_right_array($feepayment_page);



				$payment_page='payment';

				$payment_access = mj_smgt_get_userrole_wise_filter_access_right_array($payment_page);



				$library_page='library';

				$library_access = mj_smgt_get_userrole_wise_filter_access_right_array($library_page);



				$hostel_page='hostel';

				$hostel_access = mj_smgt_get_userrole_wise_filter_access_right_array($hostel_page);



				$leave_page='leave';

				$leave_access = mj_smgt_get_userrole_wise_filter_access_right_array($leave_page);



				$transport_page='transport';

				$transport_access = mj_smgt_get_userrole_wise_filter_access_right_array($transport_page);



				$report_page='report';

				$report_access = mj_smgt_get_userrole_wise_filter_access_right_array($report_page);

				

				$advance_report='advance_report';

				$advance_report_access = mj_smgt_get_userrole_wise_filter_access_right_array($advance_report);



				$notice_page='notice';

				$notice_access = mj_smgt_get_userrole_wise_filter_access_right_array($notice_page);



				$message_page='message';

				$message_access = mj_smgt_get_userrole_wise_filter_access_right_array($message_page);



				$holiday_page='holiday';

				$holiday_access = mj_smgt_get_userrole_wise_filter_access_right_array($holiday_page);



				$notification_page='notification';

				$notification_access = mj_smgt_get_userrole_wise_filter_access_right_array($notification_page);



				$event_page='event';

				$event_access = mj_smgt_get_userrole_wise_filter_access_right_array($event_page);



				$custom_field_page='custom_field';

				$custom_field_access = mj_smgt_get_userrole_wise_filter_access_right_array($custom_field_page);



				$sms_setting_page='sms_setting';

				$sms_setting_access = mj_smgt_get_userrole_wise_filter_access_right_array($sms_setting_page);



				$general_settings_page='general_settings';

				$general_settings_access = mj_smgt_get_userrole_wise_filter_access_right_array($general_settings_page);



				$email_template_page='email_template';

				$email_template_access = mj_smgt_get_userrole_wise_filter_access_right_array($email_template_page);



				$migration_page='migration';

				$migration_access =mj_smgt_get_userrole_wise_filter_access_right_array($migration_page);



				$student_view_access = $student_access['view'];

				$student_add_access = $student_access['add'];



				$admission_view_access = $admission_access['view'];

				$admission_add_access = $admission_access['add'];



				$staff_view_access = $supportstaff_access['view'];

				$staff_add_access = $supportstaff_access['add'];



				$teacher_view_access = $teacher_access['view'];

				$teacher_add_access = $teacher_access['add'];



				$parent_view_access = $parent_access['view'];

				$parent_add_access = $parent_access['add'];



				$exam_view_access = $exam_access['view'];

				$exam_add_access = $exam_access['add'];



				$hall_view_access = $exam_hall_access['view'];

				$hall_add_access = $exam_hall_access['add'];



				$mark_view_access = $manage_marks_access['view'];

				$mark_add_access = $manage_marks_access['add'];



				$grade_view_access = $grade_access['view'];

				$grade_add_access = $grade_access['add'];



				$homework_view_access = $homework_access['view'];

				$homework_add_access = $homework_access['add'];



				$attendance_view_access = $attendance_access['view'];

				$attendance_add_access = $attendance_access['add'];



				$document_view_access = $document_access['view'];

				$document_add_access = $document_access['add'];



				$fees_view_access = $feepayment_access['view'];

				$fees_add_access = $feepayment_access['add'];


				$tax_view_access = $tax_access['view'];
				$tax_add_access = $tax_access['add'];



				$payment_view_access = $payment_access['view'];

				$payment_add_access = $payment_access['add'];



				$library_view_access = $library_access['view'];

				$library_add_access = $library_access['add'];



				$leave_view_access  = $leave_access['view'];

				$leave_add_access  = $leave_access['add'];



				$hostel_view_access = $hostel_access['view'];

				$hostel_add_access = $hostel_access['add'];



				$transport_view_access = $transport_access['view'];

				$transport_add_access = $transport_access['add'];



				$report_view_access = $report_access['view'];

				$report_add_access = $report_access['add'];


				// $advance_report_view_access = $advance_report_access['view'];

				// $advance_report_add_access = $advance_report_access['add'];


				$notice_view_access = $notice_access['view'];

				$notice_add_access = $notice_access['add'];



				$message_view_access = $message_access['view'];

				$message_add_access = $message_access['add'];



				$holiday_view_access = $holiday_access['view'];

				$holiday_add_access = $holiday_access['add'];



				$notification_view_access = $notification_access['view'];

				$notification_add_access = $notification_access['add'];



				$event_view_access = $event_access['view'];

				$event_add_access = $event_access['add'];



				$field_view_access = $custom_field_access['view'];

				$field_add_access = $custom_field_access['add'];



				$sms_view_access = $sms_setting_access['view'];

				$sms_add_access = $sms_setting_access['add'];



				$mail_view_access = $email_template_access['view'];

				$mail_add_access = $email_template_access['add'];



				$class_view_access = $class_access['view'];

				$class_add_access = $class_access['add'];



				$schedule_view_access = $schedule_access['view'];

				$schedule_add_access = $schedule_access['add'];



				$virtual_class_view_access = $virtual_classroom_access['view'];

				$virtual_class_add_access = $virtual_classroom_access['add'];



				$subject_view_access = $subject_access['view'];

				$subject_add_access = $subject_access['add'];



				$migration_view_access = $migration_access['view'];

				$migration_add_access = $migration_access['add'];

			}

			else

			{

				$student_view_access = 1;

				$student_add_access = 1;



				$admission_view_access = 1;

				$admission_add_access = 1;



				$staff_view_access = 1;

				$staff_add_access = 1;



				$teacher_view_access = 1;

				$teacher_add_access = 1;



				$parent_view_access = 1;

				$parent_add_access = 1;



				$exam_view_access = 1;

				$exam_add_access = 1;



				$hall_view_access = 1;

				$hall_add_access = 1;



				$mark_view_access = 1;

				$mark_add_access = 1;



				$grade_view_access = 1;

				$grade_add_access = 1;



				$homework_view_access = 1;

				$homework_add_access = 1;



				$attendance_view_access = 1;

				$attendance_add_access = 1;



				$document_view_access = 1;

				$document_add_access = 1;

				$fees_view_access = 1;
				$fees_add_access = 1;

				$tax_view_access = 1;
				$tax_add_access = 1;

				$payment_view_access = 1;
				$payment_add_access = 1;



				$library_view_access = 1;

				$library_add_access = 1;



				$leave_view_access = 1;

				$leave_add_access = 1;



				$hostel_view_access = 1;

				$hostel_add_access = 1;



				$transport_view_access = 1;

				$transport_add_access = 1;



				$report_view_access = 1;

				$report_add_access = 1;


				$advance_report_view_access = 1;

				$advance_report_add_access = 1;


				$notice_view_access = 1;

				$notice_add_access = 1;



				$message_view_access = 1;

				$message_add_access = 1;



				$holiday_view_access = 1;

				$holiday_add_access = 1;



				$notification_view_access = 1;

				$notification_add_access = 1;



				$event_view_access = 1;

				$event_add_access = 1;



				$field_view_access = 1;

				$field_add_access = 1;



				$sms_view_access = 1;

				$sms_add_access = 1;



				$mail_view_access = 1;

				$mail_add_access = 1;



				$class_view_access = 1;

				$class_add_access = 1;



				$schedule_view_access = 1;

				$schedule_add_access = 1;



				$virtual_class_view_access = 1;

				$virtual_class_add_access = 1;



				$subject_view_access = 1;

				$subject_add_access = 1;



				$migration_view_access = 1;

				$migration_add_access = 1;

			}
			if($_REQUEST['page'] == 'smgt_school')
			{
				?>
				<div class="popup-bg">

					<div class="overlay-content content_width">

						<div class="modal-content d-modal-style">

							<div class="task_event_list">

							</div>

							<div class="category_list">
							</div>

						</div>

					</div>

				</div>
				<?php
				if(get_option('smgt_enable_video_popup_show') == 'yes')
				{
				?>
				<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/H2oDKfMVN-I?si=1kWparkE0ekoLYm3";?>" title="School Overview">
					<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
				</a>
				<?php
				}
			}
			?>

			<div class="row smgt-header plugin_code_start admin_dashboard_main_div" style="margin: 0;">

				<!--HEADER PART IN SET LOGO & TITEL START-->

				<div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 padding_0">

					<a href="<?php echo admin_url().'admin.php?page=smgt_school';?>" class='smgt-logo'>

						<img src="<?php  echo get_option( 'smgt_system_logo' ); ?>" class="system_logo_height_width">

					</a>



					<!--  toggle button && desgin start-->

					<button type="button" id="sidebarCollapse" class="navbar-btn">

						<span></span>

						<span></span>

						<span></span>

					</button>

					<!--  toggle button && desgin end-->

				</div>

				<div class="col-sm-12 col-md-12 col-lg-10 col-xl-10 smgt-right-heder">

					<div class="row">

						<div class="col-sm-8 col-md-8 col-lg-8 col-xl-8 name_and_icon_dashboard align_items_unset_res smgt_header_width">

							<div class="smgt_title_add_btn">

								<!-- Page Name  -->

								<h3 class="smgt-addform-header-title rtl_menu_backarrow_float">

									<?php

										$school_obj = new School_Management ( get_current_user_id () );

										$page_name = "";

										$active_tab = "";

										$action = "";

										if(!empty($_REQUEST['page']))

										{

											$page_name = $_REQUEST ['page'];

										}

										if(!empty($_REQUEST['tab']))

										{

											$active_tab = $_REQUEST['tab'];

										}

										if(!empty($_REQUEST['action']))

										{

											$action = $_REQUEST['action'];

										}

										$role=$school_obj->role;



										if(isset( $_REQUEST ['page'] ) && $_REQUEST ['page'] == 'smgt_school')

										{

											echo esc_html_e( 'Welcome to Dashboard', 'school-mgt' ).', ';

											if($role == 'management')

											{

												echo esc_html_e( "Management", 'school-mgt' );

											}

											else

											{

												echo esc_html_e('Admin', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_student')

										{

											if($active_tab == 'addstudent' || $active_tab == 'view_student')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_student&tab=studentlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit'){

													echo esc_html_e('Edit Student', 'school-mgt' );

												}

												elseif($action == 'view_student'){

													echo esc_html_e('View Student', 'school-mgt' );

												}

												else{

													echo esc_html_e( 'Add Student', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Student', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_teacher')

										{

											if($active_tab == 'addteacher' || $active_tab == 'view_teacher')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_teacher&tab=teacherlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit'){

													echo esc_html_e('Edit Teacher', 'school-mgt' );

												}

												elseif($active_tab == 'view_teacher'){

													echo esc_html_e('View Teacher', 'school-mgt' );

												}

												else{

													echo esc_html_e( 'Add Teacher', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Teacher', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_parent')

										{

											if($active_tab == 'addparent' || $active_tab == 'view_parent')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_parent&tab=parentlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit'){

													echo esc_html_e('Edit Parent', 'school-mgt' );

												}

												elseif($action == 'view_parent'){

													echo esc_html_e('View Parent', 'school-mgt' );

												}

												else{

													echo esc_html_e( 'Add Parent', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Parent', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_supportstaff')

										{

											if($active_tab == 'addsupportstaff' || $active_tab == 'view_supportstaff')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Support Staff', 'school-mgt' );

												}

												elseif($action == 'view_supportstaff')

												{

													echo esc_html_e('View Support Staff', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Support Staff', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Support Staff', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_student_homewrok')

										{

											if($active_tab == 'addhomework')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													?>

													<?php

													echo esc_html_e('Homework', 'school-mgt' );

												}

												else

												{

													?>

													<?php

													echo esc_html_e( 'Homework', 'school-mgt' );

												}

											}

											elseif($active_tab == 'view_stud_detail')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												echo esc_html_e( 'View Submission', 'school-mgt' );

											}

											else

											{

												echo esc_html_e( 'Homework', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_library')

										{

											if($active_tab == 'booklist' || $active_tab == 'addbook' )

											{

												echo esc_html_e('Book', 'school-mgt' );

											}

											elseif($active_tab == 'issuelist' || $active_tab == 'issuebook')

											{

												echo esc_html_e('Issue Book', 'school-mgt' );

											}

											else

											{

												echo esc_html_e( 'Library', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_class')

										{

											if($active_tab == 'addclass')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_class&tab=classlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Class', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Class', 'school-mgt' );

												}

											}
											elseif($active_tab == 'class_wise_student_list')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_class&tab=classlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php
												echo mj_smgt_get_class_name($_REQUEST['class_id']).' '.esc_html__( 'Student List', 'school-mgt' );

											}

											else

											{

												echo esc_html_e( 'Class', 'school-mgt' );

											}



										}

										elseif($page_name == 'smgt_admission')

										{

											if($active_tab == 'admission_form' || $active_tab == 'view_admission')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_admission';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Admission', 'school-mgt' );

												}

												elseif($action == 'view_admission')

												{

													echo esc_html_e( 'View Admission', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Admission', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Admission', 'school-mgt' );

											}



										}

										elseif($page_name == 'smgt_route')

										{

											if($active_tab == 'addroute')

											{

												?>

												<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_route&tab=route_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a> -->

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Class Time Table', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Class Time Table', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Class Time Table', 'school-mgt' );

											}



										}

										elseif($page_name == 'smgt_virtual_classroom')

										{

											if($active_tab == 'edit_meeting')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_virtual_classroom';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Virtual Classroom', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Virtual Classroom', 'school-mgt' );

												}

											}

											elseif($active_tab == 'view_past_participle_list')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_virtual_classroom';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												echo esc_html_e( 'Participant List', 'school-mgt' );

											}

											else

											{

												echo esc_html_e( 'Virtual Classroom', 'school-mgt' );

											}



										}

										elseif($page_name == 'smgt_exam')

										{

											if($active_tab == 'addexam')

											{

												?>

												<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_exam&tab=examlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a> -->

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Exam', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Exam', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Exam', 'school-mgt' );

											}



										}

										elseif($page_name == 'smgt_Subject')

										{

											if($active_tab == 'addsubject')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_Subject&tab=Subject';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Subject', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Subject', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Subject', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_hall')

										{

											if($active_tab == 'addhall')

											{

												?>

												<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_hall&tab=hall_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a> -->

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Exam Hall', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Exam Hall', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Exam Hall', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_grade')

										{

											if($active_tab == 'addgrade')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_grade&tab=gradelist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Grade', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Grade', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Grade', 'school-mgt' );

											}

										}



										elseif($page_name == 'smgt_result')

										{

											if($active_tab == 'result')

											{

												echo esc_html_e( 'Manage Marks', 'school-mgt' );

											}

											elseif($active_tab == 'export_marks')

											{

												echo esc_html_e( 'Export Marks', 'school-mgt' );

											}

											elseif($active_tab == 'multiple_subject_marks')

											{

												echo esc_html_e( 'Multiple Subject Marks', 'school-mgt' );

											}

											else

											{

												echo esc_html_e( 'Manage Marks', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_attendence')

										{

											if($active_tab == 'student_attendance')

											{

												echo esc_html_e( 'Student Attendance', 'school-mgt' );

											}

											else

											{

												echo esc_html_e( 'Teacher Attendance', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_library')

										{

											echo esc_html_e( 'Library', 'school-mgt' );

										}

										//--- Leave Module start ---//

										elseif($page_name == 'smgt_leave')

										{

											if($active_tab == 'add_leave')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_leave&tab=leave_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Leave', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Leave', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Leave', 'school-mgt' );

											}

										}

										//--- Leave Module End ---//

										//Hostel Module start

										elseif($page_name == 'smgt_hostel')

										{

											if($page_name == 'smgt_hostel' && $active_tab == 'hostel_list')

											{

												echo esc_html_e( 'Hostel', 'school-mgt' );

											}

											elseif($page_name == 'smgt_hostel' && $active_tab == 'room_list')

											{

												echo esc_html_e( 'Room', 'school-mgt' );

											}

											elseif($page_name == 'smgt_hostel' && $active_tab == 'bed_list')

											{

												echo esc_html_e( 'Beds', 'school-mgt' );

											}

											elseif($page_name == 'smgt_hostel' && $active_tab == 'add_hostel')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=hostel_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Hostel', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Hostel', 'school-mgt' );

												}

											}

											elseif($page_name == 'smgt_hostel' && $active_tab == 'hostel_room_list')
											{
												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=hostel_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php
												echo esc_html__( 'Hostel Room List', 'school-mgt' );

											}
											elseif($page_name == 'smgt_hostel' && $active_tab == 'add_room')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=room_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit_room')

												{

													echo esc_html_e('Edit Room', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Room', 'school-mgt' );

												}

											}
											elseif($page_name == 'smgt_hostel' && $active_tab == 'assign_bed_list')
											{
												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=room_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php
												echo esc_html_e('Assign Bed List', 'school-mgt' );
											}

											elseif($page_name == 'smgt_hostel' && $active_tab == 'assign_room')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=room_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												echo esc_html_e('Assign Room', 'school-mgt' );

											}

											elseif($page_name == 'smgt_hostel' && $active_tab == 'add_bed')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=bed_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit_bed')

												{

													echo esc_html_e('Edit Beds', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Beds', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Hostel', 'school-mgt' );

											}



										}

										//Hostel Module End

										elseif($page_name == 'smgt_notice')

										{

											if($active_tab == 'addnotice')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_notice&tab=noticelist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Notice', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Notice', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Notice', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_event')

										{

											if($active_tab == 'add_event')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_event&tab=eventlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e('Edit Event', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Event', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Event', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_notification')

										{

											if($active_tab == 'addnotification')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_notification&tab=notificationlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												echo esc_html_e( 'Add Notification', 'school-mgt' );

											}

											else

											{

												echo esc_html_e( 'Notification', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_holiday')

										{

											if($active_tab == 'addholiday')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_holiday&tab=holidaylist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit'){

													echo esc_html_e('Edit Holiday', 'school-mgt' );

												}

												else{

													echo esc_html_e( 'Add Holiday', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Holiday', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_message')

										{

											echo esc_html_e( 'Message', 'school-mgt' );

										}

										elseif($page_name == 'smgt_Migration')

										{

											echo esc_html_e( 'Migration', 'school-mgt' );

										}

										elseif($page_name == 'smgt_payment')

										{

											if($active_tab == 'payment')

											{

												echo esc_html_e( 'Payment', 'school-mgt' );

											}

											elseif($active_tab == 'incomelist')

											{

												echo esc_html_e( 'Income', 'school-mgt' );

											}

											elseif($active_tab == 'expenselist')

											{

												echo esc_html_e( 'Expense', 'school-mgt' );

											}



											if($active_tab == 'addpayment')

											{

												?>

												<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=payment';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a> -->

												<?php

												if($action == 'edit')

												{

													echo esc_html_e( 'Payment', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Payment', 'school-mgt' );

												}



											}

											elseif($active_tab == 'addincome')

											{

												?>

												<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=incomelist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a> -->

												<?php

												if($action == 'edit')

												{

													echo esc_html_e( 'Income', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Income', 'school-mgt' );

												}



											}

											elseif($active_tab == 'addexpense')

											{

												?>

												<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=expenselist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a> -->

												<?php

												if($action == 'edit')

												{

													echo esc_html_e( 'Expense', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Expense', 'school-mgt' );

												}

											}

											elseif($active_tab == 'view_invoice')

											{

												if($_REQUEST['invoice_type'] == 'invoice')

												{

													?>

													<a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=payment';?>'>

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

													</a>

													<?php

												}

												elseif($_REQUEST['invoice_type'] == 'income')

												{

													?>

													<a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=incomelist';?>'>

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

													</a>

													<?php

												}

												elseif($_REQUEST['invoice_type'] == 'expense')

												{

													?>

													<a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=expenselist';?>'>

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

													</a>

													<?php

												}

												echo esc_html_e( 'View Invoice', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_fees_payment')
										{

											if($active_tab == 'feeslist')

											{

												echo esc_html_e( 'Fees Type', 'school-mgt' );

											}

											elseif($active_tab == 'feespaymentlist')

											{

												echo esc_html_e( 'Fees Payment', 'school-mgt' );

											}

											elseif($active_tab == 'recurring_feespaymentlist')

											{

												echo esc_html_e( 'Recurring Fees Payment', 'school-mgt' );

											}



											if($active_tab == 'addfeetype')

											{

												?>

												<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=feeslist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a> -->

												<?php

												if($action == 'edit')

												{

													echo esc_html_e( 'Fees Type', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Fees Type', 'school-mgt' );

												}



											}

											elseif($active_tab == 'addpaymentfee')

											{

												?>

												<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=feespaymentlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a> -->

												<?php

												if($action == 'edit')

												{

													echo esc_html_e( 'Fees Payment', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Fees Payment', 'school-mgt' );

												}



											}
											elseif($active_tab == 'addrecurringpayment')

											{

												?>

												<?php
												if($action == 'edit')
												{

													echo esc_html_e( 'Edit Recurring Fees Payment', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Recurring Fees Payment', 'school-mgt' );

												}



											}

											elseif($active_tab == 'view_fesspayment')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=feespaymentlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												echo esc_html_e( 'View Fees Payment Invoice', 'school-mgt' );

											}

										}
										elseif($page_name == 'smgt_tax')
										{

											if($active_tab == 'add_tax')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_tax&tab=tax';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e( 'Edit Tax', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Tax', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Tax', 'school-mgt' );

											}

										}
										elseif($page_name == 'smgt_transport')
										{

											if($active_tab == 'addtransport')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_transport&tab=transport';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e( 'Edit Transport', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Transport', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Transport', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_report')

										{

											echo esc_html_e( 'Reports', 'school-mgt' );

										}

										elseif($page_name == 'smgt_advance_report')

										{

											echo esc_html_e( 'Advance Reports', 'school-mgt' );

										}

										elseif($page_name == 'smgt_setup')

										{

											echo esc_html_e( 'License settings', 'school-mgt' );

										}

										elseif($page_name == 'custom_field')

										{

											if($active_tab == 'add_custome_field')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=custom_field&tab=custome_field_list';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit')

												{

													echo esc_html_e( 'Edit Custom Field', 'school-mgt' );

												}

												else

												{

													echo esc_html_e( 'Add Custom Field', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Custom Fields', 'school-mgt' );

											}

										}

										elseif($page_name == 'smgt_sms-setting')

										{

											echo esc_html_e( 'SMS Settings', 'school-mgt' );

										}

										elseif($page_name == 'smgt_email_template')

										{

											echo esc_html_e( 'Email Template', 'school-mgt' );

										}

										elseif($page_name == 'smgt_access_right')

										{

											echo esc_html_e( 'Access Right', 'school-mgt' );

										}

										elseif($page_name == 'smgt_system_videos')

										{

											echo esc_html_e( 'How To Videos', 'school-mgt' );

										}

										elseif($page_name == 'smgt_gnrl_settings')

										{

											echo esc_html_e( 'General Settings', 'school-mgt' );

										}

										elseif($page_name == 'smgt_document')

										{

											if($active_tab == 'add_document')

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_document&tab=documentlist';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

												</a>

												<?php

												if($action == 'edit'){

													echo esc_html_e('Edit Document', 'school-mgt' );

												}

												else{

													echo esc_html_e( 'Add Document', 'school-mgt' );

												}

											}

											else

											{

												echo esc_html_e( 'Documents', 'school-mgt' );

											}

										}

										else

										{

											echo $page_name;

										}



									?>

								</h3>

								<div class="smgt_add_btn"><!-------- Plus button div -------->

									<?php

										if($page_name == "smgt_student" && $active_tab != 'addstudent' && $action != 'view_student')

										{

											if($student_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_student&tab=addstudent';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}



										}

										elseif($page_name == "smgt_admission" && $active_tab != 'admission_form' && $active_tab != 'view_admission')

										{

											if($admission_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_admission&tab=admission_form';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_class" && $active_tab != 'addclass' && $active_tab != 'class_wise_student_list')

										{

											if($class_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_class&tab=addclass';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_route" && $active_tab != 'addroute')

										{

											if($schedule_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_route&tab=addroute';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_teacher" && $active_tab != 'addteacher' && $active_tab != 'view_teacher' && $action != 'view_teacher')

										{

											if($teacher_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_teacher&tab=addteacher';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_parent" && $active_tab != 'addparent' && $action != 'view_parent')

										{

											if($parent_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_parent&tab=addparent';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_supportstaff" && $active_tab != 'addsupportstaff' && $action != 'view_supportstaff')

										{

											if($staff_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_supportstaff&tab=addsupportstaff';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_student_homewrok" && $active_tab != 'addhomework' && $active_tab != 'view_stud_detail')

										{

											if($homework_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_student_homewrok&tab=addhomework';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_virtual_classroom" && $active_tab != 'edit_meeting' && $active_tab != 'view_past_participle_list')

										{

											if($virtual_class_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_route&tab=addroute';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_Subject" && $active_tab != 'addsubject')

										{

											if($subject_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_Subject&tab=addsubject';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_exam" && $active_tab != 'addexam')

										{

											if($exam_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_exam&tab=addexam';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_hall" && $active_tab != 'addhall')

										{

											if($hall_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hall&tab=addhall';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_library" && $active_tab == 'issuelist')

										{

											if($library_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_library&tab=issuebook';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_library" && $active_tab == 'booklist')

										{

											if($library_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_library&tab=addbook';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_grade" && $active_tab != 'addgrade')

										{

											if($grade_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_grade&tab=addgrade';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == 'smgt_hostel' && $active_tab == 'hostel_list')

										{

											if($hostel_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=add_hostel';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}
										elseif($page_name == 'smgt_tax' && $active_tab == 'tax')
										{

											if($tax_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_tax&tab=add_tax';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_payment")

										{

											if($active_tab == 'payment')

											{

												if($payment_add_access == 1)

												{

													?>

													<a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=addpayment';?>'>

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

													</a>

													<?php

												}

											}

											elseif($active_tab == 'incomelist')

											{

												if($payment_add_access == 1)

												{

													?>

													<a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=addincome';?>'>

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

													</a>

													<?php

												}

											}

											elseif($active_tab == 'expenselist')

											{

												if($payment_add_access == 1)

												{

													?>

													<a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=addexpense';?>'>

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

													</a>

													<?php

												}

											}

										}

										elseif($page_name == "smgt_fees_payment")

										{

											if($active_tab == 'feeslist')

											{

												if($fees_add_access == 1)

												{

													?>

													<a href='<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=addfeetype';?>'>

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

													</a>

													<?php

												}

											}

											elseif($active_tab == 'feespaymentlist' OR $active_tab == 'recurring_feespaymentlist' )

											{

												if($fees_add_access == 1)

												{

													?>

													<a href='<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=addpaymentfee';?>'>

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

													</a>

													<?php

												}

											}

										}

										elseif($page_name == "smgt_transport" && $active_tab != 'addtransport')

										{

											if($hostel_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_transport&tab=addtransport';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_leave" && $active_tab != 'add_leave')

										{

											if($leave_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_leave&tab=add_leave';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == 'smgt_hostel' && $active_tab == 'room_list')

										{

											if($hostel_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=add_room';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == 'smgt_hostel' && $active_tab == 'bed_list')

										{

											if($hostel_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=add_bed';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										//--- Add Btn hostel End ---//

										elseif($page_name == "smgt_notice" && $active_tab != 'addnotice')

										{

											if($notice_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_notice&tab=addnotice';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_event" && $active_tab != 'add_event')

										{

											if($event_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_event&tab=add_event';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_notification" && $active_tab != 'addnotification')

										{

											if($notification_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_notification&tab=addnotification';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_holiday" && $active_tab != 'addholiday')

										{

											if($holiday_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_holiday&tab=addholiday';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_message")

										{

											if($message_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_message&tab=compose';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "custom_field" && $active_tab != 'add_custome_field')

										{

											if($field_add_access == 1)

											{

												?>

												<a href='<?php echo admin_url().'admin.php?page=custom_field&tab=add_custome_field';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($page_name == "smgt_document" && $active_tab != 'add_document')

										{

											// if($holiday_add_access == 1)

											// {

												?>

												<a href='<?php echo admin_url().'admin.php?page=smgt_document&tab=add_document';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											// }

										}



									?>

								</div><!-------- Plus button div -------->

								<!-- End Page Name  -->

							</div>

						</div>



						<!-- Right Header  -->

						<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">

							<div class="smgt-setting-notification">

								<!-- <a href='<?php echo admin_url().'admin.php?page=smgt_gnrl_settings';?>' class="smgt-setting-notification-bg">

									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Settings.png"?>" class="smgt-right-heder-list-link">

								</a> -->
								<div class="smgt-user-dropdown smgt-setting-notification-bg setting_dropdown_responsive dashboard_header_setting_rtl" style="margin-right:15px;">

									<ul class="">

										<!-- BEGIN USER LOGIN DROPDOWN -->

										<li class="">

											<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Settings.png"?>" class="smgt-dropdown-userimg">

											</a>

											<ul class="dropdown-menu extended action_dropdawn logout_dropdown_menu logout heder-dropdown-menu setting_dropdown_menu" aria-labelledby="dropdownMenuLink">

												<li class="float_left_width_100 ">

													<a class="dropdown-item smgt-back-wp float_left_width_100" href="<?php echo admin_url().'admin.php?page=smgt_gnrl_settings';?>"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/General-setting-.png"?>" class="dashboard_popup_icon">
														<p class="dashboard_setting_dropdow">
															<?php esc_html_e( 'General Settings', 'school-mgt' ); ?>
														</p>
													</a>

												</li>
												<li class="float_left_width_100 ">

													<a class="dropdown-item smgt-back-wp float_left_width_100" href="<?php echo admin_url().'admin.php?page=custom_field';?>"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/Custom-Fields-.png"?>" class="dashboard_popup_icon">
														<p class="dashboard_setting_dropdow">
															<?php esc_html_e( 'Custom Fields', 'school-mgt' ); ?>
														</p>
													</a>

												</li>
												<li class="float_left_width_100 ">

													<a class="dropdown-item smgt-back-wp float_left_width_100" href="<?php echo admin_url().'admin.php?page=smgt_sms-setting';?>"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/SMS_Settings-.png"?>" class="dashboard_popup_icon">
														<p class="dashboard_setting_dropdow">
															<?php esc_html_e( 'SMS Settings', 'school-mgt' ); ?>
														</p>
													</a>

												</li>
												<li class="float_left_width_100 ">

													<a class="dropdown-item smgt-back-wp float_left_width_100" href="<?php echo admin_url().'admin.php?page=smgt_email_template';?>"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/Email-Template-.png"?>" class="dashboard_popup_icon">
														<p class="dashboard_setting_dropdow">
															<?php esc_html_e( 'Email Template', 'school-mgt' ); ?>
														</p>
													</a>

												</li>
												<li class="float_left_width_100 ">

													<a class="dropdown-item smgt-back-wp float_left_width_100" href="<?php echo admin_url().'admin.php?page=smgt_access_right';?>"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/Access-Rights-.png"?>" class="dashboard_popup_icon">
														<p class="dashboard_setting_dropdow">
															<?php esc_html_e( 'Access Right', 'school-mgt' ); ?>
														</p>
													</a>

												</li>

											</ul>

										</li>

										<!-- END USER LOGIN DROPDOWN -->

									</ul>

								</div>
								<a href='<?php echo admin_url().'admin.php?page=smgt_notification';?>' class="smgt-setting-notification-bg">

									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Bell-Notification.png"?>" class="smgt-right-heder-list-link">

									<spna class="between_border123 smgt-right-heder-list-link"> </span>

								</a>

								<div class="smgt-user-dropdown">

									<ul class="">

										<!-- BEGIN USER LOGIN DROPDOWN -->

										<li class="">

											<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Avatar1.png"?>" class="smgt-dropdown-userimg">

											</a>

											<ul class="dropdown-menu extended action_dropdawn logout_dropdown_menu logout heder-dropdown-menu" aria-labelledby="dropdownMenuLink">

												<li class="float_left_width_100 ">

													<a class="dropdown-item smgt-back-wp float_left_width_100" href="<?php echo admin_url();?>"><i class="fa fa-user"></i>

													<?php esc_html_e( 'Back to wp-admin', 'school-mgt' ); ?></a>

												</li>

												<li class="float_left_width_100 ">

													<a class="dropdown-item float_left_width_100" href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-sign-out"></i><?php esc_html_e( 'Log Out', 'school-mgt' ); ?></a>

												</li>

											</ul>

										</li>

										<!-- END USER LOGIN DROPDOWN -->

									</ul>

								</div>

							</div>

						</div>

						<!-- Right Header  -->

					</div>

				</div>

			</div>



			<div class="row main_page plugin_code_start admin_dashboard_menu_rs"  style="margin: 0;">

				<div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 padding_0" id="main_sidebar-bgcolor">

					<!-- menu sidebar main div strat  -->

					<div class="main_sidebar">

						<nav id="sidebar">

							<ul class='smgt-navigation navbar-collapse rs_side_menu_bgcolor' id="navbarNav">

								<?php

								if($_SESSION['cmgt_verify'] == '')

								{

								?>

								<li class="card-icon">

									<a href='<?php echo admin_url().'admin.php?page=smgt_setup';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_setup") { echo "active"; } ?>">

										<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/license.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/license.png"?>">

										<span><?php esc_html_e( 'License settings', 'school-mgt' ); ?></span>

									</a>

								</li>

								<?php

								}

								?>



								<li class="card-icon">

									<a href="<?php echo admin_url().'admin.php?page=smgt_school';?>" class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_school") { echo "active"; } ?>">

										<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/dashboards.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/dashboards.png"?>">

										<span><?php esc_html_e( 'Dashboard', 'school-mgt' ); ?></span>

									</a>

								</li>

								<?php

								if($admission_view_access == 1)

								{

									?>

									<li class="card-icon">

										<a href='<?php echo admin_url().'admin.php?page=smgt_admission';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_admission") { echo "active"; } ?>">

										<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Admission.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Admission.png"?>">

										<span><?php esc_html_e( 'Admission', 'school-mgt' ); ?></span>

										</a>

									</li>

									<?php

								}

								if($class_view_access == 1 || $schedule_view_access == 1 || $virtual_class_view_access == 1 || $subject_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon">

										<a href='#' class=" <?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_class" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_route" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_virtual_classroom" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_Subject" ) { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Class.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Class.png"?>">

											<span><?php esc_html_e('Class', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu dropdown-menu'>

											<?php

											if($class_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_class';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_class") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Class', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($schedule_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_route';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_route") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Class Routine', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($virtual_class_view_access == 1)

											{

												if(get_option('smgt_enable_virtual_classroom') == "yes")

												{

													?>

													<li class=''>

														<a href='<?php echo admin_url().'admin.php?page=smgt_virtual_classroom';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_virtual_classroom") { echo "active"; } ?>">

														<span><?php esc_html_e( 'Virtual Classroom', 'school-mgt' ); ?></span>

														</a>

													</li>

													<?php

												}

											}

											if($subject_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_Subject';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_Subject") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Subject', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											?>

										</ul>

									</li>

									<?php

								}

								if($student_view_access == 1 || $staff_view_access == 1 || $teacher_view_access == 1 || $parent_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon">

										<a href='#' class=" <?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_student" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_teacher" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_supportstaff" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_parent" ) { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon margin_left_3px" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/user-black.png"?>">

											<img class="icon margin_left_3px" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/user-white.png"?>">

											<span class="margin_left_12px"><?php esc_html_e('Users', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu dropdown-menu'>

											<?php

											if($student_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_student';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_student") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Student', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($teacher_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_teacher';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_teacher") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Teacher', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($staff_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_supportstaff';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_supportstaff") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Support Staff', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($parent_view_access == 1)

											{

												?>

												<li class="">

													<a href='<?php echo admin_url().'admin.php?page=smgt_parent';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_parent") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Parent', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											?>

										</ul>

									</li>

									<?php

								}

								if($exam_view_access == 1 || $hall_view_access == 1 || $mark_view_access == 1 || $grade_view_access == 1 || $migration_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon">

										<a href='#' class=" <?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_exam" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_hall" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_result" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_grade" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_Migration" ) { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Exam.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Exam.png"?>">

											<span class=""><?php esc_html_e('Student Evaluation', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu dropdown-menu'>

											<?php

											if($exam_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_exam';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_exam") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Exam', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($hall_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_hall';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_hall") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Exam Hall', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($mark_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_result';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_result") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Manage Marks', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($grade_view_access == 1)

											{

												?>

												<li class="">

													<a href='<?php echo admin_url().'admin.php?page=smgt_grade';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_grade") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Grade', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($migration_view_access == 1)

											{

												?>

												<li class="">

													<a href='<?php echo admin_url().'admin.php?page=smgt_Migration';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_Migration") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Migration', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											?>

										</ul>

									</li>

									<?php

								}

								if($homework_view_access == 1)

								{

									?>

									<li class="card-icon">

										<a href='<?php echo admin_url().'admin.php?page=smgt_student_homewrok';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_student_homewrok") { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/homework.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>">

											<span><?php esc_html_e( 'Homework', 'school-mgt' ); ?></span>

										</a>

									</li>

									<?php

								}



								if($attendance_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon">

										<a href='#' class='<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_attendence") { echo "active"; } ?>'>

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Attendance.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>">

											<span><?php esc_html_e( 'Attendance', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu dropdown-menu'>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_attendence&tab=student_attendance';?>' class="">

												<span><?php esc_html_e( 'Student Attendance', 'school-mgt' ); ?></span>

												</a>

											</li>



											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_attendence&tab=teacher_attendance';?>' class="">

												<span><?php esc_html_e( 'Teacher Attendance', 'school-mgt' ); ?></span>

												</a>

											</li>

										</ul>

									</li>

									<?php

								}



								//--  Start ADD document side menu page name and link  --//



								if($document_view_access == 1)

								{

									?>

									<li class="card-icon">

										<a href='<?php echo admin_url().'admin.php?page=smgt_document';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_document") { echo "active"; } ?>">

										<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/document.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/document.png"?>">

										<span><?php esc_html_e( 'Documents', 'school-mgt' ); ?></span>

										</a>

									</li>

									<?php

								}



								//-- End- ADD document side menu page name and link  --//



								//--  Start ADD leave side menu page name and link  --//

								if($leave_view_access == 1)

								{	?>

									<li class="card-icon">

										<a href='<?php echo admin_url().'admin.php?page=smgt_leave';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_leave") { echo "active"; } ?>">

										<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/leave.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/leave.png"?>">

										<span><?php esc_html_e( 'Leave', 'school-mgt' ); ?></span>

										</a>

									</li>

									<?php

								}

								//-- End- ADD leave side menu page name and link  --//

								if($tax_view_access == 1 || $fees_view_access == 1 || $payment_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon">

										<a href='#' class=" <?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_fees_payment" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_payment" ) { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Payment.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>">

											<span><?php esc_html_e('Payment', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu dropdown-menu'>

											<?php

											if($tax_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_tax&tab=tax';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_tax") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Tax', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($fees_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=feeslist';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_fees_payment") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Fees payment', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($payment_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_payment&tab=payment';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_payment") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Payment', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											?>

										</ul>

									</li>

									<?php

								}

								if($library_view_access == 1)

								{

									?>

									<li class="card-icon">

										<a href='<?php echo admin_url().'admin.php?page=smgt_library&tab=booklist';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_library") { echo "active"; } ?>">

										<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Library.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Library.png"?>">

										<span><?php esc_html_e( 'Library', 'school-mgt' ); ?></span>

										</a>

									</li>

									<?php

								}

								if($hostel_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon">

										<a href='#' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_hostel") { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/hostel.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>">

											<span><?php esc_html_e( 'Hostel', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu dropdown-menu'>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=hostel_list';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_hostel") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Hostel', 'school-mgt' ); ?></span>

												</a>

											</li>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=room_list';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_hostel") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Room', 'school-mgt' ); ?></span>

												</a>

											</li>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_hostel&tab=bed_list';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_hostel") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Beds', 'school-mgt' ); ?></span>

												</a>

											</li>

										</ul>

									</li>

									<?php

								}

								if($transport_view_access == 1)

								{

									?>

									<li class="card-icon">

										<a href='<?php echo admin_url().'admin.php?page=smgt_transport';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_transport") { echo "active"; } ?>">

										<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Transportation.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Transportation.png"?>">

										<span><?php esc_html_e( 'Transport', 'school-mgt' ); ?></span>

										</a>

									</li>

									<?php

								}

								if($report_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon report">

										<a href='#' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/report.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/report.png"?>">

											<span><?php esc_html_e( 'Reports', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu dropdown-menu'>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_report&tab=student_information_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Student Information', 'school-mgt' ); ?></span>

												</a>

											</li>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_report&tab=fianance_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Finance/Payment', 'school-mgt' ); ?></span>

												</a>

											</li>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_report&tab=attendance_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Attendance', 'school-mgt' ); ?></span>

												</a>

											</li>



											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_report&tab=examinations_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Examinations', 'school-mgt' ); ?></span>

												</a>

											</li>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_report&tab=library_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Library', 'school-mgt' ); ?></span>

												</a>

											</li>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_report&tab=hostel_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Hostel', 'school-mgt' ); ?></span>

												</a>

											</li>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_report&tab=user_log_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'User Log', 'school-mgt' ); ?></span>

												</a>

											</li>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_report&tab=audit_log_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Audit Trail Report', 'school-mgt' ); ?></span>

												</a>

											</li>

										</ul>

									</li>

									<?php

								}

								// if($advance_report_view_access == 1)
								// {
									?>
									<!-- <li class="has-submenu nav-item card-icon">

										<a href='#' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_advance_report") { echo "active"; } ?>">

											<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/report.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/report.png"?>">

											<span><?php esc_html_e( 'Advance Reports', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu dropdown-menu'>

											<li class=''>

												<a href='<?php echo admin_url().'admin.php?page=smgt_advance_report&tab=student_information_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_advance_report") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Student Information', 'school-mgt' ); ?></span>

												</a>

											</li>

										</ul>

									</li> -->
									<?php
								// }


								if($notice_view_access == 1 || $message_view_access == 1 || $holiday_view_access == 1 || $notification_view_access == 1 || $event_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon general_setting_menu">

										<a href='#' class=" <?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_notice" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_message" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_event" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_notification" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_holiday" ) { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/notifications.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/notifications.png"?>">

											<span><?php esc_html_e('Notification', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu admin_submenu_css dropdown-menu'>

											<?php

											if($notice_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_notice';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_notice") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Notice', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($event_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_event';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_event") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Event', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($message_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_message';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_message") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Message', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($notification_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_notification';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_notification") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Notification', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($holiday_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_holiday';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_holiday") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Holiday', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											?>

										</ul>

									</li>

									<?php

								}

								if($field_view_access == 1 || $sms_view_access == 1 || $mail_view_access == 1)

								{

									?>

									<li class="has-submenu nav-item card-icon <?php if($role != "management"){ ?> general_setting_menu <?php } ?>">

										<a href='#' class=" <?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "custom_field" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_sms-setting" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_email_template" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_access_right" || $_REQUEST ['page'] && $_REQUEST ['page'] == "smgt_gnrl_settings" ) { echo "active"; } ?>">

											<img class="icon img-top responsive_iphone_icon" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/setting.png"?>">

											<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/setting.png"?>">

											<span><?php esc_html_e('System Settings', 'school-mgt' ); ?></span>

											<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

											<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

										</a>

										<ul class='submenu admin_submenu_css dropdown-menu'>

											<?php

											if($field_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=custom_field';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "custom_field") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Custom Fields', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($sms_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_sms-setting';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_sms-setting") { echo "active"; } ?>">

													<span><?php esc_html_e( 'SMS Settings', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											if($mail_view_access == 1)

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_email_template';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_email_template") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Email Template', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											$role=$school_obj->role;

											if($role == 'admin')

											{

												?>

												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_access_right';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_access_right") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Access Right', 'school-mgt' ); ?></span>

													</a>

												</li>
												<?php
												if(get_option('smgt_enable_video_popup_show') == 'yes')
												{
												?>
												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_system_videos';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_system_videos") { echo "active"; } ?>">

														<span><?php esc_html_e( 'How To Videos', 'school-mgt' ); ?></span>

													</a>

												</li>
												<?php
												}
												?>
												<li class=''>

													<a href='<?php echo admin_url().'admin.php?page=smgt_gnrl_settings';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "smgt_gnrl_settings") { echo "active"; } ?>">

													<span><?php esc_html_e( 'General Settings', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											?>

										</ul>

									</li>

									<?php

								}

								?>

							</ul>

						</nav>

					</div>

					<!-- End menu sidebar main div  -->

				</div>

				<!-- dashboard content div start  -->

				<div class="col col-sm-12 col-md-12 col-lg-10 col-xl-10 dashboard_margin padding_left_0 padding_right_0">

					<div class="page-inner min_height_1088 admin_homepage_padding_top">

						<!-- main-wrapper div START-->

						<div id="main-wrapper" class="main-wrapper-div label_margin_top_15px admin_dashboard">

						<?php



						$page_name = $_REQUEST ['page'];



						if($_REQUEST ['page'] == 'smgt_student')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/student/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_teacher'){

							require_once SMS_PLUGIN_DIR. '/admin/includes/teacher/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_supportstaff'){

							require_once SMS_PLUGIN_DIR. '/admin/includes/supportstaff/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_parent'){

							require_once SMS_PLUGIN_DIR. '/admin/includes/parent/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_class'){

							require_once SMS_PLUGIN_DIR. '/admin/includes/class/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_route')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/routine/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_admission')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/admission/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_virtual_classroom')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/virtual_classroom/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_Subject')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/subject/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_exam')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/exam/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_hall')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/hall/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_result')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/mark/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_grade')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/grade/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_Migration')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/migration/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_student_homewrok')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/student_HomeWork/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_attendence')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/index.php';

						}
						elseif($_REQUEST ['page'] == 'smgt_tax')
						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/tax/index.php';

						}
						elseif($_REQUEST ['page'] == 'smgt_fees_payment')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/feespayment/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_payment')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/payment/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_library')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/library/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_hostel')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/hostel/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_leave')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/leave/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_transport')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/transport/index.php';

						}
						
						elseif($_REQUEST ['page'] == 'smgt_report')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/report/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_advance_report')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/advance_report/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_notice')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/notice/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_event')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/event/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_notification')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/notification/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_message')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/message/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_holiday')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/holiday/index.php';

						}

						elseif($_REQUEST ['page'] == 'custom_field')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/customfield/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_sms-setting')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/sms_setting/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_email_template')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/email-template/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_access_right')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_system_videos')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/system-video.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_gnrl_settings')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/general-settings.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_setup')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/setupform/index.php';

						}

						elseif($_REQUEST ['page'] == 'smgt_document')

						{

							require_once SMS_PLUGIN_DIR. '/admin/includes/ducuments/index.php';

						}

						?>



						<?php

						if(isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == 'smgt_school')
						{
							$wizard_option = get_option('smgt_setup_wizard_step');
							$wizard_status = get_option('smgt_setup_wizard_status');
							$setup_i=1;
							?>

							<!-- Setup Wizard Start  -->

							<div class = "setup_wizard_dashboard">

								<div class="accordion_wizzard">

									<h4 class="accordion-header wizard_heading" id="flush-heading<?php echo $setup_i;?>">

										<button class="accordion-button wizzard_button  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $setup_i;?>" aria-controls="flush-heading<?php echo $setup_i;?>">

											<?php esc_attr_e('Setup Wizard', 'school-mgt'); ?>

										</button>

									</h4>



									<div id="flush-collapse_collapse_<?php echo $setup_i;?>" class="accordion-collapse collapse wizard_accordion_rtl <?php if($wizard_status != 'yes'){ echo "show";}?>" aria-labelledby="flush-heading<?php echo $setup_i;?>" role="tabpanel" data-bs-parent="#accordionExample">

										<div class="m-auto panel_wizard">

											<div class="wizard_main">

												<div class="steps clearfix">

													<ul role="tablist">

														<li role="tab" class="first wizard_responsive disabled <?php if($wizard_option['step1_general_setting'] =='yes'){ echo 'done';} ?>" aria-disabled="false" aria-selected="true">

															<a id="form-total-t-0" href="admin.php?page=smgt_gnrl_settings" aria-controls="form-total-p-0">

																<span class="current-info audible"> </span>

																<div class="title wizard-title">

																	<span class="step-icon">

																		<img class="center wizard_setting" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_setting.png"?>">

																		<?php

																		if($wizard_option['step1_general_setting'] =='yes'){ ?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_vector.png"?>">

																		<?php }

																			else{

																			?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_hour_glass.png"?>">

																		<?php } ?>

																</span>

																	<span class="step-number"><?php esc_html_e( 'General Settings', 'school-mgt' ); ?></span>

																</div>

															</a>

														</li>

														<li role="tab" class="disabled wizard_responsive external_padding <?php if($wizard_option['step2_class'] =='yes'){ echo 'done';} ?>" aria-disabled="true">

															<a id="form-total-t-1" href="admin.php?page=smgt_class&tab=addclass" aria-controls="form-total-p-1">

																<div class="title wizard-title">

																	<span class="step-icon">

																		<img class="center wizard_setting" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Class.png"?>">

																		<?php

																		if($wizard_option['step2_class'] =='yes'){ ?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_vector.png"?>">

																		<?php }

																			else{

																			?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_hour_glass.png"?>">

																		<?php } ?>

																</span>

																	<span class="step-number"><?php echo esc_html_e( 'Add Class', 'school-mgt' );?></span>

																</div>

															</a>

														</li>

														<li role="tab" class="disabled wizard_responsive external_padding wizard-title <?php if($wizard_option['step3_teacher'] =='yes'){ echo 'done';} ?>" aria-disabled="true">

															<a id="form-total-t-2" href="admin.php?page=smgt_teacher&tab=addteacher" aria-controls="form-total-p-2">

																<div class="title">

																	<span class="step-icon">

																		<img class="center wizard_setting" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_teacher.png"?>">

																		<?php

																		if($wizard_option['step3_teacher'] =='yes'){ ?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_vector.png"?>">

																		<?php }

																			else{

																			?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_hour_glass.png"?>">

																		<?php } ?>

																	</span>

																	<span class="step-number"><?php echo esc_html_e( 'Add Teacher', 'school-mgt' ); ?></span>

																</div>

															</a>

														</li>

														<li role="tab" class="disabled wizard_responsive wizard-title <?php if($wizard_option['step4_subject'] =='yes'){ echo 'done';} ?>" aria-disabled="true">

															<a id="form-total-t-2" href="admin.php?page=smgt_Subject&tab=addsubject" aria-controls="form-total-p-2">

																<div class="title">

																	<span class="step-icon">

																		<img class="center wizard_setting" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_subject.png"?>">

																		<?php

																		if($wizard_option['step4_subject'] =='yes'){ ?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_vector.png"?>">

																		<?php }

																			else{

																			?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_hour_glass.png"?>">

																		<?php } ?>

																	</span>

																	<span class="step-number"><?php echo esc_html_e( 'Add Subject', 'school-mgt' ); ?></span>

																</div>

															</a>

														</li>

														<li role="tab" class="disabled wizard_responsive wizard-title last <?php if($wizard_option['step5_class_time_table'] =='yes'){ echo 'done';} ?>" aria-disabled="true">

															<a id="form-total-t-2" href="admin.php?page=smgt_route&tab=addroute" aria-controls="form-total-p-2">

																<div class="title">

																	<span class="step-icon">

																		<img class="center wizard_setting" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_timetable.png"?>">

																		<?php

																		if($wizard_option['step5_class_time_table'] =='yes'){ ?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_vector.png"?>">

																		<?php }

																			else{

																			?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_hour_glass.png"?>">

																		<?php } ?>

																	</span>

																	<span class="step-number"><?php echo esc_attr__('Add Class Time Table', 'school-mgt'); ?></span>

																</div>

															</a>

														</li>

														<li role="tab" class="disabled wizard_responsive wizard-title last last_child <?php if($wizard_option['step6_student'] =='yes'){ echo 'done';} ?>" aria-disabled="true">

															<a id="form-total-t-2" href="admin.php?page=smgt_student&tab=addstudent" aria-controls="form-total-p-2">

																<div class="title">

																	<span class="step-icon">

																		<img class="center wizard_setting" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_student.png"?>">

																		<?php

																		if($wizard_option['step6_student'] =='yes'){ ?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_vector.png"?>">

																		<?php }

																			else{

																			?>

																			<img class="status_image" src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_hour_glass.png"?>">

																		<?php } ?>

																	</span>

																	<span class="step-number"><?php echo esc_html_e( 'Add Student', 'school-mgt' ); ?></span>

																</div>

															</a>

														</li>

													</ul>

												</div>

											</div>

										</div>

									</div>

								</div>

							</div><!-- Setup Wizard End -->

							<div class="row menu_row dashboard_content_rs first_row_padding_top">

								<script src="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.js"></script>

								<link rel="stylesheet" href="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.css">

								<!-- USER REPORT CARD START -->
								<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 responsive_div_dasboard">

									<div class="panel panel-white smgt-line-chat">

										<div class="panel-heading" id="smgt-line-chat-p">

											<h3 class="panel-title" style="float: left;"><?php esc_html_e('Users','school-mgt');?></h3>

										</div>

										<div class="smgt-member-chart">

											<div class="outer">

												<canvas id="userContainer" width="300" height="250"></canvas>

												<p class="percent">

												<?php

												$user_query = new WP_User_Query(array('role' => 'parent'));

												$parent_count = (int) $user_query->get_total();

												$user_query_1 = new WP_User_Query(array('role' => 'student'));

												$student_count = (int) $user_query_1->get_total();

												$user_query_2 = new WP_User_Query(array('role' => 'teacher'));

												$teacher_count = (int) $user_query_2->get_total();

												$user_query_3 = new WP_User_Query(array('role' => 'supportstaff'));

												$staff_count = (int) $user_query_3->get_total();

												$total_student_parent = $parent_count + $student_count + $teacher_count + $staff_count;

												echo (int)$total_student_parent;

												?>

												</p>

												<p class="percent1">

													<?php esc_html_e('Users','school-mgt');?>

												</p>

											</div>

											<script>
												var options1 = {
													type: 'doughnut',
													data: {
														labels: ["<?php esc_html_e('Students', 'school-mgt'); ?>", "<?php esc_html_e('Parents', 'school-mgt'); ?> ", "<?php esc_html_e('Teachers', 'school-mgt'); ?>", "<?php esc_html_e('Support Staff', 'school-mgt'); ?>"],
														datasets: [{
															label: '# of Votes',
															data: [<?php echo $student_count; ?>, <?php echo $parent_count; ?>, <?php echo $teacher_count; ?>, <?php echo $staff_count; ?>],
															backgroundColor: [
																'#1E90FF',
																'#32CD32',
																'#FF4500',
																'#FFA500',
															],
															borderColor: [
																'rgba(255, 255, 255 ,1)',
																'rgba(255, 255, 255 ,1)',
																'rgba(255, 255, 255 ,1)',
																'rgba(255, 255, 255 ,1)',
															],
															borderWidth: 1,

														}]
													},
													options: {
														rotation: 1 * Math.PI,
														// circumference: 1 * Math.PI,
														legend: {
															display: false
														},
														tooltip: {
															enabled: false
														},
														cutoutPercentage: 85
													}
												}

												var ctx1 = document.getElementById('userContainer').getContext('2d');
												new Chart(ctx1, options1);

												var options2 = {
													type: 'doughnut',
													data: {
														labels: ["", "Purple", ""],
														datasets: [{
															data: [88.5, 1],
															backgroundColor: [
																"rgba(0,0,0,0)",
																"rgba(255,255,255,1)",

															],
															borderColor: [
																'rgba(0, 0, 0 ,0)',
																'rgba(46, 204, 113, 1)',

															],
															borderWidth: 5

														}]
													},
													options: {
														cutoutPercentage: 95,
														rotation: 1 * Math.PI,
														circumference: 1 * Math.PI,
														legend: {
															display: false
														},
														tooltips: {
															enabled: false
														}
													}
												}
											</script>

										</div>

										<div class="row ps-3 padding_top_10p users_label_div mt-4 rtl_dashboard_labelsetup">
											<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #1E90FF;"></p>
												<p class="user_report_label"><?php esc_html_e('Students', 'school-mgt'); ?></p>
											</div>
											<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #32CD32;"></p>
												<p class="user_report_label"><?php esc_html_e('Parents', 'school-mgt'); ?></p>
											</div>
											<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #FF4500;"></p>
												<p class="user_report_label"><?php esc_html_e('Teachers', 'school-mgt'); ?></p>
											</div>
											<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #FFA500;"></p>
												<p class="user_report_label"><?php esc_html_e('Support Staff', 'school-mgt'); ?></p>
											</div>
										</div>

									</div>

								</div>
								<!-- USER REPORT CARD END -->

								<!-- STUDENT STATUS REPORT CARD START -->
								<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 responsive_div_dasboard">
									<div class="panel panel-white smgt-line-chat">
										<div class="panel-heading" id="smgt-line-chat-p">
											<h3 class="panel-title" style="float: left;"><?php esc_html_e('Student Status','school-mgt');?></h3>
											<a href="<?php echo admin_url() . 'admin.php?page=smgt_student'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL . "/assets/images/dashboard_icon/Redirect.png" ?>"></a>
										</div>
										<div class="smgt-member-chart">
											<div class="outer">

												<canvas id="studentContainer" width="300" height="250"></canvas>

												<p class="percent">

												<?php

												$user_query = mj_smgt_approve_student_list();

												$inactive = 0;
												if(!empty($user_query))
												{
													$inactive = count($user_query);
												}
												$approve_student = mj_smgt_get_all_student_list();
												$approve = 0;
												if(!empty($approve_student))
												{
													$approve = count($approve_student);
												}
												$total_student = $inactive + $approve;

												echo (int)$total_student;

												?>

												</p>

												<p class="percent1">

													<?php esc_html_e('Student Status','school-mgt');?>

												</p>

											</div>
											<script>
												var options1 = {
													type: 'doughnut',
													data: {
														labels: ["<?php esc_html_e('Inactive Students', 'school-mgt'); ?>", "<?php esc_html_e('Active Students', 'school-mgt'); ?> "],
														datasets: [{
															label: '# of Votes',
															data: [<?php echo $inactive; ?>, <?php echo $approve; ?>],
															backgroundColor: [
																'#FF5722',
																'#8BC34A'
															],
															borderColor: [
																'rgba(255, 255, 255 ,1)',
																'rgba(255, 255, 255 ,1)',
															],
															borderWidth: 1,

														}]
													},
													options: {
														rotation: 1 * Math.PI,
														// circumference: 1 * Math.PI,
														legend: {
															display: false
														},
														tooltip: {
															enabled: false
														},
														cutoutPercentage: 85
													}
												}
												var ctx1 = document.getElementById('studentContainer').getContext('2d');
												new Chart(ctx1, options1);
												var options2 = {
													type: 'doughnut',
													data: {
														labels: ["", "Purple", ""],
														datasets: [{
															data: [88.5, 1],
															backgroundColor: [
																"rgba(0,0,0,0)",
																"rgba(255,255,255,1)",

															],
															borderColor: [
																'rgba(0, 0, 0 ,0)',
																'rgba(46, 204, 113, 1)',

															],
															borderWidth: 5

														}]
													},
													options: {
														cutoutPercentage: 95,
														rotation: 1 * Math.PI,
														circumference: 1 * Math.PI,
														legend: {
															display: false
														},
														tooltips: {
															enabled: false
														}
													}
												}
											</script>

										</div>

										<div class="row ps-3 padding_top_10p users_label_div mt-4 rtl_dashboard_labelsetup">
											<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #FF5722;"></p>
												<p class="user_report_label"><?php esc_html_e('Inactive Students', 'school-mgt'); ?></p>
											</div>
											<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #8BC34A;"></p>
												<p class="user_report_label"><?php esc_html_e('Active Students', 'school-mgt'); ?></p>
											</div>
										</div>

									</div>

								</div>
								<!-- STUDENT STATUS REPORT CARD END -->

								<!-- PAYMENT STATUS REPORT CARD START -->
								<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 responsive_div_dasboard">

									<div class="panel panel-white smgt-line-chat">

										<div class="panel-heading" id="smgt-line-chat-p">

											<h3 class="panel-title" style="float: left;"><?php esc_html_e('Payment Status','school-mgt');?></h3>
											<a href="<?php echo admin_url() . 'admin.php?page=smgt_fees_payment&tab=feespaymentlist'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL . "/assets/images/dashboard_icon/Redirect.png" ?>"></a>
										</div>

										<div class="smgt-member-chart">

											<div class="outer">

												<canvas id="paymentstatusContainer" width="300" height="250"></canvas>

												<p class="percent">

												<?php

													$total = MJ_smgt_get_payment_amout_by_payment_status('total');
													$paid = MJ_smgt_get_payment_amout_by_payment_status('Fully Paid');

													$unpaid = $total - $paid;
													$currency_symbol = html_entity_decode(mj_smgt_get_currency_symbol(get_option('smgt_currency_code')));
													echo MJ_smgt_currency_symbol_position_language_wise(number_format($total, 2, '.', ''));
												?>

												</p>

												<p class="percent1">

													<?php esc_html_e('Payment Status','school-mgt');?>

												</p>

											</div>

											<script>
												var options1 = {
													type: 'doughnut',
													data: {
														labels: ["<?php esc_html_e('Paid', 'school-mgt'); ?>", "<?php esc_html_e('Unpaid', 'school-mgt'); ?>"],
														datasets: [{
															label: '# of Votes',
															data: [<?php echo number_format($paid, 2, '.', '') ?>, <?php echo number_format($unpaid, 2, '.', ''); ?>],
															backgroundColor: [
																'#40A415',
																'#BA170B'
															],
															borderColor: [
																'rgba(255, 255, 255 ,1)',
																'rgba(255, 255, 255 ,1)'


															],
															borderWidth: 1,

														}]
													},
													options: {
														rotation: 1 * Math.PI,
														// circumference: 1 * Math.PI,
														legend: {
															display: false
														},
														tooltips: {
															enabled: true,
															callbacks: {
																label: function(tooltipItem, data) {
																	var label = data.labels[tooltipItem.index] || '';
																	var symbol = '<?php echo html_entity_decode(mj_smgt_get_currency_symbol(get_option('smgt_currency_code'))); ?>';
																	var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
																	return label + ': ' + symbol + value;
																}
															}
														},
														cutoutPercentage: 85
													}
												}

												var ctx1 = document.getElementById('paymentstatusContainer').getContext('2d');
												new Chart(ctx1, options1);

												var options2 = {
													type: 'doughnut',
													data: {
														labels: ["", "Purple", ""],
														datasets: [{
															data: [88.5, 1],
															backgroundColor: [
																"rgba(0,0,0,0)",
																"rgba(255,255,255,1)",

															],
															borderColor: [
																'rgba(0, 0, 0 ,0)',
																'rgba(46, 204, 113, 1)',

															],
															borderWidth: 5

														}]
													},
													options: {
														cutoutPercentage: 95,
														rotation: 1 * Math.PI,
														circumference: 1 * Math.PI,
														legend: {
															display: false
														},
														tooltips: {
															enabled: false
														}
													}
												}
											</script>

										</div>

										<div class="row ps-3 padding_top_10p users_label_div mt-4 rtl_dashboard_labelsetup">

											<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  users_report_label ps-2">
												<p class="users_report_dotcolor" style="background:#40A415;"></p>
												<p class="user_report_label"><?php esc_html_e('Paid', 'school-mgt'); ?></p>
											</div>
											<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  users_report_label ps-2">
												<p class="users_report_dotcolor" style="background:#BA170B;"></p>
												<p class="user_report_label"><?php esc_html_e('Unpaid', 'school-mgt'); ?></p>
											</div>
										</div>

									</div>

								</div>
								<!-- PAYMENT STATUS REPORT CARD END -->

								<!-- ATTENDANCE REPORT CARD START -->
								<div class="col-lg-4 col-md-4 col-xl-4 col-sm-4 responsive_div_dasboard">
									<div class="panel panel-white gmgt-line-chat">
										<div class="row mb-3 dashboard_height_card">
											<div class="col-6 col-lg-6 col-md-6 col-xl-6 attendance_report_title">
												<h3 class="panel-title" style="font-size:20px;"><?php esc_html_e('Attendance', 'school-mgt'); ?></h3>
											</div>
											<div class="col-6 col-lg-6 col-md-6 col-xl-6 padding_right_25 rtl_padding_dropdown">

												<select class="form-control attendance_report_filter dash_report_filter" name="date_type" autocomplete="off">

													<option value="today"><?php esc_attr_e('Today', 'school-mgt'); ?></option>

													<option value="this_week"><?php esc_attr_e('This Week', 'school-mgt'); ?></option>

													<option value="last_week"><?php esc_attr_e('Last Week', 'school-mgt'); ?></option>

													<option value="this_month" selected><?php esc_attr_e('This Month', 'school-mgt'); ?></option>

													<option value="last_month"><?php esc_attr_e('Last Month', 'school-mgt'); ?></option>

													<option value="last_3_month"><?php esc_attr_e('Last 3 Months', 'school-mgt'); ?></option>

													<option value="last_6_month"><?php esc_attr_e('Last 6 Months', 'school-mgt'); ?></option>

													<option value="last_12_month"><?php esc_attr_e('Last 12 Months', 'school-mgt'); ?></option>

													<option value="this_year"><?php esc_attr_e('This Year', 'school-mgt'); ?></option>

													<option value="last_year"><?php esc_attr_e('Last Year', 'school-mgt'); ?></option>

												</select>

											</div>
										</div>
										<div class="smgt-member-chart">
											<div class="outer attendance_report_load">
												<canvas id="chartJSContainerattendance" width="300" height="250"></canvas>

												<p class="percent">
													<?php
													$result =  mj_smgt_all_date_type_value('this_month');
													$response =  json_decode($result);
													$start_date = $response[0];
													$end_date = $response[1];

													$present = MJ_smgt_attendance_data_by_status($start_date, $end_date,'Present');
													$absent = MJ_smgt_attendance_data_by_status($start_date, $end_date,'Absent');
													$late = MJ_smgt_attendance_data_by_status($start_date, $end_date,'Late');
													$halfday = MJ_smgt_attendance_data_by_status($start_date, $end_date,'Half Day');


													$attendance = $present + $absent + $late + $halfday;
													echo $attendance;
													?>
												</p>
												<script>
													var options1 = {
														type: 'doughnut',
														data: {
															labels: ["<?php esc_html_e('Present', 'school-mgt'); ?>", "<?php esc_html_e('Absent', 'school-mgt'); ?>", "<?php esc_html_e('Late', 'school-mgt'); ?>", "<?php esc_html_e('Half Day', 'school-mgt'); ?>"],
															datasets: [{
																label: '# of Votes',
																data: [<?php echo $present; ?>, <?php echo $absent; ?>, <?php echo $late; ?>, <?php echo $halfday; ?>],
																backgroundColor: [
																	'#28A745',
																	'#DC3545',
																	'#FFC107',
																	'#007BFF',

																],
																borderColor: [
																	'rgba(255, 255, 255 ,1)',
																	'rgba(255, 255, 255 ,1)',
																	'rgba(255, 255, 255 ,1)',
																	'rgba(255, 255, 255 ,1)',
																],
																borderWidth: 1,

															}]
														},
														options: {
															rotation: 1 * Math.PI,
															// circumference: 1 * Math.PI,
															legend: {
																display: false
															},
															tooltip: {
																enabled: false
															},
															cutoutPercentage: 85
														}
													}

													var ctx1 = document.getElementById('chartJSContainerattendance').getContext('2d');
													new Chart(ctx1, options1);

													var options2 = {
														type: 'doughnut',
														data: {
															labels: ["", "Purple", ""],
															datasets: [{
																data: [88.5, 1],
																backgroundColor: [
																	"rgba(0,0,0,0)",
																	"rgba(255,255,255,1)",

																],
																borderColor: [
																	'rgba(0, 0, 0 ,0)',
																	'rgba(46, 204, 113, 1)',

																],
																borderWidth: 5

															}]
														},
														options: {
															cutoutPercentage: 95,
															rotation: 1 * Math.PI,
															circumference: 1 * Math.PI,
															legend: {
																display: false
															},
															tooltips: {
																enabled: false
															}
														}
													}
												</script>
												<p class="percent1">

													<?php esc_html_e('Attendance', 'school-mgt'); ?>

												</p>
											</div>
										</div>
										<div class="row ps-3 padding_top_10p users_label_div mt-4 rtl_dashboard_labelsetup">
											<div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-6 col-xs-6 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #28A745;"></p>
												<p class="user_report_label"><?php esc_html_e('Present', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-6 col-xs-6 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #DC3545;"></p>
												<p class="user_report_label"><?php esc_html_e('Absent', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-6 col-xs-6 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #FFC107;"></p>
												<p class="user_report_label"><?php esc_html_e('Late', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-6 col-xs-6 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #007BFF;"></p>
												<p class="user_report_label"><?php esc_html_e('Half Day', 'school-mgt'); ?></p>
											</div>
										</div>

									</div>
								</div>
								<!-- ATTENDANCE REPORT CARD END -->

								<!-- ATTENDANCE REPORT CARD START -->
								<div class="col-lg-4 col-md-4 col-xl-4 col-sm-4 responsive_div_dasboard">
									<div class="panel panel-white gmgt-line-chat">
										<div class="row mb-3 dashboard_height_card">
											<div class="col-6 col-lg-8 col-md-8 col-xl-8 attendance_report_title">
												<h3 class="panel-title" style="font-size:20px;"><?php esc_html_e('Payment Report', 'school-mgt'); ?></h3>
											</div>
											<div class="col-6 col-lg-4 col-md-4 col-xl-4 padding_right_25 rtl_padding_dropdown">

												<select class="form-control payment_report_filter dash_report_filter" name="date_type" autocomplete="off">

													<option value="today"><?php esc_attr_e('Today', 'school-mgt'); ?></option>

													<option value="this_week"><?php esc_attr_e('This Week', 'school-mgt'); ?></option>

													<option value="last_week"><?php esc_attr_e('Last Week', 'school-mgt'); ?></option>

													<option value="this_month" selected><?php esc_attr_e('This Month', 'school-mgt'); ?></option>

													<option value="last_month"><?php esc_attr_e('Last Month', 'school-mgt'); ?></option>

													<option value="last_3_month"><?php esc_attr_e('Last 3 Months', 'school-mgt'); ?></option>

													<option value="last_6_month"><?php esc_attr_e('Last 6 Months', 'school-mgt'); ?></option>

													<option value="last_12_month"><?php esc_attr_e('Last 12 Months', 'school-mgt'); ?></option>

													<option value="this_year"><?php esc_attr_e('This Year', 'school-mgt'); ?></option>

													<option value="last_year"><?php esc_attr_e('Last Year', 'school-mgt'); ?></option>

												</select>

											</div>
										</div>
										<div class="smgt-member-chart">
											<div class="outer payment_report_load">
												<canvas id="chartJSContainerpayment" width="300" height="250"></canvas>

												<p class="percent">
													<?php

													$result =  mj_smgt_all_date_type_value('this_month');
													$response =  json_decode($result);
													$start_date = $response[0];
													$end_date = $response[1];

													$cash_payment = mj_smgt_get_payment_paid_data_by_date_method("Cash",$start_date,$end_date);

													if(!empty($cash_payment))
													{
														$cashAmount = 0;
														foreach($cash_payment as $cash)
														{

															$cashAmount += $cash->amount;
														}
													}
													else{
														$cashAmount = 0;
													}
													$Cheque_payment = mj_smgt_get_payment_paid_data_by_date_method("Cheque",$start_date,$end_date);
													if(!empty($Cheque_payment))
													{
														$chequeAmount = 0;
														foreach($Cheque_payment as $cheque)
														{

															$chequeAmount += $cheque->amount;
														}
													}
													else{
														$chequeAmount = 0;
													}
													$bank_payment = mj_smgt_get_payment_paid_data_by_date_method("Bank Transfer",$start_date,$end_date);
													if(!empty($bank_payment))
													{
														$bankAmount = 0;
														foreach($bank_payment as $bank)
														{

															$bankAmount += $bank->amount;
														}
													}
													else{
														$bankAmount = 0;
													}
													$paypal_payment = mj_smgt_get_payment_paid_data_by_date_method("paypal",$start_date,$end_date);
													if(!empty($paypal_payment))
													{
														$paypalAmount = 0;
														foreach($paypal_payment as $paypal)
														{

															$paypalAmount += $paypal->amount;
														}
													}
													else{
														$paypalAmount = 0;
													}
													$stripe_payment = mj_smgt_get_payment_paid_data_by_date_method("Stripe",$start_date,$end_date);
													if(!empty($stripe_payment))
													{
														$stripeAmount = 0;
														foreach($stripe_payment as $stripe)
														{

															$stripeAmount += $stripe->amount;
														}
													}
													else{
														$stripeAmount = 0;
													}
													$Total_amount =  $cashAmount + $chequeAmount + $bankAmount + $paypalAmount + $stripeAmount;
													$currency_symbol = html_entity_decode(MJ_smgt_get_currency_symbol(get_option( 'smgt_currency_code' )));
													echo MJ_smgt_currency_symbol_position_language_wise(number_format($Total_amount,2,'.',''));
													
													?>
												</p>
												<script>
													var options1 = {
														type: 'doughnut',
														data: {
															labels: ["<?php esc_html_e('Cash','school-mgt');?>", "<?php esc_html_e('Cheque','school-mgt');?>","<?php esc_html_e('Bank Transfer','school-mgt');?>", "<?php esc_html_e('Paypal','school-mgt');?>", "<?php esc_html_e('Stripe','school-mgt');?>"],
															datasets: [
																{
																	label: '# of Votes',
																	data: [<?php echo $cashAmount;?>, <?php echo $chequeAmount;?>,<?php echo $bankAmount;?>, <?php echo $paypalAmount;?>, <?php echo $stripeAmount;?>],
																	backgroundColor: [
																		'#CD6155',
																		'#00BCD4',
																		'#F5B041',
																		'#99A3A4',
																		'#9B59B6',
																	],
																	borderColor: [
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																		'rgba(255, 255, 255 ,1)',
																	],
																	borderWidth: 1,

																}
															]
														},
														options: {
														rotation: 1 * Math.PI,
																	// circumference: 1 * Math.PI,
																	legend: {
																		display: false
																	},
																	tooltips: {
																	enabled: true,
																	callbacks: {
																		label: function(tooltipItem, data) {
																			var label = data.labels[tooltipItem.index] || '';
																			var symbol = '<?php echo html_entity_decode(MJ_smgt_get_currency_symbol(get_option( 'smgt_currency_code' ))); ?>';
																			var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
																			return label + ': ' + symbol + value;
																		}
																	}
																},
																	cutoutPercentage: 85
														}
													}

													var ctx1 = document.getElementById('chartJSContainerpayment').getContext('2d');
													new Chart(ctx1, options1);

													var options2 = {
														type: 'doughnut',
														data: {
															labels: ["", "Purple", ""],
															datasets: [
															{
																	data: [88.5, 1],
																	backgroundColor: [
																		"rgba(0,0,0,0)",
																		"rgba(255,255,255,1)",

																	],
																	borderColor: [
																	'rgba(0, 0, 0 ,0)',
																	'rgba(46, 204, 113, 1)',

																],
																borderWidth: 5

															}]
														},
														options: {
															cutoutPercentage: 95,
															rotation: 1 * Math.PI,
															circumference: 1 * Math.PI,
																	legend: {
																		display: false
																	},
																	tooltips: {
																		enabled: false
																	}
														}
													}
												</script>
												<p class="percent1">

													<?php esc_html_e('Payment Report', 'school-mgt'); ?>

												</p>
											</div>
										</div>
										<div class="row ps-3 padding_top_10p users_label_div mt-4 rtl_dashboard_labelsetup">
											<div class="col-4 col-sm-4 col-md-6 col-lg-4 col-xl-4 col-xs-4 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #99A3A4;"></p>
												<p class="user_report_label"><?php esc_html_e('Paypal', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-4 col-xl-4 col-xs-4 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #9B59B6;"></p>
												<p class="user_report_label"><?php esc_html_e('Stripe', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-4 col-xl-4 col-xs-4 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #CD6155;"></p>
												<p class="user_report_label"><?php esc_html_e('Cash', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-4 col-xl-4 col-xs-4 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #00BCD4;"></p>
												<p class="user_report_label"><?php esc_html_e('Cheque', 'school-mgt'); ?></p>
											</div>
											<div class="col-8 col-sm-4 col-md-6 col-lg-8 col-xl-8 col-xs-8 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #F5B041;"></p>
												<p class="user_report_label"><?php esc_html_e('Bank Transfer', 'school-mgt'); ?></p>
											</div>
										</div>
									</div>
								</div>
								<!-- ATTENDANCE REPORT CARD END -->

								<!-- FEES PAYMENT DETAILS REPORT START-->
								<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 responsive_div_dasboard precription_padding_left1">

									<div class="panel panel-white admmision_div" style="height: 450px;">

										<div class="panel-heading" id="smgt-line-chat-p">

											<h3 class="panel-title"><?php esc_html_e('Fees Payment Details','school-mgt');?></h3>

											<a class="page_link1" href="<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=feespaymentlist'; ?>">

												<img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>">

											</a>

										</div>

										<div class="panel-body">

											<div class="events1">

												<?php

												$obj_feespayment = new mj_smgt_feespayment();

												$i= 0;

												$feespayment_data = $obj_feespayment->mj_smgt_get_five_fees();

												if(!empty($feespayment_data))
												{
													foreach ($feespayment_data as $retrieved_data)
													{
														if($i == 0)
														{
															$color_class='smgt_assign_bed_color0';
														}
														elseif($i == 1)
														{
															$color_class='smgt_assign_bed_color1';
														}
														elseif($i == 2)
														{
															$color_class='smgt_assign_bed_color2';
														}
														elseif($i == 3)
														{
															$color_class='smgt_assign_bed_color3';
														}
														elseif($i == 4)
														{
															$color_class='smgt_assign_bed_color4';
														}
													?>

														<div class="fees_payment_height calendar-event">

															<p class="fees_payment_padding_top_0 remainder_title Bold viewbedlist show_task_event date_font_size" id="<?php echo esc_attr($retrieved_data->fees_pay_id); ?>" model="Feespayment Details" style="">

																<label for="" class="date_assignbed_label">

																<?php

																echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->total_amount,2,'.',''));

																?>

																</label>

																<span class=" <?php echo $color_class; ?>"></span>

															</p>

															<p class="remainder_date assignbed_name assign_bed_name_size">

															<?php

																$student_data =	get_userdata($retrieved_data->student_id);

																if(!empty($student_data)){

																	echo esc_html($student_data->display_name);

																}

																else{

																	echo 'N/A';

																}

															?>

															</p>

															<p class="remainder_date assign_bed_date assign_bed_name_size">

															<?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date); ?>

															</p>

														</div>

													<?php

													$i++;

													}



												}

												else

												{

													?>

														<div class="calendar-event-new">

															<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

															<div class="col-md-12 dashboard_btn">

																<a href="<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=addpaymentfee'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('Fees Payment','school-mgt');?></a>

															</div>

														</div>

													<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>
								<!-- FEES PAYMENT DETAILS REPORT -->
							</div>

							<!-- Chart and Fees Payment Row Div  -->

							<!-- Celender And Chart Row  -->

							<div class="row calander-chart-div">

								<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

									<div class="smgt-calendar panel">

										<div class="row panel-heading activities">

											<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">

												<h3 class="panel-title calander_heading_title_width"><?php esc_html_e('Calendar','school-mgt');?></h3>

											</div>

											<div class="smgt-cal-py col-sm-12 col-md-8 col-lg-8 col-xl-8 celender_dot_div">

												<div class="smgt-card-head">

													<ul class="smgt-cards-indicators smgt-right">

														<!--set caldender-header event-List Start -->

														<li><span class="smgt-indic smgt-blue-indic"></span> <?php esc_html_e( 'Holiday', 'school-mgt' ); ?></li>

														<li><span class="smgt-indic smgt-yellow-indic"></span> <?php esc_html_e( 'Notice', 'school-mgt' );?></li>

														<li><span class="smgt-indic smgt-perple-indic"></span> <?php esc_html_e( 'Exam', 'school-mgt' );?></li>

														<li><span class="smgt-indic smgt-light-blue-indic"></span> <?php esc_html_e( 'Event', 'school-mgt' );?></li>

														<!--set caldender-header event-List End -->

													</ul>

												</div>

											</div>

										</div>

										<div class="smgt-cal-py smgt-calender-margin-top">

											<div id="calendar"></div>

										</div>

									</div>

								</div>

							</div>

							<!-- Celender And Chart Row  -->

							<!-- INCOME EXPENCE REPORT START -->
							<div class="row menu_row dashboard_content_rs"><!-- Row Div Start  -->
								<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL . '/assets/js/chart_loder.js'; ?>"></script>
								<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 responsive_div_dasboard precription_padding_left1">

									<div class="panel panel-white income_expence_chart">

										<div class="row">

											<div class="col-md-8 input">

												<div class="panel-heading ">

													<h3 class="panel-title"><?php esc_html_e('Income-Expense Report', 'school-mgt'); ?></h3>

												</div>

											</div>

											<div class="col-md-2 mb-3 col-6 input margin_top_20px margin_left_20px margin_rtl_30px responsive_months dashboard_payment_report_padding_left">

												<select id="month" name="month" class="line_height_30px form-control dash_month_load " style="height: 35px !important;">

													<option value="all_month"><?php esc_attr_e('All Month', 'school-mgt'); ?></option>

													<?php

													$month = array('1' => esc_html__('January', 'school-mgt'), '2' => esc_html__('February', 'school-mgt'), '3' => esc_html__('March', 'school-mgt'), '4' => esc_html__('April', 'school-mgt'), '5' => esc_html__('May', 'school-mgt'), '6' => esc_html__('June', 'school-mgt'), '7' => esc_html__('July', 'school-mgt'), '8' => esc_html__('August', 'school-mgt'), '9' => esc_html__('September', 'school-mgt'), '10' => esc_html__('October', 'school-mgt'), '11' => esc_html__('November', 'school-mgt'), '12' => esc_html__('December', 'school-mgt'),);

													foreach ($month as $key => $value) {

														$selected = (date('m') == $key ? ' selected' : '');

														echo '<option value="' . $key . '"' . $selected . '>' .  esc_html__($value, 'school-mgt') . '</option>' . "\n";
													}

													?>

												</select>

											</div>

											<div class="col-md-2 mb-3 col-6 input margin_top_20px margin_left_20px responsive_months dashboard_payment_report_padding">

												<select name="year" class="line_height_30px form-control dash_year_load" style="height: 35px !important;">
													<?php

													$current_year = date('Y');

													$min_year = $current_year - 10;



													for ($i = $current_year; $i >= $min_year; $i--) {

														$year_array[$i] = $i;

														$selected = ($current_year == $i ? ' selected' : '');

														echo '<option value="' . $i . '"' . $selected . '>' . $i . '</option>' . "\n";
													}

													?>

												</select>

											</div>


										</div>

										<div class="panel-body class_padding">

											<div class="events1" id="income_expence_report_append">

												<?php
													$month = date("m");

													$current_month = date("m");

													$current_year = date("Y");

													$dataPoints_2 = array();
													if ($month == "2")
													{
														$max_d = "29";
													}
													elseif ($month == "4" || $month == "6" || $month == "9" || $month == "11")
													{
														$max_d = "30";
													}
													else
													{
														$max_d = "31";
													}
													for ($d = 1; $d <= $max_d; $d++)
													{
														$time = mktime(12, 0, 0, $month, $d, $current_year);

														if (date('m', $time) == $month)

															$date_list[] = date('Y-m-d', $time);

														$day_date[] = date('d', $time);

														$month_first_date = min($date_list);

														$month_last_date =   max($date_list);
													}

													$month = array();

													$i = 1;

													foreach ($day_date as $value)
													{
														$month[$i] = $value;
														$i++;
													}
													array_push($dataPoints_2, array(esc_html__('Day', 'school-mgt'), esc_html__('Income', 'school-mgt'), esc_html__('Expense', 'school-mgt'), esc_html__('Net Profit', 'school-mgt')));
													$expense_array = array();

													$currency_symbol = html_entity_decode(MJ_smgt_get_currency_symbol(get_option( 'smgt_currency_code' )));

													foreach ($month as $key => $value)
													{
														global $wpdb;

														$table_name = $wpdb->prefix . "smgt_income_expense";

														$q = "SELECT * FROM $table_name WHERE YEAR(income_create_date) = $current_year AND MONTH(income_create_date) = $current_month AND DAY(income_create_date) = $value and invoice_type='income'";

														$q1 = "SELECT * FROM $table_name WHERE YEAR(income_create_date) = $current_year AND MONTH(income_create_date) = $current_month AND DAY(income_create_date) = $value and invoice_type='expense'";

														$result = $wpdb->get_results($q);
														$result1 = $wpdb->get_results($q1);

														$expense_yearly_amount = 0;
														foreach ($result1 as $expense_entry) {
															$all_entry = json_decode($expense_entry->entry);
															$amount = 0;
															foreach ($all_entry as $entry) {
																$amount += $entry->amount;
															}
															$expense_yearly_amount += $amount;
														}

														$expense_amount = $expense_yearly_amount;


														$income_yearly_amount = 0;
														foreach ($result as $income_entry) {
															$all_entry = json_decode($income_entry->entry);
															$amount = 0;
															foreach ($all_entry as $entry) {
																$amount += $entry->amount;
															}

															$income_yearly_amount += $amount;
														}

														$income_amount = $income_yearly_amount;

														$expense_array[] = $expense_amount;
														$income_array[] = $income_amount;
														$net_profit_array = $income_amount - $expense_amount;
														array_push($dataPoints_2, array($value, $income_amount, $expense_amount, $net_profit_array));
													}
													$income_filtered = array_filter($income_array);
													$expense_filtered = array_filter($expense_array);
													$new_array = json_encode($dataPoints_2);

													if (!empty($income_filtered) || !empty($expense_filtered)) {
														$new_currency_symbol = html_entity_decode($currency_symbol);

													?>

														<script type="text/javascript">
															google.charts.load('current', {
																'packages': ['bar']
															});
															google.charts.setOnLoadCallback(drawChart);

															function drawChart() {
																var data = google.visualization.arrayToDataTable(<?php echo $new_array; ?>);

																var options = {

																	bars: 'vertical', // Required for Material Bar Charts.
																	colors: ['#104B73', '#FF9054', '#70ad46'],

																};

																var chart = new google.charts.Bar(document.getElementById('barchart_material'));

																chart.draw(data, google.charts.Bar.convertOptions(options));
															}
														</script>
														<div id="barchart_material" style="width:100%;height: 430px; padding:20px;"></div>
													<?php
													} else {
													?>
														<div class="calendar-event-new">
															<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL . "/assets/images/dashboard_icon/no_data_img.png" ?>">
														</div>
													<?php
													}
												?>

											</div>

										</div>

									</div>

								</div>


								<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 responsive_div_dasboard precription_padding_left1">

									<div class="panel panel-white income_expence_chart">

										<div class="row">

											<div class="col-md-8 input">

												<div class="panel-heading ">

													<h3 class="panel-title"><?php esc_html_e('Fees Payment Report', 'school-mgt'); ?></h3>

												</div>

											</div>

											<div class="col-md-2 mb-3 col-6 input margin_top_20px margin_left_20px margin_rtl_30px responsive_months dashboard_payment_report_padding_left">

												<select id="month" name="month" class="line_height_30px form-control fees_month_load " style="height: 35px !important;">

													<option value="all_month"><?php esc_attr_e('All Month', 'school-mgt'); ?></option>

													<?php

													$month = array('1' => esc_html__('January', 'school-mgt'), '2' => esc_html__('February', 'school-mgt'), '3' => esc_html__('March', 'school-mgt'), '4' => esc_html__('April', 'school-mgt'), '5' => esc_html__('May', 'school-mgt'), '6' => esc_html__('June', 'school-mgt'), '7' => esc_html__('July', 'school-mgt'), '8' => esc_html__('August', 'school-mgt'), '9' => esc_html__('September', 'school-mgt'), '10' => esc_html__('October', 'school-mgt'), '11' => esc_html__('November', 'school-mgt'), '12' => esc_html__('December', 'school-mgt'),);

													foreach ($month as $key => $value) {

														$selected = (date('m') == $key ? ' selected' : '');

														echo '<option value="' . $key . '"' . $selected . '>' .  esc_html__($value, 'school-mgt') . '</option>' . "\n";
													}

													?>

												</select>

											</div>

											<div class="col-md-2 mb-3 col-6 input margin_top_20px margin_left_20px responsive_months dashboard_payment_report_padding">

												<select name="year" class="line_height_30px form-control fees_year_load" style="height: 35px !important;">
													<?php

													$current_year = date('Y');

													$min_year = $current_year - 10;



													for ($i = $current_year; $i >= $min_year; $i--) {

														$year_array[$i] = $i;

														$selected = ($current_year == $i ? ' selected' : '');

														echo '<option value="' . $i . '"' . $selected . '>' . $i . '</option>' . "\n";
													}

													?>

												</select>

											</div>


										</div>

										<div class="panel-body class_padding">

											<div class="events1" id="fees_report_append">

												<?php
													$month = date("m");

													$current_month = date("m");

													$current_year = date("Y");

													$dataPoints_payment = array();
													if ($month == "2")
													{
														$max_d = "29";
													}
													elseif ($month == "4" || $month == "6" || $month == "9" || $month == "11")
													{
														$max_d = "30";
													}
													else
													{
														$max_d = "31";
													}
													for ($d = 1; $d <= $max_d; $d++)
													{
														$time = mktime(12, 0, 0, $month, $d, $current_year);

														if (date('m', $time) == $month)

															$date_list[] = date('Y-m-d', $time);

														$day_date_1[] = date('d', $time);

														$month_first_date = min($date_list);

														$month_last_date =   max($date_list);
													}

													$month = array();

													$i = 1;

													foreach ($day_date_1 as $value)
													{
														$month[$i] = $value;
														$i++;
													}
													array_push($dataPoints_payment, array(esc_html__('Day', 'school-mgt'), esc_html__('Payment', 'school-mgt')));
													$payment_array = array();

													$currency_symbol = html_entity_decode(MJ_smgt_get_currency_symbol(get_option( 'smgt_currency_code' )));

													foreach ($month as $key => $value)
													{
														global $wpdb;

														$table_name = $wpdb->prefix . "smgt_fee_payment_history";
														$q = "SELECT * FROM $table_name WHERE YEAR(paid_by_date) = $current_year AND MONTH(paid_by_date) = $current_month AND DAY(paid_by_date) = $value";
														$result = $wpdb->get_results($q);

														$amount= 0;
														foreach ($result as $payment_entry)
														{
															$amount += $payment_entry->amount;
														}
														$payment_amount = $amount;

														$payment_array[] = $payment_amount;

														array_push($dataPoints_payment, array($value, $payment_amount));
													}
													$payment_filtered = array_filter($payment_array);

													$new_array = json_encode($dataPoints_payment);

													if (!empty($payment_filtered))
													{

														?>
														<script type="text/javascript">
															google.charts.load('current', {
																'packages': ['bar']
															});
															google.charts.setOnLoadCallback(drawChart);

															function drawChart() {
																var data = google.visualization.arrayToDataTable(<?php echo $new_array; ?>);

																var options = {

																	bars: 'vertical', // Required for Material Bar Charts.
																	colors: ['<?php echo get_option('smgt_system_color_code');?>'],

																};

																var chart = new google.charts.Bar(document.getElementById('payment_bar_material'));

																chart.draw(data, google.charts.Bar.convertOptions(options));
															}
														</script>
														<div id="payment_bar_material" style="width:100%;height: 430px; padding:20px;"></div>
													<?php

													}
													else
													{
													?>
														<div class="calendar-event-new">
															<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL . "/assets/images/dashboard_icon/no_data_img.png" ?>">
														</div>
													<?php
													}

												?>

											</div>

										</div>

									</div>

								</div>

							</div><!-- Row Div Start  -->

							<!-- Class and Exam List Row  -->

							<div class="row">

								<div class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left">

									<div class="panel panel-white event priscription">

										<div class="panel-heading ">

											<h3 class="panel-title"><?php esc_html_e('Class','school-mgt');?></h3>

											<a class="page-link123" href="<?php echo admin_url().'admin.php?page=smgt_class'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

										</div>

										<div class="panel-body class_padding">

											<div class="events1">

												<?php

												$class_data = mj_smgt_class_dashboard();

												$i=0;

												if(!empty($class_data))

												{

													foreach ($class_data as $retrieved_data)

													{

														$class_id=$retrieved_data->class_id;

														$user=count(get_users(array(

															'meta_key' => 'class_name',

															'meta_value' => $class_id

														)));



														if($i == 0)

														{

															$color_class='smgt_class_color0';

														}

														elseif($i == 1)

														{

															$color_class='smgt_class_color1';



														}

														elseif($i == 2)

														{

															$color_class='smgt_class_color2';



														}

														elseif($i == 3)

														{

															$color_class='smgt_class_color3';



														}

														elseif($i == 4)

														{

															$color_class='smgt_class_color4';



														}

														?>



														<div class="row smgt-group-list-record profile_image_class class_record_height">

															<div class="cursor_pointer col-sm-2 col-md-2 col-lg-2 col-xl-2 <?php echo $color_class; ?> remainder_title class_tag Bold save1 show_task_event show_task_event_list profile_image_appointment smgt_class_color0" id="<?php echo $retrieved_data->class_id;?>" model="Class Details">

																<img class="class_image_1 center" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Class.png"?>">

															</div>

															<div class="d-flex align-items-center col-sm-7 col-md-7 col-lg-7 col-xl-7 smgt-group-list-record-col-img">

																<div class="cursor_pointer class_font_color cmgt-group-list-group-name remainder_title_pr Bold viewdetail show_task_event" id="<?php echo $retrieved_data->class_id;?>" model="Class Details">

																	<span><?php echo $retrieved_data->class_name;?></span>

																</div>

															</div>

															<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 justify-content-end d-flex align-items-center smgt-group-list-record-col-count">

																<div class="smgt-group-list-total-group">

																	<?php

																		echo $user.' ';

																		esc_attr_e('Out Of', 'school-mgt');

																		echo ' '.$retrieved_data->class_capacity;

																	?>

																</div>

															</div>

														</div>

														<?php

														$i++;

													}

												}

												else

												{

													?>

													<div class="calendar-event-new">

														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

														<div class="col-md-12 dashboard_btn padding_top_30px">

															<a href="<?php echo admin_url().'admin.php?page=smgt_class&tab=addclass'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Class','school-mgt');?></a>

														</div>



													</div>

													<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>

								<div  class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left1">

									<div class="panel panel-white event operation">

										<div class="panel-heading ">

											<h3 class="panel-title"><?php esc_html_e('Exam List','school-mgt');?></h3>

											<a class="page-link123" href="<?php echo admin_url().'admin.php?page=smgt_exam'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

										</div>

										<div class="panel-body">

											<div class="events">

												<?php

												$exam = new smgt_exam;

												$examdata = $exam->mj_smgt_exam_list_for_dashboard();

												$i=0;

												if(!empty($examdata))

												{

													foreach ($examdata as $retrieved_data)

													{

														$cid=$retrieved_data->class_id;

														if($i == 0)

														{

															$color_class='smgt_class_color0';

														}

														elseif($i == 1)

														{

															$color_class='smgt_class_color1';



														}

														elseif($i == 2)

														{

															$color_class='smgt_class_color2';



														}

														elseif($i == 3)

														{

															$color_class='smgt_class_color3';



														}

														elseif($i == 4)

														{

															$color_class='smgt_class_color4';



														}

														?>



														<div class="calendar_event_p calendar-event view-complaint">

															<p class="cursor_pointer smgt_exam_list_img show_task_event <?php echo $color_class;?>" id="<?php echo $retrieved_data->exam_id; ?>" model="Exam Details">

																<img class="class_image_1 center" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Exam_hall.png"?>">

															</p>

															<p class="cursor_pointer smgt_exam_remainder_title_pr remainder_title_pr Bold viewpriscription show_task_event"  id="<?php echo $retrieved_data->exam_id;?>" model="Exam Details">

																<?php echo $retrieved_data->exam_name;?>&nbsp;&nbsp;<span class="smgt_exam_start_date">

																<?php echo get_the_title($retrieved_data->exam_term);?>&nbsp;|&nbsp;<?php echo mj_smgt_get_class_name($cid);?></span>

															</p>

															<p class="smgt_exam_remainder_title_pr smgt_description_line">

																<span class="smgt_activity_date" id="smgt_start_date_end_date"><?php  echo mj_smgt_getdate_in_input_box($retrieved_data->exam_start_date); ?>&nbsp;|&nbsp;<?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_end_date); ?></span>

															</p>

														</div>

														<?php

														$i++;

													}

												}

												else

												{

													?>

													<div class="calendar-event-new">

														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

														<div class="col-md-12 dashboard_btn padding_top_30px">

															<a href="<?php echo admin_url().'admin.php?page=smgt_exam&tab=addexam'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Exam','school-mgt');?></a>

														</div>

													</div>

													<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>



								<div class="col-sm-12 col-md-6 col-lg-6 col-xs-12 responsive_div_dasboard precription_padding_left">

									<div class="panel panel-white event">

										<div class="panel-heading ">

											<h3 class="panel-title"><?php esc_html_e('Notice','school-mgt');?></h3>

											<a class="page-link123" href="<?php echo admin_url().'admin.php?page=smgt_notice'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

										</div>

										<div class="panel-body">

											<div class="events">

												<?php

												$args['post_type'] = 'notice';

												$args['posts_per_page'] = 4;

												$args['post_status'] = 'public';

												$q = new WP_Query();

												$retrieve_class = $q->query($args);



												$format = get_option('date_format');

												$i=0;

												if(!empty($retrieve_class))

												{

													foreach ($retrieve_class as $retrieved_data)

													{

														if($i == 0)

														{

															$color_class='smgt_notice_color0';

														}

														elseif($i == 1)

														{

															$color_class='smgt_notice_color1';



														}

														elseif($i == 2)

														{

															$color_class='smgt_notice_color2';



														}

														elseif($i == 3)

														{

															$color_class='smgt_notice_color3';



														}

														elseif($i == 4)

														{

															$color_class='smgt_notice_color4';

														}

														?>

														<div class="calendar-event notice_div <?php echo $color_class; ?>">

															<div class="notice_div_contant profile_image_prescription">

																<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 notice_description_div">

																	<p class="cursor_pointer remainder_title Bold viewdetail notice_descriptions show_task_event notice_heading notice_content_rs" id="<?php echo esc_attr($retrieved_data->ID); ?>" model="Noticeboard Details" style="width: 100%;">

																		<label for="" class="cursor_pointer notice_heading_label notice_heading">

																			<?php echo esc_html($retrieved_data->post_title); ?>

																		</label>



																		<a href="#" class="notice_date_div">

																		<?php echo mj_smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'start_date',true)); ?> &nbsp;|&nbsp; <?php echo mj_smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'end_date',true)); ?>

																		</a>

																	</p>

																	<p class="cursor_pointer remainder_title viewdetail notice_descriptions" style="width: 100%;"><?php echo esc_html($retrieved_data->post_content); ?></p>

																</div>

															</div>

														</div>

													<?php

													$i++;

													}

												}

												else

												{

													?>

													<div class="calendar-event-new">

														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

														<div class="col-md-12 dashboard_btn padding_top_30px">

															<a href="<?php echo admin_url().'admin.php?page=smgt_notice&tab=addnotice'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Notice','school-mgt');?></a>

														</div>

													</div>

													<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>

								<div class="col-sm-12 col-md-6 col-lg-6 col-xs-12 responsive_div_dasboard precription_padding_left1">

									<div class="panel panel-white massage">

										<div class="panel-heading">

											<h3 class="panel-title"><?php esc_html_e('Event List','school-mgt');?></h3>

											<a class="page-link123" href="<?php echo admin_url().'admin.php?page=smgt_event&tab=eventlist'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

										</div>

										<div class="panel-body">

											<div class="events notice_content_div">

												<?php

												$event_data = $obj_event->MJ_smgt_get_all_event_for_dashboard();

												$i=0;

												if(!empty($event_data))

												{

													foreach ($event_data as $retrieved_data)

													{

														if($i == 0)

														{

															$color_class='smgt_class_color0';

														}

														elseif($i == 1)

														{

															$color_class='smgt_class_color1';



														}

														elseif($i == 2)

														{

															$color_class='smgt_class_color2';



														}

														elseif($i == 3)

														{

															$color_class='smgt_class_color3';



														}

														elseif($i == 4)

														{

															$color_class='smgt_class_color4';



														}

														?>

														<div class="calendar-event profile_image_class">



															<p class="cursor_pointer class_tag Bold save1 show_task_event show_task_event_list profile_image_appointment <?php echo $color_class; ?>" id="<?php echo $retrieved_data->event_id; ?>" model="Event Details">

																<img class="class_image center" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/notice.png"?>">

															</p>

															<p class="cursor_pointer padding_top_5px_res remainder_title_pr card_content_width show_task_event padding_top_card_content viewpriscription class_width" style="color: #333333;"  id="<?php echo $retrieved_data->event_id; ?>" model="Event Details">

																<?php echo $retrieved_data->event_title; ?>

															</p>

															<p class="remainder_date_pr date_background class_width"> <label for="" class="label_for_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->start_date); ?></label> </p>

															<p class="remainder_title_pr viewpriscription card_content_width class_width assignbed_name1 card_margin_top">

																<?php

																	$strlength = strlen($retrieved_data->description);

																	if ($strlength > 90)

																	{

																		echo substr($retrieved_data->description, 10, 90) . '...';

																	} else

																	{

																		echo $retrieved_data->description;

																	}

																?>

															</p>



														</div>

														<?php

														$i++;

													}

												}

												else

												{

													?>

													<div class="calendar-event-new">

														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

														<div class="col-md-12 dashboard_btn padding_top_30px">

															<a href="<?php echo admin_url().'admin.php?page=smgt_event&tab=add_event'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('Add Event','school-mgt');?></a>

														</div>

													</div>

													<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>

								<div class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left">

									<div class="panel panel-white event priscription">

										<div class="panel-heading ">

											<h3 class="panel-title"><?php esc_html_e('Notification','school-mgt');?></h3>

											<a class="page-link123" href="<?php echo admin_url().'admin.php?page=smgt_notification'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

										</div>

										<div class="panel-body message_rtl_css">

											<div class="events1">

												<?php

												$notification_data = mj_smgt_notification_dashboard();



												$i=0;

												if(!empty($notification_data))

												{

													foreach ($notification_data as $retrieved_data)

													{



														if($i == 0)

														{

															$color_class='smgt_class_color0';

														}

														elseif($i == 1)

														{

															$color_class='smgt_class_color1';



														}

														elseif($i == 2)

														{

															$color_class='smgt_class_color2';



														}

														elseif($i == 3)

														{

															$color_class='smgt_class_color3';



														}

														elseif($i == 4)

														{

															$color_class='smgt_class_color4';



														}

														?>



														<div class="calendar-event profile_image_class">

															<p class="cursor_pointer remainder_title_pr Bold viewpriscription show_task_event class_tag <?php echo $color_class; ?>" id="<?php echo esc_attr($retrieved_data->notification_id); ?>" model="Notification Details" >

																<img class="class_image center" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Notification.png"?>">

															</p>

															<p class="cursor_pointer padding_top_5px_res card_content_width remainder_title_pr viewpriscription show_task_event class_width padding_top_card_content" id="<?php echo esc_attr($retrieved_data->notification_id); ?>" model="Notification Details" style="color: #333333;">

																<?php echo $retrieved_data->title; ?>

															</p>

															<p class="remainder_date_pr date_background class_width"> <label for="" class="label_for_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date); ?></label> </p>

															<p class="remainder_title_pr card_content_width viewpriscription class_width assignbed_name1 card_margin_top" >

																<?php echo $retrieved_data->message; ?>

															</p>

														</div>

												<?php

												$i++;

													}

												}

												else

												{

													?>

													<div class="calendar-event-new">

														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

														<div class="col-md-12 dashboard_btn padding_top_30px">

															<a href="<?php echo admin_url().'admin.php?page=smgt_notification&tab=addnotification'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Notification','school-mgt');?></a>

														</div>



													</div>

													<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>

								<div  class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left1">

									<div class="panel panel-white event operation">

										<div class="panel-heading ">

											<h3 class="panel-title"><?php esc_html_e('Holiday List','school-mgt');?></h3>

											<a class="page-link123" href="<?php echo admin_url().'admin.php?page=smgt_holiday'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

										</div>

										<div class="panel-body">

											<div class="events rtl_notice_css">

												<?php

												$holidaydata = mj_smgt_holiday_dashboard();



												$i=0;

												if(!empty($holidaydata))

												{

													foreach ($holidaydata as $retrieved_data)

													{

														if($i == 0)

														{

															$color_class='smgt_class_color0';

														}

														elseif($i == 1)

														{

															$color_class='smgt_class_color1';



														}

														elseif($i == 2)

														{

															$color_class='smgt_class_color2';



														}

														elseif($i == 3)

														{

															$color_class='smgt_class_color3';



														}

														elseif($i == 4)

														{

															$color_class='smgt_class_color4';



														}



														if($retrieved_data->status == 0)

														{



															?>

															<div class="calendar-event profile_image_class">



																<p class="cursor_pointer remainder_title class_tag Bold save1 show_task_event show_task_event_list profile_image_appointment <?php echo $color_class; ?>" id="<?php echo $retrieved_data->holiday_id; ?>" model="holiday Details">

																	<img class="class_image center" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Holiday.png"?>">

																</p>

																<p class="cursor_pointer holiday_list_description_res remainder_title_pr show_task_event padding_top_card_content viewpriscription holiday_width" style="color: #333333;"  id="<?php echo $retrieved_data->holiday_id; ?>" model="holiday Details">

																	<?php echo $retrieved_data->holiday_title; ?> <span class="date_div_color"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->date); ?> | <?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date); ?></span>

																</p>

																<p class="remainder_title_pr holiday_list_description_res viewpriscription holiday_width assignbed_name1 card_margin_top">

																	<?php

																		echo $retrieved_data->description;

																	?>

																</p>



															</div>

															<?php

														}

														$i++;

													}

												}

												else

												{

													?>

													<div class="calendar-event-new">

														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

														<div class="col-md-12 dashboard_btn padding_top_30px">

															<a href="<?php echo admin_url().'admin.php?page=smgt_holiday&tab=addholiday'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Holiday','school-mgt');?></a>

														</div>

													</div>

													<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>


								<div class="col-sm-12 col-md-6 col-lg-6 col-xs-12 responsive_div_dasboard precription_padding_left1">

									<div class="panel panel-white massage">

										<div class="panel-heading">

											<h3 class="panel-title"><?php esc_html_e('Message','school-mgt');?></h3>

											<a class="page-link123" href="<?php echo admin_url().'admin.php?page=smgt_message'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

										</div>

										<div class="panel-body">

											<div class="events notice_content_div">

												<?php

												//$message_data = mj_smgt_message_dashboard();

												$max = 5;

												if(isset($_GET['pg']))

												{

													$p = $_GET['pg'];

												}

												else

												{

													$p = 1;

												}

												$limit = ($p - 1) * $max;



												$post_id=0;

												$message_data = mj_smgt_get_inbox_message(get_current_user_id(),$limit,$max);



												$i=0;

												if(!empty($message_data))

												{

													foreach ($message_data as $retrieved_data)

													{

														if($i == 0)

														{

															$color_class='smgt_class_color0';

														}

														elseif($i == 1)

														{

															$color_class='smgt_class_color1';



														}

														elseif($i == 2)

														{

															$color_class='smgt_class_color2';



														}

														elseif($i == 3)

														{

															$color_class='smgt_class_color3';



														}

														elseif($i == 4)

														{

															$color_class='smgt_class_color4';



														}

														?>

														<div class="calendar-event profile_image_class">



															<p class="cursor_pointer class_tag Bold save1 show_task_event show_task_event_list profile_image_appointment <?php echo $color_class; ?>" id="<?php echo $retrieved_data->message_id; ?>" model="Message Details">

																<img class="class_image center" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Message_Chat.png"?>">

															</p>

															<p class="cursor_pointer padding_top_5px_res remainder_title_pr card_content_width show_task_event padding_top_card_content viewpriscription class_width" style="color: #333333;"  id="<?php echo $retrieved_data->message_id; ?>" model="Message Details">

																<?php echo $retrieved_data->subject; ?>

															</p>

															<p class="remainder_date_pr date_background class_width"> <label for="" class="label_for_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->date); ?></label> </p>

															<p class="remainder_title_pr viewpriscription card_content_width class_width assignbed_name1 card_margin_top">

																<?php

																	$strlength = strlen($retrieved_data->message_body);

																	if ($strlength > 90)

																	{

																		echo substr($retrieved_data->message_body, 10, 90) . '...';

																	} else

																	{

																		echo $retrieved_data->message_body;

																	}

																?>

															</p>



														</div>

														<?php

														$i++;

													}

												}

												else

												{

													?>

													<div class="calendar-event-new">

														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

														<div class="col-md-12 dashboard_btn padding_top_30px">

															<a href="<?php echo admin_url().'admin.php?page=smgt_message&tab=compose'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Message','school-mgt');?></a>

														</div>

													</div>

												<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>

								<div  class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left1">

									<div class="panel panel-white event operation">

										<div class="panel-heading ">

											<h3 class="panel-title"><?php esc_html_e('Homework List','school-mgt');?></h3>

											<a class="page-link123" href="<?php echo admin_url().'admin.php?page=smgt_student_homewrok'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

										</div>

										<div class="panel-body">

											<div class="events rtl_notice_css">

												<?php

												$homework_data = MJ_smgt_get_homework_data_for_dashboard();

												$i=0;
												if(!empty($homework_data))
												{
													foreach ($homework_data as $retrieved_data)
													{
														if($i == 0)
														{
															$color_class='smgt_class_color0';
														}
														elseif($i == 1)
														{
															$color_class='smgt_class_color1';
														}
														elseif($i == 2)
														{
															$color_class='smgt_class_color2';
														}
														elseif($i == 3)
														{
															$color_class='smgt_class_color3';
														}
														elseif($i == 4)
														{
															$color_class='smgt_class_color4';
														}

														?>

														<div class="calendar-event profile_image_class">
															<p class="cursor_pointer class_tag Bold save1 show_task_event show_task_event_list profile_image_appointment <?php echo $color_class; ?>" id="<?php echo $retrieved_data->homework_id; ?>" model="homework Details">

																<img class="class_image center" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>">

															</p>

															<p class="cursor_pointer padding_top_5px_res remainder_title_pr card_content_width show_task_event padding_top_card_content viewpriscription class_width homework_dashboard_rtl" style="color: #333333;"  id="<?php echo $retrieved_data->homework_id; ?>" model="homework Details">

																<?php echo $retrieved_data->title; ?>

															</p>

															<p class="remainder_date_pr date_background class_width homework_date_rtl"> <label for="" class="label_for_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->submition_date); ?></label> </p>
															<p class="remainder_title_pr viewpriscription card_content_width class_width assignbed_name1 card_margin_top homework_dashboard_rtl">
																<?php
																	echo smgt_get_class_section_name_wise($retrieved_data->class_name,$retrieved_data->section_id);
																?>
															</p>
														</div>

														<?php


															$i++;


													}

												}

												else

												{

													?>

													<div class="calendar-event-new">

														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

														<div class="col-md-12 dashboard_btn padding_top_30px">

															<a href="<?php echo admin_url().'admin.php?page=smgt_student_homewrok&tab=addhomework'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('Add Homework','school-mgt');?></a>

														</div>

													</div>

													<?php

												}

												?>

											</div>

										</div>

									</div>

								</div>

							</div>

							<?php

						}

						?>

						</div>

					</div>

				</div>

				<!-- End dashboard content div -->

			</div>

			<!-- Footer Part Start  -->
			<footer class='smgt-footer'>
				<p>
					<?php
					echo esc_html__('Copyright ©', 'school-mgt') . date('Y') . ' <a href="https://mojoomla.com/" target="_blank">' . esc_html__('Mojoomla.', 'school-mgt') . '</a> ' . esc_html__('All rights reserved.', 'school-mgt');
					?>
				</p>
			</footer>

			<!-- Footer Part End  -->

		</body>

		<!-- body part End  -->

	</html>

<!-- End task-event POP-UP Code -->
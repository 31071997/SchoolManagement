<?php


// render template//

$obj_feespayment= new mj_smgt_feespayment();



if(isset($_REQUEST['STATUS']) && $_REQUEST['STATUS'] == 'TXN_SUCCESS')



{



    $trasaction_id  = $_REQUEST["TXNID"];



	$custom_array = explode("_",$_REQUEST['ORDERID']);



	$feedata['fees_pay_id']=$custom_array[1];



	$feedata['amount']=$_REQUEST['TXNAMOUNT'];



	$feedata['payment_method']='Paytm';



	$feedata['trasaction_id']=$trasaction_id;







	$PaymentSucces = $obj_feespayment->mj_smgt_add_feespayment_history($feedata);



	if($PaymentSucces)



	{



		wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&action=success');

		die;

	}







}

if(isset($_REQUEST['payment_status']) && $_REQUEST['action'] == 'paypal_payment' && $_REQUEST['payment_status'] == 'Completed')



{



	$trasaction_id  = $_POST["txn_id"];



	$custom_array = explode("_",$_POST['custom']);



	$feedata['fees_pay_id']=$custom_array[1];



	$feedata['amount']=$_POST['mc_gross_1'];



	$feedata['payment_method']='paypal';



	$feedata['trasaction_id']=$trasaction_id ;



	$PaymentSucces = $obj_feespayment->mj_smgt_add_feespayment_history($feedata);



	if($PaymentSucces)



	{



		wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&action=success');



		die;

	}



}



global $current_user;

$user_roles = $current_user->roles;

$user_role = array_shift($user_roles);



if($user_role != 'teacher' && $user_role != 'student'  && $user_role != 'parent'  && $user_role != 'supportstaff')

{

	wp_redirect ( admin_url () . 'admin.php?page=smgt_school' );

	exit;

}

if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf')

{

	$sudent_id = $_REQUEST['student'];

	mj_smgt_downlosd_smgt_result_pdf($sudent_id);

}

$obj_attend = new Attendence_Manage ();

$school_obj = new School_Management ( get_current_user_id () );

$obj_route = new Class_routine ();

$obj_event = new event_Manage();

$obj_virtual_classroom = new mj_smgt_virtual_classroom();

$notive_array = array ();

$cal_array = array ();

//--------- User Student ---------//

if($school_obj->role=='student')

{
	$class = $school_obj->class_info;
    $sectionname="";
    $section=0;
    $section = get_user_meta(get_current_user_id(),'class_section',true);
	if($section != "")
	{

		$sectionname = mj_smgt_get_section_name($section);
	}
	else

	{

		$section=0;

	}

	foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )

	{

		$period = $obj_route->mj_smgt_get_periad($class->class_id,$section,$daykey );

		if(!empty( $period ))

		{

			foreach ( $period as $period_data )

			{

				if (get_option('smgt_enable_virtual_classroom') == 'yes')

				{

					$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

					if (!empty($meeting_data))

					{

						$color = 'rgb(46, 138, 194)';

					}

					else

					{

						$color = 'rgb(91,112,222)';

					}

				}

				else

				{

					$meeting_data = '';

					$color = 'rgb(91,112,222)';

				}

				if(!empty($meeting_data))

				{

					$meeting_stat_link = $meeting_data->meeting_start_link;

					$meeting_join_link = $meeting_data->meeting_join_link;

					$agenda = $meeting_data->agenda;

				}

				else

				{

					$meeting_stat_link = '';

					$meeting_join_link = '';

					$agenda = '';

				}



				$teacher_obj = new Smgt_Teacher;

				$classes = $teacher_obj->mj_smgt_get_singal_class_teacher($period_data->class_id);

				$stime = explode(":",$period_data->start_time);

				$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);

				$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);

				// $start_am_pm=$stime[2];

				$start_time = $start_hour.':'.$start_min;

				$start_time_data = new DateTime($start_time);

		   		$starttime=date_format($start_time_data,'H:i:s');



		   		$etime = explode(":",$period_data->end_time);

				$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);

				$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);

				// $end_am_pm=$etime[2];

				$end_time = $end_hour.':'.$end_min;

				$end_time_data = new DateTime($end_time);

		   		$edittime=date_format($end_time_data,'H:i:s');

				$user = get_userdata( $classes->teacher_id );

				$notive_array [] = array (

				'type' =>  'class',

				'title' => mj_smgt_get_single_subject_name($period_data->subject_id),

				'class_name' => mj_smgt_get_class_name( $period_data->class_id ),

				'subject' => mj_smgt_get_single_subject_name($period_data->subject_id),

				'start' => $starttime,

				'end' => $edittime,

				'agenda' => $agenda,

				'teacher' => $user->display_name,

				'role' => 'student',

				'meeting_start_link' => $meeting_stat_link,

				'meeting_join_link' => $meeting_join_link,

				'dow' => [ $daykey ] ,

				'color' => $color

				);

			}

		}

	}

    $class_id = $school_obj->class_info->class_id;

	$class_section = $school_obj->class_info->class_section;
	$notice_list_student = mj_smgt_student_notice_dashbord($class_id,$class_section);

	if (! empty ($notice_list_student))

	{
		foreach ($notice_list_student as $notice )
		{
			$notice_start_date=get_post_meta($notice->ID,'start_date',true);
			$notice_end_date=get_post_meta($notice->ID,'end_date',true);
			$notice_comment = $notice->post_content;
			if(!empty($notice->post_content))
			{
				$notice_comment = $notice->post_content;
			}
			else
			{
				$notice_comment = "N/A";
			}
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

			$start_to_end_date = mj_smgt_getdate_in_input_box($notice_start_date).' '.$to.' '.mj_smgt_getdate_in_input_box($notice_end_date);
			$notice_for = ucfirst(get_post_meta($notice->ID, 'notice_for',true));

			$notice_title = $notice->post_title;
				$i=1;
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

	if(isset($class_id) && $section == '')
	{
		$exam_list	= 	mj_smgt_get_all_exam_by_class_id($class_id);
	}
	else
	{
		$exam_list	= mj_smgt_get_all_exam_by_class_id_and_section_id_array($class_id,$section);
	}
	// Exam List For Student
	if (!empty($exam_list))

	{

		foreach ($exam_list as $exam) {

			$exam_start_date = $exam->exam_start_date;

			$exam_end_date = $exam->exam_end_date;

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



}



//---------- user parents -----------//

if($school_obj->role=='parent')

{

	$chil_array =$school_obj->child_list;

	if(!empty($chil_array))

	{

		foreach($chil_array as $child_id)

		{

			$sectionname="";

			$section=0;

			$class = $school_obj->mj_smgt_get_user_class_id($child_id);

			$section = get_user_meta($child_id,'class_section',true);

			if($section!="")

			{

				$sectionname = mj_smgt_get_section_name($section);

			}

			else

			{

				$section=0;

			}

			foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )

			{

				$period = $obj_route->mj_smgt_get_periad($class->class_id,$section,$daykey );

				if(!empty( $period ))

				{

					foreach ( $period as $period_data )

					{

						if (get_option('smgt_enable_virtual_classroom') == 'yes')

						{

							$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

							if (!empty($meeting_data))

							{

								$color = 'rgb(46, 138, 194)';

							}

							else

							{

								$color = 'rgb(91,112,222)';

							}

						}

						else

						{

							$meeting_data = '';

							$color = 'rgb(91,112,222)';

						}

						if(!empty($meeting_data))

						{

							$meeting_stat_link = $meeting_data->meeting_start_link;

							$meeting_join_link = $meeting_data->meeting_join_link;

							$agenda = $meeting_data->agenda;

						}

						else

						{

							$meeting_stat_link = '';

							$meeting_join_link = '';

							$agenda = '';

						}



						$teacher_obj = new Smgt_Teacher;

						$classes = $teacher_obj->mj_smgt_get_singal_class_teacher($period_data->class_id);

						$stime = explode(":",$period_data->start_time);

						$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);

						$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);

						// $start_am_pm=$stime[2];

						$start_time = $start_hour.':'.$start_min;

						$start_time_data = new DateTime($start_time);

				   		$starttime=date_format($start_time_data,'H:i:s');



						if(!empty($route_data->end_time))

						{

							$etime = explode(":",$route_data->end_time);

							$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);

							$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);

							
							$end_time = $end_hour.':'.$end_min;

							$end_time_data = new DateTime($end_time);

							$edittime=date_format($end_time_data,'H:i:s');

						}

						else

						{

							$edittime = '';

						}

						$user = get_userdata( $classes->teacher_id );

						$notive_array [] = array (

						'type' =>  'class',

						'title' => mj_smgt_get_single_subject_name($period_data->subject_id),

						'class_name' => mj_smgt_get_class_name( $period_data->class_id ),

						'subject' => mj_smgt_get_single_subject_name($period_data->subject_id),

						'start' => $starttime,

						'end' => $edittime,

						'agenda' => $agenda,

						'teacher' => $user->display_name,

						'role' => 'parent',

						'meeting_start_link' => $meeting_stat_link,

						'meeting_join_link' => $meeting_join_link,

						'dow' => [ $daykey ] ,

						'color' => $color

						);

					}

				}

			}

		}

	}

	$notice_list_parent = mj_smgt_parent_notice_dashbord();

	if (!empty ($notice_list_parent))

	{

		foreach ($notice_list_parent as $notice )

		{

				$notice_start_date=get_post_meta($notice->ID,'start_date',true);
				$notice_end_date=get_post_meta($notice->ID,'end_date',true);
				$notice_title = $notice->post_title;
				$notice_comment = $notice->post_content;
				$notice_for = ucfirst(get_post_meta($notice->ID, 'notice_for',true));
				if(!empty($notice->post_content))
				{
					$notice_comment = $notice->post_content;
				}
				else
				{
					$notice_comment = "N/A";
				}
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
				$i=1;
				$to =  esc_html__( "To", "school-mgt" );

				$start_to_end_date = mj_smgt_getdate_in_input_box($notice_start_date).' '.$to.' '.mj_smgt_getdate_in_input_box($notice_end_date);

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



	$user_id=get_current_user_id();

	$user_meta =get_user_meta($user_id, 'child', true);

	foreach($user_meta as $c_id)

	{

		$classdata[]=get_user_meta($c_id,'class_name',true);

		$section_id[] = get_user_meta($c_id,'class_section',true);

		$section_new_id = implode(',',$section_id);

		if(!empty($classdata) && $section_new_id == "")

		{

			$result[] = mj_smgt_get_all_exam_by_class_id_array($classdata);

		}

		else

		{

			$result[] = mj_smgt_get_all_exam_by_class_id_and_section_id_array_parent($classdata,$section_id);

		}

	}
	if(!empty($result)){
		$mergedArray = array_merge(...$result);

		$exam_list = array_unique($mergedArray, SORT_REGULAR);
	}
	else{
		$exam_list = "";
	}




	// Exam List For Parent

	if (!empty($exam_list))

	{

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

}



//--------- user support staff -----------//

if($school_obj->role=='supportstaff')

{



	$notice_list_supportstaff = mj_smgt_supportstaff_notice_dashbord();

	if (! empty ($notice_list_supportstaff))

	{

		foreach ($notice_list_supportstaff as $notice )

		{

			$notice_start_date=get_post_meta($notice->ID,'start_date',true);

			$notice_end_date=get_post_meta($notice->ID,'end_date',true);
			$notice_title = $notice->post_title;
			if(!empty($notice->post_content))
			{
				$notice_comment = $notice->post_content;
			}
			else
			{
				$notice_comment = "N/A";
			}
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

			$start_to_end_date = mj_smgt_getdate_in_input_box($notice_start_date).' '.$to.' '.mj_smgt_getdate_in_input_box($notice_end_date);
			$notice_for = ucfirst(get_post_meta($notice->ID, 'notice_for',true));
				$i=1;

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

	$exam_list = mj_smgt_get_all_data('exam');

	if (!empty($exam_list))

	{

		foreach ($exam_list as $exam) {

			$exam_start_date = $exam->exam_start_date;

			$exam_end_date = $exam->exam_end_date;

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

}



//---------- user teacher --------//

if($school_obj->role=='teacher')

{

	if(!empty($school_obj->class_info))

	{

		$class_name = $school_obj->class_info->class_id;

		$class_section = $school_obj->class_info->class_section;

	}

	else

	{

		$class_name = '';

		$class_section = '';

	}

	$route_data = '';

	$notice_list_teacher = mj_smgt_teacher_notice_dashbord($class_name);

	foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )

	{
		$period_1 = $obj_route->mj_smgt_get_periad_by_teacher(get_current_user_id(),$daykey);
		$period_2 = $obj_route->mj_smgt_get_periad_by_particular_teacher(get_current_user_id(),$daykey);

		if(!empty($period_1) && !empty($period_2))
		{
			$period = array_merge($period_1, $period_2);

		}
		elseif(!empty($period_1) && empty($period_2))
		{
			$period = $period_1;
		}
		elseif(empty($period_1) && !empty($period_2))
		{
			$period = $period_2;
		}
		if(!empty( $period ))

		{

			foreach ( $period as $period_data )

			{

				if (get_option('smgt_enable_virtual_classroom') == 'yes')

				{

					$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

					if (!empty($meeting_data))

					{

						$color = 'rgb(46, 138, 194)';

					}

					else

					{

						$color = 'rgb(91,112,222)';

					}

				}

				else

				{

					$meeting_data = '';

					$color = 'rgb(91,112,222)';

				}

				if(!empty($meeting_data))

				{

					$meeting_stat_link = $meeting_data->meeting_start_link;

					$meeting_join_link = $meeting_data->meeting_join_link;

					$agenda = $meeting_data->agenda;

				}

				else

				{

					$meeting_stat_link = '';

					$meeting_join_link = '';

					$agenda = '';

				}

				if(!empty($route_data))

				{

					$stime = explode(":",$period_data->start_time);

					$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);

					$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);

					$start_am_pm=$stime[2];

					$start_time = $start_hour.':'.$start_min.' '.$start_am_pm;

					$start_time_data = new DateTime($start_time);

					$starttime=date_format($start_time_data,'H:i:s');



					$etime = explode(":",$route_data->end_time);

					$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);

					$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);

					$end_am_pm=$etime[2];

					$end_time = $end_hour.':'.$end_min.' '.$end_am_pm;

					$end_time_data = new DateTime($end_time);

					$edittime=date_format($end_time_data,'H:i:s');

				}

				else

				{

					$starttime = '';

					$edittime = '';

				}

				$user = get_userdata( get_current_user_id());

				$notive_array [] = array (

				'type' =>  'class',

				'title' => mj_smgt_get_single_subject_name($period_data->subject_id),

				'class_name' => mj_smgt_get_class_name( $period_data->class_id ),

				'subject' => mj_smgt_get_single_subject_name($period_data->subject_id),

				'start' => $starttime,

				'end' => $edittime,

				'agenda' => $agenda,

				'teacher' => $user->display_name,

				'role' => 'teacher',

				'meeting_start_link' => $meeting_stat_link,

				'dow' => [ $daykey ] ,

				'color' => $color

				);

			}

		}

	}



	if (! empty ($notice_list_teacher)) {



		foreach ($notice_list_teacher as $notice )

		{

			$notice_start_date=get_post_meta($notice->ID,'start_date',true);

			$notice_end_date=get_post_meta($notice->ID,'end_date',true);

			$notice_comment = $notice->post_content;

			if(!empty($notice->post_content))

			{

				$notice_comment = $notice->post_content;

			}

			else

			{

				$notice_comment = "N/A";

			}

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

			$start_to_end_date = mj_smgt_getdate_in_input_box($notice_start_date).' '.$to.' '.mj_smgt_getdate_in_input_box($notice_end_date);

			$notice_title = $notice->post_title;
			$notice_for = ucfirst(get_post_meta($notice->ID, 'notice_for',true));
				$i=1;

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

	$obj_exam=new smgt_exam;

	$user_id = get_current_user_id();

	$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);

	// Exam Data For

	$exam_list	= $obj_exam->mj_smgt_get_all_exam_by_class_id_created_by($class_id,$user_id);

	if (!empty($exam_list))

	{

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



}



//--------- holiday event on calender -----------//

$holiday_list = mj_smgt_get_all_data( 'holiday' );

if (!empty( $holiday_list )) {

	foreach ( $holiday_list as $notice )

	{

		if($notice->status == 0)

		{

			$notice_start_date=$notice->date;

			$notice_end_date=$notice->end_date;
			$holiday_title = $notice->holiday_title;

			$holiday_comment = $notice->description;
			if(!empty($holiday->description))
			{
				$holiday_comment = $holiday->description;

			}
			else
			{
				$holiday_comment ="N/A";
			}
			$i=1;
			$to =  esc_html__( "To", "school-mgt" );

			$start_to_end_date = mj_smgt_getdate_in_input_box($notice_start_date).' '.$to.' '.mj_smgt_getdate_in_input_box($notice_end_date);
			$notive_array[] = array(

				'event_title' => esc_html__( 'Holiday Details', 'school-mgt' ),

				'title' => $notice->holiday_title,

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





if (! is_user_logged_in ()) {

	$page_id = get_option ( 'smgt_login_page' );



	wp_redirect ( home_url () . "?page_id=" . $page_id );

}

if (is_super_admin ()) {

		wp_redirect ( admin_url () . 'admin.php?page=smgt_school' );

}



?>

<!DOCTYPE html>

<html lang="en">



	<head>

		<meta charset="utf-8">

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/Bootstrap/bootstrap-multiselect.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dataTables.min.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/jquery.dataTables.min.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/jquery-ui.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/font-awesome.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/popup.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dynamic_css.php'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/style.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/newversion.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/Bootstrap/bootstrap-timepicker.min.css'; ?>">



		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dashboard.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/fullcalendar.min.css'; ?>">

		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/Bootstrap/bootstrap5.min.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/Bootstrap/bootstrap.min.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/white.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/schoolmgt.min.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/smgt_new_design.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/popping_font.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/smgt_responsive_new_design.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/font-awesome.min.css'; ?>">

		<!------------ Material Design -------------->

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/material/bootstrap-inputs.css'; ?>">

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/material/material.min.js'; ?>"></script>

		<!------------ Material Design -------------->

		<?php if (is_rtl())

		{?>

			<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/Bootstrap/bootstrap-rtl.min.css'; ?>">

			<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/custome_rtl.css'; ?>">

			<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/new_design_rtl.css'; ?>">

			<?php

		}?>



		<!-- <link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/simple-line-icons.css'; ?>"> -->

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine.jquery.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/school-responsive.css'; ?>">

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dataTables.responsive.css'; ?>">



		<!--------------- MULTIPLE SELECT ITEM JS ------------------->

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.min.js'; ?>"></script>

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.css'; ?>">

		<!---------------------- END MULTIPLE SELECT ITEM JS ---------------->





		<?php

		if(@file_exists(get_stylesheet_directory().'/css/smgt-customcss.css')) {

			?>

			<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/smgt-customcss.css" type="text/css" />

			<?php

		}

		else

		{

			?>

			<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/smgt-customcss.css'; ?>">

			<?php

		}

		?>

		

		<!---------------------- TIME PICKER JS AND CSS --------------------->

		
		<!---------------------- TIME PICKER JS AND CSS --------------------->

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/Jquery/jquery-3.6.0.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/smgt_custom.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/popper.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/Bootstrap/bootstrap-multiselect.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/Jquery/jquery.timeago.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/Jquery/jquery-ui.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/Jquery/jquery-ui-lan.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/moment.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/fullcalendar.min.js'; ?>"></script>

		<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/mdtimepicker.min.css'; ?>">
		
		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/mdtimepicker.min.js'; ?>"></script>
		<?php /*--------Full calendar multilanguage---------*/

			$lancode=get_locale();

			$code=substr($lancode,0,2);?>
		<script>
			jQuery(document).ready(function($)
			{
				$.datepicker.setDefaults( $.datepicker.regional[ "<?php echo $code;?>" ] );
			});	
		</script>
		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/calendar-lang/'.$code.'.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/datatables.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/Jquery/jquery.dataTables.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.buttons.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.tableTools.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.editor.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.responsive.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/Bootstrap/bootstrap5.min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/icheck.min.js'; ?>"></script>

		<!-- Print -->

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/smgt-dataTables-buttons-min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/smgt-buttons-print-min.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>

		<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
		
		

		<script>

			$ = jQuery.noConflict();

			var calendar_laungage ="<?php echo mj_smgt_calander_laungage();?>";

			document.addEventListener('DOMContentLoaded', function() {

			var calendarEl = document.getElementById('calendar');

			var calendar = new FullCalendar.Calendar(calendarEl, {



				initialView: 'dayGridMonth',

				dayMaxEventRows: 1,

				locale: calendar_laungage,

				headerToolbar: {

					left: 'prev, today next',

					center: 'title',

					right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'

				},

				events: <?php echo json_encode($notive_array);?>,

				eventClick:  function(event, jsEvent, view)

				{
					// alert(event.event._def.extendedProps.description);
					if(event.event._def.extendedProps.description=='notice')
					{

						$("#event_booked_popup #notice_title").html(event.event._def.extendedProps.notice_title);

						$("#event_booked_popup #start_to_end_date").html(event.event._def.extendedProps.start_to_end_date);

						$("#event_booked_popup #discription").html(event.event._def.extendedProps.notice_comment);

						$("#event_booked_popup #notice_for").html(event.event._def.extendedProps.notice_for);

						$("#event_booked_popup #class_name_111").html(event.event._def.extendedProps.class_name);

						$("#event_booked_popup #redirect").html(event.event._def.extendedProps.redirect);

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

				},

			});

				calendar.render();

			});





		</script>



	</head>
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

	<!-- CLASS BOOK IN CALANDER POPUP HTML CODE -->

	<div id="eventContent" class="modal-body display_none height_auto"><!--MODAL BODY DIV START-->



		<p class="margin_0px"><b><?php esc_html_e('Class Name:','school-mgt');?></b> <span id="class_name"></span></p><br>

		<p class="margin_0px"><b><?php esc_html_e('Subject:','school-mgt');?></b> <span id="subject"></span></p><br>

		<p class="margin_0px"><b><?php esc_html_e('Date:','school-mgt');?> </b> <span id="date"></span></p><br>

		<p class="margin_0px"><b><?php esc_html_e('Time:','school-mgt');?> </b> <span id="time"></span></p><br>

		<p class="margin_0px"><b><?php esc_html_e('Teacher Name:','school-mgt');?></b> <span id="teacher_name"></span></p><br>

		<p id="agenda" class="class_schedule_topic margin_0px"></p><br>

		<p id="meeting_start_link" class="margin_0px"></p>

	</div>

	<!--MODAL BODY DIV END-->

	<body class="schoo-management-content-frontend">

		<?php

		$user = wp_get_current_user ();



		?>

		<!--task-event POP up code -->

		<div class="popup-bg">

			<div class="overlay-content content_width">

				<div class="modal-content d-modal-style">

					<div class="task_event_list">

					</div>

				</div>

			</div>

		</div>

		<!-- End task-event POP-UP Code -->

		<div class="row smgt-header admin_dashboard_main_div" style="margin: 0;">

			<!--HEADER PART IN SET LOGO & TITEL START-->

			<div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 padding_0 hide_frontend_navbar_logo_mobile_app">

				<a href="<?php echo home_url().'?dashboard=user';?>" class='smgt-logo'>

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

			<?php

			if ( is_rtl() )

			{

				$rtl_left_icon_class = "fa-chevron-left";

			}

			else

			{

				$rtl_left_icon_class = "fa-chevron-right";

			}

			?>

			<div class="col-sm-12 col-md-12 col-lg-10 col-xl-10 smgt-right-heder with_100_mobile_app">

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



								if(isset ( $_REQUEST ['dashboard'] ) && $_REQUEST ['dashboard'] == "user" && $page_name == "")

								{

									echo esc_html_e( 'Welcome, ', 'school-mgt' );

									echo $user->display_name;

								}

								elseif($page_name == 'admission')

								{

									if($active_tab == 'addadmission' || $active_tab == 'view_admission')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=admission';?>'>

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

								elseif($page_name == 'class')

								{

									if($active_tab == 'addclass')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=class';?>'>

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

										<a href='<?php echo home_url().'?dashboard=user&page=class';?>'>

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

								elseif($page_name == 'tax')

								{

									if($active_tab == 'add_tax')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=tax';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Edit Tax', 'school-mgt' );

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

								elseif($page_name == 'schedule')

								{

									if($active_tab == 'addroute')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=schedule';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Edit Class Time Table', 'school-mgt' );

										}

										else

										{

											echo esc_html_e( 'Add Class Time Table', 'school-mgt' );

										}

									}

									else

									{

										echo esc_html_e( 'Class Time Table', 'school-mgt' );

									}



								}

								elseif($page_name == 'subject')

								{

									if($active_tab == 'addsubject')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=subject';?>'>

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

								elseif($page_name == 'student')

								{

									$role_name=mj_smgt_get_user_role(get_current_user_id());

									if($active_tab == 'addstudent' || $active_tab == 'view_student')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=student';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Edit Student', 'school-mgt' );

										}

										elseif($active_tab == 'view_student')

										{

											if($role_name == "parent")

											{

												echo esc_html_e('View Child', 'school-mgt' );

											}

											else

											{

												echo esc_html_e( 'View Student', 'school-mgt' );

											}



										}

										else

										{

											echo esc_html_e( 'Add Student', 'school-mgt' );

										}

									}

									elseif($role_name == "parent")

									{

										echo esc_html_e( 'Child', 'school-mgt' );

									}

									else

									{

										echo esc_html_e( 'Student', 'school-mgt' );

									}



								}

								elseif($page_name == 'teacher')

								{

									if($active_tab == 'addteacher' || $active_tab == 'view_teacher')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=teacher';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Edit Teacher', 'school-mgt' );

										}

										elseif($active_tab == 'view_teacher')

										{

											echo esc_html_e( 'View Teacher', 'school-mgt' );

										}

										else

										{

											echo esc_html_e( 'Add Teacher', 'school-mgt' );

										}

									}

									else

									{

										echo esc_html_e( 'Teacher', 'school-mgt' );

									}



								}

								elseif($page_name == 'supportstaff')

								{

									if($active_tab == 'addsupportstaff' || $active_tab == 'view_supportstaff')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=supportstaff';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Edit Support Staff', 'school-mgt' );

										}

										elseif($active_tab == 'view_supportstaff')

										{

											echo esc_html_e( 'View Support Staff', 'school-mgt' );

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

								elseif($page_name == 'parent')

								{

									if($active_tab == 'addparent' || $active_tab == 'view_parent')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=parent';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Edit Parent', 'school-mgt' );

										}

										elseif($active_tab == 'view_parent')

										{

											echo esc_html_e( 'View Parent', 'school-mgt' );

										}

										else

										{

											echo esc_html_e( 'Add Parent', 'school-mgt' );

										}

									}

									else

									{

										echo esc_html_e( 'Parent', 'school-mgt' );

									}



								}

								elseif($page_name == 'exam')

								{

									if($active_tab == 'addexam' || $active_tab == 'exam_time_table')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=exam';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Exam', 'school-mgt' );

										}

										elseif($active_tab == 'exam_time_table')

										{

											echo esc_html_e( 'Exam Time Table', 'school-mgt' );

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

								elseif($page_name == 'exam_hall')

								{

									if($active_tab == 'addhall' || $active_tab == 'exam_hall_receipt')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=exam_hall';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Exam Hall', 'school-mgt' );

										}

										elseif($active_tab == 'exam_hall_receipt')

										{

											echo esc_html_e( 'Exam Hall Receipt', 'school-mgt' );

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

								elseif($page_name == 'manage_marks')

								{

									if($page_name == 'manage_marks' && $active_tab == 'result')

									{

										echo esc_html_e('Manage Marks', 'school-mgt' );

									}

									elseif($page_name == 'manage_marks' && $active_tab == 'export_marks')

									{

										echo esc_html_e('Export Marks', 'school-mgt' );

									}

									elseif($page_name == 'manage_marks' && $active_tab == 'multiple_subject_marks')

									{

										echo esc_html_e('Multiple Subject Marks', 'school-mgt' );

									}

									else

									{

										echo esc_html_e('Manage Marks', 'school-mgt' );

									}

								}

								elseif($page_name == 'grade')

								{

									if($active_tab == 'addgrade')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=grade';?>'>

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

								elseif($page_name == 'virtual_classroom')

								{

									if($active_tab == 'view_past_participle_list' )

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=virtual_classroom';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										echo esc_html_e( 'Participant List', 'school-mgt' );

									}

									elseif($active_tab == 'edit_meeting' && $action == 'edit')

									{
										?>

										<a href='<?php echo home_url().'?dashboard=user&page=virtual_classroom';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php
										echo esc_html_e( 'Edit Virtual Classroom', 'school-mgt' );

									}

									else

									{

										echo esc_html_e( 'Virtual Classroom', 'school-mgt' );

									}

								}

								elseif($page_name == 'homework')

								{

									if($active_tab == 'addhomework')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=homework';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit')

										{

											echo esc_html_e('Edit Homework', 'school-mgt' );

										}

										else

										{

											echo esc_html_e( 'Add Homework', 'school-mgt' );

										}

									}

									elseif($active_tab == 'view_stud_detail')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=homework';?>'>

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

								elseif($page_name == 'attendance')

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

								elseif($page_name == 'library')

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

								elseif($page_name == 'feepayment')

								{

									if($active_tab == 'feeslist' || $active_tab =="")

									{
										if($user_role == "student" || $user_role == "parent")
										{
											echo esc_html_e( 'Fees Payment', 'school-mgt' );
										}
										else
										{
											echo esc_html_e( 'Fees Type', 'school-mgt' );
										}

									}

									elseif($active_tab == 'feepaymentlist')

									{

										echo esc_html_e( 'Fees Payment', 'school-mgt' );

									}
									elseif($active_tab == 'recurring_feespaymentlist')

									{

										echo esc_html_e( 'Recurring Fees Payment', 'school-mgt' );

									}


									if($active_tab == 'addfeetype')

									{

										echo esc_html_e( 'Fees Type', 'school-mgt' );

									}

									elseif($active_tab == 'addpaymentfee')

									{

										echo esc_html_e( 'Fees Payment', 'school-mgt' );

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

										<a href='<?php echo home_url().'?dashboard=user&page=feepayment&tab=feepaymentlist';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										echo esc_html_e( 'View Fees Payment Invoice', 'school-mgt' );

									}

								}

								// --- Hostel Module Start -- //

								elseif($page_name == 'hostel')

								{

									if($page_name == 'hostel' && $active_tab == 'hostel_list')

									{

										echo esc_html_e( 'Hostel', 'school-mgt' );

									}

									elseif($page_name == 'hostel' && $active_tab == 'room_list')

									{

										echo esc_html_e( 'Room', 'school-mgt' );

									}

									elseif($page_name == 'hostel' && $active_tab == 'bed_list')

									{

										echo esc_html_e( 'Beds', 'school-mgt' );

									}

									elseif($page_name == 'hostel' && $active_tab == 'add_hostel')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=hostel_list';?>'>

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
									elseif($page_name == 'hostel' && $active_tab == 'hostel_room_list')

									{

										?>
										<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=hostel_list';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>
										<?php
										echo esc_html__( 'Hostel Room List', 'school-mgt' );

									}
									elseif($page_name == 'hostel' && $active_tab == 'add_room')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=room_list';?>'>

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
									elseif($page_name == 'hostel' && $active_tab == 'assign_bed_list')

									{
										?>
										<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=room_list';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>
										<?php
										echo esc_html_e( 'Assign Bed List', 'school-mgt' );
									}
									elseif($page_name == 'hostel' && $active_tab == 'assign_room')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=room_list';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										echo esc_html_e('Assign Room', 'school-mgt' );

									}

									elseif($page_name == 'hostel' && $active_tab == 'add_bed')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=bed_list';?>'>

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

								}// --- Hostel Module End -- //

								elseif($page_name == 'transport')

								{

									if($active_tab == 'addtransport')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=transport&tab=transport_list';?>'>

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

								elseif($page_name == 'leave')

								{

									if($active_tab == 'add_leave')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=leave&tab=leave_list';?>'>

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

								elseif($page_name == 'custom_field')

								{

									if($active_tab == 'add_custome_field')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=custom_field&tab=custome_field_list';?>'>

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

								elseif($page_name == 'migration')

								{

									echo esc_html_e( 'Migration', 'school-mgt' );

								}

								elseif($page_name == 'holiday')

								{

									if($active_tab == 'addholiday')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=holiday&tab=holidaylist';?>'>

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

								elseif($page_name == 'notice')

								{

									if($active_tab == 'addnotice')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=notice&tab=noticelist';?>'>

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

								elseif($page_name == 'event')

								{

									if($active_tab == 'add_event')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=event&tab=eventlist';?>'>

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

								elseif($page_name == 'sms_setting')

								{

									echo esc_html_e( 'SMS Settings', 'school-mgt' );

								}

								elseif($page_name == 'email_template')

								{

									echo esc_html_e( 'Email Template', 'school-mgt' );

								}

								elseif($page_name == 'payment')

								{

									if($active_tab == 'paymentlist')

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



									if($active_tab == 'addinvoice')

									{

										echo esc_html_e( 'Payment', 'school-mgt' );

									}

									elseif($active_tab == 'addincome')

									{

										echo esc_html_e( 'Income', 'school-mgt' );

									}

									elseif($active_tab == 'addexpense')

									{

										echo esc_html_e( 'Expense', 'school-mgt' );

									}

									elseif($active_tab == 'view_invoice')

									{

										if($_REQUEST['invoice_type'] == 'invoice')

										{

											?>

											<a href='<?php echo home_url().'?dashboard=user&page=payment&tab=paymentlist';?>'>

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

											</a>

											<?php

										}

										elseif($_REQUEST['invoice_type'] == 'income')

										{

											?>

											<a href='<?php echo home_url().'?dashboard=user&page=payment&tab=incomelist';?>'>

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

											</a>

											<?php

										}

										elseif($_REQUEST['invoice_type'] == 'expense')

										{

											?>

											<a href='<?php echo home_url().'?dashboard=user&page=payment&tab=expenselist';?>'>

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

											</a>

											<?php

										}

										echo esc_html_e( 'View Payment Invoice', 'school-mgt' );

									}

								}

								elseif($page_name == 'message')

								{

									echo esc_html_e( 'Message', 'school-mgt' );

								}

								elseif($page_name == 'general_settings')

								{

									echo esc_html_e( 'General Settings', 'school-mgt' );

								}

								elseif($page_name == 'notification')

								{

									if($active_tab == 'addnotification')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=notification';?>'>

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

								elseif($page_name == 'account')

								{

									echo esc_html_e( 'Account', 'school-mgt' );

								}

								elseif($page_name == 'report')

								{

									echo esc_html_e( 'Report', 'school-mgt' );

								}

								elseif($page_name == 'document')

								{

									if($active_tab == 'add_document')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=document&tab=documentlist';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Back_Arrow.png"?>">

										</a>

										<?php

										if($action == 'edit'){

											echo esc_html_e('Edit Document', 'school-mgt' );

										}

										else

										{

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

								$user_access=mj_smgt_get_userrole_wise_access_right_array();



								if($page_name == "admission" && $active_tab != 'addadmission' && $action != 'view_admission')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=admission&tab=addadmission';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}



								}

								elseif($page_name == "class" && $active_tab != 'addclass' && $active_tab != 'class_wise_student_list')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=class&tab=addclass';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}
								elseif($page_name == "tax" && $active_tab != 'add_tax')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=tax&tab=add_tax';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}
								elseif($page_name == "schedule" && $active_tab != 'addroute')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=schedule&tab=addroute';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}
								elseif($page_name == "virtual_classroom" && $active_tab != 'edit_meeting' && $active_tab != 'view_past_participle_list')
								{

									if($user_role == "teacher" || $user_role =="supportstaff")
									{

									?>
									
									<a href='<?php echo home_url().'?dashboard=user&page=schedule&tab=addroute';?>'>

										<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

									</a>
									
									<?php
									}
								}

								elseif($page_name == "subject" && $active_tab != 'addsubject')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=subject&tab=addsubject';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "student" && $active_tab != 'addstudent' && $active_tab != 'view_student')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=student&tab=addstudent';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "teacher" && $active_tab != 'addteacher' && $active_tab != 'view_teacher')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=teacher&tab=addteacher';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "parent" && $active_tab != 'addparent' && $active_tab != 'view_parent')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=parent&tab=addparent';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "exam" && $active_tab != 'addexam' && $active_tab != 'exam_time_table')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=exam&tab=addexam';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "exam_hall" && $active_tab != 'addhall' && $active_tab != 'exam_hall_receipt')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=exam_hall&tab=addhall';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "grade" && $active_tab != 'addgrade')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=grade&tab=addgrade';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "homework" && $active_tab != 'addhomework' && $active_tab != 'view_stud_detail')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=homework&tab=addhomework';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "feepayment")

								{
									if($active_tab != 'view_fesspayment')

									{

										if($page_name == 'feepayment' && $active_tab != 'addfeetype' && $active_tab != 'feepaymentlist' && $active_tab != 'recurring_feespaymentlist' && $active_tab != 'addrecurringpayment'  && $active_tab != 'addpaymentfee')

										{

											if($user_access['add'] == '1')

											{

												?>

												<a href='<?php echo home_url().'?dashboard=user&page=feepayment&tab=addfeetype';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

										elseif($active_tab == 'feepaymentlist' || $active_tab == 'recurring_feespaymentlist')

										{

											if($user_access['add'] == '1')

											{

												?>

												<a href='<?php echo home_url().'?dashboard=user&page=feepayment&tab=addpaymentfee';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

												<?php

											}

										}

									}



								}

								elseif($page_name == "payment")

								{

									if($active_tab == 'paymentlist')

									{

										if($user_access['add'] == '1')

										{

											?>

											<a href='<?php echo home_url().'?dashboard=user&page=payment&tab=addinvoice';?>'>

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

											</a>

											<?php

										}

									}

									elseif($active_tab == 'incomelist')

									{

										if($user_access['add'] == '1')

										{

											?>

											<a href='<?php echo home_url().'?dashboard=user&page=payment&tab=addincome';?>'>

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

											</a>

											<?php

										}

									}

									elseif($active_tab == 'expenselist')

									{

										if($user_access['add'] == '1')

										{

											?>

											<a href='<?php echo home_url().'?dashboard=user&page=payment&tab=addexpense';?>'>

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

											</a>

											<?php

										}

									}

								}

								// --- hostel module Add Btn start  -----//

								elseif($page_name == "hostel")

								{

									if($active_tab == 'hostel_list' && $active_tab != 'add_hostel')

									{

										if($user_access['add'] == '1')

										{

											?>

											<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=add_hostel&action=insert';?>'>

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

											</a>

											<?php

										}

									}

									elseif($active_tab == 'room_list' && $active_tab != 'add_room')

									{

										if($user_access['add'] == '1')

										{

											?>

											<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=add_room&action=insert';?>'>

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

											</a>

											<?php

										}

									}

									elseif($active_tab == 'bed_list' && $active_tab != 'add_bed')

									{

										if($user_access['add'] == '1')

										{

											?>

												<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=add_bed&action=insert';?>'>

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

												</a>

											<?php

										}

									}

									else

									{



									}

								}// --- hostel module Add Btn start  -----//

								elseif($page_name == "transport" && $active_tab != 'addtransport')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=transport&tab=addtransport';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "leave" && $active_tab != 'add_leave')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=leave&tab=add_leave';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "custom_field" && $active_tab != 'add_custome_field')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=custom_field&tab=add_custome_field';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "holiday" && $active_tab != 'addholiday')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=holiday&tab=addholiday';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "notice" && $active_tab != 'addnotice')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=notice&tab=addnotice';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "event" && $active_tab != 'add_event')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=event&tab=add_event';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "message" && $active_tab != 'compose')

								{

									if($user_access['add']=='1')

									{  ?>

										<a href='<?php echo home_url().'?dashboard=user&page=message&tab=compose';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "notification" && $active_tab != 'addnotification')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=notification&tab=addnotification';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "library" && $active_tab == 'issuelist')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=library&tab=issuebook';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "library" && $active_tab == 'booklist')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=library&tab=addbook';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								elseif($page_name == "document" && $active_tab != 'add_document')

								{

									if($user_access['add'] == '1')

									{

										?>

										<a href='<?php echo home_url().'?dashboard=user&page=document&tab=add_document';?>'>

											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Add_new_Button.png" ?>">

										</a>

										<?php

									}

								}

								?>

							</div>

						</div>

					</div>

					<!-- Right Header  -->

					<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">

						<div class="smgt-setting-notification">

							<a href='<?php echo home_url().'?dashboard=user&page=notification';?>' class="smgt-setting-notification-bg">

								<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Bell-Notification.png"?>" class="smgt-right-heder-list-link">

								<spna class="between_border123 smgt-right-heder-list-link"> </span>

							</a>

							<div class="smgt-user-dropdown">

								<ul class="">

									<!-- BEGIN USER LOGIN DROPDOWN -->

									<li class="">

										<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

											<?php

												$role_name=mj_smgt_get_user_role(get_current_user_id());

												$user_info = get_userdata(get_current_user_id());

												$userimage=$user_info->smgt_user_avatar;

											?>

											<img src="

											<?php

												if(!empty($userimage))

												{

													echo $userimage;

												}

												else

												{

													if($role_name == "student")

													{

														echo get_option( 'smgt_student_thumb_new' );

													}

													elseif($role_name == "teacher")

													{

														echo get_option( 'smgt_teacher_thumb_new' );

													}

													elseif($role_name == "supportstaff")

													{

														echo get_option( 'smgt_supportstaff_thumb_new' );

													}

													elseif($role_name == "parent")

													{

														echo get_option( 'smgt_parent_thumb_new' );

													}

												}

											?>" class="smgt-dropdown-userimg">

										</a>

										<ul class="dropdown-menu extended action_dropdawn logout_dropdown_menu logout heder-dropdown-menu" aria-labelledby="dropdownMenuLink">

											<li class="float_left_width_100 ">

												<a class="dropdown-item smgt-back-wp float_left_width_100" href="?dashboard=user&page=account"><i class="fa fa-user"></i>

												<?php esc_html_e( 'My Profile', 'school-mgt' ); ?></a>

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

		<div class="row main_page admin_dashboard_menu_rs"  style="margin: 0;">

			<div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 padding_0 hide_frontend_navbar_mobile_app" id="main_sidebar-bgcolor">

				<?php

				$page_name='';

				if(!empty($_REQUEST ['page']))

				{

					$page_name = $_REQUEST ['page'];

				}

				?>

				<!-- menu sidebar main div strat  -->

				<div class="main_sidebar">

					<nav id="sidebar">

						<ul class='smgt-navigation frontend_smgt_navigation navbar-collapse rs_side_menu_bgcolor' id="navbarNav">

							<li class="card-icon">

								<a href="<?php echo home_url().'?dashboard=user';?>" class="<?php if (isset ( $_REQUEST ['dashboard'] ) && $_REQUEST ['dashboard'] == "user" && $page_name == "") { echo "active"; } ?>">

									<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/dashboards.png"?>">

									<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/dashboards.png"?>">

									<span><?php esc_html_e( 'Dashboard', 'school-mgt' ); ?></span>

								</a>

							</li>

							<?php

							$page = 'admission';

							$admission=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($admission)

							{

								?>

								<li class="card-icon">

									<a href='<?php echo home_url().'?dashboard=user&page=admission';?>' class="<?php if (isset ( $page_name ) && $page_name == "admission") { echo "active"; } ?>">

									<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Admission.png"?>">

									<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Admission.png"?>">

									<span><?php esc_html_e( 'Admission', 'school-mgt' ); ?></span>

									</a>

								</li>

								<?php

							}

							$class_page='class';

							$routine_page='schedule';

							$virtual_class='virtual_classroom';

							$subject_page='subject';

							$class_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($class_page);

							$routine_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($routine_page);

							$virtual_class_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($virtual_class);

							$subject_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($subject_page);

							if($class_page_1 == 1 || $routine_page_1 == 1 || $virtual_class_page_1 == 1 || $subject_page_1 == 1)

							{

								?>

								<li class="has-submenu nav-item card-icon">

									<a href='#' class="nav-link <?php if (isset ( $page_name ) && $page_name == "class" || $page_name && $page_name == "schedule" || $page_name && $page_name == "virtual_classroom" || $page_name && $page_name == "subject" ) { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Class.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Class.png"?>">

										<span><?php esc_html_e('Class', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu'>

										<?php

										$page = 'class';

										$class=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($class)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=class';?>' class="<?php if (isset ( $page_name ) && $page_name == "class") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Class', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'schedule';

										$schedule=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($schedule)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=schedule';?>' class="<?php if (isset ( $page_name ) && $page_name == "schedule") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Class Routine', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'virtual_classroom';

										$virtual_classroom=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($virtual_classroom)

										{

											if(get_option('smgt_enable_virtual_classroom') == "yes")

											{

												?>

												<li class=''>

													<a href='<?php echo home_url().'?dashboard=user&page=virtual_classroom';?>' class="<?php if (isset ( $page_name ) && $page_name == "virtual_classroom") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Virtual Classroom', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

										}

										$page = 'subject';

										$subject=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($subject)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=subject';?>' class="<?php if (isset ( $page_name ) && $page_name == "subject") { echo "active"; } ?>">

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

							$student_page='student';

							$teacher_page='teacher';

							$supportstaff_page='supportstaff';

							$parent_page='parent';

							$student_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($student_page);

							$teacher_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($teacher_page);

							$supportstaff_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($supportstaff_page);

							$parent_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($parent_page);

							if($student_page_1 == 1 || $teacher_page_1 == 1 || $supportstaff_page_1 == 1 || $parent_page_1 == 1)

							{

								?>

								<li class="has-submenu nav-item card-icon">

									<a href='#' class="nav-link <?php if (isset ( $page_name ) && $page_name == "student" || $page_name && $page_name == "teacher" || $page_name && $page_name == "supportstaff" || $page_name && $page_name == "parent" ) { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/user-black.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/user-white.png"?>">

										<span class="margin_left_15px"><?php esc_html_e('Users', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu'>

										<?php

										$page='student';

										$student=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($student==1)

										{

											$role_name=mj_smgt_get_user_role(get_current_user_id());

											if($role_name == "parent")

											{

												?>

												<li class=''>

													<a href='<?php echo home_url().'?dashboard=user&page=student';?>' class="<?php if (isset ( $page_name ) && $page_name == "student") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Child', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}

											else

											{

												?>

												<li class=''>

													<a href='<?php echo home_url().'?dashboard=user&page=student';?>' class="<?php if (isset ( $page_name ) && $page_name == "student") { echo "active"; } ?>">

													<span><?php esc_html_e( 'Student', 'school-mgt' ); ?></span>

													</a>

												</li>

												<?php

											}



										}

										$page='teacher';

										$teacher=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($teacher==1)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=teacher';?>' class="<?php if (isset ( $page_name ) && $page_name == "teacher") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Teacher', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page='supportstaff';

										$supportstaff=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($supportstaff==1)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=supportstaff';?>' class="<?php if (isset ( $page_name ) && $page_name == "supportstaff") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Support Staff', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page='parent';

										$parent=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($parent==1)

										{

											?>

											<li class="">

												<a href='<?php echo home_url().'?dashboard=user&page=parent';?>' class="<?php if (isset ( $page_name ) && $page_name == "parent") { echo "active"; } ?>">

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

							$exam_page='exam';

							$exam_hall_page='exam_hall';

							$manage_marks_page='manage_marks';

							$grade_page='grade';

							$exam_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($exam_page);

							$exam_hall_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($exam_hall_page);

							$manage_marks_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($manage_marks_page);

							$grade_page_1=mj_smgt_page_access_rolewise_accessright_dashboard($grade_page);

							if($exam_page_1 == 1 || $exam_hall_page_1 == 1 || $manage_marks_page_1 == 1 || $grade_page_1 == 1)

							{

								?>

								<li class="has-submenu nav-item card-icon">

									<a href='#' class="nav-link <?php if (isset ( $page_name ) && $page_name == "exam" || $page_name && $page_name == "exam_hall" || $page_name && $page_name == "manage_marks" || $page_name && $page_name == "grade" || $page_name && $page_name == "migration" ) { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Exam.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Exam.png"?>">

										<span class=""><?php esc_html_e('Student Evaluation', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu'>

										<?php

										$page='exam';

										$exam_page=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($exam_page==1)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=exam';?>' class="<?php if (isset ( $page_name ) && $page_name == "exam") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Exam', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page='exam_hall';

										$exam_hall=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($exam_hall==1)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=exam_hall';?>' class="<?php if (isset ( $page_name ) && $page_name == "exam_hall") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Exam Hall', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page='manage_marks';

										$manage_marks=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($manage_marks==1)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=manage_marks';?>' class="<?php if (isset ( $page_name ) && $page_name == "manage_marks") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Manage Marks', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page='grade';

										$grade=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($grade==1)

										{

											?>

											<li class="">

												<a href='<?php echo home_url().'?dashboard=user&page=grade';?>' class="<?php if (isset ( $page_name ) && $page_name == "grade") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Grade', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page='migration';

										$migration=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($migration==1)

										{

											?>

											<li class="">

												<a href='<?php echo home_url().'?dashboard=user&page=migration';?>' class="<?php if (isset ( $page_name ) && $page_name == "migration") { echo "active"; } ?>">

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

							$page = 'homework';

							$homework=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($homework)

							{

								?>

								<li class="card-icon">

									<a href='<?php echo home_url().'?dashboard=user&page=homework';?>' class="<?php if (isset ( $page_name ) && $page_name == "homework") { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/homework.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>">

										<span><?php esc_html_e( 'Homework', 'school-mgt' ); ?></span>

									</a>

								</li>

								<?php

							}

							$page = 'attendance';

							$attendance=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($attendance)

							{

								?>

								<li class="has-submenu nav-item card-icon">

									<a href='#' class="<?php if (isset ( $page_name ) && $page_name == "attendance") { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Attendance.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>">

										<span><?php esc_html_e( 'Attendance', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu'>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=attendance&tab=student_attendance';?>' class="">

											<span><?php esc_html_e( 'Student Attendance', 'school-mgt' ); ?></span>

											</a>

										</li>

										<?php

										if($school_obj->role == 'supportstaff' || $school_obj->role == 'teacher'){

										?>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=attendance&tab=teacher_attendance';?>' class="">

											<span><?php esc_html_e( 'Teacher Attendance', 'school-mgt' ); ?></span>

											</a>

										</li>

										<?php

										}

										?>

									</ul>

								</li>

								<?php

							}

							$page = 'document';

							$document=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($document)

							{

								?>

								<li class="card-icon">

									<a href='<?php echo home_url().'?dashboard=user&page=document';?>' class="<?php if (isset ( $page_name ) && $page_name == "document") { echo "active"; } ?>">

									<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/document.png"?>">

									<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/document.png"?>">

									<span><?php esc_html_e( 'Documents', 'school-mgt' ); ?></span>

									</a>

								</li>

								<?php

							}

							$page = 'leave';

							$leave=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($leave)

							{

								?>

								<li class="card-icon">

									<a href='<?php echo home_url().'?dashboard=user&page=leave';?>' class="<?php if (isset ( $page_name ) && $page_name == "leave") { echo "active"; } ?>">

									<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/leave.png"?>">

									<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/leave.png"?>">

									<span><?php esc_html_e( 'Leave', 'school-mgt' ); ?></span>

									</a>

								</li>

								<?php

							}

							$feepayment = 'feepayment';

							$payment = 'payment';

							$tax = 'tax';

							$feepayment_1=mj_smgt_page_access_rolewise_accessright_dashboard($feepayment);

							$payment_1=mj_smgt_page_access_rolewise_accessright_dashboard($payment);

							$tax_1=mj_smgt_page_access_rolewise_accessright_dashboard($tax);
							
							if($tax_1 == 1 || $feepayment_1 == 1 ||  $payment_1 == 1)

							{

								?>

								<li class="has-submenu nav-item card-icon">

									<a href='#' class="nav-link <?php if (isset ( $page_name ) && $page_name == "feepayment" || isset ( $page_name ) && $page_name == "payment" || isset ( $page_name ) && $page_name == "tax" ) { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Payment.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>">

										<span><?php esc_html_e('Payment', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu'>

										<?php

										$page = 'tax';

										$tax=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($tax)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=tax';?>' class="<?php if (isset ( $page_name ) && $page_name == "tax") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Tax', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'feepayment';

										$feepayment=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($feepayment)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=feepayment';?>' class="<?php if (isset ( $page_name ) && $page_name == "feepayment") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Fees payment', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'payment';

										$payment=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($payment)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=payment&tab=paymentlist';?>' class="<?php if (isset ( $page_name ) && $page_name == "payment") { echo "active"; } ?>">

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

							$page = 'library';

							$library=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($library)

							{

								?>

								<li class="card-icon">

									<a href='<?php echo home_url().'?dashboard=user&page=library&tab=booklist';?>' class="<?php if (isset ( $page_name ) && $page_name == "library") { echo "active"; } ?>">

									<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Library.png"?>">

									<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Library.png"?>">

									<span><?php esc_html_e( 'Library', 'school-mgt' ); ?></span>

									</a>

								</li>

								<?php

							}

							$page = 'hostel';

							$hostel=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($hostel)

							{

								?>

								<li class="has-submenu nav-item card-icon">

									<a href='#' class="<?php if (isset ( $page_name ) && $page_name == "hostel") { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/hostel.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>">

										<span><?php esc_html_e( 'Hostel', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu'>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=hostel_list';?>' class="<?php if (isset ( $page_name ) && $page_name == "hostel") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Hostel', 'school-mgt' ); ?></span>

											</a>

										</li>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=room_list';?>' class="<?php if (isset ( $page_name ) && $page_name == "hostel") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Room', 'school-mgt' ); ?></span>

											</a>

										</li>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=hostel&tab=bed_list';?>' class="<?php if (isset ( $page_name ) && $page_name == "hostel") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Beds', 'school-mgt' ); ?></span>

											</a>

										</li>

									</ul>

								</li>

								<?php

							}

							$page = 'transport';

							$transport=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($transport)

							{

								?>

								<li class="card-icon">

									<a href='<?php echo home_url().'?dashboard=user&page=transport';?>' class="<?php if (isset ( $page_name ) && $page_name == "transport") { echo "active"; } ?>">

									<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/Transportation.png"?>">

									<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Transportation.png"?>">

									<span><?php esc_html_e( 'Transport', 'school-mgt' ); ?></span>

									</a>

								</li>

								<?php

							}

							$page = 'report';

							$report=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($report)

							{

								?>

								<li class="has-submenu nav-item card-icon report">

									<a href='#' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/report.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/report.png"?>">

										<span><?php esc_html_e( 'Report', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu'>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=report&tab=student_information_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Student Information', 'school-mgt' ); ?></span>

											</a>

										</li>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=report&tab=fianance_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Finance/Payment', 'school-mgt' ); ?></span>

											</a>

										</li>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=report&tab=attendance_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Attendance', 'school-mgt' ); ?></span>

											</a>

										</li>



										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=report&tab=examinations_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Examinations', 'school-mgt' ); ?></span>

											</a>

										</li>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Library', 'school-mgt' ); ?></span>

											</a>

										</li>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=report&tab=hostel_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Hostel', 'school-mgt' ); ?></span>

											</a>

										</li>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=report&tab=user_log_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

											<span><?php esc_html_e( 'User Log', 'school-mgt' ); ?></span>

											</a>

										</li>

										<li class=''>

											<a href='<?php echo home_url().'?dashboard=user&page=report&tab=audit_log_report';?>' class="<?php if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == "report") { echo "active"; } ?>">

											<span><?php esc_html_e( 'Audit Trail Report', 'school-mgt' ); ?></span>

											</a>

										</li>

									</ul>

								</li>

								<?php

							}

							$notice = 'notice';

							$message = 'message';

							$holiday = 'holiday';

							$notification = 'notification';

							$event = 'event';

							$notification_1 = mj_smgt_page_access_rolewise_accessright_dashboard($notification);

							$notice_1=mj_smgt_page_access_rolewise_accessright_dashboard($notice);

							$event_1=mj_smgt_page_access_rolewise_accessright_dashboard($event);

							$message_1=mj_smgt_page_access_rolewise_accessright_dashboard($message);

							$holiday_1=mj_smgt_page_access_rolewise_accessright_dashboard($holiday);

							if($notice_1 == 1 || $event_1 == 1 || $message_1 == 1 || $holiday_1 == 1 || $notification_1 == 1)

							{

								?>

								<li class="has-submenu nav-item card-icon notification_hovor">

									<a href='#' class="nav-link <?php if (isset ( $page_name ) && $page_name == "notice" || $page_name && $page_name == "message" || $page_name && $page_name == "event" || $page_name && $page_name == "notification" || $page_name && $page_name == "holiday" ) { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/notifications.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/notifications.png"?>">

										<span><?php esc_html_e('Notification', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu notification_hovor_dropdown'>

										<?php

										$page = 'notice';

										$notice=mj_smgt_page_access_rolewise_accessright_dashboard($page);



										if($notice)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=notice';?>' class="<?php if (isset ( $page_name ) && $page_name == "notice") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Notice', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'event';

										$event=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($event)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=event';?>' class="<?php if (isset ( $page_name ) && $page_name == "event") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Event', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'message';

										$message=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($message)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=message';?>' class="<?php if (isset ( $page_name ) && $page_name == "message") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Message', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'notification';

										$notification=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($notification)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=notification';?>' class="<?php if (isset ( $page_name ) && $page_name == "notification") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Notification', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'holiday';

										$holiday=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($holiday)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=holiday';?>' class="<?php if (isset ( $page_name ) && $page_name == "holiday") { echo "active"; } ?>">

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



							$custom_field = 'custom_field';

							$sms_setting = 'sms_setting';

							$general_settings = 'general_settings';

							$email_template = 'email_template';

							$custom_field_1=mj_smgt_page_access_rolewise_accessright_dashboard($custom_field);

							$sms_setting_1=mj_smgt_page_access_rolewise_accessright_dashboard($sms_setting);

							$email_template_1=mj_smgt_page_access_rolewise_accessright_dashboard($email_template);

							$general_settings_1=mj_smgt_page_access_rolewise_accessright_dashboard($general_settings);

							if($custom_field_1 == 1 || $sms_setting_1 == 1  || $email_template_1 == 1 )

							{

								?>

								<li class="has-submenu nav-item card-icon">

									<a href='#' class="nav-link <?php if (isset ( $page_name ) && $page_name == "custom_field" || $page_name && $page_name == "sms_setting" || $page_name && $page_name == "email_template") { echo "active"; } ?>">

										<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/setting.png"?>">

										<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/setting.png"?>">

										<span><?php esc_html_e('System Settings', 'school-mgt' ); ?></span>

										<i class="fa <?php echo $rtl_left_icon_class; ?> dropdown-right-icon icon" aria-hidden="true"></i>

										<i class="fa fa-chevron-down icon dropdown-down-icon" aria-hidden="true"></i>

									</a>

									<ul class='submenu dropdown-menu'>

										<?php

										$page = 'custom_field';

										$custom_field=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($custom_field)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=custom_field';?>' class="<?php if (isset ( $page_name ) && $page_name == "custom_field") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Custom Fields', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'sms_setting';

										$sms_setting=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($sms_setting)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=sms_setting';?>' class="<?php if (isset ( $page_name ) && $page_name == "sms_setting") { echo "active"; } ?>">

												<span><?php esc_html_e( 'SMS Settings', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'email_template';

										$email_template=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($email_template)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=email_template';?>' class="<?php if (isset ( $page_name ) && $page_name == "email_template") { echo "active"; } ?>">

												<span><?php esc_html_e( 'Email Template', 'school-mgt' ); ?></span>

												</a>

											</li>

											<?php

										}

										$page = 'general_settings';

										$general_settings=mj_smgt_page_access_rolewise_accessright_dashboard($page);

										if($general_settings)

										{

											?>

											<li class=''>

												<a href='<?php echo home_url().'?dashboard=user&page=general_settings';?>' class="<?php if (isset ( $page_name ) && $page_name == "smgt_gnrl_settings") { echo "active"; } ?>">

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

							$page = 'account';

							$account=mj_smgt_page_access_rolewise_accessright_dashboard($page);

							if($account)

							{

								?>

								<li class="card-icon">

									<a href='<?php echo home_url().'?dashboard=user&page=account';?>' class="<?php if (isset ( $page_name ) && $page_name == "account") { echo "active"; } ?>">

									<img class="icon img-top" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Account.png"?>">

									<img class="icon " src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Account-white.png"?>">

									<span><?php esc_html_e( 'Account', 'school-mgt' ); ?></span>

									</a>

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

			<div class="col col-sm-12 col-md-12 col-lg-10 col-xl-10 dashboard_margin padding_left_0 padding_right_0 with_100_mobile_app">

				<div class="page-inner min_height_1088 frontend_homepage_padding_top">

					<!-- main-wrapper div START-->

					<div id="main-wrapper" class="main-wrapper-div label_margin_top_15px admin_dashboard">

						<?php

						if (isset( $_REQUEST ['page'] ))

						{

							$page_name='';

							if(!empty($_REQUEST ['page']))

							{

								$page_name = $_REQUEST ['page'];

							}

							require_once SMS_PLUGIN_DIR . '/template/'.$page_name.'.php';

						}

						if(isset( $_REQUEST ['dashboard'] ) && $_REQUEST ['dashboard']  == 'user' && $page_name == '')
						{
							$dashboard_result = MJ_smgt_frontend_dashboard_card_access();
							?>

							<!-- Four Card , Chart and Fees Payment Row Div  -->

							<div class="row menu_row dashboard_content_rs user_dashdoard_responsive first_row_padding_top">
							<script src="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.js"></script>

							<link rel="stylesheet" href="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.css">
								<!-- USER REPORT CARD START -->
								 <?php if($dashboard_result['smgt_user_chart'] == "yes")
								 {
								?>
								<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 responsive_div_dasboard">

									<div class="panel panel-white smgt-line-chat" style="height: 405px;">

										<div class="panel-heading mb-2" id="smgt-line-chat-p">

											<h3 class="panel-title" style="float: left;"><?php esc_html_e('Users','school-mgt');?></h3>

										</div>

										<div class="smgt-member-chart">

											<div class="outer">

												<canvas id="userContainer" width="300" height="250"></canvas>

												<p class="percent">

												<?php

												$user_id=get_current_user_id();
												$studentdata = mj_smgt_student_count_for_dashbord_card($user_id,$user_role);
												if(!empty($studentdata)){
													$student_count = count($studentdata);
												}
												else{
													$student_count=0;
												}

												$parentdata	= mj_smgt_parent_count_for_dashbord_card($user_id,$user_role);

												if(!empty($parentdata)){
													$parent_count = count($parentdata);
												}
												else{
													$parent_count=0;
												}

												$teacher = mj_smgt_techer_count_for_dashbord_card(get_current_user_id(),$user_role);

												if(!empty($teacher))
												{
													$teacher_count = count($teacher);
												}
												else{
													$teacher_count = '0';
												}

												$page = 'supportstaff';
												$user_access = mj_smgt_get_userrole_wise_access_right_page_wise_array_for_dashboard($page);
												
													if($user_role == "supportstaff"){
														$staff_count = "1";
													}
													else{
														$user_query = new WP_User_Query(array('role' => 'supportstaff'));

														$staff_count = (int) $user_query->get_total();
													}
												

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
								<?php
							    } ?>
								<!-- USER REPORT CARD END -->
								<!-- STUDENT STATUS REPORT CARD START -->
								<?php
								if($user_role == "supportstaff" || $user_role == "teacher")
								{
								if($dashboard_result['smgt_student_status_chart'] == "yes")
								{
								?>
									<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 responsive_div_dasboard">
										<div class="panel panel-white smgt-line-chat" style="height: 405px;">
											<div class="panel-heading mb-2" id="smgt-line-chat-p">
												<h3 class="panel-title" style="float: left;"><?php esc_html_e('Student Status','school-mgt');?></h3>
												<?php
												$page = 'student';
												$user_access = mj_smgt_get_userrole_wise_access_right_page_wise_array_for_dashboard($page);

												if(isset($user_access['view']) && $user_access['view'] == "1")
												{
													?>
													<a href="<?php echo home_url().'?dashboard=user&page=student'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL . "/assets/images/dashboard_icon/Redirect.png" ?>"></a>
													<?php
												}
												?>
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
								<?php
								 }
								}
								?>
								<!-- PAYMENT STATUS REPORT CARD START -->
								 <?php
								 if($user_role != "teacher")
								 {
									if($dashboard_result['smgt_payment_status_chart'] == "yes")
									{
									?>
									<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 responsive_div_dasboard">
									<div class="panel panel-white smgt-line-chat" style="height: 405px;">

										<div class="panel-heading mb-2" id="smgt-line-chat-p">

											<h3 class="panel-title" style="float: left;"><?php esc_html_e('Payment Status','school-mgt');?></h3>
											<?php
											$page = 'feepayment';
											$user_access = mj_smgt_get_userrole_wise_access_right_page_wise_array_for_dashboard($page);

											if(isset($user_access['view']) && $user_access['view'] == "1")
											{
												?>
												<a href="<?php echo home_url().'?dashboard=user&page=feepayment&tab=feepaymentlist'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL . "/assets/images/dashboard_icon/Redirect.png" ?>"></a>
												<?php
											}
											?>
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
															data: [<?php echo number_format($paid, 2, '.', '') ?>,  <?php echo number_format($unpaid, 2, '.', ''); ?>],
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
									<?php
									}
								 }
								 ?>

								<!-- PAYMENT STATUS REPORT CARD END -->

								<!-- ATTENDANCE REPORT CARD START -->
								 <?php
								 if($user_role == "supportstaff" || $user_role == "teacher")
								 {
									if($dashboard_result['smgt_attendance_chart'] == "yes")
									{
								 ?>
								<div class="col-lg-4 col-md-4 col-xl-4 col-sm-4 responsive_div_dasboard">
									<div class="panel panel-white smgt-line-chat" style="height: 405px;">
										<div class="row mb-3">
											<div class="col-6 col-lg-6 col-md-6 col-xl-6 attendance_report_title">
												<h3 class="panel-title" style="font-size:20px;"><?php esc_html_e('Attendance', 'school-mgt'); ?></h3>
											</div>
											<div class="col-6 col-lg-6 col-md-6 col-xl-6 padding_right_25">

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
									<?php
								 }
								}
								 ?>

								<!-- ATTENDANCE REPORT CARD END -->

								<!-- ATTENDANCE REPORT CARD START -->
								 <?php
								 if($user_role == "supportstaff")
								 {
									if($dashboard_result['smgt_payment_report'] == "yes")
									{
									?>
									<div class="col-lg-4 col-md-4 col-xl-4 col-sm-4 responsive_div_dasboard">
									<div class="panel panel-white smgt-line-chat" style="height: 405px;">
										<div class="row mb-3">
											<div class="col-6 col-lg-7 col-md-8 col-xl-8 attendance_report_title">
												<h3 class="panel-title" style="font-size:20px;"><?php esc_html_e('Payment Report', 'school-mgt'); ?></h3>
											</div>
											<div class="col-6 col-lg-5 col-md-4 col-xl-4 padding_right_25">

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
											<div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-4 col-xs-4 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #99A3A4;"></p>
												<p class="user_report_label"><?php esc_html_e('Paypal', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-4 col-xs-4 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #9B59B6;"></p>
												<p class="user_report_label"><?php esc_html_e('Stripe', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-4 col-xs-4 users_report_label ps-1">
												<p class="users_report_dotcolor" style="background: #CD6155;"></p>
												<p class="user_report_label"><?php esc_html_e('Cash', 'school-mgt'); ?></p>
											</div>
											<div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-4 col-xs-4 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #00BCD4;"></p>
												<p class="user_report_label"><?php esc_html_e('Cheque', 'school-mgt'); ?></p>
											</div>
											<div class="col-8 col-sm-4 col-md-6 col-lg-6 col-xl-8 col-xs-8 users_report_label ps-2">
												<p class="users_report_dotcolor" style="background: #F5B041;"></p>
												<p class="user_report_label"><?php esc_html_e('Bank Transfer', 'school-mgt'); ?></p>
											</div>
										</div>
									</div>
								</div>
									<?php
								 }
								}
								 ?>

								<!-- ATTENDANCE REPORT CARD END -->
								<!-- STUDENT STATUS REPORT CARD END -->
								<?php
								if($dashboard_result['smgt_invoice_chart'] == "yes")
								{
									if($user_role != "teacher")
									{
										?>
											<div class="col-lg-4 col-md-4 col-xs-4 col-sm-4 responsive_div_dasboard precription_padding_left1">

												<div class="panel panel-white admmision_div" style="height: 405px;">

													<div class="panel-heading" id="smgt-line-chat-p">

														<h3 class="panel-title"><?php esc_html_e('Fees Payment','school-mgt');?></h3>
														<?php
														$page='feepayment';

														$feepayment=mj_smgt_page_access_rolewise_accessright_dashboard($page);
														if($feepayment == "1")
														{
															?>
															<a class="page_link1" href="<?php echo home_url().'?dashboard=user&page=feepayment&tab=feepaymentlist'; ?>">
	
																<img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>">
	
															</a>
															<?php
														}
														?>

													</div>

													<div class="panel-body">

														<div class="events1">

															<?php

															$obj_feespayment = new mj_smgt_feespayment();

															$i= 0;

															$user_id = get_current_user_id();

															$feespayment_data = user_vise_fees_payment_for_dashboard($user_id,$user_role);



															$page='feepayment';

															$feepayment_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

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

																		<p class="remainder_title Bold viewbedlist show_task_event date_font_size" id="<?php echo esc_attr($retrieved_data->fees_pay_id); ?>" model="Feespayment Details" style="">

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

																if($feepayment_access_right['add'] == 1)

																{

																	?>

																	<div class="calendar-event-new">

																		<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																		<div class="col-md-12 dashboard_btn">

																			<a href="<?php echo home_url().'?dashboard=user&page=feepayment&tab=addpaymentfee'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('Fees Payment','school-mgt');?></a>

																		</div>

																	</div>

																	<?php

																}

																else

																{

																	?>

																	<div class="calendar-event-new">

																		<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																	</div>

																	<?php

																}

															}

															?>

														</div>

													</div>

												</div>

											</div>
										<?php
									}

								}

								?>

							</div>

							<!-- Four Card , Chart and Fees Payment Row Div  -->



							<!-- Celender And Chart Row  -->

							<div class="row calander-chart-div">

								<?php

								if($user_role == 'student')
								{
									$class_id=get_user_meta(get_current_user_id(), 'class_name',true);

									$section_name=get_user_meta(get_current_user_id(), 'class_section',true);
									?>

									<div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">

										<script type="text/javascript">

											$(document).ready(function()



											{

												var qr_code_urlnew =JSON.stringify({"user_id": '<?php echo get_current_user_id();?>',"class_id":'<?php echo $class_id;?>',"section_id":'<?php echo $section_name;?>',"qr_type":"schoolqr"});

												var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + qr_code_urlnew + '&amp;size=50x50';



												$('.id_card_barcode').attr('src', url);



											});

										</script>

										<div class="smgt-qr-code panel">

											<div class="qr_code_card student_qr">

												<div class="qr_main_div">

													<h3><?php esc_html_e('Scan Below QR For Attendance','school-mgt');?></h3>

													<div class="qr_image_div"><img class="id_card_barcode qr_width" id='barcode' src=''></div>

												</div>

											</div>

										</div>

									</div>

									<?php

								}

								?>

								<!-- calender div start  -->

								<div <?php if($user_role == "teacher" || $user_role == "supportstaff" || $user_role == "parent" ){ ?> class="col-lg-12 col-md-12 col-xs-12 col-sm-12" <?php }else{ ?> class="col-lg-6 col-md-6 col-xs-12 col-sm-12" <?php } ?>>

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



								<?php

								//---------- Attendance report access right ------------//

								$page='class';

								$class_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

								if(!empty($class_access_right))

								{

									if($class_access_right['view'] == 1)

									{

										?>

										<div class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left">

											<div class="panel panel-white event priscription">

												<div class="panel-heading ">

													<h3 class="panel-title"><?php esc_html_e('Class','school-mgt');?></h3>

													<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=class'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

												</div>

												<div class="panel-body class_padding">

													<div class="events1">

														<?php
														$page = 'class';
														$user_access = mj_smgt_get_userrole_wise_access_right_page_wise_array_for_dashboard($page);
														$tablename="smgt_class";
														$user_id=get_current_user_id();
														$own_data=$user_access['own_data'];
														if($school_obj->role == 'teacher')
														{

															if($own_data == '1')
															{
																$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);
																$result	=mj_smgt_get_all_class_data_by_class_array($class_id);
															}
															else
															{
																$result = mj_smgt_get_all_data($tablename);
															}
														}
														//------- EXAM DATA FOR SUPPORT STAFF ---------//
														else
														{
															if($own_data == '1')
															{
																$result = mj_smgt_get_all_class_created_by_user($user_id);
															}
															else
															{
																$result = mj_smgt_get_all_data($tablename);

															}
														}
														$class_data = array_slice($result, 0, 5);

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

															if($class_access_right['add'] == 1)

															{

																?>

																<div class="calendar-event-new">

																	<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																	<div class="col-md-12 dashboard_btn padding_top_30px">

																		<a href="<?php echo home_url().'?dashboard=user&page=class&tab=addclass'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Class','school-mgt');?></a>

																	</div>

																</div>

																<?php

															}

															else

															{

																?>

																<div class="calendar-event-new">

																	<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																</div>

																<?php

															}

														}

														?>

													</div>

												</div>

											</div>

										</div>

										<?php

									}

								}



								//------------ Exam Page Access Right ------------//

								$page='exam';

								$exam_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

								if($exam_access_right['view'] == 1)

								{

									?>

									<!-- exam div start  -->

									<div  class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left1">

										<div class="panel panel-white event operation">

											<div class="panel-heading ">

												<h3 class="panel-title"><?php esc_html_e('Exam List','school-mgt');?></h3>

												<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=exam'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

											</div>

											<div class="panel-body">

												<div class="events">

													<?php

													$exam = new smgt_exam;

													$examdata = mj_smgt_exam_list_data_with_access_for_dashboard($user_role);

													// $examdata = $exam->mj_smgt_exam_list_for_dashboard();

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

														if($exam_access_right['add'] == 1)

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																<div class="col-md-12 dashboard_btn padding_top_30px">

																	<a href="<?php echo home_url().'?dashboard=user&page=exam&tab=addexam'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Exam','school-mgt');?></a>

																</div>

															</div>

															<?php

														}

														else

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

															</div>

															<?php

														}

													}

													?>

												</div>

											</div>

										</div>

									</div>

									<?php

								}

								?>

								<!-- Class and Exam list Row End -->



								<!-- Notice and Massage Row Div Start  -->

								<?php

								//------------ Notice Page Access Right ------------//

								$page='notice';

								$notice_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

								if($notice_access_right['view'] == 1)

								{

									?>

									<div class="col-sm-12 col-md-6 col-lg-6 col-xs-12 responsive_div_dasboard precription_padding_left">

										<div class="panel panel-white event">

											<div class="panel-heading ">

												<h3 class="panel-title"><?php esc_html_e('Notice','school-mgt');?></h3>

												<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=notice'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

											</div>

											<div class="panel-body">

												<div class="events">

													<?php

													$retrieve_class = mj_smgt_notice_list_with_user_access_right($user_role);

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

																			<label for="" class="notice_heading_label notice_heading">

																				<?php echo esc_html($retrieved_data->post_title); ?>

																				<a href="#" class="notice_date_div">

																				<?php echo mj_smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'start_date',true)); ?> &nbsp;|&nbsp; <?php echo mj_smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'end_date',true)); ?>

																				</a>

																			</label>

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

														if($notice_access_right['add'] == 1)

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																<div class="col-md-12 dashboard_btn padding_top_30px">

																	<a href="<?php echo home_url().'?dashboard=user&page=notice&tab=addnotice'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Notice','school-mgt');?></a>

																</div>

															</div>

															<?php

														}

														else

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

															</div>

															<?php

														}

													}

													?>

												</div>

											</div>

										</div>

									</div>

									<?php

								}

								//------------ EVENT Page Access Right ------------//

								$page='event';

								$event_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

								if($event_access_right['view'] == 1)

								{

									?>

									<div class="col-sm-12 col-md-6 col-lg-6 col-xs-12 responsive_div_dasboard precription_padding_left1">

										<div class="panel panel-white massage">

											<div class="panel-heading">

												<h3 class="panel-title"><?php esc_html_e('Event List','school-mgt');?></h3>

												<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=event&tab=eventlist'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

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

														if($event_access_right['add'] == 1)

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																<div class="col-md-12 dashboard_btn padding_top_30px">

																	<a href="<?php echo home_url().'?dashboard=user&page=event&tab=add_event'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('Add Event','school-mgt');?></a>

																</div>

															</div>

															<?php

														}

														else

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

															</div>

															<?php

														}

													}

													?>

												</div>

											</div>

										</div>

									</div>

									<?php

								}

								//------------ Notification Page Access Right ------------//

								$page='notification';

								$notification_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

								if($notification_access_right['view'] == 1)

								{

									?>

									<!-- Holiday And Notification Row Div Start  -->

									<div class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left">

										<div class="panel panel-white event priscription">

											<div class="panel-heading ">

												<h3 class="panel-title"><?php esc_html_e('Notification','school-mgt');?></h3>

												<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=notification'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

											</div>

											<div class="panel-body message_rtl_css">

												<div class="events1">

													<?php

													$user_id = get_current_user_id();

													if($school_obj->role == 'student')

													{

														$notification_data = mj_smgt_get_student_own_notification_created_by_for_dashboard($user_id);

													}

													elseif($school_obj->role == 'teacher')

													{

														$notification_data = mj_smgt_get_all_notification_created_by_for_dashboard($user_id);

													}

													elseif($school_obj->role == 'parent')

													{

														$notification_data = mj_smgt_get_all_notification_for_parent_for_dashboard($user_id);

													}

													else{

														$notification_data = mj_smgt_get_all_notification_created_by($user_id);

													}


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

														if($notification_access_right['add'] == 1)

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																<div class="col-md-12 dashboard_btn padding_top_30px">

																	<a href="<?php echo home_url().'?dashboard=user&page=notification&tab=addnotification'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Notification','school-mgt');?></a>

																</div>

															</div>

															<?php

														}

														else

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

															</div>

															<?php

														}

													}

													?>

												</div>

											</div>

										</div>

									</div>

									<?php

								}

								//------------ holiday Page Access Right ------------//

								$page='holiday';

								$holiday_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

								if($holiday_access_right['view'] == 1)

								{

									?>

									<!------------ notifincation div start  ----------->

									<div  class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left1">

										<div class="panel panel-white event operation">

											<div class="panel-heading ">

												<h3 class="panel-title"><?php esc_html_e('Holiday List','school-mgt');?></h3>

												<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=holiday'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

											</div>

											<div class="panel-body">

												<div class="events">

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

														if($holiday_access_right['add'] == 1)

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																<div class="col-md-12 dashboard_btn padding_top_30px">

																	<a href="<?php echo home_url().'?dashboard=user&page=holiday&tab=addholiday'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Holiday','school-mgt');?></a>

																</div>

															</div>

															<?php

														}

														else

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

															</div>

															<?php

														}

													}

													?>

												</div>

											</div>

										</div>

									</div>

									<!-- Notification Div End -->

									<?php

								}

								//------------ Message Page Access Right ------------//

								$page='message';

								$message_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

								if($message_access_right['view'] == 1)

								{

									?>

									<!-- Message Div start  -->

									<div class="col-sm-12 col-md-6 col-lg-6 col-xs-12 responsive_div_dasboard precription_padding_left1">

										<div class="panel panel-white massage">

											<div class="panel-heading">

												<h3 class="panel-title"><?php esc_html_e('Message','school-mgt');?></h3>

												<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=message'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

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

														if($message_access_right['add'] == 1)

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																<div class="col-md-12 dashboard_btn padding_top_30px">

																	<a href="<?php echo home_url().'?dashboard=user&page=message&tab=compose'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('ADD Message','school-mgt');?></a>

																</div>

															</div>

															<?php

														}

														else

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

															</div>

															<?php

														}

													}

													?>

												</div>

											</div>

										</div>

									</div>

									<!-- Notice and Massage Row Div End  -->

									<?php

								}
								if($user_role == "student")
								{
									$page = 'schedule';
									$schedule = mj_smgt_get_userrole_wise_filter_access_right_array($page);
									if($schedule['view'] == 1)
									{
										$class = $school_obj->class_info;
										$sectionname="";

										$section=0;

										$section = get_user_meta(get_current_user_id(),'class_section',true);
										if($section!="")
										{
											$sectionname = mj_smgt_get_section_name($section);
										}
										else
										{
											$section=0;
										}
										?>
										<div  class="col-md-12 col-lg-12 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left1">
											<div class="panel panel-white event operation" style="height:auto!important">
												<div class="panel-heading">
													<h3 class="panel-title"><?php esc_html_e('Schedule List','school-mgt');?></h3>
													<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=schedule'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>
												</div>
												<div class="panel-body">
													<table class="table table-bordered" cellspacing="0" cellpadding="0" border="0">
														<?php
															foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )
															{
																?>
																<tr>
																	<th width="100"><?php echo $dayname;?></th>
																	<td>

																	<?php

																		$period = $obj_route->mj_smgt_get_periad ( $class->class_id,$section,$daykey );

																		if (! empty ( $period ))

																			foreach ( $period as $period_data )

																			{

																				$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

																				if(!empty($meeting_data))

																				{

																					$data_toggle = 'data-bs-toggle="dropdown"';

																				}

																				else

																				{

																					$data_toggle = '';

																				}

																				echo '<div class="btn-group m-b-sm">';

																				echo '<button class="btn btn-primary class_list_button dropdown-toggle" aria-expanded="false" '.$data_toggle.'><span class="period_box" id=' . $period_data->route_id . '>' . mj_smgt_get_single_subject_name( $period_data->subject_id );

																				$start_time_data = explode(":", $period_data->start_time);

																				$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

																				$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);

																				



																				$end_time_data = explode(":", $period_data->end_time);

																				$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

																				$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);

																				

																				echo '<span class="time"> ('.$start_hour.':'.$start_min.' - '.$end_hour.':'.$end_min.') </span>';

																				$virtual_classroom_page_name = 'virtual_classroom';

																				$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page_name);

																				if (get_option('smgt_enable_virtual_classroom') == 'yes')

																				{

																					if ($virtual_classroom_access_right['view'] == '1')

																					{

																						if(!empty($meeting_data))

																						{

																							$meeting_join_link = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="'.$meeting_data->meeting_join_link.'" target="_blank">'.esc_attr__('Join Virtual Class','school-mgt').'</a></li>';

																						}

																						else

																						{

																							$meeting_join_link = '';

																						}

																					}


																				}
																				echo "<span class='caret'></span></button>";

																				echo '<ul role="menu" class="dropdown-menu schedule_menu">

																					'.$meeting_join_link.'

																				</ul>';

																				echo '</div>';

																			}

																		?>

																	</td>
																</tr>
																<?php
															}
														?>
													</table>
												</div>
											</div>
										</div>
										<?php
									}
								}
								$page='homework';

								$homework_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($page);

								if($homework_access_right['view'] == 1)

								{

									?>

									<div  class="col-md-6 col-lg-6 col-sm-12 com-xs-12 responsive_div_dasboard precription_padding_left1">

										<div class="panel panel-white event operation">

											<div class="panel-heading">
												<?php
												if($user_role == 'parent' || $user_role == 'student')
												{
													?>
													<h3 class="panel-title"><?php esc_html_e('My Homework','school-mgt');?></h3>
													<?php
												}
												else
												{
													?>
													<h3 class="panel-title"><?php esc_html_e('Homework List','school-mgt');?></h3>
													<?php
												}
												?>
											
												<a class="page-link123" href="<?php echo home_url().'?dashboard=user&page=homework'; ?>"><img class="vertical_align_unset" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Redirect.png"?>"></a>

											</div>

											<div class="panel-body">

												<div class="events rtl_notice_css">

													<?php

													$homework_data = MJ_smgt_get_homework_data_for_frontend_dashboard();

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

														if($homework_access_right['add'] == 1)

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

																<div class="col-md-12 dashboard_btn padding_top_30px">

																	<a href="<?php echo home_url().'?dashboard=user&page=homework&tab=addhomework'; ?>" class="btn save_btn event_for_alert line_height_31px"><?php esc_html_e('Add Homework','school-mgt');?></a>

																</div>

															</div>

															<?php

														}

														else

														{

															?>

															<div class="calendar-event-new">

																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

															</div>

															<?php

														}

													}

													?>

												</div>

											</div>

										</div>

									</div>

									<?php

								}

								?>

							</div>

							<?php

						}

						?>

					</div>

				</div>

			</div>

			<!-- End dashboard content div -->

		</div>

		<footer class='smgt-footer'>
			<p>
				<?php
				echo esc_html__('Copyright ©', 'gym_mgt') . date('Y') . ' <a href="https://mojoomla.com/" target="_blank">' . esc_html__('Mojoomla.', 'gym_mgt') . '</a> ' . esc_html__('All rights reserved.', 'gym_mgt');
				?>
			</p>
		</footer>

	</body>

</html>
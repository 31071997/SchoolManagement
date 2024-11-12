<?php
$obj_class=new MJ_gmgt_classschedule;

$obj_nutrition=new MJ_gmgt_nutrition;

$booking_data=$obj_class->MJ_gmgt_get_member_book_class_dashboard(get_current_user_id());


if($user_role == 'member')
{
    //------------ CALENDAR WORKOUT CODE START ------------//

    $assign_workout_view_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array('assign-workout');

    if($assign_workout_view_access['view'] == "1")
    {

        $obj_workouttype = new MJ_gmgt_workouttype;

	    $workout_template = $obj_workouttype->MJ_gmgt_get_assign_workout_data_for_particular_member(get_current_user_id());

        if(!empty($workout_template))
        {
            foreach($workout_template as $workout_data)
            {
                $level = get_the_title($workout_data->level_id);
                
                $array = $obj_workouttype->MJ_gmgt_get_assign_workout_days_by_workout_id($workout_data->workout_id);

                if(!empty($workout_data->workout_name))
                {
                    $workout_name = $workout_data->workout_name;
                }
                else
                {
                    $workout_name = esc_html__( 'No Title', 'gym_mgt' );
                }

                $dayNames = array_column($array, 'day_name');
                
                $days = array_unique($dayNames);
                
                if(!empty($days))
                {
                    foreach ($days as $day) 
                    {
                        $start_date = $workout_data->start_date;

                        $end_date = $workout_data->end_date;

                        // GET WORKOUT DATES FOR CALENDAR BY ASSIGN DATES
                        $dates = MJ_gmgt_get_workout_dates_for_calendar($start_date, $end_date, $day);

                        if(!empty($dates))
                        {
                            foreach($dates as $formatted_date)
                            {
                                if($formatted_date == date('Y-m-d'))
                                {
                                    $redirect = "<a href='?dashboard=user&page=assign-workout&tab=start_workout&btn_name=start_workout_log&workout_id=".esc_attr($workout_data->workout_id)."'>".esc_html__('Start Workout', 'gym_mgt')." </a>";
                                }
                                elseif($formatted_date < date('Y-m-d'))
                                {
                                    $redirect = "<a href='?dashboard=user&page=assign-workout&tab=start_workout&start_date=".$formatted_date."&workout_id=".esc_attr($workout_data->workout_id)."'>".esc_html__('Start Workout', 'gym_mgt')." </a>";
                                }
                                else
                                {
                                    $redirect = "";
                                }
                            
                            
                                $cal_array[] = array (
        
                                    'event_title' => esc_html__( 'Today Workout', 'gym_mgt' ),
        
                                    'workout_title' => $workout_name,
        
                                    'level' => $level,
        
                                    'comment' => $workout_data->description,
        
                                    'workout_duration' => $workout_data->workout_duration.' '.esc_html__( 'Week', 'gym_mgt' ),
        
                                    'day' => esc_html__($day,'gym_mgt'),
        
                                    'type' =>  'my_workout',
        
                                    'description' => 'my_workout',
                    
                                    'title' => $workout_name,
                    
                                    'start' =>$formatted_date,
                    
                                    'end' => $formatted_date,
                    
                                    'color' => '#FFA500',
        
                                    'redirect' => $redirect
                    
                                );
                        
                            }
        
                        }
                    
                    }
            
                }
                
            }
        }
    }

    //------------ CALENDAR CLASS CODE START ------------//

    $class_view_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array('class-schedule');

    if($class_view_access['view'] == "1")
    {
    
        $obj_class=new MJ_gmgt_classschedule;

        $class_data_all=$obj_class->MJ_gmgt_get_all_classes_by_user_membership(); 
        
        if(!empty($class_data_all))
        {
            foreach ($class_data_all as $classdatas)			
            {
                $user_data= get_userdata($classdatas->staff_id);

                $staff_member_name=$user_data->display_name;

                $class_start_end_date = MJ_gmgt_getdate_in_input_box($classdatas->start_date).' '.esc_html__('To','gym_mgt').' '.MJ_gmgt_getdate_in_input_box($classdatas->end_date);

                $date_from =  date('Y-m-d');

                if($date_from == "0000-00-00")
                {
                    $date_from = date('Y-m-d');

                    $date_from = strtotime($date_from);
                }	
                else
                {

                    $date_from =  date('Y-m-d');

                    $date_from = strtotime($date_from);

                }				
                $date_check = $classdatas->end_date; 

                $member_limit = $classdatas->member_limit; 

                if($date_check == "0000-00-00")
                {

                    $date_to = 2050-12-31;

                    $date_to = strtotime($date_to);

                }	
                else
                {

                    $date_to = $classdatas->end_date; 

                    $date_to = strtotime($date_to);

                }

                $new_time = DateTime::createFromFormat('h:i A', $classdatas->start_time);
                
                $startTime_24 = $new_time->format('H:i:s');

                $new_time_end = DateTime::createFromFormat('h:i A', $classdatas->end_time);
                
                $endTime_24 = $new_time_end->format('H:i:s');
                
                $current_time = current_time( "H:i:s",$gmt = 0);
                $class_start_time = $new_time->format('h:i A');
                $class_end_time = $new_time_end->format('h:i A');
                $class_time = MJ_gmgt_timeremovecolonbefoream_pm($class_start_time).' '.esc_html__('To','gym_mgt').' '.MJ_gmgt_timeremovecolonbefoream_pm($class_end_time);

                for ($i=$date_from; $i<=$date_to; $i+=86400)
                {  

                    $date1=date("Y-m-d", $i);

                    $day = date("l", strtotime($date1));

                    $day_array=json_decode($classdatas->day);

                    $class_id=$classdatas->class_id;

                    $booked_class_data=$obj_class->MJ_gmgt_get_book_class_bydate($class_id,$date1);

                    $booked_class_status=$obj_class->MJ_gmgt_get_book_class_status_bydate($class_id,$date1,get_current_user_id());
            

                    $meeting_data_join_link = $obj_virtual_classroom->MJ_gmgt_get_singal_meeting_join_link__class_id_in_zoom($class_id);

                    if(!empty($meeting_data_join_link))
                    {
                        $zoom_link=$meeting_data_join_link;
                    }
                    else
                    {
                        $zoom_link='';
                    }
                
                    
                    if($booked_class_status == "yes")
                    {
                        $color = "#008B00";
                        
                        $enable_service=get_option('gym_class_cancel_booking');
                        $cancel_redirect = "";
                        if($enable_service=='yes')
                        {
                            
                            $book_class = $obj_class->MJ_gmgt_get_book_class_data_bydate($class_id,$date1,get_current_user_id());

                            if(MJ_gmgt_cancel_class($date1,$classdatas->start_time) == '1') 
                            { 
                            $cancel_redirect = "<a href='?dashboard=user&page=class-schedule&tab=class_booking&action=delete&class_booking_id=$book_class->id'  onclick='return confirm('".esc_html__('Do you really want to cancel this class?','gym_mgt')."');'>" .esc_html__('Cancel','gym_mgt'). "</a>";

                            }
                        }

                    
                    }
                    else
                    {
                        $cancel_redirect = "";

                        $color = "#22BAA0";
                    }
                
                    $remaning_memmber=$member_limit -  $booked_class_data;

                    if (is_array($day_array) && in_array($day, $day_array))
                    {
                    
                        $cal_array [] = array (

                            'type' =>  'class',

                            'class_id' => $classdatas->class_id,

                            'day' => $day,

                            'title' => $classdatas->class_name,

                            'trainer' => $staff_member_name,
                            
                            'start' => $date1."T".$startTime_24,

                            'end' => $date1."T".$endTime_24,

                            'color' => $color,

                            'class_start_end_date' => $class_start_end_date,

                            'class_time' => $class_time,

                            'Member_limit' => $member_limit,

                            'remaning_memmber' => $remaning_memmber,

                            'class_date' => $date1,

                            'meeting_data_join_link' => $zoom_link,

                            'current_time' => $current_time,

                            'end_time' => $endTime_24,

                            'booked_class_status' => $booked_class_status,

                            'cancel_redirect' => $cancel_redirect,

                        );
                    
                    }

                }

            } 

        }

    }

	//------------- START GET NOTICE DATA CODE --------------//

    $notice_view_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array('notice');

    if($notice_view_access['view'] == "1")
    {

        if (! empty ( $obj_gym->notice )) 
        {

            foreach ( $obj_gym->notice as $notice ) 
            {
                $notice_start_date=get_post_meta($notice->ID,'gmgt_start_date',true);

                $notice_end_date=get_post_meta($notice->ID,'gmgt_end_date',true);

                if(!empty($notice->post_content))
                {
                    $notice_comment = $notice->post_content;
                }
                else
                {
                    $notice_comment = "N/A";
                }

                $start_to_end_date = $notice_start_date.' '.esc_html__('To','gym_mgt').' '.$notice_end_date;

                $notice_title = $notice->post_title;

                $notice_for = ucfirst(get_post_meta($notice->ID, 'notice_for',true));

                if(get_post_meta( $notice->ID, 'gmgt_class_id',true) != "" && get_post_meta( $notice->ID, 'gmgt_class_id',true) =="all")
                {

                    $class_name = 'All';

                }
                elseif(get_post_meta( $notice->ID, 'gmgt_class_id',true) != "")
                {

                    $class_name = mj_gmgt_get_class_name(get_post_meta($notice->ID, 'gmgt_class_id',true));

                }
                else
                {
                    $class_name='N/A';
                }

                $i=1;

                $cal_array[] = array (

                    'event_title' => esc_html__( 'Notice Details', 'gym_mgt' ),

                    'type' =>  'notice',

                    'notice_title' => $notice_title,

                    'description' => 'notice',

                    'notice_comment' => $notice_comment,

                    'notice_for' => $notice_for,

                    'title' => $notice->post_title,

                    'class_name' => $class_name,

                    'start' => mysql2date('Y-m-d', $notice_start_date ),

                    'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days')),

                    'color' => '#B95CF4',

                    'start_to_end_date' => $start_to_end_date

                );	

            }

        }

    }

	//--------- END GET NOTICE DATA CODE ---------//

	//--------- GET BOOKED CLASSES DATA CODE ----------//
    $class_view_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array('class-schedule');

    if($class_view_access['view'] == "1")
    {
        if (! empty ($booking_data )) 
        {
            foreach ($booking_data as $booking ) 
            {
                $booking_start_date=$booking->class_booking_date;

                $booking_end_date=$booking->class_booking_date;
            }
        }
    }
	//END GET Booked Class DATA CODE //

	//---------------- START NUTRITION DATA CODE -------------------//
    $nutrition_view_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array('nutrition');

    if($nutrition_view_access['view'] == "1")
    {

        $nutritiondata = $obj_nutrition->MJ_gmgt_get_member_nutrition($user_id);

        if (!empty ($nutritiondata)) 
        {
            foreach ( $nutritiondata as $nutrition_data) 
            {

                $start_date = $nutrition_data->start_date;
                
                $end_date = $nutrition_data->expire_date;
                
                $user=get_userdata($nutrition_data->user_id);

                $nutrition_member_title=$user->display_name;

                if(!empty($nutrition_data->nutrition_name))
                {
                    $nutrition_name = $nutrition_data->nutrition_name;
                }
                else
                {
                    $nutrition_name = esc_html__( 'No Title', 'gym_mgt' );
                }

                $intrestid=get_user_meta($nutrition_data->user_id,'intrest_area',true);

                if(!empty($intrestid))
                {

                    $calander_nutrition_membership = get_the_title($intrestid);

                }
                else
                {

                    $calander_nutrition_membership = "N/A";

                }

                $redirect = "<a href='?dashboard=user&page=nutrition&tab=member_nutrition_details&nutrition_id=$nutrition_data->id'>".esc_html__('My Nutrition', 'gym_mgt')." </a>";

                $i=1;

                $cal_array[] = array (

                    'event_title' => esc_html__( 'Nutrition Details', 'gym_mgt' ),

                    'type' =>  'nutrition',

                    'description' => 'nutrition',

                    'title' => $nutrition_name,

                    'nutrition_title'=>$nutrition_name,

                    'nutrition_description'=>$nutrition_data->description,

                    'nutrition_member_title'=>$nutrition_member_title,

                    'nutrition_start_date'=>MJ_gmgt_getdate_in_input_box($start_date),

                    'nutrition_end_date'=>MJ_gmgt_getdate_in_input_box($end_date),

                    'start' => mysql2date('Y-m-d', $start_date ),

                    'end' => date('Y-m-d',strtotime($end_date.' +'.$i.' days')),

                    'redirect' => $redirect,

                    'color' => '#C4A484'

                );	

            }
            
        }
    
    }
	//---------------- END NUTRITION DATA CODE ---------------------//
}
elseif($user_role == 'staff_member')
{

	// START GET RESERVATION DATA CODE //

    $reservation_view_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array('reservation');
    
    if($reservation_view_access['view'] == "1")
    {
        $obj_reservation = new MJ_gmgt_reservation;

        if($reservation_view_access['own_data'] == "1")
        {
            $reservationdata = $obj_reservation->MJ_gmgt_get_reservation_by_created_by_and_staff();
        }
        else{
            $reservationdata = $obj_reservation->MJ_gmgt_get_all_reservation();
        }
       
        $cal_array = array();

        if(!empty($reservationdata))
        {

            foreach ($reservationdata as $retrieved_data)
            {

                $start_time_array = explode(":",$retrieved_data->start_time);

                $start_time_array_new = $start_time_array[0].":".$start_time_array[1]."".$start_time_array[2];

                $start_time_formate =  date("H:i:s", strtotime($start_time_array_new)); 

                $start_time_data = new DateTime($start_time_formate); 

                $starttime=date_format($start_time_data,'H:i:s');		   

                $event_start_date = date('Y-m-d',strtotime($retrieved_data->event_date));

                $aevent_start_date_new=$event_start_date." ".$starttime;  

                $end_time_array = explode(":",$retrieved_data->end_time);

                $abcnew = $end_time_array[0].":".$end_time_array[1]."".$end_time_array[2];

                $Hour_new =  date("H:i:s", strtotime($abcnew)); 

                $dnew = new DateTime($Hour_new); 

                $endtime=date_format($dnew,'H:i:s');

                $event__end_date=$event_start_date." ".$endtime; 
                $starttime_new = date_format($start_time_data,'H:i A');
                $endtime_new = date_format($dnew,'H:i A');
                $reservation_place = get_the_title(esc_html($retrieved_data->place_id));

                $start_to_end_time = MJ_gmgt_timeremovecolonbefoream_pm($starttime_new).' '.esc_html__('To', 'gym_mgt').' '. MJ_gmgt_timeremovecolonbefoream_pm($endtime_new);
                
                $reservation_staffmember = MJ_gmgt_get_display_name(esc_html($retrieved_data->staff_id));

                $i=1;

                $cal_array [] = array 

                (
                    'event_title' => esc_html__( 'Reservation Details', 'gym_mgt' ),

                    'type' =>  'reservationdata',

                    'reservation_title' =>$retrieved_data->event_name,

                    'reservation_date' => MJ_gmgt_getdate_in_input_box($event_start_date),

                    'start_to_end_time' => $start_to_end_time,

                    'reservation_staffmember' => $reservation_staffmember,

                    'reservation_place' => $reservation_place,

                    'title' => $retrieved_data->event_name,

                    'description' => 'reservation',

                    'start' => $aevent_start_date_new,

                    'end' => $event__end_date,

                    'color' => '#FF9054'

                );

            }

        }
    }
	

	//END GET RESERVATION DATA CODE //

	//START GET BIRTHDAY DATA CODE //

	$birthday_boys=get_users(array('role'=>'member'));

	$boys_list="";

	if (! empty ( $birthday_boys )) 

	{

		foreach ( $birthday_boys as $boys ) 

		{

			$startdate = date("Y",strtotime($boys->birth_date));

			$enddate = $startdate + 90;

			$years = range($startdate,$enddate,1);

			foreach($years as $year)

			{	

				$startdate1=date("m-d",strtotime($boys->birth_date));

				$cal_array[] = array (

				'type' =>  'Birthday',

				'title' => $boys->first_name."'s '".esc_html__( 'Birthday', 'gym_mgt' ),

				'start' =>"{$year}-{$startdate1}",

				'end' =>"{$year}-{$startdate1}",

				'backgroundColor' => '#F25656');

			}

		}

			

	}

	//END GET BIRTHDAY DATA CODE //

	//START GET NOTICE DATA CODE //

    $notice_view_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array('notice');

    if($notice_view_access['view'] == "1")
    {

        if(!empty( $obj_gym->notice )) 
        {

            foreach ($obj_gym->notice as $notice) 
            {

                $notice_start_date=get_post_meta($notice->ID,'gmgt_start_date',true);

                $notice_end_date=get_post_meta($notice->ID,'gmgt_end_date',true);

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

                $start_to_end_date = $notice_start_date.' '.esc_html__( 'To', 'gym_mgt' ).' '.$notice_end_date;

                $notice_title = $notice->post_title;

                $notice_for = MJ_gmgt_GetRoleName(get_post_meta( esc_html($notice->ID), 'notice_for',true));



                if(get_post_meta( $notice->ID, 'gmgt_class_id',true) != "" && get_post_meta( $notice->ID, 'gmgt_class_id',true) =="all")

                {

                    $class_name = 'All';

                }

                elseif(get_post_meta( $notice->ID, 'gmgt_class_id',true) != "")

                {

                    $class_name = mj_gmgt_get_class_name(get_post_meta($notice->ID, 'gmgt_class_id',true));

                }

                else

                {

                    $class_name = "N/A";

                }

                $i=1;

                $cal_array[] = array (



                    'event_title' => esc_html__( 'Notice Details', 'gym_mgt' ),



                    'type' =>  'notice',

                        

                    'notice_title' => $notice_title,



                    'description' => 'notice',



                    'notice_comment' => $notice_comment,



                    'notice_for' => $notice_for,



                    'title' => $notice->post_title,



                    'class_name' => $class_name,



                    'start' => mysql2date('Y-m-d', $notice_start_date ),



                    'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days')),



                    'color' => '#B95CF4',



                    'start_to_end_date' => $start_to_end_date

                    

                );	

                    

            }

        }

    }

	//START GET NOTICE DATA CODE //



	//---------------- START CLASS SCHEDULE DATA CODE -------------------//

	$class_data=$obj_class->MJ_gmgt_get_all_classes_by_staffmember(get_current_user_id());	
    
	if(!empty($class_data))
    {
        foreach ($class_data as $retrived_data) 
        {
            

            $membersdata=array();

            $membersdata = $obj_class->MJ_gmgt_get_class_members($retrived_data->class_id);
           
            
            $memberships = 'N/A';
            $membership_name = array();
            if (!empty($membersdata)) {	
                foreach ($membersdata as $key => $val) {
                    $membership_value = MJ_gmgt_get_membership_name($val->membership_id);
                    if (!empty($membership_value)) {
                        $membership_name[] = $membership_value;
                    }
                }
                // Remove duplicate values from the array
                $membership_name = array_unique($membership_name);
                $memberships = implode(", ", $membership_name);
            }
            
            $class_frontend_booking = esc_html__(ucfirst($retrived_data->gmgt_class_book_approve),'gym_mgt');
            
            $day = date("l", strtotime(date("Y-m-d")));
    
            $day_array=json_decode($retrived_data->day);

            $start_date = $retrived_data->start_date;

            $end_date = $retrived_data->end_date;

            $user_data= get_userdata($retrived_data->staff_id);
    
            $staff_member_name=$user_data->display_name;
    
            $new_time = DateTime::createFromFormat('h:i A', $retrived_data->start_time);
            $start_time = $new_time->format('H:i A');
    
            $new_time_end = DateTime::createFromFormat('h:i A', $retrived_data->end_time);
            $end_time = $new_time_end->format('H:i A');
        
            $start_to_end_date = MJ_gmgt_getdate_in_input_box($start_date).' '.esc_html__('To','gym_mgt').' '.MJ_gmgt_getdate_in_input_box($end_date);
            $start_to_end_time = MJ_gmgt_timeremovecolonbefoream_pm($start_time).' '.esc_html__('To','gym_mgt').' '.MJ_gmgt_timeremovecolonbefoream_pm($end_time);
            $member_limit = $retrived_data->member_limit;
           
            if(!empty($day_array))
            {
                foreach($day_array as $days)
                {
                    $dates = MJ_gmgt_get_workout_dates_for_calendar($start_date, $end_date, $days);
                    
                    if(!empty($dates))
                    {
                        foreach($dates as $formatted_date)
                        {
                            $booked_class_data=$obj_class->MJ_gmgt_get_book_class_bydate($retrived_data->class_id,$formatted_date);
           
                            $remaning_memmber=$member_limit -  $booked_class_data;

                            $cal_array [] = array (

                                'event_title' => esc_html__('Class Schedule', 'gym_mgt'),

                                'type' =>  'class_schedule',
        
                                'day' => $day,
        
                                'title' => $retrived_data->class_name,

                                'class_schedule_name' => $retrived_data->class_name,

                                'description' => 'class_schedule',
                                
                                'class_schedule_trainer' => $staff_member_name,
        
                                'start' => $formatted_date,
        
                                'end' => $formatted_date,
        
                                'class_schedule_Member_limit' => $member_limit,

                                'class_start_to_end_time' => $start_to_end_time,
        
                                'class_schedule_remaning_memmber' => $remaning_memmber,
                                
                                'class_membership' => $memberships,

                                'class_frontend_booking' => $class_frontend_booking,
        
                                'class_schedule_class_date' => date("Y-m-d"),
        
                                'color' => '#22BAA0',
        
                            );
                        }
                    }
                    
                }
            }
            
            
            
            
        }
    }

	//---------------- END CLASS SCHEDULE DATA CODE ---------------------//

}
else
{
	//START GET NOTICE DATA CODE //
    $notice_view_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array('notice');

    if($notice_view_access['view'] == "1")
    {
        if (! empty ( $obj_gym->notice )) 
        {

            foreach ( $obj_gym->notice as $notice ) 
            {
                $notice_start_date=get_post_meta($notice->ID,'gmgt_start_date',true);

                $notice_end_date=get_post_meta($notice->ID,'gmgt_end_date',true);

                if(!empty($notice->post_content))
                {
                    $notice_comment = $notice->post_content;
                }
                else
                {
                    $notice_comment = "N/A";
                }

                $start_to_end_date = $notice_start_date.' '.esc_html__('To','gym_mgt').' '.$notice_end_date;

                $notice_title = $notice->post_title;

                $notice_for = ucfirst(get_post_meta($notice->ID, 'notice_for',true));

                if(get_post_meta( $notice->ID, 'gmgt_class_id',true) != "" && get_post_meta( $notice->ID, 'gmgt_class_id',true) =="all")
                {

                    $class_name = 'All';

                }
                elseif(get_post_meta( $notice->ID, 'gmgt_class_id',true) != "")
                {

                    $class_name = mj_gmgt_get_class_name(get_post_meta($notice->ID, 'gmgt_class_id',true));

                }
                else
                {
                    $class_name='N/A';
                }

                $i=1;

                $cal_array[] = array (

                    'event_title' => esc_html__( 'Notice Details', 'gym_mgt' ),

                    'type' =>  'notice',

                    'notice_title' => $notice_title,

                    'description' => 'notice',

                    'notice_comment' => $notice_comment,

                    'notice_for' => $notice_for,

                    'title' => $notice->post_title,

                    'class_name' => $class_name,

                    'start' => mysql2date('Y-m-d', $notice_start_date ),

                    'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days')),

                    'color' => '#B95CF4',

                    'start_to_end_date' => $start_to_end_date

                );	

            }

        }

    }
	//START GET NOTICE DATA CODE //
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

    background-image: url("<?php echo GMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>") !important;

    background-repeat: no-repeat !important;

    float: right;

    color: #fff !important;

    width: 10% !important;

    height: 30px !important;

}

.ui-dialog-titlebar {

    border: 0px solid #aaaaaa !important;

    background: unset !important;

    font-size: 22px !important;

    color: #333333 !important;

    font-weight: 500 !important;

    font-style: normal!important;

    font-family: Poppins!important;

}

<?php

if (!is_rtl())
{
    ?>

    .ui-dialog.ui-corner-all.ui-widget.ui-widget-content.ui-front.ui-draggable.ui-resizable

    {

        left: -500px !important;

    }

    <?php

}

?>

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

        width: auto !important;

        left: 10px !important;

        right: 10px !important;


    }

}

</style>

<!--------------- NOTICE CALENDER POPUP ---------------->

<div id="event_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

    <div class="penal-body">

        <div class="row">

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Notice Title','gym_mgt');?></label><br>

                <label for="" class="label_value" id="calander_notice_title"></label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Start Date To End Date','gym_mgt');?></label><br>

                <label for="" class="label_value" id="calander_start_to_end_date"></label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Notice For','gym_mgt');?></label><br>

                <label for="" class="label_value" id="calander_notice_for"></label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Class Name','gym_mgt');?></label><br>

                <label for="" class="label_value" id="calander_class_name"></label>

            </div>

            <div class="col-md-12 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Comment','gym_mgt');?></label><br>

                <label for="" class="label_value " id="calander_discription"> </label>

            </div>

        </div>  

    </div>

</div>

<!--------------- WORKOUT CALENDER POPUP ---------------->

<div id="workout_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

    <div class="penal-body">

        <div class="row">

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Workout Name','gym_mgt');?></label><br>

                <label for="" class="label_value" id="calander_workout_title"></label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Level','gym_mgt');?></label><br>

                <label for="" class="label_value" id="workout_level"></label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Workout Duration','gym_mgt');?></label><br>

                <label for="" class="label_value" id="workout_duration"></label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Day','gym_mgt');?></label><br>

                <label for="" class="label_value" id="workout_day"></label>

            </div>

            <div class="col-md-12 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Description','gym_mgt');?></label><br>

                <label for="" class="label_value " id="workout_discription"> </label>

            </div>

            <div class="col-md-12 popup_padding_15px">

               <div class="col-md-6 rtl_padding_0px workout_popup_redirect" id="workout_redirect"></div>

            </div>

        </div>  

    </div>

</div>

<!--------------- CLASS SCHEDULE CALENDER POPUP ---------------->

<div id="class_schedule_booked_popup" class="modal-body" style="display:none;"><!--MODAL BODY DIV START-->

    <div class="penal-body">

        <div class="row">

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Class Name','gym_mgt');?></label><br>

                <label for="" class="label_value" id="calander_class_schedule_name"></label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Trainer Name','gym_mgt');?></label><br>

                <label for="" class="label_value" id="calander_class_schedule_trainer"></label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Class Time','gym_mgt');?></label><br>

                <label for="" class="label_value" id="calander_class_schedule_time"></label>

            </div>
        
            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('CLass Capacity','gym_mgt');?></label><br>

                <label for="" class="label_value " id="class_schedule_Member_limit"> </label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Remaining Capacity','gym_mgt');?></label><br>

                <label for="" class="label_value " id="class_schedule_remaning_memmber"> </label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Membership Plan','gym_mgt');?></label><br>

                <label for="" class="label_value " id="class_membership"> </label>

            </div>

            <div class="col-md-6 popup_padding_15px">

                <label for="" class="popup_label_heading"><?php esc_attr_e('Frontend Class Booking','gym_mgt');?></label><br>

                <label for="" class="label_value " id="class_frontend_booking"> </label>

            </div>

        </div>  

    </div>

</div>

<!--------------- RESERVATION CALENDER POPUP ---------------->

<div id="reservation_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

<div class="penal-body">

    <div class="row">

        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Title','gym_mgt');?></label><br>

            <label for="" class="label_value" id="calander_reservation_title"></label>

        </div>

        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Place','gym_mgt');?></label><br>

            <label for="" class="label_value" id="calander_reservation_place"></label>

        </div>

        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Date','gym_mgt');?></label><br>

            <label for="" class="label_value " id="calander_reservation_date"> </label>

        </div>

        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Start Time To End Time','gym_mgt');?></label><br>

            <label for="" class="label_value" id="calander_reservation_start_to_end_time"></label>

        </div>

        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Trainer','gym_mgt');?></label><br>

            <label for="" class="label_value" id="calander_reservation_staffmember"></label>

        </div>

        

    </div>  

</div>

</div>

<!--------------- NUTRITION CALENDER POPUP ---------------->

<div id="nutrition_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

<div class="penal-body">

    <div class="row">
        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Nutrition Title','gym_mgt');?></label><br>

            <label for="" class="label_value" id="nutrition_title"></label>

        </div>

        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Member Name','gym_mgt');?></label><br>

            <label for="" class="label_value" id="calander_member_title"></label>

        </div>

        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Start Date','gym_mgt');?></label><br>

            <label for="" class="label_value " id="nutrition_start_date"> </label>

        </div>

        <div class="col-md-6 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('End Date','gym_mgt');?></label><br>

            <label for="" class="label_value" id="nutrition_end_date"></label>

        </div>

        <div class="col-md-12 popup_padding_15px">

            <label for="" class="popup_label_heading"><?php esc_attr_e('Description','gym_mgt');?></label><br>

            <label for="" class="label_value" id="nutrition_description"></label>

        </div>

        <div class="col-md-12 popup_padding_15px">

            <div class="col-md-6 rtl_padding_0px workout_popup_redirect" id="nutrition_redirect"></div>

        </div>

    </div>  

</div>

</div>

<!-- CLASS BOOK IN CALANDER POPUP HTML CODE -->

<div id="eventContent" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

	<style>

		/* .ui-dialog .ui-dialog-titlebar-close

		{

			margin: -15px -4px 0px 0px;

		} */

	    .ui-dialog 

		{

			margin-left: 70%;

			top: 1920px;

			z-index: 10000;

	    }

		.ui-dialog .ui-dialog-titlebar-close {

			margin: -18px 5px 0px 0px;

			font-size: 20px;

			background: none;

		}

		.ui-widget-header {

			border: none;

			background: none;

			color: #333;

			font-weight: bold;

			font-family: 'Poppins';

		}

		.ui-dialog .ui-dialog-title {

    		font-size: 22px;

		}

		

	</style>



	<div class="penal-body">

		<div class="row">

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_html_e('Name','gym_mgt');?></label><br>

				<label for="" class="label_value" id="class_name"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_html_e('Trainer Name','gym_mgt');?></label><br>

				<label for="" class="label_value" id="staff_member_name"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_html_e('Start Date & End Date','gym_mgt');?></label><br>

				<label for="" class="label_value" id="startdate"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_html_e('Class Time','gym_mgt');?></label><br>

				<label for="" class="label_value" id="class_time"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_html_e('CLass Capacity','gym_mgt');?></label><br>

				<label for="" class="label_value" id="Member_limit"></label>

			</div>

			<div class="col-md-6 popup_padding_15px">

				<label for="" class="popup_label_heading"><?php esc_html_e('Remaining Member','gym_mgt');?></label><br>

				<label for="" class="label_value " id="Remaining_Member_limit"> </label>

			</div>

            <div class="col-md-6 popup_padding_15px">

                <div class="col-md-6 rtl_padding_0px workout_popup_redirect" id="cancel_redirect"></div>

            </div>

		</div>  

	</div>



	<form method="post" class="fd_cal_book_class" accept-charset="utf-8" action="?dashboard=user&page=class-schedule&tab=class_booking&action=book_now">

		<input type="hidden" id="class_name_1" name="class_name_1" value="" />

		<input type="hidden" id="startTime_1" name="startTime_1" value="" />

		<input type="hidden" id="endTime_1" name="endTime_1" value="" />

		<input type="hidden" id="class_id1" name="class_id1" value="" />

		<input type="hidden" id="day_id1" name="day_id1" value="" />

		<input type="hidden" id="Remaining_Member_limit_1" name="Remaining_Member_limit_1" value="" />

		<input type="hidden" id="class_date1" name="class_date" value="" />

		<div class="submit">

			<input type="submit" name="Book_Class" id="d_id" class="btn btn-primary sumit display" value="<?php esc_html_e('Book Class','gym_mgt'); ?>"/>

		</div>

		<?php

		?>

	</form>		

</div><!--MODAL BODY DIV END-->

<!-- END CLASS BOOK IN CALANDER POPUP HTML CODE -->

<div id="zoom_booked_popup" class="modal-body " style="display:none;"><!--MODAL BODY DIV START-->

	<style>

		.ui-dialog .ui-dialog-titlebar-close

		{

			margin: -15px -4px 0px 0px;

		}

	    .ui-dialog 

		{

			margin-left: 70%;

			top: 1920px;

			z-index: 10000;

	    }

	</style>

	<p><b><?php esc_html_e('Class Name:','gym_mgt');?></b> <span id="class_name"></span></p><br>

	<p><b><?php esc_html_e('Start Date & Time:','gym_mgt');?> </b> <span id="startTime"></span></p><br>

	<p><b><?php esc_html_e('End Date & Time:','gym_mgt');?></b> <span id="endTime"></span></p><br>

	<p><b><?php esc_html_e('Trainer Name:','gym_mgt');?></b> <span id="staff_member_name"></span></p><br>

	<p><b><?php esc_html_e('Member Limit In CLass:','gym_mgt');?></b> <span id="Member_limit"></span></p><br>

	<p><b><?php esc_html_e('Remaining Member In Class:','gym_mgt');?></b> <span id="Remaining_Member_limit"></span></p><br>

	<form method="post" accept-charset="utf-8">

		<a id="join_link_href" href="#" class="btn btn-success" target="_blank"><i class="fa fa-video-camera" aria-hidden="true"></i> <?php esc_html_e('Join','gym_mgt');?> </a>

	</form>		

</div><!--MODAL BODY DIV END-->


<script>

var calendar_laungage ="<?php echo MJ_gmgt_get_current_lan_code();?>";

//var $ = jQuery.noConflict();

document.addEventListener('DOMContentLoaded', function() {

var calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {

    dayMaxEventRows: 1,	

    headerToolbar: {

        left: 'prev,today next ',

        center: 'title',

        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'

    },

    locale: calendar_laungage,

        editable: false,

        slotEventOverlap: false,

        eventTimeFormat: { // like '14:30:00'

            hour: 'numeric',

            minute: '2-digit',

            meridiem: 'short'

        },
        initialView: 'timeGridWeek',
        // allow "more" link when too many events

        events: <?php echo json_encode($cal_array);?>,

        forceEventDuration : true,

        //start add class in pop up//

        eventClick:  function(event, jsEvent, view) 
        {
           
            //---------- FOR NOTICE ----------//
           
            if(event.event._def.extendedProps.description=='notice')
            {
                
                $("#event_booked_popup #calander_notice_title").html(event.event._def.extendedProps.notice_title);

                $("#event_booked_popup #calander_start_to_end_date").html(event.event._def.extendedProps.start_to_end_date);

                $("#event_booked_popup #calander_discription").html(event.event._def.extendedProps.notice_comment);	

                $("#event_booked_popup #calander_notice_for").html(event.event._def.extendedProps.notice_for);					

                $("#event_booked_popup #calander_class_name").html(event.event._def.extendedProps.class_name);

                $( "#event_booked_popup" ).removeClass( "display_none" );

                $("#event_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:300 });
            }

            //---------- FOR WORKOUT ----------//

            if(event.event._def.extendedProps.description=='my_workout')
            {
                $("#workout_booked_popup #calander_workout_title").html(event.event._def.extendedProps.workout_title);

                $("#workout_booked_popup #workout_level").html(event.event._def.extendedProps.level);

                $("#workout_booked_popup #workout_duration").html(event.event._def.extendedProps.workout_duration);	

                $("#workout_booked_popup #workout_day").html(event.event._def.extendedProps.day);					

                $("#workout_booked_popup #workout_discription").html(event.event._def.extendedProps.comment);
                
                $("#workout_booked_popup #workout_redirect").html(event.event._def.extendedProps.redirect);

                $( "#workout_booked_popup" ).removeClass( "display_none" );

                $("#workout_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:300 });
            }

            //---------- FOR RESERVATION ----------//

            if(event.event._def.extendedProps.description=='reservation')

            {

                $("#reservation_booked_popup #calander_reservation_title").html(event.event._def.extendedProps.reservation_title);

                $("#reservation_booked_popup #calander_reservation_place").html(event.event._def.extendedProps.reservation_place);

                $("#reservation_booked_popup #calander_reservation_start_to_end_time").html(event.event._def.extendedProps.start_to_end_time);

                $("#reservation_booked_popup #calander_reservation_staffmember").html(event.event._def.extendedProps.reservation_staffmember);	

                $("#reservation_booked_popup #calander_reservation_date").html(event.event._def.extendedProps.reservation_date);					

                $( "#reservation_booked_popup" ).removeClass( "display_none" );

                $("#reservation_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:300 });

            }

            //---------- FOR NUTRISION ----------//

            if(event.event._def.extendedProps.description=='nutrition')
            {

                $("#nutrition_booked_popup #calander_member_title").html(event.event._def.extendedProps.nutrition_member_title);

                $("#nutrition_booked_popup #calander_nutrition_membership").html(event.event._def.extendedProps.calander_nutrition_membership);

                $("#nutrition_booked_popup #nutrition_start_date").html(event.event._def.extendedProps.nutrition_start_date);

                $("#nutrition_booked_popup #nutrition_end_date").html(event.event._def.extendedProps.nutrition_end_date);	

                 $("#nutrition_booked_popup #nutrition_title").html(event.event._def.extendedProps.nutrition_title);	

                  $("#nutrition_booked_popup #nutrition_description").html(event.event._def.extendedProps.nutrition_description);			
                
                $("#nutrition_booked_popup #nutrition_redirect").html(event.event._def.extendedProps.redirect);			

                $( "#nutrition_booked_popup" ).removeClass( "display_none" );

                $("#nutrition_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:300 });

            }

            if(event.event._def.extendedProps.description=='class_schedule')
            {

                $("#class_schedule_booked_popup #calander_class_schedule_name").html(event.event._def.extendedProps.class_schedule_name);

                $("#class_schedule_booked_popup #calander_class_schedule_trainer").html(event.event._def.extendedProps.class_schedule_trainer);

                $("#class_schedule_booked_popup #calander_class_schedule_time").html(event.event._def.extendedProps.class_start_to_end_time);
                
                $("#class_schedule_booked_popup #class_schedule_Member_limit").html(event.event._def.extendedProps.class_schedule_Member_limit);	
                
                $("#class_schedule_booked_popup #class_schedule_remaning_memmber").html(event.event._def.extendedProps.class_schedule_remaning_memmber);

                $("#class_schedule_booked_popup #class_membership").html(event.event._def.extendedProps.class_membership);

                $("#class_schedule_booked_popup #class_frontend_booking").html(event.event._def.extendedProps.class_frontend_booking);

                $( "#class_schedule_booked_popup" ).removeClass( "display_none" );

                $("#class_schedule_booked_popup").dialog({ modal: true, title: event.event._def.extendedProps.event_title,width:550, height:300 });

            }

            <?php $dformate=get_option('gmgt_datepicker_format'); ?>

            var dateformate_value='<?php echo $dformate;?>';	

            if(dateformate_value == 'yy-mm-dd')
            {	

                var dateformate='YYYY-MM-DD h:mm A';

            }
            if(dateformate_value == 'yy/mm/dd')
            {	

                var dateformate='YYYY/MM/DD h:mm A';	

            }
            if(dateformate_value == 'dd-mm-yy')
            {	

                var dateformate='DD-MM-YYYY h:mm A';

            }
            if(dateformate_value == 'mm-dd-yy')
            {	

                var dateformate='MM-DD-YYYY h:mm A';

            }
            if(dateformate_value == 'mm/dd/yy')
            {	

                var dateformate='MM/DD/YYYY h:mm A';	

            }

            $("#eventContent #class_name").html(event.event._def.title);

            $("#eventContent #startdate").html(event.event._def.extendedProps.class_start_end_date);

            $("#eventContent #class_time").html(event.event._def.extendedProps.class_time);

            $("#eventContent #staff_member_name ").html(event.event._def.extendedProps.trainer);

            $("#eventContent #cancel_redirect").html(event.event._def.extendedProps.cancel_redirect);

            $("#eventContent #Member_limit ").html(event.event._def.extendedProps.Member_limit);

            $("#eventContent #Remaining_Member_limit ").html(event.event._def.extendedProps.remaning_memmber);

            $("#eventContent #class_date_id ").html(event.event._def.extendedProps.class_date);

            $("#class_name_1").val(event.event._def.title);

            $("#startTime_1").val(moment(event.event.start).format(dateformate));

            $("#endTime_1").val(moment(event.event.end).format(dateformate));

            $("#staff_member_name_1").val(event.event._def.extendedProps.trainer);

            $("#Member_limit_1").val(event.event._def.extendedProps.Member_limit);

            $("#Remaining_Member_limit_1").val(event.event._def.extendedProps.remaning_memmber);

            $("#class_id1").val(event.event._def.extendedProps.class_id);

            $("#day_id1").val(event.event._def.extendedProps.day);

            $("#class_date1").val(event.event._def.extendedProps.class_date);

            $("#d_id").html();

            //----------FOR ZOOM ----------//

            $("#zoom_booked_popup #class_name").html(event.event._def.title);

            $("#zoom_booked_popup #startTime").html(moment(event.event.start).format(dateformate));

            $("#zoom_booked_popup #endTime").html(moment(event.event.end).format(dateformate)); 

            $("#zoom_booked_popup #staff_member_name ").html(event.event._def.extendedProps.trainer);

            $("#zoom_booked_popup #Member_limit ").html(event.event._def.extendedProps.Member_limit);

            $("#zoom_booked_popup #Remaining_Member_limit ").html(event.event._def.extendedProps.remaning_memmber);

            $("#zoom_booked_popup #class_date_id ").html(event.event._def.extendedProps.class_date);

            //----------------------//

            var today = new Date();
         

            var current_time = event.event._def.extendedProps.current_time;

            var end_time  = event.event._def.extendedProps.end_time;

            
            
            var class_dates= event.event._def.extendedProps.class_date;

            var class_ids= event.event._def.extendedProps.class_id;

            var booked_class_status= event.event._def.extendedProps.booked_class_status;

            var meeting_data_join_link= event.event._def.extendedProps.meeting_data_join_link;

            var dd = today.getDate();

            var mm = today.getMonth()+1; 

            var yyyy = today.getFullYear();

            if(dd<10) 
            {

                dd='0'+dd;

            } 
            if(mm<10) 
            {

                mm='0'+mm;

            } 

            var new_today = yyyy+'-'+mm+'-'+dd;

            if(new_today <= class_dates )
            {

                if(meeting_data_join_link == '')
                {                      

                    $("#eventLink").attr('href', event.event._def.extendedProps.url);

                    $("#eventContent").dialog({ modal: true, title: '<?php echo esc_html_e( "Book Class", 'gym_mgt' ); ?>',width:340, height:410 });

                    $( "#eventContent" ).removeClass( "display_none" );

                    if(booked_class_status == "yes")
                    {

                        $("#d_id").hide();

                    } 
                    else 
                    {

                        $("#d_id").show();

                    }

                    if( new_today == class_dates && current_time > end_time)
                    {
                        $("#d_id").hide();
                    }

                    $(".ui-dialog-titlebar-close").text('x');

                    $(".ui-dialog-titlebar-close").css('height',30);

                }
                else
                {
                    var gmgt_enable_virtual_classschedule = '<?php echo get_option('gmgt_enable_virtual_classschedule');?>';

                    if(booked_class_status == "yes")
                    {

                        if(gmgt_enable_virtual_classschedule== 'yes')
                        {

                            $("#d_id").hide();

                            $("#zoom_booked_popup").dialog({ modal: true, title: 'Virtual Class',width:340, height:410 });

                            $( "#zoom_booked_popup" ).removeClass( "display_none" );

                            $("#join_link_href").attr('href', meeting_data_join_link);

                            $(".ui-dialog-titlebar-close").text('x');

                            $(".ui-dialog-titlebar-close").css('height',30);

                        }

                    } 
                    else 
                    {

                        $("#d_id").show();

                        $("#eventLink").attr('href', event.event._def.extendedProps.url);

                        $("#eventContent").dialog({ modal: true, title: 'Class Book',width:340, height:410 });

                        $(".ui-dialog-titlebar-close").text('x');

                        $(".ui-dialog-titlebar-close").css('height',30);

                    }

                }

            }

        },

        //end  add class in pop up//

        //start add mouse over event only notice,birthday and reservation event//

        //eventMouseEnter

        eventMouseover: function (event, jsEvent, view) 
        {

            <?php $dformate=get_option('gmgt_datepicker_format'); ?>

            var dateformate_value='<?php echo $dformate;?>';	

            if(dateformate_value == 'yy-mm-dd')
            {	

                var dateformate='YYYY-MM-DD';

            }
            if(dateformate_value == 'yy/mm/dd')
            {	

                var dateformate='YYYY/MM/DD';	

            }
            if(dateformate_value == 'dd-mm-yy')
            {	

                var dateformate='DD-MM-YYYY';

            }

            if(dateformate_value == 'mm-dd-yy')
            {	

                var dateformate='MM-DD-YYYY';

            }
            if(dateformate_value == 'mm/dd/yy')
            {	

                var dateformate='MM/DD/YYYY';	

            }
            var newstartdate = event.start;

            var date = new Date(newstartdate);

            var startdate = new Date(date);

            var dateObjstart = new Date(startdate);

            var momentObjstart = moment(dateObjstart);

            var momentStringstart = momentObjstart.format(dateformate);

            var newdate = event.end;

            var type = event.type;

            var date = new Date(newdate);

            var newdate1 = new Date(date);

            if(type == 'reservationdata')
            {

                newdate1.setDate(newdate1.getDate());

            }
            else
            {

                newdate1.setDate(newdate1.getDate() - 1);

            }

            var dateObj = new Date(newdate1);

            var momentObj = moment(dateObj);

            var momentString = momentObj.format(dateformate);

            var data_type=event.type;

            if(data_type == 'Birthday' || data_type == 'reservationdata' || data_type == 'notice' )
            {

                tooltip = '<div class="tooltiptopicevent dasboard_Birthday">' + '<?php esc_html_e('Title Name','gym_mgt'); ?>' + ': ' + event.title + '</br>' + ' <?php esc_html_e('Start Date','gym_mgt'); ?>' + ': ' + momentStringstart + '</br>' + '<?php esc_html_e('End Date','gym_mgt'); ?>' + ': ' + momentString + '</br>' +  ' </div>';

                $("body").append(tooltip);

                $(this).mouseover(function (e) 
                {

                    "use strict";

                    $(this).css('z-index', 10000);

                    $('.tooltiptopicevent').fadeIn('500');

                    $('.tooltiptopicevent').fadeTo('10', 1.9);

                }).mousemove(function (e) 
                {

                    "use strict";

                    $('.tooltiptopicevent').css('top', e.pageY + 10);

                    $('.tooltiptopicevent').css('left', e.pageX + 20);

                });

            }

        },
        eventMouseLeave: function (data, event, view)
        {

            "use strict";

            $(this).css('z-index', 8);

            $('.tooltiptopicevent').remove();

        },

    //end add mouse over event only notice,birthday and reservation event//
})

calendar.render();	

});
</script>
<div class="panel-white padding_0 gms_main_list responsive_margin_57px"><!--PANEL WHITE DIV START-->	

    <div class="panel-body padding_0"><!--PANEL BODY DIV START-->	

        <div class="gmgt-calendar panel">

            <div class="row panel-heading activities height_80px_res responsive_calendar_header">

                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">

                    <h3 class="panel-title calander_heading_title_width"><?php esc_html_e('Calendar','gym_mgt');?></h3>

                </div>

                <div class="gmgt-cal-py col-sm-12 col-md-9 col-lg-9 col-xl-9 Calender_responsive_margin celender_dot_div">

                    <div class="gmgt-card-head">

                        <ul class="gmgt-cards-indicators gmgt-right">

                            <?php

                            if($user_role == 'staff_member')

                            {

                                ?>

                                <li><span class="gmgt-indic gmgt-orang-indic"></span> <?php esc_html_e( 'Reservation', 'gym_mgt' ); ?></li>

                                <li><span class="gmgt-indic gmgt-light-green-indic"></span> <?php esc_html_e( 'Class Schedule', 'gym_mgt' );?></li>

                                <?php

                            }

                            elseif($user_role == 'member')

                            {

                                ?>

                                <li><span class="gmgt-indic gmgt-light-green-indic"></span> <?php esc_html_e( 'Class Schedule', 'gym_mgt' );?></li>
                               
                                <li><span class="gmgt-indic gmgt-dark-green-indic"></span> <?php esc_html_e( 'Class Booked', 'gym_mgt' );?></li>

                                <li><span class="gmgt-indic gmgt-vibrant-orange-indic"></span> <?php esc_html_e( 'My Workout', 'gym_mgt' );?></li>

                                <li><span class="gmgt-indic gmgt-light-brown-indic"></span> <?php esc_html_e( 'My Nutrition', 'gym_mgt' ); ?></li>

                                <?php

                            }

                            ?>

                            <li><span class="gmgt-indic gmgt-light-perple-indic"></span> <?php esc_html_e( 'Notice', 'gym_mgt' ); ?></li>

                        </ul>

                    </div>   

                </div>

            </div>

            <div class="gmgt-cal-py gmgt-calender-margin-top">

                <div id="calendar"></div>

            </div>

        </div>

    </div>

</div>
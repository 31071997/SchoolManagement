<?php
class Attendence_Manage
{
	
	public $class_id;
	public $status;
	public $attendance;
	public $student_id;
	public $attend_by;
	public $attendence_date;
	public $curr_date;
	public $table_name;
	public $result;
	public $role;
	public $savedata=0;
	
	
	public function __construct( $marks = null ) 
	{
		
			global $wpdb;
			$table_name = $wpdb->prefix . "attendence";
			
		
	}
	// INSERT STUDENT ATTENANCE
	public function mj_smgt_insert_student_attendance($curr_date,$class_id,$user_id,$attend_by,$status,$comment,$attendence_type)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		$curr_date=date('Y-m-d',strtotime($curr_date));
		$check_insrt_or_update =$this->mj_smgt_check_has_attendace($user_id,$class_id,$curr_date);
		
		if(empty($check_insrt_or_update))
		{
		$savedata =$wpdb->insert($table_name,array('attendence_date' =>$curr_date,
				'attend_by' =>$attend_by,
				'class_id' =>$class_id, 'user_id' =>$user_id,'status' =>$status,'role_name'=>'student','comment'=>$comment,'attendence_type'=>$attendence_type));
		}
		else 
		{
			$savedata =$wpdb->update($table_name,
					array('attend_by' =>$attend_by,'status' =>$status,'comment'=>$comment,'attendence_type'=>$attendence_type),
					array('attendence_date' =>$curr_date,'class_id' =>$class_id,'user_id' =>$user_id));
		}
		return $savedata;
	}
	// ADD SUBJECT WISE ATTENDANCE 
	public function mj_smgt_insert_subject_wise_attendance($curr_date,$class_id,$user_id,$attend_by,$status,$sub_id,$comment,$attendence_type,$section_id)
	{
		
		if(empty($sub_id))
		{
			$categories = 'class';
		}
		else{
			$categories = 'subject';
		}
		if(empty($section_id))
		{
			$section_id = NULL;
		}
		if(empty($sub_id))
		{
			$sub_id = NULL;
		}
		global $wpdb;
		$table_name = $wpdb->prefix . "smgt_sub_attendance";
	
		$curr_date=date('Y-m-d',strtotime($curr_date));
		$check_insrt_or_update = $this->mj_smgt_check_has_subject_attendace($user_id,$class_id,$curr_date,$sub_id,$section_id);
		if(empty($check_insrt_or_update))
		{
		$savedata =$wpdb->insert($table_name,array('attendance_date' =>$curr_date,
				'attend_by' =>$attend_by,
				'class_id' =>$class_id,'sub_id'=>$sub_id, 'user_id' =>$user_id,'status' =>$status,'role_name'=>'student','comment'=>$comment,'attendence_type'=>$attendence_type,'categories'=>$categories,'section_id'=>$section_id));
				
			}
		else 
		{
			
			$attendace_data['attend_by'] = $attend_by;
			$attendace_data['status'] = $status;
			$attendace_data['comment'] = $comment;
			$attendace_data['role_name'] = 'student';
			$attendace_data['attendence_type'] = $attendence_type;
			$attendace_data['categories'] = $categories;
			$attendace_data['section_id'] = $section_id;
			$attendace_data['sub_id'] = $sub_id;
			$attendace_data['attendance_date'] = $curr_date;
			$attendace_data['class_id'] = $class_id;
			$attendace_data['user_id'] = $user_id;
			$where_id['attendance_id'] = $check_insrt_or_update->attendance_id;
			$savedata =$wpdb->update($table_name,$attendace_data,$where_id);
		}
		
		return $savedata;
	}
	public function mj_smgt_check_has_attendace($user_id,$class_id,$attendace_date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		return $results=$wpdb->get_row("SELECT * FROM $table_name WHERE attendence_date='$attendace_date' and class_id=$class_id and user_id =".$user_id);
	}
	public function mj_smgt_check_has_subject_attendace($user_id,$class_id,$attendace_date,$sub_id,$section_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "smgt_sub_attendance";
		if(!empty($class_id) && !empty($sub_id) && !empty($section_id))
		{
			$results=$wpdb->get_row("SELECT * FROM $table_name WHERE attendance_date='$attendace_date' and class_id=$class_id and sub_id=$sub_id and section_id=$section_id and user_id =".$user_id);
		}
		elseif(!empty($class_id) && empty($sub_id) && !empty($section_id))
		{
			$results=$wpdb->get_row("SELECT * FROM $table_name WHERE attendance_date='$attendace_date' and class_id=$class_id and section_id=$section_id and user_id =".$user_id);
		}
		elseif(!empty($class_id) && !empty($sub_id) && empty($section_id))
		{
			$results=$wpdb->get_row("SELECT * FROM $table_name WHERE attendance_date='$attendace_date' and class_id=$class_id and sub_id=$sub_id and user_id =".$user_id);
		}
		else{
			$results=$wpdb->get_row("SELECT * FROM $table_name WHERE attendance_date='$attendace_date' and class_id=$class_id and user_id =".$user_id);
		
		}
		
		return $results;
	}

	// ADD TEACHER ATTENDANCE DATA
	public function mj_smgt_insert_teacher_attendance($curr_date,$user_id,$attend_by,$status,$comment)
	{
		$class_id=0;
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		$check_insrt_or_update =$this->mj_smgt_check_has_attendace($user_id,$class_id,$curr_date);
		$curr_date=date('Y-m-d',strtotime($curr_date));
		if(empty($check_insrt_or_update))
		{
			$savedata =$wpdb->insert($table_name,array('attendence_date' =>$curr_date,
					'attend_by' =>$attend_by,
					'class_id' =>$class_id, 'user_id' =>$user_id,'status' =>$status,'role_name'=>'teacher','comment'=>$comment));		
		}
		else
		{
			$savedata =$wpdb->update($table_name,
					array('attend_by' =>$attend_by,'status' =>$status,'comment'=>$comment),
					array('attendence_date' =>$curr_date,'class_id' =>$class_id,'user_id' =>$user_id));
		}
	}
	public function mj_smgt_save_attendence($curr_date,$class_id,$attendence,$attend_by,$status)
	{
		
		global $wpdb;
		$role='student';
		$table_name = $wpdb->prefix . "attendence";
		
		$exlude_id = mj_smgt_approve_student_list();
		$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
		if($status=='Present')
			$new_status='Absent';
		else
			$new_status='Present';
		$record_status="";
		$check_today_attendence=$this->mj_smgt_show_today_attendence($class_id,$role);
		$record_status="";
		 $curr_date=date("Y-m-d");;
		foreach($check_today_attendence as $today_data)
		{
			if($today_data['class_id']==$class_id && $today_data['attendence_date']==$curr_date)
				$record_status="update";
			
				
				
		}
		if($record_status=="update")
		{
			
			return $savedata=$this->mj_smgt_update_attendence($students,$curr_date,$class_id,$attendence,$attend_by,$status,$table_name);
		}
		else
		{
			
			foreach($students as $stud)
			{
				if(in_array($stud->ID ,$attendence))
				{
					
					 $savedata=$wpdb->insert($table_name,array('attendence_date' =>$curr_date,'attend_by' =>$attend_by,'class_id' =>$class_id, 'user_id' =>$stud->ID,'status' =>$status,'role_name'=>$role));
				}
				else
				{
					 $savedata=$wpdb->insert($table_name,array('attendence_date' =>$curr_date,'attend_by' =>$attend_by,'class_id' =>$class_id, 'user_id' =>$stud->ID,'status' =>$new_status,'role_name'=>$role));
				}
			}
			if($savedata)
				return $savedata;
		}
			
	}
	public function mj_smgt_update_attendence($students,$curr_date,$class_id,$attendence,$attend_by,$status,$table_name)
	{
		 global $wpdb;
		
		 if($status=='Present')
			$new_status='Absent';
		else
			$new_status='Present';
		 	foreach($students as $stud)
			{
				if(in_array($stud->ID ,$attendence))
				{
					
					
					 $result=$wpdb->update($table_name,array('attend_by' =>$attend_by,'status' =>$status),array('attendence_date' =>$curr_date,'class_id' =>$class_id,'user_id' =>$stud->ID));
				}
				else
				{
					  $result=$wpdb->update($table_name,array('attend_by' =>$attend_by,'status' =>$new_status),array('attendence_date' =>$curr_date,'class_id' =>$class_id,'user_id' =>$stud->ID));
				}
			}
	
		
			return $result;
	}
	
	
	public function mj_smgt_save_teacher_attendence($curr_date,$attendence,$attend_by,$status)
	{
		
		
		$role='teacher';
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		if($status=='Present')
			$new_status='Absent';
		else
			$new_status='Present';	
		$record_status="";
		$check_today_attendence=$this->mj_smgt_show_today_teacher_attendence($role);
		$record_status="";
		
		 $curr_date=$curr_date;
		foreach($check_today_attendence as $today_data)
		{
			
			if($today_data['attendence_date']==$curr_date)
			{
					
				$record_status="update";
				
			}
				
				
		}
		if($record_status=="update")
		{
			
			return $savedata=$this->mj_smgt_update_teacher_attendence($curr_date,$attendence,$attend_by,$status,$table_name);
			
		}
		else
		{
			
			foreach(mj_smgt_get_usersdata('teacher') as $stud)
			{
				
				if(in_array($stud->ID ,$attendence))
				{
					$class_id=get_user_meta($stud->ID, 'class_name', true);
					$result=$wpdb->insert($table_name,array('attendence_date' =>$curr_date,'attend_by' =>$attend_by, 'user_id' =>$stud->ID,'status' =>$status,'role_name'=>$role,'class_id'=>$class_id));
				}
				else
				{
					$result=$wpdb->insert($table_name,array('attendence_date' =>$curr_date,'attend_by' =>$attend_by, 'user_id' =>$stud->ID,'status' =>$new_status,'role_name'=>$role,'class_id'=>$class_id));
				}
			}
			return $result;
		}
		
	}

	public function mj_smgt_update_teacher_attendence($curr_date,$attendence,$attend_by,$status,$table_name)
	{
		 global $wpdb;
		
		 if($status=='Present')
			$new_status='Absent';
		else
			$new_status='Present';
		 	foreach(mj_smgt_get_usersdata('teacher') as $stud)
			{
				
				if(in_array($stud->ID ,$attendence))
				{
					
					$result=$wpdb->update($table_name,array('attend_by' =>$attend_by,'status' =>$status),array('attendence_date' =>$curr_date,'user_id' =>$stud->ID));
				}
				else
				{
					$result=$wpdb->update($table_name,array('attend_by' =>$attend_by,'status' =>$new_status),array('attendence_date' =>$curr_date,'user_id' =>$stud->ID));
				}
			}
			return $result;
		
	}
	public function mj_smgt_show_today_attendence($class_id,$role)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		$curr_date=date("Y-m-d");
		return $results=$wpdb->get_results("SELECT * FROM $table_name WHERE attendence_date='$curr_date' and class_id=$class_id and role_name='$role'",ARRAY_A);
	}
	public function mj_smgt_show_today_teacher_attendence($role)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		$curr_date=date("Y-m-d");
		return $results=$wpdb->get_results("SELECT * FROM $table_name WHERE attendence_date='$curr_date' and role_name='$role'",ARRAY_A);
		
		
	}
	public function mj_smgt_get_attendence($userid,$class_id,$date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		$curr_date=$date;
		$result=$wpdb->get_var("SELECT * FROM $table_name WHERE attendence_date='$curr_date' and class_id='$class_id' and user_id='$userid' and status='Present'");
		if($result)
		{	
			return $value='true';
		}
		else
		{ 
			return $value='false';
		}
		
	}
	public function mj_smgt_get_all_attendence()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		
		$result=$wpdb->get_results("SELECT * FROM $table_name ");
		
		return $result;		
	}
	public function mj_smgt_get_all_attendence_with_subject()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "smgt_sub_attendance";
		
		$result=$wpdb->get_results("SELECT * FROM $table_name");
		
		return $result;		
	}
	public function mj_smgt_check_attendence($userid,$class_id,$date)
	{
		global $wpdb;
		
		$table_name = $wpdb->prefix . "attendence";
		$curr_date=date('Y-m-d',strtotime($date));
		$result=$wpdb->get_row("SELECT * FROM $table_name WHERE attendence_date='$curr_date' and class_id='$class_id' and user_id=".$userid);
		return $result;
	
	}
	public function mj_smgt_check_sub_attendence($userid,$class_id,$date,$sub_id)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix . "smgt_sub_attendance";
		
		$curr_date=date('Y-m-d',strtotime($date));
		$result=$wpdb->get_row("SELECT * FROM $table_name WHERE attendance_date='$curr_date' and class_id='$class_id' and sub_id='$sub_id' and user_id=".$userid);
		
		return $result;
	
	}
	public function mj_smgt_get_teacher_attendence($userid,$date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		$curr_date=$date;
		$result=$wpdb->get_var("SELECT * FROM $table_name WHERE attendence_date='$curr_date' and  user_id='$userid' and status='Present'");
		if($result)
		{	
			return $value='true';
		}
		else
		{
			return $value='false';
		}
		
	}
	public function mj_smgt_today_presents()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		$curr_date=date("Y-m-d");
		return $result=$wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE attendence_date='$curr_date' and status='Present'");
		
	}
	public function mj_smgt_get_all_user_teacher_attendence($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "attendence";
		$result=$wpdb->get_results("SELECT * FROM $table_name where user_id=$user_id");
		return $result;		
	}
}
?>
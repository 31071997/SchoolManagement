<?php
class smgt_exam
{
	public function mj_smgt_get_subject_by_section_id($class_id,$section_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "subject";
		return $results=$wpdb->get_results("SELECT * FROM $table_name WHERE  class_id='$class_id' and section_id='$section_id' ");
	}
	public function mj_smgt_get_subject_by_class_id($class_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "subject";
		return $results=$wpdb->get_results("SELECT * FROM $table_name WHERE  class_id='$class_id'");
	}
	public function mj_smgt_insert_sub_wise_time_table($class_id,$exam_id,$subject_id,$exam_date,$start_time,$end_time)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix . "smgt_exam_time_table";
		$curr_date=date('Y-m-d');
		$curren_user= get_current_user_id();
		$exam_date_new=date('Y-m-d',strtotime($exam_date));

		$start_time_24hrs_formate=MJ_start_time_convert($start_time);
		$end_time_24hrs_formate=MJ_end_time_convert($end_time);
	
		$start_time_new=$start_time_24hrs_formate;
		$end_time_new=$end_time_24hrs_formate;
		$check_insrt_or_update =$this->mj_smgt_check_subject_data($exam_id,$subject_id);

		if(empty($check_insrt_or_update))
		{
			school_append_audit_log(''.esc_html__('Exam Time Table Added','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
			$save_data =$wpdb->insert($table_name,array('class_id' =>$class_id,
				'exam_id' =>$exam_id,
				'subject_id' =>$subject_id, 'exam_date' =>$exam_date_new,'start_time' =>$start_time_new,'end_time'=>$end_time_new,'created_date'=>$curr_date,'created_by'=>$curren_user));
		}
		else 
		{
			school_append_audit_log(''.esc_html__('Exam Time Table Updated','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);
			$save_data =$wpdb->update($table_name,
					array('exam_date' =>$exam_date_new,'start_time' =>$start_time_new,'end_time'=>$end_time_new,'created_date'=>$curr_date,'created_by'=>$curren_user),
					array('class_id' =>$class_id,'exam_id' =>$exam_id,'subject_id' =>$subject_id));
		}  
	
		return $save_data;
		
	}
	public function mj_smgt_check_subject_data($exam_id,$subject_id)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix . "smgt_exam_time_table";
		$results=$wpdb->get_results("SELECT * FROM $table_name WHERE exam_id=$exam_id and  subject_id=$subject_id");
		
		return $results;
	}
	public function mj_smgt_check_exam_time_table($class_id,$exam_id,$sub_id)
	{
		global $wpdb;
		
		$table_name = $wpdb->prefix . "smgt_exam_time_table";
		$result=$wpdb->get_row("SELECT * FROM $table_name WHERE class_id=$class_id and exam_id=$exam_id and subject_id=$sub_id");
		return $result;
	}
	public function mj_smgt_get_exam_time_table_by_exam($exam_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "smgt_exam_time_table";
		$results=$wpdb->get_results("SELECT * FROM $table_name WHERE exam_id=$exam_id");
		return $results;
	}
	public function mj_smgt_get_all_exam_by_class_id_created_by($class_id,$user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "exam";
		$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE class_id IN (".implode(',', $class_id).") OR exam_creater_id=".$user_id);
		return $results;
	}
	public function mj_smgt_get_all_exam_created_by($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "exam";
		$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE exam_creater_id=".$user_id);
		return $results;
	}
	function mj_smgt_get_all_exam_by_class_id_dashboard($class_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "exam";
		return $retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE class_id =$class_id and section_id='0' ORDER BY exam_id DESC limit 3");
	
	}
	function mj_smgt_get_all_exam_by_class_id_and_section_id_array_dashboard($class_id,$section_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "exam";
		return $retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE class_id=".$class_id." and section_id=$section_id ORDER BY exam_id DESC limit 3");
		
	}
	public function mj_smgt_get_all_exam_by_class_id_created_by_dashboard($class_id,$user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "exam";
		$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE class_id IN (".implode(',', $class_id).") OR exam_creater_id=$user_id ORDER BY exam_id DESC limit 3");
		return $results;
	}
	function mj_smgt_get_all_exam_by_class_id_array_dashboard($class_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "exam";
		return $retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE class_id IN (".implode(',',$class_id).") and section_id='0' ORDER BY exam_id DESC limit 3");
		
	}
	public function mj_smgt_get_all_exam_created_by_dashboard($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "exam";
		$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE exam_creater_id=$user_id ORDER BY exam_id DESC limit 3");
		return $results;
	}
	public function mj_smgt_exam_list_for_dashboard()
	{
		global $wpdb;
		$smgt_exam = $wpdb->prefix . 'exam';
		$result = $wpdb->get_results("SELECT * FROM $smgt_exam ORDER BY exam_id DESC limit 5");
		return $result;
	}
}
?>
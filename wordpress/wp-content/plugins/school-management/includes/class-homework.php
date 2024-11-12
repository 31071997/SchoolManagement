<?php 



class Smgt_Homework



{



	public function mj_smgt_check_valid_extension($filename)



	{



		$flag = 2;



		if($filename != '')



		{



			$flag = 0;



			$ext = pathinfo($filename, PATHINFO_EXTENSION);



			$valid_extension = array('gif','png','jpg','jpeg');



			if(in_array($ext,$valid_extension) )



			{



				$flag = 1;



			}



		}



		return $flag;



	}



	function mj_smgt_get_delete_records($tablenm,$record_id)



	{



		global $wpdb;



		$table_name = $wpdb->prefix . $tablenm;



		return $result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE homework_id= %d",$record_id));



	}



	public function mj_smgt_check_uploaded($assign_id)

	{

		global $wpdb;

		$table = $wpdb->prefix."mj_smgt_student_homework";

		$result = $wpdb->get_row("SELECT file FROM {$table} WHERE stu_homework_id = {$assign_id}",ARRAY_A);

		if($result['file'] != "")

		{

			return $result['file'];
		}

		else

		{ 

			return false;

		}

	}

	public function MJ_smgt_get_student_submitted_homework($stu_homework_id)
	{
		global $wpdb;

		$table = $wpdb->prefix."mj_smgt_student_homework";

		$result = $wpdb->get_row("SELECT * FROM $table WHERE stu_homework_id = $stu_homework_id");

		return $result;
	}

	

	function mj_smgt_get_class_homework()

	{

		global $wpdb;

		$table_name = $wpdb->prefix . 'mj_smgt_homework';

		return $result = $wpdb->get_results("SELECT * FROM $table_name");

	}

	function mj_smgt_view_submission($data){

		global $wpdb;

		$table_name = $wpdb->prefix . 'mj_smgt_homework';

		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';

		return $result = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id` where a.`homework_id`= $data ");

	}

	function mj_smgt_parent_view_detail(){

		global $wpdb;

		$table_name = $wpdb->prefix . 'mj_smgt_homework';

		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
		global $user_ID;

		$child=mj_smgt_get_parents_child_id($user_ID);		
		foreach($child as $student_id)
		{
			$class_id = get_user_meta($student_id,'class_name',true);
			$result[] = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM $table_name as a 
					LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id`
					WHERE b.student_id = %d AND a.class_name = %s",
					$student_id,
					$class_id
				)
			);
		}
		if (!empty($result)) {
			$mergedArray = array_merge(...$result);
			$homework_data = array_unique($mergedArray, SORT_REGULAR);
		} else {
			$homework_data = array();
		}
		
		return $homework_data;
	}

	function mj_smgt_parent_view_detail_for_dashboard($child_ids){

		global $wpdb;

		$table_name = $wpdb->prefix . 'mj_smgt_homework';

		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
		global $user_ID;

		$child=mj_smgt_get_parents_child_id($user_ID);		
		foreach($child as $student_id)
		{
			$class_id = get_user_meta($student_id,'class_name',true);
			$result[] = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id`WHERE b.student_id = $student_id && a.class_name = $class_id ORDER BY a.`homework_id` DESC limit 5");
		}
		if (!empty($result)) {
			$mergedArray = array_merge(...$result);
			$homework_data = array_unique($mergedArray, SORT_REGULAR);
		} else {
			$homework_data = array();
		}
		
		return $homework_data;

	}

	function mj_smgt_student_view_detail(){

		global $wpdb;

		global $user_ID;
		$class_id = get_user_meta($user_ID,'class_name',true);

		$table_name = $wpdb->prefix . 'mj_smgt_homework';

		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';

		return $result = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id`WHERE b.student_id = $user_ID && a.class_name = $class_id");


	}

	function mj_smgt_student_view_detail_for_dashboard(){

		global $wpdb;

		global $user_ID;
		$class_id = get_user_meta($user_ID,'class_name',true);
		$table_name = $wpdb->prefix . 'mj_smgt_homework';

		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';

		return $result = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id`WHERE b.student_id = $user_ID && a.class_name = $class_id ORDER BY a.`homework_id` DESC limit 5");

	}



	function mj_smgt_parent_update_detail($data,$student_id){



		global $wpdb;



		global $user_ID;



		$table_name = $wpdb->prefix . 'mj_smgt_homework';



		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';



		return $result = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id` WHERE a.`homework_id`=$data AND b.student_id = $student_id");



	}



	



	function mj_smgt_parent_update_detail_api($data,$student_id)



	{



		global $wpdb;



		global $user_ID;



		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';



		$result = $wpdb->get_row("SELECT * FROM $table_name2 where student_id=$student_id and homework_id=$data");



		return $result;



	}



	function mj_smgt_add_homework($data,$document_data)
	{
		
		global $current_user;

		global $wpdb;

		$user=$current_user->user_login;

		$table_name=$wpdb->prefix ."mj_smgt_homework";

		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';

		$homeworkdata['title']=mj_smgt_address_description_validation(stripslashes($data['title']));

		$homeworkdata['class_name']=$data['class_name'];

		$homeworkdata['section_id']=$data['class_section'];

		$homeworkdata['subject']=$data['subject_id'];

		$homeworkdata['content']=stripslashes($data['content']);

		$homeworkdata['created_date']=date('Y-m-d H:i:s');

		$homeworkdata['submition_date']= date('Y-m-d',strtotime($data['sdate']));

		$homeworkdata['createdby']=get_current_user_id();

		$subject_name=mj_smgt_get_single_subject_name($data['subject_id']);

		if(!empty($_REQUEST['homework_id']))
		{

			$homework_id['homework_id']=$_REQUEST['homework_id'];

			$homeworkdata['homework_document']=json_encode($document_data);

			$result = $wpdb->update($table_name,$homeworkdata,$homework_id);

			$last_homework_id=$wpdb->insert_id;

			$homework = $homeworkdata['title'];

			school_append_audit_log(''.esc_html__('Homework Updated','hospital_mgt').'('.$homework.')'.'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);

			if($result)
			{

				if(!empty($data['class_section']))
				{
					$class_id =$data['class_name'];

					$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));
				}
				else
				{
					$studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$data['class_section'],

					'meta_query'=> array(array('key' => 'class_name','value' => $data['class_name'],'compare' => '=')),'role'=>'student'));
				}

				$homeworstud['homework_id']=$last_homework_id;

				foreach($studentdata as $student)
				{

					$homeworstud['student_id']=$student->ID;

					$result = $wpdb->insert($table_name2,$homeworstud);

				}

			}

			$device_token[] = get_user_meta($user_id, 'token_id' , true);

			return $result;

		}
		else
		{

			$homeworkdata['homework_document']=json_encode($document_data);

			$result=$wpdb->insert($table_name,$homeworkdata);

			$last_homework_id=$wpdb->insert_id;

			$homework = $homeworkdata['title'];

			school_append_audit_log(''.esc_html__('Homework Added','hospital_mgt').'('.$homework.')'.'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);

			if($result)
			{

				if(empty($data['class_section']))
				{

					$class_id =$data['class_name'];

					$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));

				}
				else
				{

					$studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$data['class_section'],

					'meta_query'=> array(array('key' => 'class_name','value' => $data['class_name'],'compare' => '=')),'role'=>'student'));

				}

				if(!empty($studentdata))
				{

					$homeworstud['homework_id']=$last_homework_id;

					$homeworstud['status']='0';

					$homeworstud['created_by']=get_current_user_id();

					$homeworstud['created_date']=date('Y-m-d H:i:s');

					$device_token = array();

					foreach($studentdata as $student)
					{
						$homeworstud['student_id']=$student->ID;

						$insert = $wpdb->insert($table_name2,$homeworstud);

						$device_token[] = get_user_meta($student->ID, 'token_id' , true);
					}


					/* Start Send Push Notification */

					$title = esc_attr__('New Notification For Homework','school-mgt');

					$text = esc_attr__('New homework has been assign to you','school-mgt');

					$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));

					$json = json_encode($notification_data);

					$message =	MJ_smgt_send_push_notification($json);

					/* End Send Push Notification */

					if($insert)
					{
						
						if(isset($data['smgt_enable_homework_mail']) == '1' || isset($data['smgt_enable_homework_sms_student']) == '1' || isset($data['smgt_enable_homework_sms_parent']) == '1')
						{
							foreach($studentdata as $userdata)
							{
								$student_id = $userdata->ID;

								$student_name = $userdata->display_name;

								$student_email = $userdata->user_email;

								$parent 		= 	get_user_meta($student_id, 'parent_id', true);

								// SEND MAIL NOTIFICATION FOR PARENT //
								if(isset($data['smgt_enable_homework_mail']) == '1')
								{
									if(!empty($parent))
									{
										foreach($parent as $p)
										{
											$user_info	 	=    get_userdata($p);

											$email_to 	=	 $user_info->user_email;

											$searchArr = array();

											$parent_homework_mail_content = get_option('parent_homework_mail_content');

											$parent_homework_mail_subject = get_option('parent_homework_mail_subject');

											$parerntdata = get_user_by('email',$email_to);							

											$searchArr['{{parent_name}}']	=	$parerntdata->display_name;

											$searchArr['{{student_name}}']	=	$student_name;

											$searchArr['{{title}}']   =  mj_smgt_address_description_validation($data['title']);

											$searchArr['{{submition_date}}']   =  mj_smgt_getdate_in_input_box($data['sdate']);
									
											$searchArr['{{homework_date}}']   = mj_smgt_getdate_in_input_box(date('Y-m-d H:i:s'));

											$searchArr['{{subject}}']   = $subject_name;

											$searchArr['{{school_name}}']	=	get_option('smgt_school_name');

											$message = mj_smgt_string_replacement($searchArr,$parent_homework_mail_content);

											if(!empty($document_data[0]))
											{
												$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$document_data[0]['value'];
											}
											else
											{
												$attechment = '';
											}
										
											mj_smgt_send_mail_for_homework($email_to,$parent_homework_mail_subject,$message,$attechment); 
										}
									}

									// SEND MAIL NOTIFICATION FOR STUDENT //

									$string = array();

									$string['{{student_name}}']   = $student_name;

									$string['{{title}}']   =  mj_smgt_address_description_validation($data['title']);

									$string['{{submition_date}}']   =  mj_smgt_getdate_in_input_box($data['sdate']);

									$string['{{homework_date}}']   = mj_smgt_getdate_in_input_box(date('Y-m-d H:i:s'));

									$string['{{subject}}']   = $subject_name;

									$string['{{school_name}}'] =  get_option('smgt_school_name');

									$msgcontent                =  get_option('homework_mailcontent');		

									$msgsubject				   =  get_option('homework_title');

									$message = mj_smgt_string_replacement($string,$msgcontent);

									if(!empty($document_data[0]))
									{
										$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$document_data[0]['value'];
									}
									else
									{
										$attechment = '';
									}
										
									$mail =	mj_smgt_send_mail_for_homework($student_email,$msgsubject,$message,$attechment);
								}
								
								// SEND SMS NOTIFICATION FOR STUDENT //
								if(isset($data['smgt_enable_homework_sms_student']) == '1')
								{
									$message_content = "Your new homework is posted. Please check and submit it by the submission date.";

									$type = "Homework";

									// MJ_smgt_send_sms_notification($userdata->ID,$type,$message_content);
								}
								
								// SEND SMS NOTIFICATION FOR PARENT //
								if(isset($data['smgt_enable_homework_sms_parent']) == '1')
								{
									if(!empty($parent))
									{
										foreach($parent as $p)
										{
											$message_content = "Your child has a new homework assignment. Please review it with them and provide any necessary support.";

											$type = "Homework";

											// MJ_smgt_send_sms_notification($p->ID,$type,$message_content);
										}
									}

								}

								
								
							}

						}

					}

				}

			}

			return $result;

		}

    }	

	function mj_smgt_get_all_homeworklist()

	{

		global $wpdb;

		$table_name = $wpdb->prefix . "mj_smgt_homework";

		return $rows = $wpdb->get_results("SELECT * from $table_name ORDER BY submition_date DESC");

	}

	function mj_smgt_get_all_own_homeworklist()

	{

		global $wpdb;

		$get_current_user_id=get_current_user_id();

		$table_name = $wpdb->prefix . "mj_smgt_homework";

		return $rows = $wpdb->get_results("SELECT * from $table_name where createdby =$get_current_user_id ORDER BY submition_date DESC");

	}

	function mj_smgt_get_teacher_homeworklist()

	{

		global $wpdb;

		$class_name = array();

		$table_name = $wpdb->prefix . "mj_smgt_homework";

		$class_name=get_user_meta(get_current_user_id(),'class_name',true);

		return $rows = $wpdb->get_results("SELECT * from $table_name where class_name IN(".implode(',',$class_name).")" );

	}	

	function mj_smgt_get_edit_record($homework_id)

	{

		global $wpdb;

		$table_name = $wpdb->prefix . "mj_smgt_homework";

		return $rows = $wpdb->get_row("SELECT * from $table_name where homework_id=".$homework_id);

	}

	function mj_smgt_get_delete_record($homework_id)

	{

		global $wpdb;

		$table_name = $wpdb->prefix . "mj_smgt_homework";

		$home = $wpdb->get_row("SELECT * FROM $table_name where homework_id=$homework_id");

		$homework = $home->title;

		school_append_audit_log(''.esc_html__('Homework Deleted','hospital_mgt').'('.$homework.')'.'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);

		return $rows = $wpdb->query("Delete from $table_name where homework_id=".$homework_id);

	}
	// OWN HOMEWORK LIST FOR TEACHER
	function mj_smgt_get_all_own_homeworklist_for_teacher()
	{

		global $wpdb;

		$get_current_user_id=get_current_user_id();

		$table_name = $wpdb->prefix . "mj_smgt_homework";
		$class = get_user_meta($get_current_user_id,'class_name',true);
		
		foreach ($class as $class_id) 
		{
			
			$rows[] = $wpdb->get_results("SELECT * from $table_name where class_name = $class_id ORDER BY submition_date DESC");
		}
		$mergedArray = array_merge(...$rows);

		$retrieve_class = array_unique($mergedArray, SORT_REGULAR);
		
		return $retrieve_class;
	}
}
?>
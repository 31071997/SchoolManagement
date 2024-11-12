<?php
class smgt_hostel
{
	public function mj_smgt_insert_hostel($data)
	{
		global $wpdb;
		$table_smgt_hostel=$wpdb->prefix.'smgt_hostel';
		$hostel_data['hostel_name']=stripslashes(mj_smgt_onlyLetter_specialcharacter_validation($data['hostel_name']));
		$hostel_data['hostel_type']=stripslashes(mj_smgt_onlyLetter_specialcharacter_validation($data['hostel_type']));

		$hostel_data['hostel_address']=stripslashes($data['hostel_address']);
		$hostel_data['hostel_intake']=stripslashes($data['hostel_intake']);

		$hostel_data['Description']=stripslashes(mj_smgt_address_description_validation($data['Description']));
		
		if($data['action']=='edit')
		{
			$hostel_data['updated_by']=get_current_user_id();
			$hostel_data['updated_date']=date('Y-m-d');
			$hostel_id['id']=$data['hostel_id'];
			$result=$wpdb->update( $table_smgt_hostel, $hostel_data ,$hostel_id);
			
			$hostel = $hostel_data['hostel_name'];
			school_append_audit_log(''.esc_html__('Hostel Updated','hospital_mgt').'('.$hostel.')'.'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);
			return $result;
		}
		else
		{
			
			$hostel_data['created_by']=get_current_user_id();
			$hostel_data['created_date']=date('Y-m-d');
			$result=$wpdb->insert( $table_smgt_hostel,$hostel_data);
			$hostel = $hostel_data['hostel_name'];
			school_append_audit_log(''.esc_html__('Hostel Added','hospital_mgt').'('.$hostel.')'.'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
			return $result;
		}
	}
	public function mj_smgt_get_hostel_by_id($hostel_id)
	{
		global $wpdb;
		$table_smgt_hostel=$wpdb->prefix.'smgt_hostel';
		$result=$wpdb->get_row("SELECT * FROM $table_smgt_hostel where id=".$hostel_id);
		return $result;
	}
	public function mj_smgt_delete_hostel($hostel_id)
	{
		
		global $wpdb;
		$table_smgt_hostel=$wpdb->prefix.'smgt_hostel';
		$hostel = $wpdb->get_row("SELECT * FROM $table_smgt_hostel where id=$hostel_id");
	
		$hostel_name = $hostel->hostel_name;
		school_append_audit_log(''.esc_html__('Hostel Deleted','hospital_mgt').'('.$hostel_name.')'.'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);
		return $result=$wpdb->query($wpdb->prepare("DELETE FROM $table_smgt_hostel WHERE id= %d",$hostel_id));
	}
	public function mj_smgt_get_all_hostel()
	{
		global $wpdb;
		$table_smgt_hostel=$wpdb->prefix.'smgt_hostel';
		$result=$wpdb->get_results("SELECT * FROM $table_smgt_hostel");
		return $result;
	}
	public function mj_smgt_insert_room($data)
	{
		global $wpdb;
		$table_smgt_room=$wpdb->prefix.'smgt_room';
		$room_data['room_unique_id']=mj_smgt_onlyLetter_specialcharacter_validation($data['room_unique_id']);
		$room_data['hostel_id']=$data['hostel_id'];
		$room_data['room_status']='0';
		$room_data['room_category']=$data['room_category'];
		$room_data['beds_capacity']=$data['beds_capacity'];
		$room_data['room_description']=mj_smgt_address_description_validation(stripslashes($data['room_description']));
		
		if($data['action']=='edit_room')
		{
			
			$room_data['updated_by']=get_current_user_id();
			$room_data['updated_date']=date('Y-m-d');
			$room_id['id']=$data['room_id'];
			$result=$wpdb->update( $table_smgt_room, $room_data ,$room_id);
			
			$room = $room_data['room_unique_id'];
			school_append_audit_log(''.esc_html__('Room Updated','hospital_mgt').'('.$room.')'.'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);
			return $result;
		}
		else
		{
			
			$room_data['created_by']=get_current_user_id();
			$room_data['created_date']=date('Y-m-d');
			$result=$wpdb->insert( $table_smgt_room,$room_data);
			$room = $room_data['room_unique_id'];
			school_append_audit_log(''.esc_html__('Room Added','hospital_mgt').'('.$room.')'.'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
			return $result;
		}
	}
	public function mj_smgt_delete_room($room_id)
	{
		
		global $wpdb;
		$table_smgt_room=$wpdb->prefix.'smgt_room';
		$room_data = $wpdb->get_row("SELECT * FROM $table_smgt_room where id=$room_id");
		
		$room = $room_data->room_unique_id;
		school_append_audit_log(''.esc_html__('Room Deleted','hospital_mgt').'('.$room.')'.'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);
		return $result=$wpdb->query($wpdb->prepare("DELETE FROM $table_smgt_room WHERE id= %d",$room_id));
	}
	public function mj_smgt_get_room_by_id($room_id)
	{
		global $wpdb;
		$table_smgt_room=$wpdb->prefix.'smgt_room';
		$result=$wpdb->get_row("SELECT * FROM $table_smgt_room where id=".$room_id);
		return $result;
	}
	public function mj_smgt_get_all_room()
	{
		global $wpdb;
		$table_smgt_room=$wpdb->prefix.'smgt_room';
		$result=$wpdb->get_results("SELECT * FROM $table_smgt_room");
		return $result;
	}
	public function mj_smgt_insert_bed($data)
	{
		global $wpdb;
		$table_smgt_beds=$wpdb->prefix.'smgt_beds';
		$bed_data['bed_unique_id']=mj_smgt_onlyLetter_specialcharacter_validation($data['bed_unique_id']);
		$bed_data['room_id']=$data['room_id'];
		$bed_data['bed_status']='0';

		$bed_data['bed_charge']= $data['bed_charge'];

		$bed_data['bed_description']=mj_smgt_address_description_validation(stripslashes($data['bed_description']));
		
		if($data['action']=='edit_bed')
		{
			
			$bed_data['updated_by']=get_current_user_id();
			$bed_data['updated_date']=date('Y-m-d');
			$bed_id['id']=$data['bed_id'];
			$result=$wpdb->update( $table_smgt_beds, $bed_data ,$bed_id);
			
			$bed = $bed_data['bed_unique_id'];
			school_append_audit_log(''.esc_html__('Bed Updated','hospital_mgt').'('.$bed.')'.'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);
			return $result;
		}
		else
		{
			
			$bed_data['created_by']=get_current_user_id();
			$bed_data['created_date']=date('Y-m-d');
			$result=$wpdb->insert( $table_smgt_beds,$bed_data);
			$bed = $bed_data['bed_unique_id'];
			school_append_audit_log(''.esc_html__('Bed Added','hospital_mgt').'('.$bed.')'.'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
			return $result;
		}
	}
	public function mj_smgt_get_bed_by_id($bed_id)
	{
		global $wpdb;
		$table_smgt_beds=$wpdb->prefix.'smgt_beds';
		$result=$wpdb->get_row("SELECT * FROM $table_smgt_beds where id=".$bed_id);
		return $result;
	}
	public function mj_smgt_get_all_bed_by_room_id($room_id)
	{
		global $wpdb;
		$table_smgt_beds=$wpdb->prefix.'smgt_beds';
		 
		$result=$wpdb->get_results("SELECT * FROM $table_smgt_beds where room_id=".$room_id);
		return $result;
	}
	public function mj_smgt_delete_bed($bed_id)
	{
		
		global $wpdb;
		$table_smgt_beds=$wpdb->prefix.'smgt_beds';
		$event = $wpdb->get_row("SELECT * FROM $table_smgt_beds where id=$bed_id");
		
		$bed = $event->bed_unique_id;
 		school_append_audit_log(''.esc_html__('Bed Deleted','hospital_mgt').'('.$bed.')'.'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);
		return $result=$wpdb->query($wpdb->prepare("DELETE FROM $table_smgt_beds WHERE id= %d",$bed_id));
	}
	public function mj_smgt_get_assign_bed_by_id($bed_id)
	{
		global $wpdb;
		$table_smgt_assign_beds=$wpdb->prefix.'smgt_assign_beds';
		$result=$wpdb->get_row("SELECT * FROM $table_smgt_assign_beds where bed_id=".$bed_id);
		return $result;
	}
	public function mj_smgt_get_assign_bed_student_by_id($bed_id)
	{
		global $wpdb;
		$table_smgt_assign_beds=$wpdb->prefix.'smgt_assign_beds';
		$result=$wpdb->get_row("SELECT student_id FROM $table_smgt_assign_beds where bed_id=".$bed_id);
		return $result;
	}
	public function mj_smgt_get_hostel_id_by_room_id($room_id)
	{
		global $wpdb;
		$table_smgt_room=$wpdb->prefix.'smgt_room';
		$result=$wpdb->get_row("SELECT * FROM $table_smgt_room where id=".$room_id);
		if($result)
		{
			return $result->hostel_id;
		}
	}
	public function mj_smgt_assign_room($data)
	{
		global $wpdb;
		$table_smgt_beds=$wpdb->prefix.'smgt_beds';
		$table_smgt_assign_beds=$wpdb->prefix.'smgt_assign_beds';
		if(!empty($data['room_id_new']))
		{
			foreach($data['room_id_new'] as $key=>$value)
			{
				$student_unique=$data['student_id'][$key];
			 
				if(!empty($student_unique))
				{  
					$bed_id=$data['bed_id'][$key];
					
					$bed_data=$this->mj_smgt_get_bed_by_id($bed_id);
					$assign_bed_data=$this->mj_smgt_get_assign_bed_by_id($bed_id);
					
					if(!empty($assign_bed_data))
					{			
						
						$assign_bed_id['id'] =$assign_bed_data->id;
						$assign_data['hostel_id']=$data['hostel_id'];
						$assign_data['room_id']=$value;
						$assign_data['bed_id']=$bed_id;
						$assign_data['bed_unique_id']=$data['bed_unique_id'][$key];
						$assign_data['student_id']=$data['student_id'][$key];
						$assign_data['assign_date']=date("Y-m-d", strtotime($data['assign_date'][$key]));
						$assign_data['created_date']=date("Y-m-d");						
						$assign_data['created_by']=get_current_user_id();
						
						$result=$wpdb->update( $table_smgt_assign_beds, $assign_data ,$assign_bed_id);
						
						school_append_audit_log(''.esc_html__('Assign Room Updated','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);
						if($result)
						{
							$bed_data_update['bed_status']=1;
							$assign_bed_id_update['id']=$assign_bed_id;
							$result_update=$wpdb->update( $table_smgt_beds, $bed_data_update ,$assign_bed_id_update);
						}
					}
					else
					{
						
						$assign_data['hostel_id']=$data['hostel_id'];
						$assign_data['room_id']=$value;
						$assign_data['bed_id']=$bed_id;
						$assign_data['bed_unique_id']=$data['bed_unique_id'][$key];
						$assign_data['student_id']=$data['student_id'][$key];
						$assign_data['assign_date']=date("Y-m-d", strtotime($data['assign_date'][$key]));
						$assign_data['created_date']=date("Y-m-d");						
						$assign_data['created_by']=get_current_user_id();
					 
						$result=$wpdb->insert( $table_smgt_assign_beds,$assign_data);
						school_append_audit_log(''.esc_html__('Assign Bed Added','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
						if($result)
						{ 
							//---------- Hostel BED ASSIGNED MAIL ---------//

							$bed_data=$this->mj_smgt_get_bed_by_id($bed_id);
							$currency_symbol = MJ_smgt_get_currency_symbol(get_option( 'smgt_currency_code' ));
							$userdata=get_userdata($student_unique);
							$string = array();
							$string['{{student_name}}']   = mj_smgt_get_display_name($student_unique);
							$string['{{hostel_name}}']   =mj_smgt_hostel_name_by_id($data['hostel_id']);
							$string['{{room_id}}']   = mj_smgt_get_room_unique_id_by_room_id($value);
							$string['{{bed_id}}']   =$data['bed_unique_id'][$key];
							$string['{{bed_charge}}'] = html_entity_decode($currency_symbol).''.$bed_data->bed_charge;
							$string['{{school_name}}'] =  get_option('smgt_school_name');
							$MsgContent                =  get_option('bed_content');		
							$MsgSubject				   =  get_option('bed_subject');
							$message = mj_smgt_string_replacement($string,$MsgContent);
							$MsgSubject = mj_smgt_string_replacement($string,$MsgSubject);
						
							$email= $userdata->user_email;
							mj_smgt_send_mail($email,$MsgSubject,$message);
							
							/* Start Send Push Notification */

							$device_token[] = get_user_meta($student_unique, 'token_id' , true);
							$title = esc_attr__('New Notification For Assign Bed.','school-mgt');
							$text = esc_attr__('You have been assigned new Bed','school-mgt').' '.$data['bed_unique_id'][$key];
							$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));
							$json = json_encode($notification_data);
							$message =	MJ_smgt_send_push_notification($json);

							/* End Send Push Notification */
						}		

						$assign_bed_id_update['id']=$bed_id;
						$bed_data_update['bed_status']=1;
						$result_update=$wpdb->update( $table_smgt_beds, $bed_data_update ,$assign_bed_id_update);					
					}
				}
			}
		}
		return $result;
	}
	public function mj_smgt_delete_assigned_bed($room_id,$bed_id,$student_id)
	{
		school_append_audit_log(''.esc_html__('Assign Bed Deleted','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);
		global $wpdb;
		$table_smgt_beds=$wpdb->prefix.'smgt_beds';
		$table_smgt_assign_beds=$wpdb->prefix.'smgt_assign_beds';
		$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_smgt_assign_beds WHERE room_id=$room_id and bed_id=$bed_id and student_id =".$student_id));
		if($result)
		{
			$assign_bed_id_update['id']=$bed_id;
			$bed_data_update['bed_status']=0;
			$result_update=$wpdb->update( $table_smgt_beds, $bed_data_update ,$assign_bed_id_update);
		} 
		return $result_update;
	}


	// SET REMAINING BED CAPACITY FOR PARTICULAR ROOM
	public function mj_smgt_remaining_bed_capacity($room_id) 
	{
		global $wpdb;

		$table_smgt_room = $wpdb->prefix . 'smgt_room';
		$table_smgt_assign_beds = $wpdb->prefix . 'smgt_assign_beds';

		// GET BED CAPACITY FROM ROOM TABLE
		$beds_capacity = $wpdb->get_var($wpdb->prepare("SELECT beds_capacity FROM $table_smgt_room WHERE id = %d", $room_id));

		// GET ASSIGN BEDS DATA USING ROOM ID
		$assign_beds_row = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_smgt_assign_beds WHERE room_id = %d", $room_id));

		$room_capacity = (int) $beds_capacity;
		$assign_beds = count($assign_beds_row);
		
		$remaining_capacity = $room_capacity - $assign_beds;

		return $remaining_capacity;
	}

	// GET ASSIGN BED USING ROOM ID
	public function mj_smgt_get_assign_bed_by_room_id($room_id) 
	{
		global $wpdb;

		$table_smgt_assign_beds=$wpdb->prefix.'smgt_assign_beds';

		// Use prepared statements to prevent SQL injection
		$query = $wpdb->prepare("SELECT * FROM $table_smgt_assign_beds WHERE room_id = %d", $room_id);
	
		// Use get_results with the prepared query
		$result = $wpdb->get_results($query);
	
		return $result;
	}

	// GET ROOM DATA USING HOSTEL ID
	public function mj_smgt_get_room_by_hostel_id($hostel_id) 
	{
		global $wpdb;
	
		$table_smgt_room = $wpdb->prefix . 'smgt_room';

		// Use prepared statements to prevent SQL injection
		$query = $wpdb->prepare("SELECT * FROM $table_smgt_room WHERE hostel_id = %d", $hostel_id);
	
		// Use get_results with the prepared query
		$result = $wpdb->get_results($query);
	
		return $result;
	}
	
	
}
?>
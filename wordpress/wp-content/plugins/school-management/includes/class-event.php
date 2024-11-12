<?php
class event_Manage
{
    public function mj_smgt_insert_event($data,$file_name)
	{
		
		global $wpdb;
		$table_name=$wpdb->prefix.'event';
		$eventdata['event_title']=stripslashes($data['event_title']);
		$eventdata['description']=mj_smgt_address_description_validation(stripslashes($data['description']));
		$eventdata['start_date']= date('Y-m-d', strtotime($data['start_date']));
		$eventdata['start_time']=$data['start_time'];
		$eventdata['end_date']= date('Y-m-d', strtotime($data['end_date']));
		$eventdata['end_time']=$data['end_time'];
		$eventdata['event_doc']=$file_name;
        $eventdata['created_date']=date('Y-m-d');
		$eventdata['created_by']=get_current_user_id();
    
		if($data['action']=='edit')
		{
			
			$whereid['event_id']=$data['event_id'];
			$result=$wpdb->update( $table_name, $eventdata ,$whereid);
			$event = $eventdata['event_title'];
			school_append_audit_log(''.esc_html__('Event Updated','hospital_mgt').'('.$event.')'.'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_name, $eventdata );
			$event = $eventdata['event_title'];
			school_append_audit_log(''.esc_html__('Event Added','hospital_mgt').'('.$event.')'.'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
            
			if($result)
			{
				$user_list_array = get_users( array(
				'role__in'     => array('supportstaff', 'parent', 'teacher','student'),
				'fields' => array( 'ID' ),
				));
				
				if(!empty($user_list_array))
				{
					$device_token = array();
					foreach($user_list_array as $retrive_data)
					{
						$user_info = get_userdata($retrive_data->ID);
						// Email Notification
						if(isset($data['smgt_enable_event_mail']) == "1")
						{
							$Search['{{user_name}}']   	= 	$user_info->display_name;

							$Search['{{event_title}}']   	= 	stripslashes($data['event_title']);

							$Search['{{event_date}}'] 	    = 	date('Y-m-d', strtotime($data['start_date'])).' To '.date('Y-m-d', strtotime($data['end_date']));
	
							$Search['{{event_time}}'] 		= 	$data['start_time'].' To '.$data['end_time'];
							
							$Search['{{description}}'] 		= 	mj_smgt_address_description_validation(stripslashes($data['description']));

							$Search['{{school_name}}'] 		= 	get_option('smgt_school_name');

							$message 	=	 mj_smgt_string_replacement($Search, get_option('event_mailcontent'));

							$sub['{{school_name}}'] 		= 	get_option('smgt_school_name');

							$subject = mj_smgt_string_replacement($sub, get_option('event_mailsubject'));

							mj_smgt_send_mail($user_info->user_email,$subject,$message);
						}

						// SMS Notification
						if(isset($data['smgt_enable_event_sms']) == "1")
						{
							$message_content = "Exciting New Event ".stripslashes($data['event_title'])." at ".get_option('smgt_school_name').".";

							$type =	"Event";
							
							MJ_smgt_send_sms_notification($retrive_data->ID,$type,$message_content);
						}


					   $device_token[]=get_user_meta($retrive_data->id, 'token_id' , true);
					}
					$title=esc_attr__('You have a New Event','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($data['event_title']));
					$text = mj_smgt_address_description_validation(stripslashes($data['description']));
					$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'event'));
					$json = json_encode($notification_data);
					MJ_smgt_send_push_notification($json);
					//End Send Push Notification//
				}
			}
			return $result;
		}
	}
    // GET SINGLE EVENT
	public function MJ_smgt_get_single_event($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'event';
		$result = $wpdb->get_row("SELECT * FROM $table_name where event_id=".$id) ;
		return $result;
	}
    // GET All EVENT
	public function MJ_smgt_get_all_event()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'event';
		$result = $wpdb->get_results("SELECT * FROM $table_name ORDER BY start_date DESC");
		return $result;
	}
	//------------ DELETE EVENT -----------//
	public function mj_smgt_delete_event($id)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix. 'event';
		$event = $wpdb->get_row("SELECT * FROM $table_name where event_id=$id");
		
		school_append_audit_log(''.esc_html__('Event Deleted','hospital_mgt').'('.$event->event_title.')'.'',get_current_user_id(),get_current_user_id(),'delete','Event');
		$result = $wpdb->query("DELETE FROM $table_name where event_id= ".$id);
		return $result;
	}
	 // GET ALL EVENT FOR DASHBOARD
	 public function MJ_smgt_get_all_event_for_dashboard()
	 {
		 global $wpdb;
		 $table_name = $wpdb->prefix. 'event';
		 $result = $wpdb->get_results("SELECT * FROM $table_name ORDER BY event_id DESC limit 5");
		 return $result;
	 }
	// GET OWN EVENT DATA
	public function MJ_smgt_get_own_event_list($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'event';
		$result = $wpdb->get_results("SELECT * FROM $table_name where created_by=$id");
		return $result;
	}
}
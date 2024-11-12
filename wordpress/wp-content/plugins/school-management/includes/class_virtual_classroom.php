<?php

require_once SMS_PLUGIN_DIR. '/lib/vendor/autoload.php';

class mj_smgt_virtual_classroom

{

	// CREATE MEETING IN ZOOM

	public function mj_smgt_create_meeting_in_zoom($data)

	{
		
		global $wpdb;

		$teacher_all_data = get_userdata($data['teacher_id']);

		if(empty($data['password']))

		{

			$password = wp_generate_password( 10, true, true );

		}

		else

		{

			$password = $data['password'];

		}

		$start_time = $data['start_date'].'T'.$data['start_time'].':'.'00';

		$end_time = $data['end_date'].'T'.$data['end_time'].':'.'00Z';

		if ($data['weekday'] == '1')

		{

			$weekday = 2;

		}

		elseif($data['weekday'] == '2')

		{

			$weekday = 3;

		}

		elseif($data['weekday'] == '3')

		{

			$weekday = 4;

		}

		elseif($data['weekday'] == '4')

		{

			$weekday = 5;

		}

		elseif($data['weekday'] == '5')

		{

			$weekday = 6;

		}

		elseif($data['weekday'] == '6')

		{

			$weekday = 7;

		}

		elseif($data['weekday'] == '7')

		{

			$weekday = 1;

		}

		$clasname = mj_smgt_get_class_name($data['class_id']);

		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
		
	    $arr_token = get_option('smgt_virtual_classroom_access_token');

    	$token_decode = json_decode($arr_token);

	    $accessToken = $token_decode->access_token;

	    $topic = $data['agenda'];

	    try 

	    {

	    	if ($data['action']=='edit')

			{

				$meetingId = $data['zoom_meeting_id'];

				$response = $client->request('PATCH', "/v2/meetings/{$meetingId}", [

		            "headers" => [

		                "Authorization" => "Bearer $accessToken"

		            ],

		            "Query" => [

		                "occurrence_id" => "u+56LsDKSTmVXefuuMG8ug=="

		            ],

		            'json' => [

		                "topic" => $clasname,

		                // "type" => 8,

		                "start_time" => $start_time,

		                "password" => $password,

		                "agenda" => $topic,

		                "recurrence" => [

						"type" => 2,

						"weekly_days" => $weekday,

						"end_date_time" => $end_time,

						]

		            ],

		        ]);

			}

			else

			{
				var_dump($password);
				
				$response = $client->request('POST', '/v2/users/me/meetings', [

				"headers" => [

					"Authorization" => "Bearer $accessToken"

				],

				"Query" => [

					"occurrence_id" => "u+56LsDKSTmVXefuuMG8ug=="

				],

	            'json' => [

	                "topic" => $clasname,

					"start_time" => $start_time,

					"agenda" => $topic,

		                "recurrence" => [

						"type" => 2,

						"weekly_days" => $weekday,

						"end_date_time" => $end_time,

						]
		            ],

		        ]);
		        $meeting_response = json_decode($response->getBody());

			}
			
        	$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

        	$meeting_data['title'] = $clasname;

        	$meeting_data['route_id'] = (int)$data['route_id'];

        	$meeting_data['class_id'] = (int)$data['class_id'];

        	$meeting_data['section_id'] = (int)$data['class_section_id'];

        	$meeting_data['subject_id'] = (int)$data['subject_id'];

        	$meeting_data['teacher_id'] = (int)$data['teacher_id'];

        	$meeting_data['agenda'] = $data['agenda'];

        	$meeting_data['start_date'] = $data['start_date'];

        	$meeting_data['end_date'] = $data['end_date'];

        	$meeting_data['weekday_id'] = $weekday;

        	$meeting_data['password'] = $password;

        	if($data['action']=='edit')

			{

				

				$meeting_data['zoom_meeting_id'] = $data['zoom_meeting_id'];

        		$meeting_data['uuid'] = $data['uuid'];

				$meeting_data['meeting_join_link'] = $data['meeting_join_link'];

        		$meeting_data['meeting_start_link'] = $data['meeting_start_link'];

				$meetingid['meeting_id']=sanitize_text_field($data['meeting_id']);

				$meeting_data['updated_date']=date("Y-m-d h:i:sa");

			    $meeting_data['updated_by']=get_current_user_id();
				
				$result=$wpdb->update( $table_zoom_meeting, $meeting_data ,$meetingid);
				
				school_append_audit_log(''.esc_html__('Virtual Classroom Updated','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);

			}

			else

			{

				if($meeting_response)

				{

					

					$meeting_data['zoom_meeting_id'] = $meeting_response->id;

					$meeting_data['uuid'] = $meeting_response->uuid;

					$meeting_data['meeting_join_link'] = $meeting_response->join_url;

					$meeting_data['meeting_start_link'] = $meeting_response->start_url;

					$meeting_data['created_by'] = get_current_user_id();

					$meeting_data['created_date'] = date("Y-m-d h:i:sa");

					$result=$wpdb->insert( $table_zoom_meeting, $meeting_data );

					school_append_audit_log(''.esc_html__('Virtual Classroom Added','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
					
				}

			}
			
			return $result;

		}

		catch(Exception $e) 

		{

			if(401 == $e->getCode())

			{

				mj_smgt_generate_access_token();

			}

			else

			{

				wp_redirect ( admin_url().'admin.php?page=smgt_virtual_classroom&tab=meeting_list&message=5');

			}

		}

	}

	// GET ALL MEETING DATA IN ZOOM

	public function mj_smgt_get_all_meeting_data_in_zoom()

	{

		global $wpdb;

		$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

		$result = $wpdb->get_results("SELECT * FROM $table_zoom_meeting");

		return $result;

	}

	// GET MEETING BY TEACHEAR ID DATA IN ZOOM

	public function mj_smgt_get_meeting_by_teacher_id_data_in_zoom($teacher_id)

	{

		global $wpdb;

		$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

		$result = $wpdb->get_results("SELECT * FROM $table_zoom_meeting WHERE teacher_id=$teacher_id");

		return $result;

	}

	// GET MEETING BY CLASS ID DATA IN ZOOM

	public function mj_smgt_get_meeting_by_class_id_data_in_zoom($class_id)

	{

		global $wpdb;

		$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

		$result = $wpdb->get_results("SELECT * FROM $table_zoom_meeting WHERE class_id=$class_id");

		return $result;

	}

	// GET MEETING BY CLASS ID DATA IN ZOOM

	public function mj_smgt_get_meeting_by_class_id_and_section_id_data_in_zoom($class_id,$section_id)

	{

		global $wpdb;

		$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

		$result = $wpdb->get_results("SELECT * FROM $table_zoom_meeting WHERE class_id=$class_id AND section_id=$section_id");

		return $result;

	}

	// GET SINGAL MEETING DATA IN ZOOM

	public function mj_smgt_get_singal_meeting_data_in_zoom($meeting_id)

	{

		global $wpdb;

		$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

		$result = $wpdb->get_row("SELECT * FROM $table_zoom_meeting WHERE meeting_id=$meeting_id");

		return $result;

	}

	// GET SINGAL MEETING DATA BY ROUTE IN ZOOM

	public function mj_smgt_get_singal_meeting_by_route_data_in_zoom($route_id)

	{

		global $wpdb;

		$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

		$result = $wpdb->get_row("SELECT * FROM $table_zoom_meeting WHERE route_id=$route_id");

		return $result;

	}

	// GET MEETING DATA BY DAY IN ZOOM

	public function mj_smgt_get_meeting_data_by_day_in_zoom($day_id)

	{

		global $wpdb;

		$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

		$result = $wpdb->get_results("SELECT * FROM $table_zoom_meeting WHERE weekday_id=$day_id");

		return $result;

	}

	// DELETE MEETING

	public function mj_smgt_delete_meeting_in_zoom($meeting_id)

	{

		school_append_audit_log(''.esc_html__('Virtual Classroom Deleted','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);

		global $wpdb;

		$meeting_data = $this->mj_smgt_get_singal_meeting_data_in_zoom($meeting_id);

		

		try

		{

			if(!empty($meeting_data))

			{

				$client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

				$arr_token = get_option('smgt_virtual_classroom_access_token');

		    	$token_decode = json_decode($arr_token);

			    $accessToken = $token_decode->access_token;

			    $zoom_meeting_id = $meeting_data->zoom_meeting_id;

			    // $response = $client->request('DELETE', "/v2/meetings/{$zoom_meeting_id}", [

			    // "headers" => [

			    //     "Authorization" => "Bearer $accessToken"

			    // ]

			    // ]);

			}

			$table_zoom_meeting= $wpdb->prefix. 'smgt_zoom_meeting';

			$result = $wpdb->query("DELETE FROM $table_zoom_meeting WHERE meeting_id=$meeting_id");

			

		}catch(Exception $e){

	    	if(401 == $e->getCode())

			{

				mj_smgt_generate_access_token();

			}

			else

			{

				wp_redirect ( admin_url().'admin.php?page=smgt_virtual_classroom&tab=meeting_list&message=5');

			}

	    }

		return $result;

	}

	// PAST PARTICIPEL LIST

	public function mj_smgt_view_past_participle_list_in_zoom($meeting_uuid)

	{

		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

		$arr_token = get_option('smgt_virtual_classroom_access_token');

    	$token_decode = json_decode($arr_token);

	    $accessToken = $token_decode->access_token;

	    try

	    {

			$response = $client->request('GET', "v2/past_meetings/{$meeting_uuid}/participants", [

	            "headers" => [

	                "Authorization" => "Bearer $accessToken"

	            ],

	            "Query" =>[

	                "type" => 'past',

	                "page_size" => 30,

	                "include_fields" => 'device',

	            ]

	        ]);

	        $result = json_decode($response->getBody());

	    }catch(Exception $e)

	    {

	    	if(401 == $e->getCode())

			{

				mj_smgt_generate_access_token();

			}

	    }

	    return $result;

	}

}

?>
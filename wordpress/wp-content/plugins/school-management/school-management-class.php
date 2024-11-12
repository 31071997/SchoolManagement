<?php	
class School_Management
{
	public $student;
	public $teacher;
	public $exam;
	public $result;
	public $subject;
	public $schedule;
	public $transport;
	public $notice;
	public $message;
	public $role;
	public $class_info;
	public $parent_list;
	public $child_list;
	public $payment;
	public $feepayment;
	public $class_section;
	
	function __construct($user_id = NULL)
	{
		if($user_id)
		{
		
			if($this->mj_smgt_get_current_user_role($user_id) == 'student')
			{
				$this->role= "student";
				$this->class_info = $this->mj_smgt_get_user_class_id($user_id);
			
				$this->class_section_info = $this->mj_smgt_get_user_class_id($user_id);
				$this->subject = $this->mj_smgt_subject_list($this->class_info->class_id);
				
				$this->parent_list = $this->mj_smgt_parants($user_id);
				 
				$this->student = $this->mj_smgt_get_student_list($this->class_info->class_id);
				$this->payment_list = $this->mj_smgt_payment('student');
				
				
				$this->notice = $this->mj_smgt_notice_board($this->mj_smgt_get_current_user_role());
			}
			if($this->mj_smgt_get_current_user_role($user_id) == 'teacher')
			{
				$this->role= "teacher";
				$teacher_access = get_option( 'smgt_access_right_teacher');
				$teacher_access_data=$teacher_access['teacher'];
				foreach($teacher_access_data as $key=>$value)
				{
					if($key=='student')
					{
						$data=$value;
					}
				}
				if($data['own_data']=='1')
				{
					$class_id=get_user_meta($user_id,'class_name',true);
					$this->student =$this->mj_smgt_get_teacher_student_list($class_id);
				}
				else
					$this->student = $this->mj_smgt_get_all_student_list();
					$this->notice = $this->mj_smgt_notice_board($this->mj_smgt_get_current_user_role());
			}
			if($this->mj_smgt_get_current_user_role($user_id) == 'supportstaff')
			{
				$this->role= "supportstaff";
				$this->student = $this->mj_smgt_get_all_student_list();
				$this->notice = $this->mj_smgt_notice_board($this->mj_smgt_get_current_user_role());
				$this->payment_list = $this->mj_smgt_payment('supportstaff');
			}
			
			if($this->mj_smgt_get_current_user_role($user_id) == 'parent')
			{
				
				$this->role="parent";
				$this->child_list = $this->mj_smgt_child($user_id);
				$this->student =$this->mj_smgt_get_all_student_list();
				$this->payment_list = $this->mj_smgt_payment('parent');
				$this->notice = $this->mj_smgt_notice_board($this->mj_smgt_get_current_user_role());
			}
			if($this->mj_smgt_get_current_user_role($user_id) == 'administrator')
			{
				$this->role= "admin";
			}
			if($this->mj_smgt_get_current_user_role($user_id) == 'management')
			{
				$this->role= "management";
			}
			$this->payment = $this->mj_smgt_payment($this->mj_smgt_get_current_user_role());
			$this->feepayment = $this->mj_smgt_feepayment($this->mj_smgt_get_current_user_role());
		}
	}

	public function mj_smgt_get_current_user_role($userid=0) {
		if($userid!=0)
		{
			$current_user=get_userdata($userid);
			$user_roles = $current_user->roles;
		}
		else
		{
			global $current_user;
			$user_roles = $current_user->roles;
		}
		$user_role = array_shift($user_roles);
		
		return $user_role;
	}
	
	public function mj_smgt_get_user_class_id($user_id)
	{
		$user =get_userdata( $user_id );
		$user_meta =get_user_meta($user_id);
		$class_id = $user_meta['class_name'][0];
		global $wpdb;
		$table_name = $wpdb->prefix .'smgt_class';
		$class_info =$wpdb->get_row("SELECT * FROM $table_name WHERE class_id=".$class_id);
		return $class_info;
	}
	public function mj_smgt_get_user_sectio_id($user_id)
	{
		$user =get_userdata( $user_id );
		$user_meta =get_user_meta($user_id);
		$section_id = $user_meta['class_section'][0];
		global $wpdb;
		$table_name = $wpdb->prefix .'smgt_class_section';
		$section_info =$wpdb->get_row("SELECT * FROM $table_name WHERE id=".$class_id);
		return $section_info;
	}
	
	public function mj_smgt_subject_list($class_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix .'subject';
		
		$result =$wpdb->get_results("SELECT * FROM $table_name WHERE class_id=".$class_id);
		return $result;
	}
	
	public function mj_smgt_subject_list_with_calss_and_section($class_id,$section_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix .'subject';
		
		$result =$wpdb->get_results("SELECT * FROM $table_name WHERE class_id=$class_id and section_id=".$section_id);
		return $result;
	}
	
	
	public function mj_smgt_notice_board($role,$limit = -1)
	{
	  
		$args['post_type'] = 'notice';
		$args['posts_per_page'] = $limit;
		$args['post_status'] = 'public';
		$args['orderby'] = 'date';
		$args['order'] = 'DESC';
		
		
		$retrieve_noticeData1=array();
			if($role=='student')				   
			{				
				$class_id=get_user_meta(get_current_user_id(),'class_name',true);
				$section_id=get_user_meta(get_current_user_id(),'class_section',true);				
				$args['meta_query'] = array(
					'relation' => 'OR',
						array(
							'relation' => 'OR',
								array(
									'key' => 'smgt_section_id',
									'value' =>get_user_meta(get_current_user_id(),'class_section',true),	
									'compare' => '='
								),
								array(
									'key' => 'smgt_class_id',
									'value' =>get_user_meta(get_current_user_id(),'class_name',true),
									'compare' => '='
								)
						)
				);
					$q = new WP_Query();
					$retrieve_class_notice = $q->query( $args );	
					foreach($retrieve_class_notice as $notice)
					{
						$retrieve_noticeData1[]=$notice->ID;
					}
						$retrieve_notice=$retrieve_noticeData1;
			}
			else
			{
				$args['meta_query'] = array(
						'relation' => 'OR' 
					);
					$q = new WP_Query();
					$retrieve_notice = $q->query( $args );
			}
		
		return $retrieve_notice;
	}
	
	
	private function mj_smgt_notice_board_student($user_id,$role)
	{
		$class_id=get_user_meta($user_id, 'class_name',true);
		global $wpdb;
		$table_post = $wpdb->prefix .'posts';
		$table_postmeta = $wpdb->prefix .'postmeta';
		
		$notice_limit = "";
		if(!isset($_REQUEST['page']) )
			$notice_limit = "Limit 0,3";
		$sql=" select * FROM $table_post as post,$table_postmeta as post_meta where post.post_type='notice' AND 
		 (post.ID=post_meta.post_id AND (post_meta.meta_key = 'notice_for' AND 
		 (post_meta.meta_value = '$role' OR post_meta.meta_value = 'all')) OR 
		 (post_meta.meta_key = 'notice_for' AND post_meta.meta_key = 'smgt_class_id' AND
		  (post_meta.meta_value = '$class_id' OR post_meta.meta_value = 'all'))) $notice_limit";
	
		$retrieve_notice = $wpdb->get_results( $sql );
		return $retrieve_notice;
	
	}
	 function mj_smgt_notice_board_parent($role)
	{
		$args['post_type'] = 'notice';
		$args['posts_per_page'] = -1;
		$args['post_status'] = 'public';
	
		$args['meta_query'] = array(
				'relation' => 'OR',
				array(
						'key' => 'notice_for',
						'value' =>"all",
				),
				array(
						'key' => 'notice_for',
						'value' =>"$role",
				)
		);
		$q = new WP_Query();
	
		$retrieve_notice = $q->query( $args );
		return $retrieve_notice;
	
	}
	private function mj_smgt_notice_board_teacher($role)
	{
		$args['post_type'] = 'notice';
		$args['posts_per_page'] = -1;
		$args['post_status'] = 'public';
		$class_id = "";
		$args['meta_query'] = array(
				'relation' => 'OR',
				array(
						'key' => 'notice_for',
						'value' =>"all",
				),
				array(
						'key' => 'notice_for',
						'value' =>"$role",
				)
		);
		$q = new WP_Query();
	
		$retrieve_notice = $q->query( $args );
		return $retrieve_notice;
	
	}
	
	private function mj_smgt_payment($user_role)
	{
		global $wpdb;
		$table_name = $wpdb->prefix .'smgt_payment as p';
		$table_users = $wpdb->prefix .'users as u';
		if($user_role == 'student')
		{
			$result =$wpdb->get_results("SELECT * FROM $table_name WHERE student_id=".get_current_user_id());
		}
		else if($user_role == 'parent')
		{
			$result =$wpdb->get_results("SELECT * FROM $table_name WHERE student_id in (".implode(',', $this->child_list).")");	
		}
		else
			$result =$wpdb->get_results("SELECT * FROM $table_name,$table_users  where p.student_id = u.id ");
						
	 	return $result;
	}
	private function mj_smgt_feepayment($user_role)
	{
		global $wpdb;
		$table_name = $wpdb->prefix .'smgt_fees_payment as p';
		$table_users = $wpdb->prefix .'users as u';
		if($user_role == 'student')
		{
			$result =$wpdb->get_results("SELECT * FROM $table_name WHERE student_id=".get_current_user_id());
		}
		else if($user_role == 'parent')
		{
			$result =$wpdb->get_results("SELECT * FROM $table_name WHERE student_id in (".implode(',', $this->child_list).")");
		}
		else
		{
			$result =$wpdb->get_results("SELECT * FROM $table_name,$table_users  where p.student_id = u.id ");
		}
		
	 	return $result;
	}
	public function mj_smgt_get_teacher_student_list($class_id)
	{
		$exlude_id = mj_smgt_approve_student_list();
		$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
		return $students;
	}
	public function mj_smgt_get_student_list($class_id)
	{
		$exlude_id = mj_smgt_approve_student_list();
		$student_access = get_option( 'smgt_access_right_student');
		
		$student_access_data=$student_access['student'];
		foreach($student_access_data as $key=>$value)
		{
			if($key=='student')
			{
				$data=$value;
			}
		}
		if($this->role == 'student' && $data['own_data']=='1')
		{
			$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id, 'role'=>'student','exclude'=>$exlude_id));
		}
		else{
			$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id, 'role'=>'student','exclude'=>$exlude_id));
		}
		return $students;
	}
	public function mj_smgt_get_all_student_list()
	{
		$exlude_id = mj_smgt_approve_student_list();
		$students = get_users(array('role'=>'student','exclude'=>$exlude_id));
		return $students;
	}
	private function mj_smgt_parants($user_id)
	{
		
		$user_meta =get_user_meta($user_id, 'parent_id', true);
		return $user_meta;
	}
	private function mj_smgt_child($user_id)
	{
	
		$user_meta =get_user_meta($user_id, 'child', true);
		return $user_meta;
	}
	
}
?>
<?php 

class mj_smgt_feespayment

{		

	public function mj_smgt_delete_fee_type($cat_id)

	{

		$result=wp_delete_post($cat_id);		

		return $result;

	}

	

	public function mj_smgt_add_feespayment($data)
	{

		global $wpdb;

		$table_smgt_fees_payment 	= $wpdb->prefix. 'smgt_fees_payment';	

		$table_smgt_fees_payment_recurring 	= $wpdb->prefix. 'smgt_fees_payment_recurring';	

		$table_income=$wpdb->prefix.'smgt_income_expense';

		$feedata['class_id']    	=	mj_smgt_onlyNumberSp_validation($_POST['class_id']);

		$feedata['section_id']		=	mj_smgt_onlyNumberSp_validation($_POST['class_section']);	

		$feedata['fees_id']		    =	implode(',',(array)$_POST['fees_id']);

		$feedata['fees_amount']	=	$_POST['fees_amount'];	

		$feedata['description']		=	mj_smgt_address_description_validation(stripslashes($_POST['description']));	

		$feedata['start_year']		=	date('Y-m-d', strtotime($_POST['start_year']));	

		$feedata['end_year']		=	date('Y-m-d', strtotime($_POST['end_year']));	

		$feedata['paid_by_date']	=	date("Y-m-d");		

		$feedata['created_date']	=	date("Y-m-d H:i:s");

		$feedata['created_by']		=	get_current_user_id();

		if(isset($data['tax']))
		{
			$feedata['tax']=implode(",",(array)$data['tax']);	
			$feedata['tax_amount'] = MJ_smgt_get_tax_amount($data['fees_amount'],$data['tax']);
		}
		else
		{
			$feedata['tax']=null;
			$feedata['tax_amount'] = 0;	
		}	

		$feedata['total_amount']	=	$feedata['fees_amount'] + $feedata['tax_amount'];	

		$email_subject				=	get_option('fee_payment_title');		

		$SchoolName 				= 	get_option('smgt_school_name');

		if($data['action']=='edit')
		{
			$feedata['student_id']	=	$data['student_id'];				

			$fees_id['fees_pay_id']	=	$data['fees_pay_id'];

			$result=$wpdb->update($table_smgt_fees_payment,$feedata,$fees_id);

				$student = mj_smgt_get_user_name_byid($feedata['student_id']);

			school_append_audit_log(''.esc_html__('Fees Payment Updated','hospital_mgt').'('.$student.')'.'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);		
			return $result;
		}
		else
		{
			/* Add Recurring Payment Data */
			if($_POST['recurrence_type'] != 'one_time')
			{
				$recurring_feedata['class_id']    	=	mj_smgt_onlyNumberSp_validation($_POST['class_id']);
				$recurring_feedata['section_id']		=	mj_smgt_onlyNumberSp_validation($_POST['class_section']);	
				$recurring_feedata['fees_id']		    =	implode(',',(array)$_POST['fees_id']);
				$recurring_feedata['student_id']		    =	implode(',',(array)$_POST['selected_users']);
				$recurring_feedata['fees_amount']	=	$_POST['fees_amount'];
				if(isset($data['tax']))
				{
					$recurring_feedata['tax']=implode(",",(array)$data['tax']);	
					$recurring_feedata['tax_amount'] = MJ_smgt_get_tax_amount($data['fees_amount'],$data['tax']);
				}
				else
				{
					$recurring_feedata['tax']=null;
					$recurring_feedata['tax_amount'] = 0;	
				}	
				$recurring_feedata['total_amount']	=	$recurring_feedata['fees_amount'] + $recurring_feedata['tax_amount'];	
				$recurring_feedata['description']		=	mj_smgt_address_description_validation(stripslashes($_POST['description']));	
				$recurring_feedata['start_year']		=	date('Y-m-d', strtotime($_POST['start_year']));	
				$recurring_feedata['recurring_type']		=	mj_smgt_onlyNumberSp_validation($_POST['recurrence_type']);

				
				if($_POST['recurrence_type'] == 'monthly')
				{
					$recurring_enddate = date('Y-m-d', strtotime("+1 months", strtotime($_POST['start_year'])));
				}
				elseif ($_POST['recurrence_type'] == 'weekly') {
					$recurring_enddate = date('Y-m-d', strtotime("+1 week", strtotime($_POST['start_year'])));
				}
				else if($_POST['recurrence_type'] == 'quarterly')
				{
					$recurring_enddate = date('Y-m-d', strtotime("+3 months", strtotime($_POST['start_year'])));
				}
				else if($_POST['recurrence_type'] == 'half_yearly')
				{
					$recurring_enddate = date('Y-m-d', strtotime("+6 months", strtotime($_POST['start_year'])));
				}
				else
				{
					$recurring_enddate = date('Y-m-d', strtotime($_POST['end_year']));	
				}
			
				$recurring_feedata['end_year']		=	   date('Y-m-d', strtotime($_POST['end_year']));
				$recurring_feedata['recurring_enddate']	=	$recurring_enddate;			
				$recurring_feedata['status']	=	'yes';			
				$recurring_feedata['created_date']	=	date("Y-m-d H:i:s");
				$recurring_feedata['created_by']		=get_current_user_id();
				$result_recurring	=	$wpdb->insert( $table_smgt_fees_payment_recurring, $recurring_feedata );
				
				$feedata['end_year']		=	$recurring_enddate;
			}
			/* End Add Recurring Payment Data */
			
			$students = $data['selected_users'];
			
			$table_income=$wpdb->prefix.'smgt_income_expense';

			$fees_type=array();

			foreach($_POST['fees_id'] as $id)
			{ 
				$fees_type[] = mj_smgt_get_fees_term_name($id);
			}

			$fee_title = implode(" , " ,$fees_type);	

			$entry_array[] = array('entry'=>$fee_title,'amount'=>$data['fees_amount']);

			$entry_value = json_encode($entry_array);

			foreach ($students as $student_id) 
			{
				$feedata['student_id'] = $student_id;
				/* Add Fees Payment Data */
				$student_info 	= 	get_userdata($student_id);
				$parent 	= 	get_user_meta($student_id, 'parent_id', true);
				$roll_id 	= 	get_user_meta($student_id, 'roll_id', true);
				$class_name	=	get_user_meta($student_id,'class_name',true);

				$result	=	$wpdb->insert( $table_smgt_fees_payment, $feedata );
				$fees_pay_id 	= 	$wpdb->insert_id;
				$student_name = mj_smgt_get_user_name_byid($student_id);
				school_append_audit_log(''.esc_html__('Fees Payment Added','hospital_mgt').'('.$student_name.')'.'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
				/* END Add Fees Payment Data */
				if(isset($_POST['smgt_enable_feesalert_mail']) == '1' || isset($_POST['smgt_enable_feesalert_sms_student']) == '1' || isset($_POST['smgt_enable_feesalert_sms_parent']) == '1')
				{
					
					if(isset($_POST['smgt_enable_feesalert_mail']) == '1')
					{
						//Send Mail Notiifcation to student //
						$Cont = get_option('fee_payment_mailcontent');
						$email = $student_info->user_email;					
						$SearchArr['{{student_name}}']	=	$student_info->display_name;
						$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');
						$SearchArr['{{date}}']     = mj_smgt_getdate_in_input_box(date('Y-m-d'));
						$SearchArr['{{amount}}']     = MJ_smgt_currency_symbol_position_language_wise(number_format($_POST['fees_amount'],2,'.',''));
						$MessageContent = mj_smgt_string_replacement($SearchArr,get_option('fee_payment_mailcontent'));
						if(get_option('smgt_mail_notification') == '1')
						{
							mj_smgt_send_mail_paid_invoice_pdf($email,get_option('fee_payment_title'),$MessageContent,$fees_pay_id);
						}
						//End Send Mail Notiifcation to student //
						if(!empty($parent))
						{
							//Send Mail To Parant code start //
							foreach($parent as $parent_id)
							{
								$parent_info = get_userdata($parent_id);
								
								$Cont = get_option('fee_payment_title_for_parent');
								$email = $parent_info->user_email;					
								$SearchArr['{{parent_name}}']	=	$parent_info->display_name;
								$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');
								$SearchArr['{{date}}']     = mj_smgt_getdate_in_input_box(date('Y-m-d'));
								$SearchArr['{{amount}}']     = MJ_smgt_currency_symbol_position_language_wise(number_format($_POST['fees_amount'],2,'.',''));
								$SearchArr['{{child_name}}']	=	$student_info->display_name;
								$MessageContent = mj_smgt_string_replacement($SearchArr,get_option('fee_payment_mailcontent_for_parent'));
								if(get_option('smgt_mail_notification') == '1')
								{
									mj_smgt_send_mail_paid_invoice_pdf($email,get_option('fee_payment_title'),$MessageContent,$fees_pay_id);
								}
								
							}
						}
					}
					// SEND SMS NOTIFICATION TO STUDENT
					if(isset($_POST['smgt_enable_feesalert_sms_student']) == '1')
					{
						$message_content = "A new fees payment invoice has been generated for you.";
						$type = "Feespayment";
						// MJ_smgt_send_sms_notification($student_id,$type,$message_content);
					}

					// SEND SMS NOTIFICATION TO PARENT
					if(isset($_POST['smgt_enable_feesalert_sms_parent']) == '1')
					{
						if(!empty($parent))
						{
							foreach($parent as $parent_id)
							{
								$message_content = "A new fees payment invoice has been generated for your child.";
								$type = "Feespayment";
								// MJ_smgt_send_sms_notification($parent_info->ID,$type,$message_content);
							}
						}
					}
				}
			}
			
		}
		return $result;
	}
	public function mj_smgt_add_recurring_feespayment($data)
	{
		global $wpdb;
		$table_smgt_fees_payment_recurring 	= $wpdb->prefix. 'smgt_fees_payment_recurring';	
		if($data['action']=='edit')
		{
			$recurring_feedata['class_id']    	=	mj_smgt_onlyNumberSp_validation($_POST['class_id']);
			$recurring_feedata['section_id']		=	mj_smgt_onlyNumberSp_validation($_POST['class_section']);	
			$recurring_feedata['fees_id']		    =	implode(',',(array)$_POST['fees_id']);
			$recurring_feedata['student_id']		    =	implode(',',(array)$_POST['selected_users']);
			$recurring_feedata['total_amount']	=	$_POST['fees_amount'];	
			$recurring_feedata['description']		=	mj_smgt_address_description_validation(stripslashes($_POST['description']));	
			$recurring_feedata['start_year']		=	date('Y-m-d', strtotime($_POST['start_year']));	
			$recurring_feedata['end_year']		=	date('Y-m-d', strtotime($_POST['end_year']));
			$recurring_feedata['recurring_type']		=	mj_smgt_onlyNumberSp_validation($_POST['recurrence_type']);	
			$recurring_feedata['status']	=	$_POST['status'];	
			$recurring_feedata['created_date']	=	date("Y-m-d H:i:s");
			$recurring_feedata['created_by']		=get_current_user_id();
			//Update Recuring END DATE//
			$recurring_feedata['recurring_enddate']	=	$_POST['last_recurrence_date'];	
			$recurring_fees_id['recurring_id']	=	$_POST['recurring_fees_id'];
			$result=$wpdb->update($table_smgt_fees_payment_recurring,$recurring_feedata,$recurring_fees_id);
			return $result;
		}
	}
	public function mj_smgt_get_all_student_fees_data($std_id)

	{		

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment WHERE student_id=$std_id");

		return $result;

	}

	public function mj_smgt_get_payment_histry_data($fees_pay_id)

	{		

		global $wpdb;

		$table_smgt_fee_payment_history = $wpdb->prefix. 'smgt_fee_payment_history';		

		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fee_payment_history WHERE fees_pay_id=$fees_pay_id");

		return $result;

	}



public function mj_smgt_add_feespayment_history($data)

{	

	global $wpdb;

	$table_smgt_fee_payment_history = $wpdb->prefix. 'smgt_fee_payment_history';

	$tbl_payment = $wpdb->prefix. 'smgt_fees_payment';

	//-------usersmeta table data--------------

	if(isset($_POST['fees_pay_id']))

		$fees_pay_id = $_POST['fees_pay_id'];

	else

		$fees_pay_id = $data['fees_pay_id'];		

		$feedata['fees_pay_id']=$fees_pay_id;

		$feedata['amount']=$data['amount'];

		$feedata['payment_method']=$data['payment_method'];	

		

		if(isset($data['trasaction_id']))

		{

			$feedata['trasaction_id']=$data['trasaction_id'] ;

		}

		$feedata['paid_by_date']=date("Y-m-d");

		

		$feedata['created_by']= get_current_user_id();

		

		$paid_amount = $this->mj_smgt_get_paid_amount_by_feepayid($feedata['fees_pay_id']);

		

		$uddate_data['fees_paid_amount'] = $paid_amount + $feedata['amount'];

		$uddate_data['payment_status'] = $this->mj_smgt_get_payment_status_name($data['fees_pay_id']);

		$uddate_data['fees_pay_id'] = $fees_pay_id;

		$this->mj_smgt_update_paid_fees_amount($uddate_data);

		$uddate_data1['payment_status'] = $this->mj_smgt_get_payment_status_name($fees_pay_id);

		$uddate_data1['fees_pay_id'] = $fees_pay_id;

		$this->mj_smgt_update_payment_status_fees_amount($uddate_data1);

		$result=$wpdb->insert( $table_smgt_fee_payment_history, $feedata );		

		

		$email_subject 	= 	get_option('payment_recived_mailsubject');

		$MailCont	= 	get_option('payment_recived_mailcontent');

		$feespaydata = $this->mj_smgt_get_single_fee_mj_smgt_payment($fees_pay_id);

		$StudentData = get_userdata($feespaydata->student_id);	

		

		$SearchArr['{{student_name}}'] 	= 	mj_smgt_get_display_name($feespaydata->student_id);

		$SearchArr['{{invoice_no}}']	= 	$feespaydata->fees_pay_id;

		$SearchArr['{{school_name}}'] 	= 	get_option('smgt_school_name');

		

		$email_to 	 = $StudentData->user_email;

		$search['{{school_name}}'] = get_option('smgt_school_name');						

		$email_message=mj_smgt_string_replacement($SearchArr,get_option('payment_recived_mailcontent'));

		if(get_option('smgt_mail_notification') == '1')

		{	

			mj_smgt_send_mail_paid_invoice_pdf($email_to,$email_subject,$email_message,$fees_pay_id);

		}			

		/* Start Send Push Notification */

		$student_id = $feespaydata->student_id;

		$device_token[] = get_user_meta($student_id, 'token_id' , true);

		$title = esc_attr__('New Notification For Payment','school-mgt');

		$text = esc_attr__('Your have successfully paid your invoice','school-mgt');

		$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));

		$json = json_encode($notification_data);

		$message =	MJ_smgt_send_push_notification($json);

		/* End Send Push Notification */

		return $result;

}

public function mj_smgt_add_feespayment_history_For_payfast($data)

{	

	global $wpdb;

	$table_smgt_fee_payment_history = $wpdb->prefix. 'smgt_fee_payment_history';

	$tbl_payment = $wpdb->prefix. 'smgt_fees_payment';



		$fees_pay_id = $data['fees_pay_id'];		

		$feedata['fees_pay_id']=$fees_pay_id;

		$feedata['amount']=$data['amount'];

		$feedata['payment_method']=$data['payment_method'];	

		$feedata['trasaction_id']=$data['trasaction_id'] ;

		$feedata['paid_by_date']=date("Y-m-d");

		$feedata['created_by']= $data['created_by'];

		

		$paid_amount = $this->mj_smgt_get_paid_amount_by_feepayid($feedata['fees_pay_id']);

		

		$uddate_data['fees_paid_amount'] = $paid_amount + $feedata['amount'];

		$uddate_data['payment_status'] = $this->mj_smgt_get_payment_status_name($data['fees_pay_id']);

		$uddate_data['fees_pay_id'] = $fees_pay_id;

		$this->mj_smgt_update_paid_fees_amount($uddate_data);

		$uddate_data1['payment_status'] = $this->mj_smgt_get_payment_status_name($fees_pay_id);

		$uddate_data1['fees_pay_id'] = $fees_pay_id;

		$this->mj_smgt_update_payment_status_fees_amount($uddate_data1);

		$result=$wpdb->insert( $table_smgt_fee_payment_history, $feedata );		

		

		$email_subject 	= 	get_option('payment_recived_mailsubject');

		$MailCont	= 	get_option('payment_recived_mailcontent');

		$feespaydata = $this->mj_smgt_get_single_fee_mj_smgt_payment($fees_pay_id);

		

		

		$SearchArr['{{student_name}}'] 	= 	$data['name_first'].' '.$data['name_last'];

		$SearchArr['{{invoice_no}}']	= 	$feespaydata->fees_pay_id;

		$SearchArr['{{school_name}}'] 	= 	get_option('smgt_school_name');

		

		$email_to 	 = $data['email_address'];

		$search['{{school_name}}'] = get_option('smgt_school_name');						

		$email_message=mj_smgt_string_replacement($SearchArr,get_option('payment_recived_mailcontent'));

		if(get_option('smgt_mail_notification') == '1')

		{	

			mj_smgt_send_mail_paid_invoice_pdf($email_to,$email_subject,$email_message,$fees_pay_id);

		}			

		return $result;

}

public function mj_smgt_get_payment_status_name($fees_pay_id)

{	

	global $wpdb;

	$table_smgt_fees_payment = $wpdb->prefix .'smgt_fees_payment';	

	$result =$wpdb->get_row("SELECT * FROM $table_smgt_fees_payment WHERE fees_pay_id=".$fees_pay_id);

	if(!empty($result))

	{	

		if($result->fees_paid_amount == 0)

		{

			return 1;

		}

		elseif($result->fees_paid_amount < $result->total_amount)

		{

			return 1;

		}

		else

			return 2;

	}

	else

		return 0;

}

	public function mj_smgt_get_paid_amount_by_feepayid($fees_pay_id)

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$result = $wpdb->get_row("SELECT fees_paid_amount FROM $table_smgt_fees_payment where fees_pay_id = $fees_pay_id");

		return $result->fees_paid_amount;

	}

	public function mj_smgt_update_paid_fees_amount($data)

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$feedata['fees_paid_amount'] = $data['fees_paid_amount'];

		$feedata['payment_status'] = $data['payment_status'];

		$fees_id['fees_pay_id']=$data['fees_pay_id'];

			$result=$wpdb->update( $table_smgt_fees_payment, $feedata ,$fees_id);

	}

	public function mj_smgt_update_payment_status_fees_amount($data)

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		

		$feedata['payment_status'] = $data['payment_status'];

		$fees_id['fees_pay_id']=$data['fees_pay_id'];

			$result=$wpdb->update( $table_smgt_fees_payment, $feedata ,$fees_id);

	}

	public function mj_smgt_get_all_fees()

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

	

		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment Order By created_date DESC");

		return $result;

	}

	public function mj_smgt_get_all_fees_own()

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

	    $get_current_user_id=get_current_user_id();

		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment where created_by=$get_current_user_id Order By created_date DESC");

		return $result;

	}

	public function mj_smgt_get_single_fee_payment($fees_pay_id)

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees_payment where fees_pay_id = $fees_pay_id");

		return $result;

	}

	public function mj_smgt_get_single_fee_mj_smgt_payment($fees_pay_id)

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees_payment where fees_pay_id = $fees_pay_id");

		return $result;

	}

	public function mj_smgt_get_single_feetype_data($fees_id)

	{

		global $wpdb;

		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';

	

		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees where fees_id = $fees_id ");

		return $result;

	}

	public function mj_smgt_delete_feetype_data($fees_id)

	{

		school_append_audit_log(''.esc_html__('Fees Type Deleted','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);

		global $wpdb;

		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';

		$result = $wpdb->query("DELETE FROM $table_smgt_fees where fees_id= ".$fees_id);

		return $result;

	}

	public function mj_smgt_delete_feetpayment_data($fees_pay_id)

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$payment = $wpdb->get_row("SELECT * FROM $table_smgt_fees_payment where fees_pay_id=$fees_pay_id");

		if($payment)
		{
			$student = mj_smgt_get_user_name_byid($payment->student_id);

			school_append_audit_log(''.esc_html__('Fees Payment Deleted','hospital_mgt').'('.$student.')'.'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);
	
			$result = $wpdb->query("DELETE FROM $table_smgt_fees_payment where fees_pay_id= ".$fees_pay_id);
			return $result;
		}
		
		

	}

	public function mj_smgt_get_fees_payment_dashboard()

	{		

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment ORDER BY fees_pay_id  DESC  limit 3");

		return $result;

	}

	public function mj_smgt_feetype_amount_data($fees_id)

	{

		global $wpdb;

		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';

		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees where fees_id = $fees_id");

		if(!empty($result->fees_amount)){

			$fees_amount = $result->fees_amount;

		}else{

			$fees_amount = "0.00";

		}

		return $fees_amount;

	}

	// maximum 5 fees payment list 

	public function mj_smgt_get_five_fees()

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment ORDER BY fees_id DESC LIMIT 5");

		return $result;

	}

	// maximum 5 fees payment list of frontend users

	public function mj_smgt_get_five_fees_users($id)

	{

		global $wpdb;

		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';

		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment where student_id = $id ORDER BY fees_id DESC LIMIT 5");

		return $result;

	}

	public function mj_smgt_get_all_recurring_fees()
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment_recurring';
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment Order By created_date DESC");
		return $result;
	}
	public function mj_smgt_get_all_recurring_fees_active($date)
	{
		
		$recurring_enddate=date('Y-m-d', strtotime("-1 day", strtotime($date)));
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment_recurring';
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment where status='yes' && recurring_enddate='$recurring_enddate' && end_year > '$recurring_enddate'");
		return $result;
	}

	public function mj_smgt_delete_recurring_fees($recurring_id)
	{
		school_append_audit_log(''.esc_html__('Recurring Fees Deleted','hospital_mgt').'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees_payment_recurring';
		$result = $wpdb->query("DELETE FROM $table_smgt_fees where recurring_id = ".$recurring_id );
		return $result;
	}

	public function mj_smgt_get_single_recurring_fees($recurring_id)
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment_recurring';
		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees_payment where recurring_id = $recurring_id");
		return $result;
	}

	// GET RECURRING FEES DATA BY CLASS ID
	public function mj_smgt_get_recurring_fees_by_class($class_id)
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment_recurring';
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment where class_id = $class_id");
		return $result;
	}
}
?>
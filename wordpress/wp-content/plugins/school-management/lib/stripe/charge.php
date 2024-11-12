<?php 
//include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
session_start(); 
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

include_once($parse_uri[0]. 'wp-admin/includes/plugin.php' );
require_once( $parse_uri[0] . 'wp-load.php' );

if(get_option('smgt_paymaster_pack')=="yes" && is_plugin_active('school-management/school-management.php'))
{
	$amount =  $_REQUEST['amount'];
	$pay_id = $_REQUEST['pay_id'];
	$customer_id = get_current_user_id();
	$currency = get_option("smgt_currency_code");
	
}
if(get_option('gmgt_paymaster_pack')=="yes" && is_plugin_active('gym-management/gym-management.php'))
{
	$amount =  $_REQUEST['amount'];	
	$pay_id = $_REQUEST['pay_id'];
	//$customer_id = get_current_user_id();
	$customer_id = $_REQUEST['data_user'];
	/* var_dump($customer_id);
	die; */
	$currency = get_option("gmgt_currency_code");
	
 }
if(get_option('cmgt_paymaster_pack')=="yes" && is_plugin_active('church-management/church-management.php'))
{
	$amount =  $_REQUEST['amount'];	
	$pay_id = $_REQUEST['pay_id'];
	$customer_id = get_current_user_id();
	$donetion_type = $_REQUEST['donetion_type'];
	$currency = get_option("cmgt_currency_code");
}
if(get_option('amgt_paymaster_pack')=="yes" && is_plugin_active('apartment-management/apartment-management.php'))
{
	$amount =  $_REQUEST['amount'];	
	$pay_id = $_REQUEST['pay_id'];
	$customer_id = get_current_user_id();
	//$donetion_type = $_REQUEST['donetion_type'];
	$currency = get_option("apartment_currency_code");
}
if(get_option('hmgt_paymaster_pack')=="yes" && is_plugin_active('hospital-management/hospital-management.php'))
{
	$amount =  $_REQUEST['amount'];	
	$pay_id = $_REQUEST['pay_id'];
	$customer_id = get_current_user_id();
	//$donetion_type = $_REQUEST['donetion_type'];
	$currency = get_option("hmgt_currency_code");
}
$secret_key = $_REQUEST['secret_key'];
$result = get_userdata($customer_id);
$display_name = $result->display_name;
$user_login = $result->user_login;
$customer_email = $result->user_email;

try {	 
	require_once  PM_PLUGIN_DIR .'/lib/stripe/Stripe/init.php';	
	require_once  PM_PLUGIN_DIR .'/lib/stripe/Stripe/lib/Stripe.php';	

	\Stripe\Stripe::setApiKey($secret_key);

	$customer = \Stripe\Customer::create(array(
		"email" => $customer_email,
		"description" => 'create customer', // The token submitted from Checkout
		"metadata" => array( // Note: You can specify up to 20 keys, with key names up to 40 characters long and values up to 500 characters long.
		'NAME'          => $display_name,
		'EMAIL'         => $_POST['stripeEmail'],
		'ORDER DETAILS' => 'create customer',
	)
	));

	$cust_id=$customer->id;	
	// `source` is obtained with Stripe.js; see https://stripe.com/docs/payments/accept-a-payment-charges#web-create-token
	$charge=\Stripe\Charge::create([
		'amount' =>$amount*100,
		'currency' =>$currency,
		'source' =>'tok_amex', // The token submitted from Checkout
	]);
if($charge->status =="succeeded")
{ 
	if(is_plugin_active('school-management/school-management.php'))
	{
		$obj_fees_payment = new Smgt_feespayment(); 
		$data['fees_pay_id']=$pay_id;
		$data['amount']=$amount;
		$data['payment_method']="Stripe";
		$data['created_by']=$customer_id;
		$data['paid_by_date']=date('Y-m-d');		
		$result = $obj_fees_payment->add_feespayment_history($data);		
		if($result){			
			wp_redirect( home_url(). get_option('smgt_stripe_success_url'));			
		} 
		else {			
			wp_redirect( home_url(). get_option('smgt_stripe_cencal_url'));			
		}
	 }
	 //for gym plug in// 
	if(get_option('gmgt_paymaster_pack')=="yes" && is_plugin_active('gym-management/gym-management.php'))
	{
			if(isset($_REQUEST['where_payment']) && $_REQUEST['where_payment']=="front_end")
			{
					$obj_membership_payment=new MJ_gmgt_membership_payment;
					$obj_membership=new MJ_gmgt_membership;	
					$obj_member=new MJ_gmgt_member;
					
					$trasaction_id  = '';
					
					$joiningdate=date("Y-m-d");
					$membership=$obj_membership->MJ_gmgt_get_single_membership($pay_id);
					
					$validity=$membership->membership_length_id;
					
					$expiredate= date('Y-m-d', strtotime($joiningdate. ' + '.$validity.' days'));
					$membership_status = 'continue';
					$payment_data = array();
					$membershippayment=$obj_membership_payment->MJ_gmgt_checkMembershipBuyOrNot($customer_id,$joiningdate,$expiredate);
				
					if(!empty($membershippayment))
					{
						global $wpdb;
						$table_gmgt_membership_payment=$wpdb->prefix.'Gmgt_membership_payment';
						$payment_data['payment_status'] = 0;
						$whereid['mp_id']=$membershippayment->mp_id;
						$wpdb->update( $table_gmgt_membership_payment, $payment_data ,$whereid);
						$plan_id =$membershippayment->mp_id;
					}
					else
					{
						global $wpdb;
						//invoice number generate
						$table_income=$wpdb->prefix.'gmgt_income_expense';
						$result_invoice_no=$wpdb->get_results("SELECT * FROM $table_income");						
						
						if(empty($result_invoice_no))
						{							
							$invoice_no='00001';
						}
						else
						{							
							$result_no=$wpdb->get_row("SELECT invoice_no FROM $table_income where invoice_id=(SELECT max(invoice_id) FROM $table_income)");
							$last_invoice_number=$result_no->invoice_no;
							$invoice_number_length=strlen($last_invoice_number);
							
							if($invoice_number_length=='5')
							{
								$invoice_no = str_pad($last_invoice_number+1, 5, 0, STR_PAD_LEFT);
							}
							else	
							{
								$invoice_no='00001';
							}				
						}
								
						$payment_data['invoice_no']=$invoice_no;
						$payment_data['member_id'] = $customer_id;
						$payment_data['membership_id'] = $pay_id;
						$payment_data['membership_amount'] = MJ_gmgt_get_membership_price($pay_id);
						$payment_data['start_date'] = $joiningdate;
						$payment_data['end_date'] = $expiredate;
						$payment_data['membership_status'] = $membership_status;
						$payment_data['payment_status'] = 0;
						$payment_data['created_date'] = date("Y-m-d");
						$payment_data['created_by'] = $customer_id;
						$plan_id = $obj_member->MJ_gmgt_add_membership_payment_detail($payment_data);
						
						//save membership payment data into income table							
						$membership_name=MJ_gmgt_get_membership_name($pay_id);
						$entry_array[]=array('entry'=>$membership_name,'amount'=>MJ_gmgt_get_membership_price($pay_id));	
						$incomedata['entry']=json_encode($entry_array);	
						
						$incomedata['invoice_type']='income';
						$incomedata['invoice_label']=__("Fees Payment","gym_mgt");
						$incomedata['supplier_name']=$customer_id;
						$incomedata['invoice_date']=date('Y-m-d');
						$incomedata['receiver_id']=$customer_id;
						$incomedata['amount']=MJ_gmgt_get_membership_price($pay_id);
						$incomedata['total_amount']=MJ_gmgt_get_membership_price($pay_id);
						$incomedata['invoice_no']=$invoice_no;
						$incomedata['paid_amount']=$amount;
						$incomedata['payment_status']='Fully Paid';
						$result_income=$wpdb->insert( $table_income,$incomedata); 
					}
					$feedata['mp_id']=$plan_id;
					//$feedata['memebership_id']=$_POST['custom'];
					$feedata['amount']=$amount;
					$feedata['payment_method']='Stripe';		
					$feedata['trasaction_id']='';
					$feedata['created_by']=$customer_id;
					$result=$obj_membership_payment->MJ_gmgt_add_feespayment_history($feedata);
					if($result)
					{
						$u = new WP_User($customer_id);
						$u->remove_role( 'subscriber' );
						$u->add_role( 'member' );
						$gmgt_hash=delete_user_meta($customer_id, 'gmgt_hash');
						update_user_meta( $customer_id, 'membership_id', $pay_id );					
						/* wp_redirect(home_url() .'/?action=success');	 */
						if(is_user_logged_in())
						{
							wp_redirect( home_url(). get_option('gmgt_stripe_success_url'));
						}
						else
						{
							wp_redirect(home_url() .'/?action=payment_success_message');
						}	
					} 
					if($_REQUEST['frontend_class_action']=='frontend_book')
					{
					
						$obj_class=new MJ_gmgt_classschedule;
						$result=$obj_class->booking_class_shortcode_frontend($_REQUEST['class_id1'],$_REQUEST['day_id1'],$_REQUEST['startTime_1'],$_REQUEST['frontend_class_action'],'',$_REQUEST['class_date'],$pay_id,$customer_id);
						if($result)
						{	
							$page_id = get_option ( 'gmgt_class_booking_page' );	
							$referrer_ipn = array(				
								'page_id' => $page_id,
								'message'=>$result					
							);				
							$referrer_ipn = add_query_arg( $referrer_ipn, home_url() );	
							wp_redirect ($referrer_ipn);
						}
					}
					
				
			}
			else
			{
				if($_REQUEST['payment_type'] == 'Sales_Payment')
				{
                    $obj_store=new MJ_gmgt_store;
					$saledata['mp_id']=$pay_id;
					$saledata['amount']=$amount;
					$saledata['payment_method']='Stripe';	
					$saledata['trasaction_id']="";
					$saledata['created_by']=$customer_id;
					//$result = $obj_membership_payment->add_feespayment_history($feedata);
					$sales_payment_result = $obj_store->MJ_gmgt_add_sellpayment_history($saledata);
					if($sales_payment_result)
					{
					  wp_redirect( home_url(). get_option('gmgt_stripe_store_success_url'));			
				    }
				    else
				    {
					  wp_redirect( home_url(). get_option('gmgt_stripe_store_cencal_url'));						
				    }
				}
				elseif($_REQUEST['payment_type'] == 'Income_Payment')
				{
					$obj_payment=new MJ_gmgt_payment;
					$incomedata['mp_id']=$pay_id;
					$incomedata['amount']=$amount;
					$incomedata['payment_method']='Stripe';	
					$incomedata['trasaction_id']="";
					$incomedata['created_by']=$customer_id;
					$income_result = $obj_payment->MJ_gmgt_add_income_payment_history($incomedata);
					if($income_result)
					{
					  wp_redirect( home_url(). get_option('gmgt_stripe_income_success_url'));			
				    }
				    else
				    {
					  wp_redirect( home_url(). get_option('gmgt_stripe_income_cencal_url'));						
				    }
				}
				else
				{
					$obj_membership_payment = new MJ_gmgt_membership_payment();
					$feedata['mp_id']=$pay_id;
					$feedata['amount']=$amount;
					$feedata['payment_method']='Stripe';	
					$feedata['trasaction_id']="";
					$feedata['created_by']=$customer_id;
					$feespayment_result = $obj_membership_payment->MJ_gmgt_add_feespayment_history($feedata);
					if($feespayment_result)
					{
					  wp_redirect( home_url(). get_option('gmgt_stripe_success_url'));			
				    }
				    else
				    {
					  wp_redirect( home_url(). get_option('gmgt_stripe_cencal_url'));						
				    }
					
				}
				
			}
	}
	if(get_option('cmgt_paymaster_pack')=="yes" && is_plugin_active('church-management/church-management.php'))
	{
		$transaction = new Cmgttransaction(); 
		//$data['fees_pay_id']=$pay_id;
		$data['member_id']=$customer_id;
		$data['amount']=$amount;
		$data['donetion_type']=$donetion_type;
		$data['pay_method']="Stripe";
		$data['transaction_id']=$charge->balance_transaction;
		$data['created_date']=date("Y-m-d");
		$data['created_by']=$customer_id;
		$data['transaction_date']=date('Y-m-d');
		$data['description']=" ";

		
		$result = $transaction->MJ_cmgt_add_transaction($data);
		
          	
		if($result){	
           		
			wp_redirect( home_url(). get_option('cmgt_stripe_success_url'));			
		} 
		else {			
			wp_redirect( home_url(). get_option('cmgt_stripe_cencal_url'));			
		} 
	}
	if(get_option('amgt_paymaster_pack')=="yes" && is_plugin_active('apartment-management/apartment-management.php'))
	{
		$paymentdata['invoice_id']=$_REQUEST['pay_id'];
		$paymentdata['amount']=$amount;
		$paymentdata['payment_method']='Stripe';
		$paymentdata['member_id']=$customer_id;
		$paymentdata['created_by']=$customer_id;
		$obj_account =new Amgt_Accounts;
		$result = $obj_account->amgt_add_own_payment($paymentdata);
			
		if($result){	
			wp_redirect( home_url(). get_option('amgt_stripe_success_url'));			
		} 
		else {			
			wp_redirect( home_url(). get_option('amgt_stripe_cencal_url'));			
		} 
	}
	if(get_option('hmgt_paymaster_pack')=="yes" && is_plugin_active('hospital-management/hospital-management.php'))
	{
		$obj_invoice= new MJhmgt_invoice();
		$paymentdata['invoice_id']=$_REQUEST['pay_id'];
		$paymentdata['invoice_type']="income";
		$paymentdata['party_name']=get_current_user_id();
		$paymentdata['invoice_date']=date("Y-m-d");
		$paymentdata['income_entry']=array($_REQUEST['payment_type']);
		$paymentdata['income_amount']=array($amount);
		$paymentdata['payment_method']='Stripe';	
		$paymentdata['payment_description']='Stripe';	
		$paymentdata['transaction_id']=$charge->balance_transaction;
		
		
		$result = $obj_invoice -> MJhmgt_add_income($paymentdata);
			
		if($result)
		{	
			wp_redirect( home_url(). get_option('hmgt_stripe_success_url'));			
		} 
		else {			
			wp_redirect( home_url(). get_option('hmgt_stripe_cencal_url'));			
		} 
	}
}
 }catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];

  print('Status is:' . $e->getHttpStatus() . "\n");
  print('Type is:' . $err['type'] . "\n");
  print('Code is:' . $err['code'] . "\n");
  // param is '' in this case
  print('Param is:' . $err['param'] . "\n");
  print('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\RateLimit $e) {
	var_dump($e);
  // Too many requests made to the API too quickly
} catch (\Stripe\Error\InvalidRequest $e) {
	var_dump($e);
  // Invalid parameters were supplied to Stripe's API
} catch (\Stripe\Error\Authentication $e) {
	var_dump($e);
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\Stripe\Error\ApiConnection $e) {
	var_dump($e);
  // Network communication with Stripe failed
} catch (\Stripe\Error\Base $e) {
	var_dump($e);
  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
	var_dump($e);
  // Something else happened, completely unrelated to Stripe
}

?>
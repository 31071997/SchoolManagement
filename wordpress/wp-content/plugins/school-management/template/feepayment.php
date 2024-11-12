<?php

//-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();

$role_name=mj_smgt_get_user_role(get_current_user_id());

$access=mj_smgt_page_access_rolewise_and_accessright();

$tablename="smgt_payment";

$obj_invoice= new Smgtinvoice();

$obj_fees= new Smgt_fees();

$obj_feespayment= new mj_smgt_feespayment();

if($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff')
{

	$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'feeslist';

}
else
{

	$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'feepaymentlist';

}

//--------------- ACCESS WISE ROLE -----------//

$user_access=mj_smgt_get_userrole_wise_access_right_array();

if (isset ( $_REQUEST ['page'] ))

{

	if($user_access['view']=='0')

	{

		mj_smgt_access_right_page_not_access_message();

		die;

	}

}

//------------- SAVE FEESTYPE PAYMENT METHOD --------------//

if(isset($_POST['add_feetype_payment']))

{

	//POP up data save in payment history

	if($_POST['payment_method'] == 'Paypal')

	{

		require_once SMS_PLUGIN_DIR. '/lib/paypal/paypal_process.php';

	}

	elseif($_POST['payment_method'] == 'Stripe')

	{

		require_once PM_PLUGIN_DIR. '/lib/stripe/index.php';

	}

	elseif($_POST['payment_method'] == 'Skrill')

	{

		require_once PM_PLUGIN_DIR. '/lib/skrill/skrill.php';

	}

	elseif($_POST['payment_method'] == 'Instamojo')

	{

		require_once PM_PLUGIN_DIR. '/lib/instamojo/instamojo.php';

	}

	elseif($_POST['payment_method'] == 'PayUMony')

	{

		require_once PM_PLUGIN_DIR. '/lib/OpenPayU/index.php';

	}

	elseif($_REQUEST['payment_method'] == '2CheckOut')

	{

		require_once PM_PLUGIN_DIR. '/lib/2checkout/index.php';

	}

	elseif($_POST['payment_method'] == 'iDeal')

	{

		require_once PM_PLUGIN_DIR. '/lib/ideal/ideal.php';

	}

	elseif($_POST['payment_method'] == 'Paystack')

	{

		require_once PM_PLUGIN_DIR. '/lib/paystack/paystack.php';

	}

	elseif($_POST['payment_method'] == 'paytm')

	{

		require_once PM_PLUGIN_DIR. '/lib/PaytmKit/index.php';

	}

	elseif($_POST['payment_method'] == 'razorpay')

	{

		require_once PM_PLUGIN_DIR. '/lib/razorpay/index.php';

	}

	elseif($_POST['payment_method'] == 'Payfast')

	{

		require_once PM_PLUGIN_DIR. '/lib/payfast/payfast_process.php';

	}

	else

	{

		$result=$obj_feespayment->mj_smgt_add_feespayment_history($_POST);

		if($result)

		{

			wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&message=1');

		}

	}

}

if(isset($_REQUEST['action'])&& $_REQUEST['action']=='success')

{

	?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php 	esc_attr_e('Payment Added successfully','school-mgt'); ?>

	</div>

	<?php

}

//Paytm Success//



//----------------- PAYSTACK COMPLATE --------------//
$reference='';

$reference = isset($_GET['reference']) ? $_GET['reference'] : '';

if($reference)

{

      $paystack_secret_key=get_option('paystack_secret_key');

	  $curl = curl_init();

	  curl_setopt_array($curl, array(

	  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),

	  CURLOPT_RETURNTRANSFER => true,

	  CURLOPT_HTTPHEADER => [

		"accept: application/json",

		"authorization: Bearer $paystack_secret_key",

		"cache-control: no-cache"

	  ],

	));

	$response = curl_exec($curl);

	$err = curl_error($curl);

	if($err)

	{

		// there was an error contacting the Paystack API

	  die('Curl returned error: ' . $err);

	}

	$tranx = json_decode($response);

	if(!$tranx->status)

	{

	  // there was an error from the API

	  die('API returned error: ' . $tranx->message);

	}

	if('success' == $tranx->data->status)

	{

		$trasaction_id  = $tranx->data->reference;

		$feedata['fees_pay_id']=$tranx->data->metadata->custom_fields->fees_pay_id;

		$feedata['amount']=$tranx->data->amount / 100;

		$feedata['payment_method']='Paystack';

		$feedata['trasaction_id']=$trasaction_id ;

		$PaymentSucces = $obj_feespayment->mj_smgt_add_feespayment_history($feedata);

		if($PaymentSucces)

		{

			wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&action=success');

		}

	}

}


// payment history payfast

if(isset($_REQUEST['payment']) && $_REQUEST['payment'] == 'paystack_success')

{

    $trasaction_id  = '';
	$feedata['fees_pay_id']=$_REQUEST['pay_id'];
	$feedata['amount']=$_POST['amt'];
	$feedata['payment_method']='Payfast';
	$feedata['trasaction_id']=$trasaction_id;
	$PaymentSucces = $obj_feespayment->mj_smgt_add_feespayment_history($feedata);
	if($PaymentSucces)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&action=success');
		exit;
	}

}



//Payment History entry for skrill//

if(isset($_REQUEST['pay_id']) && isset($_REQUEST['amt']))

{

	$obj_fees_payment = new mj_smgt_feespayment();

	$feedata['fees_pay_id']=$_REQUEST['pay_id'];

	$feedata['amount']=$_REQUEST['amt'];

	$feedata['payment_method']="Payfast";

	$feedata['created_by']=get_current_user_id();

	$feedata['paid_by_date']=date('Y-m-d');

	$result = $obj_fees_payment->mj_smgt_add_feespayment_history($feedata);
	if($result){

		wp_redirect(home_url().'?dashboard=user&page=feepayment&tab=feepaymentlist&action=success');

	}

}

//Payment History entry for instamojo//

if(isset($_REQUEST['payment_id']) && isset($_REQUEST['payment_request_id']))

{

	$obj_fees_payment = new mj_smgt_feespayment();

		$feedata['fees_pay_id']=$_REQUEST['pay_id'];

		$feedata['amount']=$_REQUEST['amount'];

		$feedata['payment_method']="Instamojo";

		$feedata['trasaction_id']=$_REQUEST['payment_id'];

		$feedata['created_by']=get_current_user_id();

		$feedata['paid_by_date']=date('Y-m-d');

		$result = $obj_fees_payment->mj_smgt_add_feespayment_history($feedata);

		if($result)

		{

		wp_redirect(home_url().'?dashboard=user&page=feepayment&tab=feepaymentlist&action=success');

		exit();

	}

}

//----------------- PAYMENT CENCAL --------------//

if(isset($_REQUEST['action'])&& $_REQUEST['action']=='cancel')

{ ?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Payment Cancel','school-mgt');	?>

	</div>

<?php

}

//----------------- SAVE FEES TYPE -------------------//

if(isset($_POST['save_feetype']))

{

    $nonce = $_POST['_wpnonce'];

	if ( wp_verify_nonce( $nonce, 'save_fees_type_front_nonce' ) )

	{

		if($_REQUEST['action']=='edit')

		{

			$result=$obj_fees->mj_smgt_add_fees($_POST);

			if($result)

			{

				wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feeslist&message=5');

			}

		}

		else

		{

			if(!$obj_fees->mj_smgt_is_duplicat_fees($_POST['fees_title_id'],$_POST['class_id']))

			{

				$result=$obj_fees->mj_smgt_add_fees($_POST);

				if($result)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feeslist&message=4');

				}

			}

			else

			{

				wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feeslist&message=6');

			}

		}

    }

}

/* Update Recurring Invoice Data */
if(isset($_POST['save_recurring_feetype_payment']))
{
    $nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_payment_fees_admin_nonce' ) )
    {

		$start_date = date('Y-m-d', strtotime($_POST['start_year']));
		$end_date = date('Y-m-d', strtotime($_POST['end_year']));
		if($start_date <= $end_date )
		{
			if($_REQUEST['action']=='edit')
			{
				$result=$obj_feespayment->mj_smgt_add_recurring_feespayment($_POST);
				if($result)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=recurring_feespaymentlist&message=recurring_feetype_edit');

				}

			}
		}
		else
		{
			?>
			<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
				<p><?php echo esc_attr__('End Date should be greater than Start Date.','school-mgt');?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div>
			<?php
		}
    }
}
//------------------ SAVE PAYMENT ---------------//

if(isset($_POST['save_feetype_payment']))

{

	$nonce = $_POST['_wpnonce'];

	if ( wp_verify_nonce( $nonce, 'save_payment_fees_front_nonce' ) )

    {

		if(isset($_REQUEST['smgt_enable_feesalert_mail']))

			update_option( 'smgt_enable_feesalert_mail', 1 );

		else

			update_option( 'smgt_enable_feesalert_mail', 0 );
		$start_date = date('Y-m-d', strtotime($_POST['start_year']));
		$end_date = date('Y-m-d', strtotime($_POST['end_year']));
		if($start_date <= $end_date )
		{

			if($_REQUEST['action']=='edit')

			{



				$result=$obj_feespayment->mj_smgt_add_feespayment($_POST);

				if($result)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&message=2');

				}

			}

			else

			{

				$result=$obj_feespayment->mj_smgt_add_feespayment($_POST);

				if($result)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&message=1');

				}

			}
		}
		else{
			?>
			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

				</button>

				<?php esc_attr_e('End Date should be greater than Start Date.','school-mgt'); ?>

			</div>
			<?php
		}

    }

}
if(isset($_REQUEST['delete_selected_recurring_feelist']))
{
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
    {
	   $result=$obj_feespayment->mj_smgt_delete_recurring_fees($id);
	}
	if($result)
	{
		?>
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php esc_html_e('Fee Deleted Successfully.','school-mgt'); ?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
		<?php

	}
}
//----------------- DELETE FEES TYPE -----------------//

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{

	if(isset($_REQUEST['fees_id']))

	{

		$result=$obj_fees->mj_smgt_delete_feetype_data($_REQUEST['fees_id']);

		if($result)

		{

			wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=feeslist&message=7');

		}

	}

	if(isset($_REQUEST['fees_pay_id']))

	{

		$result=$obj_feespayment->mj_smgt_delete_feetpayment_data($_REQUEST['fees_pay_id']);

		if($result)

		{

			wp_redirect (  home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&message=3');

		}

	}
	if(isset($_REQUEST['recurring_fees_id']))
	{
		$result=$obj_feespayment->mj_smgt_delete_recurring_fees($_REQUEST['recurring_fees_id']);
		if($result)
		{

			wp_redirect ( home_url() . '?dashboard=user&page=feepayment&tab=recurring_feespaymentlist&message=fee_del');
		}

	}
}

//----------------- MULTIPLE DELETE FEES TYPE ----------------------//

if(isset($_REQUEST['delete_selected_feetype']))

{

	if(!empty($_REQUEST['id']))

	{

		foreach($_REQUEST['id'] as $id)

		{

			$result=$obj_feespayment->mj_smgt_delete_feetype_data($id);

			wp_redirect (  home_url() . '?dashboard=user&page=feepayment&tab=feeslist&message=3');

		}

	}

	if($result)

	{

		?>

		<div id="message" class="updated below-h2">

			<p><?php esc_attr_e('Fees Type Deleted Successfully.','school-mgt');?></p>

		</div>

		<?php

	}

}

//--------------------- MULTIPLE FEES PAYMENT DELETE --------------------//

if(isset($_REQUEST['delete_selected_feelist']))

{

	if(!empty($_REQUEST['id']))

	{

		foreach($_REQUEST['id'] as $id)

		{

			$result=$obj_feespayment->mj_smgt_delete_feetpayment_data($id);

			wp_redirect (  home_url() . '?dashboard=user&page=feepayment&tab=feepaymentlist&message=3');

		}

	}

	if($result)

	{

		?>

		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">

			<p><?php esc_html_e('Fee Deleted Successfully.','school-mgt'); ?></p>

			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>

		</div>

		<?php

	}

}



?>

<script type="text/javascript">

jQuery(document).ready(function($)

{

	"use strict";

	$('#invoice_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	$('#income_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	$('#expense_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});



     jQuery('#paymentt_list').DataTable({

		"initComplete": function(settings, json) {
			$(".print-button").css({"margin-top": "-5%"});
		},
		//stateSave: true,
		//"order": [[ 2, "desc" ]],

		"dom": 'lifrtp',

		"aoColumns":[

			<?php

			if($role_name == "supportstaff")

			{

				?>

				{"bSortable": false},

				<?php

			}

			?>

			{"bSortable": false},

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},
			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},

	         {"bSortable": false}],

			 language:<?php echo mj_smgt_datatable_multi_language();?>

		});



    $('#expense_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	$('#expense_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	$("#fees_data").multiselect({

		nonSelectedText: '<?php esc_attr_e( 'Select Fees Type', 'school-mgt' ) ;?>',

		includeSelectAllOption: true,

		selectAllText: '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',

		templates: {

				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',

		}

	});

	$("body").on("change", "#end_year", function (){

		var end_value = parseInt($('#end_year option:selected').val());

		var start_value = parseInt($('#start_year option:selected').attr("id"));

		if(start_value > end_value )

		{

			$("#end_year option[value='']").attr('selected','selected');

			alert(language_translate2.lower_starting_year_alert);

			return false;

		}

	});

	var table =  jQuery('#feetype_list').DataTable({

		"initComplete": function(settings, json) {
			$(".print-button").css({"margin-top": "-5%"});
		},

		"order": [[ 2, "asc" ]],

		"dom": 'lifrtp',

		"aoColumns":[

			<?php

			if($role_name == "supportstaff")

			{

				?>

				{"bSortable": false},

				<?php

			}

			?>

			{"bSortable": false},

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},

			<?php

			if($user_access['edit']=='1' || $user_access['delete']=='1'){

			?>

			{"bSortable": false}

			<?php } ?>],

		language:<?php echo mj_smgt_datatable_multi_language();?>

	});

	$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

	$('.select_all').on('click', function(e)
		{
			if($(this).is(':checked',true))
			{
				$(".smgt_sub_chk").prop('checked', true);
			}
			else
			{
				$(".smgt_sub_chk").prop('checked',false);
			}
		});
		$('.smgt_sub_chk').on('change',function()
		{
			if(false == $(this).prop("checked"))
			{
				$(".select_all").prop('checked', false);
			}
			if ($('.smgt_sub_chk:checked').length == $('.smgt_sub_chk').length )
			{
				$(".select_all").prop('checked', true);
			}
		});

	//------------- multiple delete js -----------//

	$(".delete_selected").on('click', function()

	{

		if ($('.select-checkbox:checked').length == 0 )

		{

			alert("<?php esc_html_e('Please select atleast one record','school-mgt');?>");

			return false;

		}

		else

		{

			var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?','school-mgt');?>");

			if(alert_msg == false)

			{

				return false;

			}

			else

			{

				return true;

			}

		}

	});

	jQuery('#checkbox-select-all').on('click', function(){

		var rows = table.rows({ 'search': 'applied' }).nodes();

		jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);

	});

	var blank_expense_entry = $('#expense_entry').html();

});

//////////////

var blank_income_entry ='';

$(document).ready(function() {

	blank_expense_entry = $('#expense_entry').html();

});



function add_entry()

{

	$("#expense_entry").append(blank_expense_entry);

}



function deleteParentElement(n){

	alert(language_translate2.do_delete_record);

	n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);

}

</script>

<!-- POP up code -->

<div class="popup-bg">

    <div class="overlay-content">

		<div class="modal-content">

			<div class="invoice_data"></div>

			<div class="category_list"></div>

		</div>

    </div>

</div>

<!-- End POP-UP Code -->



<!-- End POP-UP Code -->

<div class="panel-body panel-white frontend_list_margin_40px_res">

<?php

//---------------- MESSAGE -----------//

$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

switch($message)

{

	case '1':

		$message_string = esc_attr__('Fees Payment Added Successfully.','school-mgt');

		break;

	case '2':

		$message_string = esc_attr__('Fees Payment Updated Successfully.','school-mgt');

		break;

	case '3':

		$message_string = esc_attr__('Fees Payment Deleted Successfully.','school-mgt');

		break;

	case '4':

		$message_string = esc_attr__('Fees Type Added Successfully.','school-mgt');

		break;

	case '5':

		$message_string = esc_attr__('Fees Type Updated Successfully.','school-mgt');

		break;

	case '6':

		$message_string = esc_attr__('Duplicate Fee.','school-mgt');

		break;

	case '7':

		$message_string = esc_attr__('Fees Type Deleted Successfully.','school-mgt');

		break;
	case 'recurring_feetype_edit':

		$message_string = esc_attr__('Recurring Invoice Updated Successfully.','school-mgt');

		break;
	case 'fee_del':

		$message_string = esc_attr__('Fees Deleted Successfully.','school-mgt');

		break;

}



if($message)

{

	?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php echo $message_string;?>

	</div>

	<?php

} ?>

<div class="panel-body panel-white">

	<?php

	if($active_tab != 'view_fesspayment')

	{

		$page_action='';

		if(!empty($_REQUEST['action']))

		{

			$page_action = $_REQUEST['action'];

		}

		?>

		<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">

			<?php

			if($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff')

			{

				?>

				<li class="<?php if($active_tab=='feeslist'){?>active<?php }?>">

					<a href="?dashboard=user&page=feepayment&tab=feeslist" class="padding_left_0 tab <?php echo $active_tab == 'feeslist' ? 'active' : ''; ?>">

					<?php esc_html_e('Fees Type List', 'school-mgt'); ?></a>

				</li>

				<?php

			}

			if($active_tab=='addfeetype' && $page_action == 'edit')

			{

				?>

				<li class="<?php if($active_tab=='addfeetype'){?>active<?php }?>">

					<a href="?dashboard=user&page=feepayment&tab=addfeetype&action=edit&fees_id=<?php echo $_REQUEST['fees_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addfeetype' ? 'active' : ''; ?>">

					<?php esc_html_e('Edit Fees Type', 'school-mgt'); ?></a>

				</li>

				<?php

			}

			elseif($active_tab=='addfeetype')

			{

				if($user_access['add']=='1')

				{

					?>

					<li class="<?php if($active_tab=='addfeetype'){?>active<?php }?>">

						<a href="?dashboard=user&page=feepayment&tab=addfeetype" class="padding_left_0 tab <?php echo $active_tab == 'addfeetype' ? 'active' : ''; ?>">

						<?php esc_html_e('Add Fees Type', 'school-mgt'); ?></a>

					</li>

					<?php

				}

			}

			?>

			<li class="<?php if($active_tab=='feepaymentlist'){?>active<?php }?>">

				<a href="?dashboard=user&page=feepayment&tab=feepaymentlist" class="padding_left_0 tab <?php echo $active_tab == 'feepaymentlist' ? 'active' : ''; ?>">

				<?php esc_html_e('Fees Payment', 'school-mgt'); ?></a>

			</li>

			<?php

			if($active_tab=='addpaymentfee' && $page_action == 'edit')

			{

				?>

				<li class="<?php if($active_tab=='addpaymentfee'){?>active<?php }?>">

					<a href="?dashboard=user&page=feepayment&tab=addpaymentfee&action=edit&fees_pay_id=<?php echo $_REQUEST['fees_pay_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addpaymentfee' ? 'active' : ''; ?>">

					<?php esc_html_e('Edit Fees Payment', 'school-mgt'); ?></a>

				</li>

				<?php

			}

			elseif($active_tab=='addpaymentfee')

			{

				if($user_access['add']=='1')

				{

					?>

					<li class="<?php if($active_tab=='addpaymentfee'){?>active<?php }?>">

						<a href="?dashboard=user&page=feepayment&tab=addpaymentfee" class="padding_left_0 tab <?php echo $active_tab == 'addpaymentfee' ? 'active' : ''; ?>">

						<?php esc_html_e('Add Fees Payment', 'school-mgt'); ?></a>

					</li>

					<?php

				}

			}
			if($role_name == "teacher" || $role_name == "supportstaff")
			{
				$recurring_option=get_option('smgt_enable_recurring_invoices');
				if($recurring_option == 'yes')
				{
				?>
				<li class="<?php if($active_tab=='recurring_feespaymentlist'){?>active<?php }?>">
					<a href="?dashboard=user&page=feepayment&tab=recurring_feespaymentlist" class="padding_left_0 tab <?php echo $active_tab == 'recurring_feespaymentlist' ? 'active' : ''; ?>">
					<?php esc_html_e('Recurring Fees Payment List', 'school-mgt'); ?></a>
				</li>
			<?php
			}
			if($active_tab=='addrecurringpayment' && $action == 'edit')
			{
			?>
			<li class="<?php if($active_tab=='addrecurringpayment'){?>active<?php }?>">

					<a href="?dashboard=user&page=feepayment&tab=addrecurringpayment" class="padding_left_0 tab <?php echo $active_tab == 'addrecurringpayment' ? 'active' : ''; ?>">

					<?php esc_html_e('Edit Recurring Fees Payment', 'school-mgt'); ?></a>

			</li>
			<?php
			}
			} ?>
		</ul>

		<?php

	}

	?>

	<div class="">

		<?php

		if($active_tab == 'feeslist')

		{

			$user_id=get_current_user_id();

			//------- EXAM DATA FOR STUDENT ---------//

			if($school_obj->role == 'student')

			{

				$own_data=$user_access['own_data'];

				if($own_data == '1')

				{

					$retrieve_class = $obj_fees->mj_smgt_get_own_fees($user_id);

				}

				else

				{

					$retrieve_class = $obj_fees->mj_smgt_get_all_fees();

				}

			}

			//------- EXAM DATA FOR TEACHER ---------//

			elseif($school_obj->role == 'teacher')

			{

				$own_data=$user_access['own_data'];

				if($own_data == '1')

				{

					$retrieve_class = $obj_fees->mj_smgt_get_own_fees($user_id);

				}

				else

				{

					$retrieve_class = $obj_fees->mj_smgt_get_all_fees();

				}

			}

			//------- EXAM DATA FOR PARENT ---------//

			elseif($school_obj->role == 'parent')

			{

				$own_data=$user_access['own_data'];

				if($own_data == '1')

				{

					$retrieve_class = $obj_fees->mj_smgt_get_own_fees($user_id);

				}

				else

				{

					$retrieve_class = $obj_fees->mj_smgt_get_all_fees();

				}

			}

			//------- EXAM DATA FOR SUPPORT STAFF ---------//

			else

			{

				$own_data=$user_access['own_data'];

				if($own_data == '1')

				{

					$retrieve_class = $obj_fees->mj_smgt_get_own_fees($user_id);

				}

				else

				{

					$retrieve_class = $obj_fees->mj_smgt_get_all_fees();

				}

			}

			if(!empty($retrieve_class))

			{

				?>

				<div class="panel-body"><!---------------- PENAL BODY ----------------->

					<div class="table-responsive"><!--------------- TABLE RESPONSIVE -------------->

						<!-------------- FEESTYPE LIST FORM -------------->

						<form id="frm-example" name="frm-example" method="post">

							<table id="feetype_list" class="display admin_feestype_datatable" cellspacing="0" width="100%">

								<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

									<tr>

										<?php

										if($role_name == "supportstaff")

										{

											?>

											<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>

											<?php

										}

										?>

										<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

										<th><?php esc_attr_e('Fees Title','school-mgt');?></th>

										<th><?php esc_attr_e('Class Name','school-mgt');?> </th>

										<th><?php esc_attr_e('Section Name','school-mgt');?> </th>

										<th><?php esc_attr_e('Fees Amount','school-mgt');?></th>

										<th><?php esc_attr_e('Description','school-mgt');?></th>

										<?php if($user_access['edit']=='1' || $user_access['delete']=='1'){

												?>

										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

										<?php } ?>

									</tr>

								</thead>

								<tbody>

									<?php



									$i=0;

									foreach ($retrieve_class as $retrieved_data)

									{

										if($i == 10)

										{

											$i=0;

										}

										if($i == 0)

										{

											$color_class='smgt_class_color0';

										}

										elseif($i == 1)

										{

											$color_class='smgt_class_color1';

										}

										elseif($i == 2)

										{

											$color_class='smgt_class_color2';

										}

										elseif($i == 3)

										{

											$color_class='smgt_class_color3';

										}

										elseif($i == 4)

										{

											$color_class='smgt_class_color4';

										}

										elseif($i == 5)

										{

											$color_class='smgt_class_color5';

										}

										elseif($i == 6)

										{

											$color_class='smgt_class_color6';

										}

										elseif($i == 7)

										{

											$color_class='smgt_class_color7';

										}

										elseif($i == 8)

										{

											$color_class='smgt_class_color8';

										}

										elseif($i == 9)

										{

											$color_class='smgt_class_color9';

										}

										?>

										<tr>

											<?php

											if($role_name == "supportstaff")

											{

												?>

												<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->fees_id;?>"></td>

												<?php

											}

											?>



											<td class="user_image width_50px profile_image_prescription padding_left_0">

												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">

												</p>

											</td>

											<td><?php echo get_the_title($retrieved_data->fees_title_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Fees Title','school-mgt');?>"></i></td>

											<td><?php if(!empty($retrieved_data->class_id)){ if($retrieved_data->class_id == "all_class"){ esc_attr_e('All Class','school-mgt'); }else{ echo mj_smgt_get_class_name($retrieved_data->class_id);} }else{ echo "N/A"; }?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
											<td><?php if($retrieved_data->section_id!=0){ echo mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Section Name','school-mgt');?>"></i></td>

											<td><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->fees_amount,2,'.','')); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Fees Amount','school-mgt');?>"></i></td>

											<?php
													$comment =$retrieved_data->description;
													$comment = ltrim($comment, ' ');
													$description = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
													?>
													<td>
														<?php
														if(!empty($comment))
														{
															echo $description;
														}else{
															echo "N/A";
														}

														?>
														<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($comment)){echo $comment;}else{esc_html_e('Description','school-mgt');}?>"></i>
													</td>

											<?php if($user_access['edit']=='1' || $user_access['delete']=='1'){

												?>

											<td class="action">

												<div class="smgt-user-dropdown">

													<ul class="" style="margin-bottom: 0px !important;">

														<li class="">

															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

															</a>

															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">



																<?php

																if($user_access['edit']=='1')

																{

																	?>

																	<li class="float_left_width_100 border_bottom_menu">

																		<a href="?dashboard=user&page=feepayment&tab=addfeetype&action=edit&fees_id=<?php echo $retrieved_data->fees_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																	</li>

																	<?php

																}

																if($user_access['delete']=='1')

																{

																	?>

																	<li class="float_left_width_100 ">

																		<a href="?dashboard=user&page=feepayment&tab=feeslist&action=delete&fees_id=<?php echo $retrieved_data->fees_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

																		<i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

																	</li>

																	<?php

																}

																?>

															</ul>

														</li>

													</ul>

												</div>

											</td>

											<?php } ?>

										</tr>

										<?php

										$i++;

									}

									?>

								</tbody>

							</table>

							<?php

							if($role_name == "supportstaff")

							{

								?>

								<div class="print-button pull-left">

									<button class="btn-sms-color button_reload">

										<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">

										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

									</button>

									<?php

									if($user_access['delete']=='1')

									{

										?>

										<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_feetype" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

										<?php

									}

									?>

								</div>

								<?php

							}

							?>

						</form><!-------------- FEESTYPE LIST FORM -------------->

					</div><!--------------- TABLE RESPONSIVE -------------->

				</div><!---------------- PENAL BODY ----------------->

				<?php

			}

			else

			{

				if($user_access['add']=='1')

				{

					?>

					<div class="no_data_list_div no_data_img_mt_30px">

						<a href="<?php echo home_url().'?dashboard=user&page=feepayment&tab=addfeetype';?>">

							<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >

						</a>

						<div class="col-md-12 dashboard_btn margin_top_20px">

							<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>

						</div>

					</div>

					<?php

				}

				else

				{

					?>

					<div class="calendar-event-new">

						<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

					</div>

					<?php

				}



			}

		}

		if($active_tab == 'addfeetype')

		{

			$fees_id=0;

			if(isset($_REQUEST['fees_id']))

				$fees_id=$_REQUEST['fees_id'];

			$edit=0;

			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

			{

				$edit=1;

				$result = $obj_fees->mj_smgt_get_single_feetype_data($fees_id);

			}

			?>

			<div class="panel-body"><!---------------- PENAL BODY ---------------->

				<!------------------- ADD FEES TYPE FORM --------------->

				<form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">

					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

					<input type="hidden" name="action" value="<?php echo $action;?>">

					<input type="hidden" name="fees_id" value="<?php echo $fees_id;?>">

					<input type="hidden" name="invoice_type" value="expense">

					<div class="header">

						<h3 class="first_hed"><?php esc_html_e('Fees Type Information','school-mgt');?></h3>

					</div>

					<div class="form-body user_form">

						<div class="row">

							<div class="col-md-4 input">

								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Fee Type','school-mgt');?><span class="require-field">*</span></label>

								<select class="line_height_30px form-control validate[required] smgt_feetype max_width_100" name="fees_title_id" id="category_data">

									<option value=""><?php esc_attr_e('Select Fee Type','school-mgt');?></option>

									<?php

									$activity_category=mj_smgt_get_all_category('smgt_feetype');

									if(!empty($activity_category))

									{

										if($edit)

										{

											$fees_val=$result->fees_title_id;

										}

										else

										{

											$fees_val='';

										}



										foreach ($activity_category as $retrive_data)

										{

										?>

											<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$fees_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>

										<?php }

									}

									?>

								</select>

							</div>

							<div class="col-sm-2 padding_bottom_15px_res rtl_margin_top_15px">

								<button id="addremove_cat" class="btn btn-info add_btn" model="smgt_feetype"><?php esc_attr_e('Add','school-mgt');?></button>

							</div>

							<div class="col-md-6 input error_msg_left_margin">

								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>

								<?php $classval = 0;

								if($edit)

								$classval = $result->class_id;?>

								<select name="class_id" class="line_height_30px form-control validate[required] max_width_100" id="class_list">

									<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
									<option value="all_class" <?php selected($classval, 'all_class');  ?>><?php esc_attr_e('All Class','school-mgt');?></option>
									<?php

										foreach(mj_smgt_get_allclass() as $classdata)

										{

										?>

										<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>

									<?php }?>

								</select>

							</div>

							<?php wp_nonce_field( 'save_fees_type_front_nonce' ); ?>

							<div class="col-md-6 input">

								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>

								<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>

								<select name="class_section" class="line_height_30px form-control max_width_100" id="class_section">

									<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>

									<?php

									if($edit){

										foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)

										{  ?>

										<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

									<?php }

									}?>

								</select>

							</div>

							<div class="col-md-6 error_msg_left_margin">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="fees_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php if($edit){ echo $result->fees_amount;}elseif(isset($_POST['fees_amount'])) echo $_POST['fees_amount'];?>" name="fees_amount">

										<label for="userinput1" class=""><?php esc_html_e('Fees Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="required">*</span></label>

									</div>

								</div>

							</div>

							<div class="col-md-6 note_text_notice">

								<div class="form-group input">

									<div class="col-md-12 note_border margin_bottom_15px_res">

										<div class="form-field">

											<textarea name="description" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>

											<span class="txt-title-label"></span>

											<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="form-body user_form">

						<div class="row">

							<div class="col-sm-6">

								<input type="submit" value="<?php if($edit){ esc_attr_e('Save Fee Type','school-mgt'); }else{ esc_attr_e('Create Fee Type','school-mgt');}?>" name="save_feetype" class="btn btn-success save_btn"/>

							</div>

						</div>

					</div>

				</form>

			</div>

			<?php

		}

		if($active_tab == 'feepaymentlist')

		{

			$user_id=get_current_user_id();

			//------- Payment DATA FOR STUDENT ---------//

			if($school_obj->role == 'student')

			{

				$data=$school_obj->feepayment;

			}

			//------- Payment DATA FOR TEACHER ---------//

			elseif($school_obj->role == 'teacher')

			{

				$own_data=$user_access['own_data'];

				if($own_data == '1')

				{

					global $wpdb;

					$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);

					$table_name = $wpdb->prefix .'smgt_fees_payment';

					$data =$wpdb->get_results("SELECT * FROM $table_name WHERE class_id in (".implode(',', $class_id).")");

				}

				else

				{

					$data=$school_obj->feepayment;

				}

			}

			//------- Payment DATA FOR PARENT ---------//

			elseif($school_obj->role == 'parent')

			{

				$data=$school_obj->feepayment;

			}

			elseif($school_obj->role == 'supportstaff')

			{

				$own_data=$user_access['own_data'];

				if($own_data == '1')

				{

				$data=$obj_feespayment->mj_smgt_get_all_fees_own();

				}

				else

				{

					$data=$obj_feespayment->mj_smgt_get_all_fees();

				}

			}

			//------- Payment DATA FOR SUPPORT STAFF ---------//

			else

			{

				$data=$school_obj->feepayment;

			}

			if(!empty($data))

			{



				?>

				<div class="panel-body"><!------------- PENAL BODY ------------------>

					<div class="table-responsive"><!------------- TABLE RESPOSNIVE ------------------>

						<!------------- FEES PAYMENT LIST FORM ----------------->

						<form id="frm-example" name="frm-example" method="post">

							<table id="paymentt_list" class="display dataTable feespayment_datatable" cellspacing="0" width="100%">

								<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

									<tr>

										<?php

										if($role_name == "supportstaff")
										{
											?>

											<th class="padding_0"><input type="checkbox" class="smgt_sub_chk select_all" id="select_all"></th>

											<?php
										}

										?>

										<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

										<th><?php esc_attr_e('Fees Title','school-mgt');?></th>

										<th><?php esc_attr_e('Student Name','school-mgt');?></th>

										<th><?php esc_attr_e('Class Name','school-mgt');?> </th>

										<th><?php esc_attr_e('Payment Status','school-mgt'); ?></th>

										<th><?php esc_attr_e('Total Amount','school-mgt');?></th>

										<th><?php esc_attr_e('Paid Amount','school-mgt');?></th>

										<th><?php esc_attr_e('Due Amount','school-mgt');?></th>

										<th><?php esc_attr_e('Start Date To End Date','school-mgt');?></th>

										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

									</tr>

								</thead>

								<tbody>

									<?php

									$i=0;

									foreach ($data as $retrieved_data)

									{

										if($i == 10)

										{

											$i=0;

										}

										if($i == 0)

										{

											$color_class='smgt_class_color0';

										}

										elseif($i == 1)

										{

											$color_class='smgt_class_color1';

										}

										elseif($i == 2)

										{

											$color_class='smgt_class_color2';

										}

										elseif($i == 3)

										{

											$color_class='smgt_class_color3';

										}

										elseif($i == 4)

										{

											$color_class='smgt_class_color4';

										}

										elseif($i == 5)

										{

											$color_class='smgt_class_color5';

										}

										elseif($i == 6)

										{

											$color_class='smgt_class_color6';

										}

										elseif($i == 7)

										{

											$color_class='smgt_class_color7';

										}

										elseif($i == 8)

										{

											$color_class='smgt_class_color8';

										}

										elseif($i == 9)

										{

											$color_class='smgt_class_color9';

										}

										?>

										<tr>

											<?php
											if($role_name == "supportstaff")
											{
												?>
												<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->fees_pay_id;?>"></td>
												<?php
											}
											?>

											<td class="user_image width_50px">
												<a href="?dashboard=user&page=feepayment&tab=view_fesspayment&idtest=<?php echo $retrieved_data->fees_pay_id; ?>&view_type=view_payment" class="" >
													<?php
														$uid=$retrieved_data->student_id;
														$umetadata=mj_smgt_get_user_image($uid);
														if(empty($umetadata))
														{
															echo '<img src='.get_option( 'smgt_student_thumb_new' ).' class="img-circle" />';
														}
														else
														{
															echo '<img src='.$umetadata.' class="img-circle" />';
														}
													?>
												</a>
											</td>
											<td><?php

											$fees_id=explode(',',$retrieved_data->fees_id);

											$fees_type=array();

											foreach($fees_id as $id)

											{

												$fees_type[] = mj_smgt_get_fees_term_name($id);

											}

											echo implode(" , " ,$fees_type);

											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Fees Title','school-mgt');?>"></i></td>

											<td><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>

											<td><?php if($retrieved_data->class_id == "0"){ esc_html_e('All Class','school-mgt');}else{ echo smgt_get_class_section_name_wise($retrieved_data->class_id,$retrieved_data->section_id);}  ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

											<td>

												<?php

												$smgt_get_payment_status=mj_smgt_get_payment_status($retrieved_data->fees_pay_id);

												if($smgt_get_payment_status == 'Not Paid')

												{

												echo "<span class='red_color'>";

												}

												elseif($smgt_get_payment_status == 'Partially Paid')

												{

													echo "<span class='perpal_color'>";

												}

												else

												{

													echo "<span class='green_color'>";

												}



												echo esc_html__("$smgt_get_payment_status","school-mgt");

												echo "</span>";

												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i>

											</td>

											<td><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->total_amount,2,'.','')); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>
											<td><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->fees_paid_amount,2,'.','')); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Paid Amount','school-mgt');?>"></i></td>

												<?php

												$Due_amt = $retrieved_data->total_amount-$retrieved_data->fees_paid_amount;

												$due_amount=number_format($Due_amt,2,'.','');

												?>

											<td><?php echo MJ_smgt_currency_symbol_position_language_wise($due_amount); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Due Amount','school-mgt');?>"></i></td>

											<td><?php echo $retrieved_data->start_year.' '.esc_html__('To','school-mgt').' '.$retrieved_data->end_year;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start Date To End Date','school-mgt');?>"></i></td>

											<td class="action">

												<div class="smgt-user-dropdown">

													<ul class="" style="margin-bottom: 0px !important;">

														<li class="">

															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

															</a>

															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																<li class="float_left_width_100 ">

																	<a href="?dashboard=user&page=feepayment&tab=view_fesspayment&idtest=<?php echo $retrieved_data->fees_pay_id; ?>&view_type=view_payment" class="float_left_width_100" ><i class="fa fa-eye"></i><?php esc_attr_e('View Invoice','school-mgt');?></a>

																</li>

																<?php

																if($school_obj->role == 'supportstaff' || $school_obj->role == 'parent' ||  $school_obj->role == 'student')

																{

																	if($retrieved_data->fees_paid_amount < $retrieved_data->total_amount || $retrieved_data->fees_paid_amount == 0)

																	{

																		?>

																		<li class="float_left_width_100 ">

																			<a href="#" class="float_left_width_100 show-payment-popup" idtest="<?php echo $retrieved_data->fees_pay_id; ?>" view_type="payment" due_amount="<?php echo $due_amount; ?>" ><i class="fa fa-credit-card" aria-hidden="true"></i><?php esc_attr_e('Pay','school-mgt');?></a>

																		</li>

																		<?php

																	}

																}

																if($user_access['edit']=='1')

																{

																	?>

																	<li class="float_left_width_100 border_bottom_menu">

																		<a href="?dashboard=user&page=feepayment&tab=addpaymentfee&action=edit&fees_pay_id=<?php echo $retrieved_data->fees_pay_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																	</li>

																	<?php

																}

																if($user_access['delete']=='1')

																{

																	?>

																	<li class="float_left_width_100 ">

																		<a href="?dashboard=user&page=feepayment&tab=examlist&action=delete&fees_pay_id=<?php echo $retrieved_data->fees_pay_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

																		<i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

																	</li>

																	<?php

																}

																?>

															</ul>

														</li>

													</ul>

												</div>

											</td>

										</tr>

										<?php

										$i++;

									}

									?>

								</tbody>

							</table>

							<?php

							if($role_name == "supportstaff")

							{

								?>

								<div class="print-button pull-left">

									<button class="btn-sms-color button_reload">

										<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">

										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

									</button>

									<?php

									if($user_access['delete']=='1')

									{

										?>

										<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_feelist" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

										<?php

									}

									?>

								</div>

								<?php

							}

							?>

						</form><!------------- FEES PAYMENT LIST FORM ----------------->

					</div><!------------- TABLE RESPOSNIVE ------------------>

				</div><!------------- PENAL BODY ------------------>

				<?php

			}

			else

			{

				if($user_access['add']=='1')

				{

					?>

					<div class="no_data_list_div no_data_img_mt_30px">

						<a href="<?php echo home_url().'?dashboard=user&page=feepayment&tab=addpaymentfee';?>">

							<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >

						</a>

						<div class="col-md-12 dashboard_btn margin_top_20px">

							<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>

						</div>

					</div>

					<?php

				}

				else

				{

					?>

					<div class="calendar-event-new">

						<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

					</div>

					<?php

				}

			}

		}

        if($active_tab == 'addpaymentfee')

		{

			$fees_pay_id=0;

			if(isset($_REQUEST['fees_pay_id']))

				$fees_pay_id=$_REQUEST['fees_pay_id'];

			$edit=0;

			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

			{

				$edit=1;

				$result = $obj_feespayment->mj_smgt_get_single_fee_mj_smgt_payment($fees_pay_id);

			}

			?>
<script type="text/javascript">
    jQuery(document).ready(function($)
	{
        $('#selected_users').multiselect({
			nonSelectedText :"<?php esc_attr_e('Select Users','school-mgt');?>",
			includeSelectAllOption: true,
			selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
			templates: {
				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
		});
		$('.tax_charge').multiselect(
        {
            nonSelectedText: '<?php esc_html_e('Select Tax','school-mgt');?>',
            includeSelectAllOption: true,
            allSelectedText: '<?php esc_html_e('All Selected','school-mgt'); ?>',
            selectAllText: '<?php esc_html_e('Select all','school-mgt'); ?>',
            templates: {
                button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
            },
            buttonContainer: '<div class="dropdown" />'
        });
        var start = new Date();
		var end = new Date(new Date().setYear(start.getFullYear()+1));
		$("#start_date_event").datepicker(
		{
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			minDate:0,
			changeMonth: true,
			changeYear: true,
			onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() + 0);
				$("#end_date_event").datepicker("option", "minDate", dt);
			},
			beforeShow: function (textbox, instance)
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			}
	    });
	    $("#end_date_event").datepicker(
		{
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			minDate:0,
			changeMonth: true,
			changeYear: true,
	       onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() - 0);
				$("#start_date_event").datepicker("option", "maxDate", dt);
			},
			beforeShow: function (textbox, instance)
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			}
	    });
    });
    </script>
			<div class="panel-body"><!---------------- PENAL BODY ----------------->

				<!-------------- FEES PAYMENT FORM ------------------>

				<form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">

					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

					<input type="hidden" name="action" value="<?php echo $action;?>">

					<input type="hidden" name="fees_pay_id" value="<?php echo $fees_pay_id;?>">
					<input type="hidden" name="recurrence_type" value="one_time">
					<input type="hidden" name="invoice_type" value="expense">

					<div class="header">

						<h3 class="first_hed"><?php esc_html_e('Fees Payment Information','school-mgt');?></h3>

					</div>

					<div class="form-body user_form">

						<div class="row">

						<?php
							if(!$edit)
							{
							$recurring_option=get_option('smgt_enable_recurring_invoices');
							if($recurring_option == 'yes')
							{
							?>
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 recurring_option_checkbox rtl_margin_bottom_0px">
								<div class="form-group">

									<div class="col-md-12 form-control">

										<div class="row padding_radio">

											<div class="input-group">

												<label class="custom-top-label" for="classis_limit"><?php esc_html_e('Recurrence Type','school-mgt');?></label>

												<div class="d-inline-block gender_line_height_24px">

													<?php $recurrence_type = "one_time"; if($edit){ $recurrence_type=$result->recurrence_type; }elseif(isset($_POST['recurrence_type'])) {$recurrence_type=sanitize_text_field($_POST['recurrence_type']);}?>

													<label class="radio-inline">

														<input type="radio" value="one_time" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('one_time',esc_html($recurrence_type)); ?>/><?php esc_html_e('One Time','school-mgt');?>

													</label>
													<label class="radio-inline">
														<input type="radio" value="weekly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('weekly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Weekly','school-mgt');?> 

													</label>
													<label class="radio-inline">

														<input type="radio" value="monthly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('monthly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Monthly','school-mgt');?>

													</label>

													<label class="radio-inline">
														<input type="radio" value="quarterly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('Quarterly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Quarterly','school-mgt');?>

													</label>
													<label class="radio-inline">
														<input type="radio" value="half_yearly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('half_yearly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Half- Yearly','school-mgt');?>

													</label>

												</div>

											</div>

										</div>

									</div>

								</div>
							</div>
							<div class="col-md-6 input">
							</div>
							<?php
							}
							}
							if($edit)
							{
							?>
							<div class="col-md-6 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
								<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
								<select name="class_id" id="class_list" class="form-control validate[required] load_fees_drop max_width_100" style="height:47px;">
									<?php
									if($addparent)
									{
										$classdata=mj_smgt_get_class_by_id($student->class_name);
										?>
										<option value="<?php echo $student->class_name;?>"><?php echo $classdata->class_name;?></option>
										<?php
									}?>
									<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
									<?php
									foreach(mj_smgt_get_allclass() as $classdata)
									{ ?>
										<option value="<?php echo $classdata['class_id'];?>"
											<?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?>
										</option>
										<?php
									}?>
								</select>
							</div>
							<div class="col-md-6 input class_section_hide">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
								<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
								<select name="class_section" class="form-control max_width_100" id="class_section" style="height:47px;">
									<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
									<?php
											if($edit){
												foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)
												{  ?>
									<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>>
										<?php echo $sectiondata->section_name;?></option>
									<?php }
											}?>
								</select>
							</div>
							<div class="col-md-6 input class_section_hide">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?></label>
								<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
								<select name="student_id" id="student_list" class="form-control validate[required] max_width_100" style="height:47px;">
									<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
									<?php
										if($edit)
										{
											echo '<option value="'.$result->student_id.'" '.selected($result->student_id,$result->student_id).'>'.mj_smgt_student_display_name_with_roll($result->student_id).'</option>';
										}
									?>
								</select>
							</div>
							<?php
							}
							else{
							?>
							<div id="smgt_select_class" class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input single_class_div">
								<label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
								<select name="class_id"  id="fees_class_list_id" class="form-control load_fees min_width_100 validate[required]" style="height:47px;">
									<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
									<option value="all_class"><?php esc_attr_e('All Class','school-mgt');?></option>
									<?php
									foreach(mj_smgt_get_allclass() as $classdata)
									{
									?>
									<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
								<?php }?>
								</select>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input class_section_id">
								<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
								<?php if(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
								<select name="class_section" class="form-control min_width_100" id="fees_class_section_id" style="height:47px;">
									<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
									<?php
									if($edit){
										foreach(mj_smgt_get_class_sections($user_info->class_name) as $sectiondata)
										{  ?>
										<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
									<?php }
									}?>
								</select>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 single_class_div support_staff_user_div input">
								<div id="messahe_test"></div>
								<div class="col-sm-12 smgt_multiple_select rtl_padding_left_right_0px">
									<span class="user_display_block">
										<select name="selected_users[]" id="selected_users" class="form-control min_width_250px validate[required]" multiple="multiple">
											<?php
											$student_list = mj_smgt_get_all_student_list();
											foreach($student_list  as $retrive_data)
											{
												echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->display_name.'</option>';
											}
											?>
										</select>
									</span>
								</div>
							</div>
							<?php
							}
							?>

							<?php wp_nonce_field( 'save_payment_fees_front_nonce' ); ?>

							<div class="col-md-6 padding_bottom_15px_res rtl_margin_top_15px">

								<div class="col-sm-12 multiselect_validation_class smgt_multiple_select rtl_padding_left_right_0px">

									<select name="fees_id[]" multiple="multiple" id="fees_data" class="line_height_30px form-control validate[required] max_width_100">

										<?php

										if($edit)

										{

											$fees_id=explode(',',$result->fees_id);

											foreach($fees_id as $id)

											{

												if(mj_smgt_get_fees_term_name($id) !== " ")

														{

												echo '<option value="'.$id.'" '.selected($id,$id).'>'.mj_smgt_get_fees_term_name($id).'</option>';

											}

											}

										}

										?>

									</select>

								</div>

							</div>

							<div class="col-md-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="fees_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="text" value="<?php if($edit){ echo $result->fees_amount;}elseif(isset($_POST['fees_amount'])) { echo $_POST['fees_amount']; }else{ echo "0"; } ?>" name="fees_amount" readonly>

										<label for="userinput1" class=""><?php esc_html_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="required">*</span></label>

									</div>

								</div>

							</div>

							<div class="rtl_margin_top_15px col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 multiselect_validation_member rtl_margin_bottom_0px smgt_multiple_select">
                        <select class="form-control tax_charge" id="tax_id" name="tax[]" multiple="multiple">
                            <?php
                                if($edit)
                                {
                                    if ($result->tax !== null) {
                                        $tax_id = explode(',', $result->tax);
                                    } else {
                                        $tax_id[]='';
                                    }
                                }
                                else
                                {
                                    $tax_id[]='';
                                }
                                $obj_tax=new tax_Manage();
                                $smgt_taxs=$obj_tax->MJ_smgt_get_all_tax();
                                if(!empty($smgt_taxs))
                                {
                                    foreach($smgt_taxs as $data)
                                    {
                                        $selected = "";
                                        if(in_array($data->tax_id,$tax_id))
                                            $selected = "selected";

                                        ?>
                                        <option value="<?php echo esc_attr($data->tax_id); ?>" <?php echo esc_html($selected); ?>>
                                        <?php echo esc_html($data->tax_title);?> - <?php echo esc_html($data->tax_value);?></option>
                                    <?php
                                    }

                                }
                            ?>
                            </select>
                        </div>

							<div class="col-md-6 note_text_notice">

								<div class="form-group input">

									<div class="col-md-12 note_border margin_bottom_15px_res">

										<div class="form-field">

											<textarea name="description" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>

											<span class="txt-title-label"></span>

											<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>

										</div>

									</div>

								</div>

							</div>

							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="start_date_event" class="form-control date_picker validate[required] start_date datepicker1" autocomplete="off" type="text"  name="start_year" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->start_year)));}elseif(isset($_POST['start_year'])) echo mj_smgt_getdate_in_input_box($_POST['start_year']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
										<label class="active date_label" for="start"><?php esc_html_e('Start Date','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="end_date_event" class="form-control date_picker validate[required] start_date datepicker2" type="text"  name="end_year" autocomplete="off" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->end_year)));}elseif(isset($_POST['end_year'])) echo mj_smgt_getdate_in_input_box($_POST['end_year']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
										<label class="date_label" for="end"><?php esc_html_e('End Date','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<div class="col-md-6 mb-3 rtl_margin_top_15px rtl_margin_bottom_0px">

								<div class="form-group">

									<div class="col-md-12 form-control input_height_50px">

										<div class="row padding_radio">

											<div class="input-group input_checkbox">

												<label class="custom-top-label label_right_position"><?php esc_html_e('Send Email To Students & Parents','school-mgt');?></label>

												<div class="checkbox checkbox_lebal_padding_8px">

													<label>

													<input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="smgt_enable_feesalert_mail" value="1" <?php echo checked(get_option('smgt_enable_feesalert_mail'),'yes');?> />

													</label>

												</div>
												&nbsp;&nbsp;<span><?php esc_html_e('Enable','school-mgt');?></span>
											</div>

										</div>

									</div>

								</div>

							</div>

							<div class="col-md-3 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">

								<div class="form-group">

									<div class="col-md-12 form-control input_height_50px">

										<div class="row padding_radio">

											<div class="input-group input_checkbox">

												<label class="custom-top-label label_right_position"><?php esc_html_e('Send SMS To Students','school-mgt');?></label>

												<div class="checkbox checkbox_lebal_padding_8px">

													<label>

														<input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="smgt_enable_feesalert_sms_student"  value="1" <?php echo checked(get_option('smgt_enable_feesalert_sms'),'yes');?>/>

													</label>

												</div>
												&nbsp;&nbsp;<span><?php esc_html_e('Enable','school-mgt');?></span>
											</div>

										</div>

									</div>

								</div>

							</div>

							<div class="col-md-3 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">

								<div class="form-group">

									<div class="col-md-12 form-control input_height_50px">

										<div class="row padding_radio">

											<div class="input-group input_checkbox">

												<label class="custom-top-label label_right_position"><?php esc_html_e('Send SMS To Parents','school-mgt');?></label>

												<div class="checkbox checkbox_lebal_padding_8px">

													<label>

														<input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="smgt_enable_feesalert_sms_parent"  value="1" <?php echo checked(get_option('smgt_enable_feesalert_sms'),'yes');?>/>

													</label>

												</div>
												&nbsp;&nbsp;<span><?php esc_html_e('Enable','school-mgt');?></span>
											</div>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="form-body user_form padding_top_15px_res">

						<div class="row">

							<div class="col-sm-6">

								<input type="submit" value="<?php if($edit){ esc_attr_e('Save Invoice','school-mgt'); }else{ esc_attr_e('Create Invoice','school-mgt');}?>" name="save_feetype_payment" class="btn btn-success save_btn"/>

							</div>

						</div>

					</div>

				</form>

			</div>



        	<?php

        }

		elseif($active_tab == 'view_fesspayment')

		{

			$fees_pay_id = $_REQUEST['idtest'];

			$fees_detail_result = mj_smgt_get_single_fees_payment_record($fees_pay_id);

			$fees_history_detail_result = mj_smgt_get_payment_history_by_feespayid($fees_pay_id);

			$obj_feespayment= new mj_smgt_feespayment();

			?>

			<div class="penal-body"><!----- penal Body --------->

				<div id="Fees_invoice"><!----- Fees Invoice --------->

					<div id="rs_invoice_view_mt_15" class="modal-body border_invoice_page margin_top_15px_rs invoice_model_body float_left_width_100 padding_0_res height_1000px mb-3">

						<img class="rtl_image_set_invoice invoiceimage float_left image_width_98px invoice_image_model"  src="<?php echo plugins_url('/school-management/assets/images/listpage_icon/invoice.png'); ?>" width="100%">

						<div id="invoice_print" class="main_div float_left_width_100 payment_invoice_popup_main_div">

							<div class="invoice_width_100 float_left" border="0">

								<h3 class=""><?php echo get_option( 'smgt_school_name' ) ?></h3>

								<div class="row margin_top_20px">

									<div class="col-md-1 col-sm-2 col-xs-3">

										<div class="width_1 rtl_width_80px">

											<img class="system_logo"  src="<?php echo esc_url(get_option( 'smgt_school_logo' )); ?>">

										</div>

									</div>

								<div class="col-md-11 col-sm-10 col-xs-9 invoice_address invoice_address_css">

									<div class="row">

										<div class="col-md-12 col-sm-12 col-xs-12 invoice_padding_bottom_15px padding_right_0">

											<label class="popup_label_heading"><?php esc_html_e('Address','school-mgt');

											?>

											</label><br>

											<label for="" class="label_value word_break_all">	<?php

													echo chunk_split(get_option( 'smgt_school_address' ),100,"<BR>")."";

												?></label>

										</div>

										<div class="row col-md-12 invoice_padding_bottom_15px">

											<div class="col-md-6 col-sm-6 col-xs-6 address_css padding_right_0 email_width_auto">

												<label class="popup_label_heading"><?php esc_html_e('Email','school-mgt');?> </label><br>

												<label for="" class="label_value word_break_all"><?php echo get_option( 'smgt_email' ),"<BR>";  ?></label>

											</div>



											<div class="col-md-6 col-sm-6 col-xs-6 address_css padding_right_0 padding_left_30px">

												<label class="popup_label_heading"><?php esc_html_e('Phone','school-mgt');?> </label><br>

												<label for="" class="label_value"><?php echo get_option( 'smgt_contact_number' )."<br>";  ?></label>

											</div>

										</div>

										<div align="right" class="width_24"></div>

									</div>

								</div>

							</div>

							<div class="col-md-12 col-sm-12 col-xl-12 mozila_display_css margin_top_20px">

								<div class="row">

									<div class="width_50a1 float_left_width_100">

										<div class="col-md-8 col-sm-8 col-xs-5 padding_0 float_left display_grid display_inherit_res margin_bottom_20px">

											<div class="billed_to display_flex display_inherit_res invoice_address_heading">

												<h3 class="billed_to_lable invoice_model_heading bill_to_width_12"><?php esc_html_e('Bill To','school-mgt');?> : </h3>

												<?php

													$student_id=$fees_detail_result->student_id;

													$patient=get_userdata($student_id);

													echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";

												?>

											</div>

											<div class="width_60b2 address_information_invoice">

												<?php

												$student_id=$fees_detail_result->student_id;

												$patient=get_userdata($student_id);

												// echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";

												$address=get_user_meta( $student_id,'address',true );



												echo chunk_split($address,30,"<BR>");

												echo get_user_meta( $student_id,'city',true ).","."<BR>"; ;

												echo get_user_meta( $student_id,'zip_code',true ).",<BR>";

												?>

												</div>

											</div>

											<div class="col-md-3 col-sm-4 col-xs-7 float_left">

												<div class="width_50a1112">

													<div class="width_20c" align="center">

														<?php



														$issue_date='DD-MM-YYYY';

														$issue_date=$fees_detail_result->paid_by_date;

														$payment_status = mj_smgt_get_payment_status($fees_detail_result->fees_pay_id);

														?>

														<h5 class="align_left"> <label class="popup_label_heading text-transfer-upercase"><?php   echo esc_html__('Date :','school-mgt') ?> </label>&nbsp;  <label class="invoice_model_value"><?php echo mj_smgt_getdate_in_input_box(date("Y-m-d", strtotime($issue_date))); ?></label></h5>

														<h5 class="align_left"><label class="popup_label_heading text-transfer-upercase"><?php echo esc_html__('Status :','school-mgt')?> </label>  &nbsp;<label class="invoice_model_value"><?php

															if($payment_status=='Fully Paid')

															{ echo '<span class="green_color">'.esc_attr__('Fully Paid','school-mgt').'</span>';}

															if($payment_status=='Partially Paid')

															{ echo '<span class="perpal_color">'.esc_attr__('Partially Paid','school-mgt').'</span>';}

															if($payment_status=='Not Paid')

															{echo '<span class="red_color">'.esc_attr__('Not Paid','school-mgt').'</span>'; } ?></h5>

													</div>

												</div>

											</div>

										</div>

									</div>

								</div>

								<table class="width_100 margin_top_10px_res">

									<tbody>

										<tr>

											<td>

												<h3 class="display_name"><?php esc_attr_e('Invoice Entries','school-mgt');?></h3>

											<td>

										</tr>

									</tbody>

								</table>

								<div class="table-responsive padding_bottom_15px rtl_padding-left_40px">

									<table class="table model_invoice_table">

										<thead class="entry_heading invoice_model_entry_heading">

											<tr>

												<th class="entry_table_heading align_left">#</th>

												<th class="entry_table_heading align_left"><?php esc_attr_e('Date','school-mgt');?></th>

												<th class="entry_table_heading align_left"> <?php esc_attr_e('Fees Type','school-mgt');?></th>

												<th class="entry_table_heading align_left"><?php esc_attr_e('Total','school-mgt');?> </th>

											</tr>

										</thead>

										<tbody>

											<?php

											$fees_id=explode(',',$fees_detail_result->fees_id);

											$x=1;
											$amounts = 0;
											foreach($fees_id as $id)

											{

												?>

												<tr>

													<td class="align_left invoice_table_data"> <?php echo $x; ?></td>

													<td class="align_left invoice_table_data"> <?php echo mj_smgt_getdate_in_input_box($fees_detail_result->created_date);?></td>

													<td class="align_left invoice_table_data"> <?php echo mj_smgt_get_fees_term_name($id); ?></td>

													<td class="align_left invoice_table_data">

														<?php

														$amount=$obj_feespayment->mj_smgt_feetype_amount_data($id);
														$amounts += $amount;
														echo MJ_smgt_currency_symbol_position_language_wise(number_format($amount,2,'.',''));

														?>

													</td>

												</tr>

												<?php

												$x++;

											}
											$sub_total = $amounts;
											if(!empty($fees_detail_result->tax)){

												$tax_name = MJ_smgt_tax_name_by_tax_id_array_for_invoice(esc_html($fees_detail_result->tax));

											}

											else{

												$tax_name = '';

											}
											?>

										</tbody>

									</table>

								</div>

								<div class="table-responsive rtl_padding-left_40px rtl_float_left_width_100px">

									<table width="100%" border="0">

										<tbody>

											<tr style="">

												<td  align="right" class="rtl_float_left_label padding_bottom_15px total_heading"><?php esc_attr_e('Sub Total :','school-mgt');?></td>

												<td align="right" class="rtl_width_15px rtl_text_align_left padding_bottom_15px total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($sub_total,2,'.','')); ?></td>

											</tr>
											<?php if(isset($fees_detail_result->tax_amount))
											{
												?>
											<tr>
												<td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php echo esc_attr__('Tax Amount','school-mgt').'('.$tax_name.')'.'  :';?></td>
												<td align="right" class="rtl_width_15px rtl_text_align_left padding_bottom_15px total_value"><?php echo "+".MJ_smgt_currency_symbol_position_language_wise(number_format($fees_detail_result->tax_amount,2,'.',''));?></td>
											</tr>
											<?php
											}?>
											<tr>

												<td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php esc_attr_e('Payment Made :','school-mgt');?></td>

												<td align="right" class="rtl_width_15px rtl_text_align_left padding_bottom_15px total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($fees_detail_result->fees_paid_amount,2,'.',''));?></td>

											</tr>

											<tr>

												<td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php esc_attr_e('Due Amount :','school-mgt');?></td>

												<?php $Due_amount = $fees_detail_result->total_amount - $fees_detail_result->fees_paid_amount; ?>

												<td align="right" class="rtl_width_15px rtl_text_align_left padding_bottom_15px total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($Due_amount,2,'.','')); ?></td>

											</tr>

										</tbody>

									</table>

								</div>

								<?php

								$subtotal = $fees_detail_result->total_amount;

								$paid_amount = $fees_detail_result->fees_paid_amount;

								$grand_total = $subtotal - $paid_amount;

								?>

								<div id="res_rtl_width_100" class="rtl_float_left row margin_top_10px_res col-md-4 col-sm-4 col-xs-4 view_invoice_lable_css inovice_width_100px_rs float_left grand_total_div invoice_table_grand_total" style="float: right;margin-right:0px;">

									<div class="width_50_res align_right col-md-5 col-sm-5 col-xs-5 view_invoice_lable padding_11 padding_right_0_left_0 float_left grand_total_label_div invoice_model_height line_height_1_5 padding_left_0_px"><h3 style="float: right;" class="padding color_white margin invoice_total_label"><?php esc_html_e('Grand Total','school-mgt');?> </h3></div>

									<div class="width_50_res  align_right col-md-7 col-sm-7 col-xs-7 view_invoice_lable  padding_right_5_left_5 padding_11 float_left grand_total_amount_div"><h3 class="padding margin text-right color_white invoice_total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($subtotal,2,'.','')); ?></h3></div>

								</div>

								<?php if(!empty($fees_history_detail_result))

								{

									?>

									<table class="width_100 margin_top_10px_res">

										<tbody>

											<tr>

												<td>

													<h3 class="display_name res_pay_his_mt_10px"><?php esc_attr_e('Payment History','school-mgt');?></h3>

												<td>

											</tr>

										</tbody>

									</table>

									<div class="table-responsive table_max_height_350px rtl_width_96">

										<table class="table model_invoice_table">

											<thead class="entry_heading invoice_model_entry_heading">

												<tr>

													<th class="entry_table_heading align_left"><?php esc_attr_e('Date','school-mgt');?></th>

													<th class="entry_table_heading align_left"> <?php esc_attr_e('Amount','school-mgt');?></th>

													<th class="entry_table_heading align_left"><?php esc_attr_e('Method','school-mgt');?> </th>

												</tr>

											</thead>

											<tbody>

												<?php

												foreach($fees_history_detail_result as  $retrive_date)

												{

													?>

													<tr>

														<td class="align_left invoice_table_data"><?php echo mj_smgt_getdate_in_input_box($retrive_date->paid_by_date);?></td>

														<td class="align_left invoice_table_data"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrive_date->amount,2,'.',''));?></td>

														<td class="align_left invoice_table_data"><?php  $data=$retrive_date->payment_method;

															echo esc_attr__("$data","school-mgt");

															?>

														</td>

													</tr>

													<?php

												} ?>

											</tbody>

										</table>

									</div>

									<?php

								} ?>

								<div class="col-md-12 grand_total_main_div total_padding_15px rtl_float_none">

									<div class="row margin_top_10px_res width_50_res col-md-6 col-sm-6 col-xs-6 pull-left invoice_print_pdf_btn">

										<div class="col-md-2 print_btn_rs width_50_res">

											<a href="?page=smgt_fees_payment&print=print&payment_id=<?php echo $_REQUEST['idtest'];?>&fee_paymenthistory=<?php echo 'fee_paymenthistory';?>" target="_blank" class="btn btn save_btn invoice_btn_div"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/print.png" ?>" > </a>

										</div>

										<div class="col-md-3 pdf_btn_rs width_50_res">

											<a href="?page=smgt_fees_payment&print=pdf&payment_id=<?php echo $_REQUEST['idtest'];?>&fee_paymenthistory=<?php echo "fee_paymenthistory";?>" target="_blank" class="btn color_white invoice_btn_div btn save_btn"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/pdf.png" ?>" ></a>

										</div>

									</div>



								</div>

								<div class="margin_top_20px"></div>

							</div>

						</div>

					</div>

				</div><!----- Fees Invoice --------->

			</div><!----- penal Body --------->

			<?php

		}
		if($active_tab == 'recurring_feespaymentlist')
		{

			$user_id=get_current_user_id();

			if($school_obj->role == 'teacher')
			{
				$own_data=$user_access['own_data'];

				if($own_data == '1')

				{
					global $wpdb;
					$class_id	= 	get_user_meta(get_current_user_id(),'class_name',true);

					$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment_recurring';
					$retrieve_class =$wpdb->get_results("SELECT * FROM $table_smgt_fees_payment WHERE class_id in (".implode(',', $class_id).")");
				}
				else{
					$retrieve_class = $obj_feespayment->mj_smgt_get_all_recurring_fees();
				}
			}
			else{
				$retrieve_class = $obj_feespayment->mj_smgt_get_all_recurring_fees();
			}
			if(!empty($retrieve_class))
			{
				?>
				<script type="text/javascript">

					jQuery(document).ready(function($){

						var table =  jQuery('#recurring_fees_paymnt_list').DataTable({

							"initComplete": function(settings, json) {

									$(".print-button").css({"margin-top": "-5%"});

								},

							responsive: true,
							"dom": 'lifrtp',
							"aoColumns":[

							{"bSortable": true},

							{"bSortable": true},

							{"bSortable": true},

							{"bSortable": true},

							{"bSortable": true},

							{"bSortable": true},
							{"bSortable": true},
							<?php
							if($user_access['edit']=='1' || $user_access['delete']=='1')
							{
							?>
								{"bSortable": false}
							<?php
							}
							?>

							],

							language:<?php echo mj_smgt_datatable_multi_language();?>

						});

						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

						jQuery('#checkbox-select-all').on('click', function(){

							var rows = table.rows({ 'search': 'applied' }).nodes();

							jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);

						});



						$("#delete_selected").on('click', function()

						{

							if ($('.select-checkbox:checked').length == 0 )

							{

								alert(language_translate2.one_record_select_alert);

								return false;

							}

							else

							{

									var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?', 'school-mgt') ?>");

									if(alert_msg == false)

									{

										return false;

									}

									else

									{

										return true;

									}

							}

						});



						$("#fees_reminder").on('click', function()

						{

								if ($('.select-checkbox:checked').length == 0 )

								{

									alert(language_translate2.one_record_select_alert);

									return false;

								}

							else

							{

									var alert_msg=confirm("<?php esc_html_e("Are you sure you want to send a mail reminder?",'school-mgt') ?>");

									if(alert_msg == false)

									{

										return false;

									}

									else

									{

										return true;

									}

							}

						});

						$('.select_all').on('click', function(e)

						{

							if($(this).is(':checked',true))

							{

								$(".smgt_sub_chk").prop('checked', true);

							}

							else

							{

								$(".smgt_sub_chk").prop('checked',false);

							}

						});

						$('.smgt_sub_chk').on('change',function()

						{

							if(false == $(this).prop("checked"))

							{

								$(".select_all").prop('checked', false);

							}

							if ($('.smgt_sub_chk:checked').length == $('.smgt_sub_chk').length )

							{

								$(".select_all").prop('checked', true);

							}

						});

						$("#fees_reminder_single").on('click', function()

						{

							alert(language_translate2.mail_reminder);

							return true;

						});

					});

				</script>



				<div class="panel-body">

					<div class="table-responsive">

						<form id="frm-example" name="frm-example" method="post">

							<table id="recurring_fees_paymnt_list" class="display" cellspacing="0" width="100%">

								<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

									<tr>

										<th><?php esc_attr_e('Fees Title','school-mgt');?></th>
										<th><?php esc_attr_e('Recurring Type','school-mgt');?></th>
										<th><?php esc_attr_e('Student Name','school-mgt');?></th>
										<th><?php esc_attr_e('Class Name','school-mgt');?> </th>
										<th><?php esc_attr_e('Status','school-mgt'); ?></th>
										<th><?php esc_attr_e('Total Amount','school-mgt');?></th>
										<th><?php esc_attr_e('Start Date To End Date','school-mgt');?></th>
										<?php
										if($user_access['edit']=='1' || $user_access['delete']=='1')
										{
										?>
											<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
										<?php
										}
										?>
									</tr>

								</thead>

								<tbody>

									<?php

									$i=0;

									foreach ($retrieve_class as $retrieved_data)
									{
										if($i == 10)
										{

											$i=0;

										}
										if($i == 0)
										{
											$color_class='smgt_class_color0';
										}
										elseif($i == 1)

										{

											$color_class='smgt_class_color1';

										}

										elseif($i == 2)

										{

											$color_class='smgt_class_color2';

										}

										elseif($i == 3)

										{

											$color_class='smgt_class_color3';

										}

										elseif($i == 4)

										{

											$color_class='smgt_class_color4';

										}

										elseif($i == 5)

										{

											$color_class='smgt_class_color5';

										}

										elseif($i == 6)

										{

											$color_class='smgt_class_color6';

										}

										elseif($i == 7)

										{

											$color_class='smgt_class_color7';

										}

										elseif($i == 8)

										{

											$color_class='smgt_class_color8';

										}

										elseif($i == 9)

										{

											$color_class='smgt_class_color9';

										}

										?>

										<tr>


											<td>

											<?php
											$fees_id=explode(',', $retrieved_data->fees_id);
											$fees_type=array();
											foreach($fees_id as $id)
											{
												$fees_type[] = mj_smgt_get_fees_term_name($id);
											}
											echo implode(" , " ,$fees_type);
											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Fees Title','school-mgt');?>"></i></td>
											<td>
												<?php
												if($retrieved_data->recurring_type == "monthly")
												{
													esc_html_e('Monthly','school-mgt');
												}
												elseif($retrieved_data->recurring_type == "weekly")
												{
													esc_html_e('Weekly','school-mgt');
												}
												elseif($retrieved_data->recurring_type == "quarterly")
												{
													esc_html_e('Quarterly','school-mgt');
												}
												elseif($retrieved_data->recurring_type == "half_yearly")
												{
													esc_html_e('Half- Yearly','school-mgt');
												}
												else{
													esc_html_e('One Time','school-mgt');
												}
												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Recurring Type','school-mgt');?>"></i></td>

											<?php
											$student_id_array=explode(',', $retrieved_data->student_id);
											$student_data=array();
											foreach($student_id_array as $student_id)
											{
												$student_data[] =mj_smgt_student_display_name_with_roll($student_id);
											}
											?>
											<td><?php echo implode(", " ,$student_data);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php echo implode(", " ,$student_data);?>"></i></td>

											<td><?php if($retrieved_data->class_id == "0"){ esc_html_e('All Class','school-mgt');}else{ echo smgt_get_class_section_name_wise($retrieved_data->class_id,$retrieved_data->section_id);} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
											<td><?php echo $retrieved_data->status;?></td>

											<td><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->total_amount,2,'.','')); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>


											<td><?php echo $retrieved_data->start_year.' '.esc_html__('To','school-mgt').' '.$retrieved_data->end_year;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start Date To End Date','school-mgt');?>"></i></td>
											<?php
											if($user_access['edit']=='1' || $user_access['delete']=='1')
											{
											?>

											<td class="action">

												<div class="smgt-user-dropdown">

													<ul class="" style="margin-bottom: 0px !important;">

														<li class="">

															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

															</a>

															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																<?php

																if($user_access['edit']=='1')

																{

																	?>

																	<li class="float_left_width_100 border_bottom_menu">

																		<a href="?dashboard=user&page=feepayment&tab=addrecurringpayment&action=edit&recurring_fees_id=<?php echo $retrieved_data->recurring_id ;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																	</li>

																	<?php

																}

																if($user_access['delete']=='1')

																{

																	?>

																	<li class="float_left_width_100 ">

																		<a href="?dashboard=user&page=feepayment&tab=feespaymentlist&action=delete&recurring_fees_id=<?php echo $retrieved_data->recurring_id ;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

																		<i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

																	</li>

																	<?php

																}

																?>


															</ul>

														</li>

													</ul>

												</div>

											</td>
											<?php
											}
											?>

										</tr>

										<?php

										$i++;

									}

									?>

								</tbody>

							</table>


						</form>

					</div>

				</div>

				<?php

			}

			else

			{

				if($user_access_add=='1')

				{

					?>

					<div class="no_data_list_div no_data_img_mt_30px">

						<a href="<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=addpaymentfee';?>">

							<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >

						</a>

						<div class="col-md-12 dashboard_btn margin_top_20px">

							<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>

						</div>

					</div>

					<?php

				}

				else

				{

					?>

					<div class="calendar-event-new">

						<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

					</div>

					<?php

				}

			}
		}
		if($active_tab == 'addrecurringpayment')
		{
			$recurring_fees_id=0;
			if(isset($_REQUEST['recurring_fees_id']))
				$recurring_fees_id=$_REQUEST['recurring_fees_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_feespayment->mj_smgt_get_single_recurring_fees($recurring_fees_id);
			}
			?>
			<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				$('#selected_users').multiselect({
					nonSelectedText :"<?php esc_attr_e('Select Users','school-mgt');?>",
					includeSelectAllOption: true,
					selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
					templates: {
						button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
					},
				});
				$('.tax_charge').multiselect(
				{
					nonSelectedText: '<?php esc_html_e('Select Tax','school-mgt');?>',
					includeSelectAllOption: true,
					allSelectedText: '<?php esc_html_e('All Selected','school-mgt'); ?>',
					selectAllText: '<?php esc_html_e('Select all','school-mgt'); ?>',
					templates: {
						button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
					},
					buttonContainer: '<div class="dropdown" />'
				});
				var start = new Date();
				var end = new Date(new Date().setYear(start.getFullYear()+1));
				$("#start_date_event").datepicker(
				{
					dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
					minDate:0,
					changeMonth: true,
					changeYear: true,
					onSelect: function (selected) {
						var dt = new Date(selected);
						dt.setDate(dt.getDate() + 0);
						$("#end_date_event").datepicker("option", "minDate", dt);
					},
					beforeShow: function (textbox, instance)
					{
						instance.dpDiv.css({
							marginTop: (-textbox.offsetHeight) + 'px'
						});
					}
				});
				$("#end_date_event").datepicker(
				{
					dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
					minDate:0,
					changeMonth: true,
					changeYear: true,
				onSelect: function (selected) {
						var dt = new Date(selected);
						dt.setDate(dt.getDate() - 0);
						$("#start_date_event").datepicker("option", "maxDate", dt);
					},
					beforeShow: function (textbox, instance)
					{
						instance.dpDiv.css({
							marginTop: (-textbox.offsetHeight) + 'px'
						});
					}
				});

			});
			</script>
			<div class="panel-body margin_top_20px padding_top_15px_res"><!----- penal Body --------->
				<form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="recurring_fees_id" value="<?php echo $recurring_fees_id;?>">
					<input type="hidden" name="last_recurrence_date" value="<?php echo $result->recurring_enddate;?>">
					<input type="hidden" name="last_recurrence_date" value="<?php echo $result->recurring_enddate;?>">
					<div class="form-body user_form">
						<div class="row">
							<?php
							if($edit)
							{
							?>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 recurring_option_checkbox rtl_margin_bottom_0px">
								<div class="form-group">

									<div class="col-md-12 form-control">

										<div class="row padding_radio">

											<div class="input-group">

												<label class="custom-top-label" for="classis_limit"><?php esc_html_e('Recurrence Type','school-mgt');?></label>

												<div class="d-inline-block gender_line_height_24px">

													<?php $recurrence_type = "one_time"; if($edit){ $recurrence_type=$result->recurring_type; }elseif(isset($_POST['recurrence_type'])) {$recurrence_type=sanitize_text_field($_POST['recurrence_type']);}?>

													<label class="radio-inline">
														<input type="radio" value="weekly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('weekly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Weekly','school-mgt');?> 

													</label>
													<label class="radio-inline">

														<input type="radio" value="monthly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('monthly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Monthly','school-mgt');?>

													</label>

													<label class="radio-inline">
														<input type="radio" value="quarterly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('quarterly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Quarterly','school-mgt');?>

													</label>
													<label class="radio-inline">
														<input type="radio" value="half_yearly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('half_yearly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Half- Yearly','school-mgt');?>

													</label>

												</div>

											</div>

										</div>

									</div>

								</div>
							</div>

							<div class="col-md-6 input">
							</div>
							<?php
							}
							if($edit)
							{
							?>
								<div class="col-md-6 input">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
									<?php
									if($edit){ $classval=$result->class_id; }else{$classval='';}

									?>
									<select name="class_id" id="fees_class_list_id" class="form-control validate[required] load_fees_drop max_width_100" style="height: 47px;">
											<option value="<?php echo $classval;?>"
											<?php selected($classval, $classval);  ?>><?php echo mj_smgt_get_class_name_by_id($classval);?>
									</select>
                       			 </div>
								<div class="col-md-6 input class_section_hide">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
									<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
									<select name="class_section" class="form-control max_width_100" id="fees_class_section_id" style="height: 47px;">
										<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
										<?php
												if($edit){
													foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)
													{  ?>
										<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>>
											<?php echo $sectiondata->section_name;?></option>
										<?php }
												}?>
									</select>
								</div>

								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 single_class_div support_staff_user_div input">
									<div id="messahe_test"></div>
									<div class="col-sm-12 smgt_multiple_select rtl_padding_left_right_0px">
										<span class="user_display_block">
											<select name="selected_users[]" id="selected_users" class="form-control min_width_250px validate[required]" multiple="multiple">
											<?php
												$student_list =  get_student_by_class_id_and_section($result->class_id,$result->section_id);
												if(!empty($student_list)){
													$student_data = explode(',',$result->student_id);
													foreach($student_list  as $student_id)
													{

														$selected = "";
														if(in_array($student_id->ID,$student_data))
														{
															$selected = "selected";
														}
													?>
													<option value="<?php echo $student_id->ID; ?>" <?php echo $selected; ?>><?php echo mj_smgt_student_display_name_with_roll($student_id->ID);?></option>
													<?php

													}
												}
											?>
											</select>
										</span>
									</div>
								</div>
								<?php
							}
							?>

						<?php wp_nonce_field( 'save_payment_fees_admin_nonce' ); ?>
						<div class="col-md-6 padding_bottom_15px_res rtl_margin_top_15px">
							<div class="col-sm-12 smgt_multiple_select rtl_padding_left_right_0px">
								<select name="fees_id[]" multiple="multiple" id="fees_data" class="form-control validate[required] max_width_100">
									<?php
									if($edit)
									{
										$fees_data = mj_smgt_get_fees_by_class_id($result->class_id);
										if(!empty($fees_data))
										{
											$fees_id=explode(',',$result->fees_id);
											foreach($fees_data as $id)
											{
												if(mj_smgt_get_fees_term_name($id->fees_id) !== " ")
												{
													$selected = "";
													if(in_array($id->fees_id,$fees_id))
													{
														$selected = "selected";
													}
												?>
												<option value="<?php echo $id->fees_id;?>" <?php echo $selected; ?>><?php echo mj_smgt_get_fees_term_name($id->fees_id);?></option>
												<?php
											}
											}
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="fees_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="text" value="<?php if($edit){ echo $result->fees_amount;}elseif(isset($_POST['fees_amount'])) { echo $_POST['fees_amount']; }else{ echo "0"; }  ?>" name="fees_amount" readonly>
									<label for="userinput1" class=""><?php esc_html_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="rtl_margin_top_15px col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 multiselect_validation_member smgt_multiple_select">
							<select class="form-control tax_charge" id="tax_id" name="tax[]" multiple="multiple">
								<?php
									if($edit)
									{
										if ($result->tax !== null) {
											$tax_id = explode(',', $result->tax);
										} else {
											$tax_id[]='';
										}
									}
									else
									{
										$tax_id[]='';
									}
									$obj_tax=new tax_Manage();
									$smgt_taxs=$obj_tax->MJ_smgt_get_all_tax();
									if(!empty($smgt_taxs))
									{
										foreach($smgt_taxs as $data)
										{
											$selected = "";
											if(in_array($data->tax_id,$tax_id))
												$selected = "selected";

											?>
											<option value="<?php echo esc_attr($data->tax_id); ?>" <?php echo esc_html($selected); ?>>
											<?php echo esc_html($data->tax_title);?> - <?php echo esc_html($data->tax_value);?></option>
										<?php
										}

									}
								?>
							</select>
						</div>
						<div class="col-md-6 note_text_notice">
							<div class="form-group input">
								<div class="col-md-12 note_border margin_bottom_15px_res">
									<div class="form-field">
										<textarea name="description" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>
										<span class="txt-title-label"></span>
										<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
									</div>
								</div>
							</div>
						</div>

							<div class="col-md-6 mb-0">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="start_date_event" class="form-control date_picker validate[required] start_date datepicker1" autocomplete="off" type="text"  name="start_year" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->start_year)));}elseif(isset($_POST['start_year'])) echo mj_smgt_getdate_in_input_box($_POST['start_year']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
										<label class="active date_label" for="start"><?php esc_html_e('Start Date','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-6 mb-0">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="end_date_event" class="form-control date_picker validate[required] start_date datepicker2" type="text"  name="end_year" autocomplete="off" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->end_year)));}elseif(isset($_POST['end_year'])) echo mj_smgt_getdate_in_input_box($_POST['end_year']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
										<label class="date_label" for="end"><?php esc_html_e('End Date','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 recurring_option_checkbox rtl_margin_bottom_0px margin_15px_rtl mb-0">
								<div class="form-group">

									<div class="col-md-12 form-control">

										<div class="row padding_radio">

											<div class="input-group">

												<label class="custom-top-label" for="classis_limit"><?php esc_html_e('Status','school-mgt');?></label>

												<div class="d-inline-block gender_line_height_24px">

													<?php $status = "no"; if($edit){ $recurrence_type=$result->status; }elseif(isset($_POST['status'])) {$recurrence_type=sanitize_text_field($_POST['status']);}?>

													<label class="radio-inline">

														<input type="radio" value="yes" class="recurrence_type validate[required]" name="status" <?php checked('yes',esc_html($recurrence_type)); ?>/><?php esc_html_e('Yes','school-mgt');?>

													</label>

													<label class="radio-inline">
														<input type="radio" value="no" class="recurrence_type validate[required]" name="status" <?php checked('no',esc_html($recurrence_type)); ?>/><?php esc_html_e('No','school-mgt');?>

													</label>

												</div>

											</div>

										</div>

									</div>

								</div>
							</div>

						</div>
					</div>
					<div class="form-body user_form padding_top_15px_res">
						<div class="row">
							<div class="col-sm-6">
								<input type="submit" value="<?php if($edit){ esc_attr_e('Save Recurring Invoice','school-mgt'); }else{ esc_attr_e('Create Invoice','school-mgt');}?>" name="save_recurring_feetype_payment" onclick="return confirm('<?php esc_attr_e('Are you sure you want to edit this record? This data change in next recurring invoice details.','school-mgt');?>');" class="btn btn-success save_btn" />
							</div>
						</div>
					</div>
				</form>
			</div><!----- penal Body --------->
			<?php
		}
        ?>

        </div>

    </div>

 <?php

 ?>
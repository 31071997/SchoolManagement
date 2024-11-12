<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
$user_id=get_current_user_id();
$role_name=mj_smgt_get_user_role(get_current_user_id());
mj_smgt_browser_javascript_check();
$tablename="smgt_payment";
$obj_invoice= new Smgtinvoice();
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'paymentlist';
//--------------- ACCESS WISE ROLE -----------//
$user_access=mj_smgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		mj_smgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
//--------------- SAVE PAYMENT ---------------------//
if(isset($_POST['save_payment']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_payment_frontend_nonce' ) )
	{
		$section_id=0;
		if(isset($_POST['class_section']))
			$section_id=$_POST['class_section'];
			$created_date = date("Y-m-d H:i:s");

		if(isset($_POST['tax']))
		{
			$tax = implode(",",(array)$_POST['tax']);	
			$tax_amount = MJ_smgt_get_tax_amount($_POST['amount'],$_POST['tax']);
		}
		else
		{
			$tax=null;
			$tax_amount = 0;	
		}	
		$total_amount = $_POST['amount'] + $tax_amount;
		$payment_data=array(
			'student_id'=>mj_smgt_onlyNumberSp_validation($_POST['student_id']),
			'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['class_id']),
			'section_id'=>$section_id,
			'payment_title'=>mj_smgt_popup_category_validation($_POST['payment_title']),
			'description'=>mj_smgt_address_description_validation($_POST['description']),
			'payment_status'=>mj_smgt_popup_category_validation($_POST['payment_status']),
			'date'=>$created_date,					
			'payment_reciever_id'=>get_current_user_id(),
			'created_by'=>get_current_user_id(),
			'tax'=>$tax,
			'tax_amount'=>$tax_amount,
			'fees_amount'=>$_POST['amount'],		
			'amount'=>$total_amount			
		);
			
		if($_REQUEST['action']=='edit')
		{
			$transport_id=array('payment_id'=>$_REQUEST['payment_id']);				
			$result=mj_smgt_update_record($tablename,$payment_data,$transport_id);
			if($result){ 
				wp_redirect ( home_url() . '?dashboard=user&page=payment&tab=paymentlist&message=2');
			 }
		}
		else
		{
			$result=mj_smgt_insert_record($tablename,$payment_data);
			if($result)
				wp_redirect ( home_url() . '?dashboard=user&page=payment&tab=paymentlist&message=1');
		}
    }
}
//--------save income-------------//
if(isset($_POST['save_income']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_income_frontend_nonce' ) )
	{
		if($_REQUEST['action']=='edit')
		{
			$result=$obj_invoice->mj_smgt_add_income($_POST);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=payment&tab=incomelist&message=4');
			}
		}
		else
		{
			$result=$obj_invoice->mj_smgt_add_income($_POST);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=payment&tab=incomelist&message=3');
			}
		}
    }	
}
//--------save Expense-------------//
if(isset($_POST['save_expense']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_expense_front_nonce' ) )
	{
		if($_REQUEST['action']=='edit')
		{
			$result=$obj_invoice->mj_smgt_add_expense($_POST);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=payment&tab=expenselist&message=6');
			}
		}
		else
		{
			$result=$obj_invoice->mj_smgt_add_expense($_POST);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=payment&tab=expenselist&message=5');
			}
		}
    }
}
//----------------- DELETE RECORD ------------//
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	if(isset($_REQUEST['payment_id'])){
	$result=delete_mj_smgt_payment($tablename,$_REQUEST['payment_id']);
		if($result)
		{
			wp_redirect (home_url() . '?dashboard=user&page=payment&tab=paymentlist&message=8');
		}
	}
	if(isset($_REQUEST['income_id']))
	{
		$result=$obj_invoice->mj_smgt_delete_income($_REQUEST['income_id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=payment&tab=incomelist&message=9');
		}
	}
	if(isset($_REQUEST['expense_id']))
	{
		$result=$obj_invoice->mj_smgt_delete_expense($_REQUEST['expense_id']);
		if($result)
		{
			wp_redirect (  home_url() . '?dashboard=user&page=payment&tab=expenselist&message=7');
		}
	}
}
//---------------- DELETE MULTIPLE PAYMENT RECORD -------------//	
if(isset($_REQUEST['delete_selected_payment']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
		$result=delete_mj_smgt_payment($tablename,$id);
	if($result)
	{ 
		wp_redirect (home_url() . '?dashboard=user&page=payment&tab=paymentlist&message=8');
	}
}
//----------------- DELETE INCOME MULTIPLE RECORD ------------//
if(isset($_REQUEST['delete_selected_income']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
		$result=$obj_invoice->mj_smgt_delete_income($id);
	if($result)
	{ 
		wp_redirect ( home_url() . '?dashboard=user&page=payment&tab=incomelist&message=9');
	}
}
//----------------- DELETE EXPENSE MULTIPLE RECORD ------------//
if(isset($_REQUEST['delete_selected_expense']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
		$result=$obj_invoice->mj_smgt_delete_expense($id);
	if($result)
	{ 
		wp_redirect (  home_url() . '?dashboard=user&page=payment&tab=expenselist&message=7');
	}
}

?>
<script type="text/javascript">
jQuery(document).ready(function() {	
	$('#invoice_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('#income_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('#expense_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	
	$('#invoice_date').datepicker({
		changeMonth: true,
	    changeYear: true,
	    dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
	    yearRange:'-65:+25',
	    onChangeMonthYear: function(year, month, inst) {
	        $(this).val(month + "/" + year);
	    }
    }); 
});
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="invoice_data"></div>		 
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<div class="panel-body panel-white frontend_list_margin_30px_res"><!---------- PENAL BODY --------------->
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Payment Inserted Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Payment Updated Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Income Added successfully.','school-mgt');
			break;
		case '4':
			$message_string = esc_attr__('Income updated successfully.','school-mgt');
			break;
		case '5':
			$message_string = esc_attr__('Expense Added successfully.','school-mgt');
			break;
		case '6':
			$message_string = esc_attr__('Expense updated successfully.','school-mgt');
			break;
		case '7':
			$message_string = esc_attr__('Expense Deleted Successfully.','school-mgt');
			break;
		case '8':
			$message_string = esc_attr__('Payment Deleted Successfully.','school-mgt');
			break;
		case '9':
			$message_string = esc_attr__('Income Deleted Successfully.','school-mgt');
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
	} 
	
	if($active_tab!='view_invoice')
	{
		$page_action='';
		if(!empty($_REQUEST['action']))
		{
			$page_action = $_REQUEST['action'];
		}
		?>
		<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
			
			<li class="<?php if($active_tab=='paymentlist'){?>active<?php }?>">			
				<a href="?dashboard=user&page=payment&tab=paymentlist" class="padding_left_0 tab <?php echo $active_tab == 'paymentlist' ? 'active' : ''; ?>">
				<?php esc_html_e('Payment', 'school-mgt'); ?></a> 
			</li>
			<?php
			if($active_tab=='addinvoice' && $page_action == 'edit')
			{
				?>
				<li class="<?php if($active_tab=='addinvoice'){?>active<?php }?>">			
					<a href="?dashboard=user&page=payment&tab=addinvoice&action=edit&invoice_id=<?php echo $_REQUEST['invoice_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addinvoice' ? 'active' : ''; ?>">
					<?php esc_html_e('Edit Payment', 'school-mgt'); ?></a> 
				</li>
				<?php
			}
			elseif($active_tab=='addinvoice')
			{
				if($user_access['add']=='1')
				{
					?>
					<li class="<?php if($active_tab=='addinvoice'){?>active<?php }?>">			
						<a href="?dashboard=user&page=payment&tab=addinvoice" class="padding_left_0 tab <?php echo $active_tab == 'addinvoice' ? 'active' : ''; ?>">
						<?php esc_html_e('Add Payment', 'school-mgt'); ?></a> 
					</li>
					<?php
				}
			}
			$user_role=mj_smgt_get_roles($user_id);
			if(($user_role != 'student') AND ( $user_role != 'parent') )
			{
				?>
				<li class="<?php if($active_tab=='incomelist'){?>active<?php }?>">
					<a href="?dashboard=user&page=payment&tab=incomelist" class="padding_left_0 tab <?php echo $active_tab == 'incomelist' ? 'active' : ''; ?>">
					<?php esc_html_e('Income List', 'school-mgt'); ?></a> 
				</li> 
				<?php
				if($active_tab=='addincome' && $page_action == 'edit')
				{
					?>
					<li class="<?php if($active_tab=='addincome'){?>active<?php }?>">			
						<a href="?dashboard=user&page=payment&tab=addincome&action=edit&income_id=<?php echo $_REQUEST['income_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addincome' ? 'active' : ''; ?>">
						<?php esc_html_e('Edit Income', 'school-mgt'); ?></a> 
					</li>
					<?php
				}
				elseif($active_tab=='addincome')
				{
					if($user_access['add']=='1')
					{
						?>
						<li class="<?php if($active_tab=='addincome'){?>active<?php }?>">			
							<a href="?dashboard=user&page=payment&tab=addincome" class="padding_left_0 tab <?php echo $active_tab == 'addincome' ? 'active' : ''; ?>">
							<?php esc_html_e('Add Income', 'school-mgt'); ?></a> 
						</li>
						<?php
					}
				}

				?>
				<li class="<?php if($active_tab=='expenselist'){?>active<?php }?>">
					<a href="?dashboard=user&page=payment&tab=expenselist" class="padding_left_0 tab <?php echo $active_tab == 'expenselist' ? 'active' : ''; ?>">
					<?php esc_html_e('Expense List', 'school-mgt'); ?></a> 
				</li> 
				<?php
				if($active_tab=='addexpense' && $page_action == 'edit')
				{
					?>
					<li class="<?php if($active_tab=='addexpense'){?>active<?php }?>">			
						<a href="?dashboard=user&page=payment&tab=addexpense&action=edit&expense_id=<?php echo $_REQUEST['expense_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addexpense' ? 'active' : ''; ?>">
						<?php esc_html_e('Edit Expense', 'school-mgt'); ?></a> 
					</li>
					<?php
				}
				elseif($active_tab=='addexpense')
				{
					if($user_access['add']=='1')
					{
						?>
						<li class="<?php if($active_tab=='addexpense'){?>active<?php }?>">			
							<a href="?dashboard=user&page=payment&tab=addexpense" class="padding_left_0 tab <?php echo $active_tab == 'addexpense' ? 'active' : ''; ?>">
							<?php esc_html_e('Add Expense', 'school-mgt'); ?></a> 
						</li>
						<?php
					}
				}
			}
			?> 
		</ul>
		<?php
	}
	?>
	<div class="">
		<?php 
		//--------------------- PAYMENT LIST ------------------------//
		if($active_tab == 'paymentlist')
		{
			//------- Payment DATA FOR STUDENT ---------//
			if($school_obj->role == 'student')
			{
				$data=$school_obj->payment_list;
			}

			//------- Payment DATA FOR PARENT ---------//
			elseif($school_obj->role == 'parent')
			{
				$data=$school_obj->payment_list;
			}
			//------- Payment DATA FOR SUPPORT STAFF ---------//
			else
			{ 
				$own_data=$user_access['own_data'];
				if($own_data == '1')
				{ 
					$data	= $obj_invoice->mj_smgt_get_invoice_created_by($user_id);
				}
				else
				{
					$data=$school_obj->payment_list;
				}
			} 
			if(!empty($data))
			{
				?>
				<script>
					jQuery(document).ready(function() 
					{
						var table =  jQuery('#payment_list').DataTable({
							"initComplete": function(settings, json) {
								$(".print-button").css({"margin-top": "-5%"});
							},
							//stateSave: true,
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
									{"bSortable": true},	                 	                  
									{"bSortable": true}, 	                 	                  
									{"bSortable": false}],
							language:<?php echo mj_smgt_datatable_multi_language();?>
						});
						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
						jQuery('#checkbox-select-all').on('click', function()
						{
							var rows = table.rows({ 'search': 'applied' }).nodes();
							jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
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
						//---------- delete selected js ---------------//
						$("#delete_selected").on('click', function()
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
					
					});
				</script>
				<div class="panel-body"><!-------------- PENAL BODY ------------>
					<div class="table-responsive"><!---------------- TABLE RESPONSIVE ---------------->
						<!--------------- PAYMENT LIST FORM ----------------->
						<form id="frm-example" name="frm-example" method="post">
							<table id="payment_list" class="display dataTable" cellspacing="0" width="100%">
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
										<th><?php esc_attr_e('Student Name','school-mgt');?></th>
										<th><?php esc_attr_e('Class Name','school-mgt');?> </th>
										<th><?php esc_attr_e('Payment Title','school-mgt');?></th>
										<th><?php esc_attr_e('Amount','school-mgt');?></th>
										<th><?php esc_attr_e('Status','school-mgt');?></th>
										<th><?php esc_attr_e('Date','school-mgt');?></th>
										<th><?php esc_attr_e('Created By','school-mgt');?></th>
										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i=0;
									foreach ($data as $retrieved_data)
									{			
										$class_id=get_user_meta(get_current_user_id(), 'class_name',true);

										$section_name=get_user_meta($retrieved_data->student_id, 'class_section',true);
										
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
												<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->payment_id;?>"></td>
												<?php
											}
											?>
											<td class="user_image width_50px profile_image_prescription padding_left_0">
												<a href="?dashboard=user&page=payment&tab=view_invoice&idtest=<?php echo $retrieved_data->payment_id; ?>&invoice_type=invoice" class="" >
													<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
													</p>
												</a>
											</td>
											<td>
												<?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
											</td>
											<td><?php echo smgt_get_class_section_name_wise($retrieved_data->class_id,$section_name);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
											<td><?php echo stripslashes($retrieved_data->payment_title);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Payment Title','school-mgt');?>"></i></td>               
											<td><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->amount,2,'.',''));?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Amount','school-mgt');?>"></i></td>
											<td><?php 
													if($retrieved_data->payment_status=='Paid') 
														echo "<span class='green_color'> " .esc_attr__('Fully Paid','school-mgt')." </span>";
													elseif($retrieved_data->payment_status=='Part Paid')
														echo "<span class='perpal_color'> " .esc_attr__('Partially Paid','school-mgt')." </span>";
													else
														echo "<span class='red_color'> " .esc_attr__('Not Paid','school-mgt')." </span>";	?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>
											<td><?php  echo mj_smgt_getdate_in_input_box($retrieved_data->date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>         
											<td class="status"><?php echo mj_smgt_get_display_name($retrieved_data->created_by);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Created By','school-mgt');?>"></i></td>
											<td class="action">  
												<div class="smgt-user-dropdown">
													<ul class="" style="margin-bottom: 0px !important;">
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
															</a>
															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																
																<li class="float_left_width_100 ">
																	<a href="?dashboard=user&page=payment&tab=view_invoice&idtest=<?php echo $retrieved_data->payment_id; ?>&invoice_type=invoice" class="float_left_width_100" ><i class="fa fa-eye"></i> <?php esc_attr_e('View Invoice','school-mgt');?></a>
																</li>
																
																<?php
																if($user_access['edit']=='1')
																{
																	?>
																	<li class="float_left_width_100 border_bottom_menu">
																		<a href="?dashboard=user&page=payment&tab=addinvoice&action=edit&payment_id=<?php echo $retrieved_data->payment_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																	</li>
																	<?php 
																} 
																if($user_access['delete']=='1')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=payment&tab=paymentlist&action=delete&payment_id=<?php echo $retrieved_data->payment_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
										<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
									</button>
									<?php
									if($school_obj->role == 'supportstaff')
									{
										if($user_access['delete']=='1')
										{ 
											?>
											<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_payment" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
											<?php
										}
									}
									?>
								</div>
								<?php
							}
							?>
						</form><!--------------- PAYMENT LIST FORM ----------------->
					</div><!---------------- TABLE RESPONSIVE ---------------->
				</div><!-------------- PENAL BODY ------------>
				<?php 
			}
			else
			{
				if($user_access['add']=='1')
				{
					?>
					<div class="no_data_list_div no_data_img_mt_30px"> 
						<a href="<?php echo home_url().'?dashboard=user&page=payment&tab=addinvoice';?>">
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
		if($active_tab == 'addinvoice')
		{
			?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					$('#payment_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
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
				} );
			</script>
			<?php 
			$edit = 0;
			if (isset ( $_REQUEST ['action'] ) && $_REQUEST ['action'] == 'edit') 
			{
				$edit = 1;
				$payment_data = mj_smgt_get_payment_by_id($_REQUEST['payment_id']);
			} 
			?>
			<div class="panel-body"><!------------- PENAL BODY -------------->
				<!---------------- PAYMENT ADD FORM ----------------->
				<form name="payment_form" action="" method="post" class="form-horizontal" id="payment_form">
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<div class="header">	
						<h3 class="first_hed"><?php esc_html_e('Payment Information','school-mgt');?></h3>
					</div>
					<div class="form-body user_form"><!--------- Form Body --------->
						<div class="row"><!--------- Row Div --------->
							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="payment_title" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $payment_data->payment_title;}?>" name="payment_title"/>
										<label for="userinput1" class=""><?php esc_html_e('Title','school-mgt');?><span class="required">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-6 input error_msg_left_margin">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
								<?php
								if($edit){ $classval=$payment_data->class_id; }else{$classval='';}?>
								<select name="class_id" id="class_list" class="line_height_30px form-control validate[required] max_width_100">
									<?php if($addparent){ 
											$classdata=mj_smgt_get_class_by_id($student->class_name);
										?>
									<option value="<?php echo $student->class_name;?>" ><?php echo $classdata->class_name;?></option>
									<?php }?>
									<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
										<?php
										foreach(mj_smgt_get_allclass() as $classdata)
										{ ?>
											<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
									<?php }?>
								</select>                           
							</div>
							<div class="col-md-6 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
								<?php if($edit){ $sectionval=$payment_data->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
								<select name="class_section" class="line_height_30px form-control max_width_100" id="class_section">
									<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
									<?php
									if($edit){
										foreach(mj_smgt_get_class_sections($payment_data->class_id) as $sectiondata)
										{  ?>
											<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
									<?php } 
									}?>
								</select>                         
							</div>
							<div class="col-md-6 input error_msg_left_margin">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?><span class="require-field">*</span></label>
								<?php if($edit){ $classval=$payment_data->class_id; }else{$classval='';}?>
								<select name="student_id" id="student_list" class="line_height_30px form-control validate[required] max_width_100">
									<?php if(isset($payment_data->student_id)){ 
									$student=get_userdata($payment_data->student_id);
									?>
									<option value="<?php echo $payment_data->student_id;?>" ><?php echo $student->first_name." ".$student->last_name;?></option>
									<?php }
									else
									{?>
										<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
									<?php } ?>
								</select>                       
							</div>
							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="amount" class="form-control validate[required,min[0],maxSize[12]]" type="number" step="0.01" value="<?php if($edit){ echo $payment_data->fees_amount;}?>" name="amount">
										<label for="userinput1" class=""><?php esc_html_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="required">*</span></label>
									</div>
								</div>
							</div>
							<div class="rtl_margin_top_15px col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 multiselect_validation_member smgt_multiple_select">
								<select class="form-control tax_charge" id="tax_id" name="tax[]" multiple="multiple">
									<?php
										if($edit)
										{
											if ($payment_data->tax !== null) {
												$tax_id = explode(',', $payment_data->tax);
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
							<?php wp_nonce_field( 'save_payment_frontend_nonce' ); ?>
							<div class="col-md-6 input error_msg_left_margin">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Status','school-mgt');?></label>
								<select name="payment_status" id="payment_status" class="line_height_30px form-control max_width_100">
									<option value="Paid"
										<?php if($edit)selected('Paid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Paid','school-mgt');?></option>
									<option value="Part Paid"
										<?php if($edit)selected('Part Paid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Part Paid','school-mgt');?></option>
									<option value="Unpaid"
										<?php if($edit)selected('Unpaid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Unpaid','school-mgt');?></option>
								</select>                    
							</div>
							<div class="col-md-6 note_text_notice">
								<div class="form-group input">
									<div class="col-md-12 note_border margin_bottom_15px_res">
										<div class="form-field">
											<textarea name="description" id="description" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150"><?php if($edit){ echo $payment_data->description;}?></textarea>
											<span class="txt-title-label"></span>
											<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
						</div><!--------- Row Div --------->
					</div><!--------- Form Body --------->
					<div class="form-body user_form">
						<div class="row">
							<div class="col-sm-6">        	
								<input type="submit" value="<?php if($edit){ esc_attr_e('Save Payment','school-mgt'); }else{ esc_attr_e('Add Payment','school-mgt');}?>" name="save_payment" class="btn btn-success save_btn" />
							</div>
						</div>
					</div>
				</form><!---------------- PAYMENT ADD FORM ----------------->
			</div><!------------- PENAL BODY -------------->
			<?php 
		}
		//--------------------- INCOME LIST ------------------------//
		if($active_tab == 'incomelist')
		{ 
			$user_id=get_current_user_id();
			if($school_obj->role == 'supportstaff')
			{
				$own_data=$user_access['own_data'];
				if($own_data == '1')
				{
				$all_income_data=$obj_invoice->mj_smgt_get_income_data_created_by($user_id);
				}		
				else
				{
					$all_income_data=$obj_invoice->mj_smgt_get_all_income_data();
				}								 
			}
			else
			{
				$all_income_data=$obj_invoice->mj_smgt_get_all_income_data();
			}
			if(!empty($all_income_data))
			{
				?>
				<script type="text/javascript">
					jQuery(document).ready(function() 
					{
						var table = jQuery('#tblincome').DataTable({
							"initComplete": function(settings, json) {
								$(".print-button").css({"margin-top": "-5%"});
							},
							//stateSave: true,
							"order": [[ 4, "Desc" ]],
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
									{"bSortable": false}
								],
							language:<?php echo mj_smgt_datatable_multi_language();?>
						});
						jQuery('#checkbox-select-all').on('click', function()
						{
							var rows = table.rows({ 'search': 'applied' }).nodes();
							jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
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
						$("#delete_selected").on('click', function()
						{	
							if ($('.select-checkbox:checked').length == 0 )
							{
								alert("<?php esc_html_e('Please select atleast one record','school-mgt');?>");
								return false;
							}
							else{
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
					});
				</script>
				<div class="panel-body"><!------------- PENAL BODY --------------->
					<div class="table-responsive"><!------------ TABLE RESPONSIVE ----------------->
						<!--------------- INCOME LIST FORM ------------------>
						<form id="frm-example" name="frm-example" method="post">
							<table id="tblincome" class="display" cellspacing="0" width="100%">
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
										<th> <?php esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>
										<th> <?php esc_attr_e( 'Total Amount', 'school-mgt' ) ;?></th>
										<th><?php esc_attr_e('Status','school-mgt');?></th>
									<th> <?php esc_attr_e( 'Date', 'school-mgt' ) ;?></th>
									<th> <?php esc_attr_e( 'Created By', 'school-mgt' ) ;?></th>
										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i=0;
									foreach ($all_income_data as $retrieved_data)
									{ 
										$all_entry=json_decode($retrieved_data->entry);
										$total_amount=0;
										foreach($all_entry as $entry)
										{
											$total_amount+=$entry->amount;
										}
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
													<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->income_id;?>"></td>
												<?php
											}
											?>
											<td class="user_image width_50px profile_image_prescription padding_left_0">
												<a href="?dashboard=user&page=payment&tab=view_invoice&idtest=<?php echo $retrieved_data->income_id; ?>&invoice_type=income" class="" >
													<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
													</p>
												</a>
											</td>
											<td class="patient_name">
													<?php echo mj_smgt_student_display_name_with_roll($retrieved_data->supplier_name);?> 
												<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
											</td>
											<td class="income_amount"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($total_amount,2,'.',''));?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>
											<td><?php 
											if($retrieved_data->payment_status == 'Paid') 
											{
													echo "<span class='green_color'> " .esc_attr__('Fully Paid','school-mgt')." </span>";
											}
											elseif($retrieved_data->payment_status=='Part Paid')
											{
												echo "<span class='perpal_color'> " .esc_attr__('Partially Paid','school-mgt')." </span>";
											}
											else
											{
												echo "<span class='red_color'> " .esc_attr__('Not Paid','school-mgt')." </span>";
											}
											?>
											 <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>
									<td class="status"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->income_create_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>
									<td class="status"><?php echo mj_smgt_get_display_name($retrieved_data->create_by);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Created By','school-mgt');?>"></i></td>
											<td class="action">  
												<div class="smgt-user-dropdown">
													<ul class="" style="margin-bottom: 0px !important;">
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
															</a>
															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																
																<li class="float_left_width_100 ">
																	<a href="?dashboard=user&page=payment&tab=view_invoice&idtest=<?php echo $retrieved_data->income_id; ?>&invoice_type=income" class="float_left_width_100" ><i class="fa fa-eye"></i> <?php esc_attr_e('View Invoice','school-mgt');?></a>
																</li>
																
																<?php
																if($user_access['edit']=='1')
																{
																	?>
																	<li class="float_left_width_100 border_bottom_menu">
																		<a href="?dashboard=user&page=payment&tab=addincome&action=edit&income_id=<?php echo $retrieved_data->income_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																	</li>
																	<?php 
																} 
																if($user_access['delete']=='1')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=payment&tab=incomelist&action=delete&income_id=<?php echo $retrieved_data->income_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
										<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
									</button>
									<?php 
									if($user_access['delete']=='1')
									{ 
										?>
										<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_income" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</form><!--------------- INCOME LIST FORM ------------------>
					</div><!------------ TABLE RESPONSIVE ----------------->
				</div><!------------- PENAL BODY --------------->
				<?php
			}
			else
			{
				if($user_access['add']=='1')
				{
					?>
					<div class="no_data_list_div no_data_img_mt_30px"> 
						<a href="<?php echo home_url().'?dashboard=user&page=payment&tab=addincome';?>">
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
        if($active_tab == 'addincome')
        {
            $income_id=0;
			if(isset($_REQUEST['income_id']))
				$income_id=$_REQUEST['income_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = $obj_invoice->mj_smgt_get_income_data($income_id);
				}?>
			<div class="panel-body">
				<form name="income_form" action="" method="post" class="mt-3 form-horizontal" id="income_form">
			 		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="income_id" value="<?php echo $income_id;?>">
					<input type="hidden" name="invoice_type" value="income">
					<div class="header">	
						<h3 class="first_hed margin_top_0px_image"><?php esc_html_e('Income Information','school-mgt');?></h3>
					</div>
					<div class="form-body user_form"><!--------- Form Body --------->
						<div class="row"><!--------- Row Div --------->
							<div class="col-md-6 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
								<?php
								if($edit){ $classval=$result->class_id; }else{$classval='';}?>
								<select name="class_id" id="class_list" class="line_height_30px form-control validate[required] max_width_100">
									<?php if($addparent)
									{ 
										$classdata=mj_smgt_get_class_by_id($student->class_name);
										?>
										<option value="<?php echo $student->class_name;?>" ><?php echo $classdata->class_name;?></option>
										<?php 
									}
									?>
									<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
										<?php
											foreach(mj_smgt_get_allclass() as $classdata)
											{ ?>
											<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
									<?php } ?>
								</select>                        
							</div>
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
											} ?>
								</select>                        
							</div>
							<div class="col-md-6 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?><span class="require-field">*</span></label>
								<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>                     
								<select name="supplier_name" id="student_list" class="line_height_30px form-control validate[required] max_width_100">                    
									<?php if(isset($result->supplier_name))
									{ 
										$student=get_userdata($result->supplier_name);
										?>
										<option value="<?php echo $result->supplier_name;?>" ><?php echo $student->first_name." ".$student->last_name;?></option>
										<?php 
									}
									else
									{ 
										?>
										<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
										<?php 
									} ?>
								</select>                    
							</div>
							<div class="col-md-6 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Status','school-mgt');?></label>
								<select name="payment_status" id="payment_status" class="line_height_30px form-control validate[required] max_width_100">
									<option value="Paid"
										<?php if($edit)selected('Paid',$result->payment_status);?> ><?php esc_attr_e('Paid','school-mgt');?></option>
									<option value="Part Paid"
										<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php esc_attr_e('Part Paid','school-mgt');?></option>
									<option value="Unpaid"
										<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php esc_attr_e('Unpaid','school-mgt');?></option>
								</select>                 
							</div>
							<?php wp_nonce_field( 'save_income_frontend_nonce' ); ?>
							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="invoice_date" class="form-control " type="text"  value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->income_create_date);}elseif(isset($_POST['invoice_date'])){ echo mj_smgt_getdate_in_input_box($_POST['invoice_date']);}else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d"));}?>" name="invoice_date" readonly>
										<label for="userinput1" class=""><?php esc_html_e('Date','school-mgt');?><span class="required">*</span></label>
									</div>
								</div>
							</div>
						</div><!--------- Row Div --------->
					</div><!--------- Form Body --------->
					<hr>
					<div class="header">	
						<h3 class="first_hed margin_top_0px_image"><?php esc_html_e('Income Entry','school-mgt');?></h3>
					</div>
					<div id="income_entry_main">
						<?php 			
						if($edit)
						{
							$all_entry=json_decode($result->entry);
						}
						else
						{
							if(isset($_POST['income_entry']))
							{					
								$all_data=$obj_invoice->mj_smgt_get_entry_records($_POST);
								$all_entry=json_decode($all_data);
							}					
						}
						if(!empty($all_entry))
						{
							$i=0;
							foreach($all_entry as $entry)
							{
								?>
								<div id="income_entry">
									<div class="form-body user_form income_fld">
										<div class="row">
											<div class="col-md-3">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="income_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]">
														<label for="userinput1" class=""><?php esc_html_e('Income Amount','school-mgt');?><span class="required">*</span></label>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="income_entry" class="form-control btn_top validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
														<label for="userinput1" class=""><?php esc_html_e('Income Entry Label','school-mgt');?><span class="required">*</span></label>
													</div>
												</div>
											</div>
											<?php
											if($i == 0 )
											{ 
												?>
												<div class="col-md-2 symptoms_deopdown_div">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="rtl_margin_top_15px daye_name_onclickr" id="add_new_entry">
												</div>
												<?php
											}
											else
											{
												?>
												<div class="col-md-2 symptoms_deopdown_div">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" onclick="deleteParentElement(this)" alt="" class="rtl_margin_top_15px">
												</div>
												<?php
											}
											?>
										</div>
									</div>
								</div>
								<?php 
								$i++;
							}				
						}
						else
						{?>
							<div id="income_entry">
								<div class="form-body user_form income_fld">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group input">
												<div class="col-md-12 form-control">
													<input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]">
													<label for="userinput1" class=""><?php esc_html_e('Income Amount','school-mgt');?><span class="required">*</span></label>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group input">
												<div class="col-md-12 form-control">
													<input id="income_entry" class="form-control btn_top validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]">
													<label for="userinput1" class=""><?php esc_html_e('Income Entry Label','school-mgt');?><span class="required">*</span></label>
												</div>
											</div>
										</div>
										<div class="col-md-2 symptoms_deopdown_div">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="rtl_margin_top_15px daye_name_onclickr" id="add_new_entry">
										</div>
									</div>
								</div>
							</div>
							<?php 
						} ?>
					</div>
					<hr>
					<div class="form-body user_form income_fld">
						<div class="row">
							<div class="col-sm-6">
								<input type="submit" value="<?php if($edit){ esc_attr_e('Save Income','school-mgt'); }else{ esc_attr_e('Create Income Entry','school-mgt');}?>" name="save_income" class="btn btn-success save_btn"/>
							</div>
						</div>
					</div>
        		</form>
        	</div>
			<script>
				// CREATING BLANK INVOICE ENTRY
				var blank_income_entry ='';
				$(document).ready(function() 
				{
					
				blank_income_entry = '<div class="padding_top_15px_res form-body user_form income_fld"><div class="row"><div class="col-md-3"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]"><label for="userinput1" class="active "><?php esc_html_e('Income Amount','school-mgt');?><span class="required">*</span></label></div></div></div><div class="col-md-3"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_entry" class="form-control btn_top validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]"><label for="userinput1" class="active "><?php esc_html_e('Income Entry Label','school-mgt');?><span class="required">*</span></label></div></div></div><div class="col-md-2 symptoms_deopdown_div"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" onclick="deleteParentElement(this)" alt="" class="rtl_margin_top_15px"></div></div></div>';			
				}); 

				function add_entry()
				{
					
					$("#income_entry_main").append(blank_income_entry);		
				}
				// REMOVING INVOICE ENTRY
				function deleteParentElement(n)
				{
					var alert = confirm(language_translate2.delete_record_alert);
					if (alert == true){
						n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
					}
					else{

					}
				}
			</script> 
			<?php 
		}
		//--------------------- EXPENSE LIST ------------------------//
		if($active_tab == 'expenselist')
		{
			$user_id=get_current_user_id();
			if($school_obj->role == 'supportstaff')
			{
				$own_data=$user_access['own_data'];
				if($own_data == '1')
				{
				$all_expense_data=$obj_invoice->mj_smgt_get_all_expense_data_created_by($user_id);
				}		
				else
				{
					$all_expense_data=$obj_invoice->mj_smgt_get_all_expense_data();
				}								 
			}
			else
			{
				$all_expense_data=$obj_invoice->mj_smgt_get_all_expense_data();
			}
			if(!empty($all_expense_data))
			{
				$invoice_id=0;
				?>
				<script type="text/javascript">
					$(document).ready(function() 
					{
						var table = jQuery('#tblexpence').DataTable(
						{
							"initComplete": function(settings, json) {
								$(".print-button").css({"margin-top": "-5%"});
							},
							//stateSave: true,
							"order": [[ 2, "Desc" ]],
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
								{"bSortable": false}
							],
							language:<?php echo mj_smgt_datatable_multi_language();?>
						});
						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
						jQuery('#checkbox-select-all').on('click', function(){     
							var rows = table.rows({ 'search': 'applied' }).nodes();
							jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
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
						$("#delete_selected").on('click', function()
						{	
							if ($('.select-checkbox:checked').length == 0 )
							{
								alert("<?php esc_html_e('Please select atleast one record','school-mgt');?>");
								return false;
							}
							else{
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
					});
				</script>
				<div class="panel-body"><!-------------- PENAL BODY --------------->
					<div class="table-responsive"><!-------------- TABLE RESPONSIVE -------------->
						<!-------------- EXPENSE LIST FORM ------------------>
						<form id="frm-example" name="frm-example" method="post">
							<table id="tblexpence" class="display expense_datatable" cellspacing="0" width="100%">
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
										<th> <?php esc_attr_e( 'Supplier Name', 'school-mgt' ) ;?></th>
										<th> <?php esc_attr_e( 'Total Amount', 'school-mgt' ) ;?></th>
										<th> <?php esc_attr_e( 'Status', 'school-mgt' ) ;?></th>
										<th> <?php esc_attr_e( 'Date', 'school-mgt' ) ;?></th>
										<th> <?php esc_attr_e( 'Created By', 'school-mgt' ) ;?></th>
										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i=0;
									foreach ($all_expense_data as $retrieved_data)
									{ 
										$all_entry=json_decode($retrieved_data->entry);
							
										$total_amount=0;
										foreach($all_entry as $entry)
										{
											$total_amount += $entry->amount;
										}
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
													<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->income_id;?>"></td>
												<?php
											}
											?>
											<td class="user_image width_50px profile_image_prescription padding_left_0">
												<a href="?dashboard=user&page=payment&tab=view_invoice&idtest=<?php echo $retrieved_data->income_id; ?>&invoice_type=expense" class="" >
													<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
													</p>
												</a>
											</td>
											<td class="patient_name"><?php echo $retrieved_data->supplier_name;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Supplier Name','school-mgt');?>"></i></td>
											<td class="income_amount"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($total_amount,2,'.',''));?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>
											<td>
									<?php 
									if($retrieved_data->payment_status == 'Paid') 
									{
											echo "<span class='green_color'> " .esc_attr__('Fully Paid','school-mgt')." </span>";
									}
									elseif($retrieved_data->payment_status=='Part Paid')
									{
										echo "<span class='perpal_color'> " .esc_attr__('Partially Paid','school-mgt')." </span>";
									}
									else
									{
										echo "<span class='red_color'> " .esc_attr__('Not Paid','school-mgt')." </span>";
									}
									?>
								    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>
									<td class="status"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->income_create_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>
									<td class="status"><?php echo mj_smgt_get_display_name($retrieved_data->create_by);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Created By','school-mgt');?>"></i></td>
											<td class="action">  
												<div class="smgt-user-dropdown">
													<ul class="" style="margin-bottom: 0px !important;">
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
															</a>
															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																
																<li class="float_left_width_100 ">
																	<a href="?dashboard=user&page=payment&tab=view_invoice&idtest=<?php echo $retrieved_data->income_id; ?>&invoice_type=expense" class="float_left_width_100" ><i class="fa fa-eye"></i> <?php esc_attr_e('View Invoice','school-mgt');?></a>
																</li>
																
																<?php
																if($user_access['edit']=='1')
																{
																	?>
																	<li class="float_left_width_100 border_bottom_menu">
																		<a href="?dashboard=user&page=payment&tab=addexpense&action=edit&expense_id=<?php echo $retrieved_data->income_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																	</li>
																	<?php 
																} 
																if($user_access['delete']=='1')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=payment&tab=expenselist&action=delete&expense_id=<?php echo $retrieved_data->income_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
										<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
									</button>
									<?php 
									if($user_access['delete']=='1')
									{ 
										?>
										<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_expense" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</form><!-------------- EXPENSE LIST FORM ------------------>
					</div><!-------------- TABLE RESPONSIVE -------------->
				</div><!-------------- PENAL BODY --------------->
				<?php 
			}
			else
			{
				if($user_access['add']=='1')
				{
					?>
					<div class="no_data_list_div no_data_img_mt_30px"> 
						<a href="<?php echo home_url().'?dashboard=user&page=payment&tab=addexpense';?>">
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
		if($active_tab == 'addexpense')
		{
			$expense_id=0;
			if(isset($_REQUEST['expense_id']))
				$expense_id=$_REQUEST['expense_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					$edit=1;
					$result = $obj_invoice->mj_smgt_get_income_data($expense_id);
			}?>
			<div class="panel-body">
				<form name="expense_form" action="" method="post" class="mt-3 form-horizontal" id="expense_form">
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="expense_id" value="<?php echo $expense_id;?>">
					<input type="hidden" name="invoice_type" value="expense">
					<div class="header">	
						<h3 class="first_hed margin_top_0px_image"><?php esc_html_e('Expense Information','school-mgt');?></h3>
					</div>
					<div class="form-body user_form"><!--------- Form Body --------->
						<div class="row"><!--------- Row Div --------->
							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="supplier_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->supplier_name;}elseif(isset($_POST['supplier_name'])) echo $_POST['supplier_name'];?>" name="supplier_name">
										<label for="userinput1" class=""><?php esc_html_e('Supplier Name','school-mgt');?><span class="required">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-6 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Status','school-mgt');?></label>
								<select name="payment_status" id="payment_status" class="line_height_30px form-control validate[required] max_width_100">
									<option value="Paid"
										<?php if($edit)selected('Paid',$result->payment_status);?> ><?php esc_attr_e('Paid','school-mgt');?></option>
									<option value="Part Paid"
										<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php esc_attr_e('Part Paid','school-mgt');?></option>
									<option value="Unpaid"
										<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php esc_attr_e('Unpaid','school-mgt');?></option>
								</select>              
							</div>
							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="invoice_date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->income_create_date);}elseif(isset($_POST['invoice_date'])){ echo mj_smgt_getdate_in_input_box($_POST['invoice_date']);}else{ echo  mj_smgt_getdate_in_input_box(date("Y-m-d"));}?>" name="invoice_date" readonly>
										<label for="userinput1" class=""><?php esc_html_e('Date','school-mgt');?><span class="required">*</span></label>
									</div>
								</div>
							</div>
						</div><!--------- Row Div --------->
					</div><!--------- Form Body --------->
					<hr>
					<div id="expense_entry_main">
						<?php 			
						if($edit)
						{
							$all_entry=json_decode($result->entry);
						}
						else
						{
							if(isset($_POST['income_entry']))
							{					
								$all_data=$obj_invoice->mj_smgt_get_entry_records($_POST);
								$all_entry=json_decode($all_data);
							}					
						}
						if(!empty($all_entry))
						{
							$i=0;
							foreach($all_entry as $entry)
							{ ?>
								<div id="expense_entry">
									<div class="form-body user_form income_fld">
										<div class="row">
											<div class="col-md-3">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="income_amount" class="form-control btn_top amt validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]" >
														<label for="userinput1" class=""><?php esc_html_e('Expense Amount','school-mgt');?><span class="required">*</span></label>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="income_entry" class="form-control entry btn_top validate[required,custom[description_validation]] text-input" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
														<label for="userinput1" class=""><?php esc_html_e('Expense Entry Label','school-mgt');?><span class="required">*</span></label>
													</div>
												</div>
											</div>
											<?php
											if($i == 0 )
											{ 
												?>
												<div class="col-md-2 symptoms_deopdown_div">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="rtl_margin_top_15px daye_name_onclickr" id="add_new_entry">
												</div>
												<?php
											}
											else
											{
												?>
												<div class="col-md-2 symptoms_deopdown_div">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" onclick="deleteParentElement(this)" alt="" class="rtl_margin_top_15px">
												</div>
												<?php
											}
											?>
										</div>
									</div>
								</div>
								<?php 
								$i++; 
							}				
						}
						else 
						{ 
							?>
							<div id="expense_entry">
								<div class="form-body user_form income_fld">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group input">
												<div class="col-md-12 form-control">
													<input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]">
													<label for="userinput1" class=""><?php esc_html_e('Expense Amount','school-mgt');?><span class="required">*</span></label>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group input">
												<div class="col-md-12 form-control">
													<input id="income_entry" class="form-control btn_top validate[required,custom[description_validation]] text-input" maxlength="50" type="text" value="" name="income_entry[]">
													<label for="userinput1" class=""><?php esc_html_e('Expense Entry Label','school-mgt');?><span class="required">*</span></label>
												</div>
											</div>
										</div>
										<div class="col-md-2 symptoms_deopdown_div">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="rtl_margin_top_15px daye_name_onclickr" id="add_new_entry">
										</div>
									</div>
								</div>
							</div>					
							<?php 
						} 
						?>
					</div>
					<?php wp_nonce_field( 'save_expense_front_nonce' ); ?>
					<hr>
					<div class="form-body user_form">
						<div class="row">
							<div class="col-sm-6">
								<input type="submit" value="<?php if($edit){ esc_attr_e('Save Expense','school-mgt'); }else{ esc_attr_e('Create Expense Entry','school-mgt');}?>" name="save_expense" class="btn btn-success save_btn"/>
							</div>
						</div>
					</div>
				</form>
			</div>
			<script>
				// CREATING BLANK INVOICE ENTRY
				$(document).ready(function() 
				{
					blank_expense_entry = '<div class="padding_top_15px_res form-body user_form income_fld"><div class="row"><div class="col-md-3"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]"><label for="userinput1" class="active "><?php esc_html_e('Expense Amount','school-mgt');?><span class="required">*</span></label></div></div></div><div class="col-md-3"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_entry" class="form-control btn_top validate[required,custom[description_validation]] text-input" maxlength="50" type="text" value="" name="income_entry[]"><label for="userinput1" class="active "><?php esc_html_e('Expense Entry Label','school-mgt');?><span class="required">*</span></label></div></div></div><div class="col-md-2 symptoms_deopdown_div"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" onclick="deleteParentElement(this)" alt="" class="rtl_margin_top_15px"></div></div></div>';			
				}); 

				function add_entry()
				{
					$("#expense_entry_main").append(blank_expense_entry);		
				}
				// REMOVING INVOICE ENTRY
				function deleteParentElement(n)
				{
					var alert = confirm(language_translate2.delete_record_alert);
					if (alert == true){
						n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
					}
					else{

					}
				}
			</script> 
			<?php 
		}
		if($active_tab == 'view_invoice')
		{
			$obj_invoice= new Smgtinvoice();
			if($_REQUEST['invoice_type']=='invoice')
			{
				$invoice_data=mj_smgt_get_payment_by_id($_REQUEST['idtest']);
			}
			if($_REQUEST['invoice_type']=='income'){
				$income_data=$obj_invoice->mj_smgt_get_income_data($_REQUEST['idtest']);
			}
			if($_REQUEST['invoice_type']=='expense'){
				$expense_data=$obj_invoice->mj_smgt_get_income_data($_REQUEST['idtest']);	
			} ?>
			<div class="penal-body"><!----- penal Body --------->
				<div id="Payment_invoice"><!----- Payment Invoice --------->
					<div id="rs_invoice_view_mt_15" class="modal-body border_invoice_page margin_top_15px_rs invoice_model_body float_left_width_100 height_600px"><!---- model body  ----->
						<img class="rtl_image_set_invoice invoiceimage image_width_98px float_left invoice_image_model"  src="<?php echo plugins_url('/school-management/assets/images/listpage_icon/invoice.png'); ?>" width="100%">
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
												<label class="popup_label_heading"><?php esc_html_e('Address','school-mgt'); ?>
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
										<div class="col-md-8 col-sm-8 col-xs-5 padding_0 float_left display_grid display_inherit_res margin_bottom_20px rs_main_billed_to">
											<div class="billed_to display_flex invoice_address_heading rs_width_billed_to">								
												<?php
												$issue_date='DD-MM-YYYY';
												if(!empty($income_data))
												{
													$issue_date=$income_data->income_create_date;
													$payment_status=$income_data->payment_status;
												}
												if(!empty($invoice_data))
												{
													$issue_date=$invoice_data->date;
													$payment_status=$invoice_data->payment_status;	
												}
												if(!empty($expense_data))
												{
													$issue_date=$expense_data->income_create_date;
													$payment_status=$expense_data->payment_status;
												}
												?>
												<h3 class="billed_to_lable invoice_model_heading bill_to_width_12 rs_bill_to_width_40"><?php esc_html_e('Bill To','school-mgt');?> : </h3>
												
												<?php
												if(!empty($expense_data))
												{
													$party_name=$expense_data->supplier_name; 
													echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($party_name),30,"<BR>"). "</h3>";
												}
												else{
													if(!empty($income_data))
														$student_id=$income_data->supplier_name;
													if(!empty($invoice_data))
														$student_id=$invoice_data->student_id;
													$patient=get_userdata($student_id);						
													echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
												}
												?>
											</div>
											<div class="width_60b2 address_information_invoice">								
												<?php 	
												if(!empty($expense_data))
												{
													// $party_name=$expense_data->supplier_name; 
													// echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($party_name),30,"<BR>"). "</h3>";
												}
												else
												{
													if(!empty($income_data))
														$student_id=$income_data->supplier_name;
													if(!empty($invoice_data))
														$student_id=$invoice_data->student_id;
													$patient=get_userdata($student_id);						
													// echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
													$address=get_user_meta( $student_id,'address',true );

													echo chunk_split($address,30,"<BR>"); 
													echo get_user_meta( $student_id,'city',true ).","."<BR>"; ; 
													echo get_user_meta( $student_id,'zip_code',true ).",<BR>"; 
												}		
												?>	
												</div>
											</div>
											<div class="col-md-3 col-sm-4 col-xs-7 float_left">
												<div class="width_50a1112">
													<div class="width_20c" align="center">
														<?php
														if(!empty($invoice_data))
														{
															
														}
														?>
														<h5 class="align_left"> <label class="popup_label_heading text-transfer-upercase"><?php   echo esc_html__('Date :','school-mgt') ?> </label>&nbsp;  <label class="invoice_model_value"><?php echo mj_smgt_getdate_in_input_box(date("Y-m-d", strtotime($issue_date))); ?></label></h5>
														<h5 class="align_left"><label class="popup_label_heading text-transfer-upercase"><?php echo esc_html__('Status :','school-mgt')?> </label>  &nbsp;<label class="invoice_model_value"><?php 
															if($payment_status=='Paid') 
															{ echo '<span class="green_color">'.esc_attr__('Fully Paid','school-mgt').'</span>';}
															if($payment_status=='Part Paid')
															{ echo '<span class="perpal_color">'.esc_attr__('Partially Paid','school-mgt').'</span>';}
															if($payment_status=='Unpaid')
															{ echo '<span class="red_color">'.esc_attr__('Not Paid','school-mgt').'</span>'; } ?></h5>														
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
												<?php
												if(!empty($invoice_data))
												{
													?>
													<h3 class="display_name"><?php esc_attr_e('Invoice Entries','school-mgt');?></h3>
													<?php
												}
												elseif(!empty($income_data))
												{
													?>
													<h3 class="display_name"><?php esc_attr_e('Income Entries','school-mgt');?></h3>
													<?php
												}
												elseif(!empty($expense_data))
												{
													?>
													<h3 class="display_name"><?php esc_attr_e('Expense Entries','school-mgt');?></h3>
													<?php
												}
												?>
												
											<td>	
										</tr>
									</tbody>
								</table>
								<div class="table-responsive table_max_height_180px rtl_padding-left_40px">
									<table class="table model_invoice_table">
										<thead class="entry_heading invoice_model_entry_heading">					
											<tr>
												<th class="entry_table_heading align_center">#</th>
												<th class="entry_table_heading align_center"> <?php esc_attr_e('Date','school-mgt');?></th>
												<th class="entry_table_heading align_center"><?php esc_attr_e('Entry','school-mgt');?> </th>
												<th class="entry_table_heading align_center"><?php esc_attr_e('Price','school-mgt');?></th>
												<th class="entry_table_heading align_center"> <?php esc_attr_e('Issue By','school-mgt');?> </th>
											</tr>						
										</thead>
										<tbody>
											<?php 
											$id=1;
											$total_amount=0;
											if(!empty($income_data) || !empty($expense_data))
											{
												if(!empty($expense_data))
												{
													$income_data=$expense_data;
												}
												
												$patient_all_income=$obj_invoice->mj_smgt_get_onepatient_income_data($income_data->supplier_name);
											
												foreach($patient_all_income as $result_income)
												{
													$income_entries=json_decode($result_income->entry);
													foreach($income_entries as $each_entry)
													{
														$total_amount+=$each_entry->amount;								
														?>
														<tr>
															<td class="align_center invoice_table_data"><?php echo $id;?></td>
															<td class="align_center invoice_table_data"><?php echo mj_smgt_getdate_in_input_box($result_income->income_create_date);?></td>
															<td class="align_center invoice_table_data"><?php echo $each_entry->entry; ?> </td>
															<td class="align_center invoice_table_data"> <?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($each_entry->amount,2,'.','')) ; ?></td>
															<td class="align_center invoice_table_data"><?php echo mj_smgt_get_display_name($result_income->create_by);?></td>
														</tr>
														<?php 
														$id+=1;
													}
												}
											}
											if(!empty($invoice_data))
											{
												$total_amount=$invoice_data->amount
												?>
												<tr>
													<td class="align_center invoice_table_data"><?php echo $id;?></td>
													<td class="align_center invoice_table_data"><?php echo mj_smgt_getdate_in_input_box($invoice_data->date);?></td>
													<td class="align_center invoice_table_data"><?php echo $invoice_data->payment_title; ?> </td>
													<td class="align_center invoice_table_data"> <?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($invoice_data->amount,2,'.','')); ?></td>
													<td class="align_center invoice_table_data"><?php echo mj_smgt_get_display_name($invoice_data->payment_reciever_id);?></td>
												</tr>
												<?php 
											}?>
										</tbody>
									</table>
								</div>
								
													
								<?php 
								if(!empty($invoice_data))
								{								
									$grand_total= $total_amount;			
									$sub_total = $invoice_data->fees_amount;
									if(!empty($invoice_data->tax))
									{

										$tax_name = MJ_smgt_tax_name_by_tax_id_array_for_invoice(esc_html($invoice_data->tax));

									}

									else{

										$tax_name = '';

									}							
								}
								if(!empty($income_data))
								{
									$grand_total=$total_amount;
								}
								?>								
								<div class="table-responsive rtl_padding-left_40px rtl_float_left_width_100px">
									<table width="100%" border="0">
										<tbody>		
											<?php if(isset($invoice_data->tax_amount) && !empty($invoice_data->tax_amount))
											{
												?>
												<tr>
													<td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php echo esc_attr__('Sub Total','school-mgt').'  :';?></td>
													<td align="right" class="rtl_width_15px padding_bottom_15px rtl_text_align_left total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($sub_total,2,'.',''));?></td>
												</tr>
											<tr>
												<td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php echo esc_attr__('Tax Amount','school-mgt').'('.$tax_name.')'.'  :';?></td>
												<td align="right" class="rtl_width_15px padding_bottom_15px rtl_text_align_left total_value"><?php echo "+".MJ_smgt_currency_symbol_position_language_wise(number_format($invoice_data->tax_amount,2,'.',''));?></td>
											</tr>
											<?php
											}?>
										</tbody>	
									</table>
								</div>
								<div class="col-md-12 grand_total_main_div total_padding_15px rtl_float_none">
						<div class="row margin_top_10px_res width_50_res col-md-8 col-sm-8 col-xs-8 print-button pull-left invoice_print_pdf_btn">
							<div class="col-md-2 print_btn_rs width_50_res" style="width:10%;">
								<a  href="?page=smgt_payment&print=print&invoice_id=<?php echo $_REQUEST['idtest'];?>&invoice_type=<?php echo $_REQUEST['invoice_type'];?>" target="_blank"class="btn color_white btn save_btn invoice_btn_div"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/print.png" ?>" > </a>
							</div>
						
							<div class="col-md-3 pdf_btn_rs width_50_res">
								<a href="?page=smgt_payment&print=pdf&invoice_id=<?php echo $_REQUEST['idtest'];?>&invoice_type=<?php echo $_REQUEST['invoice_type'];?>" target="_blank" class="btn color_white invoice_btn_div btn save_btn"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/pdf.png" ?>" ></a>
							</div>
							
						</div>
						<div id="res_rtl_width_100" class="row margin_top_10px_res col-md-4 col-sm-4 col-xs-4 view_invoice_lable_css inovice_width_100px_rs float_left grand_total_div invoice_table_grand_total" style="float: right;">
							<div class="width_50_res align_right col-md-5 col-sm-5 col-xs-5 view_invoice_lable padding_11 padding_right_0_left_0 float_left grand_total_label_div invoice_model_height line_height_1_5 padding_left_0_px"><h3 style="float:right;" class="padding color_white margin invoice_total_label"><?php esc_html_e('Grand Total','school-mgt');?> </h3></div>
							<div class="width_50_res align_right col-md-7 col-sm-7 col-xs-7 view_invoice_lable  padding_right_5_left_5 padding_11 float_left grand_total_amount_div"><h3 class="padding margin text-right color_white invoice_total_value" style="float: right;"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($grand_total,2,'.','')); ?></h3></div>
						</div>
					</div>
								<div class="margin_top_20px"></div>
							</div>
						</div>
					</div><!---- model body  ----->
				</div><!----- Payment Invoice --------->
			</div><!----- penal Body --------->
			<?php
		}
    	?>
    </div>
</div><!---------- PENAL BODY --------------->
<?php ?>
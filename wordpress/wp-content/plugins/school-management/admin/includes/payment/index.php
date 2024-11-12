<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$role=mj_smgt_get_user_role(get_current_user_id());
if($role == 'administrator')
{
	$user_access_add=1;
	$user_access_edit=1;
	$user_access_delete=1;
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('payment');
	$user_access_add=$user_access['add'];
	$user_access_edit=$user_access['edit'];
	$user_access_delete=$user_access['delete'];
	$user_access_view=$user_access['view'];
	
	if (isset ( $_REQUEST ['page'] ))
	{	
		if($user_access_view=='0')
		{	
			mj_smgt_access_right_page_not_access_message_admin_side();
			die;
		}
		if(!empty($_REQUEST['action']))
		{
			if ('payment' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('payment' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('payment' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
			{
				if($user_access_add=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			} 
		}
	}
}
?>

<?php 
$tablename="smgt_payment";
$obj_invoice= new Smgtinvoice();

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{	
	if(isset($_REQUEST['payment_id']))
	{
		$result=delete_mj_smgt_payment($tablename,$_REQUEST['payment_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=payment&message=payment_del');
		}
	}
	if(isset($_REQUEST['income_id']))
	{
		$result=$obj_invoice->mj_smgt_delete_income($_REQUEST['income_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=income_del');
		}
	}
	if(isset($_REQUEST['expense_id']))
	{
		$result=$obj_invoice->mj_smgt_delete_expense($_REQUEST['expense_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=expense_del');
		}
	}
}
// Delete Payment 	
if(isset($_REQUEST['delete_selected_payment']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
	{
		$result=delete_mj_smgt_payment($tablename,$id);
		wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=payment&message=payment_del');		
	}
			
	if($result)
	{ 
		wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=payment&message=payment_del');		
	}
}
	
// Delete Income 
if(isset($_REQUEST['delete_selected_income']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
	{
		$result=$obj_invoice->mj_smgt_delete_income($id);
		wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=income_del');		
	}
			
	if($result)
	{ 
		wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=income_del');		
	}
}
	
// Delete Expense 	
if(isset($_REQUEST['delete_selected_expense']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
	{
		$result=$obj_invoice->mj_smgt_delete_expense($id);
		wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=3');		
	}
			
	if($result)
	{ 
		wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=3');		
	}
}
	
//----------update and delete record---------------------
if(isset($_POST['save_payment']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_payment_admin_nonce' ) )
	{
	
		if(isset($_POST['class_section']))
		{
			$section_id=mj_smgt_onlyNumberSp_validation($_POST['class_section']);	
		}
		else
		{
			$section_id=0;
		}


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
		$created_date = date("Y-m-d H:i:s");
		$payment_data=array('student_id'=>mj_smgt_onlyNumberSp_validation($_POST['student_id']),
			'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['class_id']),
			'section_id'=>$section_id,
			'payment_title'=>mj_smgt_popup_category_validation($_POST['payment_title']),
			'description'=>mj_smgt_address_description_validation($_POST['description']),
			'payment_status'=>mj_smgt_onlyNumberSp_validation($_POST['payment_status']),
			'date'=>$created_date,					
			'payment_reciever_id'=>get_current_user_id(),				
			'created_by'=>get_current_user_id(),
			'tax'=>$tax,
			'tax_amount'=>$tax_amount,
			'fees_amount'=>$_POST['amount'],		
			'amount'=>$total_amount,		
		);
		$tablename="smgt_payment";
		if($_REQUEST['action']=='edit')
		{
			$transport_id=array('payment_id'=>$_REQUEST['payment_id']);
			$result=mj_smgt_update_record($tablename,$payment_data,$transport_id);
			if($result)
			{ 
				wp_redirect ( admin_url().'admin.php?page=smgt_payment&tab=payment&message=2');
			}
		}
		else
		{
			$result=mj_smgt_insert_record($tablename,$payment_data);
			if($result)
			{ 
				wp_redirect ( admin_url().'admin.php?page=smgt_payment&tab=payment&message=1');
			}
		}
	}
}

// Save Income 
if(isset($_POST['save_income']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_income_fees_admin_nonce' ) )	
	{			
		if($_REQUEST['action']=='edit')
		{	
			$result=$obj_invoice->mj_smgt_add_income($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=income_edit');
			}
		}
		else
		{
			$result=$obj_invoice->mj_smgt_add_income($_POST);			
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=income_add');
			}
		}
	}
}
//--------save Expense-------------
if(isset($_POST['save_expense']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_expense_fees_admin_nonce' ) )
	{			
		if($_REQUEST['action']=='edit')
		{	
			$result=$obj_invoice->mj_smgt_add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=expense_edit');
			}
		}
		else
		{
			$result=$obj_invoice->mj_smgt_add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=expense_add');
			}
		}			
	}	
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'payment';
?>
<!-- POP-UP Code -->
<div class="popup-bg">
    <div class="overlay-content popup_payment">
		<div class="modal-content">
			<div class="invoice_data"></div>
		</div>
	</div> 
</div>
<!-- End POP-UP Code -->

<div class="page-inner"><!------ Page Inner ----->
	<div id="" class=" payment_list main_list_margin_5px tab_margin_top_40px"> 
		<?php
		if(isset($_REQUEST['message']))
		{
			
			$message = isset($_REQUEST['message'])?$_REQUEST['message']:0;
			$message_string = "";
			switch($message)
			{
				case '1':
					$message_string = esc_attr__('Payment Added Successfully.','school-mgt');
					break;
				case '2':
					$message_string = esc_attr__('Payment Updated Successfully.','school-mgt');
					break;	
				case '3':
					$message_string = esc_attr__('Expense Deleted Successfully.','school-mgt');
					break;
				case 'payment_del':
					$message_string = esc_attr__('Payment Deleted Successfully.','school-mgt');
					break;
				case 'income_del':
					$message_string = esc_attr__('Income Deleted Successfully.','school-mgt');
				break;
				case 'expense_del':
					$message_string = esc_attr__('Expense Deleted Successfully.','school-mgt');
				break;
				case 'income_add':
					$message_string = esc_attr__('Income Added Successfully.','school-mgt');
				break;
				case 'income_edit':
					$message_string = esc_attr__('Income Updated Successfully.','school-mgt');
				break;
				case 'expense_add':
					$message_string = esc_attr__('Expense Added Successfully.','school-mgt');
				break;
				case 'expense_edit':
					$message_string = esc_attr__('Expense Updated Successfully.','school-mgt');
				break;
					
			} 
			if($message)
			{ 
				?>
				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
					<p><?php echo $message_string;?></p>
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
				<?php 
			}
		} ?>
		<div class="panel-white"><!--------- Penal White ---------->
			<div class="panel-body">  <!--------- Penal Body ---------->  
				<?php
				if($active_tab!='view_invoice')
				{
					$action = "";
					if(!empty($_REQUEST['action']))
					{
						$action = $_REQUEST['action'];
					}
					?>
					<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
						<li class="<?php if($active_tab=='payment'){?>active<?php }?>">			
							<a href="?page=smgt_payment&tab=payment" class="padding_left_0 tab <?php echo $active_tab == 'payment' ? 'active' : ''; ?>">
							<?php esc_html_e('Payment List', 'school-mgt'); ?></a> 
						</li>
						<?php
						if($active_tab=='addpayment' && $action == 'edit')
						{
							?>
							<li class="<?php if($active_tab=='addpayment'){?>active<?php }?>">
								<a href="?page=smgt_payment&tab=addpayment" class="padding_left_0 tab <?php echo $active_tab == 'addpayment' ? 'active' : ''; ?>">
								<?php esc_html_e('Edit Payment', 'school-mgt'); ?></a> 
							</li> 
							<?php
						}
						elseif($active_tab=='addpayment')
						{
							?>
							<li class="<?php if($active_tab=='addpayment'){?>active<?php }?>">
								<a href="?page=smgt_payment&tab=addpayment" class="padding_left_0 tab <?php echo $active_tab == 'addpayment' ? 'active' : ''; ?>">
								<?php esc_html_e('Add Payment', 'school-mgt'); ?></a> 
							</li> 
							<?php
						}
						?>
						<li class="<?php if($active_tab=='incomelist'){?>active<?php }?>">
							<a href="?page=smgt_payment&tab=incomelist" class="padding_left_0 tab <?php echo $active_tab == 'incomelist' ? 'active' : ''; ?>">
							<?php esc_html_e('Income List', 'school-mgt'); ?></a> 
						</li> 
						<?php
						if($active_tab=='addincome' && $action == 'edit')
						{
							?>
							<li class="<?php if($active_tab=='addincome'){?>active<?php }?>">
								<a href="?page=smgt_payment&tab=addincome" class="padding_left_0 tab <?php echo $active_tab == 'addincome' ? 'active' : ''; ?>">
								<?php esc_html_e('Edit Income', 'school-mgt'); ?></a> 
							</li> 
							<?php
						}
						elseif($active_tab=='addincome')
						{
							?>
							<li class="<?php if($active_tab=='addincome'){?>active<?php }?>">
								<a href="?page=smgt_payment&tab=addincome" class="padding_left_0 tab <?php echo $active_tab == 'addincome' ? 'active' : ''; ?>">
								<?php esc_html_e('Add Income', 'school-mgt'); ?></a> 
							</li> 
							<?php
						}
						?> 
						<li class="<?php if($active_tab=='expenselist'){?>active<?php }?>">
							<a href="?page=smgt_payment&tab=expenselist" class="padding_left_0 tab <?php echo $active_tab == 'expenselist' ? 'active' : ''; ?>">
							<?php esc_html_e('Expense List', 'school-mgt'); ?></a> 
						</li> 
						<?php
						if($active_tab=='addexpense' && $action == 'edit')
						{
							?>
							<li class="<?php if($active_tab=='addexpense'){?>active<?php }?>">
								<a href="?page=smgt_payment&tab=addexpense" class="padding_left_0 tab <?php echo $active_tab == 'addexpense' ? 'active' : ''; ?>">
								<?php esc_html_e('Edit Expense', 'school-mgt'); ?></a> 
							</li> 
							<?php
						}
						elseif($active_tab=='addexpense')
						{
							?>
							<li class="<?php if($active_tab=='addexpense'){?>active<?php }?>">
								<a href="?page=smgt_payment&tab=addexpense" class="padding_left_0 tab <?php echo $active_tab == 'addexpense' ? 'active' : ''; ?>">
								<?php esc_html_e('Add Expense', 'school-mgt'); ?></a> 
							</li> 
							<?php
						}
				
						?>
					</ul> 
					<?php
				}
				?>
				<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/payment.js'; ?>" ></script>
  				<?php
				if($active_tab == 'payment')
				{	
					$retrieve_class = get_mj_smgt_payment_list();
					if(!empty($retrieve_class))
					{	
						?> 
						<script>
							jQuery(document).ready(function() 
							{
								var table =  jQuery('#payment_list').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
											$('#payment_list th:first-child').removeClass('sorting_asc');
										},
										//stateSave: true,
									responsive: true,
									"order": [[ 2, "asc" ]],
									"dom": 'lifrtp',
									"aoColumns":[	                  
									{"bSortable": false},	                 
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
								jQuery('#checkbox-select-all').on('click', function(){     
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
						<div class="panel-body"><!------- penal Body --------->
							<div class="table-responsive"><!--------- Table Responsive ---------->
								<form id="frm-example" name="frm-example" method="post">
									<table id="payment_list" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
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
											foreach ($retrieve_class as $retrieved_data)
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
													<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->payment_id;?>"></td>
													<td class="user_image width_50px profile_image_prescription padding_left_0">
														<a href="?page=smgt_payment&tab=view_invoice&idtest=<?php echo $retrieved_data->payment_id; ?>&invoice_type=invoice" class="" >
															<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
															</p>
														</a>
													</td>
													<td>
															<?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?>
														<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
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
													<td>
														<?php  echo mj_smgt_getdate_in_input_box($retrieved_data->date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>         
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
																			<a href="?page=smgt_payment&tab=view_invoice&idtest=<?php echo $retrieved_data->payment_id; ?>&invoice_type=invoice" class="float_left_width_100" ><i class="fa fa-eye"></i> <?php esc_attr_e('View Invoice','school-mgt');?></a>
																		</li>
																		
																		<?php
																		if($user_access_edit == '1')
																		{
																			?>
																			<li class="float_left_width_100 border_bottom_menu">
																				<a href="?page=smgt_payment&tab=addpayment&action=edit&payment_id=<?php echo $retrieved_data->payment_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																			</li>
																			<?php 
																		} 
																		if($user_access_delete =='1')
																		{
																			?>
																			<li class="float_left_width_100 ">
																				<a href="?page=smgt_payment&tab=payment&action=delete&payment_id=<?php echo $retrieved_data->payment_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
									<div class="print-button pull-left">
										<button class="btn-sms-color button_reload">
											<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
											<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
										</button>
										<?php if($user_access_delete =='1')
										{ 
											?>
											<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_payment" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
											<?php
										}
										?>
									</div>
								</form>
							</div><!--------- Table Responsive ---------->
						</div><!------- penal Body --------->
						<?php 
					}
					else
					{
						if($user_access_add=='1')
						{
							?>
							<div class="no_data_list_div no_data_img_mt_30px"> 
								<a href="<?php echo admin_url().'admin.php?page=smgt_payment&tab=addpayment';?>">
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
				if($active_tab == 'addpayment')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add-payment.php';
					
				}
				if($active_tab == 'incomelist')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/payment/income-list.php';
				}
				if($active_tab == 'addincome')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add_income.php';
				}
				if($active_tab == 'expenselist')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/payment/expense-list.php';
				}
				if($active_tab == 'addexpense')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add_expense.php';
				}
				if($active_tab == 'view_invoice')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/payment/view_invoice.php';
				}
				?>
			</div><!--------- Penal Body ----------> 
		</div><!--------- Penal White ----------> 
	</div>
</div><!------ Page Inner ----->
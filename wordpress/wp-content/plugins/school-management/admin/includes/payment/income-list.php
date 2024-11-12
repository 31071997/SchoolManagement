<?php 
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
}
$obj_invoice= new Smgtinvoice();
if($active_tab == 'incomelist')
{ 
	$obj_invoice->mj_smgt_get_all_income_data();
	if(!empty($obj_invoice->mj_smgt_get_all_income_data()))
	{

		?>
		<script type="text/javascript">
			$(document).ready(function() 
			{
				var table = jQuery('#tblincome_admin').DataTable(
				{
					"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
					//stateSave: true,
					responsive: true,
					"order": [[ 4, "Desc" ]],
					"dom": 'lifrtp',
					"aoColumns":[
						{"bSortable": false},
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
				jQuery('#checkbox-select-all').on('click', function()
				{     
					var rows = table.rows({ 'search': 'applied' }).nodes();
					jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
				}); 
			
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
		<div class="panel-body"><!--------- penal body --------->
			<div class="table-responsive"><!--------- table Responsive --------->
				<form id="frm-example" name="frm-example" method="post">
					<table id="tblincome_admin" class="display" cellspacing="0" width="100%">
						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
							<tr>
								<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
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
							foreach ($obj_invoice->mj_smgt_get_all_income_data() as $retrieved_data)
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
									<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->income_id;?>"></td>
									<td class="user_image width_50px profile_image_prescription padding_left_0">
										<a href="?page=smgt_payment&tab=view_invoice&idtest=<?php echo $retrieved_data->income_id; ?>&invoice_type=income" class="" >
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
															<a href="?page=smgt_payment&tab=view_invoice&idtest=<?php echo $retrieved_data->income_id; ?>&invoice_type=income" class="float_left_width_100" ><i class="fa fa-eye"></i> <?php esc_attr_e('View Invoice','school-mgt');?></a>
														</li>
														
														<?php
														if($user_access_edit == '1')
														{
															?>
															<li class="float_left_width_100 border_bottom_menu">
																<a href="?page=smgt_payment&tab=addincome&action=edit&income_id=<?php echo $retrieved_data->income_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
															</li>
															<?php 
														} 
														if($user_access_delete =='1')
														{
															?>
															<li class="float_left_width_100 ">
																<a href="?page=smgt_payment&tab=incomelist&action=delete&income_id=<?php echo $retrieved_data->income_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
							<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_income" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
							<?php
						}
						?>
					</div>
				</form>
			</div><!--------- Table Responsive --------->
		</div><!--------- penal body --------->
		<?php  
	}
	else
	{
		if($user_access_add=='1')
		{
			?>
			<div class="no_data_list_div no_data_img_mt_30px"> 
				<a href="<?php echo admin_url().'admin.php?page=smgt_payment&tab=addincome';?>">
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
} ?>
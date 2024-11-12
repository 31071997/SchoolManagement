<?php 
$obj_lib= new Smgtlibrary();
if($active_tab == 'issuelist')
{ 
	$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks(); 
	if(!empty($retrieve_issuebooks))
	{
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				var table =  jQuery('#issue_list').DataTable({
					"initComplete": function(settings, json) {
						$(".print-button").css({"margin-top": "-5%"});
					},
					//stateSave: true,
					responsive: true,
					"dom": 'lifrtp',
					"order": [[ 2, "asc" ]],
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
					if ($('.smgt_sub_chk:checked').length == 0 )
					{
						alert(language_translate2.one_record_select_alert);
						return false;
					}
					else
					{
						var alert_msg=confirm(language_translate2.delete_record_alert);
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
				$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
			});
		</script>
		
		<div class="panel-body"><!--panel-body -->
			<div class="table-responsive">
				<form id="frm-example" name="frm-example" method="post">
					<table id="issue_list" class="display" cellspacing="0" width="100%">
						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
							<tr>
								<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
								<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
								<th><?php esc_attr_e('Student Name','school-mgt');?></th>
								<th><?php esc_attr_e('Book Title','school-mgt');?></th>
								<th><?php esc_attr_e('Issue Date','school-mgt');?></th>
								<th><?php esc_attr_e('Return Date ','school-mgt');?></th>
								<th><?php esc_attr_e('Accept Return Date ','school-mgt');?></th>
								<th><?php esc_attr_e('Period','school-mgt');?></th>
								<th><?php esc_attr_e('Fine','school-mgt');?></th>
								<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if(!empty($retrieve_issuebooks))
							{
								$i=0;
								foreach ($retrieve_issuebooks as $retrieved_data)
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
										<td class="checkbox_width_10px">
											<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>">
										</td>
										<td class="user_image width_50px profile_image_prescription padding_left_0">
											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Library.png"?>" height="30px" width="30px" alt="" class="massage_image center">
											</p>
										</td>
										<td>
											<a class="color_black" href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->student_id;?>"><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Student Name','school-mgt');?>" ></i>
										</td>
										<td>
											<?php echo stripslashes(mj_smgt_get_bookname($retrieved_data->book_id));?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Book Title','school-mgt');?>" ></i>
										</td>
										<td>
											<?php echo mj_smgt_getdate_in_input_box($retrieved_data->issue_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Issue Date','school-mgt');?>" ></i>
										</td>
										<td>
											<?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Return Date','school-mgt');?>" ></i>
										</td>
										<td>
											<?php if(!empty($retrieved_data->actual_return_date)){echo mj_smgt_getdate_in_input_box($retrieved_data->actual_return_date);}else{echo "N/A";} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Accept Return Date','school-mgt');?>" ></i>
										</td>
										<td>
											<?php echo get_the_title($retrieved_data->period);?> <?php _e('Day','school-mgt') ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Period','school-mgt');?>" ></i>
										</td>
										<td class="" ><?php if($retrieved_data->fine == "" || $retrieved_data->fine == 0){ echo 'N/A';}else{echo MJ_smgt_currency_symbol_position_language_wise($retrieved_data->fine); } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Fine','school-mgt');?>" ></i></td>
										<td class="action"> 
											<div class="smgt-user-dropdown">
												<ul class="" style="margin-bottom: 0px !important;">
													<li class="">
														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
														</a>
														<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
															<li class="float_left_width_100 border_bottom_item">
																<a href="?page=smgt_library&tab=issuebook&action=edit&issuebook_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?> </a>
															</li>	
															<li class="float_left_width_100">
																<a href="?page=smgt_library&tab=issuelist&action=delete&issuebook_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;"  onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a> 
															</li>
														</ul>
													</li>
												</ul>
											</div>	
										</td>
									</tr>
									<?php 
									$i++;
								} 
							} ?>	
						</tbody>
					</table>
					<div class="print-button pull-left">
						<button class="btn btn-success btn-sms-color button_reload">
							<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->id); ?>" style="margin-top: 0px;">
							<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
						</button>
						<?php 
						if($user_access_delete =='1')
						{ ?>
								<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_issuebook" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
							<?php
						} ?>
					</div>
				</form>
			</div>    
		</div>  <!--panel-body -->
		<?php 
	}
	else
	{
		if($user_access_add=='1')
		{
			?>
			<div class="no_data_list_div no_data_img_mt_30px"> 
				<a href="<?php echo admin_url().'admin.php?page=smgt_library&tab=issuebook';?>">
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
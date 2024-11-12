<?php 
	$obj=new Smgt_Homework();
	$retrieve_class=$obj->mj_smgt_get_all_homeworklist();	
	
?>
<!-- POP up code -->
<div class="popup-bg">
	<div class="overlay-content">
		<div class="modal-content">
			<div class="view_popup"></div>     
		</div>
	</div>    
</div>
<!-- End POP-UP Code -->
<?php
if(!empty($retrieve_class))
{
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			"use strict";	
			$('#class_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
				
			var table =  jQuery('#homework_list').DataTable({
				"initComplete": function(settings, json) {
					$(".print-button").css({"margin-top": "-5%"});
				},
				responsive: true,
				"order": [[7, "desc" ]],
				"ordering": true,
				"dom": 'lifrtp',
				"aoColumns":[	      	                  
							{"bSortable": false},
							{"bSortable": false},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": false}],
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
		});

	</script>
	<div class=""><!-- div -->
		<div class="table-responsive"><!-- table-responsive -->
			<form id="frm-example" name="frm-example" method="post">
				<table id="homework_list" class="display" cellspacing="0" width="100%">
					<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
						<tr>
							<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
							<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
							<th><?php esc_attr_e('Title','school-mgt');?></th>
							<th><?php esc_attr_e('Class','school-mgt');?></th>
							<th><?php esc_attr_e('Subject','school-mgt');?></th>
							<th><?php esc_attr_e('Homework Date','school-mgt');?></th>
							<th><?php esc_attr_e('Submission Date','school-mgt');?></th>
							<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
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
								<td class="checkbox_width_10px">
									<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->homework_id;?>">
								</td>

								<td class="user_image width_50px profile_image_prescription">	
									<a class="view_details_popup" href="#" id="<?php echo $retrieved_data->homework_id;?>" type="Homework_view">
										<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>" alt="" class="massage_image center image_icon_height_25px margin_top_3px">
										</p>
									</a>
								</td>

								<td class="">
									<a class="color_black view_details_popup" href="#" id="<?php echo $retrieved_data->homework_id;?>" type="Homework_view">
										<?php echo $retrieved_data->title;?>
									</a> 
									<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Title','school-mgt');?>" ></i>
								</td>
								<td>
									<?php echo smgt_get_class_section_name_wise($retrieved_data->class_name,$retrieved_data->section_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class','school-mgt');?>" ></i>
								</td>
								<td>
									<?php echo mj_smgt_get_subject_byid($retrieved_data->subject);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Subject','school-mgt');?>" ></i>
								</td>
								<td>
									<?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Homework Date','school-mgt');?>" ></i>
								</td>
								<td>
									<?php echo mj_smgt_getdate_in_input_box($retrieved_data->submition_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Submission Date','school-mgt');?>" ></i>
								</td>
								<td class="action"> 
									<div class="smgt-user-dropdown">
										<ul class="" style="margin-bottom: 0px !important;">
											<li class="">
												<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
												</a>
												<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
													
													<?php  
														$doc_data=json_decode($retrieved_data->homework_document);
													?>
													<li class="float_left_width_100 ">
														<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->homework_id;?>" type="Homework_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View','school-mgt');?></a>
													</li>

													<li class="float_left_width_100">
														<a href="?page=smgt_student_homewrok&tab=view_stud_detail&action=viewsubmission&homework_id=<?php echo $retrieved_data->homework_id;?>" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/submitted_homework.png"?>"style="height:17px;">&nbsp;&nbsp;<?php esc_attr_e('Submitted Homework','school-mgt');?></a>
													</li>

													<?php
													if(!empty($doc_data[0]->value))
													{
														?>
														<!-- <li class="float_left_width_100">
															<a download href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>"  class="float_left_width_100" style="font-size: 14px !important;" record_id="<?php echo $retrieved_data->homework_id;?>"><i class="fa fa-download"></i><?php esc_html_e('Download Document', 'school-mgt');?></a>
														</li> -->
													
														<li class="float_left_width_100">
															<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="float_left_width_100" record_id="<?php echo $retrieved_data->homework_id;?>"><i class="fa fa-eye"> </i><?php esc_html_e('View Document', 'school-mgt');?></a>
														</li>
														<?php
													}
													?>
													<?php 
													if($user_access_edit == '1')
													{
														?>

														<li class="float_left_width_100 border_bottom_item">
															<a href="?page=smgt_student_homewrok&tab=addhomework&action=edit&homework_id=<?php echo $retrieved_data->homework_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a>
														</li>
														<?php 
													} ?>
													<?php 
													if($user_access_delete =='1')
													{ 
														?>
														<li class="float_left_width_100 ">
															<a href="?page=smgt_student_homewrok&tab=homeworklist&action=delete&homework_id=<?php echo $retrieved_data->homework_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"> </i> <?php esc_attr_e('Delete','school-mgt');?></a>
														</li>
														<?php 
													} ?>

												</ul>
											</li>
										</ul>
									</div>	
								</td>
							</tr>
							<?php 
							$i++;
						} ?>
					</tbody>
				</table>
				<div class="print-button pull-left">
					<button class="btn btn-success btn-sms-color button_reload">
						<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->homework_id); ?>" style="margin-top: 0px;">
						<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
					</button>
					<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
				</div>
			</form>
		</div><!--------- Table Responsive ------->
	</div>
	<?php 	
}
else
{
	if($user_access_add=='1')
	{
		?>
		<div class="no_data_list_div no_data_img_mt_30px"> 
			<a href="<?php echo admin_url().'admin.php?page=smgt_student_homewrok&tab=addhomework';?>">
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

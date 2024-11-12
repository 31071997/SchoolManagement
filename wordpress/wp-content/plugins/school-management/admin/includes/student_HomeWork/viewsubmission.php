<div class="panel-body"><!-- panel-body--> 	
	<div class="table-responsive"><!-- table-responsive --> 	
		<form id="frm-example" name="frm-example" method="post">
			<table id="submission_list" class="display" cellspacing="0" width="100%">
				<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
					<tr>
						<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
						<th><?php esc_attr_e('Title','school-mgt');?></th>
						<th><?php esc_attr_e('Class','school-mgt');?></th>
						<th><?php esc_attr_e('Student Name','school-mgt');?></th>
						<th><?php esc_attr_e('Subject','school-mgt');?></th>
						<th><?php esc_attr_e('Status','school-mgt');?></th>
						<th><?php esc_attr_e('Homework Date','school-mgt');?></th>
						<th><?php esc_attr_e('Submitted Date','school-mgt');?></th>
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
							<td class="padding_left_0 user_image width_50px profile_image_prescription">	
								<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>" alt="" class="massage_image center image_icon_height_25px margin_top_3px">
								</p>
							</td>
							<td><?php echo $retrieved_data->title;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Title','school-mgt');?>" ></i></td>
							
							<td><?php echo mj_smgt_get_class_name($retrieved_data->class_name);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class','school-mgt');?>" ></i></td>

							<td>
								<a class="" href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->student_id;?>"><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Student Name','school-mgt');?>" ></i>
							</td>
							
							<td><?php echo mj_smgt_get_single_subject_name($retrieved_data->subject);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Subject','school-mgt');?>" ></i></td>
							<?php  
							if($retrieved_data->status==1)
							{
								if(date('Y-m-d',strtotime($retrieved_data->uploaded_date)) <= $retrieved_data->submition_date)
								{
									?>
									<td><label class="green_color"><?php esc_attr_e('Submitted','school-mgt'); ?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Status','school-mgt');?>" ></i></td>
									<?php
								}
								else
								{
									?><td><label class="green_color"><?php esc_attr_e('Late-Submitted','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Status','school-mgt');?>" ></i></td><?php
								}
							}
							else 
							{ ?>
									<td><label class="color-red"><?php esc_attr_e('Pending','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Status','school-mgt');?>" ></i></td>
									<?php
										
							} ?>  
							<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Homework Date','school-mgt');?>" ></i></td>
							<?php  
							if($retrieved_data->uploaded_date==0000-00-00)
							{
									?>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "N/A ";?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Submitted Date','school-mgt');?>" ></i></td> 
								<?php 
							} 
							else
							{ ?>
								<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->uploaded_date);?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Submitted Date','school-mgt');?>" ></i></td>
								<?php 
							} ?>
							<?php 
							if($retrieved_data->status == 1)
							{ 
								?>
								<td class="smgt_download_btn"> 
								<a download href="<?php print content_url().'/uploads/homework_file/'.$retrieved_data->file; ?>" class="status_read btn btn-info" record_id="<?php echo $retrieved_data->stu_homework_id;?>" download><?php esc_html_e(' Download', 'school-mgt');?></a></td>
								<?php 
							} 
							else 
							{ 
								?>
								<td class="smgt_download_btn"><a href="<?php echo SMS_PLUGIN_URL;?>/uploadfile/<?php echo $retrieved_data->file;?>" disabled="disabled" class="btn btn-disabled"> <?php esc_attr_e('Download','school-mgt');?></a></td><?php 
							}?>
						</tr>
						<?php 
						$i++;
					} ?>
				</tbody>
			</table>
		</form>
	</div><!-- table-responsive --> 
</div><!-- panel-body-->
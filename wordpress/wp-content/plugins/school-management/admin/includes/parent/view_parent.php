<?php
	$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
	$parent_data=get_userdata($_REQUEST['parent_id']);
 	$user_meta =get_user_meta($_REQUEST['parent_id'], 'child', true); 
	$parent_id = $_REQUEST['parent_id'];
?>
<div class="panel-body view_page_main"><!-- START PANEL BODY DIV-->
	<div class="content-body"><!-- START CONTENT BODY DIV-->
		<!-- Detail Page Header Start -->
		<section id="user_information" class="">
			<div class="view_page_header_bg">
				<div class="row">
					<div class="col-xl-10 col-md-9 col-sm-10">
						<div class="user_profile_header_left float_left_width_100">
							<?php
							$umetadata=mj_smgt_get_user_image($parent_data->ID);
							?>
							<img class="user_view_profile_image" src="<?php if(!empty($umetadata)) {echo $umetadata; }else{ echo get_option( 'smgt_parent_thumb_new' );}?>">
							<div class="row profile_user_name">
								<div class="float_left view_top1">
									<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
										<label class="view_user_name_label"><?php echo esc_html($parent_data->display_name);?></label>
										<?php
										if($user_access_edit=='1')
										{
											?>
											<div class="view_user_edit_btn">
												<a class="color_white margin_left_2px" href="?page=smgt_parent&tab=addparent&action=edit&parent_id=<?php echo $parent_data->ID;?>">
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/edit.png"?>">
												</a>
											</div>
											<?php
										}
										?>
									</div>
									<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
										<div class="view_user_phone float_left_width_100">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<lable><?php echo $parent_data->mobile_number;?></label>
										</div>
									</div>
								</div>
							</div>
							<div class="row view_user_teacher_label">
								<div class="col-xl-12 col-md-12 col-sm-12">
									<div class="view_top2">
										<div class="row view_user_teacher_label">
											<div class="col-md-12 address_student_div">
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $parent_data->address; ?></label>
											</div>		
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-3 col-sm-2">
						<div class="group_thumbs">
							<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Group.png"?>">
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Detail Page Header End -->

		<!-- Detail Page Tabing Start -->
		<section id="body_area" class="">
			<div class="row">
				<div class="col-xl-12 col-md-12 col-sm-12">
					<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
						<li class="<?php if($active_tab1=='general'){?>active<?php }?>">			
							<a href="admin.php?page=smgt_parent&tab=view_parent&action=view_parent&tab1=general&parent_id=<?php echo $_REQUEST['parent_id'];?>" 
							class="padding_left_0 tab <?php echo $active_tab1 == 'general' ? 'active' : ''; ?>">
							<?php esc_html_e('GENERAL', 'school-mgt'); ?></a> 
						</li>
						<li class="<?php if($active_tab1=='Child'){?>active<?php }?>">
							<a href="admin.php?page=smgt_parent&tab=view_parent&action=view_parent&tab1=Child&parent_id=<?php echo $_REQUEST['parent_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'Child' ? 'active' : ''; ?>">
							<?php esc_html_e('Child List', 'school-mgt'); ?></a> 
						</li>  
					</ul>
				</div>
			</div>
		</section>
		<!-- Detail Page Tabing End -->

		<!-- Detail Page Body Content Section  -->
		<section id="body_content_area" class="">
			<div class="panel-body"><!-- START PANEL BODY DIV-->
				<?php 
				// general tab start 
				if($active_tab1 == "general")
				{
					?>
					<div class="row margin_top_15px margin_left_3">
						<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'school-mgt'); ?> </label><br/>
							<label class="view_page_content_labels"> <?php echo $parent_data->user_email; ?> </label>
						</div>
						<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Mobile Number', 'school-mgt'); ?> </label><br/>
							<label class="view_page_content_labels">
							+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $parent_data->mobile_number; ?>
							</label>	
						</div>

						<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br/>
							<label class="view_page_content_labels"> <?php echo mj_smgt_getdate_in_input_box($parent_data->birth_date); ?>
							</label>
						</div>
						
						<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br/>
							<label class="view_page_content_labels"> <?php echo esc_html_e(ucfirst( $parent_data->gender),'school-mgt'); ?></label>	
						</div>
						
						<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Relation', 'school-mgt'); ?> </label><br/>
							<label class="view_page_content_labels"><?php $relation = $parent_data->relation; if(!empty($relation)){echo esc_html__($parent_data->relation,'school-mgt');}else{ echo'N/A';} ?></label>
						</div>

					</div>
					<!-- student Information div start  -->
					<div class="row margin_top_20px">
						<div class="col-xl-12 col-md-12 col-sm-12">
							<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
								<div class="guardian_div">
									<label class="view_page_label_heading"> <?php esc_html_e('Contact Information', 'school-mgt'); ?> </label>
									<div class="row">
										<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label> <br>
											<label class="view_page_content_labels"><?php if(!empty($parent_data->city)){ echo $parent_data->city;}else{ echo "N/A";} ?></label>
										</div>
										<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
											<label class="ftext_style_capitalization view_page_content_labels"><?php if(!empty($parent_data->state)){ echo $parent_data->state; }else{ echo "N/A"; } ?></label>
										</div>
										<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zip Code', 'school-mgt'); ?> </label><br>
											<label class="view_page_content_labels"><?php echo $parent_data->zip_code; ?></label>
										</div>
										<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Alt. Mobile Number', 'school-mgt'); ?> </label><br>
											<label class="view_page_content_labels"><?php if(!empty($parent_data->phone)){?>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $parent_data->phone; }else{ echo "N/A"; } ?></label>
										</div>
										
									</div>
								</div>	
							</div>
						</div>
					</div>
					<?php
				}
				// attendance tab start 
				elseif($active_tab1 == "Child")
				{
					?>
					<script type="text/javascript">
						jQuery(document).ready(function($)
						{
							"use strict";	
							jQuery('#parents_list_detailpage').DataTable({
								
								//stateSave: true,
								responsive: true,
								"order": [[ 1, "asc" ]],
								"responsive": true,
								dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',
								"aoColumns":[	                  
											{"bSortable": false},
											{"bSortable": true},
											{"bSortable": true},
											{"bSortable": true},
											{"bSortable": true}],
								language:<?php echo mj_smgt_datatable_multi_language();?>
							});
							$('.dataTables_filter').addClass('search_btn_view_page');
							$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
						});
					</script>
					<div class="">
						<div id="Section1" class="">
							<div class="row">
								<div class="col-lg-12">
									<div class="">
										<div class="card-content">
											<div class="table-responsive">
												<?php if(!empty($user_meta))
													  { 
														?>
												<table id="parents_list_detailpage" class="display table" cellspacing="0" width="100%">
													<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
														<tr>
															<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
															<th><?php echo esc_attr_e( 'Student Name & Email', 'school-mgt' ) ;?></th>
															<th><?php esc_attr_e('Mobile Number','school-mgt');?> </th>   
															<th> <?php echo esc_attr_e( 'Class & Section', 'school-mgt' ) ;?></th>
															<th><?php esc_attr_e('Roll No.','school-mgt');?></th>
														</tr>
													</thead>
													<tbody>
													<?php
														
															foreach($user_meta as $childsdata)
															{
																$child=get_userdata($childsdata);
																
																if(!empty($child))
																{
																?>
																<tr>
																	<td class="width_50px">
																	<?php 
																		if($childsdata)
																		{
																			$umetadata=mj_smgt_get_user_image($childsdata);
																		}
																		if(empty($umetadata))
																		{
																			echo '<img src='.get_option( 'smgt_student_thumb_new' ).' height="50px" width="50px" class="img-circle" />';
																		}
																		else
																			echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';
																		?>
																	</td>
																	<td class="name">
																		<a class="color_black" href="admin.php?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $child->ID;?>"><?php echo $child->first_name." ".$child->middle_name." ".$child->last_name;?></a>
																		<br>
																		<label class="list_page_email"><?php echo $child->user_email;?></label>
																	</td>

																	<td>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $child->mobile_number;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile Number','school-mgt');?>" ></i>
																	</td>
																	<td class="name">
																		<?php 
																		$class_id = get_user_meta($child->ID, 'class_name',true);
																		$section_id = get_user_meta($child->ID, 'class_section',true);
																		$classname = smgt_get_class_section_name_wise($class_id,$section_id);
																		if(!empty($classname))
																		{
																			echo $classname;
																		}
																		else
																		{
																			echo "N/A";
																		}
																	?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class & Section','school-mgt');?>" ></i>
																	</td>

																	<td>
																		<?php echo get_user_meta($child->ID, 'roll_id',true);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Roll No.','school-mgt');?>" ></i></td>
																</tr>
																<?php
																}
															}
														
													?>
												</tbody>
												</table>
												<?php }else{
													?>
													<div class="calendar-event-new"> 
														<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
													</div>
													<?php
												} ?>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>	
			</div><!-- END PANEL BODY DIV-->
		</section>
		<!-- Detail Page Body Content Section End -->
	</div><!-- END CONTENT BODY DIV-->
</div><!-- END PANEL BODY DIV-->
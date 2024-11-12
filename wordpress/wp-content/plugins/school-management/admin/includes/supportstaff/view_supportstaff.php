<?php
 $active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
 $staff_data=get_userdata($_REQUEST['supportstaff_id']);
?>

<div class="panel-body smgt_support_view_page view_page_main"><!-- START PANEL BODY DIV-->
	<div class="content-body">
		<!-- Detail Page Header Start -->
		<section id="user_information" class="view_page_header_bg">
			<div class="view_page_header_bg">
				<div class="row">
					<div class="col-xl-10 col-md-9 col-sm-10">
						<div class="user_profile_header_left float_left_width_100">
							<?php
							$umetadata=mj_smgt_get_user_image($staff_data->ID);
							?>
							<img class="user_view_profile_image" src="<?php if(!empty($umetadata)) {echo $umetadata; }else{ echo get_option( 'smgt_supportstaff_thumb_new' );}?>">
							<div class="row profile_user_name">
								<div class="float_left view_top1">
									<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
										<label class="view_user_name_label"><?php echo esc_html($staff_data->display_name);?></label>
										<?php
										if($user_access_edit=='1')
										{
											?>
											<div class="view_user_edit_btn">
												<a class="color_white margin_left_2px" href="?page=smgt_supportstaff&tab=addsupportstaff&action=edit&supportstaff_id=<?php echo $staff_data->ID;?>">
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/edit.png"?>">
												</a>
											</div>
											<?php
										}
										?>
									</div>
									<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
										<div class="view_user_phone float_left_width_100">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<lable><?php echo $staff_data->mobile_number;?></label>
										</div>
									</div>
								</div>
							</div>
							<div class="row padding_top_15px_res support_staff_address_row">
								<div class="col-xl-12 col-md-12 col-sm-12">
									<div class="view_top2">
										<div class="row view_user_doctor_label support_staff_address_row">
											<div class="col-md-12 address_student_div">
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $staff_data->address; ?></label>
											</div>		
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-md-3 col-sm-2 group_thumbs">
						<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Group.png"?>">
					</div>
				</div>
			</div>
		</section>
		<!-- Detail Page Header End -->

		<!-- Detail Page Body Content Section  -->
		<section id="body_area" class="">
			<div class="panel-body"><!-- START PANEL BODY DIV-->
				<?php 
				// general tab start 
				if($active_tab1 == "general")
				{
					?>
					<div class="row margin_top_15px margin_left_3">
						<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'school-mgt'); ?> </label><br/>
							<label class="word_brack view_page_content_labels"> <?php echo $staff_data->user_email; ?> </label>
						</div>
						<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Mobile Number', 'school-mgt'); ?> </label><br/>
							<label class="word_brack view_page_content_labels">+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $staff_data->mobile_number; ?></label>	
						</div>
						<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br/>
							<label class="word_brack view_page_content_labels"> <?php 

								if($staff_data->gender=='male') 

									echo esc_attr__('Male','school-mgt');

								elseif($student_data->gender=='female') 

									echo esc_attr__('Female','school-mgt');

								?> </label>	
						</div>
						<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
							<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br/>
							<label class="word_brack view_page_content_labels"> <?php echo mj_smgt_getdate_in_input_box($staff_data->birth_date);?> </label>
						</div>
					</div>
					<!-- student Information div start  -->
					<div class="row margin_top_20px">
						<div class="col-xl-12 col-md-12 col-sm-12">
							<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
								<div class="guardian_div">
									<label class="view_page_label_heading"> <?php esc_html_e('Contact Information', 'school-mgt'); ?> </label>
									<div class="row">
										<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label><br>
											<label class="word_brack view_page_content_labels"><?php echo $staff_data->city; ?></label>
										</div>
										<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
											<label class="word_brack view_page_content_labels"><?php if(!empty($staff_data->state)){ echo $staff_data->state; }else{ echo "N/A"; } ?></label>
										</div>
										<div class="col-xl-4 col-md-4 col-sm-12 address_rs_css margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zipcode', 'school-mgt'); ?> </label><br>
											<label class="word_brack view_page_content_labels"><?php echo $staff_data->zip_code; ?></label>
										</div>
										<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Alt. Mobile Number', 'school-mgt'); ?> </label><br>
											<label class="word_brack view_page_content_labels"><?php if(!empty($staff_data->alternet_mobile_number)){ ?>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $staff_data->alternet_mobile_number; }else{ echo "N/A"; } ?></label>
										</div>
										<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Working Hour', 'school-mgt'); ?> </label><br>
											<label class="view_page_content_labels">
												<?php 
												if(!empty($staff_data->working_hour))
												{ 
													$working_data = $staff_data->working_hour; 
													if($working_data == 'full_time'){
														echo esc_html_e('Full Time', 'school-mgt');
													}
													else
													{
														echo esc_html_e('Part Time', 'school-mgt');
													}
												}
												else
												{ 
													echo "N/A"; 
												} 
												?>
											</label>
										</div>
										<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
											<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Position', 'school-mgt'); ?> </label><br>
											<label class="view_page_content_labels"><?php if(!empty($staff_data->possition)){ echo $staff_data->possition; }else{ echo "N/A"; } ?></label>
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
	</div><!-- START CONTENT BODY DIV-->
</div><!-- END PANEL BODY DIV-->

<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>
<?php
 $active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
 $student_data=get_userdata($_REQUEST['id']);
 $user_meta =get_user_meta($_REQUEST['id'], 'parent_id', true); 
 $custom_field_obj = new Smgt_custome_field;								
 $module='student';	
 $user_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
 $sibling_information_value=str_replace('"[','[',$student_data->sibling_information);
 $sibling_information_value1=str_replace(']"',']',$sibling_information_value);
 $sibling_information=json_decode($sibling_information_value1);

?>
<!-- POP up code -->
<div class="popup-bg">
	<div class="overlay-content content_width">
		<div class="modal-content d-modal-style">
			<div class="task_event_list">
			</div>
		</div>
	</div>
</div>
<!-- POP up code -->
	
<div class="panel-body view_page_main"><!-- START PANEL BODY DIV-->
	<div class="content-body">
			<!-- Detail Page Header Start -->
			<section id="user_information" class="view_page_header_bg">
				<div class="view_page_header_bg">
					<div class="row">
						<div class="col-xl-10 col-md-9 col-sm-10">
							<div class="user_profile_header_left float_left_width_100">
								<?php
								$umetadata=mj_smgt_get_user_image($student_data->ID);
								?>
								<img class="user_view_profile_image" src="<?php if(!empty($userimage)) {echo $umetadata; }else{ echo get_option( 'smgt_student_thumb_new' );}?>">
								<div class="row profile_user_name">
									<div class="float_left view_top1">
										<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
											<label class="view_user_name_label"><?php echo esc_html($student_data->display_name);?></label>
											<?php
											if($user_access_edit=='1')
											{
												?>
												<div class="view_user_edit_btn">
													<a class="color_white margin_left_2px" href="?page=smgt_admission&tab=admission_form&action=edit&id=<?php echo $student_data->ID;?>">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/edit.png"?>">
													</a>
												</div>
												<?php
											}
											?>
										</div>
										<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
											<div class="view_user_phone float_left_width_100">
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<lable><?php echo $student_data->mobile_number;?></label>
											</div>
										</div>
									</div>
								</div>
								<div id="res_add_width" class="row ">
									<div class="col-xl-12 col-md-12 col-sm-12">
										<div class="view_top2">
											<div class="row view_user_doctor_label">
												<div class="col-md-12 address_student_div">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $student_data->address; ?></label>
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
								<label class="word_brack view_page_content_labels"> <?php echo $student_data->user_email; ?> </label>
							</div>
							<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
								<label class="view_page_header_labels"> <?php esc_html_e('Admission Number', 'school-mgt'); ?> </label><br/>
								<label class="word_brack view_page_content_labels"><?php echo $student_data->admission_no;?> </label>	
							</div>
							<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
								<label class="view_page_header_labels"> <?php esc_html_e('Admission Date', 'school-mgt'); ?> </label><br/>
								<label class="word_brack view_page_content_labels"> <?php echo  mj_smgt_getdate_in_input_box($student_data->admission_date); ?> </label>
							</div>

							<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
								<label class="view_page_header_labels"> <?php esc_html_e('Previous School', 'school-mgt'); ?> </label><br/>
								<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->preschool_name)){ echo $student_data->preschool_name; }else{ echo "N/A"; } ?> </label>	
							</div>
						</div>
						<!-- student Information div start  -->
						<div class="row margin_top_20px">
							<div class="col-xl-12 col-md-12 col-sm-12">
								<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs rtl_padding_0px">
									<div class="guardian_div">
										<label class="view_page_label_heading"> <?php esc_html_e('Student Information', 'school-mgt'); ?> </label>
										<div class="row">
											<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Full Name', 'school-mgt'); ?> </label> <br>
												<label class="word_brack view_page_content_labels"><?php echo $student_data->display_name; ?></label>
											</div>
											<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Alt. Mobile Number', 'school-mgt'); ?> </label><br>
												<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->alternet_mobile_number)){ ?>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $student_data->alternet_mobile_number; }else{ echo "N/A"; } ?></label>
											</div>
											<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br>
												<label class="view_page_content_labels">
													<?php 
													if($student_data->gender=='male') 
														echo esc_attr__('Male','school-mgt');
													elseif($student_data->gender=='female') 
														echo esc_attr__('Female','school-mgt');
													elseif($student_data->gender=='other') 
														echo esc_attr__('Other','school-mgt');
													else
														echo "N/A";
													?>
												</label>
											</div>
											<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br>
												<label class="word_brack view_page_content_labels"><?php echo mj_smgt_getdate_in_input_box($student_data->birth_date); ?></label>
											</div>
											<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label><br>
												<label class="word_brack view_page_content_labels"><?php echo $student_data->city; ?></label>
											</div>
											<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
												<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->state)){ echo $student_data->state; }else{ echo "N/A"; } ?></label>
											</div>
											<div class="col-xl-3 col-md-3 col-sm-12 address_rs_css margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zipcode', 'school-mgt'); ?> </label><br>
												<label class="word_brack view_page_content_labels"><?php echo $student_data->zip_code; ?></label>
											</div>
										</div>
									</div>	
								</div>

								<!-- Sibling Information  -->
								<?php
							
								if(!empty($sibling_information[0]->siblingsstudent))
								{
									?>
									<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
										<div class="guardian_div">
										<label class="view_page_label_heading"> <?php esc_html_e('Siblings Information', 'school-mgt'); ?> </label>
											<?php
											
											foreach($sibling_information as $value)
											{
												$sibling_data=get_userdata($value->siblingsstudent);

												if(!empty($sibling_data))
												{
													
													?>
													<div class="row">
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Sibling Name', 'school-mgt'); ?> </label> <br>
														<label class="word_brack view_page_content_labels"><?php  echo mj_smgt_student_display_name_with_roll($sibling_data->ID);?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Sibling Email', 'school-mgt'); ?> </label> <br>
														<label class="word_brack view_page_content_labels"><?php echo $sibling_data->user_email;?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Class', 'school-mgt'); ?> </label><br>
														<label class="word_brack ftext_style_capitalization view_page_content_labels"><?php echo smgt_get_class_section_name_wise($value->siblingsclass,$value->siblingssection); ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Mobile Number', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($sibling_data->mobile_number)){ echo "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '.$sibling_data->mobile_number;}else{ echo 'N/A'; }?></label>
													</div>
												</div>
													<?php
												}
												
											}
											?>
										</div>	
									</div>
									<?php
								}
							?>
								<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
									<?php
									if($student_data->parent_status == 'Father' || $student_data->parent_status == 'Both')
									{
										if(!empty($student_data->father_first_name))
										{
											?>
											<div class="guardian_div">
												<label class="view_page_label_heading"> <?php esc_html_e('Father Information', 'school-mgt'); ?> </label>
												<div class="row">
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Name', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php echo esc_html_e($student_data->fathersalutation,'school-mgt').' '.$student_data->father_first_name.' '.$student_data->father_middle_name.' '.$student_data->father_last_name; ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Email', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_email)){ echo $student_data->father_email; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels font_transfer_capitalize">
														<?php 
														if(!empty($student_data->fathe_gender))
														{ 
															if($student_data->fathe_gender=='male') 
																echo esc_attr__('Male','school-mgt');
															elseif($student_data->fathe_gender=='female') 
																echo esc_attr__('Female','school-mgt');
																
														}else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(($student_data->father_birth_date == "" || $student_data->father_birth_date == "01/01/1970")){ echo "N/A"; }else{ echo mj_smgt_getdate_in_input_box($student_data->father_birth_date); }  ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Address', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_address)){ echo $student_data->father_address; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_state_name)){ echo $student_data->father_state_name; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_city_name)){ echo $student_data->father_city_name; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zip Code', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_zip_code)){ echo $student_data->father_zip_code; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Mobile No.', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_mobile)){ echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $student_data->father_mobile; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('School Name', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_school)){ echo $student_data->father_school; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Medium of Instruction', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_medium)){ echo $student_data->father_medium; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Qualification', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_education)){ echo $student_data->father_education; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Annual Income', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->fathe_income)){ echo mj_smgt_get_currency_symbol().''.$student_data->fathe_income; }else{ echo "N/A"; } ?></label>
													</div>

													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Occupation', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_occuption)){ echo $student_data->father_occuption; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Proof of Qualification', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels">
															<?php
															$father_doc=str_replace('"[','[',$student_data->father_doc);
															$father_doc1=str_replace(']"',']',$father_doc);
															$father_doc_info=json_decode($father_doc1);
															?>
															<p class="user-info view_page_content_labels"> 
															<?php if (!empty($father_doc_info[0]->value))
															{ 
																?>
																<a download href="<?php print content_url().'/uploads/school_assets/'.'$father_doc_info[0]->value;' ?>"  class="status_read btn btn-default"><i class="fa fa-download"></i><?php
																if(!empty($father_doc_info[0]->title))
																{									
																	echo $father_doc_info[0]->title;
																}
																else
																{
																	esc_html_e(' Download', 'school-mgt');
																}
																?>
																</a>
																<?php
															}
															else
															{
																echo "N/A";
															}
															?>
														</label>
													</div>
													
												</div>
											</div>	
											<br>
											<?php
										}
									}
									?>
									<?php
									if($student_data->parent_status == 'Mother' || $student_data->parent_status == 'Both')
									{
										if(!empty($student_data->mother_first_name))
										{
											?>
											<div class="guardian_div">
												<label class="view_page_label_heading"> <?php esc_html_e('Mother Information', 'school-mgt'); ?> </label>
												<div class="row">
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Name', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php echo esc_html_e($student_data->mothersalutation,'school-mgt').' '.$student_data->mother_first_name.' '.$student_data->mother_middle_name.' '.$student_data->mother_last_name; ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Email', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_email)){ echo $student_data->mother_email; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels font_transfer_capitalize">
															<?php 
															if(!empty($student_data->mother_gender))
															{ 
																if($student_data->mother_gender=='male') 
																	echo esc_attr__('Male','school-mgt');
																elseif($student_data->mother_gender=='female') 
																	echo esc_attr__('Female','school-mgt');
															}
															else
															{ 
																echo "N/A"; 
															} ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_birth_date)){ echo mj_smgt_getdate_in_input_box($student_data->mother_birth_date); }else{ echo "N/A"; }  ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Address', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_address)){ echo $student_data->mother_address; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_state_name)){ echo $student_data->mother_state_name; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_city_name)){ echo $student_data->mother_city_name; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zip Code', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_zip_code)){ echo $student_data->mother_zip_code; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Mobile No.', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_mobile)){ echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $student_data->mother_mobile; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('School Name', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_school)){ echo $student_data->mother_school; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Medium of Instruction', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_medium)){ echo $student_data->mother_medium; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Qualification', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_education)){ echo $student_data->mother_education; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Annual Income', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_income)){ echo mj_smgt_get_currency_symbol().''.$student_data->mother_income; }else{ echo "N/A"; } ?></label>
													</div>

													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Occupation', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_occuption)){ echo $student_data->mother_occuption; }else{ echo "N/A"; } ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Proof of Qualification', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels">
															<?php
															$mother_doc=str_replace('"[','[',$student_data->mother_doc);
															$mother_doc1=str_replace(']"',']',$mother_doc);
															$mother_doc_info=json_decode($mother_doc1);
															?>
															<p class="user-info">  
															<?php if (!empty($mother_doc_info[0]->value))
															{
																?>
																<a download href="<?php print content_url().'/uploads/school_assets/'.'$mother_doc_info[0]->value;' ?>"  class=" btn btn-default" <?php if (empty($mother_doc_info[0])) { ?> disabled <?php } ?>><i class="fa fa-download"></i>
																<?php
																if(!empty($mother_doc_info[0]->title))
																{									
																	echo $mother_doc_info[0]->title;
																}
																else
																{
																	esc_html_e(' Download', 'school-mgt');
																}
																?></a>
																<?php 
															} 
															else
															{
																echo "N/A";
															}
															?>
														</label>
													</div>
													
												</div>
											</div>	
											<?php
										}
									}
									?>
								</div>
							</div>
						</div>
						<?php
						}
						?>
				</div><!-- END PANEL BODY DIV-->
			</section>
			<!-- Detail Page Body Content Section End -->
		</div>
	</div>
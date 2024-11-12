<?php
	$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
	$teacher_obj = new Smgt_Teacher;
	$obj_route = new Class_routine();
	$teacher_data=get_userdata($_REQUEST['teacher_id']);
	$teacher_id = $_REQUEST['teacher_id'];
	$user_access=mj_smgt_get_userrole_wise_access_right_array();
	$school_obj = new School_Management ( get_current_user_id () );
	$role=$school_obj->role;
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
							$umetadata=mj_smgt_get_user_image($teacher_data->ID);
							?>
							<img class="user_view_profile_image" src="<?php if(!empty($umetadata)) {echo $umetadata; }else{ echo get_option( 'smgt_teacher_thumb_new' );}?>">
							<div class="row profile_user_name">
								<div class="float_left view_top1">
									<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
										<label class="view_user_name_label"><?php echo esc_html($teacher_data->display_name);?></label>
										<?php
										if($user_access_edit=='1')
										{
											?>
											<div class="view_user_edit_btn">
												<a class="color_white margin_left_2px" href="?page=smgt_teacher&tab=addteacher&action=edit&teacher_id=<?php echo $teacher_data->ID;?>">
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/edit.png"?>">
												</a>
											</div>
											<?php
										}
										?>
									</div>
									<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
										<div class="view_user_phone float_left_width_100">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<lable><?php echo $teacher_data->mobile_number;?></label>
										</div>
									</div>
								</div>
							</div>
							<div class="row view_user_teacher_label">
								<div class="col-xl-12 col-md-12 col-sm-12">
									<div class="view_top2">
										<div class="row view_user_teacher_label">
											<div class="col-md-12 address_student_div">
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $teacher_data->address; ?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-3 col-sm-2 add_btn_possition_teacher_res">
						<div class="group_thumbs">
							<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Group.png"?>">
						</div>
						<div class="viewpage_add_icon dropdown_menu_icon">
							<li class="dropdown_icon_menu_div">
								<a class="dropdown_icon_link" href="#" data-bs-toggle="dropdown" aria-expanded="false" >
									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/add_more_icon.png"?>" class="add_more_icon_detailpage">
								</a>
								<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
									<li class="float_left_width_100">
										<a href="admin.php?page=smgt_attendence&tab=teacher_attendance&tab1=teacher_attendences_list" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/plus_icon.png"?>" alt="" class="image_margin_right_10px"><?php esc_html_e('Attendance','school-mgt');?></a>
									</li>
									<li class="float_left_width_100">
										<a href="admin.php?page=smgt_route&tab=teacher_timetable" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/plus_icon.png"?>" alt="" class="image_margin_right_10px"><?php esc_html_e('Class Schedule','school-mgt');?></a>
									</li>
								</ul>
							</li>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Detail Page Header End -->

		<!-- Detail Page Tabing Start -->
		<section id="body_area" class="teacher_view_tab">
			<div class="row">
				<div class="col-xl-12 col-md-12 col-sm-12 rs_width">
					<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
						<li class="<?php if($active_tab1=='general'){?>active<?php }?>">
							<a href="admin.php?page=smgt_teacher&tab=view_teacher&action=view_teacher&tab1=general&teacher_id=<?php echo $_REQUEST['teacher_id'];?>"
							class="padding_left_0 tab <?php echo $active_tab1 == 'general' ? 'active' : ''; ?>">
							<?php esc_html_e('GENERAL', 'school-mgt'); ?></a>
						</li>
						<li class="<?php if($active_tab1=='class_list'){?>active<?php }?>">
							<a href="admin.php?page=smgt_teacher&tab=view_teacher&action=view_teache&tab1=class_list&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'class_list' ? 'active' : ''; ?>">
							<?php esc_html_e('CLass List', 'school-mgt'); ?></a>
						</li>
						<li class="<?php if($active_tab1=='schedule'){?>active<?php }?>">
							<a href="admin.php?page=smgt_teacher&tab=view_teacher&action=view_teache&tab1=schedule&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'schedule' ? 'active' : ''; ?>">
							<?php esc_html_e('Class Schedule', 'school-mgt'); ?></a>
						</li>
						<li class="<?php if($active_tab1=='attendance'){?>active<?php }?>">
							<a href="admin.php?page=smgt_teacher&tab=view_teacher&action=view_teache&tab1=attendance&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'attendance' ? 'active' : ''; ?>">
							<?php esc_html_e('Attendance', 'school-mgt'); ?></a>
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
					//--- general tab start ----//
					if($active_tab1 == "general")
					{
						?>
						<div class="row margin_top_15px margin_left_3">
							<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
								<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'school-mgt'); ?> </label><br/>
								<label class="view_page_content_labels"> <?php echo $teacher_data->user_email; ?> </label>
							</div>
							<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
								<label class="view_page_header_labels"> <?php esc_html_e('Mobile Number', 'school-mgt'); ?> </label><br/>
								<label class="view_page_content_labels">
								+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $teacher_data->mobile_number; ?>
								</label>
							</div>
							<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
								<label class="view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br/>
								<label class="view_page_content_labels"> <?php echo esc_html_e(ucfirst($teacher_data->gender),'school-mgt'); ?></label>
							</div>
							<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
								<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br/>
								<label class="view_page_content_labels"> <?php echo mj_smgt_getdate_in_input_box($teacher_data->birth_date); ?>
								</label>
							</div>
							<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
								<label class="view_page_header_labels"> <?php esc_html_e('Position', 'school-mgt'); ?> </label><br/>
								<label class="view_page_content_labels"><?php if(!empty($teacher_data->possition)){ echo $teacher_data->possition; }else{ echo "N/A"; } ?>
								</label>
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
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label> <br>
												<label class="view_page_content_labels"><?php echo $teacher_data->city; ?></label>
											</div>
											<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
												<label class="ftext_style_capitalization view_page_content_labels"><?php if(!empty($teacher_data->state)){ echo $teacher_data->state; }else{ echo "N/A"; } ?></label>
											</div>
											<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zip Code', 'school-mgt'); ?> </label><br>
												<label class="view_page_content_labels"><?php echo $teacher_data->zip_code; ?></label>
											</div>
											<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Alternate Mobile Number', 'school-mgt'); ?> </label><br>
												<lable class="view_page_content_labels"><?php if(!empty($teacher_data->alternet_mobile_number)){ ?>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $teacher_data->alternet_mobile_number; }else{ echo "N/A"; } ?></label>
											</div>
											<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Working Hour', 'school-mgt'); ?> </label><br>
												<label class="view_page_content_labels">
													<?php
													if(!empty($teacher_data->working_hour))
													{
														$working_data = $teacher_data->working_hour;
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
												<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Class Name', 'school-mgt'); ?> </label><br>
												<label class="view_page_content_labels">
												<?php
													$classes="";
													$classes = $teacher_obj->mj_smgt_get_class_by_teacher($teacher_data->ID);
													$classname = "";
													foreach($classes as $class)
													{
														$classname .= mj_smgt_get_class_name($class['class_id']).",";
													}
													$classname_rtrim=rtrim($classname,", ");
													$classname_ltrim=ltrim($classname_rtrim,", ");
													echo $classname_ltrim;
												?>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					//--- general tab End ----//
					//---  attendance tab start --//
					elseif($active_tab1 == "attendance")
					{
						$attendance_list = mj_smgt_monthly_attendence_teacher($teacher_id);
						
						if(!empty($attendance_list))
						{
							?>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									"use strict";
									jQuery('#attendance_list_detailpage').DataTable({
										"responsive": true,
										//"order": [[ 1, "desc" ]],
										"aoColumns":[
													{"bSortable": false},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true}],
										dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',
										language:<?php echo mj_smgt_datatable_multi_language();?>
										});
										$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
									$('.dataTables_filter').addClass('search_btn_view_page');
								} );
							</script>

							<div class="table-div"><!-- PANEL BODY DIV START -->
								<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
									<table id="attendance_list_detailpage" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
												<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>
												<th><?php esc_attr_e('Attendance Date','school-mgt');?></th>  
												<th><?php esc_attr_e('Day','school-mgt');?> </th>  
												<th><?php esc_attr_e('Attendance By','school-mgt');?> </th>  
												<th><?php esc_attr_e('Status','school-mgt');?> </th>  
												<th><?php esc_attr_e('Comment','school-mgt');?> </th>  
											</tr>
										</thead>
										<tbody>
											<?php
											$i=0;
											$srno = 1;
											if(!empty($attendance_list))
											{
												foreach ($attendance_list as $retrieved_data)
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
														<td class="user_image width_50px profile_image_prescription">
															<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image center">
															</p>
														</td>

														<td class=""><?php echo mj_smgt_get_user_name_byid($retrieved_data->user_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>

														<td class="name"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendence_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendance Date','school-mgt');?>"></i></td>

														<td class="">
															<?php
																$curremt_date = $retrieved_data->attendence_date;
																$day=date("D", strtotime($curremt_date));
																if($day == 'Mon')
																{
																	esc_html_e('Monday','school-mgt');
					
																}  
																elseif($day == 'Sun')
																{
						
																	esc_html_e('Sunday','school-mgt');
						
																} 
						
																elseif($day == 'Tue')
						
																{
						
																	esc_html_e('Tuesday','school-mgt');
						
																}
																elseif($day == 'Wed')
																{
						
																	esc_html_e('Wednesday','school-mgt');
						
																}
																elseif($day == 'Thu')
						
																{
						
																	esc_html_e('Thursday','school-mgt');
						
																}
						
																elseif($day == 'Fri')
						
																{
																	esc_html_e('Friday','school-mgt');
																}
																elseif($day == 'Sat')
																{

																	esc_html_e('Saturday','school-mgt');
																}
															?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i>
														</td>
														<td class="name">
															<?php echo mj_smgt_get_display_name($retrieved_data->attend_by); ?>
															<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance By','school-mgt');?>" ></i>
														</td>
														<td class="name">
															<?php $status_color =  MJ_smgt_attendance_status_color($retrieved_data->status);?>
															<span style="color:<?php echo $status_color;?>;">
																<?php echo esc_html__($retrieved_data->status,"school-mgt"); ?>
															</span>
															<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance Status','school-mgt');?>" ></i>
														</td>
														<td class="name">
															<?php
															if(!empty($retrieved_data->comment))
															{
																$comment =$retrieved_data->comment;
																$grade_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
																echo $grade_comment;
															}
															else
															{
																echo "N/A";
															}
															?>
															<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php 
															if(!empty($retrieved_data->comment))
															{ 
																echo $retrieved_data->comment;
															} 
															else
															{  _e('Comment','school-mgt');
															}
															?>
															"></i>
														</td>
													</tr>
													<?php
													$i++;
													$srno++;
												}
											}
											?>
										</tbody>
									</table>

								</div><!-- TABLE RESPONSIVE DIV END -->
							</div>
							<?php
						}
						else
						{ ?>
							
								<div class="no_data_list_div">
									<a href="<?php echo admin_url().'admin.php?page=smgt_attendence&tab=teacher_attendance&tab1=teacher_attendences';?>">
										<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
									</div>
								</div>
							<?php
						}
					}
					
					//---  attendance tab End --//

					//---  Class List tab Start --//
					elseif($active_tab1 == "class_list")
					{
						$classes = $teacher_obj->mj_smgt_get_class_by_teacher($teacher_id);
						if($classes)
						{
						?>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									"use strict";
									jQuery('#class_list_detailpage').DataTable({
										"responsive": true,
										//"order": [[ 1, "desc" ]],
										"aoColumns":[
													{"bSortable": false},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": false},],
										dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',
										language:<?php echo mj_smgt_datatable_multi_language();?>
										});
										$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
									$('.dataTables_filter').addClass('search_btn_view_page');
								} );
							</script>
							<div class="table-div"><!-- PANEL BODY DIV START -->
								<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
									<table id="class_list_detailpage" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
												<th><?php esc_attr_e('Class Name','school-mgt');?></th>
												<th><?php esc_attr_e('Section','school-mgt');?></th>
												<th><?php esc_attr_e('Class Numeric Value','school-mgt');?></th>  
												<th><?php esc_attr_e('Student Capacity','school-mgt');?> </th>  
												<th class="text_align_end"><?php esc_attr_e('Action','school-mgt');?> </th>  
											</tr>
										</thead>
										<tbody>
											<?php
											$i=0;
											
											if(!empty($classes))
											{
												foreach ($classes as $class_id)
												{
													$section_id = mj_smgt_get_section_by_class_id($class_id);
													$section_name = "";
													$retrieved_data = mj_smgt_get_class_data_by_class_id($class_id);

													if(!empty($retrieved_data))
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
														<td class="user_image width_50px"><img src="<?php echo get_option( 'smgt_student_thumb_new' ) ?>" class="img-circle" /></td>
														<td ><?php if($retrieved_data->class_name){ echo $retrieved_data->class_name; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Name','school-mgt');?>" ></i></td>
														<td >
															<?php
															
																foreach($section_id as $section)
																{
																	$section_name.= $section->section_name.", ";
																}
																$section_name_rtrim=rtrim($section_name,", ");
																$section_name_ltrim=ltrim($section_name_rtrim,", ");
																if(!empty($section_name_ltrim))
																{
																	echo $section_name_ltrim;
																	
																}
																else
																{
																	esc_attr_e('No Section', 'school-mgt');;
																} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Section','school-mgt');?>" ></i></td>
														<td><?php if($retrieved_data->class_num_name){ echo $retrieved_data->class_num_name; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Numeric Name','school-mgt');?>" ></i></td>
														<?php
														$class_id=$retrieved_data->class_id;
														$user=count(get_users(array(
															'meta_key' => 'class_name',
															'meta_value' => $class_id
														)));
														?>
														<td>
														<?php	
															echo $user.' ';
															esc_attr_e('Out Of', 'school-mgt');
															echo ' '.$retrieved_data->class_capacity;
														?>	
														<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Student Capacity','school-mgt');?>" ></i></td>
														<td class="action">  
															<div class="smgt-user-dropdown">
																<ul class="" style="margin-bottom: 0px !important;">
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																		</a>
																		<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																			<li class="float_left_width_100 ">
																				<a class="float_left_width_100" href="?page=smgt_class&tab=class_wise_student_list&class_id=<?php echo $retrieved_data->class_id;?>"><i class="fa fa-list"></i><?php esc_attr_e('Student List','school-mgt');?></a>
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
												}
											}
											?>
										</tbody>
									</table>

								</div><!-- TABLE RESPONSIVE DIV END -->
							</div>

						<?php
						}
						else
						{
							
						}
					}
					//---  Class List tab End --//

					//---- class schedule tab start ----//
					elseif($active_tab1 == "schedule")
					{
						?>
							<div id="Section1" class="">
								<div class="row">
									<div class="col-lg-12">
										<div class="">
											<div class="class_border_div card-content">
												<table class="table table-bordered class_schedule">
													<?php
													foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)
													{	?>
														<tr>
															<th width="100"><?php echo $dayname;?></th>
															<td>
																<?php
																
																$period_1 = $obj_route->mj_smgt_get_periad_by_teacher($teacher_data->ID,$daykey);
																$period_2 = $obj_route->mj_smgt_get_periad_by_particular_teacher($teacher_data->ID,$daykey);
																if(!empty($period_1))
																{
																	$period = array_merge($period_1, $period_2);
																}
																if(!empty($period))
																	foreach($period as $period_data)
																	{
																		echo '<div class="btn-group m-b-sm">';
																		echo '<button class="btn btn-primary class_list_button dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);

																		$start_time_data = explode(":", $period_data->start_time);
																		$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
																		$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
																		

																		$end_time_data = explode(":", $period_data->end_time);
																		$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
																		$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
																		
																		echo '<span class="time"> ('.$start_hour.':'.$start_min.' - '.$end_hour.':'.$end_min.') </span>';

																		echo '<span>'.mj_smgt_get_class_name($period_data->class_id).'</span>';
																		echo '</span></span><span class="caret"></span></button>';
																		echo '<ul role="menu" class="dropdown-menu">
																				<li><a href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.esc_attr__('Edit','school-mgt').'</a></li>
																				<li><a href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'.esc_attr__('Delete','school-mgt').'</a></li>
																			</ul>';
																		echo '</div>';
																	}
																?>
															</td>
														</tr>
														<?php
													}
													?>
												</table>

											</div>
										</div>
									</div>
								</div>
							</div>
						<?php
					}
					//---- class schedule tab End ----//
					?>
				</div><!-- END PANEL BODY DIV-->
			</section>
		<!-- Detail Page Body Content Section End -->
	</div><!-- END CONTENT BODY DIV-->
</div><!-- END PANEL BODY DIV-->

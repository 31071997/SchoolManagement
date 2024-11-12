<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$role_name=mj_smgt_get_user_role(get_current_user_id());
$active_tab = isset($_GET['tab'])?$_GET['tab']:'supportstaff_list';
$obj_admission=new smgt_admission;
//--------------- ACCESS WISE ROLE -----------//
$user_access=mj_smgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		mj_smgt_access_right_page_not_access_message();
		die;
	}
}
?>
<!-- Nav tabs -->
<div class="panel-body panel-white frontend_list_margin_30px_res"><!------------ PENAL BODY ------------->
	<?php
	//------------------ SUPPORT STAFF LIST TAB ----------------//
	if($active_tab == 'supportstaff_list')
	{ 
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				"use strict";
				var table =  jQuery('#supportstaff_list').DataTable({
					//stateSave: true,
					"order": [[ 2, "asc" ]],
					"dom": 'lifrtp',
					"aoColumns":[	                  
								{"bSortable": false},	                  	                
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": true},
								<?php
									if($role_name == "supportstaff" || $role_name == "teacher")
									{
								?>
								{"bSortable": true},	                  
								{"bSortable": false}
							<?php }
							?>],
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
				//-------------- multiple select checkbox ----------//
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
				//----------- multiple select delete js -----------//
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
		<div class="panel-body"><!------------- PENAL BODY ----------->
			<div class="table-responsive"><!------------- TABLE RESPONSIVE ----------->
				<!----------- SUPPORT STAFF LIST FORM START ---------->
				<form id="frm-example" name="frm-example" method="post">
					<table id="supportstaff_list" class="display dataTable exam_datatable" cellspacing="0" width="100%">
						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
							<tr>
								<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
								<th><?php esc_attr_e( 'Support Staff Name & Email', 'school-mgt' ) ;?></th>	
								<?php
									if($role_name == "supportstaff" || $role_name == "teacher")
									{
								?>		  
									<th> <?php esc_attr_e( 'Mobile Number', 'school-mgt' ) ;?></th>
								<?php
									}
								?>
								<th> <?php echo esc_attr_e( 'Gender', 'school-mgt' ) ;?></th>
								<th> <?php echo esc_attr_e( 'Date of Birth', 'school-mgt' ) ;?></th>
								<?php
									if($role_name == "supportstaff" || $role_name == "teacher")
									{
								?>	
									<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
								<?php
									}
								?>
							</tr>
						</thead>
						<tbody>
							<?php
							if($school_obj->role == 'supportstaff')
							{
								$own_data=$user_access['own_data'];
								if($own_data == '1')
								{ 
									$supportstaff=mj_smgt_get_own_usersdata('supportstaff');
								}
								else
								{
									$supportstaff=mj_smgt_get_usersdata('supportstaff');
								}
							}
							else
							{
								$supportstaff=mj_smgt_get_usersdata('supportstaff');
							}
							if(!empty($supportstaff))
							{
								foreach ($supportstaff as $retrieved_data)
								{ 
									?>
									<tr>
										<td class="user_image width_50px">
										<?php
											if($role_name == "supportstaff" || $role_name == "teacher")
											{
										?>
											<a class="" href="?dashboard=user&page=supportstaff&tab=view_supportstaff&action=view_supportstaff&supportstaff_id=<?php echo $retrieved_data->ID;?>">
											<?php
											}
											else{
												?>
												<a class="" href="#">
												<?php
											}
											?>
												<?php 
													$uid=$retrieved_data->ID;
													$umetadata=mj_smgt_get_user_image($uid);
													if(empty($umetadata))
													{
														echo '<img src='.get_option( 'smgt_supportstaff_thumb_new' ).' height="50px" width="50px" class="img-circle" />';
													}
													else
													{
														echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';
													}
												?>
											</a>
										</td>
										<td class="name">
											<?php
											if($role_name == "supportstaff" || $role_name == "teacher")
											{
											?>
											<a class="color_black" href="?dashboard=user&page=supportstaff&tab=view_supportstaff&action=view_supportstaff&supportstaff_id=<?php echo $retrieved_data->ID;?>">
											<?php
											}
											else{
												?>
												<a class="" href="#">
												<?php
											}
											?>
												<?php echo $retrieved_data->display_name;?>
											</a>
												<br>
												<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>
										</td>
										<?php
											if($role_name == "supportstaff" || $role_name == "teacher")
											{
										?>
										<td class="">
											+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '. get_user_meta( $uid, 'mobile_number', true );?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile Number','school-mgt');?>" ></i>
										</td>
												<?php
											}
												?>
										<td class="">
											<?php echo esc_html_e(ucfirst(get_user_meta( $uid, 'gender', true )),'school-mgt');?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Gender','school-mgt');?>" ></i>
										</td>

										<td class="">
											<?php
											$birthdate = get_user_meta( $uid, 'birth_date', true ); ?>
											<?php echo mj_smgt_getdate_in_input_box($birthdate); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date of Birth','school-mgt');?>" ></i>
										</td>
										<?php
											if($role_name == "supportstaff" || $role_name == "teacher")
											{
										?>
											<td class="action"> 
												<div class="smgt-user-dropdown">
													<ul class="" style="margin-bottom: 0px !important;">
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
															</a>
															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																<li class="float_left_width_100">
																	<a href="?dashboard=user&page=supportstaff&tab=view_supportstaff&action=view_supportstaff&supportstaff_id=<?php echo $retrieved_data->ID;?>"
																	class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?></a>
																</li>
																<?php
																if($user_access['edit'] == '1')
																{
																?>
																	<li class="float_left_width_100 border_bottom_item">
																		<a href="?dashboard=user&page=supportstaff&tab=addsupportstaff&action=edit&supportstaff_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit', 'school-mgt' ) ;?></a>
																	</li>
																	<?php 
																} ?>
																<?php 
																if($user_access['delete'] =='1')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=supportstaff&tab=supportstaff_list&action=delete&supportstaff_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"> </i>
																		<?php esc_attr_e( 'Delete', 'school-mgt' ) ;?> </a>
																	</li>
																	<?php 
																} ?>
											
															</ul>
														</li>
													</ul>
												</div>	
											</td>
											<?php
											}
											?>
									</tr>
									<?php
								}				
							}
							?>
						</tbody>
					</table>
				</form><!----------- SUPPORT STAFF LIST FORM START ---------->
			</div><!------------- TABLE RESPONSIVE ----------->
		</div><!------------- PENAL BODY ----------->
		<?php
	}
	//----------------- VIEW SUPPIRT STAFF TAB -----------------//
	if($active_tab == 'view_supportstaff')
	{
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
												if($user_access['edit'] == '1')
												{
													?>
													<div class="view_user_edit_btn">
														<a class="color_white margin_left_2px" href="?dashboard=user&page=supportstaff&tab=addsupportstaff&action=edit&supportstaff_id=<?php echo $staff_data->ID;?>">
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
							<div class="col-xl-2 col-md-3 col-sm-2 group_thumbs rtl_width_25px">
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
									<label class="word_brack view_page_content_labels"> <?php echo esc_html_e(ucfirst($staff_data->gender),'school-mgt'); ?></label>	
								</div>
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br/>
									<label class="word_brack view_page_content_labels"> <?php echo mj_smgt_getdate_in_input_box($staff_data->birth_date);?> </label>
								</div>
							</div>
							<!-- student Information div start  -->
							<div class="row margin_top_20px">
								<div class="col-xl-12 col-md-12 col-sm-12 rtl_padding_0px">
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
		<?php
	}
	?>
</div> <!------------ PENAL BODY ------------->
 <?php ?>
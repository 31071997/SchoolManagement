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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('class');
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
			if ('class' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('class' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('class' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		"use strict";	
		$('#class_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	
	});
</script>
<?php 
	// This is Class at admin side!!!!!!!!! 
	if(isset($_POST['save_class']))
	{
		$nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_class_admin_nonce' ) )
		{
			$created_date = date("Y-m-d H:i:s");
			$classdata=array('class_name'=>mj_smgt_popup_category_validation(stripslashes($_POST['class_name'])),
							'class_num_name'=>mj_smgt_onlyNumberSp_validation($_POST['class_num_name']),
							'class_capacity'=>mj_smgt_onlyNumberSp_validation($_POST['class_capacity']),	
							'creater_id'=>get_current_user_id(),
							'created_date'=>$created_date
							
			);
			$tablename="smgt_class";
			if($_REQUEST['action']=='edit')
			{
				$classid=array('class_id'=>$_REQUEST['class_id']);
				$result=mj_smgt_update_record($tablename,$classdata,$classid);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_class&tab=classlist&message=2');
					exit;
				}
			}
			else
			{
				/* Setp Wzard */
				$wizard = smgt_setup_wizard_steps_updates('step2_class');
				$result=mj_smgt_insert_record($tablename,$classdata);
				
				if($result)
				{
					wp_redirect (admin_url().'admin.php?page=smgt_class&tab=classlist&message=1');
					exit;
				}
			}
		}
	}
	$tablename="smgt_class";
	/*Delete selected Subject*/
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		{
			foreach($_REQUEST['id'] as $id)
			{
				$result=mj_smgt_delete_class($tablename,$id);
			}
		}
		
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_class&tab=classlist&message=3'); 
			exit;
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_class($tablename,$_REQUEST['class_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_class&tab=classlist&message=3');
			exit;
		}
	}

$active_tab = isset($_GET['tab'])?$_GET['tab']:'classlist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
   		<div class="modal-content">
   			<div class="invoice_data">
    		 </div>
   		</div>
  	</div> 
</div>
<!-- End POP-UP Code -->
<div class="list_padding_5px"> <!--------- list page padding ---------->
	<div  id="" class="class_list"> <!--------- list page main wrapper ---------->
		<?php
		
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Class Added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Class Updated Successfully.','school-mgt');
				break;
			case '3':
				$message_string = esc_attr__('Class Deleted Successfully.','school-mgt');
				break;
		}
		
		if($message)
		{ 
			?>
			<div id="message" class="alert below-h2 notice is-dismissible message_disabled_css alert-dismissible">
				<p><?php echo $message_string;?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div>
			<?php 
		}
		 ?>
		<div class="panel-white"> <!------ penal white -------->
			<div class="panel-body"> <!------ penal body ------->	
				<?php
				if($active_tab == 'classlist')
				{	
					$retrieve_class = mj_smgt_get_all_data($tablename);
				
					if(!empty($retrieve_class))
					{
						?>
			
						<script type="text/javascript">
							jQuery(document).ready(function($)
							{
								"use strict";	
								var table =  jQuery('#class_list').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
											$('#class_list th:first-child').removeClass('sorting_asc');
										},
									//stateSave: true,
									responsive: true,
									"ordering": true,
									dom: 'Qlifrtp',
									"aoColumns":[	                  
										{"bSortable": false, "className": 'sorting_disabled'},
												{"bSortable": false},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": false}],
											
									language:<?php echo mj_smgt_datatable_multi_language();?>
								});
								table.on('draw', function() {
									$('.dtsb-button').text('Add filter');
								});
								$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
								jQuery('#checkbox-select-all').on('click', function(){
								
								var rows = table.rows({ 'search': 'applied' }).nodes();
								jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
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
									if ($('.select-checkbox:checked').length == 0 )
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
						<div class="panel-body">
							<div class="table-responsive">
								<form id="frm-example" name="frm-example" method="post">
									<table id="class_list" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
												<th><?php esc_attr_e('Class Name','school-mgt');?></th>
												<th><?php esc_attr_e('Section','school-mgt');?></th>
												<th><?php esc_attr_e('Class Numeric Value','school-mgt');?></th>
												<th><?php esc_attr_e('Student Capacity','school-mgt');?></th>
												<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
											</tr>
										</thead>
										<tbody>
											<?php 
											foreach ($retrieve_class as $retrieved_data)
											{ 
												$class_id=$retrieved_data->class_id;
												$section_id = mj_smgt_get_section_by_class_id($class_id);
												$section_name = "";
												?>
												<tr>
													<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->class_id;?>"></td>
													<td class="user_image width_50px"><img src="<?php echo get_option( 'smgt_student_thumb_new' ) ?>" class="img-circle" /></td>
													<td ><?php if($retrieved_data->class_name){ echo $retrieved_data->class_name; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Name','school-mgt');?>" ></i></td>
													<td >
														<?php foreach($section_id as $section)
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
													<td><?php if($retrieved_data->class_num_name){ echo $retrieved_data->class_num_name; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Numeric Value','school-mgt');?>" ></i></td>
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
																			<a class="float_left_width_100" href="#" id="addremove" class_id="<?php echo $retrieved_data->class_id;?>" model="class_sec"><i class="fa fa-eye"> </i><?php esc_attr_e('View Or Add Section','school-mgt');?></a>
																		</li>
																		<li class="float_left_width_100 ">
																			<a class="float_left_width_100" href="?page=smgt_class&tab=class_wise_student_list&class_id=<?php echo $retrieved_data->class_id;?>"><i class="fa fa-list"></i><?php esc_attr_e('Student List','school-mgt');?></a>
																		</li>
																		<?php
																		if($user_access_edit == '1')
																		{
																			?>
																			<li class="float_left_width_100 border_bottom_menu">
																				<a href="?page=smgt_class&tab=addclass&action=edit&class_id=<?php echo $retrieved_data->class_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																			</li>

																			<?php 
																		} 
																		if($user_access_delete =='1')
																		{
																			?>
																			<li class="float_left_width_100 ">
																				<a href="?page=smgt_class&tab=classlist&action=delete&class_id=<?php echo $retrieved_data->class_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
											<?php } ?>
										</tbody>
									</table>
									<div class="print-button pull-left">
										<button class="btn-sms-color button_reload">
											<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
											<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
										</button>
										<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
									</div>
								</form>
							</div>
						</div>
						<?php 
					}
					else
					{
						if($user_access_add=='1')
						{
							?>
							<div class="no_data_list_div"> 
								<a href="<?php echo admin_url().'admin.php?page=smgt_class&tab=addclass';?>">
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
				if($active_tab == 'addclass')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/class/add-newclass.php';
					
				}
				if($active_tab == 'class_wise_student_list')
				{
					$class_id = $_REQUEST['class_id'];
					$student_list = get_users(array(
									'meta_key' => 'class_name',
									'meta_value' => $class_id
								));
					if(!empty($student_list))
					{
						?>
						<script type="text/javascript">
							jQuery(document).ready(function($)
							{
								"use strict";	
								var table =  jQuery('#class_wise_student_list').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
									responsive: true,
									"order": [[ 2, "DESC" ]],
									"dom": 'lifrtp',
									"aoColumns":[	                  
												{"bSortable": false},
												{"bSortable": false},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": false}
												],
									language:<?php echo mj_smgt_datatable_multi_language();?>
								});
								$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
							});
						</script>		
						<div class="panel-body">
							<div class="table-responsive">
								<form id="frm-example" name="frm-example" method="post">
									<table id="class_wise_student_list" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th><?php _e( 'Photo', 'school-mgt' ) ;?></th>
												<th><?php esc_attr_e( 'Student Name & Email', 'school-mgt' ) ;?></th>
												<th><?php esc_attr_e('Roll No.','school-mgt');?></th>
												<th><?php esc_attr_e('Class','school-mgt');?></th>
												<th><?php esc_attr_e('Section','school-mgt');?></th>
												<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
											</tr>
										</thead>
										<tbody>
											<?php 
											foreach ($student_list as $retrieved_data)
											{ 
												?>
												<tr>
													<td class="user_image width_50px">
														<a href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>">
															<?php
																$uid=$retrieved_data->ID;
																$umetadata=mj_smgt_get_user_image($uid);
																if(empty($umetadata))
																{
																	echo '<img src='.get_option( 'smgt_student_thumb_new' ).' class="img-circle" />';
																}
																else
																{
																	echo '<img src='.$umetadata.' class="img-circle" />';
																}
															?>
														</a>
													</td>
													<td class="name">
														<a class="color_black" href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>">
															<?php echo $retrieved_data->display_name;?>
														</a>
														<br>
														<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>
													</td>
													<td class="roll_no">
														<?php 
															if(get_user_meta($retrieved_data->ID, 'roll_id', true))
															{
																echo get_user_meta($retrieved_data->ID, 'roll_id',true);
															}
															else
															{
																echo "N/A";
															}
														?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Roll No.','school-mgt');?>" ></i>
													</td>
													<td class="name">
														<?php 
														$class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
														$classname=mj_smgt_get_class_name($class_id);
														if($classname == " ")
														{
															echo "N/A";
														}
														else
														{
															echo $classname;
														}
													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class','school-mgt');?>" ></i>
													</td>
													<td class="name">
													<?php 
														$section_name=get_user_meta($retrieved_data->ID, 'class_section',true);
														if($section_name!=""){
															echo mj_smgt_get_section_name($section_name); 
														}
														else
														{
															esc_attr_e('No Section','school-mgt');;
														}
													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Section','school-mgt');?>" ></i>
													</td>
													<td class="action"> 
														<div class="smgt-user-dropdown">
															<ul class="" style="margin-bottom: 0px !important;">
																<li class="">
																	<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																	</a>
																	<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																		<li class="float_left_width_100">
																			<a href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_html_e('View', 'school-mgt' ) ;?> </a>
																		</li>
																	</ul>
																</li>
															</ul>
														</div>										
													</td>
												</tr>
										<?php } ?>
										</tbody>
									</table>
								</form>
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
				?>
		 	</div> <!------ penal body -------->
		</div><!------ penal white -------->
	</div> <!--------- list page main wrapper ---------->
</div> <!--------- list page padding ---------->
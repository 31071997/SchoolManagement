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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('exam_hall');
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
			if ('exam_hall' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('exam_hall' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('exam_hall' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
	// $('.hall_recipt').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	
	$('#hall_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('#receipt_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});		
				  jQuery('.exam_hall_table').DataTable({
					
					responsive: true,
					bPaginate: false,
					bFilter: false, 
					bInfo: false,
				});   
			 
			$("body").on("click", "#checkbox-select-all", function()
			{
				if($(this).is(':checked',true))  
				{
					$(".my_check").prop('checked', true);  
				}  
				else  
				{  
					$(".my_check").prop('checked',false);  
				}
			});
			$("body").on("click", ".my_check", function()
			{
				if(false == $(this).prop("checked"))
				{
					$("#checkbox-select-all").prop('checked', false);
				}
				if ($('.my_check:checked').length == $('.my_check').length )
				{
					$("#checkbox-select-all").prop('checked', true);
				}
			});

});
</script>
<?php 
	//------- Send Mail For exam receipt ---------------//
	if(isset($_POST['send_mail_exam_receipt']))
	{
		$exam_id=$_POST['exam_id'];
	 	//---------- Asigned Student Data --------//
		global $wpdb;
		$table_name_smgt_exam_hall_receipt = $wpdb->prefix . "smgt_exam_hall_receipt";
		$student_data_asigned = $wpdb->get_results( "SELECT user_id FROM $table_name_smgt_exam_hall_receipt where exam_id=".$exam_id);
		
		//------- SEND MAIL FOR EXAM RECEIPT GENERATED ---------------//
		if(!empty($student_data_asigned))
		{
			$device_token = array();
			foreach($student_data_asigned as $student_id)
			{
				$device_token[] = get_user_meta($student_id->user_id, 'token_id' , true);
				
				$headers='';
				$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
				$userdata=get_userdata($student_id->user_id);
				$exam_data= mj_smgt_get_exam_by_id($exam_id);
				$student_email = $userdata->user_email;
				$string = array();
				$string['{{student_name}}']   = $userdata->display_name;
				$string['{{school_name}}'] =  get_option('smgt_school_name');
				$msgcontent                =  get_option('exam_receipt_content');		
				$msgsubject				   =  get_option('exam_receipt_subject');
				$message = mj_smgt_string_replacement($string,$msgcontent);
				$student_id_new=$student_id->user_id;
				mj_smgt_send_mail_receipt_pdf($student_email,$msgsubject,$message,$student_id_new,$exam_id);  
			}
			/* Start Send Push Notification */

			$title = esc_attr__('New Notification For Exam Receipt','school-mgt');
			$text = esc_attr__('Your Exam Hall Receipt has been generated.','school-mgt');
			$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));
			$json = json_encode($notification_data);
			$message =	MJ_smgt_send_push_notification($json);

			/* End Send Push Notification */
			wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=exam_hall_receipt&message=4');

		}

	}
	// This is Class at admin side!!!!!!!!! 
	//---------delete record--------------------
	$tablename="hall";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_hall($tablename,$_REQUEST['hall_id']);
		if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=3');
			}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
		{
			$result=mj_smgt_delete_hall($tablename,$id);
			wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=3');
		}
				
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=3');
		}
	}
	//----------insert and update--------------------
	if(isset($_POST['save_hall']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_hall_admin_nonce' ) )
		{
			$created_date = date("Y-m-d H:i:s");
			$hall_data=array('hall_name'=>mj_smgt_popup_category_validation($_POST['hall_name']),
							'number_of_hall'=>mj_smgt_onlyNumberSp_validation($_POST['number_of_hall']),
							'hall_capacity'=>mj_smgt_onlyNumberSp_validation($_POST['hall_capacity']),
							'description'=>mj_smgt_address_description_validation($_POST['description']),
							'date'=>$created_date,
							'created_by'=>get_current_user_id()
							);
			//table name without prefix
			$tablename="hall";
			
			if($_REQUEST['action']=='edit')
			{
				$transport_id=array('hall_id'=>$_REQUEST['hall_id']);
				$result=mj_smgt_update_record($tablename,$hall_data,$transport_id);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=2');
				}
			}
			else
			{
				$result=mj_smgt_insert_record($tablename,$hall_data);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=1');
				}
			}
	    }
	}	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'hall_list'; ?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="view_popup"></div>     
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<div class="page-inner"><!-------- Page Inner -------->
	<div id=""  class="class_list main_list_margin_5px"> 
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Exam Hall Added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Exam Hall Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Exam Hall Deleted Successfully.','school-mgt');
				break;
			case '4':
				$message_string = esc_attr__('Exam Hall Mail Send Successfully.','school-mgt');
				break;
				
		}
		
		if($message)
		{ 
			?>
			<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
				<p><?php echo $message_string;?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div>
			<?php
		} 
		?> 
		<div class="panel-white"><!-------- Penal White -------->
			<div class=""> 
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
					<li class="<?php if($active_tab=='hall_list'){?>active<?php }?>">			
						<a href="?page=smgt_hall&tab=hall_list" class="padding_left_0 tab <?php echo $active_tab == 'hall_list' ? 'active' : ''; ?>">
						<?php esc_html_e('Exam Hall List', 'school-mgt'); ?></a> 
					</li>
					<?php
					$action = "";
					if(!empty($_REQUEST['action']))
					{
						$action = $_REQUEST['action'];
					}
					if($active_tab=='addhall' && $action == 'edit')
					{
						?>
						<li class="<?php if($active_tab=='addhall'){?>active<?php }?>">
							<a href="#" class="padding_left_0 tab <?php echo $active_tab == 'addhall' ? 'active' : ''; ?>">
							<?php esc_html_e('Edit Exam Hall', 'school-mgt'); ?></a> 
						</li> 
						<?php
					}
					elseif($active_tab=='addhall')
					{
						?>
						<li class="<?php if($active_tab=='addhall'){?>active<?php }?>">
							<a href="?page=smgt_hall&tab=addhall" class="padding_left_0 tab <?php echo $active_tab == 'addhall' ? 'active' : ''; ?>">
							<?php esc_html_e('Add Exam Hall', 'school-mgt'); ?></a> 
						</li> 
						<?php
					}
					?>
					<li class="<?php if($active_tab=='exam_hall_receipt'){?>active<?php }?>">
						<a href="?page=smgt_hall&tab=exam_hall_receipt" class="padding_left_0 tab <?php echo $active_tab == 'exam_hall_receipt' ? 'active' : ''; ?>">
						<?php esc_html_e('Exam Hall Receipt', 'school-mgt'); ?></a> 
					</li>  
				</ul> 
				
				<?php
				if($active_tab == 'hall_list')
				{	
					$retrieve_class = mj_smgt_get_all_data($tablename);
					if(!empty($retrieve_class))
					{
					?>
						<script type="text/javascript">
							jQuery(document).ready(function($)
							{
								"use strict";	
								
								var table =  jQuery('#hall_list_admin').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										//stateSave: true,
									responsive: true,
									"order": [[ 2, "asc" ]],
									"dom": 'lifrtp',
									"aoColumns":[	                  
												{"bSortable": false},	                 
												{"bSortable": false},
												{"bSortable": true},
												{"bSortable": true},
												{"bSortable": false},
												{"bSortable": true},	                 	                  
												{"bSortable": false}],
									language:<?php echo mj_smgt_datatable_multi_language();?>
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
						<div class="table-responsive margin_top_20px">
							<form id="frm-example" name="frm-example" method="post">
								<table id="hall_list_admin" class="display" cellspacing="0" width="100%">
									<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
										<tr>
											<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
											<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
											<th><?php esc_attr_e('Hall Name','school-mgt');?></th>
											<th><?php esc_attr_e('Hall Numeric Value','school-mgt');?></th>
											<th><?php esc_attr_e('Hall Capacity','school-mgt');?></th>
											<th><?php esc_attr_e('Description','school-mgt');?></th>
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
												<td class="checkbox_width_10px"><input type="checkbox" name="id[]" class="smgt_sub_chk select-checkbox" value="<?php echo esc_attr($retrieved_data->hall_id); ?>"></td>
												<td class="user_image width_50px profile_image_prescription padding_left_0">
													<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->hall_id;?>" type="examhall_view" >
														<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Exam_hall.png"?>" alt="" class="massage_image center">
														</p>
													</a>
												</td>
												<td>
													<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->hall_id;?>" type="examhall_view" >
														<?php echo stripslashes($retrieved_data->hall_name);?>
													</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Hall Name','school-mgt');?>"></i>
												</td>

												<td><?php echo $retrieved_data->number_of_hall;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Hall Numeric Value','school-mgt');?>"></i></td>
												<td><?php echo $retrieved_data->hall_capacity;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Hall Capacity','school-mgt');?>"></i></td>
												<?php
												$Description = $retrieved_data->description;
												$description_msg = strlen($Description) > 30 ? substr($Description,0,30)."..." : $Description;
												?>
												<td><?php if($retrieved_data->description){ echo stripslashes($description_msg); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($Description)){ echo $Description;}else{ esc_html_e('Description','school-mgt');}?>"></i></td>          
												<td class="action">  
													<div class="smgt-user-dropdown">
														<ul class="" style="margin-bottom: 0px !important;">
															<?php
															if(!empty($retrieved_data->exam_syllabus))
															{
																$doc_data=json_decode($retrieved_data->exam_syllabus);
															}
															?>
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																</a>
																<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																	<li class="float_left_width_100 ">
																		<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->hall_id;?>" type="examhall_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View Exam Hall','school-mgt');?></a>
																	</li>
																	<?php
																	if($user_access_edit == '1')
																	{
																		?>
																		<li class="float_left_width_100 border_bottom_menu">
																			<a href="?page=smgt_hall&tab=addhall&action=edit&hall_id=<?php echo $retrieved_data->hall_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																		</li>
																		<?php 
																	} 
																	if($user_access_delete =='1')
																	{
																		?>
																		<li class="float_left_width_100 ">
																			<a href="?page=smgt_hall&tab=hall_list&action=delete&hall_id=<?php echo $retrieved_data->hall_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
										<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->hall_id); ?>" style="margin-top: 0px;">
										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
									</button>
									<?php if($user_access_delete =='1')
									{ 
										?>
										<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
										<?php
									}
									?>
								</div>
							</form>
						</div>
						<?php 
					}
					else
					{
						if($user_access_add=='1')
						{
							?>
							<div class="no_data_list_div no_data_img_mt_30px"> 
								<a href="<?php echo admin_url().'admin.php?page=smgt_hall&tab=addhall';?>">
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
				if($active_tab == 'addhall')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/hall/add-hall.php';
					
				}
				if($active_tab == 'exam_hall_receipt')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/hall/exam_hall_receipt.php';
					
				}
				?>
	 		</div>
		</div><!-------- Penal White -------->
	</div>
</div><!-------- Page Inner -------->
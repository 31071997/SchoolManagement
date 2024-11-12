<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$role_name=mj_smgt_get_user_role(get_current_user_id());
//table name without prefix
$tablename="hall";
//--------------- ACCESS WISE ROLE -----------//
$user_access=mj_smgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		mj_smgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
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
		foreach($student_data_asigned as $student_id)
		{
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
		wp_redirect ( home_url() . '?dashboard=user&page=exam_hall&tab=exam_hall_receipt&message=4');
	}
}
	// This is Class at admin side!!!!!!!!! 
//----------------- DELETE HALL --------------------//
$tablename="hall";
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result=mj_smgt_delete_hall($tablename,$_REQUEST['hall_id']);
	if($result)
	{
	
		wp_redirect ( home_url() . '?dashboard=user&page=exam_hall&tab=hall_list&message=3');
	}
}
//--------------- MULTIPLE HALL DELETE ----------------//
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$result=mj_smgt_delete_hall($tablename,$id);
		}
	}		
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=exam_hall&tab=hall_list&message=3');
	}
}
//------------- insert and update----------------//
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
				wp_redirect ( home_url() . '?dashboard=user&page=exam_hall&tab=hall_list&message=2');	
			}
		}
		else
		{
			$result=mj_smgt_insert_record($tablename,$hall_data);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=exam_hall&tab=hall_list&message=1');
			}
		}
	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'hall_list';
?>
<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		"use strict";	
		$('#hall_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#receipt_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});		
		
				jQuery('.exam_timelist').DataTable({
						bPaginate: false,
						bFilter: false, 
						bInfo: false,
					}); 

					jQuery('.exam_timelist').DataTable({
						bPaginate: false,
						bFilter: false, 
						bInfo: false,
					});				
				
				
					jQuery('.exam_hall_table').DataTable({
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

			var table =  jQuery('#hall_list_frontend').DataTable({
				"initComplete": function(settings, json) {
					$(".print-button").css({"margin-top": "-5%"});
				},
				
			"order": [[ 2, "asc" ]],
			"dom": 'lifrtp',
			"aoColumns":[	
					<?php
					if($role_name == "supportstaff")
					{
						?>
						{"bSortable": false},
						<?php
					}
					?>                  	                 
					{"bSortable": false},
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
		jQuery('#checkbox-select-all').on('click', function(){
			var rows = table.rows({ 'search': 'applied' }).nodes();
			jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
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
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="view_popup"></div>     
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<div class="panel-body panel-white frontend_list_margin_30px_res"><!----------- PENAL BODY ----------->
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
		<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
			</button>
			<?php echo $message_string;?>
		</div>
		<?php 
	} 
	?>
	<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
		<li class="<?php if($active_tab=='hall_list'){?>active<?php }?>">			
			<a href="?dashboard=user&page=exam_hall&tab=hall_list" class="padding_left_0 tab <?php echo $active_tab == 'hall_list' ? 'active' : ''; ?>">
			<?php esc_html_e('Exam Hall List', 'school-mgt'); ?></a> 
		</li>
		<?php
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			?>
			<li class="<?php if($active_tab=='addhall'){?>active<?php }?>">			
				<a href="?dashboard=user&page=exam_hall&tab=addhall&action=edit&hall_id=<?php echo $_REQUEST['hall_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addhall' ? 'active' : ''; ?>">
				<?php esc_html_e('Edit Exam Hall', 'school-mgt'); ?></a> 
			</li>
			<?php
		}
		else
		{
			if($active_tab=='addhall')
			{
				?>
				<li class="<?php if($active_tab=='addhall'){?>active<?php }?>">			
					<a href="?dashboard=user&page=exam_hall&tab=addhall" class="padding_left_0 tab <?php echo $active_tab == 'addhall' ? 'active' : ''; ?>">
					<?php esc_html_e('Add Exam Hall', 'school-mgt'); ?></a> 
				</li>
				<?php
			}
		}
		?>
		<li class="<?php if($active_tab=='exam_hall_receipt'){?>active<?php }?>">
			<a href="?dashboard=user&page=exam_hall&tab=exam_hall_receipt" class="padding_left_0 tab <?php echo $active_tab == 'exam_hall_receipt' ? 'active' : ''; ?>">
			<?php esc_html_e('Exam Hall Receipt', 'school-mgt'); ?></a> 
		</li>  
	</ul> 
	<?php 
	//-------------- EXAM HALL LIST TAB -------------//
	if($active_tab=='hall_list')
	{
		$user_id=get_current_user_id();
		if($school_obj->role == 'supportstaff' OR $school_obj->role == 'teacher' )
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$retrieve_class = mj_smgt_get_all_examhall_by_user_id($tablename);
			}
			else
			{
				$retrieve_class = mj_smgt_get_all_data($tablename);
			}
		}
		else
		{
			$retrieve_class = mj_smgt_get_all_data($tablename);
		} 
		if(!empty($retrieve_class))
		{ 
			?>
			<div class="panel-body"><!--------------- PENAL BODY -------------->
				<div class="table-responsive"><!--------------- TABLE RESPONSIVE -------------->
					<!---------------- EXAM HALL LIST FORM ---------------->
					<form id="frm-example" name="frm-example" method="post">
						<table id="hall_list_frontend" class="display dataTable" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
								<tr>
									<?php
									if($role_name == "supportstaff")
									{
										?>
										<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
										<?php
									}
									?>
									<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
									<th><?php esc_attr_e('Exam Hall','school-mgt');?></th>
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
										<?php
										if($role_name == "supportstaff")
										{
											?>
											<td class="checkbox_width_10px"><input type="checkbox" name="id[]" class="smgt_sub_chk select-checkbox" value="<?php echo esc_attr($retrieved_data->hall_id); ?>"></td>
											<?php
										}
										?>
										<td class="user_image width_50px profile_image_prescription padding_left_0">
											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->hall_id;?>" type="examhall_view" >
												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Exam_hall.png"?>" alt="" class="massage_image center">
												</p>
											</a>
										</td>
										<td class="width_25px">
											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->hall_id;?>" type="examhall_view" ><?php echo stripslashes($retrieved_data->hall_name);?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Hall','school-mgt');?>"></i>
										</td>

										<td class="width_10px"><?php echo $retrieved_data->number_of_hall;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Hall Numeric Value','school-mgt');?>"></i></td>
										<td class="width_10px"><?php echo $retrieved_data->hall_capacity;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Hall Capacity','school-mgt');?>"></i></td>
										<?php
										$Description = $retrieved_data->description;
										$description_msg = strlen($Description) > 50 ? substr($Description,0,50)."..." : $Description;
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
																<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->hall_id;?>" type="examhall_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View exam hall','school-mgt');?></a>
															</li>
															<?php
															if($user_access['edit'] =='1')
															{
																?>
																<li class="float_left_width_100 border_bottom_menu">
																	<a href="?dashboard=user&page=exam_hall&tab=addhall&action=edit&hall_id=<?php echo $retrieved_data->hall_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																</li>
																<?php 
															} 
															if($user_access['delete'] =='1')
															{
																?>
																<li class="float_left_width_100 ">
																	<a href="?dashboard=user&page=exam_hall&tab=hall_list&action=delete&hall_id=<?php echo $retrieved_data->hall_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
						<?php
						if($role_name == "supportstaff")
						{
							?>
							<div class="print-button pull-left">
								<button class="btn-sms-color button_reload">
									<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">
									<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
								</button>
								<?php 
								if($user_access['delete'] =='1')
								{ 
									?>
									<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
									<?php
								}
								?>
							</div>
							<?php
						}
						?>
					</form><!---------------- EXAM HALL LIST FORM ---------------->
				</div><!--------------- TABLE RESPONSIVE -------------->
			</div><!--------------- PENAL BODY -------------->
			<?php
		}
		else
		{
			if($user_access['add']=='1')
			{
				?>
				<div class="no_data_list_div no_data_img_mt_30px"> 
					<a href="<?php echo home_url().'?dashboard=user&page=exam_hall&tab=addhall';?>">
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
	//---------------- ADD EXAM HALL TAB ---------------//
	if($active_tab=='addhall')
	{ 
      	$edit=0;
	    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$hall_data= mj_smgt_get_hall_by_id($_REQUEST['hall_id']);
		}
		?>
       
		<div class="panel-body margin_top_20px padding_top_15px_res">
        	<form name="hall_form" action="" method="post" class="form-horizontal " id="hall_form">
          		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<div class="form-body user_form"><!-------- Form Body -------->
					<div class="row"><!-------- Row Div -------->
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="hall_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hall_data->hall_name;}?>" name="hall_name">
									<label for="userinput1" class=""><?php esc_html_e('Hall Name','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="number_of_hall" class="form-control validate[required,custom[onlyNumberSp]]" maxlength="5" type="text" value="<?php if($edit){ echo $hall_data->number_of_hall;}?>" name="number_of_hall">				
									<label for="userinput1" class=""><?php esc_html_e('Hall Numeric Value','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<?php wp_nonce_field( 'save_hall_admin_nonce' ); ?>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="hall_capacity" class="form-control validate[required,custom[onlyNumberSp]]" maxlength="5" type="text" value="<?php if($edit){ echo $hall_data->hall_capacity;}?>" name="hall_capacity">				
									<label for="userinput1" class=""><?php esc_html_e('Hall Capacity','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 note_text_notice">
							<div class="form-group input">
								<div class="col-md-12 note_border margin_bottom_15px_res">
									<div class="form-field">
										<textarea name="description" id="description" maxlength="150" class="textarea_height_47px form-control validate[custom[address_description_validation]]"><?php if($edit){ echo $hall_data->description;}?></textarea>
										<span class="txt-title-label"></span>
										<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
									</div>
								</div>
							</div>
						</div>
					</div><!-------- Row Div -------->
				</div><!-------- Form Body -------->
				<div class="form-body user_form">
					<div class="row">
						<div class="col-sm-6">        	
							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Hall','school-mgt'); }else{ esc_attr_e('Add Hall','school-mgt');}?>" name="save_hall" class="btn btn-success save_btn" />
						</div>
					</div>
				</div>
        	</form>
        </div>
		<?php 
	}
	if($active_tab=='exam_hall_receipt')
	{ 
		?>
		<div class="panel-body margin_top_20px padding_top_25px_res"><!-------- Penal Body -------->
			<form name="receipt_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="receipt_form">
				<div class="form-body user_form"><!-------- Form Body -------->
					<div class="row">
						<div class="col-md-9 input">
							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
							<?php
							$page = 'exam';
							$user_access = mj_smgt_get_userrole_wise_access_right_page_wise_array_for_dashboard($page);
							$own_data=$user_access['own_data'];
							$obj_exam=new smgt_exam;
							$user_id=get_current_user_id();
							if($own_data == '1')
							{		
								if($school_obj->role == 'teacher')
								{
									
									$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);	

									$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_by_class_id_created_by($class_id,$user_id);
								}
								else{
									
									$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_created_by($user_id);
								}
							}
							else{
								$tablename="exam"; 
								$retrieve_class = mj_smgt_get_all_data($tablename);
							}
							$exam_id="";
							if(isset($_REQUEST['exam_id']))
							{
								$exam_id=$_REQUEST['exam_id']; 
							}
							?>
							<select name="exam_id" class="line_height_30px form-control validate[required] exam_hall_receipt" id="exam_id">
								<option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>
								<?php
								foreach($retrieve_class as $retrieved_data)
								{
									$cid=$retrieved_data->class_id;
									$clasname=mj_smgt_get_class_name($cid);
									if($retrieved_data->section_id!=0)
									{
										$section_name=mj_smgt_get_section_name($retrieved_data->section_id); 
									}
									else
									{
										$section_name=esc_attr__('No Section', 'school-mgt');
									}
								?>
									<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name.'('.smgt_get_class_section_name_wise($cid,$retrieved_data->section_id).')';?></option>
								<?php	
								}
								?>
							</select>                  
						</div>
						<div class="form-group col-md-3">
							<input type="button" value="<?php esc_attr_e('Search Exam','school-mgt');?>" name="search_exam" id="search_exam" class="btn btn-info search_exam save_btn"/>
						</div>
					</div>
				</div><!-------- Form Body -------->
			</form>
			
			<div class="col-md-12 col-sm-12 col-xs-12 rtl_padding_0px">
				<div class="exam_hall_receipt_div"></div>
			</div>
		</div> <!-------- Penal Body -------->
		<?php
	}
	?>
</div><!----------- PENAL BODY ----------->
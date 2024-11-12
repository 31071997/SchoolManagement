<?php
$role_name=mj_smgt_get_user_role(get_current_user_id());
?>
<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		"use strict";	
		$('#grade_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		var table =  jQuery('#grade_list').DataTable({
			"initComplete": function(settings, json) {
				$(".print-button").css({"margin-top": "-5%"});
			},
			//stateSave: true,
			"order": [[ 2, "desc" ]],
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
					<?php
					if($user_access['edit'] =='1' || $user_access['delete'] =='1')
					{
						?>
						{"bSortable": true},
						<?php
					}
					?>	                  
					{"bSortable": false}
				],
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
		jQuery('#delete_selected').on('click', function()
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
					jQuery('#frm-example').submit();
				}
			}
		
		});
		jQuery('#checkbox-select-all').on('click', function(){
			var rows = table.rows({ 'search': 'applied' }).nodes();
			jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
		}); 
	});
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'gradelist';
$tablename="grade";
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
if(isset($_REQUEST['message']))
{
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Grade Added successfully','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Grade Updated Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Grade Deleted Successfully.','school-mgt');
			break;
	}
	if($message)
	{ 
		?>
		<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
			</button>
			<p><?php echo $message_string;?></p>
		</div>
	  	<?php 
	} 
}
//--------------- SAVE GRADE FORM --------------//
if(isset($_POST['save_grade']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_grade_admin_nonce' ) )
	{
		$created_date = date("Y-m-d H:i:s");
		
		$mark_from=$_POST['mark_from'];
		$mark_upto=$_POST['mark_upto'];
		if($mark_upto < $mark_from)
		{
			$gradedata=array('grade_name'=>mj_smgt_address_description_validation(stripslashes($_POST['grade_name'])),
						'grade_point'=>mj_smgt_onlyNumberSp_validation($_POST['grade_point']),
						'mark_from'=>mj_smgt_onlyNumberSp_validation($_POST['mark_from']),
						'mark_upto'=>mj_smgt_onlyNumberSp_validation($_POST['mark_upto']),
						'grade_comment'=>mj_smgt_address_description_validation(stripslashes($_POST['grade_comment'])),	
						'creater_id'=>get_current_user_id(),
						'created_date'=>$created_date
						
			);
			//table name without prefix
			if($_REQUEST['action']=='edit')
			{
				$grade_id=array('grade_id'=>$_REQUEST['grade_id']);
				$result=mj_smgt_update_record($tablename,$gradedata,$grade_id);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=grade&tab=gradelist&message=2');
				}
			}
			else
			{
				$grade_name=mj_smgt_get_grade_by_name($_POST['grade_name']);
				if(empty($grade_name))
				{
						$result=mj_smgt_insert_record($tablename,$gradedata);
						if($result)
						{
						wp_redirect ( home_url() . '?dashboard=user&page=grade&tab=gradelist&message=1');
						}
				}
				else
				{
					?>
					<div id="message" class="alert updated_top below-h2 notice is-dismissible alert-dismissible" style="box-shadow: 0px 0px 1px 1px rgb(0 0 0 / 10%) !important;margin-left:0px !important;">
						<p><?php esc_html_e('Grade Name All Ready Exist.','school-mgt');?></p>
						<button type="button" class="btn-default notice-dismiss" style="margin-top: -30px !important;" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span></button>
					</div>
					<?php
				}
					
			}
		}
		else
		{ 
			?>
			<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
				<p><?php echo esc_html_e('You can not add a Mark upto higher than the Mark from.','school-mgt');?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span></button>
			</div>
			<?php
		}
	}
}
//-------------- MULTIPLE GRADE DELETE ---------------//
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$result=mj_smgt_delete_grade($tablename,$id);
		}
	}	
	if($result)
	{	
		wp_redirect ( home_url() . '?dashboard=user&page=grade&tab=gradelist&message=3');
	}
}
//--------------- GRADE DELETE ACTION ---------------//
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result=mj_smgt_delete_grade($tablename,$_REQUEST['grade_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=grade&tab=gradelist&message=3');
	}
}
?>
<div class="panel-body panel-white frontend_list_margin_30px_res"><!------------- PENAL BODY ---------------->
	<?php
	//---------------- GRADE LIST TAB ----------------//
	if($active_tab == 'gradelist')
	{
		$tablename="grade";
		$own_data=$user_access['own_data'];
		if($own_data == '1')
		{ 
			$grade_data = mj_smgt_get_all_grade_data_by_user_id($tablename);
			
		}
		else
		{
			$grade_data = mj_smgt_get_all_data($tablename);
		}
		if(!empty($grade_data))
		{
			?>
			<div class="panel-body"><!------------ PENAL BODY ---------------->
				<div class="table-responsive"><!--------------- TABLE RESPONSIVE --------------->
					<!---------------- GRADE LIST PAGE FORM ------------->
					<form id="frm-example" name="frm-example" method="post">
						<table id="grade_list" class="display dataTable student_datatable" cellspacing="0" width="100%">
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
									<th><?php esc_attr_e('Grade Name','school-mgt');?></th>
									<th><?php esc_attr_e('Grade Point','school-mgt');?></th>
									<th><?php esc_attr_e('Mark From/Upto','school-mgt');?></th>
									<!-- <th><?php esc_attr_e('Mark Upto','school-mgt');?></th> -->
									<th><?php esc_attr_e('Comment','school-mgt');?></th>	
									<?php
									if($user_access['edit'] =='1' || $user_access['delete'] =='1')
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
								$i=0;
								foreach ($grade_data as $retrieved_data)
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
											<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->grade_id;?>"></td>
											<?php
										}
										?>
										<td class="user_image width_50px profile_image_prescription padding_left_0">
											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Grade.png"?>" alt="" class="massage_image center">
											</p>
										</td>
										<td><?php echo $retrieved_data->grade_name;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Grade Name','school-mgt');?>"></i></td>
										<td><?php echo $retrieved_data->grade_point;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Grade point','school-mgt');?>"></i></td>
										<td><?php echo $retrieved_data->mark_upto.' '.esc_html__('To','school-mgt').' '.$retrieved_data->mark_from;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mark From/Upto','school-mgt');?>"></i></td>
										<!-- <td><?php echo $retrieved_data->mark_upto;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mark Upto','school-mgt');?>"></i></td> -->
										<?php
										$comment =$retrieved_data->grade_comment;
										$grade_comment = strlen($comment) > 60 ? substr($comment,0,60)."..." : $comment;
										?>
										<td><?php if(!empty($grade_comment)){ echo $grade_comment; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($comment)){ echo $comment;}else{ esc_html_e('Comment','school-mgt');}?>"></i></td>
										<?php
										if($user_access['edit'] =='1' || $user_access['delete'] =='1')
										{
											?>
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
																<?php
																if($user_access['edit'] =='1')
																{
																	?>
																	<li class="float_left_width_100 border_bottom_menu">
																		<a href="?dashboard=user&page=grade&tab=addgrade&action=edit&grade_id=<?php echo $retrieved_data->grade_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																	</li>
																	<?php 
																} 
																if($user_access['delete'] =='1')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=grade&tab=gradelist&action=delete&grade_id=<?php echo $retrieved_data->grade_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
											<?php
										}
										?>
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
					</form><!---------------- GRADE LIST PAGE FORM ------------->
				</div><!--------------- TABLE RESPONSIVE --------------->
			</div><!------------ PENAL BODY ---------------->
			<?php
		}
		else
		{
			if($user_access['add']=='1')
			{
				?>
				<div class="no_data_list_div no_data_img_mt_30px"> 
					<a href="<?php echo home_url().'?dashboard=user&page=grade&tab=addgrade';?>">
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
	//------------------ GRADE ADD ----------------// 
	if($active_tab == 'addgrade')
	{
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			
			$edit=1;
			$grade_data= mj_smgt_get_grade_by_id($_REQUEST['grade_id']);
		}
		?>
		<div class="panel-body padding_top_25px_res "><!---------------- PENAL BODY ----------------->
			<!------------------- GRADE FORM START ----------------->	
        	<form name="grade_form" action="" method="post" class="form-horizontal" id="grade_form">
          		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert'; ?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Grade Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="grade_name" class="form-control validate[required,custom[address_description_validation]]" type="text" value="<?php if($edit){ echo $grade_data->grade_name;}?>" maxlength="50" name="grade_name">
									<label for="userinput1" class=""><?php esc_html_e('Grade Name','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="grade_point" class="form-control validate[required,max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->grade_point;}?>" name="grade_point" step="any">
									<label for="userinput1" class=""><?php esc_html_e('Grade Point','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<?php wp_nonce_field( 'save_grade_admin_nonce' ); ?>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mark_upto" class="form-control validate[required,max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->mark_upto;}?>" name="mark_upto" step="any">
									<label for="userinput1" class=""><?php esc_html_e('Mark From','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mark_from" class="form-control validate[required,max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->mark_from;}?>" name="mark_from" step="any">
									<label for="userinput1" class=""><?php esc_html_e('Mark Upto','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 note_text_notice">
							<div class="form-group input">
								<div class="col-md-12 note_border margin_bottom_15px_res">
									<div class="form-field">
										<textarea name="grade_comment" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150" id="grade_comment"><?php if($edit){ echo $grade_data->grade_comment;}?></textarea>
										<span class="txt-title-label"></span>
										<label class="text-area address active"><?php esc_attr_e('Comment','school-mgt');?></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-body user_form">
					<div class="row">
						<div class="col-sm-6">        	
							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Grade','school-mgt'); }else{ esc_attr_e('Add Grade','school-mgt');}?>" name="save_grade" class="btn btn-success save_btn" />
						</div>
					</div>
				</div>
        	</form><!------------------- GRADE FORM START ----------------->	
		</div><!---------------- PENAL BODY ----------------->
		<?php
	}
	?>
</div> 
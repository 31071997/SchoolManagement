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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('document');
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
			if ('document' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('document' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('document' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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

$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'documentlist');
$obj_document=new smgt_document;

if(isset($_POST['save_document']))//SAVE DOCUMENT	
{
	$nonce = sanitize_text_field($_POST['_wpnonce']);
	if (wp_verify_nonce($nonce, 'save_document_nonce'))
	{
		$upload_docs_array=array(); 
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			
			if(isset($_FILES['upload_file']) && !empty($_FILES['upload_file']) && $_FILES['upload_file']['size'] !=0)
			{		
				if($_FILES['upload_file']['size'] > 0)
					$upload_docs1=mj_smgt_load_documets_new($_FILES['upload_file'],$_FILES['upload_file'],$_POST['doc_title']);		
			}
			else
			{
				if(isset($_REQUEST['old_hidden_document']))
				$upload_docs1=$_REQUEST['old_hidden_document'];
			}
			$document_data=array();
			if(!empty($upload_docs1))
			{
				$document_data[]=array('title'=>$_POST['doc_title'],'value'=>$upload_docs1);
			}
			else
			{
				$document_data[]='';
			}
			
			$result=$obj_document->mj_smgt_add_document($_POST,$document_data);
			
			wp_redirect ( admin_url().'admin.php?page=smgt_document&tab=documentlist&message=2');
			
		}
		else
		{
			if(isset($_FILES['upload_file']) && !empty($_FILES['upload_file']) && $_FILES['upload_file']['size'] !=0)
			{		
				if($_FILES['upload_file']['size'] > 0)
					$upload_docs1=mj_smgt_load_documets_new($_FILES['upload_file'],$_FILES['upload_file'],$_POST['doc_title']);		
			}
			else
			{
				$upload_docs1='';
			}
				
			$document_data=array();
			if(!empty($upload_docs1))
			{
				$document_data[]=array('title'=>$_POST['doc_title'],'value'=>$upload_docs1);
			}
			else
			{
				$document_data[]='';
			}
			
			
			$result=$obj_document->mj_smgt_add_document($_POST,$document_data);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_document&tab=documentlist&message=1');
			} 
		}
	}
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE DOCUMENT
{
	$result=$obj_document->mj_smgt_delete_document($_REQUEST['document_id']);
	
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_document&tab=documentlist&message=3');
	}
}

if(isset($_REQUEST['delete_selected']))
{		
		if(!empty($_REQUEST['selected_id']))
		{
		foreach($_REQUEST['selected_id'] as $id)
		{
			$delete=$obj_document->mj_smgt_delete_document($id);
		}
		if($delete)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_document&tab=documentlist&message=3');
		}
	}
}

?>
<div class="page-inner"><!-- page-inner -->

	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
		<div class="row"><!-- row -->
			<div class="col-md-12 padding_0"><!-- col-md-12 -->
				<?php
				$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
				switch($message)
				{
					case '1':
						$message_string = esc_attr__('Document Inserted Successfully.','school-mgt');
						break;
					case '2':
						$message_string = esc_attr__('Document Updated Successfully.','school-mgt');
						break;	
					case '3':
						$message_string = esc_attr__('Document Deleted Successfully.','school-mgt');
						break;
				}
				if($message)
				{ ?>
					<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
						<p><?php echo $message_string;?></p>
						<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
					<?php 
				} 
				
				?>

				<div class="smgt_main_listpage"><!-- smgt_main_listpage -->
							
					<?php 
					//DOCUMENT LIST TAB
					if($active_tab == 'documentlist')
					{ 
						$documentdata=$obj_document->mj_smgt_get_all_documents();
						if(!empty($documentdata))
						{	
							?>  
							<script type="text/javascript">
								jQuery(document).ready(function($)
								{
									"use strict";	
									var table =  jQuery('#document_list').DataTable({
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
							<div class="panel-body">
								<div class="table-responsive">
									<form id="frm-example" name="frm-example" method="post">	
										<table id="document_list" class="display admin_transport_datatable" cellspacing="0" width="100%">
											<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
												<tr>
													<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
													<th><?php echo esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
													<th><?php echo esc_attr_e( 'Title', 'school-mgt' ) ;?></th>
													<th><?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?></th>
													<th><?php echo esc_attr_e( 'Class Section', 'school-mgt' ) ;?></th>
													<th><?php echo esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>			
													<th><?php echo esc_attr_e( 'Date', 'school-mgt' ) ;?></th>			
													<th><?php echo esc_attr_e( 'Description', 'school-mgt' ) ;?></th>
													<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$i=0;
												foreach ($documentdata as $retrieved_data)
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
														<td class="checkbox_width_10px">
															<input type="checkbox" name="selected_id[]" class="smgt_sub_chk select-checkbox" value="<?php echo esc_attr($retrieved_data->document_id); ?>">
														</td>	

														<td class="user_image width_50px profile_image_prescription">	
															<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>" alt="" class="massage_image center image_icon_height_25px margin_top_3px">
															</p>
														</td>
														<td class="title">
																<?php 
																$doc_data=json_decode($retrieved_data->document_content);
																	echo esc_html($doc_data[0]->title);
																?>
															<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Document Title','school-mgt');?>" ></i>
														</td>
														<td><?php if($retrieved_data->class_id=="all class"){echo esc_attr_e('All Class','school-mgt');}else{echo mj_smgt_get_class_name($retrieved_data->class_id);} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class','school-mgt');?>"></i></td> 
														<td><?php if($retrieved_data->section_id=="all section"){echo esc_attr_e('All Section','school-mgt');}else{echo mj_smgt_get_section_name($retrieved_data->section_id);} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Section','school-mgt');?>"></i></td>
														<td><?php if($retrieved_data->student_id=="all student"){ echo esc_attr_e('All Student','school-mgt'); }elseif($retrieved_data->student_id==""){ echo "N/A"; }else{ echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id); } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td> 
														<td><?php if(!empty($retrieved_data->created_date)){echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);}else{echo "N/A";} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>
														<td><?php
														$description =$retrieved_data->description;
														$grade_comment = strlen($description) > 30 ? substr($description,0,30)."..." : $description;
														if(!empty($retrieved_data->description)){echo $grade_comment;}else{echo "N/A";} 
														?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($description)){echo $description;}else{esc_html_e('Description','school-mgt');}?>"></i></td>
														<td class="action">  
															<div class="smgt-user-dropdown">
																<ul class="" style="margin-bottom: 0px !important;">
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																		</a>
																		<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																			<?php 
																			$doc_data=json_decode($retrieved_data->document_content);
																			
																			
																			if(!empty($doc_data[0]->value))
																			{
																				?>
																				<li class="float_left_width_100">
																					<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="float_left_width_100" record_id="<?php echo $retrieved_data->document_id;?>"><i class="fa fa-eye"> </i><?php esc_html_e('View Document', 'school-mgt');?></a>
																				</li>
																				<?php
																			}

																			if($user_access_edit == '1')
																			{
																				?>
																				<li class="float_left_width_100 border_bottom_menu">
																					<a href="admin.php?page=smgt_document&tab=add_document&action=edit&document_id=<?php echo $retrieved_data->document_id?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																				</li>
																				<?php 
																			} 
																			if($user_access_delete =='1')
																			{
																				?>
																				<li class="float_left_width_100 ">
																					<a href="admin.php?page=smgt_document&tab=documentlist&action=delete&document_id=<?php echo $retrieved_data->document_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
												<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->document_id); ?>" style="margin-top: 0px;">
												<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
											</button>
											<?php 
											if($user_access_delete =='1')
											{ 
												?>
												<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
												<?php
											}
											?>
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
									<a href="<?php echo admin_url().'admin.php?page=smgt_document&tab=add_document';?>">
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
					if($active_tab == 'add_document')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/ducuments/add-document.php';

					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
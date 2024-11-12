<div class="mailbox-content padding_0"><!--mailbox-content  -->	
	<?php
	$max = 10;
	if(isset($_GET['pg']))
	{
		$p = $_GET['pg'];
	}
	else
	{
		$p = 1;
	}
	$limit = ($p - 1) * $max;

	$post_id=0;

	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		{
		
			foreach($_REQUEST['id'] as $id)
			{
				$result=mj_smgt_delete_inbox_message($id);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=inbox&message=2');
				}
			}
		}
	}
	$message = mj_smgt_get_inbox_message(get_current_user_id(),$limit,$max);
	
	if(!empty($message))
	{
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				"use strict";	
				var table =  jQuery('#inbox_list').DataTable({
					"initComplete": function(settings, json) {
							$(".print-button").css({"margin-top": "-5%"});
						},
						//stateSave: true,
					responsive: true,
					"dom": 'lifrtp',
					"order": [[ 1, "asc" ]],
					"sSearch": "<i class='fa fa-search'></i>",
					"aoColumns":[	
						{"bSortable": false},	                  
						{"bSortable": false},	                 
						{"bSortable": true},
						{"bSortable": true},
						{"bSortable": true},
						{"bSortable": true},
						{"bSortable": true},	                  
						{"bSortable": true}],
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
		<form name="wcwm_report" action="" method="post"><!-- form-div -->
			<div class="table-responsive" id="sentbox_table"><!-- table-responsive  -->	
				<table id="inbox_list" class="table"><!--inbox-list table -->	
					<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
						<tr>
							<th class="padding_0" style="padding: 15px 0px !important;"><input type="checkbox" class="select_all" id="select_all"></th>
							<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
							<th><?php esc_attr_e('Message From','school-mgt');?></th>
							<th><?php esc_attr_e('Message For','school-mgt');?></th>
							<th><?php esc_attr_e('Subject','school-mgt');?></th>
							<th><?php esc_attr_e('Description','school-mgt');?></th>
							<th><?php esc_attr_e('Attachment','school-mgt');?></th>
							<th><?php _e( 'Date & Time', 'school-mgt' ) ;?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(!empty($message))
						{
							$i=0;	
							foreach($message as $msg)
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

								$message_for=get_post_meta($msg->post_id,'message_for',true);
								$attchment=get_post_meta( $msg->post_id, 'message_attachment',true);
								if($message_for=='student' || $message_for=='supportstaff' || $message_for=='teacher' || $message_for=='parent')
								{	
									if($post_id == $msg->post_id)
									{
										continue;
									}
									else
									{ ?>
										<tr>
										<td class="checkbox_width_10px">
											<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $msg->message_id;?>">
										</td>
											<td class="user_image width_50px profile_image_prescription padding_left_0">
												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/inbox_icon.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
												</p>
											</td>
											<td>
												<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"  class="text_decoration_none">
													<?php 
													$auth = get_post($msg->post_id);
													$authid = $auth->post_author;
													$author = mj_smgt_get_display_name($authid);
													echo esc_html__($author,'school-mgt');
													?>
												</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Message From','school-mgt');?>" ></i>			
											</td>		
											<td>
												<?php 
												$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($msg->post_id);	
												if($check_message_single_or_multiple == 1)
												{	
													global $wpdb;
													$tbl_name = $wpdb->prefix .'smgt_message';
													$post_id=$msg->post_id;
													$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
													$role = mj_smgt_get_display_name($get_single_user->receiver);
													echo esc_html__($role,'school-mgt');
												}
												else
												{	
													$role = get_post_meta( $msg->post_id, 'message_for',true);			
													echo esc_html__($role,'school-mgt');
												}
												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Message For','school-mgt');?>" ></i>		
											</td>
											<td class="">
												<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="smgt_inbox_tab text_decoration_none"> 
													<?php 
													$subject_char=strlen($msg->subject);
													if($subject_char <= 10)
													{
														echo $msg->subject;
													}
													else
													{
														$char_limit = 10;
														$subject_body= substr(strip_tags($msg->subject), 0, $char_limit)."...";
														echo $subject_body;
													}
													?>
													<?php 
													if(mj_smgt_count_reply_item($msg->post_id)>=1)
													{ ?>
														<span class="smgt_inbox_count_number badge badge-success  pull-right ms-1"><?php echo mj_smgt_count_reply_item($msg->post_id);?></span>
														<?php 
													} ?>
												</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($msg->subject)){ echo $msg->subject;}else{_e('Subject','school-mgt');} ?>" ></i>		
											</td>
											<td class="">
													<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"  class="text_decoration_none">
													<?php
													$body_char=strlen($msg->message_body);
													if($body_char <= 30)
													{
														echo $msg->message_body;
													}
													else
													{
														$char_limit = 30;
														$msg_body= substr(strip_tags($msg->message_body), 0, $char_limit)."...";
														echo $msg_body;
													}
													?>
												</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($msg->message_body)){ echo $msg->message_body;}else{ _e('Description','school-mgt');} ?>" ></i>	
											</td>
											<td>	
												<?php			
												if(!empty($attchment))
												{	
													$attchment_array=explode(',',$attchment);
													foreach($attchment_array as $attchment_data)
													{
														?>
														<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data; ?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_attr_e('View Attachment','school-mgt');?></a>
														<?php				
													}
												}
												else
												{
													esc_attr_e('No Attachment','school-mgt');
												}
												?>			
											</td>
											<td>
												<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"  class="text_decoration_none">
													<?php  echo  mj_smgt_convert_date_time($msg->date); ?>
												</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date & Time','school-mgt');?>" ></i>
											</td>
										</tr>
										<?php 
									}			
								}
								else
								{	
									?>
									<tr>
										<td class="checkbox_width_10px">
											<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $msg->message_id;?>">
										</td>
										<td class="user_image width_50px profile_image_prescription padding_left_0">
											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/inbox_icon.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
											</p>
										</td>	
										<td>
											<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"  class="text_decoration_none"> 
												<?php 
												$auth = get_post($msg->post_id);
												$authid = $auth->post_author;
												$author = mj_smgt_get_display_name($authid);
													echo esc_html__($author,'school-mgt');
												?>
											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Message From','school-mgt');?>" ></i>		
										</td>		
										<td>
											<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"  class="text_decoration_none"> 
												<?php 
												$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($msg->post_id);	
												if($check_message_single_or_multiple == 1)
												{	
													global $wpdb;
													$tbl_name = $wpdb->prefix .'smgt_message';
													$post_id=$msg->post_id;
													$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
													
													$role = mj_smgt_get_display_name($get_single_user->receiver);
													echo esc_html__($role,'school-mgt');
												}
												else
												{					
													$role = get_post_meta( $msg->post_id, 'message_for',true);			
													echo esc_html__($role,'school-mgt');
												}
												?>
											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Message For','school-mgt');?>" ></i>		
										</td>
										<td class="width_100px">
											<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"  class="text_decoration_none"> 
												<?php
												$subject_char=strlen($msg->subject);
												if($subject_char <= 10)
												{
													echo $msg->subject;
												}
												else
												{
													$char_limit = 10;
													$subject_body= substr(strip_tags($msg->subject), 0, $char_limit)."...";
													echo $subject_body;
												}
												?>
												<?php 
												if(mj_smgt_count_reply_item($msg->post_id)>=1)
												{ ?>
													<span class="badge badge-success pull-right"><?php echo mj_smgt_count_reply_item($msg->post_id);?></span>
													<?php 
												} ?>
											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($msg->subject)){ echo $msg->subject;}else{_e('Subject','school-mgt');} ?>" ></i>
										</td>
										<td>
											<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"  class="text_decoration_none">
												<?php
												$body_char=strlen($msg->message_body);
												if($body_char <= 30)
												{
													echo $msg->message_body;
												}
												else
												{
													$char_limit = 30;
													$msg_body= substr(strip_tags($msg->message_body), 0, $char_limit)."...";
													echo $msg_body;
												}
												?>
											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($msg->message_body)){ echo $msg->message_body;}else{ _e('Description','school-mgt');} ?>" ></i>
										</td>
										<td>	
											<?php			
											if(!empty($attchment))
											{	
												$attchment_array=explode(',',$attchment);
												foreach($attchment_array as $attchment_data)
												{
													?>
													<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data; ?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_attr_e('View Attachment','school-mgt');?></a>
													<?php				
												}
											}
											else
											{
												esc_attr_e('No Attachment','school-mgt');
											}
											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attachment','school-mgt');?>" ></i>					
										</td> 
										<td>
											<a href="?page=smgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"  class="text_decoration_none">	
												<?php  echo  mj_smgt_convert_date_time($msg->date); ?>
											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date & Time','school-mgt');?>" ></i>
										</td>
									</tr>
									<?php 
								}
								$post_id=$msg->post_id;
								$i++;
							}
						}
						?>		
					</tbody>
				</table><!--inbox-list table -->
				<div class="print-button pull-left">
					<button class="btn btn-success btn-sms-color button_reload" type="button">
						<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
						<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
					</button>
					<?php 
					if($user_access_delete =='1')
					{ ?>
						<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
						<?php 
					} ?>
				</div>
			</div><!-- table-responsive  -->	
		</form><!-- form-div -->
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
	?>
 </div><!--mailbox-content  -->	
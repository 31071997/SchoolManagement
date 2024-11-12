<?php ?>
<?php 				
$message = mj_smgt_count_inbox_item(get_current_user_id());				
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
$prev = $p - 1;
$next = $p + 1;
$limits = (int)($p - 1) * $max;
$totlal_message =count($message);
$totlal_message = ceil($totlal_message / $max);
$lpm1 = $totlal_message - 1;
$offest_value = ($p-1) * $max;
echo mj_smgt_inbox_pagination($totlal_message,$p,$lpm1,$prev,$next);

$message = mj_smgt_get_inbox_message(get_current_user_id(),$limit,$max);
?>
<div class="mailbox-content">
	<?php
	if(!empty($message))
	{
		?>
		<div class="table-responsive" id="sentbox_table">
			<script type="text/javascript">
				jQuery(document).ready(function($)
				{
					"use strict";	
					var table =  jQuery('#inbox_list').DataTable({
						//stateSave: true,
						"dom": 'lifrtp',
						"order": [[ 1, "asc" ]],
						"sSearch": "<i class='fa fa-search'></i>",
						"aoColumns":[		                  
							{"bSortable": false},	                 
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},	                  
							{"bSortable": false}],
						language:<?php echo mj_smgt_datatable_multi_language();?>
					});
					$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
				});
				</script>
			<form name="wcwm_report" action="" method="post"><!-- form-div -->
				<table class="table" id="inbox_list">
					<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
						<tr>
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
						$message = mj_smgt_get_inbox_message(get_current_user_id(),$limit,$max);
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
									$post_id='';
									if($post_id==$msg->post_id)
									{
										continue;
									}
									else
									{ 				
									?>
									<tr>
										<td class="user_image width_50px profile_image_prescription padding_left_0">
											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/inbox_icon.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
											</p>
										</td>	
										<td>
											<a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none"><?php 
											$auth = get_post($msg->post_id);
											$authid = $auth->post_author;
											echo esc_html_e(mj_smgt_get_display_name($authid),'school-mgt');
											?>
											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Message From','school-mgt');?>" ></i>
										</td>	
										<td>
											<a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">
											<?php 
											$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($msg->post_id);	
											if($check_message_single_or_multiple == 1)
											{	
												global $wpdb;
												$tbl_name = $wpdb->prefix .'smgt_message';
												$post_id=$msg->post_id;
												$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
												
												echo esc_html_e(mj_smgt_get_display_name($get_single_user->receiver),'school-mgt');
											}
											else
											{					
												echo esc_html_e(get_post_meta( $msg->post_id, 'message_for',true),'school-mgt');
											}
											?>
											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Message For','school-mgt');?>" ></i>		
										</td>
										<td>
											<a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none smgt_inbox_tab">
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
											<?php if(mj_smgt_count_reply_item($msg->post_id)>=1){ ?><span class="smgt_inbox_count_number badge badge-success  pull-right ms-1"><?php echo mj_smgt_count_reply_item($msg->post_id);?></span><?php } ?></a>
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($msg->subject)){ echo $msg->subject;}else{ _e('Subject','school-mgt');}?>" ></i>		
										</td>
										<td>
											<?php
											$body_char=strlen($msg->message_body);
											if($body_char <= 40)
											{
												echo $msg->message_body;
											}
											else
											{
												$char_limit = 40;
												$msg_body= substr(strip_tags($msg->message_body), 0, $char_limit)."...";
												echo $msg_body;
											}
											?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if($msg->message_body){ echo $msg->message_body;}else{ _e('Description','school-mgt');}?>" ></i>	
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
											<a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">	
												<?php echo mj_smgt_convert_date_time($msg->date );?>
											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date & Time','school-mgt');?>" ></i>
										</td>
										</tr>
									<?php 
									$i++;
									}			
								}
								else
								{ ?>
									<tr>
									<td class="user_image width_50px profile_image_prescription padding_left_0">
										<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/inbox_icon.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
										</p>
									</td>	
									<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none"><?php 
										$auth = get_post($msg->post_id);
										$authid = $auth->post_author;
										echo mj_smgt_get_display_name($authid);
										?>
									</a></td>	
									<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none"><?php 
										$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($msg->post_id);	
										if($check_message_single_or_multiple == 1)
										{	
											global $wpdb;
											$tbl_name = $wpdb->prefix .'smgt_message';
											$post_id=$msg->post_id;
											$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
											
											echo mj_smgt_get_display_name($get_single_user->receiver);
										}
										else
										{					
											echo get_post_meta( $msg->post_id, 'message_for',true);
										}
										?></a></td>
									<td>
										<a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none"> 
											<?php echo $msg->subject;?>
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
										<?php if(mj_smgt_count_reply_item($msg->post_id)>=1){ ?><span class="badge badge-success pull-right"><?php echo mj_smgt_count_reply_item($msg->post_id);?></span><?php } ?></a>
									</td>
									<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">
										<?php
										$body_char=strlen($msg->message_body);
										if($body_char <= 60)
										{
											echo $msg->message_body;
										}
										else
										{
											$char_limit = 60;
											$msg_body= substr(strip_tags($msg->message_body), 0, $char_limit)."...";
											echo $msg_body;
										}
										?>
									</a></td>
									<td>	
									<?php			
									if(!empty($attchment))
									{	
										$attchment_array=explode(',',$attchment);
										foreach($attchment_array as $attchment_data)
										{
											?>
											<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data; ?>" class="btn btn-default"><i class="fa fa-eye"></i> <?php esc_attr_e('View Attachment','school-mgt');?></a>
											<?php				
										}
									}
									else
									{
										esc_attr_e('No Attachment','school-mgt');
									}
									?>				
									</td>
									<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">
										<?php  echo mj_smgt_convert_date_time($msg->date );?>
									</a></td>
									</tr>
									<?php 
								}
								$post_id=$msg->post_id;
							}
						}
						else
						{ ?>
						<tr>
						<td colspan="6" class="table_empty_message">
							<?php esc_attr_e('No data available in table','school-mgt'); ?>
						</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</form><!-- form-div -->
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
		?>
 </div>
 <?php ?>
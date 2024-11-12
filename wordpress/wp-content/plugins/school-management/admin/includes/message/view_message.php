<?php 
//Subject
if($_REQUEST['from']=='sendbox')
{
	$message = get_post($_REQUEST['id']);
	mj_smgt_change_read_status_reply($_REQUEST['id']);
	$author = $message->post_author;	
	$box='sendbox';
	if(isset($_REQUEST['delete']))
	{
		echo $_REQUEST['delete'];
		wp_delete_post($_REQUEST['id']);
		wp_safe_redirect(admin_url()."admin.php?page=smgt_message&tab=sentbox&message=2" );
		exit();
	}
}
if($_REQUEST['from']=='inbox')
{
	$message = mj_smgt_get_message_by_id($_REQUEST['id']);
	$message1 = get_post($message->post_id);
	$author = $message1->post_author;	
	mj_smgt_change_read_status($_REQUEST['id']);
	mj_smgt_change_read_status_reply($message->post_id);
	$box='inbox';

	if(isset($_REQUEST['delete']))
	{
		echo $_REQUEST['delete'];
			
		mj_smgt_delete_message('smgt_message',$_REQUEST['id']);
		wp_safe_redirect(admin_url()."admin.php?page=smgt_message&tab=inbox" );
		exit();
	}
}
if(isset($_POST['replay_message']))
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	
	$result=mj_smgt_send_replay_message($_POST);
	if($result)
		wp_safe_redirect(admin_url()."admin.php?page=smgt_message&tab=view_message&from=".$message_from."&id=$message_id&message=1" );
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete-reply')
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	$result=mj_smgt_delete_reply($_REQUEST['reply_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_message&action=delete-reply&from='.$message_from.'&id='.$message_id.'&message=2');
	}
}
?>

<div class="mailbox-content">	<!-- mailbox-content -->
 	<div class="message-header"><!-- message-header -->
		<h3><span><?php esc_attr_e('Subject','school-mgt')?> :</span>  <?php if($box=='sendbox'){ echo $message->post_title; } else{ echo $message->subject; } ?></h3>
       
		<p class="message-date">
		<?php  		
		if($box=='sendbox')
		{ 
		    $date_view=$message->post_date;
			echo $date_view; 
		}
		else
		{
			$date_view=$message->date;
			echo mj_smgt_convert_date_time($date_view); 
		}
		?>
		</p>
	</div><!-- message-header -->
	<div class="message-sender"> <!-- message-sender -->                         
    	<p>
		<?php 
		if($box=='sendbox')
		{ 
			$message_for=get_post_meta($_REQUEST['id'],'message_for',true);
			$author = $message->post_author;
			$author_name = mj_smgt_get_display_name($message->post_author);
			echo "".esc_html__('From','school-mgt')." : ".esc_html__($author_name,'school-mgt')."<span>&lt;".mj_smgt_get_emailid_byuser_id($message->post_author)."&gt;</span><br>";
			
			$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($_REQUEST['id']);	
					
			if($check_message_single_or_multiple == 1)
			{
				global $wpdb;
				$tbl_name = $wpdb->prefix .'smgt_message';
				$post_id=$_REQUEST['id'];
				$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
				$role = mj_smgt_get_display_name($get_single_user->receiver);
				echo "".esc_html__('To','school-mgt')." : ".esc_html__($role,'school-mgt')."<span>&lt;".mj_smgt_get_emailid_byuser_id($get_single_user->receiver)."&gt;</span><br>";
			}
			else
			{	
				$role = get_post_meta($_REQUEST['id'], 'message_for',true);			
				echo "".esc_html__('To','school-mgt')." : ".esc_html__($role,'school-mgt');
			}
		} 
		else
		{ 
			$author = $message->sender;
			$author_name = mj_smgt_get_display_name($message->sender);
			echo "".esc_html__('From','school-mgt')." : ".esc_html__($author_name,'school-mgt')."<span>&lt;".mj_smgt_get_emailid_byuser_id($message->sender)."&gt;</span><br>";
			
			$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($message->post_id);	
					
			if($check_message_single_or_multiple == 1)
			{
				global $wpdb;
				$tbl_name = $wpdb->prefix .'smgt_message';
				$post_id=$message->post_id;
				$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
				$role = mj_smgt_get_display_name($get_single_user->receiver);
				echo "".esc_html__('To','school-mgt')." : ".esc_html__($role,'school-mgt')."<span>&lt;".mj_smgt_get_emailid_byuser_id($get_single_user->receiver)."&gt;</span><br>";
			}
			else
			{	
				$role = get_post_meta($message->post_id, 'message_for',true);		
				echo "".esc_html__('To','school-mgt')." : ".esc_html__($role,'school-mgt');
			}			
		} 
		?>
		</p>
    </div><!-- message-sender -->     
    <div class="message-content"><!-- message-content -->     			
    	<p>
			<?php 
			$receiver_id=0;
			if($box=='sendbox')
			{ 
				echo $message->post_content; 
				echo '</br>';
				echo '</br>';			
				$attchment=get_post_meta( $message->ID, 'message_attachment',true);
				if(!empty($attchment))
				{
					$attchment_array=explode(',',$attchment);
					
					foreach($attchment_array as $attchment_data)
					{
						?>
							<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data; ?>" class="btn btn-default"><i class="fa fa-download"></i><?php esc_attr_e('View Attachment','school-mgt');?></a>
						<?php								
					}
				}
				$receiver_id=(get_post_meta($_REQUEST['id'],'message_smgt_user_id',true));
			} 
			else
			{ 				
				echo $message->message_body; 
				echo '</br>';
				echo '</br>';			
				$attchment=get_post_meta( $message->post_id, 'message_attachment',true);
				if(!empty($attchment))
				{
					$attchment_array=explode(',',$attchment);
					
					foreach($attchment_array as $attchment_data)
					{
						?>
							<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data; ?>" class="btn btn-default"><i class="fa fa-download"></i><?php esc_attr_e('View Attachment','school-mgt');?></a>
						<?php								
					}
				}
				$receiver_id=$message->sender;
			}
			?>
		</p>
   
		<div class="message-options pull-right">
			<a class="btn save_btn msg_delete_btn" href="?page=smgt_message&tab=view_message&id=<?php echo $_REQUEST['id'];?>&from=<?php echo $box;?>&delete=1" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash m-r-xs"></i><?php esc_attr_e('Delete','school-mgt')?></a> 
		</div>
    </div><!-- message-content -->   
	<?php 
	if(isset($_REQUEST['from']) && $_REQUEST['from']=='inbox')
	{
		$allreply_data=mj_smgt_get_all_replies($message->post_id);
	}
	else
	{
		$allreply_data=mj_smgt_get_all_replies($_REQUEST['id']);
	}
	if(!empty($allreply_data))
	{
		foreach($allreply_data as $reply)
		{	
			$receiver_name=mj_smgt_get_receiver_name_array($reply->message_id,$reply->sender_id,$reply->created_date,$reply->message_comment);		
			if($reply->sender_id == get_current_user_id() || $reply->receiver_id == get_current_user_id())
			{
				?>
				<div class="message-content">
				
					<p><?php echo $reply->message_comment;?>
					<?php
					
					$reply_attchment=$reply->message_attachment;				
					if(!empty($reply_attchment))
					{
						echo '</br>';
						echo '</br>';
						$reply_attchment_array=explode(',',$reply_attchment);
						
						foreach($reply_attchment_array as $attchment_data1)
						{
							?>
								<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data1; ?>" class="btn btn-default"><i class="fa fa-download"></i><?php esc_attr_e('View Attachment','school-mgt');?></a>
							<?php								
						}
					}
					?>
					<br><h5>
					<?php
					esc_attr_e('Reply By : ','school-mgt'); 
					echo mj_smgt_get_display_name($reply->sender_id); 
					esc_attr_e(' || ','school-mgt'); 	
					esc_attr_e('Reply To : ','school-mgt'); 
					echo $receiver_name; 
					esc_attr_e(' || ','school-mgt'); 	
					?>
					<span class="timeago"  title="<?php echo mj_smgt_convert_date_time($reply->created_date);?>"></span>
					<?php
					if($reply->sender_id == get_current_user_id())
					{
						?>	
						<span class="comment-delete">
						<a href="admin.php?page=smgt_message&tab=view_message&action=delete-reply&from=<?php echo $_REQUEST['from'];?>&id=<?php echo $_REQUEST['id'];?>&reply_id=<?php echo $reply->id;?>"><?php esc_attr_e('Delete','school-mgt');?></a></span> 
						<?php 
					} 
					?>
					</h5> 
					</p>
				</div>
				<?php 
			}
		}
	}		
   	?>
    <script type="text/javascript">
	$(document).ready(function() 
	{			
		  $('#selected_users').multiselect({ 
			 nonSelectedText :'<?php esc_attr_e("Select users to reply","school-mgt");?>',
			 includeSelectAllOption: true,
             selectAllText: '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>'			 
		 });
		 $("body").on("click","#check_reply_user",function()
		 {
			var checked = $(".dropdown-menu input:checked").length;

			if(!checked)
			{
				alert("<?php esc_html_e('Please select atleast one users to reply','school-mgt');?>");
				return false;
			}		
		});  
	 	$("body").on("click","#replay_message_btn",function()
		 {
			$(".replay_message_div").show();	
			$(".replay_message_btn").hide();	
		});   
	});
	</script>
	<form name="message-replay" method="post" id="message-replay" enctype="multipart/form-data"><!-- form -->   
   		<input type="hidden" name="message_id" value="<?php if($_REQUEST['from']=='sendbox') {echo $_REQUEST['id'];} else { echo $message->post_id; }?>">
   		<input type="hidden" name="user_id" value="<?php echo get_current_user_id();?>">
   		<!--<input type="hidden" name="receiver_id" value="<?php echo $receiver_id;?>">-->
		<?php
		global $wpdb;
		$tbl_name = $wpdb->prefix .'smgt_message';
		$current_user_id=get_current_user_id();
		
		if((string)$current_user_id == $author)
		{		
			if($_REQUEST['from']=='sendbox')
			{
				$msg_id=$_REQUEST['id']; 
				$msg_id_integer=(int)$msg_id;
				$reply_to_users =$wpdb->get_results("SELECT *  FROM $tbl_name where post_id = $msg_id_integer");			
			}
			else
			{
				$msg_id=$message->post_id;			
				$msg_id_integer=(int)$msg_id;
				$reply_to_users =$wpdb->get_results("SELECT *  FROM $tbl_name where post_id = $msg_id_integer");			
			}		
			
		}
		else
		{
			$reply_to_users=array();
			$reply_to_users[]=(object)array('receiver'=>$author);
			
		}

		?>
		<div class="message-options pull-right">
			<button type="button" name="replay_message_btn" class="btn save_btn replay_message_btn" id="replay_message_btn"><i class="fa fa-reply m-r-xs"></i><?php esc_html_e('Reply','school-mgt')?></button>
		</div>
    	
		<div class="form-body user_form mt-3"><!-- user_form-->   
			<div class="row">
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 single_class_div support_staff_user_div input">
					<div id="messahe_test"></div>
					<div class="col-sm-12 smgt_multiple_select">
						<span class="user_display_block">
							<select name="receiver_id[]" class="form-control" id="selected_users" multiple="true">
								<?php						
								foreach($reply_to_users as $reply_to_user)
								{  	
									$user_data=get_userdata($reply_to_user->receiver);
									if(!empty($user_data))
									{								
										if($reply_to_user->receiver != get_current_user_id())
										{
											?>
											<option  value="<?php echo $reply_to_user->receiver;?>" ><?php echo mj_smgt_get_display_name($reply_to_user->receiver); ?></option>
											<?php
										}
									}							
								} 
								?>
							</select>
						</span>
					</div>
				</div>
				<div class="col-md-6 note_text_notice error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="replay_message_body" id="replay_message_body" class="textarea_height_47px form-control validate[required] form-control text-input"></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active" for="photo"><?php esc_attr_e('Message Comment','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
				</div>	
				<div  class="col-md-6 attachment_div">
					<div class="row">
						<div class="col-md-10">	
							<div class="form-group input">
								<div class="col-md-12 form-control res_rtl_height_50px">
									<label class="custom-control-label custom-top-label ml-2 margin_left_30px" for="photo"><?php esc_attr_e('Attachment','school-mgt');?></label>
									<div class="col-sm-12">	
										<input class="input-file" name="message_attachment[]" type="file" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">	
							<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_new_attachment_view()" alt="" class="rtl_margin_top_15px float_right" id="add_more_sibling">
						</div>
					</div>
				</div>
			</div>
		</div><!-- user_form-->   
		<div class="form-body user_form mt-3"><!-- user_form-->   
			<div class="row">
				<div class="col-sm-6">          
					<button type="submit" name="replay_message" class="btn btn-success save_btn" id="check_reply_user"><?php esc_attr_e('Send Message','school-mgt')?></button>	
				</div>    
			</div>
		</div>  <!-- user_form-->      
	</form><!-- form div -->   
</div><!-- mailbox-content -->
<script>
function add_new_attachment_view()
{
	$(".attachment_div").append('<div class="row"><div class="col-md-10"><div class="form-group input"><div class="col-md-12 form-control res_rtl_height_50px"><label class="custom-control-label custom-top-label ml-2 margin_left_30px" for="photo"><?php esc_attr_e('Attachment','school-mgt');?></label><div class="col-sm-12"><input  class="col-md-12 input-file" name="message_attachment[]" type="file" /></div></div></div></div><div class="col-sm-2"><input type="image" onclick="delete_attachment(this)" alt="" src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" class="remove_cirtificate rtl_margin_top_15px doc_label float_right input_btn_height_width"></div></div>');
}
</script>
<?php ?>
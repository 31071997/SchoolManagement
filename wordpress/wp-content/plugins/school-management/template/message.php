<?php ?>
<script type="text/javascript">
jQuery(document).ready(function($){
"use strict";	
$('#message_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
$('#selected_users').multiselect({ 
	 nonSelectedText :"<?php esc_attr_e( 'Select Users', 'school-mgt' ) ;?>",
	includeSelectAllOption: true,
	selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
	templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },            
 });
 $('#selected_class').multiselect({ 
		 nonSelectedText :"<?php esc_attr_e( 'Select Class', 'school-mgt' ) ;?>",
        includeSelectAllOption: true,
		selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },
     });

 $("body").on("click",".save_message_selected_user",function()
	{		
		var class_selection_type = $(".class_selection_type").val();	
				
		if(class_selection_type == 'multiple')
		{
			var checked = $(".multiselect_validation1 .dropdown-menu input:checked").length;

			if(!checked)
			{
				alert(language_translate2.one_class_select_alert);
				return false;
			}	
		}			
	}); 
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		"use strict";
		var file = this.files[0]; 		
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, [,'pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg','']) == -1)
		{
			alert('<?php esc_attr_e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. ','school-mgt');?>'  + ext + '<?php esc_attr_e(' formate are not allowed.','school-mgt'); ?>');
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');
			return false; 
		} 
		//File Size Check 
		if (file.size > 20480000) 
		{
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />'); 
			return false; 
		}
	});	

	$('#selected_users').multiselect({ 
			 nonSelectedText :'<?php esc_attr_e( 'Select users to reply', 'school-mgt' ) ;?>',
			 includeSelectAllOption: true,
			 templates: {
           		button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       		},
		 });
		 $("body").on("click","#check_reply_user",function()
		 {
			var checked = $(".dropdown-menu input:checked").length;

			if(!checked)
			{
				alert(language_translate2.one_user_replys_alert);
				return false;
			}		
		}); 
		$("body").on("click","#replay_message_btn",function()
		{
			$(".replay_message_div").show();	
			$(".replay_message_btn").hide();	
		});  
	jQuery('#message-replay').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	jQuery('span.timeago').timeago();
	$('.multiselect-search').removeClass('form-control',0);

});

function add_new_attachment()
{
	//$(".attachment_div").append('<div class="mb-3 form-group row"><label class="col-sm-2 control-label col-form-label text-md-end" for="photo"><?php esc_attr_e( 'Attachment', 'school-mgt' ) ;?></label><div class="col-sm-3"><input  class="btn_top input-file" name="message_attachment[]" type="file" /></div><div class="col-sm-2"><input type="button" value="<?php esc_attr_e( 'Delete', 'school-mgt' ) ;?>" onclick="delete_attachment(this)" class="remove_cirtificate doc_label btn btn-danger"></div></div>');
	$(".attachment_div").append('<div class="row"><div class="col-md-10"><div class="form-group input"><div class="col-md-12 form-control res_rtl_height_50px"><label class="custom-control-label custom-top-label ml-2 margin_left_30px" for="photo"><?php esc_attr_e('Attachment','school-mgt');?></label><div class="col-sm-12">	<input  class="col-md-12 input-file" name="message_attachment[]" type="file" /></div></div></div></div><div class="col-sm-2"><input type="image" onclick="delete_attachment(this)" alt="" src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" class="rtl_margin_top_15px remove_cirtificate doc_label float_right input_btn_height_width"></div></div>');
}
function delete_attachment(n)
{
	n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);				
}


function add_new_attachment2()
{
	//$(".attachment_div").append('<div class="form-group row" ><label class="col-sm-2 control-label" for="photo"><?php esc_attr_e( 'Attachment', 'school-mgt' ) ;?></label><div class="col-sm-3" style="margin-bottom: 5px;"><input  class="btn_top input-file" name="message_attachment[]" type="file" /></div><div class="col-sm-7" style="margin-bottom: 5px;"><input type="button" value="<?php esc_attr_e( 'Delete', 'school-mgt' ) ;?>" onclick="delete_attachment(this)" class="remove_cirtificate doc_label btn btn-danger"></div></div>');
	$(".attachment_div").append('<div class="row"><div class="col-md-10"><div class="form-group input"><div class="col-md-12 form-control res_rtl_height_50px"><label class="custom-control-label custom-top-label ml-2 margin_left_30px" for="photo"><?php esc_attr_e('Attachment','school-mgt');?></label><div class="col-sm-12"><input  class="col-md-12 input-file" name="message_attachment[]" type="file" /></div></div></div></div><div class="col-sm-2"><input type="image" onclick="delete_attachment(this)" alt="" src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" class="rtl_margin_top_15px remove_cirtificate doc_label float_right input_btn_height_width"></div></div>');
}
</script>

<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
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
$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'inbox'; 
?>
<div class="row mailbox-header frontend_list_margin_30px_res">
	<?php
	$tab_name='';
	if(!empty($_REQUEST['tab']))
	{
		$tab_name = $_REQUEST['tab'];
	}
	?>
	<div class="col-md-12 "><!--col-md-12 padding_0-->	
		<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per list-unstyled mailbox-nav">
			<li <?php if(!isset($tab_name) || ($tab_name == 'inbox')){?>class="active"<?php }?>>
				<a href="?dashboard=user&page=message&tab=inbox" class="smgt_inbox_tab"><i class="fa fa-inbox"></i> <?php esc_attr_e('Inbox','school-mgt');?><span class="smgt_inbox_count_number badge badge-success  pull-right ms-1" style="border-radius: 15px!important;"><?php echo mj_smgt_count_unread_message(get_current_user_id());?></span></a>
			</li>
			<li <?php if(isset($_REQUEST['page']) && $tab_name == 'sentbox'){?>class="active"<?php }?>>
				<a href="?dashboard=user&page=message&tab=sentbox" class="padding_left_0 tab"><?php esc_attr_e('Sent','school-mgt');?></a>
			</li>
			<li class="active">
				<?php 
				if(isset($_REQUEST['page']) && $tab_name == 'compose')
				{?>
					<a href="#" class="padding_left_0 tab"><?php esc_attr_e('Compose','school-mgt');?></a>
					<?php 
				}?>
			</li>
			<li class="active">
				<?php 
				if(isset($_REQUEST['page']) && $tab_name == 'view_message')
				{?>
					<a href="#" class="padding_left_0 tab"><?php esc_attr_e('View Message','school-mgt');?></a>
					<?php 
				}?>
			</li>
		</ul>
	</div><!--col-md-12 padding_0-->
	
	 <?php
		 if($active_tab == 'sentbox')
			 require_once SMS_PLUGIN_DIR. '/template/sendbox.php';
		 if($active_tab == 'inbox')
			 require_once SMS_PLUGIN_DIR. '/template/inbox.php';
		 if($active_tab == 'compose')
			 require_once SMS_PLUGIN_DIR. '/template/composemail.php';
		 if($active_tab == 'view_message')
			 require_once SMS_PLUGIN_DIR. '/template/view_message.php';
		 ?>
	 <!-- </div> -->
</div>
<?php  ?>
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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('homework');
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
			if ('homework' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('homework' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('homework' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
	
	var table =  jQuery('#submission_list').DataTable({
		"initComplete": function(settings, json) {
			$(".print-button").css({"margin-top": "-5%"});
		},
		//stateSave: true,
		responsive: true,
		"dom": 'lifrtp',
		"ordering": true,
		"aoColumns":[	                  
					{"bSortable": true},
					{"bSortable": true},
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

	$('#homework_form_admin').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('.datepicker').datepicker({
		minDate:'0',
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeYear:true,
			changeMonth: true,
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		});
});
</script>
<script type="text/javascript">
$(document).ready(function()
{
	//------------ CLOSE MESSAGE ---------//
	$('.notice-dismiss').click(function() {
		$('#message').hide();
	}); 
}); 
</script>
<?php 
    $obj_feespayment=new Smgt_Homework();	 
	// Save HomeWork !!!!!!!!! 
	if(isset($_POST['Save_Homework']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_homework_admin_nonce' ) )
		{
			$insert=new Smgt_Homework();
			if($_POST['action'] == 'edit')
			{
				if(isset($_FILES['homework_document']) && !empty($_FILES['homework_document']) && $_FILES['homework_document']['size'] !=0)
				{		
					if($_FILES['homework_document']['size'] > 0)
						$upload_docs1=mj_smgt_load_documets_new($_FILES['homework_document'],$_FILES['homework_document'],$_POST['document_name']);		
				}
				else
				{
					if(isset($_REQUEST['old_hidden_homework_document']))
					$upload_docs1=$_REQUEST['old_hidden_homework_document'];
				}
				 
				$document_data=array();
				if(!empty($upload_docs1))
				{
					$document_data[]=array('title'=>$_POST['document_name'],'value'=>$upload_docs1);
				}
				else
				{
					$document_data[]='';
				}
				
				$update_data=$insert->mj_smgt_add_homework($_POST,$document_data);
				if($update_data)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist&message=2');
				}
			}
			else 
			{
				$args = array( 'meta_query' => array( array( 'key' => 'class_name', 'value' => $_POST['class_name'], 'compare' => '=' ) ), 'count_total' => true ); 
				$users = new WP_User_Query($args); 
				if ($users->get_total() == 0)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist&message=4');
				}
				else
				{
					if(isset($_FILES['homework_document']) && !empty($_FILES['homework_document']) && $_FILES['homework_document']['size'] !=0)
					{		
						if($_FILES['homework_document']['size'] > 0)
							$upload_docs1=mj_smgt_load_documets_new($_FILES['homework_document'],$_FILES['homework_document'],$_POST['document_name']);		
					}
					else
					{
						$upload_docs1='';
					}
					 
					$document_data=array();
					if(!empty($upload_docs1))
					{
						$document_data[]=array('title'=>$_POST['document_name'],'value'=>$upload_docs1);
					}
					else
					{
						$document_data[]='';
					}
					
					$insert_data=$insert->mj_smgt_add_homework($_POST,$document_data);
					if($insert_data)
					{
						wp_redirect ( admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist&message=1');
					}
				}
			}
		}
    }
	$tablename="mj_smgt_homework";
	/*Delete selected Subject*/
	if(isset($_REQUEST['delete_selected']))
	{		
	    $ojc=new Smgt_Homework();
		 if(!empty($_REQUEST['id']))
		 {
		   foreach($_REQUEST['id'] as $id)
		    {
			  $delete=$ojc->mj_smgt_get_delete_records($tablename,$id);
		    }
			if($delete)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist&message=3');
			}
	    }
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$delete=new Smgt_Homework();
	    $dele=$delete->mj_smgt_get_delete_record($_REQUEST['homework_id']);
		if($dele)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist&message=3');
		}
	}
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'homeworklist';
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
<div class="page-inner"><!-- page-inner -->
	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
		<div class="row"><!-- Row -->
			<?php
			$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
			switch($message)
			{
				case '1':
					$message_string = esc_attr__('Homework Added Successfully.','school-mgt');
					break;
				case '2':
					$message_string = esc_attr__('Homework Updated Successfully.','school-mgt');
					break;	
				case '3':
					$message_string = esc_attr__('Homework Deleted Successfully.','school-mgt');
					break;
				case '4':
					$message_string = esc_attr__('No Student Available In This Class.','school-mgt');
					break;
			}
			if($message)
			{ ?>
			<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
				<p><?php echo $message_string;?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div>
			<?php 
		} ?>
			<div class="col-md-12 padding_0"><!-- col-md-12 -->	
				<!-- nav-tabs start -->	
				<?php
				$action = "";
				if(!empty($_REQUEST['action']))
				{
					$action = $_REQUEST['action'];
				}
				?>
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
					<li class="<?php if($active_tab=='homeworklist'){?>active<?php }?>">
						<a href="?page=smgt_student_homewrok&tab=homeworklist" class="padding_left_0 tab <?php echo $active_tab == 'homeworklist' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Homework List', 'school-mgt'); ?></a>
					</li>
					<li class="<?php if($active_tab=='view_stud_detail' || $action == 'viewsubmission'){?>active<?php }?>">
						<a href="?page=smgt_student_homewrok&tab=view_stud_detail" class="padding_left_0 tab <?php echo $active_tab == 'view_stud_detail' ? 'nav-tab-active' : ''; ?> ">
						<?php echo esc_attr__('View Submission', 'school-mgt'); ?></a>
					</li>  
					<?php
					if($active_tab == 'addhomework')
					{
						if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
						{	?>
						<li class="<?php if($active_tab=='addhomework' || $_REQUEST['action'] == 'edit'){?>active<?php }?>">
							<a href="#" class="padding_left_0 tab <?php echo $active_tab == 'addhomework' ? 'nav-tab-active' : ''; ?>">
							<?php esc_attr_e('Edit Homework', 'school-mgt'); ?></a>  
						</li> 
							<?php 
						}
						else
						{	?>
							<?php if($user_access_add == '1')
							{ ?>
								<li class="<?php if($active_tab=='addhomework' || $action	 == 'edit'){?>active<?php }?>">
									<a href="#" class="padding_left_0 tab <?php echo $active_tab == 'addhomework' ? 'nav-tab-active' : ''; ?>">
									<?php echo esc_attr__('Add Homework', 'school-mgt'); ?></a>  
								</li> 
								<?php 
							}
						} ?>
						<?php
					}
					?>
				</ul>
				<!-- nav-tabs end -->	
				<?php
				if($active_tab == 'homeworklist')
				{	
					require_once SMS_PLUGIN_DIR. '/admin/includes/student_HomeWork/homeworklist.php'; 
				}
				if($active_tab == 'addhomework')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/student_HomeWork/add-studentHomework.php';
				}
				// view student Status
				if($active_tab == 'view_stud_detail')
				{	
					$homework=new Smgt_Homework();
					$res=$homework->mj_smgt_get_class_homework();
					$edit=0;
					?>
					<div class="panel-body"><!-- panel-body--> 	
						<div class="smgt_homework_list"> <!-- smgt_homework_list--> 
							<form name="class_form" action="" method="post" class="form-horizontal" id="class_form">
								<div class="form-body user_form mb-2"> <!-- user_form div-->   
									<div class="row"><!--Row Div--> 
										<div class="col-sm-9 col-md-9 col-lg-9 col-xl-9 input smgt_form_select margin_top_15px_rs">
											<label class="custom-top-label lable_top top" for="homewrk"><?php esc_attr_e('Select Homework','school-mgt');?><span class="require-field">*</span></label>
											<?php if($edit){ $classval=$user_info->class_name; }elseif(isset($_POST['class_name'])){$classval=$_POST['class_name'];}else{$classval='';}?>
											<select name="homewrk" class="form-control validate[required]" id="homewrk">
												<option value=""><?php esc_attr_e('Select Homework','school-mgt');?></option>
												<?php
												$classval='';
												if(isset($_REQUEST['homewrk']))
												{
													$classval=$_REQUEST['homewrk'];
												}
												foreach($res as $classdata)
												{  
												?>
													<option value="<?php echo $classdata->homework_id;?>" <?php selected($classdata->homework_id,$classval);  ?>><?php echo $classdata->title;?></option>
												<?php 
												}?>
											</select>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 res_rtl_width_100">
											<input type="submit" value="<?php esc_attr_e('View','school-mgt');?>" name="view"  class="save_btn custom_class"/>
										</div>
									</div><!--Row Div--> 
								</div> <!-- user_form div--> 
								<?php
								$obj=new Smgt_Homework();
								if(isset($_POST['homewrk']))
								{
									$data=$_POST['homewrk'];
									$retrieve_class=$obj-> mj_smgt_view_submission($data);
									require_once SMS_PLUGIN_DIR. '/admin/includes/student_HomeWork/viewsubmission.php';
								}
								else
								{
									if(isset($_REQUEST['homework_id']))
									{
										$data=$_REQUEST['homework_id'];
										$retrieve_class=$obj-> mj_smgt_view_submission($data);
										require_once SMS_PLUGIN_DIR. '/admin/includes/student_HomeWork/viewsubmission.php';
									}
								}
								?>
							</form>
						</div><!-- smgt_homework_list--> 
					</div><!-- panel-body--> 	
					<?php
				}
				?>
			</div><!-- col-md-12 -->	
		</div><!-- Row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner -->
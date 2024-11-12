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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('subject');
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
			if ('subject' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('subject' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('subject' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
<script>
jQuery(document).ready(function($)
{
	"use strict";	
	$('#subject_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
    
	$("#subject_teacher").multiselect({ 
        nonSelectedText :'<?php esc_html_e('Select Teacher','school-mgt');?>',
        includeSelectAllOption: true ,
		selectAllText : '<?php esc_html_e('Select all','school-mgt');?>',
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       	},
    });	
	$(".teacher_for_alert").on('click',function()
	{	
		var checked = $(".form-check-input:checked").length;
		if(!checked)
		{
		alert(language_translate2.one_teacher_alert);
		return false;
		}	
	}); 
});
</script>
<script>
	$(document).ready(function ()
	{
		$('.file_validation ').change(function () 
		{
			var val = $(this).val().toLowerCase();
			var regex = new RegExp("(.*?)\.(docx|doc|pdf|ppt|jpg|jpeg|png|xls|xlsx|ppt|pptx|gif)$");
			if(!(regex.test(val)))
			{
				$(this).val('');
				alert("<?php _e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed.','school-mgt');?>");
			} 
		}); 
	});
</script> 
<?php 
	// This is Dashboard at admin side!!!!!!!!! 
	//--------------Delete code-------------------------------
	$teacher_obj = new Smgt_Teacher;
	$tablename="subject";
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=mj_smgt_delete_subject($tablename,$_REQUEST['subject_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=4');
			}
		}
	/*Delete selected Subject*/
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $subject_id)
			$result=mj_smgt_delete_subject($tablename,$subject_id);
		if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=4');
			}
	}
	//------------------Edit-Add code ------------------------------
	if(isset($_POST['subject']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_subject_admin_nonce' ) )
		{
		
			$syllabus='';
			if(isset($_FILES['subject_syllabus']) && !empty($_FILES['subject_syllabus']['name']))
			{
				$value = explode(".", $_FILES['subject_syllabus']['name']);
				$file_ext = strtolower(array_pop($value));
				$extensions = array("pdf");
				
				if(in_array($file_ext,$extensions )=== false)
				{				
					wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=3');
					exit;
				}
				if($_FILES['subject_syllabus']['size'] > 0)
				{
				$syllabus=inventory_image_upmj_smgt_load($_FILES['subject_syllabus']);
				}	
				else {
					$syllabus=$_POST['sylybushidden'];
				}
				//------TEMPRORY ADD RECORD FOR SET SYLLABUS----------		
			}
			
			$subjects=array(
							'subject_code'=>mj_smgt_onlyNumberSp_validation($_POST['subject_code']),
							'sub_name'=>mj_smgt_address_description_validation(stripslashes($_POST['subject_name'])),
							'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['subject_class']),
							'section_id'=>mj_smgt_onlyNumberSp_validation($_POST['class_section']),
							'teacher_id'=>0,
							'edition'=>mj_smgt_address_description_validation(stripslashes($_POST['subject_edition'])),
							'author_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['subject_author']),			
							'syllabus'=>$syllabus,
							'created_by'=>get_current_user_id()
			);
			if(isset($_FILES['subject_syllabus']) && empty($_FILES['subject_syllabus']['name']))
			{
				unset($subjects['syllabus']);
			}
			$tablename="subject";
				$selected_teachers = isset($_REQUEST['subject_teacher'])?$_REQUEST['subject_teacher']:array();
			
			if($_REQUEST['action']=='edit')
			{
				//------------ SUBJECT CODE CHECK ------------//
					$sub_id=$_REQUEST['subject_id'];
					$class_id=$_POST['subject_class'];
					global $wpdb;
					
					$table_name_subject = $wpdb->prefix .'subject';
					
					$result_sub =$wpdb->get_results("SELECT * FROM $table_name_subject WHERE class_id=$class_id and subid !=".$sub_id);
					
					if(!empty($result_sub))
					{
						foreach($result_sub as $sub_code)
						{
							$subject_code[]=$sub_code->subject_code;
						}
						$check=in_array($_POST['subject_code'], $subject_code);
						if($check)
						{
							wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=addsubject&action=edit&subject_id='.$sub_id.'&message=5');
							die;
						}
					}
					global $wpdb;
					$table_smgt_subject = $wpdb->prefix. 'teacher_subject';  
				//---------------------------------// 
					$subid=array('subid'=>$_REQUEST['subject_id']);
					$result=mj_smgt_update_record($tablename,$subjects,$subid);
					$wpdb->delete( 
						$table_smgt_subject,      // table name 
						array( 'subject_id' => $_REQUEST['subject_id'] ),  // where clause 
						array( '%s' )      // where clause data type (string)
					);
										
								
					if(!empty($selected_teachers))
					{
						$teacher_subject = $wpdb->prefix .'teacher_subject';
						foreach($selected_teachers as $teacher_id)
						{
							$wpdb->insert($teacher_subject,
								array( 
									'teacher_id' => $teacher_id,
									'subject_id' => $_REQUEST['subject_id'],
									'created_date' => time(),
									'created_by' => get_current_user_id()
								)
							); 
						}
					}			
						/* Send Assign Subject Mail */	
						if(isset($_POST['smgt_mail_service_enable']))
						{
							foreach($_POST['subject_teacher'] as $teacher_id)
							{
								$smgt_mail_service_enable = $_POST['smgt_mail_service_enable'];
								if($smgt_mail_service_enable)
								{	
									$search['{{teacher_name}}']	 	= 	mj_smgt_get_teacher($teacher_id);
									$search['{{subject_name}}'] 	= 	$_POST['subject_name'];						
									$search['{{school_name}}'] 		= 	get_option('smgt_school_name');								
									$message = mj_smgt_string_replacement($search,get_option('assign_subject_mailcontent'));	
									
									if(!empty($syllabus))
									{
										$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$syllabus;
									}
									else
									{
										$attechment = '';
									}
									if(get_option('smgt_mail_notification') == '1')
									{
										mj_smgt_send_mail_for_homework(mj_smgt_get_emailid_byuser_id($teacher_id),get_option('assign_subject_title'),$message,$attechment);
									}
								}
							}
						}		

						wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=2');
			}
			else
			{  
				/* Setup Wizard */
				$wizard = smgt_setup_wizard_steps_updates('step4_subject');
				$subject_code=$_POST['subject_code'];
				$class_id=$_POST['subject_class'];
					global $wpdb;
					
					$table_name_subject = $wpdb->prefix .'subject';
					
					$result_sub =$wpdb->get_results("SELECT * FROM $table_name_subject WHERE class_id=$class_id and subject_code=".$subject_code);
					
					if(!empty($result_sub))
					{
						wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=addsubject&message=5');
						die;
					}	
				$result=mj_smgt_insert_record($tablename,$subjects);
				$lastid = $wpdb->insert_id;
				if(!empty($selected_teachers))
				{
					$teacher_subject = $wpdb->prefix .'teacher_subject';
					$device_token = array();
					foreach($selected_teachers as $teacher_id)
					{
						$wpdb->insert( 
						$teacher_subject, 
						array( 
							'teacher_id' => $teacher_id,
							'subject_id' => $lastid,
							'created_date' => time(),
							'created_by' => get_current_user_id()
							)
						);
						$device_token[] = get_user_meta($teacher_id, 'token_id' , true);
					}
					/* Send Push Notification */

					$title = esc_attr__('New Notification For Assign Subject','school-mgt');
					$text = esc_attr__('New subject','school-mgt').' '.$_POST['subject_name'].' '.esc_attr__('has been assigned to you.','school-mgt');
					$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'notification'));
					$json = json_encode($notification_data);
					$message = MJ_smgt_send_push_notification($json);

					/* Send Push Notification */
				}
				if($result)
				{
					/* Send Assign Subject Mail */
					if(isset($_POST['smgt_mail_service_enable']))
					{
						
						foreach($_POST['subject_teacher'] as $teacher_id)
						{
							
							$smgt_mail_service_enable = $_POST['smgt_mail_service_enable'];
							if($smgt_mail_service_enable)
							{	
								$search['{{teacher_name}}']	 	= 	mj_smgt_get_teacher($teacher_id);
								$search['{{subject_name}}'] 	= 	$_POST['subject_name'];						
								$search['{{school_name}}'] 		= 	get_option('smgt_school_name');								
								$message = mj_smgt_string_replacement($search,get_option('assign_subject_mailcontent'));
								if(!empty($syllabus))
								{
									$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$syllabus;
								}
								else
								{
									$attechment = '';
								}			
								if(get_option('smgt_mail_notification') == '1')
								{
									mj_smgt_send_mail_for_homework(mj_smgt_get_emailid_byuser_id($teacher_id),get_option('assign_subject_title'),$message,$attechment);
								}	
							}
						}
						
					}	
					wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=1');
				}	
			}
		}
	}

	//-------------- EXPORT SUBJECT DATA ---------------//
	if(isset($_POST['subject_export_csv_selected']))
	{
		if(isset($_POST['id']))
		{	
			foreach($_POST['id'] as $s_id)
			{
				$subject_list[]=mj_smgt_get_subject($s_id);
			}
			if(!empty($subject_list))
			{
				$header = array();			
				$header[] = 'Subject Code';
				$header[] = 'Subject Name';
				$header[] = 'Teacher';
				$header[] = 'Class Name';
				$header[] = 'Section Name';
				$header[] = 'Author Name';			
				$header[] = 'Edition';
				$header[] = 'Created By';
				
				$filename='Reports/export_subject.csv';
				$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
				fputcsv($fh, $header);
				foreach($subject_list as $retrive_data)
				{
					$row = array();
					$teacher_group = array();
					$teacher_ids = mj_smgt_teacher_by_subject($retrive_data);
					foreach($teacher_ids as $teacher_id)
					{
						$teacher_group[] = mj_smgt_get_teacher($teacher_id);
					}
					
					$teachers = implode(',',$teacher_group);
					
					
					$cid=$retrive_data->class_id;
					$clasname=mj_smgt_get_class_name($cid);
					if($retrive_data->section_id != 0)
					{
						$section_name = mj_smgt_get_section_name($retrive_data->section_id);
					}
					else
					{
						$section_name = esc_attr__('No Section','school-mgt');
					}
					$created_by = mj_smgt_get_user_name_byid($retrive_data->created_by);

					$row[] =  $retrive_data->subject_code;
					$row[] =  $retrive_data->sub_name;
					$row[] =  $teachers;
					$row[] =  $clasname;
					$row[] =  $section_name;
					$row[] =  $retrive_data->author_name;
					$row[] =  $retrive_data->edition;
					$row[] =  $created_by;
					fputcsv($fh, $row);
				}
				
				fclose($fh);
		
				//download csv file.
				ob_clean();
				$file=SMS_PLUGIN_DIR.'/admin/Reports/export_subject.csv';//file location
			
				$mime = 'text/plain';
				header('Content-Type:application/force-download');
				header('Pragma: public');       // required
				header('Expires: 0');           // no cache
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
				header('Cache-Control: private',false);
				header('Content-Type: '.$mime);
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Content-Transfer-Encoding: binary');
				header('Connection: close');
				readfile($file);		
				exit;	
			}
			else
			{
				echo "<div style=' background: none repeat scroll 0 0 red;
				border: 1px solid;
				color: white;
				float: left;
				font-size: 17px;
				margin-top: 10px;
				padding: 10px;
				width: 98%;'>Records not found.</div>";
			}
		}
	}
	//-------------- EXPORT SUBJECT DATA ---------------//

	//--------------  IMPORT SUBJECT CSV DATA --------------//
	if(isset($_REQUEST['upload_csv_file']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'upload_subject_admin_nonce' ) )
		{
			if(isset($_FILES['csv_file']))
			{
				$errors= array();
				$file_name = $_FILES['csv_file']['name'];
				$file_size =$_FILES['csv_file']['size'];
				$file_tmp =$_FILES['csv_file']['tmp_name'];
				$file_type=$_FILES['csv_file']['type'];
				$value = explode(".", $_FILES['csv_file']['name']);
				$file_ext = strtolower(array_pop($value));				
				$extensions = array("csv");
				$upload_dir = wp_upload_dir();
				if(in_array($file_ext,$extensions )=== false)
				{
					$err=esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');
					$errors[]=$err;
					wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=6');
				}
				//------------ Check File Size ------------//
				if($file_size > 2097152)
				{
					$errors[]='File size limit 2 MB';
					wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=7');
				}
				if(empty($errors)==true)
				{
					$rows = array_map('str_getcsv', file($file_tmp));
					$header = array_map('trim',array_map('strtolower',array_shift($rows)));
					$csv = array();
					foreach ($rows as $row) 
					{
						global $wpdb;
						$csv = array_combine($header, $row);
						$selected_teachers = isset($_POST['subject_teacher'])?$_POST['subject_teacher']:array();
						$teacher_subject = $wpdb->prefix .'teacher_subject';
						$table_smgt_subject = $wpdb->prefix. 'subject';
					
						if(isset($csv['subject name']))
							$subjectdata['sub_name']=$csv['subject name'];
						if(isset($_POST['subject_teacher']))
							$subjectdata['teacher_id']=0;
						if(isset($_POST['class_name']))
							$subjectdata['class_id']=$_POST['class_name'];
						if(isset($_REQUEST['class_section']))
							$subjectdata['section_id']=$_REQUEST['class_section'];
						if(isset($csv['author name']))
							$subjectdata['author_name']=$csv['author name'];
						if(isset($csv['edition']))
							$subjectdata['edition']=$csv['edition'];
						if(isset($csv['subject code']))
							$subjectdata['subject_code']=$csv['subject code'];
						$subjectdata['created_by']=get_current_user_id();
					
						$all_subject = $wpdb->get_results("SELECT * FROM $table_smgt_subject");
						$subject_name=array();
						$subject_code=array();	
						foreach ($all_subject as $subject_data) 
						{
							$subject_name[]=$subject_data->sub_name;
							$subject_code[]=$subject_data->subject_code;
						}
						
						if (in_array($subjectdata['sub_name'], $subject_name) && in_array($subjectdata['subject_code'], $subject_code))
						{
							$import_subject_name=$subjectdata['sub_name'];
							$import_subject_code=$subjectdata['subject_code'];
							
							$existing_subject_data = $wpdb->get_row("SELECT subid FROM $table_smgt_subject where sub_name='$import_subject_name' AND subject_code='$import_subject_code'");

							$id['subid']=$existing_subject_data->subid;
												
							$wpdb->update( $table_smgt_subject, $subjectdata,$id);	
							$wpdb->delete( 
								$teacher_subject,      // table name 
								array( 'subject_id' => $existing_subject_data->subid ),  // where clause 
								array( '%s' )      // where clause data type (string)
							);
							if(!empty($selected_teachers))
							{
								foreach($selected_teachers as $teacher_id)
								{
									$wpdb->insert($teacher_subject,
										array( 
											'teacher_id' => $teacher_id,
											'subject_id' => $existing_subject_data->subid,
											'created_date' => time(),
											'created_by' => get_current_user_id()
										)
									); 
								}
							}
							$success = 1;	
						}
						else
						{ 	
							$wpdb->insert( $table_smgt_subject, $subjectdata);	
							$lastid = $wpdb->insert_id;
							if(!empty($selected_teachers))
							{
								foreach($selected_teachers as $teacher_id)
								{
									$wpdb->insert( 
									$teacher_subject, 
									array( 
										'teacher_id' => $teacher_id,
										'subject_id' => $lastid,
										'created_date' => time(),
										'created_by' => get_current_user_id()
										)
									);
					
								}
							}
							$success = 1;	
						}
						if($success == 1)
						{
							wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=8');
						}
					}
				}
			}
		}
	}
	//------ upload CSV Code  -----------//

$active_tab = isset($_GET['tab'])?$_GET['tab']:'Subject';
	?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="view_popup"></div>    
			<div class="category_list"></div> 
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<div class="page-inner">
	<div id="" class="main_list_margin_5px">
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Subject Added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Subject Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('This File Type Is Not Allowed, Please Upload Only Pdf File.','school-mgt');
				break;	
			case '4':
				$message_string = esc_attr__('Subject Deleted Successfully.','school-mgt');
				break;		
			case '5':
				$message_string = esc_attr__('Please Enter Unique Subject Code','school-mgt');
				break;	
			case '6':
				$message_string = esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');
				break;	
			case '7':
				$message_string = esc_attr__('File size limit 2 MB.','school-mgt');
				break;
			case '8':
				$message_string = esc_attr__('Subject CSV Imported Successfully.','school-mgt');
				break;
		}
		
		if($message)
		{ ?>
			<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
				<p><?php echo $message_string;?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div>
		<?php } ?>
		<div class="panel-white">
			<div class="panel-body"> 
				<?php
				if($active_tab == 'Subject')
				{
					$retrieve_subjects=mj_smgt_get_all_data($tablename);
					if(!empty($retrieve_subjects))
					{
						?>
						<script>
							jQuery(document).ready(function($)
							{
								"use strict";	
								var table =  jQuery('#subject_list').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										//stateSave: true ,
								responsive: true,
								"order": [[ 2, "DESC" ]],
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
								$('#checkbox-select-all').on('click', function(){
									
									var rows = table.rows({ 'search': 'applied' }).nodes();
									$('input[type="checkbox"]', rows).prop('checked', this.checked);
								});
								$("body").on("click",".subject_csv_selected",function()
								{
									if ($('.smgt_sub_chk:checked').length == 0 )
									{
										alert(language_translate2.one_record_select_alert);
										return false;
									}		
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
									else{
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
									<table id="subject_list" class="display datatable" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
												<th><?php esc_attr_e('Subject Code','school-mgt');?></th>
												<th><?php esc_attr_e('Subject Name','school-mgt');?></th>
												<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>
												<th><?php esc_attr_e('Class Name','school-mgt');?></th>
												<th><?php esc_attr_e('Author Name','school-mgt');?></th>
												<th><?php esc_attr_e('Edition','school-mgt');?></th>
												<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$i=0;
											foreach ($retrieve_subjects as $retrieved_data)
											{           
												$teacher_group = array();
												$teacher_ids = mj_smgt_teacher_by_subject($retrieved_data);
												foreach($teacher_ids as $teacher_id)
												{
													$teacher_group[] = mj_smgt_get_teacher($teacher_id);
												}
												$teachers = implode(',',$teacher_group);

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
													<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->subid;?>"></td>
													<td class="user_image width_50px profile_image_prescription padding_left_0">
														<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->subid;?>" type="subject_view" >
															<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/wizard/wizard_subject.png"?>" alt="" class="massage_image center">
															</p>
														</a>
													</td>
													<td><?php
													if(!empty($retrieved_data->subject_code))
													{
														echo $retrieved_data->subject_code;
													}
													else
													{
														echo "N/A";
													}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Code','school-mgt');?>"></i></td>
													<td>
														<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->subid;?>" type="subject_view" >
															<?php echo $retrieved_data->sub_name;?>
														</a> 
														<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Name','school-mgt');?>"></i>
													</td>
													<td><?php if(!empty($teachers)){echo $teachers;}else{echo 'N/A';} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>
													<td><?php $cid=$retrieved_data->class_id;
														if(!empty($cid)){
															echo  $clasname=smgt_get_class_section_name_wise($cid,$retrieved_data->section_id);
														}
														else{
															echo 'N/A';
														}
													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
													<td><?php if(!empty($retrieved_data->author_name)){ echo $retrieved_data->author_name; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Author Name','school-mgt');?>"></i></td>
													<td><?php if(!empty($retrieved_data->edition)){ echo $retrieved_data->edition; }else{ echo "N/A"; }?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Edition','school-mgt');?>"></i></td>
													<td class="action">  
														<div class="smgt-user-dropdown">
															<ul class="" style="margin-bottom: 0px !important;">
																<li class="">
																	<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																	</a>
																	<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																		<li class="float_left_width_100 ">
																			<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->subid;?>" type="subject_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View','school-mgt');?></a>
																		</li>
																		<?php
																		
																		if($user_access_edit == '1')
																		{
																			?>
																			<li class="float_left_width_100 border_bottom_menu">
																				<a href="?page=smgt_Subject&tab=addsubject&action=edit&subject_id=<?php echo $retrieved_data->subid;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																			</li>

																			<?php 
																		} 
																		if($user_access_delete =='1')
																		{
																			?>
																			<li class="float_left_width_100 ">
																				<a href="?page=smgt_Subject&tab=Subject&action=delete&subject_id=<?php echo $retrieved_data->subid;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
								
									<div class="print-button pull-left padding_top_25px_res">
										<button class="btn-sms-color button_reload">
											<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
											<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
										</button>
										<?php 
										if($user_access_delete =='1')
										{ 
											?>
											<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
											<?php
										}
										?>
										<button data-toggle="tooltip" title="<?php esc_html_e('Export CSV','school-mgt');?>" name="subject_export_csv_selected" class="subject_csv_selected export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button>

										<button data-toggle="tooltip"  title="<?php esc_html_e('Import CSV','school-mgt');?>" type="button" class="view_import_subject_csv_popup export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></button>
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
							<div class="no_data_list_div row">
								<div class="offset-md-2 col-md-4">
									<a href="<?php echo admin_url().'admin.php?page=smgt_Subject&tab=addsubject';?>">
										<img class="col-md-12 width_100px rtl_float_remove" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
									</div>
								</div>
								<div class="col-md-4">
									<a data-toggle="tooltip"  name="import_csv" type="button" class="view_import_subject_csv_popup">
										<img src="<?php echo SMS_PLUGIN_URL."/assets/images/thumb_icon/Import_list.png" ?>" alt="">
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to import CSV.','school-mgt'); ?> </label>
									</div> 
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
				if($active_tab == 'addsubject')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/subject/add-newsubject.php';
				}
				?>
		
			</div>
		</div>
	</div>
</div>
<?php ?>
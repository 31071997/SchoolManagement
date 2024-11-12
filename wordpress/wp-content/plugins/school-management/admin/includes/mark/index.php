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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('manage_marks');
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
		if ('manage_marks' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access_edit=='0')
			{	
				mj_smgt_access_right_page_not_access_message_admin_side();
				die;
			}			
		}
		if ('manage_marks' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access_delete=='0')
			{	
				mj_smgt_access_right_page_not_access_message_admin_side();
				die;
			}	
		}
		if ('manage_marks' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
jQuery(document).ready(function($){
"use strict";	
$('#select_data').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
$('#marks_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1}); 
});
</script>
<?php 
// This is Class at admin side!!!!!!!!! 
$obj_marks = new Marks_Manage();
$exam_id = 0;
$class_id =0;
$subject_id = 0;
if(isset($_REQUEST['add_mark']))
{
	$user_id = $_REQUEST['add_mark'];	
	$marks = $_REQUEST['marks_'.$user_id];

	$comment = $_REQUEST['comment_'.$user_id];
	$current_date = date("Y-m-d H:i:s");
	
	$grade_id = $obj_marks->mj_smgt_get_grade_id($marks);
	if(!$grade_id)
	{
		$grade_id = 0;
	}
	$mark_data = array('exam_id'=>$_REQUEST['exam_id'],
		'class_id'=>$_REQUEST['class_id'],
		'subject_id'=>$_REQUEST['subject_id'],
		'marks'=>$marks,						
		'grade_id'=>$grade_id,
		'student_id'=>$user_id,
		'marks_comment'=>$comment,
		'created_date'=>$current_date,
		'created_by'=>get_current_user_id()
	);
	if(isset($_REQUEST['save_'.$user_id]))
	{

		$result=$obj_marks->mj_smgt_save_marks($mark_data);
		
		wp_redirect ( admin_url().'admin.php?page=smgt_result&tab=result&message=4');
		
	}
	else
	{	
		$mark_id =$_REQUEST['mark_id_'.$user_id];
		$mark_id=array('mark_id'=>$mark_id);
		$result=$obj_marks->mj_smgt_update_marks($mark_data,$mark_id);		
		if($result){
			wp_redirect ( admin_url().'admin.php?page=smgt_result&tab=result&message=3');
		}	
	}	
}
if(isset($_REQUEST['save_all_marks']))
{
	$result=0;
	$exam_id = $_REQUEST['exam_id'];
	$class_id = $_REQUEST['class_id'];
	$subject_id = $_REQUEST['subject_id'];
	if(isset($_REQUEST['section_id']) && $_REQUEST['section_id'] != "")
	{
		$exlude_id = mj_smgt_approve_student_list();
		$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['section_id'],
			'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
	}
	else
	{ 
		$exlude_id = mj_smgt_approve_student_list();
		$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
	} 		
		
		
	foreach ( $student as $user ) 
	{
		$mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($exam_id,$class_id,$subject_id,$user->ID);
		$button_text = esc_attr__('insert','school-mgt');
		$user_id = $user->ID;		
		$marks = $_REQUEST['marks_'.$user_id];		
		$comment = $_REQUEST['comment_'.$user_id];
		$current_date = date("Y-m-d H:i:s");			
		$grade_id = $obj_marks->mj_smgt_get_grade_id($marks);
		if(!$grade_id)
		{
			$grade_id = 0;
		}
		$mark_data = array(
			'exam_id'=>$_REQUEST['exam_id'],
			'class_id'=>$_REQUEST['class_id'],
			'subject_id'=>$_REQUEST['subject_id'],
			'marks'=>$marks,
			'grade_id'=>$grade_id,
			'student_id'=>$user_id,
			'marks_comment'=>$comment,
			'created_date'=>$current_date,
			'created_by'=>get_current_user_id()
		);

		if($mark_detail)
		{
			$mark_id =$_REQUEST['mark_id_'.$user_id];
			$mark_id=array('mark_id'=>$mark_id);
			$result=$obj_marks->mj_smgt_update_marks($mark_data,$mark_id);			
			if($result){
				wp_redirect ( admin_url().'admin.php?page=smgt_result&tab=result&message=3');
			}
		}
		else
		{		
			global $wpdb;
			$table_name = $wpdb->prefix . 'marks';
			$result=$wpdb->insert( $table_name, $mark_data);
			if($result){
				wp_redirect ( admin_url().'admin.php?page=smgt_result&tab=result&message=4');
			}
		}		
	}

	
}
if(isset($_POST['export_marks']))
{
	$exam_id = $_REQUEST['exam_id'];
	$class_id = $_REQUEST['class_id'];
	$subject_list = $obj_marks->mj_smgt_student_subject($class_id);
	
	if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != 0)
	{
		$exlude_id = mj_smgt_approve_student_list();
		$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
			'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
	}
	else
	{ 
		$exlude_id = mj_smgt_approve_student_list();
		$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
	} 		
 
	$header = array();
	$marks = array();
	$header[] = 'Roll No';
	$header[] = 'Student Name';
	$header[] = 'Class';
	$subject_array = array();
	if(!empty($subject_list))
	{
		foreach($subject_list as $result)
		{
			$header[]=$result->sub_name;
			$subject_array[] = $result->subid;
		}
	}
	$header[]= 'Total';
	$filename='Reports/export_marks.csv';
	$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
	fputcsv($fh, $header);
	foreach($student as $user)
	{
		$row = array();
		$row[] =  get_user_meta($user->ID, 'roll_id',true);
		$row[] = mj_smgt_get_user_name_byid($user->ID);
		$row[] = mj_smgt_get_class_name($class_id);
		$total = 0;
		if(!empty($subject_array))
		{
			$total = 0;
			foreach($subject_array as $sub_id)
			{			
				$marks = $obj_marks->mj_smgt_export_get_subject_mark($exam_id,$class_id,$user->ID,$sub_id);
				if($marks)
				{
					$row[] =  $marks;
					$total += $marks;
				}
				else	
					$row[] = 0;
			}
			$row[] = $total;
		}
		fputcsv($fh, $row);		
	}
	fclose($fh);	
		//download csv file.
		ob_clean();
		$file=SMS_PLUGIN_DIR.'/admin/Reports/export_marks.csv';//file location
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
		//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);		
	exit;	
}

$active_tab = isset($_GET['tab'])?$_GET['tab']:'result';
if(isset($_REQUEST['exam_id']))
	$exam_id =$_REQUEST['exam_id'];

if(isset($_REQUEST['class_id']))
	$class_id =$_REQUEST['class_id'];

if(isset($_REQUEST['subject_id']))
	$subject_id =$_REQUEST['subject_id'];
?>
<div class="">	
	<div  id="" class="marks_list list_padding_5px">
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('This file type is not allowed, please upload CSV file.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('File size limit : 2 MB','school-mgt');
				break;		
			case '3':
				$message_string = esc_attr__('Marks Updated Successfully','school-mgt');
				break;		
			case '4':
				$message_string = esc_attr__('Marks Added Successfully','school-mgt');
				break;	
			case '5':
				$message_string = esc_attr__('Please enter CSV File.','school-mgt');
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
		<div class="panel-white">
			<div class="panel-body">  
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
					<?php
					if($user_access_add == '1')
					{
						?>
						<li class="<?php if($active_tab=='result'){?>active<?php }?>">			
							<a href="?page=smgt_result&tab=result" class="padding_left_0 tab <?php echo $active_tab == 'result' ? 'active' : ''; ?>">
							<?php esc_html_e('Manage Marks', 'school-mgt'); ?></a> 
						</li>
						<?php
					}
					if($user_access_add == '1')
					{
						?>
						<li class="<?php if($active_tab=='multiple_subject_marks'){?>active<?php }?>">			
							<a href="?page=smgt_result&tab=multiple_subject_marks" class="padding_left_0 tab <?php echo $active_tab == 'multiple_subject_marks' ? 'active' : ''; ?>">
							<?php esc_html_e('Add Multiple Subject Marks', 'school-mgt'); ?></a> 
						</li>
						<?php
					}
					?>
					<li class="<?php if($active_tab=='export_marks'){?>active<?php }?>">
						<a href="?page=smgt_result&tab=export_marks" class="padding_left_0 tab <?php echo $active_tab == 'export_marks' ? 'active' : ''; ?>">
						<?php esc_html_e('Export Marks', 'school-mgt'); ?></a> 
					</li>  
				
				</ul> 
			
				<?php
				$tablename="marks";
				if($active_tab == 'result')
				{
					?>	 
					<script type="text/javascript">
					$(document).ready(function() {
						jQuery('#select_data').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
					} );
					</script>
					<div class="popup-bg">
						<div class="overlay-content admission_popup">
							<div class="modal-content">
								<div class="category_list">
								</div>     
							</div>
						</div>     
					</div>
					<?php
					if(get_option('smgt_enable_video_popup_show') == 'yes')
					{
					?>
					<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/CZQzPhCPIr4?si=Hg16bHUL2gzi9xLA";?>" title="Marksheet Generation">
						<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
					</a>
					<?php
					}
					?>
					<div class="panel-body margin_top_20px padding_top_25px_res"> 
						<form method="post" id="select_data">  
							<div class="form-body user_form">
								<div class="row">
									<div class="col-md-6 input">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
										<select name="class_id"  id="class_list" class="form-control class_id_exam validate[required] text-input">
											<option value=" "><?php esc_attr_e('Select Class','school-mgt');?></option>
											<?php
												foreach(mj_smgt_get_allclass() as $classdata)
												{
												?>
													<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
											<?php }?>
										</select>                     
									</div>
									<div class="col-md-6 input">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Section','school-mgt');?></label>
										<?php
										$class_section="";
										if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
										<select name="class_section" class="form-control section_id_exam" id="class_section">
												<option value=""><?php esc_attr_e('Select Section','school-mgt');?></option>
											<?php if(isset($_REQUEST['class_section'])){
													$class_section=$_REQUEST['class_section'];
													foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
													{  ?>
													<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
												<?php }
												}?>
										</select>                   
									</div>
									<div class="col-md-6 input">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
										<select name="exam_id" class="form-control exam_list validate[required] text-input">
											<?php
											if(isset($_POST['exam_id']))
											{
												$exam_data=mj_smgt_get_all_exam_by_class_id_all($_POST['class_id']);
												if(!empty($exam_data))
												{
													foreach ($exam_data as $retrieved_data)
													{
													?>
														<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($_POST['exam_id'], $retrieved_data->exam_id);  ?>><?php echo $retrieved_data->exam_name;?></option>
													<?php
													}
												}
												?>
											<?php
											}
											else
											{?>
												<option value=""><?php esc_attr_e('Select Exam','school-mgt');?></option>
												<?php
											}
											?>
										</select>                 
									</div>
									<div class="col-md-6 input error_msg_left_margin">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Subject','school-mgt');?><span class="require-field">*</span></label>
										<select name="subject_id" id="subject_list" class="form-control validate[required] text-input">
											<?php
											if(isset($_POST['subject_id']))
											{
												$subject=mj_smgt_get_subject($_POST['subject_id']);
												$subject = mj_smgt_get_subject_by_classid($_POST['class_id']);
												if(!empty($subject))
												{
													foreach ($subject as $ubject_data)
													{
													?>
														<option value="<?php echo $ubject_data->subid ;?>" <?php selected($_POST['subject_id'], $ubject_data->subid);  ?>><?php echo $ubject_data->sub_name;?></option>
													<?php
													}
												}
												?>
											<?php
											}
											else
											{?>
												<option value=""><?php esc_attr_e('Select Subject','school-mgt');?></option>
												<?php
											}
											?>
										</select>                
									</div>
									<div class="col-md-6">
										<input type="submit" value="<?php esc_attr_e('Manage Marks','school-mgt');?>" name="manage_mark"  class="btn btn-info save_btn"/>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="clearfix"> </div>
				<?php 
				if(isset($_REQUEST['manage_mark']) || isset($_REQUEST['add_mark']) || isset($_REQUEST['save_all_marks']) || isset($_REQUEST['upload_csv_file']))
				{
					$class_id =$_REQUEST['class_id'];
					$subject_id=$_REQUEST['subject_id'];
					$exam_id = $_REQUEST['exam_id'];
					$error_message = "";
					
					if($subject_id == " ")
						$error_message= esc_attr__('Select Subject ID','school-mgt');
					if($class_id == " ")
						$error_message= esc_attr__('Select Class ID','school-mgt');
					if($exam_id == " ")
						$error_message= esc_attr__('Select Exam ID','school-mgt');
					if($error_message != "")
					{
						echo $error_message;
						exit;
					}
					
					if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != ""){
					$exlude_id = mj_smgt_approve_student_list();
					$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
									'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
					}
					else
					{ 
						$exlude_id = mj_smgt_approve_student_list();
						$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
					} 		
				?>
				<script>
					jQuery(document).ready(function($)
					{
						$('.file_validation ').change(function ()
						{
							var val = $(this).val().toLowerCase();
							var regex = new RegExp("(.*?)\.(csv)$");
							if(!(regex.test(val)))
							{
								$(this).val('');
								alert("<?php _e('Only CSV formate are allowed.','school-mgt');?>");
							}
						});

					});
				</script>
	 			 <div class="panel-body clearfix margin_top_20px">
    				 <form method="post" class="form-inline" id="marks_form" enctype="multipart/form-data">  
     					<input type="hidden" name="exam_id" value="<?php echo $exam_id;?>" />
      					<input type="hidden" name="subject_id" value="<?php echo $subject_id;?>" />
     					<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
      					<input type="hidden" name="section_id" value="<?php echo $_REQUEST['class_section'];?>" />
      					<input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />
						<?php
						if(!empty($student))
						{
							?>
							<div class="form-body user_form margin_top_20px padding_top_25px_res">
								<div class="row">	
									<div class="col-md-6">	
										<div class="form-group input">
											<div class="col-md-12 form-control res_rtl_height_50px">	
												<label for="" class="custom-control-label custom-top-label ml-2 margin_left_30px"><?php _e('Select CSV file','school-mgt');?></label>
												<div class="col-sm-12 csv_file_check">
													<input type="file" name="csv_file " id="csv_file" class="d-inline file_validation" />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<input type="submit" name="upload_csv_file" value="<?php esc_attr_e('Fill data from CSV File','school-mgt');?>" class="fill_data btn save_btn_1 margin_top_20px" /> 
							<label for="" class="margin_top_20px padding_top_25px_res whitespace_initial"><?php esc_attr_e('CSV file Must have headers as follows','school-mgt');?>: <?php esc_attr_e('roll_no, name, marks, comment','school-mgt');?></label>
							<br /><p></p>
						
							<div class="table-responsive">
								<table class="table col-md-12">
									<tr>
										<th class="multiple_subject_mark"><?php esc_attr_e('Roll No.','school-mgt');?></th>
										<th class="multiple_subject_mark"><?php esc_attr_e('Name','school-mgt');?></th>
										<th class="multiple_subject_mark"><?php esc_attr_e('Mark Obtained(out of 100)','school-mgt');?></th>
									
										<th class="multiple_subject_mark"><?php esc_attr_e('Comment','school-mgt');?></th>
										
										<th>&nbsp;</th>
									</tr>
									
									<?php

									if(isset($_REQUEST['upload_csv_file']))
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
											if($file_size > 2097152)
											{
												$errors[]='File size limit : 2 MB';
												$msg = "2";
											}	
											if(empty($file_name) && empty($file_size))
											{
												$errors[]="Please enter CSV File.";
												$msg = "5";
											}			
											if(empty($errors)==true)
											{
												$rows = array_map('str_getcsv', file($file_tmp));					
												$header = array_map('strtolower',array_shift($rows));
												$csv = array();
												foreach ($rows as $row) 
												{
													$csv[] = array_combine($header, $row);						
												}
											}
											else
											{
												wp_redirect(admin_url()."admin.php?page=smgt_result&message={$msg}");
											}
										}
									}
									
									function mj_smgt_get_csv_rowid($array,$roll_no)
									{
											
											if(!empty($array))
											{
												$marks_array = array();
												foreach($array as $key => $value)
												{
													
													if($roll_no == $value['roll_no'])
													{
														
														return $key;
													}
												}
												
												return null;
											}	
									}
									
									
									function mj_smgt_arraymap($element)
									{
										return $element['roll_no'];
									}
									
									if(!function_exists("array_column")){
										function array_column($array,$column_name){
											
											return array_map('smgt_arraymap', $array,$column_name);
										}
									}
									$i=0;
									foreach ( $student as $user ) 
									{
										$mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($exam_id,$class_id,$subject_id,$user->ID);	
										$button_text = esc_attr__('Add Mark','school-mgt');		
										if(isset($csv)){
											$key =mj_smgt_get_csv_rowid($csv,$user->roll_id);
										}
										
										if($mark_detail)
										{
											$mark_id=$mark_detail->mark_id;
											$marks=$mark_detail->marks;
											
											$marks_comment=$mark_detail->marks_comment;
											$button_text = esc_attr__('Update','school-mgt');
											$action = "edit";
										}
										else
										{
											$marks=0;
											$attendance=0;
											$marks_comment="";
											$action = "save";
											$mark_id="0";
										}
										
										echo '<tr>';
											echo '<td><span '.(isset($csv) && !(isset($key))? 'class="">': '>' ).$user->roll_id. '</span></td>';
											echo '<td><span>' .$user->first_name.' '.$user->last_name. '</span></td>';
											echo '<td id="position_relative">';
											echo '<div class="form-group input margin_bottom_0px">';
											echo '<div class="col-md-12 form-control">';
											echo '<input type="text" name="marks_'.$user->ID.'" value="'.(isset($key)? $csv[$key]['marks'] : $marks).'" class="form-control validate[required,custom[phone_number],minSize[0],maxSize[5]] text-input">';
											echo '</div>';
											echo '</div>';
											echo '</td>';
											
											echo '<td>';
											echo '<div class="form-group input margin_bottom_0px ">';
											echo '<div class="col-md-12 form-control">';
											echo '<input type="text" name="comment_'.$user->ID.'" placeholder='.esc_attr__('Comment','school-mgt') .' value="'.(isset($key)? $csv[$key]['comment'] : $marks_comment).'" maxlength="50"  class="form-control ">';
											echo '</div>';
											echo '</div>';
											echo '</td>';
											echo '<td>';
											echo '<input type="hidden" name="'.$action.'_'.$user->ID.'" value="'.$marks_comment.'" class="form-control">';
											echo '<input type="hidden" name="mark_id_'.$user->ID.'" value="'.$mark_id.'">';
											echo '<button type="submit" name="add_mark" value="'.$user->ID.'" class="btn-success save_btn p-2 font_size_12px_res">'.$button_text.'</button>';			
											echo '</td>';
										echo '</tr>';
									}
									?>
								</table>
							</div>
					
							<div class="col-sm-6 margin_top_15px">
								<input type="submit" class="btn btn-success save_btn" name="save_all_marks" value="<?php esc_attr_e('Update All Marks','school-mgt');?>">
							</div>
							<?php
						}
						else{
							?>
							<div class="">
								<h4><?php echo esc_attr__('No Student Available In This Class.','school-mgt'); ?></h3>
							</div>
							<?php 
						}
						?>
   				</form>
   			</div>
    		<?php
		}	 
	}
	if($active_tab == 'export_marks')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/mark/export_marks.php';
	}
	if($active_tab == 'multiple_subject_marks')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/mark/add_multiple_subject_marks.php';
	}
	?>
</div>
</div>
</div>  
</div>
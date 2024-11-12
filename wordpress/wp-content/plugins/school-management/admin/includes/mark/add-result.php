<?php
if(isset($_POST['save_exam']))
{
	
	$created_date = date("Y-m-d H:i:s");
	$examdata=array('exam_name'=>mj_smgt_strip_tags_and_stripslashes($_POST['exam_name']),
					'exam_start_date'=>$_POST['exam_start_date'],
					'exam_end_date'=>$_POST['exam_end_date'],
					'exam_comment'=>mj_smgt_strip_tags_and_stripslashes($_POST['exam_comment']),					
					'exam_creater_id'=>get_current_user_id(),
					'created_date'=>$created_date
					
	);
	//table name without prefix
	$tablename="exam";
	
	if($_REQUEST['action']=='edit')
	{
		$grade_id=array('exam_id'=>$_REQUEST['exam_id']);
		$modified_date_date = date("Y-m-d H:i:s");
		$examdata['modified_date']=$modified_date_date;
		mj_smgt_update_record($tablename,$examdata,$grade_id);
		$message= esc_attr__('Update Exam Successfully','school-mgt');
	}
	else
	{
		$reult=mj_smgt_insert_record($tablename,$examdata);
		
			$message= esc_attr__('Add Exam Successfully','school-mgt');
	}
	
}
?>	
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/marks.js'; ?>" ></script>
<div class="add_class">
	<h2>
    	<?php  $edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
				echo esc_html( esc_attr__( 'Edit Exam', 'school-mgt') );
				$edit=1;
				$exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);
		}
		else
		{
					echo esc_html( esc_attr__( 'Add New Exam', 'school-mgt') );
		} ?>
	</h2>
	<?php
	if(isset($message))
	{
		echo '<div id="message" class="updated below-h2"><p>'.$message.'</p></div>';
		?>
       	<form name="class_form" action="" method="post" id="marks_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<table class="form-table">	
				<tr>
					<th>
						<label><?php esc_attr_e('Exam Name','school-mgt');?> <span class="require-field">*</span></label></th>
					<td>
						<input type="text" name="exam_name"  maxlength="50" class="regular-text validate[required,custom[popup_category_validation]]" value="<?php if($edit){ echo $exam_data->exam_name;}?>"/> 
					</td>
				</tr>
				<tr>	
					<th >
						<label><?php esc_attr_e('Exam Start Date','school-mgt');?>  </label></th>
					<td>
						<input type="date" name="exam_start_date" class="validate[required]" value="<?php if($edit){ echo $exam_data->exam_start_date;}?>" readonly />
					</td>
				</tr>
				<tr>	
					<th >
						<label><?php esc_attr_e('Exam End Date','school-mgt');?>  </label></th>
					<td>
						<input type="date" name="exam_end_date" class="validate[required]" value="<?php if($edit){ echo $exam_data->exam_end_date;}?>" readonly />
					</td>
				</tr>
				<tr>
					<th >
						<label><?php esc_attr_e('Exam Comment','school-mgt');?>  </label></th>
					<td>
					<textarea name="exam_comment" class="validate[custom[address_description_validation]]" maxlength="150"><?php if($edit){ echo $exam_data->exam_comment;}?></textarea>
					</td>
				</tr>             
				<tr>
					<th ></th>
					<td><input type="submit" value="<?php if($edit){ esc_attr_e('Save Exam','school-mgt'); }else{ esc_attr_e('Add Exam','school-mgt');}?>" name="save_exam"/></td>
				</tr>
			</table>      	
        </form>
		<?php
	}
	?>
</div>
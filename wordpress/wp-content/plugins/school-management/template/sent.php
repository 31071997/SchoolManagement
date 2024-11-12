<?php 
if($school_obj->role == 'student')
{
	$subjects = $school_obj->subject;	
}
else
$subjects = mj_smgt_get_all_data('subject');
?>
 <ul class="nav nav-tabs flex-nowrap" role="tablist">
      <li class="active">
          <a href="#examlist" role="tab" data-toggle="tab">
              <icon class="fa fa-home"></icon> <?php esc_attr_e('Subject','school-mgt');?>
          </a>
      </li>
      <?php if($school_obj->role == 'teacher'){?>
      <li><a href="#add_subject" role="tab" data-toggle="tab">
          <i class="fa fa-user"></i><?php esc_attr_e('Add Subject','school-mgt');?>
          </a>
      </li>
     <?php }?>
    </ul>
    
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane fade active in" id="examlist">
          <h2><?php echo esc_html( esc_attr__( 'Subject list', 'school-mgt' ) );?></h2>       
        <table id="subject_list" class="table table-bordered display dataTable" cellspacing="0" width="100%">
        	<thead>
				<tr>                
					 <th><?php esc_attr_e('Class','school-mgt')?></th>
					<th><?php esc_attr_e('Subject Name','school-mgt')?></th>
					<th><?php esc_attr_e('Teacher Name','school-mgt')?></th>                               
				</tr>
			</thead>
 
			<tfoot>
				<tr>
				   <th><?php esc_attr_e('Class','school-mgt')?></th>
					<th><?php esc_attr_e('Subject Name','school-mgt')?></th>
					<th><?php esc_attr_e('Teacher Name','school-mgt')?></th>        
				</tr>
			</tfoot>
 
        <tbody>
          <?php 
          if($school_obj->role !='parent')
          {
		 	foreach ($subjects as $retrieved_data){ 
			
		 ?>
            <tr>
                <td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?></td>
                <td><?php echo $retrieved_data->sub_name;?></td>
                <td><?php echo mj_smgt_get_user_name_byid($retrieved_data->teacher_id);?></td>              
               
            </tr> 
            <?php } 
          }
          else 
          {
          	$chid_array =$school_obj->child_list;
          	foreach ($chid_array as $child_id)
          	{
          		$class_info = $school_obj->mj_smgt_get_user_class_id($child_id);
          		$subjects = $school_obj->mj_smgt_subject_list($class_info->class_id);
          	foreach ($subjects as $retrieved_data){
          	?>
          	    <tr>
          	        <td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?></td>
          	        <td><?php echo $retrieved_data->sub_name;?></td>
          	        <td><?php echo mj_smgt_get_user_name_byid($retrieved_data->teacher_id);?></td>              
          	    </tr> 
          	 <?php } }
          }
        ?>     
        </tbody>        
        </table>          
      </div>
      <div class="tab-pane fade" id="add_subject">
        <?php
		if(isset($_POST['subject']))
		{
			if(isset($_POST['subject_syllabus']))
			{
				$sullabus='syllabus.pdf';
			}
			else
			{
				$sullabus=$_POST['old_syllabus'];
			}
			$subjects=array('sub_name'=>mj_smgt_address_description_validation($_POST['subject_name']),
				'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['subject_class']),
				'teacher_id'=>$_POST['subject_teacher'],
				'edition'=>mj_smgt_address_description_validation($_POST['subject_edition']),
				'author_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['subject_author']),	
				'syllabus'=>$sullabus
			);
			$tablename="subject";
			if($_REQUEST['action']=='edit')
			{
				$subid=array('subid'=>$_REQUEST['subject_id']);
				mj_smgt_update_record($tablename,$subjects,$subid);
			}
			else
			{
				mj_smgt_insert_record($tablename,$subjects);
			}				
		}
		?>
		<h2>
			<?php
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{	
				$edit=1;
				echo esc_html( esc_attr__( 'Edit Subject', 'school-mgt') );
				$subject=mj_smgt_get_subject($_REQUEST['subject_id']);
			}
			else
			{
				echo esc_html( esc_attr__( 'Add New Subject', 'school-mgt') );
			}
			?>
		</h2>
		<form name="student_form" action="" method="post">
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<table class="form-table">	
				<tr class="user-user-login-wrap">
					<th><label><?php esc_attr_e('Subject Name','school-mgt');?> </label></th>
					<td>
						<input type="text" name="subject_name"  class="regular-text ,custom[address_description_validation]" maxlength="50" value="<?php if($edit){ echo $subject->sub_name;}?>"/> 
					</td>
				</tr>
				<tr class="user-user-login-wrap">
					<th><label><?php esc_attr_e('Class','school-mgt');?>  </label></th>
					<td>
						<?php if($edit){ $classval=$subject->class_id; }else{$classval='';}?>
							<select name="subject_class">
								<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
								<?php
									foreach(mj_smgt_get_allclass() as $classdata)
									{ ?>
									 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
								<?php }?>
							</select>
					</td>
				</tr>
				<tr class="user-user-login-wrap">
					<th ><label><?php esc_attr_e('Teacher','school-mgt');?>  </label></th>
								<td>
									<?php if($edit){ $teachval=$subject->teacher_id; }else{$teachval='';}?>
									<select name="subject_teacher">
										<option value=""><?php esc_attr_e('Select Teacher','school-mgt');?> </option>
									   <?php 
											foreach(mj_smgt_get_usersdata('teacher') as $teacherdata)
											{ ?>
											 <option value="<?php echo $teacherdata->ID;?>" <?php selected($teachval, $teacherdata->ID);  ?>><?php echo $teacherdata->display_name;?></option>
										<?php }?>
									</select>
								</td>
							</tr>
							<tr class="user-user-login-wrap">
								<th >
									<label><?php esc_attr_e('Edition','school-mgt');?>  </label></th>
								<td>
									 <input type="text" name="subject_edition"  class="regular-text validate[custom[address_description_validation]]"  maxlength="50" value="<?php if($edit){ echo $subject->edition;}?>"/> 
								</td>
							</tr>
							<tr class="user-user-login-wrap">
								<th >
									<label><?php esc_attr_e('Author Name','school-mgt');?>  </label></th>
								<td>
									 <input type="text" name="subject_author"  class="regular-text validate[custom[onlyLetter_specialcharacter]]" maxlength="100" value="<?php if($edit){ echo $subject->author_name;}?>"/> 
								</td>
							</tr>
							 <tr class="user-user-login-wrap">
								<th >
									<label><?php esc_attr_e('Syllabus','school-mgt');?>  </label></th>
								<td>
									 <input type="file" name="subject_syllabus" /> 
									 <input type="hidden" value="<?php if($edit){ $syllabusval=$subject->syllabus; }else{$syllabusval='';}?>" name="old_syllabus" />
								</td>
							</tr>
							<tr>
								<th ></th>
								<td><input type="submit" value="<?php if($edit){ esc_attr_e('Save Subject','school-mgt'); }else{ esc_attr_e('Add Subject','school-mgt');}?>" name="subject"/></td>
							</tr>						
					</table> 
					</form>
				</div>     
</div>
<?php ?>
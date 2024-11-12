<script type="text/javascript">
jQuery(document).ready(function($){
"use strict";	
$('#select_data').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
});
</script>
<div class="panel-body margin_top_20px padding_top_25px_res"> <!--------- penal body ------->
    <form method="post" id="select_data">  
		<div class="form-body user_form"><!--------- Form body ------->
			<div class="row">
				<div class="col-md-3 input">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
					<select name="class_id"  id="class_list" class="line_height_30px form-control validate[required] class_id_exam text-input">
						<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
						<?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{
							?>
							<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
							<?php
						} ?>
					</select>                  
				</div>
				<div class="col-md-3 input">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Section','school-mgt');?></label>
					<?php
					$class_section="";
					if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="line_height_30px form-control section_id_exam" id="class_section">
							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								$class_section=$_REQUEST['class_section'];
								foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php }
							}?>
					</select>                
				</div>
				<div class="col-md-3 input">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
					<select name="exam_id" class="line_height_30px form-control validate[required] text-input exam_list">
						<option value=""><?php esc_attr_e('Select Exam','school-mgt');?></option>
					</select>               
				</div>
				<?php	
				$school_obj = new School_Management ( get_current_user_id () );
				if($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff')
				{
					if($user_access['add'] == '1')
					{
						$access = 1;
					}
					else
					{
						$access = 0;
					}
				}
				else
				{
					$access = 1;
				}		
				if($access == 1)
				{
					?>
					<div class="col-md-3">
						<input type="submit" value="<?php esc_attr_e('Export Marks','school-mgt');?>" name="export_marks"  class="btn btn-info save_btn"/>
					</div>
					<?php
				}
				?>
			</div>
		</div>
   	</form>
</div>	<!--------- penal body ------->	
<?php

?>
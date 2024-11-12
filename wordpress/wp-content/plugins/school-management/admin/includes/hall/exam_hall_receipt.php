<div class="panel-body margin_top_20px padding_top_25px_res"><!-------- Penal Body -------->
	<form name="exam_form" action="" class="hall_recipt" method="post" class="form-horizontal" enctype="multipart/form-data" id="receipt_form">
		<div class="form-body"><!-------- Form Body -------->
			<div class="row">
				<div class="col-md-9 input">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="required">*</span></label>
					<?php
					$tablename="exam"; 
					$retrieve_class = mj_smgt_get_all_data($tablename);
					$exam_id="";
					if(isset($_REQUEST['exam_id']))
					{
						$exam_id=$_REQUEST['exam_id']; 
					}
					?>
					<select name="exam_id" class="form-control validate[required] exam_hall_receipt" id="exam_id">
						<option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>
						<?php
						foreach($retrieve_class as $retrieved_data)
						{
							$cid=$retrieved_data->class_id;
							$clasname=mj_smgt_get_class_name($cid);
							if($retrieved_data->section_id!=0)
							{
								$section_name=mj_smgt_get_section_name($retrieved_data->section_id); 
							}
							else
							{
								$section_name=esc_attr__('No Section', 'school-mgt');
							}
						?>
							<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name.'('.smgt_get_class_section_name_wise($cid,$retrieved_data->section_id).')';?></option>
						<?php	
						}
						?>
					</select>                  
				</div>
				<div class="form-group col-md-3">
					<input type="button" value="<?php esc_attr_e('Search Exam','school-mgt');?>" name="search_exam" id="search_exam" class="btn btn-info search_exam save_btn"/>
				</div>
			</div>
		</div><!-------- Form Body -------->
	</form>
	
	<div class="col-md-12 col-sm-12 col-xs-12 rtl_padding_0px">
		<div class="exam_hall_receipt_div"></div>
	</div>
</div> <!-------- Penal Body -------->
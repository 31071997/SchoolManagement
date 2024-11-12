<?php $obj_lib = new Smgtlibrary();	?>
<?php
	$issuebook_id=0;
	if(isset($_REQUEST['issuebook_id']))
		$issuebook_id=$_REQUEST['issuebook_id'];
		$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result=$obj_lib->mj_smgt_get_single_issuebooks($issuebook_id);
	}
	?>
       
<div class="panel-body"><!--panel-body -->
    <form name="book_form" action="" method="post" class="form-horizontal" id="book_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="issue_id" value="<?php echo $issuebook_id;?>">
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Issue Book Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">
					<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
					<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
					<select name="class_id" id="class_list" class="form-control validate[required] max_width_100">
						<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
							<?php
							foreach(mj_smgt_get_allclass() as $classdata)
							{ ?>
								<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
							<?php }?>
					</select>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">
					<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
					<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
					<select name="class_section" class="form-control max_width_100" id="class_section">
						<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php
						if($edit){
							foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)
							{  ?>
								<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php } 
						}?>
					</select>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">
					<label class="ml-1 custom-top-label top" for="student_list"><?php esc_attr_e('Student','school-mgt');?><span class="require-field">*</span></label>
					<select name="student_id" id="student_list" class="form-control validate[required] max_width_100">
						<?php if(isset($result->student_id)){ 
							$student = get_userdata($result->student_id);
						?>
						<option value="<?php echo $result->student_id;?>" ><?php echo mj_smgt_student_display_name_with_roll($result->student_id);?></option>
						<?php }
						else
						{?>
							<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
						<?php } ?>
					</select>
				</div>		
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="issue_date" class="datepicker form-control validate[required] text-input" type="text" name="issue_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->issue_date);}else{echo mj_smgt_getdate_in_input_box(date('Y-m-d'));}?>" readonly>
							<label class="" for="issue_date"><?php esc_attr_e('Issue Date','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 input">
					<label class="ml-1 custom-top-label top" for="period"><?php esc_attr_e('Period','school-mgt');?><span class="require-field">*</span></label>
					<select name="period_id" id="category_data" class="form-control issue_period validate[required] max_width_100 period_type">
						<option value = ""><?php esc_attr_e('Select Period','school-mgt');?></option>
						<?php 
						$period_id='';
						if($edit)
							$period_id = $result->period;
							$category_data = $obj_lib->mj_smgt_get_periodlist();
					
						if(!empty($category_data))
						{
							foreach ($category_data as $retrieved_data)
							{
								echo '<option value="'.$retrieved_data->ID.'" '.selected($period_id,$retrieved_data->ID).'>'.$retrieved_data->post_title.' '. esc_attr__('Days','school-mgt').'</option>';
							}
						}
						?>
					</select>
				</div>
				<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 mb-3">
					<button id="addremove_cat" class="rtl_margin_top_15px add_btn sibling_add_remove" model="period_type"><?php esc_attr_e('Add','school-mgt');?></button>
				</div>

				<?php wp_nonce_field( 'save_issuebook_admin_nonce' ); ?>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="return_date" class="form-control validate[required] date_picker text-input" type="text" name="return_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->end_date);}?>" readonly>
							<label class="active date_label" for="return_date"><?php esc_attr_e('Return Date','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input error_msg_left_margin rtl_margin_0px">
					<label class="ml-1 custom-top-label top" for="category_data validate[required]"><?php esc_attr_e('Book Category','school-mgt');?><span class="require-field">*</span></label>
					<select name="bookcat_id" id="bookcat_list" class="form-control max_width_100">
						<option value = ""><?php esc_attr_e('Select Category','school-mgt');?></option>
						
						   <?php
						    $book_cat='';
							if($edit)
							{
								$book_cat = $result->cat_id;
							}
							$category_data = $obj_lib->mj_smgt_get_bookcat();
						
							if(!empty($category_data))
							{
								foreach ($category_data as $retrieved_data)
								{
									echo '<option value="'.$retrieved_data->ID.'" '.selected($book_cat,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
								}
							}
						?>
					</select>
				</div>
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 error_msg_top_margin">
					<div class="col-sm-12 multiselect_validation_class smgt_multiple_select rtl_padding_left_right_0px">
						<?php if($edit)
							{ 
							$book_id=$result->book_id;
							$books_data=$obj_lib->mj_smgt_get_all_books();
							}
							else
							{
								$book_id=0;
								$books_data=$obj_lib->mj_smgt_get_all_books();
							}
						
						?>
						<select name="book_id[]" id="book_list1" multiple="multiple" class="form-control validate[required]">
							<?php 
							if($edit)
							{ 
								
								foreach($books_data as $book)
								{?>
									<option value="<?php echo $book->id;?>" <?php selected($book_id,$book->id);?>><?php echo stripslashes($book->book_name);?></option>
								<?php 
								} 
							}
							?>
						</select>		
					</div>
				</div>
				
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position">
							<div class="row padding_radio">
								<div class="">
									<label class="custom-top-label label_position_rtl" for="enable"><?php esc_attr_e('Send Mail','school-mgt');?></label>
									<input id="chk_sms_sent1" type="checkbox" class="check_box_input_margin" <?php $smgt_issue_book_mail_service_enable = 0;if($smgt_issue_book_mail_service_enable) echo "checked";?> value="1" name="smgt_issue_book_mail_service_enable"> <?php esc_attr_e('Send Mail','school-mgt');?>
								</div>												
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>    
		<div class="form-body user_form mt-3">
			<div class="row">
				<div class="col-sm-6">      
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Issue Book','school-mgt'); }else{ esc_attr_e('Issue Book','school-mgt');}?>" name="save_issue_book" class="save_btn btn btn-success book_for_alert rtl_margin_0px" />
				</div>    
			</div>
		</div>     
    </form>
</div><!--panel-body -->
<?php  
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	 
	$edit=1;
	$grade_data= mj_smgt_get_grade_by_id($_REQUEST['grade_id']);
}
?>
<div class="panel-body padding_top_25px_res"><!-------- penal body -------->
    <form name="grade_form" action="" method="post" class="form-horizontal" id="grade_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="form-body user_form">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="grade_name" class="form-control validate[required,custom[address_description_validation]]" type="text" value="<?php if($edit){ echo $grade_data->grade_name;}?>" maxlength="50" name="grade_name">
							<label for="userinput1" class=""><?php esc_html_e('Grade Name','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="grade_point" class="form-control validate[required,max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->grade_point;}?>" name="grade_point" step="any">
							<label for="userinput1" class=""><?php esc_html_e('Grade Point','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<?php wp_nonce_field( 'save_grade_admin_nonce' ); ?>
				<div class="col-md-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="mark_upto" class="form-control validate[required,max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->mark_upto;}?>" name="mark_upto" step="any"> 
							<label for="userinput1" class=""><?php esc_html_e('Mark From','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="mark_from" class="form-control validate[required,max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->mark_from;}?>" name="mark_from" step="any">
							<label for="userinput1" class=""><?php esc_html_e('Mark Upto','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				
				<div class="col-md-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="grade_comment" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150" id="grade_comment"><?php if($edit){ echo $grade_data->grade_comment;}?></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active"><?php esc_attr_e('Comment','school-mgt');?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Grade','school-mgt'); }else{ esc_attr_e('Add Grade','school-mgt');}?>" name="save_grade" class="btn btn-success save_btn" />
				</div>
			</div>
		</div>
    </form>
</div><!-------- penal body -------->
<?php
?>
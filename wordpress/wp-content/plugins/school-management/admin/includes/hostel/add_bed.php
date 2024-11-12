<?php
$obj_hostel=new smgt_hostel;
 ?>
<!--Group POP up code -->
<div class="popup-bg">
	<div class="overlay-content admission_popup">
		<div class="modal-content">
			<div class="category_list">
			</div>     
		</div>
	</div>     
</div>
<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_bed')
	{
		$edit=1;
		$bed_data=$obj_hostel->mj_smgt_get_bed_by_id($_REQUEST['bed_id']);
	}
?>
       
<div class="panel-body"> <!-- start panel-body -->
	<form name="bed_form" action="" method="post" class="form-horizontal" id="bed_form">
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="bed_id" value="<?php if($edit){ echo $bed_data->id;}?>"/> 
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Beds Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form"> <!--Card Body div-->   
			<div class="row"><!--Row Div--> 
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="bed_unique_id" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $bed_data->bed_unique_id; } else { echo mj_smgt_generate_bed_code(); } ?>"  name="bed_unique_id" readonly>	
							<label class="" for="bed_unique_id"><?php esc_attr_e('Bed Unique ID','school-mgt');?> <span class="require-field">*</span></label>	
						</div>
					</div>
				</div>
				<div class="col-md-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="room_id"><?php esc_attr_e('Room Unique ID','school-mgt');?> <span class="require-field">*</span></label>
					<select name="room_id" class="form-control validate[required] width_100" id="room_id">
						<option value=""><?php echo esc_attr_e( 'Select Room Unique ID', 'school-mgt' ) ;?></option>
						<?php $roomval='';
						$room_data=$obj_hostel->mj_smgt_get_all_room();
						if($edit){  
							$roomval=$bed_data->room_id; 
							foreach($room_data as $room)
							{ ?>
							<option value="<?php echo $room->id;?>" <?php selected($room->id,$roomval);  ?>>
							<?php echo $room->room_unique_id;?></option> 
						<?php }
						}else
						{
							foreach($room_data as $room)
							{ ?>
							<option value="<?php echo $room->id;?>" <?php selected($room->id,$roomval);  ?>><?php echo $room->room_unique_id;?></option> 
						<?php }
						}
						?>
					</select>
				</div>
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="bed_charge" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="number" value="<?php if($edit){ echo $bed_data->bed_charge;}?>" name="bed_charge">
							<label class="" for="bed_charge"><?php esc_attr_e('Charge','school-mgt');?> (<?php echo mj_smgt_get_currency_symbol(); ?>)</label>
						</div>
					</div>
				</div>
				<?php wp_nonce_field( 'save_bed_admin_nonce' ); ?>
				
				<div class="col-md-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="bed_description" id="bed_description" maxlength="150" class="textarea_height_47px form-control validate[custom[description_validation]]"><?php if($edit){ echo $bed_data->bed_description;}?></textarea>		
								<span class="txt-title-label"></span>
								<label class="text-area address active" for="bed_description"><?php esc_attr_e('Description','school-mgt');?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6">
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Bed','school-mgt'); }else{ esc_attr_e('Add Bed','school-mgt');}?>" name="save_bed" class="btn btn-success save_btn" />
				</div>
			</div>
		</div>
	</form>
</div><!-- End panel-body --> 
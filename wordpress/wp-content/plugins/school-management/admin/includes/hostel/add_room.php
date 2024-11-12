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
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_room')
{
	$edit=1;
	$room_data=$obj_hostel->mj_smgt_get_room_by_id($_REQUEST['room_id']);
}
?>
       
<div class="panel-body">
	<form name="room_form" action="" method="post" class="form-horizontal" id="room_form">
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="room_id" value="<?php if($edit){ echo $room_data->id;}?>"/> 
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Room Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form"> <!--Card Body div-->   
			<div class="row"><!--Row Div--> 

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="room_unique_id" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $room_data->room_unique_id; } else { echo mj_smgt_generate_room_code(); } ?>"  name="room_unique_id" readonly>	
							<label class="" for="room_unique_id"><?php esc_attr_e('Room Unique ID','school-mgt');?> <span class="require-field">*</span></label>	
						</div>
					</div>
				</div>
				<div class="col-md-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="hostel_type"><?php esc_attr_e('Select Hostel','school-mgt');?> <span class="require-field">*</span></label>
					<select name="hostel_id" class="form-control validate[required]" id="hostel_id">
						<option value=""><?php echo esc_attr_e( 'Select Hostel', 'school-mgt' ) ;?></option>
						<?php $hostelval='';
						$hostel_data=$obj_hostel->mj_smgt_get_all_hostel();
						if($edit){  
							$hostelval=$room_data->hostel_id; 
							foreach($hostel_data as $hostel)
							{ ?>
							<option value="<?php echo $hostel->id;?>" <?php selected($hostel->id,$hostelval);  ?>>
							<?php echo $hostel->hostel_name;?></option> 
						<?php }
						}else
						{
							foreach($hostel_data as $hostel)
							{ ?>
							<option value="<?php echo $hostel->id;?>" <?php selected($hostel->id,$hostelval);  ?>><?php echo $hostel->hostel_name;?></option> 
						<?php }
						}
						?>
					</select>
				</div>
				<div class="col-md-4 input">
					<label class="ml-1 custom-top-label top" for="hostel_type"><?php esc_attr_e('Room Type','school-mgt');?> <span class="require-field">*</span></label>
					<select class="form-control validate[required] room_category width_100" name="room_category" id="room_category">
						<option value=""><?php esc_html_e('Select Room','school-mgt');?></option>
						<?php 
						$activity_category=mj_smgt_get_all_category('room_category');
						if(!empty($activity_category))
						{
							if($edit)
							{
								$room_val=$room_data->room_category; 
							}
							else
							{
								$room_val=''; 
							}
							foreach ($activity_category as $retrive_data)
							{ 		 	
							?>
								<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$room_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
							<?php }
						} 
						?> 
					</select>	
				</div>
				<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
					<button id="addremove_cat" class="save_btn sibling_add_remove" model="room_category"><?php esc_attr_e('Add','school-mgt');?></button>		
				</div>
				
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin padding_top_15px_res">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="beds_capacity" class="form-control validate[required,custom[onlyNumberSp],maxSize[2],min[1]] text-input" type="text" value="<?php if($edit){ echo $room_data->beds_capacity; } ?>"  name="beds_capacity">
							<label class="" for="Bed Capacity"><?php esc_attr_e('Beds Capacity','school-mgt');?> <span class="require-field">*</span></label> 
						</div>
					</div>
				</div>

				<?php wp_nonce_field( 'save_room_admin_nonce' ); ?>
				<div class="col-md-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="room_description" id="room_description" maxlength="150" class="textarea_height_47px form-control validate[custom[description_validation]]"><?php if($edit){ echo $room_data->room_description;}?></textarea>
								<span class="txt-title-label"></span>
								<label  class="text-area address active" for="room_description"><?php esc_attr_e('Description','school-mgt');?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6">
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Room','school-mgt'); }else{ esc_attr_e('Add Room','school-mgt');}?>" name="save_room" class="btn btn-success save_btn" />
				</div>
			</div>
		</div>
	</form>
</div>
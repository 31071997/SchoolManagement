<?php
$obj_hostel=new smgt_hostel;
 ?>
<?php
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_assign_room')
	{
		$room_id=$_REQUEST['room_id'];
	}
	$bed_data=$obj_hostel->mj_smgt_get_all_bed_by_room_id($room_id);
	$hostel_id=$obj_hostel->mj_smgt_get_hostel_id_by_room_id($room_id);

	$exlude_id = mj_smgt_approve_student_list();
	$student_all= get_users(array('role'=>'student','exclude'=>$exlude_id));

	foreach($student_all as $aa)
	{
		$student_id[]=$aa->ID;
	}
	//--------- GET ASSIGNED STUDENT DATA -------//
	$assign_data=mj_smgt_all_assign_student_data();

	if(!empty($assign_data))
	{
		foreach($assign_data as $bb)
		{
			$student_new_id[]=$bb->student_id;
		}
		$Student_result=array_diff($student_id,$student_new_id);
	}
	else
	{
		$Student_result=$student_id;
	}
?>
<div class="panel-body"><!-- start panel-body -->
	<?php
	$i=0;
	if(!empty($bed_data))
	{
		foreach($bed_data as $data)
		{
			$student_data =mj_smgt_student_assign_bed_data($data->id);
			?>

			<form name="bed_form" action="" method="post" class="form-horizontal" id="bed_form_new">
				<input type="hidden" name="room_id_new[]" value="<?php echo $data->room_id;?>">
				<input type="hidden" name="bed_id[]" value="<?php echo $data->id;?>">
				<input type="hidden" name="hostel_id" value="<?php echo $hostel_id;?>">
				<div class="form-body user_form mt-2" id="main_assign_room"> <!--Card Body div-->

					<div class="row">
						<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="bed_unique_id_<?php echo $i;?>" class="form-control validate[required]" type="text" value="<?php echo $data->bed_unique_id;;?>" name="bed_unique_id[]" readonly>
									<label class="" for="bed_unique_id"><?php esc_attr_e( 'Bed Unique ID', 'school-mgt' ) ;?><span class="require-field"></span></label>
								</div>
							</div>
						</div>
						<?php
						if(!empty($student_data))
						{
							$new_class='';
						}
						else
						{
							$new_class='new_class';
						}
						?>

						<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 input">
							<select name="student_id[]" id="students_list_<?php echo $i ;?>" data-index="<?php echo $i;?>" class="form-control validate[required] select_student student_check <?php echo $new_class; ?> students_list_<?php echo $i ;?>">
								<?php
								if(!empty($student_data))
								{
									$roll_no = get_user_meta( $student_data->student_id, 'roll_id' , true );
									$class_id = get_user_meta( $student_data->student_id, 'class_name' , true );
								?>
									<option value="<?php echo $student_data->student_id; ?>" ><?php echo mj_smgt_get_display_name($student_data->student_id).' ('.$roll_no.') ('.mj_smgt_get_class_name($class_id).')'; ?></option>
									<?php
								}
								else
								{?>
									<option value="0"><?php  esc_attr_e( 'Select Student', 'school-mgt' );?></option>
									<?php foreach($Student_result as $student)
									{
										$roll_no = get_user_meta( $student, 'roll_id' , true );
										$class_id = get_user_meta( $student, 'class_name' , true );
									?>
										<option value="<?php echo $student; ?>"><?php echo mj_smgt_get_display_name($student).' ('.$roll_no.') ('.mj_smgt_get_class_name($class_id).')'; ?></option>
									<?php
									}
								}
								?>
							</select>
						</div>
						<?php
						if(!empty($student_data))
						{
							?>
							<!-- <div class="col-md-2 col-sm-2 col-xs-12 padding_2px"> -->
								<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="assign_date_<?php echo $i ;?>"  value="<?php  echo mj_smgt_getdate_in_input_box($student_data->assign_date); ?>" class="form-control" type="text" name="assign_date[]" readonly>
										</div>
									</div>
								</div>
							<?php
						}
						else
						{
							?>
							<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
								<div class="form-group input">
									<div class="col-md-12 col-sm-12 col-xs-12 form-control assigndate_<?php echo $i;?>" id="assigndate_<?php echo $i ;?>" name="assigndate" >
										<input id="assign_date_<?php echo $i;?>" placeholder="<?php esc_attr_e( 'Enter Date', 'school-mgt' );?>" class="datepicker form-control text-input placeholder_color" type="text" name="assign_date[]" autocomplete="off" value="<?php echo mj_smgt_getdate_in_input_box(date('Y-m-d'));?>">
									</div>
								</div>
							</div>
							<?php
						}
						if($student_data)
						{
							?>
							<div class="col-md-2 col-sm-2 col-xs-12 input">
								<label class="col-md-2 col-sm-2 col-xs-12 control-label occupied col-form-label occupied_available_btn" for="available" ><?php esc_attr_e( 'Occupied', 'school-mgt' );?></label>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12 input">
								<a href="?page=smgt_hostel&tab=room_list&action=delete_assign_bed&room_id=<?php echo $data->room_id;?>&bed_id=<?php echo $data->id;?>&student_id=<?php echo $student_data->student_id;?>" class="btn btn-danger delete_btn" onclick="return confirm('<?php esc_attr_e('Are you sure you want to vacant this bed?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a>
							</div>
							<?php
						}
						else
						{ ?>
							<div class="col-md-2 col-sm-2 col-xs-12 input">
								<label class="col-md-2 col-sm-2 col-xs-12 control-label available col-form-label occupied_available_btn" for="available" ><?php esc_attr_e( 'Available', 'school-mgt' );?></label>
							</div>
							<?php
						}
						?>
					</div>

				</div>
				<!-- </div> -->
				<?php
				$i++;
		}
		?>
			<?php wp_nonce_field( 'save_assign_room_admin_nonce' ); ?>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-sm-6">
						<input type="submit" id="Assign_bed" value="<?php esc_attr_e('Assign Room','school-mgt');?>" name="assign_room" class="btn btn-success save_btn assign_room_for_alert" />
					</div>
				</div>
			</div>
		</form>
    	<?php
	}
	else
	{ ?>
		<h4 class="require-field" style="margin-top:10px;"><?php esc_attr_e('No Bed Available','school-mgt');?></h4>
	<?php
	}
	?>
</div><!-- End panel-body -->
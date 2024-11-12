<?php 
	$obj=new Smgt_Homework();
	$retrieve_class=$obj->mj_smgt_get_all_homeworklist();		
	$role_name=mj_smgt_get_user_role(get_current_user_id());
?>
<div class="panel-body">
	<script>
		jQuery(document).ready(function() {
			var table =  jQuery('#class_list').DataTable({
				"initComplete": function(settings, json) {
					$(".print-button").css({"margin-top": "-5%"});
				},
				//stateSave: true,
				"order": [[ 1, "asc" ]],
				"aoColumns":[	                  
							<?php
							if($role_name == "supportstaff")
							{
								?>
								{"bSortable": false},
								<?php
							}
							?>
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": true},
								{"bSortable": false}],
				language:<?php echo mj_smgt_datatable_multi_language();?>
			});
				jQuery('#checkbox-select-all').on('click', function(){
				var rows = table.rows({ 'search': 'applied' }).nodes();
				jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
			}); 
			
				$("#delete_selected").on('click', function()
				{	
					if ($('.select-checkbox:checked').length == 0 )
					{
						alert("<?php esc_html_e('Please select atleast one record','school-mgt');?>");
						return false;
					}
					else
					{
						var alert_msg=confirm(language_translate2.delete_record_alert);
						if(alert_msg == false)
						{
							return false;
						}
						else
						{
							return true;
						}
					}
			});
			
		});
	</script>	
	<div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">
			<table id="class_list" class="display" cellspacing="0" width="100%">
				<tbody>
				<?php 
				foreach ($retrieve_class as $retrieved_data)
					{ 
				?>
					<tr>
						<?php
						if($role_name == "supportstaff")
						{
							?>
							<td><input type="checkbox" class="select-checkbox" name="id[]" value="<?php echo $retrieved_data->homework_id;?>"></td>
							<?php
						}
						?>
						<td><?php echo $retrieved_data->title;?></td>
						<td><?php echo mj_smgt_get_class_name($retrieved_data->class_name);?></td>
						<td><?php echo mj_smgt_get_subject_byid($retrieved_data->subject);?></td>
						<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);?></td>
						<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->submition_date);?></td>
						<td>
						<?php  
							$doc_data=json_decode($retrieved_data->homework_document);
						?>
							<a href="?page=smgt_student_homewrok&tab=addhomework&action=edit&homework_id=<?php echo $retrieved_data->homework_id;?>" class="btn btn-info"> <?php esc_attr_e('Edit','school-mgt');?></a>
							<a href="?page=smgt_student_homewrok&tab=homeworklist&action=delete&homework_id=<?php echo $retrieved_data->homework_id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a>
							<?php
							if($user_access['add']=='1')
							{ 
								?>
								<a href="?page=smgt_student_homewrok&tab=view_stud_detail&action=viewsubmission&homework_id=<?php echo $retrieved_data->homework_id;?>" class="btn btn-default"> <?php echo '<span class="fa fa-eye"></span> '.esc_attr__('View Submission','school-mgt');?></a>
								<?php
							}
							?>
							
							<?php
							if(!empty($doc_data[0]->value))
							{
							?>
								<a download href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>"  class="status_read btn btn-default" record_id="<?php echo $retrieved_data->homework_id;?>"><i class="fa fa-download"></i><?php esc_html_e('Download Document', 'school-mgt');?></a>
							
								<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="status_read btn btn-default" record_id="<?php echo $retrieved_data->homework_id;?>"><i class="fa fa-eye"></i><?php esc_html_e('View Document', 'school-mgt');?></a>
						<?php
							}
							?>
						</td>
					</tr>
				<?php 
					} ?>
		
				</tbody>
			</table>
			<?php
			if($role_name == "supportstaff")
			{
				?>
				<div class="print-button pull-left">
					<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected" class="btn btn-danger delete_selected"/>
				</div>
				<?php
			}
			?>
		</form>
	</div>
</div>
<?php ?>
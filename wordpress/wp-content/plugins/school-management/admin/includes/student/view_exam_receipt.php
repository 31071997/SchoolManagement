<?php 

$active_tab = isset($_GET['tab'])?$_GET['tab']:'view_exam_receipt';
{
	$student_id=$_REQUEST['student_id'];
	$exam_data=mj_smgt_student_exam_receipt_check($student_id);
	?>
	<div class="panel-body"><!--------- panel Body -------->
		<div class="row"><!--------- Row Div -------->
			<div class="col-md-12">
				<form id="frm-example" name="frm-example" method="post"><!------ View Exam Receipt Form ------>
					<div class="table-responsive">
						<table id="exam_list" class="display admin_student_datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo esc_attr__( 'Exam Name', 'school-mgt' ) ;?></th>
									<th><?php echo esc_attr__( 'Action', 'school-mgt' ) ;?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th> <?php echo esc_attr__( 'Exam Name', 'school-mgt' ) ;?></th>
									<th><?php echo esc_attr__( 'Action', 'school-mgt' ) ;?></th>
								</tr>
							</tfoot>
							<tbody>
								<?php
									if(!empty($exam_data))
									{
										foreach($exam_data as $retrived_data)
										{
										?>
											<tr>
												<td> <?php echo mj_smgt_get_exam_name_id($retrived_data->exam_id) ;?></td>
												<td class="action">
													<a  href="?page=smgt_student&student_exam_receipt=student_exam_receipt&student_id=<?php echo $student_id;?>&exam_id=<?php echo $retrived_data->exam_id;?>" target="_blank"class="btn btn-success"><?php esc_attr_e('Print','school-mgt');?></a>
													<a  href="?page=smgt_student&student_exam_receipt_pdf=student_exam_receipt_pdf&student_id=<?php echo $student_id;?>&exam_id=<?php echo $retrived_data->exam_id;?>" target="_blank"class="btn btn-success"><?php esc_attr_e('PDF','school-mgt');?></a>
												</td>
											</tr>
										<?php
										}
									}
								?>
							</tbody>        
						</table>
					</div>
				</form><!------ View Exam Receipt Form ------>
			</div>
		</div><!--------- Row Div -------->
	</div><!--------- panel Body -------->
	<?php 
}	
<?php 

if($active_tab == 'memberlist')

{

	?>

	<script type="text/javascript">

		jQuery(document).ready(function($){

			var table =  jQuery('#member_issue_list').DataTable({
				//stateSave: true,
				responsive: true,

				"dom": 'lifrtp',

				"order": [[ 1, "asc" ]],

				"aoColumns":[                  

					{"bSortable": false},

					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": true}, 

					{"bSortable": false}],

				language:<?php echo mj_smgt_datatable_multi_language();?>

			});

			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

		});

	</script>

	<?php

	$studentdata =$school_obj->mj_smgt_get_all_student_list();

	$obj_lib= new Smgtlibrary();

	$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks(); 

	$array = 0;

	foreach($retrieve_issuebooks as $retrive_data)

	{

		$student_data =get_userdata($retrive_data->student_id);

		if($student_data){

			$array = 1;

		}

	}

	if(!empty($array))

	{

		$school_obj = new School_Management ();?>

		<div class="panel-body"><!--start panel-body -->

			<div class="table-responsive">

				<table id="member_issue_list" class="display admin_memebrlist_datatable" cellspacing="0" width="100%">

					<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

						<tr>

							<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>

							<th><?php esc_attr_e('Name & Email','school-mgt');?></th>

							<th><?php esc_attr_e('Class','school-mgt');?></th>

							<th><?php esc_attr_e('Roll No.','school-mgt');?></th>

							<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

						</tr>

					</thead>

					<tbody>

						<?php

						$studentdata =$school_obj->mj_smgt_get_all_student_list();

						if(!empty($studentdata))

						{

							foreach ($studentdata as $retrieved_data)

							{ 

								$book_issued = mj_smgt_check_book_issued($retrieved_data->ID);

								if(!empty($book_issued))

								{ ?>

									<tr>

										<td class="user_image width_50px padding_left_0">

											<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup">

												<?php 

													$uid=$retrieved_data->ID;

													$umetadata=mj_smgt_get_user_image($uid);

													if(empty($umetadata)){

														echo '<img src='.get_option( 'smgt_student_thumb_new' ).' height="50px" width="50px" class="img-circle" />';

													}

													else

													echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';

												?>

											</a>

										</td>



										<td class="name">

											<a class="color_black" href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup"><?php echo $retrieved_data->display_name;?></a>

											<br>

											<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>

										</td>



										<td class="name">

											<?php $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
												$section = get_user_meta($retrieved_data->ID, 'class_section',true);

											echo $classname=smgt_get_class_section_name_wise($class_id,$section);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class','school-mgt');?>" ></i>

										</td>

										<td class="roll_no">

											<?php echo get_user_meta($retrieved_data->ID, 'roll_id',true);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Roll No.','school-mgt');?>" ></i>

										</td>

										

										<td class="action"> 

											<div class="smgt-user-dropdown">

												<ul class="" style="margin-bottom: 0px !important;">

													<li class="">

														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

														</a>

														<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink"> 



															<li class="float_left_width_100">

																<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?> </a>

															</li>



															<li class="float_left_width_100">

																<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="accept_returns_book_popup" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/accept_book_return.png"?>" style="height:13px;">&nbsp;&nbsp;&nbsp;<?php esc_attr_e('Accept Returns','school-mgt');?> </a>

															</li>	

										

														</ul>

													</li>

												</ul>

											</div>										

										</td>

									</tr>

									<?php 

								} 

							} 

						}?>	

				

					</tbody>

				</table>

			</div>

		</div><!--End panel-body -->

		<?php

	}

	else

	{

		?>

		<div class="no_data_list_div no_data_img_mt_30px"> 

			<a href="<?php echo admin_url().'admin.php?page=smgt_library&tab=issuebook';?>">

				<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >

			</a>

			<div class="col-md-12 dashboard_btn margin_top_20px">

				<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>

			</div> 

		</div>	

		<?php

	}

	

} ?>
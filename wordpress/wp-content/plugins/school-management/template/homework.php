<?php

$role_name=mj_smgt_get_user_role(get_current_user_id());

?>

<script type="text/javascript">

	jQuery(document).ready(function($)

	{

		"use strict";	

		$('#homework_form_tempalte').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		

		$('#view_submition_form_front').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		

		$('#homework_list_front').DataTable({

			"initComplete": function(settings, json) {
				$(".print-button").css({"margin-top": "-5%"});
			},
			//stateSave: true,
			"dom": 'lifrtp',


			"aoColumns":[	      	                  

				<?php

					if($role_name == "supportstaff")

					{

						?>

						{"bSortable": false},

						<?php

					}

					if($school_obj->role=='student' || $school_obj->role=='parent')

					{

						?>

						{"bSortable": false},

						<?php

					}
					if($school_obj->role=='parent')
					{
					?>
						{"bSortable": true},
					<?php
					}
					?>
					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": false}],

			language:<?php echo mj_smgt_datatable_multi_language();?>	

		});



		$('#class_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('#class_form123').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('.datepicker').datepicker({

			minDate:0,
			changeYear:true,

			changeMonth: true,
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",

		});



		var table =  jQuery('#submission_list').DataTable({

			

			"order": [[ 1, "asc" ]],

			"dom": 'lifrtp',

			"aoColumns":[	                  

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},
						<?php
						if($role_name == "teacher")
						{
							?>
							{"bSortable": false}
							<?php
						}
						?>
						],
			language:<?php echo mj_smgt_datatable_multi_language();?>

		});

		jQuery('#checkbox-select-all').on('click', function(){

		

		var rows = table.rows({ 'search': 'applied' }).nodes();

			jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);

		}); 

		$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

		$('.select_all').on('click', function(e)

		{

			if($(this).is(':checked',true))  

			{

				$(".smgt_sub_chk").prop('checked', true);  

			}  

			else  

			{  

				$(".smgt_sub_chk").prop('checked',false);  

			} 

		});

		$('.smgt_sub_chk').on('change',function()

		{ 

			if(false == $(this).prop("checked"))

			{ 

				$(".select_all").prop('checked', false); 

			}

			if ($('.smgt_sub_chk:checked').length == $('.smgt_sub_chk').length )

			{

				$(".select_all").prop('checked', true);

			}

		});

		$("#delete_selected").on('click', function()

		{	

			if ($('.select-checkbox:checked').length == 0 )

			{

				alert(language_translate2.one_record_select_alert);

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



	// jQuery("body").on("change", ".input-file", function ()

	// {

    //     "use strict";		

	// 	var file = this.files[0]; 

	// 	var ext = $(this).val().split('.').pop().toLowerCase(); 

	// 	//Extension Check 

	// 	if($.inArray(ext, ['pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg']) == -1)

	// 	{

	// 		alert('<?php esc_attr_e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed.','school-mgt');?>'  + ext + '<?php esc_attr_e(' formate are not allowed.','school-mgt'); ?>');

	// 		$(this).replaceWith('<input type="file" name="file" class="form-control validate[required] input-file">');

	// 		return true; 

	// 	} 

	// 	//File Size Check 

	// 	if (file.size > 20480000) 

	// 	{


	// 			alert(language_translate2.large_file_Size_alert);

	// 			$(this).replaceWith('<input type="file" name="file" class="form-control validate[required]">'); 

	// 			return false; 

			

	// 	}

	// });

	$(document).ready(function ()
	{
		$('#homework_form_admin').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('.file_validation ').change(function () 
		{
			var val = $(this).val().toLowerCase();
			var regex = new RegExp("(.*?)\.(docx|doc|pdf|ppt|jpg|jpeg|png|xls|xlsx|ppt|pptx|gif)$");
			 if(!(regex.test(val)))
			{
				$(this).val('');
				alert("<?php _e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed.','school-mgt');?>");
			} 
		}); 
	});

</script>

<?php

//-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();

//--------------- ACCESS WISE ROLE -----------//

$user_access=mj_smgt_get_userrole_wise_access_right_array();

 

if (isset ( $_REQUEST ['page'] ))

{	

	if($user_access['view']=='0')

	{	

		mj_smgt_access_right_page_not_access_message();

		die;

	}

	if(!empty($_REQUEST['action']))

	{

		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))

		{

			if($user_access['edit']=='0')

			{	

				mj_smgt_access_right_page_not_access_message();

				die;

			}			

		}

		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))

		{

			if($user_access['delete']=='0')

			{	

				mj_smgt_access_right_page_not_access_message();

				die;

			}	

		}

		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))

		{

			if($user_access['add']=='0')

			{	

				mj_smgt_access_right_page_not_access_message();

				die;

			}	

		} 

	}

}

require_once SMS_PLUGIN_DIR. '/school-management-class.php';

$homewrk=new Smgt_Homework();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'homeworklist';



if(isset($_GET['success']) && $_GET['success'] == 1 )

{

	?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Homework Uploaded successfully.','school-mgt');?>

	</div>

	<?php

}	

if(isset($_GET['filesuccess']) && $_GET['filesuccess'] == 1 )

{

	?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('File Extension Invalid !','school-mgt');?>

	</div>

	<?php

}	

if(isset($_GET['addsuccess']) && $_GET['addsuccess'] == 1 )

{

	?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Homework Added Successfully.','school-mgt');?>

	</div>

	<?php

}

if(isset($_GET['deletesuccess']) && $_GET['deletesuccess'] == 1 )

{

	?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Homework Deleted Successfully','school-mgt');?>

	</div>

	<?php 

}

if(isset($_GET['updatesuccess']) && $_GET['updatesuccess'] == 1 )

{

	?>	

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Homework Updated Successfully','school-mgt');?>

	</div>

	<?php 

}

if(isset($_GET['updatesuccess']) && $_GET['updatesuccess'] == 2 )

{

	?>	

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Homework Reviewed Successfully.','school-mgt');?>

	</div>

	<?php 

}

if(isset($_GET['deleteselectedsuccess']) && $_GET['deleteselectedsuccess'] == 1 )

{

	?>	

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Homework Deleted Successfully','school-mgt');?>

	</div>

	<?php 

}	

			

?>

<!-- POP up code -->

<div class="popup-bg">

	<div class="overlay-content">

		<div class="modal-content">

			<div class="view_popup"></div>     

		</div>

	</div>    

</div>

<!-- End POP-UP Code -->

<div class="panel-body panel-white frontend_list_margin_30px_res"><!------------ PENAL BODY -------------->

	<!---------------- TABING START ---------------->
	<?php
	$page_action = '';
	if(!empty($_REQUEST['action']))
	{
		$page_action = $_REQUEST['action'];
	}
	?>
	<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">

		<li class="<?php if($active_tab=='homeworklist'){?>active<?php }?>">

			<a href="?dashboard=user&page=homework&tab=homeworklist" class="padding_left_0 tab <?php echo $active_tab == 'homeworklist' ? 'nav-tab-active' : ''; ?>">

			<?php echo esc_attr__('Homework List', 'school-mgt'); ?></a>

		</li>

		<?php

		if($user_access['add']=='1')

		{

			?>

			<li class="<?php if($active_tab=='view_stud_detail' || $page_action == 'viewsubmission'){?>active<?php }?>">

				<a href="?dashboard=user&page=homework&tab=view_stud_detail" class="padding_left_0 tab <?php echo $active_tab == 'view_stud_detail' ? 'nav-tab-active' : ''; ?> ">

				<?php echo esc_attr__('View Submission', 'school-mgt'); ?></a>

			</li>  

			<?php

		}

		

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')

		{

			?>

			<li class="<?php if($active_tab=='Viewhomework' || $_REQUEST['action'] == 'viewsubmission'){?>active<?php }?>">

				<a href="?dashboard=user&page=homework&tab=Viewhomework&action=view&homework_id=<?php echo $_REQUEST['homework_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'Viewhomework' ? 'nav-tab-active' : ''; ?> ">

				<?php echo esc_attr__('Upload Homework', 'school-mgt'); ?></a>

			</li>  

			<?php

		}

		if($active_tab == 'addhomework')

		{

			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

			{	?>

			<li class="<?php if($active_tab=='addhomework' || $_REQUEST['action'] == 'edit'){?>active<?php }?>">

				<a href="?dashboard=user&page=homework&tab=addhomework&&action=edit&homework_id=<?php echo $_REQUEST['homework_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addhomework' ? 'nav-tab-active' : ''; ?>">

				<?php esc_attr_e('Edit Homework', 'school-mgt'); ?></a>  

			</li> 

				<?php 

			}

			else

			{	?>

				<?php 

				if($user_access['add']=='1')

				{ 

					?>

					<li class="<?php if($active_tab=='addhomework'){?>active<?php }?>">

						<a href="?dashboard=user&page=homework&tab=addhomework" class="padding_left_0 tab <?php echo $active_tab == 'addhomework' ? 'nav-tab-active' : ''; ?>">

						<?php echo esc_attr__('Add Homework', 'school-mgt'); ?></a>  

					</li> 

					<?php 

				}

			} ?>

			<?php

		}
		if($active_tab == 'view_student_homework')
		{
			?>
			<li class="<?php if($active_tab=='view_student_homework'){?>active<?php }?>">
	
			<a href="?dashboard=user&page=homework&tab=view_student_homework" class="padding_left_0 tab <?php echo $active_tab == 'view_student_homework' ? 'nav-tab-active' : ''; ?>">
	
			<?php echo esc_attr__('Submitted Homework', 'school-mgt'); ?></a>  
	
			</li> 
			<?php
		}
		?>
	</ul>

   	<!---------------- TABING END ---------------->

	<?php

	if($active_tab == 'addhomework')

	{

		require_once SMS_PLUGIN_DIR. '/template/add-studentHomework.php';

	}

	if($active_tab == 'view_stud_detail')

	{	

		$homework=new Smgt_Homework();

		if($school_obj->role=='teacher')

		{

			$res = $homework->mj_smgt_get_teacher_homeworklist();
			

		}

		else

		{

			if($user_access['own_data'] == '1')

	        {

			    $res = $homework->mj_smgt_get_all_own_homeworklist();

			}

			else

			{

				$res = $homework->mj_smgt_get_all_homeworklist();

			}

		}	
		if($page_action == "edit")
		{
			$edit=1;
		}
		else
		{
			$edit=0;
		}
	   ?>

		<div class="panel-body marging_top_50px_rs"><!-- panel-body--> 	

			<div class="smgt_homework_list"> <!-- smgt_homework_list--> 

				<form name="view_submition_form_front" action="" method="post" class="margin_top_20px padding_top_25px_res form-horizontal" id="class_form123">

					<div class="form-body user_form mb-2"> <!-- user_form div-->   

						<div class="row"><!--Row Div--> 

							<div class="col-sm-9 col-md-9 col-lg-9 col-xl-9 input smgt_form_select">

								<label class="custom-top-label lable_top top" for="homewrk"><?php esc_attr_e('Select Homework','school-mgt');?><span class="require-field">*</span></label>

								<?php if($edit){ $classval=$user_info->class_name; }elseif(isset($_POST['class_name'])){$classval=$_POST['class_name'];}else{$classval='';}?>

								<select name="homewrk" class="line_height_30px form-control validate[required]" id="homewrk">

									<option value=""><?php esc_attr_e('Select Homework','school-mgt');?></option>

									<?php

									$classval='';

									//var_dump($_REQUEST['homework_id']);

									if(isset($_REQUEST['homework_id']))

									{

										$classval=$_REQUEST['homework_id'];

									}

									foreach($res as $classdata)

									{  

										?>

										<option value="<?php echo $classdata->homework_id;?>" <?php selected($classdata->homework_id,$classval);  ?>><?php echo $classdata->title;?></option>

										<?php 

									}?>

								</select>

							</div>

							<div class="col-md-3 col-sm-3 col-xs-3 res_rtl_width_100">

								<input type="submit" value="<?php esc_attr_e('View','school-mgt');?>" name="view"  class="save_btn custom_class"/>

							</div>

						</div><!--Row Div--> 

					</div> <!-- user_form div--> 

					<?php

					$obj=new Smgt_Homework();

					if(isset($_POST['homewrk']))

					{

						$data=$_POST['homewrk'];

						$retrieve_class=$obj-> mj_smgt_view_submission($data);
					
						require_once SMS_PLUGIN_DIR. '/template/viewsubmission.php';

					}

					else

					{

						if(isset($_REQUEST['homework_id']))

						{

							$data=$_REQUEST['homework_id'];

							$retrieve_class=$obj-> mj_smgt_view_submission($data);

							require_once SMS_PLUGIN_DIR. '/template/viewsubmission.php';

						}

					}

					?>

				</form>

			</div><!-- smgt_homework_list--> 

		</div><!-- panel-body-->

       <?php

    }

	?>

    <div class="">

		<?php 

		if($active_tab=="homeworklist")

		{

			?>

			<div class="tab-pane active" id="examlist">         

				<?php 

				//------- HomeWork DATA FOR STUDENT ---------//

				if($school_obj->role=='student')

				{

					$result=$homewrk->mj_smgt_student_view_detail();
				
					
				}

				//------- HomeWork DATA FOR PARENT ---------//

				elseif($school_obj->role=='parent')
				{
					$result = $homewrk->mj_smgt_parent_view_detail();
				}

				//------- HomeWork DATA FOR TEACHER ---------//

				elseif($school_obj->role=='teacher')

				{
					$own_data=$user_access['own_data'];
				
					if($own_data == '1')

					{ 
						
						$result=$homewrk->mj_smgt_get_all_own_homeworklist_for_teacher();
						
					}

					else

					{

						$result=$homewrk->mj_smgt_get_all_homeworklist();

					}

				}

				//------- HomeWork DATA FOR SUPPORT STAFF ---------//

				else

				{

					$own_data=$user_access['own_data'];

					if($own_data == '1')

					{ 

						$result=$homewrk->mj_smgt_get_all_own_homeworklist();

					}

					else

					{

						$result=$homewrk->mj_smgt_get_all_homeworklist();

					}

					

				}
				// var_dump($result);
				// die;
				if(!empty($result))

				{

					?>

					<div class="panel-body"><!----------- PENAL BODY -------------->

						<div class="table-responsive"><!----------- TABLE RESPONSIVE --------------->

							<!---------------- HOMEWORK LIST PAGE FORM ------------->

							<form id="frm-example" name="frm-example" method="post">

								<!----------- HOME WORK LIST TABLE ------------->

								<table id="homework_list_front" class="display dataTable" cellspacing="0" width="100%">
									<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
										<tr>
											<?php
											if($role_name == "supportstaff")
											{
												?>
													<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
												<?php
											}
											?>
											<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
											<th><?php esc_attr_e('Title','school-mgt');?></th>
											<?php  
												if($school_obj->role=='parent')
												{ 
													?>
													<th><?php esc_attr_e('Student','school-mgt');?></th>
													<?php
												}
											?>
											<th><?php esc_attr_e('Class','school-mgt');?></th>
											<th><?php esc_attr_e('Subject','school-mgt');?></th>
											<th><?php esc_attr_e('Homework Date','school-mgt');?></th>
											<th><?php esc_attr_e('Submission Date','school-mgt');?></th>
											<?php  
												if($school_obj->role=='student' || $school_obj->role=='parent')
												{ 
													?>
														<th><?php esc_attr_e('Status','school-mgt');?></th>
													<?php
												}
											?>
											<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
										</tr>
									</thead>
									<tbody>

										<?php 

										$i=0;

										foreach ($result as $retrieved_data)

										{
											
											if($i == 10)

											{

												$i=0;

											}

											if($i == 0)

											{

												$color_class='smgt_class_color0';

											}

											elseif($i == 1)

											{

												$color_class='smgt_class_color1';

											}

											elseif($i == 2)

											{

												$color_class='smgt_class_color2';

											}

											elseif($i == 3)

											{

												$color_class='smgt_class_color3';

											}

											elseif($i == 4)

											{

												$color_class='smgt_class_color4';

											}

											elseif($i == 5)

											{

												$color_class='smgt_class_color5';

											}

											elseif($i == 6)

											{

												$color_class='smgt_class_color6';

											}

											elseif($i == 7)

											{

												$color_class='smgt_class_color7';

											}

											elseif($i == 8)

											{

												$color_class='smgt_class_color8';

											}

											elseif($i == 9)

											{

												$color_class='smgt_class_color9';

											}

											?>

											<tr>

												<?php

												if($role_name == "supportstaff")

												{

													?>

													<td class="checkbox_width_10px">

														<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->homework_id;?>">

													</td>

													<?php

												}

												?>

												<td class="user_image width_50px profile_image_prescription">	
													<a class="view_details_popup" href="#" id="<?php echo $retrieved_data->homework_id;?>" type="Homework_view">
														<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>" alt="" class="massage_image center image_icon_height_25px margin_top_3px">
														</p>
													</a>
												</td>
												<td>
													<a class="color_black view_details_popup" href="#" id="<?php echo $retrieved_data->homework_id;?>" type="Homework_view"><?php echo $retrieved_data->title;?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Title','school-mgt');?>" ></i>
												</td>
												<?php
												if($role_name == "parent")
												{
												?>
													<td>
														<?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class','school-mgt');?>" ></i>
													</td>
												<?php
												}
												?>
												<td>
													<?php echo smgt_get_class_section_name_wise($retrieved_data->class_name,$retrieved_data->section_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class','school-mgt');?>" ></i>
												</td>

												<td>

													<?php echo mj_smgt_get_subject_byid($retrieved_data->subject);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Subject','school-mgt');?>" ></i>

												</td>

												<td>

													<?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Homework Date','school-mgt');?>" ></i>

												</td>

												<td>

													<?php echo mj_smgt_getdate_in_input_box($retrieved_data->submition_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Submission Date','school-mgt');?>" ></i>

												</td>
												<?php  
												if($school_obj->role=='student' || $school_obj->role=='parent')
												{ 
													if($retrieved_data->status==1)
													{
														if(date('Y-m-d',strtotime($retrieved_data->uploaded_date)) <= $retrieved_data->submition_date)
														{
															?>
															<td>
																<label class="green_color">
																	<?php esc_attr_e('Submitted','school-mgt'); ?>
																</label>
																<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Status','school-mgt');?>" ></i>
															</td>
															<?php
														}
														else
														{
															?>
															<td>
																<label class="perpal_color">
																	<?php esc_attr_e('Late-Submitted','school-mgt'); ?> 
																</label>
																<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Status','school-mgt');?>" ></i>
															</td>
															<?php
														}
													}
													else
													{
														?>
														<td>
															<label class="color-red">
																<?php esc_attr_e('Pending','school-mgt'); ?> 
															</label>
															<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Status','school-mgt');?>" ></i>
														</td>
														<?php	     
													} 
												} 
												?>
												<td class="action"> 

													<div class="smgt-user-dropdown">

														<ul class="" style="margin-bottom: 0px !important;">

															<li class="">

																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

																</a>

																<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																	<?php  

																		$doc_data=json_decode($retrieved_data->homework_document);

																	?>

																	<li class="float_left_width_100 ">

																		<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->homework_id;?>" type="Homework_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View Homework Detail','school-mgt');?></a>

																	</li>

																	<?php

																	if($school_obj->role=='student' || $school_obj->role=='parent')

																	{

																		?>

																		<li class="float_left_width_100 ">

																			<a href="?dashboard=user&page=homework&tab=Viewhomework&action=view&homework_id=<?php echo $retrieved_data->homework_id;?>&student_id=<?php echo $retrieved_data->student_id;?>" class="float_left_width_100"><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('Submit Homework','school-mgt');?></a>

																		</li>

																		<?php

																	}

																	if($user_access['add']=='1')

																	{

																		?>

																		<li class="float_left_width_100">

																			<a href="?dashboard=user&page=homework&tab=view_stud_detail&action=viewsubmission&homework_id=<?php echo $retrieved_data->homework_id;?>" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/submitted_homework.png"?>"style="height:17px;">&nbsp;&nbsp;<?php esc_attr_e('Submitted Homework','school-mgt');?></a>

																		</li>

																		<?php

																	}

																	if(!empty($doc_data[0]->value))

																	{

																		?>

																		<li class="float_left_width_100">

																			<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="float_left_width_100" record_id="<?php echo $retrieved_data->homework_id;?>"><i class="fa fa-eye"> </i><?php esc_html_e('View Document', 'school-mgt');?></a>

																		</li>

																		<?php

																	}

																	?>

																	<?php 

																	if($user_access['edit']=='1')

																	{

																		?>



																		<li class="float_left_width_100 border_bottom_item">

																			<a href="?dashboard=user&page=homework&tab=addhomework&action=edit&homework_id=<?php echo $retrieved_data->homework_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a>

																		</li>

																		<?php 

																	} ?>

																	<?php 

																	if($user_access['delete']=='1')

																	{ 

																		?>

																		<li class="float_left_width_100 ">

																			<a href="?dashboard=user&page=homework&tab=homeworklist&action=delete&homework_id=<?php echo $retrieved_data->homework_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"> </i> <?php esc_attr_e('Delete','school-mgt');?></a>

																		</li>

																		<?php 

																	} ?>



																</ul>

															</li>

														</ul>

													</div>	

												</td>

											</tr>

											<?php

											$i++;

										}

										?>

									</tbody>

								</table>

								<!----------- HOME WORK LIST TABLE ------------->

								<?php

								if($role_name == "supportstaff")

								{

									?>

									<div class="print-button pull-left">

										<button class="btn btn-success btn-sms-color button_reload">

											<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">

											<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

										</button>

										<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="homework_delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

									</div>

									<?php

								}?>

							<form><!---------------- HOMEWORK LIST PAGE FORM ------------->

						</div><!----------- TABLE RESPONSIVE --------------->

					</div><!----------- PENAL BODY -------------->

					<?php

				}

				else

				{

					if($user_access['add']=='1')

					{

						?>

						<div class="no_data_list_div no_data_img_mt_30px"> 

							<a href="<?php echo home_url().'?dashboard=user&page=homework&tab=addhomework';?>">

								<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >

							</a>

							<div class="col-md-12 dashboard_btn margin_top_20px">

								<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>

							</div> 

						</div>		

						<?php

					}

					else

					{

						?>

						<div class="calendar-event-new"> 

							<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >

						</div>	

						<?php

					}

				

				}

				?>

			</div>

			<?php

		} 

		$view=0;

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')

		{

			

			$view=1;

			$objj=new Smgt_Homework();

			$classdata= $objj->mj_smgt_parent_update_detail($_GET['homework_id'],$_GET['student_id']);

			$data = $classdata[0];
		
		} 

		if($active_tab=="Viewhomework")

		{ 

			?>
			<script>
				$(document).ready(function ()
				{
					$('.save_homework').click(function() 
					{
						var val = $(".file-validation").val();
						if(val == ''){
							alert('Upload Your Homework');
							return false;
						}
					});
				});
			</script>

			<div class="tab-pane active" id=""><!------------ TAB PENAL ----------------->

				<div class="panel-body"><!-------------- PENAL BODY -------------->

					<!----------------- SUBMITE HOMEWORK ADD FORM ---------------->

					<form name="class_form" action="" method="post" class="form-horizontal" id="homework_form_tempalte" enctype="multipart/form-data">

						<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

						<input type="hidden" name="action" value="<?php echo $action;?>">

						<input type="hidden" id="stu_homework_id" name="stu_homework_id" value="<?php if($view){ echo $data->stu_homework_id;}?>">

						<input type="hidden" id="homework_id" name="homework_id" value="<?php if($view){ echo $data->homework_id;}?>">

						<input type="hidden" id="status" name="status" value="<?php if($view){ echo $data->status;}?>">    

						<input type="hidden" id="student_id" name="student_id" value="<?php if($view){ echo $data->student_id;}?>">       		

						<div class="header">	

							<h3 class="first_hed"><?php esc_html_e('Homework Submition Information','school-mgt');?></h3>

						</div>

						<div class="form-body user_form"> <!------  Form Body -------->

							<div class="row">

								<div class="col-md-6">

									<div class="form-group input">

										<div class="col-md-12 form-control">

											<input id="title" class="form-control validate[required] text-input" type="text"  value="<?php if($view){ echo $data->title;}?>" name="title" readonly>

											<label for="userinput1" class=""><?php esc_html_e('Title','school-mgt');?><span class="required">*</span></label>

										</div>

									</div>

								</div>

								<div class="col-md-6">

									<div class="form-group input">

										<div class="col-md-12 form-control">

											<input id="title" class="form-control validate[required] text-input" type="text"  value="<?php if($view){ echo mj_smgt_student_display_name_with_roll($data->student_id);}?>" name="student_names" readonly>

											<label for="userinput1" class=""><?php esc_html_e('Student Name','school-mgt');?><span class="required">*</span></label>

										</div>

									</div>

								</div>

								<div class="col-md-6">

									<div class="form-group input">

										<div class="col-md-12 form-control">

											<input id="subject" class="form-control validate[required] text-input" type="text" value="<?php if($view){ echo mj_smgt_get_single_subject_name($data->subject);}?>" name="subject" readonly>

											<label for="userinput1" class=""><?php esc_html_e('Subject','school-mgt');?><span class="required">*</span></label>

										</div>

									</div>

								</div>

								<div class="col-md-6">

									<div class="form-group input">

										<div class="col-md-12 form-control">

											<input id="submition_date" class="form-control validate[required]" type="text" value="<?php if($view){ echo $data->submition_date;}?>" name="submition_date" readonly>

											<label for="userinput1" class=""><?php esc_html_e('Submission Date','school-mgt');?><span class="required">*</span></label>

										</div>

									</div>

								</div>

								<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.1/tinymce.jquery.min.js"></script>

								<script>

									tinymce.init(

									{

										selector: 'textarea',

										menubar:false,

										toolbar: false,
										readonly : 1
									});

								</script>

								<div class="col-md-6">

									<div class="form-group input">

										<div class="col-md-12 form-control texarea_padding_15">

											<?php $str = $data->content; ?>

											<textarea id="content" class="form-control validate[required] w-100 h-200-px" value="" name="content" readonly><?php if($view){ echo '<pre>'.$str.'</pre>'; }?></textarea>

											<label for="first_name" class="textarea_label"><?php esc_attr_e('Content', 'school-mgt'); ?></label>

										</div>

									</div>

								</div>
								<?php
								
								if(!empty($data->review_file))
								{
									?>
									<div class="col-md-6">

										<div class="form-group input">

											<div class="col-md-12 form-control">

											<a download href="<?php print content_url().'/uploads/homework_file/'.$data->review_file; ?>" class=" btn " record_id="<?php echo $data->stu_homework_id;?>" download><i class="fa fa-download"></i>&nbsp;&nbsp;<?php esc_attr_e('Download','school-mgt');?></a>

												<label for="userinput1" class="upload_homework_label"><?php esc_html_e('Reviewed file','school-mgt');?><span class="required">*</span></label>

											</div>

										</div>

									</div>
									<?php
								}
								?>
								
								<?php

								if($data->status == 0)

								{

									?>

									<div class="col-md-6">	

										<div class="form-group input">

											<div class="col-md-12 form-control">	

												<div class="col-sm-12">	

													<input id="file" type='file' class="form-control file-validation input-file"  value="<?php if($view){ echo $data->submition_date;}?>" name="file">

												</div>

											</div>

										</div>

									</div>

									<?php 

								}

								else

								{?>
									<div class="col-sm-6">
									</div>  
									<div class="col-sm-6">        	

										<label class="col-sm-12 control-label col-form-label text-md-end color_green" style="color:green;" for="class_name"><?php esc_attr_e('HOMEWORK SUBMITTED !','school-mgt');?></label>

									</div> 

									<?php 

								}

								?>

							</div>

						</div>

						<?php

						if($data->status == 0)

						{

							?>

							<div class="form-body user_form"> <!------  Form Body -------->

								<div class="row">

									<div class="col-sm-6">        	

										<input type="submit" value="<?php  if($view) esc_attr_e('Submit Homework','school-mgt');?>" name="Save_Homework" class="btn btn-success save_homework save_btn" />

									</div> 

								</div>

							</div>

							<?php

						}

						?>

					</form>

				</div>

				</div> 

			</div> 

			<?php 

		}

		?>

    </div>

</div> <!------------ PENAL BODY -------------->

<?php

if(isset($_POST['save_homework_front']))
{
	
	$nonce = $_POST['_wpnonce'];

	if ( wp_verify_nonce( $nonce, 'save_homework_front_nonce' ) )

	{

		$insert=new Smgt_Homework();

		if($_POST['action'] == 'edit')

		{

			if(isset($_FILES['homework_document']) && !empty($_FILES['homework_document']) && $_FILES['homework_document']['size'] !=0)

			{		

				if($_FILES['homework_document']['size'] > 0)

					$upload_docs1=mj_smgt_load_documets_new($_FILES['homework_document'],$_FILES['homework_document'],$_POST['document_name']);		

			}

			else

			{

				if(isset($_REQUEST['old_hidden_homework_document']))

				$upload_docs1=$_REQUEST['old_hidden_homework_document'];

			}

				

			$document_data=array();

			if(!empty($upload_docs1))

			{

				$document_data[]=array('title'=>$_POST['document_name'],'value'=>$upload_docs1);

			}

			else

			{

				$document_data[]='';

			}

			

			$update_data=$insert->mj_smgt_add_homework($_POST,$document_data);

			if($update_data)

			{

				wp_redirect ( home_url() . '?dashboard=user&page=homework&tab=homeworklist&updatesuccess=1');

				exit;

			}

		}

		else

		{

			$args = array( 'meta_query' => array( array( 'key' => 'class_name', 'value' => $_POST['class_name'], 'compare' => '=' ) ), 'count_total' => true ); 

			$users = new WP_User_Query($args); 

			if ($users->get_total() == 0)

			{

				wp_redirect ( admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist&message=4');

			}

			else

			{

				if(isset($_FILES['homework_document']) && !empty($_FILES['homework_document']) && $_FILES['homework_document']['size'] !=0)

				{		

					if($_FILES['homework_document']['size'] > 0)

						$upload_docs1=mj_smgt_load_documets_new($_FILES['homework_document'],$_FILES['homework_document'],$_POST['document_name']);		

				}
				else
				{

					$upload_docs1='';

				}

					

				$document_data=array();

				if(!empty($upload_docs1))

				{

					$document_data[]=array('title'=>$_POST['document_name'],'value'=>$upload_docs1);

				}

				else

				{

					$document_data[]='';

				}

				

				$insert_data=$insert->mj_smgt_add_homework($_POST,$document_data);

				if($insert_data)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=homework&tab=homeworklist&addsuccess=1');

					exit;

				}

			}

		}

	}

}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$delete=new Smgt_Homework();

	$dele=$delete->mj_smgt_get_delete_record($_REQUEST['homework_id']);

	if($dele)
	{
		header("Location: ?dashboard=user&page=homework&tab=homeworklist&deletesuccess=1");
	}
}

if(isset($_REQUEST['homework_delete_selected']))
{		
	$tablename="mj_smgt_homework";

	$ojc=new Smgt_Homework();

	if(!empty($_REQUEST['id']))
	{

		foreach($_REQUEST['id'] as $id)
		{
			$delete=$ojc->mj_smgt_get_delete_records($tablename,$id);
			if($delete)
			{
				wp_redirect ( home_url().'?dashboard=user&page=homework&deleteselectedsuccess=1');
			}
		}
	}
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == "download")

{

	$assign_id = $_REQUEST['stud_homework_id'];

	$homework_obj=new Smgt_Homework();

	$filedata = $homework_obj->mj_smgt_check_uploaded($assign_id);

	if($filedata != false)

	{

		$file = $filedata;

	}

	$upload = wp_upload_dir();

	$upload_dir_path = $upload['basedir'];

	$file = $upload_dir_path . '/homework_file/'.$file;

	if (file_exists($file)) 

	{

		header('Content-Description: File Transfer');

		header("Content-type: application/pdf",true,200);

		header('Content-Disposition: attachment; filename='.basename($file));

		header('Content-Transfer-Encoding: binary');

		header('Expires: 0');

		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

		header('Pragma: public');

		header('Content-Length: ' . filesize($file));

		ob_clean();

		flush();

		readfile($file);

		exit;	

	}	

}



if($school_obj->role=='student' || $school_obj->role=='parent')

{

	if(isset($_POST['Save_Homework']))

    {

		$uploadfile=array('stu_homework_id'=>mj_smgt_onlyNumberSp_validation($_POST['stu_homework_id']),

		'homework_id'=>mj_smgt_onlyNumberSp_validation($_POST['homework_id']),

		'status'=>mj_smgt_onlyNumberSp_validation($_POST['status']),

		'title'=>mj_smgt_address_description_validation($_POST['title']),

		'subject'=>mj_smgt_address_description_validation($_POST['subject']),

		'content'=>$_POST['content'],

		'submition_date'=>$_POST['submition_date'],

		'upload_file'=>$_FILES['file']

				);

		if(!empty($uploadfile))

		{

			if(isset($_FILES['file']))

			{

				$randm = mt_rand(5,15);

		        $file_name = "H".$randm."_".$_FILES['file']['name'];

				$file_tmp =$_FILES['file']['tmp_name'];

				

				$upload = wp_upload_dir();

				$upload_dir_path = $upload['basedir'];

				$upload_dir = $upload_dir_path . '/homework_file';

				if (!file_exists($upload_dir))
				{

				   mkdir( $upload_dir, 0700 );

				}

				$up = move_uploaded_file($file_tmp,$upload_dir.'/'.$file_name);
			
				global $wpdb;

				$mj_smgt_student_homework = $wpdb->prefix."mj_smgt_student_homework";

				$stud_homework_id=$_POST['stu_homework_id'];

				$stud_id=$_POST['student_id'];

				$homework_id=$_POST['homework_id'];

				$status = 1 ;

				$uploaded_date=date("Y-m-d H:i:s");

				$result=$wpdb->update($mj_smgt_student_homework, array( 

				'homework_id' => $homework_id,	// string

				'student_id' => $stud_id,	// integer (number) 

				'status' => $status,

				'uploaded_date' => $uploaded_date,

				'file' => $file_name), 

					array( 'stu_homework_id' => $stud_homework_id ), 

					array( '%d','%d','%d','%s','%s'), 

					array( '%d' ));

				if($result)

				{

					header("Location: ?dashboard=user&page=homework&tab=homeworklist&success=1");

				}

			}

			else

			{

				echo "File Not Upload";

			}

		}

	}

}

if($school_obj->role == 'teacher')
{
	if(isset($_POST['review_homework']))
    {
		
		if(isset($_FILES['review_file']))
		{
			
			$randm = mt_rand(5,15);
			if(!empty($_FILES['review_file']['name']))
			{
				$file_name = "H".$randm."_".$_FILES['review_file']['name'];
			}
			else
			{
				$file_name = "";
			}
			
			$file_tmp =$_FILES['review_file']['tmp_name'];
			$upload = wp_upload_dir();
			$upload_dir_path = $upload['basedir'];
			$upload_dir = $upload_dir_path . '/homework_file';
			if (!file_exists($upload_dir))
			{
				mkdir( $upload_dir, 0700 );
			}
			$up = move_uploaded_file($file_tmp,$upload_dir.'/'.$file_name);

			global $wpdb;

			$mj_smgt_student_homework = $wpdb->prefix."mj_smgt_student_homework";

			$stud_homework_id=$_POST['stu_homework_id'];

			$result=$wpdb->update($mj_smgt_student_homework, array('review_file' => $file_name), array('stu_homework_id' => $stud_homework_id));

			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=homework&tab=view_stud_detail&updatesuccess=2');

					exit;
			}
		}
	}
}

if($active_tab == 'view_student_homework')
{
	$homework_obj=new Smgt_Homework();

	$data = $homework_obj->MJ_smgt_get_student_submitted_homework($_REQUEST['stud_homework_id']);
	$homework_data = $homework_obj->mj_smgt_get_edit_record($data->homework_id);
	
	?>
	<div class="tab-pane active" id=""><!------------ TAB PENAL ----------------->

		<div class="panel-body"><!-------------- PENAL BODY -------------->

			<!----------------- SUBMITE HOMEWORK ADD FORM ---------------->

			<form name="class_form" action="" method="post" class="form-horizontal" id="homework_form_tempalte" enctype="multipart/form-data">

				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

				<input type="hidden" name="action" value="<?php echo $action;?>">

				<input type="hidden" id="stu_homework_id" name="stu_homework_id" value="<?php  echo $data->stu_homework_id;?>">

				<input type="hidden" id="homework_id" name="homework_id" value="<?php echo $data->homework_id;?>">

				<input type="hidden" id="status" name="status" value="<?php  echo $data->status;?>">    

				<input type="hidden" id="student_id" name="student_id" value="<?php  echo $data->student_id;?>">       		

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Submitted Homework','school-mgt');?></h3>

				</div>

				<div class="form-body user_form"> <!------  Form Body -------->

					<div class="row">

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="title" class="form-control validate[required] text-input" type="text"  value="<?php  echo mj_smgt_get_display_name($data->student_id);?>" name="student_name" readonly>

									<label for="userinput1" class=""><?php esc_html_e('Student Name','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="title" class="form-control validate[required] text-input" type="text"  value="<?php  echo $homework_data->title;?>" name="title" readonly>

									<label for="userinput1" class=""><?php esc_html_e('Title','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="subject" class="form-control validate[required] text-input" type="text" value="<?php echo mj_smgt_get_single_subject_name($homework_data->subject);?>" name="subject" readonly>

									<label for="userinput1" class=""><?php esc_html_e('Subject','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="submition_date" class="form-control validate[required]" type="text" value="<?php echo mj_smgt_getdate_in_input_box($homework_data->submition_date);?>" name="submition_date" readonly>

									<label for="userinput1" class=""><?php esc_html_e('Submission Date','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="submition_date" class="form-control validate[required]" type="text" value="<?php echo mj_smgt_getdate_in_input_box($data->uploaded_date);?>" name="submitted_date" readonly>
									<label for="userinput1" class=""><?php esc_html_e('Submitted Date','school-mgt');?><span class="required">*</span></label>
									

								</div>

							</div>

						</div>
						<div class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control res_rtl_height_50px">	
									
										<a download href="<?php print content_url().'/uploads/homework_file/'.$data->file; ?>" class=" btn " record_id="<?php echo $data->stu_homework_id;?>" download><i class="fa fa-download"></i>&nbsp;&nbsp;<?php esc_attr_e('Download','school-mgt');?></a>
									
										<label for="userinput1" class="upload_homework_label"><?php esc_html_e('Uploaded File','school-mgt');?></label>
								</div>
							</div>
						</div>
					
						<div class="col-md-6">	

							<div class="form-group input">

								<div class="col-md-12 form-control">	

									<div class="col-sm-12">	

										<input id="review_file" type='file' class="form-control file-validation input-file"  value="" name="review_file">
										<label for="userinput1" class="upload_homework_label"><?php esc_html_e('Reviewed File','school-mgt');?></label>
									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="form-body user_form"> <!------  Form Body -------->

					<div class="row">

						<div class="col-sm-6">        	

							<input type="submit" value="<?php esc_attr_e('Review Homework','school-mgt');?>" name="review_homework" class="btn btn-success save_homework save_btn" />

						</div> 

					</div>

				</div>

			</form>

		</div>

		</div> 

		</div>
	<?php
}
?>

<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//

$obj_document=new smgt_document();

mj_smgt_browser_javascript_check();

$role=mj_smgt_get_user_role(get_current_user_id());

if($role == 'administrator')

{

	$user_access_add=1;

	$user_access_edit=1;

	$user_access_delete=1;

	$user_access_view=1;

}

else

{

	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('document');

	$user_access_add=$user_access['add'];

	$user_access_edit=$user_access['edit'];

	$user_access_delete=$user_access['delete'];

	$user_access_view=$user_access['view'];



	if (isset ( $_REQUEST ['page'] ))

	{	

		if($user_access_view=='0')

		{	

			mj_smgt_access_right_page_not_access_message_admin_side();

			die;

		}

		if(!empty($_REQUEST['action']))

		{

			if ('document' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))

			{

				if($user_access_edit=='0')

				{	

					mj_smgt_access_right_page_not_access_message_admin_side();

					die;

				}			

			}

			if ('document' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))

			{

				if($user_access_delete=='0')

				{	

					mj_smgt_access_right_page_not_access_message_admin_side();

					die;

				}	

			}

			if ('document' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))

			{

				if($user_access_add=='0')

				{	

					mj_smgt_access_right_page_not_access_message_admin_side();

					die;

				}	

			} 

		}

	}

}



$active_tab = sanitize_text_field(isset($_GET['tab'])?$_GET['tab']:'documentlist');





if(isset($_POST['save_document']))//SAVE DOCUMENT	

{

	$nonce = sanitize_text_field($_POST['_wpnonce']);

	if (wp_verify_nonce($nonce, 'save_document_nonce'))

	{

		$upload_docs_array=array(); 

		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')

		{

			

			if(isset($_FILES['upload_file']) && !empty($_FILES['upload_file']) && $_FILES['upload_file']['size'] !=0)

			{		

				if($_FILES['upload_file']['size'] > 0)

					$upload_docs1=mj_smgt_load_documets_new($_FILES['upload_file'],$_FILES['upload_file'],$_POST['doc_title']);		

			}

			else

			{

				if(isset($_REQUEST['old_hidden_document']))

				$upload_docs1=$_REQUEST['old_hidden_document'];

			}

				

			$document_data=array();

			if(!empty($upload_docs1))

			{

				$document_data[]=array('title'=>$_POST['doc_title'],'value'=>$upload_docs1);

			}

			else

			{

				$document_data[]='';

			}

			

			$result=$obj_document->mj_smgt_add_document($_POST,$document_data);

			if($result)

			{

				wp_redirect ( home_url().'?dashboard=user&page=document&tab=documentlist&message=2');

			} 

		}

		else

		{

			if(isset($_FILES['upload_file']) && !empty($_FILES['upload_file']) && $_FILES['upload_file']['size'] !=0)

			{		

				if($_FILES['upload_file']['size'] > 0)

					$upload_docs1=mj_smgt_load_documets_new($_FILES['upload_file'],$_FILES['upload_file'],$_POST['doc_title']);		

			}

			else

			{

				$upload_docs1='';

			}

				

			$document_data=array();

			if(!empty($upload_docs1))

			{

				$document_data[]=array('title'=>$_POST['doc_title'],'value'=>$upload_docs1);

			}

			else

			{

				$document_data[]='';

			}

			

			

			$result=$obj_document->mj_smgt_add_document($_POST,$document_data);

			if($result)

			{

				wp_redirect ( home_url().'?dashboard=user&page=document&tab=documentlist&message=1');

			} 

		}

	}

}

if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE DOCUMENT

{

	$result=$obj_document->mj_smgt_delete_document($_REQUEST['document_id']);

	

	if($result)

	{

		wp_redirect ( home_url().'?dashboard=user&page=document&tab=documentlist&message=3');

	}

}







if(isset($_REQUEST['delete_selected']))

{		

		if(!empty($_REQUEST['selected_id']))

		{

		foreach($_REQUEST['selected_id'] as $id)

		{

			$delete=$obj_document->mj_smgt_delete_document($id);

		}

		if($delete)

		{

			wp_redirect ( home_url().'?dashboard=user&page=document&tab=documentlist&message=3');

		}

	}

}



?>

<div class="panel-body panel-white frontend_list_margin_30px_res">



	<?php

	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

	switch($message)

	{

		case '1':

			$message_string = esc_attr__('Document Inserted Successfully.','school-mgt');

			break;

		case '2':

			$message_string = esc_attr__('Document Updated Successfully.','school-mgt');

			break;	

		case '3':

			$message_string = esc_attr__('Document Deleted Successfully.','school-mgt');

			break;

	}



	if($message)

	{

		?>

		<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

			</button>

			<?php echo $message_string;?>

		</div>

		<?php

	}	

	?>



							

	<?php 

	//DOCUMENT LIST TAB

	if($active_tab == 'documentlist')

	{ 

		$user_id=get_current_user_id();

		$own_data=$user_access['own_data'];

		if($own_data == '1')

		{ 

			$documentdata=mj_smgt_get_user_document_list($user_id,$school_obj->role);

		}

		else

		{

			$documentdata=$obj_document->mj_smgt_get_all_documents();		

		}

		//------- EXAM DATA FOR TEACHER ---------//



		if(!empty($documentdata))

		{	

			?>  

			<script type="text/javascript">

				jQuery(document).ready(function($)

				{

					"use strict";	

					var table =  jQuery('#document_list').DataTable({

						"initComplete": function(settings, json) {

							$(".print-button").css({"margin-top": "-5%"});

						},
						//stateSave: true,
						"order": [[ 2, "asc" ]],

						"dom": 'lifrtp',

						"aoColumns":[	

									<?php 

									if($user_access['delete'] =='1')

									{ 

										?>                  

										{"bSortable": false},

										<?php

									}?>

									{"bSortable": false},

									{"bSortable": true},

									{"bSortable": true},

									{"bSortable": true},

									{"bSortable": true},

									{"bSortable": true},

									{"bSortable": true},	                 

									{"bSortable": false}],

						language:<?php echo mj_smgt_datatable_multi_language();?>

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

						if ($('.smgt_sub_chk:checked').length == 0 )

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

			</script>

			<div class="panel-body">

				<div class="table-responsive">

					<form id="frm-example" name="frm-example" method="post">	

						<table id="document_list" class="display admin_transport_datatable" cellspacing="0" width="100%">

							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

								<tr>

									<?php 

									if($user_access['delete'] =='1')

									{ 

										?>

										<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>

										<?php

									} ?>

									<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>

									<th><?php  _e( 'Title', 'school-mgt' ) ;?></th>

									<th><?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?></th>

									<th><?php echo esc_attr_e( 'Class Section', 'school-mgt' ) ;?></th>

									<th><?php echo esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>			

									<th><?php echo esc_attr_e( 'Date', 'school-mgt' ) ;?></th>			

									<th><?php echo esc_attr_e( 'Description', 'school-mgt' ) ;?></th>

									<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

								</tr>

							</thead>

							<tbody>

								<?php 

								$i=0;

								foreach ($documentdata as $retrieved_data)

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

										if($user_access['delete'] =='1')

										{ 

											?>

											<td class="checkbox_width_10px">

												<input type="checkbox" name="selected_id[]" class="smgt_sub_chk select-checkbox" value="<?php echo esc_attr($retrieved_data->document_id); ?>">

											</td>	

											<?php

										}

										?>

										<td class="user_image width_50px profile_image_prescription">	

											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>" alt="" class="massage_image center image_icon_height_25px margin_top_3px">

											</p>

										</td>

										<td class="title">

												<?php 

												$doc_data=json_decode($retrieved_data->document_content);

													echo esc_html($doc_data[0]->title);

												?>

											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Document Title','school-mgt');?>" ></i>

										</td>

										<td><?php if($retrieved_data->class_id=="all class"){echo esc_attr_e('All Class','school-mgt');}else{echo mj_smgt_get_class_name($retrieved_data->class_id);} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class','school-mgt');?>"></i></td> 

										<td><?php if($retrieved_data->section_id=="all section"){echo esc_attr_e('All Section','school-mgt');}else{echo mj_smgt_get_section_name($retrieved_data->section_id);} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Section','school-mgt');?>"></i></td> 

										<td><?php if($retrieved_data->student_id=="all student"){ echo esc_attr_e('All Student','school-mgt'); }elseif($retrieved_data->student_id==""){ echo "N/A"; }else{ echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id); } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td> 

										<td><?php if(!empty($retrieved_data->created_date)){echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);}else{echo "N/A";} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>

										<td><?php

														$description =$retrieved_data->description;

														$grade_comment = strlen($description) > 30 ? substr($description,0,30)."..." : $description;

														if(!empty($retrieved_data->description)){echo $grade_comment;}else{echo "N/A";} 

														?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($description)){echo $description;}else{esc_html_e('Description','school-mgt');}?>"></i></td>

										<td class="action">  

											<div class="smgt-user-dropdown">

												<ul class="" style="margin-bottom: 0px !important;">

													<li class="">

														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

														</a>

														<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

															<?php 

															$doc_data=json_decode($retrieved_data->document_content);



															if(!empty($doc_data[0]->value))

															{

																?>

																<li class="float_left_width_100">

																	<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="float_left_width_100" record_id="<?php echo $retrieved_data->homework_id;?>"><i class="fa fa-eye"> </i><?php esc_html_e('View Document', 'school-mgt');?></a>

																</li>

																<?php

															}



															if($user_access_edit == '1')

															{

																?>

																<li class="float_left_width_100 border_bottom_menu">

																	<a href="?dashboard=user&page=document&tab=add_document&action=edit&document_id=<?php echo $retrieved_data->document_id?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																</li>

																<?php 

															} 

															if($user_access_delete =='1')

															{

																?>

																<li class="float_left_width_100 ">

																	<a href="?dashboard=user&page=document&tab=documentlist&action=delete&document_id=<?php echo $retrieved_data->document_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

																	<i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

																</li>

																<?php

															}

															?>

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

						<?php 

						if($user_access['delete'] =='1')

						{ 

							?>

							<div class="print-button pull-left">

								<button class="btn-sms-color button_reload">

									<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">

									<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

								</button>

								<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

							</div>

							<?php

						}

						?>

					</form>

				</div>

			</div>

			<?php 

		}

		else

		{

			if($user_access_add=='1')

			{

				?>

				<div class="no_data_list_div"> 

					<a href="<?php echo home_url().'/?dashboard=user&page=document&tab=add_document';?>">

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



	}

	if($active_tab == 'add_document')

	{

		?>

		<script type="text/javascript">

			function fileCheck(obj)

			{

				"use strict";

				var fileExtension = ['pdf','doc','docx','jpg','jpeg','png'];

				if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)

				{

					alert("<?php esc_html_e('Sorry, only JPG, pdf, docs., JPEG, PNG And GIF files are allowed.','school-mgt');?>");

					$(obj).val('');

				}	

			}

		</script>

		<script type="text/javascript">

			$(document).ready(function()

			{

				//DOCUMENT FORM VALIDATIONENGINE

				"use strict";

				<?php

					if (is_rtl())

					{

						?>	

							$('#document_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

						<?php

					}

					else

					{

						?>

						$('#document_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

						<?php

					}

				?>

				$('.onlyletter_number_space_validation').keypress(function( e ) 

				{   

					"use strict";  

					var regex = new RegExp("^[0-9a-zA-Z \b]+$");

					var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);

					if (!regex.test(key)) 

					{

						event.preventDefault();

						return false;

					} 

				});  

			} );

		</script>





		<!-- <script type="text/javascript">

			$(function () {

				$(".select_Student_div").hide();

				$(".select_class_Section").change(function () {

					if ($(this).val() == "all section") {

						$(".select_Student_div").hide();

					} else {

						$(".select_Student_div").show();

					}

				});

			});

		</script> -->

		<?php 	

		$document_id=0;

		if(isset($_REQUEST['document_id']))

		$document_id=$_REQUEST['document_id'];

		$edit=0;

		if(isset($_REQUEST['action']) && sanitize_text_field($_REQUEST['action']) == 'edit')

		{					

			$edit=1;

			$result = $obj_document->mj_smgt_get_single_document($document_id);

		} ?>



		<div class="panel-body padding_0"><!--PANEL BODY-->

			<!--DOCUMENT FORM-->

			<form name="document_form" action="" method="post" class="form-horizontal" id="document_form" enctype="multipart/form-data">

				<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>

				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">

				<input type="hidden" name="document_id" value="<?php echo esc_attr($document_id);?>"  />

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Document Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form"> <!-- user_form Strat-->   

					<div class="row"><!--Row Div Strat--> 

					<?php
					
					?>

						<div class="col-md-6 input">

							<label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?><?php if($role  == 'teacher'){ ?><span class="require-field">*</span><?php } ?></label>

							<?php if($edit){ $classval=$result->class_id; }elseif(isset($_POST['class_id'])){$classval=$_POST['class_id'];}else{$classval='';}?>

							<select name="class_id" class="line_height_30px form-control max_width_100 class_list_document <?php if($role  == 'teacher'){ echo "validate[required]";} ?>" >
							<?php
							if($role  == 'teacher'){
								?>
									<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
								<?php
							}
							else{
								?>
								<option value="all class"><?php esc_attr_e('All Class','school-mgt');?></option>
								<?php
							}
							?>

								<?php

								foreach(mj_smgt_get_allclass() as $classdata)

								{  

									?>

									<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>

									<?php 

								}?>
							

							</select>

						</div>





						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input error_msg_left_margin">

							<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>

							<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>

							<select name="class_section" class="line_height_30px form-control max_width_100 class_section_document" >

								<option value="all section"><?php esc_attr_e('All Section','school-mgt');?></option>

								<?php

								if($edit){

										foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)

										{  ?>

											<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

									<?php } 

										} ?>

							</select>    

						</div>
						<div class="col-md-6 input select_Student_div">

							<label class="ml-1 custom-top-label top"><?php esc_attr_e('Select Student','school-mgt');?><span class="require-field">*</span></label>								

							<span class="document_user_display_block">

							<?php if($edit){ $student_val=$result->student_id; }elseif(isset($_POST['selected_users'])){$student_val=$_POST['selected_users'];}else{$student_val='all student';}?>

								<select name="selected_users"  class="form-control validate[required] max_width_100 student_list" style="height: 47px;">

								<?php 

								if($student_val == 'all student')

								{

								?>

								   <option value="all student"><?php esc_attr_e('All Student','school-mgt');?></option>

								<?php

								}								

								else

								{ ?>

								<?php

									echo '<option value="'.$result->student_id.'" '.selected($result->student_id,$result->student_id).'>'.mj_smgt_student_display_name_with_roll($result->student_id).'</option>';

								}?>

								</select>

							</span>

						</div>	

					</div>

				</div>

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Upload Document','school-mgt');?></h3>

				</div>

				<div class="form-body user_form"> <!-- user_form Strat-->   

					<div class="row"><!--Row Div Strat--> 

						<?php

						if($edit)

						{

							$doc_data=json_decode($result->document_content);

							?>

							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="doc_title" maxlength="50" name="doc_title" class="form-control validate[required,custom[description_validation]] text-input" type="text"  value="<?php if(!empty($doc_data[0]->title)) { echo esc_attr($doc_data[0]->title);}elseif(isset($_POST['doc_title'])) echo esc_attr($_POST['doc_title']);?>">

										<label class="" for="doc_title"><?php esc_html_e('Document Title','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>



							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control res_rtl_height_75px">	

										<div class="col-sm-12">	

											<input type="file" name="document_content" class="file_validation input-file"/>						

											<input type="hidden" name="old_hidden_document" value="<?php if(!empty($doc_data[0]->value)){ echo esc_attr($doc_data[0]->value);}elseif(isset($_POST['document_content'])) echo esc_attr($_POST['document_content']);?>">

										</div>

										<?php

										if(!empty($doc_data[0]->value))

										{

											?>

											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">

												<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" record_id="<?php echo $result->document_id;?>">

												<i class="fa fa-download"></i>&nbsp;&nbsp;<?php esc_attr_e('Download','school-mgt');?></a>

											</div>

											<?php

										}

											?>

									</div>

								</div>

							</div>

							<?php

						}

						else 

						{

							?>

							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="doc_title" maxlength="50" class="form-control validate[required,custom[description_validation]] text-input" type="text"  value="" name="doc_title">

										<label class="" for="doc_title"><?php esc_html_e('Document Title','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>

								

							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px" style="padding:10px !important;">

										<label class="ustom-control-label profile_rtl_css custom-top-label ml-2" for="photo"><?php esc_html_e('Upload Document','school-mgt');?><span class="require-field">*</span></label>

										<div class="col-sm-12 display_flex">

											<input id="upload_file" onchange="fileCheck(this);" name="upload_file"  type="file" <?php if($edit){ ?>class="margin_left_15_res" <?php }else{ ?>class="validate[required] margin_left_15_res margin_top_5_res"<?php } ?>  />

										</div>

									</div>

								</div>

							</div>

							<?php

						}

						?>



						<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 note_text_notice">

							<div class="form-group input">

								<div class="col-md-12 note_border margin_bottom_15px_res">

									<div class="form-field">

										<textarea name="description" maxlength="150"  class="textarea_height_47px form-control validate[custom[description_validation]] text-input resize"><?php if($edit) echo esc_textarea($result->description);?></textarea>

										<span class="txt-title-label"></span>

										<label class="text-area address active" for="desc"><?php esc_html_e('Description','school-mgt');?></label>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

				<!----------   save btn    --------------> 

				<div class="form-body user_form "> <!-- user_form Strat-->   

					<div class="row"><!--Row Div Strat--> 

						<div class="col-md-6 col-sm-6 col-xs-12"> 	

							<?php wp_nonce_field( 'save_document_nonce' ); ?>

							<input type="submit" value="<?php if($edit){ esc_html_e('Edit Document','school-mgt'); }else{ esc_html_e('Add Document','school-mgt');}?>" name="save_document" class="btn save_btn"/>

						</div>

					</div><!--Row Div End--> 

				</div><!-- user_form End--> 

			</form><!--END DOCUMENT FORM-->

		</div><!--END PANEL BODY-->

		<?php

	}

	?>	

</div>
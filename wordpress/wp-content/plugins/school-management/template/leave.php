<?php

    $obj_leave = new SmgtLeave();

    $role=mj_smgt_get_user_role(get_current_user_id());



	//-------- CHECK BROWSER JAVA SCRIPT ----------//

	mj_smgt_browser_javascript_check();

	$active_tab = isset($_GET['tab'])?$_GET['tab']:'leave_list';

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



    if(isset($_POST['save_leave']))

    {

        $nonce = $_POST['_wpnonce'];

        if (wp_verify_nonce( $nonce, 'save_leave_nonce' ) )

        {

            if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')

            {

                $result=$obj_leave->hrmgt_add_leave($_POST);

                if($result)

                {

                    wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=2');

                }

            }

            else

            {

                $result=$obj_leave->hrmgt_add_leave($_POST);

                if($result)

                {

                    wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=1');

                }

            }

        }

    }

    if(isset($_POST['approve_comment'])&& $_POST['approve_comment']=='Submit')

    {

        $result=$obj_leave->hrmgt_approve_leave($_POST);

        if($result)

        {

            wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=4');

        }

    }



    if(isset($_POST['reject_leave'])&& $_POST['reject_leave']=='Submit')

    {

        $result=$obj_leave->hrmgt_reject_leave($_POST);

        if($result)

        {

            wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=5');

        }

    }



    if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')

    {

        $result=$obj_leave->hrmgt_delete_leave($_REQUEST['leave_id']);

        if($result)

        {

            wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=3');

        }

    }

    if(isset($_REQUEST['delete_selected']))

    {

        if(!empty($_REQUEST['id']))

            foreach($_REQUEST['id'] as $id)

                $result=$obj_leave->hrmgt_delete_leave($id);

                if($result)

                {

                    wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=3');

                }

    }



    if(isset($_REQUEST['approve_selected']))

    {

        if(!empty($_REQUEST['id']))

            foreach($_REQUEST['id'] as $id)

            {

                $leave_id['leave_id']= $id;

                $result = $obj_leave->hrmgt_approve_leave_selected($id);

            }

            if($result)

            {

                wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=4');

            }

            else

            {

                wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=6');

            }

    }

    if(isset($_REQUEST['reject_selected']))

    {

        if(!empty($_REQUEST['id']))

           foreach($_REQUEST['id'] as $id)

            {

                $leave_id['leave_id']= $id;

                $result = $obj_leave->hrmgt_reject_leave_selected($id);

            }

            if($result)

            {

                wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=4');

            }

            else

            {

                wp_redirect ('?dashboard=user&page=leave&tab=leave_list&message=6');

            }

    }



    if(isset($_REQUEST['message']))

    {

        $message =$_REQUEST['message'];

        if($message == 1)

        { ?>

             <div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert" style="margin:5px !important">

                <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

                </button>

                <p><?php _e('Leave Added Successfully','school-mgt');?></p>

            </div>

            <?php

        }

        elseif($message == 2)

        { ?>

            <div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert" style="margin:5px !important">

                <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

                </button>

                <p><?php _e("Leave Updated Successfully",'school-mgt');?></p>

            </div><?php

        }

        elseif($message == 3)

        { ?>

            <div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert" style="margin:5px !important">

                <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

                </button>

                <p><?php _e('Leave Deleted Successfully','school-mgt');?></p>

            </div><?php

        }

        elseif($message == 4)

        { ?>

            <div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert" style="margin:5px !important">

                <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

                    </button>

                <p><?php _e('Leave Approved Successfully','school-mgt'); ?></p>

            </div><?php

        }

        elseif($message == 5)

        { ?>

            <div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert" style="margin:5px !important">

                <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

                </button>

                <p><?php _e('Leave Rejected Successfully','school-mgt'); ?></p>

            </div><?php

        }

        elseif($message == 6)

        { ?>

            <div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert" style="margin:5px !important">

                <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

                </button>

                <p><?php _e('Oops, Something went wrong.','school-mgt'); ?></p>

            </div><?php

        }

    }

    ?>



<div class="popup-bg">

    <div class="overlay-content">

		<div class="modal-content">

			<div class="category_list"></div>

		</div>

    </div>

</div>



<div class="page-inner"><!--------- Page Inner ------->

	<div id="" class="main_list_margin_5px">



		<div class="panel-white"><!--------- penal White ------->

			<div class="panel-body"> <!--------- penal body ------->

				<?php

				if($active_tab == 'leave_list')

				{

					$user_id=get_current_user_id();

					//------- Leave DATA FOR STUDENT ---------//

					if($school_obj->role == 'student')

					{

                        $own_data=$user_access['own_data'];

						if($own_data == '1')

						{

                            $leave_data=$obj_leave->get_single_user_leaves($user_id);

						}

						else

						{

							$leave_data = mj_smgt_get_all_data('smgt_leave');

						}

					}

					//------- Leave DATA FOR TEACHER ---------//

					elseif($school_obj->role == 'teacher')

					{

                        $own_data=$user_access['own_data'];

						if($own_data == '1')

						{

							$leave_data	=mj_smgt_get_all_leave_created_by($user_id);



						}

						else

						{

							$leave_data = mj_smgt_get_all_data('smgt_leave');

						}

					}

					//------- Leave DATA FOR PARENT ---------//

					elseif($school_obj->role == 'parent')

					{

		                $child_id =get_user_meta($user_id, 'child', true);

                        $leave_data_array=array();

                        if(!empty($child_id))

                        {

                            foreach ($child_id as $student_id)

                            {

                                $leave_data_array[] = mj_smgt_get_all_leave_parent_by_child_list($student_id);

                            }

                        }

						$mergedArray = array_merge(...$leave_data_array);

		                $leave_data = array_unique($mergedArray, SORT_REGULAR);

					}

					//------- Leave DATA FOR SUPPORT STAFF ---------//

					else

					{

						$own_data=$user_access['own_data'];

						if($own_data == '1')

						{

							$leave_data	=mj_smgt_get_all_leave_created_by($user_id);

						}

						else

						{

							$leave_data = mj_smgt_get_all_data('smgt_leave');

						}

					}





						 ?>

						<script type="text/javascript">

							jQuery(document).ready(function($)

							{

								"use strict";

								var table =  jQuery('#leave_list').DataTable({

                                    //stateSave: true,

									"order": [[ 5, "desc" ]],

									"dom": 'lifrtp',

									"aoColumns":[

												{"bSortable": false},

												{"bSortable": true},

												{"bSortable": true},

												{"bSortable": true},

												{"bSortable": true},

												{"bSortable": true},

												{"bSortable": true},

												{"bSortable": true},

												{"bSortable": true},

                                                <?php

                                                if($user_access['edit']=='1' || $user_access['delete']=='1')

                                                {

                                                    ?>

                                                    {"bSortable": false}

                                                    <?php

                                                }

                                                ?>

                                            ],

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

								jQuery('#checkbox-select-all').on('click', function(){



									var rows = table.rows({ 'search': 'applied' }).nodes();

									jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);

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

						</script>



						<div class="panel-body">

                            <?php

                            if($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff')

                            {



                            ?>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

                        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
                            <script type="text/javascript">

                            $(document).ready(function() {

                                    "use strict";

                                    $('.Student_leave_drop').select2({

                                    });

                                })

                            </script>

                                <form method="post">

									<div class="form-body user_form mt-3">

										<div class="row">

											<div class="col-md-3 input">

												<select class="form-control Student_leave_drop" id="Student_leave" name="Student_id" style="height: 47px;">

													<option value="all_student"><?php _e('Select All Student','school-mgt');?></option>

													<?php

														$emp_id=0;


                                                        if($school_obj->role == 'teacher')
                                                        {
                                                            $user_id=get_current_user_id();

                                                            $class_id=get_user_meta($user_id,'class_name',true);

                                                            $studentdata=$school_obj->mj_smgt_get_teacher_student_list($class_id);
                                                        }
                                                        else
                                                        {
                                                            $studentdata=mj_smgt_get_all_student_list('student');
                                                        }

														foreach($studentdata as $student)

														{

															if(isset($_POST['Student_id']))

															{

																$emp_id = $_POST['Student_id'];

															}

															else{

																$uid = $student->ID;

																$emp_id = get_user_meta($uid, 'student', true);

															}



														?>

														<option value="<?php print $student->ID ?>" <?php selected($student->ID,$emp_id) ?>><?php echo mj_smgt_student_display_name_with_roll($student->ID);?></option>

														<?php

													} ?>

												</select>

											</div>



											<div class="col-md-3 input">

												<label for="exam_id" class="ml-1 custom-top-label top"><?php _e('Select Status','school-mgt');?></label>

												<select class="form-control" id="lave_status" name="status" style="height: 47px;">

												<?php

                            						$select_status = isset($_REQUEST['status'])?$_REQUEST['status']:'';?>

													<option value="all_status" <?php echo selected($select_status,"all_status");?>><?php _e('Select All Status','school-mgt');?></option>

													<option value="Not Approved" <?php echo selected($select_status,"Not Approved");?>><?php _e('Not Approved','school-mgt');?></option>

													<option value="Approved" <?php echo selected($select_status,"Approved");?>><?php _e('Approved','school-mgt');?></option>

													<option value="Rejected" <?php echo selected($select_status,"Rejected");?>><?php _e('Rejected','school-mgt');?></option>

												</select>

											</div>

											<div class="col-md-3 mb-3 input">

												<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date','school-mgt');?><span class="require-field">*</span></label>

													<select class="line_height_30px form-control date_type validate[required]" name="date_type" autocomplete="off">

													<?php

                            						$date_type = isset($_REQUEST['date_type'])?$_REQUEST['date_type']:'this_month';?>

														<option value="today" <?php echo selected($date_type,"today");?>><?php esc_attr_e('Today','school-mgt');?></option>

														<option value="this_week" <?php echo selected($date_type,"this_week");?>><?php esc_attr_e('This Week','school-mgt');?></option>

														<option value="last_week" <?php echo selected($date_type,"last_week");?>><?php esc_attr_e('Last Week','school-mgt');?></option>

														<option value="this_month" 	<?php echo selected($date_type,"this_month");?>><?php esc_attr_e('This Month','school-mgt');?></option>

														<option value="last_month" <?php echo selected($date_type,"last_month");?>><?php esc_attr_e('Last Month','school-mgt');?></option>

														<option value="last_3_month" <?php echo selected($date_type,"last_3_month");?>><?php esc_attr_e('Last 3 Months','school-mgt');?></option>

														<option value="last_6_month" <?php echo selected($date_type,"last_6_month");?> ><?php esc_attr_e('Last 6 Months','school-mgt');?></option>

														<option value="last_12_month" <?php echo selected($date_type,"last_12_month");?>><?php esc_attr_e('Last 12 Months','school-mgt');?></option>

														<option value="this_year" <?php echo selected($date_type,"this_year");?>><?php esc_attr_e('This Year','school-mgt');?></option>

														<option value="last_year" <?php echo selected($date_type,"last_year");?>><?php esc_attr_e('Last Year','school-mgt');?></option>

														<option value="period" <?php echo selected($date_type,"period");?>><?php esc_attr_e('Period','school-mgt');?></option>

													</select>

											</div>

											<div id="date_type_div" class="date_type_div_none row col-md-6 mb-2"></div>

											<div class="col-md-2">

												<input type="submit" name="view_student" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>

											</div>



										</div>

									</div>

								</form>

								<?php

								if(isset($_REQUEST['view_student']))

								{

									$date_type = $_POST['date_type'];

									if(isset($_POST['start_date']))

									{

										$start_date = $_POST['start_date'];

									}

									if(isset($_POST['end_date']))

									{

										$end_date = $_POST['end_date'];

									}

									$Student_id = $_POST['Student_id'];

									$status = $_POST['status'];

									$leave_data=mj_smgt_get_leave_data_filter_vise($Student_id,$status,$date_type,$start_date,$end_date);

								}

								else

								{

                                    $Student_id = 'all_student';

                                    $status = 'all_status';

                                    $date_type ='this_month';

                                    $leave_data=mj_smgt_get_leave_data_filter_vise($Student_id,$status,$date_type,$start_date,$end_date);

								}

                            }

                                if(!empty($leave_data))

				        	    {

                                ?>



							<div class="table-responsive">

								<form id="frm-example" name="frm-example" method="post">

									<table id="leave_list" class="display admin_transport_datatable" cellspacing="0" width="100%">

										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

											<tr>

												<th><?php _e( 'Image', 'school-mgt' ) ;?></th>

												<th><?php _e( 'Student Name', 'school-mgt' ) ;?></th>

                                                <th> <?php echo esc_attr_e( 'Class & Section', 'school-mgt' ) ;?></th>

												<th><?php _e( 'Leave Type', 'school-mgt' ) ;?></th>

												<th><?php _e( 'Leave Duration', 'school-mgt' ) ;?></th>

												<th><?php _e( 'Start Date', 'school-mgt' ) ;?></th>

												<th><?php _e( 'End Date', 'school-mgt' ) ;?></th>

												<th><?php _e( 'Status', 'school-mgt' ) ;?></th>

												<th><?php _e( 'Reason', 'school-mgt' ) ;?></th>

                                                <?php

                                                if($user_access['edit']=='1' || $user_access['delete']=='1')

                                                {

                                                    ?>

                                                    <th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

                                                    <?php

                                                }

                                                ?>

											</tr>

										</thead>

										<tbody>

											<?php

											$i=0;

											foreach ($leave_data as $retrieved_data)

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

													<td class="user_image width_50px profile_image_prescription">

														<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/leave.png"?>" alt="" class="massage_image center image_icon_height_25px margin_top_3px">

														</p>

													</td>

													<td><?php $sname = mj_smgt_student_display_name_with_roll($retrieved_data->student_id); if($sname){echo $sname;}else{echo 'N/A';} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>

													<td class="name">

                                                        <?php

                                                        $class_id = get_user_meta($retrieved_data->student_id, 'class_name',true);

                                                        $section_id = get_user_meta($retrieved_data->student_id, 'class_section',true);

                                                        $classname = smgt_get_class_section_name_wise($class_id,$section_id);

                                                        if(!empty($classname))

                                                        {

                                                            echo $classname;

                                                        }

                                                        else

                                                        {

                                                            echo "N/A";

                                                        }

                                                    ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class & Section','school-mgt');?>" ></i>

													</td>

                                                    <td><?php echo get_the_title($retrieved_data->leave_type);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Leave Type','school-mgt');?>"></i></td>

													<td><?php $duration = hrmgt_leave_duration_label($retrieved_data->leave_duration);

																		echo esc_html__($duration,'school-mgt');?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Leave Duration','school-mgt');?>"></i></td>

													<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->start_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Leave Start Date','school-mgt');?>"></i></td>

													<td><?php if(!empty($retrieved_data->end_date)){echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);}else{echo "N/A";}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Leave End Date','school-mgt');?>"></i></td>

													<td>

                                                     <?php

														if($retrieved_data->status == "Approved")

														{

															echo "<span class='green_color'>".esc_html__($retrieved_data->status,'school-mgt')."</span>";

														}

														else

														{

															echo "<span class='red_color'>".esc_html__($retrieved_data->status,'school-mgt')."</span>";

														}



                                                     ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>

													<td><?php

																		$comment =$retrieved_data->reason;

																		$reason = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;

																		echo $reason;

																	?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($comment)){echo $comment;}else{ esc_html_e('Reason','school-mgt');}?>"></i></td>

													<?php

                                                    if($user_access['edit']=='1' || $user_access['delete']=='1')

                                                    { ?>


                                                        <td class="action">

                                                            <div class="smgt-user-dropdown">

                                                                <ul class="" style="margin-bottom: 0px !important;">

                                                                    <li class="">

                                                                        <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

                                                                            <img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

                                                                        </a>

                                                                        <ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

                                                                            <?php

                                                                            if(($retrieved_data->status!='Approved'))

                                                                            {

                                                                                ?>

                                                                                <li class="float_left_width_100 border_bottom_menu">

                                                                                    <a href="#" leave_id="<?php echo $retrieved_data->id ?>" class="float_left_width_100 leave-approve"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/leave_approved.png"?>" style="height:17px;">&nbsp;&nbsp;<?php esc_html_e('Approve', 'school-mgt' ) ;?></a>

                                                                                </li>

                                                                                <?php

                                                                            }

                                                                            if(($retrieved_data->status!='Rejected'))

                                                                            {

                                                                                ?>

                                                                                <li class="float_left_width_100 border_bottom_menu">

                                                                                    <a href="#" leave_id="<?php echo $retrieved_data->id ?>" class="leave-reject float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/leave_rejected.png"?>" style="height:17px;">&nbsp;&nbsp;<?php esc_html_e('Reject', 'school-mgt' ) ;?></a>

                                                                                </li>

                                                                                <?php

                                                                            }

                                                                            if($role == 'admin')

                                                                            {

                                                                                ?>

                                                                                <li class="float_left_width_100 border_bottom_menu">

                                                                                    <a href="?page=smgt_leave&tab=add_leave&action=edit&leave_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

                                                                                </li>

                                                                                <li class="float_left_width_100 ">

                                                                                    <a href="?page=smgt_leave&tab=leave_list&action=delete&leave_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

                                                                                    <i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

                                                                                </li>

                                                                                <?php

                                                                            }

                                                                            else

                                                                            {

                                                                                if($user_access['edit']=='1')

                                                                                {

                                                                                    ?>

                                                                                    <li class="float_left_width_100 border_bottom_menu">

                                                                                        <a href="?dashboard=user&page=leave&tab=add_leave&action=edit&leave_id=<?php echo $retrieved_data->id; ?>" leave_id="'.$retrieved_data->id.'" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

                                                                                    </li>

                                                                                    <?php

                                                                                }

                                                                                if($user_access['delete']=='1')

                                                                                {

                                                                                    ?>

                                                                                    <li class="float_left_width_100 ">

                                                                                        <a href="?dashboard=user&page=leave&tab=leave_list&action=delete&leave_id=<?php echo $retrieved_data->id; ?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

                                                                                        <i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

                                                                                    </li>

                                                                                    <?php

                                                                                }

                                                                            }

                                                                            ?>

                                                                        </ul>



                                                                    </li>

                                                                </ul>

                                                            </div>

                                                        </td>

                                                        <?php

                                                    } ?>

												</tr>

												<?php

												$i++;

											}

											?>

										</tbody>

									</table>

								</form>

							</div>

						</div>

     					<?php

					}

                    else

                    {

                        if($user_access['add']=='1')

                        {

                            ?>

                            <div class="no_data_list_div no_data_img_mt_30px">

                                <a href="<?php echo home_url().'?dashboard=user&page=leave&tab=add_leave';?>">

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

					<!-- Start Panel body -->

					<?php

				}

                if($active_tab == 'add_leave')

	            {

                    ?>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

                    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

                    <script type="text/javascript">

                        $(document).ready(function() {

                            $('#leave_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

                        });

                    </script>



                    <script type="text/javascript">

                        $(document).ready(function() {

                            "use strict";

                            $('.add-search-single-select-js').select2({

                            });

                        })

                    </script>

                    <script type="text/javascript">

                        $(document).ready(function()

                        {    //EVENT VALIDATIONENGINE

                            "use strict";

                            var start = new Date();

                            var end = new Date(new Date().setYear(start.getFullYear()+1));

                            $(".leave_start_date").datepicker(

                            {

                                dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
                                changeYear:true,
			                    changeMonth: true,
                                minDate:0,

                                onSelect: function (selected) {

                                    var dt = new Date(selected);

                                    dt.setDate(dt.getDate() + 0);

                                    $(".leave_end_date").datepicker("option", "minDate", dt);

                                },

                                beforeShow: function (textbox, instance)

                                {

                                    instance.dpDiv.css({

                                        marginTop: (-textbox.offsetHeight) + 'px'

                                    });

                                }

                            });

                            $(".leave_end_date").datepicker(

                            {

                                dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
                                changeYear:true,
			                    changeMonth: true,
                                minDate:0,

                                onSelect: function (selected) {

                                    var dt = new Date(selected);

                                    dt.setDate(dt.getDate() - 0);

                                    $(".leave_start_date").datepicker("option", "maxDate", dt);

                                },

                                beforeShow: function (textbox, instance)

                                {

                                    instance.dpDiv.css({

                                        marginTop: (-textbox.offsetHeight) + 'px'

                                    });

                                }

                            });

                        } );

                    </script>

                        <?php

                        $leave_id=0;

                        if(isset($_REQUEST['leave_id']))

                            $leave_id=$_REQUEST['leave_id'];

                            $edit=0;

                        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

                        {

                            $edit=1;

                            $result = $obj_leave->hrmgt_get_single_leave($leave_id);

                        }
                        $students = mj_smgt_get_student_groupby_class();
                    ?>



                    <!-- Start Panel body -->

                    <div class="panel-body margin_top_20px padding_top_15px_res"><!--------- penal body ------->

                        <!-- Start Leave form -->

                        <form name="leave_form" action="" method="post" class="form-horizontal" id="leave_form">

                            <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

                            <input id="action" type="hidden" name="action" value="<?php echo $action;?>">

                            <input type="hidden" name="leave_id" value="<?php echo $leave_id;?>"  />

                            <input type="hidden" name="status" value="<?php echo "Not Approved";?>"  />

                            <!-- <input type="hidden" name="student_id" value="<?php echo $student_id;?>"  /> -->



                            <div class="header">

                                <h3 class="first_hed"><?php esc_html_e('Leave Information','school-mgt');?></h3>

                            </div>

                            <div class="form-body user_form">

                                <div class="row">

                                    <?php

                                    //------- Leave DATA FOR STUDENT ---------//

                                    if($role=="student")

                                    { ?>

                                        <input value="<?php print get_current_user_id(); ?>" name="student_id" type="hidden" />

                                        <?php

                                    }

                                    else if($role=="parent")

                                    {



                                    ?>

                                    <div class="col-md-6 input single_selecte">

                                            <select class="form-control  display-members line_height_30px max_width_700 validate[required]" name="student_id">

                                                <option value=""><?php _e('Select Student','school-mgt');?></option>

                                                <?php

                                                    if(!empty($school_obj->child_list))

                                                    {

                                                        foreach ($school_obj->child_list as $retrive_data_id)

                                                        {

                                                            $retrive_data= get_userdata($retrive_data_id);

                                                            //$emp_id = get_user_meta($uid, 'student', true);

                                                            echo '<option value="'.$retrive_data->ID.'" '.selected($student,$retrive_data->ID).'>'.mj_smgt_student_display_name_with_roll($retrive_data->ID).'</option>';

                                                        }

                                                    }

                                                ?>

                                            </select>

                                        </div>



                                    <?php

                                    }

                                    else

                                    {

                                        ?>

                                        <div class="col-md-6 input single_selecte">

                                            <select class="form-control add-search-single-select-js display-members line_height_30px max_width_700" name="student_id">

                                                <option value=""><?php _e('Select Student','school-mgt');?></option>

                                                <?php
                                                if($edit)
                                                    $student =$result->student_id;
                                                elseif(isset($_REQUEST['student_id']))
                                                    $student =$_REQUEST['student_id'];
                                                else
                                                    $student = "";
                                                    $studentdata=mj_smgt_get_all_student_list('student');

                                                foreach ($students as $label => $opt){ ?>
                                                    <optgroup label="<?php echo esc_html__('Class :','school-mgt')." ".$label; ?>">
                                                        <?php foreach ($opt as $id => $name): ?>
                                                        <option value="<?php echo $id; ?>" <?php selected($id, $student);  ?> ><?php echo $name; ?></option>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                                    <?php } ?>

                                            </select>

                                        </div>

                                        <?php

                                    }

                                    ?>

                                    <div class="col-md-4 input">

                                        <label class="ml-1 custom-top-label top" for="leave_type"><?php esc_attr_e('Leave Type','school-mgt');?> <span class="require-field">*</span></label>

                                        <select class="form-control line_height_30px validate[required] leave_type width_100" name="leave_type" id="leave_type">

                                            <option value=""><?php esc_html_e('Select Leave Type','school-mgt');?></option>

                                            <?php

                                            if($edit)

                                                $category =$result->leave_type;

                                            elseif(isset($_REQUEST['leave_type']))

                                                $category =$_REQUEST['leave_type'];

                                            else

                                                $category = "";



                                            $activity_category=mj_smgt_get_all_category('leave_type');

                                            if(!empty($activity_category))

                                            {

                                                foreach ($activity_category as $retrive_data)

                                                {

                                                    echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';

                                                }



                                            }

                                            ?>

                                        </select>

                                    </div>

                                    <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 mb-3 rtl_margin_bottom_0px">

                                        <button id="addremove_cat" class="save_btn sibling_add_remove" model="leave_type"><?php esc_attr_e('Add','school-mgt');?></button>

                                    </div>



                                    <div class="col-md-6 res_margin_bottom_20px rtl_margin_top_15px">

                                        <div class="form-group">

                                            <div class="col-md-12 form-control" style="min-height: 47px !important;">

                                                <div class="row padding_radio">

                                                    <div class="input-group">

                                                        <label class="custom-top-label margin_left_0" for="reason"><?php esc_html_e('Leave Duration','school-mgt');?><span class="required">*</span></label>

                                                        <div class="d-inline-block">

                                                            <?php $durationval = ""; if($edit){ $durationval=$result->leave_duration; }elseif(isset($_POST['duration'])) {$durationval=$_POST['duration'];}?>

                                                            <label class="radio-inline">

                                                                <input id="half_day" type="radio" value="half_day" class="tog duration" name="leave_duration" idset ="<?php if($edit) print $result->id; ?>"  <?php  checked( 'half_day', $durationval);  ?>/><?php _e('Half Day','school-mgt');?>

                                                            </label>

                                                            <label class="radio-inline">

                                                                <?php

                                                                if($edit)

                                                                {

                                                                    ?>

                                                                    <input id="full_day" type="radio" value="full_day" class="tog duration" idset ="<?php if($edit) print $result->id; ?>"  name="leave_duration"  <?php  checked( 'full_day', $durationval);  ?> /><?php _e('Full Day','school-mgt');?>

                                                                    <?php

                                                                }

                                                                else

                                                                {

                                                                    ?>

                                                                    <input id="full_day" type="radio" value="full_day" class="tog duration" idset ="<?php if($edit) print $result->id; ?>"  name="leave_duration"  <?php  checked( 'full_day', $durationval);  ?> checked /><?php _e('Full Day','school-mgt');?>

                                                                    <?php

                                                                }

                                                                ?>

                                                            </label>

                                                            <label class="radio-inline margin_left_top">

                                                                <input id="more_then_day" type="radio" idset ="<?php if($edit) print $result->id; ?>" value="more_then_day" class="tog duration" name="leave_duration"  <?php  checked( 'more_then_day', $durationval);  ?>/><?php _e('More Than One Day','school-mgt');?>

                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div id="leave_date" class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

                                        <?php

                                        if($edit)

                                        {

                                            $durationval=$result->leave_duration;

                                            if($durationval == "more_then_day" )

                                            {

                                                ?>

                                                <div class="row">

                                                    <div  class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

                                                        <div class="form-group input">

                                                            <div class="col-md-12 form-control">

                                                                <input id="leave_start_date" class="form-control validate[required] leave_start_date start_date datepicker1" autocomplete="off" type="text"  name="start_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->start_date)));}elseif(isset($_POST['start_date'])) echo mj_smgt_getdate_in_input_box($_POST['start_date']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">

                                                                <label class="active" for="start"><?php esc_html_e('Leave Start Date','school-mgt');?><span class="require-field">*</span></label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div  class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

                                                        <div class="form-group input">

                                                            <div class="col-md-12 form-control">

                                                                <input id="leave_end_date" class="form-control validate[required] leave_end_date start_date datepicker2" type="text"  name="end_date" autocomplete="off" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->end_date)));}elseif(isset($_POST['end_date'])) echo mj_smgt_getdate_in_input_box($_POST['end_date']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">

                                                                <label class="active" for="end"><?php esc_html_e('Leave End Date','school-mgt');?><span class="require-field">*</span></label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <?php

                                            }

                                            else

                                            {

                                                ?>

                                                <div class="form-group input">

                                                    <div class="col-md-12 form-control">

                                                        <input id="leave_start_date" class="form-control validate[required] leave_start_date start_date datepicker1" autocomplete="off" type="text"  name="start_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->start_date)));}elseif(isset($_POST['start_date'])) echo mj_smgt_getdate_in_input_box($_POST['start_date']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">

                                                        <label class="active" for="start"><?php esc_html_e('Leave Start Date','school-mgt');?><span class="require-field">*</span></label>

                                                    </div>

                                                </div>

                                                <?php

                                            }



                                        }

                                        else

                                        {

                                            ?>

                                            <div class="form-group input">

                                                <div class="col-md-12 form-control">

                                                    <input id="leave_start_date" class="form-control validate[required] leave_start_date start_date datepicker1" autocomplete="off" type="text"  name="start_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->start_date)));}elseif(isset($_POST['start_date'])) echo mj_smgt_getdate_in_input_box($_POST['start_date']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">

                                                    <label class="active" for="start"><?php esc_html_e('Leave Start Date','school-mgt');?><span class="require-field">*</span></label>

                                                </div>

                                            </div>

                                             <?php

                                        }

                                        ?>

                                    </div>

                                    <div class="col-md-6 note_text_notice">

                                        <div class="form-group input ">

                                            <div class="col-md-12 note_border margin_bottom_15px_res">

                                                <div class="form-field">

                                                    <textarea id="reason" maxlength="150" class="textarea_height_47px form-control validate[required,custom[address_description_validation]]" name="reason"><?php if($edit){echo $result->reason; }elseif(isset($_POST['reason'])) echo $_POST['reason']; ?> </textarea>

                                                    <span class="txt-title-label"></span>

                                                    <label  class="text-area address active" for="note"><?php esc_attr_e('Reason','school-mgt');?><span class="require-field">*</span></label>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <?php wp_nonce_field( 'save_leave_nonce' ); ?>

                                </div>

                            </div>

                            <div class="form-body user_form ">
                            <?php
                                if(!$edit)
                                {
                                ?>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
                                        <div class="form-group">
                                            <div class="col-md-12 form-control rtl_relative_position">
                                                <div class="row padding_radio">
                                                    <div class="display_flex">
                                                        <label class="custom-top-label label_position_rtl label_right_position" for="smgt_enable_leave_mail"><?php esc_attr_e('Send Mail To Parents & Students','school-mgt');?></label>
                                                        <input type="checkbox" class="check_box_input_margin" name="smgt_enable_leave_mail"  value="1" />&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
                                        <div class="form-group">
                                            <div class="col-md-12 form-control rtl_relative_position">
                                                <div class="row padding_radio">
                                                    <div class="display_flex">
                                                        <label class="custom-top-label label_position_rtl label_right_position" for="smgt_enable_leave_sms_student"><?php esc_attr_e('Enable Send SMS To Student','school-mgt');?></label>
                                                        <input type="checkbox" class="check_box_input_margin" name="smgt_enable_leave_sms_student"  value="1" />&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
                                        <div class="form-group">
                                            <div class="col-md-12 form-control rtl_relative_position">
                                                <div class="row padding_radio">
                                                    <div class="display_flex">
                                                        <label class="custom-top-label label_position_rtl label_right_position" for="smgt_enable_leave_sms_parent"><?php esc_attr_e('Enable Send SMS To Parent','school-mgt');?></label>
                                                        <input type="checkbox" class="check_box_input_margin" name="smgt_enable_leave_sms_parent"  value="1" />&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="form-body user_form">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <input type="submit" value="<?php if($edit){ _e('Save Leave','school-mgt'); }else{ _e('Add Leave','school-mgt');}?>" name="save_leave" class="btn btn-success save_btn <?php if($role!="student"){ echo 'save_leave_validate';} ?>"/>

                                    </div>

                                </div>

                            </div>

                        </form>

                        <!-- End Leave form -->

                    </div>

                    <!-- End Panel body -->

                    <?php

                }

				?>

			</div><!--------- penal body ------->

		</div><!--------- penal White ------->

	</div>

</div>
<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//

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

	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('exam');

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

			if ('exam' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))

			{

				if($user_access_edit=='0')

				{	

					mj_smgt_access_right_page_not_access_message_admin_side();

					die;

				}			

			}

			if ('exam' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))

			{

				if($user_access_delete=='0')

				{	

					mj_smgt_access_right_page_not_access_message_admin_side();

					die;

				}	

			}

			if ('exam' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))

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



?>

<script type="text/javascript">

jQuery(document).ready(function($){

	"use strict";	

	$('#exam_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	 $("#exam_start_date").datepicker({

        dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
		changeYear:true,
		changeMonth: true,
		minDate:0

    });

    $("#exam_end_date").datepicker({

		dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
		changeYear:true,
		changeMonth: true,
	   minDate:0
    });

	jQuery("body").on("change", ".input-file-1[type=file]", function ()

	{ 

		var val = $(this).val().toLowerCase();

			var regex = new RegExp("(.*?)\.(docx|doc|pdf|ppt|jpg|jpeg|png|xls|xlsx|ppt|pptx|gif)$");

			 if(!(regex.test(val)))

			{

				$(this).val('');

				alert("<?php _e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed.','school-mgt');?>");

			} 
	 });



	jQuery('.onlyletter_number_space_validation').on('keypress',function(e) 

	{     

		var regex = new RegExp("^[0-9a-zA-Z \b]+$");

		var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);

		if (!regex.test(key)) 

		{

			event.preventDefault();

			return false;

		} 

   });



	var table =  jQuery('#exam_list').DataTable({

		"initComplete": function(settings, json) {

			$(".print-button").css({"margin-top": "-5%"});

		},
        responsive: true,

		"order": [[ 6, "desc" ]],

		"dom": 'lifrtp',

		"aoColumns":[	                  

	                  {"bSortable": false},

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



	$('#exam_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	$('#exam_form2').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	$('.width_200').DataTable({

		responsive: true,

		bPaginate: false,

		bFilter: false, 

		bInfo: false,

	});



	$( "#save_exam_time" ).on("click",function(e) {

		var subject_data = $("#subject_data").val();

		var suj = JSON.parse(subject_data);

		var productIds = [];

		 jQuery.each( suj, function( i, val ) {

			

			var exdt = $("#exam_date_"+val.subid).val();

			

			var strh = $(".start_time_"+val.subid).val();

			var endh = $(".end_time_"+val.subid).val();

			

			

			var exsdtfull = exdt+strh;

			var exedtfull = exdt+endh;

		

			if ($.inArray(exsdtfull, productIds) == -1) {

				productIds.push(exsdtfull);

			}

		

			if ($.inArray(exedtfull, productIds) == -1) {

				productIds.push(exedtfull);

			}

			

			var strfull = strh;

			var endfull = endh;

			var start_time_new = Converttimeformat_new(strfull);

			var end_time_new = Converttimeformat_new(endfull);

			

			if(strfull != "")

			{

				if (start_time_new >= end_time_new) 

				{

					alert('<?php echo esc_js(__("Subject", "school-mgt"));?>'+' '+val.sub_name+' '+'<?php echo esc_js(__("End time must be greater than start time.", "school-mgt"));?>');

					e.preventDefault(e);

            	}

			}

			else

			{

				$('#exam_form2').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

			}

		});  

	});

	function Converttimeformat(strfull) {

		var hrs = Number(strfull.match(/^(\d+)/)[1]);

		var mnts = Number(strfull.match(/:(\d+)/)[1]);

		var format = strfull.match(/\s(.*)$/)[1];

		if (format == "pm" && hrs < 12) hrs = hrs + 12;

		if (format == "am" && hrs == 12) hrs = hrs - 12;

		var hours = hrs.toString();

		var minutes = mnts.toString();

		if (hrs < 10) hours = "0" + hours;

		if (mnts < 10) minutes = "0" + minutes;

		return hours + ":" + minutes;

	}



	function Converttimeformat_new(strfull) {

		var hrs = Number(strfull.match(/^(\d+)/)[1]);

		var mnts = Number(strfull.match(/:(\d+)/)[1]);

		var format = strfull.match(/\s(.*)$/)[1];

		if (format == "PM" && hrs < 12) hrs = hrs + 12;

		if (format == "AM" && hrs == 12) hrs = hrs - 12;

		var hours = hrs.toString();

		var minutes = mnts.toString();

		if (hrs < 10) hours = "0" + hours;

		if (mnts < 10) minutes = "0" + minutes;

		return hours + ":" + minutes;

	}



	$('#exam_timelist').DataTable({
		//stateSave: true,
		responsive: true,

		bPaginate: false,

		bFilter: false, 

		bInfo: false,

		language:<?php echo mj_smgt_datatable_multi_language();?>

	});

	$('.exam_table').DataTable({

		responsive: true,

		bPaginate: false,

		bFilter: false, 

		bInfo: false,

	});



});

</script>



<!-- POP up code -->



<!-- End POP-UP Code -->

 <?php

	$tablename="exam";

	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')

	{

		$result=mj_smgt_delete_exam($tablename,$_REQUEST['exam_id']);

		if($result){

			wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=3');

		}

	}

	if(isset($_REQUEST['delete_selected']))

	{		

		if(!empty($_REQUEST['id']))

		foreach($_REQUEST['id'] as $id)

		{

			$result=mj_smgt_delete_exam($tablename,$id);

		}

		if($result)

		{ 

			wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=3');

		}

	}

	//-----------SAVE EXAM -------------------------//

	if(isset($_POST['save_exam']))

	{
		
        $nonce = $_POST['_wpnonce'];

		if ( wp_verify_nonce( $nonce, 'save_exam_admin_nonce' ) )
		{

			$created_date = date("Y-m-d H:i:s");

			$examdata=array('exam_name'=>mj_smgt_popup_category_validation(stripslashes($_POST['exam_name'])),

				'class_id'=>$_POST['class_id'],

				'section_id'=>$_POST['class_section'],

				'exam_term'=>$_POST['exam_term'],

				'passing_mark'=>$_POST['passing_mark'],

				'total_mark'=>$_POST['total_mark'],

				'exam_start_date'=>date('Y-m-d', strtotime($_POST['exam_start_date'])),

				'exam_end_date'=>date('Y-m-d', strtotime($_POST['exam_end_date'])),

				'exam_comment'=>mj_smgt_address_description_validation(stripslashes($_POST['exam_comment'])),					

				'exam_creater_id'=>get_current_user_id(),

				'created_date'=>$created_date						

			);		

			 

			if ($_POST['passing_mark'] >= $_POST['total_mark'])

			{

				wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=6');

			}

			else

			{

				//table name without prefix

				$tablename="exam";

				if($_REQUEST['action']=='edit')

				{

					$exam = $_REQUEST['exam_name'];

					

					if(isset($_FILES['exam_syllabus']) && !empty($_FILES['exam_syllabus']) && $_FILES['exam_syllabus']['size'] !=0)

					{		

						if($_FILES['exam_syllabus']['size'] > 0)

							$upload_docs1=mj_smgt_load_documets_new($_FILES['exam_syllabus'],$_FILES['exam_syllabus'],$_POST['document_name']);		

					}

					else

					{

						if(isset($_REQUEST['old_hidden_exam_syllabus']))

						$upload_docs1=$_REQUEST['old_hidden_exam_syllabus'];

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

					 	

					$grade_id=array('exam_id'=>$_REQUEST['exam_id']);

					$modified_date_date = date("Y-m-d H:i:s");

					$examdata['modified_date']=$modified_date_date;

					$examdata['exam_syllabus']=json_encode($document_data);

					$result=mj_smgt_update_record($tablename,$examdata,$grade_id);

					$exam = $examdata['exam_name'];

					school_append_audit_log(''.esc_html__('Exam Updated','hospital_mgt').'('.$exam.')'.'',$_REQUEST['exam_id'],get_current_user_id(),'edit',$_REQUEST['page']);

					if($result)

					{

						wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=2');

					}

				}

				else

				{

					if(isset($_FILES['exam_syllabus']) && !empty($_FILES['exam_syllabus']) && $_FILES['exam_syllabus']['size'] !=0)

					{		

						if($_FILES['exam_syllabus']['size'] > 0)

							$upload_docs1=mj_smgt_load_documets_new($_FILES['exam_syllabus'],$_FILES['exam_syllabus'],$_POST['document_name']);		

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
					$examdata['exam_syllabus']=json_encode($document_data);

				 				

					$result=mj_smgt_insert_record($tablename,$examdata);

					$exam_name = $examdata['exam_name'];

					school_append_audit_log(''.esc_html__('Exam Added','hospital_mgt').'('.$exam_name.')'.'',$result,get_current_user_id(),'insert',$_REQUEST['page']);

					if($result)
					{
						if(empty($_POST['class_section']))
						{
							$class_id =$_POST['class_id'];

							$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));

						}
						else
						{

							$studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_POST['class_section'],

							'meta_query'=> array(array('key' => 'class_name','value' => $_POST['class_id'],'compare' => '=')),'role'=>'student'));
						
						}
						
						if(!empty($studentdata))
						{
							foreach($studentdata as $userdata)
							{
								$student_id = $userdata->ID;

								$student_name = $userdata->display_name;
								
								if(isset($_POST['smgt_enable_exam_mail']) && ($_POST['smgt_enable_exam_mail'] == '1'))
								{
									

									$student_email = $userdata->user_email;

									$add_exam_mailcontent = get_option('add_exam_mailcontent');

									$add_exam_mail_title = get_option('add_exam_mail_title');

									$parent = 	get_user_meta($student_id, 'parent_id', true);

									if($_POST['exam_start_date'] == $_POST['exam_end_date'])
									{
										$start_end_date = mj_smgt_getdate_in_input_box($_POST['exam_start_date']);
									}
									else{
										$start_end_date =  mj_smgt_getdate_in_input_box($_POST['exam_start_date']).' '.esc_attr__('TO','school-mgt').' '.mj_smgt_getdate_in_input_box($_POST['exam_end_date']);
									}
									// ADD EXAM MAIL SEND TO PARENT
									if(!empty($parent))
									{
										foreach($parent as $p)
										{
											$user_info	 	=    get_userdata($p);

											$email_to 	=	 $user_info->user_email;	

											$searchArr = array();

											$parerntdata = get_user_by('email',$email_to);							

											$searchArr['{{user_name}}']	=	$parerntdata->display_name;

											$searchArr['{{exam_name}}']   =  mj_smgt_popup_category_validation(stripslashes($_POST['exam_name']));

											$searchArr['{{exam_start_end_date}}']   =  $start_end_date;
											if(!empty($_POST['exam_comment']))
											{
												$comment =  mj_smgt_address_description_validation(stripslashes($_POST['exam_comment']));
											}
											else
											{
												$comment = "N/A";
											}
											$searchArr['{{exam_comment}}']   = $comment;

											$searchArr['{{school_name}}']	=	get_option('smgt_school_name');

											$message = mj_smgt_string_replacement($searchArr,$add_exam_mailcontent);

											if(!empty($document_data[0]))
											{
												$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$document_data[0]['value'];
											}
											else
											{
												$attechment = "";
											}
											
											$mail = mj_smgt_send_mail_for_homework($email_to,$add_exam_mail_title,$message,$attechment); 
											

										}
									}
									// ADD EXAM MAIL SEND TO STUDENT
									$string = array();

									$string['{{user_name}}']	=	$student_name;

									$string['{{exam_name}}']   =  mj_smgt_popup_category_validation(stripslashes($_POST['exam_name']));

									$string['{{exam_start_end_date}}']   =  $start_end_date;
									if(!empty($_POST['exam_comment']))
									{
										$comment =  mj_smgt_address_description_validation(stripslashes($_POST['exam_comment']));
									}
									else
									{
										$comment = "N/A";
									}
									$string['{{exam_comment}}']   = $comment;

									$string['{{school_name}}']	=	get_option('smgt_school_name');

									$message = mj_smgt_string_replacement($string,$add_exam_mailcontent);

									if(!empty($document_data[0]))
									{
										$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$document_data[0]['value'];
									}
									else
									{
										$attechment = "";
									}
											
									$mail = mj_smgt_send_mail_for_homework($student_email,$add_exam_mail_title,$message,$attechment);
								}

								if(isset($_POST['smgt_enable_exam_sms_student']) && ($_POST['smgt_enable_exam_sms_student'] == '1'))
								{
									$message_content = 'This is a reminder that your upcoming exam '.mj_smgt_popup_category_validation(stripslashes($_POST['exam_name'])).' is scheduled for '.$start_end_date.' At '.get_option('smgt_school_name');
									$type = "Add Exam";
									MJ_smgt_send_sms_notification($student_id,$type,$message_content);
								}

								if(isset($_POST['smgt_enable_exam_sms_parent']) && ($_POST['smgt_enable_exam_sms_parent'] == '1'))
								{
									$parent = 	get_user_meta($student_id, 'parent_id', true);
									if(!empty($parent))
									{
										foreach($parent as $p)
										{
											$message_content = 'We would like to inform you that your child '.$student_name.' will have an important exam '.mj_smgt_popup_category_validation(stripslashes($_POST['exam_name'])).' on '.$start_end_date.' At '.get_option('smgt_school_name');
											$type = "Add Exam";
											MJ_smgt_send_sms_notification($p,$type,$message_content);
										}
									}
									
								}
							}
						}
						
						wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=1');

					}				

				}

			}

		}		

	}

	// save Exam Time Table 

	if(isset($_POST['save_exam_table'])) 

	{	



		$obj_exam=new smgt_exam;

		$class_id=	$_POST['class_id'];

		$section_id=$_POST['section_id'];

		$exam_id=$_POST['exam_id'];



		if(isset($_POST['section_id']) && $_POST['section_id'] !=0) //-----Section ID Not Empty ---------// 

		{

			$subject_data=$obj_exam->mj_smgt_get_subject_by_section_id($class_id,$section_id);

		}

		else //-----Section ID Empty ---------// 

		{ 

			$subject_data=$obj_exam->mj_smgt_get_subject_by_class_id($class_id);

		}

	

		if(!empty($subject_data))

		{

			foreach($subject_data as $subject)

			{	

				if(isset($_POST['subject_name_'.$subject->subid]))

				{

					$save_data = $obj_exam->mj_smgt_insert_sub_wise_time_table($class_id,$exam_id,$subject->subid,$_POST['exam_date_'.$subject->subid],$_POST['start_time_'.$subject->subid],$_POST['end_time_'.$subject->subid]);

				}

			}

			

			if($save_data)

			{ 

				wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=exam_time_table&message=5');

			}

		}

		

	}  

	

	

	$active_tab = isset($_GET['tab'])?$_GET['tab']:'examlist';

?>

<div class="page-inner"> <!-------  page inner -------->

	<div  id="" class="grade_page main_list_margin_5px">

		<?php

		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

		switch($message)

		{

			case '1':

				$message_string = esc_attr__('Exam Added Successfully.','school-mgt');

				break;

			case '2':

				$message_string = esc_attr__('Exam Updated Successfully.','school-mgt');

				break;	

			case '3':

				$message_string = esc_attr__('Exam Deleted Successfully.','school-mgt');

				break;

			case '4':

				$message_string = esc_attr__('This File Type Is Not Allowed, Please Upload Only Pdf File.','school-mgt');

				break;

			case '5':

				$message_string = esc_attr__('Exam Time Table Saved Successfully.','school-mgt');

				break;

			case '6':

				$message_string = esc_attr__('Enter Total Marks Greater than Passing Marks.','school-mgt');

				break;

		}

		

		if($message)

		{ ?>

		<div id="message" class="rtl_message_display_inline_block alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">

			<p><?php echo $message_string;?></p>

			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>

		</div>

		<?php } ?>

	

		<div class="panel-white"> <!------- penal white  -------->

			<div class="panel-body">    <!-------- Penal Body --------->

				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">

					<li class="<?php if($active_tab=='examlist'){?>active<?php }?>">			

						<a href="?page=smgt_exam&tab=examlist" class="padding_left_0 tab <?php echo $active_tab == 'examlist' ? 'active' : ''; ?>">

						<?php esc_html_e('Exam List', 'school-mgt'); ?></a> 

					</li>

					<?php 

					$action = "";

					if(!empty($_REQUEST['action']))

					{

						$action = $_REQUEST['action'];

					}

					if($active_tab == 'addexam')

					{

						if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

						{	?>

						<li class="<?php if($active_tab=='addexam' || $_REQUEST['action'] == 'edit'){?>active<?php }?>">

							<a href="#" class="padding_left_0 tab <?php echo $active_tab == 'addexam' ? 'nav-tab-active' : ''; ?>">

							<?php esc_attr_e('Edit Exam', 'school-mgt'); ?></a>  

						</li> 

							<?php 

						}

						else

						{	?>

								<li class="<?php if($active_tab=='addexam'){?>active<?php }?>">

									<a href="#" class="padding_left_0 tab <?php echo $active_tab == 'addexam' ? 'nav-tab-active' : ''; ?>">

									<?php echo esc_attr__('Add Exam', 'school-mgt'); ?></a>  

								</li> 

							<?php 

						} 

					}

					?>

					<li class="<?php if($active_tab=='exam_time_table'){?>active<?php }?>">

						<a href="?page=smgt_exam&tab=exam_time_table" class="padding_left_0 tab <?php echo $active_tab == 'exam_time_table' ? 'active' : ''; ?>">

						<?php esc_html_e('Exam Time Table', 'school-mgt'); ?></a> 

					</li>

					

					<?php

					if($action == 'view')

					{ ?>  

						<li class="<?php if($active_tab=='viewexam'){?>active<?php }?>">

							<a href="admin.php?page=smgt_exam&tab=viewexam&action=view&exam_id=<?php echo $_REQUEST['exam_id']; ?>" class="padding_left_0 tab <?php echo $active_tab == 'viewexam' ? 'active' : ''; ?>">

							<?php esc_html_e('View Exam Time Table', 'school-mgt'); ?></a> 

						</li>  

						<?php

					}

					?>

				</ul> 

				<?php

				// Exam List datatable 

				if($active_tab == 'examlist')

				{	
					?>
					<div class="popup-bg">

						<div class="overlay-content">

							<div class="modal-content">

								<div class="view_popup"></div>     
								<div class="category_list"></div>   
							</div>

						</div>    

					</div>
					<?php
					if(get_option('smgt_enable_video_popup_show') == 'yes')
					{
					?>
					<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/AqXYwh_8o04?si=w1NY42aZWl8eOvtd";?>" title="Conduct School Examination">
						<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
					</a>
					<?php
					}
					$retrieve_class = mj_smgt_get_all_data($tablename);

					if(!empty($retrieve_class))

					{

						?>

						<div class="">

							<div class="table-responsive"><!-------- Table Responsive --------->

								<!-------- Exam List Form --------->

								<form id="frm-example" name="frm-example" method="post">

									<table id="exam_list" class="display" cellspacing="0" width="100%">

										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

											<tr>

												<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>

												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

												<th><?php esc_attr_e('Exam Name','school-mgt');?></th>

												<th><?php esc_attr_e('Class Name','school-mgt');?></th>

												<th><?php esc_attr_e('Exam Term','school-mgt');?></th>

												<th><?php esc_attr_e('Exam Start Date','school-mgt');?></th>

												<th><?php esc_attr_e('Exam End Date','school-mgt');?></th>

												<th><?php esc_attr_e('Exam Comment','school-mgt');?></th>

												<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

											</tr>

										</thead>

										<tbody>

											<?php 

											$i=0;

											foreach ($retrieve_class as $retrieved_data)

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

													<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->exam_id;?>"></td>

													<td class="user_image width_50px profile_image_prescription padding_left_0">

														<a href="#" class="color_black view_details_popup" id="<?php echo $retrieved_data->exam_id;?>" type="Exam_view" >

															<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Exam_hall.png"?>" alt="" class="massage_image center">

															</p>

														</a>

													</td>

													<td>

														<a href="#" class="color_black view_details_popup" id="<?php echo $retrieved_data->exam_id;?>" type="Exam_view" >

															<?php echo $retrieved_data->exam_name;?>

														</a> 

														<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Name','school-mgt');?>"></i>

													</td>

													<td><?php echo smgt_get_class_section_name_wise($retrieved_data->class_id,$retrieved_data->section_id); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

													<td><?php 
														
													if(!empty(get_the_title($retrieved_data->exam_term)))

													{
														
														echo get_the_title($retrieved_data->exam_term);

													}

													else

													{

														echo  "N/A";

													}

													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Term','school-mgt');?>"></i></td>

													<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_start_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Start Date','school-mgt');?>"></i></td>

													<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_end_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam End Date','school-mgt');?>"></i></td>

													<?php

													$comment =$retrieved_data->exam_comment;

													$exam_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;

													?>

													<td><?php if($retrieved_data->exam_comment){ echo stripslashes($exam_comment); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($comment)){ echo $comment;}else{ esc_html_e('Exam Comment','school-mgt'); } ?>"></i></td>              

													<td class="action">  

														<div class="smgt-user-dropdown">

															<ul class="" style="margin-bottom: 0px !important;">

																<?php

																if(!empty($retrieved_data->exam_syllabus))

																{

																	$doc_data=json_decode($retrieved_data->exam_syllabus);

																}

																?>

																<li class="">

																	<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

																	</a>

																	<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																		<li class="float_left_width_100 ">

																			<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->exam_id;?>" type="Exam_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View Exam Detail','school-mgt');?></a>

																		</li>

																		<li class="float_left_width_100 ">

																			<a href="?page=smgt_exam&tab=viewexam&action=view&exam_id=<?php echo $retrieved_data->exam_id;?>" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/timetable-icon.png"?>" alt="" style="height:15px;" class="">&nbsp;&nbsp;<?php esc_attr_e('Time Table Detail','school-mgt');?></a>

																		</li>

																		<?php

																		if(!empty($doc_data[0]->value))

																		{

																			?>

																			<!-- <li class="float_left_width_100">

																				<a download href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>"  class="status_read float_left_width_100" record_id="<?php echo $retrieved_data->exam_id;?>"><i class="fa fa-download"></i><?php esc_html_e(' Download Syllabus', 'school-mgt');?></a>

																			</li> -->

																			<li class="float_left_width_100">

																				<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="status_read float_left_width_100" record_id="<?php echo $retrieved_data->exam_id;?>"><i class="fa fa-eye"></i><?php esc_html_e('View Syllabus', 'school-mgt');?></a>

																			</li>

																			<?php

																		}

																		if($user_access_edit == '1')

																		{

																			?>

																			<li class="float_left_width_100 border_bottom_menu">

																				<a href="?page=smgt_exam&tab=addexam&action=edit&exam_id=<?php echo $retrieved_data->exam_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																			</li>

																			<?php 

																		} 

																		if($user_access_delete =='1')

																		{

																			?>

																			<li class="float_left_width_100 ">

																				<a href="?page=smgt_exam&tab=examlist&action=delete&exam_id=<?php echo $retrieved_data->exam_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

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

									<div class="print-button pull-left">

										<button class="btn-sms-color button_reload">

											<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->exam_id); ?>" style="margin-top: 0px;">

											<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

										</button>

										<?php if($user_access_delete =='1')

										{ 

											?>

											<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

											<?php

										}

										?>

									</div>

								</form><!-------- Exam List Form --------->

							</div><!-------- Table Responsive --------->

						</div>

						<?php 

					}

					else

					{

						if($user_access_add=='1')

						{

							?>

							<div class="no_data_list_div no_data_img_mt_30px"> 

								<a href="<?php echo admin_url().'admin.php?page=smgt_exam&tab=addexam';?>">

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

				//---------- View Exam Tab  ---------------//

				if($active_tab == 'viewexam')

				{

					if($_REQUEST['action']=='view')

					{

						$exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);

						$start_date=$exam_data->exam_start_date;

						$end_date=$exam_data->exam_end_date;

						$obj_exam=new smgt_exam;

						$exam_time_table=$obj_exam->mj_smgt_get_exam_time_table_by_exam($_REQUEST['exam_id']);

					}

					

					?>

					<div class="panel-body margin_top_20px padding_top_25px_res"> <!--------- penal body ----------->

						<div class="form-group">

							<div class="col-md-12 rtl_padding_left_right_0px_for_btn">

								<div class="exam_table_res view_exam_timetable_div">

									<table style="border: 1px solid #D9E1ED;text-align: center;margin-bottom: 0px;" class="width_100" >

										<thead>

											<tr>

												<th class="exam_hall_receipt_table_heading" style="border-top: medium none;border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Exam','school-mgt');?></th>

												<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Class','school-mgt');?></th>							

												<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Section','school-mgt');?></th>							

												<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Term','school-mgt');?></th>							

												<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Start Date','school-mgt');?></th>							

												<th class="exam_hall_receipt_table_heading" style="background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('End Date','school-mgt');?></th>							

											</tr>

										</thead>

										<tfoot></tfoot>

										<tbody>							

											<tr>

												<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo $exam_data->exam_name;?></td>							

												<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_get_class_name($exam_data->class_id);?></td>

												<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php if($exam_data->section_id!=0){ echo mj_smgt_get_section_name($exam_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>

												<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;">
													<?php 	if(!empty(get_the_title($exam_data->exam_term)))
															{
																echo get_the_title($exam_data->exam_term);
															}
															else
															{
																echo  "N/A";
															}
															?></td>

												<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($start_date);?></td>

												<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($end_date);?></td>

											</tr>

										</tbody>

									</table>

								</div>

							</div>			

						</div>

						<?php

						if(!empty($exam_time_table))

						{

							?>

							<div class="col-md-12 margin_top_40">

								<div class="exam_table_res view_exam_timetable_div">

									<table style="border: 1px solid #D9E1ED;text-align: center;margin-bottom: 0px;" class="width_100" >

										<thead>

											<tr>    

												<th class="exam_hall_receipt_table_heading" style="border-top: medium none;border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Subject Code','school-mgt');?></th>

												<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Subject Name','school-mgt');?></th>

												<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Exam Date','school-mgt');?></th>

												<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Exam Start Time','school-mgt');?></th>

												<th class="exam_hall_receipt_table_heading" style="background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Exam End Time','school-mgt');?></th>

											</tr>

										</thead>

										<tbody>

											<?php

											if(!empty($exam_time_table))

											{

												foreach($exam_time_table  as $retrieved_data)

												{

												?>

													<tr style="border: 1px solid #D9E1ED;">

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_get_single_subject_code($retrieved_data->subject_id); ?> </td>

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_get_single_subject_name($retrieved_data->subject_id);  ?> </td>

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_date); ?> </td>

														<?php

														$start_time_data = explode(":", $retrieved_data->start_time);

														$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

														$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);

														$start_am_pm=$start_time_data[2];

														$start_time=$start_hour.':'.$start_min.' '.$start_am_pm;

														

														$end_time_data = explode(":", $retrieved_data->end_time);

														$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

														$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);

														$end_am_pm=$end_time_data[2];

														$end_time=$end_hour.':'.$end_min.' '.$end_am_pm;

														?>

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo $start_time;?> </td>

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo $end_time; ?> </td>

													</tr>

												<?php 

												}

											}

											?>

										</tbody>

									</table>

								</div>

							</div>

							<?php

						}

						else

						{

							?>

							<div style="margin-top:20px !important;" id="message" class="rtl_message_display_inline_block alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">

								<p><?php esc_html_e('No Any Time Table', 'school-mgt'); ?></p>

								<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>

							</div>

							<?php

						}

						?>

					</div><!--------- penal body ----------->

					<?php

				}

				if($active_tab == 'addexam')

				{

					require_once SMS_PLUGIN_DIR. '/admin/includes/exam/add-exam.php';

				}

				if($active_tab == 'exam_time_table')

				{

					require_once SMS_PLUGIN_DIR. '/admin/includes/exam/exam_time_table.php';

				}

				?>

			</div>  <!-------- Penal Body --------->

		</div><!------- penal white  -------->

	</div>

</div> <!-------  page inner -------->
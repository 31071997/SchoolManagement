<?php 

$role_name=mj_smgt_get_user_role(get_current_user_id());

if(!empty($_REQUEST['student_id']))
{
	$class_id=get_user_meta($_REQUEST['student_id'], 'class_name',true);
	$section_name=get_user_meta($_REQUEST['student_id'], 'class_section',true);
}

$custom_field_obj =new Smgt_custome_field;

$module='student';	

$user_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
?>

<script type="text/javascript">

	jQuery(document).ready(function($)

	{

		

		"use strict";	

		jQuery('#students_list').DataTable({
			//stateSave: true,
			"initComplete": function(settings, json) {

				$(".print-button").css({"margin-top": "-5%"});

			},

			"ordering": true,
			"dom": 'lifrtp',

			"aoColumns":[

					<?php

						if($role_name == "supportstaff")

						{

							?>

							{"bSortable": false},

							<?php

						}

						?>

						{"bSortable": false},

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": true},
						<?php
						if(!empty($user_custom_field)){
							foreach($user_custom_field as $custom_field)
							{
								if($custom_field->show_in_table == "1")
								{
									?>
									{"bSortable": true}, 
									<?php
								}
							}
						}
						?>
						
						{"bSortable": false} ],

			language:<?php echo mj_smgt_datatable_multi_language();?>	

		});

		$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

		//------------ SELECT ALL CHECKBOX JS -----------------//

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

		//------------- DELETE SELECTED CONFIRM MESSAGE JS -----------------//

		$(".delete_selected").on('click', function()

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

		$('#student_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	 

		$('#birth_date').datepicker({

			maxDate : 0,

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",

			changeMonth: true,

			changeYear: true,

			yearRange:'-65:+25',

			onChangeMonthYear: function(year, month, inst) {

				$(this).val(month + "/" + year);

			}

		});



		$('.space_validation').on('keypress',function( e ) 

		{

			if(e.which === 32) 

				return false;

		});									

		//custom field datepicker

		$('.after_or_equal').datepicker({

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",										

			minDate:0,

			changeMonth: true,

			changeYear: true,
			beforeShow: function (textbox, instance) 

			{

				instance.dpDiv.css({

					marginTop: (-textbox.offsetHeight) + 'px'                   

				});

			}

		}); 

		$('.date_equals').datepicker({

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",

			minDate:0,

			maxDate:0,	

			changeMonth: true,

			changeYear: true,
			beforeShow: function (textbox, instance) 

			{

				instance.dpDiv.css({

					marginTop: (-textbox.offsetHeight) + 'px'                   

				});

			}

		}); 

		$('.before_or_equal').datepicker({

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",

			maxDate:0,

			changeMonth: true,

			changeYear: true,
			
			beforeShow: function (textbox, instance) 

			{

				instance.dpDiv.css({

					marginTop: (-textbox.offsetHeight) + 'px'                   

				});

			}

		}); 



		var table =  jQuery('#exam_list').DataTable({

			
			//stateSave: true,
			"aoColumns":[	                  

				{"bSortable": true},

				{"bSortable": false}],

			language:<?php echo mj_smgt_datatable_multi_language();?>

		});



		$(".view_more_details_div").on("click", ".view_more_details", function(event)

		{

			$('.view_more_details_div').removeClass("d-block");

			$('.view_more_details_div').addClass("d-none");



			$('.view_more_details_less_div').removeClass("d-none");

			$('.view_more_details_less_div').addClass("d-block");



			$('.user_more_details').removeClass("d-none");

			$('.user_more_details').addClass("d-block");



		});		

		$(".view_more_details_less_div").on("click", ".view_more_details_less", function(event)

		{

			$('.view_more_details_div').removeClass("d-none");

			$('.view_more_details_div').addClass("d-block");



			$('.view_more_details_less_div').removeClass("d-block");

			$('.view_more_details_less_div').addClass("d-none");



			$('.user_more_details').removeClass("d-block");

			$('.user_more_details').addClass("d-none");

		});		



		var table =  jQuery('#parents_list').DataTable({



					"order": [[ 0, "asc" ]],

					"aoColumns":[	                  

					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": true},

					{"bSortable": true}],	

					language:<?php echo mj_smgt_datatable_multi_language();?>						

				});

					

		$(".sdate").datepicker({

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",

			changeYear: true,

			changeMonth: true,

			maxDate:0,

			onSelect: function (selected) {

				var dt = new Date(selected);

				dt.setDate(dt.getDate() + 0);

				$(".edate").datepicker("option", "minDate", dt);

			}

		});

		$(".edate").datepicker({

		dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",

		changeYear: true,

		changeMonth: true,

		maxDate:0,

			onSelect: function (selected) {

				var dt = new Date(selected);

				dt.setDate(dt.getDate() - 0);

				$(".sdate").datepicker("option", "maxDate", dt);

			}

		});



		var table =  jQuery('#attendance_list').DataTable({


			//stateSave: true,
			"order": [[ 0, "asc" ]],

			dom: 'Bfrtip',

				buttons: [

				{

			extend: 'print',

			title: 'View Attendance',},

			{

			extend: 'pdf',

			title: 'View Attendance',

			}

				],

			"aoColumns":[	                  

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},

			{"bSortable": true},	           

			{"bSortable": false}],

			language:<?php echo mj_smgt_datatable_multi_language();?>							

		});			

	});

	//Custom Field File Validation//

	function Smgt_custom_filed_fileCheck(obj)

	{	

	"use strict";

		var fileExtension = $(obj).attr('file_types');

		var fileExtensionArr = fileExtension.split(',');

		var file_size = $(obj).attr('file_size');

		

		var sizeInkb = obj.files[0].size/1024;

		

		if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionArr) == -1)

		{										

			alert("Only "+fileExtension+" formats are allowed.");

			$(obj).val('');

		}	

		else if(sizeInkb > file_size)

		{										

			alert("Only "+file_size+" kb size is allowed.");

			$(obj).val('');	

		}

	}



	function fileCheck(obj) 

	{

		var fileExtension = ['jpeg', 'jpg', 'png', 'bmp',''];

		if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)

		{

			alert(language_translate2.image_forame_alert);

			$(obj).val('');

		}	

	}

</script>

<script type="text/javascript">

$(document).ready(function()



{

	var qr_code_urlnew =JSON.stringify({"user_id": '<?php echo $_REQUEST['student_id'];?>',"class_id":'<?php echo $class_id;?>',"section_id":'<?php echo $section_name;?>',"qr_type":"schoolqr"});



	var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + qr_code_urlnew + '&amp;size=50x50';



	$('.id_card_barcode').attr('src', url);



});

</script>

<?php 

//-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();


$obj_mark = new Marks_Manage();

$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'studentlist';

$role='student';

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

//--------------- SAVE STUDENT -------------------//

if(isset($_POST['save_student']))

{

	

	$nonce = $_POST['_wpnonce'];

	if ( wp_verify_nonce( $nonce, 'save_student_frontend_nonce' ) )

	{

		$firstname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);

		$middlename = mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']);

		$lastname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['last_name']);

		$userdata = array(

			'user_login'=>mj_smgt_email_validation($_POST['email']),

			'user_nicename'=>NULL,

			'user_email'=>mj_smgt_email_validation($_POST['email']),

			'user_url'=>NULL,

			'display_name'=>$firstname." ".$middlename." ".$lastname,

		);

		

		if($_POST['password'] != "")

			$userdata['user_pass']=strip_tags($_POST['password']);

		

		if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)

		{

			if($_FILES['upload_user_avatar_image']['size'] > 0)

				$member_image=mj_smgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');

				$photo=content_url().'/uploads/school_assets/'.$member_image;

		}

		else

		{

			if(isset($_REQUEST['hidden_upload_user_avatar_image']))

			$member_image=$_REQUEST['hidden_upload_user_avatar_image'];

			$photo=$member_image;

		}

		

		$usermetadata=array('roll_id'=>mj_smgt_strip_tags_and_stripslashes($_POST['roll_id']),

				'middle_name'=>mj_smgt_strip_tags_and_stripslashes($_POST['middle_name']),

				'gender'=>$_POST['gender'],

				'birth_date'=>$_POST['birth_date'],

				'address'=>mj_smgt_strip_tags_and_stripslashes($_POST['address']),

				'city'=>mj_smgt_strip_tags_and_stripslashes($_POST['city_name']),

				'state'=>mj_smgt_strip_tags_and_stripslashes($_POST['state_name']),

				'zip_code'=>mj_smgt_strip_tags_and_stripslashes($_POST['zip_code']),

				'class_name'=>$_POST['class_name'],

				'class_section'=>$_POST['class_section'],

				'phone'=>$_POST['phone'],

				'mobile_number'=>$_POST['mobile_number'],

				'alternet_mobile_number'=>$_POST['alternet_mobile_number'],

				'smgt_user_avatar'=>$photo,

				'created_by'=>get_current_user_id()



		);

		 

		$userbyroll_no=get_users(

				array('meta_query'=>

						array('relation' => 'AND',

							array('key'=>'class_name','value'=>$_POST['class_name']),

							array('key'=>'roll_id','value'=>mj_smgt_strip_tags_and_stripslashes($_POST['roll_id']))

						),

						'role'=>'student'));

		$is_rollno = count($userbyroll_no);

		

		if($_REQUEST['action']=='edit')

		{

			$userdata['ID']=$_REQUEST['student_id'];

			$result=mj_smgt_update_user($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);

			// Custom Field File Update //

			$custom_field_file_array=array();

				

			if(!empty($_FILES['custom_file']['name']))

			{

				$count_array=count($_FILES['custom_file']['name']);

					

				for($a=0;$a<$count_array;$a++)

				{			

					foreach($_FILES['custom_file'] as $image_key=>$image_val)

					{

						foreach($image_val as $image_key1=>$image_val2)

						{

							if($_FILES['custom_file']['name'][$image_key1]!='')

							{ 

								$custom_file_array[$image_key1]=array(

								'name'=>$_FILES['custom_file']['name'][$image_key1],

								'type'=>$_FILES['custom_file']['type'][$image_key1],

								'tmp_name'=>$_FILES['custom_file']['tmp_name'][$image_key1],

								'error'=>$_FILES['custom_file']['error'][$image_key1],

								'size'=>$_FILES['custom_file']['size'][$image_key1]

								);							

							}						

						}

					}

				}	

				if(!empty($custom_file_array))

				{

					foreach($custom_file_array as $key=>$value)		

					{

								

						global $wpdb;

						$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';

		

						$get_file_name=$custom_file_array[$key]['name'];

						

						$custom_field_file_value=mj_smgt_load_documets_new($value,$value,$get_file_name);	

											

						//Add File in Custom Field Meta//				

						$module='student';					

						$updated_at=date("Y-m-d H:i:s");

						$update_custom_meta_data =$wpdb->query($wpdb->prepare("UPDATE `$wpnc_custom_field_metas` SET `field_value` = '$custom_field_file_value',updated_at='$updated_at' WHERE `$wpnc_custom_field_metas`.`module` = %s AND  `$wpnc_custom_field_metas`.`module_record_id` = %d AND `$wpnc_custom_field_metas`.`custom_fields_id` = %d",$module,$result,$key));

					} 	

				}		 		

			}

			$update_custom_field=$custom_field_obj->mj_smgt_update_custom_field_metas('student',$_POST['custom'],$result);

			if($result)

			{ 

				wp_redirect ( home_url() . '?dashboard=user&page=student&&message=2'); 	

			}

		}

		else

		{

			if( !email_exists( $_POST['email'] )) 

			{			

				if($is_rollno)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=student&&message=3'); 	

				}

				else

				{

					$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);

					

					// Custom Field File Insert //

					$custom_field_file_array=array();

					if(!empty($_FILES['custom_file']['name']))

					{

						$count_array=count($_FILES['custom_file']['name']);

						

						for($a=0;$a<$count_array;$a++)

						{			

							foreach($_FILES['custom_file'] as $image_key=>$image_val)

							{

								foreach($image_val as $image_key1=>$image_val2)

								{

									if($_FILES['custom_file']['name'][$image_key1]!='')

									{  	

										$custom_file_array[$image_key1]=array(

										'name'=>$_FILES['custom_file']['name'][$image_key1],

										'type'=>$_FILES['custom_file']['type'][$image_key1],

										'tmp_name'=>$_FILES['custom_file']['tmp_name'][$image_key1],

										'error'=>$_FILES['custom_file']['error'][$image_key1],

										'size'=>$_FILES['custom_file']['size'][$image_key1]

										);							

									}	

								}

							}

						}			

						if(!empty($custom_file_array))

						{

							foreach($custom_file_array as $key=>$value)		

							{	

								global $wpdb;

								$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';

				

								$get_file_name=$custom_file_array[$key]['name'];	

								

								$custom_field_file_value=mj_smgt_load_documets_new($value,$value,$get_file_name);		

								

								//Add File in Custom Field Meta//

								$custom_meta_data['module']='student';

								$custom_meta_data['module_record_id']=$result;

								$custom_meta_data['custom_fields_id']=$key;

								$custom_meta_data['field_value']=$custom_field_file_value;

								$custom_meta_data['created_at']=date("Y-m-d H:i:s");

								$custom_meta_data['updated_at']=date("Y-m-d H:i:s");	

								 

								$insert_custom_meta_data=$wpdb->insert($wpnc_custom_field_metas, $custom_meta_data );

								 

							} 	

						}		 		

					}

					$add_custom_field=$custom_field_obj->mj_smgt_add_custom_field_metas('student',$_POST['custom'],$result);					

					if($result)

					{ 

						wp_redirect ( home_url() . '?dashboard=user&page=student&&message=1'); 	

					}

				}

			}

			else

			{

				wp_redirect ( home_url() . '?dashboard=user&page=student&&message=4'); 	

			}	 

		}

	}

}

//----------------- MULTIPLE STUDENT DELETED ----------------//

if(isset($_REQUEST['delete_selected']))

{		

	if(!empty($_REQUEST['id']))

	{

		foreach($_REQUEST['id'] as $id)

		{

			$childs=get_user_meta($id, 'parent_id', true);			

			if(!empty($childs))

			{

				foreach($childs as $key=>$childvalue)

				{						

					$parents=get_user_meta($childvalue, 'child',true);						

					if(!empty($parents))

					{

						if(($key = array_search($id, $parents)) !== false)

						{

							unset($parents[$key]);						

							update_user_meta( $childvalue,'child', $parents );								

						}						

					}					

				}

			}			

			$result=mj_smgt_delete_usedata($id);

			if($result)

			{

				wp_redirect ( home_url() . '?dashboard=user&page=student&tab=studentlist&message=5');

			}

		}

	}

	

}

// -----------Delete Student -------- //

if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')

{		

	$childs=get_user_meta($_REQUEST['student_id'], 'parent_id', true);

	if(!empty($childs))

	{

		foreach($childs as $key=>$childvalue)

		{					

			$parents=get_user_meta($childvalue, 'child',true);					

			if(!empty($parents))

			{

				if(($key = array_search($_REQUEST['student_id'], $parents)) !== false) 

				{

					unset($parents[$key]);						

					update_user_meta( $childvalue,'child', $parents );							

				}					

			}				

		}

	}

		

	$result=mj_smgt_delete_usedata($_REQUEST['student_id']);

	if($result)

	{

		wp_redirect ( home_url() . '?dashboard=user&page=student&tab=studentlist&message=5');

	}

}

$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

switch($message)

{

	case '1':

		$message_string = esc_attr__('Student Added Successfully.','school-mgt');

		break;

	case '2':

		$message_string = esc_attr__('Student Updated Successfully.','school-mgt');

		break;

	case '3':

		$message_string = esc_attr__('Roll No Already Exist.','school-mgt');

		break;

	case '4':

		$message_string = esc_attr__('Student Username Or Emailid Already Exist.','school-mgt');

		break;

	case '5':

		$message_string = esc_attr__('Student Deleted Successfully.','school-mgt');

		break;

	case '6':

		$message_string = esc_attr__('Student CSV Uploaded Successfully .','school-mgt');

		break;

	case '7':

		$message_string = esc_attr__('Student Activated Auccessfully.','school-mgt');

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

<!-- POP up code -->	



<!-- POP up code -->	

<div class="panel-body panel-white frontend_list_margin_30px_res"><!------------ PENAL BODY ------------>

	<div class="">

		<?php 

		//--------------- STUDENT LIST TAB ------------//

		if($active_tab == 'studentlist')

		{

			?>

			<div class="popup-bg">

				<div class="overlay-content max_height_overflow">   

					<div class="result"></div>

					<div class="view-parent"></div>   

				</div> 

			</div>

			<?php

			if(isset($_REQUEST['filter_class']) )

			{

				$exlude_id = mj_smgt_approve_student_list();

				if(empty($_REQUEST['class_id']) && empty($_REQUEST['class_section']))

				{

					$exlude_id = mj_smgt_approve_student_list();

					$studentdata =get_users(array('role'=>'student'));

					

				}

				elseif(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != "")

				{

					$class_id =$_REQUEST['class_id'];

					$class_section =$_REQUEST['class_section'];

					 $studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$class_section,

						'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));

				}

				elseif(isset($_REQUEST['class_id']) && $_REQUEST['class_section'] == "")

				{

					$class_id =$_REQUEST['class_id'];

					$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));	

				}	

			}	

			else 

			{

				//------- STUDENT DATA FOR STUDENT ---------//

				if($school_obj->role == 'student')

				{

					$own_data=$user_access['own_data'];

					if($own_data == '1')

					{ 

						$user_id=get_current_user_id();	

						$studentdata[] =get_userdata($user_id);

					}

					else

					{

						$studentdata	=	mj_smgt_get_usersdata('student');

					}

				}

				//------- STUDENT DATA FOR TEACHER ---------//

				elseif($school_obj->role == 'teacher')

				{

					$own_data=$user_access['own_data'];

					if($own_data == '1')

					{ 

						$user_id=get_current_user_id();		

						

						$class_id=get_user_meta($user_id,'class_name',true);

					

						$studentdata=$school_obj->mj_smgt_get_teacher_student_list($class_id);

					}

					else

					{

						$studentdata	=	mj_smgt_get_usersdata('student');

					}

				}

				//------- STUDENT DATA FOR PARENT ---------//

				elseif($school_obj->role == 'parent')

				{

					$own_data=$user_access['own_data'];

					if($own_data == '1')

					{ 

						$child_data = $school_obj->child_list;

					}

					else

					{

						$studentdata	=	mj_smgt_get_usersdata('student');

					}

				}

				else

				{

					$own_data=$user_access['own_data'];

					$user_id=get_current_user_id();		

					if($own_data == '1')

					{ 

						$studentdata= get_users(

							 array(

									'role' => 'student',

									'meta_query' => array(

									array(

											'key' => 'created_by',

											'value' => $user_id,

											'compare' => '='

										)

									)

							));	

					}

					else

					{

						$studentdata	=	mj_smgt_get_usersdata('student');

					}

				}

			}

			if(!empty($studentdata) || !empty($child_data))

			{

				?>

				<div class="panel-body"><!------------ PENAL BODY ----------->

					<div class="table-responsive"><!------------ TABLE RESPONSIVE ----------->

						<!----------- STUDENT LIST FORM START ---------->

						<form id="frm-example" name="frm-example" method="post">

							<table id="students_list" class="display dataTable student_datatable" cellspacing="0" width="100%">

								<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

									<tr>

										<?php

										if($role_name == "supportstaff")

										{

											?>

											<th class="padding_0"><input type="checkbox" class="smgt_sub_chk select_all" id="select_all"></th>

											<?php

										}

										?>

										<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>

										<th><?php echo esc_attr_e( 'Student Name & Email', 'school-mgt' ) ;?></th>

										<th> <?php echo esc_attr_e( 'Mobile No.', 'school-mgt' ) ;?></th>

										<th> <?php echo esc_attr_e( 'Class & Section', 'school-mgt' ) ;?></th>

										<th> <?php echo esc_attr_e( 'Roll No.', 'school-mgt' ) ;?></th>

										<th> <?php echo esc_attr_e( 'Gender', 'school-mgt' ) ;?></th>

										<?php
										if(!empty($user_custom_field)){
											foreach($user_custom_field as $custom_field)
											{
												if($custom_field->show_in_table == "1")
												{
													?>
													<th> <?php echo esc_attr_e( $custom_field->field_label, 'school-mgt' ) ;?></th>
													<?php
												}
											}
										}
										?>

										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

									</tr>

								</thead>

								<tbody>

									<?php 

									if(!empty($studentdata))

									{

										foreach ($studentdata as $retrieved_data)

										{

											?>

											<tr>

												<?php

												if($role_name == "supportstaff")

												{

													?>

													<td class="checkbox_width_10px"><input type="checkbox" name="id[]" class="smgt_sub_chk" value="<?php echo esc_attr($retrieved_data->id); ?>"></td>

													<?php

												}

												?>

												

												<td class="user_image width_50px">

													<a class="" href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>">

														<?php

															$uid=$retrieved_data->ID;

															$umetadata=mj_smgt_get_user_image($uid);

															if(empty($umetadata))

															{

																echo '<img src='.get_option( 'smgt_student_thumb_new' ).' class="img-circle" />';

															}

															else

															{

																echo '<img src='.$umetadata.' class="img-circle" />';

															}

														?>

													</a>

												</td>

												<td class="name">

													<a class="color_black" href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a>

													<br>

													<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>

												</td>

												<td class="name">

													+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>

													<?php

													if(!empty($retrieved_data->mobile_number)){echo $retrieved_data->mobile_number;}else{echo 'N/A';}

													?>



													<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile No.','school-mgt');?>" ></i>

												</td>

												<td class="name">

													<?php 

													$class_id = get_user_meta($retrieved_data->ID, 'class_name',true);

													$section_id = get_user_meta($retrieved_data->ID, 'class_section',true);

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

												<td class="roll_no">

													<?php 

														if(get_user_meta($retrieved_data->ID, 'roll_id', true))

														echo get_user_meta($retrieved_data->ID, 'roll_id',true);

													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Roll No.','school-mgt');?>" ></i>

												</td>

												<td class="gender">

													<?php 
														
														echo esc_attr__(ucfirst($retrieved_data->gender),'school-mgt');

													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Gender','school-mgt');?>" ></i>

												</td>

												<?php
												// Custom Field Values
												if(!empty($user_custom_field))
												{
													foreach($user_custom_field as $custom_field)
													{
														if($custom_field->show_in_table == "1")
														{
															$module='student';
															$custom_field_id = $custom_field->id;
															$module_record_id = $retrieved_data->ID;
															$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);
															if($custom_field->field_type =='date')
															{
																?>
																<td><?php if(!empty($custom_field_value)){ echo mj_smgt_getdate_in_input_box($custom_field_value); }else{ echo 'N/A'; } ?></td>
																<?php
															}
															elseif($custom_field->field_type =='file')
															{
																?>
																<td>
																	<?php
																	if(!empty($custom_field_value))
																	{
																		?>
																		<a target="" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>" download="CustomFieldfile"><button class="btn btn-default view_document" type="button">

																		<i class="fa fa-download"></i> <?php esc_attr_e('Download','school-mgt');?></button></a>
																		<?php
																	}
																	else
																	{
																		echo 'N/A';
																	}
																	?>
																</td>
																<?php
															}
															else
															{
																?>
																<td> <?php if(!empty($custom_field_value)){ echo $custom_field_value; }else{ echo 'N/A'; } ?> </td>
																<?php
															}

														}
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

																	<li class="float_left_width_100">

																		<a href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_html_e('View', 'school-mgt' ) ;?> </a>

																	</li>

																	<?php

																	

																	if($school_obj->role == 'student' || $school_obj->role == 'supportstaff' || $school_obj->role == 'teacher' )

																	{

																		

																		?>

																		<li class="float_left_width_100">

																			<a href="?dashboard=user&page=student&action=result&student_id=<?php echo $retrieved_data->ID;?>" class="show-popup float_left_width_100" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-bar-chart"> </i><?php esc_attr_e('View Result', 'school-mgt');?></a>

																		</li>

																		<?php

																		

																	

																		if($user_access['edit']=='1')

																		{

																		?>

																			<li class="float_left_width_100 border_bottom_menu">

																				<a href="?dashboard=user&page=student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"> </i> <?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																			</li>



																			<?php 

																		} 

																		if($user_access['delete']=='1')

																		{

																			?>

																			<li class="float_left_width_100 ">

																			<a href="?dashboard=user&page=student&tab=studentlist&action=delete&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

																			<i class="fa fa-trash"></i><?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

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

											</tr>	

											<?php 

										} 

									}

									if(!empty($child_data))

									{

										foreach ($school_obj->child_list as $child_id)

										{ 

											$retrieved_data= get_userdata($child_id);

											if($retrieved_data)

											{ 

												?>

												<tr>

													<?php

													if($role_name == "supportstaff")

													{

														?>

														<td class="checkbox_width_10px"><input type="checkbox" name="id[]" class="smgt_sub_chk" value="<?php echo esc_attr($retrieved_data->id); ?>"></td>

														<?php

													}

													?>

													

													<td class="user_image width_50px">

														<a class="" href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>">

															<?php

																$uid=$retrieved_data->ID;

																$umetadata=mj_smgt_get_user_image($uid);

																if(empty($umetadata))

																{

																	echo '<img src='.get_option( 'smgt_student_thumb_new' ).' class="img-circle" />';

																}

																else

																{

																	echo '<img src='.$umetadata.' class="img-circle" />';

																}

															?>

														</a>

													</td>

													<td class="name">

														<a class="color_black" href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a>

														<br>

														<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>

													</td>



													<td class="name">

													+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>

													<?php

													if(!empty($retrieved_data->mobile_number)){echo $retrieved_data->mobile_number;}else{echo 'N/A';}

													?>



													<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile No.','school-mgt');?>" ></i>

												</td>



												<td class="name">

													<?php 

													$class_id = get_user_meta($retrieved_data->ID, 'class_name',true);

													$section_id = get_user_meta($retrieved_data->ID, 'class_section',true);

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



													<td class="roll_no">

														<?php 

															if(get_user_meta($retrieved_data->ID, 'roll_id', true))

															echo get_user_meta($retrieved_data->ID, 'roll_id',true);

														?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Roll No.','school-mgt');?>" ></i>

													</td>



													<td class="gender">

														<?php 
															echo esc_attr_e(ucfirst($retrieved_data->gender),'school-mgt');
														?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Gender','school-mgt');?>" ></i>

													</td>

													<?php
													if(!empty($user_custom_field))
													{
														foreach($user_custom_field as $custom_field)
														{
															if($custom_field->show_in_table == "1")
															{
																$module='student';
																$custom_field_id = $custom_field->id;
																$module_record_id = $retrieved_data->ID;
																$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);
																if($custom_field->field_type =='date')
																{
																	?>
																	<td><?php if(!empty($custom_field_value)){ echo mj_smgt_getdate_in_input_box($custom_field_value); }else{ echo 'N/A'; } ?></td>
																	<?php
																}
																elseif($custom_field->field_type =='file')
																{
																	?>
																	<td>
																		<?php
																		if(!empty($custom_field_value))
																		{
																			?>
																			<a target="" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>" download="CustomFieldfile"><button class="btn btn-default view_document" type="button">
	
																			<i class="fa fa-download"></i> <?php esc_attr_e('Download','school-mgt');?></button></a>
																			<?php
																		}
																		else
																		{
																			echo 'N/A';
																		}
																		?>
																	</td>
																	<?php
																}
																else
																{
																	?>
																	<td> <?php if(!empty($custom_field_value)){ echo $custom_field_value; }else{ echo 'N/A'; } ?> </td>
																	<?php
																}
	
															}
														}
													}
													else
													{
														?>
														<td> <?php  echo 'N/A';  ?> </td>
														<?php
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

																		<li class="float_left_width_100">

																			<a href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_html_e('View', 'school-mgt' ) ;?> </a>

																		</li>

																		

																		<li class="float_left_width_100">

																			<a href="?dashboard=user&page=student&action=result&student_id=<?php echo $retrieved_data->ID;?>" class="show-popup float_left_width_100" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-bar-chart"> </i><?php esc_attr_e('View Result', 'school-mgt');?></a>

																		</li>

																		<?php

																		if($user_access['edit']=='1')

																		{

																			?>

																			<li class="float_left_width_100 border_bottom_menu">

																				<a href="?dashboard=user&page=student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"> </i> <?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																			</li>



																			<?php 

																		} 

																		if($user_access['delete']=='1')

																		{

																			?>

																			<li class="float_left_width_100 ">

																			<a href="?dashboard=user&page=student&tab=studentlist&action=delete&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

																			<i class="fa fa-trash"></i><?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

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

											}

										}

									}							

									?>

								</tbody>        

							</table>

							<!-------- Delete And Select All Button ----------->

							<?php

							if($role_name == "supportstaff")

							{

								?>

								<div class="print-button pull-left">

									<button class="btn btn-success btn-sms-color button_reload">

										<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">

										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

									</button>

									<?php 

									if($user_access['delete']=='1')

									{ 

										?>

										<button data-toggle="tooltip"  id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

										<?php 

									} 

									?>

								</div>

								<?php

							}

							?>

							<!-------- Delete And Select All Button ----------->

						</form><!----------- STUDENT LIST FORM START ---------->

					</div><!------------ TABLE RESPONSIVE ----------->    

				</div><!------------ PENAL BODY ----------->

				<?php 

			}

			else

			{

				if($user_access['add']=='1')

				{

					?>

					<div class="no_data_list_div no_data_img_mt_30px"> 

						<a href="<?php echo home_url().'?dashboard=user&page=student&tab=addstudent';?>">

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

		if($active_tab == 'addstudent')

		{

			$role='student';

			$edit=0;

			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

			{

				$edit=1;

				$user_info = get_userdata($_REQUEST['student_id']);

			}

			?>
			<script>
					function mj_smgt_custom_filed_fileCheck(obj)
						{	
							"use strict";
							var fileExtension = $(obj).attr('file_types');

							var fileExtensionArr = fileExtension.split(',');

							var file_size = $(obj).attr('file_size');

							

							var sizeInkb = obj.files[0].size/1024;

							

							if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionArr) == -1)

							{										

								alert("Only "+fileExtension+" formats are allowed.");

								$(obj).val('');

							}	

						}
				</script>
			<div class="panel-body"><!-------- PENAL BODY ----------->

				<!---------------- STUDENT ADD FORM START ----------------->

				<form name="student_form" action="" method="post" class="mt-3 form-horizontal" id="student_form" enctype="multipart/form-data">

					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

					<input type="hidden" name="action" value="<?php echo $action;?>">

					<input type="hidden" name="role" value="<?php echo $role;?>"  />

					<div class="header">	

						<h3 class="first_hed"><?php esc_html_e('Personal Information','school-mgt');?></h3>

					</div>

					<div class="form-body user_form"> <!--form Body div-->   

						<div class="row"><!--Row Div--> 

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input smgt_form_select">

								<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>

								<?php if($edit){ $classval=$user_info->class_name; }elseif(isset($_POST['class_name'])){$classval=$_POST['class_name'];}else{$classval='';}?>

								<select name="class_name" class="line_height_30px form-control validate[required] class_in_student max_width_100" id="class_list">

									<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>

									<?php

										foreach(mj_smgt_get_allclass() as $classdata)

										{  

										?>

											<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>

									<?php 

										} 	?>

								</select>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input smgt_form_select">

								<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>

								<?php if($edit){ $sectionval=$user_info->class_section; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>

								<select name="class_section" class="line_height_30px form-control max_width_100" id="class_section">

									<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>

									<?php

									if($edit){

										foreach(mj_smgt_get_class_sections($user_info->class_name) as $sectiondata)

										{  ?>

											<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

									<?php } 

									}?>

								</select>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="roll_id" class="form-control validate[required,custom[username_validation]]" maxlength="50" type="text" <?php if($edit){ ?>value="<?php  echo $user_info->roll_id;}elseif(isset($_POST['roll_id'])) echo $_POST['roll_id'];?>" name="roll_id">

										<label class="" for="roll_id"><?php esc_attr_e('Roll Number','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">

										<label class="" for="first_name"><?php esc_attr_e('First Name','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">

										<label class="" for="middle_name"><?php esc_attr_e('Middle Name','school-mgt');?></label>

									</div>

								</div>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">

										<label class="" for="last_name"><?php esc_attr_e('Last Name','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 res_margin_bottom_20px rtl_margin_top_15px">

								<div class="form-group">

									<div class="col-md-12 form-control" style="height: 47px !important;">

										<div class="row padding_radio">

											<div class="input-group">

												<label class="custom-top-label" for="gender"><?php esc_attr_e('Gender','school-mgt');?><span class="require-field">*</span></label>

												<div class="d-inline-block">

													<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>

													<label class="radio-inline custom_radio">

													<input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_attr_e('Male','school-mgt');?>

													</label>

													<label class="radio-inline custom_radio">

													<input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_attr_e('Female','school-mgt');?> 

													</label>

													<label class="radio-inline custom_radio">

													<input type="radio" value="other" class="tog validate[required]" name="gender"  <?php  checked( 'other', $genderval);  ?>/><?php esc_attr_e('Other','school-mgt');?> 

													</label>

												</div>

											</div>

										</div>		

									</div>

								</div>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="birth_date" class="form-control date_picker validate[required]" type="text"  name="birth_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($user_info->birth_date);}elseif(isset($_POST['birth_date'])){ echo mj_smgt_getdate_in_input_box($_POST['birth_date']);}else{ echo mj_smgt_getdate_in_input_box(date('Y-m-d')); }?>" readonly>

										<label class="col-form-label date_label text-md-end col-sm-2 control-label" for="birth_date"><?php esc_attr_e('Date of Birth','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>	

							</div>	

						</div>

					</div>

					<div class="header">	

						<h3 class="first_hed"><?php esc_html_e('Contact Information','school-mgt');?></h3>

					</div>

					<div class="form-body user_form"> <!--Card Body div-->  

						<div class="row">

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="120" type="text"  name="address" 

										<?php if($edit){ ?>value="<?php echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">

										<label class="" for="address"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 

										<?php if($edit){ ?>value="<?php echo $user_info->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">

										<label class="" for="city_name"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 

										<?php if($edit){ ?>value="<?php echo $user_info->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">

										<label class="" for="state_name"><?php esc_attr_e('State','school-mgt');?></label>

									</div>

								</div>

							</div>

							<?php wp_nonce_field( 'save_student_frontend_nonce' ); ?>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="zip_code" class="form-control validate[required,custom[zipcode],minSize[4],maxSize[8]]" maxlength="15" type="text"  name="zip_code" <?php if($edit){ ?>value="<?php echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">

										<label class="" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>



							<div class="col-md-6">

								<div class="row">

									<div class="col-md-4">

										<div class="form-group input margin_bottom_0">

											<div class="col-md-12 form-control">

												<input id="phonecode" name="phonecode" type="text" class="form-control validate[required] onlynumber_and_plussign" value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>" maxlength="5" disabled>

												<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?><span class="required red">*</span></label>

											</div>											

										</div>

									</div>

									<div class="col-md-8 mobile_error_massage_left_margin">

										<div class="form-group input margin_bottom_0">

											<div class="col-md-12 form-control">

												<input id="mobile_number" class="form-control margin_top_10_res text-input validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="mobile_number"

												value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">

												<label for="userinput6"><?php esc_html_e('Mobile Number','school-mgt');?><span class="required red">*</span></label>

											</div>

										</div>

									</div>

								</div>

							</div> 

							<div class="col-md-6">

								<div class="row">

									<div class="col-md-4">

										<div class="form-group input margin_bottom_0">

											<div class="col-md-12 form-control">

												<input id="phonecode" name="alter_mobile_number" type="text" class="form-control validate[required] onlynumber_and_plussign" value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>" maxlength="5" disabled>

												<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></label>

											</div>											

										</div>

									</div>

									<div class="col-md-8">

										<div class="form-group input margin_bottom_0">

											<div class="col-md-12 form-control">

												<input id="alternet_mobile_number" class="form-control margin_top_10_res text-input" type="text"  name="alternet_mobile_number" 

												value="<?php if($edit){ echo $user_info->alternet_mobile_number;}elseif(isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number'];?>">

												<label for="userinput6"><?php esc_html_e('Alternate Mobile Number','school-mgt');?></label>

											</div>

										</div>

									</div>

								</div>

							</div> 		

						</div>

					</div>

					<div class="header">	

						<h3 class="first_hed"><?php esc_html_e('Login Information','school-mgt');?></h3>

					</div>

					<div class="form-body user_form"> <!--Card Body div-->  

						<div class="row">

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="email" class="form-control validate[required,custom[email]] text-input student_email_id" maxlength="100" type="text"  name="email" <?php if($edit){ ?> value="<?php echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">

										<label class="" for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>

									</div>

									<div class="email_validation_div">

										<div class="formError" style="opacity: 0.87; position: absolute; top: 33px; left: 482.5px; margin-top: 0px; display: block;"><div class="formErrorArrow formErrorArrowBottom"><div class="line1"><!-- --></div><div class="line2"><!-- --></div><div class="line3"><!-- --></div><div class="line4"><!-- --></div><div class="line5"><!-- --></div><div class="line6"><!-- --></div><div class="line7"><!-- --></div><div class="line8"><!-- --></div><div class="line9"><!-- --></div><div class="line10"><!-- --></div></div><div class="formErrorContent"><?php esc_html_e('Email id Already Exist.','hospital_mgt');?><br></div></div>

									</div>

								</div>

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="password" class="form-control <?php if(!$edit){ echo 'validate[required,minSize[8],maxSize[12]]'; }else{ echo 'validate[minSize[8],maxSize[12]]'; } ?>" type="password"  name="password">

										<label class="" for="password"><?php esc_attr_e('Password','school-mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
										<i class="fa fa-eye-slash" id="togglePassword"></i>
									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="header">	

						<h3 class="first_hed"><?php esc_html_e('Profile Image','school-mgt');?></h3>

					</div>

					<div class="form-body user_form">

						<div class="row">

							<div class="col-md-6">

								<div class="form-group input">

									<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">	

										<label for="photo" class="custom-control-label custom-top-label ml-2 label_position_rtl"><?php esc_attr_e('Image','school-mgt');?></label>

										<div class="col-sm-12 display_flex">

											<input type="hidden" id="smgt_user_avatar_url" class="image_path_dots form-control" name="smgt_user_avatar" value="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar );elseif(isset($_POST['smgt_user_avatar'])) echo $_POST['smgt_user_avatar']; ?>" readonly />

											<input id="upload_user_avatar_button" type="file" class="form-control file" onchange="fileCheck(this);" value="<?php esc_html_e( 'Upload image', 'school-mgt' ); ?>" style="border:0px solid;"/>

										</div>

									</div>

									<div class="clearfix"></div>

									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">

										<div id="upload_user_avatar_preview">

											

											<?php if($edit) 

											{

											if($user_info->smgt_user_avatar == "")

											{?>

											<img class="image_preview_css" alt="" src="<?php echo get_option( 'smgt_student_thumb_new' ) ?>">

											<?php }

											else {

												?>

											<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar ); ?>" />

											<?php 

											}

											}

											else {

												?>

												<img class="image_preview_css" src="<?php echo get_option( 'smgt_student_thumb_new' ) ?>">

												<?php 

											}?>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

					<?php

					//--------- Get Module Wise Custom Field Data --------------//

					$custom_field_obj =new Smgt_custome_field;

					

					$module='student';	

					

					$compact_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);

					

					if(!empty($compact_custom_field))

					{	

						?>		

						<div class="header">	

							<h3 class="first_hed margin_top_0px_image"><?php esc_html_e('Custom Fields','school-mgt');?></h3>

						</div>	

						<div class="form-body user_form">

							<div class="row">					

								<?php

								foreach($compact_custom_field as $custom_field)

								{

									if($edit)

									{

										$custom_field_id=$custom_field->id;

										

										$module_record_id=$_REQUEST['student_id'];

										

										$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);

									}

									

									// Custom Field Validation // 

									$exa = explode('|',$custom_field->field_validation);

									$min = "";

									$max = "";

									$required = "";

									$red = "";

									$limit_value_min = "";

									$limit_value_max = "";

									$numeric = "";

									$alpha = "";

									$space_validation = "";

									$alpha_space = "";

									$alpha_num = "";

									$email = "";

									$url = "";

									$minDate="";

									$maxDate="";

									$file_types="";

									$file_size="";

									$datepicker_class="";

									foreach($exa as $key=>$value)

									{

										if (strpos($value, 'min') !== false)

										{

										$min = $value;

										$limit_value_min = substr($min,4);

										}

										elseif(strpos($value, 'max') !== false)

										{

										$max = $value;

										$limit_value_max = substr($max,4);

										}

										elseif(strpos($value, 'required') !== false)

										{

											$required="required";

											$red="*";

										}

										elseif(strpos($value, 'numeric') !== false)

										{

											$numeric="onlyNumberSp";

										}

										elseif($value == 'alpha')

										{

											$alpha="onlyLetterSp";

											$space_validation="space_validation";

										}

										elseif($value == 'alpha_space')

										{

											$alpha_space="onlyLetterSp";

										}

										elseif(strpos($value, 'alpha_num') !== false)

										{

											$alpha_num="onlyLetterNumber";

										}

										elseif(strpos($value, 'email') !== false)

										{

											$email = "email";

										}

										elseif(strpos($value, 'url') !== false)

										{

											$url="url";

										}

										elseif(strpos($value, 'after_or_equal:today') !== false )

										{

											$minDate=1;

											$datepicker_class='after_or_equal';

										}

										elseif(strpos($value, 'date_equals:today') !== false )

										{

											$minDate=$maxDate=1;

											$datepicker_class='date_equals';

										}

										elseif(strpos($value, 'before_or_equal:today') !== false)

										{	

											$maxDate=1;

											$datepicker_class='before_or_equal';

										}	

										elseif(strpos($value, 'file_types') !== false)

										{	

											$types = $value;													

										

											$file_types=substr($types,11);

										}

										elseif(strpos($value, 'file_upload_size') !== false)

										{	

											$size = $value;

											$file_size=substr($size,17);

										}

									}

									$option =$custom_field_obj->mj_smgt_getDropDownValue($custom_field->id);

									$data = 'custom.'.$custom_field->id;

									$datas = 'custom.'.$custom_field->id;											

									

									if($custom_field->field_type =='text')

									{

										?>

										<div class="col-md-6">

											<div class="form-group input">

												<div class="col-md-12 form-control">

													<input class="form-control hideattar<?php echo $custom_field->form_name; ?> validate[<?php if(!empty($required)){ echo $required; ?>,<?php } ?><?php if(!empty($limit_value_min)){ ?> minSize[<?php echo $limit_value_min; ?>],<?php } if(!empty($limit_value_max)){ ?> maxSize[<?php echo $limit_value_max; ?>],<?php } if($numeric != '' || $alpha != '' || $alpha_space != '' || $alpha_num != '' || $email != '' || $url != ''){ ?> custom[<?php echo $numeric; echo $alpha; echo $alpha_space; echo $alpha_num; echo $email; echo $url; ?>]<?php } ?>] <?php echo $space_validation; ?>" type="text" name="custom[<?php echo $custom_field->id; ?>]" id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>" <?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?>>

													<label for="<?php echo $custom_field->id; ?>" class=""><?php esc_html_e($custom_field->field_label ,'school-mgt');?></label>

												</div>

											</div>

										</div>

										<?php

									}

									elseif($custom_field->field_type =='textarea')

									{

										?>

										<div class="col-md-6 note_text_notice">	

											<div class="form-group input">

												<div class="col-md-12 note_border">

													<div class="form-field">

														<textarea rows="3" class="textarea_height_47px form-control hideattar<?php echo $custom_field->form_name; ?> validate[<?php if(!empty($required)){ echo $required; ?>,<?php } ?><?php if(!empty($limit_value_min)){ ?> minSize[<?php echo $limit_value_min; ?>],<?php } if(!empty($limit_value_max)){ ?> maxSize[<?php echo $limit_value_max; ?>],<?php } if($numeric != '' || $alpha != '' || $alpha_space != '' || $alpha_num != '' || $email != '' || $url != ''){ ?> custom[<?php echo $numeric; echo $alpha; echo $alpha_space; echo $alpha_num; echo $email; echo $url; ?>]<?php } ?>] <?php echo $space_validation; ?>" name="custom[<?php echo $custom_field->id; ?>]" id="<?php echo $custom_field->id; ?>"label="<?php echo $custom_field->field_label; ?>"><?php if($edit){ echo $custom_field_value; } ?></textarea>

														<span class="txt-title-label"></span>

														<label class="text-area address"><?php esc_html_e($custom_field->field_label,'school-mgt');?><span class="required-red">*</span></label>

													</div>

												</div>

											</div>

										</div>

										<?php 

									}

									elseif($custom_field->field_type =='date')

									{

										?>	

										<div class="col-md-6">

											<div class="form-group input">

												<div class="col-md-12 form-control">

													<input class="form-control date_picker custom_datepicker <?php echo $datepicker_class; ?> hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" type="text" name="custom[<?php echo $custom_field->id; ?>]"<?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?>id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>">

													<label for="" class="date_label"><?php esc_html_e($custom_field->field_label ,'school-mgt');?><span class="required red"><?php echo $red; ?></span></label>

												</div>

											</div>

										</div>

										<?php 

									}

									elseif($custom_field->field_type =='dropdown')

									{

										?>	

										<div class="col-md-6 col-sm-6 input">

											<label class="ml-1 custom-top-label top" for="<?php echo $custom_field->id; ?>"><?php esc_html_e($custom_field->field_label,'school-mgt');?></label>

											<select class="form-control standard_category line_height_30px  hideattar<?php echo $custom_field->form_name; ?>" <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>]"	id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>">

												<option value=""><?php esc_attr_e( 'Select', 'school-mgt' ); ?></option>

												<?php

												if(!empty($option))

												{															

													foreach ($option as $options)

													{

														?>

														<option value="<?php echo $options->option_label; ?>" <?php if($edit){ echo selected($custom_field_value,$options->option_label); } ?>> <?php echo $options->option_label; ?></option>

														<?php

													}

												}

												?>

											</select>                               

										</div>

										<?php 

									}

									elseif($custom_field->field_type =='checkbox')

									{

										?>	

										<div class="col-md-6 mb-3 smgt_main_custome_field">

											<div class="form-group">

												<div class="col-md-12 form-control">

													<div class="row padding_radio">

														<div class="">

															<label class="custom-top-label margin_left_0"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>

															<?php

															if(!empty($option))

															{

																foreach ($option as $options)

																{ 

																	if($edit)

																	{

																		$custom_field_value_array=explode(',',$custom_field_value);

																	}

																		?>	

																	<label class="me-2">

																		<input type="checkbox" value="<?php echo $options->option_label; ?>"  <?php if($edit){  echo checked(in_array($options->option_label,$custom_field_value_array)); } ?> class="hideattar<?php echo $custom_field->form_name; ?><?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>][]" >&nbsp;&nbsp;<span class="span_left_custom" style="margin-bottom: -5px;"><?php echo $options->option_label; ?></span>

																	</label>

																	<?php

																}

															}

															?>

														</div>												

													</div>

												</div>

											</div>

										</div>

										

										<?php 

									}

									elseif($custom_field->field_type =='radio')

									{

										?>

										

										<div class="col-md-6 rtl_margin_top_15px">

											<div class="form-group">

												<div class="col-md-12 form-control">

													<div class="row padding_radio">

														<div class="input-group">

															<label class="custom-top-label margin_left_0"><?php esc_html_e($custom_field->field_label,'school-mgt');?><span class="required">*</span></label>													

															<?php

															if(!empty($option))

															{

																foreach ($option as $options)

																{

																	?>

																	<div class="d-inline-block">

																		<label class="radio-inline">

																			<input type="radio"  value="<?php echo $options->option_label; ?>" <?php if($edit){ echo checked( $options->option_label, $custom_field_value); } ?> name="custom[<?php echo $custom_field->id; ?>]"  class="custom-control-input hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>  " id="<?php echo $options->option_label; ?>">

																			<?php echo $options->option_label; ?>

																		</label>&nbsp;&nbsp;

																	</div>

																	<?php

																}

															}

															?>

														</div>												

													</div>

												</div>

											</div>

										</div>

											

									

										<?php

									}

									elseif($custom_field->field_type =='file')

									{

										?>	

										

										<div class="col-md-6">

											<div class="form-group input">

												<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">	

													<label for="photo" class="custom-control-label custom-top-label ml-2"><?php esc_attr_e($custom_field->field_label,'school-mgt');?><span class="required red"><?php echo $red; ?></span></label>

													<div class="col-sm-12 display_flex">

														<input type="hidden" name="hidden_custom_file[<?php echo $custom_field->id; ?>]" value="<?php if($edit){ echo $custom_field_value; } ?>">

														<input type="file"  onchange="mj_smgt_custom_filed_fileCheck(this);" Class="hideattar<?php echo $custom_field->form_name; if($edit){ if(!empty($required)){ if($custom_field_value==''){ ?> validate[<?php echo $required; ?>] <?php } } }else{ if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } } ?>" name="custom_file[<?php echo $custom_field->id;?>]" <?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?> id="<?php echo $custom_field->id; ?>" file_types="<?php echo $file_types; ?>" file_size="<?php echo $file_size; ?>">

													</div>

													<?php

													if(!empty($custom_field_value))

													{

														?>

														<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">

															<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$custom_field_value; ?>">

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

								}	

								?>	 

							</div>

						</div>

						<?php

					}

					?>

					<!------- Save Student Button ---------->

					<div class="form-body user_form">

						<div class="row">

							<div class="col-sm-6">

							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Student','school-mgt'); }else{ esc_attr_e('Add Student','school-mgt');}?>" name="save_student" class="btn btn-success save_btn"/>

							</div>

						</div>

					</div>

				</form>

			</div>

			<?php 

		}	 

		if($active_tab == 'view_student')

		{	

			$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';

			$student_data=get_userdata($_REQUEST['student_id']);

			$user_meta =get_user_meta($_REQUEST['student_id'], 'parent_id', true);

			$parent_list = mj_smgt_get_student_parent_id($_REQUEST['student_id']);	

			$custom_field_obj = new Smgt_custome_field;								

			$module='student';	

			$user_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);

			$student_id = $_REQUEST['student_id'];

			?>

			<!-- POP up code -->

			<div class="popup-bg">

				<div class="overlay-content content_width ">

					<div class="modal-content d-modal-style">

						<div class="task_event_list">

						</div>

					</div>

				</div>

			</div>

			<!-- POP up code -->

			<div class="panel-body view_page_main"><!-- START PANEL BODY DIV-->

				<div class="content-body">

					<!-- Detail Page Header Start -->

					<section id="user_information" class="">

						<div class="view_page_header_bg">

							<div class="row">

								<div class="col-xl-10 col-md-9 col-sm-10">

									<div class="user_profile_header_left float_left_width_100">

										<?php

										$umetadata=mj_smgt_get_user_image($student_data->ID);

										if(empty($umetadata))

										{

											echo '<img src='.get_option( 'smgt_student_thumb_new' ).' class="user_view_profile_image" />';

										}

										else

										{

											echo '<img src='.$umetadata.' class="user_view_profile_image" />';

										}

										?>

										<div class="row profile_user_name">

											<div class="float_left view_top1">

												<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">

													<label class="view_user_name_label"><?php echo esc_html($student_data->display_name);?></label>

													<div class="view_user_edit_btn">

													<?php

													if($user_access['edit']=='1')

													{

														?>

														<a class="color_white margin_left_2px" href="?dashboard=user&page=student&tab=addstudent&action=edit&student_id=<?php echo $student_data->ID;?>">

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/edit.png"?>">

														</a>

														<?php

													}

													?>

													</div>

												</div>

												<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">

													<div class="view_user_phone float_left_width_100">

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<lable class="color_white_rs"><?php echo $student_data->mobile_number;?></label>

													</div>

												</div>

											</div>

										</div>

										<div class="row padding_top_15px_res">

											<div class="col-xl-12 col-md-12 col-sm-12">

												<div class="view_top2">

													<div class="row view_user_doctor_label">

														<div class="col-md-12 address_student_div">

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $student_data->address; ?></label>

														</div>		

													</div>

												</div>

											</div>

										</div>

									</div>

								</div>

								<div class="col-xl-2 col-lg-3 col-md-3 col-sm-2">

									<div class="group_thumbs">
													
										<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Group.png"?>" class="rtl_detail_page_img">

									</div>

									

								</div>

							</div>

						</div>

					</section>

					<!-- Detail Page Header End -->



					<!-- Detail Page Tabing Start -->

					<section id="body_area" class="">

						<div class="row">

							<div class="col-xl-12 col-md-12 col-sm-12">

								<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">

									<li class="<?php if($active_tab1=='general'){?>active<?php }?>">			

										<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=general&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'general' ? 'active' : ''; ?>">

										<?php esc_html_e('GENERAL', 'school-mgt'); ?></a> 

									</li>

									<?php

									$role_name=mj_smgt_get_user_role(get_current_user_id());

									$page='parent';

									$parent=mj_smgt_page_access_rolewise_accessright_dashboard($page);

									if($parent==1)

									{ 

										?>

										<li class="<?php if($active_tab1=='parent'){?>active<?php }?>">

											<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=parent&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'parent' ? 'active' : ''; ?>">

											<?php esc_html_e('Parent List', 'school-mgt'); ?></a> 

										</li>  

										<?php

									}
									?>
									<li class="<?php if($active_tab1=='hallticket'){?>active<?php }?>">

										<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=hallticket&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'hallticket' ? 'active' : ''; ?>">

										<?php esc_html_e('Hall Ticket', 'school-mgt'); ?></a> 

									</li> 
									<?php
									if($role_name == "student" || $role_name == "teacher" || $role_name == "supportstaff" || $role_name == "parent")

									{

										?>

										<li class="<?php if($active_tab1=='exam_result'){?>active<?php }?>">

											<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=exam_result&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'message' ? 'active' : ''; ?>">

											<?php esc_html_e('Exam Results', 'school-mgt'); ?></a> 

										</li> 

										<?php

									}
									$page4='homework';

									$homework=mj_smgt_page_access_rolewise_accessright_dashboard($page4);

									if($homework==1)

									{

										?>

										<li class="<?php if($active_tab1=='homework'){?>active<?php }?>">

											<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=homework&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'homework' ? 'active' : ''; ?>">

											<?php esc_html_e('HomeWork', 'school-mgt'); ?></a> 

										</li> 

										<?php

									}
									$page2='attendance';

									$attendance=mj_smgt_page_access_rolewise_accessright_dashboard($page2);

									if($attendance==1)

									{

									?>

										<li class="<?php if($active_tab1=='attendance'){?>active<?php }?>">

											<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=attendance&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'attendance' ? 'active' : ''; ?>">

											<?php esc_html_e('Attendance', 'school-mgt'); ?></a> 

										</li>  

									<?php

									}
									$leave_page='leave';

									$leave=mj_smgt_page_access_rolewise_accessright_dashboard($leave_page);

									if($leave==1){

										?>

										<li class="<?php if($active_tab1=='leave_list'){?>active<?php }?>">

											<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=leave_list&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'leave_list' ? 'active' : ''; ?>">

											<?php esc_html_e('Leave', 'school-mgt'); ?></a> 

										</li>  

									<?php

									}
									$page1='feepayment';

									$feespayment=mj_smgt_page_access_rolewise_accessright_dashboard($page1);

									if($feespayment==1)

									{ 

										?>

										<li class="<?php if($active_tab1=='feespayment'){?>active<?php }?>">

											<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=feespayment&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'feespayment' ? 'active' : ''; ?>">

											<?php esc_html_e('Fees Payment', 'school-mgt'); ?></a> 

										</li>  

										<?php

									}

									$page5='library';

									$library=mj_smgt_page_access_rolewise_accessright_dashboard($page5);

									if($library==1)

									{

										?> 

										<li class="<?php if($active_tab1=='issuebook'){?>active<?php }?>">

											<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=issuebook&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'issuebook' ? 'active' : ''; ?>">

											<?php esc_html_e('Issue Book', 'school-mgt'); ?></a> 

										</li> 

										<?php

									}

									
									$page6='message';

									$message=mj_smgt_page_access_rolewise_accessright_dashboard($page6);

									if($message==1)

									{

										if($role_name == "student")

										{

											?>

											<li class="<?php if($active_tab1=='message'){?>active<?php }?>">

												<a href="?dashboard=user&page=student&tab=view_student&action=view_student&tab1=message&student_id=<?php echo $_REQUEST['student_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'message' ? 'active' : ''; ?>">

												<?php esc_html_e('Messages', 'school-mgt'); ?></a> 

											</li> 

											<?php

										}

									}

									

									?>

								</ul>

							</div>

						</div>

					</section>

					<!-- Detail Page Tabing End -->



					<!-- Detail Page Body Content Section  -->

					<section id="body_content_area" class="">

						<div class="panel-body"><!-- START PANEL BODY DIV-->

							<?php 

							// general tab start 

							if($active_tab1 == "general")

							{

								?>

								<div class="row margin_top_15px margin_left_3">

									<div class="col-xl-4 col-md-3 col-sm-12 margin_bottom_10_res">

										<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'school-mgt'); ?> </label><br/>

										<label class="view_page_content_labels"> <?php echo $student_data->user_email; ?> </label>

									</div>

									<div class="col-xl-2 col-md-3 col-sm-12 margin_bottom_10_res">

										<label class="view_page_header_labels"> <?php esc_html_e('Roll Number', 'school-mgt'); ?> </label><br/>

										<label class="view_page_content_labels"><?php echo $student_data->roll_id; ?></label>	

									</div>

									<div class="col-xl-2 col-md-3 col-sm-12 margin_bottom_10_res">

										<label class="view_page_header_labels"> <?php esc_html_e('Class Name', 'school-mgt'); ?> </label><br/>

										<label class="view_page_content_labels"> 

											<?php $class_name = mj_smgt_get_class_name($student_data->class_name); 

											if($class_name == " "){ echo "N/A";}else{ echo $class_name;} ?> 

										</label>	

									</div>

									<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">

										<label class="view_page_header_labels"> <?php esc_html_e('Section Name', 'school-mgt'); ?> </label><br/>

										<label class="view_page_content_labels"> 

											<?php 

											if(!empty($student_data->class_section))

											{

												echo mj_smgt_get_section_name($student_data->class_section); 

											}

											else

											{

												echo esc_attr_e('No Section','school-mgt');;

											}

											

											?> 

										</label>

									</div>

								</div>

								<!-- student Information div start  -->

								<div class="row margin_top_20px">

									<div class="col-xl-8 col-md-8 col-sm-12 rtl_padding_0px">

										<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px">

											<div class="guardian_div">

												<label class="view_page_label_heading"> <?php esc_html_e('Student Information', 'school-mgt'); ?> </label>

												<div class="row">

													<div class="col-xl-3 col-md-3 col-sm-12 ">

														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Full Name', 'school-mgt'); ?> </label> <br>

														<label class="view_page_content_labels"><?php echo $student_data->display_name; ?></label>

													</div>

													<div class="col-xl-3 col-md-3 col-sm-12">

														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Alt. Mobile Number', 'school-mgt'); ?> </label><br>

														<label class="view_page_content_labels"><?php if(!empty($student_data->alternet_mobile_number)){ ?>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $student_data->alternet_mobile_number; }else{ echo "N/A"; } ?></label>

													</div>

													<div class="col-xl-3 col-md-3 col-sm-12">

														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br>

														<label class="view_page_content_labels">

															<?php 

															if($student_data->gender=='male') 

																echo esc_attr__('Male','school-mgt');

															elseif($student_data->gender=='female') 

																echo esc_attr__('Female','school-mgt');

															?>

														</label>

													</div>

													<div class="col-xl-3 col-md-3 col-sm-12">

														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br>

														<label class="view_page_content_labels"><?php echo mj_smgt_getdate_in_input_box($student_data->birth_date); ?></label>

													</div>

													<div class="col-xl-3 col-md-3 col-sm-12">

														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label><br>

														<label class="view_page_content_labels"><?php echo $student_data->city; ?></label>

													</div>

													<div class="col-xl-3 col-md-3 col-sm-12">

														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>

														<label class="view_page_content_labels"><?php if(!empty($student_data->state)){ echo $student_data->state; }else{ echo "N/A"; } ?></label>

													</div>

													<div class="col-xl-3 col-md-3 col-sm-12 address_rs_css">

														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zipcode', 'school-mgt'); ?> </label><br>

														<label class="view_page_content_labels"><?php echo $student_data->zip_code; ?></label>

													</div>

												</div>

											</div>	

										</div>

										<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">

											<div class="guardian_div parent_information_div_overflow">

												<label class="view_page_label_heading"> <?php esc_html_e('Parent Information', 'school-mgt'); ?> </label>

												<?php

												if(!empty($user_meta))

												{

													foreach($user_meta as $parentsdata)

													{

														$parent=get_userdata($parentsdata);

														if(!empty($parent)){

														?>

														<div class="row">

															<div class="col-xl-3 col-md-3 col-sm-12">

																<p class="view_page_header_labels"><?php esc_attr_e('Name','school-mgt'); ?></p>

																<p class="view_page_content_labels"><a class="color_black" href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&parent_id=<?php echo $parent->ID;?>"><?php echo mj_smgt_get_parent_name_byid($parent->ID); ?></a></p>

															</div>		

															<div class="col-xl-4 col-md-4 col-sm-12">

																<p class="view_page_header_labels"><?php esc_attr_e('Email','school-mgt'); ?></p>

																<p class="view_page_content_labels"><?php echo $parent->user_email; ?></p>

															</div>		

															<div class="col-xl-4 col-md-4 col-sm-12">

																<p class="view_page_header_labels"><?php esc_attr_e('Mobile No.','school-mgt'); ?></p>

																<p class="view_page_content_labels">+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $parent->mobile_number;?></p>

															</div>	

															<div class="col-xl-2 col-md-2 col-sm-12">

																<p class="view_page_header_labels"><?php esc_attr_e('Relation','school-mgt'); ?></p>

																<p class="view_page_content_labels"><?php if($parent->relation=='Father'){ echo esc_attr__('Father','school-mgt'); }elseif($parent->relation=='Mother'){ echo esc_attr__('Mother','school-mgt');} ?></p>

															</div>			

														</div>

														<?php

														}

													}

												}

												else

												{

													?>

													<div class="col-xl-12 col-md-12 col-sm-12 margin_top_-20px_rtl margin_top_15px" style="text-align: center;">

														<p class="view_page_content_labels"><?php echo esc_attr__('No Any Parent.','school-mgt'); ?></p>

													</div>	

													<?php	

												}

												?>

											</div>	

										</div>

										<?php

										$hostel_data = mj_smgt_student_assign_bed_data_by_student_id($student_id);

										$room_data='';

										if(!empty($hostel_data))

										{

											$room_data = mj_smgt_get_room__data_by_room_id($hostel_data->room_id);

										}

									

										$student_data_for_sibling = get_userdata($student_id);

										?>

										<!--------- Other student Imformation -------------->

										

											<?php

											$sibling_data = $student_data_for_sibling->sibling_information;

											$sibling = json_decode($sibling_data);

											if(!empty($student_data_for_sibling->sibling_information))

											{ 

												foreach ($sibling as $value) 

												{

													if(!empty($value->siblingsclass) && !empty($value->siblingsstudent))

													{

														?>

														<div class="col-xl-12 col-md-12 col-sm-12 margin_top_15px_rs">

															<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px">

																<div class="guardian_div">

																	<label class="view_page_label_heading"> <?php esc_html_e('Sibling Information', 'school-mgt'); ?> </label>

																	<div class="row">

																		<div class="col-xl-5 col-md-5 col-sm-12">

																			<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Name', 'school-mgt'); ?> </label> <br>

																			<label class="view_page_content_labels"><a class="color_black" href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $value->siblingsstudent;?>"><?php echo mj_smgt_student_display_name_with_roll($value->siblingsstudent);?></a></label>

																		</div>

																		<div class="col-xl-4 col-md-4 col-sm-12">

																			<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Class Name', 'school-mgt'); ?> </label> <br>

																			<label class="view_page_content_labels"><?php echo smgt_get_class_section_name_wise($value->siblingsclass,$value->siblingssection); ?></label>

																		</div>

																	</div>

																</div>	

															</div>

														</div>

														<?php

													}

												}

											}

											if(!empty($hostel_data))

											{

												?>

												<div class="col-xl-12 col-md-12 col-sm-12">

													<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px rtl_padding_0px margin_top_15px_rs">

														<div class="guardian_div">

															<label class="view_page_label_heading"> <?php esc_html_e('Hostel Information', 'school-mgt'); ?> </label>

															<div class="row">

																<div class="col-xl-4 col-md-4 col-sm-12">

																	<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Hostel Name', 'school-mgt'); ?> </label> <br>

																	<label class="view_page_content_labels"><?php if(!empty($hostel_data)){ if($hostel_data->hostel_id){ echo mj_smgt_hostel_name_by_id($hostel_data->hostel_id); }else{ echo "N/A"; } }else{ echo "N/A"; } ?></label>

																</div>

																<div class="col-xl-4 col-md-4 col-sm-12">

																	<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Room Unique ID', 'school-mgt'); ?> </label> <br>

																	<label class="view_page_content_labels"><?php if(!empty($room_data)){ if($room_data->room_unique_id){ echo $room_data->room_unique_id; }else{ echo "N/A"; } }else{ echo "N/A"; } ?></label>

																</div>

																<div class="col-xl-4 col-md-4 col-sm-12">

																	<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Bed Unique ID', 'school-mgt'); ?> </label> <br>

																	<label class="view_page_content_labels"><?php if(!empty($hostel_data)){ if($hostel_data->bed_unique_id){ echo $hostel_data->bed_unique_id; }else{ echo "N/A"; } }else{ echo "N/A"; } ?></label>

																</div>

																<div class="col-xl-4 col-md-4 col-sm-12">

																	<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Bed Charge', 'school-mgt'); ?> </label> <br>

																	<label class="view_page_content_labels"><?php if(!empty($hostel_data)){ if($hostel_data->bed_id){ echo  mj_smgt_get_currency_symbol().''.number_format(mj_smgt_get_bed_charge_by_id($hostel_data->bed_id),2,'.',''); }else{ echo "N/A"; } }else{ echo "N/A"; } ?></label>

																</div>

																<div class="col-xl-4 col-md-4 col-sm-12">

																	<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Bed Assign Date', 'school-mgt'); ?> </label> <br>

																	<label class="view_page_content_labels"><?php if(!empty($hostel_data)){ if($hostel_data->assign_date){ echo mj_smgt_getdate_in_input_box($hostel_data->assign_date); }else{ echo "N/A"; } }else{ echo "N/A"; } ?></label>

																</div>

															</div>

														</div>	

													</div>

												</div>

												<?php

											}

											if(!empty($user_custom_field))

											{

												?>

												<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">

													<div class="guardian_div">

														<label class="view_page_label_heading"> <?php esc_html_e('Other Information', 'school-mgt'); ?> </label>

														<div class="row">

															<?php

															foreach($user_custom_field as $custom_field)

															{

																$custom_field_id=$custom_field->id;

																$module_record_id=$_REQUEST['student_id'];

																$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);

																?>

																<div class="col-xl-3 col-md-3 col-sm-12">

																	<p class="view_page_header_labels"><?php esc_attr_e(''.$custom_field->field_label.'','school-mgt'); ?></p>

																	<?php

																	if($custom_field->field_type =='date')

																	{	

																		?>

																		<p class="view_page_header_labels"><?php if(!empty($custom_field_value)){ echo mj_smgt_getdate_in_input_box($custom_field_value); }else{ echo 'N/A'; } ?></p>

																		<?php

																	}

																	elseif($custom_field->field_type =='file')

																	{

																		if(!empty($custom_field_value))

																		{

																			?>

																			<!-- <a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>"><button class="btn btn-default view_document" type="button">

																			<i class="fa fa-eye"></i> <?php esc_attr_e('View','school-mgt');?></button></a> -->

																				

																			<a target="" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>" download="CustomFieldfile"><button class="btn btn-default view_document" type="button">

																			<i class="fa fa-download"></i> <?php esc_attr_e('Download','school-mgt');?></button></a>

																			

																			<?php 

																		}

																		else

																		{

																			echo 'N/A';

																		}

																	}

																	else

																	{

																		?>

																		<p class="user-info"><?php if(!empty($custom_field_value)){ echo $custom_field_value; }else{ echo 'N/A'; } ?></p>

																		<?php		

																	}

																	?>

																</div>		

																<?php

															}

															?>

														</div>

													</div>	

												</div>

												<?php

											}

											?>

										

									</div>

									<!-- Fees Payment Card Div Start  -->

									<div class="col-xl-4 col-md-4 col-sm-12 margin_top_20px margin_top_15px_rs">

										<div class="col-xl-12 col-md-12 col-sm-12 rtl_padding_0px">

											<div class="id_page_card card_margin_bottom">

												<img class="icard_logo"  src="<?php echo esc_url(get_option( 'smgt_school_logo' )); ?>">

												<div class="card_heading card_title_position">

													<label class="id_card_label"><?php echo get_option( 'smgt_school_name' ); ?> </label>

												</div>

												<div class="id_card_body">

													<div class="row">

														<div class="col-md-3 col-3 id_margin">

															<p class="id_card_image">

																<img class="id_card_user_image" src="<?php if(!empty($umetadata)) {echo $umetadata; }else{ echo get_option( 'smgt_student_thumb_new' );}?>">

															</p>

															<p class="id_card_image card_code">

																<img class="id_card_barcode" id='barcode' src=''>

															</p>

														</div>

														<div class="col-md-9 col-9 id_card_info row">

																<div class="p-0 col-md-6 col-6 card_user_name">

																	<h5 class="student_info"><?php echo esc_html_e('Student Name','school-mgt'); ?></h5>

																</div>

																<div class="p-0 col-md-6 col-6 card_user_name">

																	<p class="icard_dotes">:&nbsp;</p><h5 class="user_info"><?php echo esc_html_e("$student_data->display_name",'school-mgt'); ?></h5>

																</div>

																<div class="p-0 col-md-6 col-6 card_user_name">

																	<h5 class="student_info"><?php echo esc_html_e('Roll No.','school-mgt'); ?></h5>

																</div>

																<div class="p-0 col-md-6 col-6 card_user_name">

																	<p class="icard_dotes">:&nbsp;</p><h5 class="user_info"><?php if(!empty($student_data->roll_id)){ echo $student_data->roll_id; }else{ echo "N/A"; } ?></h5>

																</div>

																<div class="p-0 col-md-6 col-6 card_user_name">

																	<h5 class="student_info"><?php echo esc_html_e('Contact No','school-mgt'); ?></h5>

																</div>

																<div class="p-0 col-md-6 col-6 card_user_name">

																	<p class="icard_dotes">:&nbsp;</p><h5 class="user_info"><lable class="">+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $student_data->mobile_number;?></h5>

																</div>

																<div class="p-0 col-md-6 col-6">

																	<h5 class="student_info"><?php echo esc_html_e('Class','school-mgt'); ?></h5>

																</div>

																<div class="p-0 col-md-6 col-6">

																<p class="icard_dotes">:&nbsp;</p><h5 class="user_info"><?php $class_name = smgt_get_class_section_name_wise($student_data->class_name,$student_data->class_section); 

																		if($class_name == " "){ echo "N/A";}else{ echo $class_name;} ?> </h5>

															</div>

															</div>

														</div>

													</div>

												</div>

												<div class="qr_code_card">

													<div class="qr_main_div">

														<h3><?php esc_html_e('Scan Below QR For Attendance','school-mgt');?></h3>

														<div class="qr_image_div"><img class="id_card_barcode qr_width" id='barcode' src=''></div>

													</div>

												</div>

											</div>	

										</div> 

									</div> 

								</div>

								<?php

							}



							// prents tab start 

							elseif($active_tab1 == "parent")

							{

								

								if(!empty($user_meta))

								{

									?>

									<script type="text/javascript">

										jQuery(document).ready(function($)

										{

											"use strict";	

											jQuery('#parents_list_detailpage').DataTable({
												//stateSave: true,
												"order": [[ 1, "asc" ]],

												dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												"aoColumns":[	                  

															{"bSortable": false},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true}],

												language:<?php echo mj_smgt_datatable_multi_language();?>

											});

											$('.dataTables_filter').addClass('search_btn_view_page');

											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

										});

									</script>

									<div class="">

										<div id="Section1" class="">

											<div class="row">

												<div class="col-lg-12">

													<div class="">

														<div class="card-content">

															<div class="table-responsive">

																<table id="parents_list_detailpage" class="display table" cellspacing="0" width="100%">

																	<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

																		<tr>

																		<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>

																		<th><?php echo esc_attr_e( 'Parent Name & Email', 'school-mgt' ) ;?></th>

																		<th> <?php esc_attr_e( 'Mobile Number', 'school-mgt' ) ;?></th>

																		<th> <?php esc_attr_e( 'Alt. Mobile Number', 'school-mgt' ) ;?></th>

																		<th> <?php echo esc_attr_e( 'Relation', 'school-mgt' ) ;?></th>

																		<th> <?php echo esc_attr_e( 'Address', 'school-mgt' ) ;?></th>

																		</tr>

																	</thead>

																	<tbody>

																	<?php

																	if(!empty($user_meta))

																	{

																		foreach($user_meta as $parentsdata)

																		{

																			if(!empty($parentsdata->errors))

																			{

																				$parent = "";

																			}

																			else

																			{

																				$parent=get_userdata($parentsdata);

																			}

							

																			if (!empty($parent)) 

																			{

																				

																			?>

																			

																			<tr>

																			<td class="width_50px"><?php 

																				if($parentsdata)

																				{

																					$umetadata=mj_smgt_get_user_image($parentsdata);

																				}

																				if(empty($umetadata))

																				{

																					echo '<img src='.get_option( 'smgt_parent_thumb_new' ).' height="50px" width="50px" class="img-circle" />';

																				}

																				else

																					echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';?>

																			</td>

																			<td class="name">

																				<a class="color_black" href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&parent_id=<?php echo $parent->ID;?>">

																					<?php echo mj_smgt_get_parent_name_byid($parent->ID);?>

																				</a>

																				<br>

																				<label class="list_page_email"><?php echo $parent->user_email;?></label>

																			</td>

																			<td>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $parent->mobile_number;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile Number','school-mgt');?>" ></i></td>

																			<td><?php if(!empty($parent->phone)){ echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $parent->phone; }else{ echo "N/A";} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Alt. Mobile Number','school-mgt');?>" ></i></td>

																			<td><?php if($parent->relation=='Father'){ echo esc_attr__('Father','school-mgt'); }elseif($parent->relation=='Mother'){ echo esc_attr__('Mother','school-mgt');} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Relation','school-mgt');?>" ></i></td>

																			<td><?php 

																			 $task_subject = esc_html($parent->address);

																			 $max_length = 25; // Adjust this value to your desired maximum length
																			if($parent->address)
																			{
																				if (strlen($task_subject) > $max_length) {

																					echo substr($task_subject, 0, $max_length) . '...';
   
																				} else {
   
																					echo $task_subject;
   
																				}
																			}
																			else{
																				echo 'N/A';
																			}

																			 ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if($parent->address){ echo $parent->address; }else{ echo esc_attr__('Address','school-mgt'); }?>" ></i></td>

																		</tr>

																	<?php

																}

																		}

																	}

																	?>

																</tbody>

																</table>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<?php

								}

								else

								{

									$page_1='parent';

									$parent_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);

									if($parent_1['add']=='1')

									{

										?>

										<div class="no_data_list_div no_data_img_mt_30px"> 

											<a href="<?php echo home_url().'?dashboard=user&page=parent&tab=addparent';?>">

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



							// feespayment tab start 

							elseif($active_tab1 == "feespayment")

							{

								$fees_payment  = mj_smgt_get_fees_payment_detailpage($student_id);

								if(!empty($fees_payment))

								{

									?>

									<div class="popup-bg">

										<div class="overlay-content">

											<div class="modal-content">

												<div class=" invoice_data"></div>

												<div class="category_list">

												</div>     

											</div>

										</div>

									</div>

									<script type="text/javascript">

										jQuery(document).ready(function($) {

											"use strict";

											jQuery('#feespayment_list_detailpage').DataTable({

												"order": [[ 1, "desc" ]],

												"aoColumns":[	                  

															{"bSortable": false},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true}],

												dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												language:<?php echo mj_smgt_datatable_multi_language();?>

												});

											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...','school-mgt');?>");

											$('.dataTables_filter').addClass('search_btn_view_page');

										} );

									</script>

								

									<div class="table-div"><!-- PANEL BODY DIV START -->

										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->

											<table id="feespayment_list_detailpage" class="display" cellspacing="0" width="100%">

												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

													<tr>

														<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

														<th><?php esc_attr_e('Fees Type','school-mgt');?></th>  

														<th><?php esc_attr_e('Student Name','school-mgt');?></th>

														<th><?php esc_attr_e('Class Name','school-mgt');?></th>  

														<th><?php esc_attr_e('Total Amount','school-mgt');?> </th>  

														<th><?php esc_attr_e('Paid Amount','school-mgt');?> </th>  

														<th><?php esc_attr_e('Due Amount','school-mgt'); ?></th>

														<th><?php esc_attr_e('Payment Status','school-mgt');?></th>

														<th><?php esc_attr_e('Start Year To End Year','school-mgt');?></th>

													</tr>

												</thead>

												<tbody>

													<?php 

													$i=0;	

													if(!empty($fees_payment))

													{

														foreach ($fees_payment as $retrieved_data)

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

																<td class="cursor_pointer user_image show-view-payment-popup width_50px profile_image_prescription" idtest="<?php echo $retrieved_data->fees_pay_id; ?>" view_type="view_payment">

																	<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	

																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center">

																	</p>

																</td>

																<td class="cursor_pointer">

																	<a  href="?dashboard=user&page=feepayment&tab=view_fesspayment&idtest=<?php echo $retrieved_data->fees_pay_id; ?>&view_type=view_payment">

																	<?php 

																	$fees_id=explode(',',$retrieved_data->fees_id);

																	$fees_type=array();

																	foreach($fees_id as $id)

																	{ 

																		$fees_type[] = mj_smgt_get_fees_term_name($id);

																	}

																	echo implode(" , " ,$fees_type);	

																		?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Fees Type','school-mgt');?>"></i>

																</td>

																<td><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>

																<td class="name"><?php if($retrieved_data->class_id){ echo smgt_get_class_section_name_wise($retrieved_data->class_id,$retrieved_data->section_id); }else { esc_attr_e('N/A','school-mgt');}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

																

																<td><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . number_format($retrieved_data->total_amount,2,'.',''); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>

																<td class="department"><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . number_format($retrieved_data->fees_paid_amount,2,'.',''); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Paid Amount','school-mgt');?>"></i></td>

																<?php 

																$Due_amt = $retrieved_data->total_amount-$retrieved_data->fees_paid_amount;

																$due_amount=number_format($Due_amt,2,'.','');

																?>

																<td><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" .$due_amount; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Due Amount','school-mgt');?>"></i></td>

																<td>

																	<?php 

																	$smgt_get_payment_status=mj_smgt_get_payment_status($retrieved_data->fees_pay_id);

																	if($smgt_get_payment_status == 'Not Paid')

																	{

																	echo "<span class='red_color'>";

																	}

																	elseif($smgt_get_payment_status == 'Partially Paid')

																	{

																		echo "<span class='perpal_color'>";

																	}

																	else

																	{

																		echo "<span class='green_color'>";

																	}

																	

																	echo esc_html__("$smgt_get_payment_status","school-mgt");					 

																	echo "</span>";						

																	?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Payment Status','school-mgt');?>"></i>

																</td>

																<td><?php echo $retrieved_data->start_year.'-'.$retrieved_data->end_year;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start Year To End Year','school-mgt');?>"></i></td>

															</tr>

															<?php 

															$i++;	

														}	 

													}

													?>

												</tbody>

											</table>

											

										</div><!-- TABLE RESPONSIVE DIV END -->

									</div>

									<?php

								}

								else

								{

									$page_1='feepayment';

									$feepayment_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);

									if($feepayment_1['add']=='1')

									{

										?>

										<div class="no_data_list_div no_data_img_mt_30px"> 

											<a href="<?php echo home_url().'?dashboard=user&page=feepayment&tab=addpaymentfee';?>">

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



							// attendance tab start 

							elseif($active_tab1 == "attendance")

							{

								$attendance_list = mj_smgt_monthly_attendence($student_id);

									

								if(!empty($attendance_list))

								{

									?>

									<script type="text/javascript">

										jQuery(document).ready(function($) {

											"use strict";

											jQuery('#attendance_list_detailpage').DataTable({

												//"order": [[ 1, "desc" ]],
												//stateSave: true,
												"aoColumns":[	                  

															{"bSortable": false},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true}],

												dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												language:<?php echo mj_smgt_datatable_multi_language();?>

												});

											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...','school-mgt');?>");

											$('.dataTables_filter').addClass('search_btn_view_page');

										} );

									</script>

								

									<div class="table-div"><!-- PANEL BODY DIV START -->

										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->

											<table id="attendance_list_detailpage" class="display" cellspacing="0" width="100%">

												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

													<tr>

														<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

														<th><?php esc_attr_e('Student Name','school-mgt');?></th>

														<th><?php esc_attr_e('Class Name','school-mgt');?></th>  

														<th><?php esc_attr_e('Attendance Date','school-mgt');?> </th>  

														<th><?php esc_attr_e('Day','school-mgt');?> </th>  

														<th><?php esc_attr_e('Status','school-mgt'); ?></th>

														<th><?php esc_html_e('Attendance By','school-mgt');?></th>

														<th><?php esc_attr_e('Attendance With QR Code','school-mgt');?></th>

														<th><?php esc_attr_e('Comment','school-mgt');?></th>

													</tr>

												</thead>

												<tbody>

													<?php 

													

													$i=0;	

													$srno = 1;

													if(!empty($attendance_list))

													{

														foreach ($attendance_list as $retrieved_data)

														{

															$class_section_sub_name=smgt_get_class_section_subject($retrieved_data->class_id,$retrieved_data->section_id,$retrieved_data->sub_id);

															$created_by = get_userdata($retrieved_data->attend_by);

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

																	<a href="?dashboard=user&page=attendance&tab=student_attendance"><p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	

																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image center">

																	</p></a>

																</td>

																<td class="department"><a href="?dashboard=user&page=attendance&tab=student_attendance"><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->user_id);?></a><i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>

																<td class="">

																	<?php echo $class_section_sub_name; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i>

																</td>

																<?php $curremt_date=mj_smgt_getdate_in_input_box($retrieved_data->attendance_date); $day=date("D", strtotime($curremt_date)); ?>

																<td class="name"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendance_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendence Date','school-mgt');?>"></i></td>

																<td class="department"><?php  

																if($day == 'Mon')

																{

																	esc_html_e('Monday','school-mgt');

																}  

																elseif($day == 'Sun')

																{

																	esc_html_e('Sunday','school-mgt');

																} 

																elseif($day == 'Tue')

																{

																	esc_html_e('Tuesday','school-mgt');

																}

																elseif($day == 'Wed')

																{

																	esc_html_e('Wednesday','school-mgt');

																}

																elseif($day == 'Thu')

																{

																	esc_html_e('Thursday','school-mgt');

																}

																elseif($day == 'Fri')

																{

																	esc_html_e('Friday','school-mgt');

																}

																elseif($day == 'Sat')

																{

																	esc_html_e('Saturday','school-mgt');

																}

																?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>

																<td> <?php $status_color =  MJ_smgt_attendance_status_color($retrieved_data->status);?>
																	<span style="color:<?php echo $status_color;?>;">
																		<?php echo esc_html__($retrieved_data->status,"school-mgt"); ?>
																	</span>
																	<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>

																<td class="name">

																	<?php echo esc_html__($created_by->display_name,"school-mgt"); ?>

																	<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance By','school-mgt');?>" ></i>

																</td>

																<td class="width_20">

																<?php if ($retrieved_data->attendence_type == 'QR') {

																	echo esc_html__("Yes","school-mgt");

																}

																else{

																	echo esc_html__("No","school-mgt");

																}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendance With QR Code','school-mgt');?>"></i></td>

																<td class="name">

																	<?php

																	if(!empty($retrieved_data->comment))

																	{   $comment = $retrieved_data->comment;

																		$grade_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;

																		echo $grade_comment;

																	}

																	else

																	{

																		echo "N/A";

																	}

																	?>

																	<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php 

																	if(!empty($retrieved_data->comment))

																	{ 

																		echo $retrieved_data->comment;

																	} 

																	else

																	{  _e('Comment','school-mgt');

																	}

																	?>

																	"></i>

																</td>

															</tr>

															<?php 

															$i++;	

															$srno++;

														}	 

													}

													?>

												</tbody>

											</table>

											

										</div><!-- TABLE RESPONSIVE DIV END -->

									</div>

									<?php

								}

								else

								{

									$page_1='attendance';

									$fattendance_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);

									if($fattendance_1['add']=='1')

									{

										?>

										<div class="no_data_list_div no_data_img_mt_30px"> 

											<a href="<?php echo home_url().'?dashboard=user&page=attendance&tab=student_attendance&tab1=subject_attendence';?>">

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

							elseif ($active_tab1=='leave_list') 

							{

								$obj_leave = new SmgtLeave();

								$leave_data=$obj_leave->get_single_user_leaves($student_id);	

								if(!empty($leave_data))

								{

									?>

									<script type="text/javascript">

									jQuery(document).ready(function($)

									{

										"use strict";	

										var table =  jQuery('#leave_list').DataTable({

											

											"order": [[ 6, "desc" ]],

											"aoColumns":[	                  

														{"bSortable": false},

														{"bSortable": true},

														{"bSortable": true},

														{"bSortable": true},

														{"bSortable": true},

														{"bSortable": true},

														{"bSortable": true},

														{"bSortable": true},

														{"bSortable": true}],

														dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												language:<?php echo mj_smgt_datatable_multi_language();?>

												});

											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

											$('.dataTables_filter').addClass('search_btn_view_page');

										});

									</script>

									<div class="table-responsive"><!-- table-responsive -->

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

														<td><?php $sname = mj_smgt_student_display_name_with_roll($retrieved_data->student_id); if($sname != ''){echo $sname;}else{echo 'N/A';} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td> 

																		

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

														<td>

															<?php 

																$duration = hrmgt_leave_duration_label($retrieved_data->leave_duration);

																echo esc_html__($duration,'school-mgt');

															?> 

															<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Leave Duration','school-mgt');?>"></i></td>

														<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->start_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Leave Start Date','school-mgt');?>"></i></td> 

														<td><?php if(!empty($retrieved_data->end_date)){echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);}else{echo "N/A";}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Leave End Date','school-mgt');?>"></i></td> 

														<td>

															<?php

															$status = $retrieved_data->status;

															if($status == "Approved"){

																echo "<span class='green_color'> " .esc_html__($status,'school-mgt')." </span>";

															}

															else

															{

																echo "<span class='red_color'> " .esc_html__($status,'school-mgt')." </span>";

															}

															// echo $retrieved_data->status;

															?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>

														<td><?php 

																$comment =$retrieved_data->reason;

																$reason = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;

																echo $reason;

															?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($comment)){echo $comment;}else{ esc_html_e('Reason','school-mgt');}?>"></i></td>

													</tr>

													<?php 

													$i++;

												} 

												?>

											</tbody>

										</table>

									</form>

									</div><!--------- Table Responsive ------->

								<?php

								}

								else{

									$page_1='leave';

									$fattendance_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);

									if($fattendance_1['add']=='1')

									{

									?>

									<div class="no_data_list_div"> 

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

								

							}

							// hallticket tab start 

							elseif($active_tab1 == "hallticket")

							{

								$hall_ticket = mj_smgt_hallticket_list($student_id);

							

								if(!empty($hall_ticket))

								{

									?>

									<script type="text/javascript">

										jQuery(document).ready(function($) {

											"use strict";

											jQuery('#hall_ticket_detailpage').DataTable({
												//stateSave: true,
												"order": [[ 1, "desc" ]],

												"aoColumns":[	                  

															{"bSortable": false},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true}],

												dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												language:<?php echo mj_smgt_datatable_multi_language();?>

												});

											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...','school-mgt');?>");

											$('.dataTables_filter').addClass('search_btn_view_page');

										} );

									</script>

								

									<div class="table-div"><!-- PANEL BODY DIV START -->

										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->

											<table id="hall_ticket_detailpage" class="display" cellspacing="0" width="100%">

												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

													<tr>

														<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

														<th><?php esc_attr_e('Hall Name','school-mgt');?></th>  

														<th><?php esc_attr_e('Student Name','school-mgt');?></th>

														<th><?php esc_attr_e('Exam Name','school-mgt');?></th>  

														<th><?php esc_attr_e('Exam Term','school-mgt');?> </th>  

														<th><?php esc_attr_e('Exam Start To End Date','school-mgt');?> </th>  

														<th><?php esc_attr_e('Action','school-mgt'); ?></th>

													</tr>

												</thead>

												<tbody>

													<?php 

													$i=0;	

													if(!empty($hall_ticket))

													{

														foreach ($hall_ticket as $retrieved_data)

														{

															$exam_data= mj_smgt_get_exam_by_id($retrieved_data->exam_id);

															$start_date=$exam_data->exam_start_date;

															$end_date=$exam_data->exam_end_date; 

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

																	<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	

																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Exam_hall.png"?>" alt="" class="massage_image center image_icon_height_25px">

																	</p>

																</td>

																<td><?php echo mj_smgt_get_hall_name($retrieved_data->hall_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Hall Name','school-mgt');?>"></i></td>

																<td class="department"><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->user_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>

																<td class="name"><?php echo mj_smgt_get_exam_name_id($retrieved_data->exam_id); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Name','school-mgt');?>"></i></td>

																<td class="department"><?php echo get_the_title($exam_data->exam_term); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Term','school-mgt');?>"></i></td>

																<td class="department"><?php echo mj_smgt_getdate_in_input_box($start_date); ?><?php echo esc_html_e(" To ","school-mgt"); ?><?php echo mj_smgt_getdate_in_input_box($end_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Start To End Date','school-mgt');?>"></i></td>

																<td class="action"> 

																	<div class="smgt-user-dropdown">

																		<ul class="" style="margin-bottom: 0px !important;">

																			<li class="">

																				<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

																					<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

																				</a>

																				<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																				

																					<li class="float_left_width_100">

																						<a href="?page=smgt_student&student_exam_receipt=student_exam_receipt&student_id=<?php echo $retrieved_data->user_id;?>&exam_id=<?php echo $retrieved_data->exam_id;?>" target="_blank" class="float_left_width_100"><i class="fa fa-print"> </i><?php esc_html_e('Hall Ticket Print', 'school-mgt' ) ;?> </a>

																					</li>

																					<li class="float_left_width_100">

																						<a href="?page=smgt_student&student_exam_receipt_pdf=student_exam_receipt_pdf&student_id=<?php echo $retrieved_data->user_id;?>&exam_id=<?php echo $retrieved_data->exam_id;?>" target="_blank" class="float_left_width_100"><i class="fa fa-file-pdf-o"> </i><?php esc_attr_e('Hall Ticket PDF', 'school-mgt');?></a>

																					</li>

																				</ul>

																			</li>

																		</ul>

																	</div>										

																</td>

															</tr>

															<?php 

															$i++;	

														}	 

													}

													?>

												</tbody>

											</table>

											

										</div><!-- TABLE RESPONSIVE DIV END -->

									</div>

									<?php

								}

								else

								{

									if($role_name !="student")

									{

										$page_1='exam_hall';

										$exam_hall_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);

										

										if($exam_hall_1['add']=='1')

										{

											?>

											<div class="no_data_list_div no_data_img_mt_30px"> 

												<a href="<?php echo home_url().'?dashboard=user&page=exam_hall&tab=exam_hall_receipt';?>">

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



							// homework tab start 

							elseif($active_tab1 == "homework")

							{

								$student_homework=mj_smgt_student_homework_detail($student_id);

								if(!empty($student_homework))

								{

									?>

									<script type="text/javascript">

										jQuery(document).ready(function($) {

											"use strict";

											jQuery('#homework_detailpage').DataTable({

												"order": [[ 1, "desc" ]],

												"aoColumns":[	                  

															{"bSortable": false},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true}],

												dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												language:<?php echo mj_smgt_datatable_multi_language();?>

												});

											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...','school-mgt');?>");

											$('.dataTables_filter').addClass('search_btn_view_page');

										} );

									</script>

								

									<div class="table-div"><!-- PANEL BODY DIV START -->

										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->

											<table id="homework_detailpage" class="display" cellspacing="0" width="100%">

												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

													<tr>

														<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

														<th><?php esc_attr_e('Homework Title','school-mgt');?></th>

														<th><?php esc_attr_e('Class Name','school-mgt');?></th>

														<th><?php esc_attr_e('Subject Name','school-mgt');?></th>

														<th><?php esc_attr_e('Status','school-mgt');?></th>

														<th><?php esc_attr_e('Submission Date','school-mgt');?></th>

														<th><?php esc_attr_e('Homework Date','school-mgt');?></th>

													</tr>

												</thead>

												<tbody>

													<?php 

													

													$i=0;	

													if(!empty($student_homework))

													{

														foreach ($student_homework as $retrieved_data)

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

																	<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	

																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/homework.png"?>" alt="" class="massage_image center image_icon_height_25px">

																	</p>

																</td>

																<td><?php echo $retrieved_data->title;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Homework Title','school-mgt');?>"></i></td>

																<td><?php echo mj_smgt_get_class_name($retrieved_data->class_name);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>  

																<td><?php echo mj_smgt_get_single_subject_name($retrieved_data->subject);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Name','school-mgt');?>"></i></td>

																<?php

															

																if($retrieved_data->uploaded_date == NULL)

																{

																?>

																<td><label class="red_color"><?php esc_attr_e('Pending','school-mgt'); ?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>

																<?php

																}

																elseif($retrieved_data->uploaded_date <= $retrieved_data->submition_date)

																{

																?><td><label class="green_color"><?php esc_attr_e('Submitted','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td><?php

																}

																else

																{

																	?><td><label class="perpal_color"><?php esc_attr_e('Late Submitted','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td><?php

																}

															

																?>

																<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->submition_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Submission Date','school-mgt');?>"></i></td>

																<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Homework Date','school-mgt');?>"></i></td>

																

															</tr>

															<?php 

															$i++;	

														}	 

													}

													?>

												</tbody>

											</table>

											

										</div><!-- TABLE RESPONSIVE DIV END -->

									</div>

									<?php

								}

								else

								{

									$page_1='homework';

									$homework_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);

									if($homework_1['add']=='1')

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

							}



							// issuebooks tab start 

							elseif($active_tab1 == "issuebook")

							{

								$student_issuebook=mj_smgt_student_issuebook_detail($student_id);

								if(!empty($student_issuebook))

								{

									?>

									<script type="text/javascript">

										jQuery(document).ready(function($) {

											"use strict";

											jQuery('#issuebook_detailpage').DataTable({
												//stateSave: true,
												"order": [[ 1, "desc" ]],

												"aoColumns":[	                  

															{"bSortable": false},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true}],

												dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												language:<?php echo mj_smgt_datatable_multi_language();?>

												});

											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...','school-mgt');?>");

											$('.dataTables_filter').addClass('search_btn_view_page');

										} );

									</script>

								

									<div class="table-div"><!-- PANEL BODY DIV START -->

										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->

											<table id="issuebook_detailpage" class="display" cellspacing="0" width="100%">

												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

													<tr>

														<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

														<th><?php esc_attr_e('Student Name','school-mgt');?></th>

														<th><?php esc_attr_e('Class Name','school-mgt');?></th>

														<th><?php esc_attr_e('Book Title','school-mgt');?></th>

														<th><?php esc_attr_e('Issue Date','school-mgt');?></th>

														<th><?php esc_attr_e('Expected Return Date','school-mgt');?></th>

														<th><?php esc_attr_e('Time Period','school-mgt');?></th>

													</tr>

												</thead>

												<tbody>

													<?php 

													$i=0;	

													if(!empty($student_issuebook))

													{

														foreach ($student_issuebook as $retrieved_data)

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

																	<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	

																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Library.png"?>" alt="" class="massage_image center image_icon_height_25px">

																	</p>

																</td>

																<td class="department"><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?><i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name & Roll No.','school-mgt');?>"></i></td>

																<td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

																<td><?php echo stripslashes(mj_smgt_get_bookname($retrieved_data->book_id));?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Book Title','school-mgt');?>"></i></td>

																<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->issue_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Issue Date','school-mgt');?>"></i></td>

																<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Expected Return Date','school-mgt');?>"></i></td>

																<td><?php echo get_the_title($retrieved_data->period);?><?php echo ' '.esc_attr__('Days','school-mgt'); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Time Period','school-mgt');?>"></i></td>

																

															</tr>

															<?php 

															$i++;	

														}	 

													}

													?>

												</tbody>

											</table>

											

										</div><!-- TABLE RESPONSIVE DIV END -->

									</div>

									<?php

								}

								else

								{

									$page_1='library';

									$library_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);

									if($library_1['add']=='1')

									{

										?>

										<div class="no_data_list_div no_data_img_mt_30px"> 

											<a href="<?php echo home_url().'?dashboard=user&page=library&tab=issuebook';?>">

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



							if($active_tab1 == "exam_result")

							{

								$obj_mark = new Marks_Manage(); 

								$uid = $_REQUEST['student_id'];

								$user =get_userdata( $uid );

								$user_meta =get_user_meta($uid);

								

								$class_id = $user_meta['class_name'][0];

								

								$section_id = $user_meta['class_section'][0];

							

								$subject = $obj_mark->mj_smgt_student_subject_list($class_id,$section_id);

								$total_subject=count($subject);

								$total = 0;

								$grade_point = 0;

								if((int)$section_id !== 0)

								{

									$all_exam = mj_smgt_get_all_exam_by_class_id_and_section_id_array($class_id,$section_id);

								}

								else

								{

									$all_exam = mj_smgt_get_all_exam_by_class_id($class_id);

								}

								if(!empty($all_exam))

								{

									?>

									<script type="text/javascript">

										jQuery(document).ready(function($) {

											"use strict";

											jQuery('#messages_detailpage').DataTable({

												"responsive": true,	

												"order": [[ 1, "desc" ]],

												"aoColumns":[	                  

															{"bSortable": false},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": false}],

												dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												language:<?php echo mj_smgt_datatable_multi_language();?>

												});

												$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

											$('.dataTables_filter').addClass('search_btn_view_page');

										} );

									</script>

									<div class="table-div"><!-- PANEL BODY DIV START -->

										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->

											<table id="messages_detailpage" class="display" cellspacing="0" width="100%">

												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

													<tr> 

														<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

														<th><?php esc_attr_e('Exam Name','school-mgt');?></th>

														<th><?php esc_attr_e('Start Date','school-mgt');?></th>

														<th><?php esc_attr_e('End Date','school-mgt');?></th>

														<th class="exam_exam"><?php esc_attr_e('Action','school-mgt');?></th>

													</tr>

												</thead>

												<tbody>

													<?php 

													// var_dump($all_exam);

													// die;

													$i=0;	

													if(!empty($all_exam))

													{

														foreach ($all_exam as $retrieved_data)

														{

															$exam_id =$retrieved_data->exam_id;

															// $sender_id=$retrieved_data->sender;

															// $sender=MJ_smgt_get_display_name($sender_id);

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

																	<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	

																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Exam_hall.png"?>" alt="" class="massage_image center image_icon_height_25px">

																	</p>

																</td>

																<td class="subject_name width_20px">

																	<label class=""><?php echo _e($retrieved_data->exam_name,"school-mgt"); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Name','school-mgt');?>"></i></label>

																</td>

																<td class="department width_15px">

																	<label class=""><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_start_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start Date','school-mgt');?>"></i></label>

																</td>

																<td class="department width_15px">

																	<label class=""><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_end_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('End Date','school-mgt');?>"></i></label>

																</td>

																<td class="department width_20px">

																	<?php

																	foreach($subject as $sub) /*** ####  SUBJECT LOOPS STARTS **/

																	{

																		$marks = $obj_mark->mj_smgt_get_marks($exam_id,$class_id,$sub->subid,$uid);

																		if(!empty($marks))

																		{

																			$new_marks = $marks;



																		}

																	}

																	if(!empty($new_marks))

																	{

																		?>

																		<div class="col-md-12 row padding_left_50px  smt_view_result">

																			<div class="col-md-2 width_50">

																				<a href="?page=smgt_student&print=pdf&student=<?php echo $uid;?>&exam_id=<?php echo $exam_id;?>" class="float_right" target="_blank"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/PDF.png"?>" alt=""></a>

																			</div>

																			<div class="col-md-2 width_50 rtl_margin_left_20px" style="margin-right:22px; margin-left:5px;">

																				<a href="?page=smgt_student&print=print&student=<?php echo $uid;?>&exam_id=<?php echo $exam_id;?>" class="float_right" target="_blank" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Print.png"?>" alt=""></a>

																			</div>

																		</div>

																		<?php

																	}

																	else{

																		esc_attr_e('No Result Available.','school-mgt');

																	}

																	?>

																	<!-- <label class=""><?php echo $retrieved_data->exam_name; ?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject','school-mgt');?>"></i></label> -->

																</td>

															</tr>

															<?php 

															$i++;	

														}	 

													}

													?>

												</tbody>

											</table>

										</div><!-- TABLE RESPONSIVE DIV END -->

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



							// Message Tab Start

							if($active_tab1 == "message")

							{

								$student_message=MJ_smgt_msg_detail($student_id);

								if(!empty($student_message))

								{

									?>

									<script type="text/javascript">

										jQuery(document).ready(function($) {

											"use strict";

											jQuery('#messages_detailpage').DataTable({

												"order": [[ 1, "desc" ]],

												"aoColumns":[	                  

															{"bSortable": false},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true},

															{"bSortable": true}],

												dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

												language:<?php echo mj_smgt_datatable_multi_language();?>

												});

											$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...','school-mgt');?>");

											$('.dataTables_filter').addClass('search_btn_view_page');

										} );

									</script>

									<div class="table-div"><!-- PANEL BODY DIV START -->

										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->

											<table id="messages_detailpage" class="display" cellspacing="0" width="100%">

												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

													<tr>

														<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

														<th><?php esc_attr_e('Sender','school-mgt');?></th>

														<th><?php esc_attr_e('Subject','school-mgt');?></th>

														<th><?php esc_attr_e('Description','school-mgt');?></th>

														<th><?php esc_attr_e('Date','school-mgt');?></th>

													</tr>

												</thead>

												<tbody>

													<?php 

													$i=0;	

													if(!empty($student_message))

													{

														foreach ($student_message as $retrieved_data)

														{

															$sender_id=$retrieved_data->sender;

															$sender=MJ_smgt_get_display_name($sender_id);

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

																	<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	

																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Message_Chat.png"?>" alt="" class="massage_image center image_icon_height_25px">

																	</p>

																</td>

																<td class="subject_name width_20px">

																	<label class=""><?php echo _e($sender,"school-mgt"); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Sender','school-mgt');?>"></i></label>

																</td>

																<td class="department width_20px">

																	<label class=""><?php echo $retrieved_data->subject; ?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject','school-mgt');?>"></i></label>

																</td>

																<?php

																$massage =$retrieved_data->message_body;

																$massage_out = strlen($massage) > 30 ? substr($massage,0,30)."..." : $massage;

																?>

																<td class="specialization">

																	<label class=""><?php echo $massage_out; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Description','school-mgt');?>"></i></label>

																</td>

																<td class="department width_15px">

																	<label class=""><?php echo mj_smgt_getdate_in_input_box($retrieved_data->date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></label>

																</td>

															</tr>

															<?php 

															$i++;	

														}	 

													}

													?>

												</tbody>

											</table>

										</div><!-- TABLE RESPONSIVE DIV END -->

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

							// Message Tab End 

							?>	

						</div><!-- END PANEL BODY DIV-->

					</section>

					<!-- Detail Page Body Content Section End -->

				</div>

			</div>

			<?php

		}

		?>

	</div>

</div>
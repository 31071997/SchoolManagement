<?php



//-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();

//--------------- ACCESS WISE ROLE -----------//

$role_name=mj_smgt_get_user_role(get_current_user_id());

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

?>

<!-- POP up code -->

<div class="popup-bg">

    <div class="overlay-content">

		<div class="modal-content">

			<div class="view_popup"></div>

			<div class="invoice_data"></div>

			<div class="category_list"></div> 					

		</div>

    </div>    

</div>

<!-- End POP-UP Code -->

<?php 



	$obj_lib= new Smgtlibrary();

	//--------------Delete code-------------------------------

	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')

	{

		$result=$obj_lib->mj_smgt_delete_book($_REQUEST['book_id']);

		if($result)

		{

			wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=1');

		}

	}

	if(isset($_REQUEST['delete_selected_book']))

	{		

		if(!empty($_REQUEST['id']))

		foreach($_REQUEST['id'] as $id)

			$result=$obj_lib->mj_smgt_delete_book($id);

		if($result)

			{

				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=1');

			}

	}

	//----------------------- ISSUE BOOK DELETE ------------------------//

	

	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete' && $_REQUEST['tab']=='issuelist' && isset($_REQUEST['issuebook_id']))

	{

			

		$result=$obj_lib->mj_smgt_delete_issuebook($_REQUEST['issuebook_id']);

		if($result)

		{

			wp_redirect ( home_url() . '?dashboard=user&page=library&tab=issuelist&message=2');

		}

	}

	//-------------------------- DELETE SELECTED ISSUEBOOK ---------------------//

	if(isset($_REQUEST['delete_selected_issuebook']))

	{		

		if(!empty($_REQUEST['id']))

		{

			foreach($_REQUEST['id'] as $id)

			{

				$result=$obj_lib->mj_smgt_delete_issuebook($id);

				if($result)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=issuelist&message=2');

				}

			}

			

		}

	}

	//------------------Edit-Add code ------------------------------

	if(isset($_POST['save_book']))

	{

		$nonce = $_POST['_wpnonce'];

		if ( wp_verify_nonce( $nonce, 'save_book_frontend_nonce' ) )

		{

			if($_REQUEST['action']=='edit')

			{

				$result=$obj_lib->mj_smgt_add_book($_POST);

				if($result)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=4');	

				}

			}

			else

			{

				$result=$obj_lib->mj_smgt_add_book($_POST);

				if($result)

				{ 

					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=3');	

				}

			}

		}	

	}

	//--------------------------- SAVE ISSUE BOOK ----------------------//

	if(isset($_POST['save_issue_book']))

	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'issue_book_frontend_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{
				$result=$obj_lib->mj_smgt_add_issue_book($_POST);

				if($result)
				{ 
					/* Book Issue Mail Notification */
					if(isset($_POST['smgt_issue_book_mail_service_enable']))
					{
						foreach($_POST['book_id'] as $book_id)
						{
							$smgt_issue_book_mail_service_enable = $_POST['smgt_issue_book_mail_service_enable'];
							if($smgt_issue_book_mail_service_enable)
							{	
								$search['{{student_name}}']	 	= 	mj_smgt_get_teacher($_POST['student_id']);
								$search['{{book_name}}'] 	    = 	mj_smgt_get_bookname($book_id);	
								$search['{{issue_date}}'] 	    = 	mj_smgt_getdate_in_input_box($_POST['issue_date']);							
								$search['{{return_date}}'] 	    = 	mj_smgt_getdate_in_input_box($_POST['return_date']);					
								$search['{{school_name}}'] 		= 	get_option('smgt_school_name');								
								$message = mj_smgt_string_replacement($search,get_option('issue_book_mailcontent'));
								$mail_id=mj_smgt_get_emailid_byuser_id($_POST['student_id']);		
								if(get_option('smgt_mail_notification') == '1')
								{
									wp_mail($mail_id,get_option('issue_book_title'),$message);
								}	
							}
						}	
					}
					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=issuelist&message=5');
				}
			}
			else
			{
				$result=$obj_lib->mj_smgt_add_issue_book($_POST);
				if($result)
				{ 
					/* Book Issue Mail Notification */
					if(isset($_POST['smgt_issue_book_mail_service_enable']))
					{
						foreach($_POST['book_id'] as $book_id)
						{
							$smgt_issue_book_mail_service_enable = $_POST['smgt_issue_book_mail_service_enable'];
							if($smgt_issue_book_mail_service_enable)
							{	
								$search['{{student_name}}']	 	= 	mj_smgt_get_teacher($_POST['student_id']);
								$search['{{book_name}}'] 	    = 	mj_smgt_get_bookname($book_id);	
								$search['{{issue_date}}'] 	    = 	mj_smgt_getdate_in_input_box($_POST['issue_date']);							
								$search['{{return_date}}'] 	    = 	mj_smgt_getdate_in_input_box($_POST['return_date']);					
								$search['{{school_name}}'] 		= 	get_option('smgt_school_name');								
								$message = mj_smgt_string_replacement($search,get_option('issue_book_mailcontent'));
								$mail_id=mj_smgt_get_emailid_byuser_id($_POST['student_id']);

								if(get_option('smgt_mail_notification') == '1')
								{
									wp_mail($mail_id,get_option('issue_book_title'),$message);
								}	
							}
						}	
					}
					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=issuelist&message=6'); 
				}

			}

		}

	}

	//------------------ SUBMIT BOOK ------------------------//

	if(isset($_POST['submit_book']))

	{

		$result=$obj_lib->mj_smgt_submit_return_book($_POST);

		if($result)

		{ ?>

			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>

				</button>

				<?php esc_attr_e('Book Submitted Successfully','school-mgt');?>

			</div>

		<?php 

		}

	}	

	/* Save Book Import Data */



	//upload booklist csv	

	if(isset($_REQUEST['upload_csv_file']))

	{		

		if(isset($_FILES['csv_file']))

		{				

			$errors= array();

			$file_name = $_FILES['csv_file']['name'];

			$file_size =$_FILES['csv_file']['size'];

			$file_tmp =$_FILES['csv_file']['tmp_name'];

			$file_type=$_FILES['csv_file']['type'];			

			$value = explode(".", $_FILES['csv_file']['name']);

			$file_ext = strtolower(array_pop($value));

			$extensions = array("csv");

			$upload_dir = wp_upload_dir();

			if(in_array($file_ext,$extensions )=== false)

			{

				$errors[]="this file not allowed, please choose a CSV file.";

				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=8'); 

			}

			if($file_size > 2097152)

			{

				$errors[]='File size limit 2 MB';

				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=9');

			}			

			if(empty($errors)==true)

			{	

				$rows = array_map('str_getcsv', file($file_tmp));		

				$header = array_map('strtolower',array_shift($rows));

				

				$csv = array();

				foreach ($rows as $row) 

				{

					$csv = array_combine($header, $row);

					

					if(isset($csv['isbn']))

						$bookdata['isbn']=$csv['isbn'];

					if(isset($csv['book_name']))

						$bookdata['book_name']=$csv['book_name'];

					if(isset($csv['author_name']))

						$bookdata['author_name']=$csv['author_name'];

					if(isset($csv['rack_location']))

						$bookdata['rack_location']=$csv['rack_location'];

					if(isset($csv['cat_id']))

						$bookdata['cat_id']=$csv['cat_id'];

					if(isset($csv['price']))

						$bookdata['price']=$csv['price'];							

					if(isset($csv['quentity']))

						$bookdata['quentity']=$csv['quentity'];							

					if(isset($csv['description']))

						$bookdata['description']=$csv['description'];

					$bookdata['added_by']=get_current_user_id();

					$bookdata['added_date']=date('Y-m-d');

									

					global $wpdb;

					$table_smgt_library_book = $wpdb->prefix. 'smgt_library_book';

					$all_book = $wpdb->get_results("SELECT * FROM $table_smgt_library_book");	

					$book_name=array();

					$book_isbn=array();

					

					foreach ($all_book as $book_data) 

					{

						$book_name[]=$book_data->book_name;

						$book_isbn[]=$book_data->ISBN;

					}

					

					if (in_array($bookdata['book_name'], $book_name) && in_array($bookdata['isbn'], $book_isbn))

					{

						$import_book_name=$bookdata['book_name'];

						$import_isbn=$bookdata['isbn'];

						

						$existing_book_data = $wpdb->get_row("SELECT id FROM $table_smgt_library_book where book_name='$import_book_name' AND ISBN='$import_isbn'");



						$id['id']=$existing_book_data->id;

												

						$wpdb->update( $table_smgt_library_book, $bookdata,$id);	

						

						$success = 1;	

					}

					else

					{ 	

						$wpdb->insert( $table_smgt_library_book, $bookdata );	

						$success = 1;	

					}	

				}

			}

			else

			{

				foreach($errors as &$error) echo $error;

			}

			

			if(isset($success))

			{			

				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=7');

			} 

		}

	}

	$active_tab = isset($_GET['tab'])?$_GET['tab']:'booklist';

	

?>

<script type="text/javascript" >

jQuery(document).ready(function($)

{

	"use strict";	

	jQuery('.datepicker').datepicker({
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",		
			changeYear:true,
			changeMonth:true,
			minDate:0

		}); 

		jQuery('#bookissue_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		

		// ----- member_list tabal js start ----- // 

		jQuery('#member_list').DataTable({

			
			//stateSave: true,
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

		// ----- member_list tabal js End ----- // 



		var table =  jQuery('#liabrary_book_list').DataTable({

			"initComplete": function(settings, json) {

			$(".print-button").css({"margin-top": "-5%"});

		},
		//stateSave: true,
			"dom": 'lifrtp',

			"order": [[ 2, "asc" ]],	

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

						{"bSortable": true},

						{"bSortable": true},

						{"bSortable": false}],			

			language:<?php echo mj_smgt_datatable_multi_language();?>

		});

	

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

			else{

				var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?','school-mgt');?>");

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



		jQuery('#book_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});





            var table =  jQuery('#issue_list').DataTable({

				"initComplete": function(settings, json) {

					$(".print-button").css({"margin-top": "-5%"});

				},

				"dom": 'lifrtp',

				"order": [[ 2, "asc" ]],

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

							  {"bSortable": false},

							  {"bSortable": true},

							  {"bSortable": true},

							  {"bSortable": true},

							  {"bSortable": true},

							  {"bSortable": false},	

							<?php

							if($user_access['edit']=='1' || $user_access['delete']=='1')

							{

								?>

							 	{"bSortable": true},

							 	 <?php

							}

							?>	                  

							  {"bSortable": false}],

				language:<?php echo mj_smgt_datatable_multi_language();?>

			});

			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");



			jQuery('#checkbox-select-all').on('click', function(){

			 

			  var rows = table.rows({ 'search': 'applied' }).nodes();

			  jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);

		   	}); 

		   

			

			jQuery('#book_list1').multiselect({

				nonSelectedText :'<?php esc_attr_e( 'Select Book', 'school-mgt' ) ;?>',

				includeSelectAllOption: true,

				selectAllText : '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',

				templates: {

					button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',

				}

			 });

			jQuery(".book_for_alert").on('click',function()

			{	

				checked = $(".multiselect_validation_book .dropdown-menu input:checked").length;

				if(!checked)

				{

				 alert(language_translate2.select_one_book_alert);

				  return false;

				}	

			}); 





	// START select student class wise

	$("body").on("change", "#class_list_lib", function()

	{	

		$('#class_section_lib').html('');

		$('#class_section_lib').append('<option value="remove">Loading..</option>');

		 var selection = $("#class_list_lib").val();

		 var optionval = $(this);

		var curr_data = {

			action: 'mj_smgt_load_class_section',

			class_id: selection,			

			dataType: 'json'

		};

		$.post(smgt.ajax, curr_data, function(response) 

		{

			$("#class_section_lib option[value='remove']").remove();

			$('#class_section_lib').append(response);	

		});					

					

	});



	// START select student class wise

	$("#class_section_lib").on('change',function(){

		 var selection = $(this).val();

		 if(selection != ''){

			$('#student_list').html('');

			var optionval = $(this);

			var curr_data = {

				action: 'mj_smgt_load_section_user',

				section_id: selection,			

				dataType: 'json'

			};

					

			$.post(smgt.ajax, curr_data, function(response) 

			{

				$('#student_list').append(response);	

			});

		 }

		

	});

	

	 $("#bookcat_list").on('change',function()

	 {				

		$("#book_list1 option[value]").remove();

		var selection = $("#bookcat_list").val();		

		var optionval = $(this);

			var curr_data = 

			{

				action: 'mj_smgt_load_books',

				bookcat_id: $("#bookcat_list").val(),			

				dataType: 'json'

			};

			$.post(smgt.ajax, curr_data, function(response) {

			$('#book_list1').append(response);

			$('#book_list1').multiselect('rebuild');

			});					

	});



});

</script>

		

<div class="panel-body panel-white frontend_list_margin_30px_res">

	<?php

	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

	switch($message)

	{

		case '1':

			$message_string = esc_attr__('Book Deleted successfully','school-mgt');

			break;

		case '2':

			$message_string = esc_attr__('Issue Book Deleted successfully.','school-mgt');

			break;	

		case '3':

			$message_string = esc_attr__('Book Added Successfully.','school-mgt');

			break;

		case '4':

			$message_string = esc_attr__('Book Updated Successfully.','school-mgt');

			break;

		case '5':

			$message_string = esc_attr__('Issue Book Updated Successfully.','school-mgt');

			break;

		case '6':

			$message_string = esc_attr__('Issue Book Added Successfully.','school-mgt');

			break;

		case '7':

			$message_string = esc_attr__('Book Uploaded Successfully.','school-mgt');

			break;

		case '8':

			$message_string = esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');

			break;

			case '9':

			$message_string = esc_attr__('File size limit 2 MB.','school-mgt');

			break;			

	}

	if($message)

	{ ?>

		<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

			</button>

			<?php echo $message_string;?>

		</div>

		<?php 

	} ?>

	<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist"><!--Start nav-tabs -->

		
		

		<li class="<?php if($active_tab=='booklist'){?>active<?php }?>">

			<a href="?dashboard=user&page=library&tab=booklist"  class="padding_left_0 tab  <?php echo $active_tab == 'booklist' ? 'active' : ''; ?>">

			<?php esc_attr_e('Book List', 'school-mgt'); ?></a>

			</a>

		</li>

		<?php

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'addbook')

		{

			?>

			<li class="<?php if($active_tab=='addbook'){?>active<?php }?>">

				<a href="?dashboard=user&page=library&tab=addbook&action=edit&book_id=<?php echo $_REQUEST['book_id'];?>"  class="padding_left_0 tab  <?php echo $active_tab == 'addbook' ? 'active' : ''; ?>">

				<?php esc_attr_e('Edit Book', 'school-mgt'); ?></a>

				</a>

			</li>

			<?php 

		}

		elseif($active_tab=='addbook')

		{

			if($user_access['add']=='1')

			{

			?>

				<li class="<?php if($active_tab=='addbook'){?>active<?php }?>">

					<a href="?dashboard=user&page=library&tab=addbook"  class="padding_left_0 tab <?php echo $active_tab == 'addbook' ? 'active' : ''; ?>">

					<?php esc_attr_e('Add Book', 'school-mgt'); ?></a>

					</a>

				</li>

			<?php 

			}

		} 

		if($school_obj->role=='supportstaff' || $school_obj->role=='teacher' )

		{ ?> 

			<li class="<?php if($active_tab=='issuelist'){?>active<?php }?>">

				<a href="?dashboard=user&page=library&tab=issuelist"  class="padding_left_0 tab <?php echo $active_tab == 'issuelist' ? 'active' : ''; ?>">

				<?php esc_attr_e('Issue List', 'school-mgt'); ?></a>

				</a>

			</li>

			<?php

		}

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'issuebook')

		{?>

			<li class="<?php if($active_tab=='issuebook'){?>active<?php }?>">

				<a href="?dashboard=user&page=library&tab=issuebook&action=edit&issuebook_id=<?php echo $_REQUEST['issuebook_id'];?>"  class="padding_left_0 tab  <?php echo $active_tab	 == 'issuebook' ? 'active' : ''; ?>">

				<?php esc_attr_e('Edit Issue Book', 'school-mgt'); ?></a>

				</a>

			</li>

			<?php 

		}

		elseif($active_tab=='issuebook')

		{

			if($user_access['add']=='1')

			{ ?>

				<li class="<?php if($active_tab=='issuebook'){?>active<?php }?>">

					<a href="?dashboard=user&page=library&tab=issuebook"  class="padding_left_0 tab  <?php echo $active_tab  == 'issuebook' ? 'active' : ''; ?>">

					<?php esc_attr_e('Add Issue Book', 'school-mgt'); ?></a>

					</a>

				</li>

				<?php 

			}

		}

		?> 
		<li class="<?php if($active_tab=='memberlist'){?>active<?php }?>">

			<a href="?dashboard=user&page=library&tab=memberlist"  class="padding_left_0 tab <?php echo $active_tab == 'memberlist' ? 'active' : ''; ?>">

				<?php esc_attr_e('Member List', 'school-mgt'); ?></a>

			</a>

		</li>

	</ul>

	<?php

	if($active_tab == 'booklist')

	{ 

		

			?>

		

			<div class="panel-body">

				<form id="frm-example" name="frm-example" method="post">

					<?php

					$user_id=get_current_user_id();

					//------- BOOK DATA FOR STUDENT ---------//

					if($school_obj->role == 'supportstaff')

					{ 

						$own_data=$user_access['own_data'];

						if($own_data == '1')

						{ 

							$retrieve_books=$obj_lib->mj_smgt_get_all_books_creted_by($user_id);

						}

						else

						{

							$retrieve_books=$obj_lib->mj_smgt_get_all_books(); 

						}

					}

					else

					{

						$retrieve_books=$obj_lib->mj_smgt_get_all_books(); 

					}

					if(!empty($retrieve_books))

					{

						?>

						<div class="table-responsive">

							<table id="liabrary_book_list" class="display dataTable booklist_datatable" cellspacing="0" width="100%">

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

										<th><?php esc_attr_e('ISBN','school-mgt');?></th>

										<th><?php esc_attr_e('Book Title','school-mgt');?></th>
										<th><?php esc_attr_e('Book Category','school-mgt');?></th>
										<th><?php esc_attr_e('Book Price','school-mgt');?></th>
										<th><?php esc_attr_e('Author Name','school-mgt');?></th>

										<th><?php esc_attr_e('Rack Location','school-mgt');?></th>

										<th><?php esc_attr_e('Remaining Quantity','school-mgt');?></th>

										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

									</tr>

								</thead>

								<tbody>

									<?php 

									if(!empty($retrieve_books))

									{

										$i=0;

										foreach ($retrieve_books as $retrieved_data)

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

														<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]"	value="<?php echo $retrieved_data->id;?>">

													</td>

													<?php

												}

												?>

												<td class="user_image width_50px profile_image_prescription padding_left_0">

													<a href="#" id="<?php echo $retrieved_data->id;?>" type="booklist_view" class="view_details_popup ">

														<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Library.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">

														</p>

													</a>

												</td>

												<td><?php echo $retrieved_data->ISBN;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('ISBN','school-mgt');?>" ></i></td>

												<td><a href="#" id="<?php echo $retrieved_data->id;?>" type="booklist_view" class="view_details_popup "><?php echo stripslashes($retrieved_data->book_name);?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Book Title','school-mgt');?>" ></td>
												<td>
													<?php echo get_the_title($retrieved_data->cat_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Book Category','school-mgt');?>" ></i>
												</td>
												<td>
													<?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->price,2,'.','')); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Book Price','school-mgt');?>" ></i>
												</td>
												<td><?php echo stripslashes($retrieved_data->author_name);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Author Name','school-mgt');?>" ></td>

												<td>

													<?php

														if($retrieved_data->rack_location!=="0")

														{

															echo get_the_title($retrieved_data->rack_location);

														}

														else

														{

															echo "N/A";

														}

														?>

													<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Rack Location','school-mgt');?>" ></i>

												</td>

												<td><?php echo $retrieved_data->quentity;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Remaining Quantity','school-mgt');?>" ></i></td>

												<td class="action"> 

													<div class="smgt-user-dropdown">

														<ul class="" style="margin-bottom: 0px !important;">

															<li class="">

																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

																</a>

																<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																	<li class="float_left_width_100">

																		<a href="#" id="<?php echo $retrieved_data->id;?>" type="booklist_view" class="view_details_popup float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View Book Detail','school-mgt');?></a>

																	</li>

																	<?php 

																	if($school_obj->role=='supportstaff')

																	{ 

																		if($user_access['edit']=='1')

																		{?>

																			<li class="float_left_width_100 border_bottom_item">

																				<a href="?dashboard=user&page=library&tab=addbook&action=edit&book_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?> </a>

																			</li>

																		<?php

																		}

																		if($user_access['delete']=='1')

																		{ ?>

																			<li class="float_left_width_100">

																				<a href="?dashboard=user&page=library&tab=booklist&action=delete&book_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a> 

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

											$i++;

										}

									}

									?>	

								</tbody>

							</table> 

							<?php

							if($role_name == "supportstaff")

							{

								?>

								<div class="print-button pull-left">

									<button class="btn btn-success btn-sms-color button_reload">

										<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">

										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

									</button>

									<?php 

									if($school_obj->role=='supportstaff')

									{

										if($user_access['delete']=='1')

										{ ?>

											<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_book" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

											<?php

										}

									} ?>

								</div>

								<?php

							}

							?>

						</div>

						<?php

					}

					else

					{	

						if($user_access['add']=='1')

						{

							?>

							<div class="no_data_list_div no_data_img_mt_30px"> 

								<a href="<?php echo home_url().'?dashboard=user&page=library&tab=addbook';?>">

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

					} ?>

				</form>

			</div>

			<?php 

		

	}

	if($active_tab == 'addbook')

	{ ?>

		<?php 

		$bookid=0;

		if(isset($_REQUEST['book_id']))

		$bookid=$_REQUEST['book_id'];

		$edit=0;

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

		{

			$edit=1;

			$result=$obj_lib->mj_smgt_get_single_books($bookid);

		

		}?>

		<div class="panel-body"><!--panel-body -->	

			<form name="book_form" action="" method="post" class="mt-3 form-horizontal" id="book_form">

				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

				<input type="hidden" name="action" value="<?php echo $action;?>">

				<input type="hidden" name="book_id" value="<?php echo $bookid;?>">

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('BooK Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form">

					<div class="row">

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="isbn" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="50" value="<?php if($edit){ echo $result->ISBN;}?>" name="isbn">

									<label class="" for="isbn"><?php esc_attr_e('ISBN','school-mgt');?><span class="require-field">*</span></label>

								</div>

							</div>

						</div>



						<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 input">

							<label class="ml-1 custom-top-label top" for="category_data"><?php esc_attr_e('Book Category','school-mgt');?><span class="require-field">*</span></label>

							<select name="bookcat_id" id="" class="line_height_30px form-control smgt_bookcategory validate[required] width_100">

								<option value=""><?php esc_attr_e('Select Book Category','school-mgt');?></option>

									<?php 

									$activity_category=mj_smgt_get_all_category('smgt_bookcategory');

									if(!empty($activity_category))

									{

										if($edit)

										{

											$fees_val=$result->cat_id; 

										}

										else

										{

											$fees_val=''; 

										}

									

										foreach ($activity_category as $retrive_data)

										{ 		 	

										?>

											<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$fees_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>

										<?php }

									} 

									?> 

							</select>

						</div>

						<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 mb-3">

							<button id="addremove_cat" class="rtl_margin_top_15px add_btn sibling_add_remove" model="smgt_bookcategory"><?php esc_attr_e('Add','school-mgt');?></button>

						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

							<div class="form-group input rtl_margin_0px">

								<div class="col-md-12 form-control">

									<input id="book_name" class="form-control validate[required,custom[address_description_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo stripslashes($result->book_name);}?>" name="book_name">

									<label class="" for="book_name"><?php esc_attr_e('Book Title','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>

								</div>

							</div>

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

							<div class="form-group input rtl_margin_0px">

								<div class="col-md-12 form-control">

									<input id="book_number" class="form-control text-input" maxlength="10" type="number" value="<?php if($edit){ echo stripslashes($result->book_number);}?>" name="book_number">

									<label class="" for="book_number"><?php esc_attr_e('Book Number','school-mgt');?></label>

								</div>

							</div>

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

							<div class="form-group input ">

								<div class="col-md-12 form-control">

									<input id="author_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo stripslashes($result->author_name);}?>" name="author_name">

									<label class="" for="author_name"><?php esc_attr_e('Author Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>

								</div>

							</div>

						</div>



						<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 input">

							<label class="ml-1 custom-top-label top" for="category_data"><?php esc_attr_e('Rack Location','school-mgt');?><span class="require-field">*</span></label>

							<select name="rack_id" id="rack_category_data" class="line_height_30px form-control smgt_rack validate[required] max_width_100">

								<option value=""><?php esc_attr_e('Select Book Category','school-mgt');?></option>

								<?php 

								$activity_category=mj_smgt_get_all_category('smgt_rack');

								if(!empty($activity_category))

								{

									if($edit)

									{

										$rank_val=$result->rack_location; 

									}

									else

									{

										$rank_val=''; 

									}

								

									foreach ($activity_category as $retrive_data)

									{ 		 	

									?>

										<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$rank_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>

									<?php }

								} 

								?> 

							</select> 

						</div>

						<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 mb-3">

							<button id="addremove_cat" class="rtl_margin_top_15px add_btn sibling_add_remove" model="smgt_rack"><?php esc_attr_e('Add','school-mgt');?></button>

						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

							<div class="form-group input rtl_margin_0px">

								<div class="col-md-12 form-control">

									<input id="book_price" class="form-control validate[required,min[0],maxSize[8]]" type="number" step="0.01" value="<?php if($edit){ echo $result->price;}?>" name="book_price" >

									<label class="" for="book_price"><?php esc_attr_e('Price','school-mgt');?><span class="require-field">*</span></label>

								</div>

							</div>

						</div>



						<?php wp_nonce_field( 'save_book_frontend_nonce' ); ?>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

							<div class="form-group input rtl_margin_0px">

								<div class="col-md-12 form-control">

									<input id="quentity" class="form-control validate[required,min[0],maxSize[5]]" type="number" value="<?php if($edit){ echo $result->quentity;}?>" name="quentity">

									<label class="" for="class_capacity"><?php esc_attr_e('Quantity','school-mgt');?><span class="require-field">*</span></label>

								</div>

							</div>

						</div>

					

						<div class="col-md-6 note_text_notice">

							<div class="form-group input">

								<div class="col-md-12 note_border margin_bottom_15px_res">

									<div class="form-field">

										<textarea id="description" name="description" class="textarea_height_47px validate[custom[description_validation]] form-control" ><?php if($edit){ echo $result->description;}?>  </textarea>

										<span class="txt-title-label"></span>

										<label class="text-area address active" for="description"><?php esc_attr_e('Description','school-mgt');?></label>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>  

				

				<div class="form-body user_form">

					<div class="row">

						<div class="col-sm-6">           	

							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Book','school-mgt'); }else{ esc_attr_e('Add Book','school-mgt');}?>" name="save_book" class="btn btn-success save_btn" />

						</div>    

					</div>

				</div>    

				

			</form>

		</div><!--panel-body -->	

		<?php

	}

	if($active_tab == 'issuelist')

	{ ?>

		<div class="panel-body">

			<form id="frm-example" name="frm-example" method="post">

				<?php

				$user_id=get_current_user_id();

				//------- BOOK DATA FOR STUDENT ---------//
				if($school_obj->role == 'teacher')
				{
					$own_data=$user_access['own_data'];

					if($own_data == '1')

					{ 
						$user_id=get_current_user_id();		

						$class_id=get_user_meta($user_id,'class_name',true);

						$studentdata=$school_obj->mj_smgt_get_teacher_student_list($class_id);
						
						foreach($studentdata as $student)
						{
							$book[]=$obj_lib->mj_smgt_get_all_issuebooks_for_student($student->ID);
						
						}
						$mergedArray = array_merge(...$book);

						$retrieve_issuebooks = array_unique($mergedArray, SORT_REGULAR);

					}

					else

					{

						$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks(); 

					}
				}
				elseif($school_obj->role == 'supportstaff')

				{ 

					$own_data=$user_access['own_data'];

					if($own_data == '1')

					{ 

						$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks_created_by($user_id);

					}

					else

					{

						$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks(); 

					}

				}

				else

				{

					$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks(); 

				}

				if(!empty($retrieve_issuebooks))

				{

					?>

					<div class="table-responsive">

						<table id="issue_list" class="display dataTable issuelist_datatable" cellspacing="0" width="100%">

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

									<th><?php esc_attr_e('Student Name','school-mgt');?></th>

									<th><?php esc_attr_e('Book Title','school-mgt');?></th>

									<th><?php esc_attr_e('Issue Date','school-mgt');?></th>

									<th><?php esc_attr_e('Return Date ','school-mgt');?></th>

									<th><?php esc_attr_e('Accept Return Date ','school-mgt');?></th>

									<th><?php esc_attr_e('Period','school-mgt');?></th>

									<th><?php esc_attr_e('Fine','school-mgt');?></th>

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

								if(!empty($retrieve_issuebooks))

								{

									$i=0;

									foreach ($retrieve_issuebooks as $retrieved_data)

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

													<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>">

												</td>

												<?php

											}

											?>

											<td class="user_image width_50px profile_image_prescription padding_left_0">

												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Library.png"?>" height="30px" width="30px" alt="" class="massage_image center">

												</p>

											</td>



											<td>

												<a class="color_black" href="#"><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Student Name','school-mgt');?>" ></i></i>

											</td>

											<!-- <td></td> -->

											<td><?php echo stripslashes(mj_smgt_get_bookname($retrieved_data->book_id));?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Book Title','school-mgt');?>" ></i></td>

											<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->issue_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Issue Date','school-mgt');?>" ></i> </td>

											<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Return Date','school-mgt');?>" ></i></td>

											<td><?php  if(!empty($retrieved_data->actual_return_date)){echo mj_smgt_getdate_in_input_box($retrieved_data->actual_return_date);}else{echo "N/A";}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Accept Return Date','school-mgt');?>" ></i></td>

											<td><?php echo get_the_title($retrieved_data->period);?> <?php _e('Day','school-mgt') ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Period','school-mgt');?>" ></i></td>

											<td class="" ><?php if($retrieved_data->fine == "" || $retrieved_data->fine == 0){ echo 'N/A';}else{echo mj_smgt_get_currency_symbol().$retrieved_data->fine; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Fine','school-mgt');?>" ></i></td>

											<?php

											if($user_access['edit']=='1' || $user_access['delete']=='1')

											{

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

																

																	if($user_access['edit']=='1')

																	{

																		?>

																		<li class="float_left_width_100 border_bottom_item">

																			<a href="?dashboard=user&page=library&tab=issuebook&action=edit&issuebook_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?> </a>

																		</li>

																		<?php

																	}

																	if($user_access['delete']=='1')

																	{

																	?>

																	<li class="float_left_width_100">

																		<a href="?dashboard=user&page=library&tab=issuelist&action=delete&issuebook_id=<?php echo $retrieved_data->id;?>"  class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a> 

																	</li>

																	<?php

																	} ?>

																</ul>

															</li>

														</ul>

													</div>	

												</td>

												<?php

											}

											?>

										</tr>

										<?php 

										$i++;

									} 

								}?>	

							</tbody>

						</table>

						<?php

						if($role_name == "supportstaff")

						{

							?>

							<div class="print-button pull-left">

								<button class="btn-sms-color button_reload">

									<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">

									<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

								</button>

								<?php

								if($user_access['delete']=='1')

								{ ?>

									<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_issuebook" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

									<!-- <input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_issuebook" class="btn btn-danger delete_selected"/> -->

								<?php

								}?>

							</div>

							<?php

						}

						?>

					</div>

					<?php

				}

				else

				{	

					if($user_access['add']=='1')

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

				} ?>

			</form>

		</div>

		<?php

	}

	if($active_tab == 'issuebook')

	{

		$issuebook_id=0;

		if(isset($_REQUEST['issuebook_id']))

		$issuebook_id=$_REQUEST['issuebook_id'];

		$edit=0;

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

		{

			$edit=1;

			$result=$obj_lib->mj_smgt_get_single_issuebooks($issuebook_id);

		}?>

		<div class="panel-body"><!--panel-body -->	

			<form name="bookissue_form" action="" method="post" class="form-horizontal" id="bookissue_form">

				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

				<input type="hidden" name="action" value="<?php echo $action;?>">

				<input type="hidden" name="issue_id" value="<?php echo $issuebook_id;?>">



				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Issue Book Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form">

					<div class="row">



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">

							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Class','school-mgt');?></label>

							<?php 

							if($edit)

							{ 

								$classval=$result->class_id; 

							}

							else

							{

								$classval='';

							}?>

							<select name="class_id" id="class_list" class="line_height_30px form-control max_width_100">

								<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>

								<?php

								foreach(mj_smgt_get_allclass() as $classdata)

								{ ?>

									<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>

									<?php 

								}?>

							</select>

						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">

							<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>

							<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>

							<select name="class_section" class="line_height_30px form-control max_width_100" id="class_section">

								<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>

								<?php

								if($edit)

								{

									foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)

									{  ?>

										<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

										<?php 

									} 

								}?>

							</select>

						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">

							<label class="ml-1 custom-top-label top" for="student_list"><?php esc_attr_e('Student','school-mgt');?><span class="require-field">*</span></label>						

							<select name="student_id" id="student_list" class="line_height_30px form-control validate[required]">

								<?php 

								if(isset($result->student_id))

								{ 

									$student=get_userdata($result->student_id);

									?>

									<option value="<?php echo $result->student_id;?>" ><?php echo $student->first_name." ".$student->last_name;?></option>

									<?php 

								}

								else

								{ ?>

									<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>

									<?php 

								} ?>

							</select>

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="issue_date" class="datepicker form-control validate[required] text-input" type="text" name="issue_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->issue_date);}else{echo mj_smgt_getdate_in_input_box(date('Y-m-d'));}?>" readonly>

									<label class="" for="issue_date"><?php esc_attr_e('Issue Date','school-mgt');?><span class="require-field">*</span></label>

								</div>

							</div>

						</div>



						<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 input">

							<label class="ml-1 custom-top-label top" for="period"><?php esc_attr_e('Period','school-mgt');?><span class="require-field">*</span></label>

							<select name="period_id" class="line_height_30px form-control period_type issue_period validate[required] max_width_100">

								<option value = ""><?php esc_attr_e('Select Period','school-mgt');?></option>

								<?php 

								if($edit)

									$period_id = $result->period;

									$category_data = $obj_lib->mj_smgt_get_periodlist();

							

								if(!empty($category_data))

								{

									foreach ($category_data as $retrieved_data)

									{

										echo '<option value="'.$retrieved_data->ID.'" '.selected($period_id,$retrieved_data->ID).'>'.$retrieved_data->post_title.' '.esc_attr__("Days","school-mgt").'</option>';

									}

								}

								?>

							</select>

						</div>

						<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 mb-3">

							<button id="addremove_cat" class="rtl_margin_top_15px add_btn sibling_add_remove" model="period_type"><?php esc_attr_e('Add','school-mgt');?></button>

						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="return_date" class="form-control validate[required] text-input" type="text" name="return_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->end_date);}?>" readonly>

									<label class="" for="return_date"><?php esc_attr_e('Return Date','school-mgt');?><span class="require-field">*</span></label>

								</div>

							</div>

						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input rtl_margin_0px">

							<label class="ml-1 custom-top-label top" for="category_data validate[required]"><?php esc_attr_e('Book Category','school-mgt');?><span class="require-field">*</span></label>							

							<select name="bookcat_id" id="bookcat_list" class="validate[required] line_height_30px form-control">

								<option value = ""><?php esc_attr_e('Select Category','school-mgt');?></option>

								<?php if($edit)

								$book_cat = $result->cat_id;

								$category_data = $obj_lib->mj_smgt_get_bookcat();

								if(!empty($category_data))

								{

									foreach ($category_data as $retrieved_data)

									{

										echo '<option value="'.$retrieved_data->ID.'" '.selected($book_cat,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';

									}

								}

								?>

							</select>

						</div>



						<?php wp_nonce_field( 'issue_book_frontend_nonce' ); ?>

						

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 error_msg_top_margin">

							<div class="col-sm-12 multiselect_validation_book smgt_multiple_select rtl_padding_left_right_0px">

								<?php if($edit){ $book_id=$result->book_id; }else{$book_id=0;}?>

								<select name="book_id[]" id="book_list1" multiple="multiple" class="form-control validate[required]">

									<?php $books_data=$obj_lib->mj_smgt_get_all_books();

									foreach($books_data as $book){?>

										<option value="<?php echo $book->id;?>" <?php selected($book_id,$book->id);?> <?php if($books_data->quentity >= '0'){ ?> disabled <?php } ?>><?php echo stripslashes($book->book_name);?></option>

									<?php } ?>

								</select>

							</div>

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
							<div class="form-group">
								<div class="col-md-12 form-control rtl_relative_position">
									<div class="row padding_radio">
										<div class="display_flex">
											<label class="custom-top-label label_position_rtl " for="enable"><?php esc_attr_e('Send Mail','school-mgt');?></label>
											<input id="chk_sms_sent1" type="checkbox" class="check_box_input_margin" <?php $smgt_issue_book_mail_service_enable = 0;if($smgt_issue_book_mail_service_enable) echo "checked";?> value="1" name="smgt_issue_book_mail_service_enable">&nbsp;<?php esc_attr_e('Send Mail','school-mgt');?>
										</div>												
									</div>
								</div>
							</div>
						</div>

					</div>

				</div>

				<div class="form-body user_form">

					<div class="row">

						<div class="col-sm-6">      

							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Issue Book','school-mgt'); }else{ esc_attr_e('Issue Book','school-mgt');}?>" name="save_issue_book" class="save_btn btn btn-success book_for_alert rtl_margin_0px" />

						</div>    

					</div>

				</div>     

			</form>

		</div><!--panel-body -->

		<?php 

	}

	if($active_tab == 'memberlist')

	{ 



		$user_id=get_current_user_id();

		//------- MEMBER DATA FOR STUDENT ---------//

		if($school_obj->role == 'student')

		{

			$studentdata[] = get_userdata($user_id);

		}

		//------- MEMBER DATA FOR TEACHER ---------//

		elseif($school_obj->role == 'teacher')

		{

			$studentdata =$school_obj->student;

		}

		//------- EXAM DATA FOR PARENT ---------//

		elseif($school_obj->role == 'parent')

		{

			$studentdata1 =$school_obj->child_list;



			foreach($studentdata1 as $data)

			{

				$studentdata[] =get_userdata($data);

			}

		}

		//------- EXAM DATA FOR SUPPORT STAFF ---------//

		else

		{ 

			$studentdata =$school_obj->student;

		} 

		

		if(!empty($studentdata))

		{



			foreach ($studentdata as $retrieved_data)

			{

				$book_issued[] = mj_smgt_check_book_issued($retrieved_data->ID);

			}

		}

		

		if($school_obj->role == 'student')

		{

			$book = $book_issued;

			

		}

		

		else{

			$book = $book_issued;

		}

		

		$data = implode('',$book);

		

		if($data)

		{ 	

			?>

			<div class="panel-body">

				<div class="table-responsive">

					<table id="member_list" class="display dataTable member_list_datatable" cellspacing="0" width="100%">

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

							if(!empty($studentdata))

							{

								foreach ($studentdata as $retrieved_data)

								{

									$book_issued = mj_smgt_check_book_issued($retrieved_data->ID);

									if(!empty($book_issued))

									{ 	?>

										<tr>

											<td class="user_image width_50px padding_left_0 user_image">

												<a class="" href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup">

													<?php 

													$uid=$retrieved_data->ID;

													$umetadata=mj_smgt_get_user_image($uid);

													if(empty($umetadata))

													{

														echo '<img src='.get_option( 'smgt_student_thumb_new' ).' height="50px" width="50px" class="img-circle" />';

													}

													else

													echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';

													?>

												</a>

											</td>

											<td class="name">

												<?php if($retrieved_data){

													?>

													<a class="color_black" href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup"><?php echo $retrieved_data->display_name;?></a>

													<br>

													<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>

												<?php }else{

													echo 'N/A';

												} ?>

												

											</td>

											<td class="name">
												<?php
												 $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
												$section = get_user_meta($retrieved_data->ID, 'class_section',true);

												if($class_id){ echo $classname=smgt_get_class_section_name_wise($class_id,$section);}else{ echo "N/A"; }?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class','school-mgt');?>"></i></td>

											<td class="roll_no"><?php $roll = get_user_meta($retrieved_data->ID, 'roll_id',true); if(!empty($roll)){echo $roll;}else{echo 'N/A';}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Roll No.','school-mgt');?>"></i></td>

											

											<td class="action"> 

												<div class="smgt-user-dropdown">

													<ul class="" style="margin-bottom: 0px !important;">

														<li class="">

															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

															</a>

															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink"> 



																<?php $stud_id=get_current_user_id();

																if($school_obj->role=='student')

																{

																	if($stud_id==$retrieved_data->ID)

																	{  ?>

																		<li class="float_left_width_100">

																			<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?></a>

																		</li>

																		<?php 

																	} 

																}

																else

																{ ?> 

																	<li class="float_left_width_100">

																		<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?></a>

																	</li>

																	<?php 

																	if($school_obj->role=='supportstaff')

																	{ ?>

																		<li class="float_left_width_100">

																			<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="accept_returns_book_popup" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/accept_book_return.png"?>" style="height:13px;">&nbsp;&nbsp;&nbsp;<?php esc_attr_e('Accept Returns','school-mgt');?> </a>

																		</li>

																		<?php 

																	}

																} ?> 

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

			</div>

			<?php

		}

		else

		{	

			if($user_access['add']=='1')

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

	?>

	

</div>

<?php ?>
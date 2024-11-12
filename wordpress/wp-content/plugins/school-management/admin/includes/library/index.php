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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('library');
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
			if ('library' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('library' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('library' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
	$('#book_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	$('#book_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('.datepicker').datepicker({
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
	$('#book_list1').multiselect({
			nonSelectedText :'<?php esc_attr_e( 'Select Book', 'school-mgt' ) ;?>',
			includeSelectAllOption: true,
			selectAllText : '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',
			templates: {
				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			}
	});
	$(".book_for_alert").click(function()
	{	
		checked = $(".multiselect_validation_book .dropdown-menu input:checked").length;
		if(!checked)
		{
		 alert(language_translate2.select_one_book_alert);
		  return false;
		}	
	}); 
	// START select student class wise
	$("body").on("change", "#class_list_lib", function(){	
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
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="view_popup"></div>  
			<div class="invoice_data"></div>
			<div class="category_list">
			</div> 			
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->

<?php 
$obj_lib= new Smgtlibrary();
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_lib->mj_smgt_delete_book($_REQUEST['book_id']);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=10'); 
		}
	}
	
	if(isset($_REQUEST['delete_selected_book']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
		{
			$result=$obj_lib->mj_smgt_delete_book($id);
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=10'); 
		}
			
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=10'); 
		}
	}   
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete' && $_REQUEST['tab']=='issuelist' && isset($_REQUEST['issuebook_id']))
	{
		$result=$obj_lib->mj_smgt_delete_issuebook($_REQUEST['issuebook_id']);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=6');
		}
	}
	if(isset($_REQUEST['delete_selected_issuebook']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
		{
			$result=$obj_lib->mj_smgt_delete_issuebook($id);
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=6');
		}
			
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=6');
		}
	}
	
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
				$errors[]="This file not allowed, please choose a CSV file.";
				wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=8');
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=9');
			}			
			if(empty($errors)==true)
			{	
				$rows = array_map('str_getcsv', file($file_tmp));		
				$header = array_map('strtolower',array_shift($rows));
				
				$csv = array();
				foreach ($rows as $row) 
				{
					$csv = array_combine($header, $row);
					
					global $wpdb;
					$query = 'SELECT * FROM ' . $wpdb->posts . '
						WHERE post_type="smgt_rack" and post_title=\'' . $csv['rack_location'] . '\'';
					$retrive_data = $wpdb->get_row($query);


					$cat_query = 'SELECT * FROM ' . $wpdb->posts . '
						WHERE post_type="smgt_bookcategory" and post_title=\'' . $csv['cat_id'] . '\'';
					$cat_retrive_data = $wpdb->get_row($cat_query);
					if(isset($csv['isbn']))
						$bookdata['isbn']=$csv['isbn'];
					if(isset($csv['book_name']))
						$bookdata['book_name']=$csv['book_name'];
					if(isset($csv['author_name']))
						$bookdata['author_name']=$csv['author_name'];
					if(isset($csv['book_number']))
					$bookdata['book_number']=$csv['book_number'];
					// Rack Location //
					if(!empty($retrive_data))	
					{
						if(isset($csv['rack_location']))
							$bookdata['rack_location']=$retrive_data->ID;
					}
					else
					{
						global $wpdb;
						if(isset($csv['rack_location']))
						{
							$result = wp_insert_post( array(

								'post_status' => 'publish',
					
								'post_type' =>'smgt_rack',
					
								'post_title' => $csv['rack_location']) );
							$id = $wpdb->insert_id;

							$bookdata['rack_location']=$id;
						}
					}

					// Category Location //
					if(!empty($cat_retrive_data))	
					{
						if(isset($csv['cat_id']))
							$bookdata['cat_id']=$cat_retrive_data->ID;
					}
					else
					{
						global $wpdb;
						if(isset($csv['cat_id']))
						{
							$result = wp_insert_post( array(

								'post_status' => 'publish',
					
								'post_type' =>'smgt_bookcategory',
					
								'post_title' => $csv['cat_id']) );
							$cat_id = $wpdb->insert_id;
							$bookdata['cat_id']=$cat_id;
						}
					}
					
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
					$book_name = array();
					$book_isbn = array();
					
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
				wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=7');
			} 
		}
	}
	
	if(isset($_POST['book_csv_selected']))
	{   

		if(isset($_POST['id']))
		{
			foreach($_POST['id'] as $b_id)
			{
				$book_list[] = mj_smgt_get_book($b_id);
			}
			if(!empty($book_list))
			{
				$header = array();			
				$header[] = 'isbn';
				$header[] = 'cat_id';
				$header[] = 'book_name';
				$header[] = 'book_number';
				$header[] = 'author_name';
				$header[] = 'rack_location';
				$header[] = 'price';			
				$header[] = 'quentity';
				$header[] = 'description';

				$filename='Reports/export_book.csv';
				$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
				fputcsv($fh, $header);
				foreach($book_list as $retrive_data)
				{
					$row = array();
				
					$row[] =  $retrive_data->ISBN;
					$row[] =  get_the_title($retrive_data->cat_id);
					$row[] =  $retrive_data->book_name;
					$row[] =  $retrive_data->book_number;
					$row[] =  $retrive_data->author_name;
					$row[] =  get_the_title($retrive_data->rack_location);
					$row[] =  $retrive_data->price;
					$row[] =  $retrive_data->quentity;
					$row[] =  $retrive_data->description;

					fputcsv($fh, $row);
				}

				fclose($fh);
		
				//download csv file.
				ob_clean();
				$file=SMS_PLUGIN_DIR.'/admin/Reports/export_book.csv';//file location
			
				$mime = 'text/plain';
				header('Content-Type:application/force-download');
				header('Pragma: public');       // required
				header('Expires: 0');           // no cache
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
				header('Cache-Control: private',false);
				header('Content-Type: '.$mime);
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Content-Transfer-Encoding: binary');
				header('Connection: close');
				readfile($file);		
				exit;	
			}
			else
			{
				echo "<div style=' background: none repeat scroll 0 0 red;
				border: 1px solid;
				color: white;
				float: left;
				font-size: 17px;
				margin-top: 10px;
				padding: 10px;
				width: 98%;'>Records not found.</div>";
			}
		}
	}
	if(isset($_POST['save_book']))
	{   
        $nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_book_admin_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{		
				$result=$obj_lib->mj_smgt_add_book($_POST);
				if($result)
				{ 
				wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=1');
				 }		
			}
			else
			{
				$result=$obj_lib->mj_smgt_add_book($_POST);
				if($result)
				{ 
				 wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=2');
				 }				
			}
	    }		
	}
	if(isset($_POST['save_issue_book']))
	{
		
		$nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_issuebook_admin_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{		
				;
				$result=$obj_lib->mj_smgt_add_issue_book($_POST);
				if($result)
				{ 
					/* Book Issue Mail Notification */
					if(isset($_REQUEST['smgt_issue_book_mail_service_enable']))
					{
						foreach($_REQUEST['book_id'] as $book_id)
						{
							$smgt_issue_book_mail_service_enable = $_REQUEST['smgt_issue_book_mail_service_enable'];
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

					wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=3');
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
					wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=4');
				}				
			}
	    }		
	}

	if(isset($_POST['submit_book']) && isset($_POST['books_return']))
	{
		$result=$obj_lib->mj_smgt_submit_return_book($_POST);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=5');
		}
	}
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'booklist';
?>

<div class="page-inner"><!-- page-inner -->
	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Book Updated Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Book Added Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Issue Book Updated Successfully.','school-mgt');
				break;	
			case '4':
				$message_string = esc_attr__('Book Issued Successfully.','school-mgt');
				break;	
			case '5':
				$message_string = esc_attr__('Book Submitted Successfully.','school-mgt');
				break;	
			case '6':
				$message_string = esc_attr__('Issue Book Deleted Successfully.','school-mgt');
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
			case '10':
				$message_string = esc_attr__('Book Deleted Successfully.','school-mgt');
				break;
		}
		?>
		<div class="row"><!-- Row -->
			<?php
				if($message)
				{ ?>
					<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
						<p><?php echo $message_string;?></p>
						<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
					<?php 
				} ?>
		
			<div class="col-md-12 padding_0"><!-- col-md-12 -->	
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist"><!--Start nav-tabs -->
				
					

					<li class="<?php if($active_tab=='booklist'){?>active<?php }?>">
						<a href="?page=smgt_library&tab=booklist" class="padding_left_0 tab <?php echo $active_tab == 'booklist' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Book List', 'school-mgt'); ?></a>
					</li>
					
					
					<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['book_id']) )
					{ ?>
						<li class="<?php if($active_tab=='addbook'){?>active<?php }?>">
							<a href="?page=smgt_library&tab=addbook&action=edit&issuebook_id=<?php echo $_REQUEST['book_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'addbook' ? 'nav-tab-active' : ''; ?>">
							<?php esc_attr_e('Edit Book', 'school-mgt'); ?></a>  
						</li>
						<?php 
					}
					else
					{ ?>
						<?php 
						if($user_access_add == '1')
						{ ?>
							<?php if($page_name == 'smgt_library' && $active_tab == 'addbook')
							{ ?>
								<li class="<?php if($active_tab=='addbook'){?>active<?php }?>">
									<a href="?page=smgt_library&tab=addbook" class="padding_left_0 tab <?php echo $active_tab == 'addbook' ? 'nav-tab-active' : ''; ?>">
									<?php echo esc_attr__('Add Book', 'school-mgt'); ?></a> 
								</li>
								<?php 
							}
						}
					}
					?> 
					<li class="<?php if($active_tab=='issuelist'){?>active<?php }?>">
						<a href="?page=smgt_library&tab=issuelist" class="padding_left_0 tab <?php echo $active_tab == 'issuelist' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Issue List', 'school-mgt'); ?></a>
					</li>
					<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'issuebook' )
					{ ?>
						<li class="<?php if($active_tab=='issuebook'){?>active<?php }?>">
							<a href="?page=smgt_library&tab=issuebook&action=edit&issuebook_id=<?php echo $_REQUEST['issuebook_id'];?>" class="padding_left_0 tab <?php echo $active_tab == 'issuebook' ? 'nav-tab-active' : ''; ?>">
							<?php esc_attr_e('Edit Issue Book', 'school-mgt'); ?></a>  
						</li>
						<?php 
					}
					else
					{ ?>
						<?php 
						if($user_access_add == '1')
						{ ?>
							<?php if($page_name == 'smgt_library' && $active_tab == 'issuebook')
							{ ?>
								<li class="<?php if($active_tab=='issuebook'){?>active<?php }?>">
									<a href="?page=smgt_library&tab=issuebook" class="padding_left_0 tab <?php echo $active_tab == 'issuebook' ? 'nav-tab-active' : ''; ?>">
									<?php echo  esc_attr__('Add Issue Book', 'school-mgt'); ?></a> 
								</li>
								<?php 
							}
						} 
					}?> 
					<li class="<?php if($active_tab=='memberlist'){?>active<?php }?>">
						<a href="?page=smgt_library&tab=memberlist" class="padding_left_0 tab <?php echo $active_tab =='memberlist' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Member List', 'school-mgt'); ?></a>
					</li>
				</ul><!--End nav-tabs -->
				<?php
				if($active_tab == 'booklist')
				{ 
					if(get_option('smgt_enable_video_popup_show') == 'yes')
					{
					?>
					<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/CZQzPhCPIr4?si=Hg16bHUL2gzi9xLA";?>" title="Library Module Setup">
						<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
					</a>
					<?php
					}
					$retrieve_books=$obj_lib->mj_smgt_get_all_books();	
					if(!empty($retrieve_books))
					{
						?>
						<script type="text/javascript">
							jQuery(document).ready(function($)
							{
								"use strict";	
								var table =  jQuery('#book_list').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										//stateSave: true,
									responsive: true,
									"dom": 'lifrtp',
									"order": [[ 2, "asc" ]],
									"aoColumns":[		                  
												{"bSortable": false},
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
								$("body").on("click",".book_csv_selected_alert",function()
								{
									if ($('.smgt_sub_chk:checked').length == 0 )
									{
										alert(language_translate2.one_record_select_alert);
										return false;
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

						<div class="panel-body"><!--panel-body -->
							<div class="table-responsive">
								<form id="frm-example" name="frm-example" method="post">
									<table id="book_list" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
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
														<td class="checkbox_width_10px">
															<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]"	value="<?php echo $retrieved_data->id;?>">
														</td>

														<td class="user_image width_50px profile_image_prescription padding_left_0">
															<a href="#" id="<?php echo $retrieved_data->id;?>" type="booklist_view" class="view_details_popup ">
																<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Library.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
																</p>
															</a>
														</td>

														<td>
															<?php echo $retrieved_data->ISBN;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('ISBN','school-mgt');?>" ></i>
														</td>
														<td>
															<a href="#" id="<?php echo $retrieved_data->id;?>" type="booklist_view" class="view_details_popup ">
																<?php echo stripslashes($retrieved_data->book_name);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Book Title','school-mgt');?>" ></i>
															</a>
														</td>
														<td>
															<?php echo get_the_title($retrieved_data->cat_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Book Category','school-mgt');?>" ></i>
														</td>
														<td>
														<?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->price,2,'.','')); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Book Price','school-mgt');?>" ></i>
														</td>
														<td>
															<?php echo stripslashes($retrieved_data->author_name);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Author Name','school-mgt');?>" ></i>
														</td>
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
															 ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Rack Location','school-mgt');?>" ></i>
														</td>
														<td>
															<?php echo $retrieved_data->quentity;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Remaining Quantity','school-mgt');?>" ></i>
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
																				<a href="#" id="<?php echo $retrieved_data->id;?>" type="booklist_view" class="view_details_popup float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?></a>
																			</li>
																			<?php 
																				if($user_access_edit == '1')
																				{ ?>
																					<li class="float_left_width_100 border_bottom_item">
																						<a href="?page=smgt_library&tab=addbook&action=edit&book_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?> </a> 
																					</li>
																					<?php 
																				} ?>
																				<?php 
																				if($user_access_delete =='1')
																				{ ?>
																					<li class="float_left_width_100">
																						<a href="?page=smgt_library&tab=booklist&action=delete&book_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a> 
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
											} ?>	
									
										</tbody>        
									</table>
									<div class="print-button pull-left">
										<button class="btn btn-success btn-sms-color button_reload">
											<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->id); ?>" style="margin-top: 0px;">
											<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
										</button>
										<?php 
										if($user_access_delete =='1')
										{ ?>
											<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_book" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
											<?php 
										} ?>	
										<button data-toggle="tooltip" title="<?php esc_html_e('Export CSV','school-mgt');?>" name="book_csv_selected" class="book_csv_selected_alert export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button>
										<button data-toggle="tooltip" title="<?php esc_html_e('Import CSV','school-mgt');?>" type="button" name="import_csv" class="importdata export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></button>
									</div>
								</form>
						</div><!--panel-body -->
						<?php 
					}
					else
					{
						if($user_access_add=='1')
						{
							?>
							<div class="no_data_list_div no_data_img_mt_30px no_data_margin row">
								<div class="offset-md-2 col-md-4"> 
									<a href="<?php echo admin_url().'admin.php?page=smgt_library&tab=addbook';?>">
										<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
									</div>
								</div>
								<div class="col-md-4">
									<a data-toggle="tooltip"  name="import_csv" type="button" class="importdata">
										<img src="<?php echo SMS_PLUGIN_URL."/assets/images/thumb_icon/Import_list.png" ?>" alt="">
									</a>
									<div class="col-md-12 dashboard_btn margin_top_20px">
										<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to import CSV.','school-mgt'); ?> </label>
									</div> 
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
				if($active_tab == 'addbook')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/library/add-newbook.php';
				}
				if($active_tab == 'issuelist')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/library/issuelist.php';
				}
				if($active_tab == 'issuebook')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/library/issue-book.php';
				}
				if($active_tab == 'memberlist')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/library/memberlist.php';
				}
				?>	 
			</div><!-- col-md-12 -->	
		</div><!-- Row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner -->
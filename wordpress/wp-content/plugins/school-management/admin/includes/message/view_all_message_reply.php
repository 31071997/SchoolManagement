<script type="text/javascript">

jQuery(document).ready(function($)

{

	var table =jQuery('#view_all_message_reply_list').DataTable({	
		"initComplete": function(settings, json) {
			$(".print-button").css({"margin-top": "-5%"});
		},
         buttons: [

			{

                extend: 'print',

                text:'Print',

				title: 'Message Reply Data',

				exportOptions: {

                    columns: [1,2,3,5]

                }

            }

        ],  

		 "bProcessing": true,
			 //stateSave: true,
		 "bServerSide": true,

		 "sAjaxSource": ajaxurl+'?action=mj_smgt_view_all_relpy',

		 "bDeferRender": true, 			  

			

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

		table.on('page.dt', function() {

		  $('html, body').animate({

			scrollTop: $(".dataTables_wrapper").offset().top

		   }, 'slow');

		});

	

});



</script>	

<?php

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_users_reply_message')	

{		

	global $wpdb;

	$tablename		=	"smgt_message_replies";

	$table_name = $wpdb->prefix . $tablename;

	$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id= %d",$_REQUEST['users_reply_message_id']));

	

	if($result)

	{

		wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message_reply&message=2');

	}

}

if(isset($_POST['delete_selected']))

{		

	global $wpdb;

	$tablename		=	"smgt_message_replies";

	$table_name = $wpdb->prefix . $tablename;

	

	if(!empty($_REQUEST['id']))

	{

		foreach($_REQUEST['id'] as $id)

		{

			$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id= %d",$id));

			wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message_reply&message=2');

		}

		if($result)

		{ 

			wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message_reply&message=2');

		}

	}

}



?>

<div class="mailbox-content padding_0"><!-- mailbox-content  -->

	<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.js'></script>

	<script type='text/javascript' src='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'></script>

	<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js'></script>

	<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js'></script>

	<script type='text/javascript' src='https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js'></script>

	<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js'></script>

	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>

	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>

	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>

	<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js'></script>

	<div class="table-responsive"><!-- table-responsive  -->

		<form id="frm-example1" name="frm-example1" method="post">	

			<table id="view_all_message_reply_list" class="display" cellspacing="0" width="100%">

				<thead>

					<tr> 

						<th class="padding_0"><input type="checkbox" class="select_all smgt_sub_chk" id="select_all" style="margin-left: 10px;"></th>           

						<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

						<th><?php esc_attr_e('Sender','school-mgt');?></th>

						<th><?php esc_attr_e('Receiver','school-mgt');?></th>                

						<th><?php esc_attr_e('Description','school-mgt');?></th>                                            

						<th><?php esc_attr_e('Attachment','school-mgt');?></th>               

						<th><?php esc_attr_e('Date & Time','school-mgt');?></th>       

						<th><?php esc_attr_e('Action','school-mgt');?></th>         

					</tr>

				</thead>      

			</table>



			<div class="print-button pull-left">

				<button class="btn btn-success btn-sms-color button_reload">

					<input type="checkbox" name="id[]" class="select_all" value="<?php if(!empty($retrieved_data->ID)){ echo esc_attr($retrieved_data->ID); } ?>" style="margin-top: 0px;">

					<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

				</button>

				<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_check delete_selected delete_margin_bottom" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

					

			</div>

		</form>

	</div><!-- table-responsive  -->

</div><!-- mailbox-content  -->
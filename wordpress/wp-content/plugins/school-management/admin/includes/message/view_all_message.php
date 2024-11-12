<?php
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_users_message')	
{		
	global $wpdb;
	$tablename		=	"smgt_message";
	$table_name = $wpdb->prefix . $tablename;
	
	$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE message_id= %d",$_REQUEST['users_message_id']));
	if($result)
	{	
		wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message&message=2');
	}
}
if(isset($_REQUEST['delete_selected_view_all_messages']))
{		
	global $wpdb;
	$tablename		=	"smgt_message";
	$table_name = $wpdb->prefix . $tablename;
	
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE message_id= %d",$id));			
			wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message&message=2');
		}
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message&message=2');
		}
	}
}
?>
<div class="mailbox-content padding_0"><!-- mailbox-content -->
	<script type='text/javascript' src='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'></script>
	<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js'></script>
	<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js'></script>
	<script type='text/javascript' src='https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js'></script>
	<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
	<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js'></script>
	<?php
	
		?>
		<script>
			jQuery(document).ready(function($){
				$('.multiselect-search').removeClass('form-control',0);
				var table = jQuery('.display').DataTable({
					"initComplete": function(settings, json) {
						$(".print-button").css({"margin-top": "-5%"});
					},
				//stateSave: true,
				"responsive": true,
				
				dom: 'Bfrtip',
				buttons: [
					{
						extend: 'print',
						text:'Print',
						title: 'Message Data',
						exportOptions: 
						{
							columns: [1,2,3,4,5,6,8],
						}
					}
				], 
				// "bProcessing": true,
				// "bServerSide": true,
				// "sAjaxSource": ajaxurl+'?action=mj_smgt_view_all_message',
				// "bDeferRender": true, 	

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
				{"bSortable": false},              	                 
				{"bSortable": false}],
				language:<?php echo mj_smgt_datatable_multi_language();?>
				});
				
				$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
				
				table.on('page.dt', function() {
				$('html, body').animate({
					scrollTop: $(".dataTables_wrapper").offset().top
				}, 'slow');
				}); 
				
				$(".delete_check").on('click', function()
				{	
					if ($('.sub_chk:checked').length == 0 )
					{
						alert(language_translate2.one_message_alert);
						return false;
					}
					else{
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

			jQuery('#select_all').on('click', function(e)
				{
					if($(this).is(':checked',true))  
					{
						$(".sub_chk").prop('checked', true);  
					}
					else  
					{
						$(".sub_chk").prop('checked',false);  
					}
				});
				$("body").on("change", ".sub_chk", function(event)
				{ 
					if(false == $(this).prop("checked"))
					{ 
						$("#select_all").prop('checked', false); 
					}
					if ($('.sub_chk:checked').length == $('.sub_chk').length )
					{
						$("#select_all").prop('checked', true);
					}
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
		</script>
		<div class="table-responsive"><!-- table-responsive -->
			<form id="frm-example" name="frm-example" method="post">	<!-- form div -->
				<table id="" class="display" cellspacing="0" width="100%">
					<thead>
						<tr> 
							<th class="padding_0"><input type="checkbox" class="select_all smgt_sub_chk" id="select_all"></th>           
							<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
							<th><?php esc_attr_e('Message For','school-mgt');?></th> 
							<th><?php esc_attr_e('Sender','school-mgt');?></th>
							<th><?php esc_attr_e('Receiver','school-mgt');?></th>    
							<th><?php esc_attr_e('Class','school-mgt');?></th>                                            
							<th><?php esc_attr_e('Subject','school-mgt');?></th>               
							<th><?php esc_attr_e('Description','school-mgt');?></th>  
							<th><?php esc_attr_e('Attachment','school-mgt');?></th>               
							<th><?php esc_attr_e('Date & Time','school-mgt');?></th>       
							<th><?php esc_attr_e('Action','school-mgt');?></th>         
						</tr>
					</thead>      
				</table>
				<div class="print-button pull-left">
					<button class="btn btn-success btn-sms-color button_reload">
						<input type="checkbox" name="id[]" class="select_all smgt_sub_chk" value="<?php if(!empty($retrieved_data->message_id)){ echo esc_attr($retrieved_data->message_id); }?>" style="margin-top: 0px;">
						<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
					</button>
				
					<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_view_all_messages" class="delete_selected  delete_check delete_margin_bottom" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
						
						
				</div>
				
			</form><!-- form div -->
		</div><!-- table-responsive -->
</div><!-- mailbox-content -->
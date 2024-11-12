jQuery(document).ready(function($){
	"use strict";	
	$('#class_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		
	var table =  jQuery('#class_list').DataTable({
		responsive: true,
		"order": [[ 1, "asc" ]],
		"dom": 'Bfrtip',
		"buttons": [
			'colvis'
		], 
		"aoColumns":[	                  
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": false}],
		    language:<?php echo mj_smgt_datatable_multi_language();?>
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
					var alert_msg=confirm("Are you sure you want to delete this record?");
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

	$('#homework_form_admin').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('.datepicker').datepicker({
		minDate : '0',
		dateFormat: "yy-mm-dd",
		beforeShow: function (textbox, instance) 
		{
			instance.dpDiv.css({
				marginTop: (-textbox.offsetHeight) + 'px'                   
			});
		}
		});

});
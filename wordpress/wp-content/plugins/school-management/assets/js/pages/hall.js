jQuery(document).ready(function($)
{
	"use strict";	
	
	$('#hall_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#receipt_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		
			 
			 
				  jQuery('.exam_hall_table').DataTable({
					responsive: true,
					bPaginate: false,
					bFilter: false, 
					bInfo: false,
				});   
			 
			$("body").on("click", "#checkbox-select-all", function()
			{
				if($(this).is(':checked',true))  
				{
					$(".my_check").prop('checked', true);  
				}  
				else  
				{  
					$(".my_check").prop('checked',false);  
				}
			});
			$("body").on("click", ".my_check", function()
			{
				if(false == $(this).prop("checked"))
				{
					$("#checkbox-select-all").prop('checked', false);
				}
				if ($('.my_check:checked').length == $('.my_check').length )
				{
					$("#checkbox-select-all").prop('checked', true);
				}
			});

			var table =  jQuery('#hall_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"dom": 'Bfrtip',
		"buttons": [
			'colvis'
		], 
		"aoColumns":[	                  
	                  {"bSortable": false},	                 
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                 	                  
	                  {"bSortable": false}],
		// language:<?php echo smgt_datatable_multi_language();?>
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
				alert(language_translate2.delete_record_alert);
				return true;
			}
	});

});
jQuery(document).ready(function($){
	"use strict";	
	$('#holiday_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	  
	  $('#date').datepicker({		
		dateFormat: "yy-mm-dd",
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#end_date").datepicker("option", "minDate", dt);
        }
	}); 
	$('#end_date').datepicker({		
		dateFormat: "yy-mm-dd",
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 0);
            $("#date").datepicker("option", "maxDate", dt);
        }
	}); 	 

	var table =  jQuery('#holiday_list').DataTable({
        responsive: true,
		"order": [[ 3, "desc" ]],
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

});
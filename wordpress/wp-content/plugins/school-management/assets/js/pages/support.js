jQuery(document).ready(function($){
	"use strict";	
$("body").on("click",".staff_csv_selected",function()
		 {
			if ($('.selected_staff:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}		
		});  

var table =  jQuery('#supportstaff_list').DataTable({
        responsive: true,
		"order": [[ 2, "asc" ]],
		"dom": 'Bfrtip',
		"buttons": [
			'colvis'
		], 
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": false},	                  	                
	                  {"bSortable": true},
	                  {"bSortable": true},	                  
	                  {"bSortable": false}],
		//language:<?php //echo smgt_datatable_multi_language();?>
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

$('#teacher_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#birth_date').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate : 0,
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
	}); 
	
});
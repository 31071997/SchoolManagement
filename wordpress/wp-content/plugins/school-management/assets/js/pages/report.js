jQuery(document).ready(function($){
	"use strict";	
			$('#failed_report').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$("#sdate").datepicker({
        dateFormat: "yy-mm-dd",
		changeYear: true,
		changeMonth: true,
		maxDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#edate").datepicker("option", "minDate", dt);
        }
    });

	
    $("#edate").datepicker({
       dateFormat: "yy-mm-dd",
	   changeYear: true,
	   changeMonth: true,
	   maxDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 0);
            $("#sdate").datepicker("option", "maxDate", dt);
        }
    });

     $('#fee_payment_report').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	 $('#example5').DataTable({
        responsive: true,
		// language:<?php //echo smgt_datatable_multi_language();?>	
    });

    $('.sdate').datepicker({dateFormat: "yy-mm-dd",changeYear: true,changeMonth:true}); 
    $('.edate').datepicker({dateFormat: "yy-mm-dd",changeMonth: true,changeMonth:true}); 

    var table = jQuery('#tblexpence').DataTable({
				"responsive": true,
				"order": [[ 2, "Desc" ]],
				"dom": 'Bfrtip',
				buttons: [
				{
				extend: 'print',
				title: ' Expense Report List',
				},
			],
				"aoColumns":[
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true}
				],
				// language:<?php echo smgt_datatable_multi_language();?>
			});

	 $('#fee_payment_report').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	 $('#sdate').datepicker({
		 dateFormat: "yy-mm-dd",
		 changeYear: true,
		 changeMonth: true,
		 maxDate : 0,
		 beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		 }); 
	 $('#edate').datepicker({
		 dateFormat: "yy-mm-dd",
		 changeYear: true,
		 changeMonth: true,
		 maxDate : 0,
		 beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		 }); 
	 $('#example4').DataTable({
        responsive: true,
		// language:<?php echo smgt_datatable_multi_language();?>	
    });

});
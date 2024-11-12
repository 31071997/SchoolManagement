jQuery(document).ready(function($){
	"use strict";	
	$('#teacher_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$("body").on("click",".teacher_csv_export_alert",function()
	{
		if ($('.selected_teacher:checked').length == 0 )
		{
			alert(language_translate2.one_record_select_alert);
			return false;
		}		
	}); 

	$('.sdate').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate:0,
		beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 
	$('.edate').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate:0,
		beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		});  

	var table =  jQuery('#attendance_teacher_list').DataTable({
			responsive: true,
			 dom: 'Bfrtip',
				buttons: [
				{
            extend: 'print',
			title: 'View Attendance',

				}
			
				],
		
			"order": [[ 0, "asc" ]],
			"aoColumns":[	                  
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},					           
			{"bSortable": false}],	
			//language:<?php //echo smgt_datatable_multi_language();?>	
		});

	var table =  jQuery('#teacher_list').DataTable({
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
		  else
		  {
				var alert_msg=confirm("Are you sure you want to delete this record");
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
	        },
    }); 
	$('#class_name').multiselect({
			nonSelectedText :'Select Class',
			includeSelectAllOption: true,
			selectAllText : 'Select all',
			templates: {
           	button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
      		 },
		 });
	 	 

	$('#upload_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	

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


});
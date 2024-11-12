jQuery(document).ready(function($){
	"use strict";	
		 $('#book_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

		 var table =  jQuery('#book_list').DataTable({
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
						  {"bSortable": true},	                  
						  {"bSortable": false}],
				//language:<?php echo smgt_datatable_multi_language();?>
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

			 $('#book_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('.datepicker').datepicker({
		dateFormat: "yy-mm-dd",
		minDate:0,
		beforeShow: function (textbox, instance) 
		{
			instance.dpDiv.css({
				marginTop: (-textbox.offsetHeight) + 'px'                   
			});
		}
	}); 
	/* $('#return_date').datepicker({	
		dateFormat: "yy-mm-dd",
		minDate:0,
		beforeShow: function (textbox, instance) 
		{
			instance.dpDiv.css({
				marginTop: (-textbox.offsetHeight) + 'px'                   
			});
		}  
	}); */ 	
	 $('#book_list1').multiselect({
			nonSelectedText :'Select Book',
			includeSelectAllOption: true,
			selectAllText : 'Select all',
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

	var table =  jQuery('#issue_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		dom: 'Bfrtip',
			buttons: [
				{
			extend: 'print',
			title: 'Library Issued Book List',
			exportOptions: {
                    columns: [ 0, 1, 2,3, 4 ,5]
                },
                customize: function ( win ) {
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
				},
			},
			'colvis'
			],
		"aoColumns":[	                  
	        {"bSortable": false},
	        {"bSortable": true},
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

	 var table =  jQuery('#example123').DataTable({
		responsive: true,
		"order": [[ 1, "desc" ]],
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
		//   language:<?php echo smgt_datatable_multi_language();?>
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

});
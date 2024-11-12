jQuery(document).ready(function($){
	"use strict";	
	$('#expense_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#invoice_date').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
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

    	var blank_income_entry ='';
    	var blank_expense_entry ='';
   		blank_expense_entry = $('#expense_entry').html();   	
  	

   	$('#expense_form').validationEngine({
        promptPosition: "bottomRight",
        maxErrorsPerField: 1
    });

    $("#fees_data").multiselect({
        nonSelectedText: 'Select Fees Type',
        includeSelectAllOption: true,
        selectAllText: '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',
        templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       }
    });
	
    $('#invoice_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: '-65:+25',
        beforeShow: function(textbox, instance) {
            instance.dpDiv.css({
                marginTop: (-textbox.offsetHeight) + 'px'
            });
        },
        onChangeMonthYear: function(year, month, inst) {
            $(this).val(month + "/" + year);
        }
    });
    $('#end_year').on('change',function() {

        var end_value = parseInt($('#end_year option:selected').val());
        var start_value = parseInt($('#start_year option:selected').attr("id"));
        if (start_value > end_value) {
            $("#end_year option[value='']").attr('selected', 'selected');
            alert(language_translate2.starting_year_alert);
            return false;
        }
    });

    var blank_income_entry = '';
    var blank_expense_entry = '';
	blank_expense_entry = $('#expense_entry').html();

	function add_entry() {
	    $("#expense_entry").append(blank_expense_entry);
	}

	function deleteParentElement(n) {
	    alert(language_translate2.do_delete_record);
	    n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
	}

	var table =  jQuery('#feetype_list').DataTable({
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
		// language:<?php echo smgt_datatable_multi_language();?>
	 });

	jQuery('#checkbox-select-all').on('click', function(){     
		var rows = table.rows({ 'search': 'applied' }).nodes();
		jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
	}); 
   
	 var table =  jQuery('#fee_paymnt').DataTable({
		responsive: true,
		"order": [[ 8, "desc" ]],
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
		  {"bSortable": true},
		  {"bSortable": true},
		  {"bSortable": true},
		  {"bSortable": true},
		  {"bSortable": false}],
		//   language:<?php echo smgt_datatable_multi_language();?>
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
	
	$("#fees_reminder").on('click', function()
	{	
			if ($('.select-checkbox:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}
		else
		{
				var alert_msg=confirm("Are you sure you want to send a mail reminder?");
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
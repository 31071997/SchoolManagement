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
    	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	blank_expense_entry = $('#expense_entry').html();

   	function add_entry()
   	{
   		$("#expense_entry").append(blank_expense_entry);
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
		alert(language_translate2.do_delete_record);
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}	
});
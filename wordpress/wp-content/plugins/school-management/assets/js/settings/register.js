jQuery(document).ready(function($){
	"use strict";	
	$('#registration_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#birth_date').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate:0,
		changeMonth: true,
		changeYear: true,
		yearRange:'-65:+25',
		onChangeMonthYear: function(year, month, inst) {
			$(this).val(month + "/" + year);
		}
    }); 
	    //custom field datepicker
		$('.after_or_equal').datepicker({
			dateFormat: "yy-mm-dd",										
			minDate:0,
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 
		$('.date_equals').datepicker({
			dateFormat: "yy-mm-dd",
			minDate:0,
			maxDate:0,										
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 
		$('.before_or_equal').datepicker({
			dateFormat: "yy-mm-dd",
			maxDate:0,
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 

		$('.space_validation').on('keypress',function( e ) 
		{
		   if(e.which === 32) 
			 return false;
		});									
		
		//Custom Field File Validation//
		function Smgt_custom_filed_fileCheck(obj)
		{	
		   "use strict";
			var fileExtension = $(obj).attr('file_types');
			var fileExtensionArr = fileExtension.split(',');
			var file_size = $(obj).attr('file_size');
			
			var sizeInkb = obj.files[0].size/1024;
			
			if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionArr) == -1)
			{										
				alert("Only "+fileExtension+"formats are allowed.");
				$(obj).val('');
			}	
			else if(sizeInkb > file_size)
			{										
				alert("Only "+file_size+" kb size is allowed");
				$(obj).val('');	
			}
		}


		
});

function fileCheck(obj) 
{
	var fileExtension = ['jpeg', 'jpg', 'png', 'bmp',''];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert(language_translate2.image_forame_alert);
		$(obj).val('');
	}	
}
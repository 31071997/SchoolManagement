jQuery(document).ready(function($){
	"use strict";	
	$('#exam_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$("#exam_start_date").datepicker({
	   dateFormat: "yy-mm-dd",
	   minDate:0,
	   onSelect: function (selected) {
		   var dt = new Date(selected);
		   dt.setDate(dt.getDate() + 1);
		   $("#exam_end_date").datepicker("option", "minDate", dt);
	   }
   });
   $("#exam_end_date").datepicker({
	   minDate:0,
	  dateFormat: "yy-mm-dd",
	   onSelect: function (selected) {
		   var dt = new Date(selected);
		   dt.setDate(dt.getDate() + -1);
		   $("#exam_start_date").datepicker("option", "maxDate", dt);
	   }
   });
   jQuery("body").on("change", ".input-file[type=file]", function ()
   { 
	   var file = this.files[0]; 
	   var file_id = jQuery(this).attr('id'); 
	   var ext = $(this).val().split('.').pop().toLowerCase(); 
	   //Extension Check 
	   if($.inArray(ext, ['pdf']) == -1)
	   {
			 alert(language_translate2.pdf_alert);
		   $(this).replaceWith('<input type="file" name="exam_syllabus" class="form-control file_validation input-file">');
		   return false; 
	   } 
		//File Size Check 
		if (file.size > 20480000) 
		{
		   alert(language_translate2.large_file_Size_alert);
		   $(this).replaceWith('<input type="file" name="exam_syllabus" class="form-control file_validation input-file">'); 
		   return false; 
		}
	});

	jQuery('.onlyletter_number_space_validation').keypress(function(e) 
	{     
		var regex = new RegExp("^[0-9a-zA-Z \b]+$");
		var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
		if (!regex.test(key)) 
		{
			event.preventDefault();
			return false;
		} 
   });  

});
jQuery(document).ready(function($){
	"use strict";	
	$('#message_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	 $('#selected_users').multiselect({ 
		 nonSelectedText :"Select Users",
		includeSelectAllOption: true,
		enableCaseInsensitiveFiltering: true,
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },
		// buttonContainer: '<div class="dropdown" />'         
     });
	 $('#selected_class').multiselect({ 
		 nonSelectedText :'Select Class',
         includeSelectAllOption: true,
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },
		buttonContainer: '<div class="dropdown" />'       
     });
	$("body").on("click",".save_message_selected_user",function()
	{		
		var class_selection_type = $(".class_selection_type").val();	
				
		if(class_selection_type == 'multiple')
		{
			var checked = $(".multiselect_validation1 .dropdown-menu input:checked").length;

			if(!checked)
			{
				alert(language_translate2.one_class_select_alert);
				return false;
			}	
		}			
	});  
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		"use strict";
		var file = this.files[0]; 		
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, [,'pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg','']) == -1)
		{
			  alert('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');
			return false; 
		} 
		//File Size Check 
		if (file.size > 20480000) 
		{
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />'); 
			return false; 
		}
	}); 

	jQuery('#message-replay').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	jQuery('span.timeago').timeago();
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		"use strict";
		var file = this.files[0]; 		
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, [,'pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg']) == -1)
		{
			
			  alert('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');
			return false; 
		} 
		//File Size Check 
		if (file.size > 20480000) 
		{
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />'); 
			return false; 
		}
	});

	$('.multiselect-search').removeClass('form-control',0);


});

function add_new_attachment()
{
	$(".attachment_div").append('<div class="form-group row mb-3"><label class="col-sm-2 control-label col-form-label text-md-end" for="photo">Attachment</label><div class="col-sm-4"><input  class="btn_top input-file" name="message_attachment[]" type="file" /></div><div class="col-sm-2"><input type="button" value="Delete" onclick="delete_attachment(this)" class="remove_cirtificate doc_label btn btn-danger"></div></div>');
}

function delete_attachment(n)
{
	n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);				
}
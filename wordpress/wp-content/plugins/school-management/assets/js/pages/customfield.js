jQuery(document).ready(function($)
{
	$('#custom_field_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});			

 	$(".file_edit").on( "load", function() {
		
		return false;
		// Handler for .load() called.
	});
	
	$(".required_rule").on('change', function (event) 
	{
		$('.nullable_rule').iCheck('uncheck');
	});

	$(".nullable_rule").on('change', function (event) 
	{		 
		$('.required_rule').iCheck('uncheck');
	});

	$(".nullable_rule").on('ifUnchecked', function (event) 
	{
		 
		$('.required_rule').iCheck('check');
	});

	$(".required_rule").on('ifUnchecked', function (event) 
	{
		 
		$('.nullable_rule').iCheck('check');
	});

	$(".only_number").on('change', function (event) 
	{
		if ($("input#only_number_id").is(':checked')) { 
		
			$('.only_char,.char_space,.char_num,.email,.url,.date').iCheck('disable');
			$('.only_char,.char_space,.char_num,.email,.url,.date').iCheck('uncheck');
			$('.only_char,.char_space,.char_num,.email,.url,.date').attr('disabled', true);
		}
		else{
			$('.only_char,.char_space,.char_num,.email,.url,.date').iCheck('enable');
			$('.only_char,.char_space,.char_num,.email,.url,.date').attr('disabled', false);
		}
	});

	$(".only_char").on('change', function (event)
	{
		if ($("input#only_char_id").is(':checked')) {
			$('.only_number,.char_space,.char_num,.email,.url,.date').iCheck('disable');
			$('.only_number,.char_space,.char_num,.email,.url,.date').iCheck('uncheck');
			$('.only_number,.char_space,.char_num,.email,.url,.date').attr('disabled', true);
		}
		else{
			
			$('.only_number,.char_space,.char_num,.email,.url,.date').iCheck('enable');
			$('.only_number,.char_space,.char_num,.email,.url,.date').attr('disabled', false);
		}
	});

	$(".char_num").on('change', function (event) 
	{
		if ($("input#char_num_id").is(':checked')) {
			$('.only_char,.only_number,.char_space,.email,.url,.date').iCheck('disable');
			$('.only_char,.only_number,.char_space,.email,.url,.date').iCheck('uncheck');
			$('.only_char,.only_number,.char_space,.email,.url,.date').attr('disabled', true);
		}
		else{
			$('.only_char,.only_number,.char_space,.char_num,.email,.url,.date').iCheck('enable');
			$('.only_char,.only_number,.char_space,.char_num,.email,.url,.date').attr('disabled', false);
		}
	});
	

	$(".char_space").on('change', function (event)
	{
		if ($("input#char_space_id").is(':checked')) {
			$('.only_char,.only_number,.char_num,.email,.url,.date').iCheck('disable');
			$('.only_char,.only_number,.char_num,.email,.url,.date').iCheck('uncheck');
			$('.only_char,.only_number,.char_num,.email,.url,.date').attr('disabled', true);
		}
		else{
			$('.only_char,.only_number,.char_num,.email,.url,.date').iCheck('enable');
			$('.only_char,.only_number,.char_num,.email,.url,.date').attr('disabled', false);
		}
	});

	$(".email").on('change', function (event) 
	{
		if ($("input#email_id").is(':checked')) {
			$('.only_char,.only_number,.char_num,.char_space,.url,.date').iCheck('disable');
			$('.only_char,.only_number,.char_num,.char_space,.url,.date').iCheck('uncheck');
			$('.only_char,.only_number,.char_num,.char_space,.url,.date').attr('disabled', true);
		}
		else{
			$('.only_char,.only_number,.char_num,.char_space,.url,.date').iCheck('enable');
			$('.only_char,.only_number,.char_num,.char_space,.url,.date').attr('disabled', false);
		}
	});

	$(".url").on('change', function (event) 
	{
		if ($("input#url_id").is(':checked')) {
			$('.only_char,.only_number,.char_num,.char_space,.email,.date').iCheck('disable');
			$('.only_char,.only_number,.char_num,.char_space,.email,.date').iCheck('uncheck');
			$('.only_char,.only_number,.char_num,.char_space,.email,.date').attr('disabled', true);
		}
		else{
			$('.only_char,.only_number,.char_num,.char_space,.email,.date').iCheck('enable');
			$('.only_char,.only_number,.char_num,.char_space,.email,.date').attr('disabled', false);
		}
	});

	$(".date").on('change', function (event) 
	{
		if ($("input#date0").is(':checked')) 
		{
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').iCheck('disable');
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').iCheck('uncheck');
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').attr('disabled', true);

			$.each($('.date'), function (key, value) 
			{
				$('#date' + key).iCheck('disable');
				$('#date' + key).attr('disabled', true);
			});
			$(this).iCheck('enable');
			$(this).attr('disabled', false);
		}
		else if ($("input#date1").is(':checked')) 
		{
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').iCheck('disable');
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').iCheck('uncheck');
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').attr('disabled', true);

			$.each($('.date'), function (key, value) 
			{
				$('#date' + key).iCheck('disable');
				$('#date' + key).attr('disabled', true);
			});
			$(this).iCheck('enable');
			$(this).attr('disabled', false);
		}
		else if ($("input#date2").is(':checked')) 
		{
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').iCheck('disable');
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').iCheck('uncheck');
			$('.only_char,.only_number,.char_num,.char_space,.email,.url,.min,.max').attr('disabled', true);

			$.each($('.date'), function (key, value) 
			{
				$('#date' + key).iCheck('disable');
				$('#date' + key).attr('disabled', true);
			});
			$(this).iCheck('enable');
			$(this).attr('disabled', false);
		}
		else
		{
			$('.only_char,.only_number,.char_num,.char_num,.char_space,.email,.url,.min,.max').iCheck('uncheck');
			$('.only_char,.only_number,.char_num,.char_num,.char_space,.email,.url,.min,.max').attr('disabled', false);

			$.each($('.date'), function (key, value) 
			{
				$('#date' + key).iCheck('enable');
				$('#date' + key).attr('disabled', false);
			});
		}	
	});

	$('body').on('change', '.dropdown_change', function () 
	{
		var dropdwon_data = $(".dropdown_change option:selected").val();
	 	 
		if (dropdwon_data == 'text' || dropdwon_data == 'textarea') 
		{
			$('.date').iCheck('disable');
			$('.date').attr('disabled', true);
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url').iCheck('enable');
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url').attr('disabled', false);
			
			$('.file_type_and_size').fadeOut(1000);
			$('.radio_cat').fadeOut(1000);
			$('.checkbox_cat').fadeOut(1000);
			$('.sub_cat').fadeOut(1000);

		}
		else if (dropdwon_data == 'dropdown') 
		{ 
			$('.radio_cat').fadeOut(1000);
			$('.checkbox_cat').fadeOut(1000);
			$('.sub_cat').fadeIn(1000);

			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').iCheck('disable');
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').attr('disabled', true);
			
			$('.file_type_and_size').fadeOut(1000);	
			$('#max_value').val('max');
			$('#max_limit').fadeOut(1000);
			$('#min_value').val('min');
			$('#min_limit').fadeOut(1000);
		}
		else if (dropdwon_data == 'checkbox') 
		{
			 
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').iCheck('disable');
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').attr('disabled', true);
			
			$('.file_type_and_size').fadeOut(1000);
			$('.sub_cat').fadeOut(1000);
			$('.radio_cat').fadeOut(1000);
			$('.checkbox_cat').fadeIn(1000);
		}
		else if (dropdwon_data == 'radio') 
		{
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').iCheck('disable');
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').attr('disabled', true);
			
			$('.file_type_and_size').fadeOut(1000);
			$('.sub_cat').fadeOut(1000);
			$('.checkbox_cat').fadeOut(1000);
			$('.radio_cat').fadeIn(1000);
			$('#max_value').val('max');
			$('#max_limit').fadeOut(1000);
			$('#min_value').val('min');
			$('#min_limit').fadeOut(1000);

		}
		else if (dropdwon_data == 'date') 
		{

			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url').iCheck('disable');
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url').attr('disabled', true);

			$('.date').iCheck('enable');
			$('.date').attr('disabled', false);
			$('.file_type_and_size').fadeOut(1000);
			$('.radio_cat').fadeOut(1000);
			$('.checkbox_cat').fadeOut(1000);
			$('.sub_cat').fadeOut(1000);
			$('#max_value').val('max');
			$('#max_limit').fadeOut(1000);
			$('#min_value').val('min');
			$('#min_limit').fadeOut(1000);
		}
		else if (dropdwon_data == 'file') 
		{

			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').iCheck('disable');
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').attr('disabled', true);
			
			$('.file_type_and_size').fadeIn(1000);
			$('.radio_cat').fadeOut(1000);
			$('.checkbox_cat').fadeOut(1000);
			$('.sub_cat').fadeOut(1000);
			$('#max_value').val('max');
			$('.file_types_value').val('file_types');
			$('.file_size_value').val('file_upload_size');
			$('#max_limit').fadeOut(1000);
			$('#min_value').val('min');
			$('#min_limit').fadeOut(1000);
		}
		else 
		{
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').iCheck('disable');
			$('.only_number,.only_char,.char_space,.char_num,.email,.max,.min,.url,.date').attr('disabled', false);
			
			$('.file_type_and_size').fadeOut(1000);
			$('.radio_cat').fadeOut(1000);
			$('.checkbox_cat').fadeOut(1000);
			$('.sub_cat').fadeOut(1000);
			$('#max_value').val('max');
			$('#max_limit').fadeOut(1000);
			$('#min_value').val('min');
			$('#min_limit').fadeOut(1000);
		}
	});
	$('body').on('change', '#module_name', function () 
	{
		var module_name_data = $("#module_name option:selected").val();

		if (module_name_data == 'user') 
		{
			$('.role_div').fadeIn(1000);			
		}
		else
		{
			$('.role_div').fadeOut(1000);
		}	
	});	

		$('body').on('click', '.add_more_drop', function () 
	{
		var text = $('.d_label').val();
		if(text == '')
		{
			alert(language_translate2.enter_value_alert);
			return false;
		}
		else
		{
			if(text.length>0){
				$('.drop_label').append('<div class="badge badge-danger label_data custom-margin" ><input type="hidden" value="' + text + '" name="d_label[]"><span>' + text + '</span><a href="#"><i class="fa fa-trash font-medium-2 delete_d_label" aria-hidden="true"></i></a></div> ');
				$('.d_label').val('');

			}
			
		}
	});
	 
	$('body').on('click', '.delete_d_label', function () 
	{
		$(this).parents('.label_data').remove();
	});

	$('body').on('click', '.add_more_checkbox', function () 
	{
		
		var text = $('.c_label').val();
		if(text == '')
		{
			alert(language_translate2.enter_value_alert);
			return false;
		}
		else
		{
			if(text.length>0){
				$('.checkbox_label').append('<div class="badge badge-danger label_data label_checkbox custom-margin"  ><input type="hidden" value="' + text + '"  name="c_label[]"><span>' + text + '</span><a href="#"><i class="fa fa-trash font-medium-2 delete_c_label" aria-hidden="true"></i></a></div> ');
				$('.c_label').val('');
			}	
		}
	});

	$('body').on('click', '.delete_c_label', function () 
	{
		$(this).parents('.label_checkbox').remove();
	});

	$('body').on('click', '.add_more_radio', function ()
	{
		var text = $('.r_label').val();
		if(text.length>0)
		{
			$('.radio_label').append('<div class="badge badge-danger label_data label_radio custom-margin custom_css" ><input type="hidden" value="' + text + '"  name="r_label[]"><span>' + text + '</span><a href="#" class="ml_5"><i class="fa fa-trash font-medium-2 delete_r_label" aria-hidden="true"></i></a></div>');
			$('.r_label').val('');
		}	
	});

	$('body').on('click', '.delete_r_label', function () 
	{
		$(this).parents('.label_radio').remove();
	});

	$(".opentext").on('change', function (event) 
	{
		if ($(this).prop("checked") == true) 
		{
			var value_data = $(this).attr('value');

			if (value_data == 'max') 
			{
				$('#max_limit').fadeIn(1000);
			}
			else if (value_data == 'min') 
			{
				$('#min_limit').fadeIn(1000);
			}
		} 
		else
		{
			var value_data = $(this).attr('value');

			if (value_data == 'max') 
			{
				$('#max_limit').fadeOut(1000);
			}
			else if (value_data == 'min') 
			{
				$('#min_limit').fadeOut(1000);
			}
		}
	});
	
	$('body').on('keyup', '#max', function () 
	{
		var limit = 'max:' + $(this).val();
		$('#max_value').attr('value', limit);
	});

	$('body').on('keyup', '#min', function () 
	{
		var limit = 'min:' + $(this).val();
		$('#min_value').attr('value', limit);
	});
	
	$('body').on('keyup', '.file_types_input', function () 
	{
		var limit = 'file_types:' + $(this).val();
		$('.file_types_value').attr('value', limit);
	});
	
	$('body').on('keyup', '.file_size_input', function () 
	{
		var limit = 'file_upload_size:' + $(this).val();
		$('.file_size_value').attr('value', limit);
	});

});
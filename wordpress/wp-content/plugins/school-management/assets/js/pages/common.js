jQuery(document).ready(function($)
{
	"use strict";
	jQuery(".view_more_details_div").on("click", ".view_more_details", function(event)
	{		
		$('.view_more_details_div').removeClass("d-block");
		$('.view_more_details_div').addClass("d-none");

		$('.view_more_details_less_div').removeClass("d-none");
		$('.view_more_details_less_div').addClass("d-block");

		$('.user_more_details').removeClass("d-none");
		$('.user_more_details').addClass("d-block");
		
	});		
	jQuery(".view_more_details_less_div").on("click", ".view_more_details_less", function(event)
	{
		$('.view_more_details_div').removeClass("d-none");
		$('.view_more_details_div').addClass("d-block");

		$('.view_more_details_less_div').removeClass("d-block");
		$('.view_more_details_less_div').addClass("d-none");

		$('.user_more_details').removeClass("d-block");
		$('.user_more_details').addClass("d-none");
	});

	$("#sinfather").on('click',function(){
		$("#motid,#motid1,#motid2,#motid3,#motid4,#motid5,#motid6,#motid7,#motid8,#motid9,#motid10,#motid11,#motid12,#motid13,#motid14,#motid15,#motid16,#motid17,#motid18").hide();

	});
	$("#sinfather").on('click',function(){
		$("#fatid,#fatid1,#fatid2,#fatid3,#fatid4,#fatid5,#fatid6,#fatid7,#fatid8,#fatid9,#fatid10,#fatid11,#fatid12,#fatid13,#fatid14,#fatid15,#fatid16,#fatid17,#fatid18").show();
	});

	$("#sinmother").on('click',function(){
		$("#motid,#motid1,#motid2,#motid3,#motid4,#motid5,#motid6,#motid7,#motid8,#motid9,#motid10,#motid11,#motid12,#motid13,#motid14,#motid15,#motid16,#motid17,#motid18").show();
		$('.mother_div').css('clear','both');
	});
	$("#sinmother").on('click',function(){
		$("#fatid,#fatid1,#fatid2,#fatid3,#fatid4,#fatid5,#fatid6,#fatid7,#fatid8,#fatid9,#fatid10,#fatid11,#fatid12,#fatid13,#fatid14,#fatid15,#fatid16,#fatid17,#fatid18").hide();
	});

	$("#boths").on('click',function(){
		$("#motid,#motid1,#motid2,#motid3,#motid4,#motid5,#motid6,#motid7,#motid8,#motid9,#motid10,#motid11,#motid12,#motid13,#motid14,#motid15,#motid16,#motid17,#motid18").show();
		$('.mother_div').css('clear','unset');
	});
	$("#boths").on('click',function(){
		$("#fatid,#fatid1,#fatid2,#fatid3,#fatid4,#fatid5,#fatid6,#fatid7,#fatid8,#fatid9,#fatid10,#fatid11,#fatid12,#fatid13,#fatid14,#fatid15,#fatid16,#fatid17,#fatid18").show();
	});
	jQuery('#admission_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('.email').on('change',function(){
		var father_email = $(".father_email").val();
		var student_email = $(".email").val();
		var mother_email = $(".mother_email").val();

		if(student_email == father_email)
		{
			alert(language_translate2.same_email_alert);
			$('.email').val('');
		}
		else if(student_email == mother_email)
		{
			alert(language_translate2.same_email_alert);
			$('.email').val('');
		}
		else
		{
			return true; 
		}
	});	
	$('.father_email').on('change',function(){
		var father_email = $(".father_email").val();
		var student_email = $(".email").val();
		var mother_email = $(".mother_email").val();

		if(student_email == father_email)
		{
			alert(language_translate2.same_email_alert);
			$('.father_email').val('');
		}
		else if(father_email == mother_email)
		{
			alert(language_translate2.same_email_alert);
			$('.father_email').val('');
		}
		else
		{
			return true; 
		}
	});	
	$('.mother_email').on('change',function(){
		var father_email = $(".father_email").val();
		var student_email = $(".email").val();
		var mother_email = $(".mother_email").val();

		if(student_email == mother_email)
		{
			alert(language_translate2.same_email_alert);
			$('.mother_email').val('');
		}
		else if(father_email == mother_email)
		{
			alert(language_translate2.same_email_alert);
			$('.mother_email').val('');
		}
		else
		{
			return true; 
		}
	});	
	if ($('#chkIsTeamLead').is(':checked') == true)
	{
		$('#sibling_div').addClass('sibling_div_block');
		$('#sibling_div').removeClass('sibling_div_none');
	} 
	else 
	{
		$('#sibling_div').removeClass('sibling_div_block');
		$('#sibling_div').addClass('sibling_div_none');
		
	}
	$('#chkIsTeamLead').on('change',function(){
		if ($('#chkIsTeamLead').is(':checked') == true)
		{
			$('#sibling_div').addClass('sibling_div_block');
			$('#sibling_div').removeClass('sibling_div_none');
		} 
		else 
		{
			$('#sibling_div').removeClass('sibling_div_block');
			$('#sibling_div').addClass('sibling_div_none');
			
		}
	});		
	 
	var table =  jQuery('#class_list').DataTable({
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
				alert(language_translate2.delete_record_alert);
				return false;
			}
		else{
				alert(language_translate2.delete_record_alert);
				return true;
			}
	});

	$('#email_template_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
  
    $('#email_template_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	

	$('#email_template_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

	 $('#email_template_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	

});
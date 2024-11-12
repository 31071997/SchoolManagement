jQuery(document).ready(function($){
	"use strict";	
	
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

    $('#admission_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
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

            $('#chkIsTeamLead').on('change',function(){
				if ($('#chkIsTeamLead').is(':checked') == true){
					$('#sibling_div').addClass('sibling_div_block');
					$('#sibling_div').removeClass('sibling_div_none');
				} else {
					$('#sibling_div').removeClass('sibling_div_block');
					$('#sibling_div').addClass('sibling_div_none');
					
				}
			});	

});

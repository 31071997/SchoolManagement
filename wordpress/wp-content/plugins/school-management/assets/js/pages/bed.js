jQuery(document).ready(function($){
	"use strict";	
	$('#bed_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

	$('#hostel_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

	$('#room_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

   	$('#bed_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

$('.datepicker').datepicker({
				defaultDate: null,
				changeMonth: true,
				changeYear: true,
				yearRange:'-75:+10',
				dateFormat: 'yy-mm-dd',
				
			 });
			// $('#assigndate_<?php echo $i ; ?>').hide();
			// $("#Assign_bed").prop("disabled", true);	
			// $('.students_list_<?php echo $i ;?>').change(function () {
			// 	var optionSelected = $(this).find("option:selected");
			// 	var valueSelected  = optionSelected.val();
			// 	var i  = '<?php echo $i ;?>';
	        //   checkselectvalue(valueSelected,i);
			//  });
		
			function checkselectvalue(value,i) {
			
				$('#assigndate_'+i).hide();
				$('.students_list_'+i).removeClass('student_check');
				$(".student_check").each(function()
				{
					var valueSelected1=$(this).val();
					if(valueSelected1 == value)
					{
						alert(language_translate2.select_different_student_alert);
						$('.students_list_'+i).val('0');	
						return false;	
					}
				});
				var value=$('.students_list_'+i).val();
				if(value =='0' )
				{
					$('#assigndate_'+i).hide();
					var name=0;
					$(".new_class").each(function()
					{
						var new_class=$(this).val();
						if(new_class != '0')
						{
							name=name+1;
						}
					});
					if(name < 1)
					{
						$("#Assign_bed").prop("disabled", true);
					}
				}
				else
				{
					$('#assigndate_'+i).show();
					$("#Assign_bed").prop("disabled", false);
				}
				$('.students_list_'+i).addClass('student_check');
			}
			
			
	$('body').on('change','.student_check',function(){
			// alert(this);
			let index = $(this).attr('data-index');

			
			if($('#students_list_'+index).val() != 0)
			{
				$('#assign_date_'+index).addClass('validate[required]');
			}else{
				$('#assign_date_'+index).removeClass('validate[required]');

			}

	});


});
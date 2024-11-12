jQuery(document).ready(function($){
	"use strict";	
	 $("body").on("click",".parent_csv_selected",function()
		 {
			if ($('.selected_parent:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}		
		}); 
	$('#parent_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	 $('#birth_date').datepicker({
		 dateFormat: "yy-mm-dd",
		 maxDate : 0,
		changeMonth: true,
		changeYear: true,
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
	
	var numItems = $('.parents_child').length;
	if(numItems == 1)
	{$('#revove_item').hide();}

function deleteParentElement(n){
				alert(language_translate2.do_delete_record);
				n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
			}
			$('#add-another_item').on('click',function(event) {
				event.preventDefault();
				var $this = $(this);
				var $last = $this.prev(); // $this.parents('.something').prev() also useful
				var $clone = $last.clone(true);
				var $inputs = $clone.find('input,textarea,select');
				$last.after($clone);
				$inputs.eq(0).focus();
				
				var numItems = $('.parents_child').length;
				if(numItems > 1)
				{
					 $('#revove_item').show();
				}
				
			});		
			$('#revove_item').on('click',function(event) {
				event.preventDefault();
				var numItems = $('.parents_child').length;
				if(numItems > 1)
				{
					 $(this).prev().prev().remove();
					 if(numItems == 2)
						 $('#revove_item').hide();
				}
				else
				{ $('#revove_item').hide();}
			});	


	$('#upload_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

	
	var table =  jQuery('#child_list').DataTable({
			responsive: true,
			"order": [[ 0, "asc" ]],
			"aoColumns":[	                  
			{"bSortable": false},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true}],
			// language:<?php echo smgt_datatable_multi_language();?>				
		});


	$(".view_more_details_div").on("click", ".view_more_details", function(event)
	{
		$('.view_more_details_div').removeClass("d-block");
		$('.view_more_details_div').addClass("d-none");

		$('.view_more_details_less_div').removeClass("d-none");
		$('.view_more_details_less_div').addClass("d-block");

		$('.user_more_details').removeClass("d-none");
		$('.user_more_details').addClass("d-block");

	});		
	$(".view_more_details_less_div").on("click", ".view_more_details_less", function(event)
	{
		$('.view_more_details_div').removeClass("d-none");
		$('.view_more_details_div').addClass("d-block");

		$('.view_more_details_less_div').removeClass("d-block");
		$('.view_more_details_less_div').addClass("d-none");

		$('.user_more_details').removeClass("d-block");
		$('.user_more_details').addClass("d-none");
	});

});
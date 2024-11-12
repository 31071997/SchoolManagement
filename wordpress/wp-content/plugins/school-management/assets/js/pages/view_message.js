jQuery(document).ready(function($)
{
	"use strict";
		jQuery('#select_all').on('click', function(e)
		{
			 if($(this).is(':checked',true))  
			 {
				$(".sub_chk").prop('checked', true);  
			 }
			 else  
			 {
				$(".sub_chk").prop('checked',false);  
			 }
		});
		$("body").on("change", ".sub_chk", function(event)
		{ 
			if(false == $(this).prop("checked"))
			{ 
				$("#select_all").prop('checked', false); 
			}
			if ($('.sub_chk:checked').length == $('.sub_chk').length )
			{
				$("#select_all").prop('checked', true);
			}
		});

		var table =jQuery('#all_message_list1').DataTable({		
		dom: 'Bfrtip',
         buttons: [
			{
                extend: 'print',
                text:'Print',
				title: 'Message Reply Data',
				exportOptions: {
                    columns: [1,2,3,5]
                }
            }
        ],  
		 "bProcessing": true,
		 "bServerSide": true,
		 "sAjaxSource": ajaxurl+'?action=mj_smgt_view_all_relpy',
		 "bDeferRender": true, 		
		responsive: true,
		"order": [[ 1, "asc" ]],
	    "aoColumns":[		  		 
		  {"bSortable": false},              	                 
		  {"bSortable": true},       
		  {"bSortable": true},              	                 
		  {"bSortable": true},              	                 
		  {"bSortable": true},              	                 
		  {"bSortable": true},              	                 
		  {"bSortable": false}],
		  language:"<?php echo mj_smgt_datatable_multi_language();?>" 
		  });
		
		table.on('page.dt', function() {
		  $('html, body').animate({
			scrollTop: $(".dataTables_wrapper").offset().top
		   }, 'slow');
		}); 		
		 
		$(".delete_check").on('click', function()
		{	
			if ($('.sub_chk:checked').length == 0 )
			{
				 alert(language_translate2.one_message_alert);
				return false;
			}
			else{
				alert(language_translate2.delete_record_alert);
				return true;
			}
			 
		});	 

});
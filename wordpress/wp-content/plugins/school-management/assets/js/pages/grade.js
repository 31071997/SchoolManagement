jQuery(document).ready(function($){
	"use strict";	
	$('#grade_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	var table =  jQuery('#grade_list').DataTable({
        responsive: true,
		"order": [[ 2, "desc" ]],
		"dom": 'Bfrtip',
		"buttons": [
			'colvis'
		], 
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                  
	                  {"bSortable": false}],
		// language:<?php //echo smgt_datatable_multi_language();?>
    });
	 jQuery('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
   }); 
   
	jQuery('#delete_selected').on('click', function(){
		 var c = confirm("Are you sure you want to delete this record?");
		if(c){
			jQuery('#frm-example').submit();
		}
	});

});
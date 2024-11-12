function add_entry()
{
	
	blank_custom_label+='<div class="form-group row mb-3">';
	blank_custom_label+='<label class="col-sm-2 control-label col-form-label text-md-end" for="income_entry">Income Entry<span class="require-field">*</span></label>';
	blank_custom_label+='<div class="col-sm-2">';
	blank_custom_label+='<input id="income_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]">';
	blank_custom_label+='</div>';
	blank_custom_label+='<div class="col-sm-4">';
	blank_custom_label+='<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]">';
	blank_custom_label+='</div>';
	blank_custom_label+='<div class="col-sm-2">';
	blank_custom_label+='<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">';
	blank_custom_label+='<i class="entypo-trash">Delete</i>';
	blank_custom_label+='</button>';
	blank_custom_label+='</div>';
	blank_custom_label+='</div>';						
	$("#income_entry").html(blank_custom_label);
}
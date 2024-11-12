<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/feetype.js'; ?>" ></script>

<?php 	
	if($active_tab == 'addfeetype')
	{
       	$fees_id=0;
		if(isset($_REQUEST['fees_id']))
			$fees_id=$_REQUEST['fees_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_fees->mj_smgt_get_single_feetype_data($fees_id);
			}
			?>
		
       <div class="panel-body">
        <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="fees_id" value="<?php echo $fees_id;?>">
		<input type="hidden" name="invoice_type" value="expense">
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="category_data"><?php esc_attr_e('Fee Type','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="fees_title_id" id="category_data" class="form-control validate[required]">
					<option value = ""><?php esc_attr_e('Select Fee Type','school-mgt');?></option>
					<?php 
					$fee_type = 0;
					if($edit)
						$fee_type = $result->fees_title_id;
					$feeype_data=$obj_fees->mj_smgt_get_all_feetype();
				
					if(!empty($feeype_data))
					{
						foreach ($feeype_data as $retrieved_data)
						{
							echo '<option value="'.$retrieved_data->ID.'" '.selected($fee_type,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
						}
					}
					?>
			</select>
			</div>
			<div class="col-sm-2">
				<button id="addremove" model="feetype"><?php esc_attr_e('Add Or Remove','school-mgt');?></button>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $classval = 0;
			if($edit)
				$classval = $result->class_id;?>
                <select name="class_id" class="form-control validate[required]" id="class_name">
                   	<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
                    <?php
					foreach(mj_smgt_get_allclass() as $classdata)
					{  
					?>
						<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
					<?php 
					}?>
                </select>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="fees_amount"><?php esc_attr_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="fees_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php if($edit){ echo $result->fees_amount;}elseif(isset($_POST['fees_amount'])) echo $_POST['fees_amount'];?>" name="fees_amount">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<textarea name="description" class="form-control validate[custom[address_description_validation]]" maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>				
			</div>
		</div>
		
		<div class="offset-sm-2 col-sm-8">
        	 <input type="submit" value="<?php if($edit){ esc_attr_e('Save Fee Type','school-mgt'); }else{ esc_attr_e('Create Fee Type','school-mgt');}?>" name="save_feetype" class="btn btn-success"/>
        </div>
        </form>
        </div>
     <?php 
	 }
	 ?>
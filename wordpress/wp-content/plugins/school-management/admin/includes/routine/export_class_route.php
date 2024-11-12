<?php
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit=1;
}
?>
<div class="panel-white margin_top_20px padding_top_25px_res">
    <div class="panel-body"> <!------- panel Body ------->
        <form name="export_class_csv" action="" method="post" class="form-horizontal" id="export_class_csv" enctype="multipart/form-data">
            <div class="form-body user_form">
				<div class="row">
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="required">*</span></label>
						<?php if($edit){ $classval=$route_data->class_id; }elseif(isset($_POST['class_id'])){$classval=$_POST['class_id'];}else{$classval='';}?>
						<select name="class_id"  id="class_list" class="form-control validate[required] max_width_100">
							<option value=" "><?php esc_attr_e('Select class Name','school-mgt');?></option>
							<?php
							foreach(mj_smgt_get_allclass() as $classdata)
							{  
								?>
								<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
								<?php 
							}?>
						</select>                                 
					</div>
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
						<select name="class_section" class="form-control max_width_100 section_id_exam" id="class_section">
							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
							<?php
							if($edit)
							{
								foreach(mj_smgt_get_class_sections($route_data->class_id) as $sectiondata)
								{  ?>
									<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
									<?php 
								} 
							}?>
						</select>                             
					</div>
                </div>
            </div>
            <?php wp_nonce_field( 'upload_class_route_admin_nonce' ); ?>
            <div class="form-body user_form">
                <div class="row">
                    <div class="col-sm-6">        	
                        <input type="submit" value="<?php esc_attr_e('Export CSV','school-mgt'); ?>" name="save_export_csv" class="btn save_btn" />
                    </div> 
                </div>
            </div>
        </form>
    </div>
</div>
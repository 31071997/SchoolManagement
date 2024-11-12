<script type="text/javascript">
	$(document).ready(function()
	{    //EVENT VALIDATIONENGINE
		"use strict";
		<?php
		if (is_rtl())
			{
			?>
				$('#event_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
			<?php
			}
			else{
				?>
				$('#event_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
				<?php
			}
		?>
		var start = new Date();
		var end = new Date(new Date().setYear(start.getFullYear()+1));
		$("#start_date_event").datepicker(
		{
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			minDate:0,
			changeMonth: true,
			changeYear: true,
			onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() + 0);
				$("#end_date_event").datepicker("option", "minDate", dt);
			},
			beforeShow: function (textbox, instance)
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			}
	    });
	    $("#end_date_event").datepicker(
		{
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			minDate:0,
			changeMonth: true,
			changeYear: true,
	       onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() - 0);
				$("#start_date_event").datepicker("option", "maxDate", dt);
			},
			beforeShow: function (textbox, instance)
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			}
	    });
	} );
</script>
<script>
    //-------- timepicker ---------//
    jQuery(document).ready(function($){
        mdtimepicker('#timepicker', {
        events: {
                timeChanged: function (data) {
                }
            },
        theme: 'purple',
        readOnly: false,
        });
    })
</script>
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/timepicker_rtl.css'; ?>">
<script type="text/javascript">
	function fileCheck(obj)
	{   //FILE VALIDATIONENGINE
		"use strict";
		var fileExtension = ['pdf','doc','jpg','jpeg','png'];
		if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
		{
			alert("<?php esc_html_e('Sorry, only JPG, pdf, docs., JPEG, PNG And GIF files are allowed.','school-mgt');?>");
			$(obj).val('');
		}
	}
</script>
<?php
$event_id=0;
if(isset($_REQUEST['event_id']))
{
	$event_id=$_REQUEST['event_id'];
	$edit=0;
}
else
{
	$edit=0;
}
if(isset($_REQUEST['action']) && sanitize_text_field($_REQUEST['action']) == 'edit')
{
	$edit=1;
	$result = $obj_event->MJ_smgt_get_single_event($event_id);
}
?>

<div class="panel-body padding_0"><!--PANEL BODY-->
	<form name="event_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="event_form"><!--ADD EVENT FORM-->
		<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>
		<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
		<input type="hidden" name="event_id" value="<?php echo esc_attr($event_id);?>"  />
		<div class="header">
			<h3 class="first_hed"><?php esc_html_e('Event Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form"> <!-- user_form Strat-->
			<div class="row"><!--Row Div Strat-->
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="event_title" maxlength="100" class="form-control text-input validate[required,custom[description_validation]]" type="text"  value="<?php if($edit){ echo esc_attr($result->event_title);}elseif(isset($_POST['event_title'])) echo esc_attr($_POST['event_title']);?>" name="event_title">
							<label class="" for="event_title"><?php esc_html_e('Event Title','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="description" id="description" maxlength="1000" class="textarea_height_60px form-control validate[required,custom[description_validation]] text-input"><?php if($edit){ echo $result->description; }elseif(isset($_POST['description'])) echo esc_textarea($_POST['description']);?></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active" for="desc"><?php esc_html_e('Description','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
				</div>
				<style>
					.dropdown-menu {
						min-width: 240px;
					}
				</style>
				<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="start_date_event" class="form-control date_picker validate[required] start_date datepicker1" autocomplete="off" type="text"  name="start_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->start_date)));}elseif(isset($_POST['start_date'])) echo mj_smgt_getdate_in_input_box($_POST['start_date']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
							<label class="active date_label" for="start"><?php esc_html_e('Start Date','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input  id="timepicker" placeholder="<?php esc_html_e('Start Time','school-mgt');?>" type="text" value="<?php if($edit){ echo esc_attr($result->start_time);}elseif(isset($_POST['start_time'])) echo esc_attr($_POST['start_time']);?>" class="form-control event_start_time validate[required]" name="start_time"/>
						</div>
					</div>
				</div>

				<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="end_date_event" class="form-control date_picker validate[required] start_date datepicker2" type="text"  name="end_date" autocomplete="off" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->end_date)));}elseif(isset($_POST['end_date'])) echo mj_smgt_getdate_in_input_box($_POST['end_date']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
							<label class="date_label" for="end"><?php esc_html_e('End Date','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="timepicker" placeholder="<?php esc_html_e('End Time','school-mgt');?>" type="text" value="<?php if($edit){ echo esc_attr($result->end_time);}elseif(isset($_POST['end_time'])) echo esc_attr($_POST['end_time']);?>" class="form-control event_end_time validate[required]" name="end_time"/>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="form-group input">
						<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">
							<label class="custom-control-label custom-top-label ml-2 label_position_rtl" for="Document"><?php esc_html_e('Document','school-mgt');?></label>
							<div class="col-sm-12 display_flex">
								<input type="hidden" name="hidden_upload_file" value="<?php if($edit){ echo $result->event_doc;}elseif(isset($_POST['upload_file'])) echo $_POST['upload_file'];?>">
								<input id="upload_file" name="upload_file" type="file" onchange="fileCheck(this);" class=""  />
							</div>
						</div>
					</div>
				</div>
				<?php
				if(!$edit){
				?>
				<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position" style="min-height: 47px !important;">
							<div class="row padding_radio">
								<div class="">
									<label class="custom-top-label label_position_rtl" for="smgt_enable_event_mail"><?php esc_attr_e('Send Mail','school-mgt');?></label>
									<input type="checkbox" class="check_box_input_margin" name="smgt_enable_event_mail"  value="1" />&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position" style="min-height: 47px !important;">
							<div class="row padding_radio">
								<div class="">
									<label class="custom-top-label label_position_rtl" for="smgt_enable_event_sms"><?php esc_attr_e('Send SMS','school-mgt');?></label>
									<input type="checkbox" class="check_box_input_margin" name="smgt_enable_event_sms"  value="1" />&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		<!----------   save btn    -------------->
		<div class="form-body user_form"> <!-- user_form Strat-->
			<div class="row"><!--Row Div Strat-->
				<div class="col-md-6 col-sm-6 col-xs-12">
					<?php wp_nonce_field( 'save_event_nonce' ); ?>
					<input id="save_event_btn" type="submit" value="<?php if($edit){ esc_html_e('Save Event','school-mgt'); }else{ esc_html_e('Add Event','school-mgt');}?>" name="save_event" class="btn save_btn event_time_validation"/>
				</div>
			</div><!--Row Div End-->
		</div><!-- user_form End-->
	</form><!--END ADD EVENT FORM-->
</div><!--END PANEL BODY-->
<?php ?>
<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'setup';
?>
<div id="cmgt_imgSpinner1"></div>
<div class="cmgt_ajax-ani"></div>
<div class="cmgt_ajax-img"><img src="<?php echo SMS_PLUGIN_URL.'/assets/images/loading.gif';?>" height="50px" width="50px"></div>
<div class="page-inner" style="min-height:1088px !important">
	<?php 
	if(isset($_REQUEST['varify_key']))
	{
		$verify_result = mj_smgt_submit_setupform($_POST);
		if($verify_result['cmgt_verify'] == '0')
		{
			?>
				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible successMessage">
				<p><?php echo $verify_result['message'];?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		    </div>
			<?php
		}
		else
		{
		?>
		<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $verify_result['message'];?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
		<?php
		}
	}
	?>
	<script type="text/javascript">
    jQuery(document).ready(function ($) {
        setTimeout(function () {
            $('.successMessage').fadeOut('fast', function () {
                // Redirect to another page after fading out
                window.location.href = '<?php echo admin_url('admin.php?page=smgt_school'); ?>';
            });
        }, 3000); // <-- time in milliseconds
    });
    </script>
	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/setup.js'; ?>" ></script>
	

		<div class="panel-body main_list_margin_15px"><!------------------ PENAL BODY ------------------->
			<!------------------ LICENSE VERIFICATION FORM ---------------------->
			<form name="verification_form" action="" method="post" class="form-horizontal" id="verification_form">
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('License Key Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"><!---------------- FORM BODY ------------------>
					<div class="row">	
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="server_name" class="form-control validate[required]" type="text" value="<?php echo $_SERVER['SERVER_NAME'];?>" name="domain_name" readonly>
									<label for="userinput1" class=""><?php esc_html_e('Domain','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control licence_key">
									<input id="licence_key" class="form-control validate[required]" type="text"  value="" name="licence_key">
									<label for="userinput1" class=""><?php esc_html_e('Envato License key','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="enter_email" class="form-control validate[required,custom[email]]" type="text"  value="" name="enter_email">
									<label for="userinput1" class=""><?php esc_html_e('Email','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
					</div>
				</div><!---------------- FORM BODY ------------------>
				<div class="form-body user_form">
					<div class="row">	
						<div class="col-sm-6">
							<input type="submit" value="<?php esc_attr_e('Submit','school-mgt');?>" name="varify_key" id="varify_key_new" class="btn btn-success save_btn"/>
						</div>
					</div>
				</div>
			</form><!------------------ LICENSE VERIFICATION FORM ---------------------->
		</div><!------------------ PENAL BODY ------------------->
	
</div>
<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	 $('#sms_setting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
});
</script>
<?php 
mj_smgt_browser_javascript_check();
$role=mj_smgt_get_user_role(get_current_user_id());
if($role == 'administrator')
{
	$user_access_add=1;
	$user_access_edit=1;
	$user_access_delete=1;
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('sms_setting');
	$user_access_add=$user_access['add'];
	$user_access_edit=$user_access['edit'];
	$user_access_delete=$user_access['delete'];
	$user_access_view=$user_access['view'];
}
$current_sms_service_active =get_option( 'smgt_sms_service');
if(isset($_REQUEST['save_sms_setting']))
{
	if(isset($_REQUEST['select_serveice']) && $_REQUEST['select_serveice'] == 'clickatell')
	{
		$custm_sms_service = array();
		$result=get_option( 'smgt_clickatell_sms_service');
		
		$custm_sms_service['username'] = trim($_REQUEST['username']);
		$custm_sms_service['password'] = $_REQUEST['password'];
		$custm_sms_service['api_key'] = $_REQUEST['api_key'];
		$custm_sms_service['sender_id'] = $_REQUEST['sender_id'];
		$result=update_option( 'smgt_clickatell_sms_service',$custm_sms_service );
	}
	if(isset($_REQUEST['select_serveice']) && $_REQUEST['select_serveice'] == 'twillo')
	{
		$custm_sms_service = array();
		$result=get_option( 'smgt_twillo_sms_service');
		$custm_sms_service['account_sid'] = trim($_REQUEST['account_sid']);
		$custm_sms_service['auth_token'] = trim($_REQUEST['auth_token']);
		$custm_sms_service['from_number'] = $_REQUEST['from_number'];
		$result=update_option( 'smgt_twillo_sms_service',$custm_sms_service );
	}
	if(isset($_REQUEST['select_serveice']) && $_REQUEST['select_serveice'] == 'msg91')
	{
		$custm_sms_service = array();
		$result=get_option( 'smgt_msg91_sms_service');
		$custm_sms_service['msg91_senderID'] = trim($_REQUEST['msg91_senderID']);
		$custm_sms_service['sms_auth_key'] = trim($_REQUEST['sms_auth_key']);
		$custm_sms_service['wpnc_sms_route'] = $_REQUEST['wpnc_sms_route'];
		$result=update_option( 'smgt_msg91_sms_service',$custm_sms_service );
	}
	
	update_option( 'smgt_sms_service',$_REQUEST['select_serveice'] );

	wp_redirect ( admin_url() . 'admin.php?page=smgt_sms-setting&message=1');
}
?>
<div class="page-inner"><!-- page-inner -->
	<div class="main_list_margin_15px marks_list"><!-- main_list_margin_15px -->
		<?php
			$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
			switch($message)
			{
				case '1':
					$message_string = esc_attr__('SMS Settings Updated Successfully.','school-mgt');
					break;
			}
			
			if($message)
			{ ?>
				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
					<p><?php echo $message_string;?></p>
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
		<?php } ?>
		<div class="row"><!-- row -->
			<div class="col-md-12 padding_0"><!-- col-md-12 -->
				<div class="smgt_main_listpage"><!-- smgt_main_listpage -->
					<div class="panel-body"> 
						<form action="" method="post" class="form-horizontal" id="sms_setting_form"> 
							<div class="header">	
								<h3 class="first_hed"><?php esc_html_e('SMS Setting Information','school-mgt');?></h3>
							</div>
							<div class="form-body user_form">
								<div class="row">
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group">
											<div class="col-md-12 form-control">
												<div class="row padding_radio">
													<div class="input-group">
														<label class="custom-top-label" for="enable"><?php esc_attr_e('Select Message Service','school-mgt');?></label>
														<div class="d-inline-block select_message_service">
															<label class="radio-inline custom_radio">
																<input id="checkbox" type="radio" <?php echo checked($current_sms_service_active,'clickatell');?>  name="select_serveice" class="label_set" value="clickatell"> <?php esc_attr_e('Clickatell ','school-mgt');?> 
															</label> 
															<label class="radio-inline custom_radio">
																<input id="checkbox" type="radio"  <?php echo checked($current_sms_service_active,'msg91');?> name="select_serveice" class="label_set" value="msg91">  <?php esc_attr_e('MSG91 ','school-mgt');?>
															</label>
														</div>
													</div>
												</div>		
											</div>
										</div>
									</div>
								</div>
							</div>
									
							<div class="mt-3" id="sms_setting_block">
								<?php 
								if($current_sms_service_active == 'clickatell')
								{
									$clickatell=get_option( 'smgt_clickatell_sms_service');
									?>
									<div class="form-body user_form mt-3">
										<div class="row">

											<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="username" class="form-control validate[required]" type="text" value="<?php echo $clickatell['username'];?>" name="username">
														<label class="" for="username"><?php esc_attr_e('Username','school-mgt');?><span class="require-field">*</span></label>
													</div>
												</div>
											</div>

											<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="password" class="form-control validate[required]" type="text" value="<?php echo $clickatell['password'];?>" name="password">
														<label class="" for="password"><?php esc_attr_e('Password','school-mgt');?><span class="require-field">*</span></label>
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="api_key" class="form-control validate[required]" type="text" value="<?php echo $clickatell['api_key'];?>" name="api_key">
														<label class="" for="api_key"><?php esc_attr_e('API Key','school-mgt');?><span class="require-field">*</span></label>
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<div class="form-group input">
													<div class="col-md-12 form-control">
														<input id="sender_id" class="form-control validate[required]" type="text" value="<?php echo $clickatell['sender_id'];?>" name="sender_id">
														<label class="" for="sender_id"><?php esc_attr_e('Sender Id','school-mgt');?><span class="require-field">*</span></label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php 
								}
								if($current_sms_service_active == 'twillo')
								{
								}
								if($current_sms_service_active == 'msg91')
								{
									$msg91=get_option( 'smgt_msg91_sms_service');
									?>
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="sms_auth_key" class="form-control validate[required]" type="text" value="<?php echo $msg91['sms_auth_key'];?>" name="sms_auth_key">
												<label class="" for="sms_auth_key"><?php esc_attr_e('Authentication Key','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="msg91_senderID" class="form-control validate[required] text-input" type="text" name="msg91_senderID" value="<?php echo $msg91['msg91_senderID'];?>">
												<label class="" for="msg91_senderID"><?php esc_attr_e('SenderID','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="wpnc_sms_route" class="form-control validate[required] text-input" type="text" name="wpnc_sms_route" value="<?php echo $msg91['wpnc_sms_route'];?>">
												<label class="" for="wpnc_sms_route"><?php esc_attr_e('Route','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="form-group row mb-3">
										<label class="col-sm-10 control-label col-form-label text-md-end " for="wpnc_sms_route"><b><?php esc_attr_e('If your operator supports multiple routes then give one route name. Eg: route=1 for promotional, route=4 for transactional SMS.','school-mgt');?></b></label>
									</div>	
									<?php 
								}
								?>
							</div>
							<?php if($user_access_add == 1 OR $user_access_edit == 1 )
							{
							?>
							<!-- <div class="offset-sm-2 col-sm-8">        	
								<input type="submit" value="<?php  esc_attr_e('Save','school-mgt');?>" name="save_sms_setting" class="btn btn-success" />
							</div> -->
							<div class="form-body user_form">
								<div class="row">
									<div class="col-sm-6">          	
										<input type="submit" value="<?php  esc_attr_e('Save','school-mgt');?>" name="save_sms_setting" class="btn btn-success save_btn" />
									</div>    
								</div>
							</div>     
							<?php 
							}
							?>
					
						</form>
					</div>
    				<div class="clearfix"> </div>
				</div><!-- smgt_main_listpage -->
	 		</div><!-- col-md-12 -->
	 	</div><!-- row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner -->
<?php ?>
<?php
$role_name=mj_smgt_get_user_role(get_current_user_id());
$user_access=mj_smgt_get_userrole_wise_access_right_array();
$obj_tax = new tax_Manage(); 
?>
<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	$('#tax_list').DataTable({
		"initComplete": function(settings, json) {
			$(".print-button").css({"margin-top": "-5%"});
		},
		//stateSave: true,
		"ordering": true,
		"dom": 'lifrtp',
		"aoColumns":[	                  
					  {"bSortable": false ,"className": 'sorting_disabled'},
	                  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  <?php
						if($user_access['edit'] == '1' || $user_access['delete'] =='1'){
						?>
							{"bSortable": false}
							<?php 
						} ?>
					],
		language:<?php echo mj_smgt_datatable_multi_language();?>
    });
	$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
});
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'tax';
//--------------- ACCESS WISE ROLE -----------//

if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		mj_smgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}

//------------------ SAVE TAX --------------------//
if(isset($_POST['save_tax']))	
{
	
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_tax_admin_nonce' ) )
    {
		if(isset($_POST['action']) && $_POST['action'] == "edit")
		{
			$result=$obj_tax->mj_smgt_insert_tax($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?dashboard=user&page=tax&tab=tax&message=2');
			}
		}
		else
		{
			$result=$obj_tax->mj_smgt_insert_tax($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?dashboard=user&page=tax&tab=tax&message=1');
			}
		}
		

		
	}
}
//------------------ DELETE TAX --------------------//
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result=$obj_tax->mj_smgt_delete_tax($_REQUEST['tax_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=tax&tab=tax&message=3');
		exit;
	}
}
if(isset($_GET['message']) && $_GET['message'] == 1 )
{
	?>
	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
		</button>
		<?php esc_attr_e('Tax Added Successfully.','school-mgt');?>
	</div>
	<?php
}
if(isset($_GET['message']) && $_GET['message'] == 2 )
{
	?>
	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
		</button>
		<?php esc_attr_e('Tax Updated Successfully.','school-mgt');?>
	</div>
	<?php
}
if(isset($_GET['message']) && $_GET['message'] == 3 )
{
	?>
	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
		</button>
		<?php esc_attr_e('Tax Deleted Successfully.','school-mgt');?>
	</div>
	<?php
}
?>
<!-- Nav tabs -->
<div class="panel-body panel-white frontend_list_margin_30px_res"><!-------------- PENAL BODY ------------>
	<?php
	//------------- ACTIVE TAB CLASS LIST -------------//
	if($active_tab == 'tax')
	{
		$user_id=get_current_user_id();
		$own_data=$user_access['own_data'];
		//------- EXAM DATA FOR TEACHER ---------//
		if($school_obj->role == 'teacher')
		{
			
			$retrieve_tax = $obj_tax->MJ_smgt_get_all_tax();
		}
		//------- EXAM DATA FOR SUPPORT STAFF ---------//
		else
		{ 
			$retrieve_tax = $obj_tax->MJ_smgt_get_all_tax();
		} 
		if(!empty($retrieve_tax))
		{
			?>
			<div class="panel-body"><!--------------- PENAL BODY ------------->
				<div class="table-responsive"><!--------------- TABLE RESPONSIVE ----------->
					<!----------- CLASS LIST FORM START ---------->
					<form id="frm-example" name="frm-example" method="post">
						<table id="tax_list" class="display dataTable exam_datatable" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
								<tr>
									<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
									<th><?php esc_attr_e('Tax Title','school-mgt');?></th>
									<th><?php esc_attr_e('Tax Value(%)','school-mgt');?></th>
									<th><?php esc_attr_e('Created Date','school-mgt');?></th>
									<?php
									if($user_access['edit'] == '1' || $user_access['delete'] =='1'){
									?>
										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
							<?php 
								$i=0;
								foreach ($retrieve_tax as $retrieved_data)
								{ 
									if($i == 10)
									{
										$i=0;
									}
									if($i == 0)
									{
										$color_class='smgt_class_color0';
									}
									elseif($i == 1)
									{
										$color_class='smgt_class_color1';
									}
									elseif($i == 2)
									{
										$color_class='smgt_class_color2';
									}
									elseif($i == 3)
									{
										$color_class='smgt_class_color3';
									}
									elseif($i == 4)
									{
										$color_class='smgt_class_color4';
									}
									elseif($i == 5)
									{
										$color_class='smgt_class_color5';
									}
									elseif($i == 6)
									{
										$color_class='smgt_class_color6';
									}
									elseif($i == 7)
									{
										$color_class='smgt_class_color7';
									}
									elseif($i == 8)
									{
										$color_class='smgt_class_color8';
									}
									elseif($i == 9)
									{
										$color_class='smgt_class_color9';
									}
									?>
									<tr>
										<td class="user_image width_50px profile_image_prescription">	
											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/tax.png"?>" alt="" class="massage_image center image_icon_height_25px margin_top_3px">
											</p>
										</td>
										<td ><?php if($retrieved_data->tax_title){ echo $retrieved_data->tax_title; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Tax Title','school-mgt');?>" ></i></td>
										<td><?php if($retrieved_data->tax_value){ echo $retrieved_data->tax_value; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Tax Value(%)','school-mgt');?>" ></i></td>
										<td><?php if($retrieved_data->created_date){ echo mj_smgt_getdate_in_input_box($retrieved_data->created_date); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Created Date','school-mgt');?>" ></i></td>
										<?php if($user_access['edit'] == '1' || $user_access['delete'] =='1'){?>
										<td class="action">  
											<div class="smgt-user-dropdown">
												<ul class="" style="margin-bottom: 0px !important;">
													<li class="">
														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
														</a>
														<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
															<?php
															if($user_access['edit'] == '1')
															{
																?>
																<li class="float_left_width_100 border_bottom_menu">
																	<a href="?dashboard=user&page=tax&tab=add_tax&action=edit&tax_id=<?php echo $retrieved_data->tax_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																</li>

																<?php 
															} 
															if($user_access['delete'] =='1')
															{
																?>
																<li class="float_left_width_100 ">
																	<a href="?dashboard=user&page=tax&tab=tax&action=delete&tax_id=<?php echo $retrieved_data->tax_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
																	<i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>
																</li>
																<?php
															}
															?>
															
														</ul>
													</li>
												</ul>
											</div>	
										</td>
										<?php 
									$i++;
									}?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</form>
				</div><!------------- TABLE RESPONSIVE ------------------>
			</div><!------------- PENAL BODY ----------------->
			<?php
		}
		else
		{
			if($user_access['add']=='1')
			{
				?>
				<div class="no_data_list_div no_data_img_mt_30px"> 
					<a href="<?php echo home_url().'?dashboard=user&page=tax&tab=add_tax';?>">
						<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
					</a>
					<div class="col-md-12 dashboard_btn margin_top_20px">
						<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
					</div> 
				</div>		
				<?php
			}
			else
			{
				?>
				<div class="calendar-event-new"> 
					<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
				</div>	
				<?php
			}
		}
	} 

	//------------- ACTIVE TAB ADD Tax FORM ----------------------//
	if($active_tab == 'add_tax')
	{
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$taxdata= $obj_tax->MJ_smgt_get_single_tax($_REQUEST['tax_id']);

		} 
		?>
		<div class="panel-body"><!-------- penal body -------->
			<form name="tax_form" action="" method="post" class="form-horizontal" id="tax_form"><!------- form Start --------->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="tax_id" value="<?php if($edit){ echo esc_attr($_REQUEST['tax_id']);}?>"  />
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Tax Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form">
					<div class="row">	
						<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="tax_title" class="form-control validate[required,custom[popup_category_validation]]" maxlength="30" type="text" value="<?php if($edit){ echo $taxdata->tax_title;}?>" name="tax_title">
								<label for="userinput1" class=""><?php esc_html_e('Tax Name','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="tax" class="form-control validate[required,custom[number]] text-input" onkeypress="if(this.value.length==6) return false;" step="0.01" type="number" value="<?php if($edit){ echo esc_attr($taxdata->tax_value);}elseif(isset($_POST['tax_value'])) echo esc_attr($_POST['tax_value']);?>" name="tax_value" min="0" max="100">
									<label class="" for="date"><?php esc_html_e('Tax Value','gym_mgt');?>(%)<span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<?php wp_nonce_field( 'save_tax_admin_nonce' ); ?>	
					</div>
				</div>
				<div class="form-body user_form">
					<div class="row">	
						<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12">        	
							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Tax','school-mgt'); }else{ esc_attr_e('Add Tax','school-mgt');}?>" name="save_tax" class="save_btn" />
						</div> 
					</div>        
				</div>               
			</form> <!------- form end --------->
		</div><!-------- penal body -------->
	<?php
	}
	?>
</div> <!-------------- PENAL BODY ------------>
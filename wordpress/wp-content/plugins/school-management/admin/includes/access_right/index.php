<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'Student';
?>
<!-- View Popup Code start -->	
<div class="popup-bg">
    <div class="overlay-content">
    	<div class="notice_content"></div>    
    </div> 
</div>	
<!-- View Popup Code end -->
	
<div class="page-inner access-right"><!-- page inner div start-->

	<!-- Page Title div start -->
	<!-- <div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div> -->
	<!-- Page Title div end -->
	<!--  main-wrapper div start  -->
	<div class="main_list_margin_15px notice_page font_size_access">
		
		<div class="row"><!-- Row start-->
			<div class="col-md-12 padding_0"><!-- col-md-12 start-->

				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist"><!--Start nav-tabs -->
					<li class="<?php if($active_tab=='Student'){?>active<?php }?>">
						<a href="?page=smgt_access_right&tab=Student" class="padding_left_0 tab <?php echo $active_tab == 'Student' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Student', 'school-mgt'); ?></a>
					</li>
					<li class="<?php if($active_tab=='Teacher'){?>active<?php }?>">
						<a href="?page=smgt_access_right&tab=Teacher" class="padding_left_0 tab <?php echo $active_tab == 'Teacher' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Teacher', 'school-mgt'); ?></a> 
					</li>
					<li class="<?php if($active_tab=='Support_staff'){?>active<?php }?>">
						<a href="?page=smgt_access_right&tab=Support_staff" class="padding_left_0 tab <?php echo $active_tab == 'Support_staff' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Support Staff', 'school-mgt'); ?></a> 
					</li>
					<li class="<?php if($active_tab=='Parent'){?>active<?php }?>">
						<a href="?page=smgt_access_right&tab=Parent" class="padding_left_0 tab <?php echo $active_tab == 'Parent' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Parent', 'school-mgt'); ?></a> 
					</li>
					<li class="<?php if($active_tab=='Management'){?>active<?php }?>">
						<a href="?page=smgt_access_right&tab=Management" class="padding_left_0 tab <?php echo $active_tab == 'Management' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_attr__('Management', 'school-mgt'); ?></a>
					</li>
					
				</ul><!--End nav-tabs -->

				<div class="clearfix"></div>
				<?php
				if($active_tab == 'Student')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/student.php';					
				 }
				 
				 elseif($active_tab == 'Teacher')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/teacher.php';
				 }
				 
				 elseif($active_tab == 'Parent')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/parent.php';
				 }
				 elseif($active_tab == 'Support_staff')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/support_staff.php';
				 }
				 elseif($active_tab == 'Management')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/management.php';
				 }
				 ?> 
			</div><!-- col-md-12 start -->
	 	</div><!-- Row start -->
	</div><!--  main-wrapper div end -->
</div><!-- page inner div end -->

<?php ?>
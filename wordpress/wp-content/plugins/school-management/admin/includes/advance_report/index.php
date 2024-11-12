<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();
$role=mj_smgt_get_user_role(get_current_user_id());
if($role == 'administrator')
{
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('report');
	$user_access_view=$user_access['view'];
	
	if (isset ( $_REQUEST ['page'] ))
	{	
		if($user_access_view=='0')
		{	
			mj_smgt_access_right_page_not_access_message_admin_side();
			die;
		}
	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'student_information_report';
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchbuilder/1.3.0/css/searchBuilder.dataTables.min.css">
<!-- DataTables SearchBuilder JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/searchbuilder/1.3.0/js/dataTables.searchBuilder.min.js"></script>
<!-- DataTables SearchBuilder Bootstrap 4 CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchbuilder/1.3.0/css/searchBuilder.bootstrap4.min.css">
<!-- DataTables Buttons Bootstrap 4 CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">

<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js"></script>
<div class="page-inner"><!-- penal Inner --->
	<div class=" transport_list main_list_margin_5px" id=""> 
		<div class="panel-white"> <!-- penal White --->
			<div class="panel-body"> <!-- penal-body --->
			<?php 
				if($active_tab == 'student_information_report')
				{
					$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'student_report'; 
					?>
					<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
						<li class="<?php if($active_tab=='student_report'){?>active<?php }?>">			
							<a href="?page=smgt_advance_report&tab=student_information_report&tab1=student_report" class="padding_left_0 tab <?php echo $active_tab == 'student_report' ? 'active' : ''; ?>">
							<?php esc_html_e('Student Report', 'school-mgt'); ?></a> 
						</li>
						<li class="<?php if($active_tab=='guardian_report'){?>active<?php }?>">			
							<a href="?page=smgt_advance_report&tab=student_information_report&tab1=guardian_report" class="padding_left_0 tab <?php echo $active_tab == 'guardian_report' ? 'active' : ''; ?>">
							<?php esc_html_e('Guardian Report', 'school-mgt'); ?></a> 
						</li>
						<li class="<?php if($active_tab=='admission_report'){?>active<?php }?>">			
							<a href="?page=smgt_advance_report&tab=student_information_report&tab1=admission_report" class="padding_left_0 tab <?php echo $active_tab == 'admission_report' ? 'active' : ''; ?>">
							<?php esc_html_e('Admission Report', 'school-mgt'); ?></a> 
						</li>
					</ul>
					<div class="clearfix panel-body">
						<?php
							if(isset($active_tab) && $active_tab == 'student_report')
							{ 				
								require_once SMS_PLUGIN_DIR.'/admin/includes/advance_report/student_report.php';
							} 
							if(isset($active_tab) && $active_tab == 'guardian_report')
							{ 				
								require_once SMS_PLUGIN_DIR.'/admin/includes/advance_report/guardian_report.php';
							} 
							if(isset($active_tab) && $active_tab == 'admission_report')
							{ 				
								require_once SMS_PLUGIN_DIR.'/admin/includes/advance_report/admission_report.php';
							} 
						?>
					</div>
					<?php
				}
			?>
			</div>
		</div>
	</div>
</div>

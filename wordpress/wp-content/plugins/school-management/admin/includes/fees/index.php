<?php
	$obj_fees= new Smgt_fees();
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{	
		if(isset($_REQUEST['fees_id']))
		{
			$result=$obj_fees->mj_smgt_delete_feetype_data($_REQUEST['fees_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_fees&tab=feeslist&message=3');
			}
		}	
	}

	
	if(isset($_POST['save_feetype']))
	{			
		if($_REQUEST['action']=='edit')
		{	
			$result=$obj_fees->mj_smgt_add_fees($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_fees&tab=feeslist&message=2');
			}
		}
		else
		{
			if(!$obj_fees->mj_smgt_is_duplicat_fees($_POST['fees_title_id'],$_POST['class_id']))
			{
				$result=$obj_fees->mj_smgt_add_fees($_POST);				
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=smgt_fees&tab=feeslist&message=1');
				}
			}
			else
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_fees&tab=feeslist&message=4');
			}
		}			
	}
	
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1){ ?>
			<div id="message" class="updated below-h2 ">
				<p><?php esc_attr_e('Record Added successfully','school-mgt'); ?></p></div>
		<?php 				
			}
			elseif($message == 2){ ?>
				<div id="message" class="updated below-h2 "><p>
					<?php	esc_attr_e("Record updated successfully",'school-mgt');	?></p>
				</div>
			<?php 
			}
			elseif($message == 3) { ?>
				<div id="message" class="updated below-h2">
				<p><?php esc_attr_e('Record deleted successfully','school-mgt');?></p></div>
			<?php
			}
			elseif($message == 4) { ?>
			<div id="message" class="updated below-h2">
				<p><?php esc_attr_e('Fee type All Ready Exist','school-mgt');?></p></div>
			<?php
					
			}
		}	

$active_tab = isset($_GET['tab'])?$_GET['tab']:'feeslist';
	?>
	<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="invoice_data"></div>		 
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div  id="main-wrapper" class=" payment_list"> 
	<div class="panel panel-white">
	<div class="panel-body">     
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_fees&tab=feeslist" class="nav-tab <?php echo $active_tab == 'feeslist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Fees Type List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'addfeetype')
		{?>
       <a href="?page=smgt_fees&tab=addfeetype&action=edit&fees_id=<?php echo $_REQUEST['fees_id'];?>" class="nav-tab <?php echo $active_tab == 'addfeetype' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Fees Type', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    		<a href="?page=smgt_fees&tab=addfeetype" class="nav-tab <?php echo $active_tab == 'addfeetype' ? 'nav-tab-active' : ''; ?>">
			<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Fee Type', 'school-mgt'); ?></a>  
        <?php 
		} ?>
        
      
    </h2>
    <?php	
	if($active_tab == 'feeslist')
	{	
		$retrieve_class = $obj_fees->mj_smgt_get_all_fees();			
	?>
	<div class="panel-body">
        <div class="table-responsive">
        <table id="example" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>                
                <th><?php esc_attr_e('Fee Type','school-mgt');?></th>                
                <th><?php esc_attr_e('Class','school-mgt');?> </th>              
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>                
                <th><?php esc_attr_e('Action','school-mgt');?></th>             
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th><?php esc_attr_e('Fee Type','school-mgt');?></th>                
                <th><?php esc_attr_e('Class','school-mgt');?> </th>              
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>                
                <th><?php esc_attr_e('Action','school-mgt');?></th>         
            </tr>
        </tfoot>
 
        <tbody>
          <?php 			
		 	foreach ($retrieve_class as $retrieved_data){ 			
			?>
            <tr>
				<td><?php echo get_the_title($retrieved_data->fees_title_id);?></td>
				<td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?></td>
				<td><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . $retrieved_data->fees_amount;?></td>
				<td><?php echo $retrieved_data->description;?></td>
                <td>
                    <a href="?page=smgt_fees&tab=addfeetype&action=edit&fees_id=<?php echo $retrieved_data->fees_id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
					<a href="?page=smgt_fees&tab=feeslist&action=delete&fees_id=<?php echo $retrieved_data->fees_id;?>" class="btn btn-danger"
					onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a></td>
            </tr>
            <?php } ?>     
        </tbody>        
        </table>
       </div></div>
     <?php 
	}
	if($active_tab == 'addfeetype')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/fees/add_feetype.php';		
	 }	 
	 ?>
	 </div>
	 </div>
	 </div>
</div>
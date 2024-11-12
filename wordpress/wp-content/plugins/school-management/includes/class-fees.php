<?php 
class Smgt_fees
{	
	
	public function mj_smgt_get_all_feetype()
	{
		$args= array('post_type'=> 'smgt_feetype','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );		
		return $result;		
	}
	public function mj_smgt_add_feetype($data)
	{
		global $wpdb;
		$result = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'smgt_feetype',
						'post_title' => mj_smgt_popup_category_validation($data['category_name'])) );
		
			return $result;			
	}
	public function mj_smgt_delete_fee_type($cat_id)
	{
		$result=wp_delete_post($cat_id);
		
		return $result;
	}
	public function mj_smgt_is_duplicat_fees($fee_type_id,$class_id)
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
	
		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees where fees_title_id = $fee_type_id AND class_id = $class_id");
		if(!empty($result))
			return true;
		else
			return false;
		
	}
	public function mj_smgt_add_fees($data)
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
		//-------usersmeta table data--------------
		$feedata['fees_title_id']=mj_smgt_onlyNumberSp_validation($data['fees_title_id']);
		$feedata['class_id']=mj_smgt_onlyNumberSp_validation($_POST['class_id']);
		$feedata['section_id']=mj_smgt_onlyNumberSp_validation($_POST['class_section']);
		$feedata['fees_amount']=$_POST['fees_amount'];
		$feedata['description']=mj_smgt_address_description_validation(stripslashes($_POST['description']));		
		$feedata['created_date']=date("Y-m-d H:i:s");
		$feedata['created_by']=get_current_user_id();
	
	
		if($data['action']=='edit')
		{
			
			$fees_id['fees_id']=$data['fees_id'];
			$result=$wpdb->update( $table_smgt_fees, $feedata ,$fees_id);
			$fee_type = get_the_title($feedata['fees_title_id']);
			school_append_audit_log(''.esc_html__('Fees Type Updated','hospital_mgt').'('.$fee_type.')'.'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);
			return $result;
		}
		else
		{
			
			$result=$wpdb->insert( $table_smgt_fees, $feedata );
			$fee_type = get_the_title($feedata['fees_title_id']);
			school_append_audit_log(''.esc_html__('Fees Type Added','hospital_mgt').'('.$fee_type.')'.'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
			return $result;
		}
	}
	public function mj_smgt_get_all_fees()
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
	
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees ORDER BY created_date DESC");
		return $result;
	}
	public function mj_smgt_get_own_fees($user_id)
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
	
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees where created_by=".$user_id);
		return $result;
	}
	public function mj_smgt_get_single_feetype_data($fees_id)
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
	
		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees where fees_id = $fees_id ");
		return $result;
	}
	public function mj_smgt_get_single_feetype_data_amount($fees_id)
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
		// $event = $wpdb->get_row("SELECT * FROM $table_name where event_id=$id");
		$result = $wpdb->get_row("SELECT fees_amount FROM $table_smgt_fees where fees_id = $fees_id ");
		if(!empty($result)){
			return $result->fees_amount;
		}
	}
		
	public function mj_smgt_delete_feetype_data($fees_id)
	{
		
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
		$fee_type = $wpdb->get_row("SELECT * FROM $table_smgt_fees where fees_id=$fees_id");
		$fee = get_the_title($fee_type->fees_title_id);

		school_append_audit_log(''.esc_html__('Fees Type Deleted','hospital_mgt').'('.$fee.')'.'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);
		$result = $wpdb->query("DELETE FROM $table_smgt_fees where fees_id= ".$fees_id);
		return $result;
	}
}
?>
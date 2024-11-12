<?php
class tax_Manage
{
	public function mj_smgt_insert_tax($data)
	{
		global $wpdb;

		$table_name = $wpdb->prefix.'mj_smgt_taxes';

		$taxdata['tax_title']=mj_smgt_strip_tags_and_stripslashes(sanitize_text_field($data['tax_title']));

		$taxdata['tax_value']=sanitize_text_field($data['tax_value']);

		$taxdata['created_date']=date("Y-m-d");

		if(isset($data['action']) && $data['action'] == 'edit')
		{
			$whereid['tax_id']=$data['tax_id'];
			$result=$wpdb->update( $table_name, $taxdata ,$whereid);
			$result = 1;
		}
		else{
			$result=$wpdb->insert( $table_name,$taxdata);	
		}
		return $result;	
	} 

	 // GET All TAXES
	 public function MJ_smgt_get_all_tax()
	 {
		 global $wpdb;
		 $table_name = $wpdb->prefix. 'mj_smgt_taxes';
		 $result = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_date DESC");
		 return $result;
	 }

	 // GET SINGLE TAX
	 public function MJ_smgt_get_single_tax($tax_id)
	 {
		 global $wpdb;
		 $table_name = $wpdb->prefix. 'mj_smgt_taxes';
		 $result = $wpdb->get_row("SELECT * FROM $table_name where tax_id=".$tax_id);
		 return $result;
	 }
	 //------------ DELETE TAX -----------//
	public function mj_smgt_delete_tax($id)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix. 'mj_smgt_taxes';
		$result = $wpdb->query("DELETE FROM $table_name where tax_id= ".$id);
		return $result;
	}
}
?>
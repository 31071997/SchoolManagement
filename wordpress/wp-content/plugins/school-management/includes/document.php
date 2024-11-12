<?php
class smgt_document
{	
        //ADD DOCUMENT FUNCTION
	public function mj_smgt_add_document($data,$document_data)
	{ 
      	global $wpdb;
		$table_name = $wpdb->prefix. 'smgt_document';
		
		if($data['action']=='edit')
		{ 
			school_append_audit_log(''.esc_html__('Update Document Detail','hospital_mgt').'',null,get_current_user_id(),'edit',$_REQUEST['page']);
            $documentdata['class_id']=$data['class_id'];
            $documentdata['section_id']=$data['class_section'];
            $documentdata['student_id']=$data['selected_users'];
            $documentdata['document_content']=json_encode($document_data);
            $documentdata['description']=$data['description'];
            $documentdata['createdby']=get_current_user_id();
            $documentdata['created_date']=date('Y-m-d');
			
			$whereid['document_id']=$data['document_id'];
			$result=$wpdb->update( $table_name, $documentdata ,$whereid);
			return $result;
		}
		else
		{
			school_append_audit_log(''.esc_html__('Add New Document Detail','hospital_mgt').'',null,get_current_user_id(),'insert',$_REQUEST['page']);
            $documentdata['class_id']=$data['class_id'];
            $documentdata['section_id']=$data['class_section'];
            $documentdata['student_id']=$data['selected_users'];
            $documentdata['document_content']=json_encode($document_data);
            $documentdata['description']=$data['description'];
            $documentdata['createdby']=get_current_user_id();
            $documentdata['created_date']=date('Y-m-d');
            $result=$wpdb->insert( $table_name, $documentdata );
			return $result;
		}
	
	}
	//GET ALL DOCUMENT FUNCTION
	public function mj_smgt_get_all_documents()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'smgt_document';
		$result = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_date DESC");
		return $result;
	}
	//GET ALL DOCUMENT FUNCTION
	public function mj_smgt_get_own_documents($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'smgt_document';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where student_id=$user_id OR createdby=$user_id ORDER BY created_date DESC");
		return $result;
	
	}
    
	//GET SINGLE DOCUMENT FUNCTION
	public function mj_smgt_get_single_document($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'smgt_document';
		$result = $wpdb->get_row("SELECT * FROM $table_name where document_id=".$id);
		return $result;
	}

	// DELETE DOCUMENTS
	public function mj_smgt_delete_document($id)
	{
		school_append_audit_log(''.esc_html__('Delete Document Detail','hospital_mgt').'',null,get_current_user_id(),'delete',$_REQUEST['page']);
		global $wpdb;
		$table_name = $wpdb->prefix. 'smgt_document';
		$result = $wpdb->query("DELETE FROM $table_name where document_id= ".$id);
       
		return $result;
	}

}
?>
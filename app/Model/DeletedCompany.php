<?php
class DeletedCompany extends AppModel{
	var $name = 'DeletedCompany';

	function deltededcompany_record($inputdata,$company_id=SES_ID,$name='',$email='',$type=0){
		$tot_user = ClassRegistry::init('CompanyUser')->find('count',array('conditions'=>array('company_id'=>$company_id,'is_active!=3')));
		$dcompany['company_name'] = CMP_SITE;
		$dcompany['owner_name'] = $name ;
		$dcompany['email'] = $email;
		$dcompany['start_date'] = CMP_CREATED;
		$dcompany['end_date'] = GMT_DATETIME;
		$dcompany['no_of_user'] = $tot_user;
		$dcompany['reason'] = $inputdata['cancel_reason'];
		$dcompany['comment'] = $inputdata['comments'];
		$dcompany['type'] = $type;
		$dcompany['cancel_type'] = $inputdata['is_delete'];
		$dcompany['created'] = GMT_DATETIME;
		$dcompany['ip'] = $_SERVER['REMOTE_ADDR'];
		if($this->save($dcompany)){
			return true;
		}else{
			return false;
		}
		
		
	}
}
?>
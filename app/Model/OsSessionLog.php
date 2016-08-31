<?php
class OsSessionLog extends AppModel{
	var $name = 'OsSessionLog';
	function getUserDetls($uid){
		$rec = $this->find('first',array('conditions'=>array('OsSessionLog.user_id'=>$uid))); 	
		if(!empty($rec)){
			$rec['OsSessionLog']['user_agent'] = json_decode($rec['OsSessionLog']['user_agent'],true);
			return $rec;
		}else{
		return false;
		}
	}
}
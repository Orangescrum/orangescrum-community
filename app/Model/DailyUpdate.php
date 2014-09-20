<?php
class DailyUpdate extends AppModel{
	var $name = 'DailyUpdate';
	function getDailyUpdateFields($project_id = NULL,$fields = array()) {
	    if(isset($project_id)) {
		$this->recursive = -1;
		return $this->find('first',array('conditions'=>array('DailyUpdate.project_id'=>$project_id,'DailyUpdate.company_id'=>SES_COMP),'fields'=>$fields));
	    }
	}
}
?>

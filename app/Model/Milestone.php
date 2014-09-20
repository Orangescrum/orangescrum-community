<?php
class Milestone extends AppModel{
	var $name = 'Milestone';
	
	function getMilestone($project_id){
	    return $this->find('list',array('conditions'=>array('Milestone.project_id'=>$project_id,'Milestone.user_id'=>SES_ID,'Milestone.company_id'=>SES_COMP),'fields'=>array('id','title'),'order'=>array('end_date ASC,title ASC')));
	}
	
	public function beforeSave($options = array()) {
		
		if(trim($this->data['Milestone']['title'])) {
			$this->data['Milestone']['title'] = htmlentities(strip_tags($this->data['Milestone']['title']));
		}
		if(trim($this->data['Milestone']['description'])) {
			$this->data['Milestone']['description'] = htmlentities(strip_tags($this->data['Milestone']['description']));
		}
	}
}
?>

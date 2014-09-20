<?php
class CaseTemplate extends AppModel{
	var $name = 'CaseTemplate';
	
	function getCaseTemplateFields($condition = array(), $fields = array()) {
	    $this->recursive = -1;
	    return $this->find('first',array('conditions'=>$condition,'fields'=>$fields));
	}
}
?>
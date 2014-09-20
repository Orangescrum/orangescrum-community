<?php
class Help extends AppModel{
    var $name = 'Help';
    
	function getAllHelps($params)
	{
		$allData = $this->find('all', array('conditions'=>array('Help.subject_id'=>$params)));
		return $allData;
	}
	function searchResults($conditions)
	{
		$searchdata = $this->find("all", array('conditions'=>$conditions));
		return $searchdata;
	}
}
?>
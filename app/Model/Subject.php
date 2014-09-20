<?php
class Subject extends AppModel{
    var $name = 'Subject';
    
	function getAllSubjects($limit=NULL)
	{
		$allData = $this->find('all');
		return $allData;
	}
	
	function subjectName($params)
	{
		$subjectname = $this->find('first', array('conditions'=>array('Subject.id'=>$params), 'fields'=>array('Subject.subject_name')));
		return $subjectname['Subject']['subject_name'];
	}
}
?>
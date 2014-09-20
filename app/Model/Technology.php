<?php
class Technology extends AppModel{
	var $name = 'Technology';
	
	var $hasMany = array(
					'ProjectTechnology' => array(
						'className' => 'ProjectTechnology',
						'foreignKey' => 'technology_id'
						)
					); 
}
?>
<?php
class ProjectTechnology extends AppModel{
	var $name = 'ProjectTechnology';
	
	var $belongsTo = array('Technology' =>
						array('className'     => 'Technology',
						'foreignKey'    => 'technology_id'
						)
					);
}
?>
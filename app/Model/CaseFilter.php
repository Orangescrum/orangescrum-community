<?php
class CaseFilter extends AppModel{
	var $name = 'CaseFilter';
	
	var $belongsTo = array('User' =>
						array('className'     => 'User',
						'foreignKey'    => 'user_id'
						)
					);
}
?>
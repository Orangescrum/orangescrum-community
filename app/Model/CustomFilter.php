<?php
class CustomFilter extends AppModel{
	var $name = 'CustomFilter';
	
	var $belongsTo = array('User' =>
						array('className'     => 'User',
						'foreignKey'    => 'user_id'
						)
					);
}
?>

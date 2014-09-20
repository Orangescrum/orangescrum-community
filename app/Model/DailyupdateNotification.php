<?php
class DailyupdateNotification extends AppModel{
	var $name = 'DailyupdateNotification';
	public $belongsTo = array('User' =>
		array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);
}
?>

<?php
class EmailReminder extends AppModel{
	public $name = 'EmailReminder';
	function getEmailReminderFields($condition = array(), $fields = array()) {
	    $this->recursive = -1;
	    return $this->find('first',array('conditions'=>$condition,'fields'=>$fields));
	}
	
	function getEmailReminder($user_id = NULL, $email_type = NULL) {
	    $EmailReminder = $this->getEmailReminderFields(array('EmailReminder.user_id' => $user_id,'EmailReminder.email_type' => $email_type));
	    $is_email = 1;
	    if(isset($EmailReminder) && !empty($EmailReminder)){
		if(gmdate('Y-m-d') == $EmailReminder['EmailReminder']['cron_date']){
		    $is_email = 0;
		}
	    }
	    
	    $return['is_email'] = $is_email;
	    $return['emailReminderId'] = $EmailReminder['EmailReminder']['id'];
	    return $return;
	}
	
	function saveEmailReminder($id = NULL, $user_id = NULL, $email_type = NULL) {
	    $this->id = '';
	    if(intval($id)){
		$this->id = $id;
	    }
	    $reminder['user_id'] = $user_id;
	    $reminder['email_type'] = $email_type;
	    $reminder['cron_date'] = gmdate('Y-m-d');
	    $this->save($reminder);
	}
}
?>

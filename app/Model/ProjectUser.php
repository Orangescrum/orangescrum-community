<?php

class ProjectUser extends AppModel {

    public $name = 'ProjectUser';
    public $belongsTo = array('Project' =>
	array('className' => 'Project',
	    'foreignKey' => 'project_id'
	),
	'User' =>
	array('className' => 'User',
	    'foreignKey' => 'user_id'
	)
    );

    /**
     * This method gets the user's detail
     * 
     * @author Orangescrum
     * @method getAllNotifyUser
     * @params number, array, string
     * @return array of user's detail
     */

    function getAllNotifyUser($project_id = NULL, $emailUser = array(), $type = 'case_status') {
		if (isset($project_id) && isset($type)) {
			$this->recursive = -1;
			$fld = $type;
			if ($type == 'new' || $type == 'reply')
			$fld = $type . "_case";
			$users = $this->query("SELECT DISTINCT User.id, User.name, User.email, UserNotification.{$fld} FROM users AS User, project_users AS ProjectUser, user_notifications AS UserNotification, company_users as CompanyUser WHERE User.id=ProjectUser.user_id AND User.id=UserNotification.user_id AND User.id=CompanyUser.user_id AND User.isactive='1' AND CompanyUser.is_active='1' AND ProjectUser.project_id='" . $project_id . "' AND ProjectUser.company_id = '" . SES_COMP . "' AND ProjectUser.default_email='1'");
			foreach ($users as $key => $value) {
				if (($value['UserNotification'][$fld] == 1) || (in_array($value['User']['id'], $emailUser))) {
					$usrDtls[]['User'] = $value['User'];
				}
			}
			return $usrDtls;
		}
    }

	function getProjectMembers($projId = NULL) {
	if(isset($projId)) {
	    return $this->query("SELECT User.id, User.uniq_id, User.name FROM users AS User, company_users AS CompanyUser,project_users AS ProjectUser WHERE User.id=CompanyUser.user_id AND User.id=ProjectUser.user_id AND ProjectUser.project_id='".$projId."' AND CompanyUser.company_id='".SES_COMP."' AND CompanyUser.is_active ='1' ORDER BY User.name ASC");
	}
    }
	
	function getAllProjectsForUsers()
	{
		$data = $this->query("select `prj`.`uniq_id`, `prj`.`name` from `projects` as `prj`, `project_users` as `prju` where `prju`.`user_id`='".SES_ID."' and `prj`.`company_id`='".SES_COMP."' and `prju`.`project_id`=`prj`.`id` and `prj`.`isactive`=1 order by `prj`.`name` ASC");
		//echo SES_ID;echo count($data);echo "<pre>";print_r($data);exit;
		return $data;
	}
}

?>
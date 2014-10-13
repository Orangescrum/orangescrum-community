<?php
class Project extends AppModel{
	public $name = 'Project';
	//var $actsAs = array('Global');

	public $hasAndBelongsToMany = array(
        'User' =>
            array(
                'className'              => 'User',
                'joinTable'              => 'project_users',
                'foreignKey'             => 'project_id',
                'associationForeignKey'  => 'user_id'
            )
    );
	
	var $hasMany = array('ProjectUser' =>
						array('className'     => 'ProjectUser',
							'foreignKey'    => 'project_id'
						)
					);

	function getProjectFields($condition = array(), $fields = array()) {
	    $this->recursive = -1;
	    return $this->find('first',array('conditions'=>$condition,'fields'=>$fields));
	}
	public function beforeSave($options = array()) {
		if(trim($this->data['Project']['name'])) {
			$this->data['Project']['name'] = htmlentities(strip_tags($this->data['Project']['name']));
		}
		if(trim($this->data['Project']['short_name'])) {
			$this->data['Project']['short_name'] = htmlentities(strip_tags($this->data['Project']['short_name']));
		}
	}
	
	function getAllProjects(){
	    $this->recursive = -1;

if(PAGE_NAME == "groupupdatealerts") {
	$orderby = "ORDER BY Project.name ASC";
}
else {
	$orderby = "ORDER BY ProjectUser.dt_visited DESC";
}

	    $sql = "SELECT DISTINCT Project.name,Project.uniq_id FROM projects AS Project,
		project_users AS ProjectUser WHERE Project.id = ProjectUser.project_id AND ProjectUser.user_id='".SES_ID."'
		    and ProjectUser.company_id='".SES_COMP."' AND Project.isactive='1' AND Project.name !='' ".$orderby;
	    $projects = $this->query($sql);
	    $allProject = array();
	    if(isset($projects) && !empty($projects)) {
		foreach($projects as $project) {
		    $allProject[$project['Project']['uniq_id']] = $project['Project']['name'];
		}
	    }
	    return $allProject;
	}
/**
 * @method public deleteprojects(string $projuid) Deleting project and all associated data from project
 
 * @return array 
 */
	function deleteprojects($projuid){
		$this->recursive=-1;
		$proj = $this->find('first',array('conditions'=>array('Project.uniq_id'=>$projuid,'Project.company_id'=>SES_COMP)));
		if($proj){
			$prjid = $proj['Project']['id'];
			// Milestone table record deletion
			$milestone_cls = ClassRegistry::init('Milestone');
			$milestone_cls->recursive = -1;
			$milestone_cls->deleteAll(array('project_id'=>$prjid));
			
			/*//Ganttchart table data deletion
			$gntchart_cls = ClassRegistry::init('Ganttchart');
			$gntchart_cls->recursive = -1;
			$gntchart_cls->deleteAll(array('project_id'=>$prjid));*/
			
			//Easycase Milestone tbl
			$easycasemilestone_cls = ClassRegistry::init('EasycaseMilestone');
			$easycasemilestone_cls->recursive = -1;
			$easycasemilestone_cls->deleteAll(array('project_id'=>$prjid));
			
			//Easycase tbl data deletion
			$easycase_cls = ClassRegistry::init('Easycase');
			$easycase_cls->recursive = -1;
			$easycase_cls->deleteAll(array('project_id'=>$prjid));
			
			//Daily update tbl data deletion
			$dupdate_cls = ClassRegistry::init('DailyUpdate');
			$dupdate_cls->recursive = -1;
			$dupdate_cls->deleteAll(array('project_id'=>$prjid));
			
			//Custom filter update tbl data deletion
			$cfilter_cls = ClassRegistry::init('CustomFilter');
			$cfilter_cls->recursive = -1;
			$cfilter_cls->deleteAll(array('project_uniq_id'=>$projuid));
			//Case User View tbl data deletion
			$cuview_cls = ClassRegistry::init('CaseUserView');
			$cuview_cls->recursive = -1;
			$cuview_cls->deleteAll(array('project_id'=>$prjid));
			//Case Recent tbl data deletion
			$caserecent_cls = ClassRegistry::init('CaseRecent');
			$caserecent_cls->recursive = -1;
			$caserecent_cls->deleteAll(array('project_id'=>$prjid));
			//Case File Drive tbl data deletion
			$cfdrive_cls = ClassRegistry::init('CaseFileDrive');
			$cfdrive_cls->recursive = -1;
			$cfdrive_cls->deleteAll(array('project_id'=>$prjid));
			//Case File tbl data deletion
			$casefile_cls = ClassRegistry::init('CaseFile');
			$casefile_cls->recursive = -1;
			$case_files_list = $casefile_cls->find('list',array('conditions'=>array('company_id'=>SES_COMP,'downloadurl IS NULL','project_id'=>$prjid),'fields'=>array('id','file')));
			$casefile_cls->deleteAll(array('project_id'=>$prjid));
			//Removing all the files from S3 Bucket
			foreach ($case_files_list AS $k=>$v){
				$photo = $v;
				$s3 = new S3(awsAccessKey, awsSecretKey);
				$s3->deleteObject(BUCKET_NAME, DIR_CASE_FILES_S3_FOLDER.$photo);
			}
			
			//Case Activity tbl data deletion
			$caseactivity_cls = ClassRegistry::init('CaseActivity');
			$caseactivity_cls->recursive = -1;
			$caseactivity_cls->deleteAll(array('project_id'=>$prjid));
			//Project User tbl data deletion
			$projectuser_cls = ClassRegistry::init('ProjectUser');
			$projectuser_cls->recursive = -1;
			$projectuser_cls->deleteAll(array('project_id'=>$prjid));
			//Project tbl data deletion
			if($this->delete($prjid,false)){
				$arr['succ'] = 1;
				$arr['msg'] = 'Project deleted successfully';;
			}
			
		}else{
			$arr['error'] = 1;
			$arr['msg'] = 'Oops! No project found with the given id.';
		}
		return $arr;
	}
}
?>

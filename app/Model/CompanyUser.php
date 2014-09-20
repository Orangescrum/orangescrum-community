<?php
class CompanyUser extends AppModel{
	var $name = 'CompanyUser';
	
/*	var $belongsTo = array('Company' =>
						array('className'     => 'Company',
						'foreignKey'    => 'company_id'
						),
						'User' =>
						array('className'     => 'User',
						'foreignKey'    => 'user_id'
						)
					);*/	
/**
 * @method private delte_company(int $comp_id) Delete all the company data
 * @return bool True/False
 */	
	function delete_company($comp_id){ 
		// List of distinct user which have no connection with other account
		$del_user = $this->query('SELECT t1.user_id,t1.company_id FROM (SELECT count(user_id) as cnt,user_id , company_id  FROM company_users GROUP BY user_id) AS t1  WHERE t1.company_id ='.$comp_id.' AND t1.cnt=1 ORDER BY `t1`.`user_id` ASC');
		foreach ($del_user AS $key=>$val){
			$user_list[]=$val['t1']['user_id'];
		}
		if($user_list){
			$user = ClassRegistry::init('User');
			$email_list = $user->find('list',array('conditions'=>array('id'=>$user_list),'fields'=>array('id','email')));
			if($email_list){
				$betauser = ClassRegistry::init('BetaUser');
				$betauser->deleteAll(array('email'=>$email_list));
			}
			$user->recursive=-1;
			//$user->deleteAll(array('id'=>$user_list));
			$user->deleteAll(array('FIND_IN_SET(User.id,"'.implode(',',$user_list).'")'));
			
			//User Notification tbl data
			$usernotification = ClassRegistry::init('UserNotification');
			$usernotification->recursive=-1;
			$usernotification->deleteAll(array('user_id'=>$user_list));
			//User Invitation  tbl data
			$userinvt = ClassRegistry::init('UserInvitation');
			$userinvt->recursive=-1;
			$userinvt->deleteAll(array('company_id'=>$comp_id));
		}
		// List of projects associated with this company
		$project_cls = ClassRegistry::init('Project');
		$project_cls->recursive=-1;
		$project_list = $project_cls->find('list',array('conditions'=>array('Project.company_id'=>$comp_id),'fields'=>array('id')));
		if($project_list){
			// Deleting records from various table 
			// Getting the list of case ids 
			$easycase_cls = ClassRegistry::init('Easycase');
			$easycase_cls->recursive=-1;
			$easycase_list = $easycase_cls->find('list',array('conditions'=>array('project_id'=>$project_list),'fields'=>array('id')));
			//Getting the list of files to be removed from the S3 bucket
			
			//Case activity tbl
			$case_activity = ClassRegistry::init('CaseActivity');
			$case_activity->recursive=-1;
			$case_activity->deleteAll(array('project_id'=>$project_list));
			// Case User views data 
 			$caseuserview = ClassRegistry::init('CaseUserView');
			$caseuserview->recursive=-1;
			$caseuserview->deleteAll(array('project_id'=>$project_list));
			// Case user email 
 			$caseuseremail = ClassRegistry::init('CaseUserEmail');
			$caseuseremail->recursive=-1;
			$caseuseremail->deleteAll(array('easycase_id'=>$easycase_list));
			// Easycase table data i.e cases
 			$easycase_cls->deleteAll(array('project_id'=>$project_list));
			//Easycase Milestone tbl data
			$easycasemilestone = ClassRegistry::init('EasycaseMilestone');
			$easycasemilestone->recursive=-1;
			$easycasemilestone->deleteAll(array('project_id'=>$project_list));
		}
			
			//Daily update  tbl data
			$dailyupdate = ClassRegistry::init('DailyUpdate');
			$dailyupdate->recursive=-1;
			$dailyupdate->deleteAll(array('company_id'=>$comp_id));
			
			//Template Module Cases tbl data
			$tmpmodcase = ClassRegistry::init('TemplateModuleCase');
			$tmpmodcase->recursive=-1;
			$tmpmodcase->deleteAll(array('company_id'=>$comp_id));
			//Project User tbl data
			$projuser = ClassRegistry::init('ProjectUser');
			$projuser->recursive=-1;
			$projuser->deleteAll(array('ProjectUser.company_id'=>$comp_id));
			//Project Template cases  tbl data
			$projtempcase = ClassRegistry::init('ProjectTemplateCase');
			$projtempcase->recursive=-1;
			$projtempcase->deleteAll(array('company_id'=>$comp_id));
			//Project Template tbl data
			$projtemp = ClassRegistry::init('ProjectTemplate');
			$projtemp->recursive=-1;
			$projtemp->deleteAll(array('company_id'=>$comp_id));
			//Milestone tbl data
			$milestone = ClassRegistry::init('Milestone');
			$milestone->recursive=-1;
			$milestone->deleteAll(array('company_id'=>$comp_id));
			//Custome Filter tbl data
			$cfilter = ClassRegistry::init('CustomFilter');
			$cfilter->recursive=-1;
			$cfilter->deleteAll(array('company_id'=>$comp_id));
			//Case Template tbl data
			$ctemplate = ClassRegistry::init('CaseTemplate');
			$ctemplate->recursive=-1;
			$ctemplate->deleteAll(array('company_id'=>$comp_id));
			//Case Recent tbl data
			$caserecent = ClassRegistry::init('CaseRecent');
			$caserecent->recursive=-1;
			$caserecent->deleteAll(array('company_id'=>$comp_id));
			//Projects tbl data
			$project_cls->deleteAll(array('company_id'=>$comp_id));
			//Log Activity tbl data
			$logactivity = ClassRegistry::init('LogActivity');
			$logactivity->recursive=-1;
			$logactivity->deleteAll(array('company_id'=>$comp_id));
			//Transaction tbl data
			$trans= ClassRegistry::init('Transaction');
			$trans->recursive=-1;
			$trans->deleteAll(array('company_id'=>$comp_id));
			//User Subscriptions tbl data
			$usersub= ClassRegistry::init('UserSubscription');
			$usersub->recursive=-1;
			$usersub->deleteAll(array('company_id'=>$comp_id));
			//Company user tbl data
			$this->recursive=-1;
			$this->deleteAll(array('company_id'=>$comp_id));
			//User Subscriptions tbl data
			$comp= ClassRegistry::init('Company');
			$comp->recursive=-1;
			$comp->deleteAll(array('id'=>$comp_id));
			
			$case_files = ClassRegistry::init('CaseFile');
			$case_files_list = $case_files->find('list',array('conditions'=>array('company_id'=>$comp_id,'downloadurl IS NULL'),'fields'=>array('id','file')));
						
			// Case files table 
			$case_files->recursive=-1;
			$case_files->deleteAll(array('company_id'=>$comp_id));
			
			//Removing all the files from S3 Bucket
			foreach ($case_files_list AS $k=>$v){
				$photo = $v;
				$s3 = new S3(awsAccessKey, awsSecretKey);
				//$info = $s3->getObjectInfo(BUCKET_NAME, DIR_USER_PHOTOS_S3_FOLDER.$photo);
				//if($photo){
					 $s3->deleteObject(BUCKET_NAME, DIR_CASE_FILES_S3_FOLDER.$photo);
				//}
			}
	
		
	}
	
}
?>

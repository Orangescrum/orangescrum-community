<?php
class CasequeryHelper extends AppHelper {

	function getAllCaseMilestone($mstid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		
		$caseCount = $Easycase->query("SELECT COUNT(Easycase.id) as totcase FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone WHERE EasycaseMilestone.easycase_id=Easycase.id AND Easycase.istype='1' AND Easycase.isactive='1' AND EasycaseMilestone.milestone_id='$mstid'");
		return $caseCount[0][0]['totcase'];
	}
	function getAllCaseIdsFromM($mstid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		
		$allCases = $Easycase->query("SELECT Easycase.id as id FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone WHERE EasycaseMilestone.easycase_id=Easycase.id AND Easycase.istype='1' AND Easycase.isactive='1' AND EasycaseMilestone.milestone_id='$mstid'");
		
		$caseIds = array();
		if(count($allCases)>0){
			foreach($allCases as $allCase){
				array_push($caseIds,$allCase['Easycase']['id']);
			}
		}else{

		}
		return $caseIds;
	}
	function getMilestoneName($caseid)
	{
		$Milestone = ClassRegistry::init('Milestone');
		$Milestone->recursive = -1;
		
		$milestones = $Milestone->query("SELECT Milestone.title as title FROM milestones as Milestone,easycase_milestones AS EasycaseMilestone WHERE EasycaseMilestone.milestone_id=Milestone.id AND EasycaseMilestone.easycase_id='".$caseid."'");
		if(isset($milestones['0']['Milestone']['title']) && $milestones['0']['Milestone']['title']) {
			return $milestones['0']['Milestone']['title'];
		}
		else {
			return false;
		}
	}
	function getAllClosedCaseMilestone($mstid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		
		$caseCount = $Easycase->query("SELECT COUNT(Easycase.id) as totcase FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone WHERE EasycaseMilestone.easycase_id=Easycase.id AND Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND EasycaseMilestone.milestone_id='$mstid'");
		return $caseCount[0][0]['totcase'];
	}
	
	function getAllCases($cid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		
		$easycases = $Easycase->find('all', array('conditions' => array('Easycase.id' => $cid)));
		return $easycases[0];
	}
	function getAllComments($repid)
	{
		App::import('Model','CaseComment'); $CaseComment = new CaseComment();
		$CaseComment->recursive = -1;
		$comments = $CaseComment->find('all', array('conditions' => array('CaseComment.easycase_id' => $repid,'CaseComment.isactive' => 1),'order' => array('CaseComment.dt_created DESC')));
		return $comments;
	}
	function getComments($comntid)
	{
		App::import('Model','CaseComment'); $CaseComment = new CaseComment();
		$CaseComment->recursive = -1;
		$cmnt = $CaseComment->find('first', array('conditions' => array('CaseComment.id' => $comntid,'CaseComment.isactive' => 1),'fields' => array('comments')));
		return $cmnt['CaseComment']['comments'];
	}
	function getCaseTitle($cid,$typ,$case_no,$project_id)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		if($typ == 1) {
			$post = $Easycase->find('first', array('conditions' => array('Easycase.id' => $cid,'Easycase.isactive' => 1,'Easycase.title !=' =>''),'fields' => array('title')));
			return $post['Easycase']['title'];
		}
		else {
			$post = $Easycase->find('first', array('conditions' => array('Easycase.id' => $cid,'Easycase.isactive' => 1),'fields' => array('message')));
			if($post['Easycase']['message']) {
				return $post['Easycase']['message'];
			}
			else {
				$getTitle = $Easycase->find('first', array('conditions' => array('Easycase.case_no' => $case_no,'Easycase.project_id' => $project_id,'Easycase.isactive' => 1,'Easycase.title !=' =>''),'fields' => array('title')));
				return $getTitle['Easycase']['title'];
				
			}
		}
	}
	function getTaskTitle($cid,$typ,$case_no,$project_id)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		if($typ == 2) {
			$getTitle = $Easycase->find('first', array('conditions' => array('Easycase.case_no' => $case_no,'Easycase.project_id' => $project_id,'Easycase.isactive' => 1,'Easycase.case_count !=' => 0),'fields' => array('title')));
			return $getTitle['Easycase']['title'];
		}
		else {
			$post = $Easycase->find('first', array('conditions' => array('Easycase.id' => $cid,'Easycase.isactive' => 1),'fields' => array('title')));
			if($post['Easycase']['title']) {
				return $post['Easycase']['title'];
			}			
		}
	}
	function getCaseUniqId($cno,$pid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$uniqid = $Easycase->find('first', array('conditions' => array('Easycase.case_no' => $cno,'Easycase.project_id' => $pid,'Easycase.istype' => 1,'Easycase.isactive' => 1),'fields' => array('uniq_id')));
		return $uniqid['Easycase']['uniq_id'];
	}
	function getProjUniqId($pid)
	{
		$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$uniqid = $Project->find('first', array('conditions' => array('Project.id' => $pid,'Project.isactive' => 1,'Project.company_id' => SES_COMP),'fields' => array('uniq_id')));
		return $uniqid['Project']['uniq_id'];
	}
	function getallInvitedProj($pid)
	{
	    if($pid) {
		$project_id = explode(",", $pid);
		if(!empty($project_id)) {
		    $qry = '';
		    $cnt = 1;
		    
		    foreach ($project_id as $key => $value) {
			if(count($project_id) == $cnt) {
			    $qry = $qry."Project.id = '".$value."'";
			} else {
			    $qry = $qry."Project.id = '".$value."' OR ";
			}
			$cnt++;
		    }
		    $sql = "SELECT DISTINCT Project.name FROM projects AS Project WHERE (".$qry.") AND Project.company_id='".SES_COMP."' ORDER BY Project.name";
		    $Project = ClassRegistry::init('Project');
		    $Project->recursive = -1;
		    $getProj = $Project->query($sql);
		    $allpj = "";
		    if(!empty($getProj)) {
			foreach ($getProj as $k => $v) {
			    $allpj = $allpj . ", " . ucwords(strtolower($v['Project']['name']));
			}
			$allpj = trim($allpj, ",");
			return $allpj;
		    } else {
			return "";
		    }
		} else {
		    return "";
		}
	    } else {
		return "";
	    }
		/*$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$getProj = $Project->find('first', array('conditions' => array('Project.id' => $pid),'fields' => array('Project.name')));
		if($getProj['Project']['name']) {
			return ucwords(strtolower($getProj['Project']['name']));
		}
		else {
			return "";
		}*/
	}
	function getUserEmail($id)
	{
		$CaseUserEmail = ClassRegistry::init('CaseUserEmail');
		$CaseUserEmail->recursive = -1;
		$userIds = $CaseUserEmail->find('all', array('conditions'=>array('CaseUserEmail.easycase_id' => $id,'CaseUserEmail.ismail'=>1), 'fields'=>array('CaseUserEmail.user_id')));
		return $userIds;
	}
	function casePostId($cno)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$caseid = $Easycase->find('first', array('conditions'=>array('Easycase.case_no' => $cno,'Easycase.istype'=>1), 'fields'=>array('Easycase.uniq_id')));
		return $caseid;
	}
	function getProjectShortName($pid)
	{
		$shortName = "";
		$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$pjArr = $Project->find('first', array('conditions'=>array('Project.id' => $pid,'Project.isactive'=>1,'Project.company_id' => SES_COMP), 'fields'=>array('Project.short_name','Project.uniq_id')));
		return $pjArr;
	}
	function getProjectName($pid)
	{
		$shortName = "";
		$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$pjArr = $Project->find('first', array('conditions'=>array('Project.id' => $pid,'Project.isactive'=>1,'Project.company_id' => SES_COMP), 'fields'=>array('Project.name','Project.uniq_id')));
		return $pjArr;
	}
function getProjectNameByUniqid($puid)
	{
          if($puid != "all"){
		     $Project = ClassRegistry::init('Project');
		     $Project->recursive = -1;
		     $pjArr = $Project->find('first', array('conditions'=>array('Project.uniq_id' => $puid,'Project.isactive'=>1,'Project.company_id' => SES_COMP), 'fields'=>array('Project.name')));
		     return $pjArr['Project']['name'] ;
          }else{
               $pjArr['Project']['name'] = 'All';
               return $pjArr['Project']['name'];
          }
	}
	function getCaseNotification($cid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$allcase = $Easycase->find('first', array('conditions'=>array('Easycase.id' => $cid,'Easycase.isactive'=>1), 'fields'=>array('Easycase.uniq_id','Easycase.case_no','Easycase.project_id','Easycase.user_id','Easycase.title')));
		return $allcase;
	}
	function caseViewData($pid,$type)
	{
		if($type == "new")
		{
			$CaseUserView = ClassRegistry::init('CaseUserView');
			$caseMsg = $CaseUserView->find('count', array('conditions' => array('CaseUserView.user_id'=>SES_ID,'CaseUserView.project_id'=>$pid, 'CaseUserView.istype'=>1, 'CaseUserView.isviewed'=>0),'fields' => 'DISTINCT CaseUserView.id'));
		
			return $caseMsg;
		}
	}
	function caseBcMems($uid)
	{
		$User = ClassRegistry::init('User');
		$User->recursive = -1;
		$usrDtls = $User->find('first', array('conditions'=>array('User.id' => $uid,'User.isactive' => 1), 'fields'=>array('User.short_name')));
		return $usrDtls['User']['short_name'];
	}
	function caseProject($pid)
	{
		$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$pjDtls = $Project->find('first', array('conditions'=>array('Project.id' => $pid,'Project.isactive' => 1,'Project.company_id' => SES_COMP), 'fields'=>array('Project.short_name','Project.name','Project.uniq_id')));
		return $pjDtls;
	}
	function caseBcTypes($typ)
	{
		if(strlen($typ) == 2 && $typ == 01)
		{
			$typ = 10;
		}
		$Type = ClassRegistry::init('Type');
		$cstype = $Type->find('first', array('conditions'=>array('Type.id' => $typ), 'fields'=>array('Type.short_name')));
		return $cstype['Type']['short_name'];
	}
	function getUserDtls($uid)
	{
		$User = ClassRegistry::init('User');
		$User->recursive = -1;
		$usrDtls = $User->find('first', array('conditions' => array('User.id' => $uid,'User.isactive' => 1),'fields' => array('User.name','User.istype','User.email','User.short_name','User.photo','User.last_name')));
		
		return $usrDtls;
	}
	function getUserDtlsArr($uid,$usrDtlsArr = array())
	{
		if(isset($usrDtlsArr[$uid])) {
			return $usrDtlsArr[$uid];
		}
		else {
			echo "";
		}
	}
	function getCaseFiles($cid)
	{
		App::import('Model','CaseFile'); $CaseFile = new CaseFile();
		$CaseFile->recursive = -1;
		$caseFiles = $CaseFile->find('all', array('conditions'=>array('CaseFile.easycase_id' => $cid,'CaseFile.comment_id' => 0,'CaseFile.isactive' => 1), 'fields'=>array('CaseFile.file','CaseFile.file_size'), 'order' => array('CaseFile.file ASC')));
		return $caseFiles;
	}
	function countCaseFiles($allcsId)
	{
		$caseFiles = 0;
		App::import('Model','CaseFile'); $CaseFile = new CaseFile();
		$CaseFile->recursive = -1;
		$caseFiles = $CaseFile->find('count', array('conditions'=>array('CaseFile.easycase_id' => $allcsId,'CaseFile.isactive' => 1), 'fields'=>'CaseFile.id'));
		return $caseFiles;
	}
	function getCommentFiles($cmnt)
	{
		App::import('Model','CaseFile'); $CaseFile = new CaseFile();
		$CaseFile->recursive = -1;
		$caseFiles = $CaseFile->find('all', array('conditions'=>array('CaseFile.comment_id' => $cmnt,'CaseFile.isactive' => 1), 'fields'=>array('CaseFile.file','CaseFile.file_size'), 'order' => array('CaseFile.file ASC')));
		return $caseFiles;
	}
	function checkCaseFile($caseid)
	{
		App::import('Model','CaseFile'); $CaseFile = new CaseFile();
		$CaseFile->recursive = -1;
		$caseFiles = $CaseFile->find('count', array('conditions'=>array('CaseFile.easycase_id' => $caseid,'CaseFile.comment_id !=' => 0,'CaseFile.isactive' => 1), 'fields' => 'DISTINCT CaseFile.id'));
		return $caseFiles;
	}
	function getType($typid)
	{
		$Type = ClassRegistry::init('Type');
		$cstype = $Type->find('first', array('conditions'=>array('Type.id' => $typid), 'fields'=>array('Type.name','Type.short_name')));
		return $cstype;
	}
	function getTypeArr($typid,$cstypeArr)
	{
		//$Type = ClassRegistry::init('Type');
		//$cstype = $Type->find('first', array('conditions'=>array('Type.id' => $typid), 'fields'=>array('Type.name','Type.short_name')));
		//return $cstypeArr[$typid];
		$return = NULL;
		foreach($cstypeArr as $type) {
		    if($type['Type']['id'] == $typid) {
			$return = $type;
		    }
		}
		return $return;
	}
	function getLastCase($cno, $pid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$lastcase = $Easycase->find('first', array('conditions'=>array('Easycase.case_no' => $cno,'Easycase.project_id' => $pid,'Easycase.isactive'=>1), 'fields'=>array('Easycase.id','Easycase.user_id'), 'order' => array('Easycase.id DESC'), 'limit' => 1));
		return $lastcase;
	}
	function allCaseReply($cno, $pid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$allCase = $Easycase->find('count', array('conditions'=>array('Easycase.case_no' => $cno,'Easycase.project_id' => $pid,'Easycase.isactive'=>1),'fields'=>'DISTINCT Easycase.id'));
		return $allCase;
	}
	function displayCaseNo($pid = NULL,$type = NULL,$id,$filters,$all = NULL)
	{
		$arr = array();
		if($filters == "assigntome" && $all != 'all') {
			$arr = array("OR" => array(
                'AND' => array(
                    'Easycase.isactive'   => 1,
					'Easycase.istype'     => 1,
					'Easycase.project_id' => $pid,
					'Easycase.assign_to' =>SES_ID 
                     ),
                array(
                    'Easycase.isactive'  => 1,
                    'Easycase.istype'    => 1,
                    'Easycase.project_id' => $pid,
                    'Easycase.assign_to' => '0',
					'Easycase.user_id' => SES_ID)));
		}
		if($filters == "latest" && $all != 'all') {
			$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
			$arr = array("AND" => array('Easycase.dt_created >' => $before,'Easycase.dt_created <=' => GMT_DATETIME));
		}
		if($filters == "delegateto" && $all != 'all') {
			$arr = array("Easycase.user_id" => SES_ID, "OR" => array("Easycase.assign_to !=" => 0), "Easycase.assign_to !=" => SES_ID);
		}
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		if($type == "project" && $all == '0')
		{
			$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $pid)));
		}
		elseif($type == "project" && $all == 'all')
		{
			$cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'),'order'=>array('ProjectUser.dt_visited DESC'));
			$ProjectUser = ClassRegistry::init('ProjectUser');
			$ProjectUser->unbindModel(array('belongsTo' => array('User')));
			$allProjArr = $ProjectUser->find('all', $cond);
			$ids = array();
			foreach($allProjArr as $csid)
			{
				array_push($ids,$csid['Project']['id']);
			}
			$total=0;
			for($i=0;$i<count($ids);$i++){
				$Easycase = ClassRegistry::init('Easycase');
				$Easycase->recursive = -1;
				$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids[$i])));
				$total += $totcase;
			}
		$totcase = $total;	
		}
		elseif($type == "type" && $all != 'all')
		{
			$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $pid,'Easycase.type_id'=>$id, $arr)));
		}
		elseif($type == "type" && $all == 'all')
		{
			$cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'),'order'=>array('ProjectUser.dt_visited DESC'));
				$ProjectUser = ClassRegistry::init('ProjectUser');
				$ProjectUser->unbindModel(array('belongsTo' => array('User')));
				$allProjArr = $ProjectUser->find('all', $cond);
				$ids = array();
				foreach($allProjArr as $csid)
				{
					array_push($ids,$csid['Project']['id']);
				}
				$total=0;
				for($i=0;$i<count($ids);$i++){
					$Easycase = ClassRegistry::init('Easycase');
					$Easycase->recursive = -1;
					$arrtyp = array();
				if($filters == "assigntome" && $all == 'all') {
					$arrtyp = array("OR" => array(
				        'AND' => array(
				            'Easycase.isactive'   => 1,
							'Easycase.istype'     => 1,
							'Easycase.project_id' => $ids[$i],
							'Easycase.assign_to' =>SES_ID 
				             ),
				        array(
				            'Easycase.isactive'  => 1,
				            'Easycase.istype'    => 1,
				            'Easycase.project_id' => $ids[$i],
				            'Easycase.assign_to' => '0',
							'Easycase.user_id' => SES_ID)));
				}
				if($filters == "latest" && $all == 'all') {
					/*App::import('Model','User');$User = new User();
					$cond = array('conditions'=>array('User.id' => SES_ID), 'fields' => array('User.dt_last_logout','User.dt_last_login'));
					$res = $User->find('first', $cond);
					$logout_time=$res['User']['dt_last_logout'];
					$login_time=$res['User']['dt_last_login'];*/
					$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
					$arrtyp = array("AND" => array('Easycase.dt_created >' => $before,'Easycase.dt_created <=' => GMT_DATETIME));
				}
				if($filters == "delegateto" && $all == 'all') {
					$arrtyp = array("Easycase.user_id" => SES_ID, "OR" => array("Easycase.assign_to !=" => 0), "Easycase.assign_to !=" => SES_ID);
				}
							
					$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids[$i],'Easycase.type_id'=>$id, $arrtyp)));
					$total += $totcase;
				}
				$totcase = $total;
			

		}
		elseif($type == "priority" && $all == 'all')
		{
			if($id == "High" && $all == 'all')
			{
					$cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'),'order'=>array('ProjectUser.dt_visited DESC'));
				$ProjectUser = ClassRegistry::init('ProjectUser');
				$ProjectUser->unbindModel(array('belongsTo' => array('User')));
				$allProjArr = $ProjectUser->find('all', $cond);
				$ids = array();
				foreach($allProjArr as $csid)
				{
					array_push($ids,$csid['Project']['id']);
				}
				$total=0;
				for($i=0;$i<count($ids);$i++){


				$arr1 = array();
				if($filters == "assigntome" && $all == 'all') {
					$arr1 = array("OR" => array(
				        'AND' => array(
				            'Easycase.isactive'   => 1,
							'Easycase.istype'     => 1,
							'Easycase.project_id' => $ids[$i],
							'Easycase.assign_to' =>SES_ID 
				             ),
				        array(
				            'Easycase.isactive'  => 1,
				            'Easycase.istype'    => 1,
				            'Easycase.project_id' => $ids[$i],
				            'Easycase.assign_to' => '0',
							'Easycase.user_id' => SES_ID)));
				}
				if($filters == "latest") {
					/*App::import('Model','User');$User = new User();
					$cond = array('conditions'=>array('User.id' => SES_ID), 'fields' => array('User.dt_last_logout','User.dt_last_login'));
					$res = $User->find('first', $cond);
					$logout_time=$res['User']['dt_last_logout'];
					$login_time=$res['User']['dt_last_login'];*/
					$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
					$arr1 = array("AND" => array('Easycase.dt_created >' => $before,'Easycase.dt_created <=' => GMT_DATETIME));
				}
				if($filters == "delegateto" && $all == 'all') {
					$arr1 = array("Easycase.user_id" => SES_ID, "OR" => array("Easycase.assign_to !=" => 0), "Easycase.assign_to !=" => SES_ID);
				}

					$Easycase = ClassRegistry::init('Easycase');
					$Easycase->recursive = -1;
					$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids[$i],'Easycase.priority'=>0, $arr1)));
					$total += $totcase;
				}
				$totcase = $total;
			}
			else if($id == "Medium" && $all == 'all')
			{
				$cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'),'order'=>array('ProjectUser.dt_visited DESC'));
				$ProjectUser = ClassRegistry::init('ProjectUser');
				$ProjectUser->unbindModel(array('belongsTo' => array('User')));
				$allProjArr = $ProjectUser->find('all', $cond);
				$ids = array();
				foreach($allProjArr as $csid)
				{
					array_push($ids,$csid['Project']['id']);
				}
				$total=0;
				for($i=0;$i<count($ids);$i++){

					$arr2 = array();
				if($filters == "assigntome" && $all == 'all') {
					$arr2 = array("OR" => array(
				        'AND' => array(
				            'Easycase.isactive'   => 1,
							'Easycase.istype'     => 1,
							'Easycase.project_id' => $ids[$i],
							'Easycase.assign_to' =>SES_ID 
				             ),
				        array(
				            'Easycase.isactive'  => 1,
				            'Easycase.istype'    => 1,
				            'Easycase.project_id' => $ids[$i],
				            'Easycase.assign_to' => '0',
							'Easycase.user_id' => SES_ID)));
				}
				if($filters == "latest") {
						/*App::import('Model','User');$User = new User();
						$cond = array('conditions'=>array('User.id' => SES_ID), 'fields' => array('User.dt_last_logout','User.dt_last_login'));
						$res = $User->find('first', $cond);
						$logout_time=$res['User']['dt_last_logout'];
						$login_time=$res['User']['dt_last_login'];*/
						$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
						$arr2 = array("AND" => array('Easycase.dt_created >' => $before,'Easycase.dt_created <=' => GMT_DATETIME));
					}
				if($filters == "delegateto" && $all == 'all') {
					$arr2 = array("Easycase.user_id" => SES_ID, "OR" => array("Easycase.assign_to !=" => 0), "Easycase.assign_to !=" => SES_ID);
				}



					$Easycase = ClassRegistry::init('Easycase');
					$Easycase->recursive = -1;
					$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids[$i],'Easycase.priority'=>1, $arr2)));
					$total += $totcase;
				}
				$totcase = $total;	
			}
			else
			{
				if($all == 'all'){
				$cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'),'order'=>array('ProjectUser.dt_visited DESC'));
				$ProjectUser = ClassRegistry::init('ProjectUser');
				$ProjectUser->unbindModel(array('belongsTo' => array('User')));
				$allProjArr = $ProjectUser->find('all', $cond);
				$ids = array();
				foreach($allProjArr as $csid)
				{
					array_push($ids,$csid['Project']['id']);
				}
				$total=0;
				for($i=0;$i<count($ids);$i++){
					$arr3 = array();
				if($filters == "assigntome" && $all == 'all') {
					$arr3 = array("OR" => array(
				        'AND' => array(
				            'Easycase.isactive'   => 1,
							'Easycase.istype'     => 1,
							'Easycase.project_id' => $ids[$i],
							'Easycase.assign_to' =>SES_ID 
				             ),
				        array(
				            'Easycase.isactive'  => 1,
				            'Easycase.istype'    => 1,
				            'Easycase.project_id' => $ids[$i],
				            'Easycase.assign_to' => '0',
							'Easycase.user_id' => SES_ID)));
				}
				if($filters == "latest") {
					/*App::import('Model','User');$User = new User();
					$cond = array('conditions'=>array('User.id' => SES_ID), 'fields' => array('User.dt_last_logout','User.dt_last_login'));
					$res = $User->find('first', $cond);
					$logout_time=$res['User']['dt_last_logout'];
					$login_time=$res['User']['dt_last_login'];*/
					$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
					$arr3 = array("AND" => array('Easycase.dt_created >' => $before,'Easycase.dt_created <=' => GMT_DATETIME));
				}
				if($filters == "delegateto" && $all == 'all') {
					$arr3 = array("Easycase.user_id" => SES_ID, "OR" => array("Easycase.assign_to !=" => 0), "Easycase.assign_to !=" => SES_ID);
				}
					$Easycase = ClassRegistry::init('Easycase');
					$Easycase->recursive = -1;
					$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids[$i],'Easycase.priority >='=>2, $arr3)));
					$total += $totcase;
				}
				$totcase = $total;
				
				}
			}
		}
		elseif($type == "priority" && $all != 'all')
		{
			if($id == "High" && $all != 'all')
			{
				$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $pid,'Easycase.priority'=>0, $arr)));
			}
			else if($id == "Medium" && $all != 'all')
			{
				$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $pid,'Easycase.priority'=>1, $arr)));
			}
			else
			{
				if($all != 'all'){
				$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $pid,'Easycase.priority >='=>2, $arr)));
				}
			}
		}
		elseif($type == "member" && $all != 'all')
		{
			$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $pid,'Easycase.user_id'=>$id, $arr)));
		}
		elseif($type == "member" && $all == 'all')
		{
			$cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'),'order'=>array('ProjectUser.dt_visited DESC'));
				$ProjectUser = ClassRegistry::init('ProjectUser');
				$ProjectUser->unbindModel(array('belongsTo' => array('User')));
				$allProjArr = $ProjectUser->find('all', $cond);
				$ids = array();
				foreach($allProjArr as $csid)
				{
					array_push($ids,$csid['Project']['id']);
				}
				$total=0;
				for($i=0;$i<count($ids);$i++){
					$arr3 = array();
				if($filters == "assigntome" && $all == 'all') {
					$arr3 = array("OR" => array(
				        'AND' => array(
				            'Easycase.isactive'   => 1,
							'Easycase.istype'     => 1,
							'Easycase.project_id' => $ids[$i],
							'Easycase.assign_to' =>SES_ID 
				             ),
				        array(
				            'Easycase.isactive'  => 1,
				            'Easycase.istype'    => 1,
				            'Easycase.project_id' => $ids[$i],
				            'Easycase.assign_to' => '0',
							'Easycase.user_id' => SES_ID)));
				}
				if($filters == "latest") {
					/*App::import('Model','User');$User = new User();
					$cond = array('conditions'=>array('User.id' => SES_ID), 'fields' => array('User.dt_last_logout','User.dt_last_login'));
					$res = $User->find('first', $cond);
					$logout_time=$res['User']['dt_last_logout'];
					$login_time=$res['User']['dt_last_login'];*/
					$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
					$arr3 = array("AND" => array('Easycase.dt_created >' => $before,'Easycase.dt_created <' => GMT_DATETIME));
				}
				if($filters == "delegateto" && $all == 'all') {
					$arr3 = array("Easycase.user_id" => SES_ID, "OR" => array("Easycase.assign_to !=" => 0), "Easycase.assign_to !=" => SES_ID);
				}
					$Easycase = ClassRegistry::init('Easycase');
					$Easycase->recursive = -1;
					$totcase = $Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids[$i],'Easycase.user_id'=>$id, $arr3)));
					$total += $totcase;
				}
				$totcase = $total;
		}
		return $totcase;
	}
	function getAllCsId($pid)
	{
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$caseIds = $Easycase->find('all', array('conditions'=>array('Easycase.project_id' => $pid),'fields' => 'id'));
		$ids = array();
		foreach($caseIds as $csid)
		{
			array_push($ids,$csid['Easycase']['id']);
		}
		return $ids;
	}
	function usedSpace($curProjId = NULL,$company_id = SES_COMP)
	{
		//$allTotsizeinMb = 0;
		//return $allTotsizeinMb;
		$CaseFiles = ClassRegistry::init('CaseFiles');
		$this->recursive = -1;
		$cond =" 1 ";
		if($company_id){
			$cond .=" AND company_id=".$company_id;
		}
		if($curProjId){
			$cond .=" AND project_id=".$curProjId;
		}
		$sql = "SELECT SUM(file_size) AS file_size  FROM case_files   WHERE ".$cond;
		$res1 = $CaseFiles->query($sql);
		$filesize = $res1['0']['0']['file_size']/1024;
		return number_format($filesize,2);
		
		/*if(!$company_id) {
			$company_id = SES_COMP;
		}
		
		if($curProjId) {
			$cid = $this->getAllCsId($curProjId);
		}
		else {
			$Project = ClassRegistry::init('Project');
			$Project->recursive = -1;
			
			$curProjId = array();
			
			$allProjIds = $Project->find('all', array('conditions'=>array('Project.company_id' => $company_id),'fields' => array('Project.id')));
			foreach($allProjIds as $pjIds) {
				$curProjId[] = $pjIds['Project']['id'];
			}
			$cid = $this->getAllCsId($curProjId);
		}
		
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$caseSize = $Easycase->find('all', array('conditions'=>array('Easycase.project_id' => $curProjId,'Easycase.isactive' => 1),'fields' => array('SUM(LENGTH(message)) as msg','SUM(LENGTH(title)) as titl')));
		
		App::import('Model','CaseFile'); $CaseFile = new CaseFile();
		$CaseFile->recursive = -1;
		$caseFileSize = $CaseFile->find('all', array('conditions'=>array('CaseFile.easycase_id' => $cid,'CaseFile.isactive' => 1), 'fields'=>array('SUM(file_size) AS filesize','SUM(LENGTH(file)) as filelength')));
		
		$totalsize = $caseSize['0']['0']['msg']+$caseSize['0']['0']['titl']+$caseFileSize['0']['0']['filelength'];
		$totalsizeInKB = $totalsize/1024;
		
		$filesizeInKb = $caseFileSize['0']['0']['filesize'];
		$allTotsizeinKb = $filesizeInKb+$totalsizeInKB;
		$allTotsizeinMb = round($allTotsizeinKb/1024,2);
		
		return $allTotsizeinMb;*/
		
	}
	function fullSpace($used, $totalsize = 1024)
	{
		$full = $used*100/$totalsize;
		$used = round($full,1);
		return $used;
	}
	function fullSpacegrid($used, $totalsize = MAX_SPACE_USAGE)
	{
		$full = $used*100/$totalsize;
		$used = round($full,2);
		return $used;
	}
	function getalluser($pjid){
		$ProjectUser = ClassRegistry::init('ProjectUser');
		$ProjectUser->recursive = -1;
		$userno = $ProjectUser->find('count', array('conditions'=>array('ProjectUser.project_id' => $pjid),'fields' => 'DISTINCT ProjectUser.user_id'));
		return $userno;

	}
	function getlatestactivitypid($pid,$chk=null){
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$latestactivity = $Easycase->find('first', array('conditions'=>array('Easycase.project_id =' => $pid),'fields' =>'dt_created','order' => array('Easycase.dt_created DESC')) );
		if($chk){
		    return $latestactivity['Easycase']['dt_created'];
		}else{
		    $latestactivity1= explode(" ",$latestactivity['Easycase']['dt_created']);
		    return $latestactivity1['0'];
		}

	}
	function getallproject($id){
		$ProjectUser = ClassRegistry::init('ProjectUser');
		$ProjectUser->recursive = -1;
		$caseIds = $ProjectUser->find('all', array('conditions'=>array('ProjectUser.user_id' => $id,'ProjectUser.company_id'=>SES_COMP),'fields' => 'project_id'));
		$ids = array();
		foreach($caseIds as $csid)
		{
			array_push($ids,$csid['ProjectUser']['project_id']);
		}
		//return $ids;
		$userallprj = array();
		foreach($ids as $k=>$v){
			$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$caseIdss = $Project->find('all', array('conditions'=>array('Project.id =' => $v),'fields' => 'name'));
			
		foreach($caseIdss as $cssid)
		{
			array_push($userallprj,$cssid['Project']['name']);
		}
			}
		return $userallprj;

	}
	function getlatestactivity($uid){
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		$latestactivity = $Easycase->find('first', array('conditions'=>array('Easycase.user_id =' => $uid),'fields' =>'dt_created','order' => array('Easycase.dt_created DESC')) );
		
		return $latestactivity;

	}
	function getpjname($pid)
	{
		$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$uniqid = $Project->find('first', array('conditions' => array('Project.id' => $pid,'Project.isactive' => 1),'fields' => array('name','short_name')));
		return $uniqid['Project']['name'];
	}
	function getusrname($uid)
	{
		$User = ClassRegistry::init('User');
		$User->recursive = -1;
		$usrname = $User->find('first', array('conditions' => array('User.id' => $uid,'User.isactive' => 1),'fields' => array('name','short_name')));
		return $usrname;
	}
	function getarccasecount($pid){
		$Easycase = ClassRegistry::init('Easycase');
		if($pid == 'all'){
			$ProjectUser = ClassRegistry::init('ProjectUser');
			$getAllProj = $ProjectUser->find('all',array('conditions'=>array('ProjectUser.user_id'=>SES_ID,'ProjectUser.company_id'=>SES_COMP),'fields'=>'ProjectUser.project_id'));
			
			$qry = '';
			$projIds = array();
			if(!empty($getAllProj)){
				foreach($getAllProj as $pj) {
					$projIds[] = $pj['ProjectUser']['project_id'];
				}
				$getUsers = array();
				if(count($projIds)) {
					$pjids = "(".implode(",",$projIds).")";					
					$qry = "AND Easycase.project_id IN ".$pjids."";
				}
			}
			$caseCount1 = $Easycase->query("SELECT COUNT( DISTINCT Easycase.id) as count FROM easycases as Easycase,archives as Archive WHERE Easycase.id=Archive.easycase_id AND Archive.type = '1' AND Archive.company_id ='".SES_COMP."' ".$qry." AND Easycase.project_id != '0';");
			return $caseCount1['0']['0']['count'];
		}else{
			$caseCount1 = $Easycase->query("SELECT COUNT( DISTINCT Easycase.id) as count FROM easycases as Easycase,archives as Archive WHERE Easycase.id=Archive.easycase_id AND Archive.type = '1' AND Archive.company_id ='".SES_COMP."' AND Easycase.project_id = '".$pid."'");
			return $caseCount1['0']['0']['count'];
		}
	}
	function getarcfilecount($pid){
		$Easycase = ClassRegistry::init('Easycase');
		if($pid == 'all'){
			$ProjectUser = ClassRegistry::init('ProjectUser');
			$getAllProj = $ProjectUser->find('all',array('conditions'=>array('ProjectUser.user_id'=>SES_ID,'ProjectUser.company_id'=>SES_COMP),'fields'=>'ProjectUser.project_id'));
			
			$qry = '';
			$projIds = array();
			if(!empty($getAllProj)){
				foreach($getAllProj as $pj) {
					$projIds[] = $pj['ProjectUser']['project_id'];
				}
				$getUsers = array();
				if(count($projIds)) {
					$pjids = "(".implode(",",$projIds).")";					
					$qry = "AND Easycase.project_id IN ".$pjids."";
				}
			}
			$caseCount1 = $Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase,case_files as CaseFile,archives as Archive WHERE Archive.case_file_id=CaseFile.id AND Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND CaseFile.isactive = '0' AND Archive.type='1' AND Archive.company_id ='".SES_COMP."' ".$qry." AND Easycase.project_id != '0';");
			return $caseCount1['0']['0']['count'];
		}else{
			$caseCount1 = $Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase,case_files as CaseFile,archives as Archive WHERE Archive.case_file_id=CaseFile.id AND Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND CaseFile.isactive = '0' AND Archive.type='1' AND Archive.company_id ='".SES_COMP."' AND Easycase.project_id = '".$pid."';");
			return $caseCount1['0']['0']['count'];
		}
	}
     function getactivitycount($pid){
          $Caseactivity = ClassRegistry::init('CaseActivity');
          if($pid == 'all'){
               $activitycount = $Caseactivity->query("SELECT COUNT(Caseactivity.id) as count FROM case_activities as Caseactivity,projects as Project,project_users as ProjectUser WHERE ProjectUser.project_id=Project.id and  ProjectUser.user_id=".SES_ID." and Project.isactive = 1 and Project.company_id = '".SES_COMP."' and Caseactivity.project_id = Project.id and Caseactivity.isactive='1'");
               return $activitycount['0']['0']['count'];
          }else{
               $activitycount = $Caseactivity->query("SELECT COUNT(Caseactivity.id) as count FROM case_activities as Caseactivity,projects as Project,project_users as ProjectUser WHERE ProjectUser.project_id=Project.id and  ProjectUser.user_id=".SES_ID." and Project.isactive = 1 and Project.company_id = '".SES_COMP."' and Caseactivity.project_id = Project.id and Caseactivity.isactive='1' and Caseactivity.project_id = ".$pid." ");
               return $activitycount['0']['0']['count'];
          }                              
     }
function getarcmilestonecount($pid){
		$Milestone = ClassRegistry::init('Milestone');
		if($pid == 'all'){
               if(SES_TYPE == 1 || SES_TYPE == 2){
                    $milestoneCount = $Milestone->query("SELECT COUNT(Milestone.id) as count FROM milestones as Milestone WHERE Milestone.isactive =0 AND Milestone.company_id ='".SES_COMP."';");
               }else{
                    $milestoneCount = $Milestone->query("SELECT COUNT(Milestone.id) as count FROM milestones as Milestone WHERE Milestone.user_id ='".SES_ID."' AND Milestone.isactive =0 AND Milestone.company_id ='".SES_COMP."';");
               }  
			return $milestoneCount['0']['0']['count'];
		}else{
               if(SES_TYPE == 1 || SES_TYPE == 2){
                     $milestoneCount = $Milestone->query("SELECT COUNT(Milestone.id) as count FROM milestones as Milestone WHERE Milestone.project_id = '".$pid."' AND Milestone.isactive =0 AND Milestone.company_id ='".SES_COMP."';");
               }else{
                     $milestoneCount = $Milestone->query("SELECT COUNT(Milestone.id) as count FROM milestones as Milestone WHERE Milestone.user_id ='".SES_ID."' AND Milestone.project_id = '".$pid."' AND Milestone.isactive =0 AND Milestone.company_id ='".SES_COMP."';");
               }
			return $milestoneCount['0']['0']['count'];
          }
	}
	function gettemplatemodulename($tid){
		App::import('Model','TemplateModule'); $TemplateModule = new TemplateModule();
		$temp_mod = $TemplateModule->find('first',array('conditions' => array('TemplateModule.id' => $tid,'TemplateModule.company_id'=>SES_COMP),'fields'=>array('TemplateModule.module_name')));
		return $temp_mod['TemplateModule']['module_name'];
	}
	function getinviteqstr($cid,$uid){
		App::import('Model','UserInvitation'); $UserInvitation = new UserInvitation();
		$qstr = $UserInvitation->find('first',array('conditions' => array('UserInvitation.user_id' => $uid,'UserInvitation.company_id'=>$cid),'fields'=>array('UserInvitation.qstr')));
		return $qstr['UserInvitation']['qstr'];
	}
	function company_name($cid){
		$comp = ClassRegistry::init('Company')->find('first',array('conditions' => array('Company.id' => $cid),'fields'=>array('Company.name')));
		return $comp['Company']['name'];
	}
	function fullSpacesubscription($used, $company_id)
	{
		$comp = ClassRegistry::init('UserSubscriptions')->find('first',array('conditions' => array('UserSubscriptions.company_id' => $company_id),'fields'=>array('UserSubscriptions.storage'),'order'=>'UserSubscriptions.id DESC'));
		if(strtolower($comp['UserSubscriptions']['storage']) != 'unlimited'){
			$full = $used*100/$comp['UserSubscriptions']['storage'];
			$used = round($full,1);
			return $used.'__'.$comp['UserSubscriptions']['storage'];
		}else{
			return $used.'__'.$comp['UserSubscriptions']['storage'];
		}
	}
	function displaymilestoneNo($pjid){
		$mile=ClassRegistry::init('Milestone')->find('count',array('conditions'=>array('Milestone.project_id'=>$pjid),'fields'=>array('Milestone.id')));
		return $mile;exit;
	}
}
?>

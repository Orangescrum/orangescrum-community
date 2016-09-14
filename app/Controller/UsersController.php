<?php
/*********************************************************************************
 * Orangescrum Community Edition is a web based Project Management software developed by
 * Orangescrum. Copyright (C) 2013-2014
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact Orangescrum, 2059 Camden Ave. #118, San Jose, CA - 95124, US. 
   or at email address support@orangescrum.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * Orangescrum" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by Orangescrum".
 ********************************************************************************/
App::uses('AppController', 'Controller');
App::import('Vendor', 'oauth');
class UsersController extends AppController {
    public $name = 'Users';
	public $components = array('Format','Postcase','Sendgrid','Tmzone','Email','Cookie');
	
	public function beforeFilter(){
		parent::beforeFilter();
	}
	function beforeRender() {
	    if($this->Auth->User("id")) {
		$withOutLoginPage = array('login','license','validate_emailurl','forgotpassword','session_maintain');
		if(in_array($this->action,$withOutLoginPage)){
		    $file = ""; $caseid = "";
		    if(isset($_GET['case'])) {
			    $caseid = $_GET['case'];
		    }
		    if(isset($_GET['project'])) {
			    $projectid = $_GET['project'];
		    }
		    if(isset($_GET['file'])) {
			    $file = $_GET['file'];
		    }
		    if($caseid && $projectid) {
			    $this->redirect(HTTP_ROOT."dashboard/?case=".$caseid."&project=".$projectid);
		    }elseif(!$caseid && $projectid) {
			    $this->redirect(HTTP_ROOT."dashboard/?project=".$projectid);
		    }
		    elseif($file) {
			    $this->redirect(HTTP_ROOT."easycases/download/".$file);
		    }
		    elseif(PAGE_NAME=='tour') {
			// $this->redirect(HTTP_ROOT."easycases/download/".$file);
		    }
		    else {
			    $this->redirect(HTTP_ROOT."dashboard");
		    }
		}
	    }
	}
	//Set global google session
	function setGoogleInfo() {
	    $this->layout='ajax';
	    $_SESSION['CHECK_GOOGLE_SES'] = 1;
	    echo 1;exit;
	}
	function license() {
	  
	}
	function company($img = null) 
	{
		if(SES_TYPE == 3) {
			$this->redirect(HTTP_ROOT."dashboard");
		}
		
		$Company = ClassRegistry::init('Company');
		$Company->recursive = -1;
		
		$photo = urldecode($img);
		$s3 = new S3(awsAccessKey, awsSecretKey);
                $info = $s3->getObjectInfo(BUCKET_NAME, DIR_USER_COMPANY_S3_FOLDER.$photo);
		//if($photo && file_exists(DIR_FILES."company/".$photo))
		if($photo && $info)
		{
			//unlink(DIR_FILES."company/".$photo);
			
			$s3->deleteObject(BUCKET_NAME, DIR_USER_COMPANY_S3_FOLDER.$photo);
			$comp['id'] = SES_ID;
			$comp['logo'] = $photo;
			$Company->save($comp);
			
			$this->Session->write("SUCCESS","Company photo removed successfully");
			$this->redirect(HTTP_ROOT."users/company");
			
		}
		
		if(isset($this->request->data['Company']))
		{
			$photo_name = "";
			if(isset($this->request->data['Company']['photo']))
			{
				//$photo_name = $this->Format->uploadPhoto($this->request->data['Company']['photo']['tmp_name'],$this->request->data['Company']['photo']['name'],$this->request->data['Company']['photo']['size'],DIR_FILES."company/",SES_ID);
				$photo_name = $this->Format->uploadPhoto($this->request->data['Company']['photo']['tmp_name'],$this->request->data['Company']['photo']['name'],$this->request->data['Company']['photo']['size'],DIR_FILES."company/",SES_ID,"cmp_logo");
				if($photo_name == "ext")
				{
					$this->Session->write("ERROR","Company logo should be an image file");
					$this->redirect(HTTP_ROOT."users/company");
				}
				elseif($photo_name == "size")
				{
					$this->Session->write("ERROR","Company logo size cannot excceed 1mb");
					$this->redirect(HTTP_ROOT."users/company");
				}
			}
			if(trim($this->request->data['Company']['name']) == "")
			{
				$this->Session->write("ERROR","Name cannot be left blank");
				$this->redirect(HTTP_ROOT."users/company");
			}
			else
			{
				$this->request->data['Company']['id'] = SES_COMP;
				if(isset($this->request->data['Company']['photo_name']))
				{
					$this->request->data['Company']['logo'] = $this->request->data['Company']['photo_name'];
				}
				else
				{
					$this->request->data['Company']['logo'] = $photo_name;
				}
				
				$Company->save($this->request->data);
				$this->Session->write("SUCCESS","Company updated successfully");
				$this->redirect(HTTP_ROOT."users/company");
			}
		}
		
		$getCompany = $Company->find('first',array('conditions'=>array('Company.id'=>SES_COMP)));
		$this->set('getCompany',$getCompany);
		
	}
	function ajax_check_user_exists(){
		$this->layout='ajax';
		ob_clean();
		$this->User->recursive = -1;		
		if($this->request->data['email'] && $this->request->data['uniq_id']) {
			if(stristr(urldecode($this->request->data['email']),",")){
				$str=""; 
				$CompanyUser = ClassRegistry::init('CompanyUser'); $UserInvitation = ClassRegistry::init('UserInvitation');
				$mail_arr1 = explode(",",urldecode(trim(trim($this->request->data['email']),",")));
				$cnt =0;$mail_arr=array();
				foreach($mail_arr1 AS $key=>$val){
					if(trim($val) != ""){
						$cnt ++;
						$mail_arr[]=$val;
					}
				}
				//Checking limitation of users 
				$totalusers_cnt = $cnt + $GLOBALS['usercount'];
				if((strtolower(trim($GLOBALS['Userlimitation']['user_limit'])) !="unlimited") && $totalusers_cnt> $GLOBALS['Userlimitation']['user_limit']){
					echo "errorlimit";exit;
				}
				
				for($i=0;$i<count($mail_arr);$i++){
					if(trim($mail_arr[$i]) != ""){
						$mail_arr[$i]=trim($mail_arr[$i]);
						
						$checkUsr = $this->User->find('first',array('conditions'=>array('User.email'=>$mail_arr[$i]),'fields'=>array('User.id')));
						$user_id = $checkUsr['User']['id'];
						if($user_id) {
							$ui = $UserInvitation->find('first',array('conditions'=>array('UserInvitation.company_id'=>SES_COMP,'UserInvitation.user_id'=>$user_id),'fields'=>array('UserInvitation.user_id')));
							if($ui['UserInvitation']['user_id']) {
								$str = $mail_arr[$i].",";
								break;
							}else {
								$cu = $CompanyUser->find('first',array('conditions'=>array('CompanyUser.company_id'=>SES_COMP,'CompanyUser.user_id'=>$user_id,'CompanyUser.is_active !=3'),'fields'=>array('CompanyUser.id')));
								if($cu['CompanyUser']['id']) {
									$str = $mail_arr[$i].",";
									break;
								}
							}
						}
						
					}
				}
				$str = trim($str);
				$str = trim($str,",");
				if(trim($str) == ""){
					echo "success";exit;
				}else{
					echo $str;exit;
				}
			}
			else{
				$checkUsr = $this->User->find('first',array('conditions'=>array('User.email'=>urldecode($this->request->data['email'])),'fields'=>array('User.id')));
				$user_id = $checkUsr['User']['id'];
				
				if($user_id) {
					if($user_id == SES_ID) {
						echo "account";
						exit;
					}
					$UserInvitation = ClassRegistry::init('UserInvitation');
					$ui = $UserInvitation->find('first',array('conditions'=>array('UserInvitation.company_id'=>SES_COMP,'UserInvitation.user_id'=>$user_id),'fields'=>array('UserInvitation.id')));
					if($ui['UserInvitation']['id']) {
						echo "invited";
					}
					else {
						$CompanyUser = ClassRegistry::init('CompanyUser');
						$cu = $CompanyUser->find('first',array('conditions'=>array('CompanyUser.company_id'=>SES_COMP,'CompanyUser.user_id'=>$user_id,'CompanyUser.user_type'=>1),'fields'=>array('CompanyUser.id')));
						if($cu['CompanyUser']['id']) {
							echo "owner";
						}
						else {
							$chku = $CompanyUser->find('first',array('conditions'=>array('CompanyUser.company_id'=>SES_COMP,'CompanyUser.user_id'=>$user_id,'CompanyUser.is_active !=3'),'fields'=>array('CompanyUser.id')));
							if($chku['CompanyUser']['id']) {
								echo "exists";
							}
						}
					}
					
				}
			}
		}
		exit;
	}
	function check_email_reg(){
		$this->layout='ajax';
		$this->User->recursive = -1;
		if($this->request->data['email']) {
			$checkUsr = $this->User->find('first',array('conditions'=>array('User.email'=>urldecode($this->request->data['email'])),'fields'=>array('User.id')));
			if($checkUsr['User']['id']) {
				echo $checkUsr['User']['id'];
			}
		}exit;
	}
	function check_short_name_reg()
	{
		$this->layout='ajax';
		
		$this->User->recursive = -1;
		if($this->request->data['short_name']) {
			$checkUsr = $this->User->find('first',array('conditions'=>array('User.short_name'=>urldecode($this->request->data['short_name'])),'fields'=>array('User.id')));
			if($checkUsr['User']['id']) {
				echo $checkUsr['User']['id'];
			}
		}
		exit;
	}
	function confirmation($uniq_id = NULL){
		$chkActivation = $this->User->find('first',array('conditions'=>array('User.query_string'=>$uniq_id)));
		if($chkActivation['User']['id'] && trim($uniq_id)) {
			$usr['User']['id'] = $chkActivation['User']['id'];
			$usr['User']['name'] = $chkActivation['User']['name'];
			$usr['User']['isactive'] = 1;
			//getting company id
			$comp = ClassRegistry::init('CompanyUser')->find('first',array('conditions'=>array('CompanyUser.user_id'=>$chkActivation['User']['id'],'CompanyUser.user_type'=>1),'fields'=>array('CompanyUser.company_id','CompanyUser.user_id')));
			$comp_id=$comp['CompanyUser']['company_id'];//company id
			//Get all template modules data
			$all_pj_temp = ClassRegistry::init('DefaultProjectTemplate')->find('all',array('fields'=>array('DefaultProjectTemplate.id','DefaultProjectTemplate.module_name')));

			$this->loadModel('ProjectTemplateCase');
			$this->loadModel('ProjectTemplate');
			$this->loadModel('Project');
			if($this->User->save($usr)){
				$notification['user_id']=$chkActivation['User']['id'];
				$notification['type']=1;
				$notification['value']=0;
				$notification['due_val']=0;
				ClassRegistry::init('UserNotification')->save($notification);
				
				//Store default task templates to company
				$this->loadModel('DefaultTemplate');
				$this->DefaultTemplate->store_default_to_cstmpl(array($comp_id));
				
			//Event log data and inserted into database in account creation--- Start
				$json_arr['name'] = $chkActivation['User']['name'];
				$json_arr['usersub_type'] = $chkActivation['User']['user_type']?'Paid':'Free';
				$json_arr['date'] = GMT_DATETIME;
				$this->Postcase->eventLog($comp_id,$chkActivation['User']['id'],$json_arr,24);
			//End 
				$first_login =0;
				if($chkActivation['User']['usersub_type']){
					$first_login=1;
				}
				$this->login(NULL,$chkActivation['User']['email'],$chkActivation['User']['password'],$first_login);
			}
		}
		
		$this->redirect(HTTP_ROOT."users/login");
		exit;
	}
	
	function add_default_template($user_id = NULL, $company_id = NULL) {
	    if(trim($user_id) && trim($company_id)) {
		$this->loadModel("CaseTemplate");
		$case_template = $this->CaseTemplate->getCaseTemplateFields(array('CaseTemplate.user_id' => $user_id, 'CaseTemplate.company_id' => $company_id), array('id'));
		if(empty($case_template)){
		    $default_template = Configure::read('default_template');
		    foreach($default_template as $key => $value) {
			$template['user_id'] = $user_id;
			$template['company_id'] = $company_id;
			$template['name'] = $value['name'];
			$template['description'] = $value['description'];
			$template['is_active'] = 1;

			//print '<pre>';print_r($template);exit;
			$this->CaseTemplate->id = '';
			$this->CaseTemplate->save($template);
		    }
		    $return = 1;
		} else 
		    $return = 0;
	    } else
		$return = 0;
	    return $return;
	}
	function email_notification() 
	{
		$UserNotification = ClassRegistry::init('UserNotification');
		
		$getAllNot = $UserNotification->find('first',array('conditions'=>array('UserNotification.user_id'=>SES_ID)));
		$this->set('getAllNot',$getAllNot);
		$DailyupdateNotification = ClassRegistry::init('DailyupdateNotification');		
		$getAllDailyupdateNot = $DailyupdateNotification->find('first',array('conditions'=>array('DailyupdateNotification.user_id'=>SES_ID)));
		$this->set('getAllDailyupdateNot',$getAllDailyupdateNot);
		/*$this->User->recursive = -1;
		$getUsrNot = $this->User->find('first',array('conditions'=>array('User.id'=>SES_ID)));
		$this->set('getUsrNot',$getUsrNot);*/
		//echo "<pre>";print_r($getAllNot);print_r($getAllDailyupdateNot);exit;
		
		if($this->request->data) {
			$this->request->data['User']['id'] = SES_ID;
//			if(ACT_TAB_ID>1 && ($this->data['category_tab']==1)){
//				$this->request->data['User']['active_dashboard_tab']=1;
//				define('ACT_TAB_ID',1);
//			}elseif(ACT_TAB_ID<=1 && ($this->data['category_tab']>1)){
//				$this->request->data['User']['active_dashboard_tab']=15;//Default 4tabs active(Sum of there binary value)
//				define('ACT_TAB_ID',15);
//			}
			if(!isset($this->request->data['User']['desk_notify'])) {
				$this->request->data['User']['desk_notify'] = 0;
			}
			$this->User->save($this->request->data['User']);
		}
		if(isset($this->request->data['UserNotification']))

		{	
			$this->request->data['UserNotification']['user_id'] = SES_ID;
			$this->request->data['UserNotification']['id'] = $getAllNot['UserNotification']['id'];
			$UserNotification->save($this->request->data['UserNotification']);
		}	
		if(isset($this->request->data['DailyupdateNotification']))
		{	
			$data['DailyupdateNotification']['id'] = $getAllDailyupdateNot['DailyupdateNotification']['id'];
			$data['DailyupdateNotification']['user_id'] = SES_ID;
			$data['DailyupdateNotification']['status'] = 0;
			if($this->request->data['DailyupdateNotification']['dly_update'] == 1){
				$data['DailyupdateNotification']['dly_update'] = 1;
				$data['DailyupdateNotification']['notification_time'] = $this->request->data['DailyupdateNotification']['not_hr'].':'. $this->request->data['DailyupdateNotification']['not_mn'];
				$comma_separated = implode(",",$this->request->data['DailyupdateNotification']['proj_name']);
				$data['DailyupdateNotification']['proj_name'] = trim($comma_separated,',');
			}else{
				$data['DailyupdateNotification']['dly_update'] = 0;
				$data['DailyupdateNotification']['notification_time'] = '';
				$data['DailyupdateNotification']['proj_name'] = '';				
			}
			$DailyupdateNotification->save($data['DailyupdateNotification']);
			
			/*$userData['User']['id'] = SES_ID;
			$userData['User']['isemail'] = $this->request->data['User']['isemail'];
			$this->User->save($userData);*/		

			$this->Session->write("SUCCESS","Notification settings saved successfully");
			//$this->redirect(HTTP_ROOT."users/email_notification");
			$this->redirect(HTTP_ROOT."users/email_notifications");

		}		
	}
	function invitation($qstr = NULL)
	{
		$isValid = 0;
		if(trim($qstr)) {
			$isValid = 1;
			if(isset($this->request->data['User'])) {
				if($this->request->data['User']['name'] && $this->request->data['User']['password'] && $this->request->data['User']['qstr']){
					$this->request->data['User']['name'] = trim($this->request->data['User']['name']);
					$this->request->data['User']['last_name'] = trim($this->request->data['User']['last_name']);
					$this->request->data['User']['short_name'] = $this->Format->makeShortName($this->request->data['User']['name'],$this->request->data['User']['last_name']);
					
					$qstr = $this->request->data['User']['qstr'];
					$this->loadModel('Timezone');
				       $getTmz = $this->Timezone->find('first',array('conditions'=>array('Timezone.gmt_offset'=>urldecode($this->request->data['User']['timezone_id']))));
				       $timezone_id = $getTmz['Timezone']['id'];
					$UserInvitation = ClassRegistry::init('UserInvitation');
					$usrInvt = $UserInvitation->find('first',array('conditions'=>array('UserInvitation.qstr'=>$qstr,'UserInvitation.is_active'=>1)));
					
					if($usrInvt['UserInvitation']['user_id']) {
						$this->request->data['User']['id'] = $usrInvt['UserInvitation']['user_id'];
						$this->request->data['User']['password'] = md5($this->request->data['User']['password']);
						$this->request->data['User']['isactive'] = 1;
						$this->request->data['User']['timezone_id'] = $timezone_id;
						$this->request->data['User']['ip'] = $_SERVER['REMOTE_ADDR'];
						 
						$this->User->save($this->request->data);
						$notification['user_id']=$usrInvt['UserInvitation']['user_id'];
						$notification['type']=1;
						$notification['value']=1;
						$notification['due_val']=1;
						ClassRegistry::init('UserNotification')->save($notification);
						$this->redirect(HTTP_ROOT."users/invitation/".$qstr);
					}
				}
			}

			$UserInvitation = ClassRegistry::init('UserInvitation');
			$ui = $UserInvitation->find('first',array('conditions'=>array('UserInvitation.qstr'=>$qstr)));
			if($ui['UserInvitation']['user_id']){
				$Company = ClassRegistry::init('Company');
				$getComp = $Company->find('first',array('conditions'=>array('Company.id'=>$ui['UserInvitation']['company_id'])));
				$getUsr = $this->User->find('first',array('conditions'=>array('User.id'=>$ui['UserInvitation']['user_id'])));
				if($getUsr['User']['id']) {
					if(!$getUsr['User']['password']) {
						$email = $getUsr['User']['email'];
					}else {
						$usrInvt['UserInvitation']['id'] = $ui['UserInvitation']['id'];
						$usrInvt['UserInvitation']['is_active'] = 0;
						$UserInvitation->save($usrInvt);
						if($ui['UserInvitation']['is_active'] == 1) {
							$comp_dtl=ClassRegistry::init('CompanyUser')->find('first',array('conditions'=>array('CompanyUser.user_id'=>$ui['UserInvitation']['user_id'],'CompanyUser.company_id'=>$ui['UserInvitation']['company_id'],'CompanyUser.user_type'=>$ui['UserInvitation']['user_type'],'CompanyUser.is_active'=>2),'fields'=>array('CompanyUser.id')));
							$CompanyUser = ClassRegistry::init('CompanyUser');
							$cmpnyUsr['CompanyUser']['id'] = $comp_dtl['CompanyUser']['id'];
							$cmpnyUsr['CompanyUser']['is_active'] = 1;
							$cmpnyUsr['CompanyUser']['act_date'] = GMT_DATETIME;
							if($CompanyUser->save($cmpnyUsr)){
								//$json_arr = array('activation_date'=>GMT_DATETIME,'desc'=>'User confirmation by clicking on the activation link');
								$comp_user_id = $CompanyUser->getLastInsertID();
								//$this->update_bt_subscription($comp_user_id,$ui['UserInvitation']['company_id'],1);
							}
							//Event log data and inserted into database in account creation--- Start
							$json_arr['email'] = $getUsr['User']['email'];
							$json_arr['name'] = trim($getUsr['User']['name']." ".$getUsr['User']['last_name']);
							$json_arr['created'] = GMT_DATETIME;
							$this->Postcase->eventLog($ui['UserInvitation']['company_id'],$getUsr['User']['id'],$json_arr,26);
						//End 
							if($ui['UserInvitation']['project_id']) {
								$ProjectUser = ClassRegistry::init('ProjectUser');
								$ProjectUser->recursive = -1;
								$getLastId = $ProjectUser->query("SELECT MAX(id) as maxid FROM project_users");
								$nextid = $getLastId[0][0]['maxid']+1;
								if(strstr($ui['UserInvitation']['project_id'],',')){
									$projectids = explode(',', $ui['UserInvitation']['project_id']);
								}else{
									$projectids[]=$ui['UserInvitation']['project_id'];
								}
								foreach($projectids as $key=>$val){
									if(trim($val)){
										$projUsr['ProjectUser']['id'] = $nextid;
										$projUsr['ProjectUser']['user_id'] = $ui['UserInvitation']['user_id'];
										$projUsr['ProjectUser']['project_id'] = trim($val);
										$projUsr['ProjectUser']['company_id'] = $ui['UserInvitation']['company_id'];
										$projUsr['ProjectUser']['dt_visited'] = GMT_DATETIME;
										$ProjectUser->create();
										$ProjectUser->save($projUsr);
									}
								}
							}
							
							$usr['User']['id'] = $ui['UserInvitation']['user_id'];
							$usr['User']['isactive'] = 1;
							//$this->User->save($usr);
							$this->User->query("UPDATE users set isactive='1' where id='".$usr['User']['id']."'");
							if(defined('SES_ID') && (SES_ID != $ui['UserInvitation']['user_id'])){
								$this->Auth->logout();
								$this->redirect(HTTP_ROOT.'users/logout/'.$ui['UserInvitation']['user_id']);exit;
							}else{
								$this->login(NULL,$getUsr['User']['email'],$getUsr['User']['password']);
							}
						}
						$this->redirect(HTTP_APP);
						//$this->redirect(HTTP_ROOT);
						//$this->login(NULL,$getUsr['User']['email'],$getUsr['User']['password']);
					}
				}
				else {
					$isValid = 0;
				}
			}
			else {
				$isValid = 0;
			}
			$this->set('AuthId',$this->Auth->User("id"));
			$this->set('email',$email);
			$this->set('qstr',$qstr);
			$this->set('company_name',$getComp['Company']['name']);
		}
		if(!$isValid) {
			$this->redirect(HTTP_APP);
		}
	}
    function manage() {
                $this->set('istype',SES_TYPE);
                $search_key=$this->request->query['user_srch'];
                $search_query="User.name LIKE '%$search_key%' OR User.last_name LIKE '%$search_key%' OR User.email  LIKE '%$search_key%' OR User.short_name  LIKE '%$search_key%'";
		$page_limit = CASE_PAGE_LIMIT;
		$page_limit = 26;
		$CompanyUser = ClassRegistry::init('CompanyUser');

		if (isset($_GET['del']) && trim(urldecode($_GET['del'])) != "") {
			$del = urldecode($_GET['del']);
			$del = addslashes($del);
			$getUsr = $this->User->find('first', array('conditions' => array('User.uniq_id' => $del), 'fields' => array('User.id', 'User.email', 'User.name', 'User.last_name')));
			$CompanyUser->deleteAll(array('user_id' => $getUsr['User']['id'], 'company_id' => SES_COMP, 'user_type!=1'));
			$UserInvitation = ClassRegistry::init('UserInvitation');
			$UserInvitation->query("DELETE FROM user_invitations WHERE user_id='" . $getUsr['User']['id'] . "' AND company_id='" . SES_COMP . "'");
			$invit = $UserInvitation->find('first', array('conditions' => array('UserInvitation.user_id' => $getUsr['User']['id'])));
			
			//Event log data and inserted into database in account creation--- Start
			$json_arr['email'] = $getUsr['User']['email'];
			$json_arr['name'] = trim($getUsr['User']['first_name'] . " " . $getUsr['User']['last_name']);
			$json_arr['created'] = GMT_DATETIME;
			$this->Postcase->eventLog(SES_COMP, SES_ID, $json_arr, 3);
			//End 
			$this->Session->write("SUCCESS", "user deleted successfully");
			$this->redirect(HTTP_ROOT . "users/manage/?role=invited");
		}
		if (isset($_GET['act']) && trim(urldecode($_GET['act'])) != "") {
			if (($GLOBALS['Userlimitation']['subscription_id'] == 1) && (strtolower($GLOBALS['Userlimitation']['user_limit']) != "unlimited")) {
			if ($GLOBALS['usercount'] >= $GLOBALS['Userlimitation']['user_limit']) {
				$this->Session->write("ERROR", "Sorry! User cannot be enabled. User Limit Exceeded!");
				$this->redirect(HTTP_ROOT . "users/manage/?type=1&role=" . $_GET['role']);
				exit;
			}
			}
			$act = urldecode($_GET['act']);
			$act = addslashes($act);
			$getUsr = $this->User->find('first', array('conditions' => array('User.uniq_id' => $act), 'fields' => array('User.id', 'User.email', 'User.name', 'User.last_name')));
			//Below code are written for the subscription i.e in case a disabled user get activated during a subscribed period	
			$comp_user = $CompanyUser->find('first', array('conditions' => array('user_id' => $getUsr['User']['id'], 'company_id' => SES_COMP)));
			if ($GLOBALS['Userlimitation']['btsubscription_id']) {
			if (strtotime($comp_user['CompanyUser']['billing_end_date']) < strtotime($GLOBALS['Userlimitation']['next_billing_date'])) {
				//$this->update_bt_subscription($comp_user['CompanyUser']['id'], $comp_user['CompanyUser']['company_id'], 2);
			}
			}
			$CompanyUser->query("UPDATE company_users as CompanyUser SET CompanyUser.is_active='1' WHERE CompanyUser.user_id='" . $getUsr['User']['id'] . "' AND CompanyUser.company_id='" . SES_COMP . "' AND CompanyUser.user_type!='1'");
			//Event log data and inserted into database in account creation--- Start
			$json_arr['email'] = $getUsr['User']['email'];
			$json_arr['name'] = trim($getUsr['User']['first_name'] . " " . $getUsr['User']['last_name']);
			$json_arr['created'] = GMT_DATETIME;
			$this->Postcase->eventLog(SES_COMP, SES_ID, $json_arr, 28);
			//End 
			$this->Session->write("SUCCESS", "User enabled successfully");
			$this->redirect(HTTP_ROOT . "users/manage/?role=" . $_GET['role']);
		}
		if (isset($_GET['deact']) && trim(urldecode($_GET['deact'])) != "") {
			$deact = urldecode($_GET['deact']);
			$deact = addslashes($deact);
			$getUsr = $this->User->find('first', array('conditions' => array('User.uniq_id' => $deact), 'fields' => array('User.id', 'User.email', 'User.name', 'User.last_name')));
			$CompanyUser->query("UPDATE company_users as CompanyUser SET CompanyUser.is_active='0' WHERE CompanyUser.user_id='" . $getUsr['User']['id'] . "' AND CompanyUser.company_id='" . SES_COMP . "' AND CompanyUser.user_type!='1'");
			//Event log data and inserted into database in account creation--- Start
			$json_arr['email'] = $getUsr['User']['email'];
			$json_arr['name'] = $getUsr['User']['first_name'] . " " . $getUsr['User']['last_name'];
			$json_arr['created'] = GMT_DATETIME;
			$this->Postcase->eventLog(SES_COMP, SES_ID, $json_arr, 27);
			//End 
			$this->Session->write("SUCCESS", "User disabled successfully");
			$this->redirect(HTTP_ROOT . "users/manage");
		}
		if (isset($_GET['grant_admin']) && trim(urldecode($_GET['grant_admin'])) != "") {
			$grant_admin = urldecode($_GET['grant_admin']);
			$grant_admin = addslashes($grant_admin);
			$getUsr = $this->User->find('first', array('conditions' => array('User.uniq_id' => $grant_admin), 'fields' => array('User.id')));
			$CompanyUser->query("UPDATE company_users as CompanyUser SET CompanyUser.user_type='2' WHERE CompanyUser.user_id='" . $getUsr['User']['id'] . "' AND CompanyUser.company_id='" . SES_COMP . "' AND CompanyUser.user_type!='1'");
			$this->Session->write("SUCCESS", "Granted admin privilege");
			$this->redirect(HTTP_ROOT . "users/manage");
		}
		if (isset($_GET['revoke_admin']) && trim(urldecode($_GET['revoke_admin'])) != "") {
			$revoke_admin = urldecode($_GET['revoke_admin']);
			$revoke_admin = addslashes($revoke_admin);
			$getUsr = $this->User->find('first', array('conditions' => array('User.uniq_id' => $revoke_admin), 'fields' => array('User.id')));
			$CompanyUser->query("UPDATE company_users as CompanyUser SET CompanyUser.user_type='3' WHERE CompanyUser.user_id='" . $getUsr['User']['id'] . "' AND CompanyUser.company_id='" . SES_COMP . "' AND CompanyUser.user_type!='1'");
			$this->Session->write("SUCCESS", "Revoked admin privilege");
			$this->redirect(HTTP_ROOT . "users/manage");
		}
		if (isset($_GET['resend']) && trim(urldecode($_GET['resend'])) != "") {
			$resend = urldecode($_GET['resend']);
			$resend = addslashes($resend);
			$UserInvitation = ClassRegistry::init('UserInvitation');
			$invit = $UserInvitation->find('first', array('conditions' => array('UserInvitation.qstr' => $resend)));

			if ($invit['UserInvitation']['user_id']) {
			$getUser = $this->User->find('first', array('conditions' => array('User.id' => $invit['UserInvitation']['user_id'])));

			$Company = ClassRegistry::init('Company');
			$comp = $Company->find('first', array('fields' => array('Company.id', 'Company.name', 'Company.uniq_id')));

			$expEmail = explode("@", $getUser['User']['email']);
			$expName = $expEmail[0];

			$qstr = $this->Format->generateUniqNumber();
			$loggedin_users = $this->Format->getUserNameForEmail($this->Auth->User("id"));
			$fromName = ucfirst($loggedin_users['User']['name']);
			$fromEmail = $loggedin_users['User']['email'];
			
			$ext_user = '';
			if (!$getUser['User']['password']) {
				$subject = $fromName." Invited you to join ".$comp['Company']['name']." on Orangescrum";
				$ext_user = 1;
			} else {
				$subject = $fromName." Invited you to join on Orangescrum";
			}
			
			$this->Email->delivery = EMAIL_DELIVERY;
			$this->Email->to = $to;
			$this->Email->subject = $subject;
			$this->Email->from = FROM_EMAIL;
			$this->Email->template = 'invite_user';
			$this->Email->sendAs = 'html';
			$this->set('expName', ucfirst($expName));
			$this->set('qstr', $qstr);
			$this->set('existing_user', $ext_user);
			
			$this->set('company_name',$comp['Company']['name']);
			$this->set('fromEmail',$fromEmail);
			$this->set('fromName',$fromName);
			
			if ($this->Sendgrid->sendgridsmtp($this->Email)) {
				$UserInvitation->query("UPDATE user_invitations set qstr='" . $qstr . "' where qstr='" . $resend . "'");
				$this->Session->write("SUCCESS", "Invitation resent to '" . $getUser['User']['email'] . "'");
				$this->redirect(HTTP_ROOT . "users/manage/?role=invited");
			}
			}
		}

		$query = "";
		if (isset($_GET['role']) && $_GET['role']) {
			$role = $_GET['role'];
		}
		if (isset($_GET['type']) && $_GET['type']) {
			$type = $_GET['type'];
		}
		if (isset($_GET['user_srch']) && $_GET['user_srch']) {
			$user_srch = htmlentities(strip_tags($_GET['user_srch']));
		}

		if (isset($_GET['page']) && $_GET['page']) {
			$page = $_GET['page'];
		}
		if ($role == "invited") {
			$query.= " AND UserInvitation.is_active = '1'";
		} else {
			if (!$role || $role == 'all') {
			$query.= " AND (CompanyUser.is_active = '1')";
			} else {
				if ($role == 2) {
					$query.= " AND (CompanyUser.user_type = '" . $role . "' OR CompanyUser.user_type = '1')";
				} elseif ($role == 3) {
					$query.= " AND CompanyUser.user_type = '" . $role . "' AND CompanyUser.is_active = '1' ";
				} elseif ($role == 'disable') {
					$query.= " AND CompanyUser.is_active = '0'";
				}
			}
		}
		$page = 1;
		if (isset($_GET['page']) && $_GET['page']) {
			$page = $_GET['page'];
		}
		$limit1 = $page * $page_limit - $page_limit;
		$limit2 = $page_limit;

		if ($user_srch) {
			$user_srch = addslashes(urldecode(htmlentities(strip_tags($user_srch))));
			$query.= " AND (User.name LIKE '%" . $user_srch . "%' OR User.last_name LIKE '%" . $user_srch . "%' OR User.email LIKE '%" . $user_srch . "%' OR User.short_name LIKE '%" . $user_srch . "%')";
		}
		
		if (isset($_GET['user']) && $_GET['user']) {
			$query.= " AND (User.uniq_id = '" . $_GET['user'] . "')";
		}
		
		if ($role == "invited") {
//			$userArr = $this->User->query("SELECT SQL_CALC_FOUND_ROWS * FROM users AS User,user_invitations AS UserInvitation WHERE User.id=UserInvitation.user_id AND UserInvitation.company_id='" . SES_COMP . "' " . trim($query) . " ORDER BY User.dt_created DESC LIMIT $limit1,$limit2");
                        $userArr = $this->User->query("SELECT SQL_CALC_FOUND_ROWS * FROM company_users AS CompanyUser LEFT JOIN users AS User ON CompanyUser.user_id=User.id WHERE CompanyUser.company_id=".SES_COMP."  AND CompanyUser.is_active ='2' AND User.email!='' AND (".$search_query.") ORDER BY User.dt_created DESC LIMIT $limit1,$limit2");

                } else {
			$userArr = $this->User->query("SELECT SQL_CALC_FOUND_ROWS * FROM users AS User,company_users AS CompanyUser WHERE User.id=CompanyUser.user_id AND CompanyUser.company_id='" . SES_COMP . "' " . trim($query) . " ORDER BY User.dt_last_login DESC LIMIT $limit1,$limit2");
		}
		$tot = $this->User->query("SELECT FOUND_ROWS() as total");
		$totUser = count($userArr);
		$arrusr = array();
		App::import("Helper", array("Format", "Casequery", "Tmzone", "Datetime"));
		$hFormat = new FormatHelper(new View(null));
		$hCasequery = new CasequeryHelper(new View(null));
		$hTmzone = new TmzoneHelper(new View(null));
		$hDatetime = new DatetimeHelper(new View(null));

		foreach ($userArr as $key => $usrall) {
			$userArr[$key]['User']['name'] = $hFormat->formatText($usrall['User']['name']);
			$userArr[$key]['User']['short_name'] = $hFormat->formatText($usrall['User']['short_name']);
			$userArr[$key]['User']['email'] = $hFormat->formatText($usrall['User']['email']);
			$userArr[$key]['User']['shln_email'] = $hFormat->shortLength($usrall['User']['email'], 30);

			if (($role != 'invited') && ($usrall['CompanyUser']['is_active'] != 2)) {
				$getprj = $hCasequery->getallproject($usrall['User']['id']);
				$allpj = "";
				foreach ($getprj as $k => $v) {
					$allpj = $allpj . ", " . ucwords(strtolower($v));
				}
				$userArr[$key]['User']['all_project'] = $hFormat->shortLength(trim($allpj, ","), 20);
				$userArr[$key]['User']['all_projects'] = trim($allpj, ",");
				$userArr[$key]['User']['total_project'] = count($getprj);
			} else {
				$allpj = $hCasequery->getallInvitedProj($usrall['CompanyUser']['project_id']);
				$userArr[$key]['User']['all_project'] = $hFormat->shortLength(trim($allpj, ","), 20);
				//$userArr[$key]['User']['total_project'] = count($getprj);
			}
			
			if ($role == 'invited') {
				$userArr[$key]['User']['qstr'] = $hCasequery->getinviteqstr($usrall['CompanyUser']['company_id'], $usrall['CompanyUser']['user_id']);
			} else if ($usrall['CompanyUser']['is_active'] == 2) {
				$userArr[$key]['User']['qstr'] = $hCasequery->getinviteqstr($usrall['CompanyUser']['company_id'], $usrall['CompanyUser']['user_id']);
			}


			if ($usrall['User']['dt_last_login']) {
				$locDT = $hTmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $usrall['User']['dt_last_login'], "datetime");
				$gmdate = $hTmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATE, "date");
				$userArr[$key]['User']['latest_activity'] = $hDatetime->dateFormatOutputdateTime_day($locDT, $gmdate);
			}
			if ($role == "invited") {
				$crdt = $usrall['User']['dt_created'];
			} else {
				$crdt = $usrall['CompanyUser']['created'];
			}
                        
			if ($crdt != "0000-00-00 00:00:00") {
				$locDT = $hTmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $crdt, "datetime");
				$gmdate = $hTmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATE, "date");
				$userArr[$key]['User']['created_on'] = $hDatetime->dateFormatOutputdateTime_day($locDT, $gmdate);
			}
                        
			if (isset($usrall['User']['name']) && !empty($usrall['User']['name'])) {
				array_push($arrusr, substr(trim($usrall['User']['name']), 0, 1));
			}
		}
		$active_user_cnt = 0;$invited_user_cnt = 0;$disabled_user_cnt=0;
		$grpcount = $CompanyUser->query('SELECT count(CompanyUser.id) as usrcnt , CompanyUser.is_active FROM company_users CompanyUser LEFT JOIN users User on CompanyUser.user_id=User.id WHERE CompanyUser.company_id='.SES_COMP.'  AND User.email!="" AND ('.$search_query.') GROUP BY CompanyUser.is_active ');
//		pr('SELECT count(CompanyUser.id) as usrcnt , CompanyUser.is_active FROM company_users CompanyUser LEFT JOIN users User on CompanyUser.user_id=User.id WHERE CompanyUser.company_id='.SES_COMP.'  AND User.email!="" AND ('.$search_query.') GROUP BY CompanyUser.is_active ');exit;
		if($grpcount){
			foreach($grpcount AS $key=>$val){
				if($val['CompanyUser']['is_active']==1){
					$active_user_cnt = $val['0']['usrcnt'];
				}elseif($val['CompanyUser']['is_active']==2){
					$invited_user_cnt = $val['0']['usrcnt'];
				}elseif($val['CompanyUser']['is_active']==0){
					$disabled_user_cnt = $val['0']['usrcnt'];
				}
			}
		}
		$this->set('active_user_cnt',$active_user_cnt);
		$this->set('invited_user_cnt',$invited_user_cnt);
		$this->set('disabled_user_cnt',$disabled_user_cnt);
		
		$this->set('caseCount', $tot[0][0]['total']);
		$this->set('page_limit', $page_limit);
		$this->set('page', $page);
		$this->set('casePage', $page);
		$this->set('projArr', $projArr);
		$this->set('userArr', $userArr);
		$this->set('role', $role);
		$this->set('type', $type);
		$this->set('user_srch', $user_srch);
		$this->set('arrusr', $arrusr);
		$this->set('totUser', $totUser);
		if (isset($_GET['resetpassword']) && $_GET['resetpassword']) {
			$this->User->recursive = -1;
			$userUniqId = urldecode($_GET['resetpassword']);
			$getData = $this->User->find("first", array('conditions' => array('User.uniq_id' => $userUniqId), 'fields' => array('User.name', 'User.email')));
			if (count($getData)) {
				$name = $getData['User']['name'];
				$to = $getData['User']['email'];
				$newPasswrod = $this->Format->generatePassword(6);

				$subject = "Orangescrum Reset Password";
				$message = "<table cellspacing='1' cellpadding='1'  width='100%' border='0'>
									<tr><td>&nbsp;</td></tr>
									<tr><td align='left' style='font:normal 14px verdana;'>Hi " . $name . ",</td></tr>
									<tr><td>&nbsp;</td></tr>
									<tr><td align='left' style='font:normal 14px verdana;'>Your Password has been reset to <b>" . $newPasswrod . "</b></td></tr>
									<tr><td>&nbsp;</td></tr>
									<tr><td>&nbsp;</td></tr>
								</table>
								";
				if ($this->Sendgrid->sendGridEmail(FROM_EMAIL, $to, $subject, $message, "ResetPassword")) {
					$newMd5Passwrod = md5($newPasswrod);
					$this->User->query("UPDATE users SET password='" . $newMd5Passwrod . "' WHERE uniq_id='" . $userUniqId . "'");

					$this->Session->write("SUCCESS", "Password of '" . $name . "' reset successfully");
					$this->redirect(HTTP_ROOT . "users/manage/");
				}
			}
		}
    }
	
	function forgotpassword() 
	{
		if(!empty($this->request->data) && empty($this->request->data['User']['repass']) && empty($this->request->data['User']['newpass'])) {
			$to = trim($this->request->data['User']['email']);
			$this->User->recursive = -1;
			$getUsrData = $this->User->find("first",array('conditions' => array('User.email'=>$to,'User.isactive'=>1),'fields'=>array('User.id','User.name')));
			if($getUsrData && is_array($getUsrData) && count($getUsrData)) {
			
				$id = $getUsrData['User']['id'];
				$name = stripslashes($getUsrData['User']['name']);
				$qstr = md5(uniqid(rand()));
				$urlValue = "?qstr=".$qstr;
				
				$this->Email->delivery = EMAIL_DELIVERY;
				$this->Email->to = $to;
				$this->Email->subject = "Forgot Password Request";
				$this->Email->from = FROM_EMAIL_NOTIFY;
				$this->Email->template = 'forgot_password';
				$this->Email->sendAs = 'html';
				$this->set('name',$name);
				$this->set('urlValue',$urlValue);
		
				if($this->Sendgrid->sendgridsmtp($this->Email))
				{
					$this->User->query("UPDATE users SET query_string='".$qstr."' WHERE id=".$id);
					
					//$this->Session->write("PASS_SUCCESS","<font style='color:green;'>Please check your mail to reset your password</font>");
					$this->Session->setFlash("Please check your mail to reset your password", 'default', array('class'=>'success'));
					$this->redirect(HTTP_ROOT."users/forgotpassword/");
				}
			}
			else {
				//$this->Session->write("ERROR_RESET","<font style='color:red;'>If an account exists with this email address, we've sent instructions on resetting your password. Please check your email!</font>");
				$this->Session->setFlash("If an account exists with this email address, we've sent instructions on resetting your password. Please check your email!", 'default', array('class'=>'success'));
				$this->redirect(HTTP_ROOT."users/forgotpassword/");
			}
		}
		if(isset($_GET['qstr']) && $_GET['qstr'])
		{  
			$queryString = urldecode($_GET['qstr']);
			$this->User->recursive = -1;
			
			$getData =$this->User->query("SELECT User.id,User.email,User.name FROM users AS User WHERE User.query_string='".$queryString."' AND User.isactive='1'");
			//pr($getData);exit;
			
			if(isset($getData) && count($getData) == 1)
			{ 	
						
					$this->set('passemail','12');
					$this->set('user_id',$getData['0']['User']['id']);
					
			}
		}
		if(!empty($this->request->data) && !empty($this->request->data['User']['repass']) && !empty($this->request->data['User']['newpass'])) {
		//echo $this->request->data['user_id'];exit;
		if($this->request->data['User']['repass']==$this->request->data['User']['newpass'])
			{
		$newMd5Passwrod = md5($this->request->data['User']['repass']);
		$id=$this->request->data['user_id'];			
		$this->User->query("UPDATE users SET password='".$newMd5Passwrod."',query_string='' WHERE id=".$id);
		//$this->Session->write("PASS_SUCCESS","<font style='color:green;'>Please Login with  your new password</font>");
		$this->set('chkemail','11');
		//$this->redirect(HTTP_ROOT."users/login/");

			}

		}
	}
	function check_short_name() 
	{
		$this->layout='ajax';
		if(isset($this->request->data['shortname']) && trim($this->request->data['shortname']))
		{
			$count = $this->User->find("count",array("conditions"=>array('User.short_name'=>trim(strtoupper($this->request->data['shortname']))),'fields'=>'DISTINCT User.id'));
			$this->set('count',$count);
			$this->set('shortname',trim(strtoupper($this->request->data['shortname'])));
		}
	}
	
	function new_user($resend = NULL){
		$Company = ClassRegistry::init('Company');
		//$comp = $Company->find('first',array('fields'=>array('Company.id','Company.name','Company.uniq_id')));
		
		$company_id = SES_COMP;
		$projectcls = ClassRegistry::init('Project');
		$default_project_id ='';
		$UserInvitation = ClassRegistry::init('UserInvitation');
		
		$invitation_id = "";
		if(isset($this->request->data['User']) || trim($resend)) {
			if($resend) {
				$invit = $UserInvitation->find('first',array('conditions'=>array('UserInvitation.qstr'=>$resend)));
				if($invit['UserInvitation']['user_id']) {					
					$invitation_id = $invit['UserInvitation']['id'];
					$this->request->data['User']['pid'] = $invit['UserInvitation']['project_id'];
					$this->request->data['User']['istype'] = 2;
					$getEmail = $this->User->find('first',array('conditions'=>array('User.id'=>$invit['UserInvitation']['user_id']),'fields'=>array('User.email')));
					$this->request->data['User']['email'] = $getEmail['User']['email'];
				}
			}else {
				$this->request->data['User']['email'] = trim($this->request->data['User']['email']);
			}
		}
		if(isset($GLOBALS['usercount']) && strtolower($GLOBALS['Userlimitation']['user_limit'])!='unlimited' && ($GLOBALS['usercount'] >= $GLOBALS['Userlimitation']['user_limit'])){
			$userlimit =1;
		}else{
			$userlimit =0;
		}
		
		$Company = ClassRegistry::init('Company');
		$comp = $Company->find('first', array('fields' => array('Company.id', 'Company.name', 'Company.uniq_id')));
		
		if($this->request->data['User']['email'] && !$userlimit) {
			$CompanyUser = ClassRegistry::init('CompanyUser');
			if(strstr($this->request->data['User']['email'],",")){
				$err=0;
				$mail_list=explode(",",trim(trim($this->request->data['User']['email']),','));
				$ucounter =0;$mail_arr=array();
				foreach($mail_list AS $key=>$val){
					if(trim($val) != ""){
						$mail_arr[]=trim($val);
						$ucounter ++;
					}
				}
				//$ucounter = count($mail_arr);
				$total_new_users = $ucounter + $GLOBALS['usercount'];
				if(strtolower($GLOBALS['Userlimitation']['user_limit'])!='unlimited' && ($total_new_users > $GLOBALS['Userlimitation']['user_limit'])){
					if(SES_TYPE == 3) {
						$this->Session->write("ERROR","Sorry! you have exceeded the user limit");
					}
					else {
						$this->Session->write("ERROR","Sorry! This account exceeded the user limit.");
					}
					$this->redirect(HTTP_ROOT);exit;
				}
				$error_emails='';
				$invite_users = '';

				for($i=0;$i<count($mail_arr);$i++){				
					if(trim($mail_arr[$i]) != ""){
						if (!filter_var($mail_arr[$i], FILTER_VALIDATE_EMAIL)) {
							$error_emails[] = $mail_arr[$i];continue;
						}
						$mail_arr[$i]=trim($mail_arr[$i]);
						$findEmail = $this->User->find('first',array('conditions'=>array('User.email'=>$mail_arr[$i]),'fields'=>array('User.id')));
						if(@$findEmail['User']['id']) {
							$userid = $findEmail['User']['id'];
						}else {
							$this->request->data['User']['uniq_id'] = $this->Format->generateUniqNumber();
							$this->request->data['User']['isactive'] = 2;
							$this->request->data['User']['isemail'] = 1;
							$this->request->data['User']['dt_created'] = GMT_DATETIME;
							$this->request->data['User']['email']=trim($mail_arr[$i]);
							$this->User->saveAll($this->request->data);
							$userid = $this->User->getLastInsertID();
						}
						if($userid && $userid != $this->Auth->User("id")) {
							$qstr = $this->Format->generateUniqNumber();
				
							if($invitation_id) {
								$InviteUsr['UserInvitation']['id'] = $invitation_id;
							}
							$InviteUsr['UserInvitation']['invitor_id'] = $this->Auth->User("id");
							$InviteUsr['UserInvitation']['user_id'] = $userid;
					
							$InviteUsr['UserInvitation']['company_id'] = $company_id;
							if(isset($this->request->data['User']['pid'])) {
							    if(is_array($this->request->data['User']['pid']) && !empty($this->request->data['User']['pid'])) {
								$InviteUsr['UserInvitation']['project_id'] = implode(",",$this->request->data['User']['pid']);
							    }elseif($this->request->data['User']['pid']){
								$InviteUsr['UserInvitation']['project_id'] = $this->request->data['User']['pid'];
							    }
							}/*else{
								if(!$project_flag){
									$project_flag=1;
									$project_list = $projectcls->find('first',array('conditions'=>array('Project.short_name'=>'WCOS','Project.isactive'=>1,'Project.company_id'=>SES_COMP),'fields'=>"Project.id"));
									if($project_list){
										$default_project_id = $project_list['Project']['id'];
									}
								}
								if($default_project_id){
									$InviteUsr['UserInvitation']['project_id'] = $default_project_id;
								}
							}*/
							$InviteUsr['UserInvitation']['qstr'] = $qstr;
							$InviteUsr['UserInvitation']['created'] = GMT_DATETIME;
							$InviteUsr['UserInvitation']['is_active'] = 1;
							$InviteUsr['UserInvitation']['user_type'] = $this->request->data['User']['istype'];
				
							if($UserInvitation->saveAll($InviteUsr))
							{
							    if(!$invitation_id) {
								$invite_users = $invite_users.",".$userid;
							    }
								$cmpnyUsr=array();
								$is_sub_upgrade =1;
								// Checking for a deleted user when gets invited again.
								$compuser = $CompanyUser->find('first',array('conditions'=>array('user_id'=>$userid,'company_id'=>SES_COMP)));
								if($compuser && $compuser['CompanyUser']['is_active']==3){
									$is_sub_upgrade=0;
									// If that user deleted in the same billing month and invited again then that user will not paid 
									if($GLOBALS['Userlimitation']['btsubscription_id']){
										if(strtotime($GLOBALS['Userlimitation']['next_billing_date']) > strtotime($compuser['CompanyUser']['billing_end_date'])){
											$is_sub_upgrade=1;
										}
									}
									$cmpnyUsr['CompanyUser']['id'] = $compuser['CompanyUser']['id'];
								}
								$cmpnyUsr['CompanyUser']['user_id'] = $userid;
								$cmpnyUsr['CompanyUser']['company_id'] = $company_id;
								$cmpnyUsr['CompanyUser']['company_uniq_id'] = COMP_UID;
								$cmpnyUsr['CompanyUser']['user_type'] = $this->request->data['User']['istype'];
								$cmpnyUsr['CompanyUser']['is_active'] = 2;
								$cmpnyUsr['CompanyUser']['created'] = GMT_DATETIME;
								if($CompanyUser->saveAll($cmpnyUsr)){
									$json_arr['email'] = $mail_arr[$i];
									$json_arr['created'] = GMT_DATETIME;
									$this->Postcase->eventLog(SES_COMP,SES_ID,$json_arr,25);

									$comp_user_id = $CompanyUser->getLastInsertID();

									$to = $mail_arr[$i];
					
									$expEmail = explode("@",$mail_arr[$i]);
									$expName = $expEmail[0];
									$loggedin_users = $this->Format->getUserNameForEmail($this->Auth->User("id"));
									$fromName = ucfirst($loggedin_users['User']['name']);
									$fromEmail = $loggedin_users['User']['email'];
									
									$ext_user = '';
									if(@$findEmail['User']['id']) {
										$subject = $fromName." invited you to join ".$comp['Company']['name']." on Orangescrum";
										$ext_user = 1;              			
									}else {
										$subject = $fromName." invited you to join Orangescrum";
									}
									
									$this->Email->delivery = EMAIL_DELIVERY;
									$this->Email->to = $to;  
									$this->Email->subject = $subject;
									$this->Email->from = FROM_EMAIL;
									$this->Email->template = 'invite_user';
									$this->Email->sendAs = 'html';
									$this->set('expName', ucfirst($expName));
									$this->set('qstr', $qstr);
									$this->set('existing_user',$ext_user);
									
									$this->set('company_name',$comp['Company']['name']);
									$this->set('fromEmail',$fromEmail);
									$this->set('fromName',$fromName);
									
									try{
										$this->Sendgrid->sendgridsmtp($this->Email);								
									}Catch(Exception $e){ 
									}
								}
							
							}
						}else{ $err=1;}
				}
				}
				if(!$err){
				    
					/*if($error_emails){
						$this->Session->write("ERROR","'".implode(',',$error_emails)."' are invalid emails. So they are not invited to OS Please try again!");
						$this->redirect(HTTP_ROOT."users/manage/");
					}*/
					$this->Session->write("SUCCESS","Invitation sent to Successfully");
					if($_SERVER['HTTP_REFERER']==HTTP_ROOT.'onbording'){
						$this->redirect(HTTP_ROOT."onbording");exit;
					}
					if((strtolower($GLOBALS['Userlimitation']['user_limit']) !='unlimited') && $GLOBALS['usercount']<=1){
						$this->redirect(HTTP_ROOT."onbording");exit;
					}else{
					    if(trim($invite_users) && !isset($this->request->data['User']['pid'])) {
							$invite_users = trim($invite_users,',');
							setcookie('LAST_INVITE_USER',$invite_users,time()+3600,'/',DOMAIN_COOKIE,false,false);
					    }
						$this->redirect(HTTP_ROOT."users/manage/?role=invited");
					}
					$this->redirect(HTTP_ROOT."users/manage/?role=invited");
				}else{
					$this->Session->write("ERROR","Invitation Failed. Please try again!");
					$this->redirect(HTTP_ROOT."users/manage/");
				}
			}else{
				  if (!filter_var($this->request->data['User']['email'], FILTER_VALIDATE_EMAIL)) {
						$error_emails = $this->request->data['User']['email'];
					}
				 		$findEmail = $this->User->find('first',array('conditions'=>array('User.email'=>$this->request->data['User']['email']),'fields'=>array('User.id')));
						
						if(@$findEmail['User']['id']) {
							$userid = $findEmail['User']['id'];
						}
						else {
							$this->request->data['User']['uniq_id'] = $this->Format->generateUniqNumber();
							$this->request->data['User']['isactive'] = 2;
							$this->request->data['User']['isemail'] = 1;
							$this->request->data['User']['dt_created'] = GMT_DATETIME;
							$this->User->save($this->request->data);
							$userid = $this->User->getLastInsertID();
						}
						
						if($userid && $userid != $this->Auth->User("id")) {
							$qstr = $this->Format->generateUniqNumber();
				
							if($invitation_id) {
								$InviteUsr['UserInvitation']['id'] = $invitation_id;
							}
							$InviteUsr['UserInvitation']['invitor_id'] = $this->Auth->User("id");
							$InviteUsr['UserInvitation']['user_id'] = $userid;
					
							$InviteUsr['UserInvitation']['company_id'] = $company_id;							
							
							
							if(isset($this->request->data['User']['pid'])) {
							    if(is_array($this->request->data['User']['pid']) && !empty($this->request->data['User']['pid'])) {
								$InviteUsr['UserInvitation']['project_id'] = implode(",",$this->request->data['User']['pid']);
							    }elseif($this->request->data['User']['pid']){
								$InviteUsr['UserInvitation']['project_id'] = $this->request->data['User']['pid'];
							    }
							}else{
								$project_list = $projectcls->find('first',array('conditions'=>array('Project.short_name'=>'WCOS','Project.isactive'=>1,'Project.company_id'=>SES_COMP),'fields'=>"Project.id"));
								if($project_list){
									$InviteUsr['UserInvitation']['project_id'] = $project_list['Project']['id'];
								}
							}
							$InviteUsr['UserInvitation']['qstr'] = $qstr;
							$InviteUsr['UserInvitation']['created'] = GMT_DATETIME;
							$InviteUsr['UserInvitation']['is_active'] = 1;
							$InviteUsr['UserInvitation']['user_type'] = $this->request->data['User']['istype'];							
							
							if($UserInvitation->save($InviteUsr))
							{
							    	$is_sub_upgrade=1;
								// Checking for a deleted user when gets invited again.
								$compuser = $CompanyUser->find('first',array('conditions'=>array('user_id'=>$userid,'company_id'=>SES_COMP)));
								if($compuser && $compuser['CompanyUser']['is_active']==3){
									$is_sub_upgrade=0;
									// If that user deleted in the same billing month and invited again then that user will not paid 
									if($GLOBALS['Userlimitation']['btsubscription_id']){
										if(strtotime($GLOBALS['Userlimitation']['next_billing_date']) > strtotime($compuser['CompanyUser']['billing_end_date'])){
											$is_sub_upgrade=1;
										}
									}
									$cmpnyUsr['CompanyUser']['id'] = $compuser['CompanyUser']['id'];
								}
								if(!$resend){
									$cmpnyUsr['CompanyUser']['user_id'] = $userid;
									$cmpnyUsr['CompanyUser']['company_id'] = $company_id;
									$cmpnyUsr['CompanyUser']['company_uniq_id'] = COMP_UID;
									$cmpnyUsr['CompanyUser']['user_type'] = $this->request->data['User']['istype'];
									$cmpnyUsr['CompanyUser']['is_active'] = 2;
									$cmpnyUsr['CompanyUser']['created'] = GMT_DATETIME;
									if($CompanyUser->saveAll($cmpnyUsr)){
									    $comp_user_id = $CompanyUser->getLastInsertID();
									    /*if($is_sub_upgrade){
										    $this->update_bt_subscription($comp_user_id,$company_id,1);
									    }*/
									}
								}
								//Event log data and inserted into database in account creation--- Start
								$json_arr['email'] = $this->request->data['User']['email'];
								$json_arr['created'] = GMT_DATETIME;
								$this->Postcase->eventLog(SES_COMP,SES_ID,$json_arr,25);
								//End 
								$to = $this->request->data['User']['email'];
								$expEmail = explode("@",$this->request->data['User']['email']);
								$expName = $expEmail[0];
								$loggedin_users = $this->Format->getUserNameForEmail($this->Auth->User("id"));
								$fromName = ucfirst($loggedin_users['User']['name']);
								$fromEmail = $loggedin_users['User']['email'];

								$ext_user = '';
								if(@$findEmail['User']['id']) {
									$subject = $fromName." invited you to join ".$comp['Company']['name']." on Orangescrum";
									$ext_user = 1;              			
								}else {
									$subject = $fromName." invited you to join Orangescrum";
								}			

								$this->Email->delivery = EMAIL_DELIVERY;
								$this->Email->to = $to;  
								$this->Email->subject = $subject;
								$this->Email->from = FROM_EMAIL;
								$this->Email->template = 'invite_user';
								$this->Email->sendAs = 'html';
								$this->set('expName', ucfirst($expName));
								$this->set('qstr', $qstr);
								$this->set('existing_user',$ext_user);

								$this->set('company_name',$comp['Company']['name']);
								$this->set('fromEmail',$fromEmail);
								$this->set('fromName',$fromName);

								try{
									$res = $this->Sendgrid->sendgridsmtp($this->Email);							
								}Catch(Exception $e){ 
								}			
								$this->Session->write("SUCCESS","Invitation sent to '".$this->request->data['User']['email']."'");
								if($_SERVER['HTTP_REFERER']==HTTP_ROOT.'onbording'){
									$this->redirect(HTTP_ROOT."onbording");exit;
								}
								if($resend) {
									$this->redirect($_SERVER['HTTP_REFERER']);exit;
									$this->redirect(HTTP_ROOT."users/manage");
								}else {
									if((strtolower($GLOBALS['Userlimitation']['user_limit']) !='unlimited') && $GLOBALS['usercount']<=1){
										$this->redirect(HTTP_ROOT."onbording");exit;
									}else{
									    if(!$invitation_id && !isset($this->request->data['User']['pid'])) {

											setcookie('LAST_INVITE_USER',$userid,time()+3600,'/',DOMAIN_COOKIE,false,false);
									    }
										$this->redirect(HTTP_ROOT."users/manage/?role=invited");
									}
								}
								
							}
						}
						$this->Session->write("ERROR","Invitation Failed. Please try again!");
						if($resend) {
							$this->redirect($_SERVER['HTTP_REFERER']);exit;
							$this->redirect(HTTP_ROOT."users/manage");
						}
						else {
							$this->redirect(HTTP_ROOT."dashboard");
						}
			}
		}
		if($resend) {
			$this->redirect(HTTP_ROOT."users/manage");
		}
		$this->layout='ajax';
		//$userType = array(2=>"Member",3=>"Customer");
		if(SES_TYPE == 1) {
			$userType = array(3=>"Member",2=>"Admin");
		}
		else {
			$userType = array(3=>"Member");
		}
		
		$TimezoneName = ClassRegistry::init('TimezoneName');
		$TimezoneName->recursive = -1;
		$tmZoneArr = $TimezoneName->find('all');

		$this->set('userType',$userType);
		$this->set('tmZoneArr',$tmZoneArr);
		$this->set('uniq_id',COMP_UID);
	}
	function getProjects() {
	    $this->layout='ajax';
	    $items = array();
	    $q = $this->request->query['tag'];
	    
	    if(trim($q)){
		$Company = ClassRegistry::init('Company');
		$comp = $Company->find('first',array('fields'=>array('Company.id')));
		$company_id = $comp['Company']['id'];

		$cond = "Project.isactive=1 AND Project.name != '' AND Project.name LIKE '%".$q."%' AND Project.company_id = ".$company_id." AND 
		Project.id IN (SELECT DISTINCT ProjectUser.project_id FROM project_users AS ProjectUser WHERE ProjectUser.user_id = ".SES_ID.")";
		if(trim($this->params['pass'][0])) {
		    $cond.= " AND Project.id NOT IN(".$this->params['pass'][0].")";
		}
		$Project = ClassRegistry::init('Project');
		$Project->recursive = -1;
		$sql = "SELECT DISTINCT Project.id,Project.name FROM projects AS Project WHERE ".$cond." ORDER BY Project.name LIMIT 0, 10";
		$projArr = $Project->query($sql);

		ob_clean();
		foreach($projArr as $key => $value) {
		    $items[] = array("key"=>$value['Project']['id'],"value"=>$value['Project']['name']);
		}
	    }
	    print json_encode($items);exit;
	}
	
	function notification()
	{
		$this->layout='ajax';
		$CaseUserView = ClassRegistry::init('CaseUserView');
		$allCases = $CaseUserView->find('all', array('conditions' => array('CaseUserView.user_id'=>SES_ID,'CaseUserView.isviewed'=>0,'CaseUserView.istype'=>1),'ORDER' => array('CaseUserView.id ASC'),'limit'=>1));
		$this->set('allCases',$allCases);
	}
	function caseview_remove()
	{
		$this->layout='ajax';
		$id = NULL;
		if(isset($this->request->data['id']))
		{
			$id = $this->request->data['id'];
		}
		$CaseUserView = ClassRegistry::init('CaseUserView');
		$CaseUserView->query("UPDATE case_user_views as CaseUserView SET CaseUserView.isviewed='1' WHERE CaseUserView.id=".$id);
		exit;
	}
	function project_menu()
	{
		$this->layout='ajax';
		$page = $this->request->data['page'];
		$pgname = isset($this->request->data['page_name'])?$this->request->data['page_name']:'';
		$limit = $this->request->data['limit'];
		$filter = $this->request->data['filter'];//echo $filter;
		$qry="";
		$ProjectUser = ClassRegistry::init('ProjectUser');
		if($filter == "delegateto"){
			$qry = " AND ec.user_id=".SES_ID." AND ec.assign_to!=0 AND ec.assign_to!=".SES_ID;
        }else if($filter == "assigntome"){
			$qry = " AND ((ec.assign_to=".SES_ID.") OR (ec.assign_to=0 AND ec.user_id=".SES_ID."))";
        }else if($filter == "latest"){
			$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
			$qry = " AND ec.dt_created > '".$before."' AND ec.dt_created <= '".GMT_DATETIME."'";
        }else if($filter == "files"){
			$qry = " AND ec.format = '1'";
         }else{
			$qry="";
         }
		 
		if($limit != "all") {
			
			$allProjArr = $ProjectUser->query("select SQL_CALC_FOUND_ROWS DISTINCT p.name,p.id,p.uniq_id as uniq_id,(select count(ec.id) from easycases as ec where ec.istype='1' AND ec.isactive='1' AND pu.project_id=ec.project_id ".trim($qry).") as count from projects as p, project_users as pu where p.id=pu.project_id and pu.user_id='".SES_ID."' and pu.company_id='".SES_COMP."' AND p.isactive='1' ORDER BY pu.dt_visited DESC LIMIT 0,$limit");
		}
		else {
			$allProjArr = $ProjectUser->query("select SQL_CALC_FOUND_ROWS DISTINCT p.name,p.id,p.uniq_id as uniq_id,(select count(ec.id) from easycases as ec where ec.istype='1' AND ec.isactive='1' AND pu.project_id=ec.project_id  ".trim($qry).") as count from projects as p, project_users as pu where p.id=pu.project_id and pu.user_id='".SES_ID."' and pu.company_id='".SES_COMP."' AND p.isactive='1' ORDER BY pu.dt_visited DESC");
		}
		
		$totProjCnt = $ProjectUser->query("SELECT FOUND_ROWS() as count");
		$countAll = $totProjCnt['0']['0']['count'];
		
		$allPjCount = $ProjectUser->query("select count(DISTINCT ec.id) as count from projects as p, project_users as pu, easycases as ec where p.id=pu.project_id and pu.user_id='".SES_ID."' AND pu.project_id=ec.project_id AND ec.istype='1' and pu.company_id='".SES_COMP."' AND ec.isactive='1' AND p.isactive='1' ".trim($qry)."");
		
		//$allPjCount1 = $this->ProjectUser->query("select p.name,p.uniq_id as uniq_id,(select count(ec.id) from easycases as ec where ec.dt_created > '".$before."' AND ec.dt_created <= '".GMT_DATETIME."' AND ec.istype='1' AND ec.isactive='1' AND pu.project_id=ec.project_id) as count from projects as p, project_users as pu where p.id=pu.project_id and pu.user_id='".SES_ID."' AND p.isactive='1'");
		
		
		$this->set('allProjArr',$allProjArr);
		$this->set('allPjCount',$allPjCount);
		
		//$countAll = $ProjectUser->find('count', array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1), 'fields' => 'DISTINCT Project.id'));
		$this->set('countAll',$countAll);
		
		$this->set('page',$page);
		$this->set('pgname',$pgname);
		$this->set('limit',$limit);
	}
	function project_all() 
	{
		$this->layout='ajax';
		
		$page = $this->request->data['page'];
		$type = $this->request->data['type'];

		if($type == "enabled")
		{
			$cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1), 'fields' => array('Project.name','Project.uniq_id'), 'order'=>array('Project.name'));
		}
		else
		{
			$cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 2), 'fields' => array('Project.name','Project.uniq_id'), 'order'=>array('Project.name'));
		}
		

		$ProjectUser = ClassRegistry::init('ProjectUser');
		$ProjectUser->unbindModel(array('belongsTo' => array('User')));
		$allProjArr = $ProjectUser->find('all', $cond);
		
		$this->set('allProjArr',$allProjArr);
		$this->set('page',$page);
		$this->set('type',$type);
	}
    public function login($demo = NULL,$email= NULL,$pass= NULL,$first_login=0) {
		$gdata = '';

		
		if (isset($_COOKIE['GOOGLE_INFO_SIGIN']) && !empty($_COOKIE['GOOGLE_INFO_SIGIN'])) {
		    $gdata = (array)json_decode($_COOKIE['GOOGLE_INFO_SIGIN']);
		    $this->request->data['User']['email'] = $gdata['email'];
		}else if(isset($_COOKIE['user_info']) && !empty($_COOKIE['user_info'])){
			$gdata['email'] = $_COOKIE['user_info'];
			$this->request->data['User']['email'] = $gdata['email'];
			unset($_COOKIE['user_info']);
			setcookie('user_info', '', time() - 60000,'/',DOMAIN_COOKIE,false,false);
		} else if (isset($_COOKIE['GOOGLE_USER_INFOS']) && !empty($_COOKIE['GOOGLE_USER_INFOS'])) {
		    $google_user_infos =  json_decode($_COOKIE['GOOGLE_USER_INFOS'], true);
		    $_SESSION['GOOGLE_USER_INFO'] = $google_user_infos['GOOGLE_USER_INFO'];
		    setcookie('GOOGLE_USER_INFOS', '', time() - 60000,'/',DOMAIN_COOKIE,false,false);
		}
		if (isset($_SESSION['GOOGLE_USER_INFO']) && !empty($_SESSION['GOOGLE_USER_INFO'])) {
		    $this->request->data['User']['email'] = $_SESSION['GOOGLE_USER_INFO']['email'];	
		}
		
		if(isset($this->request->data['User']['email'])) {
			$this->request->data['User']['email'] = trim($this->request->data['User']['email']);
		}
		if(isset($this->request->data['User'])) {
			$emailCheck = $this->request->data['User']['email'];
		}
		if(isset($_SESSION['GOOGLE_USER_INFO'])) {
			$google_user_info = $_SESSION['GOOGLE_USER_INFO'];
		}
		
        if(!empty($this->request->data) || !empty($email)) {
				
			$usrLogin = array();
			if($email && $pass) {
				$this->request->data['User']['email'] = $email;
				if(strlen($pass) == 32) {
					$this->request->data['User']['password'] = $pass;
				}else {
					$this->request->data['User']['password'] = md5($pass);
				}
				$this->User->unbindModel(array('hasAndBelongsToMany' => array('Project')));
				$usrLogin = $this->User->find('first',array('conditions'=>array('User.email'=>$this->request->data['User']['email'],'User.password'=>$this->request->data['User']['password'],'User.isactive'=>1)));
				$this->Session->write('Auth.User',$usrLogin['User']);	
			} else if (isset($_SESSION['GOOGLE_USER_INFO']) && !empty($_SESSION['GOOGLE_USER_INFO'])) {
				$this->User->unbindModel(array('hasAndBelongsToMany' => array('Project')));
				$usrLogin = $this->User->find('first',array('conditions'=>array('User.email'=>$_SESSION['GOOGLE_USER_INFO']['email'],'User.isactive'=>1)));
				$this->Session->write('Auth.User',$usrLogin['User']);
				$access_token=$_SESSION['GOOGLE_USER_INFO']['access_token'];
				unset($_SESSION['GOOGLE_USER_INFO']);
			} else if(isset($gdata['email']) && !empty($gdata['email'])) {
				$this->User->unbindModel(array('hasAndBelongsToMany' => array('Project')));
				$usrLogin = $this->User->find('first',array('conditions'=>array('User.email'=>$this->request->data['User']['email'],'User.isactive'=>1)));
				$this->Session->write('Auth.User',$usrLogin['User']);
				unset($_SESSION['GOOGLE_USER_INFO']);
				$access_token=$_COOKIE['token'];
				setcookie('GOOGLE_INFO_SIGIN','',-365,'/',DOMAIN_COOKIE,false,false);
			}
			if(($this->Auth->login() || isset($usrLogin['User']['id'])) && $this->Auth->user('id')) {
                $this->User->keepPassChk($this->Auth->user('id'));
				if($usrLogin['User']['id']){
					$this->saveUserInfo($usrLogin['User']['id'],$access_token,0);
				}
				if($this->Auth->user('isactive') == 2){
					$cookie = array();
					$this->Cookie->write('Auth.User', $cookie, '-2 weeks');
					$this->Auth->logout();
					$this->Session->write("SES_EMAIL",$this->request->data['User']['email']);
					$this->Session->setFlash("Oops! this account has been deactivated", 'default', array('class'=>'error'));
					
					$this->redirect(HTTP_ROOT."users/login");
				}
				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('dt_last_login', GMT_DATETIME);
				$this->User->saveField('query_string', '');
				if($this->isiPad()) {
					$user_sig = md5(uniqid(rand()).time());
					$this->User->saveField('sig', $user_sig);
				}
				if(isset($this->request->data['User']['remember_me'])) {
					setcookie('REMEMBER',1,time()+3600*24*7,'/',DOMAIN_COOKIE,false,false);
					unset($this->request->data['User']['remember_me']);
					$cookieTime = time()+3600*24*7;
				}else {
					$cookieTime = COOKIE_TIME;
				}
				if(!$this->Auth->user('dt_last_login')) {
					setcookie('FIRST_LOGIN',1,$cookieTime,'/',DOMAIN_COOKIE,false,false);
				}
				if($_COOKIE['FIRST_LOGIN']) {
					setcookie('FIRST_LOGIN','',-1,'/',DOMAIN_COOKIE,false,false);
				}
				setcookie('USER_UNIQ',$this->Auth->user('uniq_id'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
				setcookie('USERTYP',$this->Auth->user('istype'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
				setcookie('USERTZ',$this->Auth->user('timezone_id'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
				setcookie('USERSUB_TYPE',$this->Auth->user('usersub_type'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
				setcookie('IS_MODERATOR',$this->Auth->user('is_moderator'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
					
				if($this->Auth->User("istype") == '1') {
					setcookie('CURRENT_FILTER','latest',time()+3600*24*365,'/',DOMAIN_COOKIE,false,false);
				}
				
				$redirect = HTTP_ROOT;
				//Keeping track after successfully login.
				$this->loadModel('UserLogin');
				$user_login['user_id'] = $this->Auth->user('id');
				$this->UserLogin->save($user_login);

				if($_COOKIE['HELP'] == 1){
					setcookie('HELP',0,$cookieTime,'/',DOMAIN_COOKIE,false,false);
					$this->redirect(PROTOCOL.$seoArr[0].".".DOMAIN."help");
				}
				if($_COOKIE['CK_EMAIL_NOTIFICATION'] == 1){
					setcookie('CK_EMAIL_NOTIFICATION',0,$cookieTime,'/',DOMAIN_COOKIE,false,false);
					$this->redirect(PROTOCOL.$seoArr[0].".".DOMAIN."users/email_notifications");
				}
				if(isset($this->request->data['case_details']) && $this->request->data['case_details'] ){
					$this->redirect($redirect."dashboard#details/".$this->request->data['case_details']);exit;
				}
				if(isset($this->request->data['User']['project']) && isset($this->request->data['User']['case'])){
					$this->redirect($redirect."dashboard#details/".$this->request->data['User']['case']);
				}elseif(isset($this->request->data['User']['project'])){
					$this->redirect($redirect."dashboard/?project=".$this->request->data['User']['project']);
				}elseif(isset($this->request->data['User']['file']) && $this->request->data['User']['file']){   
					@$files=$this->request->data['User']['file'];
					$fext = strtolower(substr(strrchr($files,"."),1));
					$extList = array("jpg","jpeg","png","tif","gif","bmp","thm");
					$this->redirect($redirect."easycases/download/".$this->request->data['User']['file']);
				}elseif($update_email_redirect){
						$this->redirect(HTTP_APP."users/profile");
				}
				$this->redirect($redirect);
			}
			else
			{
				$this->Session->write("SES_EMAIL",$this->request->data['User']['email']);
				//$this->Session->write("LOGIN_ERROR","Email or Password is invalid!");
				$this->Session->setFlash("Email or Password is invalid!", 'default', array('class'=>'error'));
				$_SESSION['GOOGLE_USER_INFO']=$google_user_info;
				unset($_COOKIE['user_info']);
				setcookie('GOOGLE_USER_INFOS', '', time() - 60000,'/',DOMAIN_COOKIE,false,false);
				unset($_SESSION['GOOGLE_USER_INFO']);
				$this->redirect(HTTP_APP."users/login");
				
			}
			
		}
        	if(isset($demo) && $demo != "demo"){
			if(strstr($demo, '___')){
				$t_demo = explode('___',$demo);
				$upd_user = $this->User->find('first',array('conditions'=>array('User.update_random'=>$t_demo[0])));
				if($upd_user){
					$t_emal = $upd_user['User']['email'];
					if($t_demo[1] == "NOT_UPDATE"){
						$this->set("update_email_message",'<span style="color:red">"'.$t_emal.'" email already exists!.</span>');
					}else{				
						$upd_user['User']['update_random'] = ''; 
						$this->User->save($upd_user);
						$this->set("update_email_message",'<span style="color:green">Now you can login using "'.$t_emal.'"</span>');
					}
		
				}
			}
		 }
		$Company = ClassRegistry::init('Company');
		$Company->recursive = -1;
		$findCompany = $Company->find('first',array('conditions'=>array('Company.is_active'=>1),'fields'=>array('Company.id')));
		$this->set("findCompany",$findCompany);
		
		$rightpath = 1;
		if(!$findCompany['Company']['id']) {
			if(trim($_SERVER['REQUEST_URI']) == "/" || trim($_SERVER['REQUEST_URI']) == "/" || trim($_SERVER['REQUEST_URI']) == "") {
				$rightpath = 1;
			}
			else {
				$url = $_SERVER['REQUEST_URI'];
				$arr = explode("/", $url);
				$sub_folder = $arr[1];
				$this->set("sub_folder",$sub_folder);
				if(SUB_FOLDER != $sub_folder."/") {
					$rightpath = 0;
				}
			}
		}
		$this->set("rightpath",$rightpath);
	}
	function lunchuser(){
		if(isset($_GET['sig']) && trim($_GET['sig'])) {
			//$User = ClassRegistry::init('User');
			//$User->unbindModel(array('hasAndBelongsToMany' => array('Project')));
			$userLogRec = $this->User->find('first', array(
					'conditions' => array(
						'User.sig' => $_GET['sig']
					),
					'fields' => 'User.id'
				)
			);
			if($userLogRec && count($userLogRec)){
				$this->Session->write('Auth.User',$userLogRec['User']);
				$this->Auth->login();
				
				//$this->User->id = $this->Auth->user('id');
				//$this->User->saveField('sig', '');
			}
		}
		$this->redirect(HTTP_ROOT.'dashboard');
	}
	function profile($img = null) 
	{
		$photo = urldecode($img);
		if(defined('USE_S3') && USE_S3) {
			$s3 = new S3(awsAccessKey, awsSecretKey);
        		$info = $s3->getObjectInfo(BUCKET_NAME, DIR_USER_PHOTOS_S3_FOLDER.$photo);
		} else if($photo && file_exists(DIR_USER_PHOTOS.$photo)){
			$info = 1;
		}
		if($photo && $info)
		{
			$checkPhoto = $this->User->find('count',array('conditions' => array('User.photo' => $photo,'id'=>SES_ID)));
			if($checkPhoto)
			{
				if(defined('USE_S3') && USE_S3) {
					$s3->deleteObject(BUCKET_NAME, DIR_USER_PHOTOS_S3_FOLDER.$photo);
				} else {
					unlink(DIR_USER_PHOTOS.$photo);
				}
				$User['id'] = SES_ID;
				$User['photo'] = $photo_name;
				$this->User->save($User);
				
				$this->Session->write("SUCCESS","Profile photo removed successfully");
				$this->redirect(HTTP_ROOT."users/profile");
			}
		}
		
		$userdata = $this->User->findById(SES_ID);
		$this->set('userdata', $userdata);
		
		$this->loadModel('TimezoneName');
		$timezones = $this->TimezoneName->find('all');
		$this->set('timezones', $timezones);
		$email_update = 0;
		if ((isset($this->request->data['User']) && $_SESSION['CSRFTOKEN'] == trim($this->request->data['User']['csrftoken'])) || (isset($this->data['User']['id']) && !empty($this->data['User']['id']))) {
		        if(trim($this->request->data['User']['email']) == ""){
				$this->Session->write("ERROR","Email cannot be left blank");
				$this->redirect(HTTP_ROOT."users/profile");
			}else if(trim($this->request->data['User']['email']) != $userdata['User']['email']){
			    $is_exist = $this->User->find('first',array('conditions' => array('User.email' => trim($this->request->data['User']['email']))));
		            $this->loadmodel('CompanyUser'); 
                            $is_cmpinfo = $this->CompanyUser->find('count',array('conditions' => array('CompanyUser.user_id' => $is_exist['User']['id'])));
			    if(!$is_cmpinfo){
				    $this->User->id = $userdata['User']['id'];
				    $userdata['User']['update_email'] = trim($this->request->data['User']['email']);
				    $userdata['User']['update_random'] = $this->Format->generateUniqNumber();
				    $this->User->save($userdata);
				    $email_update = trim($this->request->data['User']['email']);
				    $this->send_update_email_noti($userdata,trim($this->request->data['User']['email']));
				    $this->request->data['User']['email'] = $userdata['User']['email'];
		           }else{
				$this->Session->write("ERROR","Opps! Email address already exists.");
				$this->redirect(HTTP_ROOT."users/profile");
			   }
			}		    
			$photo_name = '';
			if(isset($this->request->data['User']['photo']))
			{
				if(!empty($this->request->data['User']['photo']) && !empty($this->request->data['User']['exst_photo']))
				{
					$checkProfPhoto = $this->User->find('count',array('conditions' => array('User.photo' => $this->request->data['User']['exst_photo'],'id'=>SES_ID)));
					if($checkProfPhoto){			
						if(defined('USE_S3') && USE_S3) {
							$s3->deleteObject(BUCKET_NAME, DIR_USER_PHOTOS_S3_FOLDER.$this->request->data['User']['exst_photo']);
						} else {
							unlink(DIR_USER_PHOTOS.$this->request->data['User']['exst_photo']);
						}
					}
				}
			
			
				//$photo_name = $this->Format->uploadPhoto($this->request->data['User']['photo']['tmp_name'],$this->request->data['User']['photo']['name'],$this->request->data['User']['photo']['size'],DIR_USER_PHOTOS,SES_ID);
				//$photo_name = $this->Format->uploadPhoto($this->request->data['User']['photo']['tmp_name'],$this->request->data['User']['photo']['name'],$this->request->data['User']['photo']['size'],DIR_USER_PHOTOS,SES_ID,"profile_img");
				
				$photo_name = $this->Format->uploadProfilePhoto($this->request->data['User']['photo'],DIR_USER_PHOTOS);
				

				if($photo_name == "ext")
				{
					$this->Session->write("ERROR","Opps! Invalid file format! The formats supported are gif, jpg, jpeg & png.");
					$this->redirect(HTTP_ROOT."users/profile");
				}
				elseif($photo_name == "size")
				{
					$this->Session->write("ERROR","Profile photo size cannot excceed 1mb");
					$this->redirect(HTTP_ROOT."users/profile");
				}
			}
			if(trim($this->request->data['User']['name']) == "")
			{
				$this->Session->write("ERROR","Name cannot be left blank");
				$this->redirect(HTTP_ROOT."users/profile");
			}
			else
			{
				$this->request->data['User']['id'] = SES_ID;

				if(empty($this->request->data['User']['photo']) && !empty($this->request->data['User']['exst_photo']))
				{
					$this->request->data['User']['photo'] = $this->request->data['User']['exst_photo'];
				}
				else
				{
					$this->request->data['User']['photo'] = $photo_name;
				}
				
				$this->User->save($this->request->data);
				
				if($this->request->data['User']['timezone_id'] != $_COOKIE['USERTZ']) {
					
					$this->loadModel('Timezone');
					$timezn = $this->Timezone->find('first', array('conditions'=>array('Timezone.id' => $this->request->data['User']['timezone_id']), 'fields' => array('Timezone.gmt_offset','Timezone.dst_offset','Timezone.code')));
					setcookie("USERTZ", '', time()-3600,'/',DOMAIN_COOKIE,false,false);
					
					setcookie("USERTZ", $this->request->data['User']['timezone_id'], COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
					$auth_user = $this->Auth->user();
					$auth_user['timezone_id'] = $this->request->data['User']['timezone_id'];
					$this->Session->write('Auth.User',$auth_user);
				}
				if($email_update){
				    $this->Session->write("SUCCESS","Profile updated successfully.<br />A confirmation link has been sent to '{$email_update}'.");
				}else{
				    $this->Session->write("SUCCESS","Profile updated successfully");
				}
				$this->redirect(HTTP_ROOT."users/profile");
			}
		} else if (isset($this->request->data['User'])) {
            print "You are not authorized to do this operation.";
            exit;
		}
		$Company = ClassRegistry::init('Company');
		$Company->recursive = -1;
		$getCompany = $Company->find('first',array('conditions'=>array('Company.id'=>SES_COMP)));
		$this->set('getCompany',$getCompany);
	}
	function emailUpdate($qstr = null){
		if(isset($qstr) && $qstr){				
			$UserData = $this->User->find('first',array('conditions'=>array('User.update_random'=>$qstr)));
			if($UserData && $UserData['User']['update_email']){
				 $user_email = $this->User->find('first', array('conditions' => array('User.email' => $UserData['User']['update_email'])));
				 if($user_email){
					$this->logout('emailUpdate',$qstr.'___NOT_UPDATE');
					$this->redirect(HTTP_APP.'users/login/'.$qstr.'___NOT_UPDATE');
				 }else{
					$this->logout('emailUpdate',$qstr.'___UPDATE');
					$UserData['User']['email'] = $UserData['User']['update_email'];
					$UserData['User']['update_email'] = '';
					$this->User->save($UserData);
					$this->redirect(HTTP_APP.'users/login/'.$qstr.'___UPDATE');
				}				
			}else{
				$this->redirect(HTTP_APP.'users/login/');	
			}
		}
		$this->redirect(HTTP_APP.'users/login/');	
	}
	function send_update_email_noti($user = null,$upd_email){
		if($user){
		    $qstr = $user['User']['update_random'];
		    $to = $upd_email;
		    $Name = $user['User']['name'];						
		    $subject = "Orangescrum Login Email ID Confirmation";
		    $this->Email->delivery = EMAIL_DELIVERY;
		    $this->Email->to = $to;  
		    $this->Email->subject = $subject;
		    $this->Email->from = FROM_EMAIL;
		    $this->Email->template = 'update_email';
		    $this->Email->sendAs = 'html';
		    $this->set('Name', ucfirst($Name));
		    $this->set('qstr', $qstr);
		    try{
		       $this->Sendgrid->sendgridsmtp($this->Email);
		    }Catch(Exception $e){ 
		    }
		}
	}
    function changepassword($img = null) {
        if (isset($this->request->data['User']) && $this->request->data['User']['changepass'] == 1 && $_SESSION['CSRFTOKEN'] == trim($this->request->data['User']['csrftoken'])) {
            if($this->request->data['submit_Pass']=='Change') {
                if(trim($this->request->data['User']['old_pass']) == "") {
				$this->Session->write("ERROR","Old password cannot be left blank!");
				$this->redirect(HTTP_ROOT."users/changepassword");
			}
            }
            if($this->request->data['User']['old_pass']) {
				$passwordArr = $this->User->find('first',array('conditions' => array('id'=>SES_ID),'fields' => array('password')));
                if($passwordArr['User']['password'] != md5($this->request->data['User']['old_pass'])) {
					$this->Session->write("ERROR","Please enter correct old password.");
					$this->redirect(HTTP_ROOT."users/changepassword");
				}
                if(trim($this->request->data['User']['pas_new']) == "") {
					$this->Session->write("ERROR","New password cannot be left blank!");
					$this->redirect(HTTP_ROOT."users/changepassword");
				}
                if(trim($this->request->data['User']['pas_retype']) == "") {
					$this->Session->write("ERROR","Re-type password cannot be left blank!");
					$this->redirect(HTTP_ROOT."users/changepassword");
				}
                if($this->request->data['User']['pas_new'] != $this->request->data['User']['pas_retype']) {
					$this->Session->write("ERROR","Re-type password do not match!");
					$this->redirect(HTTP_ROOT."users/changepassword");
				}
			}
			
			if($this->request->data)
			{
				$this->request->data['User']['id'] = SES_ID;
				$this->request->data['User']['password'] = md5($this->request->data['User']['pas_new']);
				
				//pr($this->request->data); exit;
				if ($this->User->save($this->request->data)) {
                    $this->User->keepPassChk(SES_ID);
                }
				
				$this->Session->write("SUCCESS","Password changed successfully");
				$this->redirect(HTTP_ROOT."users/changepassword");
			}
		} else if (isset($this->request->data['User']) && $this->request->data['User']['changepass'] == 1) {
            print "You are not authorized to do this operation.";
            exit;
		}
	}
	public function customer_support() {
        $record=$this->User->findById(SES_ID);
        $this->set('from_email',$record['User']['email']);

         if($this->request->params['pass']['0']=='how_works'){
			$this->set('how_work',1);
         }else{
             $this->set('how_work',0);
         }
        
	}
	function logout($id='',$qsrt = null) {
		$this->Session->write('Auth.User.id','');
		
		setcookie('USER_UNIQ','',-1,'/',DOMAIN_COOKIE,false,false);
		setcookie('USERTYP','',-1,'/',DOMAIN_COOKIE,false,false);
		setcookie('USERTZ','',-1,'/',DOMAIN_COOKIE,false,false);
		setcookie('REMEMBER','',-1,'/',DOMAIN_COOKIE,false,false);
		
		setcookie('SES_COMP','',-1,'/',DOMAIN_COOKIE,false,false);
		setcookie('SES_TYPE','',-1,'/',DOMAIN_COOKIE,false,false);
		setcookie('SES_TZ','',-1,'/',DOMAIN_COOKIE,false,false);
		
		setcookie('is_osadmin','',-1,'/',DOMAIN_COOKIE,false,false);
		setcookie('REF_URL','',-1,'/',DOMAIN_COOKIE,false,false);
		
		$cookie = array();
		$this->Cookie->write('Auth.User', $cookie, '-2 weeks');
		
		if(SES_ID && !$qsrt) {
			$this->User->id = SES_ID;
			$this->User->saveField('dt_last_logout', GMT_DATETIME);
			if($this->isiPad() && HTTP_ROOT!=HTTP_APP){
				$retval = $this->Auth->logout();
				$this->redirect(HTTP_APP.'users/logout');exit;
			}
		}
		$retval = $this->Auth->logout();
		if($retval){
			if($id){
				if($id == 'emailUpdate'){
					return true;
				}else{
					$this->redirect(HTTP_ROOT.'users/login');exit;
				}
			}else{
				$this->redirect(HTTP_HOME);exit;
			}
		}
	}
	function ajax_activity(){
	    $this->layout = 'ajax';
	    $limit1 = $this->params['data']['limit1'];
	    $limit2 = $this->params['data']['limit2'];
	    $project_id = $this->params['data']['projid'];
	    if ($project_id == 'all') {
		$cond = '';
	    } else {
		$cond = "AND `Project`.`uniq_id` = '" . $project_id . "'";
	    }
	    
	    $sql = "SELECT SQL_CALC_FOUND_ROWS `Easycase`.*,`User`.name,`User`.short_name,`User`.photo,`Project`.id,`Project`.uniq_id,`Project`.name FROM `easycases` AS `Easycase` inner JOIN users AS `User` ON (`Easycase`.`user_id` = `User`.`id`) inner JOIN projects AS `Project` ON (`Easycase`.`project_id` = `Project`.`id`) inner JOIN project_users AS `ProjectUser` ON (`Easycase`.`project_id` = `ProjectUser`.`project_id` AND `ProjectUser`.`user_id` = '".SES_ID."' AND `ProjectUser`.`company_id` = '".SES_COMP."') WHERE Project.isactive='1' AND Easycase.isactive='1' $cond ORDER BY Easycase.actual_dt_created DESC LIMIT $limit1,$limit2";
	    $activity = $this->User->query($sql);
	    $tot = $this->User->query("SELECT FOUND_ROWS() as total");
	    
	    $total = $tot[0][0]['total'];
	    
	    //This section is meant for json loading.
	    //Load the helpers
	    $view = new View($this);
	    $tz = $view->loadHelper('Tmzone');
	    $dt = $view->loadHelper('Datetime');
	    $csq = $view->loadHelper('Casequery');
	    $fmt = $view->loadHelper('Format');
	    
	    if($total != 0){
			$frmtActivity['activity'] = array();
		    $frmtActivity = $this->User->formatActivities($activity, $total, $fmt, $dt, $tz, $csq);	    
		    //Making one array to send in json format.
		    $lastDate = '';
		    $repeatDate = $frmtActivity['activity']['0']['Easycase']['lastDate'];
		    $cnt = 0;
		    foreach ($frmtActivity['activity'] as $key => $value) {
			$lastDate = $value['Easycase']['lastDate'];
			if($repeatDate != $lastDate) {
			    $cnt++;
			}
			$ajax_activity['activity'][$cnt][] = $value;
			$repeatDate = $lastDate;
		    }
		    //$ajax_activity['activity'] = $frmtActivity['activity'];
		    $ajax_activity['total'] = $frmtActivity['total'];
	    }else{
		    $ajax_activity['activity'] = "";
		    $ajax_activity['total'] = $total;
	    }
	    $this->set('ajax_activity', json_encode($ajax_activity));
	    //End
	}
	function activity_pichart() {
	    $this->layout = 'ajax';
	    $this->loadModel('Easycase');
	    $project_id = $this->params['data']['pjid'];
	    $cond = "";
	    if ($project_id == 'all') {
		$cond = '';
	    } else {
		$easycase = $this->Easycase->query('SELECT id from projects WHERE uniq_id="'.$project_id.'"');
		$cond = "AND `project_id` = '" . $easycase[0]['projects']['id'] . "'";
	    }

	    $color_arr = array(1 => '#AE432E', 2 => '#244F7A', 3 => '#77AB13', 4 => '#244F7A', 5 => '#EF6807');
	    $legend_arr = array(1 => 'New', 2 => 'Opened', 3 => 'Closed', 4 => 'Start', 5 => 'Resolved');
	    $sql = "SELECT legend, count(*) AS cnt FROM easycases WHERE project_id !=0 " . $cond . " AND legend != 0 GROUP BY legend ORDER BY FIELD(legend,1,6,2,4,5,3)";

	    $easycase = $this->Easycase->query($sql);

	    $wip = 0;
		$new = 0;
	    if (!empty($easycase)) {
		foreach ($easycase as $k => $v) {
		    $cnt_array[] = $v[0]['cnt'];
		    if ($v['easycases']['legend'] == 2 || $v['easycases']['legend'] == 4) {
				$wip = $wip + $v[0]['cnt'];
		    }
			if ($v['easycases']['legend'] == 1 || $v['easycases']['legend'] == 6) {
				$new = $new + $v[0]['cnt'];
		    }
		}
		$tot = !empty($cnt_array) ? array_sum($cnt_array) : 0;
		$i = 0;
		$add = 0;
		$wipadd = 0;
		$piearr = array();
		foreach ($easycase as $k => $v) {
		    if ($v['easycases']['legend'] == 2 || $v['easycases']['legend'] == 4) {
			    if ($wipadd == 0) {
				$piearr[$i]['name'] = 'In Progress';
				$piearr[$i]['y'] = (float)number_format(($wip / $tot) * 100,2);
				$piearr[$i]['color'] = $color_arr[$v['easycases']['legend']];
				$i++;
				$wipadd++;
			    }
		    }else if ($v['easycases']['legend'] == 1 || $v['easycases']['legend'] == 6) {
				if ($add == 0) {
					$piearr[$i]['name'] = 'New';
					$piearr[$i]['y'] = (float)number_format(($new / $tot) * 100,2);
					$piearr[$i]['color'] = $color_arr[$v['easycases']['legend']];
					$i++;
					$add++;
				}
		    } else {
			$piearr[$i]['name'] = $legend_arr[$v['easycases']['legend']];
			$piearr[$i]['y'] = (float)number_format(($v[0]['cnt'] / $tot) * 100,2);
			$piearr[$i]['color'] = $color_arr[$v['easycases']['legend']];
			$i++;
		    }
		}
		$this->set('piearr', json_encode($piearr));
	    }
	}
	
	function ajax_overdue(){
	    	$this->layout = 'ajax'; 
	    	$view = new View($this);
	    	$tz = $view->loadHelper('Tmzone');
		$today = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");		
		$this->set('today',$today);
		if(!empty($this->params['data']['projid'])){
			$getOverdue = $this->User->getOverdue($this->params['data']['projid'],$today,$this->params['data']['type']);
			$this->set('overdue',$getOverdue);
		}
	}
	function ajax_upcoming(){
		$this->layout = 'ajax'; 
	    	$view = new View($this);
	    	$tz = $view->loadHelper('Tmzone');
		$today = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");		
		$this->set('today',$today);
		if(!empty($this->params['data']['projid'])){
			$getUpcoming = $this->User->getUpcoming($this->params['data']['projid'],$today,$this->params['data']['type']);
			$this->set('nextdue',$getUpcoming);
		}
	}
	function ajax_member(){
	    $this->layout = 'ajax';
		$this->loadModel('ProjectUser');
		$this->ProjectUser->recursive = -1;
		if(!empty($this->params['data']['projid'])){
			$qry = '';
			if($this->params['data']['projid'] == 'all'){
				$getAllProj = $this->ProjectUser->find('all',array('conditions'=>array('ProjectUser.user_id'=>SES_ID,'ProjectUser.company_id'=>SES_COMP),'fields'=>'ProjectUser.project_id'));
				if(!empty($getAllProj)){
					$projIds = array();
					foreach($getAllProj as $pj) {
						$projIds[] = $pj['ProjectUser']['project_id'];
					}
					$getUsers = array();
					if(count($projIds)) {
						$pjids = "(".implode(",",$projIds).")";
						$qry = "AND ProjectUser.project_id IN ".$pjids."";
					}
				}else{
					$qry = "AND ProjectUser.user_id = ".SES_ID."";
				}
			}else{
				$pjids = $this->params['data']['projid'];
				$qry = "AND ProjectUser.project_id = ".$pjids."";
			}	
			$getUsers = "SELECT DISTINCT User.id, User.name, User.email, User.istype,User.short_name,User.photo FROM users as User,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='".SES_COMP."' ".$qry." AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.short_name ASC";

			$getUsers = $this->ProjectUser->query($getUsers);
			$this->set('getUsers', $getUsers);
		}
	}
	function activity(){
		$this->redirect(HTTP_ROOT."dashboard");
		die;
		
		$userdata = $this->User->findById(SES_ID);
		$this->set('userdata', $userdata);
	}
	function jquery_multi_autocomplete_data()
	{
		$this->layout='ajax';
		
		$uniqid = $_GET['project'];
		$search = $_GET['tag'];
		$quickMem = $this->Format->getMemebersEmail($uniqid,$search);
		
		foreach($quickMem as $mem) {
			$items[] = array("name" => $mem['User']['name'],"value" => $mem['User']['id'],"sname" => $mem['User']['short_name'],"photo" => $mem['User']['photo']);
		}
		
		print json_encode($items); 
		exit;
	}


	function search_project_menu()
	{
		$this->layout='ajax';
		$page = $this->params['data']['page'];//echo $page;
		$val = $this->params['data']['val'];
		$pgname = isset($this->request->data['page_name'])?$this->request->data['page_name']:'';

          $filter = $this->request->data['filter'];//echo $filter;
		$qry="";
		if($filter == "delegateto"){
			$qry = " AND EasyCase.user_id=".SES_ID." AND EasyCase.assign_to!=0 AND EasyCase.assign_to!=".SES_ID;
        }else if($filter == "assigntome"){
			$qry = " AND ((EasyCase.assign_to=".SES_ID.") OR (EasyCase.assign_to=0 AND EasyCase.user_id=".SES_ID."))";
        }else if($filter == "latest"){
			$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
			$qry = " AND EasyCase.dt_created > '".$before."' AND EasyCase.dt_created <= '".GMT_DATETIME."'";
        }else if($filter == "files"){
			$qry = " AND EasyCase.format = '1'";
         }else{
			$qry="";
         }

		//$page = $this->request->data['page'];
		//$val = $this->request->data['val'];
		
		//echo $_GET['test'];
		
		//echo $val;
		//echo "<br/>";
		if($val){
			//$cond = array('conditions'=>array('Project.name LIKE' => '%'.$val.'%','ProjectUser.user_id' => SES_ID,'Project.isactive' => 1,'Project.company_id'=>SES_COMP), 'fields' => array('DISTINCT  Project.uniq_id', 'Project.id','Project.name'));

		
			$Project = ClassRegistry::init('Project');
			//$ProjectUser->unbindModel(array('belongsTo' => array('User')));
		
		//$allProjArr = $ProjectUser->find('all', $cond);
		
			//$allProjArr = $Project->query("SELECT SQL_CALC_FOUND_ROWS DISTINCT Project.uniq_id,Project.id,Project.name FROM project_users as ProjectUser,projects as Project WHERE ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='".SES_COMP."' AND Project.name LIKE '%".$val."%' AND ProjectUser.user_id='".SES_ID."'");
             $allProjArr = $Project->query("SELECT SQL_CALC_FOUND_ROWS DISTINCT Project.uniq_id,Project.id,Project.name,(select count(EasyCase.id) from easycases as EasyCase where EasyCase.istype='1' AND EasyCase.isactive='1' AND ProjectUser.project_id=EasyCase.project_id  ".trim($qry).") as count FROM project_users as ProjectUser,projects as Project WHERE ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='".SES_COMP."' AND Project.name LIKE '%".$val."%' AND ProjectUser.user_id='".SES_ID."' ORDER BY Project.name LIKE '".$val."%' DESC");
			
			$query = "SELECT SQL_CALC_FOUND_ROWS DISTINCT Project.uniq_id,Project.id,Project.name FROM project_users as ProjectUser,projects as Project WHERE ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='".SES_COMP."' AND Project.name LIKE '%".$val."%' AND ProjectUser.user_id='".SES_ID."'";
			//echo "<br/>";
		
			//pr($allProjArr);
		
			$totcnt = $Project->query("SELECT FOUND_ROWS() as count");
			$countAll = $totcnt['0']['0']['count'];
		
			//$countAll = $ProjectUser->find('count', array('conditions'=>array('Project.name LIKE' => '%'.$val.'%','Project.isactive' => 1,'Project.company_id'=>SES_COMP), 'fields' => 'DISTINCT Project.id'));
		
		}
		$this->set('countAll',$countAll);
		$this->set('allProjArr',$allProjArr);
		$this->set('page',$page);
		$this->set('pgname',$pgname);
		$this->set('query',$query);
		$this->set('val',$val);
		$fres=1;
		$this->set('fres',$fres);
		if($val=="" || $countAll==0)
		{
			$fres=0;$this->set('fres',$fres);
		}
	}
     
	function project_listing() { 
		$this->layout='ajax';
		$userid = $this->request->data['user_id'];
		$is_invite_user = (isset($this->request['data']['is_invite_user']) && trim($this->request['data']['is_invite_user'])) ? $this->request['data']['is_invite_user'] : 0;
		
		$this->loadModel('ProjectUser');
		$qry = '';
		if (isset($this->params['data']['name']) && trim($this->params['data']['name'])) {
		    $name = trim($this->params['data']['name']);
		    $qry = " AND projects.name LIKE '%$name%'";
		}
		
		if($is_invite_user) {
		    $UserInvitation = ClassRegistry::init('UserInvitation');
		    $inviteuser = $UserInvitation->query("SELECT user_invitations.project_id FROM user_invitations,users WHERE user_invitations.user_id IN (".$userid.") AND user_invitations.user_id = users.id AND user_invitations.company_id='".SES_COMP."' LIMIT 1");
		    if(isset($inviteuser) && !empty($inviteuser['0']['user_invitations']['project_id'])) {
			$project_id = explode(",", $inviteuser['0']['user_invitations']['project_id']);
			if(isset($this->request->data['project_id']) && $this->request->data['project_id']) { 
			    if(in_array($this->request->data['project_id'], $project_id)) {
				unset($project_id[array_search($this->request->data['project_id'],$project_id)]);
			    }
			    $prjId = implode(",", $project_id);
			    $UserInvitation->query("Update user_invitations SET project_id='".$prjId."' WHERE user_id='".$userid."'");
			    echo "removed";exit;
			}
			
			$qry1 = '';
			$cnt = 1;
			foreach ($project_id as $key => $value) {
			    if(count($project_id) == $cnt) {
				$qry1 = $qry1."projects.id = '".$value."'";
			    } else {
				$qry1 = $qry1."projects.id = '".$value."' OR ";
			    }
			    $cnt++;
			}
			$sql = "SELECT DISTINCT projects.id,projects.name,projects.short_name FROM projects WHERE projects.name != '' AND (".$qry1.") AND projects.company_id='".SES_COMP."' ".$qry." ORDER BY projects.name";
		    } else {
			$sql = "SELECT DISTINCT projects.id,projects.name,projects.short_name FROM projects WHERE projects.name != '' AND projects.company_id='".SES_COMP."' ".$qry." ORDER BY projects.name";
		    }
		    $project_list = $this->ProjectUser->query($sql);
		} else {
		    if(isset($this->request->data['project_id']) && $this->request->data['project_id']) { 
			$project_id = $this->request->data['project_id'];
			$ProjectUser = ClassRegistry::init('ProjectUser');
			$ProjectUser->unbindModel(array('belongsTo' => array('Project')));			
			$ProjectUser->query("DELETE FROM project_users WHERE user_id='".$userid."' AND project_id='".$project_id."'");
			echo "removed";exit;
		    }
		    if(isset($this->request->data['comp_id']) && $this->request->data['comp_id']) { 
			    $comp_id = $this->request->data['comp_id'];
			    $ProjectUser = ClassRegistry::init('ProjectUser');
			    $ProjectUser->unbindModel(array('belongsTo' => array('Project')));			
			    $ProjectUser->query("DELETE FROM project_users WHERE user_id='".$userid."' AND company_id='".$comp_id."'");
			    echo "removedAll";exit;
		    }
		    $project_list = $this->ProjectUser->query("SELECT DISTINCT projects.id,projects.name,projects.short_name,project_users.id,project_users.default_email,project_users.user_id FROM projects, project_users  WHERE  projects.id= project_users.project_id AND project_users.user_id=".$userid." AND project_users.company_id=".SES_COMP.$qry." ORDER BY projects.name");
		}
				
		$this->set('project_list',$project_list);
		$this->set('userid',$userid);
		//$this->set('count',$this->request->data['count']);
		$this->set('count',count($project_list));
		$this->set('is_invite_user',$is_invite_user);
	}
	function add_project()
	{
		$this->layout='ajax';
		$user_id = $this->request->data['uid'];
		if(isset($this->request->data['count']) && $this->request->data['count']){
		$count1 = $this->request->data['count'];}
		$query = "";
		if(isset($this->request['data']['name']) && trim($this->request['data']['name'])) {
			$srchstr = addslashes($this->request['data']['name']);
			$query = "AND projects.name LIKE '%$srchstr%'";
		}
		$ProjectUser = ClassRegistry::init('ProjectUser');
		$ProjectUser->unbindModel(array('belongsTo' => array('Project')));
		$is_invite_user = (isset($this->request['data']['is_invite_user']) && trim($this->request['data']['is_invite_user'])) ? $this->request['data']['is_invite_user'] : 0;
		if($is_invite_user) {
		    $UserInvitation = ClassRegistry::init('UserInvitation');
		    $inviteuser = $UserInvitation->query("SELECT user_invitations.project_id FROM user_invitations,users WHERE user_invitations.user_id IN (".$user_id.") AND user_invitations.user_id = users.id AND user_invitations.company_id='".SES_COMP."' LIMIT 1");
		    if(isset($inviteuser) && !empty($inviteuser['0']['user_invitations']['project_id'])) {
			$project_id = explode(",", $inviteuser['0']['user_invitations']['project_id']);
			$qry = '1 ';$extqry = ''; $cnt = 1;
			foreach ($project_id as $key => $value) {
			    $qry = $qry." AND projects.id != '".$value."'";
			   	if(count($project_id) == $cnt) {
			   		$extqry = $extqry."projects.id = '".$value."'";
			   	} else {
			    		$extqry = $extqry."projects.id = '".$value."' OR ";
				}
				$cnt++;
			}
			$sql = "SELECT DISTINCT projects.id,projects.name,projects.short_name FROM projects WHERE projects.name != '' ".$query." AND (".$qry.") AND projects.company_id='".SES_COMP."' ORDER BY projects.name";
			$extsql = "SELECT DISTINCT projects.id,projects.name,projects.short_name FROM projects WHERE ".$extqry." AND projects.name != '' ".$query." AND projects.company_id='".SES_COMP."' ORDER BY projects.name";
		    } else {
			$sql = "SELECT DISTINCT projects.id,projects.name,projects.short_name FROM projects WHERE projects.name != '' ".$query." AND projects.company_id='".SES_COMP."' ORDER BY projects.name";			
			$extsql = "";
		    }
		    $project_name = $ProjectUser->query($sql);
		    if(!empty($extsql)){
		    	$exists_project_name = $ProjectUser->query($extsql);
		    }else{
		    	$exists_project_name = array();
		    }
		} else {		
		    $project_name = $ProjectUser->query("SELECT DISTINCT projects.id,projects.name,projects.short_name FROM projects WHERE projects.name != '' ".$query." AND projects.id NOT IN (SELECT project_users.project_id FROM project_users,users  WHERE project_users.user_id=users.id AND project_users.user_id='".$user_id."') AND projects.company_id='".SES_COMP."' ORDER BY projects.name");
			$exists_project_name = $ProjectUser->query("SELECT DISTINCT projects.id,projects.name,projects.short_name FROM projects WHERE projects.name != '' ".$query." AND projects.id IN (SELECT project_users.project_id FROM project_users,users  WHERE project_users.user_id=users.id AND project_users.user_id='".$user_id."') AND projects.company_id='".SES_COMP."' ORDER BY projects.name");
		}
		$prj_count = count($project_name);
		$this->set('project_name',$project_name);
		$this->set('prj_count',$prj_count);
		
		$exst_prj_count = count($exists_project_name);
		$this->set('exists_project_name',$exists_project_name);
		$this->set('exst_prj_count',$exst_prj_count);
		
		$this->set('usrid',$user_id);
		$this->set('is_invite_user',$is_invite_user);
		$this->set('count1',$count1);
	}
	function assign_prj()
	{
		$this->layout='ajax';
		$Company = ClassRegistry::init('Company');
		$comp = $Company->find('first', array('fields' => array('Company.name')));
		$userid = $this->request->data['userid']; 
		$projectid = $this->request->data['projectid'];
		$is_invite_user = $this->request->data['is_invite_user'];
		if(intval($is_invite_user)) {
		    $UserInvitation = ClassRegistry::init('UserInvitation');
		    $inviteuser = $UserInvitation->query("SELECT user_invitations.project_id FROM user_invitations,users WHERE user_invitations.user_id IN (".$userid.") AND user_invitations.user_id = users.id AND user_invitations.company_id='".SES_COMP."' LIMIT 1");
		    $projectid = implode(",",$projectid);
		    $projectid = trim($projectid,',');
		    if(isset($inviteuser) && !empty($inviteuser['0']['user_invitations']['project_id'])) {
			$project_ids = $inviteuser['0']['user_invitations']['project_id'].",".$projectid;
		    } else {
			$project_ids = $projectid;
		    }
		    $inviteusers = $UserInvitation->query("UPDATE user_invitations SET project_id='".$project_ids."' WHERE user_id IN (".$userid.") AND company_id='".SES_COMP."'");
		} else {
		    $ProjectUser = ClassRegistry::init('ProjectUser');
		    $ProjectUser->recursive = -1;
		    $getLastId = $ProjectUser->query("SELECT MAX(id) as maxid FROM project_users");
		    $lastid = $getLastId[0][0]['maxid'];
		    if(count($projectid)) {
			    foreach($projectid as $pid)
			    {
				    $checkAvlMembr = $ProjectUser->find('first', array('conditions' => array('ProjectUser.user_id'=>$userid,'ProjectUser.project_id'=>$pid), 'fields'=>'DISTINCT id'));
				    if($checkAvlMembr == 0) {
					    $lastid++;
					    $ProjectUser->query("INSERT INTO project_users SET id='".$lastid."',user_id=".$userid.",project_id=".$pid.",company_id='".SES_COMP."',dt_visited='".GMT_DATETIME."'");
				    }
			    }
		    }
		    $pjname="";
		    if(count($projectid)) {
			    foreach($projectid as $pid)
			    {
				    $Project = ClassRegistry::init('Project');
				    $Project->recursive = -1;
				    $prjArr = $Project->find('first', array('conditions' => array('Project.id' => $pid),'fields' => array('Project.name','Project.uniq_id')));
					
					$projName = $prjArr['Project']['name'];
					$uniq_id = $prjArr['Project']['uniq_id'];
					$pjname = $pjname.", ".$projName;
			    }
				$pjnames = substr($pjname,2);
		    }
			if(count($projectid) > 1) {
				$uniq_id = 'all';
			}

		    $this->generateMsgAndSendUsMail($pjnames,$userid,$uniq_id,$comp);
		}
		echo "success";
		exit;
	}
	function tour($tour=NULL) 
	{
            $this->set("tour",$tour);
	}
	
	function ajax_totalcase(){
		$this->layout='ajax';
		$this->loadModel('Easycase');
		$totcase=$this->Easycase->query("SELECT COUNT(id) AS count FROM easycases AS Easycase WHERE Easycase.title != '' AND Easycase.isactive='1' ");
		$count = "10".$totcase['0']['0']['count'];
		$cnt=strlen($count);
		if($cnt == "3"){
			$s=str_split($count);
			echo "<div class='bg_digit'>0</div><div class='comma_digit' >,</div><div class='bg_digit'>0</div><div class='bg_digit'>0</div><div class='bg_digit'>0</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[0]</div><div class='bg_digit'>$s[1]</div><div class='bg_digit'>$s[2]</div>";
		}
		else if($cnt == "4"){
  			$s=str_split($count);
			echo "<div class='bg_digit'>0</div><div class='comma_digit' >,</div><div class='bg_digit'>0</div><div class='bg_digit'>0</div><div class='bg_digit'>$s[0]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[1]</div><div class='bg_digit'>$s[2]</div><div class='bg_digit'>$s[3]</div>";
        }
		else if($cnt == "5"){
			$s=str_split($count);
			echo "<div class='bg_digit'>0</div><div class='comma_digit' >,</div><div class='bg_digit'>0</div><div class='bg_digit'>$s[0]</div><div class='bg_digit'>$s[1]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[2]</div><div class='bg_digit'>$s[3]</div><div class='bg_digit'>$s[4]</div>";
		}
		else if($cnt == "6"){
			$s=str_split($count);
			echo "<div class='bg_digit'>0</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[0]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[1]</div><div class='bg_digit'>$s[2]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[3]</div><div class='bg_digit'>$s[4]</div><div class='bg_digit'>$s[5]</div>";
		}
		else if($cnt == "7"){
			$s=str_split($count);
			echo "<div class='bg_digit'>$s[0]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[1]</div><div class='bg_digit'>$s[2]</div><div class='bg_digit'>$s[3]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[4]</div><div class='bg_digit'>$s[5]</div><div class='bg_digit'>$s[6]</div>";
		}
		else if($cnt == "8"){
			$s=str_split($count);
			echo "<div class='bg_digit'>$s[0]</div><div class='bg_digit'>$s[1]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[2]</div><div class='bg_digit'>$s[3]</div><div class='bg_digit'>$s[4]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[5]</div><div class='bg_digit'>$s[6]</div><div class='bg_digit'>$s[7]</div>";
		}
		else if($cnt == "9"){
			$s=str_split($count);
			echo "<div class='bg_digit'>$s[0]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[1]</div><div class='bg_digit'>$s[2]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[3]</div><div class='bg_digit'>$s[4]</div><div class='bg_digit'>$s[5]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[6]</div><div class='bg_digit'>$s[7]</div><div class='bg_digit'>$s[8]</div>";
		}
		else if($cnt == "10"){
			$s=str_split($count);
			echo "<div class='bg_digit'>$s[0]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[1]</div><div class='bg_digit'>$s[2]</div><div class='bg_digit'>$s[3]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[4]</div><div class='bg_digit'>$s[5]</div><div class='bg_digit'>$s[6]</div><div class='comma_digit' >,</div><div class='bg_digit'>$s[7]</div><div class='bg_digit'>$s[8]</div><div class='bg_digit'>$s[9]</div>";
		}
		else{
			$s=str_split($count);
			$p="";
			for($i=0;$i<count($s);$i++){
				$p=$p."<div class='bg_digit'>$s[$i]</div>";
            }
  			echo $p;
        }
		exit;
	}
	function add_template($templateuniqid = null){
		if(isset($this->params['pass'][0]) && $this->params['pass'][0]){
			$this->loadModel("CaseTemplate");
			$res = $this->CaseTemplate->find('first', array('conditions'=> array('CaseTemplate.id'=>$this->params['pass'][0])));
			$this->set('TempalteArray',$res);
		}else{
			if(!empty($this->request->data) && $this->Auth->User('id')){	
				$this->request->data['CaseTemplate'] = $this->request->data['User'];
				$this->request->data['CaseTemplate']['name'] = $this->request->data['CaseTemplate']['title'];
				$this->request->data['CaseTemplate']['description'] = $this->request->data['CaseTemplate']['desc'];
				$this->request->data['CaseTemplate']['user_id'] = $this->Auth->User('id');
				$this->request->data['CaseTemplate']['company_id']=SES_COMP;
				$this->loadModel("CaseTemplate");
				if($this->request->data['CaseTemplate']['update_temp'] == 1) {
					if(isset($this->request->data['User']['id'])){
						$this->CaseTemplate->id=$this->request->data['User']['id'];
						if($this->CaseTemplate->save($this->request->data)){
							$this->Session->write("SUCCESS","Template updated successfully");
							$this->redirect(HTTP_ROOT."users/manage_template");
						}else{
							$this->Session->write("ERROR","Template can't be updated");
							$this->redirect(HTTP_ROOT."users/add_template/".$this->request->data['User']['id']);
						}
					}else{
						if($this->CaseTemplate->save($this->request->data)){
							$this->Session->write("SUCCESS","Template added successfully");
							$this->redirect(HTTP_ROOT."users/manage_template");
						}else{
							$this->Session->write("ERROR","Template can't be added");
							$this->redirect(HTTP_ROOT."users/add_template");
						}
					}
				}
			}
		}
	}
	function manage_template(){
		if(isset($this->params['pass'][0]) && $this->params['pass'][0]){
			$this->loadModel("CaseTemplate");
			$this->CaseTemplate->id=$this->params['pass'][0];
			$this->CaseTemplate->delete();
			$this->CaseTemplate->delete();
			$this->Session->write("SUCCESS","Deleted successfully");
			$this->redirect(HTTP_ROOT."users/manage_template");
		}
		if(isset($this->request->query['act']) && $this->request->query['act']){
			$v=urldecode(trim($this->request->query['act']));
			$this->loadModel("CaseTemplate");
			$this->CaseTemplate->id=$v;
			if($this->CaseTemplate->saveField("is_active",1)){
				$this->Session->write("SUCCESS","Template activated successfully");
				$this->redirect(HTTP_ROOT."users/manage_template/");
			}else{
				$this->Session->write("ERROR","Template can't be activated.Please try again.");
				$this->redirect(HTTP_ROOT."users/manage_template/");
			}
		}
		if(isset($this->request->query['inact']) && $this->request->query['inact']){
			$v=urldecode(trim($this->request->query['inact']));
			$this->loadModel("CaseTemplate");
			$this->CaseTemplate->id=$v;
			if($this->CaseTemplate->saveField("is_active",0)){
				$this->Session->write("SUCCESS","Template deactivated successfully");
				$this->redirect(HTTP_ROOT."users/manage_template/");
			}else{
				$this->Session->write("ERROR","Template can't be deactivated.Please try again.");
				$this->redirect(HTTP_ROOT."users/manage_template/");
			}
		}
		$this->loadModel("CaseTemplate");
		$res = $this->CaseTemplate->find('all',array('conditions'=>array('company_id'=>SES_COMP,'user_id'=>SES_ID,'is_active'=>1)));
		$total_record1 = $res;
		$total_records = count($total_record1);
		$this->set('total_records',$total_records);
		
		$page_limit = MILE_PAGE_LIMIT;
		$page = 1;
		$pageprev=1;
		if(isset($_GET['page']) && $_GET['page'])
		{
			$page = $_GET['page'];
		}
		$limit1 = $page*$page_limit-$page_limit;
		$limit2 = $page_limit;
		$query = "SELECT * FROM case_templates WHERE case_templates.company_id='".SES_COMP."' AND case_templates.user_id='".SES_ID."' ORDER BY created ASC LIMIT ".$limit1.",".$limit2;
		$TempalteArray = $this->CaseTemplate->query($query);
		
		//$limit = $limit1.",".$limit2;
		//$TempalteArray =$this->CaseTemplate->find('all', array('conditions'=> array('CaseTemplate.is_active'=>1,'order'=>array('CaseTemplate.created DESC'),'limit' =>$limit)));
		$count_mile = count($TempalteArray);
		$this->set('count_mile',$count_mile);
		$this->set('page_limit',$page_limit);
		$this->set('page',$page);
		$this->set('pageprev',$pageprev);	
		$this->set('TempalteArray',$TempalteArray);
	}
	function ajax_project_list_milestone()
	{
		$this->layout='ajax';
		$page = $this->request->data['page'];
		$limit = $this->request->data['limit'];
		$qry="";
		$ProjectUser = ClassRegistry::init('ProjectUser');
		 
		if($limit != "all") {
			
			$allProjArr = $ProjectUser->query("select DISTINCT p.name,p.uniq_id as uniq_id,(select count(ml.id) from milestones as ml where pu.project_id=ml.project_id ) as count from projects as p, project_users as pu where p.id=pu.project_id and pu.user_id='".SES_ID."' and pu.company_id='".SES_COMP."' AND p.isactive='1' ORDER BY pu.dt_visited DESC LIMIT 0,$limit");
		}
		else {
			$allProjArr = $ProjectUser->query("select DISTINCT p.name,p.uniq_id as uniq_id,(select count(ml.id) from milestones as ml where  pu.project_id=ml.project_id ) as count from projects as p, project_users as pu where p.id=pu.project_id and pu.user_id='".SES_ID."' and pu.company_id='".SES_COMP."' AND p.isactive='1' ORDER BY pu.dt_visited DESC");
		}
		
		$this->set('allProjArr',$allProjArr);

		$countAll = $ProjectUser->find('count', array('conditions'=>array('ProjectUser.user_id' => SES_ID,'Project.isactive' => 1,'Project.company_id' => SES_COMP), 'fields' => 'DISTINCT Project.id'));
		$this->set('countAll',$countAll);
		
		$this->set('page',$page);
		$this->set('limit',$limit);
	}
	function search_project_menu_milestone()
	{
		$this->layout='ajax';
		$page = $this->request->data['page'];
		$val = $this->request->data['val'];
		if($val!=""){
			$cond = array('conditions'=>array('Project.name LIKE' => '%'.$val.'%','ProjectUser.user_id' => SES_ID,'Project.isactive' => 1,'Project.company_id'=>SES_COMP), 'fields' => array('DISTINCT  Project.uniq_id', 'Project.id','Project.name'));

		
		$ProjectUser = ClassRegistry::init('ProjectUser');
		$ProjectUser->unbindModel(array('belongsTo' => array('User')));
		
		/*$allProjArr = $ProjectUser->find('all', $cond);	
		$countAll = $ProjectUser->find('count', array('conditions'=>array('Project.name LIKE' => '%'.$val.'%','Project.isactive' => 1,'Project.company_id'=>SES_COMP), 'fields' => 'DISTINCT Project.id'));*/
		
			$allProjArr = $ProjectUser->query("SELECT SQL_CALC_FOUND_ROWS DISTINCT Project.uniq_id,Project.id,Project.name FROM project_users as ProjectUser,projects as Project WHERE ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='".SES_COMP."' AND Project.name LIKE '%".$val."%' AND ProjectUser.user_id='".SES_ID."'");
			
			//pr($allProjArr);
		
			$totcnt = $ProjectUser->query("SELECT FOUND_ROWS() as count");
			$countAll = $totcnt['0']['0']['count'];
		}
		$this->set('countAll',$countAll);
		$this->set('allProjArr',$allProjArr);
		$this->set('page',$page);
		$fres=1;
		$this->set('fres',$fres);
		if($val=="" || $countAll==0)
		{
			$fres=0;$this->set('fres',$fres);
		}
	}
########################################
	function update_tbl(){
		$this->layout='none';
		$this->loadmodel('Easycase');
		$this->recursive=-1;
		$caseno = array(7,8,9);
		foreach($caseno AS $k=>$v){
			$res = $this->Easycase->query('SELECT *,MAX(id) AS pid ,MAX(dt_created) AS dt  FROM easycases WHERE case_no='.$v.' GROUP BY project_id');
			foreach($res as $key=>$val){
				$sql = "UPDATE easycases set updated_by='SELECT user_id FROM easycases WHERE id=".$val['0']['pid']." ',dt_created='".$val[0]['dt']."'  WHERE case_no=".$v." AND project_id=".$val['easycases']['project_id']." AND istype=1";
				$this->Easycase->query($sql);
				//echo $sql."<hr/>";
			}
		}echo "success";exit;
	}
	
	
	
	
	
	function _check_email_exist($email = null)
	{
		$this->loadmodel('BetaUser');
		$betauser = $this->BetaUser->findByEmail($email);
		$user = $this->User->find('first',array('conditions' =>array('email' => $email)));
		if(!empty($user)){
			return 'User already exists!';
		}

		else if(!empty($betauser)){
			$msg = '';
			if($betauser['BetaUser']['is_approve'] == 1){
				$msg = 'Approved Betauser';
			}else{
				$msg = 'Disapproved Betauser';
			}
			return $msg;
		}

		else
		{
			return true;
		}
		
	}
	function googleConnect(){
	    $this->layout = 'ajax'; 
		
		/*echo "<pre>";
			print_r($_GET['state']);
			exit;*/
			
	    if (isset($_GET['code'])) {
		App::import('Vendor', 'GoogleClient', array('file' => 'google-api'.DS.'src'.DS.'Google_Client.php'));
		if (isset($_GET['state'])) {
		    App::import('Vendor', 'GoogleOauth', array('file' => 'google-api'.DS.'src'.DS.'contrib'.DS.'Google_Oauth2Service.php'));
		    $client = $this->setClient(1);
		    $service = new Google_Oauth2Service($client);
		} else {
		    App::import('Vendor', 'GoogleDrive', array('file' => 'google-api'.DS.'src'.DS.'contrib'.DS.'Google_DriveService.php'));
		    $client = $this->setClient();
		}
				
		$client->authenticate();
		$token = $client->getAccessToken();
		$emails = '';
		if(isset($_GET['state'])){
		    $params = explode('-',$_GET['state']);
		    $_GET['state'] = $params[0]; 
		    $emails = $params[1]; 
		}
		
		if (isset($_GET['state']) && $_GET['state'] != 'contact') {
		    $user = $service->userinfo->get();
		    $info = (array)$user;
		    fwrite($h, print_r($info,true));
		    if (isset($info) && !empty($info)) {
			$_SESSION['CHECK_GOOGLE_SES'] = 0;
			$_SESSION['GOOGLE_USER_INFO'] = $info;
			$_SESSION['GOOGLE_USER_INFO']['access_token'] = $token;
			
			$guser['GOOGLE_USER_INFO'] = $info;
			$guser['GOOGLE_USER_INFO']['access_token'] = $token;
			setcookie('GOOGLE_USER_INFOS',  json_encode($guser),time()+3600*24*7,'/',DOMAIN_COOKIE,false,false);
		    }
		    
		    if ($_GET['state'] == "login") {
			setcookie('google_login',1,time()+300,'/',DOMAIN_COOKIE,false,false);
		    }
		    setcookie('google_accessToken',$token,time()+3600*24*7,'/',DOMAIN_COOKIE,false,false);
		}
		if(isset($_GET['state']) && $_GET['state'] == 'contact'){
		    
		    $CompanyUser = ClassRegistry::init('CompanyUser');
			
			
				
		    $checkCmnpyUsr = $CompanyUser->find('all',array('conditions'=>array('CompanyUser.company_uniq_id'=>$params[2]),'fields'=>array('CompanyUser.user_id')));
		    $CmnpyUsr = array();
		    if($checkCmnpyUsr){
			$checkCmnpyUsr = Hash::extract($checkCmnpyUsr, '{n}.CompanyUser.user_id');
			$checkCmnpyUsr = array_unique($checkCmnpyUsr);
			$CmnpyUsr = $this->User->find('all',array('conditions'=>array('User.id'=>$checkCmnpyUsr),'fields'=>array('User.email')));
			if($CmnpyUsr){
				
			    $CmnpyUsr = Hash::extract($CmnpyUsr, '{n}.User.email');
			}
		    }
		    $this->set('CompUsers',$CmnpyUsr);
		    $temp_arr = json_decode($token,true);
		    $max_results = 500;
		    $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&alt=json&v=3.0&oauth_token='.$temp_arr['access_token'].'&orderby=lastmodified&sortorder=descending';
		    $response =  $this->getContacts($url);
		   /* echo "<pre>";
		    print_r($response);
		    echo "</pre>";exit;*/
		    $response = json_decode($response,true);
		    $this->set('contacts',$response);
		    $this->set('emails',$emails);
		    setcookie('google_accessToken',$temp_arr['access_token'],time()+3600*24*7,'/',DOMAIN_COOKIE,false,false);
		    $this->render('google_contact');
		}
	    }
	}
	
	function getContacts($url){
	     $curl = curl_init();
	     $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

	     curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
	     curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	     curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	

	     curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
	     curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
	     curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
	     curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
	     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.

	     $contents = curl_exec($curl);
	     curl_close($curl);
	     return $contents;
	}
	
	/*
	 * @author Orangescrum
	 * @method setClient
	 * @return an object of google.
	 */
	
	function setClient($isLogin = NULL) {
	    $client = new Google_Client();

	    // Get your credentials from the APIs Console
	    $client->setClientId(CLIENT_ID);
	    $client->setClientSecret(CLIENT_SECRET);
	    $client->setRedirectUri(REDIRECT_URI);
	    if (isset($isLogin)) {
		$client->setDeveloperKey(API_KEY);
		$client->setScopes(array(
		    'https://www.googleapis.com/auth/userinfo.profile'
		));
	    } else {
		$client->setScopes(array(
		    'https://www.googleapis.com/auth/drive'
		));
	    }
	    
	    $client->setUseObjects(true);
	    return $client;
	}
	
	/*
	 * @author Orangescrum
	 * @method googleConnect
	 * @return a token.
	 */
	
	function googleSignup(){
	    $this->layout = 'ajax';
	    $data = "";
	    if (isset($_GET['code'])) {
		
		App::import('Vendor', 'GoogleClient', array('file' => 'google-api'.DS.'src'.DS.'Google_Client.php'));
		App::import('Vendor', 'GoogleOauth', array('file' => 'google-api'.DS.'src'.DS.'contrib'.DS.'Google_Oauth2Service.php'));
		
		$client = $this->setClientForConnect();
		$service = new Google_Oauth2Service($client);
		
		$client->authenticate();
		$token = $client->getAccessToken();
		
		if (isset($_GET['state'])) {
		    $user = $service->userinfo->get();
		    $info = (array)$user;
		    if (isset($info) && !empty($info)) {
			$_SESSION['CHECK_GOOGLE_SES'] = 0;
			$_SESSION['GOOGLE_USER_INFO'] = $info;
			$_SESSION['GOOGLE_USER_INFO']['access_token'] = $token;
		    }
		    
                    //Set google info from session and check if google email exists in our record or not.
                    if (isset($info) && !empty($info)) {
                        $isEmail = $this->User->find('first',array('conditions'=>array('User.email'=>urldecode($info['email'])),'fields'=>array('User.id')));
                        if($isEmail['User']['id']) {
                            setcookie('user_info',$info['email'],time()+300,'/',DOMAIN_COOKIE,false,false);
                        }
                    }
//                    pr($info);exit;
		    if ($_GET['state'] == "signup") {
			setcookie('google_signup',1,time()+300,'/',DOMAIN_COOKIE,false,false);
		    }
		}
		
		setcookie('google_accessToken',$token,time()+300,'/',DOMAIN_COOKIE,false,false);
	    }
	}
	
	/*
	 * @author Orangescrum
	 * @method setClientForConnect
	 * @return an object of google.
	 */
	
	function setClientForConnect() {
	    $client = new Google_Client();

	    // Get your credentials from the APIs Console
	    $client->setClientId(CLIENT_ID_SIGNUP);
	    $client->setClientSecret(CLIENT_SECRET_SIGNUP);
	    $client->setRedirectUri(REDIRECT_URI_SIGNUP);
	    $client->setDeveloperKey(API_KEY);
	    $client->setScopes(array(
		'https://www.googleapis.com/auth/userinfo.profile'
	    ));
	    
	    $client->setUseObjects(true);
	    return $client;
	}
	
	function account_activity(){
		/*if(SES_TYPE!=1){
			$this->redirect(HTTP_ROOT);exit;
		}*/
		$flag=0;
		$record_per_page =20;
		if(isset($this->data['ajaxlayout']) && $this->data['ajaxlayout']){
			$this->layout='ajax';
			$this->set('ajaxlayout',1);
		}
		//
		$this->loadModel('logType');
		$this->loadModel('logActivity');
		$conditions =" 1 AND logActivity.log_type_id!=5 ";
		if($this->Auth->User('istype')!=1 && SES_TYPE==1){
			//$conditions=array('company_id'=>SES_COMP);
			$conditions .='   AND logActivity.company_id='.SES_COMP;
			$company_id = SES_COMP;
		}elseif($this->Auth->User('istype')==1 && isset($this->data['company_id'])){
			$flag =1;
			$conditions .='   AND logActivity.company_id='.$this->data['company_id'];
			$company_id = $this->data['company_id'];
		}
		if(isset($this->data['filter']) && $this->data['filter']){
			$conditions .=" AND logActivity.log_type_id=".$this->data['filter'];
			$this->set('filter',$this->data['filter']);
		}else{
			$this->set('filter','');
		}
		if(isset($this->data['page']) && $this->data['page']){
			$page= $this->data['page']-1;
		}else{
			$page=0;
		}
		$logtype = $this->logType->find('list',array('conditions'=>array('id !=5'),'fields'=>array('id','name'),'order'=>'name ASC'));
		$limit = $page*$record_per_page.', '.$record_per_page;
		$sql ="SELECT SQL_CALC_FOUND_ROWS  `logActivity`.*, `Company`.`name`, `User`.`name`, `User`.`last_name`, `User`.`email` FROM 
				`log_activities` AS `logActivity` inner JOIN `companies` AS `Company` ON (`Company`.`id` = `logActivity`.`company_id`) 
				inner JOIN `users` AS `User` ON (`logActivity`.`user_id` = `User`.`id`) WHERE ".$conditions." ORDER BY logActivity.created DESC LIMIT ".$limit;
		$arr = $this->logActivity->query($sql);
		$sQuery = " SELECT FOUND_ROWS() AS cnt	";
		$total_record = $this->logActivity->query($sQuery);
		$this->set('logtype',$logtype);
		$this->set('logactivity',$arr);
		$this->set('activityCount',$total_record[0][0]['cnt']);
		$this->set('page_limit',$record_per_page);
		$this->set('page',$page+1);
		//Flag is required for osadmin company details page activity listing
		$this->set('flag',$flag);
		$this->set('comp_id',$company_id);
		if($this->data['company_id']){
			$this->layout='ajax';
			$this->render('payment_activity');
		}
	}

	function close_onbording(){
		$this->layout='ajax';
		$cookiename = $this->data['cookiename'];
		setcookie($cookiename.SES_ID,1,time()+(7*24*60*60),'/',DOMAIN_COOKIE,false,false);
		echo 1;exit;
	}
        
	function check_fordisabled_user(){
		$emailids = trim(trim($this->data['email']),',');
		if($emailids && strstr($emailids,',')){
			$emails = explode(',', $emailids);
			foreach ($emails as $key => $value) {
				if(trim($value)!=''){
					$emaillist[] = $value;
				}
			}
		}elseif($emailids){
			$emaillist[] =  $emailids;
		}
		$userlist = $this->User->find('list',array('joins'=>array(
		array(
			'table' => 'company_users',
			'alias' => 'CompanyUser',
			'type' => 'inner',
			'conditions'=> array('CompanyUser.user_id=User.id','User.email IS NOT NULL','User.email'=>$emaillist,'CompanyUser.company_id'=>SES_COMP,'CompanyUser.user_type !='=>1,'CompanyUser.is_active'=>0)
		)),
		'fields'=>array('User.id','User.email')));
		if($userlist){
			echo implode(',', $userlist);exit;
		}else{
			echo '1';exit;
		}
		//print_r($userlist);exit;
	}

	function categorytab(){
		$this->layout='ajax';
	}

	function ajax_savecategorytab(){
		if($this->data['is_ajaxflag']){
			$this->layout='ajax';
			$tabvalue = $this->data['tabvalue']?$this->data['tabvalue']:0;
			$data['User']['id'] = SES_ID;
			$data['User']['active_dashboard_tab']= $tabvalue;
			if($this->User->save($data)){
				define('ACT_TAB_ID',$tabvalue);
				echo '1';exit;
			}else{
				echo '0';exit;
			}
		}else{
			$this->redirect(HTTP_ROOT);
		}
	}
	function gmailContacts(){
		$this->layout = 'ajax';
		$contactEmail =  gmailContactEmail();
		$this->set('gmailContact',$contactEmail);
	}

	function resend_invitation(){
		if($this->data['querystring'] && $this->data['ajax_flag']){
			$resend = $this->data['querystring'];
			$UserInvitation= ClassRegistry::init('UserInvitation');
			$invit = $UserInvitation->find('first',array('conditions'=>array('UserInvitation.qstr'=>$resend)));
			if($invit){
				$qstr = $this->Format->generateUniqNumber();
				$data['UserInvitation']['id'] = $invit['UserInvitation']['id'];
				$data['UserInvitation']['qstr'] = $qstr;
				if($UserInvitation->save($data)){
						$inviteduser = $this->User->find('first',array('conditions'=>array('User.id'=>$invit['UserInvitation']['user_id']),'fields'=>array('User.name','User.email')));
						
						$to = $inviteduser['User']['email'];
						if($inviteduser['User']['name']){
							$expName = $inviteduser['User']['name'];
						}else{
							$expEmail = explode("@",$inviteduser['User']['email']);
							$expName = $expEmail[0];
						}
						$loggedin_users = $this->Format->getUserNameForEmail($this->Auth->User("id"));
						$fromName = ucfirst($loggedin_users['User']['name']);
						$fromEmail = $loggedin_users['User']['email'];
						
						$Company = ClassRegistry::init('Company');
						$comp = $Company->find('first', array('fields' => array('Company.id', 'Company.name', 'Company.uniq_id')));
						
						$subject = $fromName." invited you to join ".$comp['Company']['name']." on Orangescrum";
						
						$this->Email->delivery = EMAIL_DELIVERY;
						$this->Email->to = $to;  
						$this->Email->subject = $subject;
						$this->Email->from = FROM_EMAIL;
						$this->Email->template = 'invite_user';
						$this->Email->sendAs = 'html';
						$this->set('expName', ucfirst($expName));
						$this->set('qstr', $qstr);
						$this->set('existing_user',1);
						
						$this->set('company_name',$comp['Company']['name']);
						$this->set('fromEmail',$fromEmail);
						$this->set('fromName',$fromName);
						
						try{
							if($this->Sendgrid->sendgridsmtp($this->Email)){
								$arr['msg']='succ';
								$arr['qstr'] = $qstr;
								echo json_encode($arr);exit;
							}else{
								$arr['msg']='err';
								$arr['type'] = 'Mail not sent';
								echo json_encode($arr);exit;
							}
						}Catch(Exception $e){ 
							$arr['msg']='err';
							$arr['type'] = 'Error sending email';
							echo json_encode($arr);exit;
						}
				}else{
					$arr['msg']='err';
					$arr['type'] = 'datasave_err';
					echo json_encode($arr);exit;
				}
			}else{
				$arr['msg']='err';
				$arr['type'] = 'Wrong query string';
				echo json_encode($arr);exit;
			}
		}else{
			$arr['msg']='err';
			$arr['type'] = 'Not Allowed';
			echo json_encode($arr);exit;
		}
	}

	function onbording_inviteuser(){}

	
function show_preview_img(){
	$this->layout='ajax';
	//sleep(20);
	if(!empty($this->params['data']['User']['photo']['name'])){
		$size = $this->params['data']['User']['photo']['size'];
		$sizeinkb = $size/1024;
	
		$name = $this->params['data']['User']['photo']['name'];
		$tmp_name = $this->params['data']['User']['photo']['tmp_name'];
	
		$type = $this->params['data']['User']['photo']['type'];
			
		$file_path = WWW_ROOT.'files/profile/orig/';
	
		$newFileName = ""; $updateData = ""; $message = "success"; $displayname = "";
		//$allowedSize = MAX_FILE_SIZE*1024;
	
		//move_uploaded_file($tmp_name,$file_path.$name);
		//$newFileName = $name;
		
		$newFileName = $this->Format->showuploadImage($tmp_name,$name,$size,$file_path,SES_ID);
		if($newFileName == 'small size image'){
			echo '{"message":"'.$newFileName.'"}';
		}else{
			if(USE_S3){
			    $s3 = new S3(awsAccessKey, awsSecretKey);
			    $s3->putObjectFile(WWW_ROOT.'files/profile/orig/'.$newFileName,BUCKET_NAME ,DIR_USER_PHOTOS_TEMP.$newFileName ,S3::ACL_PRIVATE);
			}			
			$res_array = array(
				"name"=>$displayname,
				"sizeinkb"=>$sizeinkb,
				"filepath"=>$file_path,
				"filename"=>$newFileName,
				"message"=>$message
			);
			echo json_encode($res_array);
			//echo '{"name":"'.$displayname.'","sizeinkb":"'.$sizeinkb.'","filepath":"'.$file_path.'","filename":"'.$newFileName.'","message":"'.$message.'"}';
		}
		exit;
	}
}
function done_cropimage(){
	$this->layout='ajax';
	if(!empty($this->params['data']['width']) && !empty($this->params['data']['height'])){
		$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
		$max_file_size = 100 * 1024; #200kb
		$nw = $nh = 100; # image with & height
		$imgName = HTTP_ROOT.'files/profile/'.$this->params['data']['imgName'];
		$imgthumbSrc = "";
		  if ( isset($imgName) ) {  
			  # grab data form post request
			  $x = (int) $this->params['data']['x-cord'];
			  $y = (int) $this->params['data']['y-cord'];
			  $w = (int) $this->params['data']['width'];
			  $h = (int) $this->params['data']['height'];
			  if(USE_S3){			  
				$imgSrc = $this->Format->generateTemporaryURL(DIR_USER_PHOTOS_S3_TEMP.$this->params['data']['imgName']);
			  }else{
				$imgSrc = HTTP_ROOT.'files/profile/orig/'.$this->params['data']['imgName'];
			  }
			//getting the image dimensions
			list($width, $height) = getimagesize($imgSrc);
				//saving the image into memory (for manipulation with GD Library)
				$type = exif_imagetype($imgSrc);
				switch ($type) { 
					case 1 : 
						$myImage = imagecreatefromgif($imgSrc); 
					break; 
					case 2 : 
						$myImage = imagecreatefromjpeg($imgSrc); 
					break; 
					case 3 : 
						$myImage = imagecreatefrompng($imgSrc); 
					break; 
					case 6 : 
						$myImage = imagecreatefromwbmp($imgSrc); 
					break; 
					default: 
						$src = imagecreatefromjpeg($imgSrc);
					break;
				}

				// calculating the part of the image to use for thumbnail
				/*if ($width > $height) {
				  $y = 0;
				  $x = ($width - $height) / 2;
				  $smallestSide = $height;
				} else {
				  $x = 0;
				  $y = 0;
				 // $y = ($height - $width) / 2;
				  $smallestSide = $width;
				}*/
				// copying the part into thumbnail
				$thumbSize = 120;
				$thumb = imagecreatetruecolor($thumbSize, $thumbSize);
				imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $w, $h);
			  /*# read image binary data
			  $data = file_get_contents($imgName);
			  # create v image form binary data
			  $vImg = imagecreatefromstring($data);
			  $dstImg = imagecreatetruecolor($nw, $nh);
			  # copy image
			  imagecopyresampled($dstImg, $vImg, 0, 0, $x, $y, $nw, $nh, $w, $h);
			  # save image
			  imagejpeg($dstImg, $path);*/
			  //Imagejpeg($thumb, $path);
			  $imgthumbNm = $this->params['data']['imgName'];//time()."_".$this->params['data']['imgName'];
			  $imgthumbSrc = DIR_USER_PHOTOS.$imgthumbNm;
			  switch ($type) { 
				case 1 : 
					imagegif($thumb, $imgthumbSrc);
				break; 
				case 2 : 
					imagejpeg($thumb, $imgthumbSrc);
				break; 
				case 3 : 
					imagepng($thumb, $imgthumbSrc);
				break; 
				case 6 : 
					imagewbmp($thumb, $imgthumbSrc);
				break; 
				default: 
					imagejpeg($thumb, $imgthumbSrc);
				break;
			}
			//echo "<img src='$path' />";
			if(USE_S3){
			    $s3 = new S3(awsAccessKey, awsSecretKey);
			    $s3->putObjectFile(DIR_USER_PHOTOS.$imgthumbNm,BUCKET_NAME ,DIR_USER_PHOTOS_THUMB.$imgthumbNm ,S3::ACL_PRIVATE);
			}
			echo $imgthumbNm;
		  } else {
		    echo 'file not set';
		  }
	}
	exit;
}
	function email_notifications(){
		$UserNotification = ClassRegistry::init('UserNotification');
		$getAllNot = $UserNotification->find('first',array('conditions'=>array('UserNotification.user_id'=>SES_ID)));
		$this->set('getAllNot',$getAllNot);
		if($this->request->data && $_SESSION['CSRFTOKEN'] == trim($this->request->data['UserNotification']['csrftoken'])) {
			$this->request->data['User']['id'] = SES_ID;
			if(!isset($this->request->data['User']['desk_notify'])) {
				$this->request->data['User']['desk_notify'] = 0;
			}
			$this->User->save($this->request->data['User']);
			if(isset($this->request->data['UserNotification']))
			{	
				$this->request->data['UserNotification']['user_id'] = SES_ID;
				$this->request->data['UserNotification']['id'] = $getAllNot['UserNotification']['id'];
				$UserNotification->save($this->request->data['UserNotification']);
			}
			$this->Session->write("SUCCESS","Notifications changed successfully");
			$this->redirect(HTTP_ROOT."users/email_notifications");	
		} else if (isset($this->request->data['UserNotification'])) {
            print "You are not authorized to do this operation.";
            exit;
		}
				
		
	}
	function email_reports(){
		$DailyupdateNotification = ClassRegistry::init('DailyupdateNotification');		
		$getAllDailyupdateNot = $DailyupdateNotification->find('first',array('conditions'=>array('DailyupdateNotification.user_id'=>SES_ID,'company_id'=>SES_COMP)));
		$this->set('getAllDailyupdateNot',$getAllDailyupdateNot);
		$UserNotification = ClassRegistry::init('UserNotification');
		$getAllNot = $UserNotification->find('first',array('conditions'=>array('UserNotification.user_id'=>SES_ID)));
		$this->set('getAllNot',$getAllNot);
		if($this->request->data && $_SESSION['CSRFTOKEN'] == trim($this->request->data['UserNotification']['csrftoken'])) {
//			pr($this->request);exit;
			if(isset($this->request->data['UserNotification']))
			{	
				$this->request->data['UserNotification']['user_id'] = SES_ID;
				$this->request->data['UserNotification']['id'] = $getAllNot['UserNotification']['id'];
				$UserNotification->save($this->request->data['UserNotification']);
			}	
			if(isset($this->request->data['DailyupdateNotification']))
			{	
				$data['DailyupdateNotification']['id'] = $getAllDailyupdateNot['DailyupdateNotification']['id'];
				$data['DailyupdateNotification']['user_id'] = SES_ID;
				$data['DailyupdateNotification']['status'] = 0;
				if($this->request->data['DailyupdateNotification']['dly_update'] == 1){
					$data['DailyupdateNotification']['dly_update'] = 1;
					$data['DailyupdateNotification']['notification_time'] = $this->request->data['DailyupdateNotification']['not_hr'].':'. $this->request->data['DailyupdateNotification']['not_mn'];
					$comma_separated = implode(",",$this->request->data['DailyupdateNotification']['proj_name']);
					$data['DailyupdateNotification']['proj_name'] = trim($comma_separated,',');
				}else{
					$data['DailyupdateNotification']['dly_update'] = 0;
					$data['DailyupdateNotification']['notification_time'] = '';
					$data['DailyupdateNotification']['proj_name'] = '';				
				}
                                $data['DailyupdateNotification']['company_id']=SES_COMP;
				$DailyupdateNotification->save($data['DailyupdateNotification']);
			}
			$this->Session->write("SUCCESS","Reports changed successfully");
			$this->redirect(HTTP_ROOT."users/email_reports");	
		} else if (isset($this->request->data['UserNotification']) || isset($this->request->data['DailyupdateNotification'])) {
            print "You are not authorized to do this operation.";
            exit;
		}
	}
	function mycompany(){
		if(SES_TYPE == 3) {
			$this->redirect(HTTP_ROOT."dashboard");
		}
		$Company = ClassRegistry::init('Company');
		$Company->recursive = -1;

		if (isset($this->request->data['Company']) && $_SESSION['CSRFTOKEN'] == trim($this->request->data['Company']['csrftoken'])) {
            
			if(trim($this->request->data['Company']['name']) == "")
			{
				$this->Session->write("ERROR","Name cannot be left blank");
				$this->redirect(HTTP_ROOT."users/mycompany");
			}else{
				$this->request->data['Company']['id'] = SES_COMP;
				$Company->save($this->request->data);
				$this->Session->write("SUCCESS","Company updated successfully");
				$this->redirect(HTTP_ROOT."users/mycompany");
			}
		} else if (isset($this->request->data['Company'])) {
            print "You are not authorized to do this operation.";
            exit;
		}
		$getCompany = $Company->find('first',array('conditions'=>array('Company.id'=>SES_COMP)));
		$this->set('getCompany',$getCompany);
	}
//	function importexport(){
//		
//	}
	function cancelact(){
		
	}
	function actactivity(){
		
	}
	function milestone(){
		
	}
	function analytics(){
		
	}
	function check_password(){
		$pass = $this->data['password'];
		if(SES_ID){
			$userinfo = $this->User->find('first',array('conditions'=>array('User.id'=>SES_ID)));
            if($userinfo['User']['password']) {
                if($userinfo['User']['password'] == md5($pass)) {
                    echo 1;
                    exit;
                }else {
                    echo "Wrong password entered.";
                    exit;
				}
            }else if($userinfo['User']['password']=='' && $pass=='') {
                echo 1;
                exit;
			}
        }else {
            echo "You are not allowed to do this activity";
            exit;
		}
	}

	function validate_emailurl(){
		$this->layout='ajax';
		$data = $this->User->validate_emailurl($this->data);
		echo json_encode($data);exit;
	}
       
	function ajax_assignedproject_delete(){
		$this->layout='ajax';
		if(!empty($this->params['data']['id']) && !empty($this->params['data']['userId'])){
		  	$this->loadModel('ProjectUser');
		  	$this->loadModel('UserInvitation');
		  	if(isset($this->params['data']['isInvite']) && $this->params['data']['isInvite'] == 1){	  		
		  		 $inviteuser = $this->UserInvitation->query("SELECT user_invitations.project_id FROM user_invitations,users WHERE user_invitations.user_id IN (".$this->params['data']['userId'].") AND user_invitations.user_id = users.id AND user_invitations.company_id='".SES_COMP."' LIMIT 1");
				if(isset($inviteuser) && !empty($inviteuser['0']['user_invitations']['project_id'])) {
					$project_id = explode(",", $inviteuser['0']['user_invitations']['project_id']);
					if(isset($this->request->data['id']) && $this->request->data['id']) { 
						if(in_array($this->request->data['id'], $project_id)) {
							unset($project_id[array_search($this->request->data['id'],$project_id)]);
						}
						$prjId = implode(",", $project_id);
						$this->UserInvitation->query("Update user_invitations SET project_id='".$prjId."' WHERE user_id='".$this->params['data']['userId']."'");
						echo "success";exit;
					}else{
						echo 'error';exit;
					}
				}
		  	}else{
				$projectUsers = $this->ProjectUser->find('first',array('conditions'=>array('ProjectUser.project_id'=>$this->params['data']['id'],'ProjectUser.user_id'=>$this->params['data']['userId'],'ProjectUser.company_id'=>SES_COMP),'fields'=>array('ProjectUser.id')));
				if(!empty($projectUsers) && !empty($projectUsers['ProjectUser']['id'])){
					$this->ProjectUser->id = $projectUsers['ProjectUser']['id'];  
					$res = $this->ProjectUser->delete();     
					if($res){  
						echo "success";
					}else{
						echo 'error';
					} 
				}else{
					echo 'error';
				}             
		  	}
		}	  
        exit;
	}
	function generateMsgAndSendUsMail($pjnames,$userid,$projUniqId,$comp)
	{
                $User_id=$this->Auth->user('id');
                $this->loadModel('User');
                $rec=$this->User->findById($User_id);
                $from_name=$rec['User']['name'].' '.$rec['User']['last_name'];

                App::import('helper', 'Casequery');
		$csQuery = new CasequeryHelper(new View(null));
		
		App::import('helper', 'Format');
		$frmtHlpr = new FormatHelper(new View(null));
		
		##### get User Details
		$toUsrArr = $csQuery->getUserDtls($userid);
		$to = ""; $to_name = "";
		if(count($toUsrArr)) {
			$to = $toUsrArr['User']['email'];
			$to_name = $frmtHlpr->formatText($toUsrArr['User']['name']);
		}
		
		$multiple = 0;
		if(stristr($pjnames,",")) {
			$multiple = 1;
			$subject = "You have been added to multiple projects on Orangescrum";
		}
		else {
			$subject = "You have been added to ".$pjnames." on Orangescrum";
		}
		
		$this->Email->delivery = EMAIL_DELIVERY;
		$this->Email->to = $to;
		$this->Email->subject = $subject;
		$this->Email->from = FROM_EMAIL_NOTIFY;
		$this->Email->template = 'project_add';
		$this->Email->sendAs = 'html';
		$this->set('to_name',$to_name);
		$this->set('projName',$pjnames);
		$this->set('projUniqId',$projUniqId);
		$this->set('multiple',$multiple);
		$this->set('company_name',$comp['Company']['name']);
		$this->set('from_name',$from_name);
		return $this->Sendgrid->sendgridsmtp($this->Email);
	}
	function getting_started(){

        $id=$this->Auth->user('id');
        $this->loadModel('UserInvitation');
        $this->loadModel('Project');
        $this->loadModel('Easycase');
        $projects=$this->Project->findByUserId($id);
        $invitations=$this->UserInvitation->findByInvitorId($id);
        $tasks=$this->Easycase->findByUserId($id);
        $this->set(compact('projects','invitations','tasks'));
     }
     
    public function saveUserInfo($user_id,$access_token,$is_signup) {
        $this->loadModel('UserInfo');
        $user_info=$this->UserInfo->findByUserId($user_id);
        if(empty($user_info)) {
            $arr['user_id']=$user_id;
            $arr['access_token']=$access_token;
            $arr['is_google_signup']=$is_signup;
            $this->UserInfo->save($arr);
        }
    }
    
    function register_user() {
	$this->layout = 'ajax';	    
	$this->loadModel('Company');
	
	$email = urldecode($this->request->data['email']);	
	$password = urldecode($this->request->data['password']);
	$company = urldecode($this->request->data['company']);
	$seo_url = '';
	$isGoogle = 0;
	$google_data = "";
	$bt_profile_id = '';
	$credit_cardtoken = '';
	$cnumber = '';
	$expiry_date = '';
	$gaccess_token = '';
	$sub_type = 0;
	$message = '';
	
	$temp_name = explode("@",$email);	
	$name = $temp_name[0];
	$last_name = '';
	
	$short_name = $this->Format->makeShortName($name, $last_name);
	//Get the timezone for the registered user
	$this->loadModel('Timezone');
	$getTmz = $this->Timezone->find('first', array('conditions' => array('Timezone.gmt_offset' => urldecode($this->request->data['timezone_id']))));
	$timezone_id = $getTmz['Timezone']['id'];
	//Choose the subscritpion plan as selected by the user 

	$plan_id = (isset($this->data['plan_id']) && $this->data['plan_id']) ? $this->data['plan_id'] : 1;
	$this->loadModel('Subscription');
	$subScription = $this->Subscription->find('first', array('conditions' => array('Subscription.plan' => $plan_id)));

	if ($this->request->data && $name && $email && $company) {
	    $comp['Company']['uniq_id'] = $this->Format->generateUniqNumber();
	    $comp['Company']['seo_url'] = $this->Format->makeSeoUrl($seo_url);
	    $comp['Company']['subscription_id'] = $subScription['Subscription']['id'];
	    $comp['Company']['name'] = $company;
		$comp['Company']['logo'] = 'default-invoice-logo.png';
	    $comp['Company']['contact_phone'] = 'NA';
	    $message = "success";
	    try {
		$sus_comp = $this->Company->save($comp);
	    } catch (Exception $e) {
		$this->Company->delete($company_id);
		$message = 'Error in Creating Company';
	    }	    
	    if ($message == 'success' && $sus_comp) {
		$company_id = $this->Company->getLastInsertID();
		$activation_id = $this->Format->generateUniqNumber();
		$usr['User']['uniq_id'] = $this->Format->generateUniqNumber();
		$usr['User']['email'] = $email;
		$usr['User']['password'] = $this->Auth->password($password);
		if (!trim($name)) {
		    $nme = explode("@", $email);
		    $name = $nme[0];
		}
		$usr['User']['name'] = $name;
		$usr['User']['last_name'] = $last_name;
		$usr['User']['short_name'] = $short_name;
		$usr['User']['istype'] = 2;
		$usr['User']['isactive'] = 1;
		$usr['User']['dt_created'] = GMT_DATETIME;
		$usr['User']['dt_updated'] = GMT_DATETIME;
		$usr['User']['query_string'] = $activation_id;
		$usr['User']['timezone_id'] = $timezone_id ? $timezone_id : 26;
		$usr['User']['btprofile_id'] = $bt_profile_id;
		$usr['User']['credit_cardtoken'] = $credit_cardtoken;
		$usr['User']['card_number'] = $cnumber;
		$usr['User']['expiry_date'] = $expiry_date;
		$usr['User']['usersub_type'] = $sub_type;
		$usr['User']['is_agree'] = 1;
		$ip = $this->Format->getRealIpAddr();
		$usr['User']['ip'] = $ip;
		$usr['User']['gaccess_token'] = $gaccess_token;
		
		try {
		    $sus_user = $this->User->save($usr);
		} catch (Exception $e) {
		    $this->Company->delete($company_id);
		    $message = 'Error in Creating Company';
		}
		if ($message == 'success' && $sus_user) {
		    $comp_usr['CompanyUser']['user_id'] = $this->User->getLastInsertID();
		    $comp_usr['CompanyUser']['company_id'] = $company_id;
		    $comp_usr['CompanyUser']['company_uniq_id'] = $comp['Company']['uniq_id'];
		    $comp_usr['CompanyUser']['user_type'] = 1;
		    $this->loadModel('CompanyUser');
		    try {
			$sus_companyuser = $this->CompanyUser->save($comp_usr);
		    } catch (Exception $e) {
			$this->Company->delete($company_id);
			$message = 'Error in Creating Company';
		    }
		    if ($message == "success" && $sus_companyuser) {
			    $price = $subScription['Subscription']['price'];			    
			    $companyUid = $this->CompanyUser->getLastInsertID();
			    $this->loadModel('UserSubscription');
			    $sub_usr['UserSubscription']['user_id'] = $comp_usr['CompanyUser']['user_id'];
			    $sub_usr['UserSubscription']['company_id'] = $company_id;
			    $sub_usr['UserSubscription']['subscription_id'] = $subScription['Subscription']['id'];
			    $sub_usr['UserSubscription']['storage'] = $subScription['Subscription']['storage'];
			    $sub_usr['UserSubscription']['project_limit'] = $subScription['Subscription']['project_limit'];
			    $sub_usr['UserSubscription']['user_limit'] = $subScription['Subscription']['user_limit'];
			    $sub_usr['UserSubscription']['milestone_limit'] = $subScription['Subscription']['milestone_limit'];
			    $sub_usr['UserSubscription']['free_trail_days'] = $subScription['Subscription']['free_trail_days'];
			    $sub_usr['UserSubscription']['price'] = $price;
				$sub_usr['UserSubscription']['is_free'] = 1;
			    $sub_usr['UserSubscription']['month'] = $subScription['Subscription']['month'];
			    $sub_usr['UserSubscription']['created'] = GMT_DATETIME;
			    try {
				$usersubs = $this->UserSubscription->save($sub_usr);
			    } catch (Exception $e) {
				$this->Company->delete($company_id);
				$this->User->delete($comp_usr['CompanyUser']['user_id']);
				$this->CompanyUser->delete($companyUid);
				$message = 'Error in Creating Company';
			    }
			    if ($message == "success"){
				//Insert a new record for user notification.
				$notification['user_id'] = $comp_usr['CompanyUser']['user_id'];
				$notification['type'] = 1;
				$notification['value'] = 1;
				$notification['due_val'] = 1;
				ClassRegistry::init('UserNotification')->save($notification);
				
				//Event log data and inserted into database in account creation--- Start
				$json_arr['company_name'] = $comp['Company']['name'];
				$json_arr['name'] = $usr['User']['name'];
				$json_arr['user_type'] = isset($this->request->data['bt_profile_id']) ? 'Paid' : 'Free';
				$json_arr['created'] = GMT_DATETIME;
				$this->Postcase->eventLog($company_id, $comp_usr['CompanyUser']['user_id'], $json_arr, 1);


				//here send email to user uncomment.
				/*$to = $email;
				$from = FROM_EMAIL;
				$subject = "Welcome to Orangescrum, " . ucfirst($name) . "!";
				$activation_url = PROTOCOL . $comp['Company']['seo_url'] . "." . DOMAIN . "users/confirmation/" . $activation_id;
				$web_address = PROTOCOL . $comp['Company']['seo_url'] . "." . DOMAIN;
				$this->Email->delivery = EMAIL_DELIVERY;
				$this->Email->to = $to;
				$this->Email->subject = $subject;
				$this->Email->from = $from;
				$this->Email->template = 'free_signup';
				$this->Email->sendAs = 'html';
				$this->set('activation_url', $activation_url);
				$this->set('project_limit', $subScription['Subscription']['project_limit']);
				$this->set('user_limit', $subScription['Subscription']['user_limit']);
				$this->set('storage', $subScription['Subscription']['storage']);
				$this->set('expName', ucfirst($name));
				$this->set('password', $password);
				$this->set('web_address', $web_address);
				$this->set('plan_id', $plan_id);
				$this->set('free_trail_days', $subScription['Subscription']['free_trail_days']);
				$this->set('price', $subScription['Subscription']['price']);
				$this->Sendgrid->sendgridsmtp($this->Email);*/
				$message = "success";	
			    }			    
			}
		    }
		}
	} else {
	    $message = 'Error in Creating Company';
	}
	$msg['message'] = 'success';
	if ($message != "success") {
	    if ($company_id) {
		$this->loadModel('Company');
		$this->Company->delete($company_id);
	    }
	    if ($comp_usr['CompanyUser']['user_id']) {
		$this->loadModel('User');
		$this->User->delete($comp_usr['CompanyUser']['user_id']);
	    }
	    if ($companyUid) {
		$this->loadModel('CompanyUser');
		$this->CompanyUser->delete($companyUid);
	    }
	     $msg['message'] = 'Error in Creating Company';
	}	
	echo json_encode($msg);
	exit;
    }
   
    function checkToken() {
        $this->layout = 'ajax';
        if ($this->request->data['ajax']) {
            echo json_encode(array('token' => $_SESSION['CSRFTOKEN']));
            exit;
        } else {
            print "You are not authorized to do this operation.";
            exit;
        }
    }
   
}

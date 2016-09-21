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
class ProjectsController extends AppController {
    public $name = 'Projects';
	public $components = array('Format','Postcase','Tmzone','Sendgrid');
	
	function beforeRender()
	{
		if(SES_TYPE == 3) {
			//$this->redirect(HTTP_ROOT."dashboard");
		}
		/*if($this->action === 'index') {
			$this->set(	'scaffoldFields', array( 'name', 'short_name', 'isactive', 'dt_created' ) );
		}
		if($this->action === 'view') {
			$this->set(	'scaffoldFields', array( 'name', 'short_name', 'isactive', 'dt_created','dt_updated' ) );
		}
		if($this->action === 'edit') {
			$this->set(	'scaffoldFields', array( 'name', 'short_name') );
		}
		if($this->action === 'add') {
			$this->set(	'scaffoldFields', array( 'name', 'short_name') );
		}*/
	}
	function ajax_check_project_exists()
	{
		$this->layout = 'ajax';
		
		$this->Project->recursive = -1;
		
		$name = $this->params['data']['name'];
		$shortname = $this->params['data']['shortname'];
		
		if(isset($this->params['data']['uniqid'])) {
			$uniqid = $this->params['data']['uniqid'];
			$conditions = array('Project.name' => urldecode($name),'Project.company_id'=>SES_COMP,'Project.uniq_id !='=>$uniqid);
		}
		else {
			$conditions = array('Project.name' => urldecode($name),'Project.company_id'=>SES_COMP);
		}
		
		$chkName = $this->Project->find('first', array('conditions' => $conditions));

		if(isset($chkName['Project']['id']) && $chkName['Project']['id']) {
			echo "Project";
		}
		else {
			if(isset($this->params['data']['uniqid'])) {
				$uniqid = $this->params['data']['uniqid'];
				$conditions = array('Project.short_name' => urldecode($shortname),'Project.company_id'=>SES_COMP,'Project.uniq_id !='=>$uniqid);
			}
			else {
				$conditions = array('Project.short_name' => urldecode($shortname),'Project.company_id'=>SES_COMP);
			}
			$chkShortName = $this->Project->find('first', array('conditions' => $conditions));
			if(isset($chkShortName['Project']['id']) && $chkShortName['Project']['id']) {
				echo "ShortName";
			}
		}
		exit;
	}
	
	function ajax_edit_project() {
	    $this->layout='ajax';
	    $uniqid = NULL; $uname = NULL;$projArr = array(); $getTech = array();
	    
	    if (isset($this->request->data['pid']) && $this->request->data['pid']) {
		$uniqid = $this->request->data['pid'];
		$this->loadModel("Project");
		$this->Project->recursive = -1;
		$projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $uniqid, 'Project.company_id' => SES_COMP)));
		if (count($projArr)) {
		    $this->loadModel("User");
		    $this->User->recursive = -1;
		    $getUser = $this->User->find("first", array('conditions' => array('User.isactive' => 1, 'User.id' => $projArr['Project']['user_id']), 'fields' => array('User.name')));
		    if (count($getUser)) {
			$uname = $getUser['User']['name'];
		    }
		}
	    }
	    $this->set('uniqid', $uniqid);
	    $this->set('uname', $uname);
	    $this->set('projArr', $projArr);
	    
	    $getProjUsers = $this->Project->query("select User.name,ProjectUser.default_email,User.id,Project.id,ProjectUser.id from project_users as ProjectUser, users as User, projects as Project where User.id=ProjectUser.user_id and Project.uniq_id='".$_GET['pid']."' and Project.id=ProjectUser.project_id and User.isactive='1'");
	    $this->set('getProjUsers',$getProjUsers);

	    $this->loadModel("Easycase");
		$this->Easycase->recursive = -1;
		$quickMem = $this->Easycase->getMemebers($uniqid,'default');
		$this->set('quickMem',$quickMem);
	    $prj = $this->Project->findByUniqId($uniqid);
	    $this->set('defaultAssign',$prj['Project']['default_assign']);
	}
	
	function settings($img = null) {
		
		if(isset($this->params['data']['Project'])) {
		    $this->loadModel("ProjectUser");
		    $postProject['Project'] = $this->params['data']['Project'];
		    $postProject['Project']['name'] = trim($postProject['Project']['name']);
		    $postProject['Project']['short_name'] = trim($postProject['Project']['short_name']);
		    
		    if($postProject['Project']['validateprj'] == 1)	{
			$prjid = $postProject['Project']['id'];
			$redirect = HTTP_ROOT."projects/manage/";
			$page_lmt = $postProject['Project']['pg'];
			if(intval($page_lmt) > 1) {
			    $redirect.="?page=".$page_lmt;
			}
			
			$findName = $this->Project->query("SELECT id FROM projects WHERE name='".addslashes($postProject['Project']['name'])."' AND id!=".$prjid ." AND company_id='".SES_COMP."'");
			if(count($findName)) {
			    $this->Session->write("ERROR","Project name '".$postProject['Project']['name']."' already exists");
			    $this->redirect($redirect);
			}
			
			$findShrtName = $this->Project->query("SELECT id FROM projects WHERE short_name='".addslashes($postProject['Project']['short_name'])."' AND id!=".$prjid." AND company_id='".SES_COMP."'");
			if(!empty($findShrtName)) {
			    $this->Session->write("ERROR","Project short name '".$postProject['Project']['short_name']."' already exists");
			    $this->redirect($redirect);
			}
			
			$postProject['Project']['dt_updated'] = GMT_DATETIME;
			if($this->Project->save($postProject)) {
			    $this->Session->write("SUCCESS","'".strip_tags($postProject['Project']['name'])."' saved successfully");
			    $this->redirect($redirect);
			}
		    } else {
			//$this->redirect(HTTP_ROOT."projects/settings/?pid=".$postProject['Project']['uniq']);
		    }
		}

		
		/*$uniqid = NULL; $uname = NULL;
		$projArr = array(); $getTech = array();
		if(isset($_GET['pid']) && $_GET['pid']) {
			$uniqid = $_GET['pid'];
			$this->Project->recursive = -1;
			//$uniqid = Sanitize::clean($uniqid, array('encode' => false));
			$projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id'=>$uniqid,'Project.company_id'=>SES_COMP)));
			if(count($projArr))
			{
				$User = ClassRegistry::init('User');
				$User->recursive = -1;
				$getUser = $User->find("first",array('conditions'=>array('User.isactive'=>1,'User.id'=>$projArr['Project']['user_id']),'fields'=>array('User.name')));
				if(count($getUser)){
					$uname = $getUser['User']['name'];
				}
				
				$Technology = ClassRegistry::init('Technology');
				$getTech = $Technology->find("all",array('conditions'=>array('Technology.name'<>'')));
			}else{
				$this->redirect(HTTP_ROOT."projects/gridview/");
			}
		}
		$this->set('getTech',$getTech);
		$this->set('projArr',$projArr);
		$this->set('uniqid',$uniqid);
		$this->set('uname',$uname);
		This multi section is commenting is due to:
		 implement in ajax_edit_project() in ajax.
		 */
		
		/*$getProjUsers = $this->Project->query("select User.name,ProjectUser.default_email,User.id,Project.id,ProjectUser.id from project_users as ProjectUser, users as User, projects as Project where User.id=ProjectUser.user_id and Project.uniq_id='".$_GET['pid']."' and Project.id=ProjectUser.project_id and User.isactive='1'");
		$this->set('getProjUsers',$getProjUsers);

		$this->loadModel("Easycase");
		$this->Easycase->recursive = -1;
		$quickMem = $this->Easycase->getMemebers($_GET['pid'],'default');
		$this->set('quickMem',$quickMem);
		$prj = $this->Project->findByUniqId($uniqid);
		$defaultAssign = $prj['Project']['default_assign'];
		$this->set('defaultAssign',$defaultAssign);*/
	}
	function manage($projtype=NULL)
	{
		$page_limit = 17;
		if($projtype == 'inactive') {
			$page_limit = 18;
		}
		$this->Project->recursive = -1;
		$pjid = NULL;
		if(isset($_GET['id']) && $_GET['id']){
			$pjid = $_GET['id'];
		}
		if(isset($_GET['proj_srch']) && $_GET['proj_srch']){
			$pjname = htmlentities(strip_tags($_GET['proj_srch']));
		    $this->set('prjsrch','project search');
		}
		if(isset($_GET['page']) && $_GET['page']) {
			$page = $_GET['page'];
		}
		if(trim($pjid)){
			$project = "Project";
			$getProj = $this->Project->find('first', array('conditions' => array('Project.id'=>$pjid,'Project.company_id'=>SES_COMP),'fields' => array('Project.name','Project.id')));
			if(isset($getProj['Project']['name']) && $getProj['Project']['name']){
				$project = $getProj['Project']['name'];
			}
			if($getProj['Project']['id']) {
				if(isset($_GET['action']) && $_GET['action'] == "activate"){
					$this->Project->query("UPDATE projects SET isactive='1' WHERE id=".$getProj['Project']['id']);
					$this->Session->write("SUCCESS","'".$project."' activated successfully");
					$this->redirect(HTTP_ROOT."projects/manage/");
				}
				if(isset($_GET['action']) && $_GET['action'] == "delete"){
					$this->Project->query("DELETE FROM projects WHERE id=".$getProj['Project']['id']);
					
					$ProjectUser = ClassRegistry::init('ProjectUser');
					$ProjectUser->recursive = -1;
					$ProjectUser->query("DELETE FROM project_users WHERE project_id=".$getProj['Project']['id']);
					
					$this->Session->write("SUCCESS","'".$project."' deleted successfully");
					$this->redirect(HTTP_ROOT."projects/manage/");
				}
				if(isset($_GET['action']) && $_GET['action'] == "deactivate"){
					$this->Project->query("UPDATE projects SET isactive='2' WHERE id=".$getProj['Project']['id']);
					$this->Session->write("SUCCESS","'".$project."' deactivated successfully");
					$this->redirect(HTTP_ROOT."projects/manage/inactive");
				}
				
			}else {
				$this->Session->write("ERROR","Invalid or Wrong action!");
				$this->redirect(HTTP_ROOT."projects/manage");
			}
		}
		
		$action = ""; $uniqid = ""; $query = "";
		if(isset($_GET['uniqid']) && $_GET['uniqid']) {
			$uniqid = $_GET['uniqid'];
		}
		
		if($projtype == "inactive") {
			$query = "AND Project.isactive='2'";
		}else {
			$query = "AND Project.isactive='1'";
		}
		if(isset($_GET['project']) && $_GET['project']) {
			$query .= " AND Project.uniq_id='".$_GET['project']."'";
		}
		$query .= " AND Project.company_id='".SES_COMP."'";
		if(isset($_GET['action']) && $_GET['action']) {
			$action = $_GET['action'];
		}
		$page = 1;
		$pageprev=1;
		if(isset($_GET['page']) && $_GET['page']){
			$page = $_GET['page'];
		}
		$limit1 = $page*$page_limit-$page_limit;
		$limit2 = $page_limit;
		
		$prjselect = $this->Project->query("SELECT name FROM projects AS Project WHERE name!='' ".$query." ORDER BY dt_created DESC");
		$arrprj=array();
		foreach($prjselect as $pjall){
			if(isset($pjall['Project']['name']) && !empty($pjall['Project']['name'])){
				array_push($arrprj,substr(trim($pjall['Project']['name']),0,1) );
			}
		}
		if(isset($_GET['prj']) && $_GET['prj']){
			//$_GET['prj'] = Sanitize::clean($_GET['prj'], array('encode' => false));
			$_GET['prj']=chr($_GET['prj']);
			$pj=$_GET['prj']."%";
			$query .= " AND Project.name LIKE '".addslashes($pj)."'";
		}
		
		if(SES_TYPE == 3) {
			$query .= " AND Project.user_id=".$this->Auth->user('id');
			if($pjname){
				$prjAllArr = $this->Project->query("SELECT SQL_CALC_FOUND_ROWS Project.id,uniq_id,name,Project.user_id,project_type,short_name,Project.isactive,dt_updated,(select count(easycases.id) as tot from easycases where easycases.project_id=Project.id and easycases.istype='1' and easycases.isactive='1') as totalcase,(select ROUND(SUM(easycases.hours), 1) as hours from easycases where easycases.project_id=Project.id and easycases.reply_type='0' and easycases.isactive='1') as totalhours,(select count(company_users.id) as tot from company_users, project_users where project_users.user_id = company_users.user_id and project_users.company_id = company_users.company_id and company_users.is_active = 1
	and project_users.project_id = Project.id) as totusers,(SELECT SUM(case_files.file_size) AS file_size FROM case_files WHERE case_files.project_id=Project.id) AS storage_used FROM projects AS Project WHERE Project.name!='' ".$query." and name LIKE '%".addslashes($pjname)."%' ORDER BY dt_created DESC LIMIT $limit1,$limit2 ");                   
			}else{
				$prjAllArr = $this->Project->query("SELECT SQL_CALC_FOUND_ROWS Project.id,uniq_id,name,Project.user_id,project_type,short_name,Project.isactive,dt_updated,(select count(easycases.id) as tot from easycases where easycases.project_id=Project.id and easycases.istype='1' and easycases.isactive='1') as totalcase,(select ROUND(SUM(easycases.hours), 1) as hours from easycases where easycases.project_id=Project.id and easycases.reply_type='0' and easycases.isactive='1') as totalhours,(select count(company_users.id) as tot from company_users, project_users where project_users.user_id = company_users.user_id and project_users.company_id = company_users.company_id and company_users.is_active = 1
	and project_users.project_id = Project.id) as totusers,(SELECT SUM(case_files.file_size) AS file_size FROM case_files WHERE case_files.project_id=Project.id) AS storage_used FROM projects AS Project WHERE Project.name!='' ".$query." ORDER BY dt_created DESC LIMIT $limit1,$limit2");
		
			}
		}
		else {
			if($pjname){
				$prjAllArr = $this->Project->query("SELECT SQL_CALC_FOUND_ROWS  id,uniq_id,name,user_id,project_type,short_name,isactive,dt_updated,(select count(easycases.id) as tot from easycases where easycases.project_id=Project.id and easycases.istype='1' and easycases.isactive='1') as totalcase,(select ROUND(SUM(easycases.hours), 1) as hours from easycases where easycases.project_id=Project.id and easycases.reply_type='0' and easycases.isactive='1') as totalhours,(select count(company_users.id) as tot from company_users, project_users where project_users.user_id = company_users.user_id and project_users.company_id = company_users.company_id and company_users.is_active = 1
	and project_users.project_id = Project.id) as totusers,(SELECT SUM(case_files.file_size) AS file_size  FROM case_files WHERE case_files.project_id=Project.id) AS storage_used FROM projects AS Project WHERE name!='' ".$query." and name LIKE '%".addslashes($pjname)."%' ORDER BY dt_created DESC LIMIT $limit1,$limit2 ");                   
			}else{
				$prjAllArr = $this->Project->query("SELECT SQL_CALC_FOUND_ROWS id,uniq_id,name,user_id,project_type,short_name,isactive,dt_updated,(select count(easycases.id) as tot from easycases where easycases.project_id=Project.id and easycases.istype='1' and easycases.isactive='1') as totalcase,(select ROUND(SUM(easycases.hours), 1) as hours from easycases where easycases.project_id=Project.id and easycases.reply_type='0' and easycases.isactive='1') as totalhours,(select count(company_users.id) as tot from company_users, project_users where project_users.user_id = company_users.user_id and project_users.company_id = company_users.company_id and company_users.is_active = 1
	and project_users.project_id = Project.id) as totusers,(SELECT SUM(case_files.file_size) AS file_size  FROM case_files WHERE case_files.project_id=Project.id) AS storage_used FROM projects AS Project WHERE name!='' ".$query." ORDER BY dt_created DESC LIMIT $limit1,$limit2");
		
			}
		}
		
		//pr($prjAllArr);exit;
		  
		$tot = $this->Project->query("SELECT FOUND_ROWS() as total");
		$CaseCount = $tot[0][0]['total'];
		$active_project_cnt = 0;$inactive_project_cnt=0;
		if(SES_TYPE == 3) {
			$grpcount = $this->Project->query('SELECT count(Project.id) as prjcnt, Project.isactive FROM projects AS Project WHERE Project.user_id='.$this->Auth->user('id').' AND Project.company_id='.SES_COMP.' GROUP BY Project.isactive');	
		}
		else {
			$grpcount = $this->Project->query('SELECT count(Project.id) as prjcnt, Project.isactive FROM projects AS Project WHERE Project.company_id='.SES_COMP.' GROUP BY Project.isactive');	
		}
		if($grpcount){
			foreach($grpcount AS $key=>$val){
				if($val['Project']['isactive']==1){
					$active_project_cnt = $val['0']['prjcnt'];
				}elseif($val['Project']['isactive']==2){
					$inactive_project_cnt = $val['0']['prjcnt'];
				}
			}
		}
		$this->set('inactive_project_cnt',$inactive_project_cnt);
		$this->set('active_project_cnt',$active_project_cnt);
		
		$this->set('caseCount',$tot[0][0]['total']);
		
    	$this->set(compact('data'));
		  $this->set('total_records',$prjAllArr);
          $this->set('proj_srch',  $pjname);
		  $this->set('page_limit',$page_limit);
		  $this->set('page',$page);
		  $this->set('pageprev',$pageprev);
		  $count_grid = count($prjAllArr);
		  $this->set('count_grid',$count_grid);
		  $this->set('prjAllArr',$prjAllArr);
		  $this->set('projtype',$projtype);
		  $this->set('action',$action);
		  $this->set('uniqid',$uniqid);
		  $this->set('arrprj',$arrprj);
		  $this->set('page_limit',$page_limit);
		  $this->set('casePage',$page);
	}
	function add_project(){
		$Company = ClassRegistry::init('Company');
		$comp = $Company->find('first', array('fields' => array('Company.name')));
		$userscls = ClassRegistry::init('User');
		$companyusercls = ClassRegistry::init('CompanyUser');
		$postProject['Project'] = $this->params['data']['Project'];
		if(isset($this->data['Project']['members_list']) && $this->data['Project']['members_list']){
			$emaillist = trim(trim($this->data['Project']['members_list']),',');
			if(strstr(trim($emaillist),',')){
				$emailid = explode(',', $emaillist);
			}else{
				$emailid = explode(',', $emaillist);
			}
			$emailarr='';
			foreach($emailid AS $ind =>$data){
				if(trim($data)!=''){
					$emailarr[$ind]= trim($data);
					$cond .= " (email LIKE '%".trim($data)."%') OR";
				}
			}
			//print_r($emailarr);exit;
			if($emailarr!=''){
				$emailarr = array_unique($emailarr);
				$cond = substr($cond, 0,  strlen($cond)-2);
				$userlist = $userscls->find('list',array('conditions'=>array($cond),'fields'=>array('id','email')));
				if($userlist){
					$compuserlist = $companyusercls->find('list',array('conditions'=>array('company_id'=>SES_COMP,'user_id'=>array_keys($userlist),'is_active'=>1),'fields'=>array('CompanyUser.id','CompanyUser.user_id')));
					if($compuserlist){
						foreach($compuserlist AS $k1=>$value){
							$postProject['Project']['members'][]= $value;
							$removeduserlist[] = $userlist[$value];
							//$index = array_search($userlist[$value],$emailarr);
							//unset($emailarr[$index]);
						}
						foreach($emailarr AS $key1=>$edata){
							if(in_array(trim($edata),$removeduserlist)){
								unset($emailarr[$key1]);
							}
						}
					}
				}
			}
		}
		$memberslist ='';
		if($postProject['Project']['members']){
			$memberslist = array_unique($postProject['Project']['members']);
		}elseif(!$GLOBALS['project_count']){
			$memberslist[] = SES_ID;
		}
		if(isset($this->params['data']['Project']) && $postProject['Project']['validate'] == 1) {
			$findName = $this->Project->find('first',array('conditions'=>array('Project.name'=>$postProject['Project']['name'],'Project.company_id'=>SES_ID),'fields'=>array('Project.id')));
			if($findName) {
				$this->Session->write("ERROR","Project name '".$postProject['Project']['name']."' already exists");
				$this->redirect(HTTP_ROOT."projects/manage/");
			}
			$findShrtName = $this->Project->find('first',array('conditions'=>array('Project.short_name'=>$postProject['Project']['short_name'],'Project.company_id'=>SES_ID),'fields'=>array('Project.id')));
			if($findShrtName) {
				$this->Session->write("ERROR","Project short name '".$postProject['Project']['short_name']."' already exists");
				$this->redirect(HTTP_ROOT."projects/manage/");
			}
			
			$postProject['Project']['uniq_id'] = trim($postProject['Project']['name']);
			$postProject['Project']['short_name'] = trim($postProject['Project']['short_name']);
			
			$prjUniqId = md5(uniqid());
			$postProject['Project']['uniq_id'] = $prjUniqId;
			$postProject['Project']['user_id'] = SES_ID;
			$postProject['Project']['project_type'] = 1;
			if(isset($postProject['Project']['default_assign']) && !empty($postProject['Project']['default_assign'])){
				$postProject['Project']['default_assign'] = $postProject['Project']['default_assign'];	
			}else{
				$postProject['Project']['default_assign'] = SES_ID;
			}
			$postProject['Project']['isactive'] = 1;
			$postProject['Project']['name'] = trim($postProject['Project']['name']);
			$postProject['Project']['dt_created'] = GMT_DATETIME;
			$postProject['Project']['company_id'] = SES_COMP;
			
			if($this->Project->save($postProject)){
				$prjid = $this->Project->getLastInsertID();
				
				$User = ClassRegistry::init('User');
				$User->recursive = -1;
				//$adminArr = $User->find("all",array('conditions'=>array('User.isactive'=>1,'User.istype'=>1),'fields'=>array('User.id')));
				
				$ProjectUser = ClassRegistry::init('ProjectUser');
				$ProjectUser->recursive = -1;
				$getLastId = $ProjectUser->query("SELECT MAX(id) as maxid FROM project_users");
				$lastid = $getLastId[0][0]['maxid']+1;
				if(!empty($memberslist)){
					foreach($memberslist as $members) {
						$ProjUsr['ProjectUser']['id'] = $lastid;
						$ProjUsr['ProjectUser']['project_id'] = $prjid;
						$ProjUsr['ProjectUser']['user_id'] = $members;
						$ProjUsr['ProjectUser']['company_id'] = SES_COMP;
						$ProjUsr['ProjectUser']['default_email'] = 1;
						$ProjUsr['ProjectUser']['istype'] = 1;
						$ProjUsr['ProjectUser']['dt_visited'] = GMT_DATETIME;
						$ProjectUser->saveAll($ProjUsr);
						$lastid = $lastid+1;
						if($this->Auth->user('id')!=$members){
							$this->generateMsgAndSendPjMail($prjid,$members,$comp);
						}
					}
				}
				
				
				
				if(isset($postProject['Project']['module_id']) && isset($prjid) && $postProject['Project']['module_id']){
					//Add relation when template is added
					$post_temp['TemplateModuleCase']['template_module_id']=$postProject['Project']['module_id'];
					$post_temp['TemplateModuleCase']['user_id']=SES_ID;
					$post_temp['TemplateModuleCase']['company_id']=SES_COMP;
					$post_temp['TemplateModuleCase']['project_id']=$prjid;
					$s=ClassRegistry::init('TemplateModuleCase')->save($post_temp);

					$this->loadModel("ProjectTemplateCase");
					$pjtemp = $this->ProjectTemplateCase->find('all', array('conditions'=> array('ProjectTemplateCase.template_id'=>$postProject['Project']['module_id']), 'order'=>'ProjectTemplateCase.sort ASC'));
					$Easycase = ClassRegistry::init('Easycase');
					$Easycase->recursive = -1;
					$CaseActivity = ClassRegistry::init('CaseActivity');
					foreach($pjtemp as $temp){
						$postCases['Easycase']['uniq_id'] = md5(uniqid());
						$postCases['Easycase']['project_id'] = $prjid;
						$postCases['Easycase']['user_id'] = SES_ID;
						$postCases['Easycase']['type_id'] = 2;
						$postCases['Easycase']['priority'] = 1;
						$postCases['Easycase']['title'] = $temp['ProjectTemplateCase']['title'];
						$postCases['Easycase']['message'] = $temp['ProjectTemplateCase']['description'];
						$postCases['Easycase']['assign_to'] = SES_ID;
						$postCases['Easycase']['due_date'] = "";
						$postCases['Easycase']['istype'] = 1;
						$postCases['Easycase']['format'] = 2;
						$postCases['Easycase']['status'] = 1;
						$postCases['Easycase']['legend'] = 1;
						$postCases['Easycase']['isactive'] = 1;
						$postCases['Easycase']['dt_created'] = GMT_DATETIME;
						$postCases['Easycase']['actual_dt_created'] = GMT_DATETIME;
						$caseNoArr = $Easycase->find('first', array('conditions' => array('Easycase.project_id' => $prjid),'fields' => array('MAX(Easycase.case_no) as caseno')));
						$caseNo = $caseNoArr[0]['caseno']+1;
						$postCases['Easycase']['case_no'] = $caseNo;
						if($Easycase->saveAll($postCases))
						{
							$caseid = $Easycase->getLastInsertID();
							$CaseActivity->recursive = -1;
							$CaseAct['easycase_id'] = $caseid;
							$CaseAct['user_id'] = SES_ID;
							$CaseAct['project_id'] = $prjid;
							$CaseAct['case_no'] = $caseNo;
							$CaseAct['type'] = 1;
							$CaseAct['dt_created'] = GMT_DATETIME;
							$CaseActivity->saveAll($CaseAct);
						}
					}
				}
				
				if($emailarr!=''){
					$inviteduserlist = $this->Postcase->invitenewuser($emailarr,$prjid,$this);
				}
				$this->Session->write("SUCCESS","'".strip_tags($postProject['Project']['name'])."' created successfully");
			
				setcookie('LAST_CREATED_PROJ',$prjid,time()+3600,'/',DOMAIN_COOKIE,false,false);
				
				$CompanyUser = ClassRegistry::init('CompanyUser');
				$checkMem = $CompanyUser->find('all',array('conditions'=>array('CompanyUser.company_id'=>SES_COMP,'CompanyUser.is_active'=>1)));
				if(isset($checkMem['CompanyUser']['id']) && $checkMem['CompanyUser']['id']) {
//					$ProjectUser = ClassRegistry::init("ProjectUser");
//					$checkProjusr = $ProjectUser->find('first',array('conditions'=>array('ProjectUser.project_id'=>$prjid,'ProjectUser.user_id !='=>SES_ID)));
//					
//					if(isset($checkProjusr['ProjectUser']['id']) && $checkProjusr['ProjectUser']['id']) {
//						//setcookie('CREATE_CASE',1,time()+3600,'/',DOMAIN_COOKIE,false,false);
//						$this->redirect(HTTP_ROOT."dashboard");
//					}
//					else {
						if(count($memberslist)< count($checkMem)){
							setcookie('LAST_PROJ',$prjid,time()+3600,'/',DOMAIN_COOKIE,false,false);
						}	
						setcookie('ASSIGN_USER',$prjid,time()+3600,'/',DOMAIN_COOKIE,false,false);
						setcookie('PROJ_NAME',trim($postProject['Project']['name']),time()+3600,'/',DOMAIN_COOKIE,false,false);
						$this->redirect(HTTP_ROOT."projects/manage");
					
				}else {
					//setcookie('INVITE_USER',1,time()+3600,'/',DOMAIN_COOKIE,false,false);
					//$this->redirect(HTTP_ROOT."dashboard");
					if($GLOBALS['project_count']>=1){
						if(count($memberslist)< count($checkMem)){
					    setcookie('LAST_PROJ',$prjid,time()+3600,'/',DOMAIN_COOKIE,false,false);
						}
						$this->redirect(HTTP_ROOT."projects/manage");
					}else{
						$this->redirect(HTTP_ROOT.'onbording');
					}
					
				}
				
				//setcookie('NEW_PROJECT',$prjid,time()+3600,'/',DOMAIN_COOKIE,false,false);
				
			}
		}
		else {
			$this->Session->write("ERROR","Error creating project");
			$this->redirect(HTTP_ROOT."projects/manage/");
		}
	}
	
	function check_proj_short_name()
	{
		$this->layout='ajax';
		ob_clean();
		if(isset($this->params['data']['shortname']) && trim($this->params['data']['shortname']))
		{
			$count = $this->Project->find("count",array("conditions"=>array('Project.short_name'=>trim(strtoupper($this->params['data']['shortname'])),'Project.company_id'=>SES_COMP),'fields'=>'DISTINCT Project.id'));
			$this->set('count',$count);
			$this->set('shortname',trim(strtoupper($this->params['data']['shortname'])));
		}
	}
	function assign()
	{
		if(isset($this->request->data['ProjectUser']['project_id'])) {


			


			$projectid = $this->request->data['ProjectUser']['project_id'];
			
			$lists1 = $this->request->data['ProjectUser']['mem_avl'].",";
			$lis1 = explode(",",$lists1);

				

			$lists2 = $this->request->data['ProjectUser']['mem_ext'];

			$lis2 = explode(",",$lists2);

	
			$lis1 = array_filter($lis1);
			$lis2 = array_filter($lis2);



			
			$ProjectUser = ClassRegistry::init('ProjectUser');
			$ProjectUser->recursive = -1;
			$getLastId = $ProjectUser->query("SELECT MAX(id) as maxid FROM project_users");
			$lastid = $getLastId[0][0]['maxid'];
			
			$query = "";
			$Easycase = ClassRegistry::init('Easycase');
			$Easycase->recursive = -1;
			$getcaseIds = $Easycase->find("all",array('conditions', array('Easycase.project_id' => $projectid, 'Easycase.istype' => 1), 'fields' => array('Easycase.id')));
			
			$CaseUserEmail = ClassRegistry::init('CaseUserEmail');
			$CaseUserEmail->recursive = -1;
			if(count($lis1)) {
				foreach($lis1 as $ids1)
				{
					$checkAvlMem1 = $ProjectUser->find('count', array('conditions' => array('ProjectUser.user_id'=>$ids1,'ProjectUser.project_id'=>$projectid), 'fields'=>'DISTINCT ProjectUser.id'));
					if($checkAvlMem1) {
						$ProjectUser->query("DELETE FROM project_users WHERE user_id=".$ids1." AND project_id=".$projectid);
						
						if(count($getcaseIds))
						{
							foreach($getcaseIds as $getid)
							{
								if($getid['Easycase']['id']) {
									$CaseUserEmail->query("UPDATE case_user_emails SET ismail='0' WHERE user_id=".$ids1." AND easycase_id=".$getid['Easycase']['id']);
								}
							}
						}	
					}
				}
			}
			if(count($lis2)) {
				foreach($lis2 as $ids2)
				{
					$checkAvlMem2 = $ProjectUser->find('count', array('conditions' => array('ProjectUser.user_id'=>$ids2,'ProjectUser.project_id'=>$projectid), 'fields'=>'DISTINCT id'));
					if($checkAvlMem2 == 0) {
						$lastid++;
						$ProjectUser->query("INSERT INTO project_users SET id='".$lastid."',user_id=".$ids2.",project_id=".$projectid.",company_id='".SES_COMP."',dt_visited='".GMT_DATETIME."'");
						
						if(count($getcaseIds))
						{
							foreach($getcaseIds as $getid)
							{
								if($getid['Easycase']['id']) {
									$CaseUserEmail->query("UPDATE case_user_emails SET ismail='1' WHERE user_id=".$ids2." AND easycase_id=".$getid['Easycase']['id']);
								}
							}
						}
					}
				}
			}
			
			$prjid = $this->request->data['ProjectUser']['project_id'];
			$getProj = $this->Project->find('first', array('conditions' => array('Project.isactive'=>1,'Project.id'=>$prjid),'fields' => array('Project.uniq_id','Project.name')));
			
			$this->Session->write("SUCCESS","User(s) successfully assigned to '".$getProj['Project']['name']."'");
			$this->redirect(HTTP_ROOT."projects/assign/?pid=".$getProj['Project']['uniq_id']);
		}
		
		$pid = NULL; $projId = NULL;
		$memsAvlArr = array(); $custAvlArr = array(); $memsExtArr = array(); $custExtArr = array();
		$this->Project->recursive = -1;
		$projArr = $this->Project->find('all', array('conditions' => array('Project.isactive'=>1,'Project.name !='=>'','Project.company_id'=>SES_COMP),'fields' => array('DISTINCT Project.uniq_id,Project.name')));
		
		if(isset($_GET['pid']) && $_GET['pid'])
		{
			$pid = $_GET['pid'];
			
			$getProj = $this->Project->find('first', array('conditions' => array('Project.isactive'=>1,'Project.uniq_id'=>$pid,'Project.company_id'=>SES_COMP),'fields' => array('Project.id')));
			if(count($getProj['Project']))
			{
				$projId = $getProj['Project']['id'];
				
				$ProjectUser = ClassRegistry::init('ProjectUser');
				//$ProjectUser->unbindModel(array('belongsTo' => array('Project')));
				
				if(SES_TYPE == 1) {
					$memsAvlArr = $ProjectUser->query("SELECT DISTINCT User.id,User.name,User.email,User.istype,User.short_name,CompanyUser.user_type FROM users AS User, company_users AS CompanyUser WHERE User.id = CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND User.isactive='1' AND User.name!='' AND NOT EXISTS(SELECT ProjectUser.user_id FROM project_users AS ProjectUser WHERE ProjectUser.user_id=User.id AND ProjectUser.project_id=".$projId.") ORDER BY User.istype ASC,User.name");
					
					$memsExtArr = $ProjectUser->query("SELECT DISTINCT User.id,User.name,User.email,User.istype,User.short_name,CompanyUser.user_type FROM users AS User, company_users AS CompanyUser,project_users AS ProjectUser WHERE ProjectUser.user_id=User.id AND User.id = CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND User.isactive='1' AND User.name!='' AND ProjectUser.project_id=".$projId." ORDER BY User.istype ASC,User.name");
					
				}
				else {
					$memsAvlArr = $ProjectUser->query("SELECT DISTINCT User.id,User.name,User.email,User.istype,User.short_name,CompanyUser.user_type FROM users AS User, company_users AS CompanyUser WHERE User.id = CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND CompanyUser.user_type!='1' AND User.isactive='1' AND User.name!=''  AND NOT EXISTS(SELECT ProjectUser.user_id FROM project_users AS ProjectUser WHERE ProjectUser.user_id=User.id AND ProjectUser.project_id=".$projId.") ORDER BY User.istype ASC,User.name");


					
					$memsExtArr = $ProjectUser->query("SELECT DISTINCT User.id,User.name,User.email,User.istype,User.short_name,CompanyUser.user_type FROM users AS User, company_users AS CompanyUser,project_users AS ProjectUser WHERE ProjectUser.user_id=User.id AND User.id = CompanyUser.user_id AND CompanyUser.user_type!='1' AND CompanyUser.company_id='".SES_COMP."' AND User.isactive='1' AND User.name!='' AND ProjectUser.project_id=".$projId." ORDER BY User.istype ASC,User.name");
				}
			}
		}
		$this->set('projArr',$projArr);
		$this->set('memsAvlArr',$memsAvlArr);
		//$this->set('custAvlArr',$custAvlArr);
		$this->set('memsExtArr',$memsExtArr);
		//$this->set('custExtArr',$custExtArr);
		$this->set('pid',$pid);
		$this->set('projId',$projId);
	}

	function gridview($projtype=NULL){
		$page_limit = 15;
		$this->Project->recursive = -1;
		$pjid = NULL;
		if(isset($_GET['id']) && $_GET['id']){
			$pjid = $_GET['id'];
		}
		if(isset($_GET['proj_srch']) && $_GET['proj_srch']){
			$pjname = htmlentities(strip_tags($_GET['proj_srch']));
            $this->set('prjsrch','project search');
		}
        if(isset($_GET['page']) && $_GET['page']) {
			$page = $_GET['page'];
		}
		if(trim($pjid)){
			$project = "Project";
			$getProj = $this->Project->find('first', array('conditions' => array('Project.id'=>$pjid,'Project.company_id'=>SES_COMP),'fields' => array('Project.name','Project.id')));
			if(isset($getProj['Project']['name']) && $getProj['Project']['name']){
				$project = $getProj['Project']['name'];
			}
			if($getProj['Project']['id']) {
				if(isset($_GET['action']) && $_GET['action'] == "activate"){
					$this->Project->query("UPDATE projects SET isactive='1' WHERE id=".$getProj['Project']['id']);
					$this->Session->write("SUCCESS","'".$project."' activated successfully");
					$redirect = HTTP_ROOT."projects/manage/inactive/";
					if(isset($_GET['pg']) && (intval($_GET['pg']) > 1)){
					    $redirect = HTTP_ROOT."projects/manage/inactive/?page=".$_GET['pg'];
					}
					$this->redirect($redirect);
				}
				if(isset($_GET['action']) && $_GET['action'] == "delete"){
					$this->Project->query("DELETE FROM projects WHERE id=".$getProj['Project']['id']);
					
					$ProjectUser = ClassRegistry::init('ProjectUser');
					$ProjectUser->recursive = -1;
					$ProjectUser->query("DELETE FROM project_users WHERE project_id=".$getProj['Project']['id']);
					
					$this->Session->write("SUCCESS","'".$project."' deleted successfully");
					$this->redirect(HTTP_ROOT."projects/gridview/");
				}
				if(isset($_GET['action']) && $_GET['action'] == "deactivate"){
					$redirect = HTTP_ROOT."projects/manage/";
					if(isset($_GET['pg']) && (intval($_GET['pg']) > 1)){
					    $redirect = HTTP_ROOT."projects/manage/?page=".$_GET['pg'];
					}
					$this->Project->query("UPDATE projects SET isactive='2' WHERE id=".$getProj['Project']['id']);
					$this->Session->write("SUCCESS","'".$project."' deactivated successfully");
					$this->redirect($redirect);
				}
				
			}else {
				$this->Session->write("ERROR","Invalid or Wrong action!");
				$this->redirect(HTTP_ROOT."projects/gridview");
			}
		}
		
		$action = ""; $uniqid = ""; $query = "";
		if(isset($_GET['uniqid']) && $_GET['uniqid']) {
			$uniqid = $_GET['uniqid'];
		}
		if($projtype == "disabled") {
			$query = "AND isactive='2'";
		}else {
			$query = "AND isactive='1'";
		}
		$query .= " AND company_id='".SES_COMP."'";
		if(isset($_GET['action']) && $_GET['action']) {
			$action = $_GET['action'];
		}
		$page = 1;
		$pageprev=1;
		if(isset($_GET['page']) && $_GET['page']){
			$page = $_GET['page'];
		}
		$limit1 = $page*$page_limit-$page_limit;
		$limit2 = $page_limit;
		
		$prjselect = $this->Project->query("SELECT name FROM projects AS Project WHERE name!='' ".$query." ORDER BY name");
		$arrprj=array();
		foreach($prjselect as $pjall){
			if(isset($pjall['Project']['name']) && !empty($pjall['Project']['name'])){
				array_push($arrprj,substr(trim($pjall['Project']['name']),0,1) );
			}
		}
		if(isset($_GET['prj']) && $_GET['prj']){
			//$_GET['prj'] = Sanitize::clean($_GET['prj'], array('encode' => false));
			$_GET['prj']=chr($_GET['prj']);
			$pj=$_GET['prj']."%";
			$query .= " AND name LIKE '".addslashes($pj)."'";
		}
          
        if($pjname){
			$prjAllArr = $this->Project->query("SELECT SQL_CALC_FOUND_ROWS  id,uniq_id,name,user_id,project_type,short_name,isactive,(select count(easycases.id) as tot from easycases where easycases.project_id=Project.id and easycases.istype='1' and easycases.isactive='1') as totalcase,(select ROUND(SUM(easycases.hours), 1) as hours from easycases where easycases.project_id=Project.id and easycases.istype='2' and easycases.isactive='1') as totalhours,(select count(company_users.id) as tot from company_users, project_users where project_users.user_id = company_users.user_id and project_users.company_id = company_users.company_id and company_users.is_active = 1
and project_users.project_id = Project.id) as totusers,(SELECT SUM(case_files.file_size) AS file_size  FROM case_files   WHERE case_files.project_id=Project.id) AS storage_used FROM projects AS Project WHERE name!='' ".$query." and name LIKE '%".addslashes($pjname)."%' ORDER BY name LIMIT $limit1,$limit2 ");                   
		}else{
			$prjAllArr = $this->Project->query("SELECT SQL_CALC_FOUND_ROWS id,uniq_id,name,user_id,project_type,short_name,isactive,(select count(easycases.id) as tot from easycases where easycases.project_id=Project.id and easycases.istype='1' and easycases.isactive='1') as totalcase,(select ROUND(SUM(easycases.hours), 1) as hours from easycases where easycases.project_id=Project.id and easycases.istype='2' and easycases.isactive='1') as totalhours,(select count(company_users.id) as tot from company_users, project_users where project_users.user_id = company_users.user_id and project_users.company_id = company_users.company_id and company_users.is_active = 1
and project_users.project_id = Project.id) as totusers,(SELECT SUM(case_files.file_size) AS file_size  FROM case_files   WHERE case_files.project_id=Project.id) AS storage_used FROM projects AS Project WHERE name!='' ".$query." ORDER BY name LIMIT $limit1,$limit2");
	
		}
		  
		$tot = $this->Project->query("SELECT FOUND_ROWS() as total");
		$CaseCount = $tot[0][0]['total'];
		$this->set('caseCount',$tot[0][0]['total']);
		
    	$this->set(compact('data'));
		  $this->set('total_records',$prjAllArr);
          $this->set('proj_srch',  $pjname);
		  $this->set('page_limit',$page_limit);
		  $this->set('page',$page);
		  $this->set('pageprev',$pageprev);
		  $count_grid = count($prjAllArr);
		  $this->set('count_grid',$count_grid);
		  $this->set('prjAllArr',$prjAllArr);
		  $this->set('projtype',$projtype);
		  $this->set('action',$action);
		  $this->set('uniqid',$uniqid);
		  $this->set('arrprj',$arrprj);
		  $this->set('page_limit',$page_limit);
		  $this->set('casePage',$page);
	}
	
	function groupupdatealerts() {
		
	    $this->loadModel('Project');
		$this->loadModel('ProjectUser');
	    $project = $this->Project->getAllProjects();
		//$projectsForUser = $this->ProjectUser->getAllProjectsForUsers();
	    $this->set('project',$project);
		
	}
	
	function projectMembers() {
	    $this->layout='ajax';
	    
	    //Getting project id
	    $this->loadModel('Project');
	    $project = $this->Project->getProjectFields(array('Project.uniq_id'=>$this->params['data']['id']),array('id'));
	    
	    //Getting project members of correspoding project
	    $this->loadModel('ProjectUser');
	    $projectuser = $this->ProjectUser->getProjectMembers($project['Project']['id']);
	    
	    //To whom sent an email
	    $this->loadModel('DailyUpdate');
	    $selecteduser = $this->DailyUpdate->getDailyUpdateFields($project['Project']['id']);
	    
	    $this->loadModel('TimezoneName');
	    $timezones = $this->TimezoneName->find('all');
	    $this->set('timezones', $timezones);

	    $this->set('projectuser',$projectuser);
	    $this->set('selecteduser',$selecteduser);
	}
	
	function dailyUpdate() {    


	    //Getting project id
	    $this->loadModel('Project');
	    $project = $this->Project->getProjectFields(array('Project.uniq_id'=>$this->data['Project']['uniq_id']),array('id'));
	    
	    $usr = $this->data['Project']['user'];
	    $this->loadModel('User');
	    
	    //Getting user ids
	    $uids = '';
	    foreach($usr as $key => $value) {
		$user = $this->User->getUserFields(array('User.uniq_id'=>$value),array('id'));
		$uids.=",".$user['User']['id'];
	    }
	    
	    //Making an array to insert or update
	    $data['company_id'] = SES_COMP;
	    $data['project_id'] = $project['Project']['id'];
	    $data['post_by'] = SES_ID;
	    $data['user_id'] = ltrim($uids,",");
	    $data['timezone_id'] = $this->data['Project']['timezone_id'];
	    $data['notification_time'] = trim($this->data['Project']['hour']).":".trim($this->data['Project']['minute']);
	    $data['days'] = $this->data['Project']['days'];
	    
	    $this->loadModel('DailyUpdate');
	    //Check if insert or update
		$this->loadModel('DailyUpdate');
	    $selecteduser = $this->DailyUpdate->getDailyUpdateFields($project['Project']['id']);
		if(isset($selecteduser['DailyUpdate']) && !empty($selecteduser['DailyUpdate'])){
			$this->DailyUpdate->id = $selecteduser['DailyUpdate']['id'];
		}
		
	    //Save or update records
	     if($this->DailyUpdate->save($data)){

		$this->Session->write("SUCCESS","Group update alert has been saved successfully.");
	    }else{

		$this->Session->write("ERROR","Failed to save of Group update alert.");
	    }

	    $this->redirect(HTTP_ROOT."projects/groupupdatealerts");
	}
	
	function cancelDailyUpdate() {	
	    if(intval($this->params['pass'][0])) {
		$this->loadModel('DailyUpdate');
		if($this->DailyUpdate->delete($this->params['pass'][0])) {		

		    $this->Session->write("SUCCESS","Group update alert has been saved successfully.");
		}else{

		    $this->Session->write("ERROR","Failed to save of Group update alert.");
		}
	    }else{

		$this->Session->write("ERROR","Failed to save of Group update alert.");
	    }

	    $this->redirect(HTTP_ROOT."projects/groupupdatealerts");
	}
	
	
	    
	
    function user_listing() {
	$this->layout = 'ajax';
	$projId = trim($this->params['data']['project_id']);
	if (isset($this->params['data']['userid']) && $this->params['data']['userid'] && isset($this->params['data']['InvitedUser']) && trim($this->params['data']['InvitedUser'])) {
	    $UserInvitation = ClassRegistry::init('UserInvitation');
	    $UserInvitation->unbindModel(array('belongsTo' => array('Project')));
	    $checkAvlInvMem = $UserInvitation->query("SELECT * FROM `user_invitations` WHERE find_in_set('" . $projId . "', `user_invitations`.project_id) > 0 AND `user_invitations`.is_active = '1' AND `user_invitations`.user_id = '" . $this->params['data']['userid'] . "'");
	    if ($checkAvlInvMem && !empty($checkAvlInvMem[0]['user_invitations']['project_id'])) {
		$pattern_array = array("/(,$projId,)/", "/(^$projId,)/", "/(,$projId$)/", "/(^$projId$)/");
		$replace_array = array(",", "", "", "");
		$mstr = preg_replace($pattern_array, $replace_array, $checkAvlInvMem[0]['user_invitations']['project_id']);
		$UserInvitation->query("UPDATE user_invitations SET project_id = '" . $mstr . "' where id = '" . $checkAvlInvMem[0]['user_invitations']['id'] . "'");
	    }
	    echo "updated";
	    exit;
	}
	if (isset($this->params['data']['userid']) && $this->params['data']['userid']) {
	    $uid = $this->params['data']['userid'];
	    $ProjectUser = ClassRegistry::init('ProjectUser');
	    $ProjectUser->unbindModel(array('belongsTo' => array('Project')));
	    $checkAvlMem3 = $ProjectUser->find('count', array('conditions' => array('ProjectUser.user_id' => $uid, 'ProjectUser.project_id' => $projId), 'fields' => 'DISTINCT ProjectUser.id'));
	    if ($checkAvlMem3) {
		$ProjectUser->query("DELETE FROM project_users WHERE user_id=" . $uid . " AND project_id=" . $projId);
	    }
	    //Remove from Group update table , that user should not get mail when he is removed from a project.
	    $this->loadModel('DailyUpdate');
	    $DailyUpdate = $this->DailyUpdate->getDailyUpdateFields($projId, array('DailyUpdate.id', 'DailyUpdate.user_id'));
	    if (isset($DailyUpdate) && !empty($DailyUpdate)) {
		$user_ids = explode(",", $DailyUpdate['DailyUpdate']['user_id']);
		if (($index = array_search($uid, $user_ids)) !== false) {
		    unset($user_ids[$index]);
		}
		$du['user_id'] = implode(",", $user_ids);
		$this->DailyUpdate->id = $DailyUpdate['DailyUpdate']['id'];
		$this->DailyUpdate->save($du);
	    }
	    echo "removed";
	    exit;
	}
	
	$qry = '';
	if (isset($this->params['data']['name']) && trim($this->params['data']['name'])) {
	    $name = trim($this->params['data']['name']);
	    $qry = " AND User.name LIKE '%$name%'";
	}
	
	$ProjectUser = ClassRegistry::init('ProjectUser');
	$ProjectUser->unbindModel(array('belongsTo' => array('Project')));
	$memsArr = $ProjectUser->query("SELECT DISTINCT User.*,CompanyUser.*,ProjectUser.* FROM users AS User,company_users AS CompanyUser,project_users AS ProjectUser WHERE User.id=CompanyUser.user_id AND User.id=ProjectUser.user_id AND ProjectUser.project_id='" . $projId . "' AND CompanyUser.company_id='" . SES_COMP . "' AND CompanyUser.is_active=1".$qry." ORDER BY User.name ASC");
	$memsExtArr['Member'] = $memsArr;
	
	$UserInvitation = ClassRegistry::init('UserInvitation');
	$memsUserInvArr = $UserInvitation->query("SELECT * FROM users AS User,user_invitations AS UserInvitation,company_users AS CompanyUser WHERE User.id=CompanyUser.user_id AND User.id=UserInvitation.user_id AND UserInvitation.company_id='" . SES_COMP . "' AND find_in_set('" . $projId . "', UserInvitation.project_id) > 0 AND UserInvitation.is_active = '1' AND CompanyUser.company_id='" . SES_COMP . "' AND CompanyUser.is_active=2".$qry." ORDER BY User.name ASC");
	$memsExtArr['Invited'] = $memsUserInvArr;
	
	$CompanyUser = ClassRegistry::init('CompanyUser');
	$memsUserDisArr = $CompanyUser->query("SELECT DISTINCT User.*,CompanyUser.*,ProjectUser.* FROM users AS User,company_users AS CompanyUser,project_users AS ProjectUser WHERE User.id=CompanyUser.user_id AND User.id=ProjectUser.user_id AND ProjectUser.project_id='" . $projId . "' AND CompanyUser.company_id='" . SES_COMP . "' AND CompanyUser.is_active=0".$qry." ORDER BY User.name ASC");
	$memsExtArr['Disabled'] = $memsUserDisArr;
	
	$this->set('memsExtArr', $memsExtArr);
	$this->set('pjid', $projId);
    }
     
	function add_user(){
	    $this->layout='ajax';
	    $projid = $this->params['data']['pjid'];
	    $pjname = urldecode($this->params['data']['pjname']);
	    $cntmng = $this->params['data']['cntmng'];
	    $query = "";
	    if(isset($this->params['data']['name']) && trim($this->params['data']['name'])) {
		    $srchstr = addslashes($this->params['data']['name']);
		    $query = "AND User.name LIKE '%$srchstr%'";
	    }

	    $ProjectUser = ClassRegistry::init('ProjectUser');

	    $ProjectUser->unbindModel(array('belongsTo' => array('Project')));

	    if(SES_TYPE == 1) {
		    $memsNotExstArr = $ProjectUser->query("SELECT DISTINCT User.id,User.name,User.email,User.istype,User.short_name,CompanyUser.user_type FROM users AS User, company_users AS CompanyUser WHERE User.id = CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND CompanyUser.is_active='1' AND User.isactive='1' AND User.name!='' ".$query." AND NOT EXISTS(SELECT ProjectUser.user_id FROM project_users AS ProjectUser WHERE ProjectUser.user_id=User.id AND ProjectUser.project_id=".$projid.") ORDER BY User.name");
		     $memsExstArr = $ProjectUser->query("SELECT DISTINCT User.id,User.name,User.email,User.istype,User.short_name,CompanyUser.user_type FROM users AS User, company_users AS CompanyUser WHERE User.id = CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND CompanyUser.is_active='1' AND User.isactive='1' AND User.name!='' ".$query." AND EXISTS(SELECT ProjectUser.user_id FROM project_users AS ProjectUser WHERE ProjectUser.user_id=User.id AND ProjectUser.project_id=".$projid.") ORDER BY User.name");
	    }
	    else {
		    $memsNotExstArr = $ProjectUser->query("SELECT DISTINCT User.id,User.name,User.email,User.istype,User.short_name,CompanyUser.user_type FROM users AS User, company_users AS CompanyUser WHERE User.id = CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND CompanyUser.is_active='1' AND User.isactive='1' AND User.name!='' ".$query." AND NOT EXISTS(SELECT ProjectUser.user_id FROM project_users AS ProjectUser WHERE ProjectUser.user_id=User.id AND ProjectUser.project_id=".$projid.") ORDER BY User.name");
		    $memsExstArr = $ProjectUser->query("SELECT DISTINCT User.id,User.name,User.email,User.istype,User.short_name,CompanyUser.user_type FROM users AS User, company_users AS CompanyUser WHERE User.id = CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND CompanyUser.is_active='1' AND User.isactive='1' AND User.name!='' ".$query." AND EXISTS(SELECT ProjectUser.user_id FROM project_users AS ProjectUser WHERE ProjectUser.user_id=User.id AND ProjectUser.project_id=".$projid.") ORDER BY User.name");
	    }	    
	    $this->set('pjname',$pjname);
	    $this->set('projid',$projid);
	    $this->set('memsNotExstArr',$memsNotExstArr);
	    $this->set('memsExstArr',$memsExstArr);
	    $this->set('cntmng',$cntmng);
	}
	function assign_userall(){
		$this->layout='ajax';
		$userid = $this->params['data']['userid'];
		$pjid = $this->params['data']['pjid'];
		
		$Company = ClassRegistry::init('Company');
		$comp = $Company->find('first', array( 'fields' => array('Company.name')));
		
		$ProjectUser = ClassRegistry::init('ProjectUser');
		$ProjectUser->recursive = -1;
		
		$getLastId = $ProjectUser->query("SELECT MAX(id) as maxid FROM project_users");
		$lastid = $getLastId[0][0]['maxid'];
		
		$Easycase = ClassRegistry::init('Easycase');
		$Easycase->recursive = -1;
		
		$CaseUserEmail = ClassRegistry::init('CaseUserEmail');
		$CaseUserEmail->recursive = -1;
		
		//$getcaseIds = $Easycase->find("all",array('conditions', array('Easycase.project_id' => $pjid, 'Easycase.istype' => 1), 'fields' => array('Easycase.id')));
		if(count($userid)) {
			foreach($userid as $id)
			{
				$checkAvlMem2 = $ProjectUser->find('count', array('conditions' => array('ProjectUser.user_id'=>$id,'ProjectUser.project_id'=>$pjid,'ProjectUser.company_id'=>SES_COMP), 'fields'=>'DISTINCT id'));
				if($checkAvlMem2 == 0) {
					$lastid++;
					$ProjectUser->query("INSERT INTO project_users SET id='".$lastid."',user_id=".$id.",project_id=".$pjid.",company_id=".SES_COMP.",dt_visited='".GMT_DATETIME."'");
					
					/*if(count($getcaseIds))
					{
						foreach($getcaseIds as $getid)
						{
							if($getid['Easycase']['id']) {
								$CaseUserEmail->query("UPDATE case_user_emails SET ismail='1' WHERE user_id=".$id." AND easycase_id=".$getid['Easycase']['id']);
							}
						}
					}*/
				}
			}
		}
		if(count($userid)) {
			$Company = ClassRegistry::init('Company');
			$comp = $Company->find('first', array('fields' => array('Company.name')));
			foreach($userid as $id){
				$this->generateMsgAndSendPjMail($pjid,$id,$comp);
			}
		}
		echo "success";
		exit;
	}
	function add_template(){
		//pr($this->request);exit;
		if(isset($this->request->data['ProjectTemplateCase']) && !empty($this->request->data['ProjectTemplateCase'])){
			if(isset($this->request->data['submit_template']) && count($this->request->data['ProjectTemplateCase']['title'])){
				$this->loadModel('ProjectTemplateCase');
				$arr=$this->request->data['ProjectTemplateCase']['title'];
				$count_arr=0;
				foreach($arr as $cs){
					if(isset($cs) && !empty($cs)){
						$temp_case['user_id']=SES_ID;
						$temp_case['company_id']=SES_COMP;
						$temp_case['template_id']=$this->request->data['ProjectTemplateCase']['template_id'];
						$temp_case['title']=$cs;
						$temp_case['description']=$this->request->data['ProjectTemplateCase']['description'][$count_arr];
						$this->ProjectTemplateCase->saveAll($temp_case);
					}
					$count_arr++;
				}
			}
			$this->Session->write("SUCCESS","Template tasks added successfully");
			$this->redirect(HTTP_ROOT."projects/manage_template/");
		}
		$this->loadModel('ProjectTemplate');
		$prj = $this->ProjectTemplate->find('all',array('conditions' => array('ProjectTemplate.company_id'=>SES_COMP,'ProjectTemplate.is_active'=>1),'fields'=>array('ProjectTemplate.id','ProjectTemplate.module_name')));
		$this->set('template_mod',$prj);	
	}
	function manage_template(){
		if(isset($_GET['id']) && !empty($_GET['id'])){
			$this->loadModel("ProjectTemplate");
			$this->ProjectTemplate->id=$_GET['id'];
			$this->ProjectTemplate->delete();
			ClassRegistry::init('ProjectTemplateCase')->query("Delete FROM project_template_cases WHERE template_id='".$_GET['id']."'");
			$this->Session->write("SUCCESS","Template Deleted successfully");
			$this->redirect(HTTP_ROOT."projects/manage_template/");
		}else if(isset($this->request->query['act']) && $this->request->query['act']){
			$v=urldecode(trim($this->request->query['act']));
			$this->loadModel("ProjectTemplate");
			$this->ProjectTemplate->id=$v;
			if($this->ProjectTemplate->saveField("is_active",1)){
				$this->Session->write("SUCCESS","Template activated successfully");
				$this->redirect(HTTP_ROOT."projects/manage_template/");
			}else{
				$this->Session->write("ERROR","Template can't be activated.Please try again.");
				$this->redirect(HTTP_ROOT."projects/manage_template/");
			}
		}else if(isset($this->request->query['inact']) && $this->request->query['inact']){
			$v=urldecode(trim($this->request->query['inact']));
			$this->loadModel("ProjectTemplate");
			$this->ProjectTemplate->id=$v;
			if($this->ProjectTemplate->saveField("is_active",0)){
				$this->Session->write("SUCCESS","Template deactivated successfully");
				$this->redirect(HTTP_ROOT."projects/manage_template/");
			}else{
				$this->Session->write("ERROR","Template can't be deactivated.Please try again.");
				$this->redirect(HTTP_ROOT."projects/manage_template/");
			}
		}
		$proj_temp = ClassRegistry::init('ProjectTemplate')->find('all',array('conditions'=>array('ProjectTemplate.company_id'=>SES_COMP)));
		$proj_temp_active = ClassRegistry::init('ProjectTemplate')->find('all',array('conditions'=>array('ProjectTemplate.company_id'=>SES_COMP,'ProjectTemplate.is_active'=>1)));
		$this->set('proj_temp',$proj_temp);
		$this->set('proj_temp_active',$proj_temp_active);
	}
	function ajax_add_template_module(){
		//print_r($this->params['data']['title']);exit;
		$this->layout='ajax';
		$title = $this->params['data']['title'];
		if(isset($this->params['data']['title']) && !empty($this->params['data']['title'])){
			$this->loadModel('ProjectTemplate');
			$prj = $this->ProjectTemplate->find('count',array('conditions' => array('ProjectTemplate.module_name' => $this->params['data']['title'],'ProjectTemplate.company_id'=>SES_COMP)));	
           if($prj == 0){
				$this->request->data['ProjectTemplate']['user_id'] = SES_ID;
				$this->request->data['ProjectTemplate']['company_id'] = SES_COMP;
				$this->request->data['ProjectTemplate']['module_name'] = $this->params['data']['title'];
				$this->request->data['ProjectTemplate']['is_default'] = 1;
				$this->request->data['ProjectTemplate']['is_active'] = 1;
				if($this->ProjectTemplate->save($this->request->data)){
					$last_insert_id = $this->ProjectTemplate->getLastInsertId();
					echo $title."-".$last_insert_id;
				}else{
					echo "0";
				}
		   }else{
				echo "0";
			}
		}
		exit;
	}
	function ajax_add_template_cases(){
		$this->layout='ajax';
		ob_clean();
		if(isset($this->params['data']['pj_id']) && isset($this->params['data']['temp_mod_id'])){
			$this->loadModel('TemplateModuleCase');
			$prj = $this->TemplateModuleCase->find('count',array('conditions' => array('TemplateModuleCase.company_id'=>SES_COMP,'TemplateModuleCase.project_id'=>$this->params['data']['pj_id'])));
			if($prj == 0){
				$this->request->data['TemplateModuleCase']['template_module_id']=$this->params['data']['temp_mod_id'];
				$this->request->data['TemplateModuleCase']['user_id']=SES_ID;
				$this->request->data['TemplateModuleCase']['company_id']=SES_COMP;
				$this->request->data['TemplateModuleCase']['project_id']=$this->params['data']['pj_id'];
				if($this->TemplateModuleCase->save($this->request->data)){
					$this->loadModel("ProjectTemplateCase");
					$pjtemp = $this->ProjectTemplateCase->find('all', array('conditions'=> array('ProjectTemplateCase.template_id'=>$this->params['data']['temp_mod_id'],'ProjectTemplateCase.company_id'=>SES_COMP)));
					$Easycase = ClassRegistry::init('Easycase');
					$Easycase->recursive = -1;
					$CaseActivity = ClassRegistry::init('CaseActivity');
					foreach($pjtemp as $temp){
						$postCases['Easycase']['uniq_id'] = md5(uniqid());
						$postCases['Easycase']['project_id'] = $this->params['data']['pj_id'];
						$postCases['Easycase']['user_id'] = SES_ID;
						$postCases['Easycase']['type_id'] = 2;
						$postCases['Easycase']['priority'] = 1;
						$postCases['Easycase']['title'] = $temp['ProjectTemplateCase']['title'];
						$postCases['Easycase']['message'] = $temp['ProjectTemplateCase']['description'];
						$postCases['Easycase']['assign_to'] = SES_ID;
						$postCases['Easycase']['due_date'] = "";
						$postCases['Easycase']['istype'] = 1;
						$postCases['Easycase']['format'] = 2;
						$postCases['Easycase']['status'] = 1;
						$postCases['Easycase']['legend'] = 1;
						$postCases['Easycase']['isactive'] = 1;
						$postCases['Easycase']['dt_created'] = GMT_DATETIME;
						$postCases['Easycase']['actual_dt_created'] = GMT_DATETIME;
						$caseNoArr = $Easycase->find('first', array('conditions' => array('Easycase.project_id' => $this->params['data']['pj_id']),'fields' => array('MAX(Easycase.case_no) as caseno')));
						$caseNo = $caseNoArr[0]['caseno']+1;
						$postCases['Easycase']['case_no'] = $caseNo;
						if($Easycase->saveAll($postCases))
						{
							$caseid = $Easycase->getLastInsertID();
							$CaseActivity->recursive = -1;
							$CaseAct['easycase_id'] = $caseid;
							$CaseAct['user_id'] = SES_ID;
							$CaseAct['project_id'] = $this->params['data']['pj_id'];
							$CaseAct['case_no'] = $caseNo;
							$CaseAct['type'] = 1;
							$CaseAct['dt_created'] = GMT_DATETIME;
							$CaseActivity->saveAll($CaseAct);
						}
					}echo "1";exit;
				}
			}else{
				echo "0";exit;
			}
		}
		exit;
	}
	function ajax_view_template_cases(){
		$this->layout='ajax';
		$this->loadModel("ProjectTemplateCase");
		//$pjtemp = $this->ProjectTemplate->find('all', array('conditions'=> array('ProjectTemplate.template_id'=>$this->params['data']['temp_id'],'ProjectTemplate.company_id'=>SES_COMP)));
		$pjtemp = $this->ProjectTemplateCase->find('all', array('conditions'=> array('ProjectTemplateCase.template_id'  => $this->params['data']['temp_id'],'ProjectTemplateCase.company_id' => SES_COMP)));
		$this->set('temp_dtls_cases',$pjtemp);
	}
	function ajax_refresh_template_module(){
		$this->layout='ajax';
		$this->loadModel('ProjectTemplate');
		$prj = $this->ProjectTemplate->find('all',array('conditions' => array('ProjectTemplate.company_id'=>SES_COMP,'ProjectTemplate.is_active'=>1),'fields'=>array('ProjectTemplate.id','ProjectTemplate.module_name')));
		$this->set('template_mod',$prj);
		$this->set('tmp_id',$this->params['data']['tmp_id']);
	}
	function ajax_view_temp_cases(){
		$this->layout='ajax';
		$pjtemp = ClassRegistry::init('ProjectTemplateCase')->find('all', array('conditions'=> array('ProjectTemplateCase.template_id'=>$this->params['data']['template_id']),'fields'=>array('ProjectTemplateCase.title','ProjectTemplateCase.description','ProjectTemplateCase.created')));
		$this->loadModel('ProjectTemplate');
		$tmpmod = ClassRegistry::init('ProjectTemplate')->find('first',array('conditions' => array('ProjectTemplate.id'=>$this->params['data']['template_id']),'fields'=>array('ProjectTemplate.module_name')));
		$this->set('mod_name',$tmpmod['ProjectTemplate']['module_name']);
		$this->set('temp_dtls_cases',$pjtemp);
	}
	function ajax_new_project(){
		$this->layout='ajax';
		//$this->loadModel('TemplateModule');
		//$modlist = ClassRegistry::init('ProjectTemplate')->find('all',array('conditions'=>array('ProjectTemplate.company_id'=>SES_COMP),'fields'=>array('ProjectTemplate.module_name','ProjectTemplate.id'), 'order'=>'ProjectTemplate.created DESC'));
		//$this->set("templates_modules",$modlist);
		
		$this->loadModel('User');
		$userArr = $this->User->query("SELECT User.name,User.last_name,User.id,User.short_name,CompanyUser.user_type FROM users AS User,company_users AS CompanyUser WHERE User.id=CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND CompanyUser.is_active ='1' AND CompanyUser.user_type!='3' AND User.isactive='1' ORDER BY CompanyUser.user_type ASC");
		$this->set("userArr",$userArr);
	}
	function ajax_json_members(){
		$this->layout='ajax';
		$search = $this->params->query['tag'];
		
		$this->loadModel('User');
		
		$userArr = $this->User->query("SELECT User.name,User.last_name,User.id,User.short_name,User.email FROM users AS User,company_users AS CompanyUser WHERE User.id=CompanyUser.user_id AND CompanyUser.company_id='".SES_COMP."' AND CompanyUser.is_active='1' AND CompanyUser.user_type='3' AND User.isactive='1' AND (User.name LIKE '%".$search."%' OR User.email LIKE '%".$search."%') ORDER BY User.name ASC");

		
		ob_clean();
		$items = array();
		foreach($userArr as $urs) {
			//$unm = $urs['User']['name']." &lt".$urs['User']['email']."&gt;";
			$unm = $urs['User']['name'].'|'.$urs['User']['email'];
			$items[] = array("name"=>$unm,"value"=>$urs['User']['id']);
		}
		print json_encode($items);exit;
	}
	
	function ajax_json_project(){
		$this->layout='ajax';
		$search = isset($this->params->query['q']) ? $this->params->query['q'] : $this->params->query['tag'];
		$this->loadModel('ProjectUser');
		//$proj_array = $this->ProjectUser->query("SELECT project_users.project_id FROM project_users WHERE project_users.user_id = '".SES_ID."' AND project_users.company_id = '".SES_COMP."'");
		$proj_array = $this->ProjectUser->query("SELECT project_users.project_id FROM project_users WHERE project_users.user_id = '".SES_ID."' AND project_users.project_id NOT IN(".$this->params['pass'][0].")");
		$projcts = array();
		foreach($proj_array as $k => $v) {
			foreach($v as $k1 => $v1){
				$projcts[] = $v1['project_id'];
			}
		}
		$this->Project->recursive = -1;
		$projname_array = $this->Project->find('all', array('conditions' => array('AND'=>array('Project.id' => $projcts,'Project.name LIKE "%' . $search . '%"')),'fields'=>array('Project.id','Project.name'),'order'=>'Project.name asc'));
		ob_clean();
		$items = array();
		
		foreach($projname_array as $urs) {
			$items[] = array("id"=>$urs['Project']['id'],"name"=>$urs['Project']['name']);
		}
		print json_encode($items);exit;
	}
	function ajax_template_case_listing(){
		$this->layout='ajax';
		//$all_cases=ClassRegistry::init('ProjectTemplateCase')->find('all',array('conditions'=>array('ProjectTemplateCase.template_id'=>$this->params['data']['template_id'],'ProjectTemplateCase.company_id'=> SES_COMP)));
		if(isset($this->params['data']['rem_template_id']) && $this->params['data']['rem_template_id'])
		{
			$this->loadModel("ProjectTemplateCase");
			$this->ProjectTemplateCase->id=$this->params['data']['rem_template_id'];
			$this->ProjectTemplateCase->delete();	
			echo "removed";exit;
		}
		$all_cases=ClassRegistry::init('ProjectTemplateCase')->query("SELECT User.short_name,User.name,ProjectTemplateCase.*  FROM users AS User,project_template_cases AS ProjectTemplateCase WHERE ProjectTemplateCase.template_id='".$this->params['data']['template_id']."' AND ProjectTemplateCase.company_id='".SES_COMP."' AND ProjectTemplateCase.user_id=User.id ;");
		$this->set("templates_cases",$all_cases);
	}
		function ajax_template_edit(){
		$this->layout='ajax';
		ob_clean();
		if(isset($this->params['data']['template_id']) && $this->params['data']['template_id'] && isset($this->params['data']['count']) && $this->params['data']['count'])
		{
			$temp_id=$this->params['data']['template_id'];
			$cnt=$this->params['data']['count'];	
			$ttl=urldecode($this->params['data']['module_name']);
			$res=ClassRegistry::init('ProjectTemplate')->find('all',array('conditions'=>array('module_name'=>$ttl,'company_id'=>SES_COMP)));
			if(count($res) == 0){
				$this->loadModel("ProjectTemplate");
				$this->ProjectTemplate->id=$temp_id;
				if($this->ProjectTemplate->saveField("module_name",$ttl)){
					echo "<a class='classhover' href='javascript:void(0);'  title='Click here to view tasks' onclick='opencases($cnt);caseListing($cnt,$temp_id)'>$ttl</a>";exit;
				}else{
					echo "fail";exit;
				}
			}else{
				echo "exist";exit;
			}
		}else{
					echo "fail";exit;
			}
	}
	function assign_template_project(){
		$this->loadModel("ProjectTemplate");
		$res = $this->ProjectTemplate->find('all',array('conditions'=>array('ProjectTemplate.module_name !='   => '','ProjectTemplate.company_id' => SES_COMP,'ProjectTemplate.is_active' => 1)));
		$this->set('temp_module',$res);
		$this->Project->recursive=-1;
		$project_details = $this->Project->find('all',array('conditions'=>array('Project.company_id'=>SES_COMP,'Project.isactive'=>1),'fields'=>array('Project.name','Project.id')));
		$this->set('project_details',$project_details);
	}
     function update_email_notification(){
          $this->layout='ajax';
	     $proj_user_id = $this->params['data']['projectuser_id'];
          $email_type = $this->params['data']['type'];
          if($proj_user_id && $email_type){
               if($email_type == 'off'){
                    $this->loadModel('ProjectUser');
                    $this->ProjectUser->query("UPDATE project_users SET default_email=0 where id='".$proj_user_id."'");
               }else{
                    $this->loadModel('ProjectUser');
                    $this->ProjectUser->query("UPDATE project_users SET default_email=1 where id='".$proj_user_id."'");
               }
          }
          echo "sucess";exit;
     }
function ajax_save_filter(){
          $this->layout='ajax';
          //For Case Status
		if(isset($this->params['data']['caseStatus']) && $this->params['data']['caseStatus']){
			$case_status = $this->params['data']['caseStatus'];
		}elseif($_COOKIE['STATUS']){
			$case_status = $_COOKIE['STATUS'];
		}

		if($case_status && $case_status != "all"){
			$case_status = strrev($case_status);
			if(strstr($case_status,"-")){
				$expst = explode("-",$case_status);
				foreach($expst as $st){
					$status.= $this->Format->displayStatus($st).", ";
				}
			}else{
				$status = $this->Format->displayStatus($case_status).", ";
			}
			$arr['case_status'] = trim($status,', ');
			//$val =1;
		}else{
			$arr['case_status'] = 'All';
		}
		
          //For case types
		if(isset($this->params['data']['caseType']) && $this->params['data']['caseType']){
			$case_types = $this->params['data']['caseType'];
		}elseif($_COOKIE['CS_TYPES']){
			$case_types = $_COOKIE['CS_TYPES'];
		}
		$types ='';
		if($case_types && $case_types != "all"){
			$case_types = strrev($case_types);
			if(strstr($case_types,"-")){
				$expst3 = explode("-",$case_types);
				foreach($expst3 as $st3){
					$types.= $this->Format->caseBcTypes($st3).", ";
				}
				$types = trim($types,', ');
			}else{
				$types = $this->Format->caseBcTypes($case_types);
			}
			$arr['case_types'] = $types;
			//$val =1;
		}else{
			$arr['case_types'] = 'All';
		}
          //For Priority
		if(isset($this->params['data']['casePriority']) && $this->params['data']['casePriority']){
			$pri_fil = $this->params['data']['casePriority'];
		}elseif($_COOKIE['PRIORITY']){
			$pri_fil = $_COOKIE['PRIORITY'];
		}
		if($pri_fil && $pri_fil != "all"){
			if(strstr($pri_fil,"-")){
				$expst2 = explode("-",$pri_fil);
				foreach($expst2 as $st2){
					$pri.= $st2.", ";
				}
				$pri = trim($pri,', ');
			}else{
				$pri = $pri_fil;
			}
			$arr['pri'] = $pri;
			//$val =1;
		}else{
			$arr['pri'] = 'All';
		}
          //For Case Members 
		if(isset($this->params['data']['caseMemeber']) && $this->params['data']['caseMemeber']){
			$case_member = $this->params['data']['caseMemeber'];
		}elseif($_COOKIE['MEMBERS']){
			$case_member = $_COOKIE['MEMBERS'];
		}
		if($case_member && $case_member != "all"){
			if(strstr($case_member,"-")){
				$expst4 = explode("-",$case_member);
				foreach($expst4 as $st4){
					$mems.= $this->Format->caseBcMems($st4).", ";
				}
			}else{
				$mems = $this->Format->caseBcMems($case_member).", ";
			}
			$arr['case_member'] = trim($mems,', ');
			//$val =1;
		}else{
			$arr['case_member'] = 'All';
		}

		
		//For Case Date Status .... 
		if(isset($this->params['data']['caseDate']) && $this->params['data']['caseDate']){
			$date = $this->params['data']['caseDate'];
		}else{
			
				$date = $this->Cookie->read('DATE');
			
		}
		if(!empty($date)){
			//$val = 1;
			if(trim($date) == 'one'){
				$arr['date'] = "Past hour";
			}else if(trim($date) == '24'){
				$arr['date'] = "Past 24Hour";
			}else if(trim($date) == 'week'){
				$arr['date'] = "Past Week";
			}else if(trim($date) == 'month'){
				$arr['date'] = "Past month";
			}else if(trim($date) == 'year'){
				$arr['date'] = "Past Year";
			}else if(strstr(trim($date),":")){
				$arr['date'] = str_replace(":"," - ",$date);
			}
		}else { 
			$arr['date'] = "Any Time"; 
		}
          $this->set('memebers',$arr['case_member']);
          $this->set('priority',$arr['pri']);
          $this->set('type',$arr['case_types']);
          $this->set('status',$arr['case_status']);
          $this->set('date',$arr['date']);

          $this->set('memebers_val',$case_member);
          $this->set('priority_val',$pri_fil);
          $this->set('type_val',$case_types);
          $this->set('status_val',$case_status);
          $this->set('date_val',$date);
     }
     function ajax_customfilter_save(){
          $this->layout='ajax';
          
          $caseStatus = $this->params['data']['caseStatus'];
          $caseType = $this->params['data']['caseType'];
          $caseDate = $this->params['data']['caseDate'];
          $caseMemeber = $this->params['data']['caseMemeber'];
          $casePriority = $this->params['data']['casePriority'];
          $filterName = $this->params['data']['filterName'];
          $projuniqid = $this->params['data']['projuniqid'];
          $this->loadModel('CustomFilter');
          	$this->CustomFilter->query("INSERT INTO custom_filters SET project_uniq_id='".$projuniqid."', company_id='".SES_COMP."', user_id='".SES_ID."', filter_name='".$filterName."',filter_date='".$caseDate."', filter_type_id='".$caseType."',filter_status='".$caseStatus."', filter_member_id='".$caseMemeber."', filter_priority='".$casePriority."', dt_created='".GMT_DATETIME."'");
          
          echo "success";
          exit;               
     }
     function ajax_custom_filter_show(){
          $this->layout='ajax';
          $limit_1 = $this->params['data']['limit1'];
          if(isset($limit_1)){
               $limit1 = (int)$limit_1+3;
               $limit2= 3; 
          }else{
               $limit1 = 0;
               $limit2= 3;
          }          
		$this->loadModel('CustomFilter');
		$getcustomfilter = "SELECT SQL_CALC_FOUND_ROWS * FROM custom_filters AS CustomFilter WHERE CustomFilter.company_id = '".SES_COMP."' and CustomFilter.user_id =  '".SES_ID."' ORDER BY CustomFilter.dt_created DESC LIMIT $limit1,$limit2";
          $getfilter = $this->CustomFilter->query($getcustomfilter);
		$tot = $this->CustomFilter->query("SELECT FOUND_ROWS() as total");
          //echo '<pre>';print_r($tot);
          $this->set('getfilter',$getfilter);
          $this->set('limit1',$limit1);
          $this->set('totalfilter',$tot[0][0]['total']);
		     }
			 
/**
 * @method public importexport(int proj_id) Dataimport Interface 
 */
	function importexport($proj_id='') {
		if(!$proj_id && (!isset($GLOBALS['getallproj'][0]['Project']['uniq_id']) && $GLOBALS['getallproj'][0]['Project']['uniq_id'])){
			$this->redirect(HTTP_ROOT.'projects/manage/');exit;
		}else{
			if(!$proj_id)
				$proj_id = $GLOBALS['getallproj'][0]['Project']['uniq_id'];
			$this->Project->recursive=-1;
			$proj_details = $this->Project->find('first',array('conditions'=>array('uniq_id'=>$proj_id,'company_id'=>SES_COMP)));
			if($proj_details && (SES_TYPE<=2)){
				$this->set('upload_file',1);
				$this->set('proj_id',$proj_details['Project']['id']);
				$this->set('proj_uid',$proj_id);
				$this->set('import_pjname',$proj_details['Project']['name']);
			}else{
				$this->redirect(HTTP_ROOT.'projects/gridview/');exit;
			}
		}
	}
/**
 * @method public data_import Dataimport Interface 
 */
	function csv_dataimport() {
		$project_id = $this->data['proj_id'];
		$project_uid = $this->data['proj_uid'];
		$task_type_arr = array('enhancement','enh','bug','research n do','rnd','quality assurance','qa','unit testing','unt','maintenance','mnt','others','oth','release','rel','update','upd','development','dev');
		$task_status_arr = array('new','close','wip','resolve','resolved','closed');
		$this->loadModel('User');
		$this->loadModel('ProjectUser');
		$task_assign_to_userid = $this->ProjectUser->find('list',array('conditions'=>array('company_id'=>SES_COMP,'project_id'=>$project_id),'fields'=>'user_id'));
		$task_assign_to_users = $this->User->find('list',array('conditions'=>array('id'=>$task_assign_to_userid,'isactive'=>1),'fields'=>'email'));		
		
		//$fields_arr = array('milestone title','milestone description','start date','end date','title','description','due date','status','type','assigned to');
		$fields_arr = array('title','description','due date','status','type','assigned to');
		
		if(isset($_FILES['import_csv'])){
			//$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/octet-stream');
			$ext = pathinfo($_FILES['import_csv']['name'], PATHINFO_EXTENSION);
			//if(in_array($_FILES['import_csv']['type'],$mimes)){
			if(strtolower($ext)== 'csv'){
			  $csv_info = $_FILES['import_csv'];
			  //Uploading the csv file to Our server
			  $file_name =SES_ID."_".$project_id."_".$csv_info['name']; 
			  @copy($csv_info['tmp_name'], CSV_PATH."task_milstone/".$file_name);
			  
			  $row = 1;
			  // Counting total rows and Restricting from uploading a file having more then 1000 record
			  $linecount = count(file(CSV_PATH."task_milstone/".$file_name));
			  if($linecount>1001){
				 @unlink($csv_info['tmp_name'], CSV_PATH."task_milstone/".$file_name);
				$this->Session->write("ERROR","Please split the file and upload again. Your file contain more than 1000 rows");
				$this->redirect(HTTP_ROOT."projects/importexport/".$project_uid);exit;
			  }
			  if($csv_info['size']>2097152){
				@unlink($csv_info['tmp_name'], CSV_PATH."task_milstone/".$file_name);
				$this->Session->write("ERROR","Please upload a file with size less then 2MB");
				$this->redirect(HTTP_ROOT."projects/importexport/".$project_uid);exit;
			  }
			//Parsing the csv file
			  if (($handle = fopen(CSV_PATH."task_milstone/".$file_name, "r")) !== FALSE) {
			  $i=0;$j=0;
			  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				if(!$i){
					// Check for column count
					if(count($data)>=1){
						// Check for exact number of fields 
						foreach($data AS $key=>$val){
							if(!in_array(strtolower($val),$fields_arr)){
								@unlink($csv_info['tmp_name'], CSV_PATH."task_milstone/".$file_name);
								$this->Session->write("ERROR","Invalid CSV file, <a href='".HTTP_ROOT."projects/download_sample_csvfile' style='text-decoration:underline;color:#0000FF'>Download</a> and check with our sample file");
								$this->redirect(HTTP_ROOT."projects/importexport/".$project_uid);exit;
							}
						}
						$fileds = $data;
						//$header_arr = array_flip($data);
						foreach($data AS $key=>$val){
							$header_arr[strtolower($val)]=$key;
						}
						
					}else{
						@unlink($csv_info['tmp_name'], CSV_PATH."task_milstone/".$file_name);
						$this->Session->write("ERROR","Require atleast Task Title column to import the Tasks");
						$this->redirect(HTTP_ROOT."projects/importexport/".$project_uid);exit;
					}
				}  else {
					
					// Verifing data
					$value= $data;
//					if($value[$header_arr['title']]){
//						$mtitle = $value[$header_arr['milestone title']];
////						$milestone_arr[$value[$header_arr['milestone title']]]['title'] = $value[$header_arr['milestone title']];
////						$milestone_arr[$value[$header_arr['milestone title']]]['desc'] = $value[$header_arr['milestone description']];
////						$milestone_arr[$value[$header_arr['milestone title']]]['start_date'] = $value[$header_arr['start date']];
////						$milestone_arr[$value[$header_arr['milestone title']]]['end_date'] = $value[$header_arr['end date']];
////						unset($value[$header_arr['milestone title']]);
////						unset($value[$header_arr['milestone description']]);
////						unset($value[$header_arr['start date']]);
////						unset($value[$header_arr['end date']]);
//					}else {
//						$mtitle = 'default';
//					}
					if(isset($value[$header_arr['title']]) && trim($value[$header_arr['title']])){
						foreach ($value as $k => $v) {
							$task_ass[strtolower($fileds[$k])]=$v;
							
							// Parsing each data for error in data 
							if(strtolower($fileds[$k])=='type' && $v){
								if(in_array(strtolower($v), $task_type_arr)){
									$task_error[strtolower($fileds[$k])] = 0;
								}else{
									$task_error[strtolower($fileds[$k])] = 1;
								}
							}elseif(strtolower($fileds[$k])=='status' && $v){
								if(in_array(strtolower($v), $task_status_arr)){
									$task_error[strtolower($fileds[$k])] = 0;
								}else{
									$task_error[strtolower($fileds[$k])] = 1;
								}
							}elseif(strtolower($fileds[$k])=='due date' && $v){
								if($this->Format->isValidDateTime($v)){
									$task_error[strtolower($fileds[$k])] = 0;
								}else{
									$task_error[strtolower($fileds[$k])] = 1;
								}
							}elseif(strtolower($fileds[$k])=='assigned to' && strtolower ($v) !='me' && $v){
								if(in_array($v,$task_assign_to_users)){
									$task_error[strtolower($fileds[$k])] = 0;
								}else{
									$task_error[strtolower($fileds[$k])] = 1;
								}
							}else{
								$task_error[strtolower($fileds[$k])] = 0;
							}
						}
						$task[] = $task_ass;
						$task_err[] = $task_error;
					}
				}
				$i++;
			  }
			   fclose($handle);
			}
			//pr($milestone_arr);echo "<hr/>";pr($task);echo "<hr/>";pr($task_err);exit;
			//$this->set('milestone_arr',$milestone_arr);
			
			$this->Project->recursive = -1;
			$projectdata = $this->Project->findById($project_id);
			
			$this->set('projectname',$projectdata['Project']['name']);
			$this->set('task',$task);
			$this->set('task_err',$task_err);
			$this->set('preview_data',1);
			$this->set('fileds',$fileds);
			$this->set('porj_id',$project_id);
			$this->set('porj_uid',$project_uid);
			$this->set('csv_file_name',$csv_info['name']);
			$this->set('total_rows',$linecount);
			$this->render('importexport');
			} else {
				$this->Session->write("ERROR","Please import a valid CSV file");
				$this->redirect(HTTP_ROOT."projects/importexport/".$project_uid);
			}
		}else{
			$this->Session->write("ERROR","Please import a valid CSV file");
			$this->redirect(HTTP_ROOT."projects/importexport/".$project_uid);
		}
	}
/**
 * @method public confirm_import Dataimport Interface 
 */	
	function confirm_import() {
		$project_id = $this->data['project_id'];
		$this->loadModel('User');
		$this->loadModel('ProjectUser');
		$task_assign_to_userid = $this->ProjectUser->find('list',array('conditions'=>array('company_id'=>SES_COMP,'project_id'=>$project_id),'fields'=>'user_id'));
		$task_assign_to_users = $this->User->find('list',array('conditions'=>array('id'=>$task_assign_to_userid,'isactive'=>1),'fields'=>'email'));
		
		//$milestone_arr = unserialize($this->data['milestone_arr']);
		$task_arr = unserialize($this->data['task_arr']);
		$this->loadModel('Milestone');
		$this->loadModel('Easycase');
		//$this->loadModel('EasycaseMilestone');
		$EasycaseMilestone = ClassRegistry::init('EasycaseMilestone');
		$EasycaseMilestone->recursive = -1;
		//Get the Case no. for the existing projects
		$caseNoArr = $this->Easycase->find('first', array('conditions' => array('Easycase.project_id' => $project_id),'fields' => array('MAX(Easycase.case_no) as caseno')));
		$caseNo = $caseNoArr[0]['caseno']+1;
		$hind =0;
		/*foreach($milestone_arr as $key=>$val){
			$default =0;
			if($key !='default'){
				$mst_id = $this->Milestone->find('first',array('conditions'=>array('title'=>$key,'project_id'=>$project_id),array('fileds'=>array('id'))));
				if(!$mst_id){
					$milestone['title']= $key;
					$milestone['description']= (isset($val['desc']) && $val['desc']) ?$val['desc']:'';
					$start_date = (isset($val['start_date']) && $val['start_date'])?$val['start_date']:'';
					if($start_date){
						$start_date = $this->Format->isValidDateTime($start_date)?date('Y-m-d',strtotime($start_date)):'';
					}			
					$milestone['start_date']= $start_date?$start_date:GMT_DATE;
					$end_date = (isset($val['end_date']) && $val['end_date'])?$val['end_date']:'';
					if($end_date){
						$end_date = $this->Format->isValidDateTime($end_date)?date('Y-m-d',strtotime($end_date)):'';
					}			
					$milestone['end_date']= $end_date?$end_date:GMT_DATE;
					//$milestone['end_date']= $end_date;
					$milestone['project_id']= $project_id;
					$milestone['user_id']= SES_ID;
					$milestone['company_id']= SES_COMP;
					$milestone['uniq_id']= md5(uniqid());
					$this->Milestone->create();
					$this->Milestone->save($milestone);
					$milestone_last_insert_id =$this->Milestone->getLastInsertID();
				}else{
					$milestone_last_insert_id = $mst_id['Milestone']['id'];
				}
			}else{
				$default =1;
			}*/
			// Preparing history data
			//$history[$hind]['milestone_title'] = $key; 
			$history[$hind++]['total_task'] = count($task_arr);
			$total_valid_rows = $total_valid_rows ? ($total_valid_rows+count($task_arr)):count($task_arr);
			foreach ($task_arr as $k => $v) {
				if(!trim($v['title']))continue;
				$easycase['title'] = $v['title'];
				$easycase['message'] = (isset($v['description']) && $v['description'])?$v['description']:'';
				$due_date = (isset($v['due date']) && $v['due date'])?$v['due date']:'';
				//$this->Format->isValidDateTime($due_date);
				if($due_date){
					$due_date = $this->Format->isValidDateTime($due_date)?date('Y-m-d',strtotime($due_date)):'';
				}				
				$easycase['due_date'] = $due_date;
				if($v['status'] && (strtoupper(trim($v['status']))=='WIP')){
					$legend =2;
				}elseif($v['status'] && ((strtolower(trim($v['status']))=='close') || (strtoupper(trim($v['status']))=='CLOSED'))){
					$legend =3;
				}elseif($v['status'] && (strtolower(trim($v['status']))=='resolve' || strtolower(trim($v['status']))=='resolved')){
					$legend =5;
				}else{
					$legend =1;
				}
				$easycase['legend'] = $legend;
				$easycase['type_id'] = $this->get_type_id($v['type']);
				if(strtolower($v['assigned to']) !='me' && $v['assigned to']){
					if(array_search($v['assigned to'],$task_assign_to_users)){
						$easycase['assign_to'] = array_search($v['assigned to'],$task_assign_to_users);
					}else{
						$easycase['assign_to'] = SES_ID;
					}
				}else{
					$easycase['assign_to'] = SES_ID;
				}
				$easycase['project_id'] = $project_id;
				$easycase['user_id'] = SES_ID;
				$easycase['priority'] = 1;
				$easycase['case_no'] = $caseNo++;
				$easycase['uniq_id'] = md5(uniqid());
				$easycase['actual_dt_created'] = GMT_DATETIME;
				$easycase['dt_created'] = GMT_DATETIME;
				$easycase['isactive'] = 1;
				$easycase['format'] = 2;

				$this->Easycase->create();
				$sid = $this->Easycase->save($easycase);
				/*if(!$default){
						$EasycaseMiles['easycase_id'] = $this->Easycase->getLastInsertID();
						$EasycaseMiles['milestone_id'] = $milestone_last_insert_id;
						$EasycaseMiles['project_id']= $project_id;
						$EasycaseMiles['user_id'] = SES_ID;
						$EasycaseMiles['dt_created'] = GMT_DATETIME;
						$EasycaseMilestone->saveAll($EasycaseMiles);
				}*/
			}
		//}
		$this->set('total_valid_rows',$total_valid_rows);
		$this->set('csv_file_name',$this->data['csv_file_name']);
		$this->set('total_rows',$this->data['total_rows']);
		$this->set('total_task',count($task_arr));
		$this->set('proj_name',$this->Format->getProjectName($project_id));
		$this->set('history',$history);
		$this->render('importexport');
		
		//echo $project_id; pr($milestone_arr);echo "<hr/>";pr($task_arr);exit;
	}
	
function get_type_id($type){
	$type = strtolower($type);
	if($type=='bug'){
		return 1;
	}elseif($type=='enhancement' || $type=='enh' ){
		return 3;
	}elseif($type=='research n do' || $type=='rnd' ){
		return 4;
	}elseif($type=='quality assurance' || $type=='qa'){
		return 5;
	}elseif($type=='unit testing' || $type=='unt'){
		return 6;
	}elseif($type=='maintenance' || $type=='mnt'){
		return 7;
	}elseif($type=='others' || $type=='oth'){
		return 8;
	}elseif($type=='release' || $type=='rel' ){
		return 9;
	}elseif($type=='update' || $type=='upd' ){
		return 10;
	}else{
		return 2;
	}
}	
/**
 * @method public download_sample_csv_file  
 */
	function download_sample_csvfile(){
		//$myFile ='demo_sample_milestone_csv_file.csv';
		$myFile ='Orangescrum_Import_Task_Sample.csv';
		header('HTTP/1.1 200 OK');
        header('Cache-Control: no-cache, must-revalidate');
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=Orangescrum_Task_Sample.csv");
        readfile(CSV_PATH."task_milstone/". $myFile);
        exit;
	}

function checkfile_existance(){
	$file_info = $_FILES['file-0'];
	$file_name = SES_ID."_".$this->data['porject_id']."_".$file_info['name']; 
	//echo $file_name;exit;
	$directory = CSV_PATH."task_milstone";
	if ($handle = opendir($directory)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				if($file_name == $entry){
					$filesize = filesize($directory.'/'.$file_name);
					if($file_info['size'] == $filesize){
						$arr['msg'] = "Already a file with same name and same size of ".  $filesize." bytes exists. Would you like to replace the exsiting file?";
					}else{
						$arr['msg'] = "Already file with same name and size of ".$filesize." bytes exists. Would you like to replace the existing file ?";
					}
					$err =1;
					$arr['success'] =0;
					$arr['error'] =1;
				}
				//echo "$entry<br/>";
			}
		}
		closedir($handle);
		if(!$err){
			$arr['success'] =1;
			$arr['msg'] = "";
			$arr['error'] =0;
		}
		echo json_encode($arr);exit;
	}
}	
function learnmore(){
	$this->layout='';
}		
function project_thumb_view(){

}
/**
 * 
 */
	function member_list(){
		$this->layout="ajax";
		$this->loadModel('User');
		$list = $this->User->get_email_list();
		if($list){
			foreach ($list as $key=>$val){
				if(trim($val['User']['email'])!='' && trim(strtolower($val['User']['email']))!='null'){
					$name ="";
					if($val['User']['name']){
						$name = stripcslashes($val['User']['name']);
					}
					if($val['User']['last_name']){
						$name .=" ".stripcslashes($val['User']['last_name']);
					}
					if($name){
						$email[$val['User']['id']] =$name." <".$val['User']['email'].">";
					}else{
						$email[$val['User']['id']]= $val['User']['email'];
					}
				}
}
		}
		//$arr['email'] = array_unique($email);
		echo json_encode(array_unique($email));exit;
	}
/**
 * @method Public onbording($paramName) Onboarding for create project
 * @return  html
 */	
	function onbording(){
		if(SES_TYPE>2){
			$this->redirect(HTTP_ROOT);exit;
		}
		if($GLOBALS['project_count']){
			$projectusercls = ClassRegistry::init('ProjectUser');
			$projectusercls->recursive=-1;
			$projectusers = $projectusercls->find('count',array('conditions'=>array('company_id'=>SES_COMP)));
			
			$this->set('projectuser_count',$projectusers?$projectusers:0);
			$easycase_cls = ClassRegistry::init('Easycase');
			$proje_ids = array_keys($GLOBALS['active_proj_list']);
			$easycase_cls->recursive=-1;
			$task_count = $easycase_cls->find('count',array('conditions'=>array('project_id'=> $proje_ids)));
			$this->set('task_crted',$task_count?$task_count:0);
		}
		$company_usercls = ClassRegistry::init('CompanyUser');
		$totalusers = $company_usercls->find('count',array('conditions'=>array('company_id'=>SES_COMP,'is_active !='=>3)));
		$this->set('totalusers',$totalusers);
		setcookie('LOAD_TW_POP',1,time()+3600,'/',DOMAIN_COOKIE,false,false);
		
		$id=$this->Auth->user('id');
        $this->loadModel('User');
        $rec=$this->User->findById($id);
        if(($rec['User']['dt_last_logout']=='' && $rec['User']['show_default_inner'])){
            $this->set('is_log_out',1);
        }
	}

	public function hide_default_inner(){
		$this->loadModel('User');
		$this->User->id=SES_ID;
		$this->User->saveField('show_default_inner',0);
		echo 'success';
		exit;
	}
/**
 * @method Public deleteprojects($projuid) Deleting project with all associated data to that project
 * @return bool true/false
 */
	function deleteprojects($projuid='',$page = NULL){
		if(SES_TYPE>2){
			$grpcount = $this->Project->query('SELECT Project.id FROM projects AS Project WHERE Project.user_id='.$this->Auth->user('id').' AND Project.uniq_id="'.$projuid.'" AND Project.company_id='.SES_COMP.'');
			if(!$grpcount[0]['Project']['id']) {
				$this->redirect(HTTP_ROOT);exit;
			}
		}
		$redirect = HTTP_ROOT."projects/manage";
		if(isset($page) && (intval($page) > 1)) {
		    $redirect.="?page=".$page;
		}
		
		if(!$projuid){
			$this->redirect($redirect);exit;
		}else{
			$arr = $this->Project->deleteprojects($projuid);
			if(isset($arr['succ']) && $arr['succ']){
				$this->Session->write('SUCCESS',$arr['msg']);
			}elseif(isset($arr['error']) && $arr['error']){
				$this->Session->write('ERROR',$arr['msg']);
			}else{
				$this->Session->write('ERROR','Oops! Error occured in deletion of project');
			}
			$this->redirect($redirect);exit;
		}
	}
	function ajax_existuser_delete(){
		$this->layout = 'ajax';	
		if (isset($this->params['data']['userid']) && $this->params['data']['userid']) {
		    $uid = $this->params['data']['userid'];
		    $projId = trim($this->params['data']['project_id']);
		    $ProjectUser = ClassRegistry::init('ProjectUser');
		    $ProjectUser->unbindModel(array('belongsTo' => array('Project')));
		    $checkAvlMem3 = $ProjectUser->find('count', array('conditions' => array('ProjectUser.user_id' => $uid, 'ProjectUser.project_id' => $projId), 'fields' => 'DISTINCT ProjectUser.id'));
		    if ($checkAvlMem3) {
			$ProjectUser->query("DELETE FROM project_users WHERE user_id=" . $uid . " AND project_id=" . $projId);
		    }
		    //Remove from Group update table , that user should not get mail when he is removed from a project.
		    $this->loadModel('DailyUpdate');
		    $DailyUpdate = $this->DailyUpdate->getDailyUpdateFields($projId, array('DailyUpdate.id', 'DailyUpdate.user_id'));
		    if (isset($DailyUpdate) && !empty($DailyUpdate)) {
			$user_ids = explode(",", $DailyUpdate['DailyUpdate']['user_id']);
			if (($index = array_search($uid, $user_ids)) !== false) {
			    unset($user_ids[$index]);
			}
			$du['user_id'] = implode(",", $user_ids);
			$this->DailyUpdate->id = $DailyUpdate['DailyUpdate']['id'];
			$this->DailyUpdate->save($du);
		    }
		    echo "success";
		    exit;
		}
	
	}
	function generateMsgAndSendPjMail($pjid,$id,$comp)
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
		$this->loadModel('User');
		$toUsrArr = $this->User->findById($id);
		$to_email = ""; $to_name = "";
		if(count($toUsrArr)) {
			$to_email = $toUsrArr['User']['email'];
			$to_name = $frmtHlpr->formatText($toUsrArr['User']['name']);
		}
//                
		##### get Project Details
		$this->Project->recursive = -1;
		$prjArr = $this->Project->find('first', array('conditions' => array('Project.id' => $pjid),'fields' => array('Project.name','Project.short_name','Project.uniq_id')));
		$projName = "";  $projUniqId = "";
		if(count($prjArr)) {
			$projName = $frmtHlpr->formatText($prjArr['Project']['name']);
			$projUniqId = $prjArr['Project']['uniq_id'];
		}
		
		$subject = "You have been added to ".$projName." on Orangescrum";

		$this->Email->delivery = EMAIL_DELIVERY;
		$this->Email->to = $to_email;      
		$this->Email->subject = $subject;
		$this->Email->from = FROM_EMAIL_NOTIFY;
		$this->Email->template = 'project_add';
		$this->Email->sendAs = 'html';
		$this->set('to_name',$to_name);
		$this->set('from_name',$from_name);
		$this->set('projName',$projName);
		$this->set('projUniqId',$projUniqId);
		$this->set('multiple',0);
		$this->set('company_name',$comp['Company']['name']);
		
		return $this->Sendgrid->sendgridsmtp($this->Email);
	}
        public function default_inner(){
            $this->layout='';
            
}

    /**
    * Showing and Managing task types by company owner
    * 
    * @method task_type
    * @author Orangescrum
    * @return
    * @copyright (c) Aug/2014, Andolsoft Pvt Ltd.
    */
    function task_type() {
	$this->loadModel("Type");
	$task_types = $this->Type->getAllTypes();

	$this->loadModel("TypeCompany");
	$sel_types = $this->TypeCompany->getSelTypes();
	$is_projects = 0;
	if (isset($sel_types) && !empty($sel_types) && isset($task_types) && !empty($task_types)) {
	    foreach ($task_types as $key => $value) {
		//if (array_search($value['Type']['id'], $sel_types) || intval($value['Total']['cnt'])) {
		if (array_search($value['Type']['id'], $sel_types)) {
		    $task_types[$key]['Type']['is_exist'] = 1;
		} else {
		    $task_types[$key]['Type']['is_exist'] = 0;
		}
	    }
		$is_projects = 1;
	}
	
	$this->set(compact('task_types', 'sel_types', 'is_projects'));
    }
    
    /**
    * Add new task types by company owner
    * 
    * @method addNewTaskType
    * @author Orangescrum
    * @return
    * @copyright (c) Aug/2014, Andolsoft Pvt Ltd.
    */
    function addNewTaskType() {
	if (isset($this->data['Type']) && !empty($this->data['Type'])) {
	    
	    $data = $this->data['Type'];
	    $data['short_name'] = strtolower($data['short_name']);
	    $data['company_id'] = SES_COMP;
	    $data['seq_order'] = 0;
	    
	    $this->loadModel("Type");
	    if(isset($data['id']) && $data['id']){		
	    }else{
		$this->Type->id = '';
	    }
	    $this->Type->save($data);
	    $id = $this->Type->getLastInsertID();
	    if(isset($data['id']) && $data['id']){		
		$this->Session->write("SUCCESS","Task type '".trim($data['name'])."' updated successfully.");
	    }else{
		$this->loadModel("TypeCompany");
		//Check record exists or not while added 1st time. If not then added all default type with new one.
		$isRes = $this->TypeCompany->getTypes();
		$cnt = 0;

		if (isset($isRes) && empty($isRes)) {
		    //Getting default task type
		    $types = $this->Type->getDefaultTypes();
		    foreach ($types as $key => $values) {
			$data1[$key]['type_id'] = $values['Type']['id'];
			$data1[$key]['company_id'] = SES_COMP;
			$cnt++;
		    }
		}

		$data1[$cnt]['type_id'] = $id;
		$data1[$cnt]['company_id'] = SES_COMP;
		$this->TypeCompany->saveAll($data1);
		$this->Session->write("SUCCESS","Task type '".trim($data['name'])."' added successfully.");
	    }
	} else {
	    $this->Session->write("ERROR","Error in addition of task type.");
	}
	$this->redirect(HTTP_ROOT."task-type");
    }
    
    /**
    * Save selected task types by company owner
    * 
    * @method saveTaskType
    * @author Orangescrum
    * @return
    * @copyright (c) Aug/2014, Andolsoft Pvt Ltd.
    */
    function saveTaskType() {
	if (isset($this->data['Type']) && !empty($this->data['Type'])) {
	    $this->loadModel("TypeCompany");
	    
	    $this->TypeCompany->query("DELETE FROM type_companies WHERE company_id=" . SES_COMP);
	    foreach ($this->data['Type'] as $key => $value) {
		$data['company_id'] = SES_COMP;
		$data['type_id'] = $value;
		
		$this->TypeCompany->id = '';
		$this->TypeCompany->save($data);
	    }
	    $this->Session->write("SUCCESS","Task type saved successfully.");
	} else {
	    $this->Session->write("ERROR","Error in saving of task type.");
	}
	$this->redirect(HTTP_ROOT."task-type");
    }
    
    /**
    * Delete task types by company owner
    * 
    * @method deleteTaskType
    * @author Orangescrum
    * @return boolean
    * @copyright (c) Aug/2014, Andolsoft Pvt Ltd.
    */
    function deleteTaskType() {
	$this->layout = 'ajax';
	$id = $this->params['data']['id'];
	if (intval($id)) {
	    $this->loadModel("Type");
	    $this->Type->id = $id;
	    $this->Type->delete();
	    
	    $this->loadModel("TypeCompany");
	    $this->TypeCompany->query("DELETE FROM type_companies WHERE type_id=" . $id . " AND company_id=" . SES_COMP);
	    
	    echo 1;
	} else {
	    echo 0;
	}
	exit;
    }
    function validateTaskType(){
	$jsonArr = array('status'=>'error');
	if(!empty($this->request['data']['name'])){
	    $this->loadModel("Type");
	    $count_type = $this->Type->find('first',array('conditions' => array('OR'=>array('Type.short_name' => trim($this->request['data']['sort_name']),'Type.name' => trim($this->request['data']['name'])),'Type.id !=' => trim($this->request['data']['id'])),'fields' => array("Type.name","Type.short_name")));
	    if(!$count_type){
		$jsonArr['status'] = 'success';
	    }else{
		if(strtolower($count_type['Type']['short_name']) == strtolower(trim($this->request['data']['sort_name']))){
		    $jsonArr['msg'] = 'sort_name';
		}
		if(strtolower($count_type['Type']['name']) == strtolower(trim($this->request['data']['name']))){
		    $jsonArr['msg'] = 'name';
		}
	    }
	}
	echo json_encode($jsonArr);exit;
    }
}
?>
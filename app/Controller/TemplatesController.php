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
class TemplatesController extends AppController {
    public $name = 'Templates';
	public $components = array('Format','Postcase','Tmzone');
	
	function beforeRender()
	{
		if(SES_TYPE == 3) {
			$this->redirect(HTTP_ROOT."dashboard");
		}
	}
	
	function default_install(){
		$this->loadModel('DefaultTemplate');
		$this->DefaultTemplate->store_default_template();
		
		$this->loadModel("Company");
		$all_company = $this->Company->find('list', array('fields'=>array('id'), 'conditions'=>array('is_active' => 1)));
				
		$this->DefaultTemplate->store_default_to_cstmpl($all_company);
		echo 'Done';
		die;
	}
	
	function ajax_sort_tasks()
	{
		$this->layout='ajax';
		$this->loadModel("ProjectTemplateCase");
		$listings = $_POST['menu'];
		for ($i = 0; $i < count($listings); $i++) 
		{
			$this->ProjectTemplateCase->query("UPDATE `project_template_cases` SET `sort`=" . $i . " WHERE `id`='" . $listings[$i] . "'");
		}
		exit;
	}
	
	function view_templates($templateId=NULL)
	{
		$this->loadModel("ProjectTemplateCase");
		$this->loadModel("ProjectTemplate");
		
		$template_name = $this->ProjectTemplate->find('first', array('conditions' => array('ProjectTemplate.id'=>$templateId,'ProjectTemplate.company_id' => SES_COMP)));
		//echo "<pre>";print_r($template_name);exit;
		$pjtemp = $this->ProjectTemplateCase->find('all', array('conditions'=> array('ProjectTemplateCase.template_id'  => $templateId,'ProjectTemplateCase.company_id' => SES_COMP), 'order' => 'ProjectTemplateCase.sort ASC'));
		if(count($pjtemp) > 0){
			$this->set('temp_dtls_cases',$pjtemp);
		}
		$this->set('template_name', $template_name['ProjectTemplate']['module_name']);
		$this->set('template_id', $templateId);
	}
	function projects(){
		$page_limit = TEMP_PROJECT_PAGE_LIMIT;
		$page = 1;
		if(isset($_GET['page']) && $_GET['page']){
			$page = $_GET['page'];
		}
		$limit1 = $page*$page_limit-$page_limit;
		$limit2 = $page_limit;
		
		if(isset($_GET['id']) && !empty($_GET['id'])){
			$this->loadModel("ProjectTemplate");
			$this->ProjectTemplate->id=$_GET['id'];
			$this->ProjectTemplate->delete();
			//ClassRegistry::init('ProjectTemplateCase')->query("Delete FROM project_template_cases WHERE template_id='".$_GET['id']."'");
			$this->Session->write("SUCCESS","Template Deleted successfully");
			$this->redirect(HTTP_ROOT."templates/projects/");
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
		//$proj_temp = ClassRegistry::init('ProjectTemplate')->find('all',array('conditions'=>array('ProjectTemplate.company_id'=>SES_COMP)));
		$proj_temp = ClassRegistry::init('ProjectTemplate')->query("select * from `project_templates` where `company_id`='".SES_COMP."' order by `created` DESC LIMIT $limit1, $limit2");
		$total_proj_count = ClassRegistry::init('ProjectTemplate')->find('count',array('conditions'=>array('ProjectTemplate.company_id'=>SES_COMP)));
		
		$proj_temp_active = ClassRegistry::init('ProjectTemplate')->find('all',array('conditions'=>array('ProjectTemplate.company_id'=>SES_COMP,'ProjectTemplate.is_active'=>1)));
		$this->set('proj_temp',$proj_temp);
		$this->set('caseCount',$total_proj_count);
		$this->set('page_limit',$page_limit);
		$this->set('casePage',$page);
		$this->set('proj_temp_active',$proj_temp_active);
		$this->set('role', $_GET['role']);
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
					//echo $title."-".$last_insert_id;
					echo "1";
				}else{
					echo "0";
				}
		   }else{
				echo "0";
			}
		}
		exit;
	}
	
	function add_to_project()
	{
		$this->layout='ajax';
		
		$this->loadModel("ProjectTemplateCase");
		$this->loadModel("Project");
		
		$pjtemp = $this->ProjectTemplateCase->find('all', array('conditions'=> array('ProjectTemplateCase.template_id'  => $this->params['data']['temp_id'],'ProjectTemplateCase.company_id' => SES_COMP), 'order' => 'ProjectTemplateCase.sort ASC'));
		//echo "<pre>";print_r($pjtemp);exit;
		
		if(count($pjtemp) > 0){
			$this->Project->recursive=-1;
			$project_details = $this->Project->find('all',array('conditions'=>array('Project.company_id'=>SES_COMP,'Project.isactive'=>1),'fields'=>array('Project.name','Project.id')));
			$this->set('project_details',$project_details);
			$this->set('temp_dtls_cases',$pjtemp);
			$this->set('template_id', $this->params['data']['temp_id']);
		}else{
			$this->set('template_id', $this->params['data']['temp_id']);
		}
	}
	
	function remove_from_tasks()
	{
		$this->layout='ajax';
		
		$this->loadModel("ProjectTemplateCase");
		$this->loadModel("Project");
		
		$pjtemp = $this->ProjectTemplateCase->find('all', array('conditions'=> array('ProjectTemplateCase.template_id'  => $this->params['data']['temp_id'],'ProjectTemplateCase.company_id' => SES_COMP), 'order' => 'ProjectTemplateCase.sort ASC'));
		//echo "<pre>";print_r($pjtemp);exit;
		
		if(count($pjtemp) > 0){
			$this->set('temp_dtls_cases',$pjtemp);
			$this->set('template_id', $this->params['data']['temp_id']);
		}else{
			$this->set('template_id', $this->params['data']['temp_id']);
		}
	}
	
	function ajax_template_case_listing(){
		$this->layout='ajax';
		if(isset($this->params['data']['templateId'], $this->params['data']['case_id']) && $this->params['data']['templateId'] && $this->params['data']['case_id'])
		{
			$this->loadModel("ProjectTemplateCase");
			$this->ProjectTemplateCase->id=$this->params['data']['case_id'];
			$this->ProjectTemplateCase->template_id=$this->params['data']['templateId'];
			$this->ProjectTemplateCase->delete();	
			
			$res = ClassRegistry::init('ProjectTemplate')->find('first',array('conditions'=>array('id'=>$this->params['data']['templateId'],'company_id'=>SES_COMP), 'fields'=>array('module_name')));
//			echo "<pre>";print_r($res);echo $res['ProjectTemplate']['module_name'];exit;
			echo "removed****".$res['ProjectTemplate']['module_name'];exit;
		}
	}
	
	function ajax_template_edit(){
		$this->layout='ajax';
		ob_clean();
		if(isset($this->params['data']['template_id']) && $this->params['data']['template_id'])
		{
			$temp_id = $this->params['data']['template_id'];
			$ttl = urldecode($this->params['data']['module_name']);
			$res = ClassRegistry::init('ProjectTemplate')->find('all',array('conditions'=>array('module_name'=>$ttl,'company_id'=>SES_COMP)));
			if(count($res) == 0){
				$this->loadModel("ProjectTemplate");
				$this->ProjectTemplate->id = $temp_id;
				if($this->ProjectTemplate->saveField("module_name",$ttl)){
					echo "success";exit;
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
	
	function ajax_add_template_cases(){
		$this->layout='ajax';
		ob_clean();
		//echo "<pre>";print_r($this->params['data']);exit;
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
					$pjtemp = $this->ProjectTemplateCase->find('all', array('conditions'=> array('ProjectTemplateCase.template_id'=>$this->params['data']['temp_mod_id'],'ProjectTemplateCase.company_id'=>SES_COMP), 'order'=>'ProjectTemplateCase.sort ASC'));
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
	
	function add_template(){
		$this->layout='ajax';
		//echo "<pre>";print_r($this->data);exit;
		$this->set('temp_id', $this->data['temp_id']);
		$this->set('temp_name', $this->data['temp_name']);
	}
	
	function add_template_task(){
		//echo "<pre>";print_r($this->request);exit;
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
			$this->redirect(HTTP_ROOT."templates/projects/");
		}
	}
	
	function edit_template_task(){
		if(count($this->request['data']) > 0){
			if(isset($this->request['data']['submit_template_edit'])){
				$this->loadModel('ProjectTemplateCase');
				$temp_case['title'] = $this->request['data']['title_edit'];
				$temp_case['description'] = $this->request['data']['description_edit'];
				$this->ProjectTemplateCase->id = $this->request['data']['template_id'];
				$this->ProjectTemplateCase->save($temp_case);
			}
			$this->Session->write("SUCCESS","Template tasks updated successfully");
			$this->redirect(HTTP_ROOT."templates/projects/");
		}
		exit;
	}
	function tasks(){
		
		$this->loadModel("CaseTemplate");
		$page_limit = TEMP_TASK_PAGE_LIMIT;
		$page = 1;
		$pageprev=1;
		if(isset($_GET['page']) && $_GET['page'])
		{
			$page = $_GET['page'];
		}
		$limit1 = $page*$page_limit-$page_limit;
		$limit2 = $page_limit;
		//$query = "SELECT SQL_CALC_FOUND_ROWS * FROM case_templates WHERE case_templates.company_id='".SES_COMP."' AND (case_templates.user_id='".SES_ID."' OR case_templates.user_id='0') ORDER BY created DESC LIMIT ".$limit1.",".$limit2;
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM case_templates WHERE case_templates.company_id='".SES_COMP."' AND (1) ORDER BY created DESC LIMIT ".$limit1.",".$limit2;
		$TempalteArray = $this->CaseTemplate->query($query);
		
		$found_rows = $this->CaseTemplate->query("SELECT FOUND_ROWS() as total");
		//echo "<pre>";print_r($TempalteArray);exit;
		//$limit = $limit1.",".$limit2;
		$this->set('caseCount',$found_rows[0][0]['total']);
		$this->set('page_limit',$page_limit);
		$this->set('casePage',$page);
		$this->set('pageprev',$pageprev);	
		$this->set('TempalteArray',$TempalteArray);
	}
	
	function temptaskdelete($tempId)
	{
		$this->loadModel("CaseTemplate");
		$this->CaseTemplate->id = $tempId;
		$this->CaseTemplate->delete();
		$this->Session->write("SUCCESS","Task template deleted successfully");
		$this->redirect(HTTP_ROOT."templates/tasks");
	}
	
	function ajax_add_task_template(){
		$this->layout='ajax';
		$this->loadModel("CaseTemplate");
		//echo "<pre>";print_r($this->request['data']);exit;
		if(isset($this->request['data']['tempId']) && $this->request['data']['tempId']){
			$res = $this->CaseTemplate->find('first', array('conditions'=> array('CaseTemplate.id'=>$this->request['data']['tempId'])));
			$res['CaseTemplate']['pageNum'] = $this->request['data']['pagenum'];
			//echo "<pre>";print_r($res);exit;
			print json_encode($res);exit;
			//$this->set('TempalteArray',$res);
		}else{
			if(!empty($this->request->data) && $this->Auth->User('id')){
				$this->request->data['CaseTemplate']['name'] = htmlentities(strip_tags($this->request->data['title']));
				$this->request->data['CaseTemplate']['description'] = $this->request->data['tempDesc'];
				$this->request->data['CaseTemplate']['user_id'] = $this->Auth->User('id');
				$this->request->data['CaseTemplate']['company_id']=SES_COMP;
				
				if(isset($this->request->data['tempId']) && $this->request->data['tempId'] == '') //Coding for ADD the task templates
				{
				   $task = $this->CaseTemplate->find('count',array('conditions' => array('CaseTemplate.user_id' =>$this->Auth->User('id'), 'CaseTemplate.name' => $this->params->data['title'],'CaseTemplate.company_id'=>SES_COMP)));	
				   if($task == 0){
						if($this->CaseTemplate->save($this->request->data)){
							echo "1";
						}else{
							echo "0";
						}
					}else{
						echo "4";
					}	
				}else{ //Code for EDIT the task template
					if(isset($this->request->data['tasktempId'])){
						if(trim($this->request->data['tasktempId'])) {
							unset($this->request->data['CaseTemplate']['user_id']);
						}
						$this->CaseTemplate->id = $this->request->data['tasktempId'];
						if($this->CaseTemplate->save($this->request->data)){
							echo "2";
						}else{
							echo "3";
						}
					}
				}
		    }
	    }	  
		exit;
	}
	
	function activateTaskTemp($tempId,$pagenum=NULL)
	{
		$this->loadModel("CaseTemplate");
		$this->CaseTemplate->id=$tempId;
		if($this->CaseTemplate->saveField("is_active",1)){
			$this->Session->write("SUCCESS","Template enabled successfully");
			if(isset($pagenum) && $pagenum != 1){
				$this->redirect(HTTP_ROOT."templates/tasks/?page=".$pagenum);
			}else{
				$this->redirect(HTTP_ROOT."templates/tasks");
			}	
		}else{
			$this->Session->write("ERROR","Template can't be enabled.Please try again.");
			$this->redirect(HTTP_ROOT."templates/tasks");
		}
	}
	
	function deactivateTaskTemp($tempId,$pagenum)
	{
		$this->loadModel("CaseTemplate");
		$this->CaseTemplate->id = $tempId;
		if($this->CaseTemplate->saveField("is_active",0)){
			$this->Session->write("SUCCESS","Template disabled successfully");
			if(isset($pagenum) && $pagenum != 1){
				$this->redirect(HTTP_ROOT."templates/tasks/?page=".$pagenum);
			}else{
				$this->redirect(HTTP_ROOT."templates/tasks");
			}	
		}else{
			$this->Session->write("ERROR","Template can't be disabled.Please try again.");
			$this->redirect(HTTP_ROOT."templates/tasks");
		}
	}
}	
?>
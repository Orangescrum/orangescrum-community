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
class ArchivesController extends AppController {
    public $name = 'Archive';
	
	function listall(){
	}
     function milestone_list($uniq_id = null){
          $this->layout='ajax';
		$page_limit = ARC_PAGE_LIMIT;
		$pjid = $this->params['data']['pjid'];
		$casePage = isset($this->params['data']['casePage'])?(int)$this->params['data']['casePage']:1;
          if($pjid=="all"){
               $this->loadModel('Milestone');
               if(SES_TYPE == 1 || SES_TYPE == 2){	
                    $total_record1 = $this->Milestone->query("SELECT * FROM milestones AS Milestone,project_users AS ProjectUser WHERE Milestone.project_id=ProjectUser.project_id AND ProjectUser.user_id='".SES_ID."' AND Milestone.company_id='".SES_COMP."' AND Milestone.isactive=0 ");
               }else{
                    $total_record1 = $this->Milestone->query("SELECT * FROM milestones AS Milestone,project_users AS ProjectUser WHERE Milestone.	user_id ='".SES_ID."' AND Milestone.project_id=ProjectUser.project_id AND ProjectUser.user_id='".SES_ID."' AND Milestone.company_id='".SES_COMP."' AND Milestone.isactive=0 ");
               }
		     $total_records = count($total_record1);
		     $this->set('total_records',$total_records);
		     $page = $casePage;
		     $limit1 = $page*$page_limit-$page_limit;
		     $limit2 = $page_limit;
               if(SES_TYPE == 1 || SES_TYPE == 2){
                    $query = "SELECT * FROM milestones AS Milestone WHERE Milestone.company_id='".SES_COMP."' AND Milestone.isactive=0  ORDER BY Milestone.start_date ASC LIMIT ".$limit1.",".$limit2;
               }else{
                    $query = "SELECT * FROM milestones AS Milestone WHERE Milestone.	user_id ='".SES_ID."' AND Milestone.company_id='".SES_COMP."' AND Milestone.isactive=0  ORDER BY Milestone.start_date ASC LIMIT ".$limit1.",".$limit2;
               }
               $milestones = $this->Milestone->query($query);
		     $count_mile = count($milestones);
               $this->set('count_mile',$count_mile);
               $this->set('page_limit',$page_limit);
			$this->set('casePage',$casePage);
			$this->set('list',$milestones);
			$this->set('pjid','all');
          }else{
               $this->loadModel('Milestone');	
               if(SES_TYPE == 1 || SES_TYPE == 2){	                    
                    $total_record1 = $this->Milestone->query("SELECT * FROM milestones AS Milestone,project_users AS ProjectUser WHERE Milestone.project_id=ProjectUser.project_id AND ProjectUser.user_id='".SES_ID."' AND Milestone.company_id='".SES_COMP."' AND Milestone.isactive=0 AND Milestone.project_id = '".$pjid."'");
               }else{
                    $total_record1 = $this->Milestone->query("SELECT * FROM milestones AS Milestone,project_users AS ProjectUser WHERE Milestone.	user_id ='".SES_ID."' AND Milestone.project_id=ProjectUser.project_id AND ProjectUser.user_id='".SES_ID."' AND Milestone.company_id='".SES_COMP."' AND Milestone.isactive=0 AND Milestone.project_id = '".$pjid."'");
               }
		     $total_records = count($total_record1);
		     $this->set('total_records',$total_records);
		     $page = $casePage;
		     $limit1 = $page*$page_limit-$page_limit;
		     $limit2 = $page_limit;
               if(SES_TYPE == 1 || SES_TYPE == 2){
                    $query = "SELECT * FROM milestones AS Milestone WHERE Milestone.project_id ='".$pjid."' AND Milestone.company_id='".SES_COMP."' AND Milestone.isactive=0 ORDER BY Milestone.start_date ASC LIMIT ".$limit1.",".$limit2;
               }else{
                    $query = "SELECT * FROM milestones AS Milestone WHERE Milestone.	user_id ='".SES_ID."' AND Milestone.project_id ='".$pjid."' AND Milestone.company_id='".SES_COMP."' AND Milestone.isactive=0 ORDER BY Milestone.start_date ASC LIMIT ".$limit1.",".$limit2;
               }                    
               
               $milestones = $this->Milestone->query($query);
		     $count_mile = count($milestones);
               $this->set('count_mile',$count_mile);
               $this->set('page_limit',$page_limit);
			$this->set('casePage',$casePage);
			$this->set('list',$milestones);
			$this->set('pjid',$pjid);
          }
     }
	function case_list($uniq_id = null)
	{
		$this->layout='ajax';
		//$page_limit = ARC_PAGE_LIMIT;
		//$page_limit = 10;
		//echo "<pre>";print_r($this->params['data']);exit;
		$pjid = $this->params['data']['pjid'];//echo $pjid;
		//$limit1 = $this->params['data']['limit1'];
		//$limit2 = $this->params['data']['limit2'];
		$casePage = isset($this->params['data']['casePage'])?(int)$this->params['data']['casePage']:1;
		$this->loadModel('Easycase');	
		$this->loadModel('ProjectUser');
		$getAllProj = $this->ProjectUser->find('all',array('conditions'=>array('ProjectUser.user_id'=>SES_ID,'ProjectUser.company_id'=>SES_COMP),'fields'=>'ProjectUser.project_id'));
		if(!empty($getAllProj)){
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
			if($pjid=="all"){
				$caseCount1 = $this->Easycase->query("SELECT Easycase.id,Easycase.title,Easycase.uniq_id,Easycase.format,Easycase.case_no,Easycase.type_id,Easycase.legend,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,Archive.dt_created, User.name, User.last_name, User.short_name FROM easycases as Easycase,archives as Archive, users as User WHERE Easycase.id=Archive.easycase_id AND Easycase.user_id=User.id AND Archive.type = '1' AND Archive.company_id ='".SES_COMP."' ".$qry." AND Easycase.project_id != '0';");
			
				//pr($caseCount1);exit;
				
				$caseCount = count($caseCount1);
				$this->set('caseCount',$caseCount);
				$page = $casePage;
				//$limit1 = $page*$page_limit-$page_limit;
				//$limit2 = $page_limit;
				$limit1 = $this->params['data']['limit1'];
				$limit2 = $this->params['data']['limit2'];
				$cse = $this->Easycase->query("SELECT Easycase.id,Easycase.title,Easycase.uniq_id,Easycase.format,Easycase.case_no,Easycase.type_id,Easycase.legend,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,Archive.dt_created, User.name, User.last_name, User.short_name FROM easycases as Easycase,archives as Archive, users as User WHERE Easycase.id=Archive.easycase_id AND Easycase.user_id=User.id AND Archive.type = '1' AND Archive.company_id ='".SES_COMP."' ".$qry." AND Easycase.project_id != '0' ORDER BY Archive.dt_created DESC LIMIT ".$limit1.",".$limit2);
			
				//pr($caseCount1);exit;
				$this->set('page_limit',$page_limit);
				$this->set('casePage',$casePage);
				$this->set('list',$cse);
				if(isset($this->params['data']['lastCount']) && $this->params['data']['lastCount'] != ''){
					$this->set('lastCount',$this->params['data']['lastCount']);
				}else{
					$this->set('lastCount',0);
				}
				$this->set('pjid','all');
			}else{
				$this->loadModel('Easycase');	
				$caseCount1 = $this->Easycase->query("SELECT Easycase.id,Easycase.title,Easycase.uniq_id,Easycase.format,Easycase.case_no,Easycase.type_id,Easycase.legend,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,Archive.dt_created FROM easycases as Easycase,archives as Archive WHERE Easycase.id=Archive.easycase_id AND Archive.type = '1' AND Archive.company_id ='".SES_COMP."' AND Easycase.project_id = '".$pjid."'");
				$caseCount = count($caseCount1);
				$this->set('caseCount',$caseCount);
				$page = $casePage;
				$limit1 = $page*$page_limit-$page_limit;
				$limit2 = $page_limit;
				
				$cse = $this->Easycase->query("SELECT Easycase.id,Easycase.title,Easycase.uniq_id,Easycase.format,Easycase.case_no,Easycase.type_id,Easycase.legend,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,Archive.dt_created FROM easycases as Easycase,archives as Archive WHERE Easycase.id=Archive.easycase_id AND Archive.type = '1' AND Archive.company_id ='".SES_COMP."' AND Easycase.project_id = '".$pjid."' ORDER BY Archive.dt_created DESC LIMIT ".$limit1.",".$limit2);
				$this->set('page_limit',$page_limit);
				$this->set('casePage',$casePage);
				$this->set('list',$cse);
				$this->set('pjid',$pjid);
			}
		}		
	}
	function file_list($uniq_id = null)
	{ 
		$this->layout='ajax';
		$page_limit = ARC_PAGE_LIMIT;
		$pjid = $this->params['data']['pjid'];
		$casePage = isset($this->params['data']['casePage'])?(int)$this->params['data']['casePage']:1;
		$this->loadModel('Easycase');
		$this->loadModel('ProjectUser');
		
		$getAllProj = $this->ProjectUser->find('all',array('conditions'=>array('ProjectUser.user_id'=>SES_ID,'ProjectUser.company_id'=>SES_COMP),'fields'=>'ProjectUser.project_id'));
		if(!empty($getAllProj)){
			if($pjid=="all")
			{		
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
				//$caseCount11 = $this->Easycase->query("SELECT Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,CaseFile.*,Archive.dt_created FROM easycases as Easycase,case_files as CaseFile,archives as Archive WHERE Archive.case_file_id=CaseFile.id AND Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND CaseFile.isactive = '0' AND Archive.type='1' AND Archive.user_id='".SES_ID."' AND Archive.company_id ='".SES_COMP."' AND Easycase.project_id != '0';");
				$caseCount11 = $this->Easycase->query("SELECT Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,CaseFile.*,Archive.dt_created FROM easycases as Easycase,case_files as CaseFile,archives as Archive WHERE Archive.case_file_id=CaseFile.id AND Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND CaseFile.isactive = '0' AND Archive.type='1' AND Archive.company_id ='".SES_COMP."' ".$qry." AND Easycase.project_id != '0';");
				$caseCountt = count($caseCount11);
				$this->set('caseCountt',$caseCountt);
				$page = $casePage;
				//$limit1 = $page*$page_limit-$page_limit;
				//$limit2 = $page_limit;
				$limit1 = $this->params['data']['limit1'];
				$limit2 = $this->params['data']['limit2'];
				//$file = $this->Easycase->query("SELECT Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,CaseFile.*,Archive.dt_created FROM easycases as Easycase,case_files as CaseFile,archives as Archive WHERE Archive.case_file_id=CaseFile.id AND Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND CaseFile.isactive = '0' AND Archive.type='1' AND Archive.user_id='".SES_ID."' AND Archive.company_id ='".SES_COMP."' AND Easycase.project_id != '0' ORDER BY Archive.dt_created DESC LIMIT ".$limit1.",".$limit2);//pr($file);exit;
			
				$file = $this->Easycase->query("SELECT Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,CaseFile.*,Archive.dt_created FROM easycases as Easycase,case_files as CaseFile,archives as Archive WHERE Archive.case_file_id=CaseFile.id AND Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND CaseFile.isactive = '0' AND Archive.type='1' AND Archive.company_id ='".SES_COMP."' ".$qry." AND Easycase.project_id != '0' ORDER BY Archive.dt_created DESC LIMIT ".$limit1.",".$limit2);//pr($file);exit;
				$this->set('page_limit',$page_limit);
				$this->set('casePage',$casePage);
				$this->set('file',$file);
				$this->set('pjid','all');
				if(isset($this->params['data']['lastCountFiles']) && $this->params['data']['lastCountFiles'] != ''){
					$this->set('lastCountFiles',$this->params['data']['lastCountFiles']);
				}else{
					$this->set('lastCountFiles',0);
				}
			}
			else
			{
				$this->loadModel('Easycase');
				$caseCount11 = $this->Easycase->query("SELECT Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,CaseFile.*,Archive.dt_created FROM easycases as Easycase,case_files as CaseFile,archives as Archive WHERE Archive.case_file_id=CaseFile.id AND Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND CaseFile.isactive = '0' AND Archive.type='1' AND Archive.company_id ='".SES_COMP."' AND Easycase.project_id = '".$pjid."';");
				$caseCountt = count($caseCount11);
				$this->set('caseCountt',$caseCountt);
				$page = $casePage;
				$limit1 = $page*$page_limit-$page_limit;
				$limit2 = $page_limit;
				$file = $this->Easycase->query("SELECT Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.istype,Easycase.project_id,CaseFile.*,Archive.dt_created FROM easycases as Easycase,case_files as CaseFile,archives as Archive WHERE Archive.case_file_id=CaseFile.id AND Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND CaseFile.isactive = '0' AND Archive.type='1' AND Archive.company_id ='".SES_COMP."' AND Easycase.project_id = '".$pjid."' ORDER BY Archive.dt_created DESC LIMIT ".$limit1.",".$limit2);//pr($file);exit;
				$this->set('page_limit',$page_limit);
				$this->set('casePage',$casePage);
				$this->set('file',$file);
				$this->set('pjid',$pjid);
			}
		}
		/*$proj_all_cond = array(
		'conditions' => array('ProjectUser.user_id'=>SES_ID,'Project.isactive'=>1,'Project.company_id'=>SES_COMP),
		'fields' => array('DISTINCT Project.id','Project.name','Project.uniq_id'),
		'order' => array('ProjectUser.dt_visited DESC')
		);
		$ProjectUser = ClassRegistry::init('ProjectUser');
		$CompanyUser = ClassRegistry::init('CompanyUser');
		
		$projAll = $ProjectUser->find('all', $proj_all_cond);
		$this->set('projAll',$projAll);*/
	}
	function move_list()
	{
		$this->layout='ajax';
		$val=$this->params['data']['val'];
		foreach($val as $val){
			$this->loadModel('Easycase');
			if(isset($this->params['data']['chk'])){
			    $qrr = $this->Easycase->query("UPDATE easycases SET isactive = '1' WHERE easycases.id ='".$val."'");
			    $qrid = $this->Easycase->query("SELECT id,project_id,case_no FROM easycases WHERE easycases.id ='".$val."'");			
			}else{
			    $qrr = $this->Easycase->query("UPDATE easycases SET isactive = '1' WHERE easycases.uniq_id ='".$val."'");
			    $qrid = $this->Easycase->query("SELECT id,project_id,case_no FROM easycases WHERE easycases.uniq_id ='".$val."'");
			}
			$CaseActivity = ClassRegistry::init('CaseActivity');
			$CaseActivity->recursive = -1;
			$CaseActivity->query("UPDATE case_activities SET isactive='1' WHERE project_id=".$qrid['0']['easycases']['project_id']." AND case_no=".$qrid['0']['easycases']['case_no']);
			$this->loadModel('Archive');
			$qrr = $this->Archive->query("UPDATE archives SET type = '2' WHERE easycase_id ='".$qrid['0']['easycases']['id']."'");

		}
		echo "success";
		exit;
	}
     function restore_case()
	{
		$this->layout='ajax';
		$val=$this->params['data']['val'];
			$this->loadModel('Easycase');
			$qrr = $this->Easycase->query("UPDATE easycases SET isactive = '1' WHERE easycases.uniq_id ='".$val."'");
			$qrid = $this->Easycase->query("SELECT id,project_id,case_no FROM easycases WHERE easycases.uniq_id ='".$val."'");
			$CaseActivity = ClassRegistry::init('CaseActivity');
			$CaseActivity->recursive = -1;
			$CaseActivity->query("UPDATE case_activities SET isactive='1' WHERE project_id=".$qrid['0']['easycases']['project_id']." AND case_no=".$qrid['0']['easycases']['case_no']);
			$this->loadModel('Archive');
			$qrr = $this->Archive->query("UPDATE archives SET type = '2' WHERE easycase_id ='".$qrid['0']['easycases']['id']."'");

		echo "success";
		exit;
	}
     function milestone_move_list(){
          $this->layout='ajax';
		$val=$this->params['data']['val'];
		foreach($val as $val){
			$this->loadModel('Milestone');
			$qrr = $this->Milestone->query("UPDATE milestones SET isactive = '1' WHERE milestones.uniq_id ='".$val."'");
		}
		echo "success";
		exit;
     }
     function milestone_remove(){
          $this->layout='ajax';
		$val=$this->params['data']['val'];//print_r($val);
          foreach($val as $val) { 
               $this->loadModel('Milestone');
               $qrid = $this->Milestone->query("SELECT id FROM milestones WHERE milestones.uniq_id ='".$val."'");
               if($qrid['0']['milestones']['id']) {
                    $this->Milestone->query("delete from milestones WHERE id ='".$qrid['0']['milestones']['id']."'");
               }
          }
          echo "success";
          exit;
     }
	function case_remove(){
		$this->layout='ajax';
		$val=$this->params['data']['val'];
		foreach($val as $val) { 
			
			$this->loadModel('Easycase');
			//$getCase = $this->Easycase->find('first',array('conditions'=>array('Easycase.uniq_id'=>$val)));
			if(isset($this->params['data']['chk'])){
			    $qrid = $this->Easycase->query("SELECT id FROM easycases WHERE easycases.id ='".$val."'");
			}else{
			    $qrid = $this->Easycase->query("SELECT id FROM easycases WHERE easycases.uniq_id ='".$val."'");
			}			
			if($qrid['0']['easycases']['id']) {
				$this->Easycase->query("delete from easycases WHERE id ='".$qrid['0']['easycases']['id']."'");
				
				$this->loadModel('CaseFile');
				$getFiles = $this->CaseFile->find('all',array('conditions'=>array('CaseFile.easycase_id'=>$qrid['0']['easycases']['id'])));
				
				foreach($getFiles as $files) {
					@unlink(DIR_CASE_FILES.$files['CaseFile']['file']);
				}
				
				$this->CaseFile->query("delete from case_files WHERE easycase_id ='".$qrid['0']['easycases']['id']."'");
				
				$this->loadModel('Archive');
				//$qrr = $this->Archive->query("UPDATE archives SET type='3' WHERE easycase_id ='".$qrid['0']['easycases']['id']."'");.
				$this->Archive->query("delete from archives WHERE easycase_id ='".$qrid['0']['easycases']['id']."'");
			}
			
			
		}
		echo "success";
		exit;
	}
	function move_file()
	{
		$this->layout='ajax';
		$val = $this->params['data']['val'];
		$this->loadModel('CaseFile');
		$this->loadModel('Archive');
		$this->loadModel('Easycase');
		foreach($val as $val){ 
			
			$qur = $this->CaseFile->query("UPDATE case_files SET isactive = '1' WHERE case_files.id =".$val);
			
			$qrr = $this->Archive->query("UPDATE archives SET type = '2' WHERE case_file_id ='".$val."'");
			
			$getFiles = $this->CaseFile->find('first',array('conditions'=>array('CaseFile.id'=>$val)));
			$checkFiles = $this->CaseFile->find('all',array('conditions'=>array('CaseFile.easycase_id'=>$getFiles['CaseFile']['easycase_id'],'CaseFile.isactive'=>1)));
			if(count($checkFiles) == 0) {
				$this->Easycase->query("UPDATE easycases SET format='2' WHERE id='".$getFiles['CaseFile']['easycase_id']."'");
			}
			else {
				$this->Easycase->query("UPDATE easycases SET format='1' WHERE id='".$getFiles['CaseFile']['easycase_id']."'");
			}
		}
		echo "success";
		exit;
	}
	function file_remove(){
		$this->layout='ajax';
		$val = $this->params['data']['val'];
		$this->loadModel('Archive');
		$this->loadModel('CaseFile');
		$this->loadModel('Easycase');
		foreach($val as $val)
		{ 		
			//$qrr = $this->Archive->query("UPDATE archives SET type = '3' WHERE case_file_id ='".$val."'");
			
			$getFiles = $this->CaseFile->find('first',array('conditions'=>array('CaseFile.id'=>$val)));
			@unlink(DIR_CASE_FILES.$getFiles['CaseFile']['file']);
			$this->CaseFile->query("delete from case_files WHERE id ='".$val."'");
			
			$this->Archive->query("delete from archives WHERE case_file_id ='".$val."'");
		}
		
		echo "success";
		exit;
	}
	function ajax_projectall_size()
	{
		$this->layout='ajax';
		$proj_id = NULL;
		
		$company = SES_COMP;
		if(isset($this->params['data']['comp'])) {
			$company = $this->params['data']['comp'];
		}
		$this->set('type',@$this->params['data']['type']);
		$this->set('company',@$company);
	}
	function ajax_project_access()
	{
		$this->layout='ajax';
		$ProjectUser = ClassRegistry::init('ProjectUser');
		$ProjectUser->recursive = -1;
		$latestactivity = $ProjectUser->find('first', array('conditions'=>array('ProjectUser.user_id =' => SES_ID),'fields' =>array('dt_visited','project_id'),'order' => array('ProjectUser.dt_visited DESC')) );
		
		$projArr = $latestactivity['ProjectUser']['project_id'];
		$this->loadModel('Project');
		$this->Project->recursive = -1;
		$projArr = $this->Project->find('first', array('conditions' => array('Project.id'=>$projArr,'Project.isactive'=>1),'fields' => array('Project.name','Project.id','Project.uniq_id')));
		
		$this->set('dt_visited',$latestactivity['ProjectUser']['dt_visited']);
		
		$this->set('projArr',$projArr);
		
	}
}
?>

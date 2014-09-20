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
class CaseSettingsController extends AppController {
	public $name = 'CaseSettings';
	
    var $paginate = array();

 	function add(){

		$this->loadModel('Project');
		$prj=$this->Project->query("SELECT Project.name,Project.id FROM projects AS Project inner JOIN project_users AS ProjectUser ON(ProjectUser.user_id='".SES_ID."' AND ProjectUser.project_id=Project.id)");
		$this->set('prj',$prj);
	}

	function getdetailscase(){

		$this->layout='ajax';
		$pjid = $this->params['data']['pjid'];
		$this->loadModel('CaseSetting');
		$csset = $this->CaseSetting->find('first',array('conditions'=>array('CaseSetting.project_id ='=>$pjid,'CaseSetting.user_id ='=>SES_ID)));
			if(!empty($csset) && count($csset)!=0){

				$this->set('csset',$csset);
			}else{
			
				$this->set('ntset','ntset');
			}
		$this->loadModel('Project');
		$this->loadModel('ProjectUser');
		$res =$this->Project->query("SELECT users.name,users.id,projects.uniq_id FROM users, project_users, projects WHERE projects.id =".$pjid." AND projects.id = project_users.project_id AND project_users.user_id = users.id");
		$this->set('res',$res);	
		$this->loadModel('Type');
		$typ = $this->Type->find('all');
		$this->set('typ',$typ);
			
	}
	function postdetailscase(){
		$this->layout='ajax';
		$this->params['data']['project_id']= $this->params['data']['pjid'];
		$this->params['data']['project_uniqid']= $this->params['data']['pjuniqid'];
		$this->params['data']['type_id'] = $this->params['data']['typid'];
		$this->params['data']['assign_to'] = $this->params['data']['asgn'];
		$this->params['data']['due_date'] = $this->params['data']['duedt'];
		$this->params['data']['priority'] = $this->params['data']['priority'];
		$this->params['data']['email']=implode(",",$this->params['data']['email']);
		$this->params['data']['user_id'] = $this->params['data']['case'];
			if($this->params['data']['id']==""){ 
				if($this->CaseSetting->save($this->params['data'])){
					$this->Session->write("SUCCESS","Project added successfully");
				}	
			}else{
				$this->CaseSetting->id=$this->params['data']['id'];
				if($this->CaseSetting->save($this->params['data'])){
					$this->Session->write("SUCCESS","Project added successfully");
				}
			}
	}
}   
?>

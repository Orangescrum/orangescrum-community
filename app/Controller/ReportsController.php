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
class ReportsController extends AppController {
	var $helpers = array ('Html','Form','Casequery','Format');
	var $name = 'Report';
	public $components = array('Format','Tmzone');
    var $paginate = array();
    	var $report_type = array('1'=>'Task','2'=>'Hour','3'=>'Bug','4'=>'Project');
	
	function chart(){
		ob_clean();
		if(isset($this->params['pass']['0']) && !empty($this->params['pass']['0'])){
			if($this->params['pass']['0'] == 'ajax')
				$this->layout = 'ajax';
			$prj = $this->params['pass']['0'] != 'ajax' ? $this->params['pass']['0'] : $this->params['pass']['1'];
			$this->loadModel('Project');
			$projarr=$this->Project->query("SELECT id,name FROM projects WHERE uniq_id='".$prj."' AND company_id='".SES_COMP."'");
			$proj_id=$projarr['0']['projects']['id'];
			$this->set('pjid',$proj_id);
			$this->set('pjname',$projarr['0']['projects']['name']);
			$type_id=0;
			$this->set('proj_uniq',$prj);
			
			$this->Project->query("UPDATE project_users SET dt_visited='".GMT_DATETIME."' WHERE user_id=".SES_ID." and project_id='".$proj_id."' and company_id='".SES_COMP."'");
		}
		
		$this->loadModel('ProjectUser');
		$proj_all_cond = array(
		'recursive'=>'1',
		'conditions' => array('ProjectUser.user_id'=>SES_ID,'ProjectUser.company_id'=>SES_COMP,'Project.isactive'=>1),
		'fields' => array('Project.id','Project.uniq_id'),
		'order' => array('ProjectUser.dt_visited DESC')
		);
		$this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
		$projAll = $this->ProjectUser->find('list', $proj_all_cond);
		$this->set('projAll',$projAll);
		
		if(!isset($this->params['pass']['0'])){
			foreach($projAll as $pid=>$puid){
				$this->set('pjid',$pid);
				$this->set('proj_uniq',$puid);
				break;
			}
		}
		if(isset($this->params['pass']['0']) && !empty($this->params['pass']['0'])){
			if(!in_array($prj,$projAll)){
				$this->Session->write("ERROR","Unauthorized URL");
				$this->redirect(HTTP_ROOT."task-report");
			}
		}
		$this->loadModel('SaveReport');
		$rptdata = $this->SaveReport->find('all',array('conditions'=>array('user_id'=>SES_ID)));
		if(!empty($rptdata)){
			$this->set('frm',date('M d, Y',strtotime($rptdata[0]['SaveReport']['frm_dt'])));
			$this->set('to',date("M d, Y",strtotime($rptdata[0]['SaveReport']['to_dt'])));
			$before=$this->Format->chgdate(date('M d, Y',strtotime($rptdata[0]['SaveReport']['frm_dt'])));
			$to=$this->Format->chgdate(date('M d, Y',strtotime($rptdata[0]['SaveReport']['to_dt'])));
			$days = (strtotime($to) - strtotime($before)) / (60 * 60 * 24);
		}else{				
			$timezone_offset = TZ_GMT;
			$cur_time = date('Y-m-d H:i:s',(strtotime(GMT_DATETIME) + ($timezone_offset*60*60)));
			$before = date('Y-m-d H:i:s',strtotime($cur_time."-7 day"));
			$days = (strtotime(date("Y-m-d H:i:s")) - strtotime($before)) / (60 * 60 * 24) + 1;
			$this->set('frm',date('M d, Y',strtotime($cur_time."-7 day")));
			$this->set('to',date("M d, Y"));
		}
		
	}
	function convertinto_array($arr='',$t=0){
		$ret_arr=array();
		global $resolved_type_arr;
		$resolved_type_arr =array();
		if(is_array($arr)){
			foreach($arr AS $key=>$val){
				foreach($val AS $k=>$v){
					
					if($t){
						$ret_arr[$v['cdate']]=isset($ret_arr[$v['cdate']])?($ret_arr[$v['cdate']]+$v['count']):$v['count'];
						$resolved_type_arr[$v['cdate']][$v['tid']] =  $v['count'];
					}else{
						$ret_arr[$v['cdate']]=$v['count'];
					}
				}
			}
		}
		return $ret_arr;
	}
function glide_chart(){
		ob_clean();
		if(isset($this->params['pass']['0']) && !empty($this->params['pass']['0'])){
			if($this->params['pass']['0'] == 'ajax')
				$this->layout = 'ajax';
			$prj = $this->params['pass']['0'] != 'ajax' ? $this->params['pass']['0'] : $this->params['pass']['1'];
			$this->loadModel('Project');
			$projarr=$this->Project->query("SELECT id,name FROM projects WHERE uniq_id='".$prj."' AND company_id='".SES_COMP."'");
			$proj_id=$projarr['0']['projects']['id'];
			$this->set('pjid',$proj_id);
			$this->set('pjname',$projarr['0']['projects']['name']);
			$type_id=0;
			$this->set('proj_uniq',$prj);
			
			$this->Project->query("UPDATE project_users SET dt_visited='".GMT_DATETIME."' WHERE user_id=".SES_ID." and project_id='".$proj_id."' and company_id='".SES_COMP."'");
		}
		
		$this->loadModel('ProjectUser');
		$proj_all_cond = array(
		'recursive'=>'1',
		'conditions' => array('ProjectUser.user_id'=>SES_ID,'ProjectUser.company_id'=>SES_COMP,'Project.isactive'=>1),
		'fields' => array('Project.id','Project.uniq_id'),
		'order' => array('ProjectUser.dt_visited DESC')
		);
		$this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
		$projAll = $this->ProjectUser->find('list', $proj_all_cond);
		$this->set('projAll',$projAll);
		
		if(!isset($this->params['pass']['0'])){
			foreach($projAll as $pid=>$puid){
				$this->set('pjid',$pid);
				$this->set('proj_uniq',$puid);
				break;
			}
		}
		if(isset($this->params['pass']['0']) && !empty($this->params['pass']['0'])){
			if(!in_array($prj,$projAll)){
				$this->Session->write("ERROR","Unauthorized URL");
				$this->redirect(HTTP_ROOT."bug-report");
			}
		}
		//$timezone_offset = $_COOKIE['SES_TZ']['GMT'];
		$this->loadModel('SaveReport');
		$rptdata = $this->SaveReport->find('all',array('conditions'=>array('user_id'=>SES_ID)));
		if(!empty($rptdata)){
			$this->set('frm',date('M d, Y',strtotime($rptdata[0]['SaveReport']['frm_dt'])));
			$this->set('to',date("M d, Y",strtotime($rptdata[0]['SaveReport']['to_dt'])));
			$before=$this->Format->chgdate(date('M d, Y',strtotime($rptdata[0]['SaveReport']['frm_dt'])));
			$to=$this->Format->chgdate(date('M d, Y',strtotime($rptdata[0]['SaveReport']['to_dt'])));
			$days = (strtotime($to) - strtotime($before)) / (60 * 60 * 24);
		}else{				
			$timezone_offset = TZ_GMT;
			$cur_time = date('Y-m-d H:i:s',(strtotime(GMT_DATETIME) + ($timezone_offset*60*60)));
			$before = date('Y-m-d H:i:s',strtotime($cur_time."-7 day"));
			$days = (strtotime(date("Y-m-d H:i:s")) - strtotime($before)) / (60 * 60 * 24) + 1;
			$this->set('frm',date('M d, Y',strtotime($cur_time."-7 day")));
			$this->set('to',date("M d, Y"));
		}
			
	}
/**
 * @method Public weeklyusage_report() Weekly usage Report for admin and owner only
 * @return HTML html page with usage details
 */
	function weeklyusage_report(){
		if(SES_TYPE > 2){
			$this->redirect(HTTP_ROOT);exit;
		}
		$easycasecls = ClassRegistry::init('Easycase');
		$companyusercls = ClassRegistry::init('CompanyUser');
		$projectcls = ClassRegistry::init('Project');
		$projectcls ->recursive=-1;;
		//$usernotificationcls = ClassRegistry::init('UserNotification');
		//$user_ids = $companyusercls->find('list',array('conditions'=>array('user_type < '=>3,'is_active'=>1,'user_id'),'fields'=>array('id','user_id')));
		//$user_lists = $usernotificationcls->find('list',array('conditions'=>array('user_id'=>SES_ID,'weekly_usage_alert'=>1),'fields'=>array('id','user_id')));
		$companyusercls->recursive=-1;
		$user_details = $companyusercls->find('all',array('joins'=>array(
			array('table'=>'users',
				'alias' => 'User',
				'type'=>'inner',				
				'conditions'=>array('CompanyUser.user_id = User.id','User.id'=>SES_ID,'CompanyUser.is_active'=>1,'CompanyUser.user_type < '=>3)),
			array('table'=>'companies',
				'alias' => 'Company',
				'type'=>'inner',				
				'conditions'=>array('CompanyUser.company_id=Company.id','CompanyUser.company_id'=>SES_COMP,'Company.is_active!=0'))),'fields'=>"Company.id,DATE(Company.created) AS dt_created,User.timezone_id,User.id,User.name,User.last_name,User.email,Company.name,Company.seo_url"));
		//$prv_date = date('Y-m-d',  strtotime('-1 week'));
		//$last_week_date = date('Y-m-d',  strtotime('-2 week'));
		$prv_date = date('Y-m-d',  strtotime('last monday'));
		$last_week_date = date('Y-m-d',  strtotime('last monday', strtotime($prv_date)));
		$this->set('last_week_date',$last_week_date);
		$this->set('prv_date',$prv_date);
		$days_diff = (strtotime(date('Y-m-d'))-strtotime($prv_date))/(24*60*60);
		$this->set('days_diff',$days_diff);
		for($i=0 ;$i<=$days_diff;$i++){
			$last7days[] = date('Y-m-d',  strtotime('-'.$i.' day'));
		}
			$this->set('last7days',$last7days);
			$timezone_details = '';
			$timezone_details = $tzone[$user_details['0']['User']['timezone_id']];
			$dateCurnt = $this->Tmzone->GetDateTime($user_details['0']['User']['timezone_id'],TZ_GMT,TZ_DST,'',GMT_DATETIME,"datetime"); 
			$this->set('dateCurnt',$dateCurnt);
			$dateCurnt1 = explode(' ',$dateCurnt);
			$tim = $dateCurnt1['0']; 
			//$min=date('i',strtotime($dateCurnt)); 
			//$hour=date('H',strtotime($dateCurnt));  
			//$day =  gmdate('N',strtotime($dateCurnt)); // Day number in numeric value
			$dt =  gmdate('j',strtotime($dateCurnt)); //Date in single numeric value
			$month =  gmdate('m',strtotime($dateCurnt)); 
			$lastDate = gmdate('Y-m-d');
			$frmdt = date("M d, Y",  (strtotime($dateCurnt)-(7*24*60*60)));
			$todt = date("M d, Y",  strtotime($dateCurnt));
			
			$userlogin = $companyusercls->query('SELECT COUNT(u.id) as notlogged,(SELECT COUNT(*) FROM company_users WHERE company_id='.$user_details['0']['Company']['id'].' AND is_active=1) AS tot FROM users u , company_users cu WHERE u.id=cu.user_id AND cu.is_active=1 AND cu.company_id='.$user_details['0']['Company']['id'].' AND DATE(u.dt_last_logout)<="'.$prv_date.'" ');
			$this->set('userlogin',$userlogin);
			$projectidlists = $projectcls->find("list",array('conditions'=>array('Project.company_id'=>SES_COMP,'isactive'=>1),'fields'=>array('Project.id')));
			$project_idcond=' ';
			if($projectidlists){
				$this->set('project_idlist',  implode(',', $projectidlists));
				$project_idcond=' FIND_IN_SET(Easycase.project_id ,"'. implode(',', $projectidlists).'") '; 
			}else{
				$this->set('project_idlist', '');
				$project_idcond =" !Easycase.project_id ";
			}
			//$caseAll = $easycasecls->query("SELECT COUNT(Cases.id) as cnt,SUM(Cases.hours)as hr_spent,GROUP_CONCAT(Cases.project_id) as project_ids, GROUP_CONCAT(Cases.id) as easycase_ids  ,Cases.istype, DATE(Cases.dt_created) as created_date FROM (SELECT * FROM easycases as Easycase WHERE Easycase.isactive=1 AND Easycase.project_id!=0 AND ".$project_idcond.") AS Cases WHERE DATE(Cases.dt_created )>='".$prv_date."' GROUP BY Cases.istype,DATE(Cases.dt_created)");
			$caseAll = $easycasecls->query("SELECT COUNT(Easycase.id) as cnt,SUM(Easycase.hours)as hr_spent,GROUP_CONCAT(Easycase.project_id) as project_ids, GROUP_CONCAT(Easycase.id) as easycase_ids  ,Easycase.istype, DATE(Easycase.dt_created) as created_date FROM easycases as Easycase WHERE Easycase.isactive=1 AND Easycase.project_id!=0 AND ".$project_idcond." AND DATE(Easycase.dt_created )>='".$prv_date."' GROUP BY Easycase.istype,DATE(Easycase.dt_created)");
			$this->set('caseAll',$caseAll);
			$project_idlist='';
			$easycase_idlist = '';
			$total_task_cr_current_week=0;$total_task_upd_current_week=0;$curr_wk_tot_hr_spent=0;
			foreach ($last7days as $key1=>$val1){
				$no_of_tasks=0;
				$no_of_tasks_upd=0;$total_hr_spent=0;
				foreach($caseAll AS $k=>$value){
					if(strtotime($value[0]['created_date'])==strtotime($val1)){
						if($value['Easycase']['istype']==1){
							$no_of_tasks = $value[0]['cnt'];
						}else{
							$no_of_tasks_upd = $value[0]['cnt'];;
						}
						$project_idlist .= $value[0]['project_ids'].',';
						$easycase_idlist .= $value[0]['easycase_ids'].',';
						//$curr_wk_tot_hr_spent += $value[0]['cnt']['hrs'];
					}
				}
				
				$total_task_cr_current_week +=$no_of_tasks;
				$total_task_upd_current_week +=$no_of_tasks_upd;
				//$curr_wk_tot_hr_spent += $total_hr_spent;
			}
				//Total task Created for the last week 
				$total_task_cr_prv_week = 0;$total_task_upd_prv_week = 0;$prv_wk_tot_hr_spent = 0;$prev_wk_proj_idlist='';$prev_wk_closed_tasks=0;$prev_wk_storage_usage=0;$prev_wk_ecase_idlist='';$prev_wk_ecase_idlists=array();$prev_wk_proj_idlists=array();
				$proj_cond =" ";
				$casefiles_cond =" ";
				if($project_idlist){
					$project_idlist =trim($project_idlist,',');
					$project_idlist=  explode(',', $project_idlist);
					$project_idlist = array_unique($project_idlist);
					$proj_cond .=" OR  FIND_IN_SET(Project.id,'".implode(',', $project_idlist)."')";
				}
				if($easycase_idlist){
					$easycase_idlist =trim($easycase_idlist,',');
					$easycase_idlist =  explode(',', $easycase_idlist);
					$easycase_idlist = array_unique($easycase_idlist);
					$casefiles_cond .=" AND  FIND_IN_SET(case_files.easycase_id,'".implode(',', $easycase_idlist)."')";
				}else{
					$casefiles_cond .=" AND !case_files.easycase_id ";
				}
				// Project details 
				$getProj = $projectcls->query("SELECT id,uniq_id,dt_created,name,user_id,project_type,short_name,isactive,(select count(easycases.id) as tot from easycases where easycases.project_id=Project.id and easycases.istype='1' and easycases.isactive='1' AND DATE(easycases.dt_created) >='".$prv_date."') as totalcase,(select count(easycases.id) as tot from easycases where easycases.project_id=Project.id and easycases.istype='1' AND easycases.isactive='1' AND easycases.legend='3'AND DATE(easycases.dt_created) >='".$prv_date."') as closedcase,(select ROUND(SUM(easycases.hours), 1) as hours from easycases where easycases.project_id=Project.id and easycases.istype='2' and easycases.isactive='1' AND DATE(easycases.dt_created) >='".$prv_date."' ) as totalhours,(SELECT SUM(case_files.file_size) AS file_size  FROM case_files   WHERE case_files.project_id=Project.id AND 1 ".$casefiles_cond.") AS storage_used FROM projects AS Project WHERE  Project.company_id=".$user_details['0']['Company']['id']." AND Project.short_name!='WCOS' AND (Project.dt_created >='".$prv_date."' ".$proj_cond.") ORDER BY Project.name ASC");
				$curr_wk_tot_closed_tasks=0;$curr_wk_tot_storage_usage=0;
				if($getProj){
					foreach($getProj AS $pkey=>$pval){
						$tot_close = $pval[0]['closedcase']?$pval[0]['closedcase']:0;
						$curr_wk_tot_closed_tasks +=$tot_close;
						$tot_users = $pval[0]['totusers']?$pval[0]['totusers']:0;
						$tot_hrs = $pval[0]['totalhours']?$pval[0]['totalhours']:'0.0';
						$curr_wk_tot_hr_spent += $tot_hrs;
						if($pval[0]['storage_used']){
							$tot_storage = number_format(($pval[0]['storage_used']/1024),2);
							$curr_wk_tot_storage_usage +=$tot_storage;
						}
					}
				}
				$this->set('getProj',$getProj);
								
				$progress_flag=1;
				if(strtotime($user_details['0']['0']['dt_created'])>=strtotime($prv_date)){
					$progress_flag=0;
				}
				$this->set('progress_flag',$progress_flag);
				$this->set('prev_wk_storage_usage',$prev_wk_storage_usage);
				$this->set('prv_wk_tot_hr_spent',$prv_wk_tot_hr_spent);
				$this->set('total_task_cr_prv_week',$total_task_cr_prv_week);
				$this->set('total_task_upd_prv_week',$total_task_upd_prv_week);
				$this->set('prev_wk_closed_tasks',$prev_wk_closed_tasks);
				
				$this->set('curr_wk_tot_hr_spent',$curr_wk_tot_hr_spent);
				$this->set('total_task_cr_current_week',$total_task_cr_current_week);
				$this->set('total_task_upd_current_week',$total_task_upd_current_week);
				$this->set('curr_wk_tot_storage_usage',$curr_wk_tot_storage_usage);
				$this->set('curr_wk_tot_closed_tasks',$curr_wk_tot_closed_tasks);
	}



/* BUG PIE CHART */


	function bug_pichart(){
		$this->layout='ajax';
		$this->loadModel('Easycase');	
		$cond = "";
		if(!empty($this->data['sdate'])){
			$dt = date('Y-m-d',strtotime($this->data['sdate']));
			$cond .= " AND DATE(actual_dt_created) >= '".$dt."' ";
		}
		if(!empty($this->data['edate'])){
			$dt = date('Y-m-d',strtotime($this->data['edate']));
			$cond .= " AND DATE(actual_dt_created) <= '".$dt."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND project_id = '".$this->data['pjid']."' ";
		}
		if(!empty($this->data['type_id'])){
			$cond .= " AND type_id = '".$this->data['type_id']."'";
		}
		if(isset($this->data['dtsearch'])){
			$this->_save_report(3);
		}
		$color_arr = array(1=>'#AE432E',2=>'#244F7A',3=>'#77AB13',4=>'#244F7A',5=>'#EF6807');
		$legend_arr = array(1=>'New',2=>'Opened',3=>'Closed',4=>'Start',5=>'Resolved');
		$sql = "SELECT legend, count(*) as cnt FROM easycases WHERE istype =1 AND project_id!=0 ".$cond." GROUP BY legend ORDER BY FIELD(legend,1,2,4,5,3)";
		$easycase = $this->Easycase->query($sql);
		$wip = 0;
		if(!empty($easycase)){
			foreach($easycase as $k=>$v){
				$cnt_array[] = $v[0]['cnt'];
				if($v['easycases']['legend'] == 2 || $v['easycases']['legend'] == 4){
					$wip = $wip + $v[0]['cnt'];
				}
			}
			$tot = !empty($cnt_array) ? array_sum($cnt_array) : 0 ;
			$i=0;
			$add=0;
			foreach($easycase as $k=>$v){
				if($v['easycases']['legend'] == 2 || $v['easycases']['legend'] == 4){
					if($add == 0){
						$piearr[$i]['name'] = 'In Progress';
						$piearr[$i]['y'] = ($wip/$tot)*100;
						$piearr[$i]['nos'] = $wip;
						$clr[$i] = $color_arr[$v['easycases']['legend']];
						$i++;	
						$add++;
					}
				}else{
					$piearr[$i]['name'] = $legend_arr[$v['easycases']['legend']];
					$piearr[$i]['nos'] = $v[0]['cnt'];
					$clr[$i] = $color_arr[$v['easycases']['legend']]; 
					$piearr[$i++]['y'] = ($v[0]['cnt']/$tot) * 100;
				}
			}
			$this->set('piearr',json_encode($piearr));
			$this->set('clrarr',json_encode($clr));
		}else{
			print "<div class='fl'><font color='red' size='2px'>No data for this date range & project.</font></div>";exit;
		}
	}

/* BUG STATISTICS */
	
	function bug_statistics(){
		$this->layout='ajax';
		$this->loadModel('Easycase');
		$prjcond = "";
		if(!empty($this->data['sdate'])){
			$dt = date('Y-m-d',strtotime($this->data['sdate']));
			$actcond .= " AND DATE(actual_dt_created) >= '".$dt."' ";
			$crtdcond .= " AND DATE(dt_created) >= '".$dt."' ";
		}
		if(!empty($this->data['edate'])){
			$dt = date('Y-m-d',strtotime($this->data['edate']));
			$actcond .= " AND DATE(actual_dt_created) <= '".$dt."' ";
			$crtdcond .= " AND DATE(dt_created) <= '".$dt."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND project_id = '".$this->data['pjid']."' ";
			$prjcond = " AND project_id = '".$this->data['pjid']."' ";
		}
		if(!empty($this->data['type_id'])){
			$cond .= " AND type_id = '".$this->data['type_id']."'";
		}
		$actcond = $actcond.$cond;
		$crtdcond = $crtdcond.$cond;
		
		$cntsql = "SELECT COUNT(*) as cnt FROM easycases WHERE istype =1 ".$actcond;
		$cnt = $this->Easycase->query($cntsql);
		$this->set('cnt',$cnt[0][0]['cnt']);
		$hrsql = "SELECT SUM(hours) as tot_hrs FROM easycases WHERE istype =2 ".$crtdcond;
		$tot_hrs = $this->Easycase->query($hrsql);
		$this->set('tot_hrs',$tot_hrs[0][0]['tot_hrs']);
		$sql = "SELECT actual_dt_created as postdate,legend,dt_created,case_no FROM easycases WHERE istype =1 AND project_id!=0 AND (legend != 1)".$actcond;
		$post_arr = $this->Easycase->query($sql);
		$resolved_cnt = 0;
		$closed_cnt = 0;
		$resolved = array();
		$closed = array();
		$resolved_diff = array();
		$closed_diff = array();
		if($cnt[0][0]['cnt'] != 0){
			if(!empty($post_arr)){
				foreach($post_arr as $k=>$v){
					if($v['easycases']['legend'] == 5){
						$resolved_diff[]=round(abs(strtotime($v['easycases']['dt_created'])-strtotime($v['easycases']['postdate']))/86400) + 1;
					}elseif($v['easycases']['legend'] == 3){
						$closed_diff[]=round(abs(strtotime($v['easycases']['dt_created'])-strtotime($v['easycases']['postdate']))/86400) + 1;
						$ressql = "SELECT max(dt_created) as createdt,legend FROM easycases WHERE istype =2 AND legend = 5 AND case_no = '".$v['easycases']['case_no']."'".$prjcond; 							
						$res_arr = $this->Easycase->query($ressql);
						if(!empty($res_arr[0][0]['createdt'])){
							$resolved_diff[]=round(abs(strtotime($res_arr[0][0]['createdt'])-strtotime($v['easycases']['postdate']))/86400) + 1;	
						}
						
					}else{
						$ressql = "SELECT max(dt_created) as createdt,legend FROM easycases WHERE istype =2 AND (legend = 5 OR legend = 3) AND case_no = '".$v['easycases']['case_no']."'".$prjcond;
						
						$res_arr = $this->Easycase->query($ressql);
						foreach($res_arr as $k=>$v1){
							if($v1['easycases']['legend'] == 3){
								$closed_diff[]=round(abs(strtotime($v1[0]['createdt'])-strtotime($v['easycases']['postdate']))/86400) + 1;	
							}
							if($v1['easycases']['legend'] == 5){
								$resolved_diff[]=round(abs(strtotime($v1[0]['createdt'])-strtotime($v['easycases']['postdate']))/86400) + 1;	
							}
						}
					}
				}
				$avg_resolved = (!empty($resolved_diff)) ? array_sum($resolved_diff)/count($resolved_diff) : 0;
				$avg_closed = (!empty($closed_diff)) ? array_sum($closed_diff)/count($closed_diff) : 0;
				$this->set('avg_resolved',$avg_resolved);
				$this->set('avg_closed',$avg_closed);
			}
			$resolved_cnt = count($resolved_diff);
			$closed_cnt = count($closed_diff);
			$this->set('resolved_cnt',$resolved_cnt);
			$this->set('closed_cnt',$closed_cnt);
		}
	}

/* BUG LINECHART */
	
	function bug_linechart(){
		$this->layout='ajax';
		$this->loadModel('Easycase');
		if(!empty($this->data['sdate'])){
			$dt = date('Y-m-d',strtotime($this->data['sdate']));
			$cond .= " AND DATE(actual_dt_created) >= '".$dt."' ";
		}
		if(!empty($this->data['edate'])){
			$dt = date('Y-m-d',strtotime($this->data['edate']));
			$cond .= " AND DATE(actual_dt_created) <= '".$dt."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND project_id = '".$this->data['pjid']."' ";
		}
		if(!empty($this->data['type_id'])){
			$cond .= " AND type_id = '".$this->data['type_id']."'";
		}
		$sql = "SELECT case_no,actual_dt_created,dt_created FROM easycases WHERE istype =1 AND project_id!=0 AND legend = 3 ".$cond." ORDER BY case_no ASC";
		$case_arr = $this->Easycase->query($sql);
		$case = array();
		if(!empty($case_arr)){
			foreach($case_arr as $k=>$v){
				$case[] = "#".$v['easycases']['case_no'];
				$closedays[] = round(abs(strtotime($v['easycases']['actual_dt_created'])-strtotime($v['easycases']['dt_created']))/86400) + 1;
			}
			$this->set('case',json_encode($case));
			$this->set('closedays',json_encode($closedays));
		}else{
			print "<font color='red' size='2px'>No data for this date range & project.</font>";exit;
		}
		
	}
/**
 * @method Public ajax_statistics()
 * @return JSON json value
 */
	function ajax_statistics(){
		$easycasecls = ClassRegistry::init('Easycase');
		$project_idlists = $this->data['project_idlists'];
		//$prv_date = date('Y-m-d',  strtotime('-1 week'));
		//$last_week_date = date('Y-m-d',  strtotime('-2 week'));
		$prv_date = date('Y-m-d',  strtotime('last monday'));
		$last_week_date = date('Y-m-d',  strtotime('last monday', strtotime($prv_date)));
		if($project_idlists){
			$project_idcond = " FIND_IN_SET(Easycase.project_id,'".$project_idlists."') ";
		}else{
			$project_idcond =" !Easycase.project_id ";
}   
		$total_task_cr_prv_week = 0;$total_task_upd_prv_week = 0;$prv_wk_tot_hr_spent = 0;$prev_wk_proj_idlist='';$prev_wk_closed_tasks=0;$prev_wk_storage_usage=0;$prev_wk_ecase_idlist='';$prev_wk_ecase_idlists=array();$prev_wk_proj_idlists=array();
		$lastweektask = $easycasecls->query("SELECT COUNT(Easycase.id) as cnt,SUM(Easycase.hours)as hr_spent,GROUP_CONCAT(Easycase.project_id) as project_ids, GROUP_CONCAT(Easycase.id) as easycase_ids  ,Easycase.istype, DATE(Easycase.dt_created) as created_date FROM easycases as Easycase WHERE Easycase.isactive=1 AND Easycase.project_id!=0 AND ".$project_idcond." AND (DATE(Easycase.dt_created )< '".$prv_date."' AND DATE(Easycase.dt_created )>= '".$last_week_date."' ) GROUP BY Easycase.istype");
		if($lastweektask){
			$prv_wk_tot_hr_spent = @$lastweektask[0][0]['hr_spent'] + @$lastweektask[1][0]['hr_spent'];
			if(@$lastweektask[0]['Easycase']['istype']==1){
				$total_task_cr_prv_week = @$lastweektask[0][0]['cnt'];
			}elseif(@$lastweektask[0]['Easycase']['istype']==2){
				$total_task_upd_prv_week = @$lastweektask[0][0]['cnt'];;
			}
			if(@$lastweektask[1]['Easycase']['istype']==1){
				$total_task_cr_prv_week = @$lastweektask[1][0]['cnt'];
			}elseif(@$lastweektask[1]['Easycase']['istype']==2){
				$total_task_upd_prv_week = @$lastweektask[1][0]['cnt'];
			}
			$prev_wk_proj_idlist = @$lastweektask[0][0]['project_ids'].",".@$lastweektask[1][0]['project_ids'];
			$prev_wk_ecase_idlist = @$lastweektask[0][0]['easycase_ids'].",".@$lastweektask[1][0]['easycase_ids'];
			if($prev_wk_proj_idlist){
				$prev_wk_proj_idlist = trim($prev_wk_proj_idlist,',');
				if(strstr($prev_wk_proj_idlist,',')){
					$prev_wk_proj_idlists = array_unique(explode(',', $prev_wk_proj_idlist));
				}else{
					$prev_wk_proj_idlists[] = $prev_wk_proj_idlist;
				}
				if($prev_wk_proj_idlist){
					//$prev_wk_proj_idlist = explode(',',$prev_wk_proj_idlist);
					$last_week_closed_cases =$easycasecls->query("SELECT count(easycases.id) as tot from easycases WHERE FIND_IN_SET(easycases.project_id,'".implode(',',$prev_wk_proj_idlists)."') and easycases.istype='1' AND easycases.isactive='1' AND easycases.legend='3'AND (DATE(easycases.dt_created) <'".$prv_date."' AND DATE(easycases.dt_created) >='".$last_week_date."')");
					if($last_week_closed_cases){
						$prev_wk_closed_tasks = $last_week_closed_cases[0][0]['tot']; 
					}
				}
			}
			// Calculating Prevous week storage usage	
			if($prev_wk_ecase_idlist){
				$prev_wk_ecase_idlist = trim($prev_wk_ecase_idlist,',');
				if(strstr($prev_wk_ecase_idlist,',')){
					$prev_wk_ecase_idlist=  explode(',', $prev_wk_ecase_idlist);
					$prev_wk_ecase_idlists= array_unique($prev_wk_ecase_idlist);
				}else{
					$prev_wk_ecase_idlists[] = $prev_wk_ecase_idlist;
				}
				if($prev_wk_ecase_idlist){
					$casefilecls =  ClassRegistry::init('CaseFile');
					$last_week_used_storage = $casefilecls->query("SELECT SUM(file_size) AS file_size  FROM case_files   WHERE FIND_IN_SET(easycase_id,'".  implode(',', $prev_wk_ecase_idlists)."')");
					if($last_week_used_storage){
						$prev_wk_storage_usage = round(($last_week_used_storage[0][0]['file_size']/1024),2); 
					}
				}
			}
		}
		$json_arr['prev_wk_closed_tasks']=$prev_wk_closed_tasks;
		$json_arr['prev_wk_storage_usage']=$prev_wk_storage_usage;
		$json_arr['prv_wk_tot_hr_spent']= $prv_wk_tot_hr_spent;
		$json_arr['total_task_cr_prv_week']=$total_task_cr_prv_week;
		$json_arr['total_task_upd_prv_week']=$total_task_upd_prv_week;
		echo json_encode($json_arr);exit;		
	}
	function bug_glide(){
		$this->layout="ajax";
		$before=date('Y-m-d',strtotime($this->data['sdate']));
		$to=date('Y-m-d',strtotime($this->data['edate']));
		$days = (strtotime($to) - strtotime($before)) / (60 * 60 * 24);
		$proj_id=$this->data['pjid'];
		
		$x=floor($days);
		if($x<7){
			$interval =1;
		}elseif($x>80){
			$interval = ceil($x/10);
		}else{
			$interval =7;
		}
		
		if(!empty($this->data['sdate'])){
			$dt = date('Y-m-d',strtotime($this->data['sdate']));
			$cond .= " AND DATE(dt_created) >= '".$dt."' ";
		}
		if(!empty($this->data['edate'])){
			$dt = date('Y-m-d',strtotime($this->data['edate']));
			$cond .= " AND DATE(dt_created) <= '".$dt."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND project_id = '".$this->data['pjid']."' ";
		}
		if(!empty($this->data['type_id'])){
			$cond .= " AND type_id = '".$this->data['type_id']."'";
		}
		
		
		
		
		$this->loadModel('Easycase');
		$actualdtarr = $this->Easycase->query("SELECT dt_created FROM easycases WHERE istype='1' AND  isactive='1' AND project_id!=0 ".$cond." ORDER BY dt_created ASC");

		$this->set('tinterval',$interval);
		$dt_arr=array(); $dts_arr=array();
		
		foreach($actualdtarr as $k=>$v){
			$dt=date('Y-m-d',strtotime(date("Y-m-d", strtotime($v['easycases']['dt_created']))));
			$dts=date('M d, Y',strtotime(date("Y-m-d H:i:s", strtotime($v['easycases']['dt_created']))));
			$times=explode(" ",GMT_DATETIME);
			array_push($dt_arr,$dt);
			array_push($dts_arr,$dts);
		}	
		/*for($i=0;$i<=$x;$i++){
			$m=" +".$i."day";
			$dt=date('Y-m-d',strtotime(date("Y-m-d", strtotime($before)) .$m));
			$dts=date('M d, Y',strtotime(date("Y-m-d H:i:s", strtotime($before)) .$m));
			$times=explode(" ",GMT_DATETIME);
			array_push($dt_arr,$dt);
			array_push($dts_arr,$dts);
		}*/
		
		$open_arr=array();
		$res_arr=array();
		$s="";$r="";	
		foreach($dt_arr as $key =>$date){
		
			$resolved_bug = $this->Easycase->query("SELECT count(type_id) AS tid ,DATE(Easycase.actual_dt_created) AS cdate,COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend='5' AND Easycase.type_id='1' AND Easycase.project_id!=0 AND Easycase.project_id='".$proj_id."' AND (DATE(Easycase.dt_created) <= '".$date."')");
			$resolvedCount=$resolved_bug['0']['0']['count'];
			
		
			$opened_bug = $this->Easycase->query("SELECT ROUND(type_id) AS tid ,DATE(Easycase.actual_dt_created) AS cdate,COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend !='5' AND Easycase.legend !='3' AND Easycase.type_id='1' AND Easycase.project_id!=0 AND Easycase.project_id='".$proj_id."' AND (DATE(Easycase.dt_created) <= '".$date."')");
			$openedCount=$opened_bug['0']['0']['count'];
			
			
			
			array_push($res_arr,$resolvedCount+$openedCount);
			array_push($open_arr,$openedCount);
		}
		
		if(!empty($res_arr) || !empty($open_arr)){		
			$resolved = implode(",",$res_arr);
			$opened = implode(",",$open_arr);
		
			$this->set('dt_arr',json_encode($dts_arr));
	
			$carr =array(array('name'=>'Resolved Bug','data'=>'['.$resolved.']'),array('name'=>'Opened Bug','data'=>'['.$opened.']'));
		
			for($i=5;$i<=100;$i++){
				$yarr[]=(int)$i;
			}
			$this->set('yarr',json_encode($yarr));	
			$this->set('carr',json_encode($carr));
		}else{
			print "<font color='red' size='2px'>No data for this date range & project.</font>";exit;
		}
	}
	
	
	
	function _save_report($rpt_type){
		$this->loadModel('SaveReport');
		$rptdata = $this->SaveReport->find('all',array('conditions'=>array('user_id'=>SES_ID)));
		if(!empty($rptdata)){
			$saverpt['SaveReport']['id'] = $rptdata[0]['SaveReport']['id'];
		}
		$fdt = date('Y-m-d',strtotime($this->data['sdate']));
		$tdt = date('Y-m-d',strtotime($this->data['edate']));
		$saverpt['SaveReport']['frm_dt'] = $fdt;
		$saverpt['SaveReport']['to_dt'] =  $tdt;
		$saverpt['SaveReport']['user_id'] = SES_ID;
		//$saverpt['SaveReport']['rpt_type'] = $rpt_type;
		$saverpt['SaveReport']['created'] = gmdate('Y-m-d H:i:s');
		$saverpt['SaveReport']['ip'] = $_SERVER['REMOTE_ADDR'];
		$this->SaveReport->save($saverpt);
	}
	function hours_report(){
		if(isset($this->params['pass']['0']) && !empty($this->params['pass']['0'])){
			if($this->params['pass']['0'] == 'ajax')
				$this->layout = 'ajax';
			$prj = $this->params['pass']['0'] != 'ajax' ? $this->params['pass']['0'] : $this->params['pass']['1'];
			$this->loadModel('Project');
			$projarr=$this->Project->query("SELECT id,name FROM projects WHERE uniq_id='".$prj."' AND company_id='".SES_COMP."'");
			$proj_id=$projarr['0']['projects']['id'];
			$this->set('pjid',$proj_id);
			$this->set('pjname',$projarr['0']['projects']['name']);
			$type_id=0;
			$this->set('proj_uniq',$prj);
			
			$this->Project->query("UPDATE project_users SET dt_visited='".GMT_DATETIME."' WHERE user_id=".SES_ID." and project_id='".$proj_id."' and company_id='".SES_COMP."'");
		}
		
		$this->loadModel('ProjectUser');
		$proj_all_cond = array(
		'recursive'=>'1',
		'conditions' => array('ProjectUser.user_id'=>SES_ID,'ProjectUser.company_id'=>SES_COMP,'ProjectUser.project_id !='=>0,'Project.isactive'=>1),
		'fields' => array('Project.id','Project.uniq_id'),
		'order' => array('ProjectUser.dt_visited DESC')
		);
		$this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
		$projAll = $this->ProjectUser->find('list', $proj_all_cond);
		$this->set('projAll',$projAll);
		
		if(!isset($this->params['pass']['0'])){
			foreach($projAll as $pid=>$puid){
				$this->set('pjid',$pid);
				$this->set('proj_uniq',$puid);
				break;
			}
		}
		if(isset($this->params['pass']['0']) && !empty($this->params['pass']['0'])){
			if(!in_array($prj,$projAll)){
				$this->Session->write("ERROR","Unauthorized URL");
				$this->redirect(HTTP_ROOT."task-report");
			}
		}
	
		$this->loadModel('SaveReport');
		$rptdata = $this->SaveReport->find('all',array('conditions'=>array('user_id'=>SES_ID)));
		if(!empty($rptdata)){
			$this->set('frm',date('M d, Y',strtotime($rptdata[0]['SaveReport']['frm_dt'])));
			$this->set('to',date("M d, Y",strtotime($rptdata[0]['SaveReport']['to_dt'])));
			$before=$this->Format->chgdate(date('M d, Y',strtotime($rptdata[0]['SaveReport']['frm_dt'])));
			$to=$this->Format->chgdate(date('M d, Y',strtotime($rptdata[0]['SaveReport']['to_dt'])));
			$days = (strtotime($to) - strtotime($before)) / (60 * 60 * 24);
		}else{
	
				
			$timezone_offset = TZ_GMT;
			$cur_time = date('Y-m-d H:i:s',(strtotime(GMT_DATETIME) + ($timezone_offset*60*60)));
			$before = date('Y-m-d H:i:s',strtotime($cur_time."-7 day"));
			$days = (strtotime(date("Y-m-d H:i:s")) - strtotime($before)) / (60 * 60 * 24) + 1;
			$this->set('frm',date('M d, Y',strtotime($cur_time."-7 day")));
			$this->set('to',date("M d, Y"));
		}
	}
	
	function hours_piechart(){
		$this->layout='ajax';
		$this->loadModel('Easycase');	
		$cond = "";
		if(!empty($this->data['sdate'])){
			$dt = date('Y-m-d',strtotime($this->data['sdate']));
			$cond .= " AND DATE(actual_dt_created) >= '".$dt."' ";
		}
		if(!empty($this->data['edate'])){
			$dt = date('Y-m-d',strtotime($this->data['edate']));
			$cond .= " AND DATE(actual_dt_created) <= '".$dt."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND project_id = '".$this->data['pjid']."' ";
		}
		if(!empty($this->data['type_id'])){
			$cond .= " AND type_id = '".$this->data['type_id']."'";
		}
		$this->loadModel('Type');
		$type_arr = $this->Type->find('list',array('fields'=>array('id','name')));
		if(isset($this->data['dtsearch'])){
			$this->_save_report(2);
		}
		
		$sql = "SELECT type_id, SUM(hours) as tot_hours FROM easycases WHERE hours != '0.0' AND project_id!=0 AND istype != 1 ".$cond." GROUP BY type_id";
		$easycase = $this->Easycase->query($sql);

		if(!empty($easycase)){
			foreach($easycase as $k=>$v){
				$cnt_array[] = $v[0]['tot_hours'];
			}
			$tot = !empty($cnt_array) ? array_sum($cnt_array) : 0 ;
			$i=0;
			foreach($easycase as $k=>$v){
				$piearr[$i]['name'] = $type_arr[$v['easycases']['type_id']];
				$piearr[$i]['hours'] = $v[0]['tot_hours'];
				$piearr[$i++]['y'] = ($v[0]['tot_hours']/$tot) * 100;
			}
			
			$this->set('piearr',json_encode($piearr));
		}else{
			print "<div class='fl'><font color='red' size='2px'>No data for this date range & project.</font></div>";exit;
		}
	}
	
	function hours_linechart(){
		$this->layout='ajax';
		$this->loadModel('Easycase');	
		
		
		$before=date('Y-m-d',strtotime($this->data['sdate']));
		$to=date('Y-m-d',strtotime($this->data['edate']));
		$days = (strtotime($to) - strtotime($before)) / (60 * 60 * 24);
		$proj_id=$this->data['pjid'];		
		$x=floor($days);
		if($x<7){
			$interval =1;
		}elseif($x>80){
			$interval = ceil($x/10);
		}else{
			$interval =7;
		}
		$this->set('tinterval',$interval);
		$view = new View($this);
	    $tz = $view->loadHelper('Tmzone');
		$dt_arr=array(); $dts_arr=array();
		
		for($i=0;$i<=$x;$i++){
			$m=" +".$i."day";
			$dt=date('Y-m-d',strtotime(date("Y-m-d", strtotime($before)) .$m));
			$dts=date('M d, Y',strtotime(date("Y-m-d H:i:s", strtotime($before)) .$m));			
			$times=explode(" ",GMT_DATETIME);
			array_push($dt_arr,$dt);
			array_push($dts_arr,$dts);
		}
		$this->set('dt_arr',json_encode($dts_arr));


		$cond = "";
		if(!empty($this->data['sdate'])){
			$dtt = date('Y-m-d',strtotime($this->data['sdate']));
			$cond .= " AND DATE(actual_dt_created) >= '".$dt_arr[0]."' ";
		}
		if(!empty($this->data['edate'])){
			$dtt = date('Y-m-d',strtotime($this->data['edate']));
			$cond .= " AND DATE(actual_dt_created) <= '".$dt_arr[$x]."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND project_id = '".$proj_id."' ";
		}
		if(!empty($this->data['type_id'])){
			$cond .= " AND type_id = '".$this->data['type_id']."'";
		}

		if(isset($this->data['dtsearch'])){
			$this->_save_report(2);
		}		
		
		$sql = "SELECT Users.name as devname ,Easycases.case_no,Easycases.project_id,Easycases.user_id,Easycases.hours,Easycases.actual_dt_created AS cdate FROM easycases as Easycases,users as Users WHERE Users.id = Easycases.user_id AND Easycases.project_id!=0 AND Easycases.reply_type=0 ".$cond."";
		$easycase = $this->Easycase->query($sql);
		if(!empty($easycase)){
			foreach($easycase as $k=>$v){
				$name[] = $v['Users']['devname'];
				$cdts = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$v['Easycases']['cdate'],"date");
				$reportArr[$cdts]['name'] = $v['Users']['devname'];
				$reportArr[$cdts][$v['Users']['devname']]['hour'][] = $v['Easycases']['hours'];
			}
			foreach($dt_arr as $key =>$date){
				foreach($name as $nm){				
					if(array_key_exists($date,$reportArr)){	
						if(!empty($reportArr[$date][$nm]['hour'])){
							$hrspent = array_sum($reportArr[$date][$nm]['hour']);
						}else{
							$hrspent = 0;
						}						
					}else{
						$hrspent = 0;

					}
					$hourspent[$date][$nm] = (float)$hrspent;
				}
			}
			$uname = '';
			foreach($hourspent as $key => $value){
				foreach($value as $nm => $hr){
					$userArr[$nm][] = $hr;
				}
			}
			foreach($userArr as $knm => $vhr){
				$carr[] =array('name'=>$knm,'data'=>$vhr);
			}

			$this->set('carr',json_encode($carr));		
			
		}else{
			print "<div class='fl'><font color='red' size='2px'>No data for this date range & project.</font></div>";exit;
		}
	}
	function hours_gridview(){
		$this->layout='ajax';
		$this->loadModel('Easycase');	
		$cond = "";
		if(!empty($this->data['sdate'])){
			$dt = date('Y-m-d',strtotime($this->data['sdate']));
			$cond .= " AND DATE(e.actual_dt_created) >= '".$dt."' ";
		}
		if(!empty($this->data['edate'])){
			$dt = date('Y-m-d',strtotime($this->data['edate']));
			$cond .= " AND DATE(e.actual_dt_created) <= '".$dt."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND e.project_id = '".$this->data['pjid']."' ";
		}
		
		$sql = "SELECT u.name as devname ,e.user_id, SUM(e.hours) as tot_hours,COUNT(*) as replies_no FROM easycases as e,users as u WHERE u.id = e.user_id AND e.project_id!=0 ".$cond." GROUP BY e.user_id ORDER BY tot_hours DESC";
		$easycase = $this->Easycase->query($sql);
		if(!empty($easycase)){
			$this->set('easycases',$easycase);	
		
			$ressql = "SELECT COUNT(*) as resolved_no,e.user_id FROM easycases as e WHERE e.istype != 1 AND e.legend = 5 ".$cond." GROUP BY e.user_id";
			$rescnt = $this->Easycase->query($ressql);
			foreach($rescnt as $k=>$v){
				$resarr[$v['e']['user_id']] = $v[0]['resolved_no'];
			}
		
			$this->set('resarr',$resarr);
		}else{
			print "<div class='fl'><font color='red' size='2px'>No data for this date range & project.</font></div>";exit;
		}
	}
	
	
	/* Task Pie Chart */
	
	function tasks_pichart(){
		$this->layout='ajax';
		$this->loadModel('Easycase');	
		$cond = "";
		if(!empty($this->data['sdate'])){
			$dt = date('Y-m-d',strtotime($this->data['sdate']));
			$cond .= " AND DATE(actual_dt_created) >= '".$dt."' ";
		}
		if(!empty($this->data['edate'])){
			$dt = date('Y-m-d',strtotime($this->data['edate']));
			$cond .= " AND DATE(actual_dt_created) <= '".$dt."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND project_id = '".$this->data['pjid']."' ";
		}
		if(!empty($this->data['type_id'])){
			$cond .= " AND type_id = '".$this->data['type_id']."'";
		}
		$this->_save_report(1);
		$sql = "SELECT type_id, count(*) as cnt FROM easycases WHERE istype =1 AND project_id!=0 ".$cond." GROUP BY type_id";
		$easycase = $this->Easycase->query($sql);
		if(!empty($easycase)){
			$this->loadModel('Type');
			$type_arr = $this->Type->find('list',array('fields'=>array('id','name')));
			foreach($easycase as $k=>$v){
				$cnt_array[] = $v[0]['cnt'];
			}
			$tot = !empty($cnt_array) ? array_sum($cnt_array) : 0 ;
			$i=0;
			foreach($easycase as $k=>$v){
				$piearr[$i]['name'] = $type_arr[$v['easycases']['type_id']];
				$piearr[$i]['tasks'] = $v[0]['cnt'];
				$piearr[$i++]['y'] = ($v[0]['cnt']/$tot) * 100;
			}
			
			$this->set('piearr',json_encode($piearr));
		}else{
			print "<div class='fl'><font color='red' size='2px'>No data for this date range & project.</font></div>";exit;
		}
	}
	
	function tasks_statistics(){
		$this->layout='ajax';
		$this->loadModel('Easycase');
		$prjcond = "";
		if(!empty($this->data['sdate'])){
			$dt = date('Y-m-d',strtotime($this->data['sdate']));
			$actcond .= " AND DATE(actual_dt_created) >= '".$dt."' ";
			$crtdcond .= " AND DATE(dt_created) >= '".$dt."' ";
			
		}
		if(!empty($this->data['edate'])){
			$dt = date('Y-m-d',strtotime($this->data['edate']));
			$actcond .= " AND DATE(actual_dt_created) <= '".$dt."' ";
			$crtdcond .= " AND DATE(dt_created) <= '".$dt."' ";
		}
		if(!empty($this->data['pjid'])){
			$cond .= " AND project_id = '".$this->data['pjid']."' ";
			$prjcond = " AND project_id = '".$this->data['pjid']."' ";
		}
		if(!empty($this->data['type_id'])){
			$cond .= " AND type_id = '".$this->data['type_id']."'";
		}
		$actcond = $actcond.$cond;
		$crtdcond = $crtdcond.$cond;
		$cntsql = "SELECT COUNT(*) as cnt FROM easycases WHERE istype =1 ".$actcond;
		$cnt = $this->Easycase->query($cntsql);
		$this->set('cnt',$cnt[0][0]['cnt']);
		$hrsql = "SELECT SUM(hours) as tot_hrs FROM easycases WHERE istype =2 ".$crtdcond;
		$tot_hrs = $this->Easycase->query($hrsql);
		$this->set('tot_hrs',$tot_hrs[0][0]['tot_hrs']);
		$sql = "SELECT actual_dt_created as postdate,legend,dt_created,case_no FROM easycases WHERE istype =1 AND project_id!=0 AND (legend != 1) ".$actcond;
		$post_arr = $this->Easycase->query($sql);
		$resolved_cnt = 0;
		$closed_cnt = 0;
		$resolved = array();
		$closed = array();
		$resolved_diff = array();
		$closed_diff = array();
		if($cnt[0][0]['cnt'] != 0){
			if(!empty($post_arr)){
				foreach($post_arr as $k=>$v){
					if($v['easycases']['legend'] == 5){
						$resolved_diff[]=round(abs(strtotime($v['easycases']['dt_created'])-strtotime($v['easycases']['postdate']))/86400) + 1;
					}elseif($v['easycases']['legend'] == 3){
						$closed_diff[]=round(abs(strtotime($v['easycases']['dt_created'])-strtotime($v['easycases']['postdate']))/86400) + 1;
						$ressql = "SELECT max(dt_created) as createdt,legend FROM easycases WHERE istype =2 AND legend = 5 AND case_no = '".$v['easycases']['case_no']."'".$prjcond;							
						$res_arr = $this->Easycase->query($ressql);
						if(!empty($res_arr[0][0]['createdt'])){
							$resolved_diff[]=round(abs(strtotime($res_arr[0][0]['createdt'])-strtotime($v['easycases']['postdate']))/86400) + 1;	
						}
						
					}else{
						$ressql = "SELECT max(dt_created) as createdt,legend FROM easycases WHERE istype =2 AND (legend = 5 OR legend = 3) AND case_no = '".$v['easycases']['case_no']."'".$prjcond;
						$res_arr = $this->Easycase->query($ressql);
						foreach($res_arr as $k=>$v1){
							if($v1['easycases']['legend'] == 3){
								$closed_diff[]=round(abs(strtotime($v1[0]['createdt'])-strtotime($v['easycases']['postdate']))/86400) + 1;	
							}
							if($v1['easycases']['legend'] == 5){
								$resolved_diff[]=round(abs(strtotime($v1[0]['createdt'])-strtotime($v['easycases']['postdate']))/86400) + 1;	
							}
						}
					}
				}
				$avg_resolved = (!empty($resolved_diff)) ? array_sum($resolved_diff)/count($resolved_diff) : 0;
				$avg_closed = (!empty($closed_diff)) ? array_sum($closed_diff)/count($closed_diff) : 0;
				$this->set('avg_resolved',$avg_resolved);
				$this->set('avg_closed',$avg_closed);
			}
			$resolved_cnt = count($resolved_diff);
			$closed_cnt = count($closed_diff);
			$this->set('resolved_cnt',$resolved_cnt);
			$this->set('closed_cnt',$closed_cnt);
		}
	}
	
	function tasks_trend(){
		$this->layout="ajax";
		$before=date('Y-m-d',strtotime($this->data['sdate']));
		$to=date('Y-m-d',strtotime($this->data['edate']));
		$days = (strtotime($to) - strtotime($before)) / (60 * 60 * 24);
		$proj_id=$this->data['pjid'];		
		$x=floor($days);
		if($x<7){
			$interval =1;
		}elseif($x>80){
			$interval = ceil($x/10);
		}else{
			$interval =7;
		}
		$this->set('tinterval',$interval);
		$dt_arr=array(); $dts_arr=array();
		for($i=0;$i<=$x;$i++){
			$m=" +".$i."day";
			$dt=date('Y-m-d',strtotime(date("Y-m-d", strtotime($before)) .$m));
			$dts=date('M d, Y',strtotime(date("Y-m-d H:i:s", strtotime($before)) .$m));
			$times=explode(" ",GMT_DATETIME);
			//$dt=$dt." ".$times['1'];
			array_push($dt_arr,$dt);
			array_push($dts_arr,$dts);
		}
		
		$this->loadModel('Easycase');
		$open_arr=array();
		$res_arr=array();
		$s="";$r="";
		$new_report = $this->Easycase->query("SELECT DATE(Easycase.actual_dt_created) AS cdate,COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend='1' AND  Easycase.project_id!=0 AND Easycase.project_id='".$proj_id."' AND ( DATE(Easycase.actual_dt_created) >= '".$dt_arr[0]."' AND DATE(Easycase.actual_dt_created) <= '".$dt_arr[$x]."') GROUP BY DATE(Easycase.actual_dt_created) ");
		$new_report = $this->convertinto_array($new_report);
		
		$wip_report = $this->Easycase->query("SELECT DATE(Easycase.actual_dt_created) AS cdate,COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND (Easycase.legend='2' || Easycase.legend='4') AND Easycase.project_id!=0 AND Easycase.project_id='".$proj_id."' AND ( DATE(Easycase.actual_dt_created) >= '".$dt_arr[0]."' AND DATE(Easycase.actual_dt_created) <= '".$dt_arr[$x]."') GROUP BY DATE(Easycase.actual_dt_created) ");
		$wip_report = $this->convertinto_array($wip_report);
		
		
		$resolved_report = $this->Easycase->query("SELECT ROUND(type_id) AS tid ,DATE(Easycase.actual_dt_created) AS cdate,COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend='5'  AND Easycase.project_id!=0 AND Easycase.project_id='".$proj_id."' AND ( DATE(Easycase.actual_dt_created) >= '".$dt_arr[0]."' AND DATE(Easycase.actual_dt_created) <= '".$dt_arr[$x]."') GROUP BY Easycase.type_id,DATE(Easycase.actual_dt_created) ");
		
		$resolved_report  = $this->convertinto_array($resolved_report,1);
		global $resolved_type_arr;
		$res_type_arr = $resolved_type_arr;
		$closed_report = $this->Easycase->query("SELECT ROUND(type_id) AS tid,DATE(Easycase.actual_dt_created) AS cdate,COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend='3' AND Easycase.project_id!=0 AND Easycase.project_id='".$proj_id."' AND ( DATE(Easycase.actual_dt_created) >= '".$dt_arr[0]."' AND DATE(Easycase.actual_dt_created) <= '".$dt_arr[$x]."') GROUP BY Easycase.type_id,DATE(Easycase.actual_dt_created) ");
		$closed_report  = $this->convertinto_array($closed_report,1);
		$cls_type_arr = $resolved_type_arr;
		
		foreach($dt_arr as $key =>$date){
			if(array_key_exists($date,$new_report)){
				$bugs[]=(int)$new_report[$date];
			}else{
				$bugs[] = (int)0;
			}
			if(array_key_exists($date,$wip_report)){
				$enh[]=(int)$wip_report[$date];
			}else{
				$enh[] = (int)0;
			}
			if(array_key_exists($date,$dev_report)){
				$dev[]=(int)$dev_report[$date];
			}else{
				$dev[] = (int)0;
			}
			if(array_key_exists($date,$resolved_report)){
				$resolved[]=(int)$resolved_report[$date];
			}else{
				$resolved[] = (int)0;
			}
			if(array_key_exists($date,$closed_report)){
				$closed[]=(int)$closed_report[$date];
			}else{
				$closed[] = (int)0;
			}
		}
			$typ = array(1=>'New','2'=>'In Progress',5=>'Resolved',3=>'Closed');
			array_unshift($typ,'All');
			$this->set('typ',$typ);
			$this->set('type',$type_id);
		
			$v['0']['name']="Opened ".$typ[$type_id]."s";
			$v['0']['data']="[".substr($s,0,strlen($s)-1)."]";
			$v['0']['color']="#AE432E";
			$v['1']['name']="Resolved ".$typ[$type_id]."s";
			$v['1']['data']="[".substr($r,0,strlen($r)-1)."]";
			$v['1']['color']="#77AB13";
			$op = $v;
			$this->set('dt_arr',json_encode($dts_arr));
				
					
			if(!$type_id){
				$carr =array(array('name'=>'New','color'=>'#F90F0F', 'connectNulls'=> 'true','data'=>$bugs),array('name'=>'In Progress','color'=>'#0066FF','connectNulls'=> 'true','data'=>$dev),array('name'=>'Resolved','color'=>'#DF6625','connectNulls'=> 'true','data'=>$resolved),array('name'=>'Closed','color'=>'#77AB13','connectNulls'=> 'true','data'=>$closed));
			}elseif($type_id==1){
				$carr =array(array('name'=>'New','color'=>'#F90F0F', 'connectNulls'=> 'true','data'=>$bugs));
			}elseif($type_id==2){
				$carr =array(array('name'=>'In Progress','d'=>'M 4 7 L 12 7 12 15 4 15 Z','color'=>'#0066FF','connectNulls'=> 'true','data'=>$dev));
			}elseif($type_id==5){
				$carr =array(array('name'=>'Closed','color'=>'#77AB13','connectNulls'=> 'true','data'=>$closed));
			}elseif($type_id==4){
				$carr =array(array('name'=>'Resolved','color'=>'#DF6625','connectNulls'=> 'true','data'=>$resolved));
			}
			for($i=5;$i<=100;$i++){
				$yarr[]=(int)$i;
			}
			if(!isset($invalid)){
				$this->set('yarr',json_encode($yarr));	
				$this->set('carr',json_encode($carr));	
			}
			print_r($this->set);
	}   

}
?>
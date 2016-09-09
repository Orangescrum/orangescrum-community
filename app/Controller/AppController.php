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
App::uses('Controller', 'Controller');
class AppController extends Controller {
    public $helpers = array('Html', 'Form', 'Text', 'Format', 'Tmzone', 'Datetime','Cache','Casequery');
    public $components = array('Auth','Session','Email', 'Cookie','Image','Format','Security');
    public $paginate = array();

    function temp_logout() {
        $this->Session->write('Auth.User.id', '');

        unset($_SESSION['GOOGLE_USER_INFO']);
        unset($_SESSION['user_last_login']);

        setcookie('USER_UNIQ', '', -1, '/', DOMAIN_COOKIE, false, false);
        setcookie('USERTYP', '', -1, '/', DOMAIN_COOKIE, false, false);
        setcookie('USERTZ', '', -1, '/', DOMAIN_COOKIE, false, false);
        setcookie('REMEMBER', '', -1, '/', DOMAIN_COOKIE, false, false);

//        setcookie('SES_COMP', '', -1, '/', DOMAIN_COOKIE, false, false);
//        setcookie('SES_TYPE', '', -1, '/', DOMAIN_COOKIE, false, false);
        setcookie('SES_TZ', '', -1, '/', DOMAIN_COOKIE, false, false);

        setcookie('is_osadmin', '', -1, '/', DOMAIN_COOKIE, false, false);
        setcookie('REF_URL', '', -1, '/', DOMAIN_COOKIE, false, false);

        $cookie = array();
        $this->Cookie->write('Auth.User', $cookie, '-2 weeks');
        /* if(SES_ID && !$qsrt) {
          $this->User->id = SES_ID;
          $this->User->saveField('dt_last_logout', GMT_DATETIME);
          if($this->isiPad() && HTTP_ROOT!=HTTP_APP){
          $retval = $this->Auth->logout();
          $this->redirect(HTTP_APP.'users/logout');exit;
          }
          } */
        $retval = $this->Auth->logout();
        $this->redirect(HTTP_APP . 'users/login');
        exit;
    }

    public function beforeFilter() {
		
		$this->Security->validatePost=false;
        $this->Security->csrfCheck=false;
        $this->Security->csrfUseOnce=false;
		
        /***Image cropping not require to enter function***/
        if($this->action=='image_thumb')return;
        
        parent::beforeFilter();
		
		Configure::write('default_action','dashboard');
		
		$this->loadModel('User');
		
        foreach($_GET as $key=>$value) {
            $_GET[$key] = strip_tags($value);
        }

        //DEFAULT_PAGE cookie will only work only if Configure::read('default_action') is mydashboard
        if(Configure::read('default_action') == 'mydashboard' && isset($_COOKIE['DEFAULT_PAGE']) && in_array($_COOKIE['DEFAULT_PAGE'],array('dashboard','mydashboard'))) {
            Configure::write('default_page',$_COOKIE['DEFAULT_PAGE']);
        } else {
            Configure::write('default_page',Configure::read('default_action'));
        }

        if(!defined('IS_ERRROR')) {
            if($this->name == 'CakeError') {
                define('IS_ERRROR', 1);
            }
            else {
                define('IS_ERRROR', 0);
            }
        }
        if($this->params['controller'] == 'easycases' && $this->params['action'] == 'dashboard' && isset($this->params->query['case'])) {
            $this->set('caseForRecent',$this->params->query['case']);
        }else {
            $this->set('caseForRecent','');
        }
        
        if(!defined('CONTROLLER')) {
            define('CONTROLLER', $this->params['controller']);
        }
        if(!defined('PAGE_NAME')) {
            define('PAGE_NAME', $this->action);
        }
        if(!defined('STATIC_PAGE')) {
            if(isset($this->params['pass']['0'])) {
                define('STATIC_PAGE', $this->params['pass']['0']);
            }
            else {
                define('STATIC_PAGE', "login");
            }
        }
        $pagesName = "";
        if(isset($this->params['pass']['0'])) {
            $pagesName = $this->params['pass']['0'];
        }
		
		$ajaxPageArray = array('project_menu','remember_filters','case_project','session_maintain','add_user','add_project','case_details','archive_case','archive_file','ajaxpostcase','check_email_reg','check_short_name_reg','check_url_reg','update_notification','feedback','check_short_name','new_user','notification','caseview_remove','project_all','jquery_multi_autocomplete_data','search_project_menu','project_listing','assign_prj','contactnow','ajax_totalcase','case_list','file_list','move_list','case_remove','move_file','file_remove','comment_edit','comment','fileremove','fileupload','case_update','case_files','case_project','case_reply','case_quick','case_message','update_assignto','exportcase','assign_userall','image_thumb','to_dos','recent_projects','recent_activities','recent_milestones','statistics','usage_details','task_progress','leader_board','post_support_inner');
		if(isset($_SERVER['HTTP_REFERER'])) {
			$this->set('referer',$_SERVER['HTTP_REFERER']);
		}
		
        $curProjId = "";
        $projUniq = "";
		
        $Company = ClassRegistry::init('Company');
        $CompanyUser = ClassRegistry::init('CompanyUser');

        if(isset($_COOKIE['USER_UNIQ']) && isset($_COOKIE['USERTYP']) && isset($_COOKIE['USERTZ'])) {
            setcookie('USER_UNIQ',$_COOKIE['USER_UNIQ'],COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
            setcookie('USERTYP',$_COOKIE['USERTYP'],COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
            setcookie('USERTZ',$_COOKIE['USERTZ'],COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
            setcookie('USERSUB_TYPE',$_COOKIE['USERSUB_TYPE'],COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
			
            $uid = NULL; //var for user id, which will be retrieve form user unique id
            if($this->Auth->user('id') && 0) {
                $uid = $this->Auth->user('id');
            } else {
                $User = ClassRegistry::init('User');
                $User->unbindModel(array('hasAndBelongsToMany' => array('Project')));
                $userLogRec = $User->find('first', array(
                        'conditions' => array(
                                'User.uniq_id' => $_COOKIE['USER_UNIQ']
                        ),
                        'fields' => 'User.id'
                        )
                );

                if($userLogRec && count($userLogRec)) {
                    $uid = $userLogRec['User']['id'];
                }
            }

            if(!$uid) {
                setcookie('USER_UNIQ','',-1,'/',DOMAIN_COOKIE,false,false);
                $this->redirect(HTTP_ROOT."users/logout");
                die;
            }

            $this->Session->write('Auth.User.id',$uid);
            $this->Session->write('Auth.User.uniq_id',$_COOKIE['USER_UNIQ']);
            $this->Session->write('Auth.User.istype',$_COOKIE['USERTYP']);
            $this->Session->write('Auth.User.timezone_id',$_COOKIE['USERTZ']);
            $this->Session->write('Auth.User.usersub_type',$_COOKIE['USERSUB_TYPE']);
            $this->Session->write('Auth.User.is_moderator',$_COOKIE['IS_MODERATOR']);

        } else {
            if(!$this->isiPad()) {
                $this->Session->write('Auth.User.id','');
            }
        }
		
        if($this->Auth->User("id")) {
            if($this->isiPad()) {
                setcookie('USER_UNIQ',$this->Auth->user('uniq_id'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
                setcookie('USERTYP',$this->Auth->user('istype'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
                setcookie('USERTZ',$this->Auth->user('timezone_id'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
                setcookie('USERSUB_TYPE',$this->Auth->user('usersub_type'),$cookieTime,'/',DOMAIN_COOKIE,false,false);
            }

            /* below code is for CSRF issue fixing: */
            if (!isset($_SESSION['CSRFTOKEN'])) {
                $tokn = $this->Format->genRandomStringCustom(25);
                $_SESSION['CSRFTOKEN'] = $tokn;
            }
            /* end */
            
            /* below code is for log out users if password reset start: */
            $t_uid = $this->Auth->User('id');
            if (!$_COOKIE['user_uniq_agent']) {
                $this->User->keepPassChk($t_uid);
            }
            $this->LoadModel('OsSessionLog');
            $existing_ses = $this->OsSessionLog->getUserDetls($t_uid);
            if ($existing_ses) {
                $t_sql = 'SELECT password FROM users WHERE id=' . $t_uid . ' limit 1';
                $rec_user_login = $this->User->query($t_sql);
                if ($rec_user_login[0]['users']['password'] != $existing_ses['OsSessionLog']['user_agent'][$_COOKIE['user_uniq_agent']]) {
                    #$this->temp_logout();
                }
            }
            /* end */

            if((!stristr(PAGE_NAME,"ajax_") && !in_array(PAGE_NAME,$ajaxPageArray)) || PAGE_NAME=='categorytab' || PAGE_NAME =='ajax_savecategorytab') {
                $User = ClassRegistry::init('User');
                $User->unbindModel(array('hasAndBelongsToMany' => array('Project')));
                $userDeskNotify = $User->find('first', array(
                        'conditions' => array(
                                'User.id' => $this->Auth->User("id")
                        ),
                        'fields' => array('User.desk_notify','active_dashboard_tab','name')
                        )
                );
                $desk_notify = (int)$userDeskNotify['User']['desk_notify'];

                define('DESK_NOTIFY', $desk_notify);
                if(!defined('ACT_TAB_ID')) {
                    define('ACT_TAB_ID', $userDeskNotify['User']['active_dashboard_tab']);
                }
                if(!defined('USERNAME')) {
                    define('USERNAME', $userDeskNotify['User']['name']);
                }
            }
            $uid = $this->Auth->User("id");
			define('TOT_COMPANY', 1);
            define('USER_TYPE', $this->Auth->user('istype'));
            define('IS_MODERATOR', $this->Auth->user('is_moderator'));
            $this->set('success',$this->Session->read("SUCCESS"));
            $this->set('error',$this->Session->read("ERROR"));
            
            $this->Session->write("SUCCESS","");
            $this->Session->write("ERROR","");
			
            $this->layout = 'default_inner';
			
            //Global Variable and cookie set
            if(!defined('FIRST_LOGIN')) {
                define('FIRST_LOGIN', @$_COOKIE['FIRST_LOGIN']);
                if(@$_COOKIE['FIRST_LOGIN']) {
                    setcookie('FIRST_LOGIN','',-1,'/',DOMAIN_COOKIE,false,false);
                }
            }
            if(!defined('INVITE_USER')) {
                define('INVITE_USER', @$_COOKIE['INVITE_USER']);
                if(@$_COOKIE['INVITE_USER']) {
                    setcookie('INVITE_USER','',-1,'/',DOMAIN_COOKIE,false,false);
                }
            }
            if(!defined('CREATE_CASE')) {
                define('CREATE_CASE', @$_COOKIE['CREATE_CASE']);
                if(@$_COOKIE['CREATE_CASE']) {
                    setcookie('CREATE_CASE','',-1,'/',DOMAIN_COOKIE,false,false);
                }
            }
            if(!defined('ASSIGN_USER')) {
                define('ASSIGN_USER', @$_COOKIE['ASSIGN_USER']);
                if(@$_COOKIE['ASSIGN_USER']) {
                    setcookie('ASSIGN_USER','',-1,'/',DOMAIN_COOKIE,false,false);
                }
            }
            if(!defined('PROJ_NAME')) {
                define('PROJ_NAME', @$_COOKIE['PROJ_NAME']);
                if(@$_COOKIE['PROJ_NAME']) {
                    setcookie('PROJ_NAME','',-1,'/',DOMAIN_COOKIE,false,false);
                }
            }

            if(!defined('SES_ID')) {
                define('SES_ID', $this->Auth->User("id"));
            }
            if(!defined('SES_TIMEZONE')) {
                define('SES_TIMEZONE', $this->Auth->User("timezone_id"));
            }

            $sesType = "";
            $sesComp = "";
            if((!stristr(PAGE_NAME,"ajax_") && !in_array(PAGE_NAME,$ajaxPageArray)) || !@$_COOKIE['SES_COMP']) {
                $getAppComp = $Company->query("SELECT CompanyUser.user_type,CompanyUser.company_id,Company.logo,Company.website,Company.name,Company.is_active,Company.is_deactivated,Company.created,Company.uniq_id,Company.twitted  FROM company_users AS CompanyUser,companies AS Company WHERE CompanyUser.company_id=Company.id AND CompanyUser.user_id='".SES_ID."'");
                if(!defined('CMP_LOGO')) {
                    define('CMP_LOGO', @$getAppComp['0']['Company']['logo']);
                }
                if(!defined('ACCOUNT_STATUS')) {
                    define('ACCOUNT_STATUS', @$getAppComp['0']['Company']['is_active']);
                }
                if(!defined('IS_DEACTIVATED')) {
                    define('IS_DEACTIVATED', @$getAppComp['0']['Company']['is_deactivated']);
                }
                if(!defined('CMP_SITE')) {
                    define('CMP_SITE', @$getAppComp['0']['Company']['name']);
                }
                if(!defined('CMP_CREATED')) {
                    define('CMP_CREATED', @$getAppComp['0']['Company']['created']);
                }
                if(!defined('COMP_UID')) {
                    define('COMP_UID', @$getAppComp['0']['Company']['uniq_id']);
                }
                if(!defined('TWITTED')) {
                    define('TWITTED', @$getAppComp['0']['Company']['twitted']);
                }

                $sesType = @$getAppComp['0']['CompanyUser']['user_type'];
                $sesComp = @$getAppComp['0']['CompanyUser']['company_id'];

                setcookie("SES_TYPE", $sesType, COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
                setcookie("SES_COMP", $sesComp, COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
                setcookie("CMP_CREATED", @$getAppComp['0']['Company']['created'], COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
                setcookie("COMP_UID", @$getAppComp['0']['Company']['uniq_id'], COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);

                if(!defined('SES_TYPE')) {
                    define('SES_TYPE', $sesType);
                }
                if(!defined('SES_COMP')) {
                    define('SES_COMP', $sesComp);
                }
            } else {
                if(!defined('SES_TYPE')) {
                    define('SES_TYPE', $_COOKIE['SES_TYPE']);
                }
                if(!defined('SES_COMP')) {
                    define('SES_COMP', $_COOKIE['SES_COMP']);
                }
                if(!defined('CMP_CREATED')) {
                    define('CMP_CREATED',  $_COOKIE['CMP_CREATED']);
                }
                if(!defined('COMP_UID')) {
                    define('COMP_UID',  $_COOKIE['COMP_UID']);
                }
                if(!defined('CMP_LOGO')) {
                    define('CMP_LOGO', '');
                }
                if(!defined('CMP_SITE')) {
                    define('CMP_SITE','');
                }
            }

            if(PAGE_NAME == 'download' && CONTROLLER == 'easycases') {
                $this->redirect(HTTP_ROOT."easycases/downloadfiles/".@$this->request->params['pass'][0]);
            }

            ##### Set Timezone Variables
            if(PAGE_NAME != 'image_thumb' && PAGE_NAME != 'project_menu' && PAGE_NAME != 'search_project_menu' && PAGE_NAME != 'ajax_case_menu' && !in_array(PAGE_NAME,array('recent_projects','recent_milestones','statistics','usage_details','task_progress','leader_board'))) {
                $this->loadModel('Timezone');
                $timezn = $this->Timezone->find('first', array('conditions'=>array('Timezone.id' => SES_TIMEZONE), 'fields' => array('Timezone.gmt_offset','Timezone.dst_offset','Timezone.code')));

                if(!defined('TZ_GMT')) {
                    define('TZ_GMT', $timezn['Timezone']['gmt_offset']);
                }
                if(!defined('TZ_DST')) {
                    define('TZ_DST', $timezn['Timezone']['dst_offset']);
                }
                if(!defined('TZ_CODE')) {
                    define('TZ_CODE', $timezn['Timezone']['code']);
                }
            }

            ##### Set Privilege for User Access
			if(SES_TYPE == 3) {
                if((CONTROLLER == "users" && PAGE_NAME == "manage") || (CONTROLLER == "users" && PAGE_NAME == "add_new") || (CONTROLLER == "users" && PAGE_NAME == "add_template") || (CONTROLLER == "users" && PAGE_NAME == "manage_template")) {
                    $this->redirect(HTTP_ROOT.Configure::read('default_page'));
                }
            }

            if(PAGE_NAME == "dashboard") {
                if(isset($_GET['case']) && !isset($_GET['project'])) {
                    $this->redirect(HTTP_ROOT."dashboard");
                }
                elseif(isset($_GET['case']) && isset($_GET['project'])) {
                    $caseUniq = urldecode($_GET['case']);
                    $countActCase = $this->Easycase->find('count',array('conditions'=>array('Easycase.uniq_id'=>$caseUniq,'Easycase.isactive'=>1),'fields'=>'Easycase.id'));
                    if(!$countActCase) {
                        $this->redirect(HTTP_ROOT."dashboard");
                    }
                }
            }

            ##### Get projects for Quick case switch case
            if(!stristr(PAGE_NAME,"ajax_") && !in_array(PAGE_NAME,$ajaxPageArray)) {
                $this->loadModel('ProjectUser');
                $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
                $getallproj = $this->ProjectUser->query("SELECT DISTINCT Project.id,Project.uniq_id,Project.name,Project.default_assign FROM project_users AS ProjectUser,projects AS Project WHERE Project.id= ProjectUser.project_id AND ProjectUser.user_id=".SES_ID." AND Project.isactive='1' AND Project.company_id='".SES_COMP."' ORDER BY ProjectUser.dt_visited DESC");
                $this->set('getallproj',$getallproj);
                $GLOBALS['getallproj']= $getallproj;

                //Get owners and admins for Create New project pop
                $this->loadModel('User');
                $projOwnAdmin = $this->User->getProjectOwnAdmin();
                $GLOBALS['projOwnAdmin']= $projOwnAdmin;
            }
            if(CONTROLLER == 'archives' && PAGE_NAME == 'listall') {
                if(!defined('PARAM_ARC')) {
                    define('PARAM_ARC', $this->params['pass']['0']);
                }
                $this->loadModel('ProjectUser');
                $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
                if(strpos($_SERVER['REQUEST_URI'],'caselist')) {
                    $projAll1 = $this->ProjectUser->query("select distinct Project.id,Project.name,Project.uniq_id, (select count(distinct id) from easycases where easycases.project_id=Project.id and istype='1' and isactive='0' and user_id=".SES_ID.") as count FROM projects as Project,project_users as ProjectUser where ProjectUser.project_id=Project.id and  ProjectUser.user_id=".SES_ID." and Project.isactive = 1 and Project.company_id = '".SES_COMP."' order by ProjectUser.dt_visited DESC");
                } else if(strpos($_SERVER['REQUEST_URI'],'filelist')) {
                    $projAll1 = $this->ProjectUser->query("select distinct Project.id,Project.name,Project.uniq_id, (SELECT COUNT(Easycase.id) as count FROM easycases as Easycase,case_files as CaseFile WHERE Easycase.id=CaseFile.easycase_id AND Easycase.isactive=1 AND CaseFile.isactive =0 AND Easycase.user_id=".SES_ID." AND Easycase.project_id = Project.id) as count FROM projects as Project,project_users as ProjectUser where ProjectUser.project_id=Project.id and  ProjectUser.user_id=".SES_ID." and Project.isactive = 1 and Project.company_id ='".SES_COMP."' order by ProjectUser.dt_visited DESC");
                } else if(strpos($_SERVER['REQUEST_URI'],'milestonelist')) {
                    if(SES_TYPE == 1 || SES_TYPE == 2) {
                        $projAll1 = $this->ProjectUser->query("select distinct Project.id,Project.name,Project.uniq_id, (SELECT COUNT(Milestone.id) as count FROM milestones as Milestone WHERE Milestone.isactive=0 AND Milestone.company_id ='".SES_COMP."' AND Milestone.project_id = Project.id) as count FROM projects as Project,project_users as ProjectUser where ProjectUser.project_id=Project.id and  ProjectUser.user_id=".SES_ID." and Project.isactive = 1 and Project.company_id ='".SES_COMP."' order by ProjectUser.dt_visited DESC");
                    } else {
                        $projAll1 = $this->ProjectUser->query("select distinct Project.id,Project.name,Project.uniq_id, (SELECT COUNT(Milestone.id) as count FROM milestones as Milestone WHERE Milestone.	user_id ='".SES_ID."' AND Milestone.isactive=0 AND Milestone.company_id ='".SES_COMP."' AND Milestone.project_id = Project.id) as count FROM projects as Project,project_users as ProjectUser where ProjectUser.project_id=Project.id and  ProjectUser.user_id=".SES_ID." and Project.isactive = 1 and Project.company_id ='".SES_COMP."' order by ProjectUser.dt_visited DESC");
                    }
                }
                $this->set('projAll',$projAll1);
            }

            $casePriority = array(0=>"Top",1=>"High",2=>"Medium",3=>"Low",4=>"Very Low",5=>"Very Very Low");
            $this->set('casePriority',$casePriority);

            if(PAGE_NAME == "dashboard" || PAGE_NAME == "milestone" || PAGE_NAME == "milestonelist" || PAGE_NAME == "activity" || PAGE_NAME == "glide_chart" || PAGE_NAME == 'chart' || PAGE_NAME == 'hours_report' || PAGE_NAME == 'mydashboard') {
                $caseUrl = "";
                $urllvalue = 0;
                $urllvalueCase = 0;
                $projUniq = "";
                $projName = "";
                if(count($getallproj) == 1) {
                    $allpj = $getallproj[0]['Project']['uniq_id'];
                }else {
                    if($_COOKIE['ALL_PROJECT']) {
                        $allpj = $_COOKIE['ALL_PROJECT'];
                    }
                    else {
                        $allpj = "";
                    }
                }
                if(isset($_GET['project'])) {
                    $projectUrl = trim(urldecode($_GET['project']));

                    $conditions = array(
                            'conditions' => array('ProjectUser.user_id' => SES_ID,'Project.isactive'=>1,'Project.uniq_id'=>$projectUrl),
                            'fields' => array('DISTINCT Project.uniq_id', 'Project.name', 'Project.id', 'Project.default_assign'),
                            'order' => array('ProjectUser.dt_visited DESC')
                    );
                    $prjs = $this->ProjectUser->find('first', $conditions);
                    if(is_array($prjs) && count($prjs)) {
                        $curProjId = $prjs['Project']['id'];
                        $projUniq = $prjs['Project']['uniq_id'];
                        $projName = $prjs['Project']['name'];
                        $defaultAssign = $prjs['Project']['default_assign'];
                        $urllvalue = 1;

                        if(isset($_GET['case']) && $_GET['case']) {
                            $caseUrl = trim(urldecode($_GET['case']));
                            $urllvalueCase = 1;
                        }

                    }
                    else {
                        $this->redirect(HTTP_ROOT.Configure::read('default_page'));
                        $urllvalue = 0;
                    }
                }

                if($urllvalue == 0 && $allpj== "") {
                    $conditions2 = array(
                            'conditions' => array('ProjectUser.user_id' => SES_ID,'Project.isactive'=>1,'ProjectUser.company_id' => SES_COMP),
                            'fields' => array('DISTINCT Project.uniq_id', 'Project.name', 'Project.id'),
                            'order' => array('ProjectUser.dt_visited DESC'),
                            'limit' => 1
                    );

                    $projects = $this->ProjectUser->query("SELECT DISTINCT Project.uniq_id,Project.name,Project.id,Project.default_assign FROM project_users AS ProjectUser,projects AS Project WHERE Project.id= ProjectUser.project_id AND ProjectUser.user_id=".SES_ID." AND Project.isactive='1' AND Project.company_id='".SES_COMP."' ORDER BY ProjectUser.dt_visited DESC LIMIT 0,1");

                    if(count($projects)) {
                        $curProjId = $projects[0]['Project']['id'];
                        $projUniq = $projects[0]['Project']['uniq_id'];
                        $projName = $projects[0]['Project']['name'];
                        $defaultAssign = $projects[0]['Project']['default_assign'];
                    }
                }
                if($allpj== "all") {
                    $curProjId = "all";
                    if(isset($_GET['project']) && isset($projUniq)) {
                        $projUniq = $projUniq;
                        $projName = $projName;
                        $defaultAssign = $defaultAssign;
                    } else {
                        $projUniq = "all";
                        $projName = "All";
                    }
                } elseif(!isset($_GET['project'])) {
                    $curProjId = $getallproj[0]['Project']['id'];
                    $projUniq = $getallproj[0]['Project']['uniq_id'];
                    $projName = $getallproj[0]['Project']['name'];
                    $defaultAssign = $getallproj[0]['Project']['default_assign'];
                }
                $this->set('sh_status',$this->Cookie->read('SH_STATUS'));
                $this->set('sh_member',$this->Cookie->read('SH_MEM'));
                $this->set('sh_pri',$this->Cookie->read('SH_PRI'));
                $this->set('sh_sts',$this->Cookie->read('SH_STS'));
                $this->set('sh_top',$this->Cookie->read('SH_TOP'));
                $this->set('sh_proj',$this->Cookie->read('SH_PROJ'));
                $this->set('sh_typ',$this->Cookie->read('SH_TYPE'));
                $this->set('curProjId',$curProjId);
                $this->set('projUniq',$projUniq);
                $this->set('defaultAssign',$defaultAssign);
                $this->set('projName',$projName);
                $this->set('urllvalue',$urllvalue);
                $this->set('urllvalueCase',$urllvalueCase);
                $this->set('caseUrl',$caseUrl);
            }
			
            if(@$_COOKIE['SEARCH']) {
                unset($_COOKIE['SEARCH']);
                $caseSearch = "";
            }
            if(isset($_GET['search']) && urldecode(trim($_GET['search']))) {
                $caseSearch = urldecode(trim($_GET['search']));
                setcookie('SEARCH',$caseSearch,COOKIE_REM,'/',DOMAIN_COOKIE,false,false);
            } elseif(@$_COOKIE['SEARCH']) {
                $caseSearch = $_COOKIE['SEARCH'];
            } elseif(isset($_REQUEST['case']) && urldecode(trim($_REQUEST['case'])) && isset($_REQUEST['project']) && urldecode(trim($_REQUEST['project'])) && !isset($_GET['search']) && !isset($_COOKIE['SEARCH'])) {
                $case = urldecode(trim($_REQUEST['case']));
                $this->loadModel('Easycase');
                $case_no = $this->Easycase->getCaseNo($case);
                $caseSearch = "#".$case_no['Easycase']['case_no'];
                setcookie('SEARCH',$caseSearch,COOKIE_REM,'/',DOMAIN_COOKIE,false,false);
            } else {
                $caseSearch = "";
            }
            $this->set('srch_text',$caseSearch);

            if(isset($_GET['case_no']) && urldecode(trim($_GET['case_no']))) {
                $case_num = urldecode(trim($_GET['case_no']));
                setcookie('CASESRCH',$case_num,COOKIE_REM,'/',DOMAIN_COOKIE,false,false);
            } elseif(@$_COOKIE['CASESRCH']) {
                $case_num = $_COOKIE['CASESRCH'];
            } else {
                $case_num = "";
            }
            $this->set('case_num',$case_num);
            if(PAGE_NAME == "download") {
                $filename = substr(strrchr($_GET['url'], "/"), 1);
                if (!isset($filename) || empty($filename)) {
                    $var = "<table align='center' width='100%'><tr><td style='font:normal 14px verdana;color:#FF0000;' align='center'>Please specify a file name for download.</td></tr></table>";
                    die($var);
                }
                if(!file_exists(DIR_CASE_FILES.$filename)) {
                    $var = "<table align='center' width='100%'><tr><td style='font:normal 14px verdana;color:#FF0000;' align='center'>Oops! File not found.<br/> File may be deleted or make sure you specified correct file name.</td></tr></table>";
                    die($var);
                }
                $chkProject = 0;
                $this->loadModel('CaseFile');
                $getCaseId = $this->CaseFile->find('first', array('conditions'=>array('CaseFile.file' => $filename), 'fields' => array('CaseFile.easycase_id')));

                if(isset($getCaseId['CaseFile']['easycase_id']) && $getCaseId['CaseFile']['easycase_id']) {
                    $caseid = $getCaseId['CaseFile']['easycase_id'];
                    $this->loadModel('Easycase');
                    $getProj = $this->Easycase->find('first', array('conditions'=>array('Easycase.id' => $caseid,'Easycase.isactive'=>1), 'fields' => array('Easycase.project_id')));

                    if(count($getCaseId)) {
                        $projid = $getProj['Easycase']['project_id'];

                        $this->loadModel('ProjectUser');
                        $conditions = array(
                                'conditions' => array('ProjectUser.user_id' => SES_ID,'Project.isactive'=>1,'Project.id'=>$projid),
                                'fields' => 'DISTINCT Project.id'
                        );
                        $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
                        $chkProject = $this->ProjectUser->find('count', $conditions);
                    }
                    if($chkProject == 0) {
                        $var = "<table align='center' width='100%'><tr><td style='font:normal 14px verdana;color:#FF0000;' align='center'>Oops! File cannot be download.<br/> You might not have access to download the file</td></tr></table>";
                        die($var);
                    }


                }
            }
            if(PAGE_NAME == "downloadImgFile") {
                $filename = substr(strrchr($_GET['url'], "/"), 1);

                if (!isset($filename) || empty($filename)) {
                    $var = "<table align='center' width='100%'><tr><td style='font:normal 14px verdana;color:#FF0000;' align='center'>Please specify a file name for download.</td></tr></table>";
                    die($var);
                }
                if(!file_exists(DIR_CASE_FILES.$filename)) {
                    $var = "<table align='center' width='100%'><tr><td style='font:normal 14px verdana;color:#FF0000;' align='center'>Oops! File not found.<br/> File may be deleted or make sure you specified correct file name.</td></tr></table>";
                    die($var);
                }
                $chkProject = 0;
                $this->loadModel('CaseFile');
                $getCaseId = $this->CaseFile->find('first', array('conditions'=>array('CaseFile.file' => $filename), 'fields' => array('CaseFile.easycase_id')));

                if(isset($getCaseId['CaseFile']['easycase_id']) && $getCaseId['CaseFile']['easycase_id']) {
                    $caseid = $getCaseId['CaseFile']['easycase_id'];
                    $this->loadModel('Easycase');
                    $getProj = $this->Easycase->find('first', array('conditions'=>array('Easycase.id' => $caseid,'Easycase.isactive'=>1), 'fields' => array('Easycase.project_id')));

                    if(count($getCaseId)) {
                        $projid = $getProj['Easycase']['project_id'];

                        $this->loadModel('ProjectUser');
                        $conditions = array(
                                'conditions' => array('ProjectUser.user_id' => SES_ID,'Project.isactive'=>1,'Project.id'=>$projid),
                                'fields' => 'DISTINCT Project.id'
                        );
                        $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
                        $chkProject = $this->ProjectUser->find('count', $conditions);
                    }
                    if($chkProject == 0) {
                        $var = "<table align='center' width='100%'><tr><td style='font:normal 14px verdana;color:#FF0000;' align='center'>Oops! File cannot be download.<br/> You might not have access to download the file</td></tr></table>";
                        die($var);
                    }
                }
            }

            if(PAGE_NAME != 'image_thumb' && PAGE_NAME != 'project_menu' && PAGE_NAME != 'search_project_menu' && PAGE_NAME != 'ajax_case_menu' && !in_array(PAGE_NAME,array('to_dos','recent_projects','recent_activities','recent_milestones','statistics','task_progress','leader_board','ajax_activity'))) {
                
                if(!stristr(PAGE_NAME,"ajax_") && !in_array(PAGE_NAME,$ajaxPageArray)) {
                    $this->loadModel('Easycase');
                    if(count($getallproj)) {
                        if(PAGE_NAME == "dashboard" && $projName!='All') {
                            $ctProjUniq = $projUniq;
                        } elseif(count($getallproj) >= 1) {
                            $ctProjUniq = $getallproj['0']['Project']['uniq_id'];
                        } else {
                            $ctProjUniq = '';
                        }
                    }
                    $projUser = array();
                    if($ctProjUniq) {
                        $projUser = array($ctProjUniq => $this->Easycase->getMemebers($ctProjUniq));
                    }
                    $GLOBALS['projUser'] = $projUser;
                    $this->set('ctProjUniq', $ctProjUniq);

                    //Getting Task templetes
                    $CaseTemplate = ClassRegistry::init('CaseTemplate');
                    $CaseTemplate->recursive = -1;
                    $getTmpl = $CaseTemplate->find('all',array(
                            'conditions'=>array(
                                    "OR" => array(
                                            'AND' => array(
                                                    'CaseTemplate.is_active'   => 1,
                                                    'CaseTemplate.company_id' => SES_COMP
                                            )
                                    )
                            ),
                            'fields' => array('id','name'),
                            'order'=>'CaseTemplate.name ASC'
                    ));
                    $GLOBALS['getTmpl'] = $getTmpl;
                    /* Create Task Ends */

                }
            }
			if(PAGE_NAME != 'image_thumb' && PAGE_NAME != 'project_menu' && PAGE_NAME != 'search_project_menu' && PAGE_NAME != 'ajax_case_menu' && !in_array(PAGE_NAME,array('to_dos','recent_projects','recent_activities','recent_milestones','statistics','task_progress','leader_board','ajax_activity'))) {
                //Checking if the company status is active or not
                if($this->params['action'] == 'upgrade_member' || $this->params['action'] == 'logout') {
                   
                } elseif(SES_TYPE<=3) {
                    $project_cls = ClassRegistry::init('Project');
                    $prjlist = $project_cls->find('list',array('conditions'=>array('company_id'=>SES_COMP),'fields'=>array('id','name')));
                    $prjcnt = $prjlist?count($prjlist):0;
                    $GLOBALS['project_count']= $prjcnt;
                    $GLOBALS['active_proj_list'] = $prjlist;
                    $this->set('is_active_proj',$prjcnt);
                    $this->set('active_proj_list',$prjlist);
                    if((SES_TYPE<=2) && (!$prjcnt) && (PAGE_NAME!='help') && (PAGE_NAME!='default_inner') && (PAGE_NAME!='hide_default_inner') && (PAGE_NAME!='ajax_new_project') && (PAGE_NAME!='launchpad') && (PAGE_NAME!='googleConnect') && (PAGE_NAME!='ajax_check_project_exists') && (PAGE_NAME!='check_fordisabled_user') && (PAGE_NAME!='onbording') && (PAGE_NAME!='ajax_quickcase_mem') && (PAGE_NAME!='ajax_case_menu') && (PAGE_NAME!='ajax_project_size') && (PAGE_NAME!='member_list') && (PAGE_NAME!='ajax_check_size') && (PAGE_NAME!='new_user') && (PAGE_NAME!='getProjects') && (PAGE_NAME!='add_project') && (PAGE_NAME !='logout') && (PAGE_NAME!='ajax_check_user_exists') && (PAGE_NAME!='image_thumb') && (PAGE_NAME!='ajax_recent_case') && (PAGE_NAME!='ajax_custom_filter_show') && (PAGE_NAME!='post_support_inner') && (PAGE_NAME!='session_maintain') && (CONTROLLER!='users' || (PAGE_NAME!='account_activity' && PAGE_NAME!='transaction' && PAGE_NAME!='creditcard' && PAGE_NAME!='subscription' && PAGE_NAME!='profile' && PAGE_NAME!='changepassword' && PAGE_NAME!='email_notifications' && PAGE_NAME!='show_preview_img' && PAGE_NAME!='done_cropimage' && PAGE_NAME!='pricing' && PAGE_NAME!='termsofservice' && PAGE_NAME!='confirmationPage' && PAGE_NAME!='upgrade'))) {
                        //echo PAGE_NAME;exit;
                        $this->redirect(HTTP_ROOT.'onbording');
                    }
                }
				$this->betauser_limitation();
                if(!stristr(PAGE_NAME,"ajax_") && !in_array(PAGE_NAME,$ajaxPageArray)) {
                    /* Create Task Starts */

                    $this->loadModel('Easycase');
                    if(count($getallproj)) {
                        if(PAGE_NAME == "dashboard" && $projName!='All') {
                            $ctProjUniq = $projUniq;
                        } elseif(count($getallproj) >= 1) {
                            $ctProjUniq = $getallproj['0']['Project']['uniq_id'];
                        } else {
                            $ctProjUniq = '';
                        }
                    }
                    $projUser = array();
                    if($ctProjUniq) {
                        $projUser = array($ctProjUniq => $this->Easycase->getMemebers($ctProjUniq));
                    }
                    $GLOBALS['projUser'] = $projUser;
                    $this->set('ctProjUniq', $ctProjUniq);

                    //Getting Task templetes
                    $CaseTemplate = ClassRegistry::init('CaseTemplate');
                    $CaseTemplate->recursive = -1;
                    $getTmpl = $CaseTemplate->find('all',array(
                            'conditions'=>array(
                                    "OR" => array(
                                            'AND' => array(
                                                    'CaseTemplate.is_active'   => 1,
                                                    'CaseTemplate.company_id' => SES_COMP
                                            )
                                    )
                            ),
                            'fields' => array('id','name'),
                            'order'=>'CaseTemplate.name ASC'
                    ));
                    $GLOBALS['getTmpl'] = $getTmpl;
                    /* Create Task Ends */

                }
            }
        } else {
			
            $this->set('success',$this->Session->read("SUCCESS"));
            $this->set('error',$this->Session->read("ERROR"));

            $this->Auth->autoRedirect = false;
            Security::setHash('md5');
			
            $this->Auth->authenticate = array('Form' => array('fields' => array('username' => 'email', 'password' => 'password')));
            $this->Auth->allow('license','login','validate_emailurl','forgotpassword','session_maintain','googleConnect', 'googleSignup', 'setGoogleInfo','ajaxpostcase','ajaxemail','register_user','invitation','emailUpdate');
            
            $this->Session->write("SUCCESS","");
            $this->Session->write("ERROR","");
     
            if(!in_array(PAGE_NAME,$this->Auth->allowedActions)) {
                $this->redirect(HTTP_HOME);
            }

            // Empty Session
            if(!defined('SES_ID')) {
                define('SES_ID', '');
            }
            if(!defined('SES_TYPE')) {
                define('SES_TYPE', '');
            }
            if(!defined('SES_TIMEZONE')) {
                define('SES_TIMEZONE', "");
            }
			
			$this->layout = 'default_outer';
			
		}
        if(!defined('PROJ_ID')) {
            define('PROJ_ID', $curProjId);
        }
        if(!defined('PROJ_UNIQ_ID')) {
            define('PROJ_UNIQ_ID', $projUniq);
        }
        if(!defined('projUniq')) {
            define('projUniq', $projUniq);
        }
        if(!defined('TOT_COMPANY')) {
            define('TOT_COMPANY', '');
        }
	
		if(@$_COOKIE['SES_COMP']) {
			$this->loadModel("TypeCompany");
			//$sql = "SELECT Type.* FROM type_companies AS TypeCompany LEFT JOIN types AS Type ON (TypeCompany.type_id=Type.id) WHERE TypeCompany.company_id=".@$_COOKIE['SES_COMP']." ORDER BY Type.company_id DESC, Type.seq_order ASC";
			$sql = "SELECT Type.* FROM type_companies AS TypeCompany LEFT JOIN types AS Type ON (TypeCompany.type_id=Type.id) WHERE TypeCompany.company_id=".@$_COOKIE['SES_COMP']." ORDER BY Type.name ASC";			
			$TypeCompany = $this->TypeCompany->query($sql);
		}
	

		if (isset($TypeCompany) && !empty($TypeCompany)) {
			$typeArr = $TypeCompany;
			$typeDflt = 1;
		} else {
			$typeDflt = 1;
			$typeArr = array(
				'1' => array(
					'Type' =>array(
						'id'   => '2',
						'name' => 'Development',
						'short_name'=> 'dev',
						'seq_order'=> '1'
					)
				),
				'2' => array(
					'Type' =>array(
						'id'   => '1',
						'name' => 'Bug',
						'short_name'=> 'bug',
						'seq_order'=> '2'
					)
				),
				'3' => array(
					'Type' =>array(
						'id'   => '10',
						'name' => 'Update',
						'short_name'=> 'upd',
						'seq_order'=> '3'
					)
				),
				'4' => array(
					'Type' =>array(
						'id'   => '12',
						'name' => 'Change Request',
						'short_name'=> 'cr',
						'seq_order'=> '4'
					)
				),
				'5' => array(
					'Type' =>array(
						'id'   => '11',
						'name' => 'Idea',
						'short_name'=> 'idea',
						'seq_order'=> '5'
					)
				),
				'6' => array(
					'Type' =>array(
						'id'   => '3',
						'name' => 'Enhancement',
						'short_name'=> 'enh',
						'seq_order'=> '6'
					)
				),
				'7' => array(
					'Type' =>array(
						'id'   => '4',
						'name' => 'Research n Do',
						'short_name'=> 'rnd',
						'seq_order'=> '7'
					)
				),
				'8' => array(
					'Type' =>array(
						'id'   => '7',
						'name' => 'Maintenance',
						'short_name'=> 'mnt',
						'seq_order'=> '8'
					)
				),
				'9' => array(
					'Type' =>array(
						'id'   => '5',
						'name' => 'Quality Assurance',
						'short_name'=> 'qa',
						'seq_order'=> '9'
					)
				),
				'10' => array(
					'Type' =>array(
						'id'   => '6',
						'name' => 'Unit Testing',
						'short_name'=> 'unt',
						'seq_order'=> '10'
					)
				),
				'11' => array(
					'Type' =>array(
						'id'   => '9',
						'name' => 'Release',
						'short_name'=> 'rel',
						'seq_order'=> '11'
					)
				),
				'12' => array(
					'Type' =>array(
						'id'   => '8',
						'name' => 'Others',
						'short_name'=> 'oth',
						'seq_order'=> '12'
					)
				),
			);
		}
		$dashboardArr = array(
				'1' => array(
						'id'   => '1',
						'name' => 'To Dos'
				),
				'2' => array(
						'id'   => '2',
						'name' => 'Recent Projects'
				),
				'3' => array(
						'id'   => '3',
						'name' => 'Usage Details'
				),
				'5' => array(
						'id'   => '5',
						'name' => 'Statistics'
				),
				'6' => array(
						'id'   => '6',
						'name' => 'Recent Activities'
				),
				'8' => array(
						'id'   => '8',
						'name' => 'Task Type'
				),
				'9' => array(
						'id'   => '9',
						'name' => 'Task Status'
				),
			);
			$GLOBALS['DASHBOARD_ORDER'] = $dashboardArr;

			$plan_types = array(9=>'Free',5=>'Basic',6=>'Team',7=>'Business',8=>'Premium',1=>'Free',2=>'Pro',3=>'Team',4=>'Premium');
			$this->set('plan_types',$plan_types);
			$GLOBALS['plan_types'] = $plan_types;
			$GLOBALS['TYPE'] = $typeArr;
			$GLOBALS['TYPE_DEFAULT'] = $typeDflt;

			if(file_exists(WWW_ROOT.'error.check')) {
				unlink(WWW_ROOT.'error.check');
			}
		
    }
    function session_maintain() {
        $this->layout='ajax';
        $sessionout = 0;
        if($_COOKIE['USER_UNIQ']) {
            setcookie('USER_UNIQ',$_COOKIE['USER_UNIQ'],COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
            setcookie('USERTYP',$_COOKIE['USERTYP'],COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
            setcookie('USERTZ',$_COOKIE['USERTZ'],COOKIE_TIME,'/',DOMAIN_COOKIE,false,false);
        }
        else {
            $sessionout = 1;
        }
        echo $sessionout;
        exit;
    }
    /*********** Image Thumb ***********/
    function image_thumb() {
        $this->autoRender = false;

        $save_to_file = true;
        $image_quality = 100;
        $image_type = -1;
        $max_x = 100;
        $max_y = 100;
        $cut_x = 0;
        $cut_y = 0;
        $images_folder = '';
        $thumbs_folder = '';
        $to_name = '';

        if($_REQUEST['type'] == "photos") {
            $images_folder = DIR_USER_PHOTOS;
            if(defined('USE_S3') && USE_S3 && urldecode($_REQUEST['file'])!='user.png') {
                $images_folder = DIR_USER_PHOTOS_S3;
            }
        } elseif($_REQUEST['type'] == "company") {
            $images_folder = DIR_FILES.'company/';
            if(defined('USE_S3') && USE_S3) {
                $images_folder = DIR_USER_COMPANY_S3;
            }
        } else {
            $images_folder = DIR_CASE_FILES;
        }
        if(isset($_REQUEST['nocache'])) {
            $save_to_file = intval($_REQUEST['nocache']) == 1;
        }
        if(isset($_REQUEST['file'])) {
            $from_name = urldecode($_REQUEST['file']);
        }
        if(isset($_REQUEST['dest'])) {
            $to_name = urldecode($_REQUEST['dest']);
        }
        if(isset($_REQUEST['quality'])) {
            $image_quality = intval($_REQUEST['quality']);
        }
        if (isset($_REQUEST['t'])) {
            $image_type = intval($_REQUEST['t']);
        }
        if (isset($_REQUEST['sizex'])) {
            $max_x = intval($_REQUEST['sizex']);
        }
        if (isset($_REQUEST['sizey'])) {
            $max_y = intval($_REQUEST['sizey']);
        }
        if (isset($_REQUEST['size'])) {
            $max_x = intval($_REQUEST['size']);
        }
        ini_set('memory_limit', '-1');//echo $images_folder.$from_name;//exit;
        //$this->Image->GenerateThumbFile($images_folder.$from_name, $to_name,$max_x,$max_y);
        $this->Image->GenerateThumbFile($images_folder.$from_name, $to_name,$max_x,$max_y,$from_name);
    }
	function betauser_limitation() {
        App::import('Model','UserSubscription');
        $usersubscription = new UserSubscription();
        $limitation = $usersubscription->find('first',array('conditions'=>array('company_id'=>SES_COMP),'order'=>'id DESC'));
        $GLOBALS['Userlimitation'] = $limitation['UserSubscription'];
        $GLOBALS['user_subscription'] = $limitation['UserSubscription'];
        $this->set("user_subscription",$limitation['UserSubscription']);
		if($limitation['UserSubscription']['subscription_id'] == 1 || $limitation['UserSubscription']['subscription_id'] == 9) {
			$GLOBALS['FREE_SUBSCRIPTION'] = 1;
		}
        //$this->set('sub_limitation',$limitation['UserSubscription']);
        //echo $this->projcetcount(SES_COMP,$limitation)."====".$this->milestonecount(SES_COMP,$limitation)."--".$this->usercount(SES_COMP,$limitation);
        $this->set('rem_projects',$this->projcetcount(SES_COMP,$limitation));
        $this->set('rem_milestone', $this->milestonecount(SES_COMP,$limitation));
        $this->set('rem_users', $this->usercount(SES_COMP,$limitation));

        App::import('Model','CaseFile');
        $cmpusr = new CaseFile();
        $usedspace = $cmpusr->getStorage();
        $this->set('used_storage',$usedspace);
        $GLOBALS['usedspace'] = $usedspace;
        if(isset($limitation['UserSubscriptions']['storage_limit']) && ( (strtolower($limitation['UserSubscriptions']['storage_limit'])=='unlimited') || $limitation['UserSubscription']['is_free'])) {
            $this->set('remspace','Unlimited');
            $GLOBALS['remspace']='Unlimited';
        } elseif(isset($limitation['UserSubscriptions']['storage_limit']) && $usedspace<= $limitation['UserSubscriptions']['storage_limit']) {
            $this->set('remspace',($limitation['UserSubscriptions']['storage_limit']-$usedspace));
            $GLOBALS['remspace']=$limitation['UserSubscriptions']['storage_limit']-$usedspace;
        } else {
            $GLOBALS['remspace']=0;
            $this->set('remspace',0);
        }
        //echo "Deal count=".$rem_deal_count."--Contact = ".$rem_contact_count."--User = ".$rem_user_count."-Total Used Space:-".$totalused;exit;
    }
    function files($type='cases', $files = NULL) {
        $this->layout='ajax';
        if($type=='photos') {
            $files = DIR_USER_PHOTOS.basename($files);
        } elseif($type=='company') {
            $files = DIR_FILES.'company/'.basename($files);
        } else {
            $files = DIR_CASE_FILES.basename($files);
        }
        //$file_mime =  mime_content_type (DIR_CASE_FILES.basename($files));
        $file_mime = @finfo_file(finfo_open(FILEINFO_MIME_TYPE), $files);
        if($file_mime) {
            header("Content-Type:$file_mime");
        }
        readfile($files);
        exit;
    }
    function _datestime() {
        if(gmdate('D',strtotime("now"))!="Fri") {
            $c = strtotime("next Friday");
            $re = gmdate('Y-m-d H:i:s', $c);
            $this->set('st',$re);
        }
        else {
            $re2=gmdate('Y-m-d H:i:s',strtotime("now"));
            $this->set('st',$re2);
        }
        $timestamp = strtotime("now");
        $this->set('st1',gmdate('Y-m-d H:i:s', $timestamp));
        $timestamp = strtotime("next Monday");
        $this->set('st2',gmdate('Y-m-d H:i:s', $timestamp));
        $timestamp = strtotime("tomorrow");
        $this->set('st3',gmdate('Y-m-d H:i:s', $timestamp));
    }
    function ajax_case_template() {
        $this->layout='';

        $tmpl_id = $this->params['data']['tmpl_id'];

        $CaseTemplate = ClassRegistry::init('CaseTemplate');
        $CaseTemplate->recursive = -1;
        $getVal = $CaseTemplate->findById($tmpl_id);

        echo $getVal['CaseTemplate']['description'];
        exit;
    }

    function projcetcount($company_id=SES_COMP,$sub_limitation=array()) {
        if(!$sub_limitation) {
            App::import('Model','UserSubscription');
            $usersubscription = new UserSubscription();
            $sub_limitation = $usersubscription->find('first',array('conditions'=>array('company_id'=>$company_id),'order'=>'id DESC'));
        }
        $this->loadModel('Projects');
        $used_pcount = $this->Projects->find('count',array('conditions'=>array('company_id'=>$company_id)));
        $this->set('used_projects_count',$used_pcount);
        if($sub_limitation['UserSubscription']['project_limit'] && (strtolower($sub_limitation['UserSubscription']['project_limit'])=='unlimited' || $sub_limitation['UserSubscription']['is_free'])) {
            return 'Unlimited';
        } else {
            if($sub_limitation['UserSubscription']['project_limit']>=$used_pcount) {
                return ($sub_limitation['UserSubscription']['project_limit']-$used_pcount);
            } else {
                return 0;
            }
        }
    }
    function milestonecount($company_id=SES_COMP,$sub_limitation=array()) {
        //Currently milestone is treated as Unlimited so calculation is not done if required later then will do
        return 'Unlimited';
        
    }
    function usercount($company_id=ACC_ID,$sub_limitation=array()) {
        if(!$sub_limitation) {
            App::import('Model','UserSubscription');
            $usersubscription = new UserSubscription();
            $sub_limitation = $usersubscription->find('first',array('conditions'=>array('company_id'=>$company_id),'order'=>'id DESC'));
        }
        App::import('Model','CompanyUsers');
        $usr = new CompanyUser();
        if($sub_limitation['UserSubscription']['btsubscription_id']) {
            //$used_ucount = $usr->find('count',array('conditions'=>array('company_id'=>$company_id,'((is_active=1 OR is_active=2) OR (is_active=0 AND DATE(billing_end_date)>="'.GMT_DATE.'"))',)));
            //It includes the deleted users who are paid for the current billing month.
            $used_ucount = $usr->find('count',array('conditions'=>array('company_id'=>$company_id)));
        } else {
            $used_ucount = $usr->find('count',array('conditions'=>array('company_id'=>$company_id,'(is_active=1 OR is_active=2)')));
        }

        $GLOBALS['usercount'] = $used_ucount;
        $this->set('current_active_users',$used_ucount);
        if($sub_limitation['UserSubscription']['user_limit'] && (strtolower($sub_limitation['UserSubscription']['user_limit'])=='unlimited' || $sub_limitation['UserSubscription']['is_free'])) {
            return 'Unlimited';
        } else {
            if($sub_limitation['UserSubscription']['user_limit']>=$used_ucount) {
                return ($sub_limitation['UserSubscription']['user_limit']-$used_ucount);
            } else {
                return 0;
            }
        }
    }
    function blankpage() {
        echo "Blank Page";
        exit;
    }

    function isiPad() {
        preg_match('/iPad/i', $_SERVER['HTTP_USER_AGENT'], $match);
        if (!empty($match)) {
            return true;
        }
        return false;
    }
}

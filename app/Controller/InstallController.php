<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * CakePHP InstallController
 * @author Andolasoft
 */
class InstallController extends AppController {

    public $helpers = array('Html');
    public $addonZipFile = array('timelog' => 'Timelog-V1.0', 'invoice' => 'Invoice-V1.0', 'taskstatusgroup' => 'TaskStatusGroup-V1.0', 'ganttchart' => 'GanttChart-V1.0', 'api' => 'API-V1.0', 'recurringtask' => 'RecurringTask-V1.0', 'chat' => 'Chat-V1.0','clientmanagement'=>'ClientRestriction-V1.0', 'projecttemplate'=>'ProjectTemplate-V1.0', 'mobileapi'=>'MobileAPI-V1.0', 'timelogpayment' => 'TimelogPayment-V1.0', 'multilanguage' => 'MultiLanguage-V1.0', 'timeloggold' => 'TimelogGold-V1.0','invoicepro' => 'InvoicePro-V1.0','expense'=> 'Expense-V1.0','wiki'=> 'Wiki-V1.0','phpmailer'=>'PhpMailer-V1.0');
    public $addonName = array('timelog' => 'Time Log ', 'invoice' => 'Invoice', 'taskstatusgroup' => 'Task Status Group', 'ganttchart' => 'Gantt Chart', 'api' => 'API', 'recurringtask' => 'Recurring Task', 'chat' => 'Chat','clientmanagement'=>'Client Restriction', 'projecttemplate'=>'Project Template', 'mobileapi'=>'Mobile API', 'timelogpayment' => 'Timelog with Payment', 'multilanguage' => 'Multi Language Support', 'timeloggold' => 'Timelog Gold','invoicepro' => 'Invoice Pro','expense'=>' Expense','wiki'=>'Wiki','phpmailer'=>'Php Mailer');
    public $addonDBName = array('timelog' => 'TLG ', 'invoice' => 'INV', 'taskstatusgroup' => 'TSG', 'ganttchart' => 'GNC', 'api' => 'API', 'recurringtask' => 'RCT','chat' => 'CHAT','clientmanagement'=>'CR', 'projecttemplate'=>'PT','mobileapi'=>'MAPI', 'timelogpayment' => 'TPAY', 'multilanguage' => 'LANG', 'timeloggold' => 'GTLG','invoicepro' => 'GINV','expense'=>'EXP','wiki'=>'WIKI','phpmailer'=>'PHPMAILER');
    public $addonSql = array('timelog' => 'log_times', 'invoice' => 'invoice', 'taskstatusgroup' => 'taskstatusgroup', 'ganttchart' => 'ganttchart', 'api' => 'api', 'recurringtask' => 'recurring_task','chat' => 'chat','clientmanagement'=>'client_restriction', 'timelogpayment' => 'payment', 'mobileapi' => 'mapi', 'multilanguage' => 'multisupport', 'timeloggold' => 'timeloggold', 'projecttemplate' => 'projecttemplate','invoicepro' => 'invoicepro','expense'=>'expense','wiki'=>'wiki','phpmailer'=>'emailsetting');
    public $addonFolder = array('timelog' => 'Timelog', 'invoice' => 'Invoice', 'taskstatusgroup' => 'TaskStatusGroup', 'ganttchart' => 'Ganttchart', 'api' => 'API', 'recurringtask' => 'RecurringTask','chat' => 'Chat','clientmanagement'=>'ClientRestriction', 'projecttemplate'=>'ProjectTemplate','mobileapi'=>'MobileApi', 'timelogpayment' => 'Timelog', 'multilanguage' => 'MultiLanguage', 'timeloggold' => 'Timelog','invoicepro' => 'Invoice','expense'=>'Expense','wiki'=>'Wiki','phpmailer'=>'PhpMailer');
    
    public function index($addon = NULL) {
        $this->set('addonName', $this->addonName);
        $this->set('addon', $addon);
    }
    
    function check_installation(){
        $this->layout = 'ajax';
        if(isset($this->request->data) && !empty($this->request->data)){
            $addon = $this->request->data['addon'];
            $arr = array();
            if(isset($this->addonZipFile[$addon]) && $this->addonZipFile[$addon] != ''){
                $zipFile = $this->addonZipFile[$addon].'.zip';
                $webroot_dir = new Folder(WEB_ROOT);
                $zipfilespresent = $webroot_dir->find($zipFile);
                $addonNm = $this->addonName[$addon];
                if(empty($zipfilespresent)){
                    $arr['error'] = '1';
                    $arr['msg'] = __("It seems you have not copied the", true)." '$zipFile' ".__("file for")." '$addonNm' ".__("addon to app/webroot folder").".";
                }else{
                    $this->loadModel('Addon');
                    $addonExists = $this->Addon->find('first', array('conditions' => array('Addon.name' => $this->addonDBName[$addon])));
                    if(empty($addonExists)){
                        $addonData = NULL;
                        $addonData['name'] = $this->addonDBName[$addon];
                        $addonData['isactive'] = 1;
                        $addonData['dt_created'] = GMT_DATETIME;
                        $this->Addon->saveAll($addonData);
						if($addon == 'timelogpayment' || $addon == 'timeloggold'){
							$tlgExists = $this->Addon->find('first', array('conditions' => array('Addon.name' => 'TLG')));
							if(empty($tlgExists)){
								$tlgData = NULL;
								$tlgData['name'] = 'TLG';
								$tlgData['isactive'] = 1;
								$tlgData['dt_created'] = GMT_DATETIME;
								$this->Addon->saveAll($tlgData);
							}else if(!empty($tlgExists) && $tlgExists['Addon']['isactive'] == 0){
								$this->Addon->id = $tlgExists['Addon']['id'];
								$this->Addon->updateAll(array('Addon.isactive' => 1, 'Addon.dt_created' => "'".GMT_DATETIME."'"));
							}
                            if($addon == 'timeloggold'){
                                $tlgPayExists = $this->Addon->find('first', array('conditions' => array('Addon.name' => 'TPAY')));
                                if(empty($tlgPayExists)){
                                        $tlgData = NULL;
                                        $tlgData['name'] = 'TPAY';
                                        $tlgData['isactive'] = 1;
                                        $tlgData['dt_created'] = GMT_DATETIME;
                                        $this->Addon->saveAll($tlgData);
                                }else if(!empty($tlgPayExists) && $tlgPayExists['Addon']['isactive'] == 0){
                                        $this->Addon->id = $tlgPayExists['Addon']['id'];
                                        $this->Addon->updateAll(array('Addon.isactive' => 1, 'Addon.dt_created' => "'".GMT_DATETIME."'"));
						}
                            }
                        } 
                        if($addon == 'invoicepro'){
							$invExists = $this->Addon->find('first', array('conditions' => array('Addon.name' => 'INV')));
							if(empty($invExists)){
								$invData = array();
								$invData['name'] = 'INV';
								$invData['isactive'] = 1;
								$invData['dt_created'] = GMT_DATETIME;
                                                                 
								$this->Addon->saveAll($invData);
                                                                
							}else if(!empty($invExists) && $invExists['Addon']['isactive'] == 0){
								$this->Addon->id = $invExists['Addon']['id'];
								$this->Addon->updateAll(array('Addon.isactive' => 1, 'Addon.dt_created' => GMT_DATETIME));
							}
						}
                    }else{
                        $this->Addon->id = $addonExists['Addon']['id'];
                        $this->Addon->updateAll(array('Addon.isactive' => 1, 'Addon.dt_created' => '"'.GMT_DATETIME.'"'));
                    }
                    $zip = new ZipArchive;
                    if ($zip->open($zipfilespresent[0]) === TRUE) {
                        $zip->extractTo($webroot_dir->path);
                        $zip->close();
                        $arr['success'] = '1';
                        $arr['msg'] = "'$zipFile' ".__("file is extracted. Please click on next.");
                    } else {
                        $arr['error'] = '1';
                        $arr['msg'] = __('Can not extract the zip file');
                    }
                }
            }else{
                $arr['error'] = '1';
                $arr['msg'] = __('Invalid addon name');
            }
            echo json_encode($arr);exit;
        }
    }
    
    function import_addonTable(){
        $this->layout = 'ajax';
        if(isset($this->request->data) && !empty($this->request->data)){
            $addon = $this->request->data['addon'];
            $arr = array();
            $addonfolder = $this->addonZipFile[$addon];
            if($addon == 'timeloggold'){
				if(TLG != 1 && TPAY != 1){
					$addon_dir = new Folder(WEB_ROOT.DS.$addonfolder, true, 0777);
					$sqlFile = $addon_dir->find('payment.sql');
					$sql = new File($addon_dir->pwd() . DS . $sqlFile[0]);
					$sqlContents = $sql->read();
					if($sqlContents != ''){
						$db = ConnectionManager::getDataSource('default');
						if($db->execute($sqlContents)){
							$sql->delete();
						}
					}
				}else{
                    $addon_dir = new Folder(WEB_ROOT.DS.$addonfolder, true, 0777);
                    $tsqlFile = $addon_dir->find('payment.sql');
                    $tsql = new File($addon_dir->pwd() . DS . $sqlFile[0]);
                    $tsql->delete();
				}
				if(GNC != 1){
					$db = ConnectionManager::getDataSource('default');
					$db->execute('ALTER TABLE `easycases` ADD `gantt_start_date` DATETIME NULL DEFAULT NULL AFTER `assign_to`;');
				}
            }
            if($addon === 'ganttchart' && GTLG !== 1){
                $db = ConnectionManager::getDataSource('default');
                $db->execute('ALTER TABLE `easycases` ADD `gantt_start_date` DATETIME NULL DEFAULT NULL AFTER `assign_to`;');
                $db->execute('ALTER TABLE `easycases` CHANGE `due_date` `due_date` datetime NULL AFTER `assign_to`;');
            }
            if($addon == 'invoicepro'){
                if(INV != 1){
                    $addon_dir = new Folder(WEB_ROOT.DS.$addonfolder, true, 0777);
                    $sqlFile = $addon_dir->find('invoice.sql');
                    $sql = new File($addon_dir->pwd() . DS . $sqlFile[0]);
                    $sqlContents = $sql->read();
                    if($sqlContents != ''){
                            $db = ConnectionManager::getDataSource('default');
                            if($db->execute($sqlContents)){
                                    $sql->delete();
                            }
                    }
                }else{
                    $addon_dir = new Folder(WEB_ROOT.DS.$addonfolder, true, 0777);
                        $tsqlFile = $addon_dir->find('invoice.sql');
                    $tsql = new File($addon_dir->pwd() . DS . $sqlFile[0]);
                    $tsql->delete();
                }
                if(TLG ==1 || TPAY ==1 || GTLG == 1){
                    $db = ConnectionManager::getDataSource('default');
                    
                    $db->execute("ALTER TABLE `log_times` ADD `auto_generate_invoice` TINYINT(2) NOT NULL DEFAULT '0' AFTER `is_billable`;");
                }
                if(defined('DBRD') && DBRD != 1){
                    $db = ConnectionManager::getDataSource('default');
                    
                    $db->execute("ALTER TABLE `companies` ADD `is_allowed` INT(11) NOT NULL DEFAULT '0' COMMENT '0:not allowed--1:allowed' AFTER `is_deactivated`;");
                    $db->execute("ALTER TABLE `projects` ADD `rate` FLOAT(5,2) NULL DEFAULT NULL AFTER `isactive`;");
                    $db->execute("ALTER TABLE `projects` ADD `invoice_customer_id` INT(11) NULL DEFAULT '0' AFTER `rate`;");
                    $db->execute("ALTER TABLE `invoice_customers` CHANGE `project_id` `project_id` INT(11) NULL DEFAULT NULL;");
                }
            }
            if($addon == 'invoicepro' || $addon == 'timeloggold' || $addon == 'executivedashboard'){
                $this->loadModel('Company');
                $companyTableFields = $this->Company->getColumnTypes();
                if(!array_key_exists('work_hour', $companyTableFields)){
                $db = ConnectionManager::getDataSource('default');
                    $db->execute("ALTER TABLE `companies` ADD `work_hour` FLOAT(5,2) NOT NULL DEFAULT '8.00' AFTER `contact_phone`;");
                }
            }
            if($addon == 'timelog' || $addon == 'timelogpaymenmt' || $addon == 'timeloggold'){
                if(GINV == 1){
                    $db = ConnectionManager::getDataSource('default');                   
                    $db->execute("ALTER TABLE `log_times` ADD `auto_generate_invoice` TINYINT(2) NOT NULL DEFAULT '0' AFTER `is_billable`;");
                }
            }
            $addon_dir = new Folder(WEB_ROOT.DS.$addonfolder, true, 0777);
            $sqlFile = $addon_dir->find($this->addonSql[$addon].'.sql');
            $sql = new File($addon_dir->pwd() . DS . $sqlFile[0]);
            if(!empty($sql)){
                $sqlContents = $sql->read();
                if($sqlContents != ''){
                    $db = ConnectionManager::getDataSource('default');
                    if($db->execute($sqlContents)){
                        $sql->delete();
                        $arr['success'] = '1';
                        $arr['msg'] = __('Databse configuration is completed. Please click on next.');
                    }
                }else{
                    $arr['error'] = '1';
                    $arr['msg'] = __('No content in the sql file.');
                }
            }else{
                $arr['error'] = '1';
                $arr['msg'] = __('No sql file is present in the zip file.');
            }
            echo json_encode($arr);exit;
        }
    }
    
    function copy_addonFolder(){
        $this->layout = 'ajax';
        if(isset($this->request->data) && !empty($this->request->data)){
            $addon = $this->request->data['addon'];
            $arr = array();
            $addonArcfolder = $this->addonZipFile[$addon];
            $addon_folder = new Folder(WEB_ROOT.DS.$addonArcfolder, true, 0777);
            if(!empty($addon_folder)){
                $dest = PLUGIN;
                $suc = $addon_folder->copy(array(
                    'to' => $dest,
                    'from' => $addon_folder->path,
                    'mode' => 0755,
                    'skip' => array(),
                    'scheme' => Folder::OVERWRITE,
                    'recursive' => true
                ));
                if($suc){
                    $addon_Delfolder = new Folder(WEB_ROOT.DS.$addonArcfolder, true, 0777);
                    $addon_Delfolder->delete();
                    $zipFile = $this->addonZipFile[$addon].'.zip';
                    $webroot_dir = new Folder(WEB_ROOT);
                    $zipfilespresent = $webroot_dir->find($zipFile);
                    $zipF = new File($webroot_dir->pwd() . DS . $zipfilespresent[0], true, 0777);
                    $zipF->delete();
                    $addonNm = $this->addonName[$addon];
                    $arr['success'] = '1';
                    $arr['msg'] = __("Please click on verify to check it is installed properly or not.");
                }else{
                    $arr['error'] = '1';
                    $arr['msg'] = __('Can not copy the folder.');
                }
            }else{
                $arr['error'] = '1';
                $arr['msg'] = __('No add-on Folder is present in the zip file.');
            }
            echo json_encode($arr);exit;
        }
    }
    
    function verify_addonInstalled(){
        $this->layout = 'ajax';
        ini_set('display_errors', 0);
        if(isset($this->request->data) && !empty($this->request->data)){
            $addon = $this->request->data['addon'];
            if($this->clear_cache() === TRUE){
                $arr['success'] = '1';
                if($addon == 'invoice'){
                    $arr['msg'] = __("Congratulations! You have successfully installed")." $addonNm ".__("add-on").". ".__("Now you have to install 'wkhtmltopdf' in your server and need to update that path in 'constants.php' file inside 'app/Config' folder in order to generate pdf of invoices. Please click on finish.");
                }else if($addon == 'chat'){
				  $arr['msg'] = __("Congratulations! You have successfully installed the chat add-on. Now you have to install 'Node.js' in your server and need to update 'NODEJS_HOST' path with 3002 port(eg:http://yourdomain:3002) in 'constants.php' file inside 'app/Config' folder in order to communicate with Node Host. Please refer 'readme.txt' file for install Node.js. Please click on finish.");
				}else{
                    $arr['msg'] = __("Congratulations! You have successfully installed")." $addonNm ".__("add-on").". ".__("Please click on finish.");
                }
            }else{
                $arr['error'] = '1';
                $arr['msg'] = __("Oops! The")." $addonNm ".__("add-on is not installed properly.");
            }
            echo json_encode($arr);exit;
        }
    }
    
    public function clear_cache() {
        Cache::clear();
        clearCache();

        $files = array();       
        $files = array_merge($files, glob(CACHE . 'models' . DS . '*'));  // remove cached models           
        $files = array_merge($files, glob(CACHE . 'persistent' . DS . '*'));  // remove cached persistent           

        foreach ($files as $f) {
            if (is_file($f)) {
                unlink($f);
            }
        }

        if(function_exists('apc_clear_cache')):      
        apc_clear_cache();
        apc_clear_cache('user');
        endif;

        $this->set(compact('files'));
        $this->layout = 'ajax';
        return TRUE;
    }

}
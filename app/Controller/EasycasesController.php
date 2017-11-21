<?php

/* * *******************************************************************************
 * Orangescrum Community Edition is a web based Project Management software developed by
 * Orangescrum. Copyright (C) 2013-2017
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
 * ****************************************************************************** */

/* * *******************************************************************************
 * Description:  Defines the Task controller
 * Portions created by Orangescrum are Copyright (C) Orangescrum.
 * All Rights Reserved.
 * ****************************************************************************** */
App::uses('AppController', 'Controller');
App::import('Vendor', 's3', array('file' => 's3' . DS . 'S3.php'));

App::import('Vendor', 'ElephantIO', array('file' => 'ElephantIO' . DS . 'Client.php'));

//use ElephantIO\Client as ElephantIOClient;

class EasycasesController extends AppController {

    public $name = 'Easycase';
    public $components = array('Format', 'Postcase', 'Sendgrid');

    function help() {
        if ($this->Auth->User("id")) {
            $this->layout = 'default_inner';
        } else {
            $this->layout = 'default_outer';
        }
        $this->loadModel('Help');
        $this->loadModel('Subject');
        if (trim(@$_GET['search_help_txt'])) {
            $search = urldecode(trim(htmlentities(strip_tags($_GET['search_help_txt']))));
        }
        if (isset($this->params['pass']['0']) && intval($this->params['pass']['0'])) {
            $subjectId = $this->params['pass']['0'];
        } else {
            $subjectId = 1;
        }
        $conditions = array();
        if (trim($search)) {
            $conditions = array('OR' => array('Help.title LIKE' => '%' . $search . '%', 'Help.description LIKE' => '%' . $search . '%'));
            $getSearchResult = $this->Help->searchResults($conditions);
        } else {
            $conditions = '';
            $getSearchResult = '';
        }
        $allSubjectData = $this->Subject->getAllSubjects();
        $allHelpData = $this->Help->getAllHelps($subjectId);
        $getSubjectName = $this->Subject->subjectName($subjectId);
        $this->set("allSubjectData", $allSubjectData);
        $this->set("subjectId", $subjectId);
        $this->set("subject_name", $getSubjectName);

        if ($getSearchResult && count($getSearchResult) > 0) { //If search result data present then display results
            $this->set("isSearchresult", 1);
            $this->set("allHelpData", $getSearchResult);
        } else {  //If no data present then display no data message
            $this->set("isSearchresult", 0);
            $this->set("allHelpData", $allHelpData);
        }
    }

    function ajax_check_size() {
        $this->layout = '';
    }

    function exportcsv() {
        $this->layout = '';
        exit;
    }

    function archive_case() {
        $this->layout = 'ajax';
        $id = $this->params['data']['id'];
        $cno = $this->params['data']['cno'];
        $pid = $this->params['data']['pid'];

        $arcCaseTitle = $this->Easycase->getCaseTitle($pid, $cno);

        $this->Easycase->query("UPDATE easycases SET isactive='0' WHERE id=" . $id);

        $CaseActivity = ClassRegistry::init('CaseActivity');
        $CaseActivity->recursive = -1;
        $CaseActivity->query("UPDATE case_activities SET isactive='0' WHERE project_id=" . $pid . " AND case_no=" . $cno);

        $CaseRecent = ClassRegistry::init('CaseRecent');
        $CaseRecent->recursive = -1;
        $CaseRecent->query("DELETE FROM case_recents WHERE easycase_id=" . $id);

        $easycs_mlst_cls = ClassRegistry::init('EasycaseMilestone');
        $easycs_mlst_cls->recursive = -1;
        $easycs_mlst_cls->query("DELETE FROM easycase_milestones WHERE easycase_id=" . $id . " AND project_id=" . $pid);

        $CaseUserView = ClassRegistry::init('CaseUserView');
        $CaseUserView->recursive = -1;
        $CaseUserView->query("DELETE FROM case_user_views WHERE easycase_id=" . $id);
        $Archive = ClassRegistry::init('Archive');
        $Archive = ClassRegistry::init('Archive');
        $Archive->recursive = -1;
        $arc = $Archive->query("SELECT * FROM archives WHERE easycase_id ='" . $id . "' AND type='2'");
        //echo $arc['0']['archives']['id'];exit;
        if (isset($arc) && count($arc) != "0") {
            $Archive = ClassRegistry::init('Archive');
            $Archive->query("UPDATE archives SET type='1' WHERE id='" . $arc['0']['archives']['id'] . "'");
        } else {
            $Archive = ClassRegistry::init('Archive');
            $Archive->recursive = -1;
            $CaseArc['easycase_id'] = $id;
            $CaseArc['case_file_id'] = "0";
            $CaseArc['type'] = "1";
            $CaseArc['user_id'] = SES_ID;
            $CaseArc['dt_created'] = GMT_DATETIME;
            $CaseArc['company_id'] = SES_COMP;
            $Archive->saveAll($CaseArc);
        }

        //socket.io implement start
        $Project = ClassRegistry::init('Project');
        $ProjectUser = ClassRegistry::init('ProjectUser');
        $ProjectUser->recursive = -1;

        $getUser = $ProjectUser->query("SELECT user_id FROM project_users WHERE project_id='" . $pid . "'");
        $prjuniq = $Project->query("SELECT uniq_id, short_name FROM projects WHERE id='" . $pid . "'");
        $prjuniqid = $prjuniq[0]['projects']['uniq_id']; //print_r($prjuniq);
        $projShName = strtoupper($prjuniq[0]['projects']['short_name']);
        $channel_name = $prjuniqid;
        $pname = $this->Format->getProjectName($projId);
        $msgpub = "'Case Replay Available in '" . $postParam['Easycase']['title'] . "''";

        $this->Postcase->iotoserver(array('channel' => $channel_name, 'message' => 'Updated.~~' . SES_ID . '~~' . $cno . '~~' . 'ARC' . '~~' . $arcCaseTitle . '~~' . $projShName));
        //socket.io implement end
        echo "success";
        exit;
    }

#### DELETING CASES CREATED BY LOGGED IN USER OR LOGGED IN AS ADMIN USER #######

    function delete_case() {
        $this->layout = 'ajax';
        $id = $this->params['data']['id'];
        $cno = $this->params['data']['cno'];
        $pid = $this->params['data']['pid'];
        $this->Easycase->recursive = -1;
        $case_list = $this->Easycase->query('SELECT id FROM easycases WHERE case_no=' . $cno . " AND project_id = " . $pid);
        if ($case_list) {
            foreach ($case_list AS $key => $val) {
                $arr[] = $val['easycases']['id'];
            }
        }
        $delCsTitle = $this->Easycase->getCaseTitle($pid, $cno);

        $this->Easycase->query("DELETE FROM easycases WHERE case_no = $cno AND project_id = $pid ");

        $CaseActivity = ClassRegistry::init('CaseActivity');
        $CaseActivity->recursive = -1;
        $CaseActivity->query("DELETE FROM case_activities WHERE project_id=" . $pid . " AND case_no=" . $cno);
        $CaseRecent = ClassRegistry::init('CaseRecent');
        $CaseRecent->recursive = -1;
        $CaseRecent->query("DELETE FROM case_recents WHERE easycase_id IN (" . implode(',', $arr) . ") AND project_id= $pid");

        $CaseUserView = ClassRegistry::init('CaseUserView');
        $CaseUserView->recursive = -1;
        $CaseUserView->query("DELETE FROM case_user_views WHERE easycase_id IN (" . implode(',', $arr) . ") AND project_id= $pid");

        $easycs_mlst_cls = ClassRegistry::init('EasycaseMilestone');
        $easycs_mlst_cls->recursive = -1;
        $easycs_mlst_cls->query("DELETE FROM easycase_milestones WHERE easycase_id=" . $id . " AND project_id=" . $pid);

        $casefiles = ClassRegistry::init('CaseFile');
        $casefiles = ClassRegistry::init('CaseFile');
        $casefiles->recursive = -1;
        $cfiles = $casefiles->query("SELECT * FROM case_files WHERE easycase_id IN (" . implode(',', $arr) . ")");
        if ($cfiles) {
            foreach ($cfiles AS $k => $v) {
                @unlink(DIR_FILES . "case_files/" . $v['case_files']['file']);
            }
        }
        $casefiles->query("DELETE FROM case_files WHERE easycase_id IN (" . implode(',', $arr) . ")");

        //By Orangescrum
        //Delete records from case file drive table.
        $this->loadModel('CaseFileDrive');
        $condition = array('CaseFileDrive.easycase_id' => $arr);
        $deleteGoogle = $this->CaseFileDrive->deleteRows($condition);

        /* $Archive = ClassRegistry::init('Archive');
          $Archive = ClassRegistry::init('Archive');
          $Archive->recursive = -1;
          $arc=$Archive->query("SELECT * FROM archives WHERE easycase_id ='".$id."' AND type='2'");
          //echo $arc['0']['archives']['id'];exit;
          if(isset($arc) && count($arc)!="0")
          {
          $Archive = ClassRegistry::init('Archive');
          $Archive->query("UPDATE archives SET type='1' WHERE id='".$arc['0']['archives']['id']."'");
          }else{
          $Archive = ClassRegistry::init('Archive');
          $Archive->recursive = -1;
          $CaseArc['easycase_id'] = $id;
          $CaseArc['case_file_id'] = "0";
          $CaseArc['type'] = "1";
          $CaseArc['user_id'] =SES_ID;
          $CaseArc['dt_created'] = GMT_DATETIME;
          $CaseArc['company_id'] = SES_COMP;
          $Archive->saveAll($CaseArc);

          } */
        //socket.io implement start
        $Project = ClassRegistry::init('Project');
        $ProjectUser = ClassRegistry::init('ProjectUser');
        $ProjectUser->recursive = -1;

        $getUser = $ProjectUser->query("SELECT user_id FROM project_users WHERE project_id='" . $pid . "'");
        $prjuniq = $Project->query("SELECT uniq_id, short_name FROM projects WHERE id='" . $pid . "'");
        $prjuniqid = $prjuniq[0]['projects']['uniq_id'];
        $projShName = strtoupper($prjuniq[0]['projects']['short_name']);
        //$channel_name = 'my_channel_delete_case';
        $channel_name = $prjuniqid;

        $this->Postcase->iotoserver(array('channel' => $channel_name, 'message' => 'Updated.~~' . SES_ID . '~~' . $cno . '~~' . 'DEL' . '~~' . $delCsTitle . '~~' . $projShName));
        //socket.io implement end
        echo "success";
        exit;
    }

    /**
     * This method gets the total storage used by user.
     *
     * @author Orangescrum
     * @method ajax_getStorage
     * @param
     * @return string
     */
    function ajax_getStorage() {
        App::import('Model', 'CaseFile');
        $CaseFile = new CaseFile();
        $usedspace = $CaseFile->getStorage();
        echo $usedspace;
        exit;
    }

    function archive_file() {
        $this->layout = 'ajax';
        $id = $this->params['data']['id'];

        $CaseFile = ClassRegistry::init('CaseFile');
        $CaseFile->recursive = -1;
        $CaseFile->query("UPDATE case_files SET isactive='0' WHERE id=" . $id);
        $Archive = ClassRegistry::init('Archive');
        $Archive->recursive = -1;
        $arc = $Archive->query("SELECT * FROM archives WHERE case_file_id ='" . $id . "' AND type='2'");


        if (isset($arc) && count($arc) != "0") {
            $Archive = ClassRegistry::init('Archive');
            $Archive->query("UPDATE archives SET type='1' WHERE case_file_id='" . $id . "'");
        } else {
            $Archive = ClassRegistry::init('Archive');
            $Archive->recursive = -1;
            $CaseArc['easycase_id'] = "0";
            $CaseArc['case_file_id'] = $id;
            $CaseArc['type'] = "1";
            $CaseArc['user_id'] = SES_ID;
            $CaseArc['dt_created'] = GMT_DATETIME;
            $CaseArc['company_id'] = SES_COMP;
            $Archive->saveAll($CaseArc);
        }

        $getFiles = $CaseFile->find('first', array('conditions' => array('CaseFile.id' => $id)));
        $checkFiles = $CaseFile->find('all', array('conditions' => array('CaseFile.easycase_id' => $getFiles['CaseFile']['easycase_id'], 'CaseFile.isactive' => 1)));
        if (count($checkFiles) == 0) {
            $this->Easycase->query("UPDATE easycases SET format='2' WHERE id='" . $getFiles['CaseFile']['easycase_id'] . "'");
        } else {
            $this->Easycase->query("UPDATE easycases SET format='1' WHERE id='" . $getFiles['CaseFile']['easycase_id'] . "'");
        }


        echo "success";
        exit;
    }

    function ajaxpostcase($oauth_arg = NULL) {

        $this->layout = 'ajax';
        if (isset($this->params['data']['CS_project_id']) && $this->params['data']['CS_project_id'] && $this->params['data']['CS_project_id'] != "all") {
            $CS_project_id = $this->params['data']['CS_project_id'];
        } elseif (isset($oauth_arg['CS_project_id'])) {
            $CS_project_id = $oauth_arg['CS_project_id'];
        } else {
            $CS_project_id = $this->params['data']['pid'];
        }

        $oauth_return = 0;
        if (isset($oauth_arg) && !empty($oauth_arg)) {
            $arr = $oauth_arg;
            $oauth_return = 1;
            $this->loadModel('UserSubscription');
            $limitation = $this->UserSubscription->find('first', array('conditions' => array('company_id' => SES_COMP), 'order' => 'id DESC'));
            $GLOBALS['Userlimitation'] = $limitation['UserSubscription'];
        } else {
            $CS_istype = $this->params['data']['CS_istype'];
            $CS_title = $this->Format->convert_ascii($this->params['data']['CS_title']);
            $CS_type_id = $this->params['data']['CS_type_id'];
            $CS_priority = $this->params['data']['CS_priority'];
            $CS_assign_to = $this->params['data']['CS_assign_to'];
            $msg = trim($this->params['data']['CS_message']);
            $msg = preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $msg);
            $msg = preg_replace('/(<br \/>)+$/', '', $msg);
            $this->params['data']['CS_message'] = $msg;
            $CS_message = $msg;
            $CS_due_date = $this->params['data']['CS_due_date'];
            $CS_milestone = $this->params['data']['CS_milestone'];
            $CS_legend = 1;
            if (isset($this->params['data']['CS_legend'])) {
                $CS_legend = $this->params['data']['CS_legend'];
            }
            $pagename = $this->params['data']['pagename'];
            $arr = $this->params['data'];
            if ($this->data['CS_type_id'] == 10) {
                $arr['CS_legend'] = 1;
            }

            if ($this->params['data']['user_auth_key']) {
                $this->loadModel('User');
                $getuser = $this->User->find('first', array('copnditions' => array('User.uniq_id' => $this->params['data']['user_auth_key'])));
                if ($getuser['User']['id']) {
                    $arr['CS_user_id'] = $getuser['User']['id'];
                }
            }

            $arr['CS_message'] = $msg;

            //By Orangescrum
            if (isset($this->params->query['data']['Easycase']['cloud_storage_files']))
                $arr['cloud_storages'] = $this->params->query['data']['Easycase']['cloud_storage_files'];
        }

        if (trim($CS_project_id)) {

            $value = $this->Postcase->casePosting($arr);
            if (intval($oauth_return)) {
                return $value;
            } else {
                echo $value;
            }
        }
        exit;
    }

    function download($files = NULL) {
        $this->layout = 'ajax';
        $this->Format->downloadFile($files);
        exit;
    }

    function downloadfiles($files = NULL) {
        $this->loadModel('CaseFile');
        $getFiles = $this->CaseFile->findByFile($files);
        if (!empty($getFiles)) {
            $this->layout = 'ajax';
            $this->Format->downloadFile($files);
            exit;
        } else {
            echo "$files has been moved permanently";
            exit;
        }
    }

    function downloadImgFile($files = NULL) {
        if (file_exists(DIR_CASE_FILES . $files)) {
            $file_path = DIR_CASE_FILES . $files;
            header("Content-type: image/jpeg");
            $content = file_get_contents($file_path);
            print $content;
            exit;
        } else {
            $var = "<table align='center' width='100%'><tr><td style='font:bold 12px verdana;color:#FF0000;' align='center'>Oops! File not found.<br/> File may be deleted or make sure you specified correct file name.</td></tr></table>";
            die($var);
        }
    }

    function comment_edit() {
        $this->layout = 'ajax';
        $comments = $this->params['data']['comments'];
        $cmntid = $this->params['data']['cmntid'];

        $this->loadModel('CaseComment');
        $CaseComment['id'] = $cmntid;
        $CaseComment['comments'] = $comments;
        $this->CaseComment->save($CaseComment);

        echo $comments;
        exit;
    }

    function comment() {
        $this->layout = 'ajax';
        $comments = $this->params['data']['comments'];
        $repid = $this->params['data']['repid'];
        $csid = $this->params['data']['csid'];
        $fileArray = $this->params['data']['allFiles'];
        $count = $this->params['data']['count'];

        $this->loadModel('CaseComment');
        $CaseComment['easycase_id'] = $repid;
        $CaseComment['comments'] = $comments;
        $CaseComment['user_id'] = SES_ID;
        $CaseComment['dt_created'] = GMT_DATETIME;
        $CaseComment['isactive'] = 1;
        $this->CaseComment->save($CaseComment);

        $cmntId = $this->CaseComment->getLastInsertID();

        $allfiles = "";
        if (is_array($fileArray) && count($fileArray)) {
            $allfiles = $this->Postcase->uploadAndInsertFile($fileArray, $repid, $cmntId);
        }
        if ($allfiles) {
            $caseDataArr = $this->Easycase->query("UPDATE easycases SET format='1' WHERE id=" . $csid);
        }
        $caseDataArr1 = $this->Easycase->query("UPDATE easycases SET dt_created='" . GMT_DATETIME . "' WHERE id=" . $csid);
        $caseDataArr = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $csid), 'fields' => array('Easycase.id', 'Easycase.case_no', 'Easycase.project_id', 'Easycase.type_id', 'Easycase.priority', 'Easycase.title', 'Easycase.uniq_id')));

        $caseStsId = $caseDataArr['Easycase']['id'];
        $caseStsNo = $caseDataArr['Easycase']['case_no'];
        $closeStsPid = $caseDataArr['Easycase']['project_id'];
        $closeStsTyp = $caseDataArr['Easycase']['type_id'];
        $closeStsPri = $caseDataArr['Easycase']['priority'];
        $closeStsTitle = $caseDataArr['Easycase']['title'];
        $caseuniqid = $caseDataArr['Easycase']['uniq_id'];

        $CaseActivity = ClassRegistry::init('CaseActivity');
        $CaseActivity->recursive = -1;
        $CaseAct['comment_id'] = $cmntId;
        $CaseAct['user_id'] = SES_ID;
        $CaseAct['project_id'] = $closeStsPid;
        $CaseAct['case_no'] = $caseStsNo;
        $CaseAct['type'] = 7;
        $CaseAct['dt_created'] = GMT_DATETIME;
        $CaseActivity->saveAll($CaseAct);

        $usrArr = array();
        $usr_names = "";
        $ids = "";
        $usrMem = array();


        $getEmailUser = $this->Format->getAllNotifyUser($closeStsPid, 'reply');
        $name_email = "";
        $usrArr = array();
        foreach ($getEmailUser as $usrMem) {
            if (isset($usrMem['User']['name']) && $usrMem['User']['name']) {
                array_push($usrArr, $usrMem['User']);
                $usr_names .= trim($usrMem['User']['name']) . ", ";
            }
        }
        $usr_names = trim(trim($usr_names), ",");
        if (count($usrArr)) {
            $emailType = "Comment";
            //$msg = "<font color='#737373' style='font-weight:bold'>Status:</font> <font color='#000' style='font:normal 12px verdana;'>Commented</font>";
            $msg = "";
            $emailbody = "<font color='#000000' style='font:normal 12px verdana;'>Commented</font> on the reply";

            //$usrArr = array_unique($usrArr);
            foreach ($usrArr as $usr) {
                $allfiles = "";
                $emailMsg = "";
                $assignTo = "";
                $this->Postcase->generateMsgAndSendMail($usr['id'], $allfiles, $caseStsNo, $closeStsTitle, $comments, $closeStsPid, $closeStsPri, $closeStsTyp, $msg, $emailbody, $assignTo, $usr_names, $caseuniqid, $emailType, $usr['email'], $usr['name']);
            }
        }

        $this->set('cmntId', $cmntId);
        $this->set('comments', $comments);
        $this->set('repid', $repid);
        $this->set('count', $count);
    }

    function fileremove($oauth_arg = NULL) {
        $this->layout = 'ajax';
        $filename = (isset($oauth_arg) && trim($oauth_arg)) ? $oauth_arg : $this->params['data']['filename'];
        if ($filename && strstr($filename, "|")) {
            $fl = explode("|", $filename);
            if (isset($fl['0'])) {
                $file = $fl['0'];
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $folder_orig_Name = 'files/case_files/' . trim($file);
                //$info = $s3->getObjectInfo(BUCKET_NAME, $folder_orig_Name,S3::ACL_PRIVATE);
                // if ($info){
                if ($s3->deleteObject(BUCKET_NAME, $folder_orig_Name, S3::ACL_PRIVATE)) {
//							if(isset($fl[2])){
//								$CaseFile = ClassRegistry::init('CaseFile')->deleteAll(array('CaseFile.id'=>$fl[2],'CaseFile.company_id'=>SES_COMP));
//								if($CaseFile){
//									echo "File Removed From db ";
//								}else{
//									echo "Error in removing File From db ";
//								}
//							}
                    echo "success";
                } else {
                    echo 'Error';
                    exit;
                }
//                    }else{
//						echo "Failure";exit;
//					}
                /* if(file_exists(DIR_CASE_FILES.$file)) {
                  if(unlink(DIR_CASE_FILES.$file)) {
                  //echo "Success";
                  }

                  } */
            }
        }
        exit;
    }

    function fileupload($oauth_arg = NULL) {
        $this->layout = 'ajax';

        //echo json_encode($oauth_arg); exit;

        $size = (isset($oauth_arg['case_files']['size'])) ? $oauth_arg['case_files']['size'] : $this->params['data']['Easycase']['case_files']['size'];
        $sizeinkb = $size / 1024;

        $storageExceeds = 0;
        $totalStorage = 0;
        $allowusage = "Unlimited";
        if (isset($oauth_arg['usedstorage']) && isset($oauth_arg['allowusage'])) {
            $usedstorage = $oauth_arg['usedstorage'];
            $allowusage = $oauth_arg['allowusage'];
        } else {
            if (!$oauth_arg) {
                $usedstorage = $this->Easycase->usedSpace('', SES_COMP);
                $allowusage = $GLOBALS['user_subscription']['storage'];
            }
        }
        if ($allowusage != 'Unlimited') {
            $usedstorageMb = $usedstorage + ($sizeinkb / 1024);
            if ($usedstorageMb > $allowusage) {
                $storageExceeds = number_format($usedstorageMb - $allowusage, 2);
            }
            $totalStorage = number_format($usedstorageMb, 2);
        }

        $name = (isset($oauth_arg['case_files']['name'])) ? $oauth_arg['case_files']['name'] : $this->params['data']['Easycase']['case_files']['name'];
        $tmp_name = (isset($oauth_arg['case_files']['tmp_name'])) ? $oauth_arg['case_files']['tmp_name'] : $this->params['data']['Easycase']['case_files']['tmp_name'];

        $type = (isset($oauth_arg['case_files']['type'])) ? $oauth_arg['case_files']['type'] : $this->params['data']['Easycase']['case_files']['type'];

        $file_path = WWW_ROOT . 'files/case_files/';

        $newFileName = "";
        $updateData = "";
        $message = "success";
        $displayname = "";
        $allowedSize = MAX_FILE_SIZE * 1024;
        if ($storageExceeds <= 0) {
            if ($sizeinkb <= $allowedSize) {
                if ($name) {
                    $oldname = $this->Format->chnageUploadedFileName($name);
                    $ext1 = substr(strrchr($oldname, "."), 1);

                    $message = $this->Format->validateFileExt($ext1);
                    if ($message == "success") {
                        $tot = strlen($oldname);
                        $extcnt = strlen($ext1);
                        $end = $tot - $extcnt - 1;
                        $onlyfile = substr($oldname, 0, $end);

                        $CaseFile = ClassRegistry::init('CaseFile');
                        $CaseFile->recursive = -1;

                        $checkFile = $CaseFile->query("SELECT id,count FROM case_files as CaseFile WHERE file='$oldname'");
                        if (count($checkFile) >= 1) {
                            $newCount = $checkFile['0']['CaseFile']['count'] + 1;
                            $newFileName = $onlyfile . "(" . $newCount . ")." . $ext1;
                            $updateData = "|" . $sizeinkb . "|" . $checkFile['0']['CaseFile']['id'] . "|" . $newCount;
                        } else {
                            $newFileName = $oldname;
                            $updateData = "|" . $sizeinkb;
                        }

                        $file = $file_path . $newFileName;
                        if (USE_S3 == 0) {
                            copy($tmp_name, $file);
                        } else {
                            try {
                                // s3 bucket  start
                                $s3 = new S3(awsAccessKey, awsSecretKey);
                                //$s3->putBucket(BUCKET_NAME, S3::ACL_PUBLIC_READ_WRITE);
                                $s3->putBucket(BUCKET_NAME, S3::ACL_PRIVATE);
                                $folder_orig_Name = DIR_CASE_FILES_S3_FOLDER_TEMP . trim($newFileName);
                                //$s3->putObjectFile($tmp_name,BUCKET_NAME ,$folder_orig_Name ,S3::ACL_PUBLIC_READ_WRITE);
                                //$s3->putObjectFile($tmp_name,BUCKET_NAME ,$folder_orig_Name ,S3::ACL_PUBLIC_READ);
                                $returnvalue = $s3->putObjectFile($tmp_name, BUCKET_NAME, $folder_orig_Name, S3::ACL_PRIVATE);
                                if (!$returnvalue) {
                                    $message = "s3_error";
                                    $subject = 'Error in uploading file to S3 bucket';
                                    $this->loadModel('User');
                                    $userdetails = $this->User->query('SELECT User.*,Company.name FROM users User,company_users AS CompanyUser,companies AS Company WHERE User.id=CompanyUser.user_id AND CompanyUser.company_id=Company.id AND CompanyUser.company_id=' . SES_COMP . ' AND User.id=' . SES_ID);
                                    $this->Email->delivery = EMAIL_DELIVERY;
                                    $this->Email->to = DEV_EMAIL;
                                    $this->Email->subject = $subject;
                                    $this->Email->from = FROM_EMAIL;
                                    $this->Email->template = 'fileupload_error';
                                    $this->set('f_size', $size);
                                    $this->set('f_type', $type);
                                    $this->set('f_name', $newFileName);
                                    $this->set('u_storage', $usedstorage);
                                    $this->set('allow_usage', $allowusage);
                                    $this->set('userdetails', $userdetails[0]);
                                    $this->Email->sendAs = 'html';
                                    $this->Sendgrid->sendgridsmtp($this->Email);
                                }
                            } catch (Exception $e) {
                                
                            }
                        }
                        //s3 bucket end
                        $displayname = $name;
                        if (strlen($name) >= 30) {
                            $displayname = substr($displayname, 0, 30);
                        }
                    }
                } else {
                    $message = "error";
                }
            } else {
                $message = "size";
            }
        } else {
            $message = "exceed";
        }
        echo '{"name":"' . $displayname . '","sizeinkb":"' . $sizeinkb . '","filename":"' . $newFileName . $updateData . '","message":"' . $message . '","storageExceeds":"' . $storageExceeds . '","totalStorage":"' . $totalStorage . '"}';
        exit;
    }

    function dashboard() {
        $caseLegendsort = "";
        if (SES_TYPE <= 2) {
            $proje_ids = array_keys($GLOBALS['active_proj_list']);
            $this->Easycase->recursive = -1;
            $task_count = $this->Easycase->find('count', array('conditions' => array('project_id' => $proje_ids)));
            if (!$task_count) {
                $this->redirect(HTTP_ROOT . 'onbording');
                exit;
            }
        }
        $arrLeftNav = array(0 => 'search', 1 => 'status', 2 => 'project', 3 => 'types', 4 => 'priority', 5 => 'members', 6 => 'top', 7 => 'statistics');
        if (isset($_GET['filter']) && $_GET['filter'] == "files") {
            $caseStatus = "attch";
            $this->Cookie->write('STATUS', "attch", '365 days');
        } elseif (isset($_GET['filter']) && $_GET['filter'] == "kanban") {
            $caseStatus = "kanban";
            $this->Cookie->write('STATUS', "kanban", '365 days');
        } else if (@$_COOKIE['STATUS']) {
            $caseStatus = $_COOKIE['STATUS'];
        } else {
            $caseStatus = "all";
        }
        if (@$_COOKIE['PRIORITY']) {
            $priorityFil = $_COOKIE['PRIORITY'];
        } else {
            $priorityFil = "all";
        }
        if (@$_COOKIE['CS_TYPES']) {
            $caseTypes = $_COOKIE['CS_TYPES'];
        } else {
            $caseTypes = "all";
        }
        if (@$_COOKIE['MEMBERS']) {
            $caseUserId = $_COOKIE['MEMBERS'];
        } else {
            $caseUserId = "all";
        }
        if (@$_COOKIE['ASSIGNTO']) {
            $caseAssignTo = $_COOKIE['ASSIGNTO'];
        } else {
            $caseAssignTo = "all";
        }
        if ($this->Cookie->read('IS_SORT')) {
            $isSort = $this->Cookie->read('IS_SORT');
        } else {
            $isSort = 0;
        }
        $milestoneIds = "all";
        if (@$_COOKIE['MILESTONES']) {
            $milestoneIds = $_COOKIE['MILESTONES'];
        }
        if (@$_COOKIE['DATE']) {
            $caseDateFil = $_COOKIE['DATE'];
        } else {
            $caseDateFil = "";
        }
        if (@$_COOKIE['DUE_DATE']) {
            $casedueDateFil = $_COOKIE['DUE_DATE'];
        } else {
            $casedueDateFil = "";
        }
        $caseDtlsSort = "";
        $caseDate = "";
        $caseTitle = "";
        $caseDueDate = "";
        $caseNum = "";
        $caseCreatedDate = "";
        if (isset($_GET['search']) && urldecode(trim($_GET['search']))) {
            $caseSearch = urldecode(trim(htmlentities(strip_tags($_GET['search']))));
            //$this->Cookie->write('SEARCH',$caseSearch,'365 days');
            setcookie('SEARCH', $caseSearch, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        } elseif (@$_COOKIE['SEARCH']) {
            $caseSearch = $_COOKIE['SEARCH'];
        } else {
            $caseSearch = "";
        }

        $caseMenuFilters = "";

        if (@$_COOKIE['CURRENT_FILTER'] && SES_TYPE == '1') {
            $caseMenuFilters = $_COOKIE['CURRENT_FILTER'];
        }
        if (isset($_GET['filters'])) {
            $caseMenuFilters = $_GET['filters'];
        }

        $this->set('caseDtlsSort', $caseDtlsSort);
        $caseSrch = "";
        $casePage = 1;
        $caseUniqId = "";

        $this->set('curProjId', PROJ_ID);
        $this->set('projUniq', PROJ_UNIQ_ID);
        $this->set('caseStatus', $caseStatus);
        $this->set('priorityFil', $priorityFil);
        $this->set('caseTypes', $caseTypes);
        $this->set('caseDate', $caseDate);
        $this->set('caseSearch', $caseSearch);
        $this->set('casePage', $casePage);
        $this->set('caseUniqId', $caseUniqId);
        $this->set('caseTitle', $caseTitle);
        $this->set('isSort', $isSort);
        $this->set('caseUserId', $caseUserId);
        $this->set('caseAssignTo', $caseAssignTo);
        $this->set('caseMenuFilters', $caseMenuFilters);
        $this->set('caseDueDate', $caseDueDate);
        $this->set('caseNum', $caseNum);
        $this->set('caseLegendsort', @$caseLegendsort);
        $this->set('milestoneIds', $milestoneIds);
        $this->set('caseDateFil', $caseDateFil);
        $this->set('casedueDateFil', $casedueDateFil);
        $this->set('caseCreatedDate', $caseCreatedDate);

        setcookie('DEFAULT_PAGE', 'dashboard', COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
    }

    function case_files() {
        $this->layout = 'ajax';
        $page_limit = CASE_PAGE_LIMIT;
        $projUniq = $this->params['data']['projFil']; // Project Uniq ID
        $casePage = $this->params['data']['casePage']; // Project Uniq ID


        $caseFileId = $this->params['data']['caseFileId'];
        $condnts = "";
        $file_srch = "";
        if (isset($caseFileId) && !empty($caseFileId)) {
            $condnts = "AND CaseFile.id='" . $caseFileId . "'";
            $file_srch = $this->params['data']['file_srch'];
        } else if (isset($this->params['data']['file_srch']) && !empty($this->params['data']['file_srch'])) {
            $file_srch = $this->params['data']['file_srch'];
            $condnts = "AND CaseFile.file LIKE '%" . trim($file_srch) . "%' ";
        }
        // get project ID from project uniq-id
        $curProjId = NULL;
        $curProjShortName = NULL;
        if ($projUniq != 'all') {
            $this->loadModel('ProjectUser');
            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $projUniq, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('Project.id', 'Project.short_name', 'ProjectUser.id')));
            if (count($projArr)) {
                $curProjId = $projArr['Project']['id'];
                $curProjShortName = $projArr['Project']['short_name'];
            }

            //Updating ProjectUser table to current date-time
            $projIsChange = $this->params['data']['projIsChange']; // Project Uniq ID
            if ($projIsChange != $projUniq) {
                $ProjectUser['id'] = $projArr['ProjectUser']['id'];
                $ProjectUser['dt_visited'] = GMT_DATETIME;
                $this->ProjectUser->save($ProjectUser);
            }
        }

        $page = $casePage;
        $limit1 = $page * $page_limit - $page_limit;
        $limit2 = $page_limit;

        if ($projUniq != 'all') {
            $caseAll = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.actual_dt_created,Easycase.istype,Easycase.project_id,Easycase.legend,CaseFile.*,Project.uniq_id FROM easycases as Easycase,case_files as CaseFile,projects as Project WHERE Easycase.id=CaseFile.easycase_id AND Easycase.project_id=Project.id AND Easycase.isactive='1' AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0 AND CaseFile.isactive='1' " . $condnts . " ORDER BY Easycase.actual_dt_created DESC LIMIT $limit1,$limit2");
        }
        if ($projUniq == 'all') {
            $caseAll = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.actual_dt_created,Easycase.istype,Easycase.project_id,Easycase.legend,CaseFile.*,Project.uniq_id FROM easycases as Easycase,case_files as CaseFile,projects as Project WHERE Easycase.id=CaseFile.easycase_id AND Project.id=Easycase.project_id AND Easycase.isactive='1' AND Easycase.project_id!=0 AND CaseFile.isactive='1' AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='" . SES_COMP . "') " . $condnts . " ORDER BY Easycase.actual_dt_created DESC LIMIT $limit1,$limit2");
        }
        $caseCount = $this->Easycase->query("SELECT FOUND_ROWS() as count");

        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $dt = $view->loadHelper('Datetime');
        $frmt = $view->loadHelper('Format');
        $cq = $view->loadHelper('Casequery');
        if (isset($caseAll) && !empty($caseAll)) {
            foreach ($caseAll as $key => $getdata) {
                if (isset($getdata['CaseFile']['downloadurl']) && trim($getdata['CaseFile']['downloadurl'])) {
                    $caseAll[$key]['fileurl'] = '';
                    $caseAll[$key]['file_name'] = $getdata['CaseFile']['file'];
                    $caseAll[$key]['link_url'] = '';
                    $caseAll[$key]['download_url'] = $getdata['CaseFile']['downloadurl'];
                    $is_google = strpos($getdata['CaseFile']['downloadurl'], "https://docs.google.com");
                    if ($is_google !== false) {
                        $caseAll[$key]['file_type'] = "gd";
                    }
                    $is_dropbox = strpos($getdata['CaseFile']['downloadurl'], "https://www.dropbox.com");
                    if ($is_dropbox !== false) {
                        $caseAll[$key]['file_type'] = "db";
                    }
                } else {
                    if ($frmt->validateImgFileExt($getdata['CaseFile']['file'])) {
                        if (USE_S3 == 0) {
                            $caseAll[$key]['fileurl'] = HTTP_CASE_FILES . $getdata['CaseFile']['file'];
                        } else {
                            $caseAll[$key]['fileurl'] = $frmt->generateTemporaryURL(DIR_CASE_FILES_S3 . $getdata['CaseFile']['file']);
                        }
                    }
                    $caseAll[$key]['file_name'] = $frmt->formatText($frmt->longstringwrap($this->Format->shortLength($getdata['CaseFile']['file'], 75)));
                    $caseAll[$key]['link_url'] = HTTP_ROOT . "easycases/download/" . $getdata['CaseFile']['file'];
                    $caseAll[$key]['download_url'] = '';
                    $caseAll[$key]['file_type'] = substr(strrchr(strtolower($getdata['CaseFile']['file']), "."), 1);
                }
                $caseAll[$key]['is_image'] = $frmt->validateImgFileExt($getdata['CaseFile']['file']);
                if ($getdata['CaseFile']['file_size'] !== '0.0')
                    $caseAll[$key]['file_size'] = $frmt->getFileSize($getdata['CaseFile']['file_size']);

                if ($getdata['Easycase']['user_id'] != SES_ID) {
                    $usrDtls = $cq->getUserDtls($getdata['Easycase']['user_id']);
                    $usrName = $frmt->formatText($usrDtls['User']['name']);
                } else {
                    $usrName = "me";
                }
                $caseAll[$key]['usrName'] = $frmt->formatText($usrName);

                $caseAll[$key]['is_archive'] = 0;
                if (SES_TYPE == 1 || SES_TYPE == 2 || ($getdata['Easycase']['legend'] == 1 && SES_ID == $getdata['Easycase']['user_id'])) {
                    $caseAll[$key]['is_archive'] = 1;
                }

                $caseAll[$key]['updatedCur'] = $updatedCur = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "date");
                $caseAll[$key]['inserted'] = $inserted = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $getdata['Easycase']['actual_dt_created'], "datetime");
                $caseAll[$key]['newUpdDt'] = $newUpdDt = date('Y-m-d', strtotime($inserted));
                $caseAll[$key]['newdt'] = $newdt = $dt->dateFormatOutputdateTime_day($newUpdDt, $updatedCur, 'date');
                $caseAll[$key]['activity'] = $dt->dateFormatOutputdateTime_day($inserted, $updatedCur, 'week');
                $caseAll[$key]['xct_activity'] = date('l, F d, Y', strtotime($inserted)) . " at " . date('h:i A', strtotime($inserted));
            }
        }

        $caseFiles['file_srch'] = $file_srch;
        $caseFiles['caseCount'] = $caseCount['0']['0']['count'];
        $caseFiles['caseAll'] = $caseAll;
        $caseFiles['page_limit'] = $page_limit;
        $caseFiles['casePage'] = $casePage;
        $caseFiles['total_files'] = $frmt->pagingShowRecords($caseCount['0']['0']['count'], $page_limit, $casePage);
        $this->set('caseFiles', json_encode($caseFiles));
    }

    function setCustomStatus() {
        $customfilterid = (isset($this->params['data']['customfilter'])) ? $this->params['data']['customfilter'] : '';
        $filter = array();
        if ($customfilterid) {
            $this->loadModel('CustomFilter');
            $getcustomfilter = "SELECT SQL_CALC_FOUND_ROWS * FROM custom_filters AS CustomFilter WHERE CustomFilter.company_id = '" . SES_COMP . "' and CustomFilter.user_id =  '" . SES_ID . "' and CustomFilter.id='" . $customfilterid . "' ORDER BY CustomFilter.dt_created DESC ";
            $getfilter = $this->CustomFilter->query($getcustomfilter);
            $filter['status'] = $getfilter[0]['CustomFilter']['filter_status'];
            $filter['priority'] = $getfilter[0]['CustomFilter']['filter_priority'];
            $filter['type'] = $getfilter[0]['CustomFilter']['filter_type_id'];
            $filter['member'] = $getfilter[0]['CustomFilter']['filter_member_id'];
            $filter['assignto'] = $getfilter[0]['CustomFilter']['filter_assignto'];
            $filter['date'] = $getfilter[0]['CustomFilter']['filter_date'];
            $filter['duedate'] = (isset($getfilter[0]['CustomFilter']['filter_duedate']) && $getfilter[0]['CustomFilter']['filter_duedate'] !== '0000-00-00 00:00:00') ? $getfilter[0]['CustomFilter']['filter_duedate'] : '';
        }
        print json_encode($filter);
        exit;
    }

    function case_project() {
        $this->layout = 'ajax';

        $resCaseProj = array();

        $page_limit = CASE_PAGE_LIMIT;
        $this->_datestime();

        $projUniq = $this->params['data']['projFil']; // Project Uniq ID
        $projIsChange = $this->params['data']['projIsChange']; // Project Uniq ID
        $caseStatus = $this->params['data']['caseStatus']; // Filter by Status(legend)
        $priorityFil = $this->params['data']['priFil']; // Filter by Priority
        $caseTypes = $this->params['data']['caseTypes']; // Filter by case Types
        $caseUserId = $this->params['data']['caseMember']; // Filter by Member
        $caseAssignTo = $this->params['data']['caseAssignTo']; // Filter by AssignTo
        $caseDate = $this->params['data']['caseDate']; // Sort by Date
        $caseSrch = $this->params['data']['caseSearch']; // Search by keyword

        $casePage = $this->params['data']['casePage']; // Pagination
        $caseUniqId = $this->params['data']['caseId']; // Case Uniq ID to close a case
        $caseTitle = $this->params['data']['caseTitle']; // Case Uniq ID to close a case
        $caseDueDate = $this->params['data']['caseDueDate']; // Sort by Due Date
        $caseNum = $this->params['data']['caseNum']; // Sort by Due Date
        $caseLegendsort = $this->params['data']['caseLegendsort']; // Sort by Case Status
        $caseAtsort = $this->params['data']['caseAtsort']; // Sort by Case Status
        $startCaseId = $this->params['data']['startCaseId']; // Start Case
        $caseResolve = $this->params['data']['caseResolve']; // Resolve Case
        $caseMenuFilters = $this->params['data']['caseMenuFilters']; // Resolve Case
        $milestoneIds = $this->params['data']['milestoneIds']; // Resolve Case
        $caseCreateDate = $this->params['data']['caseCreateDate']; // Sort by Created Date
        @$case_srch = $this->params['data']['case_srch'];
        @$case_date = $this->params['data']['case_date'];
        @$case_duedate = $this->params['data']['case_due_date'];
        @$milestone_type = $this->params['data']['mstype'];
        $changecasetype = $this->params['data']['caseChangeType'];
        $caseChangeDuedate = $this->params['data']['caseChangeDuedate'];
        $caseChangePriority = $this->params['data']['caseChangePriority'];
        $caseChangeAssignto = $this->params['data']['caseChangeAssignto'];
        $customfilterid = $this->params['data']['customfilter'];
        $detailscount = $this->params['data']['detailscount']; // Count number to open casedetails
        $filterenabled = 0;
        /* jyoti start */
        if ($customfilterid) {
            $this->loadModel('CustomFilter');
            $getcustomfilter = "SELECT SQL_CALC_FOUND_ROWS * FROM custom_filters AS CustomFilter WHERE CustomFilter.company_id = '" . SES_COMP . "' and CustomFilter.user_id =  '" . SES_ID . "' and CustomFilter.id='" . $customfilterid . "' ORDER BY CustomFilter.dt_created DESC ";
            $getfilter = $this->CustomFilter->query($getcustomfilter);
            //$tot = $this->CustomFilter->query("SELECT FOUND_ROWS() as total");
            //echo '<pre>';print_r($getfilter);
            //$this->set('getfilter',$getfilter);
            //$projUniq = $getfilter[0]['CustomFilter']['project_uniq_id'];
            if ($getfilter) {
                $caseStatus = $getfilter[0]['CustomFilter']['filter_status'];
                $priorityFil = $getfilter[0]['CustomFilter']['filter_priority'];
                $caseTypes = $getfilter[0]['CustomFilter']['filter_type_id'];
                $caseUserId = $getfilter[0]['CustomFilter']['filter_member_id'];
                $caseAssignTo = $getfilter[0]['CustomFilter']['filter_assignto'];
                $caseDate = $getfilter[0]['CustomFilter']['filter_date'];
                $case_duedate = $getfilter[0]['CustomFilter']['filter_duedate'];
                $caseSrch = $getfilter[0]['CustomFilter']['filter_search'];
            }
            $filterenabled = 1;
        }
        /* jyoti end */
        if ($caseMenuFilters) {
            //$this->Cookie->write('CURRENT_FILTER',$caseMenuFilters,'365 days');
            setcookie('CURRENT_FILTER', $caseMenuFilters, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        } else {
            //$this->Cookie->write('CURRENT_FILTER',$caseMenuFilters,'-365 days');
            setcookie('CURRENT_FILTER', $caseMenuFilters, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        }


        $caseUrl = $this->params['data']['caseUrl'];
        ######## get project ID from project uniq-id ################
        $curProjId = NULL;
        $curProjShortName = NULL;
        if ($projUniq != 'all') {
            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));

            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $projUniq, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('Project.id', 'Project.short_name', 'ProjectUser.id')));

            if (count($projArr)) {
                $curProjId = $projArr['Project']['id'];
                $curProjShortName = $projArr['Project']['short_name'];

                //Updating ProjectUser table to current date-time
                if ($projIsChange != $projUniq) {
                    $ProjectUser['id'] = $projArr['ProjectUser']['id'];
                    $ProjectUser['dt_visited'] = GMT_DATETIME;
                    $this->ProjectUser->save($ProjectUser);
                }
            }
        }

        ######### Filter by CaseUniqId ##########
        $qry = "";
        if (trim($caseUrl)) {
            $filterenabled = 1;
            $qry .= " AND Easycase.uniq_id='" . $caseUrl . "'";
        }
        ######### Filter by Status ##########
        if (trim($caseStatus) && $caseStatus != "all") {
            $filterenabled = 1;
            $qry .= $this->Format->statusFilter($caseStatus);
            $stsLegArr = $caseStatus . "-" . "";
            $expStsLeg = explode("-", $stsLegArr);
            if (!in_array("upd", $expStsLeg)) {
                $qry .= " AND Easycase.type_id !=10";
            }
        }
        /* elseif($caseMenuFilters != "closecase" && $caseMenuFilters != "milestone" && $caseMenuFilters != "bugcase" ) {
          $qry.= " AND (Easycase.legend !='3' OR Easycase.type_id ='10')";
          } */
        /* Start By OSDEV on 07112013 */ elseif ($caseMenuFilters != "closecase" && $caseMenuFilters != "milestone" && $caseMenuFilters != "bugcase" && trim(urldecode($case_srch)) == "" && empty($caseSrch)) {
            //$qry.= " AND (Easycase.legend !='3' OR Easycase.type_id ='10')";
        }
        /* End By OSDEV on 07112013 */
        //echo $qry;
        ######### Filter by Case Types ##########
        if (trim($caseTypes) && $caseTypes != "all") {
            $qry .= $this->Format->typeFilter($caseTypes);
            $filterenabled = 1;
        }
        ######### Filter by Priority ##########
        if (trim($priorityFil) && $priorityFil != "all") {
            $qry .= $this->Format->priorityFilter($priorityFil, $caseTypes);
            $filterenabled = 1;
        }
        ######### Filter by Member ##########
        if (trim($caseUserId) && $caseUserId != "all") {
            $qry .= $this->Format->memberFilter($caseUserId);
            $filterenabled = 1;
        }
        ######### Filter by AssignTo ##########
        if (trim($caseAssignTo) && $caseAssignTo != "all") {
            $qry .= $this->Format->assigntoFilter($caseAssignTo);
            $filterenabled = 1;
        }
        // Order by
        $sortby = '';
        if (isset($_COOKIE['TASKSORTBY'])) {
            $sortby = $_COOKIE['TASKSORTBY'];
            $sortorder = $_COOKIE['TASKSORTORDER'];
        }
        if ($sortby == 'title') {
            $orderby = "LTRIM(Easycase.title) " . $sortorder;
            $caseTitle = strtolower($sortorder);
        } elseif ($sortby == 'duedate') {
            $caseDueDate = strtolower($sortorder);
            $orderby = "Easycase.due_date " . $sortorder;
        } elseif ($sortby == 'caseno') {
            $caseNum = strtolower($sortorder);
            $orderby = "Easycase.case_no " . $sortorder;
        } elseif ($sortby == 'caseAt') {
            $caseAtsort = strtolower($sortorder);
            $orderby = "Assigned " . $sortorder;
        } else {
            $orderby = "Easycase.dt_created DESC";
        }
        $groupby = '';
        $gby = '';
        if (isset($_COOKIE['TASKGROUPBY']) && $_COOKIE['TASKGROUPBY'] != 'date') {
            setcookie('TASKSORTBY', '', time() - 3600, '/', DOMAIN_COOKIE, false, false);
            setcookie('TASKSORTORDER', '', time() - 3600, '/', DOMAIN_COOKIE, false, false);
            $orderby = '';
            $groupby = $_COOKIE['TASKGROUPBY'];
            if ($groupby == 'status') {
                $gby = 'status';
                $orderby .= " FIND_IN_SET(Easycase.type_id,'10'),FIND_IN_SET(Easycase.legend,'1,2,4,5,3,,10') ";
            } elseif ($groupby == 'priority') {
                $orderby .= " if(Easycase.priority = '' or Easycase.priority is null,4,Easycase.priority),Easycase.priority";
                $gby = 'priority';
            } elseif ($groupby == 'duedate') {
                $orderby .= " Easycase.due_date DESC";
                $gby = 'due_date';
            } elseif ($groupby == 'crtdate') {
                $gby = 'crtdate';
                $orderby .= " Easycase.actual_dt_created DESC";
            } elseif ($groupby == 'assignto') {
                $gby = 'assignto';
                $orderby .= " Assigned ASC";
            }
            if ($groupby != 'date') {
                $orderby .= " ,Easycase.dt_created DESC";
            }
        }

        ######### Order by Date ##########
//		 if(!empty($caseDate)){ 
//		     if($caseDate == "asc") {
//			    $orderby = "Easycase.dt_created ASC,Easycase.priority DESC";
//		     }else {
//				$orderby = "Easycase.dt_created DESC,Easycase.priority DESC";
//		     }
//          }else{
//               $orderby = "Easycase.dt_created DESC,Easycase.priority DESC";
//          }                   
//		######### Order by Title ##########
//		if($caseTitle == "desc") {
//			$orderby = "LTRIM(Easycase.title) DESC";
//		}elseif($caseTitle == "asc") {
//			$orderby = "LTRIM(Easycase.title) ASC";
//		}
//		######### Order by Due Date ##########
//		if($caseDueDate == "desc") {
//			$orderby = "Easycase.due_date DESC";
//		}elseif($caseDueDate == "asc") {
//			$orderby = "Easycase.due_date ASC";
//		}
//		if($caseNum == "desc") {
//			$orderby = "Easycase.case_no DESC";
//		}
//          if($caseCreateDate == "desc"){
//               $orderby = "Easycase.actual_dt_created DESC";
//          }else if($caseCreateDate == "asc"){
//               $orderby = "Easycase.actual_dt_created ASC";
//          }
//		elseif($caseNum == "asc") {
//			$orderby = "Easycase.case_no ASC";
//		}
//		if($caseLegendsort == "desc") {
//			//$orderby = "Easycase.legend DESC";
//			$orderby = "FIND_IN_SET(Easycase.type_id,'10'),FIND_IN_SET(Easycase.legend,'2,10,4,5,1,3') ";
//		}elseif($caseLegendsort == "asc") {
//			//$orderby = "Easycase.legend ASC";
//			$orderby = "FIND_IN_SET(Easycase.type_id,'10'),FIND_IN_SET(Easycase.legend,'3,1,5,4,10,2') ";
//		}
//		if($caseAtsort == "desc") {
//			$orderby = "Assigned DESC";
//		}elseif($caseAtsort == "asc") {
//			$orderby = "Assigned ASC";
//		}
        ######### Search by KeyWord ##########
        $searchcase = "";
        if (trim(urldecode($caseSrch)) && (trim($case_srch) == "")) {
            //$qry="";
            $filterenabled = 1;
            $searchcase = $this->Format->caseKeywordSearch($caseSrch, 'full');
        }
        if (trim(urldecode($case_srch)) != "") {
            //$qry="";
            $filterenabled = 1;
            $searchcase = "AND (Easycase.case_no = '$case_srch')";
        }

        if (trim(urldecode($caseSrch))) {
            $filterenabled = 1;
            if ((substr($caseSrch, 0, 1)) == '#') {
                //$qry="";
                $tmp = explode("#", $caseSrch);
                $casno = trim($tmp['1']);
                $searchcase = " AND (Easycase.case_no = '" . $casno . "')";
            }
        }

        $cond_easycase_actuve = "";

        if ((isset($case_srch) && !empty($case_srch)) || isset($caseSrch) && !empty($caseSrch)) {
            $cond_easycase_actuve = "";
        } else {
            $cond_easycase_actuve = "AND Easycase.isactive=1";
        }
        //echo $cond_easycase_actuve;
        if (trim($case_date) != "") {
            if (trim($case_date) == 'one') {
                $one_date = date('Y-m-d H:i:s', time() - 3600);
                $qry .= " AND Easycase.dt_created >='" . $one_date . "'";
            } else if (trim($case_date) == '24') {
                $filterenabled = 1;
                $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 day"));
                $qry .= " AND Easycase.dt_created >='" . $day_date . "'";
            } else if (trim($case_date) == 'week') {
                $filterenabled = 1;
                $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 week"));
                $qry .= " AND Easycase.dt_created >='" . $week_date . "'";
            } else if (trim($case_date) == 'month') {
                $filterenabled = 1;
                $month_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 month"));
                $qry .= " AND Easycase.dt_created >='" . $month_date . "'";
            } else if (trim($case_date) == 'year') {
                $filterenabled = 1;
                $year_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 year"));
                $qry .= " AND Easycase.dt_created >='" . $year_date . "'";
            } else if (strstr(trim($case_date), ":")) {
                $filterenabled = 1;
                //echo $case_date;exit;
                $ar_dt = explode(":", trim($case_date));
                $frm_dt = $ar_dt['0'];
                $to_dt = $ar_dt['1'];
                $qry .= " AND DATE(Easycase.dt_created) >= '" . date('Y-m-d H:i:s', strtotime($frm_dt)) . "' AND DATE(Easycase.dt_created) <= '" . date('Y-m-d H:i:s', strtotime($to_dt)) . "'";
            }
        }
        if (trim($case_duedate) != "") {

            if (trim($case_duedate) == '24') {
                $filterenabled = 1;
                $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 day"));
                $qry .= " AND (DATE(Easycase.due_date) ='" . GMT_DATE . "')";
            } else if (trim($case_duedate) == 'overdue') {
                $filterenabled = 1;
                $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 week"));
                $qry .= " AND ( DATE(Easycase.due_date) <'" . GMT_DATE . "') AND (Easycase.legend =1 || Easycase.legend=2) ";
            } else if (strstr(trim($case_duedate), ":") && trim($case_duedate) !== '0000-00-00 00:00:00') {
                $filterenabled = 1;
                $ar_dt = explode(":", trim($case_duedate));
                $frm_dt = $ar_dt['0'];
                $to_dt = $ar_dt['1'];
                $qry .= " AND DATE(Easycase.due_date) >= '" . date('Y-m-d', strtotime($frm_dt)) . "' AND DATE(Easycase.due_date) <= '" . date('Y-m-d', strtotime($to_dt)) . "'";
            }
        }
        ######### Filter by Assign To ##########
        if ($caseMenuFilters == "assigntome") {
            $qry .= " AND ((Easycase.assign_to=" . SES_ID . ") OR (Easycase.assign_to=0 AND Easycase.user_id=" . SES_ID . "))";
        }
        ######### Filter by Delegate To ##########
        elseif ($caseMenuFilters == "delegateto") {
            $qry .= " AND Easycase.assign_to!=0 AND Easycase.assign_to!=" . SES_ID . " AND Easycase.user_id=" . SES_ID;
        } elseif ($caseMenuFilters == "closecase") {
            $qry .= " AND Easycase.legend='3' AND Easycase.type_id !='10'";
        } elseif ($caseMenuFilters == "overdue") {
            //$qry.= " AND Easycase.type_id ='1'";
            $cur_dt = date('Y-m-d', strtotime(GMT_DATETIME));
            $qry .= " AND Easycase.due_date !='' AND Easycase.due_date !='0000-00-00' AND Easycase.due_date !='1970-01-01' AND Easycase.due_date < '" . $cur_dt . "' AND (Easycase.legend =1 || Easycase.legend=2) ";
        } elseif ($caseMenuFilters == "highpriority") {
            $qry .= " AND Easycase.priority ='0' ";
        } elseif ($caseMenuFilters == "newwip") {
            $qry .= " AND (Easycase.legend='1' OR Easycase.legend='2')  AND Easycase.type_id !='10'";
        }
        ######### Filter by Latest ##########
        elseif ($caseMenuFilters == "latest") {
            $filterenabled = 1;
            $qry_rest = $qry;
            $before = date('Y-m-d H:i:s', strtotime(GMT_DATETIME . "-2 day"));
            $all_rest = " AND Easycase.dt_created > '" . $before . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
            $qry_rest .= " AND Easycase.dt_created > '" . $before . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
        }
        if ($caseMenuFilters == "latest" && $projUniq != 'all') {
            $CaseCount3 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  " . $searchcase . " " . trim($qry_rest));
            $CaseCount = $CaseCount3['0']['0']['count'];
            if ($CaseCount == 0) {
                $rest = $this->Easycase->query("SELECT dt_created FROM easycases WHERE project_id ='" . $curProjId . "' ORDER BY dt_created DESC LIMIT 0 , 1");
                @$sdate = explode(" ", @$rest[0]['easycases']['dt_created']);
                $qry .= " AND Easycase.dt_created >= '" . @$sdate[0] . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
            } else {
                $qry = $qry . $all_rest;
            }
        } else if ($caseMenuFilters == "latest" && $projUniq == 'all') {
            $qry = $qry . $all_rest;
        }

        ######### Close a Case ##########
        if ($changecasetype) {
            $caseid = $changecasetype;
        } elseif ($caseChangeDuedate) {
            $caseid = $caseChangeDuedate;
        } elseif ($caseChangePriority) {
            $caseid = $caseChangePriority;
        } elseif ($caseChangeAssignto) {
            $caseid = $caseChangeAssignto;
        }
        if ($caseid) {
            $checkStatus = $this->Easycase->query("SELECT legend FROM easycases WHERE id='" . $caseid . "' AND isactive='1'");
            if ($checkStatus['0']['easycases']['legend'] == 1) {
                $status = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#763532" style="font:normal 12px verdana;">NEW</font>';
            } elseif ($checkStatus['0']['easycases']['legend'] == 4) {
                $status = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#55A0C7" style="font:normal 12px verdana;">STARTED</font>';
            } elseif ($checkStatus['0']['easycases']['legend'] == 5) {
                $status = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#EF6807" style="font:normal 12px verdana;">RESOLVED</font>';
            } elseif ($checkStatus['0']['easycases']['legend'] == 3) {
                $status = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="green" style="font:normal 12px verdana;">CLOSED</font>';
            }
        }
        //echo $startCaseId."---".$caseResolve."---".$caseUniqId;
        $commonAllId = "";
        $caseid_list = '';
        if ($startCaseId) {
            $csSts = 1;
            $csLeg = 4;
            $acType = 2;
            $cuvtype = 4;
            $commonAllId = $startCaseId;
            $emailType = "Start";
            $msg = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#55A0C7" style="font:normal 12px verdana;">STARTED</font>';
            $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">STARTED</font> the Task.';
        } elseif ($caseResolve) {
            $csSts = 1;
            $csLeg = 5;
            $acType = 3;
            $cuvtype = 5;
            $commonAllId = $caseResolve;
            $emailType = "Resolve";
            $msg = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#EF6807" style="font:normal 12px verdana;">RESOLVED</font>';
            $emailbody = '<font color="#EF6807" style="font:normal 12px verdana;">RESOLVED</font> the Task.';
        } elseif ($caseUniqId) {
            $csSts = 2;
            $csLeg = 3;
            $acType = 1;
            $cuvtype = 3;
            $commonAllId = $caseUniqId;
            $emailType = "Close";
            $msg = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="green" style="font:normal 12px verdana;">CLOSED</font>';
            $emailbody = '<font color="green" style="font:normal 12px verdana;">CLOSED</font> the Task.';
        } elseif ($changecasetype) {
            $csSts = 1;
            $csLeg = 4;
            $acType = 2;
            $cuvtype = 4;
            $commonAllId = $changecasetype;
            $emailType = "Change Type";
            $caseChageType1 = 1;
            $msg = $status;
            $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">changed the type of</font> the Task.';
        } elseif ($caseChangeDuedate) {
            $csSts = 1;
            $csLeg = 4;
            $acType = 2;
            $cuvtype = 4;
            $commonAllId = $caseChangeDuedate;
            $emailType = "Change Duedate";
            $caseChageDuedate1 = 3;
            $msg = $status;
            $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">changed the due date of</font> the Task.';
        } elseif ($caseChangePriority) {
            $csSts = 1;
            $csLeg = 4;
            $acType = 2;
            $cuvtype = 4;
            $commonAllId = $caseChangePriority;
            $emailType = "Change Priority";
            $caseChagePriority1 = 2;
            $msg = $status;
            $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">changed the priority of</font> the Task.';
        } elseif ($caseChangeAssignto) {
            $csSts = 1;
            $csLeg = 4;
            $acType = 2;
            $cuvtype = 4;
            $commonAllId = $caseChangeAssignto;
            $emailType = "Change Assignto";
            $caseChangeAssignto1 = 4;
            $msg = $status;
            $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">changed the assigned to of</font> the Task.';
        }
        if ($commonAllId) {
            $commonAllId = $commonAllId . ",";
            $commonArrId = explode(",", $commonAllId);
            $done = 1;
            if ($caseChageType1 || $caseChageDuedate1 || $caseChagePriority1 || $caseChangeAssignto1) {
                
            } else {
                foreach ($commonArrId as $commonCaseId) {

                    if (trim($commonCaseId)) {

                        $done = 1;
                        $checkSts = $this->Easycase->query("SELECT legend FROM easycases WHERE id='" . $commonCaseId . "' AND isactive='1'");
                        if (isset($checkSts['0']) && count($checkSts['0'])) {
                            if ($checkSts['0']['easycases']['legend'] == 3) {
                                $done = 0;
                            }
                            if ($csLeg == 4) {
                                if ($checkSts['0']['easycases']['legend'] == 4) {
                                    $done = 0;
                                }
                            }
                            if ($csLeg == 5) {
                                if ($checkSts['0']['easycases']['legend'] == 5) {
                                    $done = 0;
                                }
                            }
                        } else {
                            $done = 0;
                        }
                        if ($done) {

                            $caseid_list .= $commonCaseId . ',';

                            $caseDataArr = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $commonCaseId), 'fields' => array('Easycase.id', 'Easycase.case_no', 'Easycase.project_id', 'Easycase.type_id', 'Easycase.priority', 'Easycase.title', 'Easycase.uniq_id', 'Easycase.assign_to')));

                            $caseStsId = $caseDataArr['Easycase']['id'];
                            $caseStsNo = $caseDataArr['Easycase']['case_no'];
                            $closeStsPid = $caseDataArr['Easycase']['project_id'];
                            $closeStsTyp = $caseDataArr['Easycase']['type_id'];
                            $closeStsPri = $caseDataArr['Easycase']['priority'];
                            $closeStsTitle = $caseDataArr['Easycase']['title'];
                            $closeStsUniqId = $caseDataArr['Easycase']['uniq_id'];
                            $caUid = $caseDataArr['Easycase']['assign_to'];

                            $this->Easycase->query("UPDATE easycases SET case_no='" . $caseStsNo . "',updated_by='" . SES_ID . "',case_count=case_count+1, project_id='" . $closeStsPid . "', type_id='" . $closeStsTyp . "', priority='" . $closeStsPri . "', status='" . $csSts . "', legend='" . $csLeg . "', dt_created='" . GMT_DATETIME . "' WHERE id=" . $caseStsId . " AND isactive='1'");

                            $caseuniqid = md5(uniqid());
                            $this->Easycase->query("INSERT INTO easycases SET uniq_id='" . $caseuniqid . "', user_id='" . SES_ID . "', format='2', istype='2', actual_dt_created='" . GMT_DATETIME . "', case_no='" . $caseStsNo . "', project_id='" . $closeStsPid . "', type_id='" . $closeStsTyp . "', priority='" . $closeStsPri . "', status='" . $csSts . "', legend='" . $csLeg . "', dt_created='" . GMT_DATETIME . "'");
                            //$thisCaseId = mysql_insert_id();
                            //socket.io implement start
                            $Project = ClassRegistry::init('Project');
                            $ProjectUser = ClassRegistry::init('ProjectUser');
                            $ProjectUser->recursive = -1;

                            $getUser = $ProjectUser->query("SELECT user_id FROM project_users WHERE project_id='" . $closeStsPid . "'");
                            $prjuniq = $Project->query("SELECT uniq_id, short_name FROM projects WHERE id='" . $closeStsPid . "'");
                            $prjuniqid = $prjuniq[0]['projects']['uniq_id']; //print_r($prjuniq);
                            $projShName = strtoupper($prjuniq[0]['projects']['short_name']);
                            $channel_name = $prjuniqid;
                            //$pname = $this->Format->getProjectName($closeStsPid);
                            //$msg = "'Case Started in ".$pname."'";
                            $msgpub = 'Updated.~~' . SES_ID . '~~' . $caseStsNo . '~~' . 'UPD' . '~~' . $closeStsTitle . '~~' . $projShName;

                            $this->Postcase->iotoserver(array('channel' => $channel_name, 'message' => $msgpub));
                            //socket.io implement end

                            $CaseActivity = ClassRegistry::init('CaseActivity');
                            $CaseActivity->recursive = -1;
                            $CaseAct['easycase_id'] = $thisCaseId;
                            $CaseAct['user_id'] = SES_ID;
                            $CaseAct['project_id'] = $closeStsPid;
                            $CaseAct['case_no'] = $caseStsNo;
                            $CaseAct['type'] = $csLeg;
                            $CaseAct['dt_created'] = GMT_DATETIME;
                            //$CaseActivity->saveAll($CaseAct);
                        }
                    }
                }
            }
            $_SESSION['email']['email_body'] = $emailbody;
            $_SESSION['email']['msg'] = $msg;
            if ($caseChageType1 == 1) {
                $caseid_list = $commonAllId;
            } elseif ($caseChagePriority1 == 2) {
                $caseid_list = $commonAllId;
            } elseif ($caseChageDuedate1 == 3) {
                $caseid_list = $commonAllId;
            } elseif ($caseChangeAssignto1 == 4) {
                $caseid_list = $commonAllId;
            }
            $email_notification = array('allfiles' => $allfiles, 'caseNo' => $caseStsNo, 'closeStsTitle' => $closeStsTitle, 'emailMsg' => $emailMsg, 'closeStsPid' => $closeStsPid, 'closeStsPri' => $closeStsPri, 'closeStsTyp' => $closeStsTyp, 'assignTo' => $assignTo, 'usr_names' => $usr_names, 'caseuniqid' => $caseuniqid, 'csType' => $emailType, 'closeStsPid' => $closeStsPid, 'caseStsId' => $caseStsId, 'caseIstype' => 5, 'caseid_list' => $caseid_list, 'caseUniqId' => $closeStsUniqId); // $caseuniqid

            $resCaseProj['email_arr'] = json_encode($email_notification);
        }
        $msQuery1 = " ";
        if (isset($caseMenuFilters) && $caseMenuFilters == "milestone") {
            $msQuery = "";
            if ($milestoneIds != "all" && strstr($milestoneIds, "-")) {
                $expMilestoneIds = explode("-", $milestoneIds);
                foreach ($expMilestoneIds as $msid) {
                    if ($msid) {
                        $msQuery .= "EasycaseMilestone.milestone_id=" . $msid . " OR ";
                    }
                }
                if ($msQuery) {
                    $msQuery = substr($msQuery, 0, -3);
                    $msQuery = " AND (" . $msQuery . ")";
                }
            } else {
                $tody = date('Y-m-d', strtotime("now"));
                //$msQuery1 = " AND  '".$tody."' BETWEEN Milestone.start_date AND Milestone.end_date ";
            }
        }

        $resCaseProj['page_limit'] = $page_limit;
        $resCaseProj['csPage'] = $casePage;
        $resCaseProj['caseUrl'] = $caseUrl;
        $resCaseProj['projUniq'] = $projUniq;
        $resCaseProj['csdt'] = $caseDate;
        $resCaseProj['csTtl'] = $caseTitle;
        $resCaseProj['csDuDt'] = $caseDueDate;
        $resCaseProj['csCrtdDt'] = $caseCreateDate;
        $resCaseProj['csNum'] = $caseNum;
        $resCaseProj['csLgndSrt'] = $caseLegendsort;
        $resCaseProj['csAtSrt'] = $caseAtsort;
        $resCaseProj['caseMenuFilters'] = $caseMenuFilters;
        $resCaseProj['filterenabled'] = $filterenabled;

        if ($projUniq) {
            //$this->Easycase->query('SET CHARACTER SET utf8');
            $page = $casePage;
            $limit1 = $page * $page_limit - $page_limit;
            $limit2 = $page_limit;

            if (isset($caseMenuFilters) && $caseMenuFilters == "milestone") {
                if ($milestone_type == 0) {
                    $qrycheck = "Milestone.isactive='0'";
                } else {
                    $qrycheck = "Milestone.isactive='1'";
                }
                if ($projUniq != 'all') {
                    $caseAll = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.*,EasycaseMilestone.id AS Emid, EasycaseMilestone.milestone_id AS Em_milestone_id,EasycaseMilestone.user_id AS Em_user_id,EasycaseMilestone.id_seq,Milestone.id as Mid,Milestone.title AS Mtitle ,Milestone.end_date,Milestone.isactive AS Misactive,Milestone.project_id AS Mproject_id,Milestone.uniq_id AS Muinq_id FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone,milestones AS Milestone WHERE EasycaseMilestone.easycase_id=Easycase.id AND Milestone.id=EasycaseMilestone.milestone_id" . $msQuery1 . "AND Easycase.istype='1' " . $cond_easycase_actuve . " AND " . $qrycheck . " AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  " . $searchcase . " " . trim($qry) . " AND EasycaseMilestone.easycase_id=Easycase.id AND EasycaseMilestone.project_id=" . $curProjId . $msQuery . " ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY Easycase.end_date ASC,Easycase.Mtitle ASC," . $orderby . " LIMIT $limit1,$limit2");
                }
                if ($projUniq == 'all') {
                    $caseAll = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT  Easycase.*,EasycaseMilestone.id AS Emid, EasycaseMilestone.milestone_id AS Em_milestone_id,EasycaseMilestone.user_id AS Em_user_id,EasycaseMilestone.id_seq,Milestone.id as Mid,Milestone.title AS Mtitle ,Milestone.end_date,Milestone.isactive AS Misactive,Milestone.project_id AS Mproject_id,Milestone.uniq_id AS Muinq_id FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone,milestones AS Milestone WHERE EasycaseMilestone.easycase_id=Easycase.id AND Milestone.id=EasycaseMilestone.milestone_id AND Easycase.istype='1' " . $cond_easycase_actuve . " AND " . $qrycheck . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') " . $searchcase . " " . trim($qry) . " AND EasycaseMilestone.easycase_id=Easycase.id AND EasycaseMilestone.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1')" . $msQuery . " ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY Easycase.end_date ASC,Easycase.Mtitle ASC," . $orderby . " LIMIT $limit1,$limit2");
                }
            } else {
                if ($projUniq == 'all') {
                    if ($caseMenuFilters == "latest") {
                        $caseAll = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT * FROM easycases as Easycase WHERE Easycase.istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') " . $searchcase . " " . trim($qry) . "  ORDER BY  Easycase.dt_created DESC) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY " . $orderby . " LIMIT $limit1,$limit2");
                    } else {
                        $caseAll = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT * FROM easycases as Easycase WHERE Easycase.istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') " . $searchcase . " " . trim($qry) . "  ORDER BY  Easycase.project_id DESC) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY " . $orderby . " LIMIT $limit1,$limit2");
                    }
                } else {
                    //echo "SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =".SES_ID."),'Me',User.short_name) AS Assigned FROM ( SELECT * FROM easycases as Easycase WHERE istype='1' ".$cond_easycase_actuve." AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  ".$searchcase." ".trim($qry)." ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY ".$orderby." LIMIT $limit1,$limit2";exit;
                    $caseAll = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT * FROM easycases as Easycase WHERE istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  " . $searchcase . " " . trim($qry) . " ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY " . $orderby . " LIMIT $limit1,$limit2");
                }
            }

            $tot = $this->Easycase->query("SELECT FOUND_ROWS() as total");
            $CaseCount = $tot[0][0]['total'];

            $msQ = "";
            if ($milestoneIds != "all" && strstr($milestoneIds, "-")) {
                $expMilestoneIds = explode("-", $milestoneIds);
                $idArr = array();
                foreach ($expMilestoneIds as $msid) {
                    if (trim($msid)) {
                        $idArr[] = trim($msid);
                    }
                }
                if (count($idArr)) {
                    $msQ .= "AND Milestone.id IN (" . implode(",", $idArr) . ")";
                }
            }
            if ($projUniq != 'all') {
                $milestones = array();
                if (isset($caseMenuFilters) && $caseMenuFilters == "milestone") {
                    if ($milestone_type == 0) {
                        $qrycheck = "Milestone.isactive='0'";
                    } else {
                        $qrycheck = "Milestone.isactive='1'";
                    }
                    $this->loadModel('Milestone');

                    $milestones = $this->Milestone->query("SELECT `Milestone`.`id`,`Milestone`.`title`,`Milestone`.`project_id`,`Milestone`.`end_date`,`Milestone`.`uniq_id`,`Milestone`.`isactive`,`Milestone`.`user_id`,COUNT(c.easycase_id) AS totalcases,GROUP_CONCAT(c.easycase_id) AS `caseids`  FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id WHERE `Milestone`.`project_id` =" . $curProjId . " AND " . $qrycheck . " AND `Milestone`.`company_id` = " . SES_COMP . " " . $msQ . " GROUP BY Milestone.id ORDER BY `Milestone`.`id` ASC");
                }
                foreach ($milestones as $mls) {
                    $mid .= $mls['Milestone']['id'] . ',';
                    $m[$mls['Milestone']['id']]['id'] = $mls['Milestone']['id'];
                    $m[$mls['Milestone']['id']]['caseids'] = $mls[0]['caseids'];
                    $m[$mls['Milestone']['id']]['totalcases'] = $mls[0]['totalcases'];
                    $m[$mls['Milestone']['id']]['title'] = $mls['Milestone']['title'];
                    $m[$mls['Milestone']['id']]['project_id'] = $mls['Milestone']['project_id'];
                    $m[$mls['Milestone']['id']]['end_date'] = $mls['Milestone']['end_date'];
                    $m[$mls['Milestone']['id']]['uinq_id'] = $mls['Milestone']['uniq_id'];
                    $m[$mls['Milestone']['id']]['isactive'] = $mls['Milestone']['isactive'];
                    $m[$mls['Milestone']['id']]['user_id'] = $mls['Milestone']['user_id'];
                }
                $c = array();

                if ($mid) {
                    $closed_cases = $this->Easycase->query("SELECT EasycaseMilestone.milestone_id,COUNT(Easycase.id) as totcase FROM easycase_milestones AS EasycaseMilestone LEFT JOIN easycases as Easycase ON   EasycaseMilestone.easycase_id=Easycase.id WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND EasycaseMilestone.milestone_id IN(" . trim($mid, ',') . ") GROUP BY  EasycaseMilestone.milestone_id");
                    foreach ($closed_cases as $key => $val) {
                        $c[$val['EasycaseMilestone']['milestone_id']]['totalclosed'] = $val[0]['totcase'];
                    }
                }
                $resCaseProj['milestones'] = $m;
            }
            if ($projUniq == 'all') {
                $milestones = array();
                if (isset($caseMenuFilters) && $caseMenuFilters == "milestone") {
                    if ($milestone_type == 0) {
                        $qrycheck = "Milestone.isactive='0'";
                    } else {
                        $qrycheck = "Milestone.isactive='1'";
                    }
                    $cond = array('conditions' => array('ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP, 'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'), 'order' => array('ProjectUser.dt_visited DESC'));
                    $ProjectUser = ClassRegistry::init('ProjectUser');
                    $ProjectUser->unbindModel(array('belongsTo' => array('User')));
                    $allProjArr = $ProjectUser->find('all', $cond);
                    $ids = array();
                    foreach ($allProjArr as $csid) {
                        array_push($ids, $csid['Project']['id']);
                    }
                    $implode_ids = implode(',', $ids);
                    $this->loadModel('Milestone');
                    $this->Milestone->recursive = -1;

                    $milestones = $this->Milestone->query("SELECT `Milestone`.`id`,`Milestone`.`title`,`Milestone`.`project_id`,`Milestone`.`end_date`,`Milestone`.`uniq_id`,`Milestone`.`isactive`,`Milestone`.`user_id`,COUNT(c.easycase_id) AS totalcases,GROUP_CONCAT(c.easycase_id) AS `caseids`  FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id WHERE `Milestone`.`project_id` IN (" . $implode_ids . ") AND " . $qrycheck . " AND `Milestone`.`company_id` = " . SES_COMP . " " . $msQ . " GROUP BY Milestone.id ORDER BY `Milestone`.`id` ASC");

                    $mid = '';
                    foreach ($milestones as $k => $v) {
                        $mid .= $v['Milestone']['id'] . ',';
                        $m[$v['Milestone']['id']]['id'] = $v['Milestone']['id'];
                        $m[$v['Milestone']['id']]['caseids'] = $v[0]['caseids'];
                        $m[$v['Milestone']['id']]['totalcases'] = $v[0]['totalcases'];
                        $m[$v['Milestone']['id']]['title'] = $v['Milestone']['title'];
                        $m[$v['Milestone']['id']]['project_id'] = $v['Milestone']['project_id'];
                        $m[$v['Milestone']['id']]['end_date'] = $v['Milestone']['end_date'];
                        $m[$v['Milestone']['id']]['uinq_id'] = $v['Milestone']['uniq_id'];
                        $m[$v['Milestone']['id']]['isactive'] = $v['Milestone']['isactive'];
                        $m[$v['Milestone']['id']]['user_id'] = $v['Milestone']['user_id'];
                    }
                    $c = array();
                    if ($mid) {
                        $closed_cases = $this->Easycase->query("SELECT EasycaseMilestone.milestone_id,COUNT(Easycase.id) as totcase FROM easycase_milestones AS EasycaseMilestone LEFT JOIN easycases as Easycase ON   EasycaseMilestone.easycase_id=Easycase.id WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND EasycaseMilestone.milestone_id IN (" . trim($mid, ',') . ") GROUP BY  EasycaseMilestone.milestone_id");
                        foreach ($closed_cases as $key => $val) {
                            $c[$val['EasycaseMilestone']['milestone_id']]['totalclosed'] = $val[0]['totcase'];
                        }
                    }
                    $resCaseProj['milestones'] = $m;
                }
            }

            $ProjectUser = ClassRegistry::init('ProjectUser');

            if ($projUniq != 'all') {
                $usrDtlsAll = $ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id='" . $curProjId . "' AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
            } else {
                $usrDtlsAll = $ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
            }

            $usrDtlsArr = array();
            $usrDtlsPrj = array();
            foreach ($usrDtlsAll as $ud) {
                $usrDtlsArr[$ud['User']['id']] = $ud;
            }
            //$resCaseProj['usrDtlsArr'] = $usrDtlsArr;
        } else {
            $CaseCount = 0;
        }
        $resCaseProj['caseCount'] = $CaseCount;

        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $dt = $view->loadHelper('Datetime');
        $cq = $view->loadHelper('Casequery');
        $frmt = $view->loadHelper('Format');
//        pr($resCaseProj);exit;
        $frmtCaseAll = $this->Easycase->formatCases($caseAll, $CaseCount, $caseMenuFilters, $c, $m, $projUniq, $usrDtlsArr, $frmt, $dt, $tz, $cq);


        /* foreach ($frmtCaseAll['caseAll'] as $key => $value) {
          $this->loadModel('EasycaseMilestone');
          $res_milestones=$this->EasycaseMilestone->findByEasycaseId($value['Easycase']['id']);
          if($res_milestones['EasycaseMilestone']['milestone_id']) {
          $frmtCaseAll['caseAll'][$key]['Easycase']['milestone_id']=$res_milestones['EasycaseMilestone']['milestone_id'];
          }

          } */
        $resCaseProj['caseAll'] = $frmtCaseAll['caseAll'];
        $resCaseProj['milestones'] = $frmtCaseAll['milestones'];

        $pgShLbl = $frmt->pagingShowRecords($CaseCount, $page_limit, $casePage);
        $resCaseProj['pgShLbl'] = $pgShLbl;

        $curCreated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        $friday = date('Y-m-d', strtotime($curCreated . "next Friday"));
        $monday = date('Y-m-d', strtotime($curCreated . "next Monday"));
        $tomorrow = date('Y-m-d', strtotime($curCreated . "+1 day"));

        $resCaseProj['intCurCreated'] = strtotime($curCreated);
        $resCaseProj['mdyCurCrtd'] = date('m/d/Y', strtotime($curCreated));
        $resCaseProj['mdyFriday'] = date('m/d/Y', strtotime($friday));
        $resCaseProj['mdyMonday'] = date('m/d/Y', strtotime($monday));
        $resCaseProj['mdyTomorrow'] = date('m/d/Y', strtotime($tomorrow));
        $resCaseProj['GrpBy'] = $gby;
        if ($projUniq != 'all') {
            $projUser = array();
            if ($projUniq) {
                $projUser = array($projUniq => $this->Easycase->getMemebers($projUniq));
            }
            $resCaseProj['projUser'] = $projUser;
        }
//        pr(SES_ID);
//        pr($resCaseProj);exit;
        $this->set('resCaseProj', json_encode($resCaseProj));
    }

    function ajax_assignto_mem() {
        $this->layout = 'ajax';
        $project = $this->params['data']['project'];
        /* $csId = $this->params['data']['csId'];
          $caseUniqId = $this->params['data']['caseUniqId'];
          $caseAssgnUid = $this->params['data']['caseAssgnUid']; */

        $usrDtlsArr = $this->Easycase->getMemebers($project);

        /* $this->set('usrDtlsArr',$usrDtlsArr);
          $this->set('caseAutoId',$csId);
          $this->set('caseUniqId',$caseUniqId);
          $this->set('caseAssgnUid',$caseAssgnUid); */

        $this->set('projUser', json_encode(array($project => $usrDtlsArr)));
    }

    function case_details($oauth_arg = NULL) {
        $this->layout = 'ajax';
        $details = 0;

        $oauth_return = 0;
        if (isset($oauth_arg) && !empty($oauth_arg)) {
            $oauth_return = 1;
        }

        //$projUniqDtls = isset($oauth_arg['projFil']) ? $oauth_arg['projFil'] : $this->params['data']['projFil'];
        $caseUniqId = isset($oauth_arg['caseUniqId']) ? $oauth_arg['caseUniqId'] : $this->params['data']['caseUniqId'];
        //$spnajx = $this->params['data']['spnajx'];
        //$count = $this->params['data']['count'];
        /* if((isset($this->params['data']['prjid']) && $this->params['data']['prjid']) || (isset($oauth_arg['prjid']) && $oauth_arg['prjid'])){
          $prjid = isset($oauth_arg['prjid']) ? $oauth_arg['prjid'] : $this->params['data']['prjid'];
          } */

        if (isset($this->params['data']['details'])) {
            $details = $this->params['data']['details'];
        }
        if (isset($this->params['data']['sorting'])) {
            $sorting = $this->params['data']['sorting'];
            $this->Cookie->write('SORT_THREAD', $sorting, '365 days');
        } elseif ($_COOKIE['REPLY_SORT_ORDER']) {
            if ($_COOKIE['REPLY_SORT_ORDER'] == 'ASC')
                $sort_cookie = 1;
            $sorting = $_COOKIE['REPLY_SORT_ORDER'] . " LIMIT 0,5";
        }else {
            $sorting = "DESC LIMIT 0,5";
        }

        $ProjId = NULL;
        $ProjName = NULL;
        $curCaseNo = NULL;
        $curCaseId = NULL;

        ######## get case number from case uniq ID ################
        $getCaseNoPjId = $this->Easycase->getEasycase($caseUniqId);
        if ($getCaseNoPjId) {
            $curCaseNo = $getCaseNoPjId['Easycase']['case_no'];
            $curCaseId = $getCaseNoPjId['Easycase']['id'];
            $prjid = $getCaseNoPjId['Easycase']['project_id'];
            $is_active = (intval($getCaseNoPjId['Easycase']['isactive'])) ? 1 : 0;
        } else {
            //No task with uniq_id $caseUniqId
            die;
        }

        ######## Checking user_project ################
        $this->loadModel('ProjectUser');
        $cond1 = array(
            'conditions' => array('ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP, 'Project.isactive' => 1, 'Project.id' => $prjid),
            'fields' => array('DISTINCT Project.id', 'Project.uniq_id', 'Project.name')
        );
        $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
        $getProjId = $this->ProjectUser->find('first', $cond1);
        if ($getProjId) {
            $ProjId = $getProjId['Project']['id'];
            $projUniqId = $getProjId['Project']['uniq_id'];
            $ProjName = $getProjId['Project']['name'];
        } else {
            //Session user not assigned the project $prjid
            die;
        }

        $sqlcasedata = array();
        $getPostCase = array();
        if ($ProjId && $curCaseNo) {

            ######## get all cases
            $sqlcasedata = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.* FROM easycases as Easycase WHERE project_id='" . $ProjId . "' AND case_no=" . $curCaseNo . " AND istype='2'  ORDER BY dt_created " . $sorting);
            $countall = $this->Easycase->query("SELECT FOUND_ROWS() as total");
            if (($countall[0][0]['total'] > 5) && isset($sort_cookie)) {
                $limit1 = $countall[0][0]['total'] - 5;
                $sqlcasedata = $this->Easycase->query("SELECT  Easycase.* FROM easycases as Easycase WHERE project_id='" . $ProjId . "' AND case_no=" . $curCaseNo . " AND istype='2'  ORDER BY dt_created ASC LIMIT " . $limit1 . ",5");
            }

            ######## get users
            /* if($projUniqDtls != 'all'){
              $allMems = $this->Easycase->getMemebers($projUniqDtls);
              }else{
              $allMems = $this->Easycase->getMemebersid($ProjId);
              } */
            $allMemsArr = $this->Easycase->getMemebersid($ProjId);
            $allMems = array();
            foreach ($allMemsArr as $k => $getAllMems) {
                if (intval($oauth_return)) {
                    $allMemsArr[$k]['User']['id'] = $allMemsArr[$k]['User']['uniq_id'];
                }
                $allMemsArr[$k]['User']['name'] = $this->Format->formatText($getAllMems['User']['name']);

                unset(
                        $allMemsArr[$k]['User']['email'], $allMemsArr[$k]['User']['istype'], $allMemsArr[$k]['User']['short_name'], $allMemsArr[$k]['User']['uniq_id']
                );
                $allMems[$getAllMems['User']['id']] = $allMemsArr[$k];
            }

            //$this->Easycase->query('SET CHARACTER SET utf8');
            $getPostCase = $this->Easycase->query("SELECT * FROM easycases as Easycase WHERE project_id='" . $ProjId . "' AND case_no=" . $curCaseNo . " AND istype='1' ");
            $estimated_hours = (isset($getPostCase['0']['Easycase']) && !empty($getPostCase['0']['Easycase'])) ? $getPostCase['0']['Easycase']['estimated_hours'] : '0.0';
            $getHours = $this->Easycase->query("SELECT SUM(hours) as hours FROM easycases as Easycase WHERE project_id='" . $ProjId . "' AND case_no=" . $curCaseNo . " AND reply_type=0");
            $hours = $getHours[0][0]['hours'];

            $getcompletedtask = $this->Easycase->query("SELECT completed_task  FROM easycases as Easycase WHERE project_id='" . $ProjId . "' AND case_no=" . $curCaseNo . "  and completed_task != 0 ORDER BY id DESC LIMIT 1");
            $completedtask = $getcompletedtask[0]['Easycase']['completed_task'];
        } else {
            //$ProjId and $curCaseNo not found. This step should not, b'cos it handeled previously.
            die;
        }
        $this->loadModel('CaseRecent');
        $getCurCase = $this->CaseRecent->find('first', array('conditions' => array('CaseRecent.easycase_id' => $curCaseId, 'CaseRecent.user_id' => SES_ID, 'CaseRecent.project_id' => $ProjId), 'fields' => array('CaseRecent.id')));
        if (isset($getCurCase['CaseRecent']) && count($getCurCase['CaseRecent'])) {
            $post_caserecent['CaseRecent']['id'] = $getCurCase['CaseRecent']['id'];
        }
        $post_caserecent['CaseRecent']['easycase_id'] = $curCaseId;
        $post_caserecent['CaseRecent']['user_id'] = SES_ID;
        $post_caserecent['CaseRecent']['project_id'] = $ProjId;
        $post_caserecent['CaseRecent']['company_id'] = SES_COMP;
        if ($details == "0") {
            $post_caserecent['CaseRecent']['dt_created'] = GMT_DATETIME;
        }
        $this->CaseRecent->save($post_caserecent);
        ######## get easycase case members ################
        $usrDtlsAll = $this->Easycase->getTaskUser($ProjId, $curCaseNo);

        $allUserArr = array();
        foreach ($usrDtlsAll as $ud) {
            $allUserArr[$ud['User']['id']] = $ud;
        }
        ######## End get easycase case members ################

        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $dt = $view->loadHelper('Datetime');
        $cq = $view->loadHelper('Casequery');
        $frmt = $view->loadHelper('Format');

        $sqlcasedata1 = $this->Easycase->formatReplies($sqlcasedata, $allUserArr, $frmt, $cq, $tz, $dt);
        $sqlcasedata = $sqlcasedata1['sqlcasedata'];
        if (intval($oauth_return)) {
            foreach ($sqlcasedata as $key => $csdata) {
                $sqlcasedata[$key]['Easycase']['id'] = $sqlcasedata[$key]['Easycase']['uniq_id'];
                unset(
                        $sqlcasedata[$key]['Easycase']['uniq_id'], $sqlcasedata[$key]['Easycase']['user_id'], $sqlcasedata[$key]['Easycase']['project_id']
                );
            }
        }
        //add mahavir
        $caseStatus = $getPostCase['0']['Easycase']['status'];
        $caseLegendRep = $getPostCase['0']['Easycase']['legend'];
        $caseAutoId = $getPostCase['0']['Easycase']['id'];
        $caseTypeRep = $getPostCase['0']['Easycase']['type_id'];
        $casePriRep = $getPostCase['0']['Easycase']['priority'];
        //$caseNoRep = $getPostCase['0']['Easycase']['case_no'];
        $caseTitleRep = $getPostCase['0']['Easycase']['title'];
        $caseUniqId = $getPostCase['0']['Easycase']['uniq_id'];
        $caseUserDtls = $getPostCase['0']['Easycase']['user_id'];
        $actualDt = $getPostCase['0']['Easycase']['actual_dt_created'];
        $caseMsgRep = $getPostCase['0']['Easycase']['message'];
        $caseUserAsgn = $getPostCase['0']['Easycase']['assign_to'];
        $caseFormat = $getPostCase['0']['Easycase']['format'];
        //$caseProjectIdRep = $getPostCase['0']['Easycase']['project_id'];
        $caseDtCreated = $getPostCase['0']['Easycase']['dt_created'];
        $caseId = $getPostCase['0']['Easycase']['id'];
        $caseDuDate = $getPostCase['0']['Easycase']['due_date'];
        $caseUpdBy = $getPostCase['0']['Easycase']['updated_by'];

        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        $curdtT = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "date");
        $locDT1 = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $actualDt, "datetime");
        $created_on = $dt->facebook_style_date_time($locDT1, $curDateTz);
        $created_on_ttl = $dt->facebook_datetimestyle($locDT1);

        $updTzDate = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $caseDtCreated, "datetime");
        //$last_updated = $dt->facebook_style_date_time($updTzDate,$curDateTz);
        $last_upddtm = $dt->dateFormatOutputdateTime_day($updTzDate, $curDateTz);
        $last_updated_ttl = $dt->facebook_datetimestyle($updTzDate);

        $getMlstnFromCsId = $this->Easycase->getMilestoneName($caseId);
        if ($getMlstnFromCsId) {
            $milestone = $getMlstnFromCsId;
        } else {
            $milestone = '';
        }

        $protyCls = '';
        $protyTtl = '';
        if ($casePriRep == 0) {
            $protyCls = 'high_priority';
            $protyTtl = 'High';
        } elseif ($casePriRep == 1) {
            $protyCls = 'medium_priority';
            $protyTtl = 'Medium';
        } elseif ($casePriRep == 2) {
            $protyCls = 'low_priority';
            $protyTtl = 'Low';
        }

        //getting case_by
        $postuserArr = $cq->getUserDtlsArr($caseUserDtls, $allUserArr);
        $post_id = $postuserArr['User']['id'];
        $post_name = $postuserArr['User']['name'];
        $post_photo = $postuserArr['User']['photo'];
        $short_name = $postuserArr['User']['short_name'];

        if ($post_name && $caseUserDtls != SES_ID) {
            $case_by = $this->Format->shortLength($post_name, 20);
        } else {
            $case_by = "me";
        }

        //getting assignTo
        $assignTo = "";
        $assignUid = 0;
        if ($caseUserAsgn == SES_ID || ($caseUserDtls == SES_ID && $caseUserAsgn == 0)) {
            $assignUid = SES_ID;
        } elseif ($caseUserDtls != SES_ID && $caseUserAsgn == 0) {
            $assignUid = $caseUserDtls;
        } else {
            $assignUid = $caseUserAsgn;
        }
        $assigned = $cq->getUserDtlsArr($assignUid, $allUserArr);
        $assignTo = ucwords($frmt->formatText($assigned['User']['name'] . ' ' . $assigned['User']['last_name']));
        $asgnPic = $assigned['User']['photo'];
        $asgnEmail = $assigned['User']['email'];
        /* if($assignUid == SES_ID) {
          $assignTo = "me";
          }
          else {
          $assignTo = $this->Format->shortLength($assignTo,20);
          } */

        $csDuDtFmtT = $csDuDtFmt = '';
        if ($caseTypeRep == 10 || $caseLegendRep == 3 || $caseLegendRep == 5) {
            //$caseDueDate = $getdata['Easycase']['due_date'];
            if ($caseDuDate != "NULL" && $caseDuDate != "0000-00-00" && $caseDuDate != "" && $caseDuDate != "1970-01-01") {
                $csDuDtFmtT = $dt->facebook_datestyle($caseDuDate);
                $csDuDtFmt = $dt->dateFormatOutputdateTime_day($caseDuDate, $curDateTz, 'week');
                if (strpos($csDuDtFmt, 'Today'))
                    $csDuDtFmt = 'Due ' . $csDuDtFmt;
                else
                    $csDuDtFmt = 'Due On ' . $csDuDtFmt;
            }
            else {
                $csDuDtFmtT = '';
                $csDuDtFmt = '';
            }
        } else {
            //$caseDueDate = $getdata['Easycase']['due_date'];
            if ($caseDuDate != "NULL" && $caseDuDate != "0000-00-00" && $caseDuDate != "" && $caseDuDate != "1970-01-01") {
                if ($caseDuDate < $curdtT) {
                    $csDuDtFmtT = $dt->facebook_datestyle($caseDuDate);
                    $csDuDtFmt = '<div class="fl over-due"><span class="due-txt">Overdue</span><div class="cb" style="height:4px;"></div><div class="fl">' . $dt->dateFormatOutputdateTime_day($caseDuDate, $curDateTz, 'week') . '</div></div>';
                } else {
                    $csDuDtFmtT = $dt->facebook_datestyle($caseDuDate);
                    $csDuDtFmt = $dt->dateFormatOutputdateTime_day($caseDuDate, $curDateTz, 'week');
                    if (strpos($csDuDtFmt, 'Today') !== false)
                        $csDuDtFmt = '<span class="due-txt">Due ' . $csDuDtFmt . '</span>';
                    else
                        $csDuDtFmt = '<span class="due-txt">Due On ' . $csDuDtFmt . '</span>';
                }
            }
            else {
                $csDuDtFmtT = '';
                $csDuDtFmt = '';
            }
        }

        /* if($caseDuDate && !stristr($caseDuDate,"0000")) {
          $csDuDt = $dt->facebook_style_date_time($caseDuDate,$curDateTz,'date');
          }
          else {
          $csDuDt = '<i class="no_due_dt">None</i>';
          } */

        //Title Caption start
        if ($caseUpdBy) {
            $getlastUid = $caseUpdBy;
        } else {
            $getlastUid = $caseUserDtls;
        }

        if ($getlastUid && $getlastUid != SES_ID) {
            $usrDtls = $cq->getUserDtlsArr($getlastUid, $allUserArr);
            $lstUpdBy = ucwords($frmt->formatText($usrDtls['User']['name'] . ' ' . $usrDtls['User']['last_name']));
        } else {
            $lstUpdBy = "me";
        }

        //getting case type image
        $sql = "SELECT Type.* FROM types AS Type WHERE Type.company_id = 0 OR Type.company_id =" . SES_COMP;
        $this->loadModel('Type');
        $typeArr = $this->Type->query($sql);
        //$prjtype_name = $cq->getTypeArr($caseTypeRep,$GLOBALS['TYPE']);
        $prjtype_name = $cq->getTypeArr($caseTypeRep, $typeArr);

        //$name = $prjtype_name['Type']['name'];
        //$sname = $prjtype_name['Type']['short_name'];
        //$typImage = $this->Format->todo_typ($sname,$name);
        //getting case desc, img
        $countdata = count($sqlcasedata);
        $details = 0;
        if (trim(strip_tags(str_replace("&nbsp;", "", $caseMsgRep))) != "") {
            $details = 1;
        }

        $caseFiles = 0;
        if ($caseFormat != 2) {
            $filesArr = $this->Easycase->getCaseFiles($caseAutoId);
            if (count($filesArr)) {
                $caseFiles = 1;

                foreach ($filesArr as $fkey => $getFiles) {
                    $caseFileName = $getFiles['CaseFile']['file'];

                    $filesArr[$fkey]['CaseFile']['is_exist'] = 0;
                    if (trim($caseFileName)) {
                        $filesArr[$fkey]['CaseFile']['is_exist'] = 1; //$frmt->pub_file_exists(DIR_CASE_FILES_S3_FOLDER,$caseFileName);
                    }

                    //$filesArr[$fkey]['CaseFile']['file_shname'] = $frmt->shortLength($caseFileName,37);
                    //By Orangescrum
                    $downloadurl = $getFiles['CaseFile']['downloadurl'];
                    if (isset($downloadurl) && trim($downloadurl)) {
                        if (stristr($downloadurl, 'www.dropbox.com')) {
                            $filesArr[$fkey]['CaseFile']['format_file'] = 'db'; //'<img src="'.HTTP_IMAGES.'images/db16x16.png" alt="Dropbox" title="'.$caseFileName.'" width="16" height="16" border="0" style="border:0px solid #C3C3C3" />';//str_replace(array('"','\''), array('\'','"'), $frmt->imageType($caseFileName,25,10,1,$downloadurl));
                        } else {
                            $filesArr[$fkey]['CaseFile']['format_file'] = 'gd'; //'<img src="'.HTTP_IMAGES.'images/gd16x16.png" alt="Google" title="'.$caseFileName.'" width="16" height="16" border="0" style="border:0px solid #C3C3C3" />';//str_replace(array('"','\''), array('\'','"'), $frmt->imageType($caseFileName,25,10,1,$downloadurl));
                        }
                        //$filesArr[$fkey]['CaseFile']['fileurl'] = $downloadurl;
                        //$filesArr[$fkey]['CaseFile']['downloadurl'] = 1;
                    } else {
                        $filesArr[$fkey]['CaseFile']['format_file'] = substr(strrchr(strtolower($caseFileName), "."), 1); //str_replace(array('"','\''), array('\'','"'), $frmt->imageType($caseFileName,25,10,1));
                        $filesArr[$fkey]['CaseFile']['is_ImgFileExt'] = $frmt->validateImgFileExt($caseFileName);
                        if ($filesArr[$fkey]['CaseFile']['is_ImgFileExt']) {
                            if (USE_S3 == 0) {
                                $filesArr[$fkey]['CaseFile']['fileurl'] = HTTP_CASE_FILES . $caseFileName;
                            } else {
                                $filesArr[$fkey]['CaseFile']['fileurl'] = $frmt->generateTemporaryURL(DIR_CASE_FILES_S3 . $caseFileName);
                            }
                        }
                        $filesArr[$fkey]['CaseFile']['file_size'] = $frmt->getFileSize($getFiles['CaseFile']['file_size']);
                    }
                }
            }
        }
        //pr($filesArr);die;

        $allCaseFiles = $this->Easycase->getAllCaseFiles($ProjId, $curCaseNo);
        $allCaseFiles = $this->Easycase->formatFiles($allCaseFiles, $frmt, $tz, $dt);

        $displaySection = 1;
        if (!$details && !$caseFiles) { //!$details && !$caseFiles && !$countdata
            $displaySection = 0;
        }

        $displayCreated = 1;
        if (!$countdata) {
            $displayCreated = 0;
        }

        $pstFileExst = 0;
        if (trim($post_photo)) {
            $pstFileExst = 1; //$frmt->pub_file_exists(DIR_USER_PHOTOS_S3_FOLDER,$post_photo);
        }

        //get case message
        $caseMsgRep = $frmt->formatCms($caseMsgRep);
        $caseMsgRep = preg_replace('/<script.*>.*<\/script>/ims', '', $frmt->html_wordwrap($caseMsgRep, 80));

        //$locDT1 = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$actualDt,"datetime");
        if ($post_id == SES_ID) {
            $usrName = "me";
        } else {
            $usrName = $post_name;
        }
        $crtdBy = $this->Format->formatText($usrName);
        $frmtCrtdDt = $dt->dateFormatOutputdateTime_day($locDT1, $curDateTz);

        //get cases sort order
        $thread_sortorder = isset($_COOKIE['REPLY_SORT_ORDER']) ? trim($_COOKIE['REPLY_SORT_ORDER']) : 'DESC';

        if (isset($_COOKIE['REPLY_SORT_ORDER']) && (trim($_COOKIE['REPLY_SORT_ORDER']) == 'ASC')) {
            $ascStyle = 'style="display:inline"';
            $descStyle = 'style="display:none"';
        } else {
            $ascStyle = 'style="display:none"';
            $descStyle = 'style="display:inline"';
        }

        $usrCurArr = $cq->getUserDtlsArr(SES_ID, $allUserArr);
        if (!$usrCurArr) {
            $usrCurArr = $cq->getUserDtlsArr(SES_ID, $allMems);
        }
        $userPhoto = $usrCurArr['User']['photo'];
        //$usershort_name = $usrCurArr['User']['short_name'];
        $user_name = $usrCurArr['User']['name'] . ' ' . $usrCurArr['User']['last_name'];

        $usrFileExst = 0;
        if (trim($userPhoto)) {
            $usrFileExst = 1; //$frmt->pub_file_exists(DIR_USER_PHOTOS_S3_FOLDER,$userPhoto);
        }

        $userIds = $this->Easycase->getUserEmail($caseAutoId);
        $usrArr = array();
        if (count($userIds)) {
            foreach ($userIds as $usId) {
                array_push($usrArr, $usId['CaseUserEmail']['user_id']);
            }
        }

        //get assign option
        if ($caseUserAsgn) {
            if ($caseUserAsgn == SES_ID) {
                $checkAsgn = "me";
            } else {
                $checkAsgn = "other";
            }
        }
        if (!$caseUserAsgn && $caseUserDtls == SES_ID) {
            $checkAsgn = "me";
        } elseif (!$caseUserAsgn) {
            $checkAsgn = "me";
        }

        //get last resolved
        $last_resolved = $last_resolved_ttl = '';
        if ($caseTypeRep != 10) { // Checks for easycase type update
            $lastResDT = $this->Easycase->getLastResolved($ProjId, $curCaseNo);
            if ($lastResDT) {
                $resDT = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $lastResDT['Easycase']['dt_created'], "datetime");
                //$last_resolved = $dt->facebook_style_date_time($resDT,$curDateTz);
                $last_resolved = $dt->dateFormatOutputdateTime_day($resDT, $curDateTz);
                $last_resolved_ttl = $dt->facebook_datetimestyle($resDT);
            }
        }

        $last_closed = $last_closed_ttl = '';
        if ($caseTypeRep != 10) { // Checks for easycase type update
            $lastClsDT = $this->Easycase->getLastClosed($ProjId, $curCaseNo);
            if ($lastClsDT) {
                $clsDT = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $lastClsDT['Easycase']['dt_created'], "datetime");
                //$last_resolved = $dt->facebook_style_date_time($resDT,$curDateTz);
                $last_closed = $dt->dateFormatOutputdateTime_day($clsDT, $curDateTz);
                $last_closed_ttl = $dt->facebook_datetimestyle($clsDT);
            }
        }

        //For due date selection
        $friday = date('Y-m-d', strtotime($curDateTz . "next Friday"));
        $monday = date('Y-m-d', strtotime($curDateTz . "next Monday"));
        $tomorrow = date('Y-m-d', strtotime($curDateTz . "+1 day"));



        $caseDetail = array();
        $caseDetail['caseTitle'] = $this->Format->showlink(htmlentities($this->Format->convert_ascii($caseTitleRep), ENT_QUOTES));

        $caseDetail['estimated_hours'] = $estimated_hours;
        $caseDetail['hours'] = $hours;
        $caseDetail['completedtask'] = $completedtask;
        $caseDetail['sqlcasedata'] = $sqlcasedata;
        $caseDetail['CSrepcount'] = $sqlcasedata1['CSrepcount'];
        //$caseDetail['projectid'] = $ProjId;
        $caseDetail['projUniqId'] = $projUniqId;
        $caseDetail['projName'] = $ProjName;
        //$caseDetail['caseNo'] = $curCaseNo;
        $caseDetail['allMems'] = $allMems;
        //$caseDetail['spnajx'] = $spnajx;
        //$caseDetail['sorting'] = $sorting;
        //$caseDetail['details'] = $details;
        //$caseDetail['getPostCase'] = $getPostCase;
        //$caseDetail['count'] = $count;
        $caseDetail['total'] = $countall['0']['0']['total'];
        //$caseDetail['allUserArr'] = $userArr;
        $caseDetail['taskUsrs'] = $allUserArr;
        //$caseDetail['caseTypeArr'] = $GLOBALS['TYPE'];
        $caseDetail['crtdt'] = $created_on;
        $caseDetail['crtdtTtl'] = $created_on_ttl;
        //$caseDetail['lupdt'] = $last_updated;
        $caseDetail['lupdtTtl'] = $last_updated_ttl;
        $caseDetail['lupdtm'] = $last_upddtm;
        $caseDetail['mistn'] = ucfirst($milestone);
        $caseDetail['protyCls'] = $protyCls;
        $caseDetail['protyTtl'] = $protyTtl;
        $caseDetail['pstNm'] = $post_name;
        $caseDetail['pstPic'] = $post_photo;
        $caseDetail['shtNm'] = $short_name;
        $caseDetail['csby'] = $case_by;
        $caseDetail['csAtId'] = $caseAutoId;
        $caseDetail['asgnUid'] = $assignUid;
        $caseDetail['asgnTo'] = $assignTo;
        $caseDetail['asgnPic'] = $asgnPic;
        $caseDetail['asgnEmail'] = $asgnEmail;
        //$caseDetail['csDuDt'] = $csDuDt;
        $caseDetail['csDuDtFmtT'] = $csDuDtFmtT;
        $caseDetail['csDuDtFmt'] = $csDuDtFmt;
        $caseDetail['taskTyp'] = $prjtype_name['Type'];
        $caseDetail['csLgndRep'] = $caseLegendRep;
        $caseDetail['dispSec'] = $displaySection;
        $caseDetail['dispCrtd'] = $displayCreated;
        $caseDetail['pstFileExst'] = $pstFileExst;
        $caseDetail['csUsrDtls'] = $caseUserDtls;
        $caseDetail['dtls'] = $details;
        $caseDetail['csFiles'] = $caseFiles;
        $caseDetail['filesArr'] = $filesArr;
        $caseDetail['cntdta'] = $countdata;
        $caseDetail['csMsgRep'] = $caseMsgRep;
        $caseDetail['csProjIdRep'] = $ProjId;
        $caseDetail['crtdBy'] = $crtdBy;
        $caseDetail['frmtCrtdDt'] = $frmtCrtdDt;
        $caseDetail['thrdStOrd'] = $thread_sortorder;
        $caseDetail['ascStyle'] = $ascStyle;
        $caseDetail['descStyle'] = $descStyle;
        $caseDetail['csUniqId'] = $caseUniqId;
        $caseDetail['usrPhoto'] = $userPhoto;
        //$caseDetail['usrShrtNm'] = $usershort_name;
        $caseDetail['usrName'] = $user_name;
        $caseDetail['usrFileExst'] = $usrFileExst;

        //hidden fields value
        $caseDetail['csNoRep'] = $curCaseNo;
        $caseDetail['csTypRep'] = $caseTypeRep;
        $caseDetail['csPriRep'] = $casePriRep;

        $caseDetail['usrArr'] = $usrArr;
        $caseDetail['checkAsgn'] = $checkAsgn;
        $caseDetail['csUsrAsgn'] = $caseUserAsgn;
        $caseDetail['lstUpdBy'] = $lstUpdBy;
        $caseDetail['lstRes'] = $last_resolved;
        $caseDetail['lstRes_ttl'] = $last_resolved_ttl;
        $caseDetail['lstCls'] = $last_closed;
        $caseDetail['lstCls_ttl'] = $last_closed_ttl;
        $caseDetail['all_files'] = $allCaseFiles;
        $caseDetail['is_active'] = $is_active;

        //For due date selection
        $caseDetail['mdyCurCrtd'] = date('m/d/Y', strtotime($curDateTz));
        $caseDetail['mdyFriday'] = date('m/d/Y', strtotime($friday));
        $caseDetail['mdyMonday'] = date('m/d/Y', strtotime($monday));
        $caseDetail['mdyTomorrow'] = date('m/d/Y', strtotime($tomorrow));

        //for setting assign to
        $last = $caseDetail['sqlcasedata'][0];
        $record = end($allUserArr);
        if (SES_ID == $caseDetail['csUsrDtls'] && empty($caseDetail['sqlcasedata'])) {
            $caseDetail['Assign_to_user'] = $getPostCase['0']['Easycase']['assign_to'];
        } else {
            $caseDetail['Assign_to_user'] = isset($last['Easycase']['user_id']) ? $last['Easycase']['user_id'] : $record['User']['id'];
        }
        //print '<pre>';print_r($caseDetail);exit;
        if (intval($oauth_return)) {
            return $caseDetail;
        } else {
            $this->set('caseDetail', json_encode($caseDetail));
        }
    }

    function case_reply() {
        $this->layout = 'ajax';
        $details = 0;
        $caseId = $this->params['data']['id'];
        $type = $this->params['data']['type'];
        if (isset($this->params['data']['sortorder'])) {
            $sort_order = $this->params['data']['sortorder'];
        } elseif (isset($_COOKIE['REPLY_SORT_ORDER'])) {
            $sort_order = $_COOKIE['REPLY_SORT_ORDER'];
        } else {
            $sort_order = 'DESC';
        }
        if (isset($this->params['data']['sortorder'])) {
            setcookie('REPLY_SORT_ORDER', $sort_order, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        }
        $limit1 = isset($this->params['data']['rem_cases']) ? $this->params['data']['rem_cases'] : 0;
        if ($type == "post") {
            if ($sort_order == 'ASC') {
                $sorting = $sort_order . " LIMIT " . $limit1 . ",5";
            } else {
                $sorting = $sort_order . " LIMIT 0,5";
            }
        } else {
            $sorting = $sort_order;
        }
        ######## get case number from case uniq ID ################
        $cond2 = array(
            'conditions' => array('Easycase.isactive' => 1, 'Easycase.id' => $caseId),
            'fields' => array('DISTINCT Easycase.case_no', 'Easycase.uniq_id', 'Easycase.project_id')
        );
        $getCaseNo = $this->Easycase->find('first', $cond2);
        if (count($getCaseNo)) {
            $curCaseNo = $getCaseNo['Easycase']['case_no'];
            $caseUniqId = $getCaseNo['Easycase']['uniq_id'];
            $ProjId = $getCaseNo['Easycase']['project_id'];
            $is_active = (intval($getCaseNo['Easycase']['isactive'])) ? 1 : 0;
        }

        $sqlcasedata = array();
        $getPostCase = array();
        if ($ProjId && $curCaseNo) {
            ######## get all cases
            $query = "SELECT * FROM easycases as Easycase WHERE project_id='" . $ProjId . "' AND case_no=" . $curCaseNo . " AND istype='2' ORDER BY dt_created " . $sorting;
            $sqlcasedata = $this->Easycase->query($query);
        }

        ######## get easycase case members ################
        //$usrDtlsAll = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id='".$ProjId."' AND Easycase.case_no='".$curCaseNo."' AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");

        $usrDtlsAll = $this->Easycase->getTaskUser($ProjId, $curCaseNo);
        $userArr = array();
        foreach ($usrDtlsAll as $ud) {
            $userArr[$ud['User']['id']] = $ud;
        }
        ######## End get easycase case members ################
        //For json Feed
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $dt = $view->loadHelper('Datetime');
        $cq = $view->loadHelper('Casequery');
        $frmt = $view->loadHelper('Format');
        $sqlcasedata = $this->Easycase->formatReplies($sqlcasedata, $userArr, $frmt, $cq, $tz, $dt);

        $replyDetail = array();
        $replyDetail['sqlcasedata'] = $sqlcasedata['sqlcasedata'];
        $replyDetail['csAtId'] = $caseId;
        $replyDetail['is_active'] = $is_active;
        $this->set('replyDetail', json_encode($replyDetail));
    }

    function ajax_recent_case() {
        $this->layout = 'ajax';
        $limit_1 = $this->params['data']['limit1'];
        // $limit_2 = $this->params['data']['limit2'];
        if (isset($limit_1)) {
            $limit1 = (int) $limit_1 + 3;
            $limit2 = 3;
        } else {
            $limit1 = 0;
            $limit2 = 3;
        }
        //echo $limit1."------------".$limit2;
        $this->loadModel('CaseRecent');

        $caseid = "";
        if (isset($this->params['data']['caseid'])) {
            $caseid = $this->params['data']['caseid'];
        }
        if (isset($this->params['params']['form']['caseid'])) {
            $caseid = $this->params['params']['form']['caseid'];
        }
        $getCurCase1 = "SELECT SQL_CALC_FOUND_ROWS DISTINCT CaseRecent.easycase_id,CaseRecent.project_id,CaseRecent.dt_created FROM case_recents AS CaseRecent WHERE CaseRecent.company_id = '" . SES_COMP . "' and CaseRecent.user_id =  '" . SES_ID . "' ORDER BY CaseRecent.dt_created DESC LIMIT $limit1,$limit2";
        $getCurCase = $this->CaseRecent->query($getCurCase1);
        $tot = $this->CaseRecent->query("SELECT FOUND_ROWS() as total");
        //echo '<pre>';print_r($getCurCase);

        $i = 0;
        $caseArr = array();
        $caseArr1 = array();
        $caseMore = array();
        if (count($getCurCase)) {
            foreach ($getCurCase as $curCase) {
                $chk = $this->Format->checkMems($curCase['CaseRecent']['project_id'], "id");
                if ($chk) {
                    //$i++;
                    //if($i <= 5) {
                    array_push($caseArr, $curCase);
                    //}
                    //if($i <= 10) {
                    //array_push($caseArr1,$curCase);
                    //}
                }
            }
        }


        //$caseMore = array_slice($caseArr1, 5);
        //$this->set('caseMore',$caseMore);
        $this->set('caseArr', $caseArr); //print_r($caseArr);
        $this->set('caseid', $caseid);
        $this->set('totalRecentCase', $tot[0][0]['total']);
        $this->set('limit1', $limit1);
        //$this->set('limit2',$limit2);
    }

    function ajax_case_menu() {
        $this->layout = 'ajax';
        $proj_id = NULL;
        $pageload = 0;
        $prjUniqIdCsMenu = $this->params['data']['projUniq'];
        $pageload = $this->params['data']['pageload'];
        $page = $this->params['data']['page'];

        if (!$prjUniqIdCsMenu)
            die;

        if ($_COOKIE['CURRENT_FILTER']) {
            $filters = $_COOKIE['CURRENT_FILTER'];
        } else {
            $filters = '';
        }
        if (isset($this->params['data']['filters']) && $this->params['data']['filters'] == "files") {
            $filters = $this->params['data']['filters'];
        } elseif (isset($this->params['data']['filters']) && $this->params['data']['filters'] == "cases") {
            $filters = $this->params['data']['filters'];
        }
        if (isset($this->params['data']['case'])) {
            $case = $this->params['data']['case'];
        } else {
            $case = "";
        }
        $qry = '';
        $searchcase = '';
        //Filter Condition added in Menu filters counters
        if ($page == 'dashboard') {
            $projUniq = $this->params['data']['projUniq'];
            $curProjId = $this->params['data']['priFil'];
            $caseMenuFilters = $this->params['data']['caseMenuFilters'];
            $caseStatus = $this->params['data']['caseStatus']; // Filter by Status(legend)
            $priorityFil = $this->params['data']['priFil']; // Filter by Priority
            $caseTypes = $this->params['data']['caseTypes']; // Filter by case Types
            $caseUserId = $this->params['data']['caseMember']; // Filter by Member
            $caseAssignTo = $this->params['data']['caseAssignTo']; // Filter by AssignTo
            $caseSrch = $this->params['data']['caseSearch']; // Search by keyword
            @$case_srch = $this->params['data']['case_srch'];
            @$case_date = $this->params['data']['case_date'];
            @$case_duedate = $this->params['data']['case_due_date'];
            $milestoneIds = $this->params['data']['milestoneIds'];
            $checktype = $this->params['data']['checktype'];
            ######### Filter by Case Types ##########
            if ($caseTypes && $caseTypes != "all") {

                $qry .= $this->Format->typeFilter($caseTypes);
            }
            ######### Filter by Priority ##########
            if ($priorityFil && $priorityFil != "all") {

                $qry .= $this->Format->priorityFilter($priorityFil, $caseTypes);
            }
            ######### Filter by Member ##########
            if ($caseUserId && $caseUserId != "all") {

                $qry .= $this->Format->memberFilter($caseUserId);
            }
            ######### Filter by AssignTo ##########		/* Added by OSDEV on 08082013*/
            if ($caseAssignTo && $caseAssignTo != "all") {
                $qry .= $this->Format->assigntoFilter($caseAssignTo);
            }
            ######### Search by KeyWord ##########
            $searchcase = "";
            if (trim(urldecode($caseSrch)) && (trim($case_srch) == "")) {
                $qry = "";
                $searchcase = $this->Format->caseKeywordSearch($caseSrch, 'full');
            }
            if (trim(urldecode($case_srch)) != "") {
                $qry = "";
                $searchcase = "AND (Easycase.case_no = '$case_srch')";
            }

            if (trim(urldecode($caseSrch))) {
                if ((substr($caseSrch, 0, 1)) == '#') {
                    $qry = "";
                    $tmp = explode("#", $caseSrch);
                    $casno = trim($tmp['1']);
                    $searchcase = " AND (Easycase.case_no = '" . $casno . "')";
                }
            }

            if (trim($case_date) != "") {
                if (trim($case_date) == 'one') {
                    $one_date = date('Y-m-d H:i:s', time() - 3600);
                    $qry .= " AND Easycase.dt_created >='" . $one_date . "'";
                } else if (trim($case_date) == '24') {
                    $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 day"));
                    $qry .= " AND Easycase.dt_created >='" . $day_date . "'";
                } else if (trim($case_date) == 'week') {
                    $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 week"));
                    $qry .= " AND Easycase.dt_created >='" . $week_date . "'";
                } else if (trim($case_date) == 'month') {
                    $month_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 month"));
                    $qry .= " AND Easycase.dt_created >='" . $month_date . "'";
                } else if (trim($case_date) == 'year') {
                    $year_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 year"));
                    $qry .= " AND Easycase.dt_created >='" . $year_date . "'";
                } else if (strstr(trim($case_date), ":")) {
                    //echo $case_date;exit;
                    $ar_dt = explode(":", trim($case_date));
                    $frm_dt = $ar_dt['0'];
                    $to_dt = $ar_dt['1'];
                    $qry .= " AND DATE(Easycase.dt_created) >= '" . date('Y-m-d H:i:s', strtotime($frm_dt)) . "' AND DATE(Easycase.dt_created) <= '" . date('Y-m-d H:i:s', strtotime($to_dt)) . "'";
                }
            }
            if (trim($case_duedate) != "") {
                if (trim($case_duedate) == '24') {
                    $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 day"));
                    $qry .= " AND (DATE(Easycase.due_date) ='" . GMT_DATE . "')";
                } else if (trim($case_duedate) == 'overdue') {
                    $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 week"));
                    $qry .= " AND ( DATE(Easycase.due_date) <'" . GMT_DATE . "') AND (Easycase.legend=1 || Easycase.legend=2)";
                } else if (strstr(trim($case_duedate), ":")) {
                    //echo $case_duedate;exit;
                    $ar_dt = explode(":", trim($case_duedate));
                    $frm_dt = $ar_dt['0'];
                    $to_dt = $ar_dt['1'];
                    $qry .= " AND DATE(Easycase.due_date) >= '" . date('Y-m-d', strtotime($frm_dt)) . "' AND DATE(Easycase.due_date) <= '" . date('Y-m-d', strtotime($to_dt)) . "'";
                }
            }
        }
        //End




        $assignToMe = 0;
        $delegateTo = 0;
        $caseNew = 0;
        $caseFiles = 0;
        $caseHighPri = 0; // $latest = 0;
        //echo $prjUniqIdCsMenu;
        if ($prjUniqIdCsMenu != 'all') {
            $this->loadModel('Project');
            $this->Project->recursive = -1;
            $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $prjUniqIdCsMenu, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
            if (count($projArr)) {
                $proj_id = $projArr['Project']['id'];
            }
            if (!$proj_id) {
                die;
            }
            //AssigntoMe
            $assignToMe = $this->Easycase->query('SELECT COUNT(DISTINCT Easycase.id) AS asigntocnt FROM easycases AS Easycase WHERE Easycase.isactive=1 AND Easycase.istype=1 AND Easycase.project_id=' . $proj_id . ' AND (Easycase.assign_to=' . SES_ID . ' OR ( Easycase.assign_to=0 AND Easycase.user_id=' . SES_ID . ')) ' . $qry . ' ' . $searchcase);

//			$assignToMe = $this->Easycase->find('count', array('conditions' => array(
//			"OR" => array(
//			'AND' => array(
//			'Easycase.isactive'   => 1,
//			'Easycase.istype'     => 1,
//			'Easycase.project_id' => $proj_id,
//			'Easycase.assign_to' =>SES_ID 
//			),
//			array(
//			'Easycase.isactive'  => 1,
//			'Easycase.istype'    => 1,
//			'Easycase.project_id' => $proj_id,
//			'Easycase.assign_to' => '0',
//			'Easycase.user_id' => SES_ID))
//			),'fields' => 'DISTINCT Easycase.id'));
//		
            $delegateToArr = $this->Easycase->query("SELECT COUNT(DISTINCT Easycase.id) as total FROM `easycases` AS `Easycase` WHERE Easycase.isactive='1' AND Easycase.istype='1' AND Easycase.project_id='$proj_id' AND Easycase.assign_to!='0' AND Easycase.assign_to!='" . SES_ID . "' AND Easycase.user_id='" . SES_ID . "' " . $qry . " " . $searchcase);
            $delegateTo = $delegateToArr[0][0]['total'];

            //$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
            //$latest = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $proj_id,'Easycase.dt_created >'=> $before,'Easycase.dt_created <='=> GMT_DATETIME),'fields' => 'DISTINCT Easycase.id'));

            /* if($latest == 0){
              $rest=$this->Easycase->query("SELECT dt_created FROM easycases WHERE project_id ='".$proj_id."' ORDER BY dt_created DESC LIMIT 0 , 1");
              @$sdate=explode(" ",@$rest[0]['easycases']['dt_created']);
              $before=@$sdate[0];
              $latest = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $proj_id,'Easycase.dt_created >='=> $before,'Easycase.dt_created <='=> GMT_DATETIME),'fields' => 'DISTINCT Easycase.id'));
              } */
            $caseCount = $this->Easycase->query("SELECT COUNT(CaseFile.id) as count FROM easycases as Easycase,case_files as CaseFile WHERE Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND Easycase.project_id='" . $proj_id . "' AND CaseFile.isactive='1'");
            $caseFiles = $caseCount[0][0]['count'];

            //$caseNew = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $proj_id),'fields' => 'DISTINCT Easycase.id'));
            $caseNew = $this->Easycase->query('SELECT COUNT(DISTINCT Easycase.id) AS newcount FROM easycases Easycase WHERE Easycase.isactive=1 AND Easycase.istype= 1 AND Easycase.project_id =' . $proj_id . ' ' . $qry . $searchcase);

            //$closeCase = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.legend' => 3,'Easycase.type_id !=' => 10,'Easycase.istype' => 1,'Easycase.project_id' => $proj_id),'fields' => 'DISTINCT Easycase.id'));
            //$bugCase = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.type_id' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $proj_id),'fields' => 'DISTINCT Easycase.id'));
            $cur_dt = date('Y-m-d', strtotime(GMT_DATETIME));
            $ovrdueCase = $this->Easycase->query('SELECT COUNT(DISTINCT Easycase.id) as ovrduecount FROM easycases Easycase WHERE Easycase.isactive=1 AND Easycase.due_date !="" AND Easycase.due_date !="0000-00-00" AND Easycase.due_date !="1970-01-01" AND Easycase.due_date < "' . $cur_dt . '" AND (Easycase.legend =1 || Easycase.legend=2) AND Easycase.istype= 1 AND Easycase.project_id=' . $proj_id . " " . $qry . $searchcase);

            //$caseHighPri = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $proj_id,'Easycase.priority 	' => 0),'fields' => 'DISTINCT Easycase.id'));
            $caseHighPri = $this->Easycase->query('SELECT COUNT(DISTINCT Easycase.id) as hpcount FROM easycases Easycase WHERE Easycase.isactive = 1 AND Easycase.istype= 1 AND Easycase.project_id =' . $proj_id . ' AND Easycase.priority = 0 AND Easycase.type_id != 10 ' . $qry . $searchcase);

            //$this->loadModel('Milestone');
            //$milestone = $this->Milestone->query("SELECT COUNT(DISTINCT m.id) AS total FROM milestones AS m,easycase_milestones AS em WHERE m.project_id='".$proj_id."' and em.milestone_id=m.id and em.project_id='".$proj_id."' and m.company_id='".SES_COMP."' and m.isactive='1' order by m.end_date ASC,m.title ASC");
        }

        if ($prjUniqIdCsMenu == 'all') {

            $cond = array('conditions' => array('ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('DISTINCT Project.id'), 'order' => array('ProjectUser.dt_visited DESC'));

            $ProjectUser = ClassRegistry::init('ProjectUser');
            $ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $allProjArr = $ProjectUser->find('all', $cond);

            $ids = array();
            $idlist = '';
            foreach ($allProjArr as $csid) {
                $idlist .= '\'' . $csid['Project']['id'] . '\',';
                array_push($ids, $csid['Project']['id']);
            }
            $idlist = trim($idlist, ',');
            $cur_dt = date('Y-m-d', strtotime(GMT_DATETIME));
            $assignToMe = $this->Easycase->query('SELECT COUNT(DISTINCT Easycase.id) AS asigntocnt FROM easycases AS Easycase WHERE Easycase.isactive=1 AND Easycase.istype=1 AND Easycase.project_id IN(' . $idlist . ') AND (Easycase.assign_to=' . SES_ID . ' OR ( Easycase.assign_to=0 AND Easycase.user_id=' . SES_ID . ')) ' . $qry . ' ' . $searchcase);
//			$assignToMe = $this->Easycase->find('count', array('conditions' => array(
//			"OR" => array(
//			'AND' => array(
//			'Easycase.isactive'   => 1,
//			'Easycase.istype'     => 1,
//			'Easycase.project_id' => $ids,
//			'Easycase.assign_to' =>SES_ID 
//			),
//			array(
//			'Easycase.isactive'  => 1,
//			'Easycase.istype'    => 1,
//			'Easycase.project_id' => $ids,
//			'Easycase.assign_to' => '0',
//			'Easycase.user_id' => SES_ID))
//			),'fields' => 'DISTINCT Easycase.id'));

            if (count($ids)) {
                //$delegateToArr = $this->Easycase->query("SELECT COUNT(id) as total FROM `easycases` AS `Easycase` WHERE Easycase.isactive='1' AND Easycase.istype='1' AND Easycase.project_id IN (".implode(",",$ids).") AND Easycase.assign_to!='0' AND Easycase.assign_to!='".SES_ID."' AND Easycase.user_id='".SES_ID."'");
                $delegateToArr = $this->Easycase->query("SELECT COUNT(DISTINCT Easycase.id) as total FROM `easycases` AS `Easycase` WHERE Easycase.isactive='1' AND Easycase.istype='1' AND Easycase.project_id IN(" . $idlist . ") AND Easycase.assign_to!='0' AND Easycase.assign_to!='" . SES_ID . "' AND Easycase.user_id='" . SES_ID . "' " . $qry . " " . $searchcase);
                $delegateTo = $delegateToArr[0][0]['total'];

                $caseCount = $this->Easycase->query("SELECT COUNT(CaseFile.id) as count FROM easycases as Easycase,case_files as CaseFile WHERE Easycase.id=CaseFile.easycase_id AND Easycase.isactive='1' AND Easycase.project_id IN (" . implode(",", $ids) . ") AND Easycase.project_id!=0 AND CaseFile.isactive='1'");
                $caseFiles = $caseCount[0][0]['count'];

                //$this->loadModel('Milestone');
                //$milestone = $this->Milestone->query("SELECT COUNT(DISTINCT m.id) AS total FROM milestones AS m,easycase_milestones AS em WHERE m.project_id IN (".implode(",",$ids).") and em.milestone_id=m.id and em.project_id IN (".implode(",",$ids).") and m.company_id='".SES_COMP."' and m.isactive='1' order by m.end_date ASC,m.title ASC");
            }

            //$caseNew = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids),'fields' => 'DISTINCT Easycase.id'));
            $caseNew = $this->Easycase->query('SELECT COUNT(DISTINCT Easycase.id) AS newcount FROM easycases Easycase WHERE Easycase.isactive=1 AND Easycase.istype= 1 AND Easycase.project_id IN(' . $idlist . ') ' . $qry . $searchcase);

            //$before = date('Y-m-d H:i:s',strtotime(GMT_DATETIME."-2 day"));
            //$latest = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids,'Easycase.dt_created >'=> $before,'Easycase.dt_created <='=> GMT_DATETIME),'fields' => 'DISTINCT Easycase.id'));
            //$closeCase = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.legend' => 3,'Easycase.type_id !=' => 10,'Easycase.istype' => 1,'Easycase.project_id' => $ids),'fields' => 'DISTINCT Easycase.id'));
            //$bugCase = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.type_id' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids),'fields' => 'DISTINCT Easycase.id'));
            $cur_dt = date('Y-m-d', strtotime(GMT_DATETIME));
            $ovrdueCase = $this->Easycase->query('SELECT COUNT(DISTINCT Easycase.id) as ovrduecount FROM easycases Easycase WHERE Easycase.isactive=1 AND Easycase.due_date !="" AND Easycase.due_date !="0000-00-00" AND Easycase.due_date !="1970-01-01" AND Easycase.due_date < "' . $cur_dt . '" AND (Easycase.legend =1 || Easycase.legend=2) AND Easycase.istype= 1 AND Easycase.project_id IN (' . $idlist . ')' . $qry . $searchcase);

            //$caseHighPri = $this->Easycase->find('count', array('conditions'=>array('Easycase.isactive' => 1,'Easycase.istype' => 1,'Easycase.project_id' => $ids,'Easycase.priority 	' => 0),'fields' => 'DISTINCT Easycase.id'));
            $caseHighPri = $this->Easycase->query('SELECT COUNT(DISTINCT Easycase.id) as hpcount FROM easycases Easycase WHERE Easycase.isactive = 1 AND Easycase.istype= 1 AND Easycase.project_id IN(' . $idlist . ') AND Easycase.priority = 0 AND Easycase.type_id != 10 ' . $qry . $searchcase);
        }
        $resCaseMenu = array();

        //$resCaseMenu['page'] = $page;
        $resCaseMenu['assignToMe'] = $assignToMe[0][0]['asigntocnt'];
        $resCaseMenu['delegateTo'] = $delegateTo;
        //$resCaseMenu['latest'] = $latest;
        $resCaseMenu['caseFiles'] = $caseFiles;
        $resCaseMenu['caseNew'] = $caseNew[0][0]['newcount'];
        //$resCaseMenu['closeCase'] = $closeCase;
        //$resCaseMenu['filters'] = $filters;
        //$resCaseMenu['cs'] = $case;
        $resCaseMenu['overdue'] = $ovrdueCase[0][0]['ovrduecount'];
        $resCaseMenu['highPri'] = $caseHighPri[0][0]['hpcount'];
        //$resCaseMenu['total_milestone'] = $milestone['0']['0']['total'];
        $this->set('resCaseMenu', json_encode($resCaseMenu));
    }

    /* Optimized Code */

    function ajax_case_status() {
        $this->layout = 'ajax';
        $proj_id = NULL;
        $pageload = 0;
        if (isset($this->params['data']['projUniq'])) {
            $proj_uniq_id = $this->params['data']['projUniq'];
        }
        $pageload = $this->params['data']['pageload'];

        if ($proj_uniq_id != 'all') {
            $this->loadModel('Project');
            $proj_id = 0;
            $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'Project.isactive' => 1), 'fields' => array('Project.id')));
            if (count($projArr)) {
                $proj_id = $projArr['Project']['id'];
            }
        }

        $projUniq = $proj_uniq_id;
        $curProjId = $proj_id;
        $caseMenuFilters = $this->params['data']['caseMenuFilters'];

        $caseStatus = $this->params['data']['caseStatus']; // Filter by Status(legend)
        $priorityFil = $this->params['data']['priFil']; // Filter by Priority
        $caseTypes = $this->params['data']['caseTypes']; // Filter by case Types
        $caseUserId = $this->params['data']['caseMember']; // Filter by Member
        $caseAssignTo = $this->params['data']['caseAssignTo']; // Filter by AssignTo
        $caseSrch = $this->params['data']['caseSearch']; // Search by keyword
        @$case_srch = $this->params['data']['case_srch'];
        @$case_date = $this->params['data']['case_date'];
        @$case_duedate = $this->params['data']['case_due_date'];
        $milestoneIds = $this->params['data']['milestoneIds'];
        $checktype = $this->params['data']['checktype'];
        $milestoneId = isset($this->data['milestoneId']) ? $this->data['milestoneId'] : '';
        $qry = "";

        ######### Filter by Status ##########
        //Commented by GK as per the requirement of PG sir on dt:-04th Apr 2013 -- starts
        /* if($caseStatus != "all" && $this->params['data']['page_type'] != 'ajax_status') {

          $qry.= $this->Format->statusFilter($caseStatus);
          $stsLegArr = $caseStatus."-"."";
          $expStsLeg = explode("-",$stsLegArr);
          if(!in_array("upd",$expStsLeg))
          {
          $qry.= " AND Easycase.type_id !=10";
          }

          } */
        //Ends
        /* elseif($caseMenuFilters != "closecase") {
          $qry.= " AND (Easycase.legend !='3' OR Easycase.type_id ='10')";
          } */

        if (!$milestoneId) {
            ######### Filter by Case Types ##########
            if (trim($caseTypes) && $caseTypes != "all" && $this->params['data']['page_type'] != 'ajax_types') {

                $qry .= $this->Format->typeFilter($caseTypes);
            }
            ######### Filter by Priority ##########
            if (trim($priorityFil) && $priorityFil != "all" && $this->params['data']['page_type'] != 'ajax_priority') {

                $qry .= $this->Format->priorityFilter($priorityFil, $caseTypes);
            }
            ######### Filter by Member ##########
            if (trim($caseUserId) && $caseUserId != "all" && $this->params['data']['page_type'] != 'ajax_members') {

                $qry .= $this->Format->memberFilter($caseUserId);
            }
            ######### Filter by AssignTo ##########		/* Added by OSDEV on 08082013*/
            if (trim($caseAssignTo) && $caseAssignTo != "all" && $this->params['data']['page_type'] != 'ajax_assignto') {
                $qry .= $this->Format->assigntoFilter($caseAssignTo);
            }
            ######### Search by KeyWord ##########
            $searchcase = "";
            if (trim(urldecode($caseSrch)) && (trim($case_srch) == "")) {
                $qry = "";
                $searchcase = $this->Format->caseKeywordSearch($caseSrch, 'full');
            }
            if (trim(urldecode($case_srch)) != "") {
                $qry = "";
                $searchcase = "AND (Easycase.case_no = '$case_srch')";
            }

            if (trim(urldecode($caseSrch))) {
                if ((substr($caseSrch, 0, 1)) == '#') {
                    $qry = "";
                    $tmp = explode("#", $caseSrch);
                    $casno = trim($tmp['1']);
                    $searchcase = " AND (Easycase.case_no = '" . $casno . "')";
                }
            }

            if (trim($case_date) != "") {
                if (trim($case_date) == 'one') {
                    $one_date = date('Y-m-d H:i:s', time() - 3600);
                    $qry .= " AND Easycase.dt_created >='" . $one_date . "'";
                } else if (trim($case_date) == '24') {
                    $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 day"));
                    $qry .= " AND Easycase.dt_created >='" . $day_date . "'";
                } else if (trim($case_date) == 'week') {
                    $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 week"));
                    $qry .= " AND Easycase.dt_created >='" . $week_date . "'";
                } else if (trim($case_date) == 'month') {
                    $month_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 month"));
                    $qry .= " AND Easycase.dt_created >='" . $month_date . "'";
                } else if (trim($case_date) == 'year') {
                    $year_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 year"));
                    $qry .= " AND Easycase.dt_created >='" . $year_date . "'";
                } else if (strstr(trim($case_date), ":")) {
                    //echo $case_date;exit;
                    $ar_dt = explode(":", trim($case_date));
                    $frm_dt = $ar_dt['0'];
                    $to_dt = $ar_dt['1'];
                    $qry .= " AND DATE(Easycase.dt_created) >= '" . date('Y-m-d H:i:s', strtotime($frm_dt)) . "' AND DATE(Easycase.dt_created) <= '" . date('Y-m-d H:i:s', strtotime($to_dt)) . "'";
                }
            }

            if (trim($case_duedate) != "") {
                if (trim($case_duedate) == '24') {
                    $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 day"));
                    $qry .= " AND (DATE(Easycase.due_date) ='" . GMT_DATE . "')";
                } else if (trim($case_duedate) == 'overdue') {
                    $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 week"));
                    $qry .= " AND ( DATE(Easycase.due_date) <'" . GMT_DATE . "') AND (Easycase.legend=1 || Easycase.legend=2)";
                } else if (strstr(trim($case_duedate), ":")) {
                    //echo $case_duedate;exit;
                    $ar_dt = explode(":", trim($case_duedate));
                    $frm_dt = $ar_dt['0'];
                    $to_dt = $ar_dt['1'];
                    $qry .= " AND DATE(Easycase.due_date) >= '" . date('Y-m-d', strtotime($frm_dt)) . "' AND DATE(Easycase.due_date) <= '" . date('Y-m-d', strtotime($to_dt)) . "'";
                }
            }
        }
        $qry1 = "";
        ######### Filter by Assign To ##########
        if ($caseMenuFilters == "assigntome") {
            $qry .= " AND ((Easycase.assign_to=" . SES_ID . ") OR (Easycase.assign_to=0 AND Easycase.user_id=" . SES_ID . "))";
            $qry1 .= " AND ((Easycase.assign_to=" . SES_ID . ") OR (Easycase.assign_to=0 AND Easycase.user_id=" . SES_ID . "))";
        } elseif ($caseMenuFilters == "newwip") {
            $qry .= " AND (Easycase.legend='1' OR Easycase.legend='2') AND Easycase.type_id !='10' ";
            $qry1 .= " AND (Easycase.legend='1' OR Easycase.legend='2') AND Easycase.type_id !='10' ";
        } elseif ($caseMenuFilters == "highpriority") {
            $qry .= " AND Easycase.priority='0'  ";
            $qry1 .= " AND Easycase.priority='0'  ";
            ;
        }
        ######### Filter by Delegate To ##########
        elseif ($caseMenuFilters == "delegateto") {
            $qry .= " AND Easycase.assign_to!=0 AND Easycase.assign_to!=" . SES_ID . " AND Easycase.user_id=" . SES_ID;
            $qry1 .= " AND Easycase.assign_to!=0 AND Easycase.assign_to!=" . SES_ID . " AND Easycase.user_id=" . SES_ID;
        }
        ######### Filter by Close case ##########
        elseif ($caseMenuFilters == "closecase") {
            $qry .= " AND Easycase.legend='3' AND Easycase.type_id !='10'";
            $qry1 .= " AND Easycase.legend='3' AND Easycase.type_id !='10'";
        }
        ######### Filter by Bug case ##########
        elseif ($caseMenuFilters == "overdue") { /* By OSDEV 0201 */
            $cur_dt = date('Y-m-d', strtotime(GMT_DATETIME));
            $qry .= " AND Easycase.due_date !='' AND Easycase.due_date !='0000-00-00' AND Easycase.due_date !='1970-01-01' AND Easycase.due_date < '" . $cur_dt . "' AND (Easycase.legend =1 || Easycase.legend=2) ";
            $qry1 .= " AND Easycase.due_date !='' AND Easycase.due_date !='0000-00-00' AND Easycase.due_date !='1970-01-01' AND Easycase.due_date < '" . $cur_dt . "' AND (Easycase.legend =1 || Easycase.legend=2) ";
        }
        ######### Filter by Latest ##########
        elseif ($caseMenuFilters == "latest") {
            $qry_rest = $qry;
            $before = date('Y-m-d H:i:s', strtotime(GMT_DATETIME . "-2 day"));
            $all_rest = " AND Easycase.dt_created > '" . $before . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
            $qry_rest .= " AND Easycase.dt_created > '" . $before . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
        }

        if ($caseMenuFilters == "latest" && $projUniq != 'all') {
            $CaseCount3 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE istype='1' AND Easycase.isactive='1' AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  " . $searchcase . " " . trim($qry_rest));
            $CaseCount = $CaseCount3['0']['0']['count'];

            if ($CaseCount == 0) {
                $rest = $this->Easycase->query("SELECT dt_created FROM easycases WHERE project_id ='" . $curProjId . "' ORDER BY dt_created DESC LIMIT 0 , 1");
                @$sdate = explode(" ", @$rest[0]['easycases']['dt_created']);
                $qry .= " AND Easycase.dt_created >= '" . @$sdate[0] . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";

                $qry1 .= " AND Easycase.dt_created >= '" . @$sdate[0] . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
            } else {
                $qry = $qry . $all_rest;
                $qry1 .= $all_rest;
            }
        } else if ($caseMenuFilters == "latest" && $projUniq == 'all') {
            $qry = $qry . $all_rest;
            $qry1 .= $all_rest;
        }

        $mlstnQ1 = "";
        $mlstnQ2 = "";
        if ($caseMenuFilters == 'kanban' && $milestoneId) {
            $mlstnQ1 = ",easycase_milestones as em,milestones as m ";
            $mlstnQ2 = " AND em.easycase_id=Easycase.id AND em.milestone_id=m.id  AND em.milestone_id=" . $milestoneId . " ";
        } else if ($caseMenuFilters == "milestone") {
            $mstIds = array();
            if ($milestoneIds != "all" && strstr($milestoneIds, "-")) {
                $expMilestoneIds = explode("-", $milestoneIds);
                foreach ($expMilestoneIds as $msid) {
                    if ($msid) {
                        $mstIds[] = $msid;
                    }
                }
                if (count($mstIds)) {
                    $mlstFilter = " AND em.milestone_id IN (" . implode(",", $mstIds) . ") ";
                }
            }
            $mlstnQ1 = ",easycase_milestones as em,milestones as m ";
            if ($checktype != 'completed') {
                $mlst = " AND m.isactive='1' ";
            } else {
                $mlst = " AND m.isactive='0' ";
            }
            $mlstnQ2 = " AND em.easycase_id=Easycase.id AND em.milestone_id=m.id " . trim($mlst . $mlstFilter);
        }

        $Easycase = ClassRegistry::init('Easycase');
        $Easycase->recursive = -1;


        if ($proj_uniq_id == 'all') {
            $projQry = "AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1')";

            $projQryMem = "";
        } else {
            $projQry = "AND Easycase.project_id='" . $proj_id . "'";

            $projQryMem = "AND ProjectUser.project_id='" . $proj_id . "'";
        }

        if ($this->params['data']['page_type'] == 'ajax_priority') {
            $query_pri_high1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase" . $mlstnQ1 . " WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND priority='0' AND Easycase.project_id!=0 " . $mlstnQ2 . $projQry . " " . trim($qry) . "");
            $query_pri_high = $query_pri_high1['0']['0']['count'];

            $query_pri_medium1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase" . $mlstnQ1 . " WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND priority='1' AND Easycase.project_id!=0 " . $mlstnQ2 . $projQry . " " . trim($qry) . "");
            $query_pri_medium = $query_pri_medium1['0']['0']['count'];

            $query_pri_low1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase" . $mlstnQ1 . " WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND priority='2' AND Easycase.project_id!=0 " . $mlstnQ2 . $projQry . " " . trim($qry) . "");
            $query_pri_low = $query_pri_low1['0']['0']['count'];

            $this->set('proj_uniq_id', $proj_uniq_id);
            $this->set('proj_id', $proj_id);
            $this->set('CookiePriority', $_COOKIE['PRIORITY']);
            $this->set('query_pri_high', $query_pri_high);
            $this->set('query_pri_medium', $query_pri_medium);
            $this->set('query_pri_low', $query_pri_low);

            $this->render('ajax_priority', 'ajax');
        } elseif ($this->params['data']['page_type'] == 'ajax_members') {

            $memArr = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.dt_last_login, (select count(Easycase.id) from easycases as Easycase" . $mlstnQ1 . " where Easycase.user_id=User.id and Easycase.istype='1' and User.isactive='1' and Easycase.isactive='1' " . $mlstnQ2 . $projQry . " " . trim($qry) . ") as cases FROM users as User,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' " . $projQryMem . " AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name");

            $this->set('proj_uniq_id', $proj_uniq_id);
            $this->set('proj_id', $proj_id);
            $this->set('memArr', $memArr);
            $this->set('CookieMem', $_COOKIE['MEMBERS']);

            $this->render('ajax_members', 'ajax');
        } elseif ($this->params['data']['page_type'] == 'ajax_assignto') {
            $asnArr = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.dt_last_login,  (select count(Easycase.id) from easycases as Easycase" . $mlstnQ1 . " where Easycase.assign_to = User.id and Easycase.istype='1' and User.isactive='1' and Easycase.isactive='1' " . $mlstnQ2 . $projQry . " " . trim($qry) . ") as cases FROM users as User,project_users as ProjectUser,company_users as CompanyUser,projects as Project WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' $projQryMem  AND Project.id=ProjectUser.project_id AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.short_name");

            $this->set('proj_uniq_id', $proj_uniq_id);
            $this->set('proj_id', $proj_id);
            $this->set('asnArr', $asnArr);
            $this->set('CookieAsn', $_COOKIE['ASSIGNTO']);

            $this->render('ajax_assignto', 'ajax');
        } elseif ($this->params['data']['page_type'] == 'ajax_types') {
            $types_sql = "select DISTINCT t.name,t.id,t.short_name,t.company_id,(select count(Easycase.id) from easycases as Easycase" . $mlstnQ1 . " where Easycase.istype='1' AND Easycase.type_id=t.id AND Easycase.isactive='1' " . $mlstnQ2 . $projQry . " " . trim($qry) . ") as count from types as t 
	WHERE CASE WHEN (SELECT COUNT(*) AS total FROM type_companies WHERE company_id = " . SES_COMP . " HAVING total >=1) THEN id IN (SELECT type_id FROM type_companies WHERE company_id = " . SES_COMP . ") ELSE company_id = 0 End 
	ORDER BY t.seq_order";
            $typeArr = $this->Easycase->query($types_sql);

            $this->set('proj_uniq_id', $proj_uniq_id);
            $this->set('proj_id', $proj_id);
            $this->set('typeArr', $typeArr);
            $this->set('CookieTypes', $_COOKIE['CS_TYPES']);

            $this->render('ajax_types', 'ajax');
        } elseif (!$this->params['data']['page_type'] || $this->params['data']['page_type'] == 'ajax_status') {
            $query_All = 0;
            $query_New = 0;
            $query_Open = 0;
            $query_Close = 0;
            $query_Start = 0;
            $query_Resolve = 0;
            $query_Attch = 0;
            $query_Upd = 0;
            $resCaseWidget = array();
            //$query_All1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase".$mlstnQ1." WHERE Easycase.istype='1' AND Easycase.type_id!='10' AND  Easycase.isactive='1' AND Easycase.project_id!=0 ".$mlstnQ2.$projQry." ".trim($qry)."");
            //$query_All=$query_All1['0']['0']['count'];
            //echo "SELECT COUNT(Easycase.id) as count,Easycase.legend FROM easycases as Easycase".$mlstnQ1." WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend='1' AND Easycase.type_id!='10' AND Easycase.project_id!=0 ".$mlstnQ2.$projQry." ".trim($qry);
            $common_qry = $this->Easycase->query("SELECT COUNT(Easycase.id) as count,if(Easycase.type_id=10,10,Easycase.legend) AS legend FROM easycases as Easycase" . $mlstnQ1 . " WHERE Easycase.istype='1' AND  Easycase.isactive='1'  AND Easycase.project_id!=0 " . $mlstnQ2 . $projQry . " " . trim($qry) . " GROUP BY if(Easycase.type_id=10,10,Easycase.legend)");
            foreach ($common_qry AS $key => $val) {
                if ($val[0]['legend'] == 1) {
                    $query_New = $val[0]['count'];
                } elseif ($val[0]['legend'] == 2 || $val[0]['legend'] == 4) {
                    $query_Open += $val[0]['count'];
                } elseif ($val[0]['legend'] == 3) {
                    $query_Close = $val[0]['count'];
                } elseif ($val[0]['legend'] == 5) {
                    $query_Resolve = $val[0]['count'];
                }
                if ($val[0]['legend'] == 10) {
                    $query_Upd = $val[0]['count'];
                } else {
                    $query_All += $val[0]['count'];
                }
            }
//			$query_New1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count,Easycase.legend FROM easycases as Easycase".$mlstnQ1." WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend='1' AND Easycase.type_id!='10' AND Easycase.project_id!=0 ".$mlstnQ2.$projQry." ".trim($qry)."");
//			$query_New=$query_New1['0']['0']['count'];
//			
//			$query_Open1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase".$mlstnQ1." WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND (Easycase.legend='2' || Easycase.legend='4') AND Easycase.type_id!='10' AND Easycase.project_id!=0 ".$mlstnQ2.$projQry." ".trim($qry)."");
//			$query_Open=$query_Open1['0']['0']['count'];
//			
//			$query_Close1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase".$mlstnQ1." WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend='3' AND Easycase.type_id!='10' AND Easycase.project_id!=0 ".$mlstnQ2.$projQry." ".trim($qry)."");
//			$query_Close=$query_Close1['0']['0']['count'];
//	
//			$query_Resolve1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase".$mlstnQ1." WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.legend='5' AND Easycase.type_id!='10' AND Easycase.project_id!=0 ".$mlstnQ2.$projQry." ".trim($qry)."");
//			$query_Resolve=$query_Resolve1['0']['0']['count'];
//			
//			$query_Upd1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase".$mlstnQ1." WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.type_id='10' AND Easycase.project_id!=0 ".$mlstnQ2.$projQry." ".trim($qry)."");
//			$query_Upd=$query_Upd1['0']['0']['count'];

            if ($this->params['data']['page_type'] == 'ajax_status') {
                $query_Attch1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase" . $mlstnQ1 . " WHERE Easycase.istype='1' AND  Easycase.isactive='1' AND Easycase.format='1' AND Easycase.project_id!=0 " . $mlstnQ2 . $projQry . " " . trim($qry) . "");
                $query_Attch = $query_Attch1['0']['0']['count'];

                $this->set('projuniq', $proj_uniq_id);
                $this->set('pageload', $pageload);

                $this->set('query_All', $query_All);
                $this->set('query_New', $query_New);
                $this->set('query_Open', $query_Open);
                $this->set('query_Close', $query_Close);
                $this->set('query_Resolve', $query_Resolve);
                $this->set('query_Start', $query_Start);
                $this->set('query_Attch', $query_Attch);
                $this->set('query_Upd', $query_Upd);

                $this->set('CookieStatus', $_COOKIE['STATUS']);
                $this->render('ajax_status', 'ajax');
            } else {
                $resCaseWidget['al'] = $query_All;
                $resCaseWidget['nw'] = $query_New;
                $resCaseWidget['opn'] = $query_Open;
                $resCaseWidget['cls'] = $query_Close;
                $resCaseWidget['rslv'] = $query_Resolve;
                $resCaseWidget['upd'] = $query_Upd;

                $this->set('resCaseWidget', json_encode($resCaseWidget));
                $this->render('ajax_case_status', 'ajax');
            }
        }
    }

    function files($type = 'cases', $files = NULL) {
        
    }

    function ajax_milestones() {
        $this->layout = 'ajax';
        $proj_id = NULL;
        $pageload = 0;
        $projUniq = $this->params['data']['projUniq'];

        $checktype = $this->params['data']['checktype'];

        if ($checktype == "completed") {
            $qr = "and m.isactive='0'";
        } else {
            $qr = "and m.isactive='1'";
        }

        $this->loadModel('Milestone');
        if ($projUniq != 'all') {
            $milestones = array();

            $proj_id = 0;
            $this->loadModel('Project');
            $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $projUniq, 'Project.isactive' => 1), 'fields' => array('Project.id')));
            if (count($projArr)) {
                $proj_id = $projArr['Project']['id'];
            }

            $milestones = $this->Milestone->query("select DISTINCT m.id,m.title,m.end_date, (select count(em.id) from easycase_milestones as em,easycases as e where em.milestone_id=m.id and e.id=em.easycase_id and e.isactive='1') as count from milestones as m,easycase_milestones as em where m.project_id='" . $proj_id . "' and em.milestone_id=m.id and em.project_id='" . $proj_id . "' and m.company_id='" . SES_COMP . "' " . $qr . " order by m.end_date ASC,m.title ASC");

            $this->set('milestones', $milestones);
        }
        if ($projUniq == 'all') {
            $milestones = array();

            $cond = array('conditions' => array('ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP, 'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'), 'order' => array('ProjectUser.dt_visited DESC'));

            $ProjectUser = ClassRegistry::init('ProjectUser');
            $ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $allProjArr = $ProjectUser->find('all', $cond);
            $ids = array();
            foreach ($allProjArr as $csid) {
                array_push($ids, $csid['Project']['id']);
            }

            $milestones = $this->Milestone->query("select DISTINCT m.id,m.title,m.end_date, (select count(em.id) from easycase_milestones as em,easycases as e where em.milestone_id=m.id and e.id=em.easycase_id and e.isactive='1') as count from milestones as m,easycase_milestones as em where m.project_id IN (" . implode(",", $ids) . ") and em.milestone_id=m.id and em.project_id IN (" . implode(",", $ids) . ") and m.company_id='" . SES_COMP . "' " . $qr . " order by m.end_date ASC,m.title ASC");
            $this->set('milestones', $milestones);
        }
    }

    function ajax_project() {
        $this->layout = 'ajax';

        $proj_id = NULL;
        $pageload = 0;
        $proj_uniq_id = $this->params['data']['projUniq'];
        $pageload = $this->params['data']['pageload'];

        $this->loadModel('ProjectUser');

        $proj_all_cond = array(
            'conditions' => array('ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1),
            'fields' => array('DISTINCT Project.id', 'Project.name', 'Project.uniq_id'),
            'order' => array('ProjectUser.dt_visited DESC')
        );

        $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
        $projAll = $this->ProjectUser->find('all', $proj_all_cond);

        $this->set('pageload', $pageload);
        $this->set('proj_uniq_id', $proj_uniq_id);
        $this->set('projAll', $projAll);
    }

    function ajax_priority() {
        $this->layout = 'ajax';

        $proj_id = NULL;
        $pageload = 0;
        $proj_uniq_id = $this->params['data']['projUniq'];
        $pageload = $this->params['data']['pageload'];
        $caseMenuFilters = "";
        if (isset($this->params['data']['caseMenuFilters'])) {
            $caseMenuFilters = $this->params['data']['caseMenuFilters'];
        }
        if ($proj_uniq_id != 'all') {
            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1), 'fields' => array('Project.id')));

            if (count($projArr)) {
                $proj_id = $projArr['Project']['id'];
            }
            $this->set('proj_uniq_id', $proj_uniq_id);
            $this->set('proj_id', $proj_id);
            $this->set('CookiePriority', $_COOKIE['PRIORITY']);
            $this->set('caseMenuFilters', $caseMenuFilters);
        }
        if ($proj_uniq_id == 'all') {
            $this->set('proj_uniq_id', 'all');
            $this->set('proj_id', 'all');
            $this->set('CookiePriority', $_COOKIE['PRIORITY']);
            $this->set('caseMenuFilters', $caseMenuFilters);
        }
    }

    function ajax_types() {
        $this->layout = 'ajax';

        $proj_id = NULL;
        $pageload = 0;
        $proj_uniq_id = $this->params['data']['projUniq'];
        $pageload = $this->params['data']['pageload'];

        $caseMenuFilters = "";
        if (isset($this->params['data']['caseMenuFilters'])) {
            $caseMenuFilters = $this->params['data']['caseMenuFilters'];
        }

        if ($proj_uniq_id != 'all') {
            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1), 'fields' => array('Project.id')));

            if (count($projArr)) {
                $proj_id = $projArr['Project']['id'];
            }
        }

        ######### Filter by Assign To ##########
        if ($caseMenuFilters == "assigntome") {
            $qry .= " AND ((ec.assign_to=" . SES_ID . ") OR (ec.assign_to=0 AND ec.user_id=" . SES_ID . "))";
        }
        ######### Filter by Delegate To ##########
        elseif ($caseMenuFilters == "delegateto") {
            $qry .= " AND ec.assign_to!=0 AND ec.assign_to!=" . SES_ID . " AND Easycase.user_id=" . SES_ID;
        } elseif ($caseMenuFilters == "closecase") {
            $qry .= " AND ec.legend='3' AND ec.type_id !='10'";
        }
        ######### Filter by Bug case ##########
        elseif ($caseMenuFilters == "bugcase") {
            $qry .= " AND ec.type_id = 1";
        }
        ######### Filter by Latest ##########
        elseif ($caseMenuFilters == "latest") {
            $qry_rest = $qry;
            $before = date('Y-m-d H:i:s', strtotime(GMT_DATETIME . "-2 day"));
            $all_rest = " AND ec.dt_created > '" . $before . "' AND ec.dt_created <= '" . GMT_DATETIME . "'";
            $qry_rest .= " AND ec.dt_created > '" . $before . "' AND ec.dt_created <= '" . GMT_DATETIME . "'";
        }
        if ($caseMenuFilters == "latest" && $proj_uniq_id != 'all') {
            $CaseCount3 = $this->Easycase->query("SELECT COUNT(ec.id) as count FROM easycases as ec WHERE istype='1' AND ec.isactive='1' AND ec.project_id='" . $proj_id . "' AND ec.project_id!=0  " . $searchcase . " " . trim($qry_rest));
            $CaseCount = $CaseCount3['0']['0']['count'];
            if ($CaseCount == 0) {
                $rest = $this->Easycase->query("SELECT dt_created FROM easycases WHERE project_id ='" . $proj_id . "' ORDER BY dt_created DESC LIMIT 0 , 1");
                @$sdate = explode(" ", @$rest[0]['easycases']['dt_created']);
                $qry .= " AND ec.dt_created >= '" . @$sdate[0] . "' AND ec.dt_created <= '" . GMT_DATETIME . "'";
            } else {
                $qry = $qry . $all_rest;
            }
        } else if ($caseMenuFilters == "latest" && $proj_uniq_id == 'all') {
            $qry = $qry . $all_rest;
        }

        if ($proj_uniq_id != 'all') {

            $typeArr = array();
            $ProjectUser = ClassRegistry::init('ProjectUser');

            $typeArr = $this->ProjectUser->query("select DISTINCT t.name,t.id,t.short_name,(select count(ec.id) from easycases as ec where ec.istype='1' AND ec.type_id=t.id AND ec.isactive='1' AND ec.project_id='" . $proj_id . "' " . $qry . ") as count from types as t ORDER BY t.id");

            $this->set('proj_uniq_id', $proj_uniq_id);
            $this->set('proj_id', $proj_id);
            $this->set('typeArr', $typeArr);
            $this->set('CookieTypes', $_COOKIE['CS_TYPES']);
        }
        if ($proj_uniq_id == 'all') {

            $ProjectUser = ClassRegistry::init('ProjectUser');
            $typeArr = $ProjectUser->query("select DISTINCT t.name,t.id,t.short_name,(select count(ec.id) from easycases as ec,projects as p,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND p.id=ProjectUser.project_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' AND ProjectUser.user_id='" . SES_ID . "' AND p.isactive='1' AND ec.istype='1' AND ec.type_id=t.id AND ec.isactive='1' AND ec.project_id=p.id AND p.isactive='1' " . $qry . ") as count from types as t GROUP BY t.id  ORDER BY t.id");

            $this->set('proj_uniq_id', 'all');
            $this->set('proj_id', 'all');
            $this->set('typeArr', $typeArr);
            $this->set('CookieTypes', $_COOKIE['CS_TYPES']);
        }

        $this->set('caseMenuFilters', $caseMenuFilters);
    }

    function ajax_members() {
        $this->layout = 'ajax';

        $proj_id = NULL;
        $pageload = 0;
        $proj_uniq_id = $this->params['data']['projUniq'];
        $pageload = $this->params['data']['pageload'];

        $this->loadModel('ProjectUser');
        $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));

        $caseMenuFilters = "";
        if (isset($this->params['data']['caseMenuFilters'])) {
            $caseMenuFilters = $this->params['data']['caseMenuFilters'];
        }

        if ($proj_uniq_id != 'all') {
            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1), 'fields' => array('Project.id')));

            if (count($projArr)) {
                $proj_id = $projArr['Project']['id'];
            }
        }

        ######### Filter by Assign To ##########
        if ($caseMenuFilters == "assigntome") {
            $qry .= " AND ((Easycase.assign_to=" . SES_ID . ") OR (Easycase.assign_to=0 AND Easycase.user_id=" . SES_ID . "))";
        }
        ######### Filter by Delegate To ##########
        elseif ($caseMenuFilters == "delegateto") {
            $qry .= " AND Easycase.assign_to!=0 AND Easycase.assign_to!=" . SES_ID . " AND Easycase.user_id=" . SES_ID;
        } elseif ($caseMenuFilters == "closecase") {
            $qry .= " AND Easycase.legend='3' AND Easycase.type_id !='10'";
        }
        ######### Filter by Bug case ##########
        elseif ($caseMenuFilters == "bugcase") { /* By OSDEV 0201 */
            $qry .= " AND Easycase.type_id ='1'";
        }
        ######### Filter by Latest ##########
        elseif ($caseMenuFilters == "latest") {
            $qry_rest = $qry;
            $before = date('Y-m-d H:i:s', strtotime(GMT_DATETIME . "-2 day"));
            $all_rest = " AND Easycase.dt_created > '" . $before . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
            $qry_rest .= " AND Easycase.dt_created > '" . $before . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
        }
        if ($caseMenuFilters == "latest" && $proj_uniq_id != 'all') {
            $CaseCount3 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE istype='1' AND Easycase.isactive='1' AND Easycase.project_id='" . $proj_id . "' AND Easycase.project_id!=0  " . $searchcase . " " . trim($qry_rest));
            $CaseCount = $CaseCount3['0']['0']['count'];
            if ($CaseCount == 0) {
                $rest = $this->Easycase->query("SELECT dt_created FROM easycases WHERE project_id ='" . $proj_id . "' ORDER BY dt_created DESC LIMIT 0 , 1");
                @$sdate = explode(" ", @$rest[0]['easycases']['dt_created']);
                $qry .= " AND Easycase.dt_created >= '" . @$sdate[0] . "' AND Easycase.dt_created <= '" . GMT_DATETIME . "'";
            } else {
                $qry = $qry . $all_rest;
            }
        } else if ($caseMenuFilters == "latest" && $proj_uniq_id == 'all') {
            $qry = $qry . $all_rest;
        }

        if ($proj_uniq_id != 'all') {

            $memArr = $this->ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.dt_last_login, (select count(id) from easycases as Easycase where Easycase.project_id='" . $proj_id . "' and Easycase.user_id=User.id and Easycase.istype='1' and Easycase.isactive='1' " . $qry . ") as cases FROM users as User,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' AND ProjectUser.project_id='" . $proj_id . "' AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name");

            $this->set('proj_uniq_id', $proj_uniq_id);
            $this->set('proj_id', $proj_id);
            $this->set('memArr', $memArr);
            $this->set('CookieMem', $_COOKIE['MEMBERS']);

            $this->set('caseMenuFilters', $caseMenuFilters);
        }
        if ($proj_uniq_id == 'all') {
            $memArr = array();

            $cond = array('conditions' => array('ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP, 'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'), 'order' => array('ProjectUser.dt_visited DESC'));

            $allProjArr = $this->ProjectUser->find('all', $cond);
            $ids = array();

            foreach ($allProjArr as $csid) {
                array_push($ids, $csid['Project']['id']);
            }

            $memArr = $this->ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.dt_last_login, (select count(id) from easycases as Easycase where Easycase.project_id IN (" . implode(",", $ids) . ") and Easycase.user_id=User.id and Easycase.istype='1' and Easycase.isactive='1' " . $qry . ") as cases FROM users as User,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' AND ProjectUser.project_id IN (" . implode(",", $ids) . ") AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name");

            $this->set('proj_uniq_id', 'all');
            $this->set('proj_id', 'all');
            $this->set('memArr', $memArr);
            $this->set('CookieMem', $_COOKIE['MEMBERS']);

            $this->set('caseMenuFilters', $caseMenuFilters);
        }
    }

    function ajax_top() {
        $this->layout = 'ajax';

        $proj_id = NULL;
        $pageload = 0;
        $proj_uniq_id = $this->params['data']['projUniq'];
        $pageload = $this->params['data']['pageload'];

        $this->loadModel('ProjectUser');
        $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
        $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1), 'fields' => array('Project.id')));

        if (count($projArr)) {
            $proj_id = $projArr['Project']['id'];
        }

        $caseCond = array(
            'conditions' => array('Easycase.project_id' => $proj_id, 'Easycase.isactive' => 1, 'Easycase.istype' => 1, 'Easycase.type_id' => 10),
            'fields' => array('Easycase.title', 'Easycase.actual_dt_created', 'Easycase.uniq_id'),
            'order' => array('Easycase.actual_dt_created DESC')
        );
        $caseArr = $this->Easycase->find('all', $caseCond);

        $this->set('proj_uniq_id', $proj_uniq_id);
        $this->set('proj_id', $proj_id);
        $this->set('caseArr', $caseArr);

        if ($proj_id) {
            $CaseUserView = ClassRegistry::init('CaseUserView');
            $CaseUserView->query("DELETE FROM case_user_views WHERE istype='1' AND user_id='" . SES_ID . "' AND project_id=" . $proj_id);
        }
    }

    function ajax_project_size() {
        $this->layout = 'ajax';
        $proj_id = NULL;
        $pageload = 0;
        $proj_uniq_id = $this->params['data']['projUniq'];
        if (!$proj_uniq_id) {
            exit;
        }
        $pageload = $this->params['data']['pageload'];
        $user_subscription = $GLOBALS['user_subscription'];

        if ($proj_uniq_id != 'all') {
            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1), 'fields' => array('Project.id', 'Project.name', 'ProjectUser.id')));

            if (count($projArr)) {
                $proj_id = $projArr['Project']['id'];
                $proj_name = $projArr['Project']['name'];

                $ProjectUser['id'] = $projArr['ProjectUser']['id'];
                $ProjectUser['dt_visited'] = GMT_DATETIME;
                $this->ProjectUser->save($ProjectUser);
            }
            $usedspace = $this->Format->usedSpace($proj_id);
            $hspent = $this->Format->hoursspent($proj_id);
            $hspent = $hspent ? $hspent : 0;
            $arr['hourspent'] = "Hours Spent:&nbsp;" . $hspent;
            if ($user_subscription['storage'] != "Unlimited") {
                $arr['used_text'] = "Using " . $usedspace . " Mb of storage ";
                $usedspace = $this->Format->fullSpace($usedspace, $user_subscription['storage']);
            } else {
                $arr['used_text'] = "Using " . $usedspace . " Mb of storage";
            }
            $arr['used_text'] .= " | " . $arr['hourspent'];
            $arr['all'] = 0;
        } else {
            $arr['all'] = 1;
            $usedspace = $this->Format->usedSpace();
            $hspent = $this->Format->hoursspent('');
            $hspent = $hspent ? $hspent : 0;
            $arr['hourspent'] = "Hours Spent:&nbsp;" . $hspent;
            if ($user_subscription['storage'] != "Unlimited") {
                $arr['used_text'] = "Using " . $usedspace . " Mb of storage | " . $arr['hourspent'];
                $percentage = $this->Format->fullSpace($usedspace, $user_subscription['storage']);
                if ($percentage >= 100) {
                    $percentage = 100;
                }
                $width = $percentage;
                if ($percentage >= 90) {
                    $class = "cmpl_red";
                } else {
                    $class = "cmpl_green";
                }
                $arr ['used_text'] .= '<table cellpadding="0" cellspacing="0"><tr><td>' . $percentage . '% full</td>';
                if ($usedspace >= 1) {
                    $arr ['used_text'] .= '<td style="padding-left:5px;"><div class="imprv_bar" style="width:100px;margin:0px;"><div style="width:' . $width . '%;" class="' . $class . '" ></div></td>';
                }
                $arr ['used_text'] .= '</tr></table>';
            } else {
                $arr['used_text'] = "Using " . $usedspace . " Mb of storage | " . $arr['hourspent'];
            }

            //$this->set('proj_uniq_id',$proj_uniq_id);
        }

        // Last Project access activity
        $projArr = '';
        $ProjectUser = ClassRegistry::init('ProjectUser');
        $ProjectUser->recursive = -1;
        $latestactivity = $ProjectUser->find('first', array('conditions' => array('ProjectUser.user_id =' => SES_ID), 'fields' => array('dt_visited', 'project_id'), 'order' => array('ProjectUser.dt_visited DESC')));
        $projArr = $latestactivity['ProjectUser']['project_id'];
        $this->loadModel('Project');
        $this->Project->recursive = -1;
        $projArr = $this->Project->find('first', array('conditions' => array('Project.id' => $projArr, 'Project.isactive' => 1), 'fields' => array('Project.name', 'Project.id', 'Project.uniq_id')));
        //$this->set('dt_visited',$latestactivity['ProjectUser']['dt_visited']);
        //$this->set('projArr',$projArr);
        if ($projArr['Project']['name']) {
            $arr ['last_activity'] = "Last Activity |  <b>" . $this->Format->shortLength($projArr['Project']['name'], 20) . "</b> ";
            //$latestdt = $this->Casequery->getlatestactivity(SES_ID);
            if ($latestactivity['ProjectUser']['dt_visited'] && !stristr($latestactivity['ProjectUser']['dt_visited'], "0000")) {
                //$this->loadHelper('Tmzone');
                $view = new View($this);
                $tz = $view->loadHelper('Tmzone');
                $last_logindt = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $latestactivity['ProjectUser']['dt_visited'], "datetime");
                $locDResFun2 = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "date");
                $tz1 = $view->loadHelper('Datetime');
                $arr ['last_activity'] .= $tz1->dateFormatOutputdateTime_day($last_logindt, $locDResFun2);
                $arr['lastactivity_proj_id'] = $projArr['Project']['id'];
                $arr['lastactivity_proj_uid'] = $projArr['Project']['uniq_id'];
            }
        }
        echo json_encode($arr);
        exit;
    }

    function ajax_project_name() {
        $this->layout = 'ajax';
        $proj_id = NULL;
        $pageload = 0;
        $projName = "";
        $puid = 0;
        $proj_uniq_id = $this->params['data']['projUniq'];
        $pageload = $this->params['data']['pageload'];
        if ($proj_uniq_id != 'all') {
            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.name', 'ProjectUser.id')));

            if (count($projArr)) {
                $projName = $projArr['Project']['name'];
                $puid = $projArr['ProjectUser']['id'];
            }
            if ($puid) {
                $ProjectUser['id'] = $puid;
                $ProjectUser['dt_visited'] = GMT_DATETIME;
                $this->ProjectUser->save($ProjectUser);
            }
            $this->set('projName', $projName);
            $this->set('pageload', $pageload);
            $this->set('proj_uniq_id', $proj_uniq_id);
        } else {
            $this->set('projName', 'All');
            $this->set('pageload', $pageload);
            $this->set('proj_uniq_id', 'all');
        }
    }

    function ajax_project_logo() {
        $this->layout = 'ajax';
        $projName = "";
        $projLogo = "";
        $proj_uniq_id = $this->params['data']['projUniq'];
        $pageload = $this->params['data']['pageload'];
        if ($proj_uniq_id != 'all') {
            $this->loadModel('Project');
            $this->Project->recursive = -1;
            $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id), 'fields' => array('Project.name', 'Project.logo')));

            if (count($projArr)) {
                $projName = $projArr['Project']['name'];
                $projLogo = $projArr['Project']['logo'];
            }

            $this->set('projName', $projName);
            $this->set('projLogo', $projLogo);
        } else {
            $this->set('projName', '');
            $this->set('projLogo', '');
        }
    }

    function ajax_search() {
        $this->layout = 'ajax';
        $projShortName = NULL;
        $srchstr = $this->params['data']['srch'];
        $page = $this->params['data']['page'];

        $caseSearch = array();
        $prj_res = array();
        $usr_res = array();
        $file_res = array();

        if (trim(urldecode($srchstr))) {
            if ($page == "users") {
                $this->loadModel('User');
                $cond = "1";
                if (SES_TYPE == 3) {
                    $cond = "CompanyUser.user_type = '3'";
                }
                $usr_sql = "SELECT User.id,User.name,User.last_name,User.short_name,User.email,User.uniq_id,CompanyUser.is_active,UserInvitation.is_active
			FROM users AS User LEFT JOIN company_users AS CompanyUser ON (User.id=CompanyUser.user_id) LEFT JOIN user_invitations AS UserInvitation
			ON (User.id=UserInvitation.user_id) WHERE (User.name LIKE '%" . trim($srchstr) . "%'  OR User.last_name LIKE '%" . trim($srchstr) . "%' OR 
			User.email LIKE '%" . trim($srchstr) . "%'  OR User.short_name LIKE '%" . trim($srchstr) . "%') AND (" . $cond . " AND 
			CompanyUser.company_id='" . SES_COMP . "' AND ((CompanyUser.is_active = '0' OR CompanyUser.is_active = '1') OR (UserInvitation.company_id='" . SES_COMP . "' AND UserInvitation.is_active='1')))
			    GROUP BY User.id ORDER BY User.name LIMIT 0,8";

                $usr_res = $this->User->query($usr_sql);
            } elseif ($page == "projects") {
                $this->loadModel('Project');
                if (SES_TYPE == 3) {
                    $prj_sql = "SELECT Project.id,Project.uniq_id,Project.name,Project.short_name,Project.isactive FROM projects AS Project,
			project_users AS ProjectUser  WHERE Project.name!='' AND (Project.name LIKE '%" . trim($srchstr) . "%'  
			OR Project.short_name LIKE '%" . trim($srchstr) . "%') AND Project.company_id='" . SES_COMP . "' 
			AND Project.id=ProjectUser.project_id and ProjectUser.user_id='" . SES_ID . "' GROUP BY Project.id ORDER BY Project.name LIMIT 0,8";
                } else {
                    $prj_sql = "SELECT Project.id,Project.uniq_id,Project.name,Project.short_name,Project.isactive FROM projects AS Project WHERE Project.name!='' AND (Project.name LIKE '%" . trim($srchstr) . "%'
			OR Project.short_name LIKE '%" . trim($srchstr) . "%') AND Project.company_id='" . SES_COMP . "' 
			GROUP BY Project.id ORDER BY Project.name LIMIT 0,8";
                }

                $prj_res = $this->Project->query($prj_sql);
            } elseif ($page == "files") {
                $this->loadModel('CaseFile');
                $condtn = "";
                if (SES_TYPE == 3 || 1) {
                    $condtn = " AND ProjectUser.user_id='" . SES_ID . "' AND ProjectUser.project_id=Project.id";
                }
                $pjuniq = $this->params['data']['pjuniq'];
                if ($pjuniq != 'all') {
                    $condtn .= " AND Project.uniq_id ='" . $pjuniq . "'";
                }
                $file_sql = "SELECT Easycase.id,Easycase.uniq_id,Easycase.case_no,Easycase.user_id,Easycase.dt_created,Easycase.actual_dt_created,Easycase.istype,Easycase.project_id,Easycase.legend,CaseFile.*,Project.uniq_id FROM easycases as Easycase,case_files as CaseFile,projects as Project,project_users as ProjectUser WHERE Easycase.id=CaseFile.easycase_id AND Easycase.project_id=Project.id AND Easycase.isactive='1' AND Easycase.project_id=CaseFile.project_id AND Easycase.project_id!=0 AND CaseFile.isactive='1' AND CaseFile.company_id='" . SES_COMP . "' AND CaseFile.file LIKE '%" . trim($srchstr) . "%' " . $condtn . " ORDER BY Easycase.actual_dt_created DESC LIMIT 0,8";

                $file_res = $this->CaseFile->query($file_sql);
            } else {
                $pjuniq = $this->params['data']['pjuniq'];

                $searchString = "";
                if ((substr($srchstr, 0, 1)) == '#') {
                    $tmp = explode("#", $srchstr);
                    $caseno = trim($tmp['1']);
                    $searchString = " AND (Easycase.case_no = '" . $caseno . "')";
                } else {
                    $searchString = $this->Format->caseKeywordSearch($srchstr, 'half');
                }
                if (trim($pjuniq) == 'all' || trim($pjuniq) == '') {
                    $projId = 0;
                    $this->loadModel('ProjectUser');
                    $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
                    $projArr = $this->ProjectUser->find('all', array('conditions' => array('ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('DISTINCT ProjectUser.project_id')));
                    if (count($projArr)) {
                        $projId = array();
                        foreach ($projArr as $pr) {
                            array_push($projId, $pr['ProjectUser']['project_id']);
                        }
                    }

                    $caseSearch = $this->Easycase->query("SELECT Easycase.case_no,Easycase.title,Easycase.message,Easycase.project_id,Easycase.uniq_id FROM easycases as Easycase" . $mlstnQ1 . " WHERE Easycase.project_id IN (" . implode(",", $projId) . ") AND Easycase.istype='1'  " . $mlstnQ2 . " " . trim($searchString) . " LIMIT 0,8");
                } else {
                    $projId = 0;
                    $this->loadModel('ProjectUser');
                    $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
                    $projArr = $this->ProjectUser->find('all', array('conditions' => array('ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'Project.uniq_id' => $pjuniq), 'fields' => array('DISTINCT ProjectUser.project_id')));
                    $pjid = $projArr['0']['ProjectUser']['project_id'];
                    $caseSearch = $this->Easycase->query("SELECT Easycase.case_no,Easycase.title,Easycase.message,Easycase.project_id,Easycase.uniq_id FROM easycases as Easycase" . $mlstnQ1 . " WHERE Easycase.project_id='" . $pjid . "' AND Easycase.istype='1'  " . $mlstnQ2 . " " . trim($searchString) . " LIMIT 0,8");
                }
            }
        }
        $results['cases'] = $caseSearch;
        $results['projects'] = $prj_res;
        $results['users'] = $usr_res;
        $results['files'] = $file_res;

        $this->set('results', $results);
        $this->set('pjShrtName', $projShortName);
        $this->set('srchstr', $srchstr);
    }

    function ajax_filter_set() {
        $this->layout = 'ajax';
        $order = "";
        if (isset($_GET['widget'])) {
            foreach ($_GET['widget'] as $position => $item) {
                $order .= trim($item) . ",";
            }
            $order = substr($order, 0, -1);
        }
        if ($order) {
            $id = 0;
            $this->loadModel('CaseFilter');
            $casefilter = $this->CaseFilter->find('first', array('conditions' => array('CaseFilter.user_id' => SES_ID), 'fields' => array('CaseFilter.id')));
            if (count($casefilter)) {
                $id = $casefilter['CaseFilter']['id'];
            }
            if ($id) {
                $CaseFilter['id'] = $id;
                $CaseFilter['order'] = $order;
                $this->CaseFilter->save($CaseFilter);
            } else {
                $CaseFilter['user_id'] = SES_ID;
                $CaseFilter['order'] = $order;
                $this->CaseFilter->save($CaseFilter);
            }
        }
        exit;
    }

    function case_quick() {
        $this->_datestime();
        $this->layout = '';
        if (isset($this->data['csuniqid']) && $this->data['csuniqid']) {
            $taskdetails = $this->Easycase->findByUniqId($this->data['csuniqid']);
            $this->set('taskdetails', $taskdetails['Easycase']);
        }
        $uniqid = $this->params['data']['sel_myproj'];
        if ($uniqid == 'all') {
            $quickMem = array();
        } else {
            $quickMem = $this->Easycase->getMemebers($uniqid);
        }
        $quickTyp = $this->Format->getTypes();

        $this->set('quickTyp', $quickTyp);
        $this->set('quickMem', $quickMem);

        $this->loadModel('Type');
        $select = $this->Type->find('all', array('order' => 'Type.seq_order ASC'));
        $this->set('select', $select);

        $CaseTemplate = ClassRegistry::init('CaseTemplate');
        $CaseTemplate->recursive = -1;
        $getTmpl = $CaseTemplate->find('all', array('conditions' => array("OR" => array(
                    'AND' => array(
                        'CaseTemplate.is_active' => 1,
                        'CaseTemplate.company_id' => SES_COMP
                    ),
                    array(
                        'CaseTemplate.is_active' => 1,
                        'CaseTemplate.user_id' => 0,
                        'CaseTemplate.company_id' => 0))), 'order' => 'CaseTemplate.name ASC'));
        $this->set('getTmpl', $getTmpl);
        $this->loadModel('Project');
        $prj = $this->Project->findByUniqId($uniqid);
        $pid = $prj['Project']['id'];
        $defaultAssign = $prj['Project']['default_assign'];

        $this->loadModel('Milestone');
        $checkQuery = "SELECT Milestone.id,Milestone.title,Milestone.uniq_id FROM milestones AS Milestone,project_users AS ProjectUser WHERE Milestone.project_id=ProjectUser.project_id AND Milestone.isactive=1 AND ProjectUser.user_id=" . SES_ID . " AND Milestone.project_id='" . $pid . "' AND Milestone.company_id='" . SES_COMP . "'";
        $checkMstn = $this->Milestone->query($checkQuery);

        $this->set('milestone', $checkMstn);
        $this->set('prid', $pid);
        $this->set('defaultAssign', $defaultAssign);
    }

    function ajax_quickcase_mem() {
        $this->layout = 'ajax';
        $result = array();

        $uniqid = $this->params['data']['projUniq'];
        $quickMem = $this->Easycase->getMemebers($uniqid);
        //$this->set('quickMem',$quickMem);
        $result['quickMem'][$uniqid] = $quickMem;
        $this->loadModel('Project');
        if (isset($this->data['csuniqid']) && $this->data['csuniqid']) {
            $CaseUserEmail = ClassRegistry::init('CaseUserEmail');
            $CaseUserEmail->recursive = -1;
            $dassign = $CaseUserEmail->find('list', array('conditions' => array('CaseUserEmail.easycase_id' => $this->data['csuniqid'], 'CaseUserEmail.ismail' => 1), 'fields' => array('CaseUserEmail.user_id')));
            //$this->set('dassign',$dassign);
            $result['dassign'] = $dassign;
        } //else{
        $prj = $this->Project->findByUniqId($uniqid);
        $defaultAssign = $prj['Project']['default_assign'];
        //$this->set('defaultAssign',$defaultAssign);
        $result['defaultAssign'] = $defaultAssign;
        //}
        $this->set('result', json_encode($result));
    }

    function ajax_default_email() {
        $this->layout = 'ajax';
        $uniqid = $this->params['data']['projUniq'];
        $quickMem = $this->Easycase->getMemebers($uniqid, 'default');
        $this->set('quickMem', $quickMem);
    }

    function ajax_case_files() {
        $this->layout = 'ajax';
        $QckCaseFiles = array();
        $CaseFile = ClassRegistry::init('CaseFile');

        if (isset($this->params['data']['remid']) && $this->params['data']['remid']) {
            unlink(DIR_CASE_FILES . $this->params['data']['files']);
            $CaseFile->query("DELETE FROM case_files WHERE id=" . $this->params['data']['remid']);
        }

        if (isset($this->params['data']['easycaseid']) && $this->params['data']['easycaseid']) {
            $easycaseid = $this->params['data']['easycaseid'];
            $QckCaseFiles = $CaseFile->find('all', array('conditions' => array('CaseFile.easycase_id' => $easycaseid)));
        }
        if (isset($this->params['data']['remid']) && $this->params['data']['remid']) {
            if (count($QckCaseFiles) == 0) {
                $this->Easycase->query("UPDATE easycases SET format='2' WHERE id=" . $easycaseid);
            }
        }
        $this->set('QckCaseFiles', $QckCaseFiles);
    }

    function case_message() {
        $this->layout = 'ajax';
        $page = "";
        if (isset($this->params['data']['page'])) {
            $page = $this->params['data']['page'];
        }

        $ProjectUser = ClassRegistry::init('ProjectUser');
        $ProjectUser->unbindModel(array('belongsTo' => array('User')));
        $projUsrViewArr = $ProjectUser->find('all', array('conditions' => array('ProjectUser.user_id' => SES_ID), 'fields' => array('DISTINCT Project.id,Project.name,Project.uniq_id')));

        $this->set('projUsrViewArr', $projUsrViewArr);
        $this->set('page', $page);
    }

    function ajax_change_assign() {
        $this->layout = 'ajax';
        $assignto = $this->params['data']['assignto'];

        if ($assignto == "NA") {
            echo "<font color='#A5A5A5'>NA</font>";
        } elseif ($assignto == "Me") {
            echo "<font color='#A5A5A5'>Me</font>";
        } elseif (!$assignto) {
            echo "Me";
        } elseif ($assignto == SES_ID) {
            echo "Me";
        } else {
            $userData = $this->Format->getUserShortName($assignto);
            echo $userData['User']['short_name'];
        }
        exit;
    }

    function ajax_comments() {
        $this->layout = 'ajax';
        $replyid = $this->params['data']['replyid'];
        $this->set('replyid', $replyid);
    }

    function ajax_change_priority() {
        $this->layout = 'ajax';
        $priority = "";
        $caseId = "";
        $response = "";
        $priority = $this->params['data']['priority'];
        $caseId = $this->params['data']['caseId'];

        $getCase = $this->Easycase->find('first', array(
            'conditions' => array(
                'id' => $caseId, 'isactive' => 1, 'legend' => array(1, 2, 4)
            ),
            'fields' => array('id')
        ));
        if ($getCase) {
            $sql = "UPDATE `easycases` SET `priority`='" . $priority . "',dt_created = '" . GMT_DATETIME . "',case_count=case_count+1,updated_by='" . SES_ID . "' WHERE `id`='" . $caseId . "' AND isactive='1' AND (legend=1 OR legend=2 OR legend=4)";
            $upd = $this->Easycase->query($sql);
            //Jyoti start
            $sqldata = "SELECT * FROM `easycases` WHERE `id`='" . $caseId . "' ";
            $dataeasycase = $this->Easycase->query($sqldata); //print_r($dataeasycase);
            $caseuniqid = md5(uniqid());
            $this->Easycase->query("INSERT INTO easycases SET uniq_id='" . $caseuniqid . "', case_no = '" . $dataeasycase[0]['easycases']['case_no'] . "', 	case_count=0, project_id='" . $dataeasycase[0]['easycases']['project_id'] . "', user_id='" . SES_ID . "', updated_by=0, type_id='" . $dataeasycase[0]['easycases']['type_id'] . "', priority='" . $priority . "', title='', message='', hours='0', assign_to='" . $dataeasycase[0]['easycases']['assign_to'] . "', istype='2',format='2', status='" . $dataeasycase[0]['easycases']['status'] . "', legend='" . $dataeasycase[0]['easycases']['legend'] . "', isactive=1, dt_created='" . GMT_DATETIME . "',actual_dt_created='" . GMT_DATETIME . "',reply_type=4");
            //Jyoti End
            /* $sqldata = "SELECT case_no,project_id FROM `easycases` WHERE `id`='".$caseId."' ";
              $dataeasycase = $this->Easycase->query($sqldata);
              $sqlinsert = "INSERT INTO `orangescrum`.`case_activities` (`easycase_id`, `case_no`, `project_id`, `user_id`, `type`,`dt_created`) VALUES ('".$caseId."','".$dataeasycase['0']['easycases']['case_no']."','".$dataeasycase['0']['easycases']['project_id']."', '".SES_ID."','6','".GMT_DATETIME."')";
              $sqlinsertdata=$this->Easycase->query($sqlinsert); */

            $protyCls = '';
            $protyTtl = '';
            if ($casePriRep == 0) {
                $protyCls = 'high_priority';
                $protyTtl = 'High';
            } elseif ($casePriRep == 1) {
                $protyCls = 'medium_priority';
                $protyTtl = 'Medium';
            } elseif ($casePriRep == 2) {
                $protyCls = 'low_priority';
                $protyTtl = 'Low';
            }
            $protyCls = '';
            $protyTtl = '';
            if ($priority == "NULL" || $priority == "") {
                echo "";
            }
            if ($priority == 0) {
                $protyCls = 'high_priority';
                $protyTtl = 'High';
            } else if ($priority == 1) {
                $protyCls = 'medium_priority';
                $protyTtl = 'Medium';
            } else if ($priority >= 2) {
                $protyCls = 'low_priority';
                $protyTtl = 'Low';
            }
            $response = json_encode(array('protyCls' => $protyCls, 'protyTtl' => $protyTtl));
        }
        echo $response;
        exit;
        $this->set('response', $response);
    }

    function ajax_change_status() {
        $this->layout = 'ajax';
        $status = "";
        $caseId = "";
        $statusId = $this->params['data']['statusId'];

        $caseId = $this->params['data']['caseId'];
        $statusName = $this->params['data']['statusName'];
        $statusTitle = $this->params['data']['statusTitle'];

        $getCase = $this->Easycase->find('first', array(
            'conditions' => array(
                'id' => $caseId, 'isactive' => 1, 'legend' => array(1, 2, 4)
            ),
            'fields' => array('id')
        ));
        if ($getCase) {
            $sql = "UPDATE `easycases` SET `type_id`='" . $statusId . "',dt_created = '" . GMT_DATETIME . "',case_count=case_count+1,updated_by='" . SES_ID . "' WHERE `id`=" . $caseId . " AND isactive='1' AND (legend=1 OR legend=2 OR legend=4)";
            $upd = $this->Easycase->query($sql);
            //Jyoti start
            $sqldata = "SELECT * FROM `easycases` WHERE `id`='" . $caseId . "' ";
            $dataeasycase = $this->Easycase->query($sqldata); //print_r($dataeasycase);
            $caseuniqid = md5(uniqid());
            $this->Easycase->query("INSERT INTO easycases SET uniq_id='" . $caseuniqid . "', case_no = '" . $dataeasycase[0]['easycases']['case_no'] . "', 	case_count=0, project_id='" . $dataeasycase[0]['easycases']['project_id'] . "', user_id='" . SES_ID . "', updated_by=0, type_id='" . $statusId . "', priority='" . $dataeasycase[0]['easycases']['priority'] . "', title='', message='', hours='0', assign_to='" . $dataeasycase[0]['easycases']['assign_to'] . "', istype='2',format='2', status='" . $dataeasycase[0]['easycases']['status'] . "', legend='" . $dataeasycase[0]['easycases']['legend'] . "', isactive=1, dt_created='" . GMT_DATETIME . "',actual_dt_created='" . GMT_DATETIME . "',reply_type=1");
            //Jyoti End
            /* $sqldata = "SELECT case_no,project_id FROM `easycases` WHERE `id`='".$caseId."' ";
              $dataeasycase = $this->Easycase->query($sqldata);
              $sqlinsert = "INSERT INTO `orangescrum`.`case_activities` (`easycase_id`, `case_no`, `project_id`, `user_id`, `type`,`dt_created`) VALUES ('".$caseId."','".$dataeasycase['0']['easycases']['case_no']."','".$dataeasycase['0']['easycases']['project_id']."', '".SES_ID."','6','".GMT_DATETIME."')";
              $sqlinsertdata=$this->Easycase->query($sqlinsert); */

            //if($upd) {
            echo json_encode(array($statusName, $statusTitle));
            //echo $this->Format->todo_typ($statusName,$statusTitle);
            //}
        }
        exit;
    }

    function ajax_change_AssignTo() {
        $this->layout = 'ajax';
        $assignId = "";
        $caseId = "";
        $assignId = $this->params['data']['assignId'];
        $caseId = $this->params['data']['caseId'];
        $sql = "UPDATE `easycases` SET `assign_to`='" . $assignId . "',dt_created = '" . GMT_DATETIME . "', case_count=case_count+1,updated_by='" . SES_ID . "' WHERE `id`='" . $caseId . "' AND isactive='1'";
        $upd = $this->Easycase->query($sql);
        //Jyoti start
        $sqldata = "SELECT * FROM `easycases` WHERE `id`='" . $caseId . "' ";
        $dataeasycase = $this->Easycase->query($sqldata); //print_r($dataeasycase);
        $caseuniqid = md5(uniqid());
        $this->Easycase->query("INSERT INTO easycases SET uniq_id='" . $caseuniqid . "', case_no = '" . $dataeasycase[0]['easycases']['case_no'] . "', 	case_count=0, project_id='" . $dataeasycase[0]['easycases']['project_id'] . "', user_id='" . SES_ID . "', updated_by=0, type_id='" . $dataeasycase[0]['easycases']['type_id'] . "', priority='" . $dataeasycase[0]['easycases']['priority'] . "', title='', message='', hours='0', assign_to='" . $assignId . "', istype='2',format='2', status='" . $dataeasycase[0]['easycases']['status'] . "', legend='" . $dataeasycase[0]['easycases']['legend'] . "', isactive=1, dt_created='" . GMT_DATETIME . "',actual_dt_created='" . GMT_DATETIME . "',reply_type=2");
        //Jyoti End
        if (($assignId == 0) || ($assignId == SES_ID)) {
            $val['top'] = '<span style="color:#E0814E">me</span>';
            $val['details'] = '<span style="color:#E0814E">me</span>';
        } else {
            $userData = $this->Format->getUserShortName($assignId);
            //$val['top'] = strtoupper($userData['User']['short_name']);
            $val['top'] = $this->Format->shortLength(ucfirst($userData['User']['name']), 10);
            $val['details'] = "<span>" . $userData['User']['name'] . "</span>";
        }
        echo json_encode($val);
        exit;
    }

    function update_assignto() {
        $this->layout = 'ajax';
        $caseId = $this->params['data']['caseId'];
        $getCaseAsgnTo = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $caseId, 'Easycase.isactive' => '1'), 'fields' => array('DISTINCT Easycase.assign_to')));

        if ($getCaseAsgnTo['Easycase']['assign_to'] && $getCaseAsgnTo['Easycase']['assign_to'] != SES_ID) {
            $userData = $this->Format->getUserShortName($getCaseAsgnTo['Easycase']['assign_to']);
            echo "<font rel='tooltip' title='" . $userData['User']['name'] . "'>" . $userData['User']['short_name'] . "</font>";
        } else {
            echo "<font >Me</font>";
        }
        exit;
    }

    function ajax_change_DueDate() {
        $this->layout = 'ajax';
        $duedt = "";
        $caseId = "";
        $duedt = $this->params['data']['duedt'];
        $text = $this->params['data']['text'];
        //$arr = explode("/",$duedt);
        //$due_date = $arr['2']."-".$arr['0']."-".$arr['1'];

        if ($duedt != '00/00/0000' && $duedt != '') {
            $due_date = date('Y-m-d', strtotime($duedt));
        } else {
            $due_date = '0000-00-00';
        }

        $caseId = $this->params['data']['caseId'];

        $getCase = $this->Easycase->find('first', array(
            'conditions' => array(
                'id' => $caseId, 'isactive' => 1, 'legend' => array(1, 2, 4), 'type_id !=' => 10,
            ),
            'fields' => array('id')
        ));
        if ($getCase) {
            $sql = "UPDATE `easycases` SET `due_date`='" . $due_date . "',dt_created = '" . GMT_DATETIME . "',case_count=case_count+1,updated_by='" . SES_ID . "' WHERE `id`='" . $caseId . "' AND isactive='1'";
            $upd = $this->Easycase->query($sql);
            //Jyoti start
            $sqldata = "SELECT * FROM `easycases` WHERE `id`='" . $caseId . "' ";
            $dataeasycase = $this->Easycase->query($sqldata); //print_r($dataeasycase);
            $caseuniqid = md5(uniqid());
            $this->Easycase->query("INSERT INTO easycases SET uniq_id='" . $caseuniqid . "', case_no = '" . $dataeasycase[0]['easycases']['case_no'] . "', 	case_count=0, project_id='" . $dataeasycase[0]['easycases']['project_id'] . "', user_id='" . SES_ID . "', updated_by=0, type_id='" . $dataeasycase[0]['easycases']['type_id'] . "', priority='" . $dataeasycase[0]['easycases']['priority'] . "', title='', message='', hours='0', assign_to='" . $dataeasycase[0]['easycases']['assign_to'] . "',due_date='" . $due_date . "', istype='2',format='2', status='" . $dataeasycase[0]['easycases']['status'] . "', legend='" . $dataeasycase[0]['easycases']['legend'] . "', isactive=1, dt_created='" . GMT_DATETIME . "',actual_dt_created='" . GMT_DATETIME . "',reply_type=3");
            //Jyoti End
            //if($upd) {
            if ($duedt == "00/00/0000") {
                $val['top'] = '&nbsp;Set Due Dt';
                $val['details'] = '<i>NA</i>';
            } else {
                if ($text == "Today") {
                    $val['top'] = "&nbsp;Today";
                    $val['details'] = "<b>Today</b>";
                } elseif ($text == "Tomorrow") {
                    $val['top'] = "&nbsp;Tomorrow";
                    $val['details'] = "<b>Tomorrow</b>";
                } else {
                    $val['top'] = $this->Format->dateFormatOutputdateTime_day($duedt, GMT_DATETIME, 'week');
                    $val['details'] = "<b>" . $this->Format->dateFormatOutputdateTime_day($duedt, GMT_DATETIME, 'week') . "</b>";
                }
            }
            //}
            if ($val) {
                echo json_encode($val);
            }
        }
        exit;
    }

    function add_milestone() {
        //print"<pre/>";
        //print_r($this->params['data']['start_date']);exit;

        $title = $this->params['data']['title'];
        $start_date = $this->Format->chgdate($this->params['data']['start_date']);
        $end_date = $this->Format->chgdate($this->params['data']['end_date']);
        $uid = $this->params['data']['uid'];

        if (strtotime($start_date) > strtotime($end_date)) {
            $this->Session->write("ERROR", "Start date cannot exceed End date");
        } else {
            $this->loadModel('Project');
            $this->loadModel('Milestone');
            $prj = $this->Project->findByUniqId($uid);
            $this->request->data['Milestone']['project_id'] = $prj['Project']['id'];
            $this->request->data['Milestone']['title'] = $title;
            $this->request->data['Milestone']['start_date'] = $start_date;
            $this->request->data['Milestone']['end_date'] = $end_date;
            $this->request->data['Milestone']['user_id'] = SES_ID;
            $this->request->data['Milestone']['company_id'] = SES_COMP;
            $mlUniqId = md5(uniqid());
            $this->request->data['Milestone']['uniq_id'] = $mlUniqId;
            if ($this->Milestone->save($this->request->data)) {
                $last_insert_id = $this->Milestone->getLastInsertId();
                echo $title . "-" . $last_insert_id;
            } else {
                echo "0";
            }
        }
        exit;
    }

    function ajax_date() {
        $this->layout = 'ajax';
        $this->set('Date', $_COOKIE['DATE']);
    }

    function exporttoCSV_Milestone($projFil = null) {
        $this->Easycase->recursive = -1;
        $prj_unq_id = $this->data['Easycase']['project'];
        $qry = $this->Format->getSqlFields($this->data['Easycase'], $prj_unq_id);
        if ($prj_unq_id != 'all') {
            $this->loadModel('Project');
            $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $prj_unq_id, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
            if (count($projArr)) {
                $curProjId = $projArr['Project']['id'];
            }
            $projName = str_replace(" ", "_", $this->Format->getProjectName($curProjId));
            $task_list_milestone = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.*,EasycaseMilestone.id AS Emid, EasycaseMilestone.milestone_id AS Em_milestone_id,EasycaseMilestone.user_id AS Em_user_id,EasycaseMilestone.id_seq,Milestone.id as Mid,Milestone.title AS Mtitle ,Milestone.description AS Mdescription ,Milestone.end_date,Milestone.isactive AS Misactive,Milestone.project_id AS Mproject_id,Milestone.uniq_id AS Muinq_id FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone,milestones AS Milestone WHERE EasycaseMilestone.easycase_id=Easycase.id AND Milestone.id=EasycaseMilestone.milestone_id AND Easycase.istype='1' AND Easycase.isactive='1' AND Milestone.isactive='1' AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  AND EasycaseMilestone.easycase_id=Easycase.id AND EasycaseMilestone.project_id=" . $curProjId . " " . $qry . ") AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY Easycase.end_date ASC,Easycase.Mtitle ASC");
        }

        if ($prj_unq_id == 'all') {
            $this->loadModel('ProjectUser');
            $projName = 'AllProject';
            $task_list_milestone = $this->Easycase->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT  Easycase.*,EasycaseMilestone.id AS Emid, EasycaseMilestone.milestone_id AS Em_milestone_id,EasycaseMilestone.user_id AS Em_user_id,EasycaseMilestone.id_seq,Milestone.id as Mid,Milestone.title AS Mtitle ,Milestone.description AS Mdescription, Milestone.end_date,Milestone.isactive AS Misactive,Milestone.project_id AS Mproject_id,Milestone.uniq_id AS Muinq_id FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone,milestones AS Milestone WHERE EasycaseMilestone.easycase_id=Easycase.id AND Milestone.id=EasycaseMilestone.milestone_id AND Easycase.istype='1'  AND Easycase.isactive='1' " . $qry . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND Milestone.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') AND EasycaseMilestone.easycase_id=Easycase.id AND EasycaseMilestone.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1')) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY Easycase.end_date ASC,Easycase.Mtitle ASC");
        }
        $csv_output = "projectName,milestoneName,tasks#,title,status,type,created by,assigned to,created at,lastUpdated,milestoneEndDate\n";
        foreach ($task_list_milestone as $case_list) {
            if ($case_list['Easycase']['legend'] == 1) {
                $status = "New";
            } else if ($case_list['Easycase']['legend'] == 2) {
                $status = "Opened";
            } else if ($case_list['Easycase']['legend'] == 3) {
                $status = "Closed";
            } else if ($case_list['Easycase']['legend'] == 4) {
                $status = "Start";
            } else if ($case_list['Easycase']['legend'] == 5) {
                $status = "Resolved";
            }

            $createUserId = $case_list['Easycase']['Em_user_id'];
            $assignUserId = $case_list['Easycase']['assign_to'];
            $getCreateUserName = $this->Format->getRequireUserName($createUserId);
            $getAssignUserName = $this->Format->getRequireUserName($assignUserId);

            $typeId = $case_list['Easycase']['type_id'];
            $getTypeName = $this->Format->getRequireTypeName($typeId);
            $projectNameAll = str_replace(" ", "_", $this->Format->getProjectName($case_list['Easycase']['Mproject_id']));
            $case_no = $case_list['Easycase']['case_no'];
            $title = '"' . str_replace('"', '""', $case_list['Easycase']['title']) . '"';
            $status = $status;
            $type = $getTypeName;
            $createdBy = $getCreateUserName;
            $assignedTo = $getAssignUserName;
            $view = new View($this);
            $tz = $view->loadHelper('Tmzone');
            $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $case_list['Easycase']['actual_dt_created'], "datetime");
            $curCreated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
            $created = '"' . str_replace('"', '""', $this->Format->dateFormatOutputdateTime_day_EXPORT($updated, $curCreated)) . '"';

            $updated1 = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $case_list['Easycase']['dt_created'], "datetime");
            $curCreated1 = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
            $updated = '"' . str_replace('"', '""', $this->Format->dateFormatOutputdateTime_day_EXPORT($updated1, $curCreated1)) . '"';
            $milestone_enddate = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $case_list['Easycase']['end_date'], "datetime");
            $milestone_end_date = '"' . str_replace('"', '""', $this->Format->dateFormatOutputdateTime_day_EXPORT($milestone_enddate, $curCreated)) . '"';
            $milestone_name = $case_list['Easycase']['Mtitle'];
            $milestone_description = $case_list['Easycase']['Mdescription'];
            $csv_output .= $projectNameAll . "," . $milestone_name . "," . $case_no . "," . $title . "," . $status . "," . $type . "," . $createdBy . "," . $assignedTo . "," . $created . "," . $updated . "," . $milestone_end_date . "\n";
        }
        $filename = $projName . "_milestone_" . date("m-d-Y_H-i-s", time());

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $filename . ".csv");

        print $csv_output;
        exit;
    }

    function ajax_exportcsv() {
       
        $this->layout = 'ajax';
        if ($this->params['data']['projUniq']) {
            $proj_uniq_id = $this->params['data']['projUniq'];
        } else {
            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $getallproj = $this->ProjectUser->query("SELECT DISTINCT Project.id,Project.uniq_id,Project.name FROM project_users AS ProjectUser,projects AS Project WHERE Project.id= ProjectUser.project_id AND ProjectUser.user_id=" . SES_ID . " AND Project.isactive='1' AND Project.company_id='" . SES_COMP . "' ORDER BY ProjectUser.dt_visited DESC LIMIT 1");
            $proj_uniq_id = $getallproj[0]['Project']['uniq_id'];
        }
        $is_milestone = $this->params['data']['is_milestone'];
        $this->loadModel('Project');

        if ($proj_uniq_id !== 'all') {
            $project = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'Project.isactive' => 1), 'fields' => array('Project.id')));
            if (count($project)) {
                $proj_id = $project['Project']['id'];
            }
            $sql = "SELECT DISTINCT User.id, User.name, (select count(Easycase.id) from easycases as Easycase where Easycase.user_id=User.id and Easycase.istype='1' and User.isactive='1' and Easycase.isactive='1' AND Easycase.project_id='" . $proj_id . "') as cases FROM users as User,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' AND ProjectUser.project_id='" . $proj_id . "' AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name";
            $this->loadModel('Easycase');
            $memArr = $this->Easycase->query($sql);
            $this->set('memArr', $memArr);

            $sql = "SELECT DISTINCT User.id, User.name, (select count(Easycase.id) from easycases as Easycase where Easycase.assign_to = User.id and Easycase.istype='1' and User.isactive='1' and Easycase.isactive='1' AND Easycase.project_id='" . $proj_id . "') as cases FROM users as User,project_users as ProjectUser,company_users as CompanyUser,projects as Project WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' AND ProjectUser.project_id='" . $proj_id . "'  AND Project.id=ProjectUser.project_id AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name";
            $this->loadModel('Milestone');
            $milestone = $this->Milestone->find('list', array('fields' => array('id', 'title'), 'conditions' => array('company_id' => SES_COMP, 'project_id' => $proj_id)));
            $asnArr = $this->Easycase->query($sql);
            $this->set('milestone', $milestone);
            $this->set('asnArr', $asnArr);
            $this->set('uniq_id', $proj_uniq_id);

            if (intval($is_milestone)) {
                $this->loadModel('Milestone');
                $milestones = $this->Milestone->getMilestone($proj_id);
                $this->set('milestones', $milestones);
            }
        }

        $sql = "SELECT DISTINCT Project.uniq_id, Project.name FROM project_users AS ProjectUser LEFT JOIN projects AS Project ON (Project.id= ProjectUser.project_id) WHERE ProjectUser.user_id='" . SES_ID . "' AND ProjectUser.company_id='" . SES_COMP . "' AND Project.isactive='1' ORDER BY Project.name ASC";
        $projArr = $this->Project->query($sql);

        $this->loadModel('Type');
        $type_sql = "SELECT * FROM types WHERE CASE WHEN (SELECT COUNT(*) AS total FROM type_companies WHERE company_id = " . SES_COMP . " HAVING total >=1) THEN id IN (SELECT type_id FROM type_companies WHERE company_id = " . SES_COMP . ") ELSE company_id = 0 End ORDER BY company_id DESC, seq_order ASC";
        $typeArr = $this->Type->query($type_sql);
        $this->set(compact('projArr', 'is_milestone', 'typeArr'));
    }

    function ajax_change_milestone() {
        $this->layout = 'ajax';
        $proj_uniq_id = $this->params['data']['id'];
        $this->loadModel('Project');

        $project = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'Project.isactive' => 1), 'fields' => array('Project.id')));
        if (count($project)) {
            $proj_id = $project['Project']['id'];
        }

        $this->loadModel('Milestone');
        $milestones = $this->Milestone->getMilestone($proj_id);
        $this->set('milestones', $milestones);
    }

    function ajax_change_milestone_options() {
        $proj_uniq_id = $this->params['data']['id'];
        $this->loadModel('Project');
        $project = $this->Project->findByUniqId($proj_uniq_id);
        $this->loadModel('Milestone');
        $milestone = $this->Milestone->find('list', array('fields' => array('id', 'title'), 'conditions' => array('company_id' => SES_COMP, 'project_id' => $project['Project']['id'])));
        $options = '<option>All</option>';
        foreach ($milestone as $key => $value) {
            $options .= "<option value=$key>$value</option>";
        }
        echo $options;
        exit;
    }

    function ajax_member_assignto() {
        $this->layout = 'ajax';
        $proj_uniq_id = $this->params['data']['id'];
        $this->loadModel('Project');

        $project = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $proj_uniq_id, 'Project.isactive' => 1), 'fields' => array('Project.id')));
        if (count($project)) {
            $proj_id = $project['Project']['id'];
        }

        $sql = "SELECT DISTINCT User.id, User.name, (select count(Easycase.id) from easycases as Easycase where Easycase.user_id=User.id and Easycase.istype='1' and User.isactive='1' and Easycase.isactive='1' AND Easycase.project_id='" . $proj_id . "') as cases FROM users as User,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' AND ProjectUser.project_id='" . $proj_id . "' AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name";
        $this->loadModel('Easycase');
        $memArr = $this->Easycase->query($sql);
        $this->set('memArr', $memArr);

        $sql = "SELECT DISTINCT User.id, User.name, (select count(Easycase.id) from easycases as Easycase where Easycase.assign_to = User.id and Easycase.istype='1' and User.isactive='1' and Easycase.isactive='1' AND Easycase.project_id='" . $proj_id . "') as cases FROM users as User,project_users as ProjectUser,company_users as CompanyUser,projects as Project WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' AND ProjectUser.project_id='" . $proj_id . "'  AND Project.id=ProjectUser.project_id AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name";
        $asnArr = $this->Easycase->query($sql);
        $this->set('asnArr', $asnArr);
    }

    function exportTaskcsv() {
//        print_r($this->request->data); exit;
        $status_list = array();
        $from = '';
        $to = '';
        if (!empty($this->request->data['Easycase']['from'])) {
            $from = "'" . date('Y-m-d', strtotime($this->request->data['Easycase']['from'])) . ' 00:00:00' . "'";
        }
        if (!empty($this->request->data['Easycase']['to'])) {
            $to = "'" . date('Y-m-d', strtotime($this->request->data['Easycase']['to'])) . ' 23:59:59' . "'";
        }
        $date_conditions = '';
        if (!empty($from) && !empty($to)) {
            $date_conditions = " AND Easycase.actual_dt_created >=$from AND Easycase.actual_dt_created <=$to";
        } else if (!empty($from)) {
            $date_conditions = " AND Easycase.actual_dt_created >=$from";
        } else if (!empty($to)) {
            $date_conditions = " AND  Easycase.actual_dt_created <=$to";
        }
//        echo $date_conditions; exit;
        $milestone_id = $this->request->data['Easycase']['milestone'];
        $this->Easycase->recursive = -1;
        $prj_unq_id = $this->data['Easycase']['project'];
        $qry = $this->Format->getSqlFields($this->data['Easycase'], $prj_unq_id);
        if ($prj_unq_id == 'all') {
            $this->loadModel('ProjectUser');
//            echo "SELECT Easycase.*, Project.name,Milestone.* FROM easycases as Easycase, projects as Project LEFT JOIN easycase_milestones as EasycaseMilestone ON Easycase.id=EasycaseMilestone.easycase_id LEFT JOIN milestones as Milestone on Milestone.id=EasycaseMilestone.milestone_id WHERE Easycase.project_id=Project.id $date_conditions AND Easycase.istype =1 AND Easycase.title !='' AND Easycase.isactive='1' AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='" . SES_COMP . "' " . $qry . ") ORDER BY Project.name ASC"; exit;
            $case_lists = $this->Easycase->query("SELECT Easycase.*, Project.name,Milestone.* FROM easycases as Easycase LEFT JOIN easycase_milestones as EasycaseMilestone ON Easycase.id=EasycaseMilestone.easycase_id LEFT JOIN milestones as Milestone on Milestone.id=EasycaseMilestone.milestone_id , projects as Project  WHERE Easycase.project_id=Project.id $date_conditions AND Easycase.istype =1 AND Easycase.title !='' AND Easycase.isactive='1' AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='" . SES_COMP . "' " . $qry . ") ORDER BY Project.name ASC");
            $projName = 'AllProject';
            $csv_output = "Project Name,";
        } else if ($prj_unq_id != 'all') {
            $this->loadModel('Project');
            $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $prj_unq_id, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id', 'Project.workflow_id')));
            if (count($projArr)) {
                $curProjId = $projArr['Project']['id'];
//		$sql = "SELECT Easycase.*, Project.name FROM easycases as Easycase ,projects as Project, LEFT JOIN easycase_milestones as EasycaseMilestone ON Easycase.id=EasycaseMilestone.easycase_id LEFT JOIN milestones as Milestone on Milestone.id=EasycaseMilestone.milestone_id as EastcaseMilestone on  WHERE Easycase.project_id=Project.id AND Easycase.istype = 1 AND Easycase.title != '' AND Easycase.isactive='1' AND Easycase.project_id!=0 AND Easycase.project_id = '" . $curProjId . "' ".$qry." ORDER BY Easycase.dt_created ASC";
                $sql = ("SELECT Easycase.*, Project.name,Milestone.* FROM easycases as Easycase LEFT JOIN projects as Project ON Easycase.project_id=Project.id LEFT JOIN easycase_milestones as EasycaseMilestone ON Easycase.id=EasycaseMilestone.easycase_id LEFT JOIN milestones as Milestone on Milestone.id=EasycaseMilestone.milestone_id   WHERE   Easycase.istype = 1 $date_conditions AND Easycase.title != '' AND Easycase.isactive='1' AND Easycase.project_id!=0 AND Easycase.project_id = '" . $curProjId . "' " . $qry . " ORDER BY Easycase.dt_created ASC ");
                $case_lists = $this->Easycase->query($sql);
            }
            $projName = str_replace(" ", "_", ucwords($this->Format->getProjectName($curProjId)));
            $csv_output = "";
        }
        $csv_output .= __("Tasks#,Title,Description,Status,Type,Milestone,Assigned To,Due Date,Created By,Date Created,Last Updated\n", true);

        foreach ($case_lists as $case_list) {
            if ($case_list['Easycase']['legend'] == 1) {
                $status = "New";
            } else if ($case_list['Easycase']['legend'] == 2) {
                $status = "Opened";
            } else if ($case_list['Easycase']['legend'] == 3) {
                $status = "Closed";
            } else if ($case_list['Easycase']['legend'] == 4) {
                $status = "Start";
            } else if ($case_list['Easycase']['legend'] == 5) {
                $status = "Resolved";
            } 

            $createUserId = $case_list['Easycase']['user_id'];
            $assignUserId = $case_list['Easycase']['assign_to'];
            $Milestone = $case_list['Milestone']['title'];
            $getCreateUserName = $this->Format->getRequireUserName($createUserId, 1);
            $getAssignUserName = $this->Format->getRequireUserName($assignUserId, 1);

            $typeId = $case_list['Easycase']['type_id'];
            $getTypeName = $this->Format->getRequireTypeName($typeId);

            $projectNameAll = $case_list['Project']['name'];

            $case_no = $case_list['Easycase']['case_no'];
            $title = '"' . str_replace('"', '""', $case_list['Easycase']['title']) . '"';
            $description = '"' . str_replace('"', '""', strip_tags($case_list['Easycase']['message'])) . '"';
            $status = $status;
            $type = $getTypeName;
            $createdBy = $getCreateUserName;
            $assignedTo = $getAssignUserName;

            $view = new View($this);
            $tz = $view->loadHelper('Tmzone');
            $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $case_list['Easycase']['actual_dt_created'], "datetime");
            //$curCreated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
            $created = '"' . str_replace('"', '""', $this->Format->mdyFormat($updated, "time")) . '"';

            $due_date = "";
            if (trim($case_list['Easycase']['due_date']))
                $due_date = '"' . date("m/d/Y", strtotime($case_list['Easycase']['due_date'])) . '"';
            $updated1 = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $case_list['Easycase']['dt_created'], "datetime");
            $updated = '"' . str_replace('"', '""', $this->Format->mdyFormat($updated1, "time")) . '"';

            if ($prj_unq_id == 'all') {
                $csv_output .= $projectNameAll . ",";
            }
            
            $csv_output .= htmlspecialchars_decode($case_no) . "," . htmlspecialchars_decode($title) . "," . htmlspecialchars_decode($description) . "," . htmlspecialchars_decode($status) .  "," . htmlspecialchars_decode($type) . "," . htmlspecialchars_decode($Milestone) . "," . htmlspecialchars_decode($assignedTo) . "," . htmlspecialchars_decode($due_date) . "," . htmlspecialchars_decode($createdBy) . "," . htmlspecialchars_decode($created) . "," . htmlspecialchars_decode($updated) . "\n";
        }
        $filename = htmlspecialchars_decode($projName) . "_" . date("dMY", time());
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $filename . ".csv");
        print $csv_output;
        exit;
    }

    function exporttoCSV($projFil = null) {
        //ini_set('max_execution_time', 6); //increase max_execution_time to 10 min if data set is very large
        $this->Easycase->recursive = -1;
        $prj_unq_id = $projFil;
        if ($prj_unq_id != 'all') {
            $this->loadModel('Project');
            $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $prj_unq_id, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
            if (count($projArr)) {
                $curProjId = $projArr['Project']['id'];
                $case_lists = $this->Easycase->query("SELECT Easycase.*, Project.name FROM easycases as Easycase, projects as Project WHERE Easycase.project_id=Project.id AND Easycase.istype = 1 AND Easycase.title != '' AND Easycase.isactive='1' AND Easycase.project_id!=0 AND Easycase.project_id = '" . $curProjId . "' ORDER BY Easycase.dt_created ASC");
            }

            //$projectIdNew = $case_lists[0]['Easycase']['project_id'];
            //$projName     = str_replace(" ","_",$this->Format->getProjectName($projectIdNew));
            $projName = str_replace(" ", "_", $this->Format->getProjectName($curProjId));
        }
        if ($prj_unq_id == 'all') {
            $this->loadModel('ProjectUser');
            //$case_lists = $this->Easycase->query("SELECT * FROM easycases as Easycase WHERE Easycase.istype =1 AND Easycase.title !='' AND Easycase.isactive='1' AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=".SES_ID." AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='".SES_COMP."') ORDER BY Easycase.dt_created ASC");
            //echo "SELECT Easycase.*, Project.name FROM easycases as Easycase, projects as Project WHERE Easycase.project_id=Project.id AND Easycase.istype =1 AND Easycase.title !='' AND Easycase.isactive='1' AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=".SES_ID." AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='".SES_COMP."') ORDER BY Project.name ASC";exit;
            $case_lists = $this->Easycase->query("SELECT Easycase.*, Project.name FROM easycases as Easycase, projects as Project WHERE Easycase.project_id=Project.id AND Easycase.istype =1 AND Easycase.title !='' AND Easycase.isactive='1' AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND Project.company_id='" . SES_COMP . "') ORDER BY Project.name ASC");
            $projName = 'AllProject';
        }
        $csv_output = "projectName,tasks#,title,status,type,created by,assigned to,created at,lastUpdated\n";

        /* echo "<pre>";	print_r($case_lists);exit; */

        foreach ($case_lists as $case_list) {
            if ($case_list['Easycase']['legend'] == 1) {
                $status = "New";
            } else if ($case_list['Easycase']['legend'] == 2) {
                $status = "Opened";
            } else if ($case_list['Easycase']['legend'] == 3) {
                $status = "Closed";
            } else if ($case_list['Easycase']['legend'] == 4) {
                $status = "Start";
            } else if ($case_list['Easycase']['legend'] == 5) {
                $status = "Resolved";
            }

            $createUserId = $case_list['Easycase']['user_id'];
            $assignUserId = $case_list['Easycase']['assign_to'];
            $getCreateUserName = $this->Format->getRequireUserName($createUserId);
            $getAssignUserName = $this->Format->getRequireUserName($assignUserId);

            $typeId = $case_list['Easycase']['type_id'];
            $getTypeName = $this->Format->getRequireTypeName($typeId);

            $projectNameAll = $case_list['Project']['name'];

            $case_no = $case_list['Easycase']['case_no'];
            $title = '"' . str_replace('"', '""', $case_list['Easycase']['title']) . '"';
            $status = $status;
            $type = $getTypeName;
            $createdBy = $getCreateUserName;
            $assignedTo = $getAssignUserName;
            //$created    = date('m-d-Y h:i:s',strtotime($case_list['Easycase']['dt_created']));
            $view = new View($this);
            $tz = $view->loadHelper('Tmzone');
            $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $case_list['Easycase']['actual_dt_created'], "datetime");
            $curCreated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
            //$updated = $this->Format->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$case_list['Easycase']['dt_created'],"datetime");
            //$curCreated = $this->Format->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
            $created = '"' . str_replace('"', '""', $this->Format->dateFormatOutputdateTime_day_EXPORT($updated, $curCreated)) . '"';

            $updated1 = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $case_list['Easycase']['dt_created'], "datetime");
            $curCreated1 = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
            //$updated = $this->Format->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$case_list['Easycase']['dt_created'],"datetime");
            //$curCreated = $this->Format->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
            $updated = '"' . str_replace('"', '""', $this->Format->dateFormatOutputdateTime_day_EXPORT($updated1, $curCreated1)) . '"';

            $csv_output .= $projectNameAll . "," . $case_no . "," . $title . "," . $status . "," . $type . "," . $createdBy . "," . $assignedTo . "," . $created . "," . $updated . "\n";
        }
        $filename = $projName . "_tasks_all_" . date("m-d-Y_H-i-s", time());

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $filename . ".csv");

        print $csv_output;

        exit;
    }

    function exportcase() {
        $this->layout = 'ajax';
        if (isset($this->request->data['check_csv'])) {
            if (isset($this->request->data['check_typ']) && (trim($this->request->data['check_typ']) == 'printcsv')) {
                $str_milestone = $this->request->data['check_csv'];
                $milestone = explode(",", $this->request->data['check_csv']);
                $name = '';
                foreach ($milestone as $key => $val) {
                    $msql = "SELECT title FROM milestones WHERE id='" . $val . "'";
                    $this->Easycase->recursive = -1;
                    $mresult = $this->Easycase->query($msql);
                    $name .= str_replace(" ", "_", str_replace('"', "", str_replace("'", "", $mresult['0']['milestones']['title'])));
                    $name .= "_";
                }
                if (strlen($name) > 25) {
                    $name = substr($name, 0, 24) . "_" . $this->Format->dateFormatReverse(GMT_DATE) . "_milestone~.csv";
                } else {
                    $name .= "_" . $this->Format->dateFormatReverse(GMT_DATE) . "milestone.csv";
                }
                $content = 'Project Name,Milestone Name,Task#,Title,Status,Type,Assigned To';
                $content .= "\n";
                $fp = fopen(CSV_PATH . $name, 'w+');
                foreach ($milestone as $key => $val) {
                    $sql = "SELECT x.projectname,x.milestonename,x.case_no,x.title,x.status,x.type,u.short_name FROM (SELECT b.projectname,b.milestonename,b.case_no,b.title,b.status,t.short_name as type,b.isActive,b.assign_to FROM ( SELECT a.* , p.name as projectname FROM (SELECT c.* , e.case_no , e.type_id, e.priority,e.title , e.message, IF(e.legend=1,'New',IF(e.legend=2,'Wip',IF(e.legend=3,'Closed',IF(e.legend=4,'Started','Resolved')))) AS status ,IF(e.istype=1,'Post','Comment') As isType,e.dt_created,e.isActive,IF(e.assign_to,e.assign_to,e.user_id) AS assign_to  FROM ( SELECT m.title as milestonename , em.easycase_id , em.project_id,em.user_id ,em.milestone_id FROM `milestones` as m , easycase_milestones  as em WHERE m.id = em.milestone_id and em.milestone_id =" . $val . " ) AS c , easycases e WHERE c.easycase_id = e.id AND e.isActive='1') AS a , projects p WHERE a.project_id = p.id) AS b , types as t WHERE b.type_id = t.id ) as x ,users u where x.assign_to = u.id";
                    $this->Easycase->recursive = -1;
                    $result = $this->Easycase->query($sql); //print_r($result);exit;
                    foreach ($result AS $k => $v) {
                        if ($k) {
                            $v['x']['projectname'] = '';
                            $v['x']['milestonename'] = '';
                        }
                        $v['x'] = array_replace($v['x'], array('title' => str_replace('"', "", str_replace("'", "", $v['x']['title']))));
                        $content .= '"' . implode('","', $v['x']) . '"';
                        $content .= "," . $v['u']['short_name'];
                        $content .= "\n";
                    }
                    $content .= "\n";
                }
                header('Content-type: text/csv');
                header('Content-Disposition: attachment; filename=' . $name);
                @ob_clean();
                flush();
                readfile(CSV_PATH . $name);
                unlink(CSV_PATH . $name);
                exit;
            }
        }
        exit;
    }

    function ajaxemail($oauth_arg = NULL) {
        $oauth_return = 0;
        $this->loadModel("ProjectUser");
        if (isset($this->data['type'])) {
            $json_data = $this->data['json_data'];
            $data = json_decode($json_data, true);

            $data['emailbody'] = $_SESSION['email']['email_body'];
            $data['msg'] = $_SESSION['email']['msg'];
            unset($_SESSION['email']);

            if (strstr($data['caseid_list'], ',') || trim($data['caseid_list'], ',')) {
                $commonArrId = explode(',', $data['caseid_list']);
                $CaseUserEmail = ClassRegistry::init('CaseUserEmail');
                foreach ($commonArrId as $commonCaseId) {
                    if (trim($commonCaseId)) {
                        $this->loadModel("Easycase");
                        $caseDataArr = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $commonCaseId), 'fields' => array('Easycase.id', 'Easycase.case_no', 'Easycase.project_id', 'Easycase.type_id', 'Easycase.priority', 'Easycase.title', 'Easycase.uniq_id', 'Easycase.assign_to')));
                        $caseStsId = $caseDataArr['Easycase']['id'];
                        $data['caseNo'] = $caseDataArr['Easycase']['case_no'];
                        $data['projId'] = $caseDataArr['Easycase']['project_id'];
                        $data['caseTypeId'] = $caseDataArr['Easycase']['type_id'];
                        $data['casePriority'] = $caseDataArr['Easycase']['priority'];
                        $data['emailTitle'] = $caseDataArr['Easycase']['title'];
                        $data['caseUniqId'] = $closeStsUniqId = $caseDataArr['Easycase']['uniq_id'];
                        $data['caUid'] = $caseDataArr['Easycase']['assign_to'];

                        //$getEmailUser = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email FROM users as User,user_notifications as UserNotification,case_user_emails as CaseUserEmail,project_users as ProjectUser,company_users as CompanyUser WHERE User.id=UserNotification.user_id AND CaseUserEmail.user_id=UserNotification.user_id AND CaseUserEmail.easycase_id='".$caseStsId."' AND CaseUserEmail.ismail='1' AND UserNotification.case_status='1' AND User.isactive='1' AND CompanyUser.user_id=UserNotification.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='".SES_COMP."' AND ProjectUser.user_id=User.id AND ProjectUser.project_id='".$data['projId']."' AND ProjectUser.default_email='1'");
                        //$getEmailUser = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email FROM users as User,user_notifications as UserNotification,case_user_emails as CaseUserEmail,project_users as ProjectUser,company_users as CompanyUser WHERE User.id=UserNotification.user_id AND CaseUserEmail.user_id=UserNotification.user_id AND CaseUserEmail.ismail='1' AND UserNotification.case_status='1' AND User.isactive='1' AND CompanyUser.user_id=UserNotification.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='".SES_COMP."' AND ProjectUser.user_id=User.id AND ProjectUser.project_id='".$data['projId']."' AND ProjectUser.default_email='1'");

                        /* Commenting by Orangescrum
                          if (isset($this->data['emailUser'])) {
                          $getEmailUser = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email FROM users as User WHERE User.id IN (" . implode(',', $this->data['emailUser']) . ") AND User.isactive='1' ");
                          } else {
                          //$getEmailUser = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email FROM users as User,user_notifications as UserNotification,project_users as ProjectUser,company_users as CompanyUser WHERE User.id=UserNotification.user_id AND UserNotification.case_status='1' AND User.isactive='1' AND CompanyUser.user_id=UserNotification.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='" . SES_COMP . "' AND ProjectUser.user_id=User.id AND ProjectUser.project_id='" . $data['projId'] . "' AND ProjectUser.default_email='1'");
                          $getEmailUser = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email FROM users as User,user_notifications as UserNotification,case_user_emails as CaseUserEmail,project_users as ProjectUser,company_users as CompanyUser WHERE User.id=UserNotification.user_id AND CaseUserEmail.user_id=UserNotification.user_id AND CaseUserEmail.easycase_id='".$caseStsId."' AND CaseUserEmail.ismail='1' AND UserNotification.case_status='1' AND User.isactive='1' AND CompanyUser.user_id=UserNotification.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='".SES_COMP."' AND ProjectUser.user_id=User.id AND ProjectUser.project_id='".$data['projId']."'");
                          } */

                        //Added by Orangescrum
                        $emailUsers = $CaseUserEmail->getEmailUsers($commonCaseId);
                        $getEmailUser = $this->ProjectUser->getAllNotifyUser($data['projId'], $emailUsers);
                        $this->Postcase->mailToUser($data, $getEmailUser);
                        //End
                    }
                }
            }
        } else {
            if (isset($oauth_arg) && !empty($oauth_arg)) {
                $data = $oauth_arg;
                $oauth_return = 1;
            } else {
                $data = $this->data;
            }

            /* Commenting by Orangescrum
              if (isset($data['emailUser']) && $data['emailUser']) {
              $getEmailUser = $this->Easycase->query("SELECT DISTINCT User.id, User.name, User.email FROM users as User WHERE User.id IN (" . implode(',', $data['emailUser']) . ") AND User.isactive='1' ");
              } elseif (isset($data['emailUser'])) {
              $getEmailUser = "";
              } elseif ($data['caseIstype'] == 1) {
              $getEmailUser = $this->Format->getAllNotifyUser($data['projId'], 'new');
              } else {
              $getEmailUser = $this->Format->getAllNotifyUser($data['projId'], 'reply');
              } */

            //Added by Orangescrum
            if ($data['caseIstype'] == 1) {
                $getEmailUser = $this->ProjectUser->getAllNotifyUser($data['projId'], $data['emailUser'], 'new');
            } else {
                $getEmailUser = $this->ProjectUser->getAllNotifyUser($data['projId'], $data['emailUser'], 'reply');
            }//End

            if ($getEmailUser) {
                $this->Postcase->mailToUser($data, $getEmailUser);
            }

            if (intval($oauth_return)) {
                $ret = array('success' => "success");
                return json_encode($ret);
            }
        }
        echo 1;
        exit;
    }

    function ajax_common_breadcrumb() {

        $arr = array();
        $this->layout = 'ajax';
        $case_status = "all";
        $case_types = "all";
        $pri_fil = "all";
        $case_member = "all";
        $case_assignto = "all";
        $val = 0;

        //For Case Status
        if (isset($this->params['data']['caseStatus']) && $this->params['data']['caseStatus']) {
            $case_status = $this->params['data']['caseStatus'];
        } elseif ($_COOKIE['STATUS']) {
            $case_status = $_COOKIE['STATUS'];
        }
        if ($case_status && $case_status != "all") {
            $case_status = strrev($case_status);
            if (strstr($case_status, "-")) {
                $expst = explode("-", $case_status);
                foreach ($expst as $st) {
                    //$status.= $this->Format->displayStatus($st).", ";
                    $status .= "<div class='fl filter_opn' rel='tooltip' title='Task Status' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"status\");'>" . $this->Format->displayStatus($st) . "<a href='javascript:void(0);' onclick='common_reset_filter(\"taskstatus\",\"" . strrev($st) . "\",this);' class='fr'>X</a></div>"; //$this->Format->displayStatus($st).", ";
                }
            } else {
                $status = "<div class='fl filter_opn' rel='tooltip' title='Task Status' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"status\");'>" . $this->Format->displayStatus($case_status) . "<a href='javascript:void(0);' onclick='common_reset_filter(\"taskstatus\",\"" . $case_status . "\",this);' class='fr'>X</a></div>"; //$this->Format->displayStatus($case_status).", ";
            }
            $arr['case_status'] = trim($status, ', ');
            $val = 1;
        } else {
            $arr['case_status'] = 'All';
        }

        //For case types
        if (isset($this->params['data']['caseTypes']) && $this->params['data']['caseTypes']) {
            $case_types = $this->params['data']['caseTypes'];
        } elseif ($_COOKIE['CS_TYPES']) {
            $case_types = $_COOKIE['CS_TYPES'];
        }
        $types = '';
        if ($case_types && $case_types != "all") {
            //$case_types = strrev($case_types);

            $view = new View($this);
            $cq = $view->loadHelper('Casequery');

            if (strstr($case_types, "-")) {
                $expst3 = explode("-", $case_types);
                foreach ($expst3 as $st3) {
                    $csTypArr = $cq->getTypeArr($st3, $GLOBALS['TYPE']);
                    $types .= "<div class='fl filter_opn' rel='tooltip' title='Task Type' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"types\");'>" . $csTypArr['Type']['short_name'] . "<a href='javascript:void(0);' onclick='common_reset_filter(\"tasktype\",\"" . $st3 . "\",this);' class='fr'>X</a></div>"; //$this->Format->caseBcTypes($st3).", ";
                }
                $types = trim($types, ', ');
            } else {
                $csTypArr = $cq->getTypeArr($case_types, $GLOBALS['TYPE']);
                $types = "<div class='fl filter_opn' rel='tooltip' title='Task Type' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"types\");'>" . $csTypArr['Type']['short_name'] . "<a href='javascript:void(0);' onclick='common_reset_filter(\"tasktype\",\"" . $case_types . "\",this);' class='fr'>X</a></div>"; //$this->Format->caseBcTypes($case_types);
            }
            $arr['case_types'] = $types;
            $val = 1;
        } else {
            $arr['case_types'] = 'All';
        }

        //For Priority
        if (isset($this->params['data']['priFil']) && $this->params['data']['priFil']) {
            $pri_fil = $this->params['data']['priFil'];
        } elseif ($_COOKIE['PRIORITY']) {
            $pri_fil = $_COOKIE['PRIORITY'];
        }
        if ($pri_fil && $pri_fil != "all") {
            if (strstr($pri_fil, "-")) {
                $expst2 = explode("-", $pri_fil);
                foreach ($expst2 as $st2) {
                    $pri .= "<div class='fl filter_opn' rel='tooltip' title='Priority' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"priority\");'>" . $st2 . "<a href='javascript:void(0);' onclick='common_reset_filter(\"priority\",\"" . $st2 . "\",this);' class='fr'>X</a></div>";
                }
            } else {
                $pri = "<div class='fl filter_opn' rel='tooltip' title='Priority' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"priority\");'>" . $pri_fil . "<a href='javascript:void(0);' onclick='common_reset_filter(\"priority\",\"" . $pri_fil . "\",this);' class='fr'>X</a></div>";
            }
            $arr['pri'] = $pri;
            $val = 1;
        } else {
            $arr['pri'] = 'All';
        }

        //For Case Members
        if (isset($this->params['data']['caseMember']) && $this->params['data']['caseMember']) {
            $case_member = $this->params['data']['caseMember'];
        } elseif ($_COOKIE['MEMBERS']) {
            $case_member = $_COOKIE['MEMBERS'];
        }
        if ($case_member && $case_member != "all") {
            if (strstr($case_member, "-")) {
                $expst4 = explode("-", $case_member);
                $cbymems = $this->Format->caseMemsList($expst4);
                foreach ($cbymems as $key => $st4) {
                    $mems .= "<div class='fl filter_opn' rel='tooltip' title='Created By' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"users\");'>" . $st4 . "<a href='javascript:void(0);' onclick='common_reset_filter(\"members\",\"" . $key . "\",this);'  class='fr'>X</a></div>";
                }
            } else {
                $mems = "<div class='fl filter_opn' rel='tooltip' title='Created By' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"users\");'>" . $this->Format->caseMemsList($case_member) . "<a href='javascript:void(0);' onclick='common_reset_filter(\"members\",\"" . $case_member . "\",this);' class='fr'>X</a></div>";
            }
            $arr['case_member'] = $mems;
            $val = 1;
        } else {
            $arr['case_member'] = 'All';
        }
        //For AssignTo
        if (isset($this->params['data']['caseAssignTo']) && $this->params['data']['caseAssignTo']) {
            $case_assignto = $this->params['data']['caseAssignTo'];
        } elseif ($_COOKIE['ASSIGNTO']) {
            $case_assignto = $_COOKIE['ASSIGNTO'];
        }
        if ($case_assignto && $case_assignto != "all") {
            if (strstr($case_assignto, "-")) {
                $expst5 = explode("-", $case_assignto);
                $asmembers = $this->Format->caseMemsList($expst5);
                foreach ($asmembers as $key => $st5) {
                    $asns .= "<div class='fl filter_opn' rel='tooltip' title='Assign To' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"assignto\");'>" . $st5 . "<a href='javascript:void(0);' onclick='common_reset_filter(\"assignto\",\"" . $key . "\",this);' class='fr'>X</a></div>";
                }
            } else {
                $asns = "<div class='fl filter_opn' rel='tooltip' title='Assign To' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"assignto\");'>" . $this->Format->caseMemsList($case_assignto) . "<a href='javascript:void(0);' onclick='common_reset_filter(\"assignto\",\"" . $case_assignto . "\",this);' class='fr'>X</a></div>";
            }
            $arr['case_assignto'] = $asns;
            $val = 1;
        } else {
            $arr['case_assignto'] = 'All';
        }
        //For Case Date Status ....
        if (isset($this->params['data']['casedate']) && $this->params['data']['casedate']) {
            $date = $this->params['data']['casedate'];
        } else {
            if (isset($this->params['data']['resetall']) && $this->params['data']['resetall'] == 0) {
                $date = "";
            } else {
                $date = $this->Cookie->read('DATE');
            }
        }
        if (!empty($date) && ($date != 'any')) {
            $val = 1;
            if (trim($date) == 'one') {
                $arr['date'] = "<div class='fl filter_opn' rel='tooltip' title='Time' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"date\");'>Past hour<a href='javascript:void(0);' onclick='common_reset_filter(\"date\",\"\",this);' class='fr'>X</a></div>";
            } else if (trim($date) == '24') {
                $arr['date'] = "<div class='fl filter_opn' rel='tooltip' title='Time' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"date\");'>Past 24Hour<a href='javascript:void(0);' onclick='common_reset_filter(\"date\",\"\",this);' class='fr'>X</a></div>";
            } else if (trim($date) == 'week') {
                $arr['date'] = "<div class='fl filter_opn' rel='tooltip' title='Time' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"date\");');'>Past Week<a href='javascript:void(0);'  onclick='common_reset_filter(\"date\",\"\",this);' class='fr'>X</a></div>";
            } else if (trim($date) == 'month') {
                $arr['date'] = "<div class='fl filter_opn' rel='tooltip' title='Time' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"date\");'>Past month<a href='javascript:void(0);' onclick='common_reset_filter(\"date\",\"\",this);' class='fr'>X</a></div>";
            } else if (trim($date) == 'year') {
                $arr['date'] = "<div class='fl filter_opn' rel='tooltip' title='Time' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"date\");'>Past Year<a href='javascript:void(0);' onclick='common_reset_filter(\"date\",\"\",this);' class='fr'>X</a></div>";
            } else if (strstr(trim($date), ":")) {
                $arr['date'] = "<div class='fl filter_opn' rel='tooltip' title='Time' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"date\");'>" . str_replace(":", " - ", $date) . "<a href='javascript:void(0);' onclick='common_reset_filter(\"date\",\"\",this);' class='fr'>X</a></div>"; // str_replace(":"," - ",$date);
            }
        } else {
            $arr['date'] = "Any Time";
        }
        if (isset($this->params['data']['caseduedate']) && $this->params['data']['caseduedate']) {
            $duedate = $this->params['data']['caseduedate'];
        } else {
            if (isset($this->params['data']['resetall']) && $this->params['data']['resetall'] == 0) {
                $duedate = "";
            } else {
                $duedate = $_COOKIE['DUE_DATE'];
            }
        }
        if (!empty($duedate)) {
            $val = 1;
            if (trim($duedate) == 'overdue') {
                $arr['duedate'] = "<div class='fl filter_opn' rel='tooltip' title='Due Date' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"duedate\");'>Overdue<a href='javascript:void(0);' onclick='common_reset_filter(\"duedate\",\"\",this);' class='fr'>X</a></div>";
            } else if (trim($duedate) == '24') {
                $arr['duedate'] = "<div class='fl filter_opn' rel='tooltip' title='Due Date' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"duedate\");'>Today<a href='javascript:void(0);' onclick='common_reset_filter(\"duedate\",\"\",this);' class='fr'>X</a></div>";
            } else if (strstr(trim($duedate), ":")) {
                $arr['duedate'] = "<div class='fl filter_opn' rel='tooltip' title='Due Date' onclick='openfilter_popup(1,\"dropdown_menu_all_filters\");allfiltervalue(\"duedate\");'>" . str_replace(":", " - ", $duedate) . "<a href='javascript:void(0);' onclick='common_reset_filter(\"duedate\",\"\",this);' class='fr'>X</a></div>"; // str_replace(":"," - ",$date);
            }
        } else {
            $arr['duedate'] = "Any Time";
        }
        // Case page
        if (isset($this->params['data']['casePage']) && $this->params['data']['casePage']) {
            $case_page = $this->params['data']['casePage'];
        } elseif ($this->Cookie->read('PAGE')) {
            $case_page = $this->Cookie->read('PAGE');
        }
        // Case Search value
        if (isset($this->params['data']['caseSearch']) && $this->params['data']['caseSearch'] != "") {
            $case_search = trim(urldecode(htmlentities(strip_tags($this->params['data']['caseSearch']))));
        } elseif ($_COOKIE['SEARCH']) {
            $case_search = trim(urldecode(htmlentities(strip_tags($_COOKIE['SEARCH']))));
        }
        if (isset($this->params['data']['resetall'])) {
            $resetall = $this->params['data']['resetall'];
        }
        if (isset($this->params['data']['clearCaseSearch']) && $this->params['data']['clearCaseSearch']) {
            $case_search = "";
        }
        if (isset($case_search) && $case_search) {
            $arr['case_search'] = "<div class='fl filter_opn' rel='tooltip' title='Search'>" . $case_search . "<a href='javascript:void(0);' onclick='common_reset_filter(\"search\",\"\",this);' class='fr'>X</a></div>";
            $arr['search_case'] = $case_search;
            $val = 1;
        }
        if (isset($case_page) && $case_page && $case_page != 1 && $resetall == 0) {
            $arr['case_page'] = "<div class='fl filter_opn' rel='tooltip' title='Pagination'>Page: " . $case_page . "<a href='javascript:void(0);' onclick='common_reset_filter(\"casepage\",\"\",this);' class='fr'>X</a></div>";
            $arr['page_case'] = $case_page;
            $val = 1;
        }

        $arr['mlstn'] = "All";
        // Task Sort order tagging
        if (isset($_COOKIE['TASKSORTBY']) && $_COOKIE['TASKSORTBY'] != "") {
            $tsortby = $_COOKIE['TASKSORTBY'];
            $tsortorder = $_COOKIE['TASKSORTORDER'];
            if ($_COOKIE['TASKSORTBY'] == 'caseno') {
                $tsortby = 'Task#';
            } elseif ($_COOKIE['TASKSORTBY'] == 'caseAt') {
                $tsortby = 'Assigned to';
            } elseif ($_COOKIE['TASKSORTBY'] == 'duedate') {
                $tsortby = 'Due Date';
            } else {
                $tsortby = ucfirst($tsortby);
            }
            if ($tsortorder == 'DESC') {
                $sorticon = 'tsk_desc_icon';
            } else {
                $sorticon = 'tsk_asc_icon';
            }
            //$arr['tasksortby'] = "<div class='fl filter_opn' rel='tooltip' title='Sort by ".$tsortby.": ".$tsortorder."' onclick='openfilter_popup(1,\"dropdown_menu_sortby_filters\");'><span class='fl'>".$tsortby."</span><i class='fl ".$sorticon."'></i><a href='javascript:void(0);' onclick='common_reset_filter(\"taskorder\",\"\",this);' class='fr'>X</a></div>";
            $arr['tasksortby'] = "<div class='fl filter_opn' rel='tooltip' style='position:relative;' title='Sort by " . $tsortby . ": " . $tsortorder . "' onclick='openfilter_popup(1,\"dropdown_menu_sortby_filters\");'>" . $tsortby . "<i class=' " . $sorticon . "'></i><a href='javascript:void(0);' onclick='common_reset_filter(\"taskorder\",\"\",this);' class='fr' style='padding-left:20px;'>X</a></div>";
        }
        // Task Group by Tagging
        if (isset($_COOKIE['TASKGROUPBY']) && $_COOKIE['TASKGROUPBY'] != "") {
            $groupby = $_COOKIE['TASKGROUPBY'];
            if ($groupby == 'crtdate') {
                $gby = "Created Date";
            } else if ($groupby == 'duedate') {
                $gby = 'Due Date';
            } else if ($groupby == 'assignto') {
                $gby = 'Assigned to';
            } else {
                $gby = ucfirst($groupby);
            }
            $arr['taskgroupby'] = "<div class='fl filter_opn' rel='tooltip' title='Group by' onclick='openfilter_popup(1,\"dropdown_menu_groupby_filters\");'>" . $gby . "<a href='javascript:void(0);' onclick='common_reset_filter(\"taskgroupby\",\"\",this);' class='fr'>X</a></div>";
        }

        //if($this->params['data']['caseMenuFilters'] == 'milestone') {
        if (isset($this->params['data']['milestoneIds']) && $this->params['data']['milestoneIds']) {
            $milestoneIds = $this->params['data']['milestoneIds'];
        } elseif ($this->Cookie->read('MILESTONES')) {
            $milestoneIds = $this->Cookie->read('MILESTONES');
        }
        if (stristr($milestoneIds, "-")) {
            $cookies = trim(trim($milestoneIds, "-"));
            if ($cookies) {
                $ids = explode("-", $cookies);
                $this->loadModel('Milestone');
                $mlsArr = $this->Milestone->find('first', array('conditions' => array('Milestone.id' => $ids, 'Milestone.isactive' => 1), 'fields' => array('Milestone.title')));
                $titl = ucfirst(trim($mlsArr['Milestone']['title']));
                if (strlen($titl) > 5) {
                    $titl = substr($titl, 0, 5) . "...";
                }
                $arr['mlstn'] = "<div class='fl filter_opn' rel='tooltip' title='Milestone'>" . $titl . "<a href='javascript:void(0);' onclick='common_reset_filter(\"mlstn\",\"\",this);' class='fr'>X</a></div>";
                $val = 1;
            }
        }
        //}

        $arr['val'] = $val;
        echo json_encode($arr);
        exit;
    }

    // Jyoti start
    function milestone_archive() {
        $this->layout = 'ajax';
        $milestoneid = $this->params['data']['mid'];
        $milestone_title = $this->params['data']['title1'];
        //echo $milestoneid;
        if ($milestoneid) {
            $this->loadModel('Milestone');
            $this->Milestone->query("UPDATE milestones SET isactive=0 where id='" . $milestoneid . "'");
        }
        echo "Success";
        exit;
    }

## UPDATING THE SORTING ORDER FOR MILESTONE ########

    function sort_event() {
        $this->layout = 'ajax';
        $this->loadmodel('EasycaseMilestone');
        $data = $this->params->query;
        $data = $data['sort'];
        foreach ($data AS $key => $val)
            $this->EasycaseMilestone->query('UPDATE easycase_milestones SET id_seq = ' . ($key + 1) . " WHERE easycase_id=" . $val);
        echo 1;
        exit;
    }

##### EDIT THE REPLY TEXT ####################

    function edit_reply() {
        $this->layout = 'ajax';
        $case_id = $this->data['id'];
        $this->set('proj_id', $this->data['projid']);
        $this->loadmodel('Easycase');
        $this->Easycase->recursive = -1;
        $rec = $this->Easycase->query('SELECT * FROM easycases WHERE id=' . $case_id . " LIMIT 1");
        //$this->set('reply_flag',isset($this->data['reply_flag'])?1:0);
        $this->set('reply_flag', 1);
        $this->set('case_info', $rec['0']);
    }

#### EDITED TEXT OF REPLY ARE SAVED ###########	

    function save_editedvalue() {
        $this->layout = 'ajax';
        $caseno = $this->data['caseno'];
        $this->loadmodel('Easycase');
        $thisCase = $this->Easycase->findById($this->data['id']);

        $canEdit = 0;
        if ((SES_TYPE == 1 || SES_TYPE == 2 || SES_TYPE == 3 || ($thisCase['Easycase']['legend'] == 1 && SES_ID == $thisCase['Easycase']['user_id'])) && $thisCase['Easycase']['message']) {
            $canEdit = 1;
        }

        if ($canEdit && trim($this->data['message'])) {
            $Easycases['id'] = $this->data['id'];
            $Easycases['message'] = $this->data['message'];
            $Easycases['updated_by'] = SES_ID;
            $Easycases['dt_created'] = GMT_DATETIME;
            if ($this->Easycase->save($Easycases)) {
                $this->Easycase->query("UPDATE easycases SET updated_by='" . SES_ID . "', dt_created='" . GMT_DATETIME . "'  WHERE case_no='" . $caseno . "' AND istype=1 AND project_id=" . $this->data['proj_id']);
                echo 1;
                exit;
            } else {
                echo 0;
                exit;
            }
        } else {
            echo 0;
            exit;
        }
    }

    #### SAVE FILTER ###########

    function ajax_save_filter() {
        $this->layout = 'ajax';
        //For Case Status
        if (isset($this->params['data']['caseStatus']) && $this->params['data']['caseStatus']) {
            $case_status = $this->params['data']['caseStatus'];
        } elseif ($_COOKIE['STATUS']) {
            $case_status = $_COOKIE['STATUS'];
        }

        if ($case_status && $case_status != "all") {
            //$case_status = strrev($case_status);
            if (strstr($case_status, "-")) {
                $expst = explode("-", $case_status);
                foreach ($expst as $st) {
                    $status .= $this->Format->displayStatus($st) . ", ";
                }
            } else {
                $status = $this->Format->displayStatus($case_status) . ", ";
            }
            $arr['case_status'] = trim($status, ', ');
            //$val =1;
        } else {
            $arr['case_status'] = 'All';
        }

        //For case types
        if (isset($this->params['data']['caseType']) && $this->params['data']['caseType']) {
            $case_types = $this->params['data']['caseType'];
        } elseif ($_COOKIE['CS_TYPES']) {
            $case_types = $_COOKIE['CS_TYPES'];
        }
        $types = '';
        if ($case_types && $case_types != "all") {
            //$case_types = strrev($case_types);
            if (strstr($case_types, "-")) {
                $expst3 = explode("-", $case_types);
                foreach ($expst3 as $st3) {
                    $types .= $this->Format->caseBcTypes($st3) . ", ";
                }
                $types = trim($types, ', ');
            } else {
                $types = $this->Format->caseBcTypes($case_types);
            }
            $arr['case_types'] = $types;
            //$val =1;
        } else {
            $arr['case_types'] = 'All';
        }
        //For Priority
        if (isset($this->params['data']['casePriority']) && $this->params['data']['casePriority']) {
            $pri_fil = $this->params['data']['casePriority'];
        } elseif ($_COOKIE['PRIORITY']) {
            $pri_fil = $_COOKIE['PRIORITY'];
        }
        if ($pri_fil && $pri_fil != "all") {
            if (strstr($pri_fil, "-")) {
                $expst2 = explode("-", $pri_fil);
                foreach ($expst2 as $st2) {
                    $pri .= $st2 . ", ";
                }
                $pri = trim($pri, ', ');
            } else {
                $pri = $pri_fil;
            }
            $arr['pri'] = $pri;
            //$val =1;
        } else {
            $arr['pri'] = 'All';
        }
        //For Case Members
        if (isset($this->params['data']['caseMemeber']) && $this->params['data']['caseMemeber']) {
            $case_member = $this->params['data']['caseMemeber'];
        } elseif ($_COOKIE['MEMBERS']) {
            $case_member = $_COOKIE['MEMBERS'];
        }
        if ($case_member && $case_member != "all") {
            if (strstr($case_member, "-")) {
                $expst4 = explode("-", $case_member);
                foreach ($expst4 as $st4) {
                    $mems .= $this->Format->caseBcMems($st4) . ", ";
                }
            } else {
                $mems = $this->Format->caseBcMems($case_member) . ", ";
            }
            $arr['case_member'] = trim($mems, ', ');
            //$val =1;
        } else {
            $arr['case_member'] = 'All';
        }
        //For AssignTo
        if (isset($this->params['data']['caseAssignTo']) && $this->params['data']['caseAssignTo']) {
            $case_assignto = $this->params['data']['caseAssignTo'];
        } elseif ($_COOKIE['ASSIGNTO']) {
            $case_assignto = $_COOKIE['ASSIGNTO'];
        }
        if ($case_assignto && $case_assignto != "all") {
            if (strstr($case_assignto, "-")) {
                $expst5 = explode("-", $case_assignto);
                foreach ($expst5 as $st5) {
                    $asns .= $this->Format->caseBcMems($st5) . ", ";
                }
            } else {
                $asns = $this->Format->caseBcMems($case_assignto) . ", ";
            }
            $arr['case_assignto'] = trim($asns, ', ');
            $val = 1;
        } else {
            $arr['case_assignto'] = 'All';
        }
        //For Case Date Status ....
        if (isset($this->params['data']['caseDate']) && $this->params['data']['caseDate']) {
            $date = $this->params['data']['caseDate'];
        } else {
            $date = $this->Cookie->read('DATE');
        }
        if (!empty($date)) {
            //$val = 1;
            if (trim($date) == 'one') {
                $arr['date'] = "Past hour";
            } else if (trim($date) == '24') {
                $arr['date'] = "Past 24Hour";
            } else if (trim($date) == 'week') {
                $arr['date'] = "Past Week";
            } else if (trim($date) == 'month') {
                $arr['date'] = "Past month";
            } else if (trim($date) == 'year') {
                $arr['date'] = "Past Year";
            } else if (strstr(trim($date), ":")) {
                $arr['date'] = str_replace(":", " - ", $date);
            }
        } else {
            $arr['date'] = "Any Time";
        }
        $duedate = $_COOKIE['DUE_DATE'];
        $this->set('assignto', $arr['case_assignto']);
        $this->set('memebers', $arr['case_member']);
        $this->set('priority', $arr['pri']);
        $this->set('type', $arr['case_types']);
        $this->set('status', $arr['case_status']);
        $this->set('date', $arr['date']);
        $this->set('assignto_val', $case_assignto);
        $this->set('memebers_val', $case_member);
        $this->set('priority_val', $pri_fil);
        $this->set('type_val', $case_types);
        $this->set('status_val', $case_status);
        $this->set('date_val', $date);
        $this->set('duedate_val', $duedate);
        $this->set('search_val', $this->params['data']['caseSearch']);
    }

    function ajax_customfilter_save() {
        $this->layout = 'ajax';
        $caseStatus = $this->params['data']['caseStatus'];
        $caseType = $this->params['data']['caseType'];
        $caseDate = $this->params['data']['caseDate'];
        $casedueDate = $this->params['data']['casedueDate'];
        $caseMemeber = $this->params['data']['caseMemeber'];
        $caseAssignTo = $this->params['data']['caseAssignTo'];
        $casePriority = $this->params['data']['casePriority'];
        $filterName = trim($this->params['data']['filterName']);
        $projuniqid = $this->params['data']['projuniqid'];
        $caseSearch = $this->params['data']['caseSearch'];
        $this->loadModel('CustomFilter');
        $customFilters = $this->CustomFilter->find('first', array('conditions' => array('CustomFilter.filter_name' => $filterName, 'CustomFilter.company_id' => SES_COMP, 'CustomFilter.user_id' => SES_ID), 'fields' => array('CustomFilter.id'))); //,'CustomFilter.project_uniq_id'=>$projuniqid
        if (!empty($customFilters) && !empty($customFilters['CustomFilter']['id'])) {
            echo 'exists';
        } else {
            $this->CustomFilter->query("INSERT INTO custom_filters SET project_uniq_id='" . $projuniqid . "', company_id='" . SES_COMP . "', user_id='" . SES_ID . "', filter_name='" . $filterName . "',filter_date='" . $caseDate . "',filter_duedate='" . $casedueDate . "', filter_type_id='" . $caseType . "',filter_status='" . $caseStatus . "', filter_member_id='" . $caseMemeber . "', filter_priority='" . $casePriority . "',filter_assignto='" . $caseAssignTo . "',filter_search='" . $caseSearch . "', dt_created='" . GMT_DATETIME . "'");
            echo "success";
        }
        exit;
    }

    function ajax_customfilter_delete() {
        $this->layout = 'ajax';
        if (!empty($this->params['data']['id'])) {
            $this->loadModel('CustomFilter');
            $customFilters = $this->CustomFilter->find('first', array('conditions' => array('CustomFilter.id' => $this->params['data']['id']), 'fields' => array('CustomFilter.id')));
            if (!empty($customFilters) && !empty($customFilters['CustomFilter']['id'])) {
                $this->CustomFilter->id = $customFilters['CustomFilter']['id'];
                $res = $this->CustomFilter->delete();
                if ($res) {
                    echo "success";
                } else {
                    echo 'error';
                }
            } else {
                echo 'error';
            }
        }
        exit;
    }

    function ajax_custom_filter_show() {
        $this->layout = 'ajax';
        $limit_1 = $this->params['data']['limit1'];
        if (isset($limit_1)) {
            $limit1 = (int) $limit_1 + 3;
            $limit2 = 3;
        } else {
            $limit1 = 0;
            $limit2 = 3;
        }
        $this->loadModel('CustomFilter');
        $getcustomfilter = "SELECT SQL_CALC_FOUND_ROWS * FROM custom_filters AS CustomFilter WHERE CustomFilter.company_id = '" . SES_COMP . "' and CustomFilter.user_id =  '" . SES_ID . "' ORDER BY CustomFilter.dt_created DESC LIMIT $limit1,$limit2";
        $getfilter = $this->CustomFilter->query($getcustomfilter);
        $tot = $this->CustomFilter->query("SELECT FOUND_ROWS() as total");
        $this->set('getfilter', $getfilter);
        $this->set('limit1', $limit1);
        $this->set('totalfilter', $tot[0][0]['total']);
    }

    function easycaseSql() {
        $sql = "SELECT DISTINCT Easycase.case_no, Easycase.project_id, COUNT(*) AS total FROM `easycases` AS Easycase WHERE Easycase.istype= 1 GROUP BY Easycase.case_no, Easycase.project_id ORDER BY Easycase.case_no DESC";
        $easycase = $this->Easycase->query($sql);
        foreach ($easycase AS $key => $val) {
            if ($val['0']['total'] > 1) {
                $sql1 = "UPDATE easycases SET istype=2 WHERE case_no=" . $val['Easycase']['case_no'] . " AND project_id=" . $val['Easycase']['project_id'] . " AND case_count=0 AND istype=1";
                $this->Easycase->query($sql1);
                print $sql1 . "<br/>";
            }
        }
        exit;
    }

    function ajax_duedate() {
        $this->layout = 'ajax';
        $this->set('due_date', $_COOKIE['DUE_DATE']);
    }

    function edit_task_details() {
        $this->layout = 'ajax';
        $caseUid = $this->data['csUniqid'];
        if ($caseUid) {
            $casedetails = $this->Easycase->findByUniqId($caseUid);
            if ($casedetails) {
                //$projectdtls = ClassRegistry::init('Project')->find('first',array('conditions'=>array('Project.id'=>$casedetails['Easycase']['project_id']),'fields'=>array('name','uniq_id')));
                $arr['succ'] = 1;
                if ($casedetails['Easycase']['due_date']) {
                    $casedetails['Easycase']['due_date'] = date('m/d/Y', strtotime($casedetails['Easycase']['due_date']));
                }
                $casedetails['Easycase']['milestone'] = 'No Milestone';
                $casedetails['Easycase']['milestone_id'] = '';

                //$arr['data']['project_name']= $projectdtls['Project']['name'];
                //$arr['data']['project_uniq_id']= $projectdtls['Project']['uniq_id'];
                //Checking for milestone and Getting the milestone details
                $mlst_list = ClassRegistry::init('Milestone')->find('list', array('conditions' => array('project_id' => $casedetails['Easycase']['project_id'], 'isactive' => 1), 'order' => 'end_date DESC'));
                $emdetails = ClassRegistry::init('EasycaseMilestone')->find('first', array('conditions' => array('project_id' => $casedetails['Easycase']['project_id'], 'easycase_id' => $casedetails['Easycase']['id'])));
                if ($emdetails) {
                    $casedetails['Easycase']['milestone'] = $mlst_list[$emdetails['EasycaseMilestone']['milestone_id']];
                    $casedetails['Easycase']['milestone_id'] = $emdetails['EasycaseMilestone']['milestone_id'];
                }
                $arr['mlst_list'] = '';
                $arr['data'] = $casedetails['Easycase'];
                if ($mlst_list) {
                    $arr['mlst_list'] = $mlst_list;
                }
                $files = ClassRegistry::init('CaseFile')->find('all', array('conditions' => array('CaseFile.easycase_id' => $casedetails['Easycase']['id']), 'fields' => array('CaseFile.id', 'CaseFile.file', 'CaseFile.file_size', 'CaseFile.count')));
                if ($files) {
                    $arr['files'] = $files;
                } else {
                    $arr['files'] = '';
                }
                echo json_encode($arr);
                exit;
            } else {
                $arr['err'] = 1;
                $arr['msg'] = 'No matched record found with this id';
                echo json_encode($arr);
                exit;
            }
        } else {
            $arr['err'] = 1;
            $arr['msg'] = 'Invalid Case id';
            echo json_encode($arr);
            exit;
        }
    }

    //Move task from prject to project starts
    function ajax_move_task_to_project() {
        $this->layout = 'ajax';
        $project_id = $this->params['data']['project_id'];
        $case_id = $this->params['data']['case_id'];
        $this->loadModel('Project');
        $this->Project->recursive = -1;
        $sql = "SELECT DISTINCT Project.name,Project.id,Project.uniq_id FROM projects AS Project,
		project_users AS ProjectUser WHERE Project.id = ProjectUser.project_id AND ProjectUser.user_id='" . SES_ID . "'
		    and ProjectUser.company_id='" . SES_COMP . "' AND Project.isactive='1' AND Project.name !='' ORDER BY ProjectUser.dt_visited DESC";
        $projects = $this->Project->query($sql);
        $this->set('projects', $projects);
        $this->set('project_id', $project_id);
        $this->set('case_id', $case_id);
        $this->set('is_multiple', $this->data['is_multiple']);
    }

    function move_task_to_project() {
        $this->layout = 'ajax';
        //echo "<pre>";print_r($this->data);exit;
        $project_id = $this->params['data']['project_id'];
        $old_project_id = $this->params['data']['old_project_id'];
        $cond = ' 1 ';
        if ($this->data['is_multiple']) {
            $case_no = $this->params['data']['case_no'];
            $case_nos = implode(',', $case_no);
            $cond .= ' AND  FIND_IN_SET(case_no,"' . $case_nos . '") ';
        } else {
            $case_no = $this->params['data']['case_no'];
            $cond .= ' AND  case_no=' . $case_no . ' ';
        }

        $this->loadModel('Easycase');
        //Getting highest count of case number of new project.
        $sql = "SELECT MAX(case_no)+1 AS case_no FROM easycases AS Easycase WHERE project_id='" . $project_id . "'";
        $max_case_no = $this->Easycase->query($sql);
        if (isset($max_case_no['0']['0']['case_no']) && !empty($max_case_no['0']['0']['case_no'])) {
            $max_case = $max_case_no['0']['0']['case_no'];
        } else {
            $max_case = 1;
        }

        //Getting all case ids which move to new project.
        $sql = "SELECT Easycase.id, Easycase.user_id,Easycase.assign_to,GROUP_CONCAT(Easycase.id) as easycase_ids FROM easycases AS Easycase WHERE " . $cond . " AND project_id='" . $old_project_id . "' GROUP BY case_no";
        $cases = $this->Easycase->query($sql);
        if (isset($cases) && !empty($cases)) {
            $this->loadModel('ProjectUser');
            $this->loadModel('CaseFile');
            $this->loadModel('CaseFileDrive');
            $this->loadModel('CaseRecent');
            $this->loadModel('CaseUserView');
            $this->loadModel('CaseActivity');
            $this->loadModel('EasycaseMilestone');

            foreach ($cases as $key => $case) {
                $easycase['Easycase']['id'] = $case['Easycase']['id'];
                $easycase['Easycase']['project_id'] = $project_id;
                $easycase['Easycase']['case_no'] = $max_case;

                //Getting assign user is exist in new project or not
//		    if(($case['Easycase']['user_id'] == SES_ID) && ($case['Easycase']['user_id'] !== $case['Easycase']['assign_to'])) {
//				$assignto = $this->ProjectUser->find('first',array('conditions'=>array('ProjectUser.project_id'=>$project_id,'ProjectUser.user_id'=>$case['Easycase']['assign_to'],'ProjectUser.company_id'=>SES_COMP)));
//				if(isset($assignto) && empty($assignto)) {
//					$easycase['Easycase']['assign_to'] = SES_ID;
//				}
//		    }
                //Move to new project
                if (strstr($case['0']['easycase_ids'], ',')) {
                    $casearr = explode(',', $case['0']['easycase_ids']);
                } else {
                    $casearr[] = $case['0']['easycase_ids'];
                }
                if ($this->Easycase->updateAll(array('Easycase.project_id' => $project_id, 'Easycase.case_no' => $max_case), array('Easycase.id' => $casearr, 'Easycase.project_id' => $old_project_id))) {
                    //Update case files
                    $this->CaseFile->updateAll(array('CaseFile.project_id' => $project_id), array('CaseFile.easycase_id' => $casearr, 'CaseFile.project_id' => $old_project_id, 'CaseFile.company_id' => SES_COMP));
                    //Update case files drives
                    $this->CaseFileDrive->updateAll(array('CaseFileDrive.project_id' => $project_id), array('CaseFileDrive.easycase_id' => $casearr, 'CaseFileDrive.project_id' => $old_project_id));
                    //Update Case Recent
                    $this->CaseRecent->updateAll(array('CaseRecent.project_id' => $project_id), array('CaseRecent.easycase_id' => $casearr, 'CaseRecent.project_id' => $old_project_id));
                    //Update Case User ViewS
                    $this->CaseUserView->updateAll(array('CaseUserView.project_id' => $project_id), array('CaseUserView.easycase_id' => $casearr, 'CaseUserView.project_id' => $old_project_id));
                    //Update Case Activity
                    $this->CaseActivity->updateAll(array('CaseActivity.project_id' => $project_id, 'CaseActivity.case_no' => $max_case), array('CaseActivity.easycase_id' => $casearr, 'CaseActivity.project_id' => $old_project_id));
                    //Delete milestone Linking for the moved task
                    $this->EasycaseMilestone->deleteAll(array('EasycaseMilestone.easycase_id' => $casearr, 'EasycaseMilestone.project_id' => $old_project_id));
                    $msg = 1;
                } else {
                    $msg = 0;
                }
                if ($this->data['is_multiple']) {
                    $max_case++;
                }
            }
        } else {
            $msg = 0;
        }
        echo $msg;
        exit;
    }

    /**
     * @method Public kanban_task() Used for the kanban view of the tasks
     * @return JSON json data will be returned and is going to be used in Json kanban_task template
     */
    function kanban_task() {
        $this->layout = 'ajax';
        $kanbanTaskList = array();

        //$page_limit = CASE_PAGE_LIMIT;
        $page_limit = 10;
        $this->_datestime();

        $search_key = $this->data['search_key']; // searching value

        $projUniq = $this->data['projFil']; // Project Uniq ID
        $projIsChange = $this->data['projIsChange']; // Project Uniq ID

        $caseStatus = $this->data['caseStatus']; // Filter by Status(legend)
        $priorityFil = $this->data['priFil']; // Filter by Priority
        $caseTypes = $this->data['caseTypes']; // Filter by case Types
        $caseUserId = $this->data['caseMember']; // Filter by Member
        $caseAssignTo = $this->data['caseAssignTo']; // Filter by AssignTo
        $caseDate = $this->data['caseDate']; // Sort by Date
        $caseSrch = $this->data['caseSearch']; // Search by keyword
        $casePage = $this->data['casePage']; // Pagination
        $caseUniqId = $this->data['caseId']; // Case Uniq ID to close a case
        $caseTitle = $this->data['caseTitle']; // Case Uniq ID to close a case
        $caseDueDate = $this->data['caseDueDate']; // Sort by Due Date

        $caseNum = $this->data['caseNum']; // Sort by Due Date
        $caseLegendsort = $this->data['caseLegendsort']; // Sort by Case Status
        $caseAtsort = $this->data['caseAtsort']; // Sort by Case Status
        $startCaseId = $this->data['startCaseId']; // Start Case
        $caseResolve = $this->data['caseResolve']; // Resolve Case

        $caseMenuFilters = $this->data['caseMenuFilters']; // Resolve Case
        $milestoneIds = $this->data['milestoneIds']; // Resolve Case
        $milestoneUid = $this->data['milestoneUid'];
        $caseCreateDate = $this->data['caseCreateDate']; // Sort by Created Date
        @$case_srch = $this->data['case_srch'];
        @$case_date = $this->data['case_date'];
        @$case_duedate = $this->data['case_due_date'];
        @$milestone_type = $this->data['mstype'];
        $changecasetype = $this->data['caseChangeType'];
        $caseChangeDuedate = $this->data['caseChangeDuedate'];
        $caseChangePriority = $this->data['caseChangePriority'];
        $caseChangeAssignto = $this->data['caseChangeAssignto'];
        $customfilterid = $this->data['customfilter'];
        $detailscount = $this->data['data']['detailscount']; // Count number to open casedetails
        $morecontent = $this->data['morecontent'];
        if ($customfilterid) {
            $this->loadModel('CustomFilter');
            //$getcustomfilter = "SELECT  * FROM custom_filters AS CustomFilter WHERE CustomFilter.company_id = '".SES_COMP."' and CustomFilter.user_id =  '".SES_ID."' and CustomFilter.id=".$customfilterid." ORDER BY CustomFilter.dt_created DESC ";
            $getfilter = $this->CustomFilter->find('first', array('conditions' => array('CustomFilter.company_id' => SES_COMP, 'CustomFilter.user_id' => SES_ID, 'CustomFilter.id' => $customfilterid), 'order' => 'CustomFilter.dt_created DESC'));
            $caseStatus = $getfilter['CustomFilter']['filter_status'];
            $priorityFil = $getfilter['CustomFilter']['filter_priority'];
            $caseTypes = $getfilter['CustomFilter']['filter_type_id'];
            $caseUserId = $getfilter['CustomFilter']['filter_member_id'];
            $caseAssignTo = $getfilter['CustomFilter']['filter_assignto'];
            $caseDate = $getfilter['CustomFilter']['filter_date'];
            $case_duedate = $getfilter['CustomFilter']['filter_duedate'];
            $caseSrch = $getfilter['CustomFilter']['filter_search'];
        }
        if ($caseMenuFilters) {
            setcookie('CURRENT_FILTER', $caseMenuFilters, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        } else {
            setcookie('CURRENT_FILTER', $caseMenuFilters, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        }
        $caseUrl = $this->data['caseUrl'];
        $curProjId = NULL;
        $curProjShortName = NULL;
        if ($projUniq != 'all') {
            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $projUniq, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('Project.id', 'Project.short_name', 'ProjectUser.id')));
            if (count($projArr)) {
                $curProjId = $projArr['Project']['id'];
                $curProjShortName = $projArr['Project']['short_name'];

                //Updating ProjectUser table to current date-time
                if ($projIsChange != $projUniq) {
                    $ProjectUser['id'] = $projArr['ProjectUser']['id'];
                    $ProjectUser['dt_visited'] = GMT_DATETIME;
                    $this->ProjectUser->save($ProjectUser);
                }
            }
        }
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $dt = $view->loadHelper('Datetime');
        $cq = $view->loadHelper('Casequery');
        $frmt = $view->loadHelper('Format');
        ######### Filete with Milestone ##########
        if ($milestoneUid) {
            $mlst_cls = ClassRegistry::init('Milestone');
            //$mlist = $mlst_cls->find('first',array('conditions'=>array('Milestone.uniq_id'=>$milestoneUid),'fields'=>'Milestone.id,Milestone.title'));
            $mls = $mlst_cls->query("SELECT `Milestone`.`id`,`Milestone`.`title`,`Milestone`.`project_id`,`Milestone`.`end_date`,`Milestone`.`uniq_id`,`Milestone`.`isactive`,`Milestone`.`user_id`,COUNT(c.easycase_id) AS totalcases,GROUP_CONCAT(c.easycase_id) AS `caseids`  FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id WHERE `Milestone`.`uniq_id` ='" . $milestoneUid . "'  AND `Milestone`.`company_id` = " . SES_COMP);
            //echo "<pre>";print_r($mls);exit;
            $resCaseProj['mlstTitle'] = $mls[0]['Milestone']['title'];
            $resCaseProj['mlstId'] = $mls[0]['Milestone']['id'];
            $resCaseProj['mlstUid'] = $milestoneUid;
            $resCaseProj['mlstProjId'] = $mls[0]['Milestone']['project_id'];
            $resCaseProj['mlsttotalCs'] = $mls[0][0]['totalcases'];
            $resCaseProj['mlsttype'] = $mls[0]['Milestone']['isactive'];

            $curCreated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
            $curTime = date('H:i:s', strtotime($curCreated));

            $closed_cases = $mlst_cls->query("SELECT EasycaseMilestone.milestone_id,COUNT(Easycase.id) as totcase FROM easycase_milestones AS EasycaseMilestone LEFT JOIN easycases as Easycase ON   EasycaseMilestone.easycase_id=Easycase.id WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND EasycaseMilestone.milestone_id='" . $mls[0]['Milestone']['id'] . "'  GROUP BY  EasycaseMilestone.milestone_id");
            $tot_closed_case = $closed_cases[0][0]['totcase'];

            $endDate = $mls[0]['Milestone']['end_date'] . " " . $curTime;
            $days = $dt->dateDiff($endDate, $curCreated);

            $mlstDT = $dt->dateFormatOutputdateTime_day($mls[0]['Milestone']['end_date'], GMT_DATETIME, 'week');

            $totalCs = $mls[0][0]['totalcases'];
            $totalClosedCs = 0;
            if ($tot_closed_case) {
                $totalClosedCs = $tot_closed_case;
            }
            $fill = 0;
            if ($totalClosedCs != 0) {
                $fill = round((($totalClosedCs / $totalCs) * 100));
            }

            $resCaseProj['intEndDate'] = strtotime($endDate);
            $resCaseProj['mlstdays_diff'] = $days;
            $resCaseProj['mlstDT'] = $mlstDT;
            $resCaseProj['mlstFill'] = $fill;
            $resCaseProj['totalClosedCs'] = $totalClosedCs;
            $resCaseProj['totalCs'] = $totalCs;

            $qry .= ' AND EasycaseMilestone.milestone_id=' . $mls[0]['Milestone']['id'];
            //$resCaseProj['mlstTitle'] = $mlist['Milestone']['title'];
            //$resCaseProj['mlstId'] = $mlist['Milestone']['id'];
        } else {
            $resCaseProj['mlstTitle'] = '';
            $resCaseProj['mlstId'] = '';

            ######### Filter by CaseUniqId ##########
            $qry = "";
            if (trim($caseUrl)) {
                $qry .= " AND Easycase.uniq_id='" . $caseUrl . "'";
            }
            ######### Filter by Status ##########
            if ($caseStatus != "all") {
                $qry .= $this->Format->statusFilter($caseStatus);
                $stsLegArr = $caseStatus . "-" . "";
                $expStsLeg = explode("-", $stsLegArr);
                if (!in_array("upd", $expStsLeg)) {
                    $qry .= " AND Easycase.type_id !=10";
                }
            }

            ######### Filter by Case Types ##########
            if ($caseTypes && $caseTypes != "all") {
                $qry .= $this->Format->typeFilter($caseTypes);
            }
            ######### Filter by Priority ##########
            if ($priorityFil && $priorityFil != "all") {
                $qry .= $this->Format->priorityFilter($priorityFil, $caseTypes);
            }
            ######### Filter by Member ##########
            if ($caseUserId && $caseUserId != "all") {
                $qry .= $this->Format->memberFilter($caseUserId);
            }
            ######### Filter by AssignTo ##########
            if ($caseAssignTo && $caseAssignTo != "all") {
                $qry .= $this->Format->assigntoFilter($caseAssignTo);
            }

            ######### Search by KeyWord ##########
            $searchcase = "";
            if (trim(urldecode($caseSrch)) && (trim($case_srch) == "")) {
                $searchcase = $this->Format->caseKeywordSearch($caseSrch, 'full');
            }
            if (trim(urldecode($case_srch)) != "") {
                $searchcase = "AND (Easycase.case_no = '$case_srch')";
            }

            if (trim(urldecode($caseSrch))) {
                if ((substr($caseSrch, 0, 1)) == '#') {
                    $tmp = explode("#", $caseSrch);
                    $casno = trim($tmp['1']);
                    $searchcase = " AND (Easycase.case_no = '" . $casno . "')";
                }
            }
            $cond_easycase_actuve = "";
            if ((isset($case_srch) && !empty($case_srch)) || isset($caseSrch) && !empty($caseSrch)) {
                $cond_easycase_actuve = "";
            } else {
                $cond_easycase_actuve = "AND Easycase.isactive=1";
            }
            if (trim($case_date) != "") {
                if (trim($case_date) == 'one') {
                    $one_date = date('Y-m-d H:i:s', time() - 3600);
                    $qry .= " AND Easycase.dt_created >='" . $one_date . "'";
                } else if (trim($case_date) == '24') {
                    $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 day"));
                    $qry .= " AND Easycase.dt_created >='" . $day_date . "'";
                } else if (trim($case_date) == 'week') {
                    $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 week"));
                    $qry .= " AND Easycase.dt_created >='" . $week_date . "'";
                } else if (trim($case_date) == 'month') {
                    $month_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 month"));
                    $qry .= " AND Easycase.dt_created >='" . $month_date . "'";
                } else if (trim($case_date) == 'year') {
                    $year_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 year"));
                    $qry .= " AND Easycase.dt_created >='" . $year_date . "'";
                } else if (strstr(trim($case_date), ":")) {
                    $ar_dt = explode(":", trim($case_date));
                    $frm_dt = $ar_dt['0'];
                    $to_dt = $ar_dt['1'];
                    $qry .= " AND DATE(Easycase.dt_created) >= '" . date('Y-m-d H:i:s', strtotime($frm_dt)) . "' AND DATE(Easycase.dt_created) <= '" . date('Y-m-d H:i:s', strtotime($to_dt)) . "'";
                }
            }
            if (trim($case_duedate) != "") {
                if (trim($case_duedate) == '24') {
                    $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 day"));
                    $qry .= " AND (DATE(Easycase.due_date) ='" . GMT_DATE . "')";
                } else if (trim($case_duedate) == 'overdue') {
                    $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 week"));
                    $qry .= " AND ( DATE(Easycase.due_date) <'" . GMT_DATE . "') AND (Easycase.legend =1 || Easycase.legend=2) ";
                } else if (strstr(trim($case_duedate), ":")) {
                    $ar_dt = explode(":", trim($case_duedate));
                    $frm_dt = $ar_dt['0'];
                    $to_dt = $ar_dt['1'];
                    $qry .= " AND DATE(Easycase.due_date) >= '" . date('Y-m-d', strtotime($frm_dt)) . "' AND DATE(Easycase.due_date) <= '" . date('Y-m-d', strtotime($to_dt)) . "'";
                }
            }
        }
        $msQuery1 = " ";

        $resCaseProj['page_limit'] = $page_limit;
        $resCaseProj['csPage'] = $casePage;
        $resCaseProj['caseUrl'] = $caseUrl;
        $resCaseProj['projUniq'] = $projUniq;
        $resCaseProj['csdt'] = $caseDate;
        $resCaseProj['csTtl'] = $caseTitle;
        $resCaseProj['csDuDt'] = $caseDueDate;
        $resCaseProj['csCrtdDt'] = $caseCreateDate;
        $resCaseProj['csNum'] = $caseNum;
        $resCaseProj['csLgndSrt'] = $caseLegendsort;
        $resCaseProj['csAtSrt'] = $caseAtsort;
        $resCaseProj['caseMenuFilters'] = $caseMenuFilters;
        $resCaseProj['morecontent'] = $morecontent;

        if ($projUniq) {
            //$this->Easycase->query('SET CHARACTER SET utf8');
            $page = $casePage;
            $newTask_limit = $this->data['newTask_limit'];
            $inProgressTask_limit = $this->data['inProgressTask_limit'];
            $resolvedTask_limit = $this->data['resolvedTask_limit'];
            $closedTask_limit = $this->data['closedTask_limit'];
            //$limit1 = $page*$page_limit-$page_limit;
            $limit2 = $page_limit;
            if ($projUniq == 'all') {
                if (($morecontent && $morecontent == 'newTask') || !$morecontent) {
                    $caseAll['newTask'] = $this->Easycase->query("SELECT  Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE Easycase.istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') " . $searchcase . " " . trim($qry) . "  ORDER BY  Easycase.dt_created DESC) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE Easycase.legend = 1 ORDER BY Easycase.dt_created DESC LIMIT $newTask_limit,$limit2");
                }
                if (($morecontent && $morecontent == 'inprogressTask') || !$morecontent) {
                    $caseAll['inprogressTask'] = $this->Easycase->query("SELECT  Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE Easycase.istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') " . $searchcase . " " . trim($qry) . "  ORDER BY  Easycase.dt_created DESC) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE Easycase.legend = 2 OR Easycase.legend = 4  ORDER BY Easycase.dt_created DESC LIMIT $inProgressTask_limit,$limit2");
                }
                if (($morecontent && $morecontent == 'resolvedTask') || !$morecontent) {
                    $caseAll['resolvedTask'] = $this->Easycase->query("SELECT  Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE Easycase.istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') " . $searchcase . " " . trim($qry) . "  ORDER BY  Easycase.dt_created DESC) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE Easycase.legend = 5 ORDER BY Easycase.dt_created DESC LIMIT $resolvedTask_limit,$limit2");
                }
                if (($morecontent && $morecontent == 'closedTask') || !$morecontent) {
                    $caseAll['closedTask'] = $this->Easycase->query("SELECT  Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE Easycase.istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') " . $searchcase . " " . trim($qry) . "  ORDER BY  Easycase.dt_created DESC) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE Easycase.legend = 3 ORDER BY Easycase.dt_created DESC LIMIT $closedTask_limit,$limit2");
                }
            } else {
                if (($morecontent && $morecontent == 'newTask') || !$morecontent) {
                    $caseAll['newTask'] = $this->Easycase->query("SELECT Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  AND Easycase.title LIKE '%$search_key%' " . $searchcase . " " . trim($qry) . " ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE Easycase.legend = 1 ORDER BY dt_created DESC LIMIT $newTask_limit,$limit2");
                }
                if (($morecontent && $morecontent == 'inprogressTask') || !$morecontent) {
                    $caseAll['inprogressTask'] = $this->Easycase->query("SELECT Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  AND Easycase.title LIKE '%$search_key%' " . $searchcase . " " . trim($qry) . " ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE (Easycase.legend = 2) OR (Easycase.legend = 4)  ORDER BY dt_created DESC LIMIT $inProgressTask_limit,$limit2");
                }
                if (($morecontent && $morecontent == 'resolvedTask') || !$morecontent) {
                    $caseAll['resolvedTask'] = $this->Easycase->query("SELECT Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0 AND Easycase.title LIKE '%$search_key%'  " . $searchcase . " " . trim($qry) . " ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE Easycase.legend = 5  ORDER BY dt_created DESC LIMIT $resolvedTask_limit,$limit2");
                }
                if (($morecontent && $morecontent == 'closedTask') || !$morecontent) {
                    $caseAll['closedTask'] = $this->Easycase->query("SELECT Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0 AND Easycase.title LIKE '%$search_key%' " . $searchcase . " " . trim($qry) . " ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE Easycase.legend = 3  ORDER BY dt_created DESC LIMIT $closedTask_limit,$limit2");
                }
            }

            $msQ = "";

            $ProjectUser = ClassRegistry::init('ProjectUser');
            if ($projUniq != 'all') {
                $usrDtlsAll = $ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id='" . $curProjId . "' AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
            } else {
                $usrDtlsAll = $ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
            }
            $usrDtlsArr = array();
            $usrDtlsPrj = array();
            foreach ($usrDtlsAll as $ud) {
                $usrDtlsArr[$ud['User']['id']] = $ud;
            }
        } else {
            $CaseCount = 0;
        }
        $resCaseProj['caseCount'] = $CaseCount;
        $frmtCaseAll = $this->Easycase->formatKanbanTask($caseAll, $CaseCount, $caseMenuFilters, $c, $m, $projUniq, $usrDtlsArr, $frmt, $dt, $tz, $cq);
//        pr($frmtCaseAll);
        $this->loadModel('EasycaseMilestone');
        foreach ($frmtCaseAll as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $res_milestones = $this->EasycaseMilestone->findByEasycaseId($value1['Easycase']['id']);
                if ($res_milestones['EasycaseMilestone']['milestone_id']) {
                    $frmtCaseAll[$key][$key1]['Easycase']['milestone_id'] = $res_milestones['EasycaseMilestone']['milestone_id'];
                }
//                pr($frmtCaseAll[$key][$key1]['Easycase']['milestone_id']);
            }
        }
        $resCaseProj['caseAll'] = $frmtCaseAll;

        $resCaseProj['newTask_limit'] = isset($frmtCaseAll['newTask']) ? (count($frmtCaseAll['newTask']) + $newTask_limit) : $newTask_limit;
        $resCaseProj['inProgressTask_limit'] = isset($frmtCaseAll['inprogressTask']) ? (count($frmtCaseAll['inprogressTask']) + $inProgressTask_limit) : $inProgressTask_limit;
        $resCaseProj['resolvedTask_limit'] = isset($frmtCaseAll['resolvedTask']) ? (count($frmtCaseAll['resolvedTask']) + $resolvedTask_limit) : $resolvedTask_limit;
        $resCaseProj['closedTask_limit'] = isset($frmtCaseAll['closedTask']) ? (count($frmtCaseAll['closedTask']) + $closedTask_limit) : $closedTask_limit;
        $curCreated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        $friday = date('Y-m-d', strtotime($curCreated . "next Friday"));
        $monday = date('Y-m-d', strtotime($curCreated . "next Monday"));
        $tomorrow = date('Y-m-d', strtotime($curCreated . "+1 day"));

        $resCaseProj['intCurCreated'] = strtotime($curCreated);
        $resCaseProj['mdyCurCrtd'] = date('m/d/Y', strtotime($curCreated));
        $resCaseProj['mdyFriday'] = date('m/d/Y', strtotime($friday));
        $resCaseProj['mdyMonday'] = date('m/d/Y', strtotime($monday));
        $resCaseProj['mdyTomorrow'] = date('m/d/Y', strtotime($tomorrow));

        if ($projUniq != 'all') {
            $projUser = array();
            if ($projUniq) {
                $projUser = array($projUniq => $this->Easycase->getMemebers($projUniq));
            }
            $resCaseProj['projUser'] = $projUser;
        }
//        pr($resCaseProj);
//        exit;
        $this->set('kanbanTaskList', json_encode($resCaseProj));
    }

    /**
     * @method public ajax_startcase() Change the caselegend to inprogress
     * @return json
     */
    function taskactions() {
        $response = $this->Easycase->actionOntask($this->data['taskId'], $this->data['taskUid'], $this->data['type']);
        if ($response['pub_msg']) {
            $this->Postcase->iotoserver($response['pub_msg']);
        }
        echo json_encode($response);
        exit;
    }

    /**
     * @method mydashboard
     * @author Orangescrum
     * @return html
     */
    function mydashboard() {

        if (!empty($this->request->query['case_search'])) {
            $this->redirect('/dashboard?search=' . $this->request->query['case_search'] . '#tasks');
        }

        $dashboard_order = $GLOBALS['DASHBOARD_ORDER'];
        if ($_COOKIE['DASHBOARD_ORDER']) {
            $dashboard = explode("::", $_COOKIE['DASHBOARD_ORDER']);
            if (!empty($dashboard['0'])) {
                if (strpos($dashboard['0'], "_")) {
                    $info = explode("_", $dashboard['0']);
                    if (!empty($info) && ($info['0'] == SES_ID) && ($info['1'] == SES_COMP)) {
                        $order = explode(",", $dashboard['1']);
                        if (!empty($order) && !in_array('7', $order) && in_array('8', $order) && in_array('9', $order)) {
                            $cnt = 1;
                            unset($dashboard_order);
                            foreach ($order as $value) {
                                $dashboard_order[$cnt] = $GLOBALS['DASHBOARD_ORDER'][$value];
                                $cnt++;
                            }
                        }
                    }
                }
            }
        }

        $task_type = $GLOBALS['TYPE'];
        $this->set(compact('dashboard_order', 'task_type'));
        setcookie('DEFAULT_PAGE', 'mydashboard', COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
    }

    /**
     * @method ajax_save_dashboard_order
     * @author Orangescrum
     * @return boolean
     */
    function ajax_save_dashboard_order() {
        $this->layout = 'ajax';
        $order = (!empty($this->params['data']['order'])) ? $this->params['data']['order'] : '';
        if ($order) {
            $list = explode("&", $order);
            foreach ($list as $key => $value) {
                $sequency = $sequency . "," . substr($value, strpos($value, "=") + 1);
            }
            $sequency = trim($sequency, ",");
            $dashboard_order = SES_ID . "_" . SES_COMP . "::" . $sequency;
            setcookie('DASHBOARD_ORDER', $dashboard_order, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        }
        print 1;
        exit;
    }

    /**
     * @method to_dos
     * @author Orangescrum
     * @return json
     */
    function to_dos() {
        $this->layout = 'ajax';
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : 'all';

        $cond = '';
        if ($project_uid != 'all') {
            $cond = "Project.uniq_id = '" . $project_uid . "' AND";

            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));

            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $project_uid, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('ProjectUser.id')));

            if (count($projArr)) {
                //Updating ProjectUser table to current date-time
                $ProjectUser['id'] = $projArr['ProjectUser']['id'];
                $ProjectUser['dt_visited'] = GMT_DATETIME;
                $this->ProjectUser->save($ProjectUser);
            }
        }

        $dt_cond = " AND Easycase.due_date<'" . GMT_DATE . "'";
        $sql_od = "SELECT SQL_CALC_FOUND_ROWS Easycase.case_no,Easycase.actual_dt_created,Easycase.dt_created,Easycase.uniq_id,Easycase.project_id,Easycase.due_date,
		Easycase.title,Project.name,Project.short_name, Project.uniq_id, 'od' as todos_type FROM (SELECT * FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.legend!=3
		AND Easycase.legend!=5 AND Easycase.isactive=1 AND Easycase.type_id!=10 AND Easycase.project_id!=0 " . $dt_cond . " AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM
		project_users AS ProjectUser,projects AS Project WHERE " . $cond . " ProjectUser.user_id='" . SES_ID . "' AND ProjectUser.project_id=Project.id AND 
		Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "')  AND ((Easycase.assign_to='" . SES_ID . "') OR 
		(Easycase.assign_to=0 AND Easycase.user_id='" . SES_ID . "')) ORDER BY  Easycase.project_id DESC) AS Easycase LEFT JOIN projects AS Project
		ON (Easycase.project_id=Project.id)  ORDER BY Easycase.dt_created DESC LIMIT 0,5";
        $get_od_todos = $this->Easycase->query($sql_od);
        $tot_od = $this->Easycase->query("SELECT FOUND_ROWS() as tot_od");

        $qry_limit = 10 - count($get_od_todos);
        $dt_cond = " AND (Easycase.due_date>='" . GMT_DATE . "' OR Easycase.due_date IS NULL OR Easycase.due_date='0000-00-00' OR Easycase.due_date='1970-01-01' OR Easycase.due_date='')";
        $sql = "SELECT SQL_CALC_FOUND_ROWS Easycase.case_no,Easycase.actual_dt_created,Easycase.dt_created,Easycase.uniq_id,Easycase.project_id,Easycase.due_date,
		Easycase.title,Project.name,Project.short_name, Project.uniq_id, 'td' as todos_type FROM (SELECT * FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.legend!=3
		AND Easycase.legend!=5 AND Easycase.isactive=1 AND Easycase.type_id!=10 AND Easycase.project_id!=0 " . $dt_cond . " AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM
		project_users AS ProjectUser,projects AS Project WHERE " . $cond . " ProjectUser.user_id='" . SES_ID . "' AND ProjectUser.project_id=Project.id AND 
		Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "')  AND ((Easycase.assign_to='" . SES_ID . "') OR 
		(Easycase.assign_to=0 AND Easycase.user_id='" . SES_ID . "')) ORDER BY  Easycase.project_id DESC) AS Easycase LEFT JOIN projects AS Project
		ON (Easycase.project_id=Project.id)  ORDER BY Easycase.due_date DESC, Easycase.dt_created DESC LIMIT 0,$qry_limit";
        $gettodos = $this->Easycase->query($sql);
        $tot = $this->Easycase->query("SELECT FOUND_ROWS() as total");

        $this->set('gettodos', array_merge($get_od_todos, $gettodos));
        $this->set('project', $project_uid);
        $this->set('total', $tot[0][0]['total'] + $tot_od[0][0]['tot_od']);
    }

    /**
     * @method recent_projects
     * @author Orangescrum
     * @return json
     */
    function recent_projects() {
        $this->layout = 'ajax';
        $this->loadModel('Project');
        $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT Project.id,Project.uniq_id AS uniq_id,Project.name,
			Project.dt_created,(SELECT COUNT(Easycase.id) FROM easycases AS Easycase WHERE 
			Easycase.istype='1' AND Easycase.isactive='1' AND Projectuser.project_id=Easycase.project_id ) 
			AS total, (SELECT COUNT(Easycase.id) FROM easycases AS Easycase WHERE 
			Easycase.istype='1' AND Easycase.isactive='1' AND (Easycase.legend ='3' OR Easycase.legend ='5') AND Projectuser.project_id=Easycase.project_id ) 
			AS resolved FROM projects AS Project, project_users AS Projectuser WHERE Project.id=Projectuser.project_id 
			AND Projectuser.user_id='" . SES_ID . "' AND Projectuser.company_id='" . SES_COMP . "' AND Project.isactive='1' 
			ORDER BY Projectuser.dt_visited DESC LIMIT 0,10";

        $recent_projects = $this->Project->query($sql);
        $tot = $this->Project->query("SELECT FOUND_ROWS() as total");
        $this->set('recent_projects', $recent_projects);
        $this->set('total', $tot[0][0]['total']);
    }

    /**
     * @method recent_activities
     * @author Orangescrum
     * @return json
     */
    function recent_activities() {
        $this->layout = 'ajax';
        $this->loadModel('Easycase');
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : 'all';
        $cond = '';
        if ($project_uid != 'all') {
            $cond = "AND Project.uniq_id = '" . $project_uid . "'";
        }

        /* $sql = "SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.name,User.short_name,User.photo,Project.id,Project.uniq_id,Project.name
          FROM easycases AS Easycase INNER JOIN users AS User ON (Easycase.user_id = User.id) INNER JOIN
          projects AS Project ON (Easycase.project_id = Project.id) inner JOIN project_users AS ProjectUser ON
          (Easycase.project_id = ProjectUser.project_id AND ProjectUser.user_id = '".SES_ID."' AND ProjectUser.company_id = '".SES_COMP."')
          WHERE Project.isactive='1' AND Easycase.isactive='1' ".$cond." AND
          Easycase.id = (SELECT MAX(id) FROM easycases WHERE case_no = Easycase.case_no GROUP BY case_no)
          GROUP BY Easycase.case_no ORDER BY Easycase.actual_dt_created DESC LIMIT 0,10"; */
        $sql = "SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.name,User.short_name,User.photo,Project.id,Project.uniq_id,Project.name
			FROM easycases AS Easycase INNER JOIN users AS User ON (Easycase.user_id = User.id) INNER JOIN 
			projects AS Project ON (Easycase.project_id = Project.id) inner JOIN project_users AS ProjectUser ON 
			(Easycase.project_id = ProjectUser.project_id AND ProjectUser.user_id = '" . SES_ID . "' AND ProjectUser.company_id = '" . SES_COMP . "') 
			WHERE Project.isactive='1' AND Easycase.isactive='1' " . $cond . " ORDER BY Easycase.actual_dt_created DESC LIMIT 0,10";

        $recent_activities = $this->Easycase->query($sql);
        $tot = $this->Easycase->query("SELECT FOUND_ROWS() as total");
        $total = $tot[0][0]['total'];
        if ($total != 0) {
            $view = new View($this);
            $fmt = $view->loadHelper('Format');
            $dt = $view->loadHelper('Datetime');
            $tz = $view->loadHelper('Tmzone');
            $csq = $view->loadHelper('Casequery');
            $this->loadModel('User');
            $frmtActivity = $this->User->formatActivities($recent_activities, $total, $fmt, $dt, $tz, $csq);
        }

        $this->set('recent_activities', $frmtActivity['activity']);
        $this->set('project', $project_uid);
        $this->set('total', $total);
    }

    /**
     * @method recent_milestones
     * @author Orangescrum
     * @return json
     */
    function recent_milestones() {
        $this->layout = 'ajax';
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : 'all';
        if ($project_uid != 'all') {
            $this->loadModel("Project");
            $project = $this->Project->getProjectFields(array("Project.uniq_id" => $project_uid), array("Project.id"));
            $allpj = $projectId = $project['Project']['id'];
        } else {
            $allpj = "all";
        }

        $this->loadModel('Milestone');
        $sql = "SELECT SQL_CALC_FOUND_ROWS Milestone.*,Project.name,Project.uniq_id,COUNT(c.easycase_id) AS totalcases,
			GROUP_CONCAT(c.easycase_id) AS `caseids`,GROUP_CONCAT(e.legend) AS `legend` FROM milestones AS `Milestone` LEFT JOIN
			easycase_milestones AS c ON Milestone.id = c.milestone_id LEFT JOIN easycases AS e ON (c.easycase_id = e.id) LEFT JOIN
			projects AS Project ON (Project.id=Milestone.project_id) WHERE Milestone.isactive='1' AND `Milestone`.`company_id` = " . SES_COMP;

        if ($allpj != "all") {
            $sql .= " AND `Milestone`.`project_id` =" . $projectId . " AND `Milestone`.`company_id` = " . SES_COMP . "  GROUP BY Milestone.id ORDER BY `Milestone`.`modified` DESC LIMIT 0,10";
        } else {
            $allcond = array('conditions' => array('ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP, 'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'), 'order' => array('ProjectUser.dt_visited DESC'));
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $allProjArr = $this->ProjectUser->find('all', $allcond);
            $ids = array();
            foreach ($allProjArr as $csid) {
                array_push($ids, $csid['Project']['id']);
            }
            $all_ids = implode(',', $ids);
            $sql .= " AND `Milestone`.`project_id` IN (" . $all_ids . ") AND `Milestone`.`company_id` = " . SES_COMP . " GROUP BY Milestone.id ORDER BY `Milestone`.`modified` DESC LIMIT 0,10";
        }

        $recent_milestones = $this->Milestone->query($sql);

        //Finding number of resolved case.
        foreach ($recent_milestones as $key => $milestone) {
            if ($milestone['0']['legend']) {
                $legends = explode(",", $milestone['0']['legend']);
                if (in_array(3, $legends) || in_array(5, $legends)) {
                    $cnt = 0;
                    foreach ($legends as $value) {
                        if ($value == 3 || $value == 5) {
                            $cnt = $cnt + 1;
                        }
                    }
                    $recent_milestones[$key]['0']['resolved'] = $cnt;
                } else {
                    $recent_milestones[$key]['0']['resolved'] = 0;
                }
            } else {
                $recent_milestones[$key]['0']['resolved'] = 0;
            }
        }
        $this->set('recent_milestones', $recent_milestones);
        $this->set('project', $project_uid);
    }

    /**
     * @method statistics
     * @author Orangescrum
     * @return json
     */
    function statistics() {
        $this->layout = 'ajax';
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : '';
        $this->loadModel("Easycase");
        $cond = '';
        if ($project_uid != 'all') {
            $cond = " AND Project.uniq_id='" . $project_uid . "'";
            $task_have_no_hours = "SELECT DISTINCT COUNT(total.id) AS task_have_no_hours FROM (SELECT Easycase.* FROM easycases AS Easycase LEFT JOIN projects AS Project ON (Project.id = Easycase.project_id) WHERE Project.company_id='" . SES_COMP . "' AND Project.uniq_id='" . $project_uid . "' AND Easycase.isactive=1 AND Easycase.hours =0.0 GROUP BY Easycase.case_no) AS total GROUP BY total.project_id";
        } else {
            $task_have_no_hours = "SELECT SUM(total.no_hours) AS task_have_no_hours FROM (SELECT DISTINCT COUNT(total1.id) AS no_hours FROM (SELECT Easycase.* FROM easycases AS Easycase LEFT JOIN projects AS Project ON (Project.id = Easycase.project_id) WHERE Project.company_id='" . SES_COMP . "' AND Easycase.isactive=1 AND Easycase.hours =0.0 GROUP BY Easycase.case_no) AS total1 GROUP BY total1.project_id) AS total";
        }

        //$statistics = array();

        $id = $this->Auth->user('id');
        $this->loadModel('ProjectUser');
        $rec = $this->ProjectUser->findByUserId($id);
        if (!empty($rec)) {
            $task_type = $GLOBALS['TYPE'];
            $type_id = (isset($GLOBALS['TYPE'][0]['Type']['id']) && trim($GLOBALS['TYPE'][0]['Type']['id'])) ? $GLOBALS['TYPE'][0]['Type']['id'] : $GLOBALS['TYPE'][1]['Type']['id'];
            $task_type_id = (isset($_COOKIE['TASK_TYPE_IN_DASHBOARD']) && trim($_COOKIE['TASK_TYPE_IN_DASHBOARD'])) ? $_COOKIE['TASK_TYPE_IN_DASHBOARD'] : $type_id;

            $task_without_due_date = "SELECT  DISTINCT COUNT(Easycase.id) AS task_without_due_date FROM easycases AS Easycase LEFT JOIN projects AS Project
			ON (Project.id = Easycase.project_id) WHERE Project.company_id='" . SES_COMP . "' $cond AND Easycase.isactive=1 AND Easycase.istype=1 AND Easycase.due_date IS NULL";

            $hours_spent = "SELECT SUM(hours) AS hours_spent FROM easycases AS Easycase LEFT JOIN projects AS Project ON (Project.id = Easycase.project_id) WHERE Project.company_id='" . SES_COMP . "' $cond AND Easycase.isactive=1 AND Easycase.reply_type=0";

            $task_hours = "SELECT SUM(hours) as task_hours FROM easycases AS Easycase LEFT JOIN projects AS Project ON (Project.id = Easycase.project_id) WHERE Project.company_id='" . SES_COMP . "' $cond AND Easycase.isactive=1 AND Easycase.reply_type=0 AND type_id = '" . $task_type_id . "'";

            //$sql = "SELECT * FROM ($task_without_due_date) AS task_without_due_date,($task_have_no_hours) AS task_have_no_hours,($hours_spent) AS hours_spent,($bug_hours) AS bug_hours";
            $sql = "SELECT * FROM ($task_without_due_date) AS task_without_due_date,($hours_spent) AS hours_spent,($task_hours) AS task_hours";

            $statistics = $this->Easycase->query($sql);
            $task_type_name = "";
            if (isset($task_type) && !empty($task_type)) {
                foreach ($task_type as $key => $value) {
                    if ($task_type_id == $value['Type']['id']) {
                        $task_type_name = strtolower($value['Type']['name']);
                    }
                }
            }
            $this->set(compact('statistics', 'task_type_name'));
        }
    }

    /**
     * @method usage_details
     * @author Orangescrum
     * @return json
     */
    function usage_details() {
        $this->layout = 'ajax';
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : '';
        $this->loadModel("Project");

        $filecond = '';
        $usercond = '';
        $projectcond = '';
        if ($project_uid != 'all') {
            $project = $this->Project->getProjectFields(array("Project.uniq_id" => $project_uid), array("Project.id"));
            $projectId = $project['Project']['id'];
            $filecond = " AND CaseFile.project_id='" . $projectId . "'";
            $usercond = " AND ProjectUser.project_id='" . $projectId . "'";
        } else {
            $projectcond = ",(SELECT DISTINCT COUNT(Project.id) AS cnt_projects FROM projects AS Project, project_users AS ProjectUser WHERE Project.id=ProjectUser.project_id AND ProjectUser.user_id='" . SES_ID . "'
			AND  ProjectUser.company_id='" . SES_COMP . "' AND Project.isactive='1') AS total_projects";
        }

        /* $sql = "SELECT * FROM (SELECT ROUND((SUM(CaseFile.file_size)/1024),2) AS filesize FROM case_files AS CaseFile WHERE CaseFile.company_id ='".SES_COMP."' $filecond) AS total_filesize,
          (SELECT  DISTINCT COUNT(CompanyUser.id) AS cnt_users FROM company_users AS CompanyUser, project_users AS ProjectUser WHERE CompanyUser.is_active=1 AND
          CompanyUser.company_id='".SES_COMP."' $usercond AND ProjectUser.company_id=CompanyUser.company_id AND ProjectUser.user_id = CompanyUser.user_id) AS total_users $projectcond"; */

        $sql = "SELECT * FROM (SELECT ROUND((SUM(CaseFile.file_size)/1024),2) AS filesize FROM case_files AS CaseFile WHERE CaseFile.company_id ='" . SES_COMP . "' $filecond) AS total_filesize,
		(SELECT COUNT(DISTINCT(CompanyUser.user_id)) as cnt_users  FROM company_users AS CompanyUser LEFT JOIN project_users as ProjectUser ON ProjectUser.user_id = CompanyUser.user_id WHERE CompanyUser.is_active=1 AND
CompanyUser.company_id=" . SES_COMP . " $usercond) AS total_users $projectcond";

        $usage_details = $this->Project->query($sql);
        $this->set('usage_details', $usage_details);
    }

    /**
     * @method task_progress
     * @author MAV
     * @return json
     */
    function task_progress() {
        $this->layout = 'ajax';

        $projQry = "AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1')";

        $query_All = 0;
        $query_Close = 0;
        $query_Resolve = 0;
        $stsMsg = '';
        $stsMsgTtl = '';
        $taskProg = "";

        $query_All1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.type_id!='10' AND  Easycase.isactive='1' AND Easycase.project_id!=0 " . $projQry);
        $query_All = $query_All1['0']['0']['count'];



        $query_Close1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND Easycase.type_id!='10' AND Easycase.project_id!=0 " . $projQry);
        $query_Close = $query_Close1['0']['0']['count'];

        $query_Resolve1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='5' AND Easycase.type_id!='10' AND Easycase.project_id!=0 " . $projQry);
        $query_Resolve = $query_Resolve1['0']['0']['count'];

        //echo $query_Resolve.' / '.$query_Close.' / '.$query_All."<br />";
        //$query_All=0; $query_Close=0; $query_Resolve=0;

        $resolvedRate = '0%';
        $resRate = $newWipRate = 0;

        if ($query_All) {
            $resRate = (float) number_format(($query_Close + $query_Resolve) / $query_All * 100, 2);
            $newWipRate = 100 - $resRate;

            if (!$resRate || $resRate != 0.00) {
                $resolvedRate = $resRate . '%';
                $stsMsg = $resolvedRate;
                $stsMsgTtl = $resolvedRate . ' (' . ($query_Close + $query_Resolve) . ' of ' . $query_All . ' Tasks Resolved)';
            }

            if (!$newWipRate || $newWipRate == 0.00) {
                $taskProg = array(
                    array('name' => 'Resolved', 'color' => '#9FBD4B', 'y' => $resRate),
                );
            } elseif (!$resRate || $resRate == 0.00) {
                $taskProg = array(
                    array('name' => 'New & In Progress', 'color' => '#E1857A', 'y' => $newWipRate),
                );
            } else {
                $taskProg = array(
                    array('name' => 'Resolved', 'color' => '#9FBD4B', 'y' => $resRate),
                    array('name' => 'New & In Progress', 'color' => '#E1857A', 'y' => $newWipRate),
                );
            }
        }

        $this->set('progress_report', json_encode(array('sts_msg' => $stsMsg, 'sts_msg_ttl' => $stsMsgTtl, 'task_prog' => $taskProg)));
    }

    /**
     * 
     * @method task_type
     * @author SNL
     * @return json
     */
    function task_type() {
        $this->layout = 'ajax';
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : 'all';
        $task_type_id = (isset($this->params['data']['task_type_id']) && trim($this->params['data']['task_type_id'])) ? $this->params['data']['task_type_id'] : 0;
        $cond = '';
        if ($project_uid != 'all') {
            $cond = "Project.uniq_id = '" . $project_uid . "' AND";
        }
        $projQry = "AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE " . $cond . " ProjectUser.user_id=" . SES_ID . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1')";

        $query_All = 0;
        $query_Close = 0;
        $query_Resolve = 0;
        $stsMsg = '';
        $stsMsgTtl = '';
        $taskProg = "";

        $query_All1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.type_id='" . $task_type_id . "' AND  Easycase.isactive='1' AND Easycase.project_id!=0 " . $projQry);
        $query_All = $query_All1['0']['0']['count'];

        $query_Close1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND Easycase.type_id='" . $task_type_id . "' AND Easycase.project_id!=0 " . $projQry);
        $query_Close = $query_Close1['0']['0']['count'];

        $query_Resolve1 = $this->Easycase->query("SELECT COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='5' AND Easycase.type_id='" . $task_type_id . "' AND Easycase.project_id!=0 " . $projQry);
        $query_Resolve = $query_Resolve1['0']['0']['count'];

        //echo $query_Resolve.' / '.$query_Close.' / '.$query_All."<br />";
        //$query_All=0; $query_Close=0; $query_Resolve=0;

        $resolvedRate = '0%';
        $resRate = $newWipRate = 0;

        if ($query_All) {
            $resRate = (float) number_format(($query_Close + $query_Resolve) / $query_All * 100, 2);
            $newWipRate = 100 - $resRate;

            if (!$resRate || $resRate != 0.00) {
                $resolvedRate = $resRate . '%';
                $stsMsg = ' - ' . $resolvedRate . ' Completed';
                $stsMsgTtl = $resolvedRate . ' (' . ($query_Close + $query_Resolve) . ' of ' . $query_All . ' Completed)';
            }

            if (!$newWipRate || $newWipRate == 0.00) {
                $taskProg = array(
                    array('name' => 'Completed', 'color' => '#9FBD4B', 'y' => $resRate),
                );
            } elseif (!$resRate || $resRate == 0.00) {
                $taskProg = array(
                    array('name' => 'New & In Progress', 'color' => '#E1857A', 'y' => $newWipRate),
                );
            } else {
                $taskProg = array(
                    array('name' => 'Completed', 'color' => '#9FBD4B', 'y' => $resRate),
                    array('name' => 'New & In Progress', 'color' => '#E1857A', 'y' => $newWipRate),
                );
            }
        }

        $this->set('task_report', json_encode(array('sts_msg' => $stsMsg, 'sts_msg_ttl' => $stsMsgTtl, 'task_prog' => $taskProg)));
    }

    /**
     * @method task_status
     * @author MAV
     * @return json
     */
    function task_status() {
        $this->layout = 'ajax';
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : 'all';
        $cond = '';
        if ($project_uid != 'all') {
            $cond = "Project.uniq_id = '" . $project_uid . "' AND";
        }
        $projQry = "AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE " . $cond . " ProjectUser.user_id=" . SES_ID . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1')";

        $stsMsg = '';
        $stsMsgTtl = '';
        $stsArr = array(1 => 'New', 2 => 'In Progress', 3 => 'Closed', 4 => 'In Progress', 5 => 'Resolved');
        $stsColorArr = array('New' => '#AE432E', 'In Progress' => '#244F7A', 'Closed' => '#77AB13', 'Resolved' => '#EF6807');

        $query_All1 = $this->Easycase->query("SELECT legend,COUNT(Easycase.id) as count FROM easycases as Easycase WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.type_id!='10' AND Easycase.project_id!=0 " . $projQry . ' GROUP BY legend ORDER BY FIELD(legend,1,6,2,4,5,3)');

        $stsCalc = array();
        foreach ($query_All1 as $k => $v) {
            $stsCalc[$stsArr[$v['Easycase']['legend']]] += $v[0]['count'];
        }

        $statusRate;
        if (array_sum($stsCalc)) {
            foreach ($stsCalc as $k => $sts) {
                $statusRate[] = array(
                    'name' => $k,
                    'color' => $stsColorArr[$k],
                    'y' => (float) number_format(($sts / array_sum($stsCalc)) * 100, 2)
                );
            }
        }
        $this->set('status_report', json_encode(array('sts_msg' => $stsMsg, 'sts_msg_ttl' => $stsMsgTtl, 'task_prog' => $statusRate)));
    }

    function calendarView() {
        $this->layout = 'ajax';
    }

    function getTaskList() {
        //$this->layout = 'ajax';
        $calendarTaskList = array();
        $page_limit = 10;
        $this->_datestime();

        $projUniq = $this->data['projFil']; // Project Uniq ID
        $projIsChange = $this->data['projIsChange']; // Project Uniq ID

        $caseStatus = $this->data['caseStatus']; // Filter by Status(legend)
        $priorityFil = $this->data['priFil']; // Filter by Priority
        $caseTypes = $this->data['caseTypes']; // Filter by case Types
        $caseUserId = $this->data['caseMember']; // Filter by Member
        $caseAssignTo = $this->data['caseAssignTo']; // Filter by AssignTo
        $caseDate = $this->data['caseDate']; // Sort by Date
        $caseSrch = $this->data['caseSearch']; // Search by keyword
        $casePage = $this->data['casePage']; // Pagination
        $caseUniqId = $this->data['caseId']; // Case Uniq ID to close a case
        $caseTitle = $this->data['caseTitle']; // Case Uniq ID to close a case
        $caseDueDate = $this->data['caseDueDate']; // Sort by Due Date

        $caseNum = $this->data['caseNum']; // Sort by Due Date
        $caseLegendsort = $this->data['caseLegendsort']; // Sort by Case Status
        $caseAtsort = $this->data['caseAtsort']; // Sort by Case Status
        $startCaseId = $this->data['startCaseId']; // Start Case
        $caseResolve = $this->data['caseResolve']; // Resolve Case

        $caseMenuFilters = $this->data['caseMenuFilters']; // Resolve Case
        $milestoneIds = $this->data['milestoneIds']; // Resolve Case
        $milestoneUid = $this->data['milestoneUid'];
        $caseCreateDate = $this->data['caseCreateDate']; // Sort by Created Date
        @$case_srch = $this->data['case_srch'];
        @$case_date = $this->data['case_date'];
        @$case_duedate = $this->data['case_due_date'];
        @$milestone_type = $this->data['mstype'];
        $changecasetype = $this->data['caseChangeType'];
        $caseChangeDuedate = $this->data['caseChangeDuedate'];
        $caseChangePriority = $this->data['caseChangePriority'];
        $caseChangeAssignto = $this->data['caseChangeAssignto'];
        $customfilterid = $this->data['customfilter'];
        $detailscount = $this->data['data']['detailscount']; // Count number to open casedetails
        $morecontent = $this->data['morecontent'];
        if ($customfilterid) {
            $this->loadModel('CustomFilter');
            //$getcustomfilter = "SELECT  * FROM custom_filters AS CustomFilter WHERE CustomFilter.company_id = '".SES_COMP."' and CustomFilter.user_id =  '".SES_ID."' and CustomFilter.id=".$customfilterid." ORDER BY CustomFilter.dt_created DESC ";
            $getfilter = $this->CustomFilter->find('first', array('conditions' => array('CustomFilter.company_id' => SES_COMP, 'CustomFilter.user_id' => SES_ID, 'CustomFilter.id' => $customfilterid), 'order' => 'CustomFilter.dt_created DESC'));
            $caseStatus = $getfilter['CustomFilter']['filter_status'];
            $priorityFil = $getfilter['CustomFilter']['filter_priority'];
            $caseTypes = $getfilter['CustomFilter']['filter_type_id'];
            $caseUserId = $getfilter['CustomFilter']['filter_member_id'];
            $caseAssignTo = $getfilter['CustomFilter']['filter_assignto'];
            $caseDate = $getfilter['CustomFilter']['filter_date'];
            $case_duedate = $getfilter['CustomFilter']['filter_duedate'];
            $caseSrch = $getfilter['CustomFilter']['filter_search'];
        }
        if ($caseMenuFilters) {
            setcookie('CURRENT_FILTER', $caseMenuFilters, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        } else {
            setcookie('CURRENT_FILTER', $caseMenuFilters, COOKIE_REM, '/', DOMAIN_COOKIE, false, false);
        }
        $caseUrl = $this->data['caseUrl'];
        $curProjId = NULL;
        $curProjShortName = NULL;
        if ($projUniq != 'all') {
            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.uniq_id' => $projUniq, 'ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('Project.id', 'Project.short_name', 'ProjectUser.id')));
            if (count($projArr)) {
                $curProjId = $projArr['Project']['id'];
                $curProjShortName = $projArr['Project']['short_name'];

                //Updating ProjectUser table to current date-time
                if ($projIsChange != $projUniq) {
                    $ProjectUser['id'] = $projArr['ProjectUser']['id'];
                    $ProjectUser['dt_visited'] = GMT_DATETIME;
                    $this->ProjectUser->save($ProjectUser);
                }
            }
        }
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $dt = $view->loadHelper('Datetime');
        $cq = $view->loadHelper('Casequery');
        $frmt = $view->loadHelper('Format');
        ######### Filete with Milestone ##########
        /*
          if($milestoneUid ){
          $mlst_cls = ClassRegistry::init('Milestone');
          //$mlist = $mlst_cls->find('first',array('conditions'=>array('Milestone.uniq_id'=>$milestoneUid),'fields'=>'Milestone.id,Milestone.title'));
          $mls = $mlst_cls->query("SELECT `Milestone`.`id`,`Milestone`.`title`,`Milestone`.`project_id`,`Milestone`.`end_date`,`Milestone`.`uniq_id`,`Milestone`.`isactive`,`Milestone`.`user_id`,COUNT(c.easycase_id) AS totalcases,GROUP_CONCAT(c.easycase_id) AS `caseids`  FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id WHERE `Milestone`.`uniq_id` ='".$milestoneUid."'  AND `Milestone`.`company_id` = ".SES_COMP);
          //echo "<pre>";print_r($mls);exit;
          $resCaseProj['mlstTitle'] = $mls[0]['Milestone']['title'];
          $resCaseProj['mlstId'] = $mls[0]['Milestone']['id'];
          $resCaseProj['mlstUid'] = $milestoneUid;
          $resCaseProj['mlstProjId'] = $mls[0]['Milestone']['project_id'];
          $resCaseProj['mlsttotalCs'] = $mls[0][0]['totalcases'];
          $resCaseProj['mlsttype'] = $mls[0]['Milestone']['isactive'];

          $curCreated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
          $curTime = date('H:i:s',strtotime($curCreated));

          $closed_cases = $mlst_cls->query("SELECT EasycaseMilestone.milestone_id,COUNT(Easycase.id) as totcase FROM easycase_milestones AS EasycaseMilestone LEFT JOIN easycases as Easycase ON   EasycaseMilestone.easycase_id=Easycase.id WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND EasycaseMilestone.milestone_id='".$mls[0]['Milestone']['id']."'  GROUP BY  EasycaseMilestone.milestone_id");
          $tot_closed_case = $closed_cases[0][0]['totcase'];

          $endDate = $mls[0]['Milestone']['end_date']." ".$curTime;
          $days = $dt->dateDiff($endDate,$curCreated);

          $mlstDT = $dt->dateFormatOutputdateTime_day($mls[0]['Milestone']['end_date'],GMT_DATETIME,'week');

          $totalCs = $mls[0][0]['totalcases'];
          $totalClosedCs = 0;
          if($tot_closed_case){
          $totalClosedCs = $tot_closed_case;
          }
          $fill = 0;
          if($totalClosedCs != 0) {
          $fill = round((($totalClosedCs/$totalCs)*100));
          }

          $resCaseProj['intEndDate'] = strtotime($endDate);
          $resCaseProj['mlstdays_diff'] = $days;
          $resCaseProj['mlstDT'] = $mlstDT;
          $resCaseProj['mlstFill'] = $fill;
          $resCaseProj['totalClosedCs'] = $totalClosedCs;
          $resCaseProj['totalCs'] = $totalCs;

          $qry .=' AND EasycaseMilestone.milestone_id='.$mls[0]['Milestone']['id'];
          }else{
         */
        $resCaseProj['mlstTitle'] = '';
        $resCaseProj['mlstId'] = '';
        ######### Filter by CaseUniqId ##########
        $qry = "";
        if (trim($caseUrl)) {
            $qry .= " AND Easycase.uniq_id='" . $caseUrl . "'";
        }
        ######### Filter by Status ##########
        if ($caseStatus != "all") {
            $qry .= $this->Format->statusFilter($caseStatus);
            $stsLegArr = $caseStatus . "-" . "";
            $expStsLeg = explode("-", $stsLegArr);
            if (!in_array("upd", $expStsLeg)) {
                $qry .= " AND Easycase.type_id !=10";
            }
        }
        ######### Filter by Case Types ##########
        if ($caseTypes && $caseTypes != "all") {
            $qry .= $this->Format->typeFilter($caseTypes);
        }
        ######### Filter by Priority ##########
        if ($priorityFil && $priorityFil != "all") {
            $qry .= $this->Format->priorityFilter($priorityFil, $caseTypes);
        }
        ######### Filter by Member ##########
        if ($caseUserId && $caseUserId != "all") {
            $qry .= $this->Format->memberFilter($caseUserId);
        }
        ######### Filter by AssignTo ##########
        if ($caseAssignTo && $caseAssignTo != "all") {
            $qry .= $this->Format->assigntoFilter($caseAssignTo);
        }

        ######### Search by KeyWord ##########
        $searchcase = "";
        if (trim(urldecode($caseSrch)) && (trim($case_srch) == "")) {
            $searchcase = $this->Format->caseKeywordSearch($caseSrch, 'full');
        }
        if (trim(urldecode($case_srch)) != "") {
            $searchcase = "AND (Easycase.case_no = '$case_srch')";
        }

        if (trim(urldecode($caseSrch))) {
            if ((substr($caseSrch, 0, 1)) == '#') {
                $tmp = explode("#", $caseSrch);
                $casno = trim($tmp['1']);
                $searchcase = " AND (Easycase.case_no = '" . $casno . "')";
            }
        }
        $cond_easycase_actuve = "";
        if ((isset($case_srch) && !empty($case_srch)) || isset($caseSrch) && !empty($caseSrch)) {
            $cond_easycase_actuve = "";
        } else {
            $cond_easycase_actuve = "AND Easycase.isactive=1";
        }
        if (trim($case_date) != "") {
            if (trim($case_date) == 'one') {
                $one_date = date('Y-m-d H:i:s', time() - 3600);
                $qry .= " AND Easycase.dt_created >='" . $one_date . "'";
            } else if (trim($case_date) == '24') {
                $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 day"));
                $qry .= " AND Easycase.dt_created >='" . $day_date . "'";
            } else if (trim($case_date) == 'week') {
                $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 week"));
                $qry .= " AND Easycase.dt_created >='" . $week_date . "'";
            } else if (trim($case_date) == 'month') {
                $month_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 month"));
                $qry .= " AND Easycase.dt_created >='" . $month_date . "'";
            } else if (trim($case_date) == 'year') {
                $year_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 year"));
                $qry .= " AND Easycase.dt_created >='" . $year_date . "'";
            } else if (strstr(trim($case_date), ":")) {
                $ar_dt = explode(":", trim($case_date));
                $frm_dt = $ar_dt['0'];
                $to_dt = $ar_dt['1'];
                $qry .= " AND DATE(Easycase.dt_created) >= '" . date('Y-m-d H:i:s', strtotime($frm_dt)) . "' AND DATE(Easycase.dt_created) <= '" . date('Y-m-d H:i:s', strtotime($to_dt)) . "'";
            }
        }
        if (trim($case_duedate) != "") {
            if (trim($case_duedate) == '24') {
                $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 day"));
                $qry .= " AND (DATE(Easycase.due_date) ='" . GMT_DATE . "')";
            } else if (trim($case_duedate) == 'overdue') {
                $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 week"));
                $qry .= " AND ( DATE(Easycase.due_date) <'" . GMT_DATE . "') ";
            } else if (strstr(trim($case_duedate), ":")) {
                $ar_dt = explode(":", trim($case_duedate));
                $frm_dt = $ar_dt['0'];
                $to_dt = $ar_dt['1'];
                $qry .= " AND DATE(Easycase.due_date) >= '" . date('Y-m-d', strtotime($frm_dt)) . "' AND DATE(Easycase.due_date) <= '" . date('Y-m-d', strtotime($to_dt)) . "'";
            }
        }
        //}
        $msQuery1 = " ";
        /* $resCaseProj['page_limit'] = $page_limit;
          $resCaseProj['csPage'] = $casePage;
          $resCaseProj['caseUrl'] = $caseUrl;
          $resCaseProj['projUniq'] = $projUniq;
          $resCaseProj['csdt'] = $caseDate;
          $resCaseProj['csTtl'] = $caseTitle;
          $resCaseProj['csDuDt'] = $caseDueDate;
          $resCaseProj['csCrtdDt'] = $caseCreateDate;
          $resCaseProj['csNum'] = $caseNum;
          $resCaseProj['csLgndSrt'] = $caseLegendsort;
          $resCaseProj['csAtSrt'] = $caseAtsort;
          $resCaseProj['caseMenuFilters'] = $caseMenuFilters;
          $resCaseProj['morecontent'] = $morecontent; */

        $from_input_yr = $this->data['from_view_year'];
        $from_input_mth = $this->data['from_view_month'];
        $to_input_yr = $this->data['to_view_year'];
        $to_input_mth = $this->data['to_view_month'];
        $yr_mnth_arr = array('12', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11');
        $no_of_days_in_a_month = cal_days_in_month(CAL_GREGORIAN, $yr_mnth_arr[$to_input_mth], $to_input_yr);
        $no_of_days_in_a_month = $no_of_days_in_a_month - 1;
        if ($to_input_mth == 0) {
            
        } else {
            $from_input_yr = $to_input_yr;
        }
        $from_view_date = $from_input_yr . '-' . $yr_mnth_arr[$to_input_mth] . '-01';
        //print $from_view_date;
        $to_view_date = date('Y-m-d', strtotime($from_view_date . '+ ' . $no_of_days_in_a_month . ' days'));
        $proj_detl = '';
        if ($projUniq) {
            //$this->Easycase->query('SET CHARACTER SET utf8');
            $page = $casePage;
            $limit2 = $page_limit;
            if ($projUniq == 'all') {
                $caseAll['Task'] = $this->Easycase->query("SELECT Easycase.id,Easycase.case_no,Easycase.legend,Easycase.uniq_id,Easycase.project_id,Easycase.title, Easycase.due_date as start,User.short_name,User.name,User.last_name,User.photo,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE Easycase.istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') " . $searchcase . " " . trim($qry) . "  ORDER BY  Easycase.due_date DESC) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE (Easycase.due_date != 'NULL' AND Easycase.due_date != '') AND Easycase.due_date BETWEEN '" . $from_view_date . "' AND '" . $to_view_date . "' ORDER BY Easycase.due_date DESC");
            } else {
                $caseAll['Task'] = $this->Easycase->query("SELECT Easycase.id,Easycase.case_no,Easycase.legend,Easycase.uniq_id,Easycase.project_id,Easycase.title, Easycase.due_date as start,User.short_name,User.name,User.last_name,User.photo,IF((Easycase.assign_to = 0 OR Easycase.assign_to =" . SES_ID . "),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.* FROM easycases as Easycase LEFT JOIN easycase_milestones AS EasycaseMilestone ON Easycase.id= EasycaseMilestone.easycase_id WHERE istype='1' " . $cond_easycase_actuve . " AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  " . $searchcase . " " . trim($qry) . " ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id WHERE  Easycase.due_date BETWEEN '" . $from_view_date . "' AND '" . $to_view_date . "' ORDER BY due_date DESC");
            }
            //exit;
            $msQ = "";
            $ProjectUser = ClassRegistry::init('ProjectUser');
            if ($projUniq != 'all') {
                $usrDtlsAll = $ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo,Easycase.project_id FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id='" . $curProjId . "' AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
            } else {
                $usrDtlsAll = $ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo,Easycase.project_id FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . SES_ID . " AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='" . SES_COMP . "') AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
            }
            if ($usrDtlsAll) {
                $p_ids = array_unique(Hash::extract($usrDtlsAll, '{n}.Easycase.project_id'));
            }
            if ($p_ids) {
                $Project = ClassRegistry::init('Project');
                $Project->recursive = -1;
                $proj_detl = $Project->find('all', array('conditions' => array('Project.id' => $p_ids), 'fields' => array('Project.id', 'Project.uniq_id', 'Project.name', 'Project.short_name')));
                $proj_detl = Hash::combine($proj_detl, '{n}.Project.id', '{n}.Project');
            }
            $usrDtlsArr = array();
            $usrDtlsPrj = array();
            foreach ($usrDtlsAll as $ud) {
                $usrDtlsArr[$ud['User']['id']] = $ud;
            }
        } else {
            $CaseCount = 0;
        }
        //pr($caseAll);pr($proj_detl);exit;
        //$resCaseProj['caseCount'] = $CaseCount;
        $calendarArr = array();
        foreach ($caseAll['Task'] as $k => $v) {
            $ttl = $v['Easycase']['title'];
            if ($ttl && strlen($ttl) > 40) {
                $ttl = substr($ttl, 0, 37) . '...';
            }
            $calendarArr[$k]['title'] = $ttl;
            $calendarArr[$k]['original_title'] = $v['Easycase']['title'];
            $calendarArr[$k]['start'] = $v['Easycase']['start'];
            $calendarArr[$k]['srt_name'] = $v['User']['short_name'];
            $calendarArr[$k]['name'] = $v['User']['name'] . ' ' . $v['User']['last_name'];
            $calendarArr[$k]['photo'] = $v['User']['photo'];
            $calendarArr[$k]['assigned'] = $v[0]['Assigned'];
            $calendarArr[$k]['caseUniqId'] = $v['Easycase']['uniq_id'];
            $calendarArr[$k]['case_no'] = $v['Easycase']['case_no'];
            $calendarArr[$k]['caseId'] = $v['Easycase']['id'];
            $calendarArr[$k]['legend'] = $v['Easycase']['legend'];
            $calendarArr[$k]['projectName'] = $proj_detl[$v['Easycase']['project_id']]['name'];
            $calendarArr[$k]['projectSortName'] = strtoupper($proj_detl[$v['Easycase']['project_id']]['short_name']);
            $calendarArr[$k]['ProjectUniqId'] = $proj_detl[$v['Easycase']['project_id']]['uniq_id'];
        }
        echo json_encode($calendarArr);
        exit;
    }

    function updateDueDate() {
        $retJson = array('status' => 'success');
        if ($this->data['uniq_id']) {
            $Easycase = $this->Easycase->find('first', array('conditions' => array('Easycase.uniq_id' => trim($this->data['uniq_id'])), 'fields' => array('Easycase.id')));
            if ($Easycase) {
                $this->Easycase->id = $Easycase['Easycase']['id'];
                $this->Easycase->saveField('due_date', $this->data['date']);
            } else {
                $retJson['status'] = 'FAIL';
            }
        } else {
            $retJson['status'] = 'FAIL';
        }
        echo json_encode($retJson);
        exit;
    }

    /**
     * @method public taskDownload() Create downloadable folder which will contain a .csv File and a Folder containg all the Attachment
     * @return string Returns the downloadable URL
     * @author GDR<support@Orangescrum.com>
     */
    function taskDownload() {

        if (!is_dir(DOWNLOAD_TASK_PATH)) {
            mkdir(DOWNLOAD_TASK_PATH, 0777, true);
        }
        if (!is_dir(DOWNLOAD_TASK_PATH . "zipTask")) {
            mkdir(DOWNLOAD_TASK_PATH . "zipTask", 0777, true);
        }

        $caseUniqId = $this->data['caseUid'];
        //$caseUniqId = '8d082f712782302aafe8a62129f7cc24';
        $this->layout = 'ajax';
        $sorting = '';
        $ProjId = NULL;
        $ProjName = NULL;
        $curCaseNo = NULL;
        $curCaseId = NULL;
        ######## get case number from case uniq ID ################
        $getCaseNoPjId = $this->Easycase->getEasycase($caseUniqId);
        if ($getCaseNoPjId) {
            $curCaseNo = $getCaseNoPjId['Easycase']['case_no'];
            $curCaseId = $getCaseNoPjId['Easycase']['id'];
            $prjid = $getCaseNoPjId['Easycase']['project_id'];
            $is_active = (intval($getCaseNoPjId['Easycase']['isactive'])) ? 1 : 0;
        } else {
            //No task with uniq_id $caseUniqId
            die;
        }
        ######## Checking user_project ################
        $this->loadModel('ProjectUser');
        $cond1 = array(
            'conditions' => array('ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP, 'Project.isactive' => 1, 'Project.id' => $prjid),
            'fields' => array('DISTINCT Project.id', 'Project.uniq_id', 'Project.name', 'Project.short_name')
        );
        $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
        $getProjId = $this->ProjectUser->find('first', $cond1);
        if ($getProjId) {
            $ProjId = $getProjId['Project']['id'];
            $projUniqId = $getProjId['Project']['uniq_id'];
            $ProjName = $getProjId['Project']['name'];
            $projShorName = $getProjId['Project']['short_name'];
        } else {
            //Session user not assigned the project $prjid
            die;
        }
        $sqlcasedata = array();
        $getPostCase = array();
        if ($ProjId && $curCaseNo) {
            //$getPostCase = $this->Easycase->query("SELECT Easycase.*, User1.name AS created_by , User2.name as updated_by , User3.name AS Assigned_to  FROM easycases as Easycase LEFT JOIN users User1 ON Easycase.user_id=User1.id LEFT JOIN users User2 ON Easycase.updated_by= User2.id LEFT JOIN users User3 ON Easycase.assign_to= User3.id WHERE Easycase.project_id='".$ProjId."' AND Easycase.case_no=".$curCaseNo." AND (Easycase.legend !=6) ORDER BY Easycase.actual_dt_created ASC");
            $getPostCase = $this->Easycase->query("SELECT Easycase.*, User1.name AS created_by , User2.name as updated_by , User3.name AS Assigned_to  FROM easycases as Easycase LEFT JOIN users User1 ON Easycase.user_id=User1.id LEFT JOIN users User2 ON Easycase.updated_by= User2.id LEFT JOIN users User3 ON Easycase.assign_to= User3.id WHERE Easycase.project_id='" . $ProjId . "' AND Easycase.case_no=" . $curCaseNo . " AND (Easycase.istype='1' OR Easycase.legend !=6) ORDER BY Easycase.actual_dt_created ASC");
            $estimated_hours = (isset($getPostCase['0']['Easycase']) && !empty($getPostCase['0']['Easycase'])) ? $getPostCase['0']['Easycase']['estimated_hours'] : '0.0';
            $getHours = $this->Easycase->query("SELECT SUM(hours) as hours FROM easycases as Easycase WHERE project_id='" . $ProjId . "' AND case_no=" . $curCaseNo . " AND reply_type=0");
            $hours = $getHours[0][0]['hours'];
//		$getcompletedtask = $this->Easycase->query("SELECT completed_task  FROM easycases as Easycase WHERE project_id='".$ProjId."' AND case_no=".$curCaseNo."  and completed_task != 0 ORDER BY id DESC LIMIT 1");
//		$completedtask  = $getcompletedtask[0]['Easycase']['completed_task'];
        } else {
            //$ProjId and $curCaseNo not found. This step should not, b'cos it handeled previously.
            die;
        }
        $view = new View();
        $cq = $view->loadHelper('Casequery');
        $frmt = $view->loadHelper('Format');
        $curdt = date('F_dS_Y', time());
        $filename = strtoupper($projShorName) . '_TASK_' . $curCaseNo . "_" . $curdt . '.csv';
        //$filename = $ProjName . "_#".$curCaseNo."_" . date("mdY", time()).'.csv';
        $folder_name = strtoupper($projShorName) . '_TASK_' . $curCaseNo . "_" . $curdt;
        if (file_exists(DOWNLOAD_TASK_PATH . $folder_name)) {
            @chmod(DOWNLOAD_TASK_PATH . $folder_name . "/attachments", 0777);
            @array_map('unlink', glob(DOWNLOAD_TASK_PATH . $folder_name . "/attachments/*"));
            @rmdir(DOWNLOAD_TASK_PATH . $folder_name . '/attachments');
            @array_map('unlink', glob(DOWNLOAD_TASK_PATH . $folder_name . "/*"));
            $isdel = rmdir(DOWNLOAD_TASK_PATH . $folder_name);
        }

        mkdir(DOWNLOAD_TASK_PATH . $folder_name, 0777, true);

        $file = fopen(DOWNLOAD_TASK_PATH . $folder_name . '/' . $filename, "w");
        $csv_output = "Title, Description, Status, Priority, Task Type, Assigned To, Created By, Last Updated By, Created On, Estimated Hours, Hours Spent";
        fputcsv($file, explode(',', $csv_output));
        foreach ($getPostCase AS $key => $case_list) {
            $status = '';
            $priority = '';
            $tasktype = '';
            $taskTitle = '';
            //if(!$key) {
            if (isset($case_list['Easycase']['title']) && $case_list['Easycase']['title']) {
                $taskTitle = $case_list['Easycase']['title'];
            }
            $status = $this->Format->displayStatus($case_list['Easycase']['legend']);
            if ($case_list['Easycase']['priority'] == 2) {
                $priority = 'Low';
            } elseif ($case_list['Easycase']['priority'] == 1) {
                $priority = 'Medium';
            } elseif ($case_list['Easycase']['priority'] == 0) {
                $priority = 'High';
            }
            $types = $cq->getTypeArr($case_list['Easycase']['type_id'], $GLOBALS['TYPE']);
            if (count($types)) {
                $tasktype = $types['Type']['name'];
            }
            //}
            $arr = '';
            $arr[] = $title = str_replace('"', '""', $case_list['Easycase']['title']);
            $arr[] = $description = strip_tags(str_replace('"', '""', $case_list['Easycase']['message']));
            $arr[] = $status;
            $arr[] = $priority;
            $arr[] = $tasktype;
            if ($case_list['User3']['Assigned_to']) {
                $Assigned = $case_list['User3']['Assigned_to'];
            } else {
                $Assigned = $case_list['User1']['created_by'];
            }
            $arr[] = $Assigned;
            $arr[] = $crby = $case_list['User1']['created_by'];
            $arr[] = $updateby = $case_list['User2']['updated_by'];

            $tz = $view->loadHelper('Tmzone');
            $temp_dat = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $case_list['Easycase']['actual_dt_created'], "datetime");
            $arr[] = $crted = date('m/d/Y H:i:s', strtotime($temp_dat));

            //$arr[] = $crted =date('m/d/Y H:i:s', strtotime($case_list['Easycase']['actual_dt_created']));	    
            $estmthrs = '';
            $hrspent = '';
            if ($case_list['Easycase']['istype'] == 1) {
                $estmthrs = $estimated_hours;
                $hrspent = $hours;
            } else {
                $estimated_hours = '';
                $hrspent = $case_list['Easycase']['hours'];
            }
            $arr[] = $estimated_hours;
            $arr[] = $hrspent;
            $easycaseids[] = $case_list['Easycase']['id'];
            $retval = fputcsv($file, $arr);
            //$csv_output .= $title.",".$status.",".$priority.",".$tasktype.",".$description.",".$Assigned.",".$crby.",".$updateby.",".$estmthrs.",".$hrspent.",".$crted.",".$modified;
        }
        fclose($file);
        if ($retval) {
            $filesarr = ClassRegistry::init('CaseFile')->find('all', array('conditions' => array('CaseFile.easycase_id' => $easycaseids, 'CaseFile.project_id' => $ProjId, 'CaseFile.company_id' => SES_COMP)));

            if ($filesarr) {

                foreach ($filesarr AS $k => $value) {
                    if ($value['CaseFile']['downloadurl']) {
                        if (!isset($fp)) {
                            $fp = fopen(DOWNLOAD_TASK_PATH . $folder_name . '/cloud.txt', 'a+');
                        }
                        fwrite($fp, "\n\t" . $value['CaseFile']['downloadurl'] . "\n");
                        $temp_url = $value['CaseFile']['downloadurl'];
                    } else {
                        if (!file_exists(DOWNLOAD_TASK_PATH . $folder_name . '/attachments')) {
                            mkdir(DOWNLOAD_TASK_PATH . $folder_name . "/attachments", 0777, true);
                        }
                        $temp_url = $frmt->generateTemporaryURL(DIR_CASE_FILES_S3 . $value['CaseFile']['file']);
                        $img = DOWNLOAD_TASK_PATH . $folder_name . "/attachments/" . $value['CaseFile']['file'];
                        $resp = file_put_contents($img, file_get_contents($temp_url));
                    }
                }
                if (isset($fp)) {
                    fclose($fp);
                }
            }
            $zipfile_name = strtoupper($projShorName) . '_TASK_' . $curCaseNo . "_" . $curdt . '.zip';
            $zipfile = DOWNLOAD_TASK_PATH . 'zipTask/' . $zipfile_name;
            $return = $this->Format->zipFile(DOWNLOAD_TASK_PATH . $folder_name, $zipfile, 1);
            if ($return) {
                if (file_exists(DOWNLOAD_TASK_PATH . $folder_name)) {
                    @array_map('unlink', glob(DOWNLOAD_TASK_PATH . $folder_name . "/attachments/*"));
                    @rmdir(DOWNLOAD_TASK_PATH . $folder_name . '/attachments');
                    @array_map('unlink', glob(DOWNLOAD_TASK_PATH . $folder_name . "/*"));
                    $isdel = rmdir(DOWNLOAD_TASK_PATH . $folder_name);
                }
                if (USE_S3 == 0) {
                    $download_url = HTTP_ROOT . DOWNLOAD_S3_TASK_PATH . $zipfile_name;
                    $this->set('downloadurl', $download_url);
                } else {
                    $s3 = new S3(awsAccessKey, awsSecretKey);
                    $s3->putBucket(DOWNLOAD_BUCKET_NAME, S3::ACL_PRIVATE);
                    $download_url = DOWNLOAD_S3_TASK_PATH . $zipfile_name;
                    $s3_download_url = "https://s3.amazonaws.com/" . DOWNLOAD_BUCKET_NAME . '/' . DOWNLOAD_S3_TASK_PATH . $zipfile_name;
                    $returnvalue = $s3->putObjectFile(DOWNLOAD_S3_TASK_PATH . $zipfile_name, DOWNLOAD_BUCKET_NAME, $download_url, S3::ACL_PUBLIC_READ);
                    if ($returnvalue) {
                        unlink(DOWNLOAD_S3_TASK_PATH . $zipfile_name);
                    }
                    $this->set('downloadurl', $s3_download_url);
                }
                $this->set('projName', $ProjName);
                $this->set('projId', $ProjId);
                $this->set('caseUid', $caseUniqId);
                $this->set('caseNum', $curCaseNo);
                $this->set('taskTitle', $taskTitle);
                $this->set('zipfilename', $zipfile_name);
            } else {
                $this->set('derror', 'Opps! Error occured in creation of zip file.');
            }
        } else {
            $this->set('derror', 'Opps! Error occured in creating the task csv file.');
        }
    }

    /**
     * @method sendDownloadTaskMail() Used for sending email to the user with the download link of the Task
     */
    function sendDownloadTaskMail() {
        $caseNum = $this->data['caseNum'];
        $projName = $this->data['projName'];
        $downloadUrl = $this->data['dwnldUrl'];
        $title = $this->data['taskTitle'];
        $zipfile = $this->data['zipfile'];
        $subject = 'Download Task# ' . $caseNum . ": " . $title;
        $this->loadModel('User');
        $userdetails = $this->User->query('SELECT User.*,Company.name FROM users User,company_users AS CompanyUser,companies AS Company WHERE User.id=CompanyUser.user_id AND CompanyUser.company_id=Company.id AND CompanyUser.company_id=' . SES_COMP . ' AND User.id=' . SES_ID);

        $this->Email->delivery = EMAIL_DELIVERY;
        $this->Email->to = $userdetails[0]['User']['email'];
        $this->Email->subject = $subject;
        $this->Email->from = FROM_EMAIL;
//		$this->Email->filePaths  = array(DOWNLOAD_TASK_PATH.'zipTask/');
//		$this->Email->attachments =array(SES_COMP."_".$caseNum."_".$projName.".zip");
        $this->Email->attachments = array(DOWNLOAD_TASK_PATH . 'zipTask/' . $zipfile);
        $this->Email->template = 'download_task';
        $this->Email->sendAs = 'html';
        $this->set('download_url', $downloadUrl);
        $this->set('userdetails', $userdetails[0]);
        $this->set('caseNum', $caseNum);
        $this->set('projName', $projName);
        $this->set('title', $title);
        $this->set('zipfile', $zipfile);
        if ($this->Sendgrid->sendgridsmtp($this->Email)) {
            @unlink(DOWNLOAD_TASK_PATH . 'zipTask/' . $zipfile);
            echo 'Success';
            exit;
        } else {
            echo 'Failure';
            exit;
        }
    }

}

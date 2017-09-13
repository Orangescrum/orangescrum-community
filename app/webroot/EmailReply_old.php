<?php
date_default_timezone_set('UTC');
ini_set("max_execution_time", 360);
error_reporting(0);
include_once("../Config/constants.php");
include_once("../Config/database.php");

//use ElephantIO\Client as ElephantIOClient;

include_once('../Vendor/s3/S3.php');
include_once('../../mailer/ImapMailbox.php');
include_once('PHPMailer/PHPMailerAutoload.php');
//include_once('../Vendor/ElephantIO/Client.php');

define('LOG_PATH', '../tmp/logs/os-email.log');

$config= new DATABASE_CONFIG();
$settings = $config->{'default'};
/* Database  connection */
$cfg["db_host"] = $settings['host'];
$cfg["db_user"] = $settings['login'];
$cfg["db_pass"] = $settings['password'];
$cfg["db_name"] = $settings['database'];

/* Email server connection */
$username = FROM_EMAIL_NOTIFY;
$password = '**************';
$hostname = '{imap.gmail.com:993/ssl}INBOX';

/* try to connect */
$inbox = imap_open($hostname, $username, $password) or die("Couldn't get your Emails: " . imap_last_error());
$mailbox = new ImapMailbox($hostname, $username, $password);

/* grab emails */
$emails = imap_search($inbox, 'UNSEEN');
/* if emails are returned, cycle through each… */
if ($emails) {

    $output = '';

    /* put the newest emails on top */
    rsort($emails);

	//echo "Number of email:".imap_num_msg($inbox);
    
    $mysql_pconnect = mysql_pconnect($cfg["db_host"], $cfg["db_user"], $cfg["db_pass"]);
    if (!$mysql_pconnect) {
        echo "Database Connection Failed";
        exit;
    }
    
    $db = mysql_select_db($cfg["db_name"], $mysql_pconnect);
    if (!$db) {
        echo "Database '".$cfg["db_name"]."' doesn't exist.";
        exit;
    }
    /* for every email… */
    foreach ($emails as $email_number) {
//$message = imap_fetchbody($inbox,$email_number,1);
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $header = imap_headerinfo($inbox, $email_number, 1);

        /* echo '<pre>';print_r($overview);
          print_r($header);die; */

        $imapMsgParts = $mailbox->getMail($overview[0]->uid); //echo '<pre>';print_r($imapMsgParts);die;
        $message = $imapMsgParts->textPlain; //textHtmlOriginal//textHtml;//imap_fetchbody($inbox,$email_number,1);
        $only_reply = $message;
		if($message == ''){			
			$chk_multibyt = 1;
			$message = $imapMsgParts->textHtml;
			$only_reply = $message;
		}else{
			$chk_multibyt = 0;
		}
		if(mb_detect_encoding($message, mb_detect_order(), true) == 'UTF-8'){
			//print $message; this is for other utf8 characters
		}else{
        if ($base64_msg = base64_decode($message, true)) {
            $message = $base64_msg;
        }
        $message = convert_ascii($message);
		}
        /*if ($base64_msg = base64_decode($message, true)) {
            $message = $base64_msg;
        }
        $message = convert_ascii($message);*/
		
        $savedirpath = "/tmp/";
        $message1 = array();
        $message1["attachment"]["type"][0] = "text";
        $message1["attachment"]["type"][1] = "multipart";
        $message1["attachment"]["type"][2] = "message";
        $message1["attachment"]["type"][3] = "application";
        $message1["attachment"]["type"][4] = "audio";
        $message1["attachment"]["type"][5] = "image";
        $message1["attachment"]["type"][6] = "video";
        $message1["attachment"]["type"][7] = "other";
        $mail = @$header->sender['0']->mailbox;
        $hostid = @$header->sender['0']->host;
        $mail_id = $mail . "@" . $hostid; //mail id of sender
		$googlemail_cond = '';
		if($hostid == 'gmail.com'){
			$googlemail_cond = $mail."@googlemail.com";
		}
        $sender_name = isset($header->sender['0']->personal) ? $header->sender['0']->personal : $mail; //name of sender
        if (strpos($header->message_id, "blackberry") || $mail == "pgetty") {
            $message = imap_fetchbody($inbox, $email_number, 1.2);
            $message = base64_decode($message);
        }

        $is_daily_update = 0;
        $dlyUpdSubject = '';
		$case_title = '';
        if (preg_match("/Daily Catch-Up - \d+\/\d+$/i", $header->subject, $matched)) {
            $is_daily_update = 1;
            $case_title = $matched[0];
            $case_title = ucwords(str_replace(' alert', '', strtolower($case_title)));
        }

        if ($is_daily_update) {
            /*$position = strpos($header->subject, "(") + 1;
            $length = strpos($header->subject, ")") - $position;
            $pj_sname = strtolower(substr($header->subject, $position, $length));
	
			$query_pid = "SELECT * FROM projects WHERE projects.short_name = '" . $pj_sname . "' AND projects.isactive='1'"; //details of the project
			*/
			
			$t_mesg = explode('__0__',$message); // new ly added
            $position = strpos($header->subject, "(") + 1;
            $length = strpos($header->subject, ")") - $position;
            $pj_sname = strtolower(substr($header->subject, $position, $length));
			if($t_mesg && count($t_mesg) >= 3){
				$prj_uniq_id = $t_mesg[1];
				$query_pid = "SELECT * FROM projects WHERE uniq_id = '" . $prj_uniq_id . "' AND projects.isactive='1'"; //details of the project
			}else{
				$query_pid = "SELECT * FROM projects WHERE short_name = '" . $pj_sname . "' AND projects.isactive='1'"; //details of the project
			}

            $result_pid = mysql_query($query_pid) or die('Query failed: ' . mysql_error());
			$count_prject = mysql_num_rows($result_pid);
			
			if ($count_prject >= 2) {
				$row_pid = array();
				
				$getUserId = mysql_fetch_assoc(mysql_query("select id from users where users.email='" . $mail_id . "'"));
				
				while($pj_array = mysql_fetch_assoc($result_pid)) {
					
					if(mysql_num_rows(mysql_query("select id from daily_updates where company_id='".$pj_array['company_id']."' and project_id='".$pj_array['id']."' and find_in_set('".$getUserId['id']."',user_id)"))) {
						
						$row_pid['company_id'] = $pj_array['company_id'];
						$row_pid['id'] = $pj_array['id'];
						$row_pid['uniq_id'] = $pj_array['uniq_id'];
						$row_pid['name'] = $pj_array['name'];
						$row_pid['short_name'] = $pj_array['short_name'];
						
						break;
					}
				}
			}
			else {
            $row_pid = mysql_fetch_assoc($result_pid);
			}

			if ($count_prject) {
                $query = "SELECT Easycase.* FROM easycases AS Easycase WHERE Easycase.title = '" . $case_title . "' AND project_id = '" . $row_pid['id'] . "' AND Easycase.dt_created LIKE '" . gmdate('Y') . "%' LIMIT 1";
                $result = mysql_query($query) or die('Query failed: ' . mysql_error());

                $query1 = "SELECT User.id,User.name,User.uniq_id,ProjectUser.company_id FROM users as User, project_users as ProjectUser WHERE User.email='" . $mail_id . "' AND User.id=ProjectUser.user_id AND ProjectUser.project_id='" . $row_pid['id'] . "'";
                $result1 = mysql_query($query1) or die('Query failed: ' . mysql_error());

                if (mysql_num_rows($result1)) {
                    $row1 = mysql_fetch_assoc($result1);
                    $user_auth_key = $row1['uniq_id'];

                    //checking company is not a free user
                    $valid_comp = 0;
                    $sqlCom = "SELECT id,is_free,is_cancel,subscription_id FROM  user_subscriptions as UserSubscription WHERE UserSubscription.company_id='" . $row_pid['company_id'] . "' ORDER BY created DESC LIMIT 1";
                    $qryCom = mysql_query($sqlCom) or die('Query failed: ' . mysql_error());
                    if (mysql_num_rows($qryCom)) {
                        $resCom = mysql_fetch_assoc($qryCom);
                        if ($resCom['is_free'] == 1 || ( $resCom['is_cancel'] == 0 && $resCom['subscription_id'] > 1)) {
                            $valid_comp = 1;
                        }
                    }

                    if ($valid_comp) {
                        $query_dlyupd = "SELECT post_by FROM daily_updates WHERE project_id = '" . $row_pid['id'] . "'"; //get who set the daily update mail
                        $result_dlyupd = mysql_query($query_dlyupd) or die('Query failed: ' . mysql_error());
                        $dlyUpdCrtd = $row1['id'];
                        if (mysql_num_rows($result_dlyupd)) {
                            $row_dlyupd = mysql_fetch_assoc($result_dlyupd);
                            if ($row_dlyupd['post_by']) {
                                $dlyUpdCrtd = $row_dlyupd['post_by'];
                            }
                        }

                        if (!mysql_num_rows($result)) {//insert
                            $data = array();
                            $data['user_auth_key'] = $user_auth_key;
                            $data['api_file'] = 'ajaxpostcase';
                            $data['pid'] = $row_pid['id'];
                            $data['CS_project_id'] = $row_pid['uniq_id'];
                            $data['CS_istype'] = 1; //Post case
                            $data['CS_title'] = $case_title;
                            $data['CS_type_id'] = 10;
                            $data['CS_priority'] = 1;
                            $data['CS_message'] = '';
                            $data['CS_assign_to'] = $dlyUpdCrtd; //$row_pid['default_assign'];
                            $data['CS_user_id'] = $dlyUpdCrtd;
                            $data['CS_due_date'] = $row_pid['No Due Date'];
                            $data['CS_milestone'] = '';
                            $data['CS_id'] = 0;
                            $data['CS_legend'] = 1;
                            $data['hours'] = 0;
                            $data['completed'] = 0;
                            $data['from_email'] = 1;
//print_r($data);
                            $responce = curlPostData(HTTP_ROOT . 'easycases/ajaxpostcase', $data);
//print '<pre>';print_r($responce);
                            if ($responce) {
                                $responce = json_decode($responce, true);
                                if ($responce['caseNo']) {
                                    $eData = array();
                                    $eData['user_auth_key'] = $user_auth_key;
                                    $eData['api_file'] = 'ajaxemail';
                                    $eData['pid'] = $row_pid['id'];
                                    $eData['projId'] = $responce['projId'];
                                    $eData['emailUser'] = $responce['emailUser'];
                                    $eData['allfiles'] = $responce['allfiles'];
                                    $eData['caseNo'] = $responce['caseNo'];
                                    $eData['emailTitle'] = $responce['emailTitle'];
                                    $eData['emailMsg'] = $responce['emailMsg'];
                                    $eData['casePriority'] = $responce['casePriority'];
                                    $eData['caseTypeId'] = $responce['caseTypeId'];
                                    $eData['msg'] = $responce['msg'];
                                    $eData['emailbody'] = $responce['emailbody'];
                                    $eData['caseIstype'] = $responce['caseIstype'];
                                    $eData['csType'] = $responce['csType'];
                                    $eData['caUid'] = $responce['caUid'];
                                    $eData['caseid'] = $responce['caseid'];
                                    $eData['caseUniqId'] = $responce['caseUniqId'];
//echo '<pre>';print_r($eData);
                                    $email_res = curlPostData(HTTP_ROOT . 'easycases/ajaxemail', $eData);
//print '<pre>';print_r($email_res);
                                }
                            }
                        }

                        $query = "SELECT Easycase.* FROM easycases AS Easycase WHERE Easycase.title = '" . addslashes($case_title) . "' AND project_id = '" . $row_pid['id'] . "' AND Easycase.dt_created LIKE '" . gmdate('Y') . "%' LIMIT 1";
                        $result = mysql_query($query) or die('Query failed: ' . mysql_error());

                        if (mysql_num_rows($result)) {//update
                            $msg = getEmailMsg($header, $message);
                            if ($msg) {
                                $easycase = mysql_fetch_assoc($result);

                                $data = array();
                                $data['user_auth_key'] = $user_auth_key;
                                $data['api_file'] = 'ajaxpostcase';
                                $data['pid'] = $row_pid['id'];
                                $data['CS_project_id'] = $row_pid['uniq_id'];
                                $data['CS_istype'] = 2; //Comment on a post case
                                $data['CS_title'] = ''; //reply title is always null.
                                $data['CS_type_id'] = 10;
                                $data['CS_priority'] = '';
                                $data['CS_message'] = $msg;
                                $data['CS_assign_to'] = $dlyUpdCrtd; //$row_pid['default_assign'];
                                $data['CS_due_date'] = '';
                                $data['CS_milestone'] = '';
                                $data['CS_id'] = $easycase['id'];
                                $data['CS_case_no'] = $easycase['case_no'];
                                $data['datatype'] = 1;
                                $data['prelegend'] = 1;
                                $data['hours'] = 0;
                                $data['completed'] = 0;
                                $data['from_email'] = 1;
//print_r($data);
                                $responce = curlPostData(HTTP_ROOT . 'easycases/ajaxpostcase', $data);
//print '<pre>';print_r($responce);
                                if ($responce) {
                                    $responce = json_decode($responce, true);
                                    if ($responce['caseNo']) {
                                        $eData = array();
                                        $eData['user_auth_key'] = $user_auth_key;
                                        $eData['api_file'] = 'ajaxemail';
                                        $eData['pid'] = $row_pid['id'];
                                        $eData['projId'] = $responce['projId'];
                                        $eData['emailUser'] = $responce['emailUser'];
                                        $eData['allfiles'] = $responce['allfiles'];
                                        $eData['caseNo'] = $responce['caseNo'];
                                        $eData['emailTitle'] = $responce['emailTitle'];
                                        $eData['emailMsg'] = $responce['emailMsg'];
                                        $eData['casePriority'] = $responce['casePriority'];
                                        $eData['caseTypeId'] = $responce['caseTypeId'];
                                        $eData['msg'] = $responce['msg'];
                                        $eData['emailbody'] = $responce['emailbody'];
                                        $eData['caseIstype'] = $responce['caseIstype'];
                                        $eData['csType'] = $responce['csType'];
                                        $eData['caUid'] = $responce['caUid'];
                                        $eData['caseid'] = $responce['caseid'];
                                        $eData['caseUniqId'] = $responce['caseUniqId'];
//echo '<pre>';print_r($eData);
                                        $email_res = curlPostData(HTTP_ROOT . 'easycases/ajaxemail', $eData);
//print '<pre>';print_r($email_res);
                                    }
                                }
                            } else {
                                write2log("Daily Update Error: No message to add reply.", $message, $mail_id, $header->subject, $header->date);
                            }
                        } else {
                            write2log("Daily Update Error: There is no task for this reply.", $message, $mail_id, $header->subject, $header->date);
                        }
                    } else {
                        write2log("Daily Update Error: The account is cancelled:", $message, $mail_id, $header->subject, $header->date);
                    }
                } else {
                    write2log("Daily Update Error: This user not associate with project: $row_pid[name] ($row_pid[short_name]).", $message, $mail_id, $header->subject, $header->date);
                }
            } else {
                write2log("Daily Update Error: No project having short name: '$pj_sname'.", $message, $mail_id, $header->subject, $header->date);
            }
        } else {
            $task_posted = 0;
            //$funiq=strpos($message,"Case#:");
                          //print $message;exit;
			$t_str = explode('/dashboard#details/',$message);
			if(isset($t_str[1])){
				$t_str[1] = trim($t_str[1]);
				$t_str_eq =explode('>',$t_str[1]);
			}
			$cs_uniq_id = -1;
			if(isset($t_str_eq[0])){
				if(!empty($t_str_eq[0])){
					$cs_uniq_id = trim($t_str_eq[0]);
				}else{
					$cs_uniq_id = trim($t_str_eq[1]);
				}
			}
			$pos_srt = strpos($cs_uniq_id, '"');
			if($pos_srt === false) {				
			}else{
				$cs_uniq_id = explode('"',$cs_uniq_id);
				$cs_uniq_id = $cs_uniq_id[0];
			}
			if(stristr($cs_uniq_id,' ')){
				$output = preg_replace('!\s+!', ' ', $cs_uniq_id);
				$cs_uniq_id_t = explode(' ',$output);
				$cs_uniq_id = $cs_uniq_id_t[0];
			}
			//31/08/2016
			if(stristr($cs_uniq_id,'<https')){
				$cs_uniq_id = explode('<https',$cs_uniq_id);
				$cs_uniq_id = $cs_uniq_id[0];
			}
			#print $cs_uniq_id;exit;
            $funiq = strpos($message, "Task#:");
            $messagelast = substr($message, $funiq);
            $luniq = strpos($messagelast, "Type:");
            $uniq = substr($message, $funiq + 6, $luniq - 6);
            $uniq = str_replace("*", "", $uniq);
            $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
            $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
            $uniq = str_replace($carimap, $carhtml, $uniq);
            $cs_all = trim($uniq);
            $pj_ex = explode("-", $cs_all);
            $pj_sname = $pj_ex['0'];
            $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
            $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
            $pj_sname = str_replace($carimap, $carhtml, $pj_sname);
            $cs_no = @$pj_ex['1'];
            $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
            $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
            $cs_no = str_replace($carimap, $carhtml, $cs_no);
            $pj_sname = strip_tags(str_replace(" ", "", $pj_sname));
			$query_pid_eq = "SELECT * FROM easycases WHERE uniq_id = '" . trim($cs_uniq_id) . "'"; //details of the project
            
			$result_pid_eq = mysql_query($query_pid_eq) or die('Query failed: ' . mysql_error());
			$num_rows = 0;
			$num_rows = mysql_num_rows($result_pid_eq);
			/*$hj = fopen('wotextt.txt','a'); 
			fwrite($hj,$query_pid_eq);
			fwrite($hj,'---'.$num_rows.'----');*/
			
			if ( $num_rows < 1 ) {
				/*$Failedmessage = "Couldn't identify the Task Uniq_id - " . $cs_uniq_id;
				send_email(INFO_MAIL, '', "Failed to save Email reply in Orangescrum", $Failedmessage);
				continue;*/
			}		
			
			$row_pid_eq = mysql_fetch_assoc($result_pid_eq);	
			//fwrite($hj,print_r($row_pid_eq,true));
			$cs_no = $row_pid_eq['case_no'];
            $query_pid = "SELECT * FROM projects WHERE id = '" . $row_pid_eq['project_id'] . "'"; //details of the project
	    $result_pid = mysql_query($query_pid) or die('Query failed: ' . mysql_error());
               
            $foundshortname = 1;
            if (!mysql_num_rows($result_pid)) {
                $foundshortname = 0;
                $subject = $header->subject;
				/*$gotit = 0;
                $pj_sname = "";
                if ($subject) {
                    $getunq = explode(":#", $subject);
                    $nowunq = explode("(", $getunq[1]);
                    $cs_no = $nowunq[0];

                    preg_match_all('/\(([^)]+)\)/', $subject, $match);
                    if (isset($match[1][0])) {
                        if (count($match[1]) >= 2) {
                            $pj_sname = $match[1][1];
                        } else {
                            $pj_sname = $match[1][0];
                        }
                    }
                }
                if ($pj_sname) {
                    $query_pid = "SELECT * FROM projects WHERE short_name = '" . $pj_sname . "'"; //details of the project
		    $result_pid = mysql_query($query_pid) or die('Query failed: ' . mysql_error());
                    if (!mysql_num_rows($result_pid)) {
                        $gotit = 0;
                    } else {
                        $gotit = 1;
                    }
                }
                if ($gotit == 0) {
                    $Failedmessage = "Couldn't identify the Project Short name - " . $pj_sname . "<br/> Sbject: " . $subject;
                    send_email(INFO_MAIL, '', "Failed to save Email reply in Orangescrum", $Failedmessage);
                    continue;
                }*/
				$Failedmessage = "Couldn't identify the Project id - " . $row_pid_eq['project_id'] . "<br/> Sbject: " . $subject;
				send_email(INFO_MAIL, '', "Failed to save Email reply in Orangescrum", $Failedmessage);
				continue;
            } //if there is not project with this $pj_sname
	    
            $row_pid = mysql_fetch_assoc($result_pid);
			$pj_sname = $row_pid['short_name'];
            $cs_no = strip_tags($cs_no);
            $query = "SELECT * FROM easycases WHERE case_no = '" . $cs_no . "' AND project_id = '" . $row_pid['id'] . "' AND title != ''"; //details of the case
            $result = mysql_query($query) or die('Query failed: ' . mysql_error());
            $row = mysql_fetch_assoc($result);

			/*$hj = fopen('wotextt_today.txt','a');
			fwrite($hj,'==================================================================');
			fwrite($hj,$query.'----'.$cs_uniq_id);
			fwrite($hj,print_r($row_pid,true));*/
			
			/*$query1 = "SELECT User.id,User.name,User.last_name,ProjectUser.company_id FROM users as User, project_users as ProjectUser WHERE User.email='" . $mail_id . "' AND User.id=ProjectUser.user_id AND ProjectUser.project_id='" . $row_pid['id'] . "'";*/
			//print $msg;exit;            
			if($googlemail_cond != ''){
				$query1 = "SELECT User.id,User.name,User.email,ProjectUser.company_id FROM users as User, project_users as ProjectUser WHERE (User.email='" . $mail_id ."' OR User.email='".$googlemail_cond."') AND User.id=ProjectUser.user_id AND ProjectUser.project_id='" . $row_pid['id'] . "'";
			}else{
				$query1 = "SELECT User.id,User.name,ProjectUser.company_id FROM users as User, project_users as ProjectUser WHERE User.email='" . $mail_id ."' AND User.id=ProjectUser.user_id AND ProjectUser.project_id='" . $row_pid['id'] . "'";
			}

            $result1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
            $row1 = mysql_fetch_assoc($result1);
            $user_id = $row1['id']; //user id of the sender
            $unq = md5(uniqid()); //uniq id in md5 format
            $gmt_dttime = gmdate('Y-m-d H:i:s');
			if($googlemail_cond != '' && trim($row1['email']) != ''){		
				$mail_id = $row1['email'];
			}
			/*fwrite($hj,$query1.'----'.$user_id);
			fwrite($hj,print_r($row1,true));*/
			
			$query_pusers = "SELECT User.id,User.name,User.last_name FROM users as User WHERE User.id IN(SELECT user_id FROM project_users WHERE project_id ='" . $row_pid['id'] . "')";
            $result_pusers = mysql_query($query_pusers) or die('Query failed: ' . mysql_error());
			while($row_puser = mysql_fetch_assoc($result_pusers)){
				$row_pusers[] = $row_puser;
			}
			
			$query_comp = "SELECT seo_url FROM companies as Company WHERE Company.id ='" . $row_pid['company_id'] . "'";
            $result_comp = mysql_query($query_comp) or die('Query failed: ' . mysql_error());
            $row_comp = mysql_fetch_assoc($result_comp);
            $comp_name_seo = $row_comp['seo_url'];
			
			/*fwrite($hj,$foundshortname.'===---==='.$message);
			fwrite($hj,print_r($header,true));
			fwrite($hj,print_r($row_pusers,true));*/
			
            if($foundshortname == 0 && trim($message) <= 8) {
                $msg = $only_reply; $formatted = "";
                 if(stristr($msg,"Quoting ") && stristr($msg,"<".FROM_EMAIL_NOTIFY.">")) {
                    $firstexp = explode("Quoting ", $msg);
                    $secondexp = explode("Just REPLY to this Email", $msg);
                    if(count($firstexp) == 3 && count($secondexp) == 2) {
                       $newdata = "";
                       for($i=0;$i<count($firstexp)-1;$i++) {
                          $newdata.= $firstexp[$i]." ";
                       }
                       $formatted = trim($newdata);
                    }
                    else {
                        $formatted = $firstexp[0];
                    }
                }
                if(!$formatted && stristr($msg,"Just REPLY to this Email")) {
                    $thirdexp = explode("Just REPLY to this Email", $msg);
                    $formatted = $thirdexp[0];
                }
                
                if($formatted) {
                    $msg = $formatted;
                }
                 
            }
            else {				
               $msg = getEmailMsg($header, $message,$row_pusers,$chk_multibyt);
			}
			/*$hj_test = fopen('wotextt_today_o_p.txt','a');
			fwrite($hj_test,$cs_uniq_id);
			fwrite($hj_test,$msg);
			fwrite($hj_test,'----uid:'.$user_id);
			fclose($hj_test);*/
            if ($msg != "" && $user_id && mysql_num_rows($result)) {
                //checking company is not cancel
                $task_posted = 1;
                $valid_comp = 0;
                if ($row1['company_id']) {
                    $sqlCom = "SELECT id,is_free,is_cancel,subscription_id FROM  user_subscriptions as UserSubscription WHERE UserSubscription.company_id='" . $row1['company_id'] . "' ORDER BY created DESC LIMIT 1";
                    $qryCom = mysql_query($sqlCom) or die('Query failed: ' . mysql_error());
                    if (mysql_num_rows($qryCom)) {
                        $resCom = mysql_fetch_assoc($qryCom);
                        //if ($resCom['is_free'] == 1 || ( $resCom['is_cancel'] == 0 && $resCom['subscription_id'] > 1)) {
                        if ($resCom['is_cancel'] == 0) {
                            $valid_comp = 1;
                        }
                    }
                }

                if ($valid_comp) {
                    $actual_dt_created = $dt_created = gmdate('Y-m-d H:i:s', strtotime($header->date));

                    $user_id_updated = $user_id;

                    if (strtotime($row['dt_created']) > strtotime($dt_created)) {
                        $dt_created = $row['dt_created'];
                        $user_id_updated = $row['updated_by'];
                    }

                    $query2 = "INSERT INTO easycases (uniq_id,case_no,project_id,user_id,type_id,priority,title,message,assign_to,due_date,istype,format,status,legend,isactive,dt_created,actual_dt_created,from_email) VALUES
('" . $unq . "','" . $row['case_no'] . "','" . $row['project_id'] . "','" . $user_id . "','" . $row['type_id'] . "','" . $row['priority'] . "','','" . mysql_real_escape_string($msg) . "','" . $row['assign_to'] . "','" . $row['due_date'] . "','2','2','" . $row['status'] . "','2','" . $row['isactive'] . "','" . $actual_dt_created . "','" . $actual_dt_created . "','1')";
                    $result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
                    $last_id = mysql_insert_id();
                    if (isset($last_id)) {
                        $structure = imap_fetchstructure($inbox, $email_number);
                        $parts = $structure->parts;
                        $fpos = 2;
                        if ($structure->subtype == 'MIXED') {
//$message = imap_fetchbody($inbox,$email_number,1.2);
                            for ($i = 1; $i < count($parts); $i++) {
                                $message1["pid"][$i] = ($i);
                                $part = $parts[$i];
                                if ($part->ifdisposition == "1") {
                                    if ($part->disposition == "ATTACHMENT") {
                                        $message1["type"][$i] = $message1["attachment"]["type"][$part->type] . "/" . strtolower($part->subtype);
                                        $message1["subtype"][$i] = strtolower($part->subtype);
                                        $ext = $part->subtype;
                                        $params = $part->dparameters;
                                        $filename = $part->dparameters[0]->value;
                                        $filename = chnageUploadedFileName($filename);
                                        $ext = substr(strrchr($filename, "."), 1);
                                        $extList = array("bat", "com", "cpl", "dll", "exe", "msi", "msp", "pif", "shs", "sys", "cgi", "reg", "bin", "torrent", "yps", "mp4", "mpeg", "mpg", "3gp", "dat", "mod", "avi", "flv", "xvid", "scr", "com", "pif", "chm", "cmd", "cpl", "crt", "hlp", "hta", "inf", "ins", "isp", "jse?", "lnk", "mdb", "ms", "pcd", "pif", "scr", "sct", "shs", "vb", "ws", "vbs");
                                        $extn = strtolower($ext);
                                        if (!in_array($extn, $extList)) {
                                            if (file_exists($savedirpath . $filename)) {
                                                $tot = strlen($filename);
                                                $extcnt = strlen($ext);
                                                $end = $tot - $extcnt - 1;
                                                $onlyfile = substr($filename, 0, $end);
                                                for ($d = 1; $d <= 500; $d++) {
                                                    $newFile = $onlyfile . "(" . $d . ")." . $ext;
                                                    if (!file_exists($savedirpath . $newFile)) {
                                                        $filename = $newFile;
                                                        $mege = "";
                                                        $data = "";
                                                        $mege = imap_fetchbody($inbox, $email_number, $fpos);
                                                        $filename = "$filename";
                                                        $fp = fopen($savedirpath . $filename, 'w');
                                                        $coding = $part->type;
                                                        if ($coding == '0') {
                                                            $data = imap_8bit($mege);
                                                        } else if ($coding == '1') {
                                                            $data = imap_8bit($mege);
                                                        } else if ($coding == '2') {
                                                            $data = imap_binary($mege);
                                                        } else if ($coding == '3') {
                                                            $data = imap_base64($mege);
                                                        } else if ($coding == '4') {
                                                            $data = imap_qprint($mege);
                                                        } else if ($coding == '5') {
                                                            $data = imap_base64($mege);
                                                        } else {
                                                            $data = $mege;
                                                        }
//$data=getdecodevalue($mege,$part->type);	
                                                        fputs($fp, $data);
                                                        fclose($fp);
                                                        $fpos+=1;
                                                        $size = filesize($savedirpath . $filename) / 1024;
// s3 bucket  start                                 						 
                                                        $s3 = new S3(awsAccessKey, awsSecretKey);
                                                        $s3->putBucket(BUCKET_NAME, S3::ACL_PRIVATE);
                                                        $folder_orig_Name = 'files/case_files/' . trim($filename);
                                                        $s3->putObjectFile($savedirpath . $filename, BUCKET_NAME, $folder_orig_Name, S3::ACL_PRIVATE);
//s3 bucket end
                                                        unlink($savedirpath . $filename);
                                                        $query_file = "INSERT INTO case_files (easycase_id,comment_id,file,thumb,file_size,count,isactive) VALUES
('" . $last_id . "','0','" . $filename . "','','" . $size . "','0','1')";
                                                        $result_file = mysql_query($query_file) or die('Query failed: ' . mysql_error());
                                                        $query_up = "UPDATE easycases SET format='1' WHERE id= '" . $row['id'] . "' ";
                                                        $result_up = mysql_query($query_up) or die('Query failed: ' . mysql_error());
                                                        $query_new = "UPDATE easycases SET format='1' WHERE id= '" . $last_id . "' ";
                                                        $result_new = mysql_query($query_new) or die('Query failed: ' . mysql_error());
                                                        break;
                                                    }
                                                }
                                            } else {
                                                $mege = "";
                                                $data = "";
                                                $mege = imap_fetchbody($inbox, $email_number, $fpos);
                                                $filename = "$filename";
                                                $fp = fopen($savedirpath . $filename, 'w');
                                                $coding = $part->type;
                                                if ($coding == '0') {
                                                    $data = imap_8bit($mege);
                                                } else if ($coding == '1') {
                                                    $data = imap_8bit($mege);
                                                } else if ($coding == '2') {
                                                    $data = imap_binary($mege);
                                                } else if ($coding == '3') {
                                                    $data = imap_base64($mege);
                                                } else if ($coding == '4') {
                                                    $data = imap_qprint($mege);
                                                } else if ($coding == '5') {
                                                    $data = imap_base64($mege);
                                                } else {
                                                    $data = $mege;
                                                }
//$data=getdecodevalue($mege,$part->type);	
                                                fputs($fp, $data);
                                                fclose($fp);
                                                $fpos+=1;
                                                $size = floor(filesize($savedirpath . $filename) / 1024);
// s3 bucket  start                                 						 
                                                $s3 = new S3(awsAccessKey, awsSecretKey);
                                                $s3->putBucket(BUCKET_NAME, S3::ACL_PRIVATE);
                                                $folder_orig_Name = 'files/case_files/' . trim($filename);
                                                $s3->putObjectFile($savedirpath . $filename, BUCKET_NAME, $folder_orig_Name, S3::ACL_PRIVATE);
//s3 bucket end
                                                unlink($savedirpath . $filename);
                                                $query_file = "INSERT INTO case_files (easycase_id,comment_id,file,thumb,file_size,count,isactive) VALUES
('" . $last_id . "','0','" . $filename . "','','" . $size . "','0','1')";
                                                $result_file = mysql_query($query_file) or die('Query failed: ' . mysql_error());
                                                $query_up = "UPDATE easycases SET format='1' WHERE id= '" . $row['id'] . "' ";
                                                $result_up = mysql_query($query_up) or die('Query failed: ' . mysql_error());
                                                $query_new = "UPDATE easycases SET format='1' WHERE id= '" . $last_id . "' ";
                                                $result_new = mysql_query($query_new) or die('Query failed: ' . mysql_error());
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $case_count = $row['case_count'] + 1;
                    $query3 = "UPDATE easycases SET case_count ='" . $case_count . "' ,updated_by='" . $user_id_updated . "', legend='2',dt_created='" . $dt_created . "' WHERE id= '" . $row['id'] . "' ";
                    $result3 = mysql_query($query3) or die('Query failed: ' . mysql_error());
//getting all mail is and user to send mail
                    $query8 = "SELECT user_id FROM case_user_emails WHERE easycase_id='" . $row['id'] . "'";

                    $result8 = mysql_query($query8) or die('Query failed: ' . mysql_error());
                    $allmail = "";
                    while ($row8 = mysql_fetch_assoc($result8)) {
                        foreach ($row8 as $col_value) {
                            $query9 = "SELECT name FROM users WHERE id='" . $col_value . "'";
                            $result9 = mysql_query($query9) or die('Query failed: ' . mysql_error());
                            $row9 = mysql_fetch_assoc($result9);
                            $allmail = "," . $row9['name'];
                        }
                    }
                    $allmail = substr($allmail, 1);
//sending mail to the users who are included to this case
                    $query4 = "SELECT user_id FROM case_user_emails WHERE easycase_id='" . $row['id'] . "'";

                    $result4 = mysql_query($query4) or die('Query failed1: ' . mysql_error());
                    while ($row4 = mysql_fetch_assoc($result4)) {
                        foreach ($row4 as $col_value) {
//Getting the user mail id who is replying to the case
                            $query5 = "SELECT email FROM users WHERE id='" . $col_value . "'";
                            $result5 = mysql_query($query5) or die('Query failed2: ' . mysql_error());
                            $row5 = mysql_fetch_assoc($result5);
                            $to = $row5['email'];
//subject of the mail to be sent
                            $projNameInSh = $row_pid['name'];
                            /* if(strlen($projNameInSh)>20) {
                              $projNameInSh = substr($projNameInSh,0,19).'...';
                              } */
                            //$subject = "Re: [Orangescrum]:" . $projNameInSh . ":#" . $row['case_no'] . "(" . $pj_sname . ")" . "-" . stripslashes(html_entity_decode($row['title'], ENT_QUOTES));
							$subject = "Re: " . $projNameInSh . " - " . stripslashes(html_entity_decode($row['title'], ENT_QUOTES));
//type array
                            $types = array("1" => 'Bug', "2" => 'Development', "3" => 'Enhancement', "4" => "Research n Do", "5" => "Quality Assurance", "6" => "Unit Testing", "7" => "Maintenance", "8" => "Others", "9" => "Release", "10" => "Update");
                            $typ = $row['type_id']; //type id
                            $hid_priority = $row['priority']; //priority id
//priority in text in different colors
                            if ($typ != 10) {
                                $pri = "";
                                if ($hid_priority == "NULL" || $hid_priority == "") {
                                    $pri = "<font  style='color:#AD9227;padding:0;margin:0;height:16px;'>LOW</font>";
                                } else if ($hid_priority == 0) {
                                    $pri = "<font style='color:#AE432E;padding:0;margin:0;height:16px;'>HIGH</font>";
                                } else if ($hid_priority == 1) {
                                    $pri = "<font style='color:#28AF51;padding:0;margin:0;height:16px;'>MEDIUM</font>";
                                } else if ($hid_priority >= 2) {
                                    $pri = "<font style='color:#AD9227;padding:0;margin:0;height:16px;'>LOW</font>";
                                }
                                $priRity = "<font color='#737373'><b>Priority:</b></font> " . $pri;
                            } else {
                                $priRity = "";
                            }
//home path related as HTTP_ROOT
                            $home = HTTP_ROOT;
//response to the case
                            $resp = "has responded on the case:";
//status of the case going through the mail
                            $sts = "<font color='#737373' style='font-weight:bold'>Status:</font> <font color='#04407C' style='font:normal 12px verdana;'>WIP</font>";
//case number with project short name
                            $csno = $row_pid['short_name'] . "-" . $row['case_no'];
//Message to be sent
                            $message1 = "<table cellspacing='1' cellpadding='1' border='0' align='left' width='100%' bgcolor='#FFFFFF'>
<tr bgcolor='#FFFFFF'>
<td align='left'>
<table cellpadding='0' cellspacing='0' align='left' border='0' width='100%' bgcolor='#FFFFFF'>
<tr bgcolor='#FFFFFF'>
<td colspan='2' align='left' style='font:normal 12px verdana;line-height:20px' valign='top' >
<font color='#737373'><b>Title: </b></font>
<a href='" . $home . "users/login/dashboard#details/" . $row['uniq_id'] . "' target='_blank' style='text-decoration:underline;color:#F86A0C;font:normal 12px verdana;'>" . stripslashes($row['title']) . "</a>
<br/>
<font color='#737373'><b>Project:</b></font> " . $row_pid['name'] . "
</td>
</tr>
<tr bgcolor='#FFFFFF'>
<td align='left' valign='top' style='font:12px Verdana;' colspan='2' width='100%'>
<table cellspacing='0' cellpdding='0' width='50%'>
<tr>
<td align='left' style='font:normal 12px verdana;line-height:20px'>
<font color='#737373'><b>Task#:</b></font> " . $csno . "
</td>
<td style='font:normal 12px verdana;line-height:20px'>
<font color='#737373'><b>Type:</b></font> " . $types[$typ] . "
</td>
</tr>
<tr>
<td align='left' style='font:normal 12px verdana;'>" . $priRity . "</td>
<td style='font:normal 12px verdana;'>" . $sts . "</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align='left' colspan='2' style='padding:5px 0px'>
<hr style='border: none; height: 0.1em; color:#DBDBDB;background:#DBDBDB;'/>
</td>
</tr>
<tr>
<td colspan='2'>
<table width='100%' cellspacing='0' cellpadding='0' border='0'>
<tr>
<td align='left' style='font:12px verdana;' valign='top'>
<table border='0' valign='top' cellspacing='0' cellpadding='0'>
<tr>
<td align='left' style='font:12px verdana;padding-top:0px;color:#585858'>
" . $row1['name'] . " " . $resp . "
</td>	
</tr>
<tr>	  
<td align='left' style='font:12px Verdana;padding-top:8px;'>
" . stripslashes($msg) . "
</td>	
</tr>

</table>
</td>
</tr>
</table>
</td>  
</tr>

<tr>
<td align='left' colspan='2' style='padding:5px 0px'>
<hr style='border: none; height: 0.1em; color:#DBDBDB;background:#DBDBDB;'/>
</td>
</tr>
<tr>
<td align='left' style='font:12px Verdana;line-height:20px;padding-top:2px;color:#737373' colspan='2'>
To read the original message, view comments, reply & download attachment:<br/>
Link: <a href='" . $home . "users/login/dashboard#details/" . $row['uniq_id'] . "' target='_blank'>" . $home . "users/login/dashboard#details/" . $row['uniq_id'] . "</a>
</td>	  
</tr>
<tr><td align='left' colspan='2'>&nbsp;</td></tr>
<tr>
<td align='left' style='font:11px Verdana;padding-top:2px;color:#737373' colspan='2'>
<i>This email notification is sent by " . $row1['name'] . " to " . $allmail . "</i>
</td>	  
</tr>
<tr>	
<td align='left' style='padding-bottom:6px;' colspan='2'  valign='top'>
<table cellspacing='0' cellpadding='0' border='0' align='left'>
<tr>
<td align='left' style='font:11px Verdana;color:#737373;padding-top:3px;' valign='middle' width='105px'>
<i>Brought to you by</i> 
</td>
<td align='left' valign='middle' style='padding-left:5px;'>
<a href='".HTTP_ROOT."' target='_blank' style='font:11px verdana;text-decoration:underline;'>Orangescrum</a>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align='left' colspan='2' style='padding:5px 0px'>
<hr style='border: none; height: 0.1em; color:#DBDBDB;background:#DBDBDB;'/>
</td>
</tr>
<tr>
<td align='left' style='font:9px Verdana;padding-top:2px;color:#737373' colspan='2'>
You are receiving this email notification because you have subscribed to orangescrum, to unsubscribe, please email with subject 'Unsubscribe' to <a href='mailto:".SUPPORT_EMAIL."'>".SUPPORT_EMAIL."</a>
</td>	  
</tr>
</table>
</td>
</tr>
</table>
";

                            $from1 = str_replace(" ", "", $row1['name']) . "<".FROM_EMAIL_NOTIFY.">";
                            $headers = 'MIME-Version: 1.0' . "\r\n";
                            $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers.= 'From:' . $from1 . "\r\n";
                            if ($to != $mail_id) {
                                mail($to, $subject, $message1, $headers);
                            }
                        }
                    }
					//tempporarily stopped due to server not updated to php > 5.4
                    //iotoserver(array('channel' => $row_pid['uniq_id'], 'message' => 'Updated.~~NA~~' . $row['case_no'] . '~~' . 'UPD' . '~~' . stripslashes(html_entity_decode($row['title'], ENT_QUOTES)) . '~~' . $row_pid['short_name']));
                } else {
                    write2log("Add Reply Error: The account is cancelled or not upgraded ::", $message, $mail_id, $header->subject, $header->date);
                }
            } else {
                $writeLog = 1;
                if ($msg != "" && !$user_id) {
                    $checkOSReg_qry = "SELECT count(*) as total FROM users WHERE email='" . $mail_id . "'";

                    $checkOSReg_res = mysql_query($checkOSReg_qry) or die('Query failed: ' . mysql_error());
                    $checkOSReg_row = mysql_fetch_assoc($checkOSReg_res);
                    if ($checkOSReg_row['total'] > 0) {
                        $userMsg = '<span>You do not have access to this Project on orangescrum.</span><br /><br />';
			$userMsg1 = "";
                    } else {
                        $userMsg = '<span>This email: &quot;' . $mail_id . '&quot; is not registered with Orangescrum.</span><br /><br />';
                        $userMsg1 = '<span>Note: <b>To post a reply to this task, please use your Orangescrum login Email ID</b>.</span><br /><br />';
                    }
		    
		    if(php_sapi_name() === "cli") {
			    
		    } else {
			echo $userMsg.$userMsg1;
		    }
			
                    $subject = "Failed to add a reply on Task# " . $row['case_no'] . " - " . stripslashes(html_entity_decode($row['title'], ENT_QUOTES));

                    $fMessage = '<table cellspacing="1" cellpadding="1" border="0" align="left" width="100%" bgcolor="#FFFFFF">
<tr bgcolor="#FFFFFF">
<td align="left">
<table cellpadding="0" cellspacing="0" align="left" border="0" width="100%" bgcolor="#FFFFFF">
<tr>
<td colspan="2">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="left" style="font:12px verdana;" valign="top">
<table border="0" valign="top" cellspacing="0" cellpadding="0">
<tr>
<td align="left" style="font:12px verdana;padding-top:0px;color:#585858">
Hi ' . $sender_name . ',
</td>	
</tr>
<tr>	  
<td align="left" style="font:12px Verdana;padding-top:8px;">
This is to notify you that, the email reply sent could not added to Task#: ' . $row['case_no'] . ' on project &quot;<b>' . $row_pid['name'] . '</b>&quot;.<br />
' . $userMsg . '

<span><b>Task#:</b> ' . $row['case_no'] . '</span><br />
<span><b>Title:</b> ' . stripslashes(html_entity_decode($row['title'], ENT_QUOTES)) . '</span><br /><br />

' . $userMsg1 . '

<span style="collor:#848484;">Regards,<br />
The Orangescrum Team</span>
<br /><br />
</td>	
</tr>

</table>
</td>
</tr>
</table>
</td>  
</tr>
<tr><td align="left" colspan="2">&nbsp;</td></tr>
<tr>
<td align="left" colspan="2" style="padding:5px 0px">
<hr style="border: none; height: 0.1em; color:#DBDBDB;background:#DBDBDB;"/>
</td>
</tr>
<tr>
<td align="left" style="font:9px Verdana;padding-top:2px;color:#737373" colspan="2">
You are receiving this email notification because you have subscribed to Orangescrum, to unsubscribe, please email with subject \'Unsubscribe\' to <a href="mailto:'.SUPPORT_EMAIL.'">'.SUPPORT_EMAIL.'</a>
</td>	  
</tr>
</table>
</td>
</tr>
</table>';
//echo $fMessage;die;
                    $mail_res = send_email($mail_id, $sender_name, $subject, $fMessage);
                    if ($mail_res) {
                        $writeLog = 0;
                    }
                }
                if (!$task_posted) {
                    write2log("Add Reply Error<br/><b>To: ".$mail_id."</b><br/><b>Project: ".$pj_sname."</b><br/>".$msg, $message, $mail_id, $header->subject, $header->date);
                }
            }
        }
    } //end of for loop
} else {//end of if statement
    print 'No unread mail found!';
}

/* close the connection */
imap_close($inbox);

function chnageUploadedFileName($filename) {
    $output = preg_replace('/[^(\x20-\x7F)]*/', '', $filename);
    $rep1 = str_replace("~", "_", $output);
    $rep2 = str_replace("!", "_", $rep1);
    $rep3 = str_replace("@", "_", $rep2);
    $rep4 = str_replace("#", "_", $rep3);
    $rep5 = str_replace("%", "_", $rep4);
    $rep6 = str_replace("^", "_", $rep5);
    $rep7 = str_replace("&", "_", $rep6);
    $rep11 = str_replace("+", "_", $rep7);
    $rep13 = str_replace("=", "_", $rep11);
    $rep14 = str_replace(":", "_", $rep13);
    $rep15 = str_replace("|", "_", $rep14);
    $rep16 = str_replace("\"", "_", $rep15);
    $rep17 = str_replace("?", "_", $rep16);
    $rep18 = str_replace(",", "_", $rep17);
    $rep19 = str_replace("'", "_", $rep18);
    $rep20 = str_replace("$", "_", $rep19);
    $rep21 = str_replace(";", "_", $rep20);
    $rep22 = str_replace("`", "_", $rep21);
    $rep23 = str_replace(" ", "_", $rep22);
    $rep28 = str_replace("/", "_", $rep23);
    $rep29 = str_replace("?", "_", $rep28);
    $rep30 = str_replace("?", "_", $rep29);
    return $rep30;
}

function send_email($to, $name, $subject, $message) {
	
	if(define(SMTP_PWORD) && SMTP_PWORD != "******") {
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = SMTP_HOST;
		$mail->Port = SMTP_PORT;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = SMTP_UNAME;
		$mail->Password = SMTP_PWORD;
		
		$mail->setFrom(SUPPORT_EMAIL, '');
		$mail->addAddress($to, '');
		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;
		
		$res = $mail->send();
	}
}

function curlPostData($url, $data) {
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));

    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}

function getEmailMsg($header, $body, $user_detail=null,$chk_mbyt = null) {	
    $fromEmail = $header->from['0']->mailbox . "@" . $header->from['0']->host; //mail id of sender
	$fromNamenext = $header->from['0']->mailbox; //name of sender
	$user_detailRegex = '';
	$notifyRegex = '';	
    if ($fromNamenext) {
        $fromNamenextRegex = "/^On(.*)$fromNamenext(.*)/i";
    }
    $fromName = isset($header->from['0']->personal) ? decodeMimeStr($header->from['0']->personal) : null; //name of sender
    if ($fromName) {
        $frmNmRegex = "/^On(.*)$fromName(.*)/i";
    }
    $toEmail = $header->to['0']->mailbox . "@" . $header->to['0']->host; //to mail id
    $toName = isset($header->to['0']->personal) ? decodeMimeStr($header->to['0']->personal) : null; //to name
    if ($toName) {
        $toNmRegex = "/^On(.*)$toName(.*)/i";
    }

//get rid of any quoted text in the email body
    if (strpos($header->message_id, "blackberry")) {
        $msgpos = strpos($body, "Sent from");
        $msg = substr($body, 0, $msgpos - 3);
    } else {
        $body_array = explode("\n", $body);
        @$patterns = array(
            "/^_________________________________________________________________$/", //remove hotmail sig
            "/^-*(.*)Original Message(.*)-*/i", //original message quote
            "/^On(.*)wrote:(.*)/i", //check for date wrote string
            $frmNmRegex, //check for From Name email section
			$fromNamenextRegex,//check for From Name email section possibly
            $toNmRegex, //check for To Name email section
            "/^(.*)$toEmail(.*)wrote:(.*)/i", //check for To Email email section
            "/^(.*)$fromEmail(.*)wrote:(.*)/i", //check for From Email email section
            "/^>(.*)/i", //check for quoted ">" section
            "/^---(.*)On(.*)wrote:(.*)/i"//check for date wrote string with dashes
        );
		if($user_detail && !empty($user_detail)){
			foreach($user_detail as $uk => $uv){
				$user_detail_temp = $uv['name']. ' '. $uv['last_name'];
				$user_detailRegex = "/^On(.*)$user_detail_temp(.*)/i";
				array_splice($patterns, 3, 0, $user_detailRegex);
			}
		}
        $message = "";
        foreach ($body_array as $key => $value) {
			if($chk_mbyt){
				$value = strip_tags($value, '<br><li><ul><ol><u><i><p><span>');
				if(stristr($value,'Just REPLY to this Email the same will be added under the Task')){
					$value_t = explode('Just REPLY to this Email',$value);
					$value = trim($value_t[0]);
				}
			}
            foreach ($patterns as $pattern) {
                if (trim($pattern) && preg_match($pattern, $value, $matches)) {
                    break 2;
                }
            }
			if(stristr($value,'Just REPLY to this Email the same will be added under the Task')){		
			}else{
            $message .= "$value\n"; //add line to body
        }
        }
		if(stristr($message,'*De :*')){
			$t_message = explode('*De :*',$message);
			$message = $t_message[0];
		}
        $msg = $message; // = str_replace("*", "", $message);
        $msgpos = '';

        if (strpos($message, "Sent from")) {
            if (strpos($message, "Sent from my iPhone")) {
                $msgpos = strpos($message, "Sent from my iPhone");
                $msg = substr($message, 0, $msgpos);
            } else {
                $msgpos = strpos($message, "Sent from");
                $msg = substr($message, 0, $msgpos);
            }
        }
        if (strpos($message, "Content-Transfer-Encoding: quoted-printable") && trim(@$msg) == "") {
            if (strpos($message, $cmpmsg)) {
                $msgpos = strpos($message, $cmpmsg);
                $msg = substr($message, 0, $msgpos);
            }
            if (strpos(@$msg, "Content-Type: text/plain; charset=ISO-8859-1")) {
                $msgposion = strpos($msg, "Content-Type: text/plain; charset=ISO-8859-1");
                $msg = substr($msg, $msgposion + 44);
            } else {
                $msgpos = strpos($message, "Content-Transfer-Encoding: quoted-printable");
                $msg = substr($message, $msgpos + 43);
                $msgpos1 = strpos($msg, "Original Message");
                $msgpos1 = $msgpos1 - 5;
                $msg = substr($msg, 0, $msgpos1);
                $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
                $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
                $msg = str_replace($carimap, $carhtml, $msg);
            }
        } else if (strpos($message, "Original Message") && trim(@$msg) == "") {
            $msgpos2 = strpos($message, "Original Message");
            $msgpos2 = $msgpos2 - 5;
            $msg = substr($message, 0, $msgpos2);
            $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
            $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
            $msg = str_replace($carimap, $carhtml, $msg);
        }
        if (strpos($message, "From:") && strpos($message, "Sent:") && strpos($message, "To:") && strpos($message, "Subject:") && trim(@$msg) == "") {
            $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
            $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
            $message = str_replace($carimap, $carhtml, $message);
            $msgpos = strpos($message, "From:");
            $msgpos = $msgpos - 5;
            $msg = substr($message, 0, $msgpos);
            $msg1 = substr($message, 0, $msgpos);
            if (strpos($msg, "Content-Transfer-Encoding: quoted-printable")) {
                $msgepos = strpos($msg, "Content-Transfer-Encoding: quoted-printable");
                $msg = substr($msg, $msgepos + 43);
            }
            if (strpos($message, "Content-Transfer-Encoding: quoted-printable") && !strpos($msg1, "Content-Transfer-Encoding: quoted-printable")) {
                $msgpos = strpos($message, "Content-Transfer-Encoding: quoted-printable");
                $msg = substr($message, $msgpos + 43);
                $msgpos1 = strpos($msg, "Original Message");
                $msgpos1 = $msgpos1 - 5;
                $msg = substr($msg, 0, $msgpos1);
                $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
                $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
                $msg = str_replace($carimap, $carhtml, $msg);
            }
            if (strpos($message, "Original Message")) {
                $msgpos2 = strpos($message, "Original Message");
                $msgpos2 = $msgpos2 - 6;
                $msg = substr($message, 0, $msgpos2);
                $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
                $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
                $msg = str_replace($carimap, $carhtml, $msg);
            }
        }
    }
    if (strpos($msg, "Content-Type: text/html; charset=UTF-8")) {
        $stpos = strpos($msg, "Content-Type: text/html; charset=UTF-8") + 71;
        $msg = substr($msg, $stpos);
    }
    if (strpos($msg, "Content-Transfer-Encoding: 7bit")) {
        $stpos = strpos($msg, "Content-Transfer-Encoding: 7bit") + 32;
        $msg = substr($msg, $stpos);
    }
    if (strpos(@$msg, "Content-Type: text/plain; charset=ISO-8859-1")) {
        $msgposion = strpos($msg, "Content-Type: text/plain; charset=ISO-8859-1");
        $msg = substr($msg, $msgposion + 44);
    }
//echo $msg;exit;
    if (strpos($msg, "\r\n\r\n\r\n\r\n\r\n\r\n")) {
        $msg = str_replace("\r\n\r\n\r\n\r\n\r\n\r\n", "\n", $msg);
    }
    if (strpos($msg, "\r\n\r\n\r\n\r\n\r\n")) {
        $msg = str_replace("\r\n\r\n\r\n\r\n\r\n", "\n", $msg);
    }
    if (strpos($msg, "\r\n\r\n\r\n\r\n")) {
        $msg = str_replace("\r\n\r\n\r\n\r\n", "\n", $msg);
    }
    if (strpos($msg, "\r\n\r\n\r\n")) {
        $msg = str_replace("\r\n\r\n\r\n", "\n", $msg);
    }
    if (strpos($msg, "\r\n\r\n")) {
        $msg = str_replace("\r\n\r\n", "\n", $msg);
    }
    $strim = array("/**/", "/*", "*/", "**", "*", "=A0", "=", "<br />");
    @$msg = str_replace($strim, "", $msg);
    if (strpos(@$msg, FROM_EMAIL_NOTIFY)) {
        $msg = substr($msg, 0, strpos($msg, "From:"));
    }

    $msg = strip_tags($msg);
    $msg = nl2br(trim($msg));

    return trim($msg);
}

function decodeMimeStr($string, $charset = 'UTF-8') {
    $newString = '';
    $elements = imap_mime_header_decode($string);
    for ($i = 0; $i < count($elements); $i++) {
        if ($elements[$i]->charset == 'default') {
            $elements[$i]->charset = 'iso-8859-1';
        }
        $newString .= iconv($elements[$i]->charset, $charset, $elements[$i]->text);
    }
    return $newString;
}

function convert_ascii($string) {
// Replace Single Curly Quotes
    $search[] = chr(226) . chr(128) . chr(152);
    $replace[] = "'";
    $search[] = chr(226) . chr(128) . chr(153);
    $replace[] = "'";

// Replace Smart Double Curly Quotes
    $search[] = chr(226) . chr(128) . chr(156);
    $replace[] = '"';
    $search[] = chr(226) . chr(128) . chr(157);
    $replace[] = '"';

// Replace En Dash
    $search[] = chr(226) . chr(128) . chr(147);
    $replace[] = '--';

// Replace Em Dash
    $search[] = chr(226) . chr(128) . chr(148);
    $replace[] = '---';

// Replace Bullet
    $search[] = chr(226) . chr(128) . chr(162);
    $replace[] = '*';

// Replace Middle Dot
    $search[] = chr(194) . chr(183);
    $replace[] = '*';

// Replace Ellipsis with three consecutive dots
    $search[] = chr(226) . chr(128) . chr(166);
    $replace[] = '...';

// Apply Replacements
    $string = str_replace($search, $replace, $string);

// Remove any non-ASCII Characters
    $string = preg_replace("/[^\x01-\x7F]/", "", $string);

    return $string;
}

function write2log($why, $message, $mail_id, $subject, $date) {
    $log = "";
    $myFile = LOG_PATH;
    $flog = fopen($myFile, 'a');
    $log.="Failed to update:from mail ID-" . $mail_id . "\n" . "Subject:" . $subject . "\n" . "Sent Date:" . $date . "\n" . "-----------------------------------------------------------------------" . "\n";
    fwrite($flog, $log);
    fclose($flog);
    $message = "Subject: " . $subject . "<br />Sent Date: " . $date . "<br />" . $why . "<br />" . "-----------------------------------------------------------------------<br /><br />" . nl2br($message);
    if(php_sapi_name() === "cli") {

    } else {
	echo $message . "<br/>";
    }
    send_email(DEV_EMAIL, '', "Failed to save Email reply in Orangescrum", $message);
}

//socket.io implement start
function iotoserver($messageArr) {
    if (defined('NODEJS_HOST') && trim(NODEJS_HOST)) {
        try {
            $elephant = new ElephantIOClient(NODEJS_HOST, 'socket.io', 1, false, false, true);
            $elephant->setHandshakeTimeout(1000);
            $elephant->init();
            $elephant->send(
                    ElephantIOClient::TYPE_EVENT, null, null, json_encode(array('name' => 'iotoserver', 'args' => $messageArr))
            );
            $elephant->close();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
//socket.io implement end
?>

<?php
App::import('Component', 'Email');
class SendgridComponent extends EmailComponent
{
	public $components = array('Session','Email', 'Cookie','Format');
	
	function sendGridEmail($from,$to,$subject,$message,$type,$fromname=NULL)
	{
		
		App::import('helper', 'Format');
		$frmtHlpr = new FormatHelper(new View(null));

		$to = $frmtHlpr->emailText($to);
		$subject = $frmtHlpr->emailText($subject);
		$message = $frmtHlpr->emailText($message);

		$message = str_replace("<script>","&lt;script&gt;",$message);
		$message = str_replace("</script>","&lt;/script&gt;",$message);
		$message = str_replace("<SCRIPT>","&lt;script&gt;",$message);
		$message = str_replace("</SCRIPT>","&lt;/script&gt;",$message);
		$message = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $message);
		
		if(SENDGRID_USERNAME == "") {
			sendEmail($from,$to,$subject,$message,$type);
		}
		
		$url = 'http://sendgrid.com/';

		$params = array(
				'api_user'  => SENDGRID_USERNAME,
				'api_key'   => SENDGRID_PASSWORD,
				'to'        => $to,
				'subject'   => $subject,
				'html'      => $message,
				'text'      => '',
				'from'      => $from,
				'fromname'  => $fromname,
		  );
		  // From email is not valid with space.

		$request =  $url.'api/mail.send.json';
		$session = curl_init($request);
		curl_setopt ($session, CURLOPT_POST, true);
		curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($session, CURLOPT_TIMEOUT, 1);
		$response = curl_exec($session);
		curl_close($session);
		return true;
		
	}
	function sendEmail($from,$to,$subject,$message,$type)
	{
            App::import('helper', 'Format');
            $frmtHlpr = new FormatHelper(new View(null));

            $to = $frmtHlpr->emailText($to);
            $subject = $frmtHlpr->emailText($subject);
            $message = $frmtHlpr->emailText($message);

            $message = str_replace("<script>","&lt;script&gt;",$message);
            $message = str_replace("</script>","&lt;/script&gt;",$message);
            $message = str_replace("<SCRIPT>","&lt;script&gt;",$message);
            $message = str_replace("</SCRIPT>","&lt;/script&gt;",$message);
            $message = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $message);

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers.= 'From:' .$from."\r\n";

            if(mail($to,$subject,$message,$headers)) {
                    return true;
            }
	}
	function sendgridsmtp($email){
		
		if(SENDGRID_USERNAME == "") {
			return true;
		}
		
		try{
			$email->smtpOptions = array(
                                'port'=>'587',
                                'host' => 'smtp.sendgrid.net',
                                'username'=>SENDGRID_USERNAME,
                                'password'=>SENDGRID_PASSWORD,
                              );
                        
			$response = $email->send();
			return $response;
                        
		}Catch (Exception $e){
			$fp = fopen(ROOT."/app/tmp/logs/email_exception.log", "a+");
			fwrite($fp,"\n\n".date('m-d-Y H:i:s')." \n\t Exception Message: ".$e->getMessage()."  \n\t To Email:".$email->to." \t Email Subject:-".$email->subject." \t For Company id=".SES_COMP." User id=".SES_ID);
			fclose($fp);
			return false;
		}
	}	
	
	function sub_sendgrid($from = NULL,$to,$subject,$message,$file = NULL,$cc='',$bcc='') {
			if(SENDGRID_USERNAME == "") {
				return true;
			}
			if(!$from) {
				$from = SUPPORT_EMAIL;
			}
			$url = 'http://sendgrid.com/';

			$filePath = DIR_IMAGES."pdf";
			
			$params = array(
				'api_user'  => SENDGRID_USERNAME,
				'api_key'   => SENDGRID_PASSWORD,
				//'to'        => $to,
				'subject'   => $subject,
				'html'      => $message,
				'text'      => '',
				'from'      => $from
			  );
			if($file){
				$params['files['.$file.']'] = '@'.$filePath.'/'.$file;
			}
			if($cc){
				$params['to[0]'] = $to;
				if(strstr($cc, ',')){
					$cc = explode(',', $cc);
					
					foreach($cc as $key=>$val){
						$params['to['.($key+1).']'] = $val;
					}
				} else {
					$params['to[1]'] = $cc;
				}
			}else{
				$params['to'] = $to;
			}
			if($bcc){
				$params['to[0]'] = $to;
				if(strstr($bcc, ',')){
					$bcc = explode(',', $bcc);
					foreach($bcc as $k=>$v){
						$params['bcc['.$k.']'] = $v;
					}
				} else {
					$params['bcc'] = $bcc;
				}
			}
			//echo "<pre>";print_r($params);exit;
			// From email is not valid with space.
			$request =  $url.'api/mail.send.json';
			$session = curl_init($request);
			curl_setopt ($session, CURLOPT_POST, true);
			curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($session, CURLOPT_TIMEOUT, 1);
			$response = curl_exec($session);
			curl_close($session);
			
			return $response;
	}
}
?>

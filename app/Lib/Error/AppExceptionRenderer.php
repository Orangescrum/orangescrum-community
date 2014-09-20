<?php
App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {
    public function notFound($error) {
        $this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));
    }
/**
 * Convenience method to display a PDOException.
 *
 * @param PDOException $error
 * @return void
 */
	public function pdoError(PDOException $error) {	
		$url = $this->controller->request->here();
		$code = 500;
		$this->controller->response->statusCode($code);
		$this->controller->set(array(
			'code' => $code,
			'url' => h($url),
			'name' => $error->getMessage(),
			'error' => $error,
			'_serialize' => array('code', 'url', 'name', 'error')
		));
		$message = "<table cellpadding='1' cellspacing='1' align='left' width='100%'>
					<tr><td>&nbsp;</td></tr>
					<tr><td align='left' style='font-family:Arial;font-size:14px;'>Hi, </td></tr>
					<tr><td align='left' style='font-family:Arial;font-size:14px;'>A user is trying to do an activity on OS but not able to proceed due to below error </td></tr>
					<tr><td align='left' style='font-family:Arial;font-size:14px;'>&nbsp;</td></tr>	   
					<tr><td align='left' style='font-family:Arial;font-size:14px;'><font color='#EE0000;'>".$error->getMessage()."</font> </td></tr>	   
					<tr><td align='left' style='font-family:Arial;font-size:14px;'>&nbsp;</td></tr>
					<tr><td align='left' style='font-family:Arial;font-size:14px;'><b>Domain:</b> ".HTTP_ROOT."</td></tr>
					<tr><td align='left' style='font-family:Arial;font-size:14px;'><b>ERROR URL:</b> ".h($url)."</td></tr>
					<tr height='25px'><td>&nbsp;</td></tr></table>";
			$subject = "DATABASE ERROR";
			$this->alert_sendemail(SUPPORT_EMAIL,TO_DEV_EMAIL,$subject,$message,"",DEV_EMAIL,'');
			$this->_outputMessage($this->template);
	}
/**
 * @method private alert_sendemail($message) Description
 * @author GDR<support@ornagescrum.com>
 * @return bool true/fals
 */
	function alert_sendemail($from = NULL,$to,$subject,$message,$file = NULL,$cc='',$bcc=''){
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

			//'files['.$f.']' => '@'.$logfile,
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
			$request =  $url.'api/mail.send.json';
			$session = curl_init($request);
			curl_setopt ($session, CURLOPT_POST, true);
			curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($session);
			curl_close($session);
			return $response;
	}		
}
?>
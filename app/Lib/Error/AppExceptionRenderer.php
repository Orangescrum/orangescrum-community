<?php
App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {
	public $components = array('Auth','Session','Email', 'Cookie','Postcase');
	 
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
			
			$this->Postcase->sendGridEmail(SUPPORT_EMAIL,DEV_EMAIL,$subject,$message,'Exception');
			$this->_outputMessage($this->template);
	}	
}
?>
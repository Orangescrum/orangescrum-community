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
		
        $this->Email->delivery = EMAIL_DELIVERY;
		$this->Email->to = $to;
		$this->Email->replyTo = $from;
		$this->Email->subject = $subject;
		if(trim($fromname)) {
			$this->Email->from = $fromname."<".$from.">";
		}
		else {
			$this->Email->from = $from;
		}
		$this->Email->sendAs = 'html';
        if (EMAIL_DELIVERY == 'smtp') {
            if (defined('SMTP_UNAME') && defined('SMTP_PWORD') && SMTP_PWORD !== "******") {
			$email_array = array(
				'port' => SMTP_PORT,
				'host' => SMTP_HOST,
				'username' => SMTP_UNAME,
				'password' => SMTP_PWORD,
                    'timeout' => '30',
				'client' => WEB_DOMAIN
			);
            } else {
			$email_array = array(
				'port' => SMTP_PORT,
                    'host' => SMTP_HOST
			);
		}
		$this->Email->smtpOptions = $email_array;
        }
		$response = $this->Email->send($message);
		return $response;
	}

	function sendgridsmtp($email){
		$email->replyTo = FROM_EMAIL;
        if (EMAIL_DELIVERY == 'smtp') {
            if (defined('SMTP_UNAME') && defined('SMTP_PWORD') && SMTP_PWORD !== "******") {
			$email_array = array(
				'port' => SMTP_PORT,
				'host' => SMTP_HOST,
				'username' => SMTP_UNAME,
				'password' => SMTP_PWORD,
                    'timeout' => '30',
				'client' => WEB_DOMAIN
			);
            } else {
			$email_array = array(
				'port' => SMTP_PORT,
                    'host' => SMTP_HOST
			);
		}

		$email->smtpOptions = $email_array;
        }
		$response = $email->send();
		return $response;
	}	

    function sendEmail($from, $to, $subject, $message, $type) {
            App::import('helper', 'Format');
            $frmtHlpr = new FormatHelper(new View(null));

            $to = $frmtHlpr->emailText($to);
            $subject = $frmtHlpr->emailText($subject);
            $message = $frmtHlpr->emailText($message);

        $message = str_replace("<script>", "&lt;script&gt;", $message);
        $message = str_replace("</script>", "&lt;/script&gt;", $message);
        $message = str_replace("<SCRIPT>", "&lt;script&gt;", $message);
        $message = str_replace("</SCRIPT>", "&lt;/script&gt;", $message);
        $message = preg_replace('/[^(\x20-\x7F)\x0A]*/', '', $message);

        $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers.= 'From:' . $from . "\r\n";

        if (mail($to, $subject, $message, $headers)) {
                return true;
            }
	}

}
?>

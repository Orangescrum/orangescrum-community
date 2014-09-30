<body style="width:100%; margin:0; padding:0; -webkit-text-size-adjust:none; -ms-text-size-adjust:none; background-color:#ffffff;">
<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="height:auto !important; margin:0; padding:0; width:100% !important; background-color:#F0F0F0;color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:19px; margin-top:0; padding:0; font-weight:normal;">
	<tr>
		<td>
        <div id="tablewrap" style="width:100% !important; max-width:600px !important; text-align:center; margin:0 auto;">
		      <table id="contenttable" width="600" align="center" cellpadding="0" cellspacing="0" border="0" style="background-color:#FFFFFF; margin:0 auto; text-align:center; border:none; width: 100% !important; max-width:600px !important;border-top:8px solid #5191BD">
            <tr>
                <td width="100%">
                   <table bgcolor="#FFF" border="0" cellspacing="0" cellpadding="20" width="100%">
                        <tr>
                            <td width="100%" bgcolor="#FFF" style="text-align:left;">
                            	<p>
                                    Hi <?php echo $name;?>,                    
                                </p>
								
								<p>We have received your request to reset password.</p>
								
								<p>To reset, please click the button below.</p>
								
								<a style="font-weight:bold; text-decoration:none;" href="<?php echo HTTP_ROOT.'users/forgotpassword/'.$urlValue;?>" target='_blank'><div style="display:block; max-width:100% !important; width:auto !important;margin:auto; height:auto !important;background-color:#0EA426;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;border-radius:10px;color:#ffffff;font-size:16px;text-align:center">Go Ahead!</div></a>
									
								
                                <br/>
								
								
								<p>Regards,<br/>
								The Orangescrum Team</p>
                            </td>
                        </tr>
                   </table>
                  
                   <table bgcolor="#F0F0F0" border="0" cellspacing="0" cellpadding="10" width="100%" style="border-top:2px solid #F0F0F0;margin-top:10px;border-bottom:3px solid #2489B3">
                        <tr>
                            <td width="100%" bgcolor="#ffffff" style="text-align:center;">
                            	<p style="color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:14px; margin-top:0; padding:0; font-weight:normal;padding-top:5px;">
									<?php echo NEW_EMAIL_FOOTER; ?>

									You are receiving this email notification because you have subscribed to Orangescrum, to unsubscribe, please email with subject 'Unsubscribe' to <a href='mailto:support@orangescrum.com'>support@orangescrum.com</a>
									
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </div>
		</td>
	</tr>
</table> 
</body>


<?php /*?><table style="padding-top:20px;margin:0 auto;text-align:left;width:100%">
  <tbody>
  	<?php echo EMAIL_HEADER;?>
    <tr>
      <td>
	<div style="color:#000;font-family:Arial;font-size:14px;line-height:1.8em;text-align:left;padding-top: 10px;">
		<p style="display:block;margin:0 0 17px">Hi <?php echo $expName;?>,</p>
		<p>
			<?php echo $invitationMsg;?>
		</p>
		<p>
			<div>Please click on the link below to confirm.</div>
			<div><a href="<?php echo HTTP_ROOT.'users/invitation/'.$qstr;?>" target='_blank'><?php echo HTTP_ROOT."users/invitation/".$qstr; ?></a></div>
		</p>
		<p style="display:block;margin:0">
			Regards,<br/>
			The Orangescrum Team
		</p>				
	</div>
      </td>
    </tr>
    	<?php echo Configure::read('invite_user_footer');?>
    	<?php if(!empty($existing_user)){ ?>
    		<?php echo $existing_user;?>
		<?php echo Configure::read('common_footer');?>
	<?php } ?>
  </tbody>
</table><?php */?>



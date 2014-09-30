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
                                    Hi Admin,                    
                                </p>
				<p> Please find the detail</p>
				<p>User name: <?php echo $userdetails['User']['name'];?> </p>
				<p>Company: <?php echo $userdetails['Company']['name'];?> </p>				
				<p>Fie name: <?php echo $f_name;?> </p>
				<p>Fie type: <?php echo $f_type;?> </p>
				<p>Fie size: <?php echo $f_size;?> </p>				
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
									<?php echo NEW_EMAIL_FOOTER;
									
									if($existing_user)
									{
									?>
										You are receiving this email notification because you have subscribed to Orangescrum, to unsubscribe, please email with subject 'Unsubscribe' to <a href='mailto:support@orangescrum.com'>support@orangescrum.com</a>
									<?php
									}
									else {
									?>
										Your email address is used to invite you on Orangescrum. If you didn't intend to do this, just ignore this email; no account has been created yet.
									<?php
									}
									?>
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
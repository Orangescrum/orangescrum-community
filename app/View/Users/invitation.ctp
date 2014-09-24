
<!--[if lt IE 10]>
	<style type="text/css">
		.lbl_ie{font-family: myriadpro-regular;font-size:18px;margin:0px 0px 5px;color:#636363;display:block}
		.login_box{border:1px solid #ccc}
		input#pas_new, input#pas_retype{font-family: Arial}
	</style>
<![endif]-->

<script>
$(document).ready(function()
{			 
	$('#name').focus();
});
</script>

<?php
if($AuthId) {
	echo "<div style='color:#FF0000'>Please logout from Orangescrum and click on the same button from your Email to accept the Invitation.</div>";
}
else {
?>

<div class="top_m_cont_land">    
    <div class="wrapper_new">
    	<div style="display:table-cell; height:100%; min-height:100%; vertical-align:middle">
        	<div style="position:relative">
            	<div class="bg_logo_inner"></div>
                <div class="logo_landing"> 
                        <a href="<?php echo HTTP_HOME; ?>"><img src="<?php echo HTTP_ROOT; ?>img/images/logo_outer.png"  border="0" alt="Orangescrum.com" title="Orangescrum.com"/></a>
                </div>
    <div class="login_table">
        <div <?php if(PAGE_NAME == "home") { ?>class="home" <?php } else { ?>class="home_other" <?php } ?> style="height:100%;display:table">
            <!--<td style="vertical-align:middle; height:100%">-->
                <div id="container" style="display:table-cell; vertical-align:middle">
                    <!--<tr>
                        <td align="left" style="vertical-align:middle; height:100%; padding-top:25px">-->
                            <div class="demo_login_bg_l">
								<br/>
                            <!--<div style="text-align:left;"><a href="<?php echo HTTP_ROOT; ?>"><img src="<?php echo HTTP_ROOT; ?>img/images/os_logo_white.png"></a></div>-->
                                <!--<div style="float:right; width: 50%;" class="login_dtls">
                                    
                                     <ul style="padding-top:0px;margin:20px;line-height:38px; margin-top:5px;margin-left:50px; color:#fff; font-weight:bold">
                                        <li>
                                            Search & track a task in no time.
                                            
                                        </li>
                                        <li>
                                            Add a task in a flash!
                                        </li>
                                        <li>
                                            Watch all activites happening.
                                        </li>
                                        <li>
                                            Easy to collaborate with development team.
                                        </li>
                                        <li>
                                            Automatic email notification.
                                        </li>
                                        <li>
                                            Keep & share documents (.pdf, .docx, .xlsx, .jpeg).
                                        </li>
                                        <li>
                                            View milestones (due, overdue, upcoming).
                                        </li>
                                        <li>
                                            Assign Task to resources.
                                        </li>
                                    </ul>
                                </div>-->
                                    <div class="login_box">
                                        <div class="login_dialog" id="login_dialog">
											<?php echo $this->Form->create('User',array('url'=>'/users/invitation/'.$qstr,'onsubmit'=>'return validateSignUp()','autocomplete'=>'off')); ?>
											<div id="divide"></div>
												<div class="join_txt"><h2>Easy Sign Up</h2></div>
                                                <div class="join_txt"><h2><font color="#DD5227"><?php echo $company_name; ?></font></h2></div>
                                                        
                                                        <div style="width:100%"><img src="<?php echo HTTP_ROOT; ?>img/images/login_header_shadow.png" style="width:97%"/></div>
                                                    <div style="height:10px"></div>
                           
                            <span><div class="join_txt"><b>Email:</b> <span class="join_txt" style="color:#000"><?php echo $this->Format->formatText($email)?></span></div></span>
                        
                             <div style="margin-top:12px"></div>
							  <span id="err_signup" style="text-align:center;color:#FF0000;padding-bottom:10px;"></span>
                            
                            <!--<h2 style="font-weight:normal;color:#8a8a8a;font-weight:bold" class="sign_lable"><b>First Name:</b></h2>-->
    
                      <!-- <?php echo $this->Form->text('name',array('size'=>'45','id'=>'name','maxlength'=>'100','autocomplete'=>'off')); ?>-->
					  <label class="lbl_ie">Name</label>
                        <?php echo $this->Form->text('name',array('size'=>'45','maxlength'=>'100','autocomplete'=>'off','placeholder'=>'Name', 'id'=>'name', 'style'=>'background:#fff')); ?>                       
			<input type="hidden" value="<?php echo $email;?>" name="invited_email" id="invited_email"/>
                        <div class="gap10"></div>                       
                           
                            <div style="margin-top:5px">
                       <!--<label class="lbl_ie">Last Name</label>
		       <?php echo $this->Form->text('last_name',array('size'=>'45','maxlength'=>'100','autocomplete'=>'off','placeholder'=>'Last Name', 'id'=>'last_name', 'style'=>'background:#fff')); ?>
                            <div class="gap10"></div> -->      
                           
                            <!--<h2 style="font-weight:normal;color:#8a8a8a;font-weight:bold" class="sign_lable"><b>Password:</b></h2>-->
                        <!--<?php echo $this->Form->password('password',array('id'=>'pas_new','size'=>'32','maxlength'=>'15','onKeyPress'=>'return noSpace(event)','autocomplete'=>'off')); ?>-->
                       <label class="lbl_ie">Password</label>
					   <?php echo $this->Form->password('password',array('size'=>'45','size'=>'32','maxlength'=>'15','onKeyPress'=>'return noSpace(event)','autocomplete'=>'off','placeholder'=>'Password', 'id'=>'pas_new', 'style'=>'background:#fff')); ?>
                        
                         <div class="gap10"></div>      
                            <!--<h2 style="font-weight:normal;color:#8a8a8a;font-weight:bold" class="sign_lable"><b>Confirm Password:</b></h2>-->
                       <!-- <?php echo $this->Form->password('pas_retype',array('id'=>'pas_retype','size'=>'32','maxlength'=>'15','onKeyPress'=>'return noSpace(event)','autocomplete'=>'off')); ?>-->
                        <label class="lbl_ie">Confirm Password</label>
                         <?php echo $this->Form->password('pas_retype',array('size'=>'45','size'=>'32','maxlength'=>'15','onKeyPress'=>'return noSpace(event)','autocomplete'=>'off','placeholder'=>'Confirm Password', 'id'=>'pas_retype', 'style'=>'background:#fff')); ?>                        
                         <div class="gap10"></div>      
                        <?php
                        if($qstr)
                        {
                        ?>
                         <div style="margin-top:5px">&nbsp;</div>
                              <input type="hidden" name="data[User][timezone_id]" id="timezone_id" value="">
                            <input type="hidden" name="data[User][qstr]" id="hidId" value="<?php echo $qstr; ?>" readonly="true"/>
                            <span id="signupbtn">
                                <!--<button type="submit" name="submit_SignUp" id="submit_SignUp" value="Sign Up" style="width:95px; cursor:pointer;padding:1px 10px; height:26px">Sign Up</button>-->
                                <button type="submit" name="submit_SignUp" id="submit_SignUp" value="Sign Up" class="btn btn_blue">Sign Up</button>
                            </span>
                            <span id="signupload" style="display:none;"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..." width="16" height="16"/></span>
                       
                        <?php
                        }
                        else
                        {
                        ?>
                       
                            <div style="margin-top:5px;" align="center">
                                <span style="font-size:10px">You have already Sign Up! </span><a href="<?php echo HTTP_ROOT; ?>users/login/" style="color:#E8572A;">LogIn</a><br />
                                <font color="#000000">or</font><br />
                           <span style="font-size:10px">Click on activation link and try again.</span>
                           </div>         
                        <?php
                        }
                        ?>
                  
                        </form>
                                          </div>
                                    </div>        
                                </div>
                </div>
                 <div class="cb"></div>
            </div>
       
     </div>
   </div>

</div> </div></div></div>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.min.1.5.1.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.pstrength-min.1.2.js"></script>
<script>
$(document).ready(function() 
{
	$('#pas_new').pstrength();
     var visitortime = new Date();
	var visitortimezone = -visitortime.getTimezoneOffset()/60;
	//alert(visitortimezone);
	$('#timezone_id').val(visitortimezone);
});


function validateSignUp()
{	
	var name = document.getElementById('name');
	var last_name = document.getElementById('last_name');
	var pass_new = document.getElementById('pas_new');
	var pas_retype = document.getElementById('pas_retype');
	var errMsg; var done = 1;
	if(name.value.trim() == "")
	{
		errMsg = "First Name cannot be  blank!";
		name.focus();
		done = 0;
	}
	else if(last_name.value.trim() == "")
	{
		errMsg = "Last Name cannot be  blank!";
		last_name.focus();
		done = 0;
	}
	else if(pass_new.value.trim() == "")
	{
		errMsg = "Password cannot be  blank!";
		pass_new.focus();
		done = 0;
	}
	else if(pass_new.value.length < 6 || pass_new.value.length > 15)
	{
		errMsg = "Password should be between 6-15 characters!";
		pass_new.focus();
		done = 0;
	}
	else if(pas_retype.value.trim() == "")
	{
		errMsg = "Confirm Password cannot be  blank!";
		pas_retype.focus();
		done = 0;
	}
	else if(pas_retype.value != pass_new.value)
	{
		errMsg = "Password fields do not match!";
		pas_retype.focus();
		done = 0;
	}
	if(done == 0)
	{
		document.getElementById('err_signup').style.display='block';
		document.getElementById('err_signup').innerHTML=errMsg;
		return false;
	}
	else
	{
		document.getElementById('err_signup').style.display='none';
		document.getElementById('signupbtn').style.display='none';
		document.getElementById('signupload').style.display='block';
		return true;
	}
}
function noSpace(e)
{
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if(unicode != 8 ) {
        if(unicode == 32) {
            return false;
        }
        else {
            return true;
        }
    }
    else {
        return true;
    }
}
</script>
<?php
}
?>
<!--[if lt IE 10]>
	<style type="text/css">
		.lbl_ie{font-family: myriadpro-regular;font-size:18px;margin:10px 20px -8px;color:#636363;display:block}
		.login_box{border:1px solid #ccc}
	</style>
<![endif]-->
<?php if(isset($passemail) && !empty($passemail)){}else{$passemail='10';}?>
<?php if(isset($chkemail) && !empty($chkemail)){$chkemail='10';}else{$chkemail='11';}?>
<div class="top_m_cont_land">
    <div id="wrapper">
    	<div style="display:table-cell; height:100%; min-height:100%; vertical-align:middle">
        	<div style="position:relative">
            	<div class="bg_logo_inner"></div>
                <div class="logo_landing"> 
                    <a href="<?php echo HTTPS_HOME; ?>"><img src="<?php echo HTTP_ROOT; ?>img/images/logo_outer.png?v=<?php echo RELEASE; ?>"  border="0" alt="Orangescrum.com" title="Orangescrum.com"/></a>
                </div>
                <div class="login_box">
                    <div class="login_dialog" id="login_dialog">
        
                        <table border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <?php if($chkemail=="11" && $passemail=="10"){?>
                                    <?php echo $this->Form->create('User',array('url'=>'/users/forgotpassword','onsubmit'=>'return validpwd(\'email\')')); ?>
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="width:100%; vertical-align:middle">
                                                    <h2>Forgot your password?</h2>
                                                    <div><img src="<?php echo HTTP_ROOT; ?>img/images/login_header_shadow.png?v=<?php echo RELEASE; ?>" width="460" height="8"/></div>
                                                </td>
                                            </tr> 
                                            <tr>
                                              <td>
                                              	<h6>To reset your password, type the full email address you use to sign in to your Orangescrum Account.</h6>
                                              </td>
                                            </tr>
                                                <td align="center">
                                                    <div id="error" style="margin:5px 0;color:#FF0000"><?php echo $this->Session->flash(); ?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                            <td>
												<label class="lbl_ie">Email ID</label>
                                                <?php echo $this->Form->text('email',array('size'=>'60','id'=>'email','maxlength'=>'100','placeholder'=>'Email ID','title'=>'Email ID','class'=>'textbox')); ?>
                                            </td>
                                            </tr>
                                            <tr >
                                                <td align="left">
                                                    <input type="hidden" name="hidtxt" value="<?php if(isset($_GET['login'])) { echo $_GET['login']; } ?>" readonly="true">
                                                    
                                                    <input type="hidden" id="user_id" name="user_id" value="<?php if(isset($user_id)) { echo $user_id; } ?>" readonly="true">
                                                    <div id="fgpass">
                                                        <div class="fl" style="margin-left:20px; margin-top:10px;">
                                                            <button type="submit" value="Submit" name="submit_pwd"class="btn btn_blue" style="cursor:pointer">Submit</button>
                                                            <span class="or_cancel">or<a href="<?php echo HTTP_ROOT; ?>users/login">Cancel</a></span>
                                                        </div>
                                                        <div class="cb"></div>
                                                    </div>
                                                    <div class="cb"></div>
                                                    <span id="fgload" style="display:none;padding-left:20px;margin-top:10px;">
                                                  	  <img src="<?php echo HTTP_IMAGES; ?>images/feed.gif?v=<?php echo RELEASE; ?>" alt="Loading" title="Loading"/>
                                                    </span>
                                                    <?php if(isset($pass_succ) && empty($pass_succ)) { ?>
                                                    <h6 style="font-size:13px; margin-bottom:6px;">
                                                    (After submitting please click on the link sent to your email)
                                                    </h6>
        
                                                   <?php } ?>
                                                </td>
                                            </tr>
                                        </table>
                                    <?php echo $this->Form->end(); ?>
                                    <?php } ?>
                                    <?php if($passemail=="12"){ ?>
                                    <?php echo $this->Form->create('User',array('url'=>'/users/forgotpassword','onsubmit'=>'return validatepass()')); ?>
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%" style="font:normal 12px verdana;color:#FFF;">
                                        
                                        <tr>
                                        <td>
                                        <div style="padding-bottom:10px"><h2 style="">Reset your password</h2></div>
                                        <div><img src="<?php echo HTTP_ROOT; ?>img/images/login_header_shadow.png?v=<?php echo RELEASE; ?>" /></div>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td align="center" valign="bottom" colspan="2">
                                        <span id="err_pass" style="font:normal 14px PT Sans;color:#FF0000;"></span>
                                        </td>
                                        </tr>    	
                                        <tr height="5px"><td>
                                        <?php echo $this->Form->password('newpass',array('size'=>'60','id'=>'newpass','maxlength'=>'15','onKeyPress'=>'return noSpace(event)','autocomplete'=>'off','placeholder'=>'New Password','title'=>'New Password','class'=>'textbox')); ?>
                                        
                                        </td>
                                        </tr>
                                        <tr height="5px"><td>
                                        <?php echo $this->Form->password('repass',array('size'=>'60','id'=>'repass','maxlength'=>'15','onKeyPress'=>'return noSpace(event)','autocomplete'=>'off','placeholder'=>'Re-type Passowrd','title'=>'Re-type Passowrd','class'=>'textbox')); ?>
                                        </td>
                                        </tr>
                                        <tr><td style="height:5px"></td></tr>
                                        <tr>
                                        <td align="left">
                                        <input type="hidden" name="hidtxt" value="<?php if(isset($_GET['login'])) { echo $_GET['login']; } ?>" readonly="true">
                                        
                                        <input type="hidden" id="user_id" name="user_id" value="<?php if(isset($user_id)) { echo $user_id; } ?>" readonly="true">
                                        <span id="savpass" style="margin-left:20px; margin-top:10px;">
                                        <button type="submit" value="Submit" name="submit_pwd" class="btn btn_blue" style="cursor:pointer">Submit</button>
                                        <span class="or_cancel">or<a href="<?php echo HTTP_ROOT; ?>users/login">Cancel</a></span>
                                        </span>
                                        <span id="savload" style="display:none;padding-left:20px;">
                                        <img src="<?php echo HTTP_IMAGES; ?>images/feed.gif?v=<?php echo RELEASE; ?>" alt="Loading" title="Loading"/>
                                        </span>
                                        <br/><br/>
                                        
                                        </td>
                                        </tr>
                                        </table>
                                    <?php echo $this->Form->end(); ?>
                                    <?php } ?>
                                    <?php if($chkemail=="10"){?>
                                        <table cellpadding="0" cellpadding="0" border="0" width="100%" style="font:normal 12px verdana;color:#FFF;">
                                        <tr>
											<td>
											<div style=" margin-top:0px; margin-bottom:10px"><h2 style="color:#379B37; font-size:20px;">Your Password is successfully changed.<h3></div>
											<div><img src="<?php echo HTTP_ROOT; ?>img/images/login_header_shadow.png?v=<?php echo RELEASE; ?>"></div>
											</td>
											</tr>
											<tr>
											<td>
	<h2 style="color:#000; font-size:20px;"><a href="<?php echo HTTP_ROOT;?>users/login" style="color:blue;text-decoration:underline;">Login</a> with your new pasword</a></h2>
											</span>
											</td>
                                        </tr>
										
                                        </table>
                                    <?php }?>        
                                </td>
                            </tr>
                        </table>
                    </div>
             </div>
        </div> 
        </div>
    </div>
    </div>
</div>
<script>
function validpwd(txtEmail)
{
	var email = document.getElementById(txtEmail).value;
	var done = 1;
	if(email.trim() == "")
	{
		err = "Please enter your email.";
		done = 0;
	}
	else
	{
		var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!email.match(emailRegEx))
		{
			err = "Invalid 'E-mail' address!";
			done = 0;
		}
	}
	if(done == 0)
	{
		document.getElementById('error').style.display='block';
		document.getElementById('error').innerHTML=err;
		return false;
	}
	else
	{
		document.getElementById('error').style.display='none';
		document.getElementById('fgpass').style.display='none';
		document.getElementById('fgload').style.display='block';
		return true;
	}
	
}
</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.min.1.5.1.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.pstrength-min.1.2.js"></script>
<script>
$(document).ready(function() 
{
	$('#newpass').pstrength();
});

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}
function prepareInputsForHints() {
	var inputs = document.getElementsByTagName("input");
	for (var i=0; i<inputs.length; i++){
		// test to see if the hint span exists first
		if (inputs[i].parentNode.getElementsByTagName("span")[0]) {
			// the span exists!  on focus, show the hint
			inputs[i].onfocus = function () {
				document.getElementById('hints').style.display='block';
				this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
			}
			// when the cursor moves away from the field, hide the hint
			inputs[i].onblur = function () {
				//alert(this.parentNode.getElementsByTagName("span")[0].style.display);
				document.getElementById('hints').style.display='none';
				this.parentNode.getElementsByTagName("span")[0].style.display = "none";
			}
		}
	}
	// repeat the same tests as above for selects
	var selects = document.getElementsByTagName("select");
	for (var k=0; k<selects.length; k++){
		if (selects[k].parentNode.getElementsByTagName("span")[0]) {
			selects[k].onfocus = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
			}
			selects[k].onblur = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "none";
			}
		}
	}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
addLoadEvent(prepareInputsForHints);


function validatepass()
{	
	var newpass = document.getElementById('newpass');
	var repass = document.getElementById('repass');
	var errMsg; var done = 1;
	if(newpass.value.trim() == "")
	{
		errMsg = "Password cannot be  blank!";
		newpass.focus();
		done = 0;
	}
	else if(newpass.value.length < 6 || newpass.value.length > 15)
	{
		errMsg = "Password should be between 6-15 characters!";
		newpass.focus();
		done = 0;
	}
	else if(repass.value.trim() == "")
	{
		errMsg = "Re-Type Password cannot be  blank!";
		repass.focus();
		done = 0;
	}
	else if(repass.value != newpass.value)
	{
		errMsg = "Passwords do not match!";
		repass.focus();
		done = 0;
	}
	if(done == 0)
	{
		document.getElementById('err_pass').style.display='block';
		document.getElementById('err_pass').innerHTML=errMsg;
		return false;
	}
	else
	{
		document.getElementById('err_pass').style.display='none';
		document.getElementById('savpass').style.display='none';
		document.getElementById('savload').style.display='block';
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
<div class="user_profile_con thwidth">
<!--Tabs section starts -->
    <?php echo $this->element("personal_settings");?>
<!--Tabs section ends -->

<?php if(SES_ID != 515 && SES_ID != 516) {
    echo $this->Form->create('User',array('url'=>'/users/changepassword','onsubmit'=>"return checkPasswordMatch('pas_new','pas_retype','old_pass',".NO_PASSWORD.")",'autocomplete'=>'off')); ?>
<input type="hidden" name="data[User][csrftoken]" class="csrftoken" readonly="true" value="" />
<table cellspacing="0" cellpadding="0" class="col-lg-5" style="text-align:left;">
    <tbody>
        <tr style="visibility:<?php if(NO_PASSWORD){echo 'hidden; margin-top: -40px;position: absolute;';} else { echo 'visible;';} ?>" >
            <th>Old Password:</th>
            <td style="padding-bottom:25px">
		<?php echo $this->Form->password('old_pass',array('value'=>'','class'=>'form-control','id'=>'old_pass','onKeyPress'=>'return noSpace(event)','autocomplete'=>'off')); ?>
	    </td>
        </tr>
        <tr>
            <th>New Password:</th>
            <td style="padding-bottom:25px">
		<?php echo $this->Form->password('pas_new', array('value' => '', 'class' => 'form-control','id' => 'pas_new','onKeyPress' => 'return noSpace(event)', 'autocomplete' => 'off')); ?>	
    		<div id="hints" style="display: none;">
    		    <div>
    			<span class="hint">Between 6-15 characters<span class="hint-pointer">&nbsp;</span></span>
    		    </div>
    		</div>
	    </td>
        </tr>
        <tr>
            <th>Confirm Password:</th>
            <td style="padding-bottom:25px">
		<?php echo $this->Form->password('pas_retype',array('value'=>'','class'=>'form-control','id'=>'pas_retype','onKeyPress'=>'return noSpace(event)','autocomplete'=>'off')); ?>	
	    </td>
        </tr>
        <tr>
	    <th></th>
            <td class="btn_align">
            	<span id="subprof1">
		<input type="hidden" name="data[User][changepass]" id="changepass" readonly="true" value="0"/>
		<?php /*<button type="submit" value="<?php if(NO_PASSWORD){echo 'Set';}else{ echo 'Change';}?>" name="submit_Pass"  id="submit_Pass" class="btn btn_blue" onclick="document.getElementById('changepass').value='1'"><i class="icon-big-tick"></i>Change</button> */ ?>
		<button type="button" value="<?php if(NO_PASSWORD){echo 'Set';}else{ echo 'Change';}?>" name="submit_Pass"  id="submit_Pass" class="btn btn_blue" onclick="$('#changepass').val('1');checkCsrfToken('UserChangepasswordForm');"><i class="icon-big-tick"></i>Change</button>
		<!--<button type="button" class="btn btn_grey" onclick="cancelProfile('<?php echo $referer;?>');"><i class="icon-big-cross"></i>Cancel</button>-->
                 <span class="or_cancel">or
                    <a onclick="cancelProfile('<?php echo $referer;?>');">Cancel</a>
                </span>
		</span>
		<span id="subprof2" style="display:none">
		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." />
		</span>
            </td>
        </tr>						
    </tbody>
</table>
<?php echo $this->Form->end();
} else {
    echo "<center>Sorry, this is not available in this version.</center>";
} ?>

<div class="cbt"></div>
</div>

<style>
.thwidth table th{
	width:152px;
}
</style>

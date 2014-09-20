<?php echo $this->Form->create('User', array('url' => '/users/new_user', 'id' => 'myform', 'name' => 'myform', 'onsubmit' => 'return memberCustomer(\'txt_email\',\'sel_custprj\',\'loader\',\'btn\')')); ?>
<center><div id="err_email_new" style="color:#FF0000;display:none;"></div></center>
<div class="data-scroll user_pdt">
<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab">
    <tr>
	<td class="v-top">
	    Email ID:
	</td>
	<td> 
	    <?php echo $this->Form->textarea('email', array('id' => 'txt_email', 'class' => 'form-control')); ?>
	    <input type="hidden" name="data[User][istype]" value="3" id="sel_Typ"/>
	    <div class="user_inst">(Use comma to separate multiple email ids)</div>
		<!--<br/>
		<br/>
		<button type="button" class="new_drop_btn" onclick="googleConnect(0);">
				<img src="<?php echo HTTP_IMAGES; ?>images/gmail.png" height="23px" width="22px" style="position: absolute;margin: -3px 2px 0 -26px" />Gmail Contacts
		</button>
		<br/>
		<i style="font-size:12px;color:#333333">(Import your Gmail Contacts)</i>
		<br/>-->
	</td>
    </tr>
    <tr>
	<td class="v-top">
	    Project to be<br/>assigned: <br/>
	</td>
	<td <?php if ($is_active_proj >= 5) { ?>class="auto_tab_fld" <?php } ?>>
	    <?php if ($is_active_proj >= 5) { ?>
	    <select name="data[User][pid]" id="sel_custprj" class="form-control"></select>
	    <?php
	    } else {
			echo $this->Form->input('pid', array('type' => 'select', 'label' => false, 'options' => $active_proj_list, 'id' => 'select_project', 'class' => 'form-control'));
	    }
	    ?>
	</td>
    </tr>
    <input type="hidden" name="data[TimezoneName][id]" value="<?php echo SES_TIMEZONE; ?>" id="txt_loc"/>
</table>
</div>
 <div style="padding-left:124px;"> 
	    <?php
	    $totUsr = "";
	    if ((strtolower(trim($user_subscription['user_limit'])) != "unlimited") && $current_active_users >= $user_subscription['user_limit']) {
		?>
    	    <font color="#FF0000">Sorry, User Limit Exceeded!</font>
    	    <br/>
    	    <font color="#F05A14"><a href="<?php echo HTTP_ROOT; ?>pricing">Upgrade</a> your account to create more users</font>
		<?php
	    } else if ((strtolower(trim($user_subscription['user_limit'])) != "unlimited") && $current_active_users >= $user_subscription['user_limit']) {
		?>
    	    <font color="#FF0000">Sorry, User Limit Exceeded!</font>
    	    <br/>
    	    <font color="#F05A14"><a href="<?php echo HTTP_ROOT; ?>pricing">Upgrade</a> your account to create more users</font>
		<?php
	    } else {
		?>
	    
	    <span id="ldr" style="display:none;">
		<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." />
	    </span>
	    <span id="btn_addmem">
		<input type="hidden" id="uniq_id" value="<?php echo $uniq_id; ?>">
		<button type="submit" value="Add" name="addMember" class="btn btn_blue"><i class="icon-big-tick"></i>Add</button>
		<button class="btn btn_grey reset_btn" type="button" name="cancel" onclick="closePopup();" ><i class="icon-big-cross"></i>Cancel</button>
	    </span>
		<?php
	    }
	    ?>
</div> 
<?php echo $this->Form->end(); ?>
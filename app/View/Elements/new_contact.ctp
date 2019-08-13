

<?php 
echo $this->Form->create('Contact', array('url' => '/users/new_contact', 'id' => 'mycontactform', 'name' => 'myform', 'onsubmit' => 'return validate_contact();')); ?>
<center><div id="err_cont_div" style="color:#FF0000;display:none;"></div></center>
<div class="data-scroll user_pdt">
<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab">
  
    <tr>
	<td class="v-top">
	    Name:
	</td>
	<td> 
	    <?php echo $this->Form->text('name', array('id' => 'contact_name', 'class' => 'form-control')); ?>
      </td></tr>
     <tr>
	<td class="v-top">
	    Email:
	</td>
	<td> 
	    <?php echo $this->Form->text('email', array('id' => 'contact_email', 'class' => 'form-control')); ?>
	</td>
    </tr>
        <tr>
	<td class="v-top">
	    Message:
	</td>
	<td> 
	    <?php echo $this->Form->textarea('message', array('id' => 'contact_message', 'class' => 'form-control')); ?>
	  
	</td>
    </tr>

    
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
		<input type="hidden" id="uniq_id" value="<?php echo COMP_UID; ?>">
		<button type="submit" value="Add" name="addMember" class="btn btn_blue"><i class="icon-big-tick"></i>Add</button>
		<!--<button class="btn btn_grey reset_btn" type="button" name="cancel" onclick="closePopup();" ><i class="icon-big-cross"></i>Cancel</button>-->
         <span class="or_cancel">or
            <a onclick="closePopup();">Cancel</a>
        </span>
	    </span>
		<?php
	    }
	    ?>
</div> 
<?php echo $this->Form->end(); ?>
<script>
var active_prjct = "<?php echo $is_active_proj?>" ;
</script>
 
<?php
$cscount = 0;
$class = "";
$totCase = 0;
$totids = "";

if (!empty($memsExtArr['Member']) || !empty($memsExtArr['Invited']) || !empty($memsExtArr['Disabled'])) {
    ?>
<div class="scrl-ovr">
    <table cellpadding="0" cellspacing="0" class="col-lg-12 rmv_prj_usr_tbl">
        <tr class="hdr_tr">
	    <th class="w54"><input type="checkbox" class="chkbx_cur" onclick="selectremuserAll(1,0)" id="remcheckAll"/></th>
	    <th>Name</th>
	    <th>Short Name</th>
	    <th>Email</th>
	    <th>Email Notification</th>
        </tr>
	<?php
	foreach ($memsExtArr['Member'] as $v1) {

	    $user_id = $v1['User']['id'];
	    $user_email = $v1['User']['email'];
	    $user_name = $v1['User']['name'];
	    $user_shortName = $v1['User']['short_name'];
	    $user_istype = $v1['User']['istype'];
	    $cscount++;
	    if ($cscount % 2 == 0) {
		$class = "row_col";
	    } else {
		$class = "row_col_alt";
	    }
	    ?>
	<tr id="listing<?php echo $cscount; ?>" class="rw-cls <?php echo $class; ?>">
	    <td>
		    <?php
		    if ($v1['CompanyUser']['user_type'] == 1 && SES_TYPE != 1) {
			?>
	    	    <input type="checkbox" class="chkbx_cur" id="usCheckBox<?php echo $cscount; ?>" value="<?php echo $user_id; ?>" checked="checked" disabled="disabled"/>
			<?php
		    } else {
			?>
	    	    <input type="checkbox" class="chkbx_cur rem-usr-prj" id="usCheckBox<?php echo $cscount; ?>" value="<?php echo $user_id; ?>" data-usr-name="<?php echo trim($user_name);?>" onclick="selectremuserAll(0,<?php echo $cscount; ?>);" />
	    	    <input type="hidden" id="actionCls<?php echo $cscount; ?>" value="0"/>
			<?php
		    }
		    ?>
		</td>
		<td>
		    <?php echo ucfirst($this->Format->formatText($user_name)); ?>

		    <?php
		    $usr_typ_name = '';
		    if ($v1['CompanyUser']['user_type'] == 1) {
			$colors = 'color:Green';
			$usr_typ_name = 'Owner';
		    } else if ($v1['CompanyUser']['user_type'] == 2) {
			$colors = 'color:Red';
			$usr_typ_name = 'Admin';
		    } else if ($v1['CompanyUser']['user_type'] == 3 && $role != 3) {
			
		    }

		    if ($v1['CompanyUser']['is_active'] == 0) {
			$colors = 'color:Blue';
			$usr_typ_name = 'Invited';
		    }
		    ?>
		    <span style="font-size: 13px;<?php echo $colors;?>">&nbsp;&nbsp;&nbsp;<?php echo $usr_typ_name; ?></span>
		</td>
		<td>
		    <?php echo strtoupper($user_shortName); ?>
		</td>
		<td>
		    <div class="rem_usr_pop_ipad" title="<?php echo $this->Format->formatText($user_email); ?>"><?php echo $this->Format->formatText($user_email); ?></div>
		</td>
		<td>
	<?php if ($v1['ProjectUser']['default_email'] == 0) { ?>
	    	    <ul class="onoff">
	    		<li class="off" ><a href="javascript:void(0)" onclick="setemail(this, 'off', '<?php echo $v1['ProjectUser']['id'] ?>', 'on');">OFF</a></li>
	    		<li><a href="javascript:void(0)" onclick="setemail(this, 'on', '<?php echo $v1['ProjectUser']['id'] ?>', 'off');">ON</a></li>
	    	    </ul>
	<?php } else { ?>
	    	    <ul class="onoff">
	    		<li ><a href="javascript:void(0)" onclick="setemail(this, 'off', '<?php echo $v1['ProjectUser']['id'] ?>', 'on');">OFF</a></li>
	    		<li class="on"><a href="javascript:void(0)" onclick="setemail(this, 'on', '<?php echo $v1['ProjectUser']['id'] ?>', 'off');">ON</a></li>
	    	    </ul>
	<?php } ?>   
		</td>

	    </tr>

	    <input type="hidden" id="allcases" name="allcases" value="<?php echo $cscount; ?>"/>
	<?php } ?>
    </table>
	<?php if (!empty($memsExtArr['Disabled']) || !empty($memsExtArr['Invited'])) { ?>
<table cellpadding="0" cellspacing="0" class="col-lg-12 rmv_prj_usr_tbl inctv">
	    <?php
	    foreach ($memsExtArr['Disabled'] as $v2) {
		$user_id = $v2['User']['id'];
		$user_email = $v2['User']['email'];
		$user_name = $v2['User']['name'];
		$user_shortName = $v2['User']['short_name'];
		$user_istype = $v2['User']['istype'];
		$cscount++;
		?>
    <tr id="disabledlist<?php echo $cscount; ?>" class="disable-cls">	
	<td class="w54">
	    	    <input type="checkbox" class="chkbx_cur rem-usr-prj" id="usDisCheckBox<?php echo $cscount; ?>" value="<?php echo $user_id; ?>" data-usr-name="<?php echo trim($user_name);?>" onclick="selectremuserAll(0,<?php echo $cscount; ?>);" />
	    	</td>
		<td colspan="4">
		<?php echo $this->Format->formatText($user_name) . " (" . strtoupper($user_shortName) . ") "; ?>

		<?php
		$usr_typ_name = '';
		if ($v2['CompanyUser']['is_active'] == 0) {
		    $colors = 'color:#DD4D4B';
		    $usr_typ_name = 'Disabled';
		}
		?>
	    	    <span style="<?php echo $colors;?>"><?php echo $usr_typ_name; ?></span>
	    	   &nbsp;&nbsp;&nbsp; <span><?php echo $this->Format->formatText($user_email); ?></span>
	    	</td>
	        </tr>
	<?php } ?>
		    <?php
		    foreach ($memsExtArr['Invited'] as $v) {

			$user_id = $v['User']['id'];
			$user_email = $v['User']['email'];
			$user_istype = $v['User']['istype'];
			$cscount++;
			if ($cscount % 2 == 0) {
			    $class = 'border-bottom:1px solid #FFFFFF';
			} else {
			    $class = "border-bottom:1px solid #FFFFFF";
			}
			?>
		<tr id="Invitedlisting<?php echo $cscount; ?>" class="invited-cls">	
	    	<td class="w54">
	    	    <input type="checkbox" class="chkbx_cur rem-usr-prj" id="usInvCheckBox<?php echo $cscount; ?>" value="<?php echo $user_id; ?>" data-usr-name="<?php echo trim($user_email);?>" onclick="selectremuserAll(0,<?php echo $cscount; ?>);" />
	    	</td>
	    	<td colspan="4">
	    <?php
	    if ($v['UserInvitation']['is_active'] == 1) {
		$colors = 'color:Blue';
		$usr_typ_name = 'Invited';
	    }
	    ?>
	    <?php echo $this->Format->formatText($user_email); ?>
	    	    <span style="<?php echo $colors;?>">&nbsp;&nbsp;&nbsp;<?php echo $usr_typ_name; ?></span>
	    	</td>
	        </tr>
	<?php } ?>	
	</table>
</div>
    <?php } ?>
<input type="hidden" id="is_users"  value="1"/>
<?php } else { ?>
    <center class="fnt_clr_rd">No users assigned</center>
    <input type="hidden" id="is_users"  value="0"/>
<?php } ?>
<input type="hidden" id="pjid" name="pjid" value="<?php echo $pjid; ?>"/>
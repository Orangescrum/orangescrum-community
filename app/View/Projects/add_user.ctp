<table cellpadding="0" cellspacing="0" class="col-lg-12 ad_prj_usr_tbl ad_prj_usr_ipad">
    <input type="hidden" id="adusrprojnm" value="<?php echo $this->Format->formatText($pjname); ?>">
    <tr>
	<td valign="top" class="usersin_prj_frst_td">
	    <div class="scrl-ovr inner_prj_notextusr_add">
	    <table cellpadding="0" cellspacing="0" class="col-lg-12" style="width:100%">
		<tr class="hdr_tr">
		    <th><input type="checkbox" class="chkbx_cur" onclick="selectuserAll(1,0)" id="checkAll"/></th>
		    <th class="nm_ipad" style="padding-left:10px;">Name</th>
		    <th>&nbsp;</th>
		    <th>Email</th>
		</tr>
		<?php
		$userCount = count($memsNotExstArr);
		$count = 0;
		$class = "";
		$totCase = 0;
		$totids = "";
		if ($userCount) {
		    $typ = "";
		    foreach ($memsNotExstArr as $memsAvlArr) {
			$user_id = $memsAvlArr['User']['id'];
			$user_name = ucfirst($memsAvlArr['User']['name']);
			$user_shortName = $memsAvlArr['User']['short_name'];
			$user_email = $memsAvlArr['User']['email'];
			$user_istype = $memsAvlArr['User']['istype'];
			$count++;
			if ($count % 2 == 0) {
			    $class = "row_col";
			} else {
			    $class = "row_col_alt";
			}
			?>
			<tr id="listing<?php echo $count; ?>" class="rw-cls <?php echo $class; ?>">	
			    <td>
				<input type="checkbox" class="chkbx_cur ad-usr-prj" id="actionChk<?php echo $count; ?>" value="<?php echo $user_id.'@@|@@'.urlencode($user_name); ?>" onclick="selectuserAll(0,<?php echo $count; ?>,'<?php echo urlencode($user_name);?>');" />
				<input type="hidden" id="actionCls<?php echo $count; ?>" value="0"/>
			    </td>
			    <td style="padding-left:10px;<?php echo $class; ?>">
				<?php echo $this->Format->shortLength($user_name, 25); ?>
				<?php
				$usr_typ_name = '';
				if ($memsAvlArr['CompanyUser']['user_type'] == 1) {
				    $colors = 'color:Green';
				    $usr_typ_name = 'Owner';
				} else if ($memsAvlArr['CompanyUser']['user_type'] == 2) {
				    $colors = 'color:Red';
				    $usr_typ_name = 'Admin';
				} else if ($memsAvlArr['CompanyUser']['user_type'] == 3 && $role != 3) {
				    
				}
				?>
				<span style="font-size:13px;<?php echo $colors; ?>">&nbsp;&nbsp;&nbsp;<?php echo $usr_typ_name; ?></span>
			    </td>
			    <td  style="<?php echo $class; ?>">
			<?php echo strtoupper($user_shortName); ?>
			    </td>
			    <td style="<?php echo $class; ?>">
			<?php echo $this->Format->shortLength($user_email, 25); ?>
			    </td>
			</tr>
			<?php
			$totids.= $user_id . "|";
			$typ = $user_istype;
		    }
		} else {
		    ?>
		    		<tr>
		    		    <td colspan="7">
		    		<center class="fnt_clr_rd">No user(s) available.</center>
		    	</td>
			</tr>
		    <?php
		}
		?>
	</table>
	</div>
	</td>
	<td valign="top" style="padding-left:10px">
	<div class="scrl-ovr">
	<table cellpadding="0" cellspacing="0" class="col-lg-12 users_no" style="width:100%">
		<tr class="hdr_tr">
		    <th class="nm_ipad" style="padding-left:10px;background: none repeat scroll 0 0 #ABBAC3;color: #FFFFFF;">User(s) in this<?php //echo $this->Format->formatText($pjname); ?> Project</th>
		</tr>
		<?php
		$userCount = count($memsExstArr);
		$count = 0;
		$class = "";
		$totCase = 0;
		$totids = "";
		if ($userCount) {
		    $typ = "";
		    foreach ($memsExstArr as $memsAvlArr) {
			$user_id = $memsAvlArr['User']['id'];
			$user_name = ucfirst($memsAvlArr['User']['name']);
			$user_shortName = $memsAvlArr['User']['short_name'];
			$user_email = $memsAvlArr['User']['email'];
			$user_istype = $memsAvlArr['User']['istype'];
			$count++;
			if ($count % 2 == 0) {
			    $class = "row_col";
			} else {
			    $class = "row_col_alt";
			}
			?>
			<tr id="extlisting<?php echo $user_id; ?>" class="rw-cls1 <?php echo $class; ?>"  onmouseover="displayDeleteImg('<?php echo $user_id; ?>');" onmouseout="hideDeleteImg('<?php echo $user_id; ?>');">
			    <td style="padding-left:10px;<?php echo $class; ?>">
			   <div class="fl" title="<?php echo $user_email;?>">
				<?php echo $this->Format->shortLength($user_name, 25); ?>
				<?php
				$usr_typ_name = '';
				if ($memsAvlArr['CompanyUser']['user_type'] == 1) {
				    $colors = 'color:Green';
				    $usr_typ_name = 'Owner';
				} else if ($memsAvlArr['CompanyUser']['user_type'] == 2) {
				    $colors = 'color:Red';
				    $usr_typ_name = 'Admin';
				} else if ($memsAvlArr['CompanyUser']['user_type'] == 3 && $role != 3) {
				    
				}
				?>
				<span style="font-size:13px;<?php echo $colors; ?>">&nbsp;&nbsp;&nbsp;<?php echo $usr_typ_name; ?></span>
			</div>
			<div id="deleteImg_<?php echo $user_id; ?>" title="Delete" class="dropdown_cross fr" style="display:none;color:#D4696F;font-weight:bold;cursor:pointer" onclick="deleteUsersInProject('<?php echo $user_id; ?>','<?php echo $projid;?>','<?php echo urlencode($user_name);?>');">&times;</div>
				<div class="cb"></div>
			    </td>
			</tr>
			<?php
			$totids.= $user_id . "|";
			$typ = $user_istype;
			    }
			} else {
			    ?>
		    		<tr>
		    		    <td colspan="7">
		    		<center class="fnt_clr_rd">No user(s) available.</center>
		    	</td>
			</tr>
		    <?php } ?>
	</table>
	</div>
	</td>
</tr>
</table>
<input type="hidden" name="hid_cs" id="hid_cs" value="<?php echo $count; ?>"/>
<input type="hidden" name="totid" id="totid" value="<?php echo $totids; ?>"/>
<input type="hidden" name="chkID" id="chkID" value=""/>
<input type="hidden" name="slctcaseid" id="slctcaseid" value=""/>
<input type="hidden" id="getusercount" value="<?php echo $userCount; ?>" readonly="true"/>
<input type="hidden" name="project_id" id="projectId" value="<?php echo $projid; ?>"/>
<input type="hidden" name="project_name" id="project_name" value="<?php echo $pjname; ?>"/>
<input type="hidden" name="cntmng" id="cntmng" value="<?php echo $cntmng; ?>"/>
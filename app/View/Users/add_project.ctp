<style>
	.tr_active{
		background-color:#FFFFCC;
	}
</style>
<table cellpadding="0" cellspacing="0" class="col-lg-12 ad_prj_usr_tbl ad_prj_usr_ipad">
    <input type="hidden" id="userpopupname" value="<?php echo $this->Format->formatText($name); ?>">
    <tr>
	<td valign="top" class="assingef_prj_frst_td">
	<div class="scrl-ovr">
	    <table cellpadding="0" cellspacing="0" class="col-lg-12 ad_prj_usr_ipad">
		<tr class="hdr_tr">
		    <th><input type="checkbox" class="chkbx_cur" id="checkAllAddPrj"/></th>
		    <th style="padding-left:10px;">Name</th>
		    <th>Short Name</th>
		</tr>
		<?php
		$count = 0;
		$class = "";
		if ($prj_count) {
		    foreach($project_name as $prj_nm) {
			$project_id = $prj_nm['projects']['id'];
			$project_name = ucfirst($prj_nm['projects']['name']);
			$count++;
			if ($count % 2 == 0) {
			    $class = "row_col";
			} else {
			    $class = "row_col_alt";
			}
			?>
			<tr id="listing<?php echo $count; ?>" class="tr_all rw-cls <?php echo $class; ?>">	
			    <td>
				<input type="checkbox" class="chkbx_cur ad-usr-prj AddPrjToUser" id="actionChk<?php echo $count; ?>" value="<?php echo $project_id; ?>" data-prj-name="<?php echo urlencode(trim($project_name));?>"/>
				<input type="hidden" id="actionCls<?php echo $count; ?>" value="0"/>
			    </td>
			    <td style="padding-left:10px;<?php echo $class; ?>">
				<div class="assn_proj_ipad"><?php echo ucfirst($prj_nm['projects']['name']);?></div>
			    </td>
			    <td  style="<?php echo $class; ?>">
				<?php echo $prj_nm['projects']['short_name'];?>
			    </td>
			</tr>
			<?php
				}
			} else {
			    ?>
			    		<tr>
			    		    <td colspan="3">
			    		<center class="fnt_clr_rd">No project(s) available.</center>
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
	    <table cellpadding="0" cellspacing="0" class="col-lg-12 ad_prj_usr_ipad">
		<tr class="hdr_tr">
		    <th style="padding-left:10px;background: none repeat scroll 0 0 #ABBAC3;color: #FFFFFF;">Assigned Project(s)</th>
		</tr>
		<?php
		$count = 0;
		$class = "";
		if ($exst_prj_count) {
		    foreach($exists_project_name as $exprj_nm) {
			//$project_id = $exprj_nm['projects']['id'];
			//$project_name = ucfirst($exprj_nm['projects']['name']);
			$count++;
			if ($count % 2 == 0) {
			    $class = "row_col";
			} else {
			    $class = "row_col_alt";
			}
			?>
			<tr id="extlisting_<?php echo $exprj_nm['projects']['id']; ?>" class="tr_all rw-cls <?php echo $class; ?>"  onmouseover="displayDeleteImg('<?php echo $exprj_nm['projects']['id']; ?>');" onmouseout="hideDeleteImg('<?php echo $exprj_nm['projects']['id']; ?>');">	
			    <td style="padding-left:10px;<?php echo $class; ?>">
				<div class="fl assn_proj_ipad" title="<?php echo $exprj_nm['projects']['short_name'];?>"><?php echo ucfirst($exprj_nm['projects']['name']);?></div>
				<div id="deleteImg_<?php echo $exprj_nm['projects']['id']; ?>" title="Delete" class="dropdown_cross fr" style="display:none;color:#D4696F;font-weight:bold;cursor:pointer" onclick="deleteAssignedProject('<?php echo $exprj_nm['projects']['id']; ?>','<?php echo $usrid;?>','<?php echo urlencode($exprj_nm['projects']['name']);?>','<?php echo $is_invite_user;?>');">&times;</div>
				<div class="cb"></div>
			    </td>
			</tr>
			<?php
				}
		} else {
		    ?>
		    		<tr>
		    		    <td colspan="3">
		    		<center class="fnt_clr_rd">No project(s) assigned.</center>
		    	</td>
			</tr>
		    <?php
		}
		?>
		</table>
		</div>
	</td>
</tr>
</table>

<input type="hidden" id="user_id" name="user_id" value="<?php echo $usrid; ?>"/>
<input type="hidden" id="is_invite_user" name="is_invite_user" value="<?php echo $is_invite_user; ?>"/>
<input type="hidden" id="count" name="count" value="<?php echo $count1; ?>"/>

<style type="text/css">
	.ad_cs_mlstn_tbl .tsk_sts div.label {
    border-radius: 0;
    font-family: arial;
    font-size: 11px;
    font-weight: normal;
    padding: 2px 4px;
}
</style>
<div class="scrl-ovr">
    <table cellpadding="0" cellspacing="0" class="col-lg-12 ad_cs_mlstn_tbl">
	<tr class="hdr_tr">
	    <th><input type="checkbox" class="chkbx_cur" onclick="selectMilestones(1,0,'checkAll_rt')" id="checkAll_rt"/></th>
	    <th>Task#</th>
	    <th></th>
	    <th></th>
	    <th>Title</th>
	    <th>Status</th>
	    <th>Due Date</th>
	</tr>
	<?php
	    $caseCount = count($easycases);
	    $count = 0;
	    $class = "";
	    $totCase = 0;
	    $repeatcaseTypeId = "";
	    $repeatLastUid = "";
	    $repeatAssgnUid = "";
	    $totids = "";
	    $getlastUid = "";

	    if ($caseCount) {
		foreach ($easycases as $getdata) {
		    $caseAutoId = $getdata['Easycase']['id'];
		    $caseNo = $getdata['Easycase']['case_no'];
		    $caseUserId = $getdata['Easycase']['user_id'];
		    $caseTypeId = $getdata['Easycase']['type_id'];
		    $caseLegend = $getdata['Easycase']['legend'];
		    $casePriority = $getdata['Easycase']['priority'];
		    $caseFormat = $getdata['Easycase']['format'];
		    $frmt_data = $this->Format->formatText(ucfirst($getdata['Easycase']['title']));
		    $caseTitle = $this->Format->convert_ascii($frmt_data);
		    $caseAssgnUid = $getdata['Easycase']['assign_to'];
		    $caseDueDate = $getdata['Easycase']['due_date'];
		    $caseUserId = $getdata['Easycase']['user_id'];
		    $count++;
		    if ($count % 2 == 0) {
			$class = "row_col";
		    } else {
			$class = "row_col_alt";
		    }

		    if ($repeatcaseTypeId != $caseTypeId) {
			$types = $this->Casequery->getType($caseTypeId);
			if (count($types)) {
			    $typeShortName = $types['Type']['short_name'];
			    $typeName = $types['Type']['name'];
			} else {
			    $typeShortName = "";
			    $typeName = "";
			}
		    }
		?>
		<tr id="listings<?php echo $count; ?>" class="rw-cls <?php echo $class; ?>">	
		    <td align="left">
			<input type="checkbox" class="chkbx_cur ad-mlstn" id="actionChk<?php echo $count; ?>" value="<?php echo $caseAutoId; ?>" onclick="selectMilestones(0,<?php echo $count; ?>,'checkAll_rt')"/>
			<input type="hidden" id="actionClss<?php echo $count; ?>" value="0"/>
		    </td>
		    <td style="<?php echo $class; ?>" class="rght"><?php echo $caseNo; ?></td>
		    <td style="<?php echo $class; ?>"><?php echo $this->Format->todo_typ($typeShortName, $typeName); ?></td>
		    <td>
			<?php
			if ($casePriority == "NULL" || $casePriority == "") {
			    echo "";
			} elseif ($casePriority == 0) {
			    echo "<span class='tag red' title='High' rel='tooltip'>&nbsp;</span>";
			} elseif ($casePriority == 1) {
			    echo "<span class='tag orange' title='Medium' rel='tooltip'>&nbsp;</span>";
			} elseif ($casePriority >= 2) {
			    echo "<span class='tag green' title='Low' rel='tooltip'>&nbsp;</span>";
			}
			?>
		    </td>
			<td><div class="ad_cs" title="<?php echo htmlentities($caseTitle); ?>"><?php echo htmlentities($caseTitle); ?></div></td>
			<td style="width:115px;"><div class="tsk_sts" style="width: 100%;"> <?php echo $this->Format->getStatus($caseTypeId, $caseLegend); ?></div></td>
		    <td>
			<?php
			$dateCurnt = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "date");
			if ($caseDueDate != "NULL" && $caseDueDate != "0000-00-00" && $caseDueDate != "" && $caseDueDate != "1970-01-01") {
			    echo $this->Datetime->dueDateFormat($caseDueDate, $dateCurnt);
			} else {
			    echo "&nbsp;";
			}
			?>
		    </td>
		</tr>
		<?php
		    $repeatLastUid = $getlastUid;
		    $repeatAssgnUid = $caseAssgnUid;
		    $repeatcaseTypeId = $caseTypeId;
		    $totids.= $caseAutoId . "|";
		}
	    } else { ?>
		<tr valign="middle">
    		    <td colspan="7" align="center">
			<center class="fnt_clr_rd">No task(s) available.</center>
		    </td>
		</tr>
	    <?php } ?>
    </table>
    <input type="hidden" id="addcsmlstn" value="<?php echo urlencode($this->Format->shortLength($this->Format->formatText($milestone['Milestone']['title']),20)); ?>" />
    <input type="hidden" id="addcsmlstn_titl_rt" value="<?php echo $this->Format->shortLength($this->Format->formatText($milestone['Milestone']['title']),20); ?>" />
    <input type="hidden" id="cur_proj_name_rt" value="<?php echo $this->Format->shortLength($this->Format->formatText($curProjName),20); ?>" />
    
    <input type="hidden" name="hid_cs" id="hid_css" value="<?php echo $count; ?>"/>
    <input type="hidden" name="totid" id="totids" value="<?php echo $totids; ?>"/>
    <input type="hidden" name="chkID" id="chkIDs" value=""/>
    <input type="hidden" name="slctcaseid" id="slctcaseids" value=""/>
    <input type="hidden" id="getcasecount" value="<?php echo $caseCount; ?>" readonly="true"/>
    <input type="hidden" name="project_id" id="project_id_rt" value="<?php echo $projid; ?>"/>
    <input type="hidden" name="milestone_id" id="milestone_id_rt" value="<?php echo $mstid; ?>"/>
    <input type="hidden" name="countmanage" id="countmanage" value="<?php echo $countmanage; ?>"/>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $milestone['Milestone']['user_id']; ?>"/>
</div>
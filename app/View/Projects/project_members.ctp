<?php 
$time = array();
$uids = array();
$id = 0;
$timezone_id = 0;
$days = 5;
if(isset($selecteduser['DailyUpdate']) && !empty($selecteduser['DailyUpdate'])){
    $id = $selecteduser['DailyUpdate']['id'];
    $time = explode(":", $selecteduser['DailyUpdate']['notification_time']);
    $uids = explode(",", $selecteduser['DailyUpdate']['user_id']);
    $days = $selecteduser['DailyUpdate']['days'];
}
$timezone_id = isset($selecteduser['DailyUpdate']['timezone_id']) ? $selecteduser['DailyUpdate']['timezone_id'] : SES_TIMEZONE;
?>
<?php if(isset($projectuser) && !empty($projectuser)){ ?>
<tr id="tr_members">
    <th style="vertical-align:top">Users:</th>
    <td align="left" style="padding: 0 0 10px;">
	<label class="userLbl" style="padding:2px 0 5px;" ><input type="checkbox"  id="user_all" <?php if(isset($uids) && !empty($uids) && (count($uids)==count($projectuser))){ ?>checked="checked" <?php }elseif(count($uids) == 0){?>checked="checked" <?php }?> onclick="checkUncheckAll(1);" style="cursor: pointer;" />&nbsp;&nbsp;All</label><br/>
	<input type="hidden"  id="daily_update_id" value="<?php echo $id;?>" />
	<table cellspacing="0" cellpadding="0" class="projectMemberCls">
    	<tbody>
	    <?php
		$cnt = 0;
		foreach($projectuser as $key => $value) {
	    ?>
	    <?php if(($cnt%3) == 0){ ?>
	    <tr>
	    <?php } $cnt++;
		$name = trim($value['User']['name']);
		if(strlen($name) <= 10)
		    $name = $value['User']['name'];
		else
		    $name = trim(substr($value['User']['name'],0,7))."...";
	    ?>
		<td align="left">
		    <label class="userLbl" title="<?php echo $value['User']['name'];?>"><input type="checkbox" name="data[Project][user][]" class="prj_users" onclick="checkUncheckAll(0);" style="cursor: pointer;" id="userId_<?php echo $value['User']['uniq_id'];?>" value="<?php echo $value['User']['uniq_id'];?>" <?php if(count($uids) > 0 && (in_array($value['User']['id'],$uids))){ ?>checked="checked" <?php }elseif(count($uids) == 0){?>checked="checked" <?php }?> />&nbsp;&nbsp;<?php echo ucfirst($name);?></label>
		</td>
	    <?php if(($cnt%3) == 0){ ?>
	    </tr>
	     <?php }?>
	    <?php } ?>
    	</tbody>	    
	</table>
    </td>
</tr>
<tr id="tr_time">
    <th valign="top">Alert Time: </th>
    <td align="left">
	<?php $hour = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24");
	    $minute = array("00","15","30","45");
	?>
	<div>
	    <div class="opt_field fl">Hour(s)</div>
	    <div class="opt_field" style="margin-left: 160px;">Minute(s)</div>
	</div>
	<div class="cb"></div>
	<select name="data[Project][hour]" class="form-control mod-wid-153 fl" id="upd_hour">
	    <option value="">--Select--</option>
	    <?php foreach($hour as $key => $value){ ?>
		<option value="<?php echo $value;?>" <?php if(isset($time) && isset($time['0']) && !empty($time) && ($time['0']==$value)){?>selected="selected"<?php }?>><?php echo $value;?></option>
	    <?php } ?>
	</select>
	<select name="data[Project][minute]" id="upd_minute" class="form-control mod-wid-153 min_mgt fl">
	    <option value="">--Select--</option>
	    <?php foreach($minute as $key => $value){ ?>
		<option value="<?php echo $value;?>" <?php if(isset($time) && isset($time['1']) && !empty($time) && ($time['1']==$value)){?>selected="selected"<?php }?>><?php echo $value;?></option>
	    <?php } ?>
	</select>
    </td>
</tr>
<tr id="tr_timezone">
	<th valign="top">Timezone: </th>
	<td align="left">
	    <select name="data[Project][timezone_id]" class="form-control dailyUpdate_sel" id="timezone_id">
		<?php if(isset($timezones)){
		    foreach($timezones as $key => $value){ ?>
		    <option value="<?php echo $value['TimezoneName']['id'];?>" <?php if($value['TimezoneName']['id'] == $timezone_id){?>selected="selected"<?php }?>><?php echo $value['TimezoneName']['gmt']; ?> <?php echo $value['TimezoneName']['zone']; ?></option>
		    <?php }
		} ?>
	    </select>
	    <span id="loading_sel" style="display: none;"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..." /></span>
	</td>
</tr>
<tr id="tr_days">
    <th valign="top">Frequency: </th>
    <td align="left">
	<select name="data[Project][days]" id="upd_hour" class="form-control dailyUpdate_sel">
	    <option value="5" <?php if($days==5){?>selected="selected"<?php }?>>5 days in week</option>
	    <option value="6" <?php if($days==6){?>selected="selected"<?php }?>>6 days in week</option>
	    <option value="7" <?php if($days==7){?>selected="selected"<?php }?>>7 days in week</option>
	</select>
    </td>
</tr>
<?php } ?>

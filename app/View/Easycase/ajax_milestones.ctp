<?php
$i=0;
if(isset($milestones)){
	$ids = array(); $isAll = 0;
	if(stristr($_COOKIE['MILESTONES'],"-")) {
		$cookies = trim(trim($_COOKIE['MILESTONES'],"-"));
		$ids = explode("-",$cookies);
		if(count($ids) == count($milestones)) { $isAll = 1; }
	}
	if($_COOKIE['MILESTONES'] == 'all') {
		$isAll = 1;
	}
	?>
	<li class="slide_menu_div1">
		<a href="javascript:void(0);">
		    <input type="radio" name="data['milestone]" value="all" id="allmstones" onclick="checkMilestones('all')" <?php if (!$cookies || $cookies == 'all' || $isAll) { echo "checked"; } ?> class="milestone_fliter_cls" style="cursor:pointer"/>&nbsp;<b>All</b>
		</a>	
	</li>	
	<?php
	foreach($milestones as $milestone) { 
	$i++;
	?>
	<span id="curli<?php echo $i; ?>" style="display:none"></span>
	<li>
	    <a href="javascript:void(0);">
		<input type="radio" name="data['milestone]" class="milestone_fliter_cls" style="cursor:pointer" value="<?php echo $milestone['m']['id']; ?>" id="mstones<?php echo $i; ?>" <?php if (in_array($milestone['m']['id'], $ids) && !$isAll) { echo "checked"; } ?> onclick="checkMilestones('<?php echo $milestone['m']['id']; ?>')"/>
		
		<font style="color:#6A6A6A;cursor:pointer" onClick="checkMilestones('<?php echo $milestone['m']['id']; ?>','mstones<?php echo $i; ?>')">&nbsp;<?php echo ucfirst($this->Format->shortLength($milestone['m']['title'],15))." (".$milestone[0]['count'].")"; ?> 
		<?php 
		$mlstDT = $this->Datetime->dateFormatOutputdateTime_day($milestone['m']['end_date'],GMT_DATETIME,'week');
		echo "&nbsp;&nbsp;<font style='color:#A7A7A7;font-size:11px;'>".$mlstDT."</font>"; 
		?></font>
	    </a>
	<?php
	}
	?>
	<input type="hidden" id="totmstones" value="<?php echo $i; ?>">
<?php
}
?>

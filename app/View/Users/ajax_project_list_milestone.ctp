<?php
if(isset($allProjArr) && count($allProjArr))
{
?>
<a href="<?php echo HTTP_ROOT.'milestones/manage/?pj=all';?>" style="font-style:normal;text-decoration:none;">All</a>
	<hr style="padding:0px;margin:2px 0px;"/>
	<?php
	$i = 0;
	$colrs = "";
	
	foreach($allProjArr as $proj)
	{
	$i++;
	$colrs = "";
	?>
		<a href="javascript:jsVoid();" style="font-style:normal;text-decoration:none;<?php echo $colrs; ?>" onClick="updateAllProj('proj_<?php echo $proj['p']['uniq_id']; ?>','<?php echo $proj['p']['uniq_id']; ?>','<?php echo $page; ?>','0');"><?php echo $this->Format->shortLength($proj['p']['name'],30); ?> (<?php echo $proj['0']['count']; ?>) </a>
		<?php
		if($i != count($allProjArr))
		{
		?>
		<hr style="padding:0px;margin:2px 0px;"/>
		<?php
		}
	}
	if($limit != "all" && $countAll > 6)
	{
	?>
		<hr style="padding:0px;margin:2px 0px;"/>
		<span id="showMenu_case_txt">
			<a href="javascript:jsVoid();" style="text-decoration:none;padding:4px;color:#000000;" onClick="displayMilestoneMenuProjects('<?php echo $page; ?>','all');">Show More...</a>
		</span>
		<span id="loaderMenu_case" style="display:none;">
			<a href="javascript:jsVoid();" style="text-decoration:none;color:#000000;padding:4px;cursor:wait">Loading...&nbsp;&nbsp;<img src="<?php echo HTTP_IMAGES;?>images/del.gif" width="16" height="16" alt="loading..." title="loading..." border="0"/></a>
		</span>
	<?php
	}
}
?>

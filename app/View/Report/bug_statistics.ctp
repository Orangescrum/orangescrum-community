<?php if($cnt != 0) { ?>
<div class="fl"> Total <strong><?php echo $cnt; ?></strong> Bugs created.</div>
<div class="cb"></div>
<div>
	<ul>
		<li>Avg. days to Resolve a Bug: <strong><?php echo round($avg_resolved); ?></strong></li>
		<li>Avg. days to Close a Bug: <strong><?php echo round($avg_closed); ?></strong></li>
		<li>Hours spent on these Bugs: <strong><?php echo $tot_hrs; ?></strong></li>
	</ul>
</div>
<?php }else{ ?>
<div class="fl"> <font color='red' size='2px'>No data for this date range & project.</font></div>
<?php } ?>
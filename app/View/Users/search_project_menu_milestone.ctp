<?php 
if($fres==1 && $page == 'milestone'){

if(isset($allProjArr) && count($allProjArr))
{
	$i = 0;
	$colrs = "";
	foreach($allProjArr as $proj)
	{
	$i++;
	$colrs = "";
	?>
		<a href="javascript:jsVoid(0);" style="font-style:normal;text-decoration:none;<?php echo $colrs; ?>" onClick="updateAllProj('proj_<?php echo $proj['Project']['uniq_id']; ?>','<?php echo $proj['Project']['uniq_id']; ?>','<?php echo $page; ?>','0');">
			<?php echo $this->Format->shortLength($proj['Project']['name'],30); ?> (<?php echo $this->Casequery->displaymilestoneNo($proj['Project']['id']); ?>) </a>
		<?php
		if($i != count($allProjArr))
		{
		?>
		<hr style="padding:0px;margin:2px 0px;"/>
		<?php
		}
	}
	
}
}
else{
echo "No data";}
?>

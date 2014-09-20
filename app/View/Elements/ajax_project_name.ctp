<?php
if(!$projName) {
	$projName = '<font color="#A5A5A5">NA</font>';
}
else {
	$projName = $this->Format->formatText($projName);
}
if(isset($pageload))
{
	if($pageload == 0)
	{
		echo $this->Format->formatText($projName);
	}
	else
	{
	?>
	<font onClick="resetProjLink('<?php echo $proj_uniq_id; ?>');ajaxCaseView('case_project');" style="cursor:pointer;text-decoration:underline;">
		<?php echo $projName; ?>
	</font>
	<?php
	}
}
else {
	echo $projName;
}
?>

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
		$prjname =  $this->Format->formatText($projName);
          echo $this->Format->shortLength($prjname,30);
	}
	else
	{
	?>
	<font onClick="resetProjLink('<?php echo $proj_uniq_id; ?>');ajaxCaseView('case_project');" style="cursor:pointer;text-decoration:underline;">
		<?php echo $this->Format->shortLength($projName,30); ?>
	</font>
	<?php
	}
}
else {
	echo $this->Format->shortLength($projName,30);
}
?>

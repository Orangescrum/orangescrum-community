<?php
$cscount=0; $class = ""; $totCase = 0;
$totids = "";

if($templates_cases) {
?>
<table border="0" style="border:1px solid #CFD7DD;" width="100%" align="center" cellspacing="0" cellpadding="0">
	<tr bgcolor="#E5E5E5" valign="center" style="font-weight:bold;color:#333333;">
		<td width="10px" style="padding-left:5px;"></td>
		<td style="padding-left:10px;">Sl#</td>
		<td style="padding-left:10px;">Title</td>
		<td style="padding-left:10px;">Description</td>
		<td style="padding-left:10px;">Created By</td>
		<td style="padding-left:10px;">Created On</td>
	</tr>
	<?php
	foreach($templates_cases as $templates_cases) { 
		$id = $templates_cases['ProjectTemplateCase']['id'];
		$user_id = $templates_cases['ProjectTemplateCase']['user_id'];
		$title = $templates_cases['ProjectTemplateCase']['title'];
		$desc = $templates_cases['ProjectTemplateCase']['description'];
		$created = $templates_cases['ProjectTemplateCase']['created'];
		$user_name = $templates_cases['User']['short_name'];
		$cscount++;
		if($cscount%2 == 0) { $class = 'border-bottom:1px solid #F2F2F2'; }
		else { $class = "border-bottom:1px solid #F2F2F2"; }
		?>
		<tr height="22px" id="listing<?php echo $cscount; ?>">	
			<td style="padding-left:5px;<?php echo $class; ?>">

					<input type="checkbox" style="cursor:pointer;position:relative;top:-2px;" id="usCheckBox<?php echo $cscount; ?>" value="<?php echo $user_id; ?>" onclick="removecase('<?php echo $cscount; ?>','<?php echo $id; ?>','<?php echo $title; ?>')" checked="checked"/>
					<input type="hidden" id="actionCls<?php echo $cscount; ?>" value="0"/>
			</td>
			<td style="padding-left:10px;<?php echo $class; ?>">
				<?php echo $cscount; ?>
			</td>
			<td style="padding-left:10px;<?php echo $class; ?>">
				<?php echo $this->Format->shortlength($this->Format->formatText($title),20); ?>
			</td>
			<td style="padding-left:10px;<?php echo $class; ?>">
				<?php echo $this->Format->formatCms($desc); ?>
			</td>
			<td style="padding-left:12px;<?php echo $class; ?>">
				<?php echo strtoupper($user_name); ?>
			</td>
			<td style="padding-left:12px;<?php echo $class; ?>">
				<?php
					$dt = explode(" ",$created);
					$dt = explode("-",$dt[0]);
					$dateformat=$dt['1']."/".$dt['2']."/".$dt['0'];
					echo $dateformat;						
				?>
			</td>
			
		</tr>

<input type="hidden" id="allcases" name="allcases" value="<?php echo $cscount; ?>"/>
<input type="hidden" id="pjid" name="pjid" value="<?php echo $pjid; ?>"/>
<?php
	}
?>
</table>
<?php
}
else {
	?>
	<center style="font-weight:normal;color:#FF0000;padding:10px;">No Tasks for this Template</center>
	<?php
}

?>

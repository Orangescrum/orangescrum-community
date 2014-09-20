<?php
if(isset($QckCaseFiles) && count($QckCaseFiles))
{
	?>
	<table cellpadding="0" cellspacing="0" align="left">
	<?php
	foreach($QckCaseFiles as $getFiles)
	{
	?>
	<tr>
		<td style="line-height:24px;" align="left">
			<table cellpadding="0" cellspacing="0" align="left">
				<tr>
					<td align="left">
						<a href="<?php echo HTTP_ROOT; ?>easycases/download/<?php echo $getFiles['CaseFile']['file']; ?>" style="font-weight:normal;"><?php echo $this->Format->shortLength($getFiles['CaseFile']['file'],60); ?></a>
					</td>
					<td align="left" style="padding-left:10px;">
						<span id="closeimg<?php echo $getFiles['CaseFile']['id']; ?>" onClick="removeCaseFile('<?php echo $getFiles['CaseFile']['id']; ?>','<?php echo $getFiles['CaseFile']['id']; ?>','<?php echo $getFiles['CaseFile']['file']; ?>')" style="cursor:pointer">
							<img src="<?php echo HTTP_IMAGES;?>images/rem.png" border="0" title="Remove" alt="Remove" width="14px" height="14px"/>
						</span>
						<span style="display:none" id="ajxfileload<?php echo $getFiles['CaseFile']['id']; ?>">
							<img src="<?php echo HTTP_IMAGES;?>images/del.gif" border="0" title="Loading" alt="Loading"/>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	}
	?>
	</table>
	<input type="hidden" id="editimg" name="editimg" value="<?php echo count($QckCaseFiles); ?>"/>
	<?php
}
else
{
?>
	<input type="hidden" id="editimg" name="editimg" value="<?php echo count($QckCaseFiles); ?>"/>
<?php
}
?>

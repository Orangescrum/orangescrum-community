<style>
.makeHover_new{color:#ffffff;font:13px Arial;letter-spacing:1px;text-decoration:none;}
.makeHover_new:hover{text-decoration:underline;color:#FFF}
</style>
<table cellpadding="0" cellspacing="0" border="0"  width="960px" style="padding-bottom:4px">
	<tr>
		<td align="left" valign="top" colspan="6" style="padding:0 0 10px 0">
			<span style="font:bold 12px verdana;color:#F86402">Select Projects</span>
		</td>
	 </tr>
	<?php
	$s=0;
	$k=0;
	if(count($allProjArr))
	{
		foreach($allProjArr as $prjArr)
		{
		$k++;
		$n = $s%7;
		if($n == 0)
		{
		?>
		  <tr height="28px" >
		<?php
		}
		?>
			<td valign="top" align="left"  >
				<a href="javascript:jsVoid();" style="color:#FFFFFF;text-decoration:none;" onClick="updateAllProj('proj_<?php echo $prjArr['Project']['uniq_id']; ?>','<?php echo $prjArr['Project']['uniq_id']; ?>','<?php echo $page; ?>');">
					<span class="makeHover_new"><?php echo $this->Format->shortLength($prjArr['Project']['name'],16); ?></span>
				</a>
			</td>
		  <?php 
		  $s++;
		  $p = $s%7;
		  if($p == 0)
		  {
		  ?>
			  </tr>
		  <?php
		  }
		}
	}
	else
	{
	?>
	<tr>
		<td colspan="7" align="center" style="font:12px verdana; color:#FFF;">
			No Disable Projects
		</td>
	</tr>
	<?php
	}
	?>
	</tr>
	<?php
	if($type == "enabled")
	{
	?>
	<tr>
		<td colspan="7" align="right">
			<span class="makeHover_new" style="color:#F9731B;cursor:pointer" onclick="displayAllProjects('<?php echo $page; ?>','disabled');">Disabled Projects</span>
		</td>
	</tr>
	<?php
	}
	else
	{
	?>
	<tr>
		<td colspan="7" align="right">
			<span class="makeHover_new" style="color:#F9731B;cursor:pointer;" onclick="displayAllProjects('<?php echo $page; ?>','enabled');">Enabled Projects</span>
		</td>
	</tr>
	<?php
	}
	?>
</table>

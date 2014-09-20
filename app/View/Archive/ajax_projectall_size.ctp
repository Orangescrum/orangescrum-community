<?php
$usedspace = $this->Casequery->usedSpace('',$company);
if($user_subscription['storage'] != "Unlimited") {
	if($type != 'top') {
		echo "Using ".$usedspace." Mb of ".$user_subscription['storage']." Mb";
	}
	$percentage = $this->Casequery->fullSpace($usedspace,$user_subscription['storage']);
	?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<?php
				if($percentage >= 100) {
					$percentage = 100;
				}
				echo $percentage."% full";
				$width = $percentage;
				?>
			</td>
			<?php if($percentage >= 1) { ?>
			<td style="padding-left:5px;">
				<div style="background:#FFFFFF;border:1px solid #7B758E;width:100px;">
					<?php /*?><div style="width:<?php echo $width; ?>%;background:<?php if($percentage > 100) { echo "#FF0000"; } elseif($percentage > 90) { echo "#F26D2A"; }  else { echo "#05B322"; } ?>;color:#FFFFFF;line-height:12px;-moz-border-radius:1px; -webkit-border-radius:1px;border-radius:1px;">&nbsp;</div><?php */?>
					<div style="height:8px; width:<?php echo $width; ?>%;" <?php if($percentage >= 90) { ?> class="ms_progress_red" <?php } else { ?> class="ms_progress_green" <?php } ?>></div>
				</div>
				
				
			</td>
			<?php } ?>
		</tr>
	</table>
	<?php
}
else {
	echo "Using ".$usedspace." Mb of storage";
}
?>
<table  border="0" style="color:#4B4B4B;font-size:13px;" align="left">
	<tr>
		<td width="40px" valign="top">
			<input type="checkbox" name="chk_all" id="chk_all" value="all" class="checkbox_style" onClick="checkedAllRes()" checked="checked">&nbsp;All
		</td>
		<td align="left" valign="top">
			<?php
			if(count($quickMem))
			{
			?>
				<a href="javascript:void(0);" onclick="showHideMemDtls('viewmemdtls')"><i>Select the member(s) who you intend to send email notification about this posting.</i></a><br/>
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" valign="top">
			<div style="display:none" id="viewmemdtls">
			<?php
			$i = 0;
			$j = 0;
			$k = 0;
			if(count($quickMem))
			{
			?>
			<table cellpadding="0" cellspacing="0" align="left" style="border:0px solid #FF0000;">
				<?php
				$chked = "";
				foreach($quickMem as $getmems)
				{
					$j = $i%4;
					if($j == 0)
					{
					?>
					<tr>
					<?php
					}
					$chked = "";
					if($easycaseid) {
						if(in_array($getmems['User']['id'],$csUsrEml,true)) {
							$chked = "checked";
						}
					}
					elseif(($getmems['User']['istype'] == 1 || $getmems['User']['istype'] == 2) && $_SERVER['SERVER_ADDR'] != "192.168.2.101") {
						$chked = "checked";
					}
					?>
					<td align="left" valign="top" style="font-weight:normal;padding-right:10px;padding-top:5px;">
						<input type="checkbox" name="data[Easycase][user_emails][]" id="chk_<?php echo $i?>" class="checkbox_style" onClick="removeAll()" value="<?php echo $getmems['User']['id']?>" <?php echo $chked ; ?>/>&nbsp;<?php echo $this->Format->formatText($getmems['User']['name']); ?>
						
						<input type="hidden" name="data[Easycase][proj_users][]" id="proj_users"  value="<?php echo $getmems['User']['id']?>" readonly="true"/>
					</td>
					<?php
					$i = $i+1;
					$k = $i%4;
					if($k == 0)
					{
					?>
					</tr>
					<?php
					}
				}
				?>
			</table>
			<?php
			}
			?>
			</div>
		</td>
	</tr>
</table>
<input type="hidden" name="hidtotproj" id="hidtotproj" value="<?php echo $i?>">
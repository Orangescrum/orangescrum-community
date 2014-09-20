<script>
$(function () {
	$('[rel=tooltip], #main-nav span, .loader').tipsy({gravity:'s', fade:true});
});
</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left" valign="top" style="color:#878787" class="wrapword">
			<span class="case_fieldprof">Email to: </span>
			<?php
			$names = ""; $i = 0;
			foreach($quickMem as $getmems)
			{
				$i++;
				$names.="<font title='".$getmems['User']['name']."' color='#5A5A5A'>".$this->Format->shortLength($getmems['User']['name'],17)."<font>, ";
				?>
				<input type="hidden" name="data[Easycase][user_default][]" value="<?php echo $getmems['User']['id']; ?>" id="default_<?php echo $i; ?>"/>
				<?php
			}
			echo trim(trim($names),",");
			?>
			<input type="hidden" name="totaldefault" id="totaldefault" value="<?php echo $i?>" readonly="true"/>
		</td>
	</tr>
</table>


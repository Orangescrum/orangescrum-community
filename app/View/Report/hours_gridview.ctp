<div class="hr_spent_div">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="left" colspan="2">
			<table border="0" cellpadding="0" width=100% class="hr_spent_tbl">
				<tr class="hr_spent_row">
					<td align="left" class="tophead_first">
						Name
					</td>
					<td align="right" class="tophead">
						Replies
					</td>
					<td align="right" class="tophead">
						Resolved
					</td>
					<td align="right" class="tophead">
						Hours Spent
					</td>
				</tr>
				<?php
				if(!empty($easycases)) {
					$count=0; $clas = "";
					$thrs = array();
					$mnhrs = array();
					foreach($easycases as $k=>$v){
						$count++;
						$thrs[] = $v['0']['tot_hours'];
						if(isset($mainhrarr[$v['e']['user_id']])){
							$mnhrs[] = $mainhrarr[$v['e']['user_id']];
						}
						if($count %2==0) { $clas = "row_col"; }
						else { $clas = "row_col_alt"; }
						?>
						<tr class="<?php echo $clas?>" id="userlist<?php echo $count;?>" <?php if($prjAllArr['Project']['isactive'] == 2) { ?> style="background-color:#FEE2E2;" <?php } ?>>	
							<td class="hr_spent_row"><?php echo $v['u']['devname'];?></td>
							<td align="right" class="hr_spent_row_lower"><?php echo $v['0']['replies_no'];?></td>
							<td align="right" class="hr_spent_row"><?php echo (isset($resarr[$v['e']['user_id']])) ? $resarr[$v['e']['user_id']] : 0 ;?></td>
							<td align="right" class="hr_spent_row" style="font-weight:bold;"><?php  if(isset($mainhrarr[$v['e']['user_id']])) { echo ($mainhrarr[$v['e']['user_id']] + $v['0']['tot_hours']); }else { echo $v['0']['tot_hours'] ; } ?></td>
						</tr>	
					<?php }  ?>
						<input type="hidden" id="thrs" value="<?php echo array_sum($thrs) + array_sum($mnhrs); ?>" />	
			<?php	}else{  ?>
					<tr>
						<td class="no_match_td" colspan="4" align="center"><?php echo "No Results Found"; ?></td>
					</tr>
				<?php }?>
	</table>
	</td></tr>
</table>
</div>

<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<?php /*?><tr>
		<td align="left">
			<div style="float:left;margin-top:5px;"><h1 class="toplink">Task Template > Manage</h1></div>
		</td>
	</tr><?php */?>
	<tr>
		<td align="left">
			<?php /* if(count($TempalteArray)) { ?>
			<div style="float:right;" >
				<a href="<?php echo HTTP_ROOT; ?>users/add_template" style="text-decoration:none;" ><div class="btn topbtn" id="newcase_but">Create Template</div></a>
			<?php }*/ ?>
			</div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:5px;">
			<table border="0" style="border:1px solid #DCDCDC" width=100%>
				<tr height="28px">
					<td  style="padding-left:4px;" align="left" class="tophead">Title</td>                     
					<td  style="padding-left:4px;" align="left" class="tophead">Created Date</td>
					<td align="center" width="100px" class="tophead">Action</td>
				</tr>
				<?php
				$count=0; $clas = "";
				$totCase = 0; 
				if(count($TempalteArray)) {
				foreach($TempalteArray as $k => $template) {
				$count++;
				if($count %2==0) { $clas = "row_col"; } else { $clas = "row_col_alt"; }
				?>
				<tr class="<?php echo $clas?>" height="22px" id="mslisting<?php echo $count; ?>" <?php if($template['case_templates']['is_active'] == 0){?>style="background-color:#C8C8C8;"<?php }?>>
					<td style="padding-left:2px;">
							<?php echo $this->Format->formatText($template['case_templates']['name']);?>
					</td>
					<td style="padding-left:5px;" align="left">
						<?php
						/*$dt = explode(" ",$template['case_templates']['created']);
						$dt = explode("-",$dt[0]);
						$dateformat=$dt['1']."/".$dt['2']."/".$dt['0'];
						echo $dateformat;	*/	
						
						$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$template['case_templates']['created'],"datetime");
						$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATE,"date");
						echo $dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT,$gmdate,'date');				
						?>
					</td>
					<td  align="center">
						<div style="width:75px;text-align:center;">
					<?php			
					if($template['case_templates']['user_id'] == SES_ID || SES_TYPE == 1)
					{
					?>
						<?php
							if($template['case_templates']['is_active'] == 1){
						?>
								<a href="javascript:void(0);" onClick="inacttemplate(<?php echo $template['case_templates']['id']?>);">
								<div class="act_dis" rel=tooltip  title="Deactivate"></div>
								</a>&nbsp;
						<?php }else{ ?>
								<a href="javascript:void(0);" onClick="acttemplate(<?php echo $template['case_templates']['id']?>);">
								<div class="act_dis" rel=tooltip  title="Activate"></div>
								</a>&nbsp;
						<?php } ?>
						<a href='<?php echo HTTP_ROOT; ?>users/add_template/<?php echo $template['case_templates']['id']; ?>' class="makeHover"><div class="act_set" style="margin-left:5px;" rel=tooltip  title="Edit"></div></a>
						<a href="javascript:void(0);" onClick="deltemplate(<?php echo $template['case_templates']['id'];?>);">
							<div class="act_del" style="margin-left:5px;" rel="tooltip" title="Delete"></div>
						</a>
					<?php
					}
					?>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="7" align="center">
						<div id="caseImg<?php echo $count; ?>" style="display:none;width:100%;text-align:center;padding-top:5px;">
							<img src="<?php echo HTTP_IMAGES; ?>images/case_thread_loader.gif" alt="loading..." title="loading..."/>
						</div>
						<div id="caseDiv<?php echo $count; ?>" style="display:none;text-align:left;color:#666;" class="lidata_div"></div>
					</td>
				</tr>
				<?php 
				}
				}
				else {
					?>
					<td colspan="3" align="center">
						<!--<span style="width:100%;text-align:center;font-size:14px;color:#FF0000;list-style:none;height:60px;">No data available</span>-->
						<a href="<?php echo HTTP_ROOT;?>users/add_template/" style="margin-right:20px;">
							<div class="button" id="newcase_but">+ New Template</div>
						</a>
					</td>
					<?php
				}
				?>
			</table>
		</td>
	</tr>
</table>
<?php
if($count_mile != 0)
{
?>
<table align="right">
	<tr>
		<td align="center" style="font-size:12px;">
			<?php
			$numofpages = $total_records / $page_limit;
			for($j = 1; $j <= $numofpages; $j++) { }
			$start = $page*$page_limit - $page_limit;
			if($page == $j)
			{
			?>
				<?php echo $start+1?> - <?php echo $total_records?> of <?php echo $total_records?>
			<?php
			}
			else
			{
			?>
				<?php echo $start+1?> - <?php echo $page*$page_limit?> of <?php echo $total_records?>
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<?php
			if($page_limit < $total_records)
			{
				if($page != 1)
				{
					$pageprev = $page-1;
					echo "&nbsp;<a href=\"".HTTP_ROOT."users/manage_template/?page=$pageprev\" style='text-decoration:none'><span class=\"active_box\" >&lt;&nbsp;Prev</span></a>&nbsp;";
				}
				else
				{
					echo "<span class=\"inactive_box\" style='border:0px solid #000000;'>&lt;&nbsp;Prev</span>";
				}
				$numofpages = $total_records / $page_limit;

				for($i = 1; $i <= $numofpages; $i++)
				{
					if($i == $page)
					{
						echo ("&nbsp;<span class='inactive_box'>".$i."</span>&nbsp;");
					}
					else
					{
						echo "&nbsp;<a href=\"".HTTP_ROOT."users/manage_template/?page=$i\" style='text-decoration:none'><span class=\"active_box\">$i</span></a> ";
					}
				}
				if(($total_records % $page_limit) != 0)
				{
					if($i == $page)
					{
						echo ("&nbsp;<span class='inactive_box'>".$i."</span>");
					}
					else
					{
						echo "&nbsp;<a href=\"".HTTP_ROOT."users/manage_template/?page=$i\" style='text-decoration:none'><span class=\"active_box\">$i</span></a> ";
					}
				}
				if(($total_records - ($page_limit * $page)) > 0)
				{
					$pagenext = $page+1;
					echo "&nbsp;<a href=\"".HTTP_ROOT."users/manage_template/?page=$pagenext\" style='text-decoration:none'><span class=\"active_box\" >Next&nbsp;&gt;</span></a>";
					
				}
				else
				{
					echo "&nbsp;<span class=\"inactive_box\" style='border:0px solid #000000;'>Next&nbsp;&gt;</span>";
				}
			}
			?>
		</td>
	</tr>
</table>
<?php } ?>

<input type="hidden" id="totcount" name="totcount" value="<?php echo $count; ?>"/>
</script>
<script>
function viewEditDeleteHover(a,b)
{
	document.getElementById(b).style.display='block';
}
function viewEditDelete(a,b)
{
	document.getElementById(b).style.display='none';
}
function deltemplate(id)
{
	
	var conf = confirm("Are you sure to delete this template?");
	if(conf == true) {
		var strURL = "<?php echo HTTP_ROOT?>";
		var strURL = strURL+'users/manage_template/'+id;
		window.location = strURL;
	}
	else {
		return false;
	}
}
function acttemplate(id)
{
	
	var conf = confirm("Are you sure to activate this template?");
	if(conf == true) {
		var strURL = "<?php echo HTTP_ROOT?>";
		var strURL = strURL+'users/manage_template/?act='+id;
		window.location = strURL;
	}
	else {
		return false;
	}
}
function inacttemplate(id)
{
	
	var conf = confirm("Are you sure to deactivate this template?");
	if(conf == true) {
		var strURL = "<?php echo HTTP_ROOT?>";
		var strURL = strURL+'users/manage_template/?inact='+id;
		window.location = strURL;
	}
	else {
		return false;
	}
}
</script>

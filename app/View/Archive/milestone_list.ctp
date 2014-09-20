<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td align="left" colspan="2"></td>
	</tr>
	<?php if(count($list)!="0"){?>
	<tr>
	<td></td>
	</tr>
	<?php } ?>
	<tr>
		<td style="padding-top:10px;">
			<?php
			$count=0;
			?>
			<table border="1" style="border:1px solid #DCDCDC" width="100%">
				<tr height="28px">
					<td style="width:10px;" align="center" class="tophead">
					<div class="sel_bg">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<input id="allcase" type="checkbox" onclick="caseallcheck()" style="cursor: pointer;">
									</td>
									<td valign="middle">
										<div class="popup_link_case_proj_parent" style="position:relative;z-index:0;" align="left">
											<div class="popup_link_case_proj">
												<a href="javascript:jsVoid();" onclick="open_pop(this)" style="font-weight:normal;">
													<span><img src="<?php echo HTTP_IMAGES; ?>images/dwn_arr.png" style="margin-left:4px"></span>
												</a>
											</div>
											<div class="popup_option" id="popup_option" style="display:none;position:absolute;left:-22px;">
												<div class="pop_arrow_new" style="position:absolute;"></div>
												<div class="popup_con_menu" align="left" style="left:-1px; min-width:50px;padding: 2px 8px">
													<div align="left">    
														<div>
															<a href="javascript:void(0);" onclick="caseall();open_pop(this)" style="font-size:13px;font-weight:normal;">All</a>
														</div>
										
										
														<div>
															<a href="javascript:void(0);" onclick="casenone();open_pop(this)" style="font-size:13px;font-weight:normal;">None</a>
														</div>
										
										
														<div id="restore">
															<a href="javascript:void(0);" onclick="milestonerestoreall();" style="font-size:13px;font-weight:normal;">Restore</a>
														</div>
										
														<div id="remove">
															<a href="javascript:void(0);" onclick="removeallmilestone();" style="font-size:13px;font-weight:normal;">Remove</a>
														</div>
													</div>
												</div>
											</div>
									</td>
								</tr>
							</table>
							
						</div>
					</td>
					<td  style="padding-left:4px;"valign="middle" align="left" class="tophead">Title</td>
                         <td  style="padding-left:4px;width:140px;"valign="middle" align="left" class="tophead">Project Name</td>
					<td  style="padding-left:4px;width:80px;"valign="middle" align="left" class="tophead">Created By</td>
					<td  style="padding-left:4px;width:160px;"valign="middle" align="left" class="tophead">Start Date</td>
					<td  style="padding-left:4px;width:160px;"valign="middle" align="left" class="tophead">End Date</td>
					<!--<td align="center" width="80px">Action</td>-->
				</tr>
			<?php
				if(count($list)) {
				$repeatLastUid = "";
				$clas = "";
				$totCase = 0; 
				//App::import('Model','Easycase'); $Easycase = new Easycase();
				//$Easycase->recursive = -1;
				foreach($list as $lis)
				{ 
					$count++;
					if($count %2==0)
					{ 
						$clas = "row_col";
					}
					else
					{
						$clas = "row_col_alt";
					}
					
			?>
				<tr class="<?php echo $clas?>" height="25px" id="cslisting<?php echo $count; ?>">
					<td align="left" style="padding-left:10px">
						<input id="case<?php echo $count; ?>" onclick="onecheckmilestone('case<?php echo $count;?>','<?php echo $count;?>')" value="<?php echo $lis['Milestone']['uniq_id'];?>" type="checkbox" style="cursor: pointer;">
					</td>
					<td align="left" style="padding-right:4px;">
						<?php echo $lis['Milestone']['title']?>
					</td>
					<td align="left" style="padding-left:5px;">
						<?php 
						if($lis['Milestone']['project_id'])
						{
							$projectname = $this->Casequery->getpjname($lis['Milestone']['project_id']);
							echo $projectname;
						}
						?>
					</td>
					<td align="left" style="padding-left:5px;">
						<?php
					          if($lis['Milestone']['user_id']){
						          $usrname = $this->Casequery->getusrname($lis['Milestone']['user_id']);
						          echo $this->Format->shortLength($usrname['User']['name'],20);
					          }
					     ?>
					</td>
					<td align="left" style="padding-left:4px;">
				<?php 
						echo $this->Datetime->dateFormatOutputdateTime_day($lis['Milestone']['start_date'],GMT_DATE,'date');		
						?>
						
					</td>
					<td align="left" style="padding-left:4px">
						<?php 
							echo $this->Datetime->dateFormatOutputdateTime_day($lis['Milestone']['end_date'],GMT_DATE,'date');
							
						?>
					</td>
					
					<input type="hidden" id="csn<?php echo $count;?>" value="<?php echo $lis['Milestone']['uniq_id'];?>">
				</tr>
				<?php } ?>
				<?php 
					}
					else
					{
				?>
				<tr>
					<td colspan="7" align="center">
						<div style="width:100%;text-align:center;font-size:13px;color:#666666;list-style:none;margin:10px;">No Milestone in the Archive</div>
					</td>
				</tr>
				<?php
					}
				?>
			</table>
		</td>
	</tr>
	
</table>

<?php
if($total_records)
{
?>
<table cellpadding="0" cellspacing="0" border="0" align="right" >
	<tr>
		<td align="center" style="padding-top:5px;">
			<div class="show_total_case" style="font-weight:normal;color:#000;font-size:12px;">
				<?php echo $this->Format->pagingShowRecords($total_records,$page_limit,$casePage); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center" style="padding-top:5px">
		<ul class="pagination">
		<?php
		$page = $casePage;
		if($page_limit < $total_records)
		{
			$numofpages = $total_records / $page_limit;
			if(($total_records % $page_limit) != 0)
			{
				$numofpages = $numofpages+1;
			}
			$lastPage = $numofpages;
			
			$k = 1;
			$data1 = "";
			$data2 = "";
			if($numofpages > 5)
			{
				$newmaxpage = $page+2;
				if($page >= 3){
					$k = $page-2;
					$data1 = "...";
				}
				if(($numofpages - $newmaxpage) >= 2){
					if($data1){
						$data2 = "...";
						$numofpages = $page+2;
					}
					else{
						if($numofpages >= 5){
							$data2 = "...";
							$numofpages = 5;
						}
					}
				}
			}
			if($data1)
			{
				echo "<li><a href='javascript:jsVoid();' class=\"button_act\" onClick=\"casePagingCase(1)\">&laquo; First</a></li>";
				echo "<li class='hellip'>&hellip;</li>";
			}
			if($page != 1)
			{
				$pageprev = $page-1;
				echo "<li><a href='javascript:jsVoid();' class=\"button_act\" onClick=\"casePagingCase('".$pageprev."')\">&lt;&nbsp;Prev</a></li>";
			}
			else
			{
				 echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
			}
			for($i = $k; $i <= $numofpages; $i++)
			{
				if($i == $page) {
					echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">".$i."</a></li>";
				}
				else {
					echo "<li><a href='javascript:jsVoid();' class=\"button_act\" onClick=\"casePagingCase('".$i."')\">".$i."</a></li>";
				}
			}
			if(($total_records - ($page_limit * $page)) > 0)
			{
				$pagenext = $page+1;
				echo "<li><a href='javascript:jsVoid();' class=\"button_act\" onClick=\"casePagingCase('".$pagenext."')\">Next&nbsp;&gt;</a></li>";
			}
			else
			{
				echo "<li><a href='javascript:jsVoid();' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
			}
			if($data2)
			{
				echo "<li class='hellip'>&hellip;</li>";
				echo "<li><a href='javascript:jsVoid();' class=\"button_act\" onClick=\"casePagingCase('".floor($lastPage)."')\">Last &raquo;</a></li>";
			}
		}
		?>
		</ul>
		</td>
	</tr>
</table>
<?php
}
?>
<?php //echo $this->element('sql_dump'); ?>
<input type="hidden" id="all" value="<?php echo $count;?>">
<input type="hidden" id="pjid" value="<?php echo $pjid;?>">

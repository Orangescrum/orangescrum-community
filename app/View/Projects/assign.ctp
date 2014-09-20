<script>
function getPrjid(id)
{
	var x= document.getElementById(id).value;
	if(x){
		window.location='<?php echo HTTP_ROOT; ?>projects/assign/?pid='+x;
	}
	else {
		window.location='<?php echo HTTP_ROOT; ?>projects/assign/';
	}
}
function caseSetting(proj,mem)
{
	document.getElementById('topmostdiv').style.display='block';
	var op = 100;
	document.getElementById('upperDiv').style.filter="alpha(opacity="+op+")";
	document.getElementById('upperDiv').style.MozOpacity = 1;
	var done=1;
	
	if(document.getElementById(proj).value.trim() == "")
	{
		var msg="Please selct a Project to Assign Memebr(s)!";
		document.getElementById(proj).focus();
		done=0;
	}
	if(done == 1)
	{
		document.getElementById('upperDiv').style.display='none';
		return true;
	}
	else
	{
		document.getElementById('upperDiv').style.display='block';
		document.getElementById('upperDiv').style.backgroundColor='#FADAD8';
		document.getElementById('upperDiv').style.color='#FF0000';
		document.getElementById('upperDiv').value='';
		document.getElementById('upperDiv').innerHTML=msg;
		setTimeout('removeMsg()',7000);
		return false;
	}
}
function getProjectId(id,value)
{
	document.getElementById(id).value=value;
}
</script>
<div class="row-fluid">
	<article class="span12 data-block nested">
		<div class="data-container">
			<section class="tab-content">
				<div class="tab-pane" id="horizontal">
<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="">
				<tr height="35px">
					<td align="center" width="100%">
						<?php echo $this->Form->create('ProjectUser',array('url'=>'/projects/assign/','onsubmit'=>'return caseSetting(\'sel_prj\',\'list_2_serialised\')')); ?>
						<table border="0" cellspacing="4" cellpadding="4" align="center" >
							<tr>
								<td valign="top" align="center" width="100%">
									<table cellpadding="2" cellspacing="2" align="center" width="100%" border="0">
										<tr height="25px">
											<td valign="top" class="label-normal" style="font-weight:bold;" align="center">
												Project:
												<select id="sel_prj" name="sel_prj" class="text_field"  onchange="getPrjid('sel_prj')" style="padding:5px 4px;width:270px">
												<option value="">[Select]</option>
												<?php 
												foreach($projArr as $prjarr)
												{
												?>
													<option 
													<?php if($pid == $prjarr['Project']['uniq_id']) { ?> selected <?php } ?> 
													value="<?php echo $prjarr['Project']['uniq_id']; ?>"><?php echo $this->Format->formatText($prjarr['Project']['name']); ?>
													</option>
												<?php
												}
												?>
                                                </select>
											</td>
										</tr>
                                        <tr><td><div id="divide"></div></td></tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="label-normal" align="center">
                                    <table cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td align="right" valign="top">
												<table cellpadding="0" cellspacing="0" border="0">
													<tr height="20px">
														<td align="center" class="label-normal" style="font-weight:bold;">
															Member(s) Available - <?php echo count($memsAvlArr); ?>
														</td>
													</tr>
													<tr height="30px">
														<td align="center" class="text">
															Select:
															<a href='#' onclick='return $.dds.selectAll("list_1");' class="footerLnk" style="text-decoration:underline">All</a>&nbsp;
															<a href='#' onclick='return $.dds.selectNone("list_1");' class="footerLnk" style="text-decoration:underline">None</a> 
														</td>
													</tr>
													<tr height="10px" style="border:1px solid #000000;">
														<td align="left" >
															<ul id="list_1" style="height:10px;width:300px;overflow:auto;" class="label-normal">
																<?php
																$memAvlUids = "";
																foreach($memsAvlArr as $getMemsAvl)
																{
																?>
																	<li id="<?php echo $getMemsAvl['User']['id']?>" title="<?php echo stripslashes($getMemsAvl['User']['email'])?>">
																		<?php echo $this->Format->formatText($getMemsAvl['User']['name'])?>
																		 <?php 
																		  $usr_typ_name='';
																		  if($getMemsAvl['CompanyUser']['user_type'] == 1){ 
																			$colors= 'color:Green';$usr_typ_name='Owner';
																		  }else if($getMemsAvl['CompanyUser']['user_type'] == 2){
																				$colors= 'color:Red';$usr_typ_name='Admin';
																		  }else if($getMemsAvl['CompanyUser']['user_type'] == 3 && $role != 3){
																				//$colors= 'color:Blue';$usr_typ_name='Member';
																		  }
																		  ?>
																		  <span style="width:100%;text-align:center;font-size:10px;<?php echo $colors;?>">&nbsp;&nbsp;&nbsp;<?php echo $usr_typ_name;?></span>
																	</li>
																<?php
																$memAvlUids.=$getMemsAvl['User']['id'].",";
																}
																$memAvlUids = substr($memAvlUids,0,-1);
																?>
															</ul>
															<span style="visibility:hidden"><textarea id='list_1_serialised' name="data[ProjectUser][mem_avl]" cols="35" rows="1"><?php echo $memAvlUids; ?></textarea></span>
														</td>
													</tr>
												</table>
											</td>
											<td width="30px"></td>
											<td align="left" valign="top">
												<table cellpadding="0" cellspacing="0" border="0">
													<?php
													if($pid)
													{
													?>
													<tr height="20px">
														<td align="center" class="label-normal" >
															<font style="color:#575A5D;font-weight:bold">Member(s) Assigned - <?php echo count($memsExtArr); ?></font>	
														</td>
													</tr>
													<tr height="30px">
														<td align="center" class="text">
															Select: 
															<a href='#' onclick='return $.dds.selectAll("list_2");' class="footerLnk" style="text-decoration:underline">All</a> 
															<a href='#' onclick='return $.dds.selectNone("list_2");' class="footerLnk" style="text-decoration:underline">None</a> 
														</td>
													</tr>
													<tr>
														<td align="center" style="width:320px;">
															<ul id="list_2" class="label-normal" style="height:50px;width:300px;overflow:auto;" >
															<?php
															$memExtUids = "";
															foreach($memsExtArr as $getMemsExt)
															{
															?>
																<li id="<?php echo $getMemsExt['User']['id']?>" title="<?php echo stripslashes($getMemsExt['User']['email'])?>">
																	<?php echo $this->Format->formatText($getMemsExt['User']['name'])?>
																	<?php 
																		  $usr_typ_name='';
																		  if($getMemsExt['CompanyUser']['user_type'] == 1){ 
																			$colors= 'color:Green';$usr_typ_name='Owner';
																		  }else if($getMemsExt['CompanyUser']['user_type'] == 2){
																				$colors= 'color:Red';$usr_typ_name='Admin';
																		  }else if($getMemsExt['CompanyUser']['user_type'] == 3 && $role != 3){
																				//$colors= 'color:Blue';$usr_typ_name='Member';
																		  }
																		  ?>
																		  <span style="width:100%;text-align:center;font-size:10px;<?php echo $colors;?>">&nbsp;&nbsp;&nbsp;<?php echo $usr_typ_name;?></span>
																</li>
															<?php
															$memExtUids.=$getMemsExt['User']['id'].",";
															}
															$memExtUids = substr($memExtUids,0,-1);
															?>
															</ul>
															<span style="visibility:hidden"><textarea id='list_2_serialised' name="data[ProjectUser][mem_ext]" cols="35" rows="1"><?php echo $memExtUids; ?></textarea></span>
														</td>
													</tr>
													<?php
													}
													else
													{
													?>
													<tr>
														<td align="center" style="padding-top:45px">
															<font color="#FF0000" style="font:bold 12px verdana;">Select a Project to Assign Member(s)</font>
															&nbsp;<input type="hidden" id="list_2" name="list_2" value=""/>
															<input type="hidden" id="list_2_serialised" name="data[ProjectUser][mem_ext]" value=""/>
														</td>
													</tr>
													<?php
													}
													?>
												</table>
											</td>
										</tr>
										<?php /*?><tr>
											<td align="right" valign="top">
												<table cellpadding="0" cellspacing="0" border="0">
													<tr height="5px">
														<td align="center" class="label-normal" style="font-weight:bold;">
															Customer(s) Available - <?php echo count($custAvlArr)?>
														</td>
													</tr>
													<tr height="30px">
														<td align="center" class="text">
															Select:
															<a href='#' onclick='return $.dds.selectAll("list_3");' class="footerLnk" style="text-decoration:underline">All</a>&nbsp;
															<a href='#' onclick='return $.dds.selectNone("list_3");' class="footerLnk" style="text-decoration:underline">None</a> 
														</td>
													</tr>
													<tr>
														<td align="left" title="Press CTRL & select multiple resource">
															<ul id="list_3" style="height:10px;width:300px;overflow:auto;" class="label-normal">
																<?php
																$cusAvlUids = "";
																foreach($custAvlArr as $getCustAvl)
																{
																?>
																	<li id="<?php echo $getCustAvl['User']['id']?>" title="<?php echo stripslashes($getCustAvl['User']['name'])?>">
																		<?php echo $this->Format->formatText($getCustAvl['User']['name'])?>
																	</li>
																<?php
																$cusAvlUids.=$getCustAvl['User']['id'].",";
																}
																$cusAvlUids = substr($cusAvlUids,0,-1);
																?>
															</ul>
															<span style="visibility:hidden"><textarea id='list_3_serialised' name="data[ProjectUser][cust_avl]" cols="35" rows="1"><?php echo $cusAvlUids; ?></textarea></span>
														</td>
													</tr>
												</table>
											</td>
											<td width="30px"></td>
											<td align="left" valign="top">
												<table cellpadding="0" cellspacing="0" border="0">
													<?php
													if($pid)
													{
													?>
													<tr height="5px">
														<td align="center" class="label-normal" >
															<font style="color:#575A5D;font-family:verdana;font-size:11px;font-weight:bold">Customer(s) Assigned - <?php echo count($custExtArr)?></font>
														</td>
													</tr>
													<tr height="30px">
														<td align="center" class="text">
															Select: 
															<a href='#' onclick='return $.dds.selectAll("list_4");' class="footerLnk" style="text-decoration:underline">All</a> 
															<a href='#' onclick='return $.dds.selectNone("list_4");' class="footerLnk" style="text-decoration:underline">None</a> 
														</td>
													</tr>
													<tr>
														<td align="center" style="width:320px;">
															<ul id="list_4" style="height:10px;width:300px;overflow:auto;" class="label-normal">
															<?php
															$cusExtUids = "";
															foreach($custExtArr as $getCustExt)
															{
															?>
																<li id="<?php echo $getCustExt['User']['id']?>" title="<?php echo stripslashes($getCustExt['User']['email'])?>">
																	<?php echo $this->Format->formatText($getCustExt['User']['name'])?>
																</li>
															<?php
															$cusExtUids.=$getCustExt['User']['id'].",";
															}
															$cusExtUids = substr($cusExtUids,0,-1);
															?>
															</ul>
															<span style="visibility:hidden"><textarea id='list_4_serialised' name="data[ProjectUser][cust_ext]" cols="35" rows="1"><?php echo $cusExtUids; ?></textarea></span>	 
														</td>
													</tr>
													<?php
													}
													else
													{
													?>
													<tr>
														<td align="center" style="padding-top:45px">
															<font color="#FF0000" style="font:bold 12px verdana;height:50px">Select a Project to Assign Customer(s)</font>
															&nbsp;<input type="hidden" id="list_212" name="list_212" value=""/>
															<input type="hidden" id="list_4_serialised" name="data[ProjectUser][cust_ext]" value=""/>
														</td>
													</tr>
													<?php
													}
													?>
												</table>
											</td>
										</tr><?php */?>
									</table>

								</td>
							</tr>
							<tr>
								<td class="label-normal" align="center">
									<div style="position:relative;top:-8px;">
										<input type="hidden" value="<?php echo $projId; ?>" name="data[ProjectUser][project_id]" id="hid_ProjId"/>
										<button type="submit" value="Save" name="submit_Project" style="margin-left:3px;margin-top:5px;width:60px" class="blue small">Save</button>
										or
										<a href="<?php echo HTTP_ROOT; ?>dashboard">Cancel</a>
									</div>
								</td>
							</tr>
						</table>
						</form>
					</td>	
				</tr>
			</table>			
		</td>
	</tr>
</table>
</div>
			</section>
		</div>
	</article>
</div>	
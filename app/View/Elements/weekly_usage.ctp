<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center">
			<table cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td align="right">
						<img src="<?php echo HTTP_ROOT.'img/images/print.png';?>" onclick="PrintDiv()" title="Print this report" style="cursor:pointer">
					</td>
				</tr>
				<tr>
					<td align="center">
					<div id="divToPrint" style="font-family:verdana;font-size:12px;color:#333;padding:0;margin:5px 0 0;border:1px solid #ccc;float:left;width:100%;">
					<div style="background:#353C42;padding:5px 10px;margin-bottom:15px;">
						<div style="float:left;color:#FFF;font-size:26px;font-weight:bold;"><?php echo ucfirst(CMP_SITE);?></div>
						<div style="float:right;color:#fff;font-size:14px;">
							<div title="Current Week" class="fr" style="font-size:13px;text-align:right;font-weight:bold"><?php echo date("D, M d",  (strtotime($dateCurnt)-($days_diff*24*60*60)))."&nbsp;-&nbsp;".date("D, M d",strtotime($dateCurnt));?> </div>
							<div class="cb"></div>
							<div title="Last Week" class="fr" style="font-size:9px;text-align:right;padding-bottom:0px;padding-top: 5px;color: #E0E0E0;">Last Week:&nbsp;&nbsp;<?php echo date("D, M d",  (strtotime($last_week_date)))."&nbsp;-&nbsp;".date("D, M d",strtotime($prv_date)-(24*60*60));?> </div>  
						</div>
						<div style="clear:both"></div>	
					</div>
					<div style="padding:10px">
					<?php 
						if($userlogin[0][0]['notlogged']==$userlogin[0][0]['tot']){
							$logedin_color = '#ED7C16';$loggedin_per=0;
						}else{
							$loggedin_users = $userlogin[0][0]['tot']-$userlogin[0][0]['notlogged'];
							$loggedin_per =round(($loggedin_users/$userlogin[0][0]['tot'])*100);
							if($userlogin[0][0]['notlogged']<= ($userlogin[0][0]['tot']/2)){
								$logedin_color = '#5191BD';
							}else{
								$logedin_color = '#ED7C16';
							}
						}?>
						<div style="padding:5px;background:#ECECEC;font-size:15px;font-weight:bold;text-align:left;">Statistics - <span style="font-size:12px;;color:#676767">SO FAR THIS WEEK</span></div><br/>
							<div>
								<div style="float:left">
									<div class="week_rpt_div" style="border-top:1px solid <?php echo $logedin_color;?>">
										<div style="font-size:28px;color:<?php echo $logedin_color;?>;font-weight:bold"><?php echo ($userlogin[0][0]["tot"]-$userlogin[0][0]['notlogged']);?></div><div style="clear:both"></div>
										<div style="color:#666666;margin-top:5px;font-weight:bold">Logged in User</div>
										<div style="clear:both"></div>
								<?php if($progress_flag){?>
										<div style="width:120px;background:<?php echo $logedin_color;?>;padding:4px;color:#FFFFFF;font-size:11px;margin:0 auto;-moz-border-radius: 2px;border-radius: 2px;margin-top:10px;">
										<?php echo $loggedin_per.'% of Total '.$userlogin[0][0]["tot"];?>
										</div>
										<div style="color:#666666;margin-top:3px;">Last Week to Date</div>
								<?php	}?>
									</div>
								</div>
								<div style="float:left">
									<div id="tcreate_main" class="week_rpt_div" style="border-top:1px solid #696969;border-left:4px solid #FFF">
										<div id="tcreate_text" style="font-size:28px;color:#696969;font-weight:bold"><?php echo $total_task_cr_current_week; ?></div>
										<div style="clear:both"></div>
										<div style="color:#666666;margin-top:5px;font-weight:bold">
											Tasks Created
										</div>
										<div style="clear:both"></div>
										<div id="task_created" >
											<span style="font-size: 11px;">Loading...</span>
										</div>
									</div>
								</div>
								<div style="float:left">
									<div id="tupdate_main" class="week_rpt_div" style="border-top:1px solid #696969;border-left:4px solid #FFF">
										<div id="tupdate_text" style="font-size:28px;color:#696969;font-weight:bold"><?php echo $total_task_upd_current_week;?></div>
										<div style="clear:both"></div>
										<div style="color:#666666;margin-top:5px;font-weight:bold">Tasks Updated</div>
										<div style="clear:both"></div>
									<?php if($progress_flag){?>
										<div id="task_update">	
											<span style="font-size: 11px;">Loading...</span>
										</div>
									<?php }?>
									</div>
								</div>
							<div style="clear:both"></div>
							</div>
							<div style="clear:both"></div><br/>
							<div>
								<div style="float:left">
									<div id="tclosed_main" class="week_rpt_div" style="border-top:1px solid #696969;">
										<div id="tclosed_text" style="font-size:28px;color:#696969;font-weight:bold"><?php echo $curr_wk_tot_closed_tasks;?></div>
										<div style="clear:both"></div>
										<div style="color:#666666;margin-top:5px;font-weight:bold">Tasks Closed</div>
										<div style="clear:both"></div>
								<?php if($progress_flag){?>
										<div id="task_closed"><span style="font-size: 11px;">Loading...</span></div>
								<?php }?>
									</div>
								</div>
									<div style="float:left">
										<div id="hours_main" class="week_rpt_div" style="border-top:1px solid #696969;border-left:4px solid #FFF">
											<div id="hours_text" style="font-size:28px;color:#696969;font-weight:bold"><?php echo $curr_wk_tot_hr_spent;?></div>
												<div style="clear:both"></div>
												<div style="color:#666666;margin-top:5px;font-weight:bold">Hours Spent</div>
												<div style="clear:both"></div>
											<?php if($progress_flag){?>	
												<div id="task_hours_spent" > <span style="font-size: 11px;">Loading...</span></div>
										<?php }?>
										</div>
									</div>
									<div style="float:left">
										<div id="storage_main" class="week_rpt_div" style="border-top:1px solid #696969;border-left:4px solid #FFF">
											<div style="font-size:28px;color:#696969;font-weight:bold" id="storage_text"><?php echo $curr_wk_tot_storage_usage;?> <span style="font-size:18px;">Mb</span></div>
											<div style="clear:both"></div>
											<div style="color:#666666;margin-top:5px;font-weight:bold">Storage Used</div>
											<div style="clear:both"></div>
									<?php if($progress_flag){	?>
											<div id="task_storage_used">
												<span style="font-size: 11px;">Loading...</span>
											</div>
										<?php } ?>
										</div>
									</div>
									<div style="clear:both"></div>
								</div><br/><br/>
								<div style="clear:both"></div><br/>
								<div style="padding:10px;background:#F3F3F3;font-size:15px;font-weight:bold;text-align:left;">Task Status of the Week</div><br/> <div style="clear:both"></div>
								<div style="border:1px solid #EEEEEE;background:#F8F8F8;box-shadow:0px 0px 1px #fff inset">
									<div class="weekly_task_col" style="text-align:center;">Date</div>
									<div class="weekly_task_col" style="text-align:right;">Task Created</div>
									<div class="weekly_task_col" style="text-align:right;">Task Updated</div>
									<div style="clear:both"></div>
									<?php 	
									foreach ($last7days as $key1=>$val1){
										$no_of_tasks=0;
										$no_of_tasks_upd=0;$total_hr_spent=0;
										foreach($caseAll AS $k=>$value){
											if(strtotime($value[0]['created_date'])==strtotime($val1)){
												if($value['Easycase']['istype']==1){
													$no_of_tasks = $value[0]['cnt'];
												}else{
													$no_of_tasks_upd = $value[0]['cnt'];;
												}
												$total_hr_spent = $value[0]['cnt']['hrs'];
											}
										}
									?>
									<div class="weekly_task_col" style="text-align:center;font-weight:normal">
										<?php echo date("D, M d",  strtotime($val1)); ?>
									</div>
									<div class="weekly_task_col" style="text-align:right;font-weight:normal">
										<?php echo $no_of_tasks;?>
									</div>
									<div class="weekly_task_col" style="text-align:right;font-weight:normal">
										<?php echo $no_of_tasks_upd;?>
									</div>
									<div style="clear:both"></div>
									<?php } ?>
								</div>	
							<?php	
							$curr_wk_tot_closed_tasks = 0 ;$curr_wk_tot_storage_usage=0;
							if($getProj){?>
								<div style="clear:both"></div><br/>
								<div style="padding:10px;background:#F3F3F3;font-size:15px;font-weight:bold;text-align:left;">Project Status of the Week</div><br/><div style="clear:both"></div>
								<div style="border:1px solid #EEEEEE;background:#F8F8F8;box-shadow:0px 0px 1px #fff inset">
									<div class="weekly_proj_col" style="text-align:left;">Project</div>
									<div class="weekly_proj_col" style="text-align:right;">Closed/Total Tasks</div>
									<div class="weekly_proj120_col" style="text-align:right;">Hours</div>
									<div class="weekly_proj120_col" style="text-align:right;">Usage</div>
									<div style="clear:both"></div>
								<?php 
								foreach($getProj AS $pkey=>$pval){
									$tot_cases = $pval[0]['totalcase']?$pval[0]['totalcase']:0;
									$tot_hrs = $pval[0]['totalhours']?$pval[0]['totalhours']:'0.0';
									//$tot_close_per = ($pval[0]['totalcase'] && $pval[0]['closedcase'])?(round((($pval[0]['closedcase']/$pval[0]['totalcase'])*100),2)):0;
									$tot_close = $pval[0]['closedcase']?$pval[0]['closedcase']:0;
									$curr_wk_tot_closed_tasks +=$tot_close;
									$tot_users = $pval[0]['totusers']?$pval[0]['totusers']:0;
									if($pval[0]['storage_used']){
										$tot_storage = number_format(($pval[0]['storage_used']/1024),2);
										$curr_wk_tot_storage_usage +=$tot_storage;
										if($tot_storage>=1024){
											$tot_storage = number_format(($tot_storage/1024),2)." Gb";
										}else{
											$tot_storage .=" Mb";
										}
									}else{
										$tot_storage = "0 Mb";
									}

									$tot_cases = $pval[0]['totalcase']?$pval[0]['totalcase']:0;
									?>
									<div class="weekly_proj_col" style="text-align:left;font-weight:normal">
										<?php echo $pval['Project']['name'];?>
									</div>
									<div class="weekly_proj_col" style="text-align:right;font-weight:normal">
										<?php echo '<b>'.$tot_close.'</b>/'.$tot_cases;?>
									</div>
									<div class="weekly_proj120_col" style="text-align:right;font-weight:normal">
										<?php echo $tot_hrs;?>
									</div>
									<div class="weekly_proj120_col" style="text-align:right;font-weight:normal">
										<?php echo $tot_storage;?>
									</div>
									<div style="clear:both"></div>
								<?php } ?>
								</div>
								<?php }else{ ?>
									<div style="clear:both"></div><br/><div style="padding:10px;background:#F3F3F3;font-size:15px;font-weight:bold;text-align:left;">No Project Status on last week</div><br/><div style="clear:both"></div>
								<?php } ?>
									<div style="clear:both"></div><br/><div style="padding:5px;background:#ECECEC;font-size:15px;font-weight:bold;text-align:left;">Summary</div>
									<div style="text-align:left;">
										<ul>
											<li>
												<b><?php echo $userlogin[0][0]['notlogged'];?></b> Out of <b><?php echo $userlogin[0][0]['tot']; ?></b> User has not logged in to the system since last week.
											</li>
											<li>
												<b><?php echo $total_task_cr_current_week; ?></b> tasks created and <b><?php echo $total_task_upd_current_week;?></b> updated on last week
											</li>
											<li>
												<b><?php echo $curr_wk_tot_closed_tasks.'</b> closed out of <b>'.$total_task_cr_current_week.'</b> tasks, <b>'.$curr_wk_tot_hr_spent.'</b> hours spent and <b>'.$curr_wk_tot_storage_usage;?></b> Mb storage used on all projects
											</li>
										</ul>
									</div><br/>
								</div>
							</div>
						</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<script type="text/javascript">
function PrintDiv() {    
	var divToPrint = document.getElementById('divToPrint');
	var popupWin = window.open('', '_blank', 'width=800,height=600');
	popupWin.document.open();
	popupWin.document.write('<html><title>Orangescrum Weekly Usage Report <?php echo date("D, M d",  (strtotime($dateCurnt)-($days_diff*24*60*60)))."&nbsp;-&nbsp;".date("D, M d",strtotime($dateCurnt));?> </title><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
	popupWin.document.close();
}
$(document).ready(function(){
	var project_idlists = '';
	var total_task_cr_current_week = <?php echo $total_task_cr_current_week;?>;
	var total_task_upd_current_week = <?php echo $total_task_upd_current_week;?>;
	var curr_wk_tot_closed_tasks = <?php echo $curr_wk_tot_closed_tasks;?>;
	var curr_wk_tot_hr_spent = <?php echo $curr_wk_tot_hr_spent;?>;
	var curr_wk_tot_storage_usage = <?php echo $curr_wk_tot_storage_usage;?>;
	<?php if($project_idlist){?>
		project_idlists = '<?php echo $project_idlist; ?>';
	<?php }?>
	$.post(HTTP_ROOT+'reports/ajax_statistics',{'project_idlists':project_idlists},function(res){
		// Hours spent calculation 
		var prv_wk_tot_hr_spent= res.prv_wk_tot_hr_spent;
		if(curr_wk_tot_hr_spent || prv_wk_tot_hr_spent ){
			if(prv_wk_tot_hr_spent>0){
				hstaskper = (((curr_wk_tot_hr_spent- prv_wk_tot_hr_spent)/prv_wk_tot_hr_spent)*100).toFixed(0);
				if(hstaskper>0){hstask_color ='#5191BD';hstask_text='Up';}else{hstask_color = '#ED7C16';hstask_text='Down';}
			}else{
				hstask_color ='#5191BD';hstask_text = 'Up';
				hstaskper = curr_wk_tot_hr_spent*100;
			}
		}else{
			hstask_text='';	hstask_color = '#696969';hstaskper =0;
		}
		//Hours spent data 
		var hspent = '<div style="width:120px;background:'+hstask_color+';padding:4px;color:#FFFFFF;font-size:11px;margin:0 auto;-moz-border-radius: 2px;border-radius: 2px;margin-top:10px;">'+hstask_text+' '+Math.abs(hstaskper)+'% from '+prv_wk_tot_hr_spent+'</div><div style="color:#666666;margin-top:3px;">Last Week to Date</div>';
		$('#task_hours_spent').show();
		$('#task_hours_spent').html(hspent);
		$('#hours_main').css({'border-top':'1px solid'+hstask_color});
		$('#hours_text').css({'color':hstask_color});
		// Storage Calculation
		var prev_wk_storage_usage=res.prev_wk_storage_usage;
		if(curr_wk_tot_storage_usage || prev_wk_storage_usage ){
			if(prev_wk_storage_usage>0){
				storageper = (((curr_wk_tot_storage_usage - prev_wk_storage_usage)/prev_wk_storage_usage)*100).toFixed(0);
				if(storageper>0){storage_color ='#5191BD';storage_text = 'Up';}else{storage_color = '#ED7C16';storage_text ='Down';}
			}else{
				storage_color ='#5191BD';storage_text= 'Up';storageper = curr_wk_tot_storage_usage;
			}
		}else{
			storage_text = '';	storage_color = '#696969';	storageper =0;
		}
		// Storage Data 
		var storageusage ='<div style="width:130px;background:'+storage_color+';padding:4px;color:#FFFFFF;font-size:11px;margin:0 auto;-moz-border-radius: 2px;border-radius: 2px;margin-top:10px;">'+storage_text+' '+Math.abs(storageper)+'% from '+prev_wk_storage_usage+' Mb</div><div style="color:#666666;margin-top:3px;">Last Week to Date</div>';
		$('#task_storage_used').show();
		$('#task_storage_used').html(storageusage);
		$('#storage_main').css({'border-top':'1px solid'+storage_color});
		$('#storage_text').css({'color':storage_color});
		//Task Closed
		var prev_wk_closed_tasks =res.prev_wk_closed_tasks;
		if(curr_wk_tot_closed_tasks || prev_wk_closed_tasks ){
			if(prev_wk_closed_tasks>0){
				ctaskper = (((curr_wk_tot_closed_tasks - prev_wk_closed_tasks)/prev_wk_closed_tasks)*100).toFixed(0);
				if(ctaskper>0){ctask_color ='#5191BD';ctask_text='Up';}else{ctask_color = '#ED7C16';ctask_text='Down';}
			}else{
				ctask_color ='#5191BD';ctask_text='Up';ctaskper = curr_wk_tot_closed_tasks*100;
			}
		}else{
			ctask_text='';ctask_color = '#696969';ctaskper =0;
		}
		var closedtasks ='<div style="width:120px;background:'+ctask_color+';padding:4px;color:#FFFFFF;font-size:11px;margin:0 auto;-moz-border-radius: 2px;border-radius: 2px;margin-top:10px;">'+ctask_text+' '+Math.abs(ctaskper)+'% from '+prev_wk_closed_tasks+'</div><div style="color:#666666;margin-top:3px;">Last Week to Date</div>';
		$('#task_closed').show();
		$('#task_closed').html(closedtasks);
		$('#tclosed_main').css({'border-top':'1px solid'+ctask_color});
		$('#tclosed_text').css({'color':ctask_color});
		//Task Updated 
		var total_task_upd_prv_week = res.total_task_upd_prv_week;
		if(total_task_upd_current_week || total_task_upd_prv_week ){
			if(total_task_upd_prv_week>0){
				taskupdper = (((total_task_upd_current_week-total_task_upd_prv_week)/total_task_upd_prv_week)*100).toFixed(0);
				if(taskupdper>0){task_upd_color ='#5191BD';task_upd_text='Up';}else{task_upd_color = '#ED7C16';task_upd_text='Down';}
			}else{
				task_upd_color ='#5191BD';task_upd_text='Up';taskupdper = total_task_upd_current_week*100;
			}
		}else{
			task_upd_text='';task_upd_color = '#696969';taskupdper =0;
		}
		var taskupdated ='<div style="width:120px;background:'+task_upd_color+';padding:4px;color:#FFFFFF;font-size:11px;margin:0 auto;-moz-border-radius: 2px;border-radius: 2px;margin-top:10px;">'+task_upd_text+' '+Math.abs(taskupdper)+'% from '+total_task_upd_prv_week+'</div><div style="color:#666666;margin-top:3px;">Last Week to Date</div>';
		$('#task_update').show();
		$('#task_update').html(taskupdated);
		$('#tupdate_main').css({'border-top':'1px solid'+task_upd_color});
		$('#tupdate_text').css({'color':task_upd_color});
//	Task Created 
		var total_task_cr_prv_week= res.total_task_cr_prv_week;
		if(total_task_cr_current_week || total_task_cr_prv_week ){
			if(total_task_cr_prv_week>0){
				taskper = (((total_task_cr_current_week-total_task_cr_prv_week)/total_task_cr_prv_week)*100).toFixed(0);
				if(taskper>0){task_color ='#5191BD';task_text="Up";}else{task_color = '#ED7C16';task_text="Down";}
			}else{
				task_text="Up";task_color ='#5191BD';taskper = total_task_cr_current_week*100;
			}
		}else{
			task_text='';task_color = '#696969';taskper =0;
		}
		var taskcreated ='<div style="width:120px;background:'+task_color+';padding:4px;color:#FFFFFF;font-size:11px;margin:0 auto;-moz-border-radius: 2px;border-radius: 2px;margin-top:10px;">'+task_text+' '+Math.abs(taskper)+'%  from '+total_task_cr_prv_week+'</div><div style="color:#666666;margin-top:3px;">	Last Week to Date</div>';
		$('#task_created').show();
		$('#task_created').html(taskcreated);
		$('#tcreate_main').css({'border-top':'1px solid'+task_color});
		$('#tcreate_text').css({'color':task_color});
	},'json');
});
</script>
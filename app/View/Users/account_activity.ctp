<?php if(!isset($ajaxlayout)){?>
<div class="account_settings_activity payment-tab" >
<!--Tabs section starts -->
    <?php echo $this->element("account_settings");?>
<div class="col-lg-12 payment-activity-page m-left-20" >
	<div class="fl actvity-hd"><h2>Account Activities</h2></div>
	<div class="fr activity-filter"><?php echo $this->Form->input('activity_type_id', array('id'=>'activity_type_id','class'=>'form-control','style'=>"width:auto;", 'value'=>$filter, 'options' => $logtype, 'label' => false, 'empty'=>All,'onchange'=>"get_payment_activity(0);")); ?></div>
	<div class="cb"></div>
	<div class="drwline"></div>
<?php } ?>	
	<div id="activity_data">
		<table width="98%" class="tbl-act-payment">
<!--			<tr style="" class="tab_tr">
				<td align="right"><?php echo $this->Form->input('activity_type_id', array('id'=>'activity_type_id','class'=>'form-control','style'=>"width:auto;", 'value'=>$filter, 'options' => $logtype, 'label' => false, 'empty'=>All,'onchange'=>"get_payment_activity(0);")); ?></td>
			</tr>-->
			<?php $t='';
				if($logactivity){?>
				<tr>
					<td>
						<table id="activity_tbl" width="100%" class="">
							<?php	foreach($logactivity as $key=>$val){
								$cur_dt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,'',$val['logActivity']['created'],'datetime');
								$fb_date = $this->Datetime->facebook_style_date_time($cur_dt,GMT_DATETIME,'time');
							?>
							<tr>
								<td><?php echo $this->Format->activity_message($val['logActivity']['json_value'],$val['logActivity']['log_type_id'],$logtype);?> &nbsp; <i> On  <span  title="<?php echo $fb_date;?>" ><?php echo date('D, M d, Y h:i a',strtotime($cur_dt));?></span></i></td>
							</tr>
							<?php }?>
						</table>
						<table align="right" class="" width="100%">
							<tr>
								<td align="right">
								<input type="hidden" id="getcasecount" value="<?php echo $activityCount; ?>" readonly="true"/>
							<?php if($activityCount){ ?>
							<table cellpadding="0" cellspacing="0" border="0" align="right" width="100%" >
								<tr>
									<td align="right" style="padding-top:5px;">
										<div class="show_total_case">
											<?php echo  $this->Format->pagingShowRecords($activityCount,$page_limit,$page); ?>
										</div>
									</td>
								</tr>
								<tr>
									<td align="right" style="padding-top:5px">
									<ul class="pagination">
									<?php $page;
										if($page_limit < $activityCount){ 
											$numofpages = $activityCount / $page_limit;
											if(($activityCount % $page_limit) != 0){
												$numofpages = $numofpages+1;
											}
											$lastPage = $numofpages;
											$k = 1;
											$data1 = "";
											$data2 = "";
											if($numofpages > 5){
												$newmaxpage = $page+2;
												if($page >= 3){
													$k = $page-2;
													$data1 = "...";
												}
												if(($numofpages - $newmaxpage) >= 2){
													if($data1){
														$data2 = "...";
														$numofpages = $page+2;
													}else{
														if($numofpages >= 5){
															$data2 = "...";
															$numofpages = 5;
														}
													}
												}
											}
											if($flag){
												$second_arg =",".$comp_id;
											}else{
												$second_arg ='';
											}
											if($data1){
												echo "<li><a href='javascript:void(0);' onclick='get_payment_activity(1".$second_arg.");' class=\"button_act\" >&laquo; First</a></li>";
												echo "<li class='hellip'>&hellip;</li>";
											}
											if($page != 1){
												$pageprev = $page-1;
												echo "<li><a href='javascript:void(0);' onclick='get_payment_activity(".$pageprev.$second_arg.");' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
											}else{
												echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
											}
											for($i = $k; $i <= $numofpages; $i++){
												if($i == $page) {
													echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">".$i."</a></li>";
												}else {
													echo "<li><a href='javascript:void(0);' onclick='get_payment_activity(".$i.$second_arg.");' class=\"button_act\" >".$i."</a></li>";
												}
											}
											if(($activityCount - ($page_limit * $page)) > 0){
												$pagenext = $page+1;
												echo "<li><a href='javascript:void(0);' onclick='get_payment_activity(".$pagenext.$second_arg.");' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
											}else{
												echo "<li><a href='javascript:void(0);' onclick='get_payment_activity(".$pagenext.$second_arg.");' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
											}
											if($data2){
												echo "<li class='hellip'>&hellip;</li>";
												echo "<li><a href='javascript:void(0);' onclick='get_payment_activity(".floor($lastPage).$second_arg.");' class=\"button_act\" >Last &raquo;</a></li>";
											}
										} ?>
									</ul>
								</td>
							</tr>
						</table>
					<?php }	?>		
					</td>
				</tr>	
			<?php }else{ ?>
				<tr>
					<td> No Activity found!</td>
				</tr>
			<?php } ?>	
		</table>
	</table>					
</div>
<?php if(!isset($ajaxlayout)){?>
</div>
</div>
<?php }?>
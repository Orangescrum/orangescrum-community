
	<?php if($totalRecentCase){  ?>

				<?php
				foreach($caseArr as $casAr)
				{
				$casearr = $this->Casequery->getCaseNotification($casAr['CaseRecent']['easycase_id']);
				$projarr = $this->Casequery->getProjectShortName($casAr['CaseRecent']['project_id']);
				?>
					 
				<li title="<?php echo strtoupper($projarr['Project']['short_name']).' - '.$casearr['Easycase']['case_no']; ?>: <?php echo htmlentities($casearr['Easycase']['title']); ?>" <?php if($caseid == $casearr['Easycase']['uniq_id']) { echo "class='recent_current'"; } ?>>
					<a href="javascript:void(0);" onclick="javascript:location.href='<?php echo HTTP_ROOT."dashboard#details/".$casearr['Easycase']['uniq_id']; ?>'">
					<?php echo strtoupper($projarr['Project']['short_name'])." - ".$casearr['Easycase']['case_no']; ?>: <?php echo ucfirst(strtolower($this->Format->shortLength(htmlentities($casearr['Easycase']['title']),6))); ?>
					</a>
				</li>
				<?php
				}
				?>

				
				<?php if($totalRecentCase >3 && ($limit1+3)< $totalRecentCase) { ?>
					<li style="background:none;" class="li_nohover">
						<a href="javascript:void(0);">
							<div class="fr" style="width:64px;">
								<?php if($totalRecentCase >3 && $limit1 >=3  ) {?>
									<span class="menu_arrow_prev" onclick="previousRecentCase(<?php echo $limit1; ?>,'less');" title="Previous"> << </span>
								<?php } if($totalRecentCase >3 && ($limit1+3)< $totalRecentCase){ ?>
									<span class="menu_arrow_next" onclick="showmoreRecentCase(<?php echo $limit1; ?>,'more');" title="Next"> >> </span>
								<?php } ?>	
							</div>
							<div class="cb"></div>
						</a>
					</li>
				<?php } ?>	

         
    <?php 
	}else{
    ?>
		<li style="color:#FFFFFF;text-align:center;font-size:14px;">No recently viewed</li>
    <?php } ?>

<!--<input type="text" id="displayed" value="5">-->

<style>
    /*.recent_current{
	text-decoration: underline;
    }*/
    .recent_current >a{
	/*color: #AE432E !important;*/
	text-decoration: underline !important;
    }
    
</style>  
    
<script type="text/javascript">
$("#less_div a").click(function(){
	$("#recent_view").show();
	$("#recent_more").hide();
	$("#more_div").html('&nbsp;<a href="javascript:void(0);" style="text-decoration:none; font-size:11px"> &gt;&gt;</a>');
	$(this).addClass("gray");
	$("#more_div a").removeClass("gray");
	//$("#less_div a").html('');
});
$("#more_div").click(function(){
	$("#recent_view").hide();
	$("#recent_more").show();
	//$("#more_div").html('5-10 of 10');
	$("#less_div a").html('&lt;&lt;');
	$("#less_div a").removeClass("gray");
	$(this).find("a").addClass("gray");
});
</script>


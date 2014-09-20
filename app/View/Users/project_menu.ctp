<?php if(isset($allProjArr) && count($allProjArr)){ ?>
    <?php if($page != 'reports' && $page!='import' && $page != 'chart' && $page != 'hours_report' && $page != 'glide_chart') {?>
	
	    <a href="javascript:jsVoid();" class="proj_lnks" onClick="<?php if($page=='activity'){ ?>CaseActivity('all','All'); <?php } elseif($page=='mydashboard') {?>CaseDashboard('all','All'); <?php }elseif($page=='milestone') { ?>caseMilestone('all','All',1); <?php } else {?>updateAllProj('0','0','<?php echo $page; ?>','all','All') <?php }?>;">All (<?php echo $allPjCount['0']['0']['count']; ?>)</a>
		<hr class="pro_div"/>

<?php } ?>
	<?php
	$i = 0;
	$colrs = "";
	foreach($allProjArr as $proj){
	$i++;
	$colrs = "";
	?>
		<?php if($page == 'chart' || $page == 'hours_report' || $page == 'glide_chart') { ?>
			<?php if($page == 'chart'){?>
				<a href="javascript:jsVoid();" class="proj_lnks ttc" onclick="ReportMenu('<?php echo $proj['p']['uniq_id'];?>');"><?php echo $this->Format->shortLength($proj['p']['name'],30); ?></a>
			 <?php }else if($page == 'hours_report'){?>
				 <a href="javascript:jsVoid();" class="proj_lnks ttc" onclick="hoursreport('<?php echo $proj['p']['uniq_id'];?>');"><?php echo $this->Format->shortLength($proj['p']['name'],30); ?></a>
			 <?php }else if($page == 'glide_chart'){ ?>
				 <a href="javascript:jsVoid();" class="proj_lnks ttc" onclick="ReportGlideMenu('<?php echo $proj['p']['uniq_id'];?>');"><?php echo $this->Format->shortLength($proj['p']['name'],30); ?></a>
			 <?php } ?>
		
	
		<?php } else { ?>
		    <a href="javascript:jsVoid();" class="proj_lnks ttc" onClick="<?php if($page=='activity'){ ?>CaseActivity('<?php echo $proj['p']['id']; ?>','<?php echo  rawurlencode($proj['p']['name']);?>'); <?php }elseif($page=='mydashboard'){?>CaseDashboard('<?php echo $proj['p']['uniq_id']; ?>','<?php echo  rawurlencode($proj['p']['name']);?>'); <?php }elseif($page=='milestone') { ?>caseMilestone('<?php echo $proj['p']['id']; ?>','<?php echo  rawurlencode($proj['p']['name']);?>',1); <?php } else {?>updateAllProj('proj_<?php echo $proj['p']['uniq_id']; ?>','<?php echo $proj['p']['uniq_id']; ?>','<?php echo $page; ?>','0','<?php echo  rawurlencode($proj['p']['name']);?>') <?php }?>;"><?php echo $this->Format->shortLength($proj['p']['name'],30); ?> (<?php echo $proj['0']['count']; ?>)</a>
		<?php } 
		if($i != count($allProjArr))
		{
		?>
		    <hr class="pro_div"/>
		<?php
		}
	}
	if($limit != "all" && $countAll > 6){?>
		<hr class="pro_div"/>
		<div id="showMenu_case_txt">
		    <a href="javascript:jsVoid();" class="proj_lnks more" onClick="displayMenuProjects('<?php echo $page; ?>','all');">more...</a>
		</div>
		<span id="loaderMenu_case" style="display:none;">
			<a href="javascript:jsVoid();" style="text-decoration:none;color:#000000;padding:4px;cursor:wait">Loading...&nbsp;&nbsp;<img src="<?php echo HTTP_IMAGES;?>images/del.gif" width="16" height="16" alt="loading..." title="loading..." border="0"/></a>
		</span>
	<?php
	} ?>
     <!-- Add project option start -->
     <?php if(SES_TYPE == 1 || SES_TYPE == 2){ ?>
     <hr class="pro_div"/>
	<div id="newprj_but">
	    <a id="newproject" class="proj_lnks col333" href="javascript:jsVoid();" onclick="newProject('newproject','loaderprj');">+ Create Project</a>
	</div>
	<a href="javascript:jsVoid()" id="loaderprj" style="text-decoration:none;cursor:wait;display:none;">
	    Loading...<img src="<?php echo HTTP_IMAGES;?>images/del.gif" width="16" height="16" alt="loading..." title="loading..."/>
	</a>
     <?php } ?>
     <!-- Add project option end -->
<?php }
?>

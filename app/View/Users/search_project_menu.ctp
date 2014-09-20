<?php //echo '<pre>';print_r($allProjArr);
if($fres==1 && $page == 'dashboard'){ 
if(isset($allProjArr) && count($allProjArr)){
	$i = 0;
	$colrs = "";
	foreach($allProjArr as $proj){
	$i++;
	$colrs = "";
	?>
	<a href="javascript:jsVoid(0);" class="proj_lnks" onClick="updateAllProj('proj_<?php echo $proj['Project']['uniq_id']; ?>','<?php echo $proj['Project']['uniq_id']; ?>','<?php echo $page; ?>','0','<?php echo  rawurlencode($proj['Project']['name']);?>');">
			<?php echo $this->Format->shortLength($proj['Project']['name'],30); ?> (<?php echo $proj[0]['count'];//echo $this->Casequery->displayCaseNo($proj['Project']['id'],'project',0,0,0); ?>) </a>
		<?php if($i != count($allProjArr)){ ?>
			<hr class="pro_div"/>
		<?php
	}
	}
	
}
}else if($fres==1 && $page == 'archive'){
if(isset($allProjArr) && count($allProjArr)){
	$i = 0;	$colrs = "";
	foreach($allProjArr as $proj){$i++;	$colrs = "";?>
		<a href="javascript:jsVoid(0);" class="proj_lnks" onclick="chgcase('<?php echo $proj['Project']['id']; ?>','<?php echo  rawurlencode($proj['Project']['name']);?>')">
         		<?php echo $this->Format->shortLength($proj['Project']['name'],30); ?> (<?php echo $this->Casequery->getarccasecount($proj['Project']['id']); ?>)</a>
		<?php
		if($i != count($allProjArr)){?>
			<hr class="pro_div"/>
	<?php }
	}
	
}
	}else if($fres==1 && $page == 'activity'){
     if(isset($allProjArr) && count($allProjArr)){ $i = 0; $colrs = "";
	     foreach($allProjArr as $proj){   $i++;     $colrs = "";         ?>
		              <a href="javascript:jsVoid(0);" class="proj_lnks" onclick="CaseActivity('<?php echo $proj['Project']['id']; ?>','<?php echo  rawurlencode($proj['Project']['name']);?>')">
                   		<?php echo $this->Format->shortLength($proj['Project']['name'],30); ?> (<?php echo $this->Casequery->getactivitycount($proj['Project']['id']); ?>)</a>
		          <?php
		          if($i != count($allProjArr)){?>
		          <hr class="pro_div"/>
		          <?php  }
	     }
     }
}else if($fres==1 && $page == 'milestone'){
     if(isset($allProjArr) && count($allProjArr)){ $i = 0; $colrs = "";
	     foreach($allProjArr as $proj){   $i++;     $colrs = "";         ?>
		              <a href="javascript:jsVoid(0);" class="proj_lnks" onclick="caseMilestone('<?php echo $proj['Project']['id']; ?>','<?php echo  rawurlencode($proj['Project']['name']);?>',1);">
                   		<?php echo $this->Format->shortLength($proj['Project']['name'],30); ?> (<?php echo $this->Casequery->getactivitycount($proj['Project']['id']); ?>)</a>
		          <?php
		          if($i != count($allProjArr)){?>
		          <hr class="pro_div"/>
		          <?php  }
	     }
     }
}else if($fres==1 && $page == 'file'){

if(isset($allProjArr) && count($allProjArr)){
	$i = 0;	$colrs = "";
	foreach($allProjArr as $proj){	$i++;	$colrs = "";	?>
		<a href="javascript:jsVoid(0);" class="proj_lnks" onclick="chgfile('<?php echo $proj['Project']['id']; ?>','<?php echo  rawurlencode($proj['Project']['name']);?>')">
			<?php echo $this->Format->shortLength($proj['Project']['name'],30); ?> (<?php echo $this->Casequery->getarcfilecount($proj['Project']['id']); ?>)</a>
		<?php
		if($i != count($allProjArr)){	?>
		<hr class="pro_div"/>
		<?php }
	}
}
}else if($fres==1 && $page == 'milestonearchive'){
if(isset($allProjArr) && count($allProjArr)){
	$i = 0;	$colrs = "";
	foreach($allProjArr as $proj){
	$i++;	$colrs = "";	?>
		<a href="javascript:jsVoid(0);" class="proj_lnks" onclick="chgfile('<?php echo $proj['Project']['id']; ?>','<?php echo  rawurlencode(ucfirst($proj['Project']['name']));?>')">
			<?php echo $this->Format->shortLength($proj['Project']['name'],30); ?> (<?php echo $this->Casequery->getarcmilestonecount($proj['Project']['id']); ?>)</a>
		<?php
		if($i != count($allProjArr)){	?>
		<hr class="pro_div"/>
		<?php	}
	}
	
}
}else if($fres==1 && $page == 'reports'){
if(isset($allProjArr) && count($allProjArr)){
	$i = 0;	$colrs = "";
	foreach($allProjArr as $proj){
	$i++;
	$colrs = "";
	?> 
	
		<?php if($pgname == 'chart'){?>
					<a href="javascript:jsVoid();" class="proj_lnks" onclick="ReportMenu('<?php echo $proj['Project']['uniq_id'];?>');"><?php echo $this->Format->shortLength($proj['Project']['name'],30); ?></a>
			 <?php }else if($pgname == 'hours_report'){?>
				 <a href="javascript:jsVoid();" class="proj_lnks" onclick="hoursreport('<?php echo $proj['Project']['uniq_id']; ?>');"><?php echo $this->Format->shortLength($proj['Project']['name'],30); ?></a>
			 <?php }else{ ?>
				 <a href="javascript:jsVoid();" class="proj_lnks" onclick="ReportGlideMenu('<?php echo $proj['Project']['uniq_id']; ?>');"><?php echo $this->Format->shortLength($proj['Project']['name'],30); ?></a>
			 <?php } ?>
		<?php if($i != count($allProjArr)){ ?>
			<hr class="pro_div"/>
	<?php } ?>
<?php } } 
}else if(($fres==1) && $page=='import'){
if(isset($allProjArr) && count($allProjArr)){
	$i = 0;
	$colrs = "";
	foreach($allProjArr as $proj){
		$i++;$colrs = "";
	?>  
		<a class="proj_lnks" href="<?php echo HTTP_ROOT.'projects/importexport/'.$proj['Project']['uniq_id'];?>" ><?php echo $this->Format->shortLength($proj['Project']['name'],17); ?></a>	
        
	<?php if($i != count($allProjArr)){	?>
		<hr class="pro_div"/>
	<?php } ?>
<?php
}
}
}else if($fres==1){
if(isset($allProjArr) && count($allProjArr)){
	$i = 0;
	$colrs = "";
	foreach($allProjArr as $proj){
		$i++;$colrs = "";
	?>  
     <a href="javascript:void(0);" class="proj_lnks" onclick="<?php if($page=="mydashboard"){ ?>CaseDashboard('<?php echo $proj['Project']['uniq_id']; ?>','<?php echo rawurlencode($proj['Project']['name']); ?>')<?php } else {?>showProjectName('<?php echo rawurlencode($proj['Project']['name']); ?>','<?php echo $proj['Project']['uniq_id']; ?>')<?php }?>"><?php echo $this->Format->shortLength($proj['Project']['name'],27); ?></a>
	<?php if($i != count($allProjArr)){	?>
		<hr class="pro_div"/>
	<?php } ?>
<?php
}
}
}else{
echo "<div style='padding-left:5px;padding-top:5px;'>no projects matched</div>";}

?>
<input type="hidden" value="<?php echo $val; ?>">
<input type="hidden" value="<?php echo $query; ?>">
<?php if($totalfilter){
               //$totalfilter = count($getfilter);          
     ?>
<div id="recent_view1">
	
        <!--<li style="padding-top:0px; padding-bottom:0px;"><a href="#" title="" class="recent show_recent" style="margin-top:0px; font-size:15px; min-height:24px">Recently Viewed</a></li>-->
     
		<li style="padding-top:0px; padding-bottom:0px;" id="more_recent" class="show_recent_li">
			<div>
				<ul style="position:relative; left:0px; background:none; box-shadow:none; margin-top:-2px; border:none;padding-left:5px;">
					<?php
					foreach($getfilter as $getfil)
					{
					
					?>
					<li ><a href="javascript:jsVoid();" title="" class="cs cf" onclick="filterValue('<?php echo $getfil['CustomFilter']['id']; ?>','<?php echo $getfil['CustomFilter']['project_uniq_id']; ?>','<?php echo $getfil['CustomFilter']['filter_date']; ?>','<?php echo $getfil['CustomFilter']['filter_type_id']; ?>','<?php echo $getfil['CustomFilter']['filter_status']; ?>','<?php echo $getfil['CustomFilter']['filter_member_id']; ?>','<?php echo $getfil['CustomFilter']['filter_priority']; ?>','<?php echo $this->Casequery->getProjectNameByUniqid($getfil['CustomFilter']['project_uniq_id']); ?>')">
						<?php echo $getfil['CustomFilter']['filter_name'];?></a>
					</li>
					<?php
					}
					?>
			 	</ul>
			</div>
			<div class="fr">
               <?php if($totalfilter >3 && ($limit1+3)< $totalfilter) { ?>
                    <div align="right" style="margin-right:10px; font-size:10px;" id="more_div1" class="fr">
	                    <a href="javascript:void(0);" style="text-decoration:none; font-size:11px" title="Next" onclick="showmoreCustomFilter(<?php echo $limit1; ?>,'more');">&nbsp;&nbsp;>></a>
                    </div>
               <?php } ?>
               <?php if($totalfilter >3 && $limit1 >=3  ) {?>
                    <div align="right" style="margin-right:10px; font-size:10px;" id="more_div1" class="fl">
	                    <a href="javascript:void(0);" style="text-decoration:none; font-size:11px" title="Previous" onclick="previousCustomFilter(<?php echo $limit1; ?>,'less');">&nbsp;&nbsp;<< </a>
                    </div>
               <?php } ?>
			</div>
			<div class="cb"></div>   
		</li>	
         
    
</div>
<?php 
	}else{
    ?>
<div style="margin-bottom: 5px;margin-left: 50px;margin-top: -5px;"><b>Custom filter not available.</b>
<?php } ?>
</div>

<style type="text/css">
	#milestonelist .kanban-main .kanban-child{width:353px}
	#milestonelist .kbtask_div{width:95%}
</style>
<div id="milestoneList">
<div id="moreloader">
    <div class="loadingdata">Loading...</div>
</div>
<div class="user_profile_con">
<!--Tabs section starts -->
    <?php
	$active_url = HTTP_ROOT.'milestone';
	$cmplt_url = $active_url.'/completed';
    ?>
    <div class="tab tab_comon">
        <ul class="nav-tabs mod_wide">
	    <li <?php if($type == '') { ?> class="active" <?php }?>>
                <a href="<?php echo $active_url; ?>">
                <div class="fl act_milestone"></div>
                <div class="fl">Active</div>
                <div class="cbt"></div>
                </a>
            </li>
            <li <?php if($type == 'completed') { ?> class="active" <?php }?>>
                <a href="<?php echo $cmplt_url; ?>" >
                <div class="fl mt_completed"></div>
                <div class="fl">Completed</div>
                <div class="cbt"></div>
                </a>
            </li>
            <div class="cbt"></div>
        </ul>
    </div>
<!--Tabs section ends -->
</div>
	
<div id="mlstnlistingDv">
    <?php echo $this->element("../Milestone/listing");?>
</div>
<input type="hidden" name="milestone_type" id="mlsttype" value="<?php if($type=='completed'){?>0<?php }else{?>1<?php } ?>" readonly="true" />
</div>
<div id="milestone_content" style="display: none;">
	<div id="milestonelist"></div>
	<div class="milestonenextprev" style="display: none;" >
		<div class="fr">
			<button class="btn gry_btn next" type="button" title="Next">
			<i class="icon-next"></i>
			</button>
		</div>
		<div class="fr">
			<button class="btn gry_btn prev" type="button" title="Previous">
			<i class="icon-prev"></i>
			</button>
		</div>	
	</div>

</div>
<input type="hidden" value="0" id="totalMlstCnt" readonly="true"/>
<input type="hidden" value="0" id="milestoneLimit" readonly="true"/>
<input type="hidden" value="milestonelist" id="caseMenuFilters"  readonly="true"/>
<script type="text/template" id="milestonelist_tmpl">
<?php echo $this->element('ajax_milestonelist'); ?>
</script>
<script type="text/javascript">
$(".proj_mng_div .contain").hover(function(){
    $(this).find(".proj_mng").stop(true,true).animate({bottom:"0px",opacity:1},400);
},function(){
    $(this).find(".proj_mng").stop(true,true).animate({bottom:"-42px",opacity:0},400);
});
$(document).on('click','.milestonenextprev .prev',function(){
	//$('#milestoneLimit').val(parseInt($('#milestoneLimit').val())-6);
	showMilestoneList('prev');
});
$(document).on('click','.milestonenextprev .next',function(){
	showMilestoneList('next');
});
</script>
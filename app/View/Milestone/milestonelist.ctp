<style type="text/css">
	.kanban-main .kanban-child{width:353px}
	.kbtask_div{width:95%}
</style>
<div id="milestonelist"></div>
<div id="caseLoader">
	<div class="loadingdata">Loading...</div>
</div>
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
<input type="hidden" value="0" id="totalMlstCnt" readonly="true"/>
<input type="hidden" value="0" id="milestoneLimit" readonly="true"/>
<script type="text/template" id="milestonelist_tmpl">
<?php echo $this->element('ajax_milestonelist'); ?>
</script>
<script type="text/javascript">
	$(function(){
		showMilestoneList();
	});
	$(document).on('click','.milestonenextprev .prev',function(){
		$('#milestoneLimit').val(parseInt($('#milestoneLimit').val())-6);
		showMilestoneList();
	});
	$(document).on('click','.milestonenextprev .next',function(){
		showMilestoneList();
	});
</script>
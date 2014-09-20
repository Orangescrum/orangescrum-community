<div class="proj_grids glide_div" id="main_con_hours">
	<?php echo $this->element('analytics_header'); ?>
	<?php if(empty($pjid)){ ?>
		<div class="col-lg-12 full_con_al no_analytic" style="">Not enough Analytics data!</div>
	<?php }else{ ?>
		<div style="margin:20px 0 10px;">
			<?php echo $this->Form->hidden('pjid',array('size'=>'45','class'=>'datepicker small','style'=>'','maxlength'=>'100', 'id'=>'pjid','readonly'=>'readonly','value'=>$pjid)); ?>
			<?php echo $this->Form->hidden('pj_uniq',array('size'=>'45','style'=>'','id'=>'pj_uniq','maxlength'=>'100','readonly'=>'readonly','value'=>@$proj_uniq)); ?>
			<?php $pjname = $this->Casequery->getProjectName($pjid); ?>
			<?php echo $this->Form->hidden('pjname',array('size'=>'45','class'=>'datepicker small','style'=>'','maxlength'=>'100','id'=>'pjname','readonly'=>'readonly','value'=>isset($pjname['Project']['name'])?$pjname['Project']['name']:'')); ?>
		</div>
		<div class="col-lg-12 full_con_al">
			<div class="col-lg-6 m-con fl">
				<h3>Hours Spent on Task Type - Pie Chart</h3>
				<div id="piechart_container">
					Loading Pie Chart...
				</div>
			</div>
			<div class="col-lg-6 m-con fl">
				<h3>Hours Spent by All</h3>
				<div class="fr hr_display" id="hrspent"></div>
				<div id="grid_container">
					Loading Grid view...
				</div>
			</div>
			<div class="cb"></div>			
		</div>
		<div class="cb"></div>
		<div class="col-lg-12 con-100">			
			<h3>Hours Spent by All</h3>
			<div class="fr hr_display" id="hrspent"></div>
			<div id="linechart_container">
				Loading Line Chart...
			</div>
		</div>
</div>
<div class="cb"></div>
<?php } ?>
<script>
<?php if(!isset($invalid)){ ?>
$(function(){
	var pjid = $('#pjid').val();
	var sdate = $('#start_date').val();
	var edate = $('#end_date').val();
	var url = HTTP_ROOT;
	
	$('#piechart_container').load(url+'reports/hours_piechart',{'pjid':pjid,'sdate':sdate,'edate':edate}, function(res){
		if(res.length > 150){
			$('#piechart_container').parent(".col-lg-6").addClass('m-con');
			$('#piechart_container').parent(".col-lg-6").removeClass('error_box');
		}else{
			$('#piechart_container').parent(".col-lg-6").removeClass('m-con');
			$('#piechart_container').parent(".col-lg-6").addClass('error_box');
		}
	});
	
	$('#linechart_container').load(url+'reports/hours_linechart',{'pjid':pjid,'sdate':sdate,'edate':edate}, function(res){
		if(res.length > 150){
			$('#linechart_container').parent(".col-lg-6").addClass('m-con');
			$('#linechart_container').parent(".col-lg-6").removeClass('error_box');
		}else{
			$('#linechart_container').parent(".col-lg-6").removeClass('m-con');
			$('#linechart_container').parent(".col-lg-6").addClass('error_box');
		}
	});
		
	$('#grid_container').load(url+'reports/hours_gridview',{'pjid':pjid,'sdate':sdate,'edate':edate},function(res){
		if($('#thrs').length > 0){
			$('#hrspent').html("<b>"+$('#thrs').val()+"</b> hours");
		}else{
			$('#hrspent').html("");
		}

		if(res.length > 150){
			$('#grid_container').parent(".col-lg-6").addClass('m-con');
			$('#grid_container').parent(".col-lg-6").removeClass('error_box');
		}else{
			$('#grid_container').parent(".col-lg-6").removeClass('m-con');
			$('#grid_container').parent(".col-lg-6").addClass('error_box');
		}
	});
});
<?php } ?>
</script>
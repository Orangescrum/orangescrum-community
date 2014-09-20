<div class="proj_grids">
	<?php echo $this->element('analytics_header'); ?>
	
	<div style="margin:20px 0 10px;">
		<?php echo $this->Form->hidden('pjid',array('size'=>'45','class'=>'datepicker small','style'=>'','maxlength'=>'100', 'id'=>'pjid','readonly'=>'readonly','value'=>$pjid)); ?>
		<?php echo $this->Form->hidden('pj_uniq',array('size'=>'45','style'=>'','id'=>'pj_uniq','maxlength'=>'100','readonly'=>'readonly','value'=>@$proj_uniq)); ?>
		<?php $pjname = $this->Casequery->getProjectName($pjid); ?>
		<?php echo $this->Form->hidden('pjname',array('size'=>'45','class'=>'datepicker small','style'=>'','maxlength'=>'100','id'=>'pjname','readonly'=>'readonly','value'=>isset($pjname['Project']['name'])?$pjname['Project']['name']:'')); ?>
	</div>
	<div class="col-lg-12 full_con_al no_analytic" style="">
		<?php echo $this->element('weekly_usage'); ?>
	</div>
</div>
<div class="cb"></div>
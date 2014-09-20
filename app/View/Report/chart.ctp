<div class="proj_grids glide_div" id="main_con_task">
	
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
				<h3>Task Type - Pie Chart</h3>
				<div id="piechart_container">
					Loading task type pie chart...
				</div>
			</div>
			<div class="col-lg-6 m-con fl">
				<h3>Task Type Statistics</h3>
				<div id="statistic_container">
					Loading Statistics...
				</div>	
			</div>
			<div class="cb"></div>
			<div class="col-lg-12 con-100">
				<h3>Task Trend Chart</h3>
				<div id="container">
					Loading task trend chart...
				</div>
			</div>
		</div>
</div>
<div class="cb"></div>
<?php } ?>
<script type="text/javascript">

<?php if(!isset($invalid)){ ?>
$(function(){
	var pjid = $('#pjid').val();
	var sdate = $('#start_date').val();
	var edate = $('#end_date').val();
	var url = $('#pageurl').val();
	
	$('#piechart_container').load(url+'reports/tasks_pichart',{'pjid':pjid,'sdate':sdate,'edate':edate}, function(res){
		if(res.length > 150){
			$('#piechart_container').parent(".col-lg-6").addClass('m-con');
			$('#piechart_container').parent(".col-lg-6").removeClass('error_box');
		}else{
			$('#piechart_container').parent(".col-lg-6").removeClass('m-con');
			$('#piechart_container').parent(".col-lg-6").addClass('error_box');
		}
	});
	
	$('#statistic_container').load(url+'reports/tasks_statistics',{'pjid':pjid,'sdate':sdate,'edate':edate}, function(res){
		if(res.length > 150){
			$('#statistic_container').parent(".col-lg-6").addClass('m-con');
			$('#statistic_container').parent(".col-lg-6").removeClass('error_box');
		}else{
			$('#statistic_container').parent(".col-lg-6").removeClass('m-con');
			$('#statistic_container').parent(".col-lg-6").addClass('error_box');
		}
	});
	
	$('#container').load(url+'reports/tasks_trend',{'pjid':pjid,'sdate':sdate,'edate':edate}, function(res){
		if(res.length > 150){
			$('#container').parent(".col-lg-12").addClass('con-100');
			$('#container').parent(".col-lg-12").removeClass('error_box_main');
		}else{
			$('#container').parent(".col-lg-12").removeClass('con-100');
			$('#container').parent(".col-lg-12").addClass('error_box_main');
		}
	});
});
<?php } ?>


$(function(){

Highcharts.theme = {
   colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
   chart: {
	  backgroundColor: '#ffffff',
      borderWidth: 0,
      plotBackgroundColor: 'rgba(255, 255, 255, .9)',
      plotShadow: true,
      plotBorderWidth: 1
   },
   title: {
      style: {
         color: '#000',
         font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
      }
   },
   subtitle: {
      style: {
         color: '#666666',
         font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
      }
   },
   xAxis: {
    //  gridLineWidth: 1,
    //  lineColor: '#000',
    //  tickColor: '#000',
      labels: {
         style: {
            color: '#000',
            font: '11px Trebuchet MS, Verdana, sans-serif'
         }
      },
      title: {
         style: {
            color: '#333',
            fontWeight: 'bold',
            fontSize: '12px',
            fontFamily: 'Trebuchet MS, Verdana, sans-serif'

         }
      }
   },
   yAxis: {
      //minorTickInterval: 'auto',
	lineColor: '#000',
	lineWidth: 1,
	tickWidth: 1,
	tickColor: '#000',
      	labels: {
         style: {
            color: '#000',
            font: '11px Trebuchet MS, Verdana, sans-serif'
         }
      },
      title: {
         style: {
            color: '#333',
            fontWeight: 'bold',
            fontSize: '12px',
            fontFamily: 'Trebuchet MS, Verdana, sans-serif'
         }
      }
   },
   legend: {
      itemStyle: {
         font: '9pt Trebuchet MS, Verdana, sans-serif',
         color: 'black',
		 cursor:'default'

      },
      /*itemHoverStyle: {
         color: '#039'
      },*/
      itemHiddenStyle: {
         color: 'gray'
      }
   },
   labels: {
      style: {
         color: '#99b'
      }
   }
};

// Apply the theme
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);

});


</script>

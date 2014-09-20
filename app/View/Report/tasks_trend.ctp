<div id="tasktrend" style="width:99%">
</div>
<script>
$(function () {
    var chart;
	var dt = <?php echo $dt_arr;?>;
	var ydata = <?php echo $carr;?>;
	var tikinterval = <?php echo $tinterval;?>; 
	var yourLabels = <?php echo $yarr;?>;
	$(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'tasktrend',
                type: 'line',
                spacingBottom: 25
            },
            title: {
                text: '',
                x: -20 //center
            },
            subtitle: {
                x: -20
            },
            xAxis: {
				type:'datetime',
                categories: eval(dt),
				showFirstLabel:true,
                showLastLabel:true,
                tickInterval:tikinterval
            },
            yAxis: {
				min:0,
				allowDecimals:false,
				title: {
                    text: 'Tasks Count'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y +'';
                }
            },
            /*plotOptions: {
                line: {
                    events: {
                        legendItemClick: function () {
                           	return false;
                            }
                        },
                        showInLegend: true
                    }
            },*/
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
			series: eval(ydata),
        });
    });   
});
</script>

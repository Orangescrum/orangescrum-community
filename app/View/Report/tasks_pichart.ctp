<div id="piechart" style="width:99%">
</div>
<script>
Highcharts.theme = {
		chart: {
			backgroundColor: '#ffffff'
		}
	};
	// Apply the theme
	var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
</script>
<script>
var piedata = <?php echo $piearr; ?>;
$(function(){
        $('#piechart').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            tooltip: {
        	    formatter: function() {
                            return '<b>Total # of Tasks: </b>'+this.point.tasks+'<br><b>'+ this.point.name +'</b>: '+ parseFloat((this.point.percentage).toPrecision(3)) +' %';
                    }
        	    //pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
						enabled: true,
						//distance: -60,
						//color: 'white',
						formatter: function() {
							var precsson = 3;
							if(this.point.percentage<1) precsson = 2;
							if(this.point.percentage>=10) precsson = 4;
							return this.point.percentage > 1 ? parseFloat((this.point.percentage).toPrecision(precsson)) +'% '+this.point.name+'</b>' : null;
						}
					},
                    showInLegend: false
                }
            },
            series: [{
                type: 'pie',
                name: '# of Tasks',
                data: eval(piedata)
            }]
        });
    });
</script>

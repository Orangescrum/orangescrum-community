<div id="piechart"></div>
<script>
var piedata = <?php echo $piearr; ?>;
$(function () {
        // Create the chart
        $('#piechart').highcharts({
        	credits: {
			enabled: false
		},
            chart: {
                type: 'pie',
                height: 270
            },
            title: {
                text: ''
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            plotOptions: {
                pie: {
                    shadow: false,
                    center: ['50%', '50%'],
                    showInLegend: true,
                    dataLabels: {
                	distance: -30,
                	color: 'white'
            		}
                }
            },
            tooltip: {
        	    formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ parseFloat(this.y) +' %';
                    }
            },
            series: [{
                name: '# of Tasks Report',
                data: eval(piedata),
                size: '110%',
                innerSize: '50%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 1 ? this.y +'%'  : null;
                    }
                }
            }]
        });
    });
</script>
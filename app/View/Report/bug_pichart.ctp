<div id="piechart" style="width:99%">
</div>
<script>
var piedata = <?php echo $piearr; ?>;
var clrdata = <?php echo $clrarr; ?>;
$(function(){
    	
    	// Radialize the colors
	/*	Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
		    return {
		    	color: ['#AE432E','#000','#FFF'],
		        radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
		        stops: [
		            [0, color],
		            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		        ]
		    };
		});*/
		
	Highcharts.theme = {
		colors: eval(clrdata),
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
		legend: {
			itemStyle: {
				font: '9pt Trebuchet MS, Verdana, sans-serif',
				color: 'black',
				cursor:'default'

			},
			itemHoverStyle: {
				color: '#039'
			},
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
		

		
		// Build the chart
		
			
	
        $('#piechart').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true
            },
            title: {
                text: ''
            },
            tooltip: {
        	   // pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
        	  formatter: function() {
                            return '<b>Total # of Bugs: </b>'+this.point.nos+'<br><b>'+ this.point.name +'</b>: '+ parseFloat((this.point.percentage).toPrecision(3)) +' %';
                    }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
						enabled: true,
						distance: 10,
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
                name: 'Bug Report',
                data: eval(piedata)
            }]
        });
    });
</script>

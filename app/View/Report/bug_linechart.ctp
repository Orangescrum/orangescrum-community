<div id="linechart" style="width:99%"></div>
<script type="text/javascript">
$(function(){
  var casedata = <?php echo $case; ?>;
  var daysdata = <?php echo $closedays; ?>;
  Highcharts.theme = {
		colors: ['#77AB13'],
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
  $('#linechart').highcharts({
            title: {
                text: '',
               // x: -20 //center
            },
            subtitle: {
                text: '',
               // x: -20
            },
            xAxis: {
            	title: {
            		text: 'Bugs'
            	},
                categories: eval(casedata),
                labels:{
		  enabled: true
		}
            },
            yAxis: {
                title: {
                    text: 'Day(s)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: 'day(s)',
                valuePrefix: 'In '
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Closed',
                data: eval(daysdata)
            }]
        });
});
</script>

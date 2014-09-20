<div id="glide" style="width:99%"></div>
<script type="text/javascript">
Highcharts.theme = {
   colors: ['#4572A7','#AA4643'],
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
      gridLineWidth: 1,
      lineColor: '#000',
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
   yAxis: {
      minorTickInterval: 'auto',
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
$(function () {
    var chart;
    var dt = <?php echo $dt_arr;?>;  
	var ydata = <?php echo $carr;?>;
	var op = JSON.stringify( ydata );
	op = op.replace(/"/g,"'");
    op = op.replace(/'\[/g,"[");
    op = op.replace(/]'/g,"]");
	
	var tikinterval = <?php echo $tinterval;?>; 
	var yourLabels = <?php echo $yarr;?>;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'glide',
                type: 'area'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: eval(dt),
                showFirstLabel:true,
                showLastLabel:true,
		labels:{
		  enabled: true
		}
               //tickInterval:tikinterval           
            },
            yAxis: {
                title: {
                    text: 'Bug Count'
                }
            },
            tooltip: {
		valueSuffix: ''
	    },
	    legend: {
		    layout: 'vertical',
		    align: 'right',
		    verticalAlign: 'middle',
		    borderWidth: 0
	    },
            plotOptions: {
                area: {
                    marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: false
                            }
                        }
                    },
                    /*events: {
                        legendItemClick: function () {
                           return false; 
                           // <== returning false will cancel the default action
                            }
                        },
                        showInLegend: true*/
                    }
            },
          	/*series: [{
                name: 'Resolved',
                data: [15,20,25,26,25,30,28,30,27,26,22,14]
            }, {
                name: 'Opened',
                data: [10,12,15,18,10,12,10,8,5,5,4,2]
            }]*/
            series: eval(op),
        });
    });
    
});
</script>

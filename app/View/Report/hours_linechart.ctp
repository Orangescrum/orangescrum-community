<div id="linechart" style="width:99%"></div>
<script type="text/javascript">
$(function(){
	var dt = <?php echo $dt_arr;?>;
	var tikinterval = <?php echo $tinterval;?>; 
	var namedata = <?php echo $carr; ?>;
        $('#linechart').highcharts({
            title: {
                text: '',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
		type:'datetime',
                categories: eval(dt),
		showFirstLabel:true,
                showLastLabel:true,
                tickInterval:tikinterval,
            },
            yAxis: {
            min:0,
                title: {
                    text: 'Hour(s)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: 'hour'
                /*formatter: function() {
                        return  Highcharts.dateFormat('%e. %b', this.x)+'<br><b>'+ this.series.name +':</b> '+ this.y +' hour';
                }*/
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: eval(namedata)
        });
});
</script>

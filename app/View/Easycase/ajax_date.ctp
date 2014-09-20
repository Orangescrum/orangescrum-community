<li>
    <a href="javascript:void(0);">
	<input type="checkbox" id="date_any" class='cbox_date'  <?php if(trim($Date) == '' && !strstr(trim($Date),":" )){echo "checked";} ?> onClick="checkboxDate('','check');filterRequest('time');"/>
	<font onClick="checkBox('date_any');checkboxDate('any','text');filterRequest('time');" >&nbsp;Anytime</font>
    </a>
</li>

<li>
    <a href="javascript:void(0);">
	<input type="checkbox" id="date_one" class='cbox_date'  <?php if(trim($Date) == 'one'){echo "checked";} ?> onClick="checkboxDate('one','check');filterRequest('time');"/>
	<font onClick="checkBox('date_one');checkboxDate('one','text');filterRequest('time');" >&nbsp;Past hour</font>
    </a>
</li>
<li>
    <a href="javascript:void(0);">
	<input type="checkbox" id="date_24" class='cbox_date'  <?php if(trim($Date) == '24'){echo "checked";} ?> onClick="checkboxDate('24','check');filterRequest('time');"/>
	<font onClick="checkBox('date_24');checkboxDate('24','text');filterRequest('time');" >&nbsp;Past 24 hours</font>
    </a>
</li>
<li>
    <a href="javascript:void(0);">
	<input type="checkbox" id="date_week" class='cbox_date'  <?php if(trim($Date) == 'week'){echo "checked";} ?> onClick="checkboxDate('week','check');filterRequest('time');"/>
	<font onClick="checkBox('date_week');checkboxDate('week','text');filterRequest('time');">&nbsp;Past week</font>
    </a>
</li>
<li>
    <a href="javascript:void(0);">
	<input type="checkbox" id="date_month" class='cbox_date'  <?php if(trim($Date) == 'month'){echo "checked";} ?> onClick="checkboxDate('month','check');filterRequest('time');"/>
	<font onClick="checkBox('date_month');checkboxDate('month','text');filterRequest('time');" >&nbsp;Past month</font>
    </a>
</li>
<li>
    <a href="javascript:void(0);">
	<input type="checkbox" id="date_year" class='cbox_date'  <?php if(trim($Date) == 'year'){echo "checked";} ?> onClick="checkboxDate('year','check');filterRequest('time');"/>
	<font onClick="checkBox('date_year');checkboxDate('year','text');filterRequest('time');" >&nbsp;Past year</font>
    </a>
</li>
<?php if(strstr(trim($Date),":")){
 $dt=explode(":",trim($Date));
}
?>
<li>
    <a href="javascript:void(0);">
	<input type="checkbox" id="date_custom" class='cbox_date'  onClick="checkboxcustom('custom_date','date_custom','');" <?php if($dt){?> checked="checked"<?php } ?> />
	<font onClick="checkBox('date_custom');checkboxcustom('custom_date','date_custom','');" >&nbsp;Custom range</font>
    </a>
</li>
<div id="custom_date" <?php if(!$dt){?>style="display:none;"<?php }?> >
	<div  class="cdate_div_cls">
		<input type="text" id="frm"  value='<?php echo @$dt['0'];?>' placeholder="From" class="form-control"/><br/>
		<input type="text" id="to" value='<?php echo @$dt['1'];?>' placeholder="To" class="form-control"/>
	</div>
	<div  class="cdate_btn_div" style="text-align:center;margin-top: 5px;cursor:pointer">
		<button class="btn btn-primary cdate_btn"  onclick="checkboxrange('custom_range','text');">Search</button>
	</div>
</div>
<script>
$(function() {
	$( "#frm" ).datepicker({
		changeMonth: false,
		changeYear: false,
		//minDate: 0,
		hideIfNoPrevNext: true,
		maxDate: "0D",
		onClose: function( selectedDate ) {
             $( "#to" ).datepicker( "option", "minDate", selectedDate );
         }
	});
});
$(function() {
	$( "#to" ).datepicker({
		changeMonth: false,
		changeYear: false,
		//minDate: 0,
		hideIfNoPrevNext: true,
		maxDate: "0D",
		onClose: function( selectedDate ) {
                $( "#frm" ).datepicker( "option", "maxDate", selectedDate );
            }
	});
});


$("#ui-datepicker-div").click(function(e){
	e.stopPropagation();
});
</script>
<?php
$jsSearch = "";
$resVal = 0;
if(isset($case_status) && $case_status && $case_status != "all") { $resVal = 1; }
if(isset($case_types) && $case_types && $case_types != "all") { $resVal = 1; }
if(isset($pri_fil) && ($pri_fil || $pri_fil == 0) && $pri_fil != "" && $pri_fil != "all") { $resVal = 1; }
if(isset($pri_fil) && $pri_fil && $pri_fil != "all") { $resVal = 1; }
if(isset($case_member) && $case_member && $case_member != "all") { $resVal = 1; }
if(isset($date_fil) && $date_fil && $date_fil != "any" || $date_fil != "") { $resVal = 1; }
?>

<table cellpadding="0" cellspacing="0" border="1">
	<tr>
		<td align="left">
			<?php
			if(isset($case_page) && $case_page && $case_page != 1 && $resetall == 0)
			{
				$resVal = 1;
				echo "&nbsp;Page: <i>".$case_page."</i>";
			}
			?>
		</td>
		<td align="left">
			<?php
			if(isset($case_search) && $case_search != "")
			{
				$resVal = 1;
				echo "<div class='fl wrapword'>&nbsp;Search: <i>".$case_search."</i></div>";
				$jsSearch = "setSearchValue();";
			}
			if(isset($resVal) && $resVal) {
			?>
				<div style="position:relative" class="fl">
					&nbsp;&nbsp;<a href="javascript:jsVoid();" class="button-link" title="Reset Filters" onClick="<?php echo $jsSearch; ?>resetAllFilters('all');ajaxCaseView('case_project.php');" style="padding:0px 4px;font-size:11px;">Reset</a>
					<!--<div class="reset_indication"><img src="<?php HTTP_ROOT ?>img/images/reset_indication.png" /></div>-->
					<div class="reset_indication">
						<div class="fl">click to Reset Filters</div><div align="right" class="fr close_reset" style="position:relative; left:6px; top:-6px; font-size:10px; cursor:pointer">x</div>
						<div style="right: 12px; position: absolute; top: 21px;"><img src="<?php HTTP_ROOT ?>img/images/desc_large.png" /></div>
					</div>
					
					
				</div>
			<?php
			}
			?>
		</td>
	</tr>
	
</table>
<script type="text/javascript">
	function blink(selector){
	$(selector).animate({opacity:"0.7"},1000,function(){
		$(this).animate({opacity:"0.9"},1000,function(){
			blink(this);
		});
	});
	}
	$(document).ready(function() {
		blink('.reset_indication');
	});
	$(".close_reset").click(function(){
		$(this).parent(".reset_indication").hide();
		/*$(".reset_indication").unbind("mouseover",blink);*/
	});
</script>

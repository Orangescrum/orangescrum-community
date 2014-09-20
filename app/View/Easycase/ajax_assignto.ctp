<input type="hidden" id="assignTo_all">
<?php
$m=0;
if(isset($asnArr))
{
//echo 'smruti';pr($asnArr);exit;
	$m=0;
	$totAsnCase = 0;
	$h = 0;
	foreach($asnArr as $Asn)
	{   $Asnbers=explode("-",$CookieAsn);
		$m++;
		$AsnId = $Asn['User']['id'];
		$AsnUniqId = $Asn['User']['uniq_id'];
		$AsnName = $Asn['User']['name'];
		$AsnLogin = $Asn['User']['dt_last_login'];
		$shortname =  $Asn['User']['short_name'];
		//if($m > 5){$h++;
		?>
			<li <?php if($m > 5){$h++;?> id="hidAsn_<?php echo $h; ?>" style="display:none;" <?php }?>>
		    <a href="javascript:void(0);">
			<div class="slide_menu_div1">
			<input type="checkbox" id="Asns_<?php echo $m; ?>" onClick="checkboxAsns('Asns_<?php echo $m; ?>','check');filterRequest('assignto');"  <?php if (in_array($AsnId, $Asnbers)) { echo "checked"; } ?>/>
			<font onClick="checkboxAsns('Asns_<?php echo $m; ?>','text');filterRequest('assignto');"   title='<?php echo $this->Format->formatText($shortname); ?>'>
			&nbsp;<?php echo $this->Format->formatText($AsnName); ?> (<?php echo $Asn[0]['cases']; ?>)</font>
			<div style="margin:0;color:#999999;line-height:16px;padding-left:20px;font-size:11px;">
			<?php
			if($AsnLogin == "" || $AsnLogin == "NULL" || $AsnLogin == "0000-00-00 00:00:00" || !SES_TIMEZONE)
			{
				echo "Yet to Sign In";
			}
			else
			{
				$last_logindt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$AsnLogin,"datetime");
				$locDResFun2 = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
				echo "Last Sign In: ".$this->Datetime->dateFormatOutputdateTime_day($last_logindt,$locDResFun2);
			}
			?>
		    </div>
		    <input type="hidden" name="Asnids_<?php echo $m; ?>" id="Asnids_<?php echo $m; ?>" value="<?php echo $AsnId; ?>" readonly="true">
		    </div>
		    </a>
		</li>
		<?php
	}
	if($h != 0)
	{
	?>
	<div class="slide_menu_div1 more-hide-div">
		<div class="more" align="right" id="Asn_more" >
			<a href="javascript:jsVoid();" onClick="moreLeftNav('Asn_more','Asn_hide','<?php echo $h; ?>','hidAsn_')">more...</a>
		</div>
		<div class="more" align="right" id="Asn_hide" style="display:none;">
			<a href="javascript:jsVoid();" onClick="hideLeftNav('Asn_more','Asn_hide','<?php echo $h; ?>','hidAsn_')">hide...</a>
		</div>
	</div>
	<?php
	} ?>
<?php } ?>
<input type="hidden" id="totAsnId" value="<?php echo $m; ?>" readonly="true"/>

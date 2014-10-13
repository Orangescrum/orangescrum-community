<input type="hidden" id="types_all">
<?php
if(isset($typeArr))
{
	$t=0;
	$totCase = 0;
	$h=0;
	foreach($typeArr as $typ)
	{
		$typeId = $typ['t']['id'];
		$typeShortName = $typ['t']['short_name'];
		$typeName = $typ['t']['name'];
		$typecount = $typ['0']['count'];
		
		$img = "<img src='".HTTP_IMAGES."images/types/".$typeShortName.".png' />";
		if (isset($typ['t']['company_id']) && trim($typ['t']['company_id'])) {
		    $img = "";
		}
		
		$t++;
		//if($t > 5)	$h++;
		?>
		<!--<span id="hidType_<?php echo $h; ?>" style="display:none;">-->
		<li <?php if($t > 5){ $h++;?>id="hidType_<?php echo $h; ?>" style="display:none;"<?php } ?>>
		    <a href="javascript:void(0);">
			<input type="checkbox" class="cst_type_cls" id="types_<?php echo $typeId; ?>" data-id="<?php echo $typeId; ?>" onClick="checkboxTypes('types_<?php echo $typeId; ?>','check');filterRequest('type');" style="cursor:pointer;" <?php if(strstr($CookieTypes,(string)$typeId)) { echo "checked"; } ?>/>
			<font class="filter-type-font" onClick="checkboxTypes('types_<?php echo $typeId; ?>','text');filterRequest('type');"  title="<?php echo $typeName; ?>">
			&nbsp;<?php echo $img." ".$typeName; ?> (<?php if($proj_uniq_id != 'all'){ echo $typecount; }else{echo $typecount;}?>)
			</font>
			<input type="hidden" name="typeids_<?php echo $typeId; ?>" id="typeids_<?php echo $typeId; ?>" value="<?php echo $typeId; ?>" readonly="true">
		    </a>
		</li>
		<!--</span>-->
		<?php
	}
	if($h != 0)
	{
	?>
	<div class="slide_menu_div1 more-hide-div">
		<div class="more" align="right" id="type_more">
			<a href="javascript:jsVoid();" onClick="moreLeftNav('type_more','type_hide','<?php echo $h; ?>','hidType_')">more...</a>
		</div>
		<div class="more" align="right" style="display:none;" id="type_hide">
			<a href="javascript:jsVoid();" onClick="hideLeftNav('type_more','type_hide','<?php echo $h; ?>','hidType_')">hide...</a>
		</div>
	</div>
	<?php
	}
	?>
	<input type="hidden" id="totType" value="<?php echo $t; ?>" readonly="true"/>
	<?php
}
?>
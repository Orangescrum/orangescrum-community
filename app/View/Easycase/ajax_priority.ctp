<input type="hidden" id="priority_all">
<?php
$priArray = array("Low","Medium","High");
foreach($priArray as $p)
{
?>
	<li>
	    <a href="javascript:void(0);">
	<input type="checkbox" id="priority_<?php echo $p; ?>" onClick="checkboxPriority('priority_<?php echo $p; ?>','check');filterRequest('priority');" style="cursor:pointer;"  <?php if(strstr($CookiePriority,(string)$p)) { echo "checked"; } ?>/>
	
	<font onClick="checkboxPriority('priority_<?php echo $p; ?>','text');filterRequest('priority');" style="cursor:pointer;">
		&nbsp;
		<?php 
		if($p == "High") { echo "<font style='color:#AE432E'>HIGH (".$query_pri_high.")</font>"; } elseif($p == "Medium") { echo "<font style='color:#28AF51'>MEDIUM (".$query_pri_medium.")</font>"; } else { echo "<font style='color:#AD9227'>LOW (".$query_pri_low.")</font>"; }
		?>
	</font>
	</a>
	</li>
<?php
}
?>

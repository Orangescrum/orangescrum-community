<?php 
$wid="display:none;";
for($i=1;$i<$cnt;$i++){
$v="widgettype".$i;
//echo $$v;
if($$v == "1"){
$wid="display:block;";
}

}
?>

<input type="hidden" id="types_all">
<?php
if(isset($typeArr))
{   $t=0;
	foreach($typeArr as $typ)
	{	$t++;
		$typeId = $typ['Type']['id'];
		$typeShortName = $typ['Type']['short_name'];
		$typeName = $typ['Type']['name'];
		
		?>
		
		<div class="fl">
			<div class="widget text-only" id="widgettype<?php echo $typeId;?>" style="height:18px;min-width:50px;width:auto;<?php 
																$vis="widgettype".$typeId;
																if($$vis=='1'){
																	echo 'display:none;';
																}else{
																	echo 'display:block;';
																}
														?>">
	<a href="#" class="close-widget" title="Hide Widget" rel="tooltip" onclick="hideCloseWidgettype('widgettype<?php echo $typeId;?>','<?php echo $cnt;?>');showselecttype('widgettype<?php echo $typeId;?>')">x</a>
	
		<div class="right">
			<div style="margin-top:-2px;color:#6A6A6A;" class="left"><span style="font-size:14px;font-weight:bold;padding-top:9px;"><?php echo $this->Casequery->displayCaseNo($proj_id,'type',$typeId,$caseMenuFilters); ?></span></div>
			
			<div class="text_shadow left"  style="font-size:13px;float:left;margin-top:4px;"><a href="javascript:jsVoid();" title="<?php echo $typeName;?>" rel="tooltip"  onClick="typeTop('<?php echo $typeId; ?>');ajaxCaseView('case_project.php')" style="color:#6A6A6A; text-decoration:underline"><img src="<?php echo HTTP_IMAGES.'images/types/'.$typeShortName.'.png';?>" height="14" width="14"/></a></div>
		</div>	
	
</div>
		
		<input type="hidden" name="typeids_<?php echo $t; ?>" id="typeids_<?php echo $t; ?>" value="<?php echo $typeId; ?>" readonly="true">
		</div>
			
		<?php
		}	
	    ?>
	
	
	<input type="hidden" id="totType" value="<?php echo $t; ?>" readonly="true"/>
	<?php
	}
	?>

<div class="fl" align="left" style="margin:0px 5px">
    <div class="popup_link_case_proj_parent fl" align="left" style="<?php echo $wid;?>" id="closewidgettype">
 		<div class="popup_link_case_proj" id="closedwidgetchildtype" style="<?php echo $wid;?>">
			<a href="javascript:jsVoid();" onclick="open_pop(this)" style="font-weight:normal;">
				<span>Show Type Widget</span>
			</a>
		</div>
		<div class="popup_option" id="popup_option1" style="display:none;position:absolute;">
			<div class="pop_arrow_new" style="position:absolute; z-index:99; left:12px"></div>
            <div class="popup_con_menu" align="left" style="left:-1px; min-width:50px;padding: 2px 8px">
                <div align="left">
					<?php
						if(isset($typeArr))
						{   $t=0;
							foreach($typeArr as $typ)
							{	$t++;
								$typeId = $typ['Type']['id'];
								$typeShortName = $typ['Type']['short_name'];
								$typeName = $typ['Type']['name'];
					?>
    			
                    <div  id="widgettype<?php echo $typeId.'1';?>" style="<?php
								 $vis="widgettype".$typeId;
								if($$vis=='1'){
									echo 'display:block;';
								}else{
									echo 'display:none;';
								} ?>">
                        <a href="javascript:void(0);" onclick="hideCloseWidgettype('widgettype<?php echo $typeId;?>','<?php echo $cnt;?>');"><?php echo strtoupper($typeShortName);?></a>
                    </div>
    				
						<?php } }?>
    		
                </div>
            </div>
		</div>
	</div>
</div>



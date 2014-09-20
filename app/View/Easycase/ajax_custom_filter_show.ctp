<?php if($totalfilter){
               //$totalfilter = count($getfilter);          
     ?>
<style>
#main-nav ul li a.custFil{
	padding:0px 0px 0px 20px!important;
	height:16px!important;
}
</style>
				<?php 
				foreach($getfilter as $getfil)
				{
				if(!empty($getfil['CustomFilter']['filter_name']) && trim($getfil['CustomFilter']['filter_name']) != ''){
				?>
				<li id="customFilterRow_<?php echo $getfil['CustomFilter']['id']; ?>" onmouseover="displayDeleteImg('<?php echo $getfil['CustomFilter']['id']; ?>');" onmouseout="hideDeleteImg('<?php echo $getfil['CustomFilter']['id']; ?>');">
					<a href="javascript:void(0);" class="customlink" id="lnkcustomFilterRow_<?php echo $getfil['CustomFilter']['id']; ?>">
						<div class="fl" onclick="javascript:location.href='<?php echo HTTP_ROOT.'dashboard#tasks/'.$getfil['CustomFilter']['id']; ?>'">
							<?php echo $this->Format->shortLength(htmlentities($getfil['CustomFilter']['filter_name']),10);?>
						</div>
						<div id="deleteImg_<?php echo $getfil['CustomFilter']['id']; ?>" class="dropdown_cross fr" style="display:none;" onclick="deleteCustomFilter('<?php echo $getfil['CustomFilter']['id']; ?>','<?php echo urlencode($getfil['CustomFilter']['filter_name']);?>');">&times;</div>
						<div class="cb"></div>
					</a>
				</li>
				<?php }
				}
				?>
				
				<?php if($totalfilter >3 && ($limit1+3)< $totalfilter) { ?>
					<li style="background:none;" class="li_nohover">
						<a href="javascript:void(0);">
							<div class="fr" style="width:64px;">
								<?php if($totalfilter >3 && $limit1 >=3  ) {?>
									<span class="menu_arrow_prev" onclick="previousCustomFilter(<?php echo $limit1; ?>,'less');" title="Previous"> << </span>
								<?php } if($totalfilter >3 && ($limit1+3)< $totalfilter){ ?>
									<span class="menu_arrow_next" onclick="showmoreCustomFilter(<?php echo $limit1; ?>,'more');" title="Next"> >> </span>
								<?php } ?>	
							</div>
							<div class="cb"></div>
						</a>
					</li>
				<?php } ?>	

<?php }else{ ?>
<li style="color:#FFFFFF;text-align:center;font-size:14px;">No custom filters</li>
<?php } ?>

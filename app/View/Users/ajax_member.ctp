<table cellpadding="0" cellspacing="0" width="100%" align="center" style="border:1px solid #DCDCDC;">
	<tr>
		<td style="color:#333333;" class="tophead">Users of my projects <font style="font-weight:normal">(<?php echo count($getUsers); ?>)</font></td>
	</tr>
<?php if(!empty($getUsers)){ ?>
	<tr>
		<td style="border-top:0px solid #DCDCDC;padding-left:2px;" align="center">
			<?php
			foreach($getUsers as $usr) { ?>
			<div style="float:left;padding:4px;width:45px">
				<?php
				if(trim($usr['User']['photo']))
				{
				?>				
					<div style="border: 1px solid #E9E9E9;height:50px;width:40px;padding:2px;display: table-cell;vertical-align: middle;"><img src="<?php echo HTTP_ROOT;?>users/image_thumb/?type=photos&file=<?php echo $usr['User']['photo']; ?>&sizex=40&sizey=40&quality=100" border="0" title="<?php echo $this->Format->formatText(trim($usr['User']['name']." ".$usr['User']['last_name'])); ?>"/></div>
					
				<?php
				}
				else
				{
				?>
					<div style="border: 1px solid #E9E9E9;height:50px;width:40px;padding:2px;display: table-cell;vertical-align: middle;"><img src="<?php echo HTTP_ROOT;?>users/image_thumb/?type=photos&file=user.png&sizex=40&sizey=40&quality=100" border="0" title="<?php echo $this->Format->formatText(trim($usr['User']['name']." ".$usr['User']['last_name'])); ?>"/></div>
					
				<?php
				}
				?>
				<div style="clear:both"></div>
				<span title="<?php echo $this->Format->formatText(trim($usr['User']['name']." ".$usr['User']['last_name'])); ?>" rel="tooltip"><?php echo $this->Format->formatText($usr['User']['short_name']); ?></span>
				<div style="clear:both"></div>
			</div>
			<?php
			}
			?>
		</td>
	</tr>
<?php }else{ ?>
<tr>
	<td style="border-top:1px solid #DCDCDC;padding:5px 2px;font-size:12px;" align="left">
		<div style="color:#777;text-align:center;">No users found</div>
	</td>
</tr> 
<?php } ?>
</table>
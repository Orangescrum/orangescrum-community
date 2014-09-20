<div style="margin-top:10px;" class="fl" >
<?php
	$fmnth = date('M',strtotime("$fwkdt"));
	$lmnth = date('M',strtotime("$lwkdt"));
	
	if($fmnth == $lmnth) {
?>

<h1 style="font-size:20px;"><?php echo date('d',strtotime("$fwkdt"))." - ".date('d',strtotime("$lwkdt"))." ".date('M',strtotime("$lwkdt"))." ".date('Y',strtotime("$lwkdt"));  ?></h1>
<?php 
} else { ?>
<h1 style="font-size:20px;"><?php echo date('d',strtotime("$fwkdt"))." ".date('M',strtotime("$fwkdt"))." - ".date('d',strtotime("$lwkdt"))." ".date('M',strtotime("$lwkdt"))." ".date('Y',strtotime("$lwkdt"));  ?></h1>

<?php } ?>

</div>
<div style="margin-top:10px;" class="fr" > 
<div class="fl wkview" style="padding:5px;border-left:1px solid grey;border-top:1px solid grey;border-bottom:1px solid grey;"><a href='javascript:void(0);' style='text-decoration:none;color:black;font-size:15px;font-family:"Helvetica Neue",Arial,Verdana,"Nimbus Sans L",sans-serif'' onclick="anotherwk('prev','');"><b>&lt;</b></a></div>
<div class="fl wkview" style="padding:5px;border:1px solid grey;"><a href='javascript:void(0);' onclick="anotherwk('','');" style='text-decoration:none;color:black;font-size:15px;font-family:"Helvetica Neue",Arial,Verdana,"Nimbus Sans L",sans-serif'>This Week</a></div>
<div class="fl wkview" style="padding:5px;border-right:1px solid grey;border-top:1px solid grey;border-bottom:1px solid grey;"><a href='javascript:void(0);' onclick="anotherwk('next','');" style='text-decoration:none;color:black;font-size:15px;font-family:"Helvetica Neue",Arial,Verdana,"Nimbus Sans L",sans-serif''><b>&gt;</b></a></div>
<div class="cb"></div>
</div>
<div class="cb" style="height:10px;"></div>
<input type="hidden" name="fwddt" id="fwddt" value="<?php echo $fwddt; ?>" />
<input type="hidden" name="bckdt" id="bckdt" value="<?php echo $bckdt; ?>" />
<input type="hidden" name="prjctname" id="prjctname" value="<?php echo $pjname; ?>" />
<table width="100%" cellspacing="0" cellpadding="0" style="background:#D8DBD4;">
<?php $daytot = array(); ?>
	<thead>
		<tr>
		<?php foreach($wkdays as $k=>$d){ ?>
			<?php if($wkdates[$k] == date('Y-m-d')) { ?>
				<th style="border:1px solid;background:#FFFFAF;">
			<?php } else { ?>
				<th style="border:1px solid;">
			<?php } ?>
				<?php echo $d; ?><br/>
				<?php echo $wkdates[$k]; ?>	
			</th>
		<?php } ?>
		</tr>
	</thead>
	<tbody>
		<tr>
		     <?php if($dtwisearr) { ?>
			<?php foreach($wkdays as $k=>$d){ ?>
			<?php if($wkdates[$k] == date('Y-m-d')) { ?>
				<td style="border:1px solid;width:50px;background:#FFFFAF;">
			<?php } else { ?>
				<td style="border:1px solid;width:50px;">
			<?php } ?>
				<?php if(isset($dtwisearr[$wkdates[$k]])){ 
					foreach($dtwisearr[$wkdates[$k]] as $k=>$v){ ?>
						<?php 
						$msg = !empty($v[0]['title'])? $v[0]['title'] : $v[0]['message'];
						$easycaseid = $v[0]['id'];
						$crtdtime = $v[0]['crtdtime'];
						$uarr = $this->Format->getUserDtls($v['0']['user_id']);
						$uname = $uarr['User']['name']." ".$uarr['User']['last_name'];
						
						?>
						<div style="min-height:70px;max-height:70px;margin-top:5px;border-bottom:1px solid grey;position:relative;width:100%;" onmouseover="showdiv('<?php echo $easycaseid; ?>');" onmouseout="hidediv('<?php echo $easycaseid; ?>');">
					<?php	
						echo $this->Format->shortLength($msg,40);
						echo "<br/>"; ?>
						<div class="fr" style="margin-right:10px;font-size:15px;margin-top:5px;">
						<?php 
							echo "<b>".$v[0]['hours']."</b>"; 
						?>
						</div>
						<div style="border-radius:5px;z-index: 99999; position: absolute; background-color: rgb(255, 255, 255); top: 0px; display: none; left: 145px; width: 132px; text-align: left;" id="tooltip<?php echo $v[0]['id']; ?>">
						<?php echo $msg."<br/> at <b>".date('H:s a',strtotime($crtdtime))."</b><br/> By <b>".$uname."</b>"; ?>
						</div>
						</div>
						
						
				  <?php	}	
				}
				?>

			<?php } ?>
		<?php } else { ?>
				<td colspan="7" style="border:1px solid;">
			 <?php	print '<div style="min-height:50px;text-align:center;margin-top:25px;">No Data for this week.</div>';
			} 
		
		?>	
					</td>
		</tr>
		<tr>
			<?php foreach($wkdays as $k=>$d){ ?>
			<?php if($wkdates[$k] == date('Y-m-d')) { ?>
							<td style="border:1px solid;background:#FFFFAF">
			<?php } else { ?>
				<td style="border:1px solid;">
			<?php } ?>
				<?php if(isset($hrs[$wkdates[$k]])){ ?>
					<div style="min-height:30px;max-height:10px;margin:10px;font-size:15px;" class="fr">
						<?php	
							$daytot[] = array_sum($hrs[$wkdates[$k]]);
							echo "<b>".array_sum($hrs[$wkdates[$k]])."</b>";
						?>
					</div>
						
				  <?php	}?>
			</td>
			<?php } ?>
			
		</tr>
	</tbody>
</table>
<div class="fr" style="margin-top:10px;margin-right:10px;font-size:15px;">
	Total Hours: <?php  echo "<b>".array_sum($daytot)."</b>"; ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('[rel=tooltipsss]').tipsy({gravity:'w', fade:true});
	});
	function anotherwk(btntype,prjuniqid){
		if(btntype == 'prev'){
			var fwkdt = $('#bckdt').val();
		}else if(btntype == 'next'){
			var fwkdt = $('#fwddt').val();
		}else{
			var fwkdt = "";
		}
		
		var strUrl = $('#pageurl').val();
		$.post(strUrl+"reports/weeklyhours_report",{'pagetype':btntype,'fwkdt':fwkdt, 'prjuniqid':prjuniqid},function(res){
			$('#main_con').html(res);
			if(prjuniqid){
				$('#prjname').html($('#prjctname').val());
			}
		})
	}
	
	function showdiv(easycaseid){
		$('#tooltip'+easycaseid).show();
	}
	function hidediv(easycaseid){
		$('#tooltip'+easycaseid).hide();
	}
</script>
<style>
	.wkview{
		background: -webkit-linear-gradient(top, #FFFFFF, #EEEEEE);
		background: -moz-linear-gradient(top, #FFFFFF, #EEEEEE);
		background: -o-linear-gradient(top, #FFFFFF, #EEEEEE);
		background: linear-gradient(to bottom, #FFFFFF, #EEEEEE) repeat scroll 0 0 transparent
	}
	.wkview:hover{
		background: -webkit-linear-gradient(top, #F8F8F8, #E8E8E8);
		background: -moz-linear-gradient(top, #F8F8F8, #E8E8E8);
		background: -o-linear-gradient(top, #F8F8F8, #E8E8E8);
		background: linear-gradient(to bottom, #F8F8F8, #E8E8E8) repeat scroll 0 0 transparent
	}
</style>

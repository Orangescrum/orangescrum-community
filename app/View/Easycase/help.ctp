<script language="javascript" type="text/javascript">
$(document).ready(function() {
    $('.accordionButton').click(function(){
        $('.accordionContent').slideUp(300);
		$(".open_list").addClass("plus");
        if($(this).next().is(':hidden') == true) {
            $(this).next().slideDown(300);
			$(this).children().children(".open_list").removeClass("plus");
         } 
     });
    $('.accordionContent').hide();
	$(".open_list:first").removeClass("plus"); //First time the content will be stayed open
	$('.accordionButton:first').next().slideDown('slow');
});

function validate()
{
	if($("#search_help_txt").val() == ''){
		return false;
	}else{
		return true;
	}
}

</script>
<?php if(!defined('SES_ID') || !SES_ID){ ?>
<style type="text/css">
*,*:before,*:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
.noaccordContent, .accordionContent{padding-left:40px;}
.noaccordContent ul, .accordionContent ul{margin:1em 0;}
body {
	font-family:myriadpro-regular;
}
</style>
<?php } ?>


<?php echo $this->element('help_tabbs');?>
	<div style="font-size:17px;padding-left:12px;">Here are some answers to the most common questions we've been asked.</div>
	<?php if(defined('SES_ID') && SES_ID){ ?><div style="font-size:17px;padding-left:12px;">If you still have questions? <a href="javascript:void(0);" class="support-popup" style="outline:none;">Contact Us</a></div><?php } else { echo "<br/>"; } ?>

	<div class="head fl"><h3>General Question</h3></div>
	<div class="fr search_help">
		<form name="search_help" action="<?php echo $url; ?>">
			<div>
			<input type="text" placeholder="Search" value="<?php echo urldecode(trim(@$_GET['search_help_txt'])); ?>" name="search_help_txt" id="search_help_txt"/>
			</div>
			<button class="search_icon fr" onClick="return validate();"></button>
		</form>	
	</div>
	<div class="cb"></div>
	<div class="left_panel_list fl">
		<ul>
			<?php foreach ($allSubjectData as $key => $value) {
					if($key == (count($allSubjectData) - 1)){
						$classLast = 'class="last"';
					}else{
						$classLast = '';
					}
					
					if($subjectId == $value['Subject']['id'] && trim(@$_GET['search_help_txt']) == ''){
						$selectColor = 'color:#4F92BF';
					}else{
						$selectColor = '';
					}
			?>
				<li <?php echo $classLast; ?>>
					<a href="<?php echo HTTP_ROOT."easycases/help/".$value['Subject']['id']."/".$value['Subject']['subject_name'];?>" style="outline:none;<?php echo $selectColor; ?>"><?php echo $value['Subject']['subject_name'];?></a>
				</li>
			<?php } ?>	
		</ul>
	</div>
	<?php if(trim(@$_GET['search_help_txt']) && $isSearchresult == 0){?> <!--  If search text present but no search result present the display error message  -->
		<div id="display_div">
			<div class="right_panel_list fl">   
				<div class="each_list_head">Search Results for: <span style="font-size:30px;"><?php echo trim(@$_GET['search_help_txt']); ?></span></div>
				<div class="cb"></div>
				<div class="detail_help">
					<div style="color:#FF0000;">No Search Result Found</div>
				</div>
			</div>
		</div>
	<?php }else{ ?>	
		<div id="display_div">
			<div class="right_panel_list fl">   
				<div class="each_list_head">
					<?php
						if($isSearchresult == 1){
							echo "Search Results for: <span style='font-size:30px;'>".trim(@$_GET['search_help_txt'])."</span>";
						}else{
							echo $subject_name;
						}
					 ?>	
				</div>
							
				<div class="cb"></div>
				<?php foreach ($allHelpData as $key => $value) {	?>
					<div class="detail_help">
						<?php if(isset($value['Help']['title']) && $value['Help']['title'] != ''){ ?>
							<div class="accordionButton">
								<a href="javascript:void(0);" style="outline:none;"><i class="fl open_list plus"></i><?php echo $value['Help']['title']; ?></a>
							</div>
							<div class="accordionContent">
								<ul>
									<?php echo $value['Help']['description'] ?>
								</ul>
							</div>	
						<?php }else{ ?>	
							<div class="noaccordContent">
								<ul>
									<?php echo $value['Help']['description'] ?>
								</ul>
							</div>	
						<?php } ?>
					</div>
				<?php } ?>	
				<div class="cb"></div>
			</div>
		</div>
	<?php } ?>
</div>
<?php if(!defined('SES_ID') || !SES_ID){ ?>
	<div class="cb"></div>
	<div class="sub_form_bg" style="margin:40px auto;text-align:center;">
		<a style="text-decoration:none;" href="<?php echo PROTOCOL."www.".DOMAIN; ?>signup/getstarted<?php echo $ablink; ?>" onclick="getstarted_ga(' aboutus');">
		   <span class="tk_tour" style="padding:10px 30px">Get Started for Free</span>
		</a>
	</div>
</div>
<?php 
} else { 
?>
	<br/><br/>
<?php
}
?>
<div style="clear:both"></div>
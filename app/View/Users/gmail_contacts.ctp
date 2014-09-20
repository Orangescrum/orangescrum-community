<style>
button.blue {
    -moz-user-select: none;
    background-color: #138FC4;
    background-image: -moz-linear-gradient(center top , #0C97CF, #138FC4);
    border: 1px solid #0A80AF;
    border-radius: 2px 2px 2px 2px;
    color: #FFFFFF !important;
    cursor: pointer;
    display: inline-block;
    font-family: helvetica;
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 0;
    min-width: 54px;
    padding: 4px 14px;
    text-align: center;
    text-decoration: none !important;
}
.search{
	border-radius: 5px 5px 5px 5px;
    display: inline-block;
    font-family: helvetica;
    font-size: 13px;
    line-height: 18px;
    padding: 4px 10px;
}
.srchImg{
	border-radius: 0 3px 3px 0;
    cursor: pointer;
    height: 25px;
    position: relative;
    right: 26px;
    top: 8px;
    width: 22px;
}
</style>
<script type="text/javascript" src="<?php echo JS_PATH; ?>list.min.js"></script> 
<table cellpadding="0" cellspacing="0" width="100%" align="left" border="0" style="border:0px solid #DCDCDC;margin-bottom:20px; font-family: helvetica;">
<tr>
	<td style="color:#990000;font-family: 'MyriadProSemibold';font-size: 22px;text-align: center;" class="tophead">Gmail Contacts</td>
</tr>
<?php if(!empty($gmailContact)){ ?>
<tr>
	<td style="border-top:0px solid #DCDCDC;padding:5px 2px;font-size:14px" align="left">
		<div id="example-list">
			<div style="float:left;margin-left:45px;margin-top:5px;">
				<button type="submit" value="Add" name="addMember" style="margin-top:5px;width:60px" class="blue small" onClick="selectEmailAddress();">Add</button>
			</div>
			<div style="float:right">
				<input class="search" placeholder="Search Email" style="border:1px solid #DCDCDC;" />
				<img class="srchImg" src="<?php echo HTTP_IMAGES;?>images/go.png">
			</div>
			<div style="clear:both"></div>			
			<div>			
				<div style="float:left;margin-left: 41px;margin-top: 15px;">
					<input type="checkbox" id="checkAll" onclick="seletallEmail()" style="cursor:pointer;">&nbsp;<span id="allLabel">All</span>		
				</div>
				<div style="float:left;margin-left: 187px;margin-top: 17px;">
					<span class="sort" data-sort="description" style="cursor:pointer;color:#138FC4;text-decoration:underline;">Sort by Email</span>
				</div>	
				<div style="clear:both"></div>
				<ul class="list" style="list-style-type:none">
				<?php for($i=0;$i<count($gmailContact);$i++){?>
	      		 	<li>
				 	<small class="description">				
						<input type="checkbox" id="chkMail<?php echo $i;?>" class="listEmail" value="<?php echo $gmailContact[$i];?>">
						<span style="padding:5px 2px;font-size:14px"><?php echo $gmailContact[$i];?></span>
						<br/>
					</small>  
				</li>
				<?php } ?>  
				</ul>
			</div>
		</div>
		<span id="btn_addmem" style="margin-left:45px;">
			<button type="submit" value="Add" name="addMember" style="margin-top:5px;width:60px" class="blue small" onClick="selectEmailAddress();">Add</button>
		</span>
	
	</td>
</tr>
<?php }else{ ?>
<tr>
	<td style="border-top:1px solid #DCDCDC;padding:5px 2px;font-size:14px;" align="left">
		<div style="color:#777;text-align:center;">No contacts found</div>
	</td>
</tr> 
<?php } ?>
</table>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.min.1.5.1.js"></script> 
<script>
var options = {
    valueNames: [ 'feature', 'description' ]
};

var featureList = new List('example-list', options);

function selectEmailAddress(){
	var emailList = new Array();
	if($('.listEmail').is(":checked")){
		$('.listEmail:checked').each(function(){
			var email = $(this).val();
			emailList.push(email);
		 });		
		 window.opener.document.getElementById("txt_email").value = emailList;
		 window.close();
	}
}
function seletallEmail(){
	if($('#checkAll').is(":checked")){		
		$(".listEmail").attr("checked",true);
	}else{
		$(".listEmail").attr("checked",false);
	}
}
</script>

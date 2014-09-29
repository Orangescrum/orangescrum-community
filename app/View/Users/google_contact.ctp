<?php header("Content-Type:text/html; charset=utf-8"); ?>
<style>
    .g_table{
	width:100%;
    }
    .g_table tr{
	margin: 5px;
    }
    .g_table tr td{
	padding: 5px;
    }
    body{
	margin: 0px !important;
	padding: 0px !important;
    }
    .eml_hover_lnk{
	text-decoration: underline;
	padding-left:15px;
	color:#5191BD;
	text-decoration: none;
    }
    .eml_hover_lnk:hover{
	text-decoration: underline;
    }
</style>
<script type="text/javascript" src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT; ?>css/style_new_v2.css"></link>
<script type="text/javascript"> 
   function SetName(name) {
       var emails = '';
       $('.g_contact_email').each(function(){
	   if($(this).is(':checked')){
	       if(emails == ''){
		   emails = $(this).attr('data-value');
	       }else{	   
		   emails += ', '+$(this).attr('data-value');
	       }
	   }
       });
       if(emails == ''){
	   alert('Select at least one email!');
	   return false;
       }else{
	   var m_entry = $('#manual_entries').val().trim();
	   if(m_entry != ''){
		emails += ','+m_entry;
	   }
	   setCookieContact('contact_emails', emails,1);
	    window.close();
       }       
    }   
    function setCookieContact(cname, cvalue, exdays) {
	var domain_set = '';
	if(window.location.host.match(/easyagile.us/g)){
	    domain_set = '.easyagile.us';
	}else if(window.location.host.match(/orangescrum.com/g)){
	    domain_set = '.orangescrum.com';
	}else if(window.location.host.match(/orangeprabhu.com/g)){
	    domain_set = '.orangeprabhu.com';
	}
	if(cname == 'contact_emails'){
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toGMTString();
	    document.cookie = cname + "=" + cvalue + ";domain="+domain_set+";path=/;" + expires;
	}
    }
	function cancel(){
	window.close();
    }
    $(function(){
	$("#search").keyup(function(){
	    _this = this;
	    var i = 0;
	    $('#nomatch_row').remove();
	    $.each($("#email-table tbody").find("tr"), function() {
		if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) == -1){
		   $(this).hide();
		}else{
		     i = 1;
		     $(this).show(); 
		}
	    });
	    if(!i){
		$("#email-table tbody").append('<tr id="nomatch_row"><td style="color:red;padding-left: 12px;">No match found!</td></tr>');
	    }
	}); 
	$("#search").keypress(function (e) {
	    if (e.which==13)
		$("#search").trigger('keyup');
	});
	$('.g_contact_email_all').on('click',function(){
		$('.g_contact_email').each(function(){
			if($('.g_contact_email_all').is(':checked')){
				$(this).prop('checked',true);
			}else{
				$(this).prop('checked',false);
			}
		});
	});
	/*$('.g_contact_email').on('click',function(e){
	    if($(this).is(':checked')){
		var checked_len = $(".g_contact_email:checked").length;
		var tot_len = $(".g_contact_email").length;
		if(checked_len == tot_len){
		    $('.g_contact_email_all').prop('checked',true);
		}
	    }else{
		$('.g_contact_email_all').prop('checked',false);
	    }
	});*/
    });
</script>
<?php if(trim($emails) != ''){ ?>
<script type="text/javascript">
    $(function(){
	//This is for setting email in the text field if we close the window manually
	var emails = "<?php echo $emails; ?>";
	setCookieContact('contact_emails', emails,1);
    });
</script>
<?php } ?>
<div style="margin: 0px auto; text-align: center;padding: 6px 10px 0 0;" class="popup_title">
    <span>Select your contacts and import</span>
</div>
<div style="float: left;height: 42px;padding: 5px;width: 98%">
    <input type="text" id="search" placeholder="Search Contact" style="height: 36px;padding: 3px;width: 100%;margin-right: 5px;" autocomplete="off"/>
</div>
<div style="clear:both;"></div>
<div style="height:400px;width:95%;overflow: auto;text-align: center;margin-bottom: 10px;padding: 10px;float:left;" class="col-lg-9 rht-con rht_bg">
    <table id="email-table" class="col-lg-12 new_auto_tab g_table" cellspacing="0" cellpadding="0">
    <?php
        if($emails != ''){
	    $emails = preg_replace('/\s+/', '',$emails);
	    $selectedEmails = explode(',',$emails);
	}
	$g_emal_array = array();
    ?>
    <!--<tr>
	<td><input type="checkbox" class="g_contact_email_all" /></td>
	<td style="color:#5191BD;">All</td>
    </tr>-->
    <?php
	$i = 0;
	if(count($contacts['feed']['entry'])){
	    $is_there_contact = 0;
	    foreach($contacts['feed']['entry'] as $cnt) {
		/*if($i%2 == 0){
		    echo "<tr>";
		}*/
		if(($CompUsers && array_search(strtolower($cnt['gd$email']['0']['address']), array_map('strtolower', $CompUsers)) === false) || !$CompUsers){
		    $is_there_contact = 1;
		    $g_emal_array[$i] = $cnt['gd$email']['0']['address'];
		    echo "<tr>";
		    $len = strlen($cnt['gd$email']['0']['address']);		
		    $e_orig = $cnt['gd$email']['0']['address'];
		    $e_display = $e_orig;		
		    if($len > 36){
			$e_display = substr($e_display,0,33).'...';
		    }
		    if(in_array($e_orig,$selectedEmails)){
			echo '<td><input type="checkbox" class="g_contact_email" data-value="'.$e_orig.'" checked="checked" /></td><td title="'.$e_orig.'">'.$e_display.'</td>';
		    }else{
		       echo '<td><input type="checkbox" class="g_contact_email" data-value="'.$e_orig.'"/></td><td title="'.$e_orig.'">'.$e_display.'</td>'; 
		    }	
		    $i++;
		    /*if($i%2 == 1){
			echo "</tr>";
		    }*/
		    echo "</tr>";
		}
	    }
	    if(!$is_there_contact){
		echo '<tr><td style="color:red;">No Contacts</td></tr>';
	    }
	}else{
	    echo '<tr><td style="color:red;">No Contacts</td></tr>';
	}
	//if(count($contacts['feed']['entry']) != $i){
       ?>
	<!--<script type="text/javascript">
	    var len_checked = $(".g_contact_email:checked").length;
	    var count_i = "<?php echo $i;?>";
	    if((len_checked == count_i) && count_i > 0){
		$('.g_contact_email_all').prop('checked',true);
	    }
	</script>-->
       <?php	    
	//}
    ?>
    </table>
    <?php 
       $manual_entries = '';
       if($emails != ''){
	$result_arr = array_diff($selectedEmails, $g_emal_array);
	if(count($result_arr)){
	    $manual_entries = implode(',',$result_arr);
	}
       }
    ?>
    <input type="hidden" value="<?php echo $manual_entries; ?>" id="manual_entries" />
</div>
<div style="clear:both;"></div>
<?php if(count($contacts['feed']['entry'])){ ?>
<div style="margin: 0px auto; text-align: center;">
    <button class="btn btn_blue" onclick="SetName();" style="padding:7px 15px;">Import</button> or <a class="eml_hover_lnk" href="javascript:void(0);" onclick="cancel();">Cancel</a>
</div>
<?php } ?>

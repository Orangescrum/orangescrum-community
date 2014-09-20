function selectAll(){
	if($('#chkall').is(":checked")){
		$(".chkbox").attr("checked",true);
		$("#approvebutton1").removeClass('disable_button');
		$("#approvebutton1").addClass('select_button');
		$("#disapprovebutton1").removeClass('disable_button');
		$("#disapprovebutton1").addClass('select_button');
		
		$("#approvebutton2").removeClass('disable_button');
		$("#approvebutton2").addClass('select_button');
		$("#disapprovebutton2").removeClass('disable_button');
		$("#disapprovebutton2").addClass('select_button');
	}else{
		$(".chkbox").attr("checked",false);
		$("#approvebutton1").removeClass('select_button');
		$("#approvebutton1").addClass('disable_button');
		$("#disapprovebutton1").removeClass('select_button');
		$("#disapprovebutton1").addClass('disable_button');
		
		$("#approvebutton2").removeClass('select_button');
		$("#approvebutton2").addClass('disable_button');
		$("#disapprovebutton2").removeClass('select_button');
		$("#disapprovebutton2").addClass('disable_button');
	}
}
function chk_emailId(){
	
	if($('#chkall').is(":checked")){
		$('#chkall').attr("checked",false);
	}
	
	if($('.chkbox').is(":checked")){
		$("#approvebutton1").removeClass('disable_button');
		$("#approvebutton1").addClass('select_button');
		$("#disapprovebutton1").removeClass('disable_button');
		$("#disapprovebutton1").addClass('select_button');
		
		$("#approvebutton2").removeClass('disable_button');
		$("#approvebutton2").addClass('select_button');
		$("#disapprovebutton2").removeClass('disable_button');
		$("#disapprovebutton2").addClass('select_button');
	}else{
		$("#approvebutton1").removeClass('select_button');
		$("#approvebutton1").addClass('disable_button');
		$("#disapprovebutton1").removeClass('select_button');
		$("#disapprovebutton1").addClass('disable_button');
		
		$("#approvebutton2").removeClass('select_button');
		$("#approvebutton2").addClass('disable_button');
		$("#disapprovebutton2").removeClass('select_button');
		$("#disapprovebutton2").addClass('disable_button');
	}
}
function multipleBetauserAction(status,type){ //alert(status);
	if($('.chkbox').is(":checked") || type){
		if(status == 'Approve'){
			var flag = 1;
			var c = confirm("Are you sure you want to approve the selected user?");
		}else if(status == 'Disapprove'){
			var flag = 2;
			var c = confirm("Are you sure you want to reject the selected user?");
		}else if(status == 'Reject'){
               var flag = 1;
			var c = confirm("Are you sure you want to approve the selected user?");
          }
		if(c == true){
			var betauserList = new Array();
		          if(type){
                          betauserList.push(type);
                    }else{
				     $('.chkbox:checked').each(function(){
					     var betauser = $(this).val();
					     var user_ar = betauser.split("|");
					     var beta_uId = user_ar[0];
					     var beta_is_approve = user_ar[1];
					     betauserList.push(beta_uId);
				     });
                    }
				var strURL = document.getElementById('pageurl').value;
				$('#tskloader').show();
				$.post(strURL+"users/betauser",{"id":betauserList,"flag":flag},function(data) {
					
					if(data == 'approve'){
						var msg = "Beta User approved successfully.";
					}else if(data == 'disapprove'){
						var msg = "Beta User rejected successfully.";
					}else if(data == 'Reject'){
						var msg = "Beta User approved successfully.";
				    }
					$('#upperDiv').show();
					$('#upperDiv').text(msg);
					$('#upperDiv').addClass('topalerts');
					$('#upperDiv').addClass('success');
					clearTimeout(time);
					time = setTimeout(removeMsg,6000);
					dTable.fnDraw();
					$('#tskloader').hide();
				});
		
		}else{
			return false;
		}
	}
}
function newBetaUser(menu,loder) 
{
	$('#'+menu).hide();
	$('#'+loder).show();
	$("#txt_email").val('');
	document.getElementById('err_email_new').innerHTML = "";
	$('#betauser_popup').show();
	document.getElementById(menu).style.display ="block";
	document.getElementById(loder).style.display ="none";
	cover_open('cover','betauser_popup');
}

function sendInvitation(txtEmailid,loader,btn)
{
	var email_id = document.getElementById(txtEmailid).value;//alert(email_id);exit;
	//var email_arr=email_id.split(',');
	var done = 1;
	if(email_id == "")
	{
		done = 0;
		msg = "Email cannot be left blank!";
		document.getElementById('err_email_new').innerHTML = "";
		document.getElementById('err_email_new').style.display = 'block';
		document.getElementById('err_email_new').innerHTML = msg;
		document.getElementById(txtEmailid).focus();
		return false;
	}
	else
	{
          var emailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		//var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          if(email_id.trim().match(emailRegEx)){
               done = 1;
          }else{
               done = 0;
			msg = "Invalid Email.";
			document.getElementById('err_email_new').innerHTML = "";
			document.getElementById('err_email_new').style.display = 'block';
			document.getElementById('err_email_new').innerHTML = msg;
			document.getElementById(txtEmailid).focus();
			return false;
          }
     
		/*if(!email_id.match(emailRegEx))
		{  
			if(email_id.indexOf(',') != -1){
				for(var i=0;i<email_arr.length;i++){
					if((email_arr[i].trim() != "") && (!email_arr[i].trim().match(emailRegEx))){
						done = 0;
						msg = "Invalid Email: '"+email_arr[i]+"'";
						document.getElementById('err_email_new').innerHTML = "";
						document.getElementById('err_email_new').style.display = 'block';
						document.getElementById('err_email_new').innerHTML = msg;
						document.getElementById(txtEmailid).focus();
						return false;
					}
				}
			}else{
				done = 0;
				msg = "Invalid E-Mail!";
				document.getElementById('err_email_new').innerHTML = "";
				document.getElementById('err_email_new').style.display = 'block';
				document.getElementById('err_email_new').innerHTML = msg;
				document.getElementById(txtEmailid).focus();
				return false;
			}	
		}*/
		if(done != 0){
			var strURL = document.getElementById('pageurl').value;
			$.post(strURL+"users/betauser",{"email":escape(email_id)},function(data) {
				if(data == 'success'){
					document.getElementById('upperDiv').style.display='block';
					var m = "Invitation sent successfully to \'"+email_id+"\'.";
					$("#upperDiv").find(".msg_span").html(m);
					cover_close('cover','betauser_popup');
					clearTimeout(time);
					time = setTimeout(removeMsg,6000);
					dTable.fnDraw();
				}else{
					document.getElementById('err_email_new').innerHTML = "";
					document.getElementById('err_email_new').style.display = 'block';
					document.getElementById('err_email_new').innerHTML = data;
					return false;
				}
			});
		}
		
	}
	return false;
}

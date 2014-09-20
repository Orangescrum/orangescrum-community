<style type="text/css">
.ui-state-highlight{
	height:10px;
}

.dropdown ul { 
	margin:0px;
	padding:0px;
	z-index:1000;
	
	 }
.frst_opt{
	margin-top:0px;
	padding-top:0px;	 
	 }
.dropdown dd,.dropdown .more_opt { position:relative;}
.dropdown a, .dropdown a:visited,.dropdown .opt1{ 
	color:#666666; 
	text-decoration:none;
	outline:none;
	}
.dropdown .opt1{
	background:#fff;
}
.dropdown a:hover { color:#666666;background-color:#EBF0F4;}
.dropdown .opt1 a{
	background:url(<?php echo HTTP_IMAGES."images/arro.png";?>) no-repeat scroll right center;
	display:block;
	width:140px;
	line-height:26px;
	}
.dropdown .opt1 a span{
	cursor:pointer;
	padding:5px 2px;
}
.dropdown .more_opt ul { 
	background:#fff none repeat scroll 0 0;
	border:1px solid #D3D3D3;
	color:#C5C0B0;
	display:none;
	left:0px;
	margin-top:-1px;
	padding:0px 0px;
	position:absolute;
	top:0px;
	width:140px;
	list-style:none;
}
.dropdown .opt1:hover{
 	color:#5d4617;background-color:#EBF0F4;
	border-radius:5px;
}
.dropdown span.value { display:none;}
.opt1 ul li a,.more_opt ul li a { 
	padding:3px 3px;
	display:block;
	z-index:3;
}
.opt1 ul li a:hover { background-color:#EBF0F4;display:block;}
.dropdown img.flag {
	border:none;
	vertical-align:middle;
	margin-left:5px;
	position:relative;
	top:-2px;
 }
.flagvisibility { display:none;}
.arr_sep{
	padding:0px;
}
.ui-datepicker-trigger
{
	background:#FFF;
	border:0;
	padding-top:0px;padding-bottom:0px;padding-right:0px;
	margin-top:0px;margin-bottom:0px;margin-right:0px;
	
}
.ui-datepicker-trigger:hover
{
	background:#FFF;
	border:0;
	padding-top:0px;padding-bottom:0px;padding-right:0px;
	margin-top:0px;margin-bottom:0px;margin-right:0px;
}
button.ui-datepicker-trigger{
	min-width:30px;	
}
</style>

<?php
if(isset($ntset) && $ntset=="ntset"){
$csset['CaseSetting']['assign_to']="";
$csset['CaseSetting']['type_id']="";
$csset['CaseSetting']['due_date']="";
$csset['CaseSetting']['priority']="";
}
?>	
<table align="left" style="border:1px solid #FF0000;" cellpadding="0" cellspacing="0">
	<tr height="40px">
		<td class="case_fieldprof" align="right">
			Assign To:
		</td>
		<td>
			<input type="hidden" id="project_uniqid" value="<?php echo $res['0']['projects']['uniq_id'];?>">
			<select id="assign_to" name="assign_to"  class="text_field" style="width:350px;-moz-border-radius:3px 3px 3px 3px;">
				<option value="0">Select</option>
				<?php
					foreach($res as $rs)
					{ 
						if(SES_ID==$rs['users']['id']) 
						{
				?>
							<option value="<?php echo $rs['users']['id']; ?>"
								<?php 
									if($csset['CaseSetting']['assign_to'] == $rs['users']['id'])
									{
										echo "selected=true";
									}
								?>
							>me</option>
						<?php 
						}
						else 
						{
						?>
							<option value="<?php echo $rs['users']['id']; ?>"
							<?php 
								if($csset['CaseSetting']['assign_to'] == $rs['users']['id'])
								{
									echo "selected=true";
								}
							?>
							> 
								<?php echo $rs['users']['name']; ?>
							</option>
						<?php 
						}
					} 
					?>
			</select>
			<?php 
				echo $this->Form->hidden('id',array('size'=>'45','class'=>'text_field','style'=>'-moz-border-radius:3px 3px 3px 3px','id'=>'id','maxlength'=>'100','value' => $csset['CaseSetting']['id']));
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="hidden" name="case" id="case" value="<?php echo SES_ID;?>" />
		</td>
	</tr>
	<tr height="40px">
		<td class="case_fieldprof" align="right">
			Case Type:
		</td>
		<td>	
			<div id="sample" class="dropdown" style="float:left; width:140px;">
				<div class="opt1" id="opt1"  style="width:140px;">
					<a href="javascript:jsVoid()" onclick="open_more_opt('more_opt');">
						<span>
							<?php
								if(isset($csset['CaseSetting']['type_id']) && $csset['CaseSetting']['type_id'])
								{

									foreach($typ as $k=>$v)
									{
										foreach($v as $key=>$value)
										{
											if($value['id'] == $csset['CaseSetting']['type_id'])
											{
												$im1= $this->Format->todo_typ_src($value['short_name'],$value['name']);
												echo "<img class='flag' src='".$im1."' alt='' />".$value['name']."";
											}
										}
									}
								}
								else
								{
							?>
									<img class="flag" src="<?php echo HTTP_IMAGES.'images/types/dev.png';?>" alt="type" style="padding-top:3px;"/>&nbsp;Development
								<?php
						 		} 
								?>
							</span>
						</a>
					</div>
<div class="more_opt" id="more_opt" style="width:350px;">
<ul>
<?php
foreach($typ as $k=>$v){
foreach($v as $key=>$value){
foreach($value as $key1=>$result){
if($key1=='name'&& $key1='short_name'){
$im1= $this->Format->todo_typ_src($value['short_name'],$value['name']);
echo "<li>
<a href='javascript:jsVoid()'>
<img class='flag' src='".$im1."' alt='' />
<span class='value'>".$value['id']."
</span>".$value['name']."
</a>
</li>";
}
}
}
}
?>
</ul>
</div>
</div>
</td>
</tr>
<tr height="40px">
<input type="hidden" id="project_uniqid" value="<?php echo $res['0']['projects']['uniq_id'];?>">
<td align="right" width="35%" style="padding-right:25px;" class="case_fieldprof">Due Date: </td>
<td>
<div class="dropdown" style="float:left;margin-left:0px;z-index: 9999999;">
<div class="opt1" id="opt3">
<a href="javascript:jsVoid()" onclick="open_more_opt('more_opt3');"><span>
<?php
if($csset['CaseSetting']['due_date'] == 'today'){?>
Today<span class="value">today</span>	
<?php
}else if($csset['CaseSetting']['due_date'] == 'nextmonday'){ ?>

Next Monday<span class="value">nextmonday</span>
<?php 
}else if($csset['CaseSetting']['due_date'] == 'thisfriday'){ ?>
This Friday<span class="value">thisfriday</span>
<?php 
}else if($csset['CaseSetting']['due_date'] == 'tomorrow'){ ?>
Tomorrow<span class="value">tomorrow</span>
<?php  
}else{ ?>
No Due Date<span class="value">No Due Date</span>
<?php  } ?> 
</span></a>
</div>

<div class="more_opt" id="more_opt3">
<ul>
<li><a href="javascript:jsVoid()">Today<span class="value">today</span></a></li> 	
<li><a href="javascript:jsVoid()">Next Monday<span class="value">nextmonday</span></a></li> 
<li><a href="javascript:jsVoid()">Tomorrow<span class="value">tomorrow</span></a></li>
<li><a href="javascript:jsVoid()">This Friday<span class="value">thisfriday</span></a></li> 
<li><a href="javascript:jsVoid()">No Due Date<span class="value">no due date</span></a></li> 
</ul>
</div>
</div>
</td>
</tr>
<tr height="40px">
<td class="case_fieldprof" align="right" width="35%" style="padding-right:25px" class="case_fieldprof">Priority:
</td>
<td>
<span id="hd2">
<div class="dropdown" style="float:left;">
<div class="opt1" id="opt2">
<a href="javascript:jsVoid()" onclick="open_more_opt('more_opt2');"><span>
<?php
if($csset['CaseSetting']['priority'] == '2' ){?>
<font style='color:#AD9227;font-size:13px;'>Low</font>
<span class="value">LOW</span>

<?php
}else if($csset['CaseSetting']['priority'] == '1'){ ?>

<font style='color:#28AF51;font-size:13px;'>MEDIUM</font><span class="value">medium</span> 
<?php 
}else if($csset['CaseSetting']['priority'] == '0'){ ?>
<font style='color:#AE432E;font-size:13px;'>HIGH</font><span class="value">high</span> 
<?php  
}else { ?>
<span ><font style='color:#28AF51;font-size:13px;'>&nbsp;MEDIUM</font></span>

<?php  } ?> 
<span></a>
</div>
<div class="more_opt" id="more_opt2">
<ul style="width:140px">
<li>
<a href="javascript:jsVoid()"><font style='color:#AD9227;font-size:13px;'>&nbsp;LOW</font>
<span class="value">2</span>
</a>
</li>
<li>
<a href="javascript:jsVoid()"><font style='color:#28AF51;font-size:13px;'>&nbsp;MEDIUM</font>
<span class="value">1</span>
</a>
</li>
<li>
<a href="javascript:jsVoid()"><font style='color:#AE432E;font-size:13px;'>&nbsp;HIGH</font>
<span class="value">0</span>
</a>
</li>
</ul>
</div>
</div>
</span>
</td>			
</tr>
<tr height="40px">
<td class="case_fieldprof" valign="top" width="35%" align="right" style="padding-right:25px">
Email Notification:&nbsp;<input id="hh" type="checkbox" checked=checked onclick="checkallusr('chkcnt')" value="all" style="cursor:pointer;"> 
</td>
<td>
<table>
<tr>
<?php
$emailarr=explode(",",$csset['CaseSetting']['email']);	
$i=0;
$r=0;
$j=0;
$k=0;
foreach($res as $rs){ 
$j = $r%3;
if($j == 0)
{
?>
<tr>
<?php
}
?>

<input type="checkbox" name="email[]" id="chk<?php echo $i;?>"  value="<?php echo $rs['users']['id']; ?>"
<?php
if(in_array($rs['users']['id'],$emailarr)){
echo "checked=checked";
}
?>
style="cursor:pointer;"> 
<td>
<?php $i++; echo $rs['users']['name']; ?> 
&nbsp;&nbsp;
</td>

<?php
$r = $r+1;
$k = $r%3;
if($k == 0)
{
?>
</tr>
<?php
}
} ?>
</tr>
</table>
</td>
<div>
<input type="hidden" id="chkcnt" value="<?php echo $i;?>">
</div>			  
</tr>
</table>

<script>
	function checkallusr(id){
		cnt=document.getElementById(id).value;
			if(document.getElementById("hh").checked==true){
				for(var i=0;i<cnt;i++){
					document.getElementById('chk'+i).checked=true;
				}
			}
			if(document.getElementById("hh").checked==false){
				for(var i=0;i<cnt;i++){
					document.getElementById('chk'+i).checked=false;
				}
			}
	}


function hide_pri(val) {
		document.getElementById("CS_title").value = val;
	}
	$(document).ready(function() {
		
		$(".more_opt ul li a").click(function() {
			var text = $(this).html();
			var path=$(this).parent("li").parent("ul").parent("div").prev("div").attr("id");
			$("#"+path).children("a").children("span").html(text);
			
			if(path =="opt3")
			{       //alert(getSelectedValue("opt3"));
				var hidden_val=$("#" + path).find("a span.value").html();
				
				$("#due_date").val(hidden_val);
				
			}
			else if(path =="opt2"){
				//alert(getSelectedValue("opt2"));
				$("#priority").val(getSelectedValue("opt2"));
				
			}
			else
			{      //alert(getSelectedValue("opt1"));
				$("#type_id").val(getSelectedValue("opt1"));
				
				$("#hd1").show();
				$("#hd2").show();
				
				if($("#type_id").val() == 10){
					$("#hd1").hide();
					$("#hd2").hide();
					$("#CS_title").val('<?php echo $titleValue; ?>');
					document.getElementById("CS_title").style.color='#000';
				}
				else if($("#type_id").val() != 10 && $("#CS_title").val() == '<?php echo $titleValue; ?>')
				{
					document.getElementById("CS_title").value ="";
				}
			}
			$("#"+path).next("div").children("ul").hide();
		});
		
		function getSelectedValue(id) {
            return $("#" + id).find("a span.value").html();
	    }
		
		$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if (! $clicked.parents().hasClass("dropdown"))
				$(".dropdown .more_opt ul").hide();
		});
	});
	function open_more_opt(more_opt){
		$("#"+more_opt).children("ul").toggle();
	}

	
</script>
<input type="hidden" id="due_date" value="<?php
												if($csset['CaseSetting']['due_date']==""){
													echo "No Due Date";
												}else{ echo $csset['CaseSetting']['due_date'];}?>">

<input type="hidden" id="priority" value="<?php
												if($csset['CaseSetting']['priority']==""){
													echo "1";
												}else{ 
												 echo $csset['CaseSetting']['priority']; }?>">
<input type="hidden" id="type_id" value="<?php 
												if($csset['CaseSetting']['type_id']==""){
													echo "2";
												}else{ 

													echo $csset['CaseSetting']['type_id']; }?>">

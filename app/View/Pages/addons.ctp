<style>
.slide_rht_con{position:relative}
.breadcrumb_div{display:none}
.mn_pg_dv{height:100%;width:100%;padding-top:10px;}
.btn_buy_learn{position:absolute;width:100%;padding:8px 0;text-align:center;left:0;right:0;margin:auto;top:0px;border-bottom:1px solid #d9d9f1;}
.btn_buy_learn a{text-decoration:none;display:inline-block;height:30px;line-height:30px;font-size:15px;vertical-align:middle;width:auto;color: #fff;padding:0px 30px;border-radius:30px;}
@-webkit-keyframes glowing_1 {
  0% { background-color: #004A7F; -webkit-box-shadow: 0 0 3px #004A7F; }
  50% { background-color: #0094FF; -webkit-box-shadow: 0 0 10px #0094FF; }
  100% { background-color: #004A7F; -webkit-box-shadow: 0 0 3px #004A7F; }
}

@-moz-keyframes glowing_1 {
  0% { background-color: #004A7F; -moz-box-shadow: 0 0 3px #004A7F; }
  50% { background-color: #0094FF; -moz-box-shadow: 0 0 10px #0094FF; }
  100% { background-color: #004A7F; -moz-box-shadow: 0 0 3px #004A7F; }
}

@-o-keyframes glowing_1 {
  0% { background-color: #004A7F; box-shadow: 0 0 3px #004A7F; }
  50% { background-color: #0094FF; box-shadow: 0 0 10px #0094FF; }
  100% { background-color: #004A7F; box-shadow: 0 0 3px #004A7F; }
}

@keyframes glowing_1 {
  0% { background-color: #004A7F; box-shadow: 0 0 3px #004A7F; }
  50% { background-color: #0094FF; box-shadow: 0 0 10px #0094FF; }
  100% { background-color: #004A7F; box-shadow: 0 0 3px #004A7F; }
}

.btn_buy_learn a.learn_nw_a_btn{
  -webkit-animation: glowing_1 1500ms infinite;
  -moz-animation: glowing_1 1500ms infinite;
  -o-animation: glowing_1 1500ms infinite;
  animation: glowing_1 1500ms infinite;
}
 @-webkit-keyframes glowing_2 {
  0% { background-color: #f7972b; -webkit-box-shadow: 0 0 3px #f3850a; }
  50% { background-color: #d8770a; -webkit-box-shadow: 0 0 10px #f3850a; }
  100% { background-color: #f7972b; -webkit-box-shadow: 0 0 3px #f3850a; }
}

@-moz-keyframes glowing_2 {
  0% { background-color: #f7972b; -moz-box-shadow: 0 0 3px #f3850a; }
  50% { background-color: #d8770a; -moz-box-shadow: 0 0 10px #f3850a; }
  100% { background-color: #f7972b; -moz-box-shadow: 0 0 3px #f3850a; }
}

@-o-keyframes glowing_2 {
  0% { background-color: #f7972b; box-shadow: 0 0 3px #f3850a; }
  50% { background-color: #d8770a; box-shadow: 0 0 10px #f3850a; }
  100% { background-color: #f7972b; box-shadow: 0 0 3px #f3850a; }
}

@keyframes glowing_2 {
  0% { background-color: #f7972b; box-shadow: 0 0 3px #f3850a; }
  50% { background-color: #d8770a; box-shadow: 0 0 10px #f3850a; }
  100% { background-color: #f7972b; box-shadow: 0 0 3px #f3850a; }
}
.btn_buy_learn a.buy_nw_a_btn {
  -webkit-animation: glowing_2 1000ms infinite;
  -moz-animation: glowing_2 1000ms infinite;
  -o-animation: glowing_2 1000ms infinite;
  animation: glowing_2 1000ms infinite;
  background-color:#f7972b;
  margin-right:15px
} 
</style>

<?php

$image_name = $addon_name.".jpg";
$addon_link_learn = "https://calendly.com/priyankagarwal/15min";
//echo $addon_name;
if($addon_name == 'executive_dashboard'){
	$addon_link = "https://www.orangescrum.org/addon-details/executivedashboard";
} else if($addon_name == 'user_role_management'){
	$addon_link = "https://www.orangescrum.org/addon-details/userrolemanagement";
}else if($addon_name == 'gantt_chart'){
	$addon_link = "https://www.orangescrum.org/addon-details/ganttchart";
}else if($addon_name == 'time_log_gold'){
	$addon_link = "https://www.orangescrum.org/addon-details/timeloggold";
}else if($addon_name == 'project_template'){
	$addon_link = "https://www.orangescrum.org/addon-details/projecttemplate";
}else if($addon_name == 'time_log'){
	$addon_link = "https://www.orangescrum.org/addon-details/timelog";
}else if($addon_name == 'task_status_group'){
	$addon_link = "https://www.orangescrum.org/addon-details/taskstatus";
}else if($addon_name == 'invoice'){
	$addon_link = "https://www.orangescrum.org/addon-details/invoice";
}else if($addon_name == 'document'){
	$addon_link = "https://www.orangescrum.org/addon-details/documentmanagement";
}else if($addon_name == 'bug_issue_tracking'){
	$addon_link = "https://www.orangescrum.org/addon-details/bugissuetracker";
}else if($addon_name == 'chat'){
	$addon_link = "https://www.orangescrum.org/addon-details/chat";
}else if($addon_name == 'expense_management'){
	$addon_link = "https://www.orangescrum.org/addon-details/expensemanagement";
}else if($addon_name == 'wiki'){
	$addon_link = "https://www.orangescrum.org/addon-details/wiki";
}else if($addon_name == 'recurring_task'){
	$addon_link = "https://www.orangescrum.org/addon-details/recurringtask";
}else if($addon_name == 'client_management'){
	$addon_link = "https://www.orangescrum.org/addon-details/clientmanagement";
}else if($addon_name == 'mobile_api'){
	$addon_link = "https://www.orangescrum.org/addon-details/mobileapi ";
}else if($addon_name == 'api'){
	$addon_link = "https://www.orangescrum.org/addon-details/api";
}else if($addon_name == 'multilingual'){
	$addon_link = "https://www.orangescrum.org/addon-details/multilingual";
}
?>
<div class="mn_pg_dv">
<img src=<?php echo HTTP_ROOT."img/pages/".$image_name; ?> style="width:100%">
	<div class="btn_buy_learn">
	<a href="<?php echo $addon_link;?>" target ="_blank" class="buy_nw_a_btn">Learn More </a>
	<a href="<?php echo $addon_link_learn;?>" class="learn_nw_a_btn" target ="_blank"> Schedule A Demo </a>
	</div>
</div>

<script>
    $(document).ready(function(){
       $('.side-nav').scroll(); 
    });
    </script>

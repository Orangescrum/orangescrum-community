$(document).ready(function() {
	$(".dropdown-menu").click(function(event) {
		event.stopPropagation();
    });
	
    $(".dropdown-menu").click(function(event) {
		event.stopPropagation();
    });
	$(document).on('click','[id^="titlehtml"]', function(){
		var task_data = $(this).attr('data-task').split('|');
		var caseUniqId = task_data[0];
		/*if(PAGE_NAME=='milestonelist'){
			window.location.href =HTTP_ROOT+'#details/'+caseUniqId+"/milestone";
		}else{*/
			window.location.hash = 'details/'+caseUniqId;
		//}
		
	});
	
	$(document).on('click','[id^="act_reply"]', function(){
		var task_data = $(this).attr('data-task').split('|');
		var caseUniqId = task_data[0];
		scrollToRep = caseUniqId;
		/*if(PAGE_NAME=='milestonelist'){
			window.location.href =HTTP_ROOT+'#details/'+caseUniqId+"/milestone";
		}else{*/
			window.location.hash = 'details/'+caseUniqId;
		//}
		
	});
	
	$(document).on('click', '.task_detail_back', function(){
		var params = parseUrlHash(urlHash);
		if($('#caseMenuFilters').val()=='kanban'){
			window.location.hash = 'kanban';
		}else if($('#caseMenuFilters').val()=='activities'){
			window.location.hash = 'activities';
		}else if($('#caseMenuFilters').val()=='milestonelist'){
			window.location.hash = 'milestonelist';
		}else if($('#caseMenuFilters').val()=='calendar'){
			//window.location.hash = 'calendar';
			easycase.routerHideShow('calendar');
		}/*else if (params[2] && (params[2]=='milestone')) {
			window.location.href=HTTP_ROOT+'milestone';
		}*/else{
			window.location.hash = 'tasks';
		}
		
	});
	$(document).on('click', '.milestonekb_detail_head', function(){
		refreshKanbanTask=1;
		window.location.hash = $('#refMilestone').val();		
	});
	$("img.lazy").lazyload({ placeholder : "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" });
});

function setCustomStatus(arg, customid) {
	remember_filters('reset','all');
	$('#dropdown_menu_all_filters').hide();
	$('.dropdown_status').hide();
	$(".case-filter-menu").css({"position":'fixed'});
	//document.getElementById('casePage').value = "1"; // Pagination
	casePage = 1;
	document.getElementById('case_srch').value = "";
	document.getElementById('caseDateFil').value = "";
	document.getElementById('casedueDateFil').value = "";
	
    $.post(HTTP_ROOT + "easycases/setCustomStatus", {'customfilter': customid}, function(data) {
	if (data) {
	    $("#caseStatus").val(data.status);
	    $("#priFil").val(data.priority);
	    $("#caseTypes").val(data.type);
	    $("#caseMember").val(data.member);
	    $("#caseAssignTo").val(data.assignto);
	    $("#caseDate").val(data.date);
	    $("#caseDueDate").val(data.duedate);
	    
	    $("#widgethideshow").show();
	    easycase.showTaskLists(arg);
	    $("#customFil").addClass('open');
	    $(".menu-files").removeClass('active');
	    $(".menu-cases").removeClass('active');
	    $(".allmenutab").removeClass('active');
	    $(".more_menu_li").removeClass('active');
	    if($('.customFilter').html() == '') {
		openAjaxCustomFilter('auto',customid);
	    } else {
		$('.customlink').removeClass('active');
		$("#lnkcustomFilterRow_"+customid).addClass('active');
		$("#deleteImg_"+customid).show();
	    }
	}
    },'json');
}

function loadCaseMenu(strURL, params, ispageload){
	$.post(strURL, params, function(data){
		if(data) {
			if(parseInt(data.caseNew) >= 0) {
			    $('#taskCnt').html(data.caseNew).show();
				$('#taskCnt').attr('title',data.caseNew+' Tasks');
			    $('#tskTabAllCnt').html(" ("+data.caseNew+")");
			}
			
			if(parseInt(data.caseFiles) >= 0) {
			    $('#fileCnt').html(data.caseFiles).show();
				$('#fileCnt').attr('title',data.caseFiles+' File');
			}
			
			if(parseInt(data.assignToMe) >= 0)
			    $('#tskTabMyCnt').html(" ("+data.assignToMe+")");
			
			if(parseInt(data.delegateTo) >= 0)
			    $('#tskTabDegCnt').html(" ("+data.delegateTo+")");
			
			if(parseInt(data.highPri) >= 0)
			    $('#tskTabHPriCnt').html(" ("+data.highPri+")");
			
			if(parseInt(data.overdue) >= 0)
			    $('#tskTabOverdueCnt').html(" ("+data.overdue+")");
			
			/*if(parseInt(data.total_milestone) > 0){
			    $('#mlstn_fltr').show();
			} else {
			    $('#mlstn_fltr').hide();
			}*/
		}
	});
}

/************ Case Listing ************/
function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,0])[1]
    );
}

function resetBreadcrumbFilters(strURL, caseStatus1, priFil1, caseTypes1, caseMember1, caseAssignTo1, resetall1, case_date,case_due_date, casePage, caseSearch, clearCaseSearch, caseMenuFilters, milestoneIds) {
	var filterid = document.getElementById('customFIlterId').value;
    $.post(strURL + "ajax_common_breadcrumb", {"caseMember": caseMember1, "caseAssignTo": caseAssignTo1, "resetall": resetall1, "caseTypes": caseTypes1, "caseStatus": caseStatus1, "casedate": case_date,'caseduedate':case_due_date, "priFil": priFil1, "casePage": casePage, 'caseSearch': caseSearch, 'clearCaseSearch': clearCaseSearch, 'caseMenuFilters': caseMenuFilters, 'milestoneIds': milestoneIds}, function(data) {
	if (data) {
//		if(data.val){
//			$('#filter_div_menu').show();
//	    }else{
//			$('#filter_div_menu').hide();
//	    }
		if(data.val){
			$('#filtered_items').show();
			$('#savereset_filter').show();
		}else{
			$('#filtered_items').hide();
			$('#savereset_filter').hide();
		}
		$('#filtered_items').html('');
	    if(data.case_assignto != 'All') {
			$('#filtered_items').append(data.case_assignto);
	    }
	    if(data.duedate != 'Any Time') {
			$('#filtered_items').append(data.duedate);
	    }
	    
	    if(data.case_member != 'All') {
			$('#filtered_items').append(data.case_member);
	    }
	    	    
	    if(data.case_types != 'All') {
			$('#filtered_items').append(data.case_types);
	    }
	    	    
	    if(data.case_status != 'All') {
			$('#filtered_items').append(data.case_status);
		}

		if(data.date != 'Any Time') {
			$('#filtered_items').append(data.date);
	    }

	    if(data.pri != 'All') {
			$('#filtered_items').append(data.pri);
	    }
		if(data.tasksortby){
			$('#sortby_items').html(data.tasksortby);
		}else{
			$('#sortby_items').html('');
		}
		if(data.taskgroupby){
			$('#groupby_items').html(data.taskgroupby);
		}else{
			$('#groupby_items').html('');
		}
	    $('#not_assign').html(data.case_assignto);
	    $('#not_mem').html(data.case_member);
	    $('#not_type').html(data.case_types);
	    $('#not_sts').html(data.case_status);
	    $('#not_date').html(data.date);
	    $('#not_pri').html(data.pri);
	    if (data.search_case) {
			$('#filtered_items').append(data.case_search);
			//$('#not_srch').html(", Search: <span>" + data.search_case + "</span>");
	    } else {
			$('#not_srch').html(" ");
	    }
	    if (data.page_case) {
			$('#filtered_items').append(data.case_page);
			//$('#not_page').html(", Page: <span>" + data.page_case + "</span>");
	    }
		
	    if (data.val) {
		$('#reset_btn').show();
		if (filterid || ($('#filtered_items .filter_opn').length == 1 && casePage > 1)) {
		    //$('#customFIlterId').val('');
		    $('#savefilter_btn').hide();
		    $('#or').hide();
		} else {
		    $('#savefilter_btn').show();
		    $('#or').show();
		}
		if (data.case_page) {
		    $('#case_page').html(data.case_page);
		}else {
		    $('#case_page').html('');
		}
		if (data.case_search) {
		    $('#search_txt_spn').html(data.case_search);
		    if ($.trim($("#hid_srch_text").val()) !== '') {
			$("#closesrch").css('top', '0px');
			$("#hid_srch_text").val('');
		    } else {
			$("#closesrch").css('top', '-7px');
		    }
		    $("#closesrch").css('position', 'relative');
		    $('#closesrch').show();
		}
		else {
		    $('#search_txt_spn').html('');
		}
		//blink('.reset_indication');
	    } else {
			$('#reset_btn').hide();
			$('#or').hide();
			$('#savefilter_btn').hide();
			$('#search_txt_spn').html('');
			$('#case_page').html('');
	    }

	    //if (data.mlstn && caseMenuFilters == 'milestone') {
			if(data.mlstn !== 'All') {
				$('#filtered_items').append(data.mlstn);
			}
		//$('#not_mlstn').html("Milestone: (<span>" + data.mlstn + "</span>), ");
	    /*}
	    else {
		$('#not_mlstn').html('');
	    }*/
		$('[rel=tooltip]').tipsy({gravity:'s', fade:true});
	}
    }, 'json');
}


function checkMilestones(id, current) {
    var totmstones = document.getElementById('totmstones').value;
    var milestns = "";
    var msid = "";
    if (current) {
	/*if (document.getElementById(current).checked == true) {
	    document.getElementById(current).checked = false;
	}
	else {
	    document.getElementById(current).checked = true;
	}*/
	document.getElementById(current).checked = true;
    }
    if (id == "all") {
	for (var i = 1; i <= totmstones; i++) {
	    var msid = 'mstones' + i;
	    document.getElementById(msid).checked = false;
	    var curli = 'curli' + i;
	    document.getElementById(curli).style.background = '#FFF';
	    var chkid = document.getElementById(msid).value;
	    milestns = milestns + "-" + chkid;
	}
	milestns = 'all';
	document.getElementById('allmstones').checked = true;
    }
    else {
	var chk = 0;
	for (var i = 1; i <= totmstones; i++) {
	    var msid = 'mstones' + i;
	    var curli = 'curli' + i;
	    if (id == "all") {
		document.getElementById(msid).checked = false;
	    }
	    else {
		if (document.getElementById(msid).checked == true) {
		    document.getElementById(curli).style.background = '#FFF';
		    var chkid = document.getElementById(msid).value;
		    milestns = milestns + "-" + chkid;
		    chk++;
		}
		else {
		    document.getElementById(curli).style.background = '#F2F2F2';
		}
	    }
	}
	if (chk == totmstones) {
	    document.getElementById('allmstones').checked = true;
	    for (var i = 1; i <= totmstones; i++) {
		var curli = 'curli' + i;
		document.getElementById(curli).style.background = '#FFF';
		var msid = 'mstones' + i;
		document.getElementById(msid).checked = false;
		milestns = 'all';
	    }
	}
	else {
	    document.getElementById('allmstones').checked = false;
	}
    }
    document.getElementById('milestoneIds').value = milestns;
    //$('#casePage').val(1);
	casePage = 1;
    remember_filters('MILESTONES',milestns);
    ajaxCaseView('case_project');
}
function filterRequest(type){
	if(_filterInterval) {
		clearInterval(_filterInterval);
	}
	_filterInterval = setTimeout(function(){
		$('#customFIlterId').val('');
		window.location.hash = 'tasks';
		easycase.refreshTaskList();
	},1000);
}
function checkboxDate(x, typ) {
    if (x && (!$('#date_' + x).is(':checked'))) {
	checkboxDate('', '');
	return false;
    }
    $('.cbox_date').removeAttr('checked');
    if (x) {
	$('#date_' + x).attr('checked', 'checked');
    } else {
	$('#date_any').attr('checked', 'checked');
    }

    $('#frm').val("");
    $('#to').val("");
    $('#custom_date').hide();
    $('#caseDateFil').val(x);
    //$('#casePage').val(1);
	casePage = 1;
    remember_filters('DATE',x);
}
function checkboxdueDate(x, typ) {
    $('#duedate_'+x).attr('checked','checked');
	if (x) {
		$('#duedate_' +x).attr('checked', 'checked');
    }else {
		$('#duedate_any').attr('checked', 'checked');
    }
	if(x=='any'){
		x='';
	}
    $('#duefrm').val("");
    $('#dueto').val("");
    $('#custom_duedate').hide();
    $('#casedueDateFil').val(x);
    //$('#casePage').val(1);
	casePage = 1;
    remember_filters('DUE_DATE',x);
}

function checkboxcustom(id ,cid,ctype) {
	if(_filterInterval) {
		clearInterval(_filterInterval);
	}
	
	if(ctype){
		$('#'+cid).attr('checked', 'checked');
	}else{
		if(!$('#'+cid).is(":checked")){
			$('.cbox_date').removeAttr('checked');
			$('#date_any').prop('checked',true);
		}else{
			$('.cbox_date').removeAttr('checked');
			$('#'+cid).prop('checked',true);
		}
	}
    if ($('#'+cid).is(':checked')) {
		$('#'+ id).show();
		$('#'+cid).prop('checked',true);
    } else {
		$('#' + id).hide();
		$('#'+ctype+'frm').val("");
		$('#'+ctype+'to').val("");
		checkboxDate('', '');
		if ((id != 'custom_date') || (id != 'custom_duedate') ) {
			filterRequest('time');
		}
    }

}
	
function checkBox(id) {
    if (document.getElementById(id).checked != true) {
	document.getElementById(id).checked = true;
    } else {
	document.getElementById(id).checked = false;
    }
}

function checkboxStatus(id, typ) {
    var x = "";
    if (id == 'status_all') {
	document.getElementById(id).checked = true;
	document.getElementById('status_new').checked = false;
	document.getElementById('status_open').checked = false;
	document.getElementById('status_close').checked = false;
	//document.getElementById('status_start').checked = false;
	document.getElementById('status_resolve').checked = false;
	document.getElementById('status_file').checked = false;
	document.getElementById('status_upd').checked = false;
	var x = "alll";
    }
    else {
	document.getElementById('status_all').checked = false;
	if (typ == "check")

	{
	    if (document.getElementById(id).checked == true) {
		document.getElementById(id).checked = true;
	    }
	    else
	    {
		document.getElementById(id).checked = false;
	    }
	}
	else
	{
	    if (document.getElementById(id).checked == false) {
		document.getElementById(id).checked = true;
	    }
	    else
	    {
		document.getElementById(id).checked = false;
	    }
	}
    }
    if (document.getElementById('status_new').checked == true) {
	x = 1 + "-";
    }
    if (document.getElementById('status_open').checked == true) {
	x = 2 + "-" + x;
    }
    if (document.getElementById('status_close').checked == true) {
	x = 3 + "-" + x;
    }
    /*if(document.getElementById('status_start').checked == true) {
     x = 4+"-"+x;
     }*/
    if (document.getElementById('status_resolve').checked == true) {
	x = 5 + "-" + x;
    }
    if (document.getElementById('status_file').checked == true) {
	x = "attch-" + x;
    }
    if (document.getElementById('status_upd').checked == true) {
	x = "upd-" + x;
    }
    if (x == "") {
	document.getElementById('status_all').checked = true;
	x = "alll";
    }
    if (x != "all") {
	var status = x.substring(0, x.length - 1);
    }
    else {
	var status = x;
    }
    document.getElementById('caseStatus').value = status;
    //$('#casePage').val(1);
	casePage = 1;
    remember_filters('STATUS',status);
}

function moreLeftNav(more, hide, tot, id) {
    for (var i = 1; i <= tot; i++) {
		var spanid = id + i;
		document.getElementById(spanid).style.display = 'block';
    }
    document.getElementById(more).style.display = 'none';
    document.getElementById(hide).style.display = 'block';
	//$(".case-filter-menu").css({"position":'absolute'});
}

function hideLeftNav(more, hide, tot, id) {
    for (var i = 1; i <= tot; i++) {
	var spanid = id + i;
	document.getElementById(spanid).style.display = 'none';
    }
    document.getElementById(hide).style.display = 'none';
    document.getElementById(more).style.display = 'block';
	//$(".case-filter-menu").css({"position":'fixed'});

}

function checkboxTypes(id, typ) {
    var x = "";
    var totid = document.getElementById('totType').value;
    if (id == 'types_all') {
	document.getElementById(id).checked = true;
	for (var i = 1; i <= totid; i++)
	{
	    var checkboxid = "types_" + i;
	    document.getElementById(checkboxid).checked = false;
	}
	var x = "alll";
    }
    else {
	document.getElementById('types_all').checked = false;
	if (typ == "check")
	{
	    if (document.getElementById(id).checked == true) {
		document.getElementById(id).checked = true;
	    }
	    else
	    {
		document.getElementById(id).checked = false;
	    }
	}
	else
	{
	    if (document.getElementById(id).checked == false) {
		document.getElementById(id).checked = true;
	    }
	    else
	    {
		document.getElementById(id).checked = false;
	    }
	}
    }
    
    $('.cst_type_cls').each(function() {
	var dt_id = $(this).attr('data-id');
	if($("#"+this.id).is(':checked')){
	    var typeid = "typeids_" + dt_id;
	    var typevalue = $("#"+typeid).val();
	    x = typevalue + "-" + x;
	}
    });
    
    if (x === "") {
		document.getElementById('types_all').checked = true;
		var types = "all";
    } else {
		var types = x.substring(0, x.length - 1);
    }
    document.getElementById('caseTypes').value = types;
    //$('#casePage').val(1);
	casePage = 1;
    remember_filters('CS_TYPES',types);
}

function checkboxPriority(id, typ) {
    var x = "";
    if (id == 'priority_all') {
	document.getElementById(id).checked = true;
	document.getElementById('priority_High').checked = false;
	document.getElementById('priority_Medium').checked = false;
	document.getElementById('priority_Low').checked = false;
	var x = "alll";
    }
    else {
	document.getElementById('priority_all').checked = false;
	if (typ == "check")
	{
	    if (document.getElementById(id).checked == true) {
		document.getElementById(id).checked = true;
	    }
	    else {
		document.getElementById(id).checked = false;
	    }
	}
	else
	{
	    if (document.getElementById(id).checked == false) {
		document.getElementById(id).checked = true;
	    }
	    else {
		document.getElementById(id).checked = false;
	    }
	}
    }
    if (document.getElementById('priority_High').checked == true) {
	x = "High-";
    }
    if (document.getElementById('priority_Medium').checked == true) {
	x = "Medium-" + x;
    }
    if (document.getElementById('priority_Low').checked == true) {
	x = "Low-" + x;
    }
    if (x == "") {
	document.getElementById('priority_all').checked = true;
	x = "alll";
    }
    if (x != "all") {
	var priority = x.substring(0, x.length - 1);
    }
    else {
	var priority = x;
    }
    document.getElementById('priFil').value = priority;
    //$('#casePage').val(1);
	casePage = 1;
    remember_filters('PRIORITY',priority);
}

function checkboxMems(id, typ) {
    var x = "";
    var totid = document.getElementById('totMemId').value;
    if (id == 'types_all') {
    }
    else {
	document.getElementById('types_all').checked = false;
	if (typ == "check")
	{
	    if (document.getElementById(id).checked == true) {
		document.getElementById(id).checked = true;
	    }
	    else
	    {
		document.getElementById(id).checked = false;
	    }
	}
	else
	{
	    if (document.getElementById(id).checked == false) {
		document.getElementById(id).checked = true;
	    }
	    else
	    {
		document.getElementById(id).checked = false;
	    }
	}
    }
    for (var j = 1; j <= totid; j++) {
	var checkboxid = "mems_" + j;
	if (document.getElementById(checkboxid).checked == true)
	{
	    var typeid = "memids_" + j;
	    var typevalue = document.getElementById(typeid).value;
	    x = typevalue + "-" + x;
	}
    }
    if (x == "") {
	var mems = "all";
    }
    else {
	var mems = x.substring(0, x.length - 1);
    }
    document.getElementById('caseMember').value = mems;
    //$('#casePage').val(1);
	casePage = 1;
    remember_filters('MEMBERS',mems);
}
function checkboxAsns(id, typ) {
    var x = "";
    var totid = document.getElementById('totAsnId').value;
    if (id == 'types_all') {
    }
    else {
	document.getElementById('assignTo_all').checked = false;
	if (typ == "check")
	{
	    if (document.getElementById(id).checked == true) {
		document.getElementById(id).checked = true;
	    }
	    else
	    {
		document.getElementById(id).checked = false;
	    }
	}
	else
	{
	    if (document.getElementById(id).checked == false) {
		document.getElementById(id).checked = true;
	    }
	    else
	    {
		document.getElementById(id).checked = false;
	    }
	}
    }
    for (var j = 1; j <= totid; j++) {
	var checkboxid = "Asns_" + j;
	if (document.getElementById(checkboxid).checked == true)
	{
	    var typeid = "Asnids_" + j;
	    var typevalue = document.getElementById(typeid).value;
	    x = typevalue + "-" + x;
	}
    }
    if (x == "") {
	var Asns = "all";
    }
    else {
	var Asns = x.substring(0, x.length - 1);
    }
    document.getElementById('caseAssignTo').value = Asns;
    //$('#casePage').val(1);
	casePage = 1;
    remember_filters('ASSIGNTO',Asns);
}


function checkboxrange() {
    var start_date = document.getElementById('frm');
    var end_date = document.getElementById('to');
    var errMsg;
    var done = 1;
    if (start_date.value.trim() == "") {
	errMsg = "From Date cannot be left blank!";
	start_date.focus();
	done = 5;

    }
    else if (end_date.value.trim() == "") {
	errMsg = "To Date cannot be left blank!";
	end_date.focus();
	done = 5;

    }
    else if (Date.parse(start_date.value) > Date.parse(end_date.value)) {
	errMsg = "From Date cannot exceed To Date!";
	end_date.focus();
	done = 0;
    }
    if (done == 0) {
	var op = 100;
	showTopErrSucc('error', errMsg);
	return false;
    } else if (done == 5) {
	return false;
    }
    else {
	var from = document.getElementById('frm').value;
	var to = document.getElementById('to').value;
	document.getElementById('date_any').checked = false;
	document.getElementById('date_one').checked = false;
	document.getElementById('date_24').checked = false;
	document.getElementById('date_week').checked = false;
	document.getElementById('date_month').checked = false;
	document.getElementById('date_year').checked = false;
	var x = from + ":" + to;
	document.getElementById('caseDateFil').value = x;
	remember_filters('DATE',encodeURIComponent(x));
	filterRequest('time');
    }
}
function searchduedate(){
	var fduedate = $.trim($('#duefrm').val());
	var tduedate = $.trim($('#dueto').val());
	if(fduedate==''){
		showTopErrSucc('error', 'From Date cannot be left blank!');
		$('#duefrm').focus();return false;
	}else if(tduedate==''){
		showTopErrSucc('error', 'To Date cannot be left blank!');
		$('#dueto').focus();return false;
	}else if(Date.parse(fduedate) > Date.parse(tduedate)) {
		showTopErrSucc('error', 'From Date cannot exceed To Date!');
		$('#duefrm').focus();return false;
	}else{
		var x = fduedate + ":" + tduedate;
		$('#casedueDateFil').val(x);
		remember_filters('DUE_DATE',encodeURIComponent(x));
		filterRequest('duedate');
	}
}

function resetAllFilters(type) {
	//$('#filter_div_menu').fadeOut('slow');
	$('#filtered_items').fadeOut('slow');
	$('#savereset_filter').fadeOut('slow');
	$('[rel=tooltip]').tipsy({gravity:'s', fade:false});
    var requiredUrl = HTTP_ROOT;
    var n = requiredUrl.indexOf("filters=cases");
    if ($('#search_txt_spn').text()) {
	$('#clearCaseSearch').val(1);
    }
    try {
	document.getElementById('caseStatus').value = "all"; // Filter by Status(legend)
	document.getElementById('priFil').value = "all"; // Filter by Priority
	document.getElementById('caseTypes').value = "all"; // Filter by case Types
	document.getElementById('caseMember').value = "all";  // Filter by Member
	document.getElementById('caseAssignTo').value = "all";  // Filter by AssignTo
	document.getElementById('milestoneIds').value = "all";

	//document.getElementById('casePage').value = "1"; // Pagination
	casePage = 1;
	$('#case_search, #caseSearch').val("");
	document.getElementById('case_srch').value = "";
	document.getElementById('caseDateFil').value = "";
	document.getElementById('casedueDateFil').value = "";
	document.getElementById('status_all').checked = true;
	document.getElementById('status_new').checked = false;
	document.getElementById('status_open').checked = false;
	document.getElementById('status_close').checked = false;
	//document.getElementById('status_start').checked = false;
	document.getElementById('status_resolve').checked = false;
	document.getElementById('status_file').checked = false;
	document.getElementById('status_upd').checked = false;

	var totid = document.getElementById('totMemId').value;
	for (var i = 1; i <= totid; i++) {
	    var checkboxid = "mems_" + i;
	    document.getElementById(checkboxid).checked = false;
	}

	document.getElementById('priority_all').checked = true;
	document.getElementById('priority_High').checked = false;
	document.getElementById('priority_Medium').checked = false;
	document.getElementById('priority_Low').checked = false;

	var totid = document.getElementById('totType').value;
	for (var i = 1; i <= totid; i++) {
	    var checkboxid = "types_" + i;
	    document.getElementById(checkboxid).checked = false;
	}

    }
    catch (e) {
    }

    if (type == "all") {
	if (n != -1) {
	    remember_filters('reset','all');
		var hashtag = parseUrlHash(urlHash);
		if(hashtag[0]=='kanban'){
			easycase.showKanbanTaskList('kanban');
		}else{
			ajaxCaseView("case_project.php");
		}	
		$("#case_search").attr("placeholder", "Search");
		window.location = HTTP_ROOT + "dashboard";
	} else {
		remember_filters('reset','all');
		var hashtag = parseUrlHash(urlHash);
		if(hashtag[0]=='kanban'){
			easycase.showKanbanTaskList('kanban');
		}else{
			$('#customFIlterId').val('');
			window.location.hash = 'tasks';
			easycase.refreshTaskList();
		}	
	}
    }
    else if (type == "filters") {
	if (n != -1) {
	    remember_filters('reset','filters');
		var hashtag = parseUrlHash(urlHash);
		if(hashtag[0]=='kanban'){
			easycase.showKanbanTaskList('kanban');
		}else{
			ajaxCaseView("case_project.php");
		}	
		$("#case_search").attr("placeholder", "Search");
		window.location = HTTP_ROOT + "dashboard/";
	} else {
	    remember_filters('reset','filters');
		var hashtag = parseUrlHash(urlHash);
		if(hashtag[0]=='kanban'){
			easycase.showKanbanTaskList('kanban');
		}else{
			ajaxCaseView("case_project.php");
		}	
	}
    }

}

//Filter Buckets on task listing ends

function bindPrettyview(id) {
    $(".gallery a[rel^='"+id+"']").prettyPhoto({
	animation_speed:'normal',
	autoplay_slideshow: false, 
	social_tools: false,
	overlay_gallery: false,
	deeplinking: false
    });
}

function fuploadUI(csAtId) {
	var isExceed = 0;
	reply_total_files = new Array();reply_indx = 0;
	$('INPUT[type="file"]').change(function () {
		var isExceed = $("#isExceed").val();
		if(this.value.match(/\.(.+)$/) == null){
		    alert('File "'+this.value+'" has no extension, please upload files with extension ');
		    this.value = '';
		    return false;
		}
		if(this.value){
		    var ext = this.value.match(/\.(.+)$/)[1].toLowerCase();
		    if($.inArray(ext, ["bat","com","cpl","dll","exe","msi","msp","pif","shs","sys","cgi","reg","bin","torrent","yps","mp4","mpeg","mpg","3gp","dat","mod","avi","flv","xvid","scr","com","pif","chm","cmd","cpl","crt","hlp","hta","inf","ins","isp","jse?","lnk","mdb","ms","pcd","pif","scr","sct","shs","vb","ws","vbs"]) >= 1) {
			    alert("Sorry, '"+ext+"' file type is not allowed!");
			    this.value = '';
		    }
		}else if(isExceed == 1) {
			//alert("Sorry, Storage Limit Exceeded!");
		}
		//reply_total_files = new Array();reply_indx = 0;
	});

	var i = 0;
	$('.upload'+csAtId+':not(.applied'+csAtId+')').addClass('applied'+csAtId+'').fileUploadUI({
		uploadTable: $('#up_files'+csAtId+''),
		downloadTable: $('#up_files'+csAtId+''),
		buildUploadRow: function (files, index)
		 {
			var filename = files[index].name;
			if(filename.length > 35)
			{
				filename = filename.substr(0,35);
			}
			gFileupload = 0;
			reply_total_files.push(filename);
			return $('<tr><td valign="top">' + filename + '</td>' +
					'<td valign="top" width="100px" style="padding-left:10px;" title="Uploading..." rel="tooltip"><div class="progress-bar"><div class="progress-bar blue"><\/div><\/div></td>' +
					'<td valign="top" style="padding-left:10px;"><div class="file_upload_cancel">' +
					'<font id="cancel" title="Cancel" title="Cancel" rel="tooltip">' +
					'<span class="ui-icon-fupload ui-icon-cancel" onclick="cancelReplyFile(\''+filename+'\');">Cancel<\/span>' +
					'<\/font><\/div><\/td><\/tr>');
		},
		buildDownloadRow: function (file)
		{
			var fmaxilesize = document.getElementById('fmaxilesize').value;
			reply_indx++;
			if(file.name != "error")
			{
				if(file.message == "success")
				{
					var allowedSize = parseInt(fmaxilesize)*1024;
					if(parseInt(file.sizeinkb) <= parseInt(allowedSize)) {
						i++;
						/*if($('div [id^="'+csAtId+'jquerydiv"]').is(":visible")){
							i++;
						}else{
							i=1;	
						}*/
						document.getElementById('totfiles'+csAtId+'').value = i;
						var oncheck = "";
						var fname = file.filename.split("|");
						
						var filesize = file.sizeinkb;
						if(filesize >= 1024) {
							filesize = filesize/1024;
							filesize = Math.round(filesize*10)/10;
							filesize = filesize+" Mb";
						}
						else {
							filesize = Math.round(filesize*10)/10;
							filesize = filesize+" Kb";
						}
						
						if(parseInt(reply_total_files.length) == reply_indx){
						   gFileupload = 1;
						}
						
						return $('<tr><td style="color:#0683B8;" valign="top"><div id="'+csAtId+'jquerydiv'+i+'"><input type="checkbox" checked onClick="return removeFile(\''+csAtId+'jqueryfile'+i+'\',\''+csAtId+'jquerydiv'+i+'\');" style="cursor:pointer;"/>&nbsp;&nbsp;<a href="'+HTTP_ROOT+'easycases/download/'+fname[0]+'" style="text-decoration:underline;position:relative;top:-2px;">'+file.name+' ('+filesize+')</a><input type="hidden" name="data[Easycase][name][]" id="'+csAtId+'jqueryfile'+i+'" value="'+file.filename+'"/><\/div><\/td><\/tr>');
					}
					else {
						alert("Error uploading file. File size cannot be more then "+fmaxilesize+" Mb!");
						if(parseInt(reply_total_files.length) == reply_indx){
						    gFileupload = 1;
						 }
					}
				}
				else if(file.message == "exceed") {
					alert("Error uploading file.\nStorage usage exceeds by "+file.storageExceeds+" Mb!");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
				}
				else if(file.message == "size") {
					alert("Error uploading file. File size cannot be more then "+fmaxilesize+" Mb!");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
				}
				else if(file.message == "error") {
					alert("Error uploading file. Please try with another file");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
				}else if(file.message == "s3_error") {
					alert("Due to some network problem your file is not uploaded.Please try again after sometime.");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
				}
				else {
					alert("Sorry, "+file.message+" file type is not allowed!");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
					//\nAllowed are: txt, doc, docx, xls, xlsx, pdf, odt, ppt, jpeg, tif, gif, psd, jpg or png.
				}
			}
			else {
				alert("Error uploading file. Please try with another file");
				if(parseInt(reply_total_files.length) == reply_indx){
				    gFileupload = 1;
				 }
			}
		}
	});
}

/* Function require to get the CUSTOM FILTERS lists on the left panel starts here */

function openAjaxCustomFilter(dataVal,customid){
	if($("#customFil").hasClass("open")){
		if(dataVal == 'auto'){
			$(".customFilterLoader").show();
		}else{
			$(".customFilterLoader").hide();
		}
	}else{
	    $(".menu-files").removeClass('active');
	    $(".menu-cases").removeClass('active');
		$(".customFilterLoader").show();
	}
	$('.customFilter').html('');
	var strURL = HTTP_ROOT+"easycases/"; //alert(strURL);
	$.post(strURL+"ajax_custom_filter_show", function(data){
		if(data) {
			$(".customFilterLoader").hide();
			$('.customFilter').html(data);
			if(customid !== '') {
			    $('.customlink').removeClass('active');
			    $("#lnkcustomFilterRow_"+customid).addClass('active');
			    $("#deleteImg_"+customid).show();
			    $(".allmenutab").removeClass('active');
			    $(".more_menu_li").removeClass('active');
			}
		}
	});
}

/* Function require to get the CUSTOM FILTERS lists on the left panel ends here */

function showmoreCustomFilter(limit,type){ //Function to get NEXT Custom Filters by using the pagination
	if(type == "more"){
		var limit1 = limit;
	}
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"easycases/"; 
	$(".customFilterLoader").show();
	$('.customFilter').html('');
	$.post(strURL+"ajax_custom_filter_show",{"limit1":limit1,'type':type}, function(data){
		if(data) {
			$(".customFilterLoader").hide();
			$('.customFilter').html(data);
		}
	});
}

function previousCustomFilter(limit,type){ //Function to get PREVIOUS Custom Filters by using the pagination
	if(type == "less"){
		var limit1 = parseInt(limit)-6;
	}
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"easycases/";
	$(".customFilterLoader").show();
	$('.customFilter').html('');
	$.post(strURL+"ajax_custom_filter_show",{"limit1":limit1,'type':type}, function(data){
		if(data){
			$(".customFilterLoader").hide();
			$('.customFilter').html(data);
		}
	});
}

function displayDeleteImg(id) //Display the delete image for the Custom Filters
{
	$("#deleteImg_"+id).show();
}

function hideDeleteImg(id) //Hide the delete image for the Custom Filters
{
	$("#deleteImg_"+id).hide();
}

function deleteCustomFilter(id,name){
	if(id){
		var conf = confirm("Are you sure you want to delete custom filter '"+decodeURIComponent(name.replace(/\+/g,' '))+"' ?");
		if(conf == true){
			var strURL = document.getElementById('pageurl').value;
			strURL = strURL+"easycases/";
			$.post(strURL+"ajax_customfilter_delete",{'id':id}, function(data){
				if(data){
					if(data == 'success'){
						$("#customFilterRow_"+id).fadeOut('slow'); 
						openAjaxCustomFilter('auto','');
						ajaxCaseView();
					}else{
						return false;
					}
				}
			});
		}else{
			return false;
		}
	}
}

function openAjaxRecentCase(){
	//alert($('#recentCases').attr('class'));
	if($("#recentCases").hasClass("open")){  //If the HTML for recent viewed list is opened
	    $('.recentViewed').html('');
	}else{
		$(".recentViewLoader").show();
        $(".recentViewed").html('');
		var strURL = HTTP_ROOT+"easycases/";
		$.post(strURL+"ajax_recent_case",function(data){
			  if(data) {
				  $(".recentViewLoader").hide();
				  $('.recentViewed').html(data);
			  }
		});
	}
}

function showmoreRecentCase(limit,type){
	if(type == "more"){
		var limit1 = limit;
		var limit2 = 3;
	}
	var strURL = HTTP_ROOT+"easycases/";
	$(".recentViewLoader").show();
	$(".recentViewed").html('');
	$.post(strURL+"ajax_recent_case",{"limit1":limit1,'type':type}, function(data){
		if(data){
			$(".recentViewLoader").hide();
		    $('.recentViewed').html(data);
		}
	});
}

function previousRecentCase(limit,type){
	if(type == "less"){
		var limit1 = parseInt(limit)-6;
		var limit2 = 3;
	}//alert(limit1);
	var strURL = HTTP_ROOT+"easycases/";
	$(".recentViewLoader").show();
	$(".recentViewed").html('');
	$.post(strURL+"ajax_recent_case",{"limit1":limit1,'type':type}, function(data){
		if(data){
			$(".recentViewLoader").hide();
		    $('.recentViewed').html(data);
		}
	});
}

function caseDetailsSearch(pid,cid,page){
	if(page == "dashboard"){
		window.location = HTTP_ROOT+'dashboard/?case='+cid+"&project="+pid;
	}
	else{
		window.location = HTTP_ROOT+'dashboard/?case='+cid+"&project="+pid;
	}
}


/* Code for Archive starts here */

function changeArcCaseList(type){
	var displayedcases = $("#displayedCases").val();
	var limit1, limit2;
	if(type=="more"){
		limit1 = displayedcases;
		limit2 = ARC_CASE_PAGE_LIMIT; //Present in footer_inner.ctp
	}else{
		limit1 = 0;
		limit2 = ARC_CASE_PAGE_LIMIT;
	}
	if(type == "more") {
		$(".morebar_arc_case").show();
		var lastCount = $("#caselist").children("tr:last").attr("data-value");
    } else {
		document.getElementById('caseLoader').style.display='block'; 
    }
	
	var displayedcases = $("#displayedCases").val();
	var url = HTTP_ROOT+"archives/case_list";
	$.post(url,{"pjid":"all", "limit1":limit1, "limit2":limit2, "lastCount":lastCount}, function(data){
	  if(data) {
		$("#caselistDiv").show();
		$("#filelistDiv").hide();
		$('#task_li').addClass('active');
		$('#file_li').removeClass('active');
		//alert(data);
		var data = data.replace("<head/>", "");
		var data = data.replace("<head/ >", "");
		var data = data.replace("<head />", "");
		if(type == "more"){
			$(".morebar_arc_case").hide();
			
			$('.caselistall').append(data);
			if($('.chkOneArcCase:checked').length == $(".chkOneArcCase").length) {
				$("#allcase").prop('checked',true);
			} else {
				$("#allcase").prop('checked',false);
			}
			var displayedcases = $("#displayedCases").val();
			var newdisplayedcases = (parseInt(displayedcases)) + ARC_CASE_PAGE_LIMIT;
			$("#displayedCases").val(newdisplayedcases);
		}else{
			document.getElementById('caseLoader').style.display="none";
			$(".all_first_rows").remove();
			$(".pj_id").remove();
			$(".total_case_count").remove();
			$("#displayedCases").remove();
			$("#displayedCases").val(ARC_CASE_PAGE_LIMIT);
			$('#allcase').parents('.dropdown').removeClass('active');
			$('#allcase').next('.all_chk').attr('data-toggle','');
			$('#allcase').prop('checked',false);
			$('.caselistall').find("tr:gt(0)").remove();
			$('.caselistall').append(data);
		}
	  }
	});
}

function enableArcCaseOptions(){
	if($('.chkOneArcCase:checked').length){
		$('#allcase').parents('.dropdown').addClass('active');
		$('#allcase').next('.all_chk').attr('data-toggle','dropdown');
	} else {
		$('#allcase').parents('.dropdown').removeClass('active');
		$('#allcase').next('.all_chk').attr('data-toggle','');
	}
}

$(function(chkAll,chkOne,row, active_class){
	$(document).on('click', chkAll, function(e){
		if($(chkAll).is(':checked')){
			$(chkOne).prop('checked',true);
			$(chkOne).parents(row).addClass(active_class);
		} else {
			$(chkAll).parent().removeClass('open');
			$(chkOne).prop('checked',false);
			$(chkOne).parents(row).removeClass(active_class);
		}
		enableArcCaseOptions();
	});
	
	$(document).on('click', chkOne, function(e){
		if($(this).is(':checked')){
			$(this).parents(row).addClass(active_class);
		} else {
			$(chkAll).parent().removeClass('open');
			$(this).parents(row).removeClass(active_class);
		}
		
		if($(chkOne+':checked').length == $(chkOne).length) {
			$(chkAll).prop('checked',true);
		} else {
			$(chkAll).prop('checked',false);
		}
		enableArcCaseOptions();
	});
}('#allcase','.chkOneArcCase','.tr_all','tr_all_active'));

function changeArcFileList(type){ //alert(type)
	var displayedfiles = $("#displayedFiles").val(); //alert(displayedfiles);
	var limit1, limit2;
	if(type=="more"){
		limit1 = displayedfiles;
		limit2 = ARC_FILE_PAGE_LIMIT; //present in footer_inner.ctp
	}else{
		limit1 = 0;
		limit2 = ARC_FILE_PAGE_LIMIT;
	}
	if(type == "more") {
		$(".morebar_arc_case").show();
		var lastCountFiles = $("#filelist").children("tr:last").attr("data-value");
    } else {
		document.getElementById('caseLoader').style.display='block';
    }
	var url = HTTP_ROOT+"archives/file_list";
	$.post(url,{"pjid":"all", "limit1":limit1, "limit2":limit2, "lastCountFiles":lastCountFiles}, function(data){
	  if(data) {
		$("#caselistDiv").hide();
		$("#filelistDiv").show();
		$('#file_li').addClass('active');
		$('#task_li').removeClass('active');	
		var data = data.replace("<head/>", "");
		var data = data.replace("<head/ >", "");
		var data = data.replace("<head />", "");
		if(type == "more"){
			$(".morebar_arc_case").hide();
			$('.filelistall').append(data);
			if($('.chkOneArcFile:checked').length == $(".chkOneArcFile").length){
				$("#allfile").prop('checked',true);
			} else {
				$("#allfile").prop('checked',false);
			}
			var displayedfiles = $("#displayedFiles").val();
			var newdisplayedfiles = (parseInt(displayedfiles))+ARC_FILE_PAGE_LIMIT;
			$("#displayedFiles").val(newdisplayedfiles);
		}else{
			document.getElementById('caseLoader').style.display="none";
			$(".all_first_rows_files").remove();
			$(".filepjid").remove();
			$(".total_file_count").remove();
			$("#displayedFiles").remove();
			$("#displayedFiles").val(ARC_FILE_PAGE_LIMIT);
			$('#allfile').parents('.dropdown').removeClass('active');
			$('#allfile').next('.all_chk').attr('data-toggle','');
			$('#allfile').prop('checked',false);
			$('.filelistall').find("tr:gt(0)").remove();
			$('.filelistall').append(data);
		}
	  }
	});
}

function enableArcFileOptions(){
	if($('.chkOneArcFile:checked').length){
		$('#allfile').parents('.dropdown').addClass('active');
		$('#allfile').next('.all_chk').attr('data-toggle','dropdown');
	} else {
		$('#allfile').parents('.dropdown').removeClass('active');
		$('#allfile').next('.all_chk').attr('data-toggle','');
	}
}
$(function(chkAll,chkOne,row, active_class){
	$(document).on('click', chkAll, function(e){
		if($(chkAll).is(':checked')){
			$(chkOne).prop('checked',true);
			$(chkOne).parents(row).addClass(active_class);
		} else {
			$(chkAll).parent().removeClass('open');
			$(chkOne).prop('checked',false);
			$(chkOne).parents(row).removeClass(active_class);
		}
		enableArcFileOptions();
	});
	
	$(document).on('click', chkOne, function(e){
		if($(this).is(':checked')){
			$(this).parents(row).addClass(active_class);
		} else {
			$(chkAll).parent().removeClass('open');
			$(this).parents(row).removeClass(active_class);
		}
		if($(chkOne+':checked').length == $(chkOne).length) {
			$(chkAll).prop('checked',true);
		} else {
			$(chkAll).prop('checked',false);
		}
		enableArcFileOptions();
	});
}('#allfile','.chkOneArcFile','.tr_all','tr_all_active'));
function restoreall(){
	var pjid = document.getElementById('pjid').value;
	var count = $("#caselist").children("tr:last").attr("data-value");
	var val = new Array();
	var alt = new Array();
	for(var i=1;i<=count;i++)
	{
		if(document.getElementById("case"+i).checked==true)
		{
			val.push(document.getElementById("case"+i).value);
			alt.push(document.getElementById("csn"+i).value);
		}
	}
	if(val.length!='0')
	{
		if(confirm("Are you sure you want to restore task# "+alt+"?"))
		{
			document.getElementById('caseLoader').style.display="block";
			var pageurl = document.getElementById('pageurl').value;
			var url = pageurl+"archives/move_list";
			$.post(url,{"val":val}, function(data){
			  if(data)
			  {
				var url = HTTP_ROOT+"archives/case_list";
				$.post(url,{"pjid":"all", "limit1":0, "limit2":ARC_CASE_PAGE_LIMIT, "lastCount":''}, function(data){
					if(data) {
						var data = data.replace("<head/>", "");
						var data = data.replace("<head/ >", "");
						var data = data.replace("<head />", "");
						$(".all_first_rows").remove();
						$(".pj_id").remove();
						$(".total_case_count").remove();
						$("#displayedCases").val(ARC_CASE_PAGE_LIMIT);
						$('.caselistall').append(data);
						$(".dropdown").removeClass("open active");
						$("#allcase").prop('checked',false);
						$(".all_chk").attr("data-toggle", "");
						document.getElementById('caseLoader').style.display="none";
						showTopErrSucc('success','Task(s) have been restored.');
					}
				});
			  }
		  });	
	   }
	}
	else{
		alert("No task selected!");

	}
}


function restoreFromTask(case_id,pjid,case_no){
	var count = $("#caselist").children("tr:last").attr("data-value");
	var val = new Array();
	val.push(case_id);	
	if(val.length!='0')
	{
		if(confirm("Are you sure you want to restore task# "+case_no+"?"))
		{
		    document.getElementById('caseLoader').style.display="block";
		    var pageurl = document.getElementById('pageurl').value;
		    var url = pageurl+"archives/move_list";		    
		    $.post(url,{"val":val,'chk':1}, function(data){
		      if(data){
			  showTopErrSucc('success','Task(s) have been restored.');
			  easycase.refreshTaskList();
		      }
		   });
	       }
	}
	else{
		alert("No task selected!");
	}
}

function removeall(){
	var pjid=document.getElementById('pjid').value;
	var count = $("#caselist").children("tr:last").attr("data-value");
	var val = new Array();
	var alt = new Array();
	for(var i=1;i<=count;i++)
	{
		if(document.getElementById("case"+i).checked==true)
		{
			val.push(document.getElementById("case"+i).value);
			alt.push(document.getElementById("csn"+i).value);
		}
	}
	if(val.length!='0')
	{
		if(confirm("Are you sure you want to remove task# "+alt +"?"))
		{
			document.getElementById('caseLoader').style.display="block";
			var pageurl = document.getElementById('pageurl').value;
			var url = pageurl+"archives/case_remove";
			$.post(url,{"val":val}, function(data){
				if(data)
				{
					var url = HTTP_ROOT+"archives/case_list";
					$.post(url,{"pjid":"all", "limit1":0, "limit2":ARC_CASE_PAGE_LIMIT, "lastCount":''}, function(data){
						if(data) {
							var data = data.replace("<head/>", "");
							var data = data.replace("<head/ >", "");
							var data = data.replace("<head />", "");
							$(".all_first_rows").remove();
							$(".pj_id").remove();
							$(".total_case_count").remove();
							$("#displayedCases").remove();
							$('.caselistall').append(data);
							$(".dropdown").removeClass("open active");
							$("#allcase").prop('checked',false);
							$(".all_chk").attr("data-toggle", "");
							document.getElementById('caseLoader').style.display="none";
							showTopErrSucc('success','Task(s) have been removed.');
						}
					});
				}
			});
		}
	}
	else{
		alert("No task selected!");
	}
}

function removeFromTask(case_id,pjid,case_no){
	var val = new Array();
	val.push(case_id);
	if(val.length!='0')
	{
		if(confirm("Are you sure you want to remove task# "+case_no +"?"))
		{
			document.getElementById('caseLoader').style.display="block";
			var pageurl = document.getElementById('pageurl').value;
			var url = pageurl+"archives/case_remove";
			$.post(url,{"val":val,'chk':1}, function(data){
				if(data)
				{				
				    showTopErrSucc('success','Task(s) have been removed.');
				    easycase.refreshTaskList();
				}
			});
		}
	}
	else{
		alert("No task selected!");
	}
}

function restorefile(){
	var pjid=document.getElementById('filepjid').value;
	var count = $("#filelist").children("tr:last").attr("data-value");
	var val = new Array();
	for(var i=1;i<=count;i++)
	{
		if(document.getElementById("file"+i).checked==true)
		{
			val.push(document.getElementById("file"+i).value);
		}
		
	}
	var url = HTTP_ROOT+"archives/move_file";
	if(val.length!='0')
	{
		if(confirm("Are you sure you want to restore?"))
		{
			document.getElementById('caseLoader').style.display="block";
			$.post(url,{"val":val}, function(data){
			  if(data)
			  {
				var url = HTTP_ROOT+"archives/file_list";
			    $.post(url,{"pjid":pjid, "limit1":0, "limit2":ARC_FILE_PAGE_LIMIT, "lastCountFiles":''}, function(data){
					if(data) {
						var data = data.replace("<head/>", "");
						var data = data.replace("<head/ >", "");
						var data = data.replace("<head />", "");
						$(".all_first_rows_files").remove();
						$(".filepjid").remove();
						$(".total_file_count").remove();
						$("#displayedFiles").val(ARC_FILE_PAGE_LIMIT);
						$('.filelistall').append(data);
						$(".dropdown").removeClass("open active");
						$("#allfile").prop('checked',false);
						$(".all_chk").attr("data-toggle", "");
						document.getElementById('caseLoader').style.display="none";
						showTopErrSucc('success','File has been restored.');
					}
				});	
			  }
		   });
		}
	}
	else{
		alert("No file selected!");
	}
}

function removefile(){
	var pjid=document.getElementById('filepjid').value;
	var count = $("#filelist").children("tr:last").attr("data-value");
	var val = new Array();
	for(var i=1;i<=count;i++)
	{
		if(document.getElementById("file"+i).checked==true)
		{
			val.push(document.getElementById("file"+i).value);
		}
		
	}
	var url = HTTP_ROOT+"archives/file_remove";
	if(val.length!='0')
	{
		if(confirm("Are you sure you want to remove?"))
		{
			document.getElementById('caseLoader').style.display="block";
			$.post(url,{"val":val}, function(data){
			  if(data)
			  {
				var url = HTTP_ROOT+"archives/file_list";
			    $.post(url,{"pjid":pjid, "limit1":0, "limit2":ARC_FILE_PAGE_LIMIT, "lastCountFiles":''}, function(data){
					if(data) {
						var data = data.replace("<head/>", "");
						var data = data.replace("<head/ >", "");
						var data = data.replace("<head />", "");
						$(".all_first_rows_files").remove();
						$(".filepjid").remove();
						$(".total_file_count").remove();
						$("#displayedFiles").remove();
						$('.filelistall').append(data);
						$(".dropdown").removeClass("open active");
						$("#allfile").prop('checked',false);
						$(".all_chk").attr("data-toggle", "");
						document.getElementById('caseLoader').style.display="none";
						showTopErrSucc('success','File has been removed.');
					}
				});	
			  }
		   });
		}	
	}
	else{
		alert("No file selected!");
	}
}
/* Code for Archive ends here */

/* Code for the Template section starts here */

function createTaskTemplate(action)
{
	if(action == 'add'){
		var tempTitle = $("#tasktemptitle").val();
		var tempDesc = $("#desc").val();
	}else{
		var tempTitle = $("#tasktemptitle_edit").val();
		var tempDesc = $("#desc_edit").val();
	}
	if(tempTitle == ''){
		$("#err_task_temp").show();
		$("#task_temp_err").html('Please provide a template title.');
	}else if(tempDesc == ''){
		$("#err_task_temp").show();
		$("#task_temp_err").html('Please provide template description.');
	}else{
		var pageurl = HTTP_ROOT;
		var url = pageurl+"templates/ajax_add_task_template";
		if(action == 'add'){
			$("#tasktemploader").removeClass("ldr-ad-btn");
			$("#task_btn").hide();
			var tempId = '';
			var page_num = '';
		}else if(action == 'edit'){
			$("#tasktemploader_edit").removeClass("ldr-ad-btn");
			$("#task_btn_edit").hide();
			var tempId = $("#hid_edit_id").val();
			var page_num = $("#hid_edit_page_num").val();
		}
	    $.post(url,{"tasktempId":tempId,"title":tempTitle, "tempDesc":tempDesc}, function(data){
			if(data) {
				$("#tasktemploader").addClass("ldr-ad-btn");
				$("#task_btn").show();
				if(data == 1){ 
					closePopup();
					showTopErrSucc('success','Template created successfully.');
					document.location.href = pageurl+"templates/tasks"
				}else if(data == 0){
					closePopup();
					showTopErrSucc('error','Template can\'t be added.');
				}else if(data == 2){
					closePopup();
					showTopErrSucc('success','Template updated successfully.');
					if((page_num == 1) || (page_num == '')){
						document.location.href = pageurl+"templates/tasks";
					}else{
						document.location.href = pageurl+"templates/tasks/?page="+page_num;
					}
				}else if(data == 3){
					closePopup();
					showTopErrSucc('error','Template can\'t be updated.');
				}else if(data == 4){
					closePopup();
					showTopErrSucc('error','This Template already exists.');
				}
			}
		});	
	}
}
function createTemplate()
{
	var tempTitle = $("#projtemptitle").val();
	if(tempTitle == ''){
		$("#project_temp_err").html('Please provide a template name.');
	}else{
		$("#prjtemploader").removeClass("ldr-ad-btn");
		$("#prj_btn").hide();
		var pageurl = HTTP_ROOT;
		var url = pageurl+"templates/ajax_add_template_module";
		$.post(url,{"title":tempTitle}, function(data){
			if(data) {
				$("#prjtemploader").addClass("ldr-ad-btn");
				$("#prj_btn").show();
				if(data == 1){ 
					closePopup();
					showTopErrSucc('success','Template created successfully.');
					document.location.href = pageurl+"templates/projects"
				}else if(data == 0){
					closePopup();
					showTopErrSucc('error','This Template already exists.');
				}
			}
		});	
	}
}

function addToProject(val, temp_name){
    openPopup();
	$(".add_prod_temp_name").html('Add "'+ temp_name +'" to project');
	$(".add_to_project").show();
    $('#inner_tmp_add').hide();
	$('#add-tmp-btn').hide();
	$(".popup_bg").css({"width":'850px'});
    $(".popup_form").css({"margin-top": "6px"});
    var strURL = HTTP_ROOT + "templates/add_to_project";
    $.post(strURL, {"temp_id":val}, function(data) {
	if (data) {
	    $(".loader_dv").hide();
		$('#inner_tmp_add').show();
	    $('#inner_tmp_add').html(data);
		$('.add-tmp-btn').show();
	}
    });
}

function ActivateTaskTemp(id, pageno){
	if(confirm("Are you sure to enable this template?")){
		document.location.href = HTTP_ROOT+'templates/activateTaskTemp/'+id+'/'+pageno;
	}else{
		return false;
	}
}

function DeactivateTaskTemp(id, pageno){
	if(confirm("Are you sure to disable this template?")){
		document.location.href = HTTP_ROOT+'templates/deactivateTaskTemp/'+id+'/'+pageno;
	}else{
		return false;
	}
}
function addTempToTask(val, temp_name, IsFromAddProject)
{
	if(IsFromAddProject == 1){
		closePopup();
		openPopup();
	}else{
		openPopup();
	}

	if(parseInt(temp_name.length) > 50){
		var tempName = temp_name.substr(0, 50)+"...";
	}else{
		var tempName = temp_name;
	}
	
	$(".add_task_temp_name").html('Add tasks to template "'+ tempName +'"');
	$(".add_task_to_temp").show();
	$("#task_to_temp_err").html('');
    $('#inner_task_add').hide();
	$('#add-task-btn').hide();
	$(".popup_bg").css({"width":'700px'});
    $(".popup_form").css({"margin-top": "6px"});
    var strURL = HTTP_ROOT + "templates/add_template";
    $.post(strURL, {"temp_id":val, "temp_name":temp_name}, function(data) {
	if (data) {
	    $(".loader_dv").hide();
		$('#inner_task_add').show();
	    $('#inner_task_add').html(data);
		$("#title").focus();
		$('.add-task-btn').show();
	}
    });
}

function removeTaskFromTemp(val, temp_name)
{
	openPopup();
	$(".remove_from_task").show();
	$(".proj_temp_name").html('Manage tasks for template "'+ temp_name +'"');
    $('#inner_tasks').hide();
	$('#add-remove-btn').hide();
	$(".popup_bg").css({"width":'850px'});
    $(".popup_form").css({"margin-top": "-5px"});
    var strURL = HTTP_ROOT + "templates/remove_from_tasks";
    $.post(strURL, {"temp_id":val, "temp_name":temp_name}, function(data) {
	if (data) {
	    $(".loader_dv").hide();
		$('#inner_tasks').show();
	    $('#inner_tasks').html(data);
		$('.add-remove-btn').show();
	}
    });
}

function selectcaseAll(arg, i) {
    if (parseInt(arg)) {
	if ($('#checkAll').is(":checked")) {
	    $(".ad-usr-prj").attr("checked", "checked");
	    $('.rw-cls').css({'background-color': '#FFFFCC'});
		$("#taskAddBtns").show();
	} else {
	    $(".ad-usr-prj").attr("checked", false);
	    $('.rw-cls').css({'background-color': ''});
		$("#taskAddBtns").hide();
	}
    } else {
	var listing = "listing_" + i;
	if ($('.ad-usr-prj:checked').length == $('.ad-usr-prj').length) {
	    $("#checkAll").attr("checked", "checked");
	    $('#' + listing).css({'background-color': '#FFFFCC'});
		$("#taskAddBtns").show();
	} else {
	    $("#checkAll").attr("checked", false);
	    if ($('#actionChk' + i).is(":checked")) {
		$('#' + listing).css({'background-color': '#FFFFCC'});
		$("#taskAddBtns").show();
	    } else {
		$('#' + listing).css({'background-color': ''});
		$("#taskAddBtns").hide();
	    }
	}
    }
}

function validateTaskTemplateEdit() {
	var title = document.getElementById('title_edit');
	var desc = document.getElementById('description_edit');
	$("#task_project_err_edit").html('');
	$("#prjtemploader_task_prj").show();
	$("#prj_btn_task_edit").hide();
	var errMsg;
	var done = 1;		
	if(title.value.trim() == ""){
		errMsg = "Title cannot be left blank!";
		title.focus();
		done = 0;	
	}
	if(done == 0) {
		var op = 100;
		$("#task_project_err_edit").html(errMsg);
		$("#prjtemploader_task_prj").hide();
		$("#prj_btn_task_edit").show();
		return false;
	}
	else {
		return true;
	}
}

function EditTaskProject(tempId, tmp_name, tmp_desc)
{	
	//closePopup();
	$(".remove_from_task").hide();
	openPopup();
    $(".task_project_edit").show();
    $("#header_task_prj").html(tmp_name);
    $('#inner_task_project_edit').show();
	$(".loader_dv_tsk_prj").hide();
	$("#title_edit").val(tmp_name);
	$("#description_edit").val(tmp_desc);
	$("#temp_id").val(tempId);
	$("#title_edit").focus();
}

function EditTask(tempId, tmp_name, pagenum)
{
	openPopup();
    $(".project_temp_popup_edit").show();
    $("#header_prj_task_temp").html(tmp_name);
    $('#inner_project_temp_edit').show();
	$(".loader_dv_prj").hide();
	$("#projtemptitle_edit").val(tmp_name);
	$("#hid_orig_projtemptitle_edit").val(tmp_name);
	$("#hid_temptitle_id").val(tempId);
	$("#hid_page_num").val(pagenum);
	$("#projtemptitle_edit").focus();
}

function save_edit_template()
{
	var temp_title = $('#projtemptitle_edit').val();
	var orig_temp_title = $('#hid_orig_projtemptitle_edit').val();
	var temptitle_id = $('#hid_temptitle_id').val();
	var pageNum = $("#hid_page_num").val();
	
	var strURL = HTTP_ROOT+'templates/ajax_template_edit';
	
	if(temp_title.trim() != ''){
		if(temp_title.trim() == orig_temp_title.trim()){
			return false;
		}else{
			$("#prjtemploader_edit").show();
			$("#prj_btn_edit").hide();
			$.post(strURL,{"template_id":temptitle_id,"module_name":escape(temp_title.trim())},function(data) {
				if(data){ //alert(data);
					 if(data.trim() == 'fail') {
							$("#project_temp_err_edit").hide();
							$("#prj_btn_edit").show();
					  }else if(data.trim() == 'exist'){
							$("#project_temp_err_edit").hide();
							$("#prj_btn_edit").show();
							$("#prjtemploader_edit").hide();
							$("#project_temp_err_edit").show();
							$("#project_temp_err_edit").html("This Template already exists.");
							return false;
					  }else{
						if((pageNum && parseInt(pageNum) == 1) || (pageNum == '')){
							document.location.href = HTTP_ROOT+'templates/projects';
						}else{
							document.location.href = HTTP_ROOT+'templates/projects?page='+pageNum;
						}
						showTopErrSucc('success',"Template updated successfully.");
						$("#prjtemploader_edit").hide();
					 }
				}
			});
		}
	}else{
		$("#project_temp_err_edit").html("Template name can't be blank.");
		return false;
	}
}

function deltemplatecases(caseId, caseName)
{
	var templateId = $('#templateId').val();
	if (confirm("Are you sure you want to remove '"+caseName+"'?")) {
		$("#listing_"+caseId).fadeOut(1000);
		var strURL = HTTP_ROOT + 'templates/ajax_template_case_listing';
		$.post(strURL, {"templateId": templateId, "case_id": caseId}, function(data) {
		if (data) {
			//var splitdata = data.split("****");
			//removeTaskFromTemp(templateId, splitdata[1]);
		}
		});
	}else{
		return false;
	}
}
function remove_cases_template() {
    var done = 0;
    var case_name = '';
    $('#inner_tasks input:checked').each(function() {
	if($(this).attr('id') !== 'checkAll'){
	    case_name = case_name +", "+ $(this).attr('data-case-name');
	    done++;
	}
    });
    case_name = case_name.replace(', ','');
    if(done) {
	if (confirm("Are you sure you want to remove '"+case_name+"'?")) {
	    var templateId = $('#templateId').val();
	    $('#inner_tasks input:checked').each(function() {
		if($(this).attr('id') !== 'checkAll'){
		    var listid = $(this).attr('id');
		    var case_id = $(this).attr('value');
		    var listing = $("#"+listid).parents("tr").attr('id');
		    $("#" + listing).fadeOut(1000);
		    var strURL = HTTP_ROOT + 'templates/ajax_template_case_listing';
		    $.post(strURL, {"templateId": templateId, "case_id": case_id}, function(data) {
			if (data) {
				//var splitdata = data.split("****");
				//removeTaskFromTemp(templateId, splitdata[1]);
			}
		    });
		}
	    });
	    showTopErrSucc('success', 'Task removed successfully');
	} else {
	    return false;
	}
    } else {
		showTopErrSucc('error', 'No task is selected to delete');
	    return false;
	}
}

function validateTaskTemplate() {
	var title = document.getElementById('title');
	var desc = document.getElementById('description');
	$("#task_to_temp_err").html('');
	var errMsg;
	var done = 1;		
	if(title.value.trim() == ""){
		errMsg = "Title cannot be left blank!";
		title.focus();
		done = 0;	
	}
	if(done == 0) {
		var op = 100;
		//showTopErrSucc('error',errMsg);
		$("#task_to_temp_err").html(errMsg);
		return false;
	}
	else {
		//document.getElementById('subprof1').style.display='none';
		//document.getElementById('subprof2').style.display='block';
		return true;
	}
}

function add_cases_project(){
	var pj_id=document.getElementById('proj_id').value;
	var temp_mod_id=document.getElementById('templateId').value;
	var projectName = $("#proj_id option:selected").text();
	//alert(pj_id);alert(temp_mod_id);return false;
	var done =1;
	if(pj_id == 0){
		errMsg = "Please select a project to add tasks.";
		done = 0;
	}
	if(done == 0) {
		var op = 100;
		showTopErrSucc('error',errMsg);
		return false;
	}
	else {
		var conf = confirm("Are you sure you want to add these tasks to '"+projectName+"'?");
		if(conf == true)
		{
		$("#addtaskloader").removeClass('ldr-ad-btn');
		$("#taskAddBtns").hide();
		
		var strURL = HTTP_ROOT+"templates/";
		$.post(strURL+"ajax_add_template_cases",{"pj_id":pj_id,"temp_mod_id":temp_mod_id}, function(data){
			  if(data) {
				if(data == 1){
					$("#addtaskloader").addClass('ldr-ad-btn');
					$("#taskAddBtns").show();
					var op = 100;
					showTopErrSucc('success','Tasks has been added.');
					return false;	
				}else{
					$("#addtaskloader").addClass('ldr-ad-btn');
					$("#taskAddBtns").show();
					var op = 100;
					showTopErrSucc('error','Template is already added to this project!');
					return false;
				}
			  }
		});
		}else{
			return false;		
		}		
	}
}


/* Code for the Template section ends here */

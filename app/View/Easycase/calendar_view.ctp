<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ROOT; ?>css/fullcalendar/fullcalendar.css"/>
<script src='<?php echo HTTP_ROOT; ?>js/fullcalendar/fullcalendar.min.js'></script>

<script>

	$(document).ready(function() {
	        var strURL = HTTP_ROOT + "easycases/";		
		var url = strURL+"getTaskList";
		var current_url = '';
		var new_url     = '';
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();               
		var calendar = $('#calendar').fullCalendar({
			header: { 
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek' /*,agendaDay*/
			},
			selectable: true,
			selectHelper: true,
			events: function( start, end, callback ) {
			    $('.fc-button-today').text('Today');			        
			    $('.fc-button-month').text('Month');
			    $('.fc-button-agendaWeek').text('Week');
			    $('.fc-button-agendaDay').text('Day');
			    var year = end.getFullYear();
			    var month = end.getMonth();
			    
			    var s_year = start.getFullYear();
			    var s_month = start.getMonth();		    
			    //console.log(month+'----'+year+'---'+s_year+'----'+s_month);
			    var type ='calendar';
			    new_url  = url;
			    //if( new_url != current_url ){
				//{"projFil":projFil,"projIsChange":projIsChange,"casePage":casePage,'caseStatus':caseStatus,'customfilter':customfilter,'caseTypes':caseTypes,'priFil':priFil,'caseMember':caseMember,'caseAssignTo':caseAssignTo,'caseSearch':caseSearch,'case_srch':case_srch,'case_date':case_date,'case_due_date':case_due_date,'morecontent':'','milestoneUid':milestone_uid}
				var params = parseUrlHash(urlHash);
				var milestone_uid = $('#milestoneUid').val();
				if(params[1]){
					milestone_uid = params[1];
					$('#milestoneUid').val(params[1]);
					if(($('#caseMenuFilters').val() =='milestone') || ($('#caseMenuFilters').val()=='milestonelist'))
						$('#refMilestone').val($('#caseMenuFilters').val());
				}
				$('#select_view div').tipsy({gravity:'n', fade:true});
				var globalkanbantimeout =null;var morecontent ='';
				if(type =='calendar'){
					//crt_popup_close();
					$('#select_view div').removeClass('disable');
					$('#calendar_btn').addClass('disable');
					easycase.routerHideShow('calendar');
					$("#caseMenuFilters").val('calendar');
					//$(".menu-cases").addClass('active');
					$(".menu-files").removeClass('active');
					$(".menu-milestone").removeClass('active');
					//$("#brdcrmb-cse-hdr").html('Tasks');
				}	
				var strURL = HTTP_ROOT+"easycases/";
				var casePage = $('#casePage').val(); // Pagination
				//if(morecontent){
					//$('#loader_'+morecontent).show();
				//}else{
					$('#caseLoader').show();
				//}
				var projFil = $('#projFil').val(); 
				var projIsChange = $('#projIsChange').val(); 
				var customfilter = $('#customFIlterId').value;//Change case type
				var caseStatus = $('#caseStatus').val(); // Filter by Status(legend)
				var priFil = $('#priFil').val(); // Filter by Priority
				var caseTypes = $('#caseTypes').val(); // Filter by case Types
				var caseMember = $('#caseMember').val();  // Filter by Member
				var caseAssignTo = $('#caseAssignTo').val();  // Filter by AssignTo
				var caseSearch = $('#case_search').val(); // Search by keyword
				var case_date = $('#caseDateFil').val(); // Search by Date
				var case_due_date = $('#casedueDateFil').val(); // Search by Date
				var case_srch = $('#case_srch').val();
				var caseId = document.getElementById('caseId').value; // Close a case
				var strURL = HTTP_ROOT + "easycases/";
				var tskURL = strURL+"getTaskList";
				$.post(tskURL,{"from_view_year":s_year,"from_view_month":s_month,"to_view_year":year,"to_view_month":month,"projFil":projFil,"projIsChange":projIsChange,"casePage":casePage,'caseStatus':caseStatus,'customfilter':customfilter,'caseTypes':caseTypes,'priFil':priFil,'caseMember':caseMember,'caseAssignTo':caseAssignTo,'caseSearch':caseSearch,'case_srch':case_srch,'case_date':case_date,'case_due_date':case_due_date,'morecontent':'','milestoneUid':milestone_uid},function(res){
				    $('#caseLoader').hide();
				    callback(res);
				},'json');				
			   //}else{
			      // console.log(user_events);
			       //callback(user_events);
			   //}
			},
			select: function(start, end, allDay) {	
				var check = $.fullCalendar.formatDate(start,'yyyy-MM-dd');
				var today = $.fullCalendar.formatDate(new Date(),'yyyy-MM-dd');
				if(check < today)
				{
				    return false;
				}
				else
				{
					var year = start.getFullYear();
					var month = start.getMonth();
					month_t = eval(month+1);
					var date = start.getDate();
					var dayArr = ['Sun','Mon','Tues','Weds','Thurs','Fri','Sat'];
					var monthArr = ['Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec'];
					gDueDate = 0;
					creatask();                                
					$('#CS_due_date').val(month_t+'/'+date+'/'+year);
					$('#date_dd').html(monthArr[month]+' '+date+', '+dayArr[start.getDay()]);
					$('#opt3').parent().removeClass('option-toggle').addClass('option-toggle_active');
					$('#date_dd').css('font-weight','bold');
				}
			},
			eventClick: function(calEvent, jsEvent, view) {
			    //console.log(calEvent);
			    //editask(calEvent.caseUniqId,calEvent.ProjectUniqId,calEvent.projectName);
			    easycase.ajaxCaseDetails(calEvent.caseUniqId, 'case', 0);
			},
			eventRender: function(event, element) {
			    var addition = '';
			    var prj_typ = $('#projFil').val();
			    if(prj_typ == 'all')
				addition = " <b>"+event.projectSortName+"</b> #"+event.case_no+": ";
			    else
				addition = " #"+event.case_no+": ";
			    if(event.photo != undefined && event.photo != ''){
				element.find(".fc-event-title").before("<img rel='tooltip' src='"+HTTP_ROOT+"users/image_thumb/?type=photos&file="+event.photo+"&sizex=26&sizey=26&quality=100' class='round_profile_img' height='26' width='26' title='Assigned to: "+event.name+"'/>"+addition);
				//element.prev(".fc-day-number").css( "background-color", "red" );
			    }else{
				element.find(".fc-event-title").before("<img rel='tooltip' src='"+HTTP_ROOT+"users/image_thumb/?type=photos&file=user.png&sizex=92&sizey=92&quality=100' class='round_profile_img' height='26' width='26' title='Assigned to: "+event.name+"'/>"+addition);
				//element.prev(".fc-day-number").css( "background-color", "red" );				
			    }
			    element.find('.fc-event-title').attr('title',event.original_title);
                            //console.log(event);
			    var clrCod = '';
			    if(event.legend == 1){ //new
				clrCod = '#DB7F6D';
			    }else if(event.legend == 5){ //resolved
				clrCod = '#EFA05F';
		            }else if(event.legend == 3){ //closed
				clrCod = '#78B07D';
			    }else{ //Wip
				clrCod = '#658FD3';
			    }
			    if(clrCod != ''){
				    element.find('.fc-event-inner').parent().css('border','1px solid '+clrCod);
				    element.find('.fc-event-inner').css('background-color',clrCod);
			    }			    
			    $('[rel=tooltip]').tipsy({gravity:'s', fade:true});
			},
			eventDrop: function( event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) { 
			    var check = $.fullCalendar.formatDate(event.start,'yyyy-MM-dd');
			    var today = $.fullCalendar.formatDate(new Date(),'yyyy-MM-dd');
			    var arr = ['5','3'];
			    if((check < today) || ($.inArray(event.legend,arr) != -1)){
				revertFunc();
			    }else{
				if (confirm("Are you sure you want to change the Due Date?")) {			       
				   var s_year = event.start.getFullYear();
				   var s_month = event.start.getMonth();
				   var s_date = event.start.getDate();
				   s_month = eval(s_month+1);
				   var date = s_year+'-'+s_month+'-'+s_date;
				   var strURL = HTTP_ROOT + "easycases/";
				   var updURL = strURL+"updateDueDate";
				   var text ='';
				   date = s_month+'/'+s_date+'/'+s_year;    
				   $.post(HTTP_ROOT+"easycases/ajax_change_DueDate",{"caseId":event.caseId,"duedt":date,"text":text},function(data) { 
				   },'json');				   
				   /*$.post(updURL,{'date':date,'uniq_id':event.caseUniqId},function(res){
				       //console.log(res);
				    },'json');*/
				}else{
				   revertFunc();
				}
			    }
			},
			editable: true
			//eventColor: '#378006'
		    });		    
	});

</script>
<style>
#calendar {
    margin-top: 50px;
    margin-bottom: 50px;
    width: 94%;
    margin: 0 auto;
    margin-left:20px
 }
 .fc-event-container{
     z-index: 1 !important;
 }
 .fc-button{
     position: static !important;
 }
 .round_profile_img{
     top: 0px !important;
 }
.fc-past{
     background-color: #F8F8FF !important;
 }
 .fc-last, .fc-first{
     background-color: #EEEFFF !important; /*#EDEDED*/
 }
 .fc-widget-header{
     background-color: #fff !important;
 }
 .fc-today{
     background-color: #FCFCCE !important;
 }
</style>
<div id='calendar'></div>

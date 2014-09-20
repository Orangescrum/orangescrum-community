<% var due_date = getCookie('DUE_DATE');
var duedateArr = {'Anytime':'any', 'Overdue':'overdue', 'Today':'24'};
for(i in duedateArr){
var chked = '';
if(duedateArr[i] == 'any' && trim(due_date) == ''){
	chked = 'checked';
} else if(duedateArr[i] == 'overdue' && trim(due_date) == 'overdue'){
	chked = 'checked';
} else if(duedateArr[i] == '24' && trim(due_date) == '24'){
	chked = 'checked';
} %>
<li>
    <a href="javascript:void(0);">
	<input type="radio"  name="duedate_filter" id="duedate_<%= duedateArr[i] %>" class="cbox_date" style="cursor:pointer" <%= chked %> onclick="checkboxdueDate(<%= '\''+duedateArr[i]+'\',\'check\'' %>);filterRequest(<%= '\'duedate\'' %>);" />
	<font onclick="checkboxdueDate(<%= '\''+duedateArr[i]+'\',\'text\'' %>);filterRequest(<%= '\'duedate\'' %>);" >&nbsp;<%= i %></font>
    </a>
</li>
<% } %>
<% var dt = '';
if(trim(due_date).indexOf(':')!=-1){
 var dt=trim(due_date).split(':');
} %>
<li>
    <a href="javascript:void(0);">
		<input type="radio"  name="duedate_filter" id="duedate_custom" class="cbox_date" style="cursor:pointer" onclick="checkboxcustom(<%= '\'custom_duedate\',\'duedate_custom\',\'due\'' %>);" <% if(dt){ %> checked="checked" <% } %> />
		<font onClick="checkboxcustom(<%= '\'custom_duedate\',\'duedate_custom\',\'due\'' %>);" >&nbsp;Custom range</font>
    </a>
</li>
<div id="custom_duedate" <% if(!dt){%>style="display:none;"<% } %>>
	<div  class="cdate_div_cls">
		<input type="text" id="duefrm"  value="<%= dt[0] %>" placeholder="From" class="form-control"/><br/>
		<input type="text" id="dueto" value="<%= dt[1] %>" placeholder="To" class="form-control" />
	</div>
	<div  class="cduedate_btn_div" style="text-align:center;margin-top: 5px;cursor:pointer">
		<button class="btn btn-primary cdate_btn" style="cursor: pointer;"  onclick="return searchduedate();">Search</button>
	</div>
</div>
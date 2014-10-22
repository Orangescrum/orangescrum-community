<li style="position:relative; border-bottom:1px solid #657381;" id="casesmenu" <% if((filters == "" && page == "dashboard" && !cs) || (filters == "cases" && page == "dashboard" ) || (filters == "delegateto" && page == "dashboard") || (filters == "latest" && page == "dashboard"))  { %><%= 'class="testmenucls current"' %><% } %>>
	<a href="javascript:jsVoid();" class="cases" onclick="caseMenuFileter(<%= '\'cases\',\'' + page + '\', \'' + filters + '\', \'' + cs + '\'' %>);">
		Tasks
	</a>
	<span title="<%= caseNew %> tasks" class="badge"><%= caseNew %></span>
	<ul id="mstoneul" class="sub_ul" style="width:210px; height:212px;">
		<li id="latest" <% if(filters == "latest" && page == "dashboard") { %><%= 'class="current"' %><% } %>>
			<a href="javascript:jsVoid();"  onclick="caseMenuFileter(<%= '\'latest\', \'' + page + '\', \'' + filters + '\', \'' + cs + '\'' %>);">
				Recent<div class="rec_nt"></div>
			</a>
			<span title="<%= latest %> Latest" class="sub_badge"><%= latest %></span>
		</li>
		<li id="assigntomenu" <% if(filters == "assigntome" && page == "dashboard") { %><%= 'class="current"' %><% } %>>
			<a href="javascript:jsVoid();"  onclick="caseMenuFileter(<%= '\'assigntome\', \'' + page + '\', \'' + filters + '\', \'' + cs + '\'' %>);">
				Assigned&nbsp;To&nbsp;Me<div class="asn_me"></div>
			</a>
			<span title="<%= assignToMe %> tasks" class="sub_badge"><%= assignToMe %></span>
		</li>
		<li id="delegatetomenu" <% if(filters == "delegateto" && page == "dashboard") { %><%= 'class="current"' %><% } %>>
			<a href="javascript:jsVoid();" onclick="caseMenuFileter(<%= '\'delegateto\', \'' + page + '\', \'' + filters + '\', \'' + cs + '\'' %>);">
				Delegated&nbsp;To&nbsp;Others<div class="del_gt"></div>
			</a>
			<span title="<%= delegateTo %> tasks" class="sub_badge"><%= delegateTo %></span>
		</li>
		<li id="overduemenu" <% if(filters == "overdue" && page == "dashboard")  { %><%= 'style="font-weight:bold"' %><% } %>>
			<a href="javascript:jsVoid();" onclick="caseMenuFileter(<%= '\'overdue\', \'' + page + '\', \'' + filters + '\', \'' + cs + '\'' %>);"<% if(filters == "overdue" && page == "dashboard")  { %><%= 'style="font-weight:bold"' %><% } %>>Bug<div class="bug_cs"></div></a>
			<span title="<%= overdue %> tasks" class="sub_badge"><%= overdue %></span>
		</li>
		<li id="closecasemenu" <% if(filters == "closecase" && page == "dashboard")  { %><%= 'style="font-weight:bold"' %><% } %>>
			<a href="javascript:jsVoid();" onclick="caseMenuFileter(<%= '\'closecase\', \'' + page + '\', \'' + filters + '\', \'' + cs + '\'' %>);"<% if(filters == "closecase" && page == "dashboard")  { %><%= 'style="font-weight:bold"' %><% } %>>Closed<div class="close_cs"></div></a>
			<span title="<%= closeCase %> tasks" class="sub_badge"><%= closeCase %></span>
		</li>
		<li id="all_case" <% if(filters == "" && page == "dashboard" && !cs)  { %><%= 'style="font-weight:bold"' %><% } %>>
			<a href="javascript:jsVoid();" onclick="caseMenuFileter(<%= '\'cases\', \'' + page + '\', \'' + filters + '\', \'' + cs + '\'' %>);"<% if(filters == "" && page == "dashboard" && !cs)  { %><%= 'style="font-weight:bold"' %><% } %>>All<div class="allca_se"></div></a>
			<span title="<%= caseNew %> tasks" class="sub_badge"><%= caseNew %></span>
		</li>
	</ul>
</li>
<li id="filesmenu" <% if(filters == "files" && page == "dashboard") { %><%= 'class="current"' %><% } %> style="border-bottom:none">
	<a href="javascript:jsVoid();" class="files no-submenu" onclick="caseMenuFileter(<%= '\'files\', \'' + page + '\', \'' + filters + '\', \'' + cs + '\'' %>);">Files</a>
	<span title="<%= caseFiles %> files" class="badge"><%= caseFiles %></span>
</li>

<table cellpadding="0" cellspacing="0" border="0" align="right" >
	<tr>
		<td align="center" style="padding-top:5px;">
			<div class="show_total_case" style="font-weight:normal;color:#000;font-size:12px;"><%= pgShLbl %></div>
		</td>
	</tr>
	<tr>
		<td align="center" style="padding-top:5px">
		<ul class="pagination">
		<% var page = parseInt(csPage);
		if(page_limit < caseCount) {
			var numofpages = caseCount / page_limit;
			if((caseCount % page_limit) != 0) {
				numofpages = numofpages+1;
			}
			var lastPage = numofpages;
			
			var k = 1;
			var data1 = "";
			var data2 = "";
			if(numofpages > 5) {
				var newmaxpage = page+2;
				if(page >= 3) {
					var k = page-2;
					data1 = "...";
				}
				if((numofpages - newmaxpage) >= 2) {
					if(data1) {
						data2 = "...";
						numofpages = page+2;
					} else {
						if(numofpages >= 5) {
							data2 = "...";
							numofpages = 5;
						}
					}
				}
			}
			if(data1) { %>
				<li><a href="javascript:jsVoid();" class="button_act" onClick="casePaging(1)">&laquo; First</a></li>
				<li class="hellip">&hellip;</li>
		<% 	}
			if(page != 1) {
				var pageprev = page-1; %>
				<li><a href="javascript:jsVoid();" class="button_act" onClick="casePaging(<%= '\'' + pageprev + '\'' %>)">&lt;&nbsp;Prev</a></li>
		<% 	} else { %>
				 <li><a href="javascript:jsVoid();" class="button_prev" style="cursor:text">&lt;&nbsp;Prev</a></li>
		<% 	}
			for(var i = k; i <= numofpages; i++) {
				if(i == page) {
		%>
					<li><a href="javascript:jsVoid();" class="button_page" style="cursor:text"><%= i %></a></li>
		<% 		} else { %>
					<li><a href="javascript:jsVoid();" class="button_act" onClick="casePaging(<%= '\'' + i + '\'' %>)"><%= i %></a></li>
		<% 		}
			}
			if((caseCount - (page_limit * page)) > 0) {
				var pagenext = page+1; %>
				<li><a href="javascript:jsVoid();" class="button_act" onClick="casePaging(<%= '\'' + pagenext + '\'' %>)">Next&nbsp;&gt;</a></li>
		<% 	} else { %>
				<li><a href="javascript:jsVoid();" class="button_prev">Next&nbsp;&gt;</a></li>
		<% 	}
			if(data2) { %>
				<li class="hellip">&hellip;</li>
				<li><a href="javascript:jsVoid();" class="button_act" onClick="casePaging(<%= '\'' + Math.floor(lastPage) + '\'' %>)">Last &raquo;</a></li>
		<% 	}
		} %>
		</ul>
		</td>
	</tr>
</table>
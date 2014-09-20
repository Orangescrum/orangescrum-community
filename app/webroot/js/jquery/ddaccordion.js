//Accordion Content script: By Dynamic Drive, at http://www.dynamicdrive.com
//Created: Jan 7th, 08'
//Version 1.3: April 3rd, 08':
//**1) Script now no longer conflicts with other JS frameworks
//**2) Adds custom oninit() and onopenclose() event handlers that fire when Accordion Content instance has initialized, plus whenever a header is opened/closed
//**3) Adds support for expanding header(s) using the URL parameter (ie: http://mysite.com/accordion.htm?headerclass=0,1)
//April 9th, 08': Fixed "defaultexpanded" setting not working when page first loads

jQuery.noConflict()

var ddaccordion={
	
	contentclassname:{}, //object to store corresponding contentclass name based on headerclass

	expandone:function(headerclass, selected){ //PUBLIC function to expand a particular header
		this.toggleone(headerclass, selected, "expand")
	},

	collapseone:function(headerclass, selected){ //PUBLIC function to collapse a particular header
		this.toggleone(headerclass, selected, "collapse")
	},

	expandall:function(headerclass){ //PUBLIC function to expand all headers based on their shared CSS classname
		var $=jQuery
		var $headers=$('.'+headerclass)
		$('.'+this.contentclassname[headerclass]+':hidden').each(function(){
			$headers.eq(parseInt($(this).attr('contentindex'))).click()
		})
	},

	collapseall:function(headerclass){ //PUBLIC function to collapse all headers based on their shared CSS classname
		var $=jQuery
		var $headers=$('.'+headerclass)
		$('.'+this.contentclassname[headerclass]+':visible').each(function(){
			$headers.eq(parseInt($(this).attr('contentindex'))).click()
		})
	},

	toggleone:function(headerclass, selected, optstate){ //PUBLIC function to expand/ collapse a particular header
		var $=jQuery
		var $targetHeader=$('.'+headerclass).eq(selected)
		var $subcontent=$('.'+this.contentclassname[headerclass]).eq(selected)
		if (typeof optstate=="undefined" || optstate=="expand" && $subcontent.is(":hidden") || optstate=="collapse" && $subcontent.is(":visible"))
			$targetHeader.click()
	},

	expandit:function($targetHeader, $targetContent, config, isclicked){
		$targetContent.slideDown(config.animatespeed, function(){config.onopenclose($targetHeader.get(0), parseInt($targetHeader.attr('headerindex')), $targetContent.css('display'), isclicked)})
		this.transformHeader($targetHeader, config, "expand")
	},

	collapseit:function($targetHeader, $targetContent, config, isclicked){
		$targetContent.slideUp(config.animatespeed, function(){config.onopenclose($targetHeader.get(0), parseInt($targetHeader.attr('headerindex')), $targetContent.css('display'), isclicked)})
		this.transformHeader($targetHeader, config, "collapse")
	},

	transformHeader:function($targetHeader, config, state){
		$targetHeader.addClass((state=="expand")? config.cssclass.expand : config.cssclass.collapse) //alternate btw "expand" and "collapse" CSS classes
		.removeClass((state=="expand")? config.cssclass.collapse : config.cssclass.expand)
		if (config.htmlsetting.location=='src'){ //Change header image (assuming header is an image)?
			$targetHeader=($targetHeader.is("img"))? $targetHeader : $targetHeader.find('img').eq(0) //Set target to either header itself, or first image within header
			$targetHeader.attr('src', (state=="expand")? config.htmlsetting.expand : config.htmlsetting.collapse) //change header image
		}
		else if (config.htmlsetting.location=="prefix") //if change "prefix" HTML, locate dynamically added ".accordprefix" span tag and change it
			$targetHeader.find('.accordprefix').html((state=="expand")? config.htmlsetting.expand : config.htmlsetting.collapse)
		else if (config.htmlsetting.location=="suffix")
			$targetHeader.find('.accordsuffix').html((state=="expand")? config.htmlsetting.expand : config.htmlsetting.collapse)
	},

	urlparamselect:function(headerclass){
		var result=window.location.search.match(new RegExp(headerclass+"=((\\d+)(,(\\d+))*)", "i")) //check for "?headerclass=2,3,4" in URL
		if (result!=null)
			result=RegExp.$1.split(',')
		return result //returns null, [index], or [index1,index2,etc], where index are the desired selected header indices
	},

	getCookie:function(Name){ 
		var re=new RegExp(Name+"=[^;]+", "i") //construct RE to search for target name/value pair
		if (document.cookie.match(re)) //if cookie found
			return document.cookie.match(re)[0].split("=")[1] //return its value
		return null
	},

	setCookie:function(name, value){
		document.cookie = name + "=" + value
	},

	init:function(config)
	{
	document.write('<style type="text/css">\n')
	document.write('.'+config.contentclass+'{display: none}\n') //generate CSS to hide contents
	document.write('<\/style>')
	jQuery(document).ready(function($)
	{
			
		ddaccordion.urlparamselect(config.headerclass)
		var persistedheaders=ddaccordion.getCookie(config.headerclass)
		ddaccordion.contentclassname[config.headerclass]=config.contentclass //remember contentclass name based on headerclass
		config.cssclass={collapse: config.toggleclass[0], expand: config.toggleclass[1]} //store expand and contract CSS classes as object properties
		config.htmlsetting={location: config.togglehtml[0], collapse: config.togglehtml[1], expand: config.togglehtml[2]} //store HTML settings as object properties
		config.oninit=(typeof config.oninit=="undefined")? function(){} : config.oninit //attach custom "oninit" event handler
		config.onopenclose=(typeof config.onopenclose=="undefined")? function(){} : config.onopenclose //attach custom "onopenclose" event handler
		var lastexpanded={} //object to hold reference to last expanded header and content (jquery objects)
		var expandedindices=ddaccordion.urlparamselect(config.headerclass) || ((config.persiststate && persistedheaders!=null)? persistedheaders : config.defaultexpanded)
		if (typeof expandedindices=='string') //test for valid cookie ('string'), invalid being null or 1st page load
			expandedindices=expandedindices.replace(/c/ig, '').split(',') //if valid, change to array value
		var $subcontents=$('.'+config["contentclass"])
		if (!(expandedindices instanceof Array)) //check for invalid expandedindices value
			expandedindices=[]
		if (config["collapseprev"] && expandedindices.length>1)
			expandedindices=[expandedindices.pop()] //return last array element as an array (for sake of jQuery.inArray())
		$('.'+config["headerclass"]).each(function(index){ //loop through all headers
			if (/(prefix)|(suffix)/i.test(config.htmlsetting.location) && $(this).html()!=""){ //add a SPAN element to header depending on user setting and if header is a container tag
				$('<span class="accordprefix"></span>').prependTo(this)
				$('<span class="accordsuffix"></span>').appendTo(this)
			}
			$(this).attr('headerindex', index+'h') //store position of this header relative to its peers
			$subcontents.eq(index).attr('contentindex', index+'c') //store position of this content relative to its peers
			var $subcontent=$subcontents.eq(index)
			if (jQuery.inArray(index, expandedindices)!=-1){ //check for headers that should be expanded automatically
				if (config.animatedefault==false)
					$subcontent.show()
				ddaccordion.expandit($(this), $subcontent, config, false) //Last Boolean value sets 'isclicked' parameter
				lastexpanded={$header:$(this), $content:$subcontent}
			}  //end check
			else{
				$subcontent.hide()
				config.onopenclose($(this).get(0), parseInt($(this).attr('headerindex')), $subcontent.css('display'), false) //Last Boolean value sets 'isclicked' parameter
				ddaccordion.transformHeader($(this), config, "collapse")
			}
		})
		$('.'+config["headerclass"]).click(function(){ //assign behavior when headers are clicked on
				var $subcontent=$subcontents.eq(parseInt($(this).attr('headerindex'))) //get subcontent that should be expanded/collapsed
				if ($subcontent.css('display')=="none"){
					ddaccordion.expandit($(this), $subcontent, config, true) //Last Boolean value sets 'isclicked' parameter
					if (config["collapseprev"] && lastexpanded.$header && $(this).get(0)!=lastexpanded.$header.get(0)){ //collapse previous content?
						ddaccordion.collapseit(lastexpanded.$header, lastexpanded.$content, config, true) //Last Boolean value sets 'isclicked' parameter
					}
					lastexpanded={$header:$(this), $content:$subcontent}
				}
				else{
					ddaccordion.collapseit($(this), $subcontent, config, true) //Last Boolean value sets 'isclicked' parameter
				}
				return false
 	})
		config.oninit($('.'+config["headerclass"]).get(), expandedindices)
		$(window).bind('unload', function(){ //clean up and persist on page unload
			$('.'+config["headerclass"]).unbind('click')
			var expandedindices=[]
			$('.'+config["contentclass"]+":visible").each(function(index){ //get indices of expanded headers
				expandedindices.push($(this).attr('contentindex'))
			})
			if (config.persiststate==true){ //persist state?
				expandedindices=(expandedindices.length==0)? '-1c' : expandedindices //No contents expanded, indicate that with dummy '-1c' value?
				//ddaccordion.setCookie(config.headerclass, expandedindices)
			}
		})
	})
	}
}

ddaccordion.init({
	headerclass: "expandable", //Shared CSS class name of headers group that are expandable
	contentclass: "categoryitems", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	//onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	//defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc]. [] denotes no content
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	
	togglehtml: ["prefix", "<img src='images/res_exp.gif' width='9px' height='9px'/>", "<img src='images/res_clsp.gif' width='9' height='9'/>"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "normal", //speed of animation: "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isclicked){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})
(function($)
{$.fn.document=function()
{var element=this.get(0);if(element.nodeName.toLowerCase()=='iframe')
{return element.contentWindow.document;}
return this;};$.fn.documentSelection=function()
{var element=this.get(0);if(element.contentWindow.document.selection)
return element.contentWindow.document.selection.createRange().text;else
return element.contentWindow.getSelection().toString();};$.fn.wysiwyg=function(options)
{if(arguments.length>0&&arguments[0].constructor==String)
{var action=arguments[0].toString();var params=[];for(var i=1;i<arguments.length;i++)
params[i-1]=arguments[i];if(action in Wysiwyg)
{return this.each(function()
{$.data(this,'wysiwyg').designMode();Wysiwyg[action].apply(this,params);});}
else return this;}
var controls={};if(options&&options.controls)
{var controls=options.controls;delete options.controls;}
options=$.extend({html:'<'+'?xml version="1.0" encoding="UTF-8"?'+'><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head><link href="https://fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold,bolditalic" rel="stylesheet" type="text/css"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">STYLE_SHEET</head><body style="margin:10px;font-size:13px;font-family:\'PT Sans\', Arial, sans-serif;color:#000000;">INITIAL_CONTENT</body></html>',css:{},debug:false,autoSave:true,rmUnwantedBr:true,brIE:true,controls:{},messages:{}},options);options.messages=$.extend(true,options.messages,Wysiwyg.MSGS_EN);options.controls=$.extend(true,options.controls,Wysiwyg.TOOLBAR);for(var control in controls)
{if(control in options.controls)
$.extend(options.controls[control],controls[control]);else
options.controls[control]=controls[control];}
return this.each(function()
{Wysiwyg(this,options);});};function Wysiwyg(element,options)
{return this instanceof Wysiwyg?this.init(element,options):new Wysiwyg(element,options);}
$.extend(Wysiwyg,{insertImage:function(szURL,attributes)
{var self=$.data(this,'wysiwyg');if(self.constructor==Wysiwyg&&szURL&&szURL.length>0)
{if($.browser.msie)self.focus();if(attributes)
{self.editorDoc.execCommand('insertImage',false,'#jwysiwyg#');var img=self.getElementByAttributeValue('img','src','#jwysiwyg#');if(img)
{img.src=szURL;for(var attribute in attributes)
{img.setAttribute(attribute,attributes[attribute]);}}}
else
{self.editorDoc.execCommand('insertImage',false,szURL);}}},createLink:function(szURL)
{var self=$.data(this,'wysiwyg');if(self.constructor==Wysiwyg&&szURL&&szURL.length>0)
{var selection=$(self.editor).documentSelection();if(selection.length>0)
{if($.browser.msie)self.focus();self.editorDoc.execCommand('unlink',false,[]);self.editorDoc.execCommand('createLink',false,szURL);}
else if(self.options.messages.nonSelection)
alert(self.options.messages.nonSelection);}},insertHtml:function(szHTML)
{var self=$.data(this,'wysiwyg');if(self.constructor==Wysiwyg&&szHTML&&szHTML.length>0)
{if($.browser.msie)
{self.focus();self.editorDoc.execCommand('insertImage',false,'#jwysiwyg#');var img=self.getElementByAttributeValue('img','src','#jwysiwyg#');if(img)
{$(img).replaceWith(szHTML);}}
else
{self.editorDoc.execCommand('insertHTML',false,szHTML);}}},setContent:function(newContent)
{var self=$.data(this,'wysiwyg');self.setContent(newContent);self.saveContent();},clear:function()
{var self=$.data(this,'wysiwyg');self.setContent('');self.saveContent();},MSGS_EN:{nonSelection:'select the text you wish to link'},TOOLBAR:{bold:{visible:true,tags:['b','strong'],css:{fontWeight:'bold'},tooltip:"Bold"},italic:{visible:true,tags:['i','em'],css:{fontStyle:'italic'},tooltip:"Italic"},strikeThrough:{visible:true,tags:['s','strike'],css:{textDecoration:'line-through'},tooltip:"Strike-through"},underline:{visible:true,tags:['u'],css:{textDecoration:'underline'},tooltip:"Underline"},separator00:{visible:false,separator:true},insertOrderedList:{visible:true,tags:['ol'],tooltip:"Insert Ordered List"},insertUnorderedList:{visible:true,tags:['ul'],tooltip:"Insert Unordered List"},separator01:{visible:true,separator:true},indent:{visible:true,tooltip:"Indent"},outdent:{visible:true,tooltip:"Outdent"}}});$.extend(Wysiwyg.prototype,{original:null,options:{},element:null,editor:null,focus:function()
{$(this.editorDoc.body).focus();},init:function(element,options)
{var self=this;this.editor=element;this.options=options||{};$.data(element,'wysiwyg',this);var newX=element.width||element.clientWidth;var newY=element.height||element.clientHeight;if(element.nodeName.toLowerCase()=='textarea')
{this.original=element;if(newX==0&&element.cols)
newX=(element.cols*8)+21;if(newY==0&&element.rows)
newY=(element.rows*16)+16;var editor=this.editor=$('<iframe src="javascript:false;"></iframe>').css({minHeight:(newY-6).toString()+'px',width:(newX-8).toString()+'px'}).attr('id',$(element).attr('id')+'IFrame').attr('frameborder','0');this.editor.attr('tabindex',$(element).attr('tabindex'));if($.browser.msie)
{this.editor.css('height',(newY).toString()+'px');}}
var panel=this.panel=$('<ul role="menu" class="panel"></ul>');this.appendControls();this.element=$('<div></div>').css({width:(newX>0)?(newX).toString()+'px':'100%'}).addClass('wysiwyg').append(panel).append($('<div><!-- --></div>').css({clear:'both'})).append(editor);$(element).hide().before(this.element);this.viewHTML=false;this.initialHeight=newY-8;this.initialContent=$(element).val();this.initFrame();if(this.initialContent.length==0)
this.setContent('');var form=$(element).closest('form');if(this.options.autoSave)
{form.submit(function(){self.saveContent();});}
form.bind('reset',function()
{self.setContent(self.initialContent);self.saveContent();});},initFrame:function()
{var self=this;var style='';if(this.options.css&&this.options.css.constructor==String)
{style='<link rel="stylesheet" type="text/css" media="screen" href="'+this.options.css+'" />';}
this.editorDoc=$(this.editor).document();this.editorDoc_designMode=false;try{this.editorDoc.designMode='on';this.editorDoc_designMode=true;}catch(e){$(this.editorDoc).focus(function()
{self.designMode();});}
this.editorDoc.open();this.editorDoc.write(this.options.html.replace(/INITIAL_CONTENT/,function(){return self.initialContent;}).replace(/STYLE_SHEET/,function(){return style;}));this.editorDoc.close();this.editorDoc.contentEditable='true';if($.browser.msie)
{setTimeout(function(){$(self.editorDoc.body).css('border','none');},0);}
$(this.editorDoc).click(function(event)
{self.checkTargets(event.target?event.target:event.srcElement);});$(this.original).focus(function()
{if(!$.browser.msie)
{self.focus();}});if(this.options.autoSave)
{$(this.editorDoc).keydown(function(){self.saveContent();}).keyup(function(){self.saveContent();}).mousedown(function(){self.saveContent();});}
if(this.options.css)
{setTimeout(function()
{if(self.options.css.constructor==String)
{}
else
$(self.editorDoc).find('body').css(self.options.css);},0);}
$(this.editorDoc).keydown(function(event)
{if($.browser.msie&&self.options.brIE&&event.keyCode==13)
{var rng=self.getRange();rng.pasteHTML('<br />');rng.collapse(false);rng.select();return false;}
return true;});},designMode:function()
{if(!(this.editorDoc_designMode))
{try{this.editorDoc.designMode='on';this.editorDoc_designMode=true;}catch(e){}}},getSelection:function()
{return(window.getSelection)?window.getSelection():document.selection;},getRange:function()
{var selection=this.getSelection();if(!(selection))
return null;return(selection.rangeCount>0)?selection.getRangeAt(0):selection.createRange();},getContent:function()
{return $($(this.editor).document()).find('body').html();},setContent:function(newContent)
{$($(this.editor).document()).find('body').html(newContent);},saveContent:function()
{if(this.original)
{var content=this.getContent();if(this.options.rmUnwantedBr)
{content=(content.substr(-4)=='<br>')?content.substr(0,content.length-4):content;}
$(this.original).val(content);}},withoutCss:function()
{if($.browser.mozilla)
{try
{this.editorDoc.execCommand('styleWithCSS',false,false);}
catch(e)
{try
{this.editorDoc.execCommand('useCSS',false,true);}
catch(e)
{}}}},appendMenu:function(cmd,args,className,fn,tooltip)
{var self=this;args=args||[];$('<li></li>').append($('<a role="menuitem" tabindex="-1" href="javascript:;">'+(className||cmd)+'</a>').addClass(className||cmd).attr('title',tooltip)).click(function(){if(fn)fn.apply(self);else
{self.withoutCss();self.editorDoc.execCommand(cmd,false,args);}
if(self.options.autoSave)self.saveContent();}).appendTo(this.panel);},appendMenuSeparator:function()
{$('<li role="separator" class="separator"></li>').appendTo(this.panel);},appendControls:function()
{for(var name in this.options.controls)
{var control=this.options.controls[name];if(control.separator)
{if(control.visible!==false)
this.appendMenuSeparator();}
else if(control.visible)
{this.appendMenu(control.command||name,control.arguments||[],control.className||control.command||name||'empty',control.exec,control.tooltip||control.command||name||'');}}},checkTargets:function(element)
{for(var name in this.options.controls)
{var control=this.options.controls[name];var className=control.className||control.command||name||'empty';$('.'+className,this.panel).removeClass('active');if(control.tags)
{var elm=element;do{if(elm.nodeType!=1)
break;if($.inArray(elm.tagName.toLowerCase(),control.tags)!=-1)
$('.'+className,this.panel).addClass('active');}while((elm=elm.parentNode));}
if(control.css)
{var elm=$(element);do{if(elm[0].nodeType!=1)
break;for(var cssProperty in control.css)
if(elm.css(cssProperty).toString().toLowerCase()==control.css[cssProperty])
$('.'+className,this.panel).addClass('active');}while((elm=elm.parent()));}}},getElementByAttributeValue:function(tagName,attributeName,attributeValue)
{var elements=this.editorDoc.getElementsByTagName(tagName);for(var i=0;i<elements.length;i++)
{var value=elements[i].getAttribute(attributeName);if($.browser.msie)
{value=value.substr(value.length-attributeValue.length);}
if(value==attributeValue)
return elements[i];}
return false;}});})(jQuery);
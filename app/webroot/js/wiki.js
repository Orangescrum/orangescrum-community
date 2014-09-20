/*
WICK: Web Input Completion Kit
http://wick.sourceforge.net/
Copyright (c) 2004, Christopher T. Holland
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of the Christopher T. Holland, nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/
/* start dhtml building blocks */
function freezeEvent(e) {
if (e.preventDefault) e.preventDefault();
e.returnValue = false;
e.cancelBubble = true;
if (e.stopPropagation) e.stopPropagation();
return false;
}//freezeEvent

function isWithinNode(e,i,c,t,obj) {
answer = false;
te = e;
while(te && !answer) {
	if	((te.id && (te.id == i)) || (te.className && (te.className == i+"Class"))
			|| (!t && c && te.className && (te.className == c))
			|| (!t && c && te.className && (te.className.indexOf(c) != -1))
			|| (t && te.tagName && (te.tagName.toLowerCase() == t))
			|| (obj && (te == obj))
		) {
		answer = te;
	} else {
		te = te.parentNode;
	}
}
return te;
}//isWithinNode

function getEvent(event) {
return (event ? event : window.event);
}//getEvent()

function getEventElement(e) {
return (e.srcElement ? e.srcElement: (e.target ? e.target : e.currentTarget));
}//getEventElement()

function findElementPosX(obj) {
	curleft = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			curleft += obj.offsetLeft;
			obj = obj.offsetParent;
		}
	}//if offsetParent exists
	else if (obj.x)
		curleft += obj.x
	return curleft;
}//findElementPosX

function findElementPosY(obj) {
	curtop = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			curtop += obj.offsetTop;
			obj = obj.offsetParent;
		}
	}//if offsetParent exists
	else if (obj.y)
		curtop += obj.y
	return curtop;
}//findElementPosY

/* end dhtml building blocks */

function handleKeyPress(event) {
e = getEvent(event);
eL = getEventElement(e);

upEl = isWithinNode(eL,null,"wickEnabled",null,null);

kc = e["keyCode"];

if (siw && ((kc == 13) || (kc == 9))) {
	siw.selectingSomething = true;
	if (siw.isSafari) siw.inputBox.blur();   //hack to "wake up" safari
	siw.inputBox.focus();
	siw.inputBox.value = siw.inputBox.value.replace(/[ \r\n\t\f\s]+$/gi,' ');
	hideSmartInputFloater();
} else if (upEl && (kc != 38) && (kc != 40) && (kc != 37) && (kc != 39) && (kc != 13) && (kc != 27)) {
	if (!siw || (siw && !siw.selectingSomething)) {
		processSmartInput(upEl);
	}
} else if (siw && siw.inputBox) {
	siw.inputBox.focus(); //kinda part of the hack.
}

}//handleKeyPress()


function handleKeyDown(event) {
e = getEvent(event);
eL = getEventElement(e);

if (siw && (kc = e["keyCode"])) {
	if (kc == 40) {
		siw.selectingSomething = true;
		freezeEvent(e);
		if (siw.isGecko) siw.inputBox.blur(); /* Gecko hack */
		selectNextSmartInputMatchItem();
	} else if (kc == 38) {
		siw.selectingSomething = true;
		freezeEvent(e);
		if (siw.isGecko) siw.inputBox.blur();
		selectPreviousSmartInputMatchItem();
	} else if ((kc == 13) || (kc == 9)) {
		siw.selectingSomething = true;
		activateCurrentSmartInputMatch();
		freezeEvent(e);
	} else if (kc == 27)  {
		hideSmartInputFloater();
		freezeEvent(e);
	} else {
		siw.selectingSomething = false;
	}
}

}//handleKeyDown()

function handleFocus(event) {
	e = getEvent(event);
	eL = getEventElement(e);
	if (focEl = isWithinNode(eL,null,"wickEnabled",null,null)) {
	if (!siw || (siw && !siw.selectingSomething)) processSmartInput(focEl);
	}
}//handleFocus()

function handleBlur(event) {
	e = getEvent(event);
	eL = getEventElement(e);
	if (blurEl = isWithinNode(eL,null,"wickEnabled",null,null)) {
		if (siw && !siw.selectingSomething) hideSmartInputFloater();
	}
}//handleBlur()

function handleClick(event) {
	e2 = getEvent(event);
	eL2 = getEventElement(e2);
	if (siw && siw.selectingSomething) {
		selectFromMouseClick();
	}
}//handleClick()

function handleMouseOver(event) {
	e = getEvent(event);
	eL = getEventElement(e);
	if (siw && (mEl = isWithinNode(eL,null,"matchedSmartInputItem",null,null))) {
		siw.selectingSomething = true;
		selectFromMouseOver(mEl);
	} else if (isWithinNode(eL,null,"siwCredit",null,null)) {
		siw.selectingSomething = true;
	}else if (siw) {
		siw.selectingSomething = false;
	}
}//handleMouseOver

function showSmartInputFloater() {
if (!siw.floater.style.display || (siw.floater.style.display=="none")) {
	if (!siw.customFloater) {
		x = findElementPosX(siw.inputBox);
		y = findElementPosY(siw.inputBox) + siw.inputBox.offsetHeight;
		//hack: browser-specific adjustments.
		if (!siw.isGecko && !siw.isWinIE) x += 8;
		if (!siw.isGecko && !siw.isWinIE) y += 10;
		siw.floater.style.left = x;
		siw.floater.style.top = y;
	} else {
	//you may
	//do additional things for your custom floater
	//beyond setting display and visibility
	}
	siw.floater.style.display="block";
	siw.floater.style.visibility="visible";
}
}//showSmartInputFloater()

function hideSmartInputFloater() {
if (siw) {
siw.floater.style.display="none";
siw.floater.style.visibility="hidden";
siw = null;
}//siw exists
}//hideSmartInputFloater

function processSmartInput(inputBox) {
if (!siw) siw = new smartInputWindow();
siw.inputBox = inputBox;

classData = inputBox.className.split(" ");
siwDirectives = null;
for (i=0;(!siwDirectives && classData[i]);i++) {
	if (classData[i].indexOf("wickEnabled") != -1)
		siwDirectives = classData[i];
}

if (siwDirectives && (siwDirectives.indexOf(":") != -1)) {
siw.customFloater = true;
newFloaterId = siwDirectives.split(":")[1];
siw.floater = document.getElementById(newFloaterId);
siw.floaterContent = siw.floater.getElementsByTagName("div")[0];
}


setSmartInputData();
if (siw.matchCollection && (siw.matchCollection.length > 0)) selectSmartInputMatchItem(0);
content = getSmartInputBoxContent();
if (content) {
	modifySmartInputBoxContent(content);
	showSmartInputFloater();
} else hideSmartInputFloater();
}//processSmartInput()

function smartInputMatch(cleanValue, value) {
	this.cleanValue = cleanValue;
	this.value = value;
	this.isSelected = false;
}//smartInputMatch

function simplify(s) {
return s.toLowerCase().replace(/^[ \s\f\t\n\r]+/,'').replace(/[ \s\f\t\n\r]+$/,'');
//.replace(/[�,�,�,�,\u00E9,\u00E8,\u00EA,\u00EB]/gi,"e").replace(/[�,�,\u00E0,\u00E2]/gi,"a").
}//simplify

function getUserInputToMatch(s) {
a = s;
fields = s.split(",");
if (fields.length > 0) a = fields[fields.length - 1];
return a;
}//getUserInputToMatch

function getUserInputBase() {
s = siw.inputBox.value;
a = s;
if ((lastComma = s.lastIndexOf(",")) != -1) {
	a = a.replace(/^(.*\,[ \r\n\t\f\s]*).*$/i,'$1');
}
else
	a = "";
return a;
}//getUserInputBase()

function runMatchingLogic(userInput, standalone) {
	userInput = simplify(userInput);
	uifc = userInput.charAt(0).toLowerCase();
	if (uifc == '"') uifc = (n = userInput.charAt(1)) ? n.toLowerCase() : "z";
	if (standalone) userInput = uifc;
	if (siw) siw.matchCollection = new Array();
	pointerToCollectionToUse = collection;
	if (siw && siw.revisedCollection && (siw.revisedCollection.length > 0) && siw.lastUserInput && (userInput.indexOf(siw.lastUserInput) == 0)) {
		pointerToCollectionToUse = siw.revisedCollection;
	} else if (collectionIndex[userInput] && (collectionIndex[userInput].length > 0)) {
		pointerToCollectionToUse = collectionIndex[userInput];
	} else if (collectionIndex[uifc] && (collectionIndex[uifc].length > 0)) {
		pointerToCollectionToUse = collectionIndex[uifc];
	} else if (siw && (userInput.length == 1) && (!collectionIndex[uifc])) {
		siw.buildIndex = true;
	} else if (siw) {
		siw.buildIndex = false;
	}
	
	tempCollection = new Array();

	re1m = new RegExp("^([ \"\>\<\-]*)("+userInput+")","i");
	re2m = new RegExp("([ \"\>\<\-]+)("+userInput+")","i");
	re1 = new RegExp("^([ \"\}\{\-]*)("+userInput+")","gi");
	re2 = new RegExp("([ \"\}\{\-]+)("+userInput+")","gi");
	
	for (i=0,j=0;(i<pointerToCollectionToUse.length);i++) {
		displayMatches = ((!standalone) && (j < siw.MAX_MATCHES));
		entry = pointerToCollectionToUse[i];
		mEntry = simplify(entry);
		if (!standalone && (mEntry.indexOf(userInput) == 0)) {
			userInput = userInput.replace(/\>/gi,'\\}').replace(/\< ?/gi,'\\{');
			re = new RegExp("(" + userInput + ")","i");
			if (displayMatches) {
				siw.matchCollection[j] = new smartInputMatch(entry, mEntry.replace(/\>/gi,'}').replace(/\< ?/gi,'{').replace(re,"<b>$1</b>"));
			}
			tempCollection[j] = entry;
			j++;		
		} else if (mEntry.match(re1m) || mEntry.match(re2m)) {
			if (!standalone && displayMatches) {
				siw.matchCollection[j] = new smartInputMatch(entry, mEntry.replace(/\>/gi,'}').replace(/\</gi,'{').replace(re1,"$1<b>$2</b>").replace(re2,"$1<b>$2</b>"));
			}
			tempCollection[j] = entry;
			j++;
		}
	}//loop thru collection
	if (siw) {
		siw.lastUserInput = userInput;
		siw.revisedCollection = tempCollection.join(",").split(",");
		collectionIndex[userInput] = tempCollection.join(",").split(",");
	}
	if (standalone || siw.buildIndex) {
		collectionIndex[uifc] = tempCollection.join(",").split(",");
		if (siw) siw.buildIndex = false;
	}
}//runMatchingLogic

function setSmartInputData() {
if (siw) {
orgUserInput = siw.inputBox.value;
orgUserInput = getUserInputToMatch(orgUserInput);
userInput = orgUserInput.toLowerCase().replace(/[\r\n\t\f\s]+/gi,' ').replace(/^ +/gi,'').replace(/ +$/gi,'').replace(/ +/gi,' ').replace(/\\/gi,'').replace(/\[/gi,'').replace(/\(/gi,'').replace(/\./gi,'\.').replace(/\?/gi,'');
if (userInput && (userInput != "") && (userInput != '"')) {
	runMatchingLogic(userInput);
}//if userinput not blank and is meaningful
else {
siw.matchCollection = null;
}
}//siw exists ... uhmkaaayyyyy
}//setSmartInputData

function getSmartInputBoxContent() {
a = null;
if (siw && siw.matchCollection && (siw.matchCollection.length > 0)) {
a = '';
for (i = 0;i < siw.matchCollection.length; i++) {
selectedString = siw.matchCollection[i].isSelected ? ' selectedSmartInputItem' : '';
a += '<p class="matchedSmartInputItem' + selectedString + '">' + siw.matchCollection[i].value.replace(/\{ */gi,"&lt;").replace(/\} */gi,"&gt;") + '</p>';
}//
}//siw exists
return a;
}//getSmartInputBoxContent

function modifySmartInputBoxContent(content) {
//todo: remove credits 'cuz no one gives a shit ;] - done
siw.floaterContent.innerHTML = '<div id="smartInputResults">' + content + (siw.showCredit ? ('') : '') +'</div>';
siw.matchListDisplay = document.getElementById("smartInputResults");
}//modifySmartInputBoxContent()

function selectFromMouseOver(o) {
currentIndex = getCurrentlySelectedSmartInputItem();
if (currentIndex != null) deSelectSmartInputMatchItem(currentIndex);
newIndex = getIndexFromElement(o);
selectSmartInputMatchItem(newIndex);
modifySmartInputBoxContent(getSmartInputBoxContent());
}//selectFromMouseOver

function selectFromMouseClick() {
activateCurrentSmartInputMatch();
siw.inputBox.focus();
 var el = $("#members_list").get(0);
 var elemLen = el.value.length;
el.selectionStart = elemLen;
el.selectionEnd = elemLen;
el.focus();
hideSmartInputFloater();
}//selectFromMouseClick

function getIndexFromElement(o) {
index = 0;
while(o = o.previousSibling) {
index++;
}//
return index;
}//getIndexFromElement

function getCurrentlySelectedSmartInputItem() {
answer = null;
for (i = 0; ((i < siw.matchCollection.length) && !answer) ; i++) {
	if (siw.matchCollection[i].isSelected)
		answer = i;
}//
return answer;
}//getCurrentlySelectedSmartInputItem

function selectSmartInputMatchItem(index) {
	siw.matchCollection[index].isSelected = true;
}//selectSmartInputMatchItem()

function deSelectSmartInputMatchItem(index) {
	siw.matchCollection[index].isSelected = false;
}//deSelectSmartInputMatchItem()

function selectNextSmartInputMatchItem() {
currentIndex = getCurrentlySelectedSmartInputItem();
if (currentIndex != null) {
	deSelectSmartInputMatchItem(currentIndex);
	if ((currentIndex + 1) < siw.matchCollection.length)
 		selectSmartInputMatchItem(currentIndex + 1);
	else
		selectSmartInputMatchItem(0);
} else {
	selectSmartInputMatchItem(0);
}
modifySmartInputBoxContent(getSmartInputBoxContent());
}//selectNextSmartInputMatchItem

function selectPreviousSmartInputMatchItem() {
currentIndex = getCurrentlySelectedSmartInputItem();
if (currentIndex != null) {
	deSelectSmartInputMatchItem(currentIndex);
	if ((currentIndex - 1) >= 0)
 		selectSmartInputMatchItem(currentIndex - 1);
	else
		selectSmartInputMatchItem(siw.matchCollection.length - 1);
} else {
	selectSmartInputMatchItem(siw.matchCollection.length - 1);
}
modifySmartInputBoxContent(getSmartInputBoxContent());
}//selectPreviousSmartInputMatchItem

function activateCurrentSmartInputMatch() {
	baseValue = getUserInputBase();
	if ((selIndex = getCurrentlySelectedSmartInputItem()) != null) {
		//console.log(matchCollection);
		addedValue = siw.matchCollection[selIndex].cleanValue;
		valiedemail=  addedValue.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
		theString = (baseValue ? baseValue : "") + valiedemail[0] + ", ";
		siw.inputBox.value = theString;
		runMatchingLogic(addedValue, true);
	}
}//activateCurrentSmartInputMatch

function smartInputWindow () {
	this.customFloater = false;
	this.floater = document.getElementById("smartInputFloater");
	this.floaterContent = document.getElementById("smartInputFloaterContent");
	this.selectedSmartInputItem = null;
	this.MAX_MATCHES = 15;
	this.isGecko = (navigator.userAgent.indexOf("Gecko/200") != -1);
	this.isSafari = (navigator.userAgent.indexOf("Safari") != -1);
	this.isWinIE = ((navigator.userAgent.indexOf("Win") != -1 ) && (navigator.userAgent.indexOf("MSIE") != -1 ));
	this.showCredit = false;
}//smartInputWindow Object

function registerSmartInputListeners() {
//inputs = document.getElementsByTagName("input");
texts = document.getElementsByTagName("textarea");
//texts = document.getElementsById(textid);
allinputs = new Array();
z = 0;
y = 0;
/*while(inputs[z]) {
allinputs[z] = inputs[z];
z++;
}*///
while(texts[y]) {
allinputs[z] = texts[y];
z++;
y++;
}//

for (i=0; i < allinputs.length;i++) {
	if ((c = allinputs[i].className) && (c == "wickEnabled")) {
		allinputs[i].setAttribute("autocomplete","OFF");
		allinputs[i].onfocus = handleFocus;
		allinputs[i].onblur = handleBlur;
		allinputs[i].onkeydown = handleKeyDown;
		allinputs[i].onkeyup = handleKeyPress;
	}
}//loop thru inputs
}//registerSmartInputListeners


function debug(s) {
ds += ( s + "\n");
}

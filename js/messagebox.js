messageObj = new DHTML_modalMessage();	// We only create one object of this class
messageObj.setShadowOffset(5);	// Large shadow


var messageTypeInformation = 0;
var messageTypeWarning = 1;
var messageTypeError = 2;
var messageTypeConfirmation = 3;

var messageOK = typeof(rslocmessageOK) == "undefined" ? "OK" : rslocmessageOK;
var messageCancel = typeof(rslocmessageCancel) == "undefined" ? "Abbrechen" : rslocmessageCancel;
var messageYes = typeof(rslocmessageYes) == "undefined" ? "Ja" : rslocmessageYes;
var messageNo = typeof(rslocmessageNo) == "undefined" ? "Nein" : rslocmessageNo;

var messageConfirmationResult = false;
var promptResult = "";

function MessageBox(messageTitle, messageContent, messageType, messageBoxWidth, messageBoxHeight, messageConfirmationEvent, CloseOnEscape) {

  if(CloseOnEscape == null)
    CloseOnEscape = true;

  messageBoxWidth += 12; // margin
  messageBoxHeight += 60;
  if(navigator.userAgent.indexOf("Firefox") < 0 && navigator.userAgent.indexOf("Netscape") < 0)
    messageBoxHeight -= 20;
  var MSIE10newer = false;

  if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
     var ieversion = new Number(RegExp.$1);
     if(ieversion > 9)
      MSIE10newer = true;
     if(ieversion <= 7)
       messageBoxHeight += 20; // Kompatibilitaetsmodus??
  }

//  if(MSIE10newer)
//     messageBoxHeight += 30;

  if (messageType > 3)
    messageType = 0;

  html =
  '<html><head><title>' + messageTitle + ' </title></head><body>' +
  '<form name="MessageBoxForm" id="MessageBoxForm">' +
  '<table border="0" onselectstart="return false;" style="cursor: default;-moz-user-select: -moz-none;-khtml-user-select: none;-webkit-user-select: none;user-select: none;">';


  if (messageTitle != "") {
    html +=
    '		<tr>' +
    '<td colspan="2" style="height: 21px; background: transparent url(images/menu_bg.jpg) top left repeat-x; color: #FFFFFF;">&nbsp;&nbsp;' + messageTitle + '</td>' +
    '		</tr>';
  }

  html +=
  '		<tr>' +
  '<td width="30">&nbsp;</td>' +
  '<td>&nbsp;</td>' +
  '		</tr>';

  html += '	<tr> ';

  if(messageType == messageTypeInformation)
    html +=  '			<td width="30" valign="top"><img border="0" src="images/icon_information.gif" width="24" height="24" style="margin: 4px; margin-right: 8px" />&nbsp;</td>';
  if(messageType == messageTypeWarning)
    html +=  '			<td width="30" valign="top"><img border="0" src="images/icon_warning.gif" width="24" height="24" style="margin: 4px; margin-right: 8px" />&nbsp;</td>';
  if(messageType == messageTypeError)
    html +=  '			<td width="30" valign="top"><img border="0" src="images/icon_error.gif" width="24" height="24" style="margin: 4px; margin-right: 8px" />&nbsp;</td>';
  if(messageType == messageTypeConfirmation)
    html +=  '			<td width="30" valign="top"><img border="0" src="images/icon_confirmation.gif" width="24" height="24" style="margin: 4px; margin-right: 8px" />&nbsp;</td>';

  html +=
  '			<td>' + messageContent + '</td>' +
  '		</tr>';


  html +=
  '		<tr>' +
  '<td width="30">&nbsp;</td>' +
  '<td>&nbsp;</td>' +
  '		</tr>';

    html +=
    '		<tr>' +
    '<td colspan="2" style=""><hr color="#7795BD" noshade="noshade" size="1" style="border:none; border-top:1px #7795BD solid; height: 1px;" /></td>' +
    '		</tr>';

  html +=
  '		<tr>';


  if(messageType == messageTypeConfirmation) {
    if(messageConfirmationEvent == "")
      html += '			<td colspan="2" width="100%" align="right"><input type="button" value="' + messageYes + '" style="width:60px" onclick="closeMessage();MessageVerify(true)">' +
      '			<input type="button" value="' + messageNo + '" style="width:60px" onclick="closeMessage();MessageVerify(false)"></td>';
      else
      html += '			<td colspan="2" width="100%" align="right"><input type="button" value="' + messageYes + '" style="width:60px" onclick="closeMessage();' + messageConfirmationEvent + '(true, this.form)">' +
      '			<input type="button" value="' + messageNo + '" style="width:60px" onclick="closeMessage();' + messageConfirmationEvent + '(false, this.form)"></td>';
  } else {
    if(!messageConfirmationEvent || messageConfirmationEvent == "undefined" || messageConfirmationEvent == "")
       html += '<td colspan="2" align="center"><input type="button" value="' + messageOK + '" style="width:60px" onclick="closeMessage();"></td>';
       else
       html += '<td colspan="2" align="center"><input type="button" value="' + messageOK + '" style="width:60px" onclick="closeMessage();' + messageConfirmationEvent + '(false, this.form)"></td>';
  }

  html +=
  '		</tr>' +
  '	</table>' +
  '</form></body></html>';
  
  messageObj.CloseOnEscape = CloseOnEscape;
  displayMessageText(html, messageBoxWidth, messageBoxHeight);

}

function MessagePrompt(messageTitle, messageContent, defaultValue, messageBoxWidth, messageBoxHeight, promptCallbackEvent, CloseOnEscape, arrPlaceholderItems, defaultPlaceholderItemsSelectedOption) {

  if(CloseOnEscape == null)
    CloseOnEscape = true;

  messageBoxWidth += 12; // margin
  messageBoxHeight += 60;
  if(navigator.userAgent.indexOf("Firefox") < 0 && navigator.userAgent.indexOf("Netscape") < 0)
    messageBoxHeight -= 20;
  var MSIE10newer = false;

  if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
     var ieversion = new Number(RegExp.$1);
     if(ieversion > 9)
      MSIE10newer = true;
     if(ieversion <= 7)
       messageBoxHeight += 20; // Kompatibilitaetsmodus??
  }

  html =
  '<html><head><title>' + messageTitle + ' </title></head><body>' +
  '<form name="MessagePromptForm" id="MessagePromptForm" onsubmit="return false;">' +
  '<table border="0" width="100%" onselectstart="return false;" style="cursor: default;-moz-user-select: -moz-none;-khtml-user-select: none;-webkit-user-select: none;user-select: none;">';


  if (messageTitle != "") {
    html +=
    '		<tr>' +
    '<td colspan="2" style="height: 21px; background: transparent url(images/menu_bg.jpg) top left repeat-x; color: #FFFFFF;">&nbsp;&nbsp;' + messageTitle + '</td>' +
    '		</tr>';
  }

  html +=
  '		<tr>' +
  '<td width="30">&nbsp;</td>' +
  '<td>&nbsp;</td>' +
  '		</tr>';

  html += '	<tr> ';

  html +=  '			<td width="30" valign="top"><img border="0" src="images/icon_confirmation.gif" width="24" height="24" style="margin: 4px; margin-right: 8px" />&nbsp;</td>';

  html +=
  '			<td>' + messageContent + '</td>' +
  '		</tr>';


  var inputwidth=messageBoxWidth - 48;

  if(arrPlaceholderItems != null){

  var temp =

  '<select size="1" id="messagePromptPlaceHoldersCB" onchange="InsertFieldValue(\'messagePromptPlaceHoldersCB\', \'promptValue\' )" style="max-width: ' + inputwidth + 'px; margin-left: 24px;">' +
  '<option selected="selected">' + defaultPlaceholderItemsSelectedOption + '</option>' +
  '</select>';

  html +=
  '		<tr>' +
  '			<td colspan="2">' + temp + '</td>' +
  '		</tr>';

  }

  html +=
  '		<tr>' +
  '			<td colspan="2"><input tabindex="1" type="text" id="promptValue" name= "promptValue" value="' + defaultValue + '" style="margin-left: 24px;width: '  + inputwidth + 'px; " /></td>' +
  '		</tr>';

  html +=
  '		<tr>' +
  '<td width="30">&nbsp;</td>' +
  '<td>&nbsp;</td>' +
  '		</tr>';

  html +=
    '		<tr>' +
    '<td colspan="2" style=""><hr color="#7795BD" noshade="noshade" size="1" style="border:none; border-top:1px #7795BD solid; height: 1px;" /></td>' +
    '		</tr>';

  html +=
  '		<tr>';


  html += '			<td colspan="2" width="100%" align="right"><input tabindex="2" id="defaultButton" type="submit" value="' + messageOK + '" style="width:60px" onclick="closeMessage();' + promptCallbackEvent + '(true, document.getElementById(\'promptValue\').value);">' +
      '			<input tabindex="3" type="button" value="' + messageCancel + '" style="width:80px" onclick="closeMessage();' + promptCallbackEvent + '(false, null);"></td>';

  html +=
  '		</tr>' +
  '	</table>' +


  '</form>';

  html += '</body></html>';


  messageObj.CloseOnEscape = CloseOnEscape;
  messageObj.defaultButtonOnReturnKey = true;
  promptResult = "";
  displayMessageText(html, messageBoxWidth, messageBoxHeight);
  messagePromptFillCB("messagePromptPlaceHoldersCB", arrPlaceholderItems);
  messageObj.setFocus("promptValue");
}

function MessagePromptMultiLine(messageTitle, messageContent, defaultValue, messageBoxWidth, messageBoxHeight, promptCallbackEvent, CloseOnEscape) {

  if(CloseOnEscape == null)
    CloseOnEscape = true;

  messageBoxWidth += 12; // margin
  messageBoxHeight += 60;
  if(navigator.userAgent.indexOf("Firefox") < 0 && navigator.userAgent.indexOf("Netscape") < 0)
    messageBoxHeight -= 20;
  var MSIE10newer = false;

  if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
     var ieversion = new Number(RegExp.$1);
     if(ieversion > 9)
      MSIE10newer = true;
     if(ieversion <= 7)
       messageBoxHeight += 20; // Kompatibilitaetsmodus??
  }

  messageBoxHeight += messageBoxHeight / 3;

  html =
  '<html><head><title>' + messageTitle + ' </title></head><body>' +
  '<form name="MessagePromptMultiLineForm" id="MessagePromptMultiLineForm" onsubmit="return false;">' +
  '<table border="0" width="100%" onselectstart="return false;" style="cursor: default;-moz-user-select: -moz-none;-khtml-user-select: none;-webkit-user-select: none;user-select: none;">';


  if (messageTitle != "") {
    html +=
    '		<tr>' +
    '<td colspan="2" style="height: 21px; background: transparent url(images/menu_bg.jpg) top left repeat-x; color: #FFFFFF;">&nbsp;&nbsp;' + messageTitle + '</td>' +
    '		</tr>';
  }

  html +=
  '		<tr>' +
  '<td width="30">&nbsp;</td>' +
  '<td>&nbsp;</td>' +
  '		</tr>';

  html += '	<tr> ';

  html +=  '			<td width="30" valign="top"><img border="0" src="images/icon_confirmation.gif" width="24" height="24" style="margin: 4px; margin-right: 8px" />&nbsp;</td>';

  html +=
  '			<td>' + messageContent + '</td>' +
  '		</tr>';

  var inputwidth=messageBoxWidth - 48;
  html +=
  '		<tr>' +
  '			<td colspan="2"><textarea tabindex="1" id="promptValue" name= "promptValue" style="margin-left: 24px;width: '  + inputwidth + 'px; height: ' + messageBoxHeight / 3 + 'px; resize: none; " >' + defaultValue + '</textarea></td>' +
  '		</tr>';

  html +=
  '		<tr>' +
  '<td width="30">&nbsp;</td>' +
  '<td>&nbsp;</td>' +
  '		</tr>';

    html +=
    '		<tr>' +
    '<td colspan="2" style=""><hr color="#7795BD" noshade="noshade" size="1" style="border:none; border-top:1px #7795BD solid; height: 1px;" /></td>' +
    '		</tr>';

  html +=
  '		<tr>';


  html += '			<td colspan="2" width="100%" align="right"><input tabindex="2" id="defaultButton" type="submit" value="' + messageOK + '" style="width:60px" onclick="closeMessage();' + promptCallbackEvent + '(true, document.getElementById(\'promptValue\').value);">' +
      '			<input tabindex="3" type="button" value="' + messageCancel + '" style="width:80px" onclick="closeMessage();' + promptCallbackEvent + '(false, null);"></td>';

  html +=
  '		</tr>' +
  '	</table>' +


  '</form></body></html>';

  messageObj.CloseOnEscape = CloseOnEscape;
  messageObj.defaultButtonOnReturnKey = true;
  promptResult = "";
  displayMessageText(html, messageBoxWidth, messageBoxHeight);
  messageObj.setFocus("promptValue");
}

function MessageVerify(ver){

	if(ver){
		// Confirmed message, i.e. clicked on "Yes"
		//alert('Message confirmed');
		messageConfirmationResult = true;
	}else{
		// Clicked on "No"
		//alert('Message not confirmed');
		messageConfirmationResult = false;
	}
}

// icon_information.gif

function displayMessage(url)
{

	messageObj.setSource(url);
	messageObj.setCssClassMessageBox(false);
	messageObj.setSize(400,200);
	messageObj.setShadowDivVisible(true);	// Enable shadow for these boxes
	messageObj.display();
}

function displayMessageBoxSized(url, width, height)
{

	messageObj.setSource(url);
	messageObj.setCssClassMessageBox(false);
	messageObj.setSize(width,height);
	messageObj.setShadowDivVisible(true);	// Enable shadow for these boxes
	messageObj.display();
}

function displayMessageText(atext, messageBoxWidth, messageBoxHeight)
{

	messageObj.setHtmlContent(atext);
	messageObj.setCssClassMessageBox(false);
	messageObj.setSize(messageBoxWidth, messageBoxHeight);
	messageObj.setShadowDivVisible(true);	// Enable shadow for these boxes
	messageObj.setSource(false);	// no html source since we want to use a static message here.
	messageObj.display();
}

function displayStaticMessage(messageContent,cssClass)
{
	messageObj.setHtmlContent(messageContent);
	messageObj.setSize(300,150);
	messageObj.setCssClassMessageBox(cssClass);
	messageObj.setSource(false);	// no html source since we want to use a static message here.
	messageObj.setShadowDivVisible(false);	// Disable shadow for these boxes
	messageObj.display();


}

function closeMessage()
{
	messageObj.close();
}

function messagePrompthtml_entity_decode( string ) {
    var ret, tarea = document.createElement('textarea');
    tarea.innerHTML = string;
    ret = tarea.value;
    try {tarea.removeNode(false);}catch(e) {}
    return ret;
}


function messagePromptFillCB(CBId, arrPlaceholderItems){
   if(arrPlaceholderItems == null) return;
   var CB = document.getElementById(CBId);
   if(CB == null) return;
   for(var i=0; i<arrPlaceholderItems.length; i++){
     var newOption = new Option(messagePrompthtml_entity_decode(arrPlaceholderItems[i][1]), arrPlaceholderItems[i][0], false, false);
     CB.options[CB.length] = newOption;
   }
}
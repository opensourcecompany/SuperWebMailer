	/************************************************************************************************************
  #############################################################################
  #                SuperMailingList / SuperWebMailer                          #
  #               Copyright � 2007 - 2017 Mirko Boeer                         #
  #                    Alle Rechte vorbehalten.                               #
  #                http://www.supermailinglist.de/                            #
  #                http://www.superwebmailer.de/                              #
  #   Support SuperMailingList: support@supermailinglist.de                   #
  #   Support SuperWebMailer: support@superwebmailer.de                       #
  #   Support-Forum: http://board.superscripte.de/                            #
  #                                                                           #
  #   Dieses Script ist urheberrechtlich geschuetzt. Zur Nutzung des Scripts  #
  #   muss eine Lizenz erworben werden.                                       #
  #                                                                           #
  #   Das Script darf weder als ganzes oder als Teil eines anderen Projekts   #
  #   verwendet oder weiterverkauft werden.                                   #
  #                                                                           #
  #   Beachten Sie fuer den Einsatz des Script-Pakets die Lizenzbedingungen   #
  #                                                                           #
  #   Fuehren Sie keine Veraenderungen an diesem Script durch. Jegliche       #
  #   Veraenderungen koennen nicht supported werden.                          #
  #                                                                           #
  #############################################################################
	************************************************************************************************************/

function DisableControl(Enabled, AControl, ALabel) {
  AControl.disabled = !Enabled;
  DisableChildElements(AControl, Enabled);

  if(ALabel != null) {
    ALabel.disabled = AControl.disabled;
  }
  
  return true;
}

function DisableControlsArray(Enabled, AControlsArray, ALabelsArray) {

  for(var i=0; i < AControlsArray.length; i++) {
    AControlsArray[i].disabled = !Enabled;
    DisableChildElements(AControlsArray[i], Enabled);
  }

  if(ALabelsArray != null)
    for(var i=0; i < ALabelsArray.length; i++) {
      ALabelsArray[i].disabled = !Enabled;

     // FF problems
     if(!Enabled) {
       ALabelsArray[i].setAttribute("borderBottomColor", ALabelsArray[i].style.color, false);
       ALabelsArray[i].style.color = "#C0C0C0";
       ALabelsArray[i].style.cursor = 'default';
     } else {
       ALabelsArray[i].style.cursor = 'pointer';
       ALabelsArray[i].style.color = ALabelsArray[i].style.borderBottomColor;
     }

    }

  return true;
}

function DisableControlsById(ReferenceControlName, AControlsNameArray) {
  if(document.getElementById(ReferenceControlName) == null) return;
  var labs = document.getElementsByTagName('label');
  var Enabled = document.getElementById(ReferenceControlName).checked && !document.getElementById(ReferenceControlName).disabled;
  for(var i=0; i < AControlsNameArray.length; i++) {
    document.getElementById(AControlsNameArray[i]).disabled = !Enabled;

    for(var j=0; j<labs.length; j++) {
       var htmlFor = labs[j].getAttribute('for') ?
                            labs[j].getAttribute('for') : labs[j].getAttribute('htmlFor');
       if(htmlFor == AControlsNameArray[i]) {
          labs[j].disabled = !Enabled;
          // FF problems
          if(!Enabled) {
            labs[j].setAttribute("borderBottomColor", labs[j].style.color, false);
            labs[j].style.color = "#C0C0C0";
            labs[j].style.cursor = 'default';
          } else {
            labs[j].style.cursor = 'pointer';
            labs[j].style.color = labs[j].style.borderBottomColor;
          }
       }
    }
  }


  return true;
}

function CheckAllCheckboxes(sourcecheckboxName, targetcheckboxNames, AFormId)
{
 var AForm = document.getElementById(AFormId);
 if(AForm == null)
   return true;

 var elem = AForm.elements[targetcheckboxNames];

	if (elem != null)
		{
  		var checkit  = AForm.elements[sourcecheckboxName].checked;

  		if (elem.length != null)
  			{
    			for (var i = 0; i < elem.length; i++)
  	  			elem[i].checked = checkit;
  			}
  		else
  			elem.checked = checkit;
		}
 return true;
}

function ShowHideItem(IDToHide)
{
		var AItem = document.getElementById(IDToHide);
		if(AItem == null) return;

		if (AItem.style.display == 'none')
 			AItem.style.display = '';
 		else
    if (AItem.style.display == '')
 			  AItem.style.display = 'none';
    		else
 			    AItem.style.display = 'none';
}

function ShowItem(AItemId, doShow) {
  var AItem = document.getElementById(AItemId);
  if(AItem == null) return;
  if(doShow == true)
    AItem.style.display = '';
    else
    AItem.style.display = 'none';
}

function DisableItem(AItemId, Enabled) {
  var AItem = document.getElementById(AItemId);
  if(AItem == null) {
    AItem = document.getElementsByName(AItemId)[0];
    if(AItem == null)
       return;
  }
  AItem.disabled = !Enabled;
  DisableChildElements(AItem, Enabled);
  
  var labs = document.getElementsByTagName('label');
  for(var j=0; j<labs.length; j++) {
     var htmlFor = labs[j].getAttribute('for') ?
                          labs[j].getAttribute('for') : labs[j].getAttribute('htmlFor');
     if(htmlFor == AItemId) {
        labs[j].disabled = !Enabled;
        // FF problems
        if(!Enabled) {
          labs[j].setAttribute("borderBottomColor", labs[j].style.color, false);
          labs[j].style.color = "#C0C0C0";
          labs[j].style.cursor = 'default';
        } else {
          labs[j].style.cursor = 'pointer';
          labs[j].style.color = labs[j].style.borderBottomColor;
        }
     }
  }
}

function DisableChildElements(elem, Enabled){
 if(elem == null) return;
 try {
   if(elem.nodeType == 1)
      elem.disabled = !Enabled;
 } catch(e) {}
 
 var childs = elem.childNodes;
 if(childs == null) return;
 for(var i=0; i<childs.length; i++){
     DisableChildElements(childs[i], Enabled);
 }
}

function DisableItemCursorPointer(AItemId, Enabled) {
  var AItem = document.getElementById(AItemId);
  if(AItem == null) return;
  AItem.disabled = !Enabled;
  if(!Enabled)
    AItem.style.cursor = 'default';
    else
    AItem.style.cursor = 'pointer';

}

function ChangeImage(AItemId, newImage) {
  var AItem = document.getElementById(AItemId);
  if(AItem == null) return;
  AItem.src = newImage;
}

function GetCheckedCount(ACheckBoxId, AFormId) {
  var AForm = document.getElementById(AFormId);
  if(AForm == null)
    return 0;

  var AItem = document.getElementById(ACheckBoxId);
  var elem = AForm.elements[ACheckBoxId];

  if (elem != null) {

  		if (elem.length != null)
  			{
       var count = 0;
       for (var i = 0; i < elem.length; i++)
  	  			  if(elem[i].checked)
            count++;
       return count;
  			}
  		else
  			if (elem.checked)
        return 1;
  }
  return 0;
}

function CheckAndRemoveCriticalChars(AFormId) {
  var AForm = document.getElementById(AFormId);
  if(AForm == null)
    return;
    
  for(var i=0; i<AForm.elements.length; i++)  {
    if (AForm.elements[i].type == "text" && AForm.elements[i].name.indexOf("URL") == -1 ) { // only input type="text" and not URL in Name
      var element = AForm.elements[i];
      
      if(AForm.elements[i].name.indexOf("Subject") != -1 || AForm.elements[i].name.indexOf("MailPreHeaderText") != -1)
        element.value = element.value.replace(/[�`\\]/g, "");
      else
        if(AForm.elements[i].name.indexOf("Name") != -1)
           element.value = element.value.replace(/[�`'"\\%?=+<>]/g, "");
    }
  }
}

function InsertFieldValue(CBId, TargetElementId) {
  var CB = document.getElementById(CBId);
  if(CB == null) return;
   if(CB.selectedIndex > 0) {
     PasteText(TargetElementId, CB.options[CB.selectedIndex].value );
     CB.selectedIndex = 0;
     if(document.getElementById(TargetElementId) && document.getElementById(TargetElementId).onchange != null)
       document.getElementById(TargetElementId).onchange();
   }
}

function ShowCKEditor(elementName, Awidth, Aheight, AToolbar, AfullPage){
  var textarea = document.getElementById(elementName);
  if( textarea ) {

     var editor = null;
     for(var i in CKEDITOR.instances) {
        if(CKEDITOR.instances[i].name == elementName) {
          editor = CKEDITOR.instances[i];
          break;
        }
     }

     if(editor != null) { editor.setData(textarea.value); return true;} // is loaded

     var sBasePath = myBasePath + 'ckeditor/';

     CKEDITOR.config.BasePath	= sBasePath ;

     if(AToolbar == null)
        AToolbar = 'MyDefaultMailToolbar';

     if(AfullPage == null)
       AfullPage = true;

     CKEDITOR.config.fullPage = AfullPage;

     try {
       CKEDITOR.replace(elementName,
        {
          customConfig : myBasePath + 'plugins/swm_sml_ckeditor_config.js',
          toolbar : AToolbar,
          fullPage: AfullPage,
          width : Awidth,
          height: Aheight
        }
       );

     } catch(e){
       textarea.style.display = '';
       textarea.style.visibility = 'visible';
       return false;
     }
     Sleep(500);


    return true;
 	}
 	return false;
}


function HideCKEditor(elementName){
  var textarea = document.getElementById(elementName);
  var editor = null;
  for(var i in CKEDITOR.instances) {
     if(CKEDITOR.instances[i].name == elementName) {
       editor = CKEDITOR.instances[i];
       break;
     }
  }

  if (editor!=null && textarea.style.display == "none" /*&& document.removeChild*/)
  {
     try {
      editor.updateElement();
     } catch(e){
      textarea.value = editor.getData();
     }

     Sleep(100);
     try {
       editor.destroy(false); // bug in ckeditor destroy bei vorher geoeffneten dialog Fenster macht fehler
       editor = null;
     } catch(e){
     }



     return true;
  }
  return false;
}

function Sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function PasteText(TargetElementId, aText) {
  var input = document.getElementById(TargetElementId);
  input.focus();
  /* f�r Internet Explorer */
  if(typeof document.selection != 'undefined') {
    /* Einf�gen des Formatierungscodes */
    var range = document.selection.createRange();
    var insText = range.text;
    range.text = insText + aText;
    /* Anpassen der Cursorposition */
    range = document.selection.createRange();
    if (insText.length == 0) {
      //range.move('character', -aText.length);
    } else {
      range.moveStart('character', insText.length + aText.length);
    }
    range.select();
  }
  /* f�r neuere auf Gecko basierende Browser */
  else if(typeof input.selectionStart != 'undefined')
  {
    /* Einf�gen des Formatierungscodes */
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + insText + aText + input.value.substr(end);
    /* Anpassen der Cursorposition */
    var pos;
    if (insText.length == 0) {
      pos = start + aText.length;
    } else {
      pos = start + aText.length + insText.length;
    }
    input.selectionStart = pos;
    input.selectionEnd = pos;
  }
  /* f�r die �brigen Browser */
  else
  {
  }
}

// http://aktuell.de.selfhtml.org/artikel/javascript/utf8b64/utf8.htm
function utf8_encode(rohtext) {
   // dient der Normalisierung des Zeilenumbruchs
   rohtext = rohtext.replace(/\r\n/g,"\n");
   var utftext = "";
   for(var n=0; n<rohtext.length; n++)
       {
       // ermitteln des Unicodes des  aktuellen Zeichens
       var c=rohtext.charCodeAt(n);
       // alle Zeichen von 0-127 => 1byte
       if (c<128)
           utftext += String.fromCharCode(c);
       // alle Zeichen von 127 bis 2047 => 2byte
       else if((c>127) && (c<2048)) {
           utftext += String.fromCharCode((c>>6)|192);
           utftext += String.fromCharCode((c&63)|128);}
       // alle Zeichen von 2048 bis 66536 => 3byte
       else {
           utftext += String.fromCharCode((c>>12)|224);
           utftext += String.fromCharCode(((c>>6)&63)|128);
           utftext += String.fromCharCode((c&63)|128);}
       }
   return utftext;
}


function utf8_decode(utftext) {
   var plaintext = ""; var i=0; var c=c1=c2=0;
   // while-Schleife, weil einige Zeichen uebersprungen werden
   while(i<utftext.length)
       {
       c = utftext.charCodeAt(i);
       if (c<128) {
           plaintext += String.fromCharCode(c);
           i++;}
       else if((c>191) && (c<224)) {
           c2 = utftext.charCodeAt(i+1);
           plaintext += String.fromCharCode(((c&31)<<6) | (c2&63));
           i+=2;}
       else {
           c2 = utftext.charCodeAt(i+1); c3 = utftext.charCodeAt(i+2);
           plaintext += String.fromCharCode(((c&15)<<12) | ((c2&63)<<6) | (c3&63));
           i+=3;}
       }
   return plaintext;
}
//

function OpenEditor(formId, formElementId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("htmledit.php?form=" + formId + "&formElement=" + formElementId + "&nocache=" + nocache, 708, 540);
}

function AttachmentUpload(formId, formElement1Name, Element1Name, formElement2Name, Element2Name) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  ShowModalDialog("attachmentsupload.php?form=" + formId + "&formElement1=" + formElement1Name + "&formElement2=" + formElement2Name + '&Element1Name=' + Element1Name + '&Element2Name=' + Element2Name + "&nocache=" + nocache, 708, 400);
}

function AttachmentDelete(formId, formElement1Name, formElement2Name) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  var AForm = document.getElementById(formId);
  if(AForm == null)
    return 0;

  var AItems = document.getElementsByName(formElement1Name);
  var files = "";
  for(var i=0; i<AItems.length; i++) {
    if(AItems[i].checked)
       if(files == "")
          files = AItems[i].value;
          else
          files = files + ";" + AItems[i].value;
  }

  ShowModalDialog("attachmentsdelete.php?form=" + formId + "&formElement1=" + formElement1Name + "&formElement2=" + formElement2Name + "&files=" + escape(files) + "&nocache=" + nocache, 708, 480);
}

function PersAttachmentAddEdit(formId, formElement1Name, editvalue) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("persattachmentsaddedit.php?form=" + formId + "&formElement1=" + formElement1Name + '&EditValue=' + encodeURIComponent(editvalue) + "&nocache=" + nocache, 708, 480);
}

function MailHeaderFieldsAddEdit(formId, formElement1Name, editvalue) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("mailheaderfieldsaddedit.php?form=" + formId + "&formElement1=" + formElement1Name + '&EditValue=' + encodeURIComponent(editvalue) + "&nocache=" + nocache, 708, 300);
}

function MTATestWindow(MTAId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("mta_test.php?mta_id=" + MTAId + "&nocache=" + nocache, 708, 440);
}

function InboxTestWindow(InboxId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("inbox_test.php?inbox_id=" + InboxId + "&nocache=" + nocache, 708, 140);
}

function FunctionsOpen(formId, formElementId, isfckeditor) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("browsefunctions.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + nocache, 708, 480);
}

function TextBlocksOpen(formId, formElementId, isfckeditor) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("browsetextblocks.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + nocache, 708, 400);
}

function TargetGroupsOpen() {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("browsetargetgroups.php?" + "nocache=" + nocache, 708, 400);
}

function ReasonsForUnsubscriptionOpen(MailingListId, FormsId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("browsereasonsforunsubscription.php?MailingListId=" + MailingListId + "&FormsId=" + FormsId + "&nocache=" + nocache, 708, 400);
}

function SurveysOpen(formId, formElementId, isfckeditor) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("browsesurveys.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + nocache, 708, 400);
}

function LocalMessagesBrowse() {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("browselocalmessages.php?nocache=" + nocache, 708, 400);
}

function TemplatesSelectDlgOpen(formId, formElementId, isfckeditor) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("plaintexttemplates.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + nocache, 708, 400);
}

function HTMLTemplatesSelectDlgOpen(formId, formElementId, isfckeditor, formIframeName, wizardableonly) {
  var date = new Date();
  var nocache = date.getTime() / 1000;

  ShowModalDialog("htmltemplates.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&formIframeName=" + formIframeName + "&wizardableonly=" + wizardableonly + "&nocache=" + nocache, 708, 400);
}

function SerialMailPreviewOpen(MailingListId, FormId, MailTemplate) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  oWindow = window.open("serialmailpreview.php?MailingListId=" + MailingListId + "&FormId=" + FormId + "&MailTemplate=" + MailTemplate + "&nocache=" + nocache, "SerialMailPreviewWnd","width=750,height=700,scrollbars=yes,status=yes,toolbar=no,resizable=no,location=no,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function SerialMailPreviewOpenResponder(MailingListId, FormId, ResponderType, ResponderId, ResponderMailItemId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  oWindow = window.open("serialmailpreview.php?MailingListId=" + MailingListId + "&FormId=" + FormId + "&ResponderType=" + ResponderType + "&ResponderId=" + ResponderId + '&ResponderMailItemId=' + ResponderMailItemId + "&nocache=" + nocache, "SerialMailPreviewWnd","width=750,height=700,scrollbars=yes,status=yes,toolbar=no,resizable=no,location=no,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function ResponderPreviewOpenResponder(MailingListId, FormId, ResponderType, ResponderId, ResponderMailItemId, RequestedInfos) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  height = 670;
  width = 708;
  if(RequestedInfos == "GetBandwidth")
    height = 320;
  if(RequestedInfos == "SpamTest")
    width = 900;
  if(RequestedInfos == "TestMail")
    height = 300;

  ShowModalDialog("responderpreview.php?MailingListId=" + MailingListId + "&FormId=" + FormId + "&ResponderType=" + ResponderType + "&ResponderId=" + ResponderId + '&ResponderMailItemId=' + ResponderMailItemId + '&' + RequestedInfos + '=1' + "&nocache=" + nocache, width, height);
}

function ShowHelpWindow(helpTopic) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  oWindow = window.open("help.php?helpTopic=" + helpTopic + "&nocache=" + nocache, "HelpWnd","width=800,height=480,scrollbars=yes,status=no,toolbar=yes,resizable=yes,location=no,modal=no");
  oWindow.opener = window;
  oWindow.focus();
}

function ShowNewsletterArchive(NAUniqueId, NAId, UserId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  height = 670;
  width = 800;
  oWindow = window.open("show_na.php?na=" + NAUniqueId + "&newsletterarchive=" + NAId + "&nauser=" + UserId + "&nocache=" + nocache, "ShowNewsletterArchiveWnd","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=no");
  oWindow.opener = window;
  oWindow.focus();
}

function ShowNewsletterArchiveAsRSS(NAUniqueId, NAId, UserId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  height = 670;
  width = 800;
  oWindow = window.open("show_na.php?na=" + NAUniqueId + "&newsletterarchive=" + NAId + "&nauser=" + UserId + "&nocache=" + nocache + '&showRSS=1', "ShowNewsletterRSSArchiveWnd","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=no");
  oWindow.opener = window;
  oWindow.focus();
}

function ShowRcptsColumnsDlg(PersTrackingRcptsListColumns) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  var s = "";
  if(PersTrackingRcptsListColumns)
     s = "&PersTrackingRcptsListColumns=1";

  ShowModalDialog("rcptscolumns.php?nocache=" + nocache + s, 708, 500);
}

function ShowTwitterPostDlg(CampaignId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  height = 770;
  width = 890;
  oWindow = window.open("show_twitterpostdlg.php?CampaignId=" + CampaignId + "&nocache=" + nocache, "ShowTwitterPostDlg","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function ShowFacebookPostDlg(CampaignId) {
  var date = new Date();
  var nocache = date.getTime() / 1000;
  height = 770;
  width = 890;
  oWindow = window.open("show_facebookpostdlg.php?CampaignId=" + CampaignId + "&nocache=" + nocache, "ShowFacebookPostDlg","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function HideEmptyMenuItems(){
  var Menus = Array("MainMenu", "SystemMenu");
  for(var i=0;i<Menus.length;i++) {
    if(document.getElementById(Menus[i]) ) {
      var Menu = document.getElementById(Menus[i]);
      var LINode = Menu.firstChild;
      while(LINode && LINode.tagName != "LI")
        LINode = LINode.nextSibling;
      while(LINode) {
        var child = LINode.firstChild;
        while(child){
         if( child.tagName == "UL"  ) {
           var count = 0;
           var cnode = child.firstChild;
           while(cnode) {
             if(cnode.tagName) {
               count++;
               break;
             }
             cnode = cnode.nextSibling;
           }
           if(count == 0)
             LINode.style.display = 'none';
           break;
         }
         child = child.nextSibling;
        }
        LINode = LINode.nextSibling;
        while(LINode && LINode.tagName != "LI")
           LINode = LINode.nextSibling;
      }
    }
  }
}

function AddTableDblClickEvent(tableId, adblclickevent){
		var tableObj = document.getElementById(tableId);
		if(tableObj == null) return;
		var tBody = tableObj.getElementsByTagName('TBODY');
		if(tBody){
			var rows = tBody[0].getElementsByTagName('TR');
		}else{
			var rows = tableObj.getElementsByTagName('TR');
		}
		for(var no=0; no<rows.length; no++){
		 for(var i=0; i < rows[no].cells.length; i++) {
		    try {
		    if(rows[no].cells[i].ondblclick == null)
		       if(adblclickevent == null)
      		    rows[no].cells[i].ondblclick = _TableCellDblClick;
      		    else
      		    rows[no].cells[i].ondblclick = adblclickevent;
   		 } catch(e) {/*empty cell*/}
		 }
		}
}

function _TableCellDblClick(event){
  if (!event)
    event = window.event;
  if (!event) return;

  if(event.srcElement) { // IE
     var row = event.srcElement;
  } else{
     var row = event.target;
  }
  
  if(row == null) return;
  
  if (row.nodeType == 3) // Safari bug
		   row = row.parentNode;

  if(row.tagName == "TD")
     while(row != null && row.tagName != "TR")
        row = row.parentNode;

  if(row == null) return;

	 for(var i=0; i < row.cells.length; i++) {
	   var inputs = row.cells[i].getElementsByTagName("INPUT");
	   if(inputs == null || inputs.length == 0) {
      var as = row.cells[i].getElementsByTagName("A");
      if(as == null || as.length != 1 || as[0].disabled)
        continue;
      as[0].click();
      return;
    }
	   for(var j=0; j<inputs.length; j++){
  	    if(inputs[j].onclick != null && inputs[j].name.indexOf("Edit") == 0 && !inputs[j].disabled) {
  	       inputs[j].click();
  	       return;
  	    }
	   }
	 }
}

function SortOnClick(sortfieldname, sortfieldnames, sortorder){
 var tags = document.getElementsByName(sortfieldnames);
 if(tags.length == 0) return;
 var tag = tags[0];

 var index = -1;
 for(var i=0; i<tag.options.length; i++){
   if(tag.options[i].value == sortfieldname){
     index = i;
     break;
   }
 }

 if(index == -1) return;

 var changeSortOrder = false;
 if(tag.selectedIndex == index)
   changeSortOrder = true;

 tag.selectedIndex = index;

 if(changeSortOrder){
   var sortorder  = document.getElementsByName(sortorder);
   if(sortorder == 0) return;
   sortorder = sortorder[0];
   if(sortorder.selectedIndex == 0)
     sortorder.selectedIndex = 1;
     else
     sortorder.selectedIndex = 0;
 }

 var FilterApplyBtn = document.getElementsByName("FilterApplyBtn");
 if(FilterApplyBtn.length == 0) return;
 FilterApplyBtn[0].click();
}

function CSSaddClass(classname, element) {
    if(element.classList){
      element.classList.add(classname)
      return;
    }

    var cn = element.className;
    //test for existance
    if( cn.indexOf( classname ) != -1 ) {
    	return;
    }
    //add a space if the element already has class
    if( cn != '' ) {
    	classname = ' '+classname;
    }
    element.className = cn+classname;
}

function CSSremoveClass(classname, element) {
    if(element.classList){
      element.classList.remove(classname)
      return;
    }
    var cn = element.className;
    var rxp = new RegExp( "\\s?\\b"+classname+"\\b", "g" );
    cn = cn.replace( rxp, '' );
    element.className = cn;
}

// add ajaxLoading ever when it used with jQuery
window.onload = function () {
   if (!(typeof jQuery === "undefined")){
     $(document).ajaxStart(function(){
        $("body, .dhtmlgoodies_tabPane div").addClass('ajaxLoading');
     });
     $(document).ajaxStop(function(){
        $("body, .dhtmlgoodies_tabPane div").removeClass('ajaxLoading');
     });

     $('#AutoUpdateTextPart, #MailFormat').on('click', function(){
       if($('#tabTabdhtmlgoodies_HTMLTexttabView_1').attr('class') == "tabActive")
          $('#tabTabdhtmlgoodies_HTMLTexttabView_1').trigger("click");
     });

     $('#tabTabdhtmlgoodies_HTMLTexttabView_1').on('click', function(){
       var AutoUpdateTextPart = document.getElementById("AutoUpdateTextPart") && document.getElementById("AutoUpdateTextPart").checked;
       if(!document.getElementById('MailFormat') || !document.getElementById("MailPlainText") || !document.getElementById("MailHTMLText") || !AutoUpdateTextPart) return;
       if( document.getElementById('MailFormat').selectedIndex > 1 &&
          (AutoUpdateTextPart || document.getElementById("MailPlainText").value.trim() == "") ){

          if(document.getElementById('MailEditType')){ // campaign only
            if(document.getElementById('MailEditType').selectedIndex == 0){

               var editor = CKEDITOR.instances['MailHTMLText'];
               var indentationChars = editor.dataProcessor.writer.indentationChars;
               var lineBreakChars = editor.dataProcessor.writer.lineBreakChars;
               editor.dataProcessor.writer.indentationChars = '';
               editor.dataProcessor.writer.lineBreakChars = '';
               editor.updateElement();

               var html = editor.getData();

               editor.dataProcessor.writer.indentationChars = indentationChars;
               editor.dataProcessor.writer.lineBreakChars = lineBreakChars;
               editor.updateElement();
               }
               else
               if(document.getElementById('WizardHTMLText') && document.getElementById('MailEditType').selectedIndex == 1){
                if(!$.browser.msie){ // not in IE
                  SaveIPE(true);
                  ShowItem("WizardHTMLText", false);
                  var html = $('#MailHTMLText').val();
                }
               }else
                if(myCodeMirror && document.getElementById('MailEditType').selectedIndex == 2){
                  myCodeMirror.save();
                  var html = $('#MailHTMLText').val();
                }
          } else { // all other forms and responders
              if(CKEDITOR){
                var editor = CKEDITOR.instances['MailHTMLText'];
                var indentationChars = editor.dataProcessor.writer.indentationChars;
                var lineBreakChars = editor.dataProcessor.writer.lineBreakChars;
                editor.dataProcessor.writer.indentationChars = '';
                editor.dataProcessor.writer.lineBreakChars = '';
                editor.updateElement();

                var html = editor.getData();

                editor.dataProcessor.writer.indentationChars = indentationChars;
                editor.dataProcessor.writer.lineBreakChars = lineBreakChars;
                editor.updateElement();
              }
          }
          if(html){
             var nocache = new Date().getTime() / 1000;
             $.ajaxSetup({ cache: false });
             $.post("ajax_htmltoplaintext.php" + "?nocache=" + nocache, {html: html} )
               .done(function( data ) {
                  document.getElementById("MailPlainText").value = data;
               });
          }
       }
     });

   }
}

function AddAutoUpdateTextPart(TabbedControlid, MailFormatCBid, MailHTMLTextid, MailPlainTextid){
   if(document.getElementById(TabbedControlid) && document.getElementById(MailFormatCBid) && document.getElementById(MailHTMLTextid) && document.getElementById(MailPlainTextid)){

      var Tab = "tabTab" + TabbedControlid + "_1";
      if(!document.getElementById(Tab)) return;

      $('#' + MailFormatCBid).on('click', function(){
        if($('#' + Tab).attr('class') == "tabActive")
           $('#' + Tab).trigger("click");
      });

      $('#' + Tab).on('click', function(){
          var ck = CKEDITOR && CKEDITOR.instances[MailHTMLTextid];
          if( document.getElementById(MailFormatCBid).selectedIndex > 0 && (document.getElementById(MailPlainTextid).value.trim() == "" || (ck && CKEDITOR.instances[MailHTMLTextid].checkDirty()) )  ){
             if(ck){

                var editor = CKEDITOR.instances[MailHTMLTextid];
                var indentationChars = editor.dataProcessor.writer.indentationChars;
                var lineBreakChars = editor.dataProcessor.writer.lineBreakChars;
                editor.dataProcessor.writer.indentationChars = '';
                editor.dataProcessor.writer.lineBreakChars = '';
                editor.updateElement();

                var html = editor.getData();

                editor.dataProcessor.writer.indentationChars = indentationChars;
                editor.dataProcessor.writer.lineBreakChars = lineBreakChars;
                editor.updateElement();
             }
             if(html){
                var nocache = new Date().getTime() / 1000;
                $.ajaxSetup({ cache: false });
                $.post("ajax_htmltoplaintext.php" + "?nocache=" + nocache, {html: html} )
                  .done(function( data ) {
                     document.getElementById(MailPlainTextid).value = data;
                     editor.resetDirty();
                  });
             }
          }
      });
   }
}

// no trim() function?
if(typeof String.prototype.trim !== 'function') {
   String.prototype.trim = function() {
      return this.replace(/^\s+|\s+$/, '');
   }
}

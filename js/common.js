	/************************************************************************************************************
  #############################################################################
  #                SuperMailingList / SuperWebMailer                          #
  #               Copyright © 2007 - 2022 Mirko Boeer                         #
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
var SMLSWM_TOKEN_COOKIE_NAME = 'smlswmCsrfToken';

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
   if(elem.nodeType == 1 && elem.tagName != "LABEL")
      elem.disabled = !Enabled;
      else
      if(elem.nodeType == 1 && elem.tagName == "LABEL"){
        // FF problems
        if(!Enabled) {
          elem.setAttribute("borderBottomColor", elem.style.color, false);
          elem.style.color = "#C0C0C0";
          elem.style.cursor = 'default';
        } else {
          elem.style.cursor = 'pointer';
          elem.style.color = elem.style.borderBottomColor;
        }
        elem.disabled = !Enabled;
      }
 } catch(e) {}
 
 var childs = elem.childNodes;
 if(childs == null) return;
 for(var i=0; i<childs.length; i++){
     DisableChildElements(childs[i], Enabled);
 }
}

function DisableSiblingElements(elem, Enabled){
 var nextNode = elem.parentNode;
 nextNode = nextNode.nextSibling;
 while(nextNode != null){
   if(nextNode.nodeType == 1){
      DisableChildElements(nextNode, Enabled);
   }
   nextNode = nextNode.nextSibling;
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

function GetCheckBoxChecked(ACheckBoxId){
  var AItem = document.getElementById(ACheckBoxId);
  if(AItem == null) return false;
  return AItem.checked && !AItem.disabled;
}

function CheckAndRemoveCriticalChars(AFormId) {
  var AForm = document.getElementById(AFormId);
  if(AForm == null)
    return;
    
  for(var i=0; i<AForm.elements.length; i++)  {
    if (AForm.elements[i].type == "text" && AForm.elements[i].name.indexOf("URL") == -1 ) { // only input type="text" and not URL in Name
      var element = AForm.elements[i];
      
      if(AForm.elements[i].name.indexOf("Subject") != -1 || AForm.elements[i].name.indexOf("MailPreHeaderText") != -1)
        element.value = element.value.replace(/[´`\\]/g, "");
      else
        if(AForm.elements[i].name.indexOf("Name") != -1)
           element.value = element.value.replace(/[´`'"\\%?=+]/g, "");
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

function IsCKEditorVisible(elementName){
  if(document.getElementById(elementName)){
     var editor = null;
     for(var i in CKEDITOR.instances) {
        if(CKEDITOR.instances[i].name == elementName) {
          editor = CKEDITOR.instances[i];
          break;
        }
     }

     return editor != null ? true : false;
  }
  return false;
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
  /* für Internet Explorer */
  if(typeof document.selection != 'undefined') {
    /* Einfügen des Formatierungscodes */
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
  /* für neuere auf Gecko basierende Browser */
  else if(typeof input.selectionStart != 'undefined')
  {
    /* Einfügen des Formatierungscodes */
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
  /* für die übrigen Browser */
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

function GetFrameIndex(name){
  for(var i = 0; i < window.frames.length; i++)
    if(window.frames[i].name == name)
      return i;
  return -1;
}

function getNoCache(){
  return new Date().getTime() / 1000;
}

function OpenEditor(formId, formElementId) {
  ShowModalDialog("htmledit.php?form=" + formId + "&formElement=" + formElementId + "&nocache=" + getNoCache(), 708, 540);
}

function AttachmentUpload(formId, formElement1Name, Element1Name, formElement2Name, Element2Name) {
  ShowModalDialog("attachmentsupload.php?form=" + formId + "&formElement1=" + formElement1Name + "&formElement2=" + formElement2Name + '&Element1Name=' + Element1Name + '&Element2Name=' + Element2Name + "&nocache=" + getNoCache(), 708, 400);
}

function AttachmentDelete(formId, formElement1Name, formElement2Name) {
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

  ShowModalDialog("attachmentsdelete.php?form=" + formId + "&formElement1=" + formElement1Name + "&formElement2=" + formElement2Name + "&files=" + escape(files) + "&nocache=" + getNoCache(), 708, 480);
}

function PersAttachmentAddEdit(formId, formElement1Name, editvalue) {
  ShowModalDialog("persattachmentsaddedit.php?form=" + formId + "&formElement1=" + formElement1Name + '&EditValue=' + encodeURIComponent(editvalue) + "&nocache=" + getNoCache(), 708, 480);
}

function MailHeaderFieldsAddEdit(formId, formElement1Name, editvalue) {
  ShowModalDialog("mailheaderfieldsaddedit.php?form=" + formId + "&formElement1=" + formElement1Name + '&EditValue=' + encodeURIComponent(editvalue) + "&nocache=" + getNoCache(), 708, 300);
}

function MTATestWindow(MTAId) {
  ShowModalDialog("mta_test.php?mta_id=" + MTAId + "&nocache=" + getNoCache(), 708, 440);
}

function InboxTestWindow(InboxId) {
  ShowModalDialog("inbox_test.php?inbox_id=" + InboxId + "&nocache=" + getNoCache(), 708, 140);
}

function FunctionsOpen(formId, formElementId, isfckeditor) {
  ShowModalDialog("browsefunctions.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + getNoCache(), 708, 480);
}

function EmojisOpen(formId, formElementId, isfckeditor) {
  ShowModalDialog("browseemojis.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + getNoCache(), 708, 600);
}

function VariantsOfSubjectsOpen(formId, formElementId) {
  ShowModalDialog("browsevariantsofsubject.php?form=" + formId + "&formElement=" + formElementId + "&nocache=" + getNoCache(), 708, 480);
}

function TextBlocksOpen(formId, formElementId, isfckeditor) {
  ShowModalDialog("browsetextblocks.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + getNoCache(), 708, 400);
}

function TargetGroupsOpen() {
  ShowModalDialog("browsetargetgroups.php?" + "nocache=" + getNoCache(), 708, 400);
}

function ReasonsForUnsubscriptionOpen(MailingListId, FormsId) {
  ShowModalDialog("browsereasonsforunsubscription.php?MailingListId=" + MailingListId + "&FormsId=" + FormsId + "&nocache=" + getNoCache(), 708, 400);
}

function SurveysOpen(formId, formElementId, isfckeditor) {
  ShowModalDialog("browsesurveys.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + getNoCache(), 708, 400);
}

function LocalMessagesBrowse() {
  ShowModalDialog("browselocalmessages.php?nocache=" + getNoCache(), 708, 400);
}

function TemplatesSelectDlgOpen(formId, formElementId, isfckeditor) {
  ShowModalDialog("plaintexttemplates.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&nocache=" + getNoCache(), 708, 400);
}

function HTMLTemplatesSelectDlgOpen(formId, formElementId, isfckeditor, formIframeName, wizardableonly) {
  ShowModalDialog("htmltemplates.php?form=" + formId + "&formElement=" + formElementId + "&IsFCKEditor=" + isfckeditor + "&formIframeName=" + formIframeName + "&wizardableonly=" + wizardableonly + "&nocache=" + getNoCache(), 708, 400);
}

function SerialMailPreviewOpen(MailingListId, FormId, MailTemplate) {
  oWindow = openWindowWithPost("serialmailpreview.php?MailingListId=" + MailingListId + "&FormId=" + FormId + "&MailTemplate=" + MailTemplate + "&nocache=" + getNoCache(), {}, "SerialMailPreviewWnd", "width=750,height=700,scrollbars=yes,status=yes,toolbar=no,resizable=no,location=no,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function SerialMailPreviewOpenResponder(MailingListId, FormId, ResponderType, ResponderId, ResponderMailItemId) {
  oWindow = openWindowWithPost("serialmailpreview.php?MailingListId=" + MailingListId + "&FormId=" + FormId + "&ResponderType=" + ResponderType + "&ResponderId=" + ResponderId + '&ResponderMailItemId=' + ResponderMailItemId + "&nocache=" + getNoCache(), {}, "SerialMailPreviewWnd", "width=750,height=700,scrollbars=yes,status=yes,toolbar=no,resizable=no,location=no,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function ResponderPreviewOpenResponder(MailingListId, FormId, ResponderType, ResponderId, ResponderMailItemId, RequestedInfos) {
  height = 670;
  width = 708;
  if(RequestedInfos == "GetBandwidth")
    height = 320;
  if(RequestedInfos == "SpamTest")
    width = 900;
  if(RequestedInfos == "TestMail")
    height = 300;
  ShowModalDialog("responderpreview.php?MailingListId=" + MailingListId + "&FormId=" + FormId + "&ResponderType=" + ResponderType + "&ResponderId=" + ResponderId + '&ResponderMailItemId=' + ResponderMailItemId + '&' + RequestedInfos + '=1' + "&nocache=" + getNoCache(), width, height);
}

function ShowHelpWindow(helpTopic) {
  oWindow = window.open("help.php?helpTopic=" + helpTopic + "&nocache=" + getNoCache(), "HelpWnd","width=800,height=480,scrollbars=yes,status=no,toolbar=yes,resizable=yes,location=no,modal=no");
  oWindow.opener = window;
  oWindow.focus();
}

function ShowNewsletterArchive(NAUniqueId, NAId, UserId) {
  height = 670;
  width = 800;
  oWindow = window.open("show_na.php?na=" + NAUniqueId + "&newsletterarchive=" + NAId + "&nauser=" + UserId + "&nocache=" + getNoCache(), "ShowNewsletterArchiveWnd","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=no");
  oWindow.opener = window;
  oWindow.focus();
}

function ShowNewsletterArchiveAsRSS(NAUniqueId, NAId, UserId) {
  height = 670;
  width = 800;
  oWindow = window.open("show_na.php?na=" + NAUniqueId + "&newsletterarchive=" + NAId + "&nauser=" + UserId + "&nocache=" + getNoCache() + '&showRSS=1', "ShowNewsletterRSSArchiveWnd","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=no");
  oWindow.opener = window;
  oWindow.focus();
}

function ShowRcptsColumnsDlg(PersTrackingRcptsListColumns) {
  var s = "";
  if(PersTrackingRcptsListColumns)
     s = "&PersTrackingRcptsListColumns=1";

  ShowModalDialog("rcptscolumns.php?nocache=" + getNoCache() + s, 708, 500);
}

function ShowTwitterPostDlg(CampaignId) {
  height = 770;
  width = 890;
  oWindow = openWindowWithPost("show_twitterpostdlg.php?CampaignId=" + CampaignId + "&nocache=" + getNoCache(), {}, "ShowTwitterPostDlg", "width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function ShowFacebookPostDlg(CampaignId) {
  height = 770;
  width = 890;
  oWindow = openWindowWithPost("show_facebookpostdlg.php?CampaignId=" + CampaignId + "&nocache=" + getNoCache(), {}, "ShowFacebookPostDlg","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=yes");
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
   if(sortorder.length == 0) return;
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

function getCookieValue( name ) {
 			name = name.toLowerCase();
 			var parts = document.cookie.split( ';' );
 			var pair, key;


 			for ( var i = 0; i < parts.length; i++ ) {
 				pair = parts[ i ].split( '=' );
 				key = decodeURIComponent( pair[ 0 ].trim().toLowerCase() );
 				if ( key === name ) {
 					return decodeURIComponent( pair.length > 1 ? pair[ 1 ] : '' );
 				}
 			}

 			return null;
}

function openWindowWithPost(path, PostParams, WindowName, WindowParams){
  var newwindow = window.open('', WindowName, WindowParams);
  if(newwindow)
    CreateFormAndPostIt(path, PostParams, "post", WindowName);
  return newwindow;
}

// usage CreateFormAndPostIt('fullurlpath', {field1:'value1', field2:'value2'}, 'post', '_self');
function CreateFormAndPostIt(path, params, method, target) {
  try{
    method = method || "post";

    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    if(target)
      form.setAttribute("target", target);

    if(document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME).length){
      if(!params)
        params = {};
      params[SMLSWM_TOKEN_COOKIE_NAME] = document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME)[0].value;
    }

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
    try{
      document.body.removeChild(form);
      form.remove();
    }catch(e){}
    return true;
 } catch(e) { return false}
}

function HTMLEntityDecode( string ) {
    var ret, tarea = document.createElement('textarea');
    tarea.innerHTML = string;
    ret = tarea.value;
    tarea = null;
    return ret;
}


// Initialize onclick handler and add ajaxLoading ever when it used with jQuery
var IsInitializedSMLSWMJS=false;

if(window.addEventListener){
  window.addEventListener("load", function _xload(event) {
      window.removeEventListener("load", _xload, false);
      InitializeSMLSWMJS();
  }, false);

  window.addEventListener('DOMContentLoaded', function _xDOMContentLoaded(){
    window.removeEventListener("DOMContentLoaded", _xDOMContentLoaded, false);
    InitializeSMLSWMJS();
  }, false);

} else{
  window.onload = function () {
    InitializeSMLSWMJS();
  }
}

// alternative to DOMContentLoaded
document.onreadystatechange = function () {
    if (document.readyState === "interactive") {
        InitializeSMLSWMJS();
    }
}

InitializeSMLSWMJS = function () {
   if (!(typeof jQuery === "undefined") && !IsInitializedSMLSWMJS){
     IsInitializedSMLSWMJS = true;

    	$(document).ready(function(){

        if(document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME).length){

          $('a').on("contextmenu", function(evt) {evt.preventDefault(); return false;});

          $('a').click(function(e) {
             var _href = $(this).attr("href");
             if(!_href) return true;
             var _onclick = $(this).attr("onclick");
             var bm = (_href == '') || (_href.indexOf('#') == 0) || (_href.indexOf('http:') == 0) || (_href.indexOf('https:') == 0);
             if(!bm && !_onclick && _href.indexOf('javascript:') == -1 && _href.indexOf(SMLSWM_TOKEN_COOKIE_NAME) == -1 && _href.indexOf('show_saved_data.php') == -1 ){
     //console.log(_href + "  " + bm);
               if(!CreateFormAndPostIt(_href, {}, 'post', $(this).attr("target")))
                 $(this).attr("href", _href + ( _href.indexOf('?') != -1 ? "&" + SMLSWM_TOKEN_COOKIE_NAME + '=' + document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME)[0].value : "?" + SMLSWM_TOKEN_COOKIE_NAME + '=' + document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME)[0].value) );
                 else{
                   e.stopPropagation();
                   e.preventDefault();
                   return false;
                 }
             }else{
               if(_href.indexOf('javascript:') > -1)
                e.stopPropagation();
             }
          });

          $('a').mousedown(function(e) {
            var _href = $(this).attr("href");
            if(!_href) return true;
            var _onclick = $(this).attr("onclick");
            var bm = (_href == '') || (_href.indexOf('#') == 0) || (_href.indexOf('http:') == 0) || (_href.indexOf('https:') == 0);

            // 2 or 4 is middle, 3 is right
            if(e.which == 2 || e.which == 4) {
              if(!bm && !_onclick && _href.indexOf('javascript:') == -1 && _href.indexOf(SMLSWM_TOKEN_COOKIE_NAME) == -1 && _href.indexOf('show_saved_data.php') == -1 ){
                e.preventDefault();
                e.stopPropagation();
                $(this).trigger('click');
                return false;
              }
            }
          });

        }
    	});

     $(document).ajaxStart(function(){
        $("body, .dhtmlgoodies_tabPane div").addClass('ajaxLoading');
     });
     $(document).ajaxStop(function(){
        $("body, .dhtmlgoodies_tabPane div").removeClass('ajaxLoading');
     });
     $(document).ajaxSend(function(event, jqxhr, settings){
       if( (settings["method"] == "GET" || settings["type"] == "GET" || settings["method"] == "HEAD" || settings["type"] == "HEAD") && document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME).length ){
          settings["headers"] = "X-" + SMLSWM_TOKEN_COOKIE_NAME + ": " + document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME)[0].value;
          jqxhr.setRequestHeader("X-" + SMLSWM_TOKEN_COOKIE_NAME, document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME)[0].value);
       }
     });

     $('#AutoUpdateTextPart, #MailFormat').on('click', function(){
       if($('#tabTabdhtmlgoodies_HTMLTexttabView_1').attr('class') == "tabActive")
          $('#tabTabdhtmlgoodies_HTMLTexttabView_1').trigger("click");
     });

     $('#tabTabdhtmlgoodies_HTMLTexttabView_1').on('click', function(){
       var AutoUpdateTextPart = document.getElementById("AutoUpdateTextPart") && document.getElementById("AutoUpdateTextPart").checked;
       var ipe_editor = false;
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
                ipe_editor = true;
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
             $.ajaxSetup({ cache: false });
             data = {html: html};
             data[SMLSWM_TOKEN_COOKIE_NAME] = document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME)[0].value;
             $.post("ajax_htmltoplaintext.php" + "?nocache=" + getNoCache() + "&ipe_editor=" + ipe_editor, data )
               .done(function( data ) {
                  document.getElementById("MailPlainText").value = HTMLEntityDecode(data);
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
                $.ajaxSetup({ cache: false });
                data = {html: html};
                data[SMLSWM_TOKEN_COOKIE_NAME] = document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME)[0].value;
                $.post("ajax_htmltoplaintext.php" + "?nocache=" + getNoCache(), data )
                  .done(function( data ) {
                     document.getElementById(MailPlainTextid).value = HTMLEntityDecode(data);
                     editor.resetDirty();
                  });
             }
          }
      });
   }
}

function InsertLoadingStatusOnSubmit(formId){
  if(window.navigator.userAgent.indexOf("Firefox") != -1) return; // not in FF, it's to slow never loads image
  try {
    if(formId){
      $("#" + formId).submit(function( event ) {
         var e = $(this)[0].onsubmit;
         if(e == undefined || e == null || String(e).indexOf("CheckAndRemoveCriticalChars") != -1)
           ShowModalDialog('templates/default/loading.htm', 32, 32, false, false, ' ', false, true);
         return true;
      });
    } else {
      $("form").submit(function( event ) {
         var e = $(this)[0].onsubmit;
         if(e == undefined || e == null || String(e).indexOf("CheckAndRemoveCriticalChars") != -1)
           ShowModalDialog('templates/default/loading.htm', 32, 32, false, false, ' ', false, true);
         return true;
      });
    }
  } catch(err) {}
}

function RemoveLoadingStatusOnSubmit(){
 try {
  if(_mdialogObj)
    _mdialogObj.close();
 } catch(e) {}
}

document.addEventListener('contextmenu', function(event) {
   var prevent = (!event.srcElement) || !(event.srcElement.tagName == "INPUT" || event.srcElement.tagName == "TEXTAREA");

   var types = ["checkbox", "radio", "button", "submit", "image"];

   if(!prevent && (event.srcElement.readOnly || event.srcElement.disabled || event.srcElement.tagName == "INPUT" && types.includes(event.srcElement.type)  ) ) // INPUT/TEXTAREA => property readOnly exists
      prevent = true;

   if(prevent)
      event.preventDefault();
}, true);

// no trim() function?
if(typeof String.prototype.trim !== 'function') {
   String.prototype.trim = function() {
      return this.replace(/^\s+|\s+$/, '');
   }
}

// Internet Explorer
if (!Array.prototype.includes) {
  Object.defineProperty(Array.prototype, "includes", {
    enumerable: false,
    value: function(obj) {
        var newArr = this.filter(function(el) {
          return el === obj;
        });
        return newArr.length > 0;
      }
  });
}
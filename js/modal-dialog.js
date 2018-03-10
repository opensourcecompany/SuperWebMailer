_mdialogObj = new DHTML_modalMessage();	// We only create one object of this class
_mdialogObj.setShadowOffset(5);	// Large shadow


function ShowModalDialog(dialogURL, dialogBoxWidth, dialogBoxHeight, CloseBtnText, showdialogTitle, dialogTitleText) {

  // default parameter emulation
  CloseBtnText = CloseBtnText || false;
  showdialogTitle = showdialogTitle || true;
  dialogTitleText = dialogTitleText || '';

  dialogBoxWidth += 12; // margin
  dialogBoxHeight += 60;
  if(navigator.userAgent.indexOf("Firefox") < 0 && navigator.userAgent.indexOf("Netscape") < 0)
    dialogBoxHeight -= 20;
  var MSIE10newer = false;

  if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
     var ieversion = new Number(RegExp.$1);
     if(ieversion > 9)
      MSIE10newer = true;
     if(ieversion <= 7)
       dialogBoxHeight += 20; // Kompatibilitaetsmodus??
  }


  _mdialogObj.CloseOnEscape = false;

  var dialogTitleHeight = 0;
  var dialogTitle = dialogTitleText;

  if(showdialogTitle){
    dialogTitleHeight = 23;
    if(!dialogTitle)
      dialogTitle = '<div class="modaldialogTitle" id="modaldialogTitle"><div id="modaldialogTitle_text" style="float: left; padding: 4px"></div><div style="text-align: right; padding: 2px"><img src="images/dialogclose.gif" onclick="closeDialog();" style="cursor: pointer;" /></div></div><div style="clear: both;"></div>';
  }

  var html = dialogTitle + '<div><iframe id="_mdialog_iframe" name="_mdialog_iframe" width="100%" height="' + dialogBoxHeight + '" src="' + dialogURL + '" frameborder="0" style="border-width:0px;" onload="this.contentWindow.focus();this.contentWindow.document.body.focus();"></iframe></div>';

  if(CloseBtnText)
    html += '<div id="closeBtnDiv"><input id="DialogCloseBtn" type="button" value="' + CloseBtnText + '" style="width:80px" onclick="closeDialog();"></div>';

 	_mdialogObj.setHtmlContent(html);
 	_mdialogObj.setCssClassMessageBox(false);
 	_mdialogObj.setSize(dialogBoxWidth, dialogBoxHeight + dialogTitleHeight);
 	_mdialogObj.setShadowDivVisible(true);	// Enable shadow for these boxes
  _mdialogObj.setSource(false);	// no html source since we want to use a static message here.
 	_mdialogObj.display();
  _mdialogObj.divs_content.style.fontSize ="inherit";
  if(CloseBtnText)
    document.getElementById("_mdialog_iframe").height = dialogBoxHeight - parseInt( window.getComputedStyle(document.getElementById("closeBtnDiv")).height ) - 8;

  var Escapefunction = function(e){ if ((e.which && e.which == 27) || (e.keyCode && e.keyCode == 27)) { try {parent.closeDialog();} catch(E) {} e.preventDefault ? e.preventDefault() : e.returnValue = false; e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true; return false; } };
  if(document.getElementById('_mdialog_iframe') && document.getElementById('_mdialog_iframe').contentWindow && document.getElementById('_mdialog_iframe').contentWindow.addEventListener){
     document.getElementById('_mdialog_iframe').contentWindow.focus();
     document.getElementById('_mdialog_iframe').contentWindow.addEventListener('keydown', Escapefunction, false);
     document.getElementById('_mdialog_iframe').contentWindow.addEventListener('keypress', Escapefunction, false);
     document.getElementById('_mdialog_iframe').contentWindow.addEventListener('load', function(e) { var s = document.getElementById('_mdialog_iframe').contentDocument.title; if(s.lastIndexOf('(') > -1) {s = s.substr(0, s.lastIndexOf('('));}  parent.document.getElementById('modaldialogTitle_text').innerHTML = s;  }, false);
  }
}

function closeDialog()
{
	_mdialogObj.close();
}





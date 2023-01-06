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

var CurrentLanguage="de";
var rsAdd = "Neues Standard-Element";
var rsNew = "Anderes Element...";
var rsMoveItem = "Verschieben";
var rsEditItem = "Bearbeiten";
//var rsDup = "Duplizieren";
var rsCSSEditItem = "Style bearbeiten";
var rsDeleteItem = "L&ouml;schen";
var rsDoubleClickToEdit = "Zum Bearbeiten hier klicken";

var rsTargetGroups = "Zielgruppen";

var rsApply = "&Uuml;bernehmen";
var rsCancel = rslocmessageCancel;//"Abbrechen";
var rsYes = rslocmessageYes; // "Ja";
var rsNo = rslocmessageNo; // "Nein";

var rsWarning = "Warnung";

var rsTemplateUnsuitable = "Die Vorlage ist f&uuml;r den Assistenten/Inplace Editor nicht geeignet, es sind keine bearbeitbaren Elemente enthalten.";

// onChange MailEditType
function WarnOnChangeMailEdit(){
  var NewMailEditType = $("#MailEditType").val();
  MessageBox("Warnung", "Bei &Auml;nderung des Bearbeitungstyps der E-Mail werden die &Auml;nderungen am Dokument nicht in den WYSIWYG-HTML-Editor/reinen HTML-Quelltext und in den Assistenten/Inplace Editor gegenseitig &uuml;bernommen.<br /><br />M&ouml;chten Sie wirklich den Bearbeitungstyp jetzt &auml;ndern?", messageTypeConfirmation, 380, 200, "ConfirmChangeMailEditEvent", false);
}

function ConfirmChangeMailEditEvent(value) {
  if(value) {
    if( $("#MailEditType").val() == "Editor" || $("#MailEditType").val() == "PlainSource" ) // new selection before confirmation
       SaveIPE(true);
    ShowItem("WizardHTMLText", false);
    ShowHideEMailTexts();
  } else {
    // recall old setting
    if( document.getElementById('IPEWizard').style.display == '' )
        $("#MailEditType").val("Wizard")
        else
        if(CKEDITOR.instances['MailHTMLText'] != null)
          $("#MailEditType").val("Editor");
          else
          if($('#MailEditType option').length > 2) // campaignEdit only
            $("#MailEditType").val("PlainSource");
  }
}
// onChange MailEditType /

var Modified = false;
var _Modified = function(value){ // SM => SWM compatibility
  Modified = value;
  if(Modified)
    $('#edit_window').contents().find("#COLORSCHEME_SELECT, #COLORSCHEME_SELECT_SCRIPT").remove(); // SM 13 special template
}

var IE10orOlder = false;
if ($.browser.msie && $.browser.version <= 10)
  IE10orOlder = true;

//if ( !(typeof html_entity_decode === "function")) {
//}

$("#edit_window").load(function() {
    if(IE10orOlder)
      $(this).height( $(this).parent().innerHeight() );

});

$(window).resize(function() {
    if(IE10orOlder)
       $("#edit_window").height( $("#edit_window").parent().innerHeight() - 8 );
});


function ShowIPEWizard(){
  ShowItem('IPEWizard', true);
}

function HideIPEWizard(){
  ShowItem('IPEWizard', false);
}

$("#MaxMinBtn").click(function(e){
  e.stopPropagation();

  var that = $(this).closest("#IPEWizard");

  if($(this).val() == "0") {
    var A = Array('height', 'width', 'opacity', 'z-index');
    var saveCSS = "";
    for(var i=0; i<A.length; i++){
      saveCSS += ", " + A[i] + ":" + $(that).css(A[i]);
    }

    var offset = $(that).position();
    saveCSS += ", top:" + offset.top;
    saveCSS += ", left:" + offset.left;

    $(that).attr("saveCSS", saveCSS);

    $(that).css('position', "absolute");
    $(that).css('top', 0);
    $(that).css('left', 0);
    $(that).css('height', "100%");
    $(that).css('width', "100%");
    $(that).css('opacity', 1);
    $(that).css('z-index', 1001);

    $("html").scrollTop(0);
    $("html").scrollLeft(0);
    $("body").scrollTop(0);
    $("body").scrollLeft(0);

    setTimeout( function() { $("html").scrollTop(0); $("html").scrollLeft(0); $("body").scrollTop(0); $("body").scrollLeft(0); }, 100 );

    var saveBody = $("body").css('width') + ',' + $("body").css('height');
    $("body").attr("saveBody", saveBody);

    $("body").css('width', 0);
    $("body").css('height', 0);
    $("html").css('overflow', 'hidden');

    $(this).val(1);
    if(IE10orOlder)
      $("#edit_window").height( $("#edit_window").parent().innerHeight() );
  } else
    if($(this).val() == "1") {

      $("html").css('overflow-x', 'hidden');
      $("html").css('overflow-y', 'scroll');

      var saveBody = $("body").attr("saveBody");
      $("html").attr("saveBody", "");
      $("body").css('width', saveBody.split(',')[0]);
      $("body").css('height', saveBody.split(',')[1]);

      var saveCSS = $(that).attr("saveCSS");
      $(that).attr("saveCSS", "");

      $(that).css('position', "static");
      saveCSS = saveCSS.split(',');
      for(var i=saveCSS.length - 1; i>=0; i--) {
         if( saveCSS[i].split(':')[1] == null ) continue;
         $(that).css( saveCSS[i].split(':')[0], saveCSS[i].split(':')[1].trim()  );
         if(saveCSS[i].split(':')[0].trim() == "height")
           var aHeight = saveCSS[i].split(':')[1].trim();
      }

      $(that).css('height', aHeight);

      $("html").attr("style", "");
      $("body").css("width", "");
      $("body").css("height", "");

      $(this).val(0);

      $(window).scrollTop(400);

      if(IE10orOlder)
        $("#edit_window").height( parseInt(aHeight) - $("#edit_window").parent().closest("TABLE").find("TD").height() - 7 );

    }

  return false;
});

$(document).ready(function(){

  $("*").dblclick(function(e){
    console.log("!!!DblClick detected");
    e.preventDefault();
  });

  $("#IPEShowPreviewBtn").on('mousedown keydown', function(e){
     showLoading(true);
  });

  $("#IPEShowPreviewBtn").on('mouseup mouseout', function(e){
     showLoading(false);
  });

  $("button").button();

  // ColorPicker
  $('#bordercolor, #fontcolor, #bgcolor, #borderColor, #hr_color, #bodybgcolor, #bodyfontcolor, #bodylinkcolor, #bodylinkvisitedcolor, #bodylinkactivecolor, #bodylinkhovercolor').ColorPicker({
  //	color: '#0000ff',
  // flat: true,
  	onShow: function (colpkr) {

    if(maxZ == null)
      var maxZ = 1;
    $("body > *").each(function() {
     var el = $(this);
     if($(el).css("position") != "static" && !$(el).is(".colorpicker") && parseInt($(el).css("z-index")) > maxZ) {
       maxZ = parseInt($(el).css("z-index")) + 1;
     }
     });
  	 $(".colorpicker").css("z-index", maxZ);

  		$(colpkr).fadeIn(500);
  		return false;
  	},
  	onSubmit: function(hsb, hex, rgb, el) {
  		$(el).val('#' + hex);
  		$(el).ColorPickerHide();
  	},
  	onBeforeShow: function () {
  		$(this).ColorPickerSetColor(this.value.substr(1));
  	},
  	onHide: function (colpkr) {
  		$(colpkr).fadeOut(500);
  		return false;
  	},
  	onChange: function (hsb, hex, rgb) {
  		$('#colorSelector div').css('backgroundColor', '#' + hex);
  	}
  });

  $('#bordercolor, #fontcolor, #bgcolor, #borderColor, #hr_color, #bodybgcolor, #bodyfontcolor, #bodylinkcolor, #bodylinkvisitedcolor, #bodylinkactivecolor, #bodylinkhovercolor, .colorpicker, .colorpicker_color').keydown(function(e) {
   	if ((e.which && e.which == 27) || (e.keyCode && e.keyCode == 27)) {
      e.stopPropagation();
      $(this).ColorPickerHide();
    		return false;
   	}
  });
});

function CloseColorPicker(){
  $('#bordercolor, #fontcolor, #bgcolor, #hr_color, #bodybgcolor, #bodyfontcolor, #bodylinkcolor, #bodylinkvisitedcolor, #bodylinkactivecolor, #bodylinkhovercolor').each(function(){
    $(this).ColorPickerHide();
  });
}


function showLoading(show){
  if(show == false){
    $("html, body").removeClass("ajaxLoading");
    $("input").removeClass("ajaxLoading");
    $("div").removeClass("ajaxLoading");
    return;
  }
  $("html, body").addClass("ajaxLoading");
  $("input").addClass("ajaxLoading");
  $("div").addClass("ajaxLoading");
}

// added SM 13, quicker unwrap DOM variant
// IPE_unwrap($(this).get(0));
function _IPE_unwrap(el){
    var parent = el.parentNode; // get the element's parent node
    while (el.firstChild){
        parent.insertBefore(el.firstChild, el); // move all children out of the element
    }
    parent.removeChild(el); // remove the empty element
}

function SaveIPE(DoClone){

  // not selected
  if(DoClone != true)
     if( !(document.getElementById('MailEditType').selectedIndex == 1 && document.getElementById('MailFormat').selectedIndex > 0) ) return true;

  showLoading(true);

  try{

    try {
      $('#edit_window').contents().find("#COLORSCHEME_SELECT, #COLORSCHEME_SELECT_SCRIPT").remove(); // SM 13 special template
    } catch(e){
    }

    var obj = $('#edit_window').contents();
    if(DoClone == true) {
      if($(obj).find('html').html() != null) {
          obj = $(obj).find('html').clone(false, false);
        }
        else {
         obj = $(obj).clone(false, false);
        }
    }

    if(DoClone != true) {
     	$(obj).find("input[type='hidden']").remove();
    }

    if($(obj).find('html').html() != null)
      var html = $(obj).find('html').html();
      else
      var html = $(obj).html();

    if(html == ""){
      console.log("!!!HTML is empty");
    }

    data = "<!DOCTYPE html>" + String.fromCharCode(0x3C) + 'html>' + html +'</html>';

   	$('#ipehtml').html("");
   	$('#ipehtml').append("<textarea name=\"WizardHTMLText\" style=\"width: 1px;height: 1px; border: 0px; padding: 0px; margin: 0px;\" id=\"WizardHTMLText\" rows=\"1\" cols=\"1\">"+data+"</textarea>");

    // cleanup html
    $(obj).find("#COLORSCHEME_SELECT").remove(); // SM 13 special template
   	$(obj).find(".ipe_repeater_toolbar, .ipe_toolbar").remove();
   	$(obj).find(".ipe_repeater, .ipe_repeater_item, #tableofcontents").children().unwrap();

    // changed SM 13, remove empty .ipe_repeater => editable <a> removed
    $(obj).find(".ipe_repeater").each(
   		function(){
       $(this).remove();
   	 }
    );

    $(obj).find(".ipe_item").each(
   		function(){
       // don't remove tag!!
       $(this).attr("class", null);
       $(this).attr("id", null);
       $(this).attr("title", null);
       $(this).attr("rel", null);
       $(this).attr("label", null);
       $(this).attr("tableofcontentstitle", null);
       $(this).attr("style", null);
       _IPE_unwrap($(this).get(0)); // changed SM 13, remove div tag
   	 }
    );

    $(obj).find(".editable").each(
   		function(){
   		  if($(this).value=="true" && $(this).get(0) && $(this).get(0).tagName.toUpperCase() == "DIV" && $(this).attr("style") == null || $(this).attr("style") == "")
         // remove tag
         if($(this).children().html() != null)
           $(this).children().unwrap();
           else {
             var html = $(this).html();
             $(this).parent().html($(this).html());
             $(this).remove();
           }
   	 }
    );


    // changed SM 13, remove editable=false
    $(obj).find("div[editable='false']").each(
    	function(){
     		  $(this).attr("editable", null);
     }
    );

    // remove firebug style
    $(obj).find("style").each(
   		function(){
   		  if($(this).html().indexOf('.firebug') > 0)
      		  $(this).remove();
   	 }
    );

    $(obj).find("script").remove();
   	$(obj).find("meta[name='SKYPE_TOOLBAR']").remove();
   	$(obj).find("input[type='hidden']").remove();
   	$(obj).find("link[href='ipe/css/inplace_edit.css']").remove();
   	$(obj).find("link[href='ipe/css/redmond/jquery-ui-1.8.14.custom.css']").remove();

    $(obj).find(".socialmedia_item").each(
   		function(){
   		  $(this).attr("class", null);
   	 }
    );

    $(obj).find('*[rel="tableofcontentsentry"]').each(
   		function(){
   		  $(this).attr("rel", null);
   	 }
    );

    //


    if($(obj).find('html').html() != null)
      var html = $(obj).find('html').html();
      else
      var html = $(obj).html();


    // remove comments
    //html = html.replace(/<!--[\s\S]*?-->/g, "");

    data = "<!DOCTYPE html>" + String.fromCharCode(0x3C) + 'html>' + html + '</html>';

    data = data.replace(/\n/g, ' ').replace(/ +/g, ' ').replace(/\t/g, '');


    // don't override CKEditor content
    if(DoClone == true)
      if( $(obj).find("#no_template_loaded").html() ){
        showLoading(false);
        //return true; we override it now
      }

   	$('#MailHTMLText').val( "" );
   	$('#MailHTMLText').val( data );

    $obj = null;

    showLoading(false);

    if(DoClone == true)
       return true;

    PageModified = false;
    Modified = false;

    return true;
  }catch(error){
    console.log("!!!" + "An Error occured" + "\n" + 'Name: ' + error.name + "\n" + 'Message: ' + error.message + "\n" + 'Stack: ' + error.stack);
    showLoading(false);
  }
}

// filemanager callback function
function SetUrl(Path) {
 $('#' + "imageurl").val(Path);
}

function OpenFileManager(elementId, reqWidth, reqHeight){
  if(reqWidth == undefined || reqWidth == null)
    reqWidth = -1;
  if(reqHeight == undefined || reqHeight == null)
    reqHeight = -1;

  var date = new Date();
  var nocache = date.getTime() / 1000;
  var height = (window.screen.height * 70 / 100);
  var width = (window.screen.width * 80 / 100);
  var filemanager = "ckeditor/filemanager/index.php?type=Images&FCKEditor=" + elementId + "&langCode=" + CurrentLanguage + "&IPEEditor=" + elementId + '&reqWidth=' + reqWidth + '&reqHeight=' + reqHeight;
  oWindow = openWindowWithPost(filemanager + "&nocache=" + nocache, {}, "ImageFileManager","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function OpenPixabayManager(elementId, reqWidth, reqHeight){
  if(reqWidth == undefined || reqWidth == null)
    reqWidth = -1;
  if(reqHeight == undefined || reqHeight == null)
    reqHeight = -1;

  var date = new Date();
  var nocache = date.getTime() / 1000;

  var height = (window.screen.height * 70 / 100);
  var width = (window.screen.width * 80 / 100);
  var filemanager = "ckeditor/pixabay/index.php?langCode=" + CurrentLanguage + "&IPEEditor=" + elementId + '&reqWidth=' + reqWidth + '&reqHeight=' + reqHeight;
  oWindow = openWindowWithPost(filemanager + "&nocache=" + nocache, {}, "ImageFileManager","width=" + width + ",height=" + height + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=yes,dependent=yes,modal=yes");
  oWindow.opener = window;
  oWindow.focus();
}

function LoadNewIPEElements(destelement_id){
  var date = new Date();
  var nocache = date.getTime() / 1000;
  $("#Loading").show();
  $.ajaxSetup({ cache: false });


 	jQuery.get('ajax_load_new_ipe_elements.php' + "?nocache=" + nocache, "", function(data){
 	   $("#" + destelement_id).html( data );
 	});

  $("#Loading").hide();


  // new items dialog selectable
		$( "#dialog-new_items" ).selectable({

   selecting: function(event, ui) {
      // prevent multiselect
  				$( "#dialog-new" ).find( ".ui-selected", this ).each(function() {
   				  $(this).removeClass("ui-selected");
  				});
   },
   selected: function(event, ui) {


    // prevent multiselect
		  var count = 0;
  		$( "#dialog-new" ).find( ".ui-selected" ).each(function() {
       count++;
       if($(this).get(0).tagName.toUpperCase() != "LI"){ // markiert das enthaltene Element nicht LI
         $(this).parent().addClass("ui-selected");
         $(this).removeClass("ui-selected");
       }
				});

				if(count > 1){
  				$( "#dialog-new" ).find( ".ui-selected" ).each(function() {
   				  count--;
         if(count > 1)
     				  $(this).removeClass("ui-selected");
  				});
				}
				//

    var ApplyBtn = $("#dialog-new").parent().find('.ui-dialog-buttonpane').find('button:first');
    ApplyBtn.attr("disabled", null);
    ApplyBtn.removeClass('ui-state-disabled');

    $(this).off('dblclick');
    $(this).dblclick(function() {
      var ApplyBtn = $("#dialog-new").parent().find('.ui-dialog-buttonpane').find('button:first');
      ApplyBtn.click();
    });

   },
   unselected: function(event, ui) {
    $(this).off('dblclick');
    var ApplyBtn = $("#dialog-new").parent().find('.ui-dialog-buttonpane').find('button:first');
    ApplyBtn.attr("disabled", "disabled");
    ApplyBtn.addClass('ui-state-disabled');
   },
   stop: function() {
			}
		});

}

function LoadMultilineToCKEditor(NotWhite){
  var iv = IsCKEditorVisible("multiline");

  if(NotWhite == undefined)
     NotWhite = false;
  MultiLineBGColorNotWhite = NotWhite;

  ShowCKEditor('multiline', 654, 180, 'MyDefaultMultiLineToolbar', false);

  try {
    CKEDITOR.instances.multiline.on('instanceReady', function(e) {
         if(MultiLineBGColorNotWhite){
             CKEDITOR.instances.multiline.document.getBody().setStyle('background-color', '#C0C0C0');
             CKEDITOR.instances.multiline.document.appendStyleText( 'body { background-color: #C0C0C0 }' );
         }

         CKEDITOR.instances.multiline.on('mode', function() {
           if(this.mode !== 'source' && MultiLineBGColorNotWhite){
             CKEDITOR.instances.multiline.document.getBody().setStyle('background-color', '#C0C0C0');
             CKEDITOR.instances.multiline.document.appendStyleText( 'body { background-color: #C0C0C0 }' );
           }
           if(this.mode !== 'source')
             CKEDITOR.instances.multiline.focus();
         });

         CKEDITOR.instances.multiline.focus();
    });

    if(iv)
      CKEDITOR.instances.multiline.fire('instanceReady');

  } catch(e){
  }
  if(!iv)
    AddImageSizeChangedEventToCKEditor('multiline');
}

function ShowPreview(){
  try {
  // remove no_template_loaded div
  $('#edit_window').contents().find("#no_template_loaded").remove();
  SaveIPE(true);
  ShowItem("WizardHTMLText", false);
  oWindow = window.open("about:blank", "IPEPreview","width=640,height=480,scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=no,dependent=no,modal=yes");
  var html = $('#MailHTMLText').val();
  if(html.indexOf("<title>") >= 0){
    html = html.replace(/\<title\>.*?\<\/title\>/g, '<title>'+$("#MailSubject").val()+'</title>');
  }
  oWindow.document.open();
  oWindow.document.writeln( html );
  Sleep(200); // for IE 9 we sleep, otherwise gives jquery ui errors
  oWindow.document.close();
  oWindow.opener = window;
  oWindow.focus();
  } catch(e){
  }
}

function MakeListEntriesSelectable(){

		// dialog-list-elements
		$( "#dialog-list-elements" ).selectable({
   selecting: function(event, ui) {
      // prevent multiselect
  				$( "#dialog-list" ).find( ".ui-selected", this ).each(function() {
   				  $(this).removeClass("ui-selected");
  				});
   },
   selected: function(event, ui) {

    // prevent multiselect
		  var count = 0;
  		$( "#dialog-list" ).find( ".ui-selected" ).each(function() {
       count++;
       if($(this).get(0).tagName.toUpperCase() != "LI"){ // markiert das enthaltene Element nicht LI
         $(this).parent().addClass("ui-selected");
         $(this).removeClass("ui-selected");
       }
				});

				if(count > 1){
  				$( "#dialog-list" ).find( ".ui-selected" ).each(function() {
   				  count--;
         if(count > 0) {
     				  $(this).removeClass("ui-selected");
     				}
  				});
				}
				//

    $("#dialog-list-edit").attr("disabled", null);
    $("#dialog-list-edit").removeClass('ui-state-disabled');

    $(this).off('dblclick');
    if(count > 0){
      $(this).dblclick(function() {
         $("#dialog-list-edit").click();
      });
    }

    ActivateDeactivateUpDownBtns();

   },
   unselected: function(event, ui) {
    $(this).off('dblclick');

    $("#dialog-list-edit").attr("disabled", "disabled");
    $("#dialog-list-edit").addClass('ui-state-disabled');

   },
   stop: function() {
				ActivateDeactivateUpDownBtns();
			}
		});
	$( "#dialog-list-elements" ).disableSelection();

 // delete element
 $( ".li_delete").off('click');
 $( ".li_delete").click(function() {
    $(this).parent().remove();

		  var count = 0;
  		$( "#dialog-list" ).find( ".ui-selected" ).each(function() {
       count++;
       if(count) return true; // break;
				});

    if(count == 0) {
      $("#dialog-list-edit").attr("disabled", "disabled");
      $("#dialog-list-edit").addClass('ui-state-disabled');
    }

    ActivateDeactivateUpDownBtns();
 });

}


function ActivateDeactivateUpDownBtns(){
 var current = $( "#dialog-list-elements" ).find('.ui-selected');
 if(current.get(0) == null || current.prev().get(0) == null) {
   $("#dialog-list-Up").attr("disabled", "disabled");
   $("#dialog-list-Up").addClass('ui-state-disabled');
 } else {
   $("#dialog-list-Up").attr("disabled", null);
   $("#dialog-list-Up").removeClass('ui-state-disabled');
 }

 if(current.get(0) == null || current.next().get(0) == null) {
   $("#dialog-list-Down").attr("disabled", "disabled");
   $("#dialog-list-Down").addClass('ui-state-disabled');
 } else {
   $("#dialog-list-Down").attr("disabled", null);
   $("#dialog-list-Down").removeClass('ui-state-disabled');
 }
}

function MoveSelectedListEntry(direction){
  var current = $( "#dialog-list-elements" ).find('.ui-selected');
  if(direction == "Up")
     current.prev().before(current);
     else
     current.next().after(current);
  ActivateDeactivateUpDownBtns();
}



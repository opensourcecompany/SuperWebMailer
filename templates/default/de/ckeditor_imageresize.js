  var supportsAutoImageResampling = true;
  var imagesizechangedData = {};
  var DontAskForResampleAgain = false;
  var DontShowResampleHintAgain = false;
  var AutoResampleImage = false;


  function AddImageSizeChangedEventToCKEditor(instancename){

    if (typeof instancename === 'object')
      instancename = instancename.name;

    CKEDITOR.instances[instancename].on('imagesizechanged', function(e) {

      var MessageBoxCaption = "<span style=\"text-align: justify;\">Durch das &Auml;ndern der sichtbaren Bildgr&ouml;&szlig;e, z.B. durch Ziehen mit Maus, &auml;ndert sich <b>nicht</b> die Original-Bildgr&ouml;&szlig;e des zu versendenden Bildes.<br />Viele E-Mail-Programme verwenden f&uuml;r die Darstellung der E-Mail die Original-Gr&ouml;&szlig;e, nicht die im HTML-Code angegebene Gr&ouml;&szlig;e des Bildes, dies kann damit zu Darstellungsfehlern f&uuml;hren.</span>";
      var MessageBoxDontAskForResampleAktionAgainCaption = '<br /><br /><input type="checkbox" id="DontAskForResampleAgain" name="DontAskForResampleAgain" /><label for="DontAskForResampleAgain">&nbsp;Aktion in dieser Bearbeitungssession merken</label>';
      var MessageBoxDontShowResampleHintAgainCaption = '<br /><br /><input type="checkbox" id="DontShowResampleHintAgain" name="DontShowResampleHintAgain" /><label for="DontShowResampleHintAgain">&nbsp;W&auml;hrend dieser Bearbeitungssession nicht erneut anzeigen</label>';

      var filename = e.data.element.getAttribute("src"); // data-cke-saved-src
      if(filename.indexOf('://') > -1 || filename.indexOf('newsletter_templates/') == 0 || filename.indexOf('/images/socialmedia/') > 0){
        if(!DontShowResampleHintAgain)
          MessageBox("Warnung", MessageBoxCaption + MessageBoxDontShowResampleHintAgainCaption, messageTypeWarning, 438, 200, "DontShowResampleHintAgainEvent");
        return false; // don't trigger again
      }

      imagesizechangedData["filename"] = filename;
      imagesizechangedData["old_dimensions"] = e.data.old_dimensions;
      imagesizechangedData["new_dimensions"] = e.data.new_dimensions;

      if(!DontAskForResampleAgain)
         MessageBox("Warnung", MessageBoxCaption + "<br /><br />Soll das Bild auf die eingestellte Bildgr&ouml;&szlig;e umgerechnet und das Original-Bild damit <b>&uuml;berschrieben</b> werden?" + MessageBoxDontAskForResampleAktionAgainCaption, messageTypeConfirmation, 438, 240, "ConfirmResampleImageEvent", false);
         else
          ConfirmResampleImageEvent(AutoResampleImage, null);
      return false; // don't trigger again
    });
  }
  function DontShowResampleHintAgainEvent(value, form){
    if(form && form.elements["DontShowResampleHintAgain"].checked)
      DontShowResampleHintAgain = true;
  }

  function ConfirmResampleImageEvent(value, form){
    if(form && form.elements["DontAskForResampleAgain"].checked)
      DontAskForResampleAgain = true;
    AutoResampleImage = value;
    if(!value) return;

    var data = {};
    data[SMLSWM_TOKEN_COOKIE_NAME] = document.getElementsByName(SMLSWM_TOKEN_COOKIE_NAME)[0].value;
    data["ckCsrfToken"] = CKEDITOR.tools.getCsrfToken();

    var MessageBoxErrorCaption = "Die Speicherung des Bildes mit der neuen Gr&ouml;&szlig;e ist fehlgeschlagen, Fehler:<br /><br />";

    $.ajax({
      url: 'ckeditor/filemanager/connectors/php/resize.php?mode=resample&filename=' + imagesizechangedData.filename + '&current_width=' + imagesizechangedData.old_dimensions.width + '&current_height=' + imagesizechangedData.old_dimensions.height + '&newwidth=' + imagesizechangedData.new_dimensions.width + '&newheight=' + imagesizechangedData.new_dimensions.height + '&_=' + new Date().getTime() / 1000,
      type: 'POST',
      data: data,
      beforeSend: function(data) {
       ShowModalDialog('templates/default/loading.htm', 32, 32, false, false, ' ', false, true);
       return true;
      },
      success: function(data) {
        RemoveLoadingStatusOnSubmit();
        if(data.indexOf("textarea") > -1){
           data = data.substr(data.indexOf('>') + 1);
           data = data.substr(0, data.lastIndexOf('</'));
        }
        data = JSON.parse(data);
        if(data && data["Code"] == 0){
          // success
        }else{
          if(!data)
            var errormessage = "Unknown response " + data;
            else
            var errormessage = data["Error"];
          MessageBox("Fehler", MessageBoxErrorCaption + errormessage, messageTypeError, 380, 150);
        }
      },
      error: function(jqXHR) {
        RemoveLoadingStatusOnSubmit();
        MessageBox("Fehler", MessageBoxErrorCaption + jqXHR.status + ' ' + jqXHR.statusText, messageTypeError, 380, 150);
      },
      fail: function(jqXHR) {
        RemoveLoadingStatusOnSubmit();
        MessageBox("Fehler", MessageBoxErrorCaption + jqXHR.status + ' ' + jqXHR.statusText, messageTypeError, 380, 150);
      },
      complete: function() {
        RemoveLoadingStatusOnSubmit();
      }
    })
  }

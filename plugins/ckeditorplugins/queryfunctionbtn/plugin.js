	/************************************************************************************************************
  #############################################################################
  #                SuperMailingList / SuperWebMailer                          #
  #               Copyright © 2007 - 2017 Mirko Boeer                         #
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

CKEDITOR.plugins.add('queryfunctionbtn',
  {
   	lang : ['en','de'],
    init:function(editor) {

        	editor.ui.addButton("queryfunctionbtn",{
        					label:editor.lang.queryfunctionbtn.label,
        					command:'insertfunction_cmd',
        					icon:this.path + "btncustomfct.gif"
        	});

        	var cmd = editor.addCommand('insertfunction_cmd', {exec:showInsertFunctionDialogPlugin});
        	cmd.modes={wysiwyg:1,source:0};
         cmd.canUndo=true;

	   }
});

var mypluginspath = CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf("ckeditor/"));

function showInsertFunctionDialogPlugin(e){
   var date = new Date();
   var nocache = date.getTime() / 1000;
   ShowModalDialog(mypluginspath + "browsefunctions.php?form=" + CKEDITOR.config['FormName'] + "&formElement=" + e.name + "&IsFCKEditor=true" + "&nocache=" + nocache, 708, 400);
   // the current FCK Editor
   CurrentFCKEditor = CKEDITOR;
}


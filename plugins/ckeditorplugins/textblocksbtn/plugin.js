	/************************************************************************************************************
  #############################################################################
  #                SuperMailingList / SuperWebMailer                          #
  #               Copyright © 2007 - 2018 Mirko Boeer                         #
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

CKEDITOR.plugins.add('textblocksbtn',
  {
   	lang : ['en','de'],
    init:function(editor) {

        	editor.ui.addButton("textblocksbtn",{
        					label:editor.lang.textblocksbtn.label,
        					command:'inserttextblock_cmd',
        					icon:this.path + "btntextblocks.gif"
        	});

        	var cmd = editor.addCommand('inserttextblock_cmd', {exec:showInsertTextblocksDialogPlugin});
        	cmd.modes={wysiwyg:1,source:0};
         cmd.canUndo=true;

	   }
});

var mypluginspath = CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf("ckeditor/"));

function showInsertTextblocksDialogPlugin(e){
   var date = new Date();
   var nocache = date.getTime() / 1000;

   ShowModalDialog(mypluginspath + "browsetextblocks.php?form=" + CKEDITOR.config['FormName'] + "&formElement=" + e.name + "&IsFCKEditor=true" + "&nocache=" + nocache, 708, 400);

   // the current FCK Editor
   CurrentFCKEditor = CKEDITOR;
}


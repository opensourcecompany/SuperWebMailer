	/************************************************************************************************************
  #############################################################################
  #                SuperMailingList / SuperWebMailer                          #
  #               Copyright © 2007 - 2011 Mirko Boeer                         #
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

CKEDITOR.plugins.add('loadfilebtn',
  {
   	lang : ['en','de'],
    init:function(editor) {

        	editor.ui.addButton && editor.ui.addButton('loadfilebtn',{
        					label:editor.lang.loadfilebtn.label,
        					command:'loadfile_cmd',
        					icon:this.path + "btnloadfile.gif"
        	});

        	var cmd = editor.addCommand('loadfile_cmd', {exec:showLoadFileDialogPlugin});
        	cmd.modes={wysiwyg:1,source:0};
         cmd.canUndo=true;

	   }
});

var mypluginspath = CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf("ckeditor/"));

function showLoadFileDialogPlugin(editor){
   var date = new Date();
   var nocache = date.getTime() / 1000;

   oWindow = window.open(mypluginspath + "loadfile.php?form=" + CKEDITOR.config['FormName'] + "&formElement=" + editor.name + "&IsFCKEditor=true" + "&nocache=" + nocache, "loadfileEditWnd","width=750,height=580,scrollbars=yes,status=yes,toolbar=no,resizable=no,location=yes");

   oWindow.opener = window;
   // the current FCK Editor
   CurrentFCKEditor = CKEDITOR;
}


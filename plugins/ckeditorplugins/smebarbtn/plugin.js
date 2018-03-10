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

CKEDITOR.plugins.add('smebarbtn',
  {
   	lang : ['en','de'],
    init:function(editor) {

        	editor.ui.addButton("smebarbtn",{
        					label:editor.lang.smebarbtn.label,
        					command:'smebar_cmd',
        					icon:this.path + "btnsme.gif"
        	});

        	var cmd = editor.addCommand('smebar_cmd', {exec:showLoadSMEBarPlugin});
        	cmd.modes={wysiwyg:1,source:0};
         cmd.canUndo=true;

	   }
});

var mypluginspath = CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf("ckeditor/"));

function showLoadSMEBarPlugin(editor){
   var date = new Date();
   var nocache = date.getTime() / 1000;

   oWindow = window.open(mypluginspath + "loadsmebar.php?formElement=" + editor.name + "&nocache=" + nocache, "loadsmebarEditWnd","width=766,height=580,scrollbars=yes,status=yes,toolbar=no,resizable=no,location=yes");

   oWindow.opener = window;
   // the current FCK Editor
   CurrentFCKEditor = CKEDITOR;
}


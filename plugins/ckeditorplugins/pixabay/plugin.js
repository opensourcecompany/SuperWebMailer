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

CKEDITOR.plugins.add('pixabaybtn',
  {
   	lang : ['en','de'],
    init:function(editor) {

        	editor.ui.addButton && editor.ui.addButton('pixabaybtn',{
        					label:editor.lang.pixabaybtn.label,
        					command:'pixabay_cmd',
        					icon:this.path + "pixabay_grey.png"
        	});

        	var cmd = editor.addCommand('pixabay_cmd', {exec:showPixabayDialogPlugin});
        	cmd.modes={wysiwyg:1,source:0};
         cmd.canUndo=true;

	   }
});

function showPixabayDialogPlugin(editor, destUrlfield){
   var date = new Date();
   var nocache = date.getTime() / 1000;

   if (typeof destUrlfield === 'object')
     destUrlfield="";

   var w = (window.screen.width * 80 / 100);
   var h = (window.screen.height * 70 / 100);
   oWindow = openWindowWithPost(CKEDITOR.basePath + "pixabay/index.php?nocache=" + nocache + "&CKEditor=" + editor.name + '&language=' + CKEDITOR.lang.detect('en') + (destUrlfield != "" ? '&destUrlfield=' + destUrlfield : ''), {}, "PixabayDialogWnd", "width=" + w + ",height=" + h + ",scrollbars=yes,status=yes,toolbar=no,resizable=yes,location=no")
   oWindow.opener = window;
   // the current FCK Editor
   CurrentFCKEditor = CKEDITOR;
}


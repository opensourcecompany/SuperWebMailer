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

var supported_targetgroups_Elements = {a:1, p:1, div:1, span:1, img:1, hr:1, h1:1, h2:1, h3:1, h4:1, h5:1, h6:1};

CKEDITOR.plugins.add('targetgroups',
  {
   	lang : ['en','de'],
    icons: 'targetgroups',
    init: function(editor) {

    var lang = editor.lang.targetgroups;

   	var cmd = editor.addCommand('targetgroups_cmd', {exec:targetgroups_cmd} );
   	cmd.modes={wysiwyg:1,source:0};
    cmd.canUndo=true;
    //

   	var cmd = editor.addCommand('targetgroupsTABLE_cmd', {exec:targetgroupsTABLE_cmd} );
   	cmd.modes={wysiwyg:1,source:0};
    cmd.canUndo=true;
    //

   	var cmd = editor.addCommand('targetgroupsTR_cmd', {exec:targetgroupsTR_cmd} );
   	cmd.modes={wysiwyg:1,source:0};
    cmd.canUndo=true;

    // dialog
    CKEDITOR.dialog.add('targetgroupsDialog', this.path + 'dialogs/targetgroups.js');

    	editor.ui.addButton && editor.ui.addButton( 'targetgroupsBtn', {
        label: editor.lang.targetgroups.Buttonlabel,
        command: 'targetgroups_cmd',
   					icon:this.path + "btntgroups.gif"
    });
//
    if (editor.contextMenu) {

        editor.addMenuGroup( 'targetgroups' );
        editor.addMenuItem( 'targetgroupsItem', {
            label: editor.lang.targetgroups.Buttonlabel,
            icon: this.path + 'btntgroups.gif',
            command: 'targetgroups_cmd',
            group: 'targetgroups'
        });

        editor.addMenuItem( 'targetgroupsTABLEItem', {
            label: editor.lang.targetgroups.tgroupForTable,
            icon: this.path + 'btntgroups.gif',
            command: 'targetgroupsTABLE_cmd',
            group: 'targetgroups'
        });

        editor.addMenuItem( 'targetgroupsTRItem', {
            label: editor.lang.targetgroups.tgroupForTD,
            icon: this.path + 'btntgroups.gif',
            command: 'targetgroupsTR_cmd',
            group: 'targetgroups'
        });

     			editor.contextMenu.addListener(function(element, selection) {

     				return {
     					targetgroupsItem: CKEDITOR.TRISTATE_OFF,
          targetgroupsTABLEItem: CKEDITOR.TRISTATE_OFF,
          targetgroupsTRItem: CKEDITOR.TRISTATE_OFF
     				};

     			} );
    		}

      editor.on('selectionChange', function( ev )
       {
        if ( ev.editor.readOnly ){
           editor.getCommand( 'targetgroups_cmd' ).setState( CKEDITOR.TRISTATE_DISABLED );
           editor.getCommand( 'targetgroupsTABLE_cmd' ).setState( CKEDITOR.TRISTATE_DISABLED );
           editor.getCommand( 'targetgroupsTR_cmd' ).setState( CKEDITOR.TRISTATE_DISABLED );
          	return;
         }

        var element = ev.data.path.lastElement && ev.data.path.lastElement.getAscendant(supported_targetgroups_Elements, true);

        var command = editor.getCommand( 'targetgroups_cmd' );
        if (element) {

            var mi = ev.editor.getMenuItem('targetgroupsItem');

            var atext = editor.lang.targetgroups.tgroupForTag;

            if(mi) {mi.label = atext.replace(/%s/, element.getName().toUpperCase() );}

            command.setState( CKEDITOR.TRISTATE_OFF );
            }
          	else {
            command.setState( CKEDITOR.TRISTATE_DISABLED );
           }

        // TABLE
        var command = editor.getCommand( 'targetgroupsTABLE_cmd' );
        element = ev.data.path.lastElement && ev.data.path.lastElement.getAscendant('table', true);
        if ( element  ) {
            command.setState( CKEDITOR.TRISTATE_OFF );
            }
          	else {
            command.setState( CKEDITOR.TRISTATE_DISABLED );
           }

        // TR
        var command = editor.getCommand( 'targetgroupsTR_cmd' );
        element = ev.data.path.lastElement && ev.data.path.lastElement.getAscendant('tr', true);
        if ( element ) {
            command.setState( CKEDITOR.TRISTATE_OFF );
            }
          	else {
            command.setState( CKEDITOR.TRISTATE_DISABLED );
           }

       } );


    }
});


// http://docs.ckeditor.com/#!/guide/plugin_sdk_sample_2
function targetgroups_cmd(ev){
   var element = targetgroupsGetSelectedElement(ev);
   if(!element) return;
   element = element.getAscendant(supported_targetgroups_Elements, true);
   if(!element) return;

   CKEDITOR.config._targetgroupsSender = 'ELEMENT';

   ev.openDialog( 'targetgroupsDialog' );
}

function targetgroupsTABLE_cmd(ev){
   var element = targetgroupsGetSelectedElement(ev);
   if(!element) return;
   element = element.getAscendant('table', true);
   if(!element) return;

   CKEDITOR.config._targetgroupsSender = 'TABLE';

   ev.openDialog( 'targetgroupsDialog' );
}

function targetgroupsTR_cmd(ev){
   var element = targetgroupsGetSelectedElement(ev);
   if(!element) return;
   element = element.getAscendant('tr', true);
   if(!element) return;

   CKEDITOR.config._targetgroupsSender = 'TR';

   ev.openDialog( 'targetgroupsDialog' );
}


function targetgroupsGetSelectedElement(editor){
  var element = editor.getSelection() && editor.getSelection().getSelectedElement();
  if(!element)
    element = editor.getSelection() && editor.getSelection().getStartElement();
  return element;
}

function plugin_Sleep(milliseconds, callback) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
  if(callback)
    callback();
}

function LoadCKEditorTargetGroups(){
  var editor = CKEDITOR.currentInstance;
  if(!editor) { return;}
  var element = targetgroupsGetSelectedElement(editor);
  if(!element) { return;}

  if(CKEDITOR.config._targetgroupsSender == 'ELEMENT'){
    element = element.getAscendant(supported_targetgroups_Elements, true);
    if(!element) { return;}
  } else if(CKEDITOR.config._targetgroupsSender == 'TABLE') {
       element = element.getAscendant('table', true);
       if(!element) { return;}
     }else if(CKEDITOR.config._targetgroupsSender == 'TR') {
         element = element.getAscendant('tr', true);
         if(!element) { return;}
     }
  document.getElementById("__targetgroupsIframe").contentWindow.LoadTargetGroups(element.getAttribute('target_groups'));
}

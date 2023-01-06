	/************************************************************************************************************
  #############################################################################
  #                SuperMailingList / SuperWebMailer                          #
  #               Copyright © 2007 - 2021 Mirko Boeer                         #
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

CKEDITOR.plugins.add('tableclonerow',
  {
    requires: 'contextmenu,pastetools',
    lang : ['en','de'],

    init: function(editor) {

// pasting row(s) to current table
// not supported another table in table row

 			editor.pasteTools.register( {
 				priority: 200,

 				canHandle: function( evt ) {
       var data = evt.data,
 						clipboardHtml = data.dataValue || CKEDITOR.plugins.pastetools.getClipboardData( data, 'text/html' );

  					if ( !clipboardHtml ) {
  						return false;
  					}

       var canHandle = true;
       var element = tableclonerowGetSelectedElement(evt.editor);
       if(element)
          element = element.getAscendant('tr', true);
       if(!element)
         canHandle = false;

       if(canHandle){
         // remove fake <br data= from CKEditor itself
         if(clipboardHtml.search(/<br/ig) == 0 && clipboardHtml.search(/<table/ig) > -1){
           clipboardHtml = clipboardHtml.substr( clipboardHtml.search(/>/ig) + 1 ).trimLeft();
         }
         // remove outer <div
         if(clipboardHtml.search(/<div/ig) == 0 && clipboardHtml.search(/<table/ig) > -1){
            clipboardHtml = clipboardHtml.substr( clipboardHtml.search(/>/ig) + 1 ).trimLeft();
            clipboardHtml = clipboardHtml.replace(/<\/table>(.*?)<\/div>/i, "</table>");
         }
         // remove first occurence of table and tbody tag
         clipboardHtml = clipboardHtml.replace(/<table(.*?)>/i, "");
         clipboardHtml = clipboardHtml.replace(/<tbody>/i, "");
         clipboardHtml = clipboardHtml.replace(/<\/tbody>/i, "").trim();
         clipboardHtml = clipboardHtml.replace(/<\/table>/i, "").trim();

         //<tr> must be first and </tr> last element, when not, we don't handle it
         canHandle = clipboardHtml.search(/<table/ig) == -1 && clipboardHtml.search(/<tr/ig) == 0 && clipboardHtml.search(/<td/ig) != -1;

         // find lastpos of </tr>
         if(canHandle){
           var re = /<\/tr>/gi;
           var LastPos = 0;
           while(re.test(clipboardHtml))
             LastPos = re.lastIndex;

           canHandle = LastPos == clipboardHtml.length;
         }
       }

       if(canHandle){ //cellCount of current table must be equal new cellCount, colSpan not! supported
         var currentCellCount = element.$.cells.length;
         var rowCount = clipboardHtml.split(/<tr/ig).length - 1;
         var cellCount = (clipboardHtml.split(/<td/ig).length - 1) / rowCount;
         canHandle = currentCellCount == cellCount;
       }

  					return canHandle;
 				},

 				handle: function( evt, next ) {
 					var data = evt.data,
 						clipboardHtml = data.dataValue || CKEDITOR.plugins.pastetools.getClipboardData( data, 'text/html' );

      // remove fake <br data= from CKEditor itself
      if(clipboardHtml.search(/<br/ig) == 0 && clipboardHtml.search(/<table/ig) > -1){
        clipboardHtml = clipboardHtml.substr( clipboardHtml.search(/>/ig) + 1 ).trimLeft();
      }
      // remove outer <div
      if(clipboardHtml.search(/<div/ig) == 0 && clipboardHtml.search(/<table/ig) > -1){
         clipboardHtml = clipboardHtml.substr( clipboardHtml.search(/>/ig) + 1 ).trimLeft();
         clipboardHtml = clipboardHtml.replace(/<\/table>(.*?)<\/div>/i, "</table>");
      }

      var done = false;

      var element = tableclonerowGetSelectedElement(evt.editor);

      if(element)
        element = element.getAscendant('tr', true);

      if(element){

        evt.editor.fire( 'saveSnapshot' );
        try {
          var temp = new CKEDITOR.dom.element('div');
         	temp.$.innerHTML = clipboardHtml;

          var el = temp.getFirst().remove();

          var nodeList = el.getChild(0).getChildren();
          for(var i = nodeList.count() - 1; i > -1; i--){
            var newRow = nodeList.getItem(i);
            newRow.insertBefore( element );
            element = newRow;
          }

          temp.remove(false);
          temp = null;

          done = true;
        }
        catch (e) {
          // ignore errors let editor do it
        }
        evt.editor.fire( 'saveSnapshot' );
      }

      if(done)
        evt.cancel(); // cancel pasting content
        else
   					next(); // next filter
 				}
 			} );


// tableclonerow cmds
    var lang = editor.lang.tableclonerow;

   	var cmd = editor.addCommand('tableclonerow_InsertRowBeforeWithApplyingContent_cmd', {exec:tableclonerow_InsertRowBeforeWithApplyingContent_cmd} );
   	cmd.modes={wysiwyg:1,source:0};
    cmd.canUndo=true;
    editor.keystrokeHandler.keystrokes[CKEDITOR.CTRL + CKEDITOR.ALT + 66 /* B */] = 'tableclonerow_InsertRowBeforeWithApplyingContent_cmd';

   	var cmd = editor.addCommand('tableclonerow_InsertRowAfterWithApplyingContent_cmd', {exec:tableclonerow_InsertRowAfterWithApplyingContent_cmd} );
   	cmd.modes={wysiwyg:1,source:0};
    cmd.canUndo=true;
    editor.keystrokeHandler.keystrokes[CKEDITOR.CTRL + CKEDITOR.ALT + 65 /* A */] = 'tableclonerow_InsertRowAfterWithApplyingContent_cmd';

   	var cmd = editor.addCommand('tableclonerow_SelectRow_cmd', {exec:tableclonerow_SelectRow_cmd} );
   	cmd.modes={wysiwyg:1,source:0};
    cmd.canUndo=true;

   	var cmd = editor.addCommand('TableCloneItSelf_cmd', {exec:TableCloneItSelf_cmd} );
   	cmd.modes={wysiwyg:1,source:0};
    cmd.canUndo=true;
    editor.keystrokeHandler.keystrokes[CKEDITOR.CTRL + CKEDITOR.ALT + 68 /* D */] = 'TableCloneItSelf_cmd';

    if (editor.contextMenu) {

        editor.addMenuItem( 'TableCloneItSelf', {
            label: editor.lang.tableclonerow.TableClone,
            command: 'TableCloneItSelf_cmd',
            group: 'table', //CKEditor group Table https://github.com/ckeditor/ckeditor4/blob/master/plugins/table/plugin.js
            order: 2
        });

        editor.addMenuGroup( 'Tableclonerow', 5 );

        editor.addMenuItems({
            Tableclonerow :
            {
              label : editor.lang.tableclonerow.tableclonerow,
              group : 'Tableclonerow',
              order : 21,
              getItems : function() {
                return {
                 tableclonerow_InsertRowBeforeWithApplyingContentItem: CKEDITOR.TRISTATE_OFF,
                 tableclonerow_InsertRowAfterWithApplyingContentItem: CKEDITOR.TRISTATE_OFF,
                 tableclonerow_SelectRow_cmdItem: CKEDITOR.TRISTATE_OFF,
                };
              }
            },

            tableclonerow_InsertRowBeforeWithApplyingContentItem :
            {
              label : editor.lang.tableclonerow.InsertRowBeforeWithApplyingContent,
              group : 'Tableclonerow',
              command : 'tableclonerow_InsertRowBeforeWithApplyingContent_cmd',
              order : 22
            },

            tableclonerow_InsertRowAfterWithApplyingContentItem :
            {
              label : editor.lang.tableclonerow.InsertRowAfterWithApplyingContent,
              group : 'Tableclonerow',
              command : 'tableclonerow_InsertRowAfterWithApplyingContent_cmd',
              order : 23
            },

            tableclonerow_SelectRow_cmdItem :
            {
              label : editor.lang.tableclonerow.SelectRow,
              group : 'Tableclonerow',
              command : 'tableclonerow_SelectRow_cmd',
              order : 24
            }

        });

     			editor.contextMenu.addListener(function(element, selection) {

           var ret = {};
           if(element.getAscendant('tr', true))
               ret.Tableclonerow = CKEDITOR.TRISTATE_OFF;
           if(element.getAscendant('table', true))
               ret.TableCloneItSelf = CKEDITOR.TRISTATE_OFF;
           return ret;

     			});
    		}

      editor.on('selectionChange', function( ev )
       {
        if ( ev.editor.readOnly ){
           editor.getCommand( 'tableclonerow_InsertRowBeforeWithApplyingContent_cmd' ).setState( CKEDITOR.TRISTATE_DISABLED );
           editor.getCommand( 'tableclonerow_InsertRowAfterWithApplyingContent_cmd' ).setState( CKEDITOR.TRISTATE_DISABLED );
           editor.getCommand( 'tableclonerow_SelectRow_cmd' ).setState( CKEDITOR.TRISTATE_DISABLED );
           editor.getCommand( 'TableCloneItSelf_cmd' ).setState( CKEDITOR.TRISTATE_DISABLED );
          	return;
         }

        // TR
        var commandBefore = editor.getCommand( 'tableclonerow_InsertRowBeforeWithApplyingContent_cmd' );
        var commandAfter = editor.getCommand( 'tableclonerow_InsertRowAfterWithApplyingContent_cmd' );
        var commandSelect = editor.getCommand( 'tableclonerow_SelectRow_cmd' );
        var element = ev.data.path.lastElement && ev.data.path.lastElement.getAscendant('tr', true);
        if ( element ) {
            commandBefore.setState( CKEDITOR.TRISTATE_OFF );
            commandAfter.setState( CKEDITOR.TRISTATE_OFF );
            commandSelect.setState( CKEDITOR.TRISTATE_OFF );
            }
          	else {
            commandBefore.setState( CKEDITOR.TRISTATE_DISABLED );
            commandAfter.setState( CKEDITOR.TRISTATE_DISABLED );
            commandSelect.setState( CKEDITOR.TRISTATE_DISABLED );
           }

        // TABLE
        var command = editor.getCommand( 'TableCloneItSelf_cmd' );
        var element = ev.data.path.lastElement && ev.data.path.lastElement.getAscendant('table', true);
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

// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_dom_element.html
function tableclonerow_InsertRowBeforeWithApplyingContent_cmd(ev){
   var element = tableclonerowGetSelectedElement(ev);
   if(!element) return;
   element = element.getAscendant('tr', true);
   if(!element) return;

   ev.fire( 'saveSnapshot' );
   var newRow = element.clone( true, true );

   newRow.insertBefore( element );
   ev.fire("blur"); // internal hack for "dirty" check
}

function tableclonerow_InsertRowAfterWithApplyingContent_cmd(ev){
   var element = tableclonerowGetSelectedElement(ev);
   if(!element) return;
   element = element.getAscendant('tr', true);
   if(!element) return;

   ev.fire( 'saveSnapshot' );
   var newRow = element.clone( true, true );

   newRow.insertAfter( element );
   ev.fire("blur"); // internal hack for "dirty" check
}

function tableclonerow_SelectRow_cmd(ev){
   var element = tableclonerowGetSelectedElement(ev);
   if(!element) return;
   element = element.getAscendant('tr', true);
   if(!element) return;

   ev.getSelection().selectElement( element );
}

function TableCloneItSelf_cmd(ev){
   var element = tableclonerowGetSelectedElement(ev);
   if(!element) return;
   element = element.getAscendant('table', true);
   if(!element) return;

   var parent_element = element.getAscendant('table', false); // find parent of table element

   ev.fire( 'saveSnapshot' );
   var newTable = element.clone( true, true );

   newTable.insertAfter( element );

   if(!parent_element){ // parent is not table, than we insert a <div>&nbsp;</div> block
     var newElement = new CKEDITOR.dom.element('div');
     newElement.appendHtml("&nbsp;");
     newElement.insertBefore(newTable);
   }

   ev.fire("blur"); // internal hack for "dirty" check
}

function tableclonerowGetSelectedElement(editor){
  var element = editor.getSelection() && editor.getSelection().getSelectedElement();
  if(!element)
    element = editor.getSelection() && editor.getSelection().getStartElement();
  return element;
}



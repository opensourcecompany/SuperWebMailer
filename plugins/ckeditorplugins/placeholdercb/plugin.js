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

CKEDITOR.plugins.add('placeholdercb',
  {
    requires : [ 'richcombo' ],
   	lang : ['en','de'],
    init:function(editor) {


      lang = editor.lang.format;
      //this.add('value', 'drop_text', 'drop_label');

      editor.ui.addRichCombo( 'placeholdercb',
         {
            label : editor.lang.placeholdercb.label,
            title : editor.lang.placeholdercb.title,
            voiceLabel : editor.lang.placeholdercb.title,
//            className : 'cke_combo__format', //'cke_placeholder' chrome problems!
            multiSelect : false,

            panel :
            {
          					css: [ CKEDITOR.skin.getPath( 'editor' ) ].concat( editor.config.contentsCss ),
               voiceLabel : lang.panelVoiceLabel,
               width: 500
            },

            init : function()
            {
               this.startGroup( editor.lang.placeholdercb.label );
               tags = GetPlaceholders(editor);
               //this.add('value', 'drop_text', 'drop_label');
               for (var this_tag in tags){
                  this.add(tags[this_tag][0], tags[this_tag][1], placeholderplugin_html_entity_decode(tags[this_tag][1]).replace("<b>", "").replace("</b>", "") );
               }
            },

            onClick : function( value )
            {
               editor.focus();
               editor.fire( 'saveSnapshot' );

               if (value.indexOf("Link") > 0)
                  value = '<a href="' + value + '">' + value + '</a>';

               editor.insertHtml(value);
               editor.fire( 'saveSnapshot' );
            }
         });
   }
});


function GetPlaceholders(editor){
 var tags = new Array();
 var t = 0;
 var arrPlaceholders = null;
 if ( (parent.arrSubscribePlaceholders != undefined) && (editor.name == 'OptInConfirmationMailHTMLText') )
			for (var i = 0; i < parent.arrSubscribePlaceholders.length; i++)
				tags[t++] = [parent.arrSubscribePlaceholders[i][0], '<b>' + parent.arrSubscribePlaceholders[i][1] + '</b>'];

 if ( (parent.arrUnsubscribePlaceholders != undefined) && (editor.name == 'OptOutConfirmationMailHTMLText') )
			for (var i = 0; i < parent.arrUnsubscribePlaceholders.length; i++)
				tags[t++] = [parent.arrUnsubscribePlaceholders[i][0], '<b>' + parent.arrUnsubscribePlaceholders[i][1] + '</b>'];

	if ((parent.arrPlaceholderItems ? arrPlaceholders = parent.arrPlaceholderItems : arrPlaceholders = arrPlaceholderItems) != undefined) {
   for (var i = 0; i < arrPlaceholders.length; i++)
			 tags[t++] = [arrPlaceholders[i][0], arrPlaceholders[i][1]];
	}

 if ( (parent.arrNewsletterUnsubscribePlaceholders != undefined) && (editor.name == 'OptInConfirmedMailHTMLText' || editor.name == 'MailHTMLText' || editor.name == 'multiline') )
			for (var i = 0; i < parent.arrNewsletterUnsubscribePlaceholders.length; i++)
				tags[t++] = [parent.arrNewsletterUnsubscribePlaceholders[i][0], '<b>' + parent.arrNewsletterUnsubscribePlaceholders[i][1] + '</b>'];

 if ( (parent.arrResponderPlaceholders != undefined)  )
			for (var i = 0; i < parent.arrResponderPlaceholders.length; i++)
				tags[t++] = [parent.arrResponderPlaceholders[i][0], parent.arrResponderPlaceholders[i][1]];

 return tags;
}

function html_entity_decode( string ) {
    var ret, tarea = document.createElement('textarea');
    tarea.innerHTML = string;
    ret = tarea.value;
    try {tarea.removeNode(false);}catch(e) {}
    return ret;
}

function GetPlaceholdersForLinkDialog(editor){
 var tags = new Array();
 var t = 0;
 var arrPlaceholders = null;

 if ( (parent.arrSubscribePlaceholders != undefined) && (editor.name == 'OptInConfirmationMailHTMLText') )
			for (var i = 0; i < parent.arrSubscribePlaceholders.length; i++)
				tags[t++] = [html_entity_decode(parent.arrSubscribePlaceholders[i][1]), parent.arrSubscribePlaceholders[i][0]];

 if ( (parent.arrUnsubscribePlaceholders != undefined) && (editor.name == 'OptOutConfirmationMailHTMLText') )
			for (var i = 0; i < parent.arrUnsubscribePlaceholders.length; i++)
				tags[t++] = [html_entity_decode(parent.arrUnsubscribePlaceholders[i][1]), parent.arrUnsubscribePlaceholders[i][0]];

 if ((parent.arrPlaceholderItems ? arrPlaceholders = parent.arrPlaceholderItems : arrPlaceholders = arrPlaceholderItems) != undefined) {
			for (var i = 0; i < arrPlaceholders.length; i++)
			 tags[t++] = [html_entity_decode(arrPlaceholders[i][1]), arrPlaceholders[i][0]];
	}

 if ( (parent.arrNewsletterUnsubscribePlaceholders != undefined) && (editor.name == 'OptInConfirmedMailHTMLText' || editor.name == 'MailHTMLText' || editor.name == 'multiline') )
			for (var i = 0; i < parent.arrNewsletterUnsubscribePlaceholders.length; i++)
				tags[t++] = [html_entity_decode(parent.arrNewsletterUnsubscribePlaceholders[i][1]), parent.arrNewsletterUnsubscribePlaceholders[i][0]];

 if ( (parent.arrResponderPlaceholders != undefined)  )
			for (var i = 0; i < parent.arrResponderPlaceholders.length; i++)
				tags[t++] = [html_entity_decode(parent.arrResponderPlaceholders[i][1]), parent.arrResponderPlaceholders[i][0]];

 return tags;
}

function placeholderplugin_html_entity_decode( string ) {
    var ret, tarea = document.createElement('textarea');
    tarea.innerHTML = string;
    ret = tarea.value;
    try {tarea.removeNode(false);}catch(e) {}
    return ret;
}


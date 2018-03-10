/*
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
*/

CKEDITOR.editorConfig = function( config )
{
    config.filebrowserImageBrowseUrl = CKEDITOR.basePath + 'filemanager/index.php?type=Images';
    config.filebrowserImageUploadUrl = CKEDITOR.basePath + 'filemanager/connectors/php/upload.php?type=Images&command=QuickUpload';
    config.filebrowserDocPropsBrowseUrl = CKEDITOR.basePath + 'filemanager/index.php?type=Images';

    config.uiColor = '#E2EAF3';

    config.title = false;
    config.browserContextMenuOnCtrl = false;
    CKEDITOR.disableAutoInline = true;

    config.fullPage = true;
    config.allowedContent = true
    config.docType = '<!DOCTYPE HTML>';
    config.emailProtection = '';
    config.enterMode = CKEDITOR.ENTER_DIV; // p is recommended but users....
    config.fillEmptyBlocks = true;
    config.fontSize_sizes = '8pt/ 8pt;9pt/ 9pt;10pt/10pt;11pt/11pt;12pt/12pt;14pt/14pt;18pt/18pt;24pt/24pt;36pt/36pt/8px/ 8px;9px/ 9px;10px/10px;11px/11px;12px/12px;14px/14px;16px/16px;18px/18px;20px/20px;22px/22px;24px/24px;26px/26px;28px/28px;36px/36px;48px/48px;72px/72px';
    config.font_names = 'Arial;Calibri;Comic Sans MS;Courier New;Georgia;Tahoma;Times New Roman;Verdana';
    config.ignoreEmptyParagraph = true;

    config.removeDialogTabs = config.removeDialogTabs + ';image:advanced'; // ImageDlgHideLink, ImageDlgHideAdvanced
    config.disableNativeTableHandles = true; // false gives errors in IE
    config.disableObjectResizing = false;


    config.toolbar_MyDefaultToolbar =
    [
        { name: 'document',    items : [ 'Source','-','NewPage','DocProps','Preview','Print','-','Templates' ] },
        { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
        { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',      items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
        '/',
        { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors',      items : [ 'TextColor','BGColor' ] },
        { name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About' ] },
        '/',
        { name: 'newsletter',  items : [ 'placeholdercb', 'queryfunctionbtn', 'loadfilebtn', 'targetgroupsBtn' ] }

    ];

    config.toolbar_MyDefaultMailToolbar =
    [
        { name: 'document',    items : [ 'Source','-','NewPage','DocProps','Preview','Print','-','Templates' ] },
        { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
        { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',      items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
        '/',
        { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors',      items : [ 'TextColor','BGColor' ] },
        { name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About' ] },
        '/',
        { name: 'newsletter',  items : [ 'placeholdercb', 'queryfunctionbtn', 'textblocksbtn', 'loadfilebtn', 'smebarbtn', 'targetgroupsBtn' ] }

    ];

    config.toolbar_MyDefaultSimpleToolbar =
    [
        { name: 'document',    items : [ 'Source','-','NewPage','DocProps','Preview','Print' ] },
        { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
        { name: 'links',       items : [ 'Link','Unlink' ] },
        { name: 'insert',      items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
        '/',
        { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors',      items : [ 'TextColor','BGColor' ] },
        { name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About' ] },
        '/',
        { name: 'newsletter',  items : [ 'loadfilebtn' ] }

    ];


    config.toolbar_MyDefaultReadOnlyToolbar =
    [
        { name: 'default',    items : [ 'Preview', 'Print', 'Find', 'Maximize', '-', 'About' ] }

    ];

    config.toolbar_MyDefaultMultiLineToolbar =
    [
        { name: 'document',    items : [ 'Source','-' ] },
        { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
        { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',      items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
        '/',
        { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors',      items : [ 'TextColor','BGColor' ] },
        '/',
        { name: 'newsletter',  items : [ 'placeholdercb', 'queryfunctionbtn', 'textblocksbtn', 'targetgroupsBtn' ] }

    ];

   var basePath = CKEDITOR.basePath;
   basePath = basePath.substr(0, basePath.indexOf("ckeditor/")) + 'plugins/';

   //load external plugin
   CKEDITOR.plugins.addExternal('queryfunctionbtn', basePath + 'ckeditorplugins/queryfunctionbtn/', 'plugin.js');
   CKEDITOR.plugins.addExternal('textblocksbtn', basePath + 'ckeditorplugins/textblocksbtn/', 'plugin.js');
   CKEDITOR.plugins.addExternal('loadfilebtn', basePath + 'ckeditorplugins/loadfilebtn/', 'plugin.js');
   CKEDITOR.plugins.addExternal('placeholdercb', basePath + 'ckeditorplugins/placeholdercb/', 'plugin.js');
   CKEDITOR.plugins.addExternal('smebarbtn', basePath + 'ckeditorplugins/smebarbtn/', 'plugin.js');
   CKEDITOR.plugins.addExternal('targetgroups', basePath + 'ckeditorplugins/targetgroups/', 'plugin.js');

   CKEDITOR.plugins.addExternal('docprops', basePath + 'ckeditorplugins/docprops/', 'plugin.js');
   CKEDITOR.plugins.addExternal('tableresize', basePath + 'ckeditorplugins/tableresize/', 'plugin.js');
   CKEDITOR.plugins.addExternal('codemirror', basePath + 'ckeditorplugins/codemirror/', 'plugin.js');

   // devtools,
   var _plugins = 'docprops,placeholdercb,queryfunctionbtn,textblocksbtn,loadfilebtn,smebarbtn,tableresize,codemirror';
   if(document.getElementById("TargetGroupsSupport") && document.getElementById("TargetGroupsSupport").value == "1")
     _plugins += ',targetgroups';
   config.extraPlugins = _plugins;

   config.templates_files = [ CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf("ckeditor/")) + 'fcktemplates.php' ];

   // remove resizing
   config.resize_enabled = false;

   // toolbar can collapse or not, we allow this
   config.toolbarCanCollapse = true;

   // scayt autoStartup disabled
   config.scayt_autoStartup = false;

   // no elementspath in status line and no magicline
   config.removePlugins = 'elementspath,magicline';
   if(CKEDITOR.config.fullPage){ // bug table selection in fullPage mode
     config.removePlugins += ',tableselection';
   }

   // sample modify style in style box
   // http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Styles
   /*
   CKEDITOR.stylesSet.add( 'my_styles',
   [
   	// Block-level styles
   	{ name : 'Blue_Title', element : 'h2', styles : { 'color' : 'Blue' } },
   	{ name : 'Red_itle' , element : 'h3', styles : { 'color' : 'Red' } },

   	// Inline styles
   	{ name : 'CSS_Style', element : 'span', attributes : { 'class' : 'my_style' } },
   	{ name : 'Marker: Yellow', element : 'span', styles : { 'background-color' : 'Yellow' } }
   ]);
   config.stylesSet = 'my_styles';
   */


};


// HTML formattings
CKEDITOR.on( 'instanceReady', function( ev )
 {

    var element = ev.editor.element;
 			var form = element.$.form && new CKEDITOR.dom.element( element.$.form );
  			if ( form )
  			{

     	var editor = ev.editor,	dataProcessor = editor.dataProcessor,	htmlFilter = dataProcessor && dataProcessor.htmlFilter;

      // set HTML 4 style
      SetHTMLFilterRules(htmlFilter);

      // copied from ckeditor source to hook submit handler and remove spaces+crlfs
  				function onHTMLEditorSubmit()
  				{
        // remove spaces and line breaks
        ev.editor.dataProcessor.writer.indentationChars = '';
        ev.editor.dataProcessor.writer.lineBreakChars = '';

        ev.editor.updateElement();
  				}
  				form.on( 'submit',onHTMLEditorSubmit );

  				// Setup the submit function because it doesn't fire the
  				// "submit" event.
  				if ( !form.$.submit.nodeName && !form.$.submit.length )
  				{
  					form.$.submit = CKEDITOR.tools.override( form.$.submit, function( originalHTMLEditorSubmit )
  						{
  							return function()
  								{
  									ev.editor.updateElement();

  									// For IE, the DOM submit function is not a
  									// function, so we need thid check.
  									if ( originalHTMLEditorSubmit.apply )
  										originalHTMLEditorSubmit.apply( this, arguments );
  									else
  										originalHTMLEditorSubmit();
  								};
  						});
  				}

  				// Remove 'submit' events registered on form element before destroying.(#3988)
  				ev.editor.on( 'destroy', function()
  				{
  					form.removeListener( 'submit', onHTMLEditorSubmit );
  				} );
  			}
    // /

    /*
    ev.editor.on( 'getData', function( editor ) {
      //alert(editor.data.dataValue);
    });

    ev.editor.on( 'setData', function( editor ) {
      //alert(editor.data.dataValue);
    });
    */

    //ev.editor.dataProcessor.writer.lineBreakChars = '';
    ev.editor.dataProcessor.writer.indentationChars = '    ';
    ev.editor.dataProcessor.writer.selfClosingEnd = ' />';

    var tags = ['div', 'p', 'table', 'td', 'tr', 'ol', 'ul', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

    for (var key in tags)
       ev.editor.dataProcessor.writer.setRules(
              tags[key],
               {
                   indent : true,
                   breakBeforeOpen : true,
                   breakAfterOpen : false,
                   breakBeforeClose : false,
                   breakAfterClose : true
               }
           );

    ev.editor.dataProcessor.writer.setRules(
           'head',
            {
                indent : true,
                breakBeforeOpen : false,
                breakAfterOpen : true,
                breakBeforeClose : true,
                breakAfterClose : true
            }
        );

    ev.editor.dataProcessor.writer.setRules(
           'title',
            {
                indent : true,
                breakBeforeOpen : true,
                breakAfterOpen : false,
                breakBeforeClose : false,
                breakAfterClose : true
            }
        );

    ev.editor.dataProcessor.writer.setRules(
           'body',
            {
                indent : true,
                breakBeforeOpen : true,
                breakAfterOpen : true,
                breakBeforeClose : true,
                breakAfterClose : false
            }
        );

    ev.editor.dataProcessor.writer.setRules(
           'meta',
            {
                indent : true,
                breakBeforeOpen : true,
                breakAfterOpen : true,
                breakBeforeClose : true,
                breakAfterClose : true
            }
        );

    ev.editor.dataProcessor.writer.setRules(
           'style',
            {
                indent : true,
                breakBeforeOpen : true,
                breakAfterOpen : true,
                breakBeforeClose : true,
                breakAfterClose : true
            }
        );

    ev.editor.dataProcessor.writer.setRules(
           'br',
            {
                indent : false,
                breakBeforeOpen : false,
                breakAfterOpen : false,
                breakBeforeClose : false,
                breakAfterClose : false
            }
        );

 }
);

// HTML 4 style
function SetHTMLFilterRules(htmlFilter){

 	// Output properties as attributes, not styles.
 	htmlFilter.addRules(
 		{
 			elements :
 			{
 				$ : function( element )
 				{
 					// Output dimensions of images as width and height
 					if ( element.name == 'img'  )
 					{
 						var style = element.attributes.style;

 						if ( style )
 						{
 							// Get the width from the style.
 							var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec( style );
 							var width = match && match[1];

 							// Get the height from the style.
 							var match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec( style );
 							var height = match && match[1];

 							// Get the float from the style.
        var match = /(?:^|\s)float\s*:\s*(\w*)(;|$)/i.exec( style );
 							var afloat = match && match[1];
 							if(afloat)
 							  afloat = afloat.replace(/;/, "");
 							if(afloat != "right" && afloat != "left"){
 							  afload = false;
 							}

 							if ( width )
 							{
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
 								element.attributes.width = width;
 							}

 							if ( height )
 							{
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
 								element.attributes.height = height;
 							}

 							if ( afloat )
 							{
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)float\s*:\s*(\w*);?/i , '' );
 								element.attributes.align = afloat;
 							}

 						}
 					}

 					// Output alignment of paragraphs, div and td using align / valign
 					if ( element.name == 'p' || element.name == 'div' || element.name == 'td' )
 					{
       // delete blank background=
       if(element.attributes.background == "") {
         delete element.attributes.background;
       }

 						var style = element.attributes.style;

 						if ( style )
 						{
 							// Get the align from the style.
 							var match = /(?:^|\s)text-align\s*:\s*(\w*)(;|$)/i.exec( style );
 							var align = match && match[1];
 							if(align)
 							  align = align.replace(/;/, "");

 							// Get the valign from the style for td only.
 							var match = /(?:^|\s)vertical-align\s*:\s*(\w*)(;|$)/i.exec( style );
 							var valign = match && match[1];
 							if(valign)
 							  valign = valign.replace(/;/, "");

 							if ( align )
 							{
         //element.attributes.style = element.attributes.style.replace( /(?:^|\s)text-align\s*:\s*(\w*)(;|$)?/i , '' );
 								element.attributes.align = align;
 							}

 							if ( valign ) // for td only
 							{
         //element.attributes.style = element.attributes.style.replace( /(?:^|\s)vertical-align\s*:\s*(\w*)(;|$)?/i , '' );
 								element.attributes.valign = valign;
 							}

 						}
 					}

 					// Output image borders as HTML
 					if ( element.name == 'img' )
 					{
 						var style = element.attributes.style;

 						if ( style )
 						{
        //margin *******************
        var match = /(?:^|\s)margin\s*:\s*(.*)(;|$)/i.exec( style );
 							var margin = match && match[1];
 							if(margin)
   							margin = margin.replace(/;/, "");
        if(margin) {
     					element.attributes.style = element.attributes.style.replace( /(?:^|\s)margin\s*:\s*(.*)(;|$)/i , '' );
     					margin = margin.trim();
     					if(margin.indexOf(" ") != -1){ // e.g. margin 10px 20px;
     					  var vspace = parseInt(margin);
     					  var hspace = parseInt(margin.substr(margin.indexOf(" ")+1));
     					  element.attributes.hspace = hspace;
     					  element.attributes.vspace = vspace;
     					} else if(parseInt(margin) > 0) { // e.g. margin 10px;
     					  element.attributes.hspace = parseInt(margin);
     					  element.attributes.vspace = parseInt(margin);
     					}
   					}

        // chrome long style
   					var match = /(?:^|\s)margin-left\s*:\s*(\d+)px/i.exec( style );
								var margin_left = match && match[1] && parseInt(match[1]) >= 0;
								if(margin_left)
								  margin_left = parseInt(match[1]);
   					var match = /(?:^|\s)margin-right\s*:\s*(\d+)px/i.exec( style );
								var margin_right = match && match[1] && parseInt(match[1]) >= 0;
								if(margin_right)
								  margin_right = parseInt(match[1]);
   					var match = /(?:^|\s)margin-top\s*:\s*(\d+)px/i.exec( style );
								var margin_top = match && match[1] && parseInt(match[1]) >= 0;
								if(margin_top)
								  margin_top = parseInt(match[1]);
   					var match = /(?:^|\s)margin-bottom\s*:\s*(\d+)px/i.exec( style );
								var margin_bottom = match && match[1] && parseInt(match[1]) >= 0;
								if(margin_bottom)
								  margin_bottom = parseInt(match[1]);

        if(margin_left && margin_right && (margin_left == margin_right)){
          element.attributes.hspace = margin_left;
          element.attributes.style = element.attributes.style.replace( /(?:^|\s)margin-left\s*:\s*(\d+)px(;|$)/i , '' );
          element.attributes.style = element.attributes.style.replace( /(?:^|\s)margin-right\s*:\s*(\d+)px(;|$)/i , '' );
        }

        if(margin_top && margin_bottom && (margin_top == margin_bottom)){
          element.attributes.vspace = margin_top;
          element.attributes.style = element.attributes.style.replace( /(?:^|\s)margin-top\s*:\s*(\d+)px(;|$)/i , '' );
          element.attributes.style = element.attributes.style.replace( /(?:^|\s)margin-bottom\s*:\s*(\d+)px(;|$)/i , '' );
        }

        // border *************************

 						 // FF short style
 							// border-width.
 							var match = /(?:^|\s)border-width\s*:\s*(\d+)px/i.exec( style );
								var border_width = match && match[1] && parseInt(match[1]) >= 0;
								if(border_width)
								  border_width = parseInt(match[1]);
								// border-style
 							var match = /(?:^|\s)border-style\s*:\s*(\w*)(;|$)/i.exec( style );
 							var border_style = match && match[1];
 							if(border_style)
   							border_style = border_style.replace(/;/, "");

 							// IE long style
 							var match = /(?:^|\s)border-bottom\s*:\s*(\S*)\s*solid/i.exec( style );
								var border_bottom = match && match[1] && match[0].indexOf("solid") != -1 && parseInt(match[1]) >= 0;
								if(border_bottom)
								  border_bottom = parseInt(match[1]);

 							var match = /(?:^|\s)border-left\s*:\s*(\S*)\s*solid/i.exec( style );
								var border_left = match && match[1] && match[0].indexOf("solid") != -1 && parseInt(match[1]) >= 0;
								if(border_left)
								  border_left = parseInt(match[1]);

 							var match = /(?:^|\s)border-right\s*:\s*(\S*)\s*solid/i.exec( style );
								var border_right = match && match[1] && match[0].indexOf("solid") != -1 && parseInt(match[1]) >= 0;
								if(border_right)
								  border_right = parseInt(match[1]);

 							var match = /(?:^|\s)border-top\s*:\s*(\S*)\s*solid/i.exec( style );
								var border_top = match && match[1] && match[0].indexOf("solid") != -1 && parseInt(match[1]) >= 0;
								if(border_top)
								  border_top = parseInt(match[1]);

        if(border_bottom && border_left && border_right && border_top){

          var border = new Array(border_bottom, border_left, border_right, border_top);
          var b = border[0];
          for(var i=1; i<border.length; i++)
            if(b != border[i]){
              b = -1;
              break;
            }

          if(b != -1) {
            border_width = String(parseInt(border_bottom));
            border_style = "solid";
          }
        }
        // IE long style /

        // Chrome long style
 							var match = /(?:^|\s)border-top-width\s*:\s*(\d+)px/i.exec( style );
								var border_top = match && match[1] && parseInt(match[1]) >= 0;
								if(border_top)
								  border_top = parseInt(match[1]);

 							var match = /(?:^|\s)border-right-width\s*:\s*(\d+)px/i.exec( style );
								var border_right = match && match[1] && parseInt(match[1]) >= 0;
								if(border_right)
								  border_right = parseInt(match[1]);

 							var match = /(?:^|\s)border-left-width\s*:\s*(\d+)px/i.exec( style );
								var border_left = match && match[1] && parseInt(match[1]) >= 0;
								if(border_left)
								  border_left = parseInt(match[1]);

 							var match = /(?:^|\s)border-bottom-width\s*:\s*(\d+)px/i.exec( style );
								var border_bottom = match && match[1] && parseInt(match[1]) >= 0;
								if(border_bottom)
								  border_bottom = parseInt(match[1]);

 							var match = /(?:^|\s)border-top-style\s*:\s*(\w*)(;|$)/i.exec( style );
 							var border_style_top = match && match[1] && match[1].lastIndexOf('solid') != -1;
 							var match = /(?:^|\s)border-right-style\s*:\s*(\w*)(;|$)/i.exec( style );
 							var border_style_right = match && match[1] && match[1].lastIndexOf('solid') != -1;
 							var match = /(?:^|\s)border-left-style\s*:\s*(\w*)(;|$)/i.exec( style );
 							var border_style_left = match && match[1] && match[1].lastIndexOf('solid') != -1;
 							var match = /(?:^|\s)border-bottom-style\s*:\s*(\w*)(;|$)/i.exec( style );
 							var border_style_bottom = match && match[1] && match[1].lastIndexOf('solid') != -1;

 							if(border_style_top && border_style_right && border_style_left && border_style_bottom)
   							border_style = "solid";


        if(border_bottom && border_left && border_right && border_top){

          var border = new Array(border_bottom, border_left, border_right, border_top);
          var b = border[0];
          for(var i=1; i<border.length; i++)
            if(b != border[i]){
              b = -1;
              break;
            }

          if(b != -1) {
            border_width = String(parseInt(border_bottom));
          }
        }
        // Chrome long style /


 							if ( border_width >= 0 && border_style == "solid" )
 							{

         // FF
         element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-width\s*:\s*(\d+)px?/i , '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-style\s*:\s*(\w*)(;|$)?/i , '' );

 								// IE long style with/without ;
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-bottom\s*:\s*(\S*)\s*solid(;|$)/i, '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-left\s*:\s*(\S*)\s*solid(;|$)/i, '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-right\s*:\s*(\S*)\s*solid(;|$)/i, '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-top\s*:\s*(\S*)\s*solid(;|$)/i, '' );
         // // IE long style with/without ; /

         // Chrome long style
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-top-width\s*:\s*(\d+)px(;|$)/i , '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-right-width\s*:\s*(\d+)px(;|$)/i , '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-left-width\s*:\s*(\d+)px(;|$)/i , '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-bottom-width\s*:\s*(\d+)px(;|$)/i , '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-top-style\s*:\s*(\w*)(;|$)/i , '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-right-style\s*:\s*(\w*)(;|$)/i , '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-left-style\s*:\s*(\w*)(;|$)/i , '' );
 								element.attributes.style = element.attributes.style.replace( /(?:^|\s)border-bottom-style\s*:\s*(\w*)(;|$)/i , '' );
         // Chrome long style//

 								element.attributes.border = parseInt(border_width);
 							}
 						}
 					}

 					// output td background-color as bgcolor and white-spacing als nowrap
 					if ( element.name == 'table' || element.name == 'td' )
 					{
       // IE bug <td align="middle"> is invalid must be <td align="center">
 					 if(element.attributes.align && element.attributes.align == "middle")
 					   element.attributes.align = "center";

       // delete blank background=
       if(element.attributes.background == "") {
         delete element.attributes.background;
       }

 						var style = element.attributes.style;
 						if ( style )
 						{

         style = convertRGBToHex( style ); // FF rgb() problem
         element.attributes.style = style;

  							// Get the width from the style.
  							var match = /(?:^|\s)width\s*:\s*(\S*)?/i.exec( style );
  							var width = match && match[1];
  							if(width && width.indexOf("%") == -1)
  							  width = parseInt(width);

  							// Get the height from the style.
  							var match = /(?:^|\s)height\s*:\s*(\S*)?/i.exec( style );
  							var height = match && match[1];
  							if(height && height.indexOf("%") == -1)
  							  height = parseInt(height);

  							if ( width )
  							{
  							 width = String(width).replace(/;/, "");
          element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\S*)?/i , '' );
  								element.attributes.width = width;
  							}

  							if ( height )
  							{
  							 height = String(height).replace(/;/, "");
          element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\S*)?/i , '' );
  								element.attributes.height = height;
  							}

 								// background-color
  							var match = /(?:^|\s)background-color\s*:\s*(\S*)(;|$)/i.exec( style );
  							var background_color = match && match[1];

  							if(background_color){
   								// we let it in td tag, so webbased emailers can't override it --> element.attributes.style = element.attributes.style.replace( /(?:^|\s)background-color\s*:\s*(\S*)(;|$)?/i , '' );
           background_color = background_color.replace(/;/, "");
           element.attributes.bgcolor = background_color;
  							}

  							// white-space: nowrap
  							var match = /(?:^|\s)white-space\s*:\s*(\S*)(;|$)/i.exec( style );
  							var white_space = match && match[1];
  							if(white_space)
  							  white_space = white_space.replace(/;/, "");
  							if(white_space != "nowrap")
  							   white_space = false;
  							if(white_space){
   								element.attributes.style = element.attributes.style.replace( /(?:^|\s)white-space\s*:\s*(\S*)(;|$)?/i , '' );
           element.attributes.nowrap = "nowrap";
  							}

 						}
 					}

 					// output body background-color as bgcolor
 					if ( element.name == 'body' )
 					{
 						var style = element.attributes.style;
 						if ( style )
 						{
         style = convertRGBToHex( style ); // FF rgb() problem
         element.attributes.style = style;
 								// background-color
  							var match = /(?:^|\s)background-color\s*:\s*(\S*)(;|$)/i.exec( style );
  							var background_color = match && match[1];

  							if(background_color){
   								// we let it in body tag, so webbased emailers can't override it --> element.attributes.style = element.attributes.style.replace( /(?:^|\s)background-color\s*:\s*(\S*)(;|$)?/i , '' );
           background_color = background_color.replace(/;/, "");
           element.attributes.bgcolor = background_color;
  							}

 						}
 					}

 					if ( !element.attributes.style || element.attributes.style.replace(/ /, "") == "" || element.attributes.style.replace(/ /, "") == ";" )
 						delete element.attributes.style;
 						else {
         // FF problems with &quot; in url()
         var style = element.attributes.style;
   						if(style.indexOf("url(&quot;") != -1) {
   						   style = style.replace("url(&quot;", "url('");
   						   style = style.replace("&quot;)", "')");
      						element.attributes.style = style;
   						}
 						}

 					return element;
 				}
 			},

 			attributes :
 				{
 					style : function( value, element )
 					{
 						// Return #RGB for background and border colors
 						return convertRGBToHex( value );
 					}
 				}
 		} );

}
// HTML 4 style /

// change link, image, docProps dialog definition
CKEDITOR.on('dialogDefinition', function( ev )
	{
		// Take the dialog name and its definition from the event
		// data.
		var dialogName = ev.data.name;
		var dialogDefinition = ev.data.definition;
  var dialog = ev.data.definition.dialog;

		if ( dialogName == 'link' )
		{
			// Get a reference to the "Link Info" tab.
			var infoTab = dialogDefinition.getContents( 'info' );

   var fields = GetPlaceholdersForLinkDialog(ev.editor);

   if(fields.length > 0) {
  			// Add a combo box field to the "info" tab.
  			infoTab.add( {
  					type : 'select',
  					label : ev.editor.lang.placeholdercb.cbSerialEMailFieldAsURLLabel,//'Serial email field in URL',
  					id : 'cbSerialEMailFieldAsURL',
  					items : [[ev.editor.lang.placeholdercb.cbSerialEMailFieldAsURLSelect, 0]].concat(fields),
  					css : [ CKEDITOR.config.contentsCss, CKEDITOR.getUrl( CKEDITOR.skinPath + 'editor.css' ) ],
  					'default' : '0',
  					onChange : function()
  					{
  					  InsertSerialFieldValueInLinkField('cbSerialEMailFieldAsURL', 'url');
  					}
  				});
		 }

   // hook onchange handler of linkType
   var vlinkType = infoTab.get( 'linkType' );
   var onlinkTypeChangeFunction = vlinkType['onChange'];
   vlinkType['onChange'] = function() {
       if( CKEDITOR.dialog.getCurrent().getValueOf("info", "linkType") != "url" ) {
          var vcb = CKEDITOR.dialog.getCurrent().getContentElement("info", "cbSerialEMailFieldAsURL");
          if(vcb)
            vcb.disable();
       } else {
          var vcb = CKEDITOR.dialog.getCurrent().getContentElement("info", "cbSerialEMailFieldAsURL");
          if(vcb)
            vcb.enable();
       }

       if(onlinkTypeChangeFunction.apply)
         onlinkTypeChangeFunction.apply(this);
         else
         onlinkTypeChangeFunction();
   }

		}

		if ( dialogName == 'image' )
		{

			var infoTab = dialogDefinition.getContents( 'info' );

			// Add a checkbox to the "info" tab.
			infoTab.add( {
					type : 'checkbox',
					label : ev.editor.lang.placeholdercb.chkboxInsertImageAsURL,
					id : 'chkInsertImageAsCompleteURL',
					css : [ CKEDITOR.config.contentsCss, CKEDITOR.getUrl( CKEDITOR.skinPath + 'editor.css' ) ],
					onClick : function()
					{
       SetImageAsCompleteURL('chkInsertImageAsCompleteURL');
					}
				},

    "txtAlt" // before Alt-Tag
     );


   // images border=0
   var txtBorderField = infoTab.get( 'txtBorder' );
   txtBorderField['default'] = '0';

   // hook onchange handler of txtUrl
   var vtxtUrl = infoTab.get('txtUrl');
   var ontxtURLChangeFunction = vtxtUrl['onChange'];
   vtxtUrl['onChange'] = function() {
       RefreshImageAsCompleteURL();
       if(ontxtURLChangeFunction.apply)
         ontxtURLChangeFunction.apply(this);
         else
         ontxtURLChangeFunction();
   }

			var linkTab = dialogDefinition.getContents( 'Link' );
   linkTab.remove('browse');
  }

		if ( dialogName == 'docProps' )
		{

   dialogDefinition.removeContents('general');
   dialogDefinition.removeContents('preview');

			var designTab = dialogDefinition.getContents( 'design' );

   designTab.remove( 'bgFixed' );

   // fix 3.6
   var bgImage = designTab.get('bgImage');
   bgImage.style = 'width: 210px';

   var bgImageChoose = designTab.get('bgImageChoose');
   bgImageChoose.style = 'display:inline-block;margin-top:10px;margin-left: 10px';
   //

  }

		if ( dialogName == 'tableProperties' )
		{
    dialog.on('show', function () {
       // change width in style width for properties dialog
       var sel = CKEDITOR.currentInstance.getSelection();
       var element;
     		if (sel && (element = sel.getStartElement() && sel.getStartElement().getAscendant('table', true)) ) {
           var cwidth = CKEDITOR.dialog.getCurrent().getContentElement("info", "txtWidth");
           var cheight = CKEDITOR.dialog.getCurrent().getContentElement("info", "txtHeight");

           var swidth = element.getStyle("width", "");
           var sheight = element.getStyle("height", "");

           if(!swidth && cwidth){
             element.setStyle("width", element.getAttribute("width")+'px');
             cwidth.setValue(element.getAttribute("width"), true);
             cwidth.onChange();
           }
           if(!sheight && cheight){
             element.setStyle("height", element.getAttribute("height")+'px');
             cheight.setValue(element.getAttribute("height"), true);
             cheight.onChange();
           }
        }
       });
  }

	});


 function RefreshImageAsCompleteURL() {
    CKEDITOR.dialog.getCurrent().setValueOf("info", "chkInsertImageAsCompleteURL", CKEDITOR.dialog.getCurrent().getValueOf("info", "txtUrl").indexOf("http://") > -1 || CKEDITOR.dialog.getCurrent().getValueOf("info", "txtUrl").indexOf("https://") > -1);
 }

 function SetImageAsCompleteURL(checkBoxId) {
   var checkBoxValue = CKEDITOR.dialog.getCurrent().getValueOf("info", checkBoxId);
   var txtURL = CKEDITOR.dialog.getCurrent().getValueOf("info", "txtUrl");
   var URL = CKEDITOR.basePath;
   URL = URL.substr(0, URL.indexOf("/ckeditor"));

   // subdomains
   var count = 0;
   var s = URL;
   while(s.lastIndexOf("/") != -1){
     s = s.substr(0, s.lastIndexOf("/"));
     count++;
   }
   if(count > 2) {
     for(var i=0;i<count-2;i++)
       URL = URL.substr(0, URL.lastIndexOf("/"));
   }

   if(checkBoxValue) {
     if(txtURL.indexOf("http:") == -1 && txtURL.indexOf("https:") == -1){
       txtURL = URL + txtURL;
       CKEDITOR.dialog.getCurrent().setValueOf("info", "txtUrl", txtURL);
     }
   } else{
     if(txtURL.indexOf("http:") > -1 || txtURL.indexOf("https:") > -1){
       txtURL = txtURL.substr(URL.length);
       CKEDITOR.dialog.getCurrent().setValueOf("info", "txtUrl", txtURL);
     }
   }
 }

 function InsertSerialFieldValueInLinkField(CBId, TargetElementId) {
   if(CKEDITOR.dialog.getCurrent().getValueOf("info", "linkType") == "anchor") return;
   var value = CKEDITOR.dialog.getCurrent().getValueOf("info", CBId);
   if( value == 0) return; // first element
   var value1 = CKEDITOR.dialog.getCurrent().getValueOf("info", TargetElementId);
   if(value.indexOf("Link") < 0) // e.g.AltBrowserLink
      value1 = value1 + value;
      else
      value1 = value;

   CKEDITOR.dialog.getCurrent().setValueOf("info", TargetElementId, value1);
   CKEDITOR.dialog.getCurrent().setValueOf("info", CBId, 0);
   CKEDITOR.dialog.getCurrent().getContentElement("info", TargetElementId).focus();
   if(value.indexOf("Link") > 0)
      CKEDITOR.dialog.getCurrent().setValueOf("info", "protocol", ""); // set to other on e.g. AltBrowserLink
 }

 /**
 * Convert a CSS rgb(R, G, B) color back to #RRGGBB format.
 * @param Css style string (can include more than one color
 * @return Converted css style.
 */
function convertRGBToHex( cssStyle )
{
 if(cssStyle.indexOf("rgba") < 0) // chrome rgba
   return cssStyle.replace( /(?:rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\))/gi, function( match, red, green, blue )
  		{
  			red = parseInt( red, 10 ).toString( 16 );
  			green = parseInt( green, 10 ).toString( 16 );
  			blue = parseInt( blue, 10 ).toString( 16 );
  			var color = [red, green, blue] ;

  			// Add padding zeros if the hex value is less than 0x10.
  			for ( var i = 0 ; i < color.length ; i++ )
  				color[i] = String( '0' + color[i] ).slice( -2 ) ;

  			return '#' + color.join( '' ) ;
  		 });


   return cssStyle.replace( /(?:rgba\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\))/gi, function( match, red, green, blue, alpha )
  		{
  			red = parseInt( red, 10 ).toString( 16 );
  			green = parseInt( green, 10 ).toString( 16 );
  			blue = parseInt( blue, 10 ).toString( 16 );
  			var color = [red, green, blue] ;

  			// Add padding zeros if the hex value is less than 0x10.
  			for ( var i = 0 ; i < color.length ; i++ )
  				color[i] = String( '0' + color[i] ).slice( -2 ) ;

     if(alpha == 0 && red == 0 && green == 0 && blue == 0)
    			return 'transparent';
     else
    			return '#' + color.join( '' ) ;
  		 });

}

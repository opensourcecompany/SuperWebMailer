	/************************************************************************************************************
  #############################################################################
  #                SuperMailingList / SuperWebMailer                          #
  #               Copyright © 2007 - 2015 Mirko Boeer                         #
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

// changed for jquery 1.7 .unbind -> .off, .bind -> .on

var createtableofcontents = false;
var item_id = 1;
var repeater_item_id = 1;
var Initialized = false;

function MakeRepeatersSortable(){
		$('.ipe_repeater').sortable('destroy');

		$('.ipe_repeater').sortable( {
         opacity: 0.9,
		       scroll: true,
		       handle: $('.ipe_move'),
		       items: '.ipe_repeater_item',
		       connectWith: $(".ipe_repeater"),
		       placeholder: "ipe_hover",
		       forceHelperSize: true,
		       forcePlaceholderSize: true,
		       cursor: "crosschair",
		       tolerance: "pointer",
		       //cursorAt: 'top',

		       activate: function(event, ui) {  },

		       over: function(event, ui){  },

		       start: function(event, ui){
                            $('.ipe_repeater').addClass("show_repeaterborder");
                          },
		       stop: function(event, ui){

          // simulate "mouseout"
		    	   $('.ipe_repeater').removeClass("show_repeaterborder");
          $('.ipe_repeater_item').removeClass("ipe_repeater_item_hover");
		    	   $('.ipe_move').removeClass('ui-state-hover');

          $('.ipe_repeater_item').css("left", null);
          $('.ipe_repeater_item').css("top", null);
          $('.ipe_repeater_item').css("right", null);
          $('.ipe_repeater_item').css("height", null);
          $('.ipe_repeater_item').css("position", null);
          $('.ipe_repeater_item').css("opacity", null);

          SortTableOfContents();
		    	   parent.Modified = true;
		    	   $('.ipe_repeater').each(function(){

		    		   $(this).append($(this).find('.ipe_repeater_toolbar:first'));

		    	   });

		    	}
		    });
       $( ".ipe_repeater_toolbar" ).disableSelection();
}
//--------------- item --------------
function SetupIPEItem(that){

	if(!$(that).find(".ipe_toolbar").html() ){
		$(that).prepend("<div class=\"ipe_toolbar\"></div>");
		$(that).find(".ipe_toolbar:first").append("<div class=\"ipe_edit ui-button ui-icon ui-icon-pencil ui-state-default ui-corner-all\" title=\"" + rsEditItem + "\"></div>");
  if(parent.document.getElementById("TargetGroupsSupport") && parent.document.getElementById("TargetGroupsSupport").value == 1 &&
     parent.document.getElementById("TargetGroupsDefined") && parent.document.getElementById("TargetGroupsDefined").value == 1) {
       $(that).find(".ipe_toolbar:first").append("<div class=\"ipe_targetgroups ui-button ui-icon ui-icon-person ui-state-default ui-corner-all\" title=\"" +  rsTargetGroups + "\"></div>");
     }
	}
 $(that).attr("id", item_id++);
 $(that).attr("title", rsDoubleClickToEdit);

 $(that).off('click');
	$(that).click(function(e){
  e.stopPropagation();
		EditItem($(this));
		return false; // for hyperlinks
	});


 $(that).find('.ipe_toolbar').find(".ipe_edit").off('click');
	$(that).find('.ipe_toolbar').find(".ipe_edit").click(function(e){
  e.stopPropagation();
		EditItem($(this).closest(".ipe_item"));
	});

 $(that).find('.ipe_toolbar').find(".ipe_targetgroups").off('click');
	$(that).find('.ipe_toolbar').find(".ipe_targetgroups").click(function(e){
  e.stopPropagation();
		ShowTargetGroupsDialog($(this).closest(".ipe_item"));
	});

 $(that).find('.ipe_toolbar').find(".ipe_edit,.ipe_targetgroups").off('hover');
	$(that).find('.ipe_toolbar').find(".ipe_edit,.ipe_targetgroups").hover(function(e){
   $(this).addClass('ui-state-hover');
 		$(this).closest('.ipe_item').addClass("ipe_hover");
	}, function(){
    $(this).removeClass('ui-state-hover');

    // IE bug it shows hover when there a a-Tag and doesn't remove it
    if(createtableofcontents) {
      $(this).closest('.ipe_item').parent().addClass("ipe_hover");
    		$(this).closest('.ipe_item').parent().removeClass("ipe_hover");
    }

  		$(this).closest('.ipe_item').removeClass("ipe_hover");
	});

	$(that).find('.ipe_toolbar').off('click');
	$(that).find('.ipe_toolbar').click(function(e){
   e.stopPropagation();
	});


}

//--------------- repeater items --------------

function SetupRepeaterItem(that){
	if(!$(that).find('.ipe_toolbar').html()){
		$(that).prepend("<div class='ipe_toolbar'></div>");
	}

	if($(that).attr("editable") && !$(that).find('.ipe_toolbar').find(".ipe_cssedit").attr("class") ){
		$(that).find(".ipe_toolbar:first").append("<div class='ipe_cssedit ui-button ui-icon ui-icon-wrench ui-state-default ui-corner-all' title='" + rsCSSEditItem + "'></div>");
	}

	if(!$(that).find('.ipe_toolbar').find(".ipe_move").attr("class")){
		$(that).find(".ipe_toolbar:first").append("<div class='ipe_move ui-button ui-icon ui-icon-arrow-4-diag ui-state-default ui-corner-all' title='" + rsMoveItem + "'></div>");
		$(that).find(".ipe_toolbar:first").append("<div class='ipe_delete ui-button ui-icon ui-icon-trash ui-state-default ui-corner-all' title='" +  rsDeleteItem + "'></div>");
 }

	$(that).find('.ipe_toolbar').off('click');
	$(that).find('.ipe_toolbar').click(function(e){
   e.stopPropagation();
	});


 $(that).find('.ipe_toolbar').find(".ipe_move").off('hover');
	$(that).find('.ipe_toolbar').find(".ipe_move").hover(
			function(e){
    $(this).addClass('ui-state-hover');
    $(this).css('cursor','move');
				$(this).closest(".ipe_repeater_item").addClass("ipe_repeater_item_hover");
			}
			,
			function(e){
				$(this).closest(".ipe_repeater_item").removeClass("ipe_repeater_item_hover");
    $(this).css('cursor','auto');
    $(this).removeClass('ui-state-hover');
			}
	);

 $(that).find('.ipe_toolbar').find(".ipe_delete").off('hover');
	$(that).find('.ipe_toolbar').find(".ipe_delete").hover(
			function(e){
    $(this).addClass('ui-state-hover');
				$(this).closest(".ipe_repeater_item").addClass("ipe_repeater_item_hover");
			}
			,
			function(e){
				$(this).closest(".ipe_repeater_item").removeClass("ipe_repeater_item_hover");
				$(this).removeClass('ui-state-hover');
			}
	);

 $(that).find('.ipe_toolbar').find(".ipe_cssedit").off('hover');
	$(that).find('.ipe_toolbar').find(".ipe_cssedit").hover(
			function(e){
    $(this).addClass('ui-state-hover');
				$(this).closest(".ipe_repeater_item").addClass("ipe_repeater_item_hover");
			}
			,
			function(e){
				$(this).closest(".ipe_repeater_item").removeClass("ipe_repeater_item_hover");
				$(this).removeClass('ui-state-hover');
			}
	);

	$(that).find(".ipe_cssedit").off('click');
	$(that).find(".ipe_cssedit").click(function(e){
  e.stopPropagation();
		var elem = $(this).closest(".editable");
  CSSEditor(elem);
	});

	$(that).find(".ipe_delete").off('click');
	$(that).find(".ipe_delete").click(function(e){
  e.stopPropagation();
  var id = $(this).closest(".ipe_item").attr('id');
  var remove_element = $(this);

  parent.$( "#dialog-removeitem" ).dialog("destroy");
		parent.$( "#dialog-removeitem" ).dialog({
			resizable: false,
			draggable: false,
			height:150,
			modal: true
		});

  parent.$( "#dialog-removeitem" ).dialog( "option", "buttons", [
    {
        text: rsYes,
        click: function() {
                             parent.$(this).dialog("close");
                          			RemoveTableOfContentsEntry(id);
                          			tableofcontentsincludedoncemore = false;
                          			if(remove_element.closest(".ipe_repeater_item").attr("tableofcontents_mainitem") == "true")
                             			tableofcontentsincludedoncemore = RemoveTableOfContents( remove_element.closest(".ipe_repeater_item") );
                             remove_element.closest(".ipe_repeater_item").removeClass("ipe_repeater_item_hover");
                          			remove_element.closest(".ipe_repeater_item").remove();
                          			parent.Modified = true;
                          			if(tableofcontentsincludedoncemore)
                          			  CreateTableOfContents();
                          }
    },

    {
        text: rsNo,
        click: function() {
                             parent.$(this).dialog("close");
                             remove_element.closest(".ipe_repeater_item").removeClass("ipe_repeater_item_hover");
                          }
    }

  ] );

	});
	MakeRepeatersSortable();
}

function Init_IPE(){
 var anythingDone = false;

 InitializeTemplate();

 item_id = 1;

	$(".ipe_item").each(function(){
		SetupIPEItem(this);
  anythingDone = true;
	});

	$('.ipe_repeater').each(function(){
		if(!$(this).find(".ipe_repeater_toolbar").html()){
			$(this).append("<div class='ipe_repeater_toolbar'></div>");

   var addbtntitle = $(this).attr("label") ? $(this).attr("label") : rsAdd;
   $(this).find('.ipe_repeater_toolbar').append("<div class='ipe_addrepeater_item ui-button ui-state-default ui-corner-all' style='font-size:0.8em;font-weight:normal;'>"+addbtntitle+"</div>");
			$(this).find('.ipe_repeater_toolbar').find(".ipe_addrepeater_item").button({ text: true, icons: {primary:'ui-icon-plus',secondary:''} });

 		$(this).find('.ipe_repeater_toolbar').append("<div class='ipe_newrepeater_item ui-button ui-state-default ui-corner-all' style='font-size:0.8em;font-weight:normal;'>"+rsNew+"</div>");
	 	$(this).find('.ipe_repeater_toolbar').find(".ipe_newrepeater_item").button({ text: true, icons: {primary:'ui-icon-arrowreturnthick-1-e',secondary:''} });

   anythingDone = true;

		}
	});

	$('.ipe_repeater_toolbar').find(".ipe_addrepeater_item").off("click");
	$('.ipe_repeater_toolbar').find(".ipe_addrepeater_item").click(function(){

	  $(this).removeClass('ui-state-hover');
   var ItemData = $(this).closest(".ipe_repeater").attr("repeateritemdata") ?  utf8_decode(decodeURIComponent($(this).closest(".ipe_repeater").attr("repeateritemdata").replace(/\+/g, " "))) : "<div>No template found.</div>";

   var label = $(this).closest(".ipe_repeater").attr("label") ? $(this).closest(".ipe_repeater").attr("label") : "";
   AddRepeaterItem($(this).closest(".ipe_repeater"), ItemData, label);


	});

	$('.ipe_repeater_toolbar').find(".ipe_newrepeater_item").off("click");
	$('.ipe_repeater_toolbar').find(".ipe_newrepeater_item").click(function(){

	  $(this).removeClass('ui-state-hover');

   NewRepeaterItem($(this));

	});

	$(".ipe_repeater_item").each(function(){
		SetupRepeaterItem(this);
  anythingDone = true;
	});

 if(!anythingDone){
   parent.MessageBox(rsWarning, rsTemplateUnsuitable, parent.messageTypeWarning, 380, 130, "", false);
 }

}

parent.inherit(window)(function(){
	$(document).ready(function(){

    rsAdd = parent.rsAdd;
    rsNew = parent.rsNew;
    rsMoveItem = parent.rsMoveItem;
    rsEditItem = parent.rsEditItem;
    //rsDup = parent.;
    rsCSSEditItem = parent.rsCSSEditItem;
    rsDeleteItem = parent.rsDeleteItem;
    rsDoubleClickToEdit = parent.rsDoubleClickToEdit;

    rsApply = parent.rsApply;
    rsCancel = parent.rsCancel;
    rsYes = parent.rsYes;
    rsNo = parent.rsNo;
    rsWarning = parent.rsWarning;

    rsTemplateUnsuitable = parent.rsTemplateUnsuitable;

    rsTargetGroups = parent.rsTargetGroups;

	  _IPE();
	});
});


// "constructor"
function _IPE(){
  if(Initialized) { return;}
		Init_IPE();
		parent.Modified = false;
		CreateTableOfContents();
		Initialized = true;
}

function InitializeTemplate(){
 // single and multiline fields
 var A = Array('singleline', 'multiline');
 for(var i=0; i<A.length; i++)
   $(A[i]).each(
  		function(aindex){
  		   var label = "";
       if($(this).attr("label"))
         label = $(this).attr("label");
  		   var targetgroups = "";
       if($(this).attr("target_groups"))
         targetgroups = ' target_groups="' + $(this).attr("target_groups") + '"';
       if(A[i] == "singleline") {

    		   var tableofcontentstitle = "false";
         if($(this).attr("tableofcontentstitle") == "true")
           tableofcontentstitle = $(this).attr("tableofcontentstitle");

         $(this).wrap("<div class=\"ipe_item\" rel=\"" + A[i] + "\"  style=\"\" label=\"" + label + "\" tableofcontentstitle=\"" + tableofcontentstitle + "\"" + targetgroups + "></div>")
       }
       else {
         $(this).wrap("<div class=\"ipe_item\" rel=\"" + A[i] + "\"  style=\"\" label=\"" + label + "\"" + targetgroups + "></div>")
       }
       // remove tag
       if($(this).children().html())
         $(this).children().unwrap();
         else {
           var html = $(this).html();
           $(this).parent().html($(this).html());
           $(this).remove();
         }
  	 }
   );

 $('div[class="socialmedia"]').each(
		function(aindex){
   var label = "";
   if($(this).attr("label"))
     label = $(this).attr("label");
   var targetgroups = "";
   if($(this).attr("target_groups"))
      targetgroups = ' target_groups="' + $(this).attr("target_groups") + '"';
   $(this).wrap("<div class=\"ipe_item\" rel=\"socialmedia\"  style=\"\" label=\"" + label + "\"" + targetgroups + "></div>")
   $(this).attr("class", null);
  }
 );

 // images
 $("img").each(
		function(aindex){
		   if($(this).attr("editable") && $(this).attr("editable") == "true") {
        if($(this).closest(".ipe_item").attr("rel") != "multiline") { // when there are an .ipe_item for multiline we don't add a new, we edit it as block
     		   var label = "";
          if($(this).attr("label"))
            label = $(this).attr("label");
          var targetgroups = "";
          if($(this).attr("target_groups"))
             targetgroups = ' target_groups="' + $(this).attr("target_groups") + '"';
          $(this).wrap("<div class=\"ipe_item\" rel=\"" + "img" + "\"  style=\"\" label=\"" + label + "\"" + targetgroups + "></div>")
        }
        $(this).attr("editable", null); // remove for reload on add item
     }
	 }
 );

 // hr
 $("hr").each(
		function(aindex){
		   if($(this).attr("editable") && $(this).attr("editable") == "true") {
        if($(this).closest(".ipe_item").attr("rel") != "multiline") { // when there are an .ipe_item for multiline we don't add a new, we edit it as block
     		   var label = "";
          if($(this).attr("label"))
            label = $(this).attr("label");
          var targetgroups = "";
          if($(this).attr("target_groups"))
             targetgroups = ' target_groups="' + $(this).attr("target_groups") + '"';
          $(this).wrap("<div class=\"ipe_item\" rel=\"" + "hr" + "\"  style=\"\" label=\"" + label + "\"" + targetgroups + "></div>")
        }
        $(this).attr("editable", null); // remove for reload on add item
     }
	 }
 );

 // ul, ol
 $("ul, ol").each(
		function(aindex){
		   if($(this).attr("editable") && $(this).attr("editable") == "true") {
        if($(this).closest(".ipe_item").attr("rel") != "multiline") { // when there are an .ipe_item for multiline we don't add a new, we edit it as block
     		   var label = "";
          if($(this).attr("label"))
            label = $(this).attr("label");
          var targetgroups = "";
          if($(this).attr("target_groups"))
             targetgroups = ' target_groups="' + $(this).attr("target_groups") + '"';
          $(this).wrap("<div class=\"ipe_item\" rel=\"" + $(this).get(0).tagName.toLowerCase() + "\"  style=\"\" label=\"" + label + "\"" + targetgroups + "></div>")
        }
        $(this).attr("editable", null); // remove for reload on add item
     }
	 }
 );

 // links
 $("a").each(
		function(aindex){
		   if($(this).attr("editable") == "true" && ($(this).attr("name") == "" || $(this).attr("name") == null ) ) {
        if($(this).closest(".ipe_item").attr("rel") != "multiline") { // when there are an .ipe_item for multiline we don't add a new, we edit it as block
          var label = "";
          if($(this).attr("label"))
            label = $(this).attr("label");
          var targetgroups = "";
          if($(this).attr("target_groups"))
             targetgroups = ' target_groups="' + $(this).attr("target_groups") + '"';
          $(this).wrap("<div class=\"ipe_item\" rel=\"" + "a" + "\"  style=\"display:inline\" label=\"" + label + "\"" + targetgroups + "></div>")
        }
        $(this).attr("editable", null); // remove for reload on add item

     }
	 }
 );

 // repeaters
 $("repeater").each(
		function(aindex){
     var repeateritemdata = $(this).attr("repeateritemdata") ? $(this).attr("repeateritemdata") :  encodeURIComponent(utf8_encode(($(this).html())));
     var label = "";
     var editable = false;
     if($(this).attr("label"))
       label = $(this).attr("label");
     var targetgroups = "";
     if($(this).attr("target_groups"))
        targetgroups = ' target_groups="' + $(this).attr("target_groups") + '"';
     if($(this).attr("editable") && $(this).attr("editable") == "true")
       editable = true;
     var editablehtml = '';
     if(editable)
       editablehtml = 'editable="true"';
     $(this).wrap("<div class=\"ipe_repeater\" rel=\"repeater\" id=\"" + repeater_item_id + "\" style=\"\" label=\"" + label + "\" repeateritemdata=\"" + repeateritemdata + "\" " + editablehtml + targetgroups + "></div>")

     $(this).wrap("<div class=\"ipe_repeater_item\" rel=\"repeater_item\" id=\"" + repeater_item_id + "-0\" style=\"\" label=\"" + label + "\" " + editablehtml + targetgroups + "></div>")

     if(editable)
       $(this).wrap("<div class=\"editable\" style=\"\"" + editablehtml + targetgroups + "></div>")

     repeater_item_id++;
     // remove tag
     $(this).children().unwrap();
	 }
 );

 //tableofcontents
 createtableofcontents = ($("tableofcontents").html() != "") || ($('div[id="tableofcontents"]').html());
 if(createtableofcontents) {
   $("tableofcontents").each(
   		function(){
       if($(this).html()) {
         var style = "";
         if($(this).attr("style"))
           style = ' style="' + $(this).attr("style") + '"';
         var aclass = "";
         if($(this).attr("class"))
           aclass = ' class="' + $(this).attr("class") + '"';
         var tableofcontentsdata = encodeURIComponent(utf8_encode($(this).html()));
         $(this).wrap("<div id=\"tableofcontents\" tableofcontentsdata=\"" + tableofcontentsdata + "\"" + style + aclass + "></div>");
         // remove tag
         $(this).children().unwrap();
       }
     }
   );
 }

}

function AddRepeaterItem(target, html, label){

 // remove no_templated_loaded id
 $("#no_template_loaded").remove();

 var editable = false;
 if($(target).attr("editable"))
   editable = true;
 if(html.indexOf('editable="false"') >= 0)
   editable = false;

	$(target).find(".ipe_repeater_toolbar").before("<div class=\"ipe_repeater_itemNEW\" id=\"ipe_repeater_itemNEW\"></div>");
	var item = $(target).find(".ipe_repeater_itemNEW");
	item.attr("class", "ipe_repeater_item");
	item.attr("rel", "repeater_item");
	item.attr("label", label);
 if(editable) {
  	item.attr("editable", "true");
   html = "<div class=\"editable\" style=\"\" editable=\"true\">" + html + "</div>";
 }
 if(html.indexOf("<tableofcontents>") > -1 || html.indexOf("<TABLEOFCONTENTS>") > -1)
   	item.attr("tableofcontents_mainitem", "true");
	item.html(html);

	$(target).find(".ipe_repeater_item").each(function(AIndex){
	  $(this).attr("id", target.attr("id") + "-" + AIndex);
	});

 Init_IPE();
 CreateTableOfContents();
	parent.Modified = true;
}

function NewRepeaterItem(that){

  // remove no_templated_loaded id
  $("#no_template_loaded").remove();

  parent.$( "#dialog-new" ).dialog( "destroy" );
  parent.$( "#dialog-new" ).dialog({
			resizable: true,
			modal: true,
			width: 650,
			height: 500,
   close: function() {
              $(that).closest(".ipe_item").removeClass("ipe_hover");
              $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
          }
		});

		parent.LoadNewIPEElements("dialog-new_items");

  parent.$( "#dialog-new" ).dialog( "option", "buttons", [
    {
        text: html_entity_decode(rsApply),
        click: function() {
                             parent.$(this).dialog("close");

                             var filename = parent.$(this).find(".ui-selected").children().attr("rel");
                             if(filename != null) {
                               var date = new Date();
                               var nocache = date.getTime() / 1000;
                              	jQuery.get('ajax_load_new_ipe_elements.php' + "?nocache=" + nocache + '&filename=' + filename, "", function(data){

                              	   AddRepeaterItem(that.closest(".ipe_repeater"), data, $(this).closest(".ipe_repeater").attr("label") ? $(this).closest(".ipe_repeater").attr("label") : "");

                              	});

                             		parent.Modified = true;
                           		}

                          }
    },

    {
        text: rsCancel,
        click: function() {
                             parent.$(this).dialog("close");
                          }
    }

  ] );

}

function CreateTableOfContents(){
  if(!createtableofcontents) return;

  $('div[id="tableofcontents"]').each(
  		function(){

       var tableofcontents = $(this);
       var tableofcontentsdata = utf8_decode(decodeURIComponent($(tableofcontents).attr("tableofcontentsdata")));
       $(this).find("a").each(function(){
         var anchortext = $(this).attr("href").substr(1);
         $(this).remove();
         $('body').find('a[id*="' + anchortext + '"]').each(function(){
            $(this).remove();
        	});
      	});

       $(tableofcontents).html("");

       $(".ipe_item").each(function(AIndex){
         if( !$(this).attr("tableofcontentstitle") || $(this).attr("tableofcontentstitle") == "false" ) return true; //continue
         if( ($(this).attr("rel") != "singleline") ) return true; //continue
        	var clone = $(this).clone(false);
        	clone.find(".ipe_toolbar").remove();
         var anchortext = RemoveDoubleSpacesInSingleLine(clone.text().trim());
         clone.remove();
         $(tableofcontents).append(tableofcontentsdata);

         var astyle = "";
         var aclass = "";
         if($(tableofcontents).find("tableofcontentstitle").attr("style"))
           astyle = ' style="' + $(tableofcontents).find("tableofcontentstitle").attr("style") + '"';
           else
           if($(tableofcontents).attr("style"))
              astyle = ' style="' + $(tableofcontents).attr("style") + '"';
         if($(tableofcontents).find("tableofcontentstitle").attr("class"))
           aclass = ' class="' + $(tableofcontents).find("tableofcontentstitle").attr("class") + '"';
           else
           if($(tableofcontents).attr("class"))
              aclass = ' class="' + $(tableofcontents).attr("class") + '"';

         $(tableofcontents).find("tableofcontentstitle").parent().append("<a href=\"#anchor_" + $(this).attr("id") + "\"" + astyle + aclass + ">" + anchortext + "</a>");
         // IE7 bug, #anchor will be changed to url#anchor, there is no fix

         $(tableofcontents).find("tableofcontentstitle").remove();
         var a = 'a[id="anchor_' + $(this).attr("id") + '"]';
         if( $("body").find( a ).attr("id") == null ) { // prevent dup anchors
           $(this).before("<a rel=\"tableofcontentsentry\" name=\"anchor_" + $(this).attr("id") + "\" id=\"anchor_" + $(this).attr("id") + "\"></a>");
         }

      	});

       if($.browser.msie && parseInt($.browser.version, 10) < 9){
         // IE < 9 fix, remove single </TABLEOFCONTENTSTITLE> tags
         var html = $(tableofcontents).html();
         html = html.replace(/\<\/TABLEOFCONTENTSTITLE\>/ig, "");
         $(tableofcontents).html(html);
       }


  		}
  )

}

function RemoveTableOfContents(ignoreitem){
  if(!createtableofcontents) return false;

  var tableofcontentsincludedoncemore = false;
  $('div[id="tableofcontents"]').each( // $("#tableofcontents")
  		function(){
  		   if(ignoreitem != null && ignoreitem.attr("id") != $(this).closest(".ipe_repeater_item").attr("id")){
  		     tableofcontentsincludedoncemore = true; // is included in an other block
  		   }
       var tableofcontents = $(this);
       $(tableofcontents).empty();
    }
  );

  $('body').find('a[rel="tableofcontentsentry"]').each(function(){
     $(this).remove();
 	});
 	if(!tableofcontentsincludedoncemore)
 	   createtableofcontents = false;
 	return tableofcontentsincludedoncemore;
}

function RemoveTableOfContentsEntry(id){
  if(!createtableofcontents) return;
  var anchortext = "anchor_" + id;


  $('body').find('a[href*="#' + anchortext + '"]').each(function(){
     $(this).parent().children().unwrap();
     $(this).remove();
 	});
  $('body').find('a[id*="' + anchortext + '"]').each(function(){
     $(this).remove();
 	});
}

function EditTableOfContentsEntry(id, newtext){
  if(!createtableofcontents) return;
  var anchortext = "anchor_" + id;


  $('body').find('a[href*="#' + anchortext + '"]').each(function(){
     $(this).text(newtext);
 	});
}

function SortTableOfContents(){
  // create it new
  CreateTableOfContents();
}

function EditItem(that){

  // remove no_templated_loaded id
  $("#no_template_loaded").remove();

  if($(that).attr("rel") == "singleline") {

    if($(that).attr("label")) {
      parent.$("#labelForSingleline").html($(that).attr("label"));
    } else {
      parent.$("#labelForSingleline").html( parent.$("#labelForSingleline").attr("defaulttitle") );
    }

    parent.$( "#dialog-singleline" ).dialog( "destroy" );
    parent.$( "#dialog-singleline" ).dialog({
  			resizable: false,
  			modal: true,
  			width: 600,
     close: function() {
              $(that).closest(".ipe_item").removeClass("ipe_hover");
              $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
              MakeRepeatersSortable(); // add sortable
            }
  		});

    parent.$( "#dialog-singleline" ).find('input').keypress(function(e) {
    	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
    		$(this).parent().parent().parent().parent().find('.ui-dialog-buttonpane').find('button:first').click(); /* Assuming the first one is the action button */
    		return false;
    	}
    });
    //

  		var singleline = parent.$( "#singleline" );
  		var singleline_hyperlink = parent.$( "#singleline_hyperlink" );
   	var clone = $(that).find(".ipe_toolbar").clone(true);
   	$(that).find(".ipe_toolbar").remove();
   	if($(that).find("a").attr("href") == null) {
      		singleline.val( RemoveDoubleSpacesInSingleLine($(that).text().trim()) );
      		singleline_hyperlink .val("");
    		}
    		else {
      		singleline.val( RemoveDoubleSpacesInSingleLine($(that).text().trim()) );
      		singleline_hyperlink.val( $(that).find("a").attr("href") );
    		}
  		$(that).prepend(clone);

    parent.$( "#dialog-singleline" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");

                               parent.$(this).dialog("close");

                              	var clone = $(that).find(".ipe_toolbar").clone(true);
                              	$(that).find(".ipe_toolbar").remove();
                              	$(that).empty();
                              	if( singleline_hyperlink.val().trim() == "") {
                               		$(that).text( singleline.val().trim() );
                               } else {

                                 var alink = singleline_hyperlink.val().trim();
                                 if(alink.indexOf("http") < 0 && alink.indexOf("mailto:") < 0 && alink.indexOf("[") != 0) // not field name as first char
                                    alink = "http://" + alink;

                                 $(that).html( '<a href="' + alink + '">' + singleline.val().trim() + '</a>' );
                               }
                             		$(that).prepend(clone);

                             		parent.Modified = true;
                               EditTableOfContentsEntry($(that).attr("id"), singleline.val().trim());
                               MakeRepeatersSortable(); // add sortable
                            }
      },

      {
          text: rsCancel,
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");

                            }
      }

    ] );
  }

  if($(that).attr("rel") == "img") {

    if($(that).attr("label")) {
      parent.$("#labelForImage").html($(that).attr("label"));
    } else {
      parent.$("#labelForImage").html( parent.$("#labelForImage").attr("defaulttitle") );
    }

    parent.$( "#dialog-image" ).dialog( "destroy" );
    parent.$( "#dialog-image" ).dialog({
  			resizable: false,
  			modal: true,
  			width: 600,
     close: function() {
                $(that).closest(".ipe_item").removeClass("ipe_hover");
                $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
            }
  		});

    parent.$( "#dialog-image" ).find('input').keypress(function(e) {
    	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
    		$(this).parent().parent().parent().parent().find('.ui-dialog-buttonpane').find('button:first').click(); /* Assuming the first one is the action button */
    		return false;
    	}
    });
    //

  		var imageurl = parent.$( "#imageurl" );
  		var alttext = parent.$( "#alttext" );
  		var image_hyperlinkurl = parent.$( "#image_hyperlinkurl" );
  		imageurl.val( $(that).find("img").attr("src") );
  		alttext.val( $(that).find("img").attr("alt") );

    var x = $(that).find("img").attr("src");
    if(x.indexOf("http:") == -1 && x.indexOf("https:") == -1)
      parent.$("#chkassiInsertImageAsCompleteURL").attr("checked", false);
      else
      parent.$("#chkassiInsertImageAsCompleteURL").attr("checked", true);

   	if($(that).find("a").attr("href") == null) {
    		image_hyperlinkurl.val("");
    		}
    		else {
      		image_hyperlinkurl.val( $(that).find("a").attr("href") );
    		}

    parent.$( "#dialog-image" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");

                             		$(that).find("img").attr("src", imageurl.val().trim());
                             		$(that).find("img").attr("alt", alttext.val().trim());

                              	if( image_hyperlinkurl.val().trim() == "") {
                            		   $(that).find("a").children().unwrap();
                               } else {

                            		   $(that).find("a").children().unwrap();

                                 var alink = image_hyperlinkurl.val().trim();
                                 if(alink.indexOf("http") < 0 && alink.indexOf("mailto:") < 0 && alink.indexOf("[") != 0) // not field name as first char
                                    alink = "http://" + alink;

                                 $(that).find("img").wrap( '<a href="' + alink + '"></a>' );
                               }

                               parent.$(this).dialog("close");
                             		parent.Modified = true;

                            }
      },

      {
          text: rsCancel,
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");
                            }
      }

    ] );
  }

  if($(that).attr("rel") == "a") {

    if($(that).attr("label")) {
      parent.$("#labelForHyperlink").html($(that).attr("label"));
    } else {
      parent.$("#labelForHyperlink").html( parent.$("#labelForHyperlink").attr("defaulttitle") );
    }

    parent.$( "#dialog-hyperlink" ).dialog( "destroy" );
    parent.$( "#dialog-hyperlink" ).dialog({
  			resizable: false,
  			modal: true,
  			width: 600,
     close: function() {
                $(that).closest(".ipe_item").removeClass("ipe_hover");
                $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
            }
  		});

    parent.$( "#dialog-hyperlink" ).find('input').keypress(function(e) {
    	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
    		$(this).parent().parent().parent().parent().find('.ui-dialog-buttonpane').find('button:first').click(); /* Assuming the first one is the action button */
    		return false;
    	}
    });
    //

  		var hyperlink = parent.$( "#hyperlink" );
  		var textforhyperlink = parent.$( "#textforhyperlink" );
  		hyperlink.val( $(that).find("a").attr("href") );
  		textforhyperlink.val( RemoveDoubleSpacesInSingleLine($(that).find("a").text().trim()) );


    parent.$( "#dialog-hyperlink" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");

                               parent.$(this).dialog("close");

                               var alink = hyperlink.val().trim();
                               if(alink.indexOf("http") < 0 && alink.indexOf("mailto:") < 0 && alink.indexOf("#") != 0 && alink.indexOf("[") != 0)
                                  alink = "http://" + alink;

                             		$(that).find("a").attr("href", alink);
                             		if(textforhyperlink.val().trim() != "")
                               		$(that).find("a").text(textforhyperlink.val().trim());
                               		else
                               		$(that).find("a").text(alink);
                             		parent.Modified = true;

                            }
      },

      {
          text: rsCancel,
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");
                            }
      }

    ] );
  }

  if($(that).attr("rel") == "multiline") {

    if($(that).attr("label")) {
      parent.$("#labelForMultiline").html($(that).attr("label"));
    } else {
      parent.$("#labelForMultiline").html( parent.$("#labelForMultiline").attr("defaulttitle") );
    }

  		var multiline = parent.$( "#multiline" );

   	var clone = $(that).find(".ipe_toolbar").clone(true);
   	$(that).find(".ipe_toolbar").remove();
   	var multilinehtml = $(that).html().replace(/\n/g, ' ').replace(/ +/g, ' ').replace(/\t/g, '');
  		$(that).prepend(clone);

    // bug ckeditor parent.$( "#dialog-multiline" ).dialog( "destroy" );
    parent.$( "#dialog-multiline" ).dialog({
  			resizable: false,
     autoOpen: true,
  			modal: true,
  			width: 700,
  			open : function() {
  			         multiline.val( multilinehtml );
              parent.LoadMultilineToCKEditor();
            },
     close: function() {
                $(that).closest(".ipe_item").removeClass("ipe_hover");
                $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                MakeRepeatersSortable(); // add sortable
            }
  		});
    //

    parent.$( "#dialog-multiline" ).find('input').keypress(function(e) {
    	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
    		$(this).parent().parent().parent().parent().find('.ui-dialog-buttonpane').find('button:first').click();
    		return false;
    	}
    });

    parent.$( "#dialog-multiline" ).dialog( "option", "position", 'center' );

    parent.$( "#dialog-multiline" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");

                               try {
                                  parent.CKEDITOR.instances.multiline.updateElement();

                                } catch (e) {
                                  alert("Error getting content of editor");
                                }

                               parent.$(this).dialog("close");

                              	var clone = $(that).find(".ipe_toolbar").clone(true);
                              	$(that).find(".ipe_toolbar").remove();
                              	$(that).empty();
                             		$(that).html( multiline.val() );
                             		$(that).prepend(clone);

                             		parent.Modified = true;

                               multiline.val("");
                            }
      },

      {
          text: rsCancel,
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");

                            }
      }

    ] );



  }


  if($(that).attr("rel") == "hr") {

    parent.$( "#dialog-hr" ).dialog( "destroy" );
    parent.$( "#dialog-hr" ).dialog({
  			resizable: false,
  			modal: true,
  			width: 600,
  			closeOnEscape: false, // problems with colorpicker
     close: function() {
                $(that).closest(".ipe_item").removeClass("ipe_hover");
                $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                parent.CloseColorPicker();
            }
  		});

    parent.$( "#dialog-hr" ).find('input').keypress(function(e) {
    	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
    		$(this).parent().parent().parent().parent().find('.ui-dialog-buttonpane').find('button:first').click(); /* Assuming the first one is the action button */
    		return false;
    	}
    });
    //

    if($(that).find("hr").attr("width") != null)
      parent.$( "#hr_width" ).val( $(that).find("hr").attr("width") )
    else
      parent.$( "#hr_width" ).val( $(that).find("hr").css("width") );

    if($(that).find("hr").attr("size") != null)
    		parent.$( "#hr_height" ).val( $(that).find("hr").attr("size") + 'px' );
    else
    		parent.$( "#hr_height" ).val( $(that).find("hr").css("height") );

    if( $(that).find("hr").css("text-align") != null )
      parent.$( "#hralignmentCB" ).val( $(that).find("hr").css("text-align") );
      else
      parent.$( "#hralignmentCB" ).val( "left" );

    if( $(that).find("hr").attr("color") != null )
      parent.$( "#hr_color" ).val( $(that).find("hr").attr("color") );
      else
      if( $(that).find("hr").css("color") != null )
         parent.$( "#hr_color" ).val( convertRGBToHex($(that).find("hr").css("color")) );

    if($(that).find("hr").attr("noshade") != null)
       parent.$( "#hr_noshade" ).attr("checked", "on");
       else
       parent.$( "#hr_noshade" ).attr("checked", null);

    parent.$( "#dialog-hr" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");

                             		$(that).find("hr").attr("width", null);
                             		$(that).find("hr").attr("size", null);
                             		$(that).find("hr").attr("align", null);
                             		$(that).find("hr").attr("color", null);

                             		$(that).find("hr").attr("width", parent.$( "#hr_width" ).val());
                             		$(that).find("hr").css("width", parent.$( "#hr_width" ).val());
                             		$(that).find("hr").css("height", parent.$( "#hr_height" ).val());
                             		$(that).find("hr").css("text-align", parent.$( "#hralignmentCB" ).val());
                             		$(that).find("hr").attr("color", parent.$( "#hr_color" ).val());
                             		$(that).find("hr").css("color", parent.$( "#hr_color" ).val());
                             		$(that).find("hr").css("background-color", parent.$( "#hr_color" ).val());
                             		if(parent.$( "#hr_noshade" ).attr("checked") != null)
                             		    $(that).find("hr").attr("noshade", "noshade");
                             		    else
                             		    $(that).find("hr").attr("noshade", null);


                               parent.$(this).dialog("close");
                             		parent.Modified = true;

                            }
      },

      {
          text: rsCancel,
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");
                            }
      }

    ] );
  }

  if($(that).attr("rel") == "ul" || $(that).attr("rel") == "ol" ) {

    var listTag = "ul, ol";

    parent.$( "#dialog-list" ).dialog( "destroy" );
    parent.$( "#dialog-list" ).dialog({
  			resizable: false,
  			modal: true,
  			width: 600,
  			closeOnEscape: false, // problems with double dialogs
     close: function() {
                $(that).closest(".ipe_item").removeClass("ipe_hover");
                $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
            },
     open: function() {
              parent.$("#dialog-list-edit").attr("disabled", "disabled");
              parent.$("#dialog-list-edit").addClass('ui-state-disabled');

              parent.$("#dialog-list-remove").attr("disabled", "disabled");
              parent.$("#dialog-list-remove").addClass('ui-state-disabled');

              parent.MakeListEntriesSelectable();
              parent.ActivateDeactivateUpDownBtns();
            }
  		});

    parent.$( "#dialog-list" ).find('input').keypress(function(e) {
    	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
    		$(this).parent().parent().parent().parent().find('.ui-dialog-buttonpane').find('button:first').click(); /* Assuming the first one is the action button */
    		return false;
    	}
    });
    //

    parent.$( "#dialog-list" ).find("#dialog-list-elements").html("");

   	var clone = $(that).clone(false);
   	$(clone).find(".ipe_toolbar").remove();
   	$(clone).find("li").each(function(){
  		   $(this).attr("class", "ui-state-default");
  		   $(this).append('<div class="li_delete ui-button ui-icon ui-icon-trash ui-corner-all" title="' + rsDeleteItem + '"></div>');
	   });

    if($(that).find(listTag).css("list-style-type") != null)
       parent.$("#ListStyleTypeCB").val($(that).find(listTag).css("list-style-type"));
       else
       parent.$("#ListStyleTypeCB").val("disc");

    if( $(that).find(listTag).css("text-align") != null )
      parent.$("#ListalignmentCB" ).val( $(that).find(listTag).css("text-align") );
      else
      parent.$("#ListalignmentCB" ).val( "left" );

    if(clone.find(listTag).children())
      clone.find(listTag).children().unwrap();
      else
      clone.html("");
    parent.$("#dialog-list-elements").prepend(clone.html());
    clone = null;
    parent.MakeListEntriesSelectable();


    parent.$( "#dialog-list" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");

                               parent.$(this).dialog("close");

                               $(that).find(listTag).css("list-style-type", parent.$("#ListStyleTypeCB").val());
                               $(that).find(listTag).css("text-align", parent.$("#ListalignmentCB" ).val());

                               $(that).find(listTag).html("");
                               var clone = parent.$(this).find(listTag);
                               clone.find(".li_delete").remove();
                               clone.find("li").attr("class", null);
                               $(that).find(listTag).prepend(clone.html());
                               clone = null;

                             		parent.Modified = true;

                            }
      },

      {
          text: rsCancel,
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");
                            }
      }

    ] );
  }

  if($(that).attr("rel") == "socialmedia") {

    if($(that).attr("label")) {
      parent.$("#labelForSocialMedia").html($(that).attr("label"));
    } else {
      parent.$("#labelForSocialMedia").html( parent.$("#labelForSocialMedia").attr("defaulttitle") );
    }

    parent.$( "#dialog-socialmedia" ).dialog( "destroy" );
    parent.$( "#dialog-socialmedia" ).dialog({
  			resizable: false,
  			modal: true,
  			width: 600,
     close: function() {
                $(that).closest(".ipe_item").removeClass("ipe_hover");
                $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                MakeRepeatersSortable(); // add sortable
            }
  		});

    parent.$( "#dialog-socialmedia" ).find('input').keypress(function(e) {
    	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
    		$(this).parent().parent().parent().parent().find('.ui-dialog-buttonpane').find('button:first').click(); /* Assuming the first one is the action button */
    		return false;
    	}
    });
    //

  		var socialmediabar = parent.$( "#socialmediabar" );
  		var smbaralignmentCB = parent.$( "#smbaralignmentCB" );



    var date = new Date();
    var nocache = date.getTime() / 1000;
    var filename = "socialmediashare.htm";
   	jQuery.get('ajax_load_new_ipe_elements.php' + "?nocache=" + nocache + '&filename=' + filename + '&smechkboxes=true', "", function(data){

       socialmediabar.html(data);

       // check checkbox for shown items
       $(that).find(".socialmedia_item").each(function(){
         socialmediabar.find("#" + $(this).attr("id")).find("input").attr("checked", true);
      	});

       if( $(that).css("text-align") != null )
         smbaralignmentCB.val( $(that).css("text-align") );
         else
         smbaralignmentCB.val( "left" );

      	socialmediabar.find(".socialmedia_item").hover(function(e){
         $(this).addClass('ui-state-hover');
      	}, function(){
          $(this).removeClass('ui-state-hover');
      	});

       socialmediabar.find(".socialmedia_item").off('click');
      	socialmediabar.find(".socialmedia_item").click(function(e){
        e.stopPropagation();
        $(this).find("input").attr("checked", !$(this).find("input").attr("checked") );
      	});

       socialmediabar.find(".socialmedia_item").find("input").off('click');
      	socialmediabar.find(".socialmedia_item").find("input").click(function(e){
        e.stopPropagation();
      	});

       socialmediabar.find(".socialmedia_item").find("a").off('click');
      	socialmediabar.find(".socialmedia_item").find("a").click(function(e){
        e.stopPropagation();
        $(this).parent().find("input").attr("checked", !$(this).parent().find("input").attr("checked") );
        return false;
      	});

   	});



    parent.$( "#dialog-socialmedia" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");

                               $(that).css("text-align", smbaralignmentCB.val( ));

                               $(that).find("#socialmedia").html("");
                               socialmediabar.find(".socialmedia_item").each(function(){
                                 if( $(this).find("input").attr("checked") ) {
                                     $(this).find("input").remove();
                                     $(this).find("br").remove();

                                     var clone = $(this).clone();
                                     $(that).find("#socialmedia").append( clone );
                                 }
                              	});


                               parent.$(this).dialog("close");
                             		parent.Modified = true;

                            }
      },

      {
          text: rsCancel,
          click: function() {
                               $(that).closest(".ipe_item").removeClass("ipe_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_edit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");
                            }
      }

    ] );
  }

}

function ShowTargetGroupsDialog(obj){

  var tgroups = obj.attr("target_groups");

  //parent.$( "#dialog-targetgroups" ).dialog( "destroy" );   don't destroy it gives jquery errors in iframe

  parent.$( "#dialog-targetgroups" ).dialog({
			resizable: false,
			modal: true,
			width: 600,
   open: function( ) {
          try{
            parent.document.getElementById("__wizardtargetgroupsIframe").contentWindow.UnCheckAll();
          } catch (e) {
           // ignore
          }
          parent.$( "#dialog-targetgroups" ).find("#_edit_target_groups").val(tgroups);
          parent.document.getElementById("__wizardtargetgroupsIframe").src = "./ajax_showtargetgroups.php?wizard=wizard";
         }
		});

  parent.$( "#dialog-targetgroups" ).find('input').keypress(function(e) {
  	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
  		$(this).parent().parent().parent().parent().find('.ui-dialog-buttonpane').find('button:first').click(); /* Assuming the first one is the action button */
  		return false;
  	}
  });
  //

  parent.$( "#dialog-targetgroups" ).dialog( "option", "buttons", [
    {
        text: html_entity_decode(rsApply),
        click: function() {
                             tgroups = parent.document.getElementById("__wizardtargetgroupsIframe").contentWindow.GetCheckedTargetGroups();

                             if(!tgroups)
                                obj.attr("target_groups", null);
                                else
                                obj.attr("target_groups", tgroups);

                             parent.$(this).dialog("close");
                             parent.Modified = true;
                             return true;
                          }
    },

    {
        text: rsCancel,
        click: function() {
                             parent.$(this).dialog("close");
                          }
    }

  ] );

  return false;
}

function CSSEditor(that){

    // remove no_templated_loaded id
    $("#no_template_loaded").remove();

    parent.$( "#dialog-cssedit" ).dialog( "destroy" );
    parent.$( "#dialog-cssedit" ).dialog({
  			resizable: false,
  			modal: true,
  			width: 600,
  			closeOnEscape: false, // problems with colorpicker
     close: function() {
              $(that).removeClass("ipe_repeater_item_hover");
              $(that).find(".ipe_toolbar").find(".ipe_cssedit").removeClass("ui-state-hover");
              parent.CloseColorPicker();
            }
  		});

    // set values
    parent.$( "#dialog-cssedit" ).find('#fontName').val( $(that).css('font-family') );
    parent.$( "#dialog-cssedit" ).find('#fontSize').val( $(that).css('font-size') );
    parent.$( "#dialog-cssedit" ).find('#fontcolor').val( convertRGBToHex($(that).css('color')) );
    parent.$( "#dialog-cssedit" ).find('#bgcolor').val( convertRGBToHex($(that).css('background-color')) );
    parent.$( "#dialog-cssedit" ).find('#width').val( $(that).css('width') );
    parent.$( "#dialog-cssedit" ).find('#height').val( $(that).css('height') );


    var a = Array("", "-top", "-left", "-right", "-bottom");

    parent.$( "#dialog-cssedit" ).find('#padding').val( "" );
    for(var i=0; i<a.length; i++)
      if( $(that).css('padding' + a[i]) != null && $(that).css('padding' + a[i]) != "none" && $(that).css('padding' + a[i]) != "")  {
         parent.$( "#dialog-cssedit" ).find('#padding').val( $(that).css('padding' + a[i]) );
         break;
       }

    parent.$( "#dialog-cssedit" ).find('#margin').val( "" );
    for(var i=0; i<a.length; i++)
      if( $(that).css('margin' + a[i]) != null &&  $(that).css('margin' + a[i]) != "none" && $(that).css('margin' + a[i]) != "")  {
         parent.$( "#dialog-cssedit" ).find('#margin').val( $(that).css('margin' + a[i]) );
         break;
       }


    var a = Array("", "-top-", "-left-", "-right-", "-bottom-");
    parent.$( "#dialog-cssedit" ).find('#borderStyle').val( 'none' );
    for(var i=0; i<a.length; i++)
      if( $(that).css('border' + a[i] + 'style') != null && $(that).css('border' + a[i] + 'style') != "none" && $(that).css('border' + a[i] + 'style') != "")  {
         parent.$( "#dialog-cssedit" ).find('#borderStyle').val( $(that).css('border' + a[i] + 'style') );
         break;
       }

    parent.$( "#dialog-cssedit" ).find('#borderWidth').val( "" );
    for(var i=0; i<a.length; i++)
      if( $(that).css('border' + a[i] + 'width') != null && $(that).css('border' + a[i] + 'width') != "none" && $(that).css('border' + a[i] + 'width') != "")  {
         parent.$( "#dialog-cssedit" ).find('#borderWidth').val( $(that).css('border' + a[i] + 'width') );
       }

    parent.$( "#dialog-cssedit" ).find('#borderColor').val( "" );
    for(var i=0; i<a.length; i++)
      if( $(that).css('border' + a[i] + 'color') != null && $(that).css('border' + a[i] + 'color') != "none" && $(that).css('border' + a[i] + 'color') != "")  {
         parent.$( "#dialog-cssedit" ).find('#borderColor').val( convertRGBToHex($(that).css('border' + a[i] + 'color')) );
       }
    //

    // remove width/height on changing fonts
    parent.$( "#dialog-cssedit" ).find('#fontName, #fontSize, #padding, #margin').change(function(){
      parent.$( "#dialog-cssedit" ).find('#width').val("");
      parent.$( "#dialog-cssedit" ).find('#height').val("");
    });

    parent.$( "#dialog-cssedit" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               $(that).removeClass("ipe_repeater_item_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_cssedit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");

                               // set values
                               $(that).css('font-family', parent.$( "#dialog-cssedit" ).find('#fontName').val());
                               $(that).css('font-size', parent.$( "#dialog-cssedit" ).find('#fontSize').val());
                               $(that).css('color', parent.$( "#dialog-cssedit" ).find('#fontcolor').val());
                               $(that).css('background-color', parent.$( "#dialog-cssedit" ).find('#bgcolor').val());
                               $(that).css('width', parent.$( "#dialog-cssedit" ).find('#width').val());
                               $(that).css('height', parent.$( "#dialog-cssedit" ).find('#height').val());
                               $(that).css('padding', parent.$( "#dialog-cssedit" ).find('#padding').val());
                               $(that).css('margin', parent.$( "#dialog-cssedit" ).find('#margin').val());
                               $(that).css('border-style', parent.$( "#dialog-cssedit" ).find('#borderStyle').val());
                               $(that).css('border-width', parent.$( "#dialog-cssedit" ).find('#borderWidth').val());
                               $(that).css('border-color', parent.$( "#dialog-cssedit" ).find('#borderColor').val());
                               //


                             		parent.Modified = true;
                            }
      },

      {
          text: rsCancel,
          click: function() {
                               $(that).removeClass("ipe_repeater_item_hover");
                               $(that).find(".ipe_toolbar").find(".ipe_cssedit").removeClass("ui-state-hover");
                               parent.$(this).dialog("close");
                            }
      }

    ] );

}

function PageBodyPropertiesEditor(){

    // remove no_templated_loaded id
    $("#no_template_loaded").remove();

    var that = $("body");

    parent.$( "#dialog-pagebodyproperties" ).dialog( "destroy" );
    parent.$( "#dialog-pagebodyproperties" ).dialog({
  			resizable: false,
  			modal: true,
  			width: 600,
  			closeOnEscape: false, // problems with colorpicker
     close: function() {
              parent.CloseColorPicker();
            }
  		});


    // set values
    parent.$( "#dialog-pagebodyproperties" ).find('#bodybgcolor').val( convertRGBToHex($(that).css('background-color')) );
    var a = Array("", "-top", "-left", "-right", "-bottom");

    parent.$( "#dialog-pagebodyproperties" ).find('#bodypadding').val( "" );
    for(var i=0; i<a.length; i++)
      if( $(that).css('padding' + a[i]) != null && $(that).css('padding' + a[i]) != "none" && $(that).css('padding' + a[i]) != "")  {
         parent.$( "#dialog-pagebodyproperties" ).find('#bodypadding').val( $(that).css('padding' + a[i]) );
         break;
       }

    parent.$( "#dialog-pagebodyproperties" ).find('#bodymargin').val( "" );
    for(var i=0; i<a.length; i++)
      if( $(that).css('margin' + a[i]) != null &&  $(that).css('margin' + a[i]) != "none" && $(that).css('margin' + a[i]) != "")  {
         parent.$( "#dialog-pagebodyproperties" ).find('#bodymargin').val( $(that).css('margin' + a[i]) );
         break;
       }

    parent.$( "#dialog-pagebodyproperties" ).find('#bodyfontcolor').val( convertRGBToHex($(that).css('color')) );

    parent.$( "#dialog-pagebodyproperties" ).find('#bodyfontName').val( $(that).css('font-family') );
    parent.$( "#dialog-pagebodyproperties" ).find('#bodyfontSize').val( $(that).css('font-size') );

    $("a").css('color') ? parent.$( "#dialog-pagebodyproperties" ).find('#bodylinkcolor').val( convertRGBToHex( $("a").css('color') ) ) : parent.$( "#dialog-pagebodyproperties" ).find('#bodylinkcolor').val( convertRGBToHex( $("a:link").css('color') ) );

    if($("a").css('text-decoration'))
      parent.$( "#dialog-pagebodyproperties" ).find('#bodylinktextdecoration').val( $("a").css('text-decoration') );
      else
      if($("a:link").css('text-decoration'))
         parent.$( "#dialog-pagebodyproperties" ).find('#bodylinktextdecoration').val( $("a:link").css('text-decoration') );

    parent.$( "#dialog-pagebodyproperties" ).dialog( "option", "buttons", [
      {
          text: html_entity_decode(rsApply),
          click: function() {
                               parent.$(this).dialog("close");

                               // set values
                               $(that).css('background-color', parent.$( "#dialog-pagebodyproperties" ).find('#bodybgcolor').val());
                               $(that).css('padding', parent.$( "#dialog-pagebodyproperties" ).find('#bodypadding').val());
                               $(that).css('margin', parent.$( "#dialog-pagebodyproperties" ).find('#bodymargin').val());
                               $(that).css('color', parent.$( "#dialog-pagebodyproperties" ).find('#bodyfontcolor').val());
                               $(that).css('font-family', parent.$( "#dialog-pagebodyproperties" ).find('#bodyfontName').val());
                               $(that).css('font-size', parent.$( "#dialog-pagebodyproperties" ).find('#bodyfontSize').val());
                               $("a").css('color', parent.$( "#dialog-pagebodyproperties" ).find('#bodylinkcolor').val());
                               $("a").css('text-decoration', parent.$( "#dialog-pagebodyproperties" ).find('#bodylinktextdecoration').val());
                               $("a:link").css('color', parent.$( "#dialog-pagebodyproperties" ).find('#bodylinkcolor').val());
                               $("a:link").css('text-decoration', parent.$( "#dialog-pagebodyproperties" ).find('#bodylinktextdecoration').val());
                               //


                             		parent.Modified = true;
                            }
      },

      {
          text: rsCancel,
          click: function() {
                               parent.$(this).dialog("close");
                            }
      }

    ] );

}

function RemoveDoubleSpacesInSingleLine(value){
  if(value == null) return "";
  return value.replace(/\n/g, ' ').replace(/\t/g, '').replace(/ +/g, ' ');
}

// http://aktuell.de.selfhtml.org/artikel/javascript/utf8b64/utf8.htm
function utf8_encode(rohtext) {
   // dient der Normalisierung des Zeilenumbruchs
   rohtext = rohtext.replace(/\r\n/g,"\n");
   var utftext = "";
   for(var n=0; n<rohtext.length; n++)
       {
       // ermitteln des Unicodes des  aktuellen Zeichens
       var c=rohtext.charCodeAt(n);
       // alle Zeichen von 0-127 => 1byte
       if (c<128)
           utftext += String.fromCharCode(c);
       // alle Zeichen von 127 bis 2047 => 2byte
       else if((c>127) && (c<2048)) {
           utftext += String.fromCharCode((c>>6)|192);
           utftext += String.fromCharCode((c&63)|128);}
       // alle Zeichen von 2048 bis 66536 => 3byte
       else {
           utftext += String.fromCharCode((c>>12)|224);
           utftext += String.fromCharCode(((c>>6)&63)|128);
           utftext += String.fromCharCode((c&63)|128);}
       }
   return utftext;
}


function utf8_decode(utftext) {
   var plaintext = ""; var i=0; var c=c1=c2=0;
   // while-Schleife, weil einige Zeichen uebersprungen werden
   while(i<utftext.length)
       {
       c = utftext.charCodeAt(i);
       if (c<128) {
           plaintext += String.fromCharCode(c);
           i++;}
       else if((c>191) && (c<224)) {
           c2 = utftext.charCodeAt(i+1);
           plaintext += String.fromCharCode(((c&31)<<6) | (c2&63));
           i+=2;}
       else {
           c2 = utftext.charCodeAt(i+1); c3 = utftext.charCodeAt(i+2);
           plaintext += String.fromCharCode(((c&15)<<12) | ((c2&63)<<6) | (c3&63));
           i+=3;}
       }
   return plaintext;
}


function html_entity_decode( string ) {
 var ret, tarea = parent.document.createElement('textarea');
 tarea.innerHTML = string;
 ret = tarea.value;
 tarea = null;
 return ret;
}

function convertRGBToHex( cssStyle )
{
 if(cssStyle == null)
   return "";
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

// no trim() function?
if(typeof String.prototype.trim !== 'function') {
 String.prototype.trim = function() {
 return this.replace(/^\s+|\s+$/, '');
}
}

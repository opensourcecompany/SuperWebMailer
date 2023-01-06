/**
 *      Pixabay browser
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */


(function($) {

  var pixaybayfileinfoContent = "";
  var VK_LEFT = 37;
  var VK_UP = 38;
  var VK_RIGHT = 39;
  var VK_DOWN = 40;
  var VK_RETURN = 13;
  var VK_ESCAPE = 27;

  // ajax setup
  $(document).ajaxSend(function(event, jqxhr, settings){
    if(settings["url"].indexOf('pixabay.com') == -1)
      if( (settings["method"] == "GET" || settings["type"] == "GET" || settings["method"] == "HEAD" || settings["type"] == "HEAD") && document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME).length ){
         settings["headers"] = "X-" + SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME + ": " + document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)[0].value;
         jqxhr.setRequestHeader("X-" + SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME, document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)[0].value);
      }
  });
  $.ajaxSetup({ cache: false });

  // Options for alert, prompt, and confirm dialogues.
  $.prompt.setDefaults({
      overlayspeed: 'fast',
      show: 'fadeIn',
      opacity: 0.4,
      zIndex: 9996
  });

  var unhtmlentities = function(html){
    document.getElementById('unhtmlentities').innerHTML = html;
    return document.getElementById('unhtmlentities').innerText;
  }

  // Dimensions
  var OldpixabayWindowWidth = $(window).width();

  var setDimensions = function(){
  	var newH = $(window).height() - $('#uploaderForm').height() - 30;
   $('#splitter, #filetree, #fileinfo, .vsplitbar').height(newH);
   // stackoverflow possible on same values in splitter
   if(OldpixabayWindowWidth != $(window).width()){
     OldpixabayWindowWidth = $(window).width();
     $("#splitter").trigger("resize");
   }
  }
  // Dimensions /

  var populateCategoryTree = function(path, callback){
  	var result = '';
   var cap_classes = '';

   $('#TotalHits').val(0);
   $('#CurrentPage').val(1);
   $('#PagesCount').val(1);;

   var url = "pixabaycategories/ajax_getpixabaycategories.php?language=" + culture;

  	$.getJSON(url, function(aresult){

    	result += "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
     for(i=0; i<aresult.length; i++){

      x = aresult[i].split(';');

      if(x[3] == "")
        x[3] = "q=";

      result += "<li class=\"pixabay\"><a href=\"#\" class=\"" + cap_classes + "\" rel=\"" + x[2] + '#&' +  x[3] + "\">" + x[0] + "</a></li>";

     }

     result += "</ul>";

     callback(result);

  	});

  }

  var preg_replace = function(pattern, replacement, str)  {
  	 var new_str = String (str);

    pattern = pattern.substr(1);
    var _flags = pattern.substr(pattern.lastIndexOf('/') + 1);
    pattern = pattern.substr(0, pattern.lastIndexOf('/'));

    var flags = "g";
    if(_flags.indexOf('i') > -1)
      flags += "i";

    var reg_exp = RegExp(pattern, flags);
    new_str = new_str.replace (reg_exp, replacement);

  		return new_str;
  }

  var str_ireplace = function(find, replacement, content){
    find = "/" + find + "/gi";
    return content.replace(find, replacement);
  }

  // security
  var RemoveJavaScript = function(contents){
    // remove all scripts
    contents = preg_replace('/<script(.*?)>(.*?)<\/script>/i', '', contents);
    contents = preg_replace('/<object(.*?)>(.*?)<\/object>/i', '', contents);
    contents = preg_replace('/<embed(.*?)>(.*?)<\/embed>/i', '', contents);

    contents = preg_replace('/<iframe(.*?)>(.*?)<\/iframe>/i', '', contents);
    contents = preg_replace('/<frameset(.*?)>(.*?)<\/frameset>/i', '', contents);
    contents = preg_replace('/<frame(.*?)>(.*?)<\/frame>/i', '', contents);
    contents = preg_replace('/<video(.*?)>(.*?)<\/video>/i', '', contents);
    contents = preg_replace('/<svg(.*?)>(.*?)<\/svg>/i', '', contents);

    contents = str_ireplace("<script", "", contents);
    contents = str_ireplace("<object", "", contents);
    contents = str_ireplace("<embed", "", contents);
    contents = str_ireplace("<iframe", "", contents);
    contents = str_ireplace("<frameset", "", contents);
    contents = str_ireplace("<frame", "", contents);
    contents = str_ireplace("<video", "", contents);
    contents = str_ireplace("<svg", "", contents);

    contents = str_ireplace("javascript:", "", contents);

    contents = preg_replace('/(.*?)on\w+="[^"\']*"/ig', '', contents);

    return contents;
  }

  var loadImages = function(params, findText){
    var category = params.substr(0, params.indexOf('#&'));
    var q = params.substr(params.indexOf('#&') + 2);
    var cap_classes = 'pixabayitem';

    $('#fileinfo').addClass("hourglass");
    $('#fileinfo').html('<img id="activity" src="images/wait30trans.gif" width="30" height="30" />');


    if(typeof pixaybaykey === "undefined" || pixaybaykey == "")
       pixaybaykey = "10700068-2a45c388e01e4dcd37d6b505b";

    var url = 'https://pixabay.com/api/?key=' + pixaybaykey;
    //var url = 'sample_json.php?key=' + pixaybaykey;


    if (category != '' && category.toLowerCase() != 'category=')
        url += '&' + category;

    if(q != '' && q.toLowerCase() != 'q=' && !findText)
       url += '&' + q;
       else
       if(findText)
         url += '&q=' + encodeURIComponent(findText);


    url += '&lang=' + culture;

    var S = 'image_type=all';

    if ($('#options_image_type') && $('#options_image_type').val() != "")
       S = 'image_type=' + $('#options_image_type').val();

    url += '&' + S;

    var S = 'orientation=all';
    if ($('#options_orientation') && $('#options_orientation').val() != "")
     S = 'orientation=' + $('#options_orientation').val();

    url += '&' + S;

    var S = 'order=popular';
    if ($('#options_order') && $('#options_order').val() != "")
     S = 'order=' + $('#options_order').val();

    url += '&' + S;

    var S = "";

    $('#options_colorsScrollbox .pixabay_option').each(function() {
       if($(this).prop("checked") || $(this).attr("checked"))
         if(S == "")
           S = 'colors=' + $(this).val();
           else
           S += ',' + $(this).val();
    });

    if (S != '')
       url += '&' + S;

    if($('#CurrentPage').val() < 1)
      $('#CurrentPage').val(1);

    url += '&' + 'page=' + $('#CurrentPage').val()

    url += '&' + 'per_page=' + pixabayImagesPerPage;

   	$.getJSON(url, function(data){

      $('#TotalHits').val(parseInt(data.totalHits));

      $('#PagesCount').val(Math.ceil(parseInt(data.totalHits) / pixabayImagesPerPage));

      if(parseInt($('#TotalHits').val()) > 0){
        var html = '<input type="hidden" id="findparams" value="' + params + '" />';

        html += '<div id="result_images" class="flex-images">';
        for(var i=0; i < data.hits.length; i++){
          var hit = data.hits[i];
          for (var name in hit) {
            hit[name] = RemoveJavaScript(hit[name]);
          }
          html += '<div class="item" data-w="' + hit.previewWidth + '" data-h="' + hit.previewHeight + '">';
          html += '<a href="#" title="' + unhtmlentities(hit.tags) + '" rel="' + encodeURIComponent(JSON.stringify(hit)) + '" class="' + cap_classes + '">';
          html += '<img src="images/wait30trans.gif" data-src="' + hit.previewURL + '" alt="' + unhtmlentities(hit.tags) + '">';
          html += '</div>';

        };
        html += '</div>';

        $('#fileinfo').html(html);

        if( parseInt($('#PagesCount').val()) > 1){
           var PagingStatus = lg.PagingStatus.replace('%s', parseInt($('#TotalHits').val())).replace('%p1', parseInt($('#CurrentPage').val())).replace('%p2', parseInt($('#PagesCount').val()));

           $( "#fileinfo" ).append( '<div id="navigationtoolbar"><button id="PreviousBtn"><span>' +  lg.PrevPage + '</span></button>&nbsp;<button id="NextBtn"><span>' +  lg.NextPage + '</span></button><div style="margin-top: 16px;">' + PagingStatus + '</div></div>' );
           if( parseInt($('#CurrentPage').val()) > 1)
             $('#PreviousBtn').show();
             else
             $('#PreviousBtn').hide();
           if( parseInt($('#CurrentPage').val()) < parseInt($('#PagesCount').val()) )
             $('#NextBtn').show();
             else
             $('#NextBtn').hide();
        }

        $('#result_images').flexImages({ truncate: 0 });
      }

      $('#fileinfo .pixabayitem').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).off();
        var find_params = $('#findparams').attr('value');
        var path = $(this).attr('rel');
    				getFileInfo(decodeURIComponent(path), find_params, findText);
  			})

      $('#PreviousBtn').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        $('#PreviousBtn').off();
        $('#CurrentPage').val( parseInt($('#CurrentPage').val()) - 1);
        loadImages(params, findText);
   			})

      $('#NextBtn').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        $('#NextBtn').off();
        $('#CurrentPage').val( parseInt($('#CurrentPage').val()) + 1);
        loadImages(params, findText);
   			})

      $('#GoFirstPage').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        $('#GoFirstPage').off();
        $('#CurrentPage').val(1);
        loadImages(params, findText);
   			})

      $('#GoLastPage').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        $('#GoLastPage').off();
        $('#CurrentPage').val( parseInt($('#PagesCount').val()) );
        loadImages(params, findText);
   			})

      if(q == '' || q.toLowerCase() == 'q=')
        $('#FindText').show();
        else
        $('#FindText').hide();

      if(parseInt($('#TotalHits').val()) == 0){
        $('#fileinfo').html('<div style="position: relative; top: 32pt; font-size: 16px">' + lg.nothingfound + '</div>');
      }

    })
     .fail(function() {
       $('#TotalHits').val(0);
       $('#PagesCount').val(0);
       $('#CurrentPage').val(1);
       if(parseInt($('#TotalHits').val()) == 0){
         $('#fileinfo').html('<div style="position: relative; top: 32pt; font-size: 16px">' + lg.nothingfound + '</div>');
       }
      })
     .error(function() {
       $('#TotalHits').val(0);
       $('#PagesCount').val(0);
       $('#CurrentPage').val(1);
       if(parseInt($('#TotalHits').val()) == 0){
         $('#fileinfo').html('<div style="position: relative; top: 32pt; font-size: 16px">' + lg.nothingfound + '</div>');
       }
      });

    $('#FindText').keyup(function(e){
       if(e.which == 13 && $('#FindText').val().trim() != ""){
         e.stopPropagation();
         e.preventDefault($(this));
         $('#FindText').off();
         loadImages(params, $('#FindText').val());
         $('#FindText').val("");
         $('#FindText').trigger("blur");
       }
    });

    $('#options_ok_btn').click(function(e){
       $('#options_ok_btn').off();
       savedpixabay_options = null;
       $('#options_close-btn').click();
       $('#CurrentPage').val(1);
       loadImages(params, findText);
    });

    $('#fileinfo').removeClass("hourglass");
  }

  // Converts bytes to kb, mb, or gb as needed for display.
  var formatBytes = function(bytes){
  	var n = parseFloat(bytes);
  	var d = parseFloat(1024);
  	var c = 0;
  	var u = ["byte","kb","mb","gb"];

  	while(true){
  		if(n < d){
  			n = Math.round(n * 100) / 100;
  			return n + u[c];
  		} else {
  			n /= d;
  			c += 1;
  		}
  	}
  }

  var getFileInfo = function(fileJSON, find_params, findText){
    file = JSON.parse(fileJSON);
    if(!file) return;

    if( !document.getElementById('toolbar') ) {
        try {
            window.stop();
        } catch (exception) {
            document.execCommand('Stop');
        }
        $("#fileinfo img").removeAttr("src").removeAttr("data-src").css('display', 'none'); //M.B. stop loading images to speed up file selection
    }

    var insertString = function (aString, aInsertString, findString){
        var position = aString.indexOf(findString);
        if (position !== -1){
         return aString.substr(0, position) + aInsertString + aString.substr(position);
        }
        return aString;
    }

    $('#optionsBtn').hide();
    $('#FindText').hide();

    // debug
    //file.webformatURL = "https://www.supermailer.de/images/supermailer.png";

    file.webformatURL = file.webformatURL;
    file.resized = false; // is in original size

   	// Include the template.

   	var template = '<div id="preview"><h1><img src="images/wait30trans.gif" /></h1><dl></dl><div id="preview_container"><div id="preview_block"><img src="' + file.webformatURL + '" width="' + file.webformatWidth + '" height="' + file.webformatHeight + '" id="previewImage" /></div></div></div>';

    var resizetoolbar = '<span id="resizetoolbar"><button id="saveResizedImage" type="button">' + lg.ok + '</button><button id="cancelResizingImage" type="button">' + lg.cancel + '</button></span>';

   	var toolbar = '<form id="toolbar" method="post">';
   	if(window.opener != null) toolbar += '<button id="select" name="select" type="button" value="Select">' + lg.select + '</button>';
   	toolbar += '<button id="download" name="download" type="button" value="Download">' + lg.download + '</button>';
   	toolbar += '<button id="resample" name="resample" type="button" value="Resample">' + lg.resizeimage + '</button>';
  	 toolbar += '<button id="parentfolder">' + lg.backtolist + '</button>';
    toolbar += '<input	name="' + SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME + '" type="hidden" value="' + document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)[0].value  + '" />';
   	toolbar += '</form>';

    template = insertString(template, resizetoolbar, '<div id="preview_block"');
    template = insertString(template, toolbar, '<div id="preview_container"');

   	$('#fileinfo').html(template);

   	$('#parentfolder').click(function(e) {
     $('#optionsBtn').show();
     loadImages(find_params, findText);
     e.stopPropagation();
     return false;
    });

    $('#fileinfo').find('h1').text('pixabay Id ' + file.id);

    var properties = '';
    properties += '<dt>' + lg.dimensions + '</dt><dd id="dimensions">' + file.webformatWidth + 'x' + file.webformatHeight + '&nbsp;' + lg.pixel + '</dd>';
//    properties += '<dt>' + lg.size + '</dt><dd id="dd_filesize">' + formatBytes(file.imageSize) + '</dd>';
    properties += '<dt>' + lg.likes + '</dt><dd>' + file.likes + '</dd>';
    properties += '<dt>' + lg.downloads + '</dt><dd>' + file.downloads + '</dd>';
    properties += '<dt>' + lg.tags + '</dt><dd>' + file.tags + '</dd>';

  		$('#fileinfo').find('dl').html(properties);

  		$('#download').click(function(e){
     e.stopPropagation();
     var w = (window.screen.width * 80 / 100);
     var h = (window.screen.height * 70 / 100);
     oWindow = window.open(file.webformatURL, "_blank", "width=" + w + ",height=" + h + ",scrollbars=yes,status=yes,toolbar=yes,resizable=yes,location=yes,dependent=yes,modal=no");
     oWindow.opener = window;
     oWindow.focus();
     return false;
  		});


  	 // this little bit is purely cosmetic
   	$('#fileinfo').find('button').wrapInner('<span></span>');

   	$('#resample').click(function(){
  	 	resampleItem(file, find_params);
   	});

    bindToolbar(file, find_params);
    bindResizeToolbar(file, find_params);
    if( (parseInt($('#reqWidth').val()) > 0 || parseInt($('#reqHeight').val()) > 0) && file.webformatWidth != parseInt($('#reqWidth').val()))
      $('#resample').trigger("click");
  }

  var bindToolbar = function(file, find_params){
   	$('#select').click(function(){
  	  selectItem(file, find_params);
    }).show();
  }

  if(typeof Math.trunc !== 'function') {
     Math.trunc = function(x) {
    if (isNaN(x)) {
      return NaN;
    }
    if (x > 0) {
      return Math.floor(x);
    }
    return Math.ceil(x);
     }
  }

  var bindResizeToolbar = function(file, find_params){

    $( "#preview_block" ).dblclick(function() {

      if($( "#resizetoolbar" ).css( "visibility") != "visible"){

        $('#current_width').val( file.webformatWidth );
        $('#current_height').val( file.webformatHeight );

        $("#preview_block").width( file.webformatWidth );
        $("#preview_block").height( file.webformatHeight );

        $("#previewImage").addClass("dottedBorder");

        $( "#resizetoolbar" ).css( "visibility", "visible");
        $( "#toolbar" ).css( "visibility", "hidden");

        // create own handlers
        var resizeHandles = '<div id="resizerHandlers">';
        //resizeHandles += '<div class="ui-resizable-handle ui-resizable-nw" id="nwgrip"></div>';
        //resizeHandles += '<div class="ui-resizable-handle ui-resizable-ne" id="negrip"></div>';
        //resizeHandles += '<div class="ui-resizable-handle ui-resizable-sw" id="swgrip"></div>';
        resizeHandles += '<div class="ui-resizable-handle ui-resizable-se" id="segrip"></div>';
        //resizeHandles += '<div class="ui-resizable-handle ui-resizable-n" id="ngrip"></div>';
        resizeHandles += '<div class="ui-resizable-handle ui-resizable-s" id="sgrip"></div>';
        resizeHandles += '<div class="ui-resizable-handle ui-resizable-e" id="egrip"></div>';
        //resizeHandles += '<div class="ui-resizable-handle ui-resizable-w" id="wgrip"></div>';
        resizeHandles += '</div>';

        // insert it when not exists
        if(!document.getElementById("resizerHandlers"))
          $( "#preview_block > img" ).after( resizeHandles );

        // insert input fields
        $("#dimensions").html('<input id="newwidth" type="number" size="3" min="10" style="text-align: right;width: 86px;" step="10" />&nbsp;x&nbsp;<input id="newheight" type="number" size="3" min="10" style="width: 86px; text-align: right;" step="10" />&nbsp;' + lg.pixel + '<button type="button" id="resize_locked" value="1"><span class="btn_locked">&nbsp;</span></button>');

        $('#resize_locked').attr("title", unhtmlentities(lg.lockimagedim));

        $('#newwidth').val( file.webformatWidth );
        $('#newheight').val( file.webformatHeight );

        $('#resize_locked').click(function(e){
         e.stopPropagation();
         e.preventDefault();
         if($(this).val() == 0)
           $(this).val(1);
           else
           $(this).val(0);

         $(this).find('span').removeClass('btn_locked');
         $(this).find('span').removeClass('btn_unlocked');
         if($(this).val() == 0) {
           $(this).find('span').addClass('btn_unlocked');
           $( "#preview_block" ).myresizable( "option", "aspectRatio", false );
         } else {
           $(this).find('span').addClass('btn_locked');
           $('#newwidth').trigger('change');
           $( "#preview_block" ).myresizable( "option", "aspectRatio", true );
         }
         return false;
        });

        var setCorrectImageSize = function(){
          $("#previewImage").width(Math.trunc(parseInt($('#newwidth').val())));
          $("#previewImage").height(Math.trunc(parseInt($('#newheight').val())));
        }

        $('#newwidth').on('keyup keypress blur change input', function(e){

          $( "#preview_block" ).myresizable('option', 'x', parseInt($(this).val()));
          if($('#resize_locked').val() == 0){
             setCorrectImageSize();
             return true;
           }

          var v = parseInt($('#current_width').val()) / parseInt($('#current_height').val());
          if(v == 0) {
             v = 10;
             $(this).val(v);
          }
          $('#newheight').val( Math.round($(this).val() / v) );
          $( "#preview_block" ).myresizable('option', 'y', parseInt($('#newheight').val()));

          setCorrectImageSize();
        });

        $('#newheight').on('keyup keypress blur change input', function(e){

          $( "#preview_block" ).myresizable('option', 'y', parseInt($(this).val()));

          if($('#resize_locked').val() == 0){
             setCorrectImageSize();
             return true;
           }
          var v = parseInt($('#current_width').val()) / parseInt($('#current_height').val());
          if(v == 0) {
             v = 10;
             $(this).val(v);
          }
          $('#newwidth').val( Math.round($(this).val() * v) );

          $( "#preview_block" ).myresizable('option', 'x', parseInt($('#newwidth').val()));

          setCorrectImageSize();
        });

        $( window ).on('keydown', function(e){
          try{
            var focusedElementId = $(document.activeElement).id || $(':focus')[0].id;
          }catch(e){
            var focusedElementId = "";
          }

          if('newwidth' ==  focusedElementId || 'newheight' == focusedElementId)
            return;
          switch (e.which){
            case VK_ESCAPE:
                 $("#cancelResizingImage").trigger("click");
                 break;
            case VK_RETURN:
                 $("#saveResizedImage").trigger("click");
                 break;
            case VK_LEFT:
                 $('#newwidth').val(  parseInt($('#newwidth').val()) - 1);
                 $('#newwidth').trigger("change");
                 break;
            case VK_RIGHT:
                 $('#newwidth').val(  parseInt($('#newwidth').val()) + 1);
                 $('#newwidth').trigger("change");
                 break;
            case VK_DOWN:
                 $('#newheight').val(  parseInt($('#newheight').val()) + 1);
                 $('#newheight').trigger("change");
                 break;
            case VK_UP:
                 $('#newheight').val(  parseInt($('#newheight').val()) - 1);
                 $('#newheight').trigger("change");
                 break;
          }
        });

        // shown own handlers when hidden
        $("#resizerHandlers").show();

        $(this).myresizable({
     	 		aspectRatio: true,
         minHeight: 10,
         minWidth: 10,

         handles: {
                       // 'nw': '#nwgrip',
                       // 'ne': '#negrip',
                       // 'sw': '#swgrip',
                        'se': '#segrip',
                       // 'n': '#ngrip',
                        'e': '#egrip',
                        's': '#sgrip',
                        //'w': '#wgrip'
                    },

         resize: function( event, ui ) {
           $("#previewImage").width(Math.trunc(ui.size.width));
           $("#previewImage").height(Math.trunc(ui.size.height));

           $('#newwidth').val( Math.trunc(ui.size.width) );
           $('#newheight').val( Math.trunc(ui.size.height) );
         }

     		 });

      }else{
        $("#saveResizedImage").trigger("click");
      }

      // set reqWidth / reqHeight
      if(parseInt($('#reqWidth').val()) > 0 || parseInt($('#reqHeight').val()) > 0){
        if(parseInt($('#reqWidth').val()) > 0){
          $('#newwidth').val( parseInt($('#reqWidth').val()) );
          $('#newwidth').trigger("change");
          }
          else
          if(parseInt($('#reqHeight').val()) > 0){
            $('#newheight').val( parseInt($('#reqHeight').val()) );
            $('#newheight').trigger("change");
          }
      }
      // set reqWidth / reqHeight /

    });

    $("#saveResizedImage").click(function() {
        var current_width = parseInt($('#current_width').val());
        var current_height = parseInt($('#current_height').val());
        var newwidth = $("#previewImage").width() + 2 /* border 1x2 */;
        var newheight = $("#previewImage").height() + 2  /* border 1x2 */;

      		if( (current_width != newwidth) || (current_height != newheight)  ){
          $("#cancelResizingImage").trigger("click");

          $('#current_width').val(newwidth);
          $('#current_height').val(newheight);

          file.webformatWidth = newwidth;
          file.webformatHeight = newheight;
          file.resized = true;

          $("#previewImage").attr("width", file.webformatWidth);
          $("#previewImage").attr("height", file.webformatHeight);

          $('#dd_filesize').html('-');

          $("#dimensions").html( file.webformatWidth+ 'x' + file.webformatHeight + '&nbsp;' + lg.pixel );

      		}else{
          $("#cancelResizingImage").trigger("click");
        }


    });

    $("#cancelResizingImage").click(function() {
        $("#preview_block").resizable( "destroy" );
        $("#resizerHandlers").hide(); // hide own handlers
        $( window ).off('keydown');

        $("#previewImage").removeClass("dottedBorder");
        $("#resizetoolbar").css( "visibility", "hidden");
        $("#toolbar").css( "visibility", "visible");
        $("#previewImage").attr("style", null);
        $("#preview_block").attr("style", null);

        $("#previewImage").attr("width", file.webformatWidth);
        $("#previewImage").attr("height", file.webformatHeight);

        $("#dimensions").html( file.webformatWidth+ 'x' + file.webformatHeight + '&nbsp;' + lg.pixel );

    });
  }

  var selectItem = function(file, find_params){

    if(!document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME).length){
      $.prompt(lg.uploadfailed_no_CSRF_Token_Found);
      return;
    }

    var data = {url: file.webformatURL, width: file.webformatWidth, height: file.webformatHeight, resized: file.resized, id: file.id};
    // IE11 doesn't support [SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME] in object directly
    data[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME] = document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)[0].value;

    $.ajax({
      url: uploaderURL,
      type: 'POST',
      data: data,
      beforeSend: function(data) {
       pixaybayfileinfoContent = $('#fileinfo').html();
      	$('#fileinfo').html('<div style="position: relative; top: 32pt; font-size: 16px">' + $('#uploaderForm #powered_by_pixaybay').html()  + '<br/><br/>' + '<img id="activity" src="images/wait30trans.gif" width="30" height="30" />' + '</div>');
       return true;
      },
      success: function(data) {
        if(data.indexOf("OK:") != -1){
          var filename = data.substr(data.indexOf("OK:") + 3);

          if(currentActiveCKEditor){
            try {
              if( $.urlParam("destUrlfield") == ""){
                currentActiveCKEditor.focus();
                currentActiveCKEditor.fire( 'saveSnapshot' );
                currentActiveCKEditor.insertHtml('<img src="' + filename + '">');
                currentActiveCKEditor.fire( 'saveSnapshot' );
                currentActiveCKEditor.fire("blur");
              }else{
                var _CKEDITOR = window.opener.CKEDITOR || window.parent.CKEDITOR;
                var urlfield = $.urlParam("destUrlfield").split('-');
                _CKEDITOR.dialog.getCurrent().setValueOf(urlfield[0], urlfield[1], filename);
              }
              $('#closeWindow').trigger("click");
            } catch(e) {
              $.prompt(lg.ckeditorinserterror + ' ' + e.message);
            }
          } else{
            if(currentIPEEditorField){
              var _parent = window.opener || window.parent;
              pixabayPasteText(filename, currentIPEEditorField);
              _parent.SetUrl(filename);
              $('#closeWindow').trigger("click");
            }
          }
        }
      },
      error: function(jqXHR) {
       $.prompt(lg.uploadfailed + jqXHR.status + ' ' + jqXHR.statusText);
       if(pixaybayfileinfoContent != ""){
         $('#fileinfo').html(pixaybayfileinfoContent);
         pixaybayfileinfoContent = "";
       }
      },
      fail: function(jqXHR) {
       $.prompt(lg.uploadfailed + jqXHR.status + ' ' + jqXHR.statusText);
       if(pixaybayfileinfoContent != ""){
         $('#fileinfo').html(pixaybayfileinfoContent);
         pixaybayfileinfoContent = "";
       }
      },
      complete: function() {
        bindToolbar(file, find_params);
        if(pixaybayfileinfoContent != ""){
          $('#fileinfo').html(pixaybayfileinfoContent);
          pixaybayfileinfoContent = "";
        }
      }
    });
  }

  var resampleItem = function(file, find_params){
    $( "#preview_block" ).trigger("dblclick");
  }


  /*---------------------------------------------------------
    Initialization
  ---------------------------------------------------------*/


  $(function(){

   // function to retrieve GET params
   $.urlParam = function(name){
   	var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
   	if (results)
   		return results[1];
   	else
   		return 0;
   }

   if($.urlParam('langCode'))
     culture = $.urlParam('langCode');
     else
     if($.urlParam('language'))
       culture = $.urlParam('language');
   LoadLocalization(culture);
  });

  function LoadLocalization(_culture){
   var reload = false; // JQuery compatibility
   $.getJSON('scripts/languages/'  + _culture + '.js', function(aresult){
      lg = aresult;
      Initialization();
  	})
    .fail(function() {
        if(_culture == "en" || _culture == "de"){
          document.open();
          document.write("Unknown language</body></html>");
          document.close();
          try {
              window.stop();
          } catch (exception) {
              document.execCommand('Stop');
          }
        }else{
          culture = "de";
          LoadLocalization(culture)
          reload = true;
          return;
        }
     })
    .error(function() {
        if(!reload){
         if(_culture == "en" || _culture == "de"){
           document.open();
           document.write("Unknown language</body></html>");
           document.close();
           try {
               window.stop();
           } catch (exception) {
               document.execCommand('Stop');
           }
         }else{
           culture = "de";
           LoadLocalization(culture)
           return;
         }
        }
     });
  }

  function Initialization(){

  	// Provides support for adjustible columns.
  	$('#splitter').splitter({
  		sizeLeft: 300
  	});

   // Adjust layout.
  	setDimensions();
  	$(window).resize(setDimensions);

  	$('#closeWindow').attr('title', unhtmlentities(lg.closeWindow));
   $('#options_close-btn').attr('title', unhtmlentities(lg.closeWindow));

   $('#uploaderForm h1').text(lg.pixabayslogan);

   $('#BackBtnWarnText').html(lg.BackBtnWarnText);

  	// cosmetic tweak for buttons
  	$('button').wrapInner('<span></span>');

  // $('#closeWindow span').html(lg.closeWindow);

   $('#optionsBtn span').html(lg.optionsBtn);

   $('#PixabayOptionsDlgLabel').html(lg.pixabayoptions);
   $('#options_ok_btn').html(lg.ok);
   $('#options_cancel_btn').html(lg.cancel);

   $("#options_order_label").html(lg.options_order_label);
   $("#options_image_type_label").html(lg.options_image_type_label);
   $("#options_orientation_label").html(lg.options_orientation_label);
   $("#options_colorsScrollbox_label").html(lg.options_colorsScrollbox_label);

   $("#options_orderpopular").html(lg.options_orderpopular);
   $("#options_orderlatest").html(lg.options_orderlatest);

   $("#options_image_type_all").html(lg.options_image_type_all);
   $("#options_image_type_photo").html(lg.options_image_type_photo);
   $("#options_image_type_illustration").html(lg.options_image_type_illustration);
   $("#options_image_type_vector").html(lg.options_image_type_vector);

   $("#options_orientation_all").html(lg.options_orientation_all);
   $("#options_orientation_horizontal").html(lg.options_orientation_horizontal);
   $("#options_orientation_vertical").html(lg.options_orientation_vertical);

   $('#FindText').val(lg.defaultFindText);
   $('#FindText').attr("defaultText", lg.defaultFindText);

   $('#FindText').hide();

   var pixabayColorStrings = ['grayscale', 'transparent', 'red', 'orange', 'yellow', 'green', 'turquoise', 'blue', 'lilac', 'pink', 'white', 'gray', 'black', 'brown'];

   if(lg.pixabayColors){
     var temp = lg.pixabayColors.split(',');
     for(var i=0; i<temp.length && i<pixabayColorStrings.length; i++){
       lg[pixabayColorStrings[i]] = temp[i].trim();
     }
   }

   var temp = $('#options_colorsScrollbox').html();
   var html = "";

   for(var i=0; i<pixabayColorStrings.length; i++){
     var temp1 = temp;

     temp1 = temp1.replace(/options_colorsId/g, "options_" + pixabayColorStrings[i]);
     temp1 = temp1.replace(/options_colors_value/g, pixabayColorStrings[i]);
     temp1 = temp1.replace(/options_colors_name/g, lg[pixabayColorStrings[i]] ? lg[pixabayColorStrings[i]] : pixabayColorStrings[i]);

     html += temp1;
   }

   $('#options_colorsScrollbox').html(html);

   $('#optionsBtn').hide();

  	// Creates file tree = category tree.
   $('#filetree').fileTree({
     root: "/",
   		datafunc: populateCategoryTree,
   		multiFolder: false,
   		folderCallback: function(path){ // not used, no directorys
     },
   		after: function(data){
       // not used, no directorys
   		}

    }, function(params){
        var categorytext="";
        var oldcategory;
     			$('#filetree').find('li a').each(function() {

           if($(this).parent().hasClass('selected'))
             oldcategory = $(this).attr('rel');

           if($(this).attr('rel') == params){
                $(this).parent().addClass('selected');
                categorytext = $(this).text();
              }
              else
              $(this).parent().removeClass('selected');
     			});

        if(oldcategory != params){ // prevent doubleclicks
          $('#uploaderForm h1').text(lg.category + ': ' + categorytext);

          $('#optionsBtn').show();
          $('#CurrentPage').val(1);
          loadImages(params);
        }
   });

  	$('#fileinfo').html('<div style="position: relative; top: 32pt; font-size: 16px">' + $('#uploaderForm #powered_by_pixaybay').html()  + '<br/><br/>' + lg.selectcategory + '</div>');


  };

  function pixabayPasteText(aText, DestElement) {
    var input = DestElement;
    input.focus();
    /* für Internet Explorer */
    if(typeof parent.document.selection != 'undefined') {
      /* Einfügen des Formatierungscodes */
      var range = parent.document.selection.createRange();
      var insText = range.text;
      range.text = insText + aText;
      /* Anpassen der Cursorposition */
      range = parent.document.selection.createRange();
      if (insText.length == 0) {
        range.move('character', -aText.length);
      } else {
        range.moveStart('character', insText.length + aText.length);
      }
      range.select();
    }
    /* für neuere auf Gecko basierende Browser */
    else if(typeof input.selectionStart != 'undefined')
    {
      /* Einfügen des Formatierungscodes */
      var start = input.selectionStart;
      var end = input.selectionEnd;
      var insText = input.value.substring(start, end);
      input.value = input.value.substr(0, start) + insText + aText + input.value.substr(end);
      /* Anpassen der Cursorposition */
      var pos;
      if (insText.length == 0) {
        pos = start + aText.length;
      } else {
        pos = start + aText.length + insText.length;
      }
      input.selectionStart = pos;
      input.selectionEnd = pos;
    }
    /* für die übrigen Browser */
    else
    {
    }
  }

})(jQuery);

jQuery.uaMatch = function( ua ) {
	ua = ua.toLowerCase();

	var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
		/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
		/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
		/(msie) ([\w.]+)/.exec( ua ) ||
		ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
		[];

	return {
		browser: match[ 1 ] || "",
		version: match[ 2 ] || "0"
	};
};

// Don't clobber any existing jQuery.browser in case it's different
// compatibility for newer jquery versions
if ( !jQuery.browser ) {
	matched = jQuery.uaMatch( navigator.userAgent );
	browser = {};

	if ( matched.browser ) {
		browser[ matched.browser ] = true;
		browser.version = matched.version;
	}

	// Chrome is Webkit, but Webkit is also Safari.
	if ( browser.chrome ) {
		browser.webkit = true;
	} else if ( browser.webkit ) {
		browser.safari = true;
	}

	jQuery.browser = browser;
}

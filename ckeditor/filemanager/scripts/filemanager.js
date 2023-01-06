/**
 *	Filemanager JS core
 *
 *	filemanager.js
 *
 *	@license	MIT License
 *	@author		Jason Huck - Core Five Labs
 *	@author		Simon Georget <simon (at) linea21 (dot) com>
 *	@copyright	Authors
 *
 * @many changes and bugfixes 2011-2021 mirko boeer, leipzig
 */

(function($) {

var VK_LEFT = 37;
var VK_UP = 38;
var VK_RIGHT = 39;
var VK_DOWN = 40;
var VK_RETURN = 13;
var VK_ESCAPE = 27;

// function to retrieve GET params
$.urlParam = function(name){
	var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results)
		return results[1]; 
	else
		return 0;
}

/*---------------------------------------------------------
  Setup, Layout, and Status Functions
---------------------------------------------------------*/

var SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME = 'smlswmFMCsrfToken';

if(typeof lang === "undefined")
  lang = "php";

// Sets paths to connectors based on language selection.
var fileConnector = 'connectors/' + lang + '/filemanager.' + lang;
var fmtoken = "";
if(document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME).length)
  fmtoken = "&" + SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME + "=" + document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)[0].value;

var capabilities = new Array('select', 'download', 'rename', 'delete', 'resample', 'reload');

// ajax setup
$(document).ajaxSend(function(event, jqxhr, settings){
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

// Forces columns to fill the layout vertically.
// Called on initial page load and on resize.
var OldfmWindowWidth = $(window).width();
var setDimensions = function(){
	var newH = $(window).height() - $('#uploaderForm').height() - 30;
	$('#splitter, #filetree, #fileinfo, .vsplitbar').height(newH);
 if(OldfmWindowWidth != $(window).width()){
   $('#splitter, #filetree, #fileinfo, .vsplitbar').height(newH);
   OldfmWindowWidth = $(window).width();
   $("#splitter").trigger("resize");
 }
}

// Display Min Path
var displayRoot = function(path){
	if(showFullPath == false)
		return path.replace(fileRoot, "/");
	else 
		return path;
}

// preg_replace
// Code from : http://xuxu.fr/2006/05/20/preg-replace-javascript/
var preg_replace = function(array_pattern, array_pattern_replace, str)  {
	var new_str = String (str);
		for (i=0; i<array_pattern.length; i++) {
			var reg_exp= RegExp(array_pattern[i], "g");
			var val_to_replace = array_pattern_replace[i];
			new_str = new_str.replace (reg_exp, val_to_replace);
		}
		return new_str;
	}

// cleanString (), on the same model as server side (connector)
// cleanString
var cleanString = function(str) {
 // M.B.
 str = str.replace(" ", "_");
 str = str.replace("-", "_");
 var s = "";
 for($i=0; $i<str.length; $i++) {
    if (
        (str.charCodeAt($i) >= 0x30 && str.charCodeAt($i) <= 0x39) ||
        (str.charCodeAt($i) >= 0x41 && str.charCodeAt($i) <= 0x5A) ||
        (str.charCodeAt($i) >= 0x61 && str.charCodeAt($i) <= 0x7A) ||
        (str.charCodeAt($i) == 0x5F) ||
        (str.charAt($i) == '.') || (str.charAt($i) == '[') || (str.charAt($i) == ']')
       ) {
         s += str.charAt($i);
       } else {
         switch(str.charCodeAt($i)) {
           case 0xC4: s += "Ae";
                 break;
           case 0xDC: s += "Ue";
                 break;
           case 0xD6: s += "Oe";
                 break;
           case 0xE4: s += "ae";
                 break;
           case 0xFC: s += "ue";
                 break;
           case 0xF6: s += "oe";
                 break;
           case 0xDF: s += "ss";
                 break;
         }
       }
 }
 str = s;
 // M.B.

	var cleaned = "";
	var p_search  = 	new Array("Š", "š", "Đ", "đ", "Ž", "ž", "Č", "č", "Ć", "ć", "À", 
						"Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", 
						"Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "Þ", "ß", 
						"à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì",  "í",  
						"î", "ï", "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ý", 
						"ý", "þ", "ÿ", "Ŕ", "ŕ", " ", "'", "/"
						);
	var p_replace = 	new Array("S", "s", "Dj", "dj", "Z", "z", "C", "c", "C", "c", "A", 
						"A", "A", "A", "A", "A", "A", "C", "E", "E", "E", "E", "I", "I", "I", "I", 
						"N", "O", "O", "O", "O", "O", "O", "U", "U", "U", "U", "Y", "B", "Ss", 
						"a", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "i", "i",
						"i", "i", "o", "n", "o", "o", "o", "o", "o", "o", "u", "u", "u", "y", 
						"y", "b", "y", "R", "r", "_", "_", ""
					);

	cleaned = preg_replace(p_search, p_replace, str);
	cleaned = cleaned.replace(/[^_a-zA-Z0-9\[\]]/g, "");
	cleaned = cleaned.replace(/[_]+/g, "_");
	
	return cleaned;
}



// Handle Error. Freeze interactive buttons and display
// error message. Also called when auth() function return false (Code == "-1")
var handleError = function(errMsg) {
	$('#fileinfo').html('<h1>' + errMsg+ '</h1>');
	$('#newfile').attr("disabled", "disabled");
	$('#upload').attr("disabled", "disabled");
	$('#uploadsBtn').attr("disabled", "disabled");
	$('#newfolder').attr("disabled", "disabled");
}

// Test if Data structure has the 'cap' capability
// 'cap' is one of 'select', 'rename', 'delete', 'download', 'resample'
function has_capability(data, cap) {
	if (data['File Type'] == 'dir' && cap == 'download') return false;
	if (data['File Type'] == 'dir' && cap == 'select') return false; // M.B.
	if (data['File Type'] == 'dir' && cap == 'rename') return false; // M.B.
	if (data['File Type'] == 'dir' && cap == 'resample') return false; // M.B.
	if (data['File Type'] == 'dir' && cap == 'reload') return false; // M.B.
	if (typeof(data['Capabilities']) == "undefined") return true;
	else return $.inArray(cap, data['Capabilities']) > -1;
}

// from http://phpjs.org/functions/basename:360
var basename = function(path, suffix) {
    var b = path.replace(/^.*[\/\\]/g, '');

    if (typeof(suffix) == 'string' && b.substr(b.length-suffix.length) == suffix) {
        b = b.substr(0, b.length-suffix.length);
    }
    
    return b;
}

// Sets the folder status, upload, and new folder functions 
// to the path specified. Called on initial page load and 
// whenever a new directory is selected.
var setUploader = function(path){
	$('#currentpath').val(path);
	$('#uploaderForm h1').text(lg.current_folder + displayRoot(path));

	$('#newfolder').unbind().click(function(){
		var foldername =  lg.default_foldername;
		var msg = lg.prompt_foldername + ':<br /><input id="fname" name="fname" type="text" value="' + foldername + '" style="min-width: 300px" />';
		
		var getFolderName = function(v, m){
			if(v != 1) return false;		
			var fname = m.children('#fname').val();		

			if(fname != ''){
				foldername = fname;

				$.getJSON(fileConnector + '?mode=addfolder&path=' + $('#currentpath').val() + '&name=' + cleanString(foldername), function(result){
					if(result['Code'] == 0){
						addFolder(result['Parent'], result['Name']);
						getFolderInfo(result['Parent']);
					} else {
						$.prompt(result['Error']);
					}				
				});
			} else {
				$.prompt(lg.no_foldername);
			}
		}
		var btns = {}; 
		btns[lg.create_folder] = true; 
		btns[lg.cancel] = false; 
		$.prompt(msg, {
			callback: getFolderName,
			buttons: btns 
		});	
	});	
}

// Binds specific actions to the toolbar in detail views.
// Called when detail views are loaded.
var bindToolbar = function(data){
	// this little bit is purely cosmetic
	$('#fileinfo').find('button').wrapInner('<span></span>');

	if (!has_capability(data, 'select')) {
		$('#fileinfo').find('button#select').hide();
	} else {
 		$('#fileinfo').find('button#select').click(function(){
			selectItem(data);
		}).show();
	}
	
	if (!has_capability(data, 'resample')) {
		$('#fileinfo').find('button#resample').hide();
	} else {
		$('#fileinfo').find('button#resample').click(function(){
			resampleItem(data);
		}).show();
	}

	if (!has_capability(data, 'rename')) {
		$('#fileinfo').find('button#rename').hide();
	} else {
		$('#fileinfo').find('button#rename').click(function(){
			var newName = renameItem(data);
			if(newName.length) $('#fileinfo > h1').text(newName);
		}).show();
	}

	if (!has_capability(data, 'delete')) {
		$('#fileinfo').find('button#delete').hide();
	} else {
		$('#fileinfo').find('button#delete').click(function(){
			if(deleteItem(data)) $('#fileinfo').html('<h1>' + lg.select_from_left + '</h1>');
		}).show();
	}

	if (!has_capability(data, 'download')) {
		$('#fileinfo').find('button#download').hide();
	} else {
		$('#fileinfo').find('button#download').click(function(){
			window.location = fileConnector + '?mode=download&path=' + data['Path'] + fmtoken;
		}).show();
	}
}

// Converts bytes to kb, mb, or gb as needed for display.
var formatBytes = function(bytes){
	var n = parseFloat(bytes);
	var d = parseFloat(1024);
	var c = 0;
	var u = [lg.bytes,lg.kb,lg.mb,lg.gb];
	
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


/*---------------------------------------------------------
  Item Actions
---------------------------------------------------------*/

// Calls the SetUrl function for FCKEditor compatibility,
// passes file path, dimensions, and alt text back to the
// opening window. Triggered by clicking the "Select" 
// button in detail views or choosing the "Select"
// contextual menu option in list views. 
// NOTE: closes the window when finished.
var selectItem = function(data){
  // M.B. FCKEDitor / CKEditor style filenames -> absolute to relative paths
  if(data['Path'].indexOf(userfilesRoot) > -1) {
    data['Path'] = data['Path'].substr(data['Path'].indexOf(userfilesRoot));
    }
    else{
     data['Path'] = userfilesRoot + data['Path'].substr( fileRoot.length );
    }
  // M.B. /

	if(window.opener){
	 	if(window.tinyMCEPopup){
        	// use TinyMCE > 3.0 integration method
            var win = tinyMCEPopup.getWindowArg("window");
			win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = data['Path'];
            if (typeof(win.ImageDialog) != "undefined") {
				// Update image dimensions
            	if (win.ImageDialog.getImageData)
                 	win.ImageDialog.getImageData();

                // Preview if necessary
                if (win.ImageDialog.showPreviewImage)
					win.ImageDialog.showPreviewImage(data['Path']);
			}
			tinyMCEPopup.close();
			return;
		}
		if($.urlParam('CKEditor')){
			// use CKEditor 3.0 integration method
			window.opener.CKEDITOR.tools.callFunction($.urlParam('CKEditorFuncNum'), data['Path']);
		} else {
			// use FCKEditor 2.0 integration method
			if(data['Properties']['Width'] != ''){
				var p = data['Path'];
				var w = data['Properties']['Width'];
				var h = data['Properties']['Height'];			
				window.opener.SetUrl(p,w,h);
			} else {
				window.opener.SetUrl(data['Path']);
			}		
		}

		window.close();
	} else {
		$.prompt(lg.fck_select_integration);
	}
}

// Renames the current item and returns the new name.
// Called by clicking the "Rename" button in detail views
// or choosing the "Rename" contextual menu option in
// list views.
var renameItem = function(data){
	var finalName = '';

 // M.B. without extension
 filename = data['Filename'];
	if(filename.lastIndexOf(".") != -1) {
   filename = filename.substr(0, filename.lastIndexOf("."));
 }
 // M.B.

	var msg = lg.new_filename + ':<br /><input id="rname" name="rname" type="text" style="min-width: 300px" value="' + filename + '" />';

	var getNewName = function(v, m){
		if(v != 1) return false;
		rname = m.children('#rname').val();

		if(rname != ''){

   // remove bad chars in filename!!
	  rname = cleanString(rname);

   // M.B. add extension
  	ext = "";
  	if(data['Filename'].lastIndexOf(".") != -1)
      ext = data['Filename'].substr(data['Filename'].lastIndexOf("."));
   if(ext != "")
      rname = rname + ext;
   // M.B.


			var givenName = rname;
			var oldPath = data['Path'];
			var connectString = fileConnector + '?mode=rename&old=' + data['Path'] + '&new=' + givenName;

			$.ajax({
				type: 'GET',
				url: connectString,
				dataType: 'json',
				async: false,
				success: function(result){
					if(result['Code'] == 0){
						var newPath = result['New Path'];
						var newName = result['New Name'];

						updateNode(oldPath, newPath, newName);

						var title = $("#preview h1").attr("title");

						if (typeof title !="undefined" && title == oldPath) {
							$('#preview h1').text(newName);
						}

						if($('#fileinfo').data('view') == 'grid'){
							$('#fileinfo img[alt="' + oldPath + '"]').parent().next('p').text(newName);
							$('#fileinfo img[alt="' + oldPath + '"]').attr('alt', newPath);
						} else {
							$('#fileinfo td[title="' + oldPath + '"]').text(newName);
							$('#fileinfo td[title="' + oldPath + '"]').attr('title', newPath);
						}

						// M.B. $.prompt(lg.successful_rename);
					} else {
						$.prompt(result['Error']);
					}

					finalName = result['New Name'];
				}
			});
		}
	}
	var btns = {};
	btns[lg.rename] = true;
	btns[lg.cancel] = false;
	$.prompt(msg, {
		callback: getNewName,
		buttons: btns
	});

	return finalName;
}

// Prompts for confirmation, then deletes the current item.
// Called by clicking the "Delete" button in detail views
// or choosing the "Delete contextual menu item in list views.
var deleteItem = function(data){
	var isDeleted = false;
	var msg = lg.confirmation_delete;
	// M.B.
	msg = msg.replace("%s", data['Filename']);
	
	var doDelete = function(v, m){
		if(v != 1) return false;	
		var connectString = fileConnector + '?mode=delete&path=' + data['Path'];
	
		$.ajax({
			type: 'GET',
			url: connectString,
			dataType: 'json',
			async: false,
			success: function(result){
				if(result['Code'] == 0){
					removeNode(result['Path']);
					isDeleted = true;
					// M.B. $.prompt(lg.successful_delete);
				} else {
					isDeleted = false;
					$.prompt(result['Error']);
				}			
			}
		});	
	}
	var btns = {}; 
	btns[lg.yes] = true; 
	btns[lg.no] = false; 
	$.prompt(msg, {
		callback: doDelete,
		buttons: btns 
	});
	
	return isDeleted;
}


/*---------------------------------------------------------
  Functions to Update the File Tree
---------------------------------------------------------*/

// Adds a new node as the first item beneath the specified
// parent node. Called after a successful file upload.
var addNode = function(path, name, filetype){
	var ext = name.substr(name.lastIndexOf('.') + 1);
 if(path != fileRoot){
   var thisNode = $('#filetree').find('a[rel="' + path + '"]');
  	var parentNode = thisNode.parent();
 } else {
   var thisNode  = null;
 }

 // M.B. files ever without /
	var Sep = "";
 if(filetype == "dir")
   Sep = "/"; // should never "dir"
 // M.B.

 // M.B.

  if(thisNode != null){
    // sub folder
    if(!parentNode.find('ul').size()) parentNode.append('<ul></ul>');
  }

  var cap_classes = "cap_select cap_download cap_rename cap_delete cap_resample";
  var newNode = "<li class=\"file ext_" + ext.toLowerCase() + "\"><a href=\"#\" class=\"" + cap_classes + "\" rel=\"" + path + name + "\">" + name + "</a></li>";

  if(thisNode == null) // sub folder?
    $('#filetree > ul').append(newNode);
    else
    parentNode.find('ul').append(newNode);

		$('#filetree').find('li a[rel="' + path + name + Sep + '"]').click(function(e){
				//getFileInfo(path + name);
    fileTreefileCallback(path + name, true);
    e.stopPropagation();
    return false;
			}).each(function() {
    $(this).destroyContextMenu();
    $(this).contextMenu(
					{ menu: getContextMenuOptions($(this)) },
					function(action, el, pos){
						var path = $(el).attr('rel');
						setMenus(action, path);
					});
				}
			);
 // M.B.

	getFolderInfo(path);

	// M.B. $.prompt(lg.successful_added_file);

}

// Updates the specified node with a new name. Called after
// a successful rename operation.
var updateNode = function(oldPath, newPath, newName){
	var thisNode = $('#filetree').find('a[rel="' + oldPath + '"]');
	var parentNode = thisNode.parent().parent().prev('a');
	thisNode.attr('rel', newPath).text(newName);
	parentNode.click().click();
}

// Removes the specified node. Called after a successful 
// delete operation.
var removeNode = function(path){
    $('#filetree')
        .find('a[rel="' + path + '"]')
        .parent()
        .fadeOut('slow', function(){ 
            $(this).remove();

            if(path.lastIndexOf('/') == path.length - 1){
              var new_path = path.substr(0, path.length - 1);
              new_path = new_path.substr(0, new_path.lastIndexOf('/') + 1);
              if(new_path == fileRoot){
                  $("#home").trigger("click");
                  return;
                }
                else{
                  if($('#filetree').find('a[rel="' + new_path + '"]').length){
                     $('#filetree').find('a[rel="' + new_path + '"]').trigger("click");
                     return;
                  }
                }
            }
        })
    // grid case
    if($('#fileinfo').data('view') == 'grid'){
        $('#contents img[alt="' + path + '"]').parent().parent()
            .fadeOut('slow', function(){ 
                $(this).remove();
        });
    }
    // list case
    else {
        $('table#contents')
            .find('td[title="' + path + '"]')
            .parent()
            .fadeOut('slow', function(){ 
                $(this).remove();
        });
    }
    // when item is currently selected than go home
    if ($('#preview').length) {
       $("#home").trigger("click");
	   }
}


// Adds a new folder as the first item beneath the
// specified parent node. Called after a new folder is
// successfully created.
var addFolder = function(parent, name){
 // M.B. for folders class="cap_delete" addeded
// M.B. <ul class="jqueryFileTree" style="display: block;"></ul> entfernt
 var newNode = '<li class="directory collapsed"><a class="cap_delete" rel="' + parent + name + '/" href="#">' + name + '</a></li>';
	var parentNode = $('#filetree').find('a[rel="' + parent + '"]');
	if(parent != fileRoot){
		parentNode.next('ul').prepend(newNode).prev('a').trigger("click").trigger("click");
	} else {

		$('#filetree > ul').prepend(newNode);

  $('#filetree').find('li a[rel="' + parent + name + '/"]').click(function(e){
    fileTreefolderCallback(parent + name + '/', true);
    //getFolderInfo(parent + name + '/');
    e.stopPropagation();
    return false;
			}).each(function() {
    $(this).destroyContextMenu();
    $(this).contextMenu(
					{ menu: getContextMenuOptions($(this)) },
					function(action, el, pos){
						var path = $(el).attr('rel');
						setMenus(action, path);
					});
				}
			);

	}
	
// M.B.	$.prompt(lg.successful_added_folder);
}


/*---------------------------------------------------------
  Functions to Retrieve File and Folder Details
---------------------------------------------------------*/

// Decides whether to retrieve file or folder info based on
// the path provided.
var getDetailView = function(path){
	if(path.lastIndexOf('/') == path.length - 1){
  if( $('#filetree').find('a[rel="' + path + '"]').length == 0 )
     getFolderInfo(path);
  $('#filetree').find('a[rel="' + path + '"]').trigger("click");
	} else {
		getFileInfo(path, parseInt($('#reqWidth').val()) > 0 || parseInt($('#reqHeight').val()) > 0, parseInt($('#reqWidth').val()), parseInt($('#reqHeight').val()) );
	}
}

function getContextMenuOptions(elem) {
 var optionsID = elem.attr('class').replace(/ /g, '_');
	if (optionsID == "") return 'itemOptions';
	if (!($('#' + optionsID).length)) {
		// Create a clone to itemOptions with menus specific to this element
		var newOptions = $('#itemOptions').clone().attr('id', optionsID);

		if (!elem.hasClass('cap_select')) $('.select', newOptions).remove();
		if (!elem.hasClass('cap_download')) $('.download', newOptions).remove();
		if (!elem.hasClass('cap_rename')) $('.rename', newOptions).remove();
		if (!elem.hasClass('cap_delete')) $('.delete', newOptions).remove();
		if (!elem.hasClass('cap_resample')) $('.resample', newOptions).remove();
		if ( !elem.hasClass('cap_reload') || ($('#fileinfo').data('view') != 'grid') ) $('.reload', newOptions).remove();
		$('#itemOptions').after(newOptions);
	}
	return optionsID;
}

// Binds contextual menus to items in list and grid views.
var setMenus = function(action, path){
	$.getJSON(fileConnector + '?mode=getinfo&path=' + path, function(data){
		if($('#fileinfo').data('view') == 'grid'){
			var item = $('#fileinfo').find('img[alt="' + data['Path'] + '"]').parent();
		} else {
			var item = $('#fileinfo').find('td[title="' + data['Path'] + '"]').parent();
		}
	
		switch(action){
			case 'select':
    if(data['File Type'] != "dir" && data['File Type'] != "" && has_capability(data, "select")) // no directories M.B.
      selectItem(data);
				break;
			
			case 'download':
				window.location = fileConnector + '?mode=download&path=' + data['Path'] + fmtoken;
				break;
				
			case 'rename':
    if(data['File Type'] != "dir" && data['File Type'] != "") // no directories M.B.
   				var newName = renameItem(data);
				break;
				
			case 'resample':
    if(data['File Type'] != "dir" && data['File Type'] != "") // no directories M.B.
   				resampleItem(data);
				break;

			case 'delete':
				// TODO: When selected, the file is deleted and the
				// file tree is updated, but the grid/list view is not.
				if(deleteItem(data)) item.fadeOut('slow', function(){ $(this).remove(); });
				break;

			case 'reload':
    if(data['File Type'] != "dir" && data['File Type'] != "" && has_capability(data, "reload")){ // no directories M.B.
      var src = $('#fileinfo').find('img[alt="' + data['Path'] + '"]').attr("src");
      src += (src.indexOf('?') > -1 ? '&_' : '?_') + new Date().getTime();
      $('#fileinfo').find('img[alt="' + data['Path'] + '"]').attr("src", src);
    }
				break;

		}
	});
}

var resampleItem = function(data){
  if( !document.getElementById('preview_block') ) {
    getFileInfo(data['Path'], true, 0, 0);
    return;
  }
  $( "#preview_block" ).trigger("dblclick");
}

// Retrieves information about the specified file as a JSON
// object and uses that data to populate a template for
// detail views. Binds the toolbar for that detail view to
// enable specific actions. Called whenever an item is
// clicked in the file tree or list views.
var getFileInfo = function(file, resampleIt, reqWidth, reqHeight){
 $('#fileinfo').off("unveil");
 if( !document.getElementById('toolbar') ) {
     try {
         window.stop();
     } catch (exception) {
         document.execCommand('Stop');
     }
     $("#fileinfo img").removeAttr("src").css('display', 'none'); //M.B. stop loading images to speed up file selection
 }

 var insertString = function (aString, aInsertString, findString){
     var position = aString.indexOf(findString);
     if (position !== -1){
      return aString.substr(0, position) + aInsertString + aString.substr(position);
     }
     return aString;
 }

	// Update location for status, upload, & new folder functions.
	var currentpath = file.substr(0, file.lastIndexOf('/') + 1);
	setUploader(currentpath);

	// Include the template.
	var template = '<div id="preview"><h1><img src="images/wait30trans.gif" /></h1><dl></dl><div id="preview_container"><div id="preview_block"><img id="previewImage" class="loading" onload="$(this).removeClass(\'loading\');" /></div></div></div>';

 var resizetoolbar = '<span id="resizetoolbar"><button id="saveResizedImage" type="button">' + lg.ok + '</button><button id="cancelResizingImage" type="button">' + lg.cancel + '</button></span>';

	var toolbar = '<form id="toolbar" method="post">';
	if(window.opener != null) toolbar += '<button id="select" name="select" type="button" value="Select">' + lg.select + '</button>';
	toolbar += '<button id="download" name="download" type="button" value="Download">' + lg.download + '</button>';
	if(browseOnly != true) toolbar += '<button id="resample" name="resample" type="button" value="Resample">' + lg.resizeimage + '</button>';
	if(browseOnly != true) toolbar += '<button id="rename" name="rename" type="button" value="Rename">' + lg.rename + '</button>';
	if(browseOnly != true) toolbar += '<button id="delete" name="delete" type="button" value="Delete">' + lg.del + '</button>';
	toolbar += '<button id="parentfolder">' + lg.parentfolder + '</button>';
 toolbar += '<input	name="' + SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME + '" type="hidden" value="' + document.getElementsByName(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)[0].value  + '" />';
	toolbar += '</form>';
	
 template = insertString(template, resizetoolbar, '<div id="preview_block"');
 template = insertString(template, toolbar, '<div id="preview_container"');

	$('#fileinfo').html(template);
	$('#parentfolder').click(function(e) {getFolderInfo(currentpath); e.stopPropagation(); return false;});

 $('#toolbar > button').attr('disabled', "disabled");
 $('#fileinfo').addClass("hourglass");

	// Retrieve the data & populate the template.
	$.getJSON(fileConnector + '?mode=getinfo&path=' + file + '&notoken=1', function(data){
		if(data['Code'] == 0){
			$('#fileinfo').find('h1').text(lg.fileName + ' ' + data['Filename']).attr('title', file);
   var nocache = "&_=" + new Date().getTime() / 1000;
   var src = data['Preview'];
   src += (src.indexOf('?') > -1 ? '&_' : '?_') + new Date().getTime();
   $('#fileinfo').find('img').attr('src', src);
			
			var properties = '';
			
			if(data['Properties']['Width'] && data['Properties']['Width'] != '') properties += '<dt>' + lg.dimensions + '</dt><dd id="dimensions">' + data['Properties']['Width'] + 'x' + data['Properties']['Height'] + '&nbsp;' + lg.pixel + '</dd>';
			if(data['Properties']['Date Created'] && data['Properties']['Date Created'] != '') properties += '<dt>' + lg.created + '</dt><dd>' + data['Properties']['Date Created'] + '</dd>';
			if(data['Properties']['Date Modified'] && data['Properties']['Date Modified'] != '') properties += '<dt>' + lg.modified + '</dt><dd>' + data['Properties']['Date Modified'] + '</dd>';
			if(data['Properties']['Size'] && data['Properties']['Size'] != '') properties += '<dt>' + lg.size + '</dt><dd>' + formatBytes(data['Properties']['Size']) + '</dd>';
			
			$('#fileinfo').find('dl').html(properties);

   if(data['Properties']['Width'] && data['Properties']['Width'] != '' && resampleIt && reqWidth > 0 && reqWidth == parseInt(data['Properties']['Width']))
     resampleIt = false;

			// Bind toolbar functions.
			bindToolbar(data);

   bindResizeToolbar(data);

   $('#toolbar > button').removeAttr('disabled');
   $('#fileinfo').removeClass("hourglass");

   if(resampleIt){
      if( document.getElementById('preview_block') ) {
        $( "#preview_block" ).trigger("dblclick");
      }
   }

		} else {
	 		$.prompt(data['Error'] + ' no file.');
    $('#toolbar > button').removeAttr('disabled');
    $('#toolbar > button').hide();
    $('#parentfolder').show();
    $('#fileinfo').removeClass("hourglass");
		}
	})
 .fail(function(jqXHR) {
   if($('#fileinfo').hasClass("hourglass")){
     $.prompt( unhtmlentities(lg.webserver_error) +  ' ' + jqXHR.status + ' ' + jqXHR.statusText );
     $('#toolbar > button').removeAttr('disabled');
     $('#toolbar > button').hide();
     $('#parentfolder').show();
     $('#fileinfo').removeClass("hourglass");
   }
  })
 .error(function(jqXHR) {
   if($('#fileinfo').hasClass("hourglass")){
     $.prompt( unhtmlentities(lg.webserver_error) + ' ' + jqXHR.status + ' ' + jqXHR.statusText );
     $('#toolbar > button').removeAttr('disabled');
     $('#toolbar > button').hide();
     $('#parentfolder').show();
     $('#fileinfo').removeClass("hourglass");
   }
  });
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

var bindResizeToolbar = function(data){

  $( "#preview_block" ).dblclick(function() {

    if($( "#resizetoolbar" ).css( "visibility") != "visible"){

      $('#current_width').val(data['Properties']['Width']);
      $('#current_height').val(data['Properties']['Height']);

      $("#preview_block").width(data['Properties']['Width']);
      $("#preview_block").height(data['Properties']['Height']);

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

      $('#newwidth').val(data['Properties']['Width']);
      $('#newheight').val(data['Properties']['Height']);

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
      var newwidth = $("#previewImage").width();
      var newheight = $("#previewImage").height();

    		if( (current_width != newwidth) || (current_height != newheight)  ){

        // border 1
        if (current_width != newwidth){
          newwidth += 2;
        }

        if (current_height != newheight){
          newheight += 2;
        }

      			var connectString = fileConnector + '?mode=resample&filename=' + data['Path'] + '&current_width=' + current_width + '&current_height=' + current_height + '&newwidth=' + newwidth + '&newheight=' + newheight;

      			$.ajax({
      				type: 'GET',
      				url: connectString,
      				dataType: 'json',
      				async: false,
      				success: function(result){
      					if(result['Code'] == 0){
              getFileInfo(data['Path'], false, 0, 0);
              return;
      					} else {
      						$.prompt(result['Error']);
            $("#cancelResizingImage").trigger("click");
      					}
      				}
      			});
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

      $("#dimensions").html( $('#current_width').val() + 'x' + $('#current_height').val() + '&nbsp;' + lg.pixel );

  });
}

// Retrieves data for all items within the given folder and
// creates a list view. Binds contextual menu options.
// TODO: consider stylesheet switching to switch between grid
// and list views with sorting options.
var getFolderInfo = function(path){

 $('#fileinfo').addClass("hourglass");
 // Update location for status, upload, & new folder functions.
	setUploader(path);

	// Display an activity indicator.
	$('#fileinfo').html('<img id="activity" src="images/wait30trans.gif" width="30" height="30" />');

	// Retrieve the data and generate the markup.
	var url = fileConnector + '?path=' + path + '&mode=getfolder&showThumbs=' + showThumbs;
	if ($.urlParam('type')) url += '&type=' + $.urlParam('type'); else url += '&type=Images'; // M.B.
	$.getJSON(url, function(data){
		var result = '';
		
		// Is there any error or user is unauthorized?
		if(data.Code=='-1') {
			handleError(data.Error);
   $('#fileinfo').removeClass("hourglass");
			return;
		};
		
		if(data){
   var found = false;
			if($('#fileinfo').data('view') == 'grid'){
				result += '<ul id="contents" class="grid">';
				
				for(key in data){
					var props = data[key]['Properties'];
					var cap_classes = "";
					for (cap in capabilities) {
						if (has_capability(data[key], capabilities[cap])) {
							cap_classes += " cap_" + capabilities[cap];
						}
						// M.B. only delete for dirs
						if(data[key]['File Type'] == "dir" ){
						  cap_classes = "cap_delete";
       }
      // M.B.
					}
				
					var scaledWidth = 64;
					var actualWidth = props['Width'];
					if(actualWidth > 1 && actualWidth < scaledWidth) scaledWidth = actualWidth;
				
					result += '<li class="' + cap_classes + '"><div class="clip"><img src="images/wait30trans.gif" data-src="' + data[key]['Preview'] + '" width="' + scaledWidth + '" alt="' + data[key]['Path'] + '" /></div><p>' + data[key]['Filename'] + '</p>';
					if(props['Width'] && props['Width'] != '') result += '<span class="meta dimensions">' + props['Width'] + 'x' + props['Height'] + '</span>';
					if(props['Size'] && props['Size'] != '') result += '<span class="meta size">' + props['Size'] + '</span>';
					if(props['Date Created'] && props['Date Created'] != '') result += '<span class="meta created">' + props['Date Created'] + '</span>';
					if(props['Date Modified'] && props['Date Modified'] != '') result += '<span class="meta modified">' + props['Date Modified'] + '</span>';
					result += '</li>';
     found = true;
				}
				
				result += '</ul>';
			} else {
				result += '<table id="contents" class="list">';
				result += '<thead><tr><th class="headerSortDown"><span>' + lg.name + '</span></th><th><span>' + lg.dimensions + '</span></th><th><span>' + lg.size + '</span></th><th><span>' + lg.modified + '</span></th></tr></thead>';
				result += '<tbody>';
				
				for(key in data){
					var path = data[key]['Path'];
					var props = data[key]['Properties'];
     var cap_classes = "";
					for (cap in capabilities) {
						if (has_capability(data[key], capabilities[cap])) {
							cap_classes += " cap_" + capabilities[cap];
						}
						// M.B. only delete for dirs
						if(data[key]['File Type'] == "dir" ){
						  cap_classes = "cap_delete";
       }
      // M.B.
					}
					result += '<tr class="' + cap_classes + '">';
					result += '<td title="' + path + '">' + data[key]['Filename'] + '</td>';

					if(props['Width'] && props['Width'] != ''){
						result += ('<td>' + props['Width'] + 'x' + props['Height'] + '</td>');
					} else {
						result += '<td></td>';
					}
					
					if(props['Size'] && props['Size'] != ''){
						result += '<td><abbr title="' + props['Size'] + '">' + formatBytes(props['Size']) + '</abbr></td>';
					} else {
						result += '<td></td>';
					}
					
					if(props['Date Modified'] && props['Date Modified'] != ''){
						result += '<td>' + props['Date Modified'] + '</td>';
					} else {
						result += '<td></td>';
					}
				
					result += '</tr>';
     found = true;
				}
								
				result += '</tbody>';
				result += '</table>';
			}
   if(!found)
     result += '<h1>' + lg.noelementsfound + '</h1>';
		} else {
			result += '<h1>' + lg.could_not_retrieve_folder + '</h1>';
		}
		
		// Add the new markup to the DOM.
		$('#fileinfo').html(result);
  $('#fileinfo').removeClass("hourglass");

  var unveil_callback = function() {
    $(this).load(function() {
      $(this).removeClass('loading');
      $(this).attr("data-src", null);
      $(this).prop("data-src", null);
    });
    $(this).error(function() {
       var src = $(this).attr("data-src");
       if( !src || src.indexOf('?_') > -1 || src.indexOf('&_') > -1 ){
         // we gave up reloading image
         $(this).attr("data-src", null);
         $(this).prop("data-src", null);
         return;
       }
       // reload this image again
       src += (src.indexOf('?') > -1 ? '&_' : '?_') + new Date().getTime();
       $(this).attr("data-src", null);
       $(this).prop("data-src", null);

       setTimeout(function(img){
                              if(!img) return; // IE9-
                              img.attr("src", src);
                              img.prop("src", src);
                              //console.log("reload image " + src  );
                              // reload it dircectly $(this).unveil(40, unveil_callback, $('#fileinfo'));
                            }, 200 + Math.floor(Math.random() * 101), $(this));

    });
  }
  $("#fileinfo img").unveil(0, unveil_callback, $('#fileinfo'));

		// Bind click events to create detail views and add
		// contextual menu options.
		if($('#fileinfo').data('view') == 'grid'){
			$('#fileinfo').find('#contents li').click(function(){
				var path = $(this).find('img').attr('alt');
				getDetailView(path);
			}).each(function() {
				$(this).contextMenu(
					{ menu: getContextMenuOptions($(this)) },
					function(action, el, pos){
						var path = $(el).find('img').attr('alt');
						setMenus(action, path);
					}
				);
			});
		} else {
			$('#fileinfo').find('td:first-child').each(function(){
				var path = $(this).attr('title');
				var treenode = $('#filetree').find('a[rel="' + path + '"]').parent();
				$(this).css('background-image', treenode.css('background-image'));
			});
			
			$('#fileinfo tbody tr').click(function(){
				var path = $('td:first-child', this).attr('title');
				getDetailView(path);		
			}).each(function() {
				$(this).contextMenu(
					{ menu: getContextMenuOptions($(this)) },
					function(action, el, pos){
						var path = $('td:first-child', el).attr('title');
						setMenus(action, path);
					}
				);
			});
			
			$('#fileinfo').find('table').tablesorter({
				textExtraction: function(node){					
					if($(node).find('abbr').size()){
						return $(node).find('abbr').attr('title');
					} else {					
						return node.innerHTML;
					}
				}
			});
   $('#fileinfo').removeClass("hourglass");
		}
	});
}

// Retrieve data (file/folder listing) for jqueryFileTree and pass the data back
// to the callback function in jqueryFileTree
var populateFileTree = function(path, callback){
	var url = fileConnector + '?path=' + path + '&mode=getfolder&showThumbs=' + showThumbs;
	if ($.urlParam('type')) url += '&type=' + $.urlParam('type'); else url += '&type=Images'; // M.B.
	$.getJSON(url, function(data) {
		var result = '';
		// Is there any error or user is unauthorized?
		if(data.Code=='-1') {
			handleError(data.Error);
			return;
		};
		
		if(data) {
			result += "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
			for(key in data) {
				cap_classes = "";
				for (cap in capabilities) {
					if (has_capability(data[key], capabilities[cap])) {

      if(capabilities[cap] !== "reload") // on filetree no reload
        cap_classes += " cap_" + capabilities[cap];
					}

					// M.B. only delete for dirs
					if(data[key]['File Type'] == "dir" ){
					  cap_classes = "cap_delete";
      }
     // M.B.

				}
				if (data[key]['File Type'] == 'dir') {
					result += "<li class=\"directory collapsed\"><a href=\"#\" class=\"" + cap_classes + "\" rel=\"" + data[key]['Path'] + "\">" + data[key]['Filename'] + "</a></li>";
				} else {
					result += "<li class=\"file ext_" + data[key]['File Type'].toLowerCase() + "\"><a href=\"#\" class=\"" + cap_classes + "\" rel=\"" + data[key]['Path'] + "\">" + data[key]['Filename'] + "</a></li>";
				}
			}
			result += "</ul>";
		} else {
			result += '<h1>' + lg.could_not_retrieve_folder + '</h1>';
		}
		callback(result);
	});
}


/*---------------------------------------------------------
  Initialization
---------------------------------------------------------*/


$(function(){
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

// for current tree and new folders
var fileTreefolderCallback = function(path, is_new){
    var dontLoadFolderContent = 0;
    $('#filetree').find('li a').each(function() {

       if($(this).attr('rel') == path){
            $(this).addClass('selected');
            if( $(this).hasClass('dontLoadFolderContent') ){  // cosmetics fix for Home button
              $(this).removeClass('dontLoadFolderContent');
              dontLoadFolderContent = 1;
            }
            if(is_new && $(this).parent().hasClass('directory')){
              $(this).parent().addClass('expanded');
              $(this).parent().removeClass('collapsed');
            }
          }
          else{
            $(this).removeClass('selected');
            if( $(this).hasClass('dontLoadFolderContent') ){ // cosmetics fix for Home button
              $(this).removeClass('dontLoadFolderContent');
              dontLoadFolderContent = 1;
            }
            if(is_new && $(this).parent().hasClass('directory')){
              $(this).parent().removeClass('expanded');
              $(this).parent().addClass('collapsed');
            }
          }
 			});

    if(!dontLoadFolderContent)
      getFolderInfo(path);
}

// for current tree and new files
function fileTreefileCallback(file, is_new){
 			$('#filetree').find('li a').each(function() {

       if($(this).attr('rel') == file){
            $(this).parent().addClass('selected');
            // bugfix: close open directories in filetree on same level as selected file
            $(this).parent().prevAll().each(function() {
               if($(this).hasClass("directory") && $(this).hasClass("expanded") ){
                 $(this).removeClass("expanded");
                 $(this).addClass("collapsed");
                 $(this).find('UL').remove(); // cleanup
               }
            });
          }
          else{
            $(this).parent().removeClass('selected');
            // folders
            $(this).removeClass('selected');
          }
 			});

		getFileInfo(file, false, 0, 0);
}

function Initialization(){
	// Adjust layout.
	setDimensions();
	$(window).resize(setDimensions);

	// we finalize the FileManager UI initialization
	// with localized text if necessary
	if(autoload == true) {
  $('#uploadsBtn').append(lg.upload);
  $('#FileUploadH2Label').text(lg.FileUploadHeadline);
  $('#upload').append(lg.uploadallfiles);
  $('#MoreFile').append(lg.onefilemore);
  $('#close-btn').attr('title', lg.closeWindow);
		$('#newfolder').append(lg.new_folder);
		$('#grid').attr('title', lg.grid_view);
		$('#list').attr('title', lg.list_view);
		$('#closeWindow').attr('title', lg.closeWindow);
		$('#fileinfo h1').append(lg.select_from_left);
		$('#itemOptions a[href$="#select"]').append(lg.select);
		$('#itemOptions a[href$="#download"]').append(lg.download);
		$('#itemOptions a[href$="#resample"]').append(lg.resizeimage);
		$('#itemOptions a[href$="#rename"]').append(lg.rename);
		$('#itemOptions a[href$="#delete"]').append(lg.del);
		$('#itemOptions a[href$="#reload"]').append(lg.reload);
	}

 $('#BackBtnWarnText').html(lg.BackBtnWarnText);

	// Provides support for adjustible columns.
	$('#splitter').splitter({
		sizeLeft: 200
	});

	// cosmetic tweak for buttons
	$('button').wrapInner('<span></span>');

	// Set initial view state.
	$('#fileinfo').data('view', 'grid');

	$('#home').click(function(){

   $('#fileinfo').off("unveil");
   try {
       window.stop();
   } catch (exception) {
       document.execCommand('Stop');
   }
   $("#fileinfo img").removeAttr("src").css('display', 'none'); //M.B. stop loading images to speed up file selection

 		//$('#fileinfo').data('view', 'grid'); // force grid
   if($('#filetree>ul>li.expanded>a')[0]){  // cosmetics fix for Home button
     $('#filetree>ul>li.expanded>a').addClass("dontLoadFolderContent"); // don't call getFolderInfo() in fileTreefolderCallback and after than here again
     $('#filetree>ul>li.expanded>a').trigger('click');
   }
   $('#filetree>ul>li>a').removeClass("selected");
   $('#filetree>ul>li').removeClass("selected");
 		getFolderInfo(fileRoot);
	});

	// Set buttons to switch between grid and list views.
	$('#grid').click(function(){
		$(this).addClass('ON');
		$('#list').removeClass('ON');
		$('#fileinfo').data('view', 'grid');
		getFolderInfo($('#currentpath').val());
	});

	$('#list').click(function(){
		$(this).addClass('ON');
		$('#grid').removeClass('ON');
		$('#fileinfo').data('view', 'list');
		getFolderInfo($('#currentpath').val());
	});

	// Provide initial values for upload form, status, etc.
	setUploader(fileRoot);

	$('#uploader').attr('action', fileConnector);

	$('#uploader').ajaxForm({
		target: '#uploadresponse',
		beforeSubmit: function(arr, form, options) {
			if ($.urlParam('type').toString().toLowerCase() == 'images') {


    var AllfilesOK=true;
    var FileCount = 0;
    // Test if uploaded file extension is in valid image extensions
    $("#newfile", form).each( function(){
      if($(this).val() != "") {

    				var newfileSplitted = $(this).val().toLowerCase().split('.');

        var fileOK = false;
        for (key in imagesExt) {
    					if (imagesExt[key] == newfileSplitted[newfileSplitted.length-1]) {
    						 fileOK = true;
           break;
    					}
    				}

        if(!fileOK)
          AllfilesOK=false;
          else
          FileCount++;

      }

    } );



    if(!AllfilesOK){
      $.prompt(lg.UPLOAD_IMAGES_ONLY);
		  		return false;
    } else {
      return FileCount > 0;
    }

			}
		},
		success: function(result){
			eval('var data = ' + $('#uploadresponse').find('textarea').text());

   if(data[0]) {
     for(var i=0;i<data.length;i++) {
    			if(data[i]['Code'] == 0){
    				addNode(data[i]['Path'], data[i]['Name'], data[i]['File Type']);
        $('#close-btn').trigger("click");
    			} else {
    				$.prompt( data[i]['Name'] + ' => ' + data[i]['Error']);
    			}
     }
   } else {
    			if(data['Code'] == 0){
    				addNode(data['Path'], data['Name'], data['File Type']);
        $('#close-btn').trigger("click");
    			} else {
    				$.prompt( data['Name'] + ' => ' + data['Error']);
    			}
   }


		}
	});

	// Creates file tree.
  $('#filetree').fileTree({
		root: fileRoot,
		datafunc: populateFileTree,
		multiFolder: false,
		folderCallback: function(path){
    fileTreefolderCallback(path);
  },
		after: function(data){
			$('#filetree').find('li a').each(function() {
				$(this).contextMenu(
					{ menu: getContextMenuOptions($(this)) },
					function(action, el, pos){
						var path = $(el).attr('rel');
						setMenus(action, path);
					}
				)
			});
		}
	}, function(file){
    fileTreefileCallback(file);
	});

	// Disable select function if no window.opener
	if(window.opener == null) $('#itemOptions a[href$="#select"]').remove();
	// Keep only browseOnly features if needed
	if(browseOnly == true) {
		$('#newfile').remove();
		$('#upload').remove();
  $('#uploadsBtn').remove();
		$('#newfolder').remove();
		$('#toolbar').remove('#rename');
		$('.contextMenu .rename').remove();
		$('.contextMenu .delete').remove();
		$('.contextMenu .resample').remove();
	}
    getDetailView(fileRoot);
}

})(jQuery);

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


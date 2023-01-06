<?php
include_once("connectors/php/phpcompat.php");
include_once("connectors/php/csrf.inc.php");
// Prevent the browser from caching the result.
// Date in the past
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
// always modified
@header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
// HTTP/1.1
@header('Cache-Control: no-store, no-cache, must-revalidate') ;
@header('Cache-Control: post-check=0, pre-check=0', false) ;
// HTTP/1.0
@header('Pragma: no-cache') ;
@header('Content-Type: text/html; charset=utf-8');

if(!isset($_GET["CKEditor"]) && !isset($_GET["IPEEditor"]) ){
  print "No Editor found.";
  die;
}

if(isset($_GET["CKEditor"]) && empty($_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME])){
  print "No Editor or CSRFToken found.";
  die;
}

if(isset($_GET["IPEEditor"]) && empty($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME])){
  print "No Editor or CSRFToken found.";
  die;
}
$NewCsrfToken = fmGetCsrfToken();

// https://docs.ckeditor.com/ckeditor4/latest/api/CKEDITOR_tools.html#method-getCsrfToken
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="Mon, 1 Jul 2002 00:00:00 GMT" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<title>File Manager</title>
<link rel="stylesheet" type="text/css" href="styles/reset.css" />
<link rel="stylesheet" type="text/css"
	href="scripts/jquery.filetree/jqueryFileTree.css" />

    <style>
       .jqueryFileTree LI.selected {
       	background-color: #eee;
       }
       .jqueryFileTree A.selected {
       	background-color: #eee;
       }
    </style>

<link rel="stylesheet" type="text/css"
	href="scripts/jquery.contextmenu/jquery.contextMenu-1.01.css" />

<link href="styles/jquery.ui.resizable.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="styles/filemanager.css" />
<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="styles/ie.css" />
		<![endif]-->
</head>
<body style="-moz-user-select: -moz-none;-khtml-user-select: none;-webkit-user-select: none;user-select: none;">


<script>
<!--
  CheckForCKEditor = function(){
    var _CKEDITOR = window.opener.CKEDITOR || window.parent.CKEDITOR;
    if(_CKEDITOR)
      var token = _CKEDITOR.tools.getCsrfToken();
    if(!_CKEDITOR || token != <?php echo '"' . (isset($_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME]) ? $_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME] : "") . '"'; ?>){
      document.open();
      document.write("Access denied</body></html>");
      document.close();
      try {
          window.stop();
      } catch (exception) {
          document.execCommand('Stop');
      }
    }
  }
  CheckForIPEditor = function(){
    var _parent = window.opener || window.parent;
    if(_parent){
      var token = _parent.document.getElementsByName("<?php echo SMLSWM_TOKEN_COOKIE_NAME ?>");
      if(!token)
        token = "";
        else
        token = token[0].value;
    }
    if(!_parent || token != <?php echo '"' . (isset($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME]) ? $_COOKIE[SMLSWM_TOKEN_COOKIE_NAME] : "") . '"'; ?>){
      document.open();
      document.write("Access denied</body></html>");
      document.close();
      try {
          window.stop();
      } catch (exception) {
          document.execCommand('Stop');
      }
    }
  }

  window.onunload = function(event) {
    RemoveCookie();
  };

  function RemoveCookie(){
    var expires = new Date();
    expires.setTime(expires.getTime() - 3600);
    try {
      document.cookie = SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME + "=null; expires=" + expires.toUTCString();
    } catch(e) {}
  }

  var BackBtnWarningShowed = false;

//-->
</script>

<?php
   if(isset($_GET["CKEditor"])){
    echo "<script><!--\r\n CheckForCKEditor(); \r\n--></script>";
   }else{
    echo "<script><!--\r\n CheckForIPEditor(); \r\n--></script>";
   }

?>


<div>
<form id="uploaderForm" method="post">
<h1></h1>
<button id="home" name="home" type="button" value="Home">Start</button>
<button id="uploadsBtn" name="uploadsBtn" type="button"></button>
<button id="newfolder" name="newfolder" type="button" value="New Folder"></button>
<button id="grid" class="ON" type="button">&nbsp;</button>
<button id="list" type="button">&nbsp;</button>
<button id="closeWindow" type="button" onclick="RemoveCookie(); window.close();">&nbsp;</button>

    <?php
       if(isset($_GET["CKEditor"])){
         echo '<input	name="'.CKEDITOR_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME].'" />';
       }else{
         echo '<input	name="'.SMLSWM_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$_COOKIE[SMLSWM_TOKEN_COOKIE_NAME].'" />';
       }
       echo '<input	name="'.SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$NewCsrfToken.'" />';
    ?>

</form>
<div id="splitter">
  <div id="filetree"></div>
  <div id="fileinfo">
    <h1></h1>
  </div>
</div>

<ul id="itemOptions" class="contextMenu">
	<li class="select"><a href="#select"></a></li>
	<li class="reload"><a href="#reload"></a></li>
	<li class="download"><a href="#download"></a></li>
	<li class="resample"><a href="#resample"></a></li>
	<li class="rename"><a href="#rename"></a></li>
	<li class="delete separator"><a href="#delete"></a></li>
</ul>

<span style="clear:both;"></span>


<script type="text/javascript" src="scripts/jquery-latest.min.js"></script>
<script type="text/javascript" src="scripts/jquery.form-2.63.js"></script>
<script type="text/javascript" src="scripts/jquery.splitter/jquery.splitter-1.5.1.js"></script>
<script type="text/javascript" src="scripts/jquery.filetree/jqueryFileTree.js"></script>
<script type="text/javascript" src="scripts/jquery.contextmenu/jquery.contextMenu-1.01.js"></script>
<script type="text/javascript" src="scripts/jquery.impromptu-3.1.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter-2.0.5b.min.js"></script>
<script type="text/javascript" src="scripts/filemanager.config.php"></script>
<script type="text/javascript" src="scripts/filemanager.js"></script>

<script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="scripts/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="scripts/jquery.custom_resizable.js"></script>


<script type="text/javascript" src="scripts/jquery-modalpoplite/modalPopLite.js"></script>
<link href="scripts/jquery-modalpoplite/modalPopLite.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="scripts/jquery.unveil.js"></script>


<script language="JavaScript">
<!--
  function PopupCallback(){
    $('#newfileTemplate').children().each(function() {
      if($(this).index() > 0)
        $(this).remove();
    });
    $('#newfile').val('');
  }


 	$(document).ready(function(){

     $(function () {
         $('#FileUpload').modalPopLite({ openButton: '#uploadsBtn', closeButton: '#close-btn', isModal: true, callBack: PopupCallback });
         $('#ResampleImageDlg').modalPopLite({ openButton: '#resamplehiddenBtn', closeButton: '#resample_close-btn, #resample_cancel_btn', isModal: true, callBack: null });
     });

     $(document).find('#close-btn1').click(function(e){
       e.stopPropagation();
       e.preventDefault();
       $(document).find('#close-btn').click();
       return false;
    	});

     $(document).find('#MoreFile').off('click');
    	$(document).find('#MoreFile').click(function(e){
       $('#newfileTemplate').append( '<div>' + $('#newfileTemplate').children().html() + '</div>' );
       e.stopPropagation();
       e.preventDefault();
       $('#newfileTemplate').find('#newfile').last().click();
       return false;
   	});


 });

 window.onpopstate = function() {
    if(!BackBtnWarningShowed){
      alert(document.getElementById('BackBtnWarnText').innerText);
      BackBtnWarningShowed = true;
    }
 }; history.pushState({}, '');

//-->
</script>

<span id="BackBtnWarnText" style="display: none;"></span>
<textarea id="unhtmlentities" rows="0" cols="0" style="display: none"></textarea>
<input type="hidden" id="current_width" />
<input type="hidden" id="current_height" />
<input type="hidden" id="reqWidth" value="<?php echo (isset($_GET['reqWidth']) ? intval($_GET['reqWidth']) : -1) ?>" />
<input type="hidden" id="reqHeight" value="<?php echo (isset($_GET['reqHeight']) ? intval($_GET['reqHeight']) : -1) ?>" />


<div id="FileUpload" style="width: 520px; height: 360px; background-color: #FFFFFF; padding: 10px">
  <form id="uploader" method="post" onselectstart="return false;" style="display: inline; text-align: left;cursor: default;-moz-user-select: -moz-none;-khtml-user-select: none;-webkit-user-select: none;user-select: none;">
    <input id="mode" name="mode" type="hidden" value="add" />
    <input	id="currentpath" name="currentpath" type="hidden" />
    <div id="uploadresponse"></div>

    <?php
       if(isset($_GET["CKEditor"])){
         echo '<input	name="'.CKEDITOR_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME].'" />';
       }else{
         echo '<input	name="'.SMLSWM_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$_COOKIE[SMLSWM_TOKEN_COOKIE_NAME].'" />';
       }
       echo '<input	name="'.SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$NewCsrfToken.'" />';
    ?>

    <div>

      <div>
       <div style="float: right;"><input type="image" id="close-btn" src="images/close.png" title="Close" /></div>
       <h2 style="text-align: center" id="FileUploadH2Label"></h2>
       <br />
      </div>

      <div style="overflow-y: auto; height: 240px">
        <div id="newfileTemplate">
          <div>
           <input id="newfile" name="newfile[]" type="file" style="width: 100%" accept="image/jpg,image/jpeg,image/gif,image/png" />
          </div>
        </div>

        <div>
          <br />
          <button style="margin-left: 0px;" id="MoreFile" type="button"></button>
        </div>
      </div>

      <div>
       <br /><br />
       <button id="upload" name="upload" type="submit" value="Upload"></button>
      </div>

    </div>
  </form>
</div>

</body>
</html>
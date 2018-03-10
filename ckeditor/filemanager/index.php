<?php
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
<link rel="stylesheet" type="text/css"
	href="scripts/jquery.contextmenu/jquery.contextMenu-1.01.css" />
<link rel="stylesheet" type="text/css" href="styles/filemanager.css" />
<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="styles/ie.css" />
		<![endif]-->
</head>
<body>
<div>
<form id="uploaderForm" method="post">
<h1></h1>
<button id="home" name="home" type="button" value="Home">&nbsp;</button>
<button id="uploadsBtn" name="uploadsBtn" type="button"></button>
<button id="newfolder" name="newfolder" type="button" value="New Folder"></button>
<button id="grid" class="ON" type="button">&nbsp;</button>
<button id="list" type="button">&nbsp;</button>
<button id="closeWindow" type="button" onclick="window.close();">&nbsp;</button>
</form>
<div id="splitter">
<div id="filetree"></div>
<div id="fileinfo">
<h1></h1>
</div>
</div>

<ul id="itemOptions" class="contextMenu">
	<li class="select"><a href="#select"></a></li>
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
<script type="text/javascript" src="scripts/filemanager.js"></script></div>

<script type="text/javascript" src="scripts/jquery-modalpoplite/modalPopLite.js"></script></div>
<link href="scripts/jquery-modalpoplite/modalPopLite.css" rel="stylesheet" type="text/css" />



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

//-->
</script>
<noscript></noscript>

<div id="FileUpload" style="width: 520px; height: 360px; background-color: #FFFFFF; padding: 10px">
  <form id="uploader" method="post" onselectstart="return false;" style="display: inline; text-align: left;cursor: default;-moz-user-select: -moz-none;-khtml-user-select: none;-webkit-user-select: none;user-select: none;">
    <input id="mode" name="mode" type="hidden" value="add" />
    <input	id="currentpath" name="currentpath" type="hidden" />
    <div id="uploadresponse"></div>

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
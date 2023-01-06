<?php
/**
 *      Pixabay browser
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */
  include_once("../filemanager/connectors/php/phpcompat.php");
  include_once("../filemanager/connectors/php/csrf.inc.php");
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

?>
<!DOCTYPE html>
<html>
<head>
<title></title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <link rel="stylesheet" href="css/pixabay.css">
    <link rel="stylesheet" href="css/jquery.flex-images.css">

    <script src="scripts/jquery-latest.min.js"></script>
    <script src="scripts/jquery.flex-images.js"></script>

    <link rel="stylesheet" href="scripts/jquery.filetree/jqueryFileTree.css" />

    <style>
       .jqueryFileTree LI.pixabay {
       	background: url(images/pixabay.png) left top no-repeat;
       }

       .jqueryFileTree LI.selected {
       	background-color: #eee;
       }

    </style>

    <link rel="stylesheet" type="text/css" href="css/scrollbox.css" />
    <link href="scripts/jquery-modalpoplite/modalPopLite.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.ui.resizable.css" rel="stylesheet" type="text/css" />

</head>

<body style="-moz-user-select: -moz-none;-khtml-user-select: none;-webkit-user-select: none;user-select: none;">

<script>
<!--
  var currentActiveCKEditor = null;
  var currentIPEEditorField = null;
  CheckForCKEditor = function(){
    var _parent = window.opener || window.parent;
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
    }else{
      for(var i in _parent.CKEDITOR.instances) {
         if(i == "<?php !empty($_GET["CKEditor"]) ? $ck = $_GET["CKEditor"]: $ck = ""; echo $ck; ?>")
            return _parent.CKEDITOR.instances[i];
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
    }else{
      return _parent.document.getElementsByName("<?php echo isset($_GET["IPEEditor"]) ? $_GET["IPEEditor"] : ""; ?>")[0];
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
    echo "<script><!--\r\n currentActiveCKEditor = CheckForCKEditor(); \r\n--></script>";
   }else{
    echo "<script><!--\r\n currentIPEEditorField = CheckForIPEditor(); \r\n--></script>";
   }

?>

  <div>
    <div id="uploaderForm">
      <h1></h1>
      <input type="text" id="FindText" defaultText="" style="width: 98px;font-style: italic;" onfocus="this.style.fontStyle='normal'; if(this.value == this.getAttribute('defaultText')) this.value='';" onblur="this.style.fontStyle='italic'; if(this.value.trim() == '') this.value=this.getAttribute('defaultText'); else {var e = $.Event('keyup'); e.which = 13; $('#FindText').trigger(e)}" />
      <button type="button" id="optionsBtn"></button>
      <button type="button" id="closeWindow" onclick="RemoveCookie(); window.close();"></button>
      <span>&nbsp;|</span>
      <span style="font-size: 12px;" id="powered_by_pixaybay">powered by <a href="https://www.pixabay.com" title="pixabay Website" target="_blank"><img src="images/pixabay_logo.png" alt="pixabay Website"></a></span>

    <?php
       if(isset($_GET["CKEditor"])){
         echo '<input	name="'.CKEDITOR_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME].'" />';
       }else{
         echo '<input	name="'.SMLSWM_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$_COOKIE[SMLSWM_TOKEN_COOKIE_NAME].'" />';
       }
       echo '<input	name="'.SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME.'" type="hidden" value="'.$NewCsrfToken.'" />';
    ?>

    <input type="hidden" id="TotalHits" />
    <input type="hidden" id="CurrentPage" />
    <input type="hidden" id="PagesCount" />

    </div>

    <div id="splitter">
      <div id="filetree">
      </div>
      <div id="fileinfo">
         <h1></h1>
      </div>
    </div>


  </div>


  <script src="scripts/jquery.flex-images.js"></script>
  <script type="text/javascript" src="scripts/jquery.filetree/jqueryFileTree.js"></script>
  <script type="text/javascript" src="scripts/jquery.splitter/jquery.splitter-1.5.1.js"></script>
  <script type="text/javascript" src="scripts/jquery.impromptu-3.1.min.js"></script>
  <script type="text/javascript" src="scripts/jquery-modalpoplite/modalPopLite.js"></script></div>

  <script src="scripts/scrollbox.js"></script>
  <script src="scripts/pixaybayconfig.php"></script>
  <script src="scripts/pixabay.js"></script>

  <script type="text/javascript" src="scripts/jquery-ui.min.js"></script></div>
  <script type="text/javascript" src="scripts/jquery.ui.resizable.js"></script></div>
  <script type="text/javascript" src="scripts/jquery.custom_resizable.js"></script></div>

  <script>
     if(typeof String.prototype.trim !== 'function') {
        String.prototype.trim = function() {
           return this.replace(/^\s+|\s+$/, '');
        }
     }
     window.onpopstate = function() {
        if(!BackBtnWarningShowed){
          alert(document.getElementById('BackBtnWarnText').innerText);
          BackBtnWarningShowed = true;
        }
     }; history.pushState({}, '');


     var savedpixabay_options;

     function PixabayOptionsDlgCancelCallback(e){

       // Restore old settings
       if(savedpixabay_options){

          $('#options_colorsScrollbox .pixabay_option').each(function() {
             $(this).prop("checked", false);
             $(this).attr("checked", null);
          });

          for (var name in savedpixabay_options) {
            var id = name.substr(name.indexOf('_') + 1);
            if(name.indexOf("INPUT") > -1){
              $('#options_colorsScrollbox #' + id).prop("checked", true);
              $('#options_colorsScrollbox #' + id).attr("checked", "checked");
            }else{
              $('#' + id).val(savedpixabay_options[name]);
            }
          }

       }
     }

     $(function () {
         $('#PixabayOptionsDlg').modalPopLite({ openButton: '#optionsBtn', closeButton: '#options_close-btn, #options_cancel_btn', isModal: true, callBack: PixabayOptionsDlgCancelCallback });
     });

     $('#optionsBtn').click(function(e){
       // save settings, when user cancel it, we restore it
       savedpixabay_options = {};
       $('.pixabay_option').each(function() {
          if($(this).prop("tagName") == "INPUT" && ($(this).prop("checked") || $(this).attr("checked")) )
            savedpixabay_options[$(this).prop("tagName") + '_' + $(this).prop("id")] = $(this).val();
            else
             if($(this).prop("tagName") != "INPUT")
               savedpixabay_options[$(this).prop("tagName") + '_' + $(this).prop("id")] = $(this).val();
       });
     });

  </script>

  <span id="BackBtnWarnText" style="display: none;"></span>
  <textarea id="unhtmlentities" rows="0" cols="0" style="display: none"></textarea>
  <input type="hidden" id="current_width" />
  <input type="hidden" id="current_height" />
  <input type="hidden" id="reqWidth" value="<?php echo (isset($_GET['reqWidth']) ? intval($_GET['reqWidth']) : -1) ?>" />
  <input type="hidden" id="reqHeight" value="<?php echo (isset($_GET['reqHeight']) ? intval($_GET['reqHeight']) : -1) ?>" />

  <!-- PixabayOptionsDlg -->
  <div id="PixabayOptionsDlg" style="width: 520px; height: 440px; background-color: #FFFFFF; padding: 10px;">
      <div>
        <div>
         <div style="float: right;"><input type="image" id="options_close-btn" src="images/close.png" title="Close" /></div>
         <h2 style="text-align: center" id="PixabayOptionsDlgLabel"></h2>
         <br />
        </div>

        <div style="overflow-y: auto; height: 320px; font-size: 13px;">

           <div style="margin-bottom: 8px;">
            <span id="options_order_label"></span><br /><select id="options_order" size="1" class="pixabay_option">
                          <option id="options_orderpopular" value="orderpopular" selected="selected">Beliebtheit</option>
                          <option id="options_orderlatest" value="orderlatest">Neuerscheinungen</option>
                        </select>
           </div>

           <div style="margin-bottom: 8px;">
            <span id="options_image_type_label"></span><br /><select id="options_image_type" size="1" class="pixabay_option">
                          <option id="options_image_type_all" value="all" selected="selected">Alle</option>
                          <option id="options_image_type_photo" value="photo">Fotos</option>
                          <option id="options_image_type_illustration" value="illustration">Illustrationen</option>
                          <option id="options_image_type_vector" value="vector">Vektor-Grafiken</option>
                        </select>
           </div>

           <div style="margin-bottom: 8px;">
             <span id="options_orientation_label"></span><br /><select id="options_orientation" size="1" class="pixabay_option">
                          <option id="options_orientation_all" value="all" selected="selected">Egal</option>
                          <option id="options_orientation_horizontal" value="horizontal">Horizontal</option>
                          <option id="options_orientation_vertical" value="vertical">Vertikal</option>
                        </select>
           </div>

           <div style="margin-bottom: 8px;">
            <span id="options_colorsScrollbox_label"></span>
            <div class="scrollbox" style="width: 310px; height: 140px" id="options_colorsScrollbox">
                <span class="scrollboxSpan"><input type="checkbox" id="options_colorsId" value="options_colors_value" class="pixabay_option" />&nbsp;<label for="options_colorsId"><span style="background-color: options_colors_value;font-weight: bold;">options_colors_name</span></label><br /></span>
            </div>

           </div>
        </div>

        <div style="float: right">
         <br /><br />
         <button id="options_ok_btn" type="button" value="1"></button>
         <button id="options_cancel_btn" type="button" value="0"></button>
        </div>

      </div>
  </div>
  <!-- /PixabayOptionsDlg -->

</body>

</html>
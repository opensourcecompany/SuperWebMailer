<?php
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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

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

  // Set the response format.
  @header( 'Content-Type: text/html; charset='.$_QLo06 ) ;

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeTargetGroupsBrowse"]) {
      print $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"];
      exit;
    }
  }

?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="pragma" content="no-cache" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-latest.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>

<style>
 body, div { background-color: #FFFFFF; }
 label {cursor: pointer;}
 fieldset {border-color: transparent;}
</style>

<?php

   if(empty($_GET["wizard"])){
    echo "<style>
     fieldset {font-size: 0.8em; color: #000000; font-weight: normal; letter-spacing: 0px;}
    </style>";
   }

   if(!empty($_GET["wizard"])){

echo '
<link rel="stylesheet" href="ipe/css/redmond/jquery-ui-1.8.14.custom.css" type="text/css" />
<style type="text/css">
<!--
 /*  jquery UI */
 .ui-widget{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 0.8em;
  font-style: normal;
  font-variant: normal;
  font-weight: normal;
 }
 .ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 1.10em;
 	font-weight: normal;
 	color: #000000;
  margin: 0px;
  padding:2px;
  line-height: normal;
 }
-->
</style>
';
}
?>


<script>
   var wizard = "";

   function LoadTargetGroups(currentTargetGroups){
    $("#targetgroups").html('<img src="images/loading.gif" height="16" width="16" alt="Loading..." />');
    var date = new Date();
    var nocache = date.getTime() / 1000;
    $.ajaxSetup({ cache: false });

    $param = "";
    if(wizard !== "")
      $param = '&wizard=' + wizard;
   	jQuery.get('ajax_gettargetgroups.php' + "?nocache=" + nocache + $param, "", function(data){
   	   $("#targetgroups").html( data );
       _SetCheckMarkToCurrentTargetGroups(currentTargetGroups);
   	});

   }

   function GetCheckedTargetGroups(){
     var tags = document.getElementsByTagName("input");
     var result = "";
     for(var i=0; i<tags.length; i++){
       if(tags[i].type != "button" && tags[i].checked && tags[i].value)
         result += " " + tags[i].value;
     }
     return result.trim();
   }

   function _SetCheckMarkToCurrentTargetGroups(currentTargetGroups){
     if(!currentTargetGroups) return;
     var tags = document.getElementsByTagName("input");
     for(var i=0; i<tags.length; i++){
       if(tags[i].type != "button")
         tags[i].checked = false;
     }

     currentTargetGroups = currentTargetGroups.split(" ");
     for(var i=0; i< currentTargetGroups.length; i++){
        var t = currentTargetGroups[i].toLowerCase();
        for(var j=0; j<tags.length; j++){
          if(tags[i].type != "button" && tags[j].getAttribute('value') && tags[j].getAttribute('value').toLowerCase() == t)
             tags[j].checked = true;
        }
     }
   }

   function CheckAll(){
     var tags = document.getElementsByTagName("input");
     for(var i=0; i<tags.length; i++)
       if(tags[i].type != "button")
          tags[i].checked = true;
   }

   function UnCheckAll(){
     var tags = document.getElementsByTagName("input");
     for(var i=0; i<tags.length; i++)
       if(tags[i].type != "button")
          tags[i].checked = false;
   }

</script>
</head>
<body>
 <div class="ui-widget">
   <fieldset>
     <div id="targetgroups">
       <img src="images/loading.gif" height="16" width="16" alt="Loading..." />
     </div>
   </fieldset>
   <input type="hidden" name="<?php echo SMLSWM_TOKEN_COOKIE_NAME; ?>" value="<?php echo _LJPOA(); ?>" />
 </div>

<?php

   if(empty($_GET["wizard"])){
   echo "<script>
     $(document).ready(function(){
       parent.LoadCKEditorTargetGroups();
     });
     </script>";
   }

   if(!empty($_GET["wizard"])){
   echo "<script>
     wizard = 'wizard';
     $(document).ready(function(){
        parent.LoadWizardTargetGroups();
     });
     </script>";
   }
?>

</body>
</html>

<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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
  include_once("templates.inc.php");
  include_once("sessioncheck.inc.php");

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

  if(defined("SML")){
    $_QJCJi = join("", file(InstallPath."plugins/cktemplates.js"));
    // Set the response format.
    @header( 'Content-Type: text/javascript; charset='.$_Q6QQL ) ;
    print $_QJCJi;
    exit;
  }

  // import sample newsletter templates
  include_once("defaulttexts.inc.php");
  _O8QO8();

  // Set the response format.
  @header( 'Content-Type: text/javscript; charset='.$_Q6QQL ) ;



  print " CKEDITOR.addTemplates( 'default', ".$_Q6JJJ;
  print "  {".$_Q6JJJ;

    // The name of the subfolder that contains the preview images of the templates.
  print "  imagesPath : CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf('ckeditor/')) + 'plugins/template_images/',".$_Q6JJJ;

  print "   // Template definitions.".$_Q6JJJ;
  print " templates :".$_Q6JJJ;
  print "  [".$_Q6JJJ;

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeTemplateBrowse"]) {
     $_Q6Q1C["Name"] = $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"];
     $_Q6Q1C["MailHTMLText"] = "<p>".$resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]."</p>";
     $_QJCJi = str_replace("&", "&amp;", $_Q6Q1C["Name"]);
     print _O8PAD($_QJCJi, "swm_logo32x32.gif", "", $_Q6Q1C["MailHTMLText"]);

   		print " ]".$_Q6JJJ;
     print "});".$_Q6JJJ;
     exit;
    }
  }


  if($OwnerUserId == 0)
    $_QJlJ0 = "SELECT id, UsersOption, Name, MailHTMLText FROM $_Q66li WHERE MailFormat <> 'PlainText' ORDER BY Name";
    else
    $_QJlJ0 = "SELECT id, UsersOption, Name, MailHTMLText FROM $_Q66li LEFT JOIN $_Q6ftI ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)) AND MailFormat <> 'PlainText' ORDER BY Name";

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_II6ft = 0;
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      if($_II6ft++)
        print ",".$_Q6JJJ;
      $_QJCJi = str_replace("&", "&amp;", $_Q6Q1C["Name"]);
      print _O8PAD($_QJCJi, "swm_logo32x32.gif", "", $_Q6Q1C["MailHTMLText"]);
  }

		print " ]".$_Q6JJJ;
  print "});".$_Q6JJJ;


  function _O8PAD($_jlQol, $_jlQC0, $_j0O0L, $_Q6ICj){
   global $_Q6JJJ;
   $_Q6ICj = _OPQLR($_Q6ICj);
   return
    " { ".$_Q6JJJ.
    "   title: '".$_jlQol."',".$_Q6JJJ.
    "   image: '".$_jlQC0."',".$_Q6JJJ.
    "  description: '".$_j0O0L."',".$_Q6JJJ.
    "   html:".$_Q6JJJ.
    "   ".$_Q6ICj.$_Q6JJJ.
    "  }".$_Q6JJJ;
  }

?>

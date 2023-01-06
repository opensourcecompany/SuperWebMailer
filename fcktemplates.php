<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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
    $_QLJfI = join("", file(InstallPath."plugins/cktemplates.js"));
    // Set the response format.
    @header( 'Content-Type: text/javascript; charset='.$_QLo06 ) ;
    print $_QLJfI;
    exit;
  }

  // import sample newsletter templates
  include_once("defaulttexts.inc.php");
  _L61BJ();

  // Set the response format.
  @header( 'Content-Type: text/javascript; charset='.$_QLo06 ) ;



  print " CKEDITOR.addTemplates( 'default', ".$_QLl1Q;
  print "  {".$_QLl1Q;

    // The name of the subfolder that contains the preview images of the templates.
  print "  imagesPath : CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf('ckeditor/')) + 'plugins/template_images/',".$_QLl1Q;

  print "   // Template definitions.".$_QLl1Q;
  print " templates :".$_QLl1Q;
  print "  [".$_QLl1Q;

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeTemplateBrowse"]) {
     $_QLO0f["Name"] = $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"];
     $_QLO0f["MailHTMLText"] = "<p>".$resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]."</p>";
     $_QLJfI = str_replace("&", "&amp;", $_QLO0f["Name"]);
     print _L6F1B($_QLJfI, "swm_logo32x32.gif", "", $_QLO0f["MailHTMLText"]);

   		print " ]".$_QLl1Q;
     print "});".$_QLl1Q;
     exit;
    }
  }


  if($OwnerUserId == 0)
    $_QLfol = "SELECT id, UsersOption, Name, MailHTMLText FROM $_Ql10t WHERE MailFormat <> 'PlainText' ORDER BY Name";
    else
    $_QLfol = "SELECT id, UsersOption, Name, MailHTMLText FROM $_Ql10t LEFT JOIN $_Ql18I ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)) AND MailFormat <> 'PlainText' ORDER BY Name";

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_ICQjo = 0;
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if($_ICQjo++)
        print ",".$_QLl1Q;
      $_QLJfI = str_replace("&", "&amp;", unhtmlentities($_QLO0f["Name"]));
      print _L6F1B($_QLJfI, "swm_logo32x32.gif", "", $_QLO0f["MailHTMLText"] );
  }

		print " ]".$_QLl1Q;
  print "});".$_QLl1Q;


  function _L6F1B($Title, $_600I8, $_jiILL, $_QLoli){
   global $_QLl1Q;
   $_QLoli = _LRAFO($_QLoli);
   return
    " { ".$_QLl1Q.
    "   title: '".$Title."',".$_QLl1Q.
    "   image: '".$_600I8."',".$_QLl1Q.
    "  description: '".$_jiILL."',".$_QLl1Q.
    "   html:".$_QLl1Q.
    "   ".$_QLoli.$_QLl1Q.
    "  }".$_QLl1Q;
  }

?>

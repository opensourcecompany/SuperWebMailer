<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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

  if(!isset($_GET["id"]))
    $_GET["id"] = 0;

  $_GET["id"] = intval($_GET["id"]);

  if($_GET["id"] > 0 && $OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeTemplateBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if( $_GET["id"] > 0 ) {

    if($OwnerUserId == 0)
      $_QJlJ0 = "SELECT MailHTMLText FROM $_Q66li WHERE MailFormat <> 'PlainText' AND id=$_GET[id]";
      else
      $_QJlJ0 = "SELECT MailHTMLText FROM $_Q66li LEFT JOIN $_Q6ftI ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)) AND id=$_GET[id] AND MailFormat <> 'PlainText'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_num_rows($_Q60l1) == 0){
        $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
        print $_QJCJi;
        exit;
    }

    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  } else {

    $_Jtl1O = true;
    if(isset($_GET["CampaignListId"])) {
      $_GET["CampaignListId"] = intval($_GET["CampaignListId"]);

      if($OwnerUserId != 0) {
        $_QJojf = _OBOOC($UserId);
        if(!$_QJojf["PrivilegeCampaignEdit"]) {
          $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
          $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
          print $_QJCJi;
          exit;
        }
      }

      $_QJlJ0 = "SELECT `WizardHTMLText` AS `MailHTMLText` FROM `$_Q6jOo` WHERE `MailEditType`='Wizard' AND id=".$_GET["CampaignListId"];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      if(mysql_num_rows($_Q60l1)) {
         $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
         mysql_free_result($_Q60l1);
         $_Jtl1O = (trim($_Q6Q1C["MailHTMLText"]) == "") || (strpos($_Q6Q1C["MailHTMLText"], "</head>") === false);
      }
    }

    if($_Jtl1O) {
      $_Q6Q1C["MailHTMLText"] = "<!DOCTYPE html><html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><style><!-- html,body{font-family: Arial; font-size: 1em} --></style></head><body bgcolor=\"#FFFFFF\"><div id=\"no_template_loaded\">".$resourcestrings[$INTERFACE_LANGUAGE]["NoTemplateLoaded"]."<hr /></div>".'<div align="center"><table border="0" cellpadding="0" width="600"><tr><td width="100%" align="left"><repeater editable="true"><multiline></repeater></td></tr></table></div>'."</body></html>";
      $_Q6Q1C["MailHTMLText"] = str_replace("<multiline>", "<div><multiline>".$resourcestrings[$INTERFACE_LANGUAGE]["NoTemplateLoadedHint"]."<br />"."</multiline></div>", $_Q6Q1C["MailHTMLText"]);
    } else {
      $_Q6Q1C["MailHTMLText"] = SetHTMLCharSet($_Q6Q1C["MailHTMLText"], $_Q6QQL, false);
    }
  }

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
  @header( 'Content-Type: text/html; charset='.$_Q6QQL ) ;


                $_JtlLi =  '<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
                                                        <link rel="stylesheet" href="ipe/css/inplace_edit.css" type="text/css" />
                                                        <script type="text/javascript" src="ipe/js/inplace_edit.js"></script>
        <script type="text/javascript"  src="js/jquery-latest.min.js"></script>
                                                        <script type="text/javascript" src="ipe/js/jquery-ui.min.js"></script>
                                                        <link rel="stylesheet" href="ipe/css/redmond/jquery-ui-1.8.14.custom.css" type="text/css" />';

  if(strpos($_Q6Q1C["MailHTMLText"], "ipe/js/inplace_edit.js") === false)
    $_Q6Q1C["MailHTMLText"] = str_replace("</head>", $_JtlLi.$_Q6JJJ."</head>", $_Q6Q1C["MailHTMLText"]);
  print $_Q6Q1C["MailHTMLText"];

?>

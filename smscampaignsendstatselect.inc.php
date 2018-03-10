<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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

  if(!isset($_I0600))
    $_I0600 = "";

  $_IiQl1 = $_IoCtL;
  $ResponderType = "SMSCampaign";
  if(isset($_GET["ResponderType"]))
    $ResponderType = $_GET["ResponderType"];
    else
    if(isset($_POST["ResponderType"]))
      $ResponderType = $_POST["ResponderType"];

  if( $ResponderType != "SMSCampaign"){
    if( $ResponderType == "Campaign")
      include_once("campaignsendstatselect.inc.php");
      else
      include_once("responderselect.inc.php");
    return;
  }

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_QJlJ0 = "SELECT Name, CurrentSendTableName FROM $_IiQl1 WHERE id=".intval($_I6lOO);
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_Q6Q1C=mysql_fetch_array($_Q60l1);
  $_j0fti = $_Q6Q1C["CurrentSendTableName"];
  $_j06O8 = $_Q6Q1C["Name"];
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT id, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, RecipientsCount FROM $_j0fti ORDER BY StartSendDateTime DESC";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);

  if(mysql_num_rows($_Q60l1) != 1) {
    $_jjQIo = "";
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_jjQIo .= str_replace('%RECIPIENTCOUNT%', $_Q6Q1C["RecipientsCount"], '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["StartSendDateTimeFormated"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].'</option>'.$_Q6JJJ);
    }
    mysql_free_result($_Q60l1);

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I0600, "", 'DISABLED', 'campaignsendstatselect_snipped.htm');
    $_QJCJi = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QJCJi);

    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:SENDITEMS>", "</SHOW:SENDITEMS>", $_jjQIo);


    if($_IiQl1 != $_Q6jOo) {
       $_QJCJi = _OPR6L($_QJCJi, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000490"], $_j06O8));
       $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000491"], $_j06O8));
       $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000492"], $_j06O8));
    }else {
       $_QJCJi = _OPR6L($_QJCJi, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000486"], $_j06O8));
       $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000487"], $_j06O8));
       $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000488"], $_j06O8));
    }

    $_QJCJi = str_replace('name="CampaignId"', 'name="CampaignId" value="'.$_I6lOO.'"', $_QJCJi);
    $_QJCJi = str_replace('action=""', 'action="'.$_GET["action"].'"', $_QJCJi);

    print $_QJCJi;
  } else {
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_POST['SendStatId'] = $_Q6Q1C["id"];
    mysql_free_result($_Q60l1);
  }

?>

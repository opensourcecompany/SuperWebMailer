<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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

  if(!isset($_Itfj8))
    $_Itfj8 = "";

  $_jfJJ0 = $_QLi60;
  $ResponderType = "Campaign";
  if(isset($_GET["ResponderType"]))
    $ResponderType = $_GET["ResponderType"];
    else
    if(isset($_POST["ResponderType"]))
      $ResponderType = $_POST["ResponderType"];

  $_J0ifL = _LPO6C($ResponderType);
  if($_J0ifL)
   $_jfJJ0 = _LPLBQ($_J0ifL);
   else {

     if($ResponderType == "AutoResponder"){
        $_jfJJ0 = $_IoCo0;
     }

     if( $ResponderType == "SplitTest")
       $_jfJJ0 = $_jJL88;
   }

   if( $ResponderType == "DistributionList"){
     include_once("distriblistentryselect.inc.php");
     return;
   }

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_QLfol = "SELECT Name, CurrentSendTableName FROM `$_jfJJ0` WHERE id=".intval($_j01fj);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f=mysql_fetch_array($_QL8i1);
  $_jClC1 = $_QLO0f["CurrentSendTableName"];
  $_jC6ot = $_QLO0f["Name"];
  mysql_free_result($_QL8i1);

  $_QLlO6 = "";
  if($ResponderType == "Campaign"){
    $_QLlO6 = " WHERE `Campaigns_id`=".intval($_j01fj) . " ";
  }

  $_QLfol = "SELECT id, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, RecipientsCount FROM $_jClC1 $_QLlO6 ORDER BY StartSendDateTime DESC LIMIT 0,100";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  if(mysql_num_rows($_QL8i1) != 1) {
    $_J0iof = "";
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_J0iof .= str_replace('%RECIPIENTCOUNT%', $_QLO0f["RecipientsCount"], '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["StartSendDateTimeFormated"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].'</option>'.$_QLl1Q);
    }
    mysql_free_result($_QL8i1);

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_Itfj8, "", 'DISABLED', 'campaignsendstatselect_snipped.htm');
    $_QLJfI = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QLJfI);

    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:SENDITEMS>", "</SHOW:SENDITEMS>", $_J0iof);


    if($_jfJJ0 != $_QLi60) {
       $_QLJfI = _L81BJ($_QLJfI, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000490"], $_jC6ot));
       $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000491"], $_jC6ot));
       $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000492"], $_jC6ot));
    }else {
       $_QLJfI = _L81BJ($_QLJfI, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000486"], $_jC6ot));
       $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000487"], $_jC6ot));
       $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000488"], $_jC6ot));
    }

    $_QLJfI = str_replace('name="CampaignId"', 'name="CampaignId" value="'.intval($_j01fj).'"', $_QLJfI);
    $_QLJfI = str_replace('action=""', 'action="'.$_GET["action"].'"', $_QLJfI);

    print $_QLJfI;
  } else {
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_POST['SendStatId'] = $_QLO0f["id"];
    mysql_free_result($_QL8i1);
  }

?>

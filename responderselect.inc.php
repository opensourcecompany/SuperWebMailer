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

  $_jfJJ0 = $_IoCo0;
  $ResponderType = "Autoresponder";
  if(isset($_GET["ResponderType"]))
    $ResponderType = $_GET["ResponderType"];
    else
    if(isset($_POST["ResponderType"]))
      $ResponderType = $_POST["ResponderType"];

  if(isset($resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSource".$ResponderType]))
    $_flllL = $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSource".$ResponderType];
    else
    $_flllL = "Responder";
  if($_Itfj8 == "")
    $_Itfj8 = $_flllL;

  $_J0ifL = _LPO6C($ResponderType);
  if($_J0ifL)
    $_jfJJ0 = _LPLBQ($_J0ifL);
    else {
      if($ResponderType == "AutoResponder"){
        $_jfJJ0 = $_IoCo0;
      } else
      if( $ResponderType == "SplitTest") {
          $_jfJJ0 = $_jJL88;
          $_flllL = $resourcestrings[$INTERFACE_LANGUAGE]["001820"];
         } else
           if( $ResponderType == "SMSCampaign")
              $_jfJJ0 = $_jJLLf;

    }
  if($_jfJJ0 == "") return false;

  $_j16tj = "";
  if($OwnerUserId != 0) {
    $_j16tj = "LEFT JOIN `$_QlQot` ON `$_QlQot`.`maillists_id`=`$_jfJJ0`.`maillists_id` WHERE (`$_QlQot`.`users_id`=$UserId)";
    if($ResponderType == "Autoresponder")
       $_j16tj .= " OR (`$_jfJJ0`.`maillists_id` = 0)";
  }

  $_jQoQi = "";
  if($ResponderType != "Autoresponder" && $ResponderType != "SplitTest" && $ResponderType != "SMSCampaign" && !empty($_GET["TrackingStatistics"])) {
     if($_j16tj == "")
       $_jQoQi = "WHERE ";
       else
       $_jQoQi = " AND ";
     $_jQoQi .= "(`TrackLinks` > 0 OR `TrackEMailOpenings` > 0 OR `TrackLinksByRecipient` > 0 OR `TrackEMailOpeningsByRecipient` > 0)";
  }

  $_QLfol = "SELECT `id`, `Name` FROM `$_jfJJ0` $_j16tj $_jQoQi ORDER BY `Name`";
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);

  if(mysql_num_rows($_QL8i1) != 1) {
    $_J0iof = "";
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_J0iof .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
    }
    mysql_free_result($_QL8i1);

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_Itfj8, "", 'DISABLED', 'responderselect_snipped.htm');
    $_QLJfI = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:RESPONDERS>", "</SHOW:RESPONDERS>", $_J0iof);

    if($_jfJJ0 != $_QLi60) {
       if($_jfJJ0 != $_jJLLf) {
           $_QLJfI = _L81BJ($_QLJfI, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000480"], $_flllL));
         }
         else
         $_QLJfI = _L81BJ($_QLJfI, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000489"], $_flllL));

       if($_jfJJ0 == $_IjC0Q)
         $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002663"], $_flllL));
       else
         $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000481"], $_flllL));
       $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000482"], $_flllL));
    }else {
      $_QLJfI = _L81BJ($_QLJfI, "<ResponderLegendLabel>", "</ResponderLegendLabel>", $resourcestrings[$INTERFACE_LANGUAGE]["000483"]);
      $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel1>", "</ResponderLabel1>", $resourcestrings[$INTERFACE_LANGUAGE]["000484"]);
      $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel2>", "</ResponderLabel2>", $resourcestrings[$INTERFACE_LANGUAGE]["000485"]);
    }

    print $_QLJfI;
  } else {
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_POST['ResponderId'] = $_QLO0f["id"];
    mysql_free_result($_QL8i1);
  }

?>

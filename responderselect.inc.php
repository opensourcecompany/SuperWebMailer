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

  $_IiQl1 = $_IQL81;
  $ResponderType = "Autoresponder";
  if(isset($_GET["ResponderType"]))
    $ResponderType = $_GET["ResponderType"];
    else
    if(isset($_POST["ResponderType"]))
      $ResponderType = $_POST["ResponderType"];

  if(isset($resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSource".$ResponderType]))
    $_680oL = $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSource".$ResponderType];
    else
    $_680oL = "Responder";
  if($_I0600 == "")
    $_I0600 = $_680oL;

  $_jj1tl = _OAP0L($ResponderType);
  if($_jj1tl)
    $_IiQl1 = _OABJE($_jj1tl);
    else {
      if($ResponderType == "AutoResponder"){
        $_IiQl1 = $_IQL81;
      } else
      if( $ResponderType == "SplitTest") {
          $_IiQl1 = $_IooOQ;
          $_680oL = $resourcestrings[$INTERFACE_LANGUAGE]["001820"];
         } else
           if( $ResponderType == "SMSCampaign")
              $_IiQl1 = $_IoCtL;

    }
  if($_IiQl1 == "") return false;

  $_IfL8I = "";
  if($OwnerUserId != 0) {
    $_IfL8I = "LEFT JOIN `$_Q6fio` ON `$_Q6fio`.`maillists_id`=`$_IiQl1`.`maillists_id` WHERE (`$_Q6fio`.`users_id`=$UserId)";
    if($ResponderType == "Autoresponder")
       $_IfL8I .= " OR (`$_IiQl1`.`maillists_id` = 0)";
  }

  $_I8C10 = "";
  if($ResponderType != "Autoresponder" && $ResponderType != "SplitTest" && $ResponderType != "SMSCampaign" && !empty($_GET["TrackingStatistics"])) {
     if($_IfL8I == "")
       $_I8C10 = "WHERE ";
       else
       $_I8C10 = " AND ";
     $_I8C10 .= "(`TrackLinks` > 0 OR `TrackEMailOpenings` > 0 OR `TrackLinksByRecipient` > 0 OR `TrackEMailOpeningsByRecipient` > 0)";
  }

  $_QJlJ0 = "SELECT `id`, `Name` FROM `$_IiQl1` $_IfL8I $_I8C10 ORDER BY `Name`";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);

  if(mysql_num_rows($_Q60l1) != 1) {
    $_jjQIo = "";
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_jjQIo .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
    }
    mysql_free_result($_Q60l1);

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I0600, "", 'DISABLED', 'responderselect_snipped.htm');
    $_QJCJi = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QJCJi);
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:RESPONDERS>", "</SHOW:RESPONDERS>", $_jjQIo);

    if($_IiQl1 != $_Q6jOo) {
       if($_IiQl1 != $_IoCtL) {
           $_QJCJi = _OPR6L($_QJCJi, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000480"], $_680oL));
         }
         else
         $_QJCJi = _OPR6L($_QJCJi, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000489"], $_680oL));

       if($_IiQl1 == $_QoOft)
         $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002663"], $_680oL));
       else
         $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000481"], $_680oL));
       $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000482"], $_680oL));
    }else {
      $_QJCJi = _OPR6L($_QJCJi, "<ResponderLegendLabel>", "</ResponderLegendLabel>", $resourcestrings[$INTERFACE_LANGUAGE]["000483"]);
      $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel1>", "</ResponderLabel1>", $resourcestrings[$INTERFACE_LANGUAGE]["000484"]);
      $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel2>", "</ResponderLabel2>", $resourcestrings[$INTERFACE_LANGUAGE]["000485"]);
    }

    print $_QJCJi;
  } else {
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_POST['ResponderId'] = $_Q6Q1C["id"];
    mysql_free_result($_Q60l1);
  }

?>

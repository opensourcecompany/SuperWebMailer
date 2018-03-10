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

  $_IiQl1 = $_QoOft;
  $ResponderType = "DistributionList";

  if(isset($_GET["ResponderType"]))
    $ResponderType = $_GET["ResponderType"];
    else
    if(isset($_POST["ResponderType"]))
      $ResponderType = $_POST["ResponderType"];

  if( $ResponderType == "DistributionList")
     $_IiQl1 = $_QoOft;
     else{
      include_once("campaignsendstatselect.inc.php");
      return;
     }

  if(isset($_POST['DistribListId']))
    $_QCJ0l = intval($_POST['DistribListId']);
  else
    if(isset($_GET['DistribListId']))
      $_QCJ0l = intval($_GET['DistribListId']);
      else
      if ( isset($_POST['OneDistribListId']) )
         $_QCJ0l = intval($_POST['OneDistribListId']);

  if(isset($_POST['ResponderId']))
    $_QCJ0l = intval($_POST['ResponderId']);
  else
    if(isset($_GET['ResponderId']))
      $_QCJ0l = intval($_GET['ResponderId']);

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_QJlJ0 = "SELECT `$_Qoo8o`.`id` AS `DistribListEntryId`, `$_Qoo8o`.`MailSubject`, `$_QoOft`.`Name`, `$_QoOft`.`CurrentSendTableName` FROM `$_Qoo8o` ";
  $_QJlJ0 .= "RIGHT JOIN `$_QoOft` ON `$_QoOft`.id=`$_Qoo8o`.`DistribList_id`";
  $_QJlJ0 .= " WHERE `$_QoOft`.id=".intval($_QCJ0l);
  $_QJlJ0 .= " AND (`$_Qoo8o`.`SendScheduler`='SendImmediately' OR `$_Qoo8o`.`SendScheduler`='Sent')";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);

  if(mysql_num_rows($_Q60l1) == 0) {
   include_once("browsedistriblists.php");
   return;
  }

  if(mysql_num_rows($_Q60l1) != 1) {
    $_jjQIo = "";
    $_IflL6 = 0;
    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {

      $_jLJLO = $_Q6Q1C["DistribListEntryId"];
      $_QJlJ0 = "SELECT `SendState`, DATE_FORMAT(`EndSendDateTime`, $_Q6QiO) AS StartSendDateTimeFormated, `RecipientsCount` FROM `$_Q6Q1C[CurrentSendTableName]` WHERE `distriblistentry_id`=$_jLJLO";
      $_Q8Oj8 = mysql_query($_QJlJ0);
      if(mysql_num_rows($_Q8Oj8) == 0){
        // send entry was removed
        mysql_free_result($_Q8Oj8);
        continue;
      }
      mysql_free_result($_Q8Oj8);

      $_jjQIo .= '<option value="'.$_jLJLO.'">'.$_Q6Q1C["MailSubject"].'</option>'.$_Q6JJJ;
      $_jiiOJ = $_Q6Q1C["Name"];
      $_IflL6++;
    }
    mysql_free_result($_Q60l1);
    if($_IflL6 == 1){
      $_POST['OneDLEId'] = $_jLJLO;
      return;
    }

    if($_IflL6 == 0){
      include_once("browsedistriblists.php");
      exit;
    }

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I0600, "", 'DISABLED', 'distriblistentryselect_snipped.htm');
    $_QJCJi = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QJCJi);

    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:ENTRIES>", "</SHOW:ENTRIES>", $_jjQIo);


    $_QJCJi = _OPR6L($_QJCJi, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002660"], $_jiiOJ));
    $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002661"], $_jiiOJ));
    $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002662"], $_jiiOJ));

    $_QJCJi = str_replace('name="DistribListId"', 'name="DistribListId" value="'.intval($_QCJ0l).'"', $_QJCJi);
    $_QJCJi = str_replace('action=""', 'action="'.$_GET["action"].'"', $_QJCJi);

    print $_QJCJi;
  } else {
    $_Q6Q1C=mysql_fetch_assoc($_Q60l1);
    $_POST['OneDLEId'] = $_Q6Q1C["DistribListEntryId"];
    mysql_free_result($_Q60l1);
  }

?>

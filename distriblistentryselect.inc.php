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

  $_jfJJ0 = $_IjC0Q;
  $ResponderType = "DistributionList";

  if(isset($_GET["ResponderType"]))
    $ResponderType = $_GET["ResponderType"];
    else
    if(isset($_POST["ResponderType"]))
      $ResponderType = $_POST["ResponderType"];

  if( $ResponderType == "DistributionList")
     $_jfJJ0 = $_IjC0Q;
     else{
      include_once("campaignsendstatselect.inc.php");
      return;
     }

  if(isset($_POST['DistribListId']))
    $_IJ6I8 = intval($_POST['DistribListId']);
  else
    if(isset($_GET['DistribListId']))
      $_IJ6I8 = intval($_GET['DistribListId']);
      else
      if ( isset($_POST['OneDistribListId']) )
         $_IJ6I8 = intval($_POST['OneDistribListId']);

  if(isset($_POST['ResponderId']))
    $_IJ6I8 = intval($_POST['ResponderId']);
  else
    if(isset($_GET['ResponderId']))
      $_IJ6I8 = intval($_GET['ResponderId']);

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_QLfol = "SELECT `$_IjCfJ`.`id` AS `DistribListEntryId`, `$_IjCfJ`.`MailSubject`, `$_IjC0Q`.`Name`, `$_IjC0Q`.`CurrentSendTableName` FROM `$_IjCfJ` ";
  $_QLfol .= "RIGHT JOIN `$_IjC0Q` ON `$_IjC0Q`.id=`$_IjCfJ`.`DistribList_id`";
  $_QLfol .= " WHERE `$_IjC0Q`.id=".intval($_IJ6I8);
  $_QLfol .= " AND (`$_IjCfJ`.`SendScheduler`='SendImmediately' OR `$_IjCfJ`.`SendScheduler`='Sent')";
  $_QLfol .= " ORDER BY `$_IjCfJ`.`CreateDate` DESC LIMIT 0,100";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  if(mysql_num_rows($_QL8i1) == 0) {
   include_once("browsedistriblists.php");
   return;
  }

  if(mysql_num_rows($_QL8i1) != 1) {
    $_J0iof = "";
    $_j1881 = 0;
    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {

      $_JL6Jf = $_QLO0f["DistribListEntryId"];
      $_QLfol = "SELECT `SendState`, DATE_FORMAT(`EndSendDateTime`, $_QLo60) AS StartSendDateTimeFormated, `RecipientsCount` FROM `$_QLO0f[CurrentSendTableName]` WHERE `distriblistentry_id`=$_JL6Jf";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_I1O6j) == 0){
        // sent entry was removed
        mysql_free_result($_I1O6j);
        continue;
      }
      mysql_free_result($_I1O6j);

      $_J0iof .= '<option value="'.$_JL6Jf.'">'.$_QLO0f["MailSubject"].'</option>'.$_QLl1Q;
      $_Ji8J1 = $_QLO0f["Name"];
      $_j1881++;
    }
    mysql_free_result($_QL8i1);
    if($_j1881 == 1){
      $_POST['OneDLEId'] = $_JL6Jf;
      return;
    }

    if($_j1881 == 0){
      include_once("browsedistriblists.php");
      exit;
    }

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_Itfj8, "", 'DISABLED', 'distriblistentryselect_snipped.htm');
    $_QLJfI = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QLJfI);

    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:ENTRIES>", "</SHOW:ENTRIES>", $_J0iof);


    $_QLJfI = _L81BJ($_QLJfI, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002660"], $_Ji8J1));
    $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002661"], $_Ji8J1));
    $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002662"], $_Ji8J1));

    $_QLJfI = str_replace('name="DistribListId"', 'name="DistribListId" value="'.intval($_IJ6I8).'"', $_QLJfI);
    $_QLJfI = str_replace('action=""', 'action="'.$_GET["action"].'"', $_QLJfI);

    print $_QLJfI;
  } else {
    $_QLO0f=mysql_fetch_assoc($_QL8i1);
    $_POST['OneDLEId'] = $_QLO0f["DistribListEntryId"];
    mysql_free_result($_QL8i1);
  }

?>

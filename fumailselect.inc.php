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

  if(!isset($_I0600))
    $_I0600 = "";

  $_IiQl1 = $_QCLCI;

  if(!isset($_J16QO))
    $_J16QO = $ResponderId;
  $_J16QO = intval($_J16QO);

  $_QJlJ0 = "SELECT Name, FUMailsTableName FROM $_IiQl1 WHERE id=$_J16QO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  $Name = $_Q6Q1C["Name"];
  mysql_free_result($_Q60l1);
  if($_Q6Q1C["FUMailsTableName"] == "") exit;

  $_QJlJ0 = "SELECT id, Name FROM $_Q6Q1C[FUMailsTableName] ORDER BY Name";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  if(mysql_num_rows($_Q60l1) != 1) {
    $_jjQIo = "";
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_jjQIo .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
    }
    mysql_free_result($_Q60l1);

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I0600, "", 'DISABLED', 'fumailselect_snipped.htm');
    $_QJCJi = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QJCJi);
    $_QJCJi = str_replace('name="ResponderId"', 'name="ResponderId" value="'.$_J16QO.'"', $_QJCJi);
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:FUMAILS>", "</SHOW:FUMAILS>", $_jjQIo);
    $_QJCJi = _OPR6L($_QJCJi, "<ResponderName>", "</ResponderName>", $Name);


    print $_QJCJi;
  } else {
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_POST['FUMailId'] = $_Q6Q1C["id"];
    mysql_free_result($_Q60l1);
  }

?>

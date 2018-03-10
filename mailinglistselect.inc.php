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
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if(!isset($_I0600))
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000053"];

  // ********* List of MailingLists SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_Q68ff .= " WHERE (users_id=$UserId)";
     else {
      $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }
  $_Q68ff .= " ORDER BY Name ASC";

  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);

  if(mysql_num_rows($_Q60l1) != 1) {
    $_I10Cl = "";
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
    }
    mysql_free_result($_Q60l1);

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I0600, "", 'DISABLED', 'mailinglistselect_snipped.htm');

    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_I10Cl);
    // ********* List of MailingLists SQL query END

    print $_QJCJi;
  } else {
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_POST["OneMailingListId"] = $_Q6Q1C["id"];
  }

?>

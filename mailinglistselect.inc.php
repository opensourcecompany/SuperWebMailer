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

  if(!isset($_Itfj8))
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000053"];

  // ********* List of MailingLists SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QlI6f .= " WHERE (users_id=$UserId)";
     else {
      $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }
  $_QlI6f .= " ORDER BY Name ASC";

  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);

  if(mysql_num_rows($_QL8i1) != 1) {
    $_ItlLC = "";
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
    }
    mysql_free_result($_QL8i1);

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_Itfj8, "", 'DISABLED', 'mailinglistselect_snipped.htm');

    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_ItlLC);
    // ********* List of MailingLists SQL query END

    print $_QLJfI;
  } else {
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_POST["OneMailingListId"] = $_QLO0f["id"];
  }

?>

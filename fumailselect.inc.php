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

  if(!isset($_Itfj8))
    $_Itfj8 = "";

  $_jfJJ0 = $_I616t;

  if(!isset($_6QJI0))
    $_6QJI0 = $ResponderId;
  $_6QJI0 = intval($_6QJI0);

  $_QLfol = "SELECT Name, FUMailsTableName FROM $_jfJJ0 WHERE id=$_6QJI0";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  $Name = $_QLO0f["Name"];
  mysql_free_result($_QL8i1);
  if($_QLO0f["FUMailsTableName"] == "") exit;

  $_QLfol = "SELECT id, Name FROM $_QLO0f[FUMailsTableName] ORDER BY Name";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  if(mysql_num_rows($_QL8i1) != 1) {
    $_J0iof = "";
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_J0iof .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
    }
    mysql_free_result($_QL8i1);

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_Itfj8, "", 'DISABLED', 'fumailselect_snipped.htm');
    $_QLJfI = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QLJfI);
    $_QLJfI = str_replace('name="ResponderId"', 'name="ResponderId" value="'.$_6QJI0.'"', $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:FUMAILS>", "</SHOW:FUMAILS>", $_J0iof);
    $_QLJfI = _L81BJ($_QLJfI, "<ResponderName>", "</ResponderName>", $Name);


    print $_QLJfI;
  } else {
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_POST['FUMailId'] = $_QLO0f["id"];
    mysql_free_result($_QL8i1);
  }

?>

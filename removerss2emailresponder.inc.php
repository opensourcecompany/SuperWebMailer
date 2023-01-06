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

  if($OwnerUserId != 0) {
    if(empty($_QLJJ6) || !is_array($_QLJJ6))
      $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeRSS2EMailMailsRemove"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_fLJ86))
     unset($_fLJ86);
  $_fiLlo = array();
  if ( isset($_POST["OneRSS2EMailresponderListId"]) && $_POST["OneRSS2EMailresponderListId"] != "" )
      $_fLJ86[] = $_POST["OneRSS2EMailresponderListId"];
      else
      if ( isset($_POST["OneRSS2EMailresponderListIds"]) )
        $_fLJ86 = array_merge($_fLJ86, $_POST["OneRSS2EMailresponderListIds"]);


  $_IQ0Cj = array();
  _J1EP1($_fLJ86, $_IQ0Cj);

  // we don't check for errors here
  function _J1EP1($_fLJ86, &$_IQ0Cj) {
    global $_jJLQo, $_j68Co, $_QLttI;

    for($_Qli6J=0; $_Qli6J<count($_fLJ86); $_Qli6J++) {
      $_fLJ86[$_Qli6J] = intval($_fLJ86[$_Qli6J]);
      $_QLfol = "SELECT * FROM $_jJLQo WHERE id=$_fLJ86[$_Qli6J]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      reset($_QLO0f);
      foreach($_QLO0f as $key => $_QltJO) {
        if (strpos($key, "TableName") !== false) {
          $_QLfol = "DROP TABLE IF EXISTS `$_QltJO`";
          mysql_query($_QLfol, $_QLttI);
          if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
        }
      }

      // and now from RSS2EMailresponders table
      $_QLfol = "DELETE FROM $_jJLQo WHERE id=".$_fLJ86[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      // stat
      $_QLfol = "DELETE FROM $_j68Co WHERE rss2emailresponders_id=".$_fLJ86[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    }
  }

?>

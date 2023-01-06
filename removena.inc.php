<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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

  if(!defined("API")){
    include_once("config.inc.php");
    include_once("sessioncheck.inc.php");
    include_once("templates.inc.php");

    /*if($OwnerUserId != 0) {
      if(empty($privileges) || !is_array($privileges))
        $privileges = _LPALQ($UserId);
      if(!$privileges["PrivilegeNewsletterArchiveRemove"]) {
        $s = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $s = _L81BJ($s, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
        print $s;
        exit;
      }
    } DARF HIER NICHT SEIN, VORHER PRUEFEN*/

  }

  function _J1DO1($_fLI88, &$_IQ0Cj) {
    global $_j6JfL, $_QLttI;
    mysql_query("BEGIN", $_QLttI);
    for($_Qli6J=0; $_Qli6J<count($_fLI88); $_Qli6J++){
      $_fLI88[$_Qli6J] = intval($_fLI88[$_Qli6J]);
      $_QLfol = "SELECT `CampaignToNATableName` FROM `$_j6JfL` WHERE id=".$_fLI88[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) continue;
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      $_QLfol = "DROP TABLE IF EXISTS `$_QLO0f[CampaignToNATableName]`";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      $_QLfol = "DELETE FROM `$_j6JfL` WHERE id=$_fLI88[$_Qli6J]";
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
    }
    mysql_query("COMMIT", $_QLttI);
  }

  // campaign_ops.inc.php
  function _J1DOP($_J11tt) {
    global $_j6JfL, $_QLttI;

    $_QLfol = "SELECT `CampaignToNATableName` FROM `$_j6JfL`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      mysql_query("BEGIN", $_QLttI);
      for($_Qli6J=0; $_Qli6J<count($_J11tt); $_Qli6J++) {
        $_QLfol = "DELETE FROM `$_QLO0f[CampaignToNATableName]` WHERE `campaigns_id`=".intval($_J11tt[$_Qli6J]);
        mysql_query($_QLfol, $_QLttI);
        if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      }
      mysql_query("COMMIT", $_QLttI);
    }
    mysql_free_result($_QL8i1);
  }

?>

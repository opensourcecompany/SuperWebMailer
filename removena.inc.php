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
        $privileges = _OBOOC($UserId);
      if(!$privileges["PrivilegeNewsletterArchiveRemove"]) {
        $s = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $s = _OPR6L($s, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
        print $s;
        exit;
      }
    } DARF HIER NICHT SEIN, VORHER PRUEFEN*/

  }

  function _L1CRQ($_66oft, &$_QtIiC) {
    global $_IC1lt, $_Q61I1;
    mysql_query("BEGIN", $_Q61I1);
    for($_Q6llo=0; $_Q6llo<count($_66oft); $_Q6llo++){
      $_66oft[$_Q6llo] = intval($_66oft[$_Q6llo]);
      $_QJlJ0 = "SELECT `CampaignToNATableName` FROM `$_IC1lt` WHERE id=".$_66oft[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) continue;
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      $_QJlJ0 = "DROP TABLE IF EXISTS `$_Q6Q1C[CampaignToNATableName]`";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      $_QJlJ0 = "DELETE FROM `$_IC1lt` WHERE id=$_66oft[$_Q6llo]";
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
    }
    mysql_query("COMMIT", $_Q61I1);
  }

  // campaign_ops.inc.php
  function _L1CDO($_jjJCt) {
    global $_IC1lt, $_Q61I1;

    $_QJlJ0 = "SELECT `CampaignToNATableName` FROM `$_IC1lt`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      mysql_query("BEGIN", $_Q61I1);
      for($_Q6llo=0; $_Q6llo<count($_jjJCt); $_Q6llo++) {
        $_QJlJ0 = "DELETE FROM `$_Q6Q1C[CampaignToNATableName]` WHERE `campaigns_id`=".intval($_jjJCt[$_Q6llo]);
        mysql_query($_QJlJ0, $_Q61I1);
        if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      }
      mysql_query("COMMIT", $_Q61I1);
    }
    mysql_free_result($_Q60l1);
  }

?>

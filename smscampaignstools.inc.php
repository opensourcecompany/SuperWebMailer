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
  include_once("functions.inc.php");
  include_once("templates.inc.php");
  include_once("campaignstools.inc.php");
  // no session check here!
  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP"))
    exit;

  function _LOP60($_jjI1t, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_IoCtL, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_IoCtL` WHERE id=".intval($_jjI1t);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    return _LOP8R($_Q6J0Q, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }

  function _LOP8R($_Q6J0Q, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q60QL, $_Ql8C0, $_Q6JJJ, $_Q61I1;

    if($_QlQC8 == "") {
      $_QJlJ0 = "SELECT `MaillistTableName`, `GroupsTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName` FROM `$_Q60QL` WHERE id=$_Q6J0Q[maillists_id]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_array($_Q60l1);
      mysql_free_result($_Q60l1);
      $_QlQC8 = $_Q6Q1C["MaillistTableName"];
      $_Q6t6j = $_Q6Q1C["GroupsTableName"];
      $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
      $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];
    }

    $_QJlJ0 = "SELECT COUNT(`ml_groups_id`) FROM `$_Q6J0Q[GroupsTableName]`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_IO0Jo = 0;
    if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
      $_IO0Jo = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }

    $_IO1I1 = 0;
    if($_IO0Jo > 0){
      $_QJlJ0 = "SELECT COUNT(`ml_groups_id`) FROM `$_Q6J0Q[NotInGroupsTableName]`";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
        $_IO1I1 = $_Q6Q1C[0];
        mysql_free_result($_Q60l1);
      }
    }

    if($_IO0Jo > 1) {
      $_IOJ8I = "DISTINCT `$_QlQC8`.`u_EMail`,";
    } else
      $_IOJ8I = "";
    # !! cronsplit_tests.inc.php checks for `MembersAge` as last value before FROM !!
    $_QJlJ0 = "SELECT $_IOJ8I `$_QlQC8`.*, IF(`$_QlQC8`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_QlQC8`.`u_Birthday`), 0) AS `MembersAge` FROM `$_QlQC8`".$_Q6JJJ;

    $_QJlJ0 .= " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail`=`$_QlQC8`.`u_EMail`".$_Q6JJJ;
    $_QJlJ0 .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail`=`$_QlQC8`.`u_EMail`".$_Q6JJJ;

    if($_IO0Jo > 0) {
      $_QJlJ0 .= " LEFT JOIN `$_QLI68` ON `$_QlQC8`.`id`=`$_QLI68`.`Member_id`".$_Q6JJJ;
      $_QJlJ0 .= " LEFT JOIN `$_Q6J0Q[GroupsTableName]` ON `$_Q6J0Q[GroupsTableName]`.`ml_groups_id`=`$_QLI68`.`groups_id`".$_Q6JJJ;
      if($_IO1I1 > 0) {
        $_QJlJ0 .= "  LEFT JOIN ( ".$_Q6JJJ;

        $_QJlJ0 .= "    SELECT `$_QlQC8`.`id`".$_Q6JJJ;
        $_QJlJ0 .= "    FROM `$_QlQC8`".$_Q6JJJ;

        $_QJlJ0 .= "    LEFT JOIN `$_QLI68` ON `$_QlQC8`.`id`=`$_QLI68`.`Member_id`".$_Q6JJJ;
        $_QJlJ0 .= "    LEFT JOIN `$_Q6J0Q[NotInGroupsTableName]` ON".$_Q6JJJ;
        $_QJlJ0 .= "    `$_Q6J0Q[NotInGroupsTableName]`.`ml_groups_id`=`$_QLI68`.`groups_id`".$_Q6JJJ;
        $_QJlJ0 .= "    WHERE `$_Q6J0Q[NotInGroupsTableName]`.`ml_groups_id` IS NOT NULL".$_Q6JJJ;

        $_QJlJ0 .= "  ) AS `subquery` ON `subquery`.`id`=`$_QlQC8`.`id`".$_Q6JJJ;
      }
    }

    $_QJlJ0 .= " WHERE (`$_QlQC8`.`IsActive`=1 AND `$_QlQC8`.`SubscriptionStatus`<>'OptInConfirmationPending' AND TRIM(`$_QlQC8`.`u_CellNumber`)<>'')".$_Q6JJJ;
    $_QJlJ0 .= " AND (`$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL) ".$_Q6JJJ;
    if($_IO0Jo > 0) {
      $_QJlJ0 .= " AND (`$_Q6J0Q[GroupsTableName]`.`ml_groups_id` IS NOT NULL)".$_Q6JJJ;
      if($_IO1I1 > 0) {
       $_QJlJ0 .= " AND (`subquery`.`id` IS NULL)".$_Q6JJJ;
      }
    }

    if($_Q6J0Q["SendRules"] != "") {
        $_j1I60 = @unserialize($_Q6J0Q["SendRules"]);
        if($_j1I60 === false)
          $_j1I60 = array();
      }
      else
      $_j1I60 = array();

    $_QtjtL = array();
    for($_Q6llo=0; $_Q6llo<count($_j1I60); $_Q6llo++) {
      $_QtjtL[] = array("sql" => _O6L1O($_j1I60[$_Q6llo], $_QlQC8), "logicaloperator" => $_j1I60[$_Q6llo]["logicaloperator"] );
    }

    if(count($_QtjtL) > 0) {
      $_QJlJ0 .= " AND ".$_Q6JJJ."( ".$_Q6JJJ;

      $_Iijl0 = "";
      for($_Q6llo=0; $_Q6llo<count($_QtjtL); $_Q6llo++) {
        if ($_Q6llo % 2 == 0)
          $_Iijl0 = $_Iijl0 . " (  ". $_QtjtL[$_Q6llo]["sql"];
          else {
           $_Iijl0 = $_Iijl0 . " ". $_QtjtL[$_Q6llo - 1]["logicaloperator"] . " ". $_QtjtL[$_Q6llo]["sql"] . "  ) ";
           if($_Q6llo != count($_QtjtL) - 1)
              $_Iijl0 .= $_Q6JJJ.$_QtjtL[$_Q6llo]["logicaloperator"];
          }
      }
      if ( (count($_QtjtL) - 1) % 2 == 0)
         $_Iijl0 .= "  )";

      $_QJlJ0 .= $_Iijl0.$_Q6JJJ.")".$_Q6JJJ;

      }

    return $_QJlJ0;
  }

  function _LOPBF($_jjI1t, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_IoCtL, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_IoCtL` WHERE `id`=".intval($_jjI1t);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6J0Q = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    return _LOA66($_Q6J0Q, $_jQIIi, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }

  function _LOA66($_Q6J0Q, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q61I1;
    $_jQIIi = _LOP8R($_Q6J0Q, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);

    $_Q60l1 = mysql_query($_jQIIi, $_Q61I1);
    if(mysql_error($_Q61I1) == "") {
      return mysql_num_rows($_Q60l1);
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_Q61I1)."<br />";
    }

    return 0;
  }

  function _LOAFR($_Q6J0Q, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q61I1;
    $_jQIIi = _LOP8R($_Q6J0Q, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);

    $_QJlJ0 = $_jQIIi;

    $_jjjjC = $_QlQC8;
    if($_jjjjC == ""){
      $_jjjjC = substr($_QJlJ0, 0, strpos($_QJlJ0, "."));
      $_jjjjC = substr($_jjjjC, strpos($_jjjjC, " ") + 1);
      if(strpos($_jjjjC, " ") !== false)
        $_jjjjC = substr($_jjjjC, strpos($_jjjjC, " ") + 1);
    }

    $_QtjtL = substr($_QJlJ0, 0, strpos($_QJlJ0, ' ') + 1);
    $_j1toJ = substr($_QJlJ0, strpos($_QJlJ0, 'FROM'));
    if(strpos($_jQIIi, "DISTINCT ") !== false)
       $_QJlJ0 = $_QtjtL." COUNT(DISTINCT $_jjjjC.`u_EMail`) ".$_j1toJ;
       else
       $_QJlJ0 = $_QtjtL." COUNT($_jjjjC.`u_EMail`) ".$_j1toJ;

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_error($_Q61I1) == "") {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      return $_Q6Q1C[0];
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_Q61I1)."<br />";
    }

    return 0;
  }

  function _LOBEC($_jjI1t, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_IoCtL, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_IoCtL` WHERE `id`=".intval($_jjI1t);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6J0Q = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    return _LOAFR($_Q6J0Q, $_jQIIi, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }


?>

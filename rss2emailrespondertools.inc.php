<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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
    include_once("functions.inc.php");
    include_once("templates.inc.php");
  }

  // no session check here!
  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;


  function _JQB8D($_j11Lo, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_jJLQo, $_QLttI;
    $_QLfol = "SELECT * FROM `$_jJLQo` WHERE `id`=".intval($_j11Lo);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    return _JQBFE($_QLO0f, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }

  function _JQBFE($_80LOl, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QL88I, $_I8tfQ, $_QLl1Q, $_QLttI;

    if($_I8I6o == "") {
      $_QLfol = "SELECT `MaillistTableName`, `GroupsTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName` FROM `$_QL88I` WHERE `id`=$_80LOl[maillists_id]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_I8I6o = $_QLO0f["MaillistTableName"];
      $_QljJi = $_QLO0f["GroupsTableName"];
      $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
      $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
    }

    $_QLfol = "SELECT COUNT(`ml_groups_id`) FROM `$_80LOl[GroupsTableName]`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_jj6f1 = 0;
    if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
      $_jj6f1 = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }

    $_jjfiO = 0;
    if($_jj6f1 > 0){
      $_QLfol = "SELECT COUNT(`ml_groups_id`) FROM `$_80LOl[NotInGroupsTableName]`";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
        $_jjfiO = $_QLO0f[0];
        mysql_free_result($_QL8i1);
      }
    }

    if($_jj6f1 > 1) {
      $_jjOlo = "DISTINCT `$_I8I6o`.`u_EMail`,";
    } else
      $_jjOlo = "";
    $_QLfol = "SELECT $_jjOlo `$_I8I6o`.*, ".$_QLl1Q;
    $_QLfol .= "IF(`$_I8I6o`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_I8I6o`.`u_Birthday`), 0) AS `MembersAge`, ".$_QLl1Q;
    $_QLfol .= "`$_80LOl[ML_RM_RefTableName]`.`RSS_GUIDs`".$_QLl1Q;
    $_QLfol .= " FROM `$_I8I6o`".$_QLl1Q;

    $_QLfol .= " LEFT JOIN `$_80LOl[ML_RM_RefTableName]` ON `$_80LOl[ML_RM_RefTableName]`.`Member_id`=`$_I8I6o`.`id`".$_QLl1Q;

    $_QLfol .= " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail`=`$_I8I6o`.`u_EMail`".$_QLl1Q;
    $_QLfol .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail`=`$_I8I6o`.`u_EMail`".$_QLl1Q;

    if($_jj6f1 > 0) {
      $_QLfol .= " LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
      $_QLfol .= " LEFT JOIN `$_80LOl[GroupsTableName]` ON `$_80LOl[GroupsTableName]`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
      if($_jjfiO > 0) {
        $_QLfol .= "  LEFT JOIN ( ".$_QLl1Q;

        $_QLfol .= "    SELECT `$_I8I6o`.`id`".$_QLl1Q;
        $_QLfol .= "    FROM `$_I8I6o`".$_QLl1Q;

        $_QLfol .= "    LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
        $_QLfol .= "    LEFT JOIN `$_80LOl[NotInGroupsTableName]` ON".$_QLl1Q;
        $_QLfol .= "    `$_80LOl[NotInGroupsTableName]`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
        $_QLfol .= "    WHERE `$_80LOl[NotInGroupsTableName]`.`ml_groups_id` IS NOT NULL".$_QLl1Q;

        $_QLfol .= "  ) AS `subquery` ON `subquery`.`id`=`$_I8I6o`.`id`".$_QLl1Q;
      }
    }

    $_QLfol .= " WHERE (`$_I8I6o`.`IsActive`=1 AND `$_I8I6o`.`SubscriptionStatus`<>'OptInConfirmationPending')".$_QLl1Q;
    $_QLfol .= " AND (`$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL) ".$_QLl1Q;
    if($_jj6f1 > 0) {
      $_QLfol .= " AND (`$_80LOl[GroupsTableName]`.`ml_groups_id` IS NOT NULL)".$_QLl1Q;
      if($_jjfiO > 0) {
       $_QLfol .= " AND (`subquery`.`id` IS NULL)".$_QLl1Q;
      }
    }
    
    return $_QLfol;
  }

  function _JQCJC($_j11Lo, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_jJLQo, $_QLttI;
    $_QLfol = "SELECT * FROM `$_jJLQo` WHERE `id`=".intval($_j11Lo);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    return _JQDJ1($_QLO0f, $_jLiOt, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }

  function _JQDJ1($_80LOl, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLttI;
    $_jLiOt = _JQBFE($_80LOl, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);

    $_QL8i1 = mysql_query($_jLiOt, $_QLttI);
    if(mysql_error($_QLttI) == "") {
      return mysql_num_rows($_QL8i1);
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_QLttI)."<br />";
    }

    return 0;
  }

  function _JQE01($_80LOl, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLttI;
    $_jLiOt = _JQBFE($_80LOl, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);

    $_QLfol = $_jLiOt;

    $_J0Lo8 = $_I8I6o;
    if($_J0Lo8 == ""){
      $_J0Lo8 = substr($_QLfol, 0, strpos($_QLfol, "."));
      $_J0Lo8 = substr($_J0Lo8, strpos($_J0Lo8, " ") + 1);
      if(strpos($_J0Lo8, " ") !== false)
        $_J0Lo8 = substr($_J0Lo8, strpos($_J0Lo8, " ") + 1);
    }

    $_QLlO6 = substr($_QLfol, 0, strpos($_QLfol, ' ') + 1);
    $_jLI6l = substr($_QLfol, strpos($_QLfol, 'FROM'));
    if(strpos($_jLiOt, "DISTINCT ") !== false) // NO `
       $_QLfol = $_QLlO6." COUNT(DISTINCT $_J0Lo8.`u_EMail`) ".$_jLI6l;
       else
       $_QLfol = $_QLlO6." COUNT($_J0Lo8.`u_EMail`) ".$_jLI6l;

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_error($_QLttI) == "") {
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      return $_QLO0f[0];
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_QLttI)."<br />";
    }

    return 0;
  }

  function _JQE18($_j11Lo, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_jJLQo, $_QLttI;
    $_QLfol = "SELECT * FROM `$_jJLQo` WHERE `id`=".intval($_j11Lo);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    return _JQE01($_QLO0f, $_jLiOt, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }


?>

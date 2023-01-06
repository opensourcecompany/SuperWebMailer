<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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

  function _LOQFJ($_J0LQQ, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLi60, $_QLttI;
    $_QLfol = "SELECT * FROM `$_QLi60` WHERE `id`=".intval($_J0LQQ);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLL16 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    return _LOOCQ($_QLL16, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }

  function _LOOCQ($_QLL16, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QL88I, $_I8tfQ, $_QLl1Q, $_QLttI, $_QLi60;

    if($_I8I6o == "") {
      $_QLfol = "SELECT `MaillistTableName`, `GroupsTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName` FROM `$_QL88I` WHERE `id`=$_QLL16[maillists_id]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_I8I6o = $_QLO0f["MaillistTableName"];
      $_QljJi = $_QLO0f["GroupsTableName"];
      $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
      $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
    }

    $_QLfol = "SELECT COUNT(`ml_groups_id`) FROM `$_QLL16[GroupsTableName]` WHERE `Campaigns_id`=$_QLL16[id]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_jj6f1 = 0;
    if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
      $_jj6f1 = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }

    $_jjfiO = 0;
    if($_jj6f1 > 0){
      $_QLfol = "SELECT COUNT(`ml_groups_id`) FROM `$_QLL16[NotInGroupsTableName]` WHERE `Campaigns_id`=$_QLL16[id]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
        $_jjfiO = $_QLO0f[0];
        mysql_free_result($_QL8i1);
      }
    }

    if($_jj6f1 > 1 || $_QLL16["DestCampaignAction"] == 1) {
      $_jjOlo = "DISTINCT `$_I8I6o`.`u_EMail`,";
    } else
      $_jjOlo = "";
    # !! cronsplit_tests.inc.php checks for `MembersAge` as last value before FROM !!
    $_QLfol = "SELECT $_jjOlo `$_I8I6o`.*, IF(`$_I8I6o`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_I8I6o`.`u_Birthday`), 0) AS `MembersAge` FROM `$_I8I6o`".$_QLl1Q;

    $_QLfol .= " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail`=`$_I8I6o`.`u_EMail`".$_QLl1Q;
    $_QLfol .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail`=`$_I8I6o`.`u_EMail`".$_QLl1Q;

    if($_jj6f1 > 0) {
      $_QLfol .= " LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
      $_QLfol .= " LEFT JOIN `$_QLL16[GroupsTableName]` ON `$_QLL16[GroupsTableName]`.`Campaigns_id`=$_QLL16[id] AND `$_QLL16[GroupsTableName]`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
      if($_jjfiO > 0) {
        $_QLfol .= "  LEFT JOIN ( ".$_QLl1Q;

        $_QLfol .= "    SELECT `$_I8I6o`.`id`".$_QLl1Q;
        $_QLfol .= "    FROM `$_I8I6o`".$_QLl1Q;

        $_QLfol .= "    LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
        $_QLfol .= "    LEFT JOIN `$_QLL16[NotInGroupsTableName]` ON".$_QLl1Q;
        $_QLfol .= "    `$_QLL16[NotInGroupsTableName]`.`Campaigns_id`=$_QLL16[id] AND `$_QLL16[NotInGroupsTableName]`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
        $_QLfol .= "    WHERE `$_QLL16[NotInGroupsTableName]`.`ml_groups_id` IS NOT NULL".$_QLl1Q;

        $_QLfol .= "  ) AS `subquery` ON `subquery`.`id`=`$_I8I6o`.`id`".$_QLl1Q;
      }
    }

    $_J0Loj = "";
    if($_QLL16["DestCampaignAction"] == 1){

      if( $_QLL16["DestCampaignActionId"] > 0 && $_QLL16["DestCampaignActionId"] != $_QLL16["id"] ){
        // get tables from other campaign
        $_IlJlC = "SELECT `RStatisticsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksByRecipientTableName` FROM `$_QLi60` WHERE `id`=$_QLL16[DestCampaignActionId]";
        $_I1O6j = mysql_query($_IlJlC, $_QLttI);
        if($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)){
          foreach($_I1OfI as $key => $_QltJO)
            $_QLL16[$key] = $_QltJO;
          mysql_free_result($_I1O6j);
        }
      }

      switch ($_QLL16["DestCampaignActionLastRecipientsAction"]) {
          case 'WasSent':
              $_QLfol .= " LEFT JOIN `$_QLL16[RStatisticsTableName]` ON (`$_QLL16[RStatisticsTableName]`.`SendStat_id`=$_QLL16[DestCampaignActionSentEntry_id] AND `$_I8I6o`.`id`=`$_QLL16[RStatisticsTableName]`.`recipients_id` AND `$_QLL16[RStatisticsTableName]`.`Send`='Sent')".$_QLl1Q;
              $_J0Loj .= " `$_QLL16[RStatisticsTableName]`.`recipients_id` IS NOT NULL";
              break;
          case 'WasNotSent':
              $_QLfol .= " LEFT JOIN `$_QLL16[RStatisticsTableName]` ON (`$_QLL16[RStatisticsTableName]`.`SendStat_id`=$_QLL16[DestCampaignActionSentEntry_id] AND `$_I8I6o`.`id`=`$_QLL16[RStatisticsTableName]`.`recipients_id` AND `$_QLL16[RStatisticsTableName]`.`Send`='Sent')".$_QLl1Q;
              $_J0Loj .= " `$_QLL16[RStatisticsTableName]`.`recipients_id` IS NULL";
              break;
          case 'WasOpened':
              $_QLfol .= " LEFT JOIN `$_QLL16[TrackingOpeningsByRecipientTableName]` ON (`$_QLL16[TrackingOpeningsByRecipientTableName]`.`SendStat_id`=$_QLL16[DestCampaignActionSentEntry_id] AND `$_I8I6o`.`id`=`$_QLL16[TrackingOpeningsByRecipientTableName]`.`Member_id`)".$_QLl1Q;
              $_J0Loj .= " `$_QLL16[TrackingOpeningsByRecipientTableName]`.`Member_id` IS NOT NULL";
              break;
          case 'WasNotOpened':
              $_QLfol .= " LEFT JOIN `$_QLL16[TrackingOpeningsByRecipientTableName]` ON (`$_QLL16[TrackingOpeningsByRecipientTableName]`.`SendStat_id`=$_QLL16[DestCampaignActionSentEntry_id] AND `$_I8I6o`.`id`=`$_QLL16[TrackingOpeningsByRecipientTableName]`.`Member_id`)".$_QLl1Q;
              $_J0Loj .= " `$_QLL16[TrackingOpeningsByRecipientTableName]`.`Member_id` IS NULL";
              break;
          case 'HasLinkClicked':
              $_QLfol .= " LEFT JOIN `$_QLL16[TrackingLinksByRecipientTableName]` ON (`$_QLL16[TrackingLinksByRecipientTableName]`.`SendStat_id`=$_QLL16[DestCampaignActionSentEntry_id] AND `$_I8I6o`.`id`=`$_QLL16[TrackingLinksByRecipientTableName]`.`Member_id`)".$_QLl1Q;
              $_J0Loj .= " `$_QLL16[TrackingLinksByRecipientTableName]`.`Member_id` IS NOT NULL";
              break;
          case 'HasSpecialLinkClicked':
              $_QLfol .= " LEFT JOIN `$_QLL16[TrackingLinksByRecipientTableName]` ON (`$_QLL16[TrackingLinksByRecipientTableName]`.`SendStat_id`=$_QLL16[DestCampaignActionSentEntry_id] AND `$_I8I6o`.`id`=`$_QLL16[TrackingLinksByRecipientTableName]`.`Member_id` AND `$_QLL16[TrackingLinksByRecipientTableName]`.`Links_id`=$_QLL16[DestCampaignActionLastRecipientsActionLink_id])".$_QLl1Q;
              $_J0Loj .= " `$_QLL16[TrackingLinksByRecipientTableName]`.`Member_id` IS NOT NULL";
              break;
      }
    }

    $_QLfol .= " WHERE (`$_I8I6o`.`IsActive`=1 AND `$_I8I6o`.`SubscriptionStatus`<>'OptInConfirmationPending')".$_QLl1Q;
    $_QLfol .= " AND (`$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL) ".$_QLl1Q;
    if($_jj6f1 > 0) {
      $_QLfol .= " AND (`$_QLL16[GroupsTableName]`.`ml_groups_id` IS NOT NULL)".$_QLl1Q;
      if($_jjfiO > 0) {
       $_QLfol .= " AND (`subquery`.`id` IS NULL)".$_QLl1Q;
      }
    }

    if($_J0Loj)
       $_QLfol .= " AND ($_J0Loj)".$_QLl1Q;

    if(isset($_QLL16["SendRules"]) && $_QLL16["SendRules"] != "") {
        $_jioJ6 = @unserialize($_QLL16["SendRules"]);
        if($_jioJ6 === false)
          $_jioJ6 = array();
      }
      else
      $_jioJ6 = array();

    $_QLlO6 = array();
    for($_Qli6J=0; $_Qli6J<count($_jioJ6); $_Qli6J++) {
      $_QLlO6[] = array("sql" => _LORCA($_jioJ6[$_Qli6J], $_I8I6o), "logicaloperator" => $_jioJ6[$_Qli6J]["logicaloperator"] );
    }

    if(count($_QLlO6) > 0) {
      $_QLfol .= " AND ".$_QLl1Q."( ".$_QLl1Q;

      $_jf8JI = "";
      for($_Qli6J=0; $_Qli6J<count($_QLlO6); $_Qli6J++) {
        if ($_Qli6J % 2 == 0)
          $_jf8JI = $_jf8JI . " (  ". $_QLlO6[$_Qli6J]["sql"];
          else {
           $_jf8JI = $_jf8JI . " ". $_QLlO6[$_Qli6J - 1]["logicaloperator"] . " ". $_QLlO6[$_Qli6J]["sql"] . "  ) ";
           if($_Qli6J != count($_QLlO6) - 1)
              $_jf8JI .= $_QLl1Q.$_QLlO6[$_Qli6J]["logicaloperator"];
          }
      }
      if ( (count($_QLlO6) - 1) % 2 == 0)
         $_jf8JI .= "  )";

      $_QLfol .= $_jf8JI.$_QLl1Q.")".$_QLl1Q;

      }

    return $_QLfol;
  }

  function _LOL8J($_J0LQQ, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLi60, $_QLttI;
    $_QLfol = "SELECT * FROM `$_QLi60` WHERE `id`=".intval($_J0LQQ);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLL16 = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    return _LOJJJ($_QLL16, $_jLiOt, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }

  function _LOJJJ($_QLL16, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLttI;
    $_jLiOt = _LOOCQ($_QLL16, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);

    $_QL8i1 = mysql_query($_jLiOt, $_QLttI);
    if(mysql_error($_QLttI) == "") {
      return mysql_num_rows($_QL8i1);
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_QLttI)."<br />";
    }

    return 0;
  }

  function _LO6LA($_QLL16, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLttI;
    $_jLiOt = _LOOCQ($_QLL16, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);

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

  function _LO6DQ($_J0LQQ, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLi60, $_QLttI;
    $_QLfol = "SELECT * FROM `$_QLi60` WHERE `id`=".intval($_J0LQQ);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLL16 = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    return _LO6LA($_QLL16, $_jLiOt, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }

  function _LO6E8($_QLJfI){
    $_J0loJ = array("NOW()", "CURRENT_DATE()", "CURDATE()", "CURRENT_TIME()", "CURTIME()", "TIMESTAMP(", "ADDDATE(", 
    "ADDTIME(", "DATE(", "DATEDIFF(", "DATE_ADD(", "DATE_SUB(", "DAY(", "DAYOFMONTH(", "DAYOFWEEK(", "DAYOFYEAR(", 
    "HOUR(", "LAST_DAY(", "MINUTE(", "MONTH(", "QUARTER(", "SECOND(", "TIME(", "WEEK(", "WEEKDAY(", "WEEKOFYEAR(", "YEAR(", "YEARWEEK(");

    if(in_arrayi($_QLJfI, $_J0loJ) !== false)
      return $_QLJfI;
      else
      for($_Qli6J=0; $_Qli6J<count($_J0loJ); $_Qli6J++){
        if(stripos($_QLJfI, $_J0loJ[$_Qli6J]) !== false){
          return $_QLJfI;
        }
      }

    return _LRAFO($_QLJfI);
  }
  
  function _LORCA($_jQoQi, $_I8I6o) {
    global $_QLl1Q;

    $_J10jo = array("id", "u_UserFieldInt1", "u_UserFieldInt2", "u_UserFieldInt3", "u_UserFieldBool1", "u_UserFieldBool2", "u_UserFieldBool3", "u_PersonalizedTracking");

    $_QLJfI = " (";

    # 0 value
    $_J1068 = '""';
    if(strpos($_jQoQi["operator"], "_num") !== false || in_array($_jQoQi["fieldname"], $_J10jo) )
      $_J1068 = '0';

    if($_jQoQi["operator"] != "is")
      $_IliOC = "COALESCE(`$_I8I6o`" . "." . '`'.$_jQoQi["fieldname"].'`, '.$_J1068.') ';
      else
      $_IliOC = "`$_I8I6o`" . "." . '`'.$_jQoQi["fieldname"].'` ';


    if($_jQoQi["fieldname"] == 'MembersAge')
      $_IliOC = " IF(`$_I8I6o`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_I8I6o`.`u_Birthday`), 0) ";

    switch($_jQoQi["operator"]) {
      case "eq":
           $_QLJfI .= $_IliOC."="._LO6E8($_jQoQi["comparestring"]);
           break;
      case "neq":
           $_QLJfI .= $_IliOC."<>"._LO6E8($_jQoQi["comparestring"]);
           break;
      case "lt":
           $_QLJfI .= $_IliOC."<"._LO6E8($_jQoQi["comparestring"]);
           break;
      case "gt":
           $_QLJfI .= $_IliOC.">"._LO6E8($_jQoQi["comparestring"]);
           break;
      case "eq_num":
           $_QLJfI .= $_IliOC."=".intval($_jQoQi["comparestring"]);
           break;
      case "neq_num":
           $_QLJfI .= $_IliOC."<>".intval($_jQoQi["comparestring"]);
           break;
      case "lt_num":
           $_QLJfI .= $_IliOC."<".intval($_jQoQi["comparestring"]);
           break;
      case "gt_num":
           $_QLJfI .= $_IliOC.">".intval($_jQoQi["comparestring"]);
           break;
      case "contains":
           $_QLJfI .= "LOCATE("._LRAFO($_jQoQi["comparestring"]).", $_IliOC) > 0";
           break;
      case "contains_not":
           $_QLJfI .= "LOCATE("._LRAFO($_jQoQi["comparestring"]).", $_IliOC) = 0";
           break;
      case "starts_with":
           $_QLJfI .= "LOCATE("._LRAFO($_jQoQi["comparestring"]).", $_IliOC) = 1";
           break;
      case "REGEXP":
           $_QLJfI .= "$_IliOC REGEXP '".$_jQoQi["comparestring"]."' > 0";
           break;
      case "is":
           $_QLJfI .= $_IliOC." IS ".$_jQoQi["comparestring"];
           break;
    }

    $_QLJfI .= ")$_QLl1Q";

    return $_QLJfI;
  }

  function _LO8R8($_ji0I0, $_J10o0, $_j01OI) {
    global $_Ijt0i, $_QLttI;

    $_j01OI = intval($_j01OI);
    $_QLfol = "SELECT `$_Ijt0i`.* FROM `$_J10o0` LEFT JOIN `$_ji0I0` ON `$_ji0I0`.`mtas_id`=`$_J10o0`.`mtas_id` LEFT JOIN `$_Ijt0i` ON `$_Ijt0i`.`id` = `$_ji0I0`.`mtas_id` WHERE `$_ji0I0`.`SendStat_id`=$_j01OI AND (`$_ji0I0`.`MailCount` < `$_Ijt0i`.`MailLimit` OR `$_Ijt0i`.`MailLimit` <= 0) ORDER BY `$_J10o0`.`sortorder` LIMIT 0, 1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_num_rows($_QL8i1) == 0) {
       // reset to zero
       $_QLfol = "UPDATE `$_ji0I0` SET `MailCount`=0 WHERE `$_ji0I0`.`SendStat_id`=$_j01OI";
       mysql_query($_QLfol, $_QLttI);
       return _LO8R8($_ji0I0, $_J10o0, $_j01OI);
    }

    $_J00C0 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "UPDATE `$_ji0I0` SET `MailCount`=`MailCount` + 1 WHERE `mtas_id`=$_J00C0[id] AND `SendStat_id`=$_j01OI";
    mysql_query($_QLfol, $_QLttI);
    return $_J00C0;
  }

  function _LO8EB($_J0LQQ){
    global $_jJL88, $_QLttI;
    $_J0LQQ = intval($_J0LQQ);
    $_QLfol = "SELECT `CampaignsForSplitTestTableName` FROM `$_jJL88`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_j1881 = 0;
    while( $_QLO0f = mysql_fetch_assoc($_QL8i1) ){
      $_QLfol = "SELECT COUNT(id) FROM `$_QLO0f[CampaignsForSplitTestTableName]` WHERE `Campaigns_id`=$_J0LQQ";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      if($_jjJfo) {
        $_jj6L6 = mysql_fetch_row($_jjJfo);
        $_j1881 += $_jj6L6[0];
        mysql_free_result($_jjJfo);
      }
    }
    mysql_free_result($_QL8i1);
    return $_j1881;
  }

  function _LOP86($_J0LQQ){
    global $_I616t, $_QLttI;
    $_J0LQQ = intval($_J0LQQ);
    $_j1881= 0;
    // action based fum responders only
    $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `ResponderType`=1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while( $_QLO0f = mysql_fetch_assoc($_QL8i1) ){
      $_QLfol= "SELECT id FROM `$_QLO0f[FUMailsTableName]` WHERE `sort_order`=1 AND `FirstRecipientsAction` <> 'Subscribed' AND `FirstRecipientsActionCampaign_id`=$_J0LQQ";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_j1881 += mysql_num_rows($_I1O6j);
      mysql_free_result($_I1O6j);
      if($_j1881) break;
    }
    mysql_free_result($_QL8i1);
    return $_j1881;
  }

  function _LOAO1($_J0LQQ, $_J10lj){
    global $_I616t, $_QLttI;
    $_J0LQQ = intval($_J0LQQ);
    $_j1881= 0;
    // action based fum responders only
    $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `ResponderType`=1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while( $_QLO0f = mysql_fetch_assoc($_QL8i1) ){
      $_QLfol= "SELECT id FROM `$_QLO0f[FUMailsTableName]` WHERE `sort_order`=1 AND `FirstRecipientsAction`='CampaignSpecialLinkClicked' AND `FirstRecipientsActionCampaign_id`=$_J0LQQ AND `FirstRecipientsActionCampaignLink_id`=$_J10lj";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_j1881 += mysql_num_rows($_I1O6j);
      mysql_free_result($_I1O6j);
      if($_j1881) break;
    }
    mysql_free_result($_QL8i1);
    return $_j1881;
  }

?>

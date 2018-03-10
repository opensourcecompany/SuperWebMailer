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
    include_once("functions.inc.php");
    include_once("templates.inc.php");
  }

  // no session check here!
  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;

  function _O610A($_jjI1t, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q6jOo, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE `id`=".intval($_jjI1t);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    return _O61RO($_Q6J0Q, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }

  function _O61RO($_Q6J0Q, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q60QL, $_Ql8C0, $_Q6JJJ, $_Q61I1, $_Q6jOo;

    if($_QlQC8 == "") {
      $_QJlJ0 = "SELECT `MaillistTableName`, `GroupsTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName` FROM `$_Q60QL` WHERE `id`=$_Q6J0Q[maillists_id]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
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

    if($_IO0Jo > 1 || $_Q6J0Q["DestCampaignAction"] == 1) {
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

    $_jjIO6 = "";
    if($_Q6J0Q["DestCampaignAction"] == 1){

      if( $_Q6J0Q["DestCampaignActionId"] > 0 && $_Q6J0Q["DestCampaignActionId"] != $_Q6J0Q["id"] ){
        // get tables from other campaign
        $_I6jtf = "SELECT `RStatisticsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksByRecipientTableName` FROM `$_Q6jOo` WHERE `id`=$_Q6J0Q[DestCampaignActionId]";
        $_Q8Oj8 = mysql_query($_I6jtf, $_Q61I1);
        if($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)){
          foreach($_Q8OiJ as $key => $_Q6ClO)
            $_Q6J0Q[$key] = $_Q6ClO;
          mysql_free_result($_Q8Oj8);
        }
      }

      switch ($_Q6J0Q["DestCampaignActionLastRecipientsAction"]) {
          case 'WasSent':
              $_QJlJ0 .= " LEFT JOIN `$_Q6J0Q[RStatisticsTableName]` ON (`$_Q6J0Q[RStatisticsTableName]`.`SendStat_id`=$_Q6J0Q[DestCampaignActionSentEntry_id] AND `$_QlQC8`.`id`=`$_Q6J0Q[RStatisticsTableName]`.`recipients_id` AND `$_Q6J0Q[RStatisticsTableName]`.`Send`='Sent')".$_Q6JJJ;
              $_jjIO6 .= " `$_Q6J0Q[RStatisticsTableName]`.`recipients_id` IS NOT NULL";
              break;
          case 'WasNotSent':
              $_QJlJ0 .= " LEFT JOIN `$_Q6J0Q[RStatisticsTableName]` ON (`$_Q6J0Q[RStatisticsTableName]`.`SendStat_id`=$_Q6J0Q[DestCampaignActionSentEntry_id] AND `$_QlQC8`.`id`=`$_Q6J0Q[RStatisticsTableName]`.`recipients_id` AND `$_Q6J0Q[RStatisticsTableName]`.`Send`='Sent')".$_Q6JJJ;
              $_jjIO6 .= " `$_Q6J0Q[RStatisticsTableName]`.`recipients_id` IS NULL";
              break;
          case 'WasOpened':
              $_QJlJ0 .= " LEFT JOIN `$_Q6J0Q[TrackingOpeningsByRecipientTableName]` ON (`$_Q6J0Q[TrackingOpeningsByRecipientTableName]`.`SendStat_id`=$_Q6J0Q[DestCampaignActionSentEntry_id] AND `$_QlQC8`.`id`=`$_Q6J0Q[TrackingOpeningsByRecipientTableName]`.`Member_id`)".$_Q6JJJ;
              $_jjIO6 .= " `$_Q6J0Q[TrackingOpeningsByRecipientTableName]`.`Member_id` IS NOT NULL";
              break;
          case 'WasNotOpened':
              $_QJlJ0 .= " LEFT JOIN `$_Q6J0Q[TrackingOpeningsByRecipientTableName]` ON (`$_Q6J0Q[TrackingOpeningsByRecipientTableName]`.`SendStat_id`=$_Q6J0Q[DestCampaignActionSentEntry_id] AND `$_QlQC8`.`id`=`$_Q6J0Q[TrackingOpeningsByRecipientTableName]`.`Member_id`)".$_Q6JJJ;
              $_jjIO6 .= " `$_Q6J0Q[TrackingOpeningsByRecipientTableName]`.`Member_id` IS NULL";
              break;
          case 'HasLinkClicked':
              $_QJlJ0 .= " LEFT JOIN `$_Q6J0Q[TrackingLinksByRecipientTableName]` ON (`$_Q6J0Q[TrackingLinksByRecipientTableName]`.`SendStat_id`=$_Q6J0Q[DestCampaignActionSentEntry_id] AND `$_QlQC8`.`id`=`$_Q6J0Q[TrackingLinksByRecipientTableName]`.`Member_id`)".$_Q6JJJ;
              $_jjIO6 .= " `$_Q6J0Q[TrackingLinksByRecipientTableName]`.`Member_id` IS NOT NULL";
              break;
          case 'HasSpecialLinkClicked':
              $_QJlJ0 .= " LEFT JOIN `$_Q6J0Q[TrackingLinksByRecipientTableName]` ON (`$_Q6J0Q[TrackingLinksByRecipientTableName]`.`SendStat_id`=$_Q6J0Q[DestCampaignActionSentEntry_id] AND `$_QlQC8`.`id`=`$_Q6J0Q[TrackingLinksByRecipientTableName]`.`Member_id` AND `$_Q6J0Q[TrackingLinksByRecipientTableName]`.`Links_id`=$_Q6J0Q[DestCampaignActionLastRecipientsActionLink_id])".$_Q6JJJ;
              $_jjIO6 .= " `$_Q6J0Q[TrackingLinksByRecipientTableName]`.`Member_id` IS NOT NULL";
              break;
      }
    }

    $_QJlJ0 .= " WHERE (`$_QlQC8`.`IsActive`=1 AND `$_QlQC8`.`SubscriptionStatus`<>'OptInConfirmationPending')".$_Q6JJJ;
    $_QJlJ0 .= " AND (`$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL) ".$_Q6JJJ;
    if($_IO0Jo > 0) {
      $_QJlJ0 .= " AND (`$_Q6J0Q[GroupsTableName]`.`ml_groups_id` IS NOT NULL)".$_Q6JJJ;
      if($_IO1I1 > 0) {
       $_QJlJ0 .= " AND (`subquery`.`id` IS NULL)".$_Q6JJJ;
      }
    }

    if($_jjIO6)
       $_QJlJ0 .= " AND ($_jjIO6)".$_Q6JJJ;

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

  function _O6QLR($_jjI1t, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q6jOo, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE `id`=".intval($_jjI1t);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6J0Q = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    return _O6Q8B($_Q6J0Q, $_jQIIi, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }

  function _O6Q8B($_Q6J0Q, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q61I1;
    $_jQIIi = _O61RO($_Q6J0Q, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);

    $_Q60l1 = mysql_query($_jQIIi, $_Q61I1);
    if(mysql_error($_Q61I1) == "") {
      return mysql_num_rows($_Q60l1);
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_Q61I1)."<br />";
    }

    return 0;
  }

  function _O6QAL($_Q6J0Q, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q61I1;
    $_jQIIi = _O61RO($_Q6J0Q, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);

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
    if(strpos($_jQIIi, "DISTINCT ") !== false) // NO `
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

  function _O6OLL($_jjI1t, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q6jOo, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE `id`=".intval($_jjI1t);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6J0Q = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    return _O6QAL($_Q6J0Q, $_jQIIi, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }

  function _O6L1O($_I8C10, $_QlQC8) {
    global $_Q6JJJ;
    $_QJCJi = " (";

    $_I6oQj = "`$_QlQC8`" . "." . '`'.$_I8C10["fieldname"].'` ';

    if($_I8C10["fieldname"] == 'MembersAge')
      $_I6oQj = " IF(`$_QlQC8`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_QlQC8`.`u_Birthday`), 0) ";

    switch($_I8C10["operator"]) {
      case "eq":
           $_QJCJi .= $_I6oQj."="._OPQLR($_I8C10["comparestring"]);
           break;
      case "neq":
           $_QJCJi .= $_I6oQj."<>"._OPQLR($_I8C10["comparestring"]);
           break;
      case "lt":
           $_QJCJi .= $_I6oQj."<"._OPQLR($_I8C10["comparestring"]);
           break;
      case "gt":
           $_QJCJi .= $_I6oQj.">"._OPQLR($_I8C10["comparestring"]);
           break;
      case "eq_num":
           $_QJCJi .= $_I6oQj."=".intval($_I8C10["comparestring"]);
           break;
      case "neq_num":
           $_QJCJi .= $_I6oQj."<>".intval($_I8C10["comparestring"]);
           break;
      case "lt_num":
           $_QJCJi .= $_I6oQj."<".intval($_I8C10["comparestring"]);
           break;
      case "gt_num":
           $_QJCJi .= $_I6oQj.">".intval($_I8C10["comparestring"]);
           break;
      case "contains":
           $_QJCJi .= "LOCATE("._OPQLR($_I8C10["comparestring"]).", $_I6oQj) > 0";
           break;
      case "contains_not":
           $_QJCJi .= "LOCATE("._OPQLR($_I8C10["comparestring"]).", $_I6oQj) = 0";
           break;
      case "starts_with":
           $_QJCJi .= "LOCATE("._OPQLR($_I8C10["comparestring"]).", $_I6oQj) = 1";
           break;
      case "REGEXP":
           $_QJCJi .= "$_I6oQj REGEXP '".$_I8C10["comparestring"]."' > 0";
           break;
      case "is":
           $_QJCJi .= $_I6oQj." IS ".$_I8C10["comparestring"];
           break;
    }

    $_QJCJi .= ")$_Q6JJJ";

    return $_QJCJi;
  }

  function _O6LLO($_j080i, $_jjJQf, $_If010) {
    global $_Qofoi, $_Q61I1;

    $_If010 = intval($_If010);
    $_QJlJ0 = "SELECT `$_Qofoi`.* FROM `$_jjJQf` LEFT JOIN `$_j080i` ON `$_j080i`.`mtas_id`=`$_jjJQf`.`mtas_id` LEFT JOIN `$_Qofoi` ON `$_Qofoi`.`id` = `$_j080i`.`mtas_id` WHERE `$_j080i`.`SendStat_id`=$_If010 AND (`$_j080i`.`MailCount` < `$_Qofoi`.`MailLimit` OR `$_Qofoi`.`MailLimit` <= 0) ORDER BY `$_jjJQf`.`sortorder` LIMIT 0, 1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_num_rows($_Q60l1) == 0) {
       // reset to zero
       $_QJlJ0 = "UPDATE `$_j080i` SET `MailCount`=0 WHERE `$_j080i`.`SendStat_id`=$_If010";
       mysql_query($_QJlJ0, $_Q61I1);
       return _O6LLO($_j080i, $_jjJQf, $_If010);
    }

    $_jIfO0 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "UPDATE `$_j080i` SET `MailCount`=`MailCount` + 1 WHERE `mtas_id`=$_jIfO0[id] AND `SendStat_id`=$_If010";
    mysql_query($_QJlJ0, $_Q61I1);
    return $_jIfO0;
  }

  function _O6LPE($_jjI1t){
    global $_IooOQ, $_Q61I1;
    $_jjI1t = intval($_jjI1t);
    $_QJlJ0 = "SELECT `CampaignsForSplitTestTableName` FROM `$_IooOQ`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_IflL6 = 0;
    while( $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ){
      $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6Q1C[CampaignsForSplitTestTableName]` WHERE `Campaigns_id`=$_jjI1t";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      if($_ItlJl) {
        $_IO08Q = mysql_fetch_row($_ItlJl);
        $_IflL6 += $_IO08Q[0];
        mysql_free_result($_ItlJl);
      }
    }
    mysql_free_result($_Q60l1);
    return $_IflL6;
  }

  function _O6JP8($_jjI1t){
    global $_QCLCI, $_Q61I1;
    $_jjI1t = intval($_jjI1t);
    $_IflL6= 0;
    // action based fum responders only
    $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `ResponderType`=1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while( $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ){
      $_QJlJ0= "SELECT id FROM `$_Q6Q1C[FUMailsTableName]` WHERE `sort_order`=1 AND `FirstRecipientsAction` <> 'Subscribed' AND `FirstRecipientsActionCampaign_id`=$_jjI1t";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_IflL6 += mysql_num_rows($_Q8Oj8);
      mysql_free_result($_Q8Oj8);
      if($_IflL6) break;
    }
    mysql_free_result($_Q60l1);
    return $_IflL6;
  }

  function _O66LD($_jjI1t, $_jjJoO){
    global $_QCLCI, $_Q61I1;
    $_jjI1t = intval($_jjI1t);
    $_IflL6= 0;
    // action based fum responders only
    $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `ResponderType`=1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while( $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ){
      $_QJlJ0= "SELECT id FROM `$_Q6Q1C[FUMailsTableName]` WHERE `sort_order`=1 AND `FirstRecipientsAction`='CampaignSpecialLinkClicked' AND `FirstRecipientsActionCampaign_id`=$_jjI1t AND `FirstRecipientsActionCampaignLink_id`=$_jjJoO";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_IflL6 += mysql_num_rows($_Q8Oj8);
      mysql_free_result($_Q8Oj8);
      if($_IflL6) break;
    }
    mysql_free_result($_Q60l1);
    return $_IflL6;
  }

?>

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


 function _OFOO0($Name, $_j0O0L, $_JLftf, $_jl6fQ, $_jlf11, $_J0QoI = 'PlainText', $_J0J1t = 'PlainText'){

  global $UserId, $OwnerUserId, $resourcestrings, $INTERFACE_LANGUAGE, $INTERFACE_THEMESID;
  global $_Q8f1L, $_Q60QL, $_Q6fio, $_ICljl, $_Q61I1;

  if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){

    if($OwnerUserId == 0) // ist es ein Admin?
       $_JLfCJ = $UserId;
       else
       $_JLfCJ = $OwnerUserId;

    $_QJlJ0 = "SELECT id FROM $_Q60QL WHERE users_id=".$_JLfCJ;
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_num_rows($_Q60l1) > 0) {
      return 0;
    }
    mysql_free_result($_Q60l1);
  }

  $_jjjjC = TablePrefix._OA0LA($Name);
  $_QlQC8 = _OALO0($_jjjjC."_members");
  $_ItCCo = _OALO0($_jjjjC."_localblocklist");
  $_jf1J1 = _OALO0($_jjjjC."_localdomainblocklist");
  $_QlIf6 = _OALO0($_jjjjC."_statistics");
  $_QLI8o = _OALO0($_jjjjC."_forms");
  $_j0tio = _OALO0($_jjjjC."_mtas");
  $_j8QJ8 = _OALO0($_jjjjC."_inboxes");
  $_Q6t6j = _OALO0($_jjjjC."_groups");
  $_QLI68 = _OALO0($_jjjjC."_maillisttogroups");
  $_QljIQ = _OALO0($_jjjjC."_maillog");
  $_Qljli = _OALO0($_jjjjC."_edit");
  $_I8Jtl = _OALO0($_jjjjC."_reasons");
  $_I86jt = _OALO0($_jjjjC."_reasonsstat");

  if($_J0QoI == "") $_J0QoI = "PlainText";
  if($_J0J1t == "") $_J0J1t = "PlainText";

  // get userdata and set it as default email address of mailinglist
  $_IIoQO = "";
  $_IQf88 = "";
  $_QJlJ0 = "SELECT EMail, FirstName, LastName FROM $_Q8f1L WHERE id=$UserId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_IIjlQ = mysql_fetch_array($_Q60l1);
  $_IIoQO = trim($_IIjlQ["FirstName"]." ".$_IIjlQ["LastName"]);
  $_IQf88 = $_IIjlQ["EMail"];
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "INSERT INTO $_Q60QL (CreateDate, users_id, MaillistTableName, GroupsTableName, MailListToGroupsTableName, LocalBlocklistTableName, LocalDomainBlocklistTableName, StatisticsTableName, FormsTableName, MTAsTableName, InboxesTableName, MailLogTableName, EditTableName, `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName`, Name, Description, SubscriptionType, UnsubscriptionType, SenderFromName, SenderFromAddress) ";
  $_QJlJ0 .= "VALUES(NOW(), $_JLftf, '$_QlQC8', '$_Q6t6j', '$_QLI68', '$_ItCCo', '$_jf1J1', '$_QlIf6', '$_QLI8o', '$_j0tio', '$_j8QJ8', '$_QljIQ', '$_Qljli', '$_I8Jtl', '$_I86jt', "._OPQLR($Name).", "._OPQLR($_j0O0L).", "._OPQLR($_jl6fQ).", "._OPQLR($_jlf11).", "._OPQLR($_IIoQO).", "._OPQLR($_IQf88)."   )";

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1)
    _OAL8F($_QJlJ0);
    else {
     $_Q60l1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
     $_Q6Q1C = mysql_fetch_array($_Q60l1);
     $_JL8I8 = $_Q6Q1C[0];
     mysql_free_result($_Q60l1);
    }

  $_Ij6Io = join("", file(_O68A8()."newmailinglist.sql"));
  $_Ij6Io = str_replace('`TABLE_MAILLIST`', $_QlQC8, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_GROUPS`', $_Q6t6j, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_MAILLISTTOGROUP`', $_QLI68, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_LOCALBLOCKLIST`', $_ItCCo, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_LOCALDOMAINBLOCKLIST`', $_jf1J1, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_STATISTICS`', $_QlIf6, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_FORMS`', $_QLI8o, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_MTAS`', $_j0tio, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_INBOXES`', $_j8QJ8, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_MAILLOG`', $_QljIQ, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_EDIT`', $_Qljli, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_REASONSFORUNSUBSCRIPE`', $_I8Jtl, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_REASONSFORUNSUBSCRIPESTATISTICS`', $_I86jt, $_Ij6Io);


  $_Ij6il = explode(";", $_Ij6Io);

  for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
    if(trim($_Ij6il[$_Q6llo]) == "") continue;
    $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8", $_Q61I1);
    if(!$_Q60l1)
      $_Q60l1 = mysql_query($_Ij6il[$_Q6llo], $_Q61I1);
    if(!$_Q60l1)
      _OAL8F($_Ij6il[$_Q6llo]);
  }

  // user
  // user hinzufuegen
  if($OwnerUserId != 0) { // ist es Kein Admin?
    $_QJlJ0 = "INSERT INTO $_Q6fio (users_id, maillists_id) VALUES($UserId, $_JL8I8)";
    mysql_query($_QJlJ0, $_Q61I1);
  }


  // forms default
  $_QJCJi = _OPQLR( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultSubscribeSubject"]) );
  $_QJCJi .= ", "._OPQLR( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultSubscribePlainMail"]) );
  $_QJCJi .= ", "._OPQLR( $resourcestrings[$INTERFACE_LANGUAGE]["DefaultSubscribeHTMLMail"] );
  $_QJCJi .= ", "._OPQLR( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsubscribeSubject"]) );
  $_QJCJi .= ", "._OPQLR( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsubscribePlainMail"]) );
  $_QJCJi .= ", "._OPQLR( $resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsubscribeHTMLMail"] );

  $_QJCJi .= ", "._OPQLR( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditSubject"]) );
  $_QJCJi .= ", "._OPQLR( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditPlainMail"]) );
  $_QJCJi .= ", "._OPQLR( $resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditHTMLMail"] );

  $_QJlJ0 = "INSERT INTO `$_QLI8o` (`id`, `IsDefault`, `CreateDate`, `messages_id`, `Name`, `Language`, `ThemesId`, `fields`, `OptInConfirmationMailFormat`, `OptOutConfirmationMailFormat`, `OptInConfirmationMailSubject`, `OptInConfirmationMailPlainText`, `OptInConfirmationMailHTMLText`, `OptOutConfirmationMailSubject`, `OptOutConfirmationMailPlainText`, `OptOutConfirmationMailHTMLText`, `EditConfirmationMailSubject`, `EditConfirmationMailPlainText`, `EditConfirmationMailHTMLText`) VALUES";
  $_QJlJ0 .= "(1, 1, NOW(), 1, 'Standard', '$INTERFACE_LANGUAGE', $INTERFACE_THEMESID, 'a:1:{s:7:".'"u_EMail";s:15:'.'"visiblerequired"' . ";}', '$_J0QoI', '$_J0J1t', $_QJCJi)";

  $_QJlJ0 = utf8_encode($_QJlJ0);
  mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  // MTA setzen
  $_QJlJ0 = "INSERT INTO $_j0tio SET mtas_id=1, sortorder=0";
  mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);


  $_I6IJ8 = array();
  $_QJlJ0 = "SELECT * FROM `$_ICljl` WHERE `IsDefault`=1 ORDER BY `id`";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     switch( $_Q6Q1C["Type"]) {
      case 'Subscribe':
          $_I6IJ8["SubscribeConfirmationPage"] = $_Q6Q1C["id"];
          break;
      case 'Unsubscribe':
          if(!isset($_I6IJ8["UnsubscribeConfirmationPage"]))
             $_I6IJ8["UnsubscribeConfirmationPage"] = $_Q6Q1C["id"];
          break;
      case 'Error':
          $_I6IJ8["SubscribeErrorPage"] = $_Q6Q1C["id"];
          $_I6IJ8["UnsubscribeErrorPage"] = $_Q6Q1C["id"];
          break;
      case 'SubscribeConfirmation':
          $_I6IJ8["SubscribeAcceptedPage"] = $_Q6Q1C["id"];
          break;
      case 'UnsubscribeConfirmation':
          $_I6IJ8["UnsubscribeAcceptedPage"] = $_Q6Q1C["id"];
          break;
      case 'Edit':
          $_I6IJ8["EditConfirmationPage"] = $_Q6Q1C["id"];
          break;
      case 'EditConfirmation':
          $_I6IJ8["EditAcceptedPage"] = $_Q6Q1C["id"];
          break;
      case 'EditReject':
          $_I6IJ8["EditRejectPage"] = $_Q6Q1C["id"];
          break;
      case 'EditError':
          $_I6IJ8["EditErrorPage"] = $_Q6Q1C["id"];
          break;
      case 'RFUSurveyConfirmation':
          $_I6IJ8["RFUSurveyConfirmationPage"] = $_Q6Q1C["id"];
          break;
     }
  }
  mysql_free_result($_Q60l1);

  $_QJlJ0 = array();
  foreach($_I6IJ8 as $key => $_Q6ClO){
   $_QJlJ0[] .= "`$key`="._OPQLR($_Q6ClO);
  }

  if(count($_QJlJ0) > 0) {
    $_QJlJ0 = "UPDATE `$_QLI8o` SET ".join(", ", $_QJlJ0);
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
  }

  $_IoioQ = explode(";", $resourcestrings[$INTERFACE_LANGUAGE]["SampleUnsubscripeReasons"]);

  for($_Q6llo = 0; $_Q6llo < count($_IoioQ); $_Q6llo++){
    $_JL881 = "Radio";
    if($_Q6llo == count($_IoioQ) - 1)
      $_JL881 = "Text";
    $_QJlJ0 = "INSERT INTO `$_I8Jtl` SET `forms_id`=1, `Reason`=". _OPQLR($_IoioQ[$_Q6llo]) .", `ReasonType`='$_JL881', `sort_order`=". ($_Q6llo + 1);
    mysql_query($_QJlJ0, $_Q61I1);
  }

  return $_JL8I8;
 }

?>

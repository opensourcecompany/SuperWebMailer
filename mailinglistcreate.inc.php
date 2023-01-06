<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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


 function _LF1BP($Name, $_jiILL, $_fjiif, $_601fI, $_601Oj, $_60lt8 = 'PlainText', $_611jL = 'PlainText'){

  global $UserId, $OwnerUserId, $resourcestrings, $INTERFACE_LANGUAGE, $INTERFACE_THEMESID;
  global $_I18lo, $_QL88I, $_QlQot, $_jfQtI, $_QLttI;

  if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){

    if($OwnerUserId == 0) // ist es ein Admin?
       $_fjL01 = $UserId;
       else
       $_fjL01 = $OwnerUserId;

    $_QLfol = "SELECT id FROM $_QL88I WHERE users_id=".$_fjL01;
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_num_rows($_QL8i1) > 0) {
      return 0;
    }
    mysql_free_result($_QL8i1);
  }

  $_J0Lo8 = TablePrefix._L8A8P($Name);
  $_I8I6o = _L8D00($_J0Lo8."_members");
  $_jjj8f = _L8D00($_J0Lo8."_localblocklist");
  $_Jj6f0 = _L8D00($_J0Lo8."_localdomainblocklist");
  $_I8jjj = _L8D00($_J0Lo8."_statistics");
  $_IfJoo = _L8D00($_J0Lo8."_forms");
  $_ji10i = _L8D00($_J0Lo8."_mtas");
  $_JJffC = _L8D00($_J0Lo8."_inboxes");
  $_QljJi = _L8D00($_J0Lo8."_groups");
  $_IfJ66 = _L8D00($_J0Lo8."_maillisttogroups");
  $_I8jLt = _L8D00($_J0Lo8."_maillog");
  $_I8Jti = _L8D00($_J0Lo8."_edit");
  $_jQIIl = _L8D00($_J0Lo8."_reasons");
  $_jQIt6 = _L8D00($_J0Lo8."_reasonsstat");

  if($_60lt8 == "") $_60lt8 = "PlainText";
  if($_611jL == "") $_611jL = "PlainText";

  // get userdata and set it as default email address of mailinglist
  $_ICfJQ = "";
  $_Io6Lf = "";
  $_QLfol = "SELECT EMail, FirstName, LastName FROM $_I18lo WHERE id=$UserId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_IC1oC = mysql_fetch_array($_QL8i1);
  $_ICfJQ = trim($_IC1oC["FirstName"]." ".$_IC1oC["LastName"]);
  $_Io6Lf = $_IC1oC["EMail"];
  mysql_free_result($_QL8i1);

  $_QLfol = "INSERT INTO $_QL88I (CreateDate, users_id, MaillistTableName, GroupsTableName, MailListToGroupsTableName, LocalBlocklistTableName, LocalDomainBlocklistTableName, StatisticsTableName, FormsTableName, MTAsTableName, InboxesTableName, MailLogTableName, EditTableName, `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName`, Name, Description, SubscriptionType, UnsubscriptionType, SenderFromName, SenderFromAddress) ";
  $_QLfol .= "VALUES(NOW(), $_fjiif, '$_I8I6o', '$_QljJi', '$_IfJ66', '$_jjj8f', '$_Jj6f0', '$_I8jjj', '$_IfJoo', '$_ji10i', '$_JJffC', '$_I8jLt', '$_I8Jti', '$_jQIIl', '$_jQIt6', "._LRAFO($Name).", "._LRAFO($_jiILL).", "._LRAFO($_601fI).", "._LRAFO($_601Oj).", "._LRAFO($_ICfJQ).", "._LRAFO($_Io6Lf)."   )";

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1)
    _L8D88($_QLfol);
    else {
     $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
     $_QLO0f = mysql_fetch_array($_QL8i1);
     $_fjLIf = $_QLO0f[0];
     mysql_free_result($_QL8i1);
    }

  $_IiIlQ = join("", file(_LOCFC()."newmailinglist.sql"));
  $_IiIlQ = str_replace('`TABLE_MAILLIST`', $_I8I6o, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_GROUPS`', $_QljJi, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_MAILLISTTOGROUP`', $_IfJ66, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_LOCALBLOCKLIST`', $_jjj8f, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_LOCALDOMAINBLOCKLIST`', $_Jj6f0, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_STATISTICS`', $_I8jjj, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_FORMS`', $_IfJoo, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_MTAS`', $_ji10i, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_INBOXES`', $_JJffC, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_MAILLOG`', $_I8jLt, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_EDIT`', $_I8Jti, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_REASONSFORUNSUBSCRIPE`', $_jQIIl, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_REASONSFORUNSUBSCRIPESTATISTICS`', $_jQIt6, $_IiIlQ);


  $_IijLl = explode(";", $_IiIlQ);

  for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
    if(trim($_IijLl[$_Qli6J]) == "") continue;
    $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET="  . DefaultMySQLEncoding, $_QLttI);
    if(!$_QL8i1)
      $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
    if(!$_QL8i1)
      _L8D88($_IijLl[$_Qli6J]);
  }

  // user
  // user hinzufuegen
  if($OwnerUserId != 0) { // ist es Kein Admin?
    $_QLfol = "INSERT INTO $_QlQot (users_id, maillists_id) VALUES($UserId, $_fjLIf)";
    mysql_query($_QLfol, $_QLttI);
  }

  $_60IIC = "";
  $_QLfol = "SELECT `PrivacyPolicyURL` FROM $_I18lo WHERE id=$UserId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1){
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    $_60IIC = $_I1OfI["PrivacyPolicyURL"];
    mysql_free_result($_QL8i1);
  }


  // forms default
  $_QLJfI = _LRAFO( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultSubscribeSubject"]) );
  $_QLJfI .= ", "._LRAFO( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultSubscribePlainMail"]) );
  $_QLJfI .= ", "._LRAFO( $resourcestrings[$INTERFACE_LANGUAGE]["DefaultSubscribeHTMLMail"] );
  $_QLJfI .= ", "._LRAFO( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsubscribeSubject"]) );
  $_QLJfI .= ", "._LRAFO( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsubscribePlainMail"]) );
  $_QLJfI .= ", "._LRAFO( $resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsubscribeHTMLMail"] );

  $_QLJfI .= ", "._LRAFO( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditSubject"]) );
  $_QLJfI .= ", "._LRAFO( utf8_decode($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditPlainMail"]) );
  $_QLJfI .= ", "._LRAFO( $resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditHTMLMail"] );
  $_QLJfI .= ", "._LRAFO( $resourcestrings[$INTERFACE_LANGUAGE]["DefaultGroupsDescriptionLabel"] );
  $_QLJfI .= ", "._LRAFO( $_60IIC );
  $_QLJfI .= ", "._LRAFO( $resourcestrings[$INTERFACE_LANGUAGE]["DefaultPrivacyPolicyURLText"] );


  $_QLfol = "INSERT INTO `$_IfJoo` (`id`, `IsDefault`, `CreateDate`, `messages_id`, `Name`, `Language`, `ThemesId`, `fields`, `OptInConfirmationMailFormat`, `OptOutConfirmationMailFormat`, `OptInConfirmationMailSubject`, `OptInConfirmationMailPlainText`, `OptInConfirmationMailHTMLText`, `OptOutConfirmationMailSubject`, `OptOutConfirmationMailPlainText`, `OptOutConfirmationMailHTMLText`, `EditConfirmationMailSubject`, `EditConfirmationMailPlainText`, `EditConfirmationMailHTMLText`, `GroupsDescriptionLabel`, `PrivacyPolicyURL`, `PrivacyPolicyURLText`) VALUES";
  $_QLfol .= "(1, 1, NOW(), 1, 'Standard', '$INTERFACE_LANGUAGE', $INTERFACE_THEMESID, 'a:1:{s:7:".'"u_EMail";s:15:'.'"visiblerequired"' . ";}', '$_60lt8', '$_611jL', $_QLJfI)";

  $_QLfol = utf8_encode($_QLfol);
  mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  // Infobar
  if(defined("SWM")){

    _LBLPC($_j8l18, $_j8LoO);
    $_61tQQ = (count($_j8l18) && count($_j8LoO));

    $_QLfol = "UPDATE `$_IfJoo` SET `InfoBarActive`=$_61tQQ, `InfoBarSupportedTranslationLanguages`=" . _LRAFO( serialize($_j8l18) ) . ", `InfoBarLinksArray`=" . _LRAFO( serialize($_j8LoO) ) ;
    mysql_query($_QLfol, $_QLttI);

  }
  
  // MTA setzen
  $_QLfol = "INSERT INTO $_ji10i SET mtas_id=1, sortorder=0";
  mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);


  $_IlILC = array();
  $_QLfol = "SELECT * FROM `$_jfQtI` WHERE `IsDefault`=1 ORDER BY `id`";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     switch( $_QLO0f["Type"]) {
      case 'Subscribe':
          $_IlILC["SubscribeConfirmationPage"] = $_QLO0f["id"];
          break;
      case 'Unsubscribe':
          if(!isset($_IlILC["UnsubscribeConfirmationPage"]))
             $_IlILC["UnsubscribeConfirmationPage"] = $_QLO0f["id"];
          break;
      case 'Error':
          $_IlILC["SubscribeErrorPage"] = $_QLO0f["id"];
          $_IlILC["UnsubscribeErrorPage"] = $_QLO0f["id"];
          break;
      case 'SubscribeConfirmation':
          $_IlILC["SubscribeAcceptedPage"] = $_QLO0f["id"];
          break;
      case 'UnsubscribeConfirmation':
          $_IlILC["UnsubscribeAcceptedPage"] = $_QLO0f["id"];
          break;
      case 'Edit':
          $_IlILC["EditConfirmationPage"] = $_QLO0f["id"];
          break;
      case 'EditConfirmation':
          $_IlILC["EditAcceptedPage"] = $_QLO0f["id"];
          break;
      case 'EditReject':
          $_IlILC["EditRejectPage"] = $_QLO0f["id"];
          break;
      case 'EditError':
          $_IlILC["EditErrorPage"] = $_QLO0f["id"];
          break;
      case 'RFUSurveyConfirmation':
          $_IlILC["RFUSurveyConfirmationPage"] = $_QLO0f["id"];
          break;
     }
  }
  mysql_free_result($_QL8i1);

  $_QLfol = array();
  foreach($_IlILC as $key => $_QltJO){
   $_QLfol[] .= "`$key`="._LRAFO($_QltJO);
  }

  if(count($_QLfol) > 0) {
    $_QLfol = "UPDATE `$_IfJoo` SET ".join(", ", $_QLfol);
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
  }

  $_j60Q0 = explode(";", $resourcestrings[$INTERFACE_LANGUAGE]["SampleUnsubscripeReasons"]);

  for($_Qli6J = 0; $_Qli6J < count($_j60Q0); $_Qli6J++){
    $_fjLI8 = "Radio";
    if($_Qli6J == count($_j60Q0) - 1)
      $_fjLI8 = "Text";
    $_QLfol = "INSERT INTO `$_jQIIl` SET `forms_id`=1, `Reason`=". _LRAFO($_j60Q0[$_Qli6J]) .", `ReasonType`='$_fjLI8', `sort_order`=". ($_Qli6J + 1);
    mysql_query($_QLfol, $_QLttI);
  }

  return $_fjLIf;
 }

?>

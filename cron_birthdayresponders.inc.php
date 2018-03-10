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

  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("mail.php");
  include_once("mailcreate.inc.php");
  include_once("smsout.inc.php");

  function _OR01E(&$_j6O8O) {
    global $_Q61I1, $_Q6JJJ, $_jJt8t;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Qo8OO, $resourcestrings, $_Q8f1L, $_Q880O;
    global $_Qofoi, $_Ql8C0, $_Q60QL, $_QtjLI;
    global $_IIl8O, $_IjQIf;

    $_j6O8O = "Birthday responder checking starts...<br />";
    $_jIojl = 0;

    $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE UserType='Admin' AND IsActive>0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
      _OPQ6J();
      $UserId = $_Q6Q1C["id"];
      $OwnerUserId = 0;
      $Username = $_Q6Q1C["Username"];
      $UserType = $_Q6Q1C["UserType"];
      $AccountType = $_Q6Q1C["AccountType"];
      $INTERFACE_THEMESID = $_Q6Q1C["ThemesId"];
      $INTERFACE_LANGUAGE = $_Q6Q1C["Language"];

      _OP10J($INTERFACE_LANGUAGE);

      _LQLRQ($INTERFACE_LANGUAGE);

      $_QJlJ0 = "SELECT Theme FROM $_Q880O WHERE id=$INTERFACE_THEMESID";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_Q8OiJ[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);


      $_QJlJ0 = "SELECT $_IIl8O.MailSubject, $_IIl8O.id AS BirthdayResponders_id, $_IIl8O.forms_id, ";
      $_QJlJ0 .= "$_IIl8O.Name AS BirthdayResponders_Name, $_IIl8O.ML_BM_RefTableName, ";
      $_QJlJ0 .= "$_IIl8O.MaxEMailsToProcess, $_IIl8O.SendIntervalDays, $_IIl8O.SendTime, ";
      $_QJlJ0 .= "$_IIl8O.SMSSendOptions, ";
      $_QJlJ0 .= "$_Qofoi.id AS MTA_id, $_Q60QL.MaillistTableName, $_Q60QL.LocalBlocklistTableName, ";
      $_QJlJ0 .= "$_Q60QL.id AS MailingListId FROM $_IIl8O ";
      $_QJlJ0 .= "LEFT JOIN $_Qofoi ON $_Qofoi.id=$_IIl8O.mtas_id LEFT JOIN $_Q60QL ON ";
      $_QJlJ0 .= "$_Q60QL.id=$_IIl8O.maillists_id WHERE $_IIl8O.IsActive=1 AND ";
      $_QJlJ0 .= "CURRENT_TIME() > $_IIl8O.SendTime";

      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != "")
         $_j6O8O .= "MySQL error while selecting data: ".mysql_error($_Q61I1);
      while($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
        _OPQ6J();

        $_j6O8O .= "checking $_Q8OiJ[BirthdayResponders_Name]...<br />";
        $_jflIQ = 0;

        if($_Q8OiJ["SendIntervalDays"] >= 0) {
           $_Iijl0 =
               'TO_DAYS(

                   DATE_ADD(
                  `u_Birthday`,
                       INTERVAL
                         (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                           IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 1, 0 )
                  )
                         YEAR)
                  )
                  -
                  TO_DAYS( CURRENT_DATE() )
                  AS `days_to_birthday`';
        } else {

           $_Iijl0 =
               'TO_DAYS(

                   DATE_ADD(
                  `u_Birthday`,
                       INTERVAL
                         (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                           IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 0, 1 )
                  )
                         YEAR)
                  )
                  -
                  TO_DAYS( CURRENT_DATE() )
                  AS `days_to_birthday`';

        }

        $_QtjtL = " YEAR( IF(LastSending IS NOT NULL, DATE_ADD(LastSending, INTERVAL $_Q8OiJ[SendIntervalDays] DAY), '0000-00-00') ) AS `SendYear` ";


        $_QlQC8 = $_Q8OiJ["MaillistTableName"];
        $_ItCCo = $_Q8OiJ["LocalBlocklistTableName"];
        $_IO1Oj = " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
        $_IO1Oj .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
        $_IOQf6 = " `$_QlQC8`.IsActive=1 AND `$_QlQC8`.SubscriptionStatus<>'OptInConfirmationPending'".$_Q6JJJ;
        $_IOQf6 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ".$_Q6JJJ;

        $_QJlJ0 = "SELECT `$_Q8OiJ[MaillistTableName]`.id, `$_Q8OiJ[MaillistTableName]`.u_EMail, `$_Q8OiJ[MaillistTableName]`.u_Birthday, `$_Q8OiJ[MaillistTableName]`.u_CellNumber, LastSending, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MemberAge, $_Iijl0, $_QtjtL FROM `$_Q8OiJ[MaillistTableName]`";
        $_QJlJ0 .= " LEFT JOIN `$_Q8OiJ[ML_BM_RefTableName]` ON `$_Q8OiJ[ML_BM_RefTableName]`.Member_id=`$_Q8OiJ[MaillistTableName]`.id";
        $_QJlJ0 .= " $_IO1Oj ";
        $_QJlJ0 .= " WHERE $_IOQf6 ";
        $_QJlJ0 .= " AND `$_Q8OiJ[MaillistTableName]`.u_Birthday <> '0000-00-00' ";

        $_QJlJ0 .= " AND YEAR( IF(LastSending IS NOT NULL, LastSending, '0000-00-00') ) <> YEAR(NOW()) ";
        $_QJlJ0 .= " AND YEAR( IF(LastSending IS NOT NULL, DATE_ADD(LastSending, INTERVAL $_Q8OiJ[SendIntervalDays] DAY), '0000-00-00') ) <> YEAR(NOW()) ";

        if($_Q8OiJ["SendIntervalDays"] >= 0)
          $_QJlJ0 .= "  HAVING `days_to_birthday` <= ".$_Q8OiJ["SendIntervalDays"];
          else
          $_QJlJ0 .= "  HAVING `days_to_birthday` >= ".$_Q8OiJ["SendIntervalDays"]." AND `days_to_birthday` < 360";

        $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);

        if(mysql_error($_Q61I1) != "")
           $_j6O8O .= "MySQL error while selecting data: ".mysql_error($_Q61I1);

        while($_IOOt1 && $_QlftL = mysql_fetch_assoc($_IOOt1)) {

          // limit reached?
          if($_jflIQ >= $_Q8OiJ["MaxEMailsToProcess"]) break;

          _OPQ6J();

          $_jflj6 = false;
          $_jfllo = 0;
          $_jfiol = 0;

          // check for sending SMS
          if($_Q8OiJ["SMSSendOptions"] > 0 && $_QlftL["u_CellNumber"] != "") {
            $_j808f = $_QlftL["u_CellNumber"];
            $_II1Ot = array();
            if(!_LOC1E($_j808f, $_II1Ot)) {
              $_j6O8O .= join("", $_II1Ot)."<br /><br />";
              if($_Q8OiJ["SMSSendOptions"] == 2)
                continue;
            } else
              $_jflj6 = true;
          }

          mysql_query("BEGIN", $_Q61I1);

          # no SMS or EMail and SMS
          if($_Q8OiJ["SMSSendOptions"] < 2) {
            $_QJlJ0 = "INSERT INTO `$_IjQIf` SET `birthdayresponders_id`=$_Q8OiJ[BirthdayResponders_id], `MailSubject`="._OPQLR($_Q8OiJ["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `Send`='Prepared', `IsSMS`=0";
            mysql_query($_QJlJ0, $_Q61I1);

            if(mysql_affected_rows($_Q61I1) > 0) {
              $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
              $_jfl1j = mysql_fetch_array($_jfLII);
              $_jfiol = $_jfl1j[0];
              mysql_free_result($_jfLII);
            } else {
              if(mysql_errno($_Q61I1) == 1062) { // dup key
                // get old id when it is NOT always Prepared = in outqueue
                $_QJlJ0 = "SELECT id FROM `$_IjQIf` WHERE `birthdayresponders_id`=$_Q8OiJ[BirthdayResponders_id] AND `recipients_id`=$_QlftL[id] AND `Send`<>'Prepared' AND `IsSMS`=0";
                $_jfLII = mysql_query($_QJlJ0, $_Q61I1);
                if(mysql_num_rows($_jfLII) > 0){
                  $_jfl1j=mysql_fetch_array($_jfLII);
                  $_jfiol = $_jfl1j[0];
                  mysql_free_result($_jfLII);
                } else {
                  mysql_free_result($_jfLII);

                  $_jfiol = 0;
                }
              } else {
                 $_j6O8O .= "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
                 mysql_query("ROLLBACK", $_Q61I1);
                 return false;
               }
            }

            if($_Q8OiJ["SMSSendOptions"] == 1 && $_jflj6) {
              $_QJlJ0 = "INSERT INTO `$_IjQIf` SET `birthdayresponders_id`=$_Q8OiJ[BirthdayResponders_id], `MailSubject`="._OPQLR("SMS").", `IsSMS`=1, `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `Send`='Prepared'";
              mysql_query($_QJlJ0, $_Q61I1);

              if(mysql_affected_rows($_Q61I1) > 0) {
                $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
                $_jfl1j = mysql_fetch_array($_jfLII);
                $_jfllo = $_jfl1j[0];
                mysql_free_result($_jfLII);
              } else {
                 if(mysql_errno($_Q61I1) == 1062) { // dup key
                   // get old id when it is NOT always Prepared = in outqueue
                   $_QJlJ0 = "SELECT id FROM `$_IjQIf` WHERE `birthdayresponders_id`=$_Q8OiJ[BirthdayResponders_id] AND `recipients_id`=$_QlftL[id] AND `Send`<>'Prepared' AND `IsSMS`=1";
                   $_jfLII = mysql_query($_QJlJ0, $_Q61I1);
                   if(mysql_num_rows($_jfLII) > 0){
                     $_jfl1j = mysql_fetch_array($_jfLII);
                     $_jfllo = $_jfl1j[0];
                     mysql_free_result($_jfLII);
                   } else {
                     mysql_free_result($_jfLII);

                     $_jfllo = 0;

                     mysql_query("ROLLBACK", $_Q61I1);

                     continue;
                   }
                 } else {
                    $_j6O8O .= "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
                    mysql_query("ROLLBACK", $_Q61I1);
                    return false;
                  }
                }
            }
          }

          // SMS only?
          if($_Q8OiJ["SMSSendOptions"] == 2 && $_jflj6) {
            $_QJlJ0 = "INSERT INTO `$_IjQIf` SET `birthdayresponders_id`=$_Q8OiJ[BirthdayResponders_id], `MailSubject`="._OPQLR("SMS").", `IsSMS`=1, `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `Send`='Prepared'";
            mysql_query($_QJlJ0, $_Q61I1);

            if(mysql_affected_rows($_Q61I1) > 0) {
              $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
              $_jfl1j = mysql_fetch_array($_jfLII);
              $_jfllo = $_jfl1j[0];
              mysql_free_result($_jfLII);
            } else {
               if(mysql_errno($_Q61I1) == 1062) { // dup key
                 // get old id when it is NOT always Prepared = in outqueue
                 $_QJlJ0 = "SELECT `id` FROM `$_IjQIf` WHERE `birthdayresponders_id`=$_Q8OiJ[BirthdayResponders_id] AND `recipients_id`=$_QlftL[id] AND `Send`<>'Prepared' AND `IsSMS`=1";
                 $_jfLII = mysql_query($_QJlJ0, $_Q61I1);
                 if(mysql_num_rows($_jfLII) > 0){
                   $_jfl1j = mysql_fetch_array($_jfLII);
                   $_jfllo = $_jfl1j[0];
                   mysql_free_result($_jfLII);
                 } else {
                   mysql_free_result($_jfLII);

                   $_jfllo = 0;
                 }
               } else {
                  $_j6O8O .= "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
                  mysql_query("ROLLBACK", $_Q61I1);
                  return false;
                }
            }
          }


          # Mail?
          if($_jfiol) {
            // SendId = BirthdayResponders Id
            $_QJlJ0 = "INSERT INTO `$_QtjLI` SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$UserId, `Source`='BirthdayResponder', `Source_id`=$_Q8OiJ[BirthdayResponders_id], `Additional_id`=0, `SendId`=$_Q8OiJ[BirthdayResponders_id], `maillists_id`=$_Q8OiJ[MailingListId], `recipients_id`=$_QlftL[id], `mtas_id`=$_Q8OiJ[MTA_id], `LastSending`=NOW() ";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_error($_Q61I1) != "") {
              $_j6O8O .= "MySQL error while adding mail to out queue: ".mysql_error($_Q61I1);
              mysql_query("ROLLBACK", $_Q61I1);
              continue;
            }
          }

          # SMS?
          if($_jfllo) {
            // SendId = BirthdayResponders Id
            $_QJlJ0 = "INSERT INTO `$_QtjLI` SET `CreateDate`=NOW(), `statistics_id`=$_jfllo, `users_id`=$UserId, `Source`='BirthdayResponder', `Source_id`=$_Q8OiJ[BirthdayResponders_id], `Additional_id`=0, `SendId`=$_Q8OiJ[BirthdayResponders_id], `maillists_id`=$_Q8OiJ[MailingListId], `recipients_id`=$_QlftL[id], `mtas_id`=$_jJt8t, `LastSending`=NOW() ";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_error($_Q61I1) != "") {
              $_j6O8O .= "MySQL error while adding mail to out queue: ".mysql_error($_Q61I1);
              mysql_query("ROLLBACK", $_Q61I1);
              continue;
            }
          }

          if($_jfiol || $_jfllo) {
            $_jIojl++;
            $_jflIQ++;
            $_j6O8O .= "Email with subject '$_Q8OiJ[MailSubject]' was queued for sending to '$_QlftL[u_EMail]'<br />";

            // save email send date time, EVER do it
            $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_BM_RefTableName]` SET LastSending=NOW() WHERE Member_id=$_QlftL[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_affected_rows($_Q61I1) == 0) { // new?
              $_QJlJ0 = "INSERT INTO `$_Q8OiJ[ML_BM_RefTableName]` SET Member_id=$_QlftL[id], LastSending=NOW()";
              mysql_query($_QJlJ0, $_Q61I1);
            }

            // Update Birthday responder statistics
            $_QJlJ0 = "UPDATE `$_IIl8O` SET `EMailsSent`=`EMailsSent`+1 WHERE `id`=$_Q8OiJ[BirthdayResponders_id]";
            mysql_query($_QJlJ0, $_Q61I1);
          }

          mysql_query("COMMIT", $_Q61I1);

        }
        if($_IOOt1)
          mysql_free_result($_IOOt1);


      }
      if($_Q8Oj8)
        mysql_free_result($_Q8Oj8);
    }
    mysql_free_result($_Q60l1);


    $_j6O8O .= "<br />$_jIojl emails sent to queue<br />";
    $_j6O8O .= "<br />Birthday responder checking end.";

    if($_jIojl)
      return true;
    return -1;
  }



?>

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

  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("mail.php");
  include_once("mailcreate.inc.php");
  include_once("smsout.inc.php");

  function _LLOD6(&$_JIfo0) {
    global $_QLttI, $_QLl1Q, $_JQjt6, $_QLo06;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Ijt8j, $resourcestrings, $_I18lo, $_I1tQf;
    global $_Ijt0i, $_I8tfQ, $_QL88I, $_IQQot;
    global $_ICo0J, $_ICl0j;

    $_JIfo0 = "Birthday responder checking starts...<br />";
    $_J0J6C = 0;

    $_QLfol = "SELECT * FROM $_I18lo WHERE UserType='Admin' AND IsActive>0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
      _LRCOC();
      $UserId = $_QLO0f["id"];
      $OwnerUserId = 0;
      $Username = $_QLO0f["Username"];
      $UserType = $_QLO0f["UserType"];
      $AccountType = $_QLO0f["AccountType"];
      $INTERFACE_THEMESID = $_QLO0f["ThemesId"];
      $INTERFACE_LANGUAGE = $_QLO0f["Language"];

      _LRPQ6($INTERFACE_LANGUAGE);

      _JQRLR($INTERFACE_LANGUAGE);

      $_QLfol = "SELECT Theme FROM $_I1tQf WHERE id=$INTERFACE_THEMESID";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      $_I1OfI = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_I1OfI[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);


      $_QLfol = "SELECT $_ICo0J.MailSubject, $_ICo0J.id AS BirthdayResponders_id, $_ICo0J.forms_id, ";
      $_QLfol .= "$_ICo0J.Name AS BirthdayResponders_Name, $_ICo0J.ML_BM_RefTableName, ";
      $_QLfol .= "$_ICo0J.MaxEMailsToProcess, $_ICo0J.SendIntervalDays, $_ICo0J.SendTime, ";
      $_QLfol .= "$_ICo0J.SMSSendOptions, ";
      $_QLfol .= "$_Ijt0i.id AS MTA_id, $_QL88I.MaillistTableName, $_QL88I.LocalBlocklistTableName, ";
      $_QLfol .= "$_QL88I.id AS MailingListId FROM $_ICo0J ";
      $_QLfol .= "LEFT JOIN $_Ijt0i ON $_Ijt0i.id=$_ICo0J.mtas_id LEFT JOIN $_QL88I ON ";
      $_QLfol .= "$_QL88I.id=$_ICo0J.maillists_id WHERE $_ICo0J.IsActive=1 AND ";
      $_QLfol .= "CURRENT_TIME() > $_ICo0J.SendTime";

      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if(mysql_error($_QLttI) != "")
         $_JIfo0 .= "MySQL error while selecting data: ".mysql_error($_QLttI);
      while($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
        _LRCOC();

        $_JIfo0 .= "checking $_I1OfI[BirthdayResponders_Name]...<br />";

        _LBOOC($_I1OfI["MailingListId"], ($OwnerUserId != 0 ? $OwnerUserId : $UserId), 0, 'BirthdayResponder', $_I1OfI["BirthdayResponders_id"]);

        $_JJjQJ = 0;

        if($_I1OfI["SendIntervalDays"] >= 0) {
           $_jf8JI =
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

           $_jf8JI =
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

        $_QLlO6 = " YEAR( IF(LastSending IS NOT NULL, DATE_ADD(LastSending, INTERVAL $_I1OfI[SendIntervalDays] DAY), '0000-00-00') ) AS `SendYear` ";


        $_I8I6o = $_I1OfI["MaillistTableName"];
        $_jjj8f = $_I1OfI["LocalBlocklistTableName"];
        $_jj8Ci = " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
        $_jj8Ci .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
        $_jjtQf = " `$_I8I6o`.IsActive=1 AND `$_I8I6o`.SubscriptionStatus<>'OptInConfirmationPending'".$_QLl1Q;
        $_jjtQf .= " AND `$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL ".$_QLl1Q;

        $_QLfol = "SELECT `$_I1OfI[MaillistTableName]`.id, `$_I1OfI[MaillistTableName]`.u_EMail, `$_I1OfI[MaillistTableName]`.u_Birthday, `$_I1OfI[MaillistTableName]`.u_CellNumber, LastSending, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MemberAge, $_jf8JI, $_QLlO6 FROM `$_I1OfI[MaillistTableName]`";
        $_QLfol .= " LEFT JOIN `$_I1OfI[ML_BM_RefTableName]` ON `$_I1OfI[ML_BM_RefTableName]`.Member_id=`$_I1OfI[MaillistTableName]`.id";
        $_QLfol .= " $_jj8Ci ";
        $_QLfol .= " WHERE $_jjtQf ";
        $_QLfol .= " AND `$_I1OfI[MaillistTableName]`.u_Birthday <> '0000-00-00' ";

        $_QLfol .= " AND YEAR( IF(LastSending IS NOT NULL, LastSending, '0000-00-00') ) <> YEAR(NOW()) ";
        $_QLfol .= " AND YEAR( IF(LastSending IS NOT NULL, DATE_ADD(LastSending, INTERVAL $_I1OfI[SendIntervalDays] DAY), '0000-00-00') ) <> YEAR(NOW())";

        if($_I1OfI["SendIntervalDays"] >= 0)
          $_QLfol .= " HAVING `days_to_birthday` <= ".$_I1OfI["SendIntervalDays"];
          else
          $_QLfol .= " HAVING `days_to_birthday` - 365 < ABS($_I1OfI[SendIntervalDays])";

        $_jjl0t = mysql_query($_QLfol, $_QLttI);

        if(mysql_error($_QLttI) != "")
           $_JIfo0 .= "MySQL error while selecting data: ".mysql_error($_QLttI);

        // ECGList
        if(!isset($_jlt10))
          $_jlt10 = _JOLQE("ECGListCheck");
        if($_jlt10){
          // ECG List not more than 5000
          if($_I1OfI["MaxEMailsToProcess"] > 5000)
            $_I1OfI["MaxEMailsToProcess"] = 5000;
          $_J06Ji = array();                        
          while($_jjl0t && $_I8fol = mysql_fetch_assoc($_jjl0t)){ 
            $_J06Ji[] = array("email" => $_I8fol["u_EMail"]/*, "id" => $_QLO0f["id"]*/);
          }  
            
          $_J0fIj = array();
          $_J08Q1 = "";
          $_J0t0L = _L6AF6($_J06Ji, $_J0fIj, $_J08Q1);    
          if(!$_J0t0L) // request failed, is ever in ECG-liste
            $_J0fIj = $_J06Ji;
          unset($_J06Ji); 
          mysql_data_seek($_jjl0t, 0);
        }  
        // ECGList /
           
           
        while($_jjl0t && $_I8fol = mysql_fetch_assoc($_jjl0t)) {

          // limit reached?
          if($_JJjQJ >= $_I1OfI["MaxEMailsToProcess"]) break;

          _LRCOC();

          //ECGList
          $_J0olI = false;
          if($_jlt10){
            $_J0olI = array_search($_I8fol["u_EMail"], array_column($_J0fIj, 'email')) !== false;
          }

          $_JJj88 = false;
          $_JJJjf = 0;
          $_JJQ6I = 0;

          // check for sending SMS
          if($_I1OfI["SMSSendOptions"] > 0 && $_I8fol["u_CellNumber"] != "") {
            $_JJ6Q6 = $_I8fol["u_CellNumber"];
            $_IoLOO = array();
            if(!_JLODC($_JJ6Q6, $_IoLOO)) {
              $_JIfo0 .= join("", $_IoLOO)."<br /><br />";
              if($_I1OfI["SMSSendOptions"] == 2)
                continue;
            } else
              $_JJj88 = true;
          }

          mysql_query("BEGIN", $_QLttI);

          # no SMS or EMail and SMS
          if($_I1OfI["SMSSendOptions"] < 2) {
            $_QLfol = "INSERT INTO `$_ICl0j` SET `birthdayresponders_id`=$_I1OfI[BirthdayResponders_id], `MailSubject`="._LRAFO(unhtmlentities($_I1OfI["MailSubject"], $_QLo06, false)).", `SendDateTime`=NOW(), `recipients_id`=$_I8fol[id], `Send`='Prepared', `IsSMS`=0";
            mysql_query($_QLfol, $_QLttI);

            if(mysql_affected_rows($_QLttI) > 0) {
              $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
              $_JJIl0 = mysql_fetch_array($_JJQlj);
              $_JJQ6I = $_JJIl0[0];
              mysql_free_result($_JJQlj);
            } else {
              if(mysql_errno($_QLttI) == 1062) { // dup key
                // get old id when it is NOT always Prepared = in outqueue
                $_QLfol = "SELECT id FROM `$_ICl0j` WHERE `birthdayresponders_id`=$_I1OfI[BirthdayResponders_id] AND `recipients_id`=$_I8fol[id] AND `Send`<>'Prepared' AND `IsSMS`=0";
                $_JJQlj = mysql_query($_QLfol, $_QLttI);
                if(mysql_num_rows($_JJQlj) > 0){
                  $_JJIl0=mysql_fetch_array($_JJQlj);
                  $_JJQ6I = $_JJIl0[0];
                  mysql_free_result($_JJQlj);
                } else {
                  mysql_free_result($_JJQlj);

                  $_JJQ6I = 0;
                }
              } else {
                 $_JIfo0 .= "MySQL error while adding to statistics table: ".mysql_error($_QLttI);
                 mysql_query("ROLLBACK", $_QLttI);
                 return false;
               }
            }

            if($_I1OfI["SMSSendOptions"] == 1 && $_JJj88) {
              $_QLfol = "INSERT INTO `$_ICl0j` SET `birthdayresponders_id`=$_I1OfI[BirthdayResponders_id], `MailSubject`="._LRAFO("SMS").", `IsSMS`=1, `SendDateTime`=NOW(), `recipients_id`=$_I8fol[id], `Send`='Prepared'";
              mysql_query($_QLfol, $_QLttI);

              if(mysql_affected_rows($_QLttI) > 0) {
                $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
                $_JJIl0 = mysql_fetch_array($_JJQlj);
                $_JJJjf = $_JJIl0[0];
                mysql_free_result($_JJQlj);
              } else {
                 if(mysql_errno($_QLttI) == 1062) { // dup key
                   // get old id when it is NOT always Prepared = in outqueue
                   $_QLfol = "SELECT id FROM `$_ICl0j` WHERE `birthdayresponders_id`=$_I1OfI[BirthdayResponders_id] AND `recipients_id`=$_I8fol[id] AND `Send`<>'Prepared' AND `IsSMS`=1";
                   $_JJQlj = mysql_query($_QLfol, $_QLttI);
                   if(mysql_num_rows($_JJQlj) > 0){
                     $_JJIl0 = mysql_fetch_array($_JJQlj);
                     $_JJJjf = $_JJIl0[0];
                     mysql_free_result($_JJQlj);
                   } else {
                     mysql_free_result($_JJQlj);

                     $_JJJjf = 0;

                     mysql_query("ROLLBACK", $_QLttI);

                     continue;
                   }
                 } else {
                    $_JIfo0 .= "MySQL error while adding to statistics table: ".mysql_error($_QLttI);
                    mysql_query("ROLLBACK", $_QLttI);
                    return false;
                  }
                }
            }
          }

          // SMS only?
          if($_I1OfI["SMSSendOptions"] == 2 && $_JJj88) {
            $_QLfol = "INSERT INTO `$_ICl0j` SET `birthdayresponders_id`=$_I1OfI[BirthdayResponders_id], `MailSubject`="._LRAFO("SMS").", `IsSMS`=1, `SendDateTime`=NOW(), `recipients_id`=$_I8fol[id], `Send`='Prepared'";
            mysql_query($_QLfol, $_QLttI);

            if(mysql_affected_rows($_QLttI) > 0) {
              $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
              $_JJIl0 = mysql_fetch_array($_JJQlj);
              $_JJJjf = $_JJIl0[0];
              mysql_free_result($_JJQlj);
            } else {
               if(mysql_errno($_QLttI) == 1062) { // dup key
                 // get old id when it is NOT always Prepared = in outqueue
                 $_QLfol = "SELECT `id` FROM `$_ICl0j` WHERE `birthdayresponders_id`=$_I1OfI[BirthdayResponders_id] AND `recipients_id`=$_I8fol[id] AND `Send`<>'Prepared' AND `IsSMS`=1";
                 $_JJQlj = mysql_query($_QLfol, $_QLttI);
                 if(mysql_num_rows($_JJQlj) > 0){
                   $_JJIl0 = mysql_fetch_array($_JJQlj);
                   $_JJJjf = $_JJIl0[0];
                   mysql_free_result($_JJQlj);
                 } else {
                   mysql_free_result($_JJQlj);

                   $_JJJjf = 0;
                 }
               } else {
                  $_JIfo0 .= "MySQL error while adding to statistics table: ".mysql_error($_QLttI);
                  mysql_query("ROLLBACK", $_QLttI);
                  return false;
                }
            }
          }

          # Mail?
          if($_JJQ6I) {
             if(!$_J0olI){
              // SendId = BirthdayResponders Id
              $_QLfol = "INSERT INTO `$_IQQot` SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$UserId, `Source`='BirthdayResponder', `Source_id`=$_I1OfI[BirthdayResponders_id], `Additional_id`=0, `SendId`=$_I1OfI[BirthdayResponders_id], `maillists_id`=$_I1OfI[MailingListId], `recipients_id`=$_I8fol[id], `mtas_id`=$_I1OfI[MTA_id], `LastSending`=NOW(), `IsResponder`=1, `MailSubject`=" . _LRAFO(unhtmlentities($_I1OfI["MailSubject"], $_QLo06, false));
              mysql_query($_QLfol, $_QLttI);
              if(mysql_error($_QLttI) != "") {
                $_JIfo0 .= "MySQL error while adding mail to out queue: ".mysql_error($_QLttI);
                mysql_query("ROLLBACK", $_QLttI);
                continue;
              }
            }else{
              $_QLfol = "UPDATE `$_ICl0j` SET `Send`='Failed', `SendResult`=" . _LRAFO("Recipient is in ECG-Liste.") . "  WHERE `id`=$_JJQ6I";
              mysql_query($_QLfol, $_QLttI);
            }
          }

          # SMS?
          if($_JJJjf) {
            if(!$_J0olI){
              // SendId = BirthdayResponders Id
              $_QLfol = "INSERT INTO `$_IQQot` SET `CreateDate`=NOW(), `statistics_id`=$_JJJjf, `users_id`=$UserId, `Source`='BirthdayResponder', `Source_id`=$_I1OfI[BirthdayResponders_id], `Additional_id`=0, `SendId`=$_I1OfI[BirthdayResponders_id], `maillists_id`=$_I1OfI[MailingListId], `recipients_id`=$_I8fol[id], `mtas_id`=$_JQjt6, `LastSending`=NOW(), `IsResponder`=1 ";
              mysql_query($_QLfol, $_QLttI);
              if(mysql_error($_QLttI) != "") {
                $_JIfo0 .= "MySQL error while adding mail to out queue: ".mysql_error($_QLttI);
                mysql_query("ROLLBACK", $_QLttI);
                continue;
              }
            }else{
              $_QLfol = "UPDATE `$_ICl0j` SET `Send`='Failed', `SendResult`=" . _LRAFO("Recipient is in ECG-Liste.") . "  WHERE `id`=$_JJJjf";
              mysql_query($_QLfol, $_QLttI);
            }            
          }

          if($_JJQ6I || $_JJJjf) {
            if(!$_J0olI){
              $_J0J6C++;
              $_JIfo0 .= "Email with subject '$_I1OfI[MailSubject]' was queued for sending to '$_I8fol[u_EMail]'<br />";
              // Update Birthday responder statistics
              $_QLfol = "UPDATE `$_ICo0J` SET `EMailsSent`=`EMailsSent`+1 WHERE `id`=$_I1OfI[BirthdayResponders_id]";
              mysql_query($_QLfol, $_QLttI);
            } else{
              $_JIfo0 .= "Email with subject '$_I1OfI[MailSubject]' was not sent to '$_I8fol[u_EMail]', Recipient is in ECG-Liste.<br />";
            } 
            $_JJjQJ++;

            // save email send date time, EVER do it
            $_QLfol = "UPDATE `$_I1OfI[ML_BM_RefTableName]` SET LastSending=NOW() WHERE Member_id=$_I8fol[id]";
            mysql_query($_QLfol, $_QLttI);
            if(mysql_affected_rows($_QLttI) == 0) { // new?
              $_QLfol = "INSERT INTO `$_I1OfI[ML_BM_RefTableName]` SET Member_id=$_I8fol[id], LastSending=NOW()";
              mysql_query($_QLfol, $_QLttI);
            }

          }

          mysql_query("COMMIT", $_QLttI);

        }
        if($_jjl0t)
          mysql_free_result($_jjl0t);


      }
      if($_I1O6j)
        mysql_free_result($_I1O6j);
    }
    mysql_free_result($_QL8i1);


    $_JIfo0 .= "<br />$_J0J6C emails sent to queue<br />";
    $_JIfo0 .= "<br />Birthday responder checking end.";

    if($_J0J6C)
      return true;
    return -1;
  }



?>

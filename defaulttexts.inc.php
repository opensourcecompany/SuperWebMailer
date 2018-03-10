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

 function _ORFOQ(){
   global $_Q88iO, $_Q61I1;
   $_ji6CJ = mysql_query("SELECT count(*) FROM `$_Q88iO`", $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_ji6CJ);
   mysql_free_result($_ji6CJ);
   if($_Q6Q1C[0] == 0) {
     $_QJlJ0 = "INSERT INTO `$_Q88iO` SET `id`=1";
     mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_QJlJ0 = "UPDATE `$_Q88iO` SET `ScriptVersion`="._OPQLR($_QoJ8j);
     mysql_query($_QJlJ0, $_Q61I1);
   }
 }

 function _ORFOA(){
   global $_jJJjO, $_Q61I1;
   $_ji6CJ = mysql_query("SELECT count(*) FROM `$_jJJjO`", $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_ji6CJ);
   mysql_free_result($_ji6CJ);
   if($_Q6Q1C[0] == 0) {
     $_QJlJ0 = "INSERT INTO `$_jJJjO` SET `id`=1";
     mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
   }
 }

 function _ORFEQ(){
   global $_jJJtf, $OwnerOwnerUserId, $_Q61I1;
   $_ji6CJ = mysql_query("SELECT count(*) FROM `$_jJJtf`", $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_ji6CJ);
   mysql_free_result($_ji6CJ);
   if($_Q6Q1C[0] == 0) {
     if(defined("SWM")) {
     $_jif00 = array(
                   'OptInOptOutExpirationCheck' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Day'
                                                         ),
                   'CronLogCleanUp' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Day'
                                                         ),
                   'MailingListStatCleanUp' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Day'
                                                         ),

                   'AutoImport' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'ResponderStatCleanUp' => array (
                                                         'JobWorkingInterval' => 10,
                                                         'JobWorkingIntervalType' => 'Day'
                                                         ),

                   'TrackingStatCleanUp' => array (
                                                         'JobWorkingInterval' => 6,
                                                         'JobWorkingIntervalType' => 'Month'
                                                         ),

                   'BounceChecking' => array (
                                                         'JobWorkingInterval' => 30,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'AutoresponderChecking' => array (
                                                         'JobWorkingInterval' => 5,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'FollowUpResponderChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'BirthdayResponderChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Hour'
                                                         ),

                   'RSS2EMailResponderChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'EventResponderChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Hour'
                                                         ),

                   'CampaignChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'SplitTestChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'SMSCampaignChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'DistribListChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'SendEngineChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         )
                   );
     }

     if(!defined("SWM")) {
     $_jif00 = array(
                   'OptInOptOutExpirationCheck' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Day'
                                                         ),
                   'CronLogCleanUp' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Day'
                                                         ),
                   'MailingListStatCleanUp' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Day'
                                                         ),

                   'AutoImport' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'BounceChecking' => array (
                                                         'JobWorkingInterval' => 30,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'DistribListChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         ),

                   'SendEngineChecking' => array (
                                                         'JobWorkingInterval' => 1,
                                                         'JobWorkingIntervalType' => 'Minute'
                                                         )


                   );
     }

     foreach($_jif00 as $_I1i8O => $_I1L81) {
       $_QJlJ0 = "INSERT INTO `$_jJJtf` SET `JobType`='$_I1i8O', `JobEnabled`=1, `JobWorkingInterval`='$_I1L81[JobWorkingInterval]', `JobWorkingIntervalType`='$_I1L81[JobWorkingIntervalType]'";
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
     }
   }
 }

 function _O800C(){
   global $_ICljl, $INTERFACE_LANGUAGE, $_Q61I1;
   $_ji6CJ = mysql_query("SELECT count(*) FROM `$_ICljl`", $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_ji6CJ);
   mysql_free_result($_ji6CJ);

   $_jifQ0 = join("", file(_O68QF()."default_pagesframeset.htm"));

   if($_Q6Q1C[0] == 0) {
     if($INTERFACE_LANGUAGE == "de")
       $_QJlJ0 = "INSERT INTO `$_ICljl` (`id`, `CreateDate`, `IsDefault`, `Name`, `Type`, `RedirectURL`, `HTMLPage`) VALUES
        (1, NOW(), 1, 'Standard Fehlerseite', 'Error', '', '"._O8106($_jifQ0, '<p><b>Es ist ein Fehler aufgetreten:</b></p><p>[ERRORPAGEMESSAGE]</p>'.'<p>&nbsp;</p><p>Klicken Sie <a href="javascript:history.back();">hier um die Angaben zu korrigieren.</a></p>')."'),
        (2, NOW(), 1, 'Standard Anmeldung erfolgreich', 'Subscribe', '', '"._O8106($_jifQ0, '<p>Sie wurden erfolgreich zum Verteiler hinzugef&uuml;gt.</p>')."'),
        (3, NOW(), 1, 'Standard Best&auml;tigungslink bei Anmeldung versendet', 'SubscribeConfirmation', '', '"._O8106($_jifQ0, '<p>Vielen Dank f&uuml;r Ihre Anmeldung.</p><p>Es wurde Ihnen soeben eine E-Mail mit einem Best&auml;tigungslink gesendet.<br /> Bitte klicken Sie in der E-Mail auf den Best&auml;tigungslink, um zum Verteiler hinzugef&uuml;gt zu werden.</p>')."'),
        (4, NOW(), 1, 'Standard Abmeldung erfolgreich', 'Unsubscribe', '', '"._O8106($_jifQ0, '<p>Ihre E-Mail-Adresse wurde aus unserem Verteiler entfernt.</p>')."'),
        (5, NOW(), 1, 'Standard Best&auml;tigungslink bei Abmeldung versendet', 'UnsubscribeConfirmation', '', '"._O8106($_jifQ0, '<p>Wir haben Ihre Abmeldung erhalten.</p><p>Es wurde Ihnen soeben eine E-Mail mit einem Best&auml;tigungslink gesendet.<br /> Bitte klicken Sie in der E-Mail auf den Best&auml;tigungslink, um aus unserem Verteiler entfernt zu werden.</p>')."'),
        (6, NOW(), 1, 'Standard Best&auml;tigungslink bei &Auml;nderung versendet', 'EditConfirmation', '', '"._O8106($_jifQ0, '<p>Vielen Dank f&uuml;r Ihre &Auml;nderungsmitteilung.</p><p>Es wurde Ihnen soeben eine E-Mail mit einem Best&auml;tigungslink gesendet.<br /> Bitte klicken Sie in der E-Mail auf den Best&auml;tigungslink, um die &Auml;nderungen zu best&auml;tigen.</p>')."'),
        (7, NOW(), 1, 'Standard &Auml;ndern erfolgreich', 'Edit', '', '"._O8106($_jifQ0, '<p>Die &Auml;nderungen wurden erfolgreich gespeichert.</p>')."'),
        (8, NOW(), 1, 'Standard &Auml;nderung verworfen', 'EditReject', '', '"._O8106($_jifQ0, '<p>Die &Auml;nderungen wurden verworfen.</p>')."'),
        (9, NOW(), 1, 'Standard &Auml;nderung fehlerhaft', 'EditError', '', '"._O8106($_jifQ0, '<p>Die &Auml;nderungen konnten nicht durchgef&uuml;hrt werden, wahrscheinlich haben Sie bereits die &Auml;nderung best&auml;tigt oder verwerfen lassen.</p>')."'),
        (10, NOW(), 1, 'Standard Zwischenseite Klick auf Abmeldelink', 'UnsubscribeBridge', '', '"._O8106($_jifQ0, '<form><p>Best&auml;tigung Ihrer Newsletter-Abmeldung:<br /><br /><input type="submit" value="Ja, abmelden." /></p></form>')."'),
        (11, NOW(), 1, 'Standard Abmeldung erfolgreich mit Umfrage', 'Unsubscribe', '', '"._O8106($_jifQ0, '<p>Ihre E-Mail-Adresse wurde aus unserem Verteiler entfernt.</p><p>Gestatten Sie uns zu fragen, warum Sie sich abgemeldet haben?</p><p>&nbsp;</p><p>[ReasonsForUnsubscriptionSurvey]</p>')."'),
        (12, NOW(), 1, 'Standard Umfrage nach Abmeldung abgeschlossen', 'RFUSurveyConfirmation', '', '"._O8106($_jifQ0, '<p>Vielen Dank f&uuml;r Ihre Teilnahme an unserer Umfrage.</p>')."')"
        ;

       else
       $_QJlJ0 = "INSERT INTO `$_ICljl` (`id`, `CreateDate`, `IsDefault`, `Name`, `Type`, `RedirectURL`, `HTMLPage`) VALUES
        (1, NOW(), 1, 'Default error page', 'Error', '', '"._O8106($_jifQ0, '<p><b>An error occurred:</b></p><p>[ERRORPAGEMESSAGE]</p>'.'<p>&nbsp;</p><p>Click <a href="javascript:history.back();">here to complete the form.</a></p>')."'),
        (2, NOW(), 1, 'Default subscription successfully', 'Subscribe', '', '"._O8106($_jifQ0, '<p>Your email address was added successfully.</p>')."'),
        (3, NOW(), 1, 'Default confirmation link for subscription sent', 'SubscribeConfirmation', '', '"._O8106($_jifQ0, '<p>Thank you for your subscription.</p><p>We have sent an email with a confirmation link.<br />Please click on this link to confirm your subscribtion.</p>')."'),
        (4, NOW(), 1, 'Default unsubscription successfully', 'Unsubscribe', '', '"._O8106($_jifQ0, '<p>Your email address was removed from our mailinglist.</p>')."'),
        (5, NOW(), 1, 'Default confirmation link for unsubscription sent', 'UnsubscribeConfirmation', '', '"._O8106($_jifQ0, '<p>We have got your unsubscription request.</p><p>We have sent an email with a confirmation link.<br />Please click on this link to confirm your unsubscribtion request.</p>')."'),
        (6, NOW(), 1, 'Default confirmation link to confirm editing sent', 'EditConfirmation', '', '"._O8106($_jifQ0, '<p>Thank you for editing your personal data.</p><p>We have sent an email with a confirmation link.<br />Please click on this link to confirm your changes.</p>')."'),
        (7, NOW(), 1, 'Default editing successfully', 'Edit', '', '". _O8106($_jifQ0, '<p>Your changes saved successfully.</p>') ."'),
        (8, NOW(), 1, 'Default editing rejected', 'EditReject', '', '". _O8106($_jifQ0, '<p>Your changes are discarded.</p>') ."'),
        (9, NOW(), 1, 'Default editing error', 'EditError', '', '". _O8106($_jifQ0, '<p>An error ocurred, you have always confirmed or rejected your changes.</p>') ."'),
        (10, NOW(), 1, 'Default intermediate page click on unsubscribe link', 'UnsubscribeBridge', '', '"._O8106($_jifQ0, '<form><p>Confirmation of your newsletter unsubscription:<br /><br /><input type="submit" value="Yes, unsubscribe me." /></p></form>')."'),
        (11, NOW(), 1, 'Default unsubscription successfully with survey', 'Unsubscribe', '', '"._O8106($_jifQ0, '<p>Your email address was removed from our mailinglist.</p><p>Allow us to ask you why you have unsubscriped?</p><p>&nbsp;</p><p>[ReasonsForUnsubscriptionSurvey]</p>')."')
        (12, NOW(), 1, 'Default survey after unsubscription done', 'RFUSurveyConfirmation', '', '"._O8106($_jifQ0, '<p>Many thanks for your participation on our survey.</p>')."')"
        ;

     $_QJlJ0 = utf8_encode($_QJlJ0);
     mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
   }
 }

 function _O8106($_jifiO, $_Q6ICj) {
    $_jifiO = str_replace("css/default.css", ScriptBaseURL."css/default.css", $_jifiO);
    return _OPR6L($_jifiO, "<SHOW:ANSWERTEXT>", "</SHOW:ANSWERTEXT>", $_Q6ICj);
 }

 function _O81AE(){
   global $_QLo0Q, $INTERFACE_LANGUAGE, $_Qofjo, $_Q61I1;

   $_ji6CJ = mysql_query("SELECT count(*) FROM `$_QLo0Q`", $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_ji6CJ);
   mysql_free_result($_ji6CJ);
   if($_Q6Q1C[0] == 0) {

     $_QJlJ0 = "SELECT `fieldname`, `text` FROM `$_Qofjo` WHERE `language`='$INTERFACE_LANGUAGE'";
     $_ji8jt = mysql_query($_QJlJ0, $_Q61I1);
     $_ji8l1 = array();
     while($_Q8OiJ=mysql_fetch_assoc($_ji8jt)) {
       $_ji8l1[$_Q8OiJ["fieldname"]] = $_Q8OiJ["text"];
     }
     mysql_free_result($_ji8jt);

     if($INTERFACE_LANGUAGE == "de") {
       $_QJlJ0 = "INSERT INTO `$_QLo0Q` (`id`, `CreateDate`, `IsDefault`, `Name`, `EMailFormatErrorText`, `RequiredFieldsErrorText`, `ErrorText`, `SubscribeOKText`, `SubscribeTextConfirmationRequired`, `EMailAddressAlwaysInList`,
                                             `EMailAddressBlacklisted`, `UnsubscribeOKText`, `UnsubscribeTextConfirmationRequired`, `EMailAddressNotInList`, `SubscribeTextOnConfirmationSucc`, `UnsubscribeTextOnConfirmationSucc`,
                                             `SubscribeTextOnConfirmationFailure`, `UnsubscribeTextOnConfirmationFailure`, `SubscribeTextOnConfirmationAgain`, `CaptchaStringIncorrect`, `GroupsAsMandatoryFieldError`
                                             ) VALUES
       (1, NOW(), 1, 'Standard', 'Das Format der E-Mail-Adresse ist nicht korrekt.', 'Folgende Pflichtfelder wurden nicht ausgef&uuml;llt:', 'Es ist ein Fehler aufgetreten:', 'Vielen Dank f&uuml;r Ihre Anmeldung.',
         'Vielen Dank f&uuml;r Ihre Anmeldung. Ihnen wurde soeben eine E-Mail mit einem Best&auml;tigungslink zugesendet. Sie m&uuml;ssen auf diesen Link klicken um die Anmeldung abzuschlie&szlig;en.', 'Ihre E-Mail-Adresse befindet sich bereits in unserem Verteiler.',
         'Ihre E-Mail-Adresse wurde vom Administrator gesperrt.', 'Ihre E-Mail-Adresse wurde aus dem Verteiler gel&ouml;scht.', 'Ihre E-Mail-Adresse wurde zur Austragung vorgesehen. Es wurde Ihnen soeben eine E-Mail mit einem Best&auml;tigungslink zugesendet. Klicken Sie auf diesen Best&auml;tigungslink um die Austragung abzuschlie&szlig;en.',
         'Ihre E-Mail-Adresse befindet sich nicht in unserem Verteiler.', 'Die Anmeldung zu unserem Verteiler wurde erfolgreich abgeschlossen.', 'Ihre E-Mail-Adresse wurde erfolgreich aus unserem Verteiler ausgetragen.', 'Die Anmeldung an unserem Verteiler konnte nicht erfolgreich abgeschlossen werden. Melden Sie sich bitte erneut mit Ihrer E-Mail-Adresse an.',
         'Ihre E-Mail-Adresse konnte nicht aus unserem Verteiler entfernt werden. Entweder wurde diese bereits gel&ouml;scht oder Sie m&uuml;ssen sich wegen zu langer Wartezeit nochmals abmelden.', 'Sie haben sich bereits erfolgreich an unserem Verteiler angemeldet.',
         'Geben Sie das angezeigte Wort korrekt ein.', 'Bitte w&auml;hlen Sie eine Gruppe.')";

       $_QJlJ0 = utf8_encode($_QJlJ0);
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);

       $_ji8l1["u_EMail"] = 'Die E-Mail-Adresse fehlt.';
       $_ji8l1["u_EMailFormat"] = 'Sie m&uuml;ssen das gew&uuml;nschte E-Mail-Format w&auml;hlen.';
       reset($_ji8l1);
       $_QJlJ0 = "";
       foreach($_ji8l1 as $key => $_Q6ClO){
         if($key != "u_EMail" && $key != "u_EMailFormat")
            $_Q6ClO = "Sie m&uuml;ssen das Feld '$_Q6ClO' ausf&uuml;llen.";
         if($_QJlJ0 == "")
           $_QJlJ0 = "`$key`="._OPQLR($_Q6ClO);
           else
           $_QJlJ0 .= ", `$key`="._OPQLR($_Q6ClO);
       }
       $_QJlJ0 = utf8_encode($_QJlJ0);
       $_QJlJ0 = "UPDATE `$_QLo0Q` SET ".$_QJlJ0. " WHERE id=1";
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       }
       else {

         $_QJlJ0 = "INSERT INTO `$_QLo0Q` (`id`, `CreateDate`, `IsDefault`, `Name`, `EMailFormatErrorText`, `RequiredFieldsErrorText`, `ErrorText`, `SubscribeOKText`, `SubscribeTextConfirmationRequired`, `EMailAddressAlwaysInList`,
                `EMailAddressBlacklisted`, `UnsubscribeOKText`, `UnsubscribeTextConfirmationRequired`, `EMailAddressNotInList`, `SubscribeTextOnConfirmationSucc`, `UnsubscribeTextOnConfirmationSucc`,
                `SubscribeTextOnConfirmationFailure`, `UnsubscribeTextOnConfirmationFailure`, `SubscribeTextOnConfirmationAgain`, `CaptchaStringIncorrect`, `GroupsAsMandatoryFieldError`
                ) VALUES
         (1, NOW(), 1, 'Default', 'Format of email address is incorrect.', 'Following fields are not filled:', 'An error ocurred:', 'Thank you for your subscribtion.',
           'Thank you for your subscribtion. We have sent an email with a confirmation link. In the email you must click on this links to confirm your subscribtion.',
           'Your email address always exists in our mailinglist.', 'Your email address was locked by administrator.', 'Your email address was removed from our mailinglist.',
           'Your email address is marked for unsubscribtion. We have sent an email with a confirmation link. In the email you must click on this links to confirm your unsubscribtion.',
           'Your email address doesn''t exists in our mailinglist.', 'You are now subscribed to our mailinglist.', 'We have removed your email address successfully.', 'Subscribtion to our mailinglist was not successfully. You must fill in the subscribtion form again.',
           'We cant remove your email address from our mailinglist. The email address doesn''t exists or you must fill in the unsubscribtion form again.', 'Your are always subscribed to our mailinglist.',
           'Please type in the showed word correctly.', 'Please select a group.')";
         $_QJlJ0 = utf8_encode($_QJlJ0);
         mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);

         $_ji8l1["u_EMail"] = 'Email address are missing.';
         $_ji8l1["u_EMailFormat"] = 'Your must selected the desired email format.';
         reset($_ji8l1);
         $_QJlJ0 = "";
         foreach($_ji8l1 as $key => $_Q6ClO){
           if($key != "u_EMail" && $key != "u_EMailFormat")
             $_Q6ClO = "You must fill in field '$_Q6ClO'.";
           if($_QJlJ0 == "")
             $_QJlJ0 = "`$key`="._OPQLR($_Q6ClO);
             else
             $_QJlJ0 .= ", `$key`="._OPQLR($_Q6ClO);
         }
         $_QJlJ0 = utf8_encode($_QJlJ0);
         $_QJlJ0 = "UPDATE `$_QLo0Q` SET ".$_QJlJ0. " WHERE `id`=1";
         mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);

       }
     $_QJlJ0 = utf8_encode($_QJlJ0);
     mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
   }
 }

 function _O81BJ(){
   global $_Qofoi, $_Q6JJJ, $_Q61I1;

   $_ji6CJ = mysql_query("SELECT count(*) FROM `$_Qofoi`", $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_ji6CJ);
   mysql_free_result($_ji6CJ);
   if($_Q6Q1C[0] == 0) {
     $_QJlJ0 = "INSERT INTO `$_Qofoi` (`id`, `CreateDate`, `IsDefault`, `Type`, `Name`, `MailLimit`, `PHPMailParams`, `HELOName`, `SMTPPersist`, `SMTPPipelining`, `SMTPTimeout`, `SMTPServer`, `SMTPPort`, `SMTPAuth`, `SMTPUsername`, `SMTPPassword`, `sendmail_path`, `sendmail_args`) VALUES".$_Q6JJJ;
     $_QJlJ0 .= "(1, NOW(), 1, 'mail', 'Standard PHP mail()', 0, '', 'localhost', 0, 0, 0, '', 25, 0, '', '', '/usr/sbin/sendmail', '-i')".$_Q6JJJ;
//     $_QJlJ0 .= "(2, NOW(), 1, 'smtpmx', 'Standard SMTP Direct (MX)', 0, '', 'localhost', 0, 0, 0, '', 25, 0, '', '', '/usr/sbin/sendmail', '-i')".$_Q6JJJ;

     $_QJlJ0 = utf8_encode($_QJlJ0);
     mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
   }
 }

 function _O81DC() {
  global $_I88i8, $INTERFACE_LANGUAGE, $_Q61I1;

  $_ji6CJ = mysql_query("SELECT count(*) FROM `$_I88i8`", $_Q61I1);
  $_Q6Q1C = mysql_fetch_row($_ji6CJ);
  mysql_free_result($_ji6CJ);
  if($_Q6Q1C[0] == 0) {
     if($INTERFACE_LANGUAGE == "de") {
       $_QJlJ0 = "INSERT INTO `$_I88i8` (`id`, `CreateDate`, `IsDefault`, `Name`, `functiontext`) VALUES ";
       $_QJlJ0 .= "(1, NOW(), 1, 'Beispiel_Anrede',";
       $_QJlJ0 .= ' \'a:3:{i:0;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:4:"Herr";s:10:"outputtext";s:34:"Sehr geehrter Herr [u_LastName],\r\n";}i:1;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:4:"Frau";s:10:"outputtext";s:33:"Sehr geehrte Frau [u_LastName],\r\n";}i:2;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:5:"Firma";s:10:"outputtext";s:32:"Sehr geehrte Damen und Herren,\r\n";}}\' ';
       $_QJlJ0 .= ')';
     } else {
       $_QJlJ0 = "INSERT INTO `$_I88i8` (`id`, `CreateDate`, `IsDefault`, `Name`, `functiontext`) VALUES ";
       $_QJlJ0 .= "(1, NOW(), 1, 'Sample_Salutation',";
       $_QJlJ0 .= '\'a:3:{i:0;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:2:"Mr";s:10:"outputtext";s:26:"Dear Mr. [u_LastName],\r\n";}i:1;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:3:"Mrs";s:10:"outputtext";s:27:"Dear Mrs. [u_LastName],\r\n";}i:2;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:4:"firm";s:10:"outputtext";s:25:"Dear Sirs and Madams,\r\n";}}\' '.")";
     }
    $_QJlJ0 = utf8_encode($_QJlJ0);
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
  }
 }

 function _O8QO8(){
  global $_Q66li, $resourcestrings, $INTERFACE_LANGUAGE, $OwnerUserId, $_jJO1j, $_Q61I1;

  if($OwnerUserId == 0 && !$_jJO1j){
    $_Q6LIL = array();
    if ($_Q6l1t = opendir(InstallPath."newsletter_templates")) {
        while (($_Q6lfJ = readdir($_Q6l1t)) !== false) {
          if($_Q6lfJ != "." && !is_dir(InstallPath."newsletter_templates/".$_Q6lfJ) && strpos($_Q6lfJ, ".htm") !== false )
            $_Q6LIL[] = $_Q6lfJ;
        }
        closedir($_Q6l1t);
    }

    sort($_Q6LIL);

    if(count($_Q6LIL) > 0){

      for($_Q6llo=0; $_Q6llo< count($_Q6LIL); $_Q6llo++){
        $_QfC8t = join("", file(InstallPath."newsletter_templates/".$_Q6LIL[$_Q6llo]));
        if($_QfC8t === false) continue;
        $_QfC8t = utf8_encode($_QfC8t);
        $_QfC8t = SetHTMLCharSet($_QfC8t, "utf-8", false);
        $_jitLI = array();
        GetInlineFiles($_QfC8t, $_jitLI);
        for($_Qf0Ct=0; $_Qf0Ct<count($_jitLI); $_Qf0Ct++){
          if(strpos($_jitLI[$_Qf0Ct], "newsletter_templates/") === false)
            $_QfC8t = str_replace('"'.$_jitLI[$_Qf0Ct].'"', '"'."newsletter_templates/".$_jitLI[$_Qf0Ct].'"', $_QfC8t);
        }

        $_IJLt1 = substr($_Q6LIL[$_Q6llo], 0, strpos($_Q6LIL[$_Q6llo], "."));
        $_jiOiQ = 0;
        if(stripos($_IJLt1, "template-sample") !== false)
           $_IJLt1 = $resourcestrings[$INTERFACE_LANGUAGE]["000806"]." ".substr($_IJLt1, strpos($_IJLt1, "("));
           else {
             $_IJLt1 = $resourcestrings[$INTERFACE_LANGUAGE]["000807"]." ".substr($_IJLt1, strpos($_IJLt1, "("));
             $_jiOiQ = 1;
           }

        $_QJlJ0 = "INSERT INTO `$_Q66li` SET `CreateDate`=NOW(), `Name`="._OPQLR($_IJLt1).", `MailFormat`='HTML', `IsWizardable`=$_jiOiQ, `MailHTMLText`="._OPQLR($_QfC8t);
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }

      $_jJO1j = true;
      include_once("savedoptions.inc.php");
      _LQC66('NewsletterTemplatesImported', 1);
    }
  }
 }

?>

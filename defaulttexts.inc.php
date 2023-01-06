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

 function _LJDJB(){
   global $_I1O0i, $_QLttI;
   $_Ji0IC = mysql_query("SELECT count(*) FROM `$_I1O0i`", $_QLttI);
   $_QLO0f = mysql_fetch_row($_Ji0IC);
   mysql_free_result($_Ji0IC);
   if($_QLO0f[0] == 0) {
     $_QLfol = "INSERT INTO `$_I1O0i` SET `id`=1";
     mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     $_QLfol = "UPDATE `$_I1O0i` SET `ScriptVersion`="._LRAFO($_Ij6Lj);
     mysql_query($_QLfol, $_QLttI);
   }
 }

 function _LJDBF(){
   global $_JQ1I6, $_QLttI;
   $_Ji0IC = mysql_query("SELECT count(*) FROM `$_JQ1I6`", $_QLttI);
   $_QLO0f = mysql_fetch_row($_Ji0IC);
   mysql_free_result($_Ji0IC);
   if($_QLO0f[0] == 0) {
     $_QLfol = "INSERT INTO `$_JQ1I6` SET `id`=1";
     mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
   }
 }

 function _LJEJR(){
   global $_JQQI1, $OwnerOwnerUserId, $_QLttI;
   $_Ji0IC = mysql_query("SELECT count(*) FROM `$_JQQI1`", $_QLttI);
   $_QLO0f = mysql_fetch_row($_Ji0IC);
   mysql_free_result($_Ji0IC);
   if($_QLO0f[0] == 0) {
     if(defined("SWM")) {
     $_Ji066 = array(
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
     $_Ji066 = array(
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

     foreach($_Ji066 as $_IOLil => $_IOCjL) {
       $_QLfol = "INSERT INTO `$_JQQI1` SET `JobType`='$_IOLil', `JobEnabled`=1, `JobWorkingInterval`='$_IOCjL[JobWorkingInterval]', `JobWorkingIntervalType`='$_IOCjL[JobWorkingIntervalType]'";
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
     }
   }
 }

 function _LJEDE(){
   global $_jfQtI, $INTERFACE_LANGUAGE, $_QLttI;
   $_Ji0IC = mysql_query("SELECT count(*) FROM `$_jfQtI`", $_QLttI);
   $_QLO0f = mysql_fetch_row($_Ji0IC);
   mysql_free_result($_Ji0IC);

   $_Ji0Cf = join("", file(_LOC8P()."default_pagesframeset.htm"));

   if($_QLO0f[0] == 0) {
     if($INTERFACE_LANGUAGE == "de")
       $_QLfol = "INSERT INTO `$_jfQtI` (`id`, `CreateDate`, `IsDefault`, `Name`, `Type`, `RedirectURL`, `HTMLPage`) VALUES
        (1, NOW(), 1, 'Standard Fehlerseite', 'Error', '', '"._LJFLJ($_Ji0Cf, '<p><b>Es ist ein Fehler aufgetreten:</b></p><p>[ERRORPAGEMESSAGE]</p>'.'<p>&nbsp;</p><BackLink><p id="gobacklink">Klicken Sie <a href="javascript:history.back();">hier um die Angaben zu korrigieren.</a></p></BackLink>')."'),
        (2, NOW(), 1, 'Standard Anmeldung erfolgreich', 'Subscribe', '', '"._LJFLJ($_Ji0Cf, '<p>Sie wurden erfolgreich zum Verteiler hinzugef&uuml;gt.</p>')."'),
        (3, NOW(), 1, 'Standard Best&auml;tigungslink bei Anmeldung versendet', 'SubscribeConfirmation', '', '"._LJFLJ($_Ji0Cf, '<p>Vielen Dank f&uuml;r Ihre Anmeldung.</p><p>Es wurde Ihnen soeben eine E-Mail mit einem Best&auml;tigungslink gesendet.<br /> Bitte klicken Sie in der E-Mail auf den Best&auml;tigungslink, um zum Verteiler hinzugef&uuml;gt zu werden.</p>')."'),
        (4, NOW(), 1, 'Standard Abmeldung erfolgreich', 'Unsubscribe', '', '"._LJFLJ($_Ji0Cf, '<p>Ihre E-Mail-Adresse wurde aus unserem Verteiler entfernt.</p>')."'),
        (5, NOW(), 1, 'Standard Best&auml;tigungslink bei Abmeldung versendet', 'UnsubscribeConfirmation', '', '"._LJFLJ($_Ji0Cf, '<p>Wir haben Ihre Abmeldung erhalten.</p><p>Es wurde Ihnen soeben eine E-Mail mit einem Best&auml;tigungslink gesendet.<br /> Bitte klicken Sie in der E-Mail auf den Best&auml;tigungslink, um aus unserem Verteiler entfernt zu werden.</p>')."'),
        (6, NOW(), 1, 'Standard Best&auml;tigungslink bei &Auml;nderung versendet', 'EditConfirmation', '', '"._LJFLJ($_Ji0Cf, '<p>Vielen Dank f&uuml;r Ihre &Auml;nderungsmitteilung.</p><p>Es wurde Ihnen soeben eine E-Mail mit einem Best&auml;tigungslink gesendet.<br /> Bitte klicken Sie in der E-Mail auf den Best&auml;tigungslink, um die &Auml;nderungen zu best&auml;tigen.</p>')."'),
        (7, NOW(), 1, 'Standard &Auml;ndern erfolgreich', 'Edit', '', '"._LJFLJ($_Ji0Cf, '<p>Die &Auml;nderungen wurden erfolgreich gespeichert.</p>')."'),
        (8, NOW(), 1, 'Standard &Auml;nderung verworfen', 'EditReject', '', '"._LJFLJ($_Ji0Cf, '<p>Die &Auml;nderungen wurden verworfen.</p>')."'),
        (9, NOW(), 1, 'Standard &Auml;nderung fehlerhaft', 'EditError', '', '"._LJFLJ($_Ji0Cf, '<p><b>Es ist ein Fehler beim &Auml;ndern aufgetreten:</b></p><p>[ERRORPAGEMESSAGE]</p>'.'<p id="gobacklink">&nbsp;</p><p>Klicken Sie <a href="javascript:history.back();">hier um die Angaben zu korrigieren.</a></p>')."'),
        (10, NOW(), 1, 'Standard Zwischenseite Klick auf Abmeldelink', 'UnsubscribeBridge', '', '"._LJFLJ($_Ji0Cf, '<form><p>Best&auml;tigung Ihrer Newsletter-Abmeldung:<br /><br /><input type="submit" value="Ja, abmelden." /></p></form>')."'),
        (11, NOW(), 1, 'Standard Abmeldung erfolgreich mit Umfrage', 'Unsubscribe', '', '"._LJFLJ($_Ji0Cf, '<p>Ihre E-Mail-Adresse wurde aus unserem Verteiler entfernt.</p><p>Gestatten Sie uns zu fragen, warum Sie sich abgemeldet haben?</p><p>&nbsp;</p><p>[ReasonsForUnsubscriptionSurvey]</p>')."'),
        (12, NOW(), 1, 'Standard Umfrage nach Abmeldung abgeschlossen', 'RFUSurveyConfirmation', '', '"._LJFLJ($_Ji0Cf, '<p>Vielen Dank f&uuml;r Ihre Teilnahme an unserer Umfrage.</p>')."')"
        ;

       else
       $_QLfol = "INSERT INTO `$_jfQtI` (`id`, `CreateDate`, `IsDefault`, `Name`, `Type`, `RedirectURL`, `HTMLPage`) VALUES
        (1, NOW(), 1, 'Default error page', 'Error', '', '"._LJFLJ($_Ji0Cf, '<p><b>An error occurred:</b></p><p>[ERRORPAGEMESSAGE]</p>'.'<p>&nbsp;</p><BackLink><p id="gobacklink">Click <a href="javascript:history.back();">here to complete the form.</a></p></BackLink>')."'),
        (2, NOW(), 1, 'Default subscription successfully', 'Subscribe', '', '"._LJFLJ($_Ji0Cf, '<p>Your email address was added successfully.</p>')."'),
        (3, NOW(), 1, 'Default confirmation link for subscription sent', 'SubscribeConfirmation', '', '"._LJFLJ($_Ji0Cf, '<p>Thank you for your subscription.</p><p>We have sent an email with a confirmation link.<br />Please click on this link to confirm your subscribtion.</p>')."'),
        (4, NOW(), 1, 'Default unsubscription successfully', 'Unsubscribe', '', '"._LJFLJ($_Ji0Cf, '<p>Your email address was removed from our mailinglist.</p>')."'),
        (5, NOW(), 1, 'Default confirmation link for unsubscription sent', 'UnsubscribeConfirmation', '', '"._LJFLJ($_Ji0Cf, '<p>We have got your unsubscription request.</p><p>We have sent an email with a confirmation link.<br />Please click on this link to confirm your unsubscribtion request.</p>')."'),
        (6, NOW(), 1, 'Default confirmation link to confirm editing sent', 'EditConfirmation', '', '"._LJFLJ($_Ji0Cf, '<p>Thank you for editing your personal data.</p><p>We have sent an email with a confirmation link.<br />Please click on this link to confirm your changes.</p>')."'),
        (7, NOW(), 1, 'Default editing successfully', 'Edit', '', '". _LJFLJ($_Ji0Cf, '<p>Your changes saved successfully.</p>') ."'),
        (8, NOW(), 1, 'Default editing rejected', 'EditReject', '', '". _LJFLJ($_Ji0Cf, '<p>Your changes are discarded.</p>') ."'),
        (9, NOW(), 1, 'Default editing error', 'EditError', '', '". _LJFLJ($_Ji0Cf, '<p><b>An error while editing occurred:</b></p><p>[ERRORPAGEMESSAGE]</p>'.'<p>&nbsp;</p><p id="gobacklink">Click <a href="javascript:history.back();">here to complete the form.</a></p>') ."'),
        (10, NOW(), 1, 'Default intermediate page click on unsubscribe link', 'UnsubscribeBridge', '', '"._LJFLJ($_Ji0Cf, '<form><p>Confirmation of your newsletter unsubscription:<br /><br /><input type="submit" value="Yes, unsubscribe me." /></p></form>')."'),
        (11, NOW(), 1, 'Default unsubscription successfully with survey', 'Unsubscribe', '', '"._LJFLJ($_Ji0Cf, '<p>Your email address was removed from our mailinglist.</p><p>Allow us to ask you why you have unsubscriped?</p><p>&nbsp;</p><p>[ReasonsForUnsubscriptionSurvey]</p>')."')
        (12, NOW(), 1, 'Default survey after unsubscription done', 'RFUSurveyConfirmation', '', '"._LJFLJ($_Ji0Cf, '<p>Many thanks for your participation on our survey.</p>')."')"
        ;

     $_QLfol = utf8_encode($_QLfol);
     mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
   }
 }

 function _LJFLJ($_Ji1QJ, $_QLoli) {
    //$_Ji1QJ = str_replace("css/default.css", ScriptBaseURL."css/default.css", $_Ji1QJ);
    return _L81BJ($_Ji1QJ, "<SHOW:ANSWERTEXT>", "</SHOW:ANSWERTEXT>", $_QLoli);
 }

 function _LJFP0(){
   global $_Ifi1J, $INTERFACE_LANGUAGE, $_Ij8oL, $_QLttI;

   $_Ji0IC = mysql_query("SELECT count(*) FROM `$_Ifi1J`", $_QLttI);
   $_QLO0f = mysql_fetch_row($_Ji0IC);
   mysql_free_result($_Ji0IC);
   if($_QLO0f[0] == 0) {

     $_QLfol = "SELECT `fieldname`, `text` FROM `$_Ij8oL` WHERE `language`='$INTERFACE_LANGUAGE'";
     $_Ji1lL = mysql_query($_QLfol, $_QLttI);
     $_JiQtL = array();
     while($_I1OfI=mysql_fetch_assoc($_Ji1lL)) {
       $_JiQtL[$_I1OfI["fieldname"]] = $_I1OfI["text"];
     }
     mysql_free_result($_Ji1lL);

     if($INTERFACE_LANGUAGE == "de") {
       $_QLfol = "INSERT INTO `$_Ifi1J` (`id`, `CreateDate`, `IsDefault`, `Name`, `EMailFormatErrorText`, `RequiredFieldsErrorText`, `ErrorText`, `SubscribeOKText`, `SubscribeTextConfirmationRequired`, `EMailAddressAlwaysInList`,
                                             `EMailAddressBlacklisted`, `UnsubscribeOKText`, `UnsubscribeTextConfirmationRequired`, `EMailAddressNotInList`, `SubscribeTextOnConfirmationSucc`, `UnsubscribeTextOnConfirmationSucc`,
                                             `SubscribeTextOnConfirmationFailure`, `UnsubscribeTextOnConfirmationFailure`, `SubscribeTextOnConfirmationAgain`, `CaptchaStringIncorrect`, `GroupsAsMandatoryFieldError`, `PrivacyPolicyAcceptanceError`
                                             ) VALUES
       (1, NOW(), 1, 'Standard', 'Das Format der E-Mail-Adresse ist nicht korrekt.', 'Folgende Pflichtfelder wurden nicht ausgef&uuml;llt:', 'Es ist ein Fehler aufgetreten:', 'Vielen Dank f&uuml;r Ihre Anmeldung.',
         'Vielen Dank f&uuml;r Ihre Anmeldung. Ihnen wurde soeben eine E-Mail mit einem Best&auml;tigungslink zugesendet. Sie m&uuml;ssen auf diesen Link klicken um die Anmeldung abzuschlie&szlig;en.', 'Ihre E-Mail-Adresse befindet sich bereits in unserem Verteiler.',
         'Ihre E-Mail-Adresse wurde vom Administrator gesperrt.', 'Ihre E-Mail-Adresse wurde aus dem Verteiler gel&ouml;scht.', 'Ihre E-Mail-Adresse wurde zur Austragung vorgesehen. Es wurde Ihnen soeben eine E-Mail mit einem Best&auml;tigungslink zugesendet. Klicken Sie auf diesen Best&auml;tigungslink um die Austragung abzuschlie&szlig;en.',
         'Ihre E-Mail-Adresse befindet sich nicht in unserem Verteiler.', 'Die Anmeldung zu unserem Verteiler wurde erfolgreich abgeschlossen.', 'Ihre E-Mail-Adresse wurde erfolgreich aus unserem Verteiler ausgetragen.', 'Die Anmeldung an unserem Verteiler konnte nicht erfolgreich abgeschlossen werden. Melden Sie sich bitte erneut mit Ihrer E-Mail-Adresse an.',
         'Ihre E-Mail-Adresse konnte nicht aus unserem Verteiler entfernt werden. Entweder wurde diese bereits gel&ouml;scht oder Sie m&uuml;ssen sich wegen zu langer Wartezeit nochmals abmelden.', 'Sie haben sich bereits erfolgreich an unserem Verteiler angemeldet.',
         'Geben Sie das angezeigte Wort korrekt ein.', 'Bitte w&auml;hlen Sie eine Gruppe.', 'Sie m&uuml;ssen der Datenschutzerkl&auml;rung zustimmen.')";

       $_QLfol = utf8_encode($_QLfol);
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);

       $_JiQtL["u_EMail"] = 'Die E-Mail-Adresse fehlt.';
       $_JiQtL["u_EMailFormat"] = 'Sie m&uuml;ssen das gew&uuml;nschte E-Mail-Format w&auml;hlen.';
       reset($_JiQtL);
       $_QLfol = "";
       foreach($_JiQtL as $key => $_QltJO){
         if($key != "u_EMail" && $key != "u_EMailFormat")
            $_QltJO = "Sie m&uuml;ssen das Feld '$_QltJO' ausf&uuml;llen.";
         if($_QLfol == "")
           $_QLfol = "`$key`="._LRAFO($_QltJO);
           else
           $_QLfol .= ", `$key`="._LRAFO($_QltJO);
       }
       $_QLfol = utf8_encode($_QLfol);
       $_QLfol = "UPDATE `$_Ifi1J` SET ".$_QLfol. " WHERE id=1";
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       }
       else {

         $_QLfol = "INSERT INTO `$_Ifi1J` (`id`, `CreateDate`, `IsDefault`, `Name`, `EMailFormatErrorText`, `RequiredFieldsErrorText`, `ErrorText`, `SubscribeOKText`, `SubscribeTextConfirmationRequired`, `EMailAddressAlwaysInList`,
                `EMailAddressBlacklisted`, `UnsubscribeOKText`, `UnsubscribeTextConfirmationRequired`, `EMailAddressNotInList`, `SubscribeTextOnConfirmationSucc`, `UnsubscribeTextOnConfirmationSucc`,
                `SubscribeTextOnConfirmationFailure`, `UnsubscribeTextOnConfirmationFailure`, `SubscribeTextOnConfirmationAgain`, `CaptchaStringIncorrect`, `GroupsAsMandatoryFieldError`, `PrivacyPolicyAcceptanceError`
                ) VALUES
         (1, NOW(), 1, 'Default', 'Format of email address is incorrect.', 'Following fields are not filled:', 'An error ocurred:', 'Thank you for your subscribtion.',
           'Thank you for your subscribtion. We have sent an email with a confirmation link. In the email you must click on this links to confirm your subscribtion.',
           'Your email address always exists in our mailinglist.', 'Your email address was locked by administrator.', 'Your email address was removed from our mailinglist.',
           'Your email address is marked for unsubscribtion. We have sent an email with a confirmation link. In the email you must click on this links to confirm your unsubscribtion.',
           'Your email address doesn''t exists in our mailinglist.', 'You are now subscribed to our mailinglist.', 'We have removed your email address successfully.', 'Subscribtion to our mailinglist was not successfully. You must fill in the subscribtion form again.',
           'We cant remove your email address from our mailinglist. The email address doesn''t exists or you must fill in the unsubscribtion form again.', 'Your are always subscribed to our mailinglist.',
           'Please type in the showed word correctly.', 'Please select a group.'. 'You must accept privacy policy.')";
         $_QLfol = utf8_encode($_QLfol);
         mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);

         $_JiQtL["u_EMail"] = 'Email address are missing.';
         $_JiQtL["u_EMailFormat"] = 'You must select desired email format.';
         reset($_JiQtL);
         $_QLfol = "";
         foreach($_JiQtL as $key => $_QltJO){
           if($key != "u_EMail" && $key != "u_EMailFormat")
             $_QltJO = "You must fill in field '$_QltJO'.";
           if($_QLfol == "")
             $_QLfol = "`$key`="._LRAFO($_QltJO);
             else
             $_QLfol .= ", `$key`="._LRAFO($_QltJO);
         }
         $_QLfol = utf8_encode($_QLfol);
         $_QLfol = "UPDATE `$_Ifi1J` SET ".$_QLfol. " WHERE `id`=1";
         mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);

       }
     $_QLfol = utf8_encode($_QLfol);
     mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
   }
 }

 function _L6066(){
   global $_Ijt0i, $_QLl1Q, $_QLttI;

   $_Ji0IC = mysql_query("SELECT count(*) FROM `$_Ijt0i`", $_QLttI);
   $_QLO0f = mysql_fetch_row($_Ji0IC);
   mysql_free_result($_Ji0IC);
   if($_QLO0f[0] == 0) {
     $_QLfol = "INSERT INTO `$_Ijt0i` (`id`, `CreateDate`, `IsDefault`, `Type`, `Name`, `MailLimit`, `PHPMailParams`, `HELOName`, `SMTPPersist`, `SMTPPipelining`, `SMTPTimeout`, `SMTPServer`, `SMTPPort`, `SMTPAuth`, `SMTPUsername`, `SMTPPassword`, `sendmail_path`, `sendmail_args`) VALUES".$_QLl1Q;
     $_QLfol .= "(1, NOW(), 1, 'mail', 'Standard PHP mail()', 0, '', 'localhost', 0, 0, 0, '', 25, 0, '', '', '/usr/sbin/sendmail', '-i')".$_QLl1Q;
//     $_QLfol .= "(2, NOW(), 1, 'smtpmx', 'Standard SMTP Direct (MX)', 0, '', 'localhost', 0, 0, 0, '', 25, 0, '', '', '/usr/sbin/sendmail', '-i')".$_QLl1Q;

     $_QLfol = utf8_encode($_QLfol);
     mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
   }
 }

 function _L61QE() {
  global $_jQ68I, $INTERFACE_LANGUAGE, $_QLttI;

  $_Ji0IC = mysql_query("SELECT count(*) FROM `$_jQ68I`", $_QLttI);
  $_QLO0f = mysql_fetch_row($_Ji0IC);
  mysql_free_result($_Ji0IC);
  if($_QLO0f[0] == 0) {
     if($INTERFACE_LANGUAGE == "de") {
       $_QLfol = "INSERT INTO `$_jQ68I` (`id`, `CreateDate`, `IsDefault`, `Name`, `functiontext`) VALUES ";
       $_QLfol .= "(1, NOW(), 1, 'Beispiel_Anrede',";
       $_QLfol .= ' \'a:3:{i:0;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:4:"Herr";s:10:"outputtext";s:34:"Sehr geehrter Herr [u_LastName],\r\n";}i:1;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:4:"Frau";s:10:"outputtext";s:33:"Sehr geehrte Frau [u_LastName],\r\n";}i:2;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:5:"Firma";s:10:"outputtext";s:32:"Sehr geehrte Damen und Herren,\r\n";}}\' ';
       $_QLfol .= ')';
     } else {
       $_QLfol = "INSERT INTO `$_jQ68I` (`id`, `CreateDate`, `IsDefault`, `Name`, `functiontext`) VALUES ";
       $_QLfol .= "(1, NOW(), 1, 'Sample_Salutation',";
       $_QLfol .= '\'a:3:{i:0;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:2:"Mr";s:10:"outputtext";s:26:"Dear Mr. [u_LastName],\r\n";}i:1;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:3:"Mrs";s:10:"outputtext";s:27:"Dear Mrs. [u_LastName],\r\n";}i:2;a:4:{s:9:"fieldname";s:12:"u_Salutation";s:8:"operator";s:2:"eq";s:13:"comparestring";s:4:"firm";s:10:"outputtext";s:25:"Dear Sirs and Madams,\r\n";}}\' '.")";
     }
    $_QLfol = utf8_encode($_QLfol);
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
  }
 }

 function _L61BJ(){
  global $_Ql10t, $resourcestrings, $INTERFACE_LANGUAGE, $OwnerUserId, $_JQJjJ, $_QLttI;

  if(!defined("SWM")) {
    return;
  }

  if($OwnerUserId == 0 && !$_JQJjJ){
    $_QlooO = array();
    if ($_QlCt0 = opendir(InstallPath."newsletter_templates")) {
        while (($_QlCtl = readdir($_QlCt0)) !== false) {
          if($_QlCtl != "." && !is_dir(InstallPath."newsletter_templates/".$_QlCtl) && strpos($_QlCtl, ".htm") !== false )
            $_QlooO[] = $_QlCtl;
        }
        closedir($_QlCt0);
    }

    sort($_QlooO);

    if(count($_QlooO) > 0){

      for($_Qli6J=0; $_Qli6J< count($_QlooO); $_Qli6J++){
        $_I0lji = join("", file(InstallPath."newsletter_templates/".$_QlooO[$_Qli6J]));
        if($_I0lji === false) continue;
        $_I0lji = utf8_encode($_I0lji);
        $_I0lji = SetHTMLCharSet($_I0lji, "utf-8", false);
        $_JiI11 = array();
        GetInlineFiles($_I0lji, $_JiI11);
        for($_QliOt=0; $_QliOt<count($_JiI11); $_QliOt++){
          if(strpos($_JiI11[$_QliOt], "newsletter_templates/") === false)
            $_I0lji = str_replace('"'.$_JiI11[$_QliOt].'"', '"'."newsletter_templates/".$_JiI11[$_QliOt].'"', $_I0lji);
        }

        $_I6C8f = substr($_QlooO[$_Qli6J], 0, strpos($_QlooO[$_Qli6J], "."));
        $_JiIoi = 0;
        if(stripos($_I6C8f, "template-sample") !== false)
           $_I6C8f = $resourcestrings[$INTERFACE_LANGUAGE]["000806"]." ".substr($_I6C8f, strpos($_I6C8f, "("));
           else {
             $_I6C8f = $resourcestrings[$INTERFACE_LANGUAGE]["000807"]." ".substr($_I6C8f, strpos($_I6C8f, "("));
             $_JiIoi = 1;
           }

        $_QLfol = "INSERT INTO `$_Ql10t` SET `CreateDate`=NOW(), `Name`="._LRAFO($_I6C8f).", `MailFormat`='HTML', `IsWizardable`=$_JiIoi, `MailHTMLText`="._LRAFO($_I0lji);
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }

      $_JQJjJ = true;
      include_once("savedoptions.inc.php");
      _JOOFF('NewsletterTemplatesImported', 1);
    }
  }
 }

?>

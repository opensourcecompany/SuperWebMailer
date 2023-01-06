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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  // Boolean fields of form
  $_ItI0o = Array ('PrivacyPolicyAcceptanceAsMandatoryField', 'RequestReasonForUnsubscription', 'GroupsAsCheckBoxes', 'GroupsAsMandatoryField', 'SendOptInConfirmedMail', 'SendOptOutConfirmedMail', 'SendEditConfirmedMail', 'InfoBarActive');

  $_ItIti = Array ();

  $errors = array();
  $_jQ1il = 0;

  $_Itfj8 = "";

  if(isset($_POST['FormId'])) // Formular speichern?
    $_jQ1il = intval($_POST['FormId']);
  else
    if ( isset($_POST['OneFormListId']) )
       $_jQ1il = intval($_POST['OneFormListId']);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_jQ1il == 0 && !$_QLJJ6["PrivilegeFormCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_jQ1il != 0 && !$_QLJJ6["PrivilegeFormEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }


  $OneMailingListId = 0;
  if (isset($_GET["MailingListId"]) )
     $OneMailingListId = intval($_GET["MailingListId"]);
     else
     if(isset($_POST['OneMailingListId']))
       $OneMailingListId = intval($_POST['OneMailingListId']);
     else
      if(isset($_POST['MailingListId']))
        $OneMailingListId = intval($_POST['MailingListId']);
        else {
          include_once("browseforms.php");
          exit;
        }
  if($OneMailingListId == 0) {
     include_once("browseforms.php");
     exit;
  }

  if(!_LAEJL($OneMailingListId)){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  // mailinglist data
  $_QLfol = "SELECT Name, FormsTableName, MTAsTableName, GroupsTableName, `ReasonsForUnsubscripeTableName`, forms_id, SubscriptionType, UnsubscriptionType, AllowOverrideSenderEMailAddressesWhileMailCreating, SenderFromName, SenderFromAddress, ReplyToEMailAddress, ReturnPathEMailAddress, `CcEMailAddresses`, `BCcEMailAddresses`, `SubscriptionUnsubscription` FROM $_QL88I WHERE id=$OneMailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_array($_QL8i1);
  $_jQQOO = $_QLO0f["Name"];
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_ji10i = $_QLO0f["MTAsTableName"];
  $_QljJi = $_QLO0f["GroupsTableName"];
  $_jQIIl = $_QLO0f["ReasonsForUnsubscripeTableName"];
  $_601fI = $_QLO0f["SubscriptionType"];
  $_601Oj = $_QLO0f["UnsubscriptionType"];
  $_6008l = $_QLO0f["forms_id"] == $_jQ1il;
  $_QLftI = $_QLO0f["AllowOverrideSenderEMailAddressesWhileMailCreating"];
  $_ICfJQ = $_QLO0f["SenderFromName"];
  $_Io6Lf = $_QLO0f["SenderFromAddress"];
  $_Io8Jj = $_QLO0f["ReplyToEMailAddress"];
  $_Ioftt = $_QLO0f["ReturnPathEMailAddress"];
  $_Iot6L = $_QLO0f["CcEMailAddresses"];
  $_Iot8C = $_QLO0f["BCcEMailAddresses"];
  $_JCC1C = $_QLO0f["SubscriptionUnsubscription"];
  mysql_free_result($_QL8i1);

  # Kommen wir vom mailinglistcreate.php??
  if(isset($_POST['FormEditBtn'])) { // Formular speichern?

    if(isset($_POST["PrivacyPolicyAcceptanceAsMandatoryField"]) && trim($_POST["PrivacyPolicyURLText"]) == ""){
        $errors[] = "PrivacyPolicyAcceptanceAsMandatoryField";
        $errors[] = "PrivacyPolicyURLText";
        $_POST["PrivacyPolicyURLText"] = $resourcestrings[$INTERFACE_LANGUAGE]["DefaultPrivacyPolicyURLText"] . "<!--PrivacyPolicyAcceptanceAsMandatoryFieldCheckBox-->";
    }

    if(trim($_POST["PrivacyPolicyURLText"]) !== "" && trim($_POST["PrivacyPolicyURL"]) == ""){
        $errors[] = "PrivacyPolicyURL";
        $errors[] = "PrivacyPolicyURLText";
    }

    if(!isset($_POST["UseACaptcha"]))
      $_POST["UseACaptcha"] = 0;

    if($_POST["UseACaptcha"] == 1){
      if(!file_exists('captcha/require/captcha.class.php')){
        $errors[] = "UseACaptcha";
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["CaptchaImageMakerError"];
      }
    }

    if($_POST["UseACaptcha"] == 2 || $_POST["UseACaptcha"] == 3){
      if(empty($_POST["PublicReCaptchaKey"]))
        $errors[] = "PublicReCaptchaKey";
      if(empty($_POST["PrivateReCaptchaKey"]))
        $errors[] = "PrivateReCaptchaKey";
    }

    if( isset($_POST["OptInConfirmationMailHTMLText"]) ) {
       $_POST["OptInConfirmationMailHTMLText"] = CleanUpHTML( $_POST["OptInConfirmationMailHTMLText"] );
       // fix www to http://wwww.
       $_POST["OptInConfirmationMailHTMLText"] = str_replace('href="www.', 'href="http://www.', $_POST["OptInConfirmationMailHTMLText"]);
    }

    if( isset($_POST["OptInConfirmationMailPlainText"]) )
       $_POST["OptInConfirmationMailPlainText"] = stripslashes( $_POST["OptInConfirmationMailPlainText"] ) ;


    if( isset($_POST["EditConfirmationMailHTMLText"]) ) {
       $_POST["EditConfirmationMailHTMLText"] = CleanUpHTML( $_POST["EditConfirmationMailHTMLText"] );
       // fix www to http://wwww.
       $_POST["EditConfirmationMailHTMLText"] = str_replace('href="www.', 'href="http://www.', $_POST["EditConfirmationMailHTMLText"]);
    }

    if( isset($_POST["EditConfirmationMailPlainText"]) )
       $_POST["EditConfirmationMailPlainText"] = stripslashes( $_POST["EditConfirmationMailPlainText"] ) ;

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';
    //

    if ( !empty($_POST["OverrideSubUnsubURL"]) ) {
        if(strpos($_POST["OverrideSubUnsubURL"], "http://") === false && strpos($_POST["OverrideSubUnsubURL"], "https://") === false) {
          $errors[] = 'OverrideSubUnsubURL';
        }
        $_POST["OverrideSubUnsubURL"] = _LPC1C(trim($_POST["OverrideSubUnsubURL"]));
    }

    if ( !empty($_POST["OverrideTrackingURL"]) ) {
        if(strpos($_POST["OverrideTrackingURL"], "http://") === false && strpos($_POST["OverrideTrackingURL"], "https://") === false) {
          $errors[] = 'OverrideTrackingURL';
        }
        $_POST["OverrideTrackingURL"] = _LPC1C(trim($_POST["OverrideTrackingURL"]));
    }

    if ( !empty($_POST["ExternalReasonForUnsubscriptionScript"]) ) {
        if(strpos($_POST["ExternalReasonForUnsubscriptionScript"], "http://") === false && strpos($_POST["ExternalReasonForUnsubscriptionScript"], "https://") === false) {
          $errors[] = 'ExternalReasonForUnsubscriptionScript';
        }
        $_POST["ExternalReasonForUnsubscriptionScript"] = trim($_POST["ExternalReasonForUnsubscriptionScript"]);
    }

    if ( !empty($_POST["UserDefinedFormsFolder"]) ) {
       $_POST["UserDefinedFormsFolder"] = trim($_POST["UserDefinedFormsFolder"]);
       $_606QI=array("default_subscribeunsubscribe_page.htm", "default_edit_page.htm", "default_errorpage.htm");
       for($_Qli6J=0;$_Qli6J<count($_606QI);$_Qli6J++){
         if(!file_exists(InstallPath.$_POST["UserDefinedFormsFolder"]."/".$_606QI[$_Qli6J]))
            $errors[] = 'UserDefinedFormsFolder';
            else{
              if(file(InstallPath.$_POST["UserDefinedFormsFolder"]."/".$_606QI[$_Qli6J]) === false) {
                 $errors[] = 'UserDefinedFormsFolder';
              }
            }
       }
    }

    if($_QLftI) {
      if ( (!isset($_POST['SenderFromAddress'])) || (trim($_POST['SenderFromAddress']) == "") || ( !_L8JLR($_POST['SenderFromAddress']) ) )
        $errors[] = 'SenderFromAddress';
      if ( (isset($_POST['ReturnPathEMailAddress'])) && ($_POST['ReturnPathEMailAddress'] != "") && ( !_L8JLR($_POST['ReturnPathEMailAddress']) ) )
        $errors[] = 'ReturnPathEMailAddress';


           if ( (isset($_POST['ReplyToEMailAddress'])) && ($_POST['ReplyToEMailAddress'] != "")  ) {
             $_Io8tI = explode(",", $_POST['ReplyToEMailAddress']);
             $_I1Ilj = false;
             for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
               $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
               if( !_L8JAD($_Io8tI[$_Qli6J]) ) {
                 $_I1Ilj = true;
                 break;
               }
             }
             if($_I1Ilj)
               $errors[] = 'ReplyToEMailAddress';
               else
               $_POST['ReplyToEMailAddress'] = implode(",", $_Io8tI);
           }

      if ( (isset($_POST['CcEMailAddresses'])) && ($_POST['CcEMailAddresses'] != "")  ) {
          $_Io8tI = explode(",", $_POST['CcEMailAddresses']);
          $_I1Ilj = false;
          for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
             $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
             if( !_L8JLR($_Io8tI[$_Qli6J]) ) {
                 $_I1Ilj = true;
                 break;
               }
          }
          if($_I1Ilj)
             $errors[] = 'CcEMailAddresses';
             else
             $_POST['CcEMailAddresses'] = implode(",", $_Io8tI);
      }

      if ( (isset($_POST['BCcEMailAddresses'])) && ($_POST['BCcEMailAddresses'] != "")  ) {
          $_Io8tI = explode(",", $_POST['BCcEMailAddresses']);
          $_I1Ilj = false;
          for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
             $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
             if( !_L8JLR($_Io8tI[$_Qli6J]) ) {
               $_I1Ilj = true;
               break;
             }
          }
          if($_I1Ilj)
            $errors[] = 'BCcEMailAddresses';
            else
            $_POST['BCcEMailAddresses'] = implode(",", $_Io8tI);
      }

    }

    if ( ((!isset($_POST['mtas'])) || (count($_POST['mtas']) == 0)) && $_QLftI )
       $errors[] = 'mtas[]';

    if(!isset($_POST["messages_id"]))
       $errors[] = 'messages_id';
       else
       $_POST["messages_id"] = intval($_POST["messages_id"]);

    if(!isset($_POST["SubscribeErrorPage"]))
       $errors[] = 'SubscribeErrorPage';
    if(!isset($_POST["SubscribeAcceptedPage"]) && $_POST['SubscriptionType'] == "DoubleOptIn")
       $errors[] = 'SubscribeAcceptedPage';
    if(!isset($_POST["SubscribeConfirmationPage"]))
       $errors[] = 'SubscribeConfirmationPage';

    if(!isset($_POST["UnsubscribeErrorPage"]))
       $errors[] = 'UnsubscribeErrorPage';
    if(!isset($_POST["UnsubscribeAcceptedPage"]) && $_POST['UnsubscriptionType'] == "DoubleOptOut")
       $errors[] = 'UnsubscribeAcceptedPage';
    if(!isset($_POST["UnsubscribeConfirmationPage"]))
       $errors[] = 'UnsubscribeConfirmationPage';

    if(!isset($_POST["EditAcceptedPage"]) && $_POST['SubscriptionType'] == "DoubleOptIn")
       $errors[] = 'EditAcceptedPage';
    if(!isset($_POST["EditRejectPage"]) && $_POST['SubscriptionType'] == "DoubleOptIn")
       $errors[] = 'EditRejectPage';
    if(!isset($_POST["EditConfirmationPage"]))
       $errors[] = 'EditConfirmationPage';
    if(!isset($_POST["EditErrorPage"]))
       $errors[] = 'EditErrorPage';

    if(!isset($_POST["UnsubscribeBridgePage"]))
       $errors[] = 'UnsubscribeBridgePage';

    if(!isset($_POST["RFUSurveyConfirmationPage"]))
       $errors[] = 'RFUSurveyConfirmationPage';

    //

    if ( $_POST['SubscriptionType'] == "DoubleOptIn" ) {
      // OptIn
      if ( (!isset($_POST['OptInConfirmationMailSubject'])) || (trim($_POST['OptInConfirmationMailSubject']) == "") )
        $errors[] = 'OptInConfirmationMailSubject';

      if ( ( $_POST['OptInConfirmationMailFormat'] == "HTML" ) ) {
         if( trim( unhtmlentities( @strip_tags ( $_POST["OptInConfirmationMailHTMLText"] ), $_POST["OptInConfirmationMailEncoding"] ) ) == "")
           $errors[] = 'OptInConfirmationMailHTMLText';

         $_IoijI = array();
         _LA61D($_POST["OptInConfirmationMailHTMLText"], $_IoijI);
         if(count($_IoijI) > 0) {
           $errors[] = 'FileError_OptInConfirmationMailHTMLText';
           $_Itfj8 = join("<br />", $_IoijI);
         }

      }
      if ( $_POST['OptInConfirmationMailFormat'] == "PlainText"  ) {
         if(trim($_POST["OptInConfirmationMailPlainText"]) == "")
           $errors[] = 'OptInConfirmationMailPlainText';
      }

      if(count($errors) == 0) {
          $_Ioolt = $_POST["OptInConfirmationMailEncoding"];
          $_IooIi = $_POST["OptInConfirmationMailFormat"];
          $_IoOif = $_POST["OptInConfirmationMailSubject"];
          $_IoC0i = $_POST["OptInConfirmationMailPlainText"];

          if($_Ioolt != "utf-8" ) {
            if( !_LP8C1($_QLo06, $_Ioolt, $_IoOif) ) {
              $errors[] = 'OptInConfirmationMailEncoding';
              $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_LP8C1($_QLo06, $_Ioolt, $_IoC0i) ) {
                   $errors[] = 'OptInConfirmationMailEncoding';
                   $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_Ioolt != "utf-8" )
      } # if(count($errors) == 0)


      // Edit
      if ( (!isset($_POST['EditConfirmationMailSubject'])) || (trim($_POST['EditConfirmationMailSubject']) == "") )
        $errors[] = 'EditConfirmationMailSubject';

      if ( ( $_POST['EditConfirmationMailFormat'] == "HTML" ) ) {
         if( trim( unhtmlentities( @strip_tags ( $_POST["EditConfirmationMailHTMLText"] ), $_POST["EditConfirmationMailEncoding"] ) ) == "")
           $errors[] = 'EditConfirmationMailHTMLText';

         $_IoijI = array();
         _LA61D($_POST["EditConfirmationMailHTMLText"], $_IoijI);
         if(count($_IoijI) > 0) {
           $errors[] = 'FileError_EditConfirmationMailHTMLText';
           $_Itfj8 = join("<br />", $_IoijI);
         }

      }
      if ( $_POST['EditConfirmationMailFormat'] == "PlainText"  ) {
         if(trim($_POST["EditConfirmationMailPlainText"]) == "")
           $errors[] = 'EditConfirmationMailPlainText';
      }

      if(count($errors) == 0) {
          $_Ioolt = $_POST["EditConfirmationMailEncoding"];
          $_IooIi = $_POST["EditConfirmationMailFormat"];
          $_IoOif = $_POST["EditConfirmationMailSubject"];
          $_IoC0i = $_POST["EditConfirmationMailPlainText"];

          if($_Ioolt != "utf-8" ) {
            if( !_LP8C1($_QLo06, $_Ioolt, $_IoOif) ) {
              $errors[] = 'EditConfirmationMailEncoding';
              $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_LP8C1($_QLo06, $_Ioolt, $_IoC0i) ) {
                   $errors[] = 'EditConfirmationMailEncoding';
                   $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_Ioolt != "utf-8" )
      } # if(count($errors) == 0)

      //
    }

    if ( $_POST['UnsubscriptionType'] == "DoubleOptOut" ) {
      if ( (!isset($_POST['OptOutConfirmationMailSubject'])) || (trim($_POST['OptOutConfirmationMailSubject']) == "") )
        $errors[] = 'OptOutConfirmationMailSubject';

      if (( $_POST['OptOutConfirmationMailFormat'] == "HTML" ) ) {

         // fix www to http://wwww.
         $_POST["OptOutConfirmationMailHTMLText"] = str_replace('href="www.', 'href="http://www.', $_POST["OptOutConfirmationMailHTMLText"]);

         if( trim( unhtmlentities( @strip_tags ( $_POST["OptOutConfirmationMailHTMLText"] ), $_POST["OptOutConfirmationMailEncoding"] ) ) == "")
           $errors[] = 'OptOutConfirmationMailHTMLText';

         $_IoijI = array();
         _LA61D($_POST["OptOutConfirmationMailHTMLText"], $_IoijI);
         if(count($_IoijI) > 0) {
           $errors[] = 'FileError_OptOutConfirmationMailHTMLText';
           $_Itfj8 = join("<br />", $_IoijI);
         }

      }
      if ( $_POST['OptOutConfirmationMailFormat'] == "PlainText"  ) {
         if(trim($_POST["OptOutConfirmationMailPlainText"]) == "")
           $errors[] = 'OptOutConfirmationMailPlainText';
      }

      if(count($errors) == 0) {
          $_Ioolt = $_POST["OptOutConfirmationMailEncoding"];
          $_IooIi = $_POST["OptOutConfirmationMailFormat"];
          $_IoOif = $_POST["OptOutConfirmationMailSubject"];
          $_IoC0i = $_POST["OptOutConfirmationMailPlainText"];

          if($_Ioolt != "utf-8" ) {
            if( !_LP8C1($_QLo06, $_Ioolt, $_IoOif) ) {
              $errors[] = 'OptOutConfirmationMailEncoding';
              $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_LP8C1($_QLo06, $_Ioolt, $_IoC0i) ) {
                   $errors[] = 'OptOutConfirmationMailEncoding';
                   $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_Ioolt != "utf-8" )
      } # if(count($errors) == 0)

    }

    if ( isset($_POST['SendOptInConfirmedMail']) ) {
      if ( (!isset($_POST['OptInConfirmedMailSubject'])) || (trim($_POST['OptInConfirmedMailSubject']) == "") )
        $errors[] = 'OptInConfirmedMailSubject';

      if (( $_POST['OptInConfirmedMailFormat'] == "HTML" ) ) {

         // fix www to http://wwww.
         $_POST["OptInConfirmedMailHTMLText"] = str_replace('href="www.', 'href="http://www.', $_POST["OptInConfirmedMailHTMLText"]);

         if( trim( unhtmlentities( @strip_tags ( $_POST["OptInConfirmedMailHTMLText"] ), $_POST["OptInConfirmedMailEncoding"] ) ) == "")
           $errors[] = 'OptInConfirmedMailHTMLText';

         $_IoijI = array();
         _LA61D($_POST["OptInConfirmedMailHTMLText"], $_IoijI);
         if(count($_IoijI) > 0) {
           $errors[] = 'FileError_OptInConfirmedMailHTMLText';
           $_Itfj8 = join("<br />", $_IoijI);
         }

      }
      if ( $_POST['OptInConfirmedMailFormat'] == "PlainText"  ) {
         if(trim($_POST["OptInConfirmedMailPlainText"]) == "")
           $errors[] = 'OptInConfirmedMailPlainText';
      }

      if(count($errors) == 0) {
          $_Ioolt = $_POST["OptInConfirmedMailEncoding"];
          $_IooIi = $_POST["OptInConfirmedMailFormat"];
          $_IoOif = $_POST["OptInConfirmedMailSubject"];
          $_IoC0i = $_POST["OptInConfirmedMailPlainText"];

          if($_Ioolt != "utf-8" ) {
            if( !_LP8C1($_QLo06, $_Ioolt, $_IoOif) ) {
              $errors[] = 'OptInConfirmedMailEncoding';
              $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_LP8C1($_QLo06, $_Ioolt, $_IoC0i) ) {
                   $errors[] = 'OptInConfirmedMailEncoding';
                   $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_Ioolt != "utf-8" )
      } # if(count($errors) == 0)

      if(count($errors) == 0) {
        if(isset($_POST["OptInConfirmedMailHTMLText"])){
          if(!_LAFFA($_POST["OptInConfirmedMailHTMLText"], array("SubscriptionUnsubscription" => $_JCC1C), $_Itfj8)){
             $errors[] = 'OptInConfirmedMailHTMLText';
          }
        }
        if(!count($errors) && isset($_POST["OptInConfirmedMailPlainText"])){
          if(!_LAFFA($_POST["OptInConfirmedMailPlainText"], array("SubscriptionUnsubscription" => $_JCC1C), $_Itfj8)){
             $errors[] = 'OptInConfirmedMailPlainText';
          }
        }
      }

      if(count($errors) == 0) {
          $_IoLj0 = 0;
          $_IoLOt = _LADQC($_POST, $_IoLj0, "OptInConfirmed");

          if($_IoLOt > $_IoLj0) {
            $_Itfj8 = "OptInConfirmed: ".sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MEMORY_LIMIT_EXCEEDED"], _LAJRC($_IoLOt), _LAJRC($_IoLj0));
          }
      }

    }

    if ( isset($_POST['SendOptOutConfirmedMail']) ) {
      if ( (!isset($_POST['OptOutConfirmedMailSubject'])) || (trim($_POST['OptOutConfirmedMailSubject']) == "") )
        $errors[] = 'OptOutConfirmedMailSubject';

      if (( $_POST['OptOutConfirmedMailFormat'] == "HTML" ) ) {

         // fix www to http://wwww.
         $_POST["OptOutConfirmedMailHTMLText"] = str_replace('href="www.', 'href="http://www.', $_POST["OptOutConfirmedMailHTMLText"]);

         if( trim( unhtmlentities( @strip_tags ( $_POST["OptOutConfirmedMailHTMLText"] ), $_POST["OptOutConfirmedMailEncoding"] ) ) == "")
           $errors[] = 'OptOutConfirmedMailHTMLText';

         $_IoijI = array();
         _LA61D($_POST["OptOutConfirmedMailHTMLText"], $_IoijI);
         if(count($_IoijI) > 0) {
           $errors[] = 'FileError_OptOutConfirmedMailHTMLText';
           $_Itfj8 = join("<br />", $_IoijI);
         }

      }
      if ( $_POST['OptOutConfirmedMailFormat'] == "PlainText"  ) {
         if(trim($_POST["OptOutConfirmedMailPlainText"]) == "")
           $errors[] = 'OptOutConfirmedMailPlainText';
      }

      if(count($errors) == 0) {
          $_Ioolt = $_POST["OptOutConfirmedMailEncoding"];
          $_IooIi = $_POST["OptOutConfirmedMailFormat"];
          $_IoOif = $_POST["OptOutConfirmedMailSubject"];
          $_IoC0i = $_POST["OptOutConfirmedMailPlainText"];

          if($_Ioolt != "utf-8" ) {
            if( !_LP8C1($_QLo06, $_Ioolt, $_IoOif) ) {
              $errors[] = 'OptOutConfirmedMailEncoding';
              $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_LP8C1($_QLo06, $_Ioolt, $_IoC0i) ) {
                   $errors[] = 'OptOutConfirmedMailEncoding';
                   $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_Ioolt != "utf-8" )
      } # if(count($errors) == 0)


      if(count($errors) == 0) {
          $_IoLj0 = 0;
          $_IoLOt = _LADQC($_POST, $_IoLj0, "OptOutConfirmed");

          if($_IoLOt > $_IoLj0) {
            $_Itfj8 = "OptOutConfirmed: ".sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MEMORY_LIMIT_EXCEEDED"], _LAJRC($_IoLOt), _LAJRC($_IoLj0));
          }
      }

    }

    if(defined("SWM")){
      $_61tQQ = true;
      if(!isset($_POST["InfoBarActive"]))
        $_61tQQ = false;
      if($_61tQQ){  
        
        if(!isset($_POST["InfoBarSchemeColorName"]) || trim($_POST["InfoBarSchemeColorName"]) == "" )
          $errors[] = 'InfoBarSchemeColorName';
        if(!isset($_POST["InfoBarSrcLanguage"]) || trim($_POST["InfoBarSrcLanguage"]) == "" )
          $errors[] = 'InfoBarSrcLanguage';
        if(isset($_POST["InfoBarSpacer"]))  
          $_POST["InfoBarSpacer"] = trim($_POST["InfoBarSpacer"]);
          else
          $_POST["InfoBarSpacer"] = "";
        if(!isset($_POST["Link"]))
         unset($_POST["InfoBarActive"]); //disable 
        if(isset($_POST["Link"])){
          foreach($_POST["Link"] as $key => $_QltJO){
           if( !isset($_POST["URL"][$key]) || trim($_POST["URL"][$key]) == "" ) 
              $errors[] = "URL[" . $key . "]";
          }
        }
        
        // create internal InfoBarLinksArray

        if($_jQ1il > 0){
          // take saved variant as default
          $_QLfol= "SELECT InfoBarSupportedTranslationLanguages, InfoBarLinksArray FROM `$_IfJoo` WHERE id=$_jQ1il";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);
          
          $ML["InfoBarSupportedTranslationLanguages"] = @unserialize($_QLO0f["InfoBarSupportedTranslationLanguages"]);
          $ML["InfoBarLinksArray"] = @unserialize($_QLO0f["InfoBarLinksArray"]);
        }
        if( !isset($ML) || !isset($ML["InfoBarSupportedTranslationLanguages"]) || !isset($ML["InfoBarLinksArray"]) || $ML["InfoBarSupportedTranslationLanguages"] === false || $ML["InfoBarLinksArray"] === false)
           _LBLPC($ML["InfoBarSupportedTranslationLanguages"], $ML["InfoBarLinksArray"]);

        $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3
        
        foreach($ML["InfoBarLinksArray"] as $_Qli6J => $_QltJO){
         $_jfCoo = new TAltBrowserLinkInfoBarLink();
         $_jfCoo->LinkType = $_QltJO->LinkType;
         $_jfCoo->Checked = isset($_POST["Link"]) && isset($_POST["Link"][$_jfCoo->LinkType]);
         
         $_jfCoo->URL = isset($_POST["URL"]) && isset($_POST["URL"][$_jfCoo->LinkType]) ? $_POST["URL"][$_jfCoo->LinkType] : "";
         $_jfCoo->Title = isset($_POST["Title"]) && isset($_POST["Title"][$_jfCoo->LinkType]) ? $_POST["Title"][$_jfCoo->LinkType] : "";
         $_jfCoo->Text = isset($_POST["Text"]) && isset($_POST["Text"][$_jfCoo->LinkType]) ? $_POST["Text"][$_jfCoo->LinkType] : "";

         $_jfCoo->internalCaption = $ML["InfoBarLinksArray"][$_Qli6J]->internalCaption;
         if(empty($_jfCoo->URL))
           $_jfCoo->URL = $ML["InfoBarLinksArray"][$_Qli6J]->URL;
         if(empty($_jfCoo->Title))
           $_jfCoo->Title = $ML["InfoBarLinksArray"][$_Qli6J]->Title;
         if(empty($_jfCoo->Text))
           $_jfCoo->Text = $ML["InfoBarLinksArray"][$_Qli6J]->Text;
         
         $_j8LoO[$_QltJO->LinkType] = $_jfCoo;
        }
        
        $_POST["InfoBarLinksArray"] = serialize($_j8LoO);
        _LBLPR($_j8l18);
        $_POST["InfoBarSupportedTranslationLanguages"] = serialize($_j8l18);
      }    

      if(isset($_POST["Link"]))
        unset($_POST["Link"]);
      if(isset($_POST["URL"]))
        unset($_POST["URL"]);
      if(isset($_POST["Title"]))
        unset($_POST["Title"]);
      if(isset($_POST["Text"]))
        unset($_POST["Text"]);
      
    }

    if(count($errors) > 0) {
        if($_Itfj8 == "")
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        if($_Itfj8 == "")
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
          else
          $_Itfj8 = $_Itfj8."<br /><br />".$resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        // Falscheingaben
        if(isset($_POST["OptInConfirmationMailPlainText"]) && trim($_POST["OptInConfirmationMailPlainText"]) == "" && isset($_POST["OptInConfirmationMailHTMLText"]) )
           $_POST["OptInConfirmationMailPlainText"] = _LC6CP(_LBDA8 ( $_POST["OptInConfirmationMailHTMLText"] , $_QLo06 ));
        if(isset($_POST["OptOutConfirmationMailPlainText"]) && trim($_POST["OptOutConfirmationMailPlainText"]) == "" && isset($_POST["OptOutConfirmationMailHTMLText"]) )
           $_POST["OptOutConfirmationMailPlainText"] = _LC6CP(_LBDA8 ( $_POST["OptOutConfirmationMailHTMLText"], $_QLo06 ));

        if(isset($_POST["EditConfirmationMailPlainText"]) && trim($_POST["EditConfirmationMailPlainText"]) == "" && isset($_POST["EditConfirmationMailHTMLText"]) )
           $_POST["EditConfirmationMailPlainText"] = _LC6CP(_LBDA8 ( $_POST["EditConfirmationMailHTMLText"] , $_QLo06 ));

        if(isset($_POST["OptInConfirmedMailPlainText"]) && trim($_POST["OptInConfirmedMailPlainText"]) == "" && isset($_POST["OptInConfirmedMailHTMLText"]) )
           $_POST["OptInConfirmedMailPlainText"] = _LC6CP(_LBDA8 ( $_POST["OptInConfirmedMailHTMLText"], $_QLo06 ));
        if(isset($_POST["OptOutConfirmedMailPlainText"]) && trim($_POST["OptOutConfirmedMailPlainText"]) == "" && isset($_POST["OptOutConfirmedMailHTMLText"]) )
           $_POST["OptOutConfirmedMailPlainText"] = _LC6CP(_LBDA8 ( $_POST["OptOutConfirmedMailHTMLText"], $_QLo06 ));

        // overide not allowed
        if(!$_QLftI) {
          $_POST["SenderFromName"] = "";
          $_POST["SenderFromAddress"] = "";
          $_POST["ReplyToEMailAddress"] = "";
          $_POST["ReturnPathEMailAddress"] = "";
          $_POST["CcEMailAddresses"] = "";
          $_POST["BCcEMailAddresses"] = "";
        }

        $_IoLOO = $_POST;

        if(isset($_IoLOO["OptInConfirmedAttachments"])) {
           for($_Qli6J=0; $_Qli6J<count($_IoLOO["OptInConfirmedAttachments"]); $_Qli6J++) {
              $_IoLOO["OptInConfirmedAttachments"][$_Qli6J] = $_IoLOO["OptInConfirmedAttachments"][$_Qli6J];
           }
           $_POST["OptInConfirmedAttachments"] = $_IoLOO["OptInConfirmedAttachments"];
           $_IoLOO["OptInConfirmedAttachments"] = serialize($_IoLOO["OptInConfirmedAttachments"]);
        } else
          $_IoLOO["OptInConfirmedAttachments"] = "";

        if(isset($_IoLOO["OptOutConfirmedAttachments"])) {
           for($_Qli6J=0; $_Qli6J<count($_IoLOO["OptOutConfirmedAttachments"]); $_Qli6J++) {
              $_IoLOO["OptOutConfirmedAttachments"][$_Qli6J] = $_IoLOO["OptOutConfirmedAttachments"][$_Qli6J];
           }
           $_POST["OptOutConfirmedAttachments"] = $_IoLOO["OptOutConfirmedAttachments"];
           $_IoLOO["OptOutConfirmedAttachments"] = serialize($_IoLOO["OptOutConfirmedAttachments"]);
        } else
          $_IoLOO["OptOutConfirmedAttachments"] = "";

        _LR0BL($_jQ1il, $_IoLOO);
        $_POST["FormId"] = $_jQ1il;
        
        if($_jQ1il != 0){
          $_Io6Lf = "";
          $_Ioftt = "";
          if(!_LB6CD($_Io6Lf, $_Ioftt, No_rtMailingListForms, $OneMailingListId, $_jQ1il)){
             $_Itfj8 .= "<br />". sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DomainAlignmentError"], $_Io6Lf, $_Ioftt);
          }
        }   
        
        
      }
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_jQQOO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000068"], $_Itfj8, 'formedit', 'formedit_snipped.htm');
  $_QLJfI = str_replace("PRODUCTAPPNAME", $AppName, $_QLJfI);
  $_QLJfI = str_replace("%TEMPLATES_DIR%", _LOC8P(), $_QLJfI);


  $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

  #
  // ********* List of MTAs SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_Ijt0i";
  $_QlI6f .= " ORDER BY Name ASC";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);

  if(isset($_jlQJQ))
    unset($_jlQJQ);
  $_jlQJQ = array();
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
   $_jlQJQ[$_QLO0f["id"]] = $_QLO0f["Name"];
  }
  mysql_free_result($_QL8i1);
  // ********* List of MTAs query END

  #### normal placeholders
  $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language="._LRAFO($INTERFACE_LANGUAGE)." AND fieldname <> "._LRAFO("u_EMailFormat");
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_I1OoI=array();
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
   $_I1OoI[] =  sprintf("new Array('[%s]', '%s')", $_QLO0f["fieldname"], $_QLO0f["text"]);
  }
  # defaults
  foreach ($_Iol8t as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QLJfI = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
  mysql_free_result($_QL8i1);

  #### special on subscribe placeholders
  unset($_I1OoI);
  $_I1OoI=array();
  reset($_JQtOo);
  foreach ($_JQtOo as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QLJfI = str_replace ("new Array('[SUBSCRIBEPLACEHOLDER]', 'SUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
  # special on unsubscribe placeholders
  unset($_I1OoI);
  $_I1OoI=array();
  reset($_JQOIC);
  foreach ($_JQOIC as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QLJfI = str_replace ("new Array('[UNSUBSCRIBEPLACEHOLDER]', 'UNSUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
  #### special newsletter unsubscribe placeholders
  unset($_I1OoI);
  $_I1OoI=array();
  reset($_IolCJ);
  foreach ($_IolCJ as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QLJfI = str_replace ("new Array('[NEWSLETTER_UNSUBSCRIBEPLACEHOLDER]', 'NEWSLETTER_UNSUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);

  #### special on edit placeholders
  unset($_I1OoI);
  $_I1OoI=array();
  reset($_JQOtf);
  foreach ($_JQOtf as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QLJfI = str_replace ("new Array('[EDITPLACEHOLDER]', 'EDITPLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);

  // ********* List of Redirect Pages
  $_QL8i1 = _LR0B6("Error");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:SubscribeErrorPage>", "</SHOW:SubscribeErrorPage>", $_ItlLC);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:UnsubscribeErrorPage>", "</SHOW:UnsubscribeErrorPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("SubscribeConfirmation");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:SubscribeAcceptedPage>", "</SHOW:SubscribeAcceptedPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("Subscribe");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:SubscribeConfirmationPage>", "</SHOW:SubscribeConfirmationPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("UnsubscribeConfirmation");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:UnsubscribeAcceptedPage>", "</SHOW:UnsubscribeAcceptedPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("Unsubscribe");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:UnsubscribeConfirmationPage>", "</SHOW:UnsubscribeConfirmationPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("EditConfirmation");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:EditAcceptedPage>", "</SHOW:EditAcceptedPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("Edit");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:EditConfirmationPage>", "</SHOW:EditConfirmationPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("EditReject");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:EditRejectPage>", "</SHOW:EditRejectPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("EditError");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:EditErrorPage>", "</SHOW:EditErrorPage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("UnsubscribeBridge");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:UnsubscribeBridgePage>", "</SHOW:UnsubscribeBridgePage>", $_ItlLC);
  //
  $_QL8i1 = _LR0B6("RFUSurveyConfirmation");
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:RFUSurveyConfirmationPage>", "</SHOW:RFUSurveyConfirmationPage>", $_ItlLC);
  //

  // ********* List of Redirect Pages END

  // Language
  $_QLfol = "SELECT * FROM $_Ijf8l";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLoli = "";
  $_606QI=array("default_subscribeunsubscribe_page.htm", "default_edit_page.htm", "default_errorpage.htm");
  while($_QLO0f  = mysql_fetch_assoc($_QL8i1)) {

     if($_QLO0f["Language"] != "de") {

       $_IJL6o = DefaultPath.TemplatesPath."/";

       if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
         $_IJL6o .= $INTERFACE_STYLE."/";
       $_IJL6o .= $_QLO0f["Language"]."/";


       $_I0lji = true;
       for($_Qli6J=0; $_Qli6J<count($_606QI) && $_I0lji; $_Qli6J++){
         $_I1OoI = @file($_IJL6o.$_606QI[$_Qli6J]);
         if(!$_I1OoI) $_I0lji = false;
       }
       if(!$_I0lji) continue;
     }

     $_QLoli .= '<option value="'.$_QLO0f["Language"].'">'.$_QLO0f["Text"].'</option>'.$_QLl1Q;
  }
  $_QLJfI = _L81BJ($_QLJfI, '<SHOW:LANGUAGE>', '</SHOW:LANGUAGE>', $_QLoli);
  mysql_free_result($_QL8i1);
  // *************

  // Themes
  $_QLfol = "SELECT * FROM $_I1tQf";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLoli = "";
  while($_QLO0f  = mysql_fetch_assoc($_QL8i1)) {
     $_QLoli .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Text"].'</option>'.$_QLl1Q;
  }
  $_QLJfI = _L81BJ($_QLJfI, '<SHOW:THEMES>', '</SHOW:THEMES>', $_QLoli);
  mysql_free_result($_QL8i1);
  // *************

  // ********* List of Messages
  $_QL8i1 = GetMessages();
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:Messages>", "</SHOW:Messages>", $_ItlLC);
  // ********* List of Messages END

  // ********* List of Groups SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_QljJi";
  $_QlI6f .= " ORDER BY Name ASC";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);
  $_ItlLC = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_ItlLC);
  // ********* List of Groups query END

  // ********* fields
  $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language="._LRAFO($INTERFACE_LANGUAGE);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  $_IOiJ0 = _L81DB($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>");
  $_QLoli = "";
  $_Qli6J=1;
  $_IOLjO = false;
  while($_QLO0f = mysql_fetch_array($_QL8i1)) {
    if($_Qli6J == 1)
       $_Ql0fO = $_IOiJ0;
    $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', $_QLO0f["text"], $_Ql0fO);
    if($_QLO0f["fieldname"] != "u_EMail")
      $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', '<select name="fields['.$_QLO0f["fieldname"].']" size="1">'.$resourcestrings[$INTERFACE_LANGUAGE]["000069"].$resourcestrings[$INTERFACE_LANGUAGE]["000070"].'</select>', $_Ql0fO);
      else
      $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', '<select name="fields['.$_QLO0f["fieldname"].']" size="1">'.$resourcestrings[$INTERFACE_LANGUAGE]["000070"].'</select>', $_Ql0fO);
    $_Qli6J++;
    if($_Qli6J>2) {
      $_Qli6J=1;
      $_QLoli .= $_Ql0fO;
      $_Ql0fO = "";
    }
  }
  if($_Ql0fO != "")
    $_QLoli .= $_Ql0fO;
  $_QLJfI = _L81BJ($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>", $_QLoli);
  // ********* fields END

  // mail encodings
  $_QLoli = "";
  if ( iconvExists || mbfunctionsExists ) {
    reset($_Ijt8j);
    foreach($_Ijt8j as $key => $_QltJO) {
       $_QLoli .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
    }
  }
  $_QLJfI = _L81BJ($_QLJfI, "<MAILENCODINGS>", "</MAILENCODINGS>", $_QLoli);


  if(!isset($_POST['FormEditBtn']) && $_jQ1il == 0) { // Form new?

      $_QLfol = "SELECT * FROM `$_IfJoo` WHERE `IsDefault`=1";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
        mysql_free_result($_QL8i1);

        $_61CO0 = $_QLO0f["id"];
        unset($_QLO0f["id"]);
        unset($_QLO0f["CreateDate"]);
        $_QLO0f["IsDefault"] = 0;

        $_QLfol = "INSERT INTO `$_IfJoo` SET `CreateDate`=NOW()";

        reset($_QLO0f);
        foreach($_QLO0f as $key => $_QltJO){
          $_QLfol .= ", `$key`="._LRAFO($_QltJO);
        }

        mysql_query($_QLfol, $_QLttI);
        $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
        $_I1OfI = mysql_fetch_row($_QL8i1);
        $_jQ1il = $_I1OfI[0];
        mysql_free_result($_QL8i1);
        $_QLfol = "UPDATE `$_IfJoo` SET `Name`="._LRAFO($_QLO0f["Name"]." "."($_jQ1il)")." WHERE id=$_jQ1il";
        mysql_query($_QLfol, $_QLttI);

        // copy reasons
        $_QLfol = "SELECT * FROM $_jQIIl WHERE `forms_id`=$_61CO0";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
          $_QLfol = "INSERT INTO $_jQIIl SET `forms_id`=$_jQ1il, `Reason`="._LRAFO($_QLO0f["Reason"]) . ", `ReasonType`="._LRAFO($_QLO0f["ReasonType"]).", `sort_order`=$_QLO0f[sort_order]";
          mysql_query($_QLfol, $_QLttI);
        }
        mysql_free_result($_QL8i1);

      }

  }

  # Form laden
  if(isset($_POST['FormEditBtn'])) { // Formular speichern?
    $ML = $_POST;

    // umwandeln, damit er es wieder findet
    unset($ML["fields"]);
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) ) {
        $ML["fields[$_IOLil]"] = $_IOCjL;
      }
    }
    $ML["FormsTableName"] = $_IfJoo;

    if(defined("SWM")){
      $_QLfol= "SELECT InfoBarSupportedTranslationLanguages, InfoBarLinksArray FROM `$_IfJoo` WHERE id=$_jQ1il";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      
      $ML["InfoBarSupportedTranslationLanguages"] = @unserialize($_QLO0f["InfoBarSupportedTranslationLanguages"]);
      $ML["InfoBarLinksArray"] = @unserialize($_QLO0f["InfoBarLinksArray"]);
      
      if($ML["InfoBarSupportedTranslationLanguages"] === false || $ML["InfoBarLinksArray"] === false)
        _LBLPC($ML["InfoBarSupportedTranslationLanguages"], $ML["InfoBarLinksArray"]);
    }  
    
    
  } else {
     if($_jQ1il != 0) {
       $_QLfol= "SELECT *, UNIX_TIMESTAMP(CreateDate) AS UCreateDate FROM `$_IfJoo` WHERE id=$_jQ1il";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $ML = mysql_fetch_assoc($_QL8i1);

       reset($ML);
       foreach($ML as $key)
         if( isset($key) && strpos($key, "MailHTMLText") !== false)
           $ML[$key] = FixCKEditorStyleProtectionForCSS($ML[$key]);

       $ML["FormsTableName"] = $_IfJoo;

       if($ML["PrivacyPolicyAcceptanceAsMandatoryField"] < 1)
         unset($ML["PrivacyPolicyAcceptanceAsMandatoryField"]);
       if($ML["PrivacyPolicyURLText"] == "" && isset($ML["PrivacyPolicyAcceptanceAsMandatoryField"]))
          $ML["PrivacyPolicyURLText"] = $resourcestrings[$INTERFACE_LANGUAGE]["DefaultPrivacyPolicyURLText"];

       if($ML["GroupsOption"] < 1)
          $ML["GroupsOption"] = 1;

       if($ML["UseCaptcha"] <= 0)
          unset($ML["UseCaptcha"]);

       if($ML["UseReCaptcha"] <= 0)
          unset($ML["UseReCaptcha"]);

       if($ML["UseReCaptchav3"] <= 0)
          unset($ML["UseReCaptchav3"]);

       $ML["UseACaptcha"] = 0;
       if(isset($ML["UseCaptcha"]))
         $ML["UseACaptcha"] = 1;

       if(isset($ML["UseReCaptcha"]))
         $ML["UseACaptcha"] = 2;
       if(isset($ML["UseReCaptchav3"]))
         $ML["UseACaptcha"] = 3;

       if($ML["RequestReasonForUnsubscription"] <= 0)
          unset($ML["RequestReasonForUnsubscription"]);

       if($ML["GroupsAsCheckBoxes"] <= 0)
          unset($ML["GroupsAsCheckBoxes"]);

       if($ML["GroupsAsMandatoryField"] <= 0)
          unset($ML["GroupsAsMandatoryField"]);

       if($ML["SendOptInConfirmedMail"] <= 0)
          unset($ML["SendOptInConfirmedMail"]);
       if($ML["SendOptOutConfirmedMail"] <= 0)
          unset($ML["SendOptOutConfirmedMail"]);

      if($ML["OptInConfirmedAttachments"] != "") {
         $ML["OptInConfirmedAttachments"] = @unserialize($ML["OptInConfirmedAttachments"]);
         if($ML["OptInConfirmedAttachments"] === false)
            $ML["OptInConfirmedAttachments"] = array();
      } else {
        $ML["OptInConfirmedAttachments"] = array();
      }

      if($ML["OptOutConfirmedAttachments"] != "") {
         $ML["OptOutConfirmedAttachments"] = @unserialize($ML["OptOutConfirmedAttachments"]);
         if($ML["OptOutConfirmedAttachments"] === false)
            $ML["OptOutConfirmedAttachments"] = array();
      } else {
        $ML["OptOutConfirmedAttachments"] = array();
      }

      if(defined("SWM")){
        if( isset($ML["InfoBarActive"]) && !$ML["InfoBarActive"] )
          unset($ML["InfoBarActive"]);
        
        $ML["InfoBarSupportedTranslationLanguages"] = @unserialize($ML["InfoBarSupportedTranslationLanguages"]);
        $ML["InfoBarLinksArray"] = @unserialize($ML["InfoBarLinksArray"]);
        if($ML["InfoBarSupportedTranslationLanguages"] === false || $ML["InfoBarLinksArray"] === false)
          _LBLPC($ML["InfoBarSupportedTranslationLanguages"], $ML["InfoBarLinksArray"]);
      }
      
       // umwandeln, damit er es wieder findet
       if($ML["fields"] != "") {
         $_IOJoI = unserialize( $ML["fields"] );
         unset($ML["fields"]);
         reset($_IOJoI);
         foreach($_IOJoI as $_IOLil => $_IOCjL) {
           if(isset($_IOJoI[$_IOLil]) ) {
             $ML["fields[$_IOLil]"] = $_IOCjL;
           }
         }
       }
       else
        unset($ML["fields"]);

       mysql_free_result($_QL8i1);
       $ML["CREATEDATE"] = date($LongDateFormat, $ML["UCreateDate"]);

       if($_QLftI) {
         if($ML["SenderFromName"] == "")
            $ML["SenderFromName"] = $_ICfJQ;
         if($ML["SenderFromAddress"] == "")
            $ML["SenderFromAddress"] = $_Io6Lf;
         if($ML["ReplyToEMailAddress"] == "")
            $ML["ReplyToEMailAddress"] = $_Io8Jj;
         if($ML["ReturnPathEMailAddress"] == "")
            $ML["ReturnPathEMailAddress"] = $_Ioftt;
         if($ML["CcEMailAddresses"] == "")
            $ML["CcEMailAddresses"] = $_Iot6L;
         if($ML["BCcEMailAddresses"] == "")
            $ML["BCcEMailAddresses"] = $_Iot8C;
       }

     } else {
       if($_QLftI) {
         $ML["SenderFromName"] = $_ICfJQ;
         $ML["SenderFromAddress"] = $_Io6Lf;
         $ML["ReplyToEMailAddress"] = $_Io8Jj;
         $ML["ReturnPathEMailAddress"] = $_Ioftt;
         $ML["CcEMailAddresses"] = $_Iot6L;
         $ML["BCcEMailAddresses"] = $_Iot8C;
       }
       $ML["CREATEDATE"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
       $ML["GroupsOption"] = 1;
       $ML["Language"] = $INTERFACE_LANGUAGE;
       $ML["ThemesId"] = $INTERFACE_THEMESID;
       $ML["UseACaptcha"] = 0;
       
       _LBLPC($ML["InfoBarSupportedTranslationLanguages"], $ML["InfoBarLinksArray"]);
     }

    $ML["MailingListId"] = $OneMailingListId;
    $ML["SubscriptionType"] = $_601fI;
    $ML["UnsubscriptionType"] = $_601Oj;
    $ML["FormId"] = $_jQ1il;

    // MTAs
    $_QLfol = "SELECT * FROM $_ji10i ORDER BY sortorder";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $ML["mtas"] = array();
    while ($_jlI0o=mysql_fetch_array($_QL8i1) )
      $ML["mtas"][] = $_jlI0o["mtas_id"];
    mysql_free_result($_QL8i1);
  }

  if( !isset($ML["fields[u_EMail]"]) ) {
     $ML["fields[u_EMail]"] = "visiblerequired";
  }

  // --------------- MTAs sortorder
  $_jlIOl = array();
  if(!isset($ML["mtas"]))
    $ML["mtas"] = array();
  for($_Qli6J=0; $_Qli6J<count($ML["mtas"]); $_Qli6J++) {
    $_jlIOl[$ML["mtas"][$_Qli6J]] = $_jlQJQ[$ML["mtas"][$_Qli6J]];
    unset($_jlQJQ[$ML["mtas"][$_Qli6J]]);
  }
  foreach ($_jlQJQ as $key => $_QltJO) {
    $_jlIOl[$key] = $_QltJO;
  }
  $_ItlLC = "";
  foreach ($_jlIOl as $key => $_QltJO) {
    $_ItlLC .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
  }
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MTAS>", "</SHOW:MTAS>", $_ItlLC);
  // --------------- MTAs sortorder


  // Attachments
  if(isset($ML["OptInConfirmedAttachments"]) && is_array($ML["OptInConfirmedAttachments"])) {
    $Attachments = $ML["OptInConfirmedAttachments"];
    $ML["OptInConfirmedAttachments"] = array();
    foreach($Attachments as $key => $_QltJO) {
       $ML["OptInConfirmedAttachments"][$_QltJO] = "";
    }
  }
  if(isset($ML["OptOutConfirmedAttachments"]) && is_array($ML["OptOutConfirmedAttachments"])) {
    $Attachments = $ML["OptOutConfirmedAttachments"];
    $ML["OptOutConfirmedAttachments"] = array();
    foreach($Attachments as $key => $_QltJO) {
       $ML["OptOutConfirmedAttachments"][$_QltJO] = "";
    }
  }

  $_ICQjo = 0;

  $_IC1C6 = _L81DB($_QLJfI, "<OptInConfirmedAttachments>", "</OptInConfirmedAttachments>");
  $_QlooO = "";
  $_IJljf = opendir ( substr($_IIlfi, 0, strlen($_IIlfi) - 1) );
  while (false !== ($_QlCtl = readdir($_IJljf))) {
    if (!is_dir($_IIlfi.$_QlCtl) && $_QlCtl != "." && $_QlCtl != ".." && $_QlCtl != "index.php") {
      $_QlCtl = htmlspecialchars($_QlCtl, ENT_COMPAT, $_QLo06);
      $_QlooO .= $_IC1C6.$_QLl1Q;
      $_QlooO = _L81BJ($_QlooO, "<AttachmentsName>", "</AttachmentsName>", $_QlCtl);
      $_QlooO = _L81BJ($_QlooO, "&lt;AttachmentsName&gt;", "&lt;/AttachmentsName&gt;", $_QlCtl);
      $_ICQjo++;
      $_QlooO = str_replace("AttachmentsId", 'attachchkbox_'.$_ICQjo, $_QlooO);
      if(isset($ML["OptInConfirmedAttachments"]) && isset($ML["OptInConfirmedAttachments"][$_QlCtl])) {
        $_QlooO = str_replace('id="'.'attachchkbox_'.$_ICQjo.'"', 'id="'.'attachchkbox_'.$_ICQjo.'" checked="checked"', $_QlooO);
      }
    }
  }
  closedir($_IJljf);
  if(isset($ML["OptInConfirmedAttachments"]))
    unset($ML["OptInConfirmedAttachments"]);
  $_QLJfI = _L81BJ($_QLJfI, "<OptInConfirmedAttachments>", "</OptInConfirmedAttachments>", $_QlooO);

  $_IC1C6 = _L81DB($_QLJfI, "<OptOutConfirmedAttachments>", "</OptOutConfirmedAttachments>");
  $_QlooO = "";
  $_IJljf = opendir ( substr($_IIlfi, 0, strlen($_IIlfi) - 1) );
  while (false !== ($_QlCtl = readdir($_IJljf))) {
    if (!is_dir($_IIlfi.$_QlCtl) && $_QlCtl != "." && $_QlCtl != ".." && $_QlCtl != "index.php") {
      $_QlCtl = htmlspecialchars($_QlCtl, ENT_COMPAT, $_QLo06);
      $_QlooO .= $_IC1C6.$_QLl1Q;
      $_QlooO = _L81BJ($_QlooO, "<AttachmentsName>", "</AttachmentsName>", $_QlCtl);
      $_QlooO = _L81BJ($_QlooO, "&lt;AttachmentsName&gt;", "&lt;/AttachmentsName&gt;", $_QlCtl);
      $_ICQjo++;
      $_QlooO = str_replace("AttachmentsId", 'attachchkbox_'.$_ICQjo, $_QlooO);
      if(isset($ML["OptOutConfirmedAttachments"]) && isset($ML["OptOutConfirmedAttachments"][$_QlCtl])) {
        $_QlooO = str_replace('id="'.'attachchkbox_'.$_ICQjo.'"', 'id="'.'attachchkbox_'.$_ICQjo.'" checked="checked"', $_QlooO);
      }
    }
  }
  closedir($_IJljf);
  if(isset($ML["OptOutConfirmedAttachments"]))
    unset($ML["OptOutConfirmedAttachments"]);
  $_QLJfI = _L81BJ($_QLJfI, "<OptOutConfirmedAttachments>", "</OptOutConfirmedAttachments>", $_QlooO);


  # a little bit statistics

  if(isset($ML["FormsTableName"]))
     $_QLJfI = _L81BJ($_QLJfI, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $ML["FormsTableName"] );
     else
     $_QLJfI = _L81BJ($_QLJfI, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $resourcestrings[$INTERFACE_LANGUAGE]["NEW"]);

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MAILINGLISTNAME>", "</SHOW:MAILINGLISTNAME>", "'" . $_jQQOO . "'" );
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $ML["CREATEDATE"] );
  if($_6008l)
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:ISDEFAULT>", "</SHOW:ISDEFAULT>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
    else
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:ISDEFAULT>", "</SHOW:ISDEFAULT>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  if($_601fI == 'DoubleOptIn')
     $_QLJfI = _L81BJ($_QLJfI, "<SHOW:ISDOUBLEOPTIN>", "</SHOW:ISDOUBLEOPTIN>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"] );
     else
     $_QLJfI = _L81BJ($_QLJfI, "<SHOW:ISDOUBLEOPTIN>", "</SHOW:ISDOUBLEOPTIN>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"] );
  if($_601Oj == 'DoubleOptOut')
     $_QLJfI = _L81BJ($_QLJfI, "<SHOW:ISDOUBLEOPTOUT>", "</SHOW:ISDOUBLEOPTOUT>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"] );
     else
     $_QLJfI = _L81BJ($_QLJfI, "<SHOW:ISDOUBLEOPTOUT>", "</SHOW:ISDOUBLEOPTOUT>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"] );


  $_I1OoI = _LRQ0B($OneMailingListId, $_jQ1il);
  $_61iIj = "";
  for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++){
      $_61iIj .= "<option>".$_I1OoI[$_Qli6J]."</option>".$_QLl1Q;
    }

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:FORMREFERENCES>", "</SHOW:FORMREFERENCES>",  $_61iIj);

  $_ICI0L = "";

  // GD support?
  if( !extension_loaded('gd') ) {
    $_ICI0L .= "document.getElementById('UseCaptcha1').disabled = true;".$_QLl1Q;
  }

  if($_601Oj == 'SingleOptOut') {
     $_QLJfI = _L80DF($_QLJfI, "<OptOutConfirmation>", "</OptOutConfirmation>");
  }

  if($_601fI == 'SingleOptIn') {
     $_QLJfI = _L80DF($_QLJfI, "<OptInConfirmation>", "</OptInConfirmation>");
     $_QLJfI = _L80DF($_QLJfI, "<EditConfirmation>", "</EditConfirmation>");
  }

  if(!$_QLftI) {
     $_QLJfI = _L80DF($_QLJfI, "<FormEMailAddresses>", "</FormEMailAddresses>");

  }

  $_61ioC = array("<OptOutConfirmation>", "</OptOutConfirmation>", "<OptInConfirmation>", "</OptInConfirmation>", "<EditConfirmation>", "</EditConfirmation>", "<FormEMailAddresses>", "</FormEMailAddresses>", "<OptInConfirmed>", "</OptInConfirmed>", "<OptOutConfirmed>", "</OptOutConfirmed>");
  for($_Qli6J=0; $_Qli6J<count($_61ioC); $_Qli6J++)
    $_QLJfI = str_replace($_61ioC[$_Qli6J], "", $_QLJfI);

  // Infobar  
  if(defined("SWM")){
    $_61il8 = _LBLDQ();  
    $_Ift08 = _L81DB($_QLJfI, "<InfoBarSchemeColor>");
    $_Ql0fO = "";
    foreach($_61il8 as $key => $_QltJO){
      $_Ql0fO .= $_Ift08;
      $_Ql0fO = str_replace("colorValue", $key, $_Ql0fO);
      $_Ql0fO = str_replace("colorName", $_QltJO, $_Ql0fO);
    }
    $_QLJfI = _L81BJ($_QLJfI, "<InfoBarSchemeColor>", "", $_Ql0fO);
    
    $_Ift08 = _L81DB($_QLJfI, "<InfoBarSrcLanguage>");
    _LBLPR($_j8l18);
    $_Ql0fO = "";
    for($_Qli6J=0; $_Qli6J<count($_j8l18); $_Qli6J++){
      $_Ql0fO .= $_Ift08;
      $_Ql0fO = str_replace("LangCode", $_j8l18[$_Qli6J]["code"], $_Ql0fO);
      $_Ql0fO = str_replace("LangName", $_j8l18[$_Qli6J]["Name"], $_Ql0fO);
    }
    $_QLJfI = _L81BJ($_QLJfI, "<InfoBarSrcLanguage>", "", $_Ql0fO);
      
    $_61LOI = _L81DB($_QLJfI, "<InfoBarLinks>");
    $_Ql0fO = "";
    
    $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3
    
    // $ML["InfoBarLinksArray"] ==> Array Of TAltBrowserLinkInfoBarLink
    foreach($ML["InfoBarLinksArray"] as $_Qli6J => $_QltJO){
      $_Ql0fO .= $_61LOI;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LINKTEXT>", "", $ML["InfoBarLinksArray"][$_Qli6J]->internalCaption);
      
      if($ML["InfoBarLinksArray"][$_Qli6J]->Checked)
        $_Ql0fO = str_replace('name="Link[X]"', 'name="Link[X]"' . " " . 'checked="checked"', $_Ql0fO);

      $_Ql0fO = _L81BJ($_Ql0fO, "<URL[X]>", "", $ML["InfoBarLinksArray"][$_Qli6J]->URL);  
      $_Ql0fO = _L81BJ($_Ql0fO, "<Title[X]>", "", $ML["InfoBarLinksArray"][$_Qli6J]->Title);  
      $_Ql0fO = _L81BJ($_Ql0fO, "<Text[X]>", "", $ML["InfoBarLinksArray"][$_Qli6J]->Text);  
      
      if($ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtTranslate ||
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtAttachments
         )
        $_Ql0fO = str_replace('name="URL[X]"', 'name="URL[X]"' . " " .  'readonly="readonly"', $_Ql0fO);
      
      $_Ql0fO = str_replace('="Link[X]"', '="Link[' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . ']"', $_Ql0fO);
      $_Ql0fO = str_replace('"LinkX"', '"Link' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . '"', $_Ql0fO);
      $_Ql0fO = str_replace('="URL[X]"', '="URL[' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . ']"', $_Ql0fO);
      $_Ql0fO = str_replace("'URL[X]'", "'URL[" . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . "]'", $_Ql0fO);
      $_Ql0fO = str_replace('="Title[X]"', '="Title[' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . ']"', $_Ql0fO);
      $_Ql0fO = str_replace('="Text[X]"', '="Text[' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . ']"', $_Ql0fO);
     
    }
    $_QLJfI = _L81BJ($_QLJfI, "<InfoBarLinks>", "", $_Ql0fO);
    
    $_QLJfI = str_replace("browser_altbrowserlinkplaceholders.htm", _LOC8P() . "browser_altbrowserlinkplaceholders.htm", $_QLJfI); 
  }
    
  $_QLJfI = _L8AOB($errors, $ML, $_QLJfI);

  # show Warnlabel, we cannot mark fckeditor in red because this will be saved
  if(count($errors) > 0) {
    if(in_array('OptInConfirmationMailHTMLText', $errors))
       $_ICI0L .= "if (document.getElementById('OptInConfirmationMailHTMLTextWarnLabel')) document.getElementById('OptInConfirmationMailHTMLTextWarnLabel').style.display = '';$_QLl1Q";
    if(in_array('OptOutConfirmationMailHTMLText', $errors))
       $_ICI0L .= "if(document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel')) document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel').style.display = '';$_QLl1Q";

    if(in_array('EditConfirmationMailHTMLText', $errors))
       $_ICI0L .= "if (document.getElementById('EditConfirmationMailHTMLTextWarnLabel')) document.getElementById('EditConfirmationMailHTMLTextWarnLabel').style.display = '';$_QLl1Q";

    if(in_array('OptInConfirmedMailHTMLText', $errors))
       $_ICI0L .= "if (document.getElementById('OptInConfirmedMailHTMLTextWarnLabel')) document.getElementById('OptInConfirmedMailHTMLTextWarnLabel').style.display = '';$_QLl1Q";
    if(in_array('OptOutConfirmedMailHTMLText', $errors))
       $_ICI0L .= "if(document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel')) document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel').style.display = '';$_QLl1Q";

    // file errors
    if(in_array('FileError_OptInConfirmationMailHTMLText', $errors))
       $_ICI0L .= "if (document.getElementById('OptInConfirmationMailHTMLTextWarnLabel')) {document.getElementById('OptInConfirmationMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('OptInConfirmationMailHTMLTextWarnLabel').style.display = '';$_QLl1Q}";
    if(in_array('FileError_OptOutConfirmationMailHTMLText', $errors))
       $_ICI0L .= "if(document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel')) {document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel').style.display = '';$_QLl1Q}";

    if(in_array('FileError_EditConfirmationMailHTMLText', $errors))
       $_ICI0L .= "if (document.getElementById('EditConfirmationMailHTMLTextWarnLabel')) {document.getElementById('EditConfirmationMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('EditConfirmationMailHTMLTextWarnLabel').style.display = '';$_QLl1Q}";

    if(in_array('FileError_OptInConfirmedMailHTMLText', $errors))
       $_ICI0L .= "if (document.getElementById('OptInConfirmedMailHTMLTextWarnLabel')) {document.getElementById('OptInConfirmedMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('OptInConfirmedMailHTMLTextWarnLabel').style.display = '';$_QLl1Q}";
    if(in_array('FileError_OptOutConfirmedMailHTMLText', $errors))
       $_ICI0L .= "if(document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel')) {document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel').style.display = '';$_QLl1Q}";
  }
  $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_ICI0L, $_QLJfI);
  $_QLJfI = str_replace('?MailingListId=', '?MailingListId='.$OneMailingListId, $_QLJfI);


  $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
  $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

  $_QLJJ6 = _LPALQ($UserId);

  if(!$_QLJJ6["PrivilegeMTABrowse"]) {
    $_QLoli = _JJC0E($_QLoli, "browsemtas.php");
  }

  if($OwnerUserId != 0) {
    if(!$_QLJJ6["PrivilegePageBrowse"]) {
      $_QLoli = _JJC0E($_QLoli, "browsepages.php");
    }
    if(!$_QLJJ6["PrivilegeMessageBrowse"]) {
      $_QLoli = _JJC0E($_QLoli, "browsemessages.php");
    }
  }

  $_QLJfI = $_ICIIQ.$_QLoli;

  print $_QLJfI;


  function _LR0BL(&$_jQ1il, $_I6tLJ) {
    global $_QL88I, $_QlQot, $_ItI0o, $_ItIti, $_IfJoo, $_ji10i, $_6008l, $_QLttI;

    if(isset($_I6tLJ["UseACaptcha"])) {

      if($_I6tLJ["UseACaptcha"] == 0){
        $_I6tLJ["UseCaptcha"] = 0;
        $_I6tLJ["UseReCaptcha"] = 0;
        $_I6tLJ["UseReCaptchav3"] = 0;
        $_I6tLJ["PublicReCaptchaKey"] = "";
        $_I6tLJ["PrivateReCaptchaKey"] = "";
      }

      if($_I6tLJ["UseACaptcha"] == 1){
        $_I6tLJ["UseCaptcha"] = 1;
        $_I6tLJ["UseReCaptcha"] = 0;
        $_I6tLJ["UseReCaptchav3"] = 0;
        $_I6tLJ["PublicReCaptchaKey"] = "";
        $_I6tLJ["PrivateReCaptchaKey"] = "";
      }

      if($_I6tLJ["UseACaptcha"] == 2){
        $_I6tLJ["UseCaptcha"] = 0;
        $_I6tLJ["UseReCaptcha"] = 1;
        $_I6tLJ["UseReCaptchav3"] = 0;
      }
      if($_I6tLJ["UseACaptcha"] == 3){
        $_I6tLJ["UseCaptcha"] = 0;
        $_I6tLJ["UseReCaptcha"] = 0;
        $_I6tLJ["UseReCaptchav3"] = 1;
      }

    }

    $_Iflj0 = array();
    _L8EOB($_IfJoo, $_Iflj0);

    // new?
    if($_jQ1il == 0) {
      $_QLfol = "INSERT INTO `$_IfJoo` SET CreateDate=NOW()";
      mysql_query($_QLfol, $_QLttI);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_jQ1il = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }

    reset($_I6tLJ["fields"]);
    foreach($_I6tLJ["fields"] as $_IOLil => $_IOCjL) {
      if($_IOCjL == "invisible") {
        unset($_I6tLJ["fields"][$_IOLil]);
      }
    }

    $_QLfol = "UPDATE `$_IfJoo` SET ";

    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if($key == "fields") continue;
      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 ) {
             $_Io01j[] = "`$key`=1";
             }
             else
              ;
        else {
           $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]));
        }
      } else {
         if(in_array($key, $_ItI0o)) {
           $key = $_Iflj0[$_Qli6J];
           $_Io01j[] = "`$key`=0";
         } else {
           if(in_array($key, $_ItIti)) {
             $key = $_Iflj0[$_Qli6J];
             $_Io01j[] = "`$key`=0";
           }
         }
      }
    }

    $_QLfol .= join(", ", $_Io01j);
    $_QLfol .= ", `fields`="._LRAFO(serialize($_I6tLJ["fields"]));
    $_QLfol .= " WHERE id=$_jQ1il";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    // mtas
    if(isset($_I6tLJ["mtas"])) {
      $_QLfol = "DELETE FROM $_ji10i";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      for($_Qli6J=0; $_Qli6J<count($_I6tLJ["mtas"]); $_Qli6J++) {
        $_QLfol = "INSERT INTO $_ji10i SET mtas_id=".intval($_I6tLJ["mtas"][$_Qli6J]).", sortorder=$_Qli6J";
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }
    }

  }

function _LR0B6($_61lf8) {
  global $_jfQtI, $_QLttI;
  $_QlI6f = "SELECT DISTINCT `id`, `Name` FROM `$_jfQtI`";
  $_QlI6f .= " WHERE ";
  $_QlI6f .= " `$_jfQtI`.`Type`="._LRAFO($_61lf8); // defaults
  $_QlI6f .= " ORDER BY `Name` ASC";

  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);
  return $_QL8i1;
}

function GetMessages() {
  global $_Ifi1J, $_QLttI;
  $_QlI6f = "SELECT DISTINCT `id`, `Name` FROM `$_Ifi1J`";
  $_QlI6f .= " ORDER BY `Name` ASC";

  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);
  return $_QL8i1;
}

  function _LRQ0B($_IttOL, $_IoJlj){
   global $UserId, $_QL88I, $_I18lo, $_QLi60, $_IoCo0, $_I616t, $_ICo0J,
          $_jJLQo, $_jJL88, $_jJLLf, $_IjC0Q, $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;

    $_jJltj = array();

    $_jJltj[] = array(
                                   "TableName" => $_QL88I,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]
                                 );
    if(_L8B1P($_IoCo0)) {
      $_jJltj[] = array(
                                   "TableName" => $_IoCo0,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceAutoresponder"]
                                 );
    }

    if(_L8B1P($_I616t)) {
      $_jJltj[] = array(
                                   "TableName" => $_I616t,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceFollowUpResponder"]
                                 );
    }

    if(_L8B1P($_ICo0J)) {
      $_jJltj[] = array(
                                   "TableName" => $_ICo0J,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceBirthdayResponder"]
                                 );
    }

    if(_L8B1P($_jJLQo)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJLQo,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceRSS2EMailResponder"]
                                 );
    }

    if(_L8B1P($_QLi60)) {
      $_jJltj[] = array(
                                   "TableName" => $_QLi60,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceCampaign"]
                                 );
    }

    if(_L8B1P($_jJL88)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJL88,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["001820"]
                                 );
    }

    if(_L8B1P($_jJLLf)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJLLf,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceSMSCampaign"]
                                 );
    }

    if(_L8B1P($_IjC0Q)) {
      $_jJltj[] = array(
                                   "TableName" => $_IjC0Q,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceDistributionList"]
                                 );
    }

    // referenzen vorhanden?
    $_j608C = 0;
    $_6Q0Qi = array();
    for($_Qli6J=0; $_Qli6J<count($_jJltj); $_Qli6J++) {

      $_IliOC = "`maillists_id`";
      if($_jJltj[$_Qli6J]["TableName"] == $_QL88I)
        $_IliOC = "`id`";

      $_QLfol = "SELECT `Name` FROM `".$_jJltj[$_Qli6J]["TableName"]."` WHERE $_IliOC=$_IttOL AND `forms_id`=$_IoJlj";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      while($_jj6L6 = mysql_fetch_assoc($_jjJfo)){
        $_j608C++;
        $_6Q0Qi[] = $_jJltj[$_Qli6J]["Description"].": ".$_jj6L6["Name"];
      }
      mysql_free_result($_jjJfo);
    }

    return $_6Q0Qi;
  }
  

?>

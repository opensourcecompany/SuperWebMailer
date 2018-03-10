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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  // Boolean fields of form
  $_I01C0 = Array ('RequestReasonForUnsubscription', 'GroupsAsCheckBoxes', 'GroupsAsMandatoryField', 'SendOptInConfirmedMail', 'SendOptOutConfirmedMail', 'SendEditConfirmedMail');

  $_I01lt = Array ();

  $errors = array();
  $_I8jJ8 = 0;

  $_I0600 = "";

  if(isset($_POST['FormId'])) // Formular speichern?
    $_I8jJ8 = intval($_POST['FormId']);
  else
    if ( isset($_POST['OneFormListId']) )
       $_I8jJ8 = intval($_POST['OneFormListId']);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_I8jJ8 == 0 && !$_QJojf["PrivilegeFormCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_I8jJ8 != 0 && !$_QJojf["PrivilegeFormEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
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

  if(!_OCJCC($OneMailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  // mailinglist data
  $_QJlJ0 = "SELECT Name, FormsTableName, MTAsTableName, GroupsTableName, `ReasonsForUnsubscripeTableName`, forms_id, SubscriptionType, UnsubscriptionType, AllowOverrideSenderEMailAddressesWhileMailCreating, SenderFromName, SenderFromAddress, ReplyToEMailAddress, ReturnPathEMailAddress, `CcEMailAddresses`, `BCcEMailAddresses`, `SubscriptionUnsubscription` FROM $_Q60QL WHERE id=$OneMailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_array($_Q60l1);
  $_I8JQO = $_Q6Q1C["Name"];
  $_QLI8o = $_Q6Q1C["FormsTableName"];
  $_j0tio = $_Q6Q1C["MTAsTableName"];
  $_Q6t6j = $_Q6Q1C["GroupsTableName"];
  $_I8Jtl = $_Q6Q1C["ReasonsForUnsubscripeTableName"];
  $_jl6fQ = $_Q6Q1C["SubscriptionType"];
  $_jlf11 = $_Q6Q1C["UnsubscriptionType"];
  $_jlI8i = $_Q6Q1C["forms_id"] == $_I8jJ8;
  $_QJLLO = $_Q6Q1C["AllowOverrideSenderEMailAddressesWhileMailCreating"];
  $_IIoQO = $_Q6Q1C["SenderFromName"];
  $_IQf88 = $_Q6Q1C["SenderFromAddress"];
  $_IQ8QI = $_Q6Q1C["ReplyToEMailAddress"];
  $_IQ8LL = $_Q6Q1C["ReturnPathEMailAddress"];
  $_IQtQJ = $_Q6Q1C["CcEMailAddresses"];
  $_IQOlf = $_Q6Q1C["BCcEMailAddresses"];
  $_ji0i1 = $_Q6Q1C["SubscriptionUnsubscription"];
  mysql_free_result($_Q60l1);

  # Kommen wir vom mailinglistcreate.php??
  if(isset($_POST['FormEditBtn'])) { // Formular speichern?

    if(!isset($_POST["UseACaptcha"]))
      $_POST["UseACaptcha"] = 0;

    if($_POST["UseACaptcha"] == 2){
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
        $_POST["OverrideSubUnsubURL"] = _OBLDR(trim($_POST["OverrideSubUnsubURL"]));
    }

    if ( !empty($_POST["OverrideTrackingURL"]) ) {
        if(strpos($_POST["OverrideTrackingURL"], "http://") === false && strpos($_POST["OverrideTrackingURL"], "https://") === false) {
          $errors[] = 'OverrideTrackingURL';
        }
        $_POST["OverrideTrackingURL"] = _OBLDR(trim($_POST["OverrideTrackingURL"]));
    }

    if ( !empty($_POST["ExternalReasonForUnsubscriptionScript"]) ) {
        if(strpos($_POST["ExternalReasonForUnsubscriptionScript"], "http://") === false && strpos($_POST["ExternalReasonForUnsubscriptionScript"], "https://") === false) {
          $errors[] = 'ExternalReasonForUnsubscriptionScript';
        }
        $_POST["ExternalReasonForUnsubscriptionScript"] = trim($_POST["ExternalReasonForUnsubscriptionScript"]);
    }

    if ( !empty($_POST["UserDefinedFormsFolder"]) ) {
       $_POST["UserDefinedFormsFolder"] = trim($_POST["UserDefinedFormsFolder"]);
       $_jli1j=array("default_subscribeunsubscribe_page.htm", "default_edit_page.htm", "default_errorpage.htm");
       for($_Q6llo=0;$_Q6llo<count($_jli1j);$_Q6llo++){
         if(!file_exists(InstallPath.$_POST["UserDefinedFormsFolder"]."/".$_jli1j[$_Q6llo]))
            $errors[] = 'UserDefinedFormsFolder';
            else{
              if(file(InstallPath.$_POST["UserDefinedFormsFolder"]."/".$_jli1j[$_Q6llo]) === false) {
                 $errors[] = 'UserDefinedFormsFolder';
              }
            }
       }
    }

    if($_QJLLO) {
      if ( (!isset($_POST['SenderFromAddress'])) || (trim($_POST['SenderFromAddress']) == "") || ( !_OPAOJ($_POST['SenderFromAddress']) ) )
        $errors[] = 'SenderFromAddress';
      if ( (isset($_POST['ReplyToEMailAddress'])) && ($_POST['ReplyToEMailAddress'] != "") && ( !_OPAOJ($_POST['ReplyToEMailAddress']) ) )
        $errors[] = 'ReplyToEMailAddress';
      if ( (isset($_POST['ReturnPathEMailAddress'])) && ($_POST['ReturnPathEMailAddress'] != "") && ( !_OPAOJ($_POST['ReturnPathEMailAddress']) ) )
        $errors[] = 'ReturnPathEMailAddress';


      if ( (isset($_POST['CcEMailAddresses'])) && ($_POST['CcEMailAddresses'] != "")  ) {
          $_IQO0o = explode(",", $_POST['CcEMailAddresses']);
          $_Q8C08 = false;
          for($_Q6llo=0; $_Q6llo<count($_IQO0o); $_Q6llo++){
             $_IQO0o[$_Q6llo] = trim($_IQO0o[$_Q6llo]);
             if( !_OPAOJ($_IQO0o[$_Q6llo]) ) {
                 $_Q8C08 = true;
                 break;
               }
          }
          if($_Q8C08)
             $errors[] = 'CcEMailAddresses';
             else
             $_POST['CcEMailAddresses'] = implode(",", $_IQO0o);
      }

      if ( (isset($_POST['BCcEMailAddresses'])) && ($_POST['BCcEMailAddresses'] != "")  ) {
          $_IQO0o = explode(",", $_POST['BCcEMailAddresses']);
          $_Q8C08 = false;
          for($_Q6llo=0; $_Q6llo<count($_IQO0o); $_Q6llo++){
             $_IQO0o[$_Q6llo] = trim($_IQO0o[$_Q6llo]);
             if( !_OPAOJ($_IQO0o[$_Q6llo]) ) {
               $_Q8C08 = true;
               break;
             }
          }
          if($_Q8C08)
            $errors[] = 'BCcEMailAddresses';
            else
            $_POST['BCcEMailAddresses'] = implode(",", $_IQO0o);
      }

    }

    if ( ((!isset($_POST['mtas'])) || (count($_POST['mtas']) == 0)) && $_QJLLO )
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

         $_IQlQ1 = array();
         _OBEPD($_POST["OptInConfirmationMailHTMLText"], $_IQlQ1);
         if(count($_IQlQ1) > 0) {
           $errors[] = 'FileError_OptInConfirmationMailHTMLText';
           $_I0600 = join("<br />", $_IQlQ1);
         }

      }
      if ( $_POST['OptInConfirmationMailFormat'] == "PlainText"  ) {
         if(trim($_POST["OptInConfirmationMailPlainText"]) == "")
           $errors[] = 'OptInConfirmationMailPlainText';
      }

      if(count($errors) == 0) {
          $_IQCoo = $_POST["OptInConfirmationMailEncoding"];
          $_IQC1o = $_POST["OptInConfirmationMailFormat"];
          $_IQojt = $_POST["OptInConfirmationMailSubject"];
          $_IQitJ = $_POST["OptInConfirmationMailPlainText"];

          if($_IQCoo != "utf-8" ) {
            if( !_OB16R($_Q6QQL, $_IQCoo, $_IQojt) ) {
              $errors[] = 'OptInConfirmationMailEncoding';
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_OB16R($_Q6QQL, $_IQCoo, $_IQitJ) ) {
                   $errors[] = 'OptInConfirmationMailEncoding';
                   $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_IQCoo != "utf-8" )
      } # if(count($errors) == 0)


      // Edit
      if ( (!isset($_POST['EditConfirmationMailSubject'])) || (trim($_POST['EditConfirmationMailSubject']) == "") )
        $errors[] = 'EditConfirmationMailSubject';

      if ( ( $_POST['EditConfirmationMailFormat'] == "HTML" ) ) {
         if( trim( unhtmlentities( @strip_tags ( $_POST["EditConfirmationMailHTMLText"] ), $_POST["EditConfirmationMailEncoding"] ) ) == "")
           $errors[] = 'EditConfirmationMailHTMLText';

         $_IQlQ1 = array();
         _OBEPD($_POST["EditConfirmationMailHTMLText"], $_IQlQ1);
         if(count($_IQlQ1) > 0) {
           $errors[] = 'FileError_EditConfirmationMailHTMLText';
           $_I0600 = join("<br />", $_IQlQ1);
         }

      }
      if ( $_POST['EditConfirmationMailFormat'] == "PlainText"  ) {
         if(trim($_POST["EditConfirmationMailPlainText"]) == "")
           $errors[] = 'EditConfirmationMailPlainText';
      }

      if(count($errors) == 0) {
          $_IQCoo = $_POST["EditConfirmationMailEncoding"];
          $_IQC1o = $_POST["EditConfirmationMailFormat"];
          $_IQojt = $_POST["EditConfirmationMailSubject"];
          $_IQitJ = $_POST["EditConfirmationMailPlainText"];

          if($_IQCoo != "utf-8" ) {
            if( !_OB16R($_Q6QQL, $_IQCoo, $_IQojt) ) {
              $errors[] = 'EditConfirmationMailEncoding';
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_OB16R($_Q6QQL, $_IQCoo, $_IQitJ) ) {
                   $errors[] = 'EditConfirmationMailEncoding';
                   $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_IQCoo != "utf-8" )
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

         $_IQlQ1 = array();
         _OBEPD($_POST["OptOutConfirmationMailHTMLText"], $_IQlQ1);
         if(count($_IQlQ1) > 0) {
           $errors[] = 'FileError_OptOutConfirmationMailHTMLText';
           $_I0600 = join("<br />", $_IQlQ1);
         }

      }
      if ( $_POST['OptOutConfirmationMailFormat'] == "PlainText"  ) {
         if(trim($_POST["OptOutConfirmationMailPlainText"]) == "")
           $errors[] = 'OptOutConfirmationMailPlainText';
      }

      if(count($errors) == 0) {
          $_IQCoo = $_POST["OptOutConfirmationMailEncoding"];
          $_IQC1o = $_POST["OptOutConfirmationMailFormat"];
          $_IQojt = $_POST["OptOutConfirmationMailSubject"];
          $_IQitJ = $_POST["OptOutConfirmationMailPlainText"];

          if($_IQCoo != "utf-8" ) {
            if( !_OB16R($_Q6QQL, $_IQCoo, $_IQojt) ) {
              $errors[] = 'OptOutConfirmationMailEncoding';
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_OB16R($_Q6QQL, $_IQCoo, $_IQitJ) ) {
                   $errors[] = 'OptOutConfirmationMailEncoding';
                   $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_IQCoo != "utf-8" )
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

         $_IQlQ1 = array();
         _OBEPD($_POST["OptInConfirmedMailHTMLText"], $_IQlQ1);
         if(count($_IQlQ1) > 0) {
           $errors[] = 'FileError_OptInConfirmedMailHTMLText';
           $_I0600 = join("<br />", $_IQlQ1);
         }

      }
      if ( $_POST['OptInConfirmedMailFormat'] == "PlainText"  ) {
         if(trim($_POST["OptInConfirmedMailPlainText"]) == "")
           $errors[] = 'OptInConfirmedMailPlainText';
      }

      if(count($errors) == 0) {
          $_IQCoo = $_POST["OptInConfirmedMailEncoding"];
          $_IQC1o = $_POST["OptInConfirmedMailFormat"];
          $_IQojt = $_POST["OptInConfirmedMailSubject"];
          $_IQitJ = $_POST["OptInConfirmedMailPlainText"];

          if($_IQCoo != "utf-8" ) {
            if( !_OB16R($_Q6QQL, $_IQCoo, $_IQojt) ) {
              $errors[] = 'OptInConfirmedMailEncoding';
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_OB16R($_Q6QQL, $_IQCoo, $_IQitJ) ) {
                   $errors[] = 'OptInConfirmedMailEncoding';
                   $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_IQCoo != "utf-8" )
      } # if(count($errors) == 0)

      if(count($errors) == 0) {
        if(isset($_POST["OptInConfirmedMailHTMLText"])){
          if(!_OCRRL($_POST["OptInConfirmedMailHTMLText"], array("SubscriptionUnsubscription" => $_ji0i1), $_I0600)){
             $errors[] = 'OptInConfirmedMailHTMLText';
          }
        }
        if(!count($errors) && isset($_POST["OptInConfirmedMailPlainText"])){
          if(!_OCRRL($_POST["OptInConfirmedMailPlainText"], array("SubscriptionUnsubscription" => $_ji0i1), $_I0600)){
             $errors[] = 'OptInConfirmedMailPlainText';
          }
        }
      }

      if(count($errors) == 0) {
          $_II08o = 0;
          $_II0lj = _OCL0A($_POST, $_II08o, "OptInConfirmed");

          if($_II0lj > $_II08o) {
            $_I0600 = "OptInConfirmed: ".sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MEMORY_LIMIT_EXCEEDED"], _OBDF6($_II0lj), _OBDF6($_II08o));
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

         $_IQlQ1 = array();
         _OBEPD($_POST["OptOutConfirmedMailHTMLText"], $_IQlQ1);
         if(count($_IQlQ1) > 0) {
           $errors[] = 'FileError_OptOutConfirmedMailHTMLText';
           $_I0600 = join("<br />", $_IQlQ1);
         }

      }
      if ( $_POST['OptOutConfirmedMailFormat'] == "PlainText"  ) {
         if(trim($_POST["OptOutConfirmedMailPlainText"]) == "")
           $errors[] = 'OptOutConfirmedMailPlainText';
      }

      if(count($errors) == 0) {
          $_IQCoo = $_POST["OptOutConfirmedMailEncoding"];
          $_IQC1o = $_POST["OptOutConfirmedMailFormat"];
          $_IQojt = $_POST["OptOutConfirmedMailSubject"];
          $_IQitJ = $_POST["OptOutConfirmedMailPlainText"];

          if($_IQCoo != "utf-8" ) {
            if( !_OB16R($_Q6QQL, $_IQCoo, $_IQojt) ) {
              $errors[] = 'OptOutConfirmedMailEncoding';
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
            } else {
                 if( !_OB16R($_Q6QQL, $_IQCoo, $_IQitJ) ) {
                   $errors[] = 'OptOutConfirmedMailEncoding';
                   $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"];
                 }
            }
          } # if($_IQCoo != "utf-8" )
      } # if(count($errors) == 0)


      if(count($errors) == 0) {
          $_II08o = 0;
          $_II0lj = _OCL0A($_POST, $_II08o, "OptOutConfirmed");

          if($_II0lj > $_II08o) {
            $_I0600 = "OptOutConfirmed: ".sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MEMORY_LIMIT_EXCEEDED"], _OBDF6($_II0lj), _OBDF6($_II08o));
          }
      }

    }


    if(count($errors) > 0) {
        if($_I0600 == "")
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        if($_I0600 == "")
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
          else
          $_I0600 = $_I0600."<br /><br />".$resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        // Falscheingaben
        if(isset($_POST["OptInConfirmationMailPlainText"]) && trim($_POST["OptInConfirmationMailPlainText"]) == "" && isset($_POST["OptInConfirmationMailHTMLText"]) )
           $_POST["OptInConfirmationMailPlainText"] = _ODQAB ( $_POST["OptInConfirmationMailHTMLText"] , $_Q6QQL );
        if(isset($_POST["OptOutConfirmationMailPlainText"]) && trim($_POST["OptOutConfirmationMailPlainText"]) == "" && isset($_POST["OptOutConfirmationMailHTMLText"]) )
           $_POST["OptOutConfirmationMailPlainText"] = _ODQAB ( $_POST["OptOutConfirmationMailHTMLText"], $_Q6QQL );

        if(isset($_POST["EditConfirmationMailPlainText"]) && trim($_POST["EditConfirmationMailPlainText"]) == "" && isset($_POST["EditConfirmationMailHTMLText"]) )
           $_POST["EditConfirmationMailPlainText"] = _ODQAB ( $_POST["EditConfirmationMailHTMLText"] , $_Q6QQL );

        if(isset($_POST["OptInConfirmedMailPlainText"]) && trim($_POST["OptInConfirmedMailPlainText"]) == "" && isset($_POST["OptInConfirmedMailHTMLText"]) )
           $_POST["OptInConfirmedMailPlainText"] = _ODQAB ( $_POST["OptInConfirmedMailHTMLText"], $_Q6QQL );
        if(isset($_POST["OptOutConfirmedMailPlainText"]) && trim($_POST["OptOutConfirmedMailPlainText"]) == "" && isset($_POST["OptOutConfirmedMailHTMLText"]) )
           $_POST["OptOutConfirmedMailPlainText"] = _ODQAB ( $_POST["OptOutConfirmedMailHTMLText"], $_Q6QQL );

        // overide not allowed
        if(!$_QJLLO) {
          $_POST["SenderFromName"] = "";
          $_POST["SenderFromAddress"] = "";
          $_POST["ReplyToEMailAddress"] = "";
          $_POST["ReturnPathEMailAddress"] = "";
          $_POST["CcEMailAddresses"] = "";
          $_POST["BCcEMailAddresses"] = "";
        }

        $_II1Ot = $_POST;

        if(isset($_II1Ot["OptInConfirmedAttachments"])) {
           for($_Q6llo=0; $_Q6llo<count($_II1Ot["OptInConfirmedAttachments"]); $_Q6llo++) {
              $_II1Ot["OptInConfirmedAttachments"][$_Q6llo] = $_II1Ot["OptInConfirmedAttachments"][$_Q6llo];
           }
           $_POST["OptInConfirmedAttachments"] = $_II1Ot["OptInConfirmedAttachments"];
           $_II1Ot["OptInConfirmedAttachments"] = serialize($_II1Ot["OptInConfirmedAttachments"]);
        } else
          $_II1Ot["OptInConfirmedAttachments"] = "";

        if(isset($_II1Ot["OptOutConfirmedAttachments"])) {
           for($_Q6llo=0; $_Q6llo<count($_II1Ot["OptOutConfirmedAttachments"]); $_Q6llo++) {
              $_II1Ot["OptOutConfirmedAttachments"][$_Q6llo] = $_II1Ot["OptOutConfirmedAttachments"][$_Q6llo];
           }
           $_POST["OptOutConfirmedAttachments"] = $_II1Ot["OptOutConfirmedAttachments"];
           $_II1Ot["OptOutConfirmedAttachments"] = serialize($_II1Ot["OptOutConfirmedAttachments"]);
        } else
          $_II1Ot["OptOutConfirmedAttachments"] = "";

        _O8B1F($_I8jJ8, $_II1Ot);
        $_POST["FormId"] = $_I8jJ8;
      }
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I8JQO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000068"], $_I0600, 'formedit', 'formedit_snipped.htm');
  $_QJCJi = str_replace("PRODUCTAPPNAME", $AppName, $_QJCJi);
  $_QJCJi = str_replace("%TEMPLATES_DIR%", _O68QF(), $_QJCJi);


  $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

  #
  // ********* List of MTAs SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Qofoi";
  $_Q68ff .= " ORDER BY Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);

  if(isset($_jQfIO))
    unset($_jQfIO);
  $_jQfIO = array();
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_jQfIO[$_Q6Q1C["id"]] = $_Q6Q1C["Name"];
  }
  mysql_free_result($_Q60l1);
  // ********* List of MTAs query END

  #### normal placeholders
  $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language="._OPQLR($INTERFACE_LANGUAGE)." AND fieldname <> "._OPQLR("u_EMailFormat");
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q8otJ=array();
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_Q8otJ[] =  sprintf("new Array('[%s]', '%s')", $_Q6Q1C["fieldname"], $_Q6Q1C["text"]);
  }
  # defaults
  foreach ($_IIQI8 as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QJCJi = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);
  mysql_free_result($_Q60l1);

  #### special on subscribe placeholders
  unset($_Q8otJ);
  $_Q8otJ=array();
  reset($_jJioL);
  foreach ($_jJioL as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QJCJi = str_replace ("new Array('[SUBSCRIBEPLACEHOLDER]', 'SUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);
  # special on unsubscribe placeholders
  unset($_Q8otJ);
  $_Q8otJ=array();
  reset($_jJLJ6);
  foreach ($_jJLJ6 as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QJCJi = str_replace ("new Array('[UNSUBSCRIBEPLACEHOLDER]', 'UNSUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);
  #### special newsletter unsubscribe placeholders
  unset($_Q8otJ);
  $_Q8otJ=array();
  reset($_III0L);
  foreach ($_III0L as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QJCJi = str_replace ("new Array('[NEWSLETTER_UNSUBSCRIBEPLACEHOLDER]', 'NEWSLETTER_UNSUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);

  #### special on edit placeholders
  unset($_Q8otJ);
  $_Q8otJ=array();
  reset($_jJLoj);
  foreach ($_jJLoj as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QJCJi = str_replace ("new Array('[EDITPLACEHOLDER]', 'EDITPLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);

  // ********* List of Redirect Pages
  $_Q60l1 = _O8BAA("Error");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:SubscribeErrorPage>", "</SHOW:SubscribeErrorPage>", $_I10Cl);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:UnsubscribeErrorPage>", "</SHOW:UnsubscribeErrorPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("SubscribeConfirmation");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:SubscribeAcceptedPage>", "</SHOW:SubscribeAcceptedPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("Subscribe");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:SubscribeConfirmationPage>", "</SHOW:SubscribeConfirmationPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("UnsubscribeConfirmation");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:UnsubscribeAcceptedPage>", "</SHOW:UnsubscribeAcceptedPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("Unsubscribe");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:UnsubscribeConfirmationPage>", "</SHOW:UnsubscribeConfirmationPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("EditConfirmation");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EditAcceptedPage>", "</SHOW:EditAcceptedPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("Edit");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EditConfirmationPage>", "</SHOW:EditConfirmationPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("EditReject");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EditRejectPage>", "</SHOW:EditRejectPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("EditError");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EditErrorPage>", "</SHOW:EditErrorPage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("UnsubscribeBridge");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:UnsubscribeBridgePage>", "</SHOW:UnsubscribeBridgePage>", $_I10Cl);
  //
  $_Q60l1 = _O8BAA("RFUSurveyConfirmation");
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:RFUSurveyConfirmationPage>", "</SHOW:RFUSurveyConfirmationPage>", $_I10Cl);
  //

  // ********* List of Redirect Pages END

  // Language
  $_QJlJ0 = "SELECT * FROM $_Qo6Qo";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6ICj = "";
  while($_Q6Q1C  = mysql_fetch_assoc($_Q60l1)) {
     $_Q6ICj .= '<option value="'.$_Q6Q1C["Language"].'">'.$_Q6Q1C["Text"].'</option>'.$_Q6JJJ;
  }
  $_QJCJi = _OPR6L($_QJCJi, '<SHOW:LANGUAGE>', '</SHOW:LANGUAGE>', $_Q6ICj);
  mysql_free_result($_Q60l1);
  // *************

  // Themes
  $_QJlJ0 = "SELECT * FROM $_Q880O";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6ICj = "";
  while($_Q6Q1C  = mysql_fetch_assoc($_Q60l1)) {
     $_Q6ICj .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Text"].'</option>'.$_Q6JJJ;
  }
  $_QJCJi = _OPR6L($_QJCJi, '<SHOW:THEMES>', '</SHOW:THEMES>', $_Q6ICj);
  mysql_free_result($_Q60l1);
  // *************

  // ********* List of Messages
  $_Q60l1 = GetMessages();
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:Messages>", "</SHOW:Messages>", $_I10Cl);
  // ********* List of Messages END

  // ********* List of Groups SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q6t6j";
  $_Q68ff .= " ORDER BY Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_I10Cl);
  // ********* List of Groups query END

  // ********* fields
  $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language="._OPQLR($INTERFACE_LANGUAGE);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  $_I1OLj = _OP81D($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>");
  $_Q6ICj = "";
  $_Q6llo=1;
  $_I1oot = false;
  while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
    if($_Q6llo == 1)
       $_Q66jQ = $_I1OLj;
    $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', $_Q6Q1C["text"], $_Q66jQ);
    if($_Q6Q1C["fieldname"] != "u_EMail")
      $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="fields['.$_Q6Q1C["fieldname"].']" size="1">'.$resourcestrings[$INTERFACE_LANGUAGE]["000069"].$resourcestrings[$INTERFACE_LANGUAGE]["000070"].'</select>', $_Q66jQ);
      else
      $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="fields['.$_Q6Q1C["fieldname"].']" size="1">'.$resourcestrings[$INTERFACE_LANGUAGE]["000070"].'</select>', $_Q66jQ);
    $_Q6llo++;
    if($_Q6llo>2) {
      $_Q6llo=1;
      $_Q6ICj .= $_Q66jQ;
      $_Q66jQ = "";
    }
  }
  if($_Q66jQ != "")
    $_Q6ICj .= $_Q66jQ;
  $_QJCJi = _OPR6L($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>", $_Q6ICj);
  // ********* fields END

  // mail encodings
  $_Q6ICj = "";
  if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
    reset($_Qo8OO);
    foreach($_Qo8OO as $key => $_Q6ClO) {
       $_Q6ICj .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
    }
  }
  $_QJCJi = _OPR6L($_QJCJi, "<MAILENCODINGS>", "</MAILENCODINGS>", $_Q6ICj);


  if(!isset($_POST['FormEditBtn']) && $_I8jJ8 == 0) { // Form new?

      $_QJlJ0 = "SELECT * FROM `$_QLI8o` WHERE `IsDefault`=1";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
        mysql_free_result($_Q60l1);

        $_J0CL0 = $_Q6Q1C["id"];
        unset($_Q6Q1C["id"]);
        unset($_Q6Q1C["CreateDate"]);
        $_Q6Q1C["IsDefault"] = 0;

        $_QJlJ0 = "INSERT INTO `$_QLI8o` SET `CreateDate`=NOW()";

        reset($_Q6Q1C);
        foreach($_Q6Q1C as $key => $_Q6ClO){
          $_QJlJ0 .= ", `$key`="._OPQLR($_Q6ClO);
        }

        mysql_query($_QJlJ0, $_Q61I1);
        $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
        $_Q8OiJ = mysql_fetch_row($_Q60l1);
        $_I8jJ8 = $_Q8OiJ[0];
        mysql_free_result($_Q60l1);
        $_QJlJ0 = "UPDATE `$_QLI8o` SET `Name`="._OPQLR($_Q6Q1C["Name"]." "."($_I8jJ8)")." WHERE id=$_I8jJ8";
        mysql_query($_QJlJ0, $_Q61I1);

        // copy reasons
        $_QJlJ0 = "SELECT * FROM $_I8Jtl WHERE `forms_id`=$_J0CL0";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
          $_QJlJ0 = "INSERT INTO $_I8Jtl SET `forms_id`=$_I8jJ8, `Reason`="._OPQLR($_Q6Q1C["Reason"]) . ", `ReasonType`="._OPQLR($_Q6Q1C["ReasonType"]).", `sort_order`=$_Q6Q1C[sort_order]";
          mysql_query($_QJlJ0, $_Q61I1);
        }
        mysql_free_result($_Q60l1);

      }

  }

  # Form laden
  if(isset($_POST['FormEditBtn'])) { // Formular speichern?
    $ML = $_POST;

    // umwandeln, damit er es wieder findet
    unset($ML["fields"]);
    $_I16jJ = $_POST["fields"];
    reset($_I16jJ);
    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if(isset($_I16jJ[$_I1i8O]) ) {
        $ML["fields[$_I1i8O]"] = $_I1L81;
      }
    }
    $ML["FormsTableName"] = $_QLI8o;

  } else {
     if($_I8jJ8 != 0) {
       $_QJlJ0= "SELECT *, UNIX_TIMESTAMP(CreateDate) AS UCreateDate FROM `$_QLI8o` WHERE id=$_I8jJ8";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $ML=mysql_fetch_assoc($_Q60l1);

       reset($ML);
       foreach($ML as $key)
         if(strpos($key, "MailHTMLText") !== false)
           $ML[$key] = FixCKEditorStyleProtectionForCSS($ML[$key]);

       $ML["FormsTableName"] = $_QLI8o;

       if($ML["GroupsOption"] < 1)
          $ML["GroupsOption"] = 1;

       if($ML["UseCaptcha"] <= 0)
          unset($ML["UseCaptcha"]);

       if($ML["UseReCaptcha"] <= 0)
          unset($ML["UseReCaptcha"]);

       $ML["UseACaptcha"] = 0;
       if(isset($ML["UseCaptcha"]))
         $ML["UseACaptcha"] = 1;

       if(isset($ML["UseReCaptcha"]))
         $ML["UseACaptcha"] = 2;

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

       // umwandeln, damit er es wieder findet
       if($ML["fields"] != "") {
         $_I16jJ = unserialize( $ML["fields"] );
         unset($ML["fields"]);
         reset($_I16jJ);
         foreach($_I16jJ as $_I1i8O => $_I1L81) {
           if(isset($_I16jJ[$_I1i8O]) ) {
             $ML["fields[$_I1i8O]"] = $_I1L81;
           }
         }
       }
       else
        unset($ML["fields"]);

       mysql_free_result($_Q60l1);
       if($INTERFACE_LANGUAGE != "de")
         $ML["CREATEDATE"] = strftime("%c", $ML["UCreateDate"]);
        else
         $ML["CREATEDATE"] = strftime("%d.%m.%Y %H:%M", $ML["UCreateDate"]);

       if($_QJLLO) {
         if($ML["SenderFromName"] == "")
            $ML["SenderFromName"] = $_IIoQO;
         if($ML["SenderFromAddress"] == "")
            $ML["SenderFromAddress"] = $_IQf88;
         if($ML["ReplyToEMailAddress"] == "")
            $ML["ReplyToEMailAddress"] = $_IQ8QI;
         if($ML["ReturnPathEMailAddress"] == "")
            $ML["ReturnPathEMailAddress"] = $_IQ8LL;
         if($ML["CcEMailAddresses"] == "")
            $ML["CcEMailAddresses"] = $_IQtQJ;
         if($ML["BCcEMailAddresses"] == "")
            $ML["BCcEMailAddresses"] = $_IQOlf;
       }

     } else {
       if($_QJLLO) {
         $ML["SenderFromName"] = $_IIoQO;
         $ML["SenderFromAddress"] = $_IQf88;
         $ML["ReplyToEMailAddress"] = $_IQ8QI;
         $ML["ReturnPathEMailAddress"] = $_IQ8LL;
         $ML["CcEMailAddresses"] = $_IQtQJ;
         $ML["BCcEMailAddresses"] = $_IQOlf;
       }
       $ML["CREATEDATE"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
       $ML["GroupsOption"] = 1;
       $ML["Language"] = $INTERFACE_LANGUAGE;
       $ML["ThemesId"] = $INTERFACE_THEMESID;
       $ML["UseACaptcha"] = 0;
     }

    $ML["MailingListId"] = $OneMailingListId;
    $ML["SubscriptionType"] = $_jl6fQ;
    $ML["UnsubscriptionType"] = $_jlf11;
    $ML["FormId"] = $_I8jJ8;

    // MTAs
    $_QJlJ0 = "SELECT * FROM $_j0tio ORDER BY sortorder";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $ML["mtas"] = array();
    while ($_jQ816=mysql_fetch_array($_Q60l1) )
      $ML["mtas"][] = $_jQ816["mtas_id"];
    mysql_free_result($_Q60l1);
  }

  if( !isset($ML["fields[u_EMail]"]) ) {
     $ML["fields[u_EMail]"] = "visiblerequired";
  }

  // --------------- MTAs sortorder
  $_jQ88i = array();
  if(!isset($ML["mtas"]))
    $ML["mtas"] = array();
  for($_Q6llo=0; $_Q6llo<count($ML["mtas"]); $_Q6llo++) {
    $_jQ88i[$ML["mtas"][$_Q6llo]] = $_jQfIO[$ML["mtas"][$_Q6llo]];
    unset($_jQfIO[$ML["mtas"][$_Q6llo]]);
  }
  foreach ($_jQfIO as $key => $_Q6ClO) {
    $_jQ88i[$key] = $_Q6ClO;
  }
  $_I10Cl = "";
  foreach ($_jQ88i as $key => $_Q6ClO) {
    $_I10Cl .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
  }
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MTAS>", "</SHOW:MTAS>", $_I10Cl);
  // --------------- MTAs sortorder


  // Attachments
  if(isset($ML["OptInConfirmedAttachments"]) && is_array($ML["OptInConfirmedAttachments"])) {
    $Attachments = $ML["OptInConfirmedAttachments"];
    $ML["OptInConfirmedAttachments"] = array();
    foreach($Attachments as $key => $_Q6ClO) {
       $ML["OptInConfirmedAttachments"][$_Q6ClO] = "";
    }
  }
  if(isset($ML["OptOutConfirmedAttachments"]) && is_array($ML["OptOutConfirmedAttachments"])) {
    $Attachments = $ML["OptOutConfirmedAttachments"];
    $ML["OptOutConfirmedAttachments"] = array();
    foreach($Attachments as $key => $_Q6ClO) {
       $ML["OptOutConfirmedAttachments"][$_Q6ClO] = "";
    }
  }

  $_II6ft = 0;

  $_IIJi1 = _OP81D($_QJCJi, "<OptInConfirmedAttachments>", "</OptInConfirmedAttachments>");
  $_Q6LIL = "";
  $_QCC8C = opendir ( substr($_QOCJo, 0, strlen($_QOCJo) - 1) );
  while (false !== ($_Q6lfJ = readdir($_QCC8C))) {
    if (!is_dir($_QOCJo.$_Q6lfJ) && $_Q6lfJ != "." && $_Q6lfJ != ".." && $_Q6lfJ != "index.php") {
      $_Q6lfJ = utf8_encode($_Q6lfJ);
      $_Q6LIL .= $_IIJi1.$_Q6JJJ;
      $_Q6LIL = _OPR6L($_Q6LIL, "<AttachmentsName>", "</AttachmentsName>", $_Q6lfJ);
      $_Q6LIL = _OPR6L($_Q6LIL, "&lt;AttachmentsName&gt;", "&lt;/AttachmentsName&gt;", $_Q6lfJ);
      $_II6ft++;
      $_Q6LIL = str_replace("AttachmentsId", 'attachchkbox_'.$_II6ft, $_Q6LIL);
      if(isset($ML["OptInConfirmedAttachments"]) && isset($ML["OptInConfirmedAttachments"][$_Q6lfJ])) {
        $_Q6LIL = str_replace('id="'.'attachchkbox_'.$_II6ft.'"', 'id="'.'attachchkbox_'.$_II6ft.'" checked="checked"', $_Q6LIL);
      }
    }
  }
  closedir($_QCC8C);
  if(isset($ML["OptInConfirmedAttachments"]))
    unset($ML["OptInConfirmedAttachments"]);
  $_QJCJi = _OPR6L($_QJCJi, "<OptInConfirmedAttachments>", "</OptInConfirmedAttachments>", $_Q6LIL);

  $_IIJi1 = _OP81D($_QJCJi, "<OptOutConfirmedAttachments>", "</OptOutConfirmedAttachments>");
  $_Q6LIL = "";
  $_QCC8C = opendir ( substr($_QOCJo, 0, strlen($_QOCJo) - 1) );
  while (false !== ($_Q6lfJ = readdir($_QCC8C))) {
    if (!is_dir($_QOCJo.$_Q6lfJ) && $_Q6lfJ != "." && $_Q6lfJ != ".." && $_Q6lfJ != "index.php") {
      $_Q6lfJ = utf8_encode($_Q6lfJ);
      $_Q6LIL .= $_IIJi1.$_Q6JJJ;
      $_Q6LIL = _OPR6L($_Q6LIL, "<AttachmentsName>", "</AttachmentsName>", $_Q6lfJ);
      $_Q6LIL = _OPR6L($_Q6LIL, "&lt;AttachmentsName&gt;", "&lt;/AttachmentsName&gt;", $_Q6lfJ);
      $_II6ft++;
      $_Q6LIL = str_replace("AttachmentsId", 'attachchkbox_'.$_II6ft, $_Q6LIL);
      if(isset($ML["OptOutConfirmedAttachments"]) && isset($ML["OptOutConfirmedAttachments"][$_Q6lfJ])) {
        $_Q6LIL = str_replace('id="'.'attachchkbox_'.$_II6ft.'"', 'id="'.'attachchkbox_'.$_II6ft.'" checked="checked"', $_Q6LIL);
      }
    }
  }
  closedir($_QCC8C);
  if(isset($ML["OptOutConfirmedAttachments"]))
    unset($ML["OptOutConfirmedAttachments"]);
  $_QJCJi = _OPR6L($_QJCJi, "<OptOutConfirmedAttachments>", "</OptOutConfirmedAttachments>", $_Q6LIL);


  # a little bit statistics

  if(isset($ML["FormsTableName"]))
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $ML["FormsTableName"] );
     else
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $resourcestrings[$INTERFACE_LANGUAGE]["NEW"]);

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MAILINGLISTNAME>", "</SHOW:MAILINGLISTNAME>", "'" . $_I8JQO . "'" );
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $ML["CREATEDATE"] );
  if($_jlI8i)
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:ISDEFAULT>", "</SHOW:ISDEFAULT>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
    else
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:ISDEFAULT>", "</SHOW:ISDEFAULT>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  if($_jl6fQ == 'DoubleOptIn')
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:ISDOUBLEOPTIN>", "</SHOW:ISDOUBLEOPTIN>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"] );
     else
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:ISDOUBLEOPTIN>", "</SHOW:ISDOUBLEOPTIN>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"] );
  if($_jlf11 == 'DoubleOptOut')
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:ISDOUBLEOPTOUT>", "</SHOW:ISDOUBLEOPTOUT>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"] );
     else
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:ISDOUBLEOPTOUT>", "</SHOW:ISDOUBLEOPTOUT>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"] );


  $_Q8otJ = _O8CJ8($OneMailingListId, $_I8jJ8);
  $_J0iJ6 = "";
  for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++){
      $_J0iJ6 .= "<option>".$_Q8otJ[$_Q6llo]."</option>".$_Q6JJJ;
    }

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:FORMREFERENCES>", "</SHOW:FORMREFERENCES>",  $_J0iJ6);

  $_II6C6 = "";

  // GD support?
  if( !extension_loaded('gd') ) {
    $_II6C6 .= "document.getElementById('UseCaptcha1').disabled = true;".$_Q6JJJ;
  }

  if($_jlf11 == 'SingleOptOut') {
     $_QJCJi = _OP6PQ($_QJCJi, "<OptOutConfirmation>", "</OptOutConfirmation>");
  }

  if($_jl6fQ == 'SingleOptIn') {
     $_QJCJi = _OP6PQ($_QJCJi, "<OptInConfirmation>", "</OptInConfirmation>");
     $_QJCJi = _OP6PQ($_QJCJi, "<EditConfirmation>", "</EditConfirmation>");
  }

  if(!$_QJLLO) {
     $_QJCJi = _OP6PQ($_QJCJi, "<FormEMailAddresses>", "</FormEMailAddresses>");

  }

  $_J0iL0 = array("<OptOutConfirmation>", "</OptOutConfirmation>", "<OptInConfirmation>", "</OptInConfirmation>", "<EditConfirmation>", "</EditConfirmation>", "<FormEMailAddresses>", "</FormEMailAddresses>", "<OptInConfirmed>", "</OptInConfirmed>", "<OptOutConfirmed>", "</OptOutConfirmed>");
  for($_Q6llo=0; $_Q6llo<count($_J0iL0); $_Q6llo++)
    $_QJCJi = str_replace($_J0iL0[$_Q6llo], "", $_QJCJi);

  $_QJCJi = _OPFJA($errors, $ML, $_QJCJi);

  # show Warnlabel, we cannot mark fckeditor in red because this will be saved
  if(count($errors) > 0) {
    if(in_array('OptInConfirmationMailHTMLText', $errors))
       $_II6C6 .= "if (document.getElementById('OptInConfirmationMailHTMLTextWarnLabel')) document.getElementById('OptInConfirmationMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";
    if(in_array('OptOutConfirmationMailHTMLText', $errors))
       $_II6C6 .= "if(document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel')) document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";

    if(in_array('EditConfirmationMailHTMLText', $errors))
       $_II6C6 .= "if (document.getElementById('EditConfirmationMailHTMLTextWarnLabel')) document.getElementById('EditConfirmationMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";

    if(in_array('OptInConfirmedMailHTMLText', $errors))
       $_II6C6 .= "if (document.getElementById('OptInConfirmedMailHTMLTextWarnLabel')) document.getElementById('OptInConfirmedMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";
    if(in_array('OptOutConfirmedMailHTMLText', $errors))
       $_II6C6 .= "if(document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel')) document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";

    // file errors
    if(in_array('FileError_OptInConfirmationMailHTMLText', $errors))
       $_II6C6 .= "if (document.getElementById('OptInConfirmationMailHTMLTextWarnLabel')) {document.getElementById('OptInConfirmationMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('OptInConfirmationMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ}";
    if(in_array('FileError_OptOutConfirmationMailHTMLText', $errors))
       $_II6C6 .= "if(document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel')) {document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('OptOutConfirmationMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ}";

    if(in_array('FileError_EditConfirmationMailHTMLText', $errors))
       $_II6C6 .= "if (document.getElementById('EditConfirmationMailHTMLTextWarnLabel')) {document.getElementById('EditConfirmationMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('EditConfirmationMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ}";

    if(in_array('FileError_OptInConfirmedMailHTMLText', $errors))
       $_II6C6 .= "if (document.getElementById('OptInConfirmedMailHTMLTextWarnLabel')) {document.getElementById('OptInConfirmedMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('OptInConfirmedMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ}";
    if(in_array('FileError_OptOutConfirmedMailHTMLText', $errors))
       $_II6C6 .= "if(document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel')) {document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel').innerHTML='".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';document.getElementById('OptOutConfirmedMailHTMLTextWarnLabel').style.display = '';$_Q6JJJ}";
  }
  $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_II6C6, $_QJCJi);
  $_QJCJi = str_replace('?MailingListId=', '?MailingListId='.$OneMailingListId, $_QJCJi);


  $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
  $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

  $_QJojf = _OBOOC($UserId);

  if(!$_QJojf["PrivilegeMTABrowse"]) {
    $_Q6ICj = _LJ6RJ($_Q6ICj, "browsemtas.php");
  }

  if($OwnerUserId != 0) {
    if(!$_QJojf["PrivilegePageBrowse"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "browsepages.php");
    }
    if(!$_QJojf["PrivilegeMessageBrowse"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "browsemessages.php");
    }
  }

  $_QJCJi = $_IIf8o.$_Q6ICj;

  print $_QJCJi;


  function _O8B1F(&$_I8jJ8, $_Qi8If) {
    global $_Q60QL, $_Q6fio, $_I01C0, $_I01lt, $_QLI8o, $_j0tio, $_jlI8i, $_Q61I1;

    if(isset($_Qi8If["UseACaptcha"])) {

      if($_Qi8If["UseACaptcha"] == 0){
        $_Qi8If["UseCaptcha"] = 0;
        $_Qi8If["UseReCaptcha"] = 0;
        $_Qi8If["PublicReCaptchaKey"] = "";
        $_Qi8If["PrivateReCaptchaKey"] = "";
      }

      if($_Qi8If["UseACaptcha"] == 1){
        $_Qi8If["UseCaptcha"] = 1;
        $_Qi8If["UseReCaptcha"] = 0;
        $_Qi8If["PublicReCaptchaKey"] = "";
        $_Qi8If["PrivateReCaptchaKey"] = "";
      }

      if($_Qi8If["UseACaptcha"] == 2){
        $_Qi8If["UseCaptcha"] = 0;
        $_Qi8If["UseReCaptcha"] = 1;
      }

    }

    $_QLLjo = array();
    _OAJL1($_QLI8o, $_QLLjo);

    // new?
    if($_I8jJ8 == 0) {
      $_QJlJ0 = "INSERT INTO `$_QLI8o` SET CreateDate=NOW()";
      mysql_query($_QJlJ0, $_Q61I1);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_I8jJ8 = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }

    reset($_Qi8If["fields"]);
    foreach($_Qi8If["fields"] as $_I1i8O => $_I1L81) {
      if($_I1L81 == "invisible") {
        unset($_Qi8If["fields"][$_I1i8O]);
      }
    }

    $_QJlJ0 = "UPDATE `$_QLI8o` SET ";

    $_I1l61 = array();
    for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
      $key = $_QLLjo[$_Q6llo];
      if($key == "fields") continue;
      if ( isset($_Qi8If[$_QLLjo[$_Q6llo]]) ) {
        if(in_array($key, $_I01C0))
          if( $_Qi8If[$key] == "1" || intval($_Qi8If[$key]) == 0 ) {
             $_I1l61[] = "`$key`=1";
             }
             else
              ;
        else {
           $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]));
        }
      } else {
         if(in_array($key, $_I01C0)) {
           $key = $_QLLjo[$_Q6llo];
           $_I1l61[] = "`$key`=0";
         } else {
           if(in_array($key, $_I01lt)) {
             $key = $_QLLjo[$_Q6llo];
             $_I1l61[] = "`$key`=0";
           }
         }
      }
    }

    $_QJlJ0 .= join(", ", $_I1l61);
    $_QJlJ0 .= ", `fields`="._OPQLR(serialize($_Qi8If["fields"]));
    $_QJlJ0 .= " WHERE id=$_I8jJ8";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    // mtas
    if(isset($_Qi8If["mtas"])) {
      $_QJlJ0 = "DELETE FROM $_j0tio";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      for($_Q6llo=0; $_Q6llo<count($_Qi8If["mtas"]); $_Q6llo++) {
        $_QJlJ0 = "INSERT INTO $_j0tio SET mtas_id=".intval($_Qi8If["mtas"][$_Q6llo]).", sortorder=$_Q6llo";
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }
    }

  }

function _O8BAA($_J0LJf) {
  global $_ICljl, $_Q61I1;
  $_Q68ff = "SELECT DISTINCT `id`, `Name` FROM `$_ICljl`";
  $_Q68ff .= " WHERE ";
  $_Q68ff .= " `$_ICljl`.`Type`="._OPQLR($_J0LJf); // defaults
  $_Q68ff .= " ORDER BY `Name` ASC";

  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  return $_Q60l1;
}

function GetMessages() {
  global $_QLo0Q, $_Q61I1;
  $_Q68ff = "SELECT DISTINCT `id`, `Name` FROM `$_QLo0Q`";
  $_Q68ff .= " ORDER BY `Name` ASC";

  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  return $_Q60l1;
}

  function _O8CJ8($_I0o0o, $_IQ6tO){
   global $UserId, $_Q60QL, $_Q8f1L, $_Q6jOo, $_IQL81, $_QCLCI, $_IIl8O,
          $_IoOLJ, $_IooOQ, $_IoCtL, $_QoOft, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

    $_Ioi61 = array();

    $_Ioi61[] = array(
                                   "TableName" => $_Q60QL,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]
                                 );
    if(_OA1LL($_IQL81)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IQL81,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceAutoresponder"]
                                 );
    }

    if(_OA1LL($_QCLCI)) {
      $_Ioi61[] = array(
                                   "TableName" => $_QCLCI,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceFollowUpResponder"]
                                 );
    }

    if(_OA1LL($_IIl8O)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IIl8O,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceBirthdayResponder"]
                                 );
    }

    if(_OA1LL($_IoOLJ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IoOLJ,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceRSS2EMailResponder"]
                                 );
    }

    if(_OA1LL($_Q6jOo)) {
      $_Ioi61[] = array(
                                   "TableName" => $_Q6jOo,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceCampaign"]
                                 );
    }

    if(_OA1LL($_IooOQ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IooOQ,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["001820"]
                                 );
    }

    if(_OA1LL($_IoCtL)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IoCtL,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceSMSCampaign"]
                                 );
    }

    if(_OA1LL($_QoOft)) {
      $_Ioi61[] = array(
                                   "TableName" => $_QoOft,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceDistributionList"]
                                 );
    }

    // referenzen vorhanden?
    $_IoL1l = 0;
    $_J0lj0 = array();
    for($_Q6llo=0; $_Q6llo<count($_Ioi61); $_Q6llo++) {

      $_I6oQj = "`maillists_id`";
      if($_Ioi61[$_Q6llo]["TableName"] == $_Q60QL)
        $_I6oQj = "`id`";

      $_QJlJ0 = "SELECT `Name` FROM `".$_Ioi61[$_Q6llo]["TableName"]."` WHERE $_I6oQj=$_I0o0o AND `forms_id`=$_IQ6tO";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      while($_IO08Q = mysql_fetch_assoc($_ItlJl)){
        $_IoL1l++;
        $_J0lj0[] = $_Ioi61[$_Q6llo]["Description"].": ".$_IO08Q["Name"];
      }
      mysql_free_result($_ItlJl);
    }

    return $_J0lj0;
  }

?>

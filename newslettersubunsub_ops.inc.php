<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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

  include_once("mail.php");
  include_once("mailer.php");
  include_once("maillogger.php");
  include_once("replacements.inc.php");
  include_once("recipients_ops.inc.php");

  function _J0Q81($_I6tLJ, $_Jj08l, $HTMLForm, $_ftl0l = false, $_fO00O = "") {
    global $_Ij8oL, $_QljJi, $resourcestrings, $INTERFACE_STYLE, $INTERFACE_LANGUAGE, $_QLl1Q, $_QLo06, $_QLttI;

    // set theme and language of form
    // try{}finally() not support before PHP 5.5, never return or exit!!
    $_fO0QQ = $INTERFACE_STYLE;
    $_fO0JO = $INTERFACE_LANGUAGE;
    $INTERFACE_LANGUAGE = $_Jj08l["Language"];
    $INTERFACE_STYLE = $_Jj08l["Theme"];
    _JQRLR($INTERFACE_LANGUAGE);

    if(empty($_fO00O))
      $_fO00O = $_QLo06;

    if(is_array($_Jj08l["fields"]))
      $_IOJoI = $_Jj08l["fields"];
      else
      if($_Jj08l["fields"] != "") {
           $_IOJoI = @unserialize($_Jj08l["fields"]);
           if($_IOJoI === false)
             $_IOJoI["u_EMail"] = "visiblerequired";
         }
         else
         $_IOJoI["u_EMail"] = "visiblerequired";

    $_QLfol = "SELECT `text`, `fieldname` FROM `$_Ij8oL` WHERE `language`="._LRAFO($INTERFACE_LANGUAGE);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    if($HTMLForm == "editform") {
      if(!isset($_Jj08l) || empty($_Jj08l["UserDefinedFormsFolder"]))
        $_QLJfI = join("", file(_LOC8P()."default_edit_page.htm"));
        else
        $_QLJfI = join("", file(_LPC1C(InstallPath.$_Jj08l["UserDefinedFormsFolder"])."default_edit_page.htm"));

      if(isset($_I6tLJ["Action"]))
        unset($_I6tLJ["Action"]);
      if(isset($_Jj08l["SubscriptionUnsubscription"])){
        if($_Jj08l["SubscriptionUnsubscription"] == "Allowed" || $_Jj08l["SubscriptionUnsubscription"] == "UnsubscribeOnly"){
          $_QLJfI = str_replace("<IF:UNSUBSCRIBE_ALLOWED>", "", $_QLJfI);
          $_QLJfI = str_replace("</IF:UNSUBSCRIBE_ALLOWED>", "", $_QLJfI);
        }else{
          $_QLJfI = _L80DF($_QLJfI, '<IF:UNSUBSCRIBE_ALLOWED>', '</IF:UNSUBSCRIBE_ALLOWED>');
        }
      }
      }
    else {
      if(!isset($_Jj08l) || empty($_Jj08l["UserDefinedFormsFolder"]))
        $_QLJfI = join("", file(_LOC8P()."default_subscribeunsubscribe_page.htm"));
        else
        $_QLJfI = join("", file( _LPC1C(InstallPath.$_Jj08l["UserDefinedFormsFolder"])."default_subscribeunsubscribe_page.htm"));
    }

    if(isset($_I6tLJ["rid"]) && strpos($_I6tLJ["rid"], "_x") !== false){
      $_QLJfI = str_replace("./defaultnewsletter.php", "./defaultnewsletter.php?rid=".$_I6tLJ["rid"], $_QLJfI);
    }

    $_IOiJ0 = _L81DB($_QLJfI, '<TABLE:ROW>', '</TABLE:ROW>');

    // Gender block
    $_fO1I8 = _L81DB($_QLJfI, '<TABLE:GENDER>', '</TABLE:GENDER>');
    $_QLJfI = _L80DF($_QLJfI, '<TABLE:GENDER>', '</TABLE:GENDER>');

    // Title block
    $_fO1OO = _L81DB($_QLJfI, '<TABLE:TITLE>', '</TABLE:TITLE>');
    $_QLJfI = _L80DF($_QLJfI, '<TABLE:TITLE>', '</TABLE:TITLE>');

    // New email address block
    $_fOQ6O = _L81DB($_QLJfI, '<TABLE:ROW_NEW_EMAILADDRESS>', '</TABLE:ROW_NEW_EMAILADDRESS>');

    // captcha block
    if(!$_Jj08l["UseCaptcha"] && !$_Jj08l["UseReCaptcha"] && !$_Jj08l["UseReCaptchav3"]) {
      $_QLJfI = _L80DF($_QLJfI, "<TABLE:CAPTCHA>", "</TABLE:CAPTCHA>");
    } else {

          if( $_Jj08l["UseCaptcha"] ) {

            $_QLJfI = _L81BJ($_QLJfI, "<CAPTCHA:TEXT>", "</CAPTCHA:TEXT>", $resourcestrings[$INTERFACE_LANGUAGE]["DefaultCaptchaText"]);
            if(!$_ftl0l) // executable form?
              $_QLJfI = str_replace('image.php?"', 'image.php?'.md5(uniqid(rand(), true)).'"', $_QLJfI);
              else
              $_QLJfI = str_replace('image.php?"', 'image.php?'."<?php echo md5(uniqid(rand(), true)) ?>".'"', $_QLJfI);
          }

          if( $_Jj08l["UseReCaptcha"] ) {
              $_QLJfI = _L81BJ($_QLJfI, "<CAPTCHA:TEXT>", "</CAPTCHA:TEXT>", $resourcestrings[$INTERFACE_LANGUAGE]["DefaultReCaptchaText"]);
           /* recaptchav1 if(!$_ftl0l) // executable form?
              $_QLJfI = _L81BJ($_QLJfI, "<CAPTCHA:INTERNAL>", "</CAPTCHA:INTERNAL>", recaptcha_get_html($_Jj08l["PublicReCaptchaKey"]));
              else */
              $_QLJfI = _L81BJ($_QLJfI, "<CAPTCHA:INTERNAL>", "</CAPTCHA:INTERNAL>", '<div class="g-recaptcha" data-sitekey="'.$_Jj08l["PublicReCaptchaKey"].'"></div>');
              if(strpos($_QLJfI, "recaptcha/api.js") === false) {
                $_QLJfI = str_replace("</form>", '<script src="https://www.google.com/recaptcha/api.js"></script>'.$_QLl1Q."</form>", $_QLJfI);
              }
          }

          if( $_Jj08l["UseReCaptchav3"] ) {
            $_QLJfI = _L80DF($_QLJfI, "<TABLE:CAPTCHA>", "</TABLE:CAPTCHA>");

            $_Iljoj = '<script src="https://www.google.com/recaptcha/api.js?render=' . $_Jj08l["PublicReCaptchaKey"] . '"></script>';

            $_Iljoj .= '<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" />';

            $_Iljoj .= '<script><!--' . $_QLl1Q. '
                function ReCaptchaOnSubmit(){
                  grecaptcha.ready(function() {' . $_QLl1Q. '
                      grecaptcha.execute("' . $_Jj08l["PublicReCaptchaKey"] . '", {action: "newslettersubunsub"}).then(function(token) {' . $_QLl1Q. '
                        document.getElementById("g-recaptcha-response").value=token;' . $_QLl1Q. ' 
                      });' . $_QLl1Q. '
                  });' . $_QLl1Q. '

                  return true;
                }
                var forms = document.getElementsByTagName("form");
                for(var j=0; j<forms.length; j++) {
                  forms[j].onsubmit = ReCaptchaOnSubmit;
                }
                ReCaptchaOnSubmit();
                
                --></script>';

            $_QLJfI = str_replace("</form>", $_Iljoj.$_QLl1Q."</form>", $_QLJfI);
          }

       $_QLJfI = _L8OF8($_QLJfI, "<TABLE:CAPTCHA>");
    }


    $_QLJfI = _L8OF8($_QLJfI, "<TABLE:NOT_CAPTCHA>");

    // Bool block
    $_fOIQo = _L81DB($_QLJfI, '<TABLE:USERBOOL>', '</TABLE:USERBOOL>');
    $_QLJfI = _L80DF($_QLJfI, '<TABLE:USERBOOL>', '</TABLE:USERBOOL>');

    // Personalized Tracking Block
    $_fOI8i = _L81DB($_QLJfI, '<TABLE:PERSONALIZEDTRACKING>', '</TABLE:PERSONALIZEDTRACKING>');
    $_QLJfI = _L80DF($_QLJfI, '<TABLE:PERSONALIZEDTRACKING>', '</TABLE:PERSONALIZEDTRACKING>');

    // only email address
    if($HTMLForm == "unsubform"){
      $_IOJoI = array();
      $_IOJoI["u_EMail"] = "visiblerequired";
    }

    $_QLoli = "";
    $_fOjI8 = isset($_IOJoI["u_EMailFormat"]) && $_IOJoI["u_EMailFormat"] != "invisible";
    $_fOJI6 = isset($_IOJoI["u_EMailFormat"]) && $_IOJoI["u_EMailFormat"] == "visiblerequired";
    $_fO6Ij = isset($_IOJoI["u_Gender"]) && $_IOJoI["u_Gender"] != "invisible";
    $_fO6fL = isset($_IOJoI["u_Salutation"]) && $_IOJoI["u_Salutation"] != "invisible";

    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if( isset($_IOJoI[$_QLO0f["fieldname"]]) && $_IOJoI[$_QLO0f["fieldname"]] != "invisible" ) {
          $_Ql0fO = $_IOiJ0;
          $_fO68f = "";
          if($HTMLForm == "editform" && $_QLO0f["fieldname"] == "u_EMail" && !empty($_I6tLJ["key"]))
              $_fO68f = ' readonly="readonly"';
          if($_IOJoI[$_QLO0f["fieldname"]] == "visiblerequired")
             $_Ql0fO = str_replace("<!--FIELDNAME//-->", '<label for="'.$_QLO0f["fieldname"].'">'.$_QLO0f["text"].'</label>' . "&nbsp;*", $_Ql0fO);
             else
             $_Ql0fO = str_replace("<!--FIELDNAME//-->", '<label for="'.$_QLO0f["fieldname"].'">'.$_QLO0f["text"].'</label>', $_Ql0fO);
          if($_QLO0f["fieldname"] != "u_EMailFormat" && $_QLO0f["fieldname"] != "u_Gender" && $_QLO0f["fieldname"] != "u_Salutation" && strpos($_QLO0f["fieldname"], "u_User") === false && strpos($_QLO0f["fieldname"], "u_PersonalizedTracking") === false ) {
             $_It18j = 50;
             $_fO6iC = 255;
             if($_QLO0f["fieldname"] == "u_Birthday") {
                $_It18j= 10;
                $_fO6iC = 10;
             }
             $_Ql0fO = str_replace("<!--INPUTFIELD//-->", '<input type="text" name="'.$_QLO0f["fieldname"].'" size="'.$_It18j.'" maxlength="'.$_fO6iC.'" id="' . $_QLO0f["fieldname"] . '"'.$_fO68f.' />', $_Ql0fO);
             }
             elseif($_QLO0f["fieldname"] == "u_Gender") {
                $_Ql0fO = $_fO1I8;
             }
             elseif($_QLO0f["fieldname"] == "u_Salutation") {
                $_Ql0fO = $_fO1OO;
                if($_IOJoI[$_QLO0f["fieldname"]] != "visiblerequired")
                  $_Ql0fO = str_replace("*", "", $_Ql0fO);
             }
             elseif($_QLO0f["fieldname"] == "u_PersonalizedTracking") {
                $_Ql0fO = $_fOI8i;
                if($_IOJoI[$_QLO0f["fieldname"]] != "visiblerequired")
                  $_Ql0fO = str_replace("*", "", $_Ql0fO);
                $_Ql0fO = _L81BJ($_Ql0fO, '<DESCRIPTION>', '</DESCRIPTION>', $_QLO0f["text"]);
             }
             elseif(! (strpos($_QLO0f["fieldname"], "u_User") === false) ) {
               if( strpos($_QLO0f["fieldname"], "u_UserFieldBool") === false ) {
                  if( strpos($_QLO0f["fieldname"], "u_UserFieldInt") === false )
                     $_Ql0fO = str_replace("<!--INPUTFIELD//-->", '<input type="text" name="'.$_QLO0f["fieldname"].'" size="50" id="' . $_QLO0f["fieldname"] . '" />', $_Ql0fO);
                     else
                     $_Ql0fO = str_replace("<!--INPUTFIELD//-->", '<input type="text" name="'.$_QLO0f["fieldname"].'" size="5" style="text-align: right" id="' . $_QLO0f["fieldname"] . '"'.$_fO68f.' />', $_Ql0fO);
               } else {
                  $_Ql0fO = $_fOIQo;
                  $_Ql0fO = str_replace('"u_UserFieldBool"', '"'.$_QLO0f["fieldname"].'"', $_Ql0fO);
                  $_Ql0fO = _L81BJ($_Ql0fO, '<DESCRIPTION>', '</DESCRIPTION>', $_QLO0f["text"]);
                  if($_IOJoI[$_QLO0f["fieldname"]] != "visiblerequired")
                     $_Ql0fO = str_replace("*", "", $_Ql0fO);
               }

             }
             else {
               continue;
             }
          $_QLoli .= $_Ql0fO;
      }
    }
    mysql_free_result($_QL8i1);
    if($_fOjI8) {
      $_QLJfI = str_replace("<TABLE:EMAILFORMAT>", "", $_QLJfI);
      $_QLJfI = str_replace("</TABLE:EMAILFORMAT>", "", $_QLJfI);
      // ever $_fOJI6
    } else {
      $_QLJfI = _L80DF($_QLJfI, '<TABLE:EMAILFORMAT>', '</TABLE:EMAILFORMAT>');
    }

    if($_fO6Ij && !isset($_I6tLJ["u_Gender"]))
       $_I6tLJ["u_Gender"] = "m";

    // ********* fields end

    $_QLJfI = _L81BJ($_QLJfI, '<TABLE:ROW>', '</TABLE:ROW>', $_QLoli);
    $_QLJfI = _L81BJ($_QLJfI, '<TABLE:ROW_NEW_EMAILADDRESS>', '</TABLE:ROW_NEW_EMAILADDRESS>', $_fOQ6O);

    if($_Jj08l["GroupsOption"] == 2) {
      if(!$_Jj08l["GroupsAsCheckBoxes"])
        $_fOf00 = _L81DB($_QLJfI, '<TABLE:GROUPS>', '</TABLE:GROUPS>');
        else
        $_fOf00 = _L81DB($_QLJfI, '<TABLE:GROUPSCHECKBOXES>', '</TABLE:GROUPSCHECKBOXES>');

      // ********* List of Groups SQL query
      $_QlI6f = "SELECT DISTINCT `id`, `Name` FROM `$_QljJi`";
      $_QlI6f .= " ORDER BY `Name` ASC";
      $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
      $_ItlLC = "";
      if(!isset($_I6tLJ["RecipientGroups"]) ) {
        $_fOfil = ' selected="selected"';
        $_IOfJi = ' checked="checked"';
      } else {
        $_fOfil = "";
        $_IOfJi = "";
      }
      $_Qli6J=0;
      while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
        if(isset($_I6tLJ["RecipientGroups"]) && isset($_I6tLJ["RecipientGroups"][$_QLO0f["id"]]) ) {
          $_fOfil = ' selected="selected"';
          $_IOfJi = ' checked="checked"';
        }
        if(!$_Jj08l["GroupsAsCheckBoxes"])
           $_ItlLC .= '<option value="'.$_QLO0f["id"].'"'.$_fOfil.'>'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
           else
           $_ItlLC .= '<input type="checkbox" name="RecipientGroups['/*.$_Qli6J*/.']" value="'.$_QLO0f["id"].'" '.$_IOfJi.' id="RecipientGroups_'.$_QLO0f["id"].'" /><label for="RecipientGroups_'.$_QLO0f["id"].'" name="RecipientGroups">&nbsp;'.$_QLO0f["Name"]."</label><br />".$_QLl1Q;
        $_fOfil = "";
        $_IOfJi = "";
        $_Qli6J++;
      }
      mysql_free_result($_QL8i1);
      // ********* List of Groups query END
      if(!$_Jj08l["GroupsAsCheckBoxes"])
         $_fOf00 = _L81BJ($_fOf00, "<option>", "</option>", $_ItlLC);
         else
         $_fOf00 = _L81BJ($_fOf00, "<checkbox>", "</checkbox>", $_ItlLC);

      if(!$_Jj08l["GroupsAsCheckBoxes"])
        $_QLJfI = _L81BJ($_QLJfI, '<TABLE:GROUPS>', '</TABLE:GROUPS>', $_fOf00);
        else
        $_QLJfI = _L81BJ($_QLJfI, '<TABLE:GROUPSCHECKBOXES>', '</TABLE:GROUPSCHECKBOXES>', $_fOf00);

    }
    $_QLJfI = _L80DF($_QLJfI, '<TABLE:GROUPS>', '</TABLE:GROUPS>');
    $_QLJfI = _L80DF($_QLJfI, '<TABLE:GROUPSCHECKBOXES>', '</TABLE:GROUPSCHECKBOXES>');

    $_I016j = htmlentities( ConvertString($_QLo06, $_fO00O, $_Jj08l["GroupsDescriptionLabel"], false), ENT_COMPAT, $_ftl0l ? $_fO00O : $_QLo06, false);
    if(!$_I016j || $_I016j == "")
      $_I016j = ConvertString($_QLo06, $_fO00O, $_Jj08l["GroupsDescriptionLabel"], false);

    $_QLJfI = _L81BJ($_QLJfI, '<GroupsDescriptionLabel>', '</GroupsDescriptionLabel>', $_I016j );

    if($_Jj08l["PrivacyPolicyURL"] == "" || $HTMLForm == "unsubform"){
      $_QLJfI = _L80DF($_QLJfI, '<if:PrivacyPolicyURL>', '</if:PrivacyPolicyURL>');
    } else{
      $_I016j = htmlentities( ConvertString($_QLo06, $_fO00O, $_Jj08l["PrivacyPolicyURLText"], false), ENT_COMPAT || ENT_NOQUOTES, $_ftl0l ? $_fO00O : $_QLo06, false);
      if(!$_I016j || $_I016j == "")
        $_I016j = ConvertString($_QLo06, $_fO00O, $_Jj08l["PrivacyPolicyURLText"], false);
      $_I016j = str_replace('&lt;', "<", $_I016j);
      $_I016j = str_replace('&gt;', ">", $_I016j);
      $_I016j = str_replace('&#039;', "'", $_I016j);
      $_QLJfI = _L81BJ($_QLJfI, '<PrivacyPolicyURLText>', '</PrivacyPolicyURLText>', $_I016j);
      $_QLJfI = _L81BJ($_QLJfI, '<PrivacyPolicyURL>', '</PrivacyPolicyURL>', $_Jj08l["PrivacyPolicyURL"]); #old variant with <>
      $_QLJfI = str_replace('[PrivacyPolicyURL]', $_Jj08l["PrivacyPolicyURL"], $_QLJfI);
      $_QLJfI = str_replace("<if:PrivacyPolicyURL>", "", $_QLJfI);
      $_QLJfI = str_replace("</if:PrivacyPolicyURL>", "", $_QLJfI);
    }

    if($HTMLForm == "unsubform" || $HTMLForm == "subform"){
      if($HTMLForm == "unsubform") {
          $_fOfLj = '<input type="hidden" name="Action" value="unsubscribe" />';
          $_QLJfI = str_replace('value="SUBMITBTNTEXT"', 'value="'.$resourcestrings[$INTERFACE_LANGUAGE]["UNSUBSCRIBEBtnText"].'"', $_QLJfI);
         }
         else {
           $_fOfLj = '<input type="hidden" name="Action" value="subscribe" />';
           $_QLJfI = str_replace('value="SUBMITBTNTEXT"', 'value="'.$resourcestrings[$INTERFACE_LANGUAGE]["SUBSCRIBEBtnText"].'"', $_QLJfI);
         }
      $_QLJfI = _L81BJ($_QLJfI, "<TABLE:SUBUNSUB>", "</TABLE:SUBUNSUB>", $_fOfLj);
    } else{
      $_QLJfI = str_replace("<TABLE:SUBUNSUB>", "", $_QLJfI);
      $_QLJfI = str_replace("</TABLE:SUBUNSUB>", "", $_QLJfI);
      $_QLJfI = str_replace('value="SUBMITBTNTEXT"', 'value="'.$resourcestrings[$INTERFACE_LANGUAGE]["SUBMITBtnText"].'"', $_QLJfI);
    }

    $INTERFACE_STYLE = $_fO0QQ;
    $INTERFACE_LANGUAGE = $_fO0JO;
    _JQRLR($INTERFACE_LANGUAGE);

    return $_QLJfI;
  }

  function _J0OLC(&$_IfLJj, $_I80Jo, $_Io0OJ, $_IflO6, $Action, &$errors, &$_I816i) {
    global $_QLttI, $_QL88I, $_QLo06;

    foreach($_Io0OJ as $key => $_QltJO) {
      $_Io0OJ[$key] = _LA8F6($_QltJO);
    }

    // Boolean fields of form
    $_ItI0o = Array ();
    $_ItIti = Array ();

    $_I8I6o = $_I80Jo["MaillistTableName"];
    $_I8jjj = $_I80Jo["StatisticsTableName"];
    $_QljJi = $_I80Jo["GroupsTableName"];
    $_IfJ66 = $_I80Jo["MailListToGroupsTableName"];
    $_I8Jti = $_I80Jo["EditTableName"];

    $_Iflj0 = array();

    _L8FPJ($_I8I6o, $_Iflj0);

    // Mit Platzhalter <ÿMaillistTableNameÿ> weil er es mehrfach nutzen muss/koennte
    if ($_IfLJj == 0)  {
      $_QLfol = "INSERT IGNORE INTO <ÿMaillistTableNameÿ> SET DateOfSubscription=NOW()";

      if($_I80Jo["SubscriptionType"] == 'SingleOptIn') {
         $_QLfol .= ", IPOnSubscription='Single Opt In', SubscriptionStatus='Subscribed', DateOfOptInConfirmation=NOW(), ";
      } else {
         $_QLfol .= ", IPOnSubscription='".getOwnIP(false)."', SubscriptionStatus='OptInConfirmationPending', ";
      }
    } else { // recipient exists
      if($Action == "subscribeconfirm")
        $_QLfol = "UPDATE <ÿMaillistTableNameÿ> SET IsActive=1, DateOfOptInConfirmation=NOW(), IPOnSubscription='".getOwnIP(false)."',  SubscriptionStatus='Subscribed' WHERE id=".intval($_IfLJj);
        else
        $_QLfol = "UPDATE <ÿMaillistTableNameÿ> SET ";
    }

    if(isset($_Io0OJ["DuplicateEntry"]))
       $_QLfol = "";

    $_fO8Qj = false;
    if($Action == "edit") {
     if( isset($_Io0OJ["NewEMail"]) )
       $NewEMail = $_Io0OJ["NewEMail"];
     if( isset($_Io0OJ["Newu_EMail"]) )
       $NewEMail = $_Io0OJ["Newu_EMail"];

     // set new email address
     if(isset($NewEMail)) {
       # on double opt in only
       if($_I80Jo["SubscriptionType"] != 'SingleOptIn')
         $_fO8Qj = strtolower($_Io0OJ["u_EMail"]) <> strtolower($NewEMail);

       if(!$_fO8Qj)
         $_Io0OJ["u_EMail"] = $NewEMail;
         else
         $_Io0OJ["NewEMail"] = $NewEMail; // for later use
     }
    }

    if($Action == "editconfirm" && !$_fO8Qj){
      $_I1OoI = @unserialize(base64_decode($_Io0OJ["ChangedDetails"]));
      unset( $_Io0OJ["ChangedDetails"] );
      if($_I1OoI !== false)
        $_Io0OJ = array_merge($_I1OoI, $_Io0OJ);
      $Action = "edit";
      if( isset($_Io0OJ["NewEMail"]) )
        $NewEMail = $_Io0OJ["NewEMail"];
      if(isset($NewEMail)) {
          $_Io0OJ["u_EMail"] = $NewEMail;
      }
      // remove entry from EditTable
      $_fOtjo = "DELETE FROM `$_Io0OJ[EditTableName]` WHERE `Member_id`=".intval($_Io0OJ["RecipientId"]);
      mysql_query($_fOtjo, $_QLttI);
    }

    if ( ($_IfLJj == 0 || ($Action == "edit" && !$_fO8Qj)) && !isset($_Io0OJ["DuplicateEntry"]) ) {
      $_Io01j = array();
      reset($_Io0OJ);
      foreach($_Io0OJ as $key => $_QltJO) {
        // ignore none database fields
        if(!isset($_Iflj0[$key]) ) continue;

        if( $key == "u_Birthday" && isset($_Io0OJ[$key]) && $_Io0OJ[$key] != "" ) {
            $_fti1L = ".";
            if( strpos($_Io0OJ[$key], ".") === false ) {
              if( strpos($_Io0OJ[$key], "-") === false ) {
                if( strpos($_Io0OJ[$key], "/") === false ) {
                } else $_fti1L = "/";
              }
              else $_fti1L = "-";
            }

            $_I1OoI = explode($_fti1L, $_Io0OJ[$key]);

            if(strlen($_I1OoI[0]) == 2) {
             $_Io0OJ[$key] = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
            } else {
             $_Io0OJ[$key] = $_I1OoI[0]."-".$_I1OoI[1]."-".$_I1OoI[2];
            }
        } else {
          if( $key == "u_Birthday" && isset($_Io0OJ[$key]) && $_Io0OJ[$key] != "" )
            unset($_Io0OJ[$key]);
        }

        // remove Salutation if empty
        if( $key == "u_Salutation" && isset($_Io0OJ[$key]) && !(strpos($_Io0OJ[$key], "--") === false) ) {
          unset($_Io0OJ[$key]);
        }

        if ( isset($_Io0OJ[$key]) ) {
          if(in_array($key, $_ItI0o))
            if( $_Io0OJ[$key] == "1" || intval($_Io0OJ[$key]) == 0 )
               $_Io01j[] = "$key=1";
               else
                ;
          else {
             if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){ // XSS protection
               $_Io01j[] = "$key="._LRAFO(rtrim( htmlspecialchars($_Io0OJ[$key], ENT_COMPAT, $_QLo06, false)) );
             } else{
               $_Io01j[] = "$key="._LRAFO(rtrim($_Io0OJ[$key]) );
             }
          }
        } else {
           if(in_array($key, $_ItI0o)) {
             $key = $_Iflj0[$key];
             $_Io01j[] = "$key=0";
           } else {
             if(in_array($key, $_ItIti)) {
               $key = $_Iflj0[$key];
               $_Io01j[] = "$key=0";
             }
           }
        }
      }

      $_QLfol .= join(", ", $_Io01j);
    }

    // edit entry
    if($Action == "edit" && !$_fO8Qj) {
     $_QLfol .= " WHERE id=".intval($_IfLJj);
    }

    if($Action == "edit" && $_fO8Qj) {
      $_QLfol = "";
    }

    if($_QLfol != "") {
      $_fOtjC = $_QLfol;
      $_QLfol = str_replace('<ÿMaillistTableNameÿ>', $_I8I6o, $_QLfol);

      $_QL8i1 = mysql_query($_QLfol, $_QLttI);

      if(mysql_error($_QLttI) != "") {
        $errors[] = mysql_error($_QLttI);
        $_I816i[] = mysql_error($_QLttI)." ".$_QLfol;
        return 0;
      }

      $_JJQQi = "";
      $_fOtC6 = "";
      if($_IfLJj == 0) {
        $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
        $_QLO0f=mysql_fetch_array($_QL8i1);
        $_IfLJj = $_QLO0f[0];
        mysql_free_result($_QL8i1);

        if($_I80Jo["SubscriptionType"] == 'SingleOptIn' )
          $_JJQQi = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action='Subscribed', Member_id=".intval($_IfLJj);
          else
          $_JJQQi = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action='OptInConfirmationPending', Member_id=".intval($_IfLJj);
      } else {
          if($Action == "edit")
            $_JJQQi = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action='Edited', Member_id=".intval($_IfLJj);
          if($Action == "subscribeconfirm") {
            $_JJQQi = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action='Subscribed', Member_id=".intval($_IfLJj);
            $_fOtC6 = "DELETE FROM $_I8jjj WHERE Member_id=".intval($_IfLJj)." AND Action='OptInConfirmationPending'";
          }
      }

      if($_JJQQi != "")
        $_QL8i1 = mysql_query($_JJQQi, $_QLttI);
      if($_fOtC6 != "")
        $_QL8i1 = mysql_query($_fOtC6, $_QLttI);

    } else
      if( $Action == "edit" && !$_fO8Qj && $_Io0OJ["DuplicateEntry"] )
         $_IfLJj = $_Io0OJ["DuplicateEntry"];

    if($Action == "edit" && $_fO8Qj) {
      reset($_Io0OJ);
      $_fOOOo = array();
      foreach($_Io0OJ as $key => $_QltJO){
        $_QlOjt = strpos($key, "u_");
        if( ($_QlOjt !== false && $_QlOjt == 0) or ($_QlOjt === false && ($key == "NewEMail" || $key == "RecipientGroups") ) )
           $_fOOOo[$key] = $_QltJO;
      }


      $_QLfol = "SELECT `Member_id` FROM `$_I8Jti` WHERE `Member_id`=".intval($_IfLJj);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) == 0) {
        $_QLfol = "INSERT INTO `$_I8Jti` SET `Member_id`=".intval($_IfLJj).", `ChangeDate`=NOW(), `ChangedDetails`="._LRAFO( base64_encode(serialize( $_fOOOo )) );
      } else {
        $_QLfol = "UPDATE `$_I8Jti` SET `ChangeDate`=NOW(), `ChangedDetails`="._LRAFO( base64_encode(serialize( $_fOOOo )) )." WHERE `Member_id`=".intval($_IfLJj);
      }
      mysql_query($_QLfol, $_QLttI);
      mysql_free_result($_QL8i1);
    } // if($Action == "edit" && $_fO8Qj)

    // ********* Add to groups on subscribe
    if( $Action == "subscribe"  ) {
      // add to all selected groups
      if($_IflO6["GroupsOption"] == 2 && isset($_Io0OJ["RecipientGroups"]) ) {
        $_QLfol = "SELECT id FROM $_QljJi";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
          if( array_key_exists ($_QLO0f["id"], $_Io0OJ["RecipientGroups"] ) ) {
            // isset($_Io0OJ["DuplicateEntry"]) then the existing group references are removed from _LFD8Q()
            if(isset($_Io0OJ["DuplicateEntry"]) && intval($_Io0OJ["DuplicateEntry"]) > 0 && $_IfLJj == 0)
               $_IfLJj = intval($_Io0OJ["DuplicateEntry"]);
            $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=$_QLO0f[id], Member_id=".intval($_IfLJj);
            mysql_query($_QLfol, $_QLttI);
          }
        }
      } // if($_IflO6["GroupsOption"] == 2 && isset($_Io0OJ["RecipientGroups"]) )

      // add to specified group
      if($_IflO6["GroupsOption"] == 3) {
        $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=".intval($_IflO6["groups_id"]).", Member_id=".intval($_IfLJj);
        mysql_query($_QLfol, $_QLttI);
      }
    }
    // on Edit
    if( $Action == "edit" && !$_fO8Qj && $_IflO6["GroupsOption"] == 2 && isset($_Io0OJ["RecipientGroups"])  ) {
      $_POST["OneRecipientId"] = intval($_IfLJj);
      $_POST["OneMailingListId"] = intval($_I80Jo["id"]);
      if(isset($_IQ0Cj))
        unset($_IQ0Cj);
      $_IQ0Cj = array();
      _J1QBE();
      _J1J0O(array($_IfLJj), $_IfJ66, array_keys($_Io0OJ["RecipientGroups"]));
      unset($_POST["OneRecipientId"]);
      unset($_POST["OneMailingListId"]);
    }
    // on Edit
    if( $Action == "edit" && !$_fO8Qj && $_IflO6["GroupsOption"] == 2 && !isset($_Io0OJ["RecipientGroups"])  ) {
      $_POST["OneRecipientId"] = intval($_IfLJj);
      $_POST["OneMailingListId"] = intval($_I80Jo["id"]);
      if(isset($_IQ0Cj))
        unset($_IQ0Cj);
      $_IQ0Cj = array();
      _J1QBE();
      // remove from all groups
      _J1J0O(array($_IfLJj), $_IfJ66, array(), false);
      unset($_POST["OneRecipientId"]);
      unset($_POST["OneMailingListId"]);
    }
    // ********* Add to groups END

    if( ( $_I80Jo["SubscriptionType"] == 'SingleOptIn' || $Action == "subscribeconfirm" || ($Action == "edit" && !$_fO8Qj) ) && !isset($_Io0OJ["DuplicateEntry"])  ) {
       $_JJQQi = "";

      // insert in another mailinglist?
      if($_I80Jo["OnSubscribeAlsoAddToMailList"] > 0) {

        $_jf8JI = "SELECT MaillistTableName, StatisticsTableName, MailLogTableName, GroupsTableName, MailListToGroupsTableName FROM $_QL88I WHERE id=$_I80Jo[OnSubscribeAlsoAddToMailList]";
        $_QL8i1 = mysql_query($_jf8JI, $_QLttI);
        if($_QL8i1 && mysql_num_rows($_QL8i1) > 0)
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          else
          if (isset($_QLO0f))
            unset($_QLO0f);

        if($_QL8i1)
          mysql_free_result($_QL8i1);
        if(isset($_QLO0f)) {
          $_QLfol = "SELECT * FROM $_I8I6o WHERE id=".intval($_IfLJj);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_j11Io = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);

          if($Action != "edit") {
            $_QLfol = "INSERT IGNORE INTO $_QLO0f[MaillistTableName] SET SubscriptionStatus='Subscribed', ";
            $_Io01j = array();
            reset($_j11Io);
            foreach ($_j11Io as $key => $_QltJO) {
               if($key == "id" || $key == "SubscriptionStatus" || $key == "IdentString") continue;
               $_Io01j[] = "$key="._LRAFO($_QltJO);
            }

            $_QLfol .= join(", ", $_Io01j);
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);

            if($_QL8i1 && mysql_affected_rows($_QLttI) > 0) {
              $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
              $_I1OfI=mysql_fetch_row($_QL8i1);
              $_fOoIt = $_I1OfI[0];
              mysql_free_result($_QL8i1);

              $_fOolj = new _LFBOB();
              $_fOolj->_LF0QR($_QLO0f["MailLogTableName"], $_fOoIt, "SYSTEM: subscribed recipient automatically copied from source mailing list '" . $_I80Jo["Name"] . "'");
              $_fOolj = null;

              $_JJQQi = "INSERT INTO $_QLO0f[StatisticsTableName] SET ActionDate=NOW(), Action='Subscribed', Member_id=$_fOoIt";
            }
          } else {
            $_QLfol = "SELECT id FROM $_QLO0f[MaillistTableName] WHERE u_EMail="._LRAFO($_Io0OJ["u_EMail"]);
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
              $_I1OfI=mysql_fetch_row($_QL8i1);
              $_fOoIt = $_I1OfI[0];

              $_QLfol = "UPDATE $_QLO0f[MaillistTableName] SET ";
              $_Io01j = array();
              reset($_j11Io);
              foreach ($_j11Io as $key => $_QltJO) {
                if($key == "id" || $key == "SubscriptionStatus" || $key == "IdentString") continue;
                 $_Io01j[] = "$key="._LRAFO($_QltJO);
              }
              $_QLfol .= join(", ", $_Io01j);
              $_QLfol .= " WHERE id=".$_fOoIt;
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              $_JJQQi = "INSERT INTO $_QLO0f[StatisticsTableName] SET ActionDate=NOW(), Action='Edited', Member_id=$_fOoIt";
            }
          }
        }
      } // if($_I80Jo["OnSubscribeAlsoAddToMailList"] > 0)

      // remove from another mailinglist?
      if($_I80Jo["OnSubscribeAlsoRemoveFromMailList"] > 0 && $Action != "edit" ) {
        $_jf8JI = "SELECT MaillistTableName, StatisticsTableName, MailListToGroupsTableName FROM $_QL88I WHERE id=$_I80Jo[OnSubscribeAlsoRemoveFromMailList]";
        $_QL8i1 = mysql_query($_jf8JI, $_QLttI);
        if($_QL8i1 && mysql_num_rows($_QL8i1) > 0)
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          else
          if (isset($_QLO0f))
            unset($_QLO0f);
        if($_QL8i1)
          mysql_free_result($_QL8i1);
        if(isset($_QLO0f)) {

          if(!isset($_Io0OJ["u_EMail"])) {
            $_QLfol = "SELECT u_EMail FROM $_I8I6o WHERE id=".intval($_IfLJj);
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            $_j11Io = mysql_fetch_assoc($_QL8i1);
            mysql_free_result($_QL8i1);
            $_Io0OJ["u_EMail"] = $_j11Io["u_EMail"];
          }

          $_QLfol = "SELECT id FROM $_QLO0f[MaillistTableName] WHERE u_EMail="._LRAFO($_Io0OJ["u_EMail"]);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);

          if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
            $_I1OfI=mysql_fetch_assoc($_QL8i1);
            $_fOoIt = $_I1OfI["id"];
            mysql_free_result($_QL8i1);

            // remove it
            $_POST["OneRecipientId"] = $_fOoIt;
            $_POST["OneMailingListId"] = $_I80Jo["OnSubscribeAlsoRemoveFromMailList"];
            if(isset($_IQ0Cj))
              unset($_IQ0Cj);
            $_IQ0Cj = array();
            _J1QBE();
            _J1OQP(array($_fOoIt), $_IQ0Cj);
            unset($_POST["OneRecipientId"]);
            unset($_POST["OneMailingListId"]);

            $_JJQQi = "INSERT INTO $_QLO0f[StatisticsTableName] SET ActionDate=NOW(), Action='Unsubscribed', Member_id=".intval($_IfLJj);
          }
        }
      } // if($_I80Jo["OnSubscribeAlsoRemoveFromMailList"] > 0)

      if($_JJQQi != "")
        mysql_query($_JJQQi, $_QLttI);

    } // if( ($_I80Jo["SubscriptionType"] == 'SingleOptIn' && ($Action == "edit" && !$_fO8Qj)) || ($Action == "subscribeconfirm")  )

    // create unique key and send opt-in email
    if($_I80Jo["SubscriptionType"] == 'DoubleOptIn' && $Action == "subscribe" && !isset($_Io0OJ["DuplicateEntry"])  ) {
      // create confirmation IdentString
      $_6JIjo = "";
      $_6JIjo = _LPQ8Q($_6JIjo, intval($_IfLJj), intval($_IflO6["MailingListId"]), intval($_IflO6["FormId"]), $_I8I6o);
      $rid = "";
      if(isset($_Io0OJ["rid"]))
        $rid = $_Io0OJ["rid"];
      return _J0LAA("subscribeconfirm", $_IfLJj, $_I80Jo, $_IflO6, $errors, $_I816i, $rid);
    } // if($_I80Jo["SubscriptionType"] == 'DoubleOptIn' && $Action == "subscribe" && !isset($_Io0OJ["DuplicateEntry"])  )


    // create unique key and send opt-in email
    if($_I80Jo["SubscriptionType"] == 'DoubleOptIn' && $Action == "edit" && $_fO8Qj && !isset($_Io0OJ["DuplicateEntry"])  ) {
      // create confirmation IdentString
      $_6JIjo = "";
      $_6JIjo = _LPQ8Q($_6JIjo, intval($_IfLJj), intval($_IflO6["MailingListId"]), intval($_IflO6["FormId"]), $_I8I6o);
      $rid = "";
      if(isset($_Io0OJ["rid"]))
        $rid = $_Io0OJ["rid"];
      $_fOC66 = $_IfLJj;
      $_IfLJj = "editconfirm";
      $_I80Jo["NewEMail"] = $_Io0OJ["NewEMail"]; // save new email address for confirmation
      return _J0LAA("editconfirm", $_fOC66, $_I80Jo, $_IflO6, $errors, $_I816i, $rid);
    } // if($_I80Jo["SubscriptionType"] == 'DoubleOptIn' && $Action == "edit" && $_fO8Qj && !isset($_Io0OJ["DuplicateEntry"])  )

    return $_IfLJj;
  }

  function _J0O61($_IfLJj, &$_I80Jo, $_Io0OJ, $_IflO6, $Action, &$errors, &$_I816i) {
    global $_QLttI, $_QL88I, $_I8tfQ;

    $rid = "";
    if(!empty($_Io0OJ["rid"]))
      $rid = $_Io0OJ["rid"];

    $_I8I6o = $_I80Jo["MaillistTableName"];
    $_I8jjj = $_I80Jo["StatisticsTableName"];
    $_QljJi = $_I80Jo["GroupsTableName"];
    $_IfJ66 = $_I80Jo["MailListToGroupsTableName"];

    if($_I80Jo["UnsubscriptionType"] == 'SingleOptOut' || $Action == "unsubscribeconfirm" ) {
       // add to another group than we must save the record
       if($_I80Jo["OnUnsubscribeAlsoAddToMailList"] > 0) {
          $_QLfol = "SELECT * FROM $_I8I6o WHERE id=".intval($_IfLJj);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_j11Io = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);
       }
    }

    $_fOCij = true;

    // SingleOptOut or confirmation
    if($_I80Jo["UnsubscriptionType"] == 'SingleOptOut' || ($Action == "unsubscribeconfirm") ) {

      // handle groups
      if( isset($_Io0OJ["RecipientGroups"]) ) {
         // remove it
         $_POST["OneRecipientId"] = intval($_IfLJj);
         $_POST["OneMailingListId"] = intval($_I80Jo["id"]);
         if(isset($_IQ0Cj))
           unset($_IQ0Cj);
         $_IQ0Cj = array();
         _J1QBE();
         $_fOiJ0 = _J1JJL(array($_IfLJj), $_I80Jo["id"], $_IfJ66, array_keys($_Io0OJ["RecipientGroups"]));
         if($_fOiJ0 && $rid != "") {
           _LPJPD($rid);
           $rid = "";
         }

         // get name of groups for Admin notify email
         if($_fOiJ0){
           $_fOi8f = array();
           $_QLfol = "SELECT `Name` FROM `$_QljJi` WHERE ";
           $_QLlO6 = array();
           $_jt0QC = array_keys($_Io0OJ["RecipientGroups"]);
           foreach($_jt0QC as $_QliOt => $_QltJO) {
              $_QLlO6[] = intval(trim($_jt0QC[$_QliOt]));
           }
           $_Ql0fO = "";
           for($_Qli6J=0; $_Qli6J<count($_QLlO6); $_Qli6J++){
             if($_Ql0fO == "")
               $_Ql0fO = "`id`= ".$_QLlO6[$_Qli6J];
             else
               $_Ql0fO .= " OR `id`= ".$_QLlO6[$_Qli6J];
           }
           $_QLfol .= $_Ql0fO;
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if($_QL8i1){
             while($_QLO0f = mysql_fetch_assoc($_QL8i1))
               $_fOi8f[] = $_QLO0f["Name"];
             mysql_free_result($_QL8i1);
           }
           if(count($_fOi8f))
             $_I80Jo["removedFromGroups"] = join(", ", $_fOi8f);
         }

         $_QLfol = "SELECT COUNT(*) FROM $_IfJ66 WHERE Member_id=".intval($_IfLJj);
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_QLO0f = mysql_fetch_row($_QL8i1);

         // no references?
         if($_QLO0f[0] == 0) {
           // remove it completly
           if(isset($_IQ0Cj))
             unset($_IQ0Cj);
           $_IQ0Cj = array();
           _J1QBE();
           _J1OQP(array($_IfLJj), $_IQ0Cj);
           if(count($_IQ0Cj) > 0) {
             $errors = array_merge($errors, $_IQ0Cj);
             $_I816i = array_merge($_I816i, $_IQ0Cj);
             return false;
           }

           if($rid != "") {
             _LPJPD($rid);
             $rid = "";
           }

           if($_I80Jo["AddUnsubscribersToLocalBlacklist"]) {
             $_QLfol = "INSERT IGNORE INTO `$_I80Jo[LocalBlocklistTableName]` SET u_EMail="._LRAFO( $_Io0OJ["u_EMail"] );
             mysql_query($_QLfol, $_QLttI);
           }
           if($_I80Jo["AddUnsubscribersToGlobalBlacklist"]) {
             $_QLfol = "INSERT IGNORE INTO `$_I8tfQ` SET u_EMail="._LRAFO( $_Io0OJ["u_EMail"] );
             mysql_query($_QLfol, $_QLttI);
           }

         } else { // if($_QLO0f[0] == 0)

            // for groups set SubscriptionStatus back to 'Subscribed'
            $_QLfol = "UPDATE $_I8I6o SET SubscriptionStatus ='Subscribed', DateOfUnsubscription=0 WHERE id=".intval($_IfLJj);
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            // groups: he is not in this given groups, but always exists
            $_fOCij = $_fOiJ0 > 0;
         }

         unset($_POST["OneRecipientId"]);
         unset($_POST["OneMailingListId"]);

      } else {

         // remove it
         $_POST["OneRecipientId"] = intval($_IfLJj);
         $_POST["OneMailingListId"] = intval($_I80Jo["id"]);
         if(isset($_IQ0Cj))
           unset($_IQ0Cj);
         $_IQ0Cj = array();
         _J1QBE();
         _J1OQP(array($_IfLJj), $_IQ0Cj);
         unset($_POST["OneRecipientId"]);
         unset($_POST["OneMailingListId"]);

         if(count($_IQ0Cj) > 0) {
           $errors = array_merge($errors, $_IQ0Cj);
           $_I816i = array_merge($_I816i, $_IQ0Cj);
           return false;
         }

         if($rid != "") {
           _LPJPD($rid);
           $rid = "";
         }

         if($_I80Jo["AddUnsubscribersToLocalBlacklist"]) {
           $_QLfol = "INSERT IGNORE INTO `$_I80Jo[LocalBlocklistTableName]` SET u_EMail="._LRAFO( $_Io0OJ["u_EMail"] );
           mysql_query($_QLfol, $_QLttI);
         }
         if($_I80Jo["AddUnsubscribersToGlobalBlacklist"]) {
           $_QLfol = "INSERT IGNORE INTO `$_I8tfQ` SET u_EMail="._LRAFO( $_Io0OJ["u_EMail"] );
           mysql_query($_QLfol, $_QLttI);
         }

      }
    } // if($_I80Jo["UnsubscriptionType"] == 'SingleOptOut' || ($Action == "unsubscribeconfirm") )

    if($_I80Jo["UnsubscriptionType"] == 'DoubleOptOut' && ($Action == "unsubscribe") ) {
       $_QLfol = "UPDATE $_I8I6o SET SubscriptionStatus ='OptOutConfirmationPending', DateOfUnsubscription=NOW() WHERE id=".intval($_IfLJj);
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    } // if($_I80Jo["UnsubscriptionType"] == 'DoubleOptOut' && ($Action == "unsubscribe") )

    $_fOLfC = "";
    $_JJQQi = "";
    $_fOtC6 = "";
    if( $_I80Jo["UnsubscriptionType"] == 'SingleOptOut' || $Action == "unsubscribeconfirm" ) {
       $_fOLfC = "DELETE FROM $_I8jjj WHERE Member_id=".intval($_IfLJj)." AND Action='Unsubscribed'"; // statistics for groups, this can give more than one entry in statistics for Action
       $_JJQQi = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action='Unsubscribed', Member_id=".intval($_IfLJj);
       $_fOtC6 = "DELETE FROM $_I8jjj WHERE Member_id=".intval($_IfLJj)." AND Action='OptOutConfirmationPending'";
      }
      else
      $_JJQQi = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action='OptOutConfirmationPending', Member_id=".intval($_IfLJj);

    mysql_query("BEGIN", $_QLttI);
    if($_fOLfC != "")
      $_QL8i1 = mysql_query($_fOLfC, $_QLttI);
    if($_JJQQi != "")
      $_QL8i1 = mysql_query($_JJQQi, $_QLttI);
    if($_fOtC6 != "")
      $_QL8i1 = mysql_query($_fOtC6, $_QLttI);
    mysql_query("COMMIT", $_QLttI);


    if( $_fOCij && ($_I80Jo["UnsubscriptionType"] == 'SingleOptOut' || $Action == "unsubscribeconfirm")  ) {

      $_JJQQi = "";
      // insert in another mailinglist?
      if($_I80Jo["OnUnsubscribeAlsoAddToMailList"] > 0) {

        $_QLfol = "SELECT MaillistTableName, StatisticsTableName, MailLogTableName FROM $_QL88I WHERE id=$_I80Jo[OnUnsubscribeAlsoAddToMailList]";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if($_QL8i1 && mysql_num_rows($_QL8i1) > 0)
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          else
          if (isset($_QLO0f))
            unset($_QLO0f);
        if($_QL8i1)
          mysql_free_result($_QL8i1);
        if(isset($_QLO0f)) {

          $_QLfol = "INSERT IGNORE INTO $_QLO0f[MaillistTableName] SET SubscriptionStatus='Subscribed', ";
          $_Io01j = array();
          reset($_j11Io);
          foreach ($_j11Io as $key => $_QltJO) {
             if($key == "id" || $key == "SubscriptionStatus" || $key == "IdentString") continue;
             $_Io01j[] = "$key="._LRAFO($_QltJO);
          }

          $_QLfol .= join(", ", $_Io01j);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);

          if($_QL8i1 && mysql_affected_rows($_QLttI) > 0) {
            $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
            $_I1OfI = mysql_fetch_row($_QL8i1);
            $_fOoIt = $_I1OfI[0];
            mysql_free_result($_QL8i1);

            $_fOolj = new _LFBOB();
            $_fOolj->_LF0QR($_QLO0f["MailLogTableName"], $_fOoIt, "SYSTEM: unsubscribed recipient automatically copied from source mailing list '" . $_I80Jo["Name"] . "'");
            $_fOolj = null;

            $_JJQQi = "INSERT INTO $_QLO0f[StatisticsTableName] SET ActionDate=NOW(), Action='Subscribed', Member_id=$_fOoIt";
          }
        }
      } // if($_I80Jo["OnUnsubscribeAlsoAddToMailList"] > 0)

      // remove from another mailinglist?
      if($_I80Jo["OnUnsubscribeAlsoRemoveFromMailList"] > 0) {
        $_jf8JI = "SELECT MaillistTableName, StatisticsTableName, MailListToGroupsTableName FROM $_QL88I WHERE id=$_I80Jo[OnUnsubscribeAlsoRemoveFromMailList]";
        $_QL8i1 = mysql_query($_jf8JI, $_QLttI);
        if($_QL8i1 && mysql_num_rows($_QL8i1) > 0)
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          else
          if (isset($_QLO0f))
            unset($_QLO0f);
        if($_QL8i1)
          mysql_free_result($_QL8i1);
        if(isset($_QLO0f)) {
          $_QLfol = "SELECT id FROM $_QLO0f[MaillistTableName] WHERE u_EMail="._LRAFO($_Io0OJ["u_EMail"]);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);

          if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
            $_I1OfI=mysql_fetch_array($_QL8i1);
            $_fOoIt = $_I1OfI["id"];
            mysql_free_result($_QL8i1);

            $_POST["OneRecipientId"] = $_fOoIt;
            $_POST["OneMailingListId"] = $_I80Jo["OnUnsubscribeAlsoRemoveFromMailList"];
            _J1QBE();
            _J1OQP(array($_fOoIt), $_IQ0Cj);

            unset($_POST["OneRecipientId"]);
            unset($_POST["OneMailingListId"]);

            $_JJQQi = "INSERT INTO $_QLO0f[StatisticsTableName] SET ActionDate=NOW(), Action='Unsubscribed', Member_id=$_fOoIt";
          }
        }
      } // if($_I80Jo["OnUnsubscribeAlsoRemoveFromMailList"] > 0)

      if($_JJQQi != "")
         mysql_query($_JJQQi, $_QLttI);

    } // if( $_I80Jo["UnsubscriptionType"] == 'SingleOptOut' || ($Action == "unsubscribeconfirm")  )

    // create unique key and send opt-out email
    if($_I80Jo["UnsubscriptionType"] == 'DoubleOptOut' && $Action == "unsubscribe"  ) {
      // create confirmation IdentString
      if(!empty($_Io0OJ["key"]))
        $_6JIjo = $_Io0OJ["key"];
        else
        $_6JIjo = "";
      $_6JIjo = _LPQ8Q($_6JIjo, intval($_IfLJj), intval($_IflO6["MailingListId"]), intval($_IflO6["FormId"]), $_I8I6o);

      $rid = "";
      if(!empty($_Io0OJ["rid"]))
        $rid = $_Io0OJ["rid"];

      $_jt0QC = array();
      if (isset($_Io0OJ["RecipientGroups"])) {
        _J1QBE();
        $_JjfO0 = array_keys($_Io0OJ["RecipientGroups"]);
        foreach($_JjfO0 as $_QliOt => $_QltJO) {
          $_jt0QC[] = $_JjfO0[$_QliOt];
        }
        $_fOiJ0 = _J1608($_IfLJj, $_I80Jo["id"], $_IfJ66, array_keys($_Io0OJ["RecipientGroups"]));

        // no references in this groups -> don't send confirmation mail
        if(!$_fOiJ0)
          $_fOCij = false;
      }

      if($_fOCij)
        return _J0LAA("unsubscribeconfirm", $_IfLJj, $_I80Jo, $_IflO6, $errors, $_I816i, $rid, $_jt0QC);

    } // if($_I80Jo["UnsubscriptionType"] == 'DoubleOptOut' && $Action == "unsubscribe"  )

    // only group handling, recipients exists, but not in given group(s)
    if(!$_fOCij){
      $errors[] = $_IflO6["EMailAddressNotInList"];
      $_I816i[] = $_IflO6["EMailAddressNotInList"];
      return false;
    }

    return true;
  }

  function _J0OFP($_IfLJj, $_I80Jo, $_Io0OJ, $_IflO6, $Action, &$errors, &$_I816i) {
    global $_QLttI;
    $_I8jjj = $_I80Jo["StatisticsTableName"];

    // remove it
    $_POST["OneRecipientId"] = intval($_IfLJj);
    $_POST["OneMailingListId"] = intval($_I80Jo["id"]);
    if(isset($_IQ0Cj))
      unset($_IQ0Cj);
    $_IQ0Cj = array();
    _J1QBE();
    _J1OQP(array($_IfLJj), $_IQ0Cj);
    unset($_POST["OneRecipientId"]);
    unset($_POST["OneMailingListId"]);

    $_JJQQi = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action='Unsubscribed', Member_id=".intval($_IfLJj);
    mysql_query($_JJQQi, $_QLttI);

    if(count($_IQ0Cj) > 0) {
      $_I816i = array_merge($_I816i, $_IQ0Cj);
      return false;
    }

    return true;
  }

  function _J0LQ0($_IfLJj, $_I80Jo, $_Io0OJ, $_IflO6, $Action, &$errors, &$_I816i) {
    global $_QLttI;
    $_I8I6o = $_I80Jo["MaillistTableName"];
    $_I8jjj = $_I80Jo["StatisticsTableName"];
    //$_QljJi = $_I80Jo["GroupsTableName"];
    //$_IfJ66 = $_I80Jo["MailListToGroupsTableName"];

    $_QLfol = "UPDATE `$_I8I6o` SET `SubscriptionStatus`='Subscribed' WHERE id=".intval($_IfLJj);
    mysql_query($_QLfol, $_QLttI);
    if(mysql_error($_QLttI) != "") {
      $_I816i[] = mysql_error($_QLttI);
    }

    $_JJQQi = "DELETE FROM `$_I8jjj` WHERE `Action`='OptOutConfirmationPending' AND `Member_id`=".intval($_IfLJj);
    mysql_query($_JJQQi, $_QLttI);

    return count($_I816i) == 0 ? true : false;
  }

  function _J0LJD($_IfLJj, $_I80Jo, $_Io0OJ, $_IflO6, $Action, &$errors, &$_I816i) {
    global $_QLttI;

    $_QLfol = "DELETE FROM `$_I80Jo[EditTableName]` WHERE `Member_id`=".intval($_IfLJj);
    mysql_query($_QLfol, $_QLttI);

    return true;
  }

  function _J0LAA($Type, $_IfLJj, $_I80Jo, $_IflO6, &$errors, &$_I816i, $rid="", $_fOLiQ=array()) {
    global $_JQ1I6, $_Ijt0i, $_QLl1Q, $_QLttI;
    global $_JQtOo, $_JQOIC, $_JQOtf, $_IolCJ;
    global $_IIlfi, $_IJi8f, $_J1tfC, $_J1t6J;
    global $_J1OIO, $_J1Cf8, $_J1Clo;
    global $_J1tCf, $_J1OLl, $_J1oCI;

    $_I8I6o = $_I80Jo["MaillistTableName"];

    $_ICfJQ = $_I80Jo["SenderFromName"];
    $_Io6Lf = $_I80Jo["SenderFromAddress"];
    $_Io8Jj = $_I80Jo["ReplyToEMailAddress"];
    $_Ioftt = $_I80Jo["ReturnPathEMailAddress"];
    $_QLftI = $_I80Jo["AllowOverrideSenderEMailAddressesWhileMailCreating"];
    $_j1IIf = $_IflO6["OverrideSubUnsubURL"];

    if(isset($_Iot6L))
     unset($_Iot6L);
    if(isset($_Iot8C))
     unset($_Iot8C);

    if(!empty($_I80Jo["CcEMailAddresses"]))
      $_Iot6L = $_I80Jo["CcEMailAddresses"];
    if(!empty($_I80Jo["BCcEMailAddresses"]))
      $_Iot8C = $_I80Jo["BCcEMailAddresses"];

    if($_QLftI) {
      if(!empty($_IflO6["SenderFromName"]))
        $_ICfJQ = $_IflO6["SenderFromName"];
      if(!empty($_IflO6["SenderFromAddress"]))
        $_Io6Lf = $_IflO6["SenderFromAddress"];
      if(!empty($_IflO6["ReplyToEMailAddress"]))
        $_Io8Jj = $_IflO6["ReplyToEMailAddress"];
      if(!empty($_IflO6["ReturnPathEMailAddress"]))
        $_Ioftt = $_IflO6["ReturnPathEMailAddress"];
      if(!empty($_IflO6["CcEMailAddresses"]))
        $_Iot6L = $_IflO6["CcEMailAddresses"];
      if(!empty($_IflO6["BCcEMailAddresses"]))
        $_Iot8C = $_IflO6["BCcEMailAddresses"];
    }

    // Get recipient data
    if(!is_array($_IfLJj)) {
      $_IfLJj = intval($_IfLJj);
      $_I80Jo["id"] = intval($_I80Jo["id"]);
      $_IflO6["FormId"] = intval($_IflO6["FormId"]);
      $_QLfol = "SELECT * FROM `$_I8I6o` WHERE id=$_IfLJj";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_QLO0f["IdentString"] = _LPQ8Q($_QLO0f["IdentString"], $_IfLJj, $_I80Jo["id"], $_IflO6["FormId"], $_I8I6o);
      # only on edit confirmation
      if(isset($_I80Jo["NewEMail"]) && $Type == "editconfirm") {
        $_QLO0f["u_EMail"] = $_I80Jo["NewEMail"];
        unset($_I80Jo["NewEMail"]);
      }
    } else {
      $_QLO0f = $_IfLJj;
      $_IfLJj = intval($_QLO0f["id"]);
      $_I80Jo["id"] = intval($_I80Jo["id"]);
      $_IflO6["FormId"] = intval($_IflO6["FormId"]);
      $_QLO0f["IdentString"] = _LPQ8Q($_QLO0f["IdentString"], $_IfLJj, $_I80Jo["id"], $_IflO6["FormId"], $_I8I6o);
    }

    // MTAs
    $_QLfol = "SELECT $_Ijt0i.* FROM $_Ijt0i RIGHT JOIN $_I80Jo[MTAsTableName] ON $_I80Jo[MTAsTableName].mtas_id=$_Ijt0i.id ORDER BY $_I80Jo[MTAsTableName].sortorder";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    // we take the first in list
    $_J00C0 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_fOl0i = array();
    $MailType = mtInternalMail;
    if($Type == "subscribeconfirm") {
      $_Ioolt = $_IflO6["OptInConfirmationMailEncoding"];
      $Subject = $_IflO6["OptInConfirmationMailSubject"];
      $_IoC0i = $_IflO6["OptInConfirmationMailPlainText"];
      $_IooIi = $_IflO6["OptInConfirmationMailFormat"];
      $_IoQi6 = $_IflO6["OptInConfirmationMailHTMLText"];
      $_jLtII = $_IflO6["OptInConfirmationMailPriority"];
      $MailType = mtOptInConfirmationMail;
      }
      else
      if($Type == "unsubscribeconfirm") {
          $_Ioolt = $_IflO6["OptOutConfirmationMailEncoding"];
          $Subject = $_IflO6["OptOutConfirmationMailSubject"];
          $_IoC0i = $_IflO6["OptOutConfirmationMailPlainText"];
          $_IooIi = $_IflO6["OptOutConfirmationMailFormat"];
          $_IoQi6 = $_IflO6["OptOutConfirmationMailHTMLText"];
          $_jLtII = $_IflO6["OptOutConfirmationMailPriority"];
          $MailType = mtOptOutConfirmationMail;
        }
        else
        if($Type == "subscribeconfirmed") {
          $_Ioolt = $_IflO6["OptInConfirmedMailEncoding"];
          $Subject = $_IflO6["OptInConfirmedMailSubject"];
          $_IoC0i = $_IflO6["OptInConfirmedMailPlainText"];
          $_IooIi = $_IflO6["OptInConfirmedMailFormat"];
          $_IoQi6 = $_IflO6["OptInConfirmedMailHTMLText"];
          $_jLtII = $_IflO6["OptInConfirmedMailPriority"];
          $MailType = mtOptInConfirmedMail;
          if($_IflO6["OptInConfirmedAttachments"] != "")
            $_fOl0i = unserialize($_IflO6["OptInConfirmedAttachments"]);
          }
          else
          if($Type == "unsubscribeconfirmed") {
              $_Ioolt = $_IflO6["OptOutConfirmedMailEncoding"];
              $Subject = $_IflO6["OptOutConfirmedMailSubject"];
              $_IoC0i = $_IflO6["OptOutConfirmedMailPlainText"];
              $_IooIi = $_IflO6["OptOutConfirmedMailFormat"];
              $_IoQi6 = $_IflO6["OptOutConfirmedMailHTMLText"];
              $_jLtII = $_IflO6["OptOutConfirmedMailPriority"];
              $MailType = mtOptOutConfirmedMail;
              if($_IflO6["OptOutConfirmedAttachments"] != "") {
                $_fOl0i = @unserialize($_IflO6["OptOutConfirmedAttachments"]);
                if($_fOl0i === false)
                  $_fOl0i = array();
              }
            }
            else
             if($Type == "editconfirm") {
               $_Ioolt = $_IflO6["EditConfirmationMailEncoding"];
               $Subject = $_IflO6["EditConfirmationMailSubject"];
               $_IoC0i = $_IflO6["EditConfirmationMailPlainText"];
               $_IooIi = $_IflO6["EditConfirmationMailFormat"];
               $_IoQi6 = $_IflO6["EditConfirmationMailHTMLText"];
               $_jLtII = $_IflO6["EditConfirmationMailPriority"];
               $MailType = mtEditConfirmationMail;
               }
               else
                if($Type == "editconfirmed") {
                  $_Ioolt = $_IflO6["EditConfirmedMailEncoding"];
                  $Subject = $_IflO6["EditConfirmedMailSubject"];
                  $_IoC0i = $_IflO6["EditConfirmedMailPlainText"];
                  $_IooIi = $_IflO6["EditConfirmedMailFormat"];
                  $_IoQi6 = $_IflO6["EditConfirmedMailHTMLText"];
                  $_jLtII = $_IflO6["EditConfirmedMailPriority"];
                  $MailType = mtEditConfirmedMail;
                  }


    // mail object
    $_j10IJ = new _LEFO8($MailType);
    $_j10IJ->DisableECGListCheck();

    // set mail object params
    $_j10IJ->_LEQ1C();

    // MUST overwrite it?
    if(!empty($_J00C0["MTASenderEMailAddress"]))
      $_Io6Lf = $_J00C0["MTASenderEMailAddress"];

    $_j10IJ->From[] = array("address" => $_Io6Lf, "name" => _J1EBE($_QLO0f, $_I80Jo["id"], $_ICfJQ, $_Ioolt, false, array()) );
    $_j10IJ->To[] = array("address" => $_QLO0f["u_EMail"], "name" => _J1EBE($_QLO0f, $_I80Jo["id"], $_QLO0f["u_FirstName"]." ".$_QLO0f["u_LastName"], $_Ioolt, false, array()) );
    if($_Io8Jj != "")
       $_j10IJ->ReplyTo[] = array("address" => $_Io8Jj, "name" => "");
    if($_Ioftt != "")
      $_j10IJ->ReturnPath[] = array("address" => $_Ioftt, "name" => "");

    if(!empty($_Iot6L)) {
       $_IjO6t = explode(",", $_Iot6L);
       for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++)
         if(trim($_IjO6t[$_Qli6J]) != "")
           $_j10IJ->Cc[] = array("address" => trim($_IjO6t[$_Qli6J]), "name" => "");
    }

    if(!empty($_Iot8C)) {
       $_IjO6t = explode(",", $_Iot8C);
       for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++)
         if(trim($_IjO6t[$_Qli6J]) != "")
           $_j10IJ->BCc[] = array("address" => trim($_IjO6t[$_Qli6J]), "name" => "");
    }

    $_j10IJ->Subject = _J1EBE($_QLO0f, $_I80Jo["id"], $Subject, $_Ioolt, false, array());

    $_fILtI = array ();
    if($Type == "subscribeconfirm") {
      reset($_JQtOo);
      foreach ($_JQtOo as $key => $_QltJO) {
         $_IOCjL = "";
         if ($_QltJO == '[SubscribeRejectLink]')
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?Action=subscribereject&key=".$_QLO0f["IdentString"];
            else
            if ($_QltJO == '[SubscribeConfirmationLink]')
              $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?Action=subscribeconfirm&key=".$_QLO0f["IdentString"];
         if(!empty($rid))
           $_IOCjL .= "&rid=$rid";
         $_fILtI[$_QltJO] = $_IOCjL;
      }
    } elseif($Type == "unsubscribeconfirm") {
      reset($_JQOIC);
      foreach ($_JQOIC as $key => $_QltJO) {
         $_IOCjL = "";
         if ($_QltJO == '[UnsubscribeRejectLink]')
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?Action=unsubscribereject&key=".$_QLO0f["IdentString"];
            else
            if ($_QltJO == '[UnsubscribeConfirmationLink]') {
              $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?Action=unsubscribeconfirm&key=".$_QLO0f["IdentString"];
              if(count($_fOLiQ) > 0)
                 $_IOCjL .= "&RG=".urlencode(join(",", $_fOLiQ));
            }
         if(!empty($rid))
           $_IOCjL .= "&rid=$rid";
         $_fILtI[$_QltJO] = $_IOCjL;
      }
    } elseif($Type == "subscribeconfirmed" || $Type == "editconfirmed") {
      reset($_IolCJ);
      foreach ($_IolCJ as $key => $_QltJO) {
         $_IOCjL = "";
         if ($_QltJO == '[UnsubscribeLink]') {
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?key=$_QLO0f[IdentString]";
            if(count($_fOLiQ) > 0)
               $_IOCjL .= "&RG=".urlencode(join(",", $_fOLiQ));
         }
         if ($_QltJO == '[EditLink]') {
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?key=".$_QLO0f["IdentString"]."&ML=$_I80Jo[id]&F=$_IflO6[FormId]&HTMLForm=editform";
         }
         if(!empty($rid))
           $_IOCjL .= "&rid=$rid";
         $_fILtI[$_QltJO] = $_IOCjL;
      }
    } elseif($Type == "editconfirm") {
      reset($_JQOtf);
      foreach ($_JQOtf as $key => $_QltJO) {
         $_IOCjL = "";
         if ($_QltJO == '[EditRejectLink]')
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?Action=editreject&key=".$_QLO0f["IdentString"];
            else
            if ($_QltJO == '[EditConfirmationLink]') {
              $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?Action=editconfirm&key=".$_QLO0f["IdentString"];
              if(count($_fOLiQ) > 0)
                 $_IOCjL .= "&RG=".urlencode(join(",", $_fOLiQ));
            }
         if(!empty($rid))
           $_IOCjL .= "&rid=$rid";
         $_fILtI[$_QltJO] = $_IOCjL;
      }
    }

    $_j10IJ->TextPart = _J1EBE($_QLO0f, $_I80Jo["id"], $_IoC0i, $_Ioolt, false, $_fILtI);
    if($_IooIi != "PlainText") {
       $_66flC = $_IoQi6;
       $_66flC = _LPFQD("<title>", "</title>", $_66flC, $Subject);
       $_66flC = _J1EBE($_QLO0f, $_I80Jo["id"], $_66flC, $_Ioolt, true, $_fILtI);
       $_66flC = SetHTMLCharSet($_66flC, $_Ioolt, true);

       // inline images
       $_j10IJ->_LEQ1D();
       $_JiI11 = array();
       GetInlineFiles($_66flC, $_JiI11);
       for($_Qli6J=0; $_Qli6J< count($_JiI11); $_Qli6J++) {
         if(!@file_exists($_JiI11[$_Qli6J])) {
           $_QLJfI = _LA6ED($_JiI11[$_Qli6J]);
           $_66flC = str_replace($_JiI11[$_Qli6J], $_QLJfI, $_66flC);
           $_JiI11[$_Qli6J] = $_QLJfI;
         }
         $_j10IJ->InlineImages[] = array ("file" => $_JiI11[$_Qli6J], "c_type" => _LALJ6($_JiI11[$_Qli6J]), "name" => "", "isfile" => true );
       }

       $_j10IJ->HTMLPart = $_66flC;
    }

    switch ($_jLtII) {
      case 'Low' :
         $_j10IJ->Priority = mpLow;
         break;
      case 'Normal':
         $_j10IJ->Priority = mpNormal;
         break;
      case 'High'  :
         $_j10IJ->Priority = mpHighest;
    }

    // attachments
    $_j10IJ->_LEQFP();
    for($_Qli6J=0; $_Qli6J< count($_fOl0i); $_Qli6J++) {
      $_j10IJ->Attachments[] = array ("file" => $_IIlfi.CheckFileNameForUTF8($_fOl0i[$_Qli6J]), "c_type" => "application/octet-stream", "name" => "", "isfile" => true );
    }

    // email options
    $_QLfol = "SELECT * FROM $_JQ1I6";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_I1OfI = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_j10IJ->crlf = $_I1OfI["CRLF"];
    $_j10IJ->head_encoding = $_I1OfI["Head_Encoding"];
    $_j10IJ->text_encoding = $_I1OfI["Text_Encoding"];
    $_j10IJ->html_encoding = $_I1OfI["HTML_Encoding"];
    $_j10IJ->attachment_encoding = $_I1OfI["Attachment_Encoding"];
    $_j10IJ->XMailer = $_I1OfI["XMailer"];

    $_j10IJ->charset = $_Ioolt;


  // mail send settings
    $_j10IJ->Sendvariant = $_J00C0["Type"]; // mail, sendmail, smtp, smtpmx, text

    $_j10IJ->PHPMailParams = $_J00C0["PHPMailParams"];
    $_j10IJ->HELOName = $_J00C0["HELOName"];

    $_j10IJ->SMTPpersist = (bool)$_J00C0["SMTPPersist"];
    $_j10IJ->SMTPpipelining = (bool)$_J00C0["SMTPPipelining"];
    $_j10IJ->SMTPTimeout = $_J00C0["SMTPTimeout"];
    $_j10IJ->SMTPServer = $_J00C0["SMTPServer"];
    $_j10IJ->SMTPPort = $_J00C0["SMTPPort"];
    $_j10IJ->SMTPAuth = (bool)$_J00C0["SMTPAuth"];
    $_j10IJ->SMTPUsername = $_J00C0["SMTPUsername"];
    $_j10IJ->SMTPPassword = $_J00C0["SMTPPassword"];
    if(isset($_J00C0["SMTPSSL"]))
      $_j10IJ->SSLConnection = (bool)$_J00C0["SMTPSSL"];

    $_j10IJ->sendmail_path = $_J00C0["sendmail_path"];
    $_j10IJ->sendmail_args = $_J00C0["sendmail_args"];

    if($_j10IJ->Sendvariant == "savetodir"){
      $_j10IJ->savetodir_filepathandname = _LBQFJ($_J00C0["savetodir_pathname"]);
    }

    $_j10IJ->SignMail = (bool)$_J00C0["SMIMESignMail"];
    $_j10IJ->SMIMEMessageAsPlainText = (bool)$_J00C0["SMIMEMessageAsPlainText"];

    $_j10IJ->SignCert = $_J00C0["SMIMESignCert"];
    $_j10IJ->SignPrivKey = $_J00C0["SMIMESignPrivKey"];
    $_j10IJ->SignPrivKeyPassword = $_J00C0["SMIMESignPrivKeyPassword"];
    $_j10IJ->SignTempFolder = $_J1t6J;
    $_j10IJ->SignExtraCerts = $_J00C0["SMIMESignExtraCerts"];

    $_j10IJ->SMIMEIgnoreSignErrors = (bool)$_J00C0["SMIMEIgnoreSignErrors"];

    $_j10IJ->DKIM = (bool)$_J00C0["DKIM"];
    $_j10IJ->DomainKey = (bool)$_J00C0["DomainKey"];
    $_j10IJ->DKIMSelector = $_J00C0["DKIMSelector"];
    $_j10IJ->DKIMPrivKey = $_J00C0["DKIMPrivKey"];
    $_j10IJ->DKIMPrivKeyPassword = $_J00C0["DKIMPrivKeyPassword"];
    $_j10IJ->DKIMIgnoreSignErrors = (bool)$_J00C0["DKIMIgnoreSignErrors"];

    if(!$_j10IJ->_LEJE8($_fQOLQ, $_ILL61)) {
       $errors[] = $_j10IJ->errors["errorcode"];
       $_I816i[] = $_j10IJ->errors["errortext"];
       _LPAOD($_Io6Lf, join($_QLl1Q, $_I816i)."   ".$Subject);
       return false;
    }

    if(!$_j10IJ->_LE6A8($_fQOLQ, $_ILL61)) {
       $errors[] = $_j10IJ->errors["errorcode"];
       $_I816i[] = $_j10IJ->errors["errortext"];

       if( _LBOL6($_j10IJ->errors["errorcode"], $_j10IJ->errors["errortext"], $_J00C0["Type"]) ) {
          $_JJot0 = _JOLQE("HardbounceCount");
          $_QLfol = "UPDATE `$_I8I6o` SET `BounceStatus`='PermanentlyBounced', `HardbounceCount`=`HardbounceCount`+1, `IsActive`=IF(NOT `IsActive` OR `HardbounceCount`>$_JJot0, 0, 1) WHERE `id`=".intval($_IfLJj);
          mysql_query($_QLfol, $_QLttI);
          $_QLfol = "INSERT INTO `$_I80Jo[StatisticsTableName]` SET `ActionDate`=NOW(), `Action`='Bounced', `AText`="._LRAFO($_j10IJ->errors["errortext"]).", `Member_id`=".intval($_IfLJj);
          mysql_query($_QLfol, $_QLttI);
       }

       _LPAOD($_Io6Lf, join($_QLl1Q, $_I816i)."   ".$Subject);
       return false;
    } else
      $_j10IJ->_LF0QR($_I80Jo["MailLogTableName"], $_IfLJj, ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false));

    return true;
  }

  function _J0JOF($_IfLJj, $_I80Jo, $_JCl0i, $Type, $_IflO6, $_fOlQJ = "") {
   global $INTERFACE_LANGUAGE, $resourcestrings, $_Ij8oL, $_QLl1Q, $_I18lo, $_JQ1I6, $_Ijt0i, $resourcestrings, $_QLttI, $_J1t6J,
   $_QLo06;

   $_Ioolt = $_QLo06;
   $Subject = "";
   $_fOlfL = "";
   $_fOlCf = false;
   switch ($Type) {
     case 'subscribe' :
        $Subject = $resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifySubjectOnSubscribe"];
        if( isset($_I80Jo["SendEMailToAdminOnOptIn"]) )
            $_fOlCf = $_I80Jo["SendEMailToAdminOnOptIn"];
        if( isset($_I80Jo["SendEMailToEMailAddressOnOptIn"]) && $_I80Jo["SendEMailToEMailAddressOnOptIn"] )
            $_fOlfL = $_I80Jo["EMailAddressOnOptInEMailAddress"];
        break;
     case 'unsubscribe':
        $Subject = $resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifySubjectOnUnubscribe"];
        if( isset($_I80Jo["SendEMailToAdminOnOptOut"]) )
            $_fOlCf = $_I80Jo["SendEMailToAdminOnOptOut"];
        if( isset($_I80Jo["SendEMailToEMailAddressOnOptOut"]) && $_I80Jo["SendEMailToEMailAddressOnOptOut"] )
            $_fOlfL = $_I80Jo["EMailAddressOnOptOutEMailAddress"];
        break;
     case 'edit'  :
        $Subject = $resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifySubjectOnEdit"];
        if( isset($_I80Jo["SendEMailToAdminOnOptIn"]) )
            $_fOlCf = $_I80Jo["SendEMailToAdminOnOptIn"];
        if( isset($_I80Jo["SendEMailToEMailAddressOnOptIn"]) && $_I80Jo["SendEMailToEMailAddressOnOptIn"] )
            $_fOlfL = $_I80Jo["EMailAddressOnOptInEMailAddress"];
        break;
   }

   $Subject = sprintf($Subject, $_I80Jo["Name"]);

   $_fo008 = $resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifyBody"];

   if(count($_JCl0i) == 0) {
      $_QLfol = "SELECT * FROM `$_I80Jo[MaillistTableName]` WHERE id=".intval($_IfLJj);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_JCl0i = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
   }

   // Replace placeholders
   if($_fOlfL != "")
      $_fOlfL = _J1EBE($_JCl0i, $_I80Jo["id"], $_fOlfL, $_Ioolt, false, array());

   $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE'";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_IO08l = "";
   while($_Iflj0 = mysql_fetch_assoc($_QL8i1) ) {
     $key = $_Iflj0["fieldname"];
     $_QltJO = $_Iflj0["text"];
     if($key == "u_Birthday" && $_JCl0i[$key] == "0000-00-00") continue;
     if($key == "u_Gender" && $_JCl0i[$key] == "undefined") continue;
     if(isset($_JCl0i[$key]) && !empty($_JCl0i[$key]))
        $_IO08l .= unhtmlentities($_QltJO, strtoupper($_Ioolt) ).": ".ConvertString($_QLo06, $_Ioolt, $_JCl0i[$key], false).$_QLl1Q;
     if($key == "u_EMail" && $_fOlQJ != "") {
        $_IO08l .= unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifyEMailOld"], strtoupper($_Ioolt) ).": ".$_fOlQJ.$_QLl1Q;
     }
   }
   mysql_free_result($_QL8i1);
   $_fo008 .= $_QLl1Q.$_QLl1Q.$_IO08l;
   if(!empty($_I80Jo["removedFromGroups"])){
     $_fo008 .= $_QLl1Q.$resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifyRecipientsGroups"]." ".ConvertString($_QLo06, $_Ioolt, $_I80Jo["removedFromGroups"], false).$_QLl1Q;
   }

   $_QLfol = "SELECT Username, EMail FROM `$_I18lo` WHERE id=$_I80Jo[users_id]";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return false;
   $_I1OfI = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   // mail object
   $_j10IJ = new _LEFO8(mtAdminNotifyMail);

   // MTAs
   $_QLfol = "SELECT `$_Ijt0i`.* FROM `$_Ijt0i` RIGHT JOIN $_I80Jo[MTAsTableName] ON $_I80Jo[MTAsTableName].mtas_id=$_Ijt0i.id ORDER BY $_I80Jo[MTAsTableName].sortorder";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   // we take the first in list
   $_J00C0 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   // set mail object params
   $_j10IJ->_LEQ1C();

   $_Io6Lf = $_I1OfI["EMail"];
  // MUST overwrite it?
  if(!empty($_J00C0["MTASenderEMailAddress"]))
    $_Io6Lf = $_J00C0["MTASenderEMailAddress"];

   $_j10IJ->From[] = array("address" => $_Io6Lf, "name" => $_I1OfI["Username"] );
   if($_fOlCf)
     $_j10IJ->To[] = array("address" => $_I1OfI["EMail"], "name" => $_I1OfI["Username"] );
     else{
       $_IjO6t = explode(",", $_fOlfL);
       for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++)
         if(trim($_IjO6t[$_Qli6J]) != "")
            $_j10IJ->To[] = array("address" => trim($_IjO6t[$_Qli6J]), "name" => trim($_IjO6t[$_Qli6J]) );
     }
   $_j10IJ->Subject = $Subject;
   $_j10IJ->TextPart = $_fo008;

   // email options
   $_QLfol = "SELECT * FROM `$_JQ1I6`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1OfI = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   $_j10IJ->crlf = $_I1OfI["CRLF"];
   $_j10IJ->head_encoding = $_I1OfI["Head_Encoding"];
   $_j10IJ->text_encoding = $_I1OfI["Text_Encoding"];
   $_j10IJ->html_encoding = $_I1OfI["HTML_Encoding"];
   $_j10IJ->attachment_encoding = $_I1OfI["Attachment_Encoding"];

   $_j10IJ->charset = $_Ioolt;


 // mail send settings
   $_j10IJ->Sendvariant = $_J00C0["Type"]; // mail, sendmail, smtp, smtpmx, text

   $_j10IJ->PHPMailParams = $_J00C0["PHPMailParams"];
   $_j10IJ->HELOName = $_J00C0["HELOName"];

   $_j10IJ->SMTPpersist = (bool)$_J00C0["SMTPPersist"];
   $_j10IJ->SMTPpipelining = (bool)$_J00C0["SMTPPipelining"];
   $_j10IJ->SMTPTimeout = $_J00C0["SMTPTimeout"];
   $_j10IJ->SMTPServer = $_J00C0["SMTPServer"];
   $_j10IJ->SMTPPort = $_J00C0["SMTPPort"];
   $_j10IJ->SMTPAuth = (bool)$_J00C0["SMTPAuth"];
   $_j10IJ->SMTPUsername = $_J00C0["SMTPUsername"];
   $_j10IJ->SMTPPassword = $_J00C0["SMTPPassword"];
   if(isset($_J00C0["SMTPSSL"]))
     $_j10IJ->SSLConnection = (bool)$_J00C0["SMTPSSL"];

   $_j10IJ->sendmail_path = $_J00C0["sendmail_path"];
   $_j10IJ->sendmail_args = $_J00C0["sendmail_args"];

   if($_j10IJ->Sendvariant == "savetodir"){
     $_j10IJ->savetodir_filepathandname = _LBQFJ($_J00C0["savetodir_pathname"]);
   }

   $_j10IJ->SignMail = (bool)$_J00C0["SMIMESignMail"];
   $_j10IJ->SMIMEMessageAsPlainText = (bool)$_J00C0["SMIMEMessageAsPlainText"];

   $_j10IJ->SignCert = $_J00C0["SMIMESignCert"];
   $_j10IJ->SignPrivKey = $_J00C0["SMIMESignPrivKey"];
   $_j10IJ->SignPrivKeyPassword = $_J00C0["SMIMESignPrivKeyPassword"];
   $_j10IJ->SignTempFolder = $_J1t6J;
   $_j10IJ->SignExtraCerts = $_J00C0["SMIMESignExtraCerts"];

   $_j10IJ->SMIMEIgnoreSignErrors = (bool)$_J00C0["SMIMEIgnoreSignErrors"];

   $_j10IJ->DKIM = (bool)$_J00C0["DKIM"];
   $_j10IJ->DomainKey = (bool)$_J00C0["DomainKey"];
   $_j10IJ->DKIMSelector = $_J00C0["DKIMSelector"];
   $_j10IJ->DKIMPrivKey = $_J00C0["DKIMPrivKey"];
   $_j10IJ->DKIMPrivKeyPassword = $_J00C0["DKIMPrivKeyPassword"];
   $_j10IJ->DKIMIgnoreSignErrors = (bool)$_J00C0["DKIMIgnoreSignErrors"];

   if(!$_j10IJ->_LEJE8($_fQOLQ, $_ILL61)) {
      $_I816i = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000652"], $_j10IJ->errors["errorcode"], $_j10IJ->errors["errortext"]);
      _LPAOD($_Io6Lf, $_I816i."   ".$Subject);
      return false;
   }
   if(!$_j10IJ->_LE6A8($_fQOLQ, $_ILL61)) {
      $_I816i = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000652"], $_j10IJ->errors["errorcode"], $_j10IJ->errors["errortext"]);
      _LPAOD($_Io6Lf, $_I816i."   ".$Subject);
      return false;
   }

   // send to $_fOlfL?
   if($_fOlCf && $_fOlfL != "") {
      $_I80Jo["SendEMailToAdminOnOptIn"] = 0;
      $_I80Jo["SendEMailToAdminOnOptOut"] = 0;
      return _J0JOF($_IfLJj, $_I80Jo, $_JCl0i, $Type, $_IflO6, $_fOlQJ);
   } else
     return true;
  }

  function _J06OB($_Jj08l, $_QLoli, $_j11Io){
    global $_QLttI, $_JQofl;

    if(!isset($_Jj08l["RequestReasonForUnsubscription"]) || !$_Jj08l["RequestReasonForUnsubscription"] || empty($_Jj08l["ReasonsForUnsubscripeTableName"])){
      $_QLoli = str_ireplace($_JQofl["ReasonsForUnsubscriptionSurvey"], "", $_QLoli);
      return $_QLoli;
    }

    $_QLJfI = join("", file(_LOC8P()."reasonsforunsubscription_template.htm"));
    if(empty($_QLJfI)){
      $_QLoli = str_ireplace($_JQofl["ReasonsForUnsubscriptionSurvey"], "", $_QLoli);
      return $_QLoli;
    }

    $_I1OfI = _L81DB($_QLJfI, "<REASONSFORUNSUBSCRIPTION_ROW>", "</REASONSFORUNSUBSCRIPTION_ROW>");
    $_fo0L0 = "";
    $_QLfol = "SELECT * FROM `$_Jj08l[ReasonsForUnsubscripeTableName]` WHERE `forms_id`=$_Jj08l[FormId] ORDER BY `sort_order`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
      $_Ql0fO = $_I1OfI;

      $_Iljoj = "";
      if($_QLO0f["ReasonType"] == "Text"){
        $_Iljoj = ' document.getElementById(\'ReasonText_'.$_QLO0f["id"].'\').focus();';
      }

      $_Ql0fO = _L81BJ($_Ql0fO, "<REASONSFORUNSUBSCRIPTION_COL1>", "</REASONSFORUNSUBSCRIPTION_COL1>", '<input type="radio" name="ReasonsForUnsubscripe_id" value="'.$_QLO0f["id"].'" id="REASONSFORUNSUBSCRIPTION_'.$_QLO0f["id"].'" onclick="EnableDisableReasonsForUnsubscripeControls(this);'.$_Iljoj.'" />');

      $_Ql0fO = _L81BJ($_Ql0fO, "<REASONSFORUNSUBSCRIPTION_COL2>", "</REASONSFORUNSUBSCRIPTION_COL2>", '<label for="REASONSFORUNSUBSCRIPTION_'.$_QLO0f["id"].'">'.$_QLO0f["Reason"].'</label>');

      if($_QLO0f["ReasonType"] == "Text"){
        $_Ql0fO .= $_I1OfI;
        $_Ql0fO = _L81BJ($_Ql0fO, "<REASONSFORUNSUBSCRIPTION_COL1>", "</REASONSFORUNSUBSCRIPTION_COL1>", '');

        $_Ql0fO = _L81BJ($_Ql0fO, "<REASONSFORUNSUBSCRIPTION_COL2>", "</REASONSFORUNSUBSCRIPTION_COL2>", '<textarea maxlength="65535" class="reasonsforunsubscription_textarea" cols="60" rows="10" name="ReasonText" id="ReasonText_' . $_QLO0f["id"] . '" onchange="EnableDisableReasonsForUnsubscripeControls(null);" oninput="EnableDisableReasonsForUnsubscripeControls(null);" onpropertychange="EnableDisableReasonsForUnsubscripeControls(null);"></textarea>');

      }

      $_fo0L0 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_fo0L0 = _L81BJ($_QLJfI, "<REASONSFORUNSUBSCRIPTION_ROW>", "</REASONSFORUNSUBSCRIPTION_ROW>", $_fo0L0);

    $_QLoli = str_ireplace($_JQofl["ReasonsForUnsubscriptionSurvey"], $_fo0L0, $_QLoli);

    $_QLoli = str_replace('name="MailingListId"', 'name="MailingListId" value="' . $_Jj08l["MailingListId"] . '"', $_QLoli);
    $_QLoli = str_replace('name="FormId"', 'name="FormId" value="' . $_Jj08l["FormId"] . '"', $_QLoli);

    $_fo160="";
    if(is_array($_j11Io)){
      foreach($_j11Io as $key => $_QltJO){
         if(strpos($key, "u_") === false) continue;
         if($key == "u_Birthday" && $_QltJO == "0000-00-00") continue;
         if($key == "u_Gender" && $_QltJO == "undefined") continue;
         if($key == "u_Comments") continue;
         if(empty($_QltJO)) continue;
         $_fo160 .= sprintf('<input type="hidden" name="%s" value="%s" />', $key, $_QltJO);
      }
    }
    $_QLoli = str_replace('<!--MEMBERSRECORD/-->', $_fo160, $_QLoli);

    return $_QLoli;
  }

  function _J06JO($_fo1fJ, $_I8I6o, $_fo18J, $_fo1CC, $_Jj08l, $errors) {
    global $_jfQtI, $_QLo06, $_QLttI;
    global $_J1tCf, $_J1OIO, $_J1oCI, $_J1Clo;

    $_foQ6I = $_QLo06;
    $_j1IIf = $_Jj08l["OverrideSubUnsubURL"];
    $_QLfol = "SELECT `RedirectURL`, `HTMLPage`, `ForceRedirect` FROM `$_jfQtI` WHERE `id`=$_Jj08l[$_fo18J]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    if($_QLO0f["RedirectURL"] != "") {

      $_foQtj = $_QLO0f["RedirectURL"];
      $_foIIf = "";
      if(is_array($errors) && count($errors) > 0){
        $_foIIf = "ERRORPAGEMESSAGE=".urlencode( join("", $errors) );
      }
      if(isset($_Jj08l[$_fo1CC]) && $_Jj08l[$_fo1CC] != "")
         $_foIIf == "" ? $_foIIf = "PAGEMESSAGE=".urlencode( $_Jj08l[$_fo1CC] ) : $_foIIf .= "&". "PAGEMESSAGE=".urlencode( $_Jj08l[$_fo1CC] );
      if($_foIIf != "") {
        if(strpos($_foQtj, "?") === false)
           $_foQtj .= "?".$_foIIf;
           else
           $_foQtj .= "&".$_foIIf;
      }

      if( !ini_get("allow_url_fopen") || defined("ForcePageRedirect") || $_QLO0f["ForceRedirect"]>0 ) {
        header("Location: $_foQtj");
        print 'Die Seite befindet sich jetzt hier <a href="'.$_foQtj.'">'.$_QLO0f["RedirectURL"].'</a><br /><br />';
        print 'The page has changed to <a href="'.$_foQtj.'">'.$_QLO0f["RedirectURL"].'</a>';
        exit;
      } else {
        $_QLoli = join("", file($_QLO0f["RedirectURL"]));
        if($_QLoli == "") {
          header("Location: $_foQtj");
          print 'Die Seite befindet sich jetzt hier <a href="'.$_foQtj.'">'.$_QLO0f["RedirectURL"].'</a><br /><br />';
          print 'The page has changed to <a href="'.$_foQtj.'">'.$_QLO0f["RedirectURL"].'</a>';
          exit;
        }

        $_foQ6I = GetHTMLCharSet($_QLoli);

        if($_foQ6I != $_QLo06) {
          $_QLoli = ConvertString($_foQ6I, $_QLo06, $_QLoli, true);
          $_QLoli = SetHTMLCharSet($_QLoli, $_QLo06, strpos($_QLoli, "/>") !== false);
          $_foQ6I = $_QLo06;
        }

        // change inline files
        $_JiI11 = array();
        GetInlineFiles($_QLoli, $_JiI11, true);
        $url = substr($_QLO0f["RedirectURL"], 0, strpos_reverse($_QLO0f["RedirectURL"], '/', -1) + 1);

        for($_Qli6J=0; $_Qli6J<count($_JiI11); $_Qli6J++) {
          $_QLoli = str_replace('"'.$_JiI11[$_Qli6J], '"'.$url.$_JiI11[$_Qli6J], $_QLoli);
          $_QLoli = str_replace("'".$_JiI11[$_Qli6J], "'".$url.$_JiI11[$_Qli6J], $_QLoli);
        }

        // change links
        $_IjQI8 = array();
        $_66Co0 = array();

        // href
        preg_match_all('/[ \r\n]href\=([\"\']*)(.*?)\1[\s\/>]/is', $_QLoli, $_66tJo, PREG_SET_ORDER);
        for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
          if( !preg_match("/^http:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/^https:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/^javascript:/i", $_66tJo[$_Qli6J][2]) && !preg_match("/^chrome:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/^mailto:/i", $_66tJo[$_Qli6J][2]) )
               $_66Co0[] = $_66tJo[$_Qli6J][2];
        }

        // only unique links
        $_I016j = array_unique($_66Co0);
        reset($_I016j);
        foreach ($_I016j as $key => $_QltJO)
          $_IjQI8[] = $_QltJO;

        for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++) {
          if($_IjQI8[$_Qli6J] == "/") continue; // ignore this links
          $_QLoli = str_replace('href="'.$_IjQI8[$_Qli6J], 'href="'.$url.$_IjQI8[$_Qli6J], $_QLoli);
        }

      }
    } else {

       $_QLoli = $_QLO0f["HTMLPage"];

       // javascript:history.back();
       if( (defined('DefaultNewsletterPHP') ) && strpos($_QLoli, "javascript:history.back();") !== false) {
         reset($_POST);
         $_foIJl = "";
         foreach($_POST As $key => $_QltJO) {
           if($key == "Action" || $key == "g-recaptcha-response") continue;
           if($_QltJO == "") continue;
           if($key == "PrivacyPolicyAccepted" || $key == "u_PersonalizedTracking") continue;
             if(is_array($_QltJO)){
               $_QltJO = implode(",", $_QltJO);
               if($_foIJl == "")
                  $_foIJl = $key."=".($_QltJO);
                  else
                  $_foIJl .= "&".$key."=".($_QltJO);
               continue;
             }
             if($_foIJl == "")
                $_foIJl = $key."=".rawurlencode($_QltJO);
                else
                $_foIJl .= "&".$key."=".rawurlencode($_QltJO);
         }
         if($_foIJl != "")
            if( defined('DefaultNewsletterPHP') )
              $_QLoli = str_replace("javascript:history.back();", (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?".$_foIJl, $_QLoli);
             /* else it's not possible
              $_QLoli = str_replace("javascript:history.back();", $_J1Cf8."?".$_foIJl, $_QLoli); */

       }
    }

    if(is_array($errors) && count($errors) ){
      $_QLoli = _L81BJ($_QLoli, "[ERRORPAGEMESSAGE]", "[ERRORPAGEMESSAGE]", join("", $errors)."<br />");
      $_QLoli = str_replace("</body", "<!-- ERROR: ".join("", $errors)." //-->"."</body", $_QLoli);
      if(isset($_Jj08l["Action"]) && (strpos($_Jj08l["Action"], "reject") !== false || strpos($_Jj08l["Action"], "confirm") !== false) ){
        $_QLoli = str_replace("</body", "<script>if(document.getElementById('gobacklink'))document.getElementById(\"gobacklink\").style.display=\"none\";</script>"."</body", $_QLoli);
        $_QLoli = _L80DF($_QLoli, "<BackLink>", "</BackLink>");
      }
    }

    if(isset($_Jj08l[$_fo1CC])) {
      $_QLoli = _L81BJ($_QLoli, "[PAGEMESSAGE]", "[PAGEMESSAGE]", $_Jj08l[$_fo1CC]);
      $_QLoli = str_replace("</body", "<!-- MESSAGETEXT: ".$_Jj08l[$_fo1CC]." //-->"."</body", $_QLoli);
    }

    _JJCCF($_QLoli);

    if(!is_array($_fo1fJ) && intval($_fo1fJ) != 0) {
      $_QLfol = "SELECT * FROM `$_I8I6o` WHERE id=".intval($_fo1fJ);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
        $_JCl0i = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
      }
       else
         $_JCl0i = array();
      $_QLoli = _J1EBE($_JCl0i, $_Jj08l["MailingListId"], $_QLoli, $_foQ6I, true, array());
      $_fo1fJ = $_JCl0i;
    } elseif (is_array($_fo1fJ)) {
      $_QLoli = _J1EBE($_fo1fJ, $_Jj08l["MailingListId"], $_QLoli, $_foQ6I, true, array());
    }

    // UnsubscribeBridgePage
    if($_fo18J == "UnsubscribeBridgePage"){
       $_QLoli = str_ireplace("<form", '<form method="post" action="'.(!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo).'" ', $_QLoli);
       $_I6tLJ = array_merge($_POST, $_GET);
       $_I6tLJ["Action"] = "unsubscribe";
       $_I6tLJ["unsubscribeconfirmation"] = "unsubscribeconfirmation";
       reset($_I6tLJ);
       $_IiCfO = "";
       foreach($_I6tLJ as $key => $_QltJO){
         $_IiCfO .= sprintf('<input type="hidden" name="%s" value="%s" />', htmlentities($key), htmlentities($_QltJO));
       }
       $_QLoli = str_ireplace("</form", $_IiCfO."</form", $_QLoli);
    }

    if($_fo18J == "UnsubscribeConfirmationPage" && !defined('ListUnsubscribePOST') && is_array($_fo1fJ) && count($_fo1fJ) ){
       $_QLoli = _J06OB($_Jj08l, $_QLoli, $_fo1fJ);
    }

    SetHTMLHeaders($_QLo06, false);

    print $_QLoli;
    exit;
  }

  function _J0R06($_IfLJj, $_I80Jo, $_JCl0i, $_foIif, $_IflO6, $_fOlQJ = ""){
    global $AppName, $_QLttI;

    $_6t1tj = "";
    if($_foIif == "subscribe")
       $_6t1tj = $_IflO6["ExternalSubscriptionScript"];
    if($_foIif == "unsubscribe")
       $_6t1tj = $_IflO6["ExternalUnsubscriptionScript"];
    if($_foIif == "edit" || $_foIif == "editconfirm")
       $_6t1tj = $_IflO6["ExternalEditScript"];
    if($_foIif == "ReasonForUnsubscriptionVote")
       $_6t1tj = $_IflO6["ExternalReasonForUnsubscriptionScript"];
    if($_6t1tj == "") return;


    $_JJl1I = 0;
    $_J600J = "";
    $_J608j = 80;
    if(strpos($_6t1tj, "http://") !== false) {
       $_J60tC = substr($_6t1tj, 7);
    } elseif(strpos($_6t1tj, "https://") !== false) {
      $_J608j = 443;
      $_J60tC = substr($_6t1tj, 8);
    }
    $_IJL6o = substr($_J60tC, strpos($_J60tC, "/"));
    $_J60tC = substr($_J60tC, 0, strpos($_J60tC, "/"));

   if(count($_JCl0i) == 0 && $_IfLJj != 0) {
      $_QLfol = "SELECT * FROM `$_I80Jo[MaillistTableName]` WHERE id=".intval($_IfLJj);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_JCl0i = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
   }

   if($_foIif == "ReasonForUnsubscriptionVote"){
     $_I0QjQ = "AppName=$AppName";
     foreach($_JCl0i as $key => $_QltJO)
       $_I0QjQ .= "&$key=".rawurlencode($_QltJO);
   }
   else
   if( ($_foIif == "edit" || $_foIif == "editconfirm") && $_fOlQJ != "")
      $_I0QjQ = "AppName=$AppName&EMail=$_fOlQJ&Type=$_foIif&NewEMail=$_JCl0i[u_EMail]";
      else
      $_I0QjQ = "AppName=$AppName&EMail=$_JCl0i[u_EMail]&Type=$_foIif";

    _LABJA($_J60tC, "GET", $_IJL6o, $_I0QjQ, 0, $_J608j, false, "", "", $_JJl1I, $_J600J);

  }

?>

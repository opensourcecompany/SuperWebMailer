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

  include_once("mail.php");
  include_once("mailer.php");
  include_once("maillogger.php");
  include_once("replacements.inc.php");
  include_once("recipients_ops.inc.php");

  function _L0OPJ($_Qi8If, $_j6ioL, $HTMLForm, $_6ItCi = false) {
    global $_Qofjo, $_Q6t6j, $resourcestrings, $INTERFACE_LANGUAGE, $_Q6JJJ, $_Q61I1;

    if(is_array($_j6ioL["fields"]))
      $_I16jJ = $_j6ioL["fields"];
      else
      if($_j6ioL["fields"] != "") {
           $_I16jJ = @unserialize($_j6ioL["fields"]);
           if($_I16jJ === false)
             $_I16jJ["u_EMail"] = "visiblerequired";
         }
         else
         $_I16jJ["u_EMail"] = "visiblerequired";

    $_QJlJ0 = "SELECT `text`, `fieldname` FROM `$_Qofjo` WHERE `language`="._OPQLR($INTERFACE_LANGUAGE);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    if($HTMLForm == "editform") {
      if(!isset($_j6ioL) || empty($_j6ioL["UserDefinedFormsFolder"]))
        $_QJCJi = join("", file(_O68QF()."default_edit_page.htm"));
        else
        $_QJCJi = join("", file(_OBLDR(InstallPath.$_j6ioL["UserDefinedFormsFolder"])."default_edit_page.htm"));

      if(isset($_Qi8If["Action"]))
        unset($_Qi8If["Action"]);
      }
    else {
      if(!isset($_j6ioL) || empty($_j6ioL["UserDefinedFormsFolder"]))
        $_QJCJi = join("", file(_O68QF()."default_subscribeunsubscribe_page.htm"));
        else
        $_QJCJi = join("", file( _OBLDR(InstallPath.$_j6ioL["UserDefinedFormsFolder"])."default_subscribeunsubscribe_page.htm"));
    }
    $_I1OLj = _OP81D($_QJCJi, '<TABLE:ROW>', '</TABLE:ROW>');

    // Gender block
    $_6ItL0 = _OP81D($_QJCJi, '<TABLE:GENDER>', '</TABLE:GENDER>');
    $_QJCJi = _OP6PQ($_QJCJi, '<TABLE:GENDER>', '</TABLE:GENDER>');

    // Title block
    $_6IOjj = _OP81D($_QJCJi, '<TABLE:TITLE>', '</TABLE:TITLE>');
    $_QJCJi = _OP6PQ($_QJCJi, '<TABLE:TITLE>', '</TABLE:TITLE>');

    // New email address block
    $_6IOOJ = _OP81D($_QJCJi, '<TABLE:ROW_NEW_EMAILADDRESS>', '</TABLE:ROW_NEW_EMAILADDRESS>');

    // captcha block
    if(!$_j6ioL["UseCaptcha"] && !$_j6ioL["UseReCaptcha"]) {
      $_QJCJi = _OP6PQ($_QJCJi, "<TABLE:CAPTCHA>", "</TABLE:CAPTCHA>");
    } else {

      $_QJCJi = str_replace("<TABLE:CAPTCHA>", "", $_QJCJi);
      $_QJCJi = str_replace("</TABLE:CAPTCHA>", "", $_QJCJi);

      if( $_j6ioL["UseCaptcha"] ) {

        $_QJCJi = _OPR6L($_QJCJi, "<CAPTCHA:TEXT>", "</CAPTCHA:TEXT>", $resourcestrings[$INTERFACE_LANGUAGE]["DefaultCaptchaText"]);
        if(!$_6ItCi) // executable form?
          $_QJCJi = str_replace('image.php?"', 'image.php?'.md5(uniqid(rand(), true)).'"', $_QJCJi);
          else
          $_QJCJi = str_replace('image.php?"', 'image.php?'."<?php echo md5(uniqid(rand(), true)) ?>".'"', $_QJCJi);
      }

      if( $_j6ioL["UseReCaptcha"] ) {
        $_QJCJi = _OPR6L($_QJCJi, "<CAPTCHA:TEXT>", "</CAPTCHA:TEXT>", $resourcestrings[$INTERFACE_LANGUAGE]["DefaultReCaptchaText"]);
       /* recaptchav1 if(!$_6ItCi) // executable form?
          $_QJCJi = _OPR6L($_QJCJi, "<CAPTCHA:INTERNAL>", "</CAPTCHA:INTERNAL>", recaptcha_get_html($_j6ioL["PublicReCaptchaKey"]));
          else */
          $_QJCJi = _OPR6L($_QJCJi, "<CAPTCHA:INTERNAL>", "</CAPTCHA:INTERNAL>", '<div class="g-recaptcha" data-sitekey="'.$_j6ioL["PublicReCaptchaKey"].'"></div>');
          if(strpos($_QJCJi, "recaptcha/api.js") === false) {
            $_QJCJi = str_replace("</form>", '<script src="https://www.google.com/recaptcha/api.js"></script>'.$_Q6JJJ."</form>", $_QJCJi);
          }
      }

    }

    // Bool block
    $_6IOOL = _OP81D($_QJCJi, '<TABLE:USERBOOL>', '</TABLE:USERBOOL>');
    $_QJCJi = _OP6PQ($_QJCJi, '<TABLE:USERBOOL>', '</TABLE:USERBOOL>');

    // only email address
    if($HTMLForm == "unsubform"){
      $_I16jJ = array();
      $_I16jJ["u_EMail"] = "visiblerequired";
    }

    $_Q6ICj = "";
    $_6IOiC = isset($_I16jJ["u_EMailFormat"]) && $_I16jJ["u_EMailFormat"] != "invisible";
    $_6IoCJ = isset($_I16jJ["u_EMailFormat"]) && $_I16jJ["u_EMailFormat"] == "visiblerequired";
    $_6IC01 = isset($_I16jJ["u_Gender"]) && $_I16jJ["u_Gender"] != "invisible";
    $_6IC6l = isset($_I16jJ["u_Salutation"]) && $_I16jJ["u_Salutation"] != "invisible";

    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      if( isset($_I16jJ[$_Q6Q1C["fieldname"]]) && $_I16jJ[$_Q6Q1C["fieldname"]] != "invisible" ) {
          $_Q66jQ = $_I1OLj;
          if($_I16jJ[$_Q6Q1C["fieldname"]] == "visiblerequired")
             $_Q66jQ = str_replace("<!--FIELDNAME//-->", '<label for="'.$_Q6Q1C["fieldname"].'">'.$_Q6Q1C["text"].'</label>' . "&nbsp;*", $_Q66jQ);
             else
             $_Q66jQ = str_replace("<!--FIELDNAME//-->", '<label for="'.$_Q6Q1C["fieldname"].'">'.$_Q6Q1C["text"].'</label>', $_Q66jQ);
          if($_Q6Q1C["fieldname"] != "u_EMailFormat" && $_Q6Q1C["fieldname"] != "u_Gender" && $_Q6Q1C["fieldname"] != "u_Salutation" && strpos($_Q6Q1C["fieldname"], "u_User") === false ) {
             $_I00tC = 50;
             $_6ICtI = 255;
             if($_Q6Q1C["fieldname"] == "u_Birthday") {
                $_I00tC= 10;
                $_6ICtI = 10;
             }
             $_Q66jQ = str_replace("<!--INPUTFIELD//-->", '<input type="text" name="'.$_Q6Q1C["fieldname"].'" size="'.$_I00tC.'" maxlength="'.$_6ICtI.'" id="' . $_Q6Q1C["fieldname"] . '" />', $_Q66jQ);
             }
             elseif($_Q6Q1C["fieldname"] == "u_Gender") {
                $_Q66jQ = $_6ItL0;
             }
             elseif($_Q6Q1C["fieldname"] == "u_Salutation") {
                $_Q66jQ = $_6IOjj;
                if($_I16jJ[$_Q6Q1C["fieldname"]] != "visiblerequired")
                  $_Q66jQ = str_replace("*", "", $_Q66jQ);
             }
             elseif(! (strpos($_Q6Q1C["fieldname"], "u_User") === false) ) {
               if( strpos($_Q6Q1C["fieldname"], "u_UserFieldBool") === false ) {
                  if( strpos($_Q6Q1C["fieldname"], "u_UserFieldInt") === false )
                     $_Q66jQ = str_replace("<!--INPUTFIELD//-->", '<input type="text" name="'.$_Q6Q1C["fieldname"].'" size="50" id="' . $_Q6Q1C["fieldname"] . '" />', $_Q66jQ);
                     else
                     $_Q66jQ = str_replace("<!--INPUTFIELD//-->", '<input type="text" name="'.$_Q6Q1C["fieldname"].'" size="5" style="text-align: right" id="' . $_Q6Q1C["fieldname"] . '" />', $_Q66jQ);
               } else {
                  $_Q66jQ = $_6IOOL;
                  $_Q66jQ = str_replace('"u_UserFieldBool"', '"'.$_Q6Q1C["fieldname"].'"', $_Q66jQ);
                  $_Q66jQ = _OPR6L($_Q66jQ, '<DESCRIPTION>', '</DESCRIPTION>', $_Q6Q1C["text"]);
                  if($_I16jJ[$_Q6Q1C["fieldname"]] != "visiblerequired")
                     $_Q66jQ = str_replace("*", "", $_Q66jQ);
               }

             }
             else {
               continue;
             }
          $_Q6ICj .= $_Q66jQ;
      }
    }
    mysql_free_result($_Q60l1);
    if($_6IOiC) {
      $_QJCJi = str_replace("<TABLE:EMAILFORMAT>", "", $_QJCJi);
      $_QJCJi = str_replace("</TABLE:EMAILFORMAT>", "", $_QJCJi);
      // ever $_6IoCJ
    } else {
      $_QJCJi = _OP6PQ($_QJCJi, '<TABLE:EMAILFORMAT>', '</TABLE:EMAILFORMAT>');
    }

    if($_6IC01 && !isset($_Qi8If["u_Gender"]))
       $_Qi8If["u_Gender"] = "m";

    // ********* fields end

    $_QJCJi = _OPR6L($_QJCJi, '<TABLE:ROW>', '</TABLE:ROW>', $_Q6ICj);
    $_QJCJi = _OPR6L($_QJCJi, '<TABLE:ROW_NEW_EMAILADDRESS>', '</TABLE:ROW_NEW_EMAILADDRESS>', $_6IOOJ);

    if($_j6ioL["GroupsOption"] == 2) {
      if(!$_j6ioL["GroupsAsCheckBoxes"])
        $_6ICl8 = _OP81D($_QJCJi, '<TABLE:GROUPS>', '</TABLE:GROUPS>');
        else
        $_6ICl8 = _OP81D($_QJCJi, '<TABLE:GROUPSCHECKBOXES>', '</TABLE:GROUPSCHECKBOXES>');

      // ********* List of Groups SQL query
      $_Q68ff = "SELECT DISTINCT `id`, `Name` FROM `$_Q6t6j`";
      $_Q68ff .= " ORDER BY `Name` ASC";
      $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
      $_I10Cl = "";
      if(!isset($_Qi8If["RecipientGroups"]) ) {
        $_6IiiC = ' selected="selected"';
        $_I16L6 = ' checked="checked"';
      } else {
        $_6IiiC = "";
        $_I16L6 = "";
      }
      $_Q6llo=0;
      while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
        if(!$_j6ioL["GroupsAsCheckBoxes"])
           $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'"'.$_6IiiC.'>'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
           else
           $_I10Cl .= '<input type="checkbox" name="RecipientGroups['/*.$_Q6llo*/.']" value="'.$_Q6Q1C["id"].'" '.$_I16L6.' />&nbsp;'.$_Q6Q1C["Name"]."<br />".$_Q6JJJ;
        $_6IiiC = "";
        $_I16L6 = "";
        $_Q6llo++;
      }
      mysql_free_result($_Q60l1);
      // ********* List of Groups query END
      if(!$_j6ioL["GroupsAsCheckBoxes"])
         $_6ICl8 = _OPR6L($_6ICl8, "<option>", "</option>", $_I10Cl);
         else
         $_6ICl8 = _OPR6L($_6ICl8, "<checkbox>", "</checkbox>", $_I10Cl);

      if(!$_j6ioL["GroupsAsCheckBoxes"])
        $_QJCJi = _OPR6L($_QJCJi, '<TABLE:GROUPS>', '</TABLE:GROUPS>', $_6ICl8);
        else
        $_QJCJi = _OPR6L($_QJCJi, '<TABLE:GROUPSCHECKBOXES>', '</TABLE:GROUPSCHECKBOXES>', $_6ICl8);

    }
    $_QJCJi = _OP6PQ($_QJCJi, '<TABLE:GROUPS>', '</TABLE:GROUPS>');
    $_QJCJi = _OP6PQ($_QJCJi, '<TABLE:GROUPSCHECKBOXES>', '</TABLE:GROUPSCHECKBOXES>');

    if($HTMLForm == "unsubform" || $HTMLForm == "subform"){
      if($HTMLForm == "unsubform") {
          $_6ILtC = '<input type="hidden" name="Action" value="unsubscribe" />';
          $_QJCJi = str_replace('value="SUBMITBTNTEXT"', 'value="'.$resourcestrings[$INTERFACE_LANGUAGE]["UNSUBSCRIBEBtnText"].'"', $_QJCJi);
         }
         else {
           $_6ILtC = '<input type="hidden" name="Action" value="subscribe" />';
           $_QJCJi = str_replace('value="SUBMITBTNTEXT"', 'value="'.$resourcestrings[$INTERFACE_LANGUAGE]["SUBSCRIBEBtnText"].'"', $_QJCJi);
         }
      $_QJCJi = _OPR6L($_QJCJi, "<TABLE:SUBUNSUB>", "</TABLE:SUBUNSUB>", $_6ILtC);
    } else{
      $_QJCJi = str_replace("<TABLE:SUBUNSUB>", "", $_QJCJi);
      $_QJCJi = str_replace("</TABLE:SUBUNSUB>", "", $_QJCJi);
      $_QJCJi = str_replace('value="SUBMITBTNTEXT"', 'value="'.$resourcestrings[$INTERFACE_LANGUAGE]["SUBMITBtnText"].'"', $_QJCJi);
    }

    return $_QJCJi;
  }

  function _L0OEO(&$_QLitI, $_Ql00j, $_I1l66, $_QLl1Q, $Action, &$errors, &$_Ql1O8) {
    global $_Q61I1, $_Q60QL;

    // Boolean fields of form
    $_I01C0 = Array ();
    $_I01lt = Array ();

    $_QlQC8 = $_Ql00j["MaillistTableName"];
    $_QlIf6 = $_Ql00j["StatisticsTableName"];
    $_Q6t6j = $_Ql00j["GroupsTableName"];
    $_QLI68 = $_Ql00j["MailListToGroupsTableName"];
    $_Qljli = $_Ql00j["EditTableName"];

    $_QLLjo = array();

    _OA60P($_QlQC8, $_QLLjo);

    // Mit Platzhalter <ÿMaillistTableNameÿ> weil er es mehrfach nutzen muss/koennte
    if ($_QLitI == 0)  {
      $_QJlJ0 = "INSERT IGNORE INTO <ÿMaillistTableNameÿ> SET DateOfSubscription=NOW()";

      if($_Ql00j["SubscriptionType"] == 'SingleOptIn') {
         $_QJlJ0 .= ", IPOnSubscription='Single Opt In', SubscriptionStatus='Subscribed', DateOfOptInConfirmation=NOW(), ";
      } else {
         $_QJlJ0 .= ", IPOnSubscription='".getOwnIP()."', SubscriptionStatus='OptInConfirmationPending', ";
      }
    } else { // recipient exists
      if($Action == "subscribeconfirm")
        $_QJlJ0 = "UPDATE <ÿMaillistTableNameÿ> SET IsActive=1, DateOfOptInConfirmation=NOW(), IPOnSubscription='".getOwnIP()."',  SubscriptionStatus='Subscribed' WHERE id=".intval($_QLitI);
        else
        $_QJlJ0 = "UPDATE <ÿMaillistTableNameÿ> SET ";
    }

    if(isset($_I1l66["DuplicateEntry"]))
       $_QJlJ0 = "";

    $_6Ilt6 = false;
    if($Action == "edit") {
     if( isset($_I1l66["NewEMail"]) )
       $NewEMail = $_I1l66["NewEMail"];
     if( isset($_I1l66["Newu_EMail"]) )
       $NewEMail = $_I1l66["Newu_EMail"];

     // set new email address
     if(isset($NewEMail)) {
       # on double opt in only
       if($_Ql00j["SubscriptionType"] != 'SingleOptIn')
         $_6Ilt6 = strtolower($_I1l66["u_EMail"]) <> strtolower($NewEMail);

       if(!$_6Ilt6)
         $_I1l66["u_EMail"] = $NewEMail;
         else
         $_I1l66["NewEMail"] = $NewEMail; // for later use
     }
    }

    if($Action == "editconfirm" && !$_6Ilt6){
      $_Q8otJ = @unserialize(base64_decode($_I1l66["ChangedDetails"]));
      unset( $_I1l66["ChangedDetails"] );
      if($_Q8otJ !== false)
        $_I1l66 = array_merge($_Q8otJ, $_I1l66);
      $Action = "edit";
      if( isset($_I1l66["NewEMail"]) )
        $NewEMail = $_I1l66["NewEMail"];
      if(isset($NewEMail)) {
          $_I1l66["u_EMail"] = $NewEMail;
      }
      // remove entry from EditTable
      $_6j0f8 = "DELETE FROM `$_I1l66[EditTableName]` WHERE `Member_id`=".intval($_I1l66["RecipientId"]);
      mysql_query($_6j0f8, $_Q61I1);
    }

    if ( ($_QLitI == 0 || ($Action == "edit" && !$_6Ilt6)) && !isset($_I1l66["DuplicateEntry"]) ) {
      $_I1l61 = array();
      reset($_I1l66);
      foreach($_I1l66 as $key => $_Q6ClO) {
        // ignore none database fields
        if(!isset($_QLLjo[$key]) ) continue;

        if( $key == "u_Birthday" && isset($_I1l66[$key]) && $_I1l66[$key] != "" ) {
            $_6I6jO = ".";
            if( strpos($_I1l66[$key], ".") === false ) {
              if( strpos($_I1l66[$key], "-") === false ) {
                if( strpos($_I1l66[$key], "/") === false ) {
                } else $_6I6jO = "/";
              }
              else $_6I6jO = "-";
            }

            $_Q8otJ = explode($_6I6jO, $_I1l66[$key]);

            if(strlen($_Q8otJ[0]) == 2) {
             $_I1l66[$key] = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
            } else {
             $_I1l66[$key] = $_Q8otJ[0]."-".$_Q8otJ[1]."-".$_Q8otJ[2];
            }
        } else {
          if( $key == "u_Birthday" && isset($_I1l66[$key]) && $_I1l66[$key] != "" )
            unset($_I1l66[$key]);
        }

        // remove Salutation if empty
        if( $key == "u_Salutation" && isset($_I1l66[$key]) && !(strpos($_I1l66[$key], "--") === false) ) {
          unset($_I1l66[$key]);
        }

        if ( isset($_I1l66[$key]) ) {
          if(in_array($key, $_I01C0))
            if( $_I1l66[$key] == "1" || intval($_I1l66[$key]) == 0 )
               $_I1l61[] = "$key=1";
               else
                ;
          else {
             if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){ // XSS protection
               $_I1l61[] = "$key="._OPQLR(rtrim( htmlspecialchars($_I1l66[$key], ENT_COMPAT, 'UTF-8')) );
             } else{
               $_I1l61[] = "$key="._OPQLR(rtrim($_I1l66[$key]) );
             }
          }
        } else {
           if(in_array($key, $_I01C0)) {
             $key = $_QLLjo[$key];
             $_I1l61[] = "$key=0";
           } else {
             if(in_array($key, $_I01lt)) {
               $key = $_QLLjo[$key];
               $_I1l61[] = "$key=0";
             }
           }
        }
      }

      $_QJlJ0 .= join(", ", $_I1l61);
    }

    // edit entry
    if($Action == "edit" && !$_6Ilt6) {
     $_QJlJ0 .= " WHERE id=".intval($_QLitI);
    }

    if($Action == "edit" && $_6Ilt6) {
      $_QJlJ0 = "";
    }

    if($_QJlJ0 != "") {
      $_6j0ti = $_QJlJ0;
      $_QJlJ0 = str_replace('<ÿMaillistTableNameÿ>', $_QlQC8, $_QJlJ0);

      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

      if(mysql_error($_Q61I1) != "") {
        $errors[] = mysql_error($_Q61I1);
        $_Ql1O8[] = mysql_error($_Q61I1)." ".$_QJlJ0;
        return 0;
      }

      $_jfiOj = "";
      $_6j0oJ = "";
      if($_QLitI == 0) {
        $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
        $_Q6Q1C=mysql_fetch_array($_Q60l1);
        $_QLitI = $_Q6Q1C[0];
        mysql_free_result($_Q60l1);

        if($_Ql00j["SubscriptionType"] == 'SingleOptIn' )
          $_jfiOj = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action='Subscribed', Member_id=".intval($_QLitI);
          else
          $_jfiOj = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action='OptInConfirmationPending', Member_id=".intval($_QLitI);
      } else {
          if($Action == "edit")
            $_jfiOj = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action='Edited', Member_id=".intval($_QLitI);
          if($Action == "subscribeconfirm") {
            $_jfiOj = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action='Subscribed', Member_id=".intval($_QLitI);
            $_6j0oJ = "DELETE FROM $_QlIf6 WHERE Member_id=".intval($_QLitI)." AND Action='OptInConfirmationPending'";
          }
      }

      if($_jfiOj != "")
        $_Q60l1 = mysql_query($_jfiOj, $_Q61I1);
      if($_6j0oJ != "")
        $_Q60l1 = mysql_query($_6j0oJ, $_Q61I1);

    } else
      if( $Action == "edit" && !$_6Ilt6 && $_I1l66["DuplicateEntry"] )
         $_QLitI = $_I1l66["DuplicateEntry"];

    if($Action == "edit" && $_6Ilt6) {
      reset($_I1l66);
      $_6j18Q = array();
      foreach($_I1l66 as $key => $_Q6ClO){
        $_Q6i6i = strpos($key, "u_");
        if( ($_Q6i6i !== false && $_Q6i6i == 0) or ($_Q6i6i === false && ($key == "NewEMail" || $key == "RecipientGroups") ) )
           $_6j18Q[$key] = $_Q6ClO;
      }


      $_QJlJ0 = "SELECT `Member_id` FROM `$_Qljli` WHERE `Member_id`=".intval($_QLitI);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) == 0) {
        $_QJlJ0 = "INSERT INTO `$_Qljli` SET `Member_id`=".intval($_QLitI).", `ChangeDate`=NOW(), `ChangedDetails`="._OPQLR( base64_encode(serialize( $_6j18Q )) );
      } else {
        $_QJlJ0 = "UPDATE `$_Qljli` SET `ChangeDate`=NOW(), `ChangedDetails`="._OPQLR( base64_encode(serialize( $_6j18Q )) )." WHERE `Member_id`=".intval($_QLitI);
      }
      mysql_query($_QJlJ0, $_Q61I1);
      mysql_free_result($_Q60l1);
    } // if($Action == "edit" && $_6Ilt6)

    // ********* Add to groups on subscribe
    if( $Action == "subscribe"  ) {
      // add to all selected groups
      if($_QLl1Q["GroupsOption"] == 2 && isset($_I1l66["RecipientGroups"]) ) {
        $_QJlJ0 = "SELECT id FROM $_Q6t6j";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
          if( array_key_exists ($_Q6Q1C["id"], $_I1l66["RecipientGroups"] ) ) {
            // isset($_I1l66["DuplicateEntry"]) then the existing group references are removed from _OFEOP()
            if(isset($_I1l66["DuplicateEntry"]) && intval($_I1l66["DuplicateEntry"]) > 0 && $_QLitI == 0)
               $_QLitI = intval($_I1l66["DuplicateEntry"]);
            $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_Q6Q1C[id], Member_id=".intval($_QLitI);
            mysql_query($_QJlJ0, $_Q61I1);
          }
        }
      } // if($_QLl1Q["GroupsOption"] == 2 && isset($_I1l66["RecipientGroups"]) )

      // add to specified group
      if($_QLl1Q["GroupsOption"] == 3) {
        $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=".intval($_QLl1Q["groups_id"]).", Member_id=".intval($_QLitI);
        mysql_query($_QJlJ0, $_Q61I1);
      }
    }
    // on Edit
    if( $Action == "edit" && !$_6Ilt6 && $_QLl1Q["GroupsOption"] == 2 && isset($_I1l66["RecipientGroups"])  ) {
      $_POST["OneRecipientId"] = intval($_QLitI);
      $_POST["OneMailingListId"] = intval($_Ql00j["id"]);
      if(isset($_QtIiC))
        unset($_QtIiC);
      $_QtIiC = array();
      _L10PF();
      _L1QPQ(array($_QLitI), $_QLI68, array_keys($_I1l66["RecipientGroups"]));
      unset($_POST["OneRecipientId"]);
      unset($_POST["OneMailingListId"]);
    }
    // on Edit
    if( $Action == "edit" && !$_6Ilt6 && $_QLl1Q["GroupsOption"] == 2 && !isset($_I1l66["RecipientGroups"])  ) {
      $_POST["OneRecipientId"] = intval($_QLitI);
      $_POST["OneMailingListId"] = intval($_Ql00j["id"]);
      if(isset($_QtIiC))
        unset($_QtIiC);
      $_QtIiC = array();
      _L10PF();
      // remove from all groups
      _L1QPQ(array($_QLitI), $_QLI68, array(), false);
      unset($_POST["OneRecipientId"]);
      unset($_POST["OneMailingListId"]);
    }
    // ********* Add to groups END

    if( ( $_Ql00j["SubscriptionType"] == 'SingleOptIn' || $Action == "subscribeconfirm" || ($Action == "edit" && !$_6Ilt6) ) && !isset($_I1l66["DuplicateEntry"])  ) {
       $_jfiOj = "";

      // insert in another mailinglist?
      if($_Ql00j["OnSubscribeAlsoAddToMailList"] > 0) {

        $_Iijl0 = "SELECT MaillistTableName, StatisticsTableName, MailLogTableName FROM $_Q60QL WHERE id=$_Ql00j[OnSubscribeAlsoAddToMailList]";
        $_Q60l1 = mysql_query($_Iijl0, $_Q61I1);
        if($_Q60l1 && mysql_num_rows($_Q60l1) > 0)
          $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
          else
          if (isset($_Q6Q1C))
            unset($_Q6Q1C);
        if($_Q60l1)
          mysql_free_result($_Q60l1);
        if(isset($_Q6Q1C)) {
          $_QJlJ0 = "SELECT * FROM $_QlQC8 WHERE id=".intval($_QLitI);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_jIiQ8 = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);

          if($Action != "edit") {
            $_QJlJ0 = "INSERT IGNORE INTO $_Q6Q1C[MaillistTableName] SET SubscriptionStatus='Subscribed', ";
            $_I1l61 = array();
            reset($_jIiQ8);
            foreach ($_jIiQ8 as $key => $_Q6ClO) {
               if($key == "id" || $key == "SubscriptionStatus" || $key == "IdentString") continue;
               $_I1l61[] = "$key="._OPQLR($_Q6ClO);
            }

            $_QJlJ0 .= join(", ", $_I1l61);
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

            if($_Q60l1 && mysql_affected_rows($_Q61I1) > 0) {
              $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
              $_Q8OiJ=mysql_fetch_row($_Q60l1);
              $_6jQj6 = $_Q8OiJ[0];
              mysql_free_result($_Q60l1);

              $_6jIQO = new _OFBEA();
              $_6jIQO->_OF0FL($_Q6Q1C["MailLogTableName"], $_6jQj6, "SYSTEM: subscribed recipient automatically copied from source mailing list '" . $_Ql00j["Name"] . "'");
              $_6jIQO = null;

              $_jfiOj = "INSERT INTO $_Q6Q1C[StatisticsTableName] SET ActionDate=NOW(), Action='Subscribed', Member_id=$_6jQj6";
            }
          } else {
            $_QJlJ0 = "SELECT id FROM $_Q6Q1C[MaillistTableName] WHERE u_EMail="._OPQLR($_I1l66["u_EMail"]);
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
              $_Q8OiJ=mysql_fetch_row($_Q60l1);
              $_6jQj6 = $_Q8OiJ[0];

              $_QJlJ0 = "UPDATE $_Q6Q1C[MaillistTableName] SET ";
              $_I1l61 = array();
              reset($_jIiQ8);
              foreach ($_jIiQ8 as $key => $_Q6ClO) {
                if($key == "id" || $key == "SubscriptionStatus" || $key == "IdentString") continue;
                 $_I1l61[] = "$key="._OPQLR($_Q6ClO);
              }
              $_QJlJ0 .= join(", ", $_I1l61);
              $_QJlJ0 .= " WHERE id=".$_6jQj6;
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              $_jfiOj = "INSERT INTO $_Q6Q1C[StatisticsTableName] SET ActionDate=NOW(), Action='Edited', Member_id=$_6jQj6";
            }
          }
        }
      } // if($_Ql00j["OnSubscribeAlsoAddToMailList"] > 0)

      // remove from another mailinglist?
      if($_Ql00j["OnSubscribeAlsoRemoveFromMailList"] > 0 && $Action != "edit" ) {
        $_Iijl0 = "SELECT MaillistTableName, StatisticsTableName, MailListToGroupsTableName FROM $_Q60QL WHERE id=$_Ql00j[OnSubscribeAlsoRemoveFromMailList]";
        $_Q60l1 = mysql_query($_Iijl0, $_Q61I1);
        if($_Q60l1 && mysql_num_rows($_Q60l1) > 0)
          $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
          else
          if (isset($_Q6Q1C))
            unset($_Q6Q1C);
        if($_Q60l1)
          mysql_free_result($_Q60l1);
        if(isset($_Q6Q1C)) {

          if(!isset($_I1l66["u_EMail"])) {
            $_QJlJ0 = "SELECT u_EMail FROM $_QlQC8 WHERE id=".intval($_QLitI);
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            $_jIiQ8 = mysql_fetch_assoc($_Q60l1);
            mysql_free_result($_Q60l1);
            $_I1l66["u_EMail"] = $_jIiQ8["u_EMail"];
          }

          $_QJlJ0 = "SELECT id FROM $_Q6Q1C[MaillistTableName] WHERE u_EMail="._OPQLR($_I1l66["u_EMail"]);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

          if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
            $_Q8OiJ=mysql_fetch_assoc($_Q60l1);
            $_6jQj6 = $_Q8OiJ["id"];
            mysql_free_result($_Q60l1);

            // remove it
            $_POST["OneRecipientId"] = $_6jQj6;
            $_POST["OneMailingListId"] = $_Ql00j["OnSubscribeAlsoRemoveFromMailList"];
            if(isset($_QtIiC))
              unset($_QtIiC);
            $_QtIiC = array();
            _L10PF();
            _L10CL(array($_6jQj6), $_QtIiC);
            unset($_POST["OneRecipientId"]);
            unset($_POST["OneMailingListId"]);

            $_jfiOj = "INSERT INTO $_Q6Q1C[StatisticsTableName] SET ActionDate=NOW(), Action='Unsubscribed', Member_id=".intval($_QLitI);
          }
        }
      } // if($_Ql00j["OnSubscribeAlsoRemoveFromMailList"] > 0)

      if($_jfiOj != "")
        mysql_query($_jfiOj, $_Q61I1);

    } // if( ($_Ql00j["SubscriptionType"] == 'SingleOptIn' && ($Action == "edit" && !$_6Ilt6)) || ($Action == "subscribeconfirm")  )

    // create unique key and send opt-in email
    if($_Ql00j["SubscriptionType"] == 'DoubleOptIn' && $Action == "subscribe" && !isset($_I1l66["DuplicateEntry"])  ) {
      // create confirmation IdentString
      $_JIIQj = "";
      $_JIIQj = _OA81R($_JIIQj, intval($_QLitI), intval($_QLl1Q["MailingListId"]), intval($_QLl1Q["FormId"]), $_QlQC8);
      $rid = "";
      if(isset($_I1l66["rid"]))
        $rid = $_I1l66["rid"];
      return _L0RLJ("subscribeconfirm", $_QLitI, $_Ql00j, $_QLl1Q, $errors, $_Ql1O8, $rid);
    } // if($_Ql00j["SubscriptionType"] == 'DoubleOptIn' && $Action == "subscribe" && !isset($_I1l66["DuplicateEntry"])  )


    // create unique key and send opt-in email
    if($_Ql00j["SubscriptionType"] == 'DoubleOptIn' && $Action == "edit" && $_6Ilt6 && !isset($_I1l66["DuplicateEntry"])  ) {
      // create confirmation IdentString
      $_JIIQj = "";
      $_JIIQj = _OA81R($_JIIQj, intval($_QLitI), intval($_QLl1Q["MailingListId"]), intval($_QLl1Q["FormId"]), $_QlQC8);
      $rid = "";
      if(isset($_I1l66["rid"]))
        $rid = $_I1l66["rid"];
      $_6jII8 = $_QLitI;
      $_QLitI = "editconfirm";
      $_Ql00j["NewEMail"] = $_I1l66["NewEMail"]; // save new email address for confirmation
      return _L0RLJ("editconfirm", $_6jII8, $_Ql00j, $_QLl1Q, $errors, $_Ql1O8, $rid);
    } // if($_Ql00j["SubscriptionType"] == 'DoubleOptIn' && $Action == "edit" && $_6Ilt6 && !isset($_I1l66["DuplicateEntry"])  )

    return $_QLitI;
  }

  function _L0LAF($_QLitI, $_Ql00j, $_I1l66, $_QLl1Q, $Action, &$errors, &$_Ql1O8) {
    global $_Q61I1, $_Q60QL, $_Ql8C0;

    $rid = "";
    if(!empty($_I1l66["rid"]))
      $rid = $_I1l66["rid"];

    $_QlQC8 = $_Ql00j["MaillistTableName"];
    $_QlIf6 = $_Ql00j["StatisticsTableName"];
    $_Q6t6j = $_Ql00j["GroupsTableName"];
    $_QLI68 = $_Ql00j["MailListToGroupsTableName"];

    if($_Ql00j["UnsubscriptionType"] == 'SingleOptOut' || $Action == "unsubscribeconfirm" ) {
       // add to another group than we must save the record
       if($_Ql00j["OnUnsubscribeAlsoAddToMailList"] > 0) {
          $_QJlJ0 = "SELECT * FROM $_QlQC8 WHERE id=".intval($_QLitI);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_jIiQ8 = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);
       }
    }

    // SingleOptOut or confirmation
    if($_Ql00j["UnsubscriptionType"] == 'SingleOptOut' || ($Action == "unsubscribeconfirm") ) {

      // handle groups
      if( isset($_I1l66["RecipientGroups"]) ) {
         // remove it
         $_POST["OneRecipientId"] = intval($_QLitI);
         $_POST["OneMailingListId"] = intval($_Ql00j["id"]);
         if(isset($_QtIiC))
           unset($_QtIiC);
         $_QtIiC = array();
         _L10PF();
         $_Jl811 = _L1OR1(array($_QLitI), $_Ql00j["id"], $_QLI68, array_keys($_I1l66["RecipientGroups"]));
         if($_Jl811 > 0 && $rid != "") {
           _OAD1J($rid);
           $rid = "";
         }

         $_QJlJ0 = "SELECT COUNT(*) FROM $_QLI68 WHERE Member_id=".intval($_QLitI);
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_Q6Q1C = mysql_fetch_row($_Q60l1);

         // no references?
         if($_Q6Q1C[0] == 0) {
           // remove it completly
           if(isset($_QtIiC))
             unset($_QtIiC);
           $_QtIiC = array();
           _L10PF();
           _L10CL(array($_QLitI), $_QtIiC);
           if(count($_QtIiC) > 0) {
             $errors = array_merge($errors, $_QtIiC);
             $_Ql1O8 = array_merge($_Ql1O8, $_QtIiC);
             return false;
           }

           if($rid != "") {
             _OAD1J($rid);
             $rid = "";
           }

           if($_Ql00j["AddUnsubscribersToLocalBlacklist"]) {
             $_QJlJ0 = "INSERT IGNORE INTO `$_Ql00j[LocalBlocklistTableName]` SET u_EMail="._OPQLR( $_I1l66["u_EMail"] );
             mysql_query($_QJlJ0, $_Q61I1);
           }
           if($_Ql00j["AddUnsubscribersToGlobalBlacklist"]) {
             $_QJlJ0 = "INSERT IGNORE INTO `$_Ql8C0` SET u_EMail="._OPQLR( $_I1l66["u_EMail"] );
             mysql_query($_QJlJ0, $_Q61I1);
           }

         } else { // if($_Q6Q1C[0] == 0)
            $_QJlJ0 = "UPDATE $_QlQC8 SET SubscriptionStatus ='Subscribed', DateOfUnsubscription=0 WHERE id=".intval($_QLitI);
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         }

         unset($_POST["OneRecipientId"]);
         unset($_POST["OneMailingListId"]);

      } else {

         // remove it
         $_POST["OneRecipientId"] = intval($_QLitI);
         $_POST["OneMailingListId"] = intval($_Ql00j["id"]);
         if(isset($_QtIiC))
           unset($_QtIiC);
         $_QtIiC = array();
         _L10PF();
         _L10CL(array($_QLitI), $_QtIiC);
         unset($_POST["OneRecipientId"]);
         unset($_POST["OneMailingListId"]);

         if(count($_QtIiC) > 0) {
           $errors = array_merge($errors, $_QtIiC);
           $_Ql1O8 = array_merge($_Ql1O8, $_QtIiC);
           return false;
         }

         if($rid != "") {
           _OAD1J($rid);
           $rid = "";
         }

         if($_Ql00j["AddUnsubscribersToLocalBlacklist"]) {
           $_QJlJ0 = "INSERT IGNORE INTO `$_Ql00j[LocalBlocklistTableName]` SET u_EMail="._OPQLR( $_I1l66["u_EMail"] );
           mysql_query($_QJlJ0, $_Q61I1);
         }
         if($_Ql00j["AddUnsubscribersToGlobalBlacklist"]) {
           $_QJlJ0 = "INSERT IGNORE INTO `$_Ql8C0` SET u_EMail="._OPQLR( $_I1l66["u_EMail"] );
           mysql_query($_QJlJ0, $_Q61I1);
         }

      }
    } // if($_Ql00j["UnsubscriptionType"] == 'SingleOptOut' || ($Action == "unsubscribeconfirm") )

    if($_Ql00j["UnsubscriptionType"] == 'DoubleOptOut' && ($Action == "unsubscribe") ) {
       $_QJlJ0 = "UPDATE $_QlQC8 SET SubscriptionStatus ='OptOutConfirmationPending', DateOfUnsubscription=NOW() WHERE id=".intval($_QLitI);
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    } // if($_Ql00j["UnsubscriptionType"] == 'DoubleOptOut' && ($Action == "unsubscribe") )

    $_jfiOj = "";
    $_6j0oJ = "";
    if($_Ql00j["UnsubscriptionType"] == 'SingleOptOut' || ($Action == "unsubscribeconfirm") ) {
       $_jfiOj = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action='Unsubscribed', Member_id=".intval($_QLitI);
       $_6j0oJ = "DELETE FROM $_QlIf6 WHERE Member_id=".intval($_QLitI)." AND Action='OptOutConfirmationPending'";
      }
      else
      $_jfiOj = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action='OptOutConfirmationPending', Member_id=".intval($_QLitI);

    if($_jfiOj != "")
      $_Q60l1 = mysql_query($_jfiOj, $_Q61I1);
    if($_6j0oJ != "")
      $_Q60l1 = mysql_query($_6j0oJ, $_Q61I1);


    if( $_Ql00j["UnsubscriptionType"] == 'SingleOptOut' || ($Action == "unsubscribeconfirm")  ) {

      $_jfiOj = "";
      // insert in another mailinglist?
      if($_Ql00j["OnUnsubscribeAlsoAddToMailList"] > 0) {

        $_QJlJ0 = "SELECT MaillistTableName, StatisticsTableName, MailLogTableName FROM $_Q60QL WHERE id=$_Ql00j[OnUnsubscribeAlsoAddToMailList]";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q60l1 && mysql_num_rows($_Q60l1) > 0)
          $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
          else
          if (isset($_Q6Q1C))
            unset($_Q6Q1C);
        if($_Q60l1)
          mysql_free_result($_Q60l1);
        if(isset($_Q6Q1C)) {

          $_QJlJ0 = "INSERT IGNORE INTO $_Q6Q1C[MaillistTableName] SET SubscriptionStatus='Subscribed', ";
          $_I1l61 = array();
          reset($_jIiQ8);
          foreach ($_jIiQ8 as $key => $_Q6ClO) {
             if($key == "id" || $key == "SubscriptionStatus" || $key == "IdentString") continue;
             $_I1l61[] = "$key="._OPQLR($_Q6ClO);
          }

          $_QJlJ0 .= join(", ", $_I1l61);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

          if($_Q60l1 && mysql_affected_rows($_Q61I1) > 0) {
            $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
            $_Q8OiJ = mysql_fetch_row($_Q60l1);
            $_6jQj6 = $_Q8OiJ[0];
            mysql_free_result($_Q60l1);

            $_6jIQO = new _OFBEA();
            $_6jIQO->_OF0FL($_Q6Q1C["MailLogTableName"], $_6jQj6, "SYSTEM: unsubscribed recipient automatically copied from source mailing list '" . $_Ql00j["Name"] . "'");
            $_6jIQO = null;

            $_jfiOj = "INSERT INTO $_Q6Q1C[StatisticsTableName] SET ActionDate=NOW(), Action='Subscribed', Member_id=$_6jQj6";
          }
        }
      } // if($_Ql00j["OnUnsubscribeAlsoAddToMailList"] > 0)

      // remove from another mailinglist?
      if($_Ql00j["OnUnsubscribeAlsoRemoveFromMailList"] > 0) {
        $_Iijl0 = "SELECT MaillistTableName, StatisticsTableName, MailListToGroupsTableName FROM $_Q60QL WHERE id=$_Ql00j[OnUnsubscribeAlsoRemoveFromMailList]";
        $_Q60l1 = mysql_query($_Iijl0, $_Q61I1);
        if($_Q60l1 && mysql_num_rows($_Q60l1) > 0)
          $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
          else
          if (isset($_Q6Q1C))
            unset($_Q6Q1C);
        if($_Q60l1)
          mysql_free_result($_Q60l1);
        if(isset($_Q6Q1C)) {
          $_QJlJ0 = "SELECT id FROM $_Q6Q1C[MaillistTableName] WHERE u_EMail="._OPQLR($_I1l66["u_EMail"]);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

          if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
            $_Q8OiJ=mysql_fetch_array($_Q60l1);
            $_6jQj6 = $_Q8OiJ["id"];
            mysql_free_result($_Q60l1);

            $_POST["OneRecipientId"] = $_6jQj6;
            $_POST["OneMailingListId"] = $_Ql00j["OnUnsubscribeAlsoRemoveFromMailList"];
            _L10PF();
            _L10CL(array($_6jQj6), $_QtIiC);

            unset($_POST["OneRecipientId"]);
            unset($_POST["OneMailingListId"]);

            $_jfiOj = "INSERT INTO $_Q6Q1C[StatisticsTableName] SET ActionDate=NOW(), Action='Unsubscribed', Member_id=$_6jQj6";
          }
        }
      } // if($_Ql00j["OnUnsubscribeAlsoRemoveFromMailList"] > 0)

      if($_jfiOj != "")
         mysql_query($_jfiOj, $_Q61I1);

    } // if( $_Ql00j["UnsubscriptionType"] == 'SingleOptOut' || ($Action == "unsubscribeconfirm")  )

    // create unique key and send opt-out email
    if($_Ql00j["UnsubscriptionType"] == 'DoubleOptOut' && $Action == "unsubscribe"  ) {
      // create confirmation IdentString
      if(!empty($_I1l66["key"]))
        $_JIIQj = $_I1l66["key"];
        else
        $_JIIQj = "";
      $_JIIQj = _OA81R($_JIIQj, intval($_QLitI), intval($_QLl1Q["MailingListId"]), intval($_QLl1Q["FormId"]), $_QlQC8);

      $rid = "";
      if(!empty($_I1l66["rid"]))
        $rid = $_I1l66["rid"];

      $_IitLf = array();
      if (isset($_I1l66["RecipientGroups"])) {
        $_jfI01 = array_keys($_I1l66["RecipientGroups"]);
        foreach($_jfI01 as $_Qf0Ct => $_Q6ClO) {
          $_IitLf[] = $_jfI01[$_Qf0Ct];
        }
      }

      return _L0RLJ("unsubscribeconfirm", $_QLitI, $_Ql00j, $_QLl1Q, $errors, $_Ql1O8, $rid, $_IitLf);
    } // if($_Ql00j["UnsubscriptionType"] == 'DoubleOptOut' && $Action == "unsubscribe"  )

    return true;
  }

  function _L0JRQ($_QLitI, $_Ql00j, $_I1l66, $_QLl1Q, $Action, &$errors, &$_Ql1O8) {
    global $_Q61I1;
    $_QlIf6 = $_Ql00j["StatisticsTableName"];

    // remove it
    $_POST["OneRecipientId"] = intval($_QLitI);
    $_POST["OneMailingListId"] = intval($_Ql00j["id"]);
    if(isset($_QtIiC))
      unset($_QtIiC);
    $_QtIiC = array();
    _L10PF();
    _L10CL(array($_QLitI), $_QtIiC);
    unset($_POST["OneRecipientId"]);
    unset($_POST["OneMailingListId"]);

    $_jfiOj = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action='Unsubscribed', Member_id=".intval($_QLitI);
    mysql_query($_jfiOj, $_Q61I1);

    if(count($_QtIiC) > 0) {
      $_Ql1O8 = array_merge($_Ql1O8, $_QtIiC);
      return false;
    }

    return true;
  }

  function _L06JL($_QLitI, $_Ql00j, $_I1l66, $_QLl1Q, $Action, &$errors, &$_Ql1O8) {
    global $_Q61I1;
    $_QlQC8 = $_Ql00j["MaillistTableName"];
    $_QlIf6 = $_Ql00j["StatisticsTableName"];
    $_Q6t6j = $_Ql00j["GroupsTableName"];
    $_QLI68 = $_Ql00j["MailListToGroupsTableName"];

    $_QJlJ0 = "UPDATE `$_QlQC8` SET `SubscriptionStatus`='Subscribed' WHERE id=".intval($_QLitI);
    mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_error($_Q61I1) != "") {
      $_Ql1O8[] = mysql_error($_Q61I1);
    }

    $_jfiOj = "DELETE FROM `$_QlIf6` WHERE `Action`='OptOutConfirmationPending' AND `Member_id`=".intval($_QLitI);
    mysql_query($_jfiOj, $_Q61I1);

    return count($_Ql1O8) == 0 ? true : false;
  }

  function _L0RLO($_QLitI, $_Ql00j, $_I1l66, $_QLl1Q, $Action, &$errors, &$_Ql1O8) {
    global $_Q61I1;

    $_QJlJ0 = "DELETE FROM `$_Ql00j[EditTableName]` WHERE `Member_id`=".intval($_QLitI);
    mysql_query($_QJlJ0, $_Q61I1);

    return true;
  }

  function _L0RLJ($Type, $_QLitI, $_Ql00j, $_QLl1Q, &$errors, &$_Ql1O8, $rid="", $_6jI6J=array()) {
    global $_jJJjO, $_Qofoi, $_Q6JJJ, $_Q61I1;
    global $_jJioL, $_jJLJ6, $_jJLoj, $_III0L;
    global $_QOCJo, $_QCo6j, $_jji0i, $_jji0C;
    global $_jjLO0, $_jJ088, $_jJ1Il;
    global $_jjiCt, $_jjlQ0, $_jjlC6;

    $_QlQC8 = $_Ql00j["MaillistTableName"];

    $_IIoQO = $_Ql00j["SenderFromName"];
    $_IQf88 = $_Ql00j["SenderFromAddress"];
    $_IQ8QI = $_Ql00j["ReplyToEMailAddress"];
    $_IQ8LL = $_Ql00j["ReturnPathEMailAddress"];
    $_QJLLO = $_Ql00j["AllowOverrideSenderEMailAddressesWhileMailCreating"];
    $_Iijft = $_QLl1Q["OverrideSubUnsubURL"];

    if(isset($_IQtQJ))
     unset($_IQtQJ);
    if(isset($_IQOlf))
     unset($_IQOlf);

    if(!empty($_Ql00j["CcEMailAddresses"]))
      $_IQtQJ = $_Ql00j["CcEMailAddresses"];
    if(!empty($_Ql00j["BCcEMailAddresses"]))
      $_IQOlf = $_Ql00j["BCcEMailAddresses"];

    if($_QJLLO) {
      if(!empty($_QLl1Q["SenderFromName"]))
        $_IIoQO = $_QLl1Q["SenderFromName"];
      if(!empty($_QLl1Q["SenderFromAddress"]))
        $_IQf88 = $_QLl1Q["SenderFromAddress"];
      if(!empty($_QLl1Q["ReplyToEMailAddress"]))
        $_IQ8QI = $_QLl1Q["ReplyToEMailAddress"];
      if(!empty($_QLl1Q["ReturnPathEMailAddress"]))
        $_IQ8LL = $_QLl1Q["ReturnPathEMailAddress"];
      if(!empty($_QLl1Q["CcEMailAddresses"]))
        $_IQtQJ = $_QLl1Q["CcEMailAddresses"];
      if(!empty($_QLl1Q["BCcEMailAddresses"]))
        $_IQOlf = $_QLl1Q["BCcEMailAddresses"];
    }

    // Get recipient data
    if(!is_array($_QLitI)) {
      $_QLitI = intval($_QLitI);
      $_Ql00j["id"] = intval($_Ql00j["id"]);
      $_QLl1Q["FormId"] = intval($_QLl1Q["FormId"]);
      $_QJlJ0 = "SELECT * FROM `$_QlQC8` WHERE id=$_QLitI";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_Q6Q1C["IdentString"] = _OA81R($_Q6Q1C["IdentString"], $_QLitI, $_Ql00j["id"], $_QLl1Q["FormId"], $_QlQC8);
      # only on edit confirmation
      if(isset($_Ql00j["NewEMail"]) && $Type == "editconfirm") {
        $_Q6Q1C["u_EMail"] = $_Ql00j["NewEMail"];
        unset($_Ql00j["NewEMail"]);
      }
    } else {
      $_Q6Q1C = $_QLitI;
      $_QLitI = intval($_Q6Q1C["id"]);
      $_Ql00j["id"] = intval($_Ql00j["id"]);
      $_QLl1Q["FormId"] = intval($_QLl1Q["FormId"]);
      $_Q6Q1C["IdentString"] = _OA81R($_Q6Q1C["IdentString"], $_QLitI, $_Ql00j["id"], $_QLl1Q["FormId"], $_QlQC8);
    }

    // MTAs
    $_QJlJ0 = "SELECT $_Qofoi.* FROM $_Qofoi RIGHT JOIN $_Ql00j[MTAsTableName] ON $_Ql00j[MTAsTableName].mtas_id=$_Qofoi.id ORDER BY $_Ql00j[MTAsTableName].sortorder";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    // we take the first in list
    $_jIfO0 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_6jjjO = array();
    $MailType = mtInternalMail;
    if($Type == "subscribeconfirm") {
      $_IQCoo = $_QLl1Q["OptInConfirmationMailEncoding"];
      $Subject = $_QLl1Q["OptInConfirmationMailSubject"];
      $_IQitJ = $_QLl1Q["OptInConfirmationMailPlainText"];
      $_IQC1o = $_QLl1Q["OptInConfirmationMailFormat"];
      $_IQ18l = $_QLl1Q["OptInConfirmationMailHTMLText"];
      $_j1CLL = $_QLl1Q["OptInConfirmationMailPriority"];
      $MailType = mtOptInConfirmationMail;
      }
      else
      if($Type == "unsubscribeconfirm") {
          $_IQCoo = $_QLl1Q["OptOutConfirmationMailEncoding"];
          $Subject = $_QLl1Q["OptOutConfirmationMailSubject"];
          $_IQitJ = $_QLl1Q["OptOutConfirmationMailPlainText"];
          $_IQC1o = $_QLl1Q["OptOutConfirmationMailFormat"];
          $_IQ18l = $_QLl1Q["OptOutConfirmationMailHTMLText"];
          $_j1CLL = $_QLl1Q["OptOutConfirmationMailPriority"];
          $MailType = mtOptOutConfirmationMail;
        }
        else
        if($Type == "subscribeconfirmed") {
          $_IQCoo = $_QLl1Q["OptInConfirmedMailEncoding"];
          $Subject = $_QLl1Q["OptInConfirmedMailSubject"];
          $_IQitJ = $_QLl1Q["OptInConfirmedMailPlainText"];
          $_IQC1o = $_QLl1Q["OptInConfirmedMailFormat"];
          $_IQ18l = $_QLl1Q["OptInConfirmedMailHTMLText"];
          $_j1CLL = $_QLl1Q["OptInConfirmedMailPriority"];
          $MailType = mtOptInConfirmedMail;
          if($_QLl1Q["OptInConfirmedAttachments"] != "")
            $_6jjjO = unserialize($_QLl1Q["OptInConfirmedAttachments"]);
          }
          else
          if($Type == "unsubscribeconfirmed") {
              $_IQCoo = $_QLl1Q["OptOutConfirmedMailEncoding"];
              $Subject = $_QLl1Q["OptOutConfirmedMailSubject"];
              $_IQitJ = $_QLl1Q["OptOutConfirmedMailPlainText"];
              $_IQC1o = $_QLl1Q["OptOutConfirmedMailFormat"];
              $_IQ18l = $_QLl1Q["OptOutConfirmedMailHTMLText"];
              $_j1CLL = $_QLl1Q["OptOutConfirmedMailPriority"];
              $MailType = mtOptOutConfirmedMail;
              if($_QLl1Q["OptOutConfirmedAttachments"] != "") {
                $_6jjjO = @unserialize($_QLl1Q["OptOutConfirmedAttachments"]);
                if($_6jjjO === false)
                  $_6jjjO = array();
              }
            }
            else
             if($Type == "editconfirm") {
               $_IQCoo = $_QLl1Q["EditConfirmationMailEncoding"];
               $Subject = $_QLl1Q["EditConfirmationMailSubject"];
               $_IQitJ = $_QLl1Q["EditConfirmationMailPlainText"];
               $_IQC1o = $_QLl1Q["EditConfirmationMailFormat"];
               $_IQ18l = $_QLl1Q["EditConfirmationMailHTMLText"];
               $_j1CLL = $_QLl1Q["EditConfirmationMailPriority"];
               $MailType = mtEditConfirmationMail;
               }
               else
                if($Type == "editconfirmed") {
                  $_IQCoo = $_QLl1Q["EditConfirmedMailEncoding"];
                  $Subject = $_QLl1Q["EditConfirmedMailSubject"];
                  $_IQitJ = $_QLl1Q["EditConfirmedMailPlainText"];
                  $_IQC1o = $_QLl1Q["EditConfirmedMailFormat"];
                  $_IQ18l = $_QLl1Q["EditConfirmedMailHTMLText"];
                  $_j1CLL = $_QLl1Q["EditConfirmedMailPriority"];
                  $MailType = mtEditConfirmedMail;
                  }


    // mail object
    $_IiJit = new _OF0EE($MailType);

    // set mail object params
    $_IiJit->_OE868();

    // MUST overwrite it?
    if(!empty($_jIfO0["MTASenderEMailAddress"]))
      $_IQf88 = $_jIfO0["MTASenderEMailAddress"];

    $_IiJit->From[] = array("address" => $_IQf88, "name" => _L1ERL($_Q6Q1C, $_Ql00j["id"], $_IIoQO, $_IQCoo, false, array()) );
    $_IiJit->To[] = array("address" => $_Q6Q1C["u_EMail"], "name" => _L1ERL($_Q6Q1C, $_Ql00j["id"], $_Q6Q1C["u_FirstName"]." ".$_Q6Q1C["u_LastName"], $_IQCoo, false, array()) );
    if($_IQ8QI != "")
       $_IiJit->ReplyTo[] = array("address" => $_IQ8QI, "name" => "");
    if($_IQ8LL != "")
      $_IiJit->ReturnPath[] = array("address" => $_IQ8LL, "name" => "");

    if(!empty($_IQtQJ)) {
       $_Qot0C = explode(",", $_IQtQJ);
       for($_Q6llo=0; $_Q6llo<count($_Qot0C); $_Q6llo++)
         if(trim($_Qot0C[$_Q6llo]) != "")
           $_IiJit->Cc[] = array("address" => trim($_Qot0C[$_Q6llo]), "name" => "");
    }

    if(!empty($_IQOlf)) {
       $_Qot0C = explode(",", $_IQOlf);
       for($_Q6llo=0; $_Q6llo<count($_Qot0C); $_Q6llo++)
         if(trim($_Qot0C[$_Q6llo]) != "")
           $_IiJit->BCc[] = array("address" => trim($_Qot0C[$_Q6llo]), "name" => "");
    }

    $_IiJit->Subject = _L1ERL($_Q6Q1C, $_Ql00j["id"], $Subject, $_IQCoo, false, array());

    $_JiiQJ = array ();
    if($Type == "subscribeconfirm") {
      reset($_jJioL);
      foreach ($_jJioL as $key => $_Q6ClO) {
         $_I1L81 = "";
         if ($_Q6ClO == '[SubscribeRejectLink]')
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?Action=subscribereject&key=".$_Q6Q1C["IdentString"];
            else
            if ($_Q6ClO == '[SubscribeConfirmationLink]')
              $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?Action=subscribeconfirm&key=".$_Q6Q1C["IdentString"];
         if(!empty($rid))
           $_I1L81 .= "&rid=$rid";
         $_JiiQJ[$_Q6ClO] = $_I1L81;
      }
    } elseif($Type == "unsubscribeconfirm") {
      reset($_jJLJ6);
      foreach ($_jJLJ6 as $key => $_Q6ClO) {
         $_I1L81 = "";
         if ($_Q6ClO == '[UnsubscribeRejectLink]')
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?Action=unsubscribereject&key=".$_Q6Q1C["IdentString"];
            else
            if ($_Q6ClO == '[UnsubscribeConfirmationLink]') {
              $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?Action=unsubscribeconfirm&key=".$_Q6Q1C["IdentString"];
              if(count($_6jI6J) > 0)
                 $_I1L81 .= "&RG=".join(",", $_6jI6J);
            }
         if(!empty($rid))
           $_I1L81 .= "&rid=$rid";
         $_JiiQJ[$_Q6ClO] = $_I1L81;
      }
    } elseif($Type == "subscribeconfirmed" || $Type == "editconfirmed") {
      reset($_III0L);
      foreach ($_III0L as $key => $_Q6ClO) {
         $_I1L81 = "";
         if ($_Q6ClO == '[UnsubscribeLink]') {
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?key=$_Q6Q1C[IdentString]";
            if(count($_6jI6J) > 0)
               $_I1L81 .= "&RG=".join(",", $_6jI6J);
         }
         if ($_Q6ClO == '[EditLink]') {
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?key=".$_Q6Q1C["IdentString"]."&ML=$_Ql00j[id]&F=$_QLl1Q[FormId]&HTMLForm=editform";
         }
         if(!empty($rid))
           $_I1L81 .= "&rid=$rid";
         $_JiiQJ[$_Q6ClO] = $_I1L81;
      }
    } elseif($Type == "editconfirm") {
      reset($_jJLoj);
      foreach ($_jJLoj as $key => $_Q6ClO) {
         $_I1L81 = "";
         if ($_Q6ClO == '[EditRejectLink]')
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?Action=editreject&key=".$_Q6Q1C["IdentString"];
            else
            if ($_Q6ClO == '[EditConfirmationLink]') {
              $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?Action=editconfirm&key=".$_Q6Q1C["IdentString"];
              if(count($_6jI6J) > 0)
                 $_I1L81 .= "&RG=".join(",", $_6jI6J);
            }
         if(!empty($rid))
           $_I1L81 .= "&rid=$rid";
         $_JiiQJ[$_Q6ClO] = $_I1L81;
      }
    }

    $_IiJit->TextPart = _L1ERL($_Q6Q1C, $_Ql00j["id"], $_IQitJ, $_IQCoo, false, $_JiiQJ);
    if($_IQC1o != "PlainText") {
       $_JjflQ = $_IQ18l;
       $_JjflQ = _OB8O0("<title>", "</title>", $_JjflQ, $Subject);
       $_JjflQ = _L1ERL($_Q6Q1C, $_Ql00j["id"], $_JjflQ, $_IQCoo, true, $_JiiQJ);
       $_JjflQ = SetHTMLCharSet($_JjflQ, $_IQCoo, true);

       // inline images
       $_IiJit->_OEPOO();
       $_jitLI = array();
       GetInlineFiles($_JjflQ, $_jitLI);
       for($_Q6llo=0; $_Q6llo< count($_jitLI); $_Q6llo++) {
         if(!@file_exists($_jitLI[$_Q6llo])) {
           $_QJCJi = _OBEDB($_jitLI[$_Q6llo]);
           $_JjflQ = str_replace($_jitLI[$_Q6llo], $_QJCJi, $_JjflQ);
           $_jitLI[$_Q6llo] = $_QJCJi;
         }
         $_IiJit->InlineImages[] = array ("file" => $_jitLI[$_Q6llo], "c_type" => _OBCE8($_jitLI[$_Q6llo]), "name" => "", "isfile" => true );
       }

       $_IiJit->HTMLPart = $_JjflQ;
    }

    switch ($_j1CLL) {
      case 'Low' :
         $_IiJit->Priority = mpLow;
         break;
      case 'Normal':
         $_IiJit->Priority = mpNormal;
         break;
      case 'High'  :
         $_IiJit->Priority = mpHighest;
    }

    // attachments
    $_IiJit->_OEPFA();
    for($_Q6llo=0; $_Q6llo< count($_6jjjO); $_Q6llo++) {
      $_IiJit->Attachments[] = array ("file" => $_QOCJo.CheckFileNameForUTF8($_6jjjO[$_Q6llo]), "c_type" => "application/octet-stream", "name" => "", "isfile" => true );
    }


    // email options
    $_QJlJ0 = "SELECT * FROM $_jJJjO";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q8OiJ = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_IiJit->crlf = $_Q8OiJ["CRLF"];
    $_IiJit->head_encoding = $_Q8OiJ["Head_Encoding"];
    $_IiJit->text_encoding = $_Q8OiJ["Text_Encoding"];
    $_IiJit->html_encoding = $_Q8OiJ["HTML_Encoding"];
    $_IiJit->attachment_encoding = $_Q8OiJ["Attachment_Encoding"];
    $_IiJit->XMailer = $_Q8OiJ["XMailer"];

    $_IiJit->charset = $_IQCoo;


  // mail send settings
    $_IiJit->Sendvariant = $_jIfO0["Type"]; // mail, sendmail, smtp, smtpmx, text

    $_IiJit->PHPMailParams = $_jIfO0["PHPMailParams"];
    $_IiJit->HELOName = $_jIfO0["HELOName"];

    $_IiJit->SMTPpersist = (bool)$_jIfO0["SMTPPersist"];
    $_IiJit->SMTPpipelining = (bool)$_jIfO0["SMTPPipelining"];
    $_IiJit->SMTPTimeout = $_jIfO0["SMTPTimeout"];
    $_IiJit->SMTPServer = $_jIfO0["SMTPServer"];
    $_IiJit->SMTPPort = $_jIfO0["SMTPPort"];
    $_IiJit->SMTPAuth = (bool)$_jIfO0["SMTPAuth"];
    $_IiJit->SMTPUsername = $_jIfO0["SMTPUsername"];
    $_IiJit->SMTPPassword = $_jIfO0["SMTPPassword"];
    if(isset($_jIfO0["SMTPSSL"]))
      $_IiJit->SSLConnection = (bool)$_jIfO0["SMTPSSL"];

    $_IiJit->sendmail_path = $_jIfO0["sendmail_path"];
    $_IiJit->sendmail_args = $_jIfO0["sendmail_args"];

    $_IiJit->SignMail = (bool)$_jIfO0["SMIMESignMail"];
    $_IiJit->SMIMEMessageAsPlainText = (bool)$_jIfO0["SMIMEMessageAsPlainText"];

    $_IiJit->SignCert = $_jIfO0["SMIMESignCert"];
    $_IiJit->SignPrivKey = $_jIfO0["SMIMESignPrivKey"];
    $_IiJit->SignPrivKeyPassword = $_jIfO0["SMIMESignPrivKeyPassword"];
    $_IiJit->SignTempFolder = $_jji0C;

    $_IiJit->SMIMEIgnoreSignErrors = (bool)$_jIfO0["SMIMEIgnoreSignErrors"];

    $_IiJit->DKIM = (bool)$_jIfO0["DKIM"];
    $_IiJit->DomainKey = (bool)$_jIfO0["DomainKey"];
    $_IiJit->DKIMSelector = $_jIfO0["DKIMSelector"];
    $_IiJit->DKIMPrivKey = $_jIfO0["DKIMPrivKey"];
    $_IiJit->DKIMPrivKeyPassword = $_jIfO0["DKIMPrivKeyPassword"];
    $_IiJit->DKIMIgnoreSignErrors = (bool)$_jIfO0["DKIMIgnoreSignErrors"];

    if(!$_IiJit->_OED01($_JCtt0, $_I606j)) {
       $errors[] = $_IiJit->errors["errorcode"];
       $_Ql1O8[] = $_IiJit->errors["errortext"];
       _OBQEP($_IQf88, join($_Q6JJJ, $_Ql1O8)."   ".$Subject);
       return false;
    }

    if(!$_IiJit->_OEDRQ($_JCtt0, $_I606j)) {
       $errors[] = $_IiJit->errors["errorcode"];
       $_Ql1O8[] = $_IiJit->errors["errortext"];
       _OBQEP($_IQf88, join($_Q6JJJ, $_Ql1O8)."   ".$Subject);
       return false;
    } else
      $_IiJit->_OF0FL($_Ql00j["MailLogTableName"], $_QLitI, ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false));

    return true;
  }

  function _L0R6A($_QLitI, $_Ql00j, $_jiJC0, $Type, $_QLl1Q, $_6jJj6 = "") {
   global $INTERFACE_LANGUAGE, $resourcestrings, $_Qofjo, $_Q6JJJ, $_Q8f1L, $_jJJjO, $_Qofoi, $resourcestrings, $_Q61I1, $_jji0C;

   $_IQCoo = "utf-8";
   $Subject = "";
   $_6jJlf = "";
   $_6j6oi = false;
   switch ($Type) {
     case 'subscribe' :
        $Subject = $resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifySubjectOnSubscribe"];
        if( isset($_Ql00j["SendEMailToAdminOnOptIn"]) )
            $_6j6oi = $_Ql00j["SendEMailToAdminOnOptIn"];
        if( isset($_Ql00j["SendEMailToEMailAddressOnOptIn"]) && $_Ql00j["SendEMailToEMailAddressOnOptIn"] )
            $_6jJlf = $_Ql00j["EMailAddressOnOptInEMailAddress"];
        break;
     case 'unsubscribe':
        $Subject = $resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifySubjectOnUnubscribe"];
        if( isset($_Ql00j["SendEMailToAdminOnOptOut"]) )
            $_6j6oi = $_Ql00j["SendEMailToAdminOnOptOut"];
        if( isset($_Ql00j["SendEMailToEMailAddressOnOptOut"]) && $_Ql00j["SendEMailToEMailAddressOnOptOut"] )
            $_6jJlf = $_Ql00j["EMailAddressOnOptOutEMailAddress"];
        break;
     case 'edit'  :
        $Subject = $resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifySubjectOnEdit"];
        if( isset($_Ql00j["SendEMailToAdminOnOptIn"]) )
            $_6j6oi = $_Ql00j["SendEMailToAdminOnOptIn"];
        if( isset($_Ql00j["SendEMailToEMailAddressOnOptIn"]) && $_Ql00j["SendEMailToEMailAddressOnOptIn"] )
            $_6jJlf = $_Ql00j["EMailAddressOnOptInEMailAddress"];
        break;
   }

   $Subject = sprintf($Subject, $_Ql00j["Name"]);

   $_6jf8f = $resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifyBody"];

   if(count($_jiJC0) == 0) {
      $_QJlJ0 = "SELECT * FROM `$_Ql00j[MaillistTableName]` WHERE id=".intval($_QLitI);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_jiJC0 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
   }

   // Replace placeholders
   if($_6jJlf != "")
      $_6jJlf = _L1ERL($_jiJC0, $_Ql00j["id"], $_6jJlf, $_IQCoo, false, array());

   $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE'";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_I11oJ = "";
   while($_QLLjo = mysql_fetch_assoc($_Q60l1) ) {
     $key = $_QLLjo["fieldname"];
     $_Q6ClO = $_QLLjo["text"];
     if($key == "u_Birthday" && $_jiJC0[$key] == "0000-00-00") continue;
     if($key == "u_Gender" && $_jiJC0[$key] == "undefined") continue;
     if(isset($_jiJC0[$key]) && !empty($_jiJC0[$key]))
        $_I11oJ .= unhtmlentities($_Q6ClO, strtoupper($_IQCoo) ).": ".ConvertString("utf-8", $_IQCoo, $_jiJC0[$key], false).$_Q6JJJ;
     if($key == "u_EMail" && $_6jJj6 != "") {
        $_I11oJ .= unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["AdminNotifyEMailOld"], strtoupper($_IQCoo) ).": ".$_6jJj6.$_Q6JJJ;
     }
   }
   mysql_free_result($_Q60l1);
   $_6jf8f .= $_Q6JJJ.$_Q6JJJ.$_I11oJ;

   $_QJlJ0 = "SELECT Username, EMail FROM `$_Q8f1L` WHERE id=$_Ql00j[users_id]";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return false;
   $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   // mail object
   $_IiJit = new _OF0EE(mtAdminNotifyMail);

   // MTAs
   $_QJlJ0 = "SELECT `$_Qofoi`.* FROM `$_Qofoi` RIGHT JOIN $_Ql00j[MTAsTableName] ON $_Ql00j[MTAsTableName].mtas_id=$_Qofoi.id ORDER BY $_Ql00j[MTAsTableName].sortorder";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   // we take the first in list
   $_jIfO0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   // set mail object params
   $_IiJit->_OE868();

   $_IQf88 = $_Q8OiJ["EMail"];
  // MUST overwrite it?
  if(!empty($_jIfO0["MTASenderEMailAddress"]))
    $_IQf88 = $_jIfO0["MTASenderEMailAddress"];

   $_IiJit->From[] = array("address" => $_IQf88, "name" => $_Q8OiJ["Username"] );
   if($_6j6oi)
     $_IiJit->To[] = array("address" => $_Q8OiJ["EMail"], "name" => $_Q8OiJ["Username"] );
     else
     $_IiJit->To[] = array("address" => $_6jJlf, "name" => $_6jJlf );
   $_IiJit->Subject = $Subject;
   $_IiJit->TextPart = $_6jf8f;

   // email options
   $_QJlJ0 = "SELECT * FROM `$_jJJjO`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   $_IiJit->crlf = $_Q8OiJ["CRLF"];
   $_IiJit->head_encoding = $_Q8OiJ["Head_Encoding"];
   $_IiJit->text_encoding = $_Q8OiJ["Text_Encoding"];
   $_IiJit->html_encoding = $_Q8OiJ["HTML_Encoding"];
   $_IiJit->attachment_encoding = $_Q8OiJ["Attachment_Encoding"];

   $_IiJit->charset = $_IQCoo;


 // mail send settings
   $_IiJit->Sendvariant = $_jIfO0["Type"]; // mail, sendmail, smtp, smtpmx, text

   $_IiJit->PHPMailParams = $_jIfO0["PHPMailParams"];
   $_IiJit->HELOName = $_jIfO0["HELOName"];

   $_IiJit->SMTPpersist = (bool)$_jIfO0["SMTPPersist"];
   $_IiJit->SMTPpipelining = (bool)$_jIfO0["SMTPPipelining"];
   $_IiJit->SMTPTimeout = $_jIfO0["SMTPTimeout"];
   $_IiJit->SMTPServer = $_jIfO0["SMTPServer"];
   $_IiJit->SMTPPort = $_jIfO0["SMTPPort"];
   $_IiJit->SMTPAuth = (bool)$_jIfO0["SMTPAuth"];
   $_IiJit->SMTPUsername = $_jIfO0["SMTPUsername"];
   $_IiJit->SMTPPassword = $_jIfO0["SMTPPassword"];
   if(isset($_jIfO0["SMTPSSL"]))
     $_IiJit->SSLConnection = (bool)$_jIfO0["SMTPSSL"];

   $_IiJit->sendmail_path = $_jIfO0["sendmail_path"];
   $_IiJit->sendmail_args = $_jIfO0["sendmail_args"];

   $_IiJit->SignMail = (bool)$_jIfO0["SMIMESignMail"];
   $_IiJit->SMIMEMessageAsPlainText = (bool)$_jIfO0["SMIMEMessageAsPlainText"];

   $_IiJit->SignCert = $_jIfO0["SMIMESignCert"];
   $_IiJit->SignPrivKey = $_jIfO0["SMIMESignPrivKey"];
   $_IiJit->SignPrivKeyPassword = $_jIfO0["SMIMESignPrivKeyPassword"];
   $_IiJit->SignTempFolder = $_jji0C;

   $_IiJit->SMIMEIgnoreSignErrors = (bool)$_jIfO0["SMIMEIgnoreSignErrors"];

   $_IiJit->DKIM = (bool)$_jIfO0["DKIM"];
   $_IiJit->DomainKey = (bool)$_jIfO0["DomainKey"];
   $_IiJit->DKIMSelector = $_jIfO0["DKIMSelector"];
   $_IiJit->DKIMPrivKey = $_jIfO0["DKIMPrivKey"];
   $_IiJit->DKIMPrivKeyPassword = $_jIfO0["DKIMPrivKeyPassword"];
   $_IiJit->DKIMIgnoreSignErrors = (bool)$_jIfO0["DKIMIgnoreSignErrors"];

   if(!$_IiJit->_OED01($_JCtt0, $_I606j)) {
      $_Ql1O8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000652"], $_IiJit->errors["errorcode"], $_IiJit->errors["errortext"]);
      _OBQEP($_IQf88, $_Ql1O8."   ".$Subject);
      return false;
   }
   if(!$_IiJit->_OEDRQ($_JCtt0, $_I606j)) {
      $_Ql1O8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000652"], $_IiJit->errors["errorcode"], $_IiJit->errors["errortext"]);
      _OBQEP($_IQf88, $_Ql1O8."   ".$Subject);
      return false;
   }

   // send to $_6jJlf?
   if($_6j6oi && $_6jJlf != "") {
      $_Ql00j["SendEMailToAdminOnOptIn"] = 0;
      $_Ql00j["SendEMailToAdminOnOptOut"] = 0;
      return _L0R6A($_QLitI, $_Ql00j, $_jiJC0, $Type, $_QLl1Q, $_6jJj6);
   } else
     return true;
  }

  function _L0R8D($_j6ioL, $_Q6ICj, $_jIiQ8){
    global $_Q61I1, $_jJl6I;

    if(!isset($_j6ioL["RequestReasonForUnsubscription"]) || !$_j6ioL["RequestReasonForUnsubscription"] || empty($_j6ioL["ReasonsForUnsubscripeTableName"])){
      $_Q6ICj = str_ireplace($_jJl6I["ReasonsForUnsubscriptionSurvey"], "", $_Q6ICj);
      return $_Q6ICj;
    }

    $_QJCJi = join("", file(_O68QF()."reasonsforunsubscription_template.htm"));
    if(empty($_QJCJi)){
      $_Q6ICj = str_ireplace($_jJl6I["ReasonsForUnsubscriptionSurvey"], "", $_Q6ICj);
      return $_Q6ICj;
    }

    $_Q8OiJ = _OP81D($_QJCJi, "<REASONSFORUNSUBSCRIPTION_ROW>", "</REASONSFORUNSUBSCRIPTION_ROW>");
    $_6jfO0 = "";
    $_QJlJ0 = "SELECT * FROM `$_j6ioL[ReasonsForUnsubscripeTableName]` WHERE `forms_id`=$_j6ioL[FormId] ORDER BY `sort_order`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
      $_Q66jQ = $_Q8OiJ;

      $_I6ICC = "";
      if($_Q6Q1C["ReasonType"] == "Text"){
        $_I6ICC = ' document.getElementById(\'ReasonText_'.$_Q6Q1C["id"].'\').focus();';
      }

      $_Q66jQ = _OPR6L($_Q66jQ, "<REASONSFORUNSUBSCRIPTION_COL1>", "</REASONSFORUNSUBSCRIPTION_COL1>", '<input type="radio" name="ReasonsForUnsubscripe_id" value="'.$_Q6Q1C["id"].'" id="REASONSFORUNSUBSCRIPTION_'.$_Q6Q1C["id"].'" onclick="EnableDisableReasonsForUnsubscripeControls(this);'.$_I6ICC.'" />');

      $_Q66jQ = _OPR6L($_Q66jQ, "<REASONSFORUNSUBSCRIPTION_COL2>", "</REASONSFORUNSUBSCRIPTION_COL2>", '<label for="REASONSFORUNSUBSCRIPTION_'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Reason"].'</label>');

      if($_Q6Q1C["ReasonType"] == "Text"){
        $_Q66jQ .= $_Q8OiJ;
        $_Q66jQ = _OPR6L($_Q66jQ, "<REASONSFORUNSUBSCRIPTION_COL1>", "</REASONSFORUNSUBSCRIPTION_COL1>", '');

        $_Q66jQ = _OPR6L($_Q66jQ, "<REASONSFORUNSUBSCRIPTION_COL2>", "</REASONSFORUNSUBSCRIPTION_COL2>", '<textarea maxlength="65535" class="reasonsforunsubscription_textarea" cols="60" rows="10" name="ReasonText" id="ReasonText_' . $_Q6Q1C["id"] . '" onchange="EnableDisableReasonsForUnsubscripeControls(null);" oninput="EnableDisableReasonsForUnsubscripeControls(null);" onpropertychange="EnableDisableReasonsForUnsubscripeControls(null);"></textarea>');

      }

      $_6jfO0 .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_6jfO0 = _OPR6L($_QJCJi, "<REASONSFORUNSUBSCRIPTION_ROW>", "</REASONSFORUNSUBSCRIPTION_ROW>", $_6jfO0);

    $_Q6ICj = str_ireplace($_jJl6I["ReasonsForUnsubscriptionSurvey"], $_6jfO0, $_Q6ICj);

    $_Q6ICj = str_replace('name="MailingListId"', 'name="MailingListId" value="' . $_j6ioL["MailingListId"] . '"', $_Q6ICj);
    $_Q6ICj = str_replace('name="FormId"', 'name="FormId" value="' . $_j6ioL["FormId"] . '"', $_Q6ICj);

    $_6jfC1="";
    if(is_array($_jIiQ8)){
      foreach($_jIiQ8 as $key => $_Q6ClO){
         if(strpos($key, "u_") === false) continue;
         if($key == "u_Birthday" && $_Q6ClO == "0000-00-00") continue;
         if($key == "u_Gender" && $_Q6ClO == "undefined") continue;
         if($key == "u_Comments") continue;
         if(empty($_Q6ClO)) continue;
         $_6jfC1 .= sprintf('<input type="hidden" name="%s" value="%s" />', $key, $_Q6ClO);
      }
    }
    $_Q6ICj = str_replace('<!--MEMBERSRECORD/-->', $_6jfC1, $_Q6ICj);

    return $_Q6ICj;
  }

  function _L08LL($_6j8tt, $_QlQC8, $_6jtQj, $_6jtil, $_j6ioL, $errors) {
    global $_ICljl, $_Q6QQL, $_Q61I1;
    global $_jjiCt, $_jjLO0, $_jjlC6, $_jJ1Il;

    $_6jOOo = $_Q6QQL;
    $_Iijft = $_j6ioL["OverrideSubUnsubURL"];
    $_QJlJ0 = "SELECT `RedirectURL`, `HTMLPage`, `ForceRedirect` FROM `$_ICljl` WHERE `id`=$_j6ioL[$_6jtQj]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    if($_Q6Q1C["RedirectURL"] != "") {

      $_6joj1 = $_Q6Q1C["RedirectURL"];
      $_6joJ1 = "";
      if(is_array($errors) && count($errors) > 0){
        $_6joJ1 = "ERRORPAGEMESSAGE=".urlencode( join("", $errors) );
      }
      if(isset($_j6ioL[$_6jtil]) && $_j6ioL[$_6jtil] != "")
         $_6joJ1 == "" ? $_6joJ1 = "PAGEMESSAGE=".urlencode( $_j6ioL[$_6jtil] ) : $_6joJ1 .= "&". "PAGEMESSAGE=".urlencode( $_j6ioL[$_6jtil] );
      if($_6joJ1 != "") {
        if(strpos($_6joj1, "?") === false)
           $_6joj1 .= "?".$_6joJ1;
           else
           $_6joj1 .= "&".$_6joJ1;
      }

      if( !ini_get("allow_url_fopen") || defined("ForcePageRedirect") || $_Q6Q1C["ForceRedirect"]>0 ) {
        header("Location: $_6joj1");
        print 'Die Seite befindet sich jetzt hier <a href="'.$_6joj1.'">'.$_Q6Q1C["RedirectURL"].'</a><br /><br />';
        print 'The page has changed to <a href="'.$_6joj1.'">'.$_Q6Q1C["RedirectURL"].'</a>';
        exit;
      } else {
        $_Q6ICj = join("", file($_Q6Q1C["RedirectURL"]));
        if($_Q6ICj == "") {
          header("Location: $_6joj1");
          print 'Die Seite befindet sich jetzt hier <a href="'.$_6joj1.'">'.$_Q6Q1C["RedirectURL"].'</a><br /><br />';
          print 'The page has changed to <a href="'.$_6joj1.'">'.$_Q6Q1C["RedirectURL"].'</a>';
          exit;
        }

        $_6jOOo = GetHTMLCharSet($_Q6ICj);

        if($_6jOOo != $_Q6QQL) {
          $_Q6ICj = ConvertString($_6jOOo, $_Q6QQL, $_Q6ICj, true);
          $_Q6ICj = SetHTMLCharSet($_Q6ICj, $_Q6QQL, strpos($_Q6ICj, "/>") !== false);
          $_6jOOo = $_Q6QQL;
        }

        // change inline files
        $_jitLI = array();
        GetInlineFiles($_Q6ICj, $_jitLI, true);
        $_jlj0f = substr($_Q6Q1C["RedirectURL"], 0, strpos_reverse($_Q6Q1C["RedirectURL"], '/', -1) + 1);

        for($_Q6llo=0; $_Q6llo<count($_jitLI); $_Q6llo++) {
          $_Q6ICj = str_replace('"'.$_jitLI[$_Q6llo], '"'.$_jlj0f.$_jitLI[$_Q6llo], $_Q6ICj);
          $_Q6ICj = str_replace("'".$_jitLI[$_Q6llo], "'".$_jlj0f.$_jitLI[$_Q6llo], $_Q6ICj);
        }

        // change links
        $_QOLIl = array();
        $_JjoOL = array();

        // href
        preg_match_all('/[ \r\n]href\=([\"\']*)(.*?)\1[\s\/>]/is', $_Q6ICj, $_JjOII, PREG_SET_ORDER);
        for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
          if( !preg_match("/^http:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/^https:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/^javascript:/i", $_JjOII[$_Q6llo][2]) && !preg_match("/^chrome:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/^mailto:/i", $_JjOII[$_Q6llo][2]) )
               $_JjoOL[] = $_JjOII[$_Q6llo][2];
        }

        // only unique links
        $_QllO8 = array_unique($_JjoOL);
        reset($_QllO8);
        foreach ($_QllO8 as $key => $_Q6ClO)
          $_QOLIl[] = $_Q6ClO;

        for($_Q6llo=0; $_Q6llo<count($_QOLIl); $_Q6llo++) {
          if($_QOLIl[$_Q6llo] == "/") continue; // ignore this links
          $_Q6ICj = str_replace('href="'.$_QOLIl[$_Q6llo], 'href="'.$_jlj0f.$_QOLIl[$_Q6llo], $_Q6ICj);
        }

      }
    } else {

       $_Q6ICj = $_Q6Q1C["HTMLPage"];

       // javascript:history.back();
       if( (defined('DefaultNewsletterPHP') ) && strpos($_Q6ICj, "javascript:history.back();") !== false) {
         reset($_POST);
         $_6jolo = "";
         foreach($_POST As $key => $_Q6ClO) {
           if($key == "Action" || $key == "g-recaptcha-response") continue;
             if(is_array($_Q6ClO))
               $_Q6ClO = implode(",", $_Q6ClO);
             if($_6jolo == "")
                $_6jolo = $key."=".rawurlencode($_Q6ClO);
                else
                $_6jolo .= "&".$key."=".rawurlencode($_Q6ClO);
         }
         if($_6jolo != "")
            if( defined('DefaultNewsletterPHP') )
              $_Q6ICj = str_replace("javascript:history.back();", (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?".$_6jolo, $_Q6ICj);
             /* else it's not possible
              $_Q6ICj = str_replace("javascript:history.back();", $_jJ088."?".$_6jolo, $_Q6ICj); */

       }
    }

    if(is_array($errors) && count($errors) ){
      $_Q6ICj = _OPR6L($_Q6ICj, "[ERRORPAGEMESSAGE]", "[ERRORPAGEMESSAGE]", join("", $errors)."<br />");
      $_Q6ICj = str_replace("</body", "<!-- ERROR: ".join("", $errors)." //-->"."</body", $_Q6ICj);
    }

    if(isset($_j6ioL[$_6jtil])) {
      $_Q6ICj = _OPR6L($_Q6ICj, "[PAGEMESSAGE]", "[PAGEMESSAGE]", $_j6ioL[$_6jtil]);
      $_Q6ICj = str_replace("</body", "<!-- MESSAGETEXT: ".$_j6ioL[$_6jtil]." //-->"."</body", $_Q6ICj);
    }

    _LJ81E($_Q6ICj);

    if(!is_array($_6j8tt) && intval($_6j8tt) != 0) {
      $_QJlJ0 = "SELECT * FROM `$_QlQC8` WHERE id=".intval($_6j8tt);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
        $_jiJC0 = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);
      }
       else
         $_jiJC0 = array();
      $_Q6ICj = _L1ERL($_jiJC0, $_j6ioL["MailingListId"], $_Q6ICj, $_6jOOo, true, array());
      $_6j8tt = $_jiJC0;
    } elseif (is_array($_6j8tt)) {
      $_Q6ICj = _L1ERL($_6j8tt, $_j6ioL["MailingListId"], $_Q6ICj, $_6jOOo, true, array());
    }

    // UnsubscribeBridgePage
    if($_6jtQj == "UnsubscribeBridgePage"){
       $_Q6ICj = str_ireplace("<form", '<form method="post" action="'.(!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il).'" ', $_Q6ICj);
       $_Qi8If = array_merge($_POST, $_GET);
       $_Qi8If["Action"] = "unsubscribe";
       $_Qi8If["unsubscribeconfirmation"] = "unsubscribeconfirmation";
       reset($_Qi8If);
       $_IJ0Q8 = "";
       foreach($_Qi8If as $key => $_Q6ClO){
         $_IJ0Q8 .= sprintf('<input type="hidden" name="%s" value="%s" />', htmlentities($key), htmlentities($_Q6ClO));
       }
       $_Q6ICj = str_ireplace("</form", $_IJ0Q8."</form", $_Q6ICj);
    }

    if($_6jtQj == "UnsubscribeConfirmationPage" && is_array($_6j8tt) && count($_6j8tt) ){
       $_Q6ICj = _L0R8D($_j6ioL, $_Q6ICj, $_6j8tt);
    }

    SetHTMLHeaders($_Q6QQL);

    print $_Q6ICj;
    exit;
  }

  function _L08DC($_QLitI, $_Ql00j, $_jiJC0, $_6jCO1, $_QLl1Q, $_6jJj6 = ""){
    global $AppName, $_Q61I1;

    $_J660i = "";
    if($_6jCO1 == "subscribe")
       $_J660i = $_QLl1Q["ExternalSubscriptionScript"];
    if($_6jCO1 == "unsubscribe")
       $_J660i = $_QLl1Q["ExternalUnsubscriptionScript"];
    if($_6jCO1 == "edit" || $_6jCO1 == "editconfirm")
       $_J660i = $_QLl1Q["ExternalEditScript"];
    if($_6jCO1 == "ReasonForUnsubscriptionVote")
       $_J660i = $_QLl1Q["ExternalReasonForUnsubscriptionScript"];
    if($_J660i == "") return;


    $_j88of = 0;
    $_j8t8L = "";
    $_j8O60 = 80;
    if(strpos($_J660i, "http://") !== false) {
       $_j8O8t = substr($_J660i, 7);
    } elseif(strpos($_J660i, "https://") !== false) {
      $_j8O60 = 443;
      $_j8O8t = substr($_J660i, 8);
    }
    $_QCoLj = substr($_j8O8t, strpos($_j8O8t, "/"));
    $_j8O8t = substr($_j8O8t, 0, strpos($_j8O8t, "/"));

   if(count($_jiJC0) == 0 && $_QLitI != 0) {
      $_QJlJ0 = "SELECT * FROM `$_Ql00j[MaillistTableName]` WHERE id=".intval($_QLitI);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_jiJC0 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
   }

   if($_6jCO1 == "ReasonForUnsubscriptionVote"){
     $_Qf1i1 = "AppName=$AppName";
     foreach($_jiJC0 as $key => $_Q6ClO)
       $_Qf1i1 .= "&$key=".rawurlencode($_Q6ClO);
   }
   else
   if( ($_6jCO1 == "edit" || $_6jCO1 == "editconfirm") && $_6jJj6 != "")
      $_Qf1i1 = "AppName=$AppName&EMail=$_6jJj6&Type=$_6jCO1&NewEMail=$_jiJC0[u_EMail]";
      else
      $_Qf1i1 = "AppName=$AppName&EMail=$_jiJC0[u_EMail]&Type=$_6jCO1";

    _OCQDE($_j8O8t, "GET", $_QCoLj, $_Qf1i1, 0, $_j8O60, false, "", "", $_j88of, $_j8t8L);

  }

?>

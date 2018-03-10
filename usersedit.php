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
  $_I01C0 = Array ();
  _OA61D($_Q8f1L, $_I01C0, array("IsActive", "TermsOfUseAccepted", "Reference"));

  $_I01lt = Array ();

  $errors = array();
  $_QlLfl = 0;

  if(isset($_POST['OwnAccount'])){
    $_POST['UserId'] = $UserId;
  }

  if(isset($_POST['UserId'])) // Formular speichern?
    $_QlLfl = intval($_POST['UserId']);
  else
    if ( isset($_POST['OneUserId']) )
       $_QlLfl = intval($_POST['OneUserId']);

  if($OwnerUserId != 0 && !isset($_POST['OwnAccount'])) {
    $_QJojf = _OBOOC($UserId);
    if($_QlLfl == 0 && !$_QJojf["PrivilegeUserCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_QlLfl != 0 && !$_QJojf["PrivilegeUserEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";

  if(isset($_POST['UserEditBtn'])) { // Formular speichern?

    if($UserType == "SuperAdmin")
       if(empty($_POST["ProductLogoURL"]))
         $_POST["ProductLogoURL"] = "";

    // Pflichtfelder pruefen
    if ( $_QlLfl == 0 && ((!isset($_POST['Username'])) || (trim($_POST['Username']) == "")) )
      $errors[] = 'Username';
      else
       if($_QlLfl == 0)
          if( !preg_match("/^[a-zA-Z0-9_]{3,}$/", $_POST["Username"]) ) {
            $errors[] = "Username";
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090003"];
          }


    if ( (!isset($_POST['Password'])) || (trim($_POST['Password']) == "") )
      $errors[] = 'Password';

    if($_QlLfl == 0) { // only new users
       if ( (!isset($_POST['PasswordAgain'])) || (trim($_POST['PasswordAgain']) == "") )
         $errors[] = 'PasswordAgain';

       if(count($errors) == 0) {
         if(trim($_POST["Password"]) != trim($_POST["PasswordAgain"])) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090001"];
           $errors[] = "Password";
           $errors[] = "PasswordAgain";
         }
       }

       if(count($errors) == 0) {
         if(trim($_POST["Password"]) == "*PASSWORDSET*" ) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090001"];
           $errors[] = "Password";
           $errors[] = "PasswordAgain";
         }
       }

    }

    if ( (!isset($_POST['EMail'])) || (trim($_POST['EMail']) == "") || !_OPAOJ($_POST['EMail']) )
      $errors[] = 'EMail';

    if(($UserType == "SuperAdmin" || $UserType == "Admin")) {
      if ( (!isset($_POST['Language'])) || (trim($_POST['Language']) == "") )
        $errors[] = 'Language';

      if ( (!isset($_POST['ThemesId'])) || (trim($_POST['ThemesId']) == "") )
        $errors[] = 'ThemesId';
        else
        $_POST['ThemesId'] = intval($_POST['ThemesId']);


      if( ($UserType == "SuperAdmin" || isset($_POST['OwnAccount'])) && !($UserType == "SuperAdmin" && isset($_POST['OwnAccount']))  ){

        if ( empty($_POST["LimitSubUnsubScripts"]) )
          $errors[] = 'LimitSubUnsubScripts';

        if ( !empty($_POST["LimitSubUnsubScripts"]) && $_POST["LimitSubUnsubScripts"] == "Limited" ){
          if(empty($_POST["LimitSubUnsubScriptsLimitedRequests"])){
            $_POST["LimitSubUnsubScriptsLimitedRequests"] = 10;
            $errors[] = 'LimitSubUnsubScriptsLimitedRequests';
          }
          $_POST["LimitSubUnsubScriptsLimitedRequests"] = intval($_POST["LimitSubUnsubScriptsLimitedRequests"]);
          if($_POST["LimitSubUnsubScriptsLimitedRequests"] <= 0)
             $_POST["LimitSubUnsubScriptsLimitedRequests"] = 10;

          if(empty($_POST["LimitSubUnsubScriptsLimitedRequestsInterval"]))
            $_POST["LimitSubUnsubScriptsLimitedRequestsInterval"] = "Hour";
        }
      }

    } else {
      $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$UserId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_POST["Language"] = $_Q6Q1C["Language"];
      $_POST["ThemesId"] = $_Q6Q1C["ThemesId"];
      $_POST["SHOW_LOGGEDINUSER"] = $_Q6Q1C["SHOW_LOGGEDINUSER"];
      $_POST["SHOW_SUPPORTLINKS"] = $_Q6Q1C["SHOW_SUPPORTLINKS"];
      $_POST["SHOW_SHOWCOPYRIGHT"] = $_Q6Q1C["SHOW_SHOWCOPYRIGHT"];
      $_POST["SHOW_PRODUCTVERSION"] = $_Q6Q1C["SHOW_PRODUCTVERSION"];
      $_POST["SHOW_TOOLTIPS"] = $_Q6Q1C["SHOW_TOOLTIPS"];
      $_POST["ProductLogoURL"] = $_Q6Q1C["ProductLogoURL"];
      $_POST["LimitSubUnsubScripts"] = $_Q6Q1C["LimitSubUnsubScripts"];
      $_POST["LimitSubUnsubScriptsLimitedRequests"] = $_Q6Q1C["LimitSubUnsubScriptsLimitedRequests"];
      $_POST["LimitSubUnsubScriptsLimitedRequestsInterval"] = $_Q6Q1C["LimitSubUnsubScriptsLimitedRequestsInterval"];
    }

    if(!isset($_POST['OwnAccount']))
      if ( (!isset($_POST['UserType'])) || (trim($_POST['UserType']) == "") )
        $errors[] = 'UserType';

    if(defined("SWM")) {
      if(!isset($_POST['OwnAccount']) && $UserType == "SuperAdmin" && empty($_POST['AccountType']))
          $errors[] = 'AccountType';
      if(!empty($_POST['AccountType']) && $UserType == "SuperAdmin" && !isset($_POST['OwnAccount'])) {
        if($_POST['AccountType'] == "Limited" && (empty($_POST["AccountTypeLimitedMailCountLimited"]) || $_POST["AccountTypeLimitedMailCountLimited"] <= 0) )
            $errors[] = 'AccountTypeLimitedMailCountLimited';
      }
    }

    if(count($errors) == 0 && $_QlLfl == 0) {

      $_QJlJ0 = "SELECT COUNT(*) FROM $_Q8f1L WHERE Username="._OPQLR(trim($_POST['Username']));
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      if($_Q6Q1C[0] > 0) {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000104"];
        $errors[] = 'Username';
      }
      mysql_free_result($_Q60l1);
    }

    if(count($errors) > 0) {
       if($_I0600 == "")
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000103"];

      }
      else {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        $_II1Ot = $_POST;

        // only SuperAdmin set all privileges
        if( $UserType == "SuperAdmin" )
          for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++) {
            if( strpos($_I01C0[$_Q6llo], "Privilege") !== false && !isset($_II1Ot[$_I01C0[$_Q6llo]]) )
               $_II1Ot[$_I01C0[$_Q6llo]] = 1;
          }

        // own account can't change own rights
        if(isset($_POST['OwnAccount'])) {
          $_jQjOO = array();
          for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
            if(strpos($_I01C0[$_Q6llo], "Privilege") === false) {
              $_jQjOO[] = $_I01C0[$_Q6llo];
            }

          unset($_I01C0);
          $_I01C0 = array();
          for($_Q6llo=0; $_Q6llo<count($_jQjOO); $_Q6llo++)
             $_I01C0[] = $_jQjOO[$_Q6llo];
        }

        $_Ql1O8 = array();
        if(!defined("DEMO")) {
            if(isset($_II1Ot["Password"]) && $_II1Ot["Password"] == "*PASSWORDSET*")
               unset($_II1Ot["Password"]);
            if(isset($_II1Ot["apikey"]))
              unset($_II1Ot["apikey"]);
            _L6O1Q($_QlLfl, $_II1Ot, $_Ql1O8);

            if(isset($_POST['OwnAccount'])) {
               $_QJlJ0 = "SELECT $_Q8f1L.*, $_Q880O.Theme, $_Q880O.id As ThemesId FROM $_Q8f1L LEFT JOIN $_Q880O ON $_Q880O.id=$_Q8f1L.ThemesId WHERE $_Q8f1L.id=$UserId";
               $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
               $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
               mysql_free_result($_Q60l1);
               $_SESSION["Language"] = $_Q6Q1C["Language"];
               $_SESSION["Theme"] = $_Q6Q1C["Theme"];
               $_SESSION["ThemesId"] = $_Q6Q1C["ThemesId"];
               $_SESSION["SHOW_LOGGEDINUSER"] = $_Q6Q1C["SHOW_LOGGEDINUSER"];
               $_SESSION["SHOW_SUPPORTLINKS"] = $_Q6Q1C["SHOW_SUPPORTLINKS"];
               $_SESSION["SHOW_SHOWCOPYRIGHT"] = $_Q6Q1C["SHOW_SHOWCOPYRIGHT"];
               $_SESSION["SHOW_PRODUCTVERSION"] = $_Q6Q1C["SHOW_PRODUCTVERSION"];
               $_SESSION["SHOW_TOOLTIPS"] = $_Q6Q1C["SHOW_TOOLTIPS"];
               if($_Q6Q1C["ProductLogoURL"] != "")
                 $_SESSION["ProductLogoURL"] = $_Q6Q1C["ProductLogoURL"];
               LoadUserSettings();
            }

          }
          else {
            $_Ql1O8[] = "DEMO VERSION";
            $_I0600 = "";
          }
        if(count($_Ql1O8) > 0)
           $_I0600 .= "<br />".join("<br />", $_Ql1O8);
        if($_QlLfl != 0) {
           $_POST["UserId"] = $_QlLfl;
           if(isset($_II1Ot["Username"]))
             $_POST["ShowUsername"] = $_II1Ot["Username"];
        }
      }

  }

  if(!isset($_POST['OwnAccount']))
     $_QJCJi = $resourcestrings[$INTERFACE_LANGUAGE]["000100"];
     else
     $_QJCJi = $resourcestrings[$INTERFACE_LANGUAGE]["000107"];

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_QJCJi, $_I0600, 'useredit', 'usersedit_snipped.htm');

  // UserType
  $_Q6ICj = "";
  if($UserType == "SuperAdmin")
     $_Q6ICj .= '<option value="Admin">'.$resourcestrings[$INTERFACE_LANGUAGE]["Admin"].'</option>'.$_Q6JJJ;
     else {
       $_Q6ICj .= '<option value="User">'.$resourcestrings[$INTERFACE_LANGUAGE]["User"].'</option>'.$_Q6JJJ;
     }
  $_QJCJi = _OPR6L($_QJCJi, '<SHOW:USERTYPE>', '</SHOW:USERTYPE>', $_Q6ICj);
  // *************

  // Language
  $_QJlJ0 = "SELECT * FROM $_Qo6Qo";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6ICj = "";
  while($_Q6Q1C  = mysql_fetch_assoc($_Q60l1)) {
     if($_Q6Q1C["Language"] != "de") {

       $_QCoLj = DefaultPath.TemplatesPath."/";

       if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
         $_QCoLj .= $INTERFACE_STYLE."/";
       $_QCoLj .= $_Q6Q1C["Language"]."/";

       $_Q8otJ = @file($_QCoLj."main.htm");
       if(!$_Q8otJ) continue;

     }
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

  _LJ81E($_QJCJi);

  # User laden
  if(isset($_POST['UserEditBtn'])) { // Formular speichern?
    $_6OftJ = $_POST;
    if(isset($_6OftJ["ShowUsername"]) && $_6OftJ["ShowUsername"] != "")
       $_6OftJ["Username"] = $_6OftJ["ShowUsername"];

     if(isset($_POST['OwnAccount'])) {
       $_QJlJ0= "SELECT `apikey`, `AccountType`, `AccountTypeLimitedMailCountLimited`, `AccountTypeLimitedCurrentMonth`, `AccountTypeLimitedCurrentMailCount`, MONTH(NOW()) AS CurrentMonth FROM $_Q8f1L WHERE id=$_QlLfl";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $_Q6Q1C=mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);

       $_6OftJ = array_merge($_6OftJ, $_Q6Q1C);
     } else{
       $_QJlJ0= "SELECT `apikey` FROM $_Q8f1L WHERE id=$_QlLfl";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $_Q6Q1C=mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);

       if(is_array($_Q6Q1C))
          $_6OftJ = array_merge($_6OftJ, $_Q6Q1C);
     }

     if(isset($_6OftJ["Password"]) && count($errors) == 0)
        $_6OftJ["Password"] = "*PASSWORDSET*";

  } else {
    if($_QlLfl > 0) {
      $_QJlJ0= "SELECT *, MONTH(NOW()) AS CurrentMonth FROM $_Q8f1L WHERE id=$_QlLfl";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_6OftJ=mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      // own account can't change own rights
      if(isset($_POST['OwnAccount'])) {
        $_jQjOO = array();
        for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
          if(strpos($_I01C0[$_Q6llo], "Privilege") === false) {
            $_jQjOO[] = $_I01C0[$_Q6llo];
          }

        unset($_I01C0);
        $_I01C0 = array();
        for($_Q6llo=0; $_Q6llo<count($_jQjOO); $_Q6llo++)
           $_I01C0[] = $_jQjOO[$_Q6llo];
        $_6OftJ["OwnAccount"] = 1;
      }


      for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
        if(isset($_6OftJ[$_I01C0[$_Q6llo]]) && $_6OftJ[$_I01C0[$_Q6llo]] == 0)
           unset($_6OftJ[$_I01C0[$_Q6llo]]);

      $_6OftJ["UserId"] = $_6OftJ["id"];
      $_6OftJ["ShowUsername"] = $_6OftJ["Username"];
      $_6OftJ["Password"] = "*PASSWORDSET*";

    } else {
      $_6OftJ = array();
      for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
        $_6OftJ[$_I01C0[$_Q6llo]] = 1;
      if($UserType == "SuperAdmin")
        $_6OftJ["UserType"] = "Admin";
      else
        $_6OftJ["UserType"] = "User";
      $_6OftJ["AccountType"] = "Unlimited";
      $_6OftJ["LimitSubUnsubScripts"] = "Limited";
      $_6OftJ["LimitSubUnsubScriptsLimitedRequests"] = "10";
    }
  }

  // default Limit
  if(!isset($_6OftJ["AccountTypeLimitedMailCountLimited"]))
    $_6OftJ["AccountTypeLimitedMailCountLimited"] = 1000;

  if($_QlLfl == 0) { // only new users
    $_QJCJi = str_replace("<IS:NEWUSER>", "", $_QJCJi);
    $_QJCJi = str_replace("</IS:NEWUSER>", "", $_QJCJi);
  } else {
    $_QJCJi = _OPR6L($_QJCJi, '<IS:NEWUSER>', '</IS:NEWUSER>', '');
  }

  if(empty($_6OftJ["apikey"]))
    $_6OftJ["apikey"] = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
  $_QJCJi = _OPR6L($_QJCJi, '<apikey>', '</apikey>', $_6OftJ["apikey"]);

  if($UserType == "SuperAdmin" && isset($_POST['OwnAccount'])){
    $_QJCJi = _OPR6L($_QJCJi, '<SHOW:LIMITS>', '</SHOW:LIMITS>', '');
    $_QJCJi = _OPR6L($_QJCJi, '<IS:NOTSUPERADMINOWNACCOUNT>', '</IS:NOTSUPERADMINOWNACCOUNT>', '');
  } else {
    if(isset($_6OftJ["UserType"]) && $_6OftJ["UserType"] == "User")
      $_QJCJi = _OPR6L($_QJCJi, '<IS:NOTSUPERADMINOWNACCOUNT>', '</IS:NOTSUPERADMINOWNACCOUNT>', '');
    else{
      $_QJCJi = str_replace("<IS:NOTSUPERADMINOWNACCOUNT>", "", $_QJCJi);
      $_QJCJi = str_replace("</IS:NOTSUPERADMINOWNACCOUNT>", "", $_QJCJi);
    }
  }

  if($UserType == "Admin" && isset($_POST['OwnAccount'])){
     if($_6OftJ["AccountType"] == "Unlimited") {
        $_QJCJi = _OPR6L($_QJCJi, "<AccountType:Limited>", "</AccountType:Limited>", "");
        $_QJCJi = _OPR6L($_QJCJi, "<AccountType:Payed>", "</AccountType:Payed>", "");
        $_QJCJi = str_replace("<AccountType:Unlimited>", "", $_QJCJi);
        $_QJCJi = str_replace("</AccountType:Unlimited>", "", $_QJCJi);
     } else
     if($_6OftJ["AccountType"] == "Limited") {
        $_QJCJi = _OPR6L($_QJCJi, "<AccountType:Unlimited>", "</AccountType:Unlimited>", "");
        $_QJCJi = _OPR6L($_QJCJi, "<AccountType:Payed>", "</AccountType:Payed>", "");
        $_QJCJi = str_replace("<AccountType:Limited>", "", $_QJCJi);
        $_QJCJi = str_replace("</AccountType:Limited>", "", $_QJCJi);

        $_QJCJi = _OPR6L($_QJCJi, "<AccountTypeLimitedMailCountLimited>", "</AccountTypeLimitedMailCountLimited>", $_6OftJ["AccountTypeLimitedMailCountLimited"]);
        $_QllO8 = $_6OftJ["AccountTypeLimitedCurrentMailCount"];
        if($_6OftJ["CurrentMonth"] != $_6OftJ["AccountTypeLimitedCurrentMonth"])
          $_QllO8 = 0;
        $_QJCJi = _OPR6L($_QJCJi, "<AccountTypeLimitedCurrentMailCount>", "</AccountTypeLimitedCurrentMailCount>", $_QllO8);
     } else
     if($_6OftJ["AccountType"] == "Payed") {
        $_QJCJi = _OPR6L($_QJCJi, "<AccountType:Unlimited>", "</AccountType:Unlimited>", "");
        $_QJCJi = _OPR6L($_QJCJi, "<AccountType:Limited>", "</AccountType:Limited>", "");
        $_QJCJi = str_replace("<AccountType:Payed>", "", $_QJCJi);
        $_QJCJi = str_replace("</AccountType:Payed>", "", $_QJCJi);


     }

  } else
    if($UserType == "User")
      $_QJCJi = _OPR6L($_QJCJi, "<ISUSER:ADMIN>", "</ISUSER:ADMIN>", "");


  $_QJCJi = str_replace("<SHOW:LIMITS>", "", $_QJCJi);
  $_QJCJi = str_replace("</SHOW:LIMITS>", "", $_QJCJi);

  if(!isset($_6OftJ["LimitSubUnsubScriptsLimitedRequests"]))
    $_6OftJ["LimitSubUnsubScriptsLimitedRequests"] = 10;

  $_QJCJi = _OPFJA($errors, $_6OftJ, $_QJCJi);

  $_II6C6 = "";
  $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_II6C6, $_QJCJi);

  if($UserType == "SuperAdmin" || isset($_POST['OwnAccount']) || (defined("SWM") && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90) ) ) {
     $_QJCJi = _OPR6L($_QJCJi, "<USER_RIGHTS>", "</USER_RIGHTS>", "");
  } else {
     $_QJCJi = str_replace("<USER_RIGHTS>", "", $_QJCJi);
     $_QJCJi = str_replace("</USER_RIGHTS>", "", $_QJCJi);


     $_QfoQo = _OP81D($_QJCJi, "<CopyPermissionsFromUsers_items>", "</CopyPermissionsFromUsers_items>");
     $_Q66jQ = "";

     if($OwnerUserId == 0) // ist es ein Admin?
        $_j0j6C = $UserId;
        else
        $_j0j6C = $OwnerUserId;

     $_QJlJ0 = "SELECT DISTINCT `id`, `Username` FROM `$_Q8f1L` LEFT JOIN `$_QLtQO` ON `$_QLtQO`.`users_id`= `id` WHERE `owner_id` = $_j0j6C AND `id`<>$_QlLfl AND `UserType`='User'";

     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       $_Q66jQ .= $_QfoQo;
       $_Q66jQ = _OPR6L($_Q66jQ, '<CopyPermissionsFromUsers_id>', '</CopyPermissionsFromUsers_id>', $_Q6Q1C["id"]);
       $_Q66jQ = _OPR6L($_Q66jQ, '<CopyPermissionsFromUsers_name>', '</CopyPermissionsFromUsers_name>', $_Q6Q1C["Username"]);
     }
     mysql_free_result($_Q60l1);
     if(empty($_Q66jQ))
       $_QJCJi = _OP6PQ($_QJCJi, '<CopyPermissionsFromUsers_table>', '</CopyPermissionsFromUsers_table>');
       else {
        $_Q66jQ = $_QfoQo.$_Q66jQ;
        $_Q66jQ = _OPR6L($_Q66jQ, '<CopyPermissionsFromUsers_id>', '</CopyPermissionsFromUsers_id>', "0");
        $_Q66jQ = _OPR6L($_Q66jQ, '<CopyPermissionsFromUsers_name>', '</CopyPermissionsFromUsers_name>', "--");
        $_QJCJi = _OPR6L($_QJCJi, "<CopyPermissionsFromUsers_items>", "</CopyPermissionsFromUsers_items>", $_Q66jQ);
       }
  }

  if(isset($_POST['OwnAccount'])) {
      $_QJCJi = _OPR6L($_QJCJi, '<ISNOT:OWNACCOUNT>', '</ISNOT:OWNACCOUNT>', '');
      $_QJCJi = str_replace("<IS:OWNACCOUNT>", "", $_QJCJi);
      $_QJCJi = str_replace("</IS:OWNACCOUNT>", "", $_QJCJi);
      if( $UserType != "SuperAdmin" ) {
         $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
         $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);
         $_Q6ICj = _LJ6RJ($_Q6ICj, "browseusers.php");
         $_QJCJi = $_IIf8o.$_Q6ICj;
      }
    }
    else {
     $_QJCJi = str_replace("<ISNOT:OWNACCOUNT>", "", $_QJCJi);
     $_QJCJi = str_replace("</ISNOT:OWNACCOUNT>", "", $_QJCJi);
     $_QJCJi = _OPR6L($_QJCJi, '<IS:OWNACCOUNT>', '</IS:OWNACCOUNT>', '');
    }

  if(!($UserType == "SuperAdmin" || $UserType == "Admin")) {
    $_QJCJi = _OP6PQ($_QJCJi, "<IS:NOTUSER>", "</IS:NOTUSER>");

    $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$UserId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    reset($_Q6Q1C);
    foreach($_Q6Q1C as $key => $_Q6ClO) {
      if(strpos($key, "Privilege") !== false && $_Q6ClO == 0) {
         $_QJCJi = _LJ6B1($_QJCJi, $key);
      }
    }


  } else {
     $_QJCJi = str_replace("<IS:NOTUSER>", "", $_QJCJi);
     $_QJCJi = str_replace("</IS:NOTUSER>", "", $_QJCJi);
  }

  $_QJCJi = str_replace("<ISUSER:ADMIN>", "", $_QJCJi);
  $_QJCJi = str_replace("</ISUSER:ADMIN>", "", $_QJCJi);

  if((!empty($_POST["UserType"]) && $_POST["UserType"] == "User"))
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:API>", "</SHOW:API>", "");

  if( ($UserType == "Admin") && !isset($_POST['OwnAccount']))
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:API>", "</SHOW:API>", "");

  if(defined("SML") && $OwnerOwnerUserId == 0x41){
    $_QJCJi = _OPR6L($_QJCJi, "<IS:BASIC>", "</IS:BASIC>", "");
  }

  print $_QJCJi;



  function _L6O1Q(&$_QlLfl, $_Qi8If, &$_Ql1O8) {
    global $_Q8f1L, $_QLtQO, $_I01C0, $_I01lt;
    global $OwnerUserId, $OwnerOwnerUserId, $UserId, $UserType, $_f0jOC;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM `$_Q8f1L`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }
    if (mysql_num_rows($_Q60l1) > 0) {
        while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           foreach ($_Q6Q1C as $key => $_Q6ClO) {
              if($key == "Field") {
                 $_QLLjo[] = $_Q6ClO;
                 break;
              }
           }
        }
        mysql_free_result($_Q60l1);
    }

    // new entry?
    $_fjf6J = false;
    if($_QlLfl == 0) {
      if($UserType == "SuperAdmin") {
        include_once("superadmin.inc.php");
        // it creates admin and tables
        $_QlLfl = _LLEQD(trim($_Qi8If["Username"]), "Admin", $INTERFACE_LANGUAGE);
        if(isset($_f0jOC)) {
          $_Ql1O8 = array_merge($_Ql1O8, $_f0jOC );
        }

      }
      else {
           $_QJlJ0 = "INSERT INTO `$_Q8f1L` (`Username`) VALUES("._OPQLR(trim($_Qi8If["Username"])).")";
           mysql_query($_QJlJ0, $_Q61I1);
           _OAL8F($_QJlJ0);

           $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
           $_Q6Q1C=mysql_fetch_array($_Q60l1);
           $_QlLfl = $_Q6Q1C[0];
           mysql_free_result($_Q60l1);
        }
      $_fjf6J = true;
    }


    $_QJlJ0 = "UPDATE `$_Q8f1L` SET ";
    $_I1l61 = array();
    for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
      $key = $_QLLjo[$_Q6llo];
      if(defined("SWM"))
        if( ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90) && strpos($key, "Privilege") !== false) continue;
      if ( isset($_Qi8If[$_QLLjo[$_Q6llo]]) ) {
        if(in_array($key, $_I01C0))
          if( $_Qi8If[$key] == "1" || intval($_Qi8If[$key]) == 0 )
             $_I1l61[] = "`$key`=1";
             else
              ;
        else {
          if($key != "Password")
            $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]) );
            else {
              $_QlLOL = _OC1CF();
              $_I1l61[] = "`$key`=CONCAT("._OPQLR($_QlLOL).", PASSWORD("._OPQLR($_QlLOL.trim($_Qi8If[$key]) ).") )";
            }
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
    $_QJlJ0 .= " WHERE id=$_QlLfl";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }

    if($_fjf6J  && $UserType != "SuperAdmin") {
      if($OwnerUserId == 0) // Admin?
         $_j0j6C = $UserId;
         else
         $_j0j6C = $OwnerUserId;
      $_QJlJ0 = "INSERT INTO `$_QLtQO` SET `users_id`=$_QlLfl, `owner_id`=$_j0j6C";
      mysql_query($_QJlJ0, $_Q61I1);
    }

  }

?>

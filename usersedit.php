<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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
  $_ItI0o = Array ();
  _L8FCO($_I18lo, $_ItI0o, array("IsActive", "TermsOfUseAccepted", "Reference"));

  $_ItIti = Array ();

  $errors = array();
  $_I8l8o = 0;

  if(isset($_POST['OwnAccount'])){
    $_POST['UserId'] = $UserId;
  }

  if(isset($_POST['UserId'])) // Formular speichern?
    $_I8l8o = intval($_POST['UserId']);
  else
    if ( isset($_POST['OneUserId']) )
       $_I8l8o = intval($_POST['OneUserId']);

  if($OwnerUserId != 0 && !isset($_POST['OwnAccount'])) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_I8l8o == 0 && !$_QLJJ6["PrivilegeUserCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_I8l8o != 0 && !$_QLJJ6["PrivilegeUserEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";

  if(isset($_POST['UserEditBtn'])) { // Formular speichern?

    if($UserType == "SuperAdmin")
       if(empty($_POST["ProductLogoURL"]))
         $_POST["ProductLogoURL"] = "";

    if(isset($_POST['OwnAccount']) && isset($_POST["Username"]))
      unset($_POST["Username"]);

    // Pflichtfelder pruefen
    if ( $_I8l8o == 0 && ((!isset($_POST['Username'])) || (trim($_POST['Username']) == "")) )
      $errors[] = 'Username';
      else
       if($_I8l8o == 0)
          if( !preg_match("/^[a-zA-Z0-9_@]{3,}$/", $_POST["Username"]) ) {
            $errors[] = "Username";
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090003"];
          }

    if($_I8l8o != 0 && !isset($_POST['OwnAccount'])){
       if( !isset($_POST["Username"]) || !preg_match("/^[a-zA-Z0-9_@]{3,}$/", $_POST["Username"]) ) {
         $errors[] = "Username";
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090003"];
       }

       if($_POST["Username"] != $_POST["ShowUsername"]){

         $_QLfol = "SELECT COUNT(id) FROM $_I18lo WHERE Username="._LRAFO($_POST["Username"]);
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_QLO0f = mysql_fetch_row($_QL8i1);
         mysql_free_result($_QL8i1);
         if($_QLO0f[0]){
           $errors[] = "Username";
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000104"];
         }
       }
    }

    if ( (!isset($_POST['Password'])) || (trim($_POST['Password']) == "") )
      $errors[] = 'Password';

    if($_I8l8o == 0) { // only new users
       if ( (!isset($_POST['PasswordAgain'])) || (trim($_POST['PasswordAgain']) == "") )
         $errors[] = 'PasswordAgain';

       if(count($errors) == 0) {
         if(trim($_POST["Password"]) != trim($_POST["PasswordAgain"])) {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090001"];
           $errors[] = "Password";
           $errors[] = "PasswordAgain";
         }
       }

       if(count($errors) == 0) {
         if(trim($_POST["Password"]) == "*PASSWORDSET*" ) {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090001"];
           $errors[] = "Password";
           $errors[] = "PasswordAgain";
         }
       }

    }

    if ( (!isset($_POST['EMail'])) || (trim($_POST['EMail']) == "") || !_L8JLR($_POST['EMail']) )
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
      $_QLfol = "SELECT * FROM $_I18lo WHERE id=$UserId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_POST["Language"] = $_QLO0f["Language"];
      $_POST["ThemesId"] = $_QLO0f["ThemesId"];
      $_POST["SHOW_LOGGEDINUSER"] = $_QLO0f["SHOW_LOGGEDINUSER"];
      $_POST["SHOW_SUPPORTLINKS"] = $_QLO0f["SHOW_SUPPORTLINKS"];
      $_POST["SHOW_SHOWCOPYRIGHT"] = $_QLO0f["SHOW_SHOWCOPYRIGHT"];
      $_POST["SHOW_PRODUCTVERSION"] = $_QLO0f["SHOW_PRODUCTVERSION"];
      $_POST["SHOW_TOOLTIPS"] = $_QLO0f["SHOW_TOOLTIPS"];
      $_POST["ProductLogoURL"] = $_QLO0f["ProductLogoURL"];
      $_POST["LimitSubUnsubScripts"] = $_QLO0f["LimitSubUnsubScripts"];
      $_POST["LimitSubUnsubScriptsLimitedRequests"] = $_QLO0f["LimitSubUnsubScriptsLimitedRequests"];
      $_POST["LimitSubUnsubScriptsLimitedRequestsInterval"] = $_QLO0f["LimitSubUnsubScriptsLimitedRequestsInterval"];
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

    if(count($errors) == 0 && $_I8l8o == 0) {

      $_QLfol = "SELECT COUNT(*) FROM $_I18lo WHERE Username="._LRAFO(trim($_POST['Username']));
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_row($_QL8i1);
      if($_QLO0f[0] > 0) {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000104"];
        $errors[] = 'Username';
      }
      mysql_free_result($_QL8i1);
    }

    if(count($errors) > 0) {
       if($_Itfj8 == "")
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000103"];

      }
      else {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        $_IoLOO = $_POST;

        // only SuperAdmin set all privileges
        if( $UserType == "SuperAdmin" )
          for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++) {
            if( strpos($_ItI0o[$_Qli6J], "Privilege") !== false && !isset($_IoLOO[$_ItI0o[$_Qli6J]]) )
               $_IoLOO[$_ItI0o[$_Qli6J]] = 1;
          }

        // own account can't change own rights
        if(isset($_POST['OwnAccount'])) {
          $_jl0Ii = array();
          for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
            if(strpos($_ItI0o[$_Qli6J], "Privilege") === false) {
              $_jl0Ii[] = $_ItI0o[$_Qli6J];
            }

          unset($_ItI0o);
          $_ItI0o = array();
          for($_Qli6J=0; $_Qli6J<count($_jl0Ii); $_Qli6J++)
             $_ItI0o[] = $_jl0Ii[$_Qli6J];
        }

        $_I816i = array();
        if(!defined("DEMO")) {
            if(isset($_IoLOO["Password"]) && $_IoLOO["Password"] == "*PASSWORDSET*")
               unset($_IoLOO["Password"]);
            if(isset($_IoLOO["apikey"]))
              unset($_IoLOO["apikey"]);

            $_QLfol = "SELECT `AuthType` FROM `$_JQ00O` LIMIT 0,1";
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            $_I1jfC = mysql_fetch_assoc($_QL8i1);
            mysql_free_result($_QL8i1);

            if( isset($_IoLOO["Password"]) && $_I8l8o && $_I1jfC["AuthType"] == "ldap" ){
              if( $UserType !== "SuperAdmin" && isset($_POST['OwnAccount']) ){
                $_Itfj8 .= " " .$resourcestrings[$INTERFACE_LANGUAGE]["PasswordChangeHasNoEffect"];
              }
              else
                if( $UserType == "Admin" ){
                  $_Itfj8 .= " " .$resourcestrings[$INTERFACE_LANGUAGE]["PasswordChangeHasNoEffect"];
                }
            }

            _J6RFL($_I8l8o, $_IoLOO, $_I816i);

            if($_I8l8o !== 0 && !isset($_POST['OwnAccount']) && $_I1jfC["AuthType"] == "db2fa" && !version_compare(PHP_VERSION, "7.1.0", "<") && isset($_POST["2FAResetSecret"])){
              $_QLfol = "UPDATE `$_I18lo` SET `2FASecret`='' WHERE `id`=$_I8l8o"; 
              mysql_query($_QLfol, $_QLttI);
              unset($_POST["2FAResetSecret"]);
            }  
            
            if(isset($_POST['OwnAccount'])) {
               $_QLfol = "SELECT $_I18lo.*, $_I1tQf.Theme, $_I1tQf.id As ThemesId FROM $_I18lo LEFT JOIN $_I1tQf ON $_I1tQf.id=$_I18lo.ThemesId WHERE $_I18lo.id=$UserId";
               $_QL8i1 = mysql_query($_QLfol, $_QLttI);
               $_QLO0f = mysql_fetch_assoc($_QL8i1);
               mysql_free_result($_QL8i1);
               $_SESSION["Language"] = $_QLO0f["Language"];
               $_SESSION["Theme"] = $_QLO0f["Theme"];
               $_SESSION["ThemesId"] = $_QLO0f["ThemesId"];
               $_SESSION["SHOW_LOGGEDINUSER"] = $_QLO0f["SHOW_LOGGEDINUSER"];
               $_SESSION["SHOW_SUPPORTLINKS"] = $_QLO0f["SHOW_SUPPORTLINKS"];
               $_SESSION["SHOW_SHOWCOPYRIGHT"] = $_QLO0f["SHOW_SHOWCOPYRIGHT"];
               $_SESSION["SHOW_PRODUCTVERSION"] = $_QLO0f["SHOW_PRODUCTVERSION"];
               $_SESSION["SHOW_TOOLTIPS"] = $_QLO0f["SHOW_TOOLTIPS"];
               if($_QLO0f["ProductLogoURL"] != "")
                 $_SESSION["ProductLogoURL"] = $_QLO0f["ProductLogoURL"];
               LoadUserSettings();
            }

          }
          else {
            $_I816i[] = "DEMO VERSION";
            $_Itfj8 = "";
          }
        if(count($_I816i) > 0)
           $_Itfj8 .= "<br />".join("<br />", $_I816i);
        if($_I8l8o != 0) {
           $_POST["UserId"] = $_I8l8o;
           if(isset($_IoLOO["Username"]))
             $_POST["ShowUsername"] = $_IoLOO["Username"];
        }
      }

  }

  if(!isset($_POST['OwnAccount']))
     $_QLJfI = $resourcestrings[$INTERFACE_LANGUAGE]["000100"];
     else
     $_QLJfI = $resourcestrings[$INTERFACE_LANGUAGE]["000107"];

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_QLJfI, $_Itfj8, 'useredit', 'usersedit_snipped.htm');

  // UserType
  $_QLoli = "";
  if($UserType == "SuperAdmin")
     $_QLoli .= '<option value="Admin">'.$resourcestrings[$INTERFACE_LANGUAGE]["Admin"].'</option>'.$_QLl1Q;
     else {
       $_QLoli .= '<option value="User">'.$resourcestrings[$INTERFACE_LANGUAGE]["User"].'</option>'.$_QLl1Q;
     }
  $_QLJfI = _L81BJ($_QLJfI, '<SHOW:USERTYPE>', '</SHOW:USERTYPE>', $_QLoli);
  // *************

  // Language
  $_QLfol = "SELECT * FROM $_Ijf8l";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLoli = "";
  while($_QLO0f  = mysql_fetch_assoc($_QL8i1)) {
     if($_QLO0f["Language"] != "de") {

       $_IJL6o = DefaultPath.TemplatesPath."/";

       if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
         $_IJL6o .= $INTERFACE_STYLE."/";
       $_IJL6o .= $_QLO0f["Language"]."/";

       $_I1OoI = @file($_IJL6o."main.htm");
       if(!$_I1OoI) continue;

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

  _JJCCF($_QLJfI);

  # User laden
  if(isset($_POST['UserEditBtn'])) { // Formular speichern?
    $_8IfOt = $_POST;
    if(isset($_8IfOt["ShowUsername"]) && $_8IfOt["ShowUsername"] != "")
       $_8IfOt["Username"] = $_8IfOt["ShowUsername"];

     if(isset($_POST['OwnAccount'])) {
       $_QLfol= "SELECT `apikey`, `AccountType`, `AccountTypeLimitedMailCountLimited`, `AccountTypeLimitedCurrentMonth`, `AccountTypeLimitedCurrentMailCount`, MONTH(NOW()) AS CurrentMonth FROM $_I18lo WHERE id=$_I8l8o";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $_QLO0f=mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);

       $_8IfOt = array_merge($_8IfOt, $_QLO0f);
     } else{
       $_QLfol= "SELECT `apikey` FROM $_I18lo WHERE id=$_I8l8o";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $_QLO0f=mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);

       if(is_array($_QLO0f))
          $_8IfOt = array_merge($_8IfOt, $_QLO0f);
     }

     if(isset($_8IfOt["Password"]) && count($errors) == 0)
        $_8IfOt["Password"] = "*PASSWORDSET*";

  } else {
    if($_I8l8o > 0) {
      $_QLfol= "SELECT *, MONTH(NOW()) AS CurrentMonth FROM $_I18lo WHERE id=$_I8l8o";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_8IfOt=mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      // own account can't change own rights
      if(isset($_POST['OwnAccount'])) {
        $_jl0Ii = array();
        for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
          if(strpos($_ItI0o[$_Qli6J], "Privilege") === false) {
            $_jl0Ii[] = $_ItI0o[$_Qli6J];
          }

        unset($_ItI0o);
        $_ItI0o = array();
        for($_Qli6J=0; $_Qli6J<count($_jl0Ii); $_Qli6J++)
           $_ItI0o[] = $_jl0Ii[$_Qli6J];
        $_8IfOt["OwnAccount"] = 1;
      }


      for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
        if(isset($_8IfOt[$_ItI0o[$_Qli6J]]) && $_8IfOt[$_ItI0o[$_Qli6J]] == 0)
           unset($_8IfOt[$_ItI0o[$_Qli6J]]);

      $_8IfOt["UserId"] = $_8IfOt["id"];
      $_8IfOt["ShowUsername"] = $_8IfOt["Username"];
      $_8IfOt["Password"] = "*PASSWORDSET*";

    } else {
      $_8IfOt = array();
      for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
        $_8IfOt[$_ItI0o[$_Qli6J]] = 1;
      if($UserType == "SuperAdmin")
        $_8IfOt["UserType"] = "Admin";
      else
        $_8IfOt["UserType"] = "User";
      $_8IfOt["AccountType"] = "Unlimited";
      $_8IfOt["LimitSubUnsubScripts"] = "Limited";
      $_8IfOt["LimitSubUnsubScriptsLimitedRequests"] = "10";
    }
  }

  // default Limit
  if(!isset($_8IfOt["AccountTypeLimitedMailCountLimited"]))
    $_8IfOt["AccountTypeLimitedMailCountLimited"] = 1000;

  if($_I8l8o == 0) { // only new users
    $_QLJfI = str_replace("<IS:NEWUSER>", "", $_QLJfI);
    $_QLJfI = str_replace("</IS:NEWUSER>", "", $_QLJfI);
  } else {
    $_QLJfI = _L81BJ($_QLJfI, '<IS:NEWUSER>', '</IS:NEWUSER>', '');
  }

  if(empty($_8IfOt["apikey"]))
    $_8IfOt["apikey"] = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
  $_QLJfI = _L81BJ($_QLJfI, '<apikey>', '</apikey>', $_8IfOt["apikey"]);

  if($UserType == "SuperAdmin" && isset($_POST['OwnAccount'])){
    $_QLJfI = _L81BJ($_QLJfI, '<SHOW:LIMITS>', '</SHOW:LIMITS>', '');
    $_QLJfI = _L81BJ($_QLJfI, '<IS:NOTSUPERADMINOWNACCOUNT>', '</IS:NOTSUPERADMINOWNACCOUNT>', '');
  } else {
    if(isset($_8IfOt["UserType"]) && $_8IfOt["UserType"] == "User")
      $_QLJfI = _L81BJ($_QLJfI, '<IS:NOTSUPERADMINOWNACCOUNT>', '</IS:NOTSUPERADMINOWNACCOUNT>', '');
    else{
      $_QLJfI = str_replace("<IS:NOTSUPERADMINOWNACCOUNT>", "", $_QLJfI);
      $_QLJfI = str_replace("</IS:NOTSUPERADMINOWNACCOUNT>", "", $_QLJfI);
    }
  }

  if($UserType == "Admin" && isset($_POST['OwnAccount'])){
     if($_8IfOt["AccountType"] == "Unlimited") {
        $_QLJfI = _L81BJ($_QLJfI, "<AccountType:Limited>", "</AccountType:Limited>", "");
        $_QLJfI = _L81BJ($_QLJfI, "<AccountType:Payed>", "</AccountType:Payed>", "");
        $_QLJfI = str_replace("<AccountType:Unlimited>", "", $_QLJfI);
        $_QLJfI = str_replace("</AccountType:Unlimited>", "", $_QLJfI);
     } else
     if($_8IfOt["AccountType"] == "Limited") {
        $_QLJfI = _L81BJ($_QLJfI, "<AccountType:Unlimited>", "</AccountType:Unlimited>", "");
        $_QLJfI = _L81BJ($_QLJfI, "<AccountType:Payed>", "</AccountType:Payed>", "");
        $_QLJfI = str_replace("<AccountType:Limited>", "", $_QLJfI);
        $_QLJfI = str_replace("</AccountType:Limited>", "", $_QLJfI);

        $_QLJfI = _L81BJ($_QLJfI, "<AccountTypeLimitedMailCountLimited>", "</AccountTypeLimitedMailCountLimited>", $_8IfOt["AccountTypeLimitedMailCountLimited"]);
        $_I016j = $_8IfOt["AccountTypeLimitedCurrentMailCount"];
        if($_8IfOt["CurrentMonth"] != $_8IfOt["AccountTypeLimitedCurrentMonth"])
          $_I016j = 0;
        $_QLJfI = _L81BJ($_QLJfI, "<AccountTypeLimitedCurrentMailCount>", "</AccountTypeLimitedCurrentMailCount>", $_I016j);
     } else
     if($_8IfOt["AccountType"] == "Payed") {
        $_QLJfI = _L81BJ($_QLJfI, "<AccountType:Unlimited>", "</AccountType:Unlimited>", "");
        $_QLJfI = _L81BJ($_QLJfI, "<AccountType:Limited>", "</AccountType:Limited>", "");
        $_QLJfI = str_replace("<AccountType:Payed>", "", $_QLJfI);
        $_QLJfI = str_replace("</AccountType:Payed>", "", $_QLJfI);


     }

  } else
    if($UserType == "User")
      $_QLJfI = _L81BJ($_QLJfI, "<ISUSER:ADMIN>", "</ISUSER:ADMIN>", "");


  $_QLJfI = str_replace("<SHOW:LIMITS>", "", $_QLJfI);
  $_QLJfI = str_replace("</SHOW:LIMITS>", "", $_QLJfI);

  if(!isset($_8IfOt["LimitSubUnsubScriptsLimitedRequests"]))
    $_8IfOt["LimitSubUnsubScriptsLimitedRequests"] = 10;

  $_QLJfI = _L8AOB($errors, $_8IfOt, $_QLJfI);

  $_ICI0L = "";
  $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_ICI0L, $_QLJfI);

  if($UserType == "SuperAdmin" || isset($_POST['OwnAccount']) || (defined("SWM") && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90) ) ) {
     $_QLJfI = _L81BJ($_QLJfI, "<USER_RIGHTS>", "</USER_RIGHTS>", "");
  } else {
     $_QLJfI = str_replace("<USER_RIGHTS>", "", $_QLJfI);
     $_QLJfI = str_replace("</USER_RIGHTS>", "", $_QLJfI);


     $_I0Clj = _L81DB($_QLJfI, "<CopyPermissionsFromUsers_items>", "</CopyPermissionsFromUsers_items>");
     $_Ql0fO = "";

     if($OwnerUserId == 0) // ist es ein Admin?
        $_joLCQ = $UserId;
        else
        $_joLCQ = $OwnerUserId;

     $_QLfol = "SELECT DISTINCT `id`, `Username` FROM `$_I18lo` LEFT JOIN `$_IfOtC` ON `$_IfOtC`.`users_id`= `id` WHERE `owner_id` = $_joLCQ AND `id`<>$_I8l8o AND `UserType`='User'";

     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       $_Ql0fO .= $_I0Clj;
       $_Ql0fO = _L81BJ($_Ql0fO, '<CopyPermissionsFromUsers_id>', '</CopyPermissionsFromUsers_id>', $_QLO0f["id"]);
       $_Ql0fO = _L81BJ($_Ql0fO, '<CopyPermissionsFromUsers_name>', '</CopyPermissionsFromUsers_name>', $_QLO0f["Username"]);
     }
     mysql_free_result($_QL8i1);
     if(empty($_Ql0fO))
       $_QLJfI = _L80DF($_QLJfI, '<CopyPermissionsFromUsers_table>', '</CopyPermissionsFromUsers_table>');
       else {
        $_Ql0fO = $_I0Clj.$_Ql0fO;
        $_Ql0fO = _L81BJ($_Ql0fO, '<CopyPermissionsFromUsers_id>', '</CopyPermissionsFromUsers_id>', "0");
        $_Ql0fO = _L81BJ($_Ql0fO, '<CopyPermissionsFromUsers_name>', '</CopyPermissionsFromUsers_name>', "--");
        $_QLJfI = _L81BJ($_QLJfI, "<CopyPermissionsFromUsers_items>", "</CopyPermissionsFromUsers_items>", $_Ql0fO);
       }
  }

  if(isset($_POST['OwnAccount'])) {
      $_QLJfI = _L81BJ($_QLJfI, '<ISNOT:OWNACCOUNT>', '</ISNOT:OWNACCOUNT>', '');
      $_QLJfI = str_replace("<IS:OWNACCOUNT>", "", $_QLJfI);
      $_QLJfI = str_replace("</IS:OWNACCOUNT>", "", $_QLJfI);
      if( $UserType != "SuperAdmin" ) {
         $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
         $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);
         $_QLoli = _JJC0E($_QLoli, "browseusers.php");
         $_QLJfI = $_ICIIQ.$_QLoli;
      }
    }
    else {
     $_QLJfI = str_replace("<ISNOT:OWNACCOUNT>", "", $_QLJfI);
     $_QLJfI = str_replace("</ISNOT:OWNACCOUNT>", "", $_QLJfI);
     $_QLJfI = _L81BJ($_QLJfI, '<IS:OWNACCOUNT>', '</IS:OWNACCOUNT>', '');
    }

  if(!($UserType == "SuperAdmin" || $UserType == "Admin")) {
    $_QLJfI = _L80DF($_QLJfI, "<IS:NOTUSER>", "</IS:NOTUSER>");

    $_QLfol = "SELECT * FROM $_I18lo WHERE id=$UserId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    reset($_QLO0f);
    foreach($_QLO0f as $key => $_QltJO) {
      if(strpos($key, "Privilege") !== false && $_QltJO == 0) {
         $_QLJfI = _JJC1E($_QLJfI, $key);
      }
    }


  } else {
     $_QLJfI = str_replace("<IS:NOTUSER>", "", $_QLJfI);
     $_QLJfI = str_replace("</IS:NOTUSER>", "", $_QLJfI);
  }

  $_QLJfI = str_replace("<ISUSER:ADMIN>", "", $_QLJfI);
  $_QLJfI = str_replace("</ISUSER:ADMIN>", "", $_QLJfI);

  if((!empty($_POST["UserType"]) && $_POST["UserType"] == "User"))
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:API>", "</SHOW:API>", "");

  if( ($UserType == "Admin") && !isset($_POST['OwnAccount']))
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:API>", "</SHOW:API>", "");

  if(defined("SML") && $OwnerOwnerUserId == 0x41){
    $_QLJfI = _L81BJ($_QLJfI, "<IS:NOT_SML_BASIC>", "</IS:NOT_SML_BASIC>", "");
  }else
    $_QLJfI = _L8OF8($_QLJfI, "<IS:NOT_SML_BASIC>");


  if($_I8l8o !== 0 && !isset($_POST['OwnAccount']) && !version_compare(PHP_VERSION, "7.1.0", "<")){  
    $_QLfol = "SELECT * FROM `$_JQ00O` LIMIT 0,1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_I1jfC = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if($_I1jfC["AuthType"] !== "db2fa")
      $_QLJfI = _L80DF($_QLJfI, "<IS2F>", "</IS2F>");
      else{
        include_once("login_page.inc.php");
        
        $_QLfol = "SELECT * FROM $_I18lo WHERE id=$_I8l8o";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_j661I = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
        
        if($_j661I["2FASecret"] == ""){
          $_QLJfI = _L80DF($_QLJfI, "<IS2F:SECRETSET>", "</IS2F:SECRETSET>");
          $_QLJfI = _L8OF8($_QLJfI, "<IS2F:NOTSECRETSET>");
        }else{
          $_QLJfI = _L8OF8($_QLJfI, "<IS2F:SECRETSET>");
          $_QLJfI = _L80DF($_QLJfI, "<IS2F:NOTSECRETSET>", "</IS2F:NOTSECRETSET>");
          $_6l8I6 = new _2FA();
          $_QLJfI = str_replace("%SECRET%", $_6l8I6->_LDERB($_j661I), $_QLJfI);
          $_QLJfI = str_replace("%QRCODEURL%", $_6l8I6->_LDF60($_j661I), $_QLJfI);
        }   
        
      }
  }else{
    $_QLJfI = _L80DF($_QLJfI, "<IS2F>", "</IS2F>");
  }  

  print $_QLJfI;



  function _J6RFL(&$_I8l8o, $_I6tLJ, &$_I816i) {
    global $_I18lo, $_IfOtC, $_ItI0o, $_ItIti;
    global $OwnerUserId, $OwnerOwnerUserId, $UserId, $UserType, $_8OQO8;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;

    if(isset($_I6tLJ["2FASecret"]))
      unset($_I6tLJ["2FASecret"]);
    
    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM `$_I18lo`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }
    if (mysql_num_rows($_QL8i1) > 0) {
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           foreach ($_QLO0f as $key => $_QltJO) {
              if($key == "Field") {
                 $_Iflj0[] = $_QltJO;
                 break;
              }
           }
        }
        mysql_free_result($_QL8i1);
    }

    // new entry?
    $_8L68l = false;
    if($_I8l8o == 0) {
      if($UserType == "SuperAdmin") {
        include_once("superadmin.inc.php");
        // it creates admin and tables
        $_I8l8o = _JJQOE(trim($_I6tLJ["Username"]), "Admin", $INTERFACE_LANGUAGE);
        if(isset($_8OQO8)) {
          $_I816i = array_merge($_I816i, $_8OQO8 );
        }

      }
      else {
           $_QLfol = "INSERT INTO `$_I18lo` (`Username`) VALUES("._LRAFO(trim($_I6tLJ["Username"])).")";
           mysql_query($_QLfol, $_QLttI);
           _L8D88($_QLfol);

           $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
           $_QLO0f=mysql_fetch_array($_QL8i1);
           $_I8l8o = $_QLO0f[0];
           mysql_free_result($_QL8i1);
        }
      $_8L68l = true;
    }


    $_QLfol = "UPDATE `$_I18lo` SET ";
    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if(defined("SWM"))
        if( ($OwnerOwnerUserId <= 0x41 || $OwnerOwnerUserId == 0x5A) && strpos($key, "Privilege") !== false) continue;
      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 )
             $_Io01j[] = "`$key`=1";
             else
              ;
        else {
          if($key != "Password")
            $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]) );
            else {
              $_I8li6 = _LAPE1();
              $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;
              if(!$_It0IQ)
                 $_Io01j[] = "`$key`=CONCAT("._LRAFO($_I8li6).", PASSWORD("._LRAFO($_I8li6.trim($_I6tLJ[$key]) ).") )";
                 else
                 $_Io01j[] = "`$key`=CONCAT("._LRAFO($_I8li6).", SHA2("._LRAFO($_I8li6.trim($_I6tLJ[$key]) ).", 224) )";
            }
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
    $_QLfol .= " WHERE id=$_I8l8o";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }

    if($_8L68l  && $UserType != "SuperAdmin") {
      if($OwnerUserId == 0) // Admin?
         $_joLCQ = $UserId;
         else
         $_joLCQ = $OwnerUserId;
      $_QLfol = "INSERT INTO `$_IfOtC` SET `users_id`=$_I8l8o, `owner_id`=$_joLCQ";
      mysql_query($_QLfol, $_QLttI);
    }

  }

?>

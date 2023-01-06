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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeUserBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";
  if (count($_POST) > 1) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }

    $_Ilt8t = !isset($_POST["UsersActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneUserAction"]) && $_POST["OneUserAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneUserId"]) && $_POST["OneUserId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["UsersActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["UsersActions"] == "RemoveUsers") {

          if($OwnerUserId != 0) {
            $_QLJJ6 = _LPALQ($UserId);
            if(!$_QLJJ6["PrivilegeUserRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("users_ops.inc.php");

        // show now the list
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000102"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000101"];
        }

    }

    if( isset($_POST["OneUserAction"]) && isset($_POST["OneUserId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneUserAction"] == "EditUserProperties") {
        include_once("usersedit.php");
        exit;
      }

      if($_POST["OneUserAction"] == "DeleteUser") {

          if($OwnerUserId != 0) {
            $_QLJJ6 = _LPALQ($UserId);
            if(!$_QLJJ6["PrivilegeUserRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

        include_once("users_ops.inc.php");

        // show now the list
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000102"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000101"];
      }
    }

  }

    // set saved values
    if ( (count($_POST) <= 1) ) {
      include_once("savedoptions.inc.php");
      $_joiLL = _JOO1L("BrowseUsersFilter");

      if( $_joiLL != "") {
        $_I016j = @unserialize($_joiLL);
        if($_I016j !== false)
          $_POST = array_merge($_POST, $_I016j);
      }
  }
  
  // List of Users of current user SQL query
  if($UserType != "SuperAdmin") {
    $_joLCQ = $UserId;
    if($OwnerUserId != 0) // kein Admin?
      $_joLCQ = $OwnerUserId;

    $_QLfol = "SELECT {} FROM $_I18lo LEFT JOIN $_IfOtC ON id=users_id WHERE (owner_id=$_joLCQ) AND users_id<>$UserId"; // if user then not user itself
  } else {
    $_QLfol = "SELECT {} FROM $_I18lo WHERE UserType='Admin'";
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000100"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browseusers', 'browse_users_snipped.htm');


  $_QLJfI = _LQEF0($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeUserCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "usersedit.php");
    }
    if(!$_QLJJ6["PrivilegeUserEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditUserProperties");
    }

    if(!$_QLJJ6["PrivilegeUserRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteUser");
      $_QLoli = _JJCRD($_QLoli, "RemoveUsers");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  if( defined("SWM") ) {
    if($UserType != "SuperAdmin" && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90)){
      $_QLJfI = _JJC0E($_QLJfI, "usersedit.php");
    }else{
      if($UserType == "SuperAdmin" && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90) ){
         $_QLlO6 = str_replace('{}', 'COUNT('.$_I18lo.'.id)', $_QLfol);
         $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
         _L8D88($_QLfol);
         $_QLO0f=mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         if( $_QLO0f[0] > 0)
           $_QLJfI = _JJC0E($_QLJfI, "usersedit.php");
      }
    }
  }


  print $_QLJfI;



  function _LQEF0($_QLfol, $_QLoli) {
    global $UserId, $_SESSION, $_I18lo, $UserType, $_QL88I, $_IfOtC;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;
    $_Il0o6 = array();

    // Searchstring
    if( isset( $_POST["UsersSearchFor"] ) && ($_POST["UsersSearchFor"] != "") ) {
      $_Il0o6["UsersSearchFor"] = $_POST["UsersSearchFor"];
      $_IliOC = "LastName";

      if( isset( $_POST["Usersfieldname"] ) && ($_POST["Usersfieldname"] != "") ) {
        $_Il0o6["Usersfieldname"] = $_POST["Usersfieldname"];
        $_I016j = substr($_POST["Usersfieldname"], 10);
        if($_I016j != "All")
          $_IliOC = $_I016j;
          else {
            $_IliOC = "";
            $_Iflj0 = array();
            $_QLlO6 = array();
            $_jC1jJ = _LRAFO('%' . trim($_POST["UsersSearchFor"]) . '%');
            $_QLlO6[] = "(id LIKE " . $_jC1jJ . ")";
            $_QLlO6[] = "(Username LIKE " . $_jC1jJ . ")";
            $_QLlO6[] = "(EMail LIKE " . $_jC1jJ . ")";
            $_QLlO6[] = "(FirstName LIKE " . $_jC1jJ . ")";
            $_QLlO6[] = "(LastName LIKE " . $_jC1jJ . ")";
          }

      }

      if(strpos($_QLfol, " WHERE ") === false)
         $_QLfol .= " WHERE ";
         else
         $_QLfol .= " AND ";
      if($_IliOC != "")
        $_QLfol .= "  (`$_IliOC` LIKE " . _LRAFO('%' . trim($_POST["UsersSearchFor"]) . '%') . ")";
        else
        if(count($_QLlO6) > 0)
          $_QLfol .= " (".join(" OR ", $_QLlO6).")";


    } else {
      $_Il0o6["UsersSearchFor"] = "";
      $_Il0o6["Usersfieldname"] = "SearchFor_LastName";
    }

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["UsersItemsPerPage"])) {
       $_I016j = intval($_POST["UsersItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["UsersItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['UsersPageSelected'])) || ($_POST['UsersPageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['UsersPageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', 'COUNT('.$_I18lo.'.id)', $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneUserId"] ) && ($_POST["OneUserId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneUserId"] ) && ($_POST["OneUserId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneUserId"] ) && ($_POST["OneUserId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneUserId"] ) && ($_POST["OneUserId"] == "End") )
       $_IlQQ6 = $_IlILC;

    if ( ($_IlQQ6 > $_IlILC) || ($_IlQQ6 <= 0) )
       $_IlQQ6 = 1;

    $_Iil6i = ($_IlQQ6 - 1) * $_Il1jO;

    $_QlOjt = "";
    for($_Qli6J=1; $_Qli6J<=$_IlILC; $_Qli6J++)
      if($_Qli6J != $_IlQQ6)
       $_QlOjt .= "<option>$_Qli6J</option>";
       else
       $_QlOjt .= '<option selected="selected">'.$_Qli6J.'</option>';

    $_QLoli = _L81BJ($_QLoli, "<OPTION:PAGES>", "</OPTION:PAGES>", $_QlOjt);

    // Nav-Buttons
    $_Iljoj = "";
    if($_IlQQ6 == 1) {
      $_Iljoj .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_IlQQ6 == $_IlILC) || ($_IlQll == 0) ) {
      $_Iljoj .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_IlQll == 0)
      $_Iljoj .= "  DisableItem('UsersPageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    // Sort
    $_IlJj8 = " ORDER BY Username ASC";
    if( isset( $_POST["Userssortfieldname"] ) && ($_POST["Userssortfieldname"] != "") ) {
      $_Il0o6["Userssortfieldname"] = $_POST["Userssortfieldname"];
      if($_POST["Userssortfieldname"] == "SortUsername")
         $_IlJj8 = " ORDER BY Username";
      if($_POST["Userssortfieldname"] == "SortLastName")
         $_IlJj8 = " ORDER BY LastName";
      if($_POST["Userssortfieldname"] == "SortFirstName")
         $_IlJj8 = " ORDER BY FirstName";
      if($_POST["Userssortfieldname"] == "SortEMail")
         $_IlJj8 = " ORDER BY EMail";
      if($_POST["Userssortfieldname"] == "SortUserType")
         $_IlJj8 = " ORDER BY UserType";
      if($_POST["Userssortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY id";
      if($_POST["Userssortfieldname"] == "SortLastLogin")
         $_IlJj8 = " ORDER BY LastLogin";
      if (isset($_POST["Userssortorder"]) ) {
         $_Il0o6["Userssortorder"] = $_POST["Userssortorder"];
         if($_POST["Userssortorder"] == "ascending")
           $_IlJj8 .= " ASC";
           else
           $_IlJj8 .= " DESC";
         }
    } else {
      $_Il0o6["Userssortfieldname"] = "SortUsername";
      $_Il0o6["Userssortorder"] = "ascending";
    }
    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";


    $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
    if($INTERFACE_LANGUAGE != "de") {
       $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
    }

    $_QLfol = str_replace('{}', "`$_I18lo`.*, DATE_FORMAT(`$_I18lo`.`LastLogin`, $_QLo60) As LastLoginDateTime", $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    $_jCjfI = true;
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:USERNAME>", "</LIST:USERNAME>", $_QLO0f["Username"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LASTNAME>", "</LIST:LASTNAME>", $_QLO0f["LastName"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:FIRSTNAME>", "</LIST:FIRSTNAME>", $_QLO0f["FirstName"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:EMAIL>", "</LIST:EMAIL>", $_QLO0f["EMail"]);
      if(isset($resourcestrings[$INTERFACE_LANGUAGE][$_QLO0f["UserType"]]))
        $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:USERTYPE>", "</LIST:USERTYPE>", $resourcestrings[$INTERFACE_LANGUAGE][$_QLO0f["UserType"]]);
        else
        $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:USERTYPE>", "</LIST:USERTYPE>", $resourcestrings[$INTERFACE_LANGUAGE]["NA"]);

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LASTLOGIN>", "</LIST:LASTLOGIN>", $_QLO0f["LastLoginDateTime"]);
      
        
      $_Ql0fO = str_replace ('name="EditUserProperties"', 'name="EditUserProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteUser"', 'name="DeleteUser" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="UsersIDs[]"', 'name="UsersIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      if($UserType == "SuperAdmin") {
        $_QLfol = "SELECT COUNT(id) FROM $_QL88I WHERE users_id=".$_QLO0f["id"];
        $_It16Q = mysql_query($_QLfol, $_QLttI);
        $_I1OfI = mysql_fetch_row($_It16Q);
        mysql_free_result($_It16Q);
        if($_I1OfI[0]) {
           $_Ql0fO = _L81BJ($_Ql0fO, "<CAN:DELETE>", "</CAN:DELETE>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000105"], $_I1OfI[0]));
           $_jCjfI = false;
        } else {
          $_QLfol = "SELECT COUNT(users_id) FROM $_IfOtC WHERE owner_id=".$_QLO0f["id"];
          $_It16Q = mysql_query($_QLfol, $_QLttI);
          $_I1OfI = mysql_fetch_row($_It16Q);
          mysql_free_result($_It16Q);
          if($_I1OfI[0]) {
             $_Ql0fO = _L81BJ($_Ql0fO, "<CAN:DELETE>", "</CAN:DELETE>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000106"], $_I1OfI[0]));
             $_jCjfI = false;
          }
        }
      }

      $_Ql0fO = str_replace("<CAN:DELETE>", "", $_Ql0fO);
      $_Ql0fO = str_replace("</CAN:DELETE>", "", $_Ql0fO);

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    if(!$_jCjfI) {
       $_QLoli = _L81BJ($_QLoli, "<CAN:DELETE>", "</CAN:DELETE>", "");
    } else {
      $_QLoli = str_replace("<CAN:DELETE>", "", $_QLoli);
      $_QLoli = str_replace("</CAN:DELETE>", "", $_QLoli);
    }

    // save the filter for later use
    if( isset($_POST["UsersSaveFilter"]) ) {
       $_Il0o6["UsersSaveFilter"] = $_POST["UsersSaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseUsersFilter", serialize($_Il0o6) );
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);


    return $_QLoli;
  }

?>

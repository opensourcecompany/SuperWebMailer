<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeUserBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";
  if (count($_POST) != 0) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }

    $_I680t = !isset($_POST["UsersActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneUserAction"]) && $_POST["OneUserAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneUserId"]) && $_POST["OneUserId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["UsersActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["UsersActions"] == "RemoveUsers") {

          if($OwnerUserId != 0) {
            $_QJojf = _OBOOC($UserId);
            if(!$_QJojf["PrivilegeUserRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("users_ops.inc.php");

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000102"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000101"];
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
            $_QJojf = _OBOOC($UserId);
            if(!$_QJojf["PrivilegeUserRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

        include_once("users_ops.inc.php");

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000102"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000101"];
      }
    }

  }

    // set saved values
    if ( (count($_POST) == 0) ) {
      include_once("savedoptions.inc.php");
      $_j0j0O = _LQB6D("BrowseUsersFilter");

      if( $_j0j0O != "") {
        $_QllO8 = @unserialize($_j0j0O);
        if($_QllO8 !== false)
          $_POST = array_merge($_POST, $_QllO8);
      }
  }

  // List of Users of current user SQL query
  if($UserType != "SuperAdmin") {
    $_j0j6C = $UserId;
    if($OwnerUserId != 0) // kein Admin?
      $_j0j6C = $OwnerUserId;

    $_QJlJ0 = "SELECT {} FROM $_Q8f1L LEFT JOIN $_QLtQO ON id=users_id WHERE (owner_id=$_j0j6C) AND users_id<>$UserId"; // if user then not user itself
  } else {
    $_QJlJ0 = "SELECT {} FROM $_Q8f1L WHERE UserType='Admin'";
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000100"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browseusers', 'browse_users_snipped.htm');


  $_QJCJi = _OJCO8($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeUserCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "usersedit.php");
    }
    if(!$_QJojf["PrivilegeUserEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditUserProperties");
    }

    if(!$_QJojf["PrivilegeUserRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteUser");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveUsers");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  if( defined("SWM") ) {
    if($UserType != "SuperAdmin" && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90)){
      $_QJCJi = _LJ6RJ($_QJCJi, "usersedit.php");
    }else{
      if($UserType == "SuperAdmin" && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90) ){
         $_QtjtL = str_replace('{}', 'COUNT('.$_Q8f1L.'.id)', $_QJlJ0);
         $_Q60l1 = mysql_query($_QtjtL);
         _OAL8F($_QJlJ0);
         $_Q6Q1C=mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         if( $_Q6Q1C[0] > 0)
           $_QJCJi = _LJ6RJ($_QJCJi, "usersedit.php");
      }
    }
  }


  print $_QJCJi;



  function _OJCO8($_QJlJ0, $_Q6ICj) {
    global $UserId, $_SESSION, $_Q8f1L, $UserType, $_Q60QL, $_QLtQO;
    global $resourcestrings, $INTERFACE_LANGUAGE;
    $_I61Cl = array();

    // Searchstring
    if( isset( $_POST["UsersSearchFor"] ) && ($_POST["UsersSearchFor"] != "") ) {
      $_I61Cl["UsersSearchFor"] = $_POST["UsersSearchFor"];
      $_I6oQj = "LastName";

      if( isset( $_POST["Usersfieldname"] ) && ($_POST["Usersfieldname"] != "") ) {
        $_I61Cl["Usersfieldname"] = $_POST["Usersfieldname"];
        $_QllO8 = substr($_POST["Usersfieldname"], 10);
        if($_QllO8 != "All")
          $_I6oQj = $_QllO8;
          else {
            $_I6oQj = "";
            $_QLLjo = array();
            $_QtjtL = array();
            $_QtjtL[] = "(id LIKE '%".trim($_POST["UsersSearchFor"])."%')";
            $_QtjtL[] = "(Username LIKE '%".trim($_POST["UsersSearchFor"])."%')";
            $_QtjtL[] = "(EMail LIKE '%".trim($_POST["UsersSearchFor"])."%')";
            $_QtjtL[] = "(FirstName LIKE '%".trim($_POST["UsersSearchFor"])."%')";
            $_QtjtL[] = "(LastName LIKE '%".trim($_POST["UsersSearchFor"])."%')";
          }

      }

      if(strpos($_QJlJ0, " WHERE ") === false)
         $_QJlJ0 .= " WHERE ";
         else
         $_QJlJ0 .= " AND ";
      if($_I6oQj != "")
        $_QJlJ0 .= "  (`$_I6oQj` LIKE '%".trim($_POST["UsersSearchFor"])."%')";
        else
        if(count($_QtjtL) > 0)
          $_QJlJ0 .= " (".join(" OR ", $_QtjtL).")";


    } else {
      $_I61Cl["UsersSearchFor"] = "";
      $_I61Cl["Usersfieldname"] = "SearchFor_LastName";
    }

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["UsersItemsPerPage"])) {
       $_QllO8 = intval($_POST["UsersItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["UsersItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['UsersPageSelected'])) || ($_POST['UsersPageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['UsersPageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', 'COUNT('.$_Q8f1L.'.id)', $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL);
    _OAL8F($_QJlJ0);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneUserId"] ) && ($_POST["OneUserId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneUserId"] ) && ($_POST["OneUserId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneUserId"] ) && ($_POST["OneUserId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneUserId"] ) && ($_POST["OneUserId"] == "End") )
       $_I6Q6O = $_I6IJ8;

    if ( ($_I6Q6O > $_I6IJ8) || ($_I6Q6O <= 0) )
       $_I6Q6O = 1;

    $_IJQQI = ($_I6Q6O - 1) * $_I6Q68;

    $_Q6i6i = "";
    for($_Q6llo=1; $_Q6llo<=$_I6IJ8; $_Q6llo++)
      if($_Q6llo != $_I6Q6O)
       $_Q6i6i .= "<option>$_Q6llo</option>";
       else
       $_Q6i6i .= '<option selected="selected">'.$_Q6llo.'</option>';

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPTION:PAGES>", "</OPTION:PAGES>", $_Q6i6i);

    // Nav-Buttons
    $_I6ICC = "";
    if($_I6Q6O == 1) {
      $_I6ICC .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_I6Q6O == $_I6IJ8) || ($_I6Qfj == 0) ) {
      $_I6ICC .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_I6Qfj == 0)
      $_I6ICC .= "  DisableItem('UsersPageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    // Sort
    $_I6jfj = " ORDER BY Username ASC";
    if( isset( $_POST["Userssortfieldname"] ) && ($_POST["Userssortfieldname"] != "") ) {
      $_I61Cl["Userssortfieldname"] = $_POST["Userssortfieldname"];
      if($_POST["Userssortfieldname"] == "SortUsername")
         $_I6jfj = " ORDER BY Username";
      if($_POST["Userssortfieldname"] == "SortLastName")
         $_I6jfj = " ORDER BY LastName";
      if($_POST["Userssortfieldname"] == "SortFirstName")
         $_I6jfj = " ORDER BY FirstName";
      if($_POST["Userssortfieldname"] == "SortEMail")
         $_I6jfj = " ORDER BY EMail";
      if($_POST["Userssortfieldname"] == "SortUserType")
         $_I6jfj = " ORDER BY UserType";
      if($_POST["Userssortfieldname"] == "Sortid")
         $_I6jfj = " ORDER BY id";
      if (isset($_POST["Userssortorder"]) ) {
         $_I61Cl["Userssortorder"] = $_POST["Userssortorder"];
         if($_POST["Userssortorder"] == "ascending")
           $_I6jfj .= " ASC";
           else
           $_I6jfj .= " DESC";
         }
    } else {
      $_I61Cl["Userssortfieldname"] = "SortUsername";
      $_I61Cl["Userssortorder"] = "ascending";
    }
    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_QJlJ0 = str_replace('{}', "$_Q8f1L.*", $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    $_j0616 = true;
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:USERNAME>", "</LIST:USERNAME>", $_Q6Q1C["Username"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LASTNAME>", "</LIST:LASTNAME>", $_Q6Q1C["LastName"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:FIRSTNAME>", "</LIST:FIRSTNAME>", $_Q6Q1C["FirstName"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:EMAIL>", "</LIST:EMAIL>", $_Q6Q1C["EMail"]);
      if(isset($resourcestrings[$INTERFACE_LANGUAGE][$_Q6Q1C["UserType"]]))
        $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:USERTYPE>", "</LIST:USERTYPE>", $resourcestrings[$INTERFACE_LANGUAGE][$_Q6Q1C["UserType"]]);
        else
        $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:USERTYPE>", "</LIST:USERTYPE>", $resourcestrings[$INTERFACE_LANGUAGE]["NA"]);

      $_Q66jQ = str_replace ('name="EditUserProperties"', 'name="EditUserProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteUser"', 'name="DeleteUser" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="UsersIDs[]"', 'name="UsersIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      if($UserType == "SuperAdmin") {
        $_QJlJ0 = "SELECT count(id) FROM $_Q60QL WHERE users_id=".$_Q6Q1C["id"];
        $_Qllf1 = mysql_query($_QJlJ0);
        $_Q8OiJ = mysql_fetch_row($_Qllf1);
        mysql_free_result($_Qllf1);
        if($_Q8OiJ[0] > 0) {
           $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000105"]);
           $_j0616 = false;
        } else {
          $_QJlJ0 = "SELECT users_id FROM $_QLtQO WHERE owner_id=".$_Q6Q1C["id"];
          $_Qllf1 = mysql_query($_QJlJ0);
          $_Q8OiJ = mysql_fetch_row($_Qllf1);
          mysql_free_result($_Qllf1);
          if($_Q8OiJ[0] > 0) {
             $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000106"]);
             $_j0616 = false;
          }
        }
      }

      $_Q66jQ = str_replace("<CAN:DELETE>", "", $_Q66jQ);
      $_Q66jQ = str_replace("</CAN:DELETE>", "", $_Q66jQ);

      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    if(!$_j0616) {
       $_Q6ICj = _OPR6L($_Q6ICj, "<CAN:DELETE>", "</CAN:DELETE>", "");
    } else {
      $_Q6ICj = str_replace("<CAN:DELETE>", "", $_Q6ICj);
      $_Q6ICj = str_replace("</CAN:DELETE>", "", $_Q6ICj);
    }

    // save the filter for later use
    if( isset($_POST["UsersSaveFilter"]) ) {
       $_I61Cl["UsersSaveFilter"] = $_POST["UsersSaveFilter"];
       include_once("savedoptions.inc.php");
       _LQC66("BrowseUsersFilter", serialize($_I61Cl) );
    }

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);


    return $_Q6ICj;
  }

?>

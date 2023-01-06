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

  $_Ii6tC = $_I8tfQ;
  $_Ii6CO = true;

  if ( (isset($_POST["Action"]) && $_POST["Action"] == "local") || (isset($_POST["action"]) && $_POST["action"] == "local") ||
       (isset($_GET["Action"]) && $_GET["Action"] == "local") || (isset($_GET["action"]) && $_GET["action"] == "local")
     ) {
         if (! isset($_POST["OneMailingListId"]) && ! isset($_GET["OneMailingListId"]) ) {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000131"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
              else {
                $_Itfj8 = "";
                $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
              }
         }

         if(isset($_GET["OneMailingListId"]) && !isset($_POST["OneMailingListId"]) )
            $_POST["OneMailingListId"] = intval($_GET["OneMailingListId"]);

         if(!_LAEJL($_POST["OneMailingListId"])){
           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
           $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
           print $_QLJfI;
           exit;
         }

         // get local blocklist
         $_QLfol = "SELECT `LocalBlocklistTableName`, `Name`, `StatisticsTableName` FROM `$_QL88I` WHERE id=".intval($_POST["OneMailingListId"]);
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_Ii6CO = false;
         if(mysql_num_rows($_QL8i1) > 0) {
           $_QLO0f = mysql_fetch_row($_QL8i1);
           mysql_free_result($_QL8i1);
           $_Ii6tC = $_QLO0f[0];
           $_Ii8Q6 = $_QLO0f[1];
           $_I8jjj = $_QLO0f[2];
         } else {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000131"];
           include_once("mailinglistselect.inc.php");
           exit;
         }
  }

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_Ii6CO && !$_QLJJ6["PrivilegeGlobalBlockListRecipientBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if(!$_Ii6CO && !$_QLJJ6["PrivilegeLocalBlockListRecipientBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(!isset($_Itfj8))
    $_Itfj8 = "";
  if (count($_POST) > 1) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }


    $_Ilt8t = !isset($_POST["BlocklistActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneBlocklistAction"]) && $_POST["OneBlocklistAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneMemberId"]) && $_POST["OneMemberId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["BlocklistActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["BlocklistActions"] == "RemoveMembers") {

          if($OwnerUserId != 0) {
            if(!$_Ii6CO && !$_QLJJ6["PrivilegeLocalBlockListRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
            if($_Ii6CO && !$_QLJJ6["PrivilegeGlobalBlockListRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IlOlj = array();
          for($_Qli6J=count($_IlOlj) - 1; $_Qli6J>=0; $_Qli6J--)
             unset($_IlOlj[$_Qli6J]);
          if ( isset($_POST["OneMemberId"]) && $_POST["OneMemberId"] > 0 )
              $_IlOlj[] = intval($_POST["OneMemberId"]);
              else
              if ( isset($_POST["MemberIDs"]) ){
                foreach($_POST["MemberIDs"] as $key)
                  $_IlOlj[] = intval($key);
              }

          $_IQ0Cj = array();
          for($_Qli6J=0; $_Qli6J<count($_IlOlj); $_Qli6J++) {
             $_QLfol = "DELETE FROM `$_Ii6tC` WHERE `id`=".intval($_IlOlj[$_Qli6J]);
             mysql_query($_QLfol, $_QLttI);
             if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
             if(!empty($_I8jjj)) {
               $_QLfol = "DELETE FROM `$_I8jjj` WHERE `Member_id`=".intval($_IlOlj[$_Qli6J])." AND `Action`='BlackListed'";
               mysql_query($_QLfol, $_QLttI);
             }
          }

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000133"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000132"];
        }
    }

    if( isset($_POST["OneBlocklistAction"]) && isset($_POST["OneMemberId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneBlocklistAction"] == "EditMemberProperties") {
        include_once("blocklistmemberedit.php");
        exit;
      }

      if($_POST["OneBlocklistAction"] == "DeleteMember") {

        if($OwnerUserId != 0) {
          if(!$_Ii6CO && !$_QLJJ6["PrivilegeLocalBlockListRecipientRemove"]) {
            $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QLJfI;
            exit;
          }
          if($_Ii6CO && !$_QLJJ6["PrivilegeGlobalBlockListRecipientRemove"]) {
            $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QLJfI;
            exit;
          }
        }

        $_IQ0Cj = array();
        $_QLfol = "DELETE FROM $_Ii6tC WHERE id=".intval($_POST["OneMemberId"]);
        mysql_query($_QLfol, $_QLttI);
        if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

        if(!empty($_I8jjj)) {
          $_QLfol = "DELETE FROM `$_I8jjj` WHERE `Member_id`=".intval($_POST["OneMemberId"])." AND `Action`='BlackListed'";
          mysql_query($_QLfol, $_QLttI);
        }

        // show now the list
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000133"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000132"];
      }

    }

  }

  // set saved values
  if ( (count($_POST) <= 1) || (isset($_POST["MailingListSelectForm"])) || ( isset($_POST["OneBlocklistAction"]) && $_POST["OneBlocklistAction"] == "BrowseMembers") ) {
    include_once("savedoptions.inc.php");
    $_IlC81 = _JOO1L("BrowseBlocklistsFilter");

    if( $_IlC81 != "") {
      $_I016j = @unserialize($_IlC81);
      if($_I016j !== false)
        $_POST = array_merge($_POST, $_I016j);
    }
  }

  // default SQL query to get members
  $_QLfol = "SELECT {} FROM `$_Ii6tC`";


  // Template
  if($_Ii6CO)
   $_IiOfO = $resourcestrings[$INTERFACE_LANGUAGE]["000130"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"];
   else
   $_IiOfO = $_Ii8Q6." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000131"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"];

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, $_Itfj8, 'browseblmembers', 'browse_blocklist_members_snipped.htm');

  // hold hidden the listname
  if(isset($_POST["OneMailingListId"]))
    $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QLJfI);


  $_QLJfI = _L1RCQ($_Ii6tC, $_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_Ii6CO) {
      if(!$_QLJJ6["PrivilegeLocalBlockListRecipientCreate"]) {
        $_QLoli = _JJC0E($_QLoli, "blocklistmemberedit.php");
        $_QLoli = _JJC0E($_QLoli, "blocklistmemberimport.php");
        $_QLoli = _JJC0E($_QLoli, "exportblocklist.php");
      }

      if(!$_QLJJ6["PrivilegeLocalBlockListRecipientEdit"]) {
        $_QLoli = _JJC1E($_QLoli, "EditMemberProperties");
      }

      if(!$_QLJJ6["PrivilegeLocalBlockListRecipientRemove"]) {
        $_QLoli = _JJC1E($_QLoli, "DeleteMember");
        $_QLoli = _JJCRD($_QLoli, "RemoveMembers");
      }
    } else {
      if(!$_QLJJ6["PrivilegeGlobalBlockListRecipientCreate"]) {
        $_QLoli = _JJC0E($_QLoli, "blocklistmemberedit.php");
        $_QLoli = _JJC0E($_QLoli, "blocklistmemberimport.php");
        $_QLoli = _JJC0E($_QLoli, "exportblocklist.php");
      }

      if(!$_QLJJ6["PrivilegeGlobalBlockListRecipientEdit"]) {
        $_QLoli = _JJC1E($_QLoli, "EditMemberProperties");
      }

      if(!$_QLJJ6["PrivilegeGlobalBlockListRecipientRemove"]) {
        $_QLoli = _JJC1E($_QLoli, "DeleteMember");
        $_QLoli = _JJCRD($_QLoli, "RemoveMembers");
      }
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  if(!$_Ii6CO) {
    $_QLJfI = str_replace('name="Action"', 'name="Action" value="local"', $_QLJfI);
    $_QLJfI = str_replace('name="action"', 'name="action" value="local"', $_QLJfI);
    $_QLJfI = str_replace('blocklistmemberedit.php', 'blocklistmemberedit.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
    $_QLJfI = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
    $_QLJfI = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
  }
  print $_QLJfI;



  function _L1RCQ($_Ii6tC, $_QLfol, $_QLoli) {
    global $_QLttI;
    $_Il0o6 = array();

    if( isset($_POST["MembersSaveFilter"]) )
      $_Il0o6["MembersSaveFilter"] = $_POST["MembersSaveFilter"];

    // Searchstring
    if( isset( $_POST["MembersSearchFor"] ) && ($_POST["MembersSearchFor"] != "") ) {
      $_Il0o6["MembersSearchFor"] = $_POST["MembersSearchFor"];
      $_IliOC = "u_EMail";

      if( isset( $_POST["Membersfieldname"] ) && ($_POST["Membersfieldname"] != "") ) {
        $_Il0o6["Membersfieldname"] = $_POST["Membersfieldname"];
        $_I016j = substr($_POST["Membersfieldname"], 10);
        if($_I016j != "All")
          $_IliOC = $_I016j;
          else {
            $_IliOC = "";
            $_Iflj0 = array();
            $_QLlO6 = array();
            _L8EOB($_Ii6tC, $_Iflj0);
            for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
              if( _LRDB8("u_", $_Iflj0[$_Qli6J]) != 1 ) continue;
              $_QLlO6[] = "("."`$_Iflj0[$_Qli6J]` LIKE '%".trim($_POST["MembersSearchFor"])."%')";
            }
          }

      }

      if($_IliOC != "")
        $_QLfol .= " WHERE (`$_IliOC` LIKE '%".trim($_POST["MembersSearchFor"])."%')";
        else
        if(count($_QLlO6) > 0)
          $_QLfol .= " WHERE (".join(" OR ", $_QLlO6).")";


    } else {
      $_Il0o6["MembersSearchFor"] = "";
      $_Il0o6["Membersfieldname"] = "SearchForu_EMail";
    }

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["MembersItemsPerPage"])) {
       $_I016j = intval($_POST["MembersItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["MembersItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['MembersPageSelected'])) || ($_POST['MembersPageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['MembersPageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', 'COUNT(id)', $_QLlO6);
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

    if( isset( $_POST["OneMemberId"] ) && ($_POST["OneMemberId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneMemberId"] ) && ($_POST["OneMemberId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneMemberId"] ) && ($_POST["OneMemberId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneMemberId"] ) && ($_POST["OneMemberId"] == "End") )
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
      $_Iljoj .= "  DisableItem('MembersPageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    // Sort
    $_IlJj8 = " ORDER BY u_EMail ASC";
    if( isset( $_POST["Memberssortfieldname"] ) && ($_POST["Memberssortfieldname"] != "") ) {
      $_Il0o6["Memberssortfieldname"] = $_POST["Memberssortfieldname"];
      if($_POST["Memberssortfieldname"] == "SortEMail")
         $_IlJj8 = " ORDER BY u_EMail";
      if($_POST["Memberssortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY id";
      if (isset($_POST["Memberssortorder"]) ) {
         $_Il0o6["Memberssortorder"] = $_POST["Memberssortorder"];
         if($_POST["Memberssortorder"] == "ascending")
           $_IlJj8 .= " ASC";
           else
           $_IlJj8 .= " DESC";
         }
    } else {
      $_Il0o6["Memberssortfieldname"] = "SortEMail";
      $_Il0o6["Memberssortorder"] = "ascending";
    }
    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', '*', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:EMAIL>", "</LIST:EMAIL>", $_QLO0f["u_EMail"]);

      $_Ql0fO = str_replace ('name="EditMemberProperties"', 'name="EditMemberProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteMember"', 'name="DeleteMember" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="MemberIDs[]"', 'name="MemberIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    // save the filter for later use
    if( isset($_POST["MembersSaveFilter"]) ) {
       $_Il0o6["MembersSaveFilter"] = $_POST["MembersSaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseBlocklistsFilter", serialize($_Il0o6) );
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);


    return $_QLoli;
  }

?>

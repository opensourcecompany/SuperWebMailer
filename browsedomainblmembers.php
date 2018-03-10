<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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

  $_IjfjI = $_Qlt66;
  $_IjfLj = true;

  if ( (isset($_POST["Action"]) && $_POST["Action"] == "local") || (isset($_POST["action"]) && $_POST["action"] == "local") ||
       (isset($_GET["Action"]) && $_GET["Action"] == "local") || (isset($_GET["action"]) && $_GET["action"] == "local")
     ) {
         if (! isset($_POST["OneMailingListId"]) && ! isset($_GET["OneMailingListId"]) ) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001131"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
              else {
                $_I0600 = "";
                $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
              }
         }

         if(isset($_GET["OneMailingListId"]) && !isset($_POST["OneMailingListId"]) )
            $_POST["OneMailingListId"] = intval($_GET["OneMailingListId"]);

         if(!_OCJCC($_POST["OneMailingListId"])){
           $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
           $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
           print $_QJCJi;
           exit;
         }

         // get local blocklist
         $_QJlJ0 = "SELECT LocalDomainBlocklistTableName, Name FROM $_Q60QL WHERE id=".$_POST["OneMailingListId"];
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_IjfLj = false;
         if(mysql_num_rows($_Q60l1) > 0) {
           $_Q6Q1C = mysql_fetch_row($_Q60l1);
           mysql_free_result($_Q60l1);
           $_IjfjI = $_Q6Q1C[0];
           $_IjOJC = $_Q6Q1C[1];
         } else {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001131"];
           include_once("mailinglistselect.inc.php");
           exit;
         }
  }

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_IjfLj && !$_QJojf["PrivilegeGlobalBlockListRecipientBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if(!$_IjfLj && !$_QJojf["PrivilegeLocalBlockListRecipientBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(!isset($_I0600))
    $_I0600 = "";
  if (count($_POST) != 0) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }


    $_I680t = !isset($_POST["BlocklistActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneBlocklistAction"]) && $_POST["OneBlocklistAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneMemberId"]) && $_POST["OneMemberId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["BlocklistActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["BlocklistActions"] == "RemoveMembers") {

          if($OwnerUserId != 0) {
            if(!$_IjfLj && !$_QJojf["PrivilegeLocalBlockListRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
            if($_IjfLj && !$_QJojf["PrivilegeGlobalBlockListRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_I6ttf = array();
          for($_Q6llo=count($_I6ttf) - 1; $_Q6llo>=0; $_Q6llo--)
             unset($_I6ttf[$_Q6llo]);
          if ( isset($_POST["OneMemberId"]) && $_POST["OneMemberId"] != "" )
              $_I6ttf[] = intval($_POST["OneMemberId"]);
              else
              if ( isset($_POST["MemberIDs"]) )
                $_I6ttf = array_merge($_I6ttf, $_POST["MemberIDs"]);

          $_QtIiC = array();
          for($_Q6llo=0; $_Q6llo<count($_I6ttf); $_Q6llo++) {
             $_QJlJ0 = "DELETE FROM $_IjfjI WHERE id=".intval($_I6ttf[$_Q6llo]);
             mysql_query($_QJlJ0, $_Q61I1);
             if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
          }

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001133"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001132"];
        }
    }

    if( isset($_POST["OneBlocklistAction"]) && isset($_POST["OneMemberId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneBlocklistAction"] == "EditMemberProperties") {
        include_once("domainblocklistmemberedit.php");
        exit;
      }

      if($_POST["OneBlocklistAction"] == "DeleteMember") {

        if($OwnerUserId != 0) {
          if(!$_IjfLj && !$_QJojf["PrivilegeLocalBlockListRecipientRemove"]) {
            $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QJCJi;
            exit;
          }
          if($_IjfLj && !$_QJojf["PrivilegeGlobalBlockListRecipientRemove"]) {
            $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QJCJi;
            exit;
          }
        }

        $_QtIiC = array();
        $_QJlJ0 = "DELETE FROM $_IjfjI WHERE id=".intval($_POST["OneMemberId"]);
        mysql_query($_QJlJ0, $_Q61I1);
        if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001133"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001132"];
      }

    }

  }

  // set saved values
  if ( (count($_POST) == 0) || (isset($_POST["MailingListSelectForm"])) || ( isset($_POST["OneBlocklistAction"]) && $_POST["OneBlocklistAction"] == "BrowseMembers") ) {
    include_once("savedoptions.inc.php");
    $_I8QC6 = _LQB6D("BrowseDomainBlocklistsFilter");

    if( $_I8QC6 != "") {
      $_QllO8 = @unserialize($_I8QC6);
      if($_QllO8 !== false)
        $_POST = array_merge($_POST, $_QllO8);
    }
  }

  // default SQL query to get members
  $_QJlJ0 = "SELECT {} FROM $_IjfjI";


  // Template
  if($_IjfLj)
   $_Iji86 = $resourcestrings[$INTERFACE_LANGUAGE]["001130"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"];
   else
   $_Iji86 = $_IjOJC." - ".$resourcestrings[$INTERFACE_LANGUAGE]["001131"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"];

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, $_I0600, 'browsedomainblmembers', 'browse_domain_blocklist_members_snipped.htm');

  // hold hidden the listname
  if(isset($_POST["OneMailingListId"]))
    $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.intval($_POST["OneMailingListId"]).'"', $_QJCJi);


  $_QJCJi = _OLROF($_IjfjI, $_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_IjfLj) {
      if(!$_QJojf["PrivilegeLocalBlockListRecipientCreate"]) {
        $_Q6ICj = _LJ6RJ($_Q6ICj, "domainblocklistmemberedit.php");
        $_Q6ICj = _LJ6RJ($_Q6ICj, "domainblocklistmemberimport.php");
        $_Q6ICj = _LJ6RJ($_Q6ICj, "exportdomainblocklist.php");
      }

      if(!$_QJojf["PrivilegeLocalBlockListRecipientEdit"]) {
        $_Q6ICj = _LJ6B1($_Q6ICj, "EditMemberProperties");
      }

      if(!$_QJojf["PrivilegeLocalBlockListRecipientRemove"]) {
        $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteMember");
        $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveMembers");
      }
    } else {
      if(!$_QJojf["PrivilegeGlobalBlockListRecipientCreate"]) {
        $_Q6ICj = _LJ6RJ($_Q6ICj, "domainblocklistmemberedit.php");
        $_Q6ICj = _LJ6RJ($_Q6ICj, "domainblocklistmemberimport.php");
        $_Q6ICj = _LJ6RJ($_Q6ICj, "exportdomainblocklist.php");
      }

      if(!$_QJojf["PrivilegeGlobalBlockListRecipientEdit"]) {
        $_Q6ICj = _LJ6B1($_Q6ICj, "EditMemberProperties");
      }

      if(!$_QJojf["PrivilegeGlobalBlockListRecipientRemove"]) {
        $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteMember");
        $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveMembers");
      }
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  if(!$_IjfLj) {
    $_QJCJi = str_replace('name="action"', 'name="action" value="local"', $_QJCJi);
    $_QJCJi = str_replace('name="Action"', 'name="Action" value="local"', $_QJCJi);
    $_QJCJi = str_replace('domainblocklistmemberedit.php', 'domainblocklistmemberedit.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
    $_QJCJi = str_replace('domainblocklistmemberimport.php', 'domainblocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
    $_QJCJi = str_replace('exportdomainblocklist.php', 'exportdomainblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
  }
  print $_QJCJi;



  function _OLROF($_IjfjI, $_QJlJ0, $_Q6ICj) {
    global $_Q61I1;
    $_I61Cl = array();

    // Searchstring
    if( isset( $_POST["MembersSearchFor"] ) && ($_POST["MembersSearchFor"] != "") ) {
      $_I61Cl["MembersSearchFor"] = $_POST["MembersSearchFor"];
      $_I6oQj = "Domainname";

      if( isset( $_POST["Membersfieldname"] ) && ($_POST["Membersfieldname"] != "") ) {
        $_I61Cl["Membersfieldname"] = $_POST["Membersfieldname"];
        $_QllO8 = substr($_POST["Membersfieldname"], 10);
        if($_QllO8 != "All")
          $_I6oQj = $_QllO8;
          else {
            $_I6oQj = "";
            $_QLLjo = array();
            $_QtjtL = array();
            _OAJL1($_IjfjI, $_QLLjo);
            for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
              if( _OPLFQ("u_", $_QLLjo[$_Q6llo]) != 1 ) continue;
              $_QtjtL[] = "("."`$_QLLjo[$_Q6llo]` LIKE '%".trim($_POST["MembersSearchFor"])."%')";
            }
          }

      }

      if($_I6oQj != "")
        $_QJlJ0 .= " WHERE (`$_I6oQj` LIKE '%".trim($_POST["MembersSearchFor"])."%')";
        else
        if(count($_QtjtL) > 0)
          $_QJlJ0 .= " WHERE (".join(" OR ", $_QtjtL).")";


    } else {
      $_I61Cl["MembersSearchFor"] = "";
      $_I61Cl["Membersfieldname"] = "SearchForDomainname";
    }

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["MembersItemsPerPage"])) {
       $_QllO8 = intval($_POST["MembersItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["MembersItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['MembersPageSelected'])) || ($_POST['MembersPageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['MembersPageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', 'COUNT(id)', $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneMemberId"] ) && ($_POST["OneMemberId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneMemberId"] ) && ($_POST["OneMemberId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneMemberId"] ) && ($_POST["OneMemberId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneMemberId"] ) && ($_POST["OneMemberId"] == "End") )
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
      $_I6ICC .= "  DisableItem('MembersPageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    // Sort
    $_I6jfj = " ORDER BY Domainname ASC";
    if( isset( $_POST["Memberssortfieldname"] ) && ($_POST["Memberssortfieldname"] != "") ) {
      $_I61Cl["Memberssortfieldname"] = $_POST["Memberssortfieldname"];
      if($_POST["Memberssortfieldname"] == "SortDomainname")
         $_I6jfj = " ORDER BY Domainname";
      if($_POST["Memberssortfieldname"] == "Sortid")
         $_I6jfj = " ORDER BY id";
      if (isset($_POST["Memberssortorder"]) ) {
         $_I61Cl["Memberssortorder"] = $_POST["Memberssortorder"];
         if($_POST["Memberssortorder"] == "ascending")
           $_I6jfj .= " ASC";
           else
           $_I6jfj .= " DESC";
         }
    } else {
      $_I61Cl["Memberssortfieldname"] = "SortDomainname";
      $_I61Cl["Memberssortorder"] = "ascending";
    }
    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_QJlJ0 = str_replace('{}', '*', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:DOMAIN>", "</LIST:DOMAIN>", $_Q6Q1C["Domainname"]);

      $_Q66jQ = str_replace ('name="EditMemberProperties"', 'name="EditMemberProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteMember"', 'name="DeleteMember" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="MemberIDs[]"', 'name="MemberIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    // save the filter for later use
    if( isset($_POST["MembersSaveFilter"]) ) {
       $_I61Cl["MembersSaveFilter"] = $_POST["MembersSaveFilter"];
       include_once("savedoptions.inc.php");
       _LQC66("BrowseDomainBlocklistsFilter", serialize($_I61Cl) );
    }

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);


    return $_Q6ICj;
  }

?>

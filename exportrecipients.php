<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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
  include_once("savedoptions.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeExportBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_jLOo1 = "export.txt";
  $_IjL1j = array();
  $_I0600 = "";

  if (isset($_POST["OneMailingListId"]) ) {
     $OneMailingListId = intval($_POST["OneMailingListId"]);
     $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
     }
     else {
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000150"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
       $OneMailingListId = intval($_POST["OneMailingListId"]);
       $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
     }


  if(!_OCJCC($OneMailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  if(!isset($_POST["MailingListName"])) {
    $_QJlJ0 = "SELECT Name FROM $_Q60QL WHERE id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_POST["MailingListName"] = $_Q6Q1C[0];
  }

  // go prev page
  if(isset($_POST["step"]) && $_POST["step"] == 2 && isset($_POST["PrevBtn"]) )
     unset($_POST["step"]);
  if(isset($_POST["step"]) && $_POST["step"] == 3 && isset($_POST["PrevBtn"]) )
     $_POST["step"] = "2";
  if(isset($_POST["step"]) && $_POST["step"] == 4 && isset($_POST["PrevBtn"]) )
     $_POST["step"] = "3";
  //
  if(isset($_POST["step"]) && $_POST["step"] == 3) {
   if(!isset($_POST["fields"])) {
     $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000153"];
     $_POST["step"] = 2;
   }
  }

  if(isset($_POST["step"]) && $_POST["step"] == 5 && isset($_POST["RemoveExportFileBtn"])) {
    $_jLCf6 = "";
    if($_Q6lfJ = fopen($_jji0C.$_jLOo1, "w") )
       fclose($_Q6lfJ);
       else
       $_jLCf6 = "Can't open file ".$_jji0C.$_jLOo1;

    if(!unlink($_jji0C.$_jLOo1))
       $_jLCf6 .= "Can't remove file ".$_jji0C.$_jLOo1;
    unset($_POST["step"]);
    if($_jLCf6 == "")
       $_jLCf6 = "OK";

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], $_I0600, 'exportrecipients', 'export7_snipped.htm');
    $_QJCJi = str_replace("[ERRORTEXT]", $_jLCf6, $_QJCJi);
    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    print $_QJCJi;

    exit;
  }

  if(!isset($_POST["step"])) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], "", 'exportrecipients', 'export1_snipped.htm');
    $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
    $_QJCJi = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QJCJi);
    $_QJCJi = str_replace('/userfiles/export', $_jji0C, $_QJCJi);

    print $_QJCJi;
    exit;
  } elseif($_POST["step"] == 1 ) {
      $_jLiQi = _LQB6D("FileExportOptions");
      $_jLi8j = $_POST;
      unset($_jLi8j["step"]);
      if( $_jLiQi != "" ) {
       $_QllO8 = unserialize($_jLiQi);
       if($_QllO8 !== false) {
         // feldzuordnung rausnehmen, der rest muss bleiben
         foreach($_QllO8 as $_I1i8O => $_I1L81) {
           if(!(strpos($_I1i8O, "fields") === false))
              unset($_QllO8[$_I1i8O]);
         }
         $_jLi8j = array_merge($_jLi8j, $_QllO8);
       }
      }

      // zeige Step 2
      _OL086(array(), $_jLi8j);

  } elseif($_POST["step"] == 2 ) {

    $errors = array();

    if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && (!isset($_POST["groups"]) || count($_POST["groups"]) == 0) )
       $_POST["GroupsOption"] = 1;
    if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && is_array($_POST["groups"])  )
        $_POST["groups"] = join(",", $_POST["groups"]);

    if ( !isset($_POST["PrevBtn"])) { // no check on prev btn
      if(!isset($_POST["Separator"]) || trim($_POST["Separator"]) == "") {
       $errors[] = "Separator";
      }
    } else {
      _OL086($errors, $_POST);
      exit;
    }


    if(count($errors) > 0 ) {
      _OL086($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["000020"]);
      exit;
    }

    $_Q6lfJ = fopen($_jji0C.$_jLOo1, "w");
    if(!$_Q6lfJ) {
      _OL086($errors, $_POST, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000152"], $_jji0C.$_jLOo1 ));
      exit;
    }



    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], $_I0600, 'exportrecipients', 'export3_snipped.htm');

    $_I1OLj = _OP81D($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>");
    $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language="._OPQLR($INTERFACE_LANGUAGE);
    $_Q60l1 = mysql_query($_QJlJ0);

    // fields, mod 2 = 0 !!!
    $_Q6ICj = "";
    $_Q6llo=1;
    $_QL8Q8=1;
    while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
      if($_Q6llo == 1)
         $_Q66jQ = $_I1OLj;
      $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', '<label for="fields'.$_QL8Q8.'">'.$_Q6Q1C["text"]."</label>", $_Q66jQ);
      $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<input type="checkbox" id="fields'.$_QL8Q8.'" name="fields['.$_Q6Q1C["fieldname"].']" value="1" />', $_Q66jQ);
      $_Q6llo++;
      $_QL8Q8++;
      if($_Q6llo>2) {
        $_Q6llo=1;
        $_Q6ICj .= $_Q66jQ;
        $_Q66jQ = "";
      }
    }


    // optional fields, mod 2 = 0 !!!
    $_Q66jQ = '<tr><td colspan="4">&nbsp;</td></tr>'.'<tr><td colspan="4">'.$resourcestrings[$INTERFACE_LANGUAGE]["ExportOptionalFields"].'</td></tr>';
    $_Q6ICj .= $_Q66jQ;
    $_Q66jQ = "";

    $_Q6Q1C = array();
    $_Q6Q1C[] = "IsActive";
    $_Q6Q1C[] = "SubscriptionStatus";
    $_Q6Q1C[] = "DateOfSubscription";
    $_Q6Q1C[] = "DateOfOptInConfirmation";
    $_Q6Q1C[] = "IPOnSubscription";
    $_Q6Q1C[] = "IdentString";
    $_Q6Q1C[] = "LastEMailSent";
    $_Q6Q1C[] = "BounceStatus";
    $_Q6Q1C[] = "SoftbounceCount";
    $_Q6Q1C[] = "HardbounceCount";
    $_Q6Q1C[] = "HistoryOfSendEMails";

    for($_Qf0Ct=0; $_Qf0Ct<count($_Q6Q1C); $_Qf0Ct++){
      if($_Q6llo == 1)
         $_Q66jQ = $_I1OLj;
      $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', '<label for="fields'.$_QL8Q8.'">'.$resourcestrings[$INTERFACE_LANGUAGE]["ExportOpt".$_Q6Q1C[$_Qf0Ct]]."</label>", $_Q66jQ);
      $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<input type="checkbox" id="fields'.$_QL8Q8.'" name="fields['.$_Q6Q1C[$_Qf0Ct].']" value="1" />', $_Q66jQ);
      $_Q6llo++;
      $_QL8Q8++;
      if($_Q6llo>2) {
        $_Q6llo=1;
        $_Q6ICj .= $_Q66jQ;
        $_Q66jQ = "";
      }
    }


    if($_Q66jQ != "")
      $_Q6ICj .= $_Q66jQ;

    $_QJCJi = _OPR6L($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>", $_Q6ICj);
    if(isset($_POST["step"]))
      unset($_POST["step"]);

    $_jLiQi = _LQB6D("FileExportOptions");
    $_jLi8j = $_POST;
    if( $_jLiQi != "" ) {
       $_QllO8 = @unserialize($_jLiQi);
       if($_QllO8 !== false) {
         // alles rausnehmen, nur die feldzuordnung bleibt
         foreach($_QllO8 as $_I1i8O => $_I1L81) {
           if(strpos($_I1i8O, "fields") === false)
              unset($_QllO8[$_I1i8O]);
         }
         $_jLi8j = array_merge($_jLi8j, $_QllO8);
       }
    }

    $_QJCJi = _OPFJA($_IjL1j, $_jLi8j, $_QJCJi);

    print $_QJCJi;

  } elseif($_POST["step"] == 3 ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], "", 'exportrecipients', 'export4_snipped.htm');

    // save fields
    //
    unset($_POST["step"]);


    $_IJ0Q8 = "";
    $_I16jJ = $_POST["fields"];
    reset($_I16jJ);
    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
        $_IJ0Q8 .= '<input type="hidden" name="fields['.$_I1i8O.']" value="'.$_I1L81.'" />';
      }
    }

    $_jLiQi = $_POST;
    unset($_jLiQi["OneMailingListId"]);
    unset($_jLiQi["MailingListName"]);
    if(isset($_jLiQi["PrevBtn"]))
      unset($_jLiQi["PrevBtn"]);
    if(isset($_jLiQi["NextBtn"]))
      unset($_jLiQi["NextBtn"]);

    // umwandeln, damit er es wieder findet
    unset($_jLiQi["fields"]);
    $_I16jJ = $_POST["fields"];
    reset($_I16jJ);
    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
        $_jLiQi["fields[$_I1i8O]"] = $_I1L81;
      }
    }


    _LQC66("FileExportOptions", serialize($_jLiQi));

    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    $_QJCJi = _OPR6L($_QJCJi, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IJ0Q8);

    print $_QJCJi;
  } elseif($_POST["step"] == 4 ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], "", 'exportrecipients', 'export5_snipped.htm');

    $_IJ0Jo = 0;
    $_jLCff = 0;
    if(isset($_POST["RowCount"]))
       $_IJ0Jo += $_POST["RowCount"];
    if(isset($_POST["ExportRowCount"]))
       $_jLCff += $_POST["ExportRowCount"];

    $_QCC8C = fopen($_jji0C.$_jLOo1, "a");


    $_QJlJ0 = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `GroupsTableName`, `MailLogTableName` FROM `$_Q60QL` WHERE id=".$_POST["OneMailingListId"];
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    $_jf1jt = $_Q6Q1C["MaillistTableName"];
    $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
    $_Q6t6j = $_Q6Q1C["GroupsTableName"];
    $_QljIQ = $_Q6Q1C["MailLogTableName"];
    mysql_free_result($_Q60l1);

    $_jLiL1 = "";
    $_jLiLL = "";
    if($_POST["GroupsOption"] == 1){
      $_QJlJ0 = "SELECT COUNT(`u_EMail`) FROM $_jf1jt";
      if(isset($_POST["OnlyActiveRecipients"]) && $_POST["OnlyActiveRecipients"] == 1)
        $_jLiL1 = " WHERE IsActive <> 0";
    } else{
      if(!is_array($_POST["groups"]))
        $_Q6Oto = explode(",", $_POST["groups"]);
      $_jLiLL = "LEFT JOIN `$_QLI68` ON `$_jf1jt`.`id`=`$_QLI68`.`Member_id`";
      $_QJlJ0 = "SELECT COUNT(DISTINCT `$_jf1jt`.`u_EMail`) FROM $_jf1jt $_jLiLL";
      if(isset($_POST["OnlyActiveRecipients"]) && $_POST["OnlyActiveRecipients"] == 1)
        $_jLiL1 = " WHERE IsActive <> 0";


      $_jLl11 = array();
      foreach($_Q6Oto as $key => $_Q6ClO) {
         $_jLl11[] = " `$_QLI68`.`groups_id` = ".intval($_Q6ClO);
      }
      if(count($_jLl11) != 0){
        if($_jLiL1 == "")
           $_jLiL1 = " WHERE ";
           else
           $_jLiL1 .= " AND ";
        $_jLiL1 .= " (". join(" OR ", $_jLl11) .") ";
      }
    }
    $_QJlJ0 .= $_jLiL1;
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);

    if(!isset($_POST["start"]))
      { // noch nie angefasst
        // UTF-8 BOM
        fwrite($_QCC8C,  "ï»¿");
        if(isset($_POST["Header1Line"]) && $_POST["Header1Line"] != "" ) {

          $_QLLjo = array();
          $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language="._OPQLR($INTERFACE_LANGUAGE);
          $_Q60l1 = mysql_query($_QJlJ0);
          while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
             $_QLLjo[$_Q6Q1C["fieldname"]] = unhtmlentities( $_Q6Q1C["text"], $_Q6QQL );
          }
          mysql_free_result($_Q60l1);

          // fields optionally
          $_QLLjo["IsActive"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptIsActive"], "utf-8");
          $_QLLjo["SubscriptionStatus"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptSubscriptionStatus"], "utf-8");
          $_QLLjo["DateOfSubscription"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptDateOfSubscription"], "utf-8");
          $_QLLjo["DateOfOptInConfirmation"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptDateOfOptInConfirmation"], "utf-8");
          $_QLLjo["IPOnSubscription"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptIPOnSubscription"], "utf-8");
          $_QLLjo["IdentString"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptIdentString"], "utf-8");
          $_QLLjo["LastEMailSent"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptLastEMailSent"], "utf-8");
          $_QLLjo["BounceStatus"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptBounceStatus"], "utf-8");
          $_QLLjo["SoftbounceCount"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptSoftbounceCount"], "utf-8");
          $_QLLjo["HardbounceCount"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptHardbounceCount"], "utf-8");
          $_QLLjo["HistoryOfSendEMails"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptHistoryOfSendEMails"], "utf-8");
          $_QLLjo["GroupsRelations"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptGroupsRelations"], "utf-8");

          $_I16jJ = $_POST["fields"];
          reset($_I16jJ);

          $_Q8otJ = array();
          foreach($_I16jJ as $_I1i8O => $_I1L81) {
            if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
              if(isset($_POST["AddQuotes"]) && $_POST["AddQuotes"] != "")
                 $_Q8otJ[] = _OPQJR($_QLLjo[$_I1i8O]);
                else
                 $_Q8otJ[] = $_QLLjo[$_I1i8O];
            }

          }
          fwrite($_QCC8C, join($_POST["Separator"], $_Q8otJ).$_Q6JJJ );

        }
      }

    if(!isset($_POST["start"]) ) {
      $_IJQQI = 0;
    } else {
      $_IJQQI = $_POST["start"];
      unset($_POST["start"]);
    }

    unset($_POST["step"]);

    // use ever yyyy-mm-dd
    $_If0Ql = "'%d.%m.%Y'";
    if($INTERFACE_LANGUAGE != "de") {
       $_If0Ql = "'%Y-%m-%d'";
    }

    $_ILQL0 = "";
    $_IJ0Q8 = "";
    $_I16jJ = $_POST["fields"];
    reset($_I16jJ);
    $_jLljQ = false;
    $_jLlO8 = false;
    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
        $_IJ0Q8 .= '<input type="hidden" name="fields['.$_I1i8O.']" value="'.$_I1L81.'" />';
        if($_ILQL0 == ""){
            if($_I1i8O == 'u_Birthday')
              $_ILQL0 = "DATE_FORMAT($_I1i8O, $_If0Ql)";
            else
              $_ILQL0 = $_I1i8O;
          }
          else
          if($_I1i8O == 'u_Birthday') {
            $_ILQL0 .= ", ". "DATE_FORMAT($_I1i8O, $_If0Ql)";
            } else if($_I1i8O == 'HistoryOfSendEMails') {
              $_jLljQ = true;
              $_ILQL0 .= ", ". "`$_QljIQ`.`MailLog` AS HistoryOfSendEMails";
            }  else if($_I1i8O == 'GroupsRelations') {
              $_jLlO8 = true;
              $_ILQL0 .= ", ". "1 AS GroupsRelations";
            }
            else
              $_ILQL0 .= ", ".$_I1i8O;
      }
    }
    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    $_QJCJi = _OPR6L($_QJCJi, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IJ0Q8);

    $_IJQJ8 = substr($_QJCJi, 0, strpos($_QJCJi, "<BLOCK />") - 1);
    $_QJCJi = substr($_QJCJi, strpos($_QJCJi, "<BLOCK />") + strlen("<BLOCK />"));

    // progress
    if($_I6Qfj > 0)
      $_Q6i6i = sprintf("%d", $_IJQQI * 100 / $_I6Qfj );
      else
      $_Q6i6i = 0;
    // progressbar macht bei 0 mist
    if($_Q6i6i == 0)
      $_Q6i6i = 1;
    $_IJQJ8 = _OPR6L($_IJQJ8, "<SHOW:PERCENT>", "</SHOW:PERCENT>", $_Q6i6i);

    if(!defined("SWM")) { # in first version of SML LastEmailSent not exists
      $_jl0QO = array();
      _OAJL1($_jf1jt, $_jl0QO);
      if(!array_search("LastEMailSent", $_jl0QO)) {
        $_QJlJ0 = "ALTER TABLE `$_jf1jt` ADD `LastEMailSent` DATETIME NULL AFTER `IdentString`";
        mysql_query($_QJlJ0);
      }
    }

    if(isset($_POST["groups"]))
       $_ILQL0 = "DISTINCT ".$_ILQL0;

    if($_jLljQ) {
      $_jLiLL .= " LEFT JOIN `$_QljIQ` ON `$_QljIQ`.`Member_id`=`$_jf1jt`.`id`";
    }

    $_QJlJ0 = "SELECT $_ILQL0, `$_jf1jt`.`id` FROM `$_jf1jt` $_jLiLL ".$_jLiL1;

    $_QJlJ0 .= " ORDER BY id LIMIT $_IJQQI, ".intval($_POST["ExportLines"]);

    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
        fclose($_QCC8C);
        $_I0600 = "";
        $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], $_I0600, 'exportrecipients', 'export6_snipped.htm');
        $_QJCJi = _OPR6L($_QJCJi, "<EXPORT:FILECOUNT>", "</EXPORT:FILECOUNT>", $_I6Qfj);
        $_QJCJi = str_replace("DOWNLOAD", $_jjCtI."export/".$_jLOo1, $_QJCJi);
        $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
        print $_QJCJi;
        exit;
    }

    // output here, if eof() than do nothing
    print $_IJQJ8;
    flush();

    $_IflL6 = 0;
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      if(isset($_Q6Q1C["HistoryOfSendEMails"]))
         $_Q6Q1C["HistoryOfSendEMails"] = str_replace($_Q6JJJ, '\n', $_Q6Q1C["HistoryOfSendEMails"]);

      _OPQ6J();

      if($_jLlO8){
        $_QJlJ0 = "SELECT `$_Q6t6j`.`Name` FROM `$_Q6t6j` LEFT JOIN `$_QLI68` ON `$_QLI68`.`Member_id`=$_Q6Q1C[id] WHERE `$_QLI68`.`groups_id`=`$_Q6t6j`.`id`";
        $_jl0if = mysql_query($_QJlJ0);
        _OAL8F($_QJlJ0);
        $_Q6Q1C["GroupsRelations"] = array();
        while($_ji0L6 = mysql_fetch_assoc($_jl0if)){
          $_Q6Q1C["GroupsRelations"][] = $_ji0L6["Name"];
        }
        $_Q6Q1C["GroupsRelations"] = join($_POST["Separator"] == "," ? ";" : ",", $_Q6Q1C["GroupsRelations"]);
        mysql_free_result($_jl0if);
      }

      if(!empty($_POST["AddQuotes"])) {
        reset($_Q6Q1C);
        foreach($_Q6Q1C as $key => $_Q6ClO) {
          $_Q6Q1C[$key] = _OPQJR(unhtmlentities($_Q6ClO, "utf-8"));
        }
      } else {
        reset($_Q6Q1C);
        foreach($_Q6Q1C as $key => $_Q6ClO) {
          $_Q6Q1C[$key] = unhtmlentities($_Q6ClO, "utf-8");
        }
      }
      unset($_Q6Q1C["id"]); /* id field */

      fwrite($_QCC8C, join($_POST["Separator"], $_Q6Q1C).$_Q6JJJ );
      $_IflL6++;
    }

    mysql_free_result($_Q60l1);

    $_IJQQI += $_IflL6;
    $_jLCff += $_IflL6;

    print '<input type="hidden" name="start" value="'.$_IJQQI.'" />';
    print '<input type="hidden" name="ExportRowCount" value="'.$_jLCff.'" />';

    fclose($_QCC8C);

    print $_QJCJi;
  }


  function _OL086($errors, $_I1l66, $_I0600 = "") {
    global $_jji0C, $resourcestrings, $INTERFACE_LANGUAGE, $OneMailingListId, $_Q60QL;
    global $UserType, $Username, $_Q6JJJ;
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], $_I0600, 'exportrecipients', 'export2_snipped.htm');

    if(isset($_I1l66["step"]))
      unset($_I1l66["step"]);

    $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
    $_QJCJi = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QJCJi);

    if(isset($_I1l66["MailingListName"]))
      unset($_I1l66["MailingListName"]);

    if (!isset($_I1l66["Separator"]) )
      $_QJCJi = str_replace('name="Separator"', 'name="Separator" value=","', $_QJCJi);
    if (!isset($_I1l66["ExportLines"]) )
       $_QJCJi = str_replace('name="ExportLines"', 'name="ExportLines" value="200"', $_QJCJi);
    if (!isset($_I1l66["Header1Line"]) )
       $_QJCJi = str_replace('name="Header1Line"', 'name="Header1Line" checked="checked"', $_QJCJi);
    if (!isset($_I1l66["AddQuotes"]) )
       $_QJCJi = str_replace('name="AddQuotes"', 'name="AddQuotes" checked="checked"', $_QJCJi);
    if (!isset($_I1l66["GroupsOption"]) )
       $_I1l66["GroupsOption"] = 1;


    // ********* List of Groups SQL query
    $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q6t6j = $_Q6Q1C["GroupsTableName"];

    $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q6t6j";
    $_Q68ff .= " ORDER BY Name ASC";
    $_Q60l1 = mysql_query($_Q68ff);
    _OAL8F($_Q68ff);
    $_I10Cl = "";
    $_IIJi1 = _OP81D($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>");
    $_II6ft = 0;
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_I10Cl .= $_IIJi1;

      $_I10Cl = _OPR6L($_I10Cl, "<GroupsId>", "</GroupsId>", $_Q6Q1C["id"]);
      $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_Q6Q1C["id"]);
      $_I10Cl = _OPR6L($_I10Cl, "<GroupsName>", "</GroupsName>", $_Q6Q1C["Name"]);
      $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_Q6Q1C["Name"]);
      $_II6ft++;
      $_I10Cl = str_replace("GroupsLabelId", 'groupchkbox_'.$_II6ft, $_I10Cl);
    }

    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_I10Cl);
    mysql_free_result($_Q60l1);
    // ********* List of Groupss query END


    if(isset($_I1l66["groups"])) {
      if(!is_array($_I1l66["groups"]))
        $_I1l66["groups"] = explode(",", $_I1l66["groups"]);
      foreach($_I1l66["groups"] as $key => $_Q6ClO) {
        $_QJCJi = str_replace('name="groups[]" value="'.$_Q6ClO.'"', 'name="groups[]" value="'.$_Q6ClO.'" checked="checked"', $_QJCJi);
      }
      unset($_I1l66["groups"]);
    }

    if($_I1l66["GroupsOption"] == 2 && $_II6ft == 0){
      $_I1l66["GroupsOption"] = 1;
    }
    if($_II6ft == 0){
       $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', "document.getElementsByName('GroupsOption')[1].disabled = true;", $_QJCJi);
    }

    $_QJCJi = _OPFJA($errors, $_I1l66, $_QJCJi);

    print $_QJCJi;

  }

?>

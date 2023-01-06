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
  include_once("savedoptions.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeExportBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Jljf6 = "export.txt";
  $_Iio6I = array();
  $_Itfj8 = "";

  if (isset($_POST["OneMailingListId"]) ) {
     $OneMailingListId = intval($_POST["OneMailingListId"]);
     $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
     }
     else {
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000150"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
       $OneMailingListId = intval($_POST["OneMailingListId"]);
       $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
     }


  if(!_LAEJL($OneMailingListId)){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  if(!isset($_POST["MailingListName"])) {
    $_QLfol = "SELECT Name FROM $_QL88I WHERE id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    $_POST["MailingListName"] = $_QLO0f[0];
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
     $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000153"];
     $_POST["step"] = 2;
   }
  }

  if(isset($_POST["step"]) && $_POST["step"] == 5 && isset($_POST["RemoveExportFileBtn"])) {
    $_JlJLO = "";
    if($_QlCtl = fopen($_J1t6J.$_Jljf6, "w") )
       fclose($_QlCtl);
       else
       $_JlJLO = "Can't open file ".$_J1t6J.$_Jljf6;

    if(!unlink($_J1t6J.$_Jljf6))
       $_JlJLO .= "Can't remove file ".$_J1t6J.$_Jljf6;
    unset($_POST["step"]);
    if($_JlJLO == "")
       $_JlJLO = "OK";

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], $_Itfj8, 'exportrecipients', 'export7_snipped.htm');
    $_QLJfI = str_replace("[ERRORTEXT]", $_JlJLO, $_QLJfI);
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    print $_QLJfI;

    exit;
  }

  if(!isset($_POST["step"])) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], "", 'exportrecipients', 'export1_snipped.htm');
    $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
    $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);
    $_QLJfI = str_replace('/userfiles/export', $_J1t6J, $_QLJfI);

    print $_QLJfI;
    exit;
  } elseif($_POST["step"] == 1 ) {
      $_Jl8QO = _JOO1L("FileExportOptions");
      $_Jlt01 = $_POST;
      unset($_Jlt01["step"]);
      if( $_Jl8QO != "" ) {
       $_I016j = unserialize($_Jl8QO);
       if($_I016j !== false) {
         // feldzuordnung rausnehmen, der rest muss bleiben
         foreach($_I016j as $_IOLil => $_IOCjL) {
           if(!(strpos($_IOLil, "fields") === false))
              unset($_I016j[$_IOLil]);
         }
         if(isset($_I016j[SMLSWM_TOKEN_COOKIE_NAME]))
           unset($_I016j[SMLSWM_TOKEN_COOKIE_NAME]);
         $_Jlt01 = array_merge($_Jlt01, $_I016j);
       }
      }

      // zeige Step 2
      _L0FRP(array(), $_Jlt01);

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
      _L0FRP($errors, $_POST);
      exit;
    }


    if(count($errors) > 0 ) {
      _L0FRP($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["000020"]);
      exit;
    }

    $_QlCtl = fopen($_J1t6J.$_Jljf6, "w");
    if(!$_QlCtl) {
      _L0FRP($errors, $_POST, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000152"], $_J1t6J.$_Jljf6 ));
      exit;
    }



    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], $_Itfj8, 'exportrecipients', 'export3_snipped.htm');

    $_IOiJ0 = _L81DB($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>");
    $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language="._LRAFO($INTERFACE_LANGUAGE);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    // fields, mod 2 = 0 !!!
    $_QLoli = "";
    $_Qli6J=1;
    $_Ift08=1;
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if($_Qli6J == 1)
         $_Ql0fO = $_IOiJ0;
      $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', '<label for="fields'.$_Ift08.'">'.$_QLO0f["text"]."</label>", $_Ql0fO);
      $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', '<input type="checkbox" id="fields'.$_Ift08.'" name="fields['.$_QLO0f["fieldname"].']" value="1" />', $_Ql0fO);
      $_Qli6J++;
      $_Ift08++;
      if($_Qli6J>2) {
        $_Qli6J=1;
        $_QLoli .= $_Ql0fO;
        $_Ql0fO = "";
      }
    }
    if($_Ql0fO != "")
      $_QLoli .= $_Ql0fO;


    // optional fields, mod 2 = 0 !!!
    $_Ql0fO = '<tr><td colspan="4">&nbsp;</td></tr>'.'<tr><td colspan="4">'.$resourcestrings[$INTERFACE_LANGUAGE]["ExportOptionalFields"].'</td></tr>';
    $_QLoli .= $_Ql0fO;
    $_Ql0fO = "";

    $_QLO0f = array();
    $_QLO0f[] = "IsActive";
    $_QLO0f[] = "SubscriptionStatus";
    $_QLO0f[] = "DateOfSubscription";
    $_QLO0f[] = "DateOfOptInConfirmation";
    $_QLO0f[] = "IPOnSubscription";
    $_QLO0f[] = "IdentString";
    $_QLO0f[] = "PrivacyPolicyAccepted";
    $_QLO0f[] = "LastEMailSent";
    $_QLO0f[] = "BounceStatus";
    $_QLO0f[] = "SoftbounceCount";
    $_QLO0f[] = "HardbounceCount";
    $_QLO0f[] = "HistoryOfSendEMails";

    for($_QliOt=0; $_QliOt<count($_QLO0f); $_QliOt++){
      if($_Qli6J == 1)
         $_Ql0fO = $_IOiJ0;
      $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', '<label for="fields'.$_Ift08.'">'.$resourcestrings[$INTERFACE_LANGUAGE]["ExportOpt".$_QLO0f[$_QliOt]]."</label>", $_Ql0fO);
      $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', '<input type="checkbox" id="fields'.$_Ift08.'" name="fields['.$_QLO0f[$_QliOt].']" value="1" />', $_Ql0fO);
      $_Qli6J++;
      $_Ift08++;
      if($_Qli6J>2) {
        $_Qli6J=1;
        $_QLoli .= $_Ql0fO;
        $_Ql0fO = "";
      }
    }
    if($_Ql0fO != "")
      $_QLoli .= $_Ql0fO;

    $_QLJfI = _L81BJ($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>", $_QLoli);
    if(isset($_POST["step"]))
      unset($_POST["step"]);

    $_Jl8QO = _JOO1L("FileExportOptions");
    $_Jlt01 = $_POST;
    if( $_Jl8QO != "" ) {
       $_I016j = @unserialize($_Jl8QO);
       if($_I016j !== false) {
         // alles rausnehmen, nur die feldzuordnung bleibt
         foreach($_I016j as $_IOLil => $_IOCjL) {
           if(strpos($_IOLil, "fields") === false)
              unset($_I016j[$_IOLil]);
         }
         if(isset($_I016j[SMLSWM_TOKEN_COOKIE_NAME]))
           unset($_I016j[SMLSWM_TOKEN_COOKIE_NAME]);
         $_Jlt01 = array_merge($_Jlt01, $_I016j);
       }
    }

    $_QLJfI = _L8AOB($_Iio6I, $_Jlt01, $_QLJfI);

    print $_QLJfI;

  } elseif($_POST["step"] == 3 ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], "", 'exportrecipients', 'export4_snipped.htm');

    // save fields
    //
    unset($_POST["step"]);


    $_IiCfO = "";
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_IiCfO .= '<input type="hidden" name="fields['.$_IOLil.']" value="'.$_IOCjL.'" />';
      }
    }

    $_Jl8QO = $_POST;
    unset($_Jl8QO["OneMailingListId"]);
    unset($_Jl8QO["MailingListName"]);
    if(isset($_Jl8QO["PrevBtn"]))
      unset($_Jl8QO["PrevBtn"]);
    if(isset($_Jl8QO["NextBtn"]))
      unset($_Jl8QO["NextBtn"]);

    // umwandeln, damit er es wieder findet
    unset($_Jl8QO["fields"]);
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_Jl8QO["fields[$_IOLil]"] = $_IOCjL;
      }
    }


    if(isset($_Jl8QO[SMLSWM_TOKEN_COOKIE_NAME]))
       unset($_Jl8QO[SMLSWM_TOKEN_COOKIE_NAME]);

    _JOOFF("FileExportOptions", serialize($_Jl8QO));

    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IiCfO);

    print $_QLJfI;
  } elseif($_POST["step"] == 4 ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], "", 'exportrecipients', 'export5_snipped.htm');

    $_IiiJf = 0;
    $_Jl6OO = 0;
    if(isset($_POST["RowCount"]))
       $_IiiJf += $_POST["RowCount"];
    if(isset($_POST["ExportRowCount"]))
       $_Jl6OO += $_POST["ExportRowCount"];

    $_IJljf = fopen($_J1t6J.$_Jljf6, "a");


    $_QLfol = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `GroupsTableName`, `MailLogTableName` FROM `$_QL88I` WHERE id=".$_POST["OneMailingListId"];
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_Jj6jC = $_QLO0f["MaillistTableName"];
    $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
    $_QljJi = $_QLO0f["GroupsTableName"];
    $_I8jLt = $_QLO0f["MailLogTableName"];
    mysql_free_result($_QL8i1);

    $_JltIC = "";
    $_JlOQ6 = "";
    if($_POST["GroupsOption"] == 1){
      $_QLfol = "SELECT COUNT(`u_EMail`) FROM $_Jj6jC";
      if(isset($_POST["OnlyActiveRecipients"]) && $_POST["OnlyActiveRecipients"] == 1)
        $_JltIC = " WHERE IsActive <> 0";
    } else{
      if(!is_array($_POST["groups"]))
        $_QlJ8C = explode(",", $_POST["groups"]);
      $_JlOQ6 = "LEFT JOIN `$_IfJ66` ON `$_Jj6jC`.`id`=`$_IfJ66`.`Member_id`";
      $_QLfol = "SELECT COUNT(DISTINCT `$_Jj6jC`.`u_EMail`) FROM $_Jj6jC $_JlOQ6";
      if(isset($_POST["OnlyActiveRecipients"]) && $_POST["OnlyActiveRecipients"] == 1)
        $_JltIC = " WHERE IsActive <> 0";


      $_JloQO = array();
      foreach($_QlJ8C as $key => $_QltJO) {
         $_JloQO[] = " `$_IfJ66`.`groups_id` = ".intval($_QltJO);
      }
      if(count($_JloQO) != 0){
        if($_JltIC == "")
           $_JltIC = " WHERE ";
           else
           $_JltIC .= " AND ";
        $_JltIC .= " (". join(" OR ", $_JloQO) .") ";
      }
    }
    $_QLfol .= $_JltIC;
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    $_IlQll = $_QLO0f[0];
    mysql_free_result($_QL8i1);

    if(!isset($_POST["start"]))
      { // noch nie angefasst
        // UTF-8 BOM
        fwrite($_IJljf,  "ï»¿");
        if(isset($_POST["Header1Line"]) && $_POST["Header1Line"] != "" ) {

          $_Iflj0 = array();
          $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language="._LRAFO($INTERFACE_LANGUAGE);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
             $_Iflj0[$_QLO0f["fieldname"]] = unhtmlentities( $_QLO0f["text"], $_QLo06 );
          }
          mysql_free_result($_QL8i1);

          // fields optionally
          $_Iflj0["IsActive"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptIsActive"], "utf-8");
          $_Iflj0["SubscriptionStatus"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptSubscriptionStatus"], "utf-8");
          $_Iflj0["DateOfSubscription"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptDateOfSubscription"], "utf-8");
          $_Iflj0["DateOfOptInConfirmation"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptDateOfOptInConfirmation"], "utf-8");
          $_Iflj0["IPOnSubscription"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptIPOnSubscription"], "utf-8");
          $_Iflj0["IdentString"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptIdentString"], "utf-8");
          $_Iflj0["PrivacyPolicyAccepted"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptPrivacyPolicyAccepted"], "utf-8");
          $_Iflj0["LastEMailSent"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptLastEMailSent"], "utf-8");
          $_Iflj0["BounceStatus"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptBounceStatus"], "utf-8");
          $_Iflj0["SoftbounceCount"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptSoftbounceCount"], "utf-8");
          $_Iflj0["HardbounceCount"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptHardbounceCount"], "utf-8");
          $_Iflj0["HistoryOfSendEMails"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptHistoryOfSendEMails"], "utf-8");
          $_Iflj0["GroupsRelations"] = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ExportOptGroupsRelations"], "utf-8");

          $_IOJoI = $_POST["fields"];
          reset($_IOJoI);

          $_I1OoI = array();
          foreach($_IOJoI as $_IOLil => $_IOCjL) {
            if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
              if(isset($_POST["AddQuotes"]) && $_POST["AddQuotes"] != "")
                 $_I1OoI[] = _LRBC0($_Iflj0[$_IOLil]);
                else
                 $_I1OoI[] = $_Iflj0[$_IOLil];
            }

          }
          fwrite($_IJljf, join($_POST["Separator"], $_I1OoI).$_QLl1Q );

        }
      }

    if(!isset($_POST["start"]) ) {
      $_Iil6i = 0;
    } else {
      $_Iil6i = $_POST["start"];
      unset($_POST["start"]);
    }

    unset($_POST["step"]);

    // use ever yyyy-mm-dd
    $_j01CJ = "'%d.%m.%Y'";
    if($INTERFACE_LANGUAGE != "de") {
       $_j01CJ = "'%Y-%m-%d'";
    }

    $_jtiJJ = "";
    $_IiCfO = "";
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    $_JlofI = false;
    $_JlCJ6 = false;
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_IiCfO .= '<input type="hidden" name="fields['.$_IOLil.']" value="'.$_IOCjL.'" />';
        if($_jtiJJ == ""){
            if($_IOLil == 'u_Birthday')
              $_jtiJJ = "DATE_FORMAT($_IOLil, $_j01CJ)";
            else
              $_jtiJJ = $_IOLil;
          }
          else
          if($_IOLil == 'u_Birthday') {
            $_jtiJJ .= ", ". "DATE_FORMAT($_IOLil, $_j01CJ)";
            } else if($_IOLil == 'HistoryOfSendEMails') {
              $_JlofI = true;
              $_jtiJJ .= ", ". "`$_I8jLt`.`MailLog` AS HistoryOfSendEMails";
            }  else if($_IOLil == 'GroupsRelations') {
              $_JlCJ6 = true;
              $_jtiJJ .= ", ". "1 AS GroupsRelations";
            }
            else
              $_jtiJJ .= ", ".$_IOLil;
      }
    }
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IiCfO);

    $_IilfC = substr($_QLJfI, 0, strpos($_QLJfI, "<BLOCK />") - 1);
    $_QLJfI = substr($_QLJfI, strpos($_QLJfI, "<BLOCK />") + strlen("<BLOCK />"));

    // progress
    if($_IlQll > 0)
      $_QlOjt = sprintf("%d", $_Iil6i * 100 / $_IlQll );
      else
      $_QlOjt = 0;
    // progressbar macht bei 0 mist
    if($_QlOjt == 0)
      $_QlOjt = 1;
    $_IilfC = _L81BJ($_IilfC, "<SHOW:PERCENT>", "</SHOW:PERCENT>", $_QlOjt);

    if(!defined("SWM")) { # in first version of SML LastEmailSent not exists
      $_JliIC = array();
      _L8EOB($_Jj6jC, $_JliIC);
      if(!array_search("LastEMailSent", $_JliIC)) {
        $_QLfol = "ALTER TABLE `$_Jj6jC` ADD `LastEMailSent` DATETIME NULL AFTER `IdentString`";
        mysql_query($_QLfol);
      }
    }

    if(isset($_POST["groups"]))
       $_jtiJJ = "DISTINCT ".$_jtiJJ;

    if($_JlofI) {
      $_JlOQ6 .= " LEFT JOIN `$_I8jLt` ON `$_I8jLt`.`Member_id`=`$_Jj6jC`.`id`";
    }

    $_QLfol = "SELECT $_jtiJJ, `$_Jj6jC`.`id` FROM `$_Jj6jC` $_JlOQ6 ".$_JltIC;

    $_QLfol .= " ORDER BY id LIMIT $_Iil6i, ".intval($_POST["ExportLines"]);

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
        fclose($_IJljf);
        $_Itfj8 = "";
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], $_Itfj8, 'exportrecipients', 'export6_snipped.htm');
        $_QLJfI = _L81BJ($_QLJfI, "<EXPORT:FILECOUNT>", "</EXPORT:FILECOUNT>", $_IlQll);
        $_QLJfI = str_replace("DOWNLOAD", $_jfOJj."export/".$_Jljf6, $_QLJfI);
        $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
        print $_QLJfI;
        exit;
    }

    // output here, if eof() than do nothing
    print $_IilfC;
    flush();

    $_j1881 = 0;
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if(isset($_QLO0f["HistoryOfSendEMails"]))
         $_QLO0f["HistoryOfSendEMails"] = str_replace($_QLl1Q, '\n', $_QLO0f["HistoryOfSendEMails"]);

      _LRCOC();

      if($_JlCJ6){
        $_QLfol = "SELECT `$_QljJi`.`Name` FROM `$_QljJi` LEFT JOIN `$_IfJ66` ON `$_IfJ66`.`Member_id`=$_QLO0f[id] WHERE `$_IfJ66`.`groups_id`=`$_QljJi`.`id`";
        $_JliLI = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_QLO0f["GroupsRelations"] = array();
        while($_JCC81 = mysql_fetch_assoc($_JliLI)){
          $_QLO0f["GroupsRelations"][] = $_JCC81["Name"];
        }
        $_QLO0f["GroupsRelations"] = join($_POST["Separator"] == "," ? ";" : ",", $_QLO0f["GroupsRelations"]);
        mysql_free_result($_JliLI);
      }

      if(!empty($_POST["AddQuotes"])) {
        reset($_QLO0f);
        foreach($_QLO0f as $key => $_QltJO) {
          $_QLO0f[$key] = _LRBC0(unhtmlentities($_QltJO, "utf-8"));
        }
      } else {
        reset($_QLO0f);
        foreach($_QLO0f as $key => $_QltJO) {
          $_QLO0f[$key] = unhtmlentities($_QltJO, "utf-8");
        }
      }
      unset($_QLO0f["id"]); /* id field */

      fwrite($_IJljf, join($_POST["Separator"], $_QLO0f).$_QLl1Q );
      $_j1881++;
    }

    mysql_free_result($_QL8i1);

    $_Iil6i += $_j1881;
    $_Jl6OO += $_j1881;

    print '<input type="hidden" name="start" value="'.$_Iil6i.'" />';
    print '<input type="hidden" name="ExportRowCount" value="'.$_Jl6OO.'" />';

    fclose($_IJljf);

    print $_QLJfI;
  }


  function _L0FRP($errors, $_Io0OJ, $_Itfj8 = "") {
    global $_J1t6J, $resourcestrings, $INTERFACE_LANGUAGE, $OneMailingListId, $_QL88I, $_QLttI;
    global $UserType, $Username, $_QLl1Q;
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000150"], $_Itfj8, 'exportrecipients', 'export2_snipped.htm');

    if(isset($_Io0OJ["step"]))
      unset($_Io0OJ["step"]);

    $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
    $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);

    if(isset($_Io0OJ["MailingListName"]))
      unset($_Io0OJ["MailingListName"]);

    if (!isset($_Io0OJ["Separator"]) )
      $_QLJfI = str_replace('name="Separator"', 'name="Separator" value=","', $_QLJfI);
    if (!isset($_Io0OJ["ExportLines"]) )
       $_QLJfI = str_replace('name="ExportLines"', 'name="ExportLines" value="200"', $_QLJfI);
    if (!isset($_Io0OJ["Header1Line"]) )
       $_QLJfI = str_replace('name="Header1Line"', 'name="Header1Line" checked="checked"', $_QLJfI);
    if (!isset($_Io0OJ["AddQuotes"]) )
       $_QLJfI = str_replace('name="AddQuotes"', 'name="AddQuotes" checked="checked"', $_QLJfI);
    if (!isset($_Io0OJ["GroupsOption"]) )
       $_Io0OJ["GroupsOption"] = 1;


    // ********* List of Groups SQL query
    $_QLfol = "SELECT GroupsTableName FROM $_QL88I WHERE id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_QljJi = $_QLO0f["GroupsTableName"];

    $_QlI6f = "SELECT DISTINCT id, Name FROM $_QljJi";
    $_QlI6f .= " ORDER BY Name ASC";
    $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
    _L8D88($_QlI6f);
    $_ItlLC = "";
    $_IC1C6 = _L81DB($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>");
    $_ICQjo = 0;
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_ItlLC .= $_IC1C6;

      $_ItlLC = _L81BJ($_ItlLC, "<GroupsId>", "</GroupsId>", $_QLO0f["id"]);
      $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_QLO0f["id"]);
      $_ItlLC = _L81BJ($_ItlLC, "<GroupsName>", "</GroupsName>", $_QLO0f["Name"]);
      $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_QLO0f["Name"]);
      $_ICQjo++;
      $_ItlLC = str_replace("GroupsLabelId", 'groupchkbox_'.$_ICQjo, $_ItlLC);
    }

    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_ItlLC);
    mysql_free_result($_QL8i1);
    // ********* List of Groupss query END


    if(isset($_Io0OJ["groups"])) {
      if(!is_array($_Io0OJ["groups"]))
        $_Io0OJ["groups"] = explode(",", $_Io0OJ["groups"]);
      foreach($_Io0OJ["groups"] as $key => $_QltJO) {
        $_QLJfI = str_replace('name="groups[]" value="'.$_QltJO.'"', 'name="groups[]" value="'.$_QltJO.'" checked="checked"', $_QLJfI);
      }
      unset($_Io0OJ["groups"]);
    }

    if($_Io0OJ["GroupsOption"] == 2 && $_ICQjo == 0){
      $_Io0OJ["GroupsOption"] = 1;
    }
    if($_ICQjo == 0){
       $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', "document.getElementsByName('GroupsOption')[1].disabled = true;", $_QLJfI);
    }

    $_QLJfI = _L8AOB($errors, $_Io0OJ, $_QLJfI);

    print $_QLJfI;

  }

?>

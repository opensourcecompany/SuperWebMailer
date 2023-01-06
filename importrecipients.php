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
  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("newslettersubunsub_ops.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeImportBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(!function_exists("ImportErrorCheckOnShutdown")) {
    function ImportErrorCheckOnShutdown(){
     if(!function_exists("error_get_last")) return;
     $_I1Ilj = error_get_last();
     if(!$_I1Ilj) return;

     if( !($_I1Ilj["type"] == E_ERROR || $_I1Ilj["type"] == E_RECOVERABLE_ERROR || $_I1Ilj["type"] == E_USER_ERROR) ) return;

     $_JIL6C = sprintf("Fatal PHP ERROR type=%d; message=%s; file=%s; line=%d", $_I1Ilj["type"], $_I1Ilj["message"], $_I1Ilj["file"], $_I1Ilj["line"]);
     print $_JIL6C;
    }
  }

  $_ItLJj = array(".txt", ".csv");

  $_JjQjl = "importfile_errors.txt";
  $_JjIQL = $_J1t6J.$_JjQjl;

  $_Iio6I = array();
  $_Itfj8 = "";
  if (isset($_POST["OneMailingListId"]) )
     $OneMailingListId = $_POST["OneMailingListId"];
     else {
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000054"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
       $OneMailingListId = $_POST["OneMailingListId"];
     }
  if(isset($OneMailingListId))
     $OneMailingListId = intval($OneMailingListId);
  if(isset($_POST["OneMailingListId"]))
     $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);

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
     $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000058"];
     $_POST["step"] = 2;
     $_Iio6I[] = "fields[u_EMail]";
   } else {
     if( !isset($_POST["fields"]["u_EMail"]) || $_POST["fields"]["u_EMail"] == "-1" ) {
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000059"];
       $_POST["step"] = 2;
       $_Iio6I[] = "fields[u_EMail]";
     }
   }
  }

  if(!isset($_POST["step"])) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], "", 'importrecipients', 'import0_snipped.htm');
    $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
    $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);
    print $_QLJfI;
    exit;
  }
  elseif($_POST["step"] == 0 ) {

    if($_POST["ImportOption"] == "DB") {
      $_POST["DBType"] = "MYSQL";
      include_once("importrecipientsmysql.inc.php");
      exit;
    }

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], "", 'importrecipients_csv', 'import1_snipped.htm');
    $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
    $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);
    $_QLJfI = str_replace('/userfiles/import', $_ItL8f, $_QLJfI);
    $_It18j = ini_get("upload_max_filesize");
    if(!isset($_It18j) || $_It18j == "")
      $_It18j = "2M";
    if(!(strpos($_It18j, "G") === false))
       $_ItQIo = intval($_It18j) * 1024 * 1024 * 1024;
       else
       if(!(strpos($_It18j, "M") === false))
          $_ItQIo = intval($_It18j) * 1024 * 1024;
          else
          if(!(strpos($_It18j, "K") === false))
             $_ItQIo = intval($_It18j) * 1024;
             else
             $_ItQIo = intval($_It18j) * 1;
    if($_ItQIo == 0)
      $_ItQIo = 2 * 1024 * 1024;
    $_It18j .= "B";
    $_QLJfI = str_replace('upload_max_filesize', $_It18j, $_QLJfI);
    $_QLJfI = str_replace('name="MAX_FILE_SIZE"', 'name="MAX_FILE_SIZE" value="'.$_ItQIo.'"', $_QLJfI);

    print $_QLJfI;
    exit;
  } elseif($_POST["step"] == 1 ) {

      $_ItiQj = true;
      if( isset( $_FILES['file1'] ) && $_FILES['file1']['tmp_name'] != "" && $_FILES['file1']['name'] != "" && ( $_ItiQj = in_array( strtolower(ExtractFileExt($_FILES['file1']['name'])), $_ItLJj) ) ) {
        // upload akzeptieren
        if (move_uploaded_file($_FILES['file1']['tmp_name'], $_ItL8f.$_FILES['file1']['name'])){
           $_POST["ImportFilename"] = $_FILES['file1']['name'];
           @chmod($_ItL8f.$_FILES['file1']['name'], 0777);
        } else {
           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000055"], $_FILES['file1']['tmp_name'], $_ItL8f.$_FILES['file1']['name'] ), 'importrecipients_csv', 'import1_snipped.htm');
           $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
           $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);
           $_QLJfI = str_replace('/userfiles/import', $_ItL8f, $_QLJfI);
           $_It18j = ini_get("upload_max_filesize");
           if(!isset($_It18j) || $_It18j == "")
             $_It18j = "2M";
           if(!(strpos($_It18j, "G") === false))
              $_ItQIo = intval($_It18j) * 1024 * 1024 * 1024;
              else
              if(!(strpos($_It18j, "M") === false))
                 $_ItQIo = intval($_It18j) * 1024 * 1024;
                 else
                 if(!(strpos($_It18j, "K") === false))
                    $_ItQIo = intval($_It18j) * 1024;
                    else
                    $_ItQIo = intval($_It18j) * 1;
           if($_ItQIo == 0)
             $_ItQIo = 2 * 1024 * 1024;
           $_It18j .= "B";
           $_QLJfI = str_replace('upload_max_filesize', $_It18j, $_QLJfI);
           $_QLJfI = str_replace('name="MAX_FILE_SIZE"', 'name="MAX_FILE_SIZE" value="'.$_ItQIo.'"', $_QLJfI);

           $errors[] = "file1";
           $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

           print $_QLJfI;
           exit;
        }
      } else {
        if(!$_ItiQj){

           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorFileTypeNotAllowed"], 'importrecipients_csv', 'import1_snipped.htm');

           $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
           $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);
           $_QLJfI = str_replace('/userfiles/import', $_ItL8f, $_QLJfI);
           $_It18j = ini_get("upload_max_filesize");
           if(!isset($_It18j) || $_It18j == "")
             $_It18j = "2M";
           if(!(strpos($_It18j, "G") === false))
              $_ItQIo = intval($_It18j) * 1024 * 1024 * 1024;
              else
              if(!(strpos($_It18j, "M") === false))
                 $_ItQIo = intval($_It18j) * 1024 * 1024;
                 else
                 if(!(strpos($_It18j, "K") === false))
                    $_ItQIo = intval($_It18j) * 1024;
                    else
                    $_ItQIo = intval($_It18j) * 1;
           if($_ItQIo == 0)
             $_ItQIo = 2 * 1024 * 1024;
           $_It18j .= "B";
           $_QLJfI = str_replace('upload_max_filesize', $_It18j, $_QLJfI);
           $_QLJfI = str_replace('name="MAX_FILE_SIZE"', 'name="MAX_FILE_SIZE" value="'.$_ItQIo.'"', $_QLJfI);

           $errors[] = "file1";
           $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

           print $_QLJfI;
           exit;

        }
      }

      $_IiC0o = _JOO1L("FileImportOptions");
      $_IiCft = $_POST;
      unset($_IiCft["step"]);
      if( $_IiC0o != "" ) {
       $_I016j = @unserialize($_IiC0o);
       if($_I016j !== false) {
         // feldzuordnung rausnehmen, der rest muss bleiben
         foreach($_I016j as $_IOLil => $_IOCjL) {
           if(!(strpos($_IOLil, "fields") === false))
              unset($_I016j[$_IOLil]);
         }
         if(isset($_I016j[SMLSWM_TOKEN_COOKIE_NAME]))
           unset($_I016j[SMLSWM_TOKEN_COOKIE_NAME]);
         if(isset($_I016j["ImportFilename"]) && isset($_IiCft["ImportFilename"]))
           unset($_I016j["ImportFilename"]);
         $_IiCft = array_merge($_IiCft, $_I016j);
       }
      }

      // zeige Step 2
      _L0FRP(array(), $_IiCft);

  } elseif($_POST["step"] == 2 ) {

    $errors = array();

    if ( !isset($_POST["PrevBtn"])) { // no check on prev btn
      if(!isset($_POST["ImportFilename"]) || trim($_POST["ImportFilename"]) == "") {
       $errors[] = "ImportFilename";
      }
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

    $_QlCtl = fopen($_ItL8f.$_POST["ImportFilename"], "r");
    if(!$_QlCtl) {
      _L0FRP($errors, $_POST, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000056"], $_ItL8f.$_POST["ImportFilename"]));
      exit;
    }

    $_Ift08 = "";
    // mac file?
    $_IO88Q = 0;
    while (!feof($_QlCtl)) {
      $_Ift08 = fgetc($_QlCtl);
      if($_Ift08 == chr(10) || $_Ift08 == chr(13)) {

        $_IOtfO = chr(10);
        if(!feof($_QlCtl))
          $_IOtfO = fgetc($_QlCtl);

        if($_Ift08 == chr(13) && $_IOtfO != chr(10)) {
          $_IO88Q = 1;
        }
        break;
      }
    }
    $_POST["IsMacFile"] = $_IO88Q;

    $_IOO6C = ftell($_QlCtl) -1;
    fseek($_QlCtl, 0);
    $_IOoJ0 = fread($_QlCtl, $_IOO6C);
    fclose($_QlCtl);

    // UTF8 BOM?
    $_IOC1j = 'ï»¿';
    $_QlOjt = strpos($_IOoJ0, $_IOC1j);
    if($_QlOjt !== false && $_QlOjt == 0)
      $_IOoJ0 = substr($_IOoJ0, strlen($_IOC1j));

    // UTF8?
    if ( !( isset($_POST["IsUTF8"]) && $_POST["IsUTF8"] != "") ) {
       $_I0Clj = utf8_encode($_IOoJ0);
       if($_I0Clj != "")
         $_IOoJ0 = $_I0Clj;
    }


    $_IO1C0 = trim($_POST["Separator"]);
    if($_IO1C0 == '\t')
       $_IO1C0 = "\t";
    $_I1OoI = explode($_IO1C0, $_IOoJ0);
    $_I0Clj = "";
    for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++){
        $_IOCjL = $_I1OoI[$_Qli6J];
        if(isset($_POST["RemoveQuotes"]) && $_POST["RemoveQuotes"] != "") {
          $_IOCjL = str_replace('"', '', $_IOCjL);
          $_IOCjL = str_replace("\'", '', $_IOCjL);
          $_IOCjL = str_replace("`", '', $_IOCjL);
          $_IOCjL = str_replace("´", '', $_IOCjL);
        }
        if(isset($_POST["RemoveSpaces"]) && $_POST["RemoveSpaces"] != "") {
          $_IOCjL = trim($_IOCjL);
        }

        $_I0Clj .= sprintf('<option value="%s">%s</option>'.$_QLl1Q, $_Qli6J, htmlentities($_IOCjL, ENT_COMPAT, $_QLo06)  );
    }
    $_I0Clj = $_QLl1Q.sprintf('<option value="%s">%s</option>'.$_QLl1Q, "-1", "--"  ).$_QLl1Q.$_I0Clj;

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_Itfj8, 'importrecipients_csv', 'import3_snipped.htm');

    $_IOiJ0 = _L81DB($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>");
    $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    $_QLoli = "";
    $_Qli6J=1;
    $_Ift08=1;
    $_IOLjO = 0;
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if($_Qli6J == 1)
         $_Ql0fO = $_IOiJ0;
      $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', '<label for="importfield'.$_Ift08.'">'.$_QLO0f["text"]."</label>", $_Ql0fO);
      $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', '<select name="fields['.$_QLO0f["fieldname"].']" size="1" id="importfield'.$_Ift08.'" class="import_select">'.$_I0Clj.'</select>', $_Ql0fO);
      if($_QLO0f["fieldname"] == "u_Birthday") {
        $_IOLjO = $_Qli6J;
      }
      $_Qli6J++;
      $_Ift08++;
      if($_Qli6J>2) {
        $_Qli6J=1;
        $_QLoli .= $_Ql0fO;
        if($_IOLjO) {
          $_Ql0fO = $_IOiJ0;
          if($_IOLjO == 1){
            $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', '<label for="importfield'.$_Ift08.'">'.$resourcestrings[$INTERFACE_LANGUAGE]["000057"]."</label>", $_Ql0fO);
            $_I016j = '<option value="dd.mm.yyyy">dd.mm.yyyy</option>';
            $_I016j .= '<option value="yyyy-mm-dd">yyyy-mm-dd</option>';
            $_I016j .= '<option value="mm-dd-yyyy">mm-dd-yyyy</option>';
            $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', '<select name="BirthdayDateFormat" size="1" id="importfield'.$_Ift08.'" class="import_select">'.$_I016j.'</select>', $_Ql0fO);
            $_Qli6J++;
            $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', "&nbsp;", $_Ql0fO);
            $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', "&nbsp;", $_Ql0fO);
          } else{
            $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', "&nbsp;", $_Ql0fO);
            $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', "&nbsp;", $_Ql0fO);
            $_Qli6J++;
            $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', '<label for="importfield'.$_Ift08.'">'.$resourcestrings[$INTERFACE_LANGUAGE]["000057"]."</label>", $_Ql0fO);
            $_I016j = '<option value="dd.mm.yyyy">dd.mm.yyyy</option>';
            $_I016j .= '<option value="yyyy-mm-dd">yyyy-mm-dd</option>';
            $_I016j .= '<option value="mm-dd-yyyy">mm-dd-yyyy</option>';
            $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', '<select name="BirthdayDateFormat" size="1" id="importfield'.$_Ift08.'" class="import_select">'.$_I016j.'</select>', $_Ql0fO);
          }
          $_Qli6J=1;
          $_QLoli .= $_Ql0fO;
          $_IOLjO = 0;
          $_Ift08++;
        }
        $_Ql0fO = "";
      }
    }
    if($_Ql0fO != "")
      $_QLoli .= $_Ql0fO;

    $_QLJfI = _L81BJ($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>", $_QLoli);
    if(isset($_POST["step"]))
      unset($_POST["step"]);

    // ************** Groups START
    $_QLfol = "SELECT GroupsTableName FROM $_QL88I WHERE id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_IOLJ1 = mysql_fetch_array($_QL8i1);
    $_QljJi = $_IOLJ1["GroupsTableName"];
    mysql_free_result($_QL8i1);

    // ********* List of Groups SQL query
    $_QlI6f = "SELECT DISTINCT id, Name FROM $_QljJi";
    $_QlI6f .= " ORDER BY Name ASC";
    $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
    _L8D88($_QlI6f);
    $_ItlLC = "";
    while($_IOLJ1=mysql_fetch_array($_QL8i1)) {
      $_ItlLC .= '<option value="'.$_IOLJ1["id"].'">'.$_IOLJ1["Name"].'</option>'.$_QLl1Q;
    }
    mysql_free_result($_QL8i1);

    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_ItlLC);
    // ********* List of Groups query END

    // ************** Groups END

    // Groups assignment START
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:IMPORTFIELD>", "</SHOW:IMPORTFIELD>", $_I0Clj);
    // Groups assignment END

    $_IiC0o = _JOO1L("FileImportOptions");
    $_IiCft = $_POST;
    if( $_IiC0o != "" ) {
       $_I016j = @unserialize($_IiC0o);
       if($_I016j !== false) {
         // alles rausnehmen, nur die feldzuordnung bleibt
         foreach($_I016j as $_IOLil => $_IOCjL) {
           if(strpos($_IOLil, "fields") === false)
              unset($_I016j[$_IOLil]);
              else
              if(is_array($_IOCjL)) // bug while saving
                unset($_I016j[$_IOLil]);
         }
         if(isset($_I016j[SMLSWM_TOKEN_COOKIE_NAME]))
           unset($_I016j[SMLSWM_TOKEN_COOKIE_NAME]);
         if(isset($_I016j["ImportFilename"]) && isset($_IiCft["ImportFilename"]))
           unset($_I016j["ImportFilename"]);
         $_IiCft = array_merge($_IiCft, $_I016j);
       }
    }

    if(!isset($_IiCft["GroupsOption"]) || $_IiCft["GroupsOption"] <= 0)
       $_IiCft["GroupsOption"] = 1;
    if(!isset($_IiCft["ExImportOption"]) || $_IiCft["ExImportOption"] <= 0)
       $_IiCft["ExImportOption"] = 1;
    $_QLJfI = _L8AOB($_Iio6I, $_IiCft, $_QLJfI);

    print $_QLJfI;

  } elseif($_POST["step"] == 3 ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], "", 'importrecipients_csv', 'import4_snipped.htm');

    if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 3 && !isset($_POST["groups_id"]) ) {
      $_POST["GroupsOption"] = 1;
    }

    if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && (!isset($_POST["ImportGroupField"]) || $_POST["ImportGroupField"] < 0) ) {
      $_POST["GroupsOption"] = 1;
    }

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

    $_IiC0o = $_POST;
    if(isset($_IiC0o["IsMacFile"]))
      unset($_IiC0o["IsMacFile"]);
    unset($_IiC0o["OneMailingListId"]);
    unset($_IiC0o["MailingListName"]);
    if(isset($_IiC0o["PrevBtn"]))
      unset($_IiC0o["PrevBtn"]);
    if(isset($_IiC0o["NextBtn"]))
      unset($_IiC0o["NextBtn"]);


    // umwandeln, damit er es wieder findet
    unset($_IiC0o["fields"]);
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_IiC0o["fields[$_IOLil]"] = $_IOCjL;
      }
    }

    if(isset($_IiC0o[SMLSWM_TOKEN_COOKIE_NAME]))
      unset($_IiC0o[SMLSWM_TOKEN_COOKIE_NAME]);
    
    _JOOFF("FileImportOptions", serialize($_IiC0o));

    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IiCfO);

    print $_QLJfI;
  } elseif($_POST["step"] == 4 ) {

    if ( defined("DEMO") ) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], "", 'DISABLED', 'demo_snipped.htm');
      print $_QLJfI;
      exit;
    }

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], "", 'importrecipients_csv', 'import5_snipped.htm');

    $_IiiJf = 0;
    $_Iii6I = 0;
    if(isset($_POST["RowCount"]))
       $_IiiJf += $_POST["RowCount"];
    if(isset($_POST["ImportRowCount"]))
       $_Iii6I += $_POST["ImportRowCount"];

    if(isset($_POST["IsMacFile"]) && $_POST["IsMacFile"])
       ini_set('auto_detect_line_endings', TRUE);

    $_Jj68f = _JOLQE("ECGListCheck");

    $_IJljf = fopen($_ItL8f.$_POST["ImportFilename"], "r");
    $_If61l = fstat($_IJljf);
    $_QlOjt = 0;

    // spring zur fileposition
    if(isset($_POST["fileposition"])) {
        if(!empty($_POST["CreateErrorLog"])){
          $_JjJJl = @fopen($_JjIQL, "a");
        }

        $_Iiioi = $_POST["fileposition"];
        if($_Iiioi >= 0)
           fseek($_IJljf, $_Iiioi);

        // sind wir fertig?
        if($_Iiioi == -1 || feof($_IJljf) || ftell($_IJljf) >= $_If61l["size"] ) {
          fclose($_IJljf);
          $_Itfj8 = "";
          if( isset($_POST["RemoveFile"]) && $_POST["RemoveFile"] != "" ) {
            if( !@unlink($_ItL8f.$_POST["ImportFilename"]) ) {
              $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000060"], $_ItL8f.$_POST["ImportFilename"]);
            }
          }
          $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_Itfj8, 'importrecipients_csv', 'import6_snipped.htm');
          $_QLJfI = _L81BJ($_QLJfI, "<IMPORT:FILECOUNT>", "</IMPORT:FILECOUNT>", $_IiiJf);
          $_QLJfI = _L81BJ($_QLJfI, "<IMPORT:IMPORTCOUNT>", "</IMPORT:IMPORTCOUNT>", $_Iii6I);
          if(empty($_POST["CreateErrorLog"]))
             $_QLJfI = _L80DF($_QLJfI, "<IF:ERRORLOG>", "</IF:ERRORLOG>");
             else{
               $_QLJfI = str_replace("ERROR_LOGFILENAME", $_jfOJj."export/".$_JjQjl, $_QLJfI);
               $_QLJfI = str_replace("<IF:ERRORLOG>", "", $_QLJfI);
               $_QLJfI = str_replace("</IF:ERRORLOG>", "", $_QLJfI);
             }
          $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
          print $_QLJfI;
          exit;
        }

      }
      else { // noch nie angefasst
        if(!empty($_POST["CreateErrorLog"])){
          $_JjJJl = @fopen($_JjIQL, "w");
        }
        // UTF8 BOM test
        $_IOC1j = 'ï»¿';
        $_IiLOj = fread($_IJljf, strlen($_IOC1j));
        if($_IiLOj != $_IOC1j)
          fseek($_IJljf, 0);

        // Zeile 1 weg?
        $_QlOjt = 0;
        if(!empty($_POST["Header1Line"])) {
           $_IiLOj = fgets($_IJljf, 4096);
           $_QlOjt = 1;
        }
      }

    if(!isset($_POST["start"]) ) {
      $_Iil6i = $_QlOjt;
    } else {
      $_Iil6i = $_POST["start"];
      unset($_POST["start"]);
    }

    unset($_POST["step"]);

    $_IiCfO = "";
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_IiCfO .= '<input type="hidden" name="fields['.$_IOLil.']" value="'.$_IOCjL.'" />';
      }
    }
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IiCfO);

    $_IilfC = substr($_QLJfI, 0, strpos($_QLJfI, "<BLOCK />") - 1);
    $_QLJfI = substr($_QLJfI, strpos($_QLJfI, "<BLOCK />") + strlen("<BLOCK />"));

    // progress
    if($_If61l["size"] > 0)
       $_QlOjt = sprintf("%d", ftell($_IJljf) * 100 / $_If61l["size"] );
    // progressbar macht bei 0 mist
    if($_QlOjt == 0)
      $_QlOjt = 1;
    $_IilfC = _L81BJ($_IilfC, "<SHOW:PERCENT>", "</SHOW:PERCENT>", $_QlOjt);

    print $_IilfC;
    flush();

    $_QLfol = "SELECT * FROM $_QL88I WHERE id=".$_POST["OneMailingListId"];
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_Jj6jC = $_QLO0f["MaillistTableName"];
    $_I8jjj = $_QLO0f["StatisticsTableName"];
    $_QljJi = $_QLO0f["GroupsTableName"];
    $_IfJoo = $_QLO0f["FormsTableName"];
    $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
    $FormId = $_QLO0f["forms_id"];
    $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
    $_Jj6f0 = $_QLO0f["LocalDomainBlocklistTableName"];
    mysql_free_result($_QL8i1);
    $_I80Jo = $_QLO0f;

    if(isset($_POST["SendOptInEMail"]) && $_POST["SendOptInEMail"] != "" ) {
      $_QLfol = "SELECT $_IfJoo.*, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfJoo LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfJoo.messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfJoo.ThemesId WHERE $_IfJoo.id=$FormId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_Jj08l["MailingListId"] = $_I80Jo["id"];
      $_Jj08l["FormId"] = $FormId;
    }

    $_IO1C0 = $_POST["Separator"];
    if($_IO1C0 == '\t')
       $_IO1C0 = "\t";

    register_shutdown_function('ImportErrorCheckOnShutdown');

    // hier liest er die Zeilen
    for($_Qli6J=$_Iil6i; ($_Qli6J<$_Iil6i + $_POST["ImportLines"]) && !feof($_IJljf); $_Qli6J++) {

      // reset errros
      if(isset($errors) && count($errors) > 0) {
        unset($errors);
      }
      if( !isset($errors) )
        $errors = array();

      if(isset($_I816i) && count($_I816i) > 0) {
        unset($_I816i);
        $_I816i = array();
      }
      if( !isset($_I816i) )
        $_I816i = array();
      //

      _LRCOC();
      $_IiiJf++;
      $_IiLOj = fgets($_IJljf, 4096);
      // UTF8?
      if ( !( isset($_POST["IsUTF8"]) && $_POST["IsUTF8"] != "") ) {
         $_I0Clj = utf8_encode($_IiLOj);
         if($_I0Clj != "")
           $_IiLOj = $_I0Clj;
      }

      $_I0QjQ = explode($_IO1C0, $_IiLOj);

      $_Jj6L1 = array();
      if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 ) {
        if($_POST["ImportGroupField"] < count($_I0QjQ)) {
           $_Jj6L1 = $_I0QjQ[$_POST["ImportGroupField"]];

           if(isset($_POST["RemoveQuotes"]) && $_POST["RemoveQuotes"] != "") {
             $_Jj6L1 = str_replace('"', '', $_Jj6L1);
             $_Jj6L1 = str_replace("\'", '', $_Jj6L1);
             $_Jj6L1 = str_replace("`", '', $_Jj6L1);
             $_Jj6L1 = str_replace("´", '', $_Jj6L1);
           }
           if(isset($_POST["RemoveSpaces"]) && $_POST["RemoveSpaces"] != "") {
             $_Jj6L1 = trim($_Jj6L1);
           }

           $_Jjf0J = ",";
           if($_IO1C0 == ",")
             $_Jjf0J = ";";
           $_Jj6L1 = explode($_Jjf0J, $_Jj6L1);
           for($_JjfO0=0; $_JjfO0<count($_Jj6L1); $_JjfO0++)
             $_Jj6L1[$_JjfO0] = str_replace(",", "", $_Jj6L1[$_JjfO0]);
        }
      }

      $_QLfol = "INSERT IGNORE INTO $_Jj6jC SET DateOfSubscription=NOW()";

      $_JjfiL = false;
      $_Jj8iQ = "";
      if(isset($_POST["SendOptInEMail"]) && $_POST["SendOptInEMail"] != "" ) {
        $_Jj8iQ = "'OptInConfirmationPending'";
        $_QLfol .= ", SubscriptionStatus=$_Jj8iQ";
        $_JjfiL = true;
        }
        else {
          $_Jj8iQ = "'Subscribed'";
          $_QLfol .= ", SubscriptionStatus=$_Jj8iQ, DateOfOptInConfirmation=NOW(), IPOnSubscription='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."'";
        }

      $_IOJoI = $_POST["fields"];
      reset($_IOJoI);
      $_Jj8lt = "";
      $_jtiJJ = array();
      foreach($_IOJoI as $_IOLil => $_Iiloo) {
        if($_Iiloo < 0) {continue;}
        if(is_array($_Iiloo) ){
          continue;
         }
        if($_Iiloo < count($_I0QjQ))
           $_IOCjL = $_I0QjQ[$_Iiloo];
           else
           $_IOCjL = "";
        if(isset($_POST["RemoveQuotes"]) && $_POST["RemoveQuotes"] != "") {
          $_IOCjL = str_replace('"', '', $_IOCjL);
          $_IOCjL = str_replace("\'", '', $_IOCjL);
          $_IOCjL = str_replace("`", '', $_IOCjL);
          $_IOCjL = str_replace("´", '', $_IOCjL);
        }
        if(isset($_POST["RemoveSpaces"]) && $_POST["RemoveSpaces"] != "") {
          $_IOCjL = trim($_IOCjL);
        }
        if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
          if($_IOLil == "u_EMailFormat") {
            $_IOCjL = trim($_IOCjL);
            if($_IOCjL != 'HTML' && $_IOCjL != 'PlainText' && $_IOCjL != 'Text')
              $_IOCjL = 'HTML';
              else
              if($_IOCjL == 'PlainText' || $_IOCjL == 'Text')
                $_IOCjL = 'PlainText';
          } // if($_IOLil == "u_EMailFormat")
          if($_IOLil == "u_Gender") {
            $_IOCjL = strtolower($_IOCjL);
            $_IOCjL = trim($_IOCjL);
            if($_IOCjL != "m" && $_IOCjL != "w") {
              $_IOCjL = substr($_IOCjL, 0, 1);
              if($_IOCjL == "f") // female
                $_IOCjL = "w";
              if($_IOCjL != "m" && $_IOCjL != "w") {
                if($_IOCjL == "0")
                   $_IOCjL = "m";
                   else
                   if($_IOCjL == "1")
                      $_IOCjL = "w";
                      else
                       $_IOCjL = 'undefined';
              }
            }
          } // if($_IOLil == "u_Gender")
          if($_IOLil == "u_Birthday") {
            $_IOCjL = trim($_IOCjL);
            if($_POST["BirthdayDateFormat"] == "dd.mm.yyyy") {
              $_jJQOo = explode(".", $_IOCjL);
              while(count($_jJQOo) < 3)
                 $_jJQOo[] = "f";
              $_jjOlo = $_jJQOo[0];
              $_jJIft = $_jJQOo[1];
              $_jJjQi = $_jJQOo[2];
            } else
              if($_POST["BirthdayDateFormat"] == "yyyy-mm-dd") {
               $_jJQOo = explode("-", $_IOCjL);
               while(count($_jJQOo) < 3)
                  $_jJQOo[] = "f";
               $_jjOlo = $_jJQOo[2];
               $_jJIft = $_jJQOo[1];
               $_jJjQi = $_jJQOo[0];
              } else
                if($_POST["BirthdayDateFormat"] == "mm-dd-yyyy") {
                  $_jJQOo = explode("-", $_IOCjL);
                  while(count($_jJQOo) < 3)
                     $_jJQOo[] = "f";
                  $_jjOlo = $_jJQOo[1];
                  $_jJIft = $_jJQOo[0];
                  $_jJjQi = $_jJQOo[2];
                }

            if(strlen($_jJjQi) == 2)
              $_jJjQi = "19".$_jJjQi;
            if( ! (
                (intval($_jjOlo) > 0 && intval($_jjOlo) < 32) &&
                (intval($_jJIft) > 0 && intval($_jJIft) < 13)
                  )
              ) // error in date
              $_IOCjL = "0000-00-00";
              else
              $_IOCjL = "$_jJjQi-$_jJIft-$_jjOlo";

          } // if($_IOLil == "u_Birthday")

          // integer
          if(
             $_IOLil == "u_UserFieldInt1" ||
             $_IOLil == "u_UserFieldInt2" ||
             $_IOLil == "u_UserFieldInt3"
            )
              $_IOCjL = intval($_IOCjL);

          // boolean
          if(
             $_IOLil == "u_UserFieldBool1" ||
             $_IOLil == "u_UserFieldBool2" ||
             $_IOLil == "u_UserFieldBool3" ||
             $_IOLil == "u_PersonalizedTracking"
            ) {
              $_IOCjL = strtolower($_IOCjL);
              if($_IOCjL == "true")
                $_IOCjL = 1;
                if($_IOCjL == "false")
                   $_IOCjL = 0;
              $_IOCjL = intval($_IOCjL);
              if($_IOCjL < 0) $_IOCjL = 0;
              if($_IOCjL > 0) $_IOCjL = 1;
            }

          # no empty email addresses
          if($_IOLil == "u_EMail" && (trim($_IOCjL) == "" || !_L8JEL($_IOCjL)) ) {
            $_QLfol = "";
            _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressIncorrect"], $_IOCjL) );
            break;
          } else {
            if( $_IOLil == "u_EMail" ){
              $_IOCjL = _L86JE($_IOCjL);
              $_Jj8lt = $_IOCjL;
            }  
          }

          if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){
            $_jtiJJ[] = " `$_IOLil`="._LRAFO(htmlspecialchars($_IOCjL, ENT_COMPAT, $_QLo06, false));
          } else {
            $_jtiJJ[] = " `$_IOLil`="._LRAFO($_IOCjL);
          }
        }
      } // foreach($_IOJoI as $_IOLil => $_IOCjL)

      if($_QLfol != "" && $_Jj8lt == "") {
         _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorNoEMailAddress"], $_Jj8lt) );
         $_QLfol = "";
      }

      if($_QLfol != "")
        $_QLfol .= ", ".join(", ", $_jtiJJ);

      $_JjtC8 = false;
      if($_QLfol != "") {

        $_JjOjI = substr($_Jj8lt, strpos($_Jj8lt, '@') + 1);

        if(_J18DA($_Jj8lt)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalBlockList"], $_Jj8lt) );
        }

        if(!$_JjtC8 && _J18FQ($_Jj8lt, $_POST["OneMailingListId"], $_jjj8f)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalBlockList"], $_Jj8lt) );
        }

        if(!$_JjtC8 && _J1PQO($_JjOjI)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalDomainBlockList"], $_Jj8lt) );
        }

        if(!$_JjtC8 && _J1P6D($_JjOjI, $_POST["OneMailingListId"], $_Jj6f0)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalDomainBlockList"], $_Jj8lt) );
        }

        if(!$_JjtC8 && $_Jj68f ) {
          $_I016j = _L6AJP($_Jj8lt);
          $_JjtC8 = (is_bool($_I016j) && $_I016j);
          if($_JjtC8)
            _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInECGList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

      }

      if($_JjtC8 && empty($_POST["ImportBlockedRecipients"]))
        $_QLfol = "";

      if($_QLfol != "") {
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        if(!empty($_POST["CreateErrorLog"]) && $_JjJJl && mysql_affected_rows($_QLttI) == 0){
           _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorDuplicateEntry"], $_Jj8lt) );
        }
      }

      if($_QLfol != "" && mysql_affected_rows($_QLttI) > 0 ) {
        $_Iii6I++;

        // statistics
        $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
        $_QLO0f=mysql_fetch_array($_QL8i1);
        $_IfLJj = $_QLO0f[0];
        mysql_free_result($_QL8i1);

        $_QLfol = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action=$_Jj8iQ, Member_id=$_IfLJj";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);

        // **** apply groups_id to member_id START
        if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 3 && isset($_POST["groups_id"]) && intval($_POST["groups_id"]) > 0 ) {
          $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=".intval($_POST["groups_id"]).", Member_id=$_IfLJj";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
        }
        // **** apply groups_id to member_id END

        // **** import groups names and create it if not exists START
        if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && isset($_Jj6L1) && count($_Jj6L1) > 0 ) {
          for($_JjOfi = 0; $_JjOfi<count($_Jj6L1); $_JjOfi++) {
            if(trim($_Jj6L1[$_JjOfi]) == "") continue;
            $_QLfol = "INSERT IGNORE INTO $_QljJi SET CreateDate=NOW(), Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            if(mysql_affected_rows($_QLttI) > 0 ) {
              $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
              $_QLO0f=mysql_fetch_array($_QL8i1);
              $_IOjji = $_QLO0f[0];
              mysql_free_result($_QL8i1);
            } else {
              $_QLfol = "SELECT id FROM $_QljJi WHERE Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              _L8D88($_QLfol);
              $_IOjji = 0;
              if($_QL8i1) {
                 $_QLO0f=mysql_fetch_array($_QL8i1);
                 $_IOjji = $_QLO0f[0];
                 mysql_free_result($_QL8i1);
              }
            }

            if($_IOjji != 0) {
              $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=$_IOjji, Member_id=$_IfLJj";
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              _L8D88($_QLfol);
            }
          }
          $_Jj6L1 = array();
        }
        // **** import groups names and create it if not exists END

        if($_JjfiL && $_Jj8lt != "" && !$_JjtC8) {
          _LRCOC();
          _J0LAA("subscribeconfirm", $_IfLJj, $_I80Jo, $_Jj08l, $errors, $_I816i);
          _LRCOC();
        }

      } // if(mysql_affected_rows($_QLttI) > 0 )
        else
        if($_QLfol != "" && isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] > 0 && isset($_POST["ExImportOption"]) && $_POST["ExImportOption"] != 2 ) { // member exists, add to groups or update

          $_QLfol = "SELECT id FROM $_Jj6jC WHERE u_EMail="._LRAFO($_Jj8lt);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
          if(mysql_num_rows($_QL8i1) > 0) {
            $_QLO0f=mysql_fetch_array($_QL8i1);
            mysql_free_result($_QL8i1);
            $_IfLJj = $_QLO0f["id"];

            # Remove from all groups
            if(!empty($_POST["RemoveRecipientFromGroups"]))
              _J1JFE(array($_IfLJj), $_POST["OneMailingListId"], $_IfJ66);


            if($_POST["ExImportOption"] == 3){
              // Update recipient
              // no empty values
              for($_Qli6J=0; $_Qli6J<count($_jtiJJ); $_Qli6J++){
                if(strpos($_jtiJJ[$_Qli6J], '""') !== false || strpos($_jtiJJ[$_Qli6J], '"0000-00-00"') !== false) {
                  unset($_jtiJJ[$_Qli6J]);
                }
              }

              $_QLfol = "UPDATE $_Jj6jC SET ".join(", ", $_jtiJJ)." WHERE id=$_IfLJj";
              mysql_query($_QLfol, $_QLttI);
              _L8D88($_QLfol);
            }

            // **** apply groups_id to member_id START
            if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 3 && isset($_POST["groups_id"]) && intval($_POST["groups_id"]) > 0 ) {
              $_QLfol = "SELECT * FROM $_IfJ66 WHERE groups_id=".intval($_POST["groups_id"])." AND Member_id=$_IfLJj";
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              if(mysql_num_rows($_QL8i1) == 0) {
                if($_QL8i1)
                  mysql_free_result($_QL8i1);
                $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=".intval($_POST["groups_id"]).", Member_id=$_IfLJj";
                $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                _L8D88($_QLfol);
                $_Iii6I++;
              } else
                if($_QL8i1)
                  mysql_free_result($_QL8i1);
            }
           // **** apply groups_id to member_id END

           // **** import groups names and create it if not exists START
           if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && isset($_Jj6L1) && count($_Jj6L1) > 0 ) {
             for($_JjOfi=0; $_JjOfi<count($_Jj6L1); $_JjOfi++) {
               if(trim($_Jj6L1[$_JjOfi]) == "") continue;
               $_QLfol = "INSERT IGNORE INTO $_QljJi SET CreateDate=NOW(), Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
               $_QL8i1 = mysql_query($_QLfol, $_QLttI);
               _L8D88($_QLfol);
               if(mysql_affected_rows($_QLttI) > 0 ) {
                 $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
                 $_QLO0f=mysql_fetch_array($_QL8i1);
                 $_IOjji = $_QLO0f[0];
                 mysql_free_result($_QL8i1);
               } else {
                 $_QLfol = "SELECT id FROM $_QljJi WHERE Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
                 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                 _L8D88($_QLfol);
                 $_IOjji = 0;
                 if($_QL8i1) {
                    $_QLO0f=mysql_fetch_array($_QL8i1);
                    $_IOjji = $_QLO0f[0];
                    mysql_free_result($_QL8i1);
                 }
               }

               if($_IOjji != 0) {
                 $_QLfol = "SELECT * FROM $_IfJ66 WHERE groups_id=$_IOjji AND Member_id=$_IfLJj";
                 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                 if(mysql_num_rows($_QL8i1) == 0) {
                   if($_QL8i1)
                     mysql_free_result($_QL8i1);
                   $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=$_IOjji, Member_id=$_IfLJj";
                   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                   _L8D88($_QLfol);
                   $_Iii6I++;
                 } else
                   if($_QL8i1)
                     mysql_free_result($_QL8i1);
               }
             }
             $_Jj6L1 = array();
           }
           // **** import groups names and create it if not exists END

          } # if(mysql_num_rows($_QL8i1) > 0)

        } // if($_QLfol != "" && isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] > 0 && isset($_POST["ExImportOption"]) && $_POST["ExImportOption"] != 2 )
    }

    $_Iil6i += $_POST["ImportLines"];
    $_Iiioi = ftell($_IJljf);
    if(feof($_IJljf))
      $_Iiioi = -1;

    print '<input type="hidden" name="start" value="'.$_Iil6i.'" />';
    print '<input type="hidden" name="fileposition" value="'.$_Iiioi.'" />';
    print '<input type="hidden" name="RowCount" value="'.$_IiiJf.'" />';
    print '<input type="hidden" name="ImportRowCount" value="'.$_Iii6I.'" />';

    fclose($_IJljf);

    if(!empty($_POST["CreateErrorLog"]) && $_JjJJl){
      fclose($_JjJJl);
    }

    print $_QLJfI;
    flush();
  }


  function _L0FRP($errors, $_Io0OJ, $_Itfj8 = "") {
    global $_ItL8f, $resourcestrings, $INTERFACE_LANGUAGE, $OneMailingListId;
    global $UserType, $Username, $_QLl1Q;

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_Itfj8, 'importrecipients_csv', 'import2_snipped.htm');

    if(isset($_Io0OJ["step"]))
      unset($_Io0OJ["step"]);

    $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
    $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);

    if(isset($_Io0OJ["MailingListName"]))
      unset($_Io0OJ["MailingListName"]);

    if (!isset($_Io0OJ["Separator"]) )
      $_QLJfI = str_replace('name="Separator"', 'name="Separator" value=","', $_QLJfI);
    if (!isset($_Io0OJ["ImportLines"]) )
       $_QLJfI = str_replace('name="ImportLines"', 'name="ImportLines" value="200"', $_QLJfI);
    if (!isset($_Io0OJ["Header1Line"]) )
       $_QLJfI = str_replace('name="Header1Line"', 'name="Header1Line" checked="checked"', $_QLJfI);
    if (!isset($_Io0OJ["RemoveQuotes"]) )
       $_QLJfI = str_replace('name="RemoveQuotes"', 'name="RemoveQuotes" checked="checked"', $_QLJfI);
    if (!isset($_Io0OJ["RemoveSpaces"]) )
       $_QLJfI = str_replace('name="RemoveSpaces"', 'name="RemoveSpaces" checked="checked"', $_QLJfI);

    $_IO6iJ = "";
    $_IJljf = opendir ( substr($_ItL8f, 0, strlen($_ItL8f) - 1) );
    while (false !== ($_QlCtl = readdir($_IJljf))) {
      if (!is_dir($_ItL8f.$_QlCtl) && $_QlCtl != "." && $_QlCtl != ".." && $_QlCtl != "index.php") {

        if( isset($_POST["ImportFilename"]) && ($_POST["ImportFilename"] == $_QlCtl || $_POST["ImportFilename"] == @utf8_decode($_QlCtl) ) )
          $_IOfJi = ' selected="selected"';
          else
          $_IOfJi = '';
        $_IO6iJ .= '<option value="'.$_QlCtl.'"' . $_IOfJi . '>'.$_QlCtl.'</option>'.$_QLl1Q;
      }
    }
    closedir($_IJljf);
    $_QLJfI = _L81BJ($_QLJfI, "<OPTION:FILENAME>", "</OPTION:FILENAME>", $_IO6iJ);

    $_QLJfI = _L8AOB($errors, $_Io0OJ, $_QLJfI);

    print $_QLJfI;

  }

  function _LLQRO($_IO08l) {
   global $_JjJJl, $_QLl1Q;
   if(!empty($_POST["CreateErrorLog"]) && $_JjJJl){
     fwrite($_JjJJl, $_IO08l.$_QLl1Q);
   }
  }

?>

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
  include_once("dbimporthelper.inc.php");

  // Boolean fields of form
  $_ItI0o = Array ();

  $_ItIti = Array ();

  $errors = array();
  $_Itjtj = 0;

  if(isset($_POST["DoneBtn"])) { # restart new
    $_POST = array();
  }

  if(isset($_POST['AutoImportId'])) // Formular speichern?
    $_Itjtj = intval($_POST['AutoImportId']);
  else
    if ( isset($_POST['OneAutoImportListId']) )
       $_Itjtj = intval($_POST['OneAutoImportListId']);


  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_Itjtj && !$_QLJJ6["PrivilegeAutoImportEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if(!$_Itjtj && !$_QLJJ6["PrivilegeAutoImportCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // go prev page
  if(isset($_POST["step"]) && $_POST["step"] == 1 && isset($_POST["PrevBtn"]) )
     unset($_POST["step"]);
  if(isset($_POST["step"]) && $_POST["step"] == 2 && isset($_POST["PrevBtn"]) )
     $_POST["step"] = "1";
  if(isset($_POST["step"]) && $_POST["step"] == 3 && isset($_POST["PrevBtn"]) )
     $_POST["step"] = "2";
  //

  $_Itfj8 = "";

  if(!isset($_POST["step"]) || $_POST["step"] == 0 ) {

    if(isset($_POST["step"]) && $_POST["step"] == 0){
       // check for empty fields
       if(empty($_POST["Name"]))
         $errors[] = "Name";
         elseif($_Itjtj == 0){
           $_QLfol = "SELECT COUNT(*) FROM $_ItfiJ WHERE `Name`="._LRAFO(trim($_POST['Name']));
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if($_QL8i1 && ($_QLO0f = mysql_fetch_array($_QL8i1)) && ($_QLO0f[0] > 0) ) {
            mysql_free_result($_QL8i1);
            $errors[] = 'Name';
            $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001204"], trim($_POST['Name']));
           }
            else
              if($_QL8i1)
                mysql_free_result($_QL8i1);
         }
       if(empty($_POST["ImportScheduler"]))
         $errors[] = "ImportScheduler";
       if(!isset($_POST["ImportHour"]))
         $errors[] = "ImportHour";
       if(!isset($_POST["ImportMinute"]))
         $errors[] = "ImportMinute";
       if(isset($_POST["ImportHour"]) && isset($_POST["ImportMinute"]))
         $_POST['ImportTime'] = "$_POST[ImportHour]:$_POST[ImportMinute]:00";
         else
         $_POST['ImportTime'] = "00:00:00";

       if(!empty($_POST["ImportScheduler"])){
          switch($_POST["ImportScheduler"]){
            case "ImportAtDay":
                               if(empty($_POST["ImportDayNames"]))
                                  $errors[] = "ImportDayNames";
                               break;
            case "ImportOnceAMonth":
                               if(empty($_POST["ImportMonthDay"]))
                                  $errors[] = "ImportMonthDay";
                               break;
            default:
              $errors[] = "ImportScheduler";
          }
       }

       if(empty($_POST["maillists_id"]))
         $errors[] = "maillists_id";
         else{
          $_POST["maillists_id"] = intval($_POST["maillists_id"]);
          if(!_LAEJL($_POST["maillists_id"])){
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
         }
       if(empty($_POST["ImportOption"]))
         $errors[] = "ImportOption";

       if(!empty($_POST["ImportOption"]) && $_POST["ImportOption"] == "ImportDB" ){
         if(empty($_POST["DBType"]))
           $errors[] = "DBType";
         if(empty($_POST["SQLServerName"]))
           $errors[] = "SQLServerName";
         if(empty($_POST["SQLDatabase"]))
           $errors[] = "SQLDatabase";
         if(empty($_POST["SQLUsername"]))
           $errors[] = "SQLUsername";
         if(empty($_POST["SQLPassword"]))
           $errors[] = "SQLPassword";
         if(!empty($_POST["DBType"]) && !empty($_POST["SQLServerName"]) && !empty($_POST["SQLDatabase"]) && !empty($_POST["SQLUsername"]) && !empty($_POST["SQLPassword"])) {

           $_ItCCj = 0;

           if($_POST["DBType"] == "MSSQL" && !function_exists("mssql_connect")) {
             $errors[] = "DBType";
             $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["NO_MSSQL_SUPPORT"];
           } else {
             $_ItCCj = _L0COO($_POST, $errors, $_Itfj8, $_POST["DBType"], !empty($_POST["IsUTF8"]));
           }
           if($_ItCCj != 0 && $_ItCCj != $_QLttI)
              db_close ($_ItCCj, $_POST["DBType"]);

         }
       }

       if(!empty($_POST["ImportOption"]) && $_POST["ImportOption"] == "ImportCSV" ){
         $_ItiQj = true;
         if( isset( $_FILES['file1'] ) && $_FILES['file1']['tmp_name'] != "" && $_FILES['file1']['name'] != ""  && ( $_ItiQj = in_array( strtolower(ExtractFileExt($_FILES['file1']['name'])), $_ItLJj) ) ) {
            // upload akzeptieren
            if (move_uploaded_file($_FILES['file1']['tmp_name'], $_ItL8f.$_FILES['file1']['name'])){
               $_POST["ImportFilename"] = $_FILES['file1']['name'];
               @chmod($_ItL8f.$_FILES['file1']['name'], 0777);
            } else {
              $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000055"], $_FILES['file1']['tmp_name'], $_ItL8f.$_FILES['file1']['name'] );
              $errors[] = "file1";
            }
          } else {
              if(!$_ItiQj) {
                $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorFileTypeNotAllowed"];
                $errors[] = "file1";
              }
          }
       }

       if(count($errors) == 0){
         $_ItI0o = array("IsActive", "RemoveAllRecipients");
         if ( defined("DEMO") ) {
           if(isset($_POST["IsActive"]))
             unset($_POST["IsActive"]);
         }
         $_I6tLJ = $_POST;
         unset($_I6tLJ["step"]);
         if(isset($_I6tLJ["file1"]))
            unset($_I6tLJ["file1"]);
         _L0BBC($_I6tLJ, $_Itjtj);
         if($_Itjtj != 0)
            $_POST["step"]++;
       }

    } // if(isset($_POST["step"]) && $_POST["step"] == 0)

    if(!isset($_POST["step"]) || (isset($_POST["step"]) && $_POST["step"] == 0) ){

      if(count($errors) > 0 && $_Itfj8 == "")
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];

      if($_Itjtj == 0)
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001200"], $_Itfj8, 'importrecipients', 'autoimportedit0_snipped.htm');
        else {
          if(!isset($_POST["Name"])) {
            $_QLfol = "SELECT Name FROM $_ItfiJ WHERE id=$_Itjtj";
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            $_I6tLJ = mysql_fetch_assoc($_QL8i1);
            mysql_free_result($_QL8i1);
            $_POST["Name"] = $_I6tLJ["Name"];
          }
          $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_POST["Name"]), $_Itfj8, 'importrecipients', 'autoimportedit0_snipped.htm');
        }

      $_QLJfI = str_replace('name="AutoImportId"', 'name="AutoImportId" value="'.$_Itjtj.'"', $_QLJfI);
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

      // ********* List of Mailinglists
      $_QlI6f = "SELECT DISTINCT id, Name FROM $_QL88I";
      if($OwnerUserId == 0) // ist es ein Admin?
         $_QlI6f .= " WHERE (users_id=$UserId)";
         else {
          $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
         }
      $_QlI6f .= " ORDER BY Name ASC";
      $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
      _L8D88($_QlI6f);
      $_ItlLC = "";
      while($_QLO0f=mysql_fetch_array($_QL8i1)) {
        $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
      }
      mysql_free_result($_QL8i1);
      $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MAILINGLISTS>", "</SHOW:MAILINGLISTS>", $_ItlLC);
      // ********* List of Groups query END

      // HOURS
      $_Ql0fO = "";
      for($_Qli6J=0; $_Qli6J<24; $_Qli6J++){
        $_I016j = $_Qli6J;
        if($_I016j < 10)
          $_I016j = "0".$_I016j;
        $_Ql0fO .= '<option value="'.$_Qli6J.'">'.$_I016j.'</option>';
      }
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:HOUR>", "</LIST:HOUR>", $_Ql0fO);

      // MINUTES
      $_Ql0fO = "";
      for($_Qli6J=0; $_Qli6J<60; $_Qli6J++) {
        $_I016j = $_Qli6J;
        if($_I016j < 10)
          $_I016j = "0".$_I016j;
        $_Ql0fO .= '<option value="'.$_Qli6J.'">'.$_I016j.'</option>';
      }
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:MINUTE>", "</LIST:MINUTE>", $_Ql0fO);


      // Day Of month
      $_IO08Q = _LP006($_ItfiJ, "ImportMonthDay");
      $_IO08Q = substr($_IO08Q, 5);
      $_IO08Q = substr($_IO08Q, 0, strlen($_IO08Q) - 1);
      $_IO08Q = str_replace("'", "", $_IO08Q);
      $_I1OoI = explode(",", $_IO08Q);
      $_Ql0fO = "";
      for($_Qli6J=1; $_Qli6J<count($_I1OoI); $_Qli6J++) {
        if($_I1OoI[$_Qli6J] == "every day")
           $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE]["EveryDay"];
           else
           $_IO08l = $_I1OoI[$_Qli6J].".";
        $_Ql0fO .= sprintf('<option value="%s">%s</option>', $_I1OoI[$_Qli6J], $_IO08l);
      }
      $_QLJfI = _L81BJ($_QLJfI, "<OPTION:MONTHDAY>", "</OPTION:MONTHDAY>", $_Ql0fO);

      // Day names
      $_IO08Q = _LP006($_ItfiJ, "ImportDayNames");
      $_IO08Q = substr($_IO08Q, 5);
      $_IO08Q = substr($_IO08Q, 0, strlen($_IO08Q) - 1);
      $_IO08Q = str_replace("'", "", $_IO08Q);
      $_I1OoI = explode(",", $_IO08Q);
      $_Ql0fO = "";
      for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++) {
        if($_I1OoI[$_Qli6J] == "every day")
           $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE]["Day"];
           else
           $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE][$DayNumToDayName[$_I1OoI[$_Qli6J]]];
        $_Ql0fO .= sprintf('<option value="%s">%s</option>', $_I1OoI[$_Qli6J], $_IO08l);
      }
      $_QLJfI = _L81BJ($_QLJfI, "<OPTION:DAYNAME>", "</OPTION:DAYNAME>", $_Ql0fO);

      if(!isset($_POST["step"])) {
        if($_Itjtj == 0){
          $_I6tLJ['IsActive'] = 1;
          $_I6tLJ['ImportScheduler'] = 'ImportAtDay';
          $_I6tLJ['ImportDayNames'] = 'every day';
          $_I6tLJ['ImportTime'] = "00:00:00";
          $_I6tLJ['ImportOption'] = "ImportCSV";
        } else {
          $_QLfol = "SELECT * FROM $_ItfiJ WHERE id=$_Itjtj";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
          $_I6tLJ = mysql_fetch_assoc($_QL8i1);

          if(!empty($_I6tLJ["SQLServerName"]) && $_I6tLJ["DBType"] == "MSSQL")
            $_I6tLJ["SQLServerName"] = str_replace("/", "\\", $_I6tLJ["SQLServerName"]);

          if(isset($_I6tLJ['ImportTime'])) {
            $_I6tLJ["ImportHour"] = intval(substr($_I6tLJ['ImportTime'], 0, strpos($_I6tLJ['ImportTime'], ':')));
            $_I6tLJ["ImportMinute"] = substr($_I6tLJ['ImportTime'], strpos($_I6tLJ['ImportTime'], ':') + 1);
            $_I6tLJ["ImportMinute"] = intval(substr($_I6tLJ['ImportMinute'], 0, strpos($_I6tLJ['ImportMinute'], ':')));
          }
          mysql_free_result($_QL8i1);

          _L8FCO($_ItfiJ, $_ItI0o, array("LastImportDone", "IsMacFile", "RemoveRecipientFromGroups"));
          reset($_I6tLJ);
          foreach($_I6tLJ as $key => $_QltJO){
            if(in_array($key, $_ItI0o) && $_QltJO == 0)
              unset($_I6tLJ[$key]);
          }

        }
      } else{
        $_I6tLJ = $_POST;
        unset($_I6tLJ["step"]);
      }

      $_QLJfI = _L8AOB($errors, $_I6tLJ, $_QLJfI);

      print $_QLJfI;
      exit;
    } # if(!isset($_POST["step"]) || (isset($_POST["step"]) && $_POST["step"] == 0) )
  } else
  if($_POST["step"] == 1 && !isset($_POST["PrevBtn"])) {
    if($_POST["ImportOption"] == "ImportCSV"){
      if(!isset($_POST["ImportFilename"]))
        $errors[] = 'ImportFilename';
      if(!isset($_POST["Separator"]))
        $errors[] = 'Separator';
      if(!isset($_POST["ImportLines"]))
        $errors[] = 'ImportLines';
        else{
         $_POST["ImportLines"] = intval($_POST["ImportLines"]);
         if($_POST["ImportLines"] < 0)
           $_POST["ImportLines"] = 100;
        }
      if( isset($_POST["ImportFilename"]) ) {
        $_QlCtl = fopen($_ItL8f.$_POST["ImportFilename"], "r");
        if(!$_QlCtl) {
          $errors[] = 'ImportFilename';
          $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000056"], $_ItL8f.$_POST["ImportFilename"]);
        } else {
          fclose($_QlCtl);
        }
      }
    }

    if($_POST["ImportOption"] == "ImportDB"){
      if(!isset($_POST["SQLTableName"]) || trim($_POST["SQLTableName"]) == "") {
       $errors[] = "SQLTableName";
      }
      if(!isset($_POST["SQLExtImport"]) || trim($_POST["SQLExtImport"]) == "") {
       $errors[] = "SQLExtImport";
      }
      if(isset($_POST["SQLExtImport"]) && $_POST["SQLExtImport"] == 2 && trim($_POST["SQLImportSQLQuery"]) == "") {
       $errors[] = "SQLImportSQLQuery";
      }
      if(!isset($_POST["ImportLines"]))
        $errors[] = 'ImportLines';
        else{
         $_POST["ImportLines"] = intval($_POST["ImportLines"]);
         if($_POST["ImportLines"] < 0)
           $_POST["ImportLines"] = 100;
        }
    }

    if($_POST["ImportOption"] == "ImportDB" && count($errors) == 0) {

       $_QLfol = "SELECT * FROM $_ItfiJ WHERE id=$_Itjtj";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $_I6tLJ = mysql_fetch_assoc($_QL8i1);

       if(!empty($_I6tLJ["SQLServerName"]) && $_I6tLJ["DBType"] == "MSSQL")
          $_I6tLJ["SQLServerName"] = str_replace("/", "\\", $_I6tLJ["SQLServerName"]);

       if(isset($_I6tLJ['ImportTime'])) {
         $_I6tLJ["ImportHour"] = substr($_I6tLJ['ImportTime'], 0, strpos($_I6tLJ['ImportTime'], ':'));
         $_I6tLJ["ImportMinute"] = substr($_I6tLJ['ImportTime'], strpos($_I6tLJ['ImportTime'], ':') + 1);
         $_I6tLJ["ImportMinute"] = substr($_I6tLJ['ImportMinute'], 0, strpos($_I6tLJ['ImportMinute'], ':'));
       }
       mysql_free_result($_QL8i1);
       if($_I6tLJ["Separator"] == "")
         $_I6tLJ["Separator"] = ",";

       _L8FCO($_ItfiJ, $_ItI0o, array("IsActive", "LastImportDone", "IsMacFile", "RemoveRecipientFromGroups"));
       if($_I6tLJ["ImportOption"] == "ImportCSV")
         $_Ql0fO = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_csv', 'autoimporteditcsv1_snipped.htm');
         else
         $_Ql0fO = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_mysql', 'autoimporteditdb1_snipped.htm');
       reset($_ItI0o);
       foreach($_ItI0o as $key => $_QltJO) {
         if(strpos($_Ql0fO, sprintf('name="%s"', $_QltJO)) === false){
           unset($_ItI0o[$key]);
         }
       }

       reset($_ItI0o);
       foreach($_ItI0o as $key => $_QltJO) {
         if(!isset($_POST[$_QltJO]))
            unset($_I6tLJ[$_QltJO]);
       }

       $_POST = array_merge($_I6tLJ, $_POST);

       $_ItCCj = _L0COO($_POST, $errors, $_Itfj8, $_POST["DBType"], !empty($_POST["IsUTF8"]));
       $errors = array();
       if ($_ItCCj == 0) {
          $_Itfj8 = ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br />".db_error($_ItCCj, $_POST["DBType"]));
          $errors[] = "SQLServerName";
          $errors[] = "SQLUsername";
          $errors[] = "SQLPassword";
       }

       if($_ItCCj != 0) {
         if (!db_select_db ($_POST['SQLDatabase'], $_ItCCj, $_POST["DBType"])) {
           $_Itfj8 = ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".$_POST['SQLDatabase']."<br />".db_error($_ItCCj, $_POST["DBType"]));
           $errors[] = "SQLDatabase";
         } else{
           if($_POST["SQLExtImport"] == 2){
             $_QLfol = str_replace('"', "'", $_POST["SQLImportSQLQuery"]);
             $_QL8i1 = db_query($_QLfol, $_ItCCj, $_POST["DBType"]);
             if(db_error($_ItCCj, $_POST["DBType"]) != "") {
               $_Itfj8 = db_error($_ItCCj, $_POST["DBType"]);
               $errors[] = "SQLImportSQLQuery";
             }
           } else{
             if($_POST["DBType"] == "MYSQL")
               $_QLfol = "SELECT * FROM `$_POST[SQLTableName]` LIMIT 0, 1";
               else
               $_QLfol = "SELECT TOP 1 * FROM $_POST[SQLTableName]";
             $_QL8i1 = db_query($_QLfol, $_ItCCj, $_POST["DBType"]);
             if(db_error($_ItCCj, $_POST["DBType"]) != "") {
               $_Itfj8 = db_error($_ItCCj, $_POST["DBType"]);
               $errors[] = "SQLTableName";
             }
           }
         }
       if($_ItCCj != $_QLttI)
          db_close ($_ItCCj, $_POST["DBType"]);
       }
    }

    if(count($errors) == 0) {
      $_I6tLJ = $_POST;

      _L8FCO($_ItfiJ, $_ItI0o, array("IsActive", "LastImportDone", "IsMacFile", "RemoveRecipientFromGroups"));
      if($_I6tLJ["ImportOption"] == "ImportCSV")
        $_Ql0fO = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_csv', 'autoimporteditcsv1_snipped.htm');
        else
        $_Ql0fO = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_mysql', 'autoimporteditdb1_snipped.htm');
      reset($_ItI0o);
      foreach($_ItI0o as $key => $_QltJO) {
        if(strpos($_Ql0fO, sprintf('name="%s"', $_QltJO)) === false){
          unset($_ItI0o[$key]);
        }
      }

      _L0BBC($_I6tLJ, $_Itjtj);
      $_POST["step"]++;
    }
  } else
    if($_POST["step"] == 2 && !isset($_POST["PrevBtn"])) {

      if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 3 && !isset($_POST["groups_id"]) ) {
        $_POST["GroupsOption"] = 1;
      }

      if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && (!isset($_POST["ImportGroupField"]) || $_POST["ImportGroupField"] < 0) ) {
        $_POST["GroupsOption"] = 1;
      }

      if(!isset($_POST["fields"])) {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000058"];
        $errors[] = "fields[u_EMail]";
      } else {
        if( !isset($_POST["fields"]["u_EMail"]) || $_POST["fields"]["u_EMail"] == "-1" ) {
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000059"];
          $errors[] = "fields[u_EMail]";
        }
      }

      if(count($errors) == 0) {
        $_I6tLJ = $_POST;
        _L8FCO($_ItfiJ, $_ItI0o, array("IsActive", "LastImportDone", "IsMacFile"));
        $_Ql0fO = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_csv', 'autoimportedit2_snipped.htm');
        $_ItI0o[] = "RemoveRecipientFromGroups"; // bug in mysql definition int(1) -> tinyint, set it manually
        reset($_ItI0o);
        foreach($_ItI0o as $key => $_QltJO) {
          if(strpos($_Ql0fO, sprintf('name="%s"', $_QltJO)) === false){
            unset($_ItI0o[$key]);
          }
        }

        $_IO6fO = $_I6tLJ["fields"];
        foreach($_I6tLJ["fields"] as $key => $_QltJO)
           if($_QltJO == -1)
             unset($_I6tLJ["fields"][$key]);
        $_I6tLJ["fields"] = serialize($_I6tLJ["fields"]);
        _L0BBC($_I6tLJ, $_Itjtj);
        $_I6tLJ["fields"] = $_IO6fO;
        $_POST["step"]++;
      }

    }


  if(count($errors) > 0 && $_Itfj8 == "")
     $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];

  // **************************** fill forms

  if($_Itjtj > 0) {
    $_QLfol = "SELECT * FROM $_ItfiJ WHERE id=$_Itjtj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_I6tLJ = mysql_fetch_assoc($_QL8i1);
    if(!empty($_I6tLJ["SQLServerName"]) && $_I6tLJ["DBType"] == "MSSQL")
       $_I6tLJ["SQLServerName"] = str_replace("/", "\\", $_I6tLJ["SQLServerName"]);

    if(isset($_I6tLJ['ImportTime'])) {
      $_I6tLJ["ImportHour"] = substr($_I6tLJ['ImportTime'], 0, strpos($_I6tLJ['ImportTime'], ':'));
      $_I6tLJ["ImportMinute"] = substr($_I6tLJ['ImportTime'], strpos($_I6tLJ['ImportTime'], ':') + 1);
      $_I6tLJ["ImportMinute"] = substr($_I6tLJ['ImportMinute'], 0, strpos($_I6tLJ['ImportMinute'], ':'));
    }
    mysql_free_result($_QL8i1);
    if($_I6tLJ["Separator"] == "")
      $_I6tLJ["Separator"] = ",";
    $_I6tLJ = array_merge($_I6tLJ, $_POST);
  } else
    $_I6tLJ = $_POST;

  if($_POST["step"] == 1){
    if($_I6tLJ["ImportOption"] == "ImportCSV")
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_I6tLJ["Name"]), $_Itfj8, 'importrecipients_csv', 'autoimporteditcsv1_snipped.htm');
      else
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_I6tLJ["Name"]), $_Itfj8, 'importrecipients_mysql', 'autoimporteditdb1_snipped.htm');

    _L8FCO($_ItfiJ, $_ItI0o, array("IsActive", "LastImportDone", "IsMacFile", "RemoveRecipientFromGroups"));

    reset($_I6tLJ);
    foreach($_I6tLJ as $key => $_QltJO){
      if(in_array($key, $_ItI0o) && $_QltJO == 0)
        unset($_I6tLJ[$key]);
    }

    // file
    if($_I6tLJ["ImportOption"] == "ImportCSV"){
      $_IO6iJ = "";
      $_IJljf = opendir ( substr($_ItL8f, 0, strlen($_ItL8f) - 1) );
      while (false !== ($_QlCtl = readdir($_IJljf))) {
        if (!is_dir($_ItL8f.$_QlCtl) && $_QlCtl != "." && $_QlCtl != ".." && $_QlCtl != "index.php") {
          if( isset($_I6tLJ["ImportFilename"]) && $_I6tLJ["ImportFilename"] == $_QlCtl )
            $_IOfJi = ' selected="selected"';
            else
            $_IOfJi = '';
          $_IO6iJ .= '<option value="'.$_QlCtl.'"' . $_IOfJi . '>'.$_QlCtl.'</option>'.$_QLl1Q;
        }
      }
      closedir($_IJljf);
      $_QLJfI = _L81BJ($_QLJfI, "<OPTION:FILENAME>", "</OPTION:FILENAME>", $_IO6iJ);
    }

    // db
    if($_I6tLJ["ImportOption"] == "ImportDB"){
      $_IO6iJ = "";
      $_ItCCj = _L0COO($_I6tLJ, $errors, $_Itfj8, $_I6tLJ["DBType"], !empty($_I6tLJ["IsUTF8"]) && $_I6tLJ["IsUTF8"]);

      if( $_ItCCj != 0 ) {
        $_IOfi1 = db_get_tables($_I6tLJ['SQLDatabase'], $_ItCCj, $_I6tLJ["DBType"]);
        for ($_Qli6J = 0; $_Qli6J < count($_IOfi1); $_Qli6J++) {
           $_IO6iJ .= '<option value="'.$_IOfi1[$_Qli6J].'">'.$_IOfi1[$_Qli6J].'</option>'.$_QLl1Q;
        }
        if($_ItCCj != $_QLttI)
           db_close ($_ItCCj, $_I6tLJ["DBType"]);
      }
      $_QLJfI = _L81BJ($_QLJfI, "<OPTION:SQLTABLENAME>", "</OPTION:SQLTABLENAME>", $_IO6iJ);
    }

  }

  if($_POST["step"] == 2){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_I6tLJ["Name"]), $_Itfj8, 'importrecipients_csv', 'autoimportedit2_snipped.htm');

    _L8FCO($_ItfiJ, $_ItI0o, array("IsActive", "LastImportDone", "IsMacFile"));

    reset($_I6tLJ);
    foreach($_I6tLJ as $key => $_QltJO){
      if(in_array($key, $_ItI0o) && $_QltJO == 0)
        unset($_I6tLJ[$key]);
    }

    // **************************** file
    if($_I6tLJ["ImportOption"] == "ImportCSV"){
      $_QlCtl = fopen($_ItL8f.$_I6tLJ["ImportFilename"], "r");
      if(!$_QlCtl) {
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


      $_IO1C0 = trim($_I6tLJ["Separator"]);
      if($_IO1C0 == '\t')
         $_IO1C0 = "\t";
      $_I1OoI = explode($_IO1C0, $_IOoJ0);
      for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++){
        $_IOCjL = $_I1OoI[$_Qli6J];
        if(isset($_I6tLJ["RemoveQuotes"]) && $_I6tLJ["RemoveQuotes"] != "") {
          $_IOCjL = str_replace('"', '', $_IOCjL);
          $_IOCjL = str_replace("\'", '', $_IOCjL);
          $_IOCjL = str_replace("`", '', $_IOCjL);
          $_IOCjL = str_replace("´", '', $_IOCjL);
        }
        if(isset($_I6tLJ["RemoveSpaces"]) && $_I6tLJ["RemoveSpaces"] != "") {
          $_IOCjL = trim($_IOCjL);
        }
        $_I1OoI[$_Qli6J] = $_IOCjL;
      }
    }

    // **************************** db
    if($_I6tLJ["ImportOption"] == "ImportDB"){
      $_ItCCj = _L0COO($_I6tLJ, $errors, $_Itfj8, $_I6tLJ["DBType"], !empty($_I6tLJ["IsUTF8"]) && $_I6tLJ["IsUTF8"] );
      if(!$_ItCCj) {
        exit;
      }

      $_I1OoI = array();
      if($_I6tLJ["SQLExtImport"] == 2){
        $_I6tLJ["SQLImportSQLQuery"] = str_replace('"', "'", $_I6tLJ["SQLImportSQLQuery"]);
        $_QL8i1 = db_query($_I6tLJ["SQLImportSQLQuery"], $_ItCCj, $_I6tLJ["DBType"]);
        if(db_error($_ItCCj, $_I6tLJ["DBType"]) != "") {
          exit;
        }

        if(!$_QL8i1 || db_num_rows($_QL8i1, $_I6tLJ["DBType"]) == 0) {
          _L0CFO($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["MySQLQueryEmptyResult"]);
          exit;
        }

        $_Qli6J=0;
        while ($_Qli6J < db_num_fields($_QL8i1, $_I6tLJ["DBType"])) {
          $_IOC6l = db_fetch_field($_QL8i1, $_Qli6J, $_I6tLJ["DBType"]);
          if($_IOC6l) {
            $_I1OoI[]=$_IOC6l->name;
          }
          $_Qli6J++;
        }
        db_free_result($_QL8i1, $_I6tLJ["DBType"]);
      }

      if(count($_I1OoI) == 0 && $_I6tLJ["DBType"] == "MSSQL" && $_I6tLJ["SQLExtImport"] != 2) {

        $_QL8i1 = db_query("SELECT * FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = '$_I6tLJ[SQLTableName]'", $_ItCCj, $_I6tLJ["DBType"]);

        if(!$_QL8i1 || db_num_rows($_QL8i1, $_I6tLJ["DBType"]) == 0) {
          _L0CFO($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["MySQLQueryEmptyResult"]);
          exit;
        }

        while($_QLO0f = db_fetch_assoc($_QL8i1, $_I6tLJ["DBType"])){
          $_I1OoI[] = $_QLO0f["COLUMN_NAME"];
        }
        db_free_result($_QL8i1, $_I6tLJ["DBType"]);


      }

      if(count($_I1OoI) == 0 && $_I6tLJ["DBType"] == "MYSQL")
        _L8EC8($_ItCCj, $_I6tLJ["SQLTableName"], $_I1OoI);
      if($_ItCCj != $_QLttI)
         db_close($_ItCCj, $_I6tLJ["DBType"]);
    }

    // **************************** fields
    $_I0Clj = "";
    for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++)
      $_I0Clj .= sprintf('<option value="%s">%s</option>'.$_QLl1Q, $_Qli6J, htmlentities($_I1OoI[$_Qli6J], ENT_COMPAT, $_QLo06)  );
    $_I0Clj = $_QLl1Q.sprintf('<option value="%s">%s</option>'.$_QLl1Q, "-1", "--"  ).$_QLl1Q.$_I0Clj;

    $_IOiJ0 = _L81DB($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>");
    $_QLfol = "SELECT text, fieldname FROM `$_Ij8oL` WHERE language='$INTERFACE_LANGUAGE'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    $_QLoli = "";
    $_Qli6J=1;
    $_IOLjO = 0;
    $_Ift08=1;
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

    // ************** Groups START
    $_QLfol = "SELECT GroupsTableName FROM $_QL88I WHERE id=$_I6tLJ[maillists_id]";
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


    // umwandeln, damit er es wieder findet
    $_IOJoI = $_I6tLJ["fields"];
    if(!is_array($_IOJoI)) {
       $_IOJoI = unserialize($_IOJoI);
       if($_IOJoI === false)
         $_IOJoI = array();
    }
    unset($_I6tLJ["fields"]);
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_I6tLJ["fields[$_IOLil]"] = $_IOCjL;
      }
    }

    if(!isset($_I6tLJ["GroupsOption"]) || $_I6tLJ["GroupsOption"] <= 0)
       $_I6tLJ["GroupsOption"] = 1;
    if(!isset($_I6tLJ["ExImportOption"]) || $_I6tLJ["ExImportOption"] <= 0)
       $_I6tLJ["ExImportOption"] = 1;
    if(isset($_I6tLJ["RemoveRecipientFromGroups"]) && $_I6tLJ["RemoveRecipientFromGroups"] <= 0)
       unset($_I6tLJ["RemoveRecipientFromGroups"]);
  }

  if($_POST["step"] == 3){
    if ( defined("DEMO") ) {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001206"];
    }
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_I6tLJ["Name"]), $_Itfj8, 'importrecipients_csv', 'autoimportedit3_snipped.htm');

  }

  unset($_I6tLJ["step"]);
  unset($_I6tLJ["AutoImportId"]);

  $_QLJfI = str_replace('name="AutoImportId"', 'name="AutoImportId" value="'.$_Itjtj.'"', $_QLJfI);
  $_QLJfI = _L8AOB($errors, $_I6tLJ, $_QLJfI);
  print $_QLJfI;

  function _L0BBC($_I6tLJ, &$_Itjtj){
    global $_ItfiJ, $_ItI0o, $_ItIti, $_QLttI;

    if(!empty($_I6tLJ["SQLServerName"]))
       $_I6tLJ["SQLServerName"] = str_replace("\\", "/", $_I6tLJ["SQLServerName"]);

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM $_ItfiJ";
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

    // update entry? check mailinglist
    if($_Itjtj != 0) {
     $_QLfol = "SELECT * FROM $_ItfiJ WHERE id=$_Itjtj";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
     $_IOljI = false;
     if( isset($_I6tLJ["maillists_id"]) && $_QLO0f["maillists_id"] != $_I6tLJ["maillists_id"]  ) { // not the same?
       $_IOljI = true;
     } else
       if(isset($_I6tLJ["ImportOption"]) && $_QLO0f["ImportOption"] != $_I6tLJ["ImportOption"]){
          $_IOljI = true;
       } else
       if(isset($_I6tLJ["ImportOption"]) && $_I6tLJ["ImportOption"] == 'ImportCSV' && isset($_I6tLJ["ImportFilename"]) && $_I6tLJ["ImportFilename"] != $_QLO0f["ImportFilename"] ){
          $_IOljI = true;
       } else
       if(isset($_I6tLJ["ImportOption"]) && $_I6tLJ["ImportOption"] == 'ImportDB' ){
          if(
              (isset($_I6tLJ["DBType"]) && $_I6tLJ["DBType"]) != $_QLO0f["DBType"] ||
              (isset($_I6tLJ["SQLServerName"]) && $_I6tLJ["SQLServerName"] != $_QLO0f["SQLServerName"]) ||
              (isset($_I6tLJ["SQLDatabase"]) && $_I6tLJ["SQLDatabase"] != $_QLO0f["SQLDatabase"]) ||
              (isset($_I6tLJ["SQLUsername"]) && $_I6tLJ["SQLUsername"] != $_QLO0f["SQLUsername"]) ||
              (isset($_I6tLJ["SQLPassword"]) && $_I6tLJ["SQLPassword"] != $_QLO0f["SQLPassword"]) ||
              (isset($_I6tLJ["SQLTableName"]) && $_I6tLJ["SQLTableName"] != $_QLO0f["SQLTableName"]) ||
              (isset($_I6tLJ["SQLExtImport"]) && $_I6tLJ["SQLExtImport"] != $_QLO0f["SQLExtImport"]) ||
              (isset($_I6tLJ["SQLExtImport"]) && $_I6tLJ["SQLExtImport"] == 2 && $_I6tLJ["SQLImportSQLQuery"] != $_QLO0f["SQLImportSQLQuery"] )

            )
          $_IOljI = true;
       }
     if($_IOljI){
       $_QLfol = "UPDATE $_ItfiJ SET `LastImportDone`=1, `LastImportDate`='0000-00-00 00:00:00' WHERE id=$_Itjtj";
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
     }
    }

    // new entry?
    if($_Itjtj == 0) {
      $_QLfol = "INSERT INTO $_ItfiJ SET CreateDate=NOW(), `Name`="._LRAFO($_I6tLJ["Name"]).", `maillists_id`=$_I6tLJ[maillists_id]";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_Itjtj = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }

    $_QLfol = "UPDATE $_ItfiJ SET ";
    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 )
             $_Io01j[] = "`$key`=1";
             else
              ;
        else {
           $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]))."";
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
    $_QLfol .= " WHERE id=$_Itjtj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }
  }

  function _L0COO($_I6tLJ, &$errors, &$_Itfj8, $_ItOIt, $_ItCiQ) {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;
    $_ItCCj = db_connect ($_I6tLJ['SQLServerName'], $_I6tLJ['SQLUsername'], $_I6tLJ['SQLPassword'], $_ItOIt, $_I6tLJ['SQLDatabase'], $_ItCiQ);
    if ($_ItCCj == 0) {
       $_Itfj8 = ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br/>".db_error($_ItCCj, $_ItOIt));
       $errors[] = "SQLServerName";
       $errors[] = "SQLUsername";
       $errors[] = "SQLPassword";
    }

    if (!db_select_db ($_I6tLJ['SQLDatabase'], $_ItCCj, $_ItOIt)) {
      $_Itfj8 = ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".$_I6tLJ['SQLDatabase']."<br/>".db_error($_ItCCj, $_ItOIt));
      $errors[] = "SQLDatabase";
      if($_ItCCj != $_QLttI)
         db_close ($_ItCCj, $_ItOIt);
      $_ItCCj = 0;
    }
    return $_ItCCj;
  }

  function _L0CFO($errors, $_Io0OJ, $_Itfj8 = "") {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_Itjtj, $UserType, $Username, $_QLttI;
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_Io0OJ["Name"]), $_Itfj8, 'importrecipients_mysql', 'autoimportedit2_emptyresult_snipped.htm');

    if(isset($_Io0OJ["step"]))
      unset($_Io0OJ["step"]);

    unset($_Io0OJ["AutoImportId"]);

    $_QLJfI = str_replace('name="AutoImportId"', 'name="AutoImportId" value="'.$_Itjtj.'"', $_QLJfI);
    $_QLJfI = _L8AOB($errors, $_Io0OJ, $_QLJfI);
    print $_QLJfI;
  }
?>

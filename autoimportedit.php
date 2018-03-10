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
  include_once("dbimporthelper.inc.php");

  // Boolean fields of form
  $_I01C0 = Array ();

  $_I01lt = Array ();

  $errors = array();
  $_I0Qj1 = 0;

  if(isset($_POST["DoneBtn"])) { # restart new
    $_POST = array();
  }

  if(isset($_POST['AutoImportId'])) // Formular speichern?
    $_I0Qj1 = intval($_POST['AutoImportId']);
  else
    if ( isset($_POST['OneAutoImportListId']) )
       $_I0Qj1 = intval($_POST['OneAutoImportListId']);


  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_I0Qj1 && !$_QJojf["PrivilegeAutoImportEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if(!$_I0Qj1 && !$_QJojf["PrivilegeAutoImportCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
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

  $_I0600 = "";

  if(!isset($_POST["step"]) || $_POST["step"] == 0 ) {

    if(isset($_POST["step"]) && $_POST["step"] == 0){
       // check for empty fields
       if(empty($_POST["Name"]))
         $errors[] = "Name";
         elseif($_I0Qj1 == 0){
           $_QJlJ0 = "SELECT COUNT(*) FROM $_I0f8O WHERE `Name`="._OPQLR(trim($_POST['Name']));
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           if($_Q60l1 && ($_Q6Q1C = mysql_fetch_array($_Q60l1)) && ($_Q6Q1C[0] > 0) ) {
            mysql_free_result($_Q60l1);
            $errors[] = 'Name';
            $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001204"], trim($_POST['Name']));
           }
            else
              if($_Q60l1)
                mysql_free_result($_Q60l1);
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
          if(!_OCJCC($_POST["maillists_id"])){
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
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

           $_I0i1t = 0;

           if($_POST["DBType"] == "MSSQL" && !function_exists("mssql_connect")) {
             $errors[] = "DBType";
             $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["NO_MSSQL_SUPPORT"];
           } else {
             $_I0i1t = _OODBQ($_POST, $errors, $_I0600, $_POST["DBType"], !empty($_POST["IsUTF8"]));
           }
           if($_I0i1t != 0 && $_I0i1t != $_Q61I1)
              db_close ($_I0i1t, $_POST["DBType"]);

         }
       }

       if(!empty($_POST["ImportOption"]) && $_POST["ImportOption"] == "ImportCSV" ){
         $_I0L1J = true;
         if( isset( $_FILES['file1'] ) && $_FILES['file1']['tmp_name'] != "" && $_FILES['file1']['name'] != ""  && ( $_I0L1J = in_array( strtolower(_OBJAL($_FILES['file1']['name'])), $_I0l08) ) ) {
            // upload akzeptieren
            if (move_uploaded_file($_FILES['file1']['tmp_name'], $_I0lQJ.$_FILES['file1']['name'])){
               $_POST["ImportFilename"] = $_FILES['file1']['name'];
               @chmod($_I0lQJ.$_FILES['file1']['name'], 0777);
            } else {
              $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000055"], $_FILES['file1']['tmp_name'], $_I0lQJ.$_FILES['file1']['name'] );
              $errors[] = "file1";
            }
          } else {
              if(!$_I0L1J) {
                $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorFileTypeNotAllowed"];
                $errors[] = "file1";
              }
          }
       }

       if(count($errors) == 0){
         $_I01C0 = array("IsActive", "RemoveAllRecipients");
         if ( defined("DEMO") ) {
           if(isset($_POST["IsActive"]))
             unset($_POST["IsActive"]);
         }
         $_Qi8If = $_POST;
         unset($_Qi8If["step"]);
         if(isset($_Qi8If["file1"]))
            unset($_Qi8If["file1"]);
         _OODPL($_Qi8If, $_I0Qj1);
         if($_I0Qj1 != 0)
            $_POST["step"]++;
       }

    } // if(isset($_POST["step"]) && $_POST["step"] == 0)

    if(!isset($_POST["step"]) || (isset($_POST["step"]) && $_POST["step"] == 0) ){

      if(count($errors) > 0 && $_I0600 == "")
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];

      if($_I0Qj1 == 0)
        $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001200"], $_I0600, 'importrecipients', 'autoimportedit0_snipped.htm');
        else {
          if(!isset($_POST["Name"])) {
            $_QJlJ0 = "SELECT Name FROM $_I0f8O WHERE id=$_I0Qj1";
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
            $_Qi8If = mysql_fetch_assoc($_Q60l1);
            mysql_free_result($_Q60l1);
            $_POST["Name"] = $_Qi8If["Name"];
          }
          $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_POST["Name"]), $_I0600, 'importrecipients', 'autoimportedit0_snipped.htm');
        }

      $_QJCJi = str_replace('name="AutoImportId"', 'name="AutoImportId" value="'.$_I0Qj1.'"', $_QJCJi);
      $_QJCJi = str_replace('/userfiles/import', $_I0lQJ, $_QJCJi);
      $_I00tC = ini_get("upload_max_filesize");
      if(!isset($_I00tC) || $_I00tC == "")
        $_I00tC = "2M";
      if(!(strpos($_I00tC, "G") === false))
         $_I0188 = $_I00tC * 1024 * 1024 * 1024;
         else
         if(!(strpos($_I00tC, "M") === false))
            $_I0188 = $_I00tC * 1024 * 1024;
            else
            if(!(strpos($_I00tC, "K") === false))
               $_I0188 = $_I00tC * 1024;
               else
               $_I0188 = $_I00tC * 1;
      if($_I0188 == 0)
        $_I0188 = 2 * 1024 * 1024;
      $_I00tC .= "B";
      $_QJCJi = str_replace('upload_max_filesize', $_I00tC, $_QJCJi);
      $_QJCJi = str_replace('name="MAX_FILE_SIZE"', 'name="MAX_FILE_SIZE" value="'.$_I0188.'"', $_QJCJi);

      // ********* List of Mailinglists
      $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
      if($OwnerUserId == 0) // ist es ein Admin?
         $_Q68ff .= " WHERE (users_id=$UserId)";
         else {
          $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
         }
      $_Q68ff .= " ORDER BY Name ASC";
      $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
      _OAL8F($_Q68ff);
      $_I10Cl = "";
      while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
        $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
      }
      mysql_free_result($_Q60l1);
      $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MAILINGLISTS>", "</SHOW:MAILINGLISTS>", $_I10Cl);
      // ********* List of Groups query END

      // HOURS
      $_Q66jQ = "";
      for($_Q6llo=0; $_Q6llo<24; $_Q6llo++){
        $_QllO8 = $_Q6llo;
        if($_QllO8 < 10)
          $_QllO8 = "0".$_QllO8;
        $_Q66jQ .= '<option value="'.$_Q6llo.'">'.$_QllO8.'</option>';
      }
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:HOUR>", "</LIST:HOUR>", $_Q66jQ);

      // MINUTES
      $_Q66jQ = "";
      for($_Q6llo=0; $_Q6llo<60; $_Q6llo++) {
        $_QllO8 = $_Q6llo;
        if($_QllO8 < 10)
          $_QllO8 = "0".$_QllO8;
        $_Q66jQ .= '<option value="'.$_Q6llo.'">'.$_QllO8.'</option>';
      }
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:MINUTE>", "</LIST:MINUTE>", $_Q66jQ);


      // Day Of month
      $_I118Q = _OA68J($_I0f8O, "ImportMonthDay");
      $_I118Q = substr($_I118Q, 5);
      $_I118Q = substr($_I118Q, 0, strlen($_I118Q) - 1);
      $_I118Q = str_replace("'", "", $_I118Q);
      $_Q8otJ = explode(",", $_I118Q);
      $_Q66jQ = "";
      for($_Q6llo=1; $_Q6llo<count($_Q8otJ); $_Q6llo++) {
        if($_Q8otJ[$_Q6llo] == "every day")
           $_I11oJ = $resourcestrings[$INTERFACE_LANGUAGE]["EveryDay"];
           else
           $_I11oJ = $_Q8otJ[$_Q6llo].".";
        $_Q66jQ .= sprintf('<option value="%s">%s</option>', $_Q8otJ[$_Q6llo], $_I11oJ);
      }
      $_QJCJi = _OPR6L($_QJCJi, "<OPTION:MONTHDAY>", "</OPTION:MONTHDAY>", $_Q66jQ);

      // Day names
      $_I118Q = _OA68J($_I0f8O, "ImportDayNames");
      $_I118Q = substr($_I118Q, 5);
      $_I118Q = substr($_I118Q, 0, strlen($_I118Q) - 1);
      $_I118Q = str_replace("'", "", $_I118Q);
      $_Q8otJ = explode(",", $_I118Q);
      $_Q66jQ = "";
      for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++) {
        if($_Q8otJ[$_Q6llo] == "every day")
           $_I11oJ = $resourcestrings[$INTERFACE_LANGUAGE]["Day"];
           else
           $_I11oJ = $resourcestrings[$INTERFACE_LANGUAGE][$DayNumToDayName[$_Q8otJ[$_Q6llo]]];
        $_Q66jQ .= sprintf('<option value="%s">%s</option>', $_Q8otJ[$_Q6llo], $_I11oJ);
      }
      $_QJCJi = _OPR6L($_QJCJi, "<OPTION:DAYNAME>", "</OPTION:DAYNAME>", $_Q66jQ);

      if(!isset($_POST["step"])) {
        if($_I0Qj1 == 0){
          $_Qi8If['IsActive'] = 1;
          $_Qi8If['ImportScheduler'] = 'ImportAtDay';
          $_Qi8If['ImportDayNames'] = 'every day';
          $_Qi8If['ImportTime'] = "00:00:00";
          $_Qi8If['ImportOption'] = "ImportCSV";
        } else {
          $_QJlJ0 = "SELECT * FROM $_I0f8O WHERE id=$_I0Qj1";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          $_Qi8If = mysql_fetch_assoc($_Q60l1);

          if(!empty($_Qi8If["SQLServerName"]) && $_Qi8If["DBType"] == "MSSQL")
            $_Qi8If["SQLServerName"] = str_replace("/", "\\", $_Qi8If["SQLServerName"]);

          if(isset($_Qi8If['ImportTime'])) {
            $_Qi8If["ImportHour"] = intval(substr($_Qi8If['ImportTime'], 0, strpos($_Qi8If['ImportTime'], ':')));
            $_Qi8If["ImportMinute"] = substr($_Qi8If['ImportTime'], strpos($_Qi8If['ImportTime'], ':') + 1);
            $_Qi8If["ImportMinute"] = intval(substr($_Qi8If['ImportMinute'], 0, strpos($_Qi8If['ImportMinute'], ':')));
          }
          mysql_free_result($_Q60l1);

          _OA61D($_I0f8O, $_I01C0, array("LastImportDone", "IsMacFile", "RemoveRecipientFromGroups"));
          reset($_Qi8If);
          foreach($_Qi8If as $key => $_Q6ClO){
            if(in_array($key, $_I01C0) && $_Q6ClO == 0)
              unset($_Qi8If[$key]);
          }

        }
      } else{
        $_Qi8If = $_POST;
        unset($_Qi8If["step"]);
      }

      $_QJCJi = _OPFJA($errors, $_Qi8If, $_QJCJi);

      print $_QJCJi;
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
        $_Q6lfJ = fopen($_I0lQJ.$_POST["ImportFilename"], "r");
        if(!$_Q6lfJ) {
          $errors[] = 'ImportFilename';
          $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000056"], $_I0lQJ.$_POST["ImportFilename"]);
        } else {
          fclose($_Q6lfJ);
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

       $_QJlJ0 = "SELECT * FROM $_I0f8O WHERE id=$_I0Qj1";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $_Qi8If = mysql_fetch_assoc($_Q60l1);

       if(!empty($_Qi8If["SQLServerName"]) && $_Qi8If["DBType"] == "MSSQL")
          $_Qi8If["SQLServerName"] = str_replace("/", "\\", $_Qi8If["SQLServerName"]);

       if(isset($_Qi8If['ImportTime'])) {
         $_Qi8If["ImportHour"] = substr($_Qi8If['ImportTime'], 0, strpos($_Qi8If['ImportTime'], ':'));
         $_Qi8If["ImportMinute"] = substr($_Qi8If['ImportTime'], strpos($_Qi8If['ImportTime'], ':') + 1);
         $_Qi8If["ImportMinute"] = substr($_Qi8If['ImportMinute'], 0, strpos($_Qi8If['ImportMinute'], ':'));
       }
       mysql_free_result($_Q60l1);
       if($_Qi8If["Separator"] == "")
         $_Qi8If["Separator"] = ",";

       _OA61D($_I0f8O, $_I01C0, array("IsActive", "LastImportDone", "IsMacFile", "RemoveRecipientFromGroups"));
       if($_Qi8If["ImportOption"] == "ImportCSV")
         $_Q66jQ = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_csv', 'autoimporteditcsv1_snipped.htm');
         else
         $_Q66jQ = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_mysql', 'autoimporteditdb1_snipped.htm');
       reset($_I01C0);
       foreach($_I01C0 as $key => $_Q6ClO) {
         if(strpos($_Q66jQ, sprintf('name="%s"', $_Q6ClO)) === false){
           unset($_I01C0[$key]);
         }
       }

       reset($_I01C0);
       foreach($_I01C0 as $key => $_Q6ClO) {
         if(!isset($_POST[$_Q6ClO]))
            unset($_Qi8If[$_Q6ClO]);
       }

       $_POST = array_merge($_Qi8If, $_POST);

       $_I0i1t = _OODBQ($_POST, $errors, $_I0600, $_POST["DBType"], !empty($_POST["IsUTF8"]));
       $errors = array();
       if ($_I0i1t == 0) {
          $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br />".db_error($_I0i1t, $_POST["DBType"]));
          $errors[] = "SQLServerName";
          $errors[] = "SQLUsername";
          $errors[] = "SQLPassword";
       }

       if($_I0i1t != 0) {
         if (!db_select_db ($_POST['SQLDatabase'], $_I0i1t, $_POST["DBType"])) {
           $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".$_POST['SQLDatabase']."<br />".db_error($_I0i1t, $_POST["DBType"]));
           $errors[] = "SQLDatabase";
         } else{
           if($_POST["SQLExtImport"] == 2){
             $_QJlJ0 = str_replace('"', "'", $_POST["SQLImportSQLQuery"]);
             $_Q60l1 = db_query($_QJlJ0, $_I0i1t, $_POST["DBType"]);
             if(db_error($_I0i1t, $_POST["DBType"]) != "") {
               $_I0600 = db_error($_I0i1t, $_POST["DBType"]);
               $errors[] = "SQLImportSQLQuery";
             }
           } else{
             if($_POST["DBType"] == "MYSQL")
               $_QJlJ0 = "SELECT * FROM $_POST[SQLTableName] LIMIT 0, 1";
               else
               $_QJlJ0 = "SELECT TOP 1 * FROM $_POST[SQLTableName]";
             $_Q60l1 = db_query($_QJlJ0, $_I0i1t, $_POST["DBType"]);
             if(db_error($_I0i1t, $_POST["DBType"]) != "") {
               $_I0600 = db_error($_I0i1t, $_POST["DBType"]);
               $errors[] = "SQLTableName";
             }
           }
         }
       if($_I0i1t != $_Q61I1)
          db_close ($_I0i1t, $_POST["DBType"]);
       }
    }

    if(count($errors) == 0) {
      $_Qi8If = $_POST;

      _OA61D($_I0f8O, $_I01C0, array("IsActive", "LastImportDone", "IsMacFile", "RemoveRecipientFromGroups"));
      if($_Qi8If["ImportOption"] == "ImportCSV")
        $_Q66jQ = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_csv', 'autoimporteditcsv1_snipped.htm');
        else
        $_Q66jQ = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_mysql', 'autoimporteditdb1_snipped.htm');
      reset($_I01C0);
      foreach($_I01C0 as $key => $_Q6ClO) {
        if(strpos($_Q66jQ, sprintf('name="%s"', $_Q6ClO)) === false){
          unset($_I01C0[$key]);
        }
      }

      _OODPL($_Qi8If, $_I0Qj1);
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
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000058"];
        $errors[] = "fields[u_EMail]";
      } else {
        if( !isset($_POST["fields"]["u_EMail"]) || $_POST["fields"]["u_EMail"] == "-1" ) {
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000059"];
          $errors[] = "fields[u_EMail]";
        }
      }

      if(count($errors) == 0) {
        $_Qi8If = $_POST;
        _OA61D($_I0f8O, $_I01C0, array("IsActive", "LastImportDone", "IsMacFile"));
        $_Q66jQ = GetMainTemplate(true, $UserType, $Username, true, "", "", 'importrecipients_csv', 'autoimportedit2_snipped.htm');
        $_I01C0[] = "RemoveRecipientFromGroups"; // bug in mysql definition int(1) -> tinyint, set it manually
        reset($_I01C0);
        foreach($_I01C0 as $key => $_Q6ClO) {
          if(strpos($_Q66jQ, sprintf('name="%s"', $_Q6ClO)) === false){
            unset($_I01C0[$key]);
          }
        }

        $_I16fO = $_Qi8If["fields"];
        foreach($_Qi8If["fields"] as $key => $_Q6ClO)
           if($_Q6ClO == -1)
             unset($_Qi8If["fields"][$key]);
        $_Qi8If["fields"] = serialize($_Qi8If["fields"]);
        _OODPL($_Qi8If, $_I0Qj1);
        $_Qi8If["fields"] = $_I16fO;
        $_POST["step"]++;
      }

    }


  if(count($errors) > 0 && $_I0600 == "")
     $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];

  // **************************** fill forms

  if($_I0Qj1 > 0) {
    $_QJlJ0 = "SELECT * FROM $_I0f8O WHERE id=$_I0Qj1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Qi8If = mysql_fetch_assoc($_Q60l1);
    if(!empty($_Qi8If["SQLServerName"]) && $_Qi8If["DBType"] == "MSSQL")
       $_Qi8If["SQLServerName"] = str_replace("/", "\\", $_Qi8If["SQLServerName"]);

    if(isset($_Qi8If['ImportTime'])) {
      $_Qi8If["ImportHour"] = substr($_Qi8If['ImportTime'], 0, strpos($_Qi8If['ImportTime'], ':'));
      $_Qi8If["ImportMinute"] = substr($_Qi8If['ImportTime'], strpos($_Qi8If['ImportTime'], ':') + 1);
      $_Qi8If["ImportMinute"] = substr($_Qi8If['ImportMinute'], 0, strpos($_Qi8If['ImportMinute'], ':'));
    }
    mysql_free_result($_Q60l1);
    if($_Qi8If["Separator"] == "")
      $_Qi8If["Separator"] = ",";
    $_Qi8If = array_merge($_Qi8If, $_POST);
  } else
    $_Qi8If = $_POST;

  if($_POST["step"] == 1){
    if($_Qi8If["ImportOption"] == "ImportCSV")
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_Qi8If["Name"]), $_I0600, 'importrecipients_csv', 'autoimporteditcsv1_snipped.htm');
      else
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_Qi8If["Name"]), $_I0600, 'importrecipients_mysql', 'autoimporteditdb1_snipped.htm');

    _OA61D($_I0f8O, $_I01C0, array("IsActive", "LastImportDone", "IsMacFile", "RemoveRecipientFromGroups"));

    reset($_Qi8If);
    foreach($_Qi8If as $key => $_Q6ClO){
      if(in_array($key, $_I01C0) && $_Q6ClO == 0)
        unset($_Qi8If[$key]);
    }

    // file
    if($_Qi8If["ImportOption"] == "ImportCSV"){
      $_I16oJ = "";
      $_QCC8C = opendir ( substr($_I0lQJ, 0, strlen($_I0lQJ) - 1) );
      while (false !== ($_Q6lfJ = readdir($_QCC8C))) {
        if (!is_dir($_I0lQJ.$_Q6lfJ) && $_Q6lfJ != "." && $_Q6lfJ != ".." && $_Q6lfJ != "index.php") {
          if( isset($_Qi8If["ImportFilename"]) && $_Qi8If["ImportFilename"] == $_Q6lfJ )
            $_I16L6 = ' selected="selected"';
            else
            $_I16L6 = '';
          $_I16oJ .= '<option value="'.$_Q6lfJ.'"' . $_I16L6 . '>'.$_Q6lfJ.'</option>'.$_Q6JJJ;
        }
      }
      closedir($_QCC8C);
      $_QJCJi = _OPR6L($_QJCJi, "<OPTION:FILENAME>", "</OPTION:FILENAME>", $_I16oJ);
    }

    // db
    if($_Qi8If["ImportOption"] == "ImportDB"){
      $_I16oJ = "";
      $_I0i1t = _OODBQ($_Qi8If, $errors, $_I0600, $_Qi8If["DBType"], !empty($_Qi8If["IsUTF8"]) && $_Qi8If["IsUTF8"]);

      if( $_I0i1t != 0 ) {
        $_I1fiC = db_get_tables($_Qi8If['SQLDatabase'], $_I0i1t, $_Qi8If["DBType"]);
        for ($_Q6llo = 0; $_Q6llo < count($_I1fiC); $_Q6llo++) {
           $_I16oJ .= '<option value="'.$_I1fiC[$_Q6llo].'">'.$_I1fiC[$_Q6llo].'</option>'.$_Q6JJJ;
        }
        if($_I0i1t != $_Q61I1)
           db_close ($_I0i1t, $_Qi8If["DBType"]);
      }
      $_QJCJi = _OPR6L($_QJCJi, "<OPTION:SQLTABLENAME>", "</OPTION:SQLTABLENAME>", $_I16oJ);
    }

  }

  if($_POST["step"] == 2){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_Qi8If["Name"]), $_I0600, 'importrecipients_csv', 'autoimportedit2_snipped.htm');

    _OA61D($_I0f8O, $_I01C0, array("IsActive", "LastImportDone", "IsMacFile"));

    reset($_Qi8If);
    foreach($_Qi8If as $key => $_Q6ClO){
      if(in_array($key, $_I01C0) && $_Q6ClO == 0)
        unset($_Qi8If[$key]);
    }

    // **************************** file
    if($_Qi8If["ImportOption"] == "ImportCSV"){
      $_Q6lfJ = fopen($_I0lQJ.$_Qi8If["ImportFilename"], "r");
      if(!$_Q6lfJ) {
        exit;
      }

      $_QL8Q8 = "";
      // mac file?
      $_I1816 = 0;
      while (!feof($_Q6lfJ)) {
        $_QL8Q8 = fgetc($_Q6lfJ);
        if($_QL8Q8 == chr(10) || $_QL8Q8 == chr(13)) {

          $_I18I0 = chr(10);
          if(!feof($_Q6lfJ))
            $_I18I0 = fgetc($_Q6lfJ);

          if($_QL8Q8 == chr(13) && $_I18I0 != chr(10)) {
            $_I1816 = 1;
          }
          break;
        }
      }

      $_I1t0l = ftell($_Q6lfJ) -1;
      fseek($_Q6lfJ, 0);
      $_I1t66 = fread($_Q6lfJ, $_I1t0l);
      fclose($_Q6lfJ);

      // UTF8 BOM?
      $_I1O0j = 'ï»¿';
      $_Q6i6i = strpos($_I1t66, $_I1O0j);
      if($_Q6i6i !== false && $_Q6i6i == 0)
        $_I1t66 = substr($_I1t66, strlen($_I1O0j));

      // UTF8?
      if ( !( isset($_POST["IsUTF8"]) && $_POST["IsUTF8"] != "") ) {
         $_QfoQo = utf8_encode($_I1t66);
         if($_QfoQo != "")
           $_I1t66 = $_QfoQo;
      }


      $_I1I1J = $_Qi8If["Separator"];
      if($_I1I1J == '\t')
         $_I1I1J = "\t";
      $_Q8otJ = explode(trim($_I1I1J), $_I1t66);
    }

    // **************************** db
    if($_Qi8If["ImportOption"] == "ImportDB"){
      $_I0i1t = _OODBQ($_Qi8If, $errors, $_I0600, $_Qi8If["DBType"], !empty($_Qi8If["IsUTF8"]) && $_Qi8If["IsUTF8"] );
      if(!$_I0i1t) {
        exit;
      }

      $_Q8otJ = array();
      if($_Qi8If["SQLExtImport"] == 2){
        $_Qi8If["SQLImportSQLQuery"] = str_replace('"', "'", $_Qi8If["SQLImportSQLQuery"]);
        $_Q60l1 = db_query($_Qi8If["SQLImportSQLQuery"], $_I0i1t, $_Qi8If["DBType"]);
        if(db_error($_I0i1t, $_Qi8If["DBType"]) != "") {
          exit;
        }

        if(!$_Q60l1 || db_num_rows($_Q60l1, $_Qi8If["DBType"]) == 0) {
          _OOEOA($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["MySQLQueryEmptyResult"]);
          exit;
        }

        $_Q6llo=0;
        while ($_Q6llo < db_num_fields($_Q60l1, $_Qi8If["DBType"])) {
          $_I1OQ8 = db_fetch_field($_Q60l1, $_Q6llo, $_Qi8If["DBType"]);
          if($_I1OQ8) {
            $_Q8otJ[]=$_I1OQ8->name;
          }
          $_Q6llo++;
        }
        db_free_result($_Q60l1, $_Qi8If["DBType"]);
      }

      if(count($_Q8otJ) == 0 && $_Qi8If["DBType"] == "MSSQL" && $_Qi8If["SQLExtImport"] != 2) {

        $_Q60l1 = db_query("SELECT * FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = '$_Qi8If[SQLTableName]'", $_I0i1t, $_Qi8If["DBType"]);

        if(!$_Q60l1 || db_num_rows($_Q60l1, $_Qi8If["DBType"]) == 0) {
          _OOEOA($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["MySQLQueryEmptyResult"]);
          exit;
        }

        while($_Q6Q1C = db_fetch_assoc($_Q60l1, $_Qi8If["DBType"])){
          $_Q8otJ[] = $_Q6Q1C["COLUMN_NAME"];
        }
        db_free_result($_Q60l1, $_Qi8If["DBType"]);


      }

      if(count($_Q8otJ) == 0 && $_Qi8If["DBType"] == "MYSQL")
        _OAJCF($_I0i1t, $_Qi8If["SQLTableName"], $_Q8otJ);
      if($_I0i1t != $_Q61I1)
         db_close($_I0i1t, $_Qi8If["DBType"]);
    }

    // **************************** fields
    $_QfoQo = "";
    for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++)
      $_QfoQo .= sprintf('<option value="%s">%s</option>'.$_Q6JJJ, $_Q6llo, htmlentities($_Q8otJ[$_Q6llo], ENT_COMPAT, $_Q6QQL)  );
    $_QfoQo = $_Q6JJJ.sprintf('<option value="%s">%s</option>'.$_Q6JJJ, "-1", "--"  ).$_Q6JJJ.$_QfoQo;

    $_I1OLj = _OP81D($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>");
    $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    $_Q6ICj = "";
    $_Q6llo=1;
    $_I1oot = 0;
    $_QL8Q8=1;
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      if($_Q6llo == 1)
         $_Q66jQ = $_I1OLj;
      $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', '<label for="importfield'.$_QL8Q8.'">'.$_Q6Q1C["text"]."</label>", $_Q66jQ);
      $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="fields['.$_Q6Q1C["fieldname"].']" size="1" id="importfield'.$_QL8Q8.'" class="import_select">'.$_QfoQo.'</select>', $_Q66jQ);
      if($_Q6Q1C["fieldname"] == "u_Birthday") {
        $_I1oot = $_Q6llo;
      }
      $_Q6llo++;
      $_QL8Q8++;
      if($_Q6llo>2) {
        $_Q6llo=1;
        $_Q6ICj .= $_Q66jQ;
        if($_I1oot) {
          $_Q66jQ = $_I1OLj;
          if($_I1oot == 1){
            $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', '<label for="importfield'.$_QL8Q8.'">'.$resourcestrings[$INTERFACE_LANGUAGE]["000057"]."</label>", $_Q66jQ);
            $_QllO8 = '<option value="dd.mm.yyyy">dd.mm.yyyy</option>';
            $_QllO8 .= '<option value="yyyy-mm-dd">yyyy-mm-dd</option>';
            $_QllO8 .= '<option value="mm-dd-yyyy">mm-dd-yyyy</option>';
            $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="BirthdayDateFormat" size="1" id="importfield'.$_QL8Q8.'" class="import_select">'.$_QllO8.'</select>', $_Q66jQ);
            $_Q6llo++;
            $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', "&nbsp;", $_Q66jQ);
            $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', "&nbsp;", $_Q66jQ);
          } else{
            $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', "&nbsp;", $_Q66jQ);
            $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', "&nbsp;", $_Q66jQ);
            $_Q6llo++;
            $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', '<label for="importfield'.$_QL8Q8.'">'.$resourcestrings[$INTERFACE_LANGUAGE]["000057"]."</label>", $_Q66jQ);
            $_QllO8 = '<option value="dd.mm.yyyy">dd.mm.yyyy</option>';
            $_QllO8 .= '<option value="yyyy-mm-dd">yyyy-mm-dd</option>';
            $_QllO8 .= '<option value="mm-dd-yyyy">mm-dd-yyyy</option>';
            $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="BirthdayDateFormat" size="1" id="importfield'.$_QL8Q8.'" class="import_select">'.$_QllO8.'</select>', $_Q66jQ);
          }
          $_Q6llo=1;
          $_Q6ICj .= $_Q66jQ;
          $_I1oot = 0;
          $_QL8Q8++;
        }
        $_Q66jQ = "";
      }
    }
    if($_Q66jQ != "")
      $_Q6ICj .= $_Q66jQ;
    $_QJCJi = _OPR6L($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>", $_Q6ICj);

    // ************** Groups START
    $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=$_Qi8If[maillists_id]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_I1COO = mysql_fetch_array($_Q60l1);
    $_Q6t6j = $_I1COO["GroupsTableName"];
    mysql_free_result($_Q60l1);

    // ********* List of Groups SQL query
    $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q6t6j";
    $_Q68ff .= " ORDER BY Name ASC";
    $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
    _OAL8F($_Q68ff);
    $_I10Cl = "";
    while($_I1COO=mysql_fetch_array($_Q60l1)) {
      $_I10Cl .= '<option value="'.$_I1COO["id"].'">'.$_I1COO["Name"].'</option>'.$_Q6JJJ;
    }
    mysql_free_result($_Q60l1);

    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_I10Cl);
    // ********* List of Groups query END

    // ************** Groups END

    // Groups assignment START
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:IMPORTFIELD>", "</SHOW:IMPORTFIELD>", $_QfoQo);
    // Groups assignment END


    // umwandeln, damit er es wieder findet
    $_I16jJ = $_Qi8If["fields"];
    if(!is_array($_I16jJ)) {
       $_I16jJ = unserialize($_I16jJ);
       if($_I16jJ === false)
         $_I16jJ = array();
    }
    unset($_Qi8If["fields"]);
    reset($_I16jJ);
    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
        $_Qi8If["fields[$_I1i8O]"] = $_I1L81;
      }
    }

    if(!isset($_Qi8If["GroupsOption"]) || $_Qi8If["GroupsOption"] <= 0)
       $_Qi8If["GroupsOption"] = 1;
    if(!isset($_Qi8If["ExImportOption"]) || $_Qi8If["ExImportOption"] <= 0)
       $_Qi8If["ExImportOption"] = 1;
    if(isset($_Qi8If["RemoveRecipientFromGroups"]) && $_Qi8If["RemoveRecipientFromGroups"] <= 0)
       unset($_Qi8If["RemoveRecipientFromGroups"]);
  }

  if($_POST["step"] == 3){
    if ( defined("DEMO") ) {
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001206"];
    }
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_Qi8If["Name"]), $_I0600, 'importrecipients_csv', 'autoimportedit3_snipped.htm');

  }

  unset($_Qi8If["step"]);
  unset($_Qi8If["AutoImportId"]);

  $_QJCJi = str_replace('name="AutoImportId"', 'name="AutoImportId" value="'.$_I0Qj1.'"', $_QJCJi);
  $_QJCJi = _OPFJA($errors, $_Qi8If, $_QJCJi);
  print $_QJCJi;

  function _OODPL($_Qi8If, &$_I0Qj1){
    global $_I0f8O, $_I01C0, $_I01lt, $_Q61I1;

    if(!empty($_Qi8If["SQLServerName"]))
       $_Qi8If["SQLServerName"] = str_replace("\\", "/", $_Qi8If["SQLServerName"]);

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM $_I0f8O";
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

    // update entry? check mailinglist
    if($_I0Qj1 != 0) {
     $_QJlJ0 = "SELECT * FROM $_I0f8O WHERE id=$_I0Qj1";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
     $_I1lIL = false;
     if( isset($_Qi8If["maillists_id"]) && $_Q6Q1C["maillists_id"] != $_Qi8If["maillists_id"]  ) { // not the same?
       $_I1lIL = true;
     } else
       if(isset($_Qi8If["ImportOption"]) && $_Q6Q1C["ImportOption"] != $_Qi8If["ImportOption"]){
          $_I1lIL = true;
       } else
       if(isset($_Qi8If["ImportOption"]) && $_Qi8If["ImportOption"] == 'ImportCSV' && isset($_Qi8If["ImportFilename"]) && $_Qi8If["ImportFilename"] != $_Q6Q1C["ImportFilename"] ){
          $_I1lIL = true;
       } else
       if(isset($_Qi8If["ImportOption"]) && $_Qi8If["ImportOption"] == 'ImportDB' ){
          if(
              (isset($_Qi8If["DBType"]) && $_Qi8If["DBType"]) != $_Q6Q1C["DBType"] ||
              (isset($_Qi8If["SQLServerName"]) && $_Qi8If["SQLServerName"] != $_Q6Q1C["SQLServerName"]) ||
              (isset($_Qi8If["SQLDatabase"]) && $_Qi8If["SQLDatabase"] != $_Q6Q1C["SQLDatabase"]) ||
              (isset($_Qi8If["SQLUsername"]) && $_Qi8If["SQLUsername"] != $_Q6Q1C["SQLUsername"]) ||
              (isset($_Qi8If["SQLPassword"]) && $_Qi8If["SQLPassword"] != $_Q6Q1C["SQLPassword"]) ||
              (isset($_Qi8If["SQLTableName"]) && $_Qi8If["SQLTableName"] != $_Q6Q1C["SQLTableName"]) ||
              (isset($_Qi8If["SQLExtImport"]) && $_Qi8If["SQLExtImport"] != $_Q6Q1C["SQLExtImport"]) ||
              (isset($_Qi8If["SQLExtImport"]) && $_Qi8If["SQLExtImport"] == 2 && $_Qi8If["SQLImportSQLQuery"] != $_Q6Q1C["SQLImportSQLQuery"] )

            )
          $_I1lIL = true;
       }
     if($_I1lIL){
       $_QJlJ0 = "UPDATE $_I0f8O SET `LastImportDone`=1, `LastImportDate`='0000-00-00 00:00:00' WHERE id=$_I0Qj1";
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
     }
    }

    // new entry?
    if($_I0Qj1 == 0) {
      $_QJlJ0 = "INSERT INTO $_I0f8O SET CreateDate=NOW(), `Name`="._OPQLR($_Qi8If["Name"]).", `maillists_id`=$_Qi8If[maillists_id]";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_I0Qj1 = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }

    $_QJlJ0 = "UPDATE $_I0f8O SET ";
    $_I1l61 = array();
    for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
      $key = $_QLLjo[$_Q6llo];
      if ( isset($_Qi8If[$_QLLjo[$_Q6llo]]) ) {
        if(in_array($key, $_I01C0))
          if( $_Qi8If[$key] == "1" || intval($_Qi8If[$key]) == 0 )
             $_I1l61[] = "`$key`=1";
             else
              ;
        else {
           $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]))."";
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
    $_QJlJ0 .= " WHERE id=$_I0Qj1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }
  }

  function _OODBQ($_Qi8If, &$errors, &$_I0600, $_I0C11, $_I0ift) {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;
    $_I0i1t = db_connect ($_Qi8If['SQLServerName'], $_Qi8If['SQLUsername'], $_Qi8If['SQLPassword'], $_I0C11, $_Qi8If['SQLDatabase'], $_I0ift);
    if ($_I0i1t == 0) {
       $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br/>".db_error($_I0i1t, $_I0C11));
       $errors[] = "SQLServerName";
       $errors[] = "SQLUsername";
       $errors[] = "SQLPassword";
    }

    if (!db_select_db ($_Qi8If['SQLDatabase'], $_I0i1t, $_I0C11)) {
      $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".$_Qi8If['SQLDatabase']."<br/>".db_error($_I0i1t, $_I0C11));
      $errors[] = "SQLDatabase";
      if($_I0i1t != $_Q61I1)
         db_close ($_I0i1t, $_I0C11);
      $_I0i1t = 0;
    }
    return $_I0i1t;
  }

  function _OOEOA($errors, $_I1l66, $_I0600 = "") {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_I0Qj1, $UserType, $Username, $_Q61I1;
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001205"], $_I1l66["Name"]), $_I0600, 'importrecipients_mysql', 'autoimportedit2_emptyresult_snipped.htm');

    if(isset($_I1l66["step"]))
      unset($_I1l66["step"]);

    unset($_I1l66["AutoImportId"]);

    $_QJCJi = str_replace('name="AutoImportId"', 'name="AutoImportId" value="'.$_I0Qj1.'"', $_QJCJi);
    $_QJCJi = _OPFJA($errors, $_I1l66, $_QJCJi);
    print $_QJCJi;
  }
?>

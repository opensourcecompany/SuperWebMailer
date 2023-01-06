<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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

  include_once("newslettersubunsub_ops.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("dbimporthelper.inc.php");

  function _LL1JL(&$_JIfo0){
    global $_QLttI;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $resourcestrings, $_I18lo, $_QL88I, $_I1tQf, $_Ifi1J;
    global $_J1t6J;
    global $_ItfiJ;

    $_JIfo0 = "Auto importing recipients starts...<br />";
    $_JIlit = 0;

    $_QLfol = "SELECT * FROM $_I18lo WHERE UserType='Admin' AND IsActive>0";
    $_I1OQL = mysql_query($_QLfol, $_QLttI);
    while($_I1OQL && $_QLO0f = mysql_fetch_assoc($_I1OQL) ) {
      _LRCOC();
      $UserId = $_QLO0f["id"];
      $OwnerUserId = 0;
      $Username = $_QLO0f["Username"];
      $UserType = $_QLO0f["UserType"];
      $AccountType = $_QLO0f["AccountType"];
      $INTERFACE_THEMESID = $_QLO0f["ThemesId"];
      $INTERFACE_LANGUAGE = $_QLO0f["Language"];

      _LRPQ6($INTERFACE_LANGUAGE);

      _JQRLR($INTERFACE_LANGUAGE);

      $_QLfol = "SELECT Theme FROM $_I1tQf WHERE id=$INTERFACE_THEMESID";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      $_I1OfI = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_I1OfI[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);

      $_QLfol = "SELECT $_ItfiJ.* ";

      $_QLfol .= "FROM $_ItfiJ";

      $_QLfol .= " WHERE ($_ItfiJ.IsActive=1 AND ";
      $_QLfol .= " ( (CURRENT_TIME() > $_ItfiJ.ImportTime AND IF(LastImportDate <> '0000-00-00 00:00:00', TO_DAYS(NOW()) <> TO_DAYS(LastImportDate), 1) ) OR $_ItfiJ.LastImportDone = 0) )";
      $_QLfol .= " AND ( ";

      $_QLfol .= " ( $_ItfiJ.ImportScheduler = 'ImportAtDay' AND ($_ItfiJ.ImportDayNames='every day' OR $_ItfiJ.ImportDayNames=CAST(WEEKDAY(NOW()) AS CHAR) ) ) ";

      $_QLfol .= " OR ";

      $_QLfol .= " ( $_ItfiJ.ImportScheduler = 'ImportOnceAMonth' AND CAST(DAYOFMONTH(NOW()) AS CHAR) = $_ItfiJ.ImportMonthDay ) ";

      $_QLfol .= " OR ";

      $_QLfol .= " ( $_ItfiJ.LastImportDone = 0 ) "; // not completly done

      $_QLfol .= " )";

      $_I1O6j = mysql_query($_QLfol, $_QLttI);

      if(mysql_error($_QLttI) != "")
        $_JIfo0 .= "MySQL error while selecting data: ".mysql_error($_QLttI);

      while($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
         _LRCOC();
         $_JIfo0 .= "<br /><br />Checking $_I1OfI[Name]...";

         $_QLfol = "SELECT * FROM $_QL88I WHERE id=$_I1OfI[maillists_id]";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_I80Jo = mysql_fetch_assoc($_QL8i1);
         $_IfJoo = $_I80Jo["FormsTableName"];
         $FormId = $_I80Jo["forms_id"];
         mysql_free_result($_QL8i1);

         $_Jj08l = array();
         if($_I1OfI["SendOptInEMail"] ) {
           $_QLfol = "SELECT $_IfJoo.*, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfJoo LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfJoo.messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfJoo.ThemesId WHERE $_IfJoo.id=$FormId";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           $_Jj08l = mysql_fetch_assoc($_QL8i1);
           mysql_free_result($_QL8i1);
           $_Jj08l["MailingListId"] = $_I80Jo["id"];
           $_Jj08l["FormId"] = $FormId;
         }

         $_Jj1f1 = 0;
         if($_I1OfI["ImportOption"] == 'ImportCSV') {
            if($_I1OfI["Separator"] == '\t')
               $_I1OfI["Separator"] = "\t";

            $_JjQjl = "autoimportfile_errors$_I1OfI[id].txt";
            $_JjIQL = $_J1t6J.$_JjQjl;

            _LL1RJ($_I1OfI, $_I80Jo, $_Jj08l, $_JjIQL, $_JIfo0, $_Jj1f1);
            $_JIfo0 .= "<br /><br />$_Jj1f1 lines processed.";
            $_JIlit += $_Jj1f1;
         }

         if($_I1OfI["ImportOption"] == 'ImportDB') {
            if(!empty($_I1OfI["SQLServerName"]) && $_I1OfI["DBType"] == "MSSQL")
              $_I1OfI["SQLServerName"] = str_replace("/", "\\", $_I1OfI["SQLServerName"]);
            $_JjQjl = "autoimportdb_errors$_I1OfI[id].txt";
            $_JjIQL = $_J1t6J.$_JjQjl;
            _LL1P0($_I1OfI, $_I80Jo, $_Jj08l, $_JjIQL, $_JIfo0, $_Jj1f1);
            $_JIfo0 .= "<br /><br />$_Jj1f1 records processed.";
            $_JIlit += $_Jj1f1;
         }


      }


      mysql_free_result($_I1O6j);
    }
    if($_I1OQL)
      mysql_free_result($_I1OQL);


    $_JIfo0 .= "<br /><br />Auto importing done.";
    if($_JIlit)
      return true;
      else
      return -1;
  }

  function _LL1RJ($_JjI8O, $_I80Jo, $_Jjjf6, $_JjIQL, &$_JIfo0, &$_Jj1f1){
    global $_QLttI;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLo06;
    global $_ItfiJ, $_ItL8f;
    global $_I8I6o, $_I8jjj, $_IfJ66, $_I8jLt, $_I8Jti, $MailingListId;

    $_Jj1f1 = 0;
    $_Jj08l = $_Jjjf6;
    $_JjJJl = 0;

    $_Jj6jC = $_I80Jo["MaillistTableName"];
    $_I8jjj = $_I80Jo["StatisticsTableName"];
    $_QljJi = $_I80Jo["GroupsTableName"];
    $_IfJoo = $_I80Jo["FormsTableName"];
    $_IfJ66 = $_I80Jo["MailListToGroupsTableName"];
    $FormId = $_I80Jo["forms_id"];
    $_jjj8f = $_I80Jo["LocalBlocklistTableName"];
    $_Jj6f0 = $_I80Jo["LocalDomainBlocklistTableName"];

    $_I8I6o = $_I80Jo["MaillistTableName"];
    $MailingListId = $_I80Jo["id"];
    $_I8jLt = $_I80Jo["MailLogTableName"];
    $_I8Jti = $_I80Jo["EditTableName"];

    if($_JjI8O["LastImportDone"]){
       $_JjI8O["LastRowCount"] = 0;
       $_JjI8O["LastImportRowCount"] = 0;
       $_JjI8O["LastStartPosition"] = 0;
       $_JjI8O["LastFilePosition"] = 0;
       $_JjI8O["LastTablePosition"] = 0;
       $_JjI8O["LastImportDone"] = 0;
       $_JjI8O["IsMacFile"] = 0;
       $_JjI8O["LastImportRemoveMode"] = 0;

       $_QlCtl = fopen($_ItL8f.$_JjI8O["ImportFilename"], "r");
       if(!$_QlCtl){
         $_JIfo0 .= "<br />Can't open file ".$_ItL8f.$_JjI8O["ImportFilename"];
         return false;
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
       fclose($_QlCtl);
       $_JjI8O["IsMacFile"] = $_IO88Q;

       if($_JjI8O["RemoveAllRecipients"])
        $_JjI8O["LastImportRemoveMode"] = 1;


       $_QLfol = "UPDATE $_ItfiJ SET LastImportDate=NOW(), LastImportDone=$_JjI8O[LastImportDone], LastImportRemoveMode=$_JjI8O[LastImportRemoveMode], IsMacFile=$_JjI8O[IsMacFile], LastRowCount=$_JjI8O[LastRowCount], LastImportRowCount=$_JjI8O[LastImportRowCount], LastStartPosition=$_JjI8O[LastStartPosition], LastFilePosition=$_JjI8O[LastFilePosition], LastTablePosition=$_JjI8O[LastTablePosition]";
       $_QLfol .= " WHERE id=$_JjI8O[id]";
       mysql_query($_QLfol, $_QLttI);
    }


    if($_JjI8O["LastImportRemoveMode"]){
      _LRCOC();

      $_JIfo0 .= "<br />Removing recipients...";

      while(true){
        $_QLfol = "SELECT `id` FROM `$_Jj6jC` LIMIT 0, 50";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_I8oIJ = array();
        $_IQ0Cj = array();
        while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
         $_I8oIJ[] = $_QLO0f["id"];
        }
        mysql_free_result($_QL8i1);
        if(count($_I8oIJ)){
         _LRCOC();
         _J1OQP($_I8oIJ, $_IQ0Cj);
         if(count($_IQ0Cj)){
            $_JIfo0 .= "<br />".join("<br />", $_IQ0Cj);
            return false;
           }
        } else{
          $_JIfo0 .= "<br />Removing recipients done.";
          $_JjI8O["LastImportRemoveMode"] = 0;
          $_QLfol = "UPDATE `$_ItfiJ` SET `LastImportRemoveMode`=$_JjI8O[LastImportRemoveMode]";
          $_QLfol .= " WHERE `id`=$_JjI8O[id]";
          mysql_query($_QLfol, $_QLttI);
          break;
        }
      }
    }

    if($_JjI8O["IsMacFile"])
       ini_set('auto_detect_line_endings', TRUE);

    $_Jj68f = _JOLQE("ECGListCheck");
    $_JjJJl = 0;

    $_IJljf = fopen($_ItL8f.$_JjI8O["ImportFilename"], "r");
    $_If61l = fstat($_IJljf);
    $_QlOjt = 0;

    // spring zur fileposition
    if($_JjI8O["LastFilePosition"] != 0) {
        if($_JjI8O["CreateErrorLog"]){
          $_JjJJl = @fopen($_JjIQL, "a");
        }

        if($_JjI8O["LastFilePosition"] != 0)
           fseek($_IJljf, $_JjI8O["LastFilePosition"]);

        // sind wir fertig?
        if($_JjI8O["LastFilePosition"] == -1 || feof($_IJljf) || ftell($_IJljf) >= $_If61l["size"] ) {
          fclose($_IJljf);
          if( $_JjI8O["RemoveFile"] ) {
            if( !@unlink($_ItL8f.$_JjI8O["ImportFilename"]) ) {
               $_JIfo0 .= "<br />Can'remove file ".$_ItL8f.$_JjI8O["ImportFilename"];
            }
          }

          $_QLfol = "UPDATE $_ItfiJ SET LastImportDone=1";
          $_QLfol .= " WHERE id=$_JjI8O[id]";
          mysql_query($_QLfol, $_QLttI);

          $_JIfo0 .= "<br /><br />Importing file $_ItL8f$_JjI8O[ImportFilename] done.";

          return true;
        }

      }
      else { // noch nie angefasst
        if($_JjI8O["CreateErrorLog"]){
          $_JjJJl = @fopen($_JjIQL, "w");
        }

        // UTF8 BOM test
        $_IOC1j = 'ï»¿';
        $_IiLOj = fread($_IJljf, strlen($_IOC1j));
        if($_IiLOj != $_IOC1j)
          fseek($_IJljf, 0);

        // Zeile 1 weg?
        $_QlOjt = 0;
        if($_JjI8O["Header1Line"]) {
           $_IiLOj = fgets($_IJljf, 4096);
           $_QlOjt = 1;
        }
      }

    if($_JjI8O["LastStartPosition"] == 0 ) {
      $_Iil6i = $_QlOjt;
    } else {
      $_Iil6i = $_JjI8O["LastStartPosition"];
    }

    $_IOJoI = $_JjI8O["fields"];
    if(!is_array($_IOJoI)) {
       $_IOJoI = @unserialize($_IOJoI);
       if($_IOJoI === false)
         $_IOJoI = array();
    }

    _LRCOC();

    // hier liest er die Zeilen
    for($_Qli6J=$_Iil6i; ($_Qli6J<$_Iil6i + $_JjI8O["ImportLines"]) && !feof($_IJljf); $_Qli6J++) {

      $_Jj1f1++;
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
      $_JjI8O["LastRowCount"]++;
      $_IiLOj = fgets($_IJljf, 4096);
      // UTF8?
      if ( !$_JjI8O["IsUTF8"] ) {
         $_I0Clj = utf8_encode($_IiLOj);
         if($_I0Clj != "")
           $_IiLOj = $_I0Clj;
      }

      $_I0QjQ = explode($_JjI8O["Separator"], $_IiLOj);

      $_Jj6L1 = array();
      if( $_JjI8O["GroupsOption"] == 2 ) {
        if($_JjI8O["ImportGroupField"] < count($_I0QjQ)) {
           $_Jj6L1 = $_I0QjQ[$_JjI8O["ImportGroupField"]];
           if($_JjI8O["RemoveQuotes"]) {
             $_Jj6L1 = str_replace('"', '', $_Jj6L1);
             $_Jj6L1 = str_replace("\'", '', $_Jj6L1);
             $_Jj6L1 = str_replace("`", '', $_Jj6L1);
             $_Jj6L1 = str_replace("´", '', $_Jj6L1);
           }
           if($_JjI8O["RemoveSpaces"]) {
            $_Jj6L1 = trim($_Jj6L1);
           }
           $_Jjf0J = ",";
           if($_JjI8O["Separator"] == ",")
             $_Jjf0J = ";";
           $_Jj6L1 = explode($_Jjf0J, $_Jj6L1);
           for($_JjfO0=0; $_JjfO0<count($_Jj6L1); $_JjfO0++)
             $_Jj6L1[$_JjfO0] = str_replace(",", "", $_Jj6L1[$_JjfO0]);
        }
      }

      $_QLfol = "INSERT IGNORE INTO $_Jj6jC SET DateOfSubscription=NOW()";

      $_JjfiL = false;
      $_Jj8iQ = "";
      if($_JjI8O["SendOptInEMail"]) {
        $_Jj8iQ = "'OptInConfirmationPending'";
        $_QLfol .= ", SubscriptionStatus=$_Jj8iQ";
        $_JjfiL = true;
        }
        else {
          $_Jj8iQ = "'Subscribed'";
          $_QLfol .= ", SubscriptionStatus=$_Jj8iQ, DateOfOptInConfirmation=NOW(), IPOnSubscription='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."'";
        }

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
        if($_JjI8O["RemoveQuotes"]) {
          $_IOCjL = str_replace('"', '', $_IOCjL);
          $_IOCjL = str_replace("\'", '', $_IOCjL);
          $_IOCjL = str_replace("`", '', $_IOCjL);
          $_IOCjL = str_replace("´", '', $_IOCjL);
        }
        if($_JjI8O["RemoveSpaces"]) {
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
            if($_JjI8O["BirthdayDateFormat"] == "dd.mm.yyyy") {
              $_jJQOo = explode(".", $_IOCjL);
              while(count($_jJQOo) < 3)
                 $_jJQOo[] = "f";
              $_jjOlo = $_jJQOo[0];
              $_jJIft = $_jJQOo[1];
              $_jJjQi = $_jJQOo[2];
            } else
              if($_JjI8O["BirthdayDateFormat"] == "yyyy-mm-dd") {
               $_jJQOo = explode("-", $_IOCjL);
               while(count($_jJQOo) < 3)
                  $_jJQOo[] = "f";
               $_jjOlo = $_jJQOo[2];
               $_jJIft = $_jJQOo[1];
               $_jJjQi = $_jJQOo[0];
              } else
                if($_JjI8O["BirthdayDateFormat"] == "mm-dd-yyyy") {
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
            _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressIncorrect"], $_IOCjL), $_JjI8O, $_JjJJl);
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
         _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorNoEMailAddress"], $_Jj8lt), $_JjI8O, $_JjJJl);
         $_QLfol = "";
      }

      if($_QLfol != "")
        $_QLfol .= ", ".join(", ", $_jtiJJ);

      $_JjtC8 = false;
      if($_QLfol != "") {

        $_JjOjI = substr($_Jj8lt, strpos($_Jj8lt, '@') + 1);
        _LRCOC();

        if(_J18DA($_Jj8lt)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalBlockList"], $_Jj8lt), $_JjI8O, $_JjJJl);
        }

        if(!$_JjtC8 && _J18FQ($_Jj8lt, $_JjI8O["maillists_id"], $_jjj8f)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalBlockList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

        if(!$_JjtC8 && _J1PQO($_JjOjI)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalDomainBlockList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

        if(!$_JjtC8 && _J1P6D($_JjOjI, $_JjI8O["maillists_id"], $_Jj6f0)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalDomainBlockList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

        if(!$_JjtC8 && $_Jj68f ) {
          $_I016j = _L6AJP($_Jj8lt);
          $_JjtC8 = (is_bool($_I016j) && $_I016j);
          if($_JjtC8)
            _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInECGList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

      }

      _LRCOC();
      if($_JjtC8 && !$_JjI8O["ImportBlockedRecipients"])
        $_QLfol = "";

      if($_QLfol != "") {
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_QLJfI = mysql_error($_QLttI);
        if($_QLJfI != "")
          $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
        if($_JjI8O["CreateErrorLog"] && $_JjJJl && mysql_affected_rows($_QLttI) == 0){
           _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorDuplicateEntry"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }
      }

      _LRCOC();
      if($_QLfol != "" && mysql_affected_rows($_QLttI) > 0 ) {
        $_JjI8O["LastImportRowCount"]++;

        // statistics
        $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
        $_QLO0f=mysql_fetch_array($_QL8i1);
        $_IfLJj = $_QLO0f[0];
        mysql_free_result($_QL8i1);

        $_QLfol = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action=$_Jj8iQ, Member_id=$_IfLJj";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_QLJfI = mysql_error($_QLttI);
        if($_QLJfI != "")
          $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";

        // **** apply groups_id to member_id START
        if( $_JjI8O["GroupsOption"] == 3 && $_JjI8O["groups_id"] > 0 ) {
          $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=$_JjI8O[groups_id], Member_id=$_IfLJj";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLJfI = mysql_error($_QLttI);
          if($_QLJfI != "")
            $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
        }
        // **** apply groups_id to member_id END

        // **** import groups names and create it if not exists START
        if( $_JjI8O["GroupsOption"] == 2 && count($_Jj6L1) > 0 ) {
          for($_JjOfi = 0; $_JjOfi<count($_Jj6L1); $_JjOfi++) {
            if(trim($_Jj6L1[$_JjOfi]) == "") continue;

            $_QLfol = "INSERT IGNORE INTO $_QljJi SET CreateDate=NOW(), Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            $_QLJfI = mysql_error($_QLttI);
            if($_QLJfI != "")
              $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
            if(mysql_affected_rows($_QLttI) > 0 ) {
              $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
              $_QLO0f=mysql_fetch_array($_QL8i1);
              $_IOjji = $_QLO0f[0];
              mysql_free_result($_QL8i1);
            } else {
              $_QLfol = "SELECT id FROM $_QljJi WHERE Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              $_QLJfI = mysql_error($_QLttI);
              if($_QLJfI != "")
                $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
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
              $_QLJfI = mysql_error($_QLttI);
              if($_QLJfI != "")
                $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
            }
          }

          $_Jj6L1 = array();
        }
        // **** import groups names and create it if not exists END

        if($_JjfiL && $_Jj8lt != "" && !$_JjtC8) {
          _LRCOC();
          _J0LAA("subscribeconfirm", $_IfLJj, $_I80Jo, $_Jj08l, $errors, $_I816i);
          if(count($_I816i) > 0)
             $_JIfo0 .= "<br />Error while sending opt in email ".join(" ", $_I816i);
          _LRCOC();
        }

      } // if(mysql_affected_rows($_QLttI) > 0 )
        else
        if($_QLfol != "" && $_JjI8O["GroupsOption"] > 0 && $_JjI8O["ExImportOption"] != 2 ) { // member exists, add to groups or update

          $_QLfol = "SELECT id FROM $_Jj6jC WHERE u_EMail="._LRAFO($_Jj8lt);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLJfI = mysql_error($_QLttI);
          if($_QLJfI != "")
            $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
          if(mysql_num_rows($_QL8i1) > 0) {
            $_QLO0f=mysql_fetch_array($_QL8i1);
            mysql_free_result($_QL8i1);
            $_IfLJj = $_QLO0f["id"];

            # Remove from all groups
            if($_JjI8O["RemoveRecipientFromGroups"])
              _J1JFE(array($_IfLJj), $_JjI8O["maillists_id"], $_IfJ66);

            if($_JjI8O["ExImportOption"] == 3){
              // Update recipient
              // no empty values
              for($_Qli6J=0; $_Qli6J<count($_jtiJJ); $_Qli6J++){
                if(strpos($_jtiJJ[$_Qli6J], '""') !== false || strpos($_jtiJJ[$_Qli6J], '"0000-00-00"') !== false) {
                  unset($_jtiJJ[$_Qli6J]);
                }
              }

              $_QLfol = "UPDATE $_Jj6jC SET ".join(", ", $_jtiJJ)." WHERE id=$_IfLJj";
              mysql_query($_QLfol, $_QLttI);
              $_QLJfI = mysql_error($_QLttI);
              if($_QLJfI != "")
                $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
            }

            // **** apply groups_id to member_id START
            if( $_JjI8O["GroupsOption"] == 3 && $_JjI8O["groups_id"] > 0 ) {
              $_QLfol = "SELECT * FROM $_IfJ66 WHERE groups_id=$_JjI8O[groups_id] AND Member_id=$_IfLJj";
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              if(mysql_num_rows($_QL8i1) == 0) {
                if($_QL8i1)
                  mysql_free_result($_QL8i1);
                $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=$_JjI8O[groups_id], Member_id=$_IfLJj";
                $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                $_QLJfI = mysql_error($_QLttI);
                if($_QLJfI != "")
                  $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
                $_JjI8O["LastImportRowCount"]++;
              } else
                if($_QL8i1)
                  mysql_free_result($_QL8i1);
            }
           // **** apply groups_id to member_id END

           // **** import groups names and create it if not exists START
           if( $_JjI8O["GroupsOption"] == 2 && isset($_Jj6L1) && count($_Jj6L1) > 0 ) {
             for($_JjOfi=0; $_JjOfi<count($_Jj6L1); $_JjOfi++) {
               if(trim($_Jj6L1[$_JjOfi]) == "") continue;

               $_QLfol = "INSERT IGNORE INTO $_QljJi SET CreateDate=NOW(), Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
               $_QL8i1 = mysql_query($_QLfol, $_QLttI);
               $_QLJfI = mysql_error($_QLttI);
               if($_QLJfI != "")
                 $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
               if(mysql_affected_rows($_QLttI) > 0 ) {
                 $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
                 $_QLO0f=mysql_fetch_array($_QL8i1);
                 $_IOjji = $_QLO0f[0];
                 mysql_free_result($_QL8i1);
               } else {
                 $_QLfol = "SELECT id FROM $_QljJi WHERE Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
                 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                 $_QLJfI = mysql_error($_QLttI);
                 if($_QLJfI != "")
                   $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
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
                   $_QLJfI = mysql_error($_QLttI);
                   if($_QLJfI != "")
                     $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
                   $_JjI8O["LastImportRowCount"]++;
                 } else
                   if($_QL8i1)
                     mysql_free_result($_QL8i1);
               }
             }
             $_Jj6L1 = array();
           }
           // **** import groups names and create it if not exists END

          } # if(mysql_num_rows($_QL8i1) > 0)

        } // if($_QLfol != "" && $_JjI8O["GroupsOption"] > 0 && $_JjI8O["ExImportOption"] != 2 )

        _LRCOC();
        $_JjI8O["LastStartPosition"] = $_Qli6J;
        $_JjI8O["LastFilePosition"] = ftell($_IJljf);
        if(feof($_IJljf)) {
          $_JjI8O["LastFilePosition"] = -1;
        }

        $_QLfol = "UPDATE $_ItfiJ SET LastRowCount=$_JjI8O[LastRowCount], LastImportRowCount=$_JjI8O[LastImportRowCount], LastStartPosition=$_JjI8O[LastStartPosition], LastFilePosition=$_JjI8O[LastFilePosition]";
        $_QLfol .= " WHERE id=$_JjI8O[id]";
        mysql_query($_QLfol, $_QLttI);

    } # for($_Qli6J=$_Iil6i; ($_Qli6J<$_Iil6i + $_JjI8O["ImportLines"]) && !feof($_IJljf); $_Qli6J++)

    fclose($_IJljf);

    if(!empty($_JjI8O["CreateErrorLog"]) && $_JjJJl){
      fclose($_JjJJl);
    }

    return true;
  }


  function _LL1P0($_JjI8O, $_I80Jo, $_Jjjf6, $_JjIQL, &$_JIfo0, &$_Jj1f1){
    global $_QLttI;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLo06;
    global $_ItfiJ, $_ItL8f;
    global $_I8I6o, $_I8jjj, $_IfJ66, $_I8jLt, $_I8Jti, $MailingListId;

    $_Jj1f1 = 0;

    $_Jj08l = $_Jjjf6;
    $_JjJJl = 0;

    $_Jj6jC = $_I80Jo["MaillistTableName"];
    $_I8jjj = $_I80Jo["StatisticsTableName"];
    $_QljJi = $_I80Jo["GroupsTableName"];
    $_IfJoo = $_I80Jo["FormsTableName"];
    $_IfJ66 = $_I80Jo["MailListToGroupsTableName"];
    $FormId = $_I80Jo["forms_id"];
    $_jjj8f = $_I80Jo["LocalBlocklistTableName"];
    $_Jj6f0 = $_I80Jo["LocalDomainBlocklistTableName"];

    $_I8I6o = $_I80Jo["MaillistTableName"];
    $MailingListId = $_I80Jo["id"];
    $_I8jLt = $_I80Jo["MailLogTableName"];
    $_I8Jti = $_I80Jo["EditTableName"];

    if($_JjI8O["LastImportDone"]){
       $_JjI8O["LastRowCount"] = 0;
       $_JjI8O["LastImportRowCount"] = 0;
       $_JjI8O["LastStartPosition"] = 0;
       $_JjI8O["LastFilePosition"] = 0;
       $_JjI8O["LastTablePosition"] = 0;
       $_JjI8O["LastImportDone"] = 0;
       $_JjI8O["IsMacFile"] = 0;
       $_JjI8O["LastImportRemoveMode"] = 0;

       if($_JjI8O["RemoveAllRecipients"])
        $_JjI8O["LastImportRemoveMode"] = 1;

       $_QLfol = "UPDATE $_ItfiJ SET LastImportDate=NOW(), LastImportDone=$_JjI8O[LastImportDone], LastImportRemoveMode=$_JjI8O[LastImportRemoveMode], IsMacFile=$_JjI8O[IsMacFile], LastRowCount=$_JjI8O[LastRowCount], LastImportRowCount=$_JjI8O[LastImportRowCount], LastStartPosition=$_JjI8O[LastStartPosition], LastFilePosition=$_JjI8O[LastFilePosition], LastTablePosition=$_JjI8O[LastTablePosition]";
       $_QLfol .= " WHERE id=$_JjI8O[id]";
       mysql_query($_QLfol, $_QLttI);
    }

    if($_JjI8O["LastImportRemoveMode"]){
      _LRCOC();

      $_JIfo0 .= "<br />Removing recipients...";

      while(true){
        $_QLfol = "SELECT `id` FROM `$_Jj6jC` LIMIT 0, 50";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_I8oIJ = array();
        $_IQ0Cj = array();
        while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
         $_I8oIJ[] = $_QLO0f["id"];
        }
        mysql_free_result($_QL8i1);
        if(count($_I8oIJ)){
         _LRCOC();
         _J1OQP($_I8oIJ, $_IQ0Cj);
         if(count($_IQ0Cj)){
            $_JIfo0 .= "<br />".join("<br />", $_IQ0Cj);
            return false;
           }
        } else{
          $_JIfo0 .= "<br />Removing recipients done.";
          $_JjI8O["LastImportRemoveMode"] = 0;
          $_QLfol = "UPDATE `$_ItfiJ` SET `LastImportRemoveMode`=$_JjI8O[LastImportRemoveMode]";
          $_QLfol .= " WHERE `id`=$_JjI8O[id]";
          mysql_query($_QLfol, $_QLttI);
          break;
        }
      }
    }

    $_Jj68f = _JOLQE("ECGListCheck");

    $_QlOjt = 0;

    $_Itfj8 = "";
    if($_JjI8O["DBType"] == "MSSQL" && !function_exists("mssql_connect")){
       $_JIfo0 .= "<br /><br />Error: MS SQL server support not installed.";
       return false;
    }
    $_ItCCj = _L0COO($_JjI8O, $errors, $_Itfj8, $_JjI8O["DBType"], $_JjI8O["IsUTF8"]);

    if(!$_ItCCj){
       $_JIfo0 .= "<br /><br />Error while connecting to database ".$_Itfj8;
       mysql_select_db (MySQLDBName, $_QLttI);
       return false;
    }

    if($_JjI8O["SQLExtImport"] != 2){
      if($_JjI8O["DBType"] == "MYSQL")
        $_QLfol = "SELECT COUNT(*) FROM `$_JjI8O[SQLTableName]`";
        else
        $_QLfol = "SELECT COUNT(*) FROM $_JjI8O[SQLTableName]";
      $_QL8i1 = db_query($_QLfol, $_ItCCj, $_JjI8O["DBType"]);
      $_If61l = db_fetch_row($_QL8i1, $_JjI8O["DBType"]);
      db_free_result($_QL8i1, $_JjI8O["DBType"]);
      $_If61l = $_If61l[0];
    } else{
      $_QLfol = $_JjI8O["SQLImportSQLQuery"];
      $_QL8i1 = db_query($_QLfol, $_ItCCj, $_JjI8O["DBType"]);
      $_If61l = db_num_rows($_QL8i1, $_JjI8O["DBType"]);
      db_free_result($_QL8i1, $_JjI8O["DBType"]);
    }
    $_JjI8O["LastRowCount"] = $_If61l;

    if($_JjI8O["DBType"] == "MYSQL")
      $_QLfol = "SELECT * FROM `$_JjI8O[SQLTableName]`";
      else
      $_QLfol = "SELECT * FROM $_JjI8O[SQLTableName]";
    if($_JjI8O["SQLExtImport"] == 2)
      $_QLfol = $_JjI8O["SQLImportSQLQuery"];

    $_JjOL0 = db_query($_QLfol, $_ItCCj, $_JjI8O["DBType"]);

    $_JjoiC = false;
    // spring zur fileposition
    if($_JjI8O["LastTablePosition"] != 0) {
        if($_JjI8O["CreateErrorLog"]){
          $_JjJJl = @fopen($_JjIQL, "a");
        }

        if($_JjI8O["LastTablePosition"] > 0) {
          $_JjoiC = !@db_data_seek($_JjOL0, $_JjI8O["LastTablePosition"], $_JjI8O["DBType"]);
        }

        // sind wir fertig?
        if($_JjI8O["LastTablePosition"] == -1 || $_JjoiC  ) {
          if($_ItCCj != $_QLttI) {
            db_close($_ItCCj, $_JjI8O["DBType"]);
          } else{
            mysql_select_db (MySQLDBName, $_QLttI);
          }

          $_QLfol = "UPDATE $_ItfiJ SET LastImportDone=1";
          $_QLfol .= " WHERE id=$_JjI8O[id]";
          mysql_query($_QLfol, $_QLttI);

          $_JIfo0 .= "<br /><br />Importing from database done.";

          return true;
        }

      }
      else { // noch nie angefasst
        if($_JjI8O["CreateErrorLog"]){
          $_JjJJl = @fopen($_JjIQL, "w");
        }
        $_QlOjt = 0;
        $_JjI8O["LastTablePosition"] = 0;
      }


    if($_JjI8O["LastStartPosition"] == 0 ) {
      $_Iil6i = $_QlOjt;
    } else {
      $_Iil6i = $_JjI8O["LastStartPosition"];
    }


    $_IOJoI = $_JjI8O["fields"];
    if(!is_array($_IOJoI)) {
       $_IOJoI = unserialize($_IOJoI);
       if($_IOJoI === false)
         $_IOJoI = array();
    }

    _LRCOC();


    // hier liest er die Zeilen
    for($_Qli6J=$_Iil6i; ($_Qli6J<$_Iil6i + $_JjI8O["ImportLines"]) && !$_JjoiC; $_Qli6J++) {

      $_Jj1f1++;

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
      $_IiLOj = db_fetch_array($_JjOL0, $_JjI8O["DBType"]);
      if($_IiLOj === false || $_IiLOj == null) {
        $_JjoiC = true;
        $_JjI8O["LastTablePosition"] = -1;
        $_QLfol = "UPDATE $_ItfiJ SET LastTablePosition=$_JjI8O[LastTablePosition], LastImportDate=NOW()";
        $_QLfol .= " WHERE id=$_JjI8O[id]";
        mysql_query($_QLfol, $_QLttI);
        continue;
      }

      // UTF8?
      if ( !$_JjI8O["IsUTF8"] ) {
         reset($_IiLOj);
         foreach ($_IiLOj as $key => $_QltJO) {
            $u = utf8_encode($_QltJO);
            if($u != "")
              $_IiLOj[$key] = $u;
         }
      }

      $_I0QjQ = $_IiLOj;

      $_Jj6L1 = array();
      if( $_JjI8O["GroupsOption"] == 2 ) {
        if($_JjI8O["ImportGroupField"] < count($_I0QjQ)) {
           $_Jj6L1 = $_I0QjQ[$_JjI8O["ImportGroupField"]];
           $_Jjf0J = ",";
           $_Jj6L1 = explode($_Jjf0J, $_Jj6L1);
           for($_JjfO0=0; $_JjfO0<count($_Jj6L1); $_JjfO0++)
             $_Jj6L1[$_JjfO0] = str_replace(",", "", $_Jj6L1[$_JjfO0]);
        }
      }


      $_QLfol = "INSERT IGNORE INTO $_Jj6jC SET DateOfSubscription=NOW()";

      $_JjfiL = false;
      $_Jj8iQ = "";
      if($_JjI8O["SendOptInEMail"]) {
        $_Jj8iQ = "'OptInConfirmationPending'";
        $_QLfol .= ", SubscriptionStatus=$_Jj8iQ";
        $_JjfiL = true;
        }
        else {
          $_Jj8iQ = "'Subscribed'";
          $_QLfol .= ", SubscriptionStatus=$_Jj8iQ, DateOfOptInConfirmation=NOW(), IPOnSubscription='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."'";
        }


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
        if($_JjI8O["RemoveSpaces"]) {
          if(is_string($_IOCjL))
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
            if($_JjI8O["BirthdayDateFormat"] == "dd.mm.yyyy") {
              $_jJQOo = explode(".", $_IOCjL);
              while(count($_jJQOo) < 3)
                 $_jJQOo[] = "f";
              $_jjOlo = $_jJQOo[0];
              $_jJIft = $_jJQOo[1];
              $_jJjQi = $_jJQOo[2];
            } else
              if($_JjI8O["BirthdayDateFormat"] == "yyyy-mm-dd") {
               $_jJQOo = explode("-", $_IOCjL);
               while(count($_jJQOo) < 3)
                  $_jJQOo[] = "f";
               $_jjOlo = $_jJQOo[2];
               $_jJIft = $_jJQOo[1];
               $_jJjQi = $_jJQOo[0];
              } else
                if($_JjI8O["BirthdayDateFormat"] == "mm-dd-yyyy") {
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
            _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressIncorrect"], $_IOCjL), $_JjI8O, $_JjJJl );
            break;
          } else {
            if( $_IOLil == "u_EMail" ){
              $_IOCjL = _L86JE($_IOCjL);
              $_Jj8lt = $_IOCjL;
            }  
          }

          if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){
            $_jtiJJ[] = " `$_IOLil`="._LRAFO(htmlspecialchars($_IOCjL, ENT_COMPAT, $_QLo06));
          } else {
            $_jtiJJ[] = " `$_IOLil`="._LRAFO($_IOCjL);
          }
        }
      } // foreach($_IOJoI as $_IOLil => $_IOCjL)

      if($_QLfol != "" && $_Jj8lt == "") {
         _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorNoEMailAddress"], $_Jj8lt), $_JjI8O, $_JjJJl );
         $_QLfol = "";
      }

      if($_QLfol != "")
        $_QLfol .= ", ".join(", ", $_jtiJJ);


      $_JjtC8 = false;
      if($_QLfol != "") {

        $_JjOjI = substr($_Jj8lt, strpos($_Jj8lt, '@') + 1);

        if(_J18DA($_Jj8lt)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalBlockList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

        if(!$_JjtC8 && _J18FQ($_Jj8lt, $_JjI8O["maillists_id"], $_jjj8f)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalBlockList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

        if(!$_JjtC8 && _J1PQO($_JjOjI)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalDomainBlockList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

        if(!$_JjtC8 && _J1P6D($_JjOjI, $_JjI8O["maillists_id"], $_Jj6f0)) {
          $_JjtC8 = true;
          _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalDomainBlockList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }

        if(!$_JjtC8 && $_Jj68f ) {
          $_I016j = _L6AJP($_Jj8lt);
          $_JjtC8 = (is_bool($_I016j) && $_I016j);
          if($_JjtC8)
            _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInECGList"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }
        
      }

      _LRCOC();
      if($_JjtC8 && !$_JjI8O["ImportBlockedRecipients"])
        $_QLfol = "";

      if($_QLfol != "") {
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_QLJfI = mysql_error($_QLttI);
        if($_QLJfI != "")
          $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
        if($_JjI8O["CreateErrorLog"] && $_JjJJl && mysql_affected_rows($_QLttI) == 0){
           _LLQRO( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorDuplicateEntry"], $_Jj8lt), $_JjI8O, $_JjJJl );
        }
      }
      if($_QLfol != "" && mysql_affected_rows($_QLttI) > 0 ) {
        $_JjI8O["LastImportRowCount"]++;

        // statistics
        $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
        $_QLO0f=mysql_fetch_array($_QL8i1);
        $_IfLJj = $_QLO0f[0];
        mysql_free_result($_QL8i1);

        $_QLfol = "INSERT INTO $_I8jjj SET ActionDate=NOW(), Action=$_Jj8iQ, Member_id=$_IfLJj";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_QLJfI = mysql_error($_QLttI);
        if($_QLJfI != "")
          $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";

        // **** apply groups_id to member_id START
        if( $_JjI8O["GroupsOption"] == 3 && $_JjI8O["groups_id"] > 0 ) {
          $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=$_JjI8O[groups_id], Member_id=$_IfLJj";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLJfI = mysql_error($_QLttI);
          if($_QLJfI != "")
            $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
        }
        // **** apply groups_id to member_id END

        // **** import groups names and create it if not exists START
        if( $_JjI8O["GroupsOption"] == 2 && count($_Jj6L1) > 0 ) {
          for($_JjOfi = 0; $_JjOfi<count($_Jj6L1); $_JjOfi++) {
            if(trim($_Jj6L1[$_JjOfi]) == "") continue;
            $_QLfol = "INSERT IGNORE INTO $_QljJi SET CreateDate=NOW(), Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            $_QLJfI = mysql_error($_QLttI);
            if($_QLJfI != "")
              $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
            if(mysql_affected_rows($_QLttI) > 0 ) {
              $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
              $_QLO0f=mysql_fetch_array($_QL8i1);
              $_IOjji = $_QLO0f[0];
              mysql_free_result($_QL8i1);
            } else {
              $_QLfol = "SELECT id FROM $_QljJi WHERE Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              $_QLJfI = mysql_error($_QLttI);
              if($_QLJfI != "")
                $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
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
              $_QLJfI = mysql_error($_QLttI);
              if($_QLJfI != "")
                $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
            }
          }
          $_Jj6L1 = array();
        }
        // **** import groups names and create it if not exists END

        if($_JjfiL && $_Jj8lt != "" && !$_JjtC8) {
          _LRCOC();
          _J0LAA("subscribeconfirm", $_IfLJj, $_I80Jo, $_Jj08l, $errors, $_I816i);
          if(count($_I816i) > 0)
             $_JIfo0 .= "<br />Error while sending opt in email ".join(" ", $_I816i);
          _LRCOC();
        }

      } // if(mysql_affected_rows($_QLttI) > 0 )
        else
        if($_QLfol != "" && $_JjI8O["GroupsOption"] > 0 && $_JjI8O["ExImportOption"] != 2 ) { // member exists, add to groups or update
          $_QLfol = "SELECT id FROM $_Jj6jC WHERE u_EMail="._LRAFO($_Jj8lt);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLJfI = mysql_error($_QLttI);
          if($_QLJfI != "")
            $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
          if(mysql_num_rows($_QL8i1) > 0) {
            $_QLO0f=mysql_fetch_array($_QL8i1);
            mysql_free_result($_QL8i1);
            $_IfLJj = $_QLO0f["id"];

            # Remove from all groups
            if($_JjI8O["RemoveRecipientFromGroups"])
              _J1JFE(array($_IfLJj), $_JjI8O["maillists_id"], $_IfJ66);

            if($_JjI8O["ExImportOption"] == 3){
              // Update recipient
              // no empty values
              for($_Qli6J=0; $_Qli6J<count($_jtiJJ); $_Qli6J++){
                if(strpos($_jtiJJ[$_Qli6J], '""') !== false || strpos($_jtiJJ[$_Qli6J], '"0000-00-00"') !== false) {
                  unset($_jtiJJ[$_Qli6J]);
                }
              }
              $_QLfol = "UPDATE $_Jj6jC SET ".join(", ", $_jtiJJ)." WHERE id=$_IfLJj";
              mysql_query($_QLfol, $_QLttI);
              $_QLJfI = mysql_error($_QLttI);
              if($_QLJfI != "")
                $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
            }

            // **** apply groups_id to member_id START
            if( $_JjI8O["GroupsOption"] == 3 && $_JjI8O["groups_id"] > 0 ) {
              $_QLfol = "SELECT * FROM $_IfJ66 WHERE groups_id=$_JjI8O[groups_id] AND Member_id=$_IfLJj";
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              if(mysql_num_rows($_QL8i1) == 0) {
                if($_QL8i1)
                  mysql_free_result($_QL8i1);
                $_QLfol = "INSERT INTO $_IfJ66 SET groups_id=$_JjI8O[groups_id], Member_id=$_IfLJj";
                $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                $_QLJfI = mysql_error($_QLttI);
                if($_QLJfI != "")
                  $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
                $_JjI8O["LastImportRowCount"]++;
              } else
                if($_QL8i1)
                  mysql_free_result($_QL8i1);
            }
           // **** apply groups_id to member_id END

           // **** import groups names and create it if not exists START
           if( $_JjI8O["GroupsOption"] == 2 && isset($_Jj6L1) && count($_Jj6L1) > 0 ) {
             for($_JjOfi=0; $_JjOfi<count($_Jj6L1); $_JjOfi++) {
               if(trim($_Jj6L1[$_JjOfi]) == "") continue;
               $_QLfol = "INSERT IGNORE INTO $_QljJi SET CreateDate=NOW(), Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
               $_QL8i1 = mysql_query($_QLfol, $_QLttI);
               $_QLJfI = mysql_error($_QLttI);
               if($_QLJfI != "")
                 $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
               if(mysql_affected_rows($_QLttI) > 0 ) {
                 $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
                 $_QLO0f=mysql_fetch_array($_QL8i1);
                 $_IOjji = $_QLO0f[0];
                 mysql_free_result($_QL8i1);
               } else {
                 $_QLfol = "SELECT id FROM $_QljJi WHERE Name="._LRAFO(trim($_Jj6L1[$_JjOfi]));
                 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                 $_QLJfI = mysql_error($_QLttI);
                 if($_QLJfI != "")
                   $_JIfo0 .= "<br />SQL ERROR ".$_QLJfI." for $_QLfol";
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
                   $_JjI8O["LastImportRowCount"]++;
                 } else
                   if($_QL8i1)
                     mysql_free_result($_QL8i1);
               }
             }
             $_Jj6L1 = array();
           }
           // **** import groups names and create it if not exists END

          } # if(mysql_num_rows($_QL8i1) > 0)

        } // if($_QLfol != "" && $_JjI8O["GroupsOption"] > 0 && $_JjI8O["ExImportOption"] != 2 )



        _LRCOC();
        $_JjI8O["LastStartPosition"] = $_Qli6J;
        $_JjI8O["LastTablePosition"] = $_Qli6J;
        if($_JjoiC) {
          $_JjI8O["LastTablePosition"] = -1;
        }

        $_QLfol = "UPDATE $_ItfiJ SET LastRowCount=$_JjI8O[LastRowCount], LastImportRowCount=$_JjI8O[LastImportRowCount], LastStartPosition=$_JjI8O[LastStartPosition], LastTablePosition=$_JjI8O[LastTablePosition]";
        $_QLfol .= " WHERE id=$_JjI8O[id]";
        mysql_query($_QLfol, $_QLttI);

    } # for($_Qli6J=$_Iil6i; ($_Qli6J<$_Iil6i + $_JjI8O["ImportLines"]) && !$_JjoiC; $_Qli6J++) {


    if(!empty($_JjI8O["CreateErrorLog"]) && $_JjJJl){
      fclose($_JjJJl);
    }

    if($_ItCCj != $_QLttI) {
      db_close($_ItCCj, $_JjI8O["DBType"]);
      mysql_select_db (MySQLDBName, $_QLttI);
    } else{
      mysql_select_db (MySQLDBName, $_QLttI);
    }

    return true;
  }

  function _L0COO($_JjI8O, &$errors, &$_Itfj8, $_ItOIt, $_ItCiQ) {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;
    $_ItCCj = db_connect ($_JjI8O['SQLServerName'], $_JjI8O['SQLUsername'], $_JjI8O['SQLPassword'], $_ItOIt, $_JjI8O['SQLDatabase'], $_ItCiQ);
    if ($_ItCCj == 0) {
       $_Itfj8 = ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br/>".db_error($_ItCCj, $_ItOIt));
       @db_close($_ItCCj, $_ItOIt);
       return $_ItCCj;
    }

    if ($_ItCCj && !db_select_db ($_JjI8O['SQLDatabase'], $_ItCCj, $_ItOIt)) {
      $_Itfj8 = ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".$_JjI8O['SQLDatabase']."<br/>".db_error($_ItCCj, $_ItOIt));
      if($_ItCCj != $_QLttI) {
        db_close($_ItCCj, $_ItOIt);
      } else{
        mysql_select_db (MySQLDBName, $_QLttI);
      }
      $_ItCCj = 0;
    }
    return $_ItCCj;
  }

  function _LLQRO($_IO08l, $_JjI8O, $_JjJJl) {
   global $_QLl1Q;
   if($_JjI8O["CreateErrorLog"] && $_JjJJl){
     fwrite($_JjJJl, $_IO08l.$_QLl1Q);
   }
  }

?>

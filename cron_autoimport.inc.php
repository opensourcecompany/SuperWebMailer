<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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

  function _O6D8Q(&$_j6O8O){
    global $_Q61I1;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $resourcestrings, $_Q8f1L, $_Q60QL, $_Q880O, $_QLo0Q;
    global $_jji0C;
    global $_I0f8O;

    $_j6O8O = "Auto importing recipients starts...<br />";
    $_j6CCl = 0;

    $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE UserType='Admin' AND IsActive>0";
    $_Q8to6 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q8to6 && $_Q6Q1C = mysql_fetch_assoc($_Q8to6) ) {
      _OPQ6J();
      $UserId = $_Q6Q1C["id"];
      $OwnerUserId = 0;
      $Username = $_Q6Q1C["Username"];
      $UserType = $_Q6Q1C["UserType"];
      $AccountType = $_Q6Q1C["AccountType"];
      $INTERFACE_THEMESID = $_Q6Q1C["ThemesId"];
      $INTERFACE_LANGUAGE = $_Q6Q1C["Language"];

      _OP10J($INTERFACE_LANGUAGE);

      _LQLRQ($INTERFACE_LANGUAGE);

      $_QJlJ0 = "SELECT Theme FROM $_Q880O WHERE id=$INTERFACE_THEMESID";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_Q8OiJ[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);

      $_QJlJ0 = "SELECT $_I0f8O.* ";

      $_QJlJ0 .= "FROM $_I0f8O";

      $_QJlJ0 .= " WHERE ($_I0f8O.IsActive=1 AND ";
      $_QJlJ0 .= " ( (CURRENT_TIME() > $_I0f8O.ImportTime AND IF(LastImportDate <> '0000-00-00 00:00:00', TO_DAYS(NOW()) <> TO_DAYS(LastImportDate), 1) ) OR $_I0f8O.LastImportDone = 0) )";
      $_QJlJ0 .= " AND ( ";

      $_QJlJ0 .= " ( $_I0f8O.ImportScheduler = 'ImportAtDay' AND ($_I0f8O.ImportDayNames='every day' OR $_I0f8O.ImportDayNames=CAST(WEEKDAY(NOW()) AS CHAR) ) ) ";

      $_QJlJ0 .= " OR ";

      $_QJlJ0 .= " ( $_I0f8O.ImportScheduler = 'ImportOnceAMonth' AND CAST(DAYOFMONTH(NOW()) AS CHAR) = $_I0f8O.ImportMonthDay ) ";

      $_QJlJ0 .= " OR ";

      $_QJlJ0 .= " ( $_I0f8O.LastImportDone = 0 ) "; // not completly done

      $_QJlJ0 .= " )";

      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);

      if(mysql_error($_Q61I1) != "")
        $_j6O8O .= "MySQL error while selecting data: ".mysql_error($_Q61I1);

      while($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
         _OPQ6J();
         $_j6O8O .= "<br /><br />Checking $_Q8OiJ[Name]...";

         $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$_Q8OiJ[maillists_id]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_Ql00j = mysql_fetch_assoc($_Q60l1);
         $_QLI8o = $_Ql00j["FormsTableName"];
         $FormId = $_Ql00j["forms_id"];
         mysql_free_result($_Q60l1);

         $_j6ioL = array();
         if($_Q8OiJ["SendOptInEMail"] ) {
           $_QJlJ0 = "SELECT $_QLI8o.*, $_QLo0Q.*, $_Q880O.Theme FROM $_QLI8o LEFT JOIN $_QLo0Q ON $_QLo0Q.id=$_QLI8o.messages_id LEFT JOIN $_Q880O ON $_Q880O.id=$_QLI8o.ThemesId WHERE $_QLI8o.id=$FormId";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           $_j6ioL = mysql_fetch_assoc($_Q60l1);
           mysql_free_result($_Q60l1);
           $_j6ioL["MailingListId"] = $_Ql00j["id"];
           $_j6ioL["FormId"] = $FormId;
         }

         $_j6il6 = 0;
         if($_Q8OiJ["ImportOption"] == 'ImportCSV') {
            if($_Q8OiJ["Separator"] == '\t')
               $_Q8OiJ["Separator"] = "\t";

            $_j6LLj = "autoimportfile_errors$_Q8OiJ[id].txt";
            $_j6ljC = $_jji0C.$_j6LLj;

            _O6DPE($_Q8OiJ, $_Ql00j, $_j6ioL, $_j6ljC, $_j6O8O, $_j6il6);
            $_j6O8O .= "<br /><br />$_j6il6 lines processed.";
            $_j6CCl += $_j6il6;
         }

         if($_Q8OiJ["ImportOption"] == 'ImportDB') {
            if(!empty($_Q8OiJ["SQLServerName"]) && $_Q8OiJ["DBType"] == "MSSQL")
              $_Q8OiJ["SQLServerName"] = str_replace("/", "\\", $_Q8OiJ["SQLServerName"]);
            $_j6LLj = "autoimportdb_errors$_Q8OiJ[id].txt";
            $_j6ljC = $_jji0C.$_j6LLj;
            _O6DDF($_Q8OiJ, $_Ql00j, $_j6ioL, $_j6ljC, $_j6O8O, $_j6il6);
            $_j6O8O .= "<br /><br />$_j6il6 records processed.";
            $_j6CCl += $_j6il6;
         }


      }


      mysql_free_result($_Q8Oj8);
    }
    if($_Q8to6)
      mysql_free_result($_Q8to6);


    $_j6O8O .= "<br /><br />Auto importing done.";
    if($_j6CCl)
      return true;
      else
      return -1;
  }

  function _O6DPE($_jf00Q, $_Ql00j, $_jf0ii, $_j6ljC, &$_j6O8O, &$_j6il6){
    global $_Q61I1;
    global $resourcestrings, $INTERFACE_LANGUAGE;
    global $_I0f8O, $_I0lQJ;
    global $_QlQC8, $_QlIf6, $_QLI68, $_QljIQ, $_Qljli, $MailingListId;

    $_j6il6 = 0;
    $_j6ioL = $_jf0ii;
    $_jf0il = 0;

    $_jf1jt = $_Ql00j["MaillistTableName"];
    $_QlIf6 = $_Ql00j["StatisticsTableName"];
    $_Q6t6j = $_Ql00j["GroupsTableName"];
    $_QLI8o = $_Ql00j["FormsTableName"];
    $_QLI68 = $_Ql00j["MailListToGroupsTableName"];
    $FormId = $_Ql00j["forms_id"];
    $_ItCCo = $_Ql00j["LocalBlocklistTableName"];
    $_jf1J1 = $_Ql00j["LocalDomainBlocklistTableName"];

    $_QlQC8 = $_Ql00j["MaillistTableName"];
    $MailingListId = $_Ql00j["id"];
    $_QljIQ = $_Ql00j["MailLogTableName"];
    $_Qljli = $_Ql00j["EditTableName"];

    if($_jf00Q["LastImportDone"]){
       $_jf00Q["LastRowCount"] = 0;
       $_jf00Q["LastImportRowCount"] = 0;
       $_jf00Q["LastStartPosition"] = 0;
       $_jf00Q["LastFilePosition"] = 0;
       $_jf00Q["LastTablePosition"] = 0;
       $_jf00Q["LastImportDone"] = 0;
       $_jf00Q["IsMacFile"] = 0;
       $_jf00Q["LastImportRemoveMode"] = 0;

       $_Q6lfJ = fopen($_I0lQJ.$_jf00Q["ImportFilename"], "r");
       if(!$_Q6lfJ){
         $_j6O8O .= "<br />Can't open file ".$_I0lQJ.$_jf00Q["ImportFilename"];
         return false;
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
       fclose($_Q6lfJ);
       $_jf00Q["IsMacFile"] = $_I1816;

       if($_jf00Q["RemoveAllRecipients"])
        $_jf00Q["LastImportRemoveMode"] = 1;


       $_QJlJ0 = "UPDATE $_I0f8O SET LastImportDate=NOW(), LastImportDone=$_jf00Q[LastImportDone], LastImportRemoveMode=$_jf00Q[LastImportRemoveMode], IsMacFile=$_jf00Q[IsMacFile], LastRowCount=$_jf00Q[LastRowCount], LastImportRowCount=$_jf00Q[LastImportRowCount], LastStartPosition=$_jf00Q[LastStartPosition], LastFilePosition=$_jf00Q[LastFilePosition], LastTablePosition=$_jf00Q[LastTablePosition]";
       $_QJlJ0 .= " WHERE id=$_jf00Q[id]";
       mysql_query($_QJlJ0, $_Q61I1);
    }


    if($_jf00Q["LastImportRemoveMode"]){
      _OPQ6J();

      $_j6O8O .= "<br />Removing recipients...";

      while(true){
        $_QJlJ0 = "SELECT `id` FROM `$_jf1jt` LIMIT 0, 50";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        $_QltCO = array();
        $_QtIiC = array();
        while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
         $_QltCO[] = $_Q6Q1C["id"];
        }
        mysql_free_result($_Q60l1);
        if(count($_QltCO)){
         _OPQ6J();
         _L10CL($_QltCO, $_QtIiC);
         if(count($_QtIiC)){
            $_j6O8O .= "<br />".join("<br />", $_QtIiC);
            return false;
           }
        } else{
          $_j6O8O .= "<br />Removing recipients done.";
          $_jf00Q["LastImportRemoveMode"] = 0;
          $_QJlJ0 = "UPDATE `$_I0f8O` SET `LastImportRemoveMode`=$_jf00Q[LastImportRemoveMode]";
          $_QJlJ0 .= " WHERE `id`=$_jf00Q[id]";
          mysql_query($_QJlJ0, $_Q61I1);
          break;
        }
      }
    }

    if($_jf00Q["IsMacFile"])
       ini_set('auto_detect_line_endings', TRUE);

    $_jf1Lf = _LQDLR("ECGListCheck");
    $_jf0il = 0;

    $_QCC8C = fopen($_I0lQJ.$_jf00Q["ImportFilename"], "r");
    $_QLjff = fstat($_QCC8C);
    $_Q6i6i = 0;

    // spring zur fileposition
    if($_jf00Q["LastFilePosition"] != 0) {
        if($_jf00Q["CreateErrorLog"]){
          $_jf0il = @fopen($_j6ljC, "a");
        }

        if($_jf00Q["LastFilePosition"] != 0)
           fseek($_QCC8C, $_jf00Q["LastFilePosition"]);

        // sind wir fertig?
        if($_jf00Q["LastFilePosition"] == -1 || feof($_QCC8C) || ftell($_QCC8C) >= $_QLjff["size"] ) {
          fclose($_QCC8C);
          if( $_jf00Q["RemoveFile"] ) {
            if( !@unlink($_I0lQJ.$_jf00Q["ImportFilename"]) ) {
               $_j6O8O .= "<br />Can'remove file ".$_I0lQJ.$_jf00Q["ImportFilename"];
            }
          }

          $_QJlJ0 = "UPDATE $_I0f8O SET LastImportDone=1";
          $_QJlJ0 .= " WHERE id=$_jf00Q[id]";
          mysql_query($_QJlJ0, $_Q61I1);

          $_j6O8O .= "<br /><br />Importing file $_I0lQJ$_jf00Q[ImportFilename] done.";

          return true;
        }

      }
      else { // noch nie angefasst
        if($_jf00Q["CreateErrorLog"]){
          $_jf0il = @fopen($_j6ljC, "w");
        }

        // UTF8 BOM test
        $_I1O0j = 'ï»¿';
        $_IJ1QJ = fread($_QCC8C, strlen($_I1O0j));
        if($_IJ1QJ != $_I1O0j)
          fseek($_QCC8C, 0);

        // Zeile 1 weg?
        $_Q6i6i = 0;
        if($_jf00Q["Header1Line"]) {
           $_IJ1QJ = fgets($_QCC8C, 4096);
           $_Q6i6i = 1;
        }
      }

    if($_jf00Q["LastStartPosition"] == 0 ) {
      $_IJQQI = $_Q6i6i;
    } else {
      $_IJQQI = $_jf00Q["LastStartPosition"];
    }

    $_I16jJ = $_jf00Q["fields"];
    if(!is_array($_I16jJ)) {
       $_I16jJ = @unserialize($_I16jJ);
       if($_I16jJ === false)
         $_I16jJ = array();
    }

    _OPQ6J();

    // hier liest er die Zeilen
    for($_Q6llo=$_IJQQI; ($_Q6llo<$_IJQQI + $_jf00Q["ImportLines"]) && !feof($_QCC8C); $_Q6llo++) {

      $_j6il6++;
      // reset errros
      if(isset($errors) && count($errors) > 0) {
        unset($errors);
      }
      if( !isset($errors) )
        $errors = array();

      if(isset($_Ql1O8) && count($_Ql1O8) > 0) {
        unset($_Ql1O8);
        $_Ql1O8 = array();
      }
      if( !isset($_Ql1O8) )
        $_Ql1O8 = array();
      //

      _OPQ6J();
      $_jf00Q["LastRowCount"]++;
      $_IJ1QJ = fgets($_QCC8C, 4096);
      // UTF8?
      if ( !$_jf00Q["IsUTF8"] ) {
         $_QfoQo = utf8_encode($_IJ1QJ);
         if($_QfoQo != "")
           $_IJ1QJ = $_QfoQo;
      }

      $_Qf1i1 = explode($_jf00Q["Separator"], $_IJ1QJ);

      $_jfQio = array();
      if( $_jf00Q["GroupsOption"] == 2 ) {
        if($_jf00Q["ImportGroupField"] < count($_Qf1i1)) {
           $_jfQio = $_Qf1i1[$_jf00Q["ImportGroupField"]];
           $_jfQLi = ",";
           if($_jf00Q["Separator"] == ",")
             $_jfQLi = ";";
           $_jfQio = explode($_jfQLi, $_jfQio);
           for($_jfI01=0; $_jfI01<count($_jfQio); $_jfI01++)
             $_jfQio[$_jfI01] = str_replace(",", "", $_jfQio[$_jfI01]);
        }
      }

      $_QJlJ0 = "INSERT IGNORE INTO $_jf1jt SET DateOfSubscription=NOW()";

      $_jfIfl = false;
      $_jfI81 = "";
      if($_jf00Q["SendOptInEMail"]) {
        $_jfI81 = "'OptInConfirmationPending'";
        $_QJlJ0 .= ", SubscriptionStatus=$_jfI81";
        $_jfIfl = true;
        }
        else {
          $_jfI81 = "'Subscribed'";
          $_QJlJ0 .= ", SubscriptionStatus=$_jfI81, DateOfOptInConfirmation=NOW(), IPOnSubscription='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."'";
        }

      reset($_I16jJ);
      $_jfIi1 = "";
      $_ILQL0 = array();
      foreach($_I16jJ as $_I1i8O => $_IJQOL) {
        if($_IJQOL < 0) {continue;}
        if(is_array($_IJQOL) ){
          continue;
         }
        if($_IJQOL < count($_Qf1i1))
           $_I1L81 = $_Qf1i1[$_IJQOL];
           else
           $_I1L81 = "";
        if($_jf00Q["RemoveQuotes"]) {
          $_I1L81 = str_replace('"', '', $_I1L81);
          $_I1L81 = str_replace("\'", '', $_I1L81);
          $_I1L81 = str_replace("`", '', $_I1L81);
          $_I1L81 = str_replace("´", '', $_I1L81);
        }
        if($_jf00Q["RemoveSpaces"]) {
          $_I1L81 = trim($_I1L81);
        }
        if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
          if($_I1i8O == "u_EMailFormat") {
            $_I1L81 = trim($_I1L81);
            if($_I1L81 != 'HTML' && $_I1L81 != 'PlainText' && $_I1L81 != 'Text')
              $_I1L81 = 'HTML';
              else
              if($_I1L81 == 'PlainText' || $_I1L81 == 'Text')
                $_I1L81 = 'PlainText';
          } // if($_I1i8O == "u_EMailFormat")
          if($_I1i8O == "u_Gender") {
            $_I1L81 = strtolower($_I1L81);
            $_I1L81 = trim($_I1L81);
            if($_I1L81 != "m" && $_I1L81 != "w") {
              $_I1L81 = substr($_I1L81, 0, 1);
              if($_I1L81 == "f") // female
                $_I1L81 = "w";
              if($_I1L81 != "m" && $_I1L81 != "w") {
                if($_I1L81 == "0")
                   $_I1L81 = "m";
                   else
                   if($_I1L81 == "1")
                      $_I1L81 = "w";
                      else
                       $_I1L81 = 'undefined';
              }
            }
          } // if($_I1i8O == "u_Gender")
          if($_I1i8O == "u_Birthday") {
            $_I1L81 = trim($_I1L81);
            if($_jf00Q["BirthdayDateFormat"] == "dd.mm.yyyy") {
              $_Io01I = explode(".", $_I1L81);
              while(count($_Io01I) < 3)
                 $_Io01I[] = "f";
              $_IOJ8I = $_Io01I[0];
              $_Io0t6 = $_Io01I[1];
              $_Io0l8 = $_Io01I[2];
            } else
              if($_jf00Q["BirthdayDateFormat"] == "yyyy-mm-dd") {
               $_Io01I = explode("-", $_I1L81);
               while(count($_Io01I) < 3)
                  $_Io01I[] = "f";
               $_IOJ8I = $_Io01I[2];
               $_Io0t6 = $_Io01I[1];
               $_Io0l8 = $_Io01I[0];
              } else
                if($_jf00Q["BirthdayDateFormat"] == "mm-dd-yyyy") {
                  $_Io01I = explode("-", $_I1L81);
                  while(count($_Io01I) < 3)
                     $_Io01I[] = "f";
                  $_IOJ8I = $_Io01I[1];
                  $_Io0t6 = $_Io01I[0];
                  $_Io0l8 = $_Io01I[2];
                }

            if(strlen($_Io0l8) == 2)
              $_Io0l8 = "19".$_Io0l8;
            if( ! (
                (intval($_IOJ8I) > 0 && intval($_IOJ8I) < 32) &&
                (intval($_Io0t6) > 0 && intval($_Io0t6) < 13)
                  )
              ) // error in date
              $_I1L81 = "0000-00-00";
              else
              $_I1L81 = "$_Io0l8-$_Io0t6-$_IOJ8I";

          } // if($_I1i8O == "u_Birthday")

          // integer
          if(
             $_I1i8O == "u_UserFieldInt1" ||
             $_I1i8O == "u_UserFieldInt2" ||
             $_I1i8O == "u_UserFieldInt3"
            )
              $_I1L81 = intval($_I1L81);

          // boolean
          if(
             $_I1i8O == "u_UserFieldBool1" ||
             $_I1i8O == "u_UserFieldBool2" ||
             $_I1i8O == "u_UserFieldBool3"
            ) {
              $_I1L81 = strtolower($_I1L81);
              if($_I1L81 == "true")
                $_I1L81 = 1;
                if($_I1L81 == "false")
                   $_I1L81 = 0;
              $_I1L81 = intval($_I1L81);
              if($_I1L81 < 0) $_I1L81 = 0;
              if($_I1L81 > 0) $_I1L81 = 1;
            }

          # no empty email addresses
          if($_I1i8O == "u_EMail" && (trim($_I1L81) == "" || !_OPAOJ($_I1L81)) ) {
            $_QJlJ0 = "";
            _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressIncorrect"], $_I1L81), $_jf00Q, $_jf0il);
            break;
          } else {
            if( $_I1i8O == "u_EMail" )
              $_jfIi1 = $_I1L81;
          }

          if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){
            $_ILQL0[] = " `$_I1i8O`="._OPQLR(htmlspecialchars($_I1L81, ENT_COMPAT, 'UTF-8'));
          } else {
            $_ILQL0[] = " `$_I1i8O`="._OPQLR($_I1L81);
          }
        }
      } // foreach($_I16jJ as $_I1i8O => $_I1L81)

      if($_QJlJ0 != "" && $_jfIi1 == "") {
         _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorNoEMailAddress"], $_jfIi1), $_jf00Q, $_jf0il);
         $_QJlJ0 = "";
      }

      if($_QJlJ0 != "")
        $_QJlJ0 .= ", ".join(", ", $_ILQL0);

      $_jfIl8 = false;
      if($_QJlJ0 != "") {

        $_jfjC6 = substr($_jfIi1, strpos($_jfIi1, '@') + 1);
        _OPQ6J();

        if(_L0FRD($_jfIi1)) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalBlockList"], $_jfIi1), $_jf00Q, $_jf0il);
        }

        if(!$_jfIl8 && _L101P($_jfIi1, $_jf00Q["maillists_id"], $_ItCCo)) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalBlockList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

        if(!$_jfIl8 && _L1ROL($_jfjC6)) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalDomainBlockList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

        if(!$_jfIl8 && _L1RDP($_jfjC6, $_jf00Q["maillists_id"], $_jf1J1)) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalDomainBlockList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

        if(!$_jfIl8 && ($_jf1Lf && _OC0DR($_jfIi1)) ) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInECGList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

      }

      _OPQ6J();
      if($_jfIl8 && !$_jf00Q["ImportBlockedRecipients"])
        $_QJlJ0 = "";

      if($_QJlJ0 != "") {
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        $_QJCJi = mysql_error($_Q61I1);
        if($_QJCJi != "")
          $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
        if($_jf00Q["CreateErrorLog"] && $_jf0il && mysql_affected_rows($_Q61I1) == 0){
           _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorDuplicateEntry"], $_jfIi1), $_jf00Q, $_jf0il );
        }
      }

      _OPQ6J();
      if($_QJlJ0 != "" && mysql_affected_rows($_Q61I1) > 0 ) {
        $_jf00Q["LastImportRowCount"]++;

        // statistics
        $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
        $_Q6Q1C=mysql_fetch_array($_Q60l1);
        $_QLitI = $_Q6Q1C[0];
        mysql_free_result($_Q60l1);

        $_QJlJ0 = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action=$_jfI81, Member_id=$_QLitI";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        $_QJCJi = mysql_error($_Q61I1);
        if($_QJCJi != "")
          $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";

        // **** apply groups_id to member_id START
        if( $_jf00Q["GroupsOption"] == 3 && $_jf00Q["groups_id"] > 0 ) {
          $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_jf00Q[groups_id], Member_id=$_QLitI";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_QJCJi = mysql_error($_Q61I1);
          if($_QJCJi != "")
            $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
        }
        // **** apply groups_id to member_id END

        // **** import groups names and create it if not exists START
        if( $_jf00Q["GroupsOption"] == 2 && count($_jfQio) > 0 ) {
          for($_jfJj0 = 0; $_jfJj0<count($_jfQio); $_jfJj0++) {
            if(trim($_jfQio[$_jfJj0]) == "") continue;

            $_QJlJ0 = "INSERT IGNORE INTO $_Q6t6j SET CreateDate=NOW(), Name="._OPQLR(trim($_jfQio[$_jfJj0]));
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            $_QJCJi = mysql_error($_Q61I1);
            if($_QJCJi != "")
              $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
            if(mysql_affected_rows($_Q61I1) > 0 ) {
              $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
              $_Q6Q1C=mysql_fetch_array($_Q60l1);
              $_I1JQt = $_Q6Q1C[0];
              mysql_free_result($_Q60l1);
            } else {
              $_QJlJ0 = "SELECT id FROM $_Q6t6j WHERE Name="._OPQLR(trim($_jfQio[$_jfJj0]));
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              $_QJCJi = mysql_error($_Q61I1);
              if($_QJCJi != "")
                $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
              $_I1JQt = 0;
              if($_Q60l1) {
                 $_Q6Q1C=mysql_fetch_array($_Q60l1);
                 $_I1JQt = $_Q6Q1C[0];
                 mysql_free_result($_Q60l1);
              }
            }

            if($_I1JQt != 0) {
              $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_I1JQt, Member_id=$_QLitI";
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              $_QJCJi = mysql_error($_Q61I1);
              if($_QJCJi != "")
                $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
            }
          }

          $_jfQio = array();
        }
        // **** import groups names and create it if not exists END

        if($_jfIfl && $_jfIi1 != "" && !$_jfIl8) {
          _OPQ6J();
          _L0RLJ("subscribeconfirm", $_QLitI, $_Ql00j, $_j6ioL, $errors, $_Ql1O8);
          if(count($_Ql1O8) > 0)
             $_j6O8O .= "<br />Error while sending opt in email ".join(" ", $_Ql1O8);
          _OPQ6J();
        }

      } // if(mysql_affected_rows($_Q61I1) > 0 )
        else
        if($_QJlJ0 != "" && $_jf00Q["GroupsOption"] > 0 && $_jf00Q["ExImportOption"] != 2 ) { // member exists, add to groups or update

          $_QJlJ0 = "SELECT id FROM $_jf1jt WHERE u_EMail="._OPQLR($_jfIi1);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_QJCJi = mysql_error($_Q61I1);
          if($_QJCJi != "")
            $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
          if(mysql_num_rows($_Q60l1) > 0) {
            $_Q6Q1C=mysql_fetch_array($_Q60l1);
            mysql_free_result($_Q60l1);
            $_QLitI = $_Q6Q1C["id"];

            # Remove from all groups
            if($_jf00Q["RemoveRecipientFromGroups"])
              _L1LLB(array($_QLitI), $_jf00Q["maillists_id"], $_QLI68);

            if($_jf00Q["ExImportOption"] == 3){
              // Update recipient
              // no empty values
              for($_Q6llo=0; $_Q6llo<count($_ILQL0); $_Q6llo++){
                if(strpos($_ILQL0[$_Q6llo], '""') !== false || strpos($_ILQL0[$_Q6llo], '"0000-00-00"') !== false) {
                  unset($_ILQL0[$_Q6llo]);
                }
              }

              $_QJlJ0 = "UPDATE $_jf1jt SET ".join(", ", $_ILQL0)." WHERE id=$_QLitI";
              mysql_query($_QJlJ0, $_Q61I1);
              $_QJCJi = mysql_error($_Q61I1);
              if($_QJCJi != "")
                $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
            }

            // **** apply groups_id to member_id START
            if( $_jf00Q["GroupsOption"] == 3 && $_jf00Q["groups_id"] > 0 ) {
              $_QJlJ0 = "SELECT * FROM $_QLI68 WHERE groups_id=$_jf00Q[groups_id] AND Member_id=$_QLitI";
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              if(mysql_num_rows($_Q60l1) == 0) {
                if($_Q60l1)
                  mysql_free_result($_Q60l1);
                $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_jf00Q[groups_id], Member_id=$_QLitI";
                $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                $_QJCJi = mysql_error($_Q61I1);
                if($_QJCJi != "")
                  $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
                $_jf00Q["LastImportRowCount"]++;
              } else
                if($_Q60l1)
                  mysql_free_result($_Q60l1);
            }
           // **** apply groups_id to member_id END

           // **** import groups names and create it if not exists START
           if( $_jf00Q["GroupsOption"] == 2 && isset($_jfQio) && count($_jfQio) > 0 ) {
             for($_jfJj0=0; $_jfJj0<count($_jfQio); $_jfJj0++) {
               if(trim($_jfQio[$_jfJj0]) == "") continue;

               $_QJlJ0 = "INSERT IGNORE INTO $_Q6t6j SET CreateDate=NOW(), Name="._OPQLR(trim($_jfQio[$_jfJj0]));
               $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
               $_QJCJi = mysql_error($_Q61I1);
               if($_QJCJi != "")
                 $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
               if(mysql_affected_rows($_Q61I1) > 0 ) {
                 $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
                 $_Q6Q1C=mysql_fetch_array($_Q60l1);
                 $_I1JQt = $_Q6Q1C[0];
                 mysql_free_result($_Q60l1);
               } else {
                 $_QJlJ0 = "SELECT id FROM $_Q6t6j WHERE Name="._OPQLR(trim($_jfQio[$_jfJj0]));
                 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                 $_QJCJi = mysql_error($_Q61I1);
                 if($_QJCJi != "")
                   $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
                 $_I1JQt = 0;
                 if($_Q60l1) {
                    $_Q6Q1C=mysql_fetch_array($_Q60l1);
                    $_I1JQt = $_Q6Q1C[0];
                    mysql_free_result($_Q60l1);
                 }
               }

               if($_I1JQt != 0) {
                 $_QJlJ0 = "SELECT * FROM $_QLI68 WHERE groups_id=$_I1JQt AND Member_id=$_QLitI";
                 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                 if(mysql_num_rows($_Q60l1) == 0) {
                   if($_Q60l1)
                     mysql_free_result($_Q60l1);
                   $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_I1JQt, Member_id=$_QLitI";
                   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                   $_QJCJi = mysql_error($_Q61I1);
                   if($_QJCJi != "")
                     $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
                   $_jf00Q["LastImportRowCount"]++;
                 } else
                   if($_Q60l1)
                     mysql_free_result($_Q60l1);
               }
             }
             $_jfQio = array();
           }
           // **** import groups names and create it if not exists END

          } # if(mysql_num_rows($_Q60l1) > 0)

        } // if($_QJlJ0 != "" && $_jf00Q["GroupsOption"] > 0 && $_jf00Q["ExImportOption"] != 2 )

        _OPQ6J();
        $_jf00Q["LastStartPosition"] = $_Q6llo;
        $_jf00Q["LastFilePosition"] = ftell($_QCC8C);
        if(feof($_QCC8C)) {
          $_jf00Q["LastFilePosition"] = -1;
        }

        $_QJlJ0 = "UPDATE $_I0f8O SET LastRowCount=$_jf00Q[LastRowCount], LastImportRowCount=$_jf00Q[LastImportRowCount], LastStartPosition=$_jf00Q[LastStartPosition], LastFilePosition=$_jf00Q[LastFilePosition]";
        $_QJlJ0 .= " WHERE id=$_jf00Q[id]";
        mysql_query($_QJlJ0, $_Q61I1);

    } # for($_Q6llo=$_IJQQI; ($_Q6llo<$_IJQQI + $_jf00Q["ImportLines"]) && !feof($_QCC8C); $_Q6llo++)

    fclose($_QCC8C);

    if(!empty($_jf00Q["CreateErrorLog"]) && $_jf0il){
      fclose($_jf0il);
    }

    return true;
  }


  function _O6DDF($_jf00Q, $_Ql00j, $_jf0ii, $_j6ljC, &$_j6O8O, &$_j6il6){
    global $_Q61I1;
    global $resourcestrings, $INTERFACE_LANGUAGE;
    global $_I0f8O, $_I0lQJ;
    global $_QlQC8, $_QlIf6, $_QLI68, $_QljIQ, $_Qljli, $MailingListId;

    $_j6il6 = 0;

    $_j6ioL = $_jf0ii;
    $_jf0il = 0;

    $_jf1jt = $_Ql00j["MaillistTableName"];
    $_QlIf6 = $_Ql00j["StatisticsTableName"];
    $_Q6t6j = $_Ql00j["GroupsTableName"];
    $_QLI8o = $_Ql00j["FormsTableName"];
    $_QLI68 = $_Ql00j["MailListToGroupsTableName"];
    $FormId = $_Ql00j["forms_id"];
    $_ItCCo = $_Ql00j["LocalBlocklistTableName"];
    $_jf1J1 = $_Ql00j["LocalDomainBlocklistTableName"];

    $_QlQC8 = $_Ql00j["MaillistTableName"];
    $MailingListId = $_Ql00j["id"];
    $_QljIQ = $_Ql00j["MailLogTableName"];
    $_Qljli = $_Ql00j["EditTableName"];

    if($_jf00Q["LastImportDone"]){
       $_jf00Q["LastRowCount"] = 0;
       $_jf00Q["LastImportRowCount"] = 0;
       $_jf00Q["LastStartPosition"] = 0;
       $_jf00Q["LastFilePosition"] = 0;
       $_jf00Q["LastTablePosition"] = 0;
       $_jf00Q["LastImportDone"] = 0;
       $_jf00Q["IsMacFile"] = 0;
       $_jf00Q["LastImportRemoveMode"] = 0;

       if($_jf00Q["RemoveAllRecipients"])
        $_jf00Q["LastImportRemoveMode"] = 1;

       $_QJlJ0 = "UPDATE $_I0f8O SET LastImportDate=NOW(), LastImportDone=$_jf00Q[LastImportDone], LastImportRemoveMode=$_jf00Q[LastImportRemoveMode], IsMacFile=$_jf00Q[IsMacFile], LastRowCount=$_jf00Q[LastRowCount], LastImportRowCount=$_jf00Q[LastImportRowCount], LastStartPosition=$_jf00Q[LastStartPosition], LastFilePosition=$_jf00Q[LastFilePosition], LastTablePosition=$_jf00Q[LastTablePosition]";
       $_QJlJ0 .= " WHERE id=$_jf00Q[id]";
       mysql_query($_QJlJ0, $_Q61I1);
    }

    if($_jf00Q["LastImportRemoveMode"]){
      _OPQ6J();

      $_j6O8O .= "<br />Removing recipients...";

      while(true){
        $_QJlJ0 = "SELECT `id` FROM `$_jf1jt` LIMIT 0, 50";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        $_QltCO = array();
        $_QtIiC = array();
        while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
         $_QltCO[] = $_Q6Q1C["id"];
        }
        mysql_free_result($_Q60l1);
        if(count($_QltCO)){
         _OPQ6J();
         _L10CL($_QltCO, $_QtIiC);
         if(count($_QtIiC)){
            $_j6O8O .= "<br />".join("<br />", $_QtIiC);
            return false;
           }
        } else{
          $_j6O8O .= "<br />Removing recipients done.";
          $_jf00Q["LastImportRemoveMode"] = 0;
          $_QJlJ0 = "UPDATE `$_I0f8O` SET `LastImportRemoveMode`=$_jf00Q[LastImportRemoveMode]";
          $_QJlJ0 .= " WHERE `id`=$_jf00Q[id]";
          mysql_query($_QJlJ0, $_Q61I1);
          break;
        }
      }
    }

    $_jf1Lf = _LQDLR("ECGListCheck");

    $_Q6i6i = 0;

    $_I0600 = "";
    if($_jf00Q["DBType"] == "MSSQL" && !function_exists("mssql_connect")){
       $_j6O8O .= "<br /><br />Error: MS SQL server support not installed.";
       return false;
    }
    $_I0i1t = _OODBQ($_jf00Q, $errors, $_I0600, $_jf00Q["DBType"], $_jf00Q["IsUTF8"]);

    if(!$_I0i1t){
       $_j6O8O .= "<br /><br />Error while connecting to database ".$_I0600;
       mysql_select_db (MySQLDBName, $_Q61I1);
       return false;
    }

    if($_jf00Q["SQLExtImport"] != 2){
      $_QJlJ0 = "SELECT COUNT(*) FROM $_jf00Q[SQLTableName]";
      $_Q60l1 = db_query($_QJlJ0, $_I0i1t, $_jf00Q["DBType"]);
      $_QLjff = db_fetch_row($_Q60l1, $_jf00Q["DBType"]);
      db_free_result($_Q60l1, $_jf00Q["DBType"]);
      $_QLjff = $_QLjff[0];
    } else{
      $_QJlJ0 = $_jf00Q["SQLImportSQLQuery"];
      $_Q60l1 = db_query($_QJlJ0, $_I0i1t, $_jf00Q["DBType"]);
      $_QLjff = db_num_rows($_Q60l1, $_jf00Q["DBType"]);
      db_free_result($_Q60l1, $_jf00Q["DBType"]);
    }
    $_jf00Q["LastRowCount"] = $_QLjff;

    $_QJlJ0 = "SELECT * FROM $_jf00Q[SQLTableName]";
    if($_jf00Q["SQLExtImport"] == 2)
      $_QJlJ0 = $_jf00Q["SQLImportSQLQuery"];

    $_jfJ8L = db_query($_QJlJ0, $_I0i1t, $_jf00Q["DBType"]);

    $_jf6jf = false;
    // spring zur fileposition
    if($_jf00Q["LastTablePosition"] != 0) {
        if($_jf00Q["CreateErrorLog"]){
          $_jf0il = @fopen($_j6ljC, "a");
        }

        if($_jf00Q["LastTablePosition"] != 0) {
          $_jf6jf = !@db_data_seek($_jfJ8L, $_jf00Q["LastTablePosition"], $_jf00Q["DBType"]);
        }

        // sind wir fertig?
        if($_jf00Q["LastTablePosition"] == -1 || $_jf6jf  ) {
          if($_I0i1t != $_Q61I1) {
            db_close($_I0i1t, $_jf00Q["DBType"]);
          } else{
            mysql_select_db (MySQLDBName, $_Q61I1);
          }

          $_QJlJ0 = "UPDATE $_I0f8O SET LastImportDone=1";
          $_QJlJ0 .= " WHERE id=$_jf00Q[id]";
          mysql_query($_QJlJ0, $_Q61I1);

          $_j6O8O .= "<br /><br />Importing from database done.";

          return true;
        }

      }
      else { // noch nie angefasst
        if($_jf00Q["CreateErrorLog"]){
          $_jf0il = @fopen($_j6ljC, "w");
        }
        $_Q6i6i = 0;
        $_jf00Q["LastTablePosition"] = 0;
      }


    if($_jf00Q["LastStartPosition"] == 0 ) {
      $_IJQQI = $_Q6i6i;
    } else {
      $_IJQQI = $_jf00Q["LastStartPosition"];
    }


    $_I16jJ = $_jf00Q["fields"];
    if(!is_array($_I16jJ)) {
       $_I16jJ = unserialize($_I16jJ);
       if($_I16jJ === false)
         $_I16jJ = array();
    }

    _OPQ6J();


    // hier liest er die Zeilen
    for($_Q6llo=$_IJQQI; ($_Q6llo<$_IJQQI + $_jf00Q["ImportLines"]) && !$_jf6jf; $_Q6llo++) {

      $_j6il6++;

      // reset errros
      if(isset($errors) && count($errors) > 0) {
        unset($errors);
      }
      if( !isset($errors) )
        $errors = array();

      if(isset($_Ql1O8) && count($_Ql1O8) > 0) {
        unset($_Ql1O8);
        $_Ql1O8 = array();
      }
      if( !isset($_Ql1O8) )
        $_Ql1O8 = array();
      //

      _OPQ6J();
      $_IJ1QJ = db_fetch_array($_jfJ8L, $_jf00Q["DBType"]);
      if($_IJ1QJ === false) {
        $_jf6jf = true;
        $_jf00Q["LastTablePosition"] = -1;
        $_QJlJ0 = "UPDATE $_I0f8O SET LastTablePosition=$_jf00Q[LastTablePosition], LastImportDate=NOW()";
        $_QJlJ0 .= " WHERE id=$_jf00Q[id]";
        mysql_query($_QJlJ0, $_Q61I1);
        continue;
      }

      // UTF8?
      if ( !$_jf00Q["IsUTF8"] ) {
         reset($_IJ1QJ);
         foreach ($_IJ1QJ as $key => $_Q6ClO) {
            $_jffIC = utf8_encode($_Q6ClO);
            if($_jffIC != "")
              $_IJ1QJ[$key] = $_jffIC;
         }
      }

      $_Qf1i1 = $_IJ1QJ;

      $_jfQio = array();
      if( $_jf00Q["GroupsOption"] == 2 ) {
        if($_jf00Q["ImportGroupField"] < count($_Qf1i1)) {
           $_jfQio = $_Qf1i1[$_jf00Q["ImportGroupField"]];
           $_jfQLi = ",";
           $_jfQio = explode($_jfQLi, $_jfQio);
           for($_jfI01=0; $_jfI01<count($_jfQio); $_jfI01++)
             $_jfQio[$_jfI01] = str_replace(",", "", $_jfQio[$_jfI01]);
        }
      }


      $_QJlJ0 = "INSERT IGNORE INTO $_jf1jt SET DateOfSubscription=NOW()";

      $_jfIfl = false;
      $_jfI81 = "";
      if($_jf00Q["SendOptInEMail"]) {
        $_jfI81 = "'OptInConfirmationPending'";
        $_QJlJ0 .= ", SubscriptionStatus=$_jfI81";
        $_jfIfl = true;
        }
        else {
          $_jfI81 = "'Subscribed'";
          $_QJlJ0 .= ", SubscriptionStatus=$_jfI81, DateOfOptInConfirmation=NOW(), IPOnSubscription='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."'";
        }


      reset($_I16jJ);
      $_jfIi1 = "";
      $_ILQL0 = array();
      foreach($_I16jJ as $_I1i8O => $_IJQOL) {
        if($_IJQOL < 0) {continue;}
        if(is_array($_IJQOL) ){
          continue;
         }
        if($_IJQOL < count($_Qf1i1))
           $_I1L81 = $_Qf1i1[$_IJQOL];
           else
           $_I1L81 = "";
        if($_jf00Q["RemoveSpaces"]) {
          $_I1L81 = trim($_I1L81);
        }
        if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
          if($_I1i8O == "u_EMailFormat") {
            $_I1L81 = trim($_I1L81);
            if($_I1L81 != 'HTML' && $_I1L81 != 'PlainText' && $_I1L81 != 'Text')
              $_I1L81 = 'HTML';
              else
              if($_I1L81 == 'PlainText' || $_I1L81 == 'Text')
                $_I1L81 = 'PlainText';
          } // if($_I1i8O == "u_EMailFormat")
          if($_I1i8O == "u_Gender") {
            $_I1L81 = strtolower($_I1L81);
            $_I1L81 = trim($_I1L81);
            if($_I1L81 != "m" && $_I1L81 != "w") {
              $_I1L81 = substr($_I1L81, 0, 1);
              if($_I1L81 == "f") // female
                $_I1L81 = "w";
              if($_I1L81 != "m" && $_I1L81 != "w") {
                if($_I1L81 == "0")
                   $_I1L81 = "m";
                   else
                   if($_I1L81 == "1")
                      $_I1L81 = "w";
                      else
                       $_I1L81 = 'undefined';
              }
            }
          } // if($_I1i8O == "u_Gender")
          if($_I1i8O == "u_Birthday") {
            $_I1L81 = trim($_I1L81);
            if($_jf00Q["BirthdayDateFormat"] == "dd.mm.yyyy") {
              $_Io01I = explode(".", $_I1L81);
              while(count($_Io01I) < 3)
                 $_Io01I[] = "f";
              $_IOJ8I = $_Io01I[0];
              $_Io0t6 = $_Io01I[1];
              $_Io0l8 = $_Io01I[2];
            } else
              if($_jf00Q["BirthdayDateFormat"] == "yyyy-mm-dd") {
               $_Io01I = explode("-", $_I1L81);
               while(count($_Io01I) < 3)
                  $_Io01I[] = "f";
               $_IOJ8I = $_Io01I[2];
               $_Io0t6 = $_Io01I[1];
               $_Io0l8 = $_Io01I[0];
              } else
                if($_jf00Q["BirthdayDateFormat"] == "mm-dd-yyyy") {
                  $_Io01I = explode("-", $_I1L81);
                  while(count($_Io01I) < 3)
                     $_Io01I[] = "f";
                  $_IOJ8I = $_Io01I[1];
                  $_Io0t6 = $_Io01I[0];
                  $_Io0l8 = $_Io01I[2];
                }

            if(strlen($_Io0l8) == 2)
              $_Io0l8 = "19".$_Io0l8;
            if( ! (
                (intval($_IOJ8I) > 0 && intval($_IOJ8I) < 32) &&
                (intval($_Io0t6) > 0 && intval($_Io0t6) < 13)
                  )
              ) // error in date
              $_I1L81 = "0000-00-00";
              else
              $_I1L81 = "$_Io0l8-$_Io0t6-$_IOJ8I";

          } // if($_I1i8O == "u_Birthday")

          // integer
          if(
             $_I1i8O == "u_UserFieldInt1" ||
             $_I1i8O == "u_UserFieldInt2" ||
             $_I1i8O == "u_UserFieldInt3"
            )
              $_I1L81 = intval($_I1L81);

          // boolean
          if(
             $_I1i8O == "u_UserFieldBool1" ||
             $_I1i8O == "u_UserFieldBool2" ||
             $_I1i8O == "u_UserFieldBool3"
            ) {
              $_I1L81 = strtolower($_I1L81);
              if($_I1L81 == "true")
                $_I1L81 = 1;
                if($_I1L81 == "false")
                   $_I1L81 = 0;
              $_I1L81 = intval($_I1L81);
              if($_I1L81 < 0) $_I1L81 = 0;
              if($_I1L81 > 0) $_I1L81 = 1;
            }

          # no empty email addresses
          if($_I1i8O == "u_EMail" && (trim($_I1L81) == "" || !_OPAOJ($_I1L81)) ) {
            $_QJlJ0 = "";
            _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressIncorrect"], $_I1L81), $_jf00Q, $_jf0il );
            break;
          } else {
            if( $_I1i8O == "u_EMail" )
              $_jfIi1 = $_I1L81;
          }

          if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){
            $_ILQL0[] = " `$_I1i8O`="._OPQLR(htmlspecialchars($_I1L81, ENT_COMPAT, 'UTF-8'));
          } else {
            $_ILQL0[] = " `$_I1i8O`="._OPQLR($_I1L81);
          }
        }
      } // foreach($_I16jJ as $_I1i8O => $_I1L81)

      if($_QJlJ0 != "" && $_jfIi1 == "") {
         _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorNoEMailAddress"], $_jfIi1), $_jf00Q, $_jf0il );
         $_QJlJ0 = "";
      }

      if($_QJlJ0 != "")
        $_QJlJ0 .= ", ".join(", ", $_ILQL0);


      $_jfIl8 = false;
      if($_QJlJ0 != "") {

        $_jfjC6 = substr($_jfIi1, strpos($_jfIi1, '@') + 1);

        if(_L0FRD($_jfIi1)) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalBlockList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

        if(!$_jfIl8 && _L101P($_jfIi1, $_jf00Q["maillists_id"], $_ItCCo)) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalBlockList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

        if(!$_jfIl8 && _L1ROL($_jfjC6)) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalDomainBlockList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

        if(!$_jfIl8 && _L1RDP($_jfjC6, $_jf00Q["maillists_id"], $_jf1J1)) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalDomainBlockList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

        if(!$_jfIl8 && ($_jf1Lf && _OC0DR($_jfIi1)) ) {
          $_jfIl8 = true;
          _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInECGList"], $_jfIi1), $_jf00Q, $_jf0il );
        }

      }

      _OPQ6J();
      if($_jfIl8 && !$_jf00Q["ImportBlockedRecipients"])
        $_QJlJ0 = "";

      if($_QJlJ0 != "") {
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        $_QJCJi = mysql_error($_Q61I1);
        if($_QJCJi != "")
          $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
        if($_jf00Q["CreateErrorLog"] && $_jf0il && mysql_affected_rows($_Q61I1) == 0){
           _O6E18( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorDuplicateEntry"], $_jfIi1), $_jf00Q, $_jf0il );
        }
      }
      if($_QJlJ0 != "" && mysql_affected_rows($_Q61I1) > 0 ) {
        $_jf00Q["LastImportRowCount"]++;

        // statistics
        $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
        $_Q6Q1C=mysql_fetch_array($_Q60l1);
        $_QLitI = $_Q6Q1C[0];
        mysql_free_result($_Q60l1);

        $_QJlJ0 = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action=$_jfI81, Member_id=$_QLitI";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        $_QJCJi = mysql_error($_Q61I1);
        if($_QJCJi != "")
          $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";

        // **** apply groups_id to member_id START
        if( $_jf00Q["GroupsOption"] == 3 && $_jf00Q["groups_id"] > 0 ) {
          $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_jf00Q[groups_id], Member_id=$_QLitI";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_QJCJi = mysql_error($_Q61I1);
          if($_QJCJi != "")
            $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
        }
        // **** apply groups_id to member_id END

        // **** import groups names and create it if not exists START
        if( $_jf00Q["GroupsOption"] == 2 && count($_jfQio) > 0 ) {
          for($_jfJj0 = 0; $_jfJj0<count($_jfQio); $_jfJj0++) {
            if(trim($_jfQio[$_jfJj0]) == "") continue;
            $_QJlJ0 = "INSERT IGNORE INTO $_Q6t6j SET CreateDate=NOW(), Name="._OPQLR(trim($_jfQio[$_jfJj0]));
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            $_QJCJi = mysql_error($_Q61I1);
            if($_QJCJi != "")
              $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
            if(mysql_affected_rows($_Q61I1) > 0 ) {
              $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
              $_Q6Q1C=mysql_fetch_array($_Q60l1);
              $_I1JQt = $_Q6Q1C[0];
              mysql_free_result($_Q60l1);
            } else {
              $_QJlJ0 = "SELECT id FROM $_Q6t6j WHERE Name="._OPQLR(trim($_jfQio[$_jfJj0]));
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              $_QJCJi = mysql_error($_Q61I1);
              if($_QJCJi != "")
                $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
              $_I1JQt = 0;
              if($_Q60l1) {
                 $_Q6Q1C=mysql_fetch_array($_Q60l1);
                 $_I1JQt = $_Q6Q1C[0];
                 mysql_free_result($_Q60l1);
              }
            }

            if($_I1JQt != 0) {
              $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_I1JQt, Member_id=$_QLitI";
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              $_QJCJi = mysql_error($_Q61I1);
              if($_QJCJi != "")
                $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
            }
          }
          $_jfQio = array();
        }
        // **** import groups names and create it if not exists END

        if($_jfIfl && $_jfIi1 != "" && !$_jfIl8) {
          _OPQ6J();
          _L0RLJ("subscribeconfirm", $_QLitI, $_Ql00j, $_j6ioL, $errors, $_Ql1O8);
          if(count($_Ql1O8) > 0)
             $_j6O8O .= "<br />Error while sending opt in email ".join(" ", $_Ql1O8);
          _OPQ6J();
        }

      } // if(mysql_affected_rows($_Q61I1) > 0 )
        else
        if($_QJlJ0 != "" && $_jf00Q["GroupsOption"] > 0 && $_jf00Q["ExImportOption"] != 2 ) { // member exists, add to groups or update
          $_QJlJ0 = "SELECT id FROM $_jf1jt WHERE u_EMail="._OPQLR($_jfIi1);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_QJCJi = mysql_error($_Q61I1);
          if($_QJCJi != "")
            $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
          if(mysql_num_rows($_Q60l1) > 0) {
            $_Q6Q1C=mysql_fetch_array($_Q60l1);
            mysql_free_result($_Q60l1);
            $_QLitI = $_Q6Q1C["id"];

            # Remove from all groups
            if($_jf00Q["RemoveRecipientFromGroups"])
              _L1LLB(array($_QLitI), $_jf00Q["maillists_id"], $_QLI68);

            if($_jf00Q["ExImportOption"] == 3){
              // Update recipient
              // no empty values
              for($_Q6llo=0; $_Q6llo<count($_ILQL0); $_Q6llo++){
                if(strpos($_ILQL0[$_Q6llo], '""') !== false || strpos($_ILQL0[$_Q6llo], '"0000-00-00"') !== false) {
                  unset($_ILQL0[$_Q6llo]);
                }
              }
              $_QJlJ0 = "UPDATE $_jf1jt SET ".join(", ", $_ILQL0)." WHERE id=$_QLitI";
              mysql_query($_QJlJ0, $_Q61I1);
              $_QJCJi = mysql_error($_Q61I1);
              if($_QJCJi != "")
                $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
            }

            // **** apply groups_id to member_id START
            if( $_jf00Q["GroupsOption"] == 3 && $_jf00Q["groups_id"] > 0 ) {
              $_QJlJ0 = "SELECT * FROM $_QLI68 WHERE groups_id=$_jf00Q[groups_id] AND Member_id=$_QLitI";
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              if(mysql_num_rows($_Q60l1) == 0) {
                if($_Q60l1)
                  mysql_free_result($_Q60l1);
                $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_jf00Q[groups_id], Member_id=$_QLitI";
                $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                $_QJCJi = mysql_error($_Q61I1);
                if($_QJCJi != "")
                  $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
                $_jf00Q["LastImportRowCount"]++;
              } else
                if($_Q60l1)
                  mysql_free_result($_Q60l1);
            }
           // **** apply groups_id to member_id END

           // **** import groups names and create it if not exists START
           if( $_jf00Q["GroupsOption"] == 2 && isset($_jfQio) && count($_jfQio) > 0 ) {
             for($_jfJj0=0; $_jfJj0<count($_jfQio); $_jfJj0++) {
               if(trim($_jfQio[$_jfJj0]) == "") continue;
               $_QJlJ0 = "INSERT IGNORE INTO $_Q6t6j SET CreateDate=NOW(), Name="._OPQLR(trim($_jfQio[$_jfJj0]));
               $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
               $_QJCJi = mysql_error($_Q61I1);
               if($_QJCJi != "")
                 $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
               if(mysql_affected_rows($_Q61I1) > 0 ) {
                 $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
                 $_Q6Q1C=mysql_fetch_array($_Q60l1);
                 $_I1JQt = $_Q6Q1C[0];
                 mysql_free_result($_Q60l1);
               } else {
                 $_QJlJ0 = "SELECT id FROM $_Q6t6j WHERE Name="._OPQLR(trim($_jfQio[$_jfJj0]));
                 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                 $_QJCJi = mysql_error($_Q61I1);
                 if($_QJCJi != "")
                   $_j6O8O .= "<br />SQL ERROR ".$_QJCJi." for $_QJlJ0";
                 $_I1JQt = 0;
                 if($_Q60l1) {
                    $_Q6Q1C=mysql_fetch_array($_Q60l1);
                    $_I1JQt = $_Q6Q1C[0];
                    mysql_free_result($_Q60l1);
                 }
               }

               if($_I1JQt != 0) {
                 $_QJlJ0 = "SELECT * FROM $_QLI68 WHERE groups_id=$_I1JQt AND Member_id=$_QLitI";
                 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                 if(mysql_num_rows($_Q60l1) == 0) {
                   if($_Q60l1)
                     mysql_free_result($_Q60l1);
                   $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=$_I1JQt, Member_id=$_QLitI";
                   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                   _OAL8F($_QJlJ0);
                   $_jf00Q["LastImportRowCount"]++;
                 } else
                   if($_Q60l1)
                     mysql_free_result($_Q60l1);
               }
             }
             $_jfQio = array();
           }
           // **** import groups names and create it if not exists END

          } # if(mysql_num_rows($_Q60l1) > 0)

        } // if($_QJlJ0 != "" && $_jf00Q["GroupsOption"] > 0 && $_jf00Q["ExImportOption"] != 2 )



        _OPQ6J();
        $_jf00Q["LastStartPosition"] = $_Q6llo;
        $_jf00Q["LastTablePosition"] = $_Q6llo;
        if($_jf6jf) {
          $_jf00Q["LastTablePosition"] = -1;
        }

        $_QJlJ0 = "UPDATE $_I0f8O SET LastRowCount=$_jf00Q[LastRowCount], LastImportRowCount=$_jf00Q[LastImportRowCount], LastStartPosition=$_jf00Q[LastStartPosition], LastTablePosition=$_jf00Q[LastTablePosition]";
        $_QJlJ0 .= " WHERE id=$_jf00Q[id]";
        mysql_query($_QJlJ0, $_Q61I1);

    } # for($_Q6llo=$_IJQQI; ($_Q6llo<$_IJQQI + $_jf00Q["ImportLines"]) && !$_jf6jf; $_Q6llo++) {


    if(!empty($_jf00Q["CreateErrorLog"]) && $_jf0il){
      fclose($_jf0il);
    }

    if($_I0i1t != $_Q61I1) {
      db_close($_I0i1t, $_jf00Q["DBType"]);
      mysql_select_db (MySQLDBName, $_Q61I1);
    } else{
      mysql_select_db (MySQLDBName, $_Q61I1);
    }

    return true;
  }

  function _OODBQ($_jf00Q, &$errors, &$_I0600, $_I0C11, $_I0ift) {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;
    $_I0i1t = db_connect ($_jf00Q['SQLServerName'], $_jf00Q['SQLUsername'], $_jf00Q['SQLPassword'], $_I0C11, $_jf00Q['SQLDatabase'], $_I0ift);
    if ($_I0i1t == 0) {
       $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br/>".db_error($_I0i1t, $_I0C11));
       @db_close($_I0i1t, $_I0C11);
       return $_I0i1t;
    }

    if ($_I0i1t && !db_select_db ($_jf00Q['SQLDatabase'], $_I0i1t, $_I0C11)) {
      $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".$_jf00Q['SQLDatabase']."<br/>".db_error($_I0i1t, $_I0C11));
      if($_I0i1t != $_Q61I1) {
        db_close($_I0i1t, $_I0C11);
      } else{
        mysql_select_db (MySQLDBName, $_Q61I1);
      }
      $_I0i1t = 0;
    }
    return $_I0i1t;
  }

  function _O6E18($_I11oJ, $_jf00Q, $_jf0il) {
   global $_Q6JJJ;
   if($_jf00Q["CreateErrorLog"] && $_jf0il){
     fwrite($_jf0il, $_I11oJ.$_Q6JJJ);
   }
  }

?>

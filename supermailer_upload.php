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

  include_once("config.inc.php");
  include_once("htmltools.inc.php");

  # Required client version
  $_8oojj = 0x100;

  if    (

     ( (!isset($_POST["AppName"])) || ($_POST["AppName"] == "") ) ||
     ( (!isset($_POST["AppVersion"])) || ($_POST["AppVersion"] == "") ) ||
     ( (!isset($_POST["ClientVersion"])) || ($_POST["ClientVersion"] == "") ) ||
     ( (!isset($_POST["Action"])) || ($_POST["Action"] == "") )

     )
  {
    _JJL0F("Login failed, unknown or missing parameters.", 9999);
    exit;
  }

  if ($_POST["ClientVersion"] < $_8oojj) {
    _JJL0F("The client software is too old. Update it with online update function.", 9998);
    exit;
  }

  $Action = $_POST["Action"];

  # login
  # params Username, Password
  if($Action == "login"){

   if (
   ( (!isset($_POST["Username"])) || ($_POST["Username"] == "") ) ||
   ( (!isset($_POST["Password"])) || ($_POST["Password"] == "") )
   )
   {
     _JJL0F("Login failed, unknown or missing parameters.", 9999);
     exit;
   }

   $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;
   
   $_QLfol = "SELECT id, Username, Language FROM $_I18lo WHERE Username="._LRAFO($_POST["Username"])." AND ";
   if(!$_It0IQ)
     $_QLfol .= "IF(LENGTH(Password) < 80, Password=PASSWORD("._LRAFO($_POST["Password"]).")".", SUBSTRING(Password, 81)=PASSWORD("."CONCAT(SUBSTRING(Password, 1, 80), "._LRAFO($_POST["Password"]).") )".")";
     else
     $_QLfol .= "IF(LENGTH(Password) < 80, Password=SHA2("._LRAFO($_POST["Password"]).", 224)".", SUBSTRING(Password, 81)=SHA2("."CONCAT(SUBSTRING(Password, 1, 80), "._LRAFO($_POST["Password"])."), 224)".")";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);

   if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
     _JJL0F("Username / Password incorrect.", 9997);
     exit;
   }

   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   // is it a user than we need the owner_id
   $_QLfol = "SELECT owner_id FROM $_IfOtC WHERE users_id=$_QLO0f[id]";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if ( ($_QL8i1) && (mysql_num_rows($_QL8i1) > 0) ) {
     $_I016j = mysql_fetch_row($_QL8i1);
     mysql_free_result($_QL8i1);
     $_QLO0f["OwnerUserId"] = $_I016j[0];
   } else {
     $_QLO0f["OwnerUserId"] = 0;
   }

   # ignore errors if session.auto_start = 1
   if(!ini_get("session.auto_start"))
     session_start();

   #session_register("UserId", "OwnerUserId", "Username", "ClientIP", "Language");

   $_SESSION["UserId"] = $_QLO0f["id"];
   $_SESSION["OwnerUserId"] = $_QLO0f["OwnerUserId"];
   $_SESSION["Username"] = $_QLO0f["Username"];
   $_SESSION["Language"] = $_QLO0f["Language"];
   $_SESSION["ClientIP"] = getOwnIP();

   // paths
   if($_QLO0f["OwnerUserId"] != 0)
     _LRRFJ($_QLO0f["OwnerUserId"]);
     else
     _LRRFJ($_QLO0f["id"]);

   # Output
   $_8oCJ8 = array(
      "Return" => "OK",
      "ErrorCode" => 0,
      "UserId" => $_QLO0f["id"],
      "OwnerUserId" => $_QLO0f["OwnerUserId"],
      "UserFilesPath" => $_J18oI,
      "UserURL" => $_jfOJj,
      "ImportFilesPath" => $_ItL8f,
      "ExportFilesPath" => $_J1t6J,
      "AttachmentsFilesPath" => $_IIlfi,
      "ImagesFilesPath" => $_IJi8f,
      "MediaFilesPath" =>$_J1tfC,
      "SessionId" => session_id()
   );
   _JJJAJ($_8oCJ8);

   exit;
  }

  # normal functions

  ########################## check session
  if(!ini_get("session.auto_start"))
    @session_start();
  if ( ( !isset($_SESSION["UserId"]) ) Or ( !isset($_SESSION["OwnerUserId"]) ) Or ( !isset($_SESSION["Username"]))  ) {
    _JJL0F("Login incorrect or Session expired.", 100000);
    exit;
  }
  ##########################

  # logout
  # params none
  if($Action == "logout") {
    session_destroy();
    # Output
    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0
    );
    _JJJAJ($_8oCJ8);

   exit;
  }

  ## Globale vars
  $UserId = intval($_SESSION["UserId"]);
  $OwnerUserId = intval($_SESSION["OwnerUserId"]);
  $Username = $_SESSION["Username"];
  $Language = $_SESSION["Language"];
  ###

  // paths
  if($OwnerUserId != 0)
    _LRRFJ($OwnerUserId);
    else
    _LRRFJ($UserId);

  // Load User tables
  $_j6lIj = $OwnerUserId;
  if($_j6lIj == 0)
    $_j6lIj = $UserId;
  $_QLfol = "SELECT * FROM $_I18lo WHERE id=$_j6lIj";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_j661I = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  _LR8AP($_j661I);

  _LRPQ6($Language);
  _JQRLR($Language);

  # check privileges
  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeTemplateCreate"]) {
      _JJL0F("You have no permissions to create new email templates.", 100001);
      exit;
    }
  }

  # Upload image
  if($Action == "uploadimage") {
    if ( isset( $_FILES['FileName'] ) && !is_null( $_FILES['FileName']['tmp_name'] ) ) {
       $_661t1 = $_FILES['FileName']['name'];
       $_661t1 = _JJ6RE($_661t1);

       $_8oloC = _LPC1C($_IJi8f).$_661t1;

       if(!move_uploaded_file($_FILES['FileName']['tmp_name'], $_8oloC) && !copy($_FILES['FileName']['tmp_name'], $_8oloC)) {
         _JJL0F("Can't move file to $_8oloC.", 1001);
         exit;
       }

       if($OwnerUserId != 0)
         $_Jf1C8 = BasePath."userfiles/".$OwnerUserId."/image/";
         else
         $_Jf1C8 = BasePath."userfiles/".$UserId."/image/";

       # Output
       $_8oCJ8 = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "FileName" => $_Jf1C8.$_661t1,
          "CompleteFileNameAndPath" => $_8oloC,
          "SessionId" => session_id()
       );
       _JJJAJ($_8oCJ8);


    } else{
        _JJL0F("Filename missing.", 1000);
        exit;
    }

  }

  # Upload file
  if($Action == "uploadfile") {
    if ( isset( $_FILES['FileName'] ) && !is_null( $_FILES['FileName']['tmp_name'] ) ) {
       $_661t1 = $_FILES['FileName']['name'];
       $_661t1 = _JJ6RE($_661t1);

       $_8oloC = _LPC1C($_IIlfi).$_661t1;

       if(!move_uploaded_file($_FILES['FileName']['tmp_name'], $_8oloC) && !copy($_FILES['FileName']['tmp_name'], $_8oloC)) {
         _JJL0F("Can't move file to $_8oloC.", 1001);
         exit;
       }

       if($OwnerUserId != 0)
         $_Jf1C8 = BasePath."userfiles/".$OwnerUserId."/file/";
         else
         $_Jf1C8 = BasePath."userfiles/".$UserId."/file/";

       # Output
       $_8oCJ8 = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "FileName" => $_Jf1C8.$_661t1,
          "CompleteFileNameAndPath" => $_8oloC,
          "SessionId" => session_id()
       );
       _JJJAJ($_8oCJ8);


    } else{
        _JJL0F("Filename missing.", 1000);
        exit;
    }

  }

  # Upload html
  if($Action == "uploadhtml") { // utf-8 encoded!

       $_QLJfI = $_POST["Text"];
       if($_QLJfI == "") {
         _JJL0F("Text is empty.", 1002);
         exit;
       }

       $_QLo60 = "d.m.Y H:i:s";
       if($Language != "de") {
          $_QLo60 = "Y-m-d H:i:s";
       }

       $_JiIoi = 0;
       if(isset($_POST["IsWizardable"]) && intval($_POST["IsWizardable"]) == 1)
         $_JiIoi = 1;
       $_8C0jf = "Upload ".date($_QLo60);
       $_QLfol = "INSERT INTO $_Ql10t SET CreateDate=NOW(), Name="._LRAFO( $_8C0jf ).", MailFormat='HTML', MailHTMLText="._LRAFO(CleanUpHTML($_QLJfI)).", `IsWizardable`=$_JiIoi";
       mysql_query($_QLfol, $_QLttI);
       if(mysql_error($_QLttI) != "") {
         _JJL0F("Can't insert template, error: ".mysql_error($_QLttI), 1003);
         exit;
       }

       # Output
       $_8oCJ8 = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "TemplateName" => $_8C0jf,
          "SessionId" => session_id()
       );
       _JJJAJ($_8oCJ8);
  }

  # Upload text
  if($Action == "uploadtext") { // utf-8 encoded!

       $_QLJfI = $_POST["Text"];
       if($_QLJfI == "") {
         _JJL0F("Text is empty.", 1002);
         exit;
       }

       $_QLo60 = "d.m.Y H:i:s";
       if($Language != "de") {
          $_QLo60 = "Y-m-d H:i:s";
       }

       $_8C0jf = "Upload ".date($_QLo60);
       $_QLfol = "INSERT INTO $_Ql10t SET CreateDate=NOW(), Name="._LRAFO( $_8C0jf ).", MailFormat='PlainText', MailPlainText="._LRAFO($_QLJfI);
       mysql_query($_QLfol, $_QLttI);
       if(mysql_error($_QLttI) != "") {
         _JJL0F("Can't insert template, error: ".mysql_error($_QLttI), 1003);
         exit;
       }

       # Output
       $_8oCJ8 = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "TemplateName" => $_8C0jf,
          "SessionId" => session_id()
       );
       _JJJAJ($_8oCJ8);


  }


  function _JJ6RE($_661t1) {
    if(IsUTF8string($_661t1))
      $_661t1 =  utf8_decode($_661t1);
    $_QLJfI = "";
    $_661t1 = str_replace(" ", "_", $_661t1);
    $_661t1 = str_replace("-", "_", $_661t1);
    for($_Qli6J=0; $_Qli6J<strlen($_661t1); $_Qli6J++) {
       if (
           (ord($_661t1[$_Qli6J]) >= 0x30 && ord($_661t1[$_Qli6J]) <= 0x39) ||
           (ord($_661t1[$_Qli6J]) >= 0x41 && ord($_661t1[$_Qli6J]) <= 0x5A) ||
           (ord($_661t1[$_Qli6J]) >= 0x61 && ord($_661t1[$_Qli6J]) <= 0x7A) ||
           (ord($_661t1[$_Qli6J]) == 0x5F) ||
           ($_661t1[$_Qli6J] == '.')
          ) {
            $_QLJfI = $_QLJfI . $_661t1[$_Qli6J];
          } else {
            switch(ord($_661t1[$_Qli6J])) {
              case 0xC4: $_QLJfI = $_QLJfI . "Ae";
                    break;
              case 0xDC: $_QLJfI = $_QLJfI . "Ue";
                    break;
              case 0xD6: $_QLJfI = $_QLJfI . "Oe";
                    break;
              case 0xE4: $_QLJfI = $_QLJfI . "ae";
                    break;
              case 0xFC: $_QLJfI = $_QLJfI . "ue";
                    break;
              case 0xF6: $_QLJfI = $_QLJfI . "oe";
                    break;
              case 0xDF: $_QLJfI = $_QLJfI . "ss";
                    break;
            }
          }
    }
    return $_QLJfI;
  }


  function _JJL0F($_J0COJ, $_8olOL, $_8oloj = false) {
     if (!$_8oloj) {
       print "Return: ERROR\r\n";
       print "ErrorCode: $_8olOL\r\n";
       print "ErrorText: $_J0COJ\r\n";
       print "\r\n";
       flush();
       exit;
     } else {
       return array(
          "Return" => "ERROR",
          "ErrorCode" => $_8olOL,
          "ErrorText" => $_J0COJ
       );
     }
  }

  function _JJLE6($_QLJfI) {
   $_QLJfI = str_replace("\r", "", $_QLJfI);
   $_QLJfI = str_replace("\n", '\n', $_QLJfI);
   $_QLJfI = str_replace("</", '<%2F', $_QLJfI);
   return $_QLJfI;
  }

  function _JJJAJ($_8oCJ8) {
    reset($_8oCJ8);
    foreach ($_8oCJ8 as $key => $_QltJO) {
      if(!is_array($_QltJO)) {
        echo "$key: $_QltJO\r\n";
      } else {
        reset($_QltJO);

        echo "$key: ";
        for($_Qli6J=0; $_Qli6J<count($_QltJO); $_Qli6J++) {
          echo "<item value=\"$_Qli6J\">";
          if(is_array($_QltJO[$_Qli6J]) )
            foreach ($_QltJO[$_Qli6J] as $_IOLil => $_IOCjL) {
              echo "<$_IOLil>"._JJLE6($_IOCjL)."</$_IOLil>";
            }
          if(!is_array($_QltJO[$_Qli6J]))
            echo _JJLE6($_QltJO[$_Qli6J]);
          echo "</item>";
        }

      }
    }
    echo "\r\n";
    flush();
  }


?>

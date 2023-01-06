<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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

  // Boolean fields of form
  $_ItI0o = Array ('IsWizardable');

  $_ItIti = Array ();

  $errors = array();
  $_jofff = 0;

  if(isset($_POST['TemplateId'])) // Formular speichern?
    $_jofff = intval($_POST['TemplateId']);
  else
    if ( isset($_POST['OneTemplateListId']) )
       $_jofff = intval($_POST['OneTemplateListId']);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_jofff && !$_QLJJ6["PrivilegeTemplateCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_jofff && !$_QLJJ6["PrivilegeTemplateEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";

  if(isset($_POST['SubmitBtn'])) { // Formular speichern?

    if( isset($_POST["MailHTMLText"]) ) {
       $_POST["MailHTMLText"] = CleanUpHTML( $_POST["MailHTMLText"] );
       // fix www to http://wwww.
       $_POST["MailHTMLText"] = str_replace('href="www.', 'href="http://www.', $_POST["MailHTMLText"]);
    }

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';
    //
    if ( (isset($_POST['MailFormat'])) && ($_POST['MailFormat'] == "") )
      $errors[] = 'MailFormat';
    //

    if (( $_POST['MailFormat'] == "HTML" || $_POST['MailFormat'] == "Multipart" ) ) {
       if( trim( unhtmlentities( @strip_tags ( $_POST["MailHTMLText"] ), $_QLo06 ) ) == "")
         $errors[] = 'MailHTMLText';
    }
    if ( $_POST['MailFormat'] == "PlainText"  ) {
       if(trim($_POST["MailPlainText"]) == "")
         $errors[] = 'MailPlainText';
    }

    if( defined("SWM") )
      $_fJ6Of = ($OwnerOwnerUserId > 65 && $OwnerOwnerUserId != 90);
      else
      $_fJ6Of = true;

    if($OwnerUserId != 0 && isset($_POST["UsersOption"]))
      unset($_POST["UsersOption"]);

    if(!$_fJ6Of && isset($_POST["UsersOption"]))
      unset($_POST["UsersOption"]);

    if(empty($_POST["UsersOption"]) && $OwnerUserId == 0)
      $_POST["UsersOption"] = 0;
    if(isset($_POST["UsersOption"]) && $_POST["UsersOption"]){
      if( !isset($_POST["users_id"]) || count($_POST["users_id"]) == 0)
         $_POST["UsersOption"] = 0;
    }


    if(count($errors) == 0 && $_jofff == 0) {
      $_QLfol = "SELECT COUNT(*) FROM $_Ql10t WHERE `Name`="._LRAFO(trim($_POST['Name']));
      $_QL8i1 = mysql_query($_QLfol);
      if($_QL8i1 && ($_QLO0f = mysql_fetch_array($_QL8i1)) && ($_QLO0f[0] > 0) ) {
       mysql_free_result($_QL8i1);
       $errors[] = 'Name';
       $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000805"], trim($_POST['Name']));
      }
       else
         if($_QL8i1)
           mysql_free_result($_QL8i1);
    }

    if(count($errors) == 0) {
      if (( $_POST['MailFormat'] == "HTML" || $_POST['MailFormat'] == "Multipart" ) ) {
         $_IoijI = array();
         _LA61D($_POST["MailHTMLText"], $_IoijI);
         if(count($_IoijI) > 0) {
           $errors[] = 'FileError_MailHTMLText';
           $_Itfj8 = join("<br />", $_IoijI);
         }
      }
    }

    if(count($errors) > 0) {
        if($_Itfj8 == "")
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
        $_IoLOO = $_POST;
        _JJPEQ($_jofff, $_IoLOO);
        if($_jofff != 0)
           $_POST["TemplateId"] = $_jofff;
      }
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000804"], $_Itfj8, 'templateedit', 'templateedit_snipped.htm');

  $_QLJfI = str_replace ('name="TemplateId"', 'name="TemplateId" value="'.$_jofff.'"', $_QLJfI);
  $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

  if( defined("SWM") )
    $_fJ6Of = ($OwnerOwnerUserId > 65 && $OwnerOwnerUserId != 90);
    else
    $_fJ6Of = true;

  # users
  if($OwnerUserId != 0 || !$_fJ6Of) {
    $_QLJfI = _L81BJ($_QLJfI, "<CAN:SHOW_USERS>", "</CAN:SHOW_USERS>", "");
  } else {
    $_QLJfI = str_replace("<CAN:SHOW_USERS>", "", $_QLJfI);
    $_QLJfI = str_replace("</CAN:SHOW_USERS>", "", $_QLJfI);

    $_joLCQ = $UserId;
    if($OwnerUserId != 0) // kein Admin?
      $_joLCQ = $OwnerUserId;

    $_QLfol = "SELECT id, Username FROM $_I18lo LEFT JOIN $_IfOtC ON id=users_id WHERE (owner_id=$_joLCQ)";
    if($OwnerUserId != 0) // kein Admin?
      $_QLfol .= " AND users_id<>$UserId";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);

    $_IC1C6 = _L81DB($_QLJfI, "<SHOW:USERS>", "</SHOW:USERS>");
    $_ICQjo = 0;
    $_8CCCO = "";
    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      $_8CCCO .= $_IC1C6;

      $_8CCCO = _L81BJ($_8CCCO, "<UsersId>", "</UsersId>", $_QLO0f["id"]);
      $_8CCCO = _L81BJ($_8CCCO, "&lt;UsersId&gt;", "&lt;/UsersId&gt;", $_QLO0f["id"]);
      $_8CCCO = _L81BJ($_8CCCO, "<UsersName>", "</UsersName>", $_QLO0f["Username"]);
      $_8CCCO = _L81BJ($_8CCCO, "&lt;UsersName&gt;", "&lt;/UsersName&gt;", $_QLO0f["Username"]);
      $_ICQjo++;
      $_8CCCO = str_replace("UsersLabelId", 'userchkbox_'.$_ICQjo, $_8CCCO);
    }

    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:USERS>", "</SHOW:USERS>", $_8CCCO);

  }

  #### normal placeholders
  $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);
  $_I1OoI=array();
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
   $_I1OoI[] =  sprintf("new Array('[%s]', '%s')", $_QLO0f["fieldname"], $_QLO0f["text"]);
  }
  # defaults
  foreach ($_Iol8t as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QLJfI = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
  mysql_free_result($_QL8i1);

  #### special newsletter unsubscribe placeholders
  unset($_I1OoI);
  $_I1OoI=array();
  $_ICCIo = array();
  $_ICCIo = array_merge($_IolCJ, $_ICiQ1);
  reset($_ICCIo);
  foreach ($_ICCIo as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QLJfI = str_replace ("new Array('[NEWSLETTER_UNSUBSCRIBEPLACEHOLDER]', 'NEWSLETTER_UNSUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);

  # Template laden
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;

  } else {
    if($_jofff > 0) {
      $_QLfol= "SELECT * FROM $_Ql10t WHERE id=$_jofff";
      $_QL8i1 = mysql_query($_QLfol);
      _L8D88($_QLfol);
      $ML=mysql_fetch_array($_QL8i1);
      mysql_free_result($_QL8i1);
      $ML["MailHTMLText"] = FixCKEditorStyleProtectionForCSS($ML["MailHTMLText"]);

      // remove boolean fields
      for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
         if(!$ML[$_ItI0o[$_Qli6J]])
            unset($ML[$_ItI0o[$_Qli6J]]);

    } else {
     $ML = array();
     $ML["AutoUpdateTextPart"] = 1;
     $ML["MailFormat"] = "HTML";
     $ML["UsersOption"] = 0;
     $ML["IsWizardable"] = 0;
     $ML["MailHTMLText"] = $_IC18i;
    }
  }

  // select users
  if(isset($ML["UsersOption"]) && $ML["UsersOption"] > 0){
     $_QLfol = "SELECT id FROM $_I18lo LEFT JOIN $_Ql18I ON $_Ql18I.`users_id`=$_I18lo.id WHERE `templates_id`=$_jofff";
     $_QL8i1 = mysql_query($_QLfol);
     _L8D88($_QLfol);
     while($_QLO0f = mysql_fetch_array($_QL8i1)) {
       $_QLJfI = str_replace('name="users_id[]" value="'.$_QLO0f["id"].'"', 'name="users_id[]" value="'.$_QLO0f["id"].'" checked="checked"', $_QLJfI);
     }
     mysql_free_result($_QL8i1);
  }

  if(isset($ML["users_id"]))
     unset($ML["users_id"]);

  $_QLJfI = _L8AOB($errors, $ML, $_QLJfI);

  # show Warnlabel, we cannot mark fckeditor in red because this will be saved
  if(count($errors) > 0) {
    $_ICI0L = "";
    if(in_array('MailHTMLText', $errors))
       $_ICI0L .= "document.getElementById('MailHTMLTextWarnLabel').style.display = '';$_QLl1Q";
    // file errors
    if(in_array('FileError_MailHTMLText', $errors)){
       $_ICI0L .= "document.getElementById('MailHTMLTextWarnLabel').style.display = '';$_QLl1Q";
       $_ICI0L .= "document.getElementById('MailHTMLTextWarnLabel').innerHTML = '".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';$_QLl1Q";
    }
    $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_ICI0L, $_QLJfI);
  }

  print $_QLJfI;

  function _JJPEQ(&$_jofff, $_I6tLJ) {
    global $_Ql10t, $_Ql18I, $_ItI0o, $_ItIti, $UserId, $OwnerUserId;

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM $_Ql10t";
    $_QL8i1 = mysql_query($_QLfol);
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

    // new entry?
    $_fifjt = false;
    if($_jofff == 0) {
      $_QLfol = "INSERT INTO $_Ql10t (CreateDate) VALUES(NOW())";
      mysql_query($_QLfol);
      _L8D88($_QLfol);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()");
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_jofff = $_QLO0f[0];
      mysql_free_result($_QL8i1);
      $_fifjt = true;
    }


    $_QLfol = "UPDATE $_Ql10t SET ";
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
    $_QLfol .= " WHERE id=$_jofff";
    $_QL8i1 = mysql_query($_QLfol);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }

    if(isset($_I6tLJ["UsersOption"])) {
      if($_I6tLJ["UsersOption"] == 0){
        $_QLfol = "DELETE FROM `$_Ql18I` WHERE `templates_id`=$_jofff";
        mysql_query($_QLfol);
      } else{
        $_QLfol = "DELETE FROM `$_Ql18I` WHERE `templates_id`=$_jofff";
        mysql_query($_QLfol);

        $_QLfol = "INSERT INTO `$_Ql18I` (users_id, templates_id) VALUES";
        $_Ql0fO = array();
        for($_Qli6J=0; $_Qli6J<count($_I6tLJ["users_id"]); $_Qli6J++)
           $_Ql0fO[] = "(".intval($_I6tLJ["users_id"][$_Qli6J]).", $_jofff)";
        $_QLfol .= join(',', $_Ql0fO);
        mysql_query($_QLfol);
        _L8D88($_QLfol);
      }
    }

  }

?>

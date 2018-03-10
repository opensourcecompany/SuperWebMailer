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

  // Boolean fields of form
  $_I01C0 = Array ('IsWizardable');

  $_I01lt = Array ();

  $errors = array();
  $_j00LC = 0;

  if(isset($_POST['TemplateId'])) // Formular speichern?
    $_j00LC = intval($_POST['TemplateId']);
  else
    if ( isset($_POST['OneTemplateListId']) )
       $_j00LC = intval($_POST['OneTemplateListId']);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_j00LC && !$_QJojf["PrivilegeTemplateCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_j00LC && !$_QJojf["PrivilegeTemplateEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";

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
       if( trim( unhtmlentities( @strip_tags ( $_POST["MailHTMLText"] ), $_Q6QQL ) ) == "")
         $errors[] = 'MailHTMLText';
    }
    if ( $_POST['MailFormat'] == "PlainText"  ) {
       if(trim($_POST["MailPlainText"]) == "")
         $errors[] = 'MailPlainText';
    }

    if( defined("SWM") )
      $_JLllt = ($OwnerOwnerUserId > 65 && $OwnerOwnerUserId != 90);
      else
      $_JLllt = true;

    if($OwnerUserId != 0 && isset($_POST["UsersOption"]))
      unset($_POST["UsersOption"]);

    if(!$_JLllt && isset($_POST["UsersOption"]))
      unset($_POST["UsersOption"]);

    if(empty($_POST["UsersOption"]) && $OwnerUserId == 0)
      $_POST["UsersOption"] = 0;
    if(isset($_POST["UsersOption"]) && $_POST["UsersOption"]){
      if( !isset($_POST["users_id"]) || count($_POST["users_id"]) == 0)
         $_POST["UsersOption"] = 0;
    }


    if(count($errors) == 0 && $_j00LC == 0) {
      $_QJlJ0 = "SELECT COUNT(*) FROM $_Q66li WHERE `Name`="._OPQLR(trim($_POST['Name']));
      $_Q60l1 = mysql_query($_QJlJ0);
      if($_Q60l1 && ($_Q6Q1C = mysql_fetch_array($_Q60l1)) && ($_Q6Q1C[0] > 0) ) {
       mysql_free_result($_Q60l1);
       $errors[] = 'Name';
       $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000805"], trim($_POST['Name']));
      }
       else
         if($_Q60l1)
           mysql_free_result($_Q60l1);
    }

    if(count($errors) == 0) {
      if (( $_POST['MailFormat'] == "HTML" || $_POST['MailFormat'] == "Multipart" ) ) {
         $_IQlQ1 = array();
         _OBEPD($_POST["MailHTMLText"], $_IQlQ1);
         if(count($_IQlQ1) > 0) {
           $errors[] = 'FileError_MailHTMLText';
           $_I0600 = join("<br />", $_IQlQ1);
         }
      }
    }

    if(count($errors) > 0) {
        if($_I0600 == "")
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
        $_II1Ot = $_POST;
        _LJJ10($_j00LC, $_II1Ot);
        if($_j00LC != 0)
           $_POST["TemplateId"] = $_j00LC;
      }
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000804"], $_I0600, 'templateedit', 'templateedit_snipped.htm');

  $_QJCJi = str_replace ('name="TemplateId"', 'name="TemplateId" value="'.$_j00LC.'"', $_QJCJi);
  $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

  if( defined("SWM") )
    $_JLllt = ($OwnerOwnerUserId > 65 && $OwnerOwnerUserId != 90);
    else
    $_JLllt = true;

  # users
  if($OwnerUserId != 0 || !$_JLllt) {
    $_QJCJi = _OPR6L($_QJCJi, "<CAN:SHOW_USERS>", "</CAN:SHOW_USERS>", "");
  } else {
    $_QJCJi = str_replace("<CAN:SHOW_USERS>", "", $_QJCJi);
    $_QJCJi = str_replace("</CAN:SHOW_USERS>", "", $_QJCJi);

    $_j0j6C = $UserId;
    if($OwnerUserId != 0) // kein Admin?
      $_j0j6C = $OwnerUserId;

    $_QJlJ0 = "SELECT id, Username FROM $_Q8f1L LEFT JOIN $_QLtQO ON id=users_id WHERE (owner_id=$_j0j6C)";
    if($OwnerUserId != 0) // kein Admin?
      $_QJlJ0 .= " AND users_id<>$UserId";
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    $_IIJi1 = _OP81D($_QJCJi, "<SHOW:USERS>", "</SHOW:USERS>");
    $_II6ft = 0;
    $_fQOft = "";
    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
      $_fQOft .= $_IIJi1;

      $_fQOft = _OPR6L($_fQOft, "<UsersId>", "</UsersId>", $_Q6Q1C["id"]);
      $_fQOft = _OPR6L($_fQOft, "&lt;UsersId&gt;", "&lt;/UsersId&gt;", $_Q6Q1C["id"]);
      $_fQOft = _OPR6L($_fQOft, "<UsersName>", "</UsersName>", $_Q6Q1C["Username"]);
      $_fQOft = _OPR6L($_fQOft, "&lt;UsersName&gt;", "&lt;/UsersName&gt;", $_Q6Q1C["Username"]);
      $_II6ft++;
      $_fQOft = str_replace("UsersLabelId", 'userchkbox_'.$_II6ft, $_fQOft);
    }

    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:USERS>", "</SHOW:USERS>", $_fQOft);

  }

  #### normal placeholders
  $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_Q8otJ=array();
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_Q8otJ[] =  sprintf("new Array('[%s]', '%s')", $_Q6Q1C["fieldname"], $_Q6Q1C["text"]);
  }
  # defaults
  foreach ($_IIQI8 as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QJCJi = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);
  mysql_free_result($_Q60l1);

  #### special newsletter unsubscribe placeholders
  unset($_Q8otJ);
  $_Q8otJ=array();
  $_Ij0oj = array();
  $_Ij0oj = array_merge($_III0L, $_Ij18l);
  reset($_Ij0oj);
  foreach ($_Ij0oj as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  $_QJCJi = str_replace ("new Array('[NEWSLETTER_UNSUBSCRIBEPLACEHOLDER]', 'NEWSLETTER_UNSUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);

  # Template laden
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;

  } else {
    if($_j00LC > 0) {
      $_QJlJ0= "SELECT * FROM $_Q66li WHERE id=$_j00LC";
      $_Q60l1 = mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      $ML=mysql_fetch_array($_Q60l1);
      mysql_free_result($_Q60l1);
      $ML["MailHTMLText"] = FixCKEditorStyleProtectionForCSS($ML["MailHTMLText"]);

      // remove boolean fields
      for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
         if(!$ML[$_I01C0[$_Q6llo]])
            unset($ML[$_I01C0[$_Q6llo]]);

    } else {
     $ML = array();
     $ML["AutoUpdateTextPart"] = 1;
     $ML["MailFormat"] = "HTML";
     $ML["UsersOption"] = 0;
     $ML["IsWizardable"] = 0;
    }
  }

  // select users
  if(isset($ML["UsersOption"]) && $ML["UsersOption"] > 0){
     $_QJlJ0 = "SELECT id FROM $_Q8f1L LEFT JOIN $_Q6ftI ON $_Q6ftI.`users_id`=$_Q8f1L.id WHERE `templates_id`=$_j00LC";
     $_Q60l1 = mysql_query($_QJlJ0);
     _OAL8F($_QJlJ0);
     while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
       $_QJCJi = str_replace('name="users_id[]" value="'.$_Q6Q1C["id"].'"', 'name="users_id[]" value="'.$_Q6Q1C["id"].'" checked="checked"', $_QJCJi);
     }
     mysql_free_result($_Q60l1);
  }

  if(isset($ML["users_id"]))
     unset($ML["users_id"]);

  $_QJCJi = _OPFJA($errors, $ML, $_QJCJi);

  # show Warnlabel, we cannot mark fckeditor in red because this will be saved
  if(count($errors) > 0) {
    $_II6C6 = "";
    if(in_array('MailHTMLText', $errors))
       $_II6C6 .= "document.getElementById('MailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";
    // file errors
    if(in_array('FileError_MailHTMLText', $errors)){
       $_II6C6 .= "document.getElementById('MailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";
       $_II6C6 .= "document.getElementById('MailHTMLTextWarnLabel').innerHTML = '".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';$_Q6JJJ";
    }
    $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_II6C6, $_QJCJi);
  }

  print $_QJCJi;

  function _LJJ10(&$_j00LC, $_Qi8If) {
    global $_Q66li, $_Q6ftI, $_I01C0, $_I01lt, $UserId, $OwnerUserId;

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM $_Q66li";
    $_Q60l1 = mysql_query($_QJlJ0);
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

    // new entry?
    $_6JlQQ = false;
    if($_j00LC == 0) {
      $_QJlJ0 = "INSERT INTO $_Q66li (CreateDate) VALUES(NOW())";
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()");
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_j00LC = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
      $_6JlQQ = true;
    }


    $_QJlJ0 = "UPDATE $_Q66li SET ";
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
    $_QJlJ0 .= " WHERE id=$_j00LC";
    $_Q60l1 = mysql_query($_QJlJ0);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }

    if(isset($_Qi8If["UsersOption"])) {
      if($_Qi8If["UsersOption"] == 0){
        $_QJlJ0 = "DELETE FROM `$_Q6ftI` WHERE `templates_id`=$_j00LC";
        mysql_query($_QJlJ0);
      } else{
        $_QJlJ0 = "DELETE FROM `$_Q6ftI` WHERE `templates_id`=$_j00LC";
        mysql_query($_QJlJ0);

        $_QJlJ0 = "INSERT INTO `$_Q6ftI` (users_id, templates_id) VALUES";
        $_Q66jQ = array();
        for($_Q6llo=0; $_Q6llo<count($_Qi8If["users_id"]); $_Q6llo++)
           $_Q66jQ[] = "(".intval($_Qi8If["users_id"][$_Q6llo]).", $_j00LC)";
        $_QJlJ0 .= join(',', $_Q66jQ);
        mysql_query($_QJlJ0);
        _OAL8F($_QJlJ0);
      }
    }

  }

?>

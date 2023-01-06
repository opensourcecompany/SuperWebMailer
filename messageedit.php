<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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
  $_ItI0o = Array ();

  $_ItIti = Array ();

  $errors = array();
  $_j61C0 = 0;

  if(isset($_POST['MessageId'])) // Formular speichern?
    $_j61C0 = intval($_POST['MessageId']);
  else
    if ( isset($_POST['OneMessageListId']) )
       $_j61C0 = intval($_POST['OneMessageListId']);


  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_j61C0 == 0 && !$_QLJJ6["PrivilegeMessageCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_j61C0 != 0 && !$_QLJJ6["PrivilegeMessageEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";

  if(isset($_POST['SubmitBtn'])) { // Formular speichern?

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';

    if(!count($errors) && $_j61C0 == 0){
      $_QLfol= "SELECT COUNT(*) FROM $_Ifi1J WHERE Name="._LRAFO(trim($_POST['Name']));
      $_QL8i1 = mysql_query($_QLfol);
      $_QLO0f=mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      if($_QLO0f[0]>0){
        $errors[] = 'Name';
      }
    }

    //

    if(count($errors) > 0) {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
        foreach($_POST as $key => $_QltJO) {
          if(is_string($_QltJO) && strpos($_QltJO, session_id()) !== false) {
            $_QltJO = CleanUpHTML($_QltJO);
            $_POST[$key] = $_QltJO;
          }
        }
        $_IoLOO = $_POST;
        _LFC1Q($_j61C0, $_IoLOO);
        if($_j61C0 != 0)
          $_POST["MessageId"] = $_j61C0;
      }
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000064"], $_Itfj8, 'messageedit', 'messageedit_snipped.htm');

  $_QLJfI = str_replace ('name="MessageId"', 'name="MessageId" value="'.$_j61C0.'"', $_QLJfI);


  # Message laden
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;

  } else {
    if($_j61C0 > 0) {
      $_QLfol= "SELECT * FROM $_Ifi1J WHERE id=$_j61C0";
      $_QL8i1 = mysql_query($_QLfol);
      _L8D88($_QLfol);
      $ML=mysql_fetch_array($_QL8i1);
      mysql_free_result($_QL8i1);

    } else {
     $ML = array();

     // load default text
     $_QLfol = "SELECT * FROM $_Ifi1J WHERE id=1";
     $_QL8i1 = mysql_query($_QLfol);
     if(mysql_num_rows($_QL8i1) > 0) {
       $_QLO0f = mysql_fetch_array($_QL8i1);
       mysql_free_result($_QL8i1);
       unset($_QLO0f["id"]);
       unset($_QLO0f["Name"]);
       $ML = array_merge($ML, $_QLO0f);
     }
     // load default text END

    }
  }

  $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE'";
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);
  $_I0Clj = _L81DB($_QLJfI, '<TABLE:ROW>', '</TABLE:ROW>');
  $_QLoli = "";
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
     $_QLoli .= $_I0Clj;
     $_QLoli = str_replace('FIELDDESCRIPTION', "$_QLO0f[text]", $_QLoli);
     $_QLoli = str_replace('FIELDNAME', "$_QLO0f[fieldname]", $_QLoli);
  }

  $_QLJfI = _L81BJ($_QLJfI, '<TABLE:ROW>', '</TABLE:ROW>', $_QLoli);
  $_QLJfI = _L8AOB($errors, $ML, $_QLJfI);

  print $_QLJfI;

  function _LFC1Q(&$_j61C0, $_I6tLJ) {
    global $_Ifi1J, $_ItI0o, $_ItIti;

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM $_Ifi1J";
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
    if($_j61C0 == 0) {

      $_QLfol = "INSERT INTO $_Ifi1J (CreateDate) VALUES(NOW())";
      mysql_query($_QLfol);
      _L8D88($_QLfol);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()");
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_j61C0 = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }


    $_QLfol = "UPDATE $_Ifi1J SET ";
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
           $_Io01j[] = "`$key`="._LRAFO(rtrim($_I6tLJ[$key]) );
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
    $_QLfol .= " WHERE id=$_j61C0";
    $_QL8i1 = mysql_query($_QLfol);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }

  }

?>

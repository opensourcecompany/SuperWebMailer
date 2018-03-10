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
  $_I01C0 = Array ();

  $_I01lt = Array ();

  $errors = array();
  $_IolII = 0;

  if(isset($_POST['MessageId'])) // Formular speichern?
    $_IolII = intval($_POST['MessageId']);
  else
    if ( isset($_POST['OneMessageListId']) )
       $_IolII = intval($_POST['OneMessageListId']);


  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_IolII == 0 && !$_QJojf["PrivilegeMessageCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_IolII != 0 && !$_QJojf["PrivilegeMessageEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";

  if(isset($_POST['SubmitBtn'])) { // Formular speichern?

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';

    if(!count($errors) && $_IolII == 0){
      $_QJlJ0= "SELECT COUNT(*) FROM $_QLo0Q WHERE Name="._OPQLR(trim($_POST['Name']));
      $_Q60l1 = mysql_query($_QJlJ0);
      $_Q6Q1C=mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      if($_Q6Q1C[0]>0){
        $errors[] = 'Name';
      }
    }

    //

    if(count($errors) > 0) {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
        foreach($_POST as $key => $_Q6ClO) {
          if(is_string($_Q6ClO) && strpos($_Q6ClO, session_id()) !== false) {
            $_Q6ClO = CleanUpHTML($_Q6ClO);
            $_POST[$key] = $_Q6ClO;
          }
        }
        $_II1Ot = $_POST;
        _OFCA1($_IolII, $_II1Ot);
        if($_IolII != 0)
          $_POST["MessageId"] = $_IolII;
      }
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000064"], $_I0600, 'messageedit', 'messageedit_snipped.htm');

  $_QJCJi = str_replace ('name="MessageId"', 'name="MessageId" value="'.$_IolII.'"', $_QJCJi);


  # Message laden
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;

  } else {
    if($_IolII > 0) {
      $_QJlJ0= "SELECT * FROM $_QLo0Q WHERE id=$_IolII";
      $_Q60l1 = mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      $ML=mysql_fetch_array($_Q60l1);
      mysql_free_result($_Q60l1);

    } else {
     $ML = array();

     // load default text
     $_QJlJ0 = "SELECT * FROM $_QLo0Q WHERE id=1";
     $_Q60l1 = mysql_query($_QJlJ0);
     if(mysql_num_rows($_Q60l1) > 0) {
       $_Q6Q1C = mysql_fetch_array($_Q60l1);
       mysql_free_result($_Q60l1);
       unset($_Q6Q1C["id"]);
       unset($_Q6Q1C["Name"]);
       $ML = array_merge($ML, $_Q6Q1C);
     }
     // load default text END

    }
  }

  $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE'";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_QfoQo = _OP81D($_QJCJi, '<TABLE:ROW>', '</TABLE:ROW>');
  $_Q6ICj = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
     $_Q6ICj .= $_QfoQo;
     $_Q6ICj = str_replace('FIELDDESCRIPTION', "$_Q6Q1C[text]", $_Q6ICj);
     $_Q6ICj = str_replace('FIELDNAME', "$_Q6Q1C[fieldname]", $_Q6ICj);
  }

  $_QJCJi = _OPR6L($_QJCJi, '<TABLE:ROW>', '</TABLE:ROW>', $_Q6ICj);
  $_QJCJi = _OPFJA($errors, $ML, $_QJCJi);

  print $_QJCJi;

  function _OFCA1(&$_IolII, $_Qi8If) {
    global $_QLo0Q, $_I01C0, $_I01lt;

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM $_QLo0Q";
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
    if($_IolII == 0) {

      $_QJlJ0 = "INSERT INTO $_QLo0Q (CreateDate) VALUES(NOW())";
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()");
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_IolII = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }


    $_QJlJ0 = "UPDATE $_QLo0Q SET ";
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
           $_I1l61[] = "`$key`="._OPQLR(rtrim($_Qi8If[$key]) );
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
    $_QJlJ0 .= " WHERE id=$_IolII";
    $_Q60l1 = mysql_query($_QJlJ0);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }

  }

?>

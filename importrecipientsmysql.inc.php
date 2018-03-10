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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("newslettersubunsub_ops.inc.php");
  include_once("dbimporthelper.inc.php");

  if( !isset($_POST["step"]) ) {
    include_once("importrecipients.php");
    exit;
  }

  if(!function_exists("ImportErrorCheckOnShutdown")) {
    function ImportErrorCheckOnShutdown(){
     if(!function_exists("error_get_last")) return;
     $_Q8C08 = error_get_last();
     if(!$_Q8C08) return;

     if( !($_Q8C08["type"] == E_ERROR || $_Q8C08["type"] == E_RECOVERABLE_ERROR || $_Q8C08["type"] == E_USER_ERROR) ) return;

     $_j6oj1 = sprintf("Fatal PHP ERROR type=%d; message=%s; file=%s; line=%d", $_Q8C08["type"], $_Q8C08["message"], $_Q8C08["file"], $_Q8C08["line"]);
     print $_j6oj1;
    }
  }

  $_j6LLj = "importdb_errors.txt";
  $_j6ljC = $_jji0C.$_j6LLj;

  $_IjL1j = array();
  $_I0600 = "";

  if (isset($_POST["OneMailingListId"]) )
     $OneMailingListId = $_POST["OneMailingListId"];
     else {
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000054"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
       $OneMailingListId = $_POST["OneMailingListId"];
     }


  if(isset($OneMailingListId))
     $OneMailingListId = intval($OneMailingListId);
  if(isset($_POST["OneMailingListId"]))
     $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);

  if(!_OCJCC($OneMailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  if(!isset($_POST["MailingListName"])) {
    $_QJlJ0 = "SELECT Name FROM $_Q60QL WHERE id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_POST["MailingListName"] = $_Q6Q1C[0];
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
     $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000058"];
     $_POST["step"] = 2;
     $_IjL1j[] = "fields[u_EMail]";
   } else {
     if( !isset($_POST["fields"]["u_EMail"]) || $_POST["fields"]["u_EMail"] == "-1" ) {
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000059"];
       $_POST["step"] = 2;
       $_IjL1j[] = "fields[u_EMail]";
     }
   }
  }

  if(!isset($_POST["step"]) || $_POST["step"] == 0 ) {

    $errors = array();
    if(isset($_POST["DBType"]) && $_POST["DBType"] == "MSSQL" && !function_exists("mssql_connect")){
      $errors[] = "DBType";
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["NO_MSSQL_SUPPORT"];
    }

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_I0600, 'importrecipients_mysql', 'mysql_import1_snipped.htm');
    $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
    $_QJCJi = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QJCJi);

    $_IjLJt = _LQB6D("MySQLImportOptions");
    $_IjlJf = $_POST;
    if(isset($_IjlJf["step"]))
       unset($_IjlJf["step"]);
    if( $_IjLJt != "" ) {
     $_QllO8 = @unserialize($_IjLJt);
     // alles raus bis auf mysql
     if($_QllO8 !== false) {
       foreach($_QllO8 as $_I1i8O => $_I1L81) {
         if(strpos($_I1i8O, "MySQL") === false)
           if(strpos($_I1i8O, "DBType") === false)
              unset($_QllO8[$_I1i8O]);
       }
       if(isset($_QllO8["ImportSQLQuery"])) {
         $_QllO8["ImportSQLQuery"] = str_replace("10x39", "'", $_QllO8["ImportSQLQuery"]);
       }
       if( isset($_QllO8["MySQLServerName"]) && $_QllO8["DBType"] == "MSSQL" ) {
          $_QllO8["MySQLServerName"] = str_replace('/', "\\", $_QllO8["MySQLServerName"]);
       }
       $_IjlJf = array_merge($_IjlJf, $_QllO8);
     }
    }

    $_QJCJi = _OPFJA($errors, $_IjlJf, $_QJCJi);
    print $_QJCJi;
    exit;
  }elseif($_POST["step"] == 1 ) {
      if( isset($_POST['MySQLServerName']) && $_POST['MySQLServerName'] != "" && isset($_POST['MySQLUsername']) && $_POST['MySQLUsername'] != "" && isset($_POST['MySQLPassword']) && $_POST['MySQLPassword'] != "" && isset($_POST['MySQLDatabase']) && $_POST['MySQLDatabase'] != "" ) {

           $errors = array();

           $_I0i1t = 0;
           if($_POST["DBType"] == "MSSQL" && !function_exists("mssql_connect")) {
             $errors[] = "DBType";
             $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["NO_MSSQL_SUPPORT"];
           }
            else {
              $_I0i1t = db_connect ($_POST['MySQLServerName'], $_POST['MySQLUsername'], $_POST['MySQLPassword'], $_POST["DBType"], $_POST['MySQLDatabase'], !empty($_POST["IsUTF8"]));
            }
           if ($_I0i1t == 0) {
              if(count($errors) == 0)
                $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br />".db_error($_I0i1t, $_POST["DBType"]));
              $errors[] = "MySQLServerName";
              $errors[] = "MySQLUsername";
              $errors[] = "MySQLPassword";
              @db_close($_I0i1t, $_POST["DBType"]);
           }

           if ($_I0i1t !=0 && !db_select_db ($_POST['MySQLDatabase'], $_I0i1t, $_POST["DBType"])) {
             $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".$_POST['MySQLDatabase']."<br />".db_error($_I0i1t, $_POST["DBType"]));
             $errors[] = "MySQLDatabase";
           }

           if($_I0i1t != $_Q61I1) {
             if($_I0i1t != 0)
               db_close($_I0i1t, $_POST["DBType"]);
           } else {
             mysql_select_db (MySQLDBName, $_Q61I1);
           }
           if(count($errors) > 0) {
             $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_I0600, 'importrecipients_mysql', 'mysql_import1_snipped.htm');
             $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
             $_QJCJi = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QJCJi);
             $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);
             print $_QJCJi;
             exit;
           }

           $_IjLJt = _LQB6D("MySQLImportOptions");
           $_IjlJf = $_POST;
           unset($_IjlJf["step"]);
           if( $_IjLJt != "" ) {
            $_QllO8 = @unserialize($_IjLJt);
            // feldzuordnung rausnehmen, der rest muss bleiben
            if($_QllO8 !== false) {
              if( isset($_QllO8["MySQLServerName"]) && $_QllO8["DBType"] == "MSSQL" ) {
                 $_QllO8["MySQLServerName"] = str_replace('/', "\\", $_QllO8["MySQLServerName"]);
              }
              if(isset($_QllO8["DBType"]))
                unset($_QllO8["DBType"]);
              foreach($_QllO8 as $_I1i8O => $_I1L81) {
                if( !(strpos($_I1i8O, "fields") === false) || !(strpos($_I1i8O, "MySQL") === false) || !(strpos($_I1i8O, "DBType") === false) )
                   if($_I1i8O != "MySQLTableName")
                     unset($_QllO8[$_I1i8O]);
              }
              if(!isset($_QllO8["ExtImport"]))
                $_QllO8["ExtImport"] = 1;
              if(isset($_QllO8["ImportSQLQuery"])) {
                $_QllO8["ImportSQLQuery"] = str_replace("10x39", "'", $_QllO8["ImportSQLQuery"]);
              }
              $_IjlJf = array_merge($_IjlJf, $_QllO8);
            }
           }

           // zeige Step 2
           _OOEOA(array(), $_IjlJf);


        } else {

           $errors = array();
           if(!isset($_POST['MySQLServerName']) || $_POST['MySQLServerName'] == "")
              $errors[] = "MySQLServerName";
           if(!isset($_POST['MySQLUsername']) || $_POST['MySQLUsername'] == "")
              $errors[] = "MySQLUsername";
           if(!isset($_POST['MySQLPassword']) || $_POST['MySQLPassword'] == "")
              $errors[] = "MySQLPassword";
           if(!isset($_POST['MySQLDatabase']) || $_POST['MySQLDatabase'] == "")
              $errors[] = "MySQLDatabase";
           if(!isset($_POST['DBType']) || $_POST['DBType'] == "")
              $errors[] = "DBType";

           if(count($errors) > 0)
             $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
           $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_I0600, 'importrecipients_mysql', 'mysql_import1_snipped.htm');
           $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
           $_QJCJi = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QJCJi);

           $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

           print $_QJCJi;
           exit;
        }
  } elseif($_POST["step"] == 2 ) {

    $errors = array();

    if ( !isset($_POST["PrevBtn"])) { // no check on prev btn
      if(!isset($_POST["MySQLTableName"]) || trim($_POST["MySQLTableName"]) == "") {
       $errors[] = "MySQLTableName";
      }
      if(!isset($_POST["ExtImport"]) || trim($_POST["ExtImport"]) == "") {
       $errors[] = "ExtImport";
      }
      if(isset($_POST["ExtImport"]) && $_POST["ExtImport"] == 2 && trim($_POST["ImportSQLQuery"]) == "") {
       $errors[] = "ImportSQLQuery";
      }
    } else {
      _OOEOA($errors, $_POST);
      exit;
    }

    if(count($errors) > 0 ) {
      _OOEOA($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["000020"]);
      exit;
    }

    $_I0i1t = _OODBQ($errors, $_I0600, $_POST["DBType"], !empty($_POST["IsUTF8"]));
    if(!$_I0i1t) {
      _OOEOA($errors, $_POST, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000390"], $_POST["MySQLServerName"]));
      exit;
    }

    $_Q8otJ = array();
    if($_POST["ExtImport"] == 2){
      $_POST["ImportSQLQuery"] = str_replace('"', "'", $_POST["ImportSQLQuery"]);
      $_Q60l1 = db_query($_POST["ImportSQLQuery"], $_I0i1t, $_POST["DBType"]);
      if(db_error($_I0i1t, $_POST["DBType"]) != "") {
        _OOEOA($errors, $_POST, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000391"], db_error($_I0i1t, $_POST["DBType"])));
        exit;
      }

      if(!$_Q60l1 || db_num_rows($_Q60l1, $_POST["DBType"]) == 0) {
        _OOEOA($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["MySQLQueryEmptyResult"]);
        exit;
      }

      $_Q6llo=0;
      while ($_Q6llo < db_num_fields($_Q60l1, $_POST["DBType"])) {
        $_I1OQ8 = db_fetch_field($_Q60l1, $_Q6llo, $_POST["DBType"]);
        if($_I1OQ8) {
          $_Q8otJ[]=$_I1OQ8->name;
        }
        $_Q6llo++;
      }
      db_free_result($_Q60l1, $_POST["DBType"]);
    }

    if(count($_Q8otJ) == 0 && $_POST["DBType"] == "MSSQL" && $_POST["ExtImport"] != 2){

      $_Q60l1 = db_query("SELECT * FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = '$_POST[MySQLTableName]'", $_I0i1t, $_POST["DBType"]);

      if(!$_Q60l1 || db_num_rows($_Q60l1, $_POST["DBType"]) == 0) {
        _OOEOA($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["MySQLQueryEmptyResult"]);
        exit;
      }

      while($_Q6Q1C = db_fetch_assoc($_Q60l1, $_POST["DBType"])){
        $_Q8otJ[] = $_Q6Q1C["COLUMN_NAME"];
      }
      db_free_result($_Q60l1, $_POST["DBType"]);

    }

    if(count($_Q8otJ) == 0 && $_POST["DBType"] == "MYSQL")
      _OAJCF($_I0i1t, $_POST["MySQLTableName"], $_Q8otJ);
    if($_I0i1t != $_Q61I1) {
      db_close($_I0i1t, $_POST["DBType"]);
    } else {
      mysql_select_db (MySQLDBName, $_Q61I1);
    }

    $_QfoQo = "";
    for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++)
      $_QfoQo .= sprintf('<option value="%s">%s</option>'.$_Q6JJJ, $_Q6llo, htmlentities($_Q8otJ[$_Q6llo], ENT_COMPAT, $_Q6QQL)  );
    $_QfoQo = $_Q6JJJ.sprintf('<option value="%s">%s</option>'.$_Q6JJJ, "-1", "--"  ).$_Q6JJJ.$_QfoQo;

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_I0600, 'importrecipients_mysql', 'mysql_import3_snipped.htm');

    $_I1OLj = _OP81D($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>");
    $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    $_Q6ICj = "";
    $_Q6llo=1;
    $_QL8Q8=1;
    $_I1oot = 0;
    while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
      if($_Q6llo == 1)
         $_Q66jQ = $_I1OLj;
      $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', '<label for="importfield'.$_QL8Q8.'">'.$_Q6Q1C["text"]."</label>", $_Q66jQ);
      $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="fields['.$_Q6Q1C["fieldname"].']" size="1" id="importfield'.$_QL8Q8.'">'.$_QfoQo.'</select>', $_Q66jQ);
      if($_Q6Q1C["fieldname"] == "u_Birthday")
        $_I1oot = $_Q6llo;
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
            $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="BirthdayDateFormat" size="1" id="importfield'.$_QL8Q8.'">'.$_QllO8.'</select>', $_Q66jQ);
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
            $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="BirthdayDateFormat" size="1" id="importfield'.$_QL8Q8.'">'.$_QllO8.'</select>', $_Q66jQ);
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
    if(isset($_POST["step"]))
      unset($_POST["step"]);

    // ************** Groups START
    $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=$OneMailingListId";
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

    $_IjLJt = _LQB6D("MySQLImportOptions");
    $_IjlJf = $_POST;
    if( $_IjLJt != "" ) {
       $_QllO8 = @unserialize($_IjLJt);
       if($_QllO8 !== false) {
         // alles rausnehmen, nur die feldzuordnung bleibt
         foreach($_QllO8 as $_I1i8O => $_I1L81) {
           if(strpos($_I1i8O, "fields") === false)
              unset($_QllO8[$_I1i8O]);
              else
              if(is_array($_I1L81)) // bug while saving
                unset($_QllO8[$_I1i8O]);
         }
         $_IjlJf = array_merge($_IjlJf, $_QllO8);
       }
    }

    if(!isset($_IjlJf["GroupsOption"]) || $_IjlJf["GroupsOption"] <= 0)
       $_IjlJf["GroupsOption"] = 1;
    if(!isset($_IjlJf["ExImportOption"]) || $_IjlJf["ExImportOption"] <= 0)
       $_IjlJf["ExImportOption"] = 1;
    $_QJCJi = _OPFJA($_IjL1j, $_IjlJf, $_QJCJi);

    print $_QJCJi;

  } elseif($_POST["step"] == 3 ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], "", 'importrecipients_mysql', 'mysql_import4_snipped.htm');

    if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 3 && !isset($_POST["groups_id"]) ) {
      $_POST["GroupsOption"] = 1;
    }

    if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && (!isset($_POST["ImportGroupField"]) || intval($_POST["ImportGroupField"]) < 0) ) {
      $_POST["GroupsOption"] = 1;
    }

    // save fields
    //
    unset($_POST["step"]);


    $_IJ0Q8 = "";
    $_I16jJ = $_POST["fields"];
    reset($_I16jJ);
    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
        $_IJ0Q8 .= '<input type="hidden" name="fields['.$_I1i8O.']" value="'.$_I1L81.'" />';
      }
    }

    $_IjLJt = $_POST;
    unset($_IjLJt["OneMailingListId"]);
    unset($_IjLJt["MailingListName"]);
    if(isset($_IjLJt["PrevBtn"]))
      unset($_IjLJt["PrevBtn"]);
    if(isset($_IjLJt["NextBtn"]))
      unset($_IjLJt["NextBtn"]);

    // umwandeln, damit er es wieder findet
    unset($_IjLJt["fields"]);
    $_I16jJ = $_POST["fields"];
    reset($_I16jJ);
    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
        $_IjLJt["fields[$_I1i8O]"] = $_I1L81;
      }
    }


    $_IjLJt["MySQLTableName"] = $_POST["MySQLTableName"];
    $_IjLJt["MySQLServerName"] = str_replace("\\", '/', $_IjLJt["MySQLServerName"]);
    if(isset($_IjLJt["ImportSQLQuery"])) {
      $_QllO8 = $_IjLJt;
      $_QllO8["ImportSQLQuery"] = str_replace("'", "10x".ord("'"), $_QllO8["ImportSQLQuery"]);
      $_QllO8 = serialize($_QllO8);
    }else
      $_QllO8 = serialize($_IjLJt);
    _LQC66("MySQLImportOptions", $_QllO8 );

    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    $_QJCJi = _OPR6L($_QJCJi, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IJ0Q8);

    print $_QJCJi;
  } elseif($_POST["step"] == 4 ) {

    if ( defined("DEMO") ) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], "", 'DISABLED', 'demo_snipped.htm');
      print $_QJCJi;
      exit;
    }

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], "", 'importrecipients_mysql', 'mysql_import5_snipped.htm');

    $_jf1Lf = _LQDLR("ECGListCheck");

    $_J8I0L = 0;
    $_IJ0t1 = 0;
    $_Q6i6i = 0;
    if(isset($_POST["ImportRowCount"]))
       $_IJ0t1 += $_POST["ImportRowCount"];

    $_I0i1t = _OODBQ($errors, $_I0600, $_POST["DBType"], !empty($_POST["IsUTF8"]));

    if($_POST["ExtImport"] != 2){
      $_QJlJ0 = "SELECT COUNT(*) FROM $_POST[MySQLTableName]";
      $_Q60l1 = db_query($_QJlJ0, $_I0i1t, $_POST["DBType"]);
      $_QLjff = db_fetch_row($_Q60l1, $_POST["DBType"]);
      db_free_result($_Q60l1, $_POST["DBType"]);
      $_QLjff = $_QLjff[0];
    } else{
      $_QJlJ0 = $_POST["ImportSQLQuery"];
      $_Q60l1 = db_query($_QJlJ0, $_I0i1t, $_POST["DBType"]);
      $_QLjff = db_num_rows($_Q60l1, $_POST["DBType"]);
      db_free_result($_Q60l1, $_POST["DBType"]);
    }
    $_J8I0L = $_QLjff;

    $_QJlJ0 = "SELECT * FROM $_POST[MySQLTableName]";
    if($_POST["ExtImport"] == 2)
      $_QJlJ0 = $_POST["ImportSQLQuery"];

    $_jfJ8L = db_query($_QJlJ0, $_I0i1t, $_POST["DBType"]);

    $_jf6jf = false;
    // spring zur fileposition
    if(isset($_POST["tableposition"])) {
        if(!empty($_POST["CreateErrorLog"])){
          $_jf0il = @fopen($_j6ljC, "a");
        }

        $_J8IoO = intval($_POST["tableposition"]);
        if($_J8IoO >= 0) {
          $_jf6jf = !@db_data_seek($_jfJ8L, $_J8IoO, $_POST["DBType"]);
        }

        // sind wir fertig?
        if($_J8IoO == -1 || $_jf6jf  ) {
          if($_I0i1t != $_Q61I1) {
            db_close($_I0i1t, $_POST["DBType"]);
          } else {
            mysql_select_db (MySQLDBName, $_Q61I1);
          }
          $_I0600 = "";
          $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_I0600, 'importrecipients_mysql', 'mysql_import6_snipped.htm');
          $_QJCJi = _OPR6L($_QJCJi, "<IMPORT:FILECOUNT>", "</IMPORT:FILECOUNT>", $_J8I0L);
          $_QJCJi = _OPR6L($_QJCJi, "<IMPORT:IMPORTCOUNT>", "</IMPORT:IMPORTCOUNT>", $_IJ0t1);
          if(empty($_POST["CreateErrorLog"]))
             $_QJCJi = _OP6PQ($_QJCJi, "<IF:ERRORLOG>", "</IF:ERRORLOG>");
             else{
               $_QJCJi = str_replace("ERROR_LOGFILENAME", $_jjCtI."export/".$_j6LLj, $_QJCJi);
               $_QJCJi = str_replace("<IF:ERRORLOG>", "", $_QJCJi);
               $_QJCJi = str_replace("</IF:ERRORLOG>", "", $_QJCJi);
             }
          $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
          print $_QJCJi;
          exit;
        }

      }
      else { // noch nie angefasst
        if(!empty($_POST["CreateErrorLog"])){
          $_jf0il = @fopen($_j6ljC, "w");
        }
        $_Q6i6i = 0;
        $_J8IoO = 0;
      }

    if(!isset($_POST["start"]) ) {
      $_IJQQI = $_Q6i6i;
    } else {
      $_IJQQI = $_POST["start"];
      unset($_POST["start"]);
    }

    unset($_POST["step"]);

    $_IJ0Q8 = "";
    $_I16jJ = $_POST["fields"];
    reset($_I16jJ);
    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {
        $_IJ0Q8 .= '<input type="hidden" name="fields['.$_I1i8O.']" value="'.$_I1L81.'" />';
      }
    }
    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    $_QJCJi = _OPR6L($_QJCJi, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IJ0Q8);

    $_IJQJ8 = substr($_QJCJi, 0, strpos($_QJCJi, "<BLOCK />") - 1);
    $_QJCJi = substr($_QJCJi, strpos($_QJCJi, "<BLOCK />") + strlen("<BLOCK />"));

    // progress
    if($_QLjff > 0)
      $_Q6i6i = sprintf("%d", $_J8IoO * 100 / $_QLjff );
    // progressbar macht bei 0 mist
    if($_Q6i6i == 0)
      $_Q6i6i = 1;
    $_IJQJ8 = _OPR6L($_IJQJ8, "<SHOW:PERCENT>", "</SHOW:PERCENT>", $_Q6i6i);

    print $_IJQJ8;
    flush();

    $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=".$_POST["OneMailingListId"];
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_jf1jt = $_Q6Q1C["MaillistTableName"];
    $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
    $_Q6t6j = $_Q6Q1C["GroupsTableName"];
    $_QLI8o = $_Q6Q1C["FormsTableName"];
    $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
    $FormId = $_Q6Q1C["forms_id"];
    $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];
    $_jf1J1 = $_Q6Q1C["LocalDomainBlocklistTableName"];
    mysql_free_result($_Q60l1);
    $_Ql00j = $_Q6Q1C;

    if(isset($_POST["SendOptInEMail"]) && $_POST["SendOptInEMail"] != "" ) {
      $_QJlJ0 = "SELECT $_QLI8o.*, $_QLo0Q.*, $_Q880O.Theme FROM $_QLI8o LEFT JOIN $_QLo0Q ON $_QLo0Q.id=$_QLI8o.messages_id LEFT JOIN $_Q880O ON $_Q880O.id=$_QLI8o.ThemesId WHERE $_QLI8o.id=$FormId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_j6ioL["MailingListId"] = $_Ql00j["id"];
      $_j6ioL["FormId"] = $FormId;
    }

    register_shutdown_function('ImportErrorCheckOnShutdown');

    // hier liest er die Zeilen
    for($_Q6llo=$_IJQQI; ($_Q6llo<$_IJQQI + $_POST["ImportLines"]) && !$_jf6jf; $_Q6llo++) {

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
      $_IJ1QJ = db_fetch_array($_jfJ8L, $_POST["DBType"]);
      if($_IJ1QJ === false) {
        $_jf6jf = true;
        continue;
      }

      // UTF8?
      if ( empty($_POST["IsUTF8"]) ) {
         reset($_IJ1QJ);
         foreach ($_IJ1QJ as $key => $_Q6ClO) {
            $_jffIC = utf8_encode($_Q6ClO);
            if($_jffIC != "")
              $_IJ1QJ[$key] = $_jffIC;
         }
      }

      $_Qf1i1 = $_IJ1QJ;

      $_jfQio = array();
      if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 ) {
        if($_POST["ImportGroupField"] < count($_Qf1i1)) {
           $_jfQio = $_Qf1i1[$_POST["ImportGroupField"]];
           $_jfQLi = ",";
           $_jfQio = explode($_jfQLi, $_jfQio);
           for($_jfI01=0; $_jfI01<count($_jfQio); $_jfI01++)
             $_jfQio[$_jfI01] = str_replace(",", "", $_jfQio[$_jfI01]);

        }
      }

      $_QJlJ0 = "INSERT IGNORE INTO $_jf1jt SET DateOfSubscription=NOW()";

      $_jfIfl = false;
      $_jfI81 = "";
      if(isset($_POST["SendOptInEMail"]) && $_POST["SendOptInEMail"] != "" ) {
        $_jfI81 = "'OptInConfirmationPending'";
        $_QJlJ0 .= ", SubscriptionStatus=$_jfI81";
        $_jfIfl = true;
        }
        else {
          $_jfI81 = "'Subscribed'";
          $_QJlJ0 .= ", SubscriptionStatus=$_jfI81, DateOfOptInConfirmation=NOW(), IPOnSubscription='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."'";
        }

      $_I16jJ = $_POST["fields"];
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
        if(isset($_POST["RemoveSpaces"]) && $_POST["RemoveSpaces"] != "") {
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
            if($_POST["BirthdayDateFormat"] == "dd.mm.yyyy") {
              $_Io01I = explode(".", $_I1L81);
              while(count($_Io01I) < 3)
                 $_Io01I[] = "f";
              $_IOJ8I = $_Io01I[0];
              $_Io0t6 = $_Io01I[1];
              $_Io0l8 = $_Io01I[2];
            } else
              if($_POST["BirthdayDateFormat"] == "yyyy-mm-dd") {
               $_Io01I = explode("-", $_I1L81);
               while(count($_Io01I) < 3)
                  $_Io01I[] = "f";
               $_IOJ8I = $_Io01I[2];
               $_Io0t6 = $_Io01I[1];
               $_Io0l8 = $_Io01I[0];
              } else
                if($_POST["BirthdayDateFormat"] == "mm-dd-yyyy") {
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
            _ODPR6( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressIncorrect"], $_I1L81) );
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
         _ODPR6( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorNoEMailAddress"], $_jfIi1) );
         $_QJlJ0 = "";
      }

      if($_QJlJ0 != "")
        $_QJlJ0 .= ", ".join(", ", $_ILQL0);

      $_jfIl8 = false;
      if($_QJlJ0 != "") {

        $_jfjC6 = substr($_jfIi1, strpos($_jfIi1, '@') + 1);

        if(_L0FRD($_jfIi1)) {
          $_jfIl8 = true;
          _ODPR6( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalBlockList"], $_jfIi1) );
        }

        if(!$_jfIl8 && _L101P($_jfIi1, $_POST["OneMailingListId"], $_ItCCo)) {
          $_jfIl8 = true;
          _ODPR6( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalBlockList"], $_jfIi1) );
        }

        if(!$_jfIl8 && _L1ROL($_jfjC6)) {
          $_jfIl8 = true;
          _ODPR6( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInGlobalDomainBlockList"], $_jfIi1) );
        }

        if(!$_jfIl8 && _L1RDP($_jfjC6, $_POST["OneMailingListId"], $_jf1J1)) {
          $_jfIl8 = true;
          _ODPR6( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInLocalDomainBlockList"], $_jfIi1) );
        }

        if(!$_jfIl8 && ($_jf1Lf && _OC0DR($_jfIi1)) ) {
          $_jfIl8 = true;
          _ODPR6( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorEMailAddressInECGList"], $_jfIi1) );
        }

      }

      if($_jfIl8 && empty($_POST["ImportBlockedRecipients"]))
        $_QJlJ0 = "";

      if($_QJlJ0 != "") {
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        if(!empty($_POST["CreateErrorLog"]) && $_jf0il && mysql_affected_rows($_Q61I1) == 0){
           _ODPR6( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImportErrorDuplicateEntry"], $_jfIi1) );
        }
      }
      if($_QJlJ0 != "" && mysql_affected_rows($_Q61I1) > 0 ) {
        $_IJ0t1++;

        // statistics
        $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
        $_Q6Q1C=mysql_fetch_array($_Q60l1);
        $_QLitI = $_Q6Q1C[0];
        mysql_free_result($_Q60l1);

        $_QJlJ0 = "INSERT INTO $_QlIf6 SET ActionDate=NOW(), Action=$_jfI81, Member_id=$_QLitI";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);

        // **** apply groups_id to member_id START
        if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 3 && isset($_POST["groups_id"]) && $_POST["groups_id"] > 0 ) {
          $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=".intval($_POST["groups_id"]).", Member_id=$_QLitI";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
        }
        // **** apply groups_id to member_id END

        // **** import groups names and create it if not exists START
        if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && isset($_jfQio) && count($_jfQio) > 0 ) {
          for($_jfJj0 = 0; $_jfJj0<count($_jfQio); $_jfJj0++) {
            if(trim($_jfQio[$_jfJj0]) == "") continue;
            $_QJlJ0 = "INSERT IGNORE INTO $_Q6t6j SET CreateDate=NOW(), Name="._OPQLR(trim($_jfQio[$_jfJj0]));
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
            if(mysql_affected_rows($_Q61I1) > 0 ) {
              $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
              $_Q6Q1C=mysql_fetch_array($_Q60l1);
              $_I1JQt = $_Q6Q1C[0];
              mysql_free_result($_Q60l1);
            } else {
              $_QJlJ0 = "SELECT id FROM $_Q6t6j WHERE Name="._OPQLR(trim($_jfQio[$_jfJj0]));
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              _OAL8F($_QJlJ0);
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
              _OAL8F($_QJlJ0);
            }
          }

          $_jfQio = array();
        }
        // **** import groups names and create it if not exists END

        if($_jfIfl && $_jfIi1 != "" && !$_jfIl8) {
          _OPQ6J();
          _L0RLJ("subscribeconfirm", $_QLitI, $_Ql00j, $_j6ioL, $errors, $_Ql1O8);
          _OPQ6J();
        }

      } // if(mysql_affected_rows($_Q61I1) > 0 )
        else
        if($_QJlJ0 != "" && isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] > 0 && isset($_POST["ExImportOption"]) && $_POST["ExImportOption"] != 2 ) { // member exists, add to groups or update
          $_QJlJ0 = "SELECT id FROM $_jf1jt WHERE u_EMail="._OPQLR($_jfIi1);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          if(mysql_num_rows($_Q60l1) > 0) {
            $_Q6Q1C=mysql_fetch_array($_Q60l1);
            mysql_free_result($_Q60l1);
            $_QLitI = $_Q6Q1C["id"];

            # Remove from all groups
            if(!empty($_POST["RemoveRecipientFromGroups"]))
              _L1LLB(array($_QLitI), $_POST["OneMailingListId"], $_QLI68);

            if($_POST["ExImportOption"] == 3){
              // Update recipient
              // no empty values
              for($_Q6llo=0; $_Q6llo<count($_ILQL0); $_Q6llo++){
                if(strpos($_ILQL0[$_Q6llo], '""') !== false || strpos($_ILQL0[$_Q6llo], '"0000-00-00"') !== false) {
                  unset($_ILQL0[$_Q6llo]);
                }
              }
              $_QJlJ0 = "UPDATE $_jf1jt SET ".join(", ", $_ILQL0)." WHERE id=$_QLitI";
              mysql_query($_QJlJ0, $_Q61I1);
              _OAL8F($_QJlJ0);
            }

            // **** apply groups_id to member_id START
            if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 3 && isset($_POST["groups_id"]) && $_POST["groups_id"] > 0 ) {
              $_QJlJ0 = "SELECT * FROM $_QLI68 WHERE groups_id=".intval($_POST["groups_id"])." AND Member_id=$_QLitI";
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              if(mysql_num_rows($_Q60l1) == 0) {
                if($_Q60l1)
                  mysql_free_result($_Q60l1);
                $_QJlJ0 = "INSERT INTO $_QLI68 SET groups_id=".intval($_POST["groups_id"]).", Member_id=$_QLitI";
                $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                _OAL8F($_QJlJ0);
                $_IJ0t1++;
              } else
                if($_Q60l1)
                  mysql_free_result($_Q60l1);
            }
           // **** apply groups_id to member_id END

           // **** import groups names and create it if not exists START
           if( isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2 && isset($_jfQio) && count($_jfQio) > 0 ) {
             for($_jfJj0=0; $_jfJj0<count($_jfQio); $_jfJj0++) {
               if(trim($_jfQio[$_jfJj0]) == "") continue;
               $_QJlJ0 = "INSERT IGNORE INTO $_Q6t6j SET CreateDate=NOW(), Name="._OPQLR(trim($_jfQio[$_jfJj0]));
               $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
               _OAL8F($_QJlJ0);
               if(mysql_affected_rows($_Q61I1) > 0 ) {
                 $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
                 $_Q6Q1C=mysql_fetch_array($_Q60l1);
                 $_I1JQt = $_Q6Q1C[0];
                 mysql_free_result($_Q60l1);
               } else {
                 $_QJlJ0 = "SELECT id FROM $_Q6t6j WHERE Name="._OPQLR(trim($_jfQio[$_jfJj0]));
                 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                 _OAL8F($_QJlJ0);
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
                   $_IJ0t1++;
                 } else
                   if($_Q60l1)
                     mysql_free_result($_Q60l1);
               }
             }
             $_jfQio = array();
           }
           // **** import groups names and create it if not exists END

          } # if(mysql_num_rows($_Q60l1) > 0)

        } // if($_QJlJ0 != "" && isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] > 0 )

    }

    $_IJQQI += $_POST["ImportLines"];
    $_J8IoO = $_IJQQI;
    if($_J8IoO > $_QLjff )
      $_J8IoO = -1;

    print '<input type="hidden" name="start" value="'.$_IJQQI.'" />';
    print '<input type="hidden" name="tableposition" value="'.$_J8IoO.'" />';
    print '<input type="hidden" name="RowCountDB" value="'.$_J8I0L.'" />';
    print '<input type="hidden" name="ImportRowCount" value="'.$_IJ0t1.'" />';

    if($_I0i1t != $_Q61I1) {
      db_close($_I0i1t, $_POST["DBType"]);
    } else {
      mysql_select_db (MySQLDBName, $_Q61I1);
    }

    if(!empty($_POST["CreateErrorLog"]) && $_jf0il){
      fclose($_jf0il);
    }

    print $_QJCJi;
    flush();
  }


  function _OOEOA($errors, $_I1l66, $_I0600 = "") {
    global $resourcestrings, $INTERFACE_LANGUAGE, $OneMailingListId, $_Q6JJJ, $UserType, $Username, $_Q61I1;
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000054"], $_I0600, 'importrecipients_mysql', 'mysql_import2_snipped.htm');

    if(isset($_I1l66["step"]))
      unset($_I1l66["step"]);

    if(!isset($_I1l66["ExtImport"]))
      $_I1l66["ExtImport"] = 1;

    $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
    $_QJCJi = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QJCJi);

    if(isset($_I1l66["MailingListName"]))
      unset($_I1l66["MailingListName"]);

    if (!isset($_I1l66["ImportLines"]) )
       $_QJCJi = str_replace('name="ImportLines"', 'name="ImportLines" value="200"', $_QJCJi);
    if (!isset($_I1l66["RemoveSpaces"]) )
       $_QJCJi = str_replace('name="RemoveSpaces"', 'name="RemoveSpaces" checked="checked"', $_QJCJi);

    $_I16oJ = "";
    $_I0i1t = _OODBQ($errors, $_I0600, $_POST["DBType"], !empty($_POST["IsUTF8"]));

    if( $_I0i1t != 0 ) {
      $_I1fiC = db_get_tables($_POST['MySQLDatabase'], $_I0i1t, $_POST["DBType"]);
      for ($_Q6llo = 0; $_Q6llo < count($_I1fiC); $_Q6llo++) {
         $_I16oJ .= '<option value="'.$_I1fiC[$_Q6llo].'">'.$_I1fiC[$_Q6llo].'</option>'.$_Q6JJJ;
      }
      if($_I0i1t != $_Q61I1) {
        db_close($_I0i1t, $_POST["DBType"]);
      } else {
        mysql_select_db (MySQLDBName, $_Q61I1);
      }
    } else{
      @db_close($_I0i1t, $_POST["DBType"]);
    }
    $_QJCJi = _OPR6L($_QJCJi, "<OPTION:MYSQLTABLENAME>", "</OPTION:MYSQLTABLENAME>", $_I16oJ);

    $_QJCJi = _OPFJA($errors, $_I1l66, $_QJCJi);

    print $_QJCJi;

  }

  function _OODBQ(&$errors, &$_I0600, $_I0C11, $_I0ift) {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;
    $_I0i1t = db_connect ($_POST['MySQLServerName'], $_POST['MySQLUsername'], $_POST['MySQLPassword'], $_I0C11, $_POST['MySQLDatabase'], $_I0ift);
    if ($_I0i1t == 0) {
       $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br/>".db_error($_I0i1t, $_I0C11));
       $errors[] = "MySQLServerName";
       $errors[] = "MySQLUsername";
       $errors[] = "MySQLPassword";
       @db_close($_I0i1t, $_POST["DBType"]);
    }

    if (!db_select_db ($_POST['MySQLDatabase'], $_I0i1t, $_I0C11)) {
      $_I0600 = ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".$_POST['MySQLDatabase']."<br/>".db_error($_I0i1t, $_I0C11));
      $errors[] = "MySQLDatabase";
      if($_I0i1t != $_Q61I1) {
        db_close($_I0i1t, $_POST["DBType"]);
      } else {
        mysql_select_db (MySQLDBName, $_Q61I1);
      }
      $_I0i1t = 0;
    }
    return $_I0i1t;
  }

  function _ODPR6($_I11oJ) {
   global $_jf0il, $_Q6JJJ;
   if(!empty($_POST["CreateErrorLog"]) && $_jf0il){
     fwrite($_jf0il, $_I11oJ.$_Q6JJJ);
   }
  }

?>

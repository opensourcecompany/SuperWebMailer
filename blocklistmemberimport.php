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

  $_IjL1j = array();
  $_I0600 = "";
  $_IjfjI = $_Ql8C0;
  $_IjfLj = true;

  if ( (isset($_POST["action"]) && $_POST["action"] == "local") || (isset($_POST["Action"]) && $_POST["Action"] == "local") ||
       (isset($_GET["action"]) && $_GET["action"] == "local")  || (isset($_GET["Action"]) && $_GET["Action"] == "local")
     ) {
         if (! isset($_POST["OneMailingListId"]) && ! isset($_GET["OneMailingListId"]) ) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000140"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
           $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
         }

         if(isset($_GET["OneMailingListId"]) && !isset($_POST["OneMailingListId"]) )
            $_POST["OneMailingListId"] = intval($_GET["OneMailingListId"]);

         if(!_OCJCC($_POST["OneMailingListId"])){
           $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
           $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
           print $_QJCJi;
           exit;
         }

         // get local blocklist
         $_QJlJ0 = "SELECT LocalBlocklistTableName, Name FROM $_Q60QL WHERE id=".intval($_POST["OneMailingListId"]);
         $_Q60l1 = mysql_query($_QJlJ0);
         $_IjfLj = false;
         if(mysql_num_rows($_Q60l1) > 0) {
           $_Q6Q1C = mysql_fetch_row($_Q60l1);
           mysql_free_result($_Q60l1);
           $_IjfjI = $_Q6Q1C[0];
           $_IjOJC = $_Q6Q1C[1];
         } else {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000140"];
           include_once("mailinglistselect.inc.php");
           exit;
         }
  }

  if(isset($_GET["action"]))
     $_POST["action"] = $_GET["action"];
  if(isset($_GET["Action"]))
     $_POST["Action"] = $_GET["Action"];
  if(isset($_POST["Action"]))
    $_POST["action"] = $_POST["Action"];

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

  if($_IjfLj)
   $_Iji86 = $resourcestrings[$INTERFACE_LANGUAGE]["000139"];
   else
   $_Iji86 = $_IjOJC." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000140"];

  if(!isset($_POST["step"])) {

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, "", 'blocklistmemberimport', 'blocklistimport1_snipped.htm');

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

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
       $_QJCJi = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QJCJi);
       $_QJCJi = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
       $_QJCJi = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
       $action = "";
       if(isset($_POST["action"]))
          $action = $_POST["action"];
       $_QJCJi = str_replace('name="action"', 'name="action" value="'.$action.'"', $_QJCJi);
       $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QJCJi);
    }

    print $_QJCJi;
    exit;
  } elseif($_POST["step"] == 1 ) {
      if( isset( $_FILES['file1'] ) && $_FILES['file1']['tmp_name'] != "" && $_FILES['file1']['name'] != "" ) {
        // upload akzeptieren
        if (move_uploaded_file($_FILES['file1']['tmp_name'], $_I0lQJ.$_FILES['file1']['name'])){
           @chmod($_I0lQJ.$_FILES['file1']['name'], 0777);
           $_POST["ImportFilename"] = $_FILES['file1']['name'];
        } else {


           $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000055"], $_FILES['file1']['tmp_name'], $_I0lQJ.$_FILES['file1']['name'] ), 'blocklistmemberimport', 'blocklistimport1_snipped.htm');
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

           $errors[] = "file1";
           $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

           if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
              $_QJCJi = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QJCJi);
              $_QJCJi = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
              $_QJCJi = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
           }

           print $_QJCJi;
           exit;
        }
      }

      $_IjLJt = _LQB6D("BlockListImportOptions");
      $_IjlJf = $_POST;
      unset($_IjlJf["step"]);
      unset($_IjlJf["action"]);
      if( $_IjLJt != "" ) {
       $_QllO8 = unserialize($_IjLJt);
       // feldzuordnung rausnehmen, der rest muss bleiben
       foreach($_QllO8 as $_I1i8O => $_I1L81) {
         if(!(strpos($_I1i8O, "fields") === false))
            unset($_QllO8[$_I1i8O]);
       }
       $_IjlJf = array_merge($_IjlJf, $_QllO8);
      }

      // zeige Step 2
      _OL086(array(), $_IjlJf);

  } elseif($_POST["step"] == 2 ) {

    $errors = array();

    if ( !isset($_POST["PrevBtn"])) { // no check on prev btn
      if(!isset($_POST["ImportFilename"]) || trim($_POST["ImportFilename"]) == "") {
       $errors[] = "ImportFilename";
      }
      if(!isset($_POST["Separator"]) || trim($_POST["Separator"]) == "") {
       $errors[] = "Separator";
      }
    } else {
      _OL086($errors, $_POST);
      exit;
    }

    if(count($errors) > 0 ) {
      _OL086($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["000020"]);
      exit;
    }

    $_Q6lfJ = fopen($_I0lQJ.$_POST["ImportFilename"], "r");
    if(!$_Q6lfJ) {
      _OL086($errors, $_POST, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000056"], $_I0lQJ.$_POST["ImportFilename"]));
      exit;
    }

    while (!feof($_Q6lfJ)) {
      $_QL8Q8 = fgetc($_Q6lfJ);
      if($_QL8Q8 == chr(10) || $_QL8Q8 == chr(13)) break;
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


    $_Q8otJ = explode(trim($_POST["Separator"]), $_I1t66);
    $_QfoQo = "";
    for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++)
      $_QfoQo .= sprintf('<option value="%s">%s</option>'.$_Q6JJJ, $_Q6llo, htmlentities($_Q8otJ[$_Q6llo], ENT_COMPAT, $_Q6QQL)  );
    $_QfoQo = $_Q6JJJ.sprintf('<option value="%s">%s</option>'.$_Q6JJJ, "-1", "--"  ).$_Q6JJJ.$_QfoQo;

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, $_I0600, 'blocklistmemberimport', 'blocklistimport3_snipped.htm');

    $_I1OLj = _OP81D($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>");

    // only email field
    $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE' AND fieldname='u_EMail'";
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    $_Q66jQ = $_I1OLj;
    $_Q6llo=1; // only first field
    $_Q66jQ = str_replace('<!--FIELD'.$_Q6llo.'-->', $_Q6Q1C["text"], $_Q66jQ);
    $_Q66jQ = str_replace('<!--VALUE'.$_Q6llo.'-->', '<select name="fields['.$_Q6Q1C["fieldname"].']" size="1">'.$_QfoQo.'</select>', $_Q66jQ);

    $_Q6ICj = "";
    if($_Q66jQ != "")
      $_Q6ICj .= $_Q66jQ;
    $_QJCJi = _OPR6L($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>", $_Q6ICj);
    if(isset($_POST["step"]))
      unset($_POST["step"]);



    $_IjLJt = _LQB6D("BlockListImportOptions");
    $_IjlJf = $_POST;
    if( $_IjLJt != "" ) {
       $_QllO8 = unserialize($_IjLJt);
       // alles rausnehmen, nur die feldzuordnung bleibt
       foreach($_QllO8 as $_I1i8O => $_I1L81) {
         if(strpos($_I1i8O, "fields") === false)
            unset($_QllO8[$_I1i8O]);
       }
       $_IjlJf = array_merge($_IjlJf, $_QllO8);
    }


    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
       $_QJCJi = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QJCJi);
       $_QJCJi = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
       $_QJCJi = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
    }

    $_QJCJi = _OPFJA($_IjL1j, $_IjlJf, $_QJCJi);

    print $_QJCJi;

  } elseif($_POST["step"] == 3 ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, "", 'blocklistmemberimport', 'blocklistimport4_snipped.htm');

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
    unset($_IjLJt["action"]);
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


    _LQC66("BlockListImportOptions", serialize($_IjLJt));

    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    $_QJCJi = _OPR6L($_QJCJi, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IJ0Q8);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
       $_QJCJi = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QJCJi);
       $_QJCJi = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
       $_QJCJi = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
    }

    print $_QJCJi;
  } elseif($_POST["step"] == 4 ) {
    if ( defined("DEMO") ) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, "", 'DISABLED', 'demo_snipped.htm');
      print $_QJCJi;
      exit;
    }

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, "", 'blocklistmemberimport', 'blocklistimport5_snipped.htm');

    $_IJ0Jo = 0;
    $_IJ0t1 = 0;
    if(isset($_POST["RowCount"]))
       $_IJ0Jo += $_POST["RowCount"];
    if(isset($_POST["ImportRowCount"]))
       $_IJ0t1 += $_POST["ImportRowCount"];

    $_QCC8C = fopen($_I0lQJ.$_POST["ImportFilename"], "r");
    $_QLjff = fstat($_QCC8C);

    // spring zur fileposition
    if(isset($_POST["fileposition"])) {
        $_IJ0tL = $_POST["fileposition"];
        if($_IJ0tL >= 0)
           fseek($_QCC8C, $_IJ0tL);

        // sind wir fertig?
        if($_IJ0tL == -1 || feof($_QCC8C) || ftell($_QCC8C) >= $_QLjff["size"] ) {
          fclose($_QCC8C);
          $_I0600 = "";
          if( isset($_POST["RemoveFile"]) && $_POST["RemoveFile"] != "" ) {
            if( !@unlink($_I0lQJ.$_POST["ImportFilename"]) ) {
              $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000060"], $_I0lQJ.$_POST["ImportFilename"]);
            }
          }
          $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, $_I0600, 'blocklistmemberimport', 'blocklistimport6_snipped.htm');
          $_QJCJi = _OPR6L($_QJCJi, "<IMPORT:FILECOUNT>", "</IMPORT:FILECOUNT>", $_IJ0Jo);
          $_QJCJi = _OPR6L($_QJCJi, "<IMPORT:IMPORTCOUNT>", "</IMPORT:IMPORTCOUNT>", $_IJ0t1);
          $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

          if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
             $_QJCJi = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QJCJi);
             $_QJCJi = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
             $_QJCJi = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
          }

          print $_QJCJi;
          exit;
        }

      }
      else { // noch nie angefasst

        // UTF8 BOM test
        $_I1O0j = 'ï»¿';
        $_IJ1QJ = fread($_QCC8C, strlen($_I1O0j));
        if($_IJ1QJ != $_I1O0j)
          fseek($_QCC8C, 0);

        // Zeile 1 weg?
        $_Q6i6i = 0;
        if(isset($_POST["Header1Line"]) && $_POST["Header1Line"] != "" ) {
           $_IJ1QJ = fgets($_QCC8C, 4096);
           $_Q6i6i = 1;
        }
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
    if( $_QLjff["size"] > 0 )
      $_Q6i6i = sprintf("%d", ftell($_QCC8C) * 100 / $_QLjff["size"] );
    // progressbar macht bei 0 mist
    if($_Q6i6i == 0)
      $_Q6i6i = 1;
    $_IJQJ8 = _OPR6L($_IJQJ8, "<SHOW:PERCENT>", "</SHOW:PERCENT>", $_Q6i6i);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
       $_IJQJ8 = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_IJQJ8);
       $_IJQJ8 = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_IJQJ8);
       $_IJQJ8 = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_IJQJ8);
    }

    print $_IJQJ8;
    flush();

    // hier liest er die Zeilen
    for($_Q6llo=$_IJQQI; ($_Q6llo<$_IJQQI + $_POST["ImportLines"]) && !feof($_QCC8C); $_Q6llo++) {
      $_IJ0Jo++;
      $_IJ1QJ = fgets($_QCC8C, 4096);
      // UTF8?
      if ( !( isset($_POST["IsUTF8"]) && $_POST["IsUTF8"] != "") ) {
         $_QfoQo = utf8_encode($_IJ1QJ);
         if($_QfoQo != "")
           $_IJ1QJ = $_QfoQo;
      }

      $_Qf1i1 = explode($_POST["Separator"], $_IJ1QJ);

      $_QJlJ0 = "INSERT IGNORE INTO $_IjfjI SET ";

      $_I16jJ = $_POST["fields"];
      reset($_I16jJ);
      foreach($_I16jJ as $_I1i8O => $_IJQOL) {
        if($_IJQOL < 0) {continue;}
        if($_IJQOL < count($_Qf1i1))
           $_I1L81 = $_Qf1i1[$_IJQOL];
           else
           $_I1L81 = "";
        if(isset($_POST["RemoveQuotes"]) && $_POST["RemoveQuotes"] != "") {
          $_I1L81 = str_replace('"', '', $_I1L81);
          $_I1L81 = str_replace("\'", '', $_I1L81);
          $_I1L81 = str_replace("`", '', $_I1L81);
          $_I1L81 = str_replace("´", '', $_I1L81);
        }
        if(isset($_POST["RemoveSpaces"]) && $_POST["RemoveSpaces"] != "") {
          $_I1L81 = trim($_I1L81);
        }
        if(isset($_I16jJ[$_I1i8O]) && $_I1L81 != -1) {

          # no empty email addresses
          if($_I1i8O == "u_EMail" && (trim($_I1L81) == "" || !_OPAOJ($_I1L81) || strpos($_I1L81, "*") !== false || strpos($_I1L81, "?") !== false) ) {
            $_QJlJ0 = "";
            break;
          }

          $_QJlJ0 .= "$_I1i8O="._OPQLR($_I1L81);
        }
      } // foreach($_I16jJ as $_I1i8O => $_I1L81)

      if($_QJlJ0 != "") {
        $_Q60l1 = mysql_query($_QJlJ0);
        _OAL8F($_QJlJ0);
      }
      if($_QJlJ0 != "" && mysql_affected_rows($_Q61I1) > 0 ) {
        $_IJ0t1++;

      } // if(mysql_affected_rows($_Q61I1) > 0 )
    }

    $_IJQQI += $_POST["ImportLines"];
    $_IJ0tL = ftell($_QCC8C);
    if(feof($_QCC8C))
      $_IJ0tL = -1;

    print '<input type="hidden" name="start" value="'.$_IJQQI.'" />';
    print '<input type="hidden" name="fileposition" value="'.$_IJ0tL.'" />';
    print '<input type="hidden" name="RowCount" value="'.$_IJ0Jo.'" />';
    print '<input type="hidden" name="ImportRowCount" value="'.$_IJ0t1.'" />';

    fclose($_QCC8C);

    print $_QJCJi;
  }


  function _OL086($errors, $_I1l66, $_I0600 = "") {
    global $_I0lQJ, $resourcestrings, $INTERFACE_LANGUAGE, $OneMailingListId, $_Iji86;
    global $UserType, $Username, $_Q6JJJ;
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, $_I0600, 'blocklistmemberimport', 'blocklistimport2_snipped.htm');

    if(isset($_I1l66["step"]))
      unset($_I1l66["step"]);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
       $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QJCJi);
       $_QJCJi = str_replace('name="action"', 'name="action" value="'.$_POST["action"].'"', $_QJCJi);
       $_QJCJi = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QJCJi);
       $_QJCJi = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
       $_QJCJi = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
    }

    if (!isset($_I1l66["Separator"]) )
      $_QJCJi = str_replace('name="Separator"', 'name="Separator" value=","', $_QJCJi);
    if (!isset($_I1l66["ImportLines"]) )
       $_QJCJi = str_replace('name="ImportLines"', 'name="ImportLines" value="200"', $_QJCJi);
    if (!isset($_I1l66["Header1Line"]) )
       $_QJCJi = str_replace('name="Header1Line"', 'name="Header1Line" checked="checked"', $_QJCJi);
    if (!isset($_I1l66["RemoveQuotes"]) )
       $_QJCJi = str_replace('name="RemoveQuotes"', 'name="RemoveQuotes" checked="checked"', $_QJCJi);
    if (!isset($_I1l66["RemoveSpaces"]) )
       $_QJCJi = str_replace('name="RemoveSpaces"', 'name="RemoveSpaces" checked="checked"', $_QJCJi);

    $_I16oJ = "";
    $_QCC8C = opendir ( substr($_I0lQJ, 0, strlen($_I0lQJ) - 1) );
    while (false !== ($_Q6lfJ = readdir($_QCC8C))) {
      if (!is_dir($_I0lQJ.$_Q6lfJ) && $_Q6lfJ != "." && $_Q6lfJ != ".." && $_Q6lfJ != "index.php") {
        if( isset($_POST["ImportFilename"]) && ($_POST["ImportFilename"] == $_Q6lfJ || $_POST["ImportFilename"] == @utf8_decode($_Q6lfJ) ) )
          $_I16L6 = ' selected="selected"';
          else
          $_I16L6 = '';
        $_I16oJ .= '<option value="'.$_Q6lfJ.'"' . $_I16L6 . '>'.$_Q6lfJ.'</option>'.$_Q6JJJ;
      }
    }
    closedir($_QCC8C);
    $_QJCJi = _OPR6L($_QJCJi, "<OPTION:FILENAME>", "</OPTION:FILENAME>", $_I16oJ);

    $_QJCJi = _OPFJA($errors, $_I1l66, $_QJCJi);

    print $_QJCJi;

  }

?>

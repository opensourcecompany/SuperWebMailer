<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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

  $_Iio6I = array();
  $_Itfj8 = "";
  $_Ii6tC = $_I8tfQ;
  $_Ii6CO = true;

  if ( (isset($_POST["action"]) && $_POST["action"] == "local") || (isset($_POST["Action"]) && $_POST["Action"] == "local") ||
       (isset($_GET["action"]) && $_GET["action"] == "local")  || (isset($_GET["Action"]) && $_GET["Action"] == "local")
     ) {
         if (! isset($_POST["OneMailingListId"]) && ! isset($_GET["OneMailingListId"]) ) {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000140"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
           $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
         }

         if(isset($_GET["OneMailingListId"]) && !isset($_POST["OneMailingListId"]) )
            $_POST["OneMailingListId"] = intval($_GET["OneMailingListId"]);

         if(!_LAEJL($_POST["OneMailingListId"])){
           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
           $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
           print $_QLJfI;
           exit;
         }

         // get local blocklist
         $_QLfol = "SELECT LocalBlocklistTableName, Name FROM $_QL88I WHERE id=".intval($_POST["OneMailingListId"]);
         $_QL8i1 = mysql_query($_QLfol);
         $_Ii6CO = false;
         if(mysql_num_rows($_QL8i1) > 0) {
           $_QLO0f = mysql_fetch_row($_QL8i1);
           mysql_free_result($_QL8i1);
           $_Ii6tC = $_QLO0f[0];
           $_Ii8Q6 = $_QLO0f[1];
         } else {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000140"];
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
     $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000058"];
     $_POST["step"] = 2;
     $_Iio6I[] = "fields[u_EMail]";
   } else {
     if( !isset($_POST["fields"]["u_EMail"]) || $_POST["fields"]["u_EMail"] == "-1" ) {
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000059"];
       $_POST["step"] = 2;
       $_Iio6I[] = "fields[u_EMail]";
     }
   }
  }

  if($_Ii6CO)
   $_IiOfO = $resourcestrings[$INTERFACE_LANGUAGE]["000139"];
   else
   $_IiOfO = $_Ii8Q6." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000140"];

  if(!isset($_POST["step"])) {

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, "", 'blocklistmemberimport', 'blocklistimport1_snipped.htm');

    $_QLJfI = str_replace('/userfiles/import', $_ItL8f, $_QLJfI);
    $_It18j = ini_get("upload_max_filesize");
    if(!isset($_It18j) || $_It18j == "")
      $_It18j = "2M";
    if(!(strpos($_It18j, "G") === false))
       $_ItQIo = intval($_It18j) * 1024 * 1024 * 1024;
       else
       if(!(strpos($_It18j, "M") === false))
          $_ItQIo = intval($_It18j) * 1024 * 1024;
          else
          if(!(strpos($_It18j, "K") === false))
             $_ItQIo = intval($_It18j) * 1024;
             else
             $_ItQIo = intval($_It18j) * 1;
    if($_ItQIo == 0)
      $_ItQIo = 2 * 1024 * 1024;
    $_It18j .= "B";
    $_QLJfI = str_replace('upload_max_filesize', $_It18j, $_QLJfI);
    $_QLJfI = str_replace('name="MAX_FILE_SIZE"', 'name="MAX_FILE_SIZE" value="'.$_ItQIo.'"', $_QLJfI);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
       $_QLJfI = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QLJfI);
       $_QLJfI = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
       $_QLJfI = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
       $action = "";
       if(isset($_POST["action"]))
          $action = $_POST["action"];
       $_QLJfI = str_replace('name="action"', 'name="action" value="'.$action.'"', $_QLJfI);
       $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QLJfI);
    }

    print $_QLJfI;
    exit;
  } elseif($_POST["step"] == 1 ) {
      if( isset( $_FILES['file1'] ) && $_FILES['file1']['tmp_name'] != "" && $_FILES['file1']['name'] != "" ) {
        // upload akzeptieren
        if (move_uploaded_file($_FILES['file1']['tmp_name'], $_ItL8f.$_FILES['file1']['name'])){
           @chmod($_ItL8f.$_FILES['file1']['name'], 0777);
           $_POST["ImportFilename"] = $_FILES['file1']['name'];
        } else {


           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000055"], $_FILES['file1']['tmp_name'], $_ItL8f.$_FILES['file1']['name'] ), 'blocklistmemberimport', 'blocklistimport1_snipped.htm');
           $_QLJfI = str_replace('/userfiles/import', $_ItL8f, $_QLJfI);
           $_It18j = ini_get("upload_max_filesize");
           if(!isset($_It18j) || $_It18j == "")
             $_It18j = "2M";
           if(!(strpos($_It18j, "G") === false))
              $_ItQIo = intval($_It18j) * 1024 * 1024 * 1024;
              else
              if(!(strpos($_It18j, "M") === false))
                 $_ItQIo = intval($_It18j) * 1024 * 1024;
                 else
                 if(!(strpos($_It18j, "K") === false))
                    $_ItQIo = intval($_It18j) * 1024;
                    else
                    $_ItQIo = intval($_It18j) * 1;
           if($_ItQIo == 0)
             $_ItQIo = 2 * 1024 * 1024;
           $_It18j .= "B";
           $_QLJfI = str_replace('upload_max_filesize', $_It18j, $_QLJfI);
           $_QLJfI = str_replace('name="MAX_FILE_SIZE"', 'name="MAX_FILE_SIZE" value="'.$_ItQIo.'"', $_QLJfI);

           $errors[] = "file1";
           $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

           if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
              $_QLJfI = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QLJfI);
              $_QLJfI = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
              $_QLJfI = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
           }

           print $_QLJfI;
           exit;
        }
      }

      $_IiC0o = _JOO1L("BlockListImportOptions");
      $_IiCft = $_POST;
      unset($_IiCft["step"]);
      unset($_IiCft["action"]);
      if( $_IiC0o != "" ) {
       $_I016j = unserialize($_IiC0o);
       // feldzuordnung rausnehmen, der rest muss bleiben
       foreach($_I016j as $_IOLil => $_IOCjL) {
         if(!(strpos($_IOLil, "fields") === false))
            unset($_I016j[$_IOLil]);
       }
      if(isset($_I016j["ImportFilename"]) && isset($_IiCft["ImportFilename"]))
          unset($_I016j["ImportFilename"]);
       $_IiCft = array_merge($_IiCft, $_I016j);
      }

      // zeige Step 2
      _L0FRP(array(), $_IiCft);

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
      _L0FRP($errors, $_POST);
      exit;
    }

    if(count($errors) > 0 ) {
      _L0FRP($errors, $_POST, $resourcestrings[$INTERFACE_LANGUAGE]["000020"]);
      exit;
    }

    $_QlCtl = fopen($_ItL8f.$_POST["ImportFilename"], "r");
    if(!$_QlCtl) {
      _L0FRP($errors, $_POST, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000056"], $_ItL8f.$_POST["ImportFilename"]));
      exit;
    }

    while (!feof($_QlCtl)) {
      $_Ift08 = fgetc($_QlCtl);
      if($_Ift08 == chr(10) || $_Ift08 == chr(13)) break;
    }

    $_IOO6C = ftell($_QlCtl) -1;
    fseek($_QlCtl, 0);
    $_IOoJ0 = fread($_QlCtl, $_IOO6C);
    fclose($_QlCtl);

    // UTF8 BOM?
    $_IOC1j = 'ï»¿';
    $_QlOjt = strpos($_IOoJ0, $_IOC1j);
    if($_QlOjt !== false && $_QlOjt == 0)
      $_IOoJ0 = substr($_IOoJ0, strlen($_IOC1j));

    // UTF8?
    if ( !( isset($_POST["IsUTF8"]) && $_POST["IsUTF8"] != "") ) {
       $_I0Clj = utf8_encode($_IOoJ0);
       if($_I0Clj != "")
         $_IOoJ0 = $_I0Clj;
    }


    $_I1OoI = explode(trim($_POST["Separator"]), $_IOoJ0);
    $_I0Clj = "";
    for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++)
      $_I0Clj .= sprintf('<option value="%s">%s</option>'.$_QLl1Q, $_Qli6J, htmlentities($_I1OoI[$_Qli6J], ENT_COMPAT, $_QLo06)  );
    $_I0Clj = $_QLl1Q.sprintf('<option value="%s">%s</option>'.$_QLl1Q, "-1", "--"  ).$_QLl1Q.$_I0Clj;

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, $_Itfj8, 'blocklistmemberimport', 'blocklistimport3_snipped.htm');

    $_IOiJ0 = _L81DB($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>");

    // only email field
    $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE' AND fieldname='u_EMail'";
    $_QL8i1 = mysql_query($_QLfol);
    $_QLO0f = mysql_fetch_array($_QL8i1);
    $_Ql0fO = $_IOiJ0;
    $_Qli6J=1; // only first field
    $_Ql0fO = str_replace('<!--FIELD'.$_Qli6J.'-->', $_QLO0f["text"], $_Ql0fO);
    $_Ql0fO = str_replace('<!--VALUE'.$_Qli6J.'-->', '<select name="fields['.$_QLO0f["fieldname"].']" size="1">'.$_I0Clj.'</select>', $_Ql0fO);

    $_QLoli = "";
    if($_Ql0fO != "")
      $_QLoli .= $_Ql0fO;
    $_QLJfI = _L81BJ($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>", $_QLoli);
    if(isset($_POST["step"]))
      unset($_POST["step"]);



    $_IiC0o = _JOO1L("BlockListImportOptions");
    $_IiCft = $_POST;
    if( $_IiC0o != "" ) {
       $_I016j = unserialize($_IiC0o);
       // alles rausnehmen, nur die feldzuordnung bleibt
       foreach($_I016j as $_IOLil => $_IOCjL) {
         if(strpos($_IOLil, "fields") === false)
            unset($_I016j[$_IOLil]);
       }
       if(isset($_I016j["ImportFilename"]) && isset($_IiCft["ImportFilename"]))
          unset($_I016j["ImportFilename"]);
       $_IiCft = array_merge($_IiCft, $_I016j);
    }


    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
       $_QLJfI = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QLJfI);
       $_QLJfI = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
       $_QLJfI = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
    }

    $_QLJfI = _L8AOB($_Iio6I, $_IiCft, $_QLJfI);

    print $_QLJfI;

  } elseif($_POST["step"] == 3 ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, "", 'blocklistmemberimport', 'blocklistimport4_snipped.htm');

    // save fields
    //
    unset($_POST["step"]);


    $_IiCfO = "";
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_IiCfO .= '<input type="hidden" name="fields['.$_IOLil.']" value="'.$_IOCjL.'" />';
      }
    }

    $_IiC0o = $_POST;
    unset($_IiC0o["OneMailingListId"]);
    unset($_IiC0o["action"]);
    if(isset($_IiC0o["PrevBtn"]))
      unset($_IiC0o["PrevBtn"]);
    if(isset($_IiC0o["NextBtn"]))
      unset($_IiC0o["NextBtn"]);

    // umwandeln, damit er es wieder findet
    unset($_IiC0o["fields"]);
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_IiC0o["fields[$_IOLil]"] = $_IOCjL;
      }
    }


    _JOOFF("BlockListImportOptions", serialize($_IiC0o));

    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IiCfO);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
       $_QLJfI = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QLJfI);
       $_QLJfI = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
       $_QLJfI = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
    }

    print $_QLJfI;
  } elseif($_POST["step"] == 4 ) {
    if ( defined("DEMO") ) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, "", 'DISABLED', 'demo_snipped.htm');
      print $_QLJfI;
      exit;
    }

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, "", 'blocklistmemberimport', 'blocklistimport5_snipped.htm');

    $_IiiJf = 0;
    $_Iii6I = 0;
    if(isset($_POST["RowCount"]))
       $_IiiJf += $_POST["RowCount"];
    if(isset($_POST["ImportRowCount"]))
       $_Iii6I += $_POST["ImportRowCount"];

    $_IJljf = fopen($_ItL8f.$_POST["ImportFilename"], "r");
    $_If61l = fstat($_IJljf);

    // spring zur fileposition
    if(isset($_POST["fileposition"])) {
        $_Iiioi = $_POST["fileposition"];
        if($_Iiioi >= 0)
           fseek($_IJljf, $_Iiioi);

        // sind wir fertig?
        if($_Iiioi == -1 || feof($_IJljf) || ftell($_IJljf) >= $_If61l["size"] ) {
          fclose($_IJljf);
          $_Itfj8 = "";
          if( isset($_POST["RemoveFile"]) && $_POST["RemoveFile"] != "" ) {
            if( !@unlink($_ItL8f.$_POST["ImportFilename"]) ) {
              $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000060"], $_ItL8f.$_POST["ImportFilename"]);
            }
          }
          $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, $_Itfj8, 'blocklistmemberimport', 'blocklistimport6_snipped.htm');
          $_QLJfI = _L81BJ($_QLJfI, "<IMPORT:FILECOUNT>", "</IMPORT:FILECOUNT>", $_IiiJf);
          $_QLJfI = _L81BJ($_QLJfI, "<IMPORT:IMPORTCOUNT>", "</IMPORT:IMPORTCOUNT>", $_Iii6I);
          $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

          if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
             $_QLJfI = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QLJfI);
             $_QLJfI = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
             $_QLJfI = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
          }

          print $_QLJfI;
          exit;
        }

      }
      else { // noch nie angefasst

        // UTF8 BOM test
        $_IOC1j = 'ï»¿';
        $_IiLOj = fread($_IJljf, strlen($_IOC1j));
        if($_IiLOj != $_IOC1j)
          fseek($_IJljf, 0);

        // Zeile 1 weg?
        $_QlOjt = 0;
        if(isset($_POST["Header1Line"]) && $_POST["Header1Line"] != "" ) {
           $_IiLOj = fgets($_IJljf, 4096);
           $_QlOjt = 1;
        }
      }

    if(!isset($_POST["start"]) ) {
      $_Iil6i = $_QlOjt;
    } else {
      $_Iil6i = $_POST["start"];
      unset($_POST["start"]);
    }

    unset($_POST["step"]);

    $_IiCfO = "";
    $_IOJoI = $_POST["fields"];
    reset($_IOJoI);
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {
        $_IiCfO .= '<input type="hidden" name="fields['.$_IOLil.']" value="'.$_IOCjL.'" />';
      }
    }
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IiCfO);

    $_IilfC = substr($_QLJfI, 0, strpos($_QLJfI, "<BLOCK />") - 1);
    $_QLJfI = substr($_QLJfI, strpos($_QLJfI, "<BLOCK />") + strlen("<BLOCK />"));

    // progress
    if( $_If61l["size"] > 0 )
      $_QlOjt = sprintf("%d", ftell($_IJljf) * 100 / $_If61l["size"] );
    // progressbar macht bei 0 mist
    if($_QlOjt == 0)
      $_QlOjt = 1;
    $_IilfC = _L81BJ($_IilfC, "<SHOW:PERCENT>", "</SHOW:PERCENT>", $_QlOjt);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
       $_IilfC = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_IilfC);
       $_IilfC = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_IilfC);
       $_IilfC = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_IilfC);
    }

    print $_IilfC;
    flush();

    // hier liest er die Zeilen
    for($_Qli6J=$_Iil6i; ($_Qli6J<$_Iil6i + $_POST["ImportLines"]) && !feof($_IJljf); $_Qli6J++) {
      $_IiiJf++;
      $_IiLOj = fgets($_IJljf, 4096);
      // UTF8?
      if ( !( isset($_POST["IsUTF8"]) && $_POST["IsUTF8"] != "") ) {
         $_I0Clj = utf8_encode($_IiLOj);
         if($_I0Clj != "")
           $_IiLOj = $_I0Clj;
      }

      $_I0QjQ = explode($_POST["Separator"], $_IiLOj);

      $_QLfol = "INSERT IGNORE INTO $_Ii6tC SET ";

      $_IOJoI = $_POST["fields"];
      reset($_IOJoI);
      foreach($_IOJoI as $_IOLil => $_Iiloo) {
        if($_Iiloo < 0) {continue;}
        if($_Iiloo < count($_I0QjQ))
           $_IOCjL = $_I0QjQ[$_Iiloo];
           else
           $_IOCjL = "";
        if(isset($_POST["RemoveQuotes"]) && $_POST["RemoveQuotes"] != "") {
          $_IOCjL = str_replace('"', '', $_IOCjL);
          $_IOCjL = str_replace("\'", '', $_IOCjL);
          $_IOCjL = str_replace("`", '', $_IOCjL);
          $_IOCjL = str_replace("´", '', $_IOCjL);
        }
        if(isset($_POST["RemoveSpaces"]) && $_POST["RemoveSpaces"] != "") {
          $_IOCjL = trim($_IOCjL);
        }
        if(isset($_IOJoI[$_IOLil]) && $_IOCjL != -1) {

          # no empty email addresses
          if($_IOLil == "u_EMail" && (trim($_IOCjL) == "" || !_L8JEL($_IOCjL) || strpos($_IOCjL, "*") !== false || strpos($_IOCjL, "?") !== false) ) {
            $_QLfol = "";
            break;
          } else{
            if($_IOLil == "u_EMail")
              $_IOCjL = _L86JE($_IOCjL);
          }

          $_QLfol .= "$_IOLil="._LRAFO($_IOCjL);
        }
      } // foreach($_IOJoI as $_IOLil => $_IOCjL)

      if($_QLfol != "") {
        $_QL8i1 = mysql_query($_QLfol);
        _L8D88($_QLfol);
      }
      if($_QLfol != "" && mysql_affected_rows($_QLttI) > 0 ) {
        $_Iii6I++;

      } // if(mysql_affected_rows($_QLttI) > 0 )
    }

    $_Iil6i += $_POST["ImportLines"];
    $_Iiioi = ftell($_IJljf);
    if(feof($_IJljf))
      $_Iiioi = -1;

    print '<input type="hidden" name="start" value="'.$_Iil6i.'" />';
    print '<input type="hidden" name="fileposition" value="'.$_Iiioi.'" />';
    print '<input type="hidden" name="RowCount" value="'.$_IiiJf.'" />';
    print '<input type="hidden" name="ImportRowCount" value="'.$_Iii6I.'" />';

    fclose($_IJljf);

    print $_QLJfI;
  }


  function _L0FRP($errors, $_Io0OJ, $_Itfj8 = "") {
    global $_ItL8f, $resourcestrings, $INTERFACE_LANGUAGE, $OneMailingListId, $_IiOfO;
    global $UserType, $Username, $_QLl1Q;
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, $_Itfj8, 'blocklistmemberimport', 'blocklistimport2_snipped.htm');

    if(isset($_Io0OJ["step"]))
      unset($_Io0OJ["step"]);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
       $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QLJfI);
       $_QLJfI = str_replace('name="action"', 'name="action" value="'.$_POST["action"].'"', $_QLJfI);
       $_QLJfI = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QLJfI);
       $_QLJfI = str_replace('blocklistmemberimport.php', 'blocklistmemberimport.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
       $_QLJfI = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
    }

    if (!isset($_Io0OJ["Separator"]) )
      $_QLJfI = str_replace('name="Separator"', 'name="Separator" value=","', $_QLJfI);
    if (!isset($_Io0OJ["ImportLines"]) )
       $_QLJfI = str_replace('name="ImportLines"', 'name="ImportLines" value="200"', $_QLJfI);
    if (!isset($_Io0OJ["Header1Line"]) )
       $_QLJfI = str_replace('name="Header1Line"', 'name="Header1Line" checked="checked"', $_QLJfI);
    if (!isset($_Io0OJ["RemoveQuotes"]) )
       $_QLJfI = str_replace('name="RemoveQuotes"', 'name="RemoveQuotes" checked="checked"', $_QLJfI);
    if (!isset($_Io0OJ["RemoveSpaces"]) )
       $_QLJfI = str_replace('name="RemoveSpaces"', 'name="RemoveSpaces" checked="checked"', $_QLJfI);

    $_IO6iJ = "";
    $_IJljf = opendir ( substr($_ItL8f, 0, strlen($_ItL8f) - 1) );
    while (false !== ($_QlCtl = readdir($_IJljf))) {
      if (!is_dir($_ItL8f.$_QlCtl) && $_QlCtl != "." && $_QlCtl != ".." && $_QlCtl != "index.php") {
        if( isset($_POST["ImportFilename"]) && ($_POST["ImportFilename"] == $_QlCtl || $_POST["ImportFilename"] == @utf8_decode($_QlCtl) ) )
          $_IOfJi = ' selected="selected"';
          else
          $_IOfJi = '';
        $_IO6iJ .= '<option value="'.$_QlCtl.'"' . $_IOfJi . '>'.$_QlCtl.'</option>'.$_QLl1Q;
      }
    }
    closedir($_IJljf);
    $_QLJfI = _L81BJ($_QLJfI, "<OPTION:FILENAME>", "</OPTION:FILENAME>", $_IO6iJ);

    $_QLJfI = _L8AOB($errors, $_Io0OJ, $_QLJfI);

    print $_QLJfI;

  }

?>

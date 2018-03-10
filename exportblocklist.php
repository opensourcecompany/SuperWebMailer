<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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

  $_jLOo1 = "exportblocklist.txt";
  $_IjfjI = $_Ql8C0;
  $_IjfLj = true;

  if ( (isset($_POST["action"]) && $_POST["action"] == "local") || (isset($_POST["Action"]) && $_POST["Action"] == "local") ||
       (isset($_GET["action"]) && $_GET["action"] == "local")  || (isset($_GET["Action"]) && $_GET["Action"] == "local")
     ) {
         if (! isset($_POST["OneMailingListId"]) && ! isset($_GET["OneMailingListId"]) ) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000142"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
         }

         if(isset($_GET["OneMailingListId"]) && !isset($_POST["OneMailingListId"]) )
            $_POST["OneMailingListId"] = $_GET["OneMailingListId"];

         $OneMailingListId = intval($_POST["OneMailingListId"]);

         if(!_OCJCC($OneMailingListId)){
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
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000142"];
           include_once("mailinglistselect.inc.php");
           exit;
         }
  }

  if(!empty($_POST["OneMailingListId"]))
     $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);

  if(isset($_GET["action"]))
     $_POST["action"] = $_GET["action"];
  if(isset($_GET["Action"]))
     $_POST["Action"] = $_GET["Action"];
  if(isset($_POST["Action"]))
    $_POST["action"] = $_POST["Action"];

  if($_IjfLj)
   $_Iji86 = $resourcestrings[$INTERFACE_LANGUAGE]["000141"];
   else
   $_Iji86 = $_IjOJC." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000142"];

  if(!isset($_POST["ExportLines"]))
    $_POST["ExportLines"] = 100;
  $_POST["ExportLines"] = intval($_POST["ExportLines"]);


  if(isset($_POST["step"]) && $_POST["step"] == 3 && isset($_POST["RemoveExportFileBtn"])) {
    $_jLCf6 = "";
    if($_Q6lfJ = fopen($_jji0C.$_jLOo1, "w") )
       fclose($_Q6lfJ);
       else
       $_jLCf6 = "Can't open file ".$_jji0C.$_jLOo1;

    if(!unlink($_jji0C.$_jLOo1))
       $_jLCf6 .= "Can't remove file ".$_jji0C.$_jLOo1;
    unset($_POST["step"]);
    if($_jLCf6 == "")
       $_jLCf6 = "OK";

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, "", 'browseblmembers', 'blocklistexport4_snipped.htm');
    $_QJCJi = str_replace("[ERRORTEXT]", $_jLCf6, $_QJCJi);
    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    print $_QJCJi;

    exit;
  }

  if(!isset($_POST["step"])) {

    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, "", 'browseblmembers', 'blocklistexport1_snipped.htm');

    $_QJCJi = str_replace('/userfiles/export', $_jji0C, $_QJCJi);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
       $_QJCJi = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QJCJi);
       $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QJCJi);
       $action = "";
       if(isset($_POST["action"]))
          $action = $_POST["action"];
       $_QJCJi = str_replace('name="action"', 'name="action" value="'.$action.'"', $_QJCJi);
       $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QJCJi);
    }

    print $_QJCJi;
    exit;
  }

  if( isset($_POST["NextBtn"]) )
    $_POST["step"]++;
  if($_POST["step"] == 2) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, "", 'browseblmembers', 'blocklistexport2_snipped.htm');

    $_IJ0Jo = 0;
    $_jLCff = 0;
    if(isset($_POST["RowCount"]))
       $_IJ0Jo += $_POST["RowCount"];
    if(isset($_POST["ExportRowCount"]))
       $_jLCff += $_POST["ExportRowCount"];

    $_QJlJ0 = "SELECT COUNT(*) FROM $_IjfjI";
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);

    $_QCC8C = 0;

    if(!isset($_POST["start"]))
      { // noch nie angefasst
        $_QCC8C = fopen($_jji0C.$_jLOo1, "w");
        fwrite($_QCC8C, "EMail".$_Q6JJJ );
      }

    if(!$_QCC8C)
      $_QCC8C = fopen($_jji0C.$_jLOo1, "a");

    if(!isset($_POST["start"]) ) {
      $_IJQQI = 0;
    } else {
      $_IJQQI = $_POST["start"];
      unset($_POST["start"]);
    }

    unset($_POST["step"]);

    $_IJ0Q8 = "";
    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    $_QJCJi = _OPR6L($_QJCJi, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IJ0Q8);

    $_IJQJ8 = substr($_QJCJi, 0, strpos($_QJCJi, "<BLOCK />") - 1);
    $_QJCJi = substr($_QJCJi, strpos($_QJCJi, "<BLOCK />") + strlen("<BLOCK />"));

    // progress
    if($_I6Qfj > 0)
      $_Q6i6i = sprintf("%d", $_IJQQI * 100 / $_I6Qfj );
      else
      $_Q6i6i = 0;
    // progressbar macht bei 0 mist
    if($_Q6i6i == 0)
      $_Q6i6i = 1;
    $_IJQJ8 = _OPR6L($_IJQJ8, "<SHOW:PERCENT>", "</SHOW:PERCENT>", $_Q6i6i);

    $_ILQL0 = "u_EMail";

    $_QJlJ0 = "SELECT $_ILQL0, id FROM $_IjfjI ORDER BY id LIMIT $_IJQQI, ".intval($_POST["ExportLines"]);
    $_Q60l1 = mysql_query($_QJlJ0);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
        fclose($_QCC8C);
        $_I0600 = "";
        $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, $_I0600, 'browseblmembers', 'blocklistexport3_snipped.htm');
        $_QJCJi = _OPR6L($_QJCJi, "<EXPORT:FILECOUNT>", "</EXPORT:FILECOUNT>", $_I6Qfj);
        $_QJCJi = str_replace("DOWNLOAD", $_jjCtI."export/".$_jLOo1, $_QJCJi);
        $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
        print $_QJCJi;
        exit;
    }

    // output here, if eof() than do nothing
    print $_IJQJ8;
    flush();

    $_POST["Separator"] = ";";
    $_IflL6 = 0;
    while($_Q6Q1C = mysql_fetch_row($_Q60l1)) {
      _OPQ6J();
      if(isset($_POST["AddQuotes"]) && $_POST["AddQuotes"] != "") {
        for($_Q6llo=0; $_Q6llo<count($_Q6Q1C) - 1 /* -1 id field */; $_Q6llo++)
           $_Q6Q1C[$_Q6llo] = _OPQJR($_Q6Q1C[$_Q6llo]);
      } else {
        unset($_Q6Q1C[count($_Q6Q1C) - 1]); /* -1 id field */
      }
      fwrite($_QCC8C, join($_POST["Separator"], $_Q6Q1C).$_Q6JJJ );
      $_IflL6++;
    }

    mysql_free_result($_Q60l1);

    $_IJQQI += $_IflL6;
    $_jLCff += $_IflL6;

    print '<input type="hidden" name="start" value="'.$_IJQQI.'" />';
    print '<input type="hidden" name="ExportRowCount" value="'.$_jLCff.'" />';

    fclose($_QCC8C);

    print $_QJCJi;
  }

?>

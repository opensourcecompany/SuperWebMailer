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

  $_Jljf6 = "exportblocklist.txt";
  $_Ii6tC = $_I8tfQ;
  $_Ii6CO = true;

  if ( (isset($_POST["action"]) && $_POST["action"] == "local") || (isset($_POST["Action"]) && $_POST["Action"] == "local") ||
       (isset($_GET["action"]) && $_GET["action"] == "local")  || (isset($_GET["Action"]) && $_GET["Action"] == "local")
     ) {
         if (! isset($_POST["OneMailingListId"]) && ! isset($_GET["OneMailingListId"]) ) {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000142"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
         }

         if(isset($_GET["OneMailingListId"]) && !isset($_POST["OneMailingListId"]) )
            $_POST["OneMailingListId"] = $_GET["OneMailingListId"];

         $OneMailingListId = intval($_POST["OneMailingListId"]);

         if(!_LAEJL($OneMailingListId)){
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
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000142"];
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

  if($_Ii6CO)
   $_IiOfO = $resourcestrings[$INTERFACE_LANGUAGE]["000141"];
   else
   $_IiOfO = $_Ii8Q6." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000142"];

  if(!isset($_POST["ExportLines"]))
    $_POST["ExportLines"] = 100;
  $_POST["ExportLines"] = intval($_POST["ExportLines"]);


  if(isset($_POST["step"]) && $_POST["step"] == 3 && isset($_POST["RemoveExportFileBtn"])) {
    $_JlJLO = "";
    if($_QlCtl = fopen($_J1t6J.$_Jljf6, "w") )
       fclose($_QlCtl);
       else
       $_JlJLO = "Can't open file ".$_J1t6J.$_Jljf6;

    if(!unlink($_J1t6J.$_Jljf6))
       $_JlJLO .= "Can't remove file ".$_J1t6J.$_Jljf6;
    unset($_POST["step"]);
    if($_JlJLO == "")
       $_JlJLO = "OK";

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, "", 'browseblmembers', 'blocklistexport4_snipped.htm');
    $_QLJfI = str_replace("[ERRORTEXT]", $_JlJLO, $_QLJfI);
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    print $_QLJfI;

    exit;
  }

  if(!isset($_POST["step"])) {

    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, "", 'browseblmembers', 'blocklistexport1_snipped.htm');

    $_QLJfI = str_replace('/userfiles/export', $_J1t6J, $_QLJfI);

    if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
       $_QLJfI = str_replace('exportblocklist.php', 'exportblocklist.php?action=local&OneMailingListId='.$_POST["OneMailingListId"], $_QLJfI);
       $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QLJfI);
       $action = "";
       if(isset($_POST["action"]))
          $action = $_POST["action"];
       $_QLJfI = str_replace('name="action"', 'name="action" value="'.$action.'"', $_QLJfI);
       $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QLJfI);
    }

    print $_QLJfI;
    exit;
  }

  if( isset($_POST["NextBtn"]) )
    $_POST["step"]++;
  if($_POST["step"] == 2) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, "", 'browseblmembers', 'blocklistexport2_snipped.htm');

    $_IiiJf = 0;
    $_Jl6OO = 0;
    if(isset($_POST["RowCount"]))
       $_IiiJf += $_POST["RowCount"];
    if(isset($_POST["ExportRowCount"]))
       $_Jl6OO += $_POST["ExportRowCount"];

    $_QLfol = "SELECT COUNT(*) FROM $_Ii6tC";
    $_QL8i1 = mysql_query($_QLfol);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    $_IlQll = $_QLO0f[0];
    mysql_free_result($_QL8i1);

    $_IJljf = 0;

    if(!isset($_POST["start"]))
      { // noch nie angefasst
        $_IJljf = fopen($_J1t6J.$_Jljf6, "w");
        fwrite($_IJljf, "EMail".$_QLl1Q );
      }

    if(!$_IJljf)
      $_IJljf = fopen($_J1t6J.$_Jljf6, "a");

    if(!isset($_POST["start"]) ) {
      $_Iil6i = 0;
    } else {
      $_Iil6i = $_POST["start"];
      unset($_POST["start"]);
    }

    unset($_POST["step"]);

    $_IiCfO = "";
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    $_QLJfI = _L81BJ($_QLJfI, '<HIDDEN:FIELDS>', '</HIDDEN:FIELDS>', $_IiCfO);

    $_IilfC = substr($_QLJfI, 0, strpos($_QLJfI, "<BLOCK />") - 1);
    $_QLJfI = substr($_QLJfI, strpos($_QLJfI, "<BLOCK />") + strlen("<BLOCK />"));

    // progress
    if($_IlQll > 0)
      $_QlOjt = sprintf("%d", $_Iil6i * 100 / $_IlQll );
      else
      $_QlOjt = 0;
    // progressbar macht bei 0 mist
    if($_QlOjt == 0)
      $_QlOjt = 1;
    $_IilfC = _L81BJ($_IilfC, "<SHOW:PERCENT>", "</SHOW:PERCENT>", $_QlOjt);

    $_jtiJJ = "u_EMail";

    $_QLfol = "SELECT $_jtiJJ, id FROM $_Ii6tC ORDER BY id LIMIT $_Iil6i, ".intval($_POST["ExportLines"]);
    $_QL8i1 = mysql_query($_QLfol);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
        fclose($_IJljf);
        $_Itfj8 = "";
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, $_Itfj8, 'browseblmembers', 'blocklistexport3_snipped.htm');
        $_QLJfI = _L81BJ($_QLJfI, "<EXPORT:FILECOUNT>", "</EXPORT:FILECOUNT>", $_IlQll);
        $_QLJfI = str_replace("DOWNLOAD", $_jfOJj."export/".$_Jljf6, $_QLJfI);
        $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
        print $_QLJfI;
        exit;
    }

    // output here, if eof() than do nothing
    print $_IilfC;
    flush();

    $_POST["Separator"] = ";";
    $_j1881 = 0;
    while($_QLO0f = mysql_fetch_row($_QL8i1)) {
      _LRCOC();
      if(isset($_POST["AddQuotes"]) && $_POST["AddQuotes"] != "") {
        for($_Qli6J=0; $_Qli6J<count($_QLO0f) - 1 /* -1 id field */; $_Qli6J++)
           $_QLO0f[$_Qli6J] = _LRBC0($_QLO0f[$_Qli6J]);
      } else {
        unset($_QLO0f[count($_QLO0f) - 1]); /* -1 id field */
      }
      fwrite($_IJljf, join($_POST["Separator"], $_QLO0f).$_QLl1Q );
      $_j1881++;
    }

    mysql_free_result($_QL8i1);

    $_Iil6i += $_j1881;
    $_Jl6OO += $_j1881;

    print '<input type="hidden" name="start" value="'.$_Iil6i.'" />';
    print '<input type="hidden" name="ExportRowCount" value="'.$_Jl6OO.'" />';

    fclose($_IJljf);

    print $_QLJfI;
  }

?>

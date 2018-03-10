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
  include_once("templates.inc.php");
  include_once("sessioncheck.inc.php");

  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];

  if(isset($_GET["IsFCKEditor"]))
    $_POST["_IsFCKEditor"] = $_GET["IsFCKEditor"];

  $_QJCJi = join("", file(_O68QF()."loadfile.htm"));

  $_QJCJi = str_replace ('name="cmbUploaderUrl"', 'value="'.BasePath.'ckeditor/filemanager/connectors/php/uploadgetcontents.php"', $_QJCJi);


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

  $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);


  if(isset($_POST["_FORMNAME"]) && $_POST["_FORMNAME"] != "null" && $_POST["_FORMNAME"] != "")
    $_QJCJi = str_replace("'FORMNAME'", "'".$_POST["_FORMNAME"]."'", $_QJCJi);
    else
      $_QJCJi = _OP6PQ($_QJCJi, "<HAS_SOURCEELEMENT>", "</HAS_SOURCEELEMENT>");

  if(isset($_POST["_FORMFIELD"]) && $_POST["_FORMFIELD"] != "null" && $_POST["_FORMFIELD"] != "") {
      $_QJCJi = str_replace('.FORMFIELD', ".".$_POST["_FORMFIELD"], $_QJCJi);
      $_QJCJi = str_replace('<HAS_SOURCEELEMENT>', '', $_QJCJi);
      $_QJCJi = str_replace('</HAS_SOURCEELEMENT>', '', $_QJCJi);
    }
    else
    $_QJCJi = _OP6PQ($_QJCJi, "<HAS_SOURCEELEMENT>", "</HAS_SOURCEELEMENT>");

  if (!isset($_POST["_IsFCKEditor"]) || $_POST["_IsFCKEditor"] == "false" ) {
     $_QJCJi = str_replace('<ISNOTFCKEDITOR>', '', $_QJCJi);
     $_QJCJi = str_replace('</ISNOTFCKEDITOR>', '', $_QJCJi);
     $_QJCJi = _OP6PQ($_QJCJi, "<ISFCKEDITOR>", "</ISFCKEDITOR>");
   }
    else {
      $_QJCJi = _OP6PQ($_QJCJi, "<ISNOTFCKEDITOR>", "</ISNOTFCKEDITOR>");
      $_QJCJi = str_replace('<ISFCKEDITOR>', '', $_QJCJi);
      $_QJCJi = str_replace('</ISFCKEDITOR>', '', $_QJCJi);
    }

  if(isset($_POST["_FORMFIELD"]))
    $_QJCJi = str_replace('"SourceCKEditor"', '"'.$_POST["_FORMFIELD"].'"', $_QJCJi);

  if( !ini_get("allow_url_fopen") ) {
    $_I6ICC = "  ShowItem('FileFromInternet', false);\r\n";
    $_QJCJi = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_QJCJi);
  }

  SetHTMLHeaders($_Q6QQL);

  print $_QJCJi;
?>

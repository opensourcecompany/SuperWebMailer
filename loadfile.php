<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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

  $_QLJfI = _JJAQE("loadfile.htm");

  $_QLJfI = str_replace ('name="cmbUploaderUrl"', 'value="'.BasePath.'ckeditor/filemanager/connectors/php/uploadgetcontents.php"', $_QLJfI);


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

  $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);


  if(isset($_POST["_FORMNAME"]) && $_POST["_FORMNAME"] != "null" && $_POST["_FORMNAME"] != "")
    $_QLJfI = str_replace("'FORMNAME'", "'".$_POST["_FORMNAME"]."'", $_QLJfI);
    else
      $_QLJfI = _L80DF($_QLJfI, "<HAS_SOURCEELEMENT>", "</HAS_SOURCEELEMENT>");

  if(isset($_POST["_FORMFIELD"]) && $_POST["_FORMFIELD"] != "null" && $_POST["_FORMFIELD"] != "") {
      $_QLJfI = str_replace('.FORMFIELD', ".".$_POST["_FORMFIELD"], $_QLJfI);
      $_QLJfI = str_replace('<HAS_SOURCEELEMENT>', '', $_QLJfI);
      $_QLJfI = str_replace('</HAS_SOURCEELEMENT>', '', $_QLJfI);
    }
    else
    $_QLJfI = _L80DF($_QLJfI, "<HAS_SOURCEELEMENT>", "</HAS_SOURCEELEMENT>");

  if (!isset($_POST["_IsFCKEditor"]) || $_POST["_IsFCKEditor"] == "false" ) {
     $_QLJfI = str_replace('<ISNOTFCKEDITOR>', '', $_QLJfI);
     $_QLJfI = str_replace('</ISNOTFCKEDITOR>', '', $_QLJfI);
     $_QLJfI = _L80DF($_QLJfI, "<ISFCKEDITOR>", "</ISFCKEDITOR>");
   }
    else {
      $_QLJfI = _L80DF($_QLJfI, "<ISNOTFCKEDITOR>", "</ISNOTFCKEDITOR>");
      $_QLJfI = str_replace('<ISFCKEDITOR>', '', $_QLJfI);
      $_QLJfI = str_replace('</ISFCKEDITOR>', '', $_QLJfI);
    }

  if(isset($_POST["_FORMFIELD"]))
    $_QLJfI = str_replace('"SourceCKEditor"', '"'.$_POST["_FORMFIELD"].'"', $_QLJfI);

  if( !ini_get("allow_url_fopen") ) {
    $_Iljoj = "  ShowItem('FileFromInternet', false);\r\n";
    $_QLJfI = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLJfI);
  }

  SetHTMLHeaders($_QLo06);

  print $_QLJfI;
?>

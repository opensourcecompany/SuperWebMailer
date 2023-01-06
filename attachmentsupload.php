<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

  if( !isset($_GET["form"]) || !isset($_GET["formElement1"]) )
    exit;

  $_QLJfI = _JJAQE("attachmentsupload.htm");

  $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

  $_QLJfI = str_replace ("'FORMNAME'", "'$_GET[form]'", $_QLJfI);
  $_QLJfI = str_replace ("FORM1FIELD", "$_GET[formElement1]", $_QLJfI);
  $_QLJfI = str_replace ("ELEMENT1NAME", "$_GET[Element1Name]", $_QLJfI);
  if(isset($_GET["formElement2"])) {
     $_QLJfI = str_replace ("FORM2FIELD", "$_GET[formElement2]", $_QLJfI);
     $_QLJfI = str_replace ("ELEMENT2NAME", "$_GET[Element2Name]", $_QLJfI);
  }

  $_QLJfI = str_replace ('name="cmbUploaderUrl"', 'value="'.BasePath.'ckeditor/filemanager/connectors/php/upload.php"', $_QLJfI);

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

  SetHTMLHeaders($_QLo06);

  print $_QLJfI;

?>

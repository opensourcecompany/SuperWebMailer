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

  if( !isset($_GET["form"]) || !isset($_GET["formElement1"]) )
    exit;

  $_QJCJi = join("", file(_O68QF()."attachmentsupload.htm"));

  $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

  $_QJCJi = str_replace ("'FORMNAME'", "'$_GET[form]'", $_QJCJi);
  $_QJCJi = str_replace ("FORM1FIELD", "$_GET[formElement1]", $_QJCJi);
  $_QJCJi = str_replace ("ELEMENT1NAME", "$_GET[Element1Name]", $_QJCJi);
  if(isset($_GET["formElement2"])) {
     $_QJCJi = str_replace ("FORM2FIELD", "$_GET[formElement2]", $_QJCJi);
     $_QJCJi = str_replace ("ELEMENT2NAME", "$_GET[Element2Name]", $_QJCJi);
  }

  $_QJCJi = str_replace ('name="cmbUploaderUrl"', 'value="'.BasePath.'ckeditor/filemanager/connectors/php/upload.php"', $_QJCJi);

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

  SetHTMLHeaders($_Q6QQL);

  print $_QJCJi;

?>

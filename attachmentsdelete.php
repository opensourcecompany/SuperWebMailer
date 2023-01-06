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

  if( !isset($_GET["form"]) || !isset($_GET["formElement1"]) || !isset($_GET["files"]) )
    exit;

  $_QLJfI = _JJAQE("attachmentsdelete.htm");

  $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

  $_QLJfI = str_replace ("'FORMNAME'", "'$_GET[form]'", $_QLJfI);
  $_QLJfI = str_replace ("FORM1FIELD", "$_GET[formElement1]", $_QLJfI);
  if(isset($_GET["formElement2"]))
     $_QLJfI = str_replace ("FORM2FIELD", "$_GET[formElement2]", $_QLJfI);

  $_QLJfI = str_replace ('name="cmbRemoverUrl"', 'value="'.BasePath.'ckeditor/filemanager/connectors/php/remove_file.php"', $_QLJfI);

  $_QlooO = explode(";", $_GET["files"]);
  if(trim($_GET["files"]) != "") {
      for($_Qli6J=0; $_Qli6J<count($_QlooO); $_Qli6J++) {
        if(!IsUTF8string($_QlooO[$_Qli6J]) )
          $_QlooO[$_Qli6J] = utf8_encode($_QlooO[$_Qli6J]);
        if(strpos($_QlooO[$_Qli6J], "/") !== false)
           $_QlooO[$_Qli6J] = substr($_QlooO[$_Qli6J], strpos_reverse($_QlooO[$_Qli6J], "/"));
        if(strpos($_QlooO[$_Qli6J], "\\") !== false)
           $_QlooO[$_Qli6J] = substr($_QlooO[$_Qli6J], strpos_reverse($_QlooO[$_Qli6J], "\\"));
      }
      $_QLJfI = _L81BJ($_QLJfI, '<SHOW:FILENAME>', '</SHOW:FILENAME>', join("<br />", $_QlooO));
    }
    else
    $_QLJfI = _L81BJ($_QLJfI, '<SHOW:FILENAME>', '</SHOW:FILENAME>', $resourcestrings[$INTERFACE_LANGUAGE]["000094"]);

  $_I016j = join(";", $_QlooO);
  $_QLJfI = str_replace ('name="files"', 'name="files" value="'.$_I016j.'"', $_QLJfI);


  SetHTMLHeaders($_QLo06);

  print $_QLJfI;

?>

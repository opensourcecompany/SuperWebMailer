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

  if( !isset($_GET["form"]) || !isset($_GET["formElement1"]) || !isset($_GET["files"]) )
    exit;

  $_QJCJi = join("", file(_O68QF()."attachmentsdelete.htm"));

  $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

  $_QJCJi = str_replace ("'FORMNAME'", "'$_GET[form]'", $_QJCJi);
  $_QJCJi = str_replace ("FORM1FIELD", "$_GET[formElement1]", $_QJCJi);
  if(isset($_GET["formElement2"]))
     $_QJCJi = str_replace ("FORM2FIELD", "$_GET[formElement2]", $_QJCJi);

  $_QJCJi = str_replace ('name="cmbRemoverUrl"', 'value="'.BasePath.'ckeditor/filemanager/connectors/php/remove_file.php"', $_QJCJi);

  $_Q6LIL = explode(";", $_GET["files"]);
  if(trim($_GET["files"]) != "") {
      for($_Q6llo=0; $_Q6llo<count($_Q6LIL); $_Q6llo++) {
        if(!IsUTF8string($_Q6LIL[$_Q6llo]) )
          $_Q6LIL[$_Q6llo] = utf8_encode($_Q6LIL[$_Q6llo]);
        if(strpos($_Q6LIL[$_Q6llo], "/") !== false)
           $_Q6LIL[$_Q6llo] = substr($_Q6LIL[$_Q6llo], strpos_reverse($_Q6LIL[$_Q6llo], "/"));
        if(strpos($_Q6LIL[$_Q6llo], "\\") !== false)
           $_Q6LIL[$_Q6llo] = substr($_Q6LIL[$_Q6llo], strpos_reverse($_Q6LIL[$_Q6llo], "\\"));
      }
      $_QJCJi = _OPR6L($_QJCJi, '<SHOW:FILENAME>', '</SHOW:FILENAME>', join("<br />", $_Q6LIL));
    }
    else
    $_QJCJi = _OPR6L($_QJCJi, '<SHOW:FILENAME>', '</SHOW:FILENAME>', $resourcestrings[$INTERFACE_LANGUAGE]["000094"]);

  $_QllO8 = join(";", $_Q6LIL);
  $_QJCJi = str_replace ('name="files"', 'name="files" value="'.$_QllO8.'"', $_QJCJi);


  SetHTMLHeaders($_Q6QQL);

  print $_QJCJi;

?>

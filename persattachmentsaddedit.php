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

  if( !isset($_GET["form"]) || !isset($_GET["formElement1"]) )
    exit;

  $_QLJfI = _JJAQE("persattachmentsaddedit.htm");

  #### normal placeholders
  $_QLfol = "SELECT `text`, `fieldname` FROM `$_Ij8oL` WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_I1OoI = array();
  $_jl0Ii = array();
  $_jl0Ii[] = '<option value="[id]">id</option>';
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
   $_jl0Ii[] =  '<option value="['.$_QLO0f["fieldname"].']">'.$_QLO0f["text"].'</option>';
  }
  $_QLJfI = _L81BJ($_QLJfI, "<fieldnames>", "</fieldnames>", join("\r\n", $_jl0Ii));
  mysql_free_result($_QL8i1);
  #

  $_QLJfI = str_replace ("'FORMNAME'", "'$_GET[form]'", $_QLJfI);
  $_QLJfI = str_replace ("FORM1FIELD", "$_GET[formElement1]", $_QLJfI);

  if(!empty($_GET["EditValue"])) {
     $_fCjto = $_GET["EditValue"];
     $_fCjLC = "";
     if(strpos($_fCjto, ";") !== false){
       $_fCjto = substr($_fCjto, 0, strpos($_fCjto, ";"));
       $_fCjLC = trim(substr($_GET["EditValue"], strpos($_GET["EditValue"], ";") + 1));
     }

     $_QLJfI = str_replace ('name="attachmentrule"', 'name="attachmentrule" value="'. $_fCjto .'"', $_QLJfI);
     $_QLJfI = str_replace ('name="visibleattachmentname"', 'name="visibleattachmentname" value="'. $_fCjLC .'"', $_QLJfI);
     $_QLJfI = str_replace ('name="EditValue"', 'name="EditValue" value="'.$_GET["EditValue"].'"', $_QLJfI);
  }

  $_QLJfI = _L81BJ($_QLJfI, "<attachmentsfilepath>", "</attachmentsfilepath>", $_IIlfi);

  SetHTMLHeaders($_QLo06);

  print $_QLJfI;

?>

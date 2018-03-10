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
  include_once("templates.inc.php");
  include_once("sessioncheck.inc.php");

  if( !isset($_GET["form"]) || !isset($_GET["formElement1"]) )
    exit;

  $_QJCJi = join("", file(_O68QF()."mailheaderfieldaddedit.htm"));

  #### normal placeholders
  $_QJlJ0 = "SELECT `text`, `fieldname` FROM `$_Qofjo` WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q8otJ = array();
  $_jQjOO = array();
  $_jQjOO[] = '<option value="[id]">id</option>';
  while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
   $_jQjOO[] =  '<option value="['.$_Q6Q1C["fieldname"].']">'.$_Q6Q1C["text"].'</option>';
  }
  $_QJCJi = _OPR6L($_QJCJi, "<fieldnames>", "</fieldnames>", join("\r\n", $_jQjOO));
  mysql_free_result($_Q60l1);
  #

  $_QJCJi = str_replace ("'FORMNAME'", "'$_GET[form]'", $_QJCJi);
  $_QJCJi = str_replace ("FORM1FIELD", "$_GET[formElement1]", $_QJCJi);

  if(!empty($_GET["EditValue"])) {
     $_QllO8 = explode(":", $_GET["EditValue"]);
     while(count($_QllO8) < 2)
       $_QllO8[] = "";
     $_QJCJi = str_replace ('name="headername"', 'name="headername" value="'.$_QllO8[0].'"', $_QJCJi);
     $_QJCJi = str_replace ('name="headervalue"', 'name="headervalue" value="'.$_QllO8[1].'"', $_QJCJi);
     $_QJCJi = str_replace ('name="EditValue"', 'name="EditValue" value="'.$_GET["EditValue"].'"', $_QJCJi);
  }

  SetHTMLHeaders($_Q6QQL);

  print $_QJCJi;

?>

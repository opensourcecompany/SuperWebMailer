<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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

  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];



  $_Itfj8 = "";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["VariantsOfSubject"], $_Itfj8, 'campaignedit6', 'browse_variantsofemailsubject.htm');

    
  if(isset($_POST["_FORMNAME"]) && $_POST["_FORMNAME"] != "null")
    $_QLJfI = str_replace("'_FORMNAME'", "'".$_POST["_FORMNAME"]."'", $_QLJfI);

  if(isset($_POST["_FORMFIELD"]) && $_POST["_FORMFIELD"] != "null") {
      $_QLJfI = str_replace('._FORMFIELD', ".".$_POST["_FORMFIELD"], $_QLJfI);
      $_QLJfI = str_replace("'_FORMFIELD'", "'".$_POST["_FORMFIELD"]."'", $_QLJfI);
    }

  $_QLJfI = str_replace("%EMailSubjectVariantsSeparator%", EMailSubjectVariantsSeparator, $_QLJfI);


  #### normal placeholders
  $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_I1OoI=array();
  while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
   $_I1OoI[] =  sprintf("new Array('[%s]', '%s')", $_QLO0f["fieldname"], $_QLO0f["text"]);
  }
  # defaults
  foreach ($_Iol8t as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QLJfI = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
  mysql_free_result($_QL8i1);

  print $_QLJfI;
  exit;


?>

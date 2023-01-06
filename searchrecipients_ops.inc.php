<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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


 function _JO668($_jtjl0){

  if($_jtjl0["FindMethod"] < 3){
    $_81O6Q = explode(" ", $_jtjl0["SearchText"]);
  } else
    $_81O6Q = array($_jtjl0["SearchText"]);

  $_jQoQi = array();

  for($_QliOt=0; $_QliOt<count($_81O6Q); $_QliOt++) {
    $_81CoI = array();
    for($_Qli6J=0; $_Qli6J<count($_jtjl0["Fields"]); $_Qli6J++){
       if($_jtjl0["FindMethod"] < 3)
         $_81CoI[] = "`".$_jtjl0["Fields"][$_Qli6J]."`"." LIKE "._LRAFO("%".$_81O6Q[$_QliOt]."%");
         else
         if($_jtjl0["FindMethod"] == 3)
            $_81CoI[] = "`".$_jtjl0["Fields"][$_Qli6J]."`"." LIKE "._LRAFO($_81O6Q[$_QliOt]);
            else
            $_81CoI[] = "`".$_jtjl0["Fields"][$_Qli6J]."`"." REGEXP "._LRAFO($_81O6Q[$_QliOt]);
    }
    $_81CoI = "( ".join(" OR ", $_81CoI)." )";

    $_jQoQi[] = $_81CoI;

  }

  switch ($_jtjl0["FindMethod"]){
     case 1:
             $_jQoQi = join(" OR ", $_jQoQi);
             break;

     default:
             $_jQoQi = join(" AND ", $_jQoQi);
             break;
  }

  return $_jQoQi;

 }


?>

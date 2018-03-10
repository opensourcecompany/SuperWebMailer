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


 function _LO0QD($_Iii1I){

  if($_Iii1I["FindMethod"] < 3){
    $_6t6IC = explode(" ", $_Iii1I["SearchText"]);
  } else
    $_6t6IC = array($_Iii1I["SearchText"]);

  $_I8C10 = array();

  for($_Qf0Ct=0; $_Qf0Ct<count($_6t6IC); $_Qf0Ct++) {
    $_6tfj8 = array();
    for($_Q6llo=0; $_Q6llo<count($_Iii1I["Fields"]); $_Q6llo++){
       if($_Iii1I["FindMethod"] < 3)
         $_6tfj8[] = "`".$_Iii1I["Fields"][$_Q6llo]."`"." LIKE "._OPQLR("%".$_6t6IC[$_Qf0Ct]."%");
         else
         if($_Iii1I["FindMethod"] == 3)
            $_6tfj8[] = "`".$_Iii1I["Fields"][$_Q6llo]."`"." LIKE "._OPQLR($_6t6IC[$_Qf0Ct]);
            else
            $_6tfj8[] = "`".$_Iii1I["Fields"][$_Q6llo]."`"." REGEXP "._OPQLR($_6t6IC[$_Qf0Ct]);
    }
    $_6tfj8 = "( ".join(" OR ", $_6tfj8)." )";

    $_I8C10[] = $_6tfj8;

  }

  switch ($_Iii1I["FindMethod"]){
     case 1:
             $_I8C10 = join(" OR ", $_I8C10);
             break;

     default:
             $_I8C10 = join(" AND ", $_I8C10);
             break;
  }

  return $_I8C10;

 }


?>

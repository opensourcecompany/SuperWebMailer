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

  function utf8_to_windows1255($_8LfQO) {
      $_8Lf6C = "";
      $_8Lfl0 = preg_split("//",$_8LfQO);
      for ($_Qli6J=1; $_Qli6J<count($_8Lfl0)-1; $_Qli6J++) {
          $_jQCjt = ord($_8Lfl0[$_Qli6J]);
          $_8L8t1 = ord($_8Lfl0[$_Qli6J+1]);
          //print ("<p>$_jQCjt $_8L8t1");
          if ($_jQCjt==215) {
              $_8Lf6C .= chr($_8L8t1+80);
              $_Qli6J++;
          }
          elseif ($_jQCjt==214) {
              $_8Lf6C .= chr($_8L8t1+16);
              $_Qli6J++;
          }
          else {
              $_8Lf6C .= $_8Lfl0[$_Qli6J];
          }
      }
      return $_8Lf6C;
  }

?>

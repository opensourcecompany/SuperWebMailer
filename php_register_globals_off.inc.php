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

  if (ini_get('register_globals') == 1){
    // security when register_globals ON remove all global variables
    if (isset($_REQUEST) && is_array($_REQUEST))
       foreach(array_keys($_REQUEST) as $_6J6CI)
        unset($$_6J6CI);
    if (isset($_SESSION) && is_array($_SESSION))
       foreach(array_keys($_SESSION) as $_6J6CI)
        unset($$_6J6CI);
    if (isset($_SERVER) && is_array($_SERVER))
       foreach(array_keys($_SERVER)  as $_6J6CI)
        unset($$_6J6CI);
  }

?>

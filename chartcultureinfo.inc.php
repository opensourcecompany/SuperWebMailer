<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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

function addCultureInfo($_jjfl8){
 global $INTERFACE_LANGUAGE, $MonthNumToMonthName, $_Q6QQL, $INTERFACE_LANGUAGE, $resourcestrings;

  if($INTERFACE_LANGUAGE != "en") {
    $_jj8fI = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    foreach($_jj8fI as $key => $_Q6ClO) {
      $_jj8fI[$key] = '"'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE][$_Q6ClO], $_Q6QQL).'"';
    }

    $_jjt6o = $MonthNumToMonthName;
    foreach($_jjt6o as $key => $_Q6ClO) {
      $_jjt6o[$key] = '"'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE][$_Q6ClO], $_Q6QQL).'"';
    }

    $_jjOJO = ".";
    $_jjojo = ",";

    if(function_exists("localeconv")){
      $_jjofl = localeconv();
      $_jjOJO = $_jjofl["decimal_point"];
      $_jjojo = $_jjofl["thousands_sep"];
    } else{
      if($INTERFACE_LANGUAGE == "de"){
       $_jjOJO = ",";
       $_jjojo = ".";
      }
    }


    $addCultureInfo = '
               CanvasJS.addCultureInfo("'.$INTERFACE_LANGUAGE.'",
               {
                 decimalSeparator: "'.$_jjOJO.'",
                 digitGroupSeparator: "'.$_jjojo.'",

                 days: ['.join(",", $_jj8fI).'],

                 months: ['.join(",", $_jjt6o).'],

                 savePNGText: "'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["SaveAsPNGFile"], $_Q6QQL).'",
                 saveJPGText: "'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["SaveAsJPEGFile"], $_Q6QQL).'",
                 menuText: "'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ChartOptions"], $_Q6QQL).'"
               });
    ';


    $_jjfl8 = str_replace('/* addCultureInfo */', $addCultureInfo, $_jjfl8);

    $_jjfl8 = str_replace('culture: "en"', 'culture: "'.$INTERFACE_LANGUAGE.'"', $_jjfl8);

  }

  return $_jjfl8;

}

?>

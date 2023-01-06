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

function addCultureInfo($_J1jol){
 global $INTERFACE_LANGUAGE, $MonthNumToMonthName, $_QLo06, $INTERFACE_LANGUAGE, $resourcestrings;

  if($INTERFACE_LANGUAGE != "en") {
    $_J1jCO = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    foreach($_J1jCO as $key => $_QltJO) {
      $_J1jCO[$key] = '"'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE][$_QltJO], $_QLo06).'"';
    }

    $_J1Jjl = $MonthNumToMonthName;
    foreach($_J1Jjl as $key => $_QltJO) {
      $_J1Jjl[$key] = '"'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE][$_QltJO], $_QLo06).'"';
    }

    $_J1Jo8 = ".";
    $_J16tj = ",";

    if(function_exists("localeconv")){
      $_J16l1 = localeconv();
      $_J1Jo8 = $_J16l1["decimal_point"];
      $_J16tj = $_J16l1["thousands_sep"];
    } else{
      if($INTERFACE_LANGUAGE == "de"){
       $_J1Jo8 = ",";
       $_J16tj = ".";
      }
    }


    $addCultureInfo = '
               CanvasJS.addCultureInfo("'.$INTERFACE_LANGUAGE.'",
               {
                 decimalSeparator: "'.$_J1Jo8.'",
                 digitGroupSeparator: "'.$_J16tj.'",

                 days: ['.join(",", $_J1jCO).'],

                 months: ['.join(",", $_J1Jjl).'],

                 savePNGText: "'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["SaveAsPNGFile"], $_QLo06).'",
                 saveJPGText: "'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["SaveAsJPEGFile"], $_QLo06).'",
                 printText: "'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Print"], $_QLo06).'",
                 menuText: "'.unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["ChartOptions"], $_QLo06).'"
               });
    ';


    $_J1jol = str_replace('/* addCultureInfo */', $addCultureInfo, $_J1jol);

    $_J1jol = str_replace('culture: "en"', 'culture: "'.$INTERFACE_LANGUAGE.'"', $_J1jol);

    $_J1fCQ = "";
    if($INTERFACE_LANGUAGE != "en"){
      $_J1fCQ = "<style>.canvasjs-chart-toolbar div{min-width: 180px;}</style>";
    }

    if(stripos($_J1jol, '</head>') !== false)
      $_J1jol = str_replace('</head>', $_J1fCQ.'</head>', $_J1jol);
      else
      $_J1jol = $_J1fCQ . $_J1jol;

  }

  return $_J1jol;

}

?>

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

// PHP >=5.3.1

define('GEO_BASE_PATH', realpath(dirname(__FILE__)));
define('GEOIP2', 1);

#set_include_path(get_include_path().PATH_SEPARATOR.GEO_BASE_PATH.'/geoip');


function geopip2_autoload ($_J6O1O) {
   if($_J6O1O !== "JsonSerializable"){ // compat for PHP < 5.4, don't load JsonSerializable itself
       include(GEO_BASE_PATH . "/geoip/" . str_replace("\\", "/", $_J6O1O) . ".php");
     }
}
spl_autoload_register("geopip2_autoload");

# doesn't work on unix systems
#spl_autoload_extensions('.php');
#spl_autoload_register();

use GeoIp2\Database\Reader;

function _OCEED($_jt8LJ){
  try {
    return new Reader($_jt8LJ);
  } catch (Exception $_Qot0C) {
    return false;
  }
}

// Object for old GeoIP API
class GeoIP2_Location
{

    // public
    var $countryCode;
    var $countryName;
    var $region;
    var $city;
    var $postalCode;
    var $latitude;
    var $longitude;
    var $areaCode;
    var $dmaCode;

}


?>

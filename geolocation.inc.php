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

  include_once("config.inc.php");
  include_once(PEAR_PATH . "PEAR_.php");
  include_once(PEAR_PATH . "GeoIP.php");

  if(version_compare(PHP_VERSION, "5.3.1", ">="))
    include_once("geolocation2.inc.php");


  class _LBPJO{

    // @public
    var $GeoLiteCityExists = false;
    var $GeoLiteCity2Exists = false;

    // @private
    var $_6tQ8Q = "";
    var $_6tIIO = false;
    var $_6tj1o = false;

    /**
     * Construct a GeoLocation instance.
     * @param string $_6JL81 Path to geoip database(s).
     */
    function __construct($_6JL81){

     $this->GeoLiteCityExists = false;
     $this->GeoLiteCity2Exists = false;

     if(!(substr($_6JL81, strlen($_6JL81) - 1) == "/"))
       $_6JL81 .= "/";

     $this->_6tQ8Q = $_6JL81;

     $_I0lji = @fopen($this->_6tQ8Q.'GeoLiteCity.dat', 'r');
     if($_I0lji){
       $this->GeoLiteCityExists = true;
       fclose($_I0lji);
     }

     if(GEOIP2){
       $_I0lji = @fopen($this->_6tQ8Q.'GeoLite2-City.mmdb', 'r');
       if($_I0lji){
         $this->GeoLiteCity2Exists = true;
         fclose($_I0lji);
       }
     }

    }

    function _LBPJO($_6JL81){
      self::__construct($_6JL81);
    }

    function __destruct() {
      $this->_6tIIO = null;
      $this->_6tj1o = null;
    }

    function Openable(){
      if(!$this->GeoLiteCityExists && !$this->GeoLiteCity2Exists) return false;

      if($this->GeoLiteCity2Exists){
        if(!$this->_6tj1o){
           $this->_6tj1o = _LBC01($this->_6tQ8Q.'GeoLite2-City.mmdb');
        }
        return $this->_6tj1o !== false;
      }

      if(!$this->_6tIIO)
         $this->_6tIIO = new Net_GeoIP($this->_6tQ8Q.'GeoLiteCity.dat', 0);
      return $this->_6tIIO->filehandle !== false;

    }

    function GetCountryFromIP($IP){

      if(!$this->GeoLiteCityExists && !$this->GeoLiteCity2Exists) return "UNKNOWN_COUNTRY";


      if($this->GeoLiteCity2Exists){
        if(!$this->_6tj1o){
           $this->_6tj1o = _LBC01($this->_6tQ8Q.'GeoLite2-City.mmdb');
        }
        if(!$this->_6tj1o) return "UNKNOWN_COUNTRY";
        try{
          $_6tJ00 = $this->_6tj1o->city($IP);
        } catch (Exception $_IjO6t) {
          return "UNKNOWN_COUNTRY";
        }
        $_Iiloo = $_6tJ00->country->name;
        if($_Iiloo == "")
          $_Iiloo = $_6tJ00->country->isoCode;
        if($_Iiloo == "")
          $_Iiloo = "UNKNOWN_COUNTRY";
        return $_Iiloo;
      }

      if(strpos($IP, ":") !== false) return "UNKNOWN_COUNTRY"; // no ipv6 Support for old database

      if(!$this->_6tIIO)
         $this->_6tIIO = new Net_GeoIP($this->_6tQ8Q.'GeoLiteCity.dat', 0);

      if($this->_6tIIO) {
          $_6tJfO = $this->_6tIIO->lookupLocation($IP);
          if(IsPEARError($_6tJfO) || $_6tJfO == null ) {
            $_Iiloo = "UNKNOWN_COUNTRY";
          } else {
            $_Iiloo = $_6tJfO->countryName;
            if($_Iiloo == "")
              $_Iiloo = $_6tJfO->countryCode;
            if($_Iiloo == "")
               $_Iiloo = "UNKNOWN_COUNTRY";
          }
          return $_Iiloo;
      } else
        return "UNKNOWN_COUNTRY";
    }

    /*
      return ->city , ->latitude, ->longitude
    */
    function lookupLocation($IP){
      if(!$this->GeoLiteCityExists && !$this->GeoLiteCity2Exists) return null;


      if($this->GeoLiteCity2Exists){
        if(!$this->_6tj1o){
           $this->_6tj1o = _LBC01($this->_6tQ8Q.'GeoLite2-City.mmdb');
        }
        if(!$this->_6tj1o) return null;
        try{
          $_6tJ00 = $this->_6tj1o->city($IP);
        } catch (Exception $_IjO6t) {
          return null;
        }

        $_6tJ8i = new GeoIP2_Location();
        $_6tJ8i->countryCode = $_6tJ00->country->isoCode;
        $_6tJ8i->countryName = $_6tJ00->country->name;
        $_6tJ8i->region = $_6tJ00->mostSpecificSubdivision->name;
        $_6tJ8i->city = $_6tJ00->city->name;
        $_6tJ8i->postalCode = $_6tJ00->postal->code;
        $_6tJ8i->latitude = $_6tJ00->location->latitude;
        $_6tJ8i->longitude = $_6tJ00->location->longitude;
        $_6tJ8i->areaCode = "";
        $_6tJ8i->dmaCode = "";

        return $_6tJ8i;
      }


      if(strpos($IP, ":") !== false) return null; // no ipv6 Support for old database

      if(!$this->_6tIIO)
         $this->_6tIIO = new Net_GeoIP($this->_6tQ8Q.'GeoLiteCity.dat', 0);

      if($this->_6tIIO) {
         $_6tJfO = $this->_6tIIO->lookupLocation($IP);
         if(IsPEARError($_6tJfO))
           return null;
           else
           return $_6tJfO;
      } else
        return null;

    }

  }

?>

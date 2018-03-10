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
  $_J66tC = "./PEAR/";
  if(!@include_once($_J66tC."PEAR_.php")){
    $_J66tC = InstallPath."PEAR/";
    include_once($_J66tC."PEAR_.php");
  }
  include_once($_J66tC."GeoIP.php");

  if(version_compare(PHP_VERSION, "5.3.1", ">="))
    include_once("geolocation2.inc.php");


  class _OCB1C{

    // @public
    var $GeoLiteCityExists = false;
    var $GeoLiteCity2Exists = false;

    // @private
    var $_J680L = "";
    var $_J68fj = false;
    var $_J6tQt = false;

    /**
     * Construct a GeoLocation instance.
     * @param string $_JICij Path to geoip database(s).
     */
    function __construct($_JICij){

     $this->GeoLiteCityExists = false;
     $this->GeoLiteCity2Exists = false;

     if(!(substr($_JICij, strlen($_JICij) - 1) == "/"))
       $_JICij .= "/";

     $this->_J680L = $_JICij;

     $_QfC8t = @fopen($this->_J680L.'GeoLiteCity.dat', 'r');
     if($_QfC8t){
       $this->GeoLiteCityExists = true;
       fclose($_QfC8t);
     }

     if(GEOIP2){
       $_QfC8t = @fopen($this->_J680L.'GeoLite2-City.mmdb', 'r');
       if($_QfC8t){
         $this->GeoLiteCity2Exists = true;
         fclose($_QfC8t);
       }
     }

    }

    function _OCB1C($_JICij){
      self::__construct($_JICij);
    }

    function __destruct() {
      $this->_J68fj = null;
      $this->_J6tQt = null;
    }

    function Openable(){
      if(!$this->GeoLiteCityExists && !$this->GeoLiteCity2Exists) return false;

      if($this->GeoLiteCity2Exists){
        if(!$this->_J6tQt){
           $this->_J6tQt = _OCEED($this->_J680L.'GeoLite2-City.mmdb');
        }
        return $this->_J6tQt !== false;
      }

      if(!$this->_J68fj)
         $this->_J68fj = new Net_GeoIP($this->_J680L.'GeoLiteCity.dat', 0);
      return $this->_J68fj->filehandle !== false;

    }

    function GetCountryFromIP($IP){

      if(!$this->GeoLiteCityExists && !$this->GeoLiteCity2Exists) return "UNKNOWN_COUNTRY";


      if($this->GeoLiteCity2Exists){
        if(!$this->_J6tQt){
           $this->_J6tQt = _OCEED($this->_J680L.'GeoLite2-City.mmdb');
        }
        if(!$this->_J6tQt) return "UNKNOWN_COUNTRY";
        try{
          $_J6tjl = $this->_J6tQt->city($IP);
        } catch (Exception $_Qot0C) {
          return "UNKNOWN_COUNTRY";
        }
        $_IJQOL = $_J6tjl->country->name;
        if($_IJQOL == "")
          $_IJQOL = $_J6tjl->country->isoCode;
        if($_IJQOL == "")
          $_IJQOL = "UNKNOWN_COUNTRY";
        return $_IJQOL;
      }

      if(strpos($IP, ":") !== false) return "UNKNOWN_COUNTRY"; // no ipv6 Support for old database

      if(!$this->_J68fj)
         $this->_J68fj = new Net_GeoIP($this->_J680L.'GeoLiteCity.dat', 0);

      if($this->_J68fj) {
          $_J6t8o = $this->_J68fj->lookupLocation($IP);
          if(IsPEARError($_J6t8o) || $_J6t8o == null ) {
            $_IJQOL = "UNKNOWN_COUNTRY";
          } else {
            $_IJQOL = $_J6t8o->countryName;
            if($_IJQOL == "")
              $_IJQOL = $_J6t8o->countryCode;
            if($_IJQOL == "")
               $_IJQOL = "UNKNOWN_COUNTRY";
          }
          return $_IJQOL;
      } else
        return "UNKNOWN_COUNTRY";
    }

    /*
      return ->city , ->latitude, ->longitude
    */
    function lookupLocation($IP){
      if(!$this->GeoLiteCityExists && !$this->GeoLiteCity2Exists) return null;


      if($this->GeoLiteCity2Exists){
        if(!$this->_J6tQt){
           $this->_J6tQt = _OCEED($this->_J680L.'GeoLite2-City.mmdb');
        }
        if(!$this->_J6tQt) return null;
        try{
          $_J6tjl = $this->_J6tQt->city($IP);
        } catch (Exception $_Qot0C) {
          return null;
        }

        $_J6tLi = new GeoIP2_Location();
        $_J6tLi->countryCode = $_J6tjl->country->isoCode;
        $_J6tLi->countryName = $_J6tjl->country->name;
        $_J6tLi->region = $_J6tjl->mostSpecificSubdivision->name;
        $_J6tLi->city = $_J6tjl->city->name;
        $_J6tLi->postalCode = $_J6tjl->postal->code;
        $_J6tLi->latitude = $_J6tjl->location->latitude;
        $_J6tLi->longitude = $_J6tjl->location->longitude;
        $_J6tLi->areaCode = "";
        $_J6tLi->dmaCode = "";

        return $_J6tLi;
      }


      if(strpos($IP, ":") !== false) return null; // no ipv6 Support for old database

      if(!$this->_J68fj)
         $this->_J68fj = new Net_GeoIP($this->_J680L.'GeoLiteCity.dat', 0);

      if($this->_J68fj) {
         $_J6t8o = $this->_J68fj->lookupLocation($IP);
         if(IsPEARError($_J6t8o))
           return null;
           else
           return $_J6t8o;
      } else
        return null;

    }

  }

?>

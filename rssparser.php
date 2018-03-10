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

class _LQPCJ {

   //@public
        var $RSSParseError = "";
        var $XMLAvailable = false;
        var $MaxEntries = 20;
        var $channel = array();

   //@private
        var $_6t0fQ = false;
        var $_6t0of = false;
        var $_6t1Qi = false;
        var $_6t1JO = false;
        var $_6t1C0 = "";

        var $_6tQOJ = "";
        var $_6tIjJ = -1;

        // constructor
       /* function __construct() {
          $this->_LQPCJ();
        } */

        function __construct() {
         $this->XMLAvailable = function_exists("xml_parser_create");
        }

        function _LQPCJ() {
          self::__construct();
        }

        // destructor
        function __destruct() {
        }

   //@public
        function _LQPEA($_jt8LJ) {
           $this->RSSParseError = "";
           $this->_6t0fQ = false;
           $this->_6t0of = false;
           $this->_6t1Qi = false;
           $this->_6t1JO = false;
           $this->_6t1C0 = "";
           $this->_6tQOJ = "";
           $this->channel = array();
           $this->_6tIjJ = -1;

           $_6tIil = xml_parser_create(''); // '' PHP bug
           xml_set_object($_6tIil, $this);
           xml_set_element_handler($_6tIil, "RSSstartElement", "RSSendElement");
           xml_set_character_data_handler($_6tIil, "characterData");
           xml_parser_set_option($_6tIil, XML_OPTION_TARGET_ENCODING, "UTF-8").
           xml_parser_set_option($_6tIil, XML_OPTION_SKIP_WHITE, 1);
           $_QCioi = @fopen($_jt8LJ,"rb");
           if($_QCioi) {
             while ($_Qf1i1 = fread($_QCioi, 4096)) {
                 if(!xml_parse($_6tIil, $_Qf1i1, feof($_QCioi)) ) {
                         $this->RSSParseError = sprintf("XML error: %s at line %d",
                                 xml_error_string(xml_get_error_code($_6tIil)),
                                 xml_get_current_line_number($_6tIil));
                        break;
                 }
                 if(!$this->_6t0fQ && $this->_6tIjJ + 1 >= $this->MaxEntries)
                    break;
             }
             fclose($_QCioi);
           } else{
             $this->RSSParseError = "Can't open $_jt8LJ.";
           }
           xml_parser_free($_6tIil);
           return $this->RSSParseError == "";
        }

   //@public
        function _LQA0E($_6tjQI) {
           $this->RSSParseError = "";
           $this->_6t0fQ = false;
           $this->_6t0of = false;
           $this->_6t1Qi = false;
           $this->_6t1JO = false;
           $this->_6t1C0 = "";
           $this->_6tQOJ = "";
           $this->channel = array();
           $this->_6tIjJ = -1;
           $_6tIil = xml_parser_create(''); // '' PHP bug
           xml_set_object($_6tIil, $this);
           xml_set_element_handler($_6tIil, "RSSstartElement", "RSSendElement");
           xml_set_character_data_handler($_6tIil, "characterData");
           xml_parser_set_option($_6tIil, XML_OPTION_TARGET_ENCODING, "UTF-8").
           xml_parser_set_option($_6tIil, XML_OPTION_SKIP_WHITE, 1);
           if(!xml_parse($_6tIil, $_6tjQI, true) ) {
                   $this->RSSParseError = sprintf("XML error: %s at line %d",
                           xml_error_string(xml_get_error_code($_6tIil)),
                           xml_get_current_line_number($_6tIil));
           }
           xml_parser_free($_6tIil);

           // remove entries to reach MaxEntries
           if( $this->RSSParseError == "" && count($this->channel["ITEMS"]) > $this->MaxEntries ) {
             for($_Q6llo=count($this->channel["ITEMS"]) - 1; count($this->channel["ITEMS"]) > $this->MaxEntries; $_Q6llo--){
                if(array_pop($this->channel["ITEMS"]) == NULL) break;
             }
           }
           return $this->RSSParseError == "";
        }

   //@private
        function RSSstartElement($_IJ8oI, $_6tjI6, $_6tjjj) {
                if(!$this->_6t0fQ && $_6tjI6 != "CHANNEL" && $this->_6tIjJ + 1 >= $this->MaxEntries)
                   return false;
                if ($this->_6t0fQ) {
                    $this->_6t1C0 = $_6tjI6;
                    if($_6tjI6 == "GUID")
                       if(isset($_6tjjj["ISPERMALINK"])) {
                         $this->_6tQOJ = $_6tjjj["ISPERMALINK"];
                       }
                    if ($_6tjI6 == "ENCLOSURE") {
                      $this->_6t1JO = true;
                      reset($_6tjjj);
                      foreach($_6tjjj as $key => $_Q6ClO){
                         if(!isset($this->channel["ITEMS"][$this->_6tIjJ][$this->_6t1C0][$key]))
                          $this->channel["ITEMS"][$this->_6tIjJ][$this->_6t1C0][$key] = $_Q6ClO;
                         else
                          $this->channel["ITEMS"][$this->_6tIjJ][$this->_6t1C0][$key] .= $_Q6ClO;
                      }
                    }
                } elseif ($_6tjI6 == "ITEM") {
                    $this->_6t0fQ = true;
                    $this->_6tIjJ++;
                    if(!isset($this->channel["ITEMS"]))
                      $this->channel["ITEMS"] = array();
                    $this->channel["ITEMS"][$this->_6tIjJ] = array();
                } elseif ($_6tjI6 == "CHANNEL") {
                    $this->_6t0of = true;
                    $this->_6t1C0 = $_6tjI6;
                } elseif ($_6tjI6 == "IMAGE") {
                    $this->_6t1Qi = true;
                    $this->_6t1C0 = $_6tjI6;
                }
                elseif ($this->_6t0of)
                   $this->_6t1C0 = $_6tjI6;
        }

   //@private
        function RSSendElement($_IJ8oI, $_6tjI6) {
                if(!$this->_6t0fQ && $_6tjI6 != "CHANNEL" && $this->_6tIjJ + 1 >= $this->MaxEntries)
                   return false;
                if($_6tjI6 == "GUID");
                   $this->_6tQOJ = "";
                if ($_6tjI6 == "ITEM") {
                  $this->_6t0fQ = false;
                } elseif($_6tjI6 == "CHANNEL")
                   $this->_6t0of = false;
                 elseif($_6tjI6 == "IMAGE")
                   $this->_6t1Qi = false;
                 elseif($_6tjI6 == "ENCLOSURE")
                   $this->_6t1JO = false;
                 else
                  $this->_6t1C0 = "";
        }

   //@private
        function characterData($_IJ8oI, $_Qf1i1) {
             if( $this->_6t1C0 == "ENCLOSURE" ) return;
             if ($this->_6t0fQ && $this->_6t1C0 != "" && $this->_6t1C0 != "ITEM") {
                if(!isset($this->channel["ITEMS"][$this->_6tIjJ][$this->_6t1C0]))
                  $this->channel["ITEMS"][$this->_6tIjJ][$this->_6t1C0] = $_Qf1i1;
                  else
                  {
                    $_6tJI0 = "";
                    if($this->_6t1C0 == "CATEGORY")
                      $_6tJI0 = ", ";
                    $this->channel["ITEMS"][$this->_6tIjJ][$this->_6t1C0] .= $_6tJI0.$_Qf1i1;
                  }
                if($this->_6t1C0 == "GUID" && $this->_6tQOJ != "")
                  $this->channel["ITEMS"][$this->_6tIjJ]["ISPERMALINK"] = $this->_6tQOJ;
             }
             else
              if($this->_6t0of && $this->_6t1C0 != "" && $this->_6t1C0 != "CHANNEL" && !$this->_6t1Qi){
                if(!isset($this->channel[$this->_6t1C0]))
                 $this->channel[$this->_6t1C0] = $_Qf1i1;
                else
                 $this->channel[$this->_6t1C0] .= $_Qf1i1;
              } else if($this->_6t1Qi && $this->_6t1C0 == "IMAGE") {
                 $this->channel[$this->_6t1C0] = array(); // new image
              } else if($this->_6t1Qi && $this->_6t1C0 != "" && $this->_6t1C0 != "IMAGE") {
                 if(!isset($this->channel["IMAGE"][$this->_6t1C0]))
                    $this->channel["IMAGE"][$this->_6t1C0] = $_Qf1i1;
                    else
                    $this->channel["IMAGE"][$this->_6t1C0] .= $_Qf1i1;
              }
        }
}

/*$rss_parser = new _LQPCJ();
$rss_parser->_LQPEA("/rsstest.xml");
print_r($rss_parser->channel); */

?>


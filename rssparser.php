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

class _JQE86 {

   //@public
        var $RSSParseError = "";
        var $XMLAvailable = false;
        var $MaxEntries = 20;
        var $channel = array();

   //@private
        var $_81jQt = false;
        var $_81jO8 = false;
        var $_81jLC = false;
        var $_81J66 = false;
        var $_6OjC0 = "";

        var $_816J1 = "";
        var $_8166j = -1;

        // constructor
       /* function __construct() {
          $this->_JQE86();
        } */

        function __construct() {
         $this->XMLAvailable = function_exists("xml_parser_create");
        }

        function _JQE86() {
          self::__construct();
        }

        // destructor
        function __destruct() {
        }

   //@public
        function _JQF0F($_JfIIf) {
           $this->RSSParseError = "";
           $this->_81jQt = false;
           $this->_81jO8 = false;
           $this->_81jLC = false;
           $this->_81J66 = false;
           $this->_6OjC0 = "";
           $this->_816J1 = "";
           $this->channel = array();
           $this->_8166j = -1;

           $_81fIt = xml_parser_create(''); // '' PHP bug
           xml_set_object($_81fIt, $this);
           xml_set_element_handler($_81fIt, "RSSstartElement", "RSSendElement");
           xml_set_character_data_handler($_81fIt, "characterData");
           xml_parser_set_option($_81fIt, XML_OPTION_TARGET_ENCODING, "UTF-8").
           xml_parser_set_option($_81fIt, XML_OPTION_SKIP_WHITE, 1);
           $_I60fo = @fopen($_JfIIf,"rb");
           if($_I60fo) {
             while ($_I0QjQ = fread($_I60fo, 4096)) {
                 if(!xml_parse($_81fIt, $_I0QjQ, feof($_I60fo)) ) {
                         $this->RSSParseError = sprintf("XML error: %s at line %d",
                                 xml_error_string(xml_get_error_code($_81fIt)),
                                 xml_get_current_line_number($_81fIt));
                        break;
                 }
                 if(!$this->_81jQt && $this->_8166j + 1 >= $this->MaxEntries)
                    break;
             }
             fclose($_I60fo);
           } else{
             $this->RSSParseError = "Can't open $_JfIIf.";
           }
           xml_parser_free($_81fIt);
           return $this->RSSParseError == "";
        }

   //@public
        function _JQF8J($_81f8C) {
           $this->RSSParseError = "";
           $this->_81jQt = false;
           $this->_81jO8 = false;
           $this->_81jLC = false;
           $this->_81J66 = false;
           $this->_6OjC0 = "";
           $this->_816J1 = "";
           $this->channel = array();
           $this->_8166j = -1;
           $_81fIt = xml_parser_create(''); // '' PHP bug
           xml_set_object($_81fIt, $this);
           xml_set_element_handler($_81fIt, "RSSstartElement", "RSSendElement");
           xml_set_character_data_handler($_81fIt, "characterData");
           xml_parser_set_option($_81fIt, XML_OPTION_TARGET_ENCODING, "UTF-8").
           xml_parser_set_option($_81fIt, XML_OPTION_SKIP_WHITE, 1);
           if(!xml_parse($_81fIt, $_81f8C, true) ) {
                   $this->RSSParseError = sprintf("XML error: %s at line %d",
                           xml_error_string(xml_get_error_code($_81fIt)),
                           xml_get_current_line_number($_81fIt));
           }
           xml_parser_free($_81fIt);

           // remove entries to reach MaxEntries
           if( $this->RSSParseError == "" && count($this->channel["ITEMS"]) > $this->MaxEntries ) {
             for($_Qli6J=count($this->channel["ITEMS"]) - 1; count($this->channel["ITEMS"]) > $this->MaxEntries; $_Qli6J--){
                if(array_pop($this->channel["ITEMS"]) == NULL) break;
             }
           }
           return $this->RSSParseError == "";
        }

   //@private
        function RSSstartElement($_IL6Jt, $_81fCl, $_81868) {
                if(!$this->_81jQt && $_81fCl != "CHANNEL" && $this->_8166j + 1 >= $this->MaxEntries)
                   return false;
                if ($this->_81jQt) {
                    $this->_6OjC0 = $_81fCl;
                    if($_81fCl == "GUID")
                       if(isset($_81868["ISPERMALINK"])) {
                         $this->_816J1 = $_81868["ISPERMALINK"];
                       }
                    if ($_81fCl == "ENCLOSURE") {
                      $this->_81J66 = true;
                      reset($_81868);
                      foreach($_81868 as $key => $_QltJO){
                         if(!isset($this->channel["ITEMS"][$this->_8166j][$this->_6OjC0][$key]))
                          $this->channel["ITEMS"][$this->_8166j][$this->_6OjC0][$key] = $_QltJO;
                         else
                          $this->channel["ITEMS"][$this->_8166j][$this->_6OjC0][$key] .= $_QltJO;
                      }
                    }
                } elseif ($_81fCl == "ITEM") {
                    $this->_81jQt = true;
                    $this->_8166j++;
                    if(!isset($this->channel["ITEMS"]))
                      $this->channel["ITEMS"] = array();
                    $this->channel["ITEMS"][$this->_8166j] = array();
                } elseif ($_81fCl == "CHANNEL") {
                    $this->_81jO8 = true;
                    $this->_6OjC0 = $_81fCl;
                } elseif ($_81fCl == "IMAGE") {
                    $this->_81jLC = true;
                    $this->_6OjC0 = $_81fCl;
                }
                elseif ($this->_81jO8)
                   $this->_6OjC0 = $_81fCl;
        }

   //@private
        function RSSendElement($_IL6Jt, $_81fCl) {
                if(!$this->_81jQt && $_81fCl != "CHANNEL" && $this->_8166j + 1 >= $this->MaxEntries)
                   return false;
                if($_81fCl == "GUID");
                   $this->_816J1 = "";
                if ($_81fCl == "ITEM") {
                  $this->_81jQt = false;
                } elseif($_81fCl == "CHANNEL")
                   $this->_81jO8 = false;
                 elseif($_81fCl == "IMAGE")
                   $this->_81jLC = false;
                 elseif($_81fCl == "ENCLOSURE")
                   $this->_81J66 = false;
                 else
                  $this->_6OjC0 = "";
        }

   //@private
        function characterData($_IL6Jt, $_I0QjQ) {
             if( $this->_6OjC0 == "ENCLOSURE" ) return;
             if ($this->_81jQt && $this->_6OjC0 != "" && $this->_6OjC0 != "ITEM") {
                if(!isset($this->channel["ITEMS"][$this->_8166j][$this->_6OjC0]))
                  $this->channel["ITEMS"][$this->_8166j][$this->_6OjC0] = $_I0QjQ;
                  else
                  {
                    $_81888 = "";
                    if($this->_6OjC0 == "CATEGORY")
                      $_81888 = ", ";
                    $this->channel["ITEMS"][$this->_8166j][$this->_6OjC0] .= $_81888.$_I0QjQ;
                  }
                if($this->_6OjC0 == "GUID" && $this->_816J1 != "")
                  $this->channel["ITEMS"][$this->_8166j]["ISPERMALINK"] = $this->_816J1;
             }
             else
              if($this->_81jO8 && $this->_6OjC0 != "" && $this->_6OjC0 != "CHANNEL" && !$this->_81jLC){
                if(!isset($this->channel[$this->_6OjC0]))
                 $this->channel[$this->_6OjC0] = $_I0QjQ;
                else
                 $this->channel[$this->_6OjC0] .= $_I0QjQ;
              } else if($this->_81jLC && $this->_6OjC0 == "IMAGE") {
                 $this->channel[$this->_6OjC0] = array(); // new image
              } else if($this->_81jLC && $this->_6OjC0 != "" && $this->_6OjC0 != "IMAGE") {
                 if(!isset($this->channel["IMAGE"][$this->_6OjC0]))
                    $this->channel["IMAGE"][$this->_6OjC0] = $_I0QjQ;
                    else
                    $this->channel["IMAGE"][$this->_6OjC0] .= $_I0QjQ;
              }
        }
}

/*$rss_parser = new _JQE86();
$rss_parser->_JQF0F("/rsstest.xml");
print_r($rss_parser->channel); */

?>


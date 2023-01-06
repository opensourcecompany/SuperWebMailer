<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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
 include_once("mailinglistq.inc.php");

 $_8JoIt = array('0049', '0043', '0041');
 $_8Jo8I = array ( "0151",
                          "0152",
                          "0157",
                          "0159",
                          "0160",
                          "0161",
                          "0162",
                          "0163",
                          "0164",
                          "0170",
                          "0171",
                          "0172",
                          "0173",
                          "0174",
                          "0175",
                          "0176",
                          "0177",
                          "0178",
                          "0179"
                        );
 $_8Joti = array (
                          "0650",
                          "0660",
                          "0663",
                          "0664",
                          "0676",
                          "0680",
                          "0681",
                          "0688",
                          "0699"
                         );

 $_8JoCj = array ( "076",
                          "077",
                          "078",
                          "079",
                         );

 function _JLODC(&$_JJ6Q6, &$_IoLOO) {
    global $_8JoIt, $_8Jo8I, $_8JoCj, $_8Joti;
    global $INTERFACE_LANGUAGE, $resourcestrings;

    // kein +!
    $_JJ6Q6 = _LRD81(' ', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('-', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('/', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81("\\", '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('*', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('?', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('"', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81("'", '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('´', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('`', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('(', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81(')', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81('[', '', $_JJ6Q6);
    $_JJ6Q6 = _LRD81(']', '', $_JJ6Q6);

    if (substr($_JJ6Q6, 0, 2) != "00") { //0049

      if ( # 179

            !(array_search("0".substr($_JJ6Q6, 0, 3), $_8Jo8I) === false)

         )
            $_JJ6Q6 = "0049".$_JJ6Q6;

      if ( # 0179

            !(array_search(substr($_JJ6Q6, 0, 4), $_8Jo8I) === false)

         )
            $_JJ6Q6 = "0049".substr($_JJ6Q6, 1);

      if (substr($_JJ6Q6, 0, 1) == "+") { //+49
         $_JJ6Q6 = _LRD81('+', '00', $_JJ6Q6);
      }

      if (substr($_JJ6Q6, 0, 2) == "04") { //04915...
         $_JJ6Q6 = "0".$_JJ6Q6;
      }

      if ( (substr($_JJ6Q6, 0, 1) != "0") && (substr($_JJ6Q6, 0, 1) == "1") ) { //179...
        $_JJ6Q6 = "0049".$_JJ6Q6;
      }

      if ( (substr($_JJ6Q6, 0, 2) != "00") && (substr($_JJ6Q6, 0, 2) == "01") ) { //0179...
        $_JJ6Q6 = "0049".substr($_JJ6Q6, 1);
      }
    }

    if (substr($_JJ6Q6, 0, 2) != "00") { //0049

       if ( # 650

             !(array_search("0".substr($_JJ6Q6, 0, 3), $_8Joti) === false)

          )
             $_JJ6Q6 = "0043".$_JJ6Q6;


       if ( # 0650

             !(array_search(substr($_JJ6Q6, 0, 4), $_8Joti) === false)

          )
             $_JJ6Q6 = "0043".substr($_JJ6Q6, 1);


       if ( # 76

             !(array_search("0".substr($_JJ6Q6, 0, 2), $_8JoCj) === false)

          )
             $_JJ6Q6 = "0041".$_JJ6Q6;

       if ( # 076

             !(array_search(substr($_JJ6Q6, 0, 3), $_8JoCj) === false)

          )
             $_JJ6Q6 = "0041".substr($_JJ6Q6, 1);

    }

    if (substr($_JJ6Q6, 0, 4) == "0049") {
       if ( (!preg_match ('/^[0-9]{14}$/', $_JJ6Q6, $_8JoiO)) && (!preg_match ('/^[0-9]{15}$/', $_JJ6Q6, $_8JoiO)) ) {  //00491791317529
         $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{1}';
         return false;
       } else {
         if(count($_8JoiO) > 1) {
          $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{'.count($_8JoiO).'}';
          return false;
         }
       }

       if (substr($_JJ6Q6, 0, 5) == "00490") {
           $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{2}';;
           return false;
       }

    } else
       if ( (substr($_JJ6Q6, 0, 4) == "0041") || (substr($_JJ6Q6, 0, 4) == "0043") )
       {
         if (substr($_JJ6Q6, 0, 4) == "0041")
            $_6JfQj=13;
            else
            $_6JfQj=14; // AT


         if (substr($_JJ6Q6, 0, 4) == "0043") {
           if ( (!preg_match ('/^[0-9]{14}$/', $_JJ6Q6, $_8JoiO)) && (!preg_match ('/^[0-9]{15}$/', $_JJ6Q6, $_8JoiO)) && (!preg_match ('/^[0-9]{16}$/', $_JJ6Q6, $_8JoiO)) ) {  //00491791317529
             $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{1}';
             return false;
           }  else {
             if(count($_8JoiO) > 1) {
              $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{'.count($_8JoiO).'}';
              return false;
             }
           }
         } else {
           if (!preg_match('/^[0-9]{'.$_6JfQj.'}$/', $_JJ6Q6, $_8JoiO)) {  //0049 179 1317529
             $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{1}';  // $_6JfQj
             return false;
           }  else {
             if(count($_8JoiO) > 1) {
              $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{'.count($_8JoiO).'}'; // $_6JfQj
              return false;
             }
           }
         }

         if (substr($_JJ6Q6, 0, 5) == "00410") {
             $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{1}';
             return false;
         }

         if (substr($_JJ6Q6, 0, 5) == "00430") {
             $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{1}';
             return false;
         }


         $key = array_search(substr($_JJ6Q6, 0, 4), $_8JoIt);
         if($key === false) {
           $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000581"], $_JJ6Q6);
           return false;
         }

       } else {


           if (!preg_match('/^[0-9]{1,}$/', $_JJ6Q6, $_8JoiO)) {  //0049 179 1317529
             $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{1}';
             return false;
           }
            else {
            if(count($_8JoiO) > 1) {
             $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_JJ6Q6).'{'.count($_8JoiO).'}';
             return false;
            }
          }

          if (substr($_JJ6Q6, 0, 2) != '00') {
             $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000582"], $_JJ6Q6);
             return false;
           }

          if (strlen($_JJ6Q6) < 7) { // 00490179
             $_IoLOO[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000583"], $_JJ6Q6);
             return false;
          }

       }

   return true;
 }

 function _JLLJE($_8JC8i, $_8JCtQ, $SMSText, $SMSoutUsername, $SMSoutPassword, &$errors) {
   // $SMSText iso-8859-1
   $_J8f6L = new _JLJ0F();

   $_J8f6L->SMSoutUsername = $SMSoutUsername;
   $_J8f6L->SMSoutPassword = $SMSoutPassword;

   if(!$_J8f6L->Login()) {
     $errors[] = $_J8f6L->SMSoutLastErrorNo . " ". $_J8f6L->SMSoutLastErrorString;
     return false;
   }

   if(!$_J8f6L->SendSingleSMS($_8JCtQ, $_8JC8i, "", $SMSText)){
     $errors[] = $_J8f6L->SMSoutLastErrorNo . " ". $_J8f6L->SMSoutLastErrorString;
     return false;
   }

   $errors[] = $_J8f6L->SMSoutLastErrorNo . " ". $_J8f6L->SMSoutLastErrorString;
   return true;
 }


 class _JLJ0F{

  // @public
  var $SMSoutUsername = "";
  var $SMSoutPassword = "";

  var $SMSoutLastErrorNo, $SMSoutLastErrorString;

  // @private
  var $_8Jl1O = false;
  var $_8Jlli = 0x110;
  var $_860oO = "www.smsout.de";
  var $_860Cl = "/client/swm.php";
  var $_86116 = "";

  // constructor
  function __construct() {
  }

  function _JLJ0F() {
    self::__construct();
  }

  // destructor
  function __destruct() {
  }


  // @public
  function IsLoggedIn(){
    return $this->_8Jl1O && $this->_86116 != "";
  }

  // @private
  function _JLJ6P($Action){
    global $_Ij6Lj, $AppName;

    return sprintf("AppName=%s&AppVersion=%s&ClientVersion=%s&Action=%s", urlencode( $AppName ), urlencode( $_Ij6Lj ), urlencode($this->_8Jlli), urlencode($Action));
  }

  // @private
  function _JLJDD($_J60tC,$_I06t6,$_IJL6o,$_I0QjQ,$_jOjI0, $_861Lo, $_J608j, $_6foL1, $_6fCJQ, $_6fi18, &$_JJl1I, &$_J600J) {
      global $_JQjlt;
      $_6fift = false;

      while (true){
        $_6fiLj = "";
        if (empty($_I06t6))
            $_I06t6 = 'GET';
        $_I06t6 = strtoupper($_I06t6);
        $_I60fo = fsockopen($_J60tC, $_J608j, $_JJl1I, $_J600J, 20);
        if(!$_I60fo) {
          $_JJl1I = 600;
          $_J600J = "Can't connect to server.";
          return false;
        }

        if ($_I06t6 == 'GET')
            $_IJL6o .= '?' . $_I0QjQ;
        fputs($_I60fo, "$_I06t6 $_IJL6o HTTP/1.0\r\n");
        fputs($_I60fo, "Host: $_J60tC\r\n");

        if(!empty($_861Lo))
          fputs($_I60fo, "Cookie: $_861Lo\r\n");

        if($_6foL1 && $_6fift){
          fputs($_I60fo, "Authorization: Basic ".base64_encode($_6fCJQ . ":" . $_6fi18)."\r\n");
        }

        if ($_I06t6 == 'POST'){
          fputs($_I60fo, "Content-type: application/x-www-form-urlencoded\r\n");
          fputs($_I60fo, "Content-length: " . strlen($_I0QjQ) . "\r\n");
        }
        if ($_jOjI0)
            fputs($_I60fo, "User-Agent: MSIE\r\n");
            else
            fputs($_I60fo, "User-Agent: $_JQjlt\r\n");
        fputs($_I60fo, "Connection: close\r\n\r\n");
        if ($_I06t6 == 'POST')
           fputs($_I60fo, $_I0QjQ);

        if(function_exists("stream_set_timeout") && function_exists("stream_set_blocking") && function_exists("stream_get_meta_data") )  {
           stream_set_blocking($_I60fo, TRUE);
           stream_set_timeout($_I60fo, 20);
           $_6flLC = stream_get_meta_data($_I60fo);
           while ((!feof($_I60fo)) && (!$_6flLC['timed_out'])) {
            $_6fiLj .= fgets($_I60fo, 128);
            $_6flLC = stream_get_meta_data($_I60fo);
           }
         } else {
           sleep(2);
           while (!feof($_I60fo)) {
            $_6fiLj .= fgets($_I60fo, 128);
           }
         }
        fclose($_I60fo);
        $_6fllJ = trim(substr($_6fiLj, 0, strpos($_6fiLj, " ")));
        $_680O6 = trim(substr($_6fiLj, strpos($_6fiLj, " ")));
        $_J600J = $_680O6;
        $_J600J = substr($_J600J, 0, strpos($_J600J, "\r\n"));
        $_680O6 = trim(substr($_680O6, 0, strpos($_680O6, " ")));
        if($_680O6 != 200)
          $_JJl1I = $_680O6;

        if($_680O6 == 401 && $_6foL1 && !$_6fift){
          $_6fift = true;
          continue;
        } else
          return $_6fiLj;
      }
  }


  // @private
  function _JL6RR($_6fiLj){

    if(!is_array($_6fiLj))
      $_6fiLj = explode("\r\n", $_6fiLj);

    # find body
    $_QlOjt=0;
    for($_Qli6J=0; $_Qli6J<count($_6fiLj); $_Qli6J++){
      if($_6fiLj[$_Qli6J] == "") {
        $_QlOjt = $_Qli6J + 1;
        break;
      }
    }

    $_QL8i1 = array();
    for($_Qli6J=$_QlOjt; $_Qli6J<count($_6fiLj); $_Qli6J++){

      $_QltJO = explode(": ", $_6fiLj[$_Qli6J]);
      if(count($_QltJO) < 2) continue;
      $_QL8i1[$_QltJO[0]] = $_QltJO[1];

    }

    return $_QL8i1;

  }

  // @public
  function Login(){
    global $_JQjlt;
    if($this->_8Jl1O) $this->Logout();

    $_I0QjQ = $this->_JLJ6P("login");
    $_I0QjQ .= sprintf("&Username=%s&Password=%s", urlencode($this->SMSoutUsername), urlencode($this->SMSoutPassword));

    $_6fiLj = $this->_JLJDD($this->_860oO, "POST", $this->_860Cl, $_I0QjQ, 1, $this->_86116, 80, false, "", "", $this->SMSoutLastErrorNo, $this->SMSoutLastErrorString);
    if($_6fiLj == false){
      return false;
    }

    $_6fiLj = explode("\r\n", $_6fiLj);

    for($_Qli6J=0; $_Qli6J<count($_6fiLj); $_Qli6J++){
      if($_QlOjt = stripos($_6fiLj[$_Qli6J], "Set-Cookie:") !== false){
          $_I6tLJ = explode("Set-Cookie: ", $_6fiLj[$_Qli6J]);
          $this->_86116 = $_I6tLJ[1];
          if(strpos($this->_86116, ";") !== false)
             $this->_86116 = substr($this->_86116, 0, strpos($this->_86116, ";"));
          break;
      }
    }

    $_QL8i1 = $this->_JL6RR($_6fiLj);

    $_86Qof = false;
    $this->SMSoutLastErrorNo = "";
    $this->SMSoutLastErrorString = "";

    if(isset($_QL8i1["Return"]) && $_QL8i1["Return"] == "OK")
      $_86Qof = true;
    if(isset($_QL8i1["ErrorCode"]))
      $this->SMSoutLastErrorNo = intval($_QL8i1["ErrorCode"]);
    if(isset($_QL8i1["ErrorText"]))
      $this->SMSoutLastErrorString = utf8_encode( $_QL8i1["ErrorText"] );

    if($_86Qof && empty($this->_86116)) {
      $this->SMSoutLastErrorNo = 999999;
      $this->SMSoutLastErrorString = "No session cookie received.";
      return false;
    }

    $this->_8Jl1O = $_86Qof;

    return $_86Qof;
  }

  // @public
  function Logout(){
    if(!$this->_8Jl1O || empty($this->_86116)) return true;
    $this->_86116 = "";
    $this->_8Jl1O = false;
  }

  // @public
  function SendSingleSMS($SMSType, $SMSTo, $SMSCampaignName, $SMSText){
    global $_JQjlt;

    // $SMSText iso-8859-1
    $_I0QjQ = $this->_JLJ6P("sendsinglesms");
    $_I0QjQ .= sprintf("&SMSTo=%s&SMSType=V%s&SMSText=%s&SMSCampaignName=%s", urlencode($SMSTo), $SMSType, urlencode($SMSText), urlencode($SMSCampaignName));

    $_6fiLj = $this->_JLJDD($this->_860oO, "POST", $this->_860Cl, $_I0QjQ, 1, $this->_86116, 80, false, "", "", $this->SMSoutLastErrorNo, $this->SMSoutLastErrorString);
    if($_6fiLj == false){
      return false;
    }

    $_QL8i1 = $this->_JL6RR($_6fiLj);

    $_86Qof = false;
    $this->SMSoutLastErrorNo = "";
    $this->SMSoutLastErrorString = "";

    if(isset($_QL8i1["Return"]) && $_QL8i1["Return"] == "OK")
      $_86Qof = true;
    if(isset($_QL8i1["ErrorCode"]))
      $this->SMSoutLastErrorNo = intval($_QL8i1["ErrorCode"]);
    if(isset($_QL8i1["ErrorText"]))
      $this->SMSoutLastErrorString = utf8_encode( $_QL8i1["ErrorText"] );

    if($_86Qof && isset($_QL8i1["SMSCosts"])){
      $this->SMSoutLastErrorString = $_QL8i1["SMSCosts"];
    }

#    if(!$_86Qof || $this->SMSoutLastErrorNo != 0) {
#      print $_6fiLj;
#      print "<br /><br /><br /><br />";
#      print_r($_QL8i1);
#      exit;
#    }

    return $_86Qof;

  }

 }

  // @public
  function SendMassSMS($SMSType, $_86It0, $SMSCampaignName){
    global $_JQjlt;

    // $SMSText iso-8859-1
    $_I0QjQ = $this->_JLJ6P("sendmasssms");

    for($_Qli6J=0; $_Qli6J<count($_86It0); $_Qli6J++){
      if(isset($_86It0[$_Qli6J]["Errors"]) && $_86It0[$_Qli6J]["Errors"]) continue;
      $_I0QjQ .= sprintf("&SMSTo[]=%s&SMSType[]=V%s&SMSText[]=%s&SMSCampaignName[]=%s", urlencode($_86It0[$_Qli6J]["u_CellNumber"]), $SMSType, urlencode($_86It0[$_Qli6J]["smstextiso"]), urlencode($SMSCampaignName));
    }

    $_6fiLj = $this->_JLJDD($this->_860oO, "POST", $this->_860Cl, $_I0QjQ, 1, $this->_86116, 80, false, "", "", $this->SMSoutLastErrorNo, $this->SMSoutLastErrorString);
    if($_6fiLj == false){
      return false;
    }

    $_QL8i1 = $this->_JL6RR($_6fiLj);

    $_86Qof = false;
    $this->SMSoutLastErrorNo = "";
    $this->SMSoutLastErrorString = "";

    if(isset($_QL8i1["Return"]) && $_QL8i1["Return"] == "OK")
      $_86Qof = true;
    if(isset($_QL8i1["ErrorCode"]))
      $this->SMSoutLastErrorNo = intval($_QL8i1["ErrorCode"]);
    if(isset($_QL8i1["ErrorText"]))
      $this->SMSoutLastErrorString = utf8_encode( $_QL8i1["ErrorText"] );

    if($_86Qof && isset($_QL8i1["SMSCosts"])){
      $this->SMSoutLastErrorString = $_QL8i1["SMSCosts"];
    }

#    if(!$_86Qof || $this->SMSoutLastErrorNo != 0) {
#      print $_6fiLj;
#      print "<br /><br /><br /><br />";
#      print_r($_QL8i1);
#      exit;
#    }

    return $_86Qof;

  }


?>
